<?php
class ControllerExtensionModuleCategory extends Controller {
	public function index() {
		$this->load->language('extension/module/category');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$data['title_1'] = 'Каталог товаров';
        $data['title_2'] = 'Каталог услуг';

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
            if (!$category['no_left_menu']) {
                $children_data = array();

                if ($category['category_id'] == $data['category_id']) {
                    $children = $this->model_catalog_category->getCategories($category['category_id']);

                    foreach ($children as $child) {
                        if (!$child['no_left_menu']) {
                            $filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

                            $children_data[] = array(
                                'category_id' => $child['category_id'],
                                'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                                'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                            );
                        }
                    }
                }

                $filter_data = array(
                    'filter_category_id' => $category['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'category_id' => $category['category_id'],
                    'name' => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'children' => $children_data,
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }

        $data['text_printer'] = 'Ремонт принтеров';
        $data['text_monitor'] = 'Ремонт мониторов';
        $data['text_proekt'] = 'Ремонт проекторов';
        $data['text_skaner'] = 'Ремонт сканеров';

        $data['url_printer'] = $this->url->link('information/information', 'information_id=8');
        $data['url_monitor'] = $this->url->link('information/information', 'information_id=12');
        $data['url_proekt'] = $this->url->link('information/information', 'information_id=13');
        $data['url_skaner'] = $this->url->link('information/information', 'information_id=14');

		return $this->load->view('extension/module/category', $data);
	}
}