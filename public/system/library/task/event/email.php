<?php

namespace Task\Event;

use Task\TaskItem;

class Email {

    private $config;
    private $db;

    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;
        $this->log = \Log('error.log');
    }

    public function send($object)
    {
        if (empty($object->to)) {
            return ['error' => 'Empty email'];
        }
        if (empty($object->subject)) {
            return ['error' => 'Empty subject'];
        }
        if (empty($object->message)) {
            return ['error' => 'Empty message'];
        }

        $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($this->to);

        $mail = new \Mail($this->config->get('config_mail_engine'));
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        //$mail->unsubscribe = HTTPS_SERVER . 'index.php?route=account/unsubscribe&key=' . $unsubscribe_token;

        $mail->setTo($object->to);
        $mail->setFrom($this->config->get('config_mail_smtp_sender_mail'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($object->subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($object->message, ENT_QUOTES, 'UTF-8'));

        try {
            $mail->send();
        } catch (\Exception $e) {
            $this->log->write('Email: error in library/task/event/email on line 44 ' . "\n" . print_r($e, true));
        }

        time_nanosleep(0, 500000000);

        return ['success' => 1];
    }

}
