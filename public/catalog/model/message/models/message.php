<?php

class ModelMessageModelsMessage extends Model {

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('message/chat');
    }

    public function getMessages($customer_id, $chat_id, $start = 0, $limit = 20, $last_message_id = 0)
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $sql = "SELECT * FROM `" . DB_PREFIX . "message` WHERE ((sender_id='" . (int)$customer_id . "' AND recipient_id = '" . (int)$chat_id . "') OR (sender_id = '" . (int)$chat_id . "' AND recipient_id = '" . (int)$customer_id . "'))";

        if ($last_message_id) {
            $sql .= " AND message_id > '" . (int)$last_message_id . "'";
        }

        $sql .= " ORDER BY message_id DESC";

        $sql .= " LIMIT " . $start . "," . $limit;

        $query = $this->db->query($sql);

        $message_data = array();

        if ($query->rows) {
            foreach ($query->rows as $row) {
                if ($row['sender_id'] == $customer_id) {
                    $chats[] = $row['recipient_id'];
                    $chat_id = $row['recipient_id'];
                } elseif ($row['recipient_id'] == $customer_id) {
                    $chats[] = $row['sender_id'];
                    $chat_id = $row['sender_id'];
                } else {
                    continue;
                }

                $customer_info = $this->model_account_customer->getCustomerInfo($row['sender_id']);

                if ($customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $message_data[] = [
                    "message_id" => $row['message_id'],
                    "chat_id" => $chat_id,
                    "sender_id" => $row['sender_id'],
                    "name" => $customer_info['login'],
                    "image" => $image,
                    "online" => $customer_info['online'],
                    "last_seen" => $customer_info['last_seen'],
                    "text" => $row['text'],
                    "viewed" => $row['viewed'],
                    "date_added" => $row['date_added'],
                ];
            }
        }

        return $message_data;
    }

    public function getTotalMessages($customer_id, $chat_id) : int
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "message` WHERE ((sender_id='" . (int)$customer_id . "' AND recipient_id = '" . (int)$chat_id . "') OR (sender_id = '" . (int)$chat_id . "' AND recipient_id = '" . (int)$customer_id . "'))";

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
            return (int)$query->row['total'];
        }

        return 0;
    }

    public function getViewedMessages($customer_id, $chat_id)
    {
        $this->load->model('account/customer');

        $sql = "SELECT * FROM `" . DB_PREFIX . "message` WHERE (sender_id='" . (int)$customer_id . "' AND recipient_id = '" . (int)$chat_id . "') AND viewed = 0";

        $sql .= " ORDER BY message_id DESC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUnreadMessages($customer_id)
    {
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "message` WHERE recipient_id = '" . (int)$customer_id . "' AND viewed = 0";

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
            return (int)$query->row['total'];
        }

        return 0;
    }

    public function getMessageById($message_id)
    {
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "message` WHERE message_id='" . (int)$message_id . "'");

        $message_data = array();

        if ($query->row) {
            if ($query->row['sender_id'] == $this->customer->getId()) {
                $chat_id = $query->row['recipient_id'];
            } elseif ($query->row['recipient_id'] == $this->customer->getId()) {
                $chat_id = $query->row['sender_id'];
            } else {
                return $message_data;
            }

            $customer_info = $this->model_account_customer->getCustomerInfo($query->row['sender_id']);

            if ($customer_info['image']) {
                $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
            } else {
                $image = $this->model_tool_image->resize('profile.png', 80, 80);
            }

            $message_data = [
                "message_id" => $query->row['message_id'],
                "chat_id" => $chat_id,
                "sender_id" => $query->row['sender_id'],
                "recipient_id" => $query->row['recipient_id'],
                "name" => $customer_info['login'],
                "image" => $image,
                "online" => $customer_info['online'],
                "last_seen" => $customer_info['last_seen'],
                "text" => $query->row['text'],
                "viewed" => $query->row['viewed'],
                "date_added" => $query->row['date_added'],
            ];
        }

        return $message_data;
    }

    public function addMessage($data)
    {
        $query = "INSERT INTO " . DB_PREFIX . "message SET 
                        sender_id = '" . (int)$data['sender_id'] . "', 
                        recipient_id = '" . (int)$data['recipient_id'] . "', 
                        text = '" . $this->db->escape($data['text']) . "', 
                        viewed = 0,
                        date_added = NOW()";

        $this->db->query($query);

        $message_id = $this->db->getLastId();

        if (isset($data['attachment'])) {
            foreach ($data['attachment'] as $attachment_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "message_attachment  SET message_id = '" . (int)$message_id . "', attachment_id = '" . (int)$attachment_id . "'");
            }
        }

        $message_info = $this->getMessageById($message_id);

        $this->load->model('history/history');

        $attachments = $this->getMessageAttachment($message_id);

        $attachment_data = array();
        if ($attachments) {
            foreach ($attachments as $attachment) {
                $attachment_data[] = [
                    'type' => $attachment['type'],
                    'name' => $attachment['name'],
                    'size' => format_size($attachment['size']),
                    'date_added' => format_date($attachment['date_added'], $this->language->get('date_format_chat_full')),
                    'href' => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                    'upload' => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                ];
            }
        }

        $message_info['attachment'] = $attachment_data;
        // Форматируем дату
        $message_info['date_added'] = format_date($message_info['date_added'], $this->language->get('date_format_chat_full'));

        /* Регистрация событий в LongPoll нового сообщения и обновления чата для получателя */
        $message_info['chat_id'] = $data['sender_id']; // Подставляем нужный Chat_id
        $message_info['is_sender'] = 0; // Подставляем флаг отправителя is_sender в 0

        $this->longpoll->register(
            $data['recipient_id'],
            'message_new',
            $message_info
        );

        // Обновления чата в списке у Получателя
        $recipient_chat_info = $this->model_message_models_chat->getChatsInfo($data['recipient_id'], [
            $data['sender_id']
        ]);

        $recipient_info = (new \Model\Customer($this->db))->get($data['recipient_id']);

        if ($recipient_info['setting_email_notify']) {
            $this->load->model('setting/setting');

            $data['comment'] = '';
            $data['message'] = $this->language->get('notification_new_message');
            $data['link'] = $this->url->link('message/chat');

            // Unsubscribe generate
            $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($recipient_info['email']);
            $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

            $this->taskManager->set([
                'channel' => 'emails',
                'type' => 'email_send',
                'time_exec' => time(),
                'object' => [
                    'to' => $recipient_info['email'],
                    'subject' => sprintf($this->language->get('text_subject_new_message'), $this->config->get('config_name')),
                    'message' => $this->load->view('mail/new_message', $data)
                ]
            ]);
        }

        if (isset($recipient_chat_info[0])) {
            $recipient_chat_info = $recipient_chat_info[0];
        }

        $recipient_chat_info['date_added'] = format_date($recipient_chat_info['date_added']);

        $this->longpoll->register(
            $data['recipient_id'],
            'chat_update',
            $recipient_chat_info
        );

        /* Регистрация событий в LongPoll нового сообщения и обновления чата для Отправителя */
        $message_info['chat_id'] = $data['recipient_id']; // Подставляем нужный Chat_id
        $message_info['is_sender'] = 1; // Подставляем флаг отправителя is_sender в 0

        $this->longpoll->register(
            $data['sender_id'],
            'message_new',
            $message_info
        );

        // Обновления чата в списке у Отправителя
        $sender_chat_info = $this->model_message_models_chat->getChatsInfo($data['sender_id'], [
            $data['recipient_id']
        ]);

        if (isset($sender_chat_info[0])) {
            $sender_chat_info = $sender_chat_info[0];
        }

        $sender_chat_info['date_added'] = format_date($sender_chat_info['date_added']);

        $this->longpoll->register(
            $data['sender_id'],
            'chat_update',
            $sender_chat_info
        );

        return $message_id;
    }

    public function getMessageAttachment($message_id)
    {
        $result = $this->db->query("SELECT a.* FROM " . DB_PREFIX . "message_attachment ma LEFT JOIN " . DB_PREFIX . "attachment a ON ma.attachment_id = a.attachment_id WHERE ma.message_id = '" . (int)$message_id . "'");
        return $result->rows;
    }

    public function viewedMessage($recipient_id, $message_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "message SET viewed = 1 WHERE recipient_id = '" . (int)$recipient_id . "' AND message_id = '" . (int)$message_id . "'");

        $message_info = $this->getMessageById($message_id);

        $this->load->model('history/history');
        $this->longpoll->register(
            $message_info['sender_id'],
            'message_read',
            [
                'message_id' => $message_id
            ]
        );

        $chat_info = $this->model_message_models_chat->getChatsInfo($recipient_id, [$message_info['sender_id']]);

        $chat_info = $chat_info[0];
        if ($chat_info['unviewed'] == 0) {
            $this->longpoll->register(
                $recipient_id,
                'chat_read',
                [
                    'chat_id' => $chat_info['chat_id']
                ]
            );
        }
    }


}