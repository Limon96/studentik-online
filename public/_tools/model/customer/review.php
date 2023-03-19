<?php

class ModelCustomerReview extends Model {

    public function getReviews($customer_id)
    {
        $result = $this->db->query(
            "SELECT *,
                r.customer_id,
                (SELECT c.login FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer_login
            FROM " . DB_PREFIX . "customer_review cr LEFT JOIN " . DB_PREFIX . "review r ON cr.review_id = r.review_id WHERE cr.customer_id = '" . (int)$customer_id . "' ORDER BY r.date_added DESC"
        );

        return $result->rows;
    }

}