<?php 
class order extends requestHandler{

	public function index(){
		$ia_id = $this->url_segments[1];
		$data['title']="Order Product";
		$data['meta']="
		<style>.hidden{height:0;overflow:hidden;}
		.warning{border: 2px solid red;}
		.success{border: 2px solid green;}
		</style>";
		$data['description']="";
		$data['keywords']="";		

		$this->loadModel('productModel');	
	    $product_results = $this->productModel->getFullProduct($ia_id);	
	
		$data['iaddress']=$product_results;
		
		$this->addView('header',$data);	
		$this->addView('order',$data);
		$this->addView('footer',$data);
	}
	
	
	public function checkid(){
		$post = file_get_contents("php://input");			
		$json = json_decode($post,true);	
		
		$this->loadModel('productModel');
		
		$result = $this->productModel->checkOrder($json['order_number']);	
		$response = ['success'=>false];
		if($result != false){
			$response = ['success'=>true,'sid'=>crc32($json['order_number'])];
		}
		header('Content-type: application/json');
		echo json_encode($response);	
		
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