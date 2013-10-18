<?php
class Item
{
	protected $software;
	protected $date;
	protected $row;
	
	protected $itemID;
	protected $productID;
	protected $code;
	protected $sku;
	protected $name;
	protected $quantity;
	protected $price;
	protected $unitprice;
	protected $unitcost = 0;
	protected $weight = 0;
	
	//Param Shopperpress 
	protected $k;

	public function __construct( $software, $date, $row, $k = 0 ) {
		$this->software = $software;
		$this->date = $date;
		$this->row = $row;
		$this->k = $k;
        $this->setInformations();
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
		} // Cas Cart66
		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
			if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 5 ) ) {
					$this->setInfoCart66();
			}
		} // Cas Jigoshop
		else if ( 'Jigoshop' == $this->software->getSoftware() ) {
			if ( $split[0] >= 1 ) {
					$this->setInfoJigoshop();
			}
		}
		// On filtre les champs
		$this->filtre();
	}
	
	protected function setInfoShopperpress() {
		$product = '';
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopperpress/functionsShopperpress.php');
		$this->itemID = getItemInformation($this->row,$this->k,"SKU");
		$this->productID = getItemInformation($this->row,$this->k,"SKU");
		$this->code = getItemInformation($this->row,$this->k,"SKU");
		$this->sku = getItemInformation($this->row,$this->k,"SKU");
		if ($sku != '') : $product = $sku; else : $product = $items['product']; endif;
		$this->name = getItemInformation($this->row,$this->k,"Name");
		$this->quantity = getItemInformation($this->row,$this->k,"Qty");
		$this->price = getItemInformation($this->row,$this->k,"Price");
		$this->unitprice = (((float)substr($this->price,3,strlen($this->price)-2))/((float)$this->quantity));
		$this->weight = setWeight($this->sku);
	}
	
	protected function setInfoShopp() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php' );
		$this->itemID = $this->row['sku'];
		$this->productID = $this->row['product'];
		$this->code = $this->row['sku'];
		$this->sku = $this->row['sku'];
		$this->name = $this->row['name'];
		$this->quantity = $this->row['quantity'];
		$this->price = $this->row['price'];
		$this->unitprice = $this->row['unitprice'];
		$this->weight = getWeight($this->price);
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php' );
		$this->itemID = $this->row['order_item_id'];
		$this->productID = getItemInfo( $this->row, '_product_id' );
		$this->code = getItemInfo( $this->row, '_product_id' );
		$this->sku = getProductInfo( $this->productID, '_sku' );
		$this->name = getProductName( $this->productID );
		$this->quantity = getItemInfo( $this->row, '_qty' );
		$this->price = getItemInfo( $this->row, '_line_total' );
		$this->unitprice = getProductInfo( $this->productID, '_price' );
		$this->weight = getProductInfo( $this->productID, '_weight' );
	}
	
	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php' );
		$this->itemID = $this->row['order_item_id'];
		$this->productID = $this->row['prodid'];
		$this->code = '';
		$this->sku = getSKU( $this->row );
		$this->name = $this->row['name'];
		$this->quantity = $this->row['quantity'];
		$this->price = '';
		$this->unitprice = $this->row['price'];
		$this->weight = getWeight( $this->row );
	}
	
	protected function setInfoCart66() {
		$this->itemID = $this->row['id'];
		$this->productID = $this->row['product_id'];
		$this->code = '';
		$this->sku = $this->row['id'];
		$this->name = $this->row['description'];
		$this->quantity = $this->row['quantity'];
		$this->price = '';
		$this->unitprice = $this->row['product_price'];
		$this->weight = getWeight( $this->row );
	}
	
	protected function setInfoJigoshop() {
		$this->itemID = $this->k;
		$this->productID = $this->row['id'];
		$this->code = '';
		$this->sku = '';
		$this->name = $this->row['name'];
		$this->quantity = $this->row['qty'];
		$this->price = '';
		$this->unitprice = $this->row['cost'];
		$this->weight = getProductInfo( $this->row['id'], 'weight' );
	}
	
	protected function filtre() {
		$this->itemID = filtreEntier( $this->itemID );
		$this->productID = filtreFloat( $this->productID );
		$this->code = filtreFloat( $this->code );
		$this->sku = filtreTitle( $this->sku );
		$this->name = filtreTitle( $this->name );
		$this->quantity = filtreEntier( $this->quantity );
		$this->price = filtreFloat( $this->price );
		$this->unitprice = filtreFloat( $this->unitprice );
		$this->weight = filtreFloat( $this->weight );
	}
	
	public function getItemID() {
		return $this->itemID;	
	}
	
	public function getProductID() {
		return $this->productID;	
	}
	
	public function getCode() {
		return $this->code;	
	}
	
	public function getSku() {
		return $this->sku;	
	}
	
	public function getName() {
		return $this->name;	
	}
	
	public function getQuantity() {
		return $this->quantity;
	}
	
	public function getPrice() {
		return $this->	price;
	}
	
	public function getUnitPrice() {
		return $this->unitprice;	
	}
	
	public function getUnitCost() {
		return $this->unitcost;	
	}
	
	public function getWeight() {
		return 	$this->weight;
	}
}