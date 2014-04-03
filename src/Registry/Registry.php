<?php

namespace Lirc\Registry;

abstract class Registry
{
    private $_registry = array();
    private $_type;
    
    public function __construct($type)
    {
        $this->_type = $type;
    }
    
    public function get($name)
    {
        if (!isset($this->_registry[$name])) {
            $this->_registry[$name] = new $this->_type($name);
        }
        return $this->_registry[$name];
    }
    
    public function getRegistry()
    {
        return $this->_registry;
    }
}
