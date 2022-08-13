<?php

namespace Crypt\Libs;

class SHA1 {

    static public function generate($key)
    {
        return sha1($key);
    }

}