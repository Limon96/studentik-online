<?php

// Version
const VERSION = '3.0.3.7';
const VERSION_SCRIPTS = '0.0.14.8';

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
    header('Location: install/index.php');
    exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');
