<?php
class tepuy_cxp_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_cxp_class_report($as_path="../../")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sep_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia /Ing. Juniors Fraga
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad=new tepuy_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_sep_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

//////// FUNCION QUE ME LLEVA EL CODIGO DEL CONTRATO ////////////
function uf_obtengo_retencion ($as_sql,$quetraigo)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_contabilizado
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          17/04/2015
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado=false;
	$rs_data=$this->io_sql->select($as_sql);
	if($rs_data===false)
	{
		print "Error en uf_obtengogatos".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			if($quetraigo==='C')
			{
				$li_estado=$la_row["codcmp"];
			}
			else
			{
				$li_estado=$la_row["tipo"];
			}
			
		}		
	}	
	return $li_estado;
}
////////////////////////// FIN DE LA BUSQUEDA DEL NRO DE CODIGO DE LA RETENCION QUE VIENE///////////////////////


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_usuario($as_nomusu)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_numeroinicial
		//		   Access: public
		//		 Argument: $as_nomusu // Codigo de usuario
		//	  Description: Funci�n que busca Nombre y apellido del usuario que aprobo la orden
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 26/02/2008								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT nomusu,apeusu ".
				"  FROM sss_usuarios ".
				" WHERE codemp='".$this->ls_codemp."'".
				" AND codusu='".$as_nomusu."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Buscar Usuario M�TODO->uf_load_usuario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
					$ls_usuario=$row["nomusu"]." ".$row["apeusu"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_usuario;
	}// end function uf_load_usuario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_licencia($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_licencia
		//         Access: public  
		//	    Arguments: as_sql  // Funcion para extraer el numero de licencia
		//    Description: funci�n que busca el numero de Licencia del SAMAT del proveedor
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 16/05/2016									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data=$this->io_sql->select($as_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_licencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_licencia=$row["numlic"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ad_licencia;
	}// end function uf_select_licencia
	//-----------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_contribuyentes_libro_timbrefiscal($as_mes,$as_anio,$as_codret)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contribuyentes_libro_timbrefiscal
		//         Access: public  
		//	    Arguments:  as_mes    // mes del comprobante
		//	    		   as_anio   // a�o del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 25/11/2008									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
			
	/*	$ls_sql="SELECT  scb_cmp_ret.numcom, scb_cmp_ret.nomsujret, scb_cmp_ret.rif, ".
			" scb_dt_cmp_ret.basimp, scb_dt_cmp_ret.numfac,scb_dt_cmp_ret.numcon, ".
			" scb_dt_cmp_ret.iva_ret, scb_dt_cmp_ret.fecfac, scb_dt_cmp_ret.porimp, scb_dt_cmp_ret.numsop, cxp_solicitudes.tipproben, ".
			" scb_dt_cmp_ret.totcmp_con_iva, scb_dt_cmp_ret.totcmp_sin_iva, cxp_solicitudes.fecaprosol, tepuy_deducciones.porded, tepuy_deducciones.codseniat, cxp_rd.numfac as factura ".
			"  FROM scb_cmp_ret,scb_dt_cmp_ret,cxp_solicitudes, tepuy_deducciones, cxp_rd ".
				" WHERE scb_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_cmp_ret.codret='".$as_codret."' ".
				"   AND scb_cmp_ret.fecrep>='".$ld_fechadesde."' ".
				"   AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ".
				" AND scb_dt_cmp_ret.codded=tepuy_deducciones.codded ".
				" AND scb_dt_cmp_ret.codret='".$as_codret."'  AND scb_cmp_ret.codret=scb_dt_cmp_ret.codret ".
				" AND scb_cmp_ret.numcom=scb_dt_cmp_ret.numcom ".
				//" AND scb_dt_cmp_ret.numdoc = cxp_rd.numrecdoc ".
				" AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol GROUP BY scb_cmp_ret.numcom ";
				//"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				//" GROUP BY scb_cmp_ret.numcom ";
				//"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom "; */
		$ls_sql="SELECT  scb_cmp_ret.numcom, scb_cmp_ret.nomsujret, scb_cmp_ret.rif,  ".
			" scb_dt_cmp_ret.basimp, scb_dt_cmp_ret.iva_ret, scb_dt_cmp_ret.fecfac, scb_dt_cmp_ret.numsop, ".
			" scb_dt_cmp_ret.totcmp_con_iva, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp,".
			" cxp_solicitudes.tipproben, cxp_solicitudes.fecaprosol,".
			" tepuy_deducciones.porded, tepuy_deducciones.codseniat, ".
			" cxp_rd.numfac as factura, cxp_rd.numref as control".
			"  FROM scb_cmp_ret,scb_dt_cmp_ret,cxp_solicitudes,tepuy_deducciones, cxp_rd ".
			"  WHERE scb_cmp_ret.codemp='".$this->ls_codemp."' ".
			"   AND scb_cmp_ret.codret='".$as_codret."' ".
			"   AND scb_cmp_ret.fecrep>='".$ld_fechadesde."' ".
			"   AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ".
			"   AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp ".
			"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
			"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom".
			"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol".
			"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			"   AND scb_dt_cmp_ret.codded=tepuy_deducciones.codded ".
			"   AND scb_dt_cmp_ret.numdoc=cxp_rd.numrecdoc ".
			" ORDER BY scb_cmp_ret.numcom ";
		//print $ls_sql;
		//die();



		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_contribuyentes_libro_timbrefiscal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/*if($rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
			*/
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_contribuyentes_libro_timbrefiscal


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de la una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,cxp_solicitudes.monsolaux,cxp_solicitudes.usuaprosol, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE '-' END ) AS rifpro, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.dirpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.dirbene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE '-' END ) AS dirproben, ".
				"       (SELECT denfuefin".
				"		   FROM tepuy_fuentefinanciamiento".
				"         WHERE tepuy_fuentefinanciamiento.codemp=cxp_solicitudes.codemp".
				"           AND tepuy_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin".
				"  FROM cxp_solicitudes ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud_tipodocumento($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud_tipodocumento
		//         Access: public
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de la una solicitud de pago y los tipos de documento que presentan
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT(cxp_solicitudes.numsol), cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,cxp_rd.codtipdoc,".
				"       cxp_documento.dentipdoc,cxp_solicitudes.monsolaux,".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                      				  	 FROM rpc_proveedor ".
				"                                       				WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       				WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                       				 FROM rpc_beneficiario ".
				"                                       				WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                       				  AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       				ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        				 FROM rpc_proveedor ".
				"                                       				WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       				WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                       				 FROM rpc_beneficiario ".
				"                                       				WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       				ELSE '-' END ) AS rifpro, ".
				"       (SELECT denfuefin".
				"		   FROM tepuy_fuentefinanciamiento".
				"         WHERE tepuy_fuentefinanciamiento.codemp=cxp_solicitudes.codemp".
				"           AND tepuy_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd,cxp_documento ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene".
				"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc";
				//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_rec_doc_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_rec_doc_solicitud
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de recepciones de documentos asociadas a  una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_rec = new class_datastore();
		$ls_sql="SELECT cxp_dt_solicitudes.numsol,cxp_rd.numrecdoc, cxp_documento.dentipdoc,".
				"       cxp_rd.montotdoc,cxp_rd.mondeddoc, cxp_rd.moncardoc,cxp_rd.fecemidoc,cxp_rd.procede,".
				"       (SELECT MAX(procede_doc)".
				"          FROM cxp_rd_scg".
				"         WHERE cxp_rd_scg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_scg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_scg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_scg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_scg.cod_pro=cxp_rd.cod_pro ".
				"           AND cxp_rd_scg.debhab='D') AS procede_docscg,".
				"       (SELECT MAX(procede_doc)".
				"          FROM cxp_rd_spg".
				"         WHERE cxp_rd_spg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro) AS procede_docspg,".
				"       (SELECT MAX(numdoccom)".
				"          FROM cxp_rd_scg".
				"         WHERE cxp_rd_scg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_scg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_scg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_scg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_scg.cod_pro=cxp_rd.cod_pro ".
				"           AND cxp_rd_scg.debhab='D') AS numdoccomscg,".
				"       (SELECT MAX(numdoccom)".
				"          FROM cxp_rd_spg".
				"         WHERE cxp_rd_spg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro) AS numdoccomspg".
				"  FROM cxp_dt_solicitudes, cxp_solicitudes, cxp_rd, cxp_documento".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_rd.codemp=cxp_solicitudes.codemp".
				"   AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_documento.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene".
				" GROUP BY cxp_rd.codemp, cxp_dt_solicitudes.numsol,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene, cxp_documento.dentipdoc,".
				"       cxp_rd.montotdoc,cxp_rd.mondeddoc, cxp_rd.moncardoc,cxp_rd.fecemidoc,cxp_rd.procede".
				" ORDER BY cxp_rd.numrecdoc ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_rec_doc_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_rec->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_rec_doc_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_spg($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_spg
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 20/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_spg = new class_datastore();
		$ls_sql="SELECT cxp_dt_solicitudes.numsol,cxp_rd_spg.codestpro,cxp_rd_spg.spg_cuenta, sum(cxp_rd_spg.monto) AS monto ,max(spg_cuentas.denominacion) as denominacion".
				"  FROM cxp_rd_spg,cxp_dt_solicitudes,spg_cuentas".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_rd_spg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd_spg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd_spg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd_spg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd_spg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND spg_cuentas.codemp=cxp_rd_spg.codemp".
				"   AND spg_cuentas.spg_cuenta=cxp_rd_spg.spg_cuenta".
				"   AND SUBSTR(cxp_rd_spg.codestpro,1,20)=spg_cuentas.codestpro1 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,21,6)=spg_cuentas.codestpro2 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,27,3)=spg_cuentas.codestpro3 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,30,2)=spg_cuentas.codestpro4 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,32,2)=spg_cuentas.codestpro5 ".
				" GROUP BY cxp_dt_solicitudes.numsol,cxp_rd_spg.codestpro,cxp_rd_spg.spg_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_detalle_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_spg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_scg($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_scg
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 20/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_scg = new class_datastore();
		$ls_sql="SELECT cxp_rd_scg.sc_cuenta,cxp_rd_scg.monto,cxp_rd_scg.debhab,scg_cuentas.denominacion".
				" FROM cxp_rd_scg,cxp_dt_solicitudes,scg_cuentas".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_rd_scg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd_scg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd_scg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd_scg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd_scg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND scg_cuentas.codemp=cxp_rd_scg.codemp".
				"   AND scg_cuentas.sc_cuenta=cxp_rd_scg.sc_cuenta".
				" ORDER BY cxp_rd_scg.debhab";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_detalle_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_scg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de la una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 21/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.*,cxp_documento.dentipdoc, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_rd,cxp_documento ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."' ".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_rd.cod_pro='".$as_codpro."' ".
				"   AND cxp_rd.ced_bene='".$as_cedben."' ".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------
