<?php 
class restricted extends requestHandler{

	public function index(){
		$data['title']="Pong Store Restriced Area";
		$data['meta']="";
		$data['description']="";
		$data['keywords']="";		
		$data['access_granted'] = false;
		if(isset($this->url_segments[1])){
			$uuid = $this->url_segments[1];	
			$this->loadModel('restrictedModel');	
			$ia_id = $this->restrictedModel->checkUIID($uuid);
			if($ia_id !== false){
				$data['access_granted'] = true;		
				if($ia_id == 4){
					$data['access_level'] = 1;
				}else
				if($ia_id == 5){
					$data['access_level'] = 2;
				}
				
			}			
		}

		
		$this->addView('header',$data);	
		$this->addView('restricted',$data);
		$this->addView('footer',$data);
	}
}