<?php

class ControllerCommonDownload extends Controller {

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->model('tool/attachment');

        if (isset($this->request->get['attachment_id'])) {
            $attachment_id = $this->request->get['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        if ($attachment_info) {
            $file = DIR_UPLOAD . $attachment_info['src'];
            $mask = basename($attachment_info['name']);

            if (!headers_sent()) {
                if (file_exists($file)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    if (ob_get_level()) {
                        ob_end_clean();
                    }

                    readfile($file, 'rb');

                    exit();
                } else {
                    exit('Error: Could not find file ' . $file . '!');
                }
            } else {
                exit('Error: Headers already sent out!');
            }
        } else {
            $this->response->redirect($this->url->link('order/order', '', true));
        }
    }

}