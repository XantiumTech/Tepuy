<?php
class tepuy_scb_c_entregach
{
	var $dat;
 	var $SQL;
	var $is_msg_error;
	var $fun;
	var $la_security;
	function tepuy_scb_c_entregach($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->dat			= $_SESSION["la_empresa"];
		$this->sig_inc		= new tepuy_include();
		$con				= $this->sig_inc->uf_conectar();
		$this->SQL			= new class_sql($con);
		$this->fun			= new class_funciones();
		$this->la_security  = $aa_security; 
		$this->io_seguridad = new tepuy_c_seguridad();
	}

	function uf_cargar_cheques($as_codproben,$as_tipproben,$object,$li_row)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	   uf_cargar_cheques
		// Access:			public
		//	Returns:			Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de cargar el datastore con los cheques
		//						pendientes para ser entregados a los porveedores o beneficiarios
		//////////////////////////////////////////////////////////////////////////////
		
		$li_row    = 0;
		$ls_codemp = $this->dat["codemp"];
		if ($as_tipproben=="P")
		   {
		     $ls_tabla  = ", rpc_proveedor";
			 $ls_straux = " AND (scb_movbco.estbpd='P' OR scb_movbco.estbpd='D') AND trim(scb_movbco.cod_pro) = trim('".$as_codproben."')";
			 $ls_sqlaux = " AND rpc_proveedor.codemp = scb_movbco.codemp AND rpc_proveedor.cod_pro = scb_movbco.cod_pro";
		   }
		elseif($as_tipproben=="B")
		   {
		     $ls_tabla  = ", rpc_beneficiario";
			 $ls_straux = " AND (scb_movbco.estbpd='B' OR scb_movbco.estbpd='D') AND trim(scb_movbco.ced_bene) = trim('".$as_codproben."')";
			 $ls_sqlaux = " AND rpc_beneficiario.codemp = scb_movbco.codemp AND rpc_beneficiario.ced_bene = scb_movbco.ced_bene";
		   }
		else
		   {
		     $ls_tabla  = $ls_sqlaux = "";
			 $ls_straux = " AND (scb_movbco.estbpd='D')";
		   }
        $ls_sql = "SELECT scb_movbco.numdoc as numdoc,
			              scb_movbco.conmov as conmov,
					      scb_movbco.fecmov as fecmov,
			              scb_movbco.monto as monto,
						  scb_movbco.chevau as chevau,
			              scb_movbco.codban as codban,
						  scb_movbco.ctaban as ctaban
					 FROM scb_movbco, scb_banco, scb_ctabanco $ls_tabla 
					WHERE scb_movbco.estmov='C' $ls_straux						    
					  AND scb_movbco.estimpche=1 
					  AND scb_movbco.emicheproc=0 
					  AND scb_movbco.codope='CH' 
					  AND scb_movbco.tipo_destino='".$as_tipproben."'
					  AND scb_movbco.codemp = '".$ls_codemp."' 
					  AND scb_movbco.codemp = scb_banco.codemp 
					  AND scb_movbco.codban = scb_banco.codban						   
					  AND scb_ctabanco.codemp = scb_movbco.codemp
					  AND scb_ctabanco.ctaban = scb_movbco.ctaban $ls_sqlaux";
		$rs_data = $this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			print $this->SQL->message;	
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$li_row++;
				$ls_numdoc	= $row["numdoc"];
				$ls_desdoc	= $row["conmov"];
				$ldec_monto	= $row["monto"];
				$ls_codban	= $row["codban"];
				$ls_ctaban	= $row["ctaban"];
				$ls_voucher = $row["chevau"];
                $ls_fecmov  = $row["fecmov"];
				$ls_fecmov  = $this->fun->uf_convertirfecmostrar($ls_fecmov);						
															
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px>";		
				$object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."    value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$li_row][3] = "<input type=text     name=txtdesdoc".$li_row."    value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				$object[$li_row][4] = "<input type=text     name=txtmonto".$li_row."     value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=22>";
				$object[$li_row][5] = "<input type=text     name=txtfecmov".$li_row."    value='".$ls_fecmov."' class=sin-borde readonly style=text-align:center size=8 maxlength=10>"; 
				$object[$li_row][6] = "<input type=text     name=txtcodban".$li_row."    value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=3 maxlength=3>"; 
				$object[$li_row][7] = "<input type=text     name=txtcuenta".$li_row."    value='".$ls_ctaban."' class=sin-borde readonly style=text-align:center size=27 maxlength=25>";
				$object[$li_row][8] = "<input type=text     name=txtvoucher".$li_row."   value='".$ls_voucher."' class=sin-borde readonly style=text-align:center size=27 maxlength=25>";
			}
			if($li_row==0)
			{
				$li_total=5;
				for($li_row=1;$li_row<=$li_total;$li_row++)
				{
					$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px onClick='return false;'>";		
					$object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."       value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$object[$li_row][3] = "<input type=text     name=txtdesdoc".$li_row."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
					$object[$li_row][4] = "<input type=text     name=txtmonto".$li_row."     value='' class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
					$object[$li_row][5] = "<input type=text     name=txtfecmov".$li_row."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=10>"; 
					$object[$li_row][6] = "<input type=text     name=txtcodban".$li_row."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>"; 
					$object[$li_row][7] = "<input type=text     name=txtcuenta".$li_row."    value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
					$object[$li_row][8] = "<input type=text     name=txtvoucher".$li_row."   value='' class=sin-borde readonly style=text-align:right size=27 maxlength=25>";
				}
				$li_row=$li_total;
			}
		}		
		return $lb_valido;
	}

	function uf_procesar_entregach($arr_entregach,$as_codproben,$as_tipproben,$ad_fechaentrega,$as_cedula,$as_nombre,$ai_procesado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_procesar_entregach
		// Access:			public
		//	Returns:			Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de procesar la entrega del cheque al 
		//						proveedor o al beneficiario actualizando los campos de 
		//                cedula,nombre y fecha de la persona que recibio los cheques
		//////////////////////////////////////////////////////////////////////////////
		
		$ls_codemp = $this->dat["codemp"];
		$li_total  = count($arr_entregach["numdoc"]);
		$ad_fechaentrega=$this->fun->uf_convertirdatetobd($ad_fechaentrega);
		$this->SQL->begin_transaction();
		for ($li_i=1;$li_i<=$li_total;$li_i++)
		    {
			  $ls_codban = $arr_entregach["codban"][$li_i];
			  $ls_ctaban = $arr_entregach["ctaban"][$li_i];
			  $ls_numdoc = $arr_entregach["numdoc"][$li_i];
			  if ($as_tipproben=='P')
			     {
				   $ls_sqlaux 	   = " AND cod_pro='".trim($as_codproben)."'";
				   $ls_descripcion = "Se realizó la entrega del cheque No ".$ls_numdoc." del proveedor ".$as_codproben." y fue entregado a $as_nombre de cedula $as_cedula";
				 }
			  else
			     {
				   $ls_sqlaux 	   = " AND ced_bene='".trim($as_codproben)."'";
				   $ls_descripcion = "Se realizó la entrega del cheque No ".$ls_numdoc." del beneficiario ".$as_codproben." y fue entregado a $as_nombre de cedula $as_cedula";
				 }
			  $ls_sql = "UPDATE scb_movbco 
					        SET emicheproc='".$ai_procesado."',emicheced='".$as_cedula."',emichenom='".$as_nombre."',emichefec='".$ad_fechaentrega."' 
					      WHERE codemp = '".$ls_codemp."'  
						    AND codban = '".$ls_codban."' 
						    AND ctaban = '".$ls_ctaban."' 
						    AND numdoc = '".$ls_numdoc."'
						    AND codope = 'CH' $ls_sqlaux";				
			  $rs_data = $this->SQL->execute($ls_sql);
			  if ($rs_data===false)
			     {
				   $lb_valido=false;
				   $this->is_msg_error="Error en actualizar entrega de cheque, ".$this->fun->uf_convertirmsg($this->SQL->message);
			     }
			  else
			     {
		           $lb_valido=true;
			       ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			       $ls_evento="UPDATE";						
			       $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
				   ////////////////////////////////////////////////////////////////////////////////////////////////////////////								
		         }
		    }		
		return $lb_valido;
	}
}
?>