<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 26.11.14
 */

namespace Flageolett\ezcompletionbundle\Service;

use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Language;
use Flageolett\ezcompletionbundle\Interfaces\CompletionInterface;

class LanguageCompletion implements CompletionInterface
{
    /** @var LanguageService */
    protected $languageService;

    public function __construct(Repository $repository)
    {
        $this->languageService = $repository->getContentLanguageService();
    }

    public function getCompletions()
    {
        $languageObjects = $this->languageService->loadLanguages();
        $languages = array_map(function(Language $language)
        {
            return array(
                'id' => $language->id,
                'code' => $language->languageCode,
                'name' => $language->name
            );
        }, $languageObjects);

        return compact('languages');
    }
}
