<?php
class Count
{
	protected $software;
	protected $number = 0;

	public function __construct( $software, $date = '2000-01-01T00:00:00Z') {
		$this->software = $software;
        $this->setNumber($date);
    }
	
	public function getNumber() {
		return $this->number;	
	}
	
	protected function setNumber($date) {
		$time = strtotime($date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;
		$split = explode( '.' , $this->software->getVersion() );
		if ( $this->software->isCompatible() ) {
			// Cas Shopperpress
			if ( 'shopperpress' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "orderdata WHERE CONCAT(order_date,' ',order_time) > '" . $dateInLocal . "' order by CONCAT(order_date,' ',order_time) ASC"
						);
						
						foreach ( $orders as $order ) {
							$this->number++;
						}
			} // Cas Shopp
			else if ( 'Shopp' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE modified > '" . $dateInLocal . "' and (txnstatus = 'authed' or txnstatus = 'captured') order by modified ASC"
						);
						
						foreach ( $orders as $order ) {
							$this->number++;
						}
			} // Cas Woocommerce
			else if ( 'Woocommerce' == $this->software->getSoftware() ) {
						include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "posts WHERE post_date_gmt > '" . $dateInLocal . "' AND post_type = 'shop_order' order by post_date_gmt ASC", ARRAY_A
						);
						foreach ( $orders as $order ) {
							if( getStatusName( $order ) == 'on-hold' 
									|| getStatusName( $order ) == 'processing' 
											|| getStatusName( $order ) == 'completed' ) {
								$this->number++;
							}
						}
			} // Cas WP eCommerce
			else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE date > '" . $time . "' order by date ASC", 
						ARRAY_A);
						foreach ( $orders as $order ) {
							if ( $order['processed'] == 2 || $order['processed'] == 3 ) {
								$this->number++;
							}
						}
			} // Cas Cart66 Lite
			else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC", 
						ARRAY_A);
						foreach ( $orders as $order ) {
							if ( $order['status'] != 'checkout_pending' ) {
								$this->number++;
							}
						}
			}// Cas Cart66 Pro
			else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC", 
						ARRAY_A);
						foreach ( $orders as $order ) {
							if ( $order['status'] != 'checkout_pending' ) {
								$this->number++;
							}
						}
			} // Cas Jigoshop
			else if ( 'Jigoshop' == $this->software->getSoftware() ) {
						include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php');
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "posts WHERE post_date_gmt > '" . $dateInLocal . "' AND post_type = 'shop_order' order by post_date_gmt ASC", ARRAY_A
						);
						foreach ( $orders as $order ) {
							if( getStatusName( $order ) == 'on-hold' 
									|| getStatusName( $order ) == 'processing' 
											|| getStatusName( $order ) == 'completed' ) {
								$this->number++;
							}
						}
			}
		}
	}
}