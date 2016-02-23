<?php
/**
 * User:    Henning Kvinnesland
 * E-mail:  henning@byte.no
 * Date:    23.02.2016
 * Time:    14.36
 */

namespace Flageolett\eZCompletionBundle;

use eZ\Publish\Core\MVC\Symfony\Controller\Controller;

/**
 * Examples for all available completions.
 * | indicates cursor position upon completion-request.
 */
class Examples extends Controller
{
    /**
     * Completion for eZDoc (similar to PhpDoc)
     *
     * Allows PhpStorm to provide you with available contentType-fields.
     *
     * Also available via intention: "Create eZDoc"
     */
    public function eZDocCompletion($content)
    {
        // eZDoc-completion
        /** @| */
        // Result
        /** @ContentType */

        // ContentType-completion
        /** @ContentType | */
        // Result
        /** @ContentType article */

        // Somewhat sketchy variable-completion (not my doing)
        /** @ContentType article $| */
        // Result
        /** @ContentType article $content */
    }

    /**
     * Completions for available fields.
     *
     * @ContentType article $content (ContentType-hints also works here)
     */
    public function contentCompletions(Content $content)
    {
        // fields
        $content->fields['|'];
        // Result
        $content->fields['body'];
        // Type is also provided (eZXMLText)
        $content->fields['body']->xml;

        // getFields
        $content->getFields()['|'];
        // Result
        $content->getFields()['image'];
        // Type is also provided (eZObjectRelation)
        $content->getFields()['image']->destinationContentId;

        // getFieldValue
        $content->getFieldValue('|');
        // Result
        $content->getFieldValue('title');
        // Provided type (TextLine)
        $content->getFieldValue('title')->text;

        // getFieldsByLanguage
        $content->getFieldsByLanguage('|');
        // Result
        $content->getFieldsByLanguage('eng-GB');
        // Available fields
        $content->getFieldsByLanguage('eng-GB')['|'];
        // Result
        $content->getFieldsByLanguage('eng-GB')['publish_date'];
        // @TODO: Type-provider is unavailable as of now.
        $content->getFieldsByLanguage('eng-GB')['publish_date']; // Not working.

        // getTranslatedField
        $translationHelper = $this->container->get('ezpublish.translation_helper');
        $translationHelper->getTranslatedField($content, '|');
        // Result
        $translationHelper->getTranslatedField($content, 'publish_date');
        // Provided type: (DateAndTime)
        $translationHelper->getTranslatedField($content, 'publish_date')->stringFormat;

        // isFieldEmpty
        $fieldHelper = $this->container->get('ezpublish.field_helper');
        /** @TODO: missing */
        $fieldHelper->isFieldEmpty($content, '|');
        /** @TODO: missing */
        $fieldHelper->isFieldEmpty($content, 'publish_date', '|');
        /** @TODO: missing */
        $fieldHelper->isFieldEmpty($content, 'publish_date', 'eng-GB');
        /** @TODO: missing */
        $fieldHelper->isFieldEmpty($content, 'publish_date', 'eng-GB')->stringFormat;
    }

    public function repositoryCompletions()
    {
        $repository = $this->getRepository();

        // hasAccess
        $repository->hasAccess('|');
        //Result
        $repository->hasAccess('content', '|');
        //Result
        $repository->hasAccess('content', 'edit');

        // canUser
        $repository->canUser('|');
        // Result
        $repository->canUser('collaboration', '|');
        // Result
        $repository->canUser('collaboration', 'view');
    }

    public function contentService($content)
    {
        /** @TODO: missing */
        $contentService = $this->getRepository()->getContentService();
        // new ContentCreateStruct
        $contentService->newContentCreateStruct($content, '|');
        /** @TODO: missing */
        $contentService->newContentCreateStruct($content, 'eng-GB');
    }

    public function contentLanguageService()
    {
        $languageService = $this->getRepository()->getContentLanguageService();

        // loadLanguage
        $languageService->loadLanguage('|');
        // Result
        $languageService->loadLanguage('eng-GB');

        // loadLanguageById
        $languageService->loadLanguageById('|');
        // Result
        $languageService->loadLanguageById(4);
    }

    public function contentTypeService()
    {
        $contentTypeService = $this->getRepository()->getContentTypeService();

        // loadContentType
        $contentTypeService->loadContentType('|');
        // @TODO: Completions not searchable by text.
        $contentTypeService->loadContentType(4);

        // loadContentTypeByIdentifier
        $contentTypeService->loadContentTypeByIdentifier('|');
        // Result
        $contentTypeService->loadContentTypeByIdentifier('article');

        // loadContentTypeByRemoteId
        $contentTypeService->loadContentTypeByRemoteId('|');
        // Result
        $contentTypeService->loadContentTypeByRemoteId('77f3ede996a3a39c7159cc69189c5307');

        // LoadContentTypeDraft
        $contentTypeService->loadContentTypeDraft('|');
        // @TODO: Make sure only drafts are available.
        $contentTypeService->loadContentTypeDraft(16);

        // loadContentTypeGroup
        $contentTypeService->loadContentTypeGroup('|');
        // Result
        $contentTypeService->loadContentTypeGroup(2);

        // loadContentTypeGroupByIdentifier
        $contentTypeService->loadContentTypeGroupByIdentifier('|');
        $contentTypeService->loadContentTypeGroupByIdentifier('Content');
    }
}