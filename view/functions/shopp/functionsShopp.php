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
