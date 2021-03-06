<?php
class tepuy_spg_c_estprog3
{
var $is_msg_error;
	
		function tepuy_spg_c_estprog3($conn)
		{
		  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	      require_once("../../shared/class_folder/class_funciones.php");
          require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("class_folder/tepuy_spg_c_estprog4.php");
		  $this->io_estpro4    = new tepuy_spg_c_estprog4($conn);
		  $this->io_seguridad  = new tepuy_c_seguridad();		  
		  $this->io_funcion    = new class_funciones();
		  $this->io_sql        = new class_sql($conn);
		  $this->io_msg        = new class_mensajes();	
		  $this->ls_modalidad= $_SESSION["la_empresa"]["estmodest"];	
		}

function uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_select_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  C�digo de la Empresa.
//    $as_codestpro1:  C�digo del Primer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro2:  C�digo del Segundo Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro3:  C�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  12/09/2006       Fecha �ltima Actualizaci�n:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = " SELECT * FROM spg_ep3                                                          ".
               "  WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
               "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'     ";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_spg_select_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if($li_numrows>0)
		 {
		   $lb_valido=true;
		   $this->io_sql->free_result($rs_data);
		 }
	 }
  return $lb_valido;
}

function uf_spg_insert_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro3,$as_codfuefin,$as_responsable,$as_extordinal,$ai_estmodest,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_insert_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  C�digo de la Empresa.
//    $as_codestpro1:  C�digo del Primer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro2:  C�digo del Segundo Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro3:  C�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_denestpro3:  Denominaci�n del c�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//     $aa_seguridad:  Arreglo cargado con la informaci�n acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  12/09/2006       Fecha �ltima Actualizaci�n:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	  if($as_codfuefin=='')
	  {
	  		$as_codfuefin='--';
	  }
	 
	  $ls_sql = " INSERT INTO spg_ep3 (codemp,codestpro1,codestpro2,codestpro3,denestpro3,codfuefin,responsable,extordinal) ".
	//            " VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_denestpro3."','".$as_codfuefin."','".$as_responsable.".$')";
	  " VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_denestpro3."','".$as_codfuefin."','".$as_responsable."','".$as_extordinal.".$')";
	 // print $ls_sql;
	  $this->io_sql->begin_transaction();
	  $rs_data = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)		     
		 {
		   $lb_valido          = false;
 	       $this->is_msg_error = "CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_spg_insert_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	  else
		 {
		   $lb_valido      = true;
		   if ($ai_estmodest=='1')
		      {
			    $lb_valido = $this->io_estpro4->uf_spg_insert_estprog4($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,'00','NINGUNO',$ai_estmodest,$aa_seguridad);
			    if (!$lb_valido)
				   {
				     print $this->io_sql->message;
					 $lb_valido = false;
				     $this->is_msg_error = "CLASE->tepuy_SPG_C_ESTPROG4; METODO->uf_spg_insert_estprog4(Insert Nivel 4 Default); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				   }
			  }
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento      = "INSERT";
		   $ls_descripcion = "Insert� en SPG Nuevo Estructura Presupuestaria/programatica ".$as_denestpro3." con codigo ".$as_codestpro3." asociado al Nivel 1 con ".$as_codestpro1." y con el Nivel 2 a ".$as_codestpro2;
		   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}

function uf_spg_update_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro3,$as_codfuefin,$as_responsable,$as_extordinal,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_update_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  C�digo de la Empresa.
//    $as_codestpro1:  C�digo del Primer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro2:  C�digo del Segundo Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro3:  C�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//     $aa_seguridad:  Arreglo cargado con la informaci�n acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o Program�tica, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  12/09/2006       Fecha �ltima Actualizaci�n:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  
  if($this->ls_modalidad==2)
  {
  	$as_codfuefin='--';
  	
  }
 // $ls_sql =" UPDATE spg_ep3 SET denestpro3='".$as_denestpro3."' , codfuefin='".$as_codfuefin."', responsable='".$as_responsable."'                                                         ".
  $ls_sql =" UPDATE spg_ep3 SET denestpro3='".$as_denestpro3."' , codfuefin='".$as_codfuefin."', responsable='".$as_responsable."', extordinal='".$as_extordinal."'                                                         ".
           "  WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
		   "        codestpro3='".$as_codestpro3."'                                                                     ";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_spg_update_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido = true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "UPDATE";
	   $ls_descripcion = " Actualizo el codigo ".$as_codestpro3." en spg_ep3 asociado al codigo ".$as_codestpro1."en spg_ep1 y al codigo ".$as_codestpro2." en la tabla spg_ep2";
	   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
}

function uf_spg_delete_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro,$ai_estmodest,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_delete_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  C�digo de la Empresa.
//    $as_codestpro1:  C�digo del Primer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro2:  C�digo del Segundo Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro3:  C�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//     $aa_seguridad:  Arreglo cargado con la informaci�n acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o 
//                     Program�tica, la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  12/09/2006       Fecha �ltima Actualizaci�n:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

	$lb_valido = false;
	$lb_tiene  = false;
	$lb_existe = $this->uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3);
	if ($lb_existe)
	   {
		 $lb_valdelete = true;
		 if ($ai_estmodest=='1')
		    {
			  $lb_valdelete = $this->uf_delete_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$aa_seguridad);	
			}
	     else
		    {
	          $lb_tiene  = $this->uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3);
			}
		 if (($lb_valdelete) && (!$lb_tiene))
	        {
		      $ls_sql = " DELETE FROM spg_ep3 ".
		                "  WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
		                "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'    ";
			  $this->io_sql->begin_transaction();
              $rs_data=$this->io_sql->execute($ls_sql);
              if ($rs_data===false)
	             {
	               $lb_valido=false;
 	               $this->is_msg_error="CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_spg_delete_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	             }
              else
	             {
	               $lb_valido=true;
	               /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	               $ls_evento      = "DELETE";
		           $ls_descripcion = "Elimin� en el Codigo Programatico ".$as_codestpro3." en spg_ep3 asociado a ".$as_codestpro1." en spg_ep1 y a ".$as_codestpro2." en spg_ep2";
		           $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		           $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		           $aa_seguridad["ventanas"],$ls_descripcion);
		           /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	             }
 	        }	  		 
       }
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  C�digo de la Empresa.
//    $as_codestpro1:  C�digo del Primer  Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro2:  C�digo del Segundo Nivel de la Estructura Presupuestaria o Program�tica.
//    $as_codestpro3:  C�digo del Tercer  Nivel de la Estructura Presupuestaria o Program�tica.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de verificar si existen tablas relacionadas al C�digo de la Clasificaci�n. 
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = false;
  $ls_sql  = "SELECT * FROM spg_cuentas                                                      ".
             " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
             "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'     ";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	  {
		$lb_valido=false;
        $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
		else
		   {
			 $ls_sql = "SELECT codestpro3                                                              ".
			           "  FROM spg_ep4                                                                 ".
					   " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
			           "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'     ";
			 $rs_data = $this->io_sql->select($ls_sql);
             if ($rs_data===false)
	            {
		          $lb_valido=false;
                  $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG3; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	            }
	         else
	            {
		          if ($row=$this->io_sql->fetch_row($rs_data))
		             {
					   $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
					   $lb_valido=true;
	 	             }
	            }
		   }
	  }
	return $lb_valido;	
}

