<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<Error><Code><?php echo $statusManager->getCode() ; ?></Code>
		<Description><?php echo $statusManager->getDescription() ?></Description>
	</Error>
</ShipWorks>
