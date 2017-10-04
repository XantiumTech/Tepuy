<?php
class tepuy_scb_c_movbanco
{
	var $is_msg_error;
	var $io_sql;
	var $siginc;
	var $int_scg;
	var $int_spg;
	var $msg;
	var $fun;
	var $io_fecha;
	var $dat;
	var $io_seguridad;
	function tepuy_scb_c_movbanco($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$this->io_seguridad= new tepuy_c_seguridad();		
		$this->siginc=new tepuy_include();
		$this->io_fecha=new class_fecha();
		$con=$this->siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->is_msg_error="";
		$this->msg=new class_mensajes();
		$this->fun=new class_funciones();
		$this->dat=$_SESSION["la_empresa"];
		$this->la_security=$aa_security;
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}
	
function uf_generar_num_cmp($as_codemp,$as_procede)
{
	 $ls_sql="SELECT numdoc 
			  FROM scb_movbco 
			  WHERE codemp='".$as_codemp."' AND codope='".$as_procede."' 
			  ORDER BY comprobante DESC";		
	  $rs_funciondb=$this->io_sql->select($ls_sql);
	  if ($row=$this->io_sql->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["numdoc"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,15);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,15);
	  }
	return $ls_codigo;
}
	
function uf_generar_voucher($as_codemp)
{
	require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
	$io_keygen= new tepuy_c_generar_consecutivo();
	$codigo= $io_keygen->uf_generar_numero_nuevo("SCB","scb_movbco","chevau","SCBBCH",25,"","","");
	unset($io_keygen);
	$ls_codigo=$this->fun->uf_cerosizquierda($codigo,25);
	return $ls_codigo;
}
	
function uf_select_movimiento($ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_estmov)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica que el movimiento bancario no exista
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_sql="SELECT numdoc,codope,estmov 
			 FROM   scb_movbco
			 WHERE  codemp='".$ls_codemp."' AND codban ='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			 AND    numdoc='".$ls_numdoc."' AND codope ='".$ls_codope."' ";
	//print $ls_sql;
	$rs_mov=$this->io_sql->select($ls_sql);
	if(($rs_mov===false))
	{
		$this->is_msg_error="Error en select movimiento,".$this->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_mov))
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
}

function uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_estdoc,$ls_tipproben,$ls_codfuefin)
{								
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que procesa los datos de la cabecera del movimiento bancario
	//	validando que no exista y que el periodo este abierto.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	if($ldec_monret==""){$ldec_monret=0.00;}
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	//print "operacion: ".$ls_codope;
	if(($ls_codope=="CH")&&($ls_chevau!=""))
	{
		$ls_chevau= str_pad($ls_chevau,25,"0",STR_PAD_LEFT);/////agregado por Juniors el 20/12/2007
	}

	if($this->io_fecha->uf_valida_fecha_periodo($ldt_fecha,$ls_codemp))
	{
	   if(!$this->uf_select_movimiento($ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_estmov))
	   {	
		   $this->io_sql->begin_transaction();
		   $lb_valido = $this->uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,$ls_tipproben);
		   if($lb_valido)
		   {
				$lb_valido = $this->uf_insert_fuentefinancimiento($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_codfuefin);
		   }
		   $ib_valido = $lb_valido;
		   if($lb_valido)
		   {
				$ib_new = false;
		   }	
	   }
	   elseif($ls_estdoc=='C')
	   {
			$lb_valido=true;
			$lb_valido=$this->uf_update_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben);
	   }
	   else
	   {
			$lb_valido=false;   
			$this->is_msg_error="El numero de documento ya existe";
	   }
	}
	else
	{
		$this->is_msg_error=$this->io_fecha->is_msg_error;
		$lb_valido=false;
	}	
	return $lb_valido;
}

function uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->fun->uf_convertirdatetobd($ldt_fecha);
	if($ldec_monret==""){$ldec_monret=0.00;}
	$ls_sql="INSERT INTO scb_movbco(       codemp   ,     codusu   ,       codban   ,       ctaban   ,       numdoc   ,
									       codope   ,     fecmov   ,       conmov   ,       codconmov   ,      cod_pro   , 
										   ced_bene ,     nomproben,        monto   ,        monobjret  ,        monret  ,
										   chevau   ,     estmov   ,      estmovint ,      estcobing  ,esttra,estbpd, 
										   estcon   ,     feccon   ,   estreglib       ,tipo_destino,fecha,procede, codfuefin)
			 VALUES                ('".$ls_codemp."','".$ls_codusu."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."',
			 						'".$ls_codope."','".$ldt_fecha."','".$ls_conmov."','".$ls_codconmov."','".$ls_codpro."',
									'".$ls_cedbene."','".$ls_nomproben."',".$ldec_monto.",".$ldec_monobjret.",".$ldec_monret.",
									'".$ls_chevau."','".$ls_estmov."',".$li_estmovint.",".$li_cobrapaga.", 0    ,'".$ls_estbpd."',
									    0  ,'1900-01-01','".$ls_estreglib."','".$ls_tipproben."','1900-01-01','SCBBCH','--')";
	//print $ls_sql;
	$li_result=$this->io_sql->execute($ls_sql);
	if(($li_result===false))
	{
		$this->is_msg_error="Fallo insercion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		echo $this->io_sql->message;
		return false;
	}
	else
	{
		$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monobjret);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monret);
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
		
		$this->is_msg_error="El movimiento Bancario fue registrado";
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban." por un monto de ".$ldec_monto;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return $lb_valido;		
	}
}

