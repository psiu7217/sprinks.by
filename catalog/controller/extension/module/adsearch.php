<?php
/*
 * @copyright Copyright (c) Shilovsky Andrej (an911@ukr.net)
 * http://quartzstore.com
 */ 
class ControllerExtensionModuleAdsearch extends Controller {

    protected $category_id = 0;
    protected $path = array();

    public function index($setting) {
		$this->load->language('extension/module/adsearch');
                $this->load->model('extension/module/adsearch');
                 
                if(isset($this->request->get["route"])) {
                   $route = $this->request->get["route"];
                } else {
                   $route = 'common/home'; 
                }
                
		$data['position'] = $this->model_extension_module_adsearch->getLayoutModulePosition($route);
                
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		} else {
			$category_id = 0;
		}    
                
		$data['module_adsearch_showterms'] = $this->config->get("module_adsearch_showterms");   
		$data['module_adsearch_keywords'] = $this->config->get("module_adsearch_keywords"); 
		$data['module_adsearch_model'] = $this->config->get("module_adsearch_model");   
		$data['module_adsearch_category'] = $this->config->get("module_adsearch_category");
		$data['module_adsearch_brend'] = $this->config->get("module_adsearch_brend");
		$data['module_adsearch_price'] = $this->config->get("module_adsearch_price");
                $data['module_adsearch_countproduct'] = $this->config->get('module_adsearch_countproduct');
		$data['module_adsearch_viewproduct'] = $this->config->get("module_adsearch_viewproduct");
       

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_tag'])) {
                    $filter_tag = $this->request->get['filter_tag'];
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
                    $filter_category_id = ($this->config->get('adsearch_category_view')) ? $category_id : 0;
                }

                if (isset($this->request->get['filter_sub_category'])) {
                    $filter_sub_category = 1;
                } else {
                    $filter_sub_category = '';
                }

                if (isset($this->request->get['filter_manufacturer_id'])) {
                    $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
                } else {
                    $filter_manufacturer_id = array();
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

      
            if(!$this->config->get("module_adsearch_showterms")) {               
                    $filter_name = '';
                    $filter_model = '';
                    $filter_tag = '';
                    $filter_description = '';
                    $filter_category_id = 0;
                    $filter_sub_category = '';
                    $filter_manufacturer_id = '';
                    $filter_pricemin = '';
                    $filter_pricemax = '';
                    $filter_groups = '';
                    $filter_options = '';
            }
            


        $data['button_search'] = $this->language->get('button_search');
        
        $data['action'] = $this->url->link('product/adsearch', '', 'SSL');
        $data['waitload'] = HTTP_SERVER . "catalog/view/theme/default/image/ajax_load.gif";


        $data['categories'] = array();
        $data['categories'] = $this->getCategoriesSelect(0);

        $data_info['filter_category_id'] = $filter_category_id;
        $data_info['filter_sub_category'] = $filter_sub_category;
        

        // manufacturers               
        $data['manufacturers'] = '';
        $data['manufacturers'] = $this->manufacturers($data_info);
        

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_category_id'] = $filter_category_id;
        $data['filter_sub_category'] = $filter_sub_category;
        $data['filter_manufacturer_id'] = $filter_manufacturer_id;
        $data['filter_pricemin'] = $filter_pricemin;
        $data['filter_pricemax'] = $filter_pricemax;
        $data['filter_description'] = $filter_description;
       
        
            return $this->load->view('extension/module/adsearch', $data);     
    }

    private function getCategoriesSelect($parent_id, $level = 0) {
        $level++;
        $data = array();
        $results = $this->model_extension_module_adsearch->getCategories($parent_id);

        foreach ($results as $result) {
            $data[] = array(
                'category_id' => $result['category_id'],
                'name' => str_repeat('&nbsp;&nbsp;', $level) . $result['name'] . (($this->config->get("module_adsearch_countproduct")) ? ' (' . $result['countproduct'] . ')' : '')
            );
            $children = $this->getCategoriesSelect($result['category_id'], $level);
            if ($children) {
                $data = array_merge($data, $children);
            }
        }
        return $data;
    }

                
        private function manufacturers($data_info) {
                $this->load->model('tool/image');
                $results = $this->model_extension_module_adsearch->getManufacturers($data_info);
         
            foreach ($results as $key=>$manufacturer) {
                if ($this->config->get("module_adsearch_countproduct")) {
                   $results[$key]['name'] = $manufacturer['name'] . ' (' . $manufacturer['countproduct'] . ')'; 
                }
                
                if ($manufacturer['image']) {
                        $results[$key]['image'] = $this->model_tool_image->resize($manufacturer['image'], $this->config->get('module_adsearch_width'), $this->config->get('module_adsearch_height'));
                } else {
                        $results[$key]['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_adsearch_width'), $this->config->get('module_adsearch_height'));
                }            
            }            
                RETURN $results;
        }      
    
 
       
	public function search() {
		
                $this->language->load('extension/module/adsearch');
                $this->load->model('extension/module/adsearch');
              
                $json = array();   	
          	
			$data_info = array(
				'filter_category_id'         => isset($this->request->post['filter_category_id'])? $this->request->post['filter_category_id'] : false,
				'filter_sub_category'        => isset($this->request->post['filter_sub_category'])? $this->request->post['filter_sub_category'] : false
			);
                                      
                        if($this->config->get("module_adsearch_brend")) {
                            $json['manufacturer'] = $this->manufacturers($data_info);
                        }

		$this->response->setOutput(json_encode($json));
	}         
        
        
	public function adsearchautocomplete() {
		$json = array();
          	
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/module/adsearch');
			$this->load->model('tool/image');
                        
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_description'])) {
				$filter_description = $this->request->get['filter_description'];	
			} else {
				$filter_description = '';	
			}			
								
			$data = array(
				'filter_name'         => $filter_name,
				'filter_description'  => $filter_description
			);
                     		
			$results = $this->model_extension_module_adsearch->getAutocomplete($data);
			
			foreach ($results as $result) {
                           	if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
				} 
                                
                                         
				$json[] = array(
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),		
					'image'      => $image
				);	
			}
                        
		}
                
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);                
                
		$this->response->setOutput(json_encode($json));
	}
        
        
	public function autocompletemodel() {
		$json = array();
		
		if (isset($this->request->get['filter_model'])) {
			$this->load->model('extension/module/adsearch');
			$this->load->model('tool/image');
                        					
			$data = array(
				'filter_model'         => $this->request->get['filter_model']
			);
                     		
			$results = $this->model_extension_module_adsearch->getAutocomplete($data);
			
			foreach ($results as $result) {
                           	if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
				} 
                                
                                         
				$json[] = array(
					'model'       => strip_tags(html_entity_decode($result['model'], ENT_QUOTES, 'UTF-8')),		
					'image'      => $image
				);	
			}
                        
		}
                
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['model'];
		}

		array_multisort($sort_order, SORT_ASC, $json);                
                
		$this->response->setOutput(json_encode($json));
	}        
}