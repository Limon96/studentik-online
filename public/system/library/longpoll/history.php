<?php

namespace LongPoll;

class History
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($customer_id, $ts = 0)
    {
        $data = [];

        if ($ts > 0) {
            $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "history WHERE customer_id = '" . (int)$customer_id . "' AND ts > '" . (int)$ts . "' ORDER BY ts DESC LIMIT 0, 100");

            if ($result->rows) {
                foreach ($result->rows as $row) {

                    $data[] = [
                        'history_id' => $row['history_id'],
                        'code' => $row['code'],
                        'ts' => (int)$row['ts'],
                        'object' => json_decode($row['object']),
                    ];
                }

                $data = array_reverse($data);
            }
        }

        return $data;
    }

    public function set($customer_id, $code, $object = [])
    {
        if ($code == 'notification_new') {
            foreach ($object as $key => $value) {
                $object[$key] = $this->db->escape(strip_tags(htmlspecialchars_decode($value)));
            }
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "history SET
            `code` = '" . $this->db->escape($code) . "',
            `customer_id` = '" . (int)$customer_id . "',
            `object` = '" . json_encode($object, JSON_UNESCAPED_UNICODE) . "',
            `ts` = '" . $this->getTS() . "'
        ");
    }

    public function getTotalUnreadMessages($customer_id)
    {
        $result = $this->db->query("SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "message` WHERE recipient_id = '" . (int)$customer_id . "' AND viewed = 0");

        if (isset($result->row['total'])) {
            return (int)$result->row['total'];
        }

        return 0;
    }

    public function getTotalUnreadNotifications($customer_id)
    {
        $result = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "notification WHERE customer_id = " . (int)$customer_id . " AND viewed = 0");

        if (isset($result->row['total'])) {
            return (int)$result->row['total'];
        }

        return 0;
    }

    private function getTS()
    {
        return floor(microtime(true) * 100);
    }

}