function uf_update_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->fun->uf_convertirdatetobd($ldt_fecha);
	
	$ls_sql="UPDATE scb_movbco SET conmov='".$ls_conmov."',codconmov='".$ls_codconmov."',cod_pro='".$ls_codpro."',ced_bene='".$ls_cedbene."',nomproben='".$ls_nomproben."',monto='".$ldec_monto."',monobjret='".$ldec_monobjret."',fecmov='".$ldt_fecha."',monret='".$ldec_monret."',tipo_destino='$ls_tipproben'
			 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."'";

	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		$this->is_msg_error=" Fallo Actualizacion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	else
	{
		$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monobjret);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monret);
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
		$this->is_msg_error="El movimiento Bancario fue Actualizado";
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="UPDATE";
		$ls_descripcion="Actualizo el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban." por un monto de ".$ldec_monto;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		return $lb_valido;
	}
	
}

function uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_actual,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica si existe el movimiento contable
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
    $ls_codban     = $arr_movbco["codban"];
 	$ls_ctaban     = $arr_movbco["ctaban"];
	$ls_numdoc     = $arr_movbco["mov_document"];
	$ls_codope     = $arr_movbco["codope"];
	$ls_estmov	   = $arr_movbco["estmov"];
	$ldec_monobjret= $arr_movbco["objret"];	 
	
	$ls_sql="SELECT codemp,monto 
			   FROM scb_movbco_scg
			  WHERE codemp='".$ls_codemp."' 
			    AND codban='".$ls_codban."' 
				AND ctaban='".$ls_ctaban."' 
				AND numdoc='".$ls_numdoc."' 
			    AND codope='".$ls_codope."' 
				AND estmov='".$ls_estmov."' 
				AND scg_cuenta='".$ls_cuenta."' 
			    AND debhab='".$ls_operacioncon."' 
				AND codded='".$ls_codded."' 
				AND documento='".$ls_documento."'";
	$rs_dt_scg=$this->io_sql->select($ls_sql);
	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle contable ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}

function  uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$lb_mov_mandatory,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle contable del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////	
	$dat       = $_SESSION["la_empresa"];
	$ls_codemp = $dat["codemp"];
    $ls_codban = $arr_movbco["codban"];
 	$ls_ctaban = $arr_movbco["ctaban"];
	$ls_numdoc = $arr_movbco["mov_document"];
	$ls_codope = $arr_movbco["codope"];
	$ls_estmov = $arr_movbco["estmov"];
		
	$lb_valido = $this->uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,&$ldec_actual,$ls_codded);
	if(!$lb_valido)
	{
			$ls_sql="INSERT INTO scb_movbco_scg(codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,documento,codded,desmov,procede_doc,monto,monobjret)
					 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_estmov."','".trim($ls_cuenta)."','".$ls_operacioncon."','".$ls_documento."','".$ls_codded."','".$ls_descripcion."','".$ls_procede."',".$ldec_monto.",".$ldec_objret.")";
			$li_result=$this->io_sql->execute($ls_sql);	
			
			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
				print $this->io_sql->message;
				$lb_valido=false;			
			}
			else
			{
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_objret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacioncon);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
				
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion="Inserto el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		        $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
	}
	else
	{   
		$ldec_monto=$ldec_monto+$ldec_actual;
		$ls_sql="UPDATE scb_movbco_scg ".
		        "   SET monto=".$ldec_monto." ".
				" WHERE codemp='".$ls_codemp."'     AND codban='".$ls_codban."'      ".
				"   AND ctaban='".$ls_ctaban."'     AND numdoc='".$ls_numdoc."'      ".
				"   AND codope='".$ls_codope."'     AND estmov='".$ls_estmov."'      ".
				"   AND scg_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."'".
				"   AND codded='".$ls_codded."'     AND documento='".$ls_documento."'";
		if(($lb_valido)&&(!$lb_mov_mandatory))
		{
			$li_result=$this->io_sql->execute($ls_sql);	

			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
				$lb_valido=false;			
			}
			else
			{
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacioncon);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
				
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		        $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////				
			}
		}		
	}
	return $lb_valido;
}

function uf_update_monto_mov($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que actualiza el monto de un movimiento cuando se selecciona la misma cuenta
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_codban = $arr_movbco["codban"];
 		$ls_ctaban = $arr_movbco["ctaban"];
		$ls_numdoc = $arr_movbco["mov_document"];
		$ls_codope = $arr_movbco["codope"];
		$ls_estmov = $arr_movbco["estmov"];
		
		$ls_sql="UPDATE scb_movbco_scg SET monto=".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
				 AND scg_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'";

		$li_result=$this->io_sql->execute($ls_sql);	

		if($li_result===false)
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;			
		}
		else
		{
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_scgcta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
			$lb_valido=true;			
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		    $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	return $lb_valido;
}

function uf_update_montodelete($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que actualiza el monto del movimiento padre cuando se elimina una retencion
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_codban     = $arr_movbco["codban"];
 		$ls_ctaban     = $arr_movbco["ctaban"];
		$ls_numdoc     = $arr_movbco["mov_document"];
		$ls_codope     = $arr_movbco["codope"];
		$ls_estmov     = $arr_movbco["estmov"];
		
		$ls_sql="UPDATE scb_movbco_scg SET monto=monto + ".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
				 AND scg_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'";
		$li_result=$this->io_sql->execute($ls_sql);	
		if(($li_result===false))
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;			
		}
		else
		{
			
			//$this->uf_update_montos_auxiliares_movbco_scg($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_cuenta,$ls_operacioncon,$ls_codded,$ls_documento);
			
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		    $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}

	return $lb_valido;
}

function uf_select_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica si existe el movimiento contable
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
   	
	$ls_sql="SELECT * 
			 FROM scb_movbco_spg
			 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			 AND numdoc='".$ls_numdoc."' AND codope='".$as_codope."' AND estmov='".$ls_estmov."' 
			 AND spg_cuenta='".$ls_spgcuenta."' AND codestpro='".$ls_programa."' AND documento='$ls_documento'";
			
	$rs_dt_scg=$this->io_sql->select($ls_sql);
	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle Presupuestario de gasto ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}

function uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento,$ls_desmov,$ls_procededoc,$ldec_monto,$ls_operacion)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle presupuestario del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];	

	$lb_existe=$this->uf_select_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento);
	if(!$lb_existe)
	{
		$ls_sql="INSERT INTO scb_movbco_spg(codemp,codban,ctaban,numdoc,codope,estmov,codestpro,spg_cuenta,documento,desmov,procede_doc,monto,operacion)
				 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$as_codope."','".$ls_estmov."','".$ls_programa."','".$ls_spgcuenta."','".$ls_documento."','".$ls_desmov."','".$ls_procededoc."',".$ldec_monto.",'".$ls_operacion."')";

//print $ls_sql;
		$ls_evento="INSERT";
		$this->is_msg_error="Registro Insertado";
		$ls_descripcion="Inserto el detalle presupuestario a la cuenta ".$ls_spgcuenta." asociado a la programatica ".$ls_programa."  por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
	}
	else
	{
		$ls_sql="UPDATE scb_movbco_spg 
				    SET monto=monto+".$ldec_monto."
				  WHERE codemp='".$ls_codemp."' 
				    AND codban='".$ls_codban."'
					AND ctaban='".$ls_ctaban."' 
				    AND numdoc='".$ls_numdoc."'
					AND codope='".$as_codope."'
					AND estmov='".$ls_estmov."' 
				    AND codestpro='".$ls_programa."'
					AND spg_cuenta='".$ls_spgcuenta."'
					AND documento='".$ls_documento."'";
		$ls_evento="UPDATE";
		$this->is_msg_error="Registro Actualizado";
		$ls_descripcion="Actualizo el detalle presupuestario a la cuenta ".$ls_spgcuenta." asociado a la programatica ".$ls_programa."  por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
    }
	$li_result=$this->io_sql->execute($ls_sql);
	
	if(($li_result===false))
	{
		$this->is_msg_error="Error al guardar detalle de gasto, ".$this->fun->uf_convertirmsg($this->io_sql->message);		
		$lb_valido=false;
	}
	else
	{
		if (!$lb_existe)
		   {
		    
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_programa);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcuenta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
		   }
		else
		   {
			 
			 $this->uf_update_montos_auxiliares_movbco_spg($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento);
			 
		   }   
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	return $lb_valido;
}

function uf_select_dt_ingreso($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_spicuenta,$ls_documento)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica si existe el movimiento contable
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
   	
	$ls_sql="SELECT * 
			 FROM scb_movbco_spi
			 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			 AND numdoc='".$ls_numdoc."' AND codope='".$as_codope."' AND estmov='".$ls_estmov."' 
			 AND spi_cuenta='".$ls_spicuenta."' AND documento='$ls_documento'";
			
	$rs_dt_scg=$this->io_sql->select($ls_sql);
	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle de ingreso ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}

function uf_procesar_dt_ingreso($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_spicuenta,$ls_documento,$ls_desmov,$ls_procededoc,$ldec_monto,$ls_operacion)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle presupuestario del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];	

	$lb_existe=$this->uf_select_dt_ingreso($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_spicuenta,$ls_documento);
	
	if(!$lb_existe)
	{
		$ls_sql="INSERT INTO scb_movbco_spi(codemp,codban,ctaban,numdoc,codope,estmov,spi_cuenta,documento,desmov,procede_doc,monto,operacion)
				 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$as_codope."','".$ls_estmov."','".$ls_spicuenta."','".$ls_documento."','".$ls_desmov."','".$ls_procededoc."',".$ldec_monto.",'".$ls_operacion."')";
		$ls_evento="INSERT";
		$this->is_msg_error="Registro Insertado";
	    $ls_descripcion="Inserto el detalle de ingreso a la cuenta ".$ls_spicuenta."  por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
	}
	else
	{
		$ls_sql="UPDATE scb_movbco_spi 
				 SET monto=monto+".$ldec_monto."
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_numdoc."' AND codope='".$as_codope."' AND estmov='".$ls_estmov."' 
				 AND spi_cuenta='".$ls_spicuenta."' AND documento='".$ls_documento."'";
		$ls_evento="UPDATE";
		$this->is_msg_error="Registro Actualizado";
		$ls_descripcion="Actualizo el detalle de ingreso a la cuenta ".$ls_spicuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
	}
	
	$li_result=$this->io_sql->execute($ls_sql);
	
	if(($li_result===false))
	{
		$this->is_msg_error="Error al guardar detalle de ingreso, ".$this->fun->uf_convertirmsg($this->io_sql->message);		
		print $this->io_sql->message;
		$lb_valido=false;
	}

	else
	{
		if (!$lb_existe)
		   {
		     
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicuenta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
		   }
		else
		   {
			 
			 $this->uf_update_montos_auxiliares_movbco_spi($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_spicuenta,$ls_documento);
			 
		   }
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	return $lb_valido;
}

function uf_delete_dt_spi($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_spi,$ls_operacion,$ldec_monto)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina el detalle presupuestario del movimiento 
	//  junto con el contable asociado a la cuenta de presupuesto.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_cuenta_scg=$this->uf_select_cuenta_contable($ls_codemp,$ls_cuenta_spi);
	
	$ls_sql=" DELETE FROM scb_movbco_spi 
			  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND operacion='".$ls_operacion."' 
			  AND documento='".$ls_numdoc."' AND spi_cuenta='".$ls_cuenta_spi."'";
	$li_result=$this->io_sql->execute($ls_sql);				  

	if(($li_result===false))	
	{
		$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion="Elimino el detalle de ingreso a la cuenta ".$ls_cuenta_spi." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_scg,'H','00000',$ldec_monto,'SPG');
		
		$this->is_msg_error="El detalle de ingreso fue eliminado";				
	}

	return $lb_valido;
}

