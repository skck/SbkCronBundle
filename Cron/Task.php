<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */
namespace Sbk\Bundle\CronBundle\Cron;

use Cron\CronExpression;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Task
{
    protected $name;
    protected $bin;
    protected $script;
    protected $command;
    protected $arguments;
    protected $cronExpression;

    /**
     * @param mixed $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param mixed $bin
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @return mixed
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param mixed $cronExpression
     */
    public function setCronExpression($cronExpression)
    {
        $this->cronExpression = $cronExpression;
    }

    /**
     * @return mixed
     */
    public function getCronExpression()
    {
        return $this->cronExpression;
    }

    /**
     * @param mixed $script
     */
    public function setScript($script)
    {
        $this->script = $script;
    }

    /**
     * @return mixed
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->assignConfiguration($config);
    }

    /**
     * @param array $config
     */
    protected function assignConfiguration(array $config)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        $options = $resolver->resolve($config);

        $this->bin = $options['bin'];
        $this->script = $options['script'];
        $this->arguments = $options['arguments'];
        $this->command = $options['command'];

        $cronExpression = CronExpression::factory($options['expression']);
        $this->cronExpression = $cronExpression;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'bin' => 'php',
                'script' => null,
                'arguments' => array(),
                'command' => null,
                'expression' => '',
            )
        );
    }

    /**
     * Returns the executable command for the task instance
     *
     * @return string
     */
    public function getCommandToExecute()
    {
        $command = implode(
            " ",
            array(
                $this->bin,
                $this->script,
                $this->command,
                implode(
                    " ",
                    $this->arguments
                )
            )
        );
        return trim(preg_replace('/\s+/', ' ', $command));
    }

}