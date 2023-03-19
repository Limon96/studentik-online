<?php

class ModelOrderSubject extends Model
{

    public function getSubjects($data = array())
    {
        $query = "SELECT sj.subject_id, sj.section_id, sj.name FROM " . DB_PREFIX . "subject sj WHERE sj.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($data['filter_section_id']) && $data['filter_section_id'] > 0) {
            $query .= " AND sj.section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if (isset($data['filter_name']) && $data['filter_name'] != '') {
            $query .= " AND sj.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $query = $this->db->query($query);

        return $query->rows;
    }

}