<?php 
 
require_once '../include/DbOperations.php';
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['Item_name'])){
        $db = new DbOperation(); 
 
        if($db->get_item_ID($_POST['Item_name'])){
            $user = $db->get_item_info($_POST['Item_name']);
            $response['error'] = false; 
            $response['Item_ID'] = $user['Item_ID'];
            $response['Item_name'] = $user['Item_name'];
            $response['Price'] = $user['Price'];
            #$response['User_password'] = $user['User_password'];
            $response['Amount'] = $user['Amount'];
            $response['Delivery_fee'] = $user['Delivery_fee'];
            $response['Discount'] = $user['Discount'];

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