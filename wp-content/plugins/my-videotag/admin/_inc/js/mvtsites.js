$(document).ready(function(){
	$('.mvtsites-lanzar').click(function(event){
		event.stopPropagation();
		event.preventDefault();
		$('.mvtsites:visible').fadeOut();
		var popup = $(this).next('.mvtsites');
		popup.fadeIn();
		
		//obtener dimensiones
		var popup = $(this).next('.mvtsites');
		var docAltura = $(window).height();
		var docAncho = $(window).width();
		var popupAlto = popup.height();
		var popupAncho = popup.width();
		
		//centrar caja
		popup.css({
			'left': parseInt(0.5*(docAncho - popupAncho)),
			'bottom': parseInt(0.5*(docAltura - popupAlto)),
			'z-index':500
		});
		
		
	});
	$('.cerrar').click(function(event){
		$('.mvtsites:visible').fadeOut();
		event.preventDefault();
	});
	$(document).click(function(){
		$('.mvtsites:visible').fadeOut();
	});
});