//////// FUNCION QUE ME LLEVA la denominacion de la cuenta////////////
	function uf_obtengo_denominacion ($as_sql)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_obtengo_retencion
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          17/04/2015
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado="";
	$rs_data=$this->io_sql->select($as_sql);
	if($rs_data===false)
	{
		print "Error en uf_obtengo_denominacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$li_estado=$la_row["denominacion"];
		}		
	}	
	return $li_estado;
}
////////////////////////// FIN DE LA BUSQUEDA DE LA DENOMINACION DE LA CUENTA///////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_recepcionspg($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_recepcionspg
		//         Access: public 
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n presupuestaria asociada a una recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 22/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_spg = new class_datastore();
		$ls_sql="SELECT codestpro,numrecdoc,spg_cuenta,monto,numdoccom ".
				" FROM cxp_rd_spg".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedben."' ";
			//	"   AND SUBSTR(a.codestpro,0,20)=b.codestpro1 AND SUBSTR(a.codestpro,20,6)=b.codestpro2 ".
			//	"   AND SUBSTR(a.codestpro,26,3)=b.codestpro3 AND SUBSTR(a.codestpro,29,2)=b.codestpro4 ".
			//	"   AND SUBSTR(a.codestpro,31,2)=b.codestpro5 AND a.spg_cuenta=b.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_detalle_recepcionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_spg->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_recepcionspg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_recepcionscg($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_recepcionscg
		//         Access: public   
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n presupuestaria asociada a una Recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 22/05/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_scg = new class_datastore();
		$ls_sql="SELECT a.sc_cuenta,a.numrecdoc,a.debhab,a.monto,a.numdoccom,b.denominacion".
				" FROM cxp_rd_scg a, scg_cuentas b".
				" WHERE a.codemp='".$this->ls_codemp."' ".
				"   AND a.numrecdoc='".$as_numrecdoc."' ".
				"   AND a.codtipdoc='".$as_codtipdoc."' ".
				"   AND a.cod_pro='".$as_codpro."' ".
				"   AND a.ced_bene='".$as_cedben."' ".
				"   AND a.sc_cuenta=b.sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_detalle_recepcionscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_scg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_recepcionscg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepciones($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas,$as_codtipdoc,
								   $ai_registrada,$ai_anulada,$ai_procesada,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 as_codtipdoc     // Codigo de Tipo de Documento
		//                 as_registrada    // Estatus de la Recepcion Registrada
		//                 ai_anulada       // Estatus de la Recepcion Anulada
		//                 ai_procesada     // Estatus de la Recepcion Procesada
		//                 ai_orden         // Orden de los Datos en el Reporte Numero/Fecha
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_rd.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}

		if(!empty($as_codtipdoc) &&($as_codtipdoc!="-"))
		{
			$as_codtipdoc=substr($as_codtipdoc,0,5);
			$ls_criterio= $ls_criterio."   AND cxp_rd.codtipdoc='".$as_codtipdoc."'";
		}
		
		if(($ai_registrada==1)||($ai_procesada==1)||($ai_anulada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='R'";
					$lb_anterior=true;
				}
			}
			if($ai_procesada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='A'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por C�digo de personal
				$ls_orden="cxp_rd.numrecdoc ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="cxp_rd.fecregdoc ";
				break;

		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.numrecdoc,cxp_rd.fecemidoc,cxp_rd.fecregdoc,MAX(cxp_rd.montotdoc) AS montotdoc,".
				"       MAX(cxp_rd.mondeddoc) AS mondeddoc,MAX(cxp_rd.moncardoc) AS moncardoc,MAX(cxp_documento.dentipdoc) AS dentipdoc, ".
				"       (CASE MAX(tipproben) WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_rd,cxp_documento ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_rd.codemp,cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.numrecdoc,cxp_rd.fecemidoc,cxp_rd.fecregdoc".
				" ORDER BY ".$ls_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecreghas."'";
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT MAX(cxp_solicitudes.tipproben) AS tipproben,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes,cxp_historico_solicitud ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"   AND cxp_historico_solicitud.numsol=cxp_solicitudes.numsol".
				"   AND cxp_historico_solicitud.estprodoc='C'".
				"   AND cxp_solicitudes.estprosol<>'A'".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_solicitudes.codemp,cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro,cxp_solicitudes.tipproben".
				" ORDER BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_previas($as_tipproben,$as_codpro,$as_cedbene,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes_previas
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$this->ds_solprevias= new class_datastore();
		$as_tipproben="%".$as_tipproben."%";
		$ls_sql=" SELECT  cxp_solicitudes.numsol, cxp_solicitudes.monsol, cxp_historico_solicitud.estprodoc AS estatus, cxp_historico_solicitud.fecha".
				"   FROM  cxp_solicitudes, cxp_historico_solicitud ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND  cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_solicitudes.tipproben LIKE'".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND (cxp_historico_solicitud.estprodoc='C' OR cxp_historico_solicitud.estprodoc='A')".
				"    AND cxp_historico_solicitud.fecha < '".$ad_fecregdes."' ".
				"  ORDER  BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro";
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudes_previas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solprevias->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes_previas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_pagosprevios($as_tipproben,$as_codpro,$as_cedbene,$ad_fecregdes,$ad_fecreghas,&$ad_totpagosprevios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_pagosprevios
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$ls_sql=" SELECT COALESCE(SUM(cxp_solicitudes.monsol),0) AS pagos                                                                                    ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"    AND cxp_historico_solicitud.fecha <'".$ad_fecregdes."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_pagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_totpagosprevios=$row["pagos"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_pagosprevios
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalles_pagosprevios($as_numsol,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalles_pagosprevios
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$this->ds_detpagosprevios = new class_datastore();
		$ls_sql=" SELECT cxp_historico_solicitud.fecha, cxp_sol_banco.codban, cxp_sol_banco.ctaban,".
				"        cxp_sol_banco.numdoc, cxp_sol_banco.monto,scb_banco.nomban".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco,scb_banco ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_sol_banco.codemp=scb_banco.codemp".
				"    AND cxp_sol_banco.codban=scb_banco.codban".
				"	 AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"	 AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_detalles_pagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detpagosprevios->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_detalles_pagosprevios
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesactuales($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesactuales
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_solactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol, cxp_solicitudes.monsol,cxp_solicitudes.consol, cxp_historico_solicitud.estprodoc, cxp_historico_solicitud.fecha ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND (cxp_historico_solicitud.estprodoc='C' OR cxp_historico_solicitud.estprodoc='A') ".
				" ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudesactuales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesactuales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_pagosolicitudes($as_numsol,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_pagosolicitudes
		//         Access: public  
		//	    Arguments: as_numsol    // Numero de Solicitud de Pago
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_detpagsolact = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_historico_solicitud.fecha, cxp_sol_banco.codban, cxp_sol_banco.ctaban, cxp_sol_banco.numdoc,".
				"	 	 cxp_sol_banco.monto,scb_banco.nomban,cxp_sol_banco.estmov,cxp_sol_banco.codope ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco,scb_banco ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_sol_banco.codemp=scb_banco.codemp".
				"    AND cxp_sol_banco.codban=scb_banco.codban".
				"	 AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"	 AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ".$ls_criterio." ";
		//		print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_pagosolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detpagsolact->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_pagosolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_otros_creditos($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_otros_creditos
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codcar AS codigo, dencar AS denominacion, codestpro, spg_cuenta, porcar, formula".
				"  FROM tepuy_cargos".
				" WHERE codemp='".$this->ls_codemp."'".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_otros_creditos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_otros_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_deducciones($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_deducciones
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codded AS codigo, dended AS denominacion, sc_cuenta, porded, monded, formula".
				"  FROM tepuy_deducciones".
				" WHERE codemp='".$this->ls_codemp."'".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_retenciones_buscar($as_codded,$as_coddedhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_retenciones_buscar
		//	    Arguments: as_codded    // Parametro para iniciar el filtro de las deducciones a filtrar 
		//	    Arguments: as_coddedhas // Parametro para terminar el filtro de las deducciones
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funcion que busca las deducciones a filtrar
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codded AS codigo, dended AS denominacion, sc_cuenta, porded, monded, formula, islr, iva, estretmun, otras, retaposol, emitecomp ".
				"  FROM tepuy_deducciones".
				" WHERE codemp='".$this->ls_codemp."'".
				" AND codded >='".$as_codded."' AND codded<='".$as_coddedhas."' ".
				" ORDER BY codded";
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_retenciones_buscar
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_documentos($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_documentos
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codtipdoc AS codigo, dentipdoc AS denominacion, estcon, estpre".
				"  FROM cxp_documento".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_documentos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_documentos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesf1($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecemides,$ad_fecemihas,$ai_emitida,
									 $ai_contabilizada,$ai_anulada,$ai_propago,$ai_pagada)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecemides     // Fecha de Emision Desde
		//                 ad_fecemihas     // Fecha de Emision Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}
		if(($ai_emitida==1)||($ai_contabilizada==1)||($ai_anulada==1)||($ai_propago==1)||($ai_pagada==1))
		{
			$lb_anterior=false;
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='E'";
					$lb_anterior=true;
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='A'";
				}
			}
			if($ai_propago==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='S'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='S'";
				}
			}
			if($ai_pagada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='P'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='P'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"    cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,cxp_solicitudes.consol,cxp_dt_solicitudes.numrecdoc, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes, cxp_dt_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				" AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol ".
				"   ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.numsol";
				//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes
	//-------------------------------------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesf1A4($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecemides,$ad_fecemihas,$ai_emitida,
									 $ai_contabilizada,$ai_anulada,$ai_propago,$ai_pagada,$as_codprodes,$as_codprohas,$as_cuentades,$as_cuentahas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecemides     // Fecha de Emision Desde
		//                 ad_fecemihas     // Fecha de Emision Hasta
		//				   $as_codprodes	// Categoria programatica (inicio de busqueda)
		//				   $as_codprohas	// categoria programatica (fin de busqueda)
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 26/01/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$otro_filtro=" ";
		$otro_filtro1=" ";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}

	// Filtro por categoria programatica //
		if(!empty($as_codprodes))
		{
			$as_codprodes=$as_codprodes."0000";
			$ls_criterio= $ls_criterio."   AND cxp_rd_spg.codestpro>='".$as_codprodes."'";
			$otro_filtro=" AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ";
		}
		if(!empty($as_codprohas))
		{
			$as_codprohas=$as_codprohas."0000";
			$ls_criterio= $ls_criterio."   AND cxp_rd_spg.codestpro<='".$as_codprohas."'";
			$otro_filtro=" AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ";
		}
	// Fin filtro por categoria programatica //

		// Cuentas Presupuestarias //
		if(!empty($as_cuentades))
		{
			$as_cuentades=$as_cuentades;
			$ls_criterio= $ls_criterio."   AND cxp_rd_spg.spg_cuenta>='".$as_cuentades."'";
			$otro_filtro1=" AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ";
		}
		if(!empty($as_cuentahas))
		{
			$as_cuentahas=$as_cuentahas;
			$ls_criterio= $ls_criterio."   AND cxp_rd_spg.spg_cuenta<='".$as_cuentahas."'";
			$otro_filtro1=" AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ";
		}
		//////// FIN DE CTAS PRESUPUESTARIAS ////////
		
		if(($ai_emitida==1)||($ai_contabilizada==1)||($ai_anulada==1)||($ai_propago==1)||($ai_pagada==1))
		{
			$lb_anterior=false;
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='E'";
					$lb_anterior=true;
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='A'";
				}
			}
			if($ai_propago==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='S'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='S'";
				}
			}
			if($ai_pagada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='P'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='P'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"    cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,cxp_solicitudes.consol,cxp_rd.mondeddoc,cxp_dt_solicitudes.numrecdoc, ".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS rifproben ".
				"  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd_spg, cxp_rd ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				" AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol ".
				" AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc ".
				"   ".$ls_criterio." ".$otro_filtro.$otro_filtro1.
				" ORDER BY cxp_solicitudes.numsol";
				//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudesF1A4 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesf1A4
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuenta_gasto($as_ficha,$as_cedbene,$as_codpro,$as_codprodes,$as_codprohas,&$valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cuenta_gasto
		//         Access: public 
		//	    Arguments: as_ficha    ---> Numero de Ficha registrada
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca las cuenats de gastos de la  orden de compra para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 25/01/2017
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$valido=true;
		$ls_criterio=" ";
		// Filtro por categoria programatica //
		if(!empty($as_codprodes))
		{
			$as_codprodes=$as_codprodes."0000";
			$ls_criterio= $ls_criterio."   AND codestpro>='".$as_codprodes."'";
		}
		if(!empty($as_codprohas))
		{
			$as_codprohas=$as_codprohas."0000";
			$ls_criterio= $ls_criterio."   AND codestpro<='".$as_codprohas."'";
		}
		// Fin filtro por categoria programatica //

		$ls_sql=" SELECT numrecdoc, codestpro, spg_cuenta, monto      ".
			/*	"  (SELECT MAX(denominacion) FROM spg_cuentas ".
				"	WHERE spg_cuentas.codestpro1=SUBSTRING(codestpro,1,20) AND ".
				"		  spg_cuentas.codestpro2=SUBSTRING(codestpro,21,6) AND ".
				"		  spg_cuentas.codestpro3=SUBSTRING(codestpro,27,3) AND ".
				"		  spg_cuentas.spg_cuenta=spg_cuenta) AS dencuenta ".*/
				" FROM   cxp_rd_spg ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numrecdoc='".$as_ficha."'   AND      ".
				"        ced_bene='".$as_cedbene."'  AND       ".
				"        cod_pro='".$as_codpro."'         ".$ls_criterio." ".
				" ORDER BY spg_cuenta ASC";
		//print $ls_sql; die();   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_cuenta_gasto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$valido=false;
		}
		return $rs_data;
	}// end function uf_select_cuenta_gasto
	//--------------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_denominacionspg($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacionspg
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE  codemp='".$this->ls_codemp."'  AND  spg_cuenta='".$as_cuenta."' AND ".
				 "	spg_cuentas.codestpro1='".$as_codestpro1."' AND ".
				 "	spg_cuentas.codestpro2='".$as_codestpro2."' AND ".
				 "	spg_cuentas.codestpro3='".$as_codestpro3."' AND ".
				 "	spg_cuentas.codestpro4='".$as_codestpro4."' AND ".
				 "	spg_cuentas.codestpro5='".$as_codestpro5."' ";
		//print $ls_sql; die();
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_denominacionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["denominacion"];     
				$lb_valido=true;
			 }	
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
   //---------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudf2($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudf2
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de una solicitud de pago en especifico
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"        cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,cxp_solicitudes.consol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudf2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudf2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_cxp($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_cxp
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las retenciones de una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 04/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $ls_gestor = $_SESSION["ls_gestor"];
	   $lb_valido=true;
	   switch ($ls_gestor){
		case 'MYSQL':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
	   }
	   $ls_sql="SELECT cxp_rd.numrecdoc AS numdoc, cxp_rd.numref, cxp_rd.fecemidoc, cxp_rd.tipproben, rpc_proveedor.nitpro AS nit, ".
	   		   "	   rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, rpc_proveedor.rifpro, ".
			   $ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, rpc_beneficiario.telbene, ".
			   "	   rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene,cxp_solicitudes.numsol,".
			   "	   cxp_solicitudes.consol, cxp_rd.montotdoc, cxp_rd_deducciones.monret AS retenido, ".
			   "	   COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol) AS monobjret, ".
			   "	   tepuy_deducciones.porded AS porcentaje,tepuy_deducciones.dended AS dended,".
                       "      tepuy_deducciones.monded, cxp_rd.mondeddoc,		                        ".
                       "	   (SELECT cxp_sol_banco.numdoc".
			   "		  FROM cxp_sol_banco".
			   "		 WHERE cxp_sol_banco.estmov<>'A' 
			               AND cxp_sol_banco.estmov<>'O' 
			               AND cxp_solicitudes.codemp=cxp_sol_banco.codemp".
			   "           AND cxp_solicitudes.numsol=cxp_sol_banco.numsol) AS cheque".
			   "  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, tepuy_deducciones, ".
			   "       rpc_beneficiario, rpc_proveedor ".
			   " WHERE tepuy_deducciones.islr=1 ".
			   "   AND tepuy_deducciones.iva=0 ".
			   "   AND tepuy_deducciones.estretmun=0 ".
			   "   AND cxp_solicitudes.estprosol<>'A' ".
			   "   AND cxp_solicitudes.codemp='".$this->ls_codemp."' ".
			   "   AND cxp_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp ".
			   "   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol ".
			   "   AND cxp_solicitudes.cod_pro=cxp_dt_solicitudes.cod_pro ".
			   "   AND cxp_solicitudes.ced_bene=cxp_dt_solicitudes.ced_bene ".
			   "   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp ".
			   "   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro ".
			   "   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene ".
			   "   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc ".
			   "   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc ".
			   "   AND cxp_rd.codemp=cxp_rd_deducciones.codemp ".
			   "   AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro ".
			   "   AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene ".
			   "   AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc ".
			   "   AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc ".
			   "   AND tepuy_deducciones.codemp=cxp_rd_deducciones.codemp ".
			   "   AND tepuy_deducciones.codded=cxp_rd_deducciones.codded ".
			   "   AND rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
			   "   AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene ".
			   "   AND rpc_proveedor.codemp=cxp_solicitudes.codemp ".
			   "   AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro ".
			   " ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);//print $ls_sql.'<br>';
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesislr_cxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesislr_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_scb($as_numdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_scb
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las retenciones de banco
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 07/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $ls_gestor = $_SESSION["ls_gestor"];
	   $lb_valido=true;
	   switch ($ls_gestor)
	   {
		case 'MYSQL':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
	   }
	   $ls_sql="SELECT scb_movbco.numdoc, scb_movbco.chevau AS numref, scb_movbco.fecmov AS fecemidoc, scb_movbco.tipo_destino AS tipproben, ".
	   		   "	   rpc_proveedor.nitpro AS nit, rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, ".
			   "	   rpc_proveedor.rifpro, ".$ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, ".
			   "	   rpc_beneficiario.telbene, rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene, scb_movbco.conmov AS consol,".
               "       scb_movbco.monto AS montotdoc, scb_movbco.monret AS retenido, scb_movbco.monobjret AS monobjret,'' AS numsol,        ".
			   "       tepuy_deducciones.porded AS porcentaje,tepuy_deducciones.dended AS dended,scb_movbco.numdoc AS cheque  ".
			   "  FROM scb_movbco, scb_movbco_scg, tepuy_deducciones, rpc_proveedor, rpc_beneficiario ".
			   " WHERE scb_movbco.codemp = '".$this->ls_codemp."' ".
			   "   AND scb_movbco.numdoc = '".$as_numdoc."' ".
			   "   AND scb_movbco.estmov<>'O' ".
			   "   AND scb_movbco.estmov<>'A' ".
			   "   AND tepuy_deducciones.islr = 1 ".
			   "   AND tepuy_deducciones.iva = 0 ".
			   "   AND tepuy_deducciones.estretmun = 0 ".
			   "   AND scb_movbco.codemp = scb_movbco_scg.codemp ".
			   "   AND scb_movbco.codban = scb_movbco_scg.codban ".
			   "   AND scb_movbco.ctaban = scb_movbco_scg.ctaban ".
			   "   AND scb_movbco.numdoc = scb_movbco_scg.numdoc ".
			   "   AND scb_movbco.codope = scb_movbco_scg.codope ".
			   "   AND scb_movbco.estmov = scb_movbco_scg.estmov ".
			   "   AND scb_movbco_scg.codemp = tepuy_deducciones.codemp ".
			   "   AND scb_movbco_scg.codded = tepuy_deducciones.codded ".
			   "   AND scb_movbco.codemp = rpc_proveedor.codemp ".
			   "   AND scb_movbco.cod_pro = rpc_proveedor.cod_pro ".
			   "   AND scb_movbco.codemp = rpc_beneficiario.codemp ".
			   "   AND scb_movbco.ced_bene = rpc_beneficiario.ced_bene ".
			   "  ORDER BY scb_movbco.numdoc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesislr_scb ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesislr_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesgeneral($ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesgeneral
		//         Access: public  
		//	    Arguments: ad_fecdes     // Rango de fecha desde
		//	    		   ad_fechas     // Rango de fecha hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que se encarga de extraer todas aquellas solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 04/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_gestor = $_SESSION["ls_gestor"];
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		$lb_valido=true;
		switch ($ls_gestor){
		case 'MYSQL':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRE':
		   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
			   "	   MAX(COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol)) AS mon_obj_ret, SUM(cxp_rd_deducciones.monret) AS monret, ".
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre no N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.ced_bene) ".
			   "     						  	       ELSE 'RIF. � CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, tepuy_deducciones, rpc_beneficiario, rpc_proveedor ".
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ".
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesgeneral ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesgeneral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesespecifico($as_codded,$as_tipproben,$as_codprobenhas,$as_codprobendes,$ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesespecifico
		//         Access: public  
		//	    Arguments: as_codded     // C�digo de Deduccion
		//	    		   as_tipproben     // Tipo de Proveedor � beneficiario
		//	    		   as_codprobenhas     // c�digo de Poveedor / Beneficiario Desde
		//	    		   as_codprobendes     // c�digo de Poveedor / Beneficiario Hasta
		//	    		   ad_fecdes     // Rango de fecha desde
		//	    		   ad_fechas     // Rango de fecha hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que se encarga de extraer todas aquellas deducciones de las solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_gestor = $_SESSION["ls_gestor"];
		$lb_valido=true;
		$ls_criterio="";
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		if($as_codded!="")
		{
			$ls_criterio=$ls_criterio."	AND tepuy_deducciones.codded = '".$as_codded."'";
		}
		switch($as_tipproben)
		{
			case "P":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro <= '".$as_codprobenhas."'";
				}
				break;	
			case "B":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene <= '".$as_codprobenhas."'";
				}
				break;	
		}
		switch ($ls_gestor)
		{
			case 'MYSQL':
			   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
			   break;
			case 'ORACLE':
			   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
			   break;
			case 'POSTGRE':
			   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
			   break;	    
			case 'ANYWHERE':
			   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
			   break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
			   "	   MAX(COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol)) AS mon_obj_ret, SUM(cxp_rd_deducciones.monret) AS monret, ".
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.ced_bene) ".
			   "     						  	       ELSE 'RIF. � CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, tepuy_deducciones, rpc_beneficiario, rpc_proveedor ".
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ".
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".
			   $ls_criterio.
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
			   //print $ls_sql;
			   //die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesespecifico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesespecifico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_retenciones($as_codded,$as_as_coddedhasta,$as_tipproben,$as_codprobenhas,$as_codprobendes,$ad_fecdes,$ad_fechas,$as_codret,$as_emitecomp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesespecifico
		//         Access: public  
		//	    Arguments: as_codded     // C�digo de Deduccion
		//	    		   as_tipproben     // Tipo de Proveedor � beneficiario
		//	    		   as_codprobenhas     // c�digo de Poveedor / Beneficiario Desde
		//	    		   as_codprobendes     // c�digo de Poveedor / Beneficiario Hasta
		//	    		   ad_fecdes     // Rango de fecha desde
		//	    		   ad_fechas     // Rango de fecha hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que se encarga de extraer todas aquellas deducciones de las solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 10/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_gestor = $_SESSION["ls_gestor"];
		$lb_valido=true;
		$ls_criterio="";
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		//print "emite: ".$as_emitecomp;die();
		if($as_codded!="")
		{
			$ls_criterio=$ls_criterio."	AND tepuy_deducciones.codded = '".$as_codded."'";
		}
		switch($as_tipproben)
		{
			case "P":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro <= '".$as_codprobenhas."'";
				}
				break;	
			case "B":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene <= '".$as_codprobenhas."'";
				}
				break;	
		}
		switch ($ls_gestor)
		{
			case 'MYSQL':
			   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
			   break;
			case 'ORACLE':
			   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
			   break;
			case 'POSTGRE':
			   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
			   break;	    
			case 'ANYWHERE':
			   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
			   break;
		}
		if($as_emitecomp==1)
		{
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
			  "	   cxp_rd_cargos.monobjret AS base, ". //SUM(cxp_rd_deducciones.monret) AS monret, ".
			  "	   MAX(COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol)) AS mon_obj_ret,".
			  " SUM(cxp_rd_deducciones.monret) AS monret, ".
			   // NUEVO 2016 //
			   " cxp_rd_cargos.monret as montoiva, cxp_rd.montotdoc, cxp_rd.fecemidoc, cxp_rd.numfac AS factura, cxp_rd.numref AS facturac, scb_dt_cmp_ret.numcom, ".
			   ///////////////
			  
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.rifben) ".
			   "     						  	       ELSE 'RIF. � CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, ".
			   " tepuy_deducciones, rpc_beneficiario, rpc_proveedor,scb_dt_cmp_ret, cxp_rd_cargos  ".

			   /////////// cxp_solicitudes ////////////////////
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ". 
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".			 
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".			
			   $ls_criterio.
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   // NUEVO 2016 //
			   "    AND cxp_solicitudes.numsol = scb_dt_cmp_ret.numsop ".
			   //"    AND scb_dt_cmp_ret.codret ='01' ".
			   "    AND scb_dt_cmp_ret.codret ='".$as_codret."' ".
			   //////////////////////////////////////////////////////////
			   ////	 cxp_dt_solicitudes ////
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   ////  cxp_dt_solicitudes ////
			   //// cxp_rd ////
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
   			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   //"    AND (cxp_rd_deducciones.codded='00001' OR cxp_rd_deducciones.codded='00002') ".
			   "    AND (cxp_rd_deducciones.codded='".$as_codded."') ". //" OR cxp_rd_deducciones.codded='".$as_codded."') ".
			   //// cxp_rd ////
			   ///////// cxp_rd_deducciones /////////
			   "    AND cxp_rd_deducciones.codded='".$as_codded."' ".
			   "    AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
			   ///////// cxp_rd_deducciones /////////
			   ///////// rpc_ ////////
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
		}
		else
		{
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
			  "	   cxp_rd_cargos.monobjret AS base, ". //SUM(cxp_rd_deducciones.monret) AS monret, ".
			  "	   MAX(COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol)) AS mon_obj_ret,".
			  " SUM(cxp_rd_deducciones.monret) AS monret, ".
			   // NUEVO 2016 //
			   " cxp_rd_cargos.monret as montoiva, cxp_rd.montotdoc, cxp_rd.fecemidoc, cxp_rd.numfac AS factura, cxp_rd.numref AS facturac, ".
			   ///////////////
			  
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.rifben) ".
			   "     						  	       ELSE 'RIF. � CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, tepuy_deducciones, rpc_beneficiario, rpc_proveedor, cxp_rd_cargos  ".
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ".
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".
			   $ls_criterio.
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   // NUEVO 2016 //
			   //"    AND cxp_solicitudes.numsol = scb_dt_cmp_ret.numsop ".
			   //"    AND scb_dt_cmp_ret.codret ='01' ".
			   //"    AND scb_dt_cmp_ret.codret ='".$as_codret."' ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   //"    AND (cxp_rd_deducciones.codded='00001' OR cxp_rd_deducciones.codded='00002') ".
			   "    AND (cxp_rd_deducciones.codded='".$as_codded."') ". //" OR cxp_rd_deducciones.codded='".$as_codded."') ".
			   ///////////////
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
			   //print $ls_sql;
			   //die();
		}		
		//print $ls_sql;
		//die();

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_retenciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{

			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
				//print $ls_sql;
			  	//die();
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_retenciones


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesiva_proveedor($as_numcom,$as_tiporeten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesiva_proveedor
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes de iva
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret, ".
			 	"        (SELECT telpro ".
				" 		    FROM rpc_proveedor ".
				"		   WHERE rpc_proveedor.codemp=scb_cmp_ret.codemp ".
				"			 AND rpc_proveedor.cod_pro=scb_cmp_ret.codsujret) AS telpro ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom = '".$as_numcom."'".
				"   AND codret ='".$as_tiporeten."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesiva_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesiva_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesiva_detalle_unificadas($as_numcom,$as_tiporeten,$agrupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesiva_detalle
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes de iva
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, max(scb_dt_cmp_ret.numcom) as numcom, max(scb_dt_cmp_ret.numope) as numope, max(scb_dt_cmp_ret.fecfac) as fecfac, ".
				"		 max(scb_dt_cmp_ret.numfac) as numficha, max(scb_dt_cmp_ret.numcon) as numcon, max(scb_dt_cmp_ret.numnd) as numnd, max(scb_dt_cmp_ret.numnc) as numnc, ".
				"		 max(scb_dt_cmp_ret.tiptrans) as tiptrans, SUM(scb_dt_cmp_ret.totcmp_sin_iva) as totcmp_sin_iva , max(scb_dt_cmp_ret.totcmp_con_iva) as totcmp_con_iva, ".
				"		 max(scb_dt_cmp_ret.basimp) as basimp, scb_dt_cmp_ret.porimp, max(scb_dt_cmp_ret.totimp) as totimp, max(scb_dt_cmp_ret.iva_ret) as iva_ret, max(scb_dt_cmp_ret.desope) as desope, ".
				"		 max(scb_dt_cmp_ret.numsop) as numsop, max(scb_dt_cmp_ret.codban) as codban, max(scb_dt_cmp_ret.ctaban) as ctaban, max(scb_dt_cmp_ret.numdoc) as numdoc, max(scb_dt_cmp_ret.codope) as codope, cxp_solicitudes.monsol, cxp_rd.numfac as numfac, cxp_rd.procede as procede ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes,cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"	AND scb_dt_cmp_ret.codret='".$as_tiporeten."' ".
				"	AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol ".
				"	AND scb_dt_cmp_ret.numfac=cxp_rd.numrecdoc ";
		if($agrupo==1)
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codret ";
		}
		else
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp ";
		}
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesiva_detalle_unificadas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesiva_detalle_unificadas
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesiva_detalle($as_numcom,$as_tiporeten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesiva_detalle
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes de iva
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret,  max(scb_dt_cmp_ret.numcom) as numcom, max(scb_dt_cmp_ret.numope) as numope, max(scb_dt_cmp_ret.fecfac) as fecfac, ".
				" max(cxp_rd.numfac) as numfactura, max(cxp_rd.numref) as numcontrol, max(scb_dt_cmp_ret.numdoc) as numficha, ".
				" max(scb_dt_cmp_ret.numnd) as numnd, max(scb_dt_cmp_ret.numnc) as numnc, max(scb_dt_cmp_ret.tiptrans) as tiptrans, ".
				" SUM(scb_dt_cmp_ret.totcmp_sin_iva) as totcmp_sin_iva , max(scb_dt_cmp_ret.totcmp_con_iva) as totcmp_con_iva, ".
				" SUM(scb_dt_cmp_ret.basimp) as basimp, scb_dt_cmp_ret.porimp, SUM(scb_dt_cmp_ret.totimp) as totimp, SUM(scb_dt_cmp_ret.iva_ret) as iva_ret, ".
				" max(scb_dt_cmp_ret.desope) as desope, max(scb_dt_cmp_ret.numsop) as numsop, ".
				" max(scb_dt_cmp_ret.codban) as codban, max(scb_dt_cmp_ret.ctaban) as ctaban, max(scb_dt_cmp_ret.codope) as codope, cxp_solicitudes.monsol ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes,cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"	AND scb_dt_cmp_ret.codret='".$as_tiporeten."' ".
				"	AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol ".
				"	AND scb_dt_cmp_ret.numdoc=cxp_rd.numrecdoc ".
				" GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numdoc, scb_dt_cmp_ret.porimp ";
	//	print $ls_sql;
	//	die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesiva_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesiva_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contribuyentes_libro_iva($as_codemp,$as_mes,$as_anio,$as_codreten,$as_periodofiscal,$ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contribuyentes_libro_iva
		//         Access: public  
		//	    Arguments:	as_codemp   // Codigo de la empresa
		//	    		as_mes      // Mes del cual se van a generar el archivo txt
		//	    		as_anio     // A�o del cual se van a generar el archivo txt
		//	    		as_codreten // Codigo de la retencion para filtrar
		//	    		as_criterio // Criterio de filtro de rango de fechas
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que filtra las retenciones de IVA ue posteriorente genera el txt para la declaraci�n informativa
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 07/07/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	/*	$ls_sql="SELECT *  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codret ='".$as_codreten."'".
				"   AND estcmpret=1 ".
				"   AND perfiscal ='".$as_periodofiscal."' ".
				$ls_criterio; */
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_criterio=$ls_criterio."	AND tepuy_deducciones.iva = '1'";
		switch ($ls_gestor)
		{
			case 'MYSQL':
			   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
			   break;
			case 'ORACLE':
			   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
			   break;
			case 'POSTGRE':
			   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
			   break;	    
			case 'ANYWHERE':
			   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
			   break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
			  "	   cxp_rd_cargos.monobjret AS base, ". 
			  "	   MAX(COALESCE(cxp_rd_deducciones.monobjret, cxp_solicitudes.monsol)) AS mon_obj_ret,".
			  " SUM(cxp_rd_deducciones.monret) AS monret, ".
			   " cxp_rd_cargos.monret as montoiva, cxp_rd.montotdoc, cxp_rd.fecemidoc, cxp_rd.numfac AS factura, cxp_rd.numref AS facturac, scb_dt_cmp_ret.numcom, ".
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.rifben) ".
			   "     						  	       ELSE 'RIF. � CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, ".
			   " tepuy_deducciones, rpc_beneficiario, rpc_proveedor,scb_dt_cmp_ret, cxp_rd_cargos  ".

			   /////////// cxp_solicitudes ////////////////////
			   "  WHERE cxp_solicitudes.codemp = '".$as_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ". 
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".			 
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".			
			   $ls_criterio.
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   // NUEVO 2016 //
			   "    AND cxp_solicitudes.numsol = scb_dt_cmp_ret.numsop ".
			   //"    AND scb_dt_cmp_ret.codret ='01' ".
			   "    AND scb_dt_cmp_ret.codret ='".$as_codreten."' ".
			   //////////////////////////////////////////////////////////
			   ////	 cxp_dt_solicitudes ////
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   ////  cxp_dt_solicitudes ////
			   //// cxp_rd ////
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
   			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_cargos.numrecdoc ".
			   //"    AND (cxp_rd_deducciones.codded='00001' OR cxp_rd_deducciones.codded='00002') ".
			   //"    AND (cxp_rd_deducciones.codded='".$as_codded."') ". //" OR cxp_rd_deducciones.codded='".$as_codded."') ".
			   //// cxp_rd ////
			   ///////// cxp_rd_deducciones /////////
			   //"    AND cxp_rd_deducciones.codded='".$as_codded."' ".
			   "    AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
			   ///////// cxp_rd_deducciones /////////
			   ///////// rpc_ ////////
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
			//print $ls_sql;die(); 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_contribuyentes_libro_iva ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);

		}		
		return $lb_valido;
	}// end function uf_select_contribuyentes_libro_iva
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracioninformativa($as_quincena,$as_tiporeten,$as_mes,$as_anio,$aa_seguridad,&$nombredelarchivoiva)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracioninformativa
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // A�o del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que genera los txt de la declaraci�n informativa
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_periodofiscal=substr($ld_fechadesde,0,4).substr($ld_fechadesde,5,2);
		$ls_criterio="";
		//$ls_archivo="declaracioninformativa/Retencion_IVA_".date("Y_m_d_H_i").".txt";
		$ls_archivo="declaracioninformativa/Retencion_IVA_".$as_anio."_".$as_mes."_quincena_".$as_quincena.".txt";
		$nombredelarchivoiva="Retencion_IVA_".$as_anio."_".$as_mes."_quincena_".$as_quincena.".txt";
		if (file_exists($ls_archivo))
		{
   			unlink($ls_archivo);
		}
		$lo_archivo=fopen("$ls_archivo","a+");	
		switch($as_quincena)
		{
			case "1":
				$ld_fechahasta=$as_anio."-".$as_mes."-15";
				$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."'".
										  " AND fecrep <='".$ld_fechahasta."'";
				break;               
			
			case "2":
				$ld_fechadesde=$as_anio."-".$as_mes."-16";
				$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."'".
										  " AND fecrep <='".$ld_fechahasta."'";
				break;               
		}
		$ls_sql="SELECT * ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				//"   AND codret ='0000000001' ".
				"   AND codret ='".$as_tiporeten."'".
				"   AND estcmpret=1 ".
				"   AND perfiscal ='".$ls_periodofiscal."' ".
				$ls_criterio;
				//print $ls_sql;die();	   	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_declaracioninformativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_agenteretencion=$_SESSION["la_empresa"]["nombre"];
			$ls_rifagenteret=str_replace('-','',$_SESSION["la_empresa"]["rifemp"]);
			$ls_direccionagenteret=$_SESSION["la_empresa"]["direccion"];					 
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_numcom=$row["numcom"];
				$ls_perfiscal=$row["perfiscal"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_rif=str_replace('-','',$row["rif"]);
				$ls_dirsujret=$row["dirsujret"];
				$lb_valido=$this->uf_retencionesiva_detalle($ls_numcom,$as_tiporeten);						
				if($lb_valido)
				{  
					if(strlen($ls_numcom)==15)
					{
						$ls_numcom1=substr($ls_numcom,0,6);
						$ls_numcom2=substr($ls_numcom,7,8);
						$ls_numcom =$ls_numcom1.$ls_numcom2;
					}
					$li_total=$this->ds_detalle->getRowCount("numcom");			   
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numope=$this->ds_detalle->data["numope"][$li_i];					
						$ls_numfactura=$this->ds_detalle->data["numfactura"][$li_i];	
						$ls_numcontrol=$this->ds_detalle->data["numcontrol"][$li_i];
						$ls_numficha=$this->ds_detalle->data["numficha"][$li_i];
						$ld_fecfac=substr($this->ds_detalle->data["fecfac"][$li_i],0,10);
					// Excento //
						$li_siniva=number_format($this->ds_detalle->data["totcmp_sin_iva"][$li_i],2,".","");
					/////////////
					// Total facturado //
						$li_coniva=number_format($this->ds_detalle->data["totcmp_con_iva"][$li_i],2,".","");
					/////////////////////
						$li_baseimp=number_format($this->ds_detalle->data["basimp"][$li_i],2,".","");
						$li_porimp=number_format($this->ds_detalle->data["porimp"][$li_i],2,".","");
						$li_totimp=number_format($this->ds_detalle->data["totimp"][$li_i],2,".","");
						$li_ivaret=number_format($this->ds_detalle->data["iva_ret"][$li_i],2,".","");
						//$ls_numdoc=$this->ds_detalle->data["numdoc"][$li_i];
						$ls_tiptrans=$this->ds_detalle->data["tiptrans"][$li_i];
						$ls_numnotdeb=$this->ds_detalle->data["numnd"][$li_i];
						$ls_numnotcre=$this->ds_detalle->data["numnc"][$li_i];
						$li_monto=$li_baseimp + $li_totimp;
						$li_totdersiniva= number_format($li_siniva,2,".","");
						$ls_numfacafec="0";
						$ls_tipope="C";
						$ls_tipdoc="01";
						$ls_numexp="0";
						$ls_cadena=$ls_rifagenteret."\t".$ls_perfiscal."\t".$ld_fecfac."\t".$ls_tipope."\t".$ls_tipdoc."\t".
								   $ls_rif."\t".$ls_numfactura."\t".$ls_numcontrol."\t".$li_coniva."\t".$li_baseimp."\t".$li_ivaret."\t".
								   $ls_numfacafec."\t".$ls_numcom."\t".$li_totdersiniva."\t".$li_porimp."\t".$ls_numexp."\r\n";
						if ($lo_archivo)			
						{
							@fwrite($lo_archivo,$ls_cadena);
						}
					}
				}																		 																						  
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Genero el txt de Declaraci�n Informativa Para el A�o ".$as_anio." Mes ".$as_mes." Archivo ".$ls_archivo." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		}		
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracioninformativaislr($as_mes,$as_anio,$aa_seguridad,&$nombredelarchivoxml)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracioninformativa
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // A�o del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que genera los txt de la declaraci�n informativa
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 05/05/2015		Fecha �ltima Modificaci�n :  05/05/2015
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);

		$ls_periodofiscal=substr($ld_fechadesde,0,4).substr($ld_fechadesde,5,2);
		$ls_criterio="";
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_archivo="declaracioninformativa/Retencion_ISLR_".$as_mes."_".$as_anio.".xml";
		$nombredelarchivoxml="Retencion_ISLR_".$as_mes."_".$as_anio.".xml";
		//$ls_archivo="declaracioninformativa/Retencion_ISLR_".date("Y_m_d_H_i").".xml";
		//$nombredelarchivoxml="Retencion_ISLR_".date("Y_m_d_H_i").".xml";
		$as_islr=$this->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='S'",'C');
		//print $as_islr;
		// para crear archivo XML ///
		if (file_exists($ls_archivo))
		{
   			unlink($ls_archivo);
		}
		$lo_archivo=fopen($ls_archivo,"a+");
		////////////////////////////
		$ls_sql="SELECT * ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codret ='".$as_islr."'".
				"   AND estcmpret=1 ".
				"   AND perfiscal ='".$ls_periodofiscal."' ".
				$ls_criterio;
		//print $ls_sql; //."empresa".$ls_codemp;	   
		//die(); 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_declaracioninformativaislr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_agenteretencion=$_SESSION["la_empresa"]["nombre"];
			$ls_rifagenteret=str_replace('-','',$_SESSION["la_empresa"]["rifemp"]);
			$ls_direccionagenteret=$_SESSION["la_empresa"]["direccion"];

			//////////// COMANDOS PARA GENERAR ARCHIVO .XML /////////
			//$xml="<libraray>\n\t\t";
			$xml ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"; //variable que contendra el codigo xml 
			$xml .='<RelacionRetencionesISLR RifAgente="'.$ls_rifagenteret.'" Periodo="'.$ls_periodofiscal.'"'.">\n"; // \t=tab
		
			/////////////////////////////////////////////////////////
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
			
				$ls_numcom=$row["numcom"];
				//print "Numero de comprobante: ".$ls_numcom;
				$as_numcomb=$ls_numcom;
				$ls_perfiscal=$row["perfiscal"];
				$ls_fecrep=substr($row["fecrep"],0,10);
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_rif=str_replace('-','',$row["rif"]);
				$ls_dirsujret=$row["dirsujret"];
				$lb_valido=$this->uf_retencionesislr_detalles($ls_numcom,$as_islr);	
				if($lb_valido)
				{  
					if(strlen($ls_numcom)==15)
					{
						$ls_numcom1=substr($ls_numcom,0,6);
						$ls_numcom2=substr($ls_numcom,6,8);
						$ls_numcom =$ls_numcom1.$ls_numcom2;
					}
					//$li_total=$this->ds_detalle->getRowCount("numfactura");
					$li_total=$this->ds_detalle->getRowCount("numficha");
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numope=$this->ds_detalle->data["numope"][$li_i];
						$ls_numfac=$this->ds_detalle->data["numfactura"][$li_i];	
						$ls_numcontrol=$this->ds_detalle->data["numcontrol"][$li_i];	              
						$ld_fecfac=substr($this->ds_detalle->data["fecfac"][$li_i],0,10);	
						$li_siniva=number_format($this->ds_detalle->data["totcmp_sin_iva"][$li_i],2,".","");
						$li_coniva=number_format($this->ds_detalle->data["totcmp_con_iva"][$li_i],2,".","");	
						$li_baseimp=number_format($this->ds_detalle->data["basimp"][$li_i],2,".","");
						$li_evalua=$this->ds_detalle->data["basimp"][$li_i];	
						$li_porimp=$this->ds_detalle->data["porimp"][$li_i]*100;	
						$li_porimp=number_format($li_porimp,0,".","");
						//$li_porimp=number_format($this->ds_detalle->data["porimp"][$li_i],2,".","");	
						$li_totimp=number_format($this->ds_detalle->data["totimp"][$li_i],2,".","");
						$li_ivaret=number_format($this->ds_detalle->data["iva_ret"][$li_i],2,".","");
						$ls_numdoc=$this->ds_detalle->data["numficha"][$li_i];	
						$ls_tiptrans=$this->ds_detalle->data["tiptrans"][$li_i];	
						$ls_numnotdeb=$this->ds_detalle->data["numnd"][$li_i];	
						$ls_numnotcre=$this->ds_detalle->data["numnc"][$li_i];
						$li_monto=$li_baseimp + $li_totimp;  
						$li_totdersiniva= number_format(abs($li_coniva - $li_monto),2,".","");
						$ls_numfacafec="0";
						$ls_tipope="C";
						$ls_tipdoc="01";
						$ls_numexp="0";

						$cod_conseniat=$this->uf_buscar_cod_seniat($as_numcomb,$ls_numdoc,$as_islr,$ls_codsujret);
						//print "Factura: ".$ls_numfac." Factura Control: ".$ls_numref. "y Ficha Nro.: ".$ls_numdoc."Cod. Seniat: ".$cod_conseniat;

						if($li_evalua>0)
						{
						// A�ADIR DATOS AL DOCUMENTO XML 
  						/////////////////////////////////////////////////////////
							$xml .=" <DetalleRetencion>\n\t"; // \n=<ENTER> \t=TAB
    							//$xml .= "<RifAgente>".$ls_rifagenteret."</RifAgente>\n\t\t";
							//	$xml .= "<Periodo>".$ls_periodofiscal."</Periodo>\n\t\t";
							$xml .= "<RifRetenido>".$ls_rif."</RifRetenido>\n\t";
							$xml .= "<NumeroFactura>".$ls_numfac."</NumeroFactura>\n\t";
							$xml .= "<NumeroControl>".$ls_numcontrol."</NumeroControl>\n\t";
							//	$xml .= "<FechaOperacion>".substr($ld_fecfac,8,2)."-".substr($ld_fecfac,5,2)."-".substr($ld_fecfac,0,4)."</FechaOperacion>\n\t\t";
							$xml .= "<CodigoConcepto>".$cod_conseniat."</CodigoConcepto>\n\t";
							$xml .= "<MontoOperacion>".$li_baseimp."</MontoOperacion>\n\t";
							$xml .= "<PorcentajeRetencion>".$li_porimp."</PorcentajeRetencion>\n";
							$xml .=" </DetalleRetencion>\n";
						}
					}

					//print $xml;
					/// GENERA EL ARCHIVO XML ////

					//$xmlobj=new SimpleXMLElement($xml);
					//$xmlobj->asXML($lo_archivo);
				}	 																						  
			}
			$xml.="</RelacionRetencionesISLR>\n";
			//$xml.="</libraray>\n\r";
			fputs($lo_archivo, $xml);//volcamos el contenido de cadena al fichero
			fclose($lo_archivo);//cerramos el flujo
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				//fclose($lo_archivo);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Genero el txt de Declaraci�n Informativa ISLR Para el A�o ".$as_anio." Mes ".$as_mes." Archivo ".$ls_archivo." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		}		
		return $lb_valido;
	}// end function uf_declaracioninformativaislr
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesmunicipales_proveedor($as_numcom,$as_tiporeten,$as_mes,$as_anio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesmunicipales_proveedor
		//         Access: public  
		//	    Arguments: as_numcom // Numero de comprobante municipal
		//	    		   as_mes    // mes del comprobante
		//	    		   as_anio   // a�o del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret, numlic ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				//"   AND codret='0000000003' ".
				"   AND codret ='".$as_tiporeten."'".
				"   AND fecrep>='".$ld_fechadesde."' ".
				"   AND fecrep<='".$ld_fechahasta."' ".
				"   AND numcom='".$as_numcom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesmunicipales_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesmunicipales_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_retenciones_del_proveedor($as_numcom,$as_tiporeten,$as_mes,$as_anio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retenciones_del_proveedor
		//         Access: public  
		//	    Arguments: as_numcom // Numero de comprobante municipal
		//	    		   as_mes    // mes del comprobante
		//	    		   as_anio   // a�o del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret, numlic ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				//"   AND codret='0000000003' ".
				"   AND codret ='".$as_tiporeten."'".
				"   AND fecrep>='".$ld_fechadesde."' ".
				"   AND fecrep<='".$ld_fechahasta."' ".
				"   AND numcom='".$as_numcom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retenciones_del_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retenciones_del_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_retenciones_del_proveedor_unificadas($as_rif,$as_mes,$as_anio,$as_numsol,$as_fecsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retenciones_del_proveedor
		//         Access: public  
		//	    Arguments: as_numcom // Numero de comprobante municipal
		//	    		   as_mes    // mes del comprobante
		//	    		   as_anio   // a�o del comprobante
		//				   as_fecsol // Fecha del Comprobante	
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  22/09/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT scb_cmp_ret.numcom, scb_cmp_ret.codret, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal, ".
			" scb_cmp_ret.codsujret, scb_cmp_ret.nomsujret, scb_cmp_ret.rif, scb_cmp_ret.dirsujret, scb_cmp_ret.estcmpret, ". 				" scb_cmp_ret.numlic, scb_dt_cmp_ret.numdoc as numficha, cxp_rd.numfac as numfac, scb_dt_cmp_ret.fecfac, ".
			" scb_dt_cmp_ret.numcon, scb_dt_cmp_ret.totcmp_con_iva, scb_dt_cmp_ret.numsop as numsol, ".
			" cxp_solicitudes.consol,cxp_rd.procede as procede ".
			"  FROM scb_cmp_ret, scb_dt_cmp_ret, cxp_rd, cxp_solicitudes, cxp_dt_solicitudes ".
			" WHERE scb_cmp_ret.codemp='".$this->ls_codemp."' ".
			//"   AND codret ='".$as_tiporeten."'".
			//"   AND scb_cmp_ret.numcom=scb_dt_cmp_ret.numcom ".
			"   AND scb_cmp_ret.rif ='".$as_rif."'".
			//"   AND scb_cmp_ret.fecrep>='".$ld_fechadesde."' "." AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ".
  		    // Incluido por Ing. Arnaldo Paredes para filtrar por la fecha del comprobante.
			"   AND scb_cmp_ret.fecrep ='".$as_fecsol."' ".			
			"   AND scb_dt_cmp_ret.numfac=cxp_dt_solicitudes.numrecdoc ".
			// Se agreg� la comparaci�n del campo Numero de Comprobante para filtrar por orden de pago.
			"   AND scb_dt_cmp_ret.numcom=scb_cmp_ret.numcom ". 
			"   AND cxp_rd.numrecdoc=scb_dt_cmp_ret.numdoc ".
			"   AND scb_dt_cmp_ret.numsop='".$as_numsol."' ".
			"   AND cxp_solicitudes.numsol='".$as_numsol."' ".
			" GROUP BY scb_cmp_ret.codret DESC";
				//"   AND scb_dt_cmp_ret.numfac=cxp_rd.numrecdoc ".
				//"   AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ORDER BY scb_cmp_ret.codret DESC";
				//"   AND numcom='".$as_numcom."' ";


		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retenciones_del_proveedor_unificadas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retenciones_del_proveedor_unificadas
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesmunicipales_detalles($as_numcom,$as_tiporeten,$agrupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesmunicipales_detalles
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, max(scb_dt_cmp_ret.numsop) as numsop, max(scb_dt_cmp_ret.fecfac) as fecfac,  max(scb_dt_cmp_ret.numfac) as numficha, cxp_rd.numfac as numfac, max(scb_dt_cmp_ret.numcon) as numcon, ".
				"		SUM(scb_dt_cmp_ret.basimp) as basimp,  scb_dt_cmp_ret.porimp, SUM(scb_dt_cmp_ret.totimp) as totimp, cxp_solicitudes.monsol ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				//"	AND codret='0000000003' ".
				"   AND scb_dt_cmp_ret.codret ='".$as_tiporeten."'".
				"   AND scb_dt_cmp_ret.numsop =cxp_solicitudes.numsol ".
				"   AND scb_dt_cmp_ret.numfac =cxp_rd.numrecdoc";
		if($agrupo==1)
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codret ";
		}
		else
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesmunicipales_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_retencionestimbrefiscal_detalles($as_numcom,$as_tiporeten,$agrupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionestimbrefiscal_detalles
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
/*		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, max(scb_dt_cmp_ret.numsop) as numsop, max(scb_dt_cmp_ret.fecfac) as fecfac,  max(scb_dt_cmp_ret.numfac) as numficha, max(scb_dt_cmp_ret.numcon) as numcon, ".
				"		SUM(scb_dt_cmp_ret.totcmp_con_iva) as montototal, SUM(scb_dt_cmp_ret.basimp) as basimp,  porimp, SUM(scb_dt_cmp_ret.iva_ret) as totimp, cxp_solicitudes.monsol, cxp_rd.numfac as numfac ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"   AND scb_dt_cmp_ret.codret='".$as_tiporeten."'".
				"   AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol".
				"   AND scb_dt_cmp_ret.numfac=cxp_rd.numrecdoc".
				" GROUP BY scb_dt_cmp_ret.codret "; */

				//" GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp ";
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, max(scb_dt_cmp_ret.numsop) as numsop, max(scb_dt_cmp_ret.fecfac) as fecfac,  max(scb_dt_cmp_ret.numfac) as numficha, cxp_rd.numfac as numfac, max(scb_dt_cmp_ret.numcon) as numcon, ".
				"		scb_dt_cmp_ret.basimp as basimp,  scb_dt_cmp_ret.porimp, scb_dt_cmp_ret.totimp as totimp, cxp_solicitudes.monsol ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"   AND scb_dt_cmp_ret.codret ='".$as_tiporeten."'".
				"   AND scb_dt_cmp_ret.numsop =cxp_solicitudes.numsol ".
				"   AND scb_dt_cmp_ret.numfac =cxp_rd.numrecdoc";
		if($agrupo==1)
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codret ";
		}
		else
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp ";
		}
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionestimbrefiscal_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionestimbrefiscal_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_retencionesotros_detalles($as_numcom,$as_tiporeten,$agrupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesotros_detalles
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT scb_dt_cmp_ret.codret as codret, scb_dt_cmp_ret.numsop as numsop, scb_dt_cmp_ret.fecfac as fecfac,  scb_dt_cmp_ret.numfac as numficha, cxp_rd.numfac as numfac, scb_dt_cmp_ret.numcon as numcon, ".
				"		scb_dt_cmp_ret.basimp as basimp,  scb_dt_cmp_ret.porimp, scb_dt_cmp_ret.totimp as totimp, cxp_solicitudes.monsol, tepuy_deducciones.dended ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_rd, tepuy_deducciones".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"   AND scb_dt_cmp_ret.codret ='".$as_tiporeten."'".
				"   AND scb_dt_cmp_ret.numsop =cxp_solicitudes.numsol ".
				"   AND tepuy_deducciones.codded =scb_dt_cmp_ret.codded ".
				"   AND scb_dt_cmp_ret.numfac =cxp_rd.numrecdoc";
		if($agrupo==1)
		{
				$ls_sql=$ls_sql." ORDER BY scb_dt_cmp_ret.codret ";
		}
		else
		{
				$ls_sql=$ls_sql." ORDER BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp ";
		}
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesotros_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesotros_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_detalles($as_numcom,$as_tiporeten,$agrupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_detalles
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes municipales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, max(scb_dt_cmp_ret.numsop) as numsop, max(scb_dt_cmp_ret.fecfac) as fecfac,  max(scb_dt_cmp_ret.numfac) as numfactura, max(scb_dt_cmp_ret.numcon) as numcontrol, max(scb_dt_cmp_ret.numdoc) as numficha, ".
				"		max(scb_dt_cmp_ret.basimp) as basimp,  scb_dt_cmp_ret.porimp, max(scb_dt_cmp_ret.iva_ret) as totimp, cxp_solicitudes.monsol, cxp_rd.numfac as numfac ".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_rd ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"   AND scb_dt_cmp_ret.codret ='".$as_tiporeten."'".
				//"	AND codret='0000000003' ".
				"   AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol".
				"   AND scb_dt_cmp_ret.numdoc=cxp_rd.numrecdoc";
		if($agrupo==1)
		{
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codret ";
		}
		else
		{
				
				$ls_sql=$ls_sql." GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numdoc, scb_dt_cmp_ret.porimp ";
		}
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesislr_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesislr_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_cod_seniat($as_numcom,$as_numfac,$as_islr,$as_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_cod_seniat
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n del codigo del seniat de la retencion
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$cod_seniat="";
		$ls_sql="SELECT tepuy_deducciones.codseniat".
				"  FROM scb_dt_cmp_ret, cxp_solicitudes,cxp_rd_deducciones, tepuy_deducciones ".
				" WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_dt_cmp_ret.numcom='".$as_numcom."' ".
				"   AND scb_dt_cmp_ret.codret ='".$as_islr."'".
				"   AND cxp_solicitudes.cod_pro ='".$as_proveedor."'".
				"   AND cxp_solicitudes.cod_pro =cxp_rd_deducciones.cod_pro".
				"   AND cxp_rd_deducciones.numrecdoc =scb_dt_cmp_ret.numdoc".
				"   AND tepuy_deducciones.codded=cxp_rd_deducciones.codded".
				"   AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol AND tepuy_deducciones.islr='1' ".
				"   GROUP BY tepuy_deducciones.codseniat";

		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesislr_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$cod_seniat=$row["codseniat"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $cod_seniat;
	}// end function uf_buscar_cod_seniat
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesaporte_detalle($as_numcom,$as_tiporeten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesaporte_detalle
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de los comprobantes de aporte social
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 21/10/2008									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(codret) as codret, max(numcom) as numcom, max(numope) as numope, max(fecfac) as fecfac, ".
				"		 max(numfac) as numfac, max(numcon) as numcon, max(numnd) as numnd, max(numnc) as numnc, ".
				"		 max(tiptrans) as tiptrans, SUM(totcmp_sin_iva) as totcmp_sin_iva , max(totcmp_con_iva) as totcmp_con_iva, ".
				"		 SUM(basimp) as basimp, porimp, SUM(totimp) as totimp, SUM(iva_ret) as iva_ret, max(desope) as desope, ".
				"		 max(numsop) as numsop, max(codban) as codban, max(ctaban) as ctaban, max(numdoc) as numdoc, max(codope) as codope ".
				"  FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numcom='".$as_numcom."' ".
				"	AND codret='".$as_tiporeten."' ".
				" GROUP BY codemp, numfac, porimp,numsop ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_retencionesaporte_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesaporte_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------



	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_arc_cabecera($as_coddes,$as_codhas,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_arc_cabecera
		//         Access: public 
		//      Argumento: as_coddes // codigo del proveedor � beneficario desde
		//				   as_codhas // codigo del proveedor � beneficario hasta
		//				   as_tipo // Si buscamos proveedores, beneficiarios � ambos
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos de los proveedores � beneficarios que tiene deducciones de ARC
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		switch($as_tipo)
		{
			case "P": // si es un proveedor
				$ls_codprodes=$as_coddes;
				$ls_codprohas=$as_codhas;
				$ls_cedbendes="";
				$ls_cedbenhas="";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "B": // si es un beneficiario
				$ls_codprodes="";
				$ls_codprohas="";
				$ls_cedbendes=$as_coddes;
				$ls_cedbenhas=$as_codhas;
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "": // si son todos
				$ls_codprodes="";
				$ls_codprohas="";
				$ls_cedbendes="";
				$ls_cedbenhas="";
				break;
		}
		if($ls_codprodes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro>='".$ls_codprodes."'";
		}
		if($ls_codprohas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro<='".$ls_codprohas."'";
		}
		if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene>='".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene<='".$ls_cedbenhas."'";
		}
		$ls_sql="SELECT MAX(cxp_rd.tipproben) AS tipproben, MAX(rpc_proveedor.nompro) AS nompro, ".
				"		MAX(rpc_proveedor.nacpro) AS nacpro, MAX(rpc_proveedor.rifpro) AS rifpro, ".
				"		MAX(rpc_proveedor.nitpro) AS nitpro, MAX(rpc_proveedor.dirpro) AS dirpro, ".
				"       MAX(rpc_proveedor.telpro) AS telpro, MAX(rpc_beneficiario.nombene) AS nombene, ".
				"		MAX(rpc_beneficiario.apebene) AS apebene, MAX(rpc_beneficiario.nacben) AS nacben, ".
				"		MAX(rpc_beneficiario.ced_bene) AS ced_bene, MAX(rpc_beneficiario.numpasben) AS numpasben, ".
				"       MAX(rpc_beneficiario.dirbene) AS dirbene, MAX(rpc_beneficiario.telbene) AS telbene, ".
				"       MAX(cxp_rd.cod_pro) AS cod_pro, MAX(cxp_rd.ced_bene) AS ced_bene ".
				"  FROM cxp_rd, cxp_rd_deducciones, tepuy_deducciones, rpc_proveedor, rpc_beneficiario ".
				" WHERE tepuy_deducciones.islr = 1 ".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
				"   AND cxp_rd.codemp = rpc_proveedor.codemp ".
				"   AND cxp_rd.cod_pro = rpc_proveedor.cod_pro ".
				"   AND cxp_rd.codemp = rpc_beneficiario.codemp ".
				"   AND cxp_rd.ced_bene = rpc_beneficiario.ced_bene ".
				" GROUP BY cxp_rd.cod_pro, cxp_rd.ced_bene ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_arc_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{   
				$lb_valido=false;			     
			}	
			$this->io_sql->free_result($rs_data);  	
		}
		return $lb_valido;		
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_arc_detalle($as_codigo,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_arc_detalle
		//         Access: public 
		//      Argumento: $as_codigo // codigo del proveedor � beneficario desde
		//				   $as_tipo // Si buscamos proveedores, beneficiarios � ambos
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene el detalle del arc dado el proveedor � beneficario
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 15/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		switch($as_tipo)
		{
			case "P": // si es un proveedor
				$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro='".$as_codigo."'";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "B": // si es un beneficiario
				$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene='".$as_codigo."'";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
		}
		$ls_sql="SELECT cxp_rd.fecemidoc, cxp_rd.montotdoc, cxp_rd_deducciones.monobjret, ".
				"		cxp_rd_deducciones.porded, cxp_rd_deducciones.monret, cxp_rd_deducciones.cod_pro ".
				"  FROM cxp_rd, cxp_rd_deducciones, tepuy_deducciones ".
				" WHERE tepuy_deducciones.islr = 1 ".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
				" ORDER BY cxp_rd.fecemidoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_arc_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{   
				$lb_valido=false;			     
			}	
			$this->io_sql->free_result($rs_data);  	
		}
		return $lb_valido;		
	}// end function uf_arc_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_probenrelacionfacturas($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de proveedor
		//				   as_codprobendes  // Codigo proveedor/beneficiario Desde
		//				   as_codprobenhas  // Codigo proveedor/beneficiario Hasta
		//				   ad_fecregdes     // Fecha de registro Desde
		//				   ad_fecreghas     // Fecha de registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca los proveedores/beneficiarios que tienen facturas asociadas
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 02/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		switch($as_tipproben)
		{
			case "P":
				$ls_criterio=" AND cxp_rd.cod_pro>='".$as_codprobendes."'".
							 " AND cxp_rd.cod_pro<='".$as_codprobenhas."'".
							 " AND cxp_rd.ced_bene='----------'";
			break;
			case "B":
				$ls_criterio=" AND cxp_rd.ced_bene>='".$as_codprobendes."'".
							 " AND cxp_rd.ced_bene<='".$as_codprobenhas."'".
							 " AND cxp_rd.cod_pro='----------'";
			break;
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}
		$ls_sql="SELECT (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.cod_pro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.ced_bene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS codigo, ".
				"(CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre,cxp_rd.tipproben ".
				"  FROM cxp_rd,cxp_dt_solicitudes ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				" ".$ls_criterio." ".
				" GROUP BY codigo,cxp_rd.codemp,cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.tipproben";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{print($this->io_sql->message);
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_probenrelacionfacturas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_probenrelacionfacturas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_facturasproben($as_tipproben,$as_codigo,$ad_fecregdes,$ad_fecreghas,$ai_ordendoc,$ai_ordenfec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de proveedor
		//				   as_codigo     // Codigo proveedor/beneficiario 
		//				   ad_fecregdes  // Fecha de registro Desde
		//				   ad_fecreghas  // Fecha de registro Hasta
		//				   ai_ordendoc   // Indica si se desea ordenar por documento
		//				   ai_ordenfec   // Indica si se desea ordenar por fecha de Registro
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca los las facturas asociadas a un proveedor/beneficiario
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_order="";
		$this->ds_detrecdoc = new class_datastore();
		if($as_tipproben=="P")
		{
			$ls_criterio=" AND cxp_rd.cod_pro='".$as_codigo."'".
						 " AND cxp_rd.ced_bene='----------'";
		}
		else
		{
			$ls_criterio=" AND cxp_rd.ced_bene='".$as_codigo."'".
						 " AND cxp_rd.cod_pro='----------'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}
		if($ai_ordendoc==1)
		{
			$ls_order=" ORDER BY cxp_rd.numrecdoc";
		}
		if($ai_ordenfec==1)
		{
			if($ls_order=="")
			{
				$ls_order=" ORDER BY cxp_rd.fecregdoc";
			}
			else
			{
				$ls_order=$ls_order.", cxp_rd.fecregdoc";
			}
		}
		$ls_sql="SELECT cxp_rd.numrecdoc, cxp_rd.fecregdoc, cxp_rd.fecemidoc, cxp_rd.dencondoc,".
				" 		cxp_rd.montotdoc,cxp_dt_solicitudes.numsol".
				"  FROM cxp_rd,cxp_dt_solicitudes ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				" ".$ls_criterio." ".
				" ".$ls_order." ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_probenrelacionfacturas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detrecdoc->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_facturasproben
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_relacionsolicitudes($as_numsoldes,$as_numsolhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_numsoldes  // Numero de solicitud de orden de pago desde
		//				   as_numsolhas  // Numero de solicitud de orden de pago hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca los las solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$this->ds_detsolicitudes = new class_datastore();
		if(!empty($as_numsoldes))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol<='".$as_numsolhas."'";
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"       cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,".
				//"       SUM(cxp_rd_spg.monto),".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ". //, cxp_dt_solicitudes, cxp_rd_spg ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				" ".$ls_criterio." ".
				//" AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol AND cxp_rd_spg.numrecdoc=cxp_dt_solicitudes.numrecdoc ".
				//" AND cxp_solicitudes.ced_bene=cxp_dt_solicitudes.ced_bene AND cxp_solicitudes.cod_pro=cxp_dt_solicitudes.cod_pro ".
				//" AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro ".
				" ORDER BY cxp_solicitudes.numsol";
		//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_relacionsolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_relacionsolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_montobruto($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_monbruto
		//         Access: public  
		//	    Arguments: as_numsol  // Numero de solicitud de orden de pago desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca los las solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia 
		// Fecha Creaci�n: 03/07/2017	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_monto=0;
		$ls_sql="SELECT SUM(cxp_rd.montotdoc) AS montotdoc,SUM(cxp_rd.mondeddoc) AS mondeddoc".
				"  FROM cxp_rd, cxp_solicitudes, cxp_dt_solicitudes ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				" AND cxp_rd.codemp=cxp_solicitudes.codemp ".
				" AND cxp_solicitudes.numsol='".$as_numsol."'".
				" AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol ".
				" AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc ";
		//print $ls_sql;die();
		$rs_data1=$this->io_sql->select($ls_sql);
		if($rs_data1===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_montobruto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data1))
			{
				$li_monto=$row["montotdoc"]+$row["mondeddoc"];
			}
			$this->io_sql->free_result($rs_data1);
		}	
		return $li_monto;
	}// end function uf_select_montobruto
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesprobensaldos($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecemides,$ad_fecemihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesprobensaldos
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecemides     // Fecha de Emision Desde
		//                 ad_fecemihas     // Fecha de Emision Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca los proveedores/beneficiarios con solicitudes de pago asociadas
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene,cxp_solicitudes.tipproben, ".
				"   MAX((CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".

				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END )) AS nombre, ".
				"   MAX((CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.cod_pro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.ced_bene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END )) AS codigo ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND (cxp_solicitudes.estprosol='E'".
				"    OR cxp_solicitudes.estprosol='C'".
				"    OR cxp_solicitudes.estprosol='S')".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro,cxp_solicitudes.tipproben";
				" ORDER BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudesprobensaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesprobensaldos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionsaldos($as_tipproben,$as_codproben,$ad_fecemides,$ad_fecemihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesprobensaldos
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_detsolicitudes = new class_datastore();
		$lb_valido=true;
		$ls_criterio="";
		if($as_tipproben=="P")
		{
			$ls_criterio=$ls_criterio." AND cxp_solicitudes.cod_pro='".$as_codproben."'".
									  " AND cxp_solicitudes.ced_bene='----------'".
									  " AND cxp_solicitudes.tipproben='P'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND cxp_solicitudes.ced_bene='".$as_codproben."'".
									  " AND cxp_solicitudes.cod_pro='----------'".
									  " AND cxp_solicitudes.tipproben='B'";
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}
		
		$ls_sql="SELECT cxp_solicitudes.fecemisol, cxp_solicitudes.consol,cxp_solicitudes.numsol,".
				"       cxp_solicitudes.monsol".
				"  FROM cxp_solicitudes".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				"   AND (cxp_solicitudes.estprosol='E'".
				"    OR cxp_solicitudes.estprosol='C'".
				"    OR cxp_solicitudes.estprosol='S')".
				" ORDER BY cxp_solicitudes.fecemisol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_informacionsaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detsolicitudes->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionsaldos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionndnc($as_tipproben,$as_codproben,$ad_fecemides,$ad_fecemihas,$as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionndnc
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//                 ad_fecemides // Fecha de Emision Desde
		//                 ad_fecemihas // Fecha de Emision
		//                 as_numsol    // Numero de la Solicitud
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las notas de Debito/Credito de una Solicitud de Pago
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 26/08/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_detndnc = new class_datastore();
		$lb_valido=true;
		$ls_criterio="";
		if($as_tipproben=="P")
		{
			$ls_criterio=$ls_criterio." AND cxp_sol_dc.cod_pro='".$as_codproben."'".
									  " AND cxp_sol_dc.ced_bene='----------'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND cxp_sol_dc.ced_bene='".$as_codproben."'".
									  " AND cxp_sol_dc.cod_pro='----------'";
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope<='".$ad_fecemihas."'";
		}
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.numsol='".$as_numsol."'";
		}
		
		$ls_sql="SELECT cxp_sol_dc.numsol,cxp_sol_dc.monto, cxp_sol_dc.codope,cxp_sol_dc.numdc,".
				"		cxp_sol_dc.fecope,cxp_sol_dc.desope".
				"  FROM cxp_sol_dc".	
				" WHERE cxp_sol_dc.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				"   AND cxp_sol_dc.estnotadc='C'";
		//		print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_informacionndnc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detndnc->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionndnc
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionpagos($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionpagos
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_monto=0;
		$ls_sql="SELECT SUM(monto) AS monto".
				"  FROM cxp_sol_banco".	
				" WHERE cxp_sol_banco.codemp='".$this->ls_codemp."' ".
				"   AND cxp_sol_banco.numsol='".$as_numsol."'".
				" GROUP BY cxp_sol_banco.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_informacionpagos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_monto=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_monto;
	}// end function uf_select_informacionpagos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_relacionndnc($as_tipndnc,$as_numsoldes,$as_numsolhas,$as_ndncdes,$ad_ndnchas,
								    $ad_fecregdes,$ad_fecreghas,$ai_emitida,$ai_contabilizada,$ai_anulada)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_relacionndnc
		//         Access: public  
		//	    Arguments: $as_tipndnc   // Tipo de nota Debito/Credito
		//                 $as_numsoldes // Numero de Solicitud Desde
		//                 $as_numsolhas // Numero de Solicitud Hasta
		//                 $as_ndncdes   // Numero de Nota Debito/Credito Desde
		//                 $ad_ndnchas   // Numero de Nota Debito/Credito Hasta
		//                 $ad_fecregdes // Fecha de Registro Desde
		//                 $ad_fecreghas // Fecha de Registro Hasta
		//                 $ai_emitida   // Estatus de Nota Emitida
		//                 $ai_contabilizada // Estatus de Nota Contabilizada
		//                 $ai_anulada   // Estatus de Nota Anulada
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las notas de Debito / Credito
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		if(!empty($as_numsoldes))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numsol<='".$as_numsolhas."'";
		}
		if(!empty($as_ndncdes))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numdc>='".$as_ndncdes."'";
		}
		if(!empty($ad_ndnchas))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numdc<='".$ad_ndnchas."'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope<='".$ad_fecreghas."'";
		}
		switch($as_tipndnc)
		{
			case "D":
				$ls_criterio=$ls_criterio."AND cxp_sol_dc.codope='D'";
			break;
			case "C":
				$ls_criterio=$ls_criterio."AND cxp_sol_dc.codope='C'";
			break;
		}
		if(($ai_emitida==1)||($ai_contabilizada==1)||($ai_anulada==1))
		{
			$lb_anterior=false;
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='E'";
					$lb_anterior=true;
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_sol_dc.estnotadc='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_sol_dc.estnotadc='A'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_sol_dc.numsol, cxp_sol_dc.numdc, cxp_sol_dc.codtipdoc, cxp_sol_dc.numrecdoc, cxp_sol_dc.fecope,".
				"       cxp_sol_dc.codope, cxp_sol_dc.desope, cxp_sol_dc.monto, cxp_sol_dc.estapr, cxp_sol_dc.estnotadc,".
				"		cxp_documento.dentipdoc,".
				"		(CASE cxp_sol_dc.ced_bene WHEN '----------' THEN (SELECT rpc_proveedor.nompro ".
				"                                        				    FROM rpc_proveedor ".
				"                                       				   WHERE rpc_proveedor.codemp=cxp_sol_dc.codemp ".
				"                                         					 AND rpc_proveedor.cod_pro=cxp_sol_dc.cod_pro) ".
				"                       		  ELSE (SELECT ".$ls_cadena." ".
				"                                         FROM rpc_beneficiario ".
				"                                        WHERE rpc_beneficiario.codemp=cxp_sol_dc.codemp ".
				"                                          AND rpc_beneficiario.ced_bene=cxp_sol_dc.ced_bene) END) AS nombre ".
				"  FROM cxp_sol_dc,cxp_documento".	
				" WHERE cxp_sol_dc.codemp='".$this->ls_codemp."' ".
				" ".$ls_criterio." ".
				"   AND cxp_sol_dc.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_relacionndnc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_relacionndnc
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_report_libcompra($ld_fecdesde,$ld_fechasta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_spg_nota
		//		   Access: public 

		//	  Description: 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$ls_sql="SELECT RD.*,SUM(COALESCE(DED.monret,0)) as monret
				 FROM tepuy_deducciones DED,cxp_rd RD
				 LEFT OUTER JOIN cxp_rd_deducciones RDDED
				 ON RD.numrecdoc=RDDED.numrecdoc AND RD.codtipdoc=RDDED.codtipdoc AND RD.cod_pro=RDDED.cod_pro AND DED.codded=RDDED.codded
				 AND RD.ced_bene=RDDED.ced_bene
				 WHERE RD.codemp='".$_SESSION["la_empresa"]["codemp"]."' AND estlibcom=1 AND fecregdoc between '".$ld_fecdesde."' AND '".$ld_fechasta."'
				 AND DED.iva=1  
				 GROUP BY numrecdoc,cod_pro,ced_bene,codtipdoc ";*/


// HASTA SEPTIEMBRE 2016, QUE NO SE MOSTRABA EN EL LIBRO DE COMPRAS LAS EXCENTAS DE IVA //
/*		$ls_sql= "SELECT RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,MAX(RD.montotdoc) AS montotdoc,MAX(RD.mondeddoc) AS mondeddoc,RD.codtipdoc,MAX(RD.fecemidoc) AS fecemidoc,MAX(RD.numref) AS numref, MAX(RD.numfac) AS numfac,SUM(COALESCE(RDDED.monret,0)) as monret,MAX(DED.iva) AS iva
				 FROM tepuy_deducciones DED,cxp_rd RD
				 LEFT OUTER JOIN cxp_rd_deducciones RDDED
				 ON RD.numrecdoc=RDDED.numrecdoc AND RD.codtipdoc=RDDED.codtipdoc AND RD.cod_pro=RDDED.cod_pro
				 AND RD.ced_bene=RDDED.ced_bene
				 WHERE RD.codemp='".$_SESSION["la_empresa"]["codemp"]."' AND estlibcom=1 AND fecregdoc between '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
			"	 AND DED.codded=RDDED.codded AND DED.iva=1 ".
			"	 GROUP BY RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,RD.codtipdoc,RDDED.codded 
				 ORDER BY MAX(fecemidoc)"; */
// ADAPTADO PARA MOSTRAR EXENTOS DE IVA //
		$ls_sql= "SELECT RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,MAX(RD.montotdoc) AS montotdoc,MAX(RD.mondeddoc) AS mondeddoc, ".
			 " MAX(RD.moncardoc) AS moncardoc, RD.codtipdoc,MAX(RD.fecemidoc) AS fecemidoc, ".
			 " MAX(RD.numref) AS numref, MAX(RD.numfac) AS numfac ".
			 " FROM tepuy_deducciones DED,cxp_rd RD ".
			 //"	 LEFT OUTER JOIN cxp_rd_deducciones RDDED ".
			 //"	 ON RD.numrecdoc=RDDED.numrecdoc AND RD.codtipdoc=RDDED.codtipdoc AND RD.cod_pro=RDDED.cod_pro ".
			 //"	 AND RD.ced_bene=RDDED.ced_bene ".
			 "	 WHERE RD.codemp='".$_SESSION["la_empresa"]["codemp"]."' AND estlibcom=1 AND fecregdoc between '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
			//"	 AND DED.codded=RDDED.codded AND DED.iva=1 ".
			 "	 GROUP BY RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,RD.codtipdoc". //,RDDED.codded 
			 "	 ORDER BY MAX(fecemidoc)";

		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_reportLibCompra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
			//	mysql_data_seek( $rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	
	
	function uf_select_data($io_sql,$ls_cadena,$ls_campo)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_data
		//		   Access: public 
		//	  Description: Devuelve el valor del campo enviado como parametro
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data=$io_sql->select($ls_cadena);	
		if($row=$io_sql->fetch_row($data)){	$ls_result=$row[$ls_campo];}	
		else{$ls_result="";	}
		$io_sql->free_result($data);
		return $ls_result;
	}
	
	function uf_select_rowdata($io_sql,$ls_cadena)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rowdata
		//		   Access: public 
		//	  Description: Devuelve la fila resultante del select realizado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data=$io_sql->select($ls_cadena);	
		//print $ls_cadena;
		//die();
		if($row=$io_sql->fetch_row($data)){	$la_result=$row;}	
		else{$la_result=array();	}
		$io_sql->free_result($data);
		return $la_result;
	}
	
	function uf_select_rsdata($ls_cadena,$rs_data)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rsdata
		//		   Access: public 
		//	  Description: Devuelve el resultset obtenido de la consulta
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data=$this->io_sql->select($ls_cadena);	
		if($rs_data===false)
		{
			$this->io_mensajes->message(" ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
				mysql_data_seek($rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;	
	}

	function uf_buscar_asientomanual($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_asientomnual
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		 $ls_sql="SELECT monto 
		 		  FROM cxp_rd_scg 
				  WHERE codemp='".$ls_codemp."'    AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."'
				  AND 	ced_bene='".$ls_cedbene."' AND cod_pro='".$ls_codpro."'      AND estasicon='M'";
		 $ldec_monto = $this->uf_select_data($this->io_sql,$ls_sql,"monto");
		 if($ldec_monto=="")
		 {
		 	$ldec_monto=0;
		 }
		 return $ldec_monto;
	}
	
	function uf_select_dt_spg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_spg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND a.cod_pro='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND a.ced_bene='".$ls_codproben."' ";
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_aux_codestpro=" a.codestpro=CONCAT(b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,b.codestpro5) ";
		}
		else
		{
			$ls_aux_codestpro=" a.codestpro=(b.codestpro1||b.codestpro2||b.codestpro3||b.codestpro4||b.codestpro5) ";
		}

		$ls_sql="SELECT a.spg_cuenta,a.monto,a.codestpro,b.denominacion".
				"  FROM cxp_dc_spg a,spg_cuentas b".
				" WHERE a.codemp='".$_SESSION["la_empresa"]["codemp"]."'".
				"   AND a.numdc='".$ls_numnota."'".
				"   AND a.numsol='".$ls_numord."'".
				"   AND a.numrecdoc='".$ls_numrecdoc."'".
				"   AND a.codtipdoc='".$ls_codtipdoc."'".
				"   AND ".$ls_aux_codestpro."".
				"   AND a.spg_cuenta=b.spg_cuenta".
				"   AND a.codemp=b.codemp ".$ls_aux."".
				" ORDER BY a.codestpro,a.spg_cuenta";	
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_dt_spg_nota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
//				mysql_data_seek($rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;			
						
	}
	
	function uf_select_dt_scg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_scg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND a.cod_pro='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND a.ced_bene='".$ls_codproben."' ";
		}

		$ls_sql="SELECT a.*,b.denominacion 
				 FROM cxp_dc_scg a,scg_cuentas b 
				 WHERE a.codemp='".$_SESSION["la_empresa"]["codemp"]."' AND a.numdc='".$ls_numnota."' AND a.numsol='".$ls_numord."' 
				 AND a.numrecdoc='".$ls_numrecdoc."' AND a.codtipdoc='".$ls_codtipdoc."' 
				 AND a.sc_cuenta=b.sc_cuenta AND a.codemp=b.codemp ".$ls_aux."
				 ORDER BY a.debhab DESC,a.sc_cuenta";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_dt_spg_nota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
