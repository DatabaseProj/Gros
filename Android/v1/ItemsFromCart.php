<?php

require_once '../include/DbOperations.php';

$response = array();

if ($_SERVER['REQUEST_METHOD']=='POST'){
    if( isset($_POST['User_ID']) )
        { 
	    $db = new DbOperation(); 
	    $user = $db->get_allitemof_cart($_POST['User_ID']);
	    if (true){
		    $response = $user;
	    }else{
		    $response['error'] = true; 
            $response['message'] = "return error";
	    }
	}else{
		$response['error'] = true; 
		$response['message'] = "Required fields are missed";
	}
   
}else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }

echo json_encode($response);