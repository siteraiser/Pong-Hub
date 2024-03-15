<?php

class productModel extends requestHandler{  

	public $user;
	public $img_loc = 'images';
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
		SELECT *,i_addresses.id ,i_addresses.status AS ia_status FROM i_addresses 
		RIGHT JOIN products ON i_addresses.product_id = products.pid
		RIGHT JOIN users ON i_addresses.user = users.userid
		WHERE i_addresses.id = ? AND i_addresses.user = products.user
		");
		$stmt->execute(array($ia_id));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function getIntegratedAddressesByPid($user,$pid){
		$stmt=$this->pdo->prepare("
		SELECT *,i_addresses.status AS ia_status FROM i_addresses 
		WHERE i_addresses.product_id = ? AND i_addresses.user = ?
		");
		$stmt->execute(array($pid,$user));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	
	
		if(isset($product['action'])){
			if($product['action'] == 'delete'){
				return $this->deleteProduct($product);
			}
		}
	
		if(empty($this->getProductById($product['id']))){
			return $this->insertProduct($product);			
		}
		return $this->updateProduct($product);	
	}

	function insertProduct($product){
		if($this->user['userid']==''){
            return false;
        }

		$imageuri ='';
		if(isset($product['image'])){	
			//Always null for now but here if it is needed...		
			if($product['image']!=''){
				$imageuri = substr($product['label'],0, 100);				
				$imageuri = $this->imgSlug($imageuri);
				$random = substr(md5(mt_rand()), 0, 10);
				$imageuri = $this->img_loc . '/' . $random . '_' . $imageuri . '.png';
				
				$image = imagecreatefrompng($product['image']);
				imagepng($image, $this->doc_root.$imageuri);
			}
		}
		
		
		
		$query='INSERT INTO products (
			pid,
			user,
			p_type,
			label,
			details,
			scid,
			inventory,
			image
			)
			VALUES
			(?,?,?,?,?,?,?,?)';	
		
		$array=array(
			$product['id'],
			$this->user['userid'],
			$product['p_type'],
			$product['label'],
			$product['details'],
			$product['scid'],
			$product['inventory'],
			$imageuri
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
			':p_type'=>$product['p_type'],
			':label'=>$product['label'],
			':details'=>$product['details'],
			':scid'=>$product['scid'],
			':inventory'=>$product['inventory'],
			':pid'=>$product['id'],
			':user'=>$this->user['userid']		
		);
		

		
		$insert ='';
		$imageuri ='';
		if(isset($product['image'])){	
			//get and delete old image
			$this->deleteImage($product['id']);			

			if($product['image']!=''){
				$imageuri = substr($product['label'],0, 100);				
				$imageuri = $this->imgSlug($imageuri);
				$random = substr(md5(mt_rand()), 0, 10);
				$imageuri = $this->img_loc . '/' . $random . '_' . $imageuri . '.png';
				
				$image = imagecreatefrompng($product['image']);
				imagepng($image, $this->doc_root.$imageuri);
			}
			$insert =',image=:image';
			$p_array = array_merge($p_array, array(':image'=>$imageuri));
		}

		$query="UPDATE products SET 
			p_type=:p_type,
			label=:label,
			details=:details,
			scid=:scid,
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
	
	
	function deleteProduct($product){
		
		//delete image first...
		$this->deleteImage($product['id']);
		
		$stmt=$this->pdo->prepare("	
			DELETE FROM i_addresses 
			WHERE product_id = ? AND user = ? ;
			
			DELETE FROM products WHERE pid = ? AND user = ? 
		");
		
		$stmt->execute([$product['id'],$this->user['userid'],$product['id'],$this->user['userid']]);		
		if($stmt->rowCount()==0){
			return false;
		}
		return true;
		
	}
	
	
	
	
	
	//delete old image if exists
	function deleteImage($pid){
		$stmt=$this->pdo->prepare("SELECT image FROM products WHERE pid=? AND user = ?");
		$stmt->execute(array($pid,$this->user['userid']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($stmt->rowCount()==0){
			return false;
		}	
		if($row['image'] == ''){
			return false;
		}
		unlink($this->doc_root.$row['image']);
		return true;
	}


	function getIAddressById($i_addr_id){
		$stmt=$this->pdo->prepare("SELECT * FROM i_addresses WHERE iaddr_id=? AND user = ?");
		$stmt->execute(array($i_addr_id,$this->user['userid']));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}



	function submitIAddress($i_address){

		if(isset($i_address['action'])){
			if($i_address['action'] == 'delete'){
				return $this->deleteIAddress($i_address);
			}
		}
		
		
		
		
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
			ia_scid,
			status,
			ia_inventory
			)
			VALUES
			(?,?,?,?,?,?,?,?,?)';	
		
		$array=array(
			$i_address['id'],
			$this->user['userid'],
			$i_address['product_id'],
			$i_address['iaddr'],
			$i_address['ask_amount'],
			$i_address['comment'],
			$i_address['ia_scid'],
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
			ia_scid=:ia_scid,
			status=:status,
			ia_inventory=:ia_inventory
			WHERE iaddr=:iaddr';
		
	
			
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(
			':ia_scid'=>$i_address['ia_scid'],
			':status'=>$i_address['status'],
			':ia_inventory'=>$i_address['ia_inventory'],			
			':iaddr'=>$i_address['iaddr']));				
	
		if($stmt->rowCount()==0){
			return false;
		}
		return $i_address['id'];
	}	
	
	function deleteIAddress($i_address){
		
		$stmt=$this->pdo->prepare("	
			DELETE FROM i_addresses 
			WHERE iaddr_id = ? AND user = ?
		");
		
		$stmt->execute([$i_address['id'],$this->user['userid']]);		
		if($stmt->rowCount()==0){
			return false;
		}
		return true;
		
	}
	
	function newTX($params){
		$ia_id = '';
		if(isset($params['ia_id'])){
			$ia_id = $params['ia_id'];
		}
		
		$query='INSERT INTO orders (
			uuid,
			ia_id,
			userid
			)
			VALUES
			(?,?,?)';	
		
		$array=array(
			$params['uuid'],
			$ia_id,
			$this->user['userid']
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
