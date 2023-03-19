<?php

class DotEnv {

    private $data = [];

    public function __construct($dir)
    {
        try {
            $string = file_get_contents($dir . '.env');
        } catch (Exception $exception) {
            die('File not exists');
        }

        $this->load($string);
    }

    public function get($key = '')
    {
        return $this->data[$key] ?? null;
    }

    private function load($string)
    {
        $rows = $this->getRowsFromString($string);

        foreach ($rows as $row) {
            if (!$row) {
                continue;
            }

            $row = $this->parseRow($row);

            $this->data[$row[0]] = $row[1];
        }
    }

    private function getRowsFromString($string)
    {
        return explode("\n", $string);
    }
    private function parseRow($row)
    {
        return explode("=", $row);
    }

}
