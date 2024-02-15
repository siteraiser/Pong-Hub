<?php 
class papi extends requestHandler{

	public function index(){
		if(isset($_SERVER['PHP_AUTH_PW'] ) && ( strlen($_SERVER['PHP_AUTH_PW']) == 36  || strlen($_SERVER['PHP_AUTH_PW']) == 0)){//  ()             /*( isset($_SERVER['PHP_AUTH_USER'] ) && ( $_SERVER['PHP_AUTH_USER'] == "secret" ) ) AND*/
			/* Authenticated */



			$this->loadModel('userModel');
			$this->loadModel('productModel');	
			
			$user = $this->userModel->checkUser($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
			if($user !==false){

				$this->productModel->setUser($user);
			}
			
			
		//$json = json_decode($_POST['method'],true);	
		$post = file_get_contents("php://input");

			
			
			
			$json = json_decode($post,true);			
			$respnse = (object)['success'=>false];
			if($json['method'] == 'newTX'){				
				$result = $this->productModel->newTX($json['params']['uuid']);
				if($result !== false){
					//live update products with sse... or something lol
					$respnse = (object)['success'=>true];
				}
			}else if($json['method'] == 'submitProduct'){				
				$result = $this->productModel->submitProduct($json['params']);

				if($result !== null){
					$respnse = (object)['success'=>true];
				}
			}else if($json['method'] == 'submitIAddress'){				
				$result = $this->productModel->submitIAddress($json['params']);
				if($result !== null){
					$respnse = (object)['success'=>true];
				}
			}else if($json['method'] == 'checkIn'){		
				$txt = "check in called";
				$myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
				$this->userModel->checkIn($_SERVER['PHP_AUTH_PW']);				
				$respnse = (object)['success'=>true];

			}else if($json['method'] == 'register'){				
				if($user === false){
					$result = $this->userModel->insertUser($json['params']);			
					if($result !== null){								
						$respnse = (object)['success'=>true,'reg'=>$result['uuid']];
					}
				}
			}
			
			header('Content-type: application/json');
			echo json_encode($respnse);	
			die();
			
			
			
		}else{
			/* Not Authenticated */
			$respnse = (object)['success'=>false,'error'=>'Failed Authentication'];
			header('Content-type: application/json');
			echo json_encode($respnse);	
			die();
		}
	}
}
