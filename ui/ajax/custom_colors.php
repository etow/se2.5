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




//include "../inicio_admin.php";


?>
<body>

<div id="fancybox-widget" class="Wiframe">
<?









    
    
if (($_GET[accion]=="editar") and ($_GET[tipo]=="editar"))    
{

        
        if (empty($_POST[color_custom_primario]))
        {
           $error_txt.="<p class='alert alert-block alert-danger'> - Debes ingresar el color primario</p>";
           $hay_error=1;
        }        

        if (empty($_POST[color_custom_claro]))
        {
           $error_txt.="<p class='alert alert-block alert-danger'> - Debes ingresar el color primario</p>";
           $hay_error=1;
        }        


        if (empty($_POST[color_custom_oscuro]))
        {
           $error_txt.="<p class='alert alert-block alert-danger'> - Debes ingresar el color primario</p>";
           $hay_error=1;
        }        

        if (empty($_POST[color_custom_acento_1]))
        {
           $error_txt.="<p class='alert alert-block alert-danger'> - Debes ingresar el color de acento primario</p>";
           $hay_error=1;
        }        

        if (empty($_POST[color_custom_acento_2]))
        {
           $error_txt.="<p class='alert alert-block alert-danger'> - Debes ingresar el color de acento secundario</p>";
           $hay_error=1;
        }        

    
        if ($hay_error==1)
        {
           echo $error_txt;
           exit();
        }
        
        
       $sql="update z9_config set
                     color_custom_primario='$_POST[color_custom_primario]',
                     color_custom_claro='$_POST[color_custom_claro]',
                     color_custom_oscuro='$_POST[color_custom_oscuro]',
                     color_custom_acento_1='$_POST[color_custom_acento_1]',
                     color_custom_acento_2='$_POST[color_custom_acento_2]', 
                     color_custom_callTo='$_POST[color_custom_callTo]'where id_empresa=$_COOKIE[id_empresa_web]";

        if ($result=$mysqli->query($sql))
        {


                $paramtrosColoresCustom="primario=".$_POST[color_custom_primario].
                "&claro=".$_POST[color_custom_claro]. 
                "&oscuro=".$_POST[color_custom_oscuro].
                "&acento1=".$_POST[color_custom_acento_1].
                "&acento2=".$_POST[color_custom_acento_2].
                "&callTo=".$_POST[color_custom_callTo];
    
                $href.=$GLOBALS["base_url"].'/9/recursos/colores/color_custom.php?'.$paramtrosColoresCustom;            

            ?>
                <p class=ok>Los datos han sido guardados con éxito.</p>
                <script>
                    parent.$("head #PaletaDeColor").attr("data-color","custom");
                    parent.$("head #PaletaDeColor").attr("href","<?=$href;?>");
                </script>
                <?
        }else{        	
            echo "<p class='alert alert-block alert-danger'> - La edición de los datos de tu Paleta de colores personzalizada NO se ha podido llevar a cabo.</p>";
            $hay_error=1;
        }        

        
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


























$sql="select * from z9_config where id_empresa='".$_COOKIE["id_empresa_web"]."'";
$result=$mysqli->query($sql);
if ($myrow=$result->fetch_assoc())
{
    if (!empty($myrow["color_custom_primario"]) or !empty($myrow["color_custom_primario"]))
    {
        
    }else{
        //cargo los get
        $myrow["color_custom_primario"]=str_replace("#","",$_GET["primario"]);
        $myrow["color_custom_claro"]=str_replace("#","",$_GET["claro"]);
        $myrow["color_custom_oscuro"]=str_replace("#","",$_GET["oscuro"]);
        $myrow["color_custom_acento_1"]=str_replace("#","",$_GET["acento1"]);
        $myrow["color_custom_acento_2"]=str_replace("#","",$_GET["acento2"]); 
        $myrow["color_custom_callTo"]=str_replace("#","",$_GET["callTo"]);              
    }
     
}


?>
<form class="Wiframe-form" id="formWidgetMapa" method="post" action="?tipo=editar&accion=editar" enctype="multipart/form-data">
    

	<div class="Wiframe-header">
		<div class="Wiframe-title">
			Configurar Paleta de Colores Personalizada
		</div>
	</div><!--/header-->

	<div class="Wiframe-options">

		<div class="wSection">
			<div class="wList">

				  <div class="wItem wItem--input">
						<div class="wItem-wrapper">
							<div class="wItem-name">
								Color Primario
							</div>
                            <input type="text" id="color_custom_primario" class="wInput pickerColor"  name="color_custom_primario" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_primario"]?>"/>
							
							<div class="wNota">Se aplica al color de fuente de los textos del sitio</div>
						</div>
					</div>


                    <div class="wItem wItem--input">
                    	<div class="wItem-wrapper">
                    		<div class="wItem-name">
                    			Color Claro
                    		</div>
                            <input type="text" id="color_custom_claro" class="wInput pickerColor" name="color_custom_claro" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_claro"]?>"/>
                    		
                    		<div class="wNota">Se aplica para el fondo del sitio.<br />Tambien es el color de la fuente en la opción de luminosidad oscura.</div>
                    	</div>
                    </div>


				    <div class="wItem wItem--input">
						<div class="wItem-wrapper">
							<div class="wItem-name">
								Color Oscuro
							</div>
                            <input type="text" id="color_custom_oscuro" class="wInput pickerColor" name="color_custom_oscuro" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_oscuro"]?>"/>							
							<div class="wNota">Se aplica en:<br />Color de la fuente de los titulos,<br />Color de fondo al footer,<br />Alternativa de fondo oscuro en la opción de luminosidad.</div>
						</div>
					</div>


                
			</div>                        
		</div><!--/wSection-->


		<div class="wSection">
			<div class="wList">



                    <div class="wItem wItem--input">
                    	<div class="wItem-wrapper">
                    		<div class="wItem-name">
                    			Color de Acento Primario
                    		</div>
                            <input type="text" id="color_custom_acento_1" class="wInput pickerColor"  name="color_custom_acento_1" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_acento_1"]?>"/>
                    		<div class="wNota">Da la nota de color al sitio.<br />Se aplica en botones, iconos.</div>
                    	</div>
                    </div>


                    <div class="wItem wItem--input">
                    	<div class="wItem-wrapper">
                    		<div class="wItem-name">
                    			Color de Acento Secundario
                    		</div>
                            <input type="text" id="color_custom_acento_2" class="wInput pickerColor"  name="color_custom_acento_2" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_acento_2"]?>"/>
                    		<div class="wNota">Sirve como alternativa de acento.<br />Se aplica en elementos secundarios ej: links, links del footer.</div>
                    	</div>
                    </div>


                    <div class="wItem wItem--input">
                    	<div class="wItem-wrapper">
                    		<div class="wItem-name">
                    			Color de Call To Action
                    		</div>
                            <input type="text" id="color_custom_callTo" class="wInput pickerColor"  name="color_custom_callTo" required="required" size="14" maxlength="7" value="<?=$myrow["color_custom_callTo"]?>"/>
                    	</div>
                    </div>
                    
                    
                    


                
			</div>                        
		</div><!--/wSection-->


	</div><!--/options-->

	<div class="Wiframe-footer">
		<input type="submit" value="Guardar" class="Wiframe-submit">
	</div><!--/footer-->                
    
</form>                    
</div>
</body>
</html>