<?php

class ModelClaimClaim extends Model {

    public function addClaim($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "claim SET 
            customer_id = '" . (int)$data['customer_id'] . "', 
            defendant_id = '" . (int)$data['defendant_id'] . "', 
            `type` = '" . $this->db->escape($data['type']) . "', 
            object_id = '" . (int)$data['object_id'] . "', 
            comment = '" . $this->db->escape($data['comment']) . "', 
            status = 0,
            viewed = 0,
            date_added = '" . time() . "',
            date_updated = NOW()
        ");

        $claim_id = $this->db->getLastId();

        if (isset($data['attachment'])) {
            foreach ($data['attachment'] as $attachment_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "claim_attachment  SET claim_id = '" . (int)$claim_id . "', attachment_id = '" . (int)$attachment_id . "'");
            }
        }

        return $claim_id;
    }

    public function editClaim($claim_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "offer SET
            customer_id = '" . $this->customer->getId() . "', 
            defendant_id = '" . (int)$data['defendant_id'] . "', 
            `type` = '" . $this->db->escape($data['type']) . "', 
            object_id = '" . (int)$data['object_id'] . "', 
            comment = '" . $this->db->escape($data['comment']) . "', 
            date_updated = NOW()
            WHERE claim_id = '" . (int)$claim_id . "'
        ");
    }

    public function getClaim($claim_id)
    {
        $result = $this->db->query("SELECT * FROM ". DB_PREFIX . "claim WHERE claim_id = '" . $claim_id . "'");

        if ($result->row) {
            return $result->row;
        }
        return array();
    }

    public function getClaims($data)
    {
        $sql = "SELECT *, IF(customer_id = '" . (int)$this->customer->getId() . "',1,0) AS is_owner FROM ". DB_PREFIX . "claim WHERE (customer_id = '" . (int)$this->customer->getId() ."' OR defendant_id = '" . (int)$this->customer->getId() ."')";

        if (isset($data['filter_status_off'])
            && $data['filter_status_off'] != ''
            && isset($data['filter_status_on'])
            && $data['filter_status_on'] != '') {
            $sql .= '';
        } else {
            if (isset($data['filter_status_off']) && $data['filter_status_off'] != '') {
                $sql .= ' AND status = 1';
            }

            if (isset($data['filter_status_on']) && $data['filter_status_on'] != '') {
                $sql .= ' AND status = 0';
            }
        }

        $sort_data = array(
            'date_added',
            'date_updated',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'date_updated') {
                $sql .= " ORDER BY date_updated";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, status ASC";
        } else {
            $sql .= " ASC, status ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }


        $result = $this->db->query($sql);

        $offer_data = array();

        if ($result->rows) {

            foreach ($result->rows as $row) {

                $offer_data[] = $this->getClaim($row['claim_id']);
            }
        }
        return $offer_data;
    }

    public function getTotalClaims($data)
    {
        $sql = "SELECT COUNT(1) as total FROM ". DB_PREFIX . "claim WHERE (customer_id = '" . (int)$this->customer->getId() ."' OR defendant_id = '" . (int)$this->customer->getId() ."')";

        if (isset($data['filter_status_off'])
            && $data['filter_status_off'] != ''
            && isset($data['filter_status_on'])
            && $data['filter_status_on'] != '') {
            $sql .= '';
        } else {
            if (isset($data['filter_status_off']) && $data['filter_status_off'] != '') {
                $sql .= ' AND status = 1';
            }

            if (isset($data['filter_status_on']) && $data['filter_status_on'] != '') {
                $sql .= ' AND status = 0';
            }
        }

        $sort_data = array(
            'date_added',
            'date_updated',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'date_updated') {
                $sql .= " ORDER BY date_updated";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, status DESC";
        } else {
            $sql .= " ASC, status ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }


        $result = $this->db->query($sql);

        $total = 0;

        if (isset($result->row['total'])) {
            $total = (int)$result->row['total'];
        }

        return $total;
    }

    public function getClaimAttachment($claim_id)
    {
        $result = $this->db->query("SELECT a.* FROM " . DB_PREFIX . "claim_attachment oa LEFT JOIN " . DB_PREFIX . "attachment a ON oa.attachment_id = a.attachment_id WHERE oa.claim_id = '" . (int)$claim_id . "'");
        return $result->rows;
    }

    public function addAttachment($claim_id, $attachment_id)
    {
        $this->db->query("INSERT " . DB_PREFIX . "claim_attachment SET attachment_id = " . (int)$attachment_id . ", claim_id = '" . (int)$claim_id . "'");
    }

    public function deleteAttachment($claim_id, $attachment_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "claim_attachment WHERE attachment_id = " . (int)$attachment_id . " AND claim_id = '" . (int)$claim_id . "'");
    }

}