//				mysql_data_seek( $rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;			
						
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesactualescxp($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesactualescxp
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: funci�n que busca la informaci�n de las solicitudes de pagos  en el intervalo indicado
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 03/06/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_solactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol,cxp_solicitudes.consol,cxp_solicitudes.monsol, cxp_historico_solicitud.estprodoc,".
				"		 cxp_historico_solicitud.fecha ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.estprodoc='C'".
				"    AND cxp_solicitudes.estprosol<>'A'".
				" ".$ls_criterio." ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_solicitudesactualescxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesactualescxp
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionpagoscxp($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionpagoscxp
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente � False si no se creo
		//    Description: Funci�n que busca la informaci�n de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 14/07/2007									Fecha �ltima Modificaci�n :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_monto=0;
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_pagactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol,scb_movbco.monto,cxp_sol_banco.codope,scb_movbco.fecmov,scb_movbco.conmov ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud,cxp_sol_banco,scb_movbco".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_solicitudes.estprosol<>'A'".
				" ".$ls_criterio." ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_solicitudes.codemp=cxp_sol_banco.codemp".
				"    AND cxp_solicitudes.numsol=cxp_sol_banco.numsol".
				"    AND cxp_sol_banco.codemp=scb_movbco.codemp".
				"    AND cxp_sol_banco.codban=scb_movbco.codban".
				"    AND cxp_sol_banco.ctaban=scb_movbco.ctaban".
				"    AND cxp_sol_banco.numdoc=scb_movbco.numdoc".
				"    AND cxp_sol_banco.codope=scb_movbco.codope".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_informacionpagoscxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_pagactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionpagoscxp
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_datos_comprobante($ls_codemp,$ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codproben)
	{//Utilizado en libro de compra
	
		$la_data=$this->uf_select_rowdata($this->io_sql,"SELECT a.numrecdoc as numrecdoc,a.monobjret as monobjret,a.monret as monret,a.porded as porded,
																              b.codret as codret,b.numcom as numcom,b.iva_ret as iva_ret,tiptrans
												  FROM cxp_rd_deducciones a,scb_dt_cmp_ret b,scb_cmp_ret cmp
												  WHERE a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."' AND cmp.codsujret='".$ls_codproben."'
												  AND a.codemp=b.codemp AND a.codemp=cmp.codemp AND a.numrecdoc=b.numfac AND b.codret=cmp.codret AND b.numcom=cmp.numcom
												  GROUP BY a.numrecdoc ");
		return $la_data;
	}
	
	function uf_select_cargos($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben)
	{//Utilizado en libro de compra.
		$la_data=$this->uf_select_rowdata($this->io_sql,"SELECT monobjret as basimp,porcar,monret as impiva".
															 "  FROM cxp_rd_cargos ".
															 " WHERE codemp='".$ls_codemp."'".
															 "   AND numrecdoc='".$ls_numrecdoc."'".
															 "   AND codtipdoc='".$ls_codtipdoc."'".
															 "   AND cod_pro='".$ls_codpro."'".
															 "   AND ced_bene='".$ls_cedben."'");
		return	$la_data;	
	}
	
	function uf_select_dtnotas($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,&$rs_data)
	{
		$ls_cadena="SELECT * 
					FROM cxp_sol_dc 
					WHERE codemp='".$ls_codemp."' AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' 
					AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedben."'";
		$lb_valido=$this->uf_select_rsdata($ls_cadena,&$rs_data);
		return $lb_valido;	
	}
	
	function uf_select_notaformatosalida($ls_codemp,$ls_numnota,$ls_tiponota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_aux)
	{
		$la_nota=$this->uf_select_rowdata($this->io_sql,"SELECT * FROM cxp_sol_dc WHERE codemp='".$ls_codemp."' 
												   AND numdc='".$ls_numnota."' AND codope='".$ls_tiponota."' AND numsol='".$ls_numord."' 
												   AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' ".$ls_aux);
		return $la_nota;
	
	}
	
	function uf_select_notacargos($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,$ls_codope,$ls_numnota,$ls_numsol,$ldec_porcar)
	{
		$ldec_monto=0;
		$ldec_porcar=0;
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_aux_codestpro=" CONCAT(spg_cuenta,codestpro)";
		}
		else
		{
			$ls_aux_codestpro=" (b.codestpro||b.spg_cuenta) ";
		}
		$ls_sql="SELECT monto,porcar
				 FROM cxp_dc_spg a,cxp_rd_cargos c
				 WHERE a.numdc='".$ls_numnota."' AND a.codope='".$ls_codope."' AND a.numsol='".$ls_numsol."' AND a.numrecdoc='".$ls_numrecdoc."' AND a.codtipdoc='".$ls_codtipdoc."' AND a.cod_pro='".$ls_codpro."'
				 AND a.ced_bene='".$ls_cedben."' AND a.numrecdoc=c.numrecdoc AND a.codtipdoc=c.codtipdoc AND a.cod_pro=c.cod_pro AND a.ced_bene=c.ced_bene AND CONCAT(a.spg_cuenta,a.codestpro) IN (SELECT DISTINCT CONCAT(spg_cuenta,codestpro) FROM tepuy_cargos)";

		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report M�TODO->uf_select_informacionpagoscxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
				$ldec_porcar=$row["porcar"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ldec_monto;
	}

function uf_select_sol_cargos($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_car_dt = new class_datastore();
	 
	 $ls_sql=" SELECT C.numsol as numsol,C.numrecdoc as numrecdoc,
	                  P.codcar as codcar,SUM(P.monobjret) as monobjretcar,
					  SUM(P.monret) as objretcar, 
					  max(tepuy_cargos.dencar) as dencar
                 FROM cxp_dt_solicitudes C, cxp_rd_cargos P, tepuy_cargos
                WHERE C.codemp='".$as_codemp."'  
			      AND C.numsol ='".$as_numero."' 
				  AND C.codemp=P.codemp
				  AND C.numrecdoc=P.numrecdoc
				  AND C.cod_pro=P.cod_pro
				  AND C.ced_bene=P.ced_bene
				  AND P.codemp=tepuy_cargos.codemp
				  AND P.codcar=tepuy_cargos.codcar
                GROUP BY P.codcar,C.numsol,C.numrecdoc";

	 $this->ds_car_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("CLASE->tepuy_CXP_CLASS_REPORT; METODO->uf_select_sol_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_car_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	
	function uf_select_sol_deducciones($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_sol_deducciones
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_ded_dt = new class_datastore();
	 
	 $ls_sql=" SELECT C.numsol as numsol,C.numrecdoc as numrecdoc,
	                  P.codded as codded,SUM(P.monobjret) as monobjretded, 
					  SUM(P.monret) as objretded, 
					  max(tepuy_deducciones.dended) as dended
                 FROM cxp_dt_solicitudes C, cxp_rd_deducciones P, tepuy_deducciones
                WHERE C.codemp='".$as_codemp."'  
			      AND C.numsol ='".$as_numero."' 
				  AND C.codemp=P.codemp
				  AND C.numrecdoc=P.numrecdoc
				  AND C.cod_pro=P.cod_pro
		          AND C.ced_bene=P.ced_bene      
                  AND P.codemp=tepuy_deducciones.codemp
				  AND P.codded=tepuy_deducciones.codded
				GROUP BY P.codded,C.numsol,C.numrecdoc";
			  				
	 $this->ds_ded_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia->uf_select_sol_deducciones");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_ded_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}

}
?>
