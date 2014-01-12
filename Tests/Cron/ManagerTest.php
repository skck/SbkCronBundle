<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */
namespace Sbk\Bundle\CronBundle\Tests\Cron;

use Monolog\Logger;
use Sbk\Bundle\CronBundle\Cron\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $loggerStub;

    protected function setUp()
    {
        $this->loggerStub = $this->getMockBuilder('Monolog\Logger')
            ->setConstructorArgs(array('phpunit'))
            ->getMock();
    }


    public function testInstance()
    {
        $manager = new Manager($this->loggerStub, null, array());
        $this->assertInstanceOf('Sbk\Bundle\CronBundle\Cron\Manager', $manager);
    }

    public function testTasks()
    {
        $tasksConfig = array(
            'testtask' => array(
                'bin' => 'ls',
                'command' => '-l',
                'expression' => '* * * * *',
            ),
            'falsetask' => array(
                'bin' => 'ls',
                'command' => '-l',
                'expression' => 'invalid expression'
            )
        );
        $manager = new Manager($this->loggerStub, null, $tasksConfig);
        $tasks = $manager->getTasks();
        $this->assertCount(1, $tasks);

        foreach ($tasks as $task) {
            $this->assertInstanceOf('Sbk\Bundle\CronBundle\Cron\Task', $task);
        }
    }

    public function testForkTasks()
    {
        $tasksConfig = array(
            'testtask' => array(
                'bin' => 'ls',
                'command' => '-l',
                'expression' => '* * * * *',
            ),
        );
        $manager = new Manager($this->loggerStub, null, $tasksConfig);
        $manager->forkTasks();
    }
}