<?php
class Orders
{
	protected $software;
	protected $date;
	protected $orders = Array();

	public function __construct( $software, $date = '2000-01-01T00:00:00Z') {
		$this->software = $software;
		$this->date = $date;
        $this->setInformations($date);
    }
	
	protected function setInformations() {
		$split = explode( '.' , $this->software->getVersion() );
		// Cas Shopperpress
		if ( 'shopperpress' == $this->software->getSoftware() ) {
			if ( $split[0] > 7 || ( $split[0] == 7 & $split[1] >= 1 ) ) {
					$this->setOrdersShopperpress();
			}
		}// Cas Shopp
		else if ( 'Shopp' == $this->software->getSoftware() ) {
			if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] > 2 ) || ( $split[0] == 1 & $split[1] == 2 & $split[2] >= 2 ) ) {
					$this->setOrdersShopp();
			} 
		} // Cas Woocommerce
		else if ( 'Woocommerce' == $this->software->getSoftware() ) {
			if ( $split[0] >= 2 ) {
					$this->setOrdersWoocommerce();
			}
		} // Cas WP eCommerce
		else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
			if ( $split[0] >= 3 ) {
					$this->setOrdersWPeCommerce();
			}
		} // Cas Cart66
		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
			if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setOrdersCart66();
			}
		} // Cas Jigoshop
		else if ( 'Jigoshop' == $this->software->getSoftware() ) {
			if ( $split[0] >= 1 ) {
					$this->setOrdersJigoshop();
			}
		}
	}
	
	protected function setOrdersShopperpress() {
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
					"SELECT * FROM " . $wpdb->prefix . "orderdata WHERE CONCAT(order_date,' ',order_time) > '" . $dateInLocal . "' order by CONCAT(order_date,' ',order_time) ASC"
					, ARRAY_A);
		foreach ( $rows as $row ) {
			array_push($this->orders,new Order($this->software, $this->date,$row));
		}
	}
	
	protected function setOrdersShopp() {
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
					"SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE modified > '" . $dateInLocal . "' and (txnstatus = 'authed' or txnstatus = 'captured') order by modified ASC"
						, ARRAY_A);
		foreach ( $rows as $row ) {
			array_push($this->orders,new Order($this->software, $this->date,$row));
		}
	}
	
	protected function setOrdersWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "posts WHERE post_date_gmt > '" . $dateInLocal . "' AND post_type = 'shop_order' order by post_date_gmt ASC" , ARRAY_A
						);
		foreach ( $rows as $row ) {
			if( getStatusName( $row ) == 'on-hold' || getStatusName( $row ) == 'processing' || getStatusName( $row ) == 'completed' ) {
				array_push($this->orders,new Order($this->software, $this->date,$row));
			}
		}
	}
	
	protected function setOrdersWPeCommerce() {
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE date > '" . $time . "' order by date ASC" , ARRAY_A );
		foreach ( $rows as $row ) {
			if( $row['processed'] == 2 || $row['processed'] == 3 ) {
				array_push($this->orders,new Order($this->software, $this->date,$row));
			}
		}
	}
	
	protected function setOrdersCart66() {
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC", 
						ARRAY_A);
		foreach ( $rows as $row ) {
				if ( $row['status'] != 'checkout_pending' ) {
					array_push($this->orders,new Order($this->software, $this->date,$row));
				}
		}
	}
	
	protected function setOrdersJigoshop() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php');
		$time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$rows = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "posts WHERE post_date_gmt > '" . $dateInLocal . "' AND post_type = 'shop_order' order by post_date_gmt ASC" , ARRAY_A
						);
		foreach ( $rows as $row ) {
			if( getStatusName( $row ) == 'on-hold' || getStatusName( $row ) == 'processing' || getStatusName( $row ) == 'completed' ) {
				array_push($this->orders,new Order($this->software, $this->date,$row));
			}
		}
	}
	
	public function getOrders() {
		return $this->orders;	
	}

}