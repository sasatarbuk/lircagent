<?php

namespace Lirc\Config;

use Lirc\HandlerInterface;
use Lirc\Sequence\Item as SequenceItem;

class Config extends SequenceItem
{
    private $handler;
    
    public function run($iteration, $remote)
    {
        $this->handler->handleConfig($this->getName(), $iteration, $remote);
    }
    
    public function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }
}
