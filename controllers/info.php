<?php 
class info extends requestHandler{

	public function index(){
		$data['title']="Pong Store Information";
		$data['meta']="";
		$data['description']="";
		$data['keywords']="";		

		
		$this->addView('header',$data);	
		$this->addView('info',$data);
		$this->addView('footer',$data);
	}
}	