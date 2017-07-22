<?php 
require_once "core/init.php"; //connection a la BDD
include "includes/head.php"; //head (DOCTYPE ....)
include "includes/navigation.php"; //menu de navigation
include "includes/headerfull.php"; //image transform scale
include "includes/left_bar.php"; //bar a gauche du contenu pricipale

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $db->query($sql);

?>


<!-- CONTENU PRINCIPAL-->
<div class='col-md-8'>
	<div class="row">

		<h2 class="text-center">Feature Products</h2>

	<?php while ($products = mysqli_fetch_assoc($featured)) { ?>

		<div class="col-md-3">
			<h4 class="product_title"><?php echo $products["title"]; ?></h4>
			<img src="<?php echo $products['image']; ?>" alt="<?php echo $products['image']; ?>" class="img-thumb"/>
			<p class="list-price text-danger">List Price: <s>$<?php echo $products["list_price"]; ?></s></p>
			<p class="price">Our Price : $<?php echo $products["price"]; ?></p>
			<button type="button" class="btn btn-sm btn-success" onclick="detailModal(<?= $products['id']; ?>)">
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