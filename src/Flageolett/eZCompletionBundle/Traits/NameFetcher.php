<?php

namespace Flageolett\eZCompletionBundle\Traits;

trait NameFetcher
{
    protected static function getTranslatedName($object, $languageCode = false)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $names = $object->getNames();
        $name = array_shift($names);

        if (!$languageCode) {
            return $name;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $languageName = $object->getName($languageCode);

        return $languageName ?: $name;
    }
}