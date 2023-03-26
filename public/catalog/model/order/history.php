<?php

class ModelOrderHistory extends Model {

    public function addHistory($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $data['order_id'] . "', customer_id = '" . $data['customer_id'] . "', text = '" . $data['text'] . "', date_added = '" . time() . "'");
    }

    public function lastHistoryRecord($order_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . $order_id . "' ORDER BY date_added DESC LIMIT 1");

        return $result->row;
    }

}
