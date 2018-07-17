<?php
class ModelBlogBlogSetting extends Model { 
	
	
	// Get blog home SEO url
	public function getBlogHomeSeoUrls() {
		$blog_home_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'blog/home'");

		foreach ($query->rows as $result) {
			$blog_home_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $blog_home_url_data;
	}
		
	// Save blog home SEO url
	public function saveHomeSeoUrls($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'blog/home'");
		
		if (isset($data['bloghome_seo_url'])) {
			foreach ($data['bloghome_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'blog/home', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
	}
	
	

}