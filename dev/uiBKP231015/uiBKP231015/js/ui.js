var Se = {
    vars : {
      direction : "down",
      index : "",
      oldIndex : "1",
      lastPage : "",
      topBarHeight : $('.TopBar').height()
    },
    $el : {
      menu : {
        item : $('.ConfigMenu-item'),
        spot : $('.ConfigMenu-spot'),
      },
      option : $('[data-option]'),
      nav : $('[data-nav]'),
      goBack : $('.GoBack'),
      checkbox: $('.ConfigCheckbox')
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
          var offsetTop = $el.offset().top;
          var index = $el.index();
          Se.vars.direction = index > Se.vars.oldIndex ? "down" : "up";
          Se.$el.menu.spot.css('top',offsetTop - Se.vars.topBarHeight);
          Se.vars.oldIndex = index;
          Se.navToSection(target);
        }

        if(role == "pager"){
          Se.navToPage(target);
        }

      });

      Se.$el.goBack.click(Se.goBack);

      Se.$el.checkbox.click(function(e){
        e.preventDefault();
        var $el = $(this);
        $el.siblings().removeClass('is-active');
        $el.addClass('is-active');
        var action = $el.attr('data-action');
        var option = $el.attr('data-var');
        if(action == 'paleta'){

        }
        if(action == 'accent'){
          $('.SeConfig').attr('data-a-color',option);
          console.log(option);
        }
      });
    },
    select: function () {},
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
    goBack: function () {     
      $('.ConfigSection.is-active .ConfigPage.is-active').removeClass("is-active");
        Se.vars.lastPage.addClass('is-active');
    },
  }
  Se.init();