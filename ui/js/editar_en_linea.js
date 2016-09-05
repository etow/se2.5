var id=1;

var x=false;


/**

DRAG AND DROP

*/

$(".dragueable").draggable({
    helper: 'clone',
    cursor: "move",
    cursorAt: { top: 2, left: 2 },
    opacity: 0.7,
    start: function( event, ui ){
        ui.helper.html("").css("background","yellow");    
        $("body").addClass("ui-state-active");
        if (!$(ui.helper).attr("data-drag_on_root"))
        {
            $("body").addClass("not_drag_on_root");
        }
    },
    stop: function( event, ui ){
        $("body").removeClass("ui-state-active");
        $("body").removeClass("not_drag_on_root");
    }
    //drag: function (event,ui) { alert("q tul") }
});

        
$(".__editar_en_linea .dropeable").droppable({
        greedy:true,
		accept: ".dragueable",                                
        activeClass: "ui-state-active",
        hoverClass: "ui-state-hover", 
        tolerance: "touch",                               
        
		drop: function(ev, ui) {
              soltarWidgetArrastrado(ui, this);
              
            
		}
        
	});

/**

FIN DRAG AND DROP

*/








var instanciaEditor;

window.onbeforeunload = function () {
    //alert("salir:" + salir);
    if (salir)
    {
        
    }else{
        return 'Verifica haber guardado los cambios antes de salir';
    }
        
};
        

function iniciar()
{
    
    //alert('ontouchstart' in window || navigator.maxTouchPoints);
    
   
    
    //agrego id a los elementos de widget que no los tenga    
    var cntIdTmp=1;
    $(".WidgetContenido").each(function(){
        if ($(this).attr("id"))
        {
            var id=$(this).attr("id");
                    
        }else{
            var id;
            var ciclar=true;
            while (ciclar)
            {
                id="IDTMP-WidgetContenido-"+cntIdTmp;
                if ($("#"+id).length)
                {
                    cntIdTmp=cntIdTmp+1;
                }else{
                    ciclar=false;   
                }                        
            }
            $(this).attr("id",id);
        }
        if ($(this).data("id_dom"))
        {
            
        }else{
            $(this).data("id_dom",id);
        }
    });
    
    
                
    agregarClaseAElementosPadresVacios();
   
   
    
    $("#mostrarWidget").click(function(){
        if ($("#contenidoWidgets").is(":visible"))
        {                
            $("#contenidoWidgets").hide(500);
        }else{
            $("#contenidoWidgets").show(500);                    
        }
    });
    
    

    $(".__editar_en_linea").each(function(){       
       var nombre_zona=$(this).attr("id").replace("__editar_en_linea_","").replace("_"," ").toUpperCase();                
       $(this).before("<a class='borrarContenidoTotal elemento_a_eliminar' id='borrarElemento_"+ $(this).attr("id") +"'>Borrar Todo el Contenido ( "+nombre_zona+" )<span></span></a>"); 
    });
    

    agregarControlSorteableElementosRaiz();    
     
     
         
                
}//function iniciar()        
        



















