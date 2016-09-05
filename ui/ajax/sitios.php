<?
$linkSearchFolderPredet="../../..";
$minificado=true;
$fancyWidget=true;

function __autoload($class_name){
	//echo $class_name."<br>";
	$archivo_clase = str_replace("_","/",$class_name).".php";
    $archivo_clase = "../../../clases/".$archivo_clase;
    if (file_exists($archivo_clase))
	  require_once $archivo_clase; 
	return true;
}

include_once "../../../funciones.php";

include_once "../../../config.inc.php";
include_once "../../../inicio_admin_head.php";


$nombre_pagina_predet="Datos de Mi Sitio Web";



//include "../inicio_admin.php";


?>
<body>

<div id="fancybox-widget" class="Wiframe">
<?









    
    
if (($_GET[accion]=="editar") and ($_GET[tipo]=="editar"))    
{
        /*
        if ($nombre=="")
        {
            $error_txt.="<p class='alert alert-block alert-danger'> - Debes completar el nombre del sitio.</p>";
            $hay_error=1;
        }
        */
        if ((strpos($nombre_top,"'")!==false) or (strpos($nombre_top,'"')!==false))
        {
            $error_txt.="<p class='alert alert-block alert-danger'> - El Texto de la Cabecera del Sitio no puede contener ningun tipo de comillas.</p>";
            $hay_error=1;
        }
        
        
        if ((strpos($nombre,"'")!==false) or (strpos($nombre,'"')!==false))
        {
            $error_txt.="<p class='alert alert-block alert-danger'> - El Nombre del Sitio no puede contener ningun tipo de comillas.</p>";
            $hay_error=1;
        }
    
    
        if ($e_mail!='')
        {
            if (comprobar_email($e_mail)==0)
            {
               $error_txt.="<p class='alert alert-block alert-danger'> - Debes completar el campo Mail con una direccion valida</p>";
               $hay_error=1;
            }
        }
        
        
        if (!empty($_POST[usuario_cpt]))
        {
            
            $usuario_cpt_aux=preg_replace('([^A-Za-z0-9_-])', '', strtolower($_POST[usuario_cpt]));
            if ($usuario_cpt_aux!=$_POST[usuario_cpt])
            {
                   $error_txt.="<p class='alert alert-block alert-danger'> - La dirección de publicación no es valida. Recuerda que solo debe contener letras en minusculas y numeros. No pueden incluirse espacios</p>";
                   $hay_error=1;
            }else{            
            
                $sql_comp="select id_sitio from z9_sitios where '$_POST[usuario_cpt]'=usuario_cpt";
                //echo $sql_comp;
                $result_comp=$mysqli->query($sql_comp);
                if ($myrow_comp=$result_comp->fetch_assoc())
                {
                   $error_txt.="<p class='alert alert-block alert-danger'> - Ya existe otro Sitio Express con esa dirección de publicación. Intenta con otro nombre</p>";
                   $hay_error=1;
                }
            }
        }        

    
        if ($hay_error==1)
        {
           echo $error_txt;
           exit();
        }
        
        
        if (!empty($_POST[host]))
        {
            $_POST[host]=str_replace("http://","",$_POST[host]);
            $_POST[host]=str_replace("www.","",$_POST[host]);
        }
    
    	//actualizo el log de acciones de sitio express 
    	$sql_upd_log="update z20_log_acciones_se set cnt_ediciones_datos=cnt_ediciones_datos+1 where cod_empresa=$_COOKIE[id_empresa_web]";
    	//echo $sql_ins_log."<br>";
    	$result_upd_log = $mysqli->query($sql_upd_log);
            
            
            

        //GUARDO LA FOTO
        if (!$hay_error)
        {                
            if ($_FILES["logo_file"]["size"]>0)
            {
                
                if (!($tamanoLogo=getimagesize($_FILES["logo_file"]["tmp_name"])))
                {	
	       			echo "<p class=error>No se pudo ingresar la imagen. Tipo de archivo inválido.</p>";
                    $hay_error=1;                
                }else{
                    if (strpos($tamanoLogo["mime"],"psd")!==false)
                    {
    	       			echo "<p class=error>No se pudo ingresar la imagen. Tipo de archivo inválido.</p>";
                        $hay_error=1;
                    }else{                
                        
                    
                        //echo "$indice=>$tmp_name<br>";
                        /** Si tiene foto, la guardo **/
                        if ($_FILES["logo_file"]["error"]!=4)
                        {   
                            //$tmp_name = $_FILES["foto_file"]["tmp_name"];
                            $name_logo = $_COOKIE[id_empresa_web]."_".md5(rand(1,1000000))."_".modificar_nombre_imagenes($_FILES["logo_file"]["name"]);
                            $destino_name_logo="9/imagenes_logos/".$name_logo;
                            if (move_uploaded_file($_FILES["logo_file"]["tmp_name"], "../../../".$destino_name_logo))
                            {
                                if (($_POST["autoredimension"]) and ($tamanoLogo[1]>75))
                                {
                                    if ($imgRedimensionada=new SimpleImage("../../../".$destino_name_logo))
                                    {
                                        if ($imgRedimensionada->get_height()>75)
                                            $imgRedimensionada->fit_to_height(75);
                                        $imgRedimensionada->save();
                                    }
                                }
                            }else{
                                $destino_name_logo="";
                            }
                        }
                    }
                    //var_dump($tamanoLogo);Exit();

                }
            }
        }//if (!$hay_error)                                
       
        //verifica que no haya un sitio ya ingresado para la empresa
        $sql = "SELECT host, logo_file, publica_en_server_se, usuario_cpt from z9_sitios where cod_cliente=$_COOKIE[id_empresa_web]";
        //echo $sql;
        $result = $mysqli->query($sql);
        $myrow=$result->fetch_assoc();
        
        $logo_file="";

        if ( (!empty($myrow[logo_file])) and (@in_array("../../../".$myrow[logo_file],$_POST["archivosAEliminar"])) )
        {
            $array_archivosAEliminar[]="../../../".$myrow[logo_file];
            $myrow[logo_file]="";                    
        }

        if (empty($destino_name_logo) and (!$_POST[borrarLogoActual]))
        {
            $logo_file=$myrow[logo_file];            
        } 

        if (!empty($destino_name_logo))
            $logo_file=$destino_name_logo;                              
       

       
       $sql="update z9_sitios set
                     nombre_sitio='$_POST[nombre]',
                     logo_file='$logo_file'";
       if (empty($myrow[host]))
    		$sql.=",host='$_POST[host]'";		

       $sql.=" where cod_cliente=$_COOKIE[id_empresa_web]";

        
        
        //echo "$myrow[host] - $sql";    

        if ($result=$mysqli->query($sql))
        {


            $sql="update empresas set

                     e_mail_empresa='$_POST[e_mail]',
                     telefono_empresa='$_POST[telefono]',
                     direccion_empresa='$_POST[direccion]',
                     rs_facebook='$_POST[rs_facebook]',
                     rs_twitter='$_POST[rs_twitter]',
                     rs_linkedin='$_POST[rs_linkedin]',
                     rs_pinterest='$_POST[rs_pinterest]',
                     rs_google='$_POST[rs_google]'
       
                    where id_empresa=$_COOKIE[id_empresa_web]";

            if ($result=$mysqli->query($sql))
            {
            
                //veo si tiene activada la publicacion en servidores propios
                if ($myrow[publica_en_server_se] and (empty($myrow[usuario_cpt])) and (!empty($_POST["usuario_cpt"])))
                {
                    
                    $hostSE=$datosPublicacionServidorSE["host"]."/".$_POST["usuario_cpt"];
                    $sql_upd="update z9_sitios set
                                usuario_cpt='".$_POST["usuario_cpt"]."',
                                host='$hostSE',
                                max_size_mb_publicacion='".$datosPublicacionServidorSE["max_size_mb_publicacion"]."',
                               	host_o_ip_publicacion='".$datosPublicacionServidorSE["hostPublicacion"]."',
                               	carpeta_public='".$datosPublicacionServidorSE["carpeta"]."',
                               	usuario_publicacion='".encriptar_texto($datosPublicacionServidorSE["usuario"],md5($_COOKIE[id_empresa_web]))."',
                               	password_publicacion='".encriptar_texto($datosPublicacionServidorSE["contrasena"],md5($_COOKIE[id_empresa_web]))."'                           
                                                                                            
                                where cod_cliente=$_COOKIE[id_empresa_web]";
                    if ($result_upd=$mysqli->query($sql_upd))
                    {
                    }else{
                        //echo $sql_upd;
                        echo "<p class='alert alert-block alert-danger'> - No se ha podido guardar la dirección de publicación. Por favor vuelve a intentarlo, ya que de lo contrario no podrás publicar tu Sitio Express.</p>";
                        $hay_error=1;
                        
                    }
                }
                
                if (!empty($logo_file))
                    $logo_nombre="<img src='../../../$logo_file' class='ImagenLogo'>";
                else
                    $logo_nombre="$_POST[nombre]";
                ?>
                <p class=ok>Los datos han sido guardados con éxito.</p>
                <script>
                    parent.$(".__editar_en_linea_dato_sitio.__dato_logo").html("<?=$logo_nombre;?>");
                    parent.$(".__editar_en_linea_dato_sitio.__dato_telefono").html("<?=$_POST[telefono];?>");
                    parent.$(".__editar_en_linea_dato_sitio.__dato_nombre").html("<?=$_POST[nombre];?>");
                    parent.$(".__editar_en_linea_dato_sitio.__dato_email").html("<?=$_POST[e_mail];?>");
                    parent.$(".__editar_en_linea_dato_sitio.__dato_direccion").html("<?=$_POST[direccion];?>");
                </script>
                <?
                //var_dump($_POST);
                //echo "if( (!empty($myrow[logo_file]) and !empty($destino_name_logo)) or (!empty($myrow[logo_file]) and ($_POST[borrarLogoActual])) )";
                if( (!empty($myrow[logo_file]) and !empty($destino_name_logo)) or (!empty($myrow[logo_file]) and ($_POST[borrarLogoActual])) )
                    $array_archivosAEliminar[]="../../../".$myrow[logo_file];
    
            }else{        	
                echo "<p class='alert alert-block alert-danger'> - La edición de los datos de tu empresa NO se ha podido llevar a cabo.</p>";
                $hay_error=1;
                if (!empty($destino_name_logo))
                    $array_archivosAEliminar[]="../../../".$destino_name_logo;
            }
        }else{        	
            echo "<p class='alert alert-block alert-danger'> - La edición de los datos de tu sitio NO se ha podido llevar a cabo.</p>";
            $hay_error=1;
            if (!empty($destino_name_logo))
                $array_archivosAEliminar[]="../../../".$destino_name_logo;
        }        
        //var_dump($array_archivosAEliminar);
        
        if (@count($array_archivosAEliminar)>0)
        {
            foreach($array_archivosAEliminar as $archivoAEliminar)
            {
                //echo "<p>Borro ".$archivoAEliminar."</p>";
                unlink($archivoAEliminar);
            }
        }
        
        //exit();
        if (!$hay_error)
        {
        ?>
            <script>                    
                parent.$.fancybox.close();
            </script>
        
        <?
        }
        exit();
        
    
}// end if ($accion!="editar")


























