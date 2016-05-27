<?php
/**
 * User:    Henning Kvinnesland
 * E-mail:  henning@byte.no
 * Date:    23.02.2016
 * Time:    14.36
 */

namespace Flageolett\eZCompletionBundle\Examples;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;

/**
 * Examples for content-completions.
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
     * 
     * @param $content
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
     * @param Content $content
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
        $content->getFieldValue('');
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
        // Result
        $content->getFieldsByLanguage('eng-GB')['publish_date']->stringFormat;

        // getTranslatedField
        $translationHelper = $this->container->get('ezpublish.translation_helper');
        $translationHelper->getTranslatedField($content, '|');
        // Result
        $translationHelper->getTranslatedField($content, 'publish_date');
        // Result
        $translationHelper->getTranslatedField($content, 'publish_date', '|');
        // Provided type: (DateAndTime)
        $translationHelper->getTranslatedField($content, 'publish_date', 'eng-GB')->stringFormat;

        $fieldHelper = $this->container->get('ezpublish.field_helper');

        // isFieldEmpty
        $fieldHelper->isFieldEmpty($content, '|');
        // Result
        $fieldHelper->isFieldEmpty($content, 'general_tags', '|');
        // Result
        $fieldHelper->isFieldEmpty($content, 'general_tags', 'eng-GB');
        // Provided type: NetGenTags
        $fieldHelper->isFieldEmpty($content, 'general_tags', 'eng-GB')->tags;
    }
}