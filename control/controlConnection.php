<?php
// On récupère les données

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/User.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Software.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/StatusCodes.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Count.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Order.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Orders.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Item.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Attribute.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/StatusManager.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/OrderManager.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/TrackingManager.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/functions.php' );
	
$user = new User();

// On commence le processus uniquement si les identifiants sont bons et le POST est bon

$name = $user->getUsername();
$pass = $user->getPassword();
$goodCredentials = false;

$goodCredentials = ( ( $_POST['username'] == $name ) && ( $_POST['password'] == $pass ) );

// On veut récupérer le logiciel installer et sa version

$software = new Software();

// On commence le traitement si l'idification a été faite

if ( $goodCredentials ) {
	$action = htmlspecialchars( $_POST['action'] );
	if( 'getmodule' == $action ) {
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/module.php');
	}
	else if( 'getstore' == $action ) {
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/store.php');
	}
	// On continue le traitement si on a un logiciel de e-commerce reconnu
	else if ( $software->isCompatible() ) {
		if ( 'getstatuscodes' == $action ) {
			$statusCodes = new StatusCodes($software);
			include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusCode.php');
		} else if ( 'getcount' == $action ) {
			// On regarde si le nombre de commande dépasse 30
			$orderManager = new OrderManager( $software, $date );
			// Si c'est free ca télécharge tout
			// Si c'est pas free mais que client a payé ca télécharger tout
			// Sinon on refuse
			if ( $orderManager->isCommunicationError() ) {
				$description = __("There was a communication issue with our server. Please contact us : contact@advanced-creation.com. Error : " . $orderManager->getCommunicationMessage() . '.');
				include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php' );
			}
			else if ( !$orderManager->isFree() && !$orderManager->hasPayed() 
						&& isset($_POST['start'])
							&& $orderManager->getFreeOrdersNumber( htmlspecialchars( $_POST['start'] ) ) == 0
							) {
					// On laisse le client télécharger les 30 order du moi mais pas plus
					$description = __("You are trying to dowload more than 30 orders over the last month. You need to upgrade the ShipWorks Bridge plugin version or to pay for the last month. Please go on our site : http://www.advanced-creation.com/plugin-shipworks/. And then create a subscription with your domaine name which is currently : " . $_SERVER['HTTP_HOST'] );
					include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php');
			} else {
				if ( isset($_POST['start']) ) {
					$date = htmlspecialchars($_POST['start']);
					$count = new Count( $software, $date );
					include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/count.php' );
				} else {
					$count = new Count($software);
					include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/count.php' );
				}
			}
		} else if ( 'getorders' == $action ) {
			$orderManager = new OrderManager( $software, $date );
			if ( isset($_POST['start']) ) {
					if ( !$orderManager->isFree() 
							&& !$orderManager->hasPayed() 
								&& isset($_POST['start'])
									&& $orderManager->getFreeOrdersNumber( htmlspecialchars( $_POST['start'] ) ) > 0 ) {
										// Variable pour la vue
										$numberLimite = $orderManager->getFreeOrdersNumber( htmlspecialchars( $_POST['start'] ) );
						}
						if ( !$orderManager->isFree() 
							&& !$orderManager->hasPayed() 
								&& isset($_POST['start'])
									&& $orderManager->getFreeOrdersNumber( htmlspecialchars( $_POST['start'] ) ) == 0 ) {
							$description = __("You are trying to dowload more than 30 orders over the last month. You need to upgrade the ShipWorks Bridge plugin version or to pay for the last month. Please go on our site : http://www.advanced-creation.com/plugin-shipworks/. And then create a subscription with your domaine name which is currently : " . $_SERVER['HTTP_HOST'] );
							include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php');	
						} else {
							$date = htmlspecialchars($_POST['start']);
							$orders = new Orders( $software, $date );
							include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/orders.php');
						}
			} else {
				$orders = new Orders($software);
				include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/orders.php');
			}
		} else if ( 'updatestatus' == $action ) {
			$order = htmlspecialchars( $_POST['order'] );
			$status = htmlspecialchars( $_POST['status'] );
			$statusManager = new StatusManager(  $software, $date, $order, $status );
			if ( $statusManager->getResult() ) {
				include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusSuccess.php' );
			} else {
				include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusFail.php' );
			}
		} else if ( 'updateshipment' == $action ) {
			$order = htmlspecialchars( $_POST['order'] );
			$date = htmlspecialchars( $_POST['shippingdate'] );
			$carrier = htmlspecialchars( $_POST['carrier'] );
			$tracking = htmlspecialchars( $_POST['tracking'] );
			$trackingManager = new TrackingManager(  $software, $date,  $carrier, $order, $tracking );
			if ( $trackingManager->getResult() ) {
				include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/trackingSuccess.php' );
			} else {
				include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/trackingFail.php' );
			}
		}
	} else {
		$description = __("You have succesfully installed the Shipworks plugin on your website. Nevertheless this one can't work correctly because there seems to be no compatible e-commerce template/plugin activated on your website.");
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php' );
	}
} else {
	// Cas ou les identifiants ne sont pas bons
	$description = __("Wrong credentials.");
	include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php' );
}


?>