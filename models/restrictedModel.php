<?php

class restrictedModel extends requestHandler{  
	function checkUIID($uuid){
		$stmt=$this->pdo->prepare("
		SELECT * FROM orders		
		WHERE uuid = ?
		");
		$stmt->execute(array($uuid));
		if($stmt->rowCount()==0){
			return false;
		}
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['ia_id'];
	}
}