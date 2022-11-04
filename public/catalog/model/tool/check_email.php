<?php

define('DEBUG_OK', false);

class ModelToolCheckEmail extends Model
{
    var $timeout = 10;
    var $domain_rules = array("aol.com", "bigfoot.com", "brain.net.pk", "breathemail.net",
        "compuserve.com", "dialnet.co.uk", "glocksoft.com", "home.com",
        "msn.com", "rocketmail.com", "uu.net", "yahoo.com", "yahoo.de");

    private function _is_valid_email($email = "")
    {
        return preg_match('/^[.\w-]+@([\w-]+\.)+[a-zA-Z]{2,6}$/', $email);
    }

    private function _check_domain_rules($domain = "")
    {
        return in_array(strtolower($domain), $this->domain_rules);
    }

    public function execute($email = "")
    {
        return true;

        if (!$this->_is_valid_email($email)) return false;
        $host = substr(strstr($email, '@'), 1);

        if ($this->_check_domain_rules($host)) return false;
        $host .= ".";

        if (getmxrr($host, $mxhosts[0], $mxhosts[1]) == true) array_multisort($mxhosts[1], $mxhosts[0]);
        else {
            $mxhosts[0] = $host;
            $mxhosts[1] = 10;
        }
        if (DEBUG_OK) print_r($mxhosts);

        $port = 25;
        $localhost = $_SERVER['HTTP_HOST'];
        $sender = 'info@' . $localhost;

        $result = false;
        $id = 0;

        if (!isset($mxhosts[0])) {
            return $result;
        }

        if (!is_array($mxhosts[0])) {
            return $result;
        }

        while (!$result && $id < count($mxhosts[0])) {
            if (function_exists("fsockopen")) {
                if (DEBUG_OK) print_r($id . " " . $mxhosts[0][$id]);
                if ($connection = fsockopen($mxhosts[0][$id], $port, $errno, $error, $this->timeout)) {
                    fputs($connection, "HELO $localhost\r\n"); // 250
                    $data = fgets($connection, 1024);
                    $response = substr($data, 0, 1);
                    if (DEBUG_OK) print_r($data);
                    if ($response == '2') // 200, 250 etc.
                    {
                        fputs($connection, "MAIL FROM:<$sender>\r\n");
                        $data = fgets($connection, 1024);
                        $response = substr($data, 0, 1);
                        if (DEBUG_OK) print_r($data);
                        if ($response == '2') // 200, 250 etc.
                        {
                            fputs($connection, "RCPT TO:<$email>\r\n");
                            $data = fgets($connection, 1024);
                            $response = substr($data, 0, 1);
                            if (DEBUG_OK) print_r($data);
                            if ($response == '2') // 200, 250 etc.
                            {
                                fputs($connection, "data\r\n");
                                $data = fgets($connection, 1024);
                                $response = substr($data, 0, 1);
                                if (DEBUG_OK) print_r($data);
                                if ($response == '2') // 200, 250 etc.
                                {
                                    $result = true;
                                }
                            }
                        }
                    }
                    fputs($connection, "QUIT\r\n");
                    fclose($connection);
                    if ($result) return true;
                }
            } else  break;
            $id++;
        } //while
        return false;
    }

}
