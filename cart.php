<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';

if($cart_id != '')
{
	$cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'], true);
	$i = 1;
	$sub_total = 0;
	$item_count = 0;
}

?>


<div class="col-md-12">
	<div class="container">
		<div class="row">
			<h2 class="text-center">My Shopping Cart</h2><hr>
			<?php if($cart_id == ''): ?>
				<div class="bg-danger">
					<p class="text-center text-danger">Your Shopping cart is empty.</p>
				</div>
			<?php else: ?>
	
				<table class="table table-bordered table-condensed table-striped">
					<thead>
						<th>#</th>
						<th>Item</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Size</th>
						<th>Sub Total</th>
					</thead>
					<tbody>
						<?php
							foreach($items as $item)
							{
								$product_id = $item['id'];
								$productQ = $db->query("SELECT * FROM products WHERE id = '$product_id'");
								$product = mysqli_fetch_assoc($productQ);
								$sArray = explode(',', $product['sizes']);
								foreach($sArray as $sizeString)
								{
									$s = explode(':', $sizeString);
									if($s[0] == $item['size'])
									{
										$available = $s[1];
									}
									
								} ?>
	
								<tr>
									<td><?=$i; ?></td>
									<td><?=$product['title']; ?></td>
									<td><?=money($product['price']); ?></td>
									<td>
										<button class="btn btn-xs btn-default" 
					onclick="update_cart('removeone', '<?=$product['id']; ?>', '<?=$item['size']; ?>');">-</button>
										<?=$item['quantity']; ?>
										<?php if($item['quantity'] < $available):?>
										<button class="btn btn-xs btn-default" 
					onclick="update_cart('addone', '<?=$product['id']; ?>', '<?=$item['size']; ?>');">+</button>
										<?php else: ?>
											<span class="text-danger">Max</span>
										<?php endif; ?>
									</td>
									<td><?=$item['size']; ?></td>
									<td><?=money($item['quantity'] * $product['price']); ?></td>
								</tr>
	
							<?php 
							$i++;
							$item_count += $item['quantity'];
							$sub_total += ($product['price'] * $item['quantity']);
							} 
	
							$tax = TAXRATE * $sub_total;
							$tax = number_format($tax, 2);
							$grand_total = $tax + $sub_total;
	
	
							?>
					</tbody>
				</table>
				<table class="table table-bordered table-condensed text-right">
					<legend>Totals</legend>
					<thead class="totals-table-header">
						<th class="text-center">Total Items</th>
						<th class="text-center">Sub Total</th>
						<th class="text-center">Tax</th>
						<th class="text-right">Grand Total</th>
					</thead>
					<tbody>
						<tr>
							<td><?=$item_count; ?></td>
							<td><?=money($sub_total) ?></td>
							<td><?=money($tax); ?></td>
							<td class="bg-success"><?=money($grand_total); ?></td>
						</tr>
					</tbody>
				</table>
				<!-- CHECKOUT BUTTON -->
				<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal"><span class="glyphicon glyphicon-shopping-cart"></span>
  					Check Out >>
				</button>

				<!-- MODAL -->
				<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
				  	<div class="modal-dialog modal-lg" role="document">
				  	  	<div class="modal-content">
				  	  	<!-- MODAL HEADER -->
				  	  		<div class="modal-header">
				  	  		 	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  	  		 		<span aria-hidden="true">&times;</span>
				  	  		 	</button>
				  	  		  	<h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
				  	  		</div>
				  	  		<!-- MODAL BODY -->
				  	  		<div class="modal-body">
				  	  		<div class="row">
