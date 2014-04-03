<?php

namespace Lirc\Button;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistryType()
    {
        $registry = new Registry();
        $this->assertInstanceOf('Lirc\\Button\\Button', $registry->get('Button1'));
    }
}
