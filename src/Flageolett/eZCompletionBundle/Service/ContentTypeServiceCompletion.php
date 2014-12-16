<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 24.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use \eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\ContentTypeGroup;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;
use Flageolett\eZCompletionBundle\Entity\Completion;
use Flageolett\eZCompletionBundle\Entity\CompletionContainer;

class ContentTypeServiceCompletion extends CompletionAbstract
{
    /** @var ContentTypeService */
    protected $contentTypeService;
    /** @var array */
    protected $config;

    public function __construct(ContentTypeService $contentTypeService, $config)
    {
        $this->contentTypeService = $contentTypeService;
        $this->config = $config;
    }

    public function getCompletions()
    {
        $dataSource = array(
            'contentType' => $this->fetchContentTypes(),
            'contentTypeGroup' => $this->fetchContentTypeGroups()
        );
        $completions = array();
        foreach ($this->config as $type => $completions) {
            foreach ($completions[$type] as $completion) {
                $method = $completion['method'];
                $parameterIndex = $completion['parameterIndex'];
                $completionList = array();
                foreach ($dataSource[$type] as $completionData) {
                    $lookupValue = $completionData[$completion['lookupValue']];
                    $returnValue = $completionData[$completion['returnValue']];
                    $completionList[] = new Completion($lookupValue, $returnValue);
                }
                $completions[] = new CompletionContainer($method, $parameterIndex, $completionList);
            }
        }

        return $completions;
    }

    /**
     * @return ContentType[]
     */
    protected function fetchContentTypes()
    {
        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();
        $contentTypes = array();
        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = array_merge(array_map(function(ContentType $contentType)
            {
                return array(
                    'id' => $contentType->id,
                    'name' => $this->getContentTypeName($contentType),
                    'identifier' => $contentType->identifier,
                    'remoteId' => $contentType->remoteId
                );
            }, $this->contentTypeService->loadContentTypes($contentTypeGroup)), $contentTypes);
        }

        return $contentTypes;
    }

    protected function getContentTypeName(ContentType $contentType)
    {
        $name = $contentType->getName($this->language);
        if (!$name) {
            $names = $contentType->getNames();
            $name = array_shift($names);
        }

        return $name;
    }

    protected function fetchContentTypeGroups()
    {
        return array_map(function(ContentTypeGroup $contentTypeGroup)
        {
            return array(
                'id' => $contentTypeGroup->id,
                'identifier' => $contentTypeGroup->identifier
            );
        }, $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups());
    }
}
