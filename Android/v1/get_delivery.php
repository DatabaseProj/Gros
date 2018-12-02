<?php 
 
require_once '../include/DbOperations.php';
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['Delivery_ID']) ){
        $db = new DbOperation(); 
 
        
        $user = $db->get_delivery($_POST['Delivery_ID']);
        $response['error'] = false; 
        $response['Delivery_ID'] = $user['Delivery_ID'];
        }
 
    }else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }
}
 
echo json_encode($response);