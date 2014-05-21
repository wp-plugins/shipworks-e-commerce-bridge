<?php

//------- On identifie le formulaire en fonction du nom du bouton submit ---------

if (isset($_POST['cancel-subscription'])) {
	$url = "http://www.advanced-creation.com/" . "wp-admin/admin.php?page=shipworks-admin" ;
	$urlClient = $_SERVER['HTTP_HOST'];
	$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'action' => 'cancel-subscription', 'url' => $urlClient ),
				'cookies' => array()
				)
	);
			
	if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$message = $error_message . " The subscription was not canceled.";
	} else {
			$message = "The subscription is canceled.";
	}	
}

// On se connecte à advanced-creation pour obtenir les informations concernant le payement du client
$url = "http://www.advanced-creation.com/" . "wp-admin/admin.php?page=shipworks-admin" ;
$urlClient = $_SERVER['HTTP_HOST'];
$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'action' => 'control', 'url' => $urlClient ),
			'cookies' => array()
			)
);
		
if ( is_wp_error( $response ) ) {
	   	$error_message = $response->get_error_message();
		$communicationError = true;
} else {
		$doc = new DomDocument;
		$doc->loadXML($response['body']);
		$racine = $doc->documentElement;
		$hasPayed = $racine->getElementsByTagName('hasPayed')->item(0);
		$datePayment = trim($racine->getElementsByTagName('datePayment')->item(0)->firstChild->nodeValue);
		$status = trim($racine->getElementsByTagName('status')->item(0)->firstChild->nodeValue);
		$host = trim($racine->getElementsByTagName('host')->item(0)->firstChild->nodeValue);
		if (strtolower(trim($hasPayed->firstChild->nodeValue)) == "true") {
			$hasPayed = true;
			
		} else {
			$hasPayed = false;
			/*echo strtolower($hasPayed->firstChild->nodeValue);*/
		}
}


// On affiche la page tutoriel sans accès à la base de donnée

include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS.'view/subscription.php');
?>