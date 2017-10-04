<?php
class tepuy_spg_c_mod_presupuestarias
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spg;
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
function tepuy_spg_c_mod_presupuestarias()
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");	
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_tepuy_int.php");	
	require_once("../shared/class_folder/class_tepuy_int_scg.php");
	require_once("../shared/class_folder/class_tepuy_int_spg.php");
	require_once("../shared/class_folder/class_tepuy_int_spi.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
    require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");

	$this->io_function=new class_funciones();	
	$this->sig_int=new class_tepuy_int();
    $this->io_fecha=new class_fecha();
	$this->io_include=new tepuy_include();	
	$this->io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($this->io_connect);
	$this->io_msg = new class_mensajes();
	$this->io_int_spg=new class_tepuy_int_spg();	
	$this->io_int_scg=new class_tepuy_int_scg();	
	$this->is_msg_error="";
	$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
	$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
	$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
	$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
}
/**********************************************************************************************************************************/
function uf_generar_num_cmp($as_codemp,$as_procede)
{
	 $ls_sql="SELECT comprobante FROM tepuy_cmp_md WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' ORDER BY comprobante DESC";	
	  $rs_funciondb=$this->io_sql->select($ls_sql);
	  if ($row=$this->io_sql->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["comprobante"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	return $ls_codigo;
}
/**********************************************************************************************************************************/
	function uf_tepuy_insert_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                                      $as_tipo,$as_cod_prov,$as_ced_ben,$as_codfuefin,$as_coduniadm)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
    //	Function:  uf_tepuy_insert_comprobante
    //	  Access:  public
	// Arguments:  as_codemp->codigo empresa; as_procede-> procedencia; as_comprobante-> comprobante;
	//             as_fecha-< fecha ai_tipo_comp-< tipo comprobante (1,2); as_descripcion->descripcion;
	//             as_tipo->tipo fuente as_ced_ben-< beneficiario;as_cod_prov-> proveedor
	//	Returns:	 lb_valido -> variable boolean
	//	Description: Método que inserta el registro comprobante (información cabezera )en la tabla tepuy_Cmp. Usado en el mòdulo de comprobante contable
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fec=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql = " INSERT INTO tepuy_cmp_md (codemp,procede,comprobante,fecha,descripcion,tipo_comp,tipo_destino,".
		          "                            cod_pro,ced_bene,total,estapro,codfuefin,coduac)".
				  " VALUES('".$as_codemp."', '".$as_procede."', '".$as_comprobante."','".$ls_fec."', ".
				  "        '".$as_descripcion."',".$ai_tipo_comp.",'".$as_tipo."','".$as_cod_prov."', ".
				  "        '".$as_ced_ben."', ". intval(0). ",0,'".$as_codfuefin."','".$as_coduniadm."')";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=false;
			$this->is_msg_error = "Error en método uf_tepuy_insert_comprobante ";
		}
		else
		{
		   $this->is_log_transacciones="Inserto comprobante Nº".$as_comprobante." de procedencia ".$as_procede." con fecha ".$as_fecha; 
		}
		return $lb_valido;
	} // end function uf_tepuy_insertcomporbante()
/**********************************************************************************************************************************/
	function uf_tepuy_update_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$as_cod_prov,$as_ced_ben,$li_estapro,$as_codfuefin,$as_coduniadm)
    {		
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function:  uf_tepuy_delete_comprobante()	
		//	   Access:  public
		//	Arguments:  instancias de la clase propia
		//	  Returns:	booleano lb_valido
		//Description:  Método que elimina el registro comprobante (información cabezera ) en la tabla tepuy_Cmp
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		
		$ls_sql = " UPDATE tepuy_cmp_md SET descripcion='".$as_descripcion."' ,estapro=".$li_estapro.", codfuefin='".$as_codfuefin."',coduac='".$as_coduniadm."'  
		WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' AND comprobante='".$as_comprobante."' AND fecha='".$ls_fecha."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "Error en método uf_scg_update_compronbante ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;
	} //fin de uf_scg_update_conprobante
/**********************************************************************************************************************************/
    function uf_tepuy_delete_comprobante()	
    {
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_tepuy_delete_comprobante()	
	//	   Access:  public
	//	Arguments:  instancias de la clase propia
	//	  Returns:	booleano lb_valido
	//Description:  Método que elimina el registro comprobante (información cabezera ) en la tabla tepuy_Cmp
	/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="DELETE 
				 FROM tepuy_cmp_md 
				 WHERE codemp = '".$this->is_codemp."' AND  procede='".$this->is_procedencia."' 
				 AND comprobante='".$this->is_comprobante."' AND fecha='".$ld_fecha."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		
		if($li_numrows===false)
		{
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
		}
		return $lb_valido;
	} // end function uf_tepuy_delete_comprobante
/**********************************************************************************************************************************/
	function uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_select_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_existe=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	   $ls_sql =   " SELECT comprobante ".
	               " FROM tepuy_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
