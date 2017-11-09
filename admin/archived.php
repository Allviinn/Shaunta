<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/e-commerce/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

/********************************************************************************************
**************************** DESUPPRESSION PRODUIT   ****************************************
********************************************************************************************/
if(isset($_GET['restore']))
{
	$restore_id = sanitize($_GET['restore']);
	$db->query("UPDATE products SET deleted = '0' WHERE id = '$restore_id'");
	//header('Location: products.php');
}
?>
<h2 class="text-center">Archived Products</h2>
	
	<div class="container">
		<table class="table table-bordered table-condensed table-striped">
			
			<thead>
				<th></th>	<th>Product</th>	<th>Price</th>	<th>List Price</th> <th>Category</th>
			</thead>
		
			<tbody>
				<?php 
					$archivedProducts = $db->query("SELECT * FROM products WHERE deleted = '1'");
					while($archProduct = mysqli_fetch_assoc($archivedProducts)) { 
						$child_id = (int)$archProduct['categories'];
						$category = get_category($child_id);
						$category = $category['parent'].'~'.$category['child'];
	
				?>
		
					<tr>
						<td align="center">
							<a href="archived.php?restore=<?=$archProduct['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-refresh"></span>
							</a>
						</td>
						<td><?=$archProduct['title']; ?></td>
						<td><?=money($archProduct['price']); ?></td>
						<td><?=money($archProduct['list_price']); ?></td>
						<td><?=$category; ?></td>
					</tr>
		
				<?php } ?>
			</tbody>
		</table>
	</div> 

<?php
include 'includes/footer.php';
?>