<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		//Стандартный код получения категорий
		/*
		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		*/

		$data['home_text'] = 'Главная';
        $data['catalog_text'] = 'Каталог товаров';
        $data['servis_text'] = 'Сервисный центр';
        $data['about_text'] = 'О компании';
        $data['articles_text'] = 'Статьи';
        $data['pay_text'] = 'Оплата и доставка';
        $data['contact_text'] = 'Контакты';

        $data['home_url'] = $this->url->link('common/home');
        $data['catalog_url'] = $this->url->link('product/category', 'path=77');
        $data['servis_url'] = $this->url->link('information/information', 'information_id=6');
        $data['about_url'] = $this->url->link('information/information', 'information_id=4');
        $data['articles_url'] = $this->url->link('blog/category', 'blogpath=2');
        $data['pay_url'] = $this->url->link('information/information', 'information_id=18');
        $data['contact_url'] = $this->url->link('information/information', 'information_id=19');

		return $this->load->view('common/menu', $data);
	}
}
