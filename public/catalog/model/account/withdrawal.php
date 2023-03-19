<?php

class ModelAccountWithdrawal extends Model {

    public function addWithdrawal($data)
    {
        $this->load->model('account/customer');

        $this->db->query(
            "INSERT INTO " . DB_PREFIX . "withdrawal SET 
                customer_id = '" . (int)$this->customer->getId() . "',
                status = 0,
                amount = '" . (int)$data['amount'] . "',
                `method` = '" . $this->db->escape($data['method']) . "',
                `card_number` = '" . $this->db->escape($data['card_number']) . "',
                balance = '" . ($this->customer->getBalance() - (int)$data['amount']) . "',
                date_added = '" . time() . "',
                date_updated = '" . time() . "'
            "
        );

        $this->model_account_customer->setBalance(
            $this->customer->getId(),
            0 - (int)$data['amount'],
            'Заблокировано перед снятием'
        );
    }

    public function deleteWithdrawal($withdrawal_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id = '" . (int)$withdrawal_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND status = 0");

        if ($result->row && isset($result->row['amount'])) {

            $this->db->query("DELETE FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id = '" . (int)$withdrawal_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND status = 0");

            $this->load->model('account/customer');

            $this->model_account_customer->setBalance(
                $this->customer->getId(),
                (int)$result->row['amount'],
                'Отмена заявки на снятие'
            );
        }
    }

    public function getWithdrawals($start = 0, $limit = 0)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "withdrawal WHERE customer_id = '" . (int)$this->customer->getId() . "' ORDER BY date_added DESC";

        $sql .= " LIMIT " . $start . ", " . $limit;

        $result = $this->db->query($sql);

        return $result->rows;
    }

    public function getTotalWithdrawals()
    {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "withdrawal WHERE customer_id = '" . (int)$this->customer->getId() . "'";

        $result = $this->db->query($sql);

        if (isset($result->row['total'])) {
            return (int)$result->row['total'];
        }

        return 0;
    }

}