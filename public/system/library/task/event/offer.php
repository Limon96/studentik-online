<?php

namespace Task\Event;

use Model\Order;
use Task\TaskItem;

class Offer {

    private $config;
    private $db;

    private $order;
    private $offer;
    private $customer;
    private $notification;

    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;

        $this->order = new \Model\Order($this->db);
        $this->offer = new \Model\Offer($this->db);
        $this->customer = new \Model\Customer($this->db);
        $this->notification = new \Model\Notification($this->db);
    }

    public function premium($object): array
    {
        $this->order->disablePremium($object->order_id);

        return [
            'success' => 1
        ];
    }

    public function cancel($object): array
    {
        $order_status_id = $this->order->getOrderStatus($object->order->order_id);
        // Проверяем статус заказа "в ожидании"
        if ($order_status_id == $this->config->get('config_pending_order_status_id')) {
            // возвращаем заказу статус "Открытый"
            $this->order->setOrderStatus($object->order->order_id, $this->config->get('config_open_order_status_id'));
            // отменяем выбор у всех предложений заказа
            $this->offer->cancelOffersFromOrder($object->order->order_id);
            // Возвращаем деньги
            $this->customer->returnBlockedCash([
                'customer_id' => $object->order_customer_info->customer_id,
                'order_id' => $object->order->order_id,
                'title' => $object->order->title,
                'offer_id' => $object->offer->offer_id,
            ]);
            // Текст уведомления
            $message = "Предложение в заказе " . $object->order->title . " отменено системой";
            // Пишем в историю заказа системную отмену
            $this->order->setOrderHistory(
                $object->order->order_id,
                0,
                'Предложение отменено системой'
            );
            // Отправляем уведомление студенту
            $this->notification->set(
                $object->order_customer_info->customer_id,
                'order',
                $message
            );
            // Отправляем уведомление автору
            $this->notification->set(
                $object->offer_customer_info->customer_id,
                'order',
                $message
            );

            // Подготовка письма
            // HTML
            $template = new \Template('twig');

            $data = [ "message" => $message ];

            foreach ($data as $key => $value) {
                $template->set($key, $value);
            }

            $html = $template->render($this->config->get('template_directory') . 'mail/offer');
            // Отправляем в очередь письмо студенту
            (new \TaskManager($this->db, $this->config))
                ->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $object->order_customer_info->email,
                        'subject' => sprintf("Предложение отменено системой %s", $this->config->get('config_name')),
                        'message' => $html
                    ]
                ]);
            // Отправляем в очередь письмо автору
            (new \TaskManager($this->db, $this->config))
                ->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $object->offer_customer_info->email,
                        'subject' => sprintf("Предложение отменено системой %s", $this->config->get('config_name')),
                        'message' => $html
                    ]
                ]);

        }

        return [
            'success' => 1
        ];
    }

    public function close($object)
    {
        $total_claims = $this->order->geTotalOrderClaims($object->order->order_id);

        $order_status_id = (new Order($this->db))->getOrderStatus($object->order->order_id);

        if (!$total_claims && $order_status_id == $this->config->get('config_verification_order_status_id')) {
            // Устанавливаем статус заказа "Завершен"
            $this->order->setOrderStatus($object->order->order_id, $this->config->get('config_complete_order_status_id'));

            // Переводим деньги автору
            $this->customer->setBalanceOfferFromBlockedCash(
                $object->offer_customer_info,
                $object->order,
                $object->offer->offer_id,
                $this->config->get('config_commission_customer'),
                $this->config->get('config_commission')
            );

            // Текст уведомления
            $message = "Заказ " . $object->order->title . " завершен системой";
            // Пишем в историю заказа системную отмену
            $this->order->setOrderHistory(
                $object->order->order_id,
                0,
                'Заказ завершен системой'
            );
            // Отправляем уведомление студенту
            $this->notification->set(
                $object->order_customer_info->customer_id,
                'order',
                $message
            );
            // Отправляем уведомление автору
            $this->notification->set(
                $object->offer_customer_info->customer_id,
                'order',
                $message
            );

            // Подготовка письма
            // HTML
            $template = new \Template('twig');

            $data = [ "message" => $message ];

            foreach ($data as $key => $value) {
                $template->set($key, $value);
            }

            $html = $template->render($this->config->get('template_directory') . 'mail/offer');
            // Отправляем в очередь письмо студенту
            (new \TaskManager($this->db, $this->config))
                ->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $object->order_customer_info->email,
                        'subject' => sprintf("Заказ завершен системой %s", $this->config->get('config_name')),
                        'message' => $html
                    ]
                ]);
            // Отправляем в очередь письмо автору
            (new \TaskManager($this->db, $this->config))
                ->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $object->offer_customer_info->email,
                        'subject' => sprintf("Заказ завершен системой %s", $this->config->get('config_name')),
                        'message' => $html
                    ]
                ]);
        }

        return [
            'success' => 1
        ];
    }

}