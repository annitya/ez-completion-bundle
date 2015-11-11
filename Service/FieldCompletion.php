<?php

namespace Flageolett\eZCompletionBundle\Service;

use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;

class FieldCompletion extends CompletionAbstract
{
    protected $dataSource;

    public function __construct($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    protected function getDataSource()
    {
        return $this->dataSource;
    }
}