$sql="select * from z9_sitios,empresas where cod_cliente=id_empresa and cod_cliente='".$_COOKIE["id_empresa_web"]."'";
$result=$mysqli->query($sql);
if ($myrow=$result->fetch_assoc())
{
	$subcarpeta_publicacion=dame_subcarpeta_publicacion($myrow[carpeta_public]);
	if ($subcarpeta_publicacion!="")
	{
		$subcarpeta_publicacion.="/";
	}
	$sitio_web_txt=$myrow[host].$subcarpeta_publicacion;        	
}else{
    ?>
    <p class='alert alert-block alert-danger'> - No se puede acceder a la información solicitada.</p>
    </div>
    </body>
    </html>
    <?
    exit();    
}


if ($myrow[publica_en_server_se])
{
    if ((empty($myrow[usuario_cpt])) and ($_GET[tipo]=="editar")) 
    {
        $labelSite="www.".$datosPublicacionServidorSE["host"];
        $inputSite='<input type="text" required id="usuario_cpt" value="" class="wInput" name="usuario_cpt" maxlength="20" title="Esta será la dirección web de tu página una vez publicada."/>';
    }else{
        if (empty($myrow[usuario_cpt]))
            $myrow[usuario_cpt]="No Determinado<div class='alert alert-block alert-warning'>Para publicar tu Sitio Express debe estar definido este campo</div>";

        $labelSite="Usuario";
        $inputSite=$myrow[usuario_cpt];

    }        
}else{
    if (empty($myrow[host]))
    {
        /*
    	//si no esta definido el host le permito cargarlo
        $sql_site = "SELECT web_site_empresa from empresas where id_empresa=$_COOKIE[id_empresa_web]";
        //echo $sql;
        $result_site = $mysqli->query($sql_site);
        $myrow_site=$result_site->fetch_assoc();
        $dominio=str_replace("http://","",$myrow_site[web_site_empresa]);
        $dominio=str_replace("www.","",$dominio);
        $sitio_web_txt=trim($dominio);
        */
        $labelSite="Sitio Web";
        $inputSite='<input type="text" required id="host" value="" name="host"  class="wInput"/>';
    
    }else{
        $labelSite="Sitio Web";
        $inputSite="<span class='wLabel'>".$myrow[host]."</span>";
    }
}
$logo=false;
if (!empty($myrow[logo_file]))
{
    $myrow[logo_file]="../".$myrow[logo_file];
    
    $logo="../../../timthumb/timthumb.php?src=$myrow[logo_file]&b=1&w=200&h=200&1=1";
    $logo="<img src='$logo'/><br><a href='#' onclick='eliminarLogoActual();' class='btn btn-eliminar'>Eliminar Logo Actual</a>";
    $txtNuevo="Nuevo ";
    
}


