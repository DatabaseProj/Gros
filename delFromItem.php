<?php

require_once '../include/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
		$db = new DbOperation();
		if($db->delete_item()){
			$response['error'] = false;
			$response['message'] = "Detele item successfully";
		}else{
			$response['error'] = true; 
            $response['message'] = "Some error happen";   
		}

	}else{
		$response['error'] = true; 
        $response['message'] = "Required fields are missing";
	}




echo json_encode($response);