<!-- div fermante de la div ouverte dnas "headerfull.php" -->	
</div> 

<!-- PIED DE PAGE, FOOTER -->
<footer class="text-center" id="footer">
	
	&copy; Copyright 2013-2017 Shaunta's Boutique

</footer>

<script type="text/javascript">
	// script qui soccupe de la petite animation des scroll d'images dans l'en tete de la page
	$(window).scroll(function() {
		var vscroll = $(this).scrollTop();
		$("#logo-text").css({
			"transform" : "translate(0px, "+vscroll/2+"px)"
		});

		var vscroll = $(this).scrollTop();
		$("#back-flower").css({
			"transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
		});

		var vscroll = $(this).scrollTop();
		$("#for-flower").css({
			"transform" : "translate(0px, -"+vscroll/2+"px)"
		});

	});

function detailModal(id) {
	
	var data = { "id": id};
	$.ajax({
		url: '/e-commerce/includes/details_modal.php',
		method: "post",
		data: data,
		success: function(data) {
			
			$('body').append(data);
			$('#details-modal').modal('toggle');

		},
		error: function() {
			alert('Something went wrong');
		}
	});
}

</script>


</body>
</html>