/**********************************************************************************************************************************/
	function uf_tepuy_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                               $as_tipo,$as_cod_pro,$as_ced_bene,$adec_monto,$li_estapro,$as_codfuefin,$as_coduniadm)
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:   uf_tepuy_comprobante
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Procesa un comprobante 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
   	    $this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;

		if ($as_tipo=="B")
		{
		   $this->is_ced_ben  = $as_ced_bene;
   	       $this->is_cod_prov = "----------"; 
		}
		if ($as_tipo=="P")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = $as_cod_pro;
		}
		if ($as_tipo=="-")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = "----------";
		}
        if ($this->uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha))
		{	
		   $this->ib_new_comprobante=false;
           $lb_valido=$this->uf_tepuy_update_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,$li_estapro,$as_codfuefin,$as_coduniadm);
		}
		else
		{
		   $this->ib_new_comprobante=true;		
		   $lb_valido=$this->uf_tepuy_insert_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_codfuefin,$as_coduniadm);
		}
		return $lb_valido;
	} // end function uf_procesar_comprobante_en_linea()
/**********************************************************************************************************************************/
function uf_guardar_automatico($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,$as_prov,$as_bene,$as_tipo,$ai_tipo_comp,$li_estapro,$as_codfuefin,$as_coduniadm)
{
	$lb_valido=false;
	$dat=$_SESSION["la_empresa"];
	$_SESSION["fechacomprobante"]=$ad_fecha;
	if($this->uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,&$as_prov,&$as_bene,$as_tipo))
	{	
	   $lb_valido=$this->uf_tepuy_comprobante($dat["codemp"],$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,$as_desccomp,$as_tipo,$as_prov,$as_bene,0,$li_estapro,$as_codfuefin,$as_coduniadm);
	   if (!$lb_valido)
	   {
	      $this->io_msg->message("Error al procesar el comprobante Presupuestario".$this->is_msg_error);
	   }  
	   else  
	   {   
	       $this->io_msg->message("El Movimiento fue registrado.");
	   }
	   
	   $ib_valido = $lb_valido;
	   
	   if($lb_valido)
	   {
		  $ib_new = $this->ib_new_comprobante;
	   }	
	   else  
	   {  
	      $lb_valido=true;  
	   } 	
	}
	else
	{ 
	   $this->io_msg->message("Error en valida datos comprobante");
    }
	return $lb_valido;
}
/**********************************************************************************************************************************/
function uf_cargar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{

	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	$ls_sql=" SELECT  DT.codestpro1 as codest1,DT.codestpro2 as codest2,DT.codestpro3 as codest3, ".
   	        "                 DT.codestpro4 as codest4,DT.codestpro5 as codest5,DT.spg_cuenta as spg_cuenta, ".
	        "                 max(C.denominacion) as dencuenta, max(DT.procede_doc) as procede_doc, max(P.desproc) as desproc, ".
	        "				  max(DT.documento) as documento, max(DT.operacion) as operacion, max(DT.descripcion) as descripcion, ".
	        "                 max(DT.monto) as monto, max(DT.orden) as orden, max(OP.denominacion) as denominacion  ".
            " FROM spg_dtmp_cmp DT,spg_cuentas C, tepuy_procedencias P,spg_operaciones OP ".
            " WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spg_cuenta=C.spg_cuenta AND ".
			"       OP.operacion = DT.operacion AND (DT.codestpro1=C.codestpro1 AND DT.codestpro2=C.codestpro2 AND ".
			"       DT.codestpro3=C.codestpro3) AND DT.codemp='0001' AND DT.procede='".$as_procede."' AND ".
			"       DT.comprobante='".$as_comprobante."' AND DT.fecha='".$ld_fecha."' ".
            " GROUP BY codest1, codest2 , codest3 , codest4 , codest5 , DT.spg_cuenta, OP.operacion  ".
            " ORDER BY codest1, codest2 , codest3 , codest4 , codest5 , DT.spg_cuenta , dencuenta, procede_doc, ".
			"          desproc, documento, operacion, descripcion, monto, orden, denominacion ";
	//	print 	$ls_sql;
 	/*$ls_sql=" SELECT DISTINCT DT.codestpro1 as codest1,DT.codestpro2 as codest2,DT.codestpro3 as codest3, ".
	        "                 DT.codestpro4 as codest4,DT.codestpro5 as codest5,DT.spg_cuenta as spg_cuenta, ".
			"                 C.denominacion as denominacion,DT.procede_doc as procede_doc,P.desproc as desproc, ".
			"                 DT.documento as documento,DT.operacion as operacion,DT.descripcion as descripcion, ".
			"                 DT.monto as monto,DT.orden as orden, OP.denominacion as denominacion ".
  		    " FROM spg_dtmp_cmp DT,spg_cuentas C, tepuy_procedencias P,spg_operaciones OP ".
		    " WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spg_cuenta=C.spg_cuenta AND  ". 
			"       OP.operacion = DT.operacion AND (DT.codestpro1=C.codestpro1 AND DT.codestpro2=C.codestpro2 AND  ".
            "       DT.codestpro3=C.codestpro3) AND DT.codemp='".$as_codemp."' AND DT.procede='".$as_procede."' AND ".
			"       DT.comprobante='".$as_comprobante."' AND  DT.fecha='".$ld_fecha."' ".
			" ORDER BY DT.orden "; */
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	if($rs_dt_cmp===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_dt_cmp;
}
/**********************************************************************************************************************************/
function uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{

	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	$rs_dt_scg=$this->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, $ld_fecha,&$lds_detalle_cmp);
	if($rs_dt_scg===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
	}
	return $rs_dt_scg;
}
/**********************************************************************************************************************************/
 function uf_scg_cargar_detalle_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$lds_detalle_cmp)
 {	 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function: uf_scg_cargar_detalle_comprobante
	// 	   Access:  public
	//	  Returns:  estructura de datos
	//Description:  inserta la información del saldo de la cuenta correspondiente.
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql =  " SELECT DISTINCT DT.sc_cuenta as sc_cuenta,C.denominacion as denominacion,DT.procede_doc as procede_doc,P.desproc as despro,".
               		 "                 DT.documento as documento,DT.fecha as fecha,DT.debhab as debhab,DT.descripcion as descripcion,DT.monto as monto,DT.orden as orden " .
					 " FROM scg_dtmp_cmp DT,scg_cuentas C, tepuy_procedencias P ".
					 " WHERE DT.codemp='".$as_codemp."' AND DT.procede='".$as_procede."' AND DT.comprobante='".$as_comprobante."' AND ".
					 "       DT.fecha= '".$as_fecha."' AND DT.sc_cuenta=C.sc_cuenta AND DT.procede=P.procede ".
					 " ORDER BY DT.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="Error en cargar detalle comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $rs_data;
	 }  // end function uf_scg_cargar_detalle_comprobante()