$(document).ready(function(){
    

    iniciar();

    



    $("#guardar_contenido_widget").click(guardarContenidos);

    
    
    
    //*********** >>>defino las acciones para los botones<<<< **************************//
                
    
    
    /*
    
    BORRAR TODDO EL CONTENIDO
    
    */            
                
    function borrarTodoElContenido(id_a_borrar){
        borrarWidget($("#"+id_a_borrar),false);
        $("#"+id_a_borrar).append("<div class='dropeable elemento_a_eliminar'></div>");
        resetearDropeables();  
    }
    
    $(document).on("click", ".borrarContenidoTotal", function(){            
        var id_a_borrar=$(this).attr("id").replace("borrarElemento_","");
  /*      
        if (confirm("Estas seguro que deseas borrar todo el contenido?"))
        {
                 borrarTodoElContenido(id_a_borrar);                                                    
        }
*/      var callback = function(){
            return borrarTodoElContenido(id_a_borrar);
        }
        var text = '\u00bfEst\u00e1s seguro que deseas eliminar todo el contenido?';
        showMessage('confirm', text, callback);
        
                                        
    });
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    //$(".__editar_en_linea").sortable({handle: ".handle"});
    
    
    /*
    
    BORRAR WIDGET SIN CONFIRMACION
    
    */            
    
    
    $(document).on("click", "span.borrar", function(){
        //alert($(this).parent().parent().attr("id"));
        borrarWidget($(this).parent().parent(),true);
    });
    
    
    

    
    
    
    
   
    
    /*
    
    
    BORRAR WIDGET CON CONFIRMACION
    
    */            
    
    function borrarWidgetConfirmacion(that){
        console.log(that);
        borrarWidget($(that).parent().parent(),true); 
    }

    $(document).on("click", "span.borrarConfirm", function(){
        
        /*if (confirm("Si borras este elemento no podr\u00e1s editarlo en un futuro.\n\rEstas seguro que deseas eliminarlo?"))
        {
            borrarWidget($(this).parent().parent(),true);                                    
        }*/

        var that = this;

        var callback = function(){
            return borrarWidgetConfirmacion(that);
        }
        var text = "Si eliminas este elemento no podr\u00e1s editarlo en un futuro.\n\rEst\u00e1s seguro que deseas eliminarlo?";
        showMessage('confirm', text, callback);

        //$(this).parent().parent().remove();
    });            
    

    
    
    
    
    
    
    
    
    
    
    
    /*
    
    
    EDITAR WIDGET
    
    */
    

    $(document).on("click", "span.editar", function(){                          

        var data = $(this).parent().parent().data();
        var datos="?id_pagina="+id_pagina;
        var anchoMaximo=false;
        
        for(var i in data)
        {
            if (typeof data[i]!=='object')
            {
                datos=datos+"&"+i+"="+encodeURIComponent(data[i]);
                //alert(i+"...."+data[i]);
                if ((i=="id_widget")&&(data[i]==4))
                {
                    anchoMaximo=true;
                }
            }
        } 
        
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',            
            "beforeClose": function(){
                 idCapaAgregada= $('iframe.fancybox-iframe').contents().find('.WidgetContenido').attr("id");
                 x = $('iframe.fancybox-iframe').contents().find('.Div_Widget_Contenido');
                 
                                       

                 if ($(x).html())
                 {               
                    x=$(x).html();
                 }else   
                    x=false;
                
                                       
            },
            "beforeLoad":function(){
                this.href = base_url+'/widget/index.php'+datos;    
                if (anchoMaximo)
                {  
                    this.width = '100%';
                }             
            },

            "afterClose": function(){
             //alert("the value of input#banner_width1 is : \n"+x); // optional
                    
                 if (x)
                 {
                    //alert($("#"+idCapaAgregada).html()+"\n\r_______________________________________\n\r"+idCapaAgregada+"\n\r_______________________________________\n\r"+x);
                    
                    
                     $("#"+idCapaAgregada).replaceWith(x);
                                                    
                     
                     $("#"+idCapaAgregada+" > div > div.acciones_edicion_widget").remove();
                     
                     
                     $(".__editar_en_linea").find('[contenteditable=true]').each(function(){
                                                        
                        var id_tmp=$(this).attr("id");
                        
                        if (id_tmp)
                        {
                        }else{    
                            var cicla=true;
                            while (cicla){                        
                                id_tmp="ID-elementoTextoEditable-"+id;
                                if ($("#"+id_tmp).length){
                                    //sigue ciclando
                                }else{
                                    cicla=false;
                                }
                                id=id+1;
                            }
                            //alert("contenido_editable - "+ id_tmp + "\n\r"+ $(this).html());
                            $(this).attr("id",id_tmp);

                        }
                        
                        
                        var b=CKEDITOR.dom.element.get(document.getElementById(id_tmp));
                                                        
                        if (b.getEditor())
                        {
                        }else{
                            CKEDITOR.inline(document.getElementById(id_tmp));
                        }                        
                                                
                     });
                     
                    if ($("#"+idCapaAgregada).length>0)
                    { 
                        $('body,html').animate({
                        			//realizamos la animacion hacia el ancla
                        			scrollTop: $("#"+idCapaAgregada).offset().top
                        		},1000);
                    }
                    
                    //elimino todos los droppable y los vuelvo a crear asi considera los nuevos elementos
                    //$(".dropeable").droppable( "destroy" );
                    resetearDropeables();                                                     
                  
                    
                 }//if (x)

            }                        
    	});                 
        
    });
    
    
    
    
    
    
    
    
    /*
    
    
    EDITAR WIDGET X AJAX 
    
    */            
    
    
    $(document).on("click", "span.editarAjax", function(){                          

        var data = $(this).parent().parent().data();
        var datos="?accion=editar&id_pagina="+id_pagina+"&id_dom="+$(this).parent().parent().attr("id");
        
        
        
        for(var i in data)
        {
            if (typeof data[i]!=='object')
            {
                datos=datos+"&"+i+"="+encodeURIComponent(data[i]);
                //alert(i+"...."+data[i]);
                if ((i=="clase_controladora")&&(data[i]=="Widget_ContenidoHTML"))
                {
                    //alert("kljlkjkl");
                }
            }
        } 
        
    
        
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/widget/index.php'+datos;                    
            }
              
    	});                 
        
    });//end editarAjax            
    
    



    
    /*
                
    EDITAR WIDGET SLIDE
    
    */
    

    $(document).on("click", "span.editarSlide", function(){                          

        var data = $(this).parent().parent().data();
        var datos="?id_pagina="+id_pagina;
        
        for(var i in data)
        {
            if (typeof data[i]!=='object')
            {
                datos=datos+"&"+i+"="+encodeURIComponent(data[i]);
                //alert(i+"...."+data[i]);
                if ((i=="clase_controladora")&&(data[i]=="Widget_ContenidoHTML"))
                {
                    //alert("kljlkjkl");
                }
            }
        } 
        
        var procesados=new Array();
        //alert($(this).parent().parent().find(">.contenidoGrilla").html());
        var contenido=$(this).parent().parent().find(".owl-carousel").html();
        $(this).parent().parent().find(".owl-carousel .itemSlideSE").each(function(){
            

            //alert("bool: "+procesados[$(this).data("nro_slider")]);

            //alert($(this).attr("id"));
            if (procesados[$(this).attr("id")])
            {
                //no hago nada;
            }else{
                procesados[$(this).attr("id")]=1;
                //datos=datos+"&contenidoSlide["+$(this).data("nro_slider")+"]="+encodeURIComponent($(this).html());
                datos=datos+"&contenidoSlide["+$(this).attr("id")+"]="+$(this).data("nombre_slide");                                        
                
            }        
        });

        
        //return true;         
        
        $.fancybox({
            padding : 0,
            //'width' : '90%',
	        'type' : 'iframe',
            "beforeClose": function(){
                 idCapaAgregada= $('iframe.fancybox-iframe').contents().find('.WidgetContenido').attr("id");
                 x = $('iframe.fancybox-iframe').contents().find('.Div_Widget_Contenido');
                 
                 
                                              

                 if ($(x).html())
                 {               
                    //alert("HTML WIDGET:"+$(x).html());
                    //x=$(x).html().replace(/{#contenidoPrevio#}/g, contenido);;
                    x=$(x).html();                            
                 }else   
                    x=false;
                
                                       
            },
            "beforeLoad":function(){  
                this.href = base_url+'/widget/index.php'+datos;                    
            },

            "afterClose": function(){
             //alert("the value of input#banner_width1 is : \n"+x); // optional
                    
                 if (x)
                 {
                    //alert($("#"+idCapaAgregada).html()+"\n\r_______________________________________\n\r"+idCapaAgregada+"\n\r_______________________________________\n\r"+x);
                    
                     $("#"+idCapaAgregada).replaceWith(x);
                                                    
                     salir=false;             

                     $("#"+idCapaAgregada+" > div > div.acciones_edicion_widget").remove();
                     
                     
                     $(".__editar_en_linea").find('[contenteditable=true]').each(function(){
                                                        
                        var id_tmp=$(this).attr("id");
                        
                        if (id_tmp)
                        {
                        }else{    
                            var cicla=true;
                            while (cicla){                        
                                id_tmp="ID-elementoTextoEditable-"+id;
                                if ($("#"+id_tmp).length){
                                    //sigue ciclando
                                }else{
                                    cicla=false;
                                }
                                id=id+1;
                            }
                            //alert("contenido_editable - "+ id_tmp + "\n\r"+ $(this).html());
                            $(this).attr("id",id_tmp);

                        }
                        
                        
                        var b=CKEDITOR.dom.element.get(document.getElementById(id_tmp));
                                                        
                        if (b.getEditor())
                        {
                            //no hago nada pq existe
                            //alert(id_txt + " - no hago nada\n\r");
                        }else{
                            //alert(id_txt + " - agrego editor\n\r" + $(this).html());
                            CKEDITOR.inline(document.getElementById(id_tmp));
                        }                        
                                                
                     });
                     
                    if ($("#"+idCapaAgregada).length>0)
                    { 
                        $('body,html').animate({
                        			//realizamos la animacion hacia el ancla
                        			scrollTop: $("#"+idCapaAgregada).offset().top
                        		},1000);
                    }


                    resetearDropeables();                            
                    
                 }//if (x)

            }                        
    	});                 
        
    });            
    
    

    
        
    
    /*
                
    EDITAR DATOS DEL SITIO EN LINEA
    
    */
                    
    
    $(".__editar_en_linea_dato_sitio").click(function(){
        
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/9/ui/ajax/sitios.php';                    
            },
            "afterClose": function(){
                //alert("actualizo la info guardada");
            }
              
    	});             
        
        return false;   
        
    })
    
    
    
    $(".__editar_en_linea_dato_pagina").click(function(){
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/9/ui/ajax/pagina.php?id='+id_pagina;                    
            },
            "afterClose": function(){
                //alert("actualizo la info guardada");
            }
              
    	});             
        
        return false;   
        
    })   
    
    
    $(".__editar_en_linea_paginas").click(function(){
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/9/paginasOrden.php';                    
            },
            "afterClose": function(){
                //alert("actualizo la info guardada");
            }
              
    	});             
        
        return false;   
        
    })        


    $("#paletaCustom").click(function(){
        
        var datos;
        if (SeColores[SePaletaActual])
        {
            for(var i in SeColores[SePaletaActual].hex)
            {            
                datos=datos+"&"+i+"="+encodeURIComponent(SeColores[SePaletaActual].hex[i]);
            } 
        }                
        
        
        //console.log(SeColores[SePaletaActual]);
        //console.log(SeColores[SePaletaActual].hex);
        //console.log(SeColores[SePaletaActual].hex.primario);        
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/9/ui/ajax/custom_colors.php?'+datos;                    
            },
            "afterClose": function(){
				ActualizarPaletaActual();
                //alert("actualizo la info guardada");
            }
              
    	});             
        
        return false;   
        
    })



    $("#definirBackGround").click(function(){
        
        
        $.fancybox({
            padding : 0,
	        'type' : 'iframe',
            "beforeLoad":function(){  
                this.href = base_url+'/9/ui/ajax/background.php';                    
            },
            "afterClose": function(){
				//ActualizarPaletaActual();
                //alert("actualizo la info guardada");
            }
              
    	});             
        
        return false;   
        
    })
    
    
    
    
    
});  



        
        
        
        
        
        
        
        
        
        
        
        












