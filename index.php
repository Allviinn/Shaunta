<?php 
require_once "core/init.php"; //connection a la BDD
include "includes/head.php"; //head (DOCTYPE ....)
include "includes/navigation.php"; //menu de navigation
include "includes/headerfull.php"; //image transform scale
include "includes/left_bar.php"; //bar a gauche du contenu pricipale


$count_all = $db->query("SELECT COUNT(id) AS 'products_count' FROM products WHERE featured = 1");
$count_all = mysqli_fetch_assoc($count_all);

//pagintion variables
$count_all = $count_all['products_count'];
$per_page = 4;
$current_page = 1;
if(isset($_GET['p'])) {
	$current_page = sanitize($_GET['p']);
}

$page_numbers = ceil($count_all / $per_page);

$down_limit = (($current_page -1) * $per_page);

echo $down_limit;
$featured = $db->query("SELECT * FROM products 
						WHERE featured = 1 
						LIMIT ".$down_limit.", ".$per_page);


?>


<!-- CONTENU PRINCIPAL-->
<div class='col-md-8'>
	<div class="row">

		<h2 class="text-center">Feature Products</h2>

	<?php while ($products = mysqli_fetch_assoc($featured)) { ?>

		<div class="col-md-3 product-div">
			<h4 class="product_title text-center"><?=$products["title"];?></h4>
			<?php 
				$photos = explode(',', $products['image']);
			?>
			<img src="<?=$photos[0];?>" alt="<?=$products['image'];?>" class="img-thumb center-block product-img"/>
			<p class="list-price text-danger text-center">
				List Price: <s>$<?php echo $products["list_price"]; ?></s>
			</p>
			<p class="price text-center">
				Our Price : $<?=$products["price"];?>
			</p>
			<button type="button" class="btn btn-sm btn-success center-block" onclick="detailModal(<?= $products['id']; ?>)">
				Details
			</button>
		</div>

	<?php }	?>



	</div><br><br>
	<!-- pagination-->
	<div class="text-center">
		<ul class="pagination">
			<?php
				for($i = 1; $i <= $page_numbers; $i++)
				{
					echo '<li class="'.(($current_page == $i)?'active':'').'"><a href="index.php?p='.$i.'">'.$i.'</a></li>';
				}
			?>
		</ul>
	</div>
</div>




<?php

include "includes/right_bar.php"; //bar a droite du conteneu principal
include "includes/footer.php";//footer 

?>