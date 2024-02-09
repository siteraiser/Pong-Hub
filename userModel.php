<?php
class userModel extends requestHandler{  
	//update if not found
	function checkUser($username,$uuid){
		$user = $this->getUserByUUID($uuid);
		
	
		
		if($user === false){
			//insert
			return false;//$this->insertUser($username,$wallet);
			
		}else if($user['username'] != $username){

			$query="UPDATE users SET 
			username=:username
			WHERE uuid=:uuid";
		
			$stmt=$this->pdo->prepare($query);
			$stmt->execute([':username'=>$username,':uuid'=>$uuid]);			
	
			return $this->getUserByUUID($uuid);

		}else{
			return $user;
		}

	}	
	
	//User handling
	function getUserByUUID($uuid){
		$stmt=$this->pdo->prepare("SELECT * FROM users WHERE uuid = ?");
		$stmt->execute([$uuid]);
		if($stmt->rowCount()==0){
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	function getUserById($id){
		$stmt=$this->pdo->prepare("SELECT * FROM users WHERE userid = ?");
		$stmt->execute([$id]);
		if($stmt->rowCount()==0){
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}	
	
	function getUserByWallet($wallet){
		$stmt=$this->pdo->prepare("SELECT * FROM users WHERE wallet = ?");
		$stmt->execute([$wallet]);
		if($stmt->rowCount()==0){
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}	
	
	
	
	function insertUser($registration){
		
		if($this->getUserByWallet($registration['wallet'])){
			return false;
		}
		
		$UUID = new UUID;
		$uuid = $UUID->v4();
		
		$query='INSERT INTO users (
			username,
			wallet,
			uuid,
			status
			)
			VALUES
			(?,?,?,?)';	
		
		$array=[
			$registration['username'],
			$registration['wallet'],
			$uuid,
			1
			];				
				
		$stmt=$this->pdo->prepare($query);
		$stmt->execute($array);		
		
		if($stmt->rowCount()==0){
			return false;
		}
		return $this->getUserById($this->pdo->lastInsertId('userid'));
	}	
	

}	