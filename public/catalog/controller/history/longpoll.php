<?php

class ControllerHistoryLongpoll extends Controller
{

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $json['error'] = $this->language->get('text_access_denied');
            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('account/login'));
        } else {
            $key = $this->customer->getLongPollKey();
            $ts = floor(microtime(true) * 100);
            $json = [
                'key' => $key,
                'ts' => $ts,
                'server' => HTTPS_SERVER . 'longpoll.php?key=' . $key,
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
