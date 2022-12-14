<?php

class ModelAccountPartner extends Model
{
    public function getTotalRegistrations($referral_code)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'");

        return $query->row['total'];
    }

    public function getTotalOrders($referral_code)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'];
    }

    public function getTotalOrdersInWork($referral_code)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE
        order_status_id IN (
        " . join(',', [
             $this->config->get('config_progress_order_status_id'),
             $this->config->get('config_awaiting_order_status_id'),
             $this->config->get('config_verification_order_status_id'),
             $this->config->get('config_revision_order_status_id'),
            ]) . "
        )
        AND customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'];
    }

    public function getTotalCustomerBlockedCash($referral_code)
    {
        $query = $this->db->query("SELECT SUM(balance) AS total FROM `" . DB_PREFIX . "customer_blocked_cash` WHERE customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'] ?? 0;
    }

    public function getTotalOrdersCompleted($referral_code)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE
        order_status_id IN (
        " . join(',', [
                $this->config->get('config_complete_order_status_id'),
            ]) . "
        )
        AND customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'] ?? 0;
    }

    public function getTotalSumCompletedOrders($referral_code)
    {
        $query = $this->db->query("SELECT SUM(price) AS total FROM `" . DB_PREFIX . "order` WHERE
        order_status_id IN (
        " . join(',', [
                $this->config->get('config_complete_order_status_id'),
            ]) . "
        )
        AND customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'] ?? 0;
    }

    public function getTotalAvgCompletedOrders($referral_code)
    {
        $query = $this->db->query("SELECT AVG(price) AS total FROM `" . DB_PREFIX . "order` WHERE
        order_status_id IN (
        " . join(',', [
                $this->config->get('config_complete_order_status_id'),
            ]) . "
        )
        AND customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'] ?? 0;
    }

    public function getTotalCustomerAmount($referral_code)
    {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE
        amount > 0
        AND customer_id IN
        (
            SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(referral_code) = '" . $this->db->escape(mb_strtolower($referral_code)) . "'
        )");

        return $query->row['total'] ?? 0;
    }

}
