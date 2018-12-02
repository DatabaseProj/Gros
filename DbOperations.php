<?php

	/**
	* 
	*/
	class DbOperation
	{
		private $con;
		
		function __construct()
		{
			# code...
			require_once dirname(__FILE__).'/DbConnect.php';

			$db = new DbConnect();

			$this->con = $db->connect();
		}

		/*CRUD -> C -> CREATE */

		public function createUser($uid,$username, $pass, $email,$balance,$add,$phone){
			$password = md5($pass);
			$stmt = $this->con->prepare("INSERT INTO Gros1.Customer (User_ID, User_Name, Email, User_password, Balance, Address, Phone) VALUES (?, ?, ?, ?, ?, ?, ?);");
			$stmt->bind_param("sssssss",$uid,$username,$email,$password,$balance,$add,$phone);

			if ($stmt->execute()) {
				$stmt->close();
				return 1;
				
			}else{
				$stmt->close();
				return 0;
			}
		}

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


		public function getUserByUsername($username){
            $stmt = $this->con->prepare("SELECT * FROM Gros1.Customer WHERE User_Name = ?;");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $result=$stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
        }

        
        public function addItemToCart($User_ID,$Item_ID){
			if($this->isUserExist($User_ID,$Item_ID)){
				return 0;
			}else{ 
			    $stmt =$this->con->prepare("INSERT INTO Gros1.Cart (User_ID, Item_ID) VALUES(?,?);");
			    $stmt->bind_param("ss",$User_ID,$Item_ID);

			    if($stmt->execute()){
				    return 1; 
			    }else{
				    return 0; 
			    }
			    $stmt->close();
			}
		}

        public function delItemInCart($uid,$cid){
        	$stmt = $this->con->prepare("DELETE FROM Gros1.Cart WHERE User_ID = ? AND Item_ID = ?;");
        	$stmt->bind_param("ss",$uid,$cid);
        	if($stmt->execute()){
        		return 1;
        	}else{
        		return 0;
        	}
        	$stmt->store_result();
        	$stmt->close();
        }

        public function get_item_info($Item_name){
			$stmt =$this->con->prepare("SELECT * FROM Gros1.Item WHERE Item_name = ?;");
			$stmt->bind_param("s",$Item_name);
            $stmt->execute();
            $result=$stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
		}

		public function get_list_of_item(){
			$stmt = $this->con->prepare("SELECT * FROM Gros1.Item;");
			$stmt->execute();
			$result = array();

			while($rows = $stmt->fetch())
                {
                    $results[] = $rows;
                }
			$stmt->close();
			return $result;
		}

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
			return $stmt->get_result()->fetch_assoc();
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