<?
include_once "../../../config.inc.php";
$sql_disenos="select cod_template, id_diseno, desc_diseno 
                from z9_disenos, z9_disenos_rubros, z9_templates_implementaciones
                where cod_diseno=id_diseno and cod_implementacion=id_implementacion";
if ($_POST[id_rubro])
    $sql_disenos.=" and cod_rubro=$_POST[id_rubro]";
$sql_disenos.=" group by id_diseno order by rand() ";
//echo $sql_disenos;
$result_disenos=$mysqli->query($sql_disenos);
if ($myrow_disenos=$result_disenos->fetch_assoc())
{
    do{
    #COMENTO ESTA LINEA, ESTOY PROBANDO EL WIZARD CON EL EFECTO HOVER Y EL RESIZE QUE TIENE ME LO IMPIDE
    #$file="../timthumb/timthumb.php?src=../9/templates_sitios_dinamicos/1/template.jpg&w=480&h=390&z=1";
    //$_POST[id_rubro]=1;
    $file="../9/templates_sitios_dinamicos/".$myrow_disenos[cod_template]."/disenos/".$myrow_disenos["id_diseno"].".jpg";
    ?>
	<label class="Template" for="diseno-<?=$myrow_disenos["id_diseno"]?>" style="background-image:url(<?=$file;?>);">
		<span class="Template-nombre"><?=utf8_encode($myrow_disenos["desc_diseno"]);?></span>
	</label>
    <input type='radio' name='cod_diseno' value='<?=$myrow_disenos["id_diseno"];?>' class='wHide' id='diseno-<?=$myrow_disenos["id_diseno"];?>'/>    
    <?
    }while ($myrow_disenos=$result_disenos->fetch_assoc());
}else{
    echo "<div>Actualmente no tenemos dise&ntilde;os para esta categoria. <br>
               Prueba con otra y recuerda que puedes modificar todas las fotos y contenidos</div>";
}
?>
