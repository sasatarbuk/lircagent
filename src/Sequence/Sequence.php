<?php

namespace Lirc\Sequence;

abstract class Sequence
{
    private $_items = array();
    protected $_currentItemKey = 0;
    
    public function append(Item $item)
    {
        $this->_items[] = $item;
    }
    
    public function next()
    {
        if ($this->_currentItemKey + 1 >= count($this->_items)) {
            $this->_currentItemKey = 0;
        } else {
            $this->_currentItemKey ++;
        }
        return $this->getCurrent();
    }
    
    public function getNext()
    {
        if (isset($this->_items[$this->_currentItemKey + 1])) {
            return $this->_items[$this->_currentItemKey + 1];
        }
        return null;
    }
    
    public function getCurrent()
    {
        if (isset($this->_items[$this->_currentItemKey])) {
            return $this->_items[$this->_currentItemKey];
        }
        return null;
    }
    
    public function getItems()
    {
        return $this->_items;
    }
    
    public function getLength()
    {
        return count($this->_items);
    }
    
    public function isAtStart()
    {
        return $this->_currentItemKey === 0;
    }
}
