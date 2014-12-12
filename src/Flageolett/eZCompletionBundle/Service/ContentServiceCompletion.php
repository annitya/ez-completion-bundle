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

class ContentServiceCompletion extends CompletionAbstract
{
    /** @var ContentTypeService */
    protected $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    public function getCompletions()
    {
        $types = array(
            'loadContentType' => 0,
            'loadContentTypeByIdentifier' => 0,
            'loadContentTypeByRemoteId' => 0,
            'loadContentTypeGroup' => 0,
            'loadContentTypeGroupByIdentifier' => 0
        );

        $completions = array();
        foreach ($types as $identifier => $parameterIndex) {
            $completions[] = new CompletionContainer($identifier, $parameterIndex, array($this, $identifier));
        }

        $completions[] = new CompletionContainer('loadContentTypeDraft', 0, array($this, 'loadContentType'));

        return $completions;
    }

    public function loadContentType()
    {
        return array_map(function(ContentType $contentType)
        {
            return new Completion($this->getContentTypeName($contentType), $contentType->id);
        }, $this->fetchContentTypes());
    }

    public function loadContentTypeByIdentifier()
    {
        return array_map(function(ContentType $contentType)
        {
            return new Completion($this->getContentTypeName($contentType), $contentType->identifier);
        }, $this->fetchContentTypes());
    }

    public function loadContentTypeByRemoteId()
    {
        return array_map(function (ContentType $contentType) {
            return new Completion($this->getContentTypeName($contentType), $contentType->remoteId);
        }, $this->fetchContentTypes());
    }

    public function loadContentTypeGroup()
    {
        return array_map(function(ContentTypeGroup $contentTypeGroup)
        {
            return new Completion($contentTypeGroup->identifier, $contentTypeGroup->id);
        }, $this->fetchContentTypeGroups());
    }

    public function loadContentTypeGroupByIdentifier()
    {
        return array_map(function(ContentTypeGroup $contentTypeGroup) {
            return new Completion($contentTypeGroup->identifier, $contentTypeGroup->identifier);
        }, $this->fetchContentTypeGroups());
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

    /**
     * @return ContentType[]
     */
    protected function fetchContentTypes()
    {
        $contentTypeGroups = $this->fetchContentTypeGroups();
        $contentTypes = array();
        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = array_merge($contentTypes, $this->contentTypeService->loadContentTypes($contentTypeGroup));
        }

        return $contentTypes;
    }

    protected function fetchContentTypeGroups()
    {
        return $this->contentTypeService->loadContentTypeGroups();
    }

    protected function contentClassGroup()
    {
        $groups = $this->fetchContentTypeGroups();
        return array_map(function (ContentTypeGroup $group) {
            return array(
                'id' => (int)$group->id,
                'identifier' => $group->identifier
            );
        }, $groups);
    }

}
