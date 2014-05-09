<?php

namespace Lirc\Sequence;

abstract class Sequence
{
    private $items = array();
    protected $currentItemKey = 0;
    
    public function append(Item $item)
    {
        $this->items[] = $item;
    }
    
    public function next()
    {
        if ($this->currentItemKey + 1 >= count($this->items)) {
            $this->currentItemKey = 0;
        } else {
            $this->currentItemKey ++;
        }
        return $this->getCurrent();
    }
    
    public function getNext()
    {
        if (isset($this->items[$this->currentItemKey + 1])) {
            return $this->items[$this->currentItemKey + 1];
        }
        return null;
    }
    
    public function getCurrent()
    {
        if (isset($this->items[$this->currentItemKey])) {
            return $this->items[$this->currentItemKey];
        }
        return null;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function getLength()
    {
        return count($this->items);
    }
    
    public function isAtStart()
    {
        return $this->currentItemKey === 0;
    }
}
