<?php

namespace Lirc\Config;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $_sequence;
    
    public function setUp()
    {
        $this->_sequence = new Sequence();
    }
    
    public function testSetGetRemote()
    {
        $this->assertNull($this->_sequence->getRemote());
        $this->_sequence->setRemote('Remote.conf');
        $this->assertSame('Remote.conf', $this->_sequence->getRemote());
    }
    
    public function testSetGetRepeat()
    {
        $this->assertSame(0, $this->_sequence->getRepeat());
        $this->_sequence->setRepeat(5);
        $this->assertSame(5, $this->_sequence->getRepeat());
    }
    
    public function testSetGetDelay()
    {
        $this->assertSame(0, $this->_sequence->getDelay());
        $this->_sequence->setDelay(9);
        $this->assertSame(9, $this->_sequence->getDelay());
    }
}
