<?php

if (isLaravelUrl()) {
    include __DIR__ . '/laravel.php';
} else {
    include __DIR__ . '/opencart.php';
}

function getPrep($str)
{
    return '/^\/' . addslashes($str) . '(.*)/m';
}
function isPregMatch($str)
{
    return preg_match(getPrep($str), $_SERVER['REQUEST_URI']);
}

function isLaravelUrl()
{
    return isPregMatch('_manager')
        || isPregMatch('_debugbar')
        || isPregMatch('_create_session')
        || isPregMatch('blog')
        || isPregMatch('sign_in')
        || isPregMatch('sign_up')
        || isPregMatch('faq')
        || isPregMatch('new-order')
        || isPregMatch('socket')
        || isPregMatch('logout')
        || isPregMatch('orders')
        || isPregMatch('create-order')
        || isPregMatch('_autocomplete')
        || isPregMatch('order')
        //|| isPregMatch('edit')
    ;
}
