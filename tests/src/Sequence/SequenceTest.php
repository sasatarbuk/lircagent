<?php

namespace Lirc\Sequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $sequence;
    private $emptySequence;
    private $sequenceItem1;
    private $sequenceItem2;
    
    public function setUp()
    {
        $this->sequenceItem1 = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Item',
            array('SequenceItem1')
        );
        
        $this->sequenceItem2 = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Item',
            array('SequenceItem1')
        );
        
        $this->sequence = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Sequence'
        );
        
        $this->sequence->append($this->sequenceItem1);
        
        $this->emptySequence = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Sequence'
        );
    }
    
    public function testAppend()
    {
        $this->sequence->append($this->sequenceItem2);
        
        $this->assertSame(
            array(
                $this->sequenceItem1,
                $this->sequenceItem2,
            ),
            $this->sequence->getItems()
        );
    }
    
    public function testNext()
    {
        $this->sequence->append($this->sequenceItem2);
        $this->assertSame($this->sequenceItem2, $this->sequence->next());
        $this->assertSame($this->sequenceItem1, $this->sequence->next());
    }
    
    public function testGetNext()
    {
        $this->sequence->append($this->sequenceItem2);
        $this->assertSame(
            $this->sequenceItem2,
            $this->sequence->getNext()
        );
        
        $this->assertNull(null, $this->emptySequence->getNext());
    }
    
    public function testGetCurrent()
    {
        $this->assertSame(
            $this->sequenceItem1,
            $this->sequence->getCurrent()
        );
        
        $this->assertNull(null, $this->emptySequence->getCurrent());
    }
    
    public function testGetItems()
    {
        $this->assertSame(
            array(
                $this->sequenceItem1,
            ),
            $this->sequence->getItems()
        );
    }
    
    public function testGetLength()
    {
        $this->assertSame(1, $this->sequence->getLength());
        $this->sequence->append($this->sequenceItem2);
        $this->assertSame(2, $this->sequence->getLength());
    }
    
    public function testIsAtStart()
    {
        $this->assertTrue($this->sequence->isAtStart());
    }
}
