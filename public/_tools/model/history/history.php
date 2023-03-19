<?php

class ModelHistoryHistory extends Model
{

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
        $this->longpoll->register($customer_id, $code, $object);
    }

}