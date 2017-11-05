<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/e-commerce/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['delete']))
{
	$id = sanitize($_GET['delete']);
	$db->query("UPDATE products SET deleted = '1' WHERE id = '$id'");
	header('Location: products.php');
}

$dbpath = '';
/********************************************************************************************
****************************    AJOUT PRODUIT   *********************************************
********************************************************************************************/
if(isset($_GET['add']) || isset($_GET['edit'])) {

	$brand_query = $db->query("SELECT * FROM brand ORDER BY brand");
	$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
	$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
	$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
	$saved_image = "";
	

	if(isset($_GET['edit']))
	{
		$edit_id = (int)$_GET['edit'];
		$productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($productResults);

		if(isset($_GET['delete_image']))
		{
			$img_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
			unlink($img_url);
			$db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
			header('Location: products.php?edit='.$edit_id);
		}

		$category = ((isset($_POST['child']) && $_POST['child'] != "")?sanitize($_POST['child']):$product['categories']);
		$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
		$parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
		$parentResult = mysqli_fetch_assoc($parentQ);
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
		$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
		$list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
		$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
		$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
		$saved_image = (($product['image'] != "")?$product['image']:"");
		$dbpath = $saved_image;
	}

	if(!empty($sizes)) 
	{
		$sizeString = sanitize($sizes);
		
		//$sizeString = rtrim($sizeString,',');  j'enleve la virgule a la fin de la chaine en JavaScript dans lefooter dans la fonction updateSizes();
		$sizesArray = explode(',', $sizeString);
		$sArray = array();
		$qArray = array();
		foreach($sizesArray as $ss) 
		{
			$s = explode(':', $ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
		}
	} else
	{
		$sizesArray= array();
	}
	
	if($_POST) 
	{
		$dbpath = '';
		// Premier 'if() {]', pour le champ de prévisualisation des tailles et quanités, 
		$errors = array();
		

		//DEUXIEME 'if() {}', ON VERIFIE QUE LES CHAMPS DU FORMULAIRES D'AJOUT DE PRODUITS SONT VIDE? SI OUI ON REMPLI LE TABLEAU '$errors'
		$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
		foreach($required as $field) 
		{
			if($_POST[$field] == '') 
			{
				$errors[] = 'All fields with an asterisk are required.';
				break;
			}
		}
		//VERIFICATION DE LIMAGE UPLOADER POUR LAJOUT DE PRODUIT
		if(!empty($_FILES))
		{
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$nameArray = explode('.', $name);
			$fileName = $nameArray[0];
			$fileExt = $nameArray[1];
			$mime = explode('/', $photo['type']);
			$mimeType = $mime[0];
			$mimeExt = $mime[1];
			$tmpLoc = $photo['tmp_name'];
			$fileSize = $photo['size'];
			$allowed = array('png', 'jpg', 'jpeg', 'gif');
			$uploadName = md5(microtime()).'.'.$fileExt;
			$uploadPath = BASEURL.'images/products/'.$uploadName;
			$dbpath = '/e-commerce/images/products/'.$uploadName;

			if($mimeType != 'image')
			{
				$errors[] = 'The file must be an image.';
			}

			if(!in_array($fileExt, $allowed))
			{
				$errors[] = 'The photo extension must be a png, jpg, jpeg or gif.';
			}

			if($fileSize > 25000000) 
			{
				$errors[] = 'The file size must be under 25MB.';
			}

			if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg'))
			{
				$errors[] = 'File extension does not match the file.';
			}
		}

		//SI LE TABLEAU DERREURS NEST PAS VIDE? ON AFFICHE LES ERRUERS
		if(!empty($errors))
		{
			echo display_errors($errors);
		} else
		{
			//upload product photo and insertinto database
			move_uploaded_file($tmpLoc, $uploadPath);
			$insertSql = "INSERT INTO products (title, price, list_price, brand, categories, image, description, featured, sizes, deleted) VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$dbpath', '$description', '0', '$sizes', '0')";
   			
   			if(isset($_GET['edit']))
   			{
   				if(!empty($_FILES))
   				{
   					$insertSql = "UPDATE products 
   						SET title = '$title', 
   							price = '$price', 
   							list_price = '$list_price',
   							brand = '$brand',
   							categories = '$category',
   							image = '$dbpath',
   							description = '$description',
   							sizes = '$sizes'
   						WHERE id = '$edit_id'";
   				} else 
   				{
   					$insertSql =  "UPDATE products 
   						SET title = '$title', 
   							price = '$price', 
   							list_price = '$list_price',
   							brand = '$brand',
   							categories = '$category',
   							description = '$description',
   							sizes = '$sizes'
   						WHERE id = '$edit_id'";
   				}
   			}
   			$db->query($insertSql);
   			header('Location: products.php');
   			
		}


	}
?>
	
	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a new'); ?> Product</h2><hr>

	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
		
		<div class="form-group col-md-3">
			<label for="title">Title* :</label>
			<input 	type="text" 
					name="title" 
					id="title" 
					class="form-control"
					value="<?=$title;?>">
		</div>

		<div class="form-group col-md-3">
			<label for="brand">Brand* :</label>
			<select class="form-control" id="brand" name="brand">
				<option value="" <?=(($brand == '')?'selected':''); ?>></option>
					<?php 
					while($b = mysqli_fetch_assoc($brand_query)) 
					{ ?>
						<option 
							value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'') ?>><?=$b['brand'];?>
						</option>
					<?php	
					} 
					?>
			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="parent">Parent Category* :</label>
			<select class="form-control" id="parent" name="parent">
				<option value="" <?=(($parent == "")?'selected':'') ?>></option>
				<?php
				while( $p = mysqli_fetch_assoc($parent_query)) 
				{?>
					<option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':'');?>
					>
					<?=$p['category']; ?>
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
					value="<?=$price ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="list_price">List Price :</label>
			<input 	type="text" 
					name="list_price" 
					id="list_price" 
					class="form-control" 
					value="<?=$list_price ?>">
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
					value="<?=$sizes ?>"
					readonly>
		</div>
		<div class="form-group col-md-6">
			<?php if($saved_image != ""): ?>
				<div class="saved-image">
				<img src="<?=$saved_image;?>" alt="Saved Image"><br>
				<a href="products.php?delete_image=1&edit=<?=$edit_id ?>" class="text-danger">Delete Image</a>
				</div>
			<?php else: ?>
			<label for="photo">Product Photo* :</label>
			<input type="file" name="photo" id="photo" class="form-control">
		<?php endif; ?>
		</div>
		<div class="form-group col-md-6">
			<label for="description">Description* :</label>
			<textarea id="description" name="description" rows="6" class="form-control">
				<?=$description ?>
			</textarea>
		</div>
		<div class="col-md-2 pull-right">
			<a href="products.php" class="btn btn-default">Cancel</a>
			<input type="submit" name="submit"
				value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Product" class="btn btn-success">
		</div><div class="clearfix"></div>
	</form>

	<!-- *********** MODAL FOR SIZES OF ADDI?G PRODUCT ******************** -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
      </div>
      <div class="modal-body">
      	<div class="container-fluid">
        <?php for($i = 1; $i <= 5; $i++): ?>
        	<div class="form-group col-md-6">
        		<label for="size<?=$i;?>">Size</label>
        		<input 
        			type="text" 
        			name="size<?=$i;?>" 
        			id="size<?=$i;?>" 
        			value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" 
        			class="form-control">
        	</div>

        	<div class="form-group col-md-6">
        		<label for="qty<?=$i;?>">Quantity</label>
        		<input 
        			type="number" 
        			name="qty<?=$i;?>" 
        			id="qty<?=$i;?>" 
        			value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" 
        			min="0" 
        			class="form-control">
        	</div>
        <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" 
        	onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">
        	Save changes
        </button>
      </div>
    </div>
  </div>
</div>
<!-- *********** END MODAL FOR SIZES OF ADDI?G PRODUCT ******************** -->

<!-- *********** END ADD PRODUCT ******************** -->
<?php
/********************************************************************************************
****************************    LISTE PRODUIT   *********************************************
********************************************************************************************/
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
							<a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'	];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus') ;?>""></span>
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
<script type="text/javascript">
	$('document').ready(function() {
		get_child_options('<?= $category; ?>');
		
	});
</script>