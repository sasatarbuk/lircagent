<?php

namespace Lirc\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    private $registry;
    
    public function setUp()
    {
        $this->registry = $this->getMockForAbstractClass(
            'Lirc\\Registry\\Registry',
            array('Lirc\\Button\\Button')
        );
    }
    
    public function testGet()
    {
        $button1 = $this->registry->get('Button1');
        $this->assertInstanceOf('Lirc\\Button\\Button', $button1);
    }
    
    public function testGetRegistry()
    {
        $expected = array(
            'Button1' => $this->registry->get('Button1'),
            'Button2' => $this->registry->get('Button2'),
        );
        $actual = $this->registry->getRegistry();
        $this->assertSame($expected, $actual);
    }
}
