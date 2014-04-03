<?php

namespace Lirc;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $_sampleLircrc;
    
    public function setUp()
    {
        $this->_sampleLircrc = realpath(
            __DIR__ . '/../samples/lircrc'
        );
    }
    
    public function testParseRc()
    {
        $parsed = Parser::parseRc($this->_sampleLircrc, 'LircAgent Test');
        $this->assertCount(15, $parsed);
        
        $this->assertSame($parsed[0]['remote'], 'LircAgent Remote');
        $this->assertSame($parsed[0]['prog'], 'LircAgent Test');
        $this->assertSame($parsed[0]['button'], array('Green'));
        $this->assertSame($parsed[8]['config'], array('NEXT1', 'NEXT2'));
        $this->assertSame($parsed[2]['repeat'], '2');
        $this->assertSame($parsed[2]['delay'], '4');
    }
    
    /**
     * @expectedException Lirc\Exception
     */
    public function testParseRcError()
    {
        $parsed = Parser::parseRc($this->_sampleLircrc.'/fake', 'LircAgent Test');
    }
    
    public function testParseInput()
    {
        $parsed = Parser::parseInput(
            "0000000000000a97 ff Ok Remote.conf\n".
            "0000000000000a97 100 Ok Remote.conf"
        );
        
        $expected = array(
            array(
                'remote' => 'Remote.conf',
                'button' => 'Ok',
                'iteration' => 255,
            ),
            array(
                'remote' => 'Remote.conf',
                'button' => 'Ok',
                'iteration' => 256,
            ),
        );
        
        $this->assertSame($expected, $parsed);
    }
}
