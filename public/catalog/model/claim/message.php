<?php

class ModelClaimMessage extends Model {

    public function addMessage($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "claim_message SET 
            claim_id = '" . (int)$data['claim_id'] . "', 
            customer_id = '" . (int)$this->customer->getId() . "', 
            text = '" . $this->db->escape($data['text']) . "', 
            date_added = '" . time() . "'
        ");

        $claim_message_id = $this->db->getLastId();

        $this->db->query("UPDATE " . DB_PREFIX . "claim SET 
                viewed = 0
            WHERE 
                claim_id = '" . (int)$data['claim_id'] . "'
        ");

        return $claim_message_id;
    }

    public function editMessage($claim_message_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "claim_message SET
            text = '" . $this->db->escape($data['text']) . "', 
            date_updated = NOW()
            WHERE claim_message_id = '" . (int)$claim_message_id . "'
        ");
    }

    public function getMessages($claim_id, $last_claim_message_id = 0)
    {
        $sql = "SELECT *, IF(customer_id = '" . (int)$this->customer->getId() . "',1,0) AS is_owner FROM ". DB_PREFIX . "claim_message WHERE claim_id = '" . $claim_id . "'";

        if ($last_claim_message_id > 0) {
            $sql .= " AND claim_message_id > '" . (int)$last_claim_message_id . "'";
        }

        $sql .= " ORDER BY date_added DESC";

        $result = $this->db->query($sql);
        $claim_message_data = array();

        if ($result->rows) {

            $this->load->model('account/customer');

            foreach ($result->rows as $row) {

                $customer_info = $this->model_account_customer->getCustomerInfo($row['customer_id']);

                $claim_message_data[] = [
                    'claim_message_id' => $row['claim_message_id'],
                    'claim_id' => $row['claim_id'],
                    'is_owner' => $row['is_owner'],
                    'customer_id' => $customer_info['customer_id'],
                    'login' => $customer_info['login'],
                    'firstname' => $customer_info['firstname'],
                    'image' => $customer_info['image'],
                    'pro' => $customer_info['pro'],
                    'online' => $customer_info['online'],
                    'rating' => $customer_info['rating'],
                    'total_reviews_positive' => $customer_info['total_reviews_positive'],
                    'total_reviews_negative' => $customer_info['total_reviews_negative'],
                    'text' => $row['text'],
                    'date_added' => $row['date_added'],
                    'date_updated' => $row['date_updated'],
                ];
            }
        }
        return $claim_message_data;
    }

}