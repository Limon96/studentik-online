<?php
class ModelOrderOrder extends Model {

    public function addOrder($data)
    {
        $query = "INSERT INTO " . DB_PREFIX . "order SET
                        store_id = '" . (int)$this->config->get('config_store_id') . "',
                        customer_id = '" . (int)$this->customer->getId() . "',
                        customer_group_id = '" . (int)$this->customer->getGroupId() . "',
                        work_type_id = '" . (int)$data['work_type'] . "',
                        subject_id = '" . (int)$data['subject'] . "',
                        section_id = (SELECT s.section_id FROM " . DB_PREFIX . "subject s WHERE s.subject_id = '" . (int)$data['subject'] . "' LIMIT 1),
                        order_status_id = '" . (int)$this->config->get('config_order_status_id') . "',
                        payment_blocking_id = '3',
                        plagiarism_check_id = '" . (int)$data['plagiarism_check_id'] . "',
                        plagiarism = '" . json_encode(( isset($data['plagiarism']) ? $data['plagiarism'] : [] ), JSON_UNESCAPED_UNICODE) . "',
                        premium = '" . (isset($data['premium']) ? (int)$data['premium'] : 0) . "',
                        hot = '" . (isset($data['hot']) ? (int)$data['hot'] : 0) . "',
                        price = '" . (float)$data['price'] . "',
                        title = '" . $this->db->escape($data['title']) . "',
                        description = '" . $this->db->escape($data['description']) . "',
                        date_end = '" . $this->db->escape($data['date_end']) . "',
                        ip = '" . $this->request->server['REMOTE_ADDR'] . "',
                        date_added = '" . time() . "',
                        viewed = 0";

        $this->db->query($query);

        $order_id = $this->db->getLastId();

        if (isset($data['attachment'])) {
            foreach ($data['attachment'] as $attachment_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_attachment  SET order_id = '" . (int)$order_id . "', attachment_id = '" . (int)$attachment_id . "'");
            }
        }

        // Списание средств за премиумный заказ
        if (isset($data['premium']) && $data['premium'] == 1) {
            $this->load->language('account/transaction');
            $this->load->model('account/customer');

            $price_premium_order = 100;

            $amount = 0 - $price_premium_order;

            $this->model_account_customer->setBalance(
                $this->customer->getId(),
                $amount,
                sprintf($this->language->get('transaction_order_premium'), $order_id, $data['title'])
            );

            $this->taskManager->set([
                'channel' => 'default',
                'type' => 'offer_premium',
                'time_exec' => time() + 86400,
                'object' => [
                    'order_id' => $order_id
                ]
            ]);
        }

        // Списание средств за срочный заказ
        if (isset($data['hot']) && $data['hot'] == 1) {
            $this->load->language('account/transaction');
            $this->load->model('account/customer');

            $price_premium_order = 100;

            $amount = 0 - $price_premium_order;

            $this->model_account_customer->setBalance(
                $this->customer->getId(),
                $amount,
                sprintf($this->language->get('transaction_order_hot'), $order_id, $data['title'])
            );
        }

        $seo_url = seo_translit($order_id . ' ' . $data['title']);
        $this->db->query("INSERT INTO " . DB_PREFIX. "seo_url SET store_id = 0, language_id = 1, query = 'order_id=" . (int)$order_id . "', keyword = '" . $this->db->escape($seo_url) . "'");

        return $order_id;
    }