function uf_cargar_dt($as_numdoc,$as_codban,$as_ctaban,$as_codope,$ls_estmov,$objectScg,$li_row_scg,$ldec_mondeb,$ldec_monhab,$objectSpg,$li_temp_spg,$ldec_monto_spg,$objectSpi,$li_temp_spi,$ldec_monto_spi)
{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que carga todos los detalles del movimiento de banco en los object 
		//	requeridos por la clase grid_param.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$li_row_scg=0;
		$li_temp_spg=0;
		$li_temp_spi=0;
		$li_temp_ret=0;
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_sql="SELECT codban,ctaban,codope,estmov,trim(scg_cuenta) as scg_cuenta,codded,debhab,documento,desmov,procede_doc,monto,monobjret
				   FROM scb_movbco_scg
				  WHERE codemp='".$ls_codemp ."' 
				    AND numdoc ='".$as_numdoc."' 
					AND codban='".$as_codban."' 
					AND ctaban='".$as_ctaban."'
					AND codope='".$as_codope."'
					AND estmov='".$ls_estmov."'	
				  ORDER BY debhab asc,numdoc asc";
				 
		$rs_scg=$this->io_sql->select($ls_sql);		
		if(($rs_scg===false))
		{
			$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_scg))
			{
					$li_row_scg=$li_row_scg+1;
					$ls_cuenta=$row["scg_cuenta"];
					$ls_documento=$row["documento"];
					$ls_descripcion=$row["desmov"];
					$ls_procede=$row["procede_doc"];
					$ls_debhab=$row["debhab"];
					$ldec_monto=$row["monto"];
					if($ls_debhab=="D")
					{
						$ldec_mondeb=$ldec_mondeb+$ldec_monto;
					}
					else
					{
						$ldec_monhab=round($ldec_monhab,2)+$ldec_monto;
					}
					$ls_codded=$row["codded"];
					$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
					$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='".$ls_descripcion."'  title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
					$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
					$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
					$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=22>";
					$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg." value='".$ls_codded."' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
					$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
					
			}
			
			if($li_row_scg==0)		
			{
				$li_row_scg=1;
				$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
				$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
				$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
				$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=16 maxlength=22>";
				$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
				$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
			
			}
			$this->io_sql->free_result($rs_scg);
		}		 


		$ls_sql="SELECT   codban,ctaban,estmov,operacion,codestpro,trim(spg_cuenta) as spg_cuenta,documento,desmov,procede_doc,monto
				 FROM     scb_movbco_spg
        		 WHERE    codemp='".$ls_codemp."' AND numdoc ='".$as_numdoc."' and codban='".$as_codban."' 
				 and ctaban='".$as_ctaban."' and codope='".$as_codope."' AND estmov='".$ls_estmov."'
		  	     ORDER BY numdoc asc";
//print $ls_sql;
		$rs_spg=$this->io_sql->select($ls_sql);		
		if(($rs_spg===false))
		{
			$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{

			while($row=$this->io_sql->fetch_row($rs_spg))
			{
				$li_temp_spg=$li_temp_spg+1;
				$ls_cuenta=$row["spg_cuenta"];
				$ls_programatica=$row["codestpro"];
				$ls_documento=$row["documento"];
				$ls_descripcion=$row["desmov"];
				$ls_procede=$row["procede_doc"];
				$ls_operacion_spg=$row["operacion"];
				$ldec_monto=$row["monto"];
				$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
				if($ls_estmodest==2)
				{
					$ls_codestpro1=substr(substr($ls_programatica,0,20),-2);
					$ls_codestpro2=substr(substr($ls_programatica,20,6),-2);
					$ls_codestpro3=substr(substr($ls_programatica,26,3),-2);
					$ls_codestpro4=substr($ls_programatica,29,2);	
					$ls_codestpro5=substr($ls_programatica,31,2);
					$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
				}
				else
				{
					$ls_programatica=substr($ls_programatica,0,29);
				}
				
				$ldec_monto_spg=$ldec_monto_spg+$ldec_monto;
				$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='".$ls_cuenta."'       class=sin-borde readonly style=text-align:center size=9 maxlength=25>";
				$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=30 maxlength=33 >"; 
				$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='".$ls_documento."'    class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
				$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='".$ls_descripcion."'  title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left>";
				$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='".$ls_procede."'      class=sin-borde readonly style=text-align:center size=5 maxlength=6>";
				$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='".$ls_operacion_spg."'    class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='".number_format($ldec_monto,2,",",".")."'      class=sin-borde readonly style=text-align:right size=15 maxlength=19>";		
				$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
			}
			if($li_temp_spg==0)
			{
				$li_temp_spg=1;
				$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=9 maxlength=25 >";
				$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=30 maxlength=33>"; 
				$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
				$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
				$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=5 maxlength=6>";
				$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right size=15 maxlength=19>";		
				$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
			}
			$this->io_sql->free_result($rs_spg);
		}
		
		$ls_sql="SELECT   codban,ctaban,estmov,operacion,trim(spi_cuenta) as spi_cuenta,documento,desmov,procede_doc,monto
				 FROM     scb_movbco_spi
        		 WHERE    codemp='".$ls_codemp."' AND numdoc ='".$as_numdoc."' and codban='".$as_codban."' and ctaban='".$as_ctaban."' and codope='".$as_codope."' AND estmov='".$ls_estmov."'
				 ORDER BY numdoc asc";
		$rs_spi=$this->io_sql->select($ls_sql);		
		if(($rs_spi===false))
		{
			$this->is_msg_error="Error en select, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_spi))
			{
				$li_temp_spi=$li_temp_spi+1;
				$ls_cuenta=$row["spi_cuenta"];
				$ls_descripcion=$row["desmov"];
				$ls_procede=$row["procede_doc"];
				$ls_documento=$row["documento"];
				$ls_operacion_spi=$row["operacion"];
				$ldec_monto=$row["monto"];
				$ldec_monto_spi=$ldec_monto_spi + $ldec_monto;
				$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=9 maxlength=25>";
				$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
				$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
				$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center>";
				$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='".$ls_operacion_spi."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:center size=15 maxlength=19>";
				$objectSpi[$li_temp_spi][7]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
			}
			if($li_temp_spi==0)
			{
				$li_temp_spi=1;
				$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=9 maxlength=25>";
				$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
				$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
				$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
				$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=19>";
				$objectSpi[$li_temp_spi][7]  = "<img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0>";	
			}
			$this->io_sql->free_result($rs_spi);
		}	
		
}
	
function uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_documento,$ls_scgcuenta,$ls_debhab,$ls_codded,$ldec_monto,$ls_proc_delete)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// - Funcion que elimina el detalle contable del movimiento  de banco
	//
	///////////////////////////////////////////////////////////////////////////////////////////////

	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	if($ls_proc_delete=="SCG")
	{
		$ls_sql=" DELETE FROM scb_movbco_scg 
				  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
				  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."' 
				  AND scg_cuenta='".$ls_scgcuenta."'";
	}
	else
	{
		$ldec_diferencia=$this->uf_calcular_diferencia($ls_codemp,$ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ldec_monto,$ls_scgcuenta,$ls_documento);
		
		if($ldec_diferencia!=0)
		{
			$ls_sql=" UPDATE scb_movbco_scg 
					  SET monto=(monto-$ldec_monto)
					  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
					  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'
					  AND scg_cuenta='".$ls_scgcuenta."'";
		    
		}
		else
		{
			$ls_sql=" DELETE FROM scb_movbco_scg 
					  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
					  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."' 
					  AND scg_cuenta='".$ls_scgcuenta."'";
		}
	}
	$li_result=$this->io_sql->execute($ls_sql);			

	if(($li_result===false))	
	{
		$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
	  if ($ls_proc_delete!="SCG")
	     {
		   if ($ldec_diferencia!=0)
		      {
			    //$this->uf_update_montos_auxiliares_movbco_scg($ls_codemp,$ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_scgcuenta,$ls_debhab,$ls_codded,$ls_documento);
		      }
		 }
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion="Elimino el detalle contable a la cuenta ".$ls_scgcuenta." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_mov_document." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_msg_error="El detalle contable fue eliminado";				
	}

	return $lb_valido;
}
	
function uf_calcular_diferencia($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldec_monto,$ls_scgcuenta,$ls_documento)
{
	
	$ls_sql="SELECT * 
			 FROM scb_movbco_scg 
			 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' 
			 AND scg_cuenta='".$ls_scgcuenta."' AND documento='".$ls_documento."'";
	$rs_data=$this->io_sql->select($ls_sql);
	
	if(($rs_data===false))	
	{
		$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
		$ldec_monto_scg=0;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_monto_scg=$row["monto"];
		}
		else
		{
			$ldec_monto_scg=0;
		}
	}
	$ldec_diferencia=$ldec_monto-$ldec_monto_scg;
	return $ldec_diferencia;	 
			 
}
	
function uf_select_cuenta_scg($ls_codemp,$ls_programatica,$ls_cuenta_spg)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que retorna cuenta contable asociada 
	//  a la cuenta presupuestaria enviada como parametro.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ls_codest1=substr($ls_programatica,0,20);
	$ls_codest2=substr($ls_programatica,20,6);
	$ls_codest3=substr($ls_programatica,26,3);
	$ls_codest4=substr($ls_programatica,29,2);
	$ls_codest5=substr($ls_programatica,31,2);
	$ls_sql="SELECT * FROM spg_cuentas 
			 WHERE codemp='".$ls_codemp."' 	AND codestpro1='".$ls_codest1."' AND codestpro2='".$ls_codest2."' 
			 AND codestpro3='".$ls_codest3."' AND codestpro4='".$ls_codest4."' AND codestpro5='".$ls_codest5."' 
			 AND spg_cuenta='".$ls_cuenta_spg."'" ;

	$rs_cuenta=$this->io_sql->select($ls_sql);				  
	
	if(($rs_cuenta===false))	
	{
		$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
		$ls_cuenta_scg="";
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_cuenta))
		{
			$ls_cuenta_scg=$row["sc_cuenta"];
		}
		else
		{
			$ls_cuenta_scg="";
		}
	}
return $ls_cuenta_scg;
}

function uf_select_cuenta_contable($ls_codemp,$ls_cuenta_spi)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que retorna cuenta contable asociada 
	//  a la cuenta de ingreso enviada como parametro.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM spi_cuentas 
			 WHERE codemp='".$ls_codemp."' 	 
			 AND spi_cuenta='".$ls_cuenta_spi."'" ;

	$rs_cuenta=$this->io_sql->select($ls_sql);				  
	
	if(($rs_cuenta===false))	
	{
		$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
		$ls_cuenta_scg="";
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_cuenta))
		{
			$ls_cuenta_scg=$row["sc_cuenta"];
		}
		else
		{
			$ls_cuenta_scg="";
		}
	}
return $ls_cuenta_scg;
}

function uf_delete_dt_spg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ldec_monto)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina el detalle presupuestario del movimiento 
	//  junto con el contable asociado a la cuenta de presupuesto.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_cuenta_scg=$this->uf_select_cuenta_scg($ls_codemp,$ls_programatica,$ls_cuenta_spg);
	
	$ls_sql=" DELETE FROM scb_movbco_spg 
			  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND operacion='".$ls_operacion."' 
			  AND codestpro='".$ls_programatica."' AND documento='".$ls_numdoc."' AND spg_cuenta='".$ls_cuenta_spg."'";
	
	$li_result=$this->io_sql->execute($ls_sql);				  

	if(($li_result===false))	
	{
		$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion="Elimino el detalle presupuestario a la cuenta ".$ls_cuenta_spg." de programatica ".$ls_programatica." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_scg,'D','00000',$ldec_monto,'SPG');
		$this->is_msg_error="El detalle presupuestario fue eliminado";				
	}

	return $lb_valido;
}
	
