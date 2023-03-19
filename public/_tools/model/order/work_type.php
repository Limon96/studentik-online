<?php
class ModelOrderWorkType extends Model {
    public function addWorkType($data) {
        foreach ($data['work_type'] as $language_id => $value) {
            if (isset($work_type_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "work_type SET work_type_id = '" . (int)$work_type_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "work_type SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

                $work_type_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('work_type');

        return $work_type_id;
    }

    public function editWorkType($work_type_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "work_type WHERE work_type_id = '" . (int)$work_type_id . "'");

        foreach ($data['work_type'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "work_type SET work_type_id = '" . (int)$work_type_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }

        $this->cache->delete('work_type');
    }

    public function deleteWorkType($work_type_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "work_type WHERE work_type_id = '" . (int)$work_type_id . "'");

        $this->cache->delete('work_type');
    }

    public function getWorkType($work_type_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "work_type WHERE work_type_id = '" . (int)$work_type_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getWorkTypes($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "work_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
            $query = $this->db->query("SELECT work_type_id, name FROM " . DB_PREFIX . "work_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

            $work_type_data = $query->rows;

            return $work_type_data;
        }
    }

    public function getWorkTypeDescriptions($work_type_id) {
        $work_type_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "work_type WHERE work_type_id = '" . (int)$work_type_id . "'");

        foreach ($query->rows as $result) {
            $work_type_data[$result['language_id']] = array('name' => $result['name']);
        }

        return $work_type_data;
    }

    public function getTotalWorkTypes() {
        $query = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "work_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}