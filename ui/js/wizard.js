var Wizard = {
	el : {
		$indicador : $('.Wizard-bread-indicador'),
		$current : $('#Paso-1'),
		$catPopup : $('.Categorias-popup'),
		$catClose : $('.Categorias-popup-close'),
		$catItem : $('.Categorias-item'),
		$catOpen : $('.SelectorCategorias'),
		$catActual : $('#CategoriaActual'),
		$catContainer:$('.Categorias'),
		$templatesContainer:$('.Wizard-templates')
	},
	listo : false,
	tmpScroll : false,
	goto : function (target) {
		index = target.index();
		$.each($('.Magico'), function(i,el){
			$(el).children('li').removeClass('is-active').eq(index).addClass("is-active");
		})
		$('body').attr('data-paso', index + 1);
		//Wizard.moverIndicador(target);
	},
	nav : function (e,target){
		var $this = $(this);
		var $target = $(target);
		var goTarget = "";
		if($this.hasClass("is-active") || $target.hasClass("is-active") ){
			return;
		}else{
			

			if(target){
				$goTarget = $target;
			}else{
				$goTarget = $this
			}
			if(!target){
				if(!$('.Template.is-active').length){
					Wizard.error("Debes elegir una plantilla para continuar",false);
					return false;
				}
			}

			if($goTarget.attr('id') == "Paso-3"){
				if(!Wizard.isFilled){
					Wizard.error("Debes completar los campos para continuar",false);
					return false;
				}
			}
			$goTarget.addClass('is-active').removeClass('is-completed');
			//Wizard.responsive();/*ELIO*/
			$goTarget.prevAll().addClass('is-completed').removeClass('is-active');
			$goTarget.nextAll().removeClass('is-completed').removeClass('is-active');
			Wizard.goto($goTarget);
		}
	},/*
	moverIndicador: function (target){
		Wizard.current = target;
		var width = target.width() - 20;
		var position = target.position();
		var left = position.left - 5;
		var top = position.top - 7;
		Wizard.el.$indicador.css({
			"left": left,
			"width":width,
			"top": top
		});

	},*/
	error: function(msg,navto,question){

		$('.form-msg').find('.err').html(msg);

		$('.form-msg').addClass("is-active");

		setTimeout(function(){
		  $('.form-msg').addClass("fadeOut");
		  setTimeout(function(){
			  $('.form-msg').removeClass("fadeOut is-active");
			}, 500);
		}, 3000);
		if(navto){
			Wizard.nav("",navto);
		}
		if(question){
			$('.questions').children('li').removeClass('current').eq(index).addClass("current");
		}
	},
	publicar: function () {
		
    	Wizard.listo = true;
    	$("#theForm").submit();

	},
	catOpen: function () {
		Wizard.el.$catPopup.fadeIn('200');
	},
	catClose : function (){
		Wizard.el.$catPopup.fadeOut('200');
	},
	selectCat: function () {
		$(this).siblings().removeClass('is-active');
		$(this).addClass('is-active');
		Wizard.el.$catActual.text($(this).text());
		Wizard.catClose();
	},
	responsive: function () {
		//console.log("hago responsive");
		var wh = $(window).height(),
		sections = $('.Wizard-sections'),
		nav = $('.Wizard-nav'),
		title = $('.Wizard-title.is-active'),
		navoff = nav.offset().top,
		navheight = nav.height();
		titleoff = title.offset().top,
		titleheight = title.height();
		var navtotal = navoff + navheight;
		var total = navtotal + titleheight;
		var offset = 20;
		//$('#size').css({'opacity':'.5', 'width':'100%','height':total});
		sections.css({'height': wh - total - offset, 'top': total + offset});
	},
	init : function () {

		$('.navTo').click(Wizard.nav);

		$('.btn-submit').click(function(e){
			e.preventDefault();
			Wizard.publicar();
		});

		//Wizard.moverIndicador(Wizard.el.$current);

		// Cargo los templates
		$('.Categorias-item').click(Wizard.selectCat);
		
		$('.Categorias-item[for="rubro_0"]').trigger("click");

		/*
        if (gotoPretet)
        {
            Wizard.goto(gotoPretet);
        }else{
            Wizard.goto("#Paso-1");            
        }*/
         
    Wizard.el.$catOpen.click(Wizard.catOpen);
    Wizard.el.$catClose.click(Wizard.catClose);
    $(Wizard.el.$catContainer).mCustomScrollbar({
      theme:"minimal",
      scrollbarPosition: "outside",
      mouseWheel:{ scrollAmount: 200 }
    });
    Wizard.responsive();

    var timer = "";
    window.onresize = function(){
			  clearTimeout(timer);
			  timer = setTimeout(Wizard.responsive, 100);
		};


    
   
	},
	validate: {
		vacio: function(text){
			if(text === '')
				return true;
			else
				return false;
		},
		mail: function(mail){
			//var pattern = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
			var pattern = /^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/i;
			return pattern.test(mail);
		},
		text: function(text){
			var pattern = /[A-Za-z0-9_-]+/g;
			return pattern.test(text);
		},
		domain: function(domain){
			var pattern = /[A-Za-z0-9_-]*(\.\w{2,8})+$/i;
			return pattern.test(domain);
		},
		exist: function(text){
			/*HACER AJAX PARA VERIFICAR QUE NO EXISTE EL NOMBRE*/
		}
	}
}


Wizard.init();



/*$(window).resize(function(){
	Wizard.moverIndicador(Wizard.el.$current);
});*/

function seleccionarRubro (id_rubro) {
  $.post("ui/ajax/implementaciones_template.php",{id_rubro:id_rubro},function(data){
      //data += data + data + data + data + data + data + data;
      $("#implementaciones").html(data);

      $('.Template').click(function () {
      	$('.Template.is-active').removeClass('is-active');
      	$(this).addClass('is-active');
      	$('#Paso-2').trigger('click');
      });

      $('.Template').on( "mouseenter", function(e) {
      	$(this).addClass('is-on');
      	if(Wizard.tmpScroll == true){
      		$(this).removeClass('is-on');
      	}
      });

      $('.Template').on( "mouseleave", function(e) {
      	$(this).removeClass('is-on');
      });
      
      loadTemplates();/*
      setTimeout(function(){
      	alert("hola");
      }, 1000);*/

      $('.Wizard-templates').mCustomScrollbar({
      	theme:"minimal",
      	scrollbarPosition: "outside",
      	mouseWheel:{ scrollAmount: 200 },
      	callbacks:{
      	      onScrollStart: function(){
      	      	Wizard.el.$templatesContainer.addClass('scrolling');
      	      },
      	      onScroll: function(){
      	      	Wizard.el.$templatesContainer.removeClass('scrolling');
      	      }
      	}
      });

      $('.personaliza').mCustomScrollbar({
      	theme:"minimal",
      	scrollbarPosition: "outside",
      	mouseWheel:{ scrollAmount: 200 }
      });
  });
}
