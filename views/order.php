<?php
	function strip($html){	
	    //&#60; &#x3C; are lte
		$html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $html);//remove scripts
		$html = preg_replace('~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $html);		
		$content1=strip_tags($html);	
		$order = array("&#60;","&#x3C;");
		$replace = ' ';

		// Processes \r\n's first so they aren't converted twice.
		$content1 = str_replace($order, $replace, $content1);
		return $content1;
	}



echo '<h1>'.strip($iaddress['label']).'</h1>';


?>
<div class="product-wrapper<?php echo($iaddress['image'] != ''?'':' no-image'); ?>">
<div class="content-block image-block">

<?php
if($iaddress['image'] != ''){
echo '<img style="max-width: 100%;" src="'.$this->base_url.$iaddress['image'].'">';
}

?>

</div><!-- end of div-content-block -->


<div class="content-block">

<?php
$stock ='';
$product_inventory=false;
if($iaddress['ia_status']!=0){
	if($iaddress['inventory']>0){
		$product_inventory=true;
		$stock = 'Available:'. $iaddress['inventory'];
		
	}else if($iaddress['ia_inventory']>0){
		$stock = 'Available:'. $iaddress['ia_inventory'];
		
	}else{
		$stock = 'Out of Stock';
	}
}else{
	$stock = 'Item Currently Unavailable.';
}




?>
<div class="options">
Option Selected:
<?php
foreach($iaddresses as $iadd){
	$class = 'greyed_out';
	if($product_inventory){
		$class ='';
	}
	if($iadd['ia_inventory']>0){
		$class ='';
	}
	if($iadd['ia_status']==0){
		$class ='greyed_out';
	}
	if($iaddress['id'] == $iadd['id']){		
		echo '<div class="ia_comment selected '.$class.'"><span class="checkmark '.$class.'">&#10003;</span>'.strip($iadd['comment']).'</div>';
	}else{	
		if($iadd['ia_status']!=0){	
			echo '<div class="ia_comment '.$class.'"><a href="'.$this->base_url.'order/'.$iadd['id'].'">'.strip($iadd['comment']).'</a></div>';
		}
	}
	
}

?>
</div>

<?php
echo '<h3>Price: '.($iaddress['ask_amount'] * .00001).' DERO</h3>';

if($iaddress['username'] == ''){
	$iaddress['username'] = $iaddress['wallet'];
}	
echo '<h4 style="overflow-wrap: break-word;">Sold By: <span>'. $iaddress['username'].'</span></h4>';
?>
<p>
<?php
echo $stock;


//See if is a smart contract.
$scid = $iaddress['scid'];
if($iaddress['ia_scid'] != ''){
	
$scid =	$iaddress['ia_scid'];
}
echo '<div class="details">Details: <p>'. nl2br(strip($iaddress['details'])).'</p></div>';
if($scid != ''){
	echo '<p style="overflow-wrap: break-word;">SCID: '.$scid.'</p>';
}


$hidden = '';
if($iaddress['p_type'] == 'physical' || $stock == 'Out of Stock' || $stock == 'Item Currently Unavailable.'){
	$hidden = 'hidden';
}


?>
</p>

  <div id="payment_instruction_1"  class="<?php /*echo $hidden;*/ ?>">

        <p>
            Copy and paste the integrated address as the send to address to purchase the item described.
        </p>
         <div id="iaddress" style="overflow-wrap: break-word;"><?php echo $iaddress['iaddr'];?></div><br>
         <button id="copy_iaddress">Click to Copy Integrated Address</button>
            
     </div>
<?php 


