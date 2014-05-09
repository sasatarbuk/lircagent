<?php

namespace Lirc\Config;

use Lirc\Sequence\Sequence as BaseSequence;

class Sequence extends BaseSequence
{
    private $remote;
    private $repeat = 0;
    private $delay = 0;
    
    public function setRemote($remote)
    {
        $this->remote = $remote;
    }
    
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;
    }
    
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }
    
    public function getRemote()
    {
        return $this->remote;
    }
    
    public function getRepeat()
    {
        return $this->repeat;
    }
    
    public function getDelay()
    {
        return $this->delay;
    }
}
