<?php
class User
{
	protected $username_shipworks;
	protected $password_shipworks;
	protected $company_name;
	protected $street1;
	protected $street2;
	protected $street3;
	protected $city;
	protected $state;
	protected $zip;
	protected $country;
	protected $phone;
	protected $support;
	
	public function __construct()
    {
        global $wpdb;
		$table = $wpdb->prefix . "shipworks_bridge";
		$user = $wpdb->get_row("SELECT * FROM ".$table." WHERE id = 1", ARRAY_A);
         
        // Définir les variables avec les résultats de la base
        $this->username_shipworks = $user['username_shipworks'];
        $this->password_shipworks = $user['password_shipworks'];
		$this->company_name = $user['company_name'];
		$this->street1 = $user['street1'];
		$this->street2 = $user['street2'];
		$this->street3 = $user['street3'];
		$this->city = $user['city'];
		$this->state = $user['state'];
		$this->zip = $user['zip'];
		$this->country = $user['country'];
		$this->phone = $user['phone'];
		$this->support = $user['support'];
	
    }
	
	public function getUsername() {
		return $this->username_shipworks;
	}
	
	public function getPassword() {
		return $this->password_shipworks;
	}
	
	public function getCompanyName() {
		return $this->company_name;
	}
	
	public function getStreet1() {
		return $this->street1;
	}
	
	public function getStreet2() {
		return $this->street2;
	}
	
	public function getStreet3() {
		return $this->street3;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function getZip() {
		return $this->zip;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function getPhone() {
		return $this->phone;
	}
	
	public function getSupport() {
		return $this->support;
	}
	
	public function setCredentials($name,$password) {
		global $wpdb;
		$table = $wpdb->prefix . "shipworks_bridge";
		$name = htmlspecialchars($name);
		$password = htmlspecialchars($password);
		$this->username_shipworks = $name;
        $this->password_shipworks = $password;
		$wpdb->update( $table, 
				array( 
						'username_shipworks' => $name, 
		 				'password_shipworks' => $password
					), 
				array( 'id' => 1 )
		);
	}
	
	public function setAddress($company_name,$street1,$street2,$street3,$city,$state,$zip,$country,$phone,$support) {
		global $wpdb;
		$table = $wpdb->prefix . "shipworks_bridge";
		$company_name = htmlspecialchars($company_name);
		$street1 = htmlspecialchars($street1);
		$street2 = htmlspecialchars($street2);
		$street3 = htmlspecialchars($street3);
		$city = htmlspecialchars($city);
		$state = htmlspecialchars($state);
		$zip = htmlspecialchars($zip);
		$country = htmlspecialchars($country);
		$phone = htmlspecialchars($phone);
		$support = htmlspecialchars($support);
		$this->company_name = $company_name;
		$this->street1 = $street1;
		$this->street2 = $street2;
		$this->street3 = $street3;
		$this->city = $city;
		$this->state = $state;
		$this->zip = $zip;
		$this->country = $country;
		$this->phone = $phone;
		$this->support = $support;
		$wpdb->update( $table, 
				array( 
						'company_name' => $company_name,
						 'street1' => $street1, 
						 'street2' => $street2, 
						 'street3' => $street3, 
						 'city' => $city,
						 'state' => $state,
						 'zip' => $zip, 
						 'country' => $country,
						 'phone' => $phone,
						 'support' => $support
					), 
				array( 'id' => 1 )
		);
	}
}