/******************************************************* FUNCIONES *******************************************/        



function agregarControlSorteableElementosRaiz()
{ 
    $(".__editar_en_linea > .WidgetContenido").each(function(){
        
        //alert("sorteable");
        
        $(this).find(" > .Controls-wrapper").each(function(){
            
            if ($(this).html().search("Controls--mover handle")==-1)
            {
                $(this).append("<span class='Controls-item Controls--mover handle'><i class='Controls-icon se-icon-move'></i><span class='Controls-text'>Mover</span></span>");
            }
        });
        
        
        
        //$(this).find(" > .Controls-wrapper").append("<span class='Controls-item Controls--mover handle'><i class='Controls-icon se-icon-move'></i><span class='Controls-text'>Mover</span></span>");
       //alert($(this).attr("id") +"-"+$(this).text().length+"////HTML:"+$(this).text()+":FIN HTML");

    });
    
    $(".__editar_en_linea").sortable({
            handle: ".handle",
            deactivate: function( event, ui ) {agregarDrageablesIntermedios();}
    }); 
}


function eliminarDrageablesVecinosInmediatos()
{
    var cnt=1;
    $(".__editar_en_linea .dropeable").each(function(){
                
        cnt=cnt+1;        
        if ($(this).next().attr("class"))
        {
            if ($(this).next().attr("class").search("dropeable")>-1)
            {
                $(this).remove();
            }
        }
        
    }); 
       
}




