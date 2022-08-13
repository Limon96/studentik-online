<?php

class ModelPaymentPayment extends Model {

    public function addPayment($data)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "payment SET
                    customer_id = '" . (int)$data['customer_id'] . "',
                    payment_method = '" . $this->db->escape($data['payment_method']) . "',
                    amount = '" . (float)$data['amount'] . "',
                    currency = '" . $this->db->escape($data['currency']) . "',
                    payment_status_id = '" . (int)$data['payment_status_id'] . "',
                    ip = '" . $this->db->escape($data['ip']) . "',
                    date_added = NOW(),
                    date_updated = NOW()
        ";

        $this->db->query($sql);

        $payment_id = $this->db->getLastId();

        return $payment_id;
    }

    public function getPayment($payment_id)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "payment WHERE payment_id = '" . (int)$payment_id . "'";

        $result = $this->db->query($sql);

        return $result->row;
    }

    public function getPendingPayments($customer_id)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "payment WHERE customer_id = '" . (int)$customer_id . "' AND payment_status_id = '1'";

        $result = $this->db->query($sql);

        return $result->rows;
    }

    public function setPaymentStatus($payment_id, $payment_status_id)
    {
        $sql = "UPDATE " . DB_PREFIX . "payment SET
                    payment_status_id = '" . (int)$payment_status_id . "',
                    date_updated = NOW()
                WHERE payment_id = '" . (int)$payment_id . "'
        ";

        $this->db->query($sql);
    }

    public function setPlatformPaymentId($payment_id, $platform_payment_id)
    {
        $sql = "UPDATE " . DB_PREFIX . "payment SET
                    platform_payment_id = '" . $this->db->escape($platform_payment_id) . "',
                    date_updated = NOW()
                WHERE payment_id = '" . (int)$payment_id . "'
        ";

        $this->db->query($sql);
    }

}