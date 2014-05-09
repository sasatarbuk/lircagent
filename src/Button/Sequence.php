<?php

namespace Lirc\Button;

use Lirc\Sequence\Sequence as BaseSequence;
use Lirc\Config\Sequence as ConfigSequence;

class Sequence extends BaseSequence
{
    private $configSequence;
    
    public function __construct(ConfigSequence $configSequence)
    {
        $this->configSequence = $configSequence;
    }
    
    public function getConfigSequence()
    {
        return $this->configSequence;
    }
    
    public function reset()
    {
        $this->currentItemKey = 0;
        return $this->getCurrent();
    }
    
    public function runConfig($iteration, $remote)
    {
        $configSequence = $this->getConfigSequence();
        
        // Check if this sequence responds to specific remotes
        $targetRemote = $configSequence->getRemote();
        if ($targetRemote && $targetRemote != $remote) {
            return false;
        }
        
        $repeat = $configSequence->getRepeat();
        $delay  = $configSequence->getDelay();
        
        // Set $iterRepeat to be $delay iterations behind,
        // subtract one because first iteration always fires
        if ($delay > 0) {
            $iterRepeat = $iteration - $delay - 1;
        } else {
            $iterRepeat = $iteration;
        }
        
        // Run config and move sequence cursor. Skip cycles as indicated in the
        // repeat parameter of the config sequence
        if ($iteration == 0 || ($iterRepeat >= 0 && $repeat != 0 && $iterRepeat % $repeat == 0)) {
            $config = $configSequence->getCurrent();
            $config->run($iteration, $remote);
            $configSequence->next();
            return $config;
        }
        
        return false;
    }
}
