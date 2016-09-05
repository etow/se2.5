<?php

function utf8_encode_array($array)
{
    
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
     
    return $array;
}







function dameInfoCarrito($id_session,$id_empresa, $myrow_config)
{
    global $mysqli,$link_aplic_base;
    $sql = "select z6_articulos.id_articulo, nombre_articulo, precio_unitario, cantidad, foto_file  
            from z6_pedidos,z6_articulos left join z6_fotos on (z6_articulos.id_articulo=z6_fotos.id_articulo and foto_primaria)
            where cod_articulo=z6_articulos.id_articulo and cod_session ='$id_session' and '$id_empresa'=cod_empresa";
    //echo $sql;        
    $result = $mysqli->query($sql);
    $data["precio_total"]=0;
    $data["cantidad_total"]=0;
    while ($myrow = $result->fetch_assoc())
    {
        
        if (!empty($myrow[foto_file]))
        {
            $image_file="imagenes/catalogo/".$myrow[foto_file];
            $myrow[foto_file]=$link_aplic_base.$image_file;
        }
        
        $myrow["subtotal"] = number_format($myrow["precio_unitario"]*$myrow["cantidad"],$myrow_config[cant_decimales],$myrow_config[separador_decimal],$myrow_config[separador_miles]);
        $data["precio_total"]=$data["precio_total"]+($myrow["precio_unitario"]*$myrow["cantidad"]);
        
        $myrow["precio_unitario"] = number_format($myrow["precio_unitario"],$myrow_config[cant_decimales],$myrow_config[separador_decimal],$myrow_config[separador_miles]);

        $data["productos"][]=$myrow;        
        $data["cantidad_total"]=$data["cantidad_total"]+$myrow["cantidad"];
    }

    $data["precio_total"] = number_format($data["precio_total"],$myrow_config[cant_decimales],$myrow_config[separador_decimal],$myrow_config[separador_miles]);
    
    return $data;
}










function obtener_id_empresa_y_version($params)
{
    global $mysqli;
	$codigo_rta=200;
    $establecioParametro=false;
    $sql_user="select id_empresa,usuario from empresas where 1";
	if (!empty($params[dominio_empresa]))
	{
		//$url="http://www.$params[dominio_empresa]";
        $sql_user_old.=" and exists (select id_sitio from z9_sitios where host='$params[dominio_empresa]') ";
        $sql_user.=" and exists (select id_sitio from z9_sitios where host='$params[dominio_empresa]') ";
        $establecioParametro=true;
	}

	if (!empty($params[usuario]))
    {
        $sql_user.=" and usuario='$params[usuario]'";
        $sql_user_old.=" and usuario='$params[usuario]'";
        $establecioParametro=true;
    }
    
	if (!empty($params[id_cliente_whmcs]))
    {
        $sql_user.=" and id_cliente_whmcs='$params[id_cliente_whmcs]'";
        $sql_user_old.=" and id_cliente_whmcs='$params[id_cliente_whmcs]'";
        $establecioParametro=true;
    }

	if (!empty($params[serviceid_whmcs]))
    {
        $sql_user.=" and serviceid_whmcs='$params[serviceid_whmcs]'";
        $sql_user_old.=" and serviceid_whmcs='$params[serviceid_whmcs]'";
        $establecioParametro=true;
    } 

	if (!empty($params[e_mail_empresa]))
    {
        $sql_user.=" and e_mail_empresa='$params[e_mail_empresa]'";
        $sql_user_old.=" and e_mail_empresa='$params[e_mail_empresa]'";
        $establecioParametro=true;
    }
    


	if ($params[solo_demos])
    {
        //busca empresas que no tengan persmisos normales y q tengas demos
        $sql_user_old.=" AND 
                     NOT EXISTS ( SELECT * FROM empre_sist AS permisos_normales WHERE empresas.id_empresa = permisos_normales.id_empresa AND permisos_normales.tipo_permiso =0)
                     AND 
                     EXISTS (SELECT * FROM empre_sist AS demos WHERE empresas.id_empresa = demos.id_empresa AND demos.tipo_permiso=1 ) ";

        $sql_user.=" AND fecha_fin_demo is not NULL ";

        $establecioParametro=true;
    }
    
    
    
    $rtaJson["version"]="app";
    if (!empty($sql_user_old))
    {
        if ($mysqli_old=new mysqli($GLOBALS["cfg_host_aplic_old"], $GLOBALS["cfg_usuario_aplic_old"],$GLOBALS["cfg_password_aplic_old"],$GLOBALS["cfg_base_aplic_old"]))
        {
            
            
            $sql_user_old="select id_empresa,usuario from empresas where 1 ".$sql_user_old;
            //echo $sql_user_old;
            $result_user_old=$mysqli_old->query($sql_user_old);
            $resultados_user_old=$result_user_old->num_rows;    
            if ($myrow_user=$result_user_old->fetch_assoc())
            {
                $rtaJson["version"]="old";
                if ($resultados_user_old==1)
                {
                    $rtaJson["id_empresa"]=$myrow_user[id_empresa];
                    $rtaJson["usuario"]=$myrow_user[usuario];
                }else{                    
                    $mensaje_rta="Se encontro mas de una empresa con los datos indicados en la version antigua";   
            		$codigo_rta=304;//datos obligatorios no ingresados
                    do{
                        $rtaJson["empresas"][]=$myrow_user[id_empresa];
                    }while($myrow_user=$result_user->fetch_assoc());
                }            
            }
            
        }else{
    		$codigo_rta=306;
    		$mensaje_rta="No pudo conectarse a la base de datos antigua para comprobar la existencia del usuario";
        }
    }
    
    if ($codigo_rta==200)
    {
        if (!$establecioParametro)
        {        
    		$codigo_rta=302;
    		$mensaje_rta="No se establecieron datos para encontrar la empresa";
        }else{
            //echo $sql_user;
            $result_user=$mysqli->query($sql_user);
            $resultados_user=$result_user->num_rows;    
            if ($myrow_user=$result_user->fetch_assoc())
            {
                if ($rtaJson["version"]=="old")
                {
                    unset($rtaJson["id_empresa"]);
                    unset($rtaJson["usuario"]);
                    $mensaje_rta="Se encontro empresas en ambas versiones.";   
            		$codigo_rta=305;
                }else{
                    if ($resultados_user==1)
                    {
                        $rtaJson["id_empresa"]=$myrow_user[id_empresa];
                        $rtaJson["usuario"]=$myrow_user[usuario];
                    }else{
                        $mensaje_rta="Se encontro mas de una empresa con los datos indicados";   
                		$codigo_rta=304;//datos obligatorios no ingresados
                        do{
                            $rtaJson["empresas"][]=$myrow_user[id_empresa];
                        }while($myrow_user=$result_user->fetch_assoc());
                    }
                }
        		        
            }else{
                if (empty($rtaJson["id_empresa"]))
                {
            		$codigo_rta=304;
            		$mensaje_rta="No se pudo determinar el id de la empresa con los datos indicados";
                }
        	}
            
       	}
    }
    
    return array("codigo"=>$codigo_rta,"mensaje"=>$mensaje_rta,"json"=>$rtaJson);
   	    
}




