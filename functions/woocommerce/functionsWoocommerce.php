<?php 
function getInformation( $row, $field) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['ID'] . " and meta_key = '" . $field ."'", ARRAY_A);
	return $result['meta_value']; 
}

function getStatus( $software, $row ) {
	
	
	$split = explode( '.' , $software->getVersion() );
	global $wpdb;
	
	if ( $split[0] >= 2 && $split[1] >= 2 ) {
		$tab = Array( 0 => "pending",
				1 => "failed",
				2 => "on-hold",
				3 => "processing",
				4 => "completed",
				5 => "refunded",   
				6=>  "cancelled");
								   
		foreach( $tab as $key => $el ) {
			if( $el == substr( $row["post_status"], 3 ) ) {			
				return $key;
			}
		}
	} else {
		$table = $wpdb->prefix . "term_relationships";
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
		
		$table = $wpdb->prefix . "term_taxonomy";
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
		
		return $result['term_id'];
	}
}

function getStatusName( $software, $row ) {

	$split = explode( '.' , $software->getVersion() );
	global $wpdb;
	if ( $split[0] >= 2 && $split[1] >= 2 ) {
		return substr( $row["post_status"], 3 );
	} else {
		
		global $wpdb;
		$table = $wpdb->prefix . "term_relationships";
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
		if( $row['term_taxonomy_id'] != null ) {
			$table = $wpdb->prefix . "term_taxonomy";
			$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
			if( $row['term_id'] != null ) {
				$table = $wpdb->prefix . "terms";
				$results = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $row['term_id'], ARRAY_A);
			}
		}
		
		return $results['slug'];
	}
}

function isDownloadable( $software, $date, $row ) {

	$toReturn = false;
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$order = new Order($software, $date,$row);
	$i = 0; $j = 0;
	foreach ( $order->getItems() as $item ) {
		$i++;
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . 	$item->getProductID() . " and meta_key = '_downloadable'", ARRAY_A);
		if ( $result['meta_value'] == "yes" ) {
			$toReturn = true;
			$j++;
		}
	}
	if($i != $j) $toReturn = false;
	
	return $toReturn;
}

function getAttributeValue( $slug ) {
	global $wpdb;
	
	$table = $wpdb->prefix . "terms";
	$results = $wpdb->get_row("SELECT * FROM " . $table . " WHERE slug = '" . $slug . "'", ARRAY_A);
	
	return $results['name'];
}

function getItemInfo( $row, $field ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result['meta_value'];
}

function getShippingInfo( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_items";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id = " . $row['ID'] . " AND order_item_type = 'shipping'", ARRAY_A);
	
	return $result['order_item_name'];
}

function getCoupons( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_items";
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE order_id = " . $row['ID'] . " AND order_item_type = 'coupon'", ARRAY_A);
	
	return $results;
}

function isComposed( $row ) {
	global $wpdb;
	$field = '_composite_item';
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result != null ;
}

function isAttributeTMOption( $id ) {
	global $wpdb;
	$field = "'_tmcartepo_data'";
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $id . " AND meta_key = " . $field . " ", ARRAY_A);
	if ( $result != null ) {
		return $result['meta_id'];
	} else {
		return 0;
	}
}

function getTMOptionTab( $id ) {
	global $wpdb;
	$field = '_tmcartepo_data';
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	$str = $row['meta_value'];
	$tab = unserialize( $str );
	
	/*var_dump( $tab );*/
	
	return $tab;
}

function isWooSeqNumber( $row ) {
	$result = getInformation( $row , '_order_number' );
	return $result != null ;
}

function getProductInfo( $id, $field ) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result['meta_value'];
}

function getProductName( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "posts";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
	
	return $result['post_title'];
}

function getOrderNotes( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "comments";
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE comment_post_ID = " . $id . " and comment_type = 'order_note'", ARRAY_A);
	
	return $results;
}

