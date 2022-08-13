<?php

class ModelOrderSection extends Model
{

    public function getSections()
    {
        $query = "SELECT section_id, `name` FROM " . DB_PREFIX . "section WHERE language_id = '" . (int)(int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($query);

        return $query->rows;
    }

}