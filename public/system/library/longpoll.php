<?php

class LongPoll
{

    private $db;
    private $history;

    public function __construct($db)
    {
        $this->db = $db;
        $this->history = new LongPoll\History($db);
    }

    private function getCustomer($key)
    {
        $result = $this->db->query("SELECT c.customer_id, c.login, c.customer_group_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_longpoll cl ON c.customer_id = cl.customer_id WHERE cl.key = '" . $this->db->escape($key) . "' AND c.status = 1");

        if (isset($result->row['customer_id'])) {
            return $result->row;
        }

        return false;
    }

    public function register($customer_id, $code, $object = [])
    {
        $this->history->set($customer_id, $code, $object);
    }

    public function connect($key, $ts = 0)
    {
        $result = $this->db->query("SELECT c.customer_id, c.login, c.customer_group_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_longpoll cl ON c.customer_id = cl.customer_id WHERE cl.key = '" . $this->db->escape($key) . "' AND cl.expired > '" . time() . "' AND c.status = 1");

        if (isset($result->row['customer_id'])) {
            $customer_id = (int)$result->row['customer_id'];
        } else {
            $customer_id = 0;
        }

        $data = [];

        if ($customer_id) {
            $max_execution_time = 60;
            while (true) {
                $max_execution_time--;
                if ($max_execution_time < 1) break;

                $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "history WHERE customer_id = '" . $customer_id . "' AND ts > '" . (int)$ts . "' ORDER BY ts DESC LIMIT 0, 100");

                if ($result->rows) {
                    $history = [];

                    foreach ($result->rows as $row) {

                        $history[] = [
                            'code' => $row['code'],
                            'ts' => (int)$row['ts'],
                            'object' => json_decode($row['object']),
                        ];

                        if ((int)$row['ts'] > $ts) {
                            $ts = (int)$row['ts'];
                        }
                    }


                    $data['history'] = $history;
                    $data['ts'] = $ts;

                    $data['counter'] = [
                        'messages' => $this->history->getTotalUnreadMessages(
                            $customer_id
                        ),
                        'notifications' => $this->history->getTotalUnreadNotifications(
                            $customer_id
                        ),
                    ];

                    return array_reverse($data);
                }

                sleep(1);
            }
        } else {
            $data = [
                'error' => '403',
                'code' => 'key_expired',
                'message' => 'Access Denied'
            ];
        }

        return $data;
    }

}