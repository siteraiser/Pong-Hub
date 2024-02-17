<?php

echo '<h1>'.$iaddress['label'].'</h1>';

echo '<h2>'.$iaddress['comment'].'</h2>';
if($iaddress['image'] != ''){
echo '<img style="max-width: 100%;" src="/'.$iaddress['image'].'">';
}
echo '<h3>Price: '.($iaddress['ask_amount'] * .00001).' DERO</h3>';
echo '<h4>Sold By:'. $iaddress['username'].'</h4>';
?>
<p>
<?php
if($iaddress['ia_status']!=0){
	if($iaddress['inventory']>0){
		echo 'Inventory:'. $iaddress['inventory'];
	}else if($iaddress['ia_inventory']>0){
		echo 'Inventory:'. $iaddress['ia_inventory'];
	}else{
		echo 'Out of Stock';
	}
}else{
	echo 'Item Currently Unavailable.';
}
?>
</p>
<div id="payment">

<p>
Copy and paste the integrated address into the send address to purchase the item described.
</p>
<div id="iaddress" style="overflow-wrap: break-word;"><?php echo $iaddress['iaddr'];?></div><br>
<button id="copy_iaddress">Click to Copy Integrated Address</button>

<p>
After completing your purchase, check for the Order ID (uuid) in your recent transactions list (view history -> normal). 
</p>
<p>
When you have recieved the response from the seller with your order ID Enter it below to begin shipping address submission (if required). 
</p>

</div>



<p id="address_instruction_1" class="hidden">
Enter your shipping address details and then copy and send the generated message to seller wallet address.
</p>



<!-- Address Message Generator -->

Order Number: <input id="uuid" type="text" placeholder="Enter Order ID Here (7b5b48fc-180b-4e7d-bc35-70fd40c7c89c)" id="order_uuid" style="width:100%;max-width:400px;">

	<form class="form" autocomplete="on">
		<input type="hidden" id="id">
		
	
		<div id="address" class="hidden">	
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

	<div id="copy_section" class="hidden" disabled >
		Send the string below as the message to submit your address to the seller (important! Send amount must be greater than 0 Dero! .00001 minimum).<br>
		<div id="senddata"></div><br>
		<button id="copy_data" disabled >Click to Copy Message</button>
	</div>







<script>
var payment = document.getElementById("payment");
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
/*
function copyIAddress() {
	
	iaddress.select();
	iaddress.setSelectionRange(0, 99999); 
	navigator.clipboard.writeText(iaddress.value);
}
*/

var uuid = document.getElementById('uuid');
var id = document.getElementById('id');
uuid.addEventListener('input', checkOrderNumber, false);

function checkOrderNumber() {
	async function checkOrder(data) {
	  try {
		const response = await fetch("/order/checkid", {
		  method: "POST", // or 'PUT'
		  headers: {
        'credentials': 'same-origin',
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json;charset=utf-8'
        },
		  body: JSON.stringify(data),
		});

		const result = await response.json();			

		if(result.success == true){	
			id.value = result.sid;
			payment.classList.add('hidden');
			uuid.disabled = true;
			address_instruction_1.classList.remove('hidden');
			address_div.classList.remove('hidden');
			copy_section.classList.remove('hidden');
		}else{
			setTimeout(checkOrderNumber, 5000);
			
		}
		
		
	  } catch (error) {
		console.error("Error:", error);
	  }
	}
	
	var order_number = uuid.value
	if(order_number !=''){
		const data = { order_number: order_number };
		checkOrder(data);
	}
}

/*


function startAddressSubmission() {
	payment.classList.add('hidden');
	uuid.disabled = true;
	address_instruction_1.classList.remove('hidden');
	address_div.classList.remove('hidden');
	copy_section.classList.remove('hidden');
	
}
*/



/* Address Handling */
var address_div = document.getElementById('address');

var inputs = document.querySelectorAll('form.form input');
var senddata = document.getElementById('senddata');
var copy_section = document.getElementById('copy_section');
var copy_data_button = document.getElementById('copy_data');
var address_instruction_1 = document.getElementById('address_instruction_1');


var warnings = [];
var copy_data = false;

copy_data_button.addEventListener('click', () => { copy('senddata');}, false);

inputs.forEach((input) => {
input.addEventListener('input', validate, false);
input.addEventListener('keyup', validate, false);
input.addEventListener('blur', validate, false);		
});	


function fits(query){
	 
	let size =  new Blob([query]).size;
	console.log(size);
	if(size > 128){
		return false;
	}else{
		return true;
	}
}
function valid(element){
	if(element.value != '' || element.id =='l2'){
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
	

	
	if(warnings.length == 0){
		var params = {};
		var all_filled = true;
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
			copy_data = true;
			senddata.innerHTML = query;	
		}else if(!fits(query)){
			copy_data_button.disabled = true;
			copy_data = false;
			senddata.innerHTML = 'Too Long!';
		}else if(!all_filled){
			copy_data_button.disabled = true;
			copy_data = false;
			senddata.innerHTML = 'Finish Filling Out the Address Form';
			console.log(warnings);
		}
	}else{
		copy_data_button.disabled = true;
		senddata.innerHTML = '';
	}
	
	if(copy_data){
		copy_section.classList.add("success"); 
	}else{
		copy_section.classList.remove("success"); 
	}
}


/*
function copyData() {
	if(copy_data){
	  // Get the text field
	  var copyText = document.getElementById("senddata");

	  // Select the text field
	  copyText.select();
	  copyText.setSelectionRange(0, 99999); // For mobile devices

	   // Copy the text inside the text field
	  navigator.clipboard.writeText(copyText.value);

	  // Alert the copied text
 // alert("Copied the text: " + copyText.value);
	}
}

*/
</script>
