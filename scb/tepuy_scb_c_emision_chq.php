<?php
class tepuy_scb_c_emision_chq
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	
	function tepuy_scb_c_emision_chq()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_include.php");
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$this->io_sql=new class_sql($con);
		$this->io_sql_aux=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->ds_temp=new class_datastore();
		$this->ds_sol=new class_datastore();
	}

	function  uf_cargar_programaciones($as_proben,$as_codigo,$as_codban,$as_ctaban,$object,$li_rows,$ls_conmov)
	{
	    $ls_codemp = $this->dat["codemp"];
	    $ld_fecha  = date("Y-m-d");
	    if ($as_proben=='P')
	       {
		     $ls_tabla  = ', rpc_proveedor';
		     $ls_campo  = 'cod_pro';
		     $ls_campos = ',cxp_solicitudes.cod_pro as cod_pro, rpc_proveedor.nompro';
		     $ls_sqlaux = " AND cxp_solicitudes.tipproben='P' AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro";
		   }
	    elseif($as_proben=='B')
	       {
		     $ls_tabla  = ', rpc_beneficiario';
		     $ls_campo  = 'ced_bene';
		     $ls_campos = ',cxp_solicitudes.ced_bene,rpc_beneficiario.nombene,rpc_beneficiario.apebene';
		     $ls_sqlaux = " AND cxp_solicitudes.tipproben='B' AND cxp_solicitudes.ced_bene=rpc_beneficiario.ced_bene";
		   }
	    $ls_sql = "SELECT cxp_solicitudes.numsol as numsol,
		   		 	      cxp_solicitudes.consol as consol,
						  cxp_solicitudes.monsol as monsol,
						  scb_prog_pago.codban as codban,
						  scb_prog_pago.ctaban as ctaban,
						  cxp_solicitudes.codfuefin $ls_campos
	 			     FROM cxp_solicitudes, scb_prog_pago $ls_tabla
				    WHERE cxp_solicitudes.codemp='".$ls_codemp."' 
					  AND trim(cxp_solicitudes.$ls_campo)='".$as_codigo."' 
					  AND cxp_solicitudes.estprosol='S' 
					  AND scb_prog_pago.estmov='P' 
					  AND scb_prog_pago.codban='".$as_codban."' 
					  AND scb_prog_pago.ctaban='".$as_ctaban."'
					  AND scb_prog_pago.fecpropag<='".$ld_fecha."' $ls_sqlaux   
				      AND cxp_solicitudes.numsol=scb_prog_pago.numsol 
					  AND cxp_solicitudes.codemp=scb_prog_pago.codemp 
					ORDER BY cxp_solicitudes.numsol ASC";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $this->is_msg_error="Error en consulta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
			 $lb_valido=false;
		   }
		else
		   {
		     $li_temp = 0;
			 while ($row=$this->io_sql->fetch_row($rs_data))
			       {
				     $li_temp++;
					 if ($as_proben=='P')
						{
						  $ls_codprovben = trim($row["cod_pro"]);
						  $ls_nomproben  = $row["nompro"];
						}
					 else
						{
						  $ls_codprovben = trim($row["ced_bene"]);
						  $ls_nomproben  = $row["nombene"].', '.$row["apebene"];
						}
					$ls_numsol    = trim($row["numsol"]);
				// BUSCA EL MONTO REAL DE LA ORDEN DE PAGO  INCLUYENDO LOS MONTOS DE LAS DEDUCCIONES //
				//$this->uf_select_solcxp_montorden($ls_codemp,$ls_numsol,$ls_montoneto,$ls_montoret);
				//$total=$ls_montoneto+$ls_montoret;
				//print "Monto neto: ".$ls_montoneto." Monto Deducciones: ".$ls_montoret."Total: ".$total;
				//$montototaldeducciones	=$this->uf_select_solcxp_montorden($ls_codemp,$ls_numsol,"D");
				//////////////////////////////////////////////////////////////////////////////////////

					 $ls_consol	   = $row["consol"];
					 $ldec_monsol  = $row["monsol"]; //ANTES
					//$ldec_monsol  = $total;
					 $ls_codban	   = $row["codban"];
					 $ls_ctaban    = $row["ctaban"];
					 $ls_codfuefin = $row["codfuefin"];
					 $ldec_montocancelado = $this->uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban);
					 $ai_montonotas=0;
					 $lb_valido=$this->uf_load_notas_asociadas($ls_codemp,$ls_numsol,&$ai_montonotas);
					 $ldec_montopendiente  = ($ldec_monsol-$ldec_montocancelado)+$ai_montonotas;
					 $object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected('".$li_temp."');  >";
					 $object[$li_temp][2]  = "<input type=text name=txtnumsol".$li_temp." id=txtnumsol".$li_temp."  value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					 $object[$li_temp][3]  = "<input type=text name=txtconsol".$li_temp." value='".$ls_consol."' title='".$ls_consol."' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
					 $object[$li_temp][4]  = "<input type=text name=txtmonsol".$li_temp." value='".number_format($ldec_monsol,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
					 $object[$li_temp][5]  = "<input type=text name=txtmontopendiente".$li_temp."  value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
					 $object[$li_temp][6]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); onKeyPress=\"return(currencyFormat(this,'.',',',event));return keyRestrict(event,'1234567890,');\"  style=text-align:right size=18 maxlength=18>".
											 "<input type=hidden  name=txtcodfuefin".$li_temp."  id=txtcodfuefin".$li_temp."  value='".$ls_codfuefin."'>";
			       }
			 if ($li_temp==0)
			    {
				  $li_temp=1;
				  $object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected('".$li_temp."');  >";
				  $object[$li_temp][2]  = "<input type=text name=txtnumsol".$li_temp." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				  $object[$li_temp][3]  = "<input type=text name=txtconsol".$li_temp." value='' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
				  $object[$li_temp][4]  = "<input type=text name=txtmonsol".$li_temp." value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
				  $object[$li_temp][5]  = "<input type=text name=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";				
				  $object[$li_temp][6]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=18 maxlength=18>".
					  				      "<input type=hidden  name=txtcodfuefin".$li_temp."  id=txtcodfuefin".$li_temp."  value=''>";
				}
			 $this->io_sql->free_result($rs_data);
		   }
		$li_rows=$li_temp;
	}//Fin de uf_cargar_programaciones

	function uf_select_solcxp_montorden($ls_codemp,$ls_numsol,&$ls_montoneto,&$ls_montoret)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	uf_select_solcxp_montorden
	//			codemp, numorden y tipo: T=Total Orden o D=Total Deducciones
	// Access:			public
	//	Returns:			Decimal--- Valor decimal con el monto que ha sido cancelado o abonado para la solicitud
	//	Description:	Funcion que suma los montos cancelados o abonados para cada solicitud
	//////////////////////////////////////////////////////////////////////////////
		$valido=true;
		$ls_sql="SELECT SUM(rd.montotdoc) as monto, SUM(rd.mondeddoc) as deducciones, SUM(rd.moncardoc) as iva
				 FROM   cxp_rd rd, cxp_dt_solicitudes dt, cxp_solicitudes cxp
				 WHERE  cxp.codemp='".$ls_codemp."' AND cxp.numsol='".$ls_numsol.
				"' AND cxp.codemp=dt.codemp AND cxp.numsol=dt.numsol ".
				" AND dt.codemp=rd.codemp AND dt.numrecdoc=rd.numrecdoc AND dt.ced_bene=rd.ced_bene AND dt.cod_pro=rd.cod_pro";
		//print "Tipo: ".$tipo." ".$ls_sql;
	
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_montoneto=$row["monto"];
				$ls_montoret =$row["deducciones"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $valido;
	
	}//Fin de uf_select_solcxp_montoorden

	
	function uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	uf_select_solcxp_montocancelado
	// Access:			public
	//	Returns:			Decimal--- Valor decimal con el monto que ha sido cancelado o abonado para la solicitud
	//	Description:	Funcion que suma los montos cancelados o abonados para cada solicitud
	//////////////////////////////////////////////////////////////////////////////
		
		$ls_sql="SELECT sum(monto) as monto
				 FROM   cxp_sol_banco 
				 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND estmov<>'A' AND estmov<>'O'";
	
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_montocancelado=$row["monto"];
			}
			else
			{
				$ldec_montocancelado=0;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ldec_montocancelado;
	
	}//Fin de uf_select_solcxp_montocancelado
	
	function uf_load_notas_asociadas($as_codemp,$as_numsol,&$ai_montonotas)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_notas_asociadas
		//	          Access:  public
		//	        Arguments  as_codemp //  C�digo de la Empresa.
		//                     as_numsol //  N�mero de Identificaci�n de la Solicitud de Pago.
		//                     ai_montonotas //  monto de las Notas de D�bito y Cr�dito.
		//	         Returns:  lb_valido.
		//	     Description:  Funci�n que se encarga de buscar las notas de debito y cr�dito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Miguel Palencia
		// Fecha de Creaci�n:  26/09/2007       Fecha �ltima Actualizaci�n:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ai_montonotas=0;
		$ls_sql= "SELECT SUM(CASE cxp_sol_dc.codope WHEN 'NC' THEN (-1*cxp_sol_dc.monto) ".
			   "                                 			ELSE (cxp_sol_dc.monto) END) as total ".
			   "  FROM cxp_dt_solicitudes, cxp_sol_dc ".
			   " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			   "   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_sol_dc.estnotadc= 'C' ".
			   "   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
			   "   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
			   "   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
			   "   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
			   "   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
			   "   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_notas_asociadas".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_montonotas=$row["total"];
			}
		}
		return $lb_valido;
	}	
	
	function uf_select_ctaprovbene($as_provbene,$as_codprobene,$as_codban,$as_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	  uf_select_catprovben
	// Access:		  public
	//	Returns:	  String--- Retorno la cuenta contable del proveedor o beneficiario y como parametro de referenica el banco y la cuenta de banco del mismo
	//	Description:  Funcion que busca el banco, la cuenta de banbco y la cuenta contable del proveedor o beneficiario.
	//////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->dat["codemp"];
		if($as_provbene=='P')
		{
			
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_proveedor 
					 WHERE  codemp='".$ls_codemp."' AND cod_pro='".$as_codprobene."'";
		}
		else
		{
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_beneficiario 
					 WHERE  codemp='".$ls_codemp."' AND ced_bene='".$as_codprobene."'";
		}	
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codban=$row["codban"];
				$as_ctaban=$row["ctaban"];
				$ls_cuenta_scg=$row["sc_cuenta"];
			}
			else
			{
				$ls_cuenta_scg="";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_cuenta_scg;
	
	}//Fin de uf_select_ctaprovbene

	function uf_actualizar_estatus_ch($ls_codban,$ls_ctaban,$ls_numche,$ls_numchequera)
	{   
	  $lb_valido = true;
	  if (!empty($ls_numche) && !empty($ls_numchequera))
		 {
           $ls_sql = "UPDATE scb_cheques 
			             SET estche=1
					   WHERE codemp='".$this->ls_codemp."'
					     AND codban='".$ls_codban."' 
						 AND ctaban='".$ls_ctaban."' 
						 AND numche='".$ls_numche."' 
						 AND numchequera='".$ls_numchequera."'";
		   $rs_data = $this->io_sql->execute($ls_sql);
		   if ($rs_data===false)
			  {
				$this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
				$lb_valido = false;					
			  }
		 }
      return $lb_valido;	
	}
	
	function uf_procesar_emision_chq($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estdoc)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_procesar_emision_scq
	// Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se encarga de guardar los detalles d ela emision de cheque
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->dat["codemp"];

		$ls_sql="INSERT INTO cxp_sol_banco(codemp,codban,ctaban,numdoc,codope,numsol,estmov,monto)
		 		 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_numsol."','".$ls_estmov."',".$ldec_monto.")";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en insert cxp_sol_banco,".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;	
		}
		else
		{
			$lb_valido=true;
			if($ls_estdoc=='C')
			{
				$ls_sql="UPDATE scb_prog_pago
						 SET    estmov = '".$ls_estmov."'
						 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
				$li_result=$this->io_sql->execute($ls_sql);
				if(($li_result===false))
				{
					$lb_valido=false;
					$this->is_msg_error="Error en actualizar scb_prog_pago, ".$this->fun->uf_convertirmsg($this->io_sql->message);	
					print $this->is_msg_error;					
				}
				else
				{
					$lb_valido=true;
				}				
			}				
		}
		return $lb_valido;	
	}//Fin de  uf_procesar_emision_chq
	
	function uf_buscar_dt_cxpspg($as_numsol)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_buscar_dt_cxpspg
	// 	Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se buscar el detalle presupuestario de una solicitud de pago 
	//////////////////////////////////////////////////////////////////////////////
		
		
		$li_row=0;
		$lb_valido=false;
		$aa_dt_cxpspg=array();

		$ls_codemp=$this->dat["codemp"];
		
		$ls_sql="SELECT numsol, numdoc, monto as montochq 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numsol ='".$as_numsol."' AND 
				 (estmov='N' OR estmov='C')";
	   
		$rs_cheques=$this->io_sql->select($ls_sql);	

		if($rs_cheques===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_cheques))
			{				
				$li_row=$li_row+1;
				$ls_cheque=$row["numdoc"];
				$ls_numsol=$row["numsol"];
				$ldec_montochq=$row["montochq"];
				
				$ls_sql="SELECT codestpro, spg_cuenta, sum(monto) as monto
						 FROM scb_movbco_spg
		    			 WHERE codemp='".$ls_codemp."' AND procede_doc='CXPSOP' AND numdoc='".$ls_cheque."' AND documento ='".$ls_numsol."' 
						 GROUP BY codestpro, spg_cuenta ";	
				$rs_dt_spgchq=	$this->io_sql_aux->select($ls_sql);	

				if($rs_dt_spgchq===false)
				{
					$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql_aux->message);
					print $this->is_msg_error;	
					$lb_valido=false;		
				}
				else
				{
					while($row=$this->io_sql_aux->fetch_row($rs_dt_spgchq))
					{
						$ls_codestpro1=substr($row["codestpro"],0,20);
						$ls_codestpro2=substr($row["codestpro"],20,6);
						$ls_codestpro3=substr($row["codestpro"],26,3);
						$ls_codestpro4=substr($row["codestpro"],29,2);	
						$ls_codestpro5=substr($row["codestpro"],31,2);
						$ls_spgcuenta=$row["spg_cuenta"];	
						$ldec_monto	=$row["monto"];				
						$this->ds_temp->insertRow("codestpro1",$ls_codestpro1);
						$this->ds_temp->insertRow("codestpro2",$ls_codestpro2);
						$this->ds_temp->insertRow("codestpro3",$ls_codestpro3);
						$this->ds_temp->insertRow("codestpro4",$ls_codestpro4);
						$this->ds_temp->insertRow("codestpro5",$ls_codestpro5);
						$this->ds_temp->insertRow("spg_cuenta",$ls_spgcuenta);
						$this->ds_temp->insertRow("monto",$ldec_monto);
					}
				$this->io_sql_aux->free_result($rs_dt_spgchq);
				} 
			}
		}
		if(array_key_exists("codestpro1",$this->ds_temp->data))
		{		  
			if($this->ds_temp->getRowCount("codestpro1")>0)
			{				
				$arr_group[0]="codestpro1";
				$arr_group[1]="codestpro2";
				$arr_group[2]="codestpro3";
				$arr_group[3]="codestpro4";
				$arr_group[4]="codestpro5";
				$arr_group[5]="spg_cuenta";
				$this->ds_temp->group_by($arr_group,array('0'=>"monto"),"monto");
			}			
		}
		$li_row=0;
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND procede='CXPSOP' ".
				"   AND comprobante='".$as_numsol."' ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ".
				" UNION ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOC' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ".
				" UNION  ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOD' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ";
		$rs_dt_cxpspg=	$this->io_sql->select($ls_sql);
		if($rs_dt_cxpspg===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);			
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_dt_cxpspg))
			{
				$li_row=$li_row+1;
				$ls_codestpro1=$row["codestpro1"];
				$aa_dt_cxpspg["codestpro1"][$li_row] = $ls_codestpro1;
				$ls_codestpro2=$row["codestpro2"];
				$aa_dt_cxpspg["codestpro2"][$li_row] = $ls_codestpro2;
				$ls_codestpro3=$row["codestpro3"];
				$aa_dt_cxpspg["codestpro3"][$li_row] = $ls_codestpro3;
				$ls_codestpro4=$row["codestpro4"];
				$aa_dt_cxpspg["codestpro4"][$li_row] = $ls_codestpro4;
				$ls_codestpro5=$row["codestpro5"];
				$aa_dt_cxpspg["codestpro5"][$li_row] = $ls_codestpro5;
				$ls_spg_cuenta=$row["spg_cuenta"];
				$aa_dt_cxpspg["spg_cuenta"][$li_row] = $ls_spg_cuenta;			
				$ldec_monto=$row["monto"];
				$aa_dt_cxpspg["monto"][$li_row]      = $ldec_monto;	
				$ls_descripcion=$row["descripcion"];
				$aa_dt_cxpspg["descripcion"][$li_row]      = $ls_descripcion;
				// AQUI ESTABA FALLANDO YA QUE ME ESTABA RESTANDO 2 VECES CUANDO TENIA UN PAGO ANTERIOR
				/*$li_row_tots=$this->ds_temp->getRowCount("codestpro1");
				if($li_row_tots>0)
				{
					for($li_i=1;$li_i<=$li_row_tots;$li_i++)
					{
						$ls_estpro1=$this->ds_temp->getValue("codestpro1",$li_i);
						$ls_estpro2=$this->ds_temp->getValue("codestpro2",$li_i);
						$ls_estpro3=$this->ds_temp->getValue("codestpro3",$li_i);
						$ls_estpro4=$this->ds_temp->getValue("codestpro4",$li_i);
						$ls_estpro5=$this->ds_temp->getValue("codestpro5",$li_i);
						$ls_cuentaspg=$this->ds_temp->getValue("spg_cuenta",$li_i);
						$ldec_montotmp=$this->ds_temp->getValue("monto",$li_i);
						
						if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg))
						{
							$ldec_new_monto=doubleval($ldec_monto)-doubleval($ldec_montotmp);
							$aa_dt_cxpspg["monto"][$li_row]=$ldec_new_monto;
						}//End if
					}//End For
				}//End if	*/		
			}//End While
			//Asigno la matriz de detalles presupuestarios al datastore.		
			$arr_group[0]="codestpro1";
			$arr_group[1]="codestpro2";
			$arr_group[2]="codestpro3";
			$arr_group[3]="codestpro4";
			$arr_group[4]="codestpro5";
			$arr_group[5]="spg_cuenta";
			//Agrupo el datastore por programaticas y cuentas y sumo el monto
			$this->ds_sol->data=$aa_dt_cxpspg;
			$this->ds_sol->group_by($arr_group,array('0'=>"monto"),"monto");
			$li_row=$this->ds_sol->getRowCount("codestpro1");
			if($li_row>0)
			{
				for($li_j=1;$li_j<=$li_row;$li_j++)
				{
					$ls_codestpro1=$this->ds_sol->getValue("codestpro1",$li_j);
					$ls_codestpro2=$this->ds_sol->getValue("codestpro2",$li_j);
					$ls_codestpro3=$this->ds_sol->getValue("codestpro3",$li_j);
					$ls_codestpro4=$this->ds_sol->getValue("codestpro4",$li_j);
					$ls_codestpro5=$this->ds_temp->getValue("codestpro5",$li_j);
					$ls_spg_cuenta=$this->ds_sol->getValue("spg_cuenta",$li_j);
					$ldec_monto=$this->ds_sol->getValue("monto",$li_j);
					$li_row_tots=$this->ds_temp->getRowCount("codestpro1");
					if($li_row_tots>0)
					{
						for($li_i=1;$li_i<=$li_row_tots;$li_i++)
						{
							$ls_estpro1=$this->ds_temp->getValue("codestpro1",$li_i);
							$ls_estpro2=$this->ds_temp->getValue("codestpro2",$li_i);
							$ls_estpro3=$this->ds_temp->getValue("codestpro3",$li_i);
							$ls_estpro4=$this->ds_temp->getValue("codestpro4",$li_i);
							$ls_estpro5=$this->ds_temp->getValue("codestpro5",$li_i);
							$ls_cuentaspg=$this->ds_temp->getValue("spg_cuenta",$li_i);
							$ldec_montotmp=$this->ds_temp->getValue("monto",$li_i);
							
							if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg))
							{
								$ldec_new_monto=doubleval($ldec_monto)-doubleval($ldec_montotmp);
								$this->ds_sol->updateRow("monto",$ldec_new_monto,$li_j);
							}//End if
						}//End For
					}//End if	
				}
			}			
		}//End if		
	}//Fin uf_buscar_dt_cxpspg.	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fechasolicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol     //  N�mero de Solicitud
		//                 ad_fecemisol  // Fecha de Emision de la Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que obtiene la fecha de la solicitud de pago
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 29/02/2008 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_fecemisol="";
		$ls_sql="SELECT fecemisol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecemisol=$row["fecemisol"];
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ad_fecemisol;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>
