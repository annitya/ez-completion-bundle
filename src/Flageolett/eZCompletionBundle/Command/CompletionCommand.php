<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 22.11.14
 */

namespace Flageolett\eZCompletionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompletionCommand extends ContainerAwareCommand
{
    const OPTION_LANGUAGE = 'language';
    const DEFAULT_LANGUAGE = 'nor-NO';

    protected function configure()
    {
        $this->setName('ezcode:completion');
        $this->addOption(self::OPTION_LANGUAGE, 'l', InputOption::VALUE_OPTIONAL, 'Language-code for returned completions', self::DEFAULT_LANGUAGE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $completionService = $this->getContainer()->get('ezcompletionbundle.completion_service');
        $language = $input->getOption(self::OPTION_LANGUAGE);
        $completionService->setLanguage($language);

        $completions = $completionService->getCompletions();
        $output->writeln(json_encode(array('list' => $completions), JSON_PRETTY_PRINT));
    }
}
