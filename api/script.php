<?
set_time_limit(9000);
header('Content-Type: application/json');


include ("funciones.php");
include ("../funciones/se.php");
include ("../config.inc.php");
if ($mysqli=new mysqli($cfg_host, $cfg_usuario,$cfg_password,$cfg_base))
{
}else{
	exit("");
}


/*
if ($mysqli_old=new mysqli($cfg_host_aplic_old, $cfg_usuario_aplic_old,$cfg_password_aplic_old,$cfg_base_aplic_old))
{
}else{
	exit("error old db");
}
*/



//valido los datos de acceso

$validar=true;

$comando=$_GET[comando];

if ($comando=="obtener_datos_revendedor")
    $validar=false;

if (($comando=="borrar_modulo")and($_GET[flag_ejecucion_info]==1))
{
	//permite la ejecucion pq infocomercial trata de borrar el modulo
	$no_controlar_comando_y_permisoapi=1;
}


if ($validar)    
{    
    $sql_comp="select permisos, activo 
               from z0_user_api 
               where id_user_api='$_GET[idua]' and md5(hash)='$_GET[hash]'";
    //echo $sql_comp;
    if ($result_comp=$mysqli->query($sql_comp))
    {
        if ($myrow_comp=$result_comp->fetch_assoc())
        {    		
        	if ($myrow_comp[activo]!=1)
        	{
    			$codigo_rta=306;
    			$mensaje_rta="No tiene permiso para ejecutar la api. No pudo efectuarse la validación.";
    			$comando="";    		
        	}	
        	if ((strpos($comando,$myrow_comp[permisos])===false) and ($no_controlar_comando_y_permisoapi!=1) and ($myrow_comp[permisos]!="*")) 
        	{
        		$codigo_rta=305;
        		$mensaje_rta="No tiene permiso para ejecutar el comando solicitado. No pudo efectuarse la validación.";
        		$comando="";
        	
        	}	
        }else{
        	$codigo_rta=300;
        	$mensaje_rta="Los datos pasados son incorrectos. No pudo efectuarse la validación.";
        	$comando="";
        }
    }else{
    	$codigo_rta=303;
    	$mensaje_rta="No pudo efectuarse la validación.";
    	$comando="";        
    }
}





















