<?php
require_once $_SERVER['DOCUMENT_ROOT'].'e-commerce/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['add'])) {

	$brand_query = $db->query("SELECT * FROM brand ORDER BY brand");

	$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
?>
	
	<h2 class="text-center">Add a new Product</h2><hr>

	<form action="products.php?add=1" method="post" enctype="multipart/form-data">
		
		<div class="form-group col-md-3">
			<label for="title">Title* :</label>
			<input 	type="text" 
					name="title" 
					id="title" 
					class="form-control"
					value="<?=((isset($_POST['title']))?sanitize($_POST['title']):'') ?>">
		</div>

		<div class="form-group col-md-3">
			<label for="brand">Brand* :</label>
			<select class="form-control" id="brand" name="brand">
				<option value="" <?=((isset($_POST['brand']) && $_POST['brand'] == '')?'selected':''); ?>></option>
					<?php 
					while($brand = mysqli_fetch_assoc($brand_query)) 
					{ ?>
						<option 
							value="<?=$brand['id'];?>" 
							<?=((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])?'selected':'') ?>
						>
						<?=$brand['brand'];?>
						</option>
					<?php	
					} 
					?>
			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="parent">Parent Category* :</label>
			<select class="form-control" id="parent" name="parent">
				<option value="" <?=((isset($_POST['parent']) && $_POST['parent'] == '')?'selected':'') ?>></option>
				<?php
				while( $parent = mysqli_fetch_assoc($parent_query)) 
				{?>
					<option value="<?=$parent['id'] ?>" 
						<?=((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?'selected':'') ?>
					>
					<?=$parent['category']; ?>
					</option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="child">Child Categories* :</label>
			<select class="form-control" id="child" name="child">
				
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="price">Price* :</label>
			<input 	type="text" 
					name="price" 
					id="price" 
					class="form-control" 
					value="<?=((isset($_POST['price']))?sanitize($_POST['price']):'') ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="list_price">List Price* :</label>
			<input 	type="text" 
					name="list_price" 
					id="list_price" 
					class="form-control" 
					value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']):'') ?>">
		</div>
		<div class="form-group col-md-3">
			<label for>Quantity & Sizes* :</label>
			<button class="btn btn-default form-control" 
					onclick="$('#sizesModal').modal('toggle');return false">
				Quantity & Sizes
			</button>
		</div>
		<div class="form-group col-md-3">
			<label for="sizes">Sizes & Quantity Preview</label>
			<input 	type="text" 
					name="sizes" 
					id="sizes"
					class="form-control" 
					value="<?=((isset($_POST['sizes']))?$_POST['sizes']:'') ?>"
					readonly>
		</div>
		<div class="form-group col-md-6">
			<label for="photo">Product Photo* :</label>
			<input type="file" name="photo" id="photo" class="form-control">
		</div>
		<div class="form-group col-md-6">
			<label for="description">Description* :</label>
			<textarea id="description" name="description" rows="6" class="form-control">
				<?=((isset($_POST['description']))?sanitize($_POST['description']):'') ?>
			</textarea>
		</div>
		<div class="col-md-2 pull-right">
			<input type="submit" name="submit" value="Add Product" class="btn btn-success form-control">
		</div><div class="clearfix"></div>
	</form>

<?php
} else {

	$products = "SELECT * FROM products WHERE deleted != 1";
	$products = $db->query($products);
	if(isset($_GET['featured'])) {
		$featured_id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featured_sql = "UPDATE products SET featured = '$featured' WHERE id = '$featured_id'";
		$db->query($featured_sql);
		header('Location: products.php');
	}
	?>
	
	<h2 class="text-center">Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New Product</a><div class="	clearfix"></div>
	<hr>
	
	<div class="container">
		<table class="table table-bordered table-condensed table-striped">
			
			<thead>
				<th></th>	<th>Product</th>	<th>Price</th>	<th>List Price</th> <th>Category</th>	<th>Featured</th>		<th>Sold</th>
			</thead>
		
			<tbody>
				<?php 
					while($product = mysqli_fetch_assoc($products)) { 
						$child_id = (int)$product['categories'];
						$child_cat_sql = "SELECT * FROM categories WHERE id = '$child_id'";
						$child_cat_sql = $db->query($child_cat_sql);
						$child_cat = mysqli_fetch_assoc($child_cat_sql);
	
						$parent_id = (int)$child_cat['parent'];
						$parent_cat_sql = "SELECT * FROM categories WHERE id = '$parent_id'";
						$parent_cat_sql = $db->query($parent_cat_sql);
						$parent_cat = mysqli_fetch_assoc($parent_cat_sql);
	
						$category = $parent_cat['category'].'~'.$child_cat['category'];
	
				?>
		
					<tr>
						<td>
							<a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-remove"></span>
							</a>
						</td>
						<td><?=$product['title']; ?></td>
						<td><?=money($product['price']); ?></td>
						<td><?=money($product['list_price']); ?></td>
						<td><?=$category; ?></td>
						<td>
							<a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'	];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($product['	featured'] == 1)?'minus':'plus') ;?>""></span>
							</a>
							&nbsp <?=(($product['featured'] == 1)?'Featured Product':'Not Featured') ?>
						</td>
						<td>0</td>
					</tr>
		
				<?php } ?>
			</tbody>
		</table>
	</div>
	
	
	
	
<?php
}
include 'includes/footer.php';

?>
