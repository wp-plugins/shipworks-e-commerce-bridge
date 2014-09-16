<?php
class StatusManager
{
	protected $software;
	protected $date;
	protected $order;
	protected $status;
	protected $result;
	protected $code;
	protected $description;
	protected $comment;

	public function __construct( $software, $date, $order = '', $status = '', $comment = '') {
		$this->software = $software;
		$this->order = $order;
		$this->status = $status;
		$this->comment = $comment;
        $this->setInformations();
    }
	
	protected function setInformations() {
		$order = $this->order;
		$status = $this->status;
		$split = explode( '.' , $this->software->getVersion() );
		// Pour tous les e-commerce on regarde d'abord si la communication a été bonne
		if ($order == '' or $status == '') {
			if ($order == '' and $status == '') {
				$this->result = false;
				$this->code = 'ERR001';
				$this->description = 'Order and Status not communicate correctly';
			}
			else if ($order == '' and $status != '') {
				$this->result = false;
				$this->code = 'ERR002';
				$this->description = 'Order not communicate correctly';
			}
			else if ($order != '' and $status == '') {
				$this->result = false;
				$this->code = 'ERR003';
				$this->description = 'Status not communicate correctly';
			}
		}	else {
			// Cas Shopperpress
			if ( 'shopperpress' == $this->software->getSoftware() ) {
					if ( $split[0] > 7 || ( $split[0] == 7 & $split[1] >= 1 ) ) {
						$this->setInfoShopperpress();
					}
			}// Cas Shopp
			else if ( 'Shopp' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] > 2 ) || ( $split[0] == 1 & $split[1] == 2 & $split[2] >= 2 ) ) {
					$this->setInfoShopp();
				} 
			}// Cas Woocommerce
			else if ( 'Woocommerce' == $this->software->getSoftware() ) {
				if ( $split[0] >= 2 && $split[1] >= 2 ) {
					$this->setInfoWoocommerce2v2();
				} else {
					$this->setInfoWoocommerce();
				}
			}// Cas WP eCommerce
			else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
				if ( $split[0] >= 3 ) {
					$this->setInfoWPeCommerce();
				}
			} // Cas Cart66 Lite
			else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
				}
			}  // Cas Cart66 Pro
			else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {
				if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
				}
			}// Cas Jigoshop
			else if ( 'Jigoshop' == $this->software->getSoftware() ) {
				if ( $split[0] >= 1 ) {
					$this->setInfoJigoshop();
				}
			}
		}
		$this->filtre();
	}
	
	protected function setInfoShopperpress() {
		global $wpdb;
		$table = $wpdb->prefix . "orderdata";
		$status = $this->status;	
		if($status==0) {
			$status=0;
		}
		elseif ($status==1) {
			$status=3;
		}
		elseif ($status==2) {
			$status=5;
		}
		elseif ($status==3) {
			$status=6;
		}
		elseif ($status==4) {
			$status=7;
		}
		elseif ($status==5) {
			$status=8;
		}
		$this->result = $wpdb->update( $table, 
				array( 
						'order_status' => $status,
					), 
				array( 'autoid' => $this->order )
		);
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		}
	}
	
	protected function setInfoShopp() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php');
		global $wpdb;
		$table = $wpdb->prefix . "shopp_purchase";
		$status = $this->status;
		$this->result = $wpdb->update( $table, 
				array( 
						'status' => $status,
					), 
				array( 'id' => $this->order )
		);
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
			/*addComment( $this->comment, $this->order );*/
		}
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		global $wpdb;
		$status = $this->status;
		
		// Avant de mettre à jour on veut retrouver le bon order_number et pas celui de sequential woocommerce
		
		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php") 
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php") ) {
			$row = $wpdb->get_row(
					"SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
			if ( $row != null ) {
				$id = $row['post_id'];
				$this->order = $id;
			}
		}
		
		$table = $wpdb->prefix . "term_taxonomy";
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $status, ARRAY_A);
		
		$table = $wpdb->prefix . "term_relationships";
		if ( $row['term_taxonomy_id'] != null ) {
			$this->result = $wpdb->update( $table, 
					array( 
							'term_taxonomy_id' => $row['term_taxonomy_id']
						), 
					array( 'object_id' => $this->order )
			);
		}
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
			add_private_note( $this->comment, $this->order );
		}
	}
	
	protected function setInfoWoocommerce2v2() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		global $wpdb;
		$status = $this->status;
		
		// Avant de mettre à jour on veut retrouver le bon order_number et pas celui de sequential woocommerce
		
		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php") 
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php") ) {
			$row = $wpdb->get_row(
					"SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
			if ( $row != null ) {
				$id = $row['post_id'];
				$this->order = $id;
			}
		}	
		$table = $wpdb->prefix . "posts";
		$tab = Array( 0 => "pending",
								   1 => "failed",
								   2 => "on-hold",
								   3 => "processing",
								   4 => "completed",
								   5 => "refunded",
								   6=>  "cancelled");
		if ( $tab[$this->status] != null ) {
			$this->result = $wpdb->update( $table, 
					array( 
							'post_status' => 'wc-' . $tab[$this->status]
						), 
					array( 'ID' => $this->order )
			);
		}
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
			add_private_note( $this->comment, $this->order );
		}
	}
	
	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php');
		global $wpdb;
		$status = $this->status;
		
		$table = $wpdb->prefix . "wpsc_purchase_logs";
		$this->result = $wpdb->update( $table, 
				array( 
						'processed' => $status
					), 
				array( 'id' => $this->order )
		);
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
		
			/*add_comment( $this->comment, $this->order );*/
		
		}
	}
	
	protected function setInfoCart66() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/cart66/functionsCart66.php');
		global $wpdb;
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
			$status = $tab[$this->status];
		} else {
			$status = getStatusName( $this->status );
		}
		$table = $wpdb->prefix . "cart66_orders";
		$this->result = $wpdb->update( $table, 
				array( 
						'status' => $status
					), 
				array( 'id' => $this->order )
		);
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
			/*add_comment( $this->comment, $this->order );*/
		}
	}
	
	protected function setInfoJigoshop() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php' );
		global $wpdb;
		$status = $this->status;
		
		$table = $wpdb->prefix . "term_taxonomy";
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $status, ARRAY_A);
		
		$table = $wpdb->prefix . "term_relationships";
		$this->result = $wpdb->update( $table, 
				array( 
						'term_taxonomy_id' => $row['term_taxonomy_id']
					), 
				array( 'object_id' => $this->order )
		);
		if ( $this->result === 0 ) {
			$this->code = 'ERR004';
			$this->description = "The Status coudn't be update in the database";
		} else if ( $this->comment != '' ) {
			add_comment( $this->comment, $this->order );
		}
	}
	
	protected function filtre() {
		$this->description = filtreString( $this->description );
		$this->code = filtreString( $this->code );
		$this->comment = filtreString( $this->comment );
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
	
	public function getComment() {
		return $this->comment;	
	}
}