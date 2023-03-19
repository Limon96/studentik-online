<?php

namespace Task\Event;

use Task\TaskItem;

class Cash {

    private $db;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
}