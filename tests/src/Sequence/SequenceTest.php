<?php

namespace Lirc\Sequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    private $_sequence;
    private $_emptySequence;
    private $_sequenceItem1;
    private $_sequenceItem2;
    
    public function setUp()
    {
        $this->_sequenceItem1 = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Item',
            array('SequenceItem1')
        );
        
        $this->_sequenceItem2 = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Item',
            array('SequenceItem1')
        );
        
        $this->_sequence = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Sequence'
        );
        
        $this->_sequence->append($this->_sequenceItem1);
        
        $this->_emptySequence = $this->getMockForAbstractClass(
            'Lirc\\Sequence\\Sequence'
        );
    }
    
    public function testAppend()
    {
        $this->_sequence->append($this->_sequenceItem2);
        
        $this->assertSame(
            array(
                $this->_sequenceItem1,
                $this->_sequenceItem2,
            ),
            $this->_sequence->getItems()
        );
    }
    
    public function testNext()
    {
        $this->_sequence->append($this->_sequenceItem2);
        $this->assertSame($this->_sequenceItem2, $this->_sequence->next());
        $this->assertSame($this->_sequenceItem1, $this->_sequence->next());
    }
    
    public function testGetNext()
    {
        $this->_sequence->append($this->_sequenceItem2);
        $this->assertSame(
            $this->_sequenceItem2,
            $this->_sequence->getNext()
        );
        
        $this->assertNull(null, $this->_emptySequence->getNext());
    }
    
    public function testGetCurrent()
    {
        $this->assertSame(
            $this->_sequenceItem1,
            $this->_sequence->getCurrent()
        );
        
        $this->assertNull(null, $this->_emptySequence->getCurrent());
    }
    
    public function testGetItems()
    {
        $this->assertSame(
            array(
                $this->_sequenceItem1,
            ),
            $this->_sequence->getItems()
        );
    }
    
    public function testGetLength()
    {
        $this->assertSame(1, $this->_sequence->getLength());
        $this->_sequence->append($this->_sequenceItem2);
        $this->assertSame(2, $this->_sequence->getLength());
    }
    
    public function testIsAtStart()
    {
        $this->assertTrue($this->_sequence->isAtStart());
    }
}
