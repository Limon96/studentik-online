<?php

class ModelOrderWorkType extends Model
{

    public function getWorkTypes()
    {
        $query = "SELECT * FROM " . DB_PREFIX . "work_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($query);

        return $query->rows;
    }

}