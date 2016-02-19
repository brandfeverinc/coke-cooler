<?php

	if (isset($_REQUEST['name']) && isset($_REQUEST['email']) && isset($_REQUEST['question']) && isset($_REQUEST['item_id']) && isset($_REQUEST['category_id'])) {
		// send email
		$subject = "Cooler Demo Message Submission";
		$body_text = "Name: " . $_REQUEST['name'] . "\n";
		$body_text .= "Email: " . $_REQUEST['email'] . "\n";
		$body_text .= "Message: " . $_REQUEST['question'] . "\n";
		
		// default Coke email address here:
		$default_to_address = "jay.davis@phase3mc.com";
		
		$to_address = $default_to_address;
		
		// if we have an item contact, use it:
		if (trim($_REQUEST['item_id']) != "") {
		    // look up item ID's contact email:
		    include("inc/classes/Item.php");
		    $objItem = new Item($_REQUEST['item_id']);
		    if ($objItem->ContactEmail != "") {
		        $to_address = $objItem->ContactEmail;
		    }
		}

        // if we didn't have an item contact, check for category contact:
		if (trim($to_address) == $default_to_address && trim($_REQUEST['category_id']) != "") {
		    // look up category's contact email:
		    include("inc/classes/Category.php");
		    $objCategory = new Category($_REQUEST['category_id']);
		    if ($objCategory->ContactEmail != "") {
		        $to_address = $objCategory->ContactEmail;
		    }
		}
	
		include_once("inc/classes/PHPMailer.php");
		$mail = new PHPMailer();
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		$mail->ClearCCs();
		$mail->ClearBCCs();
		$mail->From = "donotreplay@coca-cola.com";
		$mail->FromName = "Cooler Demo";
		//$mail->AddReplyTo("info@coca-cola.com","Coca Cola");
		$mail->AddAddress($to_address);
		$mail->AddCC("tawania.harris@phase3mc.com");
		$mail->AddCC("greg.faulkner@phase3mc.com");
		$mail->AddCC("jay.davis@phase3mc.com");
		$mail->Sender = "donotreply@coca-cola.com";
		$mail->Subject = $subject;
		$mail->Body = $body_text;
		$mail->IsHTML(false);
		//$mail->AltBody = $text;
		$mail->Send();

		echo 1;
		return;
	}
	
	echo 0;

?>