<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class ContentTypeTemplate implements CacheWarmerInterface
{
    protected $contentTypeService;
    protected $templating;
    protected $language;

    public function __construct(ContentTypeService $contentTypeService, EngineInterface $templating)
    {
        $this->contentTypeService = $contentTypeService;
        $this->templating = $templating;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     *
     * @return string
     */
    public function warmUp($cacheDir)
    {
        $destinationPath = $cacheDir . DIRECTORY_SEPARATOR . 'eZCompletion';
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath);
        }

        foreach ($this->getContentTypes() as $contentType) {
            $php = $this->templating->render('eZCompletionBundle::contenttype.php.twig', compact('contentType'));
            /** @var ContentType $contentType */
            $filename = $destinationPath . DIRECTORY_SEPARATOR . $contentType->identifier . '.php';
            file_put_contents($filename, $php);
        }

        return $destinationPath;
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

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional() { return true; }
}