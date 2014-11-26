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
    const OPTION_NAME_COMPLETION_TYPE = 'type';

    protected function configure()
    {
        $this->setName('ezcode:completion');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $completions = array();
        $completions += $container->get('ezcompletionbundle.contenttype')->getCompletions();

        $output->writeln(json_encode($completions));
    }
}