if($iaddress['p_type'] == 'physical'){
?>
<div id="physical_goods_shipping">


    <p>
        Before completing your order, choose to submit your shipping address during or after your order.    
    </p>
        <div id="submission_type_selection">
        <span>During</span>
        <!-- Rounded switch -->
        <label class="switch">
            <input id="submission_type_checkbox" type="checkbox">
            <span class="slider round"></span>
        </label>
         <span>After</span>
        </div>
        <p id="during_after_message">
     Please enable JavaScript to contiue or choose another modern browser.
    </p>
<div id="payment_instruction_2" class="">

    <p>
      Reciever Username or Address:  <span id="ship_to_wallet" style="overflow-wrap: break-word;"><?php echo $iaddress['username'];?></span> 
      <button id="copy_ship_to_wallet">Click to Copy</button>
    </p>
    <p>
        Minimum Amount: .00001 DERO
    </p>
    
</div>
    

    <p id="address_success_message" class="hidden">
    Your shipping address is will fit into the required address submission message.<br>
   </p> 
   <div id="copy_section" class="" disabled >
        After completing the addres form, copy and send the generated message string below as the message to submit your shipping address to the seller.<br>
        <div id="senddata"></div><br>
        <button id="copy_data" disabled >Click to Copy Address Submission Message</button>
    </div>
    <!-- Address Message Generator autocomplete="false"-->
    <div id="after_uuid">
        Order Number: <input id="uuid" type="text" placeholder="Enter Order ID Here (7b5b48fc-180b-4e7d-bc35-70fd40c7c89c)" style="width:100%;max-width:400px;">       
    </div>
    
        <form class="form" autocomplete="on">
            <input type="hidden" id="id" >
            
        
            <div id="address" >	
                <label for="n">
                Full Name:</label>
                <input title="Full Name" id="n" type="text" placeholder="Full Name" required autocomplete="name"><br>
    
                <label for="l1">
                Street Address:</label>
                <input title="Street Address 1" id="l1" type="text" placeholder="Street Address" required autocomplete="shipping street-address address-line1"><br>
    
                <label for="l2">
                Apt. / suite #:</label>
                <input title="Street Address 2" id="l2" type="text" autocomplete="shipping street-address address-line2"><br>
    
                <label for="c1">
                City:</label>
                <input title="City" id="c1" type="text" required autocomplete="shipping locality"><br>
    
                <label for="s">
                State/Province:</label>
                <input title="State/Province" id="s" type="text" required autocomplete="shipping region"><br>
    
                <label for="z">
                Zip:</label>
                <input title="Zip/Postal" id="z" type="text" required autocomplete="shipping postal-code"><br>
    
                <label for="c2">
                Country:</label>
                <input title="Country" id="c2" type="text" required autocomplete="shipping country"><br>
            </div>
        </form>
    
   </div>     

<?php 
}
?>

</div><!-- end of div-content-block -->
</div><!-- end of div-product-wrapper -->

<?php 


if($iaddress['p_type'] == 'physical'){
?>
<br>
<div style="background: #0d5328; padding:20px">
    <h3 style="color: #fff;">How to submit your shipping address after receiving your order id. </h3>
  <div>
    <video  controls="" controlslist="nodownload" height="1080" poster="https://www.siteraiser.com/dero-pong-store/videos/video.PNG" width="1920" style="max-width:100%; height:auto;" id="video"><source src="https://www.siteraiser.com/dero-pong-store/videos/video.webm" type="video/webm"><source src="https://www.siteraiser.com/dero-pong-store/videos/video.mp4" type="video/mp4"></video>
  </div>
</div>

<?php 
}
?>

