<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */

namespace Sbk\Bundle\CronBundle\Tests\Cron;

use Sbk\Bundle\CronBundle\Cron\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    protected $taskOptions = array(
        'bin' => 'ls',
        'command' => '-l',
        'expression' => '* * * * *',
    );

    public function testInstance()
    {
        $task = new Task('unittest', $this->taskOptions);
        $this->assertEquals('unittest', $task->getName());
    }

    public function testCommandToExecute()
    {
        $task = new Task('unittest', $this->taskOptions);
        $commandToExecute = $task->getCommandToExecute();
        $this->assertEquals('ls -l', $commandToExecute);
    }

    public function testCronExpression()
    {
        $task = new Task('unittest', $this->taskOptions);
        $this->assertInstanceOf('Cron\CronExpression', $task->getCronExpression());
    }
}