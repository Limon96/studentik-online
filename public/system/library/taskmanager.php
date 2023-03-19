<?php

class TaskManager {

    private $manager;

    public function __construct($db, $config)
    {
        $this->manager = new Task\Manager($db, $config);
    }

    public function set($data)
    {
        $taskItem = new Task\TaskItem([
            'channel' => $data['channel'] ?? 'default',
            'type' => $data['type'],
            'object' => $data['object'],
            'status' => 0,
            'time_exec' => $data['time_exec'] ?? time()
        ]);

        $this->manager->set($taskItem);
    }

    public function run($channel)
    {
        $this->manager->get($channel);
    }

}