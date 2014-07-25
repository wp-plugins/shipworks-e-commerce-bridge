<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<Orders>
	<?php foreach( $orders->getOrders() as $order ) { ?>
	<?php 	if ( !isset( $numberLimite ) || ( isset( $numberLimite ) && $numberLimite > 0 ) ) {?>
		<Order>
			<OrderNumber><?php echo $order->getIdOrder() ?></OrderNumber>
			<OrderDate><?php echo $order->getCreationDate() ?></OrderDate>
			<LastModified><?php echo $order->getModifiedDate() ?></LastModified>
			<ShippingMethod><?php echo $order->getShippingOption() ?></ShippingMethod>
			<StatusCode><?php echo $order->getStatus() ?></StatusCode>
			<ShippingAddress>
				<FirstName><?php echo $order->getShipFirstname() ?></FirstName>
				<MiddleName><?php echo $order->getMiddleName() ?></MiddleName>
				<LastName><?php echo $order->getShipLastname() ?></LastName>
				<Company><?php echo $order->getCompany() ?></Company>
				<Street1><?php echo $order->getShipAddress() ?></Street1>
				<Street2><?php echo $order->getShipStreet2() ?></Street2>
				<Street3></Street3>
				<City><?php echo $order->getShipCity() ?></City>
				<State><?php echo $order->getShipState() ?></State>
				<PostalCode><?php echo $order->getShipPostcode() ?></PostalCode>
				<Country><?php echo $order->getShipCountry() ?></Country>
				<Residential><?php echo $order->getResidential() ?></Residential>
				<Phone><?php echo $order->getPhone() ?></Phone>
				<Fax><?php echo $order->getFax() ?></Fax>
				<Email><?php echo $order->getEmail() ?></Email>
				<Website><?php echo $order->getWebsite() ?></Website>
			</ShippingAddress>
			<BillingAddress>
				<FirstName><?php echo $order->getFirstName() ?></FirstName>
				<MiddleName><?php echo $order->getMiddleName() ?></MiddleName>
				<LastName><?php echo $order->getLastName() ?></LastName>
				<Company><?php echo $order->getCompany() ?></Company>
				<Street1><?php echo $order->getAddress() ?></Street1>
				<Street2><?php echo $order->getStreet2() ?></Street2>
				<Street3><?php echo $order->getStreet3() ?></Street3>
				<City><?php echo $order->getCity() ?></City>
				<State><?php echo $order->getState() ?></State>
				<PostalCode><?php echo $order->getPostCode() ?></PostalCode>
				<Country><?php echo $order->getCountry() ?></Country>
				<Residential><?php echo $order->getResidential() ?></Residential>
				<Phone><?php echo $order->getPhone() ?></Phone>
				<Fax><?php echo $order->getFax() ?></Fax>
				<Email><?php echo $order->getEmail() ?></Email>
				<Website><?php echo $order->getWebsite() ?></Website>
			</BillingAddress>
			<Payment>
				
			</Payment>
			<Notes>
			<?php if ( $order->getCoupons() != null ) { ?>
				<?php foreach( $order->getCoupons() as $coupon ) { ?>
				<Note public="true"><?php echo $coupon;?></Note>
				<?php } 
				}
				?>
			<?php if ( $order->getPrivateNotes() != null ) { ?>
				<?php foreach( $order->getPrivateNotes() as $note ) { ?>
				<Note public="false"><?php echo $note;?></Note>
				<?php } 
				}?>		
			</Notes>
			<Items>			
			<?php foreach( $order->getItems() as $item ) { ?>
				<Item>
					<ItemID><?php echo $item->getItemID(); ?></ItemID>
					<ProductID><?php echo $item->getProductID(); ?></ProductID>
					<Code><?php echo $item->getCode(); ?></Code>
					<SKU><?php echo $item->getSku(); ?></SKU>
					<Name><?php echo $item->getName(); ?></Name>
					<Quantity><?php echo $item->getQuantity(); ?></Quantity>
					<UnitPrice><?php echo $item->getUnitPrice(); ?></UnitPrice>
					<UnitCost><?php echo $item->getUnitCost(); ?></UnitCost>
					<Image><?php echo $item->getImage(); ?></Image>
					<ThumbnailImage><?php echo $item->getImageThumbnail(); ?></ThumbnailImage>
					<Weight><?php echo $item->getWeight(); ?></Weight>
					<?php if( $item->getAttributes() != null ) { ?>
					<Attributes>
					<?php foreach( $item->getAttributes() as $i => $attribute ) { ?>
						<Attribute>
							<AttributeID><?php echo $i; ?></AttributeID>
							<Name><?php echo $attribute->getName(); ?></Name>
							<Value><?php echo $attribute->getValue(); ?></Value>
							<Price><?php echo $attribute->getPrice(); ?></Price>
						</Attribute>
					<?php } ?>
					</Attributes>
					<?php } ?>
				</Item>
			<?php } ?>
			</Items>
			<Totals>
				<Total class="TAX"><?php echo $order->getTax(); ?></Total>
				<Total class="SHIPPING"><?php echo $order->getFreight() ?></Total>
				<Total class="Discount" impact="subtract"><?php echo $order->getDiscount() ?></Total>
				<Total class="FEE"><?php echo $order->getFee() ?></Total>
			</Totals>
		</Order>
		<?php if ( isset( $numberLimite ) ) { $numberLimite--; }
				} ?>
		<?php } ?>
	</Orders>
</ShipWorks>
