<?php

namespace Flageolett\eZCompletionBundle\Traits;

trait NameFetcher
{
    protected static function getTranslatedName($object, $languageCode = false)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $names = $object->getNames();
        /** @noinspection PhpUndefinedMethodInspection */
        $languageName = $languageCode ? $object->getName($languageCode) : false;

        return self::fallback($names, $languageName);
    }

    protected static function getTranslatedDescription($object, $languageCode = false)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $descriptions = $object->getDescriptions();
        /** @noinspection PhpUndefinedMethodInspection */
        $languageDescription = $languageCode ? $object->getDescription($languageCode) : false;

        return self::fallback($descriptions, $languageDescription);
    }

    protected static function fallback($primary, $secondary = false)
    {
        return $secondary ?: array_shift($primary);
    }
}