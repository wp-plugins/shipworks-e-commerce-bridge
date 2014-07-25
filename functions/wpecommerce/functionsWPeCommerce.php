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
	
	if( $result['meta_value'] == null ) {
		$table = $wpdb->prefix . "posts";
		$prod = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $row['prodid'] , ARRAY_A);
		$parent = $prod['post_parent'];
		if( $parent !== 0 ) {
			$table = $wpdb->prefix . "postmeta";
			$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $parent . " and meta_key = '_wpsc_sku'" , ARRAY_A);
		}	
	}
	
	return $result['meta_value'];
}

function getWeight( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['prodid'] . " and meta_key = '_wpsc_product_metadata'" , ARRAY_A);
	
	$data = unserialize($row['meta_value']);
	
	foreach ( $data as $key =>$value ) {
		if ( $key == 'weight' ) { 
			$itemWeight = $value; 
		} else if ( $key == 'weight_unit' ) {
			$weightUnit = $value;
		}
	}
	
	// Pas besoin de transformer le poids car il est automatiquement converti en pounds
	/*$itemWeight = WeightNormal( $itemWeight, $weightUnit );*/
	
	return $itemWeight;
}

function getVariationValue( $attributeid ) {
	global $wpdb;
	$table = $wpdb->prefix . "term_taxonomy";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $attributeid, ARRAY_A);
	
	$table = $wpdb->prefix . "terms";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $row['term_id'] , ARRAY_A);
	
	return $row['name'];
}

function getVariation( $attributeid ) {
	global $wpdb;
	$table = $wpdb->prefix . "term_taxonomy";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $attributeid , ARRAY_A);
	
	$termid = $row['parent'];
	
	$table = $wpdb->prefix . "terms";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $termid , ARRAY_A);
	
	return $row['name'];
}

function isVariation( $attributeid ) {
	global $wpdb;
	$table = $wpdb->prefix . "term_taxonomy";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $attributeid , ARRAY_A);
	
	return $row['taxonomy'] == 'wpsc-variation';
}

function add_comment( $comment, $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "wpsc_purchase_logs";

	$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $id, ARRAY_A);
	$result = $wpdb->update( $table, 
						array( 
								'notes' => $row['notes'] . '&#10;' . $comment
							), 
						array( 	'id' => $id
						 )
			);
			
	return $result;
}

function WeightNormal($weight, $unit) {
	
	$unitWanted = 'pounds';
 
	if (true) {
		//Unify all units to kg first
		switch ($unit) {
			case 'grams':
				$weight *= 0.001;
				break;
			case 'pounds':
				$weight *= 0.4535;
				break;
			case 'ounces':
                $weight *= 0.0283;
            break;

		}
 
		//Output desired unit
		switch ($unitWanted) {
			case 'grams':
				$weight *= 1000;
				break;
			case 'pounds':
				$weight *= 2.204;
				break;
			case 'ounces':
                $weight *= 35.274;
            break;

		}
	}
	return $weight;
}