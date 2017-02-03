<?php

	$url =	"https://project-d2a61.firebaseio.com/messages.json";

	$data['firstname']		=	$_POST['firstname'];
	$data['lastname']	    = 	$_POST['lastname'];	

		
	$chatArray 			= 	array("chat_first"=>$_POST['firstname'],"chat_second"=>$_POST['lastname'] );  
	//print"<pre>";print_r($chatArray);exit();
	$data_string = json_encode($chatArray);
	$ch 		 = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);
	echo $result = curl_exec($ch);
?>