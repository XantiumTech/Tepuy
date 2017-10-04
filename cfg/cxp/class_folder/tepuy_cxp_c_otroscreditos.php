<?php 
class tepuy_cxp_c_otroscreditos
{
	var $ls_sql;
	var $is_msg_error;

	function tepuy_cxp_c_otroscreditos($conn)
	{
	  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new tepuy_c_seguridad();	
	  $this->io_funcion = new class_funciones();
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes($conn);
	}

	function uf_insert_otroscreditos($as_codemp,$ar_datos,$ai_estmodest,$aa_seguridad) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo: uf_insert_otroscreditos
	//	Access:  public
	//	Arguments:  $as_codemp,$ar_datos,$aa_seguridad
	//	Returns: $lb_valido= Variable booleana que devuelve true si la sentencia
	//                       SQL fue ejecutada sin errores de lo contrario devuelve false.
	//	Description: Función que se encarga de insertar un registro en la tabla
	//              tepuy_cargos.     
	//////////////////////////////////////////////////////////////////////////////

	$ls_codigo       = $ar_datos["codigo"];
	$ls_denominacion = $ar_datos["denominacion"];
	$ls_spgcuenta    = $ar_datos["spg_cuenta"];
	$ls_codestpro    = $ar_datos["codestpro"];
	if ($ai_estmodest=='1')
	   {
	     $ls_codestpro  = substr($ls_codestpro,0,20).substr($ls_codestpro,21,6).substr($ls_codestpro,28,3);   
	   	 $ls_codestpro4 = '00';
	     $ls_codestpro5 = '00';
	     $ls_codestpro  = $ls_codestpro.$ls_codestpro4.$ls_codestpro5;
	   }
	else
	   {
	     $ls_codestpro1 = substr($ls_codestpro,0,2);
		 $ls_codestpro1 = $this->io_funcion->uf_cerosizquierda($ls_codestpro1,20);
		 $ls_codestpro2 = substr($ls_codestpro,3,2);
		 $ls_codestpro2 = $this->io_funcion->uf_cerosizquierda($ls_codestpro2,6);
		 $ls_codestpro3 = substr($ls_codestpro,6,2);
		 $ls_codestpro3 = $this->io_funcion->uf_cerosizquierda($ls_codestpro3,3);
		 $ls_codestpro4 = substr($ls_codestpro,9,2);
		 $ls_codestpro5 = substr($ls_codestpro,12,2);   
		 $ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
	   }
	$ld_porcentaje = $ar_datos["porcentaje"];
	if (empty($ld_porcentaje))
	   {
	     $ld_porcentaje=0;
	   }
	else
	   {
	     $ld_porcentaje = str_replace('.','',$ld_porcentaje);
 	     $ld_porcentaje = str_replace(',','.',$ld_porcentaje);
	   }   
	$li_estlibcompras   = $ar_datos["estlibcompras"];
	$ls_formula         = $ar_datos["formula"];
	$ls_sql             = " INSERT INTO tepuy_cargos                                                                         ".
			              " (codemp,codcar,dencar,codestpro,spg_cuenta,porcar,estlibcom,formula)                              ".
			              " VALUES                                                                                            ".
			              " ('".$as_codemp."','".$ls_codigo."','".$ls_denominacion."','".$ls_codestpro."','".$ls_spgcuenta."',".
			              " ".$ld_porcentaje.",".$li_estlibcompras.",'".$ls_formula."')                                       ";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_msg->message("CLASE->tepuy_CXP_C_OTROSCREDITOS; METODO->uf_insert_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="INSERT";
	     $ls_descripcion ="Insertó en CXP el Cargo ".$ls_denominacion." con código ".$ls_codigo;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }
	return $lb_valido;
	}

