<?php

class ModelAccountReview extends Model {

    public function addReview($data)
    {
        $query = "INSERT INTO " . DB_PREFIX . "review SET
                        customer_id = '" . (int)$this->customer->getId() . "',
                        customer_group_id = '" . (int)$this->customer->getGroupId() . "',
                        order_id = '" . (int)$data['order_id'] . "',
                        text = '" . $this->db->escape($data['text']) . "',
                        positive = '" . (int)$data['positive'] . "',
                        `time` = '" . (int)$data['time'] . "',
                        ip = '" . $this->request->server['REMOTE_ADDR'] . "',
                        date_added = '" . time() . "',
                        date_updated = 0";

        $this->db->query($query);

        $review_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_review SET customer_id='" . (int)$data['customer_id'] . "', review_id='" . (int)$review_id ."'");

        return $review_id;
    }

    public function editReview($review_id, $data)
    {
        $query = "UPDATE `" . DB_PREFIX . "order` SET
                    text = '" . $this->db->escape($data['text']) . "',
                    date_modified = '" . time() . "'
                WHERE review_id = '" . (int)$review_id . "'";

        $this->db->query($query);
    }

    public function getReview($review_id) {
        $query = $this->db->query(
            "SELECT *,
                (SELECT o.title FROM `" . DB_PREFIX . "order` o WHERE o.order_id = r.order_id) AS order_title
            FROM " . DB_PREFIX . "review r
            WHERE r.review_id = '" . (int)$review_id . "'"
        );
        return $query->row;
    }

    public function getReviews($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE r.review_id > 0";

        if ($data['filter_customer_id']) {
            $sql .= " AND cr.customer_id = '" . (int)$data['filter_customer_id'] . "'";
        }

        $white_list = [
            'negative',
            'positive'
        ];

        if (isset($data['filter']) && in_array($data['filter'], $white_list)) {
            if ($data['filter'] == 'positive') {
                $sql .= " AND r.positive = '1'";
            } elseif ($data['filter'] == 'negative') {
                $sql .= " AND r.positive = '0'";
            }
        }

        $sort_data = array(
            'name',
            'date_end',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'name') {
                $sql .= " ORDER BY name";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
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

        $review_data = array();

        $results = $this->db->query($sql);

        foreach ($results->rows as $result) {
            $review_data[$result['review_id']] = $this->getReview($result['review_id']);
        }

        return $review_data;
    }

    public function getTotalReviews($data = array()) {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE r.review_id > 0";

        if ($data['filter_customer_id']) {
            $sql .= " AND cr.customer_id = '" . (int)$data['filter_customer_id'] . "'";
        }

        $white_list = [
            'negative',
            'positive'
        ];

        if (isset($data['filter']) && in_array($data['filter'], $white_list)) {
            if ($data['filter'] == 'positive') {
                $sql .= " AND r.positive = '1'";
            } elseif ($data['filter'] == 'negative') {
                $sql .= " AND r.positive = '0'";
            }
        }

        $sort_data = array(
            'name',
            'date_end',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'name') {
                $sql .= " ORDER BY name";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, date_added DESC";
        } else {
            $sql .= " ASC, date_added ASC";
        }

        $result = $this->db->query($sql);

        if ($result->row['total']) {
            return (int)$result->row['total'];
        }
        return 0;
    }

    public function isExistsReview($order_id, $customer_id) {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "review WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$customer_id . "'";

        $result = $this->db->query($sql);

        if ($result->row['total']) {
            return (int)$result->row['total'];
        }

        return 0;
    }

}
