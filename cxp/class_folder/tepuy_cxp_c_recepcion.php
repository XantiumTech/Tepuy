<?php
class tepuy_cxp_c_recepcion
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_cxp_c_recepcion($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_cxp_c_recepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		$this->io_ds_cargos=new class_datastore(); // Datastored de cargos
		require_once($as_path."shared/class_folder/class_datastore.php");
		$this->io_ds_deducciones=new class_datastore(); // Datastored de Deducciones
		require_once($as_path."shared/class_folder/class_datastore.php");
		$this->io_ds_compromisos=new class_datastore(); // Datastored de Deducciones
		require_once($as_path."shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}// end function tepuy_cxp_c_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tipodocumento($as_seleccionado,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tipodocumento
		//		   Access: public
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//		 		   $as_tipo // Tipo de documento por el cual se debe filtrar
		//	  Description: Función que busca en la tabla de tipo de documento los tipos de Recepciones
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipdoc, dentipdoc, estcon, estpre ".
				"  FROM cxp_documento ".
				" ORDER BY codtipdoc ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_tipodocumento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipdoc' id='cmbcodtipdoc' onChange='javascript: ue_cambiartipodocumento();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_dentipdoc=$row["dentipdoc"];
				$ls_estcon=$row["estcon"];
				$ls_estpre=$row["estpre"];
				$ls_tipdoc=$ls_estcon.$ls_estpre;
				if($as_tipo=="C") // Recepcion Contable
				{
					if($as_seleccionado==$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre)
					{
						$ls_seleccionado="selected";
					}
					print "<option value='".$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre."' ".$ls_seleccionado.">".$ls_dentipdoc."</option>";
				}
				else
				{
					if(($ls_tipdoc!="13")&&($ls_tipdoc!="14"))
					{
						if($as_seleccionado==$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre)
						{
							$ls_seleccionado="selected";
						}
						print "<option value='".$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre."' ".$ls_seleccionado.">".$ls_dentipdoc."</option>";
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tipodocumento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_clasificacionconcepto($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_clasificacionconcepto
		//		   Access: public
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca la clasificacion del concepto y las coloca en un combo
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcla, dencla ".
				"  FROM cxp_clasificador_rd ".
				" ORDER BY codcla ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_clasificacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodcla' id='cmbcodcla' onChange='javascript: ue_agregarconcepto();'>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codcla=$row["codcla"];
				$ls_dencla=$row["dencla"];
				if($as_seleccionado==$ls_codcla)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codcla."' ".$ls_seleccionado.">".$ls_dencla."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_clasificacionconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_spgcuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_spgcuentas
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las cuentas presupuestarias de una recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT cxp_rd_spg.numdoccom, cxp_rd_spg.codestpro, cxp_rd_spg.spg_cuenta, cxp_rd_spg.monto, spg_cuentas.sc_cuenta, ".
				"		cxp_rd_spg.procede_doc, cxp_rd_spg.codfuefin, ".
				"		(SELECT MAX(cxp_rd_cargos.codcar) FROM cxp_rd_cargos ".
				"		  WHERE cxp_rd_cargos.codemp = cxp_rd_spg.codemp ".
				"			AND cxp_rd_cargos.numrecdoc = cxp_rd_spg.numrecdoc ".
				"			AND cxp_rd_cargos.codtipdoc = cxp_rd_spg.codtipdoc ".
				"			AND cxp_rd_cargos.ced_bene = cxp_rd_spg.ced_bene ".
				"			AND cxp_rd_cargos.cod_pro = cxp_rd_spg.cod_pro ".
				"  			AND cxp_rd_cargos.codestpro1 = substr(cxp_rd_spg.codestpro,1,20) ".
				"   		AND cxp_rd_cargos.codestpro2 = substr(cxp_rd_spg.codestpro,21,6) ".
				"   		AND cxp_rd_cargos.codestpro3 = substr(cxp_rd_spg.codestpro,27,3) ".
				"   		AND cxp_rd_cargos.codestpro4 = substr(cxp_rd_spg.codestpro,30,2) ".
				"   		AND cxp_rd_cargos.codestpro5 = substr(cxp_rd_spg.codestpro,32,2) ".
				"   		AND trim(cxp_rd_cargos.spg_cuenta) = trim(cxp_rd_spg.spg_cuenta)) AS cargo ".
				"  FROM cxp_rd_spg, spg_cuentas ".
				" WHERE cxp_rd_spg.codemp = '".$this->ls_codemp."'".
				"	AND cxp_rd_spg.numrecdoc = '".$as_numrecdoc."'".
				"	AND cxp_rd_spg.codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(cxp_rd_spg.ced_bene) = '".$as_cedbene."'".
				"	AND trim(cxp_rd_spg.cod_pro) = '".$as_codpro."'".
				"   AND cxp_rd_spg.codemp = spg_cuentas.codemp ".
				"   AND substr(cxp_rd_spg.codestpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(cxp_rd_spg.codestpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(cxp_rd_spg.codestpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(cxp_rd_spg.codestpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(cxp_rd_spg.codestpro,32,2) = spg_cuentas.codestpro5 ".
				"   AND trim(cxp_rd_spg.spg_cuenta) = trim(spg_cuentas.spg_cuenta) ".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sccuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sccuentas
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las cuentas contables de una recepción de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT numdoccom, debhab, sc_cuenta, estasicon AS estatus, monto, procede_doc ".
				"  FROM cxp_rd_scg ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numrecdoc = '".$as_numrecdoc."'".
				"	AND codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(ced_bene) = '".$as_cedbene."'".
				"	AND trim(cod_pro) = '".$as_codpro."'".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_sccuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_sccuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca los cargos de una recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT cxp_rd_cargos.codcar, cxp_rd_cargos.numdoccom, cxp_rd_cargos.monobjret, cxp_rd_cargos.monret, ".
				"		cxp_rd_cargos.codestpro1, cxp_rd_cargos.codestpro2, cxp_rd_cargos.codestpro3, cxp_rd_cargos.codestpro4, ".
				"		cxp_rd_cargos.codestpro5, cxp_rd_cargos.spg_cuenta, cxp_rd_cargos.porcar, cxp_rd_cargos.formula, ".
				"		cxp_rd_cargos.procede_doc, spg_cuentas.sc_cuenta ".
				"  FROM cxp_rd_cargos, spg_cuentas ".
				" WHERE cxp_rd_cargos.codemp = '".$this->ls_codemp."'".
				"	AND cxp_rd_cargos.numrecdoc = '".$as_numrecdoc."'".
				"	AND cxp_rd_cargos.codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(cxp_rd_cargos.ced_bene) = '".$as_cedbene."'".
				"	AND trim(cxp_rd_cargos.cod_pro) = '".$as_codpro."'".
				"   AND cxp_rd_cargos.codemp = spg_cuentas.codemp ".
				"   AND cxp_rd_cargos.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND cxp_rd_cargos.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND cxp_rd_cargos.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND cxp_rd_cargos.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND cxp_rd_cargos.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND cxp_rd_cargos.codestpro5 = spg_cuentas.codestpro5 ".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_deducciones
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las deducciones de una recepción de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT codded, numdoccom, monobjret, monret, sc_cuenta, porded, procede_doc,".
				"       (SELECT iva FROM tepuy_deducciones".
				"         WHERE cxp_rd_deducciones.codemp=tepuy_deducciones.codemp".
				"           AND cxp_rd_deducciones.codded=tepuy_deducciones.codded) AS iva".
				"  FROM cxp_rd_deducciones ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numrecdoc = '".$as_numrecdoc."'".
				"	AND codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(ced_bene) = '".$as_cedbene."'".
				"	AND trim(cod_pro) = '".$as_codpro."'".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND ced_bene='".$ls_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_select_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudpago($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudpago
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no en una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 18/07/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_dt_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND ced_bene='".$ls_cedbene."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_select_solicitudpago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_select_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,&$as_estprodoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_estatus
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que busca en la tabla de la recepcion el estatus de la misma
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT estprodoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND ced_bene='".$ls_cedbene."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_estatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estprodoc=$row["estprodoc"];
			}
		}
		return $lb_valido;
	}// end function uf_load_estatus
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion($as_numrecdoc,$as_numfactura,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,$ad_fecregdoc,
								 $ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,$as_numref,$as_procede,
								 $as_estlibcom,$as_estimpmun,$ai_totrowspg,$ai_totrowscg,$as_codfuefin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ai_totalgeneral  // Total General
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_cargos  // Total de Cargos
		//				   as_tipodestino  // Tipo Destino
		//				   as_numref  // Número de Referencia
		//				   as_procede  // Procede de la recepción de documentos
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   ai_totrowspg  // Total de Filas de Presupuesto
		//				   as_codfuefin  // Fuente de Financiamiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la recepción de documentos y sus detalles
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="INSERT INTO cxp_rd (codemp, numrecdoc, numfac, codtipdoc, ced_bene, cod_pro, codcla, dencondoc, fecemidoc, fecregdoc, ".
				"fecvendoc, montotdoc, mondeddoc, moncardoc, tipproben, numref, estprodoc, procede, estlibcom, estaprord, ".
				"fecaprord, usuaprord, estimpmun, codfuefin)  VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_numfactura."','".$as_codtipdoc."', ".
				"'".$as_cedbene."','".$as_codpro."','".$as_codcla."','".$as_dencondoc."','".$ad_fecemidoc."','".$ad_fecregdoc."', ".
				"'".$ad_fecvendoc."',".$ai_totalgeneral.",".$ai_deducciones.",".$ai_cargos.",'".$as_tipodestino."','".$as_numref."', ".
				"'R','".$as_procede."',".$as_estlibcom.",0,'1900-01-01','',".$as_estimpmun.",'".$as_codfuefin."')";	
				//print $ls_sql;
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Recepción de Documentos con Ficha:".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
/*			$this->io_rcbsf->io_ds_datos->insertRow("campo","montotdocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_totalgeneral);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","mondeddocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_deducciones);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","moncardocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_cargos);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			*//////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodestino,
													   $ad_fecregdoc,$ai_totrowspg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,"R",$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Recepción de Documentos fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Registrar la Recepción de Documentos."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodes,$ad_fechareg,$ai_totrowspg,
								  $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasspg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ai_totrowspg  // total de filas de cuentas SPG
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de presupuesto de una recepción ded documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowspg)&&($lb_valido);$li_i++)
		{
			$ls_nrocomp=$_POST["txtspgnrocomp".$li_i];
			$ls_programatica=$_POST["txtcodpro".$li_i];
			$ls_cuenta=$_POST["txtspgcuenta".$li_i];
			$ls_procede=$_POST["txtspgprocededoc".$li_i];
			$ls_codfuefin=$_POST["txtcodfuefin".$li_i];
			$li_moncue=$_POST["txtspgmonto".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);
			$li_monto_compromiso=0;
			$li_monto_ajuste=0;
			$li_monto_causado=0;
			$li_monto_anulado=0;
			$li_monto_recepcion=0;
			$li_monto_ordenpago=0;
			$li_monto_cargo=0;
			$li_monto_solicitud=0;
			$li_disponible=0;
			$ls_numcomanu="";
			$lb_valido=$this->uf_load_monto_comprobantes_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
											   					$ad_fechareg,$ls_programatica,$ls_cuenta,&$li_monto_compromiso);
			if($lb_valido)
			{											   			
				$lb_valido=$this->uf_load_monto_ajustes_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
															   $ls_programatica,$ls_cuenta,&$li_monto_ajuste);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_causados_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
																$ls_programatica,$ls_cuenta,&$li_monto_causado);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_comprobantes_anulados($ls_nrocomp,$as_tipodes,$as_codpro,$as_cedbene,$ad_fechareg,
																&$ls_numcomanu);
			}
			if(($lb_valido) &&($li_monto_causado>0))
			{
				$lb_valido=$this->uf_load_monto_anulados_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
																$ls_programatica,$ls_cuenta,&$li_monto_anulado);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_recepciones_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_cuenta,
																   &$li_monto_recepcion);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_ordenespago_directa_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_cuenta,
																		   &$li_monto_ordenpago);
			}
