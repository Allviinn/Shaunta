<!-- div fermante de la div ouverte dnas "headerfull.php" -->	
</div> 

<!-- PIED DE PAGE, FOOTER -->

<footer class="text-center" id="footer">
	
	&copy; Copyright 2013-2017 Shaunta's Boutique

</footer>

<script type="text/javascript">
	
function updateSizes() {
	//fonction pour voir prévisualiser les taille et quantité rentrés
	var sizeString = '';
	for(var i = 1; i <= 5; i++) 
	{
		if($('#size'+i).val() != '') 
		{
			sizeString += $('#size'+i).val()+':'+$('#qty'+i).val()+':'+$('#threshold'+i).val()+',';

		}
	}
	//fonction pour enlenver la virgule a la fin de quantité et taille
	var strLen = sizeString.length;
	var lastCar = sizeString.substr(strLen-1, strLen);
	if(lastCar == ",") {
		sizeString = sizeString.substr(0, strLen-1);
	}
	
	$('#sizes').val(sizeString);
}


//dans l'admin ajout produit, cette fonction (ajax) permet d'appeler les catégories enfant de la catégorie parent selectionner dans laquelle on souaite ajouter un poduit.
function get_child_options(selected) {
	if (typeof selected === 'undefined')
	{
		var selected = "";
	}
	var parent_id = $('#parent').val();
	$.ajax({
		url: '/e-commerce/admin/parsers/child_categories.php',
		type: 'POST',
		data : {parent_id: parent_id, selected: selected},
		success: function(data) {
			$('#child').html(data);
		},
		error: function() {
			alert('Something went wrong with the child options');
		}
	});
}

$('select[name="parent"]').change(function() {
	get_child_options();
});

</script>

</body>
</html>