function agregarDrageablesIntermedios()
{
    //alert("ejecuto agregarDrageablesIntermedios");
    $(".__editar_en_linea > .WidgetContenido").each(function(){

        if ($(this).next().attr("class"))
        {
            if ($(this).next().attr("class").search("dropeable")>-1)
            {
                //no agrego el intermedio
            }else{
                $("<div class='dropeable'></div>").insertAfter($(this));
            }
        }        
    });   
    eliminarDrageablesVecinosInmediatos();       
    resetearDropeables();    
}






function guardarContenidos(){

     var rta=true;
     huboError=false;
     
     $(".ui-state-hover").removeClass("ui-state-hover");
     
     $("#cargando").show();
     $(".__editar_en_linea").each(function(){
        var campo_a_editar=$(this).attr("id").replace("__editar_en_linea_","");
        
        //alert(campo_a_editar);
                                                   
        
        $.ajax({
            type: 'POST',
            url: base_url+"/9/ui/ajax/utlidades_paginas.php",
            data:{tipo:"editar_campo", id_pagina:id_pagina, campo:campo_a_editar, valor:$(this).html(), cod_tipo_pagina:$(".SeConfig").attr("data-tipo_pagina")},
            success:function(data){
                  //alert(data.estado); 
                  if (data.estado==200)
                  {
                        //alert(data.mensajes);
                        //todo ok
                  }else{
                        huboError=true;
                        rta=false;
                        //$(nestedSortableThis).nestedSortable('cancel');
                        alert("ERROR: "+data.mensajes);
                  }
                  //alert(data.mensajes);
            },
            error:function(data){
                huboError=true;
            },
            dataType: "json",
            async:false});  
                                            
     });

    
    /*guardo la configuracion del template*/          
    var dataSeConfig = $(".SeConfig").data();
    
    $.post(base_url+"/9/ui/ajax/utlidades_paginas.php",
        {tipo:"editar_template", 
         template_header_type:$(".SeConfig").attr("data-header-type"),
         template_header_color:$(".SeConfig").attr("data-header-color"),
         template_accent:$(".SeConfig").attr("data-accent"),
         template_offcanvas:$(".SeConfig").attr("data-offcanvas"),
         template_submenu_color:$(".SeConfig").attr("data-submenu-color"),
         template_luma:$(".SeConfig").attr("data-luma"),
         template_menu_type:$(".SeConfig").attr("data-menu-type"),
         template_sticky:$(".SeConfig").attr("data-sticky"),
         header_transparent:$(".SeConfig").attr("data-header-transparent"),
         header_luma:$(".SeConfig").attr("data-header-luma"),
         template_color:$("#PaletaDeColor").attr("data-color"),
         template_font_title:$("#SeFontsTitlesLink").attr("data-font"),
         template_font_body:$("#SeFontsBodyLink").attr("data-font"),
         template_font_logo:$("#SeFontsLogoLink").attr("data-font"),
         template_font_menu:$("#SeFontsMenuLink").attr("data-font"),
         template_font_button:$("#SeFontsButtonLink").attr("data-font")
        },
        function(data){
              //alert(data.estado); 
              if (data.estado==200)
              {
                    //alert(data.mensaje);
                    //todo ok
              }else{
                    huboError=true;
                    rta=false;
                    //$(nestedSortableThis).nestedSortable('cancel');
                    alert("ERROR : "+data.mensaje);
              }
        }, 
        "json");      

     
     if (huboError==false)
     {
        //alert("Se guardaron los datos correctamenete");
        showMessage("success", "Se guardaron los datos correctamenete")
        salir=true;
        
     }       
     $("#cargando").hide(500);
     
     return rta;

}        
var MessageTimer; 

