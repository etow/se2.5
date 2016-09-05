<?
header("Content-Type: text/html; charset=iso-8859-1");

$id_empresa=$_COOKIE[id_empresa_web];

if ($_POST["tipo"]=="info_tipo_pagina")
  $GLOBALS[considerar_link_aplic_base_predet]=true;


require("../../../config.inc.php");
require("../../../funciones.php");
require("../../../funciones/se.php");

SitioExpress_Template::reescribirContenidosWidget($_POST[valor]);

/*
if (getenv("REMOTE_ADDR")=="201.235.0.22")
{
    $rta[estado]=300;
    echo json_encode($rta);
    exit();
}
unset($rta["mensajes"]);
*/

//compruebo los datos
if ($id_empresa=autentificar(true))
{    
}else{
    $rta[estado]=300;
    $rta["mensajes"]="no autentifico";
    echo json_encode($rta);
    exit();
}


$copiaGet=$_GET;
$copiaPost=$_POST;
$copiaFiles=$_FILES;


$sql_ins="insert into log_testeo set script='".getenv('SCRIPT_NAME')."',
								parametros_get='".print_r(addslashesArray($copiaGet),true)."',
								parametros_post='".print_r(addslashesArray($copiaPost),true)."',
								parametros_files='".print_r(addslashesArray($copiaFiles),true)."',
								fecha=NOW(),
								cod_empresa=$_COOKIE[id_empresa_web],
								ip='".getenv("REMOTE_ADDR")."'";
if ($result_ins=mysql_query($sql_ins))
{
	//$rta["mensajes"]="Inserto: ".mysql_affected_rows()." \n\r ".print_r(addslashesArray($_POST),true);
}else{
	//$rta["mensajes"]=mysql_error();
}


//busco los datos del sitio
$sql_sitio="select id_sitio from z9_sitios where cod_cliente='$_COOKIE[id_empresa_web]'";
$result_sitio=mysql_query($sql_sitio);
if ($myrow_sitio=mysql_fetch_array($result_sitio))
{    
}else{
    $rta[estado]=300;
    $rta["mensajes"]="no encontro el sitio";
    echo json_encode($rta);
    exit();
}














