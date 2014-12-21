<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 26.11.14
 */

namespace Flageolett\eZCompletionBundle\Abstracts;

use Flageolett\eZCompletionBundle\Entity\Completion;
use Flageolett\eZCompletionBundle\Entity\CompletionContainer;
use Flageolett\eZCompletionBundle\Traits\LanguageAware;

abstract class CompletionAbstract
{
    use LanguageAware;

    /** @var array */
    protected $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }
    
    public function getCompletions()
    {
        return $this->mapCompletions($this->getDataSource());
    }

    abstract protected function getDataSource();

    protected function mapCompletions($dataSource)
    {
        $completions = array();
        foreach ($this->config as $type => $configurations) {
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
            return new CompletionContainer($config['method'], $parameterIndex, $completions);
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

    protected function getTranslatedName($object)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $name = $object->getName($this->language);
        if (!$name) {
            /** @noinspection PhpUndefinedMethodInspection */
            $names = $object->getNames();
            $name = array_shift($names);
        }

        return $name;
    }
}
