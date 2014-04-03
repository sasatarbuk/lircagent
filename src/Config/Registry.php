<?php

namespace Lirc\Config;

use Lirc\Registry\Registry as BaseRegistry;

class Registry extends BaseRegistry
{
    public function __construct()
    {
        // Create registry for Config objects
        parent::__construct('\\Lirc\\Config\\Config');
    }
}
