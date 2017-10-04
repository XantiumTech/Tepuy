<?php
class tepuy_scb_c_transferencias
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	var $la_security;
	function tepuy_scb_c_transferencias($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("tepuy_scb_c_movbanco.php");
		$this->io_class_movbco=new tepuy_scb_c_movbanco($aa_security);
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_sql_aux=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->ds_temp=new class_datastore();
		$this->ds_sol=new class_datastore();
		$this->la_security=$aa_security;
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}

	function uf_procesar_transferencia($arr_data,$arr_datadestino)
	{
	//////////////////////////////////////////////////////////////////////////////
	//
	//	Function:	    uf_procesar_tranferencia
	// 					
	// Access:			public
	//
	//	Returns:		Boolean Retorna si proceso correctamente
	//
	//	Description:	Funcion que se encarga de guardar los detalles de la transferencia
	//               
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->dat["codemp"];
		$li_total=count($arr_data["numtra"]);

		for($li_i=1 ; $li_i<=$li_total ; $li_i++)//for datos de origen
		{
			$ls_numtrans=$arr_data["numtra"][$li_i];
			$ls_codban=$arr_data["Codban"][$li_i];
			$ls_cuenta_banco=$arr_data["Ctaban"][$li_i];
			$ls_numdoc=$arr_data["numdoc"][$li_i];
			$ls_codope=$arr_data["codope"][$li_i];
			$ld_fecha=$arr_data["fecmov"][$li_i];
			$ls_conmov=$arr_data["concepto"][$li_i];
			$ls_cedbene=$arr_data["ced_bene"][$li_i];
			$ls_codpro =$arr_data["cod_prov"][$li_i];
			$ls_debhab =$arr_data["debhab"][$li_i]; 
			$ls_cuenta_scg=$arr_data["scg_cuenta"][$li_i];
			$ls_nomproben=$arr_data["nomproben"][$li_i];
			$ls_estmov=$arr_data["estmov"][$li_i];	
			$ldec_monto=$arr_data["monto"][$li_i];
			$ldec_monobjret=$arr_data["monobjret"][$li_i];
			$ldec_monret=$arr_data["monret"][$li_i];
			$ls_chevau=$arr_data["chevau"][$li_i];
			$ls_estbpd=$arr_data["estbpd"][$li_i];
			$ls_procede=$arr_data["procede_doc"][$li_i];
			$ls_estmovint=$arr_data["estmovint"][$li_i];
			$ls_codded=$arr_data["codded"][$li_i];
			$ld_feccon="1900/01/01";
			if($li_i==1)	
			{
				$lb_existe=$this->uf_select_documento($ls_codemp,$ls_numtrans,$ls_codope);
				if($lb_existe)
				{
					$this->is_msg_error="Numero de Documento ".$ls_numtrans." ya existe, introduzca un nuevo numero";
					return false;
				}
				$lb_valido=$this->io_class_movbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_numtrans,$ls_codope,$ld_fecha ,$ls_conmov,'---',$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,0,1,$ls_estbpd,$ls_procede,' ','N','-','--');
				if (!$lb_valido)
				   {
				     $this->is_msg_error = $this->io_class_movbco->is_msg_error; 
					 return false ;
				   }
			}
			if($lb_valido)
			{
					$ls_sql="INSERT INTO scb_movbco_scg(codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,codded,documento,desmov,procede_doc,monto,monobjret)
							 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_cuenta_banco."','".$ls_numtrans."','".$ls_codope."','".$ls_estmov."','".$ls_cuenta_scg."','".$ls_debhab."','".$ls_codded."','".$ls_numdoc."','".$ls_conmov."','SCBTRA',".$ldec_monto.",".$ldec_monobjret.")";		
					$li_result=$this->io_sql->execute($ls_sql);
					if(($li_result===false))//3
					{
						$lb_valido=false;
						$this->is_msg_error="Error en insert scb_movbco_scg,".$this->fun->uf_convertirmsg($this->io_sql->message);		
					}
					else
					{
						$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
		
						$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monobjret);
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta_banco);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numtrans);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta_scg);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
						$lb_valido=true;	
					}//End 	(3)					
				}
		}
		if($lb_valido)
		{
			$li_total=count($arr_datadestino["numtra"]);
			
			for($li_i=1 ; $li_i<=$li_total ; $li_i++)//for datos destino
			{
				$ls_numtrans=$arr_datadestino["numtra"][$li_i];
				$ls_codban=$arr_datadestino["Codban"][$li_i];
				$ls_cuenta_banco=$arr_datadestino["Ctaban"][$li_i];
				$ls_numdoc=$arr_datadestino["numdoc"][$li_i];
				$ls_codope=$arr_datadestino["codope"][$li_i];
				$ld_fecha=$arr_datadestino["fecmov"][$li_i];
				$ls_conmov=$arr_datadestino["concepto"][$li_i];
				$ls_cedbene=$arr_datadestino["ced_bene"][$li_i];
				$ls_codpro =$arr_datadestino["cod_prov"][$li_i];
				$ls_nomproben=$arr_datadestino["nomproben"][$li_i];
				$ls_estmov=$arr_datadestino["estmov"][$li_i];	
				$ldec_monto=$arr_datadestino["monto"][$li_i];
				$ldec_monobjret=$arr_datadestino["monobjret"][$li_i];
				$ldec_monret=$arr_datadestino["monret"][$li_i];
				$ls_chevau=$arr_datadestino["chevau"][$li_i];
				$ls_estbpd=$arr_datadestino["estbpd"][$li_i];
				$ls_procede=$arr_datadestino["procede_doc"][$li_i];
				$ls_estmovint=$arr_datadestino["estmovint"][$li_i];
				$ls_codded=$arr_datadestino["codded"][$li_i];
				$ld_feccon="1900/01/01";
				$lb_existe=$this->uf_select_documento($ls_codemp,$ls_numtrans,$ls_codope);
				if($lb_existe)
				{
					$this->is_msg_error="Numero de Documento ".$ls_numtrans." ya existe, introduzca un nuevo numero";
					return false;
				}
				$lb_valido=$this->io_class_movbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_numtrans,$ls_codope,$ld_fecha,$ls_conmov,'---',$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,0,1,$ls_estbpd,$ls_procede,' ','N','-','--');
				if(!$lb_valido)
				{
					return false;
				}
			
				if(!$lb_valido)
				{
					break;
				}
			}
			
		}
		return $lb_valido;
	
	}//Fin de  uf_procesar_emision_chq
	
	function uf_select_ctaauxiliar($ls_cta,$ls_dencta)
	{
		$ls_sql="SELECT sc_cuenta, denominacion FROM scg_cuentas WHERE sc_cuenta like '1110101%' AND status='C'";
		$rs_cuentas=$this->io_sql->select($ls_sql);
		if(($rs_cuentas===false))
		{
			$this->is_msg_error="Error en consulta.".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_cuentas))
			{
				$ls_cta=$row["sc_cuenta"];
				$ls_dencta=$row["denominacion"];
			}
			else
			{
				$ls_cta="";
				$ls_dencta="";
			}
		}		
	}
	
	
	function uf_select_documento($ls_codemp,$ls_numdoc,$ls_codope)
	{
		$ls_sql="SELECT * 
				 FROM scb_movbco
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if(($rs_data===false))
		{
			return false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			else
			{
				$lb_existe=false;				
			}	
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_existe;		
	}
}
?>