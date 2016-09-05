<?
/**
 * 
 *
 * http://www.misaplicaciones.com/api/SISTEMA/ACCIOON/?id_empresa=4&campos=cntProductos,link 
 * 
 * 
 **/

header('Content-Type: application/json');

$considerar_link_aplic_base_predet=true;

include_once("funciones.php");
include_once("../funciones/api.php");
include ("../config.inc.php");

$camposAMostrar=explode(",",$_GET[campos]);

//var_dump($camposAMostrar);
$leng=$_GET[leng];
if (empty($leng))
    $leng="es";


if ($_GET[sistema]=="blog")
{   
    if ($_GET[accion]=="notas")
    {
        $sql_notas = "select id_nota,titulo ";
        if (!empty($_GET[campos]))
            $sql_notas.=",".$_GET[campos]; 
        $sql_notas.= " from z1_notas where id_empresa = '$_GET[id_empresa]' ";
        
        if ($_GET[cod_categoria]>0)
            $sql_notas.=" and id_catego=$_GET[cod_categoria]";
        

        if (isset($_GET[not_ids]))
            $sql_notas.=" and id_nota not in (".$_GET[not_ids].")";

        if (isset($_GET["desde"]))
            $desde=$_GET["desde"].",";
        

            
        $sql_notas.=" order by fecha desc ";

        if (isset($_GET[limit]))
        {
            if ($_GET[limit]>0)
                $sql_notas.=" limit $desde $_GET[limit]";
        }else
                $sql_notas.=" limit $desde 4";
        
            
        if (!isset($_GET[cnt_caracteres]))
        {
            //echo "no esta definido";
            $_GET[cnt_caracteres]=100;
        }
                                
        if ($result_notas=$mysqli->query($sql_notas))
        {        
            if ($myrow_notas = $result_notas->fetch_assoc())
            {
                
                $cnt=0;
    
                do{                
                    
                   
                    $myrow_notas["linkNota"] ="$link_aplic_base/1/$_GET[id_empresa]/$leng/detalle/$myrow_notas[id_nota]/".armar_texto_modrewrite($myrow_notas[titulo],"_").".php";
                    
                    if ($_GET["mostrar_foto"])
                    {
                        $image_file="../imagenes/notas/".$myrow_notas[id_nota].".jpg";
                        if (file_exists($image_file))
                        {                
                            if (isset($_GET[configFoto]))                            
                                $myrow_notas[foto_file]=$link_aplic_base."img/notas/".$_GET[configFoto][ancho]."/".$_GET[configFoto][alto]."/".$_GET[configFoto][crop]."/".$myrow_notas[id_nota].".jpg";
                            else
                                $myrow_notas[foto_file]=$link_aplic_base."img/notas/".$myrow_notas[id_nota].".jpg";
                        //unset($myrow_notas[foto_file])
                        }else{
                            if (isset($_GET[configFoto]))
                                $myrow_notas[foto_file]="$link_aplic_base/timthumb/timthumb.php?src=../images/no-imagen.jpg&w=".$_GET[configFoto][ancho]."&h=".$_GET[configFoto][alto]."&zc=".$_GET[configFoto][crop];
                            else
                                $myrow_notas[foto_file]=$link_aplic_base."/images/no-imagen.jpg";
                            
                        }
                    }
                    
                    $myrow_notas[texto]=strip_tags($myrow_notas[texto]);
                    if ($_GET[cnt_caracteres]>0)
                        $myrow_notas[texto]=substr($myrow_notas[texto],0,$_GET[cnt_caracteres])."...";
                    
                    
                    $data[$cnt]=$myrow_notas;
                    

                    $cnt++;
                                        
                        
                }while ($myrow_notas = $result_notas->fetch_assoc());
                    
                
            } 
        }else
            $data["error"]=$mysqli->error;

    }            
    
}//end if blog 







































