<?php

namespace Task\Model;

use Model\Model;
use Task\TaskItem;

class Task extends Model {

    public function getTasks($channel, $all_status = 0)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "task WHERE failure = '0' AND channel = '" . $this->db->escape($channel) . "' AND time_exec < '" . time() . "'";

        if (!$all_status) {
            $sql .= " AND status = 0";
        }

        $sql .= " ORDER BY time_exec ASC";

        if ($channel == 'emails') {
            $sql .= " LIMIT 0, 40";
        } else {
            $sql .= " LIMIT 0, 100";
        }

        $result = $this->db->query($sql);

        $data = [];
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $row['object'] = json_decode($row['object']);

                $object = new TaskItem();
                $object->task_id = $row['task_id'];
                $object->fill($row);

                $data[] = $object;
            }
        }

        return $data;
    }

    public function set(TaskItem $task)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "task SET
            `channel` = '" . $this->db->escape($task->channel) . "',
            `type` = '" . $this->db->escape($task->type) . "',
            `object` = '" . $this->db->escape(json_encode($task->object ?? [], JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK)) . "',
            `time_exec` = '" . ($task->time_exec ?? time())  . "',
            `time_added` = '" . time() . "'
        ";

        $this->db->query($sql);
    }

    public function success($task_id)
    {
        $sql = "UPDATE " . DB_PREFIX . "task SET time_complete = '" . time() . "', status = '1' WHERE task_id = '" . $task_id . "'";

        $this->db->query($sql);
    }

    public function failure($task_id)
    {
        $sql = "UPDATE " . DB_PREFIX . "task SET time_complete = '" . time() . "', failure = '1' WHERE task_id = '" . $task_id . "'";

        $this->db->query($sql);
    }

}
