<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<Module>
		<Platform><?php echo $software->getSoftware(); ?></Platform>
		<Developer>Advanced Creation</Developer>
		<Capabilities>
			<DownloadStrategy>ByModifiedTime</DownloadStrategy>
			<OnlineCustomerID supported="false" dataType="numeric"/>
			<OnlineStatus supported="true" dataType="numeric" supportsComments="true"/>
			<OnlineShipmentUpdate supported="true"/>
		</Capabilities>
	</Module>
</ShipWorks>