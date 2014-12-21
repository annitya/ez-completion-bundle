<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\FieldTypeService;
use \eZ\Publish\API\Repository\FieldType;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;

class FieldTypeServiceCompletion extends CompletionAbstract
{
    /** @var FieldTypeService */
    protected $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    protected function getDataSource()
    {
        $fieldType = array_map(function(FieldType $fieldType)
        {
            return array('identifier' => $fieldType->getFieldTypeIdentifier());
        }, $this->fieldTypeService->getFieldTypes());

        $fieldType = array_values($fieldType);
        return compact('fieldType');
    }
}
