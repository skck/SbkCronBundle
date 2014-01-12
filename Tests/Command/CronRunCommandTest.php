<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */
namespace Sbk\Bundle\CronBundle\Tests\Command;

use Sbk\Bundle\CronBundle\Command\CronRunCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CronRunCommandTest extends WebTestCase
{
    protected static $kernel;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    public function testExecute()
    {
        $application = new Application(static::$kernel);

        $application->add(new CronRunCommand());

        $command = $application->find('cron:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

    }
}