<?php

class ModelMessageChat extends Model {

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('message/models/message');
        $this->load->model('message/models/chat');
    }

    public function getMessages($customer_id, $chat_id, $start = 0, $limit = 20, $last_message_id = 0)
    {
        return $this->model_message_models_message->getMessages($customer_id, $chat_id, $start, $limit, $last_message_id);
    }

    public function getTotalMessages($customer_id, $chat_id)
    {
        return $this->model_message_models_message->getTotalMessages($customer_id, $chat_id);
    }

    public function getViewedMessages($customer_id, $chat_id)
    {
        return $this->model_message_models_message->getViewedMessages($customer_id, $chat_id);
    }

    public function getTotalUnreadMessages($customer_id)
    {
        return $this->model_message_models_message->getTotalUnreadMessages($customer_id);
    }

    public function getMessageById($message_id)
    {
        return $this->model_message_models_message->getMessageById($message_id);
    }

    public function addMessage($data)
    {
        return $this->model_message_models_message->addMessage($data);
    }

    public function getMessageAttachment($message_id)
    {
        return $this->model_message_models_message->getMessageAttachment($message_id);
    }

    public function viewedMessage($recipient_id, $message_id)
    {
        return $this->model_message_models_message->viewedMessage($recipient_id, $message_id);
    }

    public function getChats($customer_id, $start = 0, $limit = 20, $last_message_id = 0)
    {
        return $this->model_message_models_chat->getChats($customer_id, $start, $limit, $last_message_id);
    }

    public function searchChats($customer_id, $search, $start = 0, $limit = 20)
    {
        return $this->model_message_models_chat->getChats($customer_id, $search, $start, $limit);
    }

    public function getChatsInfo($customer_id, $list_ids = [])
    {
        return $this->model_message_models_chat->getChatsInfo($customer_id, $list_ids);
    }

    public function getChat($customer_id, $chat_id)
    {
        return $this->model_message_models_chat->getChat($customer_id, $chat_id);
    }

    public function viewedChat($recipient_id, $chat_id)
    {
        return $this->model_message_models_chat->viewedChat($recipient_id, $chat_id);
    }

}