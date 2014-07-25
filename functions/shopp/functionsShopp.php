<?php // Cette fonction s'occupe de vérifier qu'une variable n'est pas vide et supprimme des espaces au début et à la fin de chaine

function getweight($parent) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$query = "SELECT value FROM ".$prefix."shopp_meta WHERE parent = '".$parent."' and context = 'price' and name = 'settings'";
		$results = mysql_query( $query );
		
		while ($sql = mysql_fetch_assoc($results)) :
			$weight = unserialize($sql['value']);
		endwhile;
		
		$i = 0; //initialize $i
		foreach ($weight as $keys => $values) :
			foreach ($values as $key =>$value) :
				if ($key == 'weight') : $itemWeight = $value; endif;
			endforeach;
		endforeach;
		return $itemWeight;
}

function weightFilter( $weight ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_meta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE type= 'setting' and name = 'weight_unit' ", ARRAY_A);
	
	$shoppUnit = $row['value'];
	// L'unité qu'on veut
	$unit = 'lb';
	
	if ($unit !== $shoppUnit) {
		//Unify all units to kg first
		switch ($shoppUnit) {
			case 'g':
				$weight *= 0.001;
				break;
			case 'lb':
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
			case 'lb':
				$weight *= 2.204;
				break;
			case 'oz':
                $weight *= 35.274;
            break;

		}
	}
	
	return $weight;
}

function getProductPrice( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . "shopp_price";
		$product = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = " . $id , ARRAY_A);
		
		if ( $product['saleprice'] == 0 ) {
			return $product['price'];
		} else {
			return $product['saleprice'];
		}
}

function getProductSku( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . "shopp_price";
		$product = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = " . $id , ARRAY_A);
		
		return $product['sku'];
}

function getAddonPrice( $row ) {
	$obj = unserialize( $row['value'] );
	
	if ( $obj->promoprice == 0 ) {
		return $obj->price;
	} else {
		return $obj->promoprice;
	}
}

function getAddonSku( $row ) {
	$obj = unserialize( $row['value'] );
	
	return $obj->sku;
}

function getAddonWeight( $row ) {
	$obj = unserialize( $row['value'] );
	
	$array = $obj->dimensions;
	
	return $array['weight'];
}

function getAttributes( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_price";
	$product = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = " . $id , ARRAY_A);
	
	$attributes = $product['label'];
	$attributes = explode(",", $attributes);
	foreach( $attributes as $key => $attribute ) {
		$attributes[$key]= trim( $attribute );
	}
	
	return $attributes;
}

function getAttributeParent( $id, $value ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_meta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE context = 'product' AND type = 'meta' AND name = 'options' and parent = " . $id , ARRAY_A);
	
	$str = $row['value'];
	$tab = unserialize( $str );
	
	/*var_dump( $tab );*/
	
	$toReturn = '';
	
	foreach( $tab["v"] as $options ) {
		foreach( $options["options"] as $option ) {
			if( $option['name'] == $value ) {
				$toReturn = $options['name'];
			}
		}
	}
	
	return $toReturn;
		
}

function isVariation( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_price";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = " . $id , ARRAY_A);
	
	if( $row['context'] == 'variation' ) {
		return true;
	} else {
		return false;
	}
}

function getCoupons( $row ) {
	$string = $row['promos'];
	$tab = unserialize( $string );
	
	return $tab;
}

function getNotes( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_meta";
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE parent = " . $id . " and type = 'order_note'", ARRAY_A);
	
	return $results;
}

function addComment( $comment, $id ) {
	global $wpdb;
	$table = $wpdb->prefix . "shopp_meta";
	$value = new stdClass;
	$value->author = 1;
	$value->message = $comment;
	$value->sent = false;
	$results = $wpdb->insert( $table,
				array( 
					'parent' => $id,
					'context' => 'purchase',
					'type' => 'order_note',
					'name' => 'note',
					'value' => serialize( $value ),
					'created' => '',
					'modified' => ''
					)
				);
	
	return $results;
}