<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET
            customer_group_id = '" . (int)$customer_group_id . "',
            store_id = '" . (int)$this->config->get('config_store_id') . "',
            language_id = '" . (int)$this->config->get('config_language_id') . "',
            customer_status_id = '" . (int)$this->config->get('config_language_id') . "',
            country_id = '" . (int)$this->config->get('config_country_id') . "',
            login = '',
            email = '" . $this->db->escape($data['email']) . "',
            salt = '" . $this->db->escape($salt = token(9)) . "',
            password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',
            ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
            timezone = '" . $this->db->escape($this->config->get('config_timezone')) . "',
            status = '" . (int)!$customer_group_info['approval'] . "',
            referral_code = '" . $this->db->escape($this->session->data['referral_code'] ?? '') . "',
            date_added = '" . time() . "'"
        );

		$customer_id = $this->db->getLastId();

		if (isset($data['login']) && $data['login'] !== '') {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET login = '" . $this->db->escape($data['login']) . "' WHERE customer_id = '" . (int)$customer_id . "'");

            $seo_url = seo_translit($data['login']);
            $this->setCustomerSeoUrl($customer_id, $seo_url);
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET login = 'user" . (int)$customer_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

            $seo_url = seo_translit('user' . (int)$customer_id);
            $this->setCustomerSeoUrl($customer_id, $seo_url);
        }




		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = '" . time() . "'");
		}

		return $customer_id;
	}

	public function addCustomerFromOrder($data) {
	    $data['password'] = substr(md5($data['email'] . microtime(true) . DB_PREFIX), 0, 8);

		$customer_group_id = $this->config->get('config_customer_group_id');

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query(
            "INSERT INTO " . DB_PREFIX . "customer SET
                customer_group_id = '" . (int)$customer_group_id . "',
                store_id = '" . (int)$this->config->get('config_store_id') . "',
                language_id = '" . (int)$this->config->get('config_language_id') . "',
                customer_status_id = '" . (int)$this->config->get('config_language_id') . "',
                country_id = '" . (int)$this->config->get('config_country_id') . "',
                login = '', email = '" . $this->db->escape($data['email']) . "',
                salt = '" . $this->db->escape($salt = token(9)) . "',
                password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',
                ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
                timezone = '" . $this->db->escape($this->config->get('config_timezone')) . "',
                status = '" . (int)!$customer_group_info['approval'] . "',
                referral_code = '" . $this->db->escape($this->session->data['referral_code'] ?? '') . "',
                date_added = '" . time() . "'"
        );

		$customer_id = $this->db->getLastId();

		if (isset($data['login']) && $data['login'] !== '') {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET login = '" . $this->db->escape($data['login']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET login = 'user" . (int)$customer_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
            $data['login'] = 'user' . (int)$customer_id;
		}

        $seo_url = seo_translit('user' . (int)$customer_id);
        $this->setCustomerSeoUrl($customer_id, $seo_url);

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = '" . time() . "'");
		}

        //$this->customer->login($data['email'], $data['password']);

        if (isset($data['email'])) {
            $this->customer->login($data['email'], '', true);
        }

		$this->load->language('mail/register');

		$data['text_welcome'] = sprintf(
            $this->language->get('text_welcome'),
            $this->config->get('config_name')
        );

        if ($customer_group_info['approval']) {

            $customer_token = sha1($data['email'] . microtime(true));

            $this->model_account_customer->setApprovalToken($data['email'], $customer_token);

            $data['approval_link'] = $this->url->link('account/register/approval', 'customer_token=' . $customer_token);

            $this->taskManager->set([
                'channel' => 'emails',
                'type' => 'email_send',
                'time_exec' => time(),
                'object' => [
                    'to' => $data['email'],
                    'subject' => sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')),
                    'message' => $this->load->view('mail/register_approval', $data),
                ]
            ]);

        } else {
            $this->taskManager->set([
                'channel' => 'emails',
                'type' => 'email_send',
                'time_exec' => time(),
                'object' => [
                    'to' => $data['email'],
                    'subject' => html_entity_decode(sprintf(
                        $this->language->get('text_subject'),
                        $this->config->get('config_name')
                    ), ENT_QUOTES, 'UTF-8'),
                    'message' => $this->load->view('mail/register_from_order', $data)
                ]
            ]);
        }

		return $customer_id;
	}

	public function editCustomer($customer_id, $data) {


		$this->db->query("UPDATE " . DB_PREFIX . "customer SET
            `firstname` = '" . $this->db->escape($data['firstname']) . "',
            `email` = '" . $this->db->escape($data['email']) . "',
            `telephone` = '" . $this->db->escape($data['telephone']) . "',
            `bdate` = '" . $this->db->escape($data['bdate']) . "',
            `languages` = '" . $this->db->escape($data['languages']) . "',
            `country_id` = '" . (int)$data['country_id'] . "',
            `gender` = '" . (int)$data['gender'] . "',
            `setting_email_notify` = '" . (int)$data['setting_email_notify'] . "',
            `setting_email_new_order` = '" . (isset($data['setting_email_new_order']) ? (int)$data['setting_email_new_order'] : 0) . "',
            `timezone` = '" . $this->db->escape($data['timezone']) . "',
            `languages` = '" . $this->db->escape($data['languages']) . "',
            `comment` = '" . (isset($data['comment']) ? $this->db->escape($data['comment']): '') . "'
        WHERE customer_id = '" . (int)$customer_id . "'");

        if (isset($data['login'])) {
            $customer_info = $this->getCustomerInfo($customer_id);

            if ($customer_info['login'] == 'user' . $customer_info['customer_id']) {
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET `login` = '" . $this->db->escape($data['login']) . "' WHERE customer_id = '" . (int)$customer_id . "'");

                $this->db->escape("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_id=" . $customer_id . "'");

                $seo_url = seo_translit($data['login']);
                $this->setCustomerSeoUrl($customer_id, $seo_url);
            }
        }

	}

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editFirstname($customer_id, $firstname) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($firstname) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editEmail($customer_id, $email) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET email = '" . $this->db->escape(utf8_strtolower($email)) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editGenderId($customer_id, $gender_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET gender_id = '" . (int)$gender_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editCountryId($customer_id, $country_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET country_id = '" . (int)$country_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editTimeZone($customer_id, $timezone) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET timezone = '" . $this->db->escaep($timezone) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editGender($customer_id, $gender) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET gender = '" . (int)$gender. "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function setApprovalToken($email, $approval_token) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET approval_token = '" . $this->db->escape($approval_token) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function approvalCustomer($approval_token) {
        $result = $this->db->query("SELECT customer_id, email FROM " . DB_PREFIX . "customer WHERE approval_token = '" . $this->db->escape($approval_token) . "'");

        $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET approval_token = '', status = 1 WHERE approval_token = '" . $this->db->escape($approval_token) . "'");

        if (isset($result->row['email'])) {
            $this->customer->login($result->row['email'], '', true);
        }
    }

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerPro($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_pro WHERE customer_id = '" . (int)$customer_id . "' AND date_end > '" . time() . "'");

		return $query->row;
	}

	public function getCustomerSections($customer_id) {
		$query = $this->db->query(
            "SELECT cs.customer_id, s.section_id, sj.name, s.name AS `section`
                FROM " . DB_PREFIX . "customer_subject cs
                LEFT JOIN " . DB_PREFIX . "subject sj ON (cs.subject_id = sj.subject_id)
                LEFT JOIN " . DB_PREFIX . "section s ON (s.section_id = sj.section_id)
            WHERE customer_id = '" . (int)$customer_id . "' GROUP BY s.section_id ORDER BY `section` ASC"
        );
		return $query->rows;
	}

	public function getCustomerSubjects($customer_id, $section_id = 0) {
		$query = $this->db->query(
		    "SELECT cs.customer_id, s.section_id, s.name AS `section`, sj.subject_id, sj.name AS subject
                FROM " . DB_PREFIX . "customer_subject cs
                LEFT JOIN " . DB_PREFIX . "subject sj ON (cs.subject_id = sj.subject_id)
                LEFT JOIN " . DB_PREFIX . "section s ON (s.section_id = sj.section_id)
            WHERE customer_id = '" . (int)$customer_id . "' " . ($section_id > 0 ? " AND s.section_id = '" . (int)$section_id . "'": '') . "
            ORDER BY `section` ASC, subject ASC"
        );

		return $query->rows;
	}

    public function setCustomerSubjects($data)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_subject WHERE customer_id = '" . (int)$this->customer->getId() ."'");
        if (isset($data['subjects']) && count($data['subjects']) > 0) {
            $sql = "INSERT INTO " . DB_PREFIX . "customer_subject (customer_id, subject_id) VALUES";

            $temp = array();
            foreach ($data['subjects'] as $subject_id) {
                $temp[] = "('" . (int)$this->customer->getId() ."','" . $subject_id . "')";
            }

            $sql .= implode(', ', $temp);

            $this->db->query($sql);
        }
	}

	public function getCustomerByEmailOrLogin($login) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($login)) . "' OR LOWER(login) = '" . $this->db->escape(utf8_strtolower($login)) . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByApprovalToken($approval_token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE approval_token = '" . $this->db->escape($approval_token) . "'");

		return $query->row;
	}

	public function getCustomerByLogin($login) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE login = '" . $this->db->escape($login) . "'");

		return $query->row;
	}

	public function getCustomerByCode($code) {
		$query = $this->db->query("SELECT customer_id, firstname, email FROM `" . DB_PREFIX . "customer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

    public function getCustomerInfo($customer_id) {
        $query = $this->db->query("SELECT
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
        FROM " . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int)$customer_id . "'");

        return $query->row;
    }

	public function getCustomers($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX ."customer WHERE status = 1";

        if (isset($data['filter_customer_group_id'])) {
            $sql .= " AND customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (isset($data['search']) && $data['search'] != '') {
            $sql .= " AND login LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        $sorts = [
            'date_added',
            'rating'
        ];

        if (in_array($data['sort'], $sorts)) {
            $sql .= " ORDER BY " . $this->db->escape($data['sort']);
        } else {
            $sql .= " ORDER BY rating";
        }

        if ($data['order'] == 'ASC') {
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

        $query = $this->db->query($sql);
        $customer_data = array();

        if ($query->rows) {
            foreach ($query->rows as $customer) {
                $customer_data[$customer['customer_id']] = $this->getCustomerInfo($customer['customer_id']);
            }
        }

        return $customer_data;
    }

	public function searchCustomers($data = array()) {
        $sql = "SELECT customer_id FROM " . DB_PREFIX ."customer WHERE status = 1";

        if (isset($data['filter_customer_group_id'])) {
            $sql .= " AND customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (isset($data['search']) && $data['search'] != '') {
            $sql .= " AND login LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        if (isset($data['start']) && isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 0) {
                $data['limit'] = 0;
            }

            $sql .= " LIMIT " . $data['start'] . "," . $data['limit'];
        } else {
            $sql .= " LIMIT 0,20";
        }

        $query = $this->db->query($sql);


        return $query->rows;
    }

	public function getTotalCustomers ($data = array()) {
        $sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX ."customer WHERE status = 1";

        if (isset($data['filter_customer_group_id'])) {
            $sql .= " AND customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (isset($data['search']) && $data['search'] != '') {
            $sql .= " AND login LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        $sorts = [
            'date_added',
            'rating'
        ];

        if (in_array($data['sort'], $sorts)) {
            $sql .= " ORDER BY " . $this->db->escape($data['sort']);
        } else {
            $sql .= " ORDER BY rating";
        }

        if ($data['order'] == 'ASC') {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        $query = $this->db->query($sql);
        if ($query->row['total']) {
            return (int)$query->row['total'];
        }
        return 0;
    }

    public function getTopCustomers($data = array()) {
        $sql = "SELECT c.* FROM " . DB_PREFIX ."customer c WHERE c.status = 1";

        if (isset($data['filter_customer_group_id'])) {
            $sql .= " AND c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        $sql .= " ORDER BY
            (SELECT SUM(cr.rating) FROM " . DB_PREFIX . "customer_rating cr WHERE cr.customer_id = c.customer_id AND cr.date_added > " . (time() - 604800) . ") DESC,
            c.rating DESC";

        if (isset($data['limit'])) {
            $sql .= " LIMIT 0," . (int)$data['limit'];
        } else {
            $sql .= " LIMIT 0,3";
        }

        $query = $this->db->query($sql);

        $customer_data = array();

        if ($query->rows) {
            foreach ($query->rows as $customer) {
                $customer_data[$customer['customer_id']] = $this->getCustomerInfo($customer['customer_id']);
            }
        }

        return $customer_data;
    }

    public function setRating($customer_id, $rating)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_rating SET
            customer_id = '" . (int)$customer_id . "',
            rating = '" . (int)$rating . "',
            date_added = '" . time() . "'
        ");

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET rating = rating + '" . (int)$rating . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function setCustomerAvatar($customer_id, $image)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET image = '" . $this->db->escape($image) . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function setBalance($customer_id, $amount, $description = '')
    {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance + '" . (float)$amount . "' WHERE customer_id = '" . (int)$customer_id . "'");

        // Запись истории транзакций
        $this->load->model('account/transaction');
        $this->load->language('account/transaction');

        $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        if (!$description) {
            if ($amount > -1) {
                $description = $this->language->get('transaction_refill');
            } else {
                $description = $this->language->get('transaction_withdraw');
            }
        }

        $this->model_account_transaction->setTransaction([
            'customer_id' => $customer_id,
            'amount' => (float)$amount,
            'description' => $description,
            'balance' => (isset($result->row['balance']) ? (float)$result->row['balance']: 0),
        ]);
    }

    public function setBlockedCash($data)
    {
        // Записываем блокируемую сумму
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_blocked_cash SET customer_id = '" . (int)$data['customer_id'] . "', order_id = '" . (int)$data['order_id'] . "', offer_id = '" . (int)$data['offer_id'] . "', balance = '" . (float)$data['balance'] . "', date_end = '" . $this->db->escape($data['date_end']) . "', date_added = '" . time() . "'");

        // Списываем с баланса блокируемую сумму
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance - '" . (float)$data['balance'] . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");

        // Запись истории транзакций
        $this->load->model('order/order');
        $this->load->model('account/transaction');
        $this->load->language('account/transaction');

        $amount = $data['balance'];

        $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$data['customer_id'] . "'");

        $order_info = $this->model_order_order->getOrder($data['order_id']);

        $this->model_account_transaction->setTransaction([
            'customer_id' => $data['customer_id'],
            'amount' => (float)$amount,
            'description' => sprintf($this->language->get('transaction_blocked'),  $order_info['order_id'], $order_info['title']),
            'balance' => (isset($result->row['balance']) ? (float)$result->row['balance']: 0),
        ]);
    }

    public function returnBlockedCash($data)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$data['customer_id'] . "' AND order_id = '" . (int)$data['order_id'] . "' AND offer_id = '" . (int)$data['offer_id'] . "'");

        if (isset($result->row['balance'])) {
            // Удаляем запись о блокировке средств
            $this->db->query("DELETE FROM  " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$data['customer_id'] . "' AND order_id = '" . (int)$data['order_id'] . "' AND offer_id = '" . (int)$data['offer_id'] . "'");

            // Пополняем баланс на блокированную сумму
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance + '" . (float)$result->row['balance'] . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");

            // Запись истории транзакций
            $this->load->model('order/order');
            $this->load->model('account/transaction');
            $this->load->language('account/transaction');

            $amount = $result->row['balance'];

            $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$data['customer_id'] . "'");

            $order_info = $this->model_order_order->getOrder($data['order_id']);

            $this->model_account_transaction->setTransaction([
                'customer_id' => $data['customer_id'],
                'amount' => (float)$amount,
                'description' => sprintf($this->language->get('transaction_return'),  $order_info['order_id'], $order_info['title']),
                'balance' => (isset($result->row['balance']) ? (float)$result->row['balance']: 0),
            ]);
        }
    }

    public function setBalanceOfferFromBlockedCash($customer_id, $offer_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_blocked_cash WHERE offer_id = '" . (int)$offer_id . "'");
        if ($result->row) {
            // Вычисляем сумму для выплаты автору
            // блок.сумма - процент студента - процент автора

            $cash = $result->row['balance'] / (1 + (int)$this->config->get('config_commission_customer') / 100) * (1 - (int)$this->config->get('config_commission') / 100);

            // Записываем автору блокированную сумму у заказчика
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance + '" . (float)$cash . "' WHERE customer_id = '" . (int)$customer_id . "'");


            // Удаляем запись
            $this->db->query("DELETE FROM  " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$result->row['customer_id'] . "' AND order_id = '" . (int)$result->row['order_id'] . "' AND offer_id = '" . (int)$offer_id . "'");

            // Запись истории транзакций
            $this->load->model('order/order');
            $this->load->model('account/transaction');
            $this->load->language('account/transaction');

            $customer_info = $this->getCustomerInfo($result->row['customer_id']);
            $order_info = $this->model_order_order->getOrder($result->row['order_id']);

            $amount = $result->row['balance']/ (1 + (int)$this->config->get('config_commission_customer') / 100);

            // order owner зачисляем рейтинг
            $this->setRating($result->row['customer_id'], $amount / 100);
            // offer owner зачисляем рейтинг
            $this->setRating($customer_id, $amount / 100);


            $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

            $this->model_account_transaction->setTransaction([
                'customer_id' => $customer_id,
                'amount' => (float)$cash,
                'description' => sprintf($this->language->get('transaction_income'), $customer_info['login'], $order_info['order_id'], $order_info['title']),
                'balance' => (isset($cash) ? (float)$cash: 0),
            ]);
        }
    }

    public function getCustomerAttachment($customer_id)
    {
        $result = $this->db->query("SELECT a.* FROM " . DB_PREFIX . "customer_attachment oa LEFT JOIN " . DB_PREFIX . "attachment a ON oa.attachment_id = a.attachment_id WHERE oa.customer_id = '" . (int)$customer_id . "'");
        return $result->rows;
    }

    public function addAttachment($attachment_id)
    {
        $this->db->query("INSERT " . DB_PREFIX . "customer_attachment SET attachment_id = " . (int)$attachment_id . ", customer_id = '" . (int)$this->customer->getId() . "'");
    }

    public function deleteAttachment($attachment_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_attachment WHERE attachment_id = " . (int)$attachment_id . " AND customer_id = '" . (int)$this->customer->getId() . "'");
    }

    public function setCustomerSeoUrl($customer_id, $keyword)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX. "seo_url SET store_id = 0, language_id = 1, query = 'customer_id=" . (int)$customer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
    }
}
