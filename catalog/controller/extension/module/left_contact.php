<?php

class ControllerExtensionModuleLeftContact extends Controller
{
    public function index($setting)
    {

        $data['title'] = $setting['title'];
        $data['address'] = $setting['address'];


        if ($setting['items']) {
            foreach ($setting['items'] as $item) {
                $icon = false;
                switch ($item['icon']) {
                    case 1:
                        $icon = 'home_black';
                        break;
                    case 2:
                        $icon = 'velcom';
                        break;
                    case 3:
                        $icon = 'mts';
                        break;
                    case 4:
                        $icon = 'viber';
                        break;
                    case 5:
                        $icon = 'email';
                        break;
                }

                $data['items'][] = array(
                    'text' => $item['text'],
                    'icon' => $icon,
                );
            }
        }

        return $this->load->view('extension/module/left_contact', $data);
    }
}