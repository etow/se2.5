<?
$linkSearchFolderPredet="../../..";
$minificado=true;
$fancyWidget=true;
$id_sistema=9;

function __autoload($class_name){
	//echo $class_name."<br>";
	$archivo_clase = str_replace("_","/",$class_name).".php";
    $archivo_clase = "../../../clases/".$archivo_clase;
    if (file_exists($archivo_clase))
	  require_once $archivo_clase; 
	return true;
}

include_once "../../../funciones.php";
include_once "../../../funciones/se.php";
include_once("../../../funciones/api.php");

include_once "../../../config.inc.php";
include_once "../../../inicio_admin_head.php";


$nombre_pagina_predet="Datos de Mi Sitio Web";



//include "../inicio_admin.php";


?>
<body>

<div id="fancybox-widget" class="Wiframe">


<?



$sql="select z903_background.*, id_sitio
      from z9_sitios left join z903_background on (cod_pagina is null and cod_sitio=id_sitio) 
      where cod_cliente='".$_COOKIE["id_empresa_web"]."'";
//echo $sql;
$result=$mysqli->query($sql);
$myrow=$result->fetch_assoc();










    
    
if (($_GET[accion]=="editar") and ($_GET[tipo]=="editar"))    
{

        
        $hay_error=false;

        
        if (!$_POST["imagen_fondo_bool"])
        {
            //borro el fondo                        
            $sql="delete from z903_background where cod_pagina is null and cod_sitio='$myrow[cod_sitio]'";                        
            //echo $sql;                         
            if ($result=mysql_query($sql))
            {
                @unlink("../../../".$myrow["background-image_file"]);
            }
            
            
        }else{
                                   

           if ($_REQUEST[establecer_bg]!="url")
                $_REQUEST["background-image"]=$_REQUEST[establecer_bg];
    
           if ($_REQUEST[establecer_bg]=="archivo"){
    			$_REQUEST["background-image"]="";
                if ($_FILES["background-image_file"]["size"]>0)
                {
                    $guardo=false;
    
                    if (!getimagesize($_FILES["background-image_file"]["tmp_name"]))
                    {	                       
                        $error_nro=10;               
                    }else{				
    				    				    				
                        //echo "$indice=>$tmp_name<br>";
                        /** Si tiene foto, la guardo **/
                        if ($_FILES["background-image_file"]["error"]!=4)
                        {                                            
                            //$tmp_name = $_FILES["foto_file"]["tmp_name"];
                            
                            $destino_name_cabecera="9/imagenes_background/";
                            $ciclar=true;
                            while ($ciclar)
                            {
                                $name_cabecera=$_COOKIE[id_empresa_web]."_".$rand."".modificar_nombre_imagenes($_FILES["background-image_file"]["name"]);
                                if (file_exists("../../../".$destino_name_cabecera.$name_cabecera))
                                    $rand=md5(rand(1,1000000));
                                else
                                    $ciclar=false;
                            }
                            
                            if (move_uploaded_file($_FILES["background-image_file"]["tmp_name"], "../../../".$destino_name_cabecera.$name_cabecera))
                            {
                                echo "subio el archivo";
                                $archivo_background_image=$destino_name_cabecera.$name_cabecera;
                                $guardo=true;
            
                            }else{
                                $error_nro=3;
                            }
                        }else{
                            $error_nro=2;                
                        }
                        
                    }
        
                }else{
                    if (file_exists("../../../".$myrow["background-image_file"]) and (!empty($myrow["background-image_file"])))
                    {
                        $archivo_background_image=$myrow["background-image_file"];
                        $guardo=true;                    
                    }else{
                        echo "<p class='alert alert-block alert-danger'> - Debes ingresar una imagen para el fondo.</p>";
                    }                    
                }     
                
                
                if (!$guardo)
                {
                    echo "<p class='alert alert-block alert-danger'> - No pudo ingresar la imagen para el fondo (Ex00$error_nro).</p>";
                    exit();                                        
                }       
                
           }
           
           
           if (empty($myrow[id_background]))
                $myrow[id_background]="NULL";
           
                     
           $sql="replace z903_background set 
                            `background-image`='".$_REQUEST["background-image"]."',
                         	`background-color`='".$_REQUEST["background-color"]."',
                         	`background-repeat`='".$_REQUEST["background-repeat"]."',
                         	`background-size`='".$_REQUEST["background-size"]."',
                         	`background-attachment`='".$_REQUEST["background-attachment"]."',
                         	`boxed`='".$_REQUEST["boxed"]."',
                            secciones_transparente='".$_REQUEST["secciones_transparente"]."',
                            `background-image_file`='".$archivo_background_image."',
                            cod_pagina=NULL,
                            id_background=$myrow[id_background],
                            cod_sitio='$myrow[id_sitio]'";
    
           //echo $sql; 
        
           if ($result=mysql_query($sql))
           {
            	//echo "<p class='alert alert-block alert-success'>La información de la imagen ha sigo guardada con éxito!</p>";
                    //si ya exisita el pasador me fijo si tenia carga imagen por archivo
                    if (!empty($myrow["background-image_file"]) and (!empty($name_cabecera)))              
                        $borro_background_file=true;                
    
                    if (!empty($myrow["background-image_file"]) and ($_REQUEST[establecer_bg]!="archivo"))              
                        $borro_background_file=true;                
                    
                    
                    if ($borro_background_file)
                        @unlink("../../../".$myrow["background-image_file"]);
                
                        
           }else{
                echo "<p class=error>La información de fondo del sitio no pudo ser guardada</p>";        
                exit();               
           }                        

        }

        
        
        //exit();
        if (!$hay_error)
        {
        ?>
            <script>
                alert("Para poder ver reflejados los cambios realizados recién en la página debe refrescar (F5) la ventana de su navegador.");                    
                parent.$.fancybox.close();
            </script>
        
        <?
            exit();
        }
        
        
    
}// end if ($accion!="editar")




























