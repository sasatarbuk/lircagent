<?php

namespace Lirc;

class Parser
{
    public static function parseRc($fileName, $program)
    {
        @ $file = file_get_contents($fileName);
        if ($file === false) {
            $message = "Lircrc file not found or inaccessible; Cannot parse";
            throw new Exception($message);
        }
        
        $file = explode("\n", $file);
        $rc = array();
        $rcs = array();
        
        $multiTypes = array('button', 'config');
        $singleTypes = array('remote', 'repeat', 'delay', 'prog');
        
        foreach ($file as $line) {
            $line = trim($line);
            
            if (empty($line) || $line[0] == '#') {
                // Skip comments
                continue;
            } elseif ($line == 'begin') {
                // Start new entry
                $rc = array();
            } elseif ($line == 'end') {
                // Add current entry to stack
                // Only Register given program's events
                if (isset($rc['prog']) && $rc['prog'] == $program) {
                    $rcs[] = $rc;
                }
            } elseif ($type = self::getType($line, $multiTypes)) {
                $rc[$type][] = self::getParam($line);
            } elseif ($type = self::getType($line, $singleTypes)) {
                $rc[$type] = self::getParam($line);
            }
        }
        return $rcs;
    }
    
    public static function parseInput($input)
    {
        $input = explode("\n", $input);
        $input = array_filter($input);
        $pattern = '@^[0-9a-f]{16}\s+([0-9a-f]+)\s+([^\s]+)\s+([^\s]+)\z@i';
        $final = array();
        foreach ($input as $line) {
            if (preg_match($pattern, $line, $matches) > 0) {
                $final[] = array(
                    'remote' => $matches[3],
                    'button' => $matches[2],
                    'iteration' => hexdec($matches[1]),
                );
            }
        }
        return $final;
    }
    
    private static function getType($line, $types)
    {
        foreach ($types as $type) {
            if (strpos($line, $type) === 0) {
                return $type;
            }
        }
        return false;
    }
    
    private static function getParam($line)
    {
        return trim(substr($line, strpos($line, '=') + 1));
    }
}
