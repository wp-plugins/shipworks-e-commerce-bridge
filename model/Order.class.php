<?php
class Order
{
	protected $software;
	protected $date;
	protected $row;
	
	
	protected $id_order;
	protected $createdDate;
	protected $modifiedDate;
	protected $shipoption;
	protected $status;
	protected $firstname;
	protected $middlename;
	protected $lastname;
	protected $company;
	protected $shipCompany;
	protected $address;
	protected $street2;
	protected $street3;
	protected $xaddress;
	protected $city;
	protected $state;
	protected $postcode;
	protected $country;
	protected $residential = 'true';
	protected $phone;
	protected $email;
	protected $fax;
	protected $website;
	protected $shipfirstname;
	protected $shiplastname;
	protected $shipaddress;
	protected $shipstreet2;
	protected $shipxaddress;
	protected $shipcity;
	protected $shipstate;
	protected $shippostcode;
	protected $shipcountry;
	protected $cardtype;
	
	protected $freight;
	protected $tax;
	protected $discount;
	protected $fee;
	
	protected $coupons = Array();
	protected $privateNotes = Array();
	
	protected $items = Array();

	public function __construct( $software, $date, $row) {
		$this->software = $software;
		$this->row = $row;
		$this->date = $date;
        $this->setInformations();
    }
	
	public function getNumber() {
		return $this->number;	
	}
	
