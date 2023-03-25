<?php
class ControllerInformationContact extends Controller
{

    public function index()
    {
        $this->load->language('information/contact');

        $this->document->setTitle('Служба поддержки');
        $this->document->setDescription('Служба поддержки пользователей проекта ' . $this->config->get('config_name'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['is_auth'] = $this->customer->isLogged();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('information/contact', $data));
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

        /* Get Admin chat_id */
        // ...
        $chat_id = 1;

        /* end Get Admin chat_id */

        if (isset($this->request->post['attachment']) && $this->request->post['attachment']) {
            $attachments = $this->request->post['attachment'];
        } else {
            $attachments = array();
        }

        if (isset($this->request->post['subject']) && $this->request->post['subject']) {
            $subject = $this->request->post['subject'];
        } else {
            $subject = '';
        }

        if (!$json['error']) {
            $message_id = $this->model_message_chat->addMessage([
                "sender_id" => $this->customer->getId(),
                "recipient_id" => $chat_id,
                "text" => ($subject ? "Тема: " . $subject . " \n\r\n\r": '') . $this->request->post['text'],
                "attachment" => $attachments
            ]);

            $message_info = $this->model_message_chat->getMessageById($message_id);
            if ($message_info['image']) {
                $image = $this->model_tool_image->resize($message_info['image'], 80, 80);
            } else {
                $image = $this->model_tool_image->resize('profile.png', 80, 80);
            }

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

            $json['message'] = [
                "message_id" => $message_info['message_id'],
                "chat_id" => $message_info['chat_id'],
                "image" => $image,
                "name" => $message_info['name'],
                "text" => $message_info['text'],
                "online" => $message_info['online'],
                "viewed" => $message_info['viewed'],
                'date_added' => format_date($message_info['date_added'], $this->language->get('date_format_chat_full')),
                "attachment" => $attachment_data,
                "last_seen" => $this->model_tool_online->format($message_info['last_seen']),
                "href" => $this->url->link('account/customer', 'customer_id=' . $message_info['sender_id'])
            ];

            $json['redirect'] = $this->url->link('message/chat', 'chat_id=' . $chat_id);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


}
