<?php
class ControllerAccountPromoCode extends Controller
{
    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/promo_code', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['start'])) {
            $start = (int)$this->request->get['start'];
        } else {
            $start = 0;
        }


        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = 0;
        }

        $this->load->language('account/promo_code');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->load->model('account/customer');

        $this->document->setTitle($this->language->get('heading_title'));


       // $this->load->model('account/promo_code');

        $filter_data = [
            'filter_customer_id' => $this->customer->getId(),
            'start' => $start,
            'limit' => $limit
        ];

       // $promo_codes = $this->model_account_promo_code->getPromoCodes($filter_data);

        $data['promo_codes'] = array();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/promo_code', $data));

    }
}