<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />

	<title>Crear Usuario</title>
    <style>
    body{
        background: #efefef;
        font-family: verdana;
        font-size: 13px;
    }
    form,.resultado{
        width: 50%;
        margin: 0 auto;
        background: #fff;
        border:1px solid #ccc;
        padding: 4em;
    
    }
    input{
        display: block;
    }
    input[type="text"],input[type="email"]{
        width: 50%;
        padding: 1em;
        margin-bottom: 1em;
    }
    input[type="submit"]{
        background: #999;
        color:#fff;
        padding: 1em 3em;
        border: 3px solid #000;
        font-size: 1.3em;
        text-transform: uppercase;
    }
    .resultado{
        margin: 1em auto;
        background: #e0cdce;
    }
    .resultado-200{
        background: #d0e5c9;
    }
    </style>
</head>

<body>


<?

if (isset($_POST) and ($_GET[crear]))
{
$url="http://app.misaplicaciones.com/api/script.php?idua=1&hash=d9da84c37017992bfae1ac2deb9ba830";
//$url="http://server/desarrollo/ProgramacionAvanzada/misaplicaciones.com/api/script.php?idua=1&hash=d9da84c37017992bfae1ac2deb9ba830";

//$parametros["ejecucion_modo_prueba"]=1;
$parametros["comando"]="crear_empresa";
$parametros["usuario_empresa"]=$_POST["usuario_empresa"];
$parametros["contrasena_empresa"]=$_POST["contrasena_empresa"];
$parametros["e_mail_empresa"]=$_POST["e_mail_empresa"];
$parametros["nombre_empresa"]=$_POST["nombre_empresa"];

/*
$parametros["direccion"]="Cerviño 5478";
$parametros["telefono"]="5254 0406";
*/
//cantidad_x_modulo


//$parametros["id_cliente_whmcs"]="crear_empresa";
//$parametros["serviceid_whmcs"]="crear_empresa";

/*

$parametros=array();
$parametros["comando"]="borrar_empresa";
$parametros["id_empresa"]=62;
*/






foreach ($parametros as $parametro=>$valor)
{
    $parametros_url.="&$parametro=".urlencode($valor);
}



$urlFinal=$url.$parametros_url;

$urlFinal.="&cantidad_x_modulo[1]=25&cantidad_x_modulo[6]=25";
//$urlFinal.="&datosAuxuliaresModulo[9][host]=picoypala.com.ar&datosAuxuliaresModulo[9][carpeta_public]=".urlencode("public_html/".$parametros["usuario_empresa"]);

if ($_POST[demo])
    $urlFinal.="&datosAuxuliaresModulo[9][publica_en_server_se]=1";

echo $urlFinal;

$JSON=file_get_contents($urlFinal);
//echo "<textarea style='    width: 100%;    height: 200px;'>$JSON</textarea><br>";
$rta=json_decode($JSON,true);


echo "<div class='resultado resultado-$rta[codigo]'>";
//echo "<strong>URL</strong>: $urlFinal<br>";
echo "<h2>Respuesta</h2>";
echo "<pre>";
var_dump($rta);
echo "</pre>";
echo "</div>";

}
?>
 
<form method="post" action="?crear=1">
<h1>Crear Usuario</h1>
<input type="text" required="" placeholder="Nombre" name="nombre_empresa"/>
<input type="email" required="" placeholder="Email" name="e_mail_empresa"/>
<input type="text" required="" placeholder="Usuario" name="usuario_empresa"/>
<input type="text" required=""placeholder="Password" name="contrasena_empresa"/>
<input type="checkbox" style="display: inline-block;" name="demo" value="1" checked=""/>Usuario de Demo
<br /><br />
<input type="submit" value="Crear Demo" />
</form>



</body>
</html>