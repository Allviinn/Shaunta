<!-- MODAL DETAILS -->
<?php 

require_once('../core/init.php'); 

$id = $_POST['id'];

$id = (int)$id;

$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);

$brand_id = $product['brand'];
$sql2 = "SELECT brand FROM brand WHERE id = '$brand_id' ";
$brand = $db->query($sql2);
$brand2 = mysqli_fetch_assoc($brand);

$sizestring = $product['sizes'];

$size_array = explode(',', $sizestring);



ob_start(); 
?>
<div data-backdrop="static" data-keyboard="false" class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">	
			<div class="modal-header">
		
				<button class="close" type="button" onclick="closeModal();">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center"><?=$product['title'];?></h4>
			
			</div>
			
			
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
					<span id="modal_errors" class="bg-danger"></span>
						<div class="col-sm-6 fotorama">
						<?php 
							$photos = explode(',', $product['image']);
							foreach($photos as $photo): ?>
								<img 
								src="<?=$photo; ?>" 
								alt="<?=$product['title'];?>" 
								class="details img-responsive"/>
						<?php
							endforeach;
						?>
						</div>
						<div class="col-sm-6">
							<h4>Details</h4>
							<p><?=nl2br($product['description']);?></p><br>
							<p>Price : $<?=$product['price'];?></p><br>
							<p>Brand : <?=$brand2['brand'];?></p>
							<form action="add_cart.php" method="post" id="add_product_form">
								<input type="hidden" name="product_id" value="<?=$id;?>">
								<input type="hidden" name="available" id="available" value="">
								<div class="form-group">
									<div class="col-xs-3">
										<label for="quantity">Quantity :</label>
									<input type="number" class="form-control" id="quantity" name="quantity" min="0">
									</div>
									
								</div><br><br><br><br>
								<div class="form-group">
									<label for="size">Size :</label>
									<select name="size" id="size" class="form-control">
										<option value=""></option>
										<?php 
											foreach($size_array as $string) {
												$string_array = explode(':', $string);
												$size  = $string_array[0];
												$available = $string_array[1];
												if($available > 0)
												{
													echo "<option data-available='".$available."' value='".$size."'>".$size." (".$available." Available)</option>";
												}
											}
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button class="btn btn-default" onclick="closeModal();">Close</button>
				<button class="btn btn-warning" onclick="add_to_cart();return false;">
					<span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
$('#size').change(function(){
	var available = $('#size option:selected').data("available");
	$('#available').val(available);
});

$(function () {
	$('.fotorama').fotorama({
  		'loop': true,
  		'autoplay': true
  	});
});

function closeModal() {
	$('#details-modal').modal('hide');
	setTimeout(function() {
		$('#details-modal').remove();
		$('.modal-backdrop').remove();
		
	},500);
}

</script>

<?php 

echo ob_get_clean(); ?>