<?php

require_once $_SERVER['DOCUMENT_ROOT'].'e-commerce/core/init.php';


$parent_id = (int)$_POST['parent_id'];
$child_query = $db->query("SELECT * FROM categories WHERE parent = '$parent_id' ORDER BY category");
ob_start();
?>

<option value=""></option>
<?php
while($child = mysqli_fetch_assoc($child_query)) { ?>
	<option value="<?=$child['id'] ?>"><?=$child['category'] ?></option>
<?php } 
?>

<?php
echo ob_get_clean();
?>