function uf_delete_all_movimiento($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina el movimiento Bancario junto con los detalles contables,presupuestarios
	//  asociados a el mismo.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];		
	
	$lb_valido=	$this->uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);//Funcion que elimina los detalles del movimiento

	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movbco 
				 WHERE 	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."' AND estmov<>'C' AND estmov<>'A' AND estmov<>'O'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php.Método->uf_delete_all_movimiento->Error al eliminar detalle de movimiento".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			if($this->io_sql->errno==1451)
			{
				$this->is_msg_error="No se pudo eliminar el Movimiento,posee relaciones";	
			}				
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->is_msg_error="El movimiento Bancario fue eliminado";
		}		
	}
	else
	{
		$lb_valido=false;
	}
	return $lb_valido;
}

function uf_delete_anulado($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov)
{
////////////////////////////////////////////////////////////////////////////////////////////////
//
// -Funcion que elimina el movimiento Bancario junto con los detalles contables,presupuestarios
//  asociados a el mismo.
//
///////////////////////////////////////////////////////////////////////////////////////////////
	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];		
	
	$lb_valido=	$this->uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);//Funcion que elimina los detalles del movimiento

	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movbco 
				 WHERE 	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."' ";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php.Método->uf_delete_anulado->Error al eliminar detalle de movimiento".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			if($this->io_sql->errno==1451)
			{
				$this->is_msg_error="No se pudo eliminar el Movimiento,posee relaciones";	
			}				
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->is_msg_error="El movimiento Bancario fue eliminado";
		}		
	}
	else
	{
		$lb_valido=false;
	}
	return $lb_valido;
}

function uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina todos los detalles asociados al movimiento Bancario 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql="DELETE FROM scb_movbco_scg 
			 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
	
	$li_result=$this->io_sql->execute($ls_sql);
	
	if(($li_result===false))
	{
		$lb_valido=false;
		$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php.Método->uf_delete_all_dtmov->Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
	}
	else
	{
		$lb_valido=true;
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion="Elimino los detalles contables del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movbco_spg 
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php->Método->uf_delete_all_dtmov.Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino los detalles presupuestarios del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	}		
	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movbco_spi
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php.Método->uf_delete_all_dtmov.Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino los detalles de ingresos del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	}			
	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="CLASS->tepuy_scb_c_movbanco.php.Método->uf_delete_all_dtmov.Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino los detalles de fuente de financimiento del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	}			

	
	return $lb_valido;
}

function uf_procesar_errorbanco($arr_errorbco)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	uf_procesar_errorbanco
	// Access:		public
	//	Returns:	Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se encarga de guardar el movimiento por erro de banco de la conciliacion
	//						insertando o actualizando
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp		= $this->dat["codemp"];
		$ls_codban      = $arr_errorbco["codban"];
		$ls_ctaban      = $arr_errorbco["ctaban"];
		$ls_numdoc      = $arr_errorbco["numdoc"];
		$ls_codope      = $arr_errorbco["codope"];
		$ls_estmov      = $arr_errorbco["estmov"];
		$ls_fecmes 		= str_replace("/","",$arr_errorbco["fecmes"]);
		$ldt_fecmov     = $this->fun->uf_convertirdatetobd($arr_errorbco["fecmov"]);
		$ls_conmov      = $arr_errorbco["conmov"];
		$ldec_monto     = $arr_errorbco["monto"] ;
		$ldec_monret    = $arr_errorbco["monret"];
		$li_cobrapaga   = $arr_errorbco["cobrapaga"];
		$li_estmovint   = $arr_errorbco["estmovint"];
		$ls_chevau      = $arr_errorbco["chevau"];
		$ls_procede     = $arr_errorbco["procede_doc"];
		$ls_estbpd      = $arr_errorbco["estbpd"];
		$ls_esterr 		= $arr_errorbco["esterrcon"];
		
		$lb_existe=$this->uf_select_error_banco($ls_numdoc,$ls_codope,$ls_ctaban,$ls_codban,$ls_fecmes);
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO scb_errorconcbco(codemp,codban,ctaban,numdoc,codope,fecmov,conmov,monmov,monret,chevou,estmov,estbpd,esterrcon,fecmesano,estcon)
					 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ldt_fecmov."','".$ls_conmov."',".$ldec_monto.",".$ldec_monret.",'".$ls_chevau."','".$ls_estmov."','".$ls_estbpd."','".$ls_esterr."','".$ls_fecmes."',1)";
			$ls_mensaje="Error en insert error banco";
			$ls_descripcion="Inserto el movimiento bancario por error en banco de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
		    
		}
		else
		{
			$ls_sql="UPDATE scb_errorconcbco
					SET fecmov='$ldt_fecmov',conmov='$ls_conmov',monmov='$ldec_monto',monret='$ldec_monret',
					chevou='$ls_chevau',estmov='$ls_estmov',estbpd='$ls_estbpd',esterrcon='$ls_esterr',estcon=1
					WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codope='$ls_codope' and
					ctaban='$ls_ctaban' AND codban='$ls_codban'AND fecmesano='$ls_fecmes'";
			$ls_mensaje="Error en update error banco";
			$ls_descripcion="Actualizo el movimiento bancario por error en banco de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;

		
		}
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error=$ls_mensaje." ".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
		  if (!$lb_existe)
			   {
			     $this->io_rcbsf->io_ds_datos->insertRow("campo","monmovaux");
				 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

				 $this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monret);
				
				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");

				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecmesano");
				 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecmes);
				 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				 $lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_errorconcbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			   }
			
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion="Inserto el movimiento bancario por error en banco de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->is_msg_error="El movimiento de error en banco fue registrado";
		}
		return $lb_valido;

}

function uf_numero_voucher($as_codemp,$ls_codban,$ls_ctaban,$ls_numdoc)
{
	 $ls_sql="  SELECT chevau 
				FROM   scb_movbco  
				WHERE  codemp ='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='CH'";		
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {
			$ls_codigo="";  
	  }
	  else
	  {
		  if ($row=$this->io_sql->fetch_row($rs_data))
		  { 
			  $ls_codigo=$row["chevau"];		  
		  }
		  else
		  {
			  $ls_codigo="";  
		  }
	  }
	return $ls_codigo;
}
	