function showMessage(tipo,msg,callback,id){

    $('.MessageBar-txt').text(msg);

    switch(tipo){
        case "success":
            $('#MessageBar').attr("class","label-success");
            $('.MessageBar-action').empty();
            MessageTimer = setTimeout(function(){
                closeMessage();
            }, 2000);
        break;
        case "warning":
            $('#MessageBar').attr("class","label-warning hide-close-button");
            $('.MessageBar-action').html('<button onclick="closeMessage()">OK</button>');
        break;
        case "error":
            $('#MessageBar').attr("class","label-danger");
            $('.MessageBar-action').html('<button onclick="closeMessage()">OK</button>');
        break;
        case "confirm":
            $('#MessageBar').attr("class","label-danger hide-close-button");
            var button = document.createElement('button');
            var text = document.createTextNode("Si, eliminar");
            button.appendChild(text);
            button.addEventListener('click',function(){
                callback();
                closeMessage();
            });
            $('.MessageBar-action').html(button).append("<button onclick='closeMessage()'>No</button>");
            clearMessageTimer();
        break;
    }

    $('#MessageBar').slideDown();

}
function clearMessageTimer(){
    if(typeof MessageTimer == 'number'){
        clearTimeout(MessageTimer);
    }
}
function closeMessage(){
    $('#MessageBar').slideUp();
}

