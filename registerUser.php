<?php

require_once '../include/DbOperations.php';

$response = array();

if ($_SERVER['REQUEST_METHOD']=='POST') {
	if (
			isset($_POST['User_ID']) and
			isset($_POST['User_Name']) and 
			isset($_POST['Email'])	and 
			isset($_POST['User_password']) and
			isset($_POST['Balance']) and 
			isset($_POST['Address']) and
			isset($_POST['Phone']))
		{
		//operate method

		$db = new DbOperation();

		if($db->createUser(
			$_POST['User_ID'],
			$_POST['User_Name'],
			$_POST['User_password'],
			$_POST['Email'],
			$_POST['Balance'],
			$_POST['Address'],
			$_POST['Phone']
			)){
			
			$response['error'] = false;
			$response['message'] = "User registered successfully";
		}else{
			$response['error'] = True;
			$response['message'] = "Some error occured please try again";

		}
	}else{
			$response['error'] = True;
			$response['message'] = "Required fields are missing";
	}


}else{
	$response['error']=True;
	$response['message'] = 'Invalid_Request';
}

echo json_encode($response);