	protected function setInformations() {
		$split = explode( '.' , $this->software->getVersion() );
		global $wpdb;
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
		} // Cas Woocommerce
		else if ( 'Woocommerce' == $this->software->getSoftware() ) {
			if ( $split[0] >= 2 ) {
					$this->setInfoWoocommerce();
			}
		} // Cas WP eCommerce
		else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
			if ( $split[0] >= 3 ) {
					$this->setInfoWPeCommerce();
			}
		} // Cas Cart66 Lite
		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
			if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
			}
		} // Cas Cart66 Pro
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
		// On filtre les champs
		$this->filtre();
	}
	
	protected function filtre() {
		// On ne filtre pas l'id
		$this->createdDate = filtreString( $this->createdDate );
		$this->modifiedDate = filtreString( $this->modifiedDate );
		$this->status = filtreEntier( $this->status );
		$this->shipoption = filtreString( $this->shipoption );
		$this->firstname =  filtreString( $this->firstname );
		$this->middlename = filtreString( $this->middlename );
		$this->lastname =  filtreString( $this->lastname );
		$this->company =  filtreString( $this->company );
		// En attendant que tout soit implémenté pour chaque software
		if( empty( $this->shipCompany ) ) {
			$this->shipCompany = $this->company;
		}
		$this->shipCompany =  filtreString( $this->shipCompany );
		$this->address =  filtreString( $this->address );
		$this->street2 = filtreString( $this->street2 );
		$this->street3 = filtreString( $this->street3 );
		$this->xaddress =  filtreString( $this->xaddress );
		$this->city =  filtreString( $this->city );
		$this->state =  filtreString( $this->state );
		$this->postcode =  filtreString( $this->postcode );
		$this->country =  filtreString( $this->country );
		$this->residential =  filtreString( $this->residential );
		$this->email = filtreString( $this->email );
		$this->phone =  filtreString( $this->phone );
		$this->fax = filtreString( $this->fax );
		$this->website = filtreString( $this->website );
		$this->shipfirstname = filtreString( $this->shipfirstname );
		$this->shiplastname = filtreString( $this->shiplastname );
		$this->shipaddress = filtreString( $this->shipaddress );
		$this->shipstreet2 = filtreString( $this->shipstreet2 );
		$this->shipxaddress = filtreString( $this->shipxaddress );
		$this->shipcity =  filtreString( $this->shipcity );
		$this->shipstate =  filtreString( $this->shipstate );
		$this->shippostcode = filtreString( $this->shippostcode );
		$this->shipcountry = filtreString( $this->shipcountry );
		$this->cardtype = filtreString( $this->cardtype );
		
		foreach( $this->coupons as $key => $coupon ) {
			$this->coupons[$key] = filtreString( $coupon );
		}
		
		foreach( $this->privateNotes as $key => $note ) {
			$this->privateNotes[$key] = filtreString( $note );
		}
		
		$this->freight = filtreFloat( $this->freight );
		$this->tax = filtreFloat( $this->tax );
		$this->discount = filtreFloat( $this->discount );
		$this->fee = filtreFloat( $this->fee);
	}
	
	protected function setInfoShopperpress() {
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopperpress/functionsShopperpress.php');
		$this->id_order = (int) $this->row['autoid'];
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['order_date'].' '.$this->row['order_time']));
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['order_date'].' '.$this->row['order_time']));
		$this->status = getStatus($this->row);
		$this->firstname =  getShippingInformation($this->row,'first_name');
		$this->middlename = '';
		$this->lastname =  getShippingInformation($this->row,'last_name');
		$this->company =  getShippingInformation($this->row,'company');
		$this->address =  getShippingInformation($this->row,'address');
		$this->street2 = '';
		$this->street3 = '';
		$this->xaddress =  getShippingInformation($this->row,'address');
		$this->city =  getShippingInformation($this->row,'city');
		$this->state =  getShippingInformation($this->row,'state');
		$this->postcode =  getShippingInformation($this->row,'postcode');
		$this->country =  $this->row['order_country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->email =  getShippingInformation($this->row,'email');
		$this->phone =  getShippingInformation($this->row,'phone');
		$this->fax = '';
		$this->website = '';
		$this->shipfirstname =  $this->firstname;
		$this->shiplastname =  $this->lastname;
		$this->shipaddress =  $this->address;
		$this->shipxaddress =  '';
		$this->shipcity =  $this->city;
		$this->shipstate =  $this->state;
		$this->shippostcode =  $this->postcode;
		$this->shipcountry =  $this->country;
		$this->cardtype =  '';
		if (requestedShippingAddress($this->row)) {
			$this->shipfirstname =  getRequestedShippingInformation($this->row,'first_name');
			$this->shiplastname = getRequestedShippingInformation($this->row,'last_name');
			$this->shipaddress =  getRequestedShippingInformation($this->row,'address');
			$this->shipcity =  getRequestedShippingInformation($this->row,'city');
			$this->shipstate =  getRequestedShippingInformation($this->row,'state');
			$this->shippostcode =  getRequestedShippingInformation($this->row,'postcode');
			$this->shipcountry =  getRequestedShippingInformation($this->row,'country');
		}
		
		$this->freight = $this->row['order_shipping'];
		$this->tax = $this->row['order_tax'];
		$this->discount = $this->row['order_coupon'];
		
		for($k = 1; $k <= getItemQuantity($this->row);$k ++){
			array_push($this->items,new Item($this->software, $this->date,$this->row,$k));
		}
	}
	
	protected function setInfoShopp() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['created']));
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['modified']));
		$this->shipoption = $this->row['shipoption'];
		$this->status = $this->row['status'];
		$this->firstname = $this->row['firstname'];
		$this->middlename = '';
		$this->lastname = $this->row['lastname'];
		$this->company = $this->row['company'];
		$this->address = $this->row['address'];
		$this->xaddress = $this->row['xaddress'];
		$this->street2 = $this->row['xaddress']; // add to correct no address line 2 shipping on shopp
		$this->street3 = '';
		$this->city = $this->row['city'];
		$this->state = $this->row['state'];
		$this->postcode = $this->row['postcode'];
		$this->country = $this->row['country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->phone = $this->row['phone'];
		$this->email = $this->row['email'];
		$this->fax = '';
		$this->website = '';
		$this->shipfirstname = $this->row['shipname'];
		$this->shiplastname = '';
		$this->shipaddress = $this->row['shipaddress'];
		$this->shipxaddress = $this->row['shipxaddress'];
		$this->shipstreet2 = $this->row['shipxaddress']; // add to correct no address line 2 billing on shopp
		
		$this->shipcity = $this->row['shipcity'];
		$this->shipstate = $this->row['shipstate'];
		$this->shippostcode = $this->row['shippostcode'];
		$this->shipcountry = $this->row['shipcountry'];
		$this->cardtype = $this->row['cardtype'];
		
		$this->freight = $this->row['freight']; // Shipping Fee
		$this->tax = $this->row['tax']; //Tax Fee
		$this->discount = $this->row['discount']; // Discount
		$this->fees = $this->row['fees']; // Add Fee
		
		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."shopp_purchase AS p LEFT JOIN ". $wpdb->prefix ."shopp_purchased AS ped ON ped.purchase = p.id WHERE p.modified > '" . $dateInLocal . "' and p.id='" . $this->id_order . "' and (p.txnstatus = 'authed' or p.txnstatus = 'captured')  order by p.id"
						, ARRAY_A);

		for($k = 0; $k < count( $rows );$k ++){
			// On ne veut pas prendre en compte les item downloadable
			if ( !($rows[$k]["type"] == "Download") ) {
				array_push($this->items,new Item($this->software, $this->date,$rows[$k]));
				
				if ( $rows[$k]['addons'] == 'yes' ) {
					// On ajoute les Addons
					global $wpdb;
					$table = $wpdb->prefix . "shopp_meta";
					$addons = $wpdb->get_results("SELECT * FROM " . $table . " WHERE parent = " . $rows[$k]['id'] . " and type = 'addon'" , ARRAY_A);
					foreach( $addons as $addon ) {
						$addon['product'] = $rows[$k]['product'];
						$addon['quantity'] = $rows[$k]['quantity'];
						array_push($this->items,new Item($this->software, $this->date,$addon));
					}
				}
			}
		}
		
		// Ajout des coupons
		if ( $this->row['promos'] != null ) {
			$coupons = getCoupons( $this->row );
			foreach( $coupons as $coupon ) {
				/*var_dump( $coupon );*/
				array_push( $this->coupons, 'Coupon : ' . $coupon );	
			}
		}
		
		// Ajout des notes
		if ( getNotes( $this->row['id'] ) != null ) {
			$notes = getNotes( $this->row['id'] );
			foreach( $notes as $note ) {
				$content = unserialize( $note['value'] );
				
				array_push( $this->privateNotes, $content->message );	
			}
		}
		
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		if ( ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php") 
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php")  ) 				&& is_numeric( getInformation( $this->row, '_order_number' ) ) ) {
			$this->id_order = getInformation( $this->row, '_order_number' );
		} else {
			$this->id_order = $this->row['ID'];
		}
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['post_date_gmt']));
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['post_modified_gmt']));
		$split = explode( '.' , $this->software->getVersion() );
		$this->shipoption = getInformation( $this->row, '_shipping_method_title' ) ;
		if ( $split[0] >= 2 && $split[1] >= 1 && $split[2] >= 2 ) {
			if ( null != getShippingInfo( $this->row ) ) {
				$this->shipoption = getShippingInfo( $this->row ) ;
			}
		}
		$this->status = getStatus( $this->software, $this->row );
		$this->firstname =  getInformation( $this->row, '_billing_first_name' );
		$this->middlename = '';
		$this->lastname = getInformation( $this->row, '_billing_last_name' );
		$this->company = getInformation( $this->row, '_billing_company' );
		$this->address = getInformation( $this->row, '_billing_address_1' );
		$this->xaddress = '';
		$this->street2 = getInformation( $this->row, '_billing_address_2' );
		$this->street3 = '';
		$this->city = getInformation( $this->row, '_billing_city' );
		$this->state = getInformation( $this->row, '_billing_state' );
		$this->postcode = getInformation( $this->row, '_billing_postcode' );
		$this->country = getInformation( $this->row, '_billing_country' );
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->phone = getInformation( $this->row, '_billing_phone' );
		$this->email = getInformation( $this->row, '_billing_email' );
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = getInformation( $this->row, '_shipping_first_name' );
		$this->shiplastname = getInformation( $this->row, '_shipping_last_name' );
		$this->shipCompany = getInformation( $this->row, '_shipping_company' );
		$this->shipaddress = getInformation( $this->row, '_shipping_address_1' );
		$this->shipstreet2 = getInformation( $this->row, '_shipping_address_2' );
		$this->shipxaddress = getInformation( $this->row, '_shipping_address_1' );
		$this->shipcity = getInformation( $this->row, '_shipping_city' );
		$this->shipstate = getInformation( $this->row, '_shipping_state' );
		$this->shippostcode = getInformation( $this->row, '_shipping_postcode' );
		$this->shipcountry = getInformation( $this->row, '_shipping_country' );
		$this->cardtype = getInformation( $this->row, '_payment_method_title' );
		
		$this->freight = getInformation( $this->row, '_order_shipping' ); // Shipping Fee
		$this->tax = ((float)getInformation( $this->row, '_order_tax' ))+((float)getInformation( $this->row, '_order_shipping_tax' )); //Tax Fee
		$this->discount = ((float)getInformation( $this->row, '_order_discount' ))+((float)getInformation( $this->row, '_cart_discount' )); // Discount
		$this->fees = ''; // Add Fee
		
		// Ajout des coupons
		if ( getCoupons( $this->row ) != null ) {
			$coupons = getCoupons( $this->row );
			foreach( $coupons as $coupon ) {
				/*var_dump( $coupon );*/
				array_push($this->coupons, 'Coupon : ' . $coupon['order_item_name']);	
			}
		}
		
		// Ajout des notes
		$notes = getOrderNotes( $this->row['ID'] );
		foreach ( $notes as $note ) {
			if ( getNotePrivacy( $note['comment_ID'] ) == 1 ) {
				/*echo 'ok' . $note['comment_ID'] . $note['comment_content'];*/
				array_push($this->coupons, $note['comment_content']);
			} else if ( getNotePrivacy( $note['comment_ID'] ) == 0  ) {
				array_push($this->privateNotes, $note['comment_content']);
			}
		}
		
		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."woocommerce_order_items WHERE order_id = " . $this->row['ID'] . " AND order_item_type = 'line_item'"
						, ARRAY_A);

		for ($k = 0; $k < count( $rows );$k ++) {
				array_push($this->items,new Item($this->software, $this->date,$rows[$k]));
		}
		
	}
	
	protected function setInfoWPeCommerce() {	
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", $this->row['date'] );
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", $this->row['date'] );
		$this->shipoption = $this->row['shipping_option'];
		$this->status = $this->row['processed'];
		$this->firstname =  getInformation( $this->row, 2);
		$this->middlename = '';
		$this->lastname = getInformation( $this->row, 3 );
		/*$this->company = getInformation( $this->row, '_billing_company' );*/
		$this->address = getInformation( $this->row, 4 );
		$this->xaddress = '';
		$this->street2 = '';
		$this->street3 = '';
		$this->city = getInformation( $this->row, 5 );
		$this->state = getInformation( $this->row, 6 );
		$this->postcode = getInformation( $this->row, 8 );
		$this->country = getInformation( $this->row, 7 );
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->phone = getInformation( $this->row, 18 );
		$this->email = getInformation( $this->row, 9 );
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = getInformation( $this->row, 11 );
		$this->shiplastname = getInformation( $this->row, 12 );
		$this->shipaddress = getInformation( $this->row, 13 );
		$this->shipstreet2 = '';
		$this->shipxaddress = '';
		$this->shipcity = getInformation( $this->row, 14 );
		$this->shipstate = getInformation( $this->row, 15 );
		$this->shippostcode = getInformation( $this->row, 17 );
		$this->shipcountry = getInformation( $this->row, 16 );
		$this->cardtype = $this->row['gateway'];
		
		$this->freight = $this->row['base_shipping']; // Shipping Fee
		$this->tax = $this->row['wpec_taxes_total']; //Tax Fee
		$this->discount = $this->row['discount_value']; // Discount
		$this->fees = ''; // Add Fee
		
		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."wpsc_cart_contents WHERE purchaseid = " . $this->row['id'] , ARRAY_A);

		for ($k = 0; $k < count( $rows );$k ++) {
				$this->freight += (float)$rows[$k]['pnp']; // On ajoute les shipp propres aux items
				/*$this->tax += (float)$rows[$k]['tax_charged'];*/ // On ajoute pas les taxes si elles sont inclues dans le prix de l'article
				array_push($this->items,new Item($this->software, $this->date,$rows[$k]));
		}
		
		if ( $this->row['discount_data'] != null ) {
			// On ne peut avoir qu'un seul coupon sur WPeCommerce
			array_push($this->coupons, 'Coupon : ' . $this->row['discount_data'] );	
		}
		
		if ( $this->row['notes'] != null ) {
			array_push($this->privateNotes, $this->row['notes'] );	
		}
		
	}
	
	protected function setInfoCart66() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/cart66/functionsCart66.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", strtotime( $this->row['ordered_on'] ) );
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", strtotime( $this->row['ordered_on'] ) );
		$this->shipoption = $this->row['shipping_method'];
		$this->status = getStatus( $this->row['status'] );
		$this->firstname =  $this->row['bill_first_name'];
		$this->middlename = '';
		$this->lastname = $this->row['bill_last_name'];
		$this->company = '';
		$this->address = $this->row['bill_address'];
		$this->xaddress = '';
		$this->street2 = $this->row['bill_address2'];
		$this->street3 = '';
		$this->city = $this->row['bill_city'];
		$this->state = $this->row['bill_state'];
		$this->postcode = $this->row['bill_zip'];
		$this->country = $this->row['bill_country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->phone = $this->row['phone'];
		$this->email = $this->row['email'];
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = $this->row['ship_first_name'];
		$this->shiplastname = $this->row['ship_last_name'];
		$this->shipaddress = $this->row['ship_address'];
		$this->shipstreet2 = $this->row['ship_address2'];
		$this->shipxaddress = '';
		$this->shipcity = $this->row['ship_city'];
		$this->shipstate = $this->row['ship_state'];
		$this->shippostcode = $this->row['ship_zip'];
		$this->shipcountry = $this->row['ship_country'];
		$this->cardtype = $this->row['gateway'];
		
		$this->freight = $this->row['shipping']; // Shipping Fee
		$this->tax = $this->row['tax']; //Tax Fee
		$this->discount = $this->row['discount_amount']; // Discount
		$this->fees = ''; // Add Fee
		
		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."cart66_order_items WHERE order_id = " . $this->row['id'] , ARRAY_A);

		for ($k = 0; $k < count( $rows );$k ++) {
				array_push($this->items,new Item($this->software, $this->date,$rows[$k]));
		}
		
		// On ajoute les coupons
		if ( $this->row['coupon'] != 'none' ) {
			array_push($this->coupons, 'Coupon : ' . $this->row['coupon']);
		}
		
		// Ajout des notes
		if ( $this->row['notes'] != null ) {
			array_push($this->privateNotes, $this->row['notes']);
		}
	}
	
	protected function setInfoJigoshop() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php' );
		$this->id_order = $this->row['ID'];
		$this->createdDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['post_date_gmt']));
		$this->modifiedDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($this->row['post_modified_gmt']));
		$this->shipoption = getInformation( $this->row, 'shipping_service' );
		$this->status = getStatus( $this->row );
		$this->firstname =  getInformation( $this->row, 'billing_first_name' );
		$this->middlename = '';
		$this->lastname = getInformation( $this->row, 'billing_last_name' );
		$this->company = getInformation( $this->row, 'billing_company' );
		$this->address = getInformation( $this->row, 'billing_address_1' );
		$this->xaddress = '';
		$this->street2 = getInformation( $this->row, 'billing_address_2' );
		$this->street3 = '';
		$this->city = getInformation( $this->row, 'billing_city' );
		$this->state = getInformation( $this->row, 'billing_state' );
		$this->postcode = getInformation( $this->row, 'billing_postcode' );
		$this->country = getInformation( $this->row, 'billing_country' );
		if ( '' != $this->company ) {
			$this->residential = 'false';	
		}
		$this->phone = getInformation( $this->row, 'billing_phone' );
		$this->email = getInformation( $this->row, 'billing_email' );
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = getInformation( $this->row, 'shipping_first_name' );
		$this->shiplastname = getInformation( $this->row, 'shipping_last_name' );
		$this->shipaddress = getInformation( $this->row, 'shipping_address_1' );
		$this->shipstreet2 = getInformation( $this->row, 'shipping_address_2' );
		$this->shipxaddress = getInformation( $this->row, 'shipping_address_1' );
		$this->shipcity = getInformation( $this->row, 'shipping_city' );
		$this->shipstate = getInformation( $this->row, 'shipping_state' );
		$this->shippostcode = getInformation( $this->row, 'shipping_postcode' );
		$this->shipcountry = getInformation( $this->row, 'shipping_country' );
		$this->cardtype = getInformation( $this->row, 'payment_method_title' );
		
		$this->freight = getInformation( $this->row, 'order_shipping' ); // Shipping Fee
		$this->tax = ((float)getInformation( $this->row, 'order_tax_no_shipping_tax' ))+((float)getInformation( $this->row, 'order_shipping_tax' )); //Tax Fee
		$this->discount = ((float)getInformation( $this->row, 'order_discount' )); // Discount
		$this->fees = ''; // Add Fee
		
		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$table = $wpdb->prefix . "postmeta";
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $this->row['ID'] . " and meta_key = 'order_items'", ARRAY_A);
		
		$object = unserialize( $result['meta_value'] );
		foreach( $object as $key => $value ) {
				array_push($this->items,new Item( $this->software, $this->date,$value, $result['meta_id'] ));
		}
		
		/* Les coupons */
		if ( getCoupons( $this->row['ID'] ) != null ) {
			$coupons = getCoupons( $this->row['ID'] );
			foreach( $coupons as $coupon ) {
				array_push($this->coupons, 'Coupon : ' . $coupon['code']);	
			}
		}
		
		// La note customer
		if ( $this->row['post_excerpt'] != null ) {
			array_push($this->coupons, $this->row['post_excerpt']);
		}
	}
	
	public function getIdOrder() {
		return $this->id_order;	
	}
	
	public function getCreationDate() {
		return $this->createdDate;	
	}
	
	public function getModifiedDate() {
		return $this->modifiedDate;	
	}
	
	public function getShippingOption() {
		return $this->shipoption;	
	}
	
	public function getStatus() {
		return $this->status;	
	}
	
	public function getFirstName() {
		return $this->firstname;	
	}
	
	public function getMiddleName() {
		return $this->middlename;	
	}
	
	public function getLastName() {
		return $this->lastname;	
	}
	
	public function getCompany() {
		return $this->company;	
	}
	
	public function getShipCompany() {
		return $this->shipCompany;	
	}
	
	public function getAddress() {
		return $this->address;	
	}
	
	public function getStreet2() {
		return $this->street2;	
	}
	
	public function getStreet3() {
		return $this->street3;	
	}
	
	public function getXAddress() {
		return $this->xaddress;	
	}
	
	public function getCity() {
		return $this->city;	
	}
	
	public function getState() {
		return $this->state;	
	}
	
	public function getPostCode() {
		return $this->postcode;	
	}
	
	public function getCountry() {
		return $this->country;	
	}
	
	public function getResidential() {
		return $this->residential;	
	}
	
	public function getPhone() {
		return $this->phone;	
	}
	
	public function getEmail() {
		return $this->email;	
	}
	
	public function getFax() {
		return $this->fax;	
	}
	
	public function getWebsite() {
		return $this->website;	
	}
	
	public function getShipFirstname() {
		return $this->shipfirstname;	
	}
	
	public function getShipLastname() {
		return $this->shiplastname;	
	}
	
	public function getShipAddress() {
		return $this->shipaddress;	
	}
	
	public function getShipStreet2() {
		return $this->shipstreet2;	
	}
	
	public function getShipCity() {
		return $this->shipcity;	
	}
	
	public function getShipState() {
		return $this->shipstate;	
	}
	
	public function getShipCountry() {
		return $this->shipcountry;	
	}
	
	public function getShipPostcode() {
		return $this->shippostcode;	
	}
	
	public function getCardtype() {
		return $this->cardtype;	
	}
	
	public function getItems() {
		return $this->items;	
	}
	
	public function getFreight() {
		return $this->freight;	
	}
	
	public function getTax() {
		return $this->tax;	
	}
	
	public function getDiscount() {
		return $this->discount;	
	}
	
	public function getFee() {
		return $this->fee;	
	}
	
	public function getCoupons() {
		return $this->coupons;	
	}
	
	public function getPrivateNotes() {
		return $this->privateNotes;	
	}
}