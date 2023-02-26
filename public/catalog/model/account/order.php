<?php

class ModelAccountOrder extends Model
{

    public function getOrder($order_id) {
        $query = $this->db->query(
            "SELECT *,
                IF(o.customer_id = '" . $this->customer->getId() . "', 1, 0) AS is_owner,
                IF((SELECT COUNT(1) FROM " .DB_PREFIX  ."offer oo WHERE oo.order_id = o.order_id AND customer_id = '" . (int)$this->customer->getId() . "'), 1, 0) AS exist_offer,
                (SELECT COUNT(1) FROM " .DB_PREFIX  ."offer co WHERE co.order_id = o.order_id) AS count_offer,
                (SELECT s.name FROM `" .DB_PREFIX  ."section` s WHERE s.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.section_id = o.section_id) AS `section`,
                (SELECT sj.name FROM `" .DB_PREFIX  ."subject` sj WHERE sj.language_id = '" . (int)$this->config->get('config_language_id') . "' AND sj.subject_id = o.subject_id) AS `subject`,
                (SELECT wt.name FROM `" .DB_PREFIX  ."work_type` wt WHERE wt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND wt.work_type_id = o.work_type_id) AS `work_type`,
                (SELECT os.name FROM `" .DB_PREFIX  ."order_status` os WHERE os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND os.order_status_id = o.order_status_id) AS `order_status` ,
                (SELECT pb.name FROM `" .DB_PREFIX  ."payment_blocking` pb WHERE pb.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pb.payment_blocking_id = o.payment_blocking_id) AS `payment_blocking`,
                (SELECT pc.name FROM `" .DB_PREFIX  ."plagiarism_check` pc WHERE pc.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pc.plagiarism_check_id = o.plagiarism_check_id) AS `plagiarism_check`,
                (SELECT n.text FROM `" .DB_PREFIX  ."order_note` n WHERE n.customer_id = '" . ($this->customer->isLogged() ? (int)$this->customer->getId() : 0). "' AND n.order_id = o.order_id) AS `note`
            FROM `" . DB_PREFIX . "order` o
            WHERE o.order_id = '" . (int)$order_id . "'"
        );
        return $query->row;
    }

    public function getOrders($data = array()) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "order` o WHERE customer_id > 0";

