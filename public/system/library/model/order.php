<?php

namespace Model;

class Order extends Model {

    public function get($order_id)
    {
        $result = $this->db->query("SELECT o.*,
                (SELECT s.name FROM `" .DB_PREFIX  ."section` s WHERE s.language_id = '1' AND s.section_id = o.section_id) AS `section`,
                (SELECT sj.name FROM `" .DB_PREFIX  ."subject` sj WHERE sj.language_id = '1' AND sj.subject_id = o.subject_id) AS `subject`,
                (SELECT wt.name FROM `" .DB_PREFIX  ."work_type` wt WHERE wt.language_id = '1' AND wt.work_type_id = o.work_type_id) AS `work_type`
                 FROM `" . DB_PREFIX ."order` o WHERE o.order_id = '" . $order_id . "'");

        if (isset($result->row) && $result->row) {
            return (object)$result->row;
        }

        return false;
    }

    public function getOrderStatus($order_id)
    {
        $result = $this->db->query("SELECT order_id, order_status_id FROM `" . DB_PREFIX ."order` WHERE order_id = '" . $order_id . "'");

        if (isset($result->row['order_status_id']) && $result->row['order_status_id']) {
            return $result->row['order_status_id'];
        }

        return false;
    }

    public function geTotalOrderClaims($order_id)
    {
        $result = $this->db->query("SELECT COUNT(1) AS total FROM `" . DB_PREFIX ."claim` WHERE `type` = 'order' AND object_id = '" . $order_id . "'");

        if (isset($result->row['total']) && $result->row['total']) {
            return (int)$result->row['total'];
        }

        return false;
    }

    public function setOrderStatus($order_id, $order_status_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX ."order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
    }

    public function disablePremium($order_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX ."order` SET premium = '0' WHERE order_id = '" . $order_id . "'");
    }

    public function setOrderHistory($order_id, $customer_id, $text)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX ."order_history SET order_id = '" . (int)$order_id . "', customer_id = '" . (int)$customer_id . "', text = '" . $this->db->escape($text) . "', date_added = '" . time() . "'");
    }

}