/**********************************************************************************************************************************/
function uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_procedencia,$as_desccomp,$as_cod_prov,$as_ced_bene,$as_tipo)
{
	$ls_desproc ="";
	if(!$this->io_int_spg->uf_valida_procedencia($as_procedencia,&$ls_desproc ) )
	{
	   $this->io_msg->message("Procedencia invalida.",$ls_desproc);
	   return false	;
	} 

	if(trim($as_comprobante)=="")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}

	if(trim($as_comprobante)=="000000000000000")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}
	
	
	if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	
	if((trim($as_cod_prov)!="----------" )&&($as_tipo=="B"))
	{
		$as_cod_prov = "----------";
	}
		
	if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario1.");
		return false;
	}
	if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario.2");
		return false;	
	}
	
	if((trim($as_ced_bene)!="----------" )&&($as_tipo=="P"))
	{
		$as_ced_bene="----------";
	}
	if($as_tipo=="-")
	{
		$as_ced_bene="----------";
		$as_cod_prov="----------";
	}

  return true;
}
/**********************************************************************************************************************************/
function uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,
                                $ls_descripcion,$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp)
{
	$lb_valido=false;
	$estpro[0]=$ls_est1;
	$estpro[1]=$ls_est2;
	$estpro[2]=$ls_est3;
	$estpro[3]=$ls_est4;
	$estpro[4]=$ls_est5;
				
	$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre) ;
	
	if($ls_mensaje!="")
	{
		if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
		{ 
		   $this->io_msg->message($this->is_msg_error);
		   return false;
		}
		$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
		$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
		$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
		$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
		$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
		$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
		$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
		$lb_valido=$this->uf_spg_comprobante_actualizar($ldec_monto_ant, $ldec_monto_act, $ls_tipocomp);
		if($lb_valido)
		{
	        $ls_sc_cuenta="";	
			if ($arr_cmp["tipo"]=="B")  
				{ $ls_fuente = $arr_cmp["beneficiario"]; }	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			$ls_comprobante = $this->io_int_spg->uf_fill_comprobante( $this->is_comprobante );
		    $ls_operacion = $this->io_int_spg->uf_operacion_mensaje_codigo($ls_mensaje);
		    if(empty($ls_operacion)) { return false; }
		    if(!$this->io_int_spg->uf_valida_procedencia( $this->io_int_spg->is_procedencia , $ls_denproc)) { return false; }
		    if(!$this->io_int_spg->io_fecha->uf_valida_fecha_mes($this->io_int_spg->is_codemp,$this->io_int_spg->id_fecha))
		    {
		 	   $this->is_msg_error = "Fecha Invalida."	;
	 		   $this->io_msg->message($this->is_msg_error);			   		  		  
 			   return false;
		    }
		    if($this->uf_spg_select_movimiento( $estpro, $ls_cuenta, $ls_procede_doc, $ls_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
		    {
		 	  $this->is_msg_error = "El movimiento contable ya existe.";
	 		  $this->io_msg->message($this->is_msg_error);			   		  		  		  
 			  return false; 	 
		    }
			$lb_valido = $this->uf_spg_comprobante_actualizar(0,$ldec_monto_ant,"C");
			if($lb_valido)
			{
				$lb_valido = $this->uf_insert_movimiento_spg($estpro,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_act);
				if($lb_valido)
				{
					$ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
					$li_pos_i=strpos($ls_mensaje,"C"); 
					if (!($li_pos_i===false))
					{			      
					  if ($this->ib_AutoConta)
					  {
						  $lb_valido=$this->uf_spg_integracion_scg($ls_codemp,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto);
					  }
					} 
				
					if(!$lb_valido)
					{
						$this->io_msg->message("No se registraron los detalles presupuestario".$this->io_int_spg->is_msg_error);
					}
				}
			}
		}
		else
		{
		  	$lb_valido=false;
		}
   }
   $ldec_monto = 0;
 return $lb_valido;
}
/**********************************************************************************************************************************/
   function uf_insert_movimiento_spg($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$ad_monto_actual)
   {
   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //	 Function:  uf_spg_insert_movimiento
   //	Arguments:  estprog->estructura programatica del gasto; as_cuenta->cuenta gasto ; as_procede_doc procedenca del documento
   //               as_documento  n° del documento; as_operacion  operacion de gasto; as_descripcion	 descripcion del movimiento  //               adec_monto   monto del mivimiento 
   //	  Returns:  lb_valido -> variable boolean
   // Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
	   $lb_valido = true;
	   $ls_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $li_orden  = $this->uf_spg_obtener_orden_movimiento();
	   $ls_sql = "INSERT INTO spg_dtmp_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden)".
			     " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','".$ls_fecha."','".$estprog[0]."','".$estprog[1]."','".$estprog[2]."','".$estprog[3].
			     "','".$estprog[4]."','".$as_cuenta."','".$as_procede_doc."','".$as_documento."','".$as_operacion."','".$as_descripcion."','".$ad_monto_actual."',".$li_orden.")"; 
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
		{
		  $lb_valido=false;
		  $this->is_msg_error = "Error de SQL método->uf_spg_insert_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  print $this->io_sql->message;
		}
	   return $lb_valido;
	}// end function uf_spg_insert_movimiento_gasto
/**********************************************************************************************************************************/
	function uf_spg_obtener_orden_movimiento()
	{   
	//////////////////////////////////////////////////////////////////////////////
	//	   Function:  uf_spg_obtener_orden_movimiento
	//	    Returns:  li_orden -> numero del orden
	//	Description:  Retorna el número de orden del movimiento de gasto spg
	/////////////////////////////////////////////////////////////////////////////	
		$li_orden=0;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql= " SELECT count(*) as orden  FROM spg_dtmp_cmp".
				 " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."'".
				 " AND fecha='".$ld_fecha."' " ;
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_obtener_orden_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   print $this->io_sql->message;
		   return false;
	    }
	    else {  if($row=$this->io_sql->fetch_row($rs_data))  { $li_orden=$row["orden"]; } } 
		
	   $this->io_sql->free_result($rs_data);		
	   return $li_orden;
    } //end function uf_spg_obtener_orden_movimiento()
