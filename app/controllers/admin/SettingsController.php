<?php

namespace app\controllers\admin;


use vendor\modules\Settings;

class SettingsController extends AppController
{
    public function indexAction () {
        if (!$this->user->access('settings')) {
            RefreshPage(url('/admin'));
        }
        $regions = Settings::instance()->regions;
        $lands = Settings::instance()->lands;
        $delivery = Settings::instance()->delivery;
        
        if (isset($_POST['save'])) {
            $regions = [];
            foreach ($_POST['regions'] as $region) {
                $region = trim(strip_tags($region));
                if (!in_array($region,$regions) && ! empty($region))
                    $regions[] = $region;
            }
            $lands = [];
            foreach ($_POST['lands'] as $land) {
                $land = trim(strip_tags($land));
                if (!in_array($land,$lands) && ! empty($land))
                    $lands[] = $land;
            }

            foreach ($_POST['delivery'] as $child) {
                $child = trim(strip_tags($child));
                if (!in_array($child, $delivery) && ! empty($child)) {
                    $delivery[] = $child;
                }
            }


            Settings::instance()->regions = $regions;
            Settings::instance()->lands = $lands;
            Settings::instance()->delivery = $delivery;
        }
        
        $this->set(compact('regions', 'lands', 'delivery'));
    }
}