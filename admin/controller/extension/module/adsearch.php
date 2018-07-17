<?php
/*
 * @copyright Copyright (c) Shilovsky Andrej (an911@ukr.net)
 * http://quartzstore.com
 */ 
class ControllerExtensionModuleAdsearch extends Controller {
	private $error = array();

	public function index() {
                $this->load->language('extension/module/adsearch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                  
			$this->model_setting_setting->editSetting('module_adsearch', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['adsearch_width'])) {
			$data['error_adsearch_width'] = $this->error['adsearch_width'];
		} else {
			$data['error_adsearch_width'] = '';
		}

		if (isset($this->error['adsearch_height'])) {
			$data['error_adsearch_height'] = $this->error['adsearch_height'];
		} else {
			$data['error_adsearch_height'] = '';
		}

  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);


   		$data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true)
   		);

   		$data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/adsearch', 'user_token=' . $this->session->data['user_token'], true)
   		);

                
		$data['action'] = $this->url->link('extension/module/adsearch', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
		
		$data['token'] = $this->session->data['user_token'];

                
		if (isset($this->request->post['module_adsearch_keywords'])) {
			$data['module_adsearch_keywords'] = $this->request->post['module_adsearch_keywords'];
		} else {
			$data['module_adsearch_keywords'] = $this->config->get('module_adsearch_keywords');
		}	

		if (isset($this->request->post['module_adsearch_model'])) {
			$data['module_adsearch_model'] = $this->request->post['module_adsearch_model'];
		} else {
			$data['module_adsearch_model'] = $this->config->get('module_adsearch_model');
		}	
				
		if (isset($this->request->post['module_adsearch_category'])) {
			$data['module_adsearch_category'] = $this->request->post['module_adsearch_category'];
		} else {
			$data['module_adsearch_category'] = $this->config->get('module_adsearch_category');
		}	
		
		if (isset($this->request->post['module_adsearch_category_view'])) {
			$data['module_adsearch_category_view'] = $this->request->post['module_adsearch_category_view'];
		} else {
			$data['module_adsearch_category_view'] = $this->config->get('module_adsearch_category_view');
		}
						
		if (isset($this->request->post['module_adsearch_brend'])) {
			$data['module_adsearch_brend'] = $this->request->post['module_adsearch_brend'];
		} else {
			$data['module_adsearch_brend'] = $this->config->get('module_adsearch_brend');
		}	
          	
		if (isset($this->request->post['module_adsearch_price'])) {
			$data['module_adsearch_price'] = $this->request->post['module_adsearch_price'];
		} else {
			$data['module_adsearch_price'] = $this->config->get('module_adsearch_price');
		}	
				
		if (isset($this->request->post['module_adsearch_countproduct'])) {
			$data['module_adsearch_countproduct'] = $this->request->post['module_adsearch_countproduct'];
		} else {
			$data['module_adsearch_countproduct'] = $this->config->get('module_adsearch_countproduct');
		}	
				
		if (isset($this->request->post['module_adsearch_showterms'])) {
			$data['module_adsearch_showterms'] = $this->request->post['module_adsearch_showterms'];
		} else {
			$data['module_adsearch_showterms'] = $this->config->get('module_adsearch_showterms');
		}	
                		
		if (isset($this->request->post['module_adsearch_viewproduct'])) {
			$data['module_adsearch_viewproduct'] = $this->request->post['module_adsearch_viewproduct'];
		} else {
			$data['module_adsearch_viewproduct'] = $this->config->get('module_adsearch_viewproduct');
		}				

		if (isset($this->request->post['module_adsearch_status'])) {
			$data['module_adsearch_status'] = $this->request->post['module_adsearch_status'];
		} else {
			$data['module_adsearch_status'] = $this->config->get('module_adsearch_status');
		}

		if (isset($this->request->post['module_adsearch_width'])) {
			$data['module_adsearch_width'] = $this->request->post['module_adsearch_width'];
		} else {
			$data['module_adsearch_width'] = $this->config->get('module_adsearch_width');
		} 
      
		if (isset($this->request->post['module_adsearch_height'])) {
			$data['module_adsearch_height'] = $this->request->post['module_adsearch_height'];
		} else {
			$data['module_adsearch_height'] = $this->config->get('module_adsearch_height');
		}
                  
             $this->load->model('localisation/stock_status');               
             $data['stockstatus']  = $this->model_localisation_stock_status->getStockStatuses();  
          
                $data['box_type'] = array('select','checkbox','radio'); 
                
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/adsearch', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/adsearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_adsearch_width']) {
			$this->error['warning'] = $this->language->get('error_adsearch_width');
		}

		if (!$this->request->post['module_adsearch_height']) {
			$this->error['warning'] = $this->language->get('error_adsearch_height');
		}
                	
		return !$this->error;
	}       

}