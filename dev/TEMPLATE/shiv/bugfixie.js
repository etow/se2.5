$(document).ready(function() { 
	$('body').css("overflow","hidden");
	$('#continuarSinActualizar').click(function() {
		$('#continuarSinActualizarDiv').css({display: "none"});
		$('body').css({"overflow":"auto"});
	});
})