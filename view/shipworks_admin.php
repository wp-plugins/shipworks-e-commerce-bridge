
<form name="shipworks_account_form" method="post" action="<?php admin_url( 'options-general.php?page=shipworks-shopperpress' ); ?>">
	<?php    echo "<h3>" . __( 'Shipworks Account' ) . "</h3>"; ?>
	<p>Account you will enter in Shipworks Generic Module, to see in detail how to proceed click here</p>
	<table class="form-table">
		<tbody>
			<tr>
				<td align="right"><strong>Shipworks Username<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="" size="30" name="username_shipwork" required></td>
			</tr>
			<tr>
				<td align="right"><strong>Shipworks Password<span class="required">*</span></strong></td>
				<td align="left"><input type="password" value="<?php if(isset($password_shipwork)) echo $password_shipwork; ?>" size="30" name="password_shipwork" required></td>
			</tr>
			<tr>
				<td></td>
				<td align="left"><input type="submit" class="button-primary" value="<?php if(isset($buttonValueAccount)) echo $buttonValueAccount; else echo 'Create'; ?>" name="send_account"></td>
			</tr>
			<tr>
				<td align="right" valign="top"><strong>URL Generic Module</strong></td>
				<td align="left"><strong><?php echo SHIPWORKSWORDPRESS_URL; ?></strong><br />
					<span style="font-size:x-small">(Please enter this url when you will set up your Shipworks Account)</span></td>
			</tr>
		</tbody>
	</table>
</form>
