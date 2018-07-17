<?php
class ControllerExtensionModuleLeftMenu extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/left_menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('left_menu', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}




		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/left_menu', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/left_menu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/left_menu', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/left_menu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}




        if (isset($this->request->post['title_1'])) {
            $data['title_1'] = $this->request->post['title_1'];
        } elseif (!empty($module_info)) {
            $data['title_1'] = $module_info['title_1'];
        } else {
            $data['title_1'] = '';
        }

        if (isset($this->request->post['title_2'])) {
            $data['title_2'] = $this->request->post['title_2'];
        } elseif (!empty($module_info)) {
            $data['title_2'] = $module_info['title_2'];
        } else {
            $data['title_2'] = '';
        }

		if (isset($this->request->post['items_1'])) {
			$data['items_1'] = $this->request->post['items_1'];
		} elseif (!empty($module_info)) {
			$data['items_1'] = $module_info['items_1'];
		} else {
			$data['items_1'] = '';
		}

        if (isset($this->request->post['items_2'])) {
            $data['items_2'] = $this->request->post['items_2'];
        } elseif (!empty($module_info)) {
            $data['items_2'] = $module_info['items_2'];
        } else {
            $data['items_2'] = '';
        }








		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/left_menu', $data));
	}
}