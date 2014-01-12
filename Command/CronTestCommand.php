<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */
namespace Sbk\Bundle\CronBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronTestCommand extends Command
{
    protected function configure()
    {
        $this->setName('cron:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('asd');
        sleep(10);
    }

}