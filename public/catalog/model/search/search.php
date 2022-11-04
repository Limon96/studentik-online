<?php

class ModelSearchSearch extends Model
{

    /*
     * id
     * title
     * type
     * online
     * section
     * subject
     * work_type
     * description
     * */

    public function search($data = array())
    {
        $tables = array();

        if (isset($data['search_customer']) && $data['search_customer']) {
            $tables[] = "SELECT customer_id AS id, 'customer' AS type,  login AS title, '' AS description FROM " . DB_PREFIX . "customer c";
        }

        if (isset($data['search_order']) && $data['search_order']) {
            $tables[] = "SELECT order_id AS id, 'order' AS type, title, description FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id = 1";
        }

        if (count($tables) > 0) {
            $sql = "SELECT * FROM (" . implode(" UNION ", $tables) . ") temp";

            if (isset($data['search'])) {
                $sql .= " WHERE (title LIKE '%" . $this->db->escape($data['search']) . "%' OR description LIKE '%" . $this->db->escape($data['search']) . "%')";
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

            $result = $this->db->query($sql);

            if ($result->rows) {
                return $this->completeResults($result->rows);
            }
        }

        return [];
    }

    protected function completeResults($results)
    {
        $customer = [];
        $order = [];

        foreach ($results as $result) {
            if ($result['type'] === 'customer') {
                $customer[] = $result['id'];
            }
            if ($result['type'] === 'order') {
                $order[] = $result['id'];
            }
        }

        $customer = $this->getCustomersByIDs($customer);
        $order = $this->getOrderByIDs($order);

        foreach ($results as &$result) {
            if ($result['type'] === 'customer' && isset($customer[$result['id']])) {
                $result = array_merge($result, $customer[$result['id']]);
            }
            if ($result['type'] === 'order' && isset($order[$result['id']])) {
                $result = array_merge($result, $order[$result['id']]);
            }
        }

        return $results;
    }

    protected function getCustomersByIDs(array $ids = []): array
    {
        if (!count($ids)) {
            return [];
        }

        $results = $this->db->query("SELECT
            c.customer_id AS id,
            c.customer_id,
            c.customer_group_id,
            c.firstname,
            c.login,
            c.gender,
            c.image,
            c.date_added,
            c.last_seen,
            c.languages,
            c.bdate,
            c.comment,
            c.email,
            c.telephone,
            c.rating,
            c.timezone,
            (SELECT SUM(cr.rating) FROM " . DB_PREFIX . "customer_rating cr WHERE cr.customer_id = c.customer_id AND cr.date_added > " . (time() - 604800) . ") as new_rating,
            c.setting_email_notify,
            (SELECT COUNT(1) AS total FROM " . DB_PREFIX . "offer of1 LEFT JOIN `" . DB_PREFIX . "order` or1 ON of1.order_id=or1.order_id WHERE of1.customer_id=c.customer_id AND or1.order_status_id='" . $this->config->get('config_complete_order_status_id') . "' AND of1.assigned = 1) AS total_orders,
            (SELECT COUNT(1) AS total FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE cr.customer_id = c.customer_id) AS total_reviews,
            (SELECT COUNT(1) AS total FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE cr.customer_id = c.customer_id AND r.positive = 1) AS total_reviews_positive,
            (SELECT COUNT(1) AS total FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE cr.customer_id = c.customer_id AND r.positive = 0) AS total_reviews_negative,
            IF((SELECT COUNT(1) AS count_pro FROM " . DB_PREFIX . "customer_pro WHERE customer_id = '" . (int)$customer_id . "' AND date_end > '" . time() . "') > 0, 1, 0) AS pro,
            (SELECT country.name FROM " . DB_PREFIX . "country country WHERE country.country_id = c.country_id AND country.status = '1') AS country,
            IF(" . time() . " - c.last_seen  < 900, 1, 0) AS online,
            (SELECT cn.text FROM " . DB_PREFIX . "customer_note cn WHERE cn.customer_id = c.customer_id AND cn.owner_id = '" . (int)$this->customer->getId() . "') AS note
        FROM " . DB_PREFIX . "customer c WHERE c.customer_id IN (" . join(',', $ids) . ")");

        if ($results->rows) {
            return $this->id2Key($results->rows);
        }

        return [];
    }

    protected function getOrderByIDs(array $ids = []): array
    {
        if (!count($ids)) {
            return [];
        }

        $results = $this->db->query("SELECT *,
                o.order_id AS id,
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
            WHERE o.order_id IN (" . join(',', $ids) . ")");

        if ($results->rows) {
            return $this->id2Key($results->rows);
        }

        return [];
    }

    protected function id2Key($array): array
    {
        $output = [];

        foreach ($array as $item) {
            $output[$item['id']] = $item;
        }

        return $output;
    }

}
