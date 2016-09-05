<?
header("Content-Type: text/html; charset=iso-8859-1");

include "../../../funciones.php";
include ("../../../config.inc.php");
$db = mysql_connect($cfg_host, $cfg_usuario,$cfg_password);
mysql_select_db($cfg_base,$db);


if ($_REQUEST[tipo]=="modrewrite")
{
    echo armar_texto_modrewrite($_POST[texto]);
    exit();
}



















































































?>