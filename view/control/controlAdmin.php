<?php
// On affiche la page de base et on récupère les données

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS .'model/User.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Software.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS .'functions/functions.php' );
	
$user = new User();
$software = new Software();

// On envoit au serveur d'advanced-creation les informations sur les versions et logiciels

sendVersionsInfo( $software->getSoftware(), $software->getVersion() );

$test = $user->getUsername();

$testAddress = $user->getCompanyName();

if (!empty($test)) {
	$boutonUpdate = "Update";	
}
if (!empty($testAddress)) {
	$boutonUpdateAdresse = "Update";	
}

//------- On identifie le formulaire en fonction du nom du bouton submit ---------

if (isset($_POST['send-credentials'])) {
	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$user->setCredentials($_POST['username'],$_POST['password']);			
	}
	else {
		$message = "Your Username and password can't be empty, please fill them and update";	
	}
}
else if (isset($_POST['send-address'])) {
	if (!empty($_POST['company_name']) 
				&& !empty($_POST['street1']) 
				&& !empty($_POST['city']) 
				&& !empty($_POST['zip']) 
				&& !empty($_POST['country'])) {
	$user->setAddress($_POST['company_name'],
						$_POST['street1'],
						$_POST['street2'],
						$_POST['street3'],
						$_POST['city'],
						$_POST['state'],
						$_POST['zip'],
						$_POST['country'],
						$_POST['phone'],
						$_POST['support']);
	}
	else {
		$message = "We coudn't update your address, please fill the required fields and try again";
	}
	$testAddress = $user->getCompanyName();
	if (!empty($testAddress)) {
		$boutonUpdateAdresse = "Update";	
	}
}

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/admin.php' );
?>