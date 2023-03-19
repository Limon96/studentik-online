<?php

namespace Model;

class Subscribe extends Model {

    public function generateUnsubscribeToken($email): string
    {
        $token = token(64);

        $this->db->query("INSERT INTO " . DB_PREFIX . "unsubscribe_token (email, token) VALUES ('" . $this->db->escape($email) . "', '" . $this->db->escape($token) . "')");

        return $token;
    }

}