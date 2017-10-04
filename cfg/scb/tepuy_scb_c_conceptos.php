<?php
class tepuy_scb_c_conceptos
{

 var $io_sql;
 var $fun;
 var $siginc;
 var $datemp;
 var $is_msg_error;
 var $io_seguridad;
 var $is_empresa;
 var $is_sistema;
 var $is_logusr;
 var $is_ventanas;
 
function tepuy_scb_c_conceptos($aa_security)
{
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$this->fun=new class_funciones();
	$this->siginc=new tepuy_include();
	$con=$this->siginc->uf_conectar();
	$this->datemp=$_SESSION["la_empresa"];
	$this->is_empresa = $aa_security[1];
	$this->is_sistema = $aa_security[2];
	$this->is_logusr  = $aa_security[3];	
	$this->is_ventana = $aa_security[4];
	$this->io_seguridad= new tepuy_c_seguridad();	
	$this->io_sql=new class_sql($con);
}
		
function uf_select_conceptos($ls_codigo,$ls_codope)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_cadena="SELECT * FROM scb_concepto WHERE codconmov='".$ls_codigo."'";
	
	$rs_data=$this->io_sql->select($ls_cadena);

	if($rs_data===false)//Hubo error en la consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else//No hubo error en la consulta
	{
		if($row=$this->io_sql->fetch_row($rs_data))//Encontro registgros
		{
			$lb_valido=true;
		}
		else//No encontro registros
		{
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado";
		}
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;


}
	
function uf_guardar_conceptos($ls_codigo,$ls_codope,$ls_denominacion,$ls_status)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe=$this->uf_select_conceptos($ls_codigo,$ls_codope);//Verifico si existe

	if(!$lb_existe)//Si no existe lo inserto
	{
		$ls_cadena= " INSERT INTO scb_concepto(codconmov,denconmov,codope) VALUES('".$ls_codigo."','".$ls_denominacion."','".$ls_codope."') ";
		$this->is_msg_error="Registro Incluido";		
		////////////////////////////////////Parametros de seguridad////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el concepto de movimiento ".$ls_codigo." con denominacion ".$ls_denominacion." y la operacion ".$ls_codope ;
		///////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else//Existe por tanto actualizo
	{
		if($ls_status=='C')
		{
			$ls_cadena= " UPDATE scb_concepto SET denconmov='".$ls_denominacion."' WHERE codconmov='".$ls_codigo."' ";
			$this->is_msg_error="Registro Actualizado";
			////////////////////////////////////Parametros de seguridad////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el concepto de movimiento ".$ls_codigo." con denominacion ".$ls_denominacion." y la operacion ".$ls_codope ;
			///////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		{
			$this->is_msg_error="Registro ya existe introduzca un nuevo codigo";
			return false;
		}
	}

	$this->io_sql->begin_transaction();//Inicio la transaccion

	$li_numrows=$this->io_sql->execute($ls_cadena);//Ejecuto la sentencia io_sql y retorno numero de filas afectadas

	if($li_numrows===false)//Verifico si hubo error enla consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_conceptos".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->is_msg_error;
	}
	else
	{
		$lb_valido=true;
		$this->io_sql->commit();//Hago commit de la transaccion
		//////////////////////////////////////////Inserto eventos en seguridad/////////////////////////////////////////////		
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	return $lb_valido;
}

function uf_delete_conceptos($ls_codigo,$ls_codope,$ls_denominacion,$ls_status)
{
//////////////////////////////////////////////////////////////////////////////////////////
// Function:    - uf_delete_conceptos
// Parameters:  - $ls_codigo( Codigo del concepto).		
//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
//			    - $ls_denominacion (Denominacion del concepto).
// Descripcion: - Funcion que elimina el concepto.
//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "DELETE FROM scb_concepto WHERE codconmov='".$ls_codigo."'";
	$this->io_sql->begin_transaction();//Inicio la transaccion.
	$rs_data   = $this->io_sql->execute($ls_sql);//Ejecuto la sentencia io_sql.
	if ($rs_data===false)//Verifico si hubo error en la consulta.
	   {
	     $lb_valido=false;
	  	 $this->is_msg_error="Error en metodo uf_delete_conceptos ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
	     $lb_valido=true;
		 ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino el concepto de movimiento ".$ls_codigo." con denominacion ".$ls_denominacion." y la operacion ".$ls_codope ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	return $lb_valido;
}
}
?>