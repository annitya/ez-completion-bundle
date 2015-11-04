<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Persistence\Solr\Content\Search\CriterionVisitor\Field;
use Flageolett\eZCompletionBundle\Traits\LanguageAware;
use Flageolett\eZCompletionBundle\Traits\NameFetcher;

class FieldCompletionFactory
{
    use LanguageAware;
    use NameFetcher;

    protected $contentTypeService;
    protected $contentTypeServiceCompletion;
    protected $languageServiceCompletion;

    public function __construct(ContentTypeService $contentTypeService, ContentTypeServiceCompletion $contentTypeServiceCompletion, LanguageServiceCompletion $languageServiceCompletion)
    {
        $this->contentTypeService;
        $this->contentTypeServiceCompletion = $contentTypeServiceCompletion;
        $this->languageServiceCompletion = $languageServiceCompletion;
    }

    public function attachCompletions(CompletionService $completionService)
    {
        $contentTypes = $this->contentTypeServiceCompletion->getContentTypes();
        foreach ($contentTypes as $contentType) {
            $source = array('field' => $this->buildFieldSource($contentType->fieldDefinitions));

            $fieldValueConfig = $this->buildGetFieldValueConfig($contentType->identifier);
            $fieldCompletion = new FieldCompletion($source);
            $completionService->addCompletionService($fieldCompletion, $fieldValueConfig);
        }
    }

    /**
     * @param FieldDefinition[] $fieldDefinitions
     *
     * @return array
     */
    protected function buildFieldSource($fieldDefinitions)
    {
        return array_map(function(FieldDefinition $fieldDefinition)
        {
            return array(
                'identifier' => $fieldDefinition->identifier,
                'name' => self::getTranslatedName($fieldDefinition, $this->language)
            );
        }, $fieldDefinitions);
    }

    protected function buildGetFieldValueConfig($identifier)
    {
        $config = array('fqn' => '\\eZCompletion\\' . $identifier);
        $fieldSource = array(
            'method' => 'getFieldValue',
            'lookupValue' => 'name',
            'returnValue' => 'identifier'
        );

        $config['sources']['field'] = array($fieldSource);
        return $config;
    }
}