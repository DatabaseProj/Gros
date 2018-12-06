<?php 
 
require_once '../include/DbOperations.php';
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['User_Name']) and isset($_POST['User_password'])){
        $db = new DbOperation(); 
 
        if($db->userLogin($_POST['User_Name'], $_POST['User_password'])){
            $user = $db->getUserByUsername($_POST['User_Name']);
            $response['error'] = false; 
            $response['User_ID'] = $user['User_ID'];
            $response['Email'] = $user['Email'];
            $response['User_Name'] = $user['User_Name'];
            $response['User_password'] = $user['User_password'];
            $response['Balance'] = $user['Balance'];
            $response['Address'] = $user['Address'];
            $response['Phone'] = $user['Phone'];

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