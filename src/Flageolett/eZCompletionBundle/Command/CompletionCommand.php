<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 22.11.14
 */

namespace Flageolett\eZCompletionBundle\Command;

use eZ\Publish\API\Repository\Values\Content\Language;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompletionCommand extends ContainerAwareCommand
{
    const OPTION_LANGUAGE = 'language';

    protected function configure()
    {
        $this->setName('ezcode:completion');
        $this->addOption(self::OPTION_LANGUAGE, 'l', InputOption::VALUE_OPTIONAL, 'Language-code for returned completions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $language = $input->getOption(self::OPTION_LANGUAGE);
        $completionService = $this->getContainer()->get('ezcompletionbundle.completion_service');
        $completionService->setLanguage($language);

        $completions = array(
            'list' => $completionService->getCompletions(),
            'contentTypes' => $this->getContainer()->get('ezcompletionbundle.contenttype')->fetchContentTypes(),
            'contentLanguages' => $this->getAvailableLanguages()
        );

        $output->writeln(json_encode($completions, JSON_PRETTY_PRINT));
    }

    protected function getAvailableLanguages()
    {
        $languageService = $this->getContainer()->get('ezpublish.api.repository')->getContentLanguageService();
        return array_map(function(Language $language) {
            return $language->languageCode;
        }, $languageService->loadLanguages());
    }
}
