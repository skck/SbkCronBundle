<?php

use Symfony\Component\Console\Application;
use ADR\Bundle\CassandraBundle\Command\CassandraCreateKeyspaceCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

use Sbk\Bundle\CronBundle\Command\CronRunCommand;
use Sbk\Bundle\CronBundle\Cron\Manager;

class CronRunCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testExecution()
    {

        $tmp_file_1 = sys_get_temp_dir().'/CronRunCommandTest-testExecution-1.txt';
        $tmp_file_2 = sys_get_temp_dir().'/CronRunCommandTest-testExecution-2.txt';
        $this->removeFileIfExists($tmp_file_1);
        $this->removeFileIfExists($tmp_file_2);
        
        $tasksConfig = array(
            'ls_task' => array(
                'bin' => 'ls',
                'command' => '-l > '.$tmp_file_1,
                'expression' => '* * * * *',
            ),
            'df_task' => array(
                'bin' => 'df',
                'command' => '-h > '.$tmp_file_2,
                'expression' => '* * * * *',
            ),
        );
        
        $application = new Application();
        $application->add(new CronRunCommand());

        $command = $application->find('cron:run');
        $command->setContainer($this->getMockContainer($tasksConfig));

        $tester = new CommandTester($command);
        $tester->execute(
            array('command' => $command->getName())
        );

        $this->assertTrue(file_exists($tmp_file_1), 'Test if file #1 has been created by task through cron:run');
        $this->assertTrue(file_exists($tmp_file_2), 'Test if file #2 has been created by task through cron:run');

        $this->removeFileIfExists($tmp_file_1);
        $this->removeFileIfExists($tmp_file_2);

    }

    private function getMockContainer($tasksConfig)
    {
        $logger = m::mock('Monolog\Logger', array('phpunit'))->makePartial();

        $cronManager = new Manager($logger, 'console', $tasksConfig);
 
        $container = m::mock('Symfony\Component\DependencyInjection\Container');
        $container
            ->shouldReceive('get')
            ->once()
            ->with('sbk_cron.manager')
            ->andReturn($cronManager)
        ;
 
        return $container;
    }

    /**
     * @param  string $file
     * @return
     */
    private function removeFileIfExists($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
