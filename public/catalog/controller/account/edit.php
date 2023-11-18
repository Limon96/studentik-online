<?php

class ControllerAccountEdit extends Controller
{
    private $error = array();

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/edit', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/edit');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->load->model('account/customer');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('account/customer', 'customer_id=' . $this->customer->getId(), true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/customer', 'customer_id=' . $this->customer->getId(), true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_edit'),
            'href' => $this->url->link('account/edit', '', true)
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['login'])) {
            $data['error_login'] = $this->error['login'];
        } else {
            $data['error_login'] = '';
        }

        if (isset($this->error['login_exists'])) {
            $data['error_login_exists'] = $this->error['login_exists'];
        } else {
            $data['error_login_exists'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        $data['action'] = $this->url->link('account/edit', '', true);
        $data['can_edit_login'] = 0;

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        if ($customer_info && $customer_info['login'] == 'user' . $customer_info['customer_id']) {
            $data['can_edit_login'] = 1;
        }

        if (isset($this->request->post['login'])) {
            $data['login'] = $this->request->post['login'];
        } elseif (!empty($customer_info)) {
            $data['login'] = $customer_info['login'];
        } else {
            $data['login'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($customer_info)) {
            $data['telephone'] = $customer_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['languages'])) {
            $data['languages'] = $this->request->post['languages'];
        } elseif (!empty($customer_info)) {
            $data['languages'] = $customer_info['languages'];
        } else {
            $data['languages'] = '';
        }

        if (isset($this->request->post['bdate'])) {
            $data['bdate'] = $this->request->post['bdate'];
        } elseif (!empty($customer_info)) {
            $data['bdate'] = $customer_info['bdate'];
        } else {
            $data['bdate'] = '1995-12-19';
        }

        if (isset($this->request->post['country_id'])) {
            $data['country_id'] = $this->request->post['country_id'];
        } elseif (!empty($customer_info)) {
            $data['country_id'] = $customer_info['country_id'];
        } else {
            $data['country_id'] = 0;
        }

        if (isset($this->request->post['gender'])) {
            $data['gender'] = $this->request->post['gender'];
        } elseif (!empty($customer_info)) {
            $data['gender'] = $customer_info['gender'];
        } else {
            $data['gender'] = 0;
        }

        if (isset($this->request->post['setting_email_notify'])) {
            $data['setting_email_notify'] = $this->request->post['setting_email_notify'];
        } elseif (!empty($customer_info)) {
            $data['setting_email_notify'] = $customer_info['setting_email_notify'];
        } else {
            $data['setting_email_notify'] = 1;
        }

        if (isset($this->request->post['setting_email_new_order'])) {
            $data['setting_email_new_order'] = $this->request->post['setting_email_new_order'];
        } elseif (!empty($customer_info)) {
            $data['setting_email_new_order'] = $customer_info['setting_email_new_order'];
        } else {
            $data['setting_email_new_order'] = 0;
        }

        $data['customer_group_id'] = $customer_info['customer_group_id'];

        if (isset($this->request->post['comment'])) {
            $data['comment'] = $this->request->post['comment'];
        } elseif (!empty($customer_info)) {
            $data['comment'] = $customer_info['comment'];
        } else {
            $data['comment'] = '';
        }

        if (isset($this->request->post['timezone'])) {
            $data['customer_timezone'] = $this->request->post['timezone'];
        } elseif (!empty($customer_info)) {
            $data['customer_timezone'] = $customer_info['timezone'];
        } else {
            $data['customer_timezone'] = $this->config->get('config_timezone');
        }

        // Set Time Zone

        $white_timezones = [
            'Europe/Kaliningrad' => 'Калининград',
            'Europe/Moscow' => 'Москва',
            'Europe/Samara' => 'Самара',
            'Asia/Yekaterinburg' => 'Екатеринбург',
            'Asia/Omsk' => 'Омск',
            'Asia/Krasnoyarsk' => 'Красноярск',
            'Asia/Irkutsk' => 'Иркутск',
            'Asia/Yakutsk' => 'Якутск',
            'Asia/Vladivostok' => 'Владивосток',
            'Asia/Magadan' => 'Магадан',
            'Asia/Kamchatka' => 'Камчатка',
        ];

        $data['timezones'] = array();

        $timestamp = time();

        $timezones = timezone_identifiers_list();

        foreach ($timezones as $timezone) {
            if (isset($white_timezones[$timezone])) {
                date_default_timezone_set($timezone);
                $hour = date('P', $timestamp);
                $data['timezones'][$hour] = array(
                    'text' => $white_timezones[$timezone] . ' (' . $hour . ')',
                    'hour' => $hour,
                    'value' => $timezone
                );
            }
        }

        date_default_timezone_set($this->config->get('config_timezone'));

        sort($data['timezones']);

        $sort = array();
        foreach ($data['timezones'] as $key => $row)
            $sort[$key] = $row['hour'];

        array_multisort($sort, SORT_ASC, $data['timezones']);
        /*
                $data['timezones'] = $sort;*/

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['back'] = $this->url->link('account/account', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/edit', $data));
    }

    public function save()
    {
        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('account/account', '', true);
        } else {
            $this->load->language('account/edit');

            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('account/account', '', true));
            } else {
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }

                if (isset($this->error['firstname'])) {
                    $json['error_firstname'] = $this->error['firstname'];
                } else {
                    $json['error_firstname'] = '';
                }

                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = '';
                }

                if (isset($this->error['password'])) {
                    $json['error_password'] = $this->error['password'];
                } else {
                    $json['error_password'] = '';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveAvatar()
    {
        if (!$this->customer->isLogged()) {
            $json['error'] = 1;
            $json['message'] = $this->language->get('error_access_denied');

        } else {
            $this->load->language('account/edit');

            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSaveAvatar()) {
                $json['success'] = $this->language->get('text_success');

                $this->model_account_customer->setCustomerAvatar($this->customer->getId(), $this->request->post['image']);
                $this->load->model('tool/image');
                $json['image'] = $this->model_tool_image->resize($this->request->post['image'], 80, 80);
            } else {
                $json['error'] = 1;
                if (isset($this->error['image'])) {
                    $json['error_image'] = $this->error['image'];
                } else {
                    $json['error_image'] = '';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function uploadAvatar()
    {
        if (!$this->customer->isLogged()) {
            $json['error'] = 1;
            $json['message'] = $this->language->get('error_access_denied');

        } else {
            $this->load->language('account/edit');

            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSaveAvatar()) {
                $json['success'] = $this->language->get('text_success');

                $image = $this->base64_to_jpeg($this->request->post['image'], 'avatars/');
                $this->model_account_customer->setCustomerAvatar($this->customer->getId(), $image);
                $this->load->model('tool/image');
                $json['image'] = $this->model_tool_image->resize($image, 80, 80);
            } else {
                $json['error'] = 1;
                if (isset($this->error['image'])) {
                    $json['error_image'] = $this->error['image'];
                } else {
                    $json['error_image'] = '';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if (isset($this->request->post['login'])) {
            $seo_url = seo_translit($this->request->post['login']);

            $seo_url_data = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'seo_url WHERE keyword = "' . $this->db->escape($seo_url) . '" AND query <> "customer_id=' . $this->customer->getId() . '"');

            if ($seo_url_data->num_rows) {
                $this->error['login'] = $this->language->get('error_login_incorrect');
            }

            if ((utf8_strlen(trim($this->request->post['login'])) < 1) || (utf8_strlen(trim($this->request->post['login'])) > 32) || !preg_match('~^[a-zA-Z0-9]+$~', $this->request->post['login'])) {
                $this->error['login'] = $this->language->get('error_login');
            }

            if ($this->request->post['login'] != 'user' . $this->customer->getId()) {

                if (preg_match('/^(?i)user[0-9]*/', $this->request->post['login'])) {
                    $this->error['login'] = $this->language->get('error_login_incorrect');
                }
            }

            $customer_info = $this->model_account_customer->getCustomerByEmailOrLogin($this->request->post['login']);

            if ($customer_info && $customer_info['customer_id'] != $this->customer->getId()) {
                $this->error['login_exists'] = $this->language->get('error_login_exists');
            }
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        /*if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }*/

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields('account', $this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if ($custom_field['location'] == 'account') {
                if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                    $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                } elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                    $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }
        }

        return !$this->error;
    }

    protected function validateSaveAvatar()
    {
        if (!isset($this->request->post['image']) && strlen($this->request->post['image']) < 3) {
            $this->error['image'] = $this->language->get('error_image');
        }

        return !$this->error;
    }

    private function base64_to_jpeg($base64_string, $output_file)
    {
        $filename = md5(microtime(true) . rand(10000, 99999));

        $output_file .= substr($filename, 0, 2) . '/' . substr($filename, 2, 2) . '/';

        $path_output_file = DIR_IMAGE . $output_file;

        if (!file_exists($path_output_file)) {
            mkdir($path_output_file, 0777, true);
        }

        $output_file .= $filename . '.png';
        $path_output_file .= $filename . '.png';

        // open the output file for writing
        $ifp = fopen($path_output_file, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }
}
