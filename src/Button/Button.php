<?php

namespace Lirc\Button;

use Lirc\Sequence\Item as SequenceItem;

class Button extends SequenceItem
{
    private $_sequences = array();
    private $_sequenceRegistry;
    
    public function registerSequence(Sequence $sequence)
    {
        if (array_search($sequence, $this->_sequences, true) === false) {
            $this->_sequences[] = $sequence;
        }
    }
    
    public function unregisterAllSequences()
    {
        foreach ($this->_sequences as $key => $sequence) {
            unset($this->_sequences[$key]);
        }
    }
    
    public function getSequences()
    {
        return $this->_sequences;
    }
    
    public function setSequenceRegistry(SequenceRegistry $sequenceRegistry)
    {
        $this->_sequenceRegistry = $sequenceRegistry;
    }
    
    public function handlePress($iteration, $remote)
    {
        // No registered sequences for this button - exit
        if (count($this->_sequences) < 1) {
            return array();
        }
        
        // Reset broken sequences. Iteration must be zero so that subsequent
        // iterations of the same button are not detected as broken
        if ($iteration == 0) {
            $this->_sequenceRegistry->resetBrokenRunning($this);
        }
        
        $configsRun = array();
        
        // Find sequences where the current button is on cursor
        foreach ($this->_sequences as $sequence) {
            if ($sequence->getCurrent() === $this) {
            
                // Move sequence cursor
                $sequence->next();
                
                if ($sequence->isAtStart()) {
                    // Run config on cursor reset
                    $configsRun[] = $sequence->runConfig($iteration, $remote);
                    
                    // One button sequences need not be unregistered from the
                    // running list since they are not registered in the first
                    // place, only true sequences do
                    if ($sequence->getLength() > 1) {
                        $this->_sequenceRegistry->unregisterRunning($sequence);
                    }
                } else {
                    // The cursor has moved beyond the first item in sequence,
                    // register this sequence as running
                    $this->_sequenceRegistry->registerRunning($sequence);
                }
            }
        }
        return $configsRun;
    }
}
