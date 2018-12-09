<?php

require_once '../include/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if (isset($_POST['User_ID']) ){

		$db = new DbOperation();
		if($db->buy($_POST['User_ID'])){
			$response['error'] = false;
			$response['message'] = "remove successfully";
		}else{
			$response['error'] = true; 
            $response['message'] = "Invalid username or password";   
		}

	}else{
		$response['error'] = true; 
        $response['message'] = "Required fields are missing";
	}
}

echo json_encode($response);