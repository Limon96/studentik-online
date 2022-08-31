<?php
namespace Cart;

use Crypt\Crypt;

class Customer {
    private $customer_id;
    private $firstname;
    private $login;
    private $customer_group_id;
    private $email;
    private $telephone;
    private $customer_gender_id;
    private $customer_status_id;
    private $balance;
    private $blocked_cash;
    private $pro;
    private $timezone;
    private $gender;

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if (isset($this->session->data['customer_id'])) {
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

            if ($customer_query->num_rows) {
                $this->customer_id = $customer_query->row['customer_id'];
                $this->firstname = $customer_query->row['firstname'];
                $this->login = $customer_query->row['login'];
                $this->customer_group_id = $customer_query->row['customer_group_id'];
                $this->email = $customer_query->row['email'];
                $this->telephone = $customer_query->row['telephone'];
                $this->image = $customer_query->row['image'];
                $this->customer_gender_id = $customer_query->row['customer_gender_id'];
                $this->customer_status_id = $customer_query->row['customer_status_id'];
                $this->balance = $customer_query->row['balance'];
                $this->timezone = $customer_query->row['timezone'];
                $this->gender = $customer_query->row['gender'];

                // Get info PRO
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_pro WHERE customer_id = '" . (int)$this->customer_id . "' AND date_end > NOW()");
                if ($query->row) {
                    $this->pro = $query->row;
                } else {
                    $this->pro = false;
                }

                // Get blocked cash
                if ($customer_query->row['customer_group_id'] == 2) {
                    $query = $this->db->query(
                        "SELECT (SUM(cbc.balance)  / (1 + " . (int)$this->config->get('config_commission_customer') . " / 100) * (1 - " . (int)$this->config->get('config_commission') ." / 100)) AS blocked_cash FROM `" . DB_PREFIX . "offer` off
                            LEFT JOIN `" . DB_PREFIX . "order` o ON (off.order_id = o.order_id)
                            LEFT JOIN `" . DB_PREFIX . "customer_blocked_cash` cbc ON (o.customer_id = cbc.customer_id AND o.order_id = cbc.order_id AND off.offer_id = cbc.offer_id)
                        WHERE off.customer_id = '" . (int)$this->customer_id . "' AND o.order_status_id = 5"
                    );
                } else {
                    $query = $this->db->query("SELECT SUM(balance) AS blocked_cash FROM " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$this->customer_id . "'");
                }
                if (isset($query->row['blocked_cash'])) {
                    $this->blocked_cash = (float)$query->row['blocked_cash'];
                } else {
                    $this->blocked_cash = 0;
                }

                $query = $this->db->query("SELECT SUM(amount) AS blocked_withdrawal FROM " . DB_PREFIX . "withdrawal WHERE customer_id = '" . (int)$this->customer_id . "' AND status = 0");

                if (isset($query->row['blocked_withdrawal'])) {
                    $this->blocked_cash += (float)$query->row['blocked_withdrawal'];
                }


                $this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', last_seen = '" . time() . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

                if (!$query->num_rows) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
                }
            } else {
                $this->logout();
            }
        }
    }

    public function login($login, $password, $override = false) {
        if ($override) {
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE (LOWER(email) = '" . $this->db->escape(utf8_strtolower($login)) . "' OR login = '" . $this->db->escape($login) . "') AND status = '1'");
        } else {
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE (LOWER(email) = '" . $this->db->escape(utf8_strtolower($login)) . "' OR login = '" . $this->db->escape($login) . "') AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");
        }

        if ($customer_query->num_rows) {
            $this->session->data['customer_id'] = $customer_query->row['customer_id'];

            $this->customer_id = $customer_query->row['customer_id'];
            $this->firstname = $customer_query->row['firstname'];
            $this->login = $customer_query->row['login'];
            $this->customer_group_id = $customer_query->row['customer_group_id'];
            $this->email = $customer_query->row['email'];
            $this->telephone = $customer_query->row['telephone'];
            $this->image = $customer_query->row['image'];
            $this->customer_gender_id = $customer_query->row['customer_gender_id'];
            $this->customer_status_id = $customer_query->row['customer_status_id'];
            $this->balance = $customer_query->row['balance'];
            $this->timezone = $customer_query->row['timezone'];
            $this->gender = $customer_query->row['gender'];

            // Get info PRO
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_pro WHERE customer_id = '" . (int)$this->customer_id . "' AND date_end > NOW()");
            if ($query->row) {
                $this->pro = $query->row;
            } else {
                $this->pro = false;
            }

            // Get blocked cash
            $query = $this->db->query("SELECT SUM(balance) AS blocked_cash FROM " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$this->customer_id . "'");
            if (isset($query->row['blocked_cash'])) {
                $this->blocked_cash = (float)$query->row['blocked_cash'];
            } else {
                $this->blocked_cash = 0;
            }

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', last_seen = '" . time() . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        unset($this->session->data['customer_id']);

        $this->customer_id = '';
        $this->firstname = '';
        $this->login = '';
        $this->customer_group_id = '';
        $this->email = '';
        $this->telephone = '';
        $this->image = '';
        $this->customer_gender_id = '';
        $this->customer_status_id = '';
        $this->balance = '';
        $this->gender = '';
    }

    public function isLogged() {
        return $this->customer_id;
    }

    public function isAdmin() {
        return $this->customer_id == 1;
    }

    public function getId() {
        return $this->customer_id;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getGroupId() {
        return $this->customer_group_id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getImage() {
        return $this->image;
    }

    public function getGenderId() {
        return $this->customer_gender_id;
    }

    public function getStatusId() {
        return $this->customer_status_id;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function getBlockedCash() {
        return $this->blocked_cash;
    }

    public function getPro() {
        return $this->pro;
    }

    public function getTimeZone() {
        return $this->timezone;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getLongPollKey() {

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_longpoll WHERE customer_id = '" . (int)$this->getId() . "' AND expired > '" . (time() + 1800)  . "' ORDER BY date_added DESC LIMIT 1");

        if (isset($result->row['key'])) {
            $key = $result->row['key'];
        } else {
            $key = Crypt::generate('sha1', md5($this->getLogin() . '$' . microtime(true))) . sha1(md5($this->getLogin() . '$' . microtime(true)));

            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_longpoll SET
                customer_id = '" . $this->getId() . "',
                `key` = '" . $key . "',
                expired = '" . (time() + 86400) . "',
                date_added = NOW()
           ");
        }

        return $key;
    }

    public function getRewardPoints() {
        $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

        return $query->row['total'];
    }

}
