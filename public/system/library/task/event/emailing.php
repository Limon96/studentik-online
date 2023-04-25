<?php

namespace Task\Event;

use Model\Customer;
use Model\Image;
use Task\TaskItem;
use Template\Twig;

class Emailing {

    private $config;
    private $db;

    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;
    }

    public function newOrder($object) : array
    {
        // Получаем список нужных пользователей
        $result = (new Customer($this->db))
            ->getCustomersFromFilter([
                'customer_group_id' => 2,
                'setting_email_new_order' => 1,
                'subject_id' => $object->order->subject_id
            ]);

        if ($result) {
            // собираем письмо
            $medata = [];
            // Получаем заказчика
            $customer_info =  (new Customer($this->db))->get($object->order->customer_id);

            if ($customer_info['image']) {
                $image = (new Image($this->db))->resize($customer_info['image'], 80, 80);
            } else {
                $image = (new Image($this->db))->resize('profile.png', 80, 80);
            }

            // Unsubscribe generate
            $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($customer_info['email']);
            $medata['unsubscribe'] = HTTPS_SERVER . 'index.php?route=account/unsubscribe&key=' . $unsubscribe_token;

            $medata['title'] = $object->order->title;
            $medata['section'] = $object->order->section;
            $medata['subject'] = $object->order->subject;
            $medata['work_type'] = $object->order->work_type;
            $medata['price'] = $object->order->price;
            $medata['description'] = nl2br(htmlspecialchars_decode($object->order->description));
            $medata['link'] = HTTPS_SERVER . 'index.php?route=order/order/info&order_id=' . $object->order->order_id;
            $medata['date_end'] = ($object->order->date_end != '0000-00-00'? format_date($object->order->date_end, 'full_date') : 'Не указан');

            $tpl = new Twig();
            $tpl->set('login', $customer_info['login']);
            $tpl->set('image', $image);
            $tpl->set('online', 0);
            $tpl->set('href', HTTPS_SERVER . 'index.php?route=account/customer&customer_id=' . $customer_info['customer_id']);

            $medata['customer'] = $tpl->render('tool/customer_no_photo');

            $medata = Emailing::convert_from_latin1_to_utf8_recursively($medata);

            $template = new Twig();

            foreach ($medata as $k => $v) {
                $template->set($k, $v);
            }

            $message = $template->render('mail/order_new');

            $tasks = [];
            foreach ($result as $customer) {
                if (filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
                    $tasks[] = new TaskItem([
                        'channel' => 'emails',
                        'type' => 'email_send',
                        'time_exec' => time(),
                        'object' => [
                            'to' => $customer['email'],
                            'subject' => "Новый заказ по предмету \"" . $object->order->subject . "\" №" . $object->order->order_id,
                            'message' => htmlspecialchars($message)
                        ],
                        'status' => 0
                    ]);
                }
            }

            return $tasks;
        }

        return [
            "error" => 'Users unknown'
        ];

    }

    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

}
