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
						<div class="col-sm-6">
							<div class="center-block">
								<img src="<?= $product['image']; ?>" alt="<?=$product['title'];?>" class="details img-responsive"/>
							</div>
						</div>
						<div class="col-sm-6">
							<h4>Details</h4>
							<p><?=$product['description'];?></p><br>
							<p>Price : $<?=$product['price'];?></p><br>
							<p>Brand : <?=$brand2['brand'];?></p>
							<form action="add_cart.php" method="post">
								<div class="form-group">
									<div class="col-xs-3">
										<label for="quantity">Quantity :</label><input type="text" class="form-control" id="quantity" name="quantity">
									</div>
									
								</div><br><br>
								<div class="form-group">
									<label for="size">Size :</label>
									<select name="size" id="size" class="form-control">
										<option value=""></option>
										<?php 
											foreach($size_array as $string) {
												$string_array = explode(':', $string);
												$size  = $string_array[0];
												$quantity = $string_array[1];
												echo "<option value='".$size."'>".$size." (".$quantity." Available)</option>";
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
				<button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span>			Add to Cart
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
function closeModal() {
	$('#details-modal').modal('hide');
	setTimeout(function() {
		$('#details-modal').remove();
		$('.modal-backdrop').remove();
		alert();
	},500);
}

</script>

<?php 

echo ob_get_clean(); ?>