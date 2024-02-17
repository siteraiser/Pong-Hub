<?php 
class home extends requestHandler{

	public function index(){
		$data['title']="Pong Hub: Home";
		$data['meta']="<style>
		.clearfix::after {
		  content: '';
		  clear: both;
		  display: table;
		}
		.products{	    
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
		}
		.product{
			width: 100%;
			box-sizing: border-box;
			border:1px solid green;
			background: #f7f7f7;
			border-radius:4px;
			padding:10px;
			margin:1%;
			font-family: sans-serif;
		}
		
		@media only screen and (min-width: 600px) {
		  .product{
			width: 48%;
			padding:10px;
		}
		}
		@media only screen and (min-width: 900px) {
		  .product{
			width: 31%;
			padding:10px;
		}
		}	
		
		.product h2,.product h3{
			margin:2px;
		}
		
		
		.product_image_wrapper{
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100px;
			height: 100px;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
		}
		.product_image{
			max-width: 100%;
			max-height: 100%;
		}
		.iaddress{    
			border: 1px solid #888;
			padding: 10px;
			display: inline-flex;
			max-width: 200px;
			flex-direction: column;
		}
		
		</style>";
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
