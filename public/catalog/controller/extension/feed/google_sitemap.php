<?php

class ControllerExtensionFeedGoogleSitemap extends Controller
{
    public function index()
    {
        $this->load->model('tool/image');

        $filename = DIR_APPLICATION . 'sitemap.txt';

        $lastmodify = filectime($filename);

        if (!$lastmodify) {
            $lastmodify = time();
        }

        /*if (time() - 86400 > $lastmodify) {

            $output = file_get_contents($filename);

            $this->response->addHeader('Content-Type: application/xml');
            $this->response->setOutput($output);
        }*/

        $output = '<?xml version="1.0" encoding="UTF-8"?>';
        $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // Home
        $output .= $this->getUrl(HTTPS_SERVER, time());

        // Landings
        $landings = $this->getLandingPages();

        foreach ($landings as $row) {
            $output .= $this->getUrl(HTTPS_SERVER . 'new-order/' . $row['slug'], strtotime($row['updated_at']));
        }

        // SubjectLandings
        $landings = $this->getSubjectLandingPages();

        foreach ($landings as $row) {
            $output .= $this->getUrl(HTTPS_SERVER . 'new-order/' . $row['parent_slug'] . '/' . $row['slug'], strtotime($row['updated_at']));
        }

        // BlogCategories
        $blog_categories = $this->getBlogCategories();

        foreach ($blog_categories as $row) {
            $output .= $this->getUrl(HTTPS_SERVER . 'blog/' . $row['slug'], strtotime($row['updated_at']));
        }

        // BlogPosts
        $blog_posts = $this->getBlogPosts();

        foreach ($blog_posts as $row) {
            $output .= $this->getUrl(HTTPS_SERVER . 'blog/post/' . $row['slug'], strtotime($row['updated_at']));
        }

        // Services
        $output .= $this->getUrl($this->url->link('services/services'), time());

        // Faq
        $output .= $this->getUrl($this->url->link('support/faq'), time());

        // Orders
        $result = $this->db->query("SELECT order_id, date_added, date_modified FROM `" . DB_PREFIX . "order` WHERE status = 1 ORDER BY date_added DESC");

        if ($result->row['date_modified'] !== '0000-00-00 00:00:00') {
            $lastmod = strtotime($result->row['date_modified']);
        } elseif ($result->row['date_added'] > 0) {
            $lastmod = $result->row['date_added'];
        } else {
            $lastmod = time() - 86400;
        }

        $output .= $this->getUrl(HTTP_SERVER . 'orders', strtotime($lastmod));

        foreach ($result->rows as $row) {
            if ($row['date_modified'] != '0000-00-00 00:00:00') {
                $lastmod = strtotime($row['date_modified']);
            } elseif ($row['date_added'] > 0) {
                $lastmod = $row['date_added'];
            } else {
                $lastmod = time() - 86400;
            }

            $output .= $this->getUrl($this->url->link('order/order/info', 'order_id=' . (int)$row['order_id']), $lastmod);
        }

        // User
        $result = $this->db->query("SELECT customer_id, last_seen FROM " . DB_PREFIX . "customer WHERE status = 1 ORDER BY date_added DESC");

        foreach ($result->rows as $row) {
            $output .= $this->getUrl($this->url->link('account/customer', 'customer_id=' . (int)$row['customer_id']), $row['last_seen']);
        }

        $output .= '</urlset>';

        file_put_contents($filename, $output);


        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($output);
    }

    protected function getUrl($url, $last_modify)
    {
        if (!$last_modify) $last_modify = time();

        $output = '<url>';
        $output .= '<loc>' . $url . '</loc>';
        $output .= '<lastmod>' . date('Y-m-d', $last_modify) . '</lastmod>';
        $output .= '</url>';

        return $output;
    }

    protected function getLandingPages()
    {
        $db = new DB(
            L_DB_CONNECTION,
            L_DB_HOST,
            L_DB_USERNAME,
            L_DB_PASSWORD,
            L_DB_DATABASE,
            L_DB_PORT
        );

        $result = $db->query("SELECT id, slug, updated_at FROM landings WHERE parent_id = 0 AND status = 1 ORDER BY created_at DESC");

        return $result->rows;
    }

    protected function getSubjectLandingPages()
    {
        $db = new DB(
            L_DB_CONNECTION,
            L_DB_HOST,
            L_DB_USERNAME,
            L_DB_PASSWORD,
            L_DB_DATABASE,
            L_DB_PORT
        );

        $result = $db->query("SELECT l.id, l.slug, l.updated_at, (SELECT l1.slug FROM landings l1 WHERE l1.id = l.parent_id) AS parent_slug FROM landings l WHERE l.parent_id > 0 AND l.status = 1 ORDER BY l.created_at DESC");

        return $result->rows;
    }

    protected function getBlogPosts()
    {
        $db = new DB(
            L_DB_CONNECTION,
            L_DB_HOST,
            L_DB_USERNAME,
            L_DB_PASSWORD,
            L_DB_DATABASE,
            L_DB_PORT
        );

        $result = $db->query("SELECT id, slug, updated_at FROM blog_posts WHERE status = 1 AND publish_at < " . $db->escape(date('Y-m-d H:i:s')) . " ORDER BY created_at DESC");

        return $result->rows;
    }

    protected function getBlogCategories()
    {
        $db = new DB(
            L_DB_CONNECTION,
            L_DB_HOST,
            L_DB_USERNAME,
            L_DB_PASSWORD,
            L_DB_DATABASE,
            L_DB_PORT
        );

        $result = $db->query("SELECT id, slug, updated_at FROM blog_categories WHERE status = 1 ORDER BY created_at DESC");

        return $result->rows;
    }

}