function resetearDropeables(){
    //elimino todos los droppable y los vuelvo a crear asi considera los nuevos elementos
    //$(".__editar_en_linea .dropeable, .__editar_en_linea.dropeable").droppable( "destroy" );     

/*
                        
    $(".__editar_en_linea .dropeable, .__editar_en_linea.dropeable").droppable({
            greedy:true,
			accept: ".dragueable",                                
            activeClass: "ui-state-active",
            hoverClass: "ui-state-hover",
            tolerance: "touch",                                
            
			drop: function(ev, ui) {

                  soltarWidgetArrastrado(ui, this); 
                
			}
            
    });            

*/

    $(".__editar_en_linea .dropeable, .__editar_en_linea.dropeable").each(function(){

        //alert("INSTANCE: "+$(this).droppable( "instance" ));
        if ($(this).droppable( "instance" ))
        {
            //no agrego el drop pq ya existe
        }else{

            $(this).droppable({
                greedy:true,
    			accept: ".dragueable",                                
                activeClass: "ui-state-active",
                hoverClass: "ui-state-hover",
                tolerance: "touch",                                
                
    			drop: function(ev, ui) {
    
                      soltarWidgetArrastrado(ui, this); 
    
    
                    
    			}
                
    		});
        
        }
            
    });


}        