if ($_GET[sistema]=="tienda")
{
    if ($_GET[accion]=="carrito")
    {
        
        $data["estado"]=true;
        session_start();
        
        if ($_GET[id_empresa]<=0){
            $data["estado"]=false;
            $data["mensaje"]="No esta definido el identificador de la empresa";
        }else{        
            if (!empty($_GET[id_articulo]))
            {            
                if (!isset($_GET[cantidad]))
                {
                    $data["estado"]=false;
                    $data["mensaje"]="No esta definida la cantidad";   
                }
                   
                if ($data["estado"])
                {
                    
                    $sql_comp="select control_stock, stock, cant_minima, nombre, precio, simbolo, cant_decimales, separador_decimal, separador_miles
                                 from z6_config, z6_articulos, z6_monedas
                                 where z6_articulos.id_empresa=z6_config.id_empresa and id_moneda=cod_moneda 
                                       and z6_articulos.id_empresa='$_GET[id_empresa]' and id_articulo=$_GET[id_articulo]";
                    
                    $result_comp=$mysqli->query($sql_comp);
                    
                    if ($myrow_comp = $result_comp->fetch_assoc())
                    {
                        
                        if ($_GET[cantidad]==0)
                        {
                            $sql = "delete from z6_pedidos where cod_articulo=$_GET[id_articulo] and cod_session = '".session_id()."'";
                            $txtExito="Se elimino del pedido el producto";                           
                            
                        }else{
                                            
                            $sql = "";
                            if (isset($_GET[no_sumar]))
                            {                    
                                $sql.=" cantidad = $_GET[cantidad]";
                            }else{
                                $sql.=" cantidad = cantidad+$_GET[cantidad]";
                            }
                                            
                            $sql.= ", nombre_articulo='$myrow_comp[nombre]'	
                                    , precio_unitario='$myrow_comp[precio]'
                                    , cod_empresa=$_GET[id_empresa]
                                    , cod_articulo=$_GET[id_articulo]
                                    , fecha=NOW()
                                    , ip='".getenv("REMOTE_ADDR")."'
                                    , cod_session = '".session_id()."'";
                                    
                                    
                            //veo si existe el producto
                            $sql_existe="select id_pedido, cantidad from z6_pedidos where cod_session = '".session_id()."' and cod_articulo=$_GET[id_articulo]";
                            $result_existe = $mysqli->query($sql_existe);
                            if ($myrow_existe = $result_existe->fetch_assoc())
                            {
                                $sql = "update z6_pedidos set ".$sql." where id_pedido=$myrow_existe[id_pedido]";     
                                $txtExito="Se actualizo la cantidad del pedido para el producto";                           
                            }else{
                                $txtExito="Se agrego el pedido para el producto";                           
                                $sql = "insert into z6_pedidos set ".$sql;
                            }

                            if (isset($_GET[no_sumar]))
                            {                                 
                                $cantidadActualizada=$_GET[cantidad];
                            }else{
                                $cantidadActualizada=$myrow_existe[cantidad]+$_GET[cantidad];
                            }     
                            /*
                            $data[cantidadActualizada]=$cantidadActualizada;
                            $data[control_stock]=$myrow_comp[control_stock];
                            $data[stock]=$myrow_comp[stock];
                            */
                            if (($myrow_comp[control_stock]) and ($myrow_comp[stock]<$cantidadActualizada))
                            {
                                $data["estado"]=false;
                                $data["mensaje"]="El producto no esta en stock";   
                                //sin stock
                            }                                                        
                            if (($myrow_comp[cant_minima]>0) and ($cantidadActualizada<$myrow_comp[cant_minima]))
                            {
                                $data["estado"]=false;
                                $data["mensaje"]="Tienes que comprar al menos $myrow_comp[cant_minima] unidades de este producto";   
                                //sin stock
                            }                                                        
                           
                        }                    
                        //echo $sql;
                        if ($data["estado"])
                        {
                            if ($result = $mysqli->query($sql))
                            {
                                $data=dameInfoCarrito(session_id(),$_GET[id_empresa],$myrow_comp);
                                $data["estado"]=true;
                                $data["mensaje"]=$txtExito;   
                                $data["moneda"]=$myrow_comp[simbolo];                    
                            }else{
                               $data["estado"]=false; 
                               $data["mensaje"]="No se pudo actualizar la cantidad";   
                            }
                        }
                        //$data["sql"]=$sql; 
                    }else{
                       $data["estado"]=false; 
                       $data["mensaje"]="No se encontro información del producto";   
                    } 
                }      
            }else{        
                
                $sql_comp="select simbolo, cant_decimales, separador_decimal, separador_miles
                             from z6_config, z6_monedas
                             where id_moneda=cod_moneda and id_empresa='$_GET[id_empresa]'";
                
                $result_comp=$mysqli->query($sql_comp);
                
                if ($myrow_comp = $result_comp->fetch_assoc())
                {                
                    $data=dameInfoCarrito(session_id(),$_GET[id_empresa],$myrow_comp);
                    $data["estado"]=true;
                    $data["moneda"]=$myrow_comp[simbolo];                    
                }else{
                   $data["estado"]=false; 
                   $data["mensaje"]="No se encontro información de la moneda";   
                }                                    
            } 
        }      

    }//if ($_GET[accion]=="carrito") 
    
    
    
    
    if ($_GET[accion]=="aplicar_cupon")
    {
        
        session_start();
        
        include_once("../funciones/6.php");
        
        $data=aplicarCupon($_GET[id_empresa],$_GET[cupon]);
        
    }//if ($_GET[accion]=="aplicar_cupon") 
    
        
    
    
    
    
    
    if ($_GET[accion]=="categorias")
    {

        if (in_array("cntProductos",$camposAMostrar))
            $sql_aux=", (select count(*) from z6_articulos where z6_articulos.id_categoria=z6_categorias.id_categoria) as cntProductos";        

        $sql_categorias = "select *$sql_aux from z6_categorias where id_empresa = '$_GET[id_empresa]' and id_padre=0 order by orden_categoria";
        $result_categorias=$mysqli->query($sql_categorias);
        
        if ($myrow_categorias = $result_categorias->fetch_assoc())
        {
            
            $cnt=0;

            do{                
                
                $link_productos_categoria =$link_aplic_base."6/$_GET[id_empresa]/$leng/listado/$myrow_categorias[id_categoria]/".armar_texto_modrewrite($myrow_categorias[desc_catego],"_").".php";                
                $link_categoria =$link_aplic_base."6/$_GET[id_empresa]/$leng/categorias/$myrow_categorias[id_categoria]/".armar_texto_modrewrite($myrow_categorias[desc_catego],"_").".php";

                
                $data[$cnt]["id"]=$myrow_categorias[id_categoria];
                $data[$cnt]["nombre"]=utf8_encode($myrow_categorias[desc_catego]);
                if (in_array("link",$camposAMostrar))
                    $data[$cnt]["link"]=$link_categoria;
                $data[$cnt]["linkProductos"]=$link_productos_categoria;
                
                if (in_array("cntProductos",$camposAMostrar))
                    $data[$cnt]["cntProductos"]=$myrow_categorias[cntProductos];;
                
                
                $sql_subcategorias = "select *$sql_aux from z6_categorias where id_empresa = '$_GET[id_empresa]' and id_padre=$myrow_categorias[id_categoria] order by orden_categoria";
                $result_subcategorias=$mysqli->query($sql_subcategorias);

                while ($myrow_subcategorias = $result_subcategorias->fetch_assoc())
                {                    
                    
                    $link_productos_categoria =$link_aplic_base."6/$_GET[id_empresa]/$leng/listado/$myrow_subcategorias[id_categoria]/".armar_texto_modrewrite($myrow_subcategorias[desc_catego],"_").".php";                
                    $link_categoria =$link_aplic_base."6/$_GET[id_empresa]/$leng/categorias/$myrow_subcategorias[id_categoria]/".armar_texto_modrewrite($myrow_subcategorias[desc_catego],"_").".php";
                                                            
                    $subcategoria=array();
                    $subcategoria["id"]=$myrow_subcategorias[id_categoria];
                    $subcategoria["nombre"]=utf8_encode($myrow_subcategorias[desc_catego]);
                    if (in_array("link",$camposAMostrar))
                        $subcategoria["link"]=$link_categoria;
                    $subcategoria["linkProductos"]=$link_productos_categoria;

                    if (in_array("cntProductos",$camposAMostrar))
                        $subcategoria["cntProductos"]=$myrow_subcategorias[cntProductos];;

                    $data[$cnt]["subcategorias"][]=$subcategoria;
                }
                    
                $cnt++;
                    
            }while ($myrow_categorias = $result_categorias->fetch_assoc());
                
            
        } 

    }//if ($_GET[accion]=="categorias")     
    
    
    if ($_GET[accion]=="productos")
    {

        if (strpos($_GET[campos],"precio")!==false)
        {
            //busco la moneda
            $sql_config = "select simbolo,cant_decimales,separador_decimal,separador_miles, carrito 
                           from z6_config, z6_monedas where cod_moneda = id_moneda and id_empresa = '$_GET[id_empresa]'";
            $result_config = mysql_query($sql_config);
            $myrow_config = mysql_fetch_assoc($result_config);                                   
            
            //var_dump($myrow_config);
            //$data["carrito"]=$myrow_config["carrito"];

        }
        
        
        if ($_GET["mostrar_foto"])
        {
            $sql_aux_from=" left join z6_fotos on (z6_articulos.id_articulo=z6_fotos.id_articulo and foto_primaria=1)";
            $sql_aux_foto=",foto_file";
        }
        $sql_productos = "select z6_articulos.id_articulo,nombre ";
        if (!empty($_GET[campos]))
            $sql_productos.=",".$_GET[campos]; 
        $sql_productos.= "$sql_aux_foto from z6_articulos $sql_aux_from where z6_articulos.id_empresa = '$_GET[id_empresa]' ";
        
        if ($_GET[cod_categoria]>0)
            $sql_productos.=" and id_categoria=$_GET[cod_categoria]";

        if ($_GET[cod_estado]>0)
            $sql_productos.=" and cod_estado=$_GET[cod_estado]";
        
        if (isset($_GET["desde"]))
            $desde=$_GET["desde"].",";
        
        if (isset($_GET[limit]))
        {
            if ($_GET[limit]>0)
                $sql_productos.=" limit $desde $_GET[limit]";
        }else
                $sql_productos.=" limit $desde 4";
            
        //echo $sql_productos;            
        if ($result_productos=$mysqli->query($sql_productos))
        {        
            if ($myrow_productos = $result_productos->fetch_assoc())
            {
                
                $cnt=0;
    
                do{   
                    
                   
                    $myrow_productos["linkProducto"] =$link_aplic_base."6/$_GET[id_empresa]/$leng/detalle/$myrow_productos[id_articulo]/".armar_texto_modrewrite($myrow_productos[nombre],"_").".php";
                    
                    if ($_GET["mostrar_foto"])
                    {
                        if (!empty($myrow_productos[foto_file]))
                        {
                            if (isset($_GET[configFoto]))                            
                                $myrow_productos[foto_file]=$link_aplic_base."img/catalogo/".$_GET[configFoto][ancho]."/".$_GET[configFoto][alto]."/".$_GET[configFoto][crop]."/".$myrow_productos[foto_file];
                            else
                                $myrow_productos[foto_file]=$link_aplic_base."img/catalogo/".$myrow_productos[foto_file];
                        //unset($myrow_productos[foto_file])
                        }else{
                            if (isset($_GET[configFoto]))
                                $myrow_productos[foto_file]="$link_aplic_base/timthumb/timthumb.php?src=../images/no-imagen.jpg&w=".$_GET[configFoto][ancho]."&h=".$_GET[configFoto][alto]."&zc=".$_GET[configFoto][crop];
                            else
                                $myrow_productos[foto_file]=$link_aplic_base."/images/no-imagen.jpg";
                            
                        }
                    }
                    
                    if (isset($myrow_productos[precio]))
                      $myrow_productos[precio] = number_format($myrow_productos[precio],$myrow_config[cant_decimales],$myrow_config[separador_decimal],$myrow_config[separador_miles]);
                    
                    if (empty($myrow_productos[precio]))
                        unset($myrow_productos[precio]);
                    
                    if (($_GET[cnt_caracteres]>0) and (strlen($myrow_productos[descripcion])>$_GET[cnt_caracteres]))
                        $myrow_productos[descripcion]=substr(strip_tags($myrow_productos[descripcion]),0,$_GET[cnt_caracteres])."...";
                    
                    $myrow_productos["moneda"]=$myrow_config[simbolo];
                    
                    
                    //$myrow_productos[nombre]=utf8_encode($myrow_productos[nombre]);
                    
                    $data[$cnt]=$myrow_productos;
                    
                    $cnt++;

                        
                }while ($myrow_productos = $result_productos->fetch_assoc());
                    
                
            } 
        }else
            $data["error"]=$mysqli->error;

    }        
}


