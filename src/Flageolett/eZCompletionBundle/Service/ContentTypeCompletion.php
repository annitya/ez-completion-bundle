<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 24.11.14
 */

namespace Flageolett\ezcompletionbundle\Service;

use \eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\ContentTypeGroup;

class ContentTypeCompletion
{
    /** @var ContentTypeService */
    protected $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    public function getCompletions()
    {
        return array(
            'contentClass' => $this->contentClass(),
            'contentClassGroup' => $this->contentClassGroup()
        );
    }

    protected function getContentClassGroups()
    {
        return $this->contentTypeService->loadContentTypeGroups();
    }

    protected function contentClassGroup()
    {
        $groups = $this->getContentClassGroups();
        return array_map(function (ContentTypeGroup $group) {
            return array(
                'id' => (int)$group->id,
                'identifier' => $group->identifier
            );
        }, $groups);
    }

    protected function contentClass()
    {
        $groups = $this->getContentClassGroups();
        $classList = array();
        foreach ($groups as $group) {
            $contentTypes = $this->contentTypeService->loadContentTypes($group);
            foreach ($contentTypes as $contentType) {
                $classList[] = array(
                    'id' => (int)$contentType->id,
                    'identifier' => $contentType->identifier,
                    'remoteId' => $contentType->remoteId
                );
            }
        }

        return $classList;
    }
}
