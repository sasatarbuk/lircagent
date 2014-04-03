<?php

namespace Lirc\Button;

use Lirc\Config\Sequence as ConfigSequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $_configSequence;
    private $_buttonSequence;
    
    private $_button1;
    private $_button2;
    
    public function setUp()
    {
        $this->_configSequence = new ConfigSequence();
        $this->_configSequence->setRemote('Remote1.conf');
        $this->_buttonSequence = new Sequence($this->_configSequence);
        
        $this->_button1 = new Button('Button');
        $this->_button1->registerSequence($this->_buttonSequence);
        $this->_buttonSequence->append($this->_button1);
        
        $this->_button2 = new Button('Button');
        $this->_button2->registerSequence($this->_buttonSequence);
        $this->_buttonSequence->append($this->_button2);
    }
    
    public function testGetConfigSequence()
    {
        $this->assertSame(
            $this->_configSequence,
            $this->_buttonSequence->getConfigSequence()
        );
    }
    
    public function testReset()
    {
        $this->_buttonSequence->next();
        $this->assertSame($this->_button2, $this->_buttonSequence->getCurrent());
        
        $this->_buttonSequence->reset();
        $this->assertSame($this->_button1, $this->_buttonSequence->getCurrent());
    }
    
    public function testRunConfigRemoteMismatch()
    {
        $this->assertFalse($this->_buttonSequence->runConfig(0, 'Remote2.conf'));
    }
    
    public function testRunConfig()
    {
        $this->_configSequence->setRepeat(2);
        $this->_configSequence->setDelay(3);
        
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
        
        $this->_configSequence->append($config1);
        $this->_configSequence->append($config2);
        $this->_configSequence->append($config3);
        
        $this->assertSame($config1, $this->_buttonSequence->runConfig(0, 'Remote1.conf'));
        $this->assertFalse($this->_buttonSequence->runConfig(1, 'Remote1.conf'));
        $this->assertFalse($this->_buttonSequence->runConfig(2, 'Remote1.conf'));
        $this->assertFalse($this->_buttonSequence->runConfig(3, 'Remote1.conf'));
        $this->assertSame($config2, $this->_buttonSequence->runConfig(4, 'Remote1.conf'));
        $this->assertFalse($this->_buttonSequence->runConfig(5, 'Remote1.conf'));
        $this->assertSame($config3, $this->_buttonSequence->runConfig(6, 'Remote1.conf'));
    }
    
    public function testRunConfigNoDelay()
    {
        $this->_configSequence->setRepeat(2);
        $this->_configSequence->setDelay(0);
        
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
        
        $this->_configSequence->append($config1);
        
        $this->assertSame($config1, $this->_buttonSequence->runConfig(0, 'Remote1.conf'));
        $this->assertFalse($this->_buttonSequence->runConfig(1, 'Remote1.conf'));
        $this->assertSame($config1, $this->_buttonSequence->runConfig(2, 'Remote1.conf'));
    }
}