?>
<script>
function eliminarArchivo(archivo,id)
{
    $("#"+id).html("&lt;input name='archivosAEliminar[]' value='"+archivo+"' type='hidden' /&gt;&lt;p class=advertencia&gt;El archivo será eliminado cuando guarde la información.&lt;/p&gt;");
}
</script>
<form class="Wiframe-form" id="Formulario" method="post" action="?tipo=editar&accion=editar" enctype="multipart/form-data">
    
    <input type="hidden" name="id" value="<?=$_GET[id];?>" />

	<div class="Wiframe-header">
		<div class="Wiframe-title">
			Definir Fondo del Sitio
		</div>
	</div><!--/header-->

	<div class="Wiframe-options">

		<div class="wSection">
			<div class="wList">
                
            
                

			</div>                        
		</div><!--/wSection-->



		<div class="wSection">
			<div class="wList ">
            

        
				<div class="wItem wItem--switch">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Definir Fondo 
						</div>
						<div class="wSwitch">
							<label for="imagen_fondo_bool">
								<div class="wSwitch-toggle"></div>
								<span class="wSwitch-text">ON</span>
								<span class="wSwitch-text">OFF</span>
							</label>                                                                                
						</div>
                        
                        <input type="checkbox" class="wHide" id="imagen_fondo_bool" value="1" checked name="imagen_fondo_bool"/>
					</div>
				</div><!--/wItem-->  
                
                <?$checkedST[$myrow[secciones_transparente]]="checked";?>
				<div class="wItem wItem--switch">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Secciones Transparentes 
						</div>
						<div class="wSwitch">
							<label for="secciones_transparente">
								<div class="wSwitch-toggle"></div>
								<span class="wSwitch-text">ON</span>
								<span class="wSwitch-text">OFF</span>
							</label>                                                                                
						</div>
                        
                        <input type="checkbox" class="wHide" id="secciones_transparente" <?=$checkedST[1]?> value="1" name="secciones_transparente"/>
					</div>
				</div><!--/wItem-->                                        
                
        
			</div>                        
		</div><!--/wSection-->  
           


		<div class="wSection" id="fondo_pagina">
        
			<div class="wSection-title">
				Fondo de la Página
			</div>                
			<div class="wList">

				<div class="wItem wItem--input">
					<div class="wItem-wrapper is-filled">
						<div class="wItem-name">
							Ancho del Sitio
						</div>
                        <?$selectedBoxed[$myrow[boxed]]="selected";?>
                        <select class="wInput" name="boxed" >
                            <option value='0' <?=$selectedBoxed[0];?>>Ancho Completo</option>
                            <option value='1' <?=$selectedBoxed[1];?>>Ancho Encajonado</option>
                        </select>								
					</div>
				</div>
                
            	  <div class="wItem wItem--input">
            			<div class="wItem-wrapper">
            				<div class="wItem-name">
            					Color de Fondo
            				</div>
                            <input type="text" id="bg_bolor" class="wInput pickerColor"  name="background-color" size="14" maxlength="7" value="<?=$myrow["background-color"]?>"/>
            			</div>
            		</div>                        
                        
            </div>
            <br />
			<div class="wSection-title">
				Imagen de Fondo de la Pagina
			</div>            
            
			<div class="wList">
				<div class="wItem wItem--radioImage">
					<div class="wItem-wrapper">
						<label for="establecer_bg_0">
							<div class="wItem-media">
							</div>
							<div class="wItem-name">
								Predeterminado
							</div>
						</label>
					</div>
				</div>
				<div class="wItem wItem--radioImage ">
					<div class="wItem-wrapper">
						<label for="establecer_bg_none">
							<div class="wItem-media">
							</div>
							<div class="wItem-name">
								Ninguna
							</div>
						</label>
					</div>
				</div>                
				<div class="wItem wItem--radioImage ">
					<div class="wItem-wrapper">
						<label for="establecer_bg_url">
							<div class="wItem-media">
							</div>
							<div class="wItem-name">
								Definir Imagen por URL
							</div>
						</label>
					</div>
				</div>
				<div class="wItem wItem--radioImage ">
					<div class="wItem-wrapper">
						<label for="establecer_bg_archivo">
							<div class="wItem-media">
							</div>
							<div class="wItem-name">
								Subir imagen
							</div>
						</label>
					</div>
				</div>                        
			</div>
            
            
