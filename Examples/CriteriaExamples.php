<?php
/**
 * User:    Henning Kvinnesland
 * E-mail:  henning@byte.no
 * Date:    23.02.2016
 * Time:    20.03
 */

namespace Flageolett\eZCompletionBundle\Examples;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;

/**
 * Examples for critera-completions used by searchService/query.
 * | indicates cursor position upon completion-request.
 */
class CriteriaExamples extends Controller
{
    public function contentCriteria(Query $query)
    {
        $query->criterion[] = new Query\Criterion\LogicalAnd([
            // ContentTypeGroupId
            new Query\Criterion\ContentTypeGroupId(''),
            // Result
            new Query\Criterion\ContentTypeGroupId(3),
            // ContentTypeId
            new Query\Criterion\ContentTypeId('|'),
            // Result
            new Query\Criterion\ContentTypeId(4),
            // ContentTypeIdentifier
            new Query\Criterion\ContentTypeIdentifier('|'),
            // Result
            new Query\Criterion\ContentTypeIdentifier('article'),
            // langaugeCode
            new Query\Criterion\LanguageCode('|'),
            // Result
            new Query\Criterion\LanguageCode('eng-GB'),
            // ObjectStateId
            new Query\Criterion\ObjectStateId('|'),
            // Result
            new Query\Criterion\ObjectStateId(1),
            // sectionId
            new Query\Criterion\SectionId('|'),
            // Result
            new Query\Criterion\SectionId(5),
        ]);
    }
}