<?php

namespace Lirc\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $handlerMock = $this->getMock('Lirc\\HandlerInterface');
        $handlerMock
            ->expects($this->once())
            ->method('handleConfig')
            ->will($this->returnValue(null))
            ->with(
                $this->equalTo('Config1'),
                $this->equalTo('01'),
                $this->equalTo('Remote.conf')
            );
        
        $config = new Config('Config1');
        $config->setHandler($handlerMock);
        $config->run('01', 'Remote.conf');
    }
}
