<?php
class tepuy_cxp_c_inicio_contadores
{
	var $ls_sql;
	var $is_msg_error;
//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_cxp_c_inicio_contadores($conn)
	{
	  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new tepuy_c_seguridad();		           
	  $this->io_funcion = new class_funciones();		  
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();
	  $this->io_database  = $_SESSION["ls_database"];
	  $this->io_gestor    = $_SESSION["ls_gestor"];
	}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_nro_control_id($as_codemp,$as_id) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_nro_control_id
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no un codigo id, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  14/03/2014       Fecha Última Actualización:.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $lb_valido = false;
    $ls_sql    = " SELECT * ".
                 " FROM   cxp_contador ".
                 " WHERE  codemp='".$as_codemp."'   AND codcmp='".$as_id."' ";
	$rs_data   = $this->io_sql->select($ls_sql);
	//print $ls_sql;
    if ($rs_data===false)
    {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_cxp_c_inicio_contadores; METODO->uf_select_nro_control_id; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
    }
    else
    {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	   {
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	   }
    } 
    return $lb_valido;
}
//----------------------------------------------------------------------------------------------------------------------------
function uf_update_contador($ls_codemp,$ls_id,$ls_retencion,$li_nro_inicial,$ls_deduccion,$ls_estado,$la_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_update_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de actualizar los registros, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  16/03/2014       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
   $ls_sql=" UPDATE cxp_contador ".
           " SET    nom='".$ls_retencion."',numcmp='".$li_nro_inicial.
           "', tipo='".$ls_deduccion."', estado='".$ls_estado."' WHERE  codemp='".$ls_codemp."' AND codcmp='".$ls_id."' ";
	//print $ls_sql;
  // $this->io_sql->begin_transaction();
  // $rs_data=$this->io_sql->execute($ls_sql);
	$li_numrows=$this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
   if ($rs_data===false)
   {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->tepuy_cxp_c_inicio_contadores; METODO->uf_update_contador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion ="Actualizo el contador  ".$as_id." del sistema ".$as_codsis;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										 $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
    return $lb_valido;
} 
//-----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------
function uf_guardar_contador($as_codemp,$as_id,$ls_retencion,$li_nro_inicial,$ls_deduccion,$ls_estado,$la_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_guardar_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//                     $ai_nro_inicial ----> nro inicial
	//                     $ai_nro_final   ----> nro final
	//                     $as_prefijo     ----> prefijo
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un registro, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  16/03/2014       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql=" INSERT INTO cxp_contador (codemp,codcmp,nom,numcmp,tipo,estado) VALUES (".
           " '".$as_codemp."','".$as_id."','".$ls_retencion."','".$li_nro_inicial."','".$ls_deduccion."','".$ls_estado."')";
	//print $ls_sql;
	//$ls_sql = " INSERT INTO tepuy_ctrl_numero ".
	//		  " (codemp, codsis, procede, id, prefijo, nro_inicial, nro_final, maxlen, nro_actual, estidact) ".
	//		  " VALUES ('".$as_codemp."','".$as_codsis."','".$as_procede."','".$as_id."','".$as_prefijo."','".$ai_nro_inicial."', ".
	//		  "         '".$ai_nro_final."','".$li_maxlen."','".$li_nro_actual."','".$ls_estidact."')";
	$li_numrows=$this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
	if ($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_cxp_c_inicio_contadores; METODO->uf_guardar_contador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Actualizo el contador  ".$as_id." del sistema ".$as_codsis;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
	}
    return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_delete_contador($as_codemp,$as_id,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_guardar_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un registro, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  16/03/2014       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql = " DELETE FROM cxp_contador WHERE codemp='".$as_codemp."' AND codcmp='".$as_id."'";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
	if ($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_cxp_c_inicio_contador; METODO->uf_delete_contador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Elimino el contador de retenciones  ".$as_id;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
	}
    return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------


}//Fin de la Clase...
?> 
