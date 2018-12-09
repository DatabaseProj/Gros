<?php

require_once '../include/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if (isset($_POST['User_ID']) and 
			isset($_POST['Item_ID'])){

		$db = new DbOperation();
		if($db->delItemInCart($_POST['User_ID'], $_POST['Item_ID'])){
			$response['error'] = false;
			$response['message'] = "Delete successfully";
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