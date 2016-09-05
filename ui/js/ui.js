var Se = {
    vars : {
      direction : "down",
      index : "",
      oldIndex : "1",
      lastPage : "",
      topBarHeight : $('.TopBar').height(),
      scrollTop:""
    },
    $el : {
      menu : {
        item : $('.ConfigMenu-item'),
        spot : $('.ConfigMenu-spot'),
      },
      option : $('[data-option]'),
      nav : $('[data-nav]'),
      goBack : $('.GoBack'),
      checkbox: $('.ConfigCheckbox'),
      dropdownButton: $('.ConfigDropdown-button')
    },
    init : function () {
      Se.vars.lastPage = $('.ConfigSection.is-active .ConfigPage.is-active');
      Se.$el.nav.click(function(){
        var $el = $(this),
        target = $('#' + $el.attr('data-nav')),
        role = $el.attr('data-role'),
        type = $el.attr('data-type');
                  
        $el.siblings().removeClass('is-active');
        $el.addClass('is-active');

        if(role == "menu"){
          var offsetLeft = $el.offset().left;
          var index = $el.index();
          Se.vars.direction = index > Se.vars.oldIndex ? "down" : "up";
          Se.$el.menu.spot.css('left',offsetLeft /*- $('.Config').offset().top*/);
          Se.vars.oldIndex = index;
          Se.navToSection(target);
        }

        if(role == "pager"){
          Se.navToPage(target);
        }

      });

      Se.$el.goBack.click(function(e){
        target = $(this).attr('data-to');
        Se.goBack(target);
      });

      Se.$el.checkbox.click(function(e){
        e.preventDefault();
        var $el = $(this);
        $el.siblings().removeClass('is-active');
        if($el.hasClass('SwitchCheckbox')){
          $el.toggleClass('is-active');
        }else{
          $el.addClass('is-active');
        }
        
        var action = $el.attr('data-action');
        var option = $el.attr('data-var');
        if(action == 'paleta'){
          var src = $('#PaletaDeColor').attr('href');
          var href = src.split('colores/');
         
          var extension = ".css";
          if(option=="custom"){
            extension = ".php";
          }
          $('#PaletaDeColor').attr('href',href[0]+'colores/'+option+extension).attr('data-color',option);
          ActualizarPaletaActual();
        }
        if(action == 'accent'){
          $('.SeConfig').attr('data-accent',option);
          $('.ConfigOptions').attr('data-accent',option);
        }
        if(action == 'submenuColor'){
          $('.SeConfig').attr('data-submenu-color',option);
        }
        if(action == 'headerColor'){
          $('.SeConfig').attr('data-header-color',option);
        }
        if(action == 'offcanvas'){
          $('.SeConfig').attr('data-offcanvas',option);
        }
        if(action == 'luma'){
          $('.SeConfig').attr('data-luma',option);
        }
        if(action == 'headerType'){
          $('.SeConfig').attr('data-header-type',option);
          Se.checkHeaderTransparent();
        }
        if(action == 'menuType'){
          $('.SeConfig').attr('data-menu-type',option);
        }
        if(action == 'sticky'){
          var dataSticky = $('.SeConfig').attr('data-sticky');
          if(dataSticky == 1){
            $('.SeConfig').attr('data-sticky','0');
          }else{
            $('.SeConfig').attr('data-sticky','1');
          }
        }
        if(action == 'headerTransparent'){
          var transparent = $('.SeConfig').attr('data-header-transparent');

          if(transparent == 1){
            $('.SeConfig').attr('data-header-transparent','0');
            $('.header-luma-controls').hide();

          }else{
            $('.SeConfig').attr('data-header-transparent','1');
            if($('.SeConfig').attr('data-header-luma') == ""){
              $('.SeConfig').attr('data-header-luma','oscuro');
            }
            $('.header-luma-controls').show();
          }
          Se.checkHeaderTransparent();
        }
        if(action == 'headerLuma'){
          $('.SeConfig').attr('data-header-luma',option);
        }
      });
      Se.$el.dropdownButton.click(function(e){
        var target = $(this).attr("data-target");
        //$('.ConfigDropdown-list').css({'display':'none', 'height': '0px'});
        Se.toggleDropdown(target);       
      });

    },
    navToSection: function(target){
      if(Se.vars.direction == "down"){
        $('[data-role="section"].is-active').attr("data-dir","up");
      }else{
        $('[data-role="section"].is-active').attr("data-dir","down");
      }

      $('[data-role="section"].is-active').removeClass('is-active');
      target.addClass('is-active');
    },
    navToPage: function (target) {
      $('[data-role="page"].is-active').removeClass('is-active');
      target.addClass('is-active');
    },
    goBack: function (target) {     
      $('.ConfigSection.is-active .ConfigPage.is-active').removeClass("is-active");
      if(!target){
        Se.vars.lastPage.addClass('is-active');
      }else{
        $('#'+target).addClass('is-active');
      }
    },
    toggleDropdown: function (target) {
      if(!$(target).hasClass('is-open')){
        $('.ConfigDropdown-list.is-open').hide().removeClass('is-open');
        $(target).slideToggle(100, function() {
          $(this).toggleClass("is-open");
        });
      }else{
        $(target).slideToggle(100, function() {
          $(this).toggleClass("is-open");
        });
      }
    },
    showAlerts : function(){

      var headerTransparent = function(){
        if($('.SeConfig').attr('data-header-transparent') == 1 ){
          $('.alert-cabecera-transparente').show();
        }else{
          $('.alert-cabecera-transparente').hide();
        }
      };

      var alerts = {
        headerTransparent : headerTransparent
      }

      return alerts;
    },
    checkHeaderTransparent: function (){
      //console.log("CHEQUEANDO HEADER TRANSPARENT");
      if($('.SeConfig').attr('data-header-transparent') == 1 ){

        showAlerts().headerTransparent();
        //console.log("EL HEADER ES TRANSPARENTE");

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
              /*PENDIENTE, REVISAR CUANDO CAMBIA DE CABECERA EN CABECERA CON INVISIBLE TRUE*/
              if(!grid.attr('data-first-grid')){
                grid.attr("data-first-grid","true");
                
              }
              if(sinpad){
                gridTop = 0;

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
        showAlerts().headerTransparent();
        var grid = $('[data-first-grid="true"]');
        $.each(grid, function(){
          var paddingTop = $(this).attr('data-padding_top');
          $(this).css('padding-top',paddingTop+"px");
          $(this).removeAttr('data-first-grid');
        });
       
      }
    }

  }
  Se.init();

  $(document).ready(function(){

        //window.dispatchEvent(new Event('resize'));
        $(function(){
            $('body').on('mouseenter', '.WidgetContenido', function(e){
                var $el = $(this);
                var timer = setTimeout(function(){
                  $el.addClass('is-hover');
                },  150);
                $(this).data('myTimer', timer);
            }).on('mouseleave', '.WidgetContenido', function(e){
                clearTimeout($(this).data('myTimer'));
                $(this).removeClass("is-hover");
            });
        })
        $('.borrarContenidoTotal').on('mouseenter', function(e){
          $(this).parent().addClass('is-on-borrar');
        }).on('mouseleave', function(e){
          $(this).parent().removeClass('is-on-borrar');
        });

        $('.ConfigPage').mCustomScrollbar({
          theme:"minimal",
          scrollbarPosition: "outside",
          mouseWheel:{ scrollAmount: 200 }
        });       
        if($('.subMenu').length){
          $('.display-on-submenu').css("display","block");
        }
        
        ActualizarAspecto();
        ActualizarFuentesActuales();
        SeFontLoader.getJson();
        Se.showAlerts().headerTransparent();
  });

  function ActualizarPaletaActual(){
    SePaletaActual = $('#PaletaDeColor').attr("data-color");
    //var url = base_url + "/9/recursos/colores/" + SePaletaActual +".png";
    if(SePaletaActual == "custom"){
      var img = '<img src="'+base_url+'/9/recursos/colores/color_custom.png">';
      $('.PaletaActual').html(img);
    }else{
      var pa = SeColores[SePaletaActual];
      var Matiz = '<span class="Matiz" style="background:'+pa.hex.acento1+';"></span><span class="Matiz" style="background:'+pa.hex.acento2+';"></span><span class="Matiz" style="background:'+pa.hex.callTo+';"></span>';
      $('.PaletaActual').html(Matiz);
    }
  }

  function ActualizarFuentesActuales(){
    var titulos = $(SeFontLoader.linkTagTitles).attr('data-font');
    var body = $(SeFontLoader.linkTagBody).attr('data-font');
    var logo = $(SeFontLoader.linkTagLogo).attr('data-font');
    var menu = $(SeFontLoader.linkTagMenu).attr('data-font');
    var deco = $(SeFontLoader.linkTagDeco).attr('data-font');

    $(SeFontLoader.currentBodyFont).attr('class','ico_'+body);
    $(SeFontLoader.currentTitleFont).attr('class','ico_'+titulos);
    $(SeFontLoader.currentLogoFont).attr('class','ico_'+logo);
    $(SeFontLoader.currentMenuFont).attr('class','ico_'+menu);
    $(SeFontLoader.currentDecoFont).attr('class','ico_'+deco);
  }

  /*
Chequear Clases de SeConfig
Modificar cases de aspecto.
  */

  function ActualizarAspecto () {
    var SeConfig = $('.SeConfig').data();
    $.each(SeConfig, function( index, value ) {
      if(index == "sticky" || index == "headerTransparent"){
        //SON SWITCHES
        if(value == 1){
           $('[data-action="'+index+'"]').addClass('is-active');
           if(index == "headerTransparent"){
            $('.header-luma-controls').show();
           }
        }else{
          $('[data-action="'+index+'"]').removeClass('is-active');
        }
      }else{
        //SON CHECKBOXES
         $('[data-action="'+index+'"][data-var="'+value+'"]').addClass('is-active').siblings().removeClass("is-active");
      }
      
    });

    ActualizarPaletaActual();
  }


var SeFontLoader = {
    google : "https://fonts.googleapis.com/css?family=",
    styleTagTitles: '#SeFontsTitlesStyle',
    styleTagBody: '#SeFontsBodyStyle',
    styleTagLogo: '#SeFontsLogoStyle',
    styleTagMenu: '#SeFontsMenuStyle',
    styleTagButton: '#SeFontsButtonStyle',
    styleTagDeco: '#SeFontsDecoStyle',

    linkTagTitles : '#SeFontsTitlesLink',
    linkTagBody : '#SeFontsBodyLink',
    linkTagLogo : '#SeFontsLogoLink',
    linkTagMenu : '#SeFontsMenuLink',
    linkTagButton : '#SeFontsButtonLink',
    linkTagDeco : '#SeFontsDecoLink',

    currentTitleFont: '#CurrentTitleFont',
    currentBodyFont: '#CurrentBodyFont',
    currentLogoFont: '#CurrentLogoFont',
    currentMenuFont: '#CurrentMenuFont',
    currentButtonFont: '#CurrentButtonFont',
    currentDecoFont: '#CurrentDecoFont',

    getJson : function(){
      $.getJSON(base_url+"/9/ui/fuentes.json", callbackFuncWithData);
      function callbackFuncWithData(data)
      {
        SeFontLoader.fontFamilies = data;
        ListarFuentes();
      }
    },
    /*fontFamilies : function () {
      var test; 
      $.getJSON(base_url+"/9/ui/fuentes.json", function(json) {
        test = json;
      });

    },*//*
    fontFamilies : function (){
      var pepe = "hola"
      $.getJSON(base_url+"/9/ui/fuentes.json", callbackFuncWithData);
      function callbackFuncWithData(data)
      {
        pepe = data;
      }
      return pepe;
    },*/
    loadFont: function (font, elemento) {
      //var fonts = font.split(',');
      //console.log(fonts);
      var output = font.replace(/_/g,'+') + ":" + SeFontLoader.fontFamilies[font][0] + "|";
      var css = SeFontLoader.fontFamilies[font][1];
      //$.each(fonts,function(i,e){
      //  output = output + font + ":" + SeFontLoader.fontFamilies[font][0] + "|";
        //css = css + SeFontLoader.fontFamilies[font][1];
      //});

      
/*
      if(elemento == "body"){
        $(SeFontLoader.linkTagBody).attr({'href': SeFontLoader.google + output, 'data-font':font} );
        SeFontLoader.createStyleBody(css, '.SeFrame');
      }else{
        $(SeFontLoader.linkTagTitles).attr({'href': SeFontLoader.google + output, 'data-font':font});
        SeFontLoader.createStyleTitles(css, '.SeFrame h1, .SeFrame h2, .SeFrame h3, .SeFrame h4, .SeFrame h5, .SeFrame h6');
      }*/


      switch(elemento){
        case "body":
          $(SeFontLoader.linkTagBody).attr({'href': SeFontLoader.google + output, 'data-font':font} );
          SeFontLoader.renderStyle(css, '.SeFrame', "styleTagBody");
        break;
        case "titles":
          $(SeFontLoader.linkTagTitles).attr({'href': SeFontLoader.google + output, 'data-font':font});
          var elemento = '.SeFrame h1, .SeFrame h2, .SeFrame h3, .SeFrame h4, .SeFrame h5, .SeFrame h6';
          SeFontLoader.renderStyle(css, elemento, "styleTagTitles");
        break;
        case "logo":
          $(SeFontLoader.linkTagLogo).attr({'href': SeFontLoader.google + output, 'data-font':font});
          SeFontLoader.renderStyle(css, '.MainLogo', "styleTagLogo");
        break;
        case "menu":
          $(SeFontLoader.linkTagMenu).attr({'href': SeFontLoader.google + output, 'data-font':font});
          SeFontLoader.renderStyle(css, '.Menu-item', "styleTagMenu");
        break;
        case "button":
          $(SeFontLoader.linkTagButton).attr({'href': SeFontLoader.google + output, 'data-font':font});
          SeFontLoader.renderStyle(css, '.btn, input[type="submit"], button', "styleTagButton");
        break;
        case "deco":
          $(SeFontLoader.linkTagMenu).attr({'href': SeFontLoader.google + output, 'data-font':font});
          SeFontLoader.renderStyle(css, '.DecoFont', "styleTagDeco");
        break;
      }
      
    },
    renderStyle: function(styles, elemento, target){
      var style = elemento + "{" + styles + "}";
      $(SeFontLoader[target]).html(style);
    }
  }

  $('.FontLoader').click(function(e){
    e.preventDefault();
    SeFontLoader.loadFont($(this).attr('data-fontToLoad'));
  });

  function ListarFuentes () {
    var lista = "";
    $.each(SeFontLoader.fontFamilies, function (font,e){ 
      var tmp = '<a href="" class="SeFontItem ico_'+font+'" data-action="fonts" data-var="'+font+'"></a>';
      lista += tmp;
    });

    $(".FontContainer").html(lista);

    $('#SeFontsParaTitulos').on('click', '.SeFontItem', function(e){
      e.preventDefault();
      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font, "titles");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentTitleFont).attr('class','ico_'+font);
    });

    $('#SeFontsParaBody').on('click', '.SeFontItem', function(e){
      e.preventDefault();

      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font,"body");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentBodyFont).attr('class','ico_'+font);
    });

    $('#SeFontsParaLogo').on('click', '.SeFontItem', function(e){
      e.preventDefault();
      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font,"logo");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentLogoFont).attr('class','ico_'+font);
    });

    $('#SeFontsParaMenu').on('click', '.SeFontItem', function(e){
      e.preventDefault();
      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font,"menu");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentMenuFont).attr('class','ico_'+font);
    });

    $('#SeFontsParaButton').on('click', '.SeFontItem', function(e){
      e.preventDefault();
      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font,"button");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentButtonFont).attr('class','ico_'+font);
    });

    $('#SeFontsParaDeco').on('click', '.SeFontItem', function(e){
      e.preventDefault();
      var font = $(this).attr("data-var");
      SeFontLoader.loadFont(font,"deco");
      Se.toggleDropdown($(this).parent().attr('id'));
      $(SeFontLoader.currentDecoFont).attr('class','ico_'+font);
    });

  }

$(document).ready(function(){
  setAlturas();
});

var timerAlturas = "";
$(window).resize(function(){

  if ( timerAlturas ) clearTimeout(timerAlturas);
  timerAlturas = setTimeout(function(){
    setAlturas();
  },  300);

});
  
function setAlturas(){
  var vw = $(window).width();
  var vh = $(window).height();
  $('.Config').height(vh - 40);
  $('.Page').height(vh - 40).width( vw - $('.Config').width());
  $('.ConfigOptions').height(vh - 100);
  $('.ConfigPage').height(vh - 100);
}