<?php
class ControllerMessageChat extends Controller {

    protected $limitMessage = 20;
    protected $limitChats = 20;

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('message/chat');

        $this->load->model('message/chat');
        $this->load->model('tool/image');

        if (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('../catalog/assets/js/message.js', 'footer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('message/chat')
        );

        $data['chat'] = $this->getChatContent();
        $data['chat_list'] = $this->getChats();

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('message/chat', $data));

    }

    public function chat()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('message/chat');

        $this->load->model('message/chat');
        $this->load->model('tool/image');

        $this->response->setOutput($this->getChatContent());
    }

    public function live()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('message/chat');
        $this->load->model('message/chat');
        $this->load->model('tool/image');
        $this->load->model('tool/online');

        if (isset($this->request->post['chat_id'])) {
            $chat_id = (int)$this->request->post['chat_id'];
        } elseif (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $limit = $this->limitMessage;

        if (isset($this->request->post['page'])) {
            $start = ((int)$this->request->post['page'] - 1) * $limit;
        } elseif (isset($this->request->get['page'])) {
            $start = ((int)$this->request->get['page'] - 1) * $limit;
        } else {
            $start = 0;
        }

        if (isset($this->request->post['last_message_id'])) {
            $last_message_id = (int)$this->request->post['last_message_id'];
        } elseif (isset($this->request->get['last_message_id'])) {
            $last_message_id = (int)$this->request->get['last_message_id'];
        } else {
            $last_message_id = 0;
        }

        $json['messages'] = array();

        $messages = $this->model_message_chat->getMessages($this->customer->getId(), $chat_id, 0, 20, $last_message_id);
        $messages = array_reverse($messages);

        foreach ($messages as $message) {
            if ($message['image']) {
                $image = $this->model_tool_image->resize($message['image'], 80, 80);
            } else {
                $image = $this->model_tool_image->resize('profile.png', 80, 80);
            }

            $attachments = $this->model_message_chat->getMessageAttachment($message['message_id']);

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

            $json['messages'][] = [
                "message_id" => $message['message_id'],
                "chat_id" => $message['chat_id'],
                "is_sender" => $message['sender_id'] == $this->customer->getId(),
                "image" => $image,
                "name" => $message['name'],
                "text" => $text,
                "online" => $message['online'],
                "viewed" => $message['viewed'],
                'date_added' => format_date($message['date_added'], $this->language->get('date_format_chat_full')),
                "attachment" => $attachment_data,
                "last_seen" => $this->model_tool_online->format($message['last_seen']),
                "href" => $this->url->link('account/customer', 'customer_id=' . $message['sender_id'])
            ];
        }

        $json['chats'] = array();

        $chats = $this->model_message_chat->getChats($this->customer->getId(), $start, $limit, $last_message_id);
        $chats = array_reverse($chats);

        if ($chats) {
            $this->load->model('tool/image');

            foreach ($chats as $chat) {
                if ($chat['image']) {
                    $image = $this->model_tool_image->resize($chat['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $json['chats'][] = [
                    "chat_id" => $chat['chat_id'],
                    "last_message_id" => $chat['last_message_id'],
                    "active" => ($chat['chat_id'] == $chat_id),
                    "name" => $chat['name'],
                    "online" => $chat['online'],
                    "viewed" => $chat['viewed'],
                    "unviewed" => $chat['unviewed'],
                    "image" => $image,
                    "text" => ($chat['sender_id'] == $this->customer->getId() ? sprintf($this->language->get('text_owner_message'), $chat['text']): $chat['text']),
                    "date_added" => format_date($chat['date_added'], $this->language->get('date_format_chat')),
                    "href" => $this->url->link('message/chat', 'chat_id=' . $chat['chat_id'])
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function send()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('message/chat');

        $this->load->model('message/chat');
        $this->load->model('tool/image');
        $this->load->model('tool/online');

        $json['error'] = array();

        if (!isset($this->request->post['text']) || $this->request->post['text'] == '') {
            if (!isset($this->request->post['attachment']) ||  count($this->request->post['attachment']) < 1) {
                $json['error']['text'] = $this->language->get('error_text');
            }
        }

        if (isset($this->request->get['chat_id']) && $this->request->get['chat_id'] > 0) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        if (!$chat_id) {
            $json['error']['warning'] = $this->language->get('error_warning');
        }

        if (isset($this->request->post['attachment']) && $this->request->post['attachment']) {
            $attachments = $this->request->post['attachment'];
        } else {
            $attachments = array();
        }

        if (!$json['error']) {
            $message_id = $this->model_message_chat->addMessage([
                "sender_id" => $this->customer->getId(),
                "recipient_id" => $chat_id,
                "text" => $this->request->post['text'],
                "attachment" => $attachments
            ]);

            // Viewed chat
            $this->model_message_chat->viewedChat($this->customer->getId(), $chat_id);

            $message_info = $this->model_message_chat->getMessageById($message_id);

            $attachments = $this->model_message_chat->getMessageAttachment($message_info['message_id']);

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
            $text = nl2br($this->observeText(htmlspecialchars_decode($message_info['text'])));
            $json['message'] = [
                "message_id" => $message_info['message_id'],
                "chat_id" => $message_info['chat_id'],
                "is_sender" => $message_info['sender_id'] == $this->customer->getId(),
                "image" => $message_info['image'],
                "name" => $message_info['name'],
                "text" => $text,
                "online" => $message_info['online'],
                "viewed" => $message_info['viewed'],
                'date_added' => format_date($message_info['date_added'], $this->language->get('date_format_chat_full')),
                "attachment" => $attachment_data,
                "last_seen" => $this->model_tool_online->format($message_info['last_seen']),
                "href" => $this->url->link('account/customer', 'customer_id=' . $message_info['sender_id'])
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function viewedMessage()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['message_id'])) {
            $message_id = (int)$this->request->get['message_id'];
        } else {
            $message_id = 0;
        }

        $this->load->language('message/chat');

        $this->load->model('message/chat');
        $this->load->model('tool/image');
        $this->load->model('tool/online');

        $json = [];

        $message_info = $this->model_message_chat->getMessageById($message_id);

        if ($message_info) {
            if ($message_info['recipient_id'] == $this->customer->getId()) {
                $this->model_message_chat->viewedMessage($this->customer->getId(), $message_id);
                $this->model_message_chat->viewedChat($this->customer->getId(), $message_info['chat_id']);
                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function viewedChat()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $this->load->language('message/chat');

        $this->load->model('message/chat');

        $json = [];

        $this->model_message_chat->viewedChat($this->customer->getId(), $chat_id);
        $json['success'] = $this->language->get('text_success');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function searchChats()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('message/chat', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->post['search'])) {
            $search = $this->request->post['search'];
        } elseif (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->post['unviewed'])) {
            $unviewed = $this->request->post['unviewed'];
        } elseif (isset($this->request->get['unviewed'])) {
            $unviewed = $this->request->get['unviewed'];
        } else {
            $unviewed = 0;
        }

        $limit = $this->limitChats;

        if (isset($this->request->get['page']) && $this->request->get['page'] > 0) {
            $start = ((int)$this->request->get['page'] - 1) * $limit;
            $page = (int)$this->request->get['page'];
        } elseif (isset($this->request->post['page']) && $this->request->post['page'] > 0) {
            $start = ((int)$this->request->post['page'] - 1) * $limit;
            $page = (int)$this->request->post['page'];
        } else {
            $start = 0;
            $page = 1;
        }

        $this->load->model('message/models/chat');
        if ($search || $unviewed == 1) {
            $results = $this->model_message_models_chat->searchChats(
                $this->customer->getId(),
                $search,
                $unviewed,
                $start,
                $limit
            );
            $resultsTotal = $this->model_message_models_chat->searchChatsTotal(
                $this->customer->getId(),
                $search,
                $unviewed,
                $start,
                $limit
            );
        } else {
            $results = $this->model_message_models_chat->getChats(
                $this->customer->getId(),
                $start,
                $limit
            );
            $resultsTotal = $this->model_message_models_chat->getChatsTotal(
                $this->customer->getId(),
                $start,
                $limit
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'chats' => $results,
            'total' => $resultsTotal,
            'page' => $page,
            'limit' => $limit,
        ]));
    }

    private function getChats()
    {
        if (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $limit = $this->limitChats;

        if (isset($this->request->get['page'])) {
            $start = ((int)$this->request->get['page'] - 1) * $limit;
        } else {
            $start = 0;
        }

        if (isset($this->request->get['last_message_id'])) {
            $last_message_id = (int)$this->request->get['last_message_id'];
        } else {
            $last_message_id = 0;
        }


        $data['chats'] = array();
        $chats = $this->model_message_chat->getChats($this->customer->getId(), $start, $limit, $last_message_id);

        if ($chats) {
            $this->load->model('tool/image');

            foreach ($chats as $chat) {
                $data['chats'][] = [
                    "chat_id" => $chat['chat_id'],
                    "last_message_id" => $chat['last_message_id'],
                    "active" => ($chat['chat_id'] == $chat_id),
                    "name" => $chat['name'],
                    "online" => $chat['online'],
                    "unread" => $chat['unread'],
                    "unviewed" => $chat['unviewed'],
                    "viewed" => $chat['viewed'],
                    "image" => $chat['image'],
                    "text" => $chat['text'],
                    "date_added" => format_date($chat['date_added'], $this->language->get('date_format_chat')),
                    "href" => $this->url->link('message/chat', 'chat_id=' . $chat['chat_id'])
                ];
            }
        }

        return $this->load->view('message/chat_list', $data);
    }

    private function getMessages()
    {
        if (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $limit = $this->limitMessage;

        if (isset($this->request->post['page'])) {
            $page = (int)$this->request->post['page'];
        } elseif (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $start = ($page - 1) * $limit;

        $data['messages'] = array();

        $data['total_messages'] = $this->model_message_chat->getTotalMessages($this->customer->getId(), $chat_id);
        $data['limit'] = $start + $limit;
        $data['page'] = $page + 1;
        $data['chat_id'] = $chat_id;

        $messages = $this->model_message_chat->getMessages($this->customer->getId(), $chat_id, $start, $limit);
        $messages = array_reverse($messages);

        foreach ($messages as $message) {

            $attachments = $this->model_message_chat->getMessageAttachment($message['message_id']);

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


            $text = nl2br($this->observeText(htmlspecialchars_decode($message['text'])));

            $data['messages'][] = [
                "message_id" => $message['message_id'],
                "chat_id" => $message['chat_id'],
                "is_sender" => $message['sender_id'] == $this->customer->getId(),
                "image" => $message['image'],
                "name" => $message['name'],
                "text" => $text,
                "online" => $message['online'],
                "viewed" => $message['viewed'],
                'date_added' => format_date($message['date_added'], $this->language->get('date_format_chat_full')),
                "attachment" => $attachment_data,
                "last_seen" => $this->model_tool_online->format($message['last_seen']),
                "href" => $this->url->link('account/customer', 'customer_id=' . $message['sender_id'])
            ];
        }


        return $this->load->view('message/message_list', $data);
    }

    private function getChatContent()
    {
        $this->load->model('tool/online');

        if (isset($this->request->get['chat_id'])) {
            $chat_id = (int)$this->request->get['chat_id'];
        } else {
            $chat_id = 0;
        }

        $data['chat_info'] = array();
        $data['message_list'] = array();

        if ($chat_id) {
            $chat_info = $this->model_message_chat->getChatsInfo($this->customer->getId(), [$chat_id]);

            $data['chat_info'] = array();
            if ($chat_info) {

                $chat_info = $chat_info[0];

                $data['chat_info'] = [
                    "chat_id" => $chat_info['chat_id'],
                    "image" => $chat_info['image'],
                    "name" => $chat_info['name'],
                    "online" => $chat_info['online'],
                    "unread" => $chat_info['unread'],
                    "unviewed" => $chat_info['unviewed'],
                    "last_seen" => $this->model_tool_online->format($chat_info['last_seen']),
                    "href" => $this->url->link('account/customer', 'customer_id=' . $chat_info['chat_id'])
                ];

                $this->model_message_chat->viewedChat($this->customer->getId(), $chat_info['chat_id']);
            }


            $data['message_list'] = $this->getMessages();
        }


        return $this->load->view('message/chat_content', $data);
    }

    private function observeText($text)
    {
        preg_match_all("~[a-z]+://\S+~", $text, $urls);

        if ($urls) {
            if ($urls[0]) {

                $search = [];
                $replace = [];
                foreach ($urls[0] as $item) {
                    if ($item) {
                        $str = '<a href="' . $item . '" target="_blank">' . $item . '</a>';
                        if (!in_array($item, $search)) {
                            $search[] = $item;
                            $replace[] = $str;
                        }
                    }
                }

                $text = str_replace($search, $replace, $text);
            }
        }

        return $text;
    }

}