<?
    if (empty($myrow["background-image"])){
        if (empty($myrow["background-image_file"]))
            $establecer_bg["0"]="checked";
        else
            $establecer_bg["archivo"]="checked";
        $myrow["background-image"]="";
    }else{        
        if ($_POST["background-image"]=="none")
        {
            $myrow["none"]="checked";
            $myrow["background-image"]="";
        }else
            $establecer_bg["url"]="checked";        
    }
     
?>
            

            <input class="wHide" <?=$establecer_bg["0"];?> type="radio" value="0" id="establecer_bg_0" name="establecer_bg"  />
            <input class="wHide" <?=$establecer_bg["none"];?> type="radio" value="none" id="establecer_bg_none" name="establecer_bg" />
            <input class="wHide" <?=$establecer_bg["url"];?> type="radio" value="url" id="establecer_bg_url" name="establecer_bg" />                    
            <input class="wHide" <?=$establecer_bg["archivo"];?> type="radio" value="archivo" id="establecer_bg_archivo" name="establecer_bg" />
            
            
            <br />
			<div class="wList">
				<div class="wItem wItem--input" id="item_background-image">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							URL de la Imagen
						</div>
                        <input type="text" class="wInput change"  name="background-image" size="14" value="<?=$myrow["background-image"];?>"/>
					</div>
				</div>
				<div class="wItem wItem--input" id="item_background-image_file">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Archivo de la Imagen
						</div>
                        <input type="file" class="wInput change" id="background-image_file"  name="background-image_file" size="14" value="<?=$myrow["background-image_file"];?>"/>
					</div>
				</div>				
				<div class="wItem wItem--input" id="item_background-repeat">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Repetición de la Imagen
						</div>
                        
                        <select name="background-repeat" class="wInput change">                        
                            <?
                            $opcionesRepeat[""]="Predefinido por el Diseño";
                            $opcionesRepeat["no-repeat"]="No Repetir";
                            $opcionesRepeat["repeat"]="Repetir";
                            $opcionesRepeat["repeat-x"]="Repetir a lo Ancho (repeat-x)";
                            $opcionesRepeat["repeat-y"]="Repetir a lo Largo (repeat-y)";
                            foreach ($opcionesRepeat as $indice=>$valor)
                            {
                                if ($indice==$myrow["background-repeat"])
                                    $selected="selected";
                                else    
                                    $selected="";
                                    
                                echo "<option $selected value='$indice'>$valor</option>";
                            }
                            ?>                        
     
                        </select>                                
					</div>
				</div>					
			</div><!--/wList-->                    
            
            
            
			<div class="wList">
				<div class="wItem wItem--input" id="item_background-size">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Tamaño de la Imagen
						</div>        
                        <select name="background-size" class="wInput change">                        
                            <?
                            $opcionesSize[""]="Predefinido por el Diseño";
                            $opcionesSize["cover"]="Cover";
                            $opcionesSize["contain"]="Contain";
    
                            foreach ($opcionesSize as $indice=>$valor)
                            {
                                if ($indice==$myrow["background-size"])
                                    $selected="selected";
                                else    
                                    $selected="";
                                    
                                echo "<option $selected value='$indice'>$valor</option>";
                            }
                            ?>                        
     
                        </select>
					</div>
				</div>
				<div class="wItem wItem--input" id="item_background-attachment">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Posicionamiento de la Imagen
						</div>
                        <select name="background-attachment" class="wInput change">                        
                            <?
    
                            $opcionesAttachment[""]="Predefinido por el Diseño";       
                            $opcionesAttachment["fixed"]="Fijo";
                            $opcionesAttachment["scroll"]="Scroll";
                            foreach ($opcionesAttachment as $indice=>$valor)
                            {
                                if ($indice==$myrow["background-attachment"])
                                    $selected="selected";
                                else    
                                    $selected="";
                                    
                                echo "<option $selected value='$indice'>$valor</option>";
                            }
                            ?>                                     
                        </select>
					</div>
				</div>								
			</div><!--/wList-->                         
            
		</div><!--wSection-->
        
        
