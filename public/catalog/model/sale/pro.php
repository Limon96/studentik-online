<?php

class ModelSalePro extends Model {

    public function getPros()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pro WHERE status = 1");

        return $query->rows;
    }

}