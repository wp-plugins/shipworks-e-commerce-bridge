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
	protected $image;
	protected $imageThumbnail;
	protected $attributes = Array();
	
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
		if ( $this->row['type'] == 'addon' ) {
			$this->itemID = getAddonSku( $this->row );
			$this->productID = $this->row['product'];
			$this->code = getAddonSku( $this->row );
			$this->sku = getAddonSku( $this->row );
			$this->name = $this->row['name'];
			$this->quantity = $this->row['quantity'];
			$this->price = $this->row['price'];
			$this->unitprice = getAddonPrice( $this->row );
			$this->weight = weightFilter( getAddonWeight( $this->row ) );
			$this->unitcost = $this->row['cost'];
		} else if ( isVariation( $this->row['price'] ) ) {
			$this->itemID = getProductSku( $this->row['price'] );
			$this->productID = $this->row['price'];
			$this->code = getProductSku( $this->row['price'] );
			$this->sku = getProductSku( $this->row['price'] );
			$this->name = $this->row['name'];
			$this->quantity = $this->row['quantity'];
			$this->price = $this->row['price'];
			$this->unitprice = getProductPrice( $this->row['price'] );
			$this->weight = weightFilter( getWeight($this->price) );
			$this->unitcost = $this->row['cost'];
			// On ajoute les attributs
			$attributes = getAttributes( $this->row['price'] );
			foreach( $attributes as $key => $attribute ) {
					array_push($this->attributes,new Attribute( $this->software, $this->date, getAttributeParent( $this->row['product'], trim( $attribute ) ), $attribute ));
			}
		} else {
			$this->itemID = getProductSku( $this->row['price'] );
			$this->productID = $this->row['price'];
			$this->code = getProductSku( $this->row['price'] );
			$this->sku = getProductSku( $this->row['price'] );
			$this->name = $this->row['name'];
			$this->quantity = $this->row['quantity'];
			$this->price = $this->row['price'];
			$this->unitcost = $this->row['cost'];
			$this->unitprice = getProductPrice( $this->row['price'] );
			$this->weight = weightFilter( getWeight($this->price) );	
		}
	}
	
	protected function setInfoWoocommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php' );
		$this->itemID = $this->row['order_item_id'];
		$this->productID = getItemInfo( $this->row, '_product_id' );
		if ( null == ( int ) getItemInfo( $this->row, '_variation_id' ) ) {
			// Dans ce cas le variation Id vaut l'id du produit ce qui est bon
			$variationId = getItemInfo( $this->row, '_product_id' );
		} else {
			// Dans ce cas l'id est celui de la variation qui va permettre d'aller cherche le sku et le prix
			$variationId = getItemInfo( $this->row, '_variation_id' );	
		}
		
		// On ajoute les attributs
		global $wpdb;
		$table = $wpdb->prefix . "woocommerce_order_itemmeta";
		$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE order_item_id = " . $this->row['order_item_id'] , ARRAY_A);
		foreach( $results as $row ) {
			if ( substr( $row['meta_key'], 0, 1 ) != "_" ) {
				// On regarde si pour cet item il existe des extra options : différent de 0
				// Si oui on enlève les champs qui on un id supérieur à celui de la ligne _tmcartepo_data : le plugin est vraiment mal fichu en base de donnée
				if ( isAttributeTMOption( $this->row['order_item_id'] ) != 0 ) {
					if ( $row['meta_id'] < isAttributeTMOption( $this->row['order_item_id'] ) ) {
						array_push($this->attributes,new Attribute( $this->software, $this->date, $row['meta_key'], $row['meta_value']));
					}
				} else {
					array_push($this->attributes,new Attribute( $this->software, $this->date, $row['meta_key'], $row['meta_value']));
				}
			}
		}
		// On ajoute les attributs dans le cas ou on a des Extra product Options
		if (  isAttributeTMOption( $this->row['order_item_id'] ) != 0 ) {
			$tab = getTMOptionTab( $this->row['order_item_id'] );
			foreach( $tab as $option ) {
				array_push($this->attributes,new Attribute( $this->software, $this->date, $option['value'], 'Extra Options(s)', $option['price']));
			}
			
		}
		// On veut dans tous les cas enregistrer l'id du produit original pour avoir le bon nom
		$productId = getItemInfo( $this->row, '_product_id' );
		if( null != getProductInfo( $variationId, '_sku' ) ) {
			$this->code = getProductInfo( $variationId, '_sku' );
			$this->sku = getProductInfo( $variationId, '_sku' );
		} else {
			$this->code = getProductInfo( $productId, '_sku' );
			$this->sku = getProductInfo( $productId, '_sku' );
		}
		$this->name = getProductName( $productId );
		$this->quantity = getItemInfo( $this->row, '_qty' );
		// Cas ou on a woocommerce Composite Products
		$this->price = getItemInfo( $this->row, '_line_total' );
		if ( isComposed( $this->row ) ) {
			$this->unitprice = 0;
		} else {
			// On regarde si il y a le plugin woocommerce-bulk-discount woocommerce-bulk-discount.php
			if ( is_plugin_active_custom( "woocommerce-bulk-discount/woocommerce-bulk-discount.php" ) ) {
				//$this->unitprice = getProductInfo( $variationId, '_price' );
				// On met le prix au cas où le variation id ne soit pas présent dans le champ
				$this->unitprice = getProductInfo( $variationId, '_price' );
				$bulkInfo = getProductInfo( $this->row['order_id'], '_woocommerce_t4m_discount_coeffs' );
				if ( $bulkInfo ) {
					$bulkData = json_decode($bulkInfo);
					foreach( $bulkData as $key => $value ) {
						if ( $key == $variationId && $value->coeff != 1 ) {
								// Si le variation id est présent on remplace le prix
								$this->unitprice = $value->coeff * $value->orig_price;
								//var_dump( $value->coeff );
						}
					}
					//var_dump( $bulkData );
				}
				//echo 'nico' . $bulkInfo;
			} else {
				$this->unitprice = getProductInfo( $variationId, '_price' );
			}
		}
		// Si le poid du variation vaut 0 on prend celui du parent
		if ( 0 != getProductInfo( $variationId, '_weight' ) ) {
			$this->weight = wooWeightNormal( getProductInfo( $variationId, '_weight' ), 'lbs');
		} else {
			$this->weight = wooWeightNormal( getProductInfo( $productId, '_weight' ), 'lbs');
		}
		// Les images
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $variationId ), 'Single Thumbs' );
		$imageThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $variationId ), 'Product Thumbs' );
		if( $image[0] == null ) {
			// Cas ou on a pas d'image pour le variation product
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->productID ), 'Single Thumbs' );
		}
		if( $imageThumbnail[0] == null ) {
			// Cas ou on a pas d'image pour le variation product
			$imageThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $this->productID ), 'Product Thumbs' );
		}
		$this->image = $image[0];
		$this->imageThumbnail = $imageThumbnail[0];
	}
	
	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php' );
		$this->itemID = $this->row['order_item_id'];
		$this->productID = $this->row['prodid'];
		$this->code = getSKU( $this->row );
		$this->sku = getSKU( $this->row );
		/* Les attributs sont dans le nom */
		$word = explode('(',trim($this->row['name']));
		global $wpdb;
		$table = $wpdb->prefix . "term_relationships";
		$rows = $wpdb->get_results("SELECT * FROM " . $table . " WHERE object_id = " . $this->row['prodid'] , ARRAY_A);
		// On ajoute les attributs
		if( $rows != null ) {
			foreach( $rows as $attribute ) {
				/*echo isVariation( $attribute['term_taxonomy_id'] ) . $attribute['term_taxonomy_id'] ;*/
				if( isVariation( $attribute['term_taxonomy_id'] ) ) {
					$key = getVariation( $attribute['term_taxonomy_id'] );
					$value = getVariationValue( $attribute['term_taxonomy_id'] );
					/*echo $attribute['term_taxonomy_id'];*/
					array_push($this->attributes,new Attribute( $this->software, $this->date, $key, $value));
				}
			}
		}
		$this->name = $word[0];
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
		
		if ( substr( $this->row['description'], -1 ) == ")" ) {
			$tab = explode( '(', $this->row['description'] );
			$this->name = $tab[0];
			$attributes = explode( ',', str_replace( ')', '', $tab[1] ) );
			foreach( $attributes as $attribute ) {
				array_push($this->attributes,new Attribute( $this->software, $this->date,'Attribute', $attribute ));
			}
		}
		
		$this->unitprice = $this->row['product_price'];
		$this->weight = getWeight( $this->row );
	}
	
	protected function setInfoJigoshop() {
		$this->itemID = $this->k;
		$this->productID = $this->row['id'];
		$unitprice;
		if ( null == $this->row['variation_id'] ) {
			// Dans ce cas le variation Id vaut l'id du produit ce qui est bon
			$variationId = $this->row['id'];

		} else {
			// Dans ce cas l'id est celui de la variation qui va permettre d'aller cherche le sku et le prix
			$variationId = $this->row['variation_id'];
			$split = explode( '.' , $this->software->getVersion() );
			if ( $split[0] > 1 || ( $split[0] == 1 & $split[1] >= 17 ) ) {
				$variations = $this->row["variation"];
				foreach( $variations as $key => $value ) {
					array_push($this->attributes,new Attribute( $this->software, $this->date,$key, $value ));
				}	
				$unitprice = $this->row['cost'];
			} else {
				//On crée les attributs
				$object = unserialize( getProductInfo( $variationId, 'order_items' ) );
				foreach( $object as $key => $value ) {
					array_push($this->attributes,new Attribute( $this->software, $this->date,$key, $value ));
				}
				$unitprice = $this->row['cost']/$this->row['qty'];
			}
			//On créer les attributs
			$object = unserialize( getProductInfo( $variationId, 'variation_data' ) );
			foreach( $object as $key => $value ) {
				array_push($this->attributes,new Attribute( $this->software, $this->date,$key, $value ));
			}
		}
		$this->code = getProductInfo( $variationId, 'sku' );
		$this->sku = getProductInfo( $variationId, 'sku' );
		$this->name = $this->row['name'];
		$this->quantity = $this->row['qty'];
		$this->price = '';
		$this->unitprice = $unitprice;
		$this->weight = getProductInfo( $variationId, 'weight' );
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
		$this->unitcost = filtreFloat( $this->unitcost );
		$this->weight = filtreFloat( $this->weight );
		$this->image = filtreString( $this->image );
		$this->imageThumbnail = filtreString( $this->imageThumbnail );
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
	
	public function getAttributes() {
		return 	$this->attributes;
	}
	
	public function getImage() {
		return 	$this->image;
	}
	
	public function getImageThumbnail() {
		return 	$this->imageThumbnail;
	}
}