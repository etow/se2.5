var tour = {
  id: "primeros-pasos",
  i18n:{
    nextBtn: "Siguiente",
    prevBtn: "Anterior",
    doneBtn: "Entendido!"
  },
  steps: [
    {
      title: "Agrega elementos a tu Sitio",
      content: "Selecciona el elemento que deseas agregar y arrastralo a la p\u00e1gina.",
      target: document.querySelector("#tour-pp-1"),
      placement: "right",
      xOffset: '-20px',
      onNext: function() {
        $('.tour-h').removeClass('tour-h');
        var next = ".h-pp-"+ (hopscotch.getCurrStepNum()+1);
        $(next).addClass('tour-h');
      }
    },
    {
      title: "Define tu est\u00e9tica",
      content: "Elige una paleta de color que te identifique, selecciona la tipograf\u00eda y muchas opciones m\u00e1s.",
      target: document.querySelector("#tour-pp-2"),
      placement: "right",
      onNext: function() {
        $('.tour-h').removeClass('tour-h');
        var next = ".h-pp-"+ (hopscotch.getCurrStepNum()+1);
        //$(next).addClass('tour-b');
        $('.TopBar-action .TopBarMenu-link').addClass('tour-h')
      }
    },
    {
      title: "Guarda y publica",
      content: "Guarda tus cambios, haz una vista previa y cuando tu sitio este listo, publ\u00edcalo.",
      target: document.querySelector("#tour-pp-3"),
      placement: "bottom",
      xOffset: '-20px',
      arrowOffset: 'center',
    }
    
  ],
  onStart: function() {
    $('.Elemento').addClass('tour-h');
    $('.SiteMask').addClass('fadeIn');
    $('.TopBar-action').append('<div class="disabler"></div>');
  },
  onEnd: function () {
    tour.onClose();
    localStorage.setItem('tour_pp_1', true);
  },
  onClose: function() {
    $('.tour-h').removeClass('tour-h');
    $('.SiteMask').removeClass('fadeIn');
    $('.disabler').remove();
  }
};

//a = \u00e1xOffset
//e = \u00e9
//i = \u00ed
//o = \u00f3
//u = \u00fa

// Start the tour!
var tour_pp_1 = localStorage.getItem('tour_pp_1');
if(!tour_pp_1){
  hopscotch.startTour(tour);
}