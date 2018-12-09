<?php

	/**
	* 
	*/
	class DbOperation
	{
		private $con;
		
		public function __construct()
		{
			# code...
			require_once dirname(__FILE__).'/DbConnect.php';

			$db = new DbConnect();

			$this->con = $db->connect();
		}

		/*CRUD -> C -> CREATE */
        # create the new user
		public function createUser($uid,$username, $pass, $email,$balance,$add,$phone){
			#$password = md5($pass);
			$stmt = $this->con->prepare("INSERT INTO Gros1.Customer (User_ID, User_Name, Email, User_password, Balance, Address, Phone) VALUES (?, ?, ?, ?, ?, ?, ?);");
			$stmt->bind_param("sssssss",$uid,$username,$email,$pass,$balance,$add,$phone);

			if ($stmt->execute()) {
				$stmt->close();
				return 1;
				
			}else{
				$stmt->close();
				return 0;
			}
		}
        #user log in 
		public function userLogin($username, $pass){
            #$password = md5($pass);
            $stmt = $this->con->prepare("SELECT User_ID FROM Gros1.Customer WHERE User_Name = ? AND User_password = ?;");
            $stmt->bind_param("ss",$username,$pass);
            $stmt->execute();
            $stmt->store_result(); 
            $row= $stmt->num_rows;
            $stmt->close();
            return $row > 0; 
        }

        #get user by input user name 
		public function getUserByUsername($username){
            $stmt = $this->con->prepare("SELECT * FROM Gros1.Customer WHERE User_Name = ?;");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $result=$stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
        }

        # system add the items to cart
        public function addItemToCart($User_ID,$Item_ID){
			if($this->isUserExist($User_ID,$Item_ID)){
				return 0;
			}else{ 
			    $stmt =$this->con->prepare("INSERT INTO Gros1.Cart (User_ID, Item_ID) VALUES(?,?);");
			    $stmt->bind_param("ss",$User_ID,$Item_ID);
			    $stmt->execute();
				$stmt->store_result();
        		$stmt = $this->con->prepare("UPDATE Gros1.Item SET Amount = (Amount-1) WHERE Item_ID = ?;");
        		$stmt->bind_param("s", $Item_ID);
        		$stmt->execute();
			    $stmt->close();
			    return 1;
			}
		}
        
        # system add the items to cart
        public function get_allitemof_cart($User_ID){
            #$stmt = $this->con->prepare("SELECT Item_name, Price, Delivery_fee, Discount FROM Gros1.Item WHERE Item_ID IN (SELECT Item_ID FROM Gros1.Cart WHERE User_ID = ?) ");
            $stmt = $this->con;
			$result = $stmt->query("SELECT Item_name, Price, Delivery_fee, Discount FROM Gros1.Item WHERE Item_ID IN (SELECT Item_ID FROM Gros1.Cart WHERE User_ID = '$User_ID') ");
			#$stmt->bind_param("s", $user_ID);
			#$stmt->execute();
			#$result = $stmt->get_result();
			if (mysqli_num_rows($result) ==0)
				return false;
			$response["it"] = array();

			while ($row = $result -> fetch_assoc()) {
					# code...
					$item = array();
					$item["Item_name"] = $row["Item_name"];
					$item["Price"] = $row["Price"];
					$item["Delivery_fee"] = $row["Delivery_fee"];
					$item["Discount"] = $row["Discount"];

					array_push($response["it"], $item);
				}
			$stmt->close();
			return $response["it"];
        }

        #delete the items in user's cart and update the item amount
        public function delItemInCart($uid,$cid){
        	$stmt = $this->con->prepare("DELETE FROM Gros1.Cart WHERE User_ID = ? AND Item_ID = ?;");
        	$stmt->bind_param("ss",$uid,$cid);
        	$stmt->execute();
        	$stmt->store_result();
        	$stmt = $this->con->prepare("UPDATE Gros1.Item SET Amount = (Amount+1) WHERE Item_ID = ?;");
        	$stmt->bind_param("s", $cid);
        	$stmt->execute();
        	$stmt->close();
        	return 1;
        }

        public function buy($uid,$tPrice){
        	$stmt = $this->con->prepare("DELETE FROM Gros1.Cart WHERE User_ID = ?;");
        	$stmt->bind_param("s",$uid);
        	$stmt->execute();
        	$stmt->store_result();
        	
        	#$stmt = $this->con;
        	#$stmt->query("UPDATE Gros1.Customer SET Balance = (Balance - '$tPrice') WHERE User_ID = 'uid'");
        	#$stmt->bind_param("ss",$tPrice, $uid);
        	#$stmt->execute();
        	#$stmt->close();
        	return 1;
        }

        public function up($uid,$tPrice){
        	$stmt = $this->con;
        	$stmt->query("UPDATE Gros1.Customer SET Balance = (Balance - '$tPrice') WHERE User_ID = '$uid'");
        	#$stmt->close;
        	return 1;
        }



        public function checkBalance($tPrice,$uid){
        	$stmt = $this->con->prepare("SELECT Balance FROM Gros1.Customer WHERE User_ID = ?;");
        	$stmt->bind_param("s",$uid);
        	$stmt->execute();
        	$result = $stmt->get_result()->fetch_assoc();
        	$stmt->close();
        	return $result['Balance'] > $tPrice;
        }

        #get the each item information for the item page
        public function get_item_info($Item_name){
			$stmt =$this->con->prepare("SELECT * FROM Gros1.Item WHERE Item_name = ?;");
			$stmt->bind_param("s",$Item_name);
            $stmt->execute();
            #$stmt->store_result();
            $row=$stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $row;
		}

        #get all items for the item page
		public function get_list_of_item(){
			$stmt = $this->con;
			$result = $stmt->query("SELECT * FROM Gros1.Item;");

			
			if (mysqli_num_rows($result) > 0){

				$response["it"] = array();

				while ($row = mysqli_fetch_array($result)) {
					# code...
					$item = array();
					$item["Item_ID"] = $row["Item_ID"];
					$item["Item_name"] = $row["Item_name"];
					$item["Price"] = $row["Price"];
					$item["Amount"] = $row["Amount"];
					$item["Delivery_fee"] = $row["Delivery_fee"];
					$item["Discount"] = $row["Discount"];

					array_push($response["it"], $item);
				}
				$stmt->close();
				return $response["it"];
			}
			
			$stmt->close();
			return 0;
			
		}
        #get the total price in the user's cart when user want to pay 
		public function get_total_price($User_ID){
            $stmt = $this->con;
            $result= $stmt->query("SELECT  SUM(Price * Discount + Delivery_fee) FROM Gros1.Item
				WHERE Item_ID IN (SELECT Item_ID FROM Gros1.Cart WHERE User_ID = '$User_ID') ;");

			#$stmt->bind_param("s", $User_ID);
			#$stmt->execute();
			#$val = $stmt->get_result();
			return $result -> fetch_assoc();	
        }
        
        #get item ID
		public function get_item_ID($Item_name){
			$stmt = $this->con->prepare("SELECT Item_ID FROM Gros1.Item WHERE Item_name = ?;");
			$stmt->bind_param("s",$Item_name);
			$stmt->execute();
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}

		#delete item if the amount is 0 
        public function delete_item(){
        	$stmt = $this->con->prepare("DELETE FROM Gros1.Item WHERE Amount=0;");
        	if($stmt->execute()){
        		return 1;
        	}else{
        		return 0;
        	}
        	$stmt->store_result();
        	$stmt->close();
        }

		# Get the delivery ID which the number of orders < 4
		public function get_delivery(){
			$stmt = $this->con->prepare("SELECT Delivery_ID FROM Gros1.Delivery WHERE num_order < 4");
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			return $row;
		}

		# Assign the delivery ID(num_order <4) to the user
		public function assign_delivery($Delivery_ID,$User_ID){
			$stmt =$this->con->prepare("INSERT INTO Gros1.Assign (Delivery_ID,User_ID) VALUES(?,?);");
			$stmt->bind_param("ss",$Delivery_ID,$User_ID);
			if($stmt->execute()){
				    return 1; 
			    }else{
				    return 0; 
			    }
			$stmt->close();
		}

		#Add new delivery
        public function add_delivery($Delivery_ID,$Deliverer_Name,$Delivere_phone,$num_order){
        	$stmt =$this->con->prepare("INSERT INTO Gros1.Delivery(Delivery_ID,Deliverer_Name,Delivere_phone,num_order)VALUES(?,?,?,?);");
 			$stmt->bind_param("ssss",$Delivery_ID,$Deliverer_Name,$Delivere_phone,$num_order);
            if($stmt->execute()){
				    return 1; 
			    }else{
				    return 0; 
			    }    
			$stmt->close();   
        }

        public function IsItemExist($uid, $cid){
        	$stmt = $this->con->prepare("SELECT User_ID FROM Gros1.Cart WHERE User_ID = ? OR Item_ID =?;");
			$stmt->bind_param("ss",$uid,$cid);
			$stmt->execute();
			#$stmt->store_result();
			$row= $stmt->num_rows;
			$stmt->close();
			return $row > 0;
        }
       
		private function isUserExist($username,$email){
			$stmt = $this->con->prepare("SELECT User_ID FROM Gros1.Customer WHERE User_Name = ? OR Email =?;");
			$stmt->bind_param("ss",$username,$email);
			$stmt->execute();
			$stmt->store_result();
			$row= $stmt->num_rows;
			$stmt->close();
			return $row > 0;
		}
	}