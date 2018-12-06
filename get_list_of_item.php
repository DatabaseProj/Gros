<?php

require_once '../include/DbOperations.php';

$response = array();

if ($_SERVER['REQUEST_METHOD']=='POST'){

	$db = new DbOperation(); 

	$user = $db->get_list_of_item();
	if ($user != 0){
		$response = $user;
	}else{
		$response['error'] = true; 
        $response['message'] = "return error";
	}
	
	#$response['Item_ID'] = $user['Item_ID'];
	#$response['Item_name'] = $user['Item_name'];
	#$response['Price'] = $user['Price'];
	#$response['Amount'] = $user['Amount'];
	#$response['Delivery_fee'] = $user['Delivery_fee'];
	#$response['Discount'] = $user['Discount'];


}else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }

echo json_encode($response);