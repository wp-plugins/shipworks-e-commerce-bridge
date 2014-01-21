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
		if ( null == getItemInfo( $this->row, '_variation_id' ) ) {
			// Dans ce cas le variation Id vaut l'id du produit ce qui est bon
			$variationId = getItemInfo( $this->row, '_product_id' );
		} else {
			// Dans ce cas l'id est celui de la variation qui va permettre d'aller cherche le sku et le prix
			$variationId = getItemInfo( $this->row, '_variation_id' );	
		}
		// On veut dans tous les cas enregistrer l'id du produit original pour avoir le bon nom
		$productId = getItemInfo( $this->row, '_product_id' );
		$this->code = getProductInfo( $variationId, '_sku' );
		$this->sku = getProductInfo( $variationId, '_sku' );
		$this->name = getProductName( $productId );
		$this->quantity = getItemInfo( $this->row, '_qty' );
		// Cas ou on a woocommerce Composite Products
		$this->price = getItemInfo( $this->row, '_line_total' );
		if ( isComposed( $this->row ) ) {
			$this->unitprice = 0;
		} else {
			$this->unitprice = getProductInfo( $variationId, '_price' );	
		}
		$this->weight = getProductInfo( $variationId, '_weight' );
	}
	
	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php' );
		$this->itemID = $this->row['order_item_id'];
		$this->productID = $this->row['prodid'];
		$this->code = getSKU( $this->row );
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
		$this->code = $this->row['item_number'];
		$this->sku = $this->row['item_number'];
		$this->name = $this->row['description'];
		$this->quantity = $this->row['quantity'];
		$this->price = '';
		$this->unitprice = $this->row['product_price'];
		$this->weight = getWeight( $this->row );
	}
	
	protected function setInfoJigoshop() {
		$this->itemID = $this->k;
		$this->productID = $this->row['id'];
		$this->code = getProductInfo( $this->row['id'], 'sku' );
		$this->sku = getProductInfo( $this->row['id'], 'sku' );
		$this->name = $this->row['name'];
		$this->quantity = $this->row['qty'];
		$this->price = '';
		$this->unitprice = $this->row['cost'];
		$this->weight = getProductInfo( $this->row['id'], 'weight' );
	}
	
	protected function filtre() {
		$this->itemID = filtreEntier( $this->itemID );
		$this->productID = filtreFloat( $this->productID );
		$this->code = filtreString( $this->code );
		$this->sku = filtreString( $this->sku );
		$this->name = filtreString( $this->name );
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