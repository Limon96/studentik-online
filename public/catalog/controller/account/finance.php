<?php

class ControllerAccountFinance extends Controller
{
    private $error = array();

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/finance');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/finance')
        );

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = 10;

        $this->load->model('account/transaction');

        $data['transactions'] = array();

        $filter_data = [
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
            'sort' => 'date_added',
            'order' => 'DESC'
        ];

        $data['page'] = $page;
        $data['start'] = ($page - 1) * $limit;
        $data['total_transactions'] = $this->model_account_transaction->getTotalTransactions($filter_data);
        $transactions = $this->model_account_transaction->getTransactions($filter_data);

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $data['transactions'][] = [
                    'description' => $transaction['description'],
                    'amount' => $this->currency->format($transaction['amount'], $this->config->get('config_currency')),
                    'balance' => $this->currency->format($transaction['balance'], $this->config->get('config_currency')),
                    'date_added' => format_date($transaction['date_added'], 'd.m.Y H:i')
                ];
            }
        }


        $data['balance'] = $this->currency->format($this->customer->getBalance() ?? 0, $this->config->get('config_currency'));
        $data['blocked_cash'] = $this->currency->format($this->customer->getBlockedCash() ?? 0, $this->config->get('config_currency'));

        $data['payment'] = $this->url->link('account/finance/payment');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/finance', $data));
    }

    public function payment()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance/payment', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/finance');

        $this->document->setTitle($this->language->get('text_balance_refill'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/finance')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_balance_refill'),
            'href' => $this->url->link('account/finance/payment')
        );

        if (isset($this->request->get['amount']) && $this->request->get['amount'] > 0) {
            $amount = (float)$this->request->get['amount'];
        } else {
            $amount = 0;
        }

        $data['amount'] = $amount;

        $data['payment_methods'] = [

            /*[
                'name' => 'СПБ',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/sbp.png',
                'commission' => '1%',
                'href' => str_replace('&amp;','&',$this->url->link('extension/payment/yookassa', 'type=sbp' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('1%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/sbp.png&name=Система Быстрых Платежей'))
            ],*/
            /*[
                'name' => 'Банковской картой',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/bans.png',
                'commission' => '3%',
                'href' => str_replace('&amp;','&',$this->url->link('extension/payment/yookassa', 'type=bank_card' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('3%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/bans.png&name=Банковской картой'))
            ],*/
            /*[
                'name' => 'ЮMoney',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/yuyu.png',
                'commission' => '4%',
                'href' => str_replace('&amp;','&',$this->url->link('extension/payment/yookassa', 'type=yoo_money' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('4%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/yuyu.png&name=ЮMoney'))
            ],*/
            /*[
                'name' => 'WebMoney',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/webmoney.png',
                'commission' => '6%',
                'href' => str_replace('&amp;','&',$this->url->link('extension/payment/yookassa', 'type=webmoney' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('6%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/webmoney.png&name=WebMoney'))
            ],*/
            /*[
                'name' => 'QIWI',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/qiwi.png',
                'commission' => '6%',
                'href' => str_replace('&amp;','&',$this->url->link('extension/payment/yookassa', 'type=qiwi' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('6%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/qiwi.png&name=QIWI'))
            ],*/
            [
                'name' => 'СПБ',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/sbp.png',
                'commission' => '1.5%',
                'href' => str_replace('&amp;', '&', $this->url->link('extension/payment/sigma', 'type=sbp' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('1.5%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/sbp.png&name=Система Быстрых Платежей'))
            ],
            /*[
                'name' => 'QIWI (Sigma)',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/qiwi.png',
                'commission' => '6%',
                'href' => str_replace('&amp;', '&', $this->url->link('extension/payment/sigma', 'type=qiwi' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('6%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/qiwi.png&name=QIWI'))
            ],*/
            [
                'name' => 'Банковской картой',
                'thumb' => HTTP_SERVER . 'catalog/assets/img/bans.png',
                'commission' => '3.5%',
                'href' => str_replace('&amp;', '&', $this->url->link('extension/payment/sigma', 'type=bank_card' . ($amount ? '&amount=' . $amount : '') . '&commission=' . urlencode('3.5%') . '&thumb=' . HTTP_SERVER . 'catalog/assets/img/bans.png&name=Банковской картой'))
            ],
        ];

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/finance_payment', $data));
    }

    public function output()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance/output', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/finance');

        $this->document->setTitle($this->language->get('text_balance_withdrawal'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/finance')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_balance_withdrawal'),
            'href' => $this->url->link('account/finance/output')
        );

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = 30;

        $this->load->model('account/withdrawal');

        $data['withdrawals'] = array();

        $data['page'] = $page;
        $data['start'] = ($page - 1) * $limit;
        $data['total_withdrawals'] = $this->model_account_withdrawal->getTotalWithdrawals();
        $withdrawals = $this->model_account_withdrawal->getWithdrawals(($page - 1) * $limit, $limit);

        if ($withdrawals) {
            foreach ($withdrawals as $item) {
                $data['withdrawals'][] = [
                    'withdrawal_id' => $item['withdrawal_id'],
                    'status' => $item['status'],
                    'comment' => $item['comment'],
                    'card_number' => $item['card_number'],
                    'amount' => $this->currency->format($item['amount'], $this->config->get('config_currency')),
                    'balance' => $this->currency->format($item['balance'], $this->config->get('config_currency')),
                    'date_added' => format_date($item['date_added'], 'd.m.Y H:i')
                ];
            }
        }

        $data['balance'] = $this->currency->format($this->customer->getBalance() ?? 0, $this->config->get('config_currency'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/finance_output', $data));
    }


    public function success()
    {
        $this->load->language('account/finance');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance/success', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->session->data['referer']) && $this->session->data['referer'] != '') {
            $this->session->data['redirect'] = $this->url->link('account/finance/success', '', true);

            if (isset($this->session->data['offer_id'])) {
                $offer_id = (int)$this->session->data['offer_id'];
            } else {
                $offer_id = 0;
            }

            if (isset($this->session->data['order_id'])) {
                $order_id = (int)$this->session->data['order_id'];
            } else {
                $order_id = 0;
            }

            if ($offer_id && $order_id) {

                $referer = $this->session->data['referer'];
                $this->load->model('order/offer');
                $result = $this->model_order_offer->assignedOffer($offer_id, $order_id, $referer);

                if (isset($result['success'])) {
                    if (isset($result['redirect']) && $result['redirect'] != '') {
                        $this->session->data['success'] = $result['success'];
                        $this->response->redirect($result['redirect']);
                    }
                }
            }
        }

        $this->document->setTitle($this->language->get('text_payment_success'));

        $data['text_message'] = $this->language->get('text_payment_success');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/payment_success', $data));
    }

    public function canceled()
    {
        $this->load->language('account/finance');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance/canceled', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->document->setTitle($this->language->get('text_payment_canceled'));


        $data['text_message'] = $this->language->get('text_payment_canceled');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/payment_canceled', $data));
    }

    public function pending()
    {
        $this->load->language('account/finance');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/finance/pending', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->document->setTitle($this->language->get('text_payment_pending'));


        $data['text_message'] = $this->language->get('text_payment_pending_message');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/payment_pending', $data));
    }

    public function output_add()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateOutputAdd()) {
            $this->load->model('account/withdrawal');
            $this->model_account_withdrawal->addWithdrawal($this->request->post);
        }

        if ($this->error) {
            foreach ($this->error as $key => $value) {
                $json['error_' . $key] = $value;
            }
        } else {
            $json['success'] = "Заявка создана";
        }

        $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
    }

    public function output_delete()
    {
        $this->load->model('account/withdrawal');
        $this->model_account_withdrawal->deleteWithdrawal($this->request->get['withdrawal_id']);

        $json['success'] = "Заявка удалена";

        $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
    }

    private function validateOutputAdd()
    {
        if (!isset($this->request->post['method'])) {
            $this->error['method'] = 'Выберите способ выплаты';
        }

        if (!isset($this->request->post['amount'])) {
            $this->error['amount'] = 'Введите сумму вывода';
        } else {
            if ($this->request->post['amount'] < 100 || $this->request->post['amount'] > 50000) {
                $this->error['amount'] = 'Введите сумму от 100 руб. до 50 000 руб.';
            }

            if ($this->request->post['amount'] > $this->customer->getBalance()) {
                $this->error['amount'] = 'Недостаточно средств для вывода';
            }
        }

        if (!isset($this->request->post['card_number'])) {
            $this->error['card_number'] = 'Введите номер карты';
        } else {
            if (strlen($this->request->post['card_number']) < 8 || strlen($this->request->post['card_number']) > 25) {
                $this->error['card_number'] = 'Введен некорректный номер карты';
            }
        }

        return !$this->error;
    }

}
