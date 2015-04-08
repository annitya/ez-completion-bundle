<?php

namespace Flageolett\eZCompletionBundle\Service;

class ContentFieldMap
{
    protected $contentTypeTemplate;

    public function __construct(ContentTypeTemplate $contentTypeTemplate)
    {
        $this->contentTypeTemplate = $contentTypeTemplate;
    }

    public function getCompletions()
    {
        $fieldmap = array();
        foreach ($this->contentTypeTemplate->getContentTypes() as $contentType) {
            foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
                $fieldmap[$contentType->identifier][$fieldDefinition->identifier] = get_class($fieldDefinition->defaultValue);
            }
        }

        return $fieldmap;
    }
}