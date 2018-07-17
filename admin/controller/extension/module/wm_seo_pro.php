<?php
class ControllerExtensionModuleWmSeoPro extends Controller {

  private $error = array();

  public function __construct($registry) {
    parent::__construct($registry);

    // WMLicense for wm-product
    $registry->set('wmlicense', new WMLicense($registry, $this->config, $this->cache, $this->db));

    $this->load->model('extension/wm_module/wm_seo_pro');
    $this->load->model('extension/wm_module/wm_validate');

  }

  public function index() {

    $this->load->language('extension/module/wm_seo_pro');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
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

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/module/wm_seo_pro', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

    if ($this->wmlicense->checkLicense('wm_seo_pro_license') && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('module_wm_seo_pro', $this->request->post);
      $this->load->model('extension/wm_module/wm_validate');
      $this->model_extension_wm_module_wm_validate->editSetting('config_wm', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    if (!$this->wmlicense->checkLicense('wm_seo_pro_license')) {

      $data['action'] = $this->url->link('extension/module/wm_seo_pro/editLicense', 'user_token=' . $this->session->data['user_token'], true);

      if (isset($this->request->post['wm_seo_pro_license'])) {
        $data['wm_seo_pro_license'] = $this->request->post['wm_seo_pro_license'];
      } else {
        $data['wm_seo_pro_license'] = $this->config->get('wm_seo_pro_license');
      }

      $data['header']      = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer']      = $this->load->controller('common/footer');

      $this->response->setOutput($this->load->view('extension/module/wm_seo_pro_license', $data));

    } else {

      $data['action'] = $this->url->link('extension/module/wm_seo_pro', 'user_token=' . $this->session->data['user_token'], true);

      if (isset($this->request->post['module_wm_seo_pro_status'])) {
        $data['module_wm_seo_pro_status'] = $this->request->post['module_wm_seo_pro_status'];
      } else {
        $data['module_wm_seo_pro_status'] = $this->config->get('module_wm_seo_pro_status');
      }

      if (isset($this->request->post['config_wm_seo_url'])) {
        $data['config_wm_seo_url'] = $this->request->post['config_wm_seo_url'];
      } else {
        $data['config_wm_seo_url'] = $this->config->get('config_wm_seo_url');
      }

      if (isset($this->request->post['config_wm_seo_url_type'])) {
        $data['config_wm_seo_url_type'] = $this->request->post['config_wm_seo_url_type'];
      } elseif ($this->config->get('config_wm_seo_url_type')) {
        $data['config_wm_seo_url_type'] = $this->config->get('config_wm_seo_url_type');
      } else {
        $data['config_wm_seo_url_type'] = 'wm_seo_url';
      }

      if (isset($this->request->post['config_wm_seo_url_include_path'])) {
        $data['config_wm_seo_url_include_path'] = $this->request->post['config_wm_seo_url_include_path'];
      } else {
        $data['config_wm_seo_url_include_path'] = $this->config->get('config_wm_seo_url_include_path');
      }

      if (isset($this->request->post['config_wm_seo_url_postfix'])) {
        $data['config_wm_seo_url_postfix'] = $this->request->post['config_wm_seo_url_postfix'];
      } else {
        $data['config_wm_seo_url_postfix'] = $this->config->get('config_wm_seo_url_postfix');
      }

      if (isset($this->request->post['config_wm_seo_url_utm'])) {
        $data['config_wm_seo_url_utm'] = $this->request->post['config_wm_seo_url_utm'];
      } else {
        $data['config_wm_seo_url_utm'] = $this->config->get('config_wm_seo_url_utm');
      }

      if (isset($this->request->post['config_wm_seo_url_https'])) {
        $data['config_wm_seo_url_https'] = $this->request->post['config_wm_seo_url_https'];
      } else {
        $data['config_wm_seo_url_https'] = $this->config->get('config_wm_seo_url_https');
      }

      if ($this->config->has('wm_seo_pro_license')) {
        $data['wm_seo_pro_license'] = $this->config->get('wm_seo_pro_license');
      } else {
        $data['wm_seo_pro_license'] = '';
      }


      $data['header']      = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer']      = $this->load->controller('common/footer');

      $this->response->setOutput($this->load->view('extension/module/wm_seo_pro', $data));
    }

  }

  private function validateLicense() {
    return $this->model_extension_wm_module_wm_validate->validateLicense();
  }

  public function activate() {
    return $this->model_extension_wm_module_wm_validate->activateLicense($this->request->post);
  }

  public function editLicense() {
    if (!$this->wmlicense->checkLicense('wm_seo_pro_license') && ($this->request->server['REQUEST_METHOD'] == 'POST') ){
      if (isset($this->request->post['wm_seo_pro_license']) && !isset($this->request->post['email']) && !isset($this->request->post['order_id'])) {
        $this->model_extension_wm_module_wm_validate->editSettingValue('config_wm','wm_seo_pro_license',$this->request->post['wm_seo_pro_license']);
      } else {
        $this->activate();
      }
    }

    $this->response->redirect($this->url->link('extension/module/wm_seo_pro', 'user_token=' . $this->session->data['user_token'], true));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'extension/module/wm_seo_pro')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

  public function install() {
    if ($this->validate()) {
      $this->load->model('extension/wm_module/wm_seo_pro');
      $this->model_extension_wm_module_wm_seo_pro->install();
    }
  }

  public function uninstall() {

  }

}
