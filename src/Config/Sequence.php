<?php

namespace Lirc\Config;

use Lirc\Sequence\Sequence as BaseSequence;

class Sequence extends BaseSequence
{
    private $_remote;
    private $_repeat = 0;
    private $_delay = 0;
    
    public function setRemote($remote)
    {
        $this->_remote = $remote;
    }
    
    public function setRepeat($repeat)
    {
        $this->_repeat = $repeat;
    }
    
    public function setDelay($delay)
    {
        $this->_delay = $delay;
    }
    
    public function getRemote()
    {
        return $this->_remote;
    }
    
    public function getRepeat()
    {
        return $this->_repeat;
    }
    
    public function getDelay()
    {
        return $this->_delay;
    }
}
