<?php 
class order extends requestHandler{

	public function index(){
		$ia_id = $this->url_segments[1];
		$data['title']="Order Product";
		$data['meta']="
		<style>
		html,body{pdding:0;margin:0;border:0;}
		*{box-sizing:border-box;}
		.hidden{height:0;overflow:hidden;visibility:hidden;}
		.warning{border: 2px solid red;}
		.success{border: 2px solid green;}
		.product-wrapper{display:block;}
		.content-block{padding:10px;}
		@media only screen and (min-width: 600px) {
			.product-wrapper{display:flex;}
			.content-block{
				width:50%;			
			}
		}
		.product-wrapper.no-image .image-block{display:none;}
		.product-wrapper.no-image .content-block{width:100%;}
		.options{
			display: flex;
			flex-direction: column;
			align-items: flex-start;
		}
		.ia_comment  { 
		display:inline-block;padding:3px;
		}
		.selected  { 
		border:solid 2px green;
		font-weight:bold;
		}
		.greyed_out{color:grey;}
		.checkmark  {
	    color:green;
		}
		
		#address input,#uuid{margin:5px;}
		#copy_section{padding:5px;}
		#senddata{padding:5px;}
		</style>";
		$data['description']="";
		$data['keywords']="";		

		$this->loadModel('productModel');	
	    $product_results = $this->productModel->getFullProduct($ia_id);	
	
		$data['iaddress']=$product_results;
		$data['iaddresses']=$this->productModel->getIntegratedAddressesByPid($product_results['user'],$product_results['product_id']);
	/*	echo'<pre>';
		var_dump($data['iaddress']);
		echo'</pre>';
		echo'<pre>';
		var_dump($data['iaddresses']);
		echo'</pre>';
*/
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
		die();
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