function ingresar_productos_de_ejemplo($id_empresa,$cantidadProductos=5)
{    
    global $mysqli;
    //veo si tiene categorias y si tiene las pongo en un vector
    $sql_cat="select id_categoria from z6_categorias where id_empresa='$id_empresa' limit 1"; 
	$result_cat=$mysqli->query($sql_cat);
	if ($myrow_cat=$result_cat->fetch_assoc())
    {
        $id_categoria=$myrow_cat[id_categoria];
    }else
        return false;

    $carpetaImgEj="../images/imgEjemplos/tienda/*.jpg";
    $imagenesEjemplo=glob($carpetaImgEj);
     
    $nombre="Producto o Servicio de Ejemplo";
    $descripcion="Este producto se cargo automaticamente de ejemplo";
    $texto="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nec aliquam urna, non rhoncus nulla. Sed ut mauris neque. Nam libero arcu, volutpat vitae risus id, malesuada tincidunt nisi. </p><p>Sed condimentum cursus felis, eget cursus urna interdum id. Donec at dui non diam rutrum eleifend non vitae libero. Sed id eros turpis. Mauris vel lacus in ligula luctus volutpat ultrices quis tellus. Fusce luctus consequat dignissim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec et dolor tortor.</p>";        
    $cod_estado=1;
    for ($i=1;$i<=$cantidadProductos;$i++)
    {
       
       $precio=rand(30,9999)/rand(1,3); 
        
       $sql_ins = "insert into z6_articulos
       (id_empresa, nombre, descripcion, id_categoria, texto, precio,
       cod_estado, fecha, ip,fecha_ingreso)
       values
       ('$id_empresa', '$nombre', '$descripcion', $id_categoria, '$texto', '$precio',
       $cod_estado, NOW(), 'Automatico',NOW())";
       //echo "<textarea>$sql_ins</textarea>";
       if ($result_ins=$mysqli->query($sql_ins))
       {
           $articulo_id=$mysqli->insert_id;
            
            
           $indice=rand(0,count($imagenesEjemplo)-1); 
           $img_fuente=$imagenesEjemplo[$indice];           
           $nombre_foto=$articulo_id.".jpg";
           $img_dest="../imagenes/catalogo/".$nombre_foto;
            
           if (@copy($img_fuente,$img_dest))
           {
                //cargo la foto
               $sql = "insert into z6_fotos (id_articulo, id_empresa, titulo, descripcion, foto_primaria, foto_file)
                      values($articulo_id, $id_empresa, '$nombre', '$descripcion', 1, '$nombre_foto')";
               if ($result = $mysqli->query($sql))
               {
                    
               }
           }             
       }
        //echo mysql_error()."<hr>";
    }
    
    return true;
}













