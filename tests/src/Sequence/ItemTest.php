<?php

namespace Lirc\Sequence;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $seqItem = $this->getMockForAbstractClass(
            'Lirc\Sequence\Item',
            array('SequenceItem')
        );
        
        $this->assertEquals('SequenceItem', $seqItem->getName());
    }
}
