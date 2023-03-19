<?php
class ModelOrderOrder extends Model {

    public function addOrder($data)
    {
        $query = "INSERT INTO `" . DB_PREFIX . "order` SET
                        store_id = '" . (int)$this->config->get('config_store_id') . "',
                        customer_id = '" . (int)$this->customer->getId() . "',
                        customer_group_id = '" . (int)$this->customer->getGroupId() . "',
                        work_type_id = '" . (int)$data['work_type_id'] . "',
                        subject_id = '" . (int)$data['subject_id'] . "',
                        premium = '" . (isset($data['premium']) ? (int)$data['premium'] : 0) . "',
                        hot = '" . (isset($data['hot']) ? (int)$data['hot'] : 0) . "',
                        section_id = (SELECT s.section_id FROM " . DB_PREFIX . "subject s WHERE s.subject_id = '" . (int)$data['subject'] . "' LIMIT 1),
                        price = '" . (float)$data['price'] . "',
                        title = '" . $this->db->escape($data['title']) . "',
                        description = '" . $this->db->escape($data['description']) . "',
                        date_end = '" . $this->db->escape($data['date_end']) . "',
                        ip = '" . $this->request->server['REMOTE_ADDR'] . "',
                        date_added = NOW(),
                        viewed = 0";

        $this->db->query($query);

        $order_id = $this->db->getLastId();

        if (isset($data['attachment'])) {
            foreach ($data['attachment'] as $attachment_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_attachment  SET order_id = '" . (int)$order_id . "', attachment_id = '" . (int)$attachment_id . "'");
            }
        }

        return $order_id;
    }

