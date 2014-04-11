<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<StatusCodes>
	<?php $status = $statusCodes->getStatus(); ?>
		<?php foreach ($status as $i => $statu ) { ?>
		<StatusCode>
			<Code><?php echo $i ?></Code>
			<Name><?php echo $status[$i]; ?></Name>
		</StatusCode>
		<?php } ?>
	</StatusCodes>
</ShipWorks>