<?php
class Attribute
{
	protected $software;
	protected $date;
	protected $row;

	protected $attributeID;
	protected $name;
	protected $value;
	protected $price;

	public function __construct( $software, $date, $key, $value) {
		$this->software = $software;
		$this->date = $date;
		$this->row = $row;
		$this->name = $key;
		$this->value = $value;
		$this->setInformations();
		// On filtre les champs
		$this->filtre();
    }

	protected function setInformations() {
		$split = explode( '.' , $this->software->getVersion() );
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
		// On filtre les champs
		$this->filtre();
	}
	
	protected function setInfoShopperpress() {
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopperpress/functionsShopperpress.php');
		
	}
	
	protected function setInfoShopp() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php' );
		
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php' );
		$this->name = ucfirst( $this->name );
		$this->value = ucfirst( $this->value );
	}
	
	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php' );
			
	}
	
	protected function setInfoCart66() {
		
	}
	
	protected function setInfoJigoshop() {
		$this->name = ucfirst( substr( $this->name, 4, strlen( $this->name ) - 1 ) );
		$this->value = ucfirst( $this->value );
	}
	
	protected function filtre() {
		$this->attributeID = filtreEntier( $this->attributeID );
		$this->name = filtreString( $this->name );
		$this->value = filtreString( $this->value );
		$this->price = filtreFloat( $this->price );
	}
	
	public function gattributeID() {
		return $this->attributeID;	
	}
	
	public function getName() {
		return $this->name;	
	}
	
	public function getValue() {
		return $this->value;	
	}
	
	public function getPrice() {
		return $this->price;	
	}
}