<?php if ( $hasPayed & $status == 'active' ) { ?>
<div class="highlight compatible">
	<p>
		The plugin is active and you can process an unlimited number of order.
	</p>
	<p>
		Your last payment was the : <?php echo " " . $datePayment; ?>
	</p>
	<p>
		Next payment will be one month later.
	</p>
	<div id="cancel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header">
		<h3 id="myModalLabel">Cancel Subscription</h3>
	  </div>
	  <div class="modal-body">
		<p>If you cancel your subscription the plugin will still be working for the month corresponding to the last payment.</p>
		<p>But no payment will be done in the futur, and when the last payed month will be over the plugin will not be working anymore.</p>
	  </div>
	  <div class="modal-footer">
	  	<form name="cancelSubscriptionForm" method="post" action="<?php PLUGIN_PATH_SHIPWORKSWORDPRESS . '../../shipworks-e-commerce-bridge/view/control/controlSubscription.php'?>">
			<input type="submit" class="button-primary" value="Cancel Subscription" name="cancel-subscription">
		</form>
	  </div>
	</div>
</div>
<?php  } else if ( $hasPayed & $status != 'active' ) { ?>
<div class="highlight compatible">
	<p>
		The subscription was canceled nevertheless the plugin is still active and you can process an unlimited number of order.
	</p>
	<p>
		Your last payment was the : <?php echo " " . $datePayment; ?>
	</p>
</div>
<?php  } else if ( !$hasPayed & $status == 'active' ) { ?>
<div class="highlight not-compatible">
	<p>
		You seems to have a subscription but the last payment was more than a month ago : <?php echo " " . $datePayment; ?>.
	</p>
	<p>
		There was probably an issue with you bank card. Please contact us : contact@advanced-creation.com.
	</p>
	<a class="button-primary" href="" >Cancel my subscription</a>
</div>
<?php } else { ?>
<div class="highlight not-compatible">
	<p>
		The plugin is not active for unlimited orders (more than 30/month). To activate it please follow this <a href="https://www.advanced-creation.com/get-your-shipworks-wordpress-plugin/" target="_blank">link</a> and get a subscription.
	</p>
</div>
<?php } ?>