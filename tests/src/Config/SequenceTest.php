<?php

namespace Lirc\Config;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $sequence;
    
    public function setUp()
    {
        $this->sequence = new Sequence();
    }
    
    public function testSetGetRemote()
    {
        $this->assertNull($this->sequence->getRemote());
        $this->sequence->setRemote('Remote.conf');
        $this->assertSame('Remote.conf', $this->sequence->getRemote());
    }
    
    public function testSetGetRepeat()
    {
        $this->assertSame(0, $this->sequence->getRepeat());
        $this->sequence->setRepeat(5);
        $this->assertSame(5, $this->sequence->getRepeat());
    }
    
    public function testSetGetDelay()
    {
        $this->assertSame(0, $this->sequence->getDelay());
        $this->sequence->setDelay(9);
        $this->assertSame(9, $this->sequence->getDelay());
    }
}
