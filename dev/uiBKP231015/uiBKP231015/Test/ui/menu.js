/*
* @version 1.4.1
* Release: 2014
*
*
* Log
* $Fecha: 2014-04-03  $
* $version: 1.4.1 - Arreglado bug en posicion del menu despues del resize
*
* $Fecha: 2014-03-06  $
* $version: 1.4.0 - Nuevo Menu Despegable, Optimizado para el uso de menos recursos.
*                 - Deteccion de dispositivo para mejor scroll de menu
*
*/

$(document).ready(function() { 

/*####### CONSTANTES #######*/
var margin = 0;
var offset = $('#menu-mobile').offset();
var id,idScroll,y;
var top = $('#menu-mobile-button').offset().top - parseFloat($('#menu-mobile-button').css('marginTop').replace(/auto/, 0));
var menuAbierto = false;
var clickMobile = false;
var doOnce = true;
var Opera = (navigator.userAgent.match(/Opera|OPR\//) ? true : false);
var ipx = (navigator.userAgent.match(/(iPod|iPhone|iPad)/i) ? true : false);
var	android = (navigator.userAgent.match(/Android/i) ? true : false);
var	osx = (navigator.userAgent.match(/webOS/i) ? true : false);
		
/*####### RESIZE #######*/

$(window).resize(function() {

	if(window.innerWidth < 768)
	{	
		if(doOnce)
		{
			top = $('#menu-mobile-button').offset().top - parseFloat($('#menu-mobile-button').css('marginTop').replace(/auto/, 0));
			doOnce = false;
		}
				clearTimeout(id);
    			id = setTimeout(doneResizing, 500);
	}
	else
	{
		if(menuAbierto = true)
		{
			$.fn.cerrarMenu(100);
		}
		$('#menu-mobile-button').offset().top = top;
	}

});

/*####### SCROLL #######*/

$(window).scroll(function (event) {		
	$('#main').css("min-height",$(window).height());
	if(window.innerWidth < 768)
	{	
		y = $(this).scrollTop();
		
		if (y >= top) {
			if(Opera || ipx)
			{
				$('#menu-mobile-button').addClass('fijoMobile');
				$('#menu-mobile-button').css("display","none")
				clearTimeout(idScroll);
    			idScroll = setTimeout(doneScroll, 200);
				
			}
			else
			{
				  $('#menu-mobile-button').addClass('fijo');
			}
		} else {
			$('#menu-mobile-button').removeClass('fijo');
			$('#menu-mobile-button').removeClass('fijoMobile');
		}	
	}
});

/*####### CLICKS #######*/

/*click en el boton de menu*/
$("#menu-mobile-button").click(function() {
	//Capa que blockea el sitio de fondo
	$('#layer-menu-mobile').css({
		"display": "block", 
		"height": $('#main').height() 
	});
		
	$('#cerrarMenu').css("display", "block");
		
	$('#menu-mobile').css({
		"height": $('#main').height() 
	});
	 
	/* Esto es para que el menu aparesca a la altura, segun donde hiso click */
	
	offset = $('#menu-mobile').offset();
	margin = $(window).scrollTop() - offset.top;
	$("#menu-mobile").css("paddingTop",margin);
	$("#cerrarMenu").css("marginTop",margin);
	$('#menu-mobile').animate({
		left: '0'
		}, 500, function() {
					menuAbierto=true;
				}
		);
					
	$('#layer-menu-mobile').animate({
		opacity: '1'
		}, 500);

});

/*click en layer negro*/
	$('#layer-menu-mobile').click(function(){
  		$.fn.cerrarMenu(500);
	});
/*click en x */
	$('#cerrarMenu').click(function(){
  		$.fn.cerrarMenu(500);
	});
	
/*Click en las listas del menu lateral Mobile*/
	$('#menu-mobile ul.MenuPrincipal li:has(ul)').click( 
	function(e) 
      { 
	  	  $(this).find('ul').slideToggle("fast");
		  if(clickMobile==false)
		  {
			$(this).find('i').first().removeClass("fi-arrow-down");
			$(this).find('i').first().addClass("fi-arrow-up");      
		   	clickMobile =!clickMobile;
		  }
		  else
		  {
					clickMobile =!clickMobile;
					$(this).find('i').first().addClass("fi-arrow-down");
					$(this).find('i').first().removeClass("fi-arrow-up");  
		  }
	  }
	  
   	  );

//Agrego el link abajo en la lista
	$("#menu-mobile ul.MenuPrincipal").attr("id","MenuPrincipalMobile");
	
	$("#MenuPrincipalMobile li").has('ul').each(function(){     
    	        $(this).find('ul').prepend("<li><a class='"+$(this).find('a').first().attr("class")+"' href='"+$(this).find('a').first().attr("href")+"'>"+$(this).find('a').first().html()+"</a></li>");
				$(this).find('a').first().removeAttr("href");
				$(this).find('a').first().append('<i class="fi-arrow-down icon-mobile"></i>');  
      });
			
/*####### FUNCIONES #######*/
	
$.fn.cerrarMenu = function ($velocidad) {

	$('#menu-mobile').animate({
		left: '-100%'
	}, $velocidad, function() {
				$('#menu-mobile').css("marginTop",0);
				$('#layer-menu-mobile').css("display", "none");
				$('#cerrarMenu').css("display", "none");
				menuAbierto = false;
			});
	$('#layer-menu-mobile').animate({
		opacity: '0'
	}, 100);
	
};

function doneResizing(){
	if(menuAbierto)
	{
	$('#layer-menu-mobile').height($('#main').height());
	$('#menu-mobile').height($('#main').height());
	}
}
function doneScroll(){
	$("#menu-mobile-button").css("top", (y-40));
	$('#menu-mobile-button').css("display","block");
	$("#menu-mobile-button").stop().animate({
		top: $(window).scrollTop() - 1
	});

}

/*FINAL DEL FN MAIN*/
});


