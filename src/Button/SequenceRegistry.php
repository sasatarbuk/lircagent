<?php

namespace Lirc\Button;

class SequenceRegistry
{
    private $_running = array();
    
    public function resetBrokenRunning(Button $currentButton)
    {
        foreach ($this->_running as $key => $sequence) {
            if ($sequence->getCurrent() !== $currentButton) {
                $sequence->reset();
                $this->unregisterRunning($sequence);
            }
        }
    }
    
    public function registerRunning(Sequence $sequence)
    {
        if (array_search($sequence, $this->_running, true) === false) {
            $this->_running[] = $sequence;
        }
    }
    
    public function unregisterRunning(Sequence $sequence)
    {
        if (($key = array_search($sequence, $this->_running, true)) !== false) {
            unset($this->_running[$key]);
        }
    }
    
    public function getRunning()
    {
        return $this->_running;
    }
    
    public function unregisterAllRunning()
    {
        foreach ($this->_running as $key => $sequence) {
            unset($this->_running[$key]);
        }
    }
}