/**********************************************************************************************************************************/
	function uf_spg_integracion_scg($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_descripcion, $adec_monto_actual)
	{
		$lb_valido=true;$ls_debhab=""; $ls_status=""; $ls_denominacion=""; $ls_mensaje_error="";$ldec_monto=0;$li_orden=0;
	
		if($adec_monto_actual > 0) 	{ $ls_debhab = "D"; }
		else{  $ls_debhab = "H"; }
		if (!$this->io_int_spg->io_int_scg->uf_scg_select_cuenta( $as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
		   return false;
		} 
		if($ls_status!="C")
		{ 
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
		   return false;
		} 
		
		$this->io_int_spg->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_spg->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_spg->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_spg->io_int_scg->is_comprobante=$this->is_comprobante;
		
		if (!$this->uf_scg_select_movimiento($as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
		{
		   	//$lb_valido = $this->io_int_scg->uf_scg_registro_movimiento_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
			$lb_valido = $this->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
		}																	 
	return $lb_valido;
	}//uf_spg_integracion_scg
/**********************************************************************************************************************************/
	function uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
                                     	      $as_tipo_destino,$as_cod_prov, $as_ced_bene, $as_cuenta,
										      $as_procede_doc, $as_documento,$as_debhab,$as_descripcion,
										      $adec_monto_anterior, $adec_monto_actual )
    {											  
	///////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_procesar_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento contable (Método Principal MAIN )
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";	
		$li_orden=0;
		$this->is_codemp      = $as_codemp;
		$this->is_procedencia = $as_procede;
		$this->is_comprobante = $as_comprobante;
		$this->id_fecha       = $as_fecha;
		$this->is_cod_prov    = $as_cod_prov;
		$this->is_ced_ben     = $as_ced_bene;
		$this->is_tipo        = $as_tipo_destino;

		if (!($this->io_int_spg->io_int_scg->uf_valida_procedencia( $as_procede , $ls_desproc))) { return false; }	 
		
		if ($this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto_actual,&$li_orden)) 
		{
		   $this->is_msg_error="El movimiento contable ya existe.";
		   return false; 	
		}
		$lb_valido = $this->uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto_actual);
		return $lb_valido;
	} //end function uf_scg_registro_movimiento()