function finalizarEdicion(guardar){
    salir=true;
    if (guardar)
    {
        if (guardarContenidos())
        {
            document.location.href = base_url+'/9/paginas.php';        
        }
    }else{
        document.location.href = base_url+'/9/paginas.php';                       
    }                                          
}



        
var flagArrastre=false;

function soltarWidgetArrastrado(ui, destino){
    
    if (flagArrastre)
    {
        //alert ("drop desactivado");
        return true;
    }else{
        flagArrastre=true;
    }
    
    //alert("suelto el widget");
    
    var data = (ui.draggable).data();
    var datos="?id_pagina="+id_pagina;
    var anchoMaximo=false;
    for(var i in data)
    {
        if (typeof data[i]!=='object')
        {
            if (i!="drag_on_root")
            {
                datos=datos+"&"+i+"="+data[i];
            }
        }

    }   
    
    if (data["id_widget"]==4)
        anchoMaximo=true;
    
    if ($(destino).parent().attr("class").search("__editar_en_linea")>-1)
    {
        if (!data["drag_on_root"])
        {
            showMessage("warning","El elemento seleccionado solo puede ser arrastrado en las \u00e1reas coloreadas en verde");
            flagArrastre = false;  
            return true;
        }
        
    }
    
    
    //var destino=this;
    
    
    $.fancybox({
        'type' : 'iframe',
        padding : 0,
        "onCancel": function(){ flagArrastre=false; }, 
        "beforeClose": function(){
            
         flagArrastre = false;  
         idCapaAgregada= $('iframe.fancybox-iframe').contents().find('.WidgetContenido').attr("id");
         //alert(idCapaAgregada + "----" + id);

            
         x = $('iframe.fancybox-iframe').contents().find('.Div_Widget_Contenido');

         
         //si el elemnto que agrego , no lo agregue en un elemento con clase __editar_en_linea (que son los elementos que permite arrastrar capas) le borro la opcion para arrastrar
         if ($(destino).attr("class").search("__editar_en_linea")==-1)
         {
             $(x).find(".handle").each(function(){
                $(this).remove();  
             });
         }
         

         if ($(x).html())
         {        
            $(x).prepend("<div class='dropeable'></div>");
            $(x).append("<div class='dropeable'></div>");
            //x=$(x).html().replace(/{#contenidoPrevio#}/g, "");;
            x=$(x).html();
         }else   
            x=false;
            
                                   
        },
        "beforeLoad":function(){  
            this.href = base_url+'/widget/index.php'+datos;                    
            if (anchoMaximo)
            {  
                this.width = '100%';
            }             

        },

        "afterClose": function(){
         //alert("the value of input#banner_width1 is : "+x); // optional
         
         if (x)
         {
            
             //alert("id donde agrego : " + $(destino).attr("id") + " class donde agrego : " + $(destino).attr("class") + " html donde agrego : " + $(destino).html());
             
             /*
             $(destino).find(".arrastrarAqui").each(function(){
                $(this).remove();
             });
             */
                          
             salir=false;             
             
             $(destino).replaceWith(x);
             
             agregarControlSorteableElementosRaiz();
             eliminarDrageablesVecinosInmediatos();
             

             $(".__editar_en_linea").find('[contenteditable=true]').each(function(){

                var id_tmp=$(this).attr("id");
                if (id_tmp)
                {
                }else{

                    var cicla=true;
                    while (cicla){                        
                        id_tmp="ID-elementoTextoEditable-"+id;
                        if ($("#"+id_tmp).length){
                            //sigue ciclando
                        }else{
                            cicla=false;
                        }
                        id=id+1;
                    }
                    //alert("contenido_editable - "+ id_tmp + "\n\r"+ $(this).html());
                    $(this).attr("id",id_tmp);

                }
                

                //alert($(this).html() + "\n\r ****** 1 - " + id_tmp + "b:" + b);
                
                var b=CKEDITOR.dom.element.get(document.getElementById(id_tmp));
                
                if (b.getEditor())
                {
                    //no hago nada pq existe
                    //alert(id_txt + " - no hago nada\n\r");
                }else{
                    //alert(id_txt + " - agrego editor\n\r" + $(this).html());
                    CKEDITOR.inline(document.getElementById(id_tmp));
                }                        
                                        
             });
             
            if ($("#"+idCapaAgregada).length>0)
            { 
                $('body,html').animate({
                			//realizamos la animacion hacia el ancla
                			scrollTop: $("#"+idCapaAgregada).offset().top
                		},1000);
                        
            }   
            
            //elimino todos los droppable y los vuelvo a crear asi considera los nuevos elementos
            //$(".dropeable").droppable( "destroy" );                         
            agregarClaseAElementosPadresVacios();
            resetearDropeables();                                       
             //alert("this2:"+this);
         }//if (x)

        }                        
	});             
}//end dropear




