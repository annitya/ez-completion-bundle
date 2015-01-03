<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 01.01.15
 */

namespace Flageolett\eZCompletionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueryCommand extends ContainerAwareCommand
{
    const OPTION_QUERYCODE = 'queryCode';
    const OPTION_METHODFQN = 'methodFqn';

    protected function configure()
    {
        $this->setName('ezcode:query');
        $this->addOption(self::OPTION_METHODFQN, null, InputOption::VALUE_REQUIRED, 'Class containing the query');
        $this->addOption(self::OPTION_QUERYCODE, null, InputOption::VALUE_REQUIRED, 'PHP code to execute');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $methodFqn = $input->getOption(self::OPTION_METHODFQN);
        $queryCode = $input->getOption(self::OPTION_QUERYCODE);

        $result = $this->getContainer()->get('ezcompletionbundle.query_service')->execute($methodFqn, $queryCode);

        $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
    }
}
