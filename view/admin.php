<?php if (isset($message)) { ?>
	<div class="updated">
		<p><strong>
			<?php echo $message; ?>
			</strong></p>
	</div>
	<?php } ?>
<?php if ( $software->isCompatible() ) { ?>
<div class="highlight compatible">
	<p>
		<?php echo __( $software->getCompatibleMessage() ); ?>
	</p>
</div>
<?php  } else { ?>
		<div class="highlight not-compatible">
			<p>
				<?php echo __( $software->getNotCompatibleMessage() ); ?>
			</p>
		</div>
<?php } ?>
<form name="shipworks_account_form" method="post" action="<?php echo PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/control/controlAdmin.php'?>">
	<?php    echo "<h3>" . __( 'Shipworks Account' ) . "</h3>"; ?>
	<p>Account you will enter in Shipworks Generic Module, to see in detail how to proceed click here</p>
	<table class="form-table">
		<tbody>
			<tr>
				<td align="right"><strong>Shipworks Username<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getUsername(); ?>" size="30" name="username" required></td>
			</tr>
			<tr>
				<td align="right"><strong>Shipworks Password<span class="required">*</span></strong></td>
				<td align="left"><input type="password" value="<?php echo $user->getPassword(); ?>" size="30" name="password" required></td>
			</tr>
			<tr>
				<td></td>
				<td align="left"><input type="submit" class="button-primary" value="<?php if(isset($boutonUpdate)) echo $boutonUpdate; else echo 'Create'; ?>" name="send-credentials"></td>
			</tr>
			<tr>
				<td align="right" valign="top"><strong>URL Generic Module</strong></td>
				<td align="left"><strong><?php echo SHIPWORKSWORDPRESS_URL; ?></strong><br />
					<span style="font-size:x-small">(Please enter this url when you will set up your Shipworks Account)</span></td>
			</tr>
		</tbody>
	</table>
</form>
<form name="shipworks_address_form" method="post" action="<?php echo PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/control/controlAdmin.php'?>">
	<?php    echo "<h3>" . __( 'Store Address' ) . "</h3>"; ?>
	<p>Please fill the form below, it should be your store address from packages are shipping</p>
	<table class="form-table">
		<tbody>
			<tr>
				<td align="right"><strong>Company Name<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getCompanyName(); ?>" size="30" name="company_name"></td>
			</tr>
			<tr>
				<td align="right"><strong>Street Line 1<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getStreet1(); ?>" size="50" name="street1" required></td>
			</tr>
			<tr>
				<td align="right"><strong>Street Line 2</strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getStreet2(); ?>" size="50" name="street2"></td>
			</tr>
			<tr>
				<td align="right"><strong>Street Line 3</strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getStreet3(); ?>" size="50" name="street3"></td>
			</tr>
			<tr>
				<td align="right"><strong>City<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getCity(); ?>" size="30" name="city" required></td>
			</tr>
			<tr>
				<td align="right"><strong>State</strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getState(); ?>" size="30" name="state"></td>
			</tr>
			<tr>
				<td align="right"><strong>Zip Code<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getZip(); ?>" size="30" name="zip" required></td>
			</tr>
			<tr>
				<td align="right"><strong>Country<span class="required">*</span></strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getCountry(); ?>" size="30" name="country" required></td>
			</tr>
			<tr>
				<td align="right"><strong>Phone</strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getPhone(); ?>" size="30" name="phone"></td>
			</tr>
			<tr>
				<td align="right"><strong>Email Support</strong></td>
				<td align="left"><input type="text" value="<?php echo $user->getSupport(); ?>" size="30" name="support"></td>
			</tr>
			<tr>
				<td></td>
				<td align="left"><input type="submit" class="button-primary" value="<?php if(isset($boutonUpdateAdresse))  echo $boutonUpdateAdresse; else echo 'Create'; ?>" name="send-address"></td>
			</tr>
		</tbody>
	</table>
</form>
