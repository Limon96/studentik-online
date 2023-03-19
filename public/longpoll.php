<?php

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}
// Startup
require_once(DIR_SYSTEM . 'startup.php');

require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/longpoll.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Request
$request = new Request();
$registry->set('request', $request);


if (isset($request->get['key'])) {
    if (isset($request->get['ts'])) {
        $ts = (int)$request->get['ts'];
    } else {
        $ts = time();
    }

    $longpoll = new LongPoll($registry->get('db'));

    $json = $longpoll->connect($request->get['key'], $ts);

    header('Content-Type: application/json');

    echo json_encode($json);

} else {
    die('Access Denied');
};
