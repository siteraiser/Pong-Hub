<?php 
class sitemap extends requestHandler{

	public function index(){

		$this->loadModel('sitemap_model');
		$this->loadModel('productModel');	
		$product_results = $this->productModel->getProductsList();

		foreach ($product_results as &$product){
			$product['iaddress'] = $this->productModel->getIAddresses($product);		
		}
		
		
        $data['urlslist'] = $this->sitemap_model->getURLS($product_results);
		
		header("Content-Type: text/xml;charset=iso-8859-1");
		
		$this->addView('sitemap',$data);
	}

}