if ($_POST["tipo"]=="orden")
{
    
    //$hay_error=1;
    //$rta["mensajes"]=print_r($_POST,true);
    //$rta["mensajes"]=$_POST[info_pagina_no_visible][0];
    
    if (is_array($_POST[info_seccion_sin_pagina]))
    {
        foreach ($_POST[info_seccion_sin_pagina] as $clave=>$valor)
        {
            if ($clave!=$valor)
            {
               $hay_error=1;
               $rta["mensajes"]="Esta intentando mover un elemento que no corresponde.";                       
            }
        }
    }
    
    if (empty($_POST["info_pagina_no_visible"][0]))    
    {
    }else{
       $hay_error=1;
       $rta["mensajes"].="Esta intentando mover un elemento que no corresponde";       
    }
        
    
    //verifico que una pagina no este en el raiz
    if ($clave_pagina_root=array_search("root",$_POST[pagina_id]))
    {
       $hay_error=1;
       $rta["mensajes"].="La Pagina no puede ser movida aquí. Comprueba que estes moviendo la misma a una sección de tu sitio.";
       
    }

    //verifico que la capa de secciones no se haya pasado dentro de una seccion
    if ($_POST[novisibles][0]!="root")
    {
       $hay_error=1;
       $rta["mensajes"].="No puede mover las páginas invisibles todas juntas";
       
    }
        
    if (!$hay_error)
    {
        //edito todas las paginas  
        $sqls[]="update z9_paginas set orden=NULL where cod_sitio=$myrow_sitio[id_sitio]";
        $sqls[]="update z9_secciones set orden_seccion=NULL where cod_sitio=$myrow_sitio[id_sitio]";
        
        $existe_pagina_no_invisible=false;
        
        $orden=0;
        $orden_seccion=0;
        //var_dump($_POST);exit();
        //compruebo q las secciones no hayan sido movidas interiormente
        if (@count($_POST[seccion_id])>0)
        {
            foreach ($_POST[seccion_id] as $id_seccion=>$padre)
            {
                if ($padre!="root")
                {
                    $hay_error=1;
                    $rta["mensajes"].="Una seccion no puede ser interna";
                    break;
                }

                    
                if ($id_seccion=="noseccion")
                    $id_seccion_numerico=0;
                else{
                    $id_seccion_numerico=$id_seccion;               
                    $orden_seccion++;
                    $sqls[]="update z9_secciones set orden_seccion=$orden_seccion where id_seccion=$id_seccion and cod_sitio=$myrow_sitio[id_sitio]";
                }

                
                $ids_paginas_de_la_seccion=array_keys($_POST[pagina_id],$id_seccion);            
                
                foreach ($ids_paginas_de_la_seccion as $id_pagina)
                {
                    $existe_pagina_no_invisible=true;
                    $orden++;
                    $sqls[]="update z9_paginas set cod_seccion=$id_seccion_numerico, orden=$orden, visible=1, cod_pagina=NULL where id_pagina=$id_pagina and cod_sitio=$myrow_sitio[id_sitio]";
        
                    $ids_subpaginas_de_la_seccion=array_keys($_POST[pagina_id],$id_pagina);
                    
                    foreach ($ids_subpaginas_de_la_seccion as $idSubPagina)
                    {
                        $orden++;
                        $sqls[]="update z9_paginas set cod_seccion=$id_seccion_numerico, orden=$orden, visible=1, cod_pagina=$id_pagina where id_pagina=$idSubPagina and cod_sitio=$myrow_sitio[id_sitio]";     
                    }
                }        
            }
        }
        
        if (!$existe_pagina_no_invisible)
        {
            $hay_error=1;
            $rta["mensajes"]="Debe existir por lo menos una página visible";
        }else{
            
            //pongo las paginas invisibles
            $paginas_invisibles=array_keys($_POST[pagina_id],"0", true);
            //$rta[post]=print_r($paginas_invisibles, true);echo json_encode($rta);exit();
            foreach ($paginas_invisibles as $id_pagina_invisible)
            {
                $orden++;
                $sqls[]="update z9_paginas set orden=$orden, visible=0, cod_pagina=NULL where id_pagina=$id_pagina_invisible and cod_sitio=$myrow_sitio[id_sitio]";
                $ids_subpaginas_de_la_invisible=array_keys($_POST[pagina_id],$id_pagina_invisible);
                foreach ($ids_subpaginas_de_la_invisible as $idSubPagina)
                {
                    $orden++;
                    $sqls[]="update z9_paginas set orden=$orden, visible=0, cod_pagina=$id_pagina_invisible 
                                    where id_pagina=$idSubPagina and cod_sitio=$myrow_sitio[id_sitio]";
                }
                
            }                
        }                    
    }
    //si hay alguna pagina en NULL quiere decir q hubo un error
    
    if ($hay_error)
    {
        $rta[estado]=300;
    }else{
        $rta[estado]=200;
        //echo "echo hago los sqls";
        //ejecuto los sql
        foreach ($sqls as $sql)
        {
            $sqlstxt.=$sql."\n\r";
            $result_orden=mysql_query($sql);
        }        
    }
    //echo "llego al final";
    
    //var_dump($_POST);
    //$rta[post]=print_r($_POST, true).$sqlstxt;
    $rta["mensajes"]=utf8_encode($rta["mensajes"]);
    //var_dump($rta);
    echo json_encode($rta);
}//end if ($_POST["tipo"]=="orden")





