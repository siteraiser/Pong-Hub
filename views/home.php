<h1>DERO Pong Store</h1>

<div class="products">
<?php 


	function strip($html){	
		$html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $html);//remove scripts
		$html = preg_replace('~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $html);		
		$content1=strip_tags($html);	
		$order = array("\r\n", "\n", "\r","&nbsp;","&#60;","&#x3C;");
		$replace = ' ';

		// Processes \r\n's first so they aren't converted twice.
		$content1 = str_replace($order, $replace, $content1);
		return $content1;
	}



$products_out = '';
foreach($product_results as $product){ 
	$product_out = '';	
	if($product['image'] != ''){
		$product_out .='<div class="product_image_wrapper"><img class="product_image" src="/'.$product['image'].'"></div>';	
	}
	$product_out .='<h2>'. substr(strip($product['label']), 0, 50).'</h2>';		
	$product_out .='<div class="product_info">';	
	$product_out .='<h3 style="overflow-wrap: break-word;">Seller: '. strip($product['username']).'</h3>';
	
	if($product['inventory'] != false){
		$product_out .='Available: '.$product['inventory'];
	}
	$details = strip($product['details']);
	$product_out .='<div class="details">'.substr($details, 0, 100).(strlen($details)>100?"...":"").'</div>';
	$product_out .='</div>';
	$product_out .='<br style="clear:both">';
	
	
	$ia_out = '';
	foreach($product['iaddress'] as $ia){
	
		$ia_out .='<div class="iaddress">';
		
		$ia_out .='<h3>'.strip($ia['comment']).'</h3>';		
		if($ia['ia_inventory'] != false){
			$ia_out .='<div>Available:'.$ia['ia_inventory'].'</div>';
		}
		$ia_out .='<div>Price:'. rtrim( sprintf('%.5F',($ia['ask_amount'] * .00001)),"0") .' DERO</div>';
		$ia_out .='<div><a href="/order/'.$ia['id'].'">Click Here to Order</a></div>';

		$ia_out .='</div>';
	}

	$products_out .= '<div class="product clearfix">'.$product_out.'<div class="iaddresses">'.$ia_out.'</div></div>';

} 

echo $products_out;


/*

echo'<pre>';
var_dump($value);
echo'</pre>';

echo'<pre>';
var_dump($value);
echo'</pre>';
*/
?>
</div>

