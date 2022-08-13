<?php
class ControllerAccountEvent extends Controller
{
    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->document->addScript('../catalog/assets/js/notification.js', 'footer');

        $this->load->language('account/event');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/customer', 'customer_id=' . $this->customer->getid(), true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_event'),
            'href' => $this->url->link('account/event', '', true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = 20;

        $this->load->model('tool/notification');

        $filter_data = [
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        ];

        $data['notifications'] = array();
        $total_notifications = $this->model_tool_notification->getTotalAll($this->customer->getId(), $filter_data);
        $notifications = $this->model_tool_notification->getAll($this->customer->getId(), $filter_data);
        if ($notifications) {
            foreach ($notifications as $notification) {
                $data['notifications'][] = [
                    'notification_id' => $notification['notification_id'],
                    'type' => $notification['type'],
                    'text' => $notification['text'],
                    'comment' => $notification['comment'],
                    'viewed' => $notification['viewed'],
                    'date_added' => format_date($notification['date_added'], 'full_datetime'),
                ];
            }
        }
        $this->model_tool_notification->viewedNotifications($this->customer->getId());

        $data['limit'] = $limit * $page;
        $data['total_notifications'] = $total_notifications;

        $data['continue'] = $this->url->link('account/event', 'page=' . ($page + 1));

        $data['pagination'] = $this->url->link('account/event', 'page=' . $page);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/event', $data));
    }

    public function viewedNotification()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/event', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['notification_id'])) {
            $notification_id = (int)$this->request->get['notification_id'];
        } else {
            $notification_id = 0;
        }

        $this->load->language('account/event');

        $this->load->model('tool/notification');

        $json = [];

        $notification_info = $this->model_tool_notification->getNotification($notification_id);

        if ($notification_info) {
            if ($notification_info['customer_id'] == $this->customer->getId()) {
                $this->model_tool_notification->viewedNotification($this->customer->getId(), $notification_id);
                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}