    public function editOrder($order_id, $data)
    {
        $query = "UPDATE `" . DB_PREFIX . "order` SET
                    work_type_id = '" . (int)$data['work_type_id'] . "',
                    subject_id = '" . (int)$data['subject_id'] . "',
                    section_id = (SELECT s.section_id FROM " . DB_PREFIX . "subject s WHERE s.subject_id = '" . (int)$data['subject_id'] . "' LIMIT 1),
                    price = '" . (float)$data['price'] . "',
                    title = '" . $this->db->escape($data['title']) . "',
                    order_status_id = '" . $this->db->escape($data['order_status_id']) . "',
                    status = '" . $this->db->escape($data['status']) . "',
                    title = '" . $this->db->escape($data['title']) . "',
                    description = '" . $this->db->escape($data['description']) . "',
                    date_end = '" . $this->db->escape($data['date_end']) . "',
                    date_modified = NOW()
                WHERE order_id = '" . (int)$order_id . "'";

        $this->db->query($query);

        if (isset($data['attachment'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_attachment  SET order_id = '" . (int)$order_id . "'");

            foreach ($data['attachment'] as $attachment_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_attachment  SET order_id = '" . (int)$order_id . "', attachment_id = '" . (int)$attachment_id . "'");
            }
        }
    }

    public function getOrder($order_id) {
        $query = $this->db->query(
            "SELECT *,
                IF(o.customer_id = '" . $this->customer->getId() . "', 1, 0) AS is_owner,
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

    public function getAssignedOffer($order_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "offer WHERE order_id = '" . (int)$order_id . "' AND assigned = 1");

        if ($query->row) {
            return $query->row;
        }
        return null;
    }

    public function getOrders($data = array()) {
        $sql = "SELECT o.*,
       (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status
       FROM `" . DB_PREFIX . "order` o
       LEFT JOIN " . DB_PREFIX . "customer cow ON (o.customer_id = cow.customer_id)
       LEFT JOIN " . DB_PREFIX . "offer oo ON (o.order_id = oo.order_id AND oo.assigned = 1)
       LEFT JOIN " . DB_PREFIX . "customer cof ON (oo.customer_id = cof.customer_id)

       WHERE o.customer_id > 0";

        if ($data['filter_order_id']) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if ($data['filter_section_id']) {
            $sql .= " AND o.section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if ($data['filter_subject_id']) {
            $sql .= " AND o.subject_id = '" . (int)$data['filter_subject_id'] . "'";
        }

        if ($data['filter_order_status_id']) {
            $sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        }

        if ($data['filter_date_added']) {
            $date_added = strtotime($data['filter_date_added']);
            $date_added_start = strtotime(date('Y-m-d', $date_added));
            $date_added_end = strtotime(date('Y-m-d', $date_added_start + 86400));

            $sql .= " AND (o.date_added > '" . $date_added_start . "' AND o.date_added < '" . $date_added_end . "')";
        }

        if ($data['filter_date_end']) {
            $sql .= " AND o.date_end = '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_premium'])) {
            $sql .= " AND o.premium = '" . (int)$data['filter_premium'] . "'";
        }

        if (!empty($data['filter_hot'])) {
            $sql .= " AND o.hot = '" . (int)$data['filter_hot'] . "'";
        }

        if (isset($data['filter_title']) && $data['filter_title'] != '') {
            $sql .= " AND o.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (isset($data['filter_owner']) && $data['filter_owner'] != '') {
            $sql .= " AND cow.login LIKE '" . $this->db->escape($data['filter_owner']) . "'";
        }
        if (isset($data['filter_offer']) && $data['filter_offer'] != '') {
            $sql .= " AND cof.login LIKE '" . $this->db->escape($data['filter_offer']) . "'";
        }

        $sql .= " GROUP BY o.order_id";

        $sort_data = array(
            'o.order_id',
            'o.title',
            'o.status',
            'order_status',
            'o.date_added',
            'o.date_end',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'title') {
                $sql .= " ORDER BY o.title";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY o.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, o.date_added DESC";
        } else {
            $sql .= " ASC, o.date_added ASC";
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
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "order` o
            LEFT JOIN " . DB_PREFIX . "customer cow ON (o.customer_id = cow.customer_id)
            LEFT JOIN " . DB_PREFIX . "offer oo ON (o.order_id = oo.order_id AND oo.assigned = 1)
            LEFT JOIN " . DB_PREFIX . "customer cof ON (oo.customer_id = cof.customer_id)
        WHERE o.customer_id > 0";

        if ($data['filter_order_id']) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if ($data['filter_section_id']) {
            $sql .= " AND o.section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if ($data['filter_subject_id']) {
            $sql .= " AND o.subject_id = '" . (int)$data['filter_subject_id'] . "'";
        }

        if ($data['filter_order_status_id']) {
            $sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        }

        if ($data['filter_date_added']) {
            $date_added = strtotime($data['filter_date_added']);
            $date_added_start = strtotime(date('Y-m-d', $date_added));
            $date_added_end = strtotime(date('Y-m-d', $date_added_start + 86400));

            $sql .= " AND (o.date_added > '" . $date_added_start . "' AND o.date_added < '" . $date_added_end . "')";
        }

        if ($data['filter_date_end']) {
            $sql .= " AND o.date_end = '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_premium'])) {
            $sql .= " AND o.premium = '" . (int)$data['filter_premium'] . "'";
        }

        if (!empty($data['filter_hot'])) {
            $sql .= " AND o.hot = '" . (int)$data['filter_hot'] . "'";
        }

        if (isset($data['filter_title']) && $data['filter_title'] != '') {
            $sql .= " AND o.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (isset($data['filter_owner']) && $data['filter_owner'] != '') {
            $sql .= " AND cow.login LIKE '" . $this->db->escape($data['filter_owner']) . "'";
        }
        if (isset($data['filter_offer']) && $data['filter_offer'] != '') {
            $sql .= " AND cof.login LIKE '" . $this->db->escape($data['filter_offer']) . "'";
        }

        $result = $this->db->query($sql);

        if ($result->row['total']) {
            return (int)$result->row['total'];
        }
        return 0;
    }

    public function getTotalSumOffers($data = array()) {
        $sql = "SELECT SUM(of.bet) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "offer `of` ON (o.order_id = of.order_id) WHERE of.assigned = 1";

        if ($data['filter_order_id']) {
            $sql .= " AND of.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if ($data['filter_section_id']) {
            $sql .= " AND o.section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if ($data['filter_subject_id']) {
            $sql .= " AND o.subject_id = '" . (int)$data['filter_subject_id'] . "'";
        }

        if ($data['filter_order_status_id']) {
            $sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        }

        if ($data['filter_date_added']) {
            $sql .= " AND o.date_added = '" . (int)$data['filter_date_added'] . "'";
        }

        if ($data['filter_date_end']) {
            $sql .= " AND o.date_end = '" . (int)$data['filter_date_end'] . "'";
        }

        if (!empty($data['filter_premium'])) {
            $sql .= " AND o.premium = '" . (int)$data['filter_premium'] . "'";
        }

        if (!empty($data['filter_hot'])) {
            $sql .= " AND o.hot = '" . (int)$data['filter_hot'] . "'";
        }

        if (isset($data['filter_title']) && $data['filter_title'] != '') {
            $sql .= " AND o.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        $sort_data = array(
            'o.name',
            'o.date_end',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'o.name') {
                $sql .= " ORDER BY o.name";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY o.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, o.date_added DESC";
        } else {
            $sql .= " ASC, o.date_added ASC";
        }

        $result = $this->db->query($sql);

        if ($result->row['total']) {
            return (int)$result->row['total'];
        }
        return 0;
    }

    public function getOrderHistory($order_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
        return $result->rows;
    }

    public function getOrderAttachment($order_id)
    {
        $result = $this->db->query("SELECT a.* FROM " . DB_PREFIX . "order_attachment oa LEFT JOIN " . DB_PREFIX . "attachment a ON oa.attachment_id = a.attachment_id WHERE oa.order_id = '" . (int)$order_id . "'");
        return $result->rows;
    }

    public function getOrderOfferAttachments($order_id)
    {
        $result = $this->db->query("SELECT a.*, oa.order_offer_attachment_id FROM " . DB_PREFIX . "order_offer_attachment oa LEFT JOIN " . DB_PREFIX . "attachment a ON oa.attachment_id = a.attachment_id WHERE oa.order_id = '" . (int)$order_id . "'");
        return $result->rows;
    }

    public function updateViewed($order_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET viewed = viewed + 1 WHERE order_id = '" . (int)$order_id . "'");
    }

    public function getOrderOfferAttachment($order_offer_attachment_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_offer_attachment WHERE order_offer_attachment_id = '" . $order_offer_attachment_id . "'");

        if (isset($result->row['order_offer_attachment_id'])) {
            return $result->row;
        }
        return false;
    }

    public function deleteOfferAttachment($order_offer_attachment_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_offer_attachment WHERE order_offer_attachment_id = '" . (int)$order_offer_attachment_id . "'");
    }

    public function setOrderStatus($order_id, $order_status_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
    }
}