<script>
    var payment_instruction_1 = document.getElementById("payment_instruction_1");

    
    var iaddress = document.getElementById("iaddress");
    var copy_iaddress_button = document.getElementById('copy_iaddress');
    copy_iaddress_button.addEventListener('click',() => { copy('iaddress'); }, false);
    
    var selection = window.getSelection();
    function copy(el) {
    
        const doc = document;
        const text = doc.getElementById( el);
        selection = window.getSelection();
    
        range = doc.createRange();
        range.selectNodeContents( text );
    
        selection.removeAllRanges();
        selection.addRange( range );
    
        range.setStart(text, 0);
        document.execCommand('copy')
        //window.getSelection().removeAllRanges();
    }

  
  

    var during_message = "To submit your address with your order include the message generated by the address form below in the transaction list for the order.";
    var after_message =  "To submit your address after ordering, you have to wait for a resonse from the seller with a uuid and enter that in the uuid field to generate your shipping address submission message.  After completing your purchase, check for the Order ID (uuid) in your recent transactions list (view history -> normal). When you have recieved the response from the seller with your order ID enter it below to begin shipping address submission (if required). Make sure to save enough Dero to cover the message fees!";
    var during_after_message = document.getElementById("during_after_message");
   
    //Preset the value
    var during_selected = true;
    during_after_message.innerHTML = during_message;
    after_uuid.classList.add('hidden');
    //Add event listener to the switch
    var submission_type_checkbox = document.getElementById("submission_type_checkbox");
    submission_type_checkbox.addEventListener('change',() => { 
    if (submission_type_checkbox.checked){
        during_selected = false
        during_after_message.innerHTML = after_message;
        after_uuid.classList.remove('hidden');
        checkOrderNumber(event)
    }else{
        during_selected = true
        during_after_message.innerHTML = during_message;
        after_uuid.classList.add('hidden');
    }
    validate(event);
     }, false);



    //copy wallet address for shipping submission message
    var ship_to_wallet = document.getElementById("ship_to_wallet");
    var copy_ship_to_wallet_button = document.getElementById('copy_ship_to_wallet');
    copy_ship_to_wallet_button.addEventListener('click',() => { copy('ship_to_wallet'); }, false);



    var crc32=function(r){for(var a,o=[],c=0;c<256;c++){a=c;for(var f=0;f<8;f++)a=1&a?3988292384^a>>>1:a>>>1;o[c]=a}for(var n=-1,t=0;t<r.length;t++)n=n>>>8^o[255&(n^r.charCodeAt(t))];return(-1^n)>>>0};
    //after uuid section
    var uuid = document.getElementById('after_uuid');
     //input for uuid
    var uuid = document.getElementById('uuid');
    //hidden input for crc32
    var id = document.getElementById('id');
    uuid.addEventListener('input', checkOrderNumber, false);
    
    
    
    function checkOrderNumber(event) {
        
        var order_number = uuid.value
        var valid = false;
    
        if(order_number !='' && order_number.length == 36){
            valid = true;
            id.value = crc32(order_number);
            copy_section.classList.add("success");        
        }
        validate(event);
        
        if(!valid){
            
            senddata.innerHTML = '<span style="color:red;">Invalid order number, (copy payload) from the seller response containing your order id (UUID).</span>';
            copy_section.classList.remove("success");
            copy_data_button.disabled = true;
        }
        
    }
    
    
    /* Address Handling */
    var address_div = document.getElementById('address');
    
    var inputs = document.querySelectorAll('#address input');
    var senddata = document.getElementById('senddata');
    var copy_section = document.getElementById('copy_section');
    var copy_data_button = document.getElementById('copy_data');
    var address_success_message =  document.getElementById('address_success_message');
    
    var warnings = [];
    var address_is_ready = false;
    
    copy_data_button.addEventListener('click', () => { copy('senddata');}, false);
    
    inputs.forEach((input) => {
    input.addEventListener('input', validate, false);
    input.addEventListener('keyup', validate, false);
    input.addEventListener('blur', validate, false);		
    });	
    
    
    function fits(query){
        if(id.value == '' && !during_selected){//and is a post submission...
            query = query+'0000000000';
        }
        let size =  new Blob([query]).size;
        //console.log(size);
        if(size > 128){
            return false;
        }else{
            return true;
        }
    }
    function valid(element){
        if(element.value != '' || element.id =='uuid' || element.id =='l2'|| element.id =='submission_type_checkbox'){
            return true;
        }
        return false;
    }
    function validate(event){
        let index = warnings.indexOf(event.target.id);	
        if(valid(event.target) ){			
            if (index !== -1) {
              warnings.splice(index, 1);
            }		
        }else {	
            if (index === -1) {	
                warnings.push(event.target.id);		
            }
        }
        inputs.forEach((input) => {
            let index = warnings.indexOf(input.id);	
            if(index !== -1) {
                input.classList.add("warning");
            }else{
                input.classList.remove("warning");
            }	
        });	
        
   // console.log(warnings)
        
        if(warnings.length == 0){
            var params = {};
            var all_filled = true;
            
            //after submission is selected
           if (!during_selected){
               params['id'] = id.value;
          }


            inputs.forEach((input) => {
                if(!valid(input)){
                    all_filled = false;
                }
                 params[input.id] = input.value;
                
            });	
             
            var query = Object.keys(params)
                .map(k => k + '$' + params[k])
                .join('?');
                
            if(fits(query) && all_filled){
                copy_data_button.disabled = false;
                address_is_ready = true;
                senddata.innerHTML = query;	
            }else if(!fits(query)){
                copy_data_button.disabled = true;
                address_is_ready = false;
                senddata.innerHTML = '<span style="color:red;">Too Long! </span>';
            }else if(!all_filled){
                copy_data_button.disabled = true;
                address_is_ready = false;
                senddata.innerHTML = '<span style="color:red;">Finish Filling Out the Address Form </span>';
            
            }
        }else{
            address_is_ready = false;
            copy_data_button.disabled = true;
            senddata.innerHTML = '<span style="color:red;">Finish Filling Out the Address Form </span>';
            
        }
        
        if(address_is_ready){
            address_success_message.classList.remove("hidden"); 
            copy_section.classList.add("success");
        }else{
            address_success_message.classList.add("hidden"); 
            copy_section.classList.remove("success");

        }
    }
    
    
    
    </script>