function uf_delete_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$aa_seguridad)
{
	$lb_valido = false;
	$lb_existe = $this->uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3);
	if ($lb_existe)
	   {
		$lb_valido          = $this->uf_verificar_movimientos($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3);
		$lb_relacion_otros  = $this->uf_spg_check_relaciones("spg_unidadadministrativa","*"," codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='00' AND codestpro5='00'" );
		$lb_relacion_otros2 = $this->uf_spg_check_relaciones("spg_cuentas","*"," codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='00' AND codestpro5='00'" );				
		if ((!$lb_valido)&&(!$lb_relacion_otros)&&(!$lb_relacion_otros2))
		   {
			 $ls_sql  = "DELETE FROM spg_ep5                                                               ".
			            " WHERE  codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
					    "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
					    "        codestpro4='00'                 AND codestpro5='00'                      ";
			 $rs_data = $this->io_sql->execute($ls_sql);				
		     if ($rs_data===false)
			    {
				  $lb_valido=false;
				  $this->is_msg_error="Error Eliminando spg_ep5,".$io_funcion->uf_convertirmsg($this->io_sql->message);
			    }
			 else
			    {
				  $lb_valido      = true;
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				  $ls_evento      = "DELETE";
				  $ls_descripcion = "Elimino el codigo 00 asociado al codigo ".$as_codestpro1.",".$as_codestpro2.",".$as_codestpro3.",00,00 en la tabla spg_epg5";
				  $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				  $aa_seguridad["ventanas"],$ls_descripcion);
				  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				  $ls_sql    = "DELETE FROM spg_ep4 ".
				               " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
							   "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
							   "       codestpro4='00'";
				  $rs_data = $this->io_sql->execute($ls_sql);
				  if ($rs_data===false)
				     {
					   $lb_valido=false;
					   $this->is_msg_error="Error Eliminando en spg_ep4,".$io_funcion->uf_convertirmsg($this->io_sql->message);						
				     }
				  else
				     {
					   $lb_valido       = true;
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					   $ls_evento      = "DELETE";
					   $ls_descripcion = "Elimino el codigo 00 asociado al codigo ".$as_codestpro1.",".$as_codestpro2.",".$as_codestpro3.",00 en la tabla spg_epg4";
					   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					   $aa_seguridad["ventanas"],$ls_descripcion);
					   /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
					 }						
			    }	
		   }
		else
		   {
			 $lb_valido=false;
		   	 $this->is_msg_error="Error al eliminar Codigo programatico, hay registros asociados a la estructura !!!";						
		   }
	   }
	return $lb_valido;
}

function uf_spg_check_relaciones($as_tabla,$as_campo,$as_where)
{
	$lb_valido = false;
	$ls_sql    = "SELECT ".$as_campo." FROM ".$as_tabla." WHERE ".$as_where;
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {  
	     $lb_valido          = false;
		 $this->is_msg_error = "No existe el codigo programatico".$io_funcion->uf_convertirmsg($this->io_sql->message);		
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
	   		  $this->io_sql->free_result($rs_data);
			}	
	   }	
   return $lb_valido;
}

function uf_verificar_movimientos($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
	$ls_sql = "SELECT * FROM spg_dt_cmp ".
	          " WHERE codemp='".$as_codemp."'        AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			  "       codestpro3=".$as_codestpro3."' AND codestpro4='00'                 AND codestpro5='00'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
	   }
	else
	   {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El codigo programatico posee movimientos relacionados";
		   }	
		else
		   {
			 $lb_valido=false;
		   }
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}

function uf_insert_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_evento,$as_descripcion)
{
	$lb_valido            = false;
	$as_codestprog4       = "00";
	$as_codestprog5       = "00";
	$as_denestadicionales = "Ninguno";
	$lb_valido            =$this->uf_spg_insert_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_denestadicionales,&$as_evento,&$as_descripcion);
	if ($lb_valido)
	   {
	     $lb_valido=$this->uf_spg_insert_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5,$as_denestadicionales,&$as_evento,&$as_descripcion);
	   }
	return $lb_valido;
}
}
?>