/**********************************************************************************************************************************/
	function uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto,&$ai_orden)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_select_movimiento
	// 	   Access:  public
	//  Arguments:  as_sc_cuenta-> cuenta contable;as_procede_doc->procedencia documento ; as_documento-> documento
	//              as_debhab->operacion debe-haber; adec_monto->monto Operacion;ai_orden->orden movimiento
	//	  Returns:  boolean
	//Description:  Este método verifica si existe o no el movimiento contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe = false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql =   " SELECT monto,orden".
		            " FROM scg_dtmp_cmp".
		            " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."' AND ".
					"       fecha='".$ld_fecha."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND sc_cuenta='".$as_cuenta."' AND debhab='".$as_debhab."'";
		$rs_mov=$this->io_sql->select($ls_sql);
		
		if($rs_mov===false)	{  $this->is_msg_error = "Error en el método uf_scg_select_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);	}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				$lb_existe=true;
				$adec_monto = $row["monto"];
				$ai_orden   = $row["orden"];
			}
			else  {  $lb_existe=false; }
		}
	   $this->io_sql->free_result($rs_mov);		
	   return $lb_existe;
	} // end function uf_scg_select_movimiento
/**********************************************************************************************************************************/
	function uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion )
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_delete_movimiento
	// 	   Access:  public
	//	  Returns:  boolean
	//Description:  Este método elimina el movimineto contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($as_fecha);
		
		$ls_sql =   " DELETE FROM scg_dtmp_cmp ".
					" WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' AND comprobante='".$as_comprobante ."' AND fecha= '".$ls_fecha."' AND ".
					"       sc_cuenta= '".$as_cuenta."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND debhab='".$as_operacion."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "Error en método uf_scg_delete_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
	    return $lb_valido;
	} // end function uf_scg_delete_movimiento()

		////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento final contable enla tabla movimiento  (DEPENDE DEL PROCESAR)
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_insert_movimiento( $as_cuenta, $as_procede_doc, $as_documento, $as_debhab, $as_descripcion, $adec_monto )
	{
		$lb_valido = true;
		$li_orden = $this->uf_scg_obtener_orden_movimiento();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = "INSERT INTO scg_dtmp_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden) " . 
				  " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','" .$ls_fecha."','".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".$adec_monto.",".$li_orden.")" ;
		$li_result=$this->io_sql->execute($ls_sql);

		if($li_result===false)
		{
		   
		   if($this->io_sql->errno==1452)
		   {
			   $this->is_msg_error = "Error en método uf_scg_insert_movimiento, Fallo alguna clave foranea";
		   }
		   else
		   {
		   		$this->is_msg_error = "Error en método uf_scg_insert_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   }
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_insert_movimiento()
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   Function:  uf_spg_select_movimiento
    //     Access: public	
	//	Arguments:  as_est1...as_est5 -> estructura programatica  ; as_cuenta->cuenta presupuestaria
	//              as_procede_doc- > procedenca del documento ; as_documento -> n° del documento
	//	  Returns:	lb_valido -> variable boolean
	//Description:  Este método verifica si el movimiento ya existe o no en la tabla de movimeintos presupuestario de gasto,
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$adec_monto,&$ai_orden)
	{
  	    $lb_existe=false;
		$ls_cuenta  = "";$lb_existe=false;$ldec_monto=0;$li_orden=0;
		$ls_codemp  =  $this->is_codemp ;
		$ls_procedencia = $as_procede_doc;
		$ls_comprobante = $as_documento;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " SELECT spg_cuenta,monto,orden".
			      " FROM spg_dtmp_cmp".		
			      " WHERE codemp='".$ls_codemp."' AND codestpro1 ='".$estprog[0]."' AND codestpro2 ='".$estprog[1]."' AND". 
			      "       codestpro3 ='".$estprog[2]."' AND codestpro4 = '".$estprog[3]."' AND codestpro5 ='".$estprog[4]."'  AND procede='".$this->is_procedencia."' AND ".
			      "       comprobante='".$this->is_comprobante."' AND fecha='".$ls_fecha."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND ".
			      "       spg_cuenta ='".$as_cuenta."'  AND  operacion='".$as_operacion."' "; 


		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
   	 	    $this->is_msg_error="Error de SQL método->uf_spg_select_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$adec_monto=$ldec_monto;
				$li_orden=$row["orden"];
				$ai_orden=$li_orden;
				$lb_existe=true;
			}				
		}
		$this->io_sql->free_result($rs_data);				
		return $lb_existe;
	} // end function uf_select_movimientos
	
	function uf_spg_comprobante_actualizar($ai_montoanterior, $ai_montoactual, $ls_tipocomp)
    {
      $lb_valido=false; 
	  $li_tipocomp=0;
	  if($ls_tipocomp=="C") { $li_tipocomp=1; }
      if($ls_tipocomp=="P") { $li_tipocomp=2; }	
	  if ($this->uf_spg_comprobante_select())
	  {
		 $lb_valido = $this->uf_spg_comprobante_update($ai_montoanterior, $ai_montoactual);
   	  }
	  else 
	  { 
	     $lb_valido = $this->uf_spg_comprobante_insert($ai_montoactual, $li_tipocomp);  
	  }
     return $lb_valido;
    } // end function uf_spg_comprobante_actualizar()
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_select
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método verifica si existe el comprobante tepuy_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_select()
	{
		$lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql= "SELECT * FROM tepuy_cmp_md WHERE procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."' AND fecha='".$ld_fecha."' ";
		$rs_data = $this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_comprobante_select class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
  	       return false;
	    }
	    else {   if($row=$this->io_sql->fetch_row($rs_data))  {  $lb_existe=true;  } } 
		$this->io_sql->free_result($rs_data);		
		return $lb_existe;
	} // end function uf_spg_comprobante_select()
	
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_update
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método actualiza si existe el comprobante tepuy_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_update($li_montoanterior, $li_montoactual)
	{
	   $lb_valido = true;
	   $li_total = ( - $li_montoanterior + $li_montoactual);
	   $ld_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = " UPDATE tepuy_cmp_md SET total = total + '".$li_total."'  ".
	             " WHERE  procede='".$this->is_procedencia."' AND comprobante= '".$this->is_comprobante."' AND fecha='".$ld_fecha."' ";
	   $li_exec=$this->io_sql->execute($ls_sql);
	   if($li_exec===false)
	   {
 	      $this->is_msg_error="Error de SQL método->uf_spg_comprobante_update class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  $lb_valido=false;
	   }	   
	   return $lb_valido;
	} // function uf_spg_comprobante_update()
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_insert
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método inserta en el compronate de gasto
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_spg_comprobante_insert($ai_monto, $ai_tipocomp)
	{
		$lb_valido=true;
		$ls_codemp = $this->is_codemp;  $ls_procede = $this->is_procedencia; $ls_comprobante = $this->is_comprobante;
		$ls_descripcion=$this->is_descripcion; 	$ls_tipo=$this->is_tipo;
		$ls_codpro=$this->is_cod_prov;
		$ls_cedbene=$this->is_ced_ben;		
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " INSERT INTO tepuy_cmp_md(codemp,procede,comprobante,fecha,descripcion,total,tipo_destino,cod_pro,ced_bene,tipo_comp)".
				  " VALUES ('".$ls_codemp."', '".$ls_procede."', '".$ls_comprobante."', '".$ld_fecha."', '".$ls_descripcion."', '".$ai_monto."', '".$ls_tipo."', '".$ls_codpro."', '".$ls_cedbene."', '".$ai_tipocomp."' )";
		$li_exec=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
 	       $this->is_msg_error="Error de SQL método->uf_spg_comprobante_insert class->class_tepuy_int_spg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
	return $lb_valido;
   }  // end function uf_spg_comp_insert

