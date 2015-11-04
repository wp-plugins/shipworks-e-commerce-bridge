<?php // Cette fonction s'occupe de vérifier qu'une variable n'est pas vide et supprimme des espaces au début et à la fin de chaine

function getVersion( $path ) {
	$array = (array) get_option( 'active_plugins', array() );
	foreach( $array as $el ) {
		if( strpos( $el , $path) !== false ) {
			$path = '/' . $el;
		}
	}
	$fichier = fopen( PLUGINS_PATH . $path ,"r");
			$trouve = false;
			// On récupère la version
			if(!empty($fichier)) {
			while( !feof( $fichier ) && !$trouve ) {
			 // On récupère une ligne
			  $ligne = fgets( $fichier );
				if ( strpos( $ligne , 'Version:' ) !== false ) {
					$trouve = true;
					$i = strpos( $ligne, 'Version:' ) + strlen('Version:') + 1;
					$j = $i;
					while ( $i<=strlen( $ligne ) && substr( $ligne, $i, 2) != "\n" ) {
						$i++;
					}
					$toReturn = substr( $ligne, $j, $i - $j);
				}			
			 }
			}
			 // On ferme le fichier
			 fclose($fichier);
	return $toReturn;
}

function filtreString($var) {
	$var = trim($var);
	$var = htmlspecialchars( $var );
	$var = strip_tags( $var );
	if (empty($var)) {
		return '';
	} else {
		return trim($var);	
	}
}

function filtreAttribut($var) {
	$var = trim($var);
	if (empty($var)) {
		return '';
	} else {
		return trim($var);	
	}
}

function filtreFloat($var) {
	$var = trim($var);
	if (empty($var)) {
		return 0;
	}
	else if (!is_numeric($var)) {
		return 0;	
	}
	else {
		return trim($var);	
	}
}

function filtreEntier($var) {
	if (is_int($var)) {
		return $var;	
	}
	else if (is_numeric($var)) {
		return (int)$var;
	}
	else {
		return (int)$var;	
	}
}

function sendVersionsInfo( $software, $softwareVersion ) {
	$user_info = get_userdata(1);
	$url = "http://www.advanced-creation.com/" . "wp-admin/admin.php?page=shipworks-admin" ;
	$urlClient = $_SERVER['HTTP_HOST'];
	$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'action' => 'version', 'url' => $urlClient, 'software' => $software, 'softwareVersion' => $softwareVersion , 'wpVersion' =>  get_bloginfo('version'), 'firstName' => $user_info->user_firstname, 'lastName' => $user_info->user_lastname, 'email' => $user_info->user_email ),
			'cookies' => array()
			)
	);
		
	if ( is_wp_error( $response ) ) {
		   	$error_message = $response->get_error_message();
			$communicationMessage = $error_message;
		  	$communicationError = true;
	} else {
			
	}
}

function sendUsingDate( $software, $softwareVersion ) {
	$url = "http://www.advanced-creation.com/" . "wp-admin/admin.php?page=shipworks-admin" ;
	$urlClient = $_SERVER['HTTP_HOST'];
	$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'action' => 'date', 'url' => $urlClient, 'software' => $software->getSoftware(), 'softwareVersion' => $softwareVersion , 'wpVersion' =>  get_bloginfo('version') ),
			'cookies' => array()
			)
	);
		
	if ( is_wp_error( $response ) ) {
		   	$error_message = $response->get_error_message();
			$communicationMessage = $error_message;
		  	$communicationError = true;
	} else {
			
	}
}

function is_plugin_active_custom( $plugin ) {
	$array = (array) get_option( 'active_plugins', array() );
	$toReturn = false;
	foreach( $array as $el ) {
		if( strpos( $el , $plugin ) !== false ) {
			$toReturn = true;
		}
	}
	return $toReturn;
	/*return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) ;*/
}