<?php
class TrackingManager
{
	protected $software;
	protected $date;
	protected $carrier;
	protected $order;
	protected $tracking;
	protected $result;
	protected $code;
	protected $description;

	public function __construct( $software, $date, $carrier, $order = '', $tracking = '') {
		$this->software = $software;
		$this->date = $date;
		$this->carrier = $carrier;
		$this->order = $order;
		$this->tracking = $tracking;
        $this->setInformations();
    }
	
	protected function setInformations() {
		$order = $this->order;
		$tracking = $this->tracking;
		$split = explode( '.' , $this->software->getVersion() );
		// Pour tous les e-commerce on regarde d'abord si la communication a été bonne
		if ($order == '' or $tracking == '') {
			if ($order == '' and $tracking == '') {
				$this->result = false;
				$this->code = 'ERR001';
				$this->description = 'Order and Tracking did not communicate correctly';
			}
			else if ($order == '' and $tracking != '') {
				$this->result = false;
				$this->code = 'ERR002';
				$this->description = 'Order did not communicate correctly';
			}
			else if ($order != '' and $tracking == '') {
				$this->result = false;
				$this->code = 'ERR003';
				$this->description = 'Tracking did not communicate correctly';
			}
		}	else {
			// Cas Shopp ( on a pas de shopperpress )
			if ( 'Shopp' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] > 2 ) || ( $split[0] == 1 & $split[1] == 2 & $split[2] >= 2 ) ) {
					$this->setInfoShopp();
				} 
			} else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
				if ( $split[0] >= 3 ) {
					$this->setInfoWPeCommerce();
				}
			} else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
				}
			} else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
				}
			} else if ( 'Woocommerce' == $this->software->getSoftware() ) {
				if ( $split[0] >= 2 ) {
					$this->setInfoWoocommerce();
				}
			} else if ( 'Jigoshop' == $this->software->getSoftware() ) {
					if ( $split[0] >= 1 ) {
							$this->setInfoJigoshop();
					}
				}
		}
		$this->filtre();
	}
	
	protected function setInfoShopp() {
		global $wpdb;
		$table = $wpdb->prefix . "shopp_purchase";
		$tracking_number = $this->tracking;
		
		//checking the identify shipping company
		$usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";
		$ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';
		$fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';
		if ( preg_match( $usps_pattern, $tracking_number ) ) { //test USPS
			$tracking_name = 'usps';
		} elseif( preg_match( $fedex_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'fedex';
		} elseif( preg_match( $ups_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'ups';
		}
		
		// Cheking if the order is in the database
		
		$row= $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE id = '" . $this->order . "'" );
		if ( !$row ) {
			$this->result = false;
			$this->code = 'ERR004';
			$this->description = 'The order is not in the Database';
		} else if ( $tracking_name != 'fedex' & $tracking_name != 'usps' & $tracking_name != 'ups') {
			$this->result = false;
			$this->code = 'ERR005';
			$this->description = "Carrier Company didn't find";
		} else {
			// On sérialise le tracking
			$tracking = new stdClass;
			$tracking->tracking = $tracking_number;
			$tracking->carrier = $tracking_name;
			$tracking = serialize($tracking);
						
			// Check if the tracking number is already in the database or need an isert
			$rowTracking = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "shopp_meta WHERE parent  = '" . $this->order . "' and context = 'purchase' and name = 'shipped'" );
			$time = strtotime("now");
			$dateInLocal = date("Y-m-d H:i:s", $time);
			/*var_dump( $rowTracking === 'true' );*/
			if ( $rowTracking === 'true' ) { // On update
				$table = $wpdb->prefix . "shopp_meta";
				$this->result = $wpdb->update( $table, 
						array( 
								'value' => $tracking,
								'modified' => $dateInLocal,
								'type' => 'event'
							), 
						array( 	'parent' => $this->order,
								'context' => 'purchase',
								'name' => 'shipped'
						 )
				);		
				
				if ( $this->result === false ) {
					$this->code = 'ERR009';
					$this->description = "The tracking number coudn't be update in the database";
				}
			} else { // On insert
				$table = $wpdb->prefix . "shopp_meta";
				$this->result = $wpdb->insert( $table, 
						array( 
								'parent' => $this->order,
								'context' => 'purchase',
								'name' => 'shipped',
								'type' => 'event',
								'value' => $tracking,
								'created' => $dateInLocal,
								'modified' => $dateInLocal
							)
						);
				/*var_dump( $this->result );*/
				if ( $this->result === false ) {
					$this->code = 'ERR010';
					$this->description = "The tracking number coudn't be insert in the database";
				}
			}
		}
	}
	
	protected function setInfoWPeCommerce() {
		global $wpdb;
		$table = $wpdb->prefix . "wpsc_purchase_logs";
		$tracking_number = $this->tracking;
		
		//checking the identify shipping company
		$usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";
		$ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';
		$fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';
		if ( preg_match( $usps_pattern, $tracking_number ) ) { //test USPS
			$tracking_name = 'usps';
		} elseif( preg_match( $fedex_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'fedex';
		} elseif( preg_match( $ups_pattern, $tracking_number ) ) { //test Ups
			$tracking_name = 'ups';
		}
		
		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);
		if ( !$row ) {
			$this->result = false;
			$this->code = 'ERR004';
			$this->description = 'The order is not in the Database';
		} else if ( $tracking_name != 'fedex' & $tracking_name != 'usps' & $tracking_name != 'ups') {
			$this->result = false;
			$this->code = 'ERR005';
			$this->description = "Carrier Company didn't find";
		} else {
			$this->result = $wpdb->update( $table, 
						array( 
								'track_id' => $tracking_number,
							), 
						array( 	'id' => $this->order
						 )
			);
			if ( $this->result === false ) {
				$this->code = 'ERR010';
				$this->description = "The tracking number coudn't be insert in the database";
			}
		}
		
	}
	
	protected function setInfoCart66() {
		global $wpdb;
		$table = $wpdb->prefix . "cart66_orders";
		$tracking_number = $this->tracking;
		
		//checking the identify shipping company
		$usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";
		$ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';
		$fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';
		if ( preg_match( $usps_pattern, $tracking_number ) ) { //test USPS
			$tracking_name = 'usps';
		} elseif( preg_match( $fedex_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'fedex';
		} elseif( preg_match( $ups_pattern, $tracking_number ) ) { //test Ups
			$tracking_name = 'ups';
		}
		
		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);
		if ( !$row ) {
			$this->result = false;
			$this->code = 'ERR004';
			$this->description = 'The order is not in the Database';
		} else if ( $tracking_name != 'fedex' & $tracking_name != 'usps' & $tracking_name != 'ups' ) {
			$this->result = false;
			$this->code = 'ERR005';
			$this->description = "Carrier Company didn't find";
		} else {
			$this->result = $wpdb->update( $table, 
						array( 
								'tracking_number' => $tracking_number . '' . 1,
							), 
						array( 	'id' => $this->order
						 )
			);
			if ( $this->result === false ) {
				$this->code = 'ERR010';
				$this->description = "The tracking number coudn't be insert in the database.";
			}
		}
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		$time = strtotime($this->date.' UTC');
		$this->date = date("y-m-d", $time);
		global $wpdb;
		$table = $wpdb->prefix . "posts";
		$tracking_number = $this->tracking;
		
		//checking the identify shipping company
		$usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";
		$ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';
		$fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';
		if ( preg_match( $usps_pattern, $tracking_number ) ) { //test USPS
			$tracking_name = 'usps';
		} elseif( preg_match( $fedex_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'fedex';
		} elseif( preg_match( $ups_pattern, $tracking_number ) ) { //test Ups
			$tracking_name = 'ups';
		}
		
		// Avant de mettre à jour on veut retrouver le bon order_number et pas celui de sequential woocommerce
		
		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php") 
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php") ) {
			$row = $wpdb->get_row(
					"SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
			if ( $row == null ) {
				$this->result = false;
				$this->code = 'ERR004';
				$this->description = 'The order is not in the Database';
			} else {
				$id = $row['post_id'];
				$this->order = $id;
			}
		}
		
		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);
		if ( !$row ) {
			$this->result = false;
			$this->code = 'ERR004';
			$this->description = 'The order is not in the Database';
		} else if ( $tracking_name != 'fedex' & $tracking_name != 'usps' & $tracking_name != 'ups' ) {
			$this->result = false;
			$this->code = 'ERR005';
			$this->description = "Carrier Company didn't find";
		} else {
			
			$note = "Your order was shipped on " . $this->date . " via " . $this->carrier . ". Tracking number is " . $this->tracking . ".";
			$this->result = add_customer_note( $note, $this->order );
			
			if ( $this->result === false ) {
				$this->code = 'ERR010';
				$this->description = "The tracking number coudn't be insert in the database.";
			}
		}
	}
	
	protected function setInfoJigoshop() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php' );
		$time = strtotime($this->date.' UTC');
		$this->date = date("y-m-d", $time);
		global $wpdb;
		$table = $wpdb->prefix . "posts";
		$tracking_number = $this->tracking;
		
		//checking the identify shipping company
		$usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";
		$ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';
		$fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';
		if ( preg_match( $usps_pattern, $tracking_number ) ) { //test USPS
			$tracking_name = 'usps';
		} elseif( preg_match( $fedex_pattern, $tracking_number ) ) { //test Fedex
			$tracking_name = 'fedex';
		} elseif( preg_match( $ups_pattern, $tracking_number ) ) { //test Ups
			$tracking_name = 'ups';
		}
		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);
		if ( !$row ) {
			$this->result = false;
			$this->code = 'ERR004';
			$this->description = 'The order is not in the Database';
		} else if ( $tracking_name != 'fedex' & $tracking_name != 'usps' & $tracking_name != 'ups' ) {
			$this->result = false;
			$this->code = 'ERR005';
			$this->description = "Carrier Company didn't find";
		} else {
			
			$note = "Your order was shipped on " . $this->date . " via " . $this->carrier . ". Tracking number is " . $this->tracking . ".";
			$this->result = add_note( $note, $this->order );
			
			if ( $this->result === false ) {
				$this->code = 'ERR010';
				$this->description = "The tracking number coudn't be insert in the database.";
			}
		}
	}
	
	protected function filtre() {
		$this->description = filtreString( $this->description );
		$this->code = filtreString( $this->code );
	}
	
	public function getResult() {
		return $this->result;	
	}
	
	public function getCode() {
		return $this->code;	
	}
	
	public function getDescription() {
		return $this->description;	
	}
}