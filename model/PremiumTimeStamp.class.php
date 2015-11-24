<?php
class PremiumTimeStamp {
	protected $current_timestamp;
	protected $saved_timestamp;

	public function __construct() {
		global $wpdb;
		$this->current_timestamp = time();

		$table = $wpdb->prefix . "shipworks_bridge";
		//$user = $wpdb->get_row("SELECT * FROM ".$table." WHERE id = 1", ARRAY_A);

		$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table' AND column_name = 'time_stamp'");
		if(empty($row)) {
			$wpdb->query("ALTER TABLE $table ADD time_stamp BIGINT NULL DEFAULT NULL");
		}
	}

	public function setTimeStamp() {
		global $wpdb;
		$table = $wpdb->prefix . "shipworks_bridge";
		$wpdb->update( $table, 
				array( 
						'time_stamp' => $this->current_timestamp
					), 
				array( 'id' => 1 )
		);
	}

	public function getTimeStamp() {
		global $wpdb;
		$table = $wpdb->prefix . "shipworks_bridge";
		$user = $wpdb->get_row("SELECT * FROM ".$table." WHERE id = 1", ARRAY_A);
		$this->saved_timestamp = $user['time_stamp'];
		return $this->saved_timestamp;
	}

	public function getSubscriptionValid() {
		$timestamp = $this->getTimeStamp();
		$oneweek = strtotime("-1 week");
		if($timestamp > $oneweek) {
			return true;
		} else {
			return false;
		}
		
	}

}
?>