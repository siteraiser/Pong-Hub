<h1>DERO Pong Store</h1>

<div class="products">
<?php 



function strip($html){	
	$html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $html);//remove scripts
	$html = preg_replace('~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $html);		
	$content1=strip_tags($html);	
	$order = array("\r\n", "\n", "\r","&nbsp;");
	$replace = ' ';

	// Processes \r\n's first so they aren't converted twice.
	$content1 = str_replace($order, $replace, $content1);
	return $content1;
}

$product_out = '';
$products_outs = '';
foreach($value as $product){ 
	$product_out = '';
	$use_ia_inventory = false;
	if($product['inventory'] == 0){
		$use_ia_inventory = true;
	}
	$product_out .='<div class="product clearfix">';
	
	if($product['image'] != ''){
		$product_out .='<div class="product_image_wrapper"><img class="product_image" src="/'.$product['image'].'"></div>';
	}
	
	$product_out .='<h2>'. strip($product['label']).'</h2>';	
	
	$product_out .='<div class="product_info">';
	
	$product_out .='<h3>Seller: '. strip($product['username']).'</h3>';
	
	if(!$use_ia_inventory){
		$product_out .='Inventory: '.$product['inventory'];
	}	
	$product_out .='</div>';
	

	$product_out .='<br style="clear:both">';
	
	
	
	$ias_have_inv = false;
	$ia_out='';
	foreach($product['iaddress'] as $ia){
		if($use_ia_inventory && ($ia['ia_inventory'] < 1)){			
			continue 1;
		}	
		
		if($ia['status'] == 0){
			
			continue 1;
		}
		$ias_have_inv = true;
		
		
		$ia_out .='<div class="iaddress">';
		$ia_out .='<h3>'.strip($ia['comment']).'</h3>';		
		if($use_ia_inventory){
			$ia_out .='<div>Inventory:'.$ia['ia_inventory'].'</div>';
		}
		$ia_out .='<div>Price:'. ($ia['ask_amount'] * .00001) .' DERO</div>';
		$ia_out .='<div><a href="/order/'.$ia['id'].'">Click Here to Order</a></div>';

		$ia_out .='</div>';
	}
	if(!$use_ia_inventory && $ias_have_inv || $use_ia_inventory && $ias_have_inv){
		$products_outs .= $product_out.$ia_out.'</div>';
	}

} 

echo $products_outs;
/*

echo'<pre>';
var_dump($value);
echo'</pre>';
*/
?>
</div>

