<?php
/*
	Plugin Name: WP Viagens
	Description: This is for updating your Wordpress plugin.
	Version: 1.0.0
	Author: Travel Tec
	Author URI: https://traveltec.com.br
*/
include_once plugin_dir_path(__FILE__) . '/PDUpdater.php';

    $updater = new PDUpdater(__FILE__);
    $updater->set_username('TravelTec');
    $updater->set_repository('trips_plugin'); 
    $updater->initialize();
