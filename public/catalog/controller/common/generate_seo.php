<?php
class ControllerCommonGenerateSeo extends Controller
{
    public function index()
    {
        $totals = [];
        $result = $this->db->query("SELECT order_id, title FROM " . DB_PREFIX . "order");

        if ($result->rows) {
            $totals['order_total'] = $result->num_rows;
            $totals['order_skipped'] = 0;
            $totals['order_generated'] = 0;

            foreach ($result->rows as $row) {
                $seo_url = $this->getUrl('order_id=' . $row['order_id']);

                if (!$seo_url) {
                    $seo_url_data = seo_translit($row['order_id'] . ' ' . $row['title']);

                    $this->setUrl('order_id=' . $row['order_id'], $seo_url_data);
                    $totals['order_generated']++;
                } else {
                    $totals['order_skipped']++;
                }
            }
        }

        $result = $this->db->query("SELECT customer_id, login FROM " . DB_PREFIX . "customer");

        if ($result->rows) {
            $totals['customer_total'] = $result->num_rows;
            $totals['customer_skipped'] = 0;
            $totals['customer_generated'] = 0;

            foreach ($result->rows as $row) {
                $seo_url = $this->getUrl('customer_id=' . $row['customer_id']);

                if (!$seo_url) {
                    $seo_url_data = seo_translit($row['login']);

                    $this->setUrl('customer_id=' . $row['customer_id'], $seo_url_data);
                    $totals['customer_generated']++;
                } else {
                    $totals['customer_skipped']++;
                }
            }
        }

        print_r($totals);
    }

    private function setUrl($key, $value)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = 0, language_id = 1, query = '" . $this->db->escape($key) . "', keyword = '" . $this->db->escape($value) . "'");
    }

    private function getUrl($key)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($key) . "'");

        if ($result->row) {
            return $result->row;
        }
        return false;
    }
}