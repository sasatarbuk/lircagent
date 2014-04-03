<?php

namespace Lirc;

class AgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Lirc\Exception
     */
    public function testCannotConnect()
    {
        $handler = new DummyHandler();
        new Agent(
            '0.0.0.0',
            null,
            null,
            $handler
        );
    }
    
    public function testIteration()
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped("pcntl_fork() is not available");
        }
        
        $pid = pcntl_fork();

        if ($pid == -1) {
            $this->markTestSkipped('Could not fork process');
            
        } elseif ($pid == 0) {
            // Setup fake Lirc dameon
            $socket = stream_socket_server('tcp://0.0.0.0:24999');
            $connection = stream_socket_accept($socket);
            $content =
                '0000000000000000 00 Right Remote.conf'."\n" .
                '0000000000000000 01 Right Remote.conf'."\n" .
                '0000000000000000 02 Right Remote.conf'."\n" .
                '0000000000000000 03 Right Remote.conf'."\n" .
                '0000000000000000 04 Right Remote.conf'."\n" .
                '0000000000000000 05 Right Remote.conf'."\n";
            
            // Wait for signal and send commands
            fread($connection, 1024);
            fwrite($connection, $content);
            
            // Wait for signal and exit this process
            fread($connection, 1024);
            fclose($connection);
            fclose($socket);
            
            exit;
            
        } else {
            // Give child process some time to
            // setup fake Lirc deamon. Fugly...
            usleep(100000);
            
            $handler = new DummyHandler();
            $agent = new Agent(
                'tcp://0.0.0.0:24999',
                __DIR__.'/../samples/lircrc',
                'LircAgent Test',
                $handler
            );
            
            // Notify child that it may fire buttons
            $socket = $agent->getSocket();
            fwrite($socket, 'send');
            
            // Give child process some time to
            // recieve the command. Again, fugly...
            usleep(100000);
            
            // Handle iteration and notify child
            $pressed = $agent->iteration();
            fwrite($socket, 'send');
            
            // Make sure child process
            // ended before proceeding
            pcntl_wait($status);
        }
        
        list($configsRun, $button, $iteration, $remote) = $pressed[0];
        $this->assertSame('VOL_UP', $configsRun[0]->getName());
        $this->assertSame('Right', $button->getName());
        $this->assertSame(0, $iteration);
        $this->assertSame('Remote.conf', $remote);
        
        list($configsRun, $button, $iteration, $remote) = $pressed[1];
        $this->assertFalse($configsRun[0]);
        $this->assertSame(1, $iteration);
        
        list($configsRun, $button, $iteration, $remote) = $pressed[2];
        $this->assertFalse($configsRun[0]);
        $this->assertSame(2, $iteration);
        
        list($configsRun, $button, $iteration, $remote) = $pressed[3];
        $this->assertFalse($configsRun[0]);
        $this->assertSame(3, $iteration);
        
        list($configsRun, $button, $iteration, $remote) = $pressed[4];
        $this->assertSame('VOL_UP', $configsRun[0]->getName());
        $this->assertSame(4, $iteration);
        
        list($configsRun, $button, $iteration, $remote) = $pressed[5];
        $this->assertSame('VOL_UP', $configsRun[0]->getName());
        $this->assertSame(5, $iteration);
        
        unset($agent);
    }
}
