<?php 

function getInformation( $row, $field) {
	global $wpdb;
	$table = $wpdb->prefix . "wpsc_submited_form_data";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE log_id = " . $row['id'] . " and form_id = " . $field , ARRAY_A);
	
	return $result['value'];
}

function getSKU( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['prodid'] . " and meta_key = '_wpsc_sku'" , ARRAY_A);
	
	return $result['meta_value'];
}

function getWeight( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['prodid'] . " and meta_key = '_wpsc_product_metadata'" , ARRAY_A);
	
	$data = unserialize($row['meta_value']);
	
	foreach ($data as $key =>$value) {
		if ($key == 'weight') { 
			$itemWeight = $value; 
		}
	}
	
	return $itemWeight;
}