<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}


        $data['text_account'] = 'Клиенту';
        $data['text_info'] = 'Юридический адрес:';
        $data['text_news'] = '<i class="fa fa-newspaper-o"></i> Наши новости';
        $data['text_contacts'] = '<i class="fa fa-newspaper-o"></i> Контакты';
        $data['text_about'] = '<i class="fa fa-newspaper-o"></i> О компании';
        $data['text_manufacturer']  = '<i class="fa fa-search-plus"></i> Поиск товаров <br> по производителям';
        $data['text_contact'] = '<i class="fa fa-phone"></i> Обратная связь';
        $data['text_sitemap'] = '<i class="fa fa-sitemap"></i> Карта сайта';
        $data['text_wishlist'] = '<i class="fa fa-share-square-o"></i> Закладки';
        $data['text_newsletter'] = '<i class="fa fa-envelope-o"></i> Рассылка';


        $data['contacts_url'] = $this->url->link('information/information', 'information_id=19');
        $data['about_url'] = $this->url->link('information/information', 'information_id=4');
        $data['news_url'] = $this->url->link('blog/category', 'blogpath=1');


        //ЮРИДИЧЕСКИЙ АДРЕС
        $data['text_address'] = '<i class="fa fa-map-marker"></i> <span itemprop="postalCode">220113</span> г. <span itemprop="addressLocality">Минск</span><span itemprop="streetAddress">ул. Я. Коласа, д.63</span>';
        $data['text_phone'] = '<i class="fa fa-phone-square"></i> Тел.<span itemprop="telephone">(+375 17) 337-00-07</span>';
        $data['text_mail'] = '<i class="fa fa-envelope"></i> <span itemprop="email">info@sprinks.by</span>';
        $data['text_skype'] = '<i class="fa fa-skype"></i> <span itemprop="name">sprinks.by</span>';
        $data['url_mail'] = 'info@sprinks.by';
        $data['url_skype'] = 'sprinks.by';

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

        $data['powered'] = date('Y', time());

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		
		return $this->load->view('common/footer', $data);
	}
}
