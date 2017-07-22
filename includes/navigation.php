<?php
//récuperation des categories pricipales
$sql = "SELECT * FROM categories WHERE parent = 0";
$pquery = $db->query($sql);
?>



<!-- *****************NAVIGATION**************** -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<a href="index.php" class="navbar-brand">Shaunta's Boutique</a>
		<!-- *************PREMIERE LISTE MENU******************* -->
		<ul class="nav navbar-nav">

			<?php 
			//affichage des categories principales
			while($parent = mysqli_fetch_assoc($pquery)) { 
			$parent_id = $parent['id']; //id parent pour les sous categories
			?>
				<li class="dropdown">
					<a href="#" class="dropdown" data-toggle="dropdown">
						<?php echo $parent['category']; ?><span class="	caret"></span>
					</a>
					<!--**************** LISTE IMBRIQUEE DU MENU ****************-->
					<ul class="dropdown-menu" role="menu">

						<?php 
							//recuperation et affichage des sous categories en fonction de l'id_parent
							$sql2 = "SELECT * FROM categories WHERE parent =".$parent_id;
							$kid_query = $db->query($sql2); 
							while ($parent2 = mysqli_fetch_assoc($kid_query)) { ?>
	
							<li><a href="#"><?php echo $parent2['category']; ?></a></li>
							
						<?php } ?>
					</ul>
					<!--************** FIN LISTE IMBRIQUEE DU MENU ****************-->
				</li>
				
			<?php } ?>
		</ul>
		<!-- ***************FIN PREMIERE LISTE MENU **************-->
	</div>
</nav>
<!--************ FIN NAVIGATION**************** -->