function uf_select_voucher($ls_chevau)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica que retorna true si el vaucher introducido ya existe
	// Autor: Ing. Laura Cabre
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];		
	$ls_sql="SELECT chevau 
			FROM scb_movbco 
			WHERE chevau='$ls_chevau' AND codope='CH' and  codemp='$ls_codemp'";		
	$rs_mov=$this->io_sql->select($ls_sql);
	if(($rs_mov===false))
	{
		pg_set_error_verbosity($this->io_sql->conn, PGSQL_ERRORS_TERSE);
		$ls_x=pg_last_error($this->io_sql->conn);
		$this->is_msg_error="Error en uf_select_voucher,".$this->io_sql->message;
		print $this->is_msg_error;
		return false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_mov))
		{
			if($row["chevau"]!="")
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}	
	}	
}
	
function uf_eliminar_error_banco($as_documento,$as_ctaban,$as_codban,$as_fecmesano)
{
	/*----------------------------------------------------------
	Funcion: uf_eliminar_error_banco
	Descripcion: Funcion que permite eliminar un error en banco
	Autor: Ing. Laura Cabré
	Fecha: 06/12/2006
	-----------------------------------------------------------*/
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	$ls_sql="DELETE FROM scb_errorconcbco
			 WHERE	codemp='".$ls_codemp."' AND numdoc like '".$as_documento."' AND codope<>'OP' and
			 ctaban='$as_ctaban' AND codban='$as_codban' AND fecmesano='$as_fecmesano'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error uf_eliminar_error_banco".$this->fun->un_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			//////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino el Error de Banco ".$as_documento." para el banco ".$as_codban." cuenta ".$as_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			return true;
		}

}
	
function uf_select_error_banco($as_documento,$as_codope,$as_ctaban,$as_codban,$as_mesano)
{
	/*-----------------------------------------------
	Funcion:uf_select_error_banco
	Descripcion: Funcion que retorna true si existe el registro
	Autor: Ing. Laura Cabré
	Fecha: 07/12/2006
	-----------------------------------------------------*/
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];		
	$ls_sql="SELECT * FROM scb_errorconcbco
			 WHERE codemp='".$ls_codemp."' AND numdoc='".$as_documento."' AND codope='$as_codope' and
			 ctaban='$as_ctaban' AND codban='$as_codban' AND fecmesano='$as_mesano'";
	
	$rs_mov=$this->io_sql->select($ls_sql);
	if(($rs_mov===false))
	{
		$this->is_msg_error="Error en uf_select_error_banco,".$this->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
		return false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_mov))
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
}

function uf_update_montos_auxiliares_movbco_scg($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_cuenta,$as_debhab,$as_codded,$as_documento)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_movbco_scg
//		    Access: private
//	     Arguments: 
//       $as_codemp Código de la Empresa.
//       $as_codban Código del Banco.
//       $as_ctaban Número de la Cuenta Bancaria. 
//       $as_numdoc Número del Documento Bancario.
//       $as_codope Código de la Operacion CH=Cheque, ND=Nota de Débito, NC=Nota de Crédito, DP=Deposito.
//       $as_estmov Estatus del Movimiento A=Anulado, C=Contabilizado, O=Original.
//       $as_cuenta Cuenta de identificacion Contable.
// $as_operacioncon La Operacion D=Debe H=Haber.
//       $as_codded Código de la deduccion aplicada.
//    $as_documento Número auxiliar de documento.
//	       Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	   Description: Función que busca y actualiza monto con su correspondiente en Bs.F.
//	    Creado Por: Ing. Miguel Palencia.
//  Fecha Creación: 15/08/2007 								Fecha Última Modificación : 15/08/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;

  $ls_sql  = "SELECT monto
                FROM scb_movbco_scg
			   WHERE codemp='".$as_codemp."'
			     AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."'
				 AND numdoc='".$as_numdoc."'
				 AND codope='".$as_codope."'
				 AND estmov='".$as_estmov."'
				 AND scg_cuenta='".$as_cuenta."'
				 AND debhab='".$as_debhab."'
				 AND codded='".$as_codded."'
				 AND documento='".$as_documento."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ld_monto = $row["monto"] ;  
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cuenta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_debhab);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codded);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
		  }
	 }
  return $lb_valido;
}

function uf_update_montos_auxiliares_movbco_spg($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_programa,$as_spgcuenta,$as_documento)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_movbco_spg
//		    Access: private
//	     Arguments: 
//       $as_codemp
//       $as_codban
//       $as_ctaban
//       $as_numdoc
//       $as_codope
//       $as_estmov
//     $as_programa
//    $as_spgcuenta
//    $as_documento
//	       Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	   Description: Función que busca y actualiza monto con su correspondiente en Bs.F.
//	    Creado Por: Ing. Miguel Palencia.
//  Fecha Creación: 15/08/2007 								Fecha Última Modificación : 15/08/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;

  $ls_sql  = "SELECT monto
                FROM scb_movbco_spg
			   WHERE codemp='".$as_codemp."' 
			     AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."' 
				 AND numdoc='".$as_numdoc."'
				 AND codope='".$as_codope."' 
				 AND estmov='".$as_estmov."' 
				 AND codestpro='".$as_programa."' 
				 AND spg_cuenta='".$as_spgcuenta."' 
				 AND documento='".$as_documento."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ld_monto = $row["monto"];   
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_programa);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spgcuenta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
		  }
	 }
  return $lb_valido;
}

