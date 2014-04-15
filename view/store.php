<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<Store>
		<Name><?php echo $user->getCompanyName(); ?></Name>
		<CompanyOrOwner><?php echo $user->getCompanyName(); ?></CompanyOrOwner>
		<Email><?php echo $user->getSupport(); ?></Email>
		<Street1><?php echo $user->getStreet1(); ?></Street1>
		<Street2><?php echo $user->getStreet2(); ?></Street2>
		<Street3><?php echo $user->getStreet3(); ?></Street3>
		<City><?php echo $user->getCity(); ?></City>
		<State><?php echo $user->getState(); ?></State>
		<PostalCode><?php echo $user->getZip(); ?></PostalCode>
		<Country><?php echo $user->getCountry(); ?></Country>
		<Phone><?php echo $user->getPhone(); ?></Phone>
		<Website><?php echo get_home_url(); ?></Website>
	</Store>
</ShipWorks>
