<?php

require_once DIR_STORAGE . 'vendor/yookassa/lib/autoload.php';

use YooKassa\Client;

class YooKassa {

    private $client;

    public function __construct($shopId, $secretKey)
    {
        $this->client = new Client();
        $this->client->setAuth($shopId, $secretKey);
    }

    public function create($paymentData) {

        $idempotenceKey = uniqid('', true);
        $response = $this->client->createPayment(
            $paymentData,
            $idempotenceKey
        );

        //get confirmation url
        try {
            $confirmation_url = $response->getConfirmation()->getConfirmationUrl();
        } catch (Exception $exception) {
            $confirmation_url = HTTP_SERVER;
        }

        return [
            'redirect_url' => $confirmation_url,
            'yookassa_payment_id' => $response->id
        ];
    }

    public function getPaymentInfo($paymentId)
    {
        $payment = $this->client->getPaymentInfo($paymentId);
        return $payment;
    }

}
