<?

include ("../config.inc.php");
$db = mysql_connect($cfg_host, $cfg_usuario,$cfg_password);
mysql_select_db($cfg_base,$db);    
    
    $rta=true;
    
    $usuario_cpt_aux=preg_replace('([^A-Za-z0-9_-])', '', strtolower($_REQUEST[usuario_cpt]));
    if ($usuario_cpt_aux!=$_REQUEST[usuario_cpt])
    {
        $rta=false;
    }else{
        //veo que no exista otro nombre
        $sql_comp="select id_sitio from z9_sitios where '$_REQUEST[usuario_cpt]'=usuario_cpt";
        //echo $sql_comp;
        $result_comp=$mysqli->query($sql_comp);
        if ($myrow_comp=$result_comp->fetch_assoc())
        {
           $rta=false;
        }        
    }
    
    //$rta=false;
    echo json_encode($rta);
?>