<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */
namespace Sbk\Bundle\CronBundle\Cron;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

/**
 * Class Manager
 * @package Sbk\Bundle\CronBundle\Cron
 */
class Manager
{
    protected $tasks = array();
    protected $logger;

    protected $defaultScript;

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param $defaultScript
     * @param $taskConfig
     */
    public function __construct(LoggerInterface $logger, $defaultScript, array $taskConfig)
    {
        $this->defaultScript = $defaultScript;
        $this->logger = $logger;
        $this->initTasks($taskConfig);
    }

    /**
     * Initialize the tasks
     *
     * @param array $config
     */
    public function initTasks(array $config)
    {
        foreach ($config as $taskName => $taskConfig) {
            $this->initTask($taskName, $taskConfig);
        }
    }

    /**
     * Initialize a single task
     *
     * @param $taskName
     * @param array $taskConfig
     */
    protected function initTask($taskName, array $taskConfig)
    {
        if (false === isset($taskConfig['script'])) {
            $taskConfig['script'] = $this->defaultScript;
        }
        try {
            $task = new Task($taskName, $taskConfig);
            $this->tasks[$taskName] = $task;
        } catch (\Exception $e) {
            $this->getLogger()->error(sprintf('Could not instantiate task "%s"', $taskName));
        }
    }

    /**
     * Starts forking the valid tasks
     */
    public function forkTasks()
    {
        foreach ($this->getTasks() as $task) {
            $this->forkTask($task);
        }
    }

    /**
     * Create a child process for a Task
     *
     * @param Task $task
     */
    public function forkTask(Task $task)
    {
        $cronExpression = $task->getCronExpression();
        if (true === $cronExpression->isDue()) {
            $this->getLogger()->info(
                sprintf('Starting cron task "%s"', $task->getName())
            );
            $taskProcess = new Process($task->getCommandToExecute());
            $taskProcess->start();
        }
    }

}