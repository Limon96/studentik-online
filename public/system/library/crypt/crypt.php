<?php
namespace Crypt;
use Braintree\Exception;

class Crypt
{

    static function generate($adapter, $key)
    {
        $adapter = 'Crypt\Libs\\' . $adapter;

        if (!class_exists($adapter)) {
            throw new Exception('Error class ' . $adapter . ' not exists');
        }

        $class = new $adapter();

        return $class->generate($key);
    }

}
