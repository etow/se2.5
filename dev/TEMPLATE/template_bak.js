(function($) {
  $.fn.visible = function(partial) {
      var $t            = $(this),
          $w            = $(window),
          viewTop       = $w.scrollTop(),
          viewBottom    = viewTop + $w.height(),
          _top          = $t.offset().top,
          _bottom       = _top + $t.height(),
          compareTop    = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;
    
    return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
  };
})(jQuery);

(function($) {
	var se = {
		el:{
			$RsMenuButton : $('.RsMenuButton'),
			$LogoAndMenuHolder : $('.Section--logoAndMenu'),
			$LogoAndMenu : $('.LogoAndMenu'),
			$Menu : $('.MainMenuContainer'),
			$MenuMobile : $('.MenuMobile'),
			$Mask : $('.SiteMask'),
			$Site : $('.SiteWrapper'),
			$Close : $('.MenuMobile-close'),
			$Sticky : $('.StickyHelper'),
			$animates : $(".u-animate")
		},
		widgets:{
			$tienda : $('#tienda_virtual_listado')
		},
		vars:{
			menuAbierto : false,
			offsetTop : parseInt($('.LogoAndMenu').offset().top),
			helperHeight : $('.LogoAndMenu').height(),
			latest: 0,
			latestTop:0,
			mobile: false
		},
		onResize:function(){
			setTimeout(
			  function() 
			  {
			  	se.resizeMenu();
			  	se.resizeTienda();
			  }, 200);
		},
		onScroll: function(){
			if($('.SeConfig').attr('data-sticky') == '1' && $('.SeConfig').attr('data-header-type') != '3'){
				var scrollTop = $(window).scrollTop();
				
				var offsetTop = (se.vars.mobile) ? se.vars.helperHeight : se.vars.offsetTop;

				if(scrollTop > offsetTop){

					se.el.$LogoAndMenuHolder.addClass("sticky");

					se.el.$Sticky.css("min-height", se.vars.helperHeight);


					if(se.vars.mobile){
						if(scrollTop < se.vars.latest){
							se.el.$LogoAndMenuHolder.addClass("u-animate").css({
								"position":"fixed",
								"top":0
							});
							se.el.$LogoAndMenuHolder.addClass("fadeInDown");		
							se.vars.latestTop = scrollTop;
						}else{
								se.el.$LogoAndMenuHolder.css({
									"position":"absolute",
									"top": se.vars.latestTop
								});

								if(se.vars.latest - se.vars.latestTop > 100){
									se.el.$LogoAndMenuHolder.removeClass("u-animate fadeInDown");
								}
						}
						se.el.$Sticky.css("min-height", se.vars.helperHeight);
						se.vars.latest = scrollTop;
						
					}	



				}else{
					if(se.vars.mobile){
						se.el.$LogoAndMenuHolder.removeClass("sticky");
						se.el.$LogoAndMenuHolder.removeClass("u-animate fadeInDown fadeOutUp");
						se.el.$LogoAndMenuHolder.attr("style","");
					}else{
						se.el.$LogoAndMenuHolder.removeClass("sticky");
					}

					se.el.$Sticky.css("min-height","0");
					se.vars.latest = 0;
					se.vars.latestTop = 0;
				}
			}else if($('.SeConfig').attr('data-sticky') == '1' && $('.SeConfig').attr('data-header-type') == '3' ){
				var scrollTop = $(window).scrollTop();
				se.el.$LogoAndMenuHolder.find(".estructura").attr("top",scrollTop);
			}
			se.animate();
		},
		abrirMenu : function(e){
			e.preventDefault();
			if(se.vars.menuAbierto == false){
				se.vars.menuAbierto = true;
				se.el.$Mask.addClass('fadeIn');
				se.el.$Site.addClass('is-open');
				se.el.$MenuMobile.addClass('is-open');
			}else{
				se.cerrarMenu();
			}
		},
		cerrarMenu : function(){
			if(se.vars.menuAbierto == true){
				se.vars.menuAbierto = false;
				se.el.$Mask.addClass('fadeOut');
				se.el.$Site.removeClass('is-open');
				se.el.$Mask.removeClass('is-open');
				se.el.$MenuMobile.removeClass('is-open');
				setTimeout(
				  function() 
				  {
				    se.el.$Mask.removeClass('fadeIn fadeOut ');
				  }, 1000);
			}
		},
		desplegarMobile: function(){		
				$(this).toggleClass('fa-caret-up');
				$(this).parent().find('.Menu').slideToggle();
			},
		animate: function(){
			if(!window.edicionOnLine){
				se.el.$animates.each(function(i, el) {
				  var elem = $(el);
				  if(!elem.hasClass("is-animated")){
				      if (elem.visible()) {
				        elem.addClass("is-animated");
				        setTimeout(function(){ 
				            elem.addClass(elem.attr("data-animate"));
				        }, 500);  
				      } 
					  
				  }
				});
			}
		},
		resizeMenu: function(){
				var altura = se.el.$Site.height();
			  se.el.$MenuMobile.css("min-height",altura);
			  if($(window).innerWidth() > 1023){
			  	se.cerrarMenu();
			  	se.vars.mobile = false;
			  }else{
			  	se.vars.mobile = true;
			  }
		},
		resizeTienda : function(){
			var $tienda = se.widgets.$tienda;
		    if($tienda.is('[data-cod_plantilla="0"]')){
		        var $figure = $tienda.find('figure'),
		            $precio = $tienda.find('.Loop-precio'),
		            $compra = $tienda.find('.Producto-compra');

		        var bottomHeight = $precio.outerHeight(true) + $compra.outerHeight();
		        $precio.css('bottom', $compra.outerHeight());
		        $figure.css("padding-bottom",bottomHeight);
		        
		    }
		},
		ready: function(){
			se.animate();
		},
		init : function(){
			$(window).on('resize', se.onResize);
			$(window).on('scroll', se.onScroll);
			$(document).ready(function(){
				se.ready();
			});
			se.onResize();
			se.el.$RsMenuButton.on('click', se.abrirMenu);
			se.el.$Mask.on('click', se.cerrarMenu);
			se.el.$Close.on('click', se.cerrarMenu);
			se.el.$MenuMobile.find('li').has('ul').addClass('is-desplegable');
			$('.is-desplegable').append('<i class="MenuMobile-desplegarIcon fa fa-caret-down"></i>');
			$('.MenuMobile-desplegarIcon').on('click', se.desplegarMobile);
		}
	}
	se.init();
})(jQuery);





