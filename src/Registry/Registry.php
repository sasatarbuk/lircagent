<?php

namespace Lirc\Registry;

abstract class Registry
{
    private $registry = array();
    private $type;
    
    public function __construct($type)
    {
        $this->type = $type;
    }
    
    public function get($name)
    {
        if (!isset($this->registry[$name])) {
            $this->registry[$name] = new $this->type($name);
        }
        return $this->registry[$name];
    }
    
    public function getRegistry()
    {
        return $this->registry;
    }
}