if ($_POST["tipo"]=="editar_campo")
{
    
    $_POST[valor]=SitioExpress_Template::reescribirContenidosWidget($_POST[valor]);    
    $_POST[valor]=utf8_decode($_POST[valor]);        
    
    
    if ($_POST[campo]=="titulo")
        $_POST[valor]=strip_tags($_POST[valor]);


    $_POST[valor]=addslashes($_POST[valor]);

    if (strpos($_POST[campo],"zona_")!==false)
    {
        
        $sql_ze="select cod_sitio from z9_zonas_editables where cod_sitio=$myrow_sitio[id_sitio]";
        $result_ze=mysql_query($sql_ze);
        if ($myrow_ze=mysql_fetch_array($result_ze))
        {            
        }else{
            //sino existe lo cree
            $sql_ins_ze="insert into z9_zonas_editables set cod_sitio=$myrow_sitio[id_sitio]";
            $result_ins_ze=mysql_query($sql_ins_ze);               
        }
        
        $campo_aux=str_replace("zona_","",$_POST[campo]);
        //edicion de zona editable
        $sql_upd_ze="update z9_zonas_editables set $campo_aux='$_POST[valor]' where cod_sitio=$myrow_sitio[id_sitio]";
        if ($result_upd_ze=mysql_query($sql_upd_ze))
        {    
            $rta[estado]=200;
            $rta["mensajes"].="Se actualizo la zona editable $campo_aux";
        }else{
            $rta[estado]=300;
            $rta["mensajes"]="No se pudo actualizar la zona editable $campo_aux";
        }        
        //$rta["mensajes"].="\r\n".$sql_upd_ze;
    }else{
        
        if (($_POST[campo]=="texto")or(($_POST[campo]=="footer")))
            $_POST[valor]=minify_html($_POST[valor]);
        
        if ($_POST[campo]=="footer")
        {
            $sql_footer="update z9_sitios set html_footer='$_POST[valor]' where id_sitio=$myrow_sitio[id_sitio]";
            if ($result_footer=mysql_query($sql_footer))
            {    
                $rta[estado]=200;
                $rta["mensajes"].="Se actualizo el pie del sitio";
                
                if (mysql_affected_rows()>0)
                {
            		$sql_upd_log="update z20_log_acciones_se set cnt_footer_editados=cnt_footer_editados+1 where cod_empresa=$_COOKIE[id_empresa_web]";
            		//echo $sql_ins_log."<br>";
                	$result_upd_log = mysql_query($sql_upd_log);
                }                        
            }else{
                $rta[estado]=300;
                $rta["mensajes"]="No se pudo actualizar el pie del sitio";
            }        
            
        }else{        
                
            $sql_upd_page="update z9_paginas set $_POST[campo]='$_POST[valor]' where id_pagina='$_POST[id_pagina]' and cod_sitio=$myrow_sitio[id_sitio]";
            if ($result_upd_page=mysql_query($sql_upd_page))
            {                
                $rta[estado]=200;
                $rta["mensajes"].="Se actualizo el campo $_POST[campo]";
                if ($_POST[campo]=="texto")
                {
                    if (mysql_affected_rows()>0)
                    {
                		$sql_upd_log="update z20_log_acciones_se set cnt_paginas_editadas=cnt_paginas_editadas+1 where cod_empresa=$_COOKIE[id_empresa_web]";
                    	$result_upd_log = mysql_query($sql_upd_log);
                    }                        
                    
                    
                    $rtaConf=SitioExpress_SitioExpress::configurarPaginaDeModulo($_POST[id_pagina],false);
                    if ($rtaConf===false)
                    {
                        $rta[estado]=300;
                        $rta["mensajes"]="No se pudo actualizar el campo $_POST[campo]";
                    }                                
                }                    
                
            }else{
                $rta[estado]=300;
                $rta["mensajes"]="No se pudo actualizar el campo $_POST[campo]";
            }
         }
    }

    echo json_encode($rta);    
    exit();
}//end if ($_POST["tipo"]=="orden")









if ($_POST["tipo"]=="editar_template")
{
    unset($_POST[tipo]);
    $rta=array();
    $sql = "update z9_config set ";
    
    foreach ($_POST as $campo=>$valor)
    {
        $sql.="$coma $campo='$valor'";
        $coma=",";
    }
    
    $sql.= "where id_empresa='".$_COOKIE[id_empresa_web]."'";
    //echo $sql;     
    if ($result=mysql_query($sql))
    {    
        if (isset($_POST[template_color]) and ($_POST[template_color]!=="custom"))
        {
            //reseteo los custom
           $sql="update z9_config set
                         color_custom_primario='',
                         color_custom_claro='',
                         color_custom_oscuro='',
                         color_custom_acento_1='',
                         color_custom_acento_2='', 
                         color_custom_callTo=''where id_empresa=$_COOKIE[id_empresa_web]";
           $result=mysql_query($sql);
            
        }
        
        
        
        if (mysql_affected_rows()>0)
        {
    		$sql_upd_log="update z20_log_acciones_se set cnt_templates_editados=cnt_templates_editados+1 where cod_empresa=$_COOKIE[id_empresa_web]";
        	$result_upd_log = mysql_query($sql_upd_log);
        }                        
        
        $rta[estado]=200;
        $rta["mensaje"]=utf8_encode("Se actualizo la configuración del template ");        
    }else{
        $rta[estado]=300;
        $rta["mensaje"]=utf8_encode("No se pudo actualizar la configuración del template");
    }       
    echo json_encode($rta);    
    exit();
}










