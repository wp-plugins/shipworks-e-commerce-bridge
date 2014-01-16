<?php 

function getInformation( $row, $field) {
	global $wpdb;
	$table = $wpdb->prefix . "postmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['ID'] . " and meta_key = '" . $field ."'", ARRAY_A);
	return $result['meta_value']; 
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

function getItemInfo( $row, $field ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result['meta_value'];
}

function isComposed( $row ) {
	global $wpdb;
	$field = '_composite_item';
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	
	return $result != null ;
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