<?php

namespace Lirc\Button;

class ButtonTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterUnregisterSequences()
    {
        $configSequence  = $this->getMock('Lirc\\Config\\Sequence');
        $buttonSequence1 = $this->getMock(
            'Lirc\\Button\\Sequence',
            null,
            array($configSequence)
        );
        
        $button = new Button('Button');
        $button->registerSequence($buttonSequence1);
        
        $this->assertSame(
            array($buttonSequence1),
            $button->getSequences()
        );
        
        $button->unregisterAllSequences();
        $this->assertEmpty($button->getSequences());
    }
    
    public function testHandlePress()
    {
        $configSequence = $this->getMock('Lirc\\Config\\Sequence');
        $buttonSequence = $this->getMock(
            'Lirc\\Button\\Sequence',
            array('runConfig'),
            array($configSequence)
        );
        
        $buttonSequenceRegistry = $this->getMock(
            'Lirc\\Button\\SequenceRegistry',
            array('resetBrokenRunning')
        );
        
        $button1 = new Button('Button1');
        $button1->registerSequence($buttonSequence);
        $button1->setSequenceRegistry($buttonSequenceRegistry);
        $buttonSequence->append($button1);
        
        $button2 = new Button('Button2');
        $button2->registerSequence($buttonSequence);
        $button2->setSequenceRegistry($buttonSequenceRegistry);
        $buttonSequence->append($button2);
        
        $buttonSequenceRegistry
            ->expects($this->exactly(1))
            ->method('resetBrokenRunning')
            ->with($this->equalTo($button1));
        
        $button1->handlePress('0', 'Remote.conf');
        $this->assertSame($button2, $buttonSequence->getCurrent());
        $this->assertSame(
            array($buttonSequence),
            $buttonSequenceRegistry->getRunning()
        );
        
        $buttonSequence
            ->expects($this->exactly(1))
            ->method('runConfig')
            ->with(
                $this->equalTo('1'),
                $this->equalTo('Remote.conf')
            );
        
        $button2->handlePress('1', 'Remote.conf');
        $this->assertSame($button1, $buttonSequence->getCurrent());
        $this->assertSame(
            array(),
            $buttonSequenceRegistry->getRunning()
        );
        
    }
}
