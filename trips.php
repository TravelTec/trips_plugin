<?php
/*
	Plugin Name: WP Viagens
	Description: This is for updating your Wordpress plugin.
	Version: 1.3
	Author: Travel Tec
	Author URI: https://traveltec.com.br
*/
if( ! class_exists( 'Smashing_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}

$updater = new Smashing_Updater( __FILE__ );
$updater->set_username( 'TravelTec' );
$updater->set_repository( 'trips_plugin' );
/*
	$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/
$updater->initialize(); 
