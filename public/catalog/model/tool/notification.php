<?php

class ModelToolNotification extends Model
{

    public function set($data = array())
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "notification SET 
            customer_id = '" . (int)$data['customer_id'] . "',
            `type` = '" . $this->db->escape($data['type']) . "',
            text = '" . $this->db->escape($data['text']) . "',
            comment = '" . (isset($data['comment']) ? $this->db->escape($data['comment']) : '') . "',
            viewed = 0,
            date_added = '" . time() . "'
        ");

        $notification_id = $this->db->getLastId();

        $notification_info = $this->getNotification($notification_id);

        $this->load->model('history/history');
        $this->model_history_history->set(
            $data['customer_id'],
            'notification_new',
            [
                'notification_id' => $notification_info['notification_id'],
                'type' => $notification_info['type'],
                'text' => $notification_info['text'],
                'comment' => $notification_info['comment'],
                'viewed' => $notification_info['viewed'],
                'date_added' => format_date($notification_info['date_added'], 'full_datetime')
            ]
        );
    }

    public function getNotification($notification_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "notification WHERE notification_id = '" . (int)$notification_id . "'");

        return $result->row;
    }

    public function getAll($customer_id, $data)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "notification WHERE customer_id = " . (int)$customer_id . " ORDER BY date_added DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }


        $results = $this->db->query($sql);

        if ($results->rows > 0) {
            return $results->rows;
        }

        return false;
    }

    public function getTotalAll($customer_id)
    {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "notification WHERE customer_id = " . (int)$customer_id . " ORDER BY date_added DESC";

        $result = $this->db->query($sql);

        if (isset($result->row['total'])) {
            return $result->row['total'];
        }

        return 0;
    }

    public function getTotalUnreadNotifications($customer_id)
    {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "notification WHERE customer_id = " . (int)$customer_id . " AND viewed = 0";

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
            return (int)$query->row['total'];
        }

        return 0;
    }

    public function viewedNotification($customer_id, $notification_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "notification SET viewed = 1 WHERE customer_id = '" . (int)$customer_id . "' AND notification_id = '" . (int)$notification_id . "'");

        $this->load->model('history/history');
        $this->model_history_history->set(
            $customer_id,
            'notification_read',
            [
                'notification_id' => $notification_id
            ]
        );
    }

    public function viewedNotifications($customer_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "notification SET viewed = 1 WHERE customer_id = '" . (int)$customer_id . "'");

        $this->longpoll->register(
            $customer_id,
            'notification_read'
        );
    }

}