/*			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_cargos_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_cuenta,&$li_monto_cargo);
			}
*/			if($lb_valido)
			{
				$li_comprometido=$li_monto_compromiso+(($li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion);
//				$li_comprometido=$li_monto_compromiso+(($li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion-$li_monto_cargo);
				if($li_monto_compromiso>0)
				{
					$li_disponible=$li_comprometido-$li_moncue;
					$li_disponible=number_format($li_disponible,2,'.','');
				}
				else
				{
					$li_disponible=0;
				}
				if($li_disponible>=0)
				{
					$ls_sql="INSERT INTO cxp_rd_spg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,codestpro,".
							"spg_cuenta,monto,codfuefin) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."',".
							"'".$as_codpro."','".$as_cedbene."','".$ls_procede."','".$ls_nrocomp."','".$ls_programatica."', ".
							"'".$ls_cuenta."',".$li_moncue.",'".$ls_codfuefin."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cuentasspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion="Insertó la cuenta ".$ls_cuenta." Estructura ".$ls_programatica." a la Recepción ".$as_numrecdoc.
										" Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene."Proveedor ".$as_codpro.
										" Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
				/*		$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncue);
		
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocomp);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_programatica);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
						*/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
					}
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("ERROR-> Se esta causando Mas de lo comprometido en la cuenta ".$ls_cuenta); 
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasscg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ai_totrowscg  // total de filas de cuentas SCG
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de contabilidad de una recepción ded documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowscg)&&($lb_valido);$li_i++)
		{
			$ls_nrocomp=$_POST["txtscgnrocomp".$li_i];
			$ls_cuenta=$_POST["txtscgcuenta".$li_i];
			$ls_debhab=$_POST["txtdebhab".$li_i];
			$ls_estatus=$_POST["txtestatus".$li_i];
			$ls_procede=$_POST["txtscgprocededoc".$li_i];
			$li_estgenasi=0;
			if($ls_debhab=="D")
			{
				$li_moncue=$_POST["txtmondeb".$li_i];					
				$li_moncue=str_replace(".","",$li_moncue);
				$li_moncue=str_replace(",",".",$li_moncue);
			}
			else
			{
				$li_moncue=$_POST["txtmonhab".$li_i];					
				$li_moncue=str_replace(".","",$li_moncue);
				$li_moncue=str_replace(",",".",$li_moncue);
			}
			$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta, ".
					"monto,estgenasi,estasicon) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."', '".$as_codtipdoc."','".$as_codpro."', ".
					"'".$as_cedbene."','".$ls_procede."','".$ls_nrocomp."','".$ls_debhab."',".
					"'".$ls_cuenta."',".$li_moncue.",".$li_estgenasi.",'".$ls_estatus."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cuentasscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Insertó la cuenta ".$ls_cuenta." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
								" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			/*	$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncue);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocomp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
				*/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentasscg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los cargos de una recepción ded documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("cargos",$_SESSION))
		{
			$this->io_ds_cargos->data=$_SESSION["cargos"];
			$li_totrow=$this->io_ds_cargos->getRowCount('codcar');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				//if ($li_fila==1)$ls_codcar="00004";else
				// DETALLE AL REGISTRAR EL CODIGO DEL CARGO CUANDO SON VARIOS IVA//
				$ls_codcar=$this->io_ds_cargos->getValue('codcar',$li_fila);
				$ls_nrocomp=$this->io_ds_cargos->getValue('nrocomp',$li_fila);
				$li_baseimp=$this->io_ds_cargos->getValue('baseimp',$li_fila);
				$li_monimp=$this->io_ds_cargos->getValue('monimp',$li_fila);
				$ls_codpro=$this->io_ds_cargos->getValue('codpro',$li_fila);
				$ls_cuenta=$this->io_ds_cargos->getValue('cuenta',$li_fila);
				$ls_formula=$this->io_ds_cargos->getValue('formula',$li_fila);
				$li_porcar=$this->io_ds_cargos->getValue('porcar',$li_fila);
				$ls_procede=$this->io_ds_cargos->getValue('procededoc',$li_fila);
				$ls_sql="INSERT INTO cxp_rd_cargos (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codcar,procede_doc,numdoccom,".
						"monobjret,monret,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,porcar,formula) ".
					    " VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_codpro."', ".
					    "'".$as_cedbene."','".$ls_codcar."','".$ls_procede."','".$ls_nrocomp."',".$li_baseimp.",".$li_monimp.",".
					    "'".substr($ls_codpro,0,20)."','".substr($ls_codpro,20,6)."','".substr($ls_codpro,26,3)."',".
					    "'".substr($ls_codpro,29,2)."','".substr($ls_codpro,31,2)."','".$ls_cuenta."',".$li_porcar.",'".$ls_formula."')";
				//print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion="Insertó el cargo ".$ls_codcar." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
									" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
					/*$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_baseimp);
		
					$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monimp);
	
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocomp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					*/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deducciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las deducciones de una recepción ded documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("deducciones",$_SESSION))
		{
			$this->io_ds_deducciones->data=$_SESSION["deducciones"];
			$li_totrow=$this->io_ds_deducciones->getRowCount('codded');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_codded=$this->io_ds_deducciones->getValue('codded',$li_fila);
				$ls_nrocomp=$this->io_ds_deducciones->getValue('documento',$li_fila);
				$li_monobjret=$this->io_ds_deducciones->getValue('monobjret',$li_fila);
				$li_monret=$this->io_ds_deducciones->getValue('monret',$li_fila);
				$ls_sccuenta=$this->io_ds_deducciones->getValue('sccuenta',$li_fila);
				$li_porded=$this->io_ds_deducciones->getValue('porded',$li_fila);
				$ls_procede=$this->io_ds_deducciones->getValue('procededoc',$li_fila);
				$li_monobjret=str_replace(".","",$li_monobjret);
				$li_monobjret=str_replace(",",".",$li_monobjret);
				$li_monret=str_replace(".","",$li_monret);
				$li_monret=str_replace(",",".",$li_monret);

				$ls_sql="INSERT INTO cxp_rd_deducciones (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codded,procede_doc,numdoccom, ".
						"monobjret,monret,porded,sc_cuenta) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."', ".
						"'".$as_codpro."','".$as_cedbene."','".$ls_codded."','".$ls_procede."','".$ls_nrocomp."',".$li_monobjret.", ".
						"".$li_monret.",".$li_porded.",'".$ls_sccuenta."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion="Insertó la Deduccion ".$ls_codded." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
									" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
					/*$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monobjret);
		
					$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monret);
	
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocomp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					*/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,$as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ad_fecregdoc  // Fecha de Registro de la Recepción
		//				   as_estatus  // Estatus de la recepción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los movimientos históricos de una recepción ded documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
				" VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."',".
				"'".$ad_fecregdoc."','".$as_estatus."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el los históricos a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
							" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,$ad_fecregdoc,
								 $ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,$as_numref,$as_procede,
								 $as_estlibcom,$as_estimpmun,$ai_totrowspg,$ai_totrowscg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_recepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ai_totalgeneral  // Total General
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_cargos  // Total de Cargos
		//				   as_tipodestino  // Tipo Destino
		//				   as_numref  // Número de Referencia
		//				   as_procede  // Procede de la recepción de documentos
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   ai_totrowspg  // Total de Filas de Presupuesto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la recepción de documentos y sus detalles
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $ls_sql="UPDATE cxp_rd ".
				"   SET dencondoc='".$as_dencondoc."', ".
				"		codcla='".$as_codcla."', ".
				"       fecemidoc='".$ad_fecemidoc."', ".
				"       fecregdoc='".$ad_fecregdoc."', ".
				"       fecvendoc='".$ad_fecvendoc."', ".
				"		montotdoc=".$ai_totalgeneral.", ".
			    "       mondeddoc=".$ai_deducciones.", ".
				"		moncardoc=".$ai_cargos.", ".
				"       numref='".$as_numref."', ".
				"		estlibcom=".$as_estlibcom.",  ".
				"		estimpmun=".$as_estimpmun."  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"	AND cod_pro='".$as_codpro."' ".
				"	AND ced_bene='".$as_cedbene."' ";		  
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_update_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montotdocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_totalgeneral);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","mondeddocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_deducciones);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","moncardocaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_cargos);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			*/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			if($lb_valido)
			{	
				$lb_valido=$this->uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodestino,
													   $ad_fecregdoc,$ai_totrowspg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,"R",$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Recepción de Documentos fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Recepción de Documentos."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detallesrecepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una recepcion
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_cargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"	AND cod_pro='".$as_codpro."' ".
				"	AND ced_bene='".$as_cedbene."'";		  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_deducciones ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND numrecdoc='".$as_numrecdoc."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND ced_bene='".$as_cedbene."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_scg ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND numrecdoc='".$as_numrecdoc."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND ced_bene='".$as_cedbene."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_spg ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND numrecdoc='".$as_numrecdoc."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND ced_bene='".$as_cedbene."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_historico_rd ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND numrecdoc='".$as_numrecdoc."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND ced_bene='".$as_cedbene."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_detallesrecepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ad_fecregdoc,$ad_fecvendoc,
						$ad_fecemidoc,$as_codcla,$as_dencondoc,$as_procede,$ai_cargos,$ai_deducciones,$ai_totalgeneral,
						$as_numref,$as_estimpmun,$as_estlibcom,$ai_totrowspg,$ai_totrowscg,$as_codfuefin,$as_numfactura,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_cxp_p_recepcion.php)
		//	    Arguments: as_existe  //  Si el registro exite ó si es nuevo
		//				   as_numrecdoc  // Número de ficha de documentos
		//				   as_tipodestino  // Tipo Destino
		//				   as_codprovben  // Código de proveedor ó beneficiario
		//				   as_codtipdoc  // Tipo de Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   as_procede  // Procede de la recepción de documentos
		//				   ai_cargos  // Total de Cargos
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_totalgeneral  // Total General
		//				   as_numref  // Número de Referencia
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   ai_totrowspg  // total de filas de Presupuesto
		//				   ai_totrowscg  // total de filas de Contabilidad
		//				   as_codfuefin // Fuente de Financiamiento
		//				   as_numfactura // Numero de Factura
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la recepción
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$as_codtipdoc=substr($as_codtipdoc,0,5);
		$lb_encontrado=$this->uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
		$ad_fecregdoc=$this->io_funciones->uf_convertirdatetobd($ad_fecregdoc);
		$ad_fecvendoc=$this->io_funciones->uf_convertirdatetobd($ad_fecvendoc);
		$ad_fecemidoc=$this->io_funciones->uf_convertirdatetobd($ad_fecemidoc);
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		switch ($as_existe)
		{
			case "FALSE":
				if(!($lb_encontrado))
				{
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregdoc,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error." Para la fecha de Recepción.");           
						return false;
					}                    
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecvendoc,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error." Para la fecha de Vencimiento.");           
						return false;
					}                    

					$lb_valido=$this->uf_insert_recepcion($as_numrecdoc,$as_numfactura,$as_codtipdoc,$ls_cedbene,$ls_codpro,$as_codcla,$as_dencondoc,
														  $ad_fecemidoc,$ad_fecregdoc,$ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,
														  $ai_cargos,$as_tipodestino,$as_numref,$as_procede,$as_estlibcom,$as_estimpmun,
														  $ai_totrowspg,$ai_totrowscg,$as_codfuefin,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Recepción de Documentos ya existe, no la puede incluir.");
					return false;
				}
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$ls_estprodoc="";
					$lb_valido=$this->uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ls_estprodoc);
					if($ls_estprodoc!="R")
					{
						$this->io_mensajes->message("La Recepción de Documentos no se puede modificar, Tiene Movimientos.");           
						return false;
					}
					$lb_valido=$this->uf_update_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$as_codcla,$as_dencondoc,
														  $ad_fecemidoc,$ad_fecregdoc,$ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,
														  $ai_cargos,$as_tipodestino,$as_numref,$as_procede,$as_estlibcom,$as_estimpmun,
														  $ai_totrowspg,$ai_totrowscg,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Recepción de Documentos no existe, no la puede actualizar.");
				}
				break;
		}
		if($lb_valido)
		{
			if((array_key_exists("ls_ajuste",$_SESSION)))
			{
				if($_SESSION["ls_ajuste"]!="")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion=$_SESSION["ls_ajuste"]." Para la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$ls_cedbene.
									 "Proveedor ".$ls_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				}
			}
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public (tepuy_cxp_p_recepcion.php)
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_tipodestino  // Tipo Destino
		//				   as_codprovben  // Código de proveedor ó beneficiario
		//				   as_codtipdoc  // Tipo de Documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y elimina la recepción
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 07/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$as_codtipdoc=substr($as_codtipdoc,0,5);
		$lb_encontrado=$this->uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
		if($lb_encontrado)
		{
			$lb_encontrado=$this->uf_select_solicitudpago($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
			if($lb_encontrado===false)
			{
				$this->io_sql->begin_transaction();				
				$lb_valido=$this->uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ls_estprodoc);
				if($ls_estprodoc!="R")
				{
					$this->io_mensajes->message("La Recepción de Documentos no se puede eliminar, Tiene Movimientos.");           
					$lb_valido=false;
				}
				switch($as_tipodestino)
				{
					case "P":
						 $ls_codpro=$as_codprovben;
						 $ls_cedbene="----------";
						 break;
					case "B":
						 $ls_codpro ="----------";
						 $ls_cedbene=$as_codprovben;
						 break;
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$ls_sql="DELETE FROM cxp_rd ".
							" WHERE codemp='".$this->ls_codemp."' ".
							"	AND numrecdoc='".$as_numrecdoc."' ".
							"	AND codtipdoc='".$as_codtipdoc."' ".
							"	AND cod_pro='".$ls_codpro."' ".
							"	AND ced_bene='".$ls_cedbene."' ";		  
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_eliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$ls_cedbene.
										 "Proveedor ".$ls_codpro." Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					}	
				}
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Recepción de Documentos fue eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$this->io_mensajes->message("Ocurrio un Error al Eliminar la Recepción de Documentos."); 
					$this->io_sql->rollback();
				}
			}
			else
			{
				$this->io_mensajes->message("La Recepción de Documentos existe en una Solicitud de Pago.");
				$lb_valido=false;	
			}
		}
		else
		{
			$this->io_mensajes->message("La Recepción de Documentos no existe, no la puede eliminar.");
			$lb_valido=false;	
		}
		return $lb_valido;
	}// end function uf_delete
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_pago($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudes_pago
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si hay solicitudes de pago asociadas a la Recepción de Documento.
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codemp ".
				"  FROM cxp_dt_solicitudes ".
		  		" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc = '".$as_numrecdoc."' ".
				"   AND codtipdoc = '".$as_codtipdoc."' ".
		  		"   AND cod_pro = '".$as_codpro."' ".
				"   AND ced_bene = '".$as_cedbene."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_select_solicitudes_pago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_solicitudes_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_comprobantes_positivos($as_tipodestino,$as_codpro,$as_cedbene,$as_fechahasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_positivos
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_operacion="CS";
		$ls_sql="SELECT DISTINCT tepuy_cmp.procede, tepuy_cmp.comprobante, tepuy_cmp.fecha, tepuy_cmp.descripcion, ".
				"				  tepuy_cmp.total ".
				"  FROM tepuy_cmp, spg_dt_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".trim($as_codpro)."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".trim($as_cedbene)."' ".
				"   AND tepuy_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha>=spg_dt_cmp.fecha ".
				" ORDER BY tepuy_cmp.comprobante ASC";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_positivos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_comprobantes_positivos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_comprobantes_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
											   $as_fechahasta,$as_programatica,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_comprobantes_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//	  			   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_operacion="CS";
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				"  FROM tepuy_cmp, spg_dt_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND tepuy_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_comprobantes_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_monto_comprobantes_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ajustes($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_positivos
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto negativo.      
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion="CS";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND spg_dt_cmp.monto < 0 ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_positivos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ajustes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ajustes_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										  $as_programatica,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ajustes_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto negativo.      
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion="CS";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND spg_dt_cmp.monto < 0 ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ajustes_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ajustes_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causados($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causados
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' Gasto Causado y Pagado 
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion1="GC";
		$ls_operacion2="CP";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causados_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										   $as_programatica,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causados_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CG' Compromiso y Gasto Causado y 'CP' Gasto Causado y Pagado 
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion1="CG";
		$ls_operacion2="CP";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causados_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causados_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_comprobantes_anulados($as_comprobante,$as_tipodestino,$as_codpro,$as_cedbene,$as_fechahasta,&$aa_numcomanu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_anulados
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GS' Gasto Causado hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_numcomanu="";
		$ls_operacion="GC";
		$ls_sql="SELECT DISTINCT tepuy_cmp.comprobante ".
				"  FROM tepuy_cmp, spg_dt_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND tepuy_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
//print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_anulados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$aa_numcomanu[$li_i]=$row["comprobante"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_comprobantes_anulados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_anulados($aa_numcomanu,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_anulados
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: aa_numcomanu  // Arreglos con  Números de Comprobantes Anulados
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' compromiso simple
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// OJO VERIFICAR SENTENCIA CON UN SELECT QUE NO ENTIENDO EN EL CÓDIGO VIEJO
		$ai_monto=0;
		$lb_valido=true; 
		$ls_operacion1="GC";
		$as_procedencia='CXPSOP';
		$ls_operacion2="CP";
		$li_numrows=count($aa_numcomanu);
		if($li_numrows>=1)
		{
			for($li_i=1;$li_i<=$li_numrows;$li_i++)
			{
				if($li_i==1)
				{
					$ls_comprobante= "AND (spg_dt_cmp.documento='".$aa_numcomanu[$li_i]."'";
				}
				else
				{
					$ls_comprobante=$ls_comprobante. "OR spg_dt_cmp.documento='".$aa_numcomanu[$li_i]."'";
				
				}
			}
			$ls_comprobante=$ls_comprobante.")";
		}
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				$ls_comprobante.
//				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
//print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_anulados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_anulados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_anulados_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										   $as_programatica,$as_spguenta,&$ai_monto) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_anulados_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' compromiso simple
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// OJO VERIFICAR SENTENCIA CON UN SELECT QUE NO ENTIENDO EN EL CÓDIGO VIEJO
		$ai_monto=0;
		$lb_valido=true; 
		$ls_operacion1="GC";
		$as_procedencia='CXPSOP';
		$ls_operacion2="CP";
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="SELECT SUM(spg_dt_cmp.monto) AS monto ".
				" FROM spg_dt_cmp, tepuy_cmp ".
				" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(tepuy_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(tepuy_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_anulados_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_anulados_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_recepciones($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_recepciones
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las Recepciones
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las recepciones asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT sum(cxp_rd_spg.monto) AS monto ".
				"  FROM cxp_rd_spg,cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"   AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc ".
				"   AND cxp_rd.numrecdoc NOT IN (SELECT cxp_dt_solicitudes.numrecdoc".
				"						    	   FROM cxp_dt_solicitudes,cxp_solicitudes".
				"						   		  WHERE cxp_solicitudes.estprosol<>'E' ".
				"							 		AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"							 		AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   						 		AND cxp_rd.codemp=cxp_dt_solicitudes.codemp ".
				"   						 		AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro ".
				"   					     		AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene ".
				"   						 		AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
				"   						 		AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc) ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_recepciones_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_recepciones_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las Recepciones
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las recepciones asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT sum(cxp_rd_spg.monto) AS monto ".
				"  FROM cxp_rd_spg,cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"	AND cxp_rd_spg.codestpro='".$as_programatica."' ".
				"	AND cxp_rd_spg.spg_cuenta='".$as_spguenta."' ".
				"   AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"   AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc ".
				"   AND cxp_rd.numrecdoc NOT IN (SELECT cxp_dt_solicitudes.numrecdoc".
				"						    	   FROM cxp_dt_solicitudes,cxp_solicitudes".
				"						   		  WHERE cxp_solicitudes.estprosol<>'E' ".
				"							 		AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"							 		AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   						 		AND cxp_rd.codemp=cxp_dt_solicitudes.codemp ".
				"   						 		AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro ".
				"   					     		AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene ".
				"   						 		AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
				"   						 		AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc) ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_recepciones_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_recepciones_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ordenespago_directa($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ordenespago_directa
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las Ordenes de pago asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT sum(scb_movbco_spgop.monto) as monto ".
				"  FROM scb_movbco_spgop, scb_movbco ".
				" WHERE scb_movbco.codemp='".$this->ls_codemp."' ".
				"   AND scb_movbco_spgop.procede_doc='".$as_procedencia."' ".
				"	AND scb_movbco_spgop.documento='".$as_comprobante."' ".
				"   AND scb_movbco.codemp=scb_movbco_spgop.codemp ".
				"   AND scb_movbco.numdoc=scb_movbco_spgop.numdoc ".
				"   AND scb_movbco.codope=scb_movbco_spgop.codope ".
				"   AND scb_movbco.codban=scb_movbco_spgop.codban ".
				"   AND scb_movbco.ctaban=scb_movbco_spgop.ctaban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ordenespago_directa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ordenespago_directa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ordenespago_directa_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ordenespago_directa
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las Ordenes de pago asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT sum(scb_movbco_spgop.monto) as monto ".
				"  FROM scb_movbco_spgop, scb_movbco ".
				" WHERE scb_movbco.codemp='".$this->ls_codemp."' ".
				"   AND scb_movbco_spgop.procede_doc='".$as_procedencia."' ".
				"	AND scb_movbco_spgop.documento='".$as_comprobante."' ".
				"	AND scb_movbco_spgop.codestpro='".$as_programatica."' ".
				"	AND scb_movbco_spgop.spg_cuenta='".$as_spguenta."' ".
				"   AND scb_movbco.codemp=scb_movbco_spgop.codemp ".
				"   AND scb_movbco.numdoc=scb_movbco_spgop.numdoc ".
				"   AND scb_movbco.codope=scb_movbco_spgop.codope ".
				"   AND scb_movbco.codban=scb_movbco_spgop.codban ".
				"   AND scb_movbco.ctaban=scb_movbco_spgop.ctaban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ordenespago_directa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ordenespago_directa_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_cargos($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_cargos
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT sum(cxp_rd_cargos.monret) AS monto     ".
				"  FROM cxp_rd_cargos, cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd.codemp=cxp_rd_cargos.codemp ".
				"	AND	cxp_rd.numrecdoc=cxp_rd_cargos.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_cargos.codtipdoc ".
				"   AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro ".
				"   AND cxp_rd.ced_bene=cxp_rd_cargos.ced_bene ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_cargos_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_cargos_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="SELECT sum(cxp_rd_cargos.monret) AS monto     ".
				"  FROM cxp_rd_cargos, cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
  		     	"   AND cxp_rd_cargos.codestpro1='".$ls_codestpro1."' ".
				"   AND cxp_rd_cargos.codestpro2='".$ls_codestpro2."' ".
			 	"   AND cxp_rd_cargos.codestpro3='".$ls_codestpro3."' ".
				"   AND cxp_rd_cargos.codestpro4='".$ls_codestpro4."' ".
			 	"   AND cxp_rd_cargos.codestpro5='".$ls_codestpro5."' ".
				"   AND cxp_rd_cargos.spg_cuenta='".$as_spguenta."' ".
				"   AND cxp_rd.codemp=cxp_rd_cargos.codemp ".
				"	AND	cxp_rd.numrecdoc=cxp_rd_cargos.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_cargos.codtipdoc ".
				"   AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro ".
				"   AND cxp_rd.ced_bene=cxp_rd_cargos.ced_bene ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_cargos_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_cargos_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_acumulado_solicitudes($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_acumulado_solicitudes
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numrecdoc  // Número de Recepcion
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT SUM(cxp_dt_solicitudes.monto) AS monto ".
			    "  FROM cxp_rd, cxp_dt_solicitudes ".
			    " WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."'  ".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."' ".
			    "   AND cxp_rd.cod_pro='".$as_codpro."' ".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'  ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp  ".
			    "   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc ".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_acumulado_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_acumulado_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causado_anterior($as_comprobante,$as_procede,$as_spgcuenta,$as_codestpro,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causado_anterior
		//		   Access: public (tepuy_cxp_c_recepcion_ajax.php)
		//	    Arguments: as_comprobante  // Número de comprobante
		//				   as_procede  // Procede de la cuenta
		//				   as_spgcuenta  // Cuenta del movimiento
		//				   as_codestpro  // Código de Programatica
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de los montos causadoas anteriormente
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 21/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(SUM(cxp_rd_spg.monto),0) AS monto ".
				"  FROM cxp_rd_spg, cxp_rd ".
				" WHERE cxp_rd_spg.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procede."' ".
				"   AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd_spg.spg_cuenta='".$as_spgcuenta."' ".
				"   AND cxp_rd_spg.codestpro='".$as_codestpro."' ". 
				"   AND cxp_rd_spg.codemp=cxp_rd.codemp ".
				"   AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc ".
				"   AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc ".
				"   AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene ".
				"   AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro ".
				"   AND cxp_rd.estprodoc<>'A' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causado_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causado_anterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargo_causado_anterior($as_codcar,$as_comprobante,$as_procede,$as_codestpro1,$as_codestpro2,$as_codestpro3,
											$as_codestpro4,$as_codestpro5,$as_cuenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargo_causado_anterior
		//		   Access: public (tepuy_cxp_c_recepcion_ajax.php)
		//	    Arguments: as_codcar  // Codigo de Cargo
		//				   as_comprobante  // Numero de compromiso
		//				   as_procede  // Procede del Documento
		//				   as_codestpro1  // Código de Programatica Nivel 1
		//				   as_codestpro2  // Código de Programatica Nivel 2
		//				   as_codestpro3  // Código de Programatica Nivel 3
		//				   as_codestpro4  // Código de Programatica Nivel 4
		//				   as_codestpro5  // Código de Programatica Nivel 5
		//				   as_cuenta     // Cuenta Presupuestaria 
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de los montos de los cargos causadoas anteriormente
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 21/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(SUM(cxp_rd_cargos.monret),0) AS monto ".
				"  FROM cxp_rd_cargos, cxp_rd ".
				" WHERE cxp_rd_cargos.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.codcar='".$as_codcar."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procede."' ".
				"   AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd_cargos.spg_cuenta='".$as_cuenta."' ".
				"   AND cxp_rd_cargos.codestpro1='".$as_codestpro1."' ". 
				"   AND cxp_rd_cargos.codestpro2='".$as_codestpro2."' ". 
				"   AND cxp_rd_cargos.codestpro3='".$as_codestpro3."' ". 
				"   AND cxp_rd_cargos.codestpro4='".$as_codestpro4."' ". 
				"   AND cxp_rd_cargos.codestpro5='".$as_codestpro5."' ". 
				"   AND cxp_rd_cargos.codemp=cxp_rd.codemp ".
				"   AND cxp_rd_cargos.numrecdoc=cxp_rd.numrecdoc ".
				"   AND cxp_rd_cargos.codtipdoc=cxp_rd.codtipdoc ".
				"   AND cxp_rd_cargos.ced_bene=cxp_rd.ced_bene ".
				"   AND cxp_rd_cargos.cod_pro=cxp_rd.cod_pro ".
				"   AND cxp_rd.estprodoc<>'A' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargo_causado_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_cargo_causado_anterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_sep($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_sep
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_cuentagasto.numsol AS comprobante, sep_cuentagasto.codestpro1, sep_cuentagasto.codestpro2, ".
				"		sep_cuentagasto.codestpro3, sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, ".
				"		sep_cuentagasto.spg_cuenta, sep_cuentagasto.monto, spg_cuentas.sc_cuenta, '--' AS codfuefin, ".
				"		(SELECT COUNT(codemp) FROM sep_solicitudcargos ".
				"		  WHERE sep_solicitudcargos.codemp = sep_cuentagasto.codemp  ".
				"			AND sep_solicitudcargos.numsol = sep_cuentagasto.numsol  ".
				"			AND sep_solicitudcargos.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND sep_solicitudcargos.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND sep_solicitudcargos.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND sep_solicitudcargos.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND sep_solicitudcargos.codestpro5 = sep_cuentagasto.codestpro5 ".
				"			AND sep_solicitudcargos.spg_cuenta = spg_cuentas.spg_cuenta ) AS cargo ".
				"  FROM sep_cuentagasto, spg_cuentas ".
				" WHERE sep_cuentagasto.codemp = '".$this->ls_codemp."' ".
				"   AND sep_cuentagasto.numsol = '".$as_numsol."' ".
				"   AND sep_cuentagasto.codemp = spg_cuentas.codemp ".
				"   AND sep_cuentagasto.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND sep_cuentagasto.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND sep_cuentagasto.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND sep_cuentagasto.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND sep_cuentagasto.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND sep_cuentagasto.codestpro5 = spg_cuentas.codestpro5 ";
				//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso_sep($as_numsol,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso_sep
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_sql="SELECT sep_solicitudcargos.codcar, sep_solicitudcargos.numsol, sep_solicitudcargos.codestpro1, ".
				"		sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, sep_solicitudcargos.codestpro4, ".
				"		sep_solicitudcargos.codestpro5, sep_solicitudcargos.spg_cuenta, sep_solicitudcargos.sc_cuenta, ".
				"		sep_solicitudcargos.formula, sep_solicitudcargos.monobjret, sep_solicitudcargos.monto, tepuy_cargos.porcar ".
				"  FROM sep_solicitudcargos, tepuy_cargos ".
				" WHERE sep_solicitudcargos.codemp='".$this->ls_codemp."' ".
				"   AND sep_solicitudcargos.numsol='".$as_numsol."' ".
				"   AND sep_solicitudcargos.codcar=tepuy_cargos.codcar";
				//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numsol"];
				$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$ls_procede="SEPSPC";
				$ls_cargo="1";
				$li_original=$row["monto"];
				$li_baseimp=$row["monobjret"];
				$li_monto_anterior=0;
				$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$li_monimp=$row["monto"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);	
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
				}		
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}		
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_soc($as_numordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_soc
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de Orden de Compra
		//	    		   as_estcondat  // estatus si la Orden de compra es de bienes ó de servicios
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una Orden de Compra
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 15/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT soc_cuentagasto.numordcom AS comprobante, soc_cuentagasto.codestpro1, soc_cuentagasto.codestpro2, ".
				"		soc_cuentagasto.codestpro3, soc_cuentagasto.codestpro4, soc_cuentagasto.codestpro5, ".
				"		soc_cuentagasto.spg_cuenta, soc_cuentagasto.monto, spg_cuentas.sc_cuenta, '--' AS codfuefin, ".
				"		(SELECT COUNT(codemp) FROM soc_solicitudcargos ".
				"		  WHERE soc_solicitudcargos.codemp = soc_cuentagasto.codemp  ".
				"			AND soc_solicitudcargos.numordcom = soc_cuentagasto.numordcom  ".
				"			AND soc_solicitudcargos.estcondat = soc_cuentagasto.estcondat  ".
				"			AND soc_solicitudcargos.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND soc_solicitudcargos.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND soc_solicitudcargos.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND soc_solicitudcargos.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND soc_solicitudcargos.codestpro5 = soc_cuentagasto.codestpro5 ".
				"			AND soc_solicitudcargos.spg_cuenta = spg_cuentas.spg_cuenta ) AS cargo ".
				"  FROM soc_cuentagasto, spg_cuentas ".
				" WHERE soc_cuentagasto.codemp = '".$this->ls_codemp."' ".
				"   AND soc_cuentagasto.numordcom = '".$as_numordcom."' ".
				"   AND soc_cuentagasto.estcondat = '".$as_estcondat."' ".
				"   AND soc_cuentagasto.codemp = spg_cuentas.codemp ".
				"   AND soc_cuentagasto.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND soc_cuentagasto.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND soc_cuentagasto.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND soc_cuentagasto.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND soc_cuentagasto.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND soc_cuentagasto.codestpro5 = spg_cuentas.codestpro5 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_soc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso_soc($as_numordcom,$as_estcondat,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso_soc
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de la Orden ded Compra
		//				   as_estcondat  // Estatus de la Orden de Compra si es de Bienes ó de Servicios
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una Orden de Compra
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 15/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_sql="SELECT soc_solicitudcargos.codcar, soc_solicitudcargos.numordcom, soc_solicitudcargos.codestpro1, ".
				"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
				"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
				"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, tepuy_cargos.porcar ".
				"  FROM soc_solicitudcargos, tepuy_cargos ".
				" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
				"   AND soc_solicitudcargos.numordcom='".$as_numordcom."' ".
				"   AND soc_solicitudcargos.estcondat='".$as_estcondat."' ".
				"   AND soc_solicitudcargos.codcar=tepuy_cargos.codcar";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numordcom"];
				$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$li_monto_anterior=0;
				switch($as_estcondat)
				{
					case "B":
						$ls_procede="SOCCOC";
						break;
					case "S":
						$ls_procede="SOCCOS";
						break;
				}
				$ls_cargo="1";
				$li_original=$row["monret"];
				$li_baseimp=$row["monobjret"];
//				$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,$row["codestpro1"],
															     $row["codestpro2"],$row["codestpro3"],$row["codestpro4"],
																 $row["codestpro5"],$ls_cuenta,&$li_monto_anterior);
				$li_monimp=$row["monret"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
	
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);			
				}
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_sno($as_comprobante)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_sno
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a un compromiso que viene de Mómina
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT tepuy_cmp.comprobante, spg_dt_cmp.codestpro1, spg_dt_cmp.codestpro2, ".
				"		spg_dt_cmp.codestpro3, spg_dt_cmp.codestpro4, spg_dt_cmp.codestpro5, ".
				"		spg_dt_cmp.spg_cuenta, spg_dt_cmp.monto, spg_cuentas.sc_cuenta, '0' AS cargo, '--' AS codfuefin ".
			  	"  FROM spg_dt_cmp, tepuy_cmp, spg_cuentas  ".
			  	" WHERE tepuy_cmp.codemp='".$this->ls_codemp."' ".
				"	AND tepuy_cmp.comprobante='".$as_comprobante."' ".
				"   AND tepuy_cmp.procede='SNOCNO' ".
			  	"   AND tepuy_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND tepuy_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND tepuy_cmp.procede=spg_dt_cmp.procede ".
				"   AND tepuy_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND tepuy_cmp.codban=spg_dt_cmp.codban ".
				"   AND tepuy_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND spg_dt_cmp.codemp = spg_cuentas.codemp".
				"   AND spg_dt_cmp.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND spg_dt_cmp.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND spg_dt_cmp.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND spg_dt_cmp.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND spg_dt_cmp.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND spg_dt_cmp.codestpro5 = spg_cuentas.codestpro5 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_sno
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso($as_numero,$as_procede,&$ao_ds_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso
		//		   Access: public (tepuy_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numero  // Número de comprobante
		//				   as_procede  // procede del documento
		//				   ao_ds_cargos  // Datastored de Cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 05/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sql="";
		switch($as_procede)
		{
			case "SOCCOC": // Orden de Compra de Bienes
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, tepuy_cargos.porcar ".
						"  FROM soc_solicitudcargos, tepuy_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='B' ".
						"   AND soc_solicitudcargos.codcar=tepuy_cargos.codcar"; 
				break;
			
			case "SOCCOS": // Orden de Compra de Servicios
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, tepuy_cargos.porcar ".
						"  FROM soc_solicitudcargos, tepuy_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='S' ".
						"   AND soc_solicitudcargos.codcar=tepuy_cargos.codcar"; 
				break;

			case "SEPSPC": // Solicitud de Ejecución Presupuestaria
				$ls_sql="SELECT sep_solicitudcargos.codcar, (sep_solicitudcargos.numsol) AS compromiso, sep_solicitudcargos.codestpro1, ".
						"		sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, sep_solicitudcargos.codestpro4, ".
						"		sep_solicitudcargos.codestpro5, sep_solicitudcargos.spg_cuenta, sep_solicitudcargos.sc_cuenta, ".
						"		sep_solicitudcargos.formula, sep_solicitudcargos.monobjret, sep_solicitudcargos.monto, tepuy_cargos.porcar ".
						"  FROM sep_solicitudcargos, tepuy_cargos ".
						" WHERE sep_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND sep_solicitudcargos.numsol='".$as_numero."' ".
						"   AND sep_solicitudcargos.codcar=tepuy_cargos.codcar"; 
				break;
		}
		if($ls_sql!="")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_valido=false; 
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$lb_existe=true;
					$ls_codcar=$row["codcar"];
					$ls_nrocomp=$row["compromiso"];
					$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
					$ls_cuenta=$row["spg_cuenta"];
					$li_baseimp=$row["monobjret"];
					$li_monto_anterior=0;
					$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$as_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
					$ls_codfuefin="--";
					$li_monimp=$row["monret"]-$li_monto_anterior;
					if($lb_valido)
					{
						$ao_ds_cargos->insertRow("codcar",$ls_codcar);			
						$ao_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
						$ao_ds_cargos->insertRow("codpro",$ls_codpro);			
						$ao_ds_cargos->insertRow("cuenta",$ls_cuenta);	
						$ao_ds_cargos->insertRow("procededoc",$as_procede);	
						$ao_ds_cargos->insertRow("baseimp",$li_baseimp);	
						$ao_ds_cargos->insertRow("monimp",$li_monimp);	
						$ao_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
					}		
				}
				$this->io_sql->free_result($rs_data);
			}		
		}
		return $lb_valido;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
