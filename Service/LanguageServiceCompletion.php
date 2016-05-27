<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Language;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;

class LanguageServiceCompletion extends CompletionAbstract
{
    /** @var LanguageService */
    protected $languageService;

    public function __construct(Repository $repository)
    {
        $this->languageService = $repository->getContentLanguageService();
    }

    public function getDataSource()
    {
        $language = array_map(function(Language $language) {
            return array(
                'id' => (int)$language->id,
                'code' => $language->languageCode,
                'name' => $language->name
            );
        }, $this->languageService->loadLanguages());

        return compact('language');
    }
}