/*
$cnt=0;

$data[$cnt]["nombre"]=$_GET[id_empresa]." - Titulo $cnt";
$data[$cnt]["link"]="http://www.pcsanabria.com.ar/$cnt/";
$cnt++;

$data[$cnt]["nombre"]=$_GET[id_empresa]." - Titulo $cnt";
$data[$cnt]["link"]="http://www.pcsanabria.com.ar/$cnt/";
$cnt++;

$data[$cnt]["nombre"]="Titulo $cnt";
$data[$cnt]["link"]="http://www.pcsanabria.com.ar/$cnt/";
$cnt++;

$data[$cnt]["nombre"]="Titulo $cnt";
$data[$cnt]["link"]="http://www.pcsanabria.com.ar/$cnt/";
$cnt++;
*/

$data=@utf8_encode_array($data);

$json=json_encode($data);


$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;
 
# If a JSONP callback was specified, print the JSON data surrounded in that,
# otherwise just print out the JSON data.
# 
# Specifying no callback param would print: {"some_key": "some_value"}
# But specifying ?callback=foo would print: foo({"some_key": "some_value"})
print $jsonp_callback ? "$jsonp_callback($json)" : $json;

exit();

?>

<script>
$(document).ready(function(){
$.getJSON( "http://www.misaplicaciones.com/api/index.php?id_empresa=4&callback=?", function( data ) {
  var items = [];
  $.each( data, function( key, val ) {
    items.push( "<li id='" + key + "'>" + val.nombre + "</li>" );
  });
 
  $( "<ul/>", {
    "class": "my-new-list",
    html: items.join( "" )
  }).appendTo( "#categorias-fecha" );
});
});
</script>
<p>inicio</p>
<div id="categorias-fecha">
<p>adentro</p>
</div>
<p>fin</p>