<?php // ==========================================  seo_url.php v.140618 opencart-russia.ru ===============================
class ControllerStartupSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class

		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($part) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'customer_id') {
						$this->request->get['customer_id'] = $url[1];
					}

					if ($url[0] == 'order_id') {
						$this->request->get['order_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}

					if ($query->row['query'] && $url[0] != 'order_id' && $url[0] != 'customer_id' && $url[0] != 'information_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {
						$this->request->get['route'] = $query->row['query'];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['order_id'])) {
					$this->request->get['route'] = 'order/order/info';
				} elseif (isset($this->request->get['customer_id'])) {
					$this->request->get['route'] = 'account/customer';
				} elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				}
			}

		// Redirect 301   
		} elseif (isset($this->request->get['route']) && empty($this->request->post) && !isset($this->request->get['token']) && $this->config->get('config_seo_url')) {
			$arg = '';
			$cat_path = false;
			$route = $this->request->get['route'];

			if ($this->request->get['route'] == 'order/order' || $this->request->get['route'] == 'account/order') {
				$route = $this->request->get['route'];

                if (isset($this->request->get['page'])) $arg = $arg . '?page=' . (int)$this->request->get['page'];
                if (isset($this->request->get['search'])) $arg = $arg . ($arg ? '&': '?') . 'search=' . $this->request->get['search'];
                if (isset($this->request->get['filter_work_type_id'])) $arg = $arg . ($arg ? '&': '?') . 'filter_work_type_id=' . (int)$this->request->get['filter_work_type_id'];
                if (isset($this->request->get['filter_section_id'])) $arg = $arg . ($arg ? '&': '?') . 'filter_section_id=' . (int)$this->request->get['filter_section_id'];
                if (isset($this->request->get['filter_subject_id'])) $arg = $arg . ($arg ? '&': '?') . 'filter_subject_id=' . (int)$this->request->get['filter_subject_id'];
                if (isset($this->request->get['filter_customer'])) $arg = $arg . ($arg ? '&': '?') . 'filter_customer=' . $this->request->get['filter_customer'];
                if (isset($this->request->get['filter_order_status_id'])) $arg = $arg . ($arg ? '&': '?') . 'filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
                if (isset($this->request->get['filter_no_offer'])) $arg = $arg . ($arg ? '&': '?') . 'filter_no_offer=' . (int)$this->request->get['filter_no_offer'];
                if (isset($this->request->get['filter_my_specialization'])) $arg = $arg . ($arg ? '&': '?') . 'filter_my_specialization=' . (int)$this->request->get['filter_my_specialization'];
                if (isset($this->request->get['filter_my_work_type'])) $arg = $arg . ($arg ? '&': '?') . 'filter_my_work_type=' . (int)$this->request->get['filter_my_work_type'];

			} elseif ($this->request->get['route'] == 'product/category' && isset($this->request->get['path'])) {
				$categorys_id = explode('_', $this->request->get['path']);
				$cat_path = '';
				foreach ($categorys_id as $category_id) {
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `query` = 'category_id=" . (int)$category_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");   
					if ($query->num_rows && $query->row['keyword'] /**/ ) {
						$cat_path .= '/' . $query->row['keyword'];
					} else {
						$cat_path = false;
						break;
					}
				}
				$arg = trim($cat_path, '/');
				if (isset($this->request->get['page'])) $arg = $arg . '?page=' . (int)$this->request->get['page'];
			} elseif ($this->request->get['route'] == 'order/order/info' && isset($this->request->get['order_id'])) {
				$route = 'order_id=' . (int)$this->request->get['order_id'];
			} elseif ($this->request->get['route'] == 'order/order/edit' && isset($this->request->get['order_id'])) {
				$route = 'order_id=' . (int)$this->request->get['order_id'];
			} elseif ($this->request->get['route'] == 'information/information' && isset($this->request->get['information_id'])) {
				$route = 'information_id=' . (int)$this->request->get['information_id'];
			} elseif (sizeof($this->request->get) > 1) {
				$args = '?' . str_replace("route=" . $this->request->get['route'].'&amp;', "", $this->request->server['QUERY_STRING']);
				$arg = str_replace('&amp;', '&', $args);
			}

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `query` = '" . $this->db->escape($route) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

			if (!empty($query->num_rows) && !empty($query->row['keyword']) && $route) {
				$this->response->redirect($query->row['keyword'] . $arg, 301);
			} elseif ($cat_path) {
				$this->response->redirect($arg, 301);
			} elseif ($this->request->get['route'] == 'common/home') {
				$this->response->redirect(HTTP_SERVER . $arg, 301);
			}
		}

	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (
                    ($data['route'] == 'order/order/info' && $key == 'order_id') ||
                    ($data['route'] == 'order/order/edit' && $key == 'order_id') ||
                    ($data['route'] == 'account/customer' && $key == 'customer_id') ||
                    ($data['route'] == 'information/information' && $key == 'information_id')
                ) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'category_id=" . (int)$category . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				} elseif ($key == 'route') {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
					if ($query->num_rows) /**/ {
						$url .= '/' . $query->row['keyword'];
					}
				}
			}
		}

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