function uf_update_montos_auxiliares_movbco_spi($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_spicuenta,$as_documento)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_movbco_spi
//		    Access: private
//	     Arguments: 
//       $as_codemp
//       $as_codban
//       $as_ctaban
//       $as_numdoc
//       $as_codope
//       $as_estmov
//    $as_spicuenta
//    $as_documento
//	       Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	   Description: Función que busca y actualiza monto con su correspondiente en Bs.F.
//	    Creado Por: Ing. Miguel Palencia.
//  Fecha Creación: 15/08/2007 								Fecha Última Modificación : 15/08/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;

  $ls_sql  = "SELECT monto
                FROM scb_movbco_spg
			   WHERE codemp='".$as_codemp."' 
			     AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."' 
				 AND numdoc='".$as_numdoc."'
				 AND codope='".$as_codope."'
				 AND estmov='".$as_estmov."' 
				 AND spi_cuenta='".$as_spicuenta."' 
				 AND documento='".$as_documento."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ld_monto = $row["monto"];   
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");


			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spicta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			
		  }
	 }
  return $lb_valido;
}

function uf_actualizar_estatus_ch($ls_codban,$ls_ctaban,$ls_numdoc,$ls_numchequera,$ai_estche)
{
  $lb_valido = true;
  if (!empty($ls_numdoc)&(!empty($ls_numchequera)))
	 {
		$ls_sql="SELECT codban
				   FROM scb_cheques
				  WHERE codban='".$ls_codban."' 
					AND ctaban='".$ls_ctaban."' 
					AND numche='".$ls_numdoc."' 
					AND numchequera='".$ls_numchequera."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;					
		}
		else
		{
		 if ($row=$this->io_sql->fetch_row($rs_data))
			{
			 /* $ls_sql = "UPDATE scb_cheques 
							SET estche=".$ai_estche."
						  WHERE codban='".$ls_codban."' 
							AND ctaban='".$ls_ctaban."' 
							AND numche='".$ls_numdoc."' 
							AND numchequera='".$ls_numchequera."'";	*/
			
			////////////////////////agregado el 08/12/2007/////////////////////////////////////////////////////////////
			$ls_sql = "UPDATE scb_cheques 
							SET estche=".$ai_estche.", estins=".$ai_estche."
							WHERE codban='".$ls_codban."' 
							AND ctaban='".$ls_ctaban."' 
							AND numche='".$ls_numdoc."' 
							AND numchequera='".$ls_numchequera."'";		
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
			   $li_result=$this->io_sql->execute($ls_sql);
			   if ($li_result===false)
				  {
					$this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
					$lb_valido=false;					
				  }		 
			}
		}
	}
  return $lb_valido;
}

function uf_insert_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_codfuefin)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_fuentefinancimiento
	//		   Access: public  
	//	    Arguments: as_codemp  // Código de empresa
	//				   as_codban  // Código de Banco
	//				   as_ctaban  // Cuenta del Banco
	//				   as_numdoc  // Número de Documento
	//				   as_codope  // Código de Operación
	//				   as_estmov  // Estatus del Movimiento
	//				   as_codfuefin  // código de La fuente de Financiamiento
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta la fuente de financiamiento por movimiento de banco
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento(codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) VALUES ".
			"('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','".$as_estmov."','".$as_codfuefin."')";
	$li_numrow=$this->io_sql->execute($ls_sql);
	if($li_numrow===false)
	{
		$lb_valido=false;
		print $this->io_sql->message;
		$this->msg->message("CLASE->Movimiento de Banco MÉTODO->uf_insert_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
	}
	return $lb_valido;
}

function uf_delete_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_delete_fuentefinancimiento
	//		   Access: public  
	//	    Arguments: as_codemp  // Código de empresa
	//				   as_codban  // Código de Banco
	//				   as_ctaban  // Cuenta del Banco
	//				   as_numdoc  // Número de Documento
	//				   as_codope  // Código de Operación
	//				   as_estmov  // Estatus del Movimiento
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que elimina la fuente de financiamiento por movimiento de banco
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="DELETE ".
			"  FROM scb_movbco_fuefinanciamiento ".
			" WHERE	codemp='".$as_codemp."' ".
			"   AND codban='".$as_codban."' ".
			"   AND ctaban='".$as_ctaban."' ".
			"   AND codope='".$as_codope."' ".
			"   AND estmov='".$as_estmov."' ".
			"   AND numdoc='".$as_numdoc."'";
	$li_numrow=$this->io_sql->execute($ls_sql);
	if($li_numrow===false)
	{
		$lb_valido=false;
		print $this->io_sql->message;
		$this->msg->message("CLASE->Movimiento de Banco MÉTODO->uf_delete_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
	}
	return $lb_valido;
}

function uf_check_insert_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_codfuefin)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_check_insert_fuentefinancimiento
	//		   Access: public  
	//	    Arguments: as_codemp  // Código de empresa
	//				   as_codban  // Código de Banco
	//				   as_ctaban  // Cuenta del Banco
	//				   as_numdoc  // Número de Documento
	//				   as_codope  // Código de Operación
	//				   as_estmov  // Estatus del Movimiento
	//				   as_codfuefin  // código de La fuente de Financiamiento
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta la fuente de financiamiento por movimiento de banco
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="SELECT codfuefin ".
			"  FROM scb_movbco_fuefinanciamiento ".
			" WHERE	codemp='".$as_codemp."' ".
			"   AND codban='".$as_codban."' ".
			"   AND ctaban='".$as_ctaban."' ".
			"   AND codope='".$as_codope."' ".
			"   AND estmov='".$as_estmov."' ".
			"   AND numdoc='".$as_numdoc."' ".
			"   AND codfuefin='".$as_codfuefin."' ";
	$rs_data=$this->io_sql->select($ls_sql);	
	if($rs_data===false)
	{
		$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;	
		$lb_valido=false;		
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
		}
		else
		{
			$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento(codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) VALUES ".
					"('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','".$as_estmov."','".$as_codfuefin."')";
			$li_numrow=$this->io_sql->execute($ls_sql);
			if($li_numrow===false)
			{
				$lb_valido=false;
				print $this->io_sql->message;
				$this->msg->message("CLASE->Movimiento de Banco MÉTODO->uf_check_insert_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			}
		}
	}
	return $lb_valido;
}
}
?>
