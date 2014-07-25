<?php 

function getStatus( $status ) {
	global $wpdb;
	$table = $wpdb->prefix . "cart66_cart_settings";
	$rows = $wpdb->get_results("SELECT * FROM " . $table, ARRAY_A);
	foreach( $rows as $line ) {
		if( $line['key'] == 'status_options' ) {
			$val = $line['value'];
		}
	}
	$customStatus = preg_split("/,/", $val);
	if( $val != null ) {
		$tab = Array();
		$i = 1;
		foreach( $customStatus as $stat ) {
			$tab[$i] = trim( $stat );
			$i++;
		}
		foreach( $tab as $key => $value ) {
			if ( $value == $status ) {
				return $key;
			}
		}
	} else {
		if ( "checkout_pending" == $status ) {
			return 1;		
		} else if ( "new" == $status ) {
			return 2;
		}
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

function add_comment( $comment, $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "cart66_orders";
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