    public function editOrder($order_id, $data)
    {
        $query = "UPDATE " . DB_PREFIX . "order SET
                    work_type_id = '" . (int)$data['work_type'] . "',
                    subject_id = '" . (int)$data['subject'] . "',
                    section_id = (SELECT s.section_id FROM " . DB_PREFIX . "subject s WHERE s.subject_id = '" . (int)$data['subject'] . "' LIMIT 1),
                    plagiarism_check_id = '" . (int)$data['plagiarism_check_id'] . "',
                    plagiarism = '" . json_encode(( isset($data['plagiarism']) ? $data['plagiarism'] : [] ), JSON_UNESCAPED_UNICODE) . "',
                    price = '" . (float)$data['price'] . "',
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

    public function cancelOrder($order_id)
    {
        $query = "UPDATE " . DB_PREFIX . "order SET
                    order_status_id = '" . $this->config->get('config_canceled_order_status_id') . "',
                    date_modified = NOW()
                WHERE order_id = '" . (int)$order_id . "'";

        $this->db->query($query);
    }

    public function openOrder($order_id)
    {
        $query = "UPDATE " . DB_PREFIX . "order SET
                    order_status_id = '" . $this->config->get('config_open_order_status_id') . "',
                    date_modified = NOW()
                WHERE order_id = '" . (int)$order_id . "'";

        $this->db->query($query);
    }

    public function setOrderStatus($order_id, $order_status_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
    }

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
            FROM " . DB_PREFIX . "order o
            WHERE o.order_id = '" . (int)$order_id . "'"
        );
        return $query->row;
    }

    public function getOrders($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "order o WHERE customer_id > 0";

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

        if (isset($data['filter_no_offer']) && $data['filter_no_offer']) {
            $sql .= " AND (SELECT COUNT(1) FROM " . DB_PREFIX . "offer oo WHERE oo.order_id = o.order_id) = 0";
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
            $sql .= " AND (title LIKE '%" . $this->db->escape($data['search']) . "%' OR description LIKE '%" . $this->db->escape($data['search']) . "%')";
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
            $sql .= " DESC, date_added DESC";
        } else {
            $sql .= " ASC, date_added ASC";
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
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "order o WHERE customer_id > 0";

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

        if (isset($data['filter_no_offer']) && $data['filter_no_offer']) {
            $sql .= " AND (SELECT COUNT(1) FROM " . DB_PREFIX . "offer oo WHERE oo.order_id = o.order_id) = 0";
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
            $sql .= " AND (title LIKE '%" . $this->db->escape($data['search']) . "%' OR description LIKE '%" . $this->db->escape($data['search']) . "%')";
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

    public function getOrderOfferAttachment($order_offer_attachment_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_offer_attachment WHERE order_offer_attachment_id = '" . $order_offer_attachment_id . "'");
        if (isset($result->row['order_offer_attachment_id'])) {
            return $result->row;
        }
        return false;
    }

    public function getOrderPaymentBlockingValue($order_id)
    {
        $result = $this->db->query("SELECT pb.value FROM ". DB_PREFIX . "payment_blocking pb LEFT JOIN ". DB_PREFIX . "order o ON pb.payment_blocking_id = o.payment_blocking_id WHERE o.order_id = '" . $order_id . "'");
        if (isset($result->row['value'])) {
            return (int)$result->row['value'];
        }
        return 0;
    }

    public function getOrderHistory($order_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "' ORDER BY date_added DESC");


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
        $this->db->query("UPDATE " . DB_PREFIX . "order SET viewed = viewed + 1 WHERE order_id = '" . (int)$order_id . "'");
    }

    public function addAttachment($order_id, $attachment_id)
    {
        $this->db->query("INSERT " . DB_PREFIX . "order_attachment SET attachment_id = " . (int)$attachment_id . ", order_id = '" . (int)$order_id . "'");
    }

    public function deleteAttachment($order_id, $attachment_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_attachment WHERE attachment_id = " . (int)$attachment_id . " AND order_id = '" . (int)$order_id . "'");
    }

    public function addOfferAttachment($order_id, $attachment_id)
    {
        $this->db->query("INSERT " . DB_PREFIX . "order_offer_attachment SET attachment_id = " . (int)$attachment_id . ", order_id = '" . (int)$order_id . "'");
    }

    public function deleteOfferAttachment($order_offer_attachment_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_offer_attachment WHERE order_offer_attachment_id = '" . (int)$order_offer_attachment_id . "'");
    }
}
