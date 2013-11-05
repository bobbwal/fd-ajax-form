<?php
if(isset($_POST) ){

	$this_form_spam = $_POST['firstname'];
	/* if firstname field is not empty then prevent form submission. 
	firstname field is likely to be completed by automated spambots.
	Humans won't complete this field as it's hidden. */
	if ($this_form_spam == "") {
		//form validation vars
		$formok = true;
		$errors = array();

		//sumbission data
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$date = date('d/m/Y');
		$time = date('H:i:s');

		//get form data
		$name = $_POST['name'];
		$email = $_POST['email'];
		
		//validate form data
		if(empty($name)){
			$formok = false;
			$errors[] = "Please enter your name";
		} 
		if(empty($email)){
			$formok = false;
			$errors[] = "Please enter your email";
		} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$formok = false;
			$errors[] = "Please enter a valid email address";
		}
		
		//send email if all is ok
		if($formok){
			$headers = "From: contact@ajax-abide.co.uk" . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			$emailbody =	"<p><strong>Name: </strong> {$name} </p>
							<p><strong>Email: </strong> {$email} </p>
							<hr>
							<p>This message was sent from the IP Address: {$ipaddress} on {$date} at {$time}</p>";

			mail("info@yourdomain.co.uk","AJAX Abide form response",$emailbody,$headers);
		}

		//what we need to return back to our form
		$returndata = array(
			'posted_form_data' => array(
				'name' => $name,
				'email' => $email
			),
			'form_ok' => $formok,
			'errors' => $errors
		);

		//if this is not an ajax request
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
				//set session variables
				session_start();
				$_SESSION['cf_returndata'] = $returndata;

				//redirect back to form
				header('location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
	else {
		//just send the spammers back, seeya
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
}