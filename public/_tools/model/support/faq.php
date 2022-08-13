<?php
class ModelSupportFAQ extends Model {
    public function addFAQ($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "faq SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

        $faq_id = $this->db->getLastId();

        foreach ($data['faq_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', question = '" . $this->db->escape($value['question']) . "', answer = '" . $this->db->escape($value['answer']) . "'");
        }

        return $faq_id;
    }

    public function editFAQ($faq_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "faq SET  sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE faq_id = '" . (int)$faq_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");

        foreach ($data['faq_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', question = '" . $this->db->escape($value['question']) . "', answer = '" . $this->db->escape($value['answer']) . "'");
        }
    }

    public function deleteFAQ($faq_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "faq WHERE faq_id = '" . (int)$faq_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");
    }

    public function getFAQ($faq_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq WHERE faq_id = '" . (int)$faq_id . "'");

        return $query->row;
    }

    public function getFAQs($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'fd.question',
            'f.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY fd.question";
        }

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
    }

    public function getFAQDescriptions($faq_id) {
        $faq_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");

        foreach ($query->rows as $result) {
            $faq_data[$result['language_id']] = array(
                'question' => $result['question'],
                'answer' => $result['answer']
            );
        }

        return $faq_data;
    }

    public function getTotalFAQs() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq");

        return $query->row['total'];
    }
}