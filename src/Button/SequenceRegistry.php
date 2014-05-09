<?php

namespace Lirc\Button;

class SequenceRegistry
{
    private $running = array();
    
    public function resetBrokenRunning(Button $currentButton)
    {
        foreach ($this->running as $key => $sequence) {
            if ($sequence->getCurrent() !== $currentButton) {
                $sequence->reset();
                $this->unregisterRunning($sequence);
            }
        }
    }
    
    public function registerRunning(Sequence $sequence)
    {
        if (array_search($sequence, $this->running, true) === false) {
            $this->running[] = $sequence;
        }
    }
    
    public function unregisterRunning(Sequence $sequence)
    {
        if (($key = array_search($sequence, $this->running, true)) !== false) {
            unset($this->running[$key]);
        }
    }
    
    public function getRunning()
    {
        return $this->running;
    }
    
    public function unregisterAllRunning()
    {
        foreach ($this->running as $key => $sequence) {
            unset($this->running[$key]);
        }
    }
}
