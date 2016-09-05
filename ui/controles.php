<?
$cabecera_imagen="<img src='$base_url/9/ui/img/interfaz/editor_logo.png' alt=''>";
if ($_SESSION[id_revendedor_web]>0)
{
    $sql_datos_revendedor = "SELECT desc_revendedor,logo_file,marca_blanca,style_header  FROM z200_revendedores left join z200_personalizaciones on (id_revendedor=cod_revendedor) where id_revendedor='$_SESSION[id_revendedor_web]'";
    //echo $sql;
    $result_datos_revendedor = mysql_query($sql_datos_revendedor,$db);
    $myrow_datos_revendedor = mysql_fetch_array($result_datos_revendedor);
}
if ($_SESSION["id_revendedor_web"] and ($myrow_datos_revendedor["marca_blanca"]))//info
{
    if (!empty($myrow_datos_revendedor[logo_file]))
    {
		$cabecera_imagen="<img border='0' src='http://www.misaplicaciones.com/"."$myrow_datos_revendedor[logo_file]' style='max-height:30px;'>";            
    }else{
        $cabecera_imagen="$myrow_datos_revendedor[desc_revendedor]";
    }
    
    if (!empty($myrow_datos_revendedor["style_header"]))
    {
        $cssRevendedor.=".TopBar{";
        if (strlen($myrow_datos_revendedor["style_header"])==6)
        {
            $cssRevendedor.="background: #".$myrow_datos_revendedor["style_header"]." !important;";
            
        }else{                
            $cssRevendedor.=$myrow_datos_revendedor["style_header"];
        }
        $cssRevendedor.="}";

        
    } 
    echo "<style>$cssRevendedor</style>";   
}
if (!empty($_SESSION[fecha_fin_demo]) and false)
{
?>
<script type="text/javascript">
<!--
	var LiveHelpSettings = {};
	LiveHelpSettings.server = 'www.grupoinfocomercial.com/clientes/modules/';
	LiveHelpSettings.embedded = true;
	LiveHelpSettings.locale = 'es';
	LiveHelpSettings.plugin = 'WHMCS';
	LiveHelpSettings.name = '';
	LiveHelpSettings.custom = '';
	LiveHelpSettings.email = '';
	(function(d, $, undefined) {
		$(window).ready(function() {
			LiveHelpSettings.server = LiveHelpSettings.server.replace(/[a-z][a-z0-9+\-.]*:\/\/|\/livehelp\/*(\/|[a-z0-9\-._~%!$&'()*+,;=:@\/]*(?![a-z0-9\-._~%!$&'()*+,;=:@]))|\/*$/g, '');
			var LiveHelp = document.createElement('script'); LiveHelp.type = 'text/javascript'; LiveHelp.async = true;
			LiveHelp.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + LiveHelpSettings.server + '/livehelp/scripts/jquery.livehelp.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(LiveHelp, s);
		});
	})(document, jQuery);
-->
</script>
<?
}//if (empty($_SESSION[fecha_fin_demo]))
?>




  <link rel="stylesheet" href="<?=$base_url;?>/9/ui/css/ui_ok.css">
  <link rel="stylesheet" href="<?=$base_url;?>/9/ui/css/style.css">
  <link rel="stylesheet" href="<?=$base_url;?>/9/ui/css/animate.css">
  <link rel="stylesheet" href="<?=$base_url;?>/9/ui/css/font.css">
  <link rel="stylesheet" href="<?=$base_url;?>/9/ui/css/scrollbar.css">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>


  <div class="TopBar">
    <div class="TopBar-logo">
      <?=$cabecera_imagen;?>
    </div>
    <div class="TopBar-options">
      <ul class="TopBarMenu TopBarMenu-inline">
        <li class="TopBarMenu-item u-relative has-submenu">
          <a target="" class="TopBarMenu-link" id=""><i class="fa fa-desktop"></i> Mi Sitio Web</a>
          <ul class="TopBarMenu-subMenu TopBarMenu-dialog" style="width:210px;">
            <li class="TopBarMenu-item">
              <a href="<?=$base_url;?>/9/paginas_editar.php" class="TopBarMenu-link"><i class="fa fa-plus"></i> Crear nueva página</a>
            </li>
            <li class="TopBarMenu-item">
              <a href="#" class="TopBarMenu-link __editar_en_linea_paginas"><i class="fa fa-list"></i> Mis Páginas</a>
            </li>
          
            <li class="TopBarMenu-item">
              <a target="" href="#" class="__editar_en_linea_dato_sitio TopBarMenu-link" id=""><i class="fa fa-info-circle"></i> Datos de Mi Sitio</a>
            </li>
            <li class="TopBarMenu-item TopBarMenu-item--divider">
            </li>
            <!--            
            <li class="TopBarMenu-item">
              <a class="TopBarMenu-link"href="<?=$base_url;?>/9/wizard.php?resetear=1" ><i class="fa fa-desktop"></i> Modificar mi diseño</a>
            </li>-->
            <li class="TopBarMenu-item">
              <a target="" href="<?=$base_url;?>/6/articulos.php?tipo=busca" class="TopBarMenu-link" id=""><i class="fa fa-shopping-cart"></i> Mi Tienda</a>
            </li>
            <li class="TopBarMenu-item">
              <a target="" href="<?=$base_url;?>/1/notas.php?tipo=busca" class="TopBarMenu-link" id=""><i class="fa fa fa-newspaper-o"></i> Mi Blog</a>
            </li>
            <li class="TopBarMenu-item">
              <a target="" href="<?=$base_url;?>/5/suscriptores.php?tipo=busca" class="TopBarMenu-link" id=""><i class="fa fa-envelope"></i> Mi Newsletter</a>
            </li>
            <li class="TopBarMenu-item TopBarMenu-item--divider">
            </li>
            <li class="TopBarMenu-item">
              <a target="" href="<?=$base_url;?>/9/configuracion.php" class="TopBarMenu-link" id=""><i class="fa fa-asterisk"></i> Opciones Avanzadas</a>
            </li>
          </ul>
          
        </li>
        <li class="TopBarMenu-item">
        <a target="_blank" href="" class="TopBarMenu-link __editar_en_linea_dato_pagina" id=""><i class="fa fa fa-gear"></i> Configurar esta Página</a>
        </li>        
<?
if (!$myrow_datos_revendedor[marca_blanca])
{
?>
        <li class="TopBarMenu-item u-noborder">
        <a target="_blank" href="<?=$base_url;?>/ayudaonline/redir.php" class="TopBarMenu-link " id=""><i class="fa fa-life-ring"></i> Ayuda</a>
        </li>        
<?
}//if ($myrow_datos_revendedor[marca_blanca])
?>

        
      </ul>
    </div>

    <div class="TopBar-action" data-intro="Guarda tus cambios, haz una vista previa y publica tu sitio cuando este listo!" data-position="bottom" >
      <ul class="TopBarMenu TopBarMenu-inline h-pp-3">
          <li class="TopBarMenu-item u-noborder" id="tour-pp-3">
            <div class="TopBar-action--guardar TopBarMenu-link TopBarMenu-grouped-item" id="guardar_contenido_widget"><i class="fa fa-floppy-o"></i> <span class="hide-on-xsmall">Guardar</span> </div>
            <!--<span class="TopBarMenu-link TopBarMenu-grouped-item TopBarMenu-dropdown"><i class="fa fa-chevron-down"></i>
              <ul>
                
              </ul>
            </span>-->
          </li>
          <li class="TopBarMenu-item u-noborder">
            <a target="_blank" href="<?=$base_url;?>/9/vista_previa_sitio.php?id_pagina=<?=$_GET[id_pagina];?>&tf_bt=<?=md5($_COOKIE["id_empresa_web"]);?>" class="TopBarMenu-link TopBar-action--preview" id=""><i class="fa fa-eye"></i> <span class="hide-on-xsmall">Vista previa</span></a>
          </li>
          <li class="TopBarMenu-item u-noborder">
            <a target="" href="<?=$base_url;?>/9/generar_sitio.php" class="TopBar-action--publicar TopBarMenu-link"><i class="fa fa-cloud-upload"></i> <span class="hide-on-xsmall">Publicar</span></a>
          </li>
          <li class="TopBarMenu-item u-noborder">
            <a target="" href="<?=$base_url;?>/logout.php" class="TopBar-action--salir TopBarMenu-link"><i class="fa fa-power-off"></i></a>
          </li>
          <!--
          <li class="TopBarMenu-item">
            
          </li>-->
      </ul>     
    </div>
    <div id="MessageBar" class="label-success">
      <div class="MessageBar-close" onclick="closeMessage()"><i class="fa fa-close"></i></div>
      <span class="MessageBar-txt">
        
      </span>
      <span class="MessageBar-action">
        
      </span>
    </div>
  </div>
<div class="Config" >
  <ul class="ConfigMenu">
    <span class="ConfigMenu-spot"></span>
    <li class="ConfigMenu-item is-active" data-role="menu" data-nav="OP-elementos">
      <i class="ConfigMenu-icon se-icon-elementos"></i>
      <span class="ConfigMenu-text">
        Elementos
      </span>
    </li>
    <li class="ConfigMenu-item h-pp-2" data-role="menu" data-nav="OP-aspecto" id="tour-pp-2">
      <i class="ConfigMenu-icon se-icon-aspecto"></i>
      <span class="ConfigMenu-text">
        Aspecto
      </span>
    </li>
  </ul>
  
  <div class="ConfigOptions" data-accent="">  
    <div class="ConfigSection is-active" id="OP-elementos" data-role="section">
      <div class="ConfigPage is-active is-main h-pp-1" >
        <div class="ConfigTitle">
          <span>Bloques de Contenido</span>
        </div>
        <div class="ConfigBox" id="tour-pp-1">
          <ul class="ConfigGroup" >
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='3' data-drag_on_root='1'>
                <i class="Elemento-icon icon-mediabox">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-mediabox"></use></svg>
                </i>
                <span class="Elemento-text">Texto combinado</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19' data-cod_layout='11' data-drag_on_root='1'>
                <i class="Elemento-icon icon-footer">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-left-right"></use></svg>
                </i>
                <span class="Elemento-text">Texto<br>alternado</span>
              </a>
            </li> 
            
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='22' data-drag_on_root='1'>
                <i class="Elemento-icon icon-slider">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-slider"></use></svg>
                </i>
                <span class="Elemento-text">Pasador de imágenes</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='7' data-drag_on_root='1'>
                <i class="Elemento-icon icon-clientes">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-clientes"></use></svg>
                </i>
                <span class="Elemento-text">Pasador de logos</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='1' data-drag_on_root='1'>
                <i class="Elemento-icon icon-call">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-call"></use></svg>
                </i>
                <span class="Elemento-text">Frase<br>destacada</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='9' data-drag_on_root='1'>
                <i class="Elemento-icon icon-contacto">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-contacto"></use></svg>
                </i>
                <span class="Elemento-text">Contacto<br>&nbsp;</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='8' data-drag_on_root='1'>
                <i class="Elemento-icon icon-titulo">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-titulo"></use></svg>
                </i>
                <span class="Elemento-text">Titulo<br></span>
              </a>
            </li> 
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='6' data-drag_on_root='1'>
                <i class="Elemento-icon icon-video">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-video"></use></svg>
                </i>
                <span class="Elemento-text">Video</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='25'>
                <i class="Elemento-icon icon-tienda">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-tienda"></use></svg>
                </i>
                <span class="Elemento-text">Productos<br>&nbsp;</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='27'>
                <i class="Elemento-icon icon-news">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-news"></use></svg>
                </i>
                <span class="Elemento-text">Notas<br>del Blog</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='5' data-drag_on_root='1'>
                <i class="Elemento-icon icon-staff">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-staff"></use></svg>
                </i>
                <span class="Elemento-text">Staff<br></span>
              </a>
            </li> 
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19' data-cod_layout='10' data-drag_on_root='1'>
                <i class="Elemento-icon icon-footer">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-footer"></use></svg>
                </i>
                <span class="Elemento-text">Pie de página</span>
              </a>
            </li>  

          </ul>
        </div>
        
        
        <!--
        <div class="ConfigTitle">
          <span>Estructura</span>
        </div>
        <div class="ConfigBox">
          <ul class="ConfigGroup">
                                            
          </ul>
        </div>-->
        
        
        
        <div class="ConfigTitle">
          <span>Elementos individuales</span>
        </div>
        <div class="ConfigBox">
          <ul class="ConfigGroup">
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='18' data-drag_on_root='1'>
                <i class="Elemento-icon icon-columna">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-columna"></use></svg>
                </i>
                <span class="Elemento-text">Estructura de columnas</span>
              </a>
            </li>     
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='20' data-elemento='separador' data-drag_on_root='1'>
                <i class="Elemento-icon icon-separador">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-separador"></use></svg>
                </i>
                <span class="Elemento-text">Separador<br>&nbsp;</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='20' data-elemento='titulo'>
                <i class="Elemento-icon icon-titulo">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-titulo"></use></svg>
                </i>
                <span class="Elemento-text">Título</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='20' data-elemento='texto'>
                <i class="Elemento-icon icon-parrafo">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-parrafo"></use></svg>
                </i>
                <span class="Elemento-text">Párrafo</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='4'>
                <i class="Elemento-icon icon-imagen">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-imagen"></use></svg>
                </i>
                <span class="Elemento-text">Imagen</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='21'>
                <i class="Elemento-icon icon-boton">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-boton"></use></svg>
                </i>
                <span class="Elemento-text">Boton</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='17'>
                <i class="Elemento-icon icon-iconos">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-iconos"></use></svg>
                </i>
                <span class="Elemento-text">Icono</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget="10">
                <i class="Elemento-icon icon-redes">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-redes"></use></svg>
                </i>
                <span class="Elemento-text">Redes Sociales</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='5'> 
                <i class="Elemento-icon icon-formulario">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-formulario"></use></svg>
                </i>
                <span class="Elemento-text">Formulario</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='13'>
                <i class="Elemento-icon icon-newsletter">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-newsletter"></use></svg>
                </i>
                <span class="Elemento-text">Newsletter</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='12'>
                <i class="Elemento-icon icon-video">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-video"></use></svg>
                </i>
                <span class="Elemento-text">Video</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='11'>
                <i class="Elemento-icon icon-mapa">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-mapa"></use></svg>
                </i>
                <span class="Elemento-text">Mapa</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='19'  data-cod_layout='4'>
                <i class="Elemento-icon icon-testimonio">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-testimonio"></use></svg>
                </i>
                <span class="Elemento-text">Testimonio</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='8'>
                <i class="Elemento-icon icon-html">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-html"></use></svg>
                </i>
                <span class="Elemento-text">Código HTML</span>
              </a>
            </li>
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='14'>
                <i class="Elemento-icon icon-buscador-tienda">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-buscador-tienda"></use></svg>
                </i>
                <span class="Elemento-text">Buscador de Productos</span>
              </a>
            </li>      
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='15'>
                <i class="Elemento-icon icon-categorias-tienda">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-categorias-tienda"></use></svg>
                </i>
                <span class="Elemento-text">Categorías<br>de la tienda</span>
              </a>
            </li>      
            <li class="ConfigGroup-item">
              <a class="Elemento dragueable ui-draggable" data-id_widget='24'>
                <i class="Elemento-icon icon-menu">
                  <svg class="svgicon"><use xlink:href="<?=$base_url;?>/9/ui/symbol-defs.svg#icon-menu"></use></svg>
                </i>
                <span class="Elemento-text">Menú<br>personalizado</span>
              </a>
            </li>
            
          </ul>
        </div><!--
        <div class="ConfigTitle">
          <span>Tienda Virtual y Blog</span>
        </div>
        <div class="ConfigBox">
          <ul>
            
            
          </ul>
        </div>-->
        
      </div><!--/ConfigPage-->
    </div><!--/ConfigSection-->
    <div class="ConfigSection" id="OP-aspecto" data-role="section">
      <div class="ConfigPage is-active is-main" >
        <div class="ConfigBox">
          <button class="ConfigButton" data-role="pager" data-nav="OP-color">
            Configurar Color
          </button>
          <button class="ConfigButton" data-role="pager" data-nav="OP-header-type">
            Configurar Cabecera
          </button>
          <button class="ConfigButton" data-role="pager" data-nav="OP-fonts">
            Configurar Fuentes
          </button>
          <button class="ConfigButton" id="definirBackGround">
            Fondo del Sitio
          </button>
        </div>
      </div>
      <div class="ConfigPage" id="OP-color" data-role="page">
        <div class="PageContent">
          <div class="ConfigBox">
            <button class="GoBack">Volver</button>
          </div>
          
          <div class="ConfigBox">
            <div class="ConfigDesc">
              Actuamente tienes selecionada esta paleta de color:
            </div>
            <div class="Config-item PaletaActual">
             
            </div>
            <button class="ConfigButton" data-role="pager" data-nav="OP-paletas">
              Seleccionar otra paleta
            </button>
          </div>
          <div class="ConfigTitle">
            <span>Color de Acento</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox-wrapper" >
              <a href="" class="ConfigCheckbox ColorCheckbox is-active template-accent-1" data-action="accent" data-var="1">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-accent-2" data-action="accent" data-var="2">
              </a>
            </div>
          </div>
          <div class="ConfigTitle">
            <span>Fondo de cabecera</span>
          </div>
          
          <div class="ConfigBox">

            <div class="ConfigCheckbox-wrapper" >
              <a href="" class="ConfigCheckbox ColorCheckbox template-submenu-color-2" data-action="headerColor" data-var="2">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-submenu-color-1" data-action="headerColor" data-var="1">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-accent-1" data-action="headerColor" data-var="3">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-accent-2" data-action="headerColor" data-var="4">
              </a>
            </div>
            <div class="Config-alert alert alert-warning alert-cabecera-transparente">
              Tienes activada la opción Cabecera Transparente, debes desactivarla para poder verestos cambios.
            </div>
          </div>
          <div class="ConfigTitle display-on-submenu">
            <span>Color de Submenu</span>
          </div>
          <div class="ConfigBox display-on-submenu">
            <div class="ConfigCheckbox-wrapper" >
              <a href="" class="ConfigCheckbox ColorCheckbox is-active template-submenu-color-1" data-action="submenuColor" data-var="oscuro">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-accent-1" data-action="submenuColor" data-var="1">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-accent-2" data-action="submenuColor" data-var="2">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-submenu-color-2" data-action="submenuColor" data-var="claro">
              </a>
            </div>
          </div>
          <div class="ConfigTitle">
            <span>Luminosidad</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox-wrapper" >
              <a href="" class="ConfigCheckbox ColorCheckbox is-active template-luma-claro" data-action="luma" data-var="claro">
              </a>
              <a href="" class="ConfigCheckbox ColorCheckbox template-luma-oscuro" data-action="luma" data-var="oscuro">
              </a>
            </div>
          </div>



        </div>
      </div>
      <div class="ConfigPage" id="OP-paletas" data-role="page">
        <div class="PageContent">
          <div class="ConfigBox">
            <button class="GoBack" data-to="OP-color">Volver</button>
          </div>
          <div class="ConfigTitle">
            <span>Crea tu propia <br>paleta de colores</span>
          </div>
          <div class="ConfigBox">
            <button class="ConfigButton" id="paletaCustom">
              Modifica tu paleta aquí
            </button>
          </div>

          <div class="ConfigTitle">
            <span>O selecciona una de <br>nuestras sugerencias</span>
          </div>
          <div class="ConfigTitle">
            <span>Filtrar por color</span>
          </div>
          <div class="ConfigBox">
            <a href="" class="ConfigCheckbox ColorCheckbox template-transparent is-active" data-action="filtrar" data-var=""></a>

            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:Gold;" data-action="filtrar" data-var="amarillo">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:orange;" data-action="filtrar" data-var="naranja">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:FireBrick;" data-action="filtrar" data-var="rojo">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:Sienna;" data-action="filtrar" data-var="marron">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:HotPink;" data-action="filtrar" data-var="rosa">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:DarkSlateBlue;" data-action="filtrar" data-var="violeta">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:DodgerBlue;" data-action="filtrar" data-var="azul">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:LimeGreen;" data-action="filtrar" data-var="verde">
            </a>
            <a href="" class="ConfigCheckbox ColorCheckbox" style="background:DarkGrey;" data-action="filtrar" data-var="gris">
            </a>
            
            


          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox-wrapper ImageCheckboxes options-to-1-col" id="colores">
              
            </div>
          </div>
        </div>
      </div>

      <div class="ConfigPage" id="OP-header-type" data-role="page">
        <div class="PageContent">
          <div class="ConfigBox">
            <button class="GoBack">Volver</button>
          </div>
          <div class="ConfigTitle">
            <span>Tipo de Cabecera</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox-wrapper options-to-1-col" >
              <a href="" class="ConfigCheckbox is-active template-header-type-1" data-action="headerType" data-var="1">
                <img src="<?=$base_url;?>/9/ui/img/interfaz/header_horizontal.png" alt="Horizontal">
              </a>
              <a href="" class="ConfigCheckbox template-header-type-2" data-action="headerType" data-var="2">
                <img src="<?=$base_url;?>/9/ui/img/interfaz/header_vertical.png" alt="Vertical">
              </a>
              <a href="" class="ConfigCheckbox template-header-type-3" data-action="headerType" data-var="3">
                <img src="<?=$base_url;?>/9/ui/img/interfaz/header_lateral.png" alt="Lateral">
              </a>
            </div>
          </div>
          <div class="ConfigTitle">
            <span>Cabecera Fija</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox SwitchCheckbox" data-action="sticky" data-active="">
                  <div class="SwitchCheckbox-toggle"></div>
                  <span class="SwitchCheckbox-text">ON</span>
                  <span class="SwitchCheckbox-text">OFF</span>                                                                              
            </div>
          </div>
          <div class="ConfigTitle">
            <span>Cabecera transparente</span>
          </div>

          <div class="ConfigBox">
            <div class="ConfigCheckbox SwitchCheckbox" data-action="headerTransparent" data-active="">
                  <div class="SwitchCheckbox-toggle"></div>
                  <span class="SwitchCheckbox-text">ON</span>
                  <span class="SwitchCheckbox-text">OFF</span>                                                                              
            </div>
          </div>
          <div class="header-luma-controls" style="display:none;">
            <div class="ConfigTitle">
              <span>Links del menu</span>
            </div>
            <div class="ConfigBox">
              <div class="ConfigCheckbox-wrapper">
                <a href="" class="ConfigCheckbox ColorCheckbox is-active template-luma-claro" data-action="headerLuma" data-var="oscuro">
                </a>
                <a href="" class="ConfigCheckbox ColorCheckbox template-luma-oscuro" data-action="headerLuma" data-var="claro">
                </a>                                                                          
              </div>
            </div>
          </div>
          
          <div class="ConfigTitle">
            <span>Tipo de Menu</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigCheckbox-wrapper MenuCheckboxes options-to-1-col" >
              <a class="Menu-link ConfigCheckbox is-active template-menu-type-1" data-action="menuType" data-var="1"><span><i>Subrayado</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-2" data-action="menuType" data-var="2"><span><i>Línea inferior</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-3" data-action="menuType" data-var="3"><span><i>Línea superior</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-4" data-action="menuType" data-var="4"><span><i>Entre llaves</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-5" data-action="menuType" data-var="5"><span><i>Triángulo izquierda</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-6" data-action="menuType" data-var="6"><span><i>Triángulo superior</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-7" data-action="menuType" data-var="7"><span><i>Triángulo inferior</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-8 template-menu-type--likeButton" data-action="menuType" data-var="8"><span><i>Estilo Flat</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-9 template-menu-type--likeButton" data-action="menuType" data-var="9"><span><i>Bordes curvos</i></span></a>
              <a href="" class="ConfigCheckbox template-menu-type-10 template-menu-type--likeButton" data-action="menuType" data-var="10"><span><i>La vieja escuela</i></span></a>
            </div>
          </div>
        </div>
      </div>

      <div class="ConfigPage" id="OP-fonts" data-role="page">
        <div class="PageContent">
          <div class="ConfigBox">
            <button class="GoBack">Volver</button>
          </div>
          <div class="ConfigTitle">
            <span>Fuente de los Títulos</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaTitulos">
                <div class="ConfigDropdown-current">
                  <div id="CurrentTitleFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow"><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaTitulos" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div><!--ConfigBox-->
          <div class="ConfigTitle">
            <span>Fuente de los textos</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaBody">
                <div class="ConfigDropdown-current">
                  <div id="CurrentBodyFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow" ><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaBody" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div><!--ConfigBox-->

          <div class="ConfigTitle">
            <span>Fuente del logo</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaLogo">
                <div class="ConfigDropdown-current">
                  <div id="CurrentLogoFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow" ><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaLogo" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div><!--ConfigBox-->

          <div class="ConfigTitle">
            <span>Fuente del menú</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaMenu">
                <div class="ConfigDropdown-current">
                  <div id="CurrentMenuFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow" ><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaMenu" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div><!--ConfigBox-->

          <div class="ConfigTitle">
            <span>Fuente de los botones</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaButton">
                <div class="ConfigDropdown-current">
                  <div id="CurrentButtonFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow" ><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaButton" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div><!--ConfigBox-->
          <!--
          <div class="ConfigTitle">
            <span>Fuente decorativa</span>
          </div>
          <div class="ConfigBox">
            <div class="ConfigDropdown">
              <div class="ConfigDropdown-button" data-target="#SeFontsParaDeco">
                <div class="ConfigDropdown-current">
                  <div id="CurrentDecoFont" class=""></div>
                </div>
                <div class="ConfigDropdown-arrow" ><i class="fa fa-2x fa-angle-down"></i></div>
              </div>
              <div id="SeFontsParaDeco" class="ConfigDropdown-list FontContainer">
                
              </div>
            </div>
          </div>--><!--ConfigBox-->
            
          <!--FixBugHeight-->
          <div class="FontContainerHelper"></div>
        </div>
      </div>




      
    </div><!--/ConfigSection-->


  </div><!--/ConfigOptions-->
</div><!--/Config-->

<!-- TEST TOUR -->
    <?php if(isset($_GET[nueva])){
      echo '<link rel="stylesheet" href="'.$base_url.'/9/ui/tour/hopscotch.min.css">';
      echo '<script src="'.$base_url.'/9/ui/tour/hopscotch.min.js"></script>';
      echo '<script src="'.$base_url.'/9/ui/tour/tour.js"></script>';
    }?>
        <script src="<?=$base_url;?>/ckeditor4.5.4/ckeditor.js"></script>
        <script src="<?=$base_url;?>/9/ui/js/editar_en_linea.js"></script>
        <script src="<?=$base_url;?>/9/ui/js/colores.js"></script>
        <!--archivos para titulo de cabecera gigante-->
        <!--<script src="<?=$base_url;?>/9/ui/js/jquery.fittext.js"></script>
        <script src="<?=$base_url;?>/9/ui/js/bigtext.js"></script>-->
        <!--<script src="<?=$base_url;?>/9/ui/js/svgxuse.js"></script>-->
        <script>
            
            var salir=<?=$valorSalir;?>;
            var base_url='<?= $base_url;?>';
            var id_pagina=<?=$_GET[id_pagina]?>;

        
        
          // This code is generally not necessary, but it is here to demonstrate
          // how to customize specific editor instances on the fly. This fits well
          // this demo because we have editable elements (like headers) that
          // require less features.
        
          // The "instanceCreated" event is fired for every editor instance created.
            <?
            /**
             *                         
            http://server/desarrollo/ProgramacionAvanzada/misaplicaciones.com/ckeditor4.5.4/samples/toolbarconfigurator/index.html#basic
             *
             **/                          
            ?>            
            
          CKEDITOR.on( 'instanceCreated', function( event ) {
            var editor = event.editor,
            
              element = editor.element, esTitulo=false;

                if (element.getAttribute( 'class' ))
                {
                    if (element.getAttribute( 'class' ).search("elementoBasicoTitulo")>-1)
                    {
                        esTitulo=true;
                    }
                }
                //alert("editor:" + element.getAttribute( 'id' ) );

                // Customize editors for headers and tag list.
                // These editors don't need features like smileys, templates, iframes etc.
                if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'taglist' || esTitulo) 
                {
              
                    // Customize the editor configurations on "configLoaded" event,
                    // which is fired after the configuration file loading and
                    // execution. This makes it possible to change the
                    // configurations before the editor initialization takes place.
                    editor.on( 'configLoaded', function () {
                    

                        editor.config.toolbarGroups = [
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                            { name: 'forms', groups: [ 'forms' ] },
                            { name: 'links', groups: [ 'links' ] },
                            { name: 'insert', groups: [ 'insert' ] },
                            { name: 'styles', groups: [ 'styles' ] },
                            { name: 'colors', groups: [ 'colors' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                            { name: 'tools', groups: [ 'tools' ] },
                            { name: 'others', groups: [ 'others' ] },
                            { name: 'about', groups: [ 'about' ] }
                          ];
                        
                        editor.config.removeButtons = 'Source,Save,Print,Templates,NewPage,Preview,Cut,Copy,Redo,PasteFromWord,Undo,Find,Replace,SelectAll,Scayt,RemoveFormat,CreateDiv,Blockquote,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,HorizontalRule,SpecialChar,PageBreak,Iframe,Styles,Font,ShowBlocks,Maximize,About,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Image,Subscript,Superscript,NumberedList,BulletedList,Outdent,JustifyCenter,JustifyLeft,JustifyRight,JustifyBlock,Table,Indent,Paste,PasteText,BGColor';

                    
                        } 
                    );
                    
                }
            
                editor.on( 'change', function () {salir=false;});
            

          });
                                    
        </script>



