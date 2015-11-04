<?php

namespace Flageolett\eZCompletionBundle\Service;

class ContentFieldMap
{
    protected $contentTypeServiceCompletion;

    public function __construct(ContentTypeServiceCompletion $contentTypeServiceCompletion)
    {
        $this->contentTypeServiceCompletion = $contentTypeServiceCompletion;
    }

    public function getCompletions()
    {
        $fieldmap = array();
        foreach ($this->contentTypeServiceCompletion->getContentTypes() as $contentType) {
            foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
                $fieldmap[$contentType->identifier][$fieldDefinition->identifier] = get_class($fieldDefinition->defaultValue);
            }
        }

        return $fieldmap;
    }
}