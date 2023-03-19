<?php

namespace Crypt\Libs;

class Encrypt {

    static public function generate($key)
    {
        return crypt($key, md5(microtime(true)));
    }

}