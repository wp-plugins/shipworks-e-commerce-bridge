<?php 

function getInformation( $row, $field) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['ID'] . " and meta_key = 'order_data'", ARRAY_A);
	$object = unserialize( $result['meta_value'] );
	
	foreach( $object as $key => $value ) {
		if ( $key == $field ) {
			return $value;	
		}
	}
}

function getStatus( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "term_relationships";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
	
	$table = $wpdb->prefix . "term_taxonomy";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
	
	return $result['term_id'];
}

function getStatusName( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "term_relationships";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
	
	$table = $wpdb->prefix . "term_taxonomy";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
	
	$table = $wpdb->prefix . "terms";
	$results = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $row['term_id'], ARRAY_A);
	
	return $results['slug'];
}

function getProductInfo( $id, $field ) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result['meta_value'];
}