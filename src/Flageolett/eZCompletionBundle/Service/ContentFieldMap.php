<?php

namespace Flageolett\eZCompletionBundle\Service;

use Flageolett\eZCompletionBundle\Traits\LanguageAware;
use Flageolett\eZCompletionBundle\Traits\NameFetcher;

class ContentFieldMap
{
    use LanguageAware;
    use NameFetcher;

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
                $fieldmap[$contentType->identifier][$fieldDefinition->identifier] = array(
                    'name' => $this->getTranslatedName($fieldDefinition, $this->language),
                    'fqn' => get_class($fieldDefinition->defaultValue),
                    'description' => $this->getTranslatedDescription($fieldDefinition, $this->language)
                );

            }
        }

        return $fieldmap;
    }
}