function uf_update_otroscreditos($as_codemp,$ar_datos,$ai_estmodest,$aa_seguridad) 
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Metodo:  uf_update_otroscreditos
	//	     Access:  public
	//	  Arguments:  $as_codemp,$ar_datos,$aa_seguridad
	//	    Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
	//                SQL fue ejecutada sin errores de lo contrario devuelve false.
	//	Description:  Función que se encarga de actualizar registros en la tabla
	//                tepuy_cargos.   
	//////////////////////////////////////////////////////////////////////////////
	$ls_codigo       = $ar_datos["codigo"];
	$ls_denominacion = $ar_datos["denominacion"];
	$ls_spgcuenta    = $ar_datos["spg_cuenta"];
	$ls_codestpro    = $ar_datos["codestpro"];
	if ($ai_estmodest=='1')
	   {
	     $ls_codestpro  = substr($ls_codestpro,0,20).substr($ls_codestpro,21,6).substr($ls_codestpro,28,3);   
	   	 $ls_codestpro4 = '00';
	     $ls_codestpro5 = '00';
	     $ls_codestpro  = $ls_codestpro.$ls_codestpro4.$ls_codestpro5;
	   }
	else
	   {
	     $ls_codestpro1 = substr($ls_codestpro,0,2);
		 $ls_codestpro1 = $this->io_funcion->uf_cerosizquierda($ls_codestpro1,20);
		 $ls_codestpro2 = substr($ls_codestpro,3,2);
		 $ls_codestpro2 = $this->io_funcion->uf_cerosizquierda($ls_codestpro2,6);
		 $ls_codestpro3 = substr($ls_codestpro,6,2);
		 $ls_codestpro3 = $this->io_funcion->uf_cerosizquierda($ls_codestpro3,3);
		 $ls_codestpro4 = substr($ls_codestpro,9,2);
		 $ls_codestpro5 = substr($ls_codestpro,12,2);   
		 $ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
	   }
	   $ld_porcentaje    = $ar_datos["porcentaje"];
	   $li_estlibcompras = $ar_datos["estlibcompras"];	
	   $ls_formula       = $ar_datos["formula"];
	   $ls_sql=" UPDATE tepuy_cargos ".
				" SET  dencar='".$ls_denominacion."',codestpro='".$ls_codestpro."',spg_cuenta='".$ls_spgcuenta."',".
				" porcar=".$ld_porcentaje.",estlibcom=".$li_estlibcompras.",formula='".$ls_formula."' ".
				" WHERE codemp='" .$as_codemp. "' AND codcar = '" .$ls_codigo. "'";
	   $this->io_sql->begin_transaction();
	   $li_numrows=$this->io_sql->execute($ls_sql);
	   if ($li_numrows===false)
	   {
		 $this->io_msg->message("CLASE->tepuy_CXP_C_OTROSCREDITOS; METODO->uf_update_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	   else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  	 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en CXP el Cargo con código ".$ls_codigo;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }
	return $lb_valido;
	} 

function uf_delete_otroscreditos($as_codemp,$as_codigo,$as_dencar,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_delete_otroscreditos
//	Access:  public
//	Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	Returns: $lb_valido= Variable booleana que devuelve true si la sentencia
//                       SQL fue ejecutada sin errores de lo contrario devuelve false.
//	Description: Funcion que se encarga de Eliminar registros en la tabla 
//               tepuy_cargos.
//////////////////////////////////////////////////////////////////////////////
  $lb_valido = false;
  $ls_sql    = "DELETE FROM tepuy_cargos WHERE codemp='".$as_codemp."' AND codcar='".$as_codigo."'";	    
  $this->io_sql->begin_transaction();
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->tepuy_CXP_C_OTROSCREDITOS; METODO->uf_delete_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
     {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion =" Eliminó en CXP el Cargo con código ".$as_codigo." con denominación ".$as_dencar;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   $lb_valido=true;
	 }	   		 
  return $lb_valido;
}

function uf_select_otroscreditos($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_select_otroscreditos
// 	     Access:  public
//	   Arguments  $as_codemp,$as_codigo
//	    Returns:  $lb_valido= Variable booleana que devuelve true si la fue
//                encontrado el registro y la sentencia SQL 
//                fue ejecutada sin errores de lo contrario devuelve false.	
//	Description:  Función que se encarga de buscar registros en la tabla
//                tepuy_cargos.
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" SELECT * FROM tepuy_cargos WHERE codemp='".$as_codemp."' AND codcar='".$as_codigo."'";
	$rs_otroscred=$this->io_sql->select($ls_sql);
	if ($rs_otroscred===false)
	   {
		 $this->io_msg->message("CLASE->tepuy_CXP_C_OTROSCREDITOS; METODO->uf_select_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_otroscred);
		 if ($li_numrows>0)
			{
			  $lb_valido=true;
			  $this->io_sql->free_result($rs_otroscred);
			}
		 else
			{
			  $lb_valido=false;
			}
		}
return $lb_valido;
}
//-------------------------------------------------------------------------------------------------------------------------------------
function uf_select_configuracion_iva($as_codemp,&$as_confiva) 
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Metodo:  uf_select_configuracion_iva
	// 	     Access:  public
	//	   Arguments  $as_codemp,$as_codigo
	//	    Returns:  $lb_valido= Variable que devuelve la configuraciondel iva si es contable
	//                o presupuestario.	
	//	Description:  Función que se encarga de buscar registros en la tabla
	//                tepuy_cargos.
	//////////////////////////////////////////////////////////////////////////////
    $lb_valido=false;
	$ls_sql=" SELECT * FROM tepuy_empresa WHERE codemp='".$as_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
	   $this->io_msg->message("CLASE->tepuy_CXP_C_OTROSCREDITOS; METODO->uf_select_configuracion_iva; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	}
	else
	{
	  /* $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)*/
	   while($row=$this->io_sql->fetch_row($rs_data))
	   {
		  $lb_valido=true;
		  $as_confiva=$row["confiva"];
	   }
	   $this->io_sql->free_result($rs_data);
	}
    return $lb_valido;
}
//-------------------------------------------------------------------------------------------------------------------------------------
}//Fin de la Clase...
?> 