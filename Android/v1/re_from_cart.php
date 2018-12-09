<?php

require_once '../include/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if (isset($_POST['User_ID']) and isset($_POST['tPrice'])){

		$db = new DbOperation();
		if ($db -> checkBalance($_POST['tPrice'],$_POST['User_ID'])){
			if($db->buy($_POST['User_ID'],$_POST['tPrice'])){
				$db->up($_POST['User_ID'],$_POST['tPrice']);
				$response['error'] = false;
				$response['message'] = "Buy successfully";
			}else{
				$response['error'] = true; 
            	$response['message'] = "Invalid username or password";   
			}
		}else{
			$response['error'] = true; 
        	$response['message'] = "Balance is not enough";
		}

	}else{
		$response['error'] = true; 
        $response['message'] = "Required fields are missing";
	}
}

echo json_encode($response);