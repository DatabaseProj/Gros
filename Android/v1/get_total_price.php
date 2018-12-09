<?php 
 
require_once '../include/DbOperations.php';
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['User_ID']) ){
        $db = new DbOperation(); 
        $user = $db->get_total_price($_POST['User_ID']);
        $response['cost'] = $user; 
        }
 
    else{
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }
}
 
echo json_encode($response);