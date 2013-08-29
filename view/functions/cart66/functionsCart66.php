<?php 

function getStatus( $status ) {
	if ( "checkout_pending" == $status ) {
		return 1;		
	} else if ( "new" == $status ) {
		return 2;
	}
}

function getStatusName( $status ) {
	if ( 1 == $status ) {
		return "checkout_pending";		
	} else if ( 2 == $status ) {
		return "new";
	}
}

function getInformation( $row, $field) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['ID'] . " and meta_key = '" . $field ."'", ARRAY_A);
	return $result['meta_value']; 
}

function getWeight( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "cart66_products";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = " . $row['product_id'] , ARRAY_A);
	return $result['weight'];
}