        if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
            $sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        }

        if (isset($data['filter_section_id']) && $data['filter_section_id']) {
            $sql .= " AND section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if (isset($data['filter_subject_id']) && $data['filter_subject_id']) {
            $sql .= " AND subject_id = '" . (int)$data['filter_subject_id'] . "'";
        }

        if (isset($data['filter_work_type_id']) && $data['filter_work_type_id']) {
            $sql .= " AND work_type_id = '" . (int)$data['filter_work_type_id'] . "'";
        }

        if (isset($data['filter_customer_id']) && $data['filter_customer_id']) {
            $sql .= " AND customer_id = '" . (int)$data['filter_customer_id'] . "'";
        }

        if (isset($data['filter_offer_id']) && $data['filter_offer_id']) {
            $sql .= " AND o.order_id IN (SELECT order_id FROM " . DB_PREFIX . "offer WHERE customer_id = '" . (int)$data['filter_offer_id'] . "' AND assigned = 1)";
        }

        if (isset($data['filter_customer']) && $data['filter_customer']) {
            $sql .= " AND customer_id IN (SELECT c.customer_id FROM " . DB_PREFIX . "customer c WHERE c.login LIKE '%" . $this->db->escape($data['filter_customer']) . "%' AND c.status = 1)";
        }

        if (isset($data['filter_assigned'])) {
            $sql .= " AND o.order_id IN (SELECT oo.order_id FROM " . DB_PREFIX . "offer oo WHERE oo.customer_id = '" . (int)$this->customer->getId() . "' AND oo.assigned = '" . ($data['filter_assigned'] ? 1: 0) . "')";
        }

        if (isset($data['filter_my_specialization']) && $data['filter_my_specialization']) {
            $sql .= " AND subject_id IN (SELECT cs.subject_id FROM " . DB_PREFIX . "customer_subject cs WHERE cs.customer_id = '" . ($this->customer->isLogged() ? (int)$this->customer->getId() : 0) . "')";
        }

        if (isset($data['filter_premium']) && !empty($data['filter_premium'])) {
            $sql .= " AND premium = '" . (int)$data['filter_premium'] . "'";
        }

        if (isset($data['filter_hot']) && !empty($data['filter_hot'])) {
            $sql .= " AND hot = '" . (int)$data['filter_hot'] . "'";
        }

        if (isset($data['search']) && $data['search']) {
            $sql .= " AND title LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        $sql .= " GROUP BY order_id";

        $sort_data = array(
            'name',
            'premium',
            'hot',
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

        $order_data = array();

        $results = $this->db->query($sql);

        foreach ($results->rows as $result) {
            $order_data[$result['order_id']] = $this->getOrder($result['order_id']);
        }

        return $order_data;
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id > 0";

        if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
            $sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        }

        if (isset($data['filter_section_id']) && $data['filter_section_id']) {
            $sql .= " AND section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if (isset($data['filter_subject_id']) && $data['filter_subject_id']) {
            $sql .= " AND subject_id = '" . (int)$data['filter_subject_id'] . "'";
        }

        if (isset($data['filter_work_type_id']) && $data['filter_work_type_id']) {
            $sql .= " AND work_type_id = '" . (int)$data['filter_work_type_id'] . "'";
        }

        if (isset($data['filter_customer_id']) && $data['filter_customer_id']) {
            $sql .= " AND customer_id = '" . (int)$data['filter_customer_id'] . "'";
        }

        if (isset($data['filter_offer_id']) && $data['filter_offer_id']) {
            $sql .= " AND o.order_id IN (SELECT order_id FROM " . DB_PREFIX . "offer WHERE customer_id = '" . (int)$data['filter_offer_id'] . "' AND assigned = 1)";
        }

        if (isset($data['filter_customer']) && $data['filter_customer']) {
            $sql .= " AND customer_id IN (SELECT c.customer_id FROM " . DB_PREFIX . "customer c WHERE c.login LIKE '%" . $this->db->escape($data['filter_customer']) . "%' AND c.status = 1)";
        }

        if (isset($data['filter_assigned'])) {
            $sql .= " AND o.order_id IN (SELECT oo.order_id FROM " . DB_PREFIX . "offer oo WHERE oo.customer_id = '" . (int)$this->customer->getId() . "' AND oo.assigned = '" . ($data['filter_assigned'] ? 1: 0) . "')";
        }

        if (isset($data['filter_my_specialization']) && $data['filter_my_specialization']) {
            $sql .= " AND subject_id IN (SELECT cs.subject_id FROM " . DB_PREFIX . "customer_subject cs WHERE cs.customer_id = '" . ($this->customer->isLogged() ? (int)$this->customer->getId() : 0) . "')";
        }

        if (isset($data['filter_premium']) && !empty($data['filter_premium'])) {
            $sql .= " AND premium = '" . (int)$data['filter_premium'] . "'";
        }

        if (isset($data['filter_hot']) && !empty($data['filter_hot'])) {
            $sql .= " AND hot = '" . (int)$data['filter_hot'] . "'";
        }

        if (isset($data['search']) && $data['search']) {
            $sql .= " AND title LIKE '%" . $this->db->escape($data['search']) . "%'";
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

    public function getTotalOffers($data = array())
    {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "offer WHERE customer_id = '" . (int)$this->customer->getId() . "'";

        if (isset($data['filter_assigned']) && !empty($data['filter_assigned'])) {
            $sql .= " AND assigned = '" . ($data['filter_assigned'] ? 1: 0) . "'";
        }

        $result = $this->db->query($sql);

        if ($result->row['total']) {
            return (int)$result->row['total'];
        }

        return 0;
    }

}
