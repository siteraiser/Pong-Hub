<?php 
class order extends requestHandler{

	public function index(){
		$ia_id = $this->url_segments[1];
	
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
		
    #physical_goods_shipping {
        border: solid 2px black;
        padding: 4px;
    }


   #submission_type_selection > * {
    vertical-align:middle;
   }
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #42d12f;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: \"\";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: #fff;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}



		</style>";
		$data['description']="";
		$data['keywords']="";		

		$this->loadModel('productModel');	
	    $product_results = $this->productModel->getFullProduct($ia_id);	
		
		$data['title']="Order Product: ".htmlspecialchars($product_results['label']);
		
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
