<?php 
class sitemap_model extends requestHandler{
	public function getURLS($products){
		$links = array();
	
		foreach($products as $product){ 
			$use_ia_inventory = false;
			if($product['inventory'] == 0){
				$use_ia_inventory = true;
			}

			$ias_have_inv = false;
			$ia_out=[];
			foreach($product['iaddress'] as $ia){
				if($use_ia_inventory && ($ia['ia_inventory'] < 1)){			
					continue 1;
				}					
				if($ia['status'] == 0){					
					continue 1;
				}
				$ias_have_inv = true;

				$ia_out[] = 'order/'.$ia['iaddr_id'];
			}
			
			if(!$use_ia_inventory && $ias_have_inv || $use_ia_inventory && $ias_have_inv){
			
				foreach($ia_out as $link){
					$links[] = array('link'=>$link, 'priority' => '0.8');
				}
				
			}

		} 
		return $links;
	}
	/*
	public function getURLS($table){
		$links = array();
		
		$stmt=$this->pdo->prepare("SELECT slug FROM $table");
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


		foreach($rows as $row)
		{
			$links[] = array('link'=>$row['slug'], 'priority' => '0.8');
		}
			
		$stmt=$this->pdo->prepare("SELECT DISTINCT description FROM $table");
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


		foreach($rows as $row)
		{
			$links[] = array('link'=>'gallery/'.$this->slugify($row['description']), 'priority' => '0.8');
		}	
		return $links;
	}
	*/
}