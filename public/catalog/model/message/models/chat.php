<?php

class ModelMessageModelsChat extends Model {

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('message/chat');
    }

    public function getChats($customer_id, $start = 0, $limit = 20, $last_message_id = 0)
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $select = "SELECT MAX(m1.message_id) AS message_id, MAX(m1.date_added) AS date_added, IF(m1.sender_id = '" . (int)$customer_id . "', m1.recipient_id, m1.sender_id) as chat_id FROM `" . DB_PREFIX . "message` m1 WHERE ((m1.sender_id='" . (int)$customer_id . "' AND m1.recipient_id <> " . (int)$customer_id . ") OR (m1.sender_id <> " . (int)$customer_id . " AND m1.recipient_id = " . (int)$customer_id . "))";

        $select .= "GROUP BY chat_id ORDER BY m1.message_id DESC";

        $sql = "SELECT m.chat_id,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m7 WHERE m7.sender_id = m.chat_id AND m7.recipient_id = '" . (int)$customer_id . "' AND m7.viewed = 0) as unviewed,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m8 WHERE m8.recipient_id = m.chat_id AND m8.sender_id = '" . (int)$customer_id . "' AND m8.viewed = 0) as unread,
        m2.* FROM (" . $select . ") m INNER JOIN `" . DB_PREFIX . "message` m2 ON m.message_id = m2.message_id";

        if ($last_message_id) {
            $sql .= " AND m.message_id > '" . (int)$last_message_id ."'";
        }
        $sql .= " ORDER BY m.message_id DESC LIMIT " . $start . "," . $limit;

        $query = $this->db->query($sql);

        $chat_data = array();

        if ($query->rows) {
            foreach ($query->rows as $row) {
                if (!$row['chat_id']) continue;

                $customer_info = $this->model_account_customer->getCustomerInfo($row['chat_id']);
                if (!$customer_info) continue;

                if (isset($customer_info['image']) && $customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $chat_data[] = [
                    "chat_id" => $row['chat_id'],
                    "last_message_id" => $row['message_id'],
                    "is_sender" => $customer_id == (int)$row['sender_id'],
                    "sender_id" => $row['sender_id'],
                    "name" => $customer_info['login'],
                    "image" => $image,
                    "online" => $customer_info['online'],
                    "last_seen" => $customer_info['last_seen'],
                    "text" => ($customer_id == (int)$row['sender_id'] ? 'Вы: ': '') . $row['text'],
                    "unread" => (int)$row['unread'],
                    "unviewed" => (int)$row['unviewed'],
                    "viewed" => (int)$row['viewed'],
                    "date_added" => format_date($row['date_added'], 'd.m.Y'),
                ];
            }
        }

        return $chat_data;
    }

    public function searchChats($customer_id, $search, $unviewed = 0, $start = 0, $limit = 20)
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $select = "SELECT MAX(m1.message_id) AS message_id, MAX(m1.date_added) AS date_added, IF(m1.sender_id = '" . (int)$customer_id . "', m1.recipient_id, m1.sender_id) as chat_id FROM `" . DB_PREFIX . "message` m1 WHERE ((m1.sender_id='" . (int)$customer_id . "' AND m1.recipient_id <> " . (int)$customer_id . ") OR (m1.sender_id <> " . (int)$customer_id . " AND m1.recipient_id = " . (int)$customer_id . "))";

        $select .= "GROUP BY chat_id ORDER BY m1.message_id DESC";

        $sql = "SELECT m.chat_id,
        (SELECT login FROM " . DB_PREFIX . "customer c2 WHERE c2.customer_id = m.chat_id AND c2.status = 1 LIMIT 1) AS `name`,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m7 WHERE m7.sender_id = m.chat_id AND m7.recipient_id = '" . (int)$customer_id . "' AND m7.viewed = 0) as unviewed,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m8 WHERE m8.recipient_id = m.chat_id AND m8.sender_id = '" . (int)$customer_id . "' AND m8.viewed = 0) as unread,
        m2.* FROM (" . $select . ") m INNER JOIN `" . DB_PREFIX . "message` m2 ON m.message_id = m2.message_id WHERE m.chat_id > 0";

        if ($search != '') {
            $sql .= "  AND (SELECT login FROM " . DB_PREFIX . "customer c2 WHERE c2.customer_id = m.chat_id AND c2.status = 1 LIMIT 1) LIKE '%" . $this->db->escape($search) . "%'";
        }
        if ($unviewed == 1) {
            $sql .= " AND (SELECT COUNT(1) FROM " . DB_PREFIX . "message m8 WHERE m8.sender_id = m.chat_id AND m8.recipient_id = '" . (int)$customer_id . "' AND m8.viewed = 0) > 0";
        }

        $sql .= " ORDER BY m.message_id DESC LIMIT " . $start . "," . $limit;

        $query = $this->db->query($sql);

        $chat_data = array();

        if ($query->rows || $unviewed == 1) {
            foreach ($query->rows as $row) {
                if (!$row['chat_id']) continue;

                $customer_info = $this->model_account_customer->getCustomerInfo($row['chat_id']);

                if ($customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $chat_data[] = [
                    "chat_id" => (int)$row['chat_id'],
                    "last_message_id" => (int)$row['message_id'],
                    "is_sender" => $customer_id == (int)$row['sender_id'],
                    "sender_id" => (int)$row['sender_id'],
                    "name" => $customer_info['login'],
                    "image" => $image,
                    "online" => $customer_info['online'],
                    "last_seen" => (int)$customer_info['last_seen'],
                    "text" => ($customer_id == (int)$row['sender_id'] ? 'Вы: ': '') . $row['text'],
                    "unread" => (int)$row['unread'],
                    "unviewed" => (int)$row['unviewed'],
                    "viewed" => (int)$row['viewed'],
                    "date_added" => format_date($row['date_added'], 'd.m.Y'),
                ];
            }
        } else {
            $results = $this->model_account_customer->searchCustomers([
                'search' => $search,
                'start' => 0,
                'limit' => 20,
            ]);

            if ($results) {
                foreach ($results as $result) {
                    $chat_info = $this->getChatsInfo($customer_id, [
                        $result['customer_id']
                    ]);
                    if (isset($chat_info[0])) {
                        $chat_data[] = $chat_info[0];
                    }
                }
            }
        }

        return $chat_data;
    }

    public function getChatsInfo($customer_id, $list_ids = [])
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $select = "SELECT MAX(m1.message_id) AS message_id, MAX(m1.date_added) AS date_added, IF(m1.sender_id = '" . (int)$customer_id . "', m1.recipient_id, m1.sender_id) as chat_id FROM `" . DB_PREFIX . "message` m1 WHERE ((m1.sender_id='" . (int)$customer_id . "' AND m1.recipient_id <> " . (int)$customer_id . ") OR (m1.sender_id <> " . (int)$customer_id . " AND m1.recipient_id = " . (int)$customer_id . "))";

        $select .= "GROUP BY chat_id ORDER BY m1.message_id DESC";

        $sql = "SELECT m.chat_id,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m7 WHERE m7.sender_id = m.chat_id AND m7.recipient_id = '" . (int)$customer_id . "' AND m7.viewed = 0) as unviewed,
        (SELECT COUNT(1) FROM " . DB_PREFIX . "message m8 WHERE m8.recipient_id = m.chat_id AND m8.sender_id = '" . (int)$customer_id . "' AND m8.viewed = 0) as unread,
        m2.* FROM (" . $select . ") m INNER JOIN `" . DB_PREFIX . "message` m2 ON m.message_id = m2.message_id ";

        if ($list_ids) {
            $sql .= " WHERE m.chat_id IN (" . implode(',', $list_ids) . ")";
        }

        $sql .= " ORDER BY m.message_id DESC";

        $query = $this->db->query($sql);

        $chat_data = array();

        if ($query->rows) {
            foreach ($query->rows as $row) {
                if (!$row['chat_id']) continue;

                $customer_info = $this->model_account_customer->getCustomerInfo($row['chat_id']);

                if ($customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $chat_data[] = [
                    "chat_id" => (int)$row['chat_id'],
                    "last_message_id" => (int)$row['message_id'],
                    "is_sender" => $customer_id == (int)$row['sender_id'],
                    "sender_id" => (int)$row['sender_id'],
                    "name" => $customer_info['login'],
                    "image" => $image,
                    "online" => (int)$customer_info['online'],
                    "last_seen" => (int)$customer_info['last_seen'],
                    "text" => ($customer_id == (int)$row['sender_id'] ? 'Вы: ': '') . $row['text'],
                    "unread" => (int)$row['unread'],
                    "unviewed" => (int)$row['unviewed'],
                    "viewed" => (int)$row['viewed'],
                    "date_added" => format_date($row['date_added'], 'd.m.Y'),
                ];
            }
        } else {
            foreach ($list_ids as $list_id) {
                $chat_info = $this->getChat($customer_id, $list_id);
                if ($chat_info) {
                    $chat_data[] = $chat_info;
                }
            }
        }

        return $chat_data;
    }

    public function getChat($customer_id, $chat_id)
    {

        $chat_data = array();
        if ($chat_id) {
            $this->load->model('account/customer');
            $this->load->model('tool/image');

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "message` WHERE (sender_id='" . (int)$customer_id . "' AND recipient_id = " . (int)$chat_id . ") OR (sender_id = " . (int)$chat_id . " AND recipient_id = " . (int)$customer_id . ") GROUP BY sender_id, recipient_id ORDER BY date_added DESC");

            if ($query->row) {
                $customer_info = $this->model_account_customer->getCustomerInfo($chat_id);

                if ($customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $chat_data = [
                    "chat_id" => (int)$chat_id,
                    "is_sender" => $customer_id == (int)$query->row['sender_id'],
                    "sender_id" => (int)$query->row['sender_id'],
                    "name" => $customer_info['login'],
                    "image" => $image,
                    "online" => (int)$customer_info['online'],
                    "last_seen" => (int)$customer_info['last_seen'],
                    "text" => ($customer_id == (int)$query->row['sender_id'] ? 'Вы: ': '') . $query->row['text'],
                    "unviewed" => (isset($query->row['unviewed']) ? (int)$query->row['unviewed']: 0),
                    "unread" => (isset($query->row['unread']) ? (int)$query->row['unread']: 0),
                    "viewed" => (int)$query->row['viewed'],
                    "date_added" => format_date($query->row['date_added'], 'd.m.Y'),
                ];
            } else {
                $customer_info = $this->model_account_customer->getCustomerInfo($chat_id);

                if ($customer_info) {

                    if ($customer_info['image']) {
                        $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                    } else {
                        $image = $this->model_tool_image->resize('profile.png', 80, 80);
                    }

                    $chat_data = [
                        "chat_id" => (int)$chat_id,
                        "sender_id" => 0,
                        "name" => $customer_info['login'],
                        "image" => $image,
                        "online" => (int)$customer_info['online'],
                        "last_seen" => (int)$customer_info['last_seen'],
                        "text" => '',
                        "unviewed" => 0,
                        "unread" => 0,
                        "viewed" => 0,
                        "date_added" => date('d.m.Y'),
                    ];
                }
            }
        }



        return $chat_data;
    }

    public function viewedChat($recipient_id, $chat_id)
    {
        $results = $this->db->query("SELECT message_id FROM " . DB_PREFIX . "message WHERE recipient_id = '" . (int)$recipient_id . "' AND sender_id = '" . (int)$chat_id . "' AND viewed = 0");

        $this->db->query("UPDATE " . DB_PREFIX . "message SET viewed = 1 WHERE recipient_id = '" . (int)$recipient_id . "' AND sender_id = '" . (int)$chat_id . "'");

        if ($results->rows) {
            $this->load->model('history/history');
            foreach ($results->rows as $row) {
                $this->longpoll->register(
                    $chat_id,
                    'message_read',
                    [
                        'message_id' => $row['message_id']
                    ]
                );
                $this->longpoll->register(
                    $recipient_id,
                    'message_read',
                    [
                        'message_id' => $row['message_id']
                    ]
                );
            }
        }

        $this->longpoll->register(
            $chat_id,
            'chat_viewed',
            [
                'chat_id' => $recipient_id
            ]
        );

        $this->longpoll->register(
            $recipient_id,
            'chat_read',
            [
                'chat_id' => $chat_id
            ]
        );
    }

}
