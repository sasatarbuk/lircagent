<?php

namespace Lirc\Button;

use Lirc\Registry\Registry as BaseRegistry;

class Registry extends BaseRegistry
{
    public function __construct()
    {
        // Create registry for Button objects
        parent::__construct('\\Lirc\\Button\\Button');
    }
}
