<?php

class ModelOrderSection extends Model
{
    public function addSection($data) {
        foreach ($data['section'] as $language_id => $value) {
            if (isset($section_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "section SET section_id = '" . (int)$section_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "section SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

                $section_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('section');

        return $section_id;
    }

    public function editSection($section_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "section WHERE section_id = '" . (int)$section_id . "'");

        foreach ($data['section'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "section SET section_id = '" . (int)$section_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }

        $this->cache->delete('section');
    }

    public function deleteSection($section_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "section WHERE section_id = '" . (int)$section_id . "'");

        $this->cache->delete('section');
    }

    public function getSection($section_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "section WHERE section_id = '" . (int)$section_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getSections($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "section WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
            $query = $this->db->query("SELECT section_id, name FROM " . DB_PREFIX . "section WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

            $section_data = $query->rows;

            return $section_data;
        }
    }

    public function getSectionDescriptions($section_id) {
        $section_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "section WHERE section_id = '" . (int)$section_id . "'");

        foreach ($query->rows as $result) {
            $section_data[$result['language_id']] = array('name' => $result['name']);
        }

        return $section_data;
    }

    public function getTotalSections() {
        $query = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "section WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}