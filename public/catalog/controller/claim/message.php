<?php

class ControllerClaimMessage extends Controller {

    private $error = array();

    public function index()
    {
        if (isset($this->request->get['claim_id'])) {
            $claim_id = (int)$this->request->get['claim_id'];
        } else {
            $claim_id = 0;
        }

        $this->load->model('claim/message');
        $this->load->model('tool/image');

        $result = $this->model_claim_message->getMessages($claim_id);

        $data['messages'] = array();

        if ($result) {
            $result = array_reverse($result);

            foreach ($result as $message) {
                if ($message['image']) {
                    $image = $this->model_tool_image->resize($message['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $data['messages'][] = [
                    'claim_message_id' => $message['claim_message_id'],
                    'claim_id' => $message['claim_id'],
                    "text" => nl2br(htmlspecialchars_decode($message['text'])),
                    'date_added' => format_date($message['date_added'], 'd.m.Y H:i'),
                    'image' => $image,
                    'login' => $message['login'],
                    'href' => $this->url->link('account/customer', 'customer_id=' . $message['customer_id'])
                ];
            }
        }

        $this->response->setOutput($this->load->view('claim/message', $data));
    }

    public function live()
    {
        if (isset($this->request->post['claim_id'])) {
            $claim_id = (int)$this->request->post['claim_id'];
        } else {
            $claim_id = 0;
        }

        if (isset($this->request->post['last_claim_message_id'])) {
            $last_claim_message_id = (int)$this->request->post['last_claim_message_id'];
        } else {
            $last_claim_message_id = 0;
        }

        $json['claim_id'] = $claim_id;
        $json['last_claim_message_id'] = $last_claim_message_id;

        $this->load->model('claim/message');
        $this->load->model('tool/image');

        $result = $this->model_claim_message->getMessages($claim_id, $last_claim_message_id);

        $json['messages'] = array();

        if ($result) {
            $result = array_reverse($result);

            foreach ($result as $message) {
                if ($message['image']) {
                    $image = $this->model_tool_image->resize($message['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $json['messages'][] = [
                    'claim_message_id' => $message['claim_message_id'],
                    'claim_id' => $message['claim_id'],
                    "text" => nl2br(htmlspecialchars_decode($message['text'])),
                    'date_added' => format_date($message['date_added'], 'd.m.Y H:i'),
                    'image' => $image,
                    'login' => $message['login'],
                    'href' => $this->url->link('account/customer', 'customer_id=' . $message['customer_id'])
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function send()
    {
        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('account/customer');
        $this->load->model('claim/message');
        if (!$this->customer->isLogged()) {
            $json['error_auth'] = $this->language->get('error_auth');
        } else {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $claim_message_id = $this->model_claim_message->addMessage($this->request->post);

                $claim_id = $this->request->post['claim_id'];

                $claim_info = $this->model_claim_claim->getClaim($this->request->post['claim_id']);

                $customer_info = $this->model_account_customer->getCustomerInfo($claim_info['customer_id']);
                $defendant_info = $this->model_account_customer->getCustomerInfo($claim_info['defendant_id']);

                $claim_title = "Претензия №" . $claim_id;
                // Уведомление order owner
                (new \Model\Notification($this->db))
                    ->set(
                        $customer_info['customer_id'],
                        'order',
                        sprintf(
                            $this->language->get('notification_message_claim'),
                            $this->url->link('claim/claim/info', 'claim_id=' . $claim_info['claim_id']),
                            $claim_title
                        )
                    );

                // Уведомление order owner
                (new \Model\Notification($this->db))
                    ->set(
                        $defendant_info['customer_id'],
                        'order',
                        sprintf(
                            $this->language->get('notification_message_claim'),
                            $this->url->link('claim/claim/info', 'claim_id=' . $claim_info['claim_id']),
                            $claim_title
                        )
                    );


                $json['claim_message_id'] = $claim_message_id;
                $json['success'] = $this->language->get('text_success');
            } else {
                if (isset($this->error['offer'])) {
                    $json['error_offer'] = $this->error['offer'];
                }
                if (isset($this->error['text'])) {
                    $json['error_text'] = $this->error['text'];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate()
    {
        if (!isset($this->request->post['claim_id']) || $this->request->post['claim_id'] < 1) {
            $this->error['claim'] = $this->language->get('error_claim');
        }

        $claim_info = $this->model_claim_claim->getClaim($this->request->post['claim_id']);
        if (!$claim_info) {
            $this->error['claim'] = $this->language->get('error_claim');
        }

        if ($claim_info['customer_id'] != $this->customer->getId() && $claim_info['defendant_id'] != $this->customer->getId()) {
            $this->error['access_denied'] = $this->language->get('error_access_denied');
        }

        if (!isset($this->request->post['text']) || strlen($this->request->post['text']) < 1) {
            $this->error['text'] = $this->language->get('error_text');
        }

        if (!$this->error) {
            return true;
        }
        return false;
    }
}