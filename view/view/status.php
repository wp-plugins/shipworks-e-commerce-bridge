<?php 
$order = htmlspecialchars($_POST['order']);
					$status = htmlspecialchars($_POST['status']);
if($status==0) {
			$status=0;
		}
		elseif ($status==1) {
			$status=3;
		}
		elseif ($status==2) {
			$status=5;
		}
		elseif ($status==3) {
			$status=6;
		}
		elseif ($status==4) {
			$status=7;
		}
		elseif ($status==5) {
			$status=8;
		}
		$doc = new DomDocument('1.0', 'UTF-8');
        $root = $doc->createElement("ShipWorks"); // create the root element
        $root->setAttribute("moduleVersion", "3.1.22.3273"); // add an attribute
        $root->setAttribute("schemaVersion", "1.0.0"); // add an attribute
        $root = $doc->appendChild($root); // we insere this element in the root ShipWorks
		
		if ($order == '' or $status == '') :
			if ($order == '' and $status == '') :
				code($doc, $root, 'ERR001', 'Order and Status not communicate correctly');
			elseif ($order == '' and $status != '') :
				code($doc, $root, 'ERR002', 'Order not communicate correctly');
			elseif ($order != '' and $status == '') :
				code($doc, $root, 'ERR003', 'Status not communicate correctly');
			endif;
		else : //no mistake with not communicate Order or Status
		// Checking database for Order and Status
			global $wpdb;
			$prefix = $wpdb->prefix;
	
			$query = "SELECT * FROM ".$prefix."orderdata WHERE autoid = '".$order."'";
			
			$results = mysql_query( $query );
			$nb_order = mysql_num_rows($results); //check if the order is in the database
			if ( $nb_order == 0 ) : 
				code($doc, $root, 'ERR004', 'The order is not in the Database');
				
			else :	
				$query = "UPDATE ".$prefix."orderdata SET order_status = '".$status."' WHERE autoid = '".$order."'";
				$results = mysql_query( $query );
				if(!$results) :
					code($doc, $root, 'ERR005', "The Status coudn't be update in the database");
				else :
					$success = $doc->createElement("UpdateSuccess");
					$success = $root->appendChild($success);
				endif;
			endif;
		endif;
			
	
	$xml_string = $doc->saveXML();
	echo $xml_string;
	
	function code($doc, $root, $codeNumb, $codeTitle) {
		$error = $doc->createElement("Error");
		$error = $root->appendChild($error);

		$code = $doc->createElement("Code");
		$code = $error->appendChild($code);
		$text_code = $doc->createTextNode($codeNumb);
		$text_code = $code->appendChild($text_code);
	
		$description = $doc->createElement("Description");
		$description = $error->appendChild($description);
		$text_description = $doc->createTextNode($codeTitle);
		$text_description = $description->appendChild($text_description);
	return $doc;
}