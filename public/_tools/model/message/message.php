<?php

class ModelMessageMessage extends Model
{

    public function getMessages($data = [])
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "message` WHERE message_id > 0";

        if (isset($data['search'])) {
            $sql .= " AND text LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        if (isset($data['filter_sender_id']) && $data['filter_sender_id'] > 0) {
            $sql .= " AND sender_id = '" . (int)$data['filter_sender_id'] . "'";
        }

        if (isset($data['filter_recipient_id']) && $data['filter_recipient_id'] > 0) {
            $sql .= " AND recipient_id = '" . (int)$data['filter_recipient_id'] . "'";
        }

        if (isset($data['filter_viewed']) && $data['filter_viewed'] == '0') {
            $sql .= " AND viewed = '0'";
        } elseif (isset($data['filter_viewed']) && $data['filter_viewed'] == '1') {
            $sql .= " AND viewed = '1'";
        }

        $sort_data = array(
            'date_added',
            'date_end'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        if ($query->rows) {
            return $this->format($query->rows);
        }

        return [];
    }

    public function getTotalMessages($data = [])
    {
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "message` WHERE message_id > 0";

        if (isset($data['search'])) {
            $sql .= " AND text LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        if (isset($data['filter_sender_id']) && $data['filter_sender_id'] > 0) {
            $sql .= " AND sender_id = '" . (int)$data['filter_sender_id'] . "'";
        }

        if (isset($data['filter_recipient_id']) && $data['filter_recipient_id'] > 0) {
            $sql .= " AND recipient_id = '" . (int)$data['filter_recipient_id'] . "'";
        }

        if (isset($data['filter_viewed']) && $data['filter_viewed'] == '0') {
            $sql .= " AND viewed = '0'";
        } elseif (isset($data['filter_viewed']) && $data['filter_viewed'] == '1') {
            $sql .= " AND viewed = '1'";
        }

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
            return $query->row['total'];
        }

        return 0;
    }

    public function getAttachments($message_id)
    {
        $result = $this->db->query("SELECT a.* FROM " . DB_PREFIX . "message_attachment ma LEFT JOIN " . DB_PREFIX . "attachment a ON ma.attachment_id = a.attachment_id WHERE ma.message_id = '" . (int)$message_id . "'");
        return $result->rows;
    }

    private function format($data)
    {
        $this->load->model('customer/customer');

        $customer_ids = [];

        foreach ($data as $item) {
            if (!in_array($item['sender_id'], $customer_ids)) {
                $customer_ids[] = (int)$item['sender_id'];
            }
            if (!in_array($item['recipient_id'], $customer_ids)) {
                $customer_ids[] = (int)$item['recipient_id'];
            }
        }

        $customer_ids = implode(',', $customer_ids);

        $customers = $this->model_customer_customer->getCustomersByIds($customer_ids, true);

        foreach ($data as &$item) {
            if (isset($customers[$item['sender_id']])) {
                $item['sender'] = $customers[$item['sender_id']];
            } else {
                $item['sender'] = null;
            }
            if (isset($customers[$item['recipient_id']])) {
                $item['recipient'] = $customers[$item['recipient_id']];
            } else {
                $item['recipient'] = null;
            }
        }

        return $data;
    }

    public function deleteMessage($message_id)
    {
        $result = $this->db->query("SELECT ma.attachment_id, a.src FROM " . DB_PREFIX . "message_attachment ma LEFT JOIN " . DB_PREFIX . "attachment a ON (ma.attachment_id = a.attachment_id) WHERE ma.message_id = '" . (int)$message_id . "'");
        if ($result->rows) {
            foreach ($result->rows as $item) {
                unlink(DIR_UPLOAD . $item['src']);
                $this->db->query("DELETE FROM " . DB_PREFIX . "attachment WHERE attachment_id = '" . (int)$item['attachment_id'] . "'");
            }
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "message_attachment WHERE message_id = '" . (int)$message_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "message` WHERE message_id = '" . (int)$message_id . "'");
    }
}
