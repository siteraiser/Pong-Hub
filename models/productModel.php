<?php

class productModel extends requestHandler{  

	public $user;
	function setUser($user){
		$this->user = $user;
	}
	//display
	function getProductsList(){
		$given = new DateTime();
		$given->setTimezone(new DateTimeZone("UTC"));
		$given->modify('-30 minutes');
		$thrityminsago = $given->format("Y-m-d H:i:s");
		
		$stmt=$this->pdo->prepare("SELECT * FROM products 
		INNER JOIN users ON products.user = users.userid WHERE users.checkin > ? ORDER BY products.id DESC");//ORDER BY users.checkin DESC
		$stmt->execute(array($thrityminsago));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
	function getIAddresses($product){
		$stmt=$this->pdo->prepare("
		SELECT * FROM i_addresses
		
		WHERE i_addresses.product_id = ? AND i_addresses.user = ?
		");
		$stmt->execute(array($product['pid'],$product['user']));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
	
	
	//orders
	
	function getFullProduct($ia_id){
		$stmt=$this->pdo->prepare("
		SELECT * FROM i_addresses 
		INNER JOIN products ON i_addresses.product_id = products.pid
		INNER JOIN users ON i_addresses.user = users.userid
		WHERE i_addresses.id = ? AND i_addresses.user = products.user
		");
		$stmt->execute(array($ia_id));
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		return $rows;
	}
	
	function checkOrder($uuid){
		$stmt=$this->pdo->prepare("
		SELECT * FROM orders 
		WHERE uuid = ?		
		");
		$stmt->execute(array($uuid));
		
		if($stmt->rowCount()==0){
			return false;
		}

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}	
	
	
	//insert / update
	function getProductById($pid){
		$stmt=$this->pdo->prepare("SELECT * FROM products WHERE pid=? AND user = ?");
		$stmt->execute(array($pid,$this->user['userid']));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}



	function submitProduct($product){
	
		if(empty($this->getProductById($product['id']))){
			return $this->insertProduct($product);			
		}
		return $this->updateProduct($product);	
	}

	function insertProduct($product){
		
		
		$query='INSERT INTO products (
			pid,
			user,
			label,
			inventory,
			image
			)
			VALUES
			(?,?,?,?,?)';	
		
		$array=array(
			$product['id'],
			$this->user['userid'],
			$product['label'],
			$product['inventory'],
			$product['image']
			);				
				
		$stmt=$this->pdo->prepare($query);
		$stmt->execute($array);		
		if($stmt->rowCount()==0){
			return false;
		}
		return $this->pdo->lastInsertId('id');
	}
	function updateProduct($product){
		/*
		$iaddr = $this->getIAddresses(['pid'=>$product['id'], 'user'=>$this->user['userid']])[0];
		if(!$this->sameAddress($iaddr,$this->user['wallet'])){
			return false;
		}
		*/
		
		$p_array = array(
			':label'=>$product['label'],
			':inventory'=>$product['inventory'],
			':pid'=>$product['id'],
			':user'=>$this->user['userid']		
		);
		
		$insert ='';
		if(isset($product['image'])){
			$insert =',image=:image';
			$p_array = array_merge($p_array, array(':image'=>$product['image']));
		}

		$query="UPDATE products SET 
			label=:label,
			inventory=:inventory
			$insert
			WHERE pid=:pid AND user=:user";
		
		$stmt=$this->pdo->prepare($query);
		$stmt->execute($p_array);				
	
		if($stmt->rowCount()==0){
			return false;
		}
		return $product['id'];
	}	

	function getIAddressById($i_addr_id){
		$stmt=$this->pdo->prepare("SELECT * FROM i_addresses WHERE iaddr_id=?");
		$stmt->execute(array($i_addr_id));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}



	function submitIAddress($i_address){
	
		if(empty($this->getIAddressById($i_address['id']))){
			return $this->insertIAddress($i_address);			
		}
		return $this->updateIAddress($i_address);	
	}

	function insertIAddress($i_address){
		if(!$this->sameAddress($i_address['iaddr'],$this->user['wallet'])){
			return false;
		}
		
		$query='INSERT INTO i_addresses (
			iaddr_id,
			user,
			product_id,
			iaddr,
			ask_amount,
			comment,
			status,
			ia_inventory
			)
			VALUES
			(?,?,?,?,?,?,?,?)';	
		
		$array=array(
			$i_address['id'],
			$this->user['userid'],
			$i_address['product_id'],
			$i_address['iaddr'],
			$i_address['ask_amount'],
			$i_address['comment'],
			$i_address['status'],
			$i_address['ia_inventory']
			);				
				
		$stmt=$this->pdo->prepare($query);
		$stmt->execute($array);		
		if($stmt->rowCount()==0){
			return false;
		}
		return $this->pdo->lastInsertId('id');
	}
	
	
	
	
	
	function updateIAddress($i_address){
		
		if(!$this->sameAddress($i_address['iaddr'],$this->user['wallet'])){
			return false;
		}
		
		$query='UPDATE i_addresses SET 
			status=:status,
			ia_inventory=:ia_inventory
			WHERE iaddr=:iaddr';
		
	
			
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(
			':status'=>$i_address['status'],
			':ia_inventory'=>$i_address['ia_inventory'],			
			':iaddr'=>$i_address['iaddr']));				
	
		if($stmt->rowCount()==0){
			return false;
		}
		return $i_address['id'];
	}	
	
	function newTX($uuid){
		
		$query='INSERT INTO orders (
			uuid
			)
			VALUES
			(?)';	
		
		$array=array(
			$uuid
			);				
				
		$stmt=$this->pdo->prepare($query);
		$stmt->execute($array);		
		if($stmt->rowCount()==0){
			return false;
		}
		return $this->pdo->lastInsertId('id');
	}
	
	function sameAddress($iaddr,$waddr){
		$ia_frag = ltrim($iaddr,"deroi1");
		$wa_frag = ltrim($waddr,"dero1");
		$ia_frag = substr($ia_frag, 0, 53);
		$wa_frag = substr($wa_frag, 0, 53);
		
		if($ia_frag != $wa_frag){
			return false;
		}
		return true;		
	}
}
