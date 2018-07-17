<?php
class ModelExtensionWmModuleWmValidate extends Model {
  public function activateLicense($data) {

    $domain_key = $this->wmlicense->getDomainKey($data);

    if ($domain_key != false) {
      $this->editSettingValue('config_wm','wm_seo_pro_license',$domain_key);
    }
  }

  public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0) {
    $this->db->query("DELETE FROM ".DB_PREFIX . "setting WHERE `code` = 'config_wm' AND `key` = 'wm_seo_pro_license'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "', `serialized` = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `store_id` = '" . (int)$store_id . "'");
  }

  public function editSetting($code, $data, $store_id = 0) {
    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "' AND `key` <> 'wm_seo_pro_license'");

    foreach ($data as $key => $value) {
      if (substr($key, 0, strlen($code)) == $code) {
        if (!is_array($value)) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
        } else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
        }
      }
    }
  }
}