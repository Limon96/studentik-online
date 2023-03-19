<?php
class ModelSupportMessage extends Model {

    public function viewedMessage($message_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "message SET  viewed = '1', date_modified = NOW() WHERE message_id = '" . (int)$message_id . "'");

    }

    public function deleteMessage($message_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "message WHERE message_id = '" . (int)$message_id . "'");
    }

    public function getMessage($message_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "message WHERE message_id = '" . (int)$message_id . "'");

        return $query->row;
    }

    public function getMessages($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "message WHERE message_id > 0";

        if (isset($data['filter_name']) && $data['filter_name'] !== '') {
            $sql .= " AND `name` LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_email']) && $data['filter_email'] !== '') {
            $sql .= " AND `email` LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_text']) && $data['filter_text'] !== '') {
            $sql .= " AND `text` LIKE '%" . $this->db->escape($data['filter_text']) . "%'";
        }

        if (isset($data['filter_utm_source']) && $data['filter_utm_source'] !== '') {
            $sql .= " AND `utm_source` LIKE '%" . $this->db->escape($data['filter_utm_source']) . "%'";
        }

        if (isset($data['filter_utm_medium']) && $data['filter_utm_medium'] !== '') {
            $sql .= " AND `utm_medium` LIKE '%" . $this->db->escape($data['filter_utm_medium']) . "%'";
        }

        if (isset($data['filter_utm_campaign']) && $data['filter_utm_campaign'] !== '') {
            $sql .= " AND `utm_campaign' LIKE '%" . $this->db->escape($data['filter_utm_campaign']) . "%'";
        }

        if (isset($data['filter_utm_content']) && $data['filter_utm_content'] !== '') {
            $sql .= " AND `utm_content' LIKE '%" . $this->db->escape($data['filter_utm_content']) . "%'";
        }

        if (isset($data['filter_utm_term']) && $data['filter_utm_term'] !== '') {
            $sql .= " AND `utm_term' LIKE '%" . $this->db->escape($data['filter_utm_term']) . "%'";
        }

        if (isset($data['filter_viewed']) && $data['filter_viewed'] !== '') {
            $sql .= " AND viewed = '" . (int)$data['filter_viewed'] . "'";
        }

        $sort_data = array(
            'viewed',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY viewed";
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


    public function getTotalMessages() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "message");

        return $query->row['total'];
    }
}