function ingresar_noticias_de_ejemplo($id_empresa,$cantidadNoticias=5)
{    
    global $mysqli;
    
    //veo si tiene categorias y si tiene las pongo en un vector
    $sql_cat="select id_catego_nota from z1_categorias where id_empresa='$id_empresa' limit 1";
    //echo "... : ".$sql_cat; 
	$result_cat=$mysqli->query($sql_cat);
	if ($myrow_cat=$result_cat->fetch_assoc())
    {
        $id_categoria=$myrow_cat[id_catego_nota];
    }else{
        return false;
    }

    $carpetaImgEj="../images/imgEjemplos/blog/*.jpg";
    $imagenesEjemplo=glob($carpetaImgEj);


    $nombre="Noticia de Ejemplo";    
    $texto="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nec aliquam urna, non rhoncus nulla. Sed ut mauris neque. Nam libero arcu, volutpat vitae risus id, malesuada tincidunt nisi. </p><p>Sed condimentum cursus felis, eget cursus urna interdum id. Donec at dui non diam rutrum eleifend non vitae libero. Sed id eros turpis. Mauris vel lacus in ligula luctus volutpat ultrices quis tellus. Fusce luctus consequat dignissim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec et dolor tortor.</p>";        
    for ($i=1;$i<=$cantidadNoticias;$i++)
    {
       $sql_ins = "insert into z1_notas (id_empresa, titulo, texto, fecha, id_catego) values($id_empresa, '$nombre', '$texto', NOW(), $id_categoria)";
       //echo "<textarea>$sql_ins</textarea>";
       if ($result_ins=$mysqli->query($sql_ins))
       {
           $nota_id=$mysqli->insert_id;


           $indice=rand(0,count($imagenesEjemplo)-1); 
           $img_fuente=$imagenesEjemplo[$indice];           

                      
           $nombre_foto=$nota_id.".jpg";
           $img_dest="../imagenes/notas/".$nombre_foto;
            
           if (@copy($img_fuente,$img_dest))
           {
           }             
       }
        //echo mysql_error()."<hr>";
    }
    
    return true;
}










