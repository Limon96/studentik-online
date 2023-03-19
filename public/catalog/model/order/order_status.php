<?php

class ModelOrderOrderStatus extends Model
{

    public function getOrderStatuses()
    {
        $query = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($query);

        return $query->rows;
    }

}