function uf_spg_valida_datos_movimiento($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
{

if (trim($as_cuenta)=="")
{
	$this->is_msg_error = "Registre la Cuenta Gasto." ;
	return false;	
}
if(trim($as_descripcion)=="")
{
	$this->is_msg_error = "Registre la Descripción del Movimiento." ;
	return false;
}

if(trim($as_documento) =="") 
{
	$this->is_msg_error = "Registre el Nº de documento." 	;
	return false;	
}

 return true ;
}

function uf_guardar_movimientos_contable($arr_cmp,$as_cuenta,$as_procede_doc,$as_descripcion,$as_documento,
                                         $as_operacioncon,$adec_monto)
{
	$lb_valido=false;

	if(!$this->uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto))
	{ 
		$this->io_msg->message($this->is_msg_error);
	   return false;
	}
	$lb_valido = $this->uf_scg_procesar_movimiento_cmp($arr_cmp["codemp"],$arr_cmp["procedencia"],$arr_cmp["comprobante"],$arr_cmp["fecha"],
                                                       $arr_cmp["proveedor"],$arr_cmp["beneficiario"],$arr_cmp["tipo"],$arr_cmp["tipo_comp"],
                                                       $as_cuenta,$as_procede_doc,$as_documento,$as_operacioncon,$as_descripcion,$adec_monto);
	if(!$lb_valido)
	{
		$this->io_msg->message("Error al registrar movimiento contable".$this->io_int_scg->is_msg_error);
	}
	$ldec_monto = 0;
    return $lb_valido;
 }

	function uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
		
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		
		if($adec_monto == 0)
		{
			$this->is_msg_error = "Registre el Monto." ;	
			return false;
		} 
	
	   return true ;
	}
	
	function uf_scg_procesar_movimiento_cmp($as_codemp,$as_procedencia,$as_comprobante,$ad_fecha,
											$as_proveedor,$as_beneficiario,$as_tipo,$as_tipo_comp,$as_sc_cuenta,
											$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto)
	{
		$this->is_codemp     = $as_codemp;
		$this->is_procedencia= $as_procedencia;
		$this->is_comprobante= $as_comprobante;
		$this->id_fecha		 = $ad_fecha;
		$this->is_cod_prov   = $as_proveedor;
		$this->is_ced_ben    = $as_beneficiario;
		$this->is_tipo       = $as_tipo;		
	
		$this->is_comprobante = $this->io_function->uf_cerosizquierda($as_comprobante,15);
		$as_documento		  =	$this->io_function->uf_cerosizquierda($as_documento,15);
		$lb_valido=true;

		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no existe");
			return false;
		}
		
		//- valido que sea una cuenta de movimiento
		if($ls_status!="C")
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no es de movimiento");
			return false;
		}
		
		//-- verifico la Procede_Doc
		if(!$this->io_int_scg->uf_valida_procedencia($as_procede_doc,&$as_descproc))
		{
			$this->io_msg->message("La procedencia ".$as_procede_doc." no esta registrada");
			return false;
		}
		
		//-- verifico la Fecha
		if(!$this->io_fecha->uf_valida_fecha_mes($as_codemp,$ad_fecha))
		{
			$this->io_msg->message($this->int_fec->is_msg_error);
			return false;
		}

		if($this->uf_scg_select_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion, &$adec_monto_anterior,&$ai_orden))
		{	
			$this->io_msg->message("El Movimiento ya existe ");
			return false;
		}
		//Inicio la transacion
		if($lb_valido)
		{
			$lb_valido= $this->uf_scg_insert_movimiento( $as_sc_cuenta, $as_procede_doc, $as_documento, $as_operacion, $as_descripcion, &$adec_monto );
		}
		return $lb_valido;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_obtener_orden_movimiento
	// 	   Access:  public
	//	  Returns:  integer
	//Description:  Este método genera un numero de orden secuencial de los movimiento 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_obtener_orden_movimiento()
	{
		$li_orden=0;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = " SELECT count(*) as orden " .
					" FROM scg_dtmp_cmp " .
					" WHERE codemp='".$this->is_codemp."' AND procede= '". $this->is_procedencia ."' AND comprobante= '".$this->is_comprobante."' AND fecha='".$this->id_fecha."'";
		$rs_saldos=$this->io_sql->select($ls_sql);
		if($rs_saldos==false)
		{
		  $this->is_msg_error = "Error en el método uf_scg_obtener_orden_movimiento ".$this->io_sql->message;
		  $lb_valido=false;
		}
		else
		{
		  if($row=$this->io_sql->fetch_row($rs_saldos))  {	$li_orden=$row["orden"]; }
		}		 
		return $li_orden;
	} //fin de uf_scg_obtener_orden_movimiento()
	
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_int_spg_delete_movimiento
    //      Access: public
    //   Arguments: $as_codemp -> codigo empresa  ; $as_procedencia -> procedencia documento ; as_comprobante -> comprobante de gasto ; $as_fecha -> fecha comprobante ;
	//              $estprog -> arreglo que contiene la estructura programatica ; $as_cuenta-> cuenta gasto ;
    //	            $as_procede_doc -< procedencia documento ; $as_documento-> documento ; $as_descripcion -> descripcion ; $as_mensaje -> mensaje ; $adec_monto-> monto operacion
    //     Returns: retorna un mensaje interno para operaciones 
    // Description: Método que elimina un movimiento de gasto por medio de la integracion en lote
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_int_spg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_pro,$as_ced_bene,
	                                      $estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,$as_tipo_comp,
										  $adec_monto_anterior,$adec_monto_actual,$as_sc_cuenta)
	{
	   $lb_valido=false;
	   $this->is_codemp      = $as_codemp;
	   $this->is_procedencia = $as_procedencia;
	   $this->is_comprobante = $as_comprobante;
	   $this->id_fecha       = $as_fecha;
	   $this->is_tipo=$as_tipo;
	   $this->is_fuente=$as_fuente;
	   $this->is_cod_prov=$as_cod_pro;
	   $this->is_ced_ben=$as_ced_bene;
       $ls_operacion = $this->io_int_spg->uf_operacion_mensaje_codigo($as_mensaje);
	   if(empty($ls_operacion)) { return false; }
	   if(!$this->uf_spg_select_movimiento( $estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
	   {
          $this->io_msg->message("El movimiento no existe.");			   		  
		  return false; 	
	   }
   
       $lb_valido = $this->uf_spg_delete_movimiento($estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion) ;
	   if ($lb_valido)
	   {
          $lb_valido = $this->uf_spg_comprobante_actualizar($lo_monto_movimiento,0,"C");
	   }
	   return $lb_valido;
    } // end function uf_int_spg_delete_movimiento()
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_spg_delete_movimiento
	//	Arguments: as_est1...as_est5  estructura programatica del gasto as_cuenta  cuenta contable; as_procede_doc  procedenca del documento
	//             as_documento       // n° del documento; as_operacion   operacion del documento de gasto; as_descripcion	 descripcion del movimiento
	//             adec_monto         // monto del mivimiento 
	//	Returns:		lb_valido -> variable boolean
	//	Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_delete_movimiento($estprog, $as_cuenta, $as_procede_doc, $as_documento, $as_operacion)
	{
	   $lb_valido  = true;
       $ldt_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = " DELETE FROM spg_dtmp_cmp ".
	             " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."' AND fecha='".$ldt_fecha."' AND ".
				 "       codestpro1='".$estprog[0]."' AND codestpro2='".$estprog[1]."' AND codestpro3='".$estprog[2]."' AND codestpro4='".$estprog[3]."' AND codestpro5='".$estprog[4]."' AND ".
			     "       spg_cuenta='".$as_cuenta."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND operacion ='".$as_operacion."'";
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
	   {
	      $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
	      $this->io_msg->message($this->is_msg_error);			   		  		  
		  $lb_valido=false;
	   }
	  return $lb_valido;
	}//Fin de uf_spg_delete_movimiento

    
	function uf_delete_all_comprobante($ls_codemp,$ls_comprobante,$ld_fecha,$ls_procedencia)
	{
	   $lb_valido=true;
	   $ld_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
	   //Eliminacion del detalle presupuestario del comprobante
	   $ls_sql="DELETE 
				FROM spg_dtmp_cmp 
				WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
	   {
	      $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
	      $this->io_msg->message($this->is_msg_error);			   		  		  
		  return false;
	   }
	   else
	   {
		   //Eliminacion del detalle Contable del comprobante
		   $ls_sql="DELETE 
					 FROM scg_dtmp_cmp 
					 WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
		   $li_rows=$this->io_sql->execute($ls_sql);
		   if($li_rows===false)
		   {
			  $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
			  $this->io_msg->message($this->is_msg_error);			   		  		  
			  return false;
		   }
		   else
		   {
			   //Eliminacion del comprobante
			   $ls_sql="DELETE 
						 FROM tepuy_cmp_md 
						 WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
			   $li_rows=$this->io_sql->execute($ls_sql);
			   if($li_rows===false)
			   {
				  $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
				  $this->io_msg->message($this->is_msg_error);			   		  		  
				  return false;
			   }
			}   
		}   
	   return $lb_valido;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_verificar_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_verificar_comprobante($as_codemp,$as_procedencia,$as_comprobante)
	{
	   $lb_existe=false;
	   $ls_sql =   " SELECT comprobante ".
	               " FROM   tepuy_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante

function uf_load_fuentes_financiamiento($as_codemp)
{
  $ls_sql  = "SELECT codfuefin, denfuefin FROM tepuy_fuentefinanciamiento WHERE codemp='".$as_codemp."' ORDER BY codfuefin ASC";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
  {
	   $this->is_msg_error="Error.CLASS->tepuy_spg_c_mod_presupuestarias.php.-Método->uf_load_fuentes_financiamiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_data;
}

function uf_load_unidades_administradoras($as_codemp)
{
  $ls_sql   = "SELECT coduac, denuac FROM spg_ministerio_ua WHERE codemp='".$as_codemp."' ORDER BY coduac ASC";
  $rs_datos = $this->io_sql->select($ls_sql);
  if ($rs_datos===false)
  {
	   $this->is_msg_error="Error.CLASS->tepuy_spg_c_mod_presupuestarias.php.-Método->uf_load_unidades_administradoras ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_datos;
}
//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_tepuycmpmd($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_tepuycmpmd()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	
	$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto", $ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("tepuy_cmp_md",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);
	return $lb_valido;
}//uf_update_bsf_tepuycmp
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgdtmpcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codestpro,
                                $as_spg_cuenta,$as_procede_doc,$as_documento,$as_operacion,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_spgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;

	$ls_codestpro1=substr($as_codestpro,0,20);
	$ls_codestpro2=substr($as_codestpro,20,6);
	$ls_codestpro3=substr($as_codestpro,26,3);
	$ls_codestpro4=substr($as_codestpro,29,2);
	$ls_codestpro5=substr($as_codestpro,31,2);

	$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spg_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_operacion);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);
	return $lb_valido;
}//uf_update_bsf_spgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------
function uf_convertir_tepuycmpmd($as_codemp,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_convertir_tepuycmpmd
	//		   Access: private
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que selecciona los campos de moneda de la tabla tepuy_cmp_md e inserta el valor convertido
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT codemp, procede, comprobante, fecha, total ".
			" FROM   tepuy_cmp_md   ".
			" WHERE  codemp='".$as_codemp."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->tepuy_rcm_c_cfg MÉTODO->SELECT->uf_convertir_tepuycmpmd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		$la_seguridad="";
		while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		{
			$ls_codemp = $row["codemp"]; 
			$ls_procede = $row["procede"];
			$ls_comprobante = $row["comprobante"]; 
			$ldt_fecha = $row["fecha"];
			$ld_total = $row["total"];

			$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_total);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_comprobante);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecha);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("tepuy_cmp_md",$this->li_candeccon,$this->li_tipconmon,
			                                                 $this->li_redconmon,$aa_seguridad);
		}
	}		
	return $lb_valido;
}// end function uf_convertir_tepuycmpmd
//-----------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_scgdtcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$adt_fecha,
                                $as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_scgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$adt_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_debhab);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,
													 $this->li_redconmon,$aa_seguridad);
	return $lb_valido;
}//uf_update_bsf_scgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------
}
?>