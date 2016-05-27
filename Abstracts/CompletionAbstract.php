<?php

namespace Flageolett\eZCompletionBundle\Abstracts;

use Flageolett\eZCompletionBundle\Entity\Completion;
use Flageolett\eZCompletionBundle\Entity\CompletionContainer;
use Flageolett\eZCompletionBundle\Traits\LanguageAware;
use Flageolett\eZCompletionBundle\Traits\NameFetcher;

abstract class CompletionAbstract
{
    use LanguageAware;
    use NameFetcher;

    /** @var string */
    protected $fqn;
    /** @var array */
    protected $sources;

    public function setConfig($config)
    {
        $this->fqn = $config['fqn'];
        $this->sources = $config['sources'];
    }
    
    public function getCompletions()
    {
        return $this->mapCompletions($this->getDataSource());
    }

    abstract protected function getDataSource();

    protected function mapCompletions($dataSource)
    {
        $completions = array();
        foreach ($this->sources as $type => $configurations) {
            $completions = array_merge($completions, $this->buildCompletionContainers($configurations, $dataSource[$type]));
        }

        return $completions;
    }

    protected function buildCompletionContainers($configs, $source)
    {
        return array_map(function($config) use($source)
        {
            $completions = $this->buildCompletions($config, $source);
            $parameterIndex = isset($config['parameterIndex']) ? $config['parameterIndex'] : 0;
            $method = isset($config['method']) ? $config['method']  : null;
            return new CompletionContainer($this->fqn, $method, $parameterIndex, $completions);
        }, $configs);
    }

    protected function buildCompletions($config, $source)
    {
        return array_map(function($completionData) use($config)
        {
            $lookupValue = $completionData[$config['lookupValue']];
            $returnValue = isset($config['returnValue']) ? $completionData[$config['returnValue']] : $lookupValue;
            return new Completion($lookupValue, $returnValue);
        }, $source);
    }
}
