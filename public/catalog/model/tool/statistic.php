<?php
class ModelToolStatistic extends Model
{

    public function totalExperts()
    {
        $query = $this->db->query("SELECT COUNT(1) as total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '2'");

        if ($query->row['total']) {
            return (int)$query->row['total'];
        }

        return 0;
    }

    public function totalStudents()
    {
        $query = $this->db->query("SELECT COUNT(1) as total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '1'");

        if ($query->row['total']) {
            return (int)$query->row['total'];
        }

        return 0;
    }

    public function totalOrderCompleted()
    {
        $query = $this->db->query("SELECT COUNT(1) as total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_order_status_id') . "'");

        if ($query->row['total']) {
            return (int)$query->row['total'];
        }

        return 0;
    }

}
