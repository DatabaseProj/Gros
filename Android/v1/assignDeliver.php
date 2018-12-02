<?php 

require_once '../include/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(
        isset($_POST['Delivery_ID']) and 
            isset($_POST['User_ID']))
        {
        //operate the data further 

        $db = new DbOperation(); 

        $result = $db->assign_delivery($_POST['Delivery_ID'], $_POST['User_ID']);

        if($result == 1){
            $response['error'] = false; 
            $response['message'] = "Get delivery successfully";
        }else{
            $response['error'] = true; 
            $response['message'] = "Some error occurred please try again";          
        }

    }else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }
}else{
    $response['error'] = true; 
    $response['message'] = "Invalid Request";
}

echo json_encode($response);