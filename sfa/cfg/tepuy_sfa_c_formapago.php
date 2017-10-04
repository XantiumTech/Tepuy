<?php
class tepuy_sfa_c_formapago
{
var $ls_sql;
var $is_msg_error;
	
	function tepuy_sfa_c_formapago($conn)
	{
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/tepuy_c_seguridad.php");	  
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->seguridad  = new tepuy_c_seguridad();		 
	  $this->io_funcion = new class_funciones();
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();		
	}

function uf_insert_formapago($as_codemp,$as_codigo,$as_den,$as_denabr,$as_sc_cuenta,$aa_seguridad) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Function:  uf_insert_formapago
	//	          Access:  public
	//	       Arguments:
	//         as_codigo:  Código de la Forma de Pago
	//         as_den:  Denominación de la Forma de Pago
	//         as_denabr:  Denominación Abreviada
	//      as_sc_cuenta:  Cuenta Contable
	//      aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc. 
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un nuevo Forma de Pago en la tabla soc_formapago. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  20/12/2016 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$ls_sql=" INSERT INTO sfa_formapago (codemp, codfor, denfor, forpag, sc_cuenta) VALUES ('".$as_codemp."','".$as_codigo."','".$as_den."','".$as_denabr."','".$as_sc_cuenta."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
         $lb_valido=false;
		 $this->io_msg->message("CLASE->tepuy_sfc_c_formapago; METODO->uf_insert_formapago; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en SFA el Forma de Pago ".$as_den." con código ".$as_codigo;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	 }
     return $lb_valido;
}

function uf_update_formapago($as_codemp,$as_codigo,$as_den,$as_denabr,$as_sc_cuenta,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Function: uf_update_formapago
	//	          Access:  public
	//	       Arguments:
	//         as_codigo:  Código de la Forma de Pago
	//         as_den:  Denominación de la Forma de Pago
	//         as_denabr:  Denominación Abreviada
	//      as_sc_cuenta:  Cuenta Contable
	//      aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de actualizar un Forma de Pago en la tabla sfa_formapago. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  20/12/2016
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	$ls_sql=" UPDATE sfa_formapago SET denfor='".$as_den."', forpag='".$as_denabr."', sc_cuenta='".$as_sc_cuenta."' WHERE codemp='".$as_codemp."' AND codfor='" .$as_codigo. "'";
	//print $ls_sql;
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->tepuy_sfc_c_formapago; METODO->uf_update_formapago; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó en SFA Forma de Pago ".$as_codigo;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		 }
      return $lb_valido;
} 

function uf_delete_formapago($as_codemp,$as_codigo,$as_den,$as_denabr,$as_sc_cuenta,$aa_seguridad)
{          		 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Método:  uf_delete_formapago
	//	          Access:  public
	//	        Arguments
	//         as_codigo:  Código de la Forma de Pago
	//         as_den:  Denominación de la Forma de Pago
	//         as_denabr:  Denominación Abreviada
	//      as_sc_cuenta:  Cuenta Contable 
	//	   $aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de eliminar un Forma de Pago en la tabla soc_formapago. 
	//     Elaborado Por:  Ing. Miguel Palencia.
	// Fecha de Creación:  20/02/2006       Fecha Última Actualización:27/03/2006.	 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   $lb_valido=false;
   $ls_sql = " DELETE FROM sfa_formapago WHERE codfor='".$as_codigo."'";	    
   $this->io_sql->begin_transaction();
   $rs_data=$this->io_sql->execute($ls_sql);
   if ($rs_data===false)
      {
	    $lb_valido=false;
	    $this->io_msg->message("CLASE->tepuy_sfc_c_formapago; METODO->uf_delete_formapago; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	    $lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó en SFA Forma de Pago ".$as_codigo." con denominación ".$as_den;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	  }	    		 
   return $lb_valido;
}

function uf_select_formapago($as_codigo) 
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	        Function:  uf_select_formapago
//	          Access:  public
//	       Arguments:
//         as_codigo:  Código del Forma de pago
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un Forma de Pago, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false.
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    =	" SELECT sfa_formapago.*, scg_cuentas.denominacion FROM sfa_formapago ".
		" INNER JOIN scg_cuentas ON sfa_formapago.sc_cuenta=scg_cuentas.sc_cuenta".
		" WHERE sfa_formapago.codfor='".$as_codigo."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_sfc_c_formapago; METODO->uf_select_formapago; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    $lb_valido=true;
	      }
	 }
  return $lb_valido;
}
}// Fin de la Clase tepuy_sep_c_formapago.
?> 
