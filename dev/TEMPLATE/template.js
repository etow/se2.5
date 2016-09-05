



/*Visible con porcentaje*/

$.fn.visible = function(type, m){ 

    if(type == null || typeof y == 'undefined') type = "auto";
    
    
    var y = 0;
    var win = $(window); 
    var wHeight = win.height()
    var viewport = {
        top : win.scrollTop()
    }; 

    viewport.bottom = viewport.top + wHeight; 

    var height = this.outerHeight();
    if(!height){
        return false;
    } 
    var bounds = this.offset();
    bounds.bottom = bounds.top + height;
    var visible = (!(viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    if(!visible){
        return false;   
    } 
    var deltas = {
        top : Math.min( 1, ( bounds.bottom - viewport.top ) / height),
        bottom : Math.min(1, ( viewport.bottom - bounds.top ) / height)
    };


    if(type == "auto"){

    	if(height > (wHeight)){
    		//console.log("es mayor al 100%");
    		y = 0.05;
    	}else if(height > (wHeight * .8 )){
    		//console.log("es mayor al 80%");
    		y = 0.1;
    	}else if(height > (wHeight / 2)){
	    	//console.log("es mayor al 50%");
	    	y = 0.4;
    	}else{
    		//console.log("es menor al 50%");
    		y = 0.7;
    	}
    }    

    return (deltas.top * deltas.bottom) >= y; 
};



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
			sticky: $('.SeConfig').attr('data-sticky'),
			headerTransparent: $('.SeConfig').attr('data-header-transparent'),
			stickyHeight: 0,
			headerType: $('.SeConfig').attr('data-header-type'), 
			latest: 0,
			latestTop:0,
			mobile: false,
			scrollDirection: true,
			scrollTop: 0
		},
		onResize:function(){
			setTimeout(
			  function() 
			  {
			  	se.vars.offsetTop = parseInt($('.LogoAndMenu').offset().top);
			  	se.vars.helperHeight = $('.LogoAndMenu').height();
			  	se.resizeMenu();
			  	se.setParallaxesSize();
			  	se.parallax();
			  	se.resizeTienda();
			  }, 200);
		},
		onScroll: function(f){
			se.vars.scrollTop = $(window).scrollTop();

			if(se.vars.scrollTop > se.vars.latest){
				se.vars.scrollDirection = true;
			}else{
				se.vars.scrollDirection = false;
			}
			
			$.each(f, function(i,e){
				e();
			});

			se.vars.latest = se.vars.scrollTop;
			se.vars.latestTop = se.vars.scrollTop;	

		},
		setParallaxesImg: function(){
			if(!window.edicionOnLine){
				$.each($('[data-parallax="1"]'),function(){
					var $el = $(this);
					var url = $el.attr('data-imagen_url');
					var height = $el.outerHeight() + ($el.outerHeight()*.3) ;
					var imgEl = '<div class="parallax-img" style="background-image:url('+url+'); height:'+height+'px;">';
					$el.prepend(imgEl);
					$el.find('.parallax-img').css("height",height);
				});
			}
		},
		setParallaxesSize:function(){
			if(!window.edicionOnLine){
				$.each($('[data-parallax="1"]'),function(){
					var $el = $(this);
					var height = $el.outerHeight() + ($el.outerHeight()*.3) ;
					$el.find('.parallax-img').css("height",height);
				});
			}
		},
		parallax: function (){
				var defase;
				$.each($('.parallax-img'),function(i,e){

						var scrollTop = $(window).scrollTop();
						var parent = $(this).parent();
						var offsetTop = parent.offset().top;
						defase = 2;

						var scrollDefase = (scrollTop/defase) - (offsetTop/defase);

						var translateY = "translateY(calc(0% + "+ (scrollDefase/2) + "px))";
						
						if($(this).parent().visible("parallax")){
							
							$(this).css({
								"-webkit-transform":translateY,
								"-moz-transform":translateY,
								"transform":translateY
							});
						}
				});
		},
		sticky:function(){
				if(se.vars.mobile){
					se.stickyMobile();
				}else{
					if(se.vars.headerType < 3){
						if(se.vars.scrollDirection && se.vars.scrollTop > (se.vars.offsetTop + se.vars.helperHeight)){
							se.el.$Sticky.css("min-height", se.vars.helperHeight);
							if(!se.checkHeaderMenu){
								se.vars.stickyHeight = 55;
								se.el.$LogoAndMenu.height(se.vars.stickyHeight);
							}
							se.el.$LogoAndMenuHolder.addClass("sticky").addClass("u-animate");
							se.el.$LogoAndMenuHolder.addClass("fadeInDown");
							se.vars.latestTop = se.vars.scrollTop;	
						}else if(!se.vars.scrollDirection && se.vars.scrollTop < (se.vars.offsetTop + se.vars.helperHeight - se.vars.stickyHeight)){
							se.el.$LogoAndMenu.height('auto');
							se.el.$Sticky.css("min-height", 0);
							se.el.$LogoAndMenuHolder.removeClass("sticky").removeClass("u-animate").removeClass("fadeInDown");
						}
					}else if(se.vars.headerType == 3){
						//if(se.vars.scrollDirection /*&& se.vars.scrollTop > (se.vars.offsetTop*/)){
							se.el.$LogoAndMenuHolder.addClass("sticky");
						//}else if(!se.vars.scrollDirection && se.vars.scrollTop < 1){
							//se.el.$LogoAndMenuHolder.removeClass("sticky");
						//}
					}

				}
		},
		stickyMobile: function(){
			if(se.vars.scrollDirection && se.vars.scrollTop > (se.vars.offsetTop)){
				if(!se.el.$LogoAndMenuHolder.hasClass('sticky-mobile')){
					se.el.$Sticky.css("height", 55);
					se.el.$LogoAndMenu.css("height", 55);
					$(se.el.$LogoAndMenu).find('span').css("height", 45);
				}
				se.el.$LogoAndMenuHolder.addClass('sticky-mobile');
			}else if(!se.vars.scrollDirection && se.vars.scrollTop < (se.vars.offsetTop + 1)){
				
				if(se.el.$LogoAndMenuHolder.hasClass('sticky-mobile')){
					se.el.$Sticky.animate({
						"height": 0
					},200, function(){
						se.el.$LogoAndMenuHolder.removeClass('sticky-mobile');
					});
					se.el.$LogoAndMenu.css("height", "");
					$(se.el.$LogoAndMenu).find('span').css("height", "");
				}
			}
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
				        //setTimeout(function(){ 
				            elem.addClass(elem.attr("data-animate"));
				        //}, 500);  
				      }
				  }
				});
			}
		},
		animateInSlider: function(event){
			if(!window.edicionOnLine){
				var element   = event.target;
				var elem = $(element).find('.owl-item').eq(event.item.index);
				var animates = elem.find('.u-animate');

				var siblings = elem.siblings('.owl-item');

				animates.each(function(i, el){
					var elem = $(el);
					elem.addClass("is-animated");
					elem.addClass(elem.attr("data-animate"));
				});
				setTimeout(function(){
					siblings.each(function(i,el){
						var animates = $(el).find('.is-animated');
						animates.each(function(){
							$(this).removeClass('is-animated '+$(this).attr("data-animate"));
						});
					});
				}, 300);
			}
		},
		resizeMenu: function(){
				var altura = se.el.$Site.height();
			  se.el.$MenuMobile.css("min-height",altura);
			  if($(window).innerWidth() > 800){
			  	se.cerrarMenu();
			  	se.vars.mobile = false;
			  }else{
			  	if(se.vars.headerType != 3){
			  		se.vars.mobile = true;
			  	}else{
			  		if($(window).width() < 799){
			  			se.vars.mobile = true;
			  		}
			  	}
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
		checkHeaderMenu:function(){
			var items = se.el.$Menu.find('#MenuPrincipal').children();
			var firstOffset = "";
			//console.log("hago check");
			$.each(items, function(i,e){
				var top = $(this).offset().top;
				if(i==0){
					firstOffset = top;
				}
				if(top > firstOffset){
					//console.log("hay doble");
					return true;
				}			
			});
		},
		checkHeaderTransparent: function (){
      console.log("CHEQUEANDO HEADER TRANSPARENT");
      if($('.SeConfig').attr('data-header-transparent') == 1 ){
        var paddingTop = $('.LogoAndMenu').height() / 2 ;
        var WidgetContenido = $($('.Section--contenido').find('.WidgetContenido')[0]);
        if($($('.ZonasEditables')[0]).children().length > 1){
          //console.log("HAY CONTENIDO EN LA ZONA 1");
        }
        var _id = WidgetContenido.attr('id');

        if($('.SeConfig').attr('data-header-type') != 3){
          /*SI EL WIDGET ES SLIDER*/
          if(WidgetContenido.hasClass('Widget_Slider')){
            /*SLIDER DE IMAGEN*/
            if(WidgetContenido.hasClass('sliderTipoImagen')){
              var imgs = $(WidgetContenido.find('.HoverImg-img'));
              $.each(imgs, function(){
                $(this).css("visibility", "hidden");
                var img = "url("+ $(this).attr("src") + ")";
                if(!grid.attr('data-first-grid')){
                  $(this).parent().attr("data-first-grid","true");
                  $(this).parent().css({
                    "background-image":img,
                    "background-size":"cover",
                    "padding-top":paddingTop+"px"
                  });
                }
              });
            }else if(WidgetContenido.hasClass('sliderTipoContenido')){
              /*SLIDER DE CONTENIDO*/
              var grid = $(WidgetContenido.find('.owl-item .itemSlideSE > .Widget_Grilla > .grilla'));
              var gridTop = parseInt(grid.css('padding-top'));
              if(!grid.attr('data-first-grid')){
                grid.attr("data-first-grid","true");
                grid.css('padding-top', (paddingTop + gridTop) + "px");
              }
            }else{
              /*SLIDER GRILLA*/
              var grid = $(WidgetContenido.find('.grilla')[0]);
              var gridTop = parseInt(grid.css('padding-top'));
              if(!grid.attr('data-first-grid')){
                grid.attr("data-first-grid","true");
                grid.css('padding-top', (paddingTop + gridTop) + "px");
              }
            }
          }else if(WidgetContenido.hasClass('Widget_Grilla')){
            /*NO ES SLIDER, ES UNA GRILLA COMUN*/
            var grid = $(WidgetContenido.find('.grilla')[0]);
            var gridTop = parseInt(grid.css('padding-top'));
            if(!grid.attr('data-first-grid')){
              grid.attr("data-first-grid","true");
              grid.css('padding-top', (paddingTop + gridTop) + "px");
            }
          }
        }
      }else{
        /*ANULO HEADER TRANSPARENTE*/
        var grid = $('[data-first-grid="true"]');
        $.each(grid, function(){
          var paddingTop = $(this).attr('data-padding_top');
          $(this).css('padding-top',paddingTop+"px");
          $(this).removeAttr('data-first-grid');
        });
       
      }
    },
		init : function(){
			var edicionOnLine = window.edicionOnLine;

			/*Chequers*/
			se.setParallaxesImg();
			se.checkHeaderMenu();
			se.checkHeaderTransparent();

			/*FUNCIONES EN SCROLL*/
			$(window).on('resize', se.onResize);	
			var functions = [se.animate];

			if(se.vars.sticky == '1' && !edicionOnLine){
					se.sticky();
					functions.push(se.sticky);

			}
			if($('[data-parallax="1"]').length && !edicionOnLine){
				functions.push(se.parallax);
			}	
			$(window).on('scroll', function(){
				se.onScroll(functions);
			});


			/*Ready*/
			$(document).ready(function(){
				se.ready();
			});

			/*Resize*/
			se.onResize();


			/*BINDINGS*/
			se.el.$RsMenuButton.on('click', se.abrirMenu);
			se.el.$Mask.on('click', se.cerrarMenu);
			se.el.$Close.on('click', se.cerrarMenu);
			se.el.$MenuMobile.find('li').has('ul').addClass('is-desplegable');
			$('.is-desplegable').append('<i class="MenuMobile-desplegarIcon fa fa-caret-down"></i>');
			$('.MenuMobile-desplegarIcon').on('click', se.desplegarMobile);



			/*Animate on slider*/
			var checkSlider = function(event){
							se.animateInSlider(event);
			};

			_SE.prototype.animate = checkSlider;


			
		}
		
	}
	se.init();
})(jQuery);





