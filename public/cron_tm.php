<?php

ini_set('default_charset', "UTF-8");

date_default_timezone_set('Europe/Moscow');

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}
// Startup
require_once(DIR_SYSTEM . 'startup.php');

require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/taskmanager.php');

// Registry
$registry = new Registry();


// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Config
$config = new Config();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $result) {
    if (!$result['serialized']) {
        $config->set($result['key'], $result['value']);
    } else {
        $config->set($result['key'], json_decode($result['value'], true));
    }
}
$registry->set('config', $config);


// Request
$request = new Request();
$registry->set('request', $request);

// Manager Run
$taskManager = new TaskManager($db, $config);

if (isset($request->server['argv'][1])) {
    $channel = $request->server['argv'][1];
} elseif (isset($request->get['channel'])) {
    $channel = $request->get['channel'];
} else {
    $channel = 'default';
}

$taskManager->run($channel);