function poner_valores_predeterminados_sistemas($id_empresa,$id_sistema,$cantidad,$datosAuxuliaresModulo=array(),$cargar_contenidos_ejemplo=true)
{
   //echo "poniendo valores predetreminados 1";
   global $mysqli; 
   $return=true; 


   if ($id_sistema==1)//notas
   {

        // Busco los datos del sistema predeterminado
        $sql = "select z1_config_notas.* from z1_config_notas where id_config_empresa = 1";
        $result = $mysqli->query($sql);
        if ($myrow = $result->fetch_assoc())
        {

           

            
            unset($myrow[id_config_nota]);
            unset($myrow[id_config_empresa]);
            $myrow[cant_notas]=$cantidad;
            unset($myrow[mod_rewrite]);
            
            // Inserto el registro
                
            $sql = "replace into z1_config_notas set id_config_empresa=".$id_empresa;
            foreach ($myrow as $campo=>$valor)
                $sql.=",$campo='".addslashes($valor)."'"; 
                
            
            //echo "<textarea rows='8' name='S1' cols='34'>$sql</textarea><br>";
            
            if ($result = $mysqli->query($sql))
            {
                if ($cargar_contenidos_ejemplo)
                {                
                    // Inserta una categoria general
                    $sql = "insert into z1_categorias (id_empresa, desc_catego) values('$id_empresa', 'General')";
                    //echo "-$sql-";
                    $result = $mysqli->query($sql);
                                
                    ingresar_noticias_de_ejemplo($id_empresa);
                }
                return true;    
            }else
                return false;    
                
            
                
        }else{    
           return false;    
        }
        


   }//end if ($id_sistema==1)NOTAS







   if ($id_sistema==5)//sucriptores
   {

      // Borro el registro anterior
      $sql = "delete from z5_config where id_empresa = $id_empresa";
      $result = $mysqli->query($sql);


      // Busco los datos del sistema predeterminado
      $sql = "select z5_config.* from z5_config where z5_config.id_empresa = 1";
      $result = $mysqli->query($sql);
      $myrow = $result->fetch_assoc();


      // Inserto datos por default para el sistema de notas


      $sql = "INSERT INTO z5_config (id_config, id_empresa, bienvenida, actualizacion, baja, max_suscriptores, max_mensajes, color_fondo, color_textos, letra_textos, tamanio_letra, msg_suscribirse,  msg_confirma_sus, msg_desuscribirse  )
      VALUES ('0', $id_empresa, '$myrow[bienvenida]', '$myrow[actualizacion]',
       '$myrow[baja]', '999999','$cantidad', '$myrow[color_fondo]', '$myrow[color_textos]', '$myrow[letra_textos]',
         '$myrow[tamanio_letra]', '$myrow[msg_suscribirse]',  '$myrow[msg_confirma_sus]', '$myrow[msg_desuscribirse]'  )";
      $result = $mysqli->query($sql);

      // Inserta una categoria general
      
      if ($cargar_contenidos_ejemplo)
      {
    	  $sql = "insert into z5_categorias (id_empresa, desc_catego) values('$id_empresa', 'Novedades')";	  
    	  //echo "-$sql-";
       	  $result = $mysqli->query($sql);
      }

   }//end if ($id_sistema==5)//sucriptores




   if ($id_sistema==6)//CATALOGO
   {


      // Busco los datos del sistema predeterminado
      $sql = "select * from z6_config where id_empresa = 1";
      $result = $mysqli->query($sql);
      $myrow = $result->fetch_assoc();

      // Borro el registro anterior
      $sql = "delete from z6_config where id_empresa = $id_empresa";
      $result = $mysqli->query($sql);


      $sql = "delete from z6_config_campos where cod_empresa = $id_empresa";
      $result = $mysqli->query($sql);





      $cant_fotos=$cantidad*3;
      
      $sql = "insert z6_config set 
                id_empresa = $id_empresa,
                cant_max_art='$cantidad',
                cant_max_fotos='$cant_fotos',                  
                cant_enteros='$myrow[cant_enteros]',
                cant_decimales='$myrow[cant_decimales]',  
                separador_decimal='$myrow[separador_decimal]',
                separador_miles='$myrow[separador_miles]',
                cod_moneda='$myrow[cod_moneda]', 
                cod_ordenamiento='$myrow[cod_ordenamiento]',  
                carrito  = '$myrow[carrito]',
                alto_grilla='$myrow[alto_grilla]', 
                ancho_grilla='$myrow[ancho_grilla]',
                alto_chica='$myrow[alto_chica]',
                ancho_chica='$myrow[ancho_chica]',
                alto_grande='$myrow[alto_grande]',
                ancho_grande='$myrow[ancho_grande]',
                solicitador_mas_info='$myrow[solicitador_mas_info]',
                tam_foto_atr='$myrow[tam_foto_atr]',
                ancho_grilla_multiimg='$myrow[ancho_grilla_multiimg]',
                imagen_completa_ch='$myrow[imagen_completa_ch]'";
      //echo "$cantidad / $cant_fotosxarticulos<textarea rows='7' name='S1' cols='31'> ******** $sql ******** </textarea>";exit();
      if ($result = $mysqli->query($sql))
      {
      //echo "<textarea rows='7' name='S1' cols='31'>$sql</textarea>";        
        // Inserta el z6_campos
        $sql = "insert into z6_config_campos set cod_empresa=$id_empresa";
        $result = $mysqli->query($sql);
        

        if ($cargar_contenidos_ejemplo)
        {
            // Inserta una categoria general
       	    $sql = "insert into z6_categorias (id_empresa, desc_catego, id_padre) values('$id_empresa', 'General', 0)";
            if ($result = $mysqli->query($sql))
            {
                ingresar_productos_de_ejemplo($id_empresa);
            }
        }
        
        if ($myrow[carrito]==1)//carrito
        {
            $rta=SitioExpress_PaginaVO::crearPaginaSistema(12,$id_empresa);
            
             //cargo la forma de pago y envio por defecto
             $sql_comp = "select * from z6_fpagos where cod_empresa = '$id_empresa'";
             $result_comp = mysql_query($sql_comp);
             if (!$myrow_comp = mysql_fetch_array($result_comp))
             {
                //ingreso forma de pago
                $sql_ins = "insert into z6_fpagos set cod_empresa=$id_empresa, 
                                                      desc_fpago='Efectivo',
                                                      porcentaje_recargo=0 ,
                                                      importe_recargo=0,
                                                      cod_fpago_predefinida=1";
                $result_ins = mysql_query($sql_ins);
             }   
                        
             
             $sql_comp = "select * from z6_fenvios where cod_empresa = '$id_empresa'";
             $result_comp = mysql_query($sql_comp);
             if (!$myrow_comp = mysql_fetch_array($result_comp))
             {
                //ingreso forma de envio
                $sql_ins = "insert into z6_fenvios set cod_empresa=$id_empresa, 
                                                      desc_fenvio='Retiro Personalmente',
                                                      porcentaje_recargo=0 ,
                                                      importe_recargo=0,
                                                      datos_envio=0";
                $result_ins = mysql_query($sql_ins);
             }   
                        
            
        }  
        if ($myrow[carrito]==2)//cotizador
        {
            $rta=SitioExpress_PaginaVO::crearPaginaSistema(11,$id_empresa);
        }          
        
      }else{
        $return=false;
      }
        


   }//end if ($id_sistema==6)//catalogo






   if ($id_sistema==9)//sitio express
   {


        $sql="replace into z9_sitios set cod_cliente=$id_empresa";	
        if ($result=$mysqli->query($sql))
        {               
            if (@count($datosAuxuliaresModulo))
            {
                foreach ($datosAuxuliaresModulo as $campo=>$valor)
                {
                    $sql_upd="update z9_sitios set $campo='$valor' where cod_cliente=$id_empresa";
                    $result_upd=$mysqli->query($sql_upd);
                }
            }
            
        }else
            $return=false;      		

      

   }//end if ($id_sistema==5)//sucriptores





   //echo "Los valores han sido restaurados";
   
   return $return;

}//END function poner_valores_predeterminados($id_empresa,$id_sistema)




