function agregarClaseAElementosPadresVacios()
{
    //busco todos los widget dentro del que quiero borrar que requieran ser borrados
    $(".__editar_en_linea").each(function(){
        
        if ($(this).find(".WidgetContenido").length ==0)
            $(this).addClass("is-empty");
        else
            $(this).removeClass("is-empty");
        
            
    });
}


function borrarWidget(obj,eliminarObjeto)
{
    
    var data;
    var hay_error=false; 
     
    //busco todos los widget dentro del que quiero borrar que requieran ser borrados
    $(obj).find(".WidgetABorrar").each(function(){

        var datos="accion=borrar&id_pagina="+id_pagina;
        data = $(this).data();        
        
        for(var i in data)
        {
            if (typeof data[i]!=='object')
                datos=datos+"&"+i+"="+data[i];
            
        }           
        /*
        $.post(base_url+"/widget/index.php",datos,function(data){
            //alert(data.codigo);
            if ((data.codigo==200) || (data.codigo==201))
            {
                //todo ok
            }else{
                hay_error=true;
                alert(data.mensaje);
                
            }
        },"json");     */
        

        $.ajax({url: base_url+"/widget/index.php",
          type: 'POST',
          async: false,
          data: datos,
          success: function(data){
                //alert(data.codigo);
                if ((data.codigo==200) || (data.codigo==201))
                {
                    //todo ok
                }else{
                    hay_error=true;
                    alert(data.mensaje);
                    
                }
            },
          dataType: "json"
           
        });           
    });
    
    
    if (hay_error)
    {
        //alert("huboooo error");
        return true;
    }
    
    
    var ejecutarBorrarWidget=false;

    //borro el objeto principal
    var datos="accion=borrar&id_pagina="+id_pagina;
    data = $(obj).data();
    
    for(var i in data)
    {
        if (typeof data[i]!=='object')
            datos=datos+"&"+i+"="+data[i];
            
        if (i=="id_widget")
            ejecutarBorrarWidget=true;    
    }            

       
    if (ejecutarBorrarWidget)
    {        
        $.post(base_url+"/widget/index.php",datos,function(data){
    
            if ((data.codigo==200) || (data.codigo==201))
            {    
            }else
                alert("No pudo borrarse el Widget");
        },"json");
    }else{
        //suele pasar por aca cuando borro todo el contenido
    }
    
    if (eliminarObjeto){
        //$(obj).remove();
        //$(obj).html("<div class='dropeable'></div>");
        $(obj).replaceWith("<div class='dropeable'></div>");
        resetearDropeables();
           
    }else{
        $(obj).html("");            
    }
    eliminarDrageablesVecinosInmediatos();  
    agregarClaseAElementosPadresVacios();  
            
}//function borrarWidget(obj)




        
                
        
        
        
        
        
        

          