<script>
$(document).ready(function(){
    
    
    $("#imagen_fondo_bool").change(function(){
        if ($(this).is(':checked'))
        {
            $("#fondo_pagina").show();
        }else{
            $("#fondo_pagina").hide();
        }
    });
    
    $("input[type='radio'][name='establecer_bg']").change(function(){
       if ($(this).val()=="url")
       {
            $("#item_background-image,#item_background-image_file,#item_background-repeat,#item_background-size,#item_background-attachment").hide();                
            $("#item_background-image,#item_background-repeat,#item_background-size,#item_background-attachment").show();
       }else{
           if ($(this).val()=="archivo")
           {
                $("#item_background-image,#item_background-image_file,#item_background-repeat,#item_background-size,#item_background-attachment").hide();                
                $("#item_background-image_file,#item_background-repeat,#item_background-size,#item_background-attachment").show();
           }else{
                $("#item_background-image,#item_background-image_file,#item_background-repeat,#item_background-size,#item_background-attachment").hide();                
           }
       }
    });
   
   
   $("#item_background-image_file,#item_background-image,#item_background-repeat,#item_background-size,#item_background-attachment").hide(); 
   if ($("input[type='radio'][name='establecer_bg']:checked").val()=="url")
   {
        $("#item_background-image,#item_background-repeat,#item_background-size,#item_background-attachment").show();
   }else{
       if ($("input[type='radio'][name='establecer_bg']:checked").val()=="archivo")
       {
            $("#item_background-image_file,#item_background-repeat,#item_background-size,#item_background-attachment").show();
       }else{
            $("#item_background-image_file,#item_background-image,#item_background-repeat,#item_background-size,#item_background-attachment").hide();
       }   
   }
});
</script>        
           
           
        <?
        if ($myrow[cod_tipo_pagina]==3)
        {
        $info_link=desarmar_link_modulo($myrow["hipervinculo"],true,6);

        $selected_tipo[$info_link["parametros"]["tipo"]]="selected";
        $selected_leng[$info_link["parametros"]["leng"]]="selected";
        ?>
		<div class="wSection">
			<div class="wSection-title">Datos de Configuración de la Tienda</div>        
			<div class="wList">

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Tipo de Información
						</div>
                        
                        <select id="parametros_hipervinculo_tipo" name="parametros_hipervinculo[tipo]" class="wInput">
                            <option value="listado" <?=$selected_tipo[listado];?>>Los Productos de su Tienda Virtual</option>
                            <option value="categorias" <?=$selected_tipo[categorias];?>>Las Categorías de su Tienda Virtual</option>
                        </select>
						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Idioma
						</div>
                        
                        <select id="parametros_hipervinculo_leng" name="parametros_hipervinculo[leng]" class="wInput">
                                <option value="es" <?=$selected_leng[es];?>>Español</option>
                                <option value="en" <?=$selected_leng[en];?>>Ingles</option>
                                <option value="port" <?=$selected_leng[port];?>>Portugues</option>
                        </select>						
					</div>
				</div>  
                
                
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Categoria
						</div>
                        
                        <select id="parametros_hipervinculo_leng" name="parametros_hipervinculo[id_categoria]" class="wInput">
                                <option value="0">Todas</option>
                                <?
                                
                                $sql_categorias = "select * from z6_categorias where id_empresa = $_COOKIE[id_empresa_web] and id_padre=0 order by orden_categoria";
                                if ($result_categorias=$GLOBALS["mysqli"]->query($sql_categorias))
                                
                                if ($myrow_categorias = $result_categorias->fetch_assoc())
                                {
                                
                                    do{	
                                        
                                        if ($myrow_categorias[id_categoria]==$info_link["parametros"]["id_categoria"])                                        
                                            $selected_cat="selected";
                                        else
                                            $selected_cat="";
                                        
                                        echo "<option $selected_cat value='$myrow_categorias[id_categoria]'>".$myrow_categorias[desc_catego]."</option>";
                                        
                                        $sql_subcategorias = "select * from z6_categorias where id_empresa = $_COOKIE[id_empresa_web] and id_padre=$myrow_categorias[id_categoria] order by orden_categoria";
                                        if ($result_subcategorias=$GLOBALS["mysqli"]->query($sql_subcategorias))
                                        
                                        while ($myrow_subcategorias = $result_subcategorias->fetch_assoc())
                                        {    
                                            
                                            if ($myrow_subcategorias[id_categoria]==$info_link["parametros"]["id_categoria"])                                        
                                                $selected_cat="selected";
                                            else
                                                $selected_cat="";
                                            
                                            echo "<option $selected_cat value='$myrow_subcategorias[id_categoria]'> > ".$myrow_subcategorias[desc_catego]."</option>";
                                        }
                                            
                                    }while ($myrow_categorias = $result_categorias->fetch_assoc());
                                            
                                }                                   
                                
                                ?>

                        </select>						
					</div>
				</div>                   
                
              
                
			</div>                    
		</div><!--/wSection-->        
        <?
        }//if ($myrow[cod_tipo_pagina])



        if ($myrow[cod_tipo_pagina]==2)
        {
        $info_link=desarmar_link_modulo($myrow["hipervinculo"],true,1);

        $selected_tipo[$info_link["parametros"]["tipo"]]="selected";
        $selected_leng[$info_link["parametros"]["leng"]]="selected";
        ?>
		<div class="wSection">
			<div class="wSection-title">Datos de Configuración de la Tienda</div>        
			<div class="wList">


				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Idioma
						</div>
                        
                        <select id="parametros_hipervinculo_leng" name="parametros_hipervinculo[leng]" class="wInput">
                                <option value="es" <?=$selected_leng[es];?>>Español</option>
                                <option value="en" <?=$selected_leng[en];?>>Ingles</option>
                                <option value="port" <?=$selected_leng[port];?>>Portugues</option>
                        </select>						
					</div>
				</div>  
                
                
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Categoria
						</div>
                        
                        <select id="parametros_hipervinculo_leng" name="parametros_hipervinculo[id_categoria]" class="wInput">
                                <option value="0">Todas</option>
                                <?
                                
                                $sql_categorias="select id_catego_nota, desc_catego from z1_categorias where id_empresa='".$_COOKIE[id_empresa_web]."'";

                                if ($result_categorias=$GLOBALS["mysqli"]->query($sql_categorias))
                                
                                if ($myrow_categorias = $result_categorias->fetch_assoc())
                                {
                                
                                    do{	
                                        
                                        if ($myrow_categorias[id_catego_nota]==$info_link["parametros"]["id_categoria"])                                        
                                            $selected_cat="selected";
                                        else
                                            $selected_cat="";
                                        
                                        echo "<option $selected_cat value='$myrow_categorias[id_catego_nota]'>".$myrow_categorias[desc_catego]."</option>";                                                                                
                                            
                                    }while ($myrow_categorias = $result_categorias->fetch_assoc());
                                            
                                }                                   
                                
                                ?>

                        </select>						
					</div>
				</div>                   
                
              
                
			</div>                    
		</div><!--/wSection-->        
        <?
        }//if ($myrow[cod_tipo_pagina])

        ?>   
	</div><!--/options-->

	<div class="Wiframe-footer">
		<input type="submit" value="Guardar" class="Wiframe-submit">
	</div><!--/footer-->                
    
</form>                    


<script src="<?=$base_url;?>/9/ui/js/widget_ui.js"></script>
</div>
</body>
</html>