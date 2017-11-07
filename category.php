<?php 
require_once "core/init.php"; //connection a la BDD
include "includes/head.php"; //head (DOCTYPE ....)
include "includes/navigation.php"; //menu de navigation
include "includes/headerpartial.php"; //image transform scale
include "includes/left_bar.php"; //bar a gauche du contenu pricipale

if(isset($_GET['cat']))
{
	$cat_id = sanitize($_GET['cat']);
} else
{
	$cat_id = '';
}

$sql = "SELECT * FROM products WHERE categories = '$cat_id'";
$productQ = $db->query($sql);
$category = get_category($cat_id);

?>


<!-- CONTENU PRINCIPAL-->
<div class='col-md-8'>
	<div class="row">

		<h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>

	<?php while ($products = mysqli_fetch_assoc($productQ)) { ?>

		<div class="col-md-3 product-div">
			<h4 class="product_title text-center"><?php echo $products["title"]; ?></h4>
			<img src="<?php echo $products['image']; ?>" alt="<?php echo $products['image']; ?>" class="img-thumb center-block"/>
			<p class="list-price text-danger text-center">
				List Price: <s>$<?php echo $products["list_price"]; ?></s>
			</p>
			<p class="price text-center">
				Our Price : $<?php echo $products["price"]; ?>
			</p>
			<button type="button" class="btn btn-sm btn-success center-block" onclick="detailModal(<?= $products['id']; ?>)">
				Details
			</button>
		</div>

	<?php } ?>

	</div>
</div>




<?php

include "includes/right_bar.php"; //bar a droite du conteneu principal
include "includes/footer.php";//footer 

?>