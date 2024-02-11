<h1>DERO Pong Store</h1>

<div>
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
$products_out = '';
foreach($value as $product){ 
	$product_out = '';
	$use_ia_inventory = false;
	if($product['inventory'] == 0){
		$use_ia_inventory = true;
	}
	$product_out .='<div style="border:1px solid green;margin:5px;padding:5px;">';
	$product_out .='<img style="float:left;margin-right:10px;" src="'.$product['image'].'">';
	$product_out .='<h2>'. strip($product['label']).'</h2>';
	$product_out .='<h3>Seller: '. strip($product['username']).'</h3>';
	if(!$use_ia_inventory){
		$product_out .='Inventory: '.$product['inventory'];
	}	
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
		$ia_out .='<div>Price:'. ($ia['ask_amount'] * .00001) .' DERO</div>';
		$ia_out .='<div><a href="/order/'.$ia['id'].'">Click Here to Order</a></div>';
		if($use_ia_inventory){
			$ia_out .='<div>Inventory:'.$ia['ia_inventory'].'</div>';
		}
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

