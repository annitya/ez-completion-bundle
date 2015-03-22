<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ContentTypeTemplate
{
    protected $contentTypeService;
    protected $templating;
    protected $cacheDir;
    protected $language;

    public function __construct(ContentTypeService $contentTypeService, EngineInterface $templating, $cacheDir)
    {
        $this->contentTypeService = $contentTypeService;
        $this->templating = $templating;
        $this->cacheDir = $cacheDir;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getDestinationPath()
    {
        return $this->cacheDir . DIRECTORY_SEPARATOR . 'eZCompletion';
    }

    public function generate()
    {
        $destinationPath = $this->getDestinationPath();
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath);
        }

        foreach ($this->getContentTypes() as $contentType) {
            $php = $this->templating->render('eZCompletionBundle::contenttype.php.twig', compact('contentType'));
            /** @var ContentType $contentType */
            $filename = $destinationPath . DIRECTORY_SEPARATOR . $contentType->identifier . '.php';
            file_put_contents($filename, $php);
        }
    }

    /**
     * @return ContentType[]
     */
    public function getContentTypes()
    {
        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();
        $contentTypes = array();
        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = array_merge($contentTypes, $this->contentTypeService->loadContentTypes($contentTypeGroup));
        }
        return $contentTypes;
    }
}