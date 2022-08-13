<?php

class ControllerCommonUpload extends Controller
{
    public function index()
    {

    }

    public function upload() {
        $this->load->language('common/upload');
        $this->load->model('tool/attachment');

        $json = array();

        if ($this->customer->isLogged()) {
            // Make sure we have the correct directory
            $directory = DIR_UPLOAD . 'user' . $this->customer->getId() . '';

            // Check its a directory
            if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD . 'user' . $this->customer->getId() . '')) != str_replace('\\', '/', DIR_UPLOAD . 'user' . $this->customer->getId() . '')) {
                mkdir($directory);
            }
        } else {
            $json['error_auth'] = $this->language->get('error_auth');
        }

        if (!$json) {
            // Check if multiple files are uploaded or just one
            $files = array();

            if (!empty($this->request->files['file']['name']) && is_array($this->request->files['file']['name'])) {
                foreach (array_keys($this->request->files['file']['name']) as $key) {
                    $files[] = array(
                        'name'     => $this->request->files['file']['name'][$key],
                        'type'     => $this->request->files['file']['type'][$key],
                        'tmp_name' => $this->request->files['file']['tmp_name'][$key],
                        'error'    => $this->request->files['file']['error'][$key],
                        'size'     => $this->request->files['file']['size'][$key]
                    );
                }
            }

            foreach ($files as $i => $file) {
                if (is_file($file['tmp_name'])) {
                    // Sanitize the filename
                    $filename = basename(html_entity_decode(translit($file['name']), ENT_QUOTES, 'UTF-8'));
                    $newfilename = md5(basename(html_entity_decode(translit(strtolower($file['name'])), ENT_QUOTES, 'UTF-8')) . microtime(true));

                    // Validate the filename length
                    if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                        $json['error'][$i] = $this->language->get('error_filename');
                    }

                    // Allowed file extension types
                    $allowed = array(
                        'bmp',
                        'djvu',
                        'doc',
                        'docx',
                        'xls',
                        'xlsx',
                        'dwg',
                        'csv',
                        'gif',
                        'jpeg',
                        'jpg',
                        'odf',
                        'odt',
                        'pdf',
                        'png',
                        'ppt',
                        'pptx',
                        'rar',
                        'rtf',
                        'svg',
                        'tga',
                        'tiff',
                        'txt',
                        'xls',
                        'xlsx',
                        'zip',
                        'a3d',
                        'cdt',
                        'cdw',
                        'm3d',
                        'dwf',
                        'cdr',
                        'ai',
                        'ics'
                    );

                    if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                        $json['error'][$i] = $this->language->get('error_filetype1');
                    }

                    // Allowed file mime types
                    $allowed = array(
                        "text/plain",
                        "image/png",
                        "image/jpeg",
                        "image/gif",
                        "image/bmp",
                        "image/tiff",
                        "image/svg+xml",
                        "application/zip",
                        "application/x-zip",
                        "application/x-zip-compressed",
                        "application/rar",
                        "application/x-rar",
                        "application/x-rar-compressed",
                        "application/octet-stream",
                        "audio/mpeg",
                        "video/quicktime",
                        "application/pdf",
                        "application/vnd.ms-excel",
                        "application/msword",
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                        "image/svg+xml",
                        "application/zip",
                        "application/zip",
                        "application/x-zip",
                        "application/x-zip",
                        "application/x-zip-compressed",
                        "application/x-zip-compressed",
                        "application/rar",
                        "application/rar",
                        "application/x-rar",
                        "application/x-rar",
                        "application/x-rar-compressed",
                        "application/x-rar-compressed",
                        "application/octet-stream",
                        "application/octet-stream",
                        "audio/mpeg",
                        "video/quicktime",
                        "application/pdf",
                        "application/vnd.ms-powerpoint",
                        "application/ppt",
                        "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                    );

                    if (!in_array($file['type'], $allowed)) {
                        $json['error'][$i] = $this->language->get('error_filetype2') . $file['type'];
                    }

                    if ($file['size'] > $this->config->get('config_file_max_size')) {
                        $json['error'][$i] = $this->language->get('error_filesize3');
                    }

                    // Return any upload error
                    if ($file['error'] != UPLOAD_ERR_OK) {
                        $json['error'][$i] = $this->language->get('error_upload_' . $file['error']);
                    }
                } else {
                    $json['error'][$i] = $this->language->get('error_upload');
                }

                if (!isset($json['error'])) {
                    move_uploaded_file($file['tmp_name'], $directory . '/' . $newfilename);

                    $attachment_id = $this->model_tool_attachment->addAttachment(array(
                        "src" => 'user' . $this->customer->getId() . '/' . $newfilename,
                        "name" => $filename,
                        "type" => strtolower(utf8_substr(strrchr($filename, '.'), 1)),
                        "size" => $file['size']
                    ));

                    $json['files'][$i] = array(
                        "name" => $filename,
                        "type" => strtolower(utf8_substr(strrchr($filename, '.'), 1)),
                        "size" => format_size($file['size']),
                        "attachment_id" => $attachment_id,
                        'date_added' => date('d.m.Y в H:i'),
                        'href' => $this->url->link('common/download', 'attachment_id=' . $attachment_id),
                        'upload' => $this->url->link('common/download', 'attachment_id=' . $attachment_id),
                    );
                }
            }
        }

        if (!isset($json['error'])) {
            $json['success'] = $this->language->get('text_uploaded');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}