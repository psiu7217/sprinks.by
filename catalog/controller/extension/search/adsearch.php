<?php
/*
 * @copyright Copyright (c) Shilovsky Andrej (an911@ukr.net)
 * http://quartzstore.com
 */ 
class ControllerExtensionSearchAdsearch extends Controller {
	public function index() {
		$this->load->language('extension/search/adsearch');

		$this->load->model('catalog/category');

		$this->load->model('extension/module/adsearch');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_tag'])) {
			$filter_tag = $this->request->get['filter_tag'];
		} elseif (isset($this->request->get['filter_name'])) {
			$filter_tag = $this->request->get['filter_name'];
		} else {
			$filter_tag = '';
		}

		if (isset($this->request->get['filter_description'])) {
			$filter_description = 1;
		} else {
			$filter_description = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = 0;
		}

		if (isset($this->request->get['filter_sub_category'])) {
			$filter_sub_category = 1;
		} else {
			$filter_sub_category = '';
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = '';
		}

		if (isset($this->request->get['filter_pricemin'])) {
			$filter_pricemin = $this->request->get['filter_pricemin'];
		} else {
			$filter_pricemin = '';
		}

		if (isset($this->request->get['filter_pricemax'])) {
			$filter_pricemax = $this->request->get['filter_pricemax'];
		} else {
			$filter_pricemax = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		if (isset($filter_name)) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $filter_name);
		} elseif (isset($filter_tag)) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $filter_tag);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (!empty($filter_name)) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
		}

		if (!empty($filter_tag)) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
		}

		if (!empty($filter_description)) {
			$url .= '&filter_description=' . $filter_description;
		}

		if (!empty($filter_model)) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($filter_model, ENT_QUOTES, 'UTF-8'));
		}

		if (!empty($filter_category_id)) {
			$url .= '&filter_category_id=' . $filter_category_id;
		}

		if (!empty($filter_sub_category)) {
			$url .= '&filter_sub_category=' . $filter_sub_category;
		}

		if (!empty($filter_manufacturer_id)) {
                    foreach ($filter_manufacturer_id as $value) { 
			$url .= '&filter_manufacturer_id[]=' . $value;
                    }    
		}

		if (!empty($filter_pricemin)) {
			$url .= '&filter_pricemin=' . $filter_pricemin;
		}

		if (!empty($filter_pricemax)) {
			$url .= '&filter_pricemax=' . $filter_pricemax;
		}

		if (!empty($sort)) {
			$url .= '&sort=' . $sort;
		}

		if (!empty($order)) {
			$url .= '&order=' . $order;
		}

		if (!empty($page)) {
			$url .= '&page=' . $page;
		}

		if (!empty($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/search/adsearch', $url)
		);

		if (isset($this->request->get['search'])) {
			$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}


		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));


		$data['compare'] = $this->url->link('product/compare');

		$this->load->model('catalog/category');

		// 3 Level Category Search
		$data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}

		$data['products'] = array();

		if (isset($filter_name) || 
                    isset($filter_tag) || 
                    isset($filter_model) ||     
                    isset($filter_category_id) ||
                    isset($filter_sub_category) ||
                    isset($filter_manufacturer_id) ||
                    isset($filter_pricemin) ||
                    isset($filter_pricemax) ||
                    isset($filter_description)) {
			$filter_data = array(
				'filter_name'         => $filter_name,
				'filter_tag'          => $filter_tag,
				'filter_description'  => $filter_description,
				'filter_model'  => $filter_model,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'filter_manufacturer_id' => $filter_manufacturer_id,
				'filter_pricemin' => ($filter_pricemin ? (float)($this->currency->convert($filter_pricemin, $this->session->data['currency'], $this->config->get('config_currency'))):''),
				'filter_pricemax' => ($filter_pricemax ? (float)($this->currency->convert($filter_pricemax, $this->session->data['currency'], $this->config->get('config_currency'))):''),
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
                
			$product_total = $this->model_extension_module_adsearch->getTotalProducts($filter_data);
                 
			$results = $this->model_extension_module_adsearch->getProducts($filter_data);
       
			foreach ($results as $result) {
				if ($result['image']) {
				        $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
				        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
                                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
                                        $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}


				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => mb_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '...',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (!empty($filter_name)) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_tag)) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_description)) {
				$url .= '&filter_description=' . $filter_description;
			}

			if (!empty($filter_model)) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($filter_model, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_category_id)) {
				$url .= '&filter_category_id=' . $filter_category_id;
			}

			if (!empty($filter_sub_category)) {
				$url .= '&filter_sub_category=' . $filter_sub_category;
			}
           
		if (!empty($filter_manufacturer_id)) {
                    foreach ($filter_manufacturer_id as $value) { 
			$url .= '&filter_manufacturer_id[]=' . $value;
                    }    
		}
            
                        if (!empty($filter_pricemin)) {
                                $url .= '&filter_pricemin=' . $filter_pricemin;
                        }

                        if (!empty($filter_pricemax)) {
                                $url .= '&filter_pricemax=' . $filter_pricemax;
                        }
                        
			if (!empty($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('extension/search/adsearch', 'sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('extension/search/adsearch', 'sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('extension/search/adsearch', 'sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (!empty($filter_name)) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_tag)) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_description)) {
				$url .= '&filter_description=' . $filter_description;
			}

			if (!empty($filter_model)) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($filter_model, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_category_id)) {
				$url .= '&filter_category_id=' . $filter_category_id;
			}

			if (!empty($filter_sub_category)) {
				$url .= '&filter_sub_category=' . $filter_sub_category;
			}
           
		if (!empty($filter_manufacturer_id)) {
                    foreach ($filter_manufacturer_id as $value) { 
			$url .= '&filter_manufacturer_id[]=' . $value;
                    }    
		}
            
                        if (!empty($filter_pricemin)) {
                                $url .= '&filter_pricemin=' . $filter_pricemin;
                        }

                        if (!empty($filter_pricemax)) {
                                $url .= '&filter_pricemax=' . $filter_pricemax;
                        }
                        
                                               
			if (!empty($sort)) {
				$url .= '&sort=' . $sort;
			}

			if (!empty($order)) {
				$url .= '&order=' . $order;
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('extension/search/adsearch', $url . '&limit=' . $value)
				);
			}


			$url = '';

			if (!empty($filter_name)) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_tag)) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}

			if (!empty($filter_description)) {
				$url .= '&filter_description=' . $filter_description;
			}

			if (!empty($filter_model)) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($filter_model, ENT_QUOTES, 'UTF-8'));
			}
                        
			if (!empty($filter_category_id)) {
				$url .= '&filter_category_id=' . $filter_category_id;
			}

			if (!empty($filter_sub_category)) {
				$url .= '&filter_sub_category=' . $filter_sub_category;
			}
           
		if (!empty($filter_manufacturer_id)) {
                    foreach ($filter_manufacturer_id as $value) { 
			$url .= '&filter_manufacturer_id[]=' . $value;
                    }    
		}
            
                        if (!empty($filter_pricemin)) {
                                $url .= '&filter_pricemin=' . $filter_pricemin;
                        }

                        if (!empty($filter_pricemax)) {
                                $url .= '&filter_pricemax=' . $filter_pricemax;
                        }
                        
			if (!empty($sort)) {
				$url .= '&sort=' . $sort;
			}

			if (!empty($order)) {
				$url .= '&order=' . $order;
			}

			if (!empty($this->request->post['limit'])) {
				$url .= '&limit=' . $this->request->post['limit'];
			}
                        
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/search/adsearch', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$this->document->addLink($this->url->link('extension/search/adsearch', $url . '&page='. $pagination->page), 'canonical');

			if ($pagination->limit && ceil($pagination->total / $pagination->limit) > $pagination->page) {
				$this->document->addLink($this->url->link('extension/search/adsearch', $url . '&page='. ($pagination->page + 1)), 'next');
			}

			if ($pagination->page > 1) {
				$this->document->addLink($this->url->link('extension/search/adsearch', $url . '&page='. ($pagination->page - 1)), 'prev');
			}

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
		}

		$data['filter_name'] = $filter_name;
		$data['filter_tag'] = $filter_tag;
		$data['filter_description'] = $filter_description;
		$data['filter_model'] = $filter_model;
		$data['filter_category_id'] = $filter_category_id;
		$data['filter_sub_category'] = $filter_sub_category;
		$data['filter_manufacturer_id'] = $filter_manufacturer_id;
		$data['filter_pricemin'] = $filter_pricemin;
		$data['filter_pricemax'] = $filter_pricemax;

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


            	$this->response->setOutput($this->load->view('extension/search/adsearch', $data));                
	}
        
}