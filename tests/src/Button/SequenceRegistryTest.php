<?php

namespace Lirc\Button;

use Lirc\Config\Sequence as ConfigSequence;

class SequenceRegistryTest extends \PHPUnit_Framework_TestCase
{
    private $sequence1;
    private $sequence2;
    private $sequenceRegistry;
    
    public function setUp()
    {
        $configSequence   = new ConfigSequence();
        $this->sequence1 = new Sequence($configSequence);
        $this->sequence2 = new Sequence($configSequence);
        
        $this->sequenceRegistry = new SequenceRegistry();
        $this->sequenceRegistry->registerRunning($this->sequence1);
    }
    
    public function testRegisterRunning()
    {
        // Register $this->sequence1 twice!
        $this->sequenceRegistry->registerRunning($this->sequence1);
        $this->sequenceRegistry->registerRunning($this->sequence2);
        $this->assertSame(
            array(
                $this->sequence1,
                $this->sequence2,
            ),
            $this->sequenceRegistry->getRunning()
        );
    }
    
    public function testUnregisterRunning()
    {
        $this->sequenceRegistry->unregisterRunning($this->sequence1);
        $this->assertEmpty($this->sequenceRegistry->getRunning());
    }
    
    public function testUnregisterAllRunning()
    {
        $this->sequenceRegistry->unregisterAllRunning();
        $this->assertEmpty($this->sequenceRegistry->getRunning());
    }
    
    public function testResetBrokenRunning()
    {
        $button1 = new Button('Button1');
        $button1->registerSequence($this->sequence1);
        $button1->setSequenceRegistry($this->sequenceRegistry);
        $this->sequence1->append($button1);
        
        $button2 = new Button('Button2');
        $button2->registerSequence($this->sequence1);
        $button2->setSequenceRegistry($this->sequenceRegistry);
        $this->sequence1->append($button2);
        
        $this->sequence1->next();
        $this->sequenceRegistry->resetBrokenRunning($button1);
        
        $this->assertSame($this->sequence1->getCurrent(), $button1);
        $this->assertEmpty($this->sequenceRegistry->getRunning());
    }
}
