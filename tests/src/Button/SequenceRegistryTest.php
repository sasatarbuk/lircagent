<?php

namespace Lirc\Button;

use Lirc\Config\Sequence as ConfigSequence;

class SequenceRegistryTest extends \PHPUnit_Framework_TestCase
{
    private $_sequence1;
    private $_sequence2;
    private $_sequenceRegistry;
    
    public function setUp()
    {
        $configSequence   = new ConfigSequence();
        $this->_sequence1 = new Sequence($configSequence);
        $this->_sequence2 = new Sequence($configSequence);
        
        $this->_sequenceRegistry = new SequenceRegistry();
        $this->_sequenceRegistry->registerRunning($this->_sequence1);
    }
    
    public function testRegisterRunning()
    {
        // Register $this->_sequence1 twice!
        $this->_sequenceRegistry->registerRunning($this->_sequence1);
        $this->_sequenceRegistry->registerRunning($this->_sequence2);
        $this->assertSame(
            array(
                $this->_sequence1,
                $this->_sequence2,
            ),
            $this->_sequenceRegistry->getRunning()
        );
    }
    
    public function testUnregisterRunning()
    {
        $this->_sequenceRegistry->unregisterRunning($this->_sequence1);
        $this->assertEmpty($this->_sequenceRegistry->getRunning());
    }
    
    public function testUnregisterAllRunning()
    {
        $this->_sequenceRegistry->unregisterAllRunning();
        $this->assertEmpty($this->_sequenceRegistry->getRunning());
    }
    
    public function testResetBrokenRunning()
    {
        $button1 = new Button('Button1');
        $button1->registerSequence($this->_sequence1);
        $button1->setSequenceRegistry($this->_sequenceRegistry);
        $this->_sequence1->append($button1);
        
        $button2 = new Button('Button2');
        $button2->registerSequence($this->_sequence1);
        $button2->setSequenceRegistry($this->_sequenceRegistry);
        $this->_sequence1->append($button2);
        
        $this->_sequence1->next();
        $this->_sequenceRegistry->resetBrokenRunning($button1);
        
        $this->assertSame($this->_sequence1->getCurrent(), $button1);
        $this->assertEmpty($this->_sequenceRegistry->getRunning());
    }
}
