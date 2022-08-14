<?php

if (isLaravelUrl()) {
    include __DIR__ . '/laravel.php';
} else {
    include __DIR__ . '/opencart.php';
}

function getPrep($str)
{
    return '/\/' . addslashes($str) . '\/(.*)/m';
}

function isLaravelUrl()
{
    return preg_match(getPrep('blog'), $_SERVER['REQUEST_URI'])
        || preg_match(getPrep('new-order'), $_SERVER['REQUEST_URI'])
        || preg_match(getPrep('_manager'), $_SERVER['REQUEST_URI'])
    ;
}
