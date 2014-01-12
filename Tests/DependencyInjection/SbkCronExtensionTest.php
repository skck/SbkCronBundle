<?php
/**
 *
 * @link http://www.superreal.de
 * @copyright (C) superReal GmbH | Agentur fuer Neue Kommunikation
 * @author Sebastian Kueck <s.kueck AT superreal.de>
 */

namespace Sbk\Bundle\CronBundle\Tests;


use Sbk\Bundle\CronBundle\DependencyInjection\SbkCronExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SbkCronExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtension()
    {
        $loader = new SbkCronExtension();
        $config = array();
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidConfigException()
    {
        $loader = new SbkCronExtension();
        $config = array('foo'=>'bar');
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testConfiguration()
    {
        $loader = new SbkCronExtension();
        $config = array(
            'tasks' => array(
                'testtask' => array(
                    'bin' => 'php',
                    'script' => '',
                    'command' => '-i',
                    'arguments' => '',
                    'expression' => '* * * * *'
                )
            )
        );
        $loader->load(array($config), new ContainerBuilder());

    }
}
 