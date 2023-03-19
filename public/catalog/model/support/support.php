<?php

class ModelSupportSupport extends Model {

    public function getFAQ() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.status = '1' ORDER BY f.sort_order");
        return $query->rows;
    }

    public function getFAQLastUpdate() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq WHERE status = '1' ORDER BY date_modified DESC LIMIT 1");
        return $query->row['date_modified'];
    }

}