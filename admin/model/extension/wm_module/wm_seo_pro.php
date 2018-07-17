<?php
class ModelExtensionWmModuleWmSeoPro extends Model {
  private $support_email = 'web-mehanik@yandex.ru';

  public function install (){

    $subject = 'Install WMSeoPRO';
    $line = "\r\n";
    $message  = 'Install info:'.$line;
    $message .= 'Domain: '.$_SERVER['SERVER_NAME'].$line;
    $message .= 'IP: '.$_SERVER['REMOTE_ADDR'].$line;
    $message .= 'Date: '.date("F j, Y, g:i a").$line;
    @mail($this->support_email, $subject, $message);

    $field_name = "main_category";
    $field_type = "tinyint(1) COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $field_position = "AFTER `category_id`";
    $table_name = "product_to_category";
    $columns = array();

    $query = $this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'config_wm', 'config_wm_seo_url_utm', 'block\r\nfrommarket\r\ngclid\r\nkeyword\r\nlist_type\r\nopenstat\r\nopenstat_service\r\nopenstat_campaign\r\nopenstat_ad\r\nopenstat_source\r\nposition\r\nsource\r\ntracking\r\ntype\r\nyclid\r\nymclid\r\nuri\r\nurltype\r\nutm_source\r\nutm_medium\r\nutm_campaign\r\nutm_content\r\nutm_term', 0)");


    $query = $this->db->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND TABLE_NAME = '" . DB_PREFIX .$table_name."';");
    foreach ($query->rows as $value) {
      $columns[] = $value['column_name'];
    }

    if (!in_array($field_name, $columns)) {
      $this->db->query("ALTER TABLE " . DB_PREFIX .$table_name." ADD ".$field_name." ".$field_type." ".$field_position.";");


      /*$seourl = array(
        'account/wishlist'       => 'wishlist',
        'account/account'        => 'account',
        'checkout/cart'          => 'cart',
        'checkout/checkout'      => 'checkout',
        'account/login'          => 'login',
        'account/logout'         => 'logout',
        'account/order'          => 'order-history',
        'account/order/info'     => 'order-information',
        'account/newsletter'     => 'newsletter',
        'product/special'        => 'specials',
        'affiliate/account'      => 'affiliates',
        'account/voucher'        => 'gift-vouchers',
        'account/recurring'      => 'recurring-payments',
        'product/manufacturer'   => 'brands',
        'information/contact'    => 'contact-us',
        'account/return/add'     => 'request-return',
        'information/sitemap'    => 'sitemap',
        'account/forgotten'      => 'forgot-password',
        'account/download'       => 'downloads',
        'account/return'         => 'returns',
        'account/transaction'    => 'transactions',
        'account/register'       => 'create-account',
        'product/compare'        => 'compare-products',
        'product/search'         => 'search',
        'account/edit'           => 'edit-account',
        'account/password'       => 'change-password',
        'account/address'        => 'address-book',
        'account/address/edit'   => 'edit-address',
        'account/address/add'    => 'add-address',
        'account/address/delete' => 'delete-address',
        'account/reward'         => 'reward-points',
        'affiliate/edit'         => 'edit-affiliate-account',
        'affiliate/password'     => 'change-affiliate-password',
        'affiliate/payment'      => 'affiliate-payment-options',
        'affiliate/tracking'     => 'affiliate-tracking-code',
        'affiliate/transaction'  => 'affiliate-transactions',
        'affiliate/logout'       => 'affiliate-logout',
        'affiliate/forgotten'    => 'affiliate-forgot-password',
        'affiliate/register'     => 'create-affiliate-account',
        'affiliate/login'        => 'affiliate-login'
      );

      $language_ids = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language");

      if ($language_ids->num_rows == 1){
        $store_ids = $this->db->query("SELECT store_id FROM ".DB_PREFIX."store");

        foreach ($language_ids->rows as $key => $language) {
          foreach ($store_ids->rows as $key => $store) {
            foreach ($seourl as $query => $keyword) {
            $qu = $this->db->query("SELECT `query` FROM " . DB_PREFIX ."seo_url WHERE `query`='" . $query. "' ");
              if ($qu->num_rows == 0) {
                $this->db->query("INSERT INTO " . DB_PREFIX ."seo_url (query,store_id,language_id keyword) VALUES ('" . $this->db->escape($query). "','".(int)$store['store_id']."','".(int)$language['language_id']."', '" . $this->db->escape($keyword) . "')");
              }
            }
          }
        }
      }*/

    }
  }
}