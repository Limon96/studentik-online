<?php
class ModelToolOnline extends Model {
	public function addOnline($ip, $customer_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'");

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_online` SET `ip` = '" . $this->db->escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
	}

    public function format($timestamp)
    {
        $this->load->language('tool/online');

        $interval = time() - $timestamp;

        if ($interval < 900) {
            $output = $this->language->get('text_online');
        } else {
            if ($interval < 3600) {
                $interval = floor($interval / 60);
                if ($interval > 10 && $interval < 20) {
                    $output = sprintf($this->language->get('text_offline_minute'), $interval);
                } else {
                    $last = substr($interval, -1);
                    if ($last == 1) {
                        $output = sprintf($this->language->get('text_offline_minute_one'), $interval);
                    } elseif ($last >= 2 && $last <= 4) {
                        $output = sprintf($this->language->get('text_offline_minutes'), $interval);
                    } else {
                        $output = sprintf($this->language->get('text_offline_minute'), $interval);
                    }
                }
            } elseif ($interval >= 3600 && $interval < 10800) {
                $interval = floor($interval / 60 / 60);
                if ($interval == 1) {
                    $output = sprintf($this->language->get('text_offline_hour'), $interval);
                } else {
                    $output = sprintf($this->language->get('text_offline_hours'), $interval);
                }
            } elseif ($interval >= 10800 && $interval < 31536000) {
                $date = date('j ', $timestamp) . ' ' . $this->language->get('month_' . date('n', $timestamp)) . ' ' . date(' в G:i', $timestamp);
                $output = sprintf($this->language->get('text_offline_date'), $date);
            } else {
                $date = date('j M o в G:i', $timestamp);
                $output = sprintf($this->language->get('text_offline_date'), $date);
            }
        }
        return $output;
	}
}