?>
<script>
function eliminarArchivo(archivo,id)
{
    $("#"+id).html("&lt;input name='archivosAEliminar[]' value='"+archivo+"' type='hidden' /&gt;&lt;p class=advertencia&gt;El archivo será eliminado cuando guarde la información.&lt;/p&gt;");
}
</script>
<form class="Wiframe-form" id="Formulario" method="post" action="?tipo=editar&accion=editar" enctype="multipart/form-data">
    

	<div class="Wiframe-header">
		<div class="Wiframe-title">
			Editar Datos de Mi Sitio
		</div>
	</div><!--/header-->

	<div class="Wiframe-options">

		<div class="wSection">
			<div class="wList">
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Nombre del Sitio
						</div>
						<input type="text" id="nombre_sitio" value="<?=$myrow[nombre_sitio];?>" name="nombre" class="wInput"/>
						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							<?=$labelSite;?>
						</div>
                        <?=$inputSite;?>						
					</div>
				</div>
                
			</div>                        
		</div><!--/wSection-->


		<div class="wSection">
			<div class="wSection-title">Datos de Contacto</div>
        
			<div class="wList">
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Mail de Contacto
						</div>
						<input type="text" id="e_mail" value="<?=$myrow[e_mail_empresa];?>" name="e_mail" class="wInput"/>
						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Teléfono
						</div>
						<input type="text" id="telefono" value="<?=$myrow[telefono_empresa];?>" name="telefono" class="wInput"/>
						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Dirección Postal
						</div>
						<input type="text" id="direccion" value="<?=$myrow[direccion_empresa];?>" name="direccion" class="wInput"/>
						
					</div>
				</div>


			</div>                        
		</div><!--/wSection-->
        
        
		<div class="wSection">
			<div class="wSection-title">Redes Sociales</div>
        
			<div class="wList">
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Facebook.com/
						</div>
						<input type="text" id="rs_facebook" value="<?=$myrow[rs_facebook];?>" name="rs_facebook" class="wInput"/>
						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Twitter.com/
						</div>
						<input type="text" id="rs_twitter" value="<?=$myrow[rs_twitter];?>" name="rs_twitter" class="wInput"/>						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Linkedin.com/
						</div>
						<input type="text" id="rs_linkedin" value="<?=$myrow[rs_linkedin];?>" name="rs_linkedin" class="wInput"/>						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Pinterest.com/
						</div>
						<input type="text" id="rs_pinterest" value="<?=$myrow[rs_pinterest];?>" name="rs_pinterest" class="wInput"/>						
					</div>
				</div>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Plus.Google.com/
						</div>
						<input type="text" id="rs_google" value="<?=$myrow[rs_google];?>" name="rs_google" class="wInput"/>						
					</div>
				</div>

			</div>                        
		</div><!--/wSection-->  
        
        
		<div class="wSection">
			<div class="wList">
                <?
                if ($logo)
                {
                ?>
				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							Imagen Actual
						</div>
                        <?=$logo?>
						
						
					</div>
				</div>
                <?
                }//if ($logo)
                ?>

				<div class="wItem wItem--input">
					<div class="wItem-wrapper">
						<div class="wItem-name">
							<?=$txtNuevo;?> Logo
						</div>
						<input type="file" id="logo_file" class="wInput" name="logo_file"/><br /><br />
					    <div><input checked='checked' type='checkbox' value='1' name='autoredimension' />Redimensionar Nuevo Logo a Tamaño Recomendable (75px de alto)</div>	
					</div>
				</div>



			</div>                        
		</div><!--/wSection-->              
        
	</div><!--/options-->

	<div class="Wiframe-footer">
		<input type="submit" value="Guardar" class="Wiframe-submit">
	</div><!--/footer-->                
    
