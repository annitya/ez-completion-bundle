<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 02.01.15
 */

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;

class QueryService extends Controller
{
    public function execute($methodFqn, $queryCode)
    {
        $parts = explode('.', $methodFqn);
        $class = $parts[0];
        $method = $parts[1];

        /** @var Controller $instance */
        $instance = new $class();
        $instance->setContainer($this->container);

        /** @var SearchResult $result */
        $result = $instance->$method();

        $response = array(
            'time' => $result->time,
            'count' => $result->totalCount,
            'hits' => array()
        );
        foreach ($result->searchHits as $hit) {
            /** @var Content $content */
            $content = $hit->valueObject;

            $response['hits'][] = array(
                'versionInfo' => $this->objectToArray($content->versionInfo),
                'fields' => $this->objectToArray($content->getFields())
            );
        }

        return compact('response', 'queryCode');
    }

    protected function objectToArray($var)
    {
        $var = $this->reflectObject($var);
        $result = array();
        $references = array();

        // loop over elements/properties
        foreach ($var as $key => $value) {
            // recursively convert objects
            if (is_object($value) || is_array($value)) {
                // but prevent cycles
                if (!in_array($value, $references)) {
                    $result[$key] = $this->objectToArray($value);
                    $references[] = $value;
                }
            } else {
                // simple values are untouched
                $result[$key] = $value;
            }
        }
        return $result;
    }

    protected function reflectObject($object)
    {
        if (!is_object($object)) {
            return $object;
        }

        $reflection = new \ReflectionClass($object);
        $array = array();
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
        }

        return $array;
    }
}
