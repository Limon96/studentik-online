<?php

namespace Model;

class Notification extends Model {

    public function set($customer_id, $type, $message)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX ."notification SET customer_id = '" . (int)$customer_id . "', `type`='" . $this->db->escape($type) ."', text = '" . $this->db->escape($message) . "', date_added = '" . time() . "'");
    }

}