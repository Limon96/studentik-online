<?php
class ModelCustomerWithdrawal extends Model {

	public function editWithdrawal($withdrawal_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "withdrawal SET
            status = '" . (int)$data['status'] . "', 
            comment = '" . $this->db->escape($data['comment']) . "'           
            WHERE withdrawal_id = '" . (int)$withdrawal_id . "'
        ");

		$this->cache->delete('withdrawal');
	}

	public function setStatus($withdrawal_id, $status) {
        $this->db->query("UPDATE " . DB_PREFIX . "withdrawal SET
                status = '" . (int)$status . "',
                date_updated = '" . time() . "'          
            WHERE withdrawal_id = '" . (int)$withdrawal_id . "'
        ");

		$this->cache->delete('withdrawal');
	}

	public function setComment($withdrawal_id, $comment) {
        $this->db->query("UPDATE " . DB_PREFIX . "withdrawal SET
                comment = '" . $this->db->escape($comment) . "',
                date_updated = '" . time() . "'          
            WHERE withdrawal_id = '" . (int)$withdrawal_id . "'
        ");

		$this->cache->delete('withdrawal');
	}

	public function deleteWithdrawal($withdrawal_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id = '" . (int)$withdrawal_id . "'");

		$this->cache->delete('withdrawal');
	}

	public function getWithdrawal($withdrawal_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id = '" . (int)$withdrawal_id . "'");

		return $query->row;
	}

	public function getWithdrawals($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id > 0";

            if (isset($data['status'])) {
                $sql .= " AND status = '" . (int)$data['status'] . "'";
            }

			$sql .= " ORDER BY date_added";

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
		} else {
            $query = $this->db->query("SELECT withdrawal_id, name FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id > 0 ORDER BY date_added DESC");
		}

        $withdrawal_data = $this->formatWithdrawals($query->rows);

        return $withdrawal_data;
	}

	public function getTotalWithdrawals() {
		$sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "withdrawal WHERE withdrawal_id > 0";

        if (isset($data['status'])) {
            $sql .= " AND status = '" . (int)$data['status'] . "'";
        }

        $query = $this->db->query($sql);

		return $query->row['total'];
	}

    private function formatWithdrawals($data) {
        $this->load->model('customer/customer');

        $customer_ids = [];

        foreach ($data as $item) {
            if (!in_array($item['customer_id'], $customer_ids)) {
                $customer_ids[] = (int)$item['customer_id'];
            }
        }

        $customer_ids = implode(',', $customer_ids);

        $customers = $this->model_customer_customer->getCustomersByIds($customer_ids, true);

        foreach ($data as &$item) {
            if (isset($customers[$item['customer_id']])) {
                $item['customer'] = $customers[$item['customer_id']];
            } else {
                $item['customer'] = null;
            }
        }

        return $data;
    }
}