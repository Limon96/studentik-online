<?php

namespace Task;

use Task\Event\Email;
use Task\Event\Emailing;
use Task\Event\Offer;
use Task\Event\Orders;

class Manager {

    private $db;
    private $config;
    private $taskModel;
    private $order;
    private $offer;
    private $emailing;
    private $log;
    private $debug = false;
    private $white_channel = [
        'default',
        'emails'
    ];

    public function __construct($db, $config)
    {
        $this->db = $db;
        $this->config = $config;
        $this->order = new Orders($this->config, $this->db);
        $this->offer = new Offer($this->config, $this->db);
        $this->emailing = new Emailing($this->config, $this->db);
        $this->taskModel = new \Task\Model\Task($db);
        $this->log = new \Log('task.log');
    }

    public function get($channel = 'default')
    {
        $tasks = $this->taskModel->getTasks($channel);
        if ($tasks) {
            foreach ($tasks as $task) {
                switch ($task->type) {
                    case "email_send":
                        $result = (new Email($this->config, $this->db))->send($task->object);
                        break;

                    case "order_cancel":
                        $result = $this->order->cancel($task->object);
                        break;

                    case "offer_cancel":
                        $result = $this->offer->cancel($task->object);
                        break;

                    case "offer_close":
                        $result = $this->offer->close($task->object);
                        break;

                    case "offer_premium":
                        $result = $this->offer->premium($task->object);
                        break;

                    case "emailing_new_order":
                        $result = $this->emailing->newOrder($task->object);

                        foreach ($result as $item) {
                            $this->set($item);
                        }
                        break;

                    default:
                        $this->log->write('[TaskManager@get] error: unknown type');
                        $this->log->write('[TaskManager@get] task: ' . print_r($task, true));
                        break;
                }

                if (isset($result['error'])) {
                    $this->taskModel->failure($task->task_id);

                    $this->log->write('[TaskManager@get] error: ' . print_r($result['error'], true));
                    $this->log->write('[TaskManager@get] task: ' . print_r($task, true));
                } else {
                    $this->taskModel->success($task->task_id);

                    if ($this->debug) {
                        $this->log->write('[TaskManager@get] success');
                        $this->log->write('[TaskManager@get] task: ' . print_r($task, true));
                    }
                }
            }
        }
    }

    public function set(TaskItem $task)
    {
        if (in_array($task->channel, $this->white_channel)) {
            $this->taskModel->set($task);

            if ($this->debug) {
                $this->log->write('[TaskManager@set] set TaskItem:' . print_r($task, true));
            }
        } else {
            if ($this->debug) {
                $this->log->write('[TaskManager@set] error channel in TaskItem:' . print_r($task, true));
            }
        }

    }

}
