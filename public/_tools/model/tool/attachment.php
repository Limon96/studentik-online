<?php

class ModelToolAttachment extends Model {

    public function addAttachment($data)
    {
        $this->db->query(
            "INSERT INTO " . DB_PREFIX . "attachment
            SET
                `customer_id` = '" . (int)$this->customer->getId() . "',
                `name` = '" . $this->db->escape($data['name']) . "',
                `type` = '" . $this->db->escape($data['type']) . "',
                `src` = '" . $this->db->escape($data['src']) . "',
                `size` = '" . (float)$data['size'] . "',
                `date_added` = NOW()"
        );

        $attachment_id = $this->db->getLastId();

        return $attachment_id;
    }

    public function getAttachment($attachment_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "attachment WHERE attachment_id = '" . (int)$attachment_id . "'");
        return $result->row;
    }

}