function actualizar_modulo($id_empresa,$datos)
{
    
    $modulo=$datos[modulo];
    $cantidad=$datos[cantidad];
    $incrementa_bool=$datos[incrementa];
    $host=$datos[host];    
    
    
    global $cant_fotosxarticulos,$mysqli;
	
    if ($modulo == 1)
    {
        if (!isset($datos[cantidad]))
            return false;
       // Actualizo los valores
       if ($incrementa_bool)
       {
    	   $sql = "update z1_config_notas set cant_notas = cant_notas+$cantidad where id_config_empresa = $id_empresa";
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
            			
	   }else{
    	   $sql = "update z1_config_notas set cant_notas = $cantidad where id_config_empresa = $id_empresa";
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
           		
	   }

	}   // end if modulo=1
	
	
	
	

	
	
	
	
	
	
	
	
	if ($modulo==5)
	{

       if (!isset($datos[cantidad]))
            return false;

       if ($incrementa_bool)
       {
	       $sql = "update z5_config set
                       max_mensajes = max_mensajes+$cantidad
                                     where id_empresa = $id_empresa";
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
	   }else{
	       $sql = "update z5_config set
                       max_mensajes = $cantidad
                                     where id_empresa = $id_empresa";
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
	   }


		
	}//end if modulo 5
	
	
	
	
	
	
	
	
	
	
	
	
	
	if ($modulo==6)
	{
       // Actualizo los valores
        if (!isset($datos[cantidad]))
            return false;

       $cant_fotosxarticulos=3;
       $cant_max_fotos=$cantidad*$cant_fotosxarticulos;
       if ($incrementa_bool)
       {
	       $sql = "update z6_config set cant_max_art = cant_max_art+$cantidad,
                                     cant_max_fotos = ((cant_max_art+$cantidad)*$cant_fotosxarticulos )
                                     where id_empresa = $id_empresa";
    	   //echo $sql;
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
	   }else{
	       $sql = "update z6_config set cant_max_art = $cantidad,
                                     cant_max_fotos = $cant_max_fotos
                                     where id_empresa = $id_empresa";
    	   //echo $sql;
	       if ($result = $mysqli->query($sql))
                return true;                                             
           else
                return false;                                             		
	   }

		
		
	}//en if modulo 6
	
	
	
	
	
	

	
	
	if ($modulo==9)
	{

        $sql = "update z9_sitios set 
                    publica_en_server_se='0',                
                    usuario_cpt='',
                    host='$host',
                    max_size_mb_publicacion='0',
                   	host_o_ip_publicacion='',
                   	carpeta_public='public_html',
                   	usuario_publicacion='',
                   	password_publicacion=''                   
         where cod_cliente = '$id_empresa'";            
        //echo $sql;
        if ($result = $mysqli->query($sql))
    		//como no es necesario actualizar la cantidad del modulo sitio express retorno true;
    		return true;
        else
            return false;                                             		
    }	
	
}//end funcion actulizar_cantidad_modulo






























