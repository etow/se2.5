/*###################
    LOADING GIF
####################*/
var alineadoM = false, alineadoI = false;
/*
var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
var cargandoSitio = document.createElement('div');
cargandoSitio.style.width = x+"px"; cargandoSitio.style.height = y+"px";
cargandoSitio.id = 'loading-se';
cargandoSitio.innerHTML = '<div class="loader-se"><p>Cargando...</p></div>';*/


$(document).ready(function() { 
$('#main').css("min-height",$(window).height());
/*#######################
CODIGO DEL MENU PRINCIPAL
#########################*/   
 	$('#menu-superior ul.MenuPrincipal li:has(ul)').hover( 
          function(e) 
          { 
            $(this).find('ul').slideDown("fast");
          }, 
          function(e) 
          { 
             $(this).find('ul').hide();
          } 
  ); 
/*###################
CHEQUEADOR DE VACIOS
####################*/
	var vacios = 0;

	if (jQuery.trim ($('.texto-cabecera').text()) == "")
   {
	$('.texto-cabecera').css("display","none");
	
   }
   	if (jQuery.trim ($('.telefono').text()) == "")
   {
	$('.telefono-container').css("display","none");
	vacios += 1;
   }
   	if (jQuery.trim ($('.calle').text()) == "")
   {
	$('.direccion-container').css("display","none");
	vacios += 1;
   }
   	if (jQuery.trim ($('.mail').text()) == "")
   {
	$('.mail-container').css("display","none");
	vacios += 1;
   }
   	if (jQuery.trim ($('.web').text()) == "")
   {
	$('.web-container').css("display","none");
   }
   
   if(vacios == 3)
   {
	   $('address.contacto').css("display","none");
   }
});


$(window).resize(function() {
  if(window.innerWidth > 767)
  {
    alinearMenu();
  }
  else{
    alinearMenu("icono");
  }
});

function alinearMenu(tipo){
  var m, s, l, n, ul, altoLinea,altoLineaTop,pm;
  if(alineadoI == false || alineadoM == false){
    if(tipo == "icono" && alineadoI == false){
      m = $(".iconoMenuBg");
      alineadoI = true;
      alinearOk();
    }else if(alineadoM == false && tipo !="icono"){
      m = $("#menu-superior .MenuPrincipal > li > a");
      alineadoM = true;
      alinearOk();
    }
  
  }
  function alinearOk(){
    altoLinea = parseInt(m.outerHeight());
    l = parseInt($("#logo .ImagenLogo").outerHeight(true))||false;
    n = parseInt($(".nombre").outerHeight(true));
    ul = $("#menu-superior .MenuPrincipal li ul");
    if(l)
    {
      //$(".nombre").hide();
        pm = (l - altoLinea)/2;
        altoLineaTop = altoLinea;
        $('#menuContainer').css("marginTop",pm+15+"px");
        ul.css("top",altoLineaTop-2+"px");
    }
    else
    {
      if(altoLinea < n){
        pm = (n - altoLinea)/2;
        altoLineaTop = altoLinea;
        m.css("marginTop",pm+"px");
        ul.css("top",altoLineaTop+pm+"px");
      }
      else{
        pm = (altoLinea - n);
        altoLineaTop = altoLinea;
        ul.css("top",altoLineaTop+"px");
      }
    }
  }
}

