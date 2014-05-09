<?php

namespace Lirc;

class Agent
{
    private $socket;
    private $configRegistry = null;
    private $buttonRegistry = null;
    private $buttonSequenceRegistry;
    
    public function __construct($socket, $rcFile, $program, HandlerInterface $handler)
    {
        if (@!($this->socket = stream_socket_client($socket))) {
            throw new Exception("Cannot connect to specified socket");
        }
        
        $this->configRegistry = new Config\Registry();
        $this->buttonRegistry = new Button\Registry();
        $this->buttonSequenceRegistry = new Button\SequenceRegistry();
        
        $events = Parser::parseRc($rcFile, $program);
        foreach ($events as $event) {
            $this->registerEvent($event, $handler);
        }
    }
    
    public function __destruct()
    {
        $this->buttonSequenceRegistry->unregisterAllRunning();
        
        foreach ($this->buttonRegistry->getRegistry() as $button) {
            $button->unregisterAllSequences();
        }
        
        stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
    }
    
    public function getSocket()
    {
        return $this->socket;
    }
    
    public function loop()
    {
        if (!$this->socket) {
            throw new Exception("No connection");
        }
        
        while (true) {
            $input = fread($this->socket, 1024);
            foreach (Parser::parseInput($input) as $input) {
                $button = $this->buttonRegistry->get($input['button']);
                $button->handlePress($input['iteration'], $input['remote']);
            }
        }
    }
    
    public function iteration($flush = false)
    {
        $read   = array($this->socket);
        $write  = null;
        $except = null;
        $input  = '';
        
        while (stream_select($read, $write, $except, 0) > 0) {
            $input .= fread($this->socket, 1024);
        }
        
        $output = array();
        if (!$flush && $input != '') {
            foreach (Parser::parseInput($input) as $input) {
                $button = $this->buttonRegistry->get($input['button']);
                $configsRun = $button->handlePress($input['iteration'], $input['remote']);
                $output[] = array($configsRun, $button, $input['iteration'], $input['remote']);
            }
        }
        return $output;
    }
    
    private function registerEvent($rc, HandlerInterface $handler)
    {
        if (isset($rc['button']) && isset($rc['config'])) {
            
            $configSequence  = new Config\Sequence();
            $buttonSequence  = new Button\Sequence($configSequence);
            
            foreach ($rc['button'] as $button) {
                $button = $this->buttonRegistry->get($button);
                $button->registerSequence($buttonSequence);
                $button->setSequenceRegistry(
                    $this->buttonSequenceRegistry
                );
                $buttonSequence->append($button);
            }
            
            foreach ($rc['config'] as $config) {
                $config = $this->configRegistry->get($config);
                $config->setHandler($handler);
                $configSequence->append($config);
            }
            
            if (isset($rc['remote'])) {
                $configSequence->setRemote($rc['remote']);
            }
            
            if (isset($rc['repeat'])) {
                $configSequence->setRepeat($rc['repeat']);
            }
            
            if (isset($rc['delay'])) {
                $configSequence->setDelay($rc['delay']);
            }
        }
    }
}
