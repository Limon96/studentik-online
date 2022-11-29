<?php
class ModelAccountAuth extends Model {

    public function get($access_token)
    {
        $result = $this->db->query("SELECT * FROM customer_auths WHERE access_token = '" . $this->db->escape($access_token) . "' AND expired_at > '" . date('Y-m-d H:i:s') . "'");

        return $result->row;
    }

}
