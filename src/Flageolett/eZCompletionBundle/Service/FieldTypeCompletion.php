<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\FieldTypeService;
use Flageolett\eZCompletionBundle\Interfaces\CompletionInterface;

class FieldTypeCompletion implements CompletionInterface
{
    /** @var FieldTypeService */
    protected $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    public function getCompletions()
    {
        $fieldTypes = array();
        $fieldTypeList = $this->fieldTypeService->getFieldTypes();
        foreach ($fieldTypeList as $fieldType) {
            $fieldTypes[] = array('identifier' => $fieldType->getFieldTypeIdentifier());
        }

        return compact('fieldTypes');
    }
}
