<?php

namespace Mail;

use Model\ClientToken;

class WebHook
{

    public function send()
    {
        static::sendToWebHook($this->to, $this->subject, $this->html);
    }

    private static function sendToWebHook($to, $subject, $message)
    {
        $myCurl = curl_init();
        curl_setopt_array($myCurl, array(
            CURLOPT_URL => 'https://studentik.online/api/smtp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'to' => $to,
                'subject' => $subject,
                'message' => $message,
                'token' => '0muU9W4KDNQiyFUsO2LQ2ey2iL0adB8'
            ])
        ));
        $response = curl_exec($myCurl);
        curl_close($myCurl);

        $data = json_decode($response, true);

        if (isset($data['success'])) return true;

        return false;
    }

}