</form>                    
<?




    if (($myrow[publica_en_server_se]) and (empty($myrow[usuario_cpt])))
    {
        ?>
        <script>
            $(document).ready(function(){
                $("#campo_usuario_cpt").click(function(){
                    $("#usuario_cpt").focus();
                });
                $("#usuario_cpt").focus(function(){
                    if ($('#usuario_cpt').val().length==0)
                    {                    
                        $.post("utilidades.php",{tipo:'modrewrite',texto:$('#nombre_sitio').val()},function(data){
                            $('#usuario_cpt').val(data);
                        });
                    }
                });
                $("#Formulario").validate({
rules: {
    usuario_cpt: {
      required: true,
      remote: "checkear_usuario_se_demo.php"
    }
},

messages: {
    usuario_cpt: {
      required: "Ingresa un nombre de usuario",
      remote: "El nombre no es valido. Recuerda que solo debe contener letras en minúsculas y números. No pueden incluirse espacios"
    }
}                  
                });                
            });                                  
        </script>
        <?
    }    
    
    ?>
    <script>
    function eliminarLogoActual(){
        $("#item_logo_actual").hide(500);
        $("#Formulario").append("<input type='hidden' name='borrarLogoActual' value='1'/>");
        alert("Para finalizar con el borrado del logo debe guardar los datos");
    } 
    </script>
    <?




?>
</div>
</body>
</html>