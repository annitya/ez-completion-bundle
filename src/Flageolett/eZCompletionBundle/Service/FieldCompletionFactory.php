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
    protected $contentTypeTemplate;
    protected $languageServiceCompletion;

    public function __construct(ContentTypeService $contentTypeService, ContentTypeTemplate $contentTypeTemplate, LanguageServiceCompletion $languageServiceCompletion)
    {
        $this->contentTypeService;
        $this->contentTypeTemplate = $contentTypeTemplate;
        $this->languageServiceCompletion = $languageServiceCompletion;
    }

    public function attachCompletions(CompletionService $completionService)
    {
        $contentTypes = $this->contentTypeTemplate->getContentTypes();
        foreach ($contentTypes as $contentType) {
            $config = $this->buildConfig($contentType->identifier);
            $source = $this->languageServiceCompletion->getDataSource();
            $source['field'] = $this->buildFieldSource($contentType->fieldDefinitions);

            $fieldCompletion = new FieldCompletion($source);
            $completionService->addCompletionService($fieldCompletion, $config);
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

    protected function buildConfig($identifier)
    {
        $config = array('fqn' => '\\eZCompletion\\' . $identifier);
        $fieldSource = array(
            'method' => 'getField',
            'lookupValue' => 'name',
            'returnValue' => 'identifier'
        );
        $languageSource = array(
            'method' => 'getField',
            'parameterIndex' => 1,
            'lookupValue' => 'name',
            'returnValue' => 'code'
        );
        $config['sources'] = array(
            'field' => array($fieldSource),
            'language' => array($languageSource)
        );

        $fieldSource['method'] = 'getFieldValue';
        $config['sources']['field'][] = $fieldSource;
        
        $languageSource['method'] = 'getFieldValue';
        $config['sources']['language'][] = $languageSource;

        return $config;
    }
}