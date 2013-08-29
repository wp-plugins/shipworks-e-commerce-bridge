<?php 

/*

@Return La chaine normalisée pour les retour chariot.

*/

function normalize($s) {
    // Normalize line endings
    // Convert all line-endings to UNIX format
    $s = str_replace("\r\n", "\n", $s);
    $s = str_replace("\r", "\n", $s);
    // Don't allow out-of-control blank lines
    $s = preg_replace("/\n{2,}/", "\n\n", $s);
    return $s;
}

function getStatus($row) {
	if($row['order_status']==0) {
		return 0;
	}
	elseif ($row['order_status']==3) {
		return 1;
	}
	elseif ($row['order_status']==5) {
		return 2;
	}
	elseif ($row['order_status']==6) {
		return 3;
	}
	elseif ($row['order_status']==7) {
		return 4;
	}
	elseif ($row['order_status']==8) {
		return 5;
	}
	else {
		return 3;	
	}
}

/*

@Return La la valeur recherchée dans l'élément $row

*/

function getShippingInformation($row,$word) {
	$strShipping = normalize($row['order_address'].'');
	$j=0;
	$toReturn = '';
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	// On saute la première ligne qui est vide
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	$ligne1 = preg_split("/[\s,]+/", substr($strShipping,0,$j));
	if($word=='name') {
		$toReturn = substr($strShipping,0,$j);
	}
	if($word=='first_name') {
		if (count($ligne1)<=2) {
	 		$toReturn = $ligne1[0];
		} else {
			$toReturn = $ligne1[0].' '.$ligne1[1];
		}
	}
	if($word=='last_name') {
		if (count($ligne1)<=2) {
	 		$toReturn = $ligne1[1];
		} else {
			$toReturn = '';
			for ($k=2;$k<count($ligne1);$k++) {
				$toReturn = $toReturn.' '.$ligne1[$k];
			}
		}
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='company') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='address') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	$ligneCity = split(",",trim(substr($strShipping,$i,$j-$i)));
	if($word=='city') {
		$toReturn = trim($ligneCity[0]);
	}
	if($word=='postcode') {
		$toReturn = trim($ligneCity[1]);
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='state') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='country') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='email') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='phone') {
		$toReturn = trim(substr($strShipping,$i+1,$j-$i));
	}
	return $toReturn;
}

function requestedShippingAddress($row) {
	$strOrder = normalize($row['order_data'].'');
	$word = "Requested Shipping";
	$toReturn = false;
	$nb = 0;
	while (!$toReturn&&$i<strlen($strOrder)-strlen($word)-1) {
		if (substr($strOrder,$i,strlen($word))==$word) {
				$toReturn = true;
		}
		$i ++;
	}
	return $toReturn;
}

// Fonction pour récupérer les informations dans le cas ou on a une adresse de livraison différente

function getRequestedShippingInformation($row,$word) {
	$strShipping = normalize($row['order_addressShip'].'');
	$j=0;
	$toReturn = '';
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	$ligne1 = preg_split("/[\s,]+/", substr($strShipping,0,$j));
	if($word=='name') {
		$toReturn = substr($strShipping,0,$j);
	}
	if($word=='first_name') {
		if (count($ligne1)<=2) {
	 		$toReturn = $ligne1[0];
		} else {
			$toReturn = $ligne1[0].' '.$ligne1[1];
		}
	}
	if($word=='last_name') {
		if (count($ligne1)<=2) {
	 		$toReturn = $ligne1[1];
		} else {
			$toReturn = '';
			for ($k=2;$k<count($ligne1);$k++) {
				$toReturn = $toReturn.' '.$ligne1[$k];
			}
		}
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='company') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='address') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	$ligneCity = split(",",trim(substr($strShipping,$i,$j-$i)));
	if($word=='city') {
		$toReturn = trim($ligneCity[0]);
	}
	if($word=='postcode') {
		$toReturn = trim($ligneCity[1]);
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='state') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	$i=$j+1;
	$j++;
	while (substr($strShipping,$j,1)!=="\n"&&($j<strlen($strShipping)-1)) {
		$j ++;
	}
	if($word=='country') {
		$toReturn = trim(substr($strShipping,$i,$j-$i));
	}
	return $toReturn;
}

// Fonctions pour récupérrer les informations sur les items dans une commande

function getItemProperty($word,$i,$strPayments) {
	while (substr($strPayments,$i,strlen($word))!==$word&&($i<strlen($strPayments)-strlen($word)-1)) {
		$i ++;
	}
	$j=0;
	while (substr($strPayments,$i+$j,1)!=="\n"&&($j<strlen($strPayments)-1)) {
		$j ++;
	}
	if ($i==(strlen($strPayments)-strlen($word)-1)) {
		return "";	
	} else {
		return trim(substr($strPayments,$i+strlen($word)+2,$j-strlen($word)-2));
	}
}

function getItemInformation($row,$nbItem,$property) {
	$strOrder = normalize($row['order_data'].'');
	$word = "Product Details";
	$i = 0;
	$nb = 0;
	while ($nb!==$nbItem&&$i<strlen($strOrder)-strlen($word)-1) {
		if (substr($strOrder,$i,strlen($word))==$word) {
				$nb ++;
		}
		$i ++;
	}
	return getItemProperty($property,$i,$strOrder);
}

function getItemQuantity($row) {
	$strOrder = normalize($row['order_data'].'');
	$word = "Product Details";
	$i = 0;
	$nb = 0;
	while ($i<strlen($strOrder)-strlen($word)-1) {
		if (substr($strOrder,$i,strlen($word))==$word) {
				$nb ++;
		}
		$i ++;
	}
	return $nb;
}

function setWeight($sku) {
	global $wpdb;
	$weight = $wpdb->get_results(
					"SELECT * FROM " . $wpdb->prefix . "postmeta WHERE post_id=" . $sku . " AND meta_key= 'weight' "
					, ARRAY_A);
	return $weight[0]['meta_value'];
}
