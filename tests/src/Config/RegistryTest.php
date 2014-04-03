<?php

namespace Lirc\Config;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistryType()
    {
        $registry = new Registry();
        $this->assertInstanceOf('Lirc\\Config\\Config', $registry->get('Config1'));
    }
}
