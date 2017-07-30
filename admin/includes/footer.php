<!-- div fermante de la div ouverte dnas "headerfull.php" -->	
</div> 

<!-- PIED DE PAGE, FOOTER -->

<footer class="text-center" id="footer">
	
	&copy; Copyright 2013-2017 Shaunta's Boutique

</footer>

<script type="text/javascript">
	
function get_child_options() {
	var parent_id = $('#parent').val();
	$.ajax({
		url: '/e-commerce/admin/parsers/child_categories.php',
		type: 'POST',
		data : {parent_id: parent_id},
		success: function(data) {
			$('#child').html(data);
		},
		error: function() {
			alert('Something went wrong with the child options');
		}
	});
}

$('select[name="parent"]').change(get_child_options);

</script>

</body>
</html>