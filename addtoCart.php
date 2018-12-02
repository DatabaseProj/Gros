<?php 

require_once '../include/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(
        isset($_POST['User_ID']) and 
            isset($_POST['Item_ID']))
        {
        //operate the data further 

        $db = new DbOperation(); 

        $result = $db->addItemToCart(    $_POST['User_ID'],
                                    $_POST['Item_ID']
                                );
        if($result == 1){
            $response['error'] = false; 
            $response['message'] = "User registered successfully";
        }elseif($result == 2){
            $response['error'] = true; 
            $response['message'] = "Some error occurred please try again";          
        }elseif($result == 0){
            $response['error'] = true; 
            $response['message'] = "It seems the items are already added in your cart, please choose other items 🙂";                        
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