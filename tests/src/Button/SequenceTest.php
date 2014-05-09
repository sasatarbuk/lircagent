<?php

namespace Lirc\Button;

use Lirc\Config\Sequence as ConfigSequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $configSequence;
    private $buttonSequence;
    
    private $button1;
    private $button2;
    
    public function setUp()
    {
        $this->configSequence = new ConfigSequence();
        $this->configSequence->setRemote('Remote1.conf');
        $this->buttonSequence = new Sequence($this->configSequence);
        
        $this->button1 = new Button('Button');
        $this->button1->registerSequence($this->buttonSequence);
        $this->buttonSequence->append($this->button1);
        
        $this->button2 = new Button('Button');
        $this->button2->registerSequence($this->buttonSequence);
        $this->buttonSequence->append($this->button2);
    }
    
    public function testGetConfigSequence()
    {
        $this->assertSame(
            $this->configSequence,
            $this->buttonSequence->getConfigSequence()
        );
    }
    
    public function testReset()
    {
        $this->buttonSequence->next();
        $this->assertSame($this->button2, $this->buttonSequence->getCurrent());
        
        $this->buttonSequence->reset();
        $this->assertSame($this->button1, $this->buttonSequence->getCurrent());
    }
    
    public function testRunConfigRemoteMismatch()
    {
        $this->assertFalse($this->buttonSequence->runConfig(0, 'Remote2.conf'));
    }
    
    public function testRunConfig()
    {
        $this->configSequence->setRepeat(2);
        $this->configSequence->setDelay(3);
        
        $config1 = $this->getMock(
            'Lirc\\Config\\Config',
            array('run'),
            array('Config1')
        );
        $config1
            ->expects($this->exactly(1))
            ->method('run')
            ->with(
                $this->equalTo(0),
                $this->equalTo('Remote1.conf')
            );
        
        $config2 = $this->getMock(
            'Lirc\\Config\\Config',
            array('run'),
            array('Config2')
        );
        $config2
            ->expects($this->exactly(1))
            ->method('run')
            ->with(
                $this->equalTo(4),
                $this->equalTo('Remote1.conf')
            );
        
        $config3 = $this->getMock(
            'Lirc\\Config\\Config',
            array('run'),
            array('Config3')
        );
        $config3
            ->expects($this->exactly(1))
            ->method('run')
            ->with(
                $this->equalTo(6),
                $this->equalTo('Remote1.conf')
            );
        
        $this->configSequence->append($config1);
        $this->configSequence->append($config2);
        $this->configSequence->append($config3);
        
        $this->assertSame($config1, $this->buttonSequence->runConfig(0, 'Remote1.conf'));
        $this->assertFalse($this->buttonSequence->runConfig(1, 'Remote1.conf'));
        $this->assertFalse($this->buttonSequence->runConfig(2, 'Remote1.conf'));
        $this->assertFalse($this->buttonSequence->runConfig(3, 'Remote1.conf'));
        $this->assertSame($config2, $this->buttonSequence->runConfig(4, 'Remote1.conf'));
        $this->assertFalse($this->buttonSequence->runConfig(5, 'Remote1.conf'));
        $this->assertSame($config3, $this->buttonSequence->runConfig(6, 'Remote1.conf'));
    }
    
    public function testRunConfigNoDelay()
    {
        $this->configSequence->setRepeat(2);
        $this->configSequence->setDelay(0);
        
        $config1 = $this->getMock(
            'Lirc\\Config\\Config',
            array('run'),
            array('Config1')
        );
        $config1
            ->expects($this->at(0))
            ->method('run')
            ->with(
                $this->equalTo(0),
                $this->equalTo('Remote1.conf')
            );
        $config1
            ->expects($this->at(1))
            ->method('run')
            ->with(
                $this->equalTo(2),
                $this->equalTo('Remote1.conf')
            );
        
        $this->configSequence->append($config1);
        
        $this->assertSame($config1, $this->buttonSequence->runConfig(0, 'Remote1.conf'));
        $this->assertFalse($this->buttonSequence->runConfig(1, 'Remote1.conf'));
        $this->assertSame($config1, $this->buttonSequence->runConfig(2, 'Remote1.conf'));
    }
}
