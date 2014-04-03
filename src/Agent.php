<?php

namespace Lirc;

class Agent
{
    private $_socket;
    private $_configRegistry = null;
    private $_buttonRegistry = null;
    private $_buttonSequenceRegistry;
    
    public function __construct($socket, $rcFile, $program, HandlerInterface $handler)
    {
        if (@!($this->_socket = stream_socket_client($socket))) {
            throw new Exception("Cannot connect to specified socket");
        }
        
        $this->_configRegistry = new Config\Registry();
        $this->_buttonRegistry = new Button\Registry();
        $this->_buttonSequenceRegistry = new Button\SequenceRegistry();
        
        $events = Parser::parseRc($rcFile, $program);
        foreach ($events as $event) {
            $this->_registerEvent($event, $handler);
        }
    }
    
    public function __destruct()
    {
        $this->_buttonSequenceRegistry->unregisterAllRunning();
        
        foreach ($this->_buttonRegistry->getRegistry() as $button) {
            $button->unregisterAllSequences();
        }
        
        stream_socket_shutdown($this->_socket, STREAM_SHUT_RDWR);
    }
    
    public function getSocket()
    {
        return $this->_socket;
    }
    
    public function loop()
    {
        if (!$this->_socket) {
            throw new Exception("No connection");
        }
        
        while (true) {
            $input = fread($this->_socket, 1024);
            foreach (Parser::parseInput($input) as $input) {
                $button = $this->_buttonRegistry->get($input['button']);
                $button->handlePress($input['iteration'], $input['remote']);
            }
        }
    }
    
    public function iteration($flush = false)
    {
        $read   = array($this->_socket);
        $write  = null;
        $except = null;
        $input  = '';
        
        while (stream_select($read, $write, $except, 0) > 0) {
            $input .= fread($this->_socket, 1024);
        }
        
        $output = array();
        if (!$flush && $input != '') {
            foreach (Parser::parseInput($input) as $input) {
                $button = $this->_buttonRegistry->get($input['button']);
                $configsRun = $button->handlePress($input['iteration'], $input['remote']);
                $output[] = array($configsRun, $button, $input['iteration'], $input['remote']);
            }
        }
        return $output;
    }
    
    private function _registerEvent($rc, HandlerInterface $handler)
    {
        if (isset($rc['button']) && isset($rc['config'])) {
            
            $configSequence  = new Config\Sequence();
            $buttonSequence  = new Button\Sequence($configSequence);
            
            foreach ($rc['button'] as $button) {
                $button = $this->_buttonRegistry->get($button);
                $button->registerSequence($buttonSequence);
                $button->setSequenceRegistry(
                    $this->_buttonSequenceRegistry
                );
                $buttonSequence->append($button);
            }
            
            foreach ($rc['config'] as $config) {
                $config = $this->_configRegistry->get($config);
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
