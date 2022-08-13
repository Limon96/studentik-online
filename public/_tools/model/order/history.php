<?php

class ModelOrderHistory extends Model {

    public function addHistory($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $data['order_id'] . "', customer_id = '" . $data['customer_id'] . "', text = '" . $data['text'] . "', date_added = '" . time() . "'");
    }

}