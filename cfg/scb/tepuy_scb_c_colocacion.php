<?php
class tepuy_scb_c_colocacion
{
	var $io_sql;
	var $is_msg_error;
	var $fun;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	var $dat;
	var $fec;
	function tepuy_scb_c_colocacion($aa_security)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_fecha.php");
		$this->fec=new class_fecha();
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->fun=new class_funciones();
		$this->is_empresa = $aa_security[1];
		$this->is_sistema = $aa_security[2];
		$this->is_logusr  = $aa_security[3];	
		$this->is_ventana = $aa_security[4];
		$this->io_seguridad= new tepuy_c_seguridad();
		$this->dat=$_SESSION["la_empresa"];	
	}

function uf_select_colocacion($as_codban,$as_ctaban,$as_colocacion,$as_cuentascg,$as_cuentaspi)
{
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT * FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND numcol='".$as_colocacion."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
		$as_status=0;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$this->is_msg_error="Numero de Colocacion ya existe";
		}
		else
		{
			$lb_valido=false;
			$as_status=0;
			$this->is_msg_error="No encontro registro";
		}
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;
}
	
function uf_guardar_colocacion($as_colocacion,$as_dencol,$as_codban,$as_cuenta_banco,$as_tasa,$ad_desde,$ad_hasta,$adec_monto,$adec_interes,$as_codtipcol,$as_cuentascg,$as_cuentaspi,$ai_estrei,$as_plazo )
{
	$li_desde=0;$li_hasta=0;
	//$lb_existe=$this->uf_select_cheques($as_codban,$as_ctaban,$as_cheque,$as_chequera,&$li_status);
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_desde);
	$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_hasta);
	$as_tasa=str_replace('.','',$as_tasa);
	$as_tasa=str_replace(',','.',$as_tasa);	
	/*$adec_monto=str_replace('.','',$adec_monto);
	$adec_monto=str_replace(',','.',$adec_monto); print "reemplazando coma por punto".$adec_monto."<br>";	
	$adec_interes=str_replace('.','',$adec_interes);
	$adec_interes=str_replace(',','.',$adec_interes);	print "reemplazando coma por punto".$adec_interes;	*/
	$lb_existe=$this->uf_select_colocacion($as_codban,$as_cuenta_banco,$as_colocacion,$as_cuentascg,$as_cuentaspi);
	//print $as_plazo."<br>"; print $as_tasa."<br>"; print $adec_monto."<br>";print $adec_interes."<br>"; 
	if(!$lb_existe)
	{
		$ls_sql= " INSERT INTO scb_colocacion(codemp, codban, ctaban, numcol, dencol, codtipcol, feccol, diacol,    ".
		         "                            tascol, monto,fecvencol, monint, sc_cuenta, spi_cuenta, estreicol)    ".
				 " VALUES                     ('".$ls_codemp."','".$as_codban."','".$as_cuenta_banco."',            ".
				 "                             '".$as_colocacion."','".$as_dencol."','".$as_codtipcol."',           ".
				 "                             '".$ld_fecdesde."','".$as_plazo."','".$as_tasa."','".$adec_monto."', ".
				 "                             '".$ld_fechasta."','".$adec_interes."','".$as_cuentascg."',          ".
				 "                             '".$as_cuentaspi."',".$ai_estrei.") ";
		$this->is_msg_error="Registro Incluido";		
		$ls_evento="INSERT";
		$ls_descripcion="Inserto la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	}
	else
	{
		$ls_sql= " UPDATE scb_colocacion ".
		         " SET    dencol='".$as_dencol."',feccol='".$ld_fecdesde."',diacol='".$as_plazo."', ".
				 "        tascol=".$as_tasa.",monto=".$adec_monto.",monint=".$adec_interes.",       ".
				 "        estreicol=".$ai_estrei.",fecvencol='".$ld_fechasta."'                     ".
				 " WHERE  codemp='".$ls_codemp."' AND codban='".$as_codban."' AND                   ".
				 "        ctaban='".$as_cuenta_banco."' AND numcol='".$as_colocacion."'";
		$this->is_msg_error="Registro Actualizado";
		$ls_evento="UPDATE";
		$ls_descripcion="Actualizo la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	}
	
	$this->io_sql->begin_transaction();

	$li_numrows=$this->io_sql->execute($ls_sql);

	if($li_numrows===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_colocacion".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
	}
	else
	{
		$lb_valido=true;
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	}

return $lb_valido;
}
	
function uf_delete_colocacion($as_colocacion,$as_codban,$as_cuenta_banco)
{
	$lb_valido = false;
	$ls_codemp = $this->dat["codemp"];
	$ls_sql    = "DELETE FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND ctaban='".$as_cuenta_banco."' AND codban='".$as_codban."' AND numcol='".$as_colocacion."' " ;
	$this->io_sql->begin_transaction();
 	$rs_data   = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_delete_colocacion  ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
	     $lb_valido=true;
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
 	   }
	return $lb_valido;
}	
//----------------------------------------------------------------------------------------------------------------------------------
 function uf_scb_select_cuentas_ingresos($as_codemp,&$ai_cuantos)
 {   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scb_select_cuentas_ingresos
	 //         Access :	private
	 //     Argumentos :    $as_codemp  // codigo dela empresa
	 //                     $ai_cuantos  // cuantos registros existen
     //	       Returns :	Retorna true o false si se realizo la consulta 
	 //	   Description :	Retorna cuantos registros en spi existen (referencia)
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/02/2007          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=false;
	 $ls_sql=" SELECT count(spi_cuenta) as cuantos ".
             " FROM   spi_cuentas ".
             " WHERE  codemp='".$as_codemp."'";
	 $rs_data= $this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {
	     $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_scb_select_cuentas_ingresos  ".$this->fun->uf_convertirmsg($this->io_sql->message);
	 }
	 else
	 {
        if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ai_cuantos=$row["cuantos"];
	      $lb_valido=true;
		}
	 }
	 return  $lb_valido;
 }// uf_scb_select_cuentas_ingresos
//----------------------------------------------------------------------------------------------------------------------------------
}// fin de la clase
?>
