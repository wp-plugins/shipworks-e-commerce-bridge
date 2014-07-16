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
	
	if ( $field = 'sku' && $result['meta_value'] == null ) {
		global $wpdb;
		$table = $wpdb->prefix . "posts";
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id , ARRAY_A);
		if ( $row['post_parent'] != 0 ) {
			return getProductInfo( $row['post_parent'], 'sku' );
		}
	}
	
	if ( $field = 'weight' ) {
		$result['meta_value'] = convertWeight( $result['meta_value'] );
	}
	
	return $result['meta_value'];
}

function getCoupons( $id ) {

	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $id . " AND meta_key = 'order_data'", ARRAY_A);
	
	$result = unserialize( $result['meta_value'] );
	
	foreach( $result as $key => $value ) {
		if( $key == 'order_discount_coupons' ) {
			$toReturn = $value;	
		}
	}

	return $toReturn;
}

function convertWeight( $weight ) {
	global $wpdb;
	$table = $wpdb->prefix . "options";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE option_name = 'jigoshop_options'", ARRAY_A);
	
	$result = unserialize( $result['option_value'] );
	
	foreach( $result as $key => $value ) {
		if( $key == 'jigoshop_weight_unit' ) {
			$unit = $value;	
		}
	}
	
	$unitWanted = 'lbs';
	
		//Unify all units to kg first
		switch ($unit) {
			case 'g':
				$weight *= 0.001;
				break;
			case 'lbs':
				$weight *= 0.4535;
				break;
			case 'oz':
                $weight *= 0.0283;
            break;
		}

		//Output desired unit
		switch ($unitWanted) {
			case 'g':
				$weight *= 1000;
				break;
			case 'lbs':
				$weight *= 2.204;
				break;
			case 'oz':
                $weight *= 35.274;
            break;
		}
	
	return $weight;
}

function add_note( $note, $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "posts";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
	$excerpt = $row['post_excerpt'];
	
	$result = $wpdb->update( $table, 
				array( 
						'post_excerpt' => $excerpt . ' ' . $note,
					),
				array( 'ID' => $id )
		);
		
	return $result; 
}