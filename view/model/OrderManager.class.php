<?php
class OrderManager
{
	protected $software;
	protected $todayDate;
	protected $number;
	protected $hasPayed = false;
	protected $communicationError = false;
	protected $communicationMessage;

	public function __construct( $software, $date ) {
		$this->software = $software;
		$this->todayDate = $date;
        $this->setInformations();
    }
	
	protected function setInformations() {
		// On veut récupérer la date d'il y a 30 jours
		$dateUnMoi = gmdate("Y-m-d\TH:i:s\Z", time() - 60*60*24*30);
		$count = new Count($this->software, $dateUnMoi);
		$this->number = $count->getNumber();
		if ( !$this->isFree() ) {
			$this->setHasPayed();
		}
		// On a pas de cas ici ca ne dépend pas du template puisqu'on utilise directement la classe count
		$this->filtre();
	}
	
	protected function filtre() {
		$this->number = filtreEntier( $this->number );
	}
	
	public function isFree() {
		return $this->number <= 30 ;	
	}
	
	protected function setHasPayed() {
		$url = "http://www.advanced-creation.com/" . "wp-admin/admin.php?page=shipworks-admin" ;
		$urlClient = $_SERVER['HTTP_HOST'];
		$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'action' => 'control', 'url' => $urlClient ),
			'cookies' => array()
			)
		);
		
		if ( is_wp_error( $response ) ) {
		   	$error_message = $response->get_error_message();
			$this->communicationMessage = $error_message;
		  	$this->communicationError = true;
		} else {
			$doc = new DomDocument;
			$doc->loadXML($response['body']);
			$racine = $doc->documentElement;
			$hasPayed = $racine->getElementsByTagName('hasPayed')->item(0);
			if (strtolower(trim($hasPayed->firstChild->nodeValue)) == "true") {
				$this->hasPayed = true;
			} else {
				$this->hasPayed = false;
				/*echo strtolower($hasPayed->firstChild->nodeValue);*/
			}
		}
	}
	
	public function getFreeOrdersNumber( $start ) {
		$count = new Count( $this->software, $start );
		$number2 = $count->getNumber();
		$p = $this->number - $number2;
		if ( $p < 30 ) {
			return 30 - $p;	
		} else {
			return 0;	
		}
	}
	
	public function hasPayed() {
		return $this->hasPayed;
	}
	
	public function isCommunicationError() {
		return $this->communicationError;	
	}
	
	public function getCommunicationMessage() {
		return $this->communicationMessage;	
	}

}