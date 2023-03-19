<?php

class ModelToolPlagiarismCheck extends Model
{

    public function getAll()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "plagiarism_check");

        return $query->rows;
    }

}