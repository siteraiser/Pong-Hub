<?php 
class home extends requestHandler{

	public function index(){
		$data['title']="Pong Hub: Home";
		$data['meta']="";
		$data['description']="";
		$data['keywords']="";		

		$this->loadModel('productModel');	
		$product_results = $this->productModel->getProductsList();
		foreach ($product_results as &$product){
			$product['iaddress'] = $this->productModel->getIAddresses($product);		
		}
	
	
	
	
		$data['value']=$product_results;
		
		$this->addView('header',$data);	
		$this->addView('home',$data);
		$this->addView('footer',$data);
	}
	
	public function sse(){
	/*	var source = new EventSource("events.php");
		source.onmessage = function(event) {
			var jdata = JSON.parse(event.data);
			console.log(jdata);
		};
	*/	
		
		
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$data = array(
			'firstName'=>'Test',
			'lastName'=>'Last'
		);
		$str = json_encode($data);
		echo "data: {$str}\n\n";
		flush();
	}
	
}