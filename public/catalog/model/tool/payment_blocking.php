<?php

class ModelToolPaymentBlocking extends Model
{

    public function getAll()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payment_blocking");

        return $query->rows;
    }

}