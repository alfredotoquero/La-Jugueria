function fancy(ancho,alto,url){
	$.fancybox({
		'type'				:	'iframe',
		'speedIn'			:	600, 
		'speedOut'			:	400,
		'overlayShow'		: 	true,
		'overlayOpacity'	:	0.3,
		'overlayColor'		:	'#000',
		'hideOnOverlayClick':	false,
		'centerOnScroll'	:	true,
		'autoDimensions'	: 	false,
		'width'         	: 	ancho,
		'height'        	: 	alto,
		'href'				:	url,
		'enableEscapeButton':	true
	});
}