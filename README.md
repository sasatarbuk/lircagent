LircAgent
=========
[![Build Status](https://travis-ci.org/sasatarbuk/lircagent.svg?branch=master)](https://travis-ci.org/sasatarbuk/lircagent)

LircAgent is a simple library for receiving LIRC events in your PHP application. 

Installation
------------
Composer is required to generate the autoloader. Go to your cloned directory and run:
```
composer install
```

Usage
-----

Define your .lircrc file (use your remote's buttons):
```
begin
     prog = MyApplication
     button = Down
     config = VOL_DOWN
     repeat = 1
     delay = 3
end

begin
     prog = MyApplication
     button = Up
     config = VOL_UP
     repeat = 1
     delay = 3
end
```

Define your own handler:
```php
<?php

class MyHandler implements Lirc\HandlerInterface
{
    public function handleConfig($config, $iteration, $remote)
    {
        echo "Config: {$config}, Iteration: {$iteration}, Remote: {$remote}\n";
    }
}
```

Initialize and listen for events:
```php
<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/MyHandler.php';

// Unix socket of the LIRC daemon
$socket = 'unix:///var/run/lirc/lircd';

// Your .lircrc file
$lircrc = __DIR__ . '/.lircrc';

// Your application name used in .lircrc
$appName = 'MyApplication';

// Your handler
$myHandler = new MyHandler();

$agent = new Lirc\Agent($socket, $lircrc, $appName, $myHandler);
$agent->loop();
```

Non-blocking mode:
```php
while(true) {
    $agent->iteration();
    usleep(20000);
}
```
