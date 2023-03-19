<?php

namespace Model;

class Review extends Model
{

    public function edit($review_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "review SET 
            positive = '" . (int)$data['positive'] . "',
            time = '" . (int)$data['time'] . "',
            text = '" . $this->db->escape($data['text']) . "',
            date_updated = '" . time() . "'
        WHERE review_id = '" . (int)$review_id . "'");
    }

    public function remove($review_id, $model = null)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

        if ($model) {
            $model->removeReview($review_id);
        }
    }

}