function getOrderMessage($id) {
	/*class GetMessage extends WC_Abstract_Order {

	}
	$get_message = new GetMessage($id);
	$message_raw = "\"".htmlspecialchars(trim($get_message->customer_message))."\"";
	return $message_raw;*/
	global $wpdb;
	$table = $wpdb->prefix . "posts";
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE ID = $id", ARRAY_A);
	return $results[0]['post_excerpt'];
}

function getNotePrivacy( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "commentmeta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE comment_id = " . $id , ARRAY_A);
	
	return $row['meta_value'];
}

function getOrderComments( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "posts";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
	
	return $result[0]['post_title'];
}

function add_customer_note( $note, $id ) {
	$is_customer_note = intval( 1 );

	if ( isset( $_SERVER['HTTP_HOST'] ) )
		$comment_author_email 	= sanitize_email( strtolower( __( 'WooCommerce', 'woocommerce' ) ) . '@' . str_replace( 'www.', '', $_SERVER['HTTP_HOST'] ) );
	else
		$comment_author_email 	= sanitize_email( strtolower( __( 'WooCommerce', 'woocommerce' ) ) . '@noreply.com' );

		$comment_post_ID 		= $id;
		$comment_author 		= __( 'WooCommerce', 'woocommerce' );
		$comment_author_url 	= '';
		$comment_content 		= $note;
		$comment_agent			= 'WooCommerce';
		$comment_type			= 'order_note';
		$comment_parent			= 0;
		$comment_approved 		= 1;
		$commentdata 			= compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' );

		$comment_id = wp_insert_comment( $commentdata );

		add_comment_meta( $comment_id, 'is_customer_note', $is_customer_note );

		if ($is_customer_note) do_action( 'woocommerce_new_customer_note', array( 'order_id' => $id, 'customer_note' => $note ) );

		return $comment_id;	
}

function add_private_note( $note, $id ) {
	$is_customer_note = intval( 0 );

		$comment_post_ID 		= $id;
		$comment_author 		= __( 'WooCommerce', 'woocommerce' );
		$comment_author_url 	= '';
		$comment_content 		= $note;
		$comment_agent			= 'WooCommerce';
		$comment_type			= 'order_note';
		$comment_parent			= 0;
		$comment_approved 		= 1;
		$commentdata 			= compact( 'comment_post_ID', 'comment_author', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' );

		$comment_id = wp_insert_comment( $commentdata );

		add_comment_meta( $comment_id, 'is_customer_note', $is_customer_note );

		return $comment_id;	
}

/**
 * 
 * Normalise dimensions, unify to cm then convert to wanted unit value
 * $unit: 'inch', 'm', 'cm', 'm'
 * Usage: wooDimNormal(55, 'inch');
 * 
 */
function wooDimNormal($dim, $unit) {
 
	$wooDimUnit = strtolower($current_unit = get_option('woocommerce_dimension_unit'));
	$unit = strtolower($unit);
 
	if ($wooDimUnit !== $unit) {
		//Unify all units to cm first
		switch ($wooDimUnit) {
			case 'inch':
				$dim *= 2.54;
				break;
			case 'm':
				$dim *= 100;
				break;
			case 'mm':
				$dim *= 0.1;
				break;
		}
 
		//Output desired unit
		switch ($unit) {
			case 'inch':
				$dim *= 0.3937;
				break;
			case 'm':
				$dim *= 0.01;
				break;
			case 'mm':
				$dim *= 10;
				break;
		}
	}
	return $dim;
}
 
/**
 * 
 * Normalise weight, unify to kg then convert to wanted to unit
 * $unit: 'g', 'kg', 'lbs', 'oz'
 * Useage: wooWeightNormal(55,'lbs');
 * 
 */
function wooWeightNormal($weight, $unit) {
 
	$wooWeightUnit = strtolower($current_unit = get_option('woocommerce_weight_unit'));
	$unit = strtolower($unit);
 
	if ($wooWeightUnit !== $unit) {
		//Unify all units to kg first
		switch ($wooWeightUnit) {
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
		switch ($unit) {
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
	}
	return $weight;
}

