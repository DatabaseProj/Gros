<?php 
 
require_once '../include/DbOperations.php';
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='POST'){
    
        $db = new DbOperation(); 
 
        
        $user = $db->get_delivery();
        $response['error'] = false; 
        $response['Delivery_ID'] = $user['Delivery_ID'];
        #$response['Deliverer_Name'] = $user['Deliverer_Name'];
        #$response['Delivere_phone'] = $user['Delivere_phone'];
        #$response['num_order'] = $user['num_order'];
        
 
    }else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }

 
echo json_encode($response);