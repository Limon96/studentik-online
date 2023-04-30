<?php

namespace Model;

class ClientToken extends Model
{

    public function get()
    {
        $result = $this->db->query("SELECT token FROM `" . DB_PREFIX . "client_tokens` o LIMIT 1");

        if (isset($result->row['token']) && $result->row['token']) {
            return (string)$result->row['token'];
        }

        return false;
    }

}
