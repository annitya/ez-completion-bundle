<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 22.11.14
 */

namespace Flageolett\eZCompletionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompletionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('ezcode:completion');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $completions = $this->getContainer()->get('ezcompletionbundle.completion_service')->getCompletions();
        $output->writeln(json_encode($completions, JSON_PRETTY_PRINT));
    }
}
