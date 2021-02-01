<?php   

	$token_cliente = $_POST['token_cliente'];
	$email_cliente = $_POST['email_cliente'];
	$nome_cliente = $_POST['nome_cliente'];
	$trip_id = $_POST['trip_id']; 

	global $post; 
	$wte_trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );   
 

	print_r($wte_trip_settings);

?>