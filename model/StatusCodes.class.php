<?php
class StatusCodes
{
	
	protected $software;
	protected $status = Array();

	public function __construct($software) {
		$this->software = $software;
        $this->setStatus();
		$this->filtre();
    }
	
	public function getStatus() {
		return $this->status;	
	}
	
	protected function filtre() {
		foreach( $this->status as $key => $el ) {
			$this->status[$key] = filtreString( $el );
		}
	}
	
	protected function setStatus() {
		global $wpdb;
		$split = explode( '.' , $this->software->getVersion() );
		if ( $this->software->isCompatible() ) {
			// Cas Shopperpress
			if ( 'shopperpress' == $this->software->getSoftware() ) {
						$this->status = Array( 0 => "Awaiting Payment",
								   1 => "Paid Completed",
								   2 => "Payment &amp; Received",
								   3 => "Payment Failed",
								   4 => "Payment Pending",
								   5 => "Payment Refunded");	
			} 
			// Cas Shopp
			else if ( 'Shopp' == $this->software->getSoftware() ) {
					$table = $wpdb->prefix . "shopp_meta";
					$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE name = 'order_status'", ARRAY_A);
					$statusCodes = unserialize( $row['value'] );
					$this->status = '';
					foreach( $statusCodes as $i => $status ) {
						$this->status[$i] = $status;	
					}
			}
			// Cas Woocommerce
			else if ( 'Woocommerce' == $this->software->getSoftware() ) {
					$table = $wpdb->prefix . "term_taxonomy";
					$statusIds = $wpdb->get_results("SELECT * FROM " . $table . " WHERE taxonomy = 'shop_order_status'", ARRAY_A);
					foreach ( $statusIds as $statusId ) {
							$table = $wpdb->prefix . "terms";
							$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $statusId['term_id'], ARRAY_A);
							
							$this->status[ $statusId['term_id'] ] = $row['name'];
					}
			}
			// Cas WP eCommerce
			else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
					$this->status = Array( 1 => "Incomplete Sale",
								   2 => "Order Received",
								   3 => "Accepted Payment",
								   4 => "Job Dispatched",
								   5 => "Closed Order",
								   6 => "Payment Declined");
			} // Cas Cart66 Lite
			else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
					$table = $wpdb->prefix . "cart66_cart_settings";
					$rows = $wpdb->get_results("SELECT * FROM " . $table, ARRAY_A);
					foreach( $rows as $line ) {
						if( $line['key'] == 'status_options' ) {
							$val = $line['value'];
						}
					}
					$status = preg_split("/,/", $val);
					if( $val != null ) {
						$tab = Array();
						$i = 1;
						foreach( $status as $stat ) {
							$tab[$i] = trim( $stat );
							$i++;
						}
						$this->status = $tab;
					} else {
						$this->status = Array( 	
											1 => "checkout_pending",
								   		 	2 => "new"
								   	);
					}
			}// Cas Cart66 Pro
			else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {
					$table = $wpdb->prefix . "cart66_cart_settings";
					$rows = $wpdb->get_results("SELECT * FROM " . $table, ARRAY_A);
					foreach( $rows as $line ) {
						if( $line['key'] == 'status_options' ) {
							$val = $line['value'];
						}
					}
					$status = preg_split("/,/", $val);
					if( $val != null ) {
						$tab = Array();
						$i = 1;
						foreach( $status as $stat ) {
							$tab[$i] = trim( $stat );
							$i++;
						}
						$this->status = $tab;
					} else {
						$this->status = Array( 	
											1 => "checkout_pending",
								   		 	2 => "new"
								   	);
					}
					
			}// Cas Jigoshop
			else if ( 'Jigoshop' == $this->software->getSoftware() ) {
					$table = $wpdb->prefix . "term_taxonomy";
					$statusIds = $wpdb->get_results("SELECT * FROM " . $table . " WHERE taxonomy = 'shop_order_status'", ARRAY_A);
					foreach ( $statusIds as $statusId ) {
							$table = $wpdb->prefix . "terms";
							$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $statusId['term_id'], ARRAY_A);
							
							$this->status[ $statusId['term_id'] ] = $row['name'];
					}
			}
		}
	}
}