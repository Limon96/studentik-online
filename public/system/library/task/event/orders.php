<?php

namespace Task\Event;

use Model\Order;
use Task\TaskItem;

class Orders {

    private $config;
    private $db;

    private $order;
    private $customer;
    private $notification;

    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;

        $this->order = new \Model\Order($this->db);
        $this->customer = new \Model\Customer($this->db);
        $this->notification = new \Model\Notification($this->db);
    }

    public function cancel($object): array
    {
        $order_status_id = $this->order->getOrderStatus($object->order->order_id);
        // Проверяем статус заказа "в ожидании"
        if ($order_status_id == $this->config->get('config_open_order_status_id')) {
            // Отменяем заказ в статус "Отменен"
            $this->order->setOrderStatus($object->order->order_id, $this->config->get('config_canceled_order_status_id'));

            // Текст уведомления
            $message = "Заказ " . $object->order->title . " отменено системой";
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
                        'subject' => sprintf("Заказ отменен системой %s", $this->config->get('config_name')),
                        'message' => $html
                    ]
                ]);
        }

        return [
            'success' => 1
        ];
    }

}