if ($comando=="crear_empresa")
{
	//valido los datos solicitados
	$codigo_rta=200;
	if (empty($_GET[contrasena_empresa]) and empty($_GET[contrasena_md5_empresa]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar la contraseña de la empresa que desea ingresar.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}
	if (empty($_GET[e_mail_empresa]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el e-mail de la empresa que desea ingresar.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
		if (comprobar_email($_GET[e_mail_empresa])!=1)
      	{
			$codigo_rta=302;//datos obligatorios no ingresados
			$mensaje_rta_vector[]="Debe ingresar un e-mail valido para la empresa que desea ingresar.";
			$mensaje_rta="Debe ingresar los datos obligatorios correctamente.";
      	}		
	}
	if (empty($_GET[nombre_empresa]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el nombre de la empresa que desea ingresar.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}	

    
    
    if ($mysqli_old=new mysqli($GLOBALS["cfg_host_aplic_old"], $GLOBALS["cfg_usuario_aplic_old"],$GLOBALS["cfg_password_aplic_old"],$GLOBALS["cfg_base_aplic_old"]))
    {
        
        if ($_GET[forzar_usuario])
        {
            if (!empty($_GET[usuario_empresa]))
                $usuario_inicial=$_GET[usuario_empresa];
            else
                $usuario_inicial="user-";
            
            $cicla=true;
            $aux="";
            while ($cicla)
            {
                $_GET[usuario_empresa]=$usuario_inicial.$aux;
                $sql_user="select id_empresa from empresas where usuario='$_GET[usuario_empresa]'";
                $result_user=$mysqli->query($sql_user);
                if ($myrow_user=$result_user->fetch_assoc())
                {
                    //siggue ciclabdo
                    $aux++;
                }else{
                    //compruebo que no exista en la base vieja
                    $sql_user="select id_empresa from empresas where usuario='$_GET[usuario_empresa]'";
                    $result_user=$mysqli_old->query($sql_user);
                    if ($myrow_user=$result_user->fetch_assoc())
                    {
                        //siggue ciclabdo
                        $aux++;
                    }else{                    
                        $cicla=false;
                    }
                }
            }
    
        }else{        
        	if (empty($_GET[usuario_empresa]))
        	{
        		$codigo_rta=303;
        		$mensaje_rta_vector[]="Debe ingresar el nombre de usuario de la empresa que desea ingresar.";
        		$mensaje_rta="Debe ingresar los datos obligatorios.";
        	}        	
        }
        
        if (!empty($_GET[usuario_empresa]))
        {
            $sql_user="select id_empresa from empresas where usuario='$_GET[usuario_empresa]'";
            $result_user=$mysqli_old->query($sql_user);
            if ($myrow_user=$result_user->fetch_assoc())
            {
        		$codigo_rta=303;
        		$mensaje_rta="El nombre de usuario ingresado ya pertenece a otra empresa en la antigua versión de Mis Aplicaciones.";
        		$mensaje_rta_vector[]=$mensaje_rta;
            }    
        }  
        
        /*
        if ($_GET[serviceid_whmcs]>0)
        {
            $sql_serviceid_whmcs="select id_empresa from empresas where serviceid_whmcs='$_GET[serviceid_whmcs]'";
            $result_serviceid_whmcs=$mysqli_old->query($sql_serviceid_whmcs);
            if ($myrow_serviceid_whmcs=$result_serviceid_whmcs->fetch_assoc())
            {
        		$codigo_rta=303;
        		$mensaje_rta_vector[]="El serviceid_whmcs ingresado ya pertenece a otra empresa en la antigua versión de Mis Aplicaciones..";
        		$mensaje_rta="El serviceid_whmcs ingresado ya pertenece a otra empresa en la antigua versión de Mis Aplicaciones..";
            }           
        }
        */      
        
    }else{
		$codigo_rta=306;
		$mensaje_rta="No pudo conectarse a la base de datos antigua para comprobar la existencia del usuario";
    }
    
    if (!empty($_GET[usuario_empresa]))
    {
        $sql_user="select id_empresa from empresas where usuario='$_GET[usuario_empresa]'";
        $result_user=$mysqli->query($sql_user);
        if ($myrow_user=$result_user->fetch_assoc())
        {
    		$codigo_rta=303;
    		$mensaje_rta_vector[]="El nombre de usuario ingresado ya pertenece a otra empresa.";
    		$mensaje_rta="El nombre de usuario ingresado ya pertenece a otra empresa.";
        }    
    }
    
    
    if ($_GET[serviceid_whmcs]>0)
    {
        $sql_serviceid_whmcs="select id_empresa from empresas where serviceid_whmcs='$_GET[serviceid_whmcs]'";
        $result_serviceid_whmcs=$mysqli->query($sql_serviceid_whmcs);
        if ($myrow_serviceid_whmcs=$result_serviceid_whmcs->fetch_assoc())
        {
    		$codigo_rta=303;
    		$mensaje_rta_vector[]="El serviceid_whmcs ingresado ya pertenece a otra empresa.";
    		$mensaje_rta="El serviceid_whmcs ingresado ya pertenece a otra empresa.";
        }           
    }else
        $_GET[serviceid_whmcs]="NULL";
    
   
    
    if (empty($_GET["cod_revendedor"]))
        $cod_revendedor=1;
    else{
        //compruebo el revendedor
        $sql_comp_rev="select id_revendedor from z200_revendedores where id_revendedor='".$_GET["cod_revendedor"]."'";
        $result_comp_rev=$mysqli->query($sql_comp_rev);
        if ($myrow_comp_rev=$result_comp_rev->fetch_assoc())
        {        
            $cod_revendedor=$_GET["cod_revendedor"];
        }else{
    			$codigo_rta=303;
    			$mensaje_rta="El identificador del revendedor pasado es incorrecto.";
        }
    }
    
	if (empty($_GET[cod_sponsor]))
	{
		$cod_sponsor="NULL";
	}else{
		$cod_sponsor=$_GET[cod_sponsor];	
	}
    	
	if ($codigo_rta==200)
	{
	if ($_GET[ejecucion_modo_prueba]==1)
	{
		 $codigo_rta=200;
		 $mensaje_rta="Comando Efectuado - Ejecución de Prueba";				
	}else{ 	
		if (!empty($_GET[dominio_empresa]))
		{
			$url="http://www.$_GET[dominio_empresa]";
		}
        
        if (empty($_GET[ip]))
		  $_GET[ip] = getenv("REMOTE_ADDR");

        $string_autenticacion="";
        while (strlen($string_autenticacion)<255)
        {
            $cadena=md5(rand(1,9999999));
            $string_autenticacion.=$cadena;
        }
        $string_autenticacion=substr($string_autenticacion,0,255);

        if (!empty($_GET[contrasena_md5_empresa]))
            $_GET[contrasena_empresa]=$_GET[contrasena_md5_empresa];
        else
            $_GET[contrasena_empresa]=md5($_GET[contrasena_empresa]);
        
        if (!isset($_GET[fecha_fin_demo]))
            $_GET[fecha_fin_demo]="NULL";
        else{
            $_GET[fecha_fin_demo]="'".$_GET[fecha_fin_demo]."'";
            $cargar_log_demo=true;
        }
        if (!isset($_GET[cargar_contenidos_ejemplo]))
            $_GET[cargar_contenidos_ejemplo]=true;
        
		//intento hacer el insert
	    $sql = "insert into empresas set	    	    
                    nombre_empresa='$_GET[nombre_empresa]', 
                    e_mail_empresa='$_GET[e_mail_empresa]',                     
                    usuario='$_GET[usuario_empresa]', 
                    contrasenia='$_GET[contrasena_empresa]', 
                    fecha_empresa=NOW(), 
                    ip_empresa='$_GET[ip]', 
                    direccion_empresa='$_GET[direccion]', 
                    telefono_empresa='$_GET[telefono]', 
                    string_autenticacion='$string_autenticacion', 
                    cod_revendedor=$cod_revendedor,
                    cod_sponsor=$cod_sponsor,
                    rs_facebook='$_GET[rs_facebook]',                     
                    rs_twitter='$_GET[rs_twitter]',                     
                    rs_linkedin='$_GET[rs_linkedin]',                     
                    rs_pinterest='$_GET[rs_pinterest]',                     
                    rs_google='$_GET[rs_google]',
                    id_cliente_whmcs='$_GET[id_cliente_whmcs]', 
                    serviceid_whmcs=$_GET[serviceid_whmcs],
                    fecha_fin_demo=$_GET[fecha_fin_demo]";
		//echo $sql;
   		if ($result = $mysqli->query($sql))
   		{
			$id_empresa=$mysqli->insert_id;			
			$codigo_rta=200;
			$mensaje_rta="La empresa $_GET[nombre_empresa] ha sido ingresada.";
            

            if ($_GET[vincularARevendedor] and $_GET[id_cliente_whmcs])
            {
                //busco si hay un con el $_GET[id_cliente_whmcs]
                $sql_revendedor="SELECT id_revendedor  FROM z200_revendedores where cod_cliente_whmcs='$_GET[id_cliente_whmcs]'";
                $result_revendedor = $mysqli->query($sql_revendedor);
                //var_dump($result);
                if ($myrow_revendedor = $result_revendedor->fetch_assoc())
                {
                    $cod_revendedor==$myrow_revendedor[id_revendedor];
                    $sql_upd="update empresas set cod_revendedor=$myrow_revendedor[id_revendedor] where id_empresa='$id_empresa'";
                    $result_upd = $mysqli->query($sql_upd);
                }
            }
            
            
            $urlFinal=$url_api_info_config_v2."&comando=dame_pais_x_ip&ip=".$_GET[ip];
                        
            $JSON=file_get_contents($urlFinal);
            //echo "$urlFinal.<textarea style='    width: 100%;    height: 200px;'>$JSON</textarea><br>";
            $rta=json_decode($JSON,true);
            
            if ($rta[pais]["cod_pais"]>0)
            {
                $cod_pais=$rta[pais]["cod_pais"];
            }            
            
            
            
            if ($cargar_log_demo)
            {
                //guarda el log de la demo
                $sql_ins_log_demo="insert into z20_demos_solicitadas set
                												ip='$_GET[ip]',
                												cod_empresa=$id_empresa,
                												fecha=NOW(),
                												e_mail='$_GET[e_mail_empresa]',
                                                                nombre='$_GET[nombre_empresa]',                
                                                                cod_pais_demo='$cod_pais',
                                                                cod_revendedor_demo=$cod_revendedor";
            
                $result_ins_log_demo=mysql_query($sql_ins_log_demo);
                                
            }
            
            $rtaJson["id_empresa"]=$id_empresa; 
            $rtaJson["usuario"]=$_GET[usuario_empresa];           
            
            //RECORRO LOS SISTEMAS y LOS SETEO
            if (!isset($_GET["cantidad_x_modulo"][1]))
                $_GET["cantidad_x_modulo"][1]=20;
                
            if (!isset($_GET["cantidad_x_modulo"][5]))
                $_GET["cantidad_x_modulo"][5]=1000;
                
            if (!isset($_GET["cantidad_x_modulo"][6]))
                $_GET["cantidad_x_modulo"][6]=10;
            
            $sql_sistemas="select * from sistemas";
       		$result_sistemas = $mysqli->query($sql_sistemas);
            while ($myrow_sistemas = $result_sistemas->fetch_assoc())
            {
                poner_valores_predeterminados_sistemas($id_empresa,$myrow_sistemas[id_sistema],$_GET["cantidad_x_modulo"][$myrow_sistemas[id_sistema]],$_GET["datosAuxuliaresModulo"][$myrow_sistemas[id_sistema]],$_GET[cargar_contenidos_ejemplo]);
            }
            //http://app.misaplicaciones.com/api/script.php?idua=1&hash=d9da84c37017992bfae1ac2deb9ba830&comando=crear_empresa&ejecucion_modo_prueba=1&usuario_empresa=demo
		
		}else{
			$codigo_rta=304;
			$mensaje_rta="No pudo ingresarse la empresa en la base de datos.";
            $mensaje_rta_vector[]=$mysqli->error;			
		}
	}//end else if ($_GET[ejecucion_modo_prueba]==1)
	}//if ($codigo_rta==200)
   	
   	
    

    
        
		
}//if ($comando=="crear_empresa")












































if ($comando=="obtener_id_empresa")
{
	//valido los datos solicitados
    $rtaFunc=obtener_id_empresa_y_version($_GET);
    
    $rtaJson=$rtaFunc["json"];
    $codigo_rta=$rtaFunc["codigo"];
    $mensaje_rta=$rtaFunc["mensaje"];

}//if ($comando=="obtener_id_empresa")
















if ($comando=="obtener_datos_revendedor")
{
	//valido los datos solicitados
	$codigo_rta=200;
    $establecioParametro=false;
    $sql_rev="select `id_revendedor`, `desc_revendedor`, `url`, `email`, `marca_blanca`, `cod_cliente_whmcs`, `cod_pais` from z200_revendedores where 1";


    if (empty($_GET[fields]))
        $fieldsReturn=array("id_revendedor", "desc_revendedor", "url", "email", "marca_blanca", "cod_cliente_whmcs", "cod_pais");
    else
        $fieldsReturn=explode(",",$_GET[fields]);
        
    
    
	if (!empty($_GET[id_revendedor]))
    {
        $sql_rev.=" and id_revendedor='$_GET[id_revendedor]'";
        $establecioParametro=true;
    }
    
	if (!empty($_GET[cod_cliente_whmcs]))
    {
        $sql_rev.=" and cod_cliente_whmcs='$_GET[cod_cliente_whmcs]'";
        $establecioParametro=true;
    }



	if (!empty($_GET[email]))
    {
        $sql_rev.=" and email='$_GET[email]'";
        $establecioParametro=true;
    }
    
    if (!$establecioParametro)
    {        
		$codigo_rta=302;
		$mensaje_rta="No se establecieron datos para encontrar el revendedor";
    }else{
    
        $result_rev=$mysqli->query($sql_rev);
        $resultados_rev=mysql_num_rows($result_rev);    
        if ($myrow_rev=mysql_fetch_assoc($result_rev))
        {
            if ($resultados_rev==1)
            {
                foreach ($myrow_rev as $campo=>$valor)
                {
                    if (@in_array($campo,$fieldsReturn))
                        $respuesta[$campo]=$valor;
                }
            }else{
                $mensaje_rta="Se encontro mas de una empresa con los datos indicados";   
        		$codigo_rta=304;//datos obligatorios no ingresados
                do{
                    $respuesta[revendedor][]=$myrow_rev[id_revendedor];
                }while($myrow_rev=mysql_fetch_array($result_rev));
                
            }
    		        
        }else{    
    		$codigo_rta=304;
    		$mensaje_rta="No se pudo determinar los datos del revendedor con los datos indicados";
    	}
        
   	}
   	


	
	$respuesta[codigo]=$codigo_rta;
    if (!empty($mensaje_rta))
	$respuesta[mensaje]=$mensaje_rta;

    echo json_encode($respuesta);

    
    exit();

}//if ($comando=="obtener_id_empresa")





























if ($comando=="borrar_empresa")
{
	//valido los datos solicitados
	$codigo_rta=200;

	
	if (empty($_GET[usuario]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el usuario de la empresa.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
		//verifico que la empresa sea correcta
		$sql_empresa="select id_empresa from empresas where usuario='$_GET[usuario]'";
   		//echo $sql_empresa;
   		$result_empresa = $mysqli->query($sql_empresa);
   		if (!($myrow_empresa = $result_empresa->fetch_assoc()))
   		{
			$codigo_rta=303;//datos obligatorios incorrectos
			$mensaje_rta_vector[]="Debe ingresar un usuario correcto de la empresa.";
			$mensaje_rta="Debe ingresar los datos obligatorios correctamente.";			
		}
	}
	


	if ($codigo_rta==200)
	{
    	if ($_GET[ejecucion_modo_prueba]==1)
    	{
    		 $codigo_rta=200;
    		 $mensaje_rta="Comando Efectuado - Ejecución de Prueba";				
    	}else{ 	
    		//PROCESO LA OPERACION
            
            if ($_GET[programar_borrado])
            {
                
           			$sql_del = "update empresas set estado_cliente=-2, fecha_borrado=DATE_ADD( NOW() , INTERVAL 3 MONTH ) where id_empresa='$myrow_empresa[id_empresa]'";
           			if ($result_del = $mysqli->query($sql_del))
           			{
        			 	$codigo_rta=200;
        			 	$mensaje_rta="La empresa ha sido marcada como borrada y sera eliminada fisicamente en 3 meses.";			
           			}else{
          				$codigo_rta=304;
           				$mensaje_rta="La Empresa NO se ha podido ser seteada como borrada y fijada su fecha de elimninacion para dentro de 3 meses";
           			}			
            }else{

                if (borrar_permisos_empresa($myrow_empresa[id_empresa]))
                {                
        			//borro la empresa
           			$sql_del = "delete from empresas where id_empresa='$myrow_empresa[id_empresa]'";
           			if ($result_del = $mysqli->query($sql_del))
           			{
        			 	$codigo_rta=200;
        			 	$mensaje_rta="La empresa ha sido borrada.";			
           			}else{
          				$codigo_rta=304;
           				$mensaje_rta="La Empresa NO se ha podido eliminar";
           			}                
                }else{
                	$codigo_rta=304;
                	$mensaje_rta="La Empresa NO se ha podido eliminar";
                }                                       
            }

    	}//end else if ($_GET[ejecucion_modo_prueba]==1)	
	
    }//if ($codigo_rta==200)
   	
   	
}//borrar_empresa

























if ($comando=="cambiar_estado")
{
	//valido los datos solicitados
	$codigo_rta=200;

	
	if (empty($_GET[usuario]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el usuario de la empresa.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
		//verifico que la empresa sea correcta
		$sql_empresa="select id_empresa from empresas where usuario='$_GET[usuario]'";
   		//echo $sql_empresa;
   		$result_empresa = $mysqli->query($sql_empresa);
   		if (!($myrow_empresa = $result_empresa->fetch_assoc()))
   		{
			$codigo_rta=303;//datos obligatorios incorrectos
			$mensaje_rta_vector[]="Debe ingresar un identificador correcto de la empresa.";
			$mensaje_rta="Debe ingresar los datos obligatorios correctamente.";			
		}
	}
    
    if (!isset($_GET[estado]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el estado para la empresa.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
	   if (!in_array($_GET[estado],array(-2,-1,0,1)))
       {
        	$codigo_rta=303;//datos obligatorios no ingresados
        	$mensaje_rta_vector[]="Debe ingresar un estado para la empresa valido.";
        	$mensaje_rta="Debe ingresar los datos obligatorios correctamente.";
       }	   
    }	


	if ($codigo_rta==200)
	{
    	if ($_GET[ejecucion_modo_prueba]==1)
    	{
    		 $codigo_rta=200;
    		 $mensaje_rta="Comando Efectuado - Ejecución de Prueba";				
    	}else{ 	
    		//PROCESO LA OPERACION
   			$sql_del = "update empresas set estado_cliente=$_GET[estado] where id_empresa='$myrow_empresa[id_empresa]'";
   			if ($result_del = $mysqli->query($sql_del))
   			{
			 	$codigo_rta=200;
			 	$mensaje_rta="El estado de la empresa ha sido modificado.";			
   			}else{
  				$codigo_rta=304;
   				$mensaje_rta="El estado de la empresa no ha sido modificado.";
   			}
    	}//end else if ($_GET[ejecucion_modo_prueba]==1)	
	
    }//if ($codigo_rta==200)
   	
   	
}//borrar_empresa




































































if ($comando=="actualizar_modulo")
{
	//valido los datos solicitados
	$codigo_rta=200;

	
	if (empty($_GET[modulo]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el modulo que desea asignar (Teinda Virtual, Noticias, etc).";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
		if ($_GET[modulo]!=6 and $_GET[modulo]!=1 and $_GET[modulo]!=5 and $_GET[modulo]!=9)
		{
			$codigo_rta=303;//datos obligatorios incorrectos
 			$mensaje_rta="Debe ingresar los datos correctamente.";
			$mensaje_rta_vector[]="Debe ingresar un modulo correcto.";						
		}		
	}

	if (empty($_GET[cantidad]) and  ($_GET[modulo]!=9))
	{
		$codigo_rta=303;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar la cantidad de registros a editar.";
		$mensaje_rta="Debe ingresar los datos correctamente.";					
	}

	
	if (empty($_GET[usuario]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el identificador de la empresa.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
		//verifico que la empresa sea correcta
		$sql_empresa="select id_empresa from empresas where usuario='$_GET[usuario]'";
   		//echo $sql_empresa;
   		$result_empresa = $mysqli->query($sql_empresa);
   		if (!($myrow_empresa = $result_empresa->fetch_assoc()))
   		{
			$codigo_rta=303;//datos obligatorios incorrectos
			$mensaje_rta_vector[]="Debe ingresar un usuario correcto de la empresa.";
			$mensaje_rta="Debe ingresar los datos obligatorios correctamente.";			
		}
	}
	


	if ($codigo_rta==200)
	{
    	if ($_GET[ejecucion_modo_prueba]==1)
    	{
    		 $codigo_rta=200;
    		 $mensaje_rta="Comando Efectuado - Ejecución de Prueba";				
    	}else{ 	
    		if (actualizar_modulo($myrow_empresa[id_empresa],$_GET))
    		{
    			$codigo_rta=200;
    			$mensaje_rta="Se ha editado la cantidad (a $_GET[cantidad]) del Modulo $_GET[modulo].";	
        	}else{
    			$codigo_rta=304;
    			$mensaje_rta="No se ha editado la cantidad (a $_GET[cantidad]) del Modulo $_GET[modulo].";	
    		}
        	
    	}//end else if ($_GET[ejecucion_modo_prueba]==1)
	}//end if ($codigo_rta==200)    	
	


}//actualizar_cantidad_modulo
























if ($comando=="verificar_datos_login")
{
    $usuario=$_GET[uhy];
    $contrasena=$_GET[plq];
    $contrasena_md5x2=$_GET[plqx2];

	$codigo_rta=200;

	if (empty($usuario))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta="Debe ingresar el usuario.";
	}
	if (empty($contrasena))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta="Debe ingresar la contrasena.";
	}

    $rtaJson["version"]=false;
    
    $sql ="select id_empresa, string_autenticacion, nombre_empresa, contrasenia
       from empresas
       where md5(usuario)='$usuario'";
    //echo $sql;
    $valido=0;
    if ($result = $mysqli->query($sql))
    {
    	$codigo_rta=200;
        if ($myrow = $result->fetch_assoc())
        {
            $encontro_usuario=1;
            $datosValidos=false;
            if (!empty($_GET[plq]) and ($myrow["contrasenia"]==$contrasena))
                $datosValidos=true;
            if (!empty($_GET[plqx2]) and (md5($myrow["contrasenia"])==$contrasena_md5x2))
                $datosValidos=true;

            if ($datosValidos)
            {
                $rtaJson["version"]="app";
                $rtaJson["idemai"]=$myrow[id_empresa];   
                $rtaJson["sv"]=$myrow[string_autenticacion];   
                $rtaJson["nombre"]=$myrow[nombre_empresa];   
                $valido=1;
                $mensaje_rta="Los datos son correcto en la nueva version. ";
            }else{
                $mensaje_rta="Los datos son incorrecto en la nueva version. ";
            }
        }else{
            $mensaje_rta="No se encontro el usuario en la nueva version. ";
        }
     }else{
        $codigo_rta=304;        
     }

    if (!$encontro_usuario)
    {
        if ($mysqli_old=new mysqli($GLOBALS["cfg_host_aplic_old"], $GLOBALS["cfg_usuario_aplic_old"],$GLOBALS["cfg_password_aplic_old"],$GLOBALS["cfg_base_aplic_old"]))
        {
            
            $sql ="select id_empresa, string_autenticacion, nombre_empresa, contrasenia
                   from empresas
                   where md5(usuario)='$usuario'";
            //echo $sql;
            $valido=0;
            if ($result = $mysqli_old->query($sql))
            {
            	$codigo_rta=200;
                if ($myrow = $result->fetch_assoc())
                {
                    $datosValidos=false;
                    if (!empty($_GET[plq]) and ($myrow["contrasenia"]==$contrasena))
                        $datosValidos=true;
                    if (!empty($_GET[plqx2]) and (md5($myrow["contrasenia"])==$contrasena_md5x2))
                        $datosValidos=true;
        
                    if ($datosValidos)
                    {
                        $rtaJson["version"]="old";
                        $rtaJson["idemai"]=$myrow[id_empresa];   
                        $rtaJson["sv"]=$myrow[string_autenticacion];   
                        $rtaJson["nombre"]=$myrow[nombre_empresa];   
                        $valido=1;
                        $mensaje_rta="Los datos son correcto en la vieja version. ";
                    }else{
                        $mensaje_rta.="Los datos son incorrecto en la vieja version. ";
                    }
                }else{
                    $mensaje_rta.="No se encontro el usuario en la vieja version. ";
                }
             }else{
                $codigo_rta=304;        
             }
            
        }else{
    		$codigo_rta=306;
    		$mensaje_rta.="No pudo conectarse a la base de datos antigua para comprobar la existencia del usuario y contraseña";
        }
        
    }
	
    $rtaJson["valido"]=$valido;   




}//if ($comando=="verificar_datos_login")




















































if ($comando=="actualizar_empresa")
{
	//valido los datos solicitados
	$codigo_rta=200;
    
    
	

    
	if (empty($_GET[usuario]) and empty($_GET[id_cliente_whmcs]) and empty($_GET[serviceid_whmcs]))
	{
		$codigo_rta=302;//datos obligatorios no ingresados
		$mensaje_rta_vector[]="Debe ingresar el algun paramatro (id_cliente_whmcs o id_empresa) para identificar la empresa.";
		$mensaje_rta="Debe ingresar los datos obligatorios.";
	}else{
        $sql_empresa="select id_empresa,fecha_fin_demo from empresas where 1";
    	if (!empty($_GET[usuario]))         
            $sql_empresa.=" and usuario='$_GET[usuario]'";
                                
    	if (!empty($_GET[id_cliente_whmcs]))        
            $sql_empresa.=" and id_cliente_whmcs='$_GET[id_cliente_whmcs]'";
    	
        if (!empty($_GET[serviceid_whmcs]))        
            $sql_empresa.=" and serviceid_whmcs='$_GET[serviceid_whmcs]'";
        
        $result_empresa=$mysqli->query($sql_empresa);
        $resultados_empresa=$result_empresa->num_rows;
        if ($myrow_empresa=$result_empresa->fetch_assoc())
        {
            if ($resultados_empresa>1)
            {
    		  $codigo_rta=302;//datos obligatorios no ingresados
    		  $mensaje_rta="No se pudo identificar la empresa a modificar. Hay mas de una empresa con los valores establecidos";
            }
        }else{
    		$codigo_rta=302;//datos obligatorios no ingresados
    		$mensaje_rta="No se pudo identificar la empresa a modificar.";            
        }
    }
    
    if (@count($_GET["datos_a_editar"])==0)
    {
    	$codigo_rta=302;//datos obligatorios no ingresados
    	$mensaje_rta="Debe definir por lo menos un dato a editar";            
    }
    
    
    if (!empty($_GET["datos_a_editar"][id_cliente_whmcs]))
        $id_cliente_whmcs=$_GET["datos_a_editar"][id_cliente_whmcs];

    if (!empty($_GET[id_cliente_whmcs]))
        $id_cliente_whmcs=$_GET[id_cliente_whmcs];
    
     
	if (isset($_GET["datos_a_editar"][contrasenia]) and empty($_GET["datos_a_editar"][contrasenia]))
    {
		$codigo_rta=303;//datos obligatorios no ingresados
		$mensaje_rta="La contraseña a modificar no puede ser vacia.";                    
    }  
	if (isset($_GET["datos_a_editar"][usuario]))
    {
        if (empty($_GET["datos_a_editar"][usuario]))
        {
		  $codigo_rta=303;//datos obligatorios no ingresados
		  $mensaje_rta="La contraseña a modificar no puede ser vacia.";                    
        }else{
            if ($mysqli_old=new mysqli($GLOBALS["cfg_host_aplic_old"], $GLOBALS["cfg_usuario_aplic_old"],$GLOBALS["cfg_password_aplic_old"],$GLOBALS["cfg_base_aplic_old"]))
            {
                $sql_user="select id_empresa from empresas where usuario='".$_GET["datos_a_editar"][usuario]."'";
                $result_user=$mysqli_old->query($sql_user);
                if ($myrow_user=$result_user->fetch_assoc())
                {
            		$codigo_rta=303;
            		$mensaje_rta="El nombre de usuario ingresado ya pertenece a otra empresa en la antigua versión de Mis Aplicaciones.";
            		$mensaje_rta_vector[]=$mensaje_rta;
                }    
                      
            }else{
        		$codigo_rta=306;
        		$mensaje_rta="No pudo conectarse a la base de datos antigua para comprobar la existencia del usuario";
            }
        }
    }  
	
    	
	if ($codigo_rta==200)
	{
    	if ($_GET[ejecucion_modo_prueba]==1)
    	{
    		 $codigo_rta=200;
    		 $mensaje_rta="Comando Efectuado - Ejecución de Prueba";				
    	}else{ 	

            if ($_GET[vincularARevendedor] and $id_cliente_whmcs)
            {
                //busco si hay un con el $_GET[id_cliente_whmcs]
                $sql_revendedor="SELECT id_revendedor  FROM z200_revendedores where cod_cliente_whmcs='$id_cliente_whmcs'";
                $result_revendedor = $mysqli->query($sql_revendedor);
                //var_dump($result);
                if ($myrow_revendedor = $result_revendedor->fetch_assoc())
                {
                    $_GET["cod_revendedor"]=$myrow_revendedor[id_revendedor];
                }
            }
            

        
    		//intento hacer el insert
    	    $sql = "update empresas set ";
            
            $camposPosiblesDeEdicion=array("nombre_empresa", "e_mail_empresa",  "usuario", "contrasenia", "cod_proveedor", "cod_empresa", "cod_franquiciado", "fecha_empresa", "ip_empresa", "telefono_empresa", "cod_sponsor", "id_cliente_whmcs", "serviceid_whmcs", "cod_revendedor","fecha_fin_demo");            
            $cntDatosAEditar=0;
            
            foreach ($camposPosiblesDeEdicion as $campo)
            {
                if (isset($_GET["datos_a_editar"][$campo]))
                {
                    
                    if ($campo=="contrasenia")
                        $_GET["datos_a_editar"][$campo]=md5($_GET["datos_a_editar"][$campo]);
                    
                    
                    if (($campo=="fecha_fin_demo") and ($_GET["datos_a_editar"][$campo]=="NULL"))
                        $campoTxt="NULL";
                    else
                        $campoTxt="'".$_GET["datos_a_editar"][$campo]."'";
                                        
                    $cntDatosAEditar++;
                                        
                    $sql.=" $campo=".$campoTxt.",";
                }
            }
            
            
            if ($cntDatosAEditar==0)
            {
            	$codigo_rta=302;//datos obligatorios no ingresados
            	$mensaje_rta="Debe definir por lo menos un dato valido a editar";            
            }
            
            
            $sql=substr($sql,0,strlen($sql)-1);
            
            $sql.= " where id_empresa=$myrow_empresa[id_empresa]";
    		//echo $sql;
       		if ($result = $mysqli->query($sql))
       		{    						
    			$codigo_rta=200;
    			$mensaje_rta="La empresa $myrow_empresa[nombre_empresa] ha sido actualizada.";
                
                $rtaJson["id_empresa"]=$myrow_empresa[id_empresa];
                
                if ((!is_null($myrow_empresa["fecha_fin_demo"])) and ($_GET["datos_a_editar"]["fecha_fin_demo"]=="NULL"))
                {
                    //pasa de demo a normal lo que implica una conversion
                    
                    $sql_conversion_demo= " insert into z20_conversiones set
                                                                        fecha=NOW(),
                                                                        observaciones='cambio fecha fin demo',
                                                                        cod_demo=(select id_demo from z20_demos_solicitadas where cod_empresa=$myrow_empresa[id_empresa]), 
                                                                        ip=''";
                    //echo $sql_conversion_demo;
               		if ($result = $mysqli->query($sql_conversion_demo))                                                                
            			$mensaje_rta.=" Se marco una conversion a raiz del cambio de fecha fin de demo.";
                    else
            			$mensaje_rta.=" No se marco laconversion a raiz del cambio de fecha fin de demo (".$mysqli->error.").";
                    
                                                               
                }
                
    		}else{
    			$codigo_rta=304;
    			$mensaje_rta="No pudo actualizarse los datos de la empresa en la base de datos.".$mysqli->error;			
    		}
    	}//end else if ($_GET[ejecucion_modo_prueba]==1)
	}//if ($codigo_rta==200)
   	
   	
    		

}//if ($comando=="crear_empresa")






























$rtaJson["comando"]=$comando;
$rtaJson["codigo"]=$codigo_rta;
$rtaJson["mensaje"]=utf8_encode($mensaje_rta);
		

if (@count($mensaje_rta_vector)>0)
    $rtaJson["errores"]=utf8_encode_array($mensaje_rta_vector);
    
echo json_encode($rtaJson);
?>
