<?php

namespace Lirc\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    private $_registry;
    
    public function setUp()
    {
        $this->_registry = $this->getMockForAbstractClass(
            'Lirc\\Registry\\Registry',
            array('Lirc\\Button\\Button')
        );
    }
    
    public function testGet()
    {
        $button1 = $this->_registry->get('Button1');
        $this->assertInstanceOf('Lirc\\Button\\Button', $button1);
    }
    
    public function testGetRegistry()
    {
        $expected = array(
            'Button1' => $this->_registry->get('Button1'),
            'Button2' => $this->_registry->get('Button2'),
        );
        $actual = $this->_registry->getRegistry();
        $this->assertSame($expected, $actual);
    }
}
