<?php

namespace Model;

class Offer extends Model {


    public function cancelOffersFromOrder($order_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX ."offer SET assigned = '0' WHERE order_id = '" . $order_id . "'");
    }

}