<form action="thankYou.php" method="post" id="payment-form">
				  	  		 	<span class="bg-danger" id="payment-errors"></span>
				  	  		 		<div id="step1" style="display:block;">
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="full_name">Full Name :</label>
				  	  		 				<input class="form-control" id="full_name" type="text" name="full_name">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="email">Email :</label>
				  	  		 				<input class="form-control" id="email" type="email" name="email">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="street">Street Address :</label>
				  	  		 				<input class="form-control" id="street" type="text" name="street">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="street2">Street Address 2 :</label>
				  	  		 				<input class="form-control" id="street2" type="text" name="street2">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="city">City :</label>
				  	  		 				<input class="form-control" id="city" type="text" name="city">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="state">State :</label>
				  	  		 				<input class="form-control" id="state" type="text" name="state">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="zip_code">Zip Code :</label>
				  	  		 				<input class="form-control" id="zip_code" type="text" name="zip_code">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-6">
				  	  		 				<label for="country">Country :</label>
				  	  		 				<input class="form-control" id="country"s type="text" name="country">
				  	  		 			</div>
				  	  		 		</div>

				  	  		 		<div id="step2" style="display:none;">
				  	  		 			<div class="form-group col-md-3">
				  	  		 				<label for="name">Name on Card :</label>
				  	  		 				<input type="text" id="name" class="form-control">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-2">
				  	  		 				<label for="cvc">CVC :</label>
				  	  		 				<input type="text" id="cvc" class="form-control">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-3">
				  	  		 				<label for="number">Card Number :</label>
				  	  		 				<input type="text" id="number" class="form-control">
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-2">
				  	  		 				<label for="exp-month">Expire Month :</label>
				  	  		 				<select id="exp-month" class="form-control">
				  	  		 					<option value=""></option>
				  	  		 					<?php for($i = 1; $i < 13; $i++): ?>
				  	  		 						<option value="<?=$i; ?>"><?=$i; ?></option>
				  	  		 					<?php endfor; ?>
				  	  		 				</select>
				  	  		 			</div>
				  	  		 			<div class="form-group col-md-2">
				  	  		 				<label for="exp-year">Expire Year :</label>
				  	  		 				<select class="form-control" id="exp-year">
				  	  		 					<option value=""></option>
				  	  		 					<?php 
				  	  		 					$yr = date("Y");
				  	  		 					for($i = 0; $i < 11; $i++): ?>
				  	  		 						<option value="<?=$yr + $i; ?>"><?=$yr + $i; ?></option>
				  	  		 					<?php endfor; ?>
				  	  		 				</select>
				  	  		 			</div>
				  	  		 		</div>
				  	  		 	
				  	  		</div>
				  	  		</div>
				  	  		<!-- MODAL FOOTER -->
				  	  		<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">
	Close
</button>
<button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">
	Next >>
</button>

<button type="button" class="btn btn-default" onclick="back_address();" style="display: none;" id="back_button">
	Back
</button>
<button type="submit" class="btn btn-primary" style="display: none;" id="checkout_button">
	Check Out >>
</button>
</form>
				  	  		</div>
				  	  	</div>
				  	</div>
				</div>
				<!-- END MODAL -->
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/javascript">

	function back_address() {
		$('#payment-errors').html('');

		$('#payment-errors').html('');
		$('#step1').css('display', 'block');
		$('#step2').css('display', 'none');
		$('#next_button').css('display', 'inline-block');
		$('#back_button').css('display', 'none');
		$('#checkout_button').css('display', 'none');

		$('#checkoutModalLabel').html('Shipping Address');
	}

	function check_address() {
		var data = {
			"full_name" : $('#full_name').val(),
			"email" : $('#email').val(),
			"street" : $('#street').val(),
			"street2" : $('#street2').val(),
			"city" : $('#city').val(),
			"state" : $('#state').val(),
			"zip_code" : $('#zip_code').val(),
			"country" : $('#country').val()
		};

		$.ajax({
			url : "/e-commerce/admin/parsers/check_address.php",
			method : "post",
			data : data,
			success : function(data) {
				if(data != 'passed')
				{
					$('#payment-errors').html(data);
				}

				if(data == 'passed')
				{
					$('#payment-errors').html('');
					$('#step1').css('display', 'none');
					$('#step2').css('display', 'block');
					$('#next_button').css('display', 'none');
					$('#back_button').css('display', 'inline-block');
					$('#checkout_button').css('display', 'inline-block');

					$('#checkoutModalLabel').html('Enter your Card Details');
				}
			},
			error : function() {alert('Something wet wrong.')}
		});
	}

	//clé de test public de stripe
	Stripe.setPublishableKey('<?=STRIPE_PUBLIC; ?>');

</script>


<?php

include 'includes/footer.php';

?>