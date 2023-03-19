<?php

class ModelOrderSubject extends Model
{
    public function addSubject($data) {
        foreach ($data['subject'] as $language_id => $value) {
            if (isset($subject_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "subject SET subject_id = '" . (int)$subject_id . "', language_id = '" . (int)$language_id . "', section_id = '" . (int)$data['section_id'] . "', name = '" . $this->db->escape($value['name']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "subject SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

                $subject_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('subject');

        return $subject_id;
    }

    public function editSubject($subject_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "subject WHERE subject_id = '" . (int)$subject_id . "'");

        foreach ($data['subject'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "subject SET subject_id = '" . (int)$subject_id . "', language_id = '" . (int)$language_id . "', section_id = '" . (int)$data['section_id'] . "', name = '" . $this->db->escape($value['name']) . "'");
        }

        $this->cache->delete('subject');
    }

    public function deleteSubject($subject_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "subject WHERE subject_id = '" . (int)$subject_id . "'");

        $this->cache->delete('subject');
    }

    public function getSubject($subject_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subject WHERE subject_id = '" . (int)$subject_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getSubjects($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "subject WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (isset($data['filter_section_id'])) {
                $sql .= " AND section_id = '" . (int)$data['filter_section_id'] ."'";
            }

            $sql .= " ORDER BY name";

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $query = $this->db->query("SELECT subject_id, name FROM " . DB_PREFIX . "subject WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

            $subject_data = $query->rows;

            return $subject_data;
        }
    }

    public function getSubjectDescriptions($subject_id) {
        $subject_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subject WHERE subject_id = '" . (int)$subject_id . "'");

        foreach ($query->rows as $result) {
            $subject_data[$result['language_id']] = array('name' => $result['name']);
        }

        return $subject_data;
    }

    public function getTotalSubjects() {
        $query = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "subject WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}