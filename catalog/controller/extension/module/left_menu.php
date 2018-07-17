<?php

class ControllerExtensionModuleLeftMenu extends Controller
{
    public function index($setting)
    {

        $data['title_1'] = $setting['title_1'];
        $data['title_2'] = $setting['title_2'];


        if ($setting['items_1']) {
            foreach ($setting['items_1'] as $item) {
                $data['items_1'][] = array(
                    'name' => $item['name'],
                    'url' => $item['url'],
                );
            }
        }
        if ($setting['items_2']) {
            foreach ($setting['items_2'] as $item) {
                $data['items_2'][] = array(
                    'name' => $item['name'],
                    'url' => $item['url'],
                );
            }
        }

        return $this->load->view('extension/module/left_menu', $data);
    }
}