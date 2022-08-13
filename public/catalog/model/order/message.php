<?php

class ModelOrderMessage extends Model {

    public function addMessage($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "offer_message SET 
            offer_id = '" . (int)$data['offer_id'] . "', 
            customer_id = '" . (int)$this->customer->getId() . "', 
            text = '" . $this->db->escape($data['text']) . "', 
            date_added = '" . time() . "'
        ");

        $offer_message_id = $this->db->getLastId();

        return $offer_message_id;
    }

    public function editMessage($offer_message_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "offer_message SET
            text = '" . $this->db->escape($data['text']) . "', 
            date_updated = NOW()
            WHERE offer_message_id = '" . (int)$offer_message_id . "'
        ");
    }

    public function getMessages($offer_id, $last_offer_message_id = 0)
    {
        $sql = "SELECT *, IF(customer_id = '" . (int)$this->customer->getId() . "',1,0) AS is_owner FROM ". DB_PREFIX . "offer_message WHERE offer_id = '" . $offer_id . "'";

        if ($last_offer_message_id > 0) {
            $sql .= " AND offer_message_id > '" . (int)$last_offer_message_id . "'";
        }

        $sql .= " ORDER BY date_added DESC";

        $result = $this->db->query($sql);
        $offer_message_data = array();

        if ($result->rows) {

            $this->load->model('account/customer');

            foreach ($result->rows as $row) {

                $customer_info = $this->model_account_customer->getCustomerInfo($row['customer_id']);

                $offer_message_data[] = [
                    'offer_message_id' => $row['offer_message_id'],
                    'offer_id' => $row['offer_id'],
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
        return $offer_message_data;
    }

}