if ($_POST["tipo"]=="info_tipo_pagina")
{
    $form=new Form_Formulario("?");

    if ($_POST[id_pagina])
    {
        $sql_pagina="SELECT z9_paginas.* from z9_paginas, z9_sitios 
                     where cod_sitio=id_sitio and cod_cliente=$_COOKIE[id_empresa_web] and id_pagina=$_POST[id_pagina]";
        //echo $sql_pagina;
        $result_pagina=$mysqli->query($sql_pagina);
        if (!($myrow_pagina=$result_pagina->fetch_assoc()))
        {
            echo "<p class='alert alert-block alert-warning'>La página es incorrecta</a>";
            exit();            
        } 
    }

    if (($_POST[cod_tipo_pagina]==1) or ($_POST[cod_tipo_pagina]==6) or ($_POST[cod_tipo_pagina]==10))//formulario de contacto o texto o landing page
    {
        
        if ( ($_POST[cod_tipo_pagina]==6)and(empty($myrow_pagina[texto])) )
        {            
            $myrow_pagina[texto]='<p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p align="center"><font face="Arial" size="2">Muchas Gracias <b><font color="#FF0000">'.$nombre_consulta.'</font></b>
                                    !!</font></p>
                                    <p align="center"><font face="Arial" size="2">Hemos recibido su consulta, y en breve nos pondremos en
                                    contacto con Usted.</font></p>
                                    <p align="center"><a href="javascript:history.go(-1)">
                                    <font face="Arial" size="2">Volver</font></a></p>
                                    <hr size="0" color="#CCCCCC">
                                    <p align="center"><font face="Arial" size="2">Thanks <b><font color="#FF0000">'.$nombre_consulta.'</font></b>
                                    !!</font></p>
                                    <p align="center"><font face="Arial" size="2">I have received your query.</font></p>
                                    <p align="center"><a href="javascript:history.go(-1)">
                                    <font face="Arial" size="2">Back</font></a></p>
                                    
                                    <p align="center">&nbsp;</p>
                                    <p align="center">&nbsp;</p>
                                    <p align="center">&nbsp;</p>';
        }        
        
        $label="Texto para la Página";
        if ($_POST[cod_tipo_pagina]==6)        
            $label="Mensaje de Formulario de Contacto";
        if ($_POST[cod_tipo_pagina]==10)        
            $label="Texto para la Landing Page";
            
            
        ?>
	           <script src="../ckeditor4.5.4/ckeditor.js"></script>
                <textarea name="texto">
                    <?=$myrow_pagina[texto];?>
                </textarea>
                <script>
CKEDITOR.replace( 'texto', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
        		{ "name": "document", groups: [ "mode", "document", "doctools" ] },
        		{ "name": "editing", groups: [ "find", "selection", "spellchecker", "editing" ] },
        		{ "name": "basicstyles", groups: [ "basicstyles", "cleanup" ] },
        		{ "name": "paragraph", groups: [ "list", "indent", "blocks", "align", "bidi", "paragraph" ] },
        		{ "name": "forms", groups: [ "forms" ] },
        		{ "name": "links", groups: [ "links" ] },
        		"/",
        		{ "name": "insert", groups: [ "insert" ] },
        		{ "name": "styles", groups: [ "styles" ] },
        		{ "name": "colors", groups: [ "colors" ] },
        		{ "name": "clipboard", groups: [ "clipboard", "undo" ] },
        		{ "name": "tools", groups: [ "tools" ] },
        		{ "name": "others", groups: [ "others" ] },
        		{ "name": "about", groups: [ "about" ] }
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Save,Print,Templates,NewPage,Preview,Cut,Copy,Find,Replace,SelectAll,Scayt,CreateDiv,BidiLtr,BidiRtl,Language,Flash,SpecialChar,PageBreak,Iframe,ShowBlocks,Maximize,About,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Superscript,Subscript,RemoveFormat'
		} );                   </script>                 
        <?
        exit();    
            
        
        $sql_sitio="SELECT id_sitio, cod_template, hoja_de_estilo, desc_toolbarset, galeria_estableciendo_carpeta
                    FROM z9_sitios
                    LEFT JOIN z9_config ON ( z9_config.id_empresa = z9_sitios.cod_cliente )        
                     
                    
                    WHERE z9_sitios.cod_cliente =".$_COOKIE[id_empresa_web];
        //echo $sql_sitio;
        $result_sitio=$mysqli->query($sql_sitio);
        if (!($myrow_sitio=$result_sitio->fetch_assoc()))
        {
            
        }        
        
        
        if ($myrow_sitio[cod_template]!=0)
        {
            if ($myrow_sitio[hoja_de_estilo]!="")
            {
              $css_template=$myrow_sitio[hoja_de_estilo];
            }else{                  
              if ($fp = fopen("../9/templates_sitios_dinamicos/$myrow_sitio[cod_template]/imagenes/estilos.css","r"))
              {
                while (!FEOF($fp))
                    $css_template .= fgets($fp);
            
                fclose($fp);
              }                                        
            }
        }

        $css_template.="body{background-color: #fff;color:#000;background:url(fondo_body_inexistente.jpg) repeat-x;}";  

                        
        $form->agregarCampos($label, "fckeditor_se", "texto", $myrow_pagina[texto], "fckeditor_se", "fckeditor_se", array("EditorAreaCSS"=>$css_template,"ToolbarSet"=>$ToolbarSet));  
        
    }//if (($_POST[cod_tipo_pagina]==1) or ($_POST[cod_tipo_pagina]==6))    
    if ($_POST[cod_tipo_pagina]==5)//link
    {
        $form->agregarCampos("Link", "text", "hipervinculo", $myrow_pagina["hipervinculo"], "", "", array("class"=>"required"));  
        
    }//if ($_POST[cod_tipo_pagina]==5)


    if ($_POST[cod_tipo_pagina]==9)//galeria
    {
        //no hago nada
    }//if ($_POST[cod_tipo_pagina]==5)



    if ($_POST[cod_tipo_pagina]==3)//Tienda Virtual
    {
        $array_tipo_info_catalogo[""]="Seleccione una Opcion";
        $array_tipo_info_catalogo["listado"]="Los Productos de su Tienda Virtual";
        $array_tipo_info_catalogo["categorias"]="Las Categorías de su Tienda Virtual";

        $array_idiomas_catalogo[""]="Seleccione un Idioma";
        $array_idiomas_catalogo["es"]="Español";
        $array_idiomas_catalogo["en"]="Ingles";
        $array_idiomas_catalogo["port"]="Portugues";

        if (!empty($myrow_pagina["hipervinculo"]))
            $info_link=desarmar_link_modulo($myrow_pagina["hipervinculo"],true,6);
          
          
        $form->agregarCampos("Qué información de tu catálogo deseas mostrar?", "select", "parametros_hipervinculo[tipo]", $info_link["parametros"]["tipo"], $array_tipo_info_catalogo, "parametros_hipervinculo_tipo", array("class"=>"required"));  
        $form->agregarCampos("En que idioma deseas mostrar la informaci&oacute;n", "select", "parametros_hipervinculo[leng]", $info_link["parametros"]["leng"], $array_idiomas_catalogo, "parametros_hipervinculo_leng", array("class"=>"required"));  
                
    }//if ($_POST[cod_tipo_pagina]==5)


    $form->mostrarHTML(true);       

}//if ($_POST["tipo"]=="info_tipo_pagina")

?>