<?php

class ParserSession {

    public static function getSessionId()
    {
        if (!static::validSessionName()) {
            return null;
        }

        $session_id = static::encrypter()->decrypt($_COOKIE['studentik_session'], false);

        $session_id = explode('|', $session_id);

        return $session_id[1] ?? null;
    }

    public static function getUserId($session_data)
    {
        foreach ($session_data as $key => $value) {
            $preg = preg_match('/(login_web_)([0-9a-z]*)/', $key, $matches);
            if ($matches) {
                return $value;
            }
        }

        return 0;
    }

    private static function validSessionName()
    {
        return isset($_COOKIE['studentik_session']) && $_COOKIE['studentik_session'] !== '';
    }


    private static function encrypter()
    {
        return new \Illuminate\Encryption\Encrypter(base64_decode(static::key()), static::cipher());
    }

    private static function key()
    {
        $dotenv = new DotEnv(DIR_LARAVEL);
        return str_replace('base64', '', $dotenv->get('APP_KEY'));
    }

    private static function cipher()
    {
        return 'AES-256-CBC';
    }

}