function borrar_permisos_empresa($id_empresa,$no_imprimo_mensaje_pantalla=1)
{
    global $mysqli;


	//comprueba desde donde estan llamando a la funcion para tomar de referencia la url de donde se ubican las imagenes
	$url_imagenes_aux="../";


      
    
    $sql = "insert into log_bajas set
                cod_empresa='$id_empresa',                
                fecha=NOW()";
    if ($result = $mysqli->query($sql))
        $id_log_baja=$mysqli->insert_id;



    $sql_sistemas="select * from sistemas";
    $result_sistemas = $mysqli->query($sql_sistemas);
    while ($myrow_sistemas = $result_sistemas->fetch_assoc())
    {
        $id_sistema=$myrow_sistemas[id_sistema];
    


        if ($id_sistema==1)//notas
        {
           // Borro la configuracion
           $sql = "delete from z1_config_notas where id_config_empresa = $id_empresa";
           $result = $mysqli->query($sql);
        
           // Borro las categorias
           $sql = "delete from z1_categorias where id_empresa = $id_empresa";
           $result = $mysqli->query($sql);
        
           // Borro las fotos de las notas
           $sql = "select * from z1_notas where id_empresa = $id_empresa";
           $result = $mysqli->query($sql);
           while ($myrow = $result->fetch_assoc())
           {
        
                 // Defino con que nombre guardare el archivo
                 $directorio=$url_imagenes_aux."imagenes/notas/";
                 $nombref=$myrow[id_nota].".jpg";
        
                 if(file_exists($directorio.$nombref))
                 {
                     // Borro el archivo temporal
                     unlink($directorio.$nombref);
                 }
        
           }//end while
        
        
           // Borro las notas
           $sql = "delete from z1_notas where id_empresa = $id_empresa";
           $result = $mysqli->query($sql);
           
           if ($no_imprimo_mensaje_pantalla!=1)
           {
           		echo "Se borraron las notas, sus fotos y las categorias. ademas se borro el registro de configuración.<br>";
           }
           $senal=1;
        
        }//end if ($id_sistema==1)NOTAS
        








        if ($id_sistema==5)//sucriptores
        {
        
          // Borro la configuracin
          $sql = "delete from z5_config where id_empresa = $id_empresa";
          $result = $mysqli->query($sql);
        
          // Borro los mensajes
          $sql = "delete from z5_mensajes where id_empresa = $id_empresa";
          $result = $mysqli->query($sql);
        
        
          // Busco las suscripciones a las categorias
          $sql = "select * from z5_suscrip_catego,z5_suscriptores
               where z5_suscrip_catego.id_suscriptor=z5_suscriptores.id_suscriptor and id_empresa=$id_empresa";
          $result = $mysqli->query($sql);
          while ($myrow = $result->fetch_assoc())
          {
                // Borro la suscripcion
                $sql_borrar = "delete from z5_suscrip_catego where id_suscrip_catego=$myrow[id_suscrip_catego]";
                $result_borrar = $mysqli->query($sql_borrar);
          }
        
          // Borro los suscriptores
          $sql = "delete from z5_suscriptores where id_empresa = $id_empresa";
          $result = $mysqli->query($sql);
        
          // Borro las categorias
          $sql = "delete from z5_categorias where id_empresa = $id_empresa";
          $result = $mysqli->query($sql);
        
          if ($no_imprimo_mensaje_pantalla!=1)
          {
           		echo "Se borraron los suscriptores, las categorias y las suscripciones a estas ultimas. ademas se borro el registro de configuración.<br>";
          }
          $senal=1;
        
        }//end if ($id_sistema==5)//sucriptores
        



        if ($id_sistema==6)//CATALOGO
        {
        
        
              // Borro la configuracion
              $sql = "delete from z6_config where id_empresa = $id_empresa";
              $result = $mysqli->query($sql);
        
              $sql = "delete from z6_config_campos where cod_empresa = $id_empresa";
              $result = $mysqli->query($sql);
        
              // Borro las Formas de Envio
              $sql = "delete from z6_fenvios  where cod_empresa = $id_empresa";
              $result = $mysqli->query($sql);
        
              // Borro las Formas de Pago
              $sql = "delete from z6_fpagos  where cod_empresa = $id_empresa";
              $result = $mysqli->query($sql);
        
        
              //borro las categorias y sus fotos
              // Busco los datos del sistema predeterminado
              $sql = "select * from z6_categorias where id_empresa=$id_empresa";
              $result = $mysqli->query($sql);
        
              while ($myrow = $result->fetch_assoc())
              {
                  $sql_borrar = "delete from z6_categorias where id_categoria = $myrow[id_categoria]";
                  $result_borrar = $mysqli->query($sql_borrar);
        
                  // Defino con que nombre guardare el archivo
                  $directorio=$url_imagenes_aux."imagenes/catalogo_categorias/";
                  $nombref=$myrow[id_categoria].".jpg";
        
                  if(file_exists($directorio.$nombref))
                  {
                   // Borro el archivo temporal
                   unlink($directorio.$nombref);
                  }
              }//end while
        
        
        
        
        
        
           //borra todas las fotos de los articulos
           $sql_art = "select id_articulo from z6_articulos where id_empresa = $id_empresa";
           $result_art = $mysqli->query($sql_art);
           while ($myrow_art=$result_art->fetch_assoc())
           {
        
        
             //borra todas las fotos
             $sql = "select foto_file from z6_fotos where id_articulo = $myrow_art[id_articulo]";
             $result = $mysqli->query($sql);
             while ($myrow=$result->fetch_assoc())
             {
        
               // Defino con que nombre guardare el archivo
               $directorio=$url_imagenes_aux."imagenes/catalogo/";
               $nombref=$myrow[foto_file];
        
               if(file_exists($directorio.$nombref))
                {
                   // Borro el archivo temporal
                   unlink($directorio.$nombref);
                }
        
             }//end while fotos
        
             //borra las fotos del articulo
             $sql = "delete from z6_fotos where id_articulo = $myrow_art[id_articulo]";
             $result = $mysqli->query($sql);
        
           } //end while aticulos
        
           $sql = "delete from z6_articulos where id_empresa = $id_empresa";
           $result = $mysqli->query($sql);
        
            if ($no_imprimo_mensaje_pantalla!=1)
        	{
            	echo "Se borraron los productos con sus fotos, al igual que las categorias y sus imagenes. Ademas se borro el registro de configuración.<br>";
            }
            $senal=1;
        
        
           //borra todas las fotos de los articulos
           $sql_art = "select * from z6_atributos where cod_empresa = $id_empresa";
           $result_art = $mysqli->query($sql_art);
           while ($myrow_art=$result_art->fetch_assoc())
           {
             //borra todas las opciones
             $sql = "select * from z6_atributos_opciones where cod_atributo = $myrow_art[id_atributo]";
             $result = $mysqli->query($sql);
             while ($myrow=$result->fetch_assoc())
             {
               // Defino con que nombre guardare el archivo
               $directorio=$url_imagenes_aux."imagenes/atributos_opciones/";
               $nombref=$myrow[id_opcion].".jpg";
        
               if(file_exists($directorio.$nombref))
                {
                   // Borro el archivo temporal
                   unlink($directorio.$nombref);
                }
        
             }//end while fotos
           	 $sql = "delete from z6_atributos_opciones where cod_atributo = $myrow_art[id_atributo]";
             $result = $mysqli->query($sql);
           }
           $sql = "delete from z6_atributos where id_empresa = $id_empresa";
           $result = $mysqli->query($sql);
        
        
        }//end if ($id_sistema==6)//catalogo
        
        








        
        
        
        if ($id_sistema==9)//SITIO EXPRESS
        {
            include_once("$url_imagenes_aux/funciones/se.php");
            //borro las paginas y sus fotos
            
            $sql_sitio = "select * from z9_sitios where cod_cliente=$id_empresa";
            $result_sitio = $mysqli->query($sql_sitio);    
            if ($myrow_sitio = $result_sitio->fetch_assoc())
            {
                $id_sitio=$myrow_sitio[id_sitio];
         
                 if (!empty($myrow_sitio[host_o_ip_publicacion]))
                    $hostPublicacion=$myrow_sitio[host_o_ip_publicacion];
                 else
                    $hostPublicacion=$myrow_sitio[host];
        
         
                if ($myrow_sitio[publica_en_server_se] and !empty($myrow_sitio[usuario_publicacion]) and !empty($myrow_sitio[password_publicacion]))
                {        
                    //	$datos_recordados_bool=TRUE;
                    //  $visible_nuevos_datos=' style="display:none;"';
                	$user=desencriptar_texto($myrow_sitio[usuario_publicacion],md5($myrow_sitio[cod_cliente]));
                	$pass=desencriptar_texto($myrow_sitio[password_publicacion],md5($myrow_sitio[cod_cliente]));
                                
                	//veo si puedo conectarme
                	if (($FtpConn = ftp_connect($hostPublicacion,$myrow_sitio[puerto_ftp], 5)))
                	{
                		if(ftp_login($FtpConn,$user,$pass))
                		{
                            if (!empty($myrow_sitio[carpeta_public]))
                                ftp_chdir($FtpConn,$myrow_sitio[carpeta_public]);
               		  
                            ftpBorrarDemoPublicada($FtpConn,$myrow_sitio[usuario_cpt]);        		  
        
                           ftp_close($FtpConn);
        
                		}else{
                		   if ($no_imprimo_mensaje_pantalla!=1)
                    	       $mensaje_pantalla.="No me loguee al servidor para borrar la carpeta de la demo en se<br>";        		  
                		}		
                	}else{
                	   if ($no_imprimo_mensaje_pantalla!=1)
                	       $mensaje_pantalla.="No me conecte al servidor para borrar la carpeta de la demo en se<br>";
                	}
                    
                }    
                
            
                // Busco todas las imagenes de las paginas y las paginas 
                $sql = "select id_pagina from z9_sitios,z9_paginas where cod_sitio=id_sitio and cod_cliente=$id_empresa";
                $result = $mysqli->query($sql);
                
                while ($myrow = $result->fetch_assoc())
                {
                    $rta=SitioExpress_PaginaVO::borrarPagina($myrow[id_pagina]);
                
                }//end while
                

                
                
                // Busco todas las backup 
                $sql = "select id_backup from z9_backup where cod_empresa=$id_empresa";
                $result = $mysqli->query($sql);
                
                while ($myrow = $result->fetch_assoc())
                {
                    eliminar_backup($myrow[id_backup],$url_imagenes_aux);    
                }//end while
                    
                
                
                
                // Busco los datos
                $sql = "select logo_file, favicon_file from z9_sitios where id_sitio='$id_sitio'";
                $result = $mysqli->query($sql);
                if ($myrow = $result->fetch_assoc())
                {
                
                  if (!empty($myrow[logo_file]))  
                    unlink($url_imagenes_aux.$myrow[logo_file]);
                
                  if (!empty($myrow[favicon_file]))  
                    unlink($url_imagenes_aux.$myrow[favicon_file]);
                
                }
                
                //boora el sitio
                $sql_borrar = "delete from z9_sitios where cod_cliente=$id_empresa";
                $result_borrar = $mysqli->query($sql_borrar);
                
                //boora los background
                $sql_borrar = "delete from z903_background where cod_sitio=$id_sitio";
                $result_borrar = $mysqli->query($sql_borrar);
            
                $sql_borrar = "delete from z902_formularios where cod_sitio=$id_sitio";
                $result_borrar = $mysqli->query($sql_borrar);
            
                $sql_borrar = "delete from z901_facebooks where cod_sitio=$id_sitio";
                $result_borrar = $mysqli->query($sql_borrar);
            
                $sql_borrar = "delete from z901_twitters where cod_sitio=$id_sitio";
                $result_borrar = $mysqli->query($sql_borrar);
                
                
                
                //boora los zonas_editables
                $sql_borrar = "delete from z9_zonas_editables where cod_sitio=$id_sitio";
                $result_borrar = $mysqli->query($sql_borrar);
                
                
                // Borro la configuracion
                $sql_del_config = "delete from z9_config where id_empresa = $id_empresa";
                $result_del_config = $mysqli->query($sql_del_config);
                
                
                
                
                
                if ($no_imprimo_mensaje_pantalla!=1)
                {
                    $mensaje_pantalla.="Se borraron las paginas con sus imagenes.Ademas se borro el registro de configuración.<br>";
                }
                
                
                if (file_exists($url_imagenes_aux."carga_imagenes/imagenes/$id_empresa/galerias_se"))
                {
                	delete_directory($url_imagenes_aux."carga_imagenes/imagenes/$id_empresa/galerias_se");
                }
            
                if (file_exists($url_imagenes_aux."carga_imagenes_jquery/imagenes/9/$id_empresa/"))
                {
                	delete_directory($url_imagenes_aux."carga_imagenes_jquery/imagenes/9/$id_empresa/");
                }
            
                
                
                
                echo $mensaje_pantalla;
                
                
                
                $senal=1;
            
            }else{
                $obs_log_baja.="No se encontro el sitio a borrar para la empresa $id_empresa <br>";
                $senal=1;
            }    
            
            
        }//end if ($id_sistema==9)//SITIO EXPRESS
            
    }//while ($myrow_sistemas = $result_sistemas->fetch_assoc())



    
    /*
    if ($no_imprimo_mensaje_pantalla!=1)
    {  	     
    	echo "<h2>Log Baja Sitio Express # $id_log_baja</h2><textarea>$obs_log_baja</textarea>";
    }*/

    if ($id_log_baja and (!empty($obs_log_baja)))
    {
        //actualizo el log
        $sql = "update log_bajas set observaciones='$obs_log_baja ------------ $mensaje_pantalla' where id_log = $id_log_baja";
        $result = $mysqli->query($sql);
        
    }




    if ($senal!=1)
    {
       if ($no_imprimo_mensaje_pantalla!=1)
       {
       echo "No se pudo borrar el contenido del sistema para la empresa. Puede deberse a que el sistema sea el buscador, aunque puede ser otro el motivo.<br>";
       }
       return false;
    }else    
       return true; 
    
    




}//END function borrar sistema







function comprobar_email($email){
    $mail_correcto = 0;
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1){
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
    }
    if ($mail_correcto)
       return 1;
    else
       return 0;
}





?>