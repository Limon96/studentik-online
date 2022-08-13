<?php

class ModelSearchSearch extends Model
{

    /*
     * id
     * title
     * type
     * online
     * section
     * subject
     * work_type
     * description
     * */

    public function search($data = array())
    {
        $tables = array();

        if (isset($data['search_customer']) && $data['search_customer']) {
            $tables[] = "SELECT
                    customer_id AS id,
                    login AS title,
                    'customer' AS type,
                    IF(" . time() . " - c.last_seen  < 900, 1, 0) AS online, 
                    image, 
                    '' AS section,
                    '' AS subject,
                    '' AS work_type,
                    '' AS description                        
                FROM
                    " . DB_PREFIX . "customer c";
        }

        if (isset($data['search_order']) && $data['search_order']) {
            $tables[] = "SELECT
                    order_id AS id,
                    title,
                    'order' AS type, 
                    '' AS image,                       
                    '' AS online,                       
                    (SELECT s.name FROM `" . DB_PREFIX . "section` s WHERE s.language_id = '1' AND s.section_id = o.section_id) AS `section`, 
                    (SELECT sj.name FROM `" . DB_PREFIX . "subject` sj WHERE sj.language_id = '1' AND sj.subject_id = o.subject_id) AS `subject`, 
                    (SELECT wt.name FROM `" . DB_PREFIX . "work_type` wt WHERE wt.language_id = '1' AND wt.work_type_id = o.work_type_id) AS `work_type`,
                    description
                FROM
                    " . DB_PREFIX . "order o";
        }

        if (count($tables) > 0) {
            $sql = "SELECT * FROM (" . implode(" UNION ", $tables) . ") temp";

            if (isset($data['search'])) {
                $sql .= " WHERE (title LIKE '%" . $this->db->escape($data['search']) . "%' OR description LIKE '%" . $this->db->escape($data['search']) . "%')";
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

            if ($result->rows) {
                 return $result->rows;
            }
        }

        return array();
    }

}