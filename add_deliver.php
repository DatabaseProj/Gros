<?php 

require_once '../include/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['Delivery_ID']) and 
			isset($_POST['Deliverer_Name'])and
		        isset($_POST['Delivere_phone']) and
		            isset($_POST['num_order']) )
		{
		//operate the data further 

		$db = new DbOperation(); 

		$result = $db->add_delivery( $_POST['Delivery_ID'],$_POST['Deliverer_Name'],$_POST['Delivere_phone'],$_POST['num_order']);
		if($result == 1){
			$response['error'] = false; 
			$response['message'] = "Add delivery  successfully";
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