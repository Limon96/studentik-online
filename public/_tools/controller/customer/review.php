<?php

class ControllerCustomerReview extends Controller {

    public function edit()
    {
        if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            (new \Model\Review($this->db))
                ->edit($this->request->get['review_id'], $this->request->post['review'][$this->request->get['review_id']]);
        }

        $this->response->setOutput(json_encode([
            'success' => 1
        ]));
    }

    public function remove()
    {
        if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            (new \Model\Review($this->db))
                ->remove(
                    $this->request->get['review_id'],
                    (new \Model\Customer($this->db))
                );
        }

        $this->response->setOutput(json_encode([
            'success' => 1
        ]));
    }

}