<?php

class ControllerHistoryHistory extends Controller
{

    public function index()
    {
        $this->load->language('history/history');

        if ($this->customer->isLogged()) {

            if (isset($this->request->get['ts'])) {
                $ts = (int)$this->request->get['ts'];
            } else {
                $ts = 0;
            }

            $this->load->model('history/history');
            $json['history'] = $this->model_history_history->getAll(
                $this->customer->getId(),
                $ts
            );

            $this->load->model('message/chat');
            $this->load->model('tool/notification');
            $json['counter'] = [
                'messages' => $this->model_message_chat->getTotalUnreadMessages(
                    $this->customer->getId()
                ),
                'notifications' => $this->model_tool_notification->getTotalUnreadNotifications(
                    $this->customer->getId()
                ),
            ];
        } else {
            $json['error'] = 'forbidden';
            $json['code'] = 403;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}