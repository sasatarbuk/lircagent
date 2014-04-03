<?php

namespace Lirc;

interface HandlerInterface
{
    public function handleConfig($config, $iteration, $remote);
}
