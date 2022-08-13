<?php

namespace Task;

class TaskItem {
    /*
        private $channel;
        private $type;
        private $object;
        private $status;
        private $time_exec;
    */
    private $fillable = [
        'channel',
        'type',
        'object',
        'status',
        'time_exec',
        'time_complete',
        'time_added'
    ];

    public function __construct($data = array())
    {
        if (!empty($data)) $this->fill($data);
    }

    public function fill($data){
        if (!empty($data)) {
            foreach ($this->fillable as $key) {
                $this->$key = $data[$key] ?? '';
            }
        }
        return $this;
    }

}