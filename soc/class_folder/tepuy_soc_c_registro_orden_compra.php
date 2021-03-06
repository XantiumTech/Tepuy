<?php
class tepuy_soc_c_registro_orden_compra
{
  function tepuy_soc_c_registro_orden_compra($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: tepuy_soc_c_registro_orden_compra
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Miguel Palencia.
	// Fecha Creaci�n: 14/04/2007 								Fecha �ltima Modificaci�n : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
		require_once($as_path."shared/class_folder/class_sql.php");
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/tepuy_include.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		require_once($as_path."shared/class_folder/tepuy_c_generar_consecutivo.php");
		
		$io_include			  = new tepuy_include();
		$io_conexion		  = $io_include->uf_conectar();
		$this->io_sql         = new class_sql($io_conexion);	
		$this->io_mensajes    = new class_mensajes();		
		$this->io_funciones   = new class_funciones();	
		$this->io_seguridad   = new tepuy_c_seguridad();
		$this->io_fecha       = new class_fecha();
		$this->io_dscuentas   = new class_datastore();
		$this->io_dscargos    = new class_datastore();
		$this->io_dssolicitud = new class_datastore();
		$this->ls_codemp      = $_SESSION["la_empresa"]["codemp"]; 
		$this->io_id_process  = new tepuy_c_generar_consecutivo();		
  }
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 14/04/2007 								Fecha �ltima Modificaci�n : 
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
	function uf_soc_combo_paises($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_soc_combo_paises
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Funci�n que busca en la tabla los paises registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codpai,despai ".
                " FROM  tepuy_pais    ".
				" WHERE codpai<>'---'  ".
                " ORDER BY despai ASC  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_soc_combo_paises ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbpais' id='cmbpais' style='width:120px' onChange='javascript: ue_cambiar_estado();'>";
			print " <option value='---'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codpai=trim($row["codpai"]);
				$ls_despai=utf8_encode(trim($row["despai"]));
				if($as_seleccionado==$ls_codpai."-".$ls_despai)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codpai."' ".$ls_seleccionado.">".$ls_despai."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_soc_combo_paises
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_soc_combo_estado($as_seleccionado,$as_codpai)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_soc_combo_estado
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Funci�n que busca en la tabla los paises registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $ls_sql=" SELECT codest,desest                   ".
                " FROM   tepuy_estados                  ".
                " WHERE  codpai='".$as_codpai."' AND     ".
				"        codpai<>'---' AND codest<>'---' ".
                " ORDER BY desest ASC            ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_soc_combo_estado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbestado' id='cmbestado' style='width:120px' onChange='javascript: ue_cambiar_municipio();'>";
			print " <option value='---'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codest=trim($row["codest"]);
				$ls_desest=utf8_encode(trim($row["desest"]));
				if($as_seleccionado==$ls_desest)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codest."' ".$ls_seleccionado.">".$ls_desest."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_soc_combo_estado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_soc_combo_municipio($as_seleccionado,$as_codpai,$as_codest)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_soc_combo_municipio
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Funci�n que busca en la tabla los paises registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codmun,denmun                                           ".
                " FROM   tepuy_municipio                                        ".
                " WHERE  codpai='".$as_codpai."' AND codest='".$as_codest."' AND ".
				"        codpai<>'---' AND codest<>'---' AND  codmun<>'---'      ".
                " ORDER BY denmun                                                ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_soc_combo_municipio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbmunicipio' id='cmbmunicipio' style='width:120px' onChange='javascript: ue_cambiar_parroquia();'>";
			print " <option value='---'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codmun=trim($row["codmun"]);
				$ls_denmun=utf8_encode(trim($row["denmun"]));
				if($as_seleccionado==$ls_denmun)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codmun."' ".$ls_seleccionado.">".$ls_denmun."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_soc_combo_municipio
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_soc_combo_parroquia($as_seleccionado,$as_codpai,$as_codest,$as_codmun)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_soc_combo_municipio
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Funci�n que busca en la tabla los paises registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codpar,denpar                                                   ".
                " FROM   tepuy_parroquia                                    ".
                " WHERE  codpai='".$as_codpai."' AND codest='".$as_codest."' ".
				"        AND codmun='".$as_codmun."'  AND codpai<>'---' AND  ".
				"        codest<>'---' AND  codmun<>'---'                    ".
                " ORDER BY denpar                                            ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_soc_combo_parroquia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbparroquia' style='width:120px' id='cmbparroquia' >";
			print " <option value='---'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codpar=trim($row["codpar"]);
				$ls_denpar=utf8_encode(trim($row["denpar"]));
				if($as_seleccionado==$ls_denmun)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codpar."' ".$ls_seleccionado.">".$ls_denpar."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_soc_combo_parroquia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numero,$as_tipsol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: public
		//		 Argument: as_numero ---> n�mero de solicitud o la orden de compra
		//                 as_tipsol ---> tipo de solicitud sep o soc
		//	  Description: Funci�n que busca los bienes asociados a una solicitud o un aorden de compra segun 
		//                 el parametro tipo solicitud
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 12/05/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
		  case 'MYSQL':
		    $ls_sqlaux = "(SELECT CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='0001' AND spg_unidadadministrativa.codemp=sep_solicitud.codemp AND spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) as estpreunieje";
		    $ls_sqlaux1 = "(SELECT CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='0001' AND spg_unidadadministrativa.codemp=soc_ordencompra.codemp AND spg_unidadadministrativa.coduniadm=soc_ordencompra.coduniadm) as estpreunieje";
		  break;
		  case 'POSTGRE':
		    $ls_sqlaux = "(SELECT codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='0001' AND spg_unidadadministrativa.codemp=sep_solicitud.codemp AND spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) as estpreunieje";
		    $ls_sqlaux1 = "(SELECT codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='0001' AND spg_unidadadministrativa.codemp=soc_ordencompra.codemp AND spg_unidadadministrativa.coduniadm=soc_ordencompra.coduniadm) as estpreunieje";
		  break;
		}
		switch ($as_tipsol)
		{
		  case 'SEP':
				$ls_sql="SELECT sep_dt_articulos.codart, sep_dt_articulos.canart, sep_dt_articulos.unidad, ".
				        "       sep_dt_articulos.monpre, sep_dt_articulos.monart, sep_dt_articulos.orden,  ".
						"		TRIM(sep_dt_articulos.spg_cuenta) AS spg_cuenta, siv_articulo.denart, ".
						"		siv_unidadmedida.unidad AS unimed, sep_solicitud.codfuefin, ".
						"       sep_solicitud.coduniadm, siv_unidadmedida.denunimed,".
					    "        (SELECT denuniadm        ". 
                        "         FROM  spg_unidadadministrativa ".
       				    "		  WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND ".
             		    "               spg_unidadadministrativa.codemp=sep_solicitud.codemp AND ".
                        "               spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) AS denuniadm, $ls_sqlaux".
						"  FROM sep_dt_articulos, siv_articulo, siv_unidadmedida, sep_solicitud ".
						" WHERE sep_dt_articulos.codemp = '".$this->ls_codemp."' AND ".
						"       sep_dt_articulos.numsol = '".$as_numero."' AND ".
						"       sep_dt_articulos.estincite = 'NI' AND ".
						"       sep_dt_articulos.numsol = sep_solicitud.numsol AND ".
						"       sep_dt_articulos.codemp = sep_solicitud.codemp AND ".
						"       sep_dt_articulos.codemp = siv_articulo.codemp  AND ".
						"       sep_dt_articulos.codart = siv_articulo.codart  AND ".
						"	    siv_articulo.codunimed = siv_unidadmedida.codunimed ".
						" ORDER BY sep_dt_articulos.orden ";
		  break;
		  
		  case 'SOC':
			  $ls_sql=" SELECT soc_dt_bienes.numordcom, soc_dt_bienes.codart, soc_dt_bienes.canart, soc_dt_bienes.numsol, ".
					  "        soc_dt_bienes.unidad, soc_dt_bienes.preuniart, soc_dt_bienes.montotart,  ".
					  "        siv_articulo.spg_cuenta, siv_articulo.denart, siv_unidadmedida.unidad AS unimed, ".
					  "        soc_dt_bienes.coduniadm, siv_unidadmedida.denunimed, ".
					  "        (SELECT denuniadm        ". 
                      "         FROM  spg_unidadadministrativa ".
       				  "			WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND ".
             		  "               spg_unidadadministrativa.codemp=soc_dt_bienes.codemp AND ".
                      "               spg_unidadadministrativa.coduniadm=soc_dt_bienes.coduniadm) AS denuniadm, $ls_sqlaux1".
					  " FROM   soc_dt_bienes , soc_ordencompra , siv_articulo , siv_unidadmedida  ".
					  " WHERE  soc_dt_bienes.codemp='".$this->ls_codemp."'   AND  ".
					  "        soc_ordencompra.numordcom='".$as_numero."' AND ".
					  "        soc_dt_bienes.estcondat='B' AND  ".
					  "        soc_dt_bienes.codemp=soc_ordencompra.codemp AND ".
					  "        soc_dt_bienes.codemp=siv_articulo.codemp  AND ".
					  "        siv_articulo.codemp=soc_ordencompra.codemp AND ".
					  "  	   soc_dt_bienes.numordcom=soc_ordencompra.numordcom AND ".
					  "        siv_articulo.codart=soc_dt_bienes.codart  AND ".
					  "        soc_dt_bienes.estcondat=soc_ordencompra.estcondat AND ".
					  "        siv_articulo.codunimed = siv_unidadmedida.codunimed  ".
					  " ORDER BY soc_dt_bienes.orden ";
		  break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numero, $as_tabla, $as_campo, $as_campo_numero, $as_codartser)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: public
		//		 Argument: as_numsol // N�mero de solicitud
		//		 		   as_tabla // Tabla en la cual se va a buscar
		//		 		   as_campo // campo por el cual se va a buscar
		//	  Description: Funci�n que busca los cargos asociados a una solicitud
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 12/05/2007				Fecha �ltima Modificaci�n : 24/06/2007
		///////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codartser!="")
		{
		  $ls_cadena=" ".$as_tabla.".".$as_campo." = '".$as_codartser."' AND ";
		}
		else
		{
		  $ls_cadena="";
		}
		$ls_sql=" SELECT ".$as_tabla.".".$as_campo." AS codigo, ".$as_tabla.".codcar, ".$as_tabla.".monbasimp, ".$as_tabla.".monimp, ".
				"        ".$as_tabla.".monto, ".$as_tabla.".formula, tepuy_cargos.dencar, TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta ".
				" FROM   ".$as_tabla.", tepuy_cargos ".
				" WHERE  ".$as_tabla.".codemp = '".$this->ls_codemp."' AND ".
				"        ".$as_tabla.".".$as_campo_numero." = '".$as_numero."' AND ".
				"        ".$ls_cadena." ".
				"        ".$as_tabla.".codemp = tepuy_cargos.codemp   AND ".
				"        ".$as_tabla.".codcar = tepuy_cargos.codcar ";		
		$rs_data=$this->io_sql->select($ls_sql);
		//print_r($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numero,$as_estcondat,$as_tipsol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: public
		//		 Argument: as_numero ---> n�mero de la orden de compra o la solicitud presupuestaria
		//                 as_estcondat  ---> tipo de la orden de compra si es de bienes � de servicios
		//                 $as_tipsol  ---> tipo de la solicitud
		//	  Description: Busca las cuentas asociadas a una solicitud presupuestaria o orden de compra
		//	   Creado Por: Ing Miguel Palencia
		// Fecha Creaci�n: 12/05/2007			Fecha �ltima Modificaci�n : 24/06/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_tipsol)
		{
			case 'SEP':
			switch ($as_estcondat)
			{
			   case 'B':
				 $ls_tabla="sep_dt_articulos"; 
				 $ld_total="(canart*monpre)";
			   break;
			   
			   case 'S':
				 $ls_tabla="sep_dt_servicio"; 
				 $ld_total="(canser*monpre)";
			   break;
			}
			$ls_sql=" SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , ".
			        "        TRIM(codestpro3) AS codestpro3 , TRIM(codestpro4) AS codestpro4 , ".
					"        TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
                    "        ".$ld_total."  AS total ".
                    " FROM  ".$ls_tabla."  ".
                    " WHERE codemp='".$this->ls_codemp."' AND ".
					"       numsol='".$as_numero."' AND estincite='NI' ";
			break;
			
			case 'SOC':
			$ls_sql=" SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
					"		 TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
					"		 monto AS total ".
					" FROM   soc_cuentagasto  ".
					" WHERE  codemp='".$this->ls_codemp."' AND ".
					"        numordcom='".$as_numero."' AND ".
					"        estcondat='".$as_estcondat."'  ".
					" UNION ".
					" SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
					" 		 TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
					"		 -monto AS total ".
					" FROM   soc_solicitudcargos ".
					" WHERE  codemp='".$this->ls_codemp."' AND ".
					"        numordcom='".$as_numero."' AND ".
					"        estcondat='".$as_estcondat."' ";
			break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_load_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_dscuentas->data=$this->io_sql->obtener_datos($rs_data);
				$this->io_dscuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3',
				                                    '3'=>'codestpro4','4'=>'codestpro5','5'=>'spg_cuenta'),
											  array('0'=>'total'),'total');
			}
		}
		return $this->io_dscuentas;
	}// end function uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_cargo($as_numero,$as_estcondat,$as_tipsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: public
		//		 Argument: as_numero ---> n�mero de la orden de compra o la solicitud de ejecucion presupuestaria
		//                 as_estcondat  ---> tipo de la orden de compra si es de bienes � de servicios
		//                 as_tipsol  ---> tipo si es sep o soc
		//	  Description: Funci�n que busca las cuentas asociadas a una solicitud
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007					Fecha �ltima Modificaci�n : 12/05/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch ($as_tipsol)
		{
		  case 'SEP':
			switch ($as_estcondat)
			{
			    case 'B':
				  $ls_tabla_cargos="sep_dta_cargos";
				  $ls_tabla_item="sep_dt_articulos";
				  $ls_campo="codart";
				break;
				
				case 'S':
				  $ls_tabla_cargos="sep_dts_cargos";
				  $ls_tabla_item="sep_dt_servicio";
				  $ls_campo="codser";
				break;
			 
			}
			  $ls_sql=" SELECT sep_solicitudcargos.codcar, sep_solicitudcargos.codestpro1, ".
			          "        sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, ".
					  "        sep_solicitudcargos.codestpro4, sep_solicitudcargos.codestpro5, ".
                      "        TRIM(sep_solicitudcargos.spg_cuenta) AS spg_cuenta, ".
					  "        ".$ls_tabla_cargos.".monimp AS total ".
					  " FROM   sep_solicitudcargos, tepuy_cargos, ".$ls_tabla_item.", ".
					  "        ".$ls_tabla_cargos." ".
                      " WHERE  sep_solicitudcargos.codemp='".$this->ls_codemp."' 
					      AND  sep_solicitudcargos.numsol='".$as_numero."' 
						  AND  ".$ls_tabla_item.".estincite='NI' 
						  AND sep_solicitudcargos.codemp=$ls_tabla_item.codemp
						  AND sep_solicitudcargos.codemp=$ls_tabla_cargos.codemp
						  AND sep_solicitudcargos.codemp=tepuy_cargos.codemp
						  AND sep_solicitudcargos.numsol=$ls_tabla_item.numsol
						  AND sep_solicitudcargos.numsol=$ls_tabla_cargos.numsol
						  AND sep_solicitudcargos.codcar=tepuy_cargos.codcar
						  AND sep_solicitudcargos.codcar=$ls_tabla_cargos.codcar
						  AND $ls_tabla_cargos.$ls_campo=$ls_tabla_item.$ls_campo";
		  break;
		  
		  case 'SOC':
			$ls_sql=" SELECT codcar,codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
					"        TRIM(spg_cuenta) AS spg_cuenta, monto AS total ".
					" FROM   soc_solicitudcargos ".
					" WHERE  codemp='".$this->ls_codemp."' AND ".
					"        numordcom='".$as_numero."'    AND ".
					"        estcondat='".$as_estcondat."' ";
		  break;
		}  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_load_cuentas_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_totales($as_numsol,$as_estcondat)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_totales
		//		   Access: public
		//		 Argument: as_numsol ---> n�mero de la solicitud presupuestaria
		//                 as_estcondat ---> tipo de la orden de compra de bienes o de servicios 
		//	  Description: Metodo que busca los totales de una sep 
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007	Fecha �ltima Modificaci�n : 24/06/2007
		///////////////////////////////////////////////////////////////////////////////////////////
		switch ($as_estcondat)
		{
			case 'B':
			  $ls_tabla_cargos="sep_dta_cargos";
			  $ls_tabla_item="sep_dt_articulos";
			  $ls_campo="codart";
			break;
			
			case 'S':
			  $ls_tabla_cargos="sep_dts_cargos";
			  $ls_tabla_item="sep_dt_servicio";
			  $ls_campo="codser";
			break;
		}
		$ls_sql=" SELECT sep_solicitud.monbasinm AS monbasinm, ".
		        "        sep_solicitud.montotcar AS montotcar, ".
				"        sep_solicitud.monto AS monto  ".
				" FROM   sep_solicitud, ".$ls_tabla_item." ".
                " WHERE  sep_solicitud.codemp='".$this->ls_codemp."' AND ".
       			"        sep_solicitud.numsol='".$as_numsol."' AND  ".
                "        ".$ls_tabla_item.".estincite='NI' AND  ".
                "        sep_solicitud.codemp=".$ls_tabla_item.".codemp AND  ".
                "        sep_solicitud.numsol=".$ls_tabla_item.".numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_load_totales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numero,$as_tipsol)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: public
		//		 Argument: as_numero ---> n�mero de la orden de compra o la solicitud de ejecucion presupuestaria
		//                 as_tipsol ---> tipo de la solicitud si es sep o soc
		//	  Description: Funci�n que busca los servicios asociados a una solicitud
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007								Fecha �ltima Modificaci�n : 24/06/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch ($_SESSION["ls_gestor"])
		{
		  case 'MYSQL':
		    if ($as_tipsol=='SEP')
			   {
                 $ls_sqlaux = "(SELECT CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND spg_unidadadministrativa.codemp=sep_solicitud.codemp AND spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) as estpreunieje";
			   }
			elseif($as_tipsol=='SOC')
			   {
			     $ls_sqlaux = "(SELECT CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND spg_unidadadministrativa.codemp=soc_ordencompra.codemp AND spg_unidadadministrativa.coduniadm=soc_ordencompra.coduniadm) as estpreunieje";
		       }
		  break;
		  case 'POSTGRE':
		    if ($as_tipsol=='SEP')
			   {
		         $ls_sqlaux = "(SELECT codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND spg_unidadadministrativa.codemp=sep_solicitud.codemp AND spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) as estpreunieje";
			   }
			elseif($as_tipsol=='SOC')
			   {
		         $ls_sqlaux = "(SELECT codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 FROM spg_unidadadministrativa WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND spg_unidadadministrativa.codemp=soc_ordencompra.codemp AND spg_unidadadministrativa.coduniadm=soc_ordencompra.coduniadm) as estpreunieje";
		       }
		  break;
		}

		switch ($as_tipsol)
		{
		  case 'SEP':
				$ls_sql=" SELECT sep_dt_servicio.codser, sep_dt_servicio.canser, ".
				        "        sep_dt_servicio.monpre, sep_dt_servicio.numsol, ".
						"		 sep_dt_servicio.monser, sep_dt_servicio.orden,  ".
						"        TRIM(sep_dt_servicio.spg_cuenta) AS spg_cuenta, ".
						"        soc_servicios.denser,sep_solicitud.codfuefin,   ".
						"        sep_solicitud.coduniadm, 						 ".
						" (SELECT COALESCE(denunimed,'') 
						     FROM siv_unidadmedida
						 	WHERE soc_servicios.codunimed=siv_unidadmedida.codunimed) as denunimed, ".
						"        (SELECT denuniadm        						 ". 
						"         FROM  spg_unidadadministrativa ".
						"		  WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND ".
						"               spg_unidadadministrativa.codemp=sep_solicitud.codemp AND   ".
						"               spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) AS denuniadm, $ls_sqlaux ".
						"  FROM  sep_dt_servicio, soc_servicios, sep_solicitud ".
						" WHERE  sep_dt_servicio.codemp = '".$this->ls_codemp."' ".
						"   AND  sep_dt_servicio.numsol = '".$as_numero."'       ".
						"   AND  sep_dt_servicio.numsol = sep_solicitud.numsol   ".
						"   AND  sep_dt_servicio.codemp = sep_solicitud.codemp   ".
						"   AND  sep_dt_servicio.codemp = soc_servicios.codemp   ".
						"   AND  sep_dt_servicio.codser = soc_servicios.codser   ".
						" ORDER  BY sep_dt_servicio.orden ";
		  break;
		  
		  case 'SOC':
				$ls_sql=" SELECT soc_dt_servicio.codser, soc_dt_servicio.canser, soc_dt_servicio.monuniser,   ".
						"        soc_dt_servicio.monsubser, soc_dt_servicio.montotser, soc_dt_servicio.orden, ".
						"        TRIM(soc_servicios.spg_cuenta) AS spg_cuenta, soc_servicios.denser, soc_dt_servicio.numsol, ".
						"        soc_dt_servicio.coduniadm, ".
						" (SELECT COALESCE(denunimed,'') 
						     FROM siv_unidadmedida
						 	WHERE soc_servicios.codunimed=siv_unidadmedida.codunimed) as denunimed, ".
						"        (SELECT denuniadm        ". 
						"         FROM  spg_unidadadministrativa ".
						"		  WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND ".
						"               spg_unidadadministrativa.codemp=soc_dt_servicio.codemp AND ".
						"               spg_unidadadministrativa.coduniadm=soc_dt_servicio.coduniadm) AS denuniadm, $ls_sqlaux ".
						" FROM   soc_dt_servicio , soc_ordencompra , soc_servicios ".
						" WHERE  soc_dt_servicio.codemp='".$this->ls_codemp."'  AND ".
						"        soc_dt_servicio.numordcom='".$as_numero."'  AND ".
						"        soc_dt_servicio.estcondat='S'  AND  ".
						"        soc_dt_servicio.codemp=soc_ordencompra.codemp  AND ".
						"        soc_dt_servicio.codemp=soc_servicios.codemp    AND ".
						"        soc_servicios.codemp=soc_ordencompra.codemp    AND ".
						"        soc_dt_servicio.numordcom=soc_ordencompra.numordcom AND ".
						"        soc_servicios.codser=soc_dt_servicio.codser         AND ".
						"        soc_dt_servicio.estcondat=soc_ordencompra.estcondat ".
						" ORDER BY soc_dt_servicio.orden "; 
		  break;
		}  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_load_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: 
		//	Description: Funci�n que obtiene e imprime los tipos de articulos
		//////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbcodtipart' id='cmbcodtipart' style='width:150px'> ";
		print "		<option value='' selected>--Seleccione uno--</option> ";
		$ls_sql="SELECT codtipart, dentipart ".
		        "  FROM siv_tipoarticulo ".
				" ORDER BY codtipart ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtipart=$row["codtipart"];
				$ls_dentipart=$row["dentipart"];
		  	    print "<option value='$ls_codtipart'>".$ls_dentipart."</option>";
			}
			$io_sql->free_result($rs_data);
		}
		print "</select>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosbienes($as_codart,$as_codprounidad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosbienes
		//		   Access: public
		//		 Argument: as_codart // C�digo del art�culo que se est�n buscando los cargos
		//		 		   as_codprounidad // C�digo Program�tico de la unidad ejecutora
		//	  Description: Funci�n que busca los cargos asociados a un art�culo
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1=substr($as_codprounidad,0,20);
		$ls_codestpro2=substr($as_codprounidad,20,6);
		$ls_codestpro3=substr($as_codprounidad,26,3);
		$ls_codestpro4=substr($as_codprounidad,29,2);
		$ls_codestpro5=substr($as_codprounidad,31,2);
		$ls_sql="SELECT siv_cargosarticulo.codart AS codigo, tepuy_cargos.codcar, tepuy_cargos.dencar, ".
				"		TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, tepuy_cargos.formula, tepuy_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM tepuy_cargos, siv_cargosarticulo ".
                " WHERE siv_cargosarticulo.codemp = '".$this->ls_codemp."' ".
				"   AND siv_cargosarticulo.codart = '".$as_codart."' ".
				"	AND tepuy_cargos.codemp = siv_cargosarticulo.codemp ".
				"   AND tepuy_cargos.codcar = siv_cargosarticulo.codcar ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_cargosbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosbienes_seleccionado($as_codart,$as_codprounidad,$ad_codivasel)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosbienes
		//		   Access: public
		//		 Argument: as_codart // C�digo del art�culo que se est�n buscando los cargos
		//		 		   as_codprounidad // C�digo Program�tico de la unidad ejecutora
		//	  Description: Funci�n que busca los cargos asociados a un art�culo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/09/2015								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1=substr($as_codprounidad,0,20);
		$ls_codestpro2=substr($as_codprounidad,20,6);
		$ls_codestpro3=substr($as_codprounidad,26,3);
		$ls_codestpro4=substr($as_codprounidad,29,2);
		$ls_codestpro5=substr($as_codprounidad,31,2);

		$ls_sql="SELECT tepuy_cargos.codcar, tepuy_cargos.dencar, ".
				"		TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, tepuy_cargos.formula, tepuy_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM tepuy_cargos, spg_cuentas ".
                " WHERE tepuy_cargos.codcar = '".$ad_codivasel."' GROUP BY tepuy_cargos.codcar";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_cargosbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes_seleccionado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosservicios($as_codser,$as_codprounidad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosservicios
		//		   Access: public
		//		 Argument: as_codser // C�digo del art�culo que se est�n buscando los cargos
		//		 		   as_codprounidad // C�digo Program�tico de la unidad ejecutora
		//	  Description: Funci�n que busca los cargos asociados a un servicio
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1=substr($as_codprounidad,0,20);
		$ls_codestpro2=substr($as_codprounidad,20,6);
		$ls_codestpro3=substr($as_codprounidad,26,3);
		$ls_codestpro4=substr($as_codprounidad,29,2);
		$ls_codestpro5=substr($as_codprounidad,31,2);
		$ls_sql="SELECT soc_serviciocargo.codser AS codigo, tepuy_cargos.codcar, tepuy_cargos.dencar, ".
				"		TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, tepuy_cargos.formula, tepuy_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM tepuy_cargos, soc_serviciocargo ".
                " WHERE soc_serviciocargo.codemp = '".$this->ls_codemp."' ".
				"   AND soc_serviciocargo.codser = '".$as_codser."' ".
				"	AND tepuy_cargos.codemp = soc_serviciocargo.codemp ".
				"   AND tepuy_cargos.codcar = soc_serviciocargo.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_cargosservicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosservicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_buscarcuentacargo($as_codcar)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_buscarcuentacargo
		//		   Access: public
		//		 Argument: as_codcar // C�digo del cargo (IVA)
		//	  Description: Funci�n que busca las categorias programaticas de los cargos asociados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 20/06/2015
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codestpro FROM tepuy_cargos ".
			" WHERE codemp = '".$this->ls_codemp."' "." AND codcar = '".$as_codcar."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_buscarcuentacargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_codestpro=$row["codestpro"];
				$ls_codestpro1=substr($as_codprounidad,0,20);
				$ls_codestpro2=substr($as_codprounidad,20,6);
				$ls_codestpro3=substr($as_codprounidad,26,3);
				$ls_codestpro4=substr($as_codprounidad,29,2);
				$ls_codestpro5=substr($as_codprounidad,31,2);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ld_codestpro;
	}// end function uf_load_buscarcuentacargos

	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar(&$as_estcom,$ad_fecordcom,$ai_estsegcom,&$as_numordcom,$as_coduniadm,$as_codfuefin,$as_rbtipord,
			            $as_codprov,$as_forpag,$ad_antpag,$as_rb_rblugcom,$as_concom,$as_codtipmod,
						$as_conordcom,$as_obsordcom,$as_lugentnomdep,$as_lugentdir,$as_diaplacom,$ad_porsegcom,
						$ad_monsegcom,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,
						$ad_montotdiv,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
						$as_codestpro5,$ai_totrowbienes,$ai_totrowservicios,$ai_totrowcargos,$ai_totrowcuentas,
						$ai_totrowcuentascargo,$ai_subtotal,$ai_cargos,$ai_total,$aa_seguridad,$as_existe,
						$as_tipsol,$as_numsoldel,$as_uniejeaso,$as_fecentordcom,$as_perentdesde,$as_perenthasta)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//	    Arguments: as_estcom  --->   Estatus de la orden de compra
		//				   ad_fecordcom ---> Fecha de registro de la orden de compra
		//				   ai_estsegcom ---> Estatus si existe seguro para la orden de compra
		//				   as_numordcom ---> Numero de la orden de compra
		//                 $as_coduniadm --->Codigo de unidad administrativa
		//				   as_codfuefin ---> C�digo de Fuente de financiamiento
		//				   as_rbtipord --->  C�digo de Fuente de financiamiento
		//                 $as_concom  --->  Condicion de la compra	 
		//				   $as_codtipmod ---> Tipo de la modalidad
		//                 $as_conordcom ---> concepto de la orden de compra
		//				   $as_obsordcom ---> Observacion de la orden de compra
		//                 $as_lugentnomdep ---> Lugar de Entega Nombre de la Dependencia
		//                 $as_lugentdir --->  Lugar de entrega de la direccion 
		//                 $as_diaplacom --->  Dias de plazo de la orden de compra  
		//				   $ad_porsegcom ---> Porcentaje de la orden de compra 
		//                 $ad_monsegcom ---> Monto del seguro de la orden de compra 
		//				   $as_codpai    ---> Codigo del Pais
		//				   $as_codest    ---> Codigo del Estado 
		//				   $as_codmun    ---> Codigo del Municipio
		//				   $as_codpar    ---> Codigo del Parroquia
		//                 $as_codmon    ---> Codigo de la moneda
		//				   $ad_tascamordcom ---> Tasa de cambio d ela orden de compra
		//                 $ad_montotdiv --->  Monto de la Divisa
		//				   as_codprov ---> C�digo de Proveedor 
		//                 $as_forpag ---> Forma de Pago
		//                 $ad_antpag ---> Anticipo de Pago
		//                 $as_rb_rblugcom --->Lugar de la Compra
		//				   ai_subtotal  --->  Subtotal de la solicitu
		//				   ai_cargos  --->  Monto del cargo
		//				   ai_total  --->  Total de la solicitud
		//				   as_codestpro1  --->  C�digo Estructura Program�tica 1
		//				   as_codestpro2  --->  C�digo Estructura Program�tica 2
		//				   as_codestpro3  --->  C�digo Estructura Program�tica 3
		//				   as_codestpro4  --->  C�digo Estructura Program�tica 4
		//				   as_codestpro5  ---> C�digo Estructura Program�tica 5
		//				   ai_totrowbienes  --->  Total de Filas de Bienes
		//				   ai_totrowcargos  --->  Total de Filas de Servicios
		//				   ai_totrowcuentas  --->  Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  --->  Total de Filas de Cuentas de los cargos
		//				   ai_totrowservicios  --->  Total de Filas de Servicios
		//				   aa_seguridad  --->  arreglo de las variables de seguridad
		//                 $as_tipsol    ------> tipo de la solicitud si es SEP o SOC
		//				   $as_numsoldel ---> numero de solicitudes a eliminar
		//				   $as_perentdesde ---> Fecha de entrega desde
		//				   $as_perenthasta ---> Fecha de entrga hasta
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error 
		//	  Description: Funcion que valida y guarda la orden de compra
		//	   Creado Por: Ing. Miguel Palencia                    Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 19/06/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_monsegcom=str_replace(".","",$ad_monsegcom);
		$ad_monsegcom=str_replace(",",".",$ad_monsegcom);
		$ai_subtotal=str_replace(".","",$ai_subtotal);
		$ai_subtotal=str_replace(",",".",$ai_subtotal);
		$ad_antpag=str_replace(".","",$ad_antpag);
		$ad_antpag=str_replace(",",".",$ad_antpag);
		$ai_cargos=str_replace(".","",$ai_cargos);
		$ai_cargos=str_replace(",",".",$ai_cargos);
		$ad_montotdiv=str_replace(".","",$ad_montotdiv);
		$ad_montotdiv=str_replace(",",".",$ad_montotdiv);
		$ai_total=str_replace(".","",$ai_total);
		$ai_total=str_replace(",",".",$ai_total);
		$ad_porsegcom=str_replace(".","",$ad_porsegcom);
		$ad_porsegcom=str_replace(",",".",$ad_porsegcom);
		$ad_tascamordcom=str_replace(".","",$ad_tascamordcom);
		$ad_tascamordcom=str_replace(",",".",$ad_tascamordcom);
		$ad_fecordcom=$this->io_funciones->uf_convertirdatetobd($ad_fecordcom);
		$as_fecentordcom=$this->io_funciones->uf_convertirdatetobd($as_fecentordcom);
		$as_perentdesde=$this->io_funciones->uf_convertirdatetobd($as_perentdesde);
		$as_perenthasta=$this->io_funciones->uf_convertirdatetobd($as_perenthasta);
		if($as_existe=='FALSE')
		{
			$lb_valido=$this->uf_validar_fecha($ad_fecordcom,$as_rbtipord);
			if(!$lb_valido)
			{
				$this->io_mensajes->message("La Fecha de esta Orden de Compra es menor a la fecha de la Orden de Compra anterior.");
				//return false;
			}
			$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecordcom,$this->ls_codemp);
			if (!$lb_valido)
			{
				$this->io_mensajes->message($this->io_fecha->is_msg_error);           
				return false;
			}                   
			$lb_valido=$this->uf_insert_orden_compra($as_estcom,$ad_fecordcom,$ai_estsegcom,&$as_numordcom,$as_coduniadm,$as_codfuefin,
													 $as_rbtipord,$as_codprov,$as_forpag,$ad_antpag,$as_rb_rblugcom,
													 $as_concom,$as_codtipmod,$as_conordcom,$as_obsordcom,$as_lugentnomdep,
													 $as_lugentdir,$as_diaplacom,$ad_porsegcom,$ad_monsegcom,$as_codpai,
													 $as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,
													 $ad_montotdiv,$as_codestpro1,$as_codestpro2,$as_codestpro3,
													 $as_codestpro4,$as_codestpro5,$ai_totrowbienes,$ai_totrowservicios,
													 $ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,
													 $ai_subtotal,$ai_cargos,$ai_total,$aa_seguridad,$as_tipsol,$as_uniejeaso,
													 $as_fecentordcom,$as_perentdesde,$as_perenthasta);
		}
		elseif($as_existe=='TRUE')
		{
			$lb_valido=$this->uf_update_orden_compra($as_estcom,$ad_fecordcom,$ai_estsegcom,$as_numordcom,$as_coduniadm,$as_codfuefin,
													 $as_rbtipord,$as_codprov,$as_forpag,$ad_antpag,$as_rb_rblugcom,
													 $as_concom,$as_codtipmod,$as_conordcom,$as_obsordcom,$as_lugentnomdep,
													 $as_lugentdir,$as_diaplacom,$ad_porsegcom,$ad_monsegcom,$as_codpai,
													 $as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,
													 $ad_montotdiv,$as_codestpro1,$as_codestpro2,$as_codestpro3,
													 $as_codestpro4,$as_codestpro5,$ai_totrowbienes,$ai_totrowservicios,
													 $ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,
													 $ai_subtotal,$ai_cargos,$ai_total,$aa_seguridad,$as_tipsol,$as_numsoldel,
													 $as_uniejeaso,$as_fecentordcom,$as_perentdesde,$as_perenthasta);
		}
		else
		{
			$this->io_mensajes->message("La Orden de Compra no existe, no la puede actualizar !!!");
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_orden_compra($as_numordcom,$as_estcondat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_orden_compra
		//		   Access: private
		//	    Arguments: as_numordcom  --->  N�mero de la orden de compra
		//                 $as_estcondat --->  Estatus de la orden de compra
		// 	      Returns: true si se existe la orden de compra o false en caso contrario
		//	  Description: Funcion que verifica si existe una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql=" SELECT numordcom ".
				"   FROM soc_ordencompra ".
				"  WHERE codemp='".$this->ls_codemp."' AND ".
				"        numordcom='".$as_numordcom."' AND ".
			//	"        estcondat='B'";
				"        estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_select_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha($ad_fecordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha
		//		   Access: private
		//		 Argument: $ad_fecordcom ---> fecha de registro de la orden de compra
		//                 $as_estcondat --->  Estatus de la orden de compra
		//	  Description: Funci�n que busca la fecha de la �ltima sep y la compara con la fecha actual
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/03/2007			Fecha �ltima Modificaci�n : 12/05/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numordcom,fecordcom ".
				"  FROM soc_ordencompra  ".
				" WHERE codemp='".$this->ls_codemp."' AND ".
				"       estcondat='".$as_estcondat."' ".
				" ORDER BY numordcom DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_validar_fecha ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$row["fecordcom"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecordcom); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_orden_compra(&$as_estcom,$ad_fecordcom,$ai_estsegcom,&$as_numordcom,$as_coduniadm,$as_codfuefin,$as_estcondat,
	                                $as_codprov,$as_forpag,$ad_antpag,$as_rb_rblugcom,$as_concom,$as_codtipmod,$as_conordcom,
									$as_obsordcom,$as_lugentnomdep,$as_lugentdir,$as_diaplacom,$ad_porsegcom,$ad_monsegcom,$as_codpai,
									$as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,$ad_montotdiv,$as_codestpro1,
									$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$ai_totrowbienes,
									$ai_totrowservicios,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,
									$ai_subtotal,$ai_cargos,$ai_total,$aa_seguridad,$as_tipsol,$as_uniejeaso,$as_fecentordcom,
									$as_perentdesde,$as_perenthasta)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_orden_compra
	//	    Arguments: as_estcom  --->   Estatus de la orden de compra
	//				   ad_fecordcom ---> Fecha de registro de la orden de compra
	//				   ai_estsegcom ---> Estatus si existe seguro para la orden de compra
	//				   as_numordcom ---> Numero de la orden de compra
	//                 $as_coduniadm --->Codigo de unidad administrativa
	//				   as_codfuefin ---> C�digo de Fuente de financiamiento
	//				   as_estcondat --->  tipo de la orden de compra 
	//                 $as_concom  --->  Condicion de la compra	 
	//				   $as_codtipmod ---> Tipo de la modalidad
	//                 $as_conordcom ---> concepto de la orden de compra
	//				   $as_obsordcom ---> Observacion de la orden de compra
	//                 $as_lugentnomdep ---> Lugar de Entega Nombre de la Dependencia
	//                 $as_lugentdir --->  Lugar de entrega de la direccion 
	//                 $as_diaplacom --->  Dias de plazo de la orden de compra  
	//				   $ad_porsegcom ---> Porcentaje de la orden de compra 
	//                 $ad_monsegcom ---> Monto del seguro de la orden de compra 
	//				   $as_codpai    ---> Codigo del Pais
	//				   $as_codest    ---> Codigo del Estado 
	//				   $as_codmun    ---> Codigo del Municipio
	//				   $as_codpar    ---> Codigo del Parroquia
	//                 $as_codmon    ---> Codigo de la moneda
	//				   $ad_tascamordcom ---> Tasa de cambio d ela orden de compra
	//                 $ad_montotdiv --->  Monto de la Divisa
	//				   as_codprov ---> C�digo de Proveedor 
	//                 $as_forpag ---> Forma de Pago
	//                 $ad_antpag ---> Anticipo de Pago
	//                 $as_rb_rblugcom --->Lugar de la Compra
	//				   ai_subtotal  --->  Subtotal de la solicitu
	//				   ai_cargos  --->  Monto del cargo
	//				   ai_total  --->  Total de la solicitud
	//				   as_codestpro1  --->  C�digo Estructura Program�tica 1
	//				   as_codestpro2  --->  C�digo Estructura Program�tica 2
	//				   as_codestpro3  --->  C�digo Estructura Program�tica 3
	//				   as_codestpro4  --->  C�digo Estructura Program�tica 4
	//				   as_codestpro5  ---> C�digo Estructura Program�tica 5
	//				   ai_totrowbienes  --->  Total de Filas de Bienes
	//				   ai_totrowcargos  --->  Total de Filas de Servicios
	//				   ai_totrowcuentas  --->  Total de Filas de Cuentas
	//				   ai_totrowcuentascargo  --->  Total de Filas de Cuentas de los cargos
	//				   ai_totrowservicios  --->  Total de Filas de Servicios
	//				   aa_seguridad  --->  arreglo de las variables de seguridad
	//                 as_uniejeaso  --->  unidades ejecutoras asociadas que provengan de una sep  
	//	      Returns: devuelve true si se inserto correctamente la orden de compra o false en caso contrario
	//	  Description: Funcion que que se encarga dde insertar una orden de compra
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($as_estcondat=='B')
	{
	  $ls_procede="SOCCOC";
	}
	else
	{
	  $ls_procede="SOCCOS";
	}
	$lb_valido=$this->io_id_process->uf_verificar_numero_generado('SOC','soc_ordencompra','numordcom',$ls_procede,15,'','estcondat',$as_estcondat,$as_numordcom);
	$ls_numordcomaux = $as_numordcom;
	$lb_valido=true;
	if($lb_valido)
	{
     	$ld_monsubtotbie = 0;
     	$ld_monsubtotser = 0;
     	$ld_monbasimp = 0;
     	$ld_mondes = 0;
		$li_estpenalm = 0;
		$li_estapro   = 0;
		$ls_codusuapr = "";
		$ls_numpolcon = 0;
		if($as_rb_rblugcom=='N')
		{ $as_rb_rblugcom = 0 ;}
		else
		{ $as_rb_rblugcom = 1 ;}
		if($as_codmon=="")
		{
		  $as_codmon='---';
		}
		if($as_codfuefin=="")
		{
		  $as_codfuefin='--';
		}
		if($as_estcom=='R')
		{
		  $as_estcom=0;
		}
		elseif($as_estcom=='E')
		{
		 $as_estcom=1;
		}
		if($as_diaplacom=="")
		{
		  $as_diaplacom=0;
		}
		if($as_codtipmod=="")
		{
	       $as_codtipmod="--";
		}
		if($as_coduniadm=="" || $as_coduniadm=="---")
		{ 
	       $as_coduniadm="----------";   	
		}
		if ($as_fecentordcom =="")
		{
		  $as_fecentordcom= $ad_fecordcom;
		}
		if($as_perentdesde=="")
		{ 
	       $as_perentdesde="1900-01-01";   	
		}
		if($as_perenthasta=="")
		{ 
	       $as_perenthasta="1900-01-01";   	
		}	
        $as_obsordcom=substr($as_obsordcom,0,499);
        $as_conordcom=substr($as_conordcom,0,499);
		
		$ls_sql=" INSERT INTO soc_ordencompra (codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, codtipmod, ".
		        "                              fecordcom, estsegcom, porsegcom, monsegcom, forpagcom, estcom, diaplacom, ".
				"							   concom, obscom, monsubtotbie, monsubtotser, monsubtot, monbasimp, monimp, ".
				"							   mondes, montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, ".
				"							   lugentdir, monant, estlugcom, tascamordcom, montotdiv, estapro, fecaprord, ".
				"                              codusuapr, numpolcon, coduniadm, obsordcom, fecent,numanacot,uniejeaso,  ".
				"                              fechentdesde,fechenthasta) ".
				" VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$as_codprov."','".$as_codmon."', ".
				"         '".$as_codfuefin."','".$as_codtipmod."','".$ad_fecordcom."','".$ai_estsegcom."',".$ad_porsegcom.",".
				"         '".$ad_monsegcom."','".$as_forpag."','".$as_estcom."','".$as_diaplacom."','".$as_concom."', ".
				"         '".$as_conordcom."',".$ld_monsubtotbie.",".$ld_monsubtotser.",".$ai_subtotal.",".$ld_monbasimp.", ".
				"         ".$ai_cargos.",".$ld_mondes.",".$ai_total.",".$li_estpenalm.",'".$as_codpai."', ".
				"         '".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_lugentnomdep."','".$as_lugentdir."', ".
				"         ".$ad_antpag.",".$as_rb_rblugcom.",".$ad_tascamordcom.",".$ad_montotdiv.",".$li_estapro.", ".
				"         '1900-01-01','".$ls_codusuapr."','".$ls_numpolcon."','".$as_coduniadm."','".$as_obsordcom."', ".
				"         '".$as_fecentordcom."','-','".$as_uniejeaso."','".$as_perentdesde."','".$as_perenthasta."')";        
		$this->io_sql->begin_transaction();				
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_sql->rollback();
		    if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
			   $lb_valido=$this->uf_insert_orden_compra($as_estcom,$ad_fecordcom,$ai_estsegcom,$as_numordcom,$as_coduniadm,
			                                            $as_codfuefin,$as_estcondat,$as_codprov,$as_forpag,$ad_antpag,
														$as_rb_rblugcom,$as_concom,$as_codtipmod,$as_conordcom,$as_obsordcom,
														$as_lugentnomdep,$as_lugentdir,$as_diaplacom,$ad_porsegcom,$ad_monsegcom,
														$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,
														$ad_montotdiv,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														$as_codestpro5,$ai_totrowbienes,$ai_totrowservicios,$ai_totrowcargos,
														$ai_totrowcuentas,$ai_totrowcuentascargo,$ai_subtotal,$ai_cargos,
														$ai_total,$aa_seguridad,$as_tipsol,$as_uniejeaso,$as_fecentordcom,
														$as_perentdesde,$as_perenthasta);
			}
			else
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� la Orden de Compra ".$as_numordcom." tipo ".$as_estcondat." de fecha".$ad_fecordcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_sep = false;
			if($as_estcondat=="B")
			{
				$lb_valido=$this->uf_insert_bienes($as_numordcom,$as_estcondat,$ai_totrowbienes,$aa_seguridad,$as_tipsol,$lb_sep);
			}
			elseif($as_estcondat=="S")
			{
				$lb_valido=$this->uf_insert_servicios($as_numordcom,$as_estcondat,$ai_totrowservicios,$aa_seguridad,$as_tipsol,$lb_sep);
			}
	        if($lb_valido)
			{
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,$ai_totrowcargos,$as_estcondat,$aa_seguridad);
			}
	        if($lb_valido)
			{
			    $lb_valido=$this->uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
			}
	        if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$ai_totrowcuentascargo,$ai_totrowcargos,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat);
			}
	        if($lb_valido)
			{
			  if($lb_sep)
			  {
			 	 if($as_estcondat=='B')
				 {
					$ai_totrow = $ai_totrowbienes;
				 }
				 elseif($as_estcondat=='S')
				 {
				    $ai_totrow = $ai_totrowservicios;
				 }
				 $lb_valido=$this->uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$ai_totrow,$aa_seguridad);
			  }	 
			}
			if($lb_valido)
			{	
				if($as_estcondat=='B')
				{
					if($ls_numordcomaux!=$as_numordcom)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Orden de Compra: ".$as_numordcom);
					}
					$this->io_mensajes->message("La Orden de Compra fue Registrada!!!");
					$this->io_sql->commit();
				}
				else
				{
					if($ls_numordcomaux!=$as_numordcom)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Orden de Servicio: ".$as_numordcom);
					}
					$this->io_mensajes->message("La Orden de Servicio fue Registrada!!!");
					$this->io_sql->commit();
				}
			}
			else
			{
				if($as_estcondat=='B')
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Orden de Compra !!!"); 
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Orden de Servicio !!!"); 
					$this->io_sql->rollback();
				}
			}
	    }
	}
	return $lb_valido;
	}// fin uf_insert_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numordcom,$as_estcondat,$ai_totrowbienes,$aa_seguridad,$as_tipsol,&$ab_sep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numordcom  ---> n�mero de la Orden de Compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowbienes  ---> total de filas de bienes
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//                 $ab_sep = Variable booleana que indica si existen Solicitudes de Ejecucion presupuestaria en la Orden.
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 21/02/08.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li=1;($li<$ai_totrowbienes)&&($lb_valido);$li++)
		{
			$ls_codart=$_POST["txtcodart".$li];
			$ls_numsolord=$_POST["txtnumsolord".$li];
			$ls_coduniadmsep=$_POST["txtcoduniadmsep".$li]; 
			if (!empty($ls_coduniadmsep))
			   {
				 $lb_sep = $ab_sep = true;
			   }
			else 
			   {
			     $lb_sep = false;
			   }
			$ls_denart=$_POST["txtdenart".$li];
			$li_canart=$_POST["txtcanart".$li];
			$ls_unidad=$_POST["cmbunidad".$li];
			$ld_preuniart=$_POST["txtpreart".$li];
			$ld_monsubart=$_POST["txtsubtotart".$li];
			$ld_carart=$_POST["txtcarart".$li];
			$ld_montotart=$_POST["txttotart".$li];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li];			
			$ls_unidadfisica=$_POST["txtunidad".$li];			
			$ld_preart=0;
			$li_canart=str_replace(".","",$li_canart);
			$li_canart=str_replace(",",".",$li_canart);
			$ld_preuniart=str_replace(".","",$ld_preuniart);
			$ld_preuniart=str_replace(",",".",$ld_preuniart);			
			$ld_montotart=str_replace(".","",$ld_montotart);
			$ld_montotart=str_replace(",",".",$ld_montotart);
			$ld_monsubart=str_replace(".","",$ld_monsubart);
			$ld_monsubart=str_replace(",",".",$ld_monsubart);
			
			$li_orden=$li;
			
	        $ls_sql=" INSERT INTO soc_dt_bienes (codemp, numordcom, estcondat, codart, unidad, canart, ".
			        "							 penart, preuniart, monsubart, montotart, orden, numsol,coduniadm)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."', ".
					"          '".$ls_codart."','".$ls_unidad."',".$li_canart.",".$ld_preart.", ".
					"           ".$ld_preuniart.",".$ld_monsubart.",".$ld_montotart.",".$li_orden.",'".$ls_numsolord."', ".
					"           '".$ls_coduniadmsep."')";                                                                       
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($lb_sep)
				{
					$lb_valido=$this->uf_actualizar_estatus_item_sep($ls_numsolord,$ls_codart,$aa_seguridad,$as_numordcom,$as_estcondat);
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert� el Articulo ".$ls_codart." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    }
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_estatus_item_sep($as_numsolord,$as_codartser,$aa_seguridad,$as_numordcom,$as_estcondat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estatus_item_sep
		//		   Access: private
		//	    Arguments: as_numsolord  ---> n�mero de la sep tomada por una  orden de Compra
		//                 as_codartser  ---> articulo  o servicio actualizar
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//                 as_numordcom ---> numero de la orden de compra
		//                 $as_estcondat  ---> tipo de la orden de compra
		//	      Returns: true si se actualizaron correctamente los articulos y falso en caso contrario
		//	  Description: este metodo actualiza el estatus estincite(estatus de incorporacion del item) en la sep que
		//                 significa que el bien fue tomado por una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_estcondat)
		{
		  case 'B':
			$ls_sql=" UPDATE sep_dt_articulos ".
					" SET    estincite='OC', ".
			        "        numdocdes='".$as_numordcom."' ".
					" WHERE  codemp='".$this->ls_codemp."' AND ".
					"	     numsol='".$as_numsolord."'    AND ".
					" 	     codart='".$as_codartser."' ";
			 $ls_cadena="articulo";		
		  break;
		  
		  case 'S':
			$ls_sql=" UPDATE sep_dt_servicio ".
			        " SET    estincite='OC', ".
			        "        numdocdes='".$as_numordcom."' ".
					" WHERE  codemp='".$this->ls_codemp."' AND ".
					"	     numsol='".$as_numsolord."'    AND ".
					" 	     codser='".$as_codartser."' ";
			 $ls_cadena="servicio";		
		  break;
		
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_actualizar_estatus_item_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo el ".$ls_cadena." ".$as_codartser." de la sep  numero ".$as_numsolord." tomada por la orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_actualizar_estatus_item_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numordcom,$as_estcondat,$ai_totrowservicios,$aa_seguridad,$as_tipsol,&$ab_sep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numordcom  ---> n�mero de la Orden de Compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowservicios  ---> total de filas de servicios
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los servicios correctamente o false en caso contrario
		//	  Description: este metodo inserta los servicios de una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li=1;($li<$ai_totrowservicios)&&($lb_valido);$li++)
		{
			$ls_codser=$_POST["txtcodser".$li];
			$ls_numsolord=$_POST["txtnumsolord".$li];
			$ls_coduniadmsep=$_POST["txtcoduniadmsep".$li];
			if (!empty($ls_coduniadmsep))
			   {
				 $lb_sep = $ab_sep = true;
			   }
			else 
			   {
			     $lb_sep = false;
			   }
			$ls_denser=$_POST["txtdenser".$li];
			$li_canser=$_POST["txtcanser".$li];
			$ld_preser=$_POST["txtpreser".$li];
			$ld_subtotser=$_POST["txtsubtotser".$li];
			$ld_carser=$_POST["txtcarser".$li];
			$ld_totser=$_POST["txttotser".$li];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li];			
			
			$li_canser=str_replace(".","",$li_canser);
			$li_canser=str_replace(",",".",$li_canser);
			$ld_preser=str_replace(".","",$ld_preser);
			$ld_preser=str_replace(",",".",$ld_preser);			
			$ld_totser=str_replace(".","",$ld_totser);
			$ld_totser=str_replace(",",".",$ld_totser);
			$ld_subtotser=str_replace(".","",$ld_subtotser);
			$ld_subtotser=str_replace(",",".",$ld_subtotser);
			
			$li_orden=$li;
			
	        $ls_sql=" INSERT INTO soc_dt_servicio (codemp, numordcom, estcondat, codser, canser, monuniser, ".
			        "                              monsubser, montotser, orden, numsol,coduniadm) ".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codser."', ".
					"          ".$li_canser.",".$ld_preser.",".$ld_subtotser.",".$ld_totser.", ".
					"          ".$li_orden.",'".$ls_numsolord."','".$ls_coduniadmsep."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
			    if($lb_sep)
				{
					$lb_valido=$this->uf_actualizar_estatus_item_sep($ls_numsolord,$ls_codser,$aa_seguridad,$as_numordcom,$as_estcondat);
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert� el Servicio ".$ls_codser." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    }
			}
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numordcom,$ai_totrowcargos,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> n�mero de la orden de compra  
		//				   ai_totrowcargos  ---> total de filas de los cargos
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los cargos correctamente o false en caso contrario
		//	  Description: Funcion que inserta los cargos de una Orden de Compra en la tabla segun el tipo de la orden 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por. Miguel Palencia 
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch($as_estcondat)
		{
			case "B": // si es de Bienes
				$ls_tabla="soc_dta_cargos";
				$ls_campo="codart";
			break;
			
			case "S": // si es de Servicios
				$ls_tabla="soc_dts_cargos";
				$ls_campo="codser";
			break;
		}		
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codartser=$_POST["txtcodservic".$li_i];
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$ls_dencar=$_POST["txtdencar".$li_i];
			$ld_bascar=$_POST["txtbascar".$li_i];
			$ld_moncar=$_POST["txtmoncar".$li_i];
			$ld_subcargo=$_POST["txtsubcargo".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$ls_cuentacargo=$_POST["cuentacargo".$li_i];
			$ld_bascar=str_replace(".","",$ld_bascar);
			$ld_bascar=str_replace(",",".",$ld_bascar);			
			$ld_moncar=str_replace(".","",$ld_moncar);
			$ld_moncar=str_replace(",",".",$ld_moncar);
			$ld_subcargo=str_replace(".","",$ld_subcargo);
			$ld_subcargo=str_replace(",",".",$ld_subcargo);
			
			$ls_sql="INSERT INTO ".$ls_tabla." (codemp, numordcom, estcondat, ".$ls_campo.", codcar, monbasimp, monimp, monto, formula)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codartser."','".$ls_codcar."',".
					" 			  ".$ld_bascar.",".$ld_moncar.",".$ld_subcargo.",'".$ls_formulacargo."')";     
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Cargo ".$ls_codcar." a la Orden de Compra ".$as_numordcom. "Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_numordcom  ---> N�mero de la orden de compra 
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowcuentas  ---> Total de Filas de las cuentas Presupuestarias
		//				   ai_totrowcuentascargo  ---> Total de Filas de las cuentas Presupuestarias del Cargo
		//				   aa_seguridad  ---> arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecuci�n Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Yozelin Barrgan
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for ($li_i=1;($li_i<$ai_totrowcuentas)&&($lb_valido);$li_i++)
		    {
			  $ls_codestpro  = trim($_POST["txtcodprogas".$li_i]);
			  $ls_codestpro1 = substr($ls_codestpro,0,20);
			  $ls_codestpro2 = substr($ls_codestpro,20,6);
			  $ls_codestpro3 = substr($ls_codestpro,26,3);
			  $ls_codestpro4 = substr($ls_codestpro,29,2);
			  $ls_codestpro5 = substr($ls_codestpro,31,2);
			  $ls_cuenta     = trim($_POST["txtcuentagas".$li_i]);
			  $li_moncue     = $_POST["txtmoncuegas".$li_i];
			  $li_moncue     = str_replace(".","",$li_moncue);
			  $li_moncue     = str_replace(",",".",$li_moncue);			
			  $this->io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
			  $this->io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
			  $this->io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
			  $this->io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
			  $this->io_dscuentas->insertRow("codestpro5",$ls_codestpro5);			
			  $this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
			  $this->io_dscuentas->insertRow("moncue",$li_moncue);			
		    }
		for ($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
		    {
			  $ls_codestpro	 = trim($_POST["txtcodprocar".$li_i]);
			  $ls_codestpro1 = substr($ls_codestpro,0,20);
			  $ls_codestpro2 = substr($ls_codestpro,20,6);
			  $ls_codestpro3 = substr($ls_codestpro,26,3);
			  $ls_codestpro4 = substr($ls_codestpro,29,2);
			  $ls_codestpro5 = substr($ls_codestpro,31,2);
			  $ls_cuenta	 = trim($_POST["txtcuentacar".$li_i]);
			  $li_moncue	 = $_POST["txtmoncuecar".$li_i];
			  $li_moncue	 = str_replace(".","",$li_moncue);
			  $li_moncue	 = str_replace(",",".",$li_moncue);			
			  $this->io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
			  $this->io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
			  $this->io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
			  $this->io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
			  $this->io_dscuentas->insertRow("codestpro5",$ls_codestpro5);			
			  $this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
			  $this->io_dscuentas->insertRow("moncue",$li_moncue);			
		    }
		$arr_group[0]="codestpro1";
		$arr_group[1]="codestpro2";
		$arr_group[2]="codestpro3";
		$arr_group[3]="codestpro4";
		$arr_group[4]="codestpro5";
		$arr_group[5]="cuenta";
		$this->io_dscuentas->group_by($arr_group,array('0'=>'moncue'),'moncue');
		$li_total=$this->io_dscuentas->getRowCount('codestpro1');	
		for ($li_fila=1;$li_fila<=$li_total;$li_fila++)
		    {
			  $ls_cuenta	 = trim($this->io_dscuentas->getValue('cuenta',$li_fila));
			  $li_moncue	 = $this->io_dscuentas->getValue('moncue',$li_fila);
			  $ls_codestpro1 = $this->io_dscuentas->getValue('codestpro1',$li_fila);
			  $ls_codestpro2 = $this->io_dscuentas->getValue('codestpro2',$li_fila);
			  $ls_codestpro3 = $this->io_dscuentas->getValue('codestpro3',$li_fila);
			  $ls_codestpro4 = $this->io_dscuentas->getValue('codestpro4',$li_fila);
			  $ls_codestpro5 = $this->io_dscuentas->getValue('codestpro5',$li_fila);
			  $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
			  
			  $ls_sql="INSERT INTO soc_cuentagasto (codemp, numordcom, estcondat, codestpro1, codestpro2, codestpro3, codestpro4,  ".
					  "							    codestpro5,spg_cuenta, monto)".
				  	  "	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codestpro1."','".$ls_codestpro2."',".
					  " 		  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."',".$li_moncue.")";        
			  $li_row=$this->io_sql->execute($ls_sql);
			  if($li_row===false)
			  {
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			  }
			  else
			  {
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� la Cuenta ".$ls_cuenta." de programatica ".$ls_codestpro." a la orden de compra ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			  }
		}
		unset($this->io_dscuentas);
		return $lb_valido;
	}// end function uf_insert_cuentas_presupuestarias
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$ai_totrowcuentascargo,$ai_totrowcargos,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowcuentascargo  ---> filas del grid cuentas cargos
		//				   ai_totrowcargos  ---> filas del grid de los creditos
		//				   aa_seguridad  ---> variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: este metodo inserta la cuentas de los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Yozelin Barrgan
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codcar=trim($_POST["txtcodcar".$li_i]);
			$ld_bascar=$_POST["txtbascar".$li_i];
			$ld_moncar=$_POST["txtmoncar".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$ld_bascar=str_replace(".","",$ld_bascar);
			$ld_bascar=str_replace(",",".",$ld_bascar);			
			$ld_moncar=str_replace(".","",$ld_moncar);
			$ld_moncar=str_replace(",",".",$ld_moncar);
			$this->io_dscargos->insertRow("codcar",$ls_codcar);	
			$this->io_dscargos->insertRow("monobjret",$ld_bascar);	
			$this->io_dscargos->insertRow("monret",$ld_moncar);	
			$this->io_dscargos->insertRow("formula",$ls_formulacargo);
		}
		$this->io_dscargos->group_by(array('0'=>'codcar'),array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];		
		if ($ls_tipafeiva=='P')
		   {
			 for ($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
			     {
				   $ls_codcargo   = $_POST["txtcodcar".$li_i];
				   $ls_codpro	  = $_POST["txtcodprocar".$li_i];
				   $ls_spg_cuenta = $_POST["txtcuentacar".$li_i];
			  	   $ld_moncuecar  = $_POST["txtmoncuecar".$li_i];
				   $li_row		  = $this->io_dscargos->find("codcar",$ls_codcargo);		
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_row);
				   $ld_monret	  = $this->io_dscargos->getValue("monret",$li_row);
				   $ls_formula	  = $this->io_dscargos->getValue("formula",$li_row);		
				   $ls_codestpro1 = substr($ls_codpro,0,20);
				   $ls_codestpro2 = substr($ls_codpro,20,6);
				   $ls_codestpro3 = substr($ls_codpro,26,3);
				   $ls_codestpro4 = substr($ls_codpro,29,2);
				   $ls_codestpro5 = substr($ls_codpro,31,2);
				   $ld_moncuecar  = str_replace(".","",$ld_moncuecar);
				   $ld_moncuecar  = str_replace(",",".",$ld_moncuecar);		
				   $ls_sc_cuenta  = "";
				   $lb_valido     = $this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
														            $ls_spg_cuenta,&$ls_sc_cuenta);
				   if ($lb_valido)
				      {
					    $ls_sql="INSERT INTO soc_solicitudcargos (codemp, numordcom,  estcondat, codcar, monobjret, monret, codestpro1, ".
								"                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
								"								  formula, monto) ".
								"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',".$ld_monobjret.", ".
								"			  ".$ld_moncuecar.",'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."', ".
								" 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_spg_cuenta."','".$ls_sc_cuenta."','".$ls_formula."', ".
								"			   ".$ld_moncuecar.")";        
						$li_row=$this->io_sql->execute($ls_sql);
						if ($li_row===false)
						   {
							 $lb_valido=false;
							 $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						   }
						else
						   {
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
							 $ls_evento="INSERT";
							 $ls_descripcion ="Insert� la Cuenta ".$ls_spg_cuenta." de programatica ".$ls_codpro." al cargos ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
							 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   }
					  }
				   else
					  {
					    $this->io_mensajes->message("ERROR-> La cuenta Presupuestaria ".$ls_spg_cuenta." No tiene cuenta contable asociada."); 
					  }
			     }
		   }
		elseif($ls_tipafeiva=='C')
		   { 
		     $li_totrowcre = $this->io_dscargos->getRowCount("codcar");
			 for ($li_i=1;$li_i<=$li_totrowcre;$li_i++)
			     {
				   $ls_codcargo   = $_POST["txtcodcar".$li_i];
				   $li_row		  = $this->io_dscargos->find("codcar",$ls_codcargo);		
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_row);
				   $ld_monret	  = $this->io_dscargos->getValue("monret",$li_row);
				   $ls_formula	  = $this->io_dscargos->getValue("formula",$li_row);
				   $ls_codestpro1 = '--------------------';
				   $ls_codestpro2 = '------';
				   $ls_codestpro3 = '---';
				   $ls_codestpro4 = '--';
				   $ls_codestpro5 = '--';
				   $ls_scgcta     = $_POST["cuentacargo".$li_i];
				   $ls_sql = "INSERT INTO soc_solicitudcargos (codemp, numordcom,  estcondat, codcar, monobjret, monret, codestpro1,
							                                   codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta,
							 								   formula, monto)
							 	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',".$ld_monobjret.",
							 			  ".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',
							  			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_scgcta."','".$ls_scgcta."','".$ls_formula."',
						 			      ".$ld_monret.")";
				   $rs_data = $this->io_sql->execute($ls_sql);
				   if ($rs_data===false)
					  {
					    $lb_valido=false;
						$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_cuentas_cargos (Iva Contable);ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					  }
				   else
					  {
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						 $ls_evento="INSERT";
						 $ls_descripcion ="Insert� la Cuenta Contable ".$ls_scgcta." al cargo ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " de tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
						 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					  } 
				 }
		   }
		return $lb_valido;
	}// end function uf_insert_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  --->  C�digo de Estructura Program�tica
		//	    		   as_codestpro2  --->  C�digo de Estructura Program�tica
		//	    		   as_codestpro3  --->  C�digo de Estructura Program�tica
		//	    		   as_codestpro4  --->  C�digo de Estructura Program�tica
		//	    		   as_codestpro5  --->  C�digo de Estructura Program�tica
		//	    		   as_spgcuenta   --->  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_sccuenta="";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codestpro1='".$as_codestpro1."' ".
				"   AND codestpro2='".$as_codestpro2."' ".
				"   AND codestpro3='".$as_codestpro3."' ".
				"   AND codestpro4='".$as_codestpro4."' ".
				"   AND codestpro5='".$as_codestpro5."' ".
				"   AND spg_cuenta='".$as_spgcuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=$row["sc_cuenta"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_cuentacontable
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numordcom ---> mumero de la orden de compra
		//				   as_estcom  ---> estatus de la orden de compra
		//                 as_estcondat ---> tipo de la orden de compra bienes o servicios
		//	  Description: Funci�n que busca que las cuentas presupuestarias est�n en la program�tica seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 12/05/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS existe ".		
				"  FROM soc_cuentagasto  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$lb_existe=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_existe))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_monto=$row["monto"];
				$li_disponibilidad=$row["disponibilidad"];
				$li_existe=$row["existe"];
				if($li_existe>0)
				{
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
					}
				}
				else
				{
					$lb_existe = false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
				}
				
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_existe)
			{
				$as_estcom=1; // EMITIDA SE DEBE CAMBIAR EN LETRAS (E)
			}
			else
			{
				$as_estcom=0; // REGISTRO SE DEBE CAMBIAR EN LETRAS (R)
			}
			$ls_sql="UPDATE soc_ordencompra ".
					"   SET estcom='".$as_estcom."' ".
					" WHERE codemp = '".$this->ls_codemp."' AND ".
					"	    numordcom = '".$as_numordcom."' AND ".
					"       estcondat= '".$as_estcondat."'  ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_enlace_sep
		//		   Access: private
		//	    Arguments: as_numordcom  ---> n�mero de la Orden de Compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowbienes  ---> total de filas de bienes
		//                 as_estcom   ---> estatus de la orden de compra 
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for ($li=1;($li<$ai_totrow)&&($lb_valido);$li++)
		    {
			  $ls_numsolord    = $_POST["txtnumsolord".$li];
			  $ls_coduniadmsep = $_POST["txtcoduniadmsep".$li];
			  if (!empty($ls_numsolord))
			     {
				   $this->io_dssolicitud->insertRow("numsol",$ls_numsolord);
				   $this->io_dssolicitud->insertRow("numordcom",$as_numordcom);
				   $this->io_dssolicitud->insertRow("canart",0);
				   $this->io_dssolicitud->insertRow("coduniadmsep",$ls_coduniadmsep);
				 }
		    }
		$this->io_dssolicitud->group_by(array('0'=>'numsol','1'=>'numordcom','2'=>'coduniadmsep'),array('0'=>'canart'),'canart');
		$li_total=$this->io_dssolicitud->getRowCount('numsol');
		for ($li_fila=1;$li_fila<=$li_total;$li_fila++)
		    {
			  $ls_numordcom    = $this->io_dssolicitud->getValue('numordcom',$li_fila);
			  $ls_numsol       = trim($this->io_dssolicitud->getValue('numsol',$li_fila));
			  $ls_coduniadmsep = $this->io_dssolicitud->getValue('coduniadmsep',$li_fila);
			  $ls_sql          = " INSERT INTO soc_enlace_sep (codemp, numordcom, estcondat, numsol, estordcom, coduniadm)".
					              "  VALUES ('".$this->ls_codemp."','".$ls_numordcom."','".$as_estcondat."', ".
					              "          '".$ls_numsol."','".$as_estcom."','".$ls_coduniadmsep."')";
			  $li_row = $this->io_sql->execute($ls_sql);
			  if ($li_row===false)
			     {
				   $lb_valido=false;
				   $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_insert_enlace_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			     }
			  else
			     {
				   $lb_valido=$this->uf_actualizar_estatus_sep($ls_numsol,$aa_seguridad,$as_numordcom);
				   if ($lb_valido)
				      {
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					    $ls_evento="INSERT";
					    $ls_descripcion ="Insert� la sep ".$ls_numsolord." a la Orden de Compra  ".$as_numordcom." tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
					    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				      }
			     }
		    }
		return $lb_valido;
	}// end function uf_insert_enlace_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_estatus_sep($as_numsolord,$aa_seguridad,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estatus_sep
		//		   Access: private
		//	    Arguments: as_numsolord  ---> n�mero de la sep tomada por una  orden de Compra
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//                 as_numordcom  ---> numero de la orden de compra
		//	      Returns: true si se actualizaron correctamente los articulos y falso en caso contrario
		//	  Description: este metodo actualiza el estatus estsol(estatus de ls solicitud) en la sep que
		//                 significa que el bien fue tomado por una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" UPDATE sep_solicitud SET estsol='P'      ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"	     numsol='".$as_numsolord."'    ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_actualizar_estatus_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo la sep  numero ".$as_numsolord." tomada por la orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_actualizar_estatus_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_orden_compra(&$as_estcom,$ad_fecordcom,$ai_estsegcom,$as_numordcom,$as_coduniadm,$as_codfuefin,$as_estcondat,
	                                $as_codprov,$as_forpag,$ad_antpag,$as_estlugcom,$as_concom,$as_codtipmod,$as_conordcom,
									$as_obsordcom,$as_lugentnomdep,$as_lugentdir,$as_diaplacom,$ad_porsegcom,$ad_monsegcom,$as_codpai,
									$as_codest,$as_codmun,$as_codpar,$as_codmon,$ad_tascamordcom,$ad_montotdiv,$as_codestpro1,
									$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$ai_totrowbienes,
									$ai_totrowservicios,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,
									$ad_subtotal,$ad_cargos,$ad_total,$aa_seguridad,$as_tipsol,$as_numsoldel,$as_uniejeaso,
									$as_fecentordcom,$as_perentdesde,$as_perenthasta)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_orden_compra
	//	    Arguments: as_estcom  --->   Estatus de la orden de compra
	//				   ad_fecordcom ---> Fecha de registro de la orden de compra
	//				   ai_estsegcom ---> Estatus si existe seguro para la orden de compra
	//				   as_numordcom ---> Numero de la orden de compra
	//                 $as_coduniadm --->Codigo de unidad administrativa
	//				   as_codfuefin ---> C�digo de Fuente de financiamiento
	//				   as_estcondat --->  tipo de la orden de compra 
	//                 $as_concom  --->  Condicion de la compra	 
	//				   $as_codtipmod ---> Tipo de la modalidad
	//                 $as_conordcom ---> concepto de la orden de compra
	//				   $as_obsordcom ---> Observacion de la orden de compra
	//                 $as_lugentnomdep ---> Lugar de Entega Nombre de la Dependencia
	//                 $as_lugentdir --->  Lugar de entrega de la direccion 
	//                 $as_diaplacom --->  Dias de plazo de la orden de compra  
	//				   $ad_porsegcom ---> Porcentaje de la orden de compra 
	//                 $ad_monsegcom ---> Monto del seguro de la orden de compra 
	//				   $as_codpai    ---> Codigo del Pais
	//				   $as_codest    ---> Codigo del Estado 
	//				   $as_codmun    ---> Codigo del Municipio
	//				   $as_codpar    ---> Codigo del Parroquia
	//                 $as_codmon    ---> Codigo de la moneda
	//				   $ad_tascamordcom ---> Tasa de cambio d ela orden de compra
	//                 $ad_montotdiv --->  Monto de la Divisa
	//				   as_codprov ---> C�digo de Proveedor 
	//                 $as_forpag ---> Forma de Pago
	//                 $ad_antpag ---> Anticipo de Pago
	//                 $as_estlugcom --->Lugar de la Compra
	//				   ad_subtotal  --->  Subtotal de la solicitu
	//				   ad_cargos  --->  Monto del cargo
	//				   ad_total  --->  Total de la solicitud
	//				   as_codestpro1  --->  C�digo Estructura Program�tica 1
	//				   as_codestpro2  --->  C�digo Estructura Program�tica 2
	//				   as_codestpro3  --->  C�digo Estructura Program�tica 3
	//				   as_codestpro4  --->  C�digo Estructura Program�tica 4
	//				   as_codestpro5  ---> C�digo Estructura Program�tica 5
	//				   ai_totrowbienes  --->  Total de Filas de Bienes
	//				   ai_totrowcargos  --->  Total de Filas de Servicios
	//				   ai_totrowcuentas  --->  Total de Filas de Cuentas
	//				   ai_totrowcuentascargo  --->  Total de Filas de Cuentas de los cargos
	//				   ai_totrowservicios  --->  Total de Filas de Servicios
	//				   aa_seguridad  --->  arreglo de las variables de seguridad
	//                 as_numsoldel  ---> numero de solicitudes a eliminar
	//                 as_uniejeaso ---> unidades ejecutoras asociadas que vengan de una sep
	//	      Returns: devuelve true si se inserto correctamente la orden de compra o false en caso contrario
	//	  Description: Funcion que que se encarga dde insertar una orden de compra
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;    
	if($lb_valido)
	{
     	$ld_monsubtotbie = 0;
     	$ld_monsubtotser = 0;
     	$ld_monbasimp = 0;
     	$ld_mondes = 0;
		$li_estpenalm = 0;
		$li_estapro   = 0;
		$ld_fecaprord = "1900-01-01";
		$ls_codusuapr = "";
		$ls_numpolcon = 0;
		if($as_estlugcom=='N')
		{ $as_estlugcom = 0 ;}
		else
		{ $as_estlugcom = 1 ;}
		if($as_codmon=="")
		{
		  $as_codmon='---';
		}
		if($as_codfuefin=="")
		{
		  $as_codfuefin='--';
		}
		if($as_estcom=='R')
		{
		  $as_estcom=0;
		}
		elseif($as_estcom=='E')
		{
		 $as_estcom=1;
		}
		if($as_diaplacom=="")
		{
		  $as_diaplacom=0;
		}
		if($as_codtipmod=="")
		{
	       $as_codtipmod="--";
		}
		if ($as_coduniadm=="" || $as_coduniadm=="---")
		   {  
	         $as_coduniadm="----------";   	
		   }
		if (empty($as_fecentordcom))
		   {
		     $as_fecentordcom = '1900-01-01';
		   }
		if($as_perentdesde=="")
		{
	       $as_perentdesde="1900-01-01";   	
		}
		if($as_perenthasta=="")
		{ 
	       $as_perenthasta="1900-01-01";   	
		}	
        $as_obsordcom=substr($as_obsordcom,0,499);
        $as_conordcom=substr($as_conordcom,0,499);
		$this->uf_buscar_numerocotiza($as_numordcom,$as_estcondat,&$as_numanacot);
		
		 $ls_sql=" UPDATE soc_ordencompra ". 
			     " SET    codmon      ='".$as_codmon."'  ,      codfuefin   ='".$as_codfuefin."'   ,".
			     "        coduniadm   ='".$as_coduniadm."'   ,".
			     "        codtipmod   ='".$as_codtipmod."' ,    fecordcom   ='".$ad_fecordcom."'   ,".
				 "        estsegcom   ='".$ai_estsegcom."' ,    porsegcom   =".$ad_porsegcom."     ,".
				 "        monsegcom   ='".$ad_monsegcom."',     forpagcom   ='".$as_forpag."'      ,".
				 "        estcom      ='".$as_estcom."',        diaplacom   ='".$as_diaplacom."'   ,".
				 "        concom      ='".$as_concom."',        obscom      ='".$as_conordcom."'   ,".
				 "        monsubtotbie =".$ld_monsubtotbie.",   monsubtotser =".$ld_monsubtotser." ,".
				 "        monsubtot    =".$ad_subtotal."   ,    monbasimp    =".$ld_monbasimp."    ,".
				 "        monimp       ='".$ad_cargos."' ,      mondes       =".$ld_mondes."       ,".
				 "        montot       =".$ad_total.",          estpenalm    =".$li_estpenalm."    ,".			  
				 "        codpai       ='".$as_codpai."',       codest       ='".$as_codest."'     ,".			  
				 "        codmun       ='".$as_codmun."' ,      codpar       ='".$as_codpar."'     ,".
				 "        lugentnomdep ='".$as_lugentnomdep."', lugentdir    ='".$as_lugentdir."'  ,".
				 "        monant       ='".$ad_antpag."' ,      estlugcom    ='".$as_estlugcom."'  ,".
				 "        tascamordcom =".$ad_tascamordcom." ,  montotdiv    =".$ad_montotdiv."    ,".
				 "        estapro      =".$li_estapro." ,       obsordcom    ='".$as_obsordcom."'     ,".
				 "        uniejeaso    ='".$as_uniejeaso."' ,   fecent='".$as_fecentordcom."',       ".
				  "       fechentdesde    ='".$as_perentdesde."' ,   fechenthasta='".$as_perenthasta."',numanacot='".$as_numanacot."' ".
				 " WHERE  codemp  ='".$this->ls_codemp."' AND  numordcom='".$as_numordcom."'  AND   ".
				 "        cod_pro ='".$as_codprov."'      AND  estcondat='".$as_estcondat."' ";
		$this->io_sql->begin_transaction();				
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_update_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Se Actualizo la Orden de Compra ".$as_numordcom." tipo ".$as_estcondat." de fecha".$ad_fecordcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_sep = false;			
			$lb_valido=$this->uf_delete_cuentas_presupuestarias($as_numordcom,$as_estcondat,$aa_seguridad);
			if($lb_valido)
			{ 
			  $lb_valido=$this->uf_delete_cuentas_cargos($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			if($lb_valido)
			{  
			  $lb_valido=$this->uf_delete_cargos($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			if($lb_valido)
			{ 
			  $lb_valido=$this->uf_delete_detalle_orden_compra($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			if($lb_valido)
			{ 
			  $la_enlace_sep=array();
			  $lb_valido=$this->uf_delete_enlace_sep($as_numordcom,$as_estcondat,$la_enlace_sep,$aa_seguridad);
			}
			if($as_estcondat=="B")
			{ 
				$lb_valido=$this->uf_insert_bienes($as_numordcom,$as_estcondat,$ai_totrowbienes,$aa_seguridad,$as_tipsol,$lb_sep);
			}
			elseif($as_estcondat=="S")
			{  
				$lb_valido=$this->uf_insert_servicios($as_numordcom,$as_estcondat,$ai_totrowservicios,$aa_seguridad,$as_tipsol,$lb_sep);
			}
	        if($lb_valido)
			{  
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,$ai_totrowcargos,$as_estcondat,$aa_seguridad);
			}
	        if($lb_valido)
			{ 
			    $lb_valido=$this->uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
			}
	        if($lb_valido)
			{  
				$lb_valido=$this->uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$ai_totrowcuentascargo,$ai_totrowcargos,$aa_seguridad);
			}
			if($lb_valido)
			{ 
				$lb_valido=$this->uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat);
			}
	        if($lb_valido)
			{ 
			  if($lb_sep)
			  { 
				 if($as_estcondat=='B') { $li_totrow=$ai_totrowbienes; }
				 else { $li_totrow=$ai_totrowservicios; }
				 $lb_valido=$this->uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$li_totrow,$aa_seguridad);
			     if ($lb_valido)
				    { 
					  $this->uf_update_sep_no_incorporadas($la_enlace_sep,$as_numordcom,$as_estcondat,$aa_seguridad);
					}
			  }	 
			}
			if($lb_valido)
			{	
				if($as_estcondat=='B')
				{
					$this->io_mensajes->message("La Orden de Compra fue Actualizada !!!");
					$this->io_sql->commit();
				}
				else
				{
					$this->io_mensajes->message("La Orden de Servicio fue Actualizada !!!");
					$this->io_sql->commit();
				}
			}
			else
			{
				if($as_estcondat=='B')
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Actualizada la Orden de Compra !!!"); 
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Actualizada la Orden de Servicio !!!"); 
					$this->io_sql->rollback();
				}
			}
	    }
	}
	return $lb_valido;
	}// fin uf_update_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cuentas_presupuestarias($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cuentas_presupuestarias
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente las cuentas presupuestarias o false en caso contrario
		//	  Description: este metodo elimina las ceuntas presupuestaruias asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" DELETE ".
				" FROM   soc_cuentagasto ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numordcom='".$as_numordcom."' AND ".
				"        estcondat='".$as_estcondat."' ";   
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_cuentas_presupuestarias ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Se eliminaron las cuentas presupuestarias de la orden de compra ".$as_numordcom."Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_cuentas_presupuestarias
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cuentas_cargos($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente las cuentas presupuestarias del cargo  o false en caso contrario
		//	  Description: este metodo elimina las ceuntas presupuestaruias de los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" DELETE ".
				" FROM   soc_solicitudcargos ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numordcom='".$as_numordcom."' AND ".
				"        estcondat='".$as_estcondat."' "; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Se eliminaron las cuentas presupuestarias de los cargos de la orden de compra ".$as_numordcom."Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cargos($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente los cargo  o false en caso contrario
		//	  Description: este metodo elimina los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_estcondat)
		{
			case "B": // si es de Bienes
				$ls_tabla="soc_dta_cargos";
				$ls_campo="codart";
			break;
			
			case "S": // si es de Servicios
				$ls_tabla="soc_dts_cargos";
				$ls_campo="codser";
			break;
		}		
		$ls_sql=" DELETE ".
				" FROM    ".$ls_tabla."  ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numordcom='".$as_numordcom."' AND ".
				"        estcondat='".$as_estcondat."' ";   
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Se eliminaron los cargos de la orden de compra ".$as_numordcom." de tipo ".$as_estcondat."  Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_enlace_sep($as_numordcom,$as_estcondat,&$la_enlace_sep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_enlace_sep
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente el enlace de la orden de compra con la sep o false en caso contrario
		//	  Description: este metodo elimina los enlace de la sep asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql=" SELECT numsol 
		            FROM soc_enlace_sep 
				   WHERE codemp='".$this->ls_codemp."' 
				     AND numordcom='".$as_numordcom."' 
					 AND estcondat='".$as_estcondat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_enlace_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
		  $i=0;
		  while ($row=$this->io_sql->fetch_row($rs_data))	  
			    {					   		     
			      $i++;
				  $ls_numsol = trim($row["numsol"]); 
			      $la_enlace_sep[$i]['numsep'] = $ls_numsol;
				  $lb_valido = $this->uf_update_item_sep($ls_numsol,$as_estcondat,$aa_seguridad,$as_numordcom);
				  if ($lb_valido)
				     {
					   $ls_sql = " DELETE 
							         FROM soc_enlace_sep 
							        WHERE codemp='".$this->ls_codemp."' 
									  AND numordcom='".$as_numordcom."' 
									  AND numsol='".$ls_numsol."' 
									  AND estcondat='".$as_estcondat."'";
					   $li_row=$this->io_sql->execute($ls_sql);
					   if ($li_row===false)
				 	      {
						    $lb_valido=false;
						    $this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_enlace_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					      }
					   else
					      {
						    /////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
							$ls_evento="DELETE";
							$ls_descripcion ="Se eliminaron los enlace de la orden de compra ".$as_numordcom." de tipo ".$as_estcondat." con la solicitud presupuestaria  $ls_numsol, Asociado a la empresa ".$this->ls_codemp;
							$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
					      }
				     }
			    }	
		}	 
		return $lb_valido;
	}// end function uf_delete_enlace_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalle_orden_compra($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalle_orden_compra
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente los detalles de la orden de compra o false en caso contrario
		//	  Description: este metodo elimina los detalles de bienes o de servicio dependiendo el tipo asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_estcondat)
		{
			case "B": // si es de Bienes
				$ls_tabla="soc_dt_bienes";
			break;
			
			case "S": // si es de Servicios
				$ls_tabla="soc_dt_servicio";
			break;
		}		
		
		$ls_sql=" DELETE ".
				" FROM   ".$ls_tabla." ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numordcom='".$as_numordcom."' AND ".
				"        estcondat='".$as_estcondat."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_detalle_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Se eliminaron los detalles de la orden de compra ".$as_numordcom." de tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_detalle_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_item_sep($as_numsol,$as_estcondat,$aa_seguridad,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_item_sep
		//		   Access: private
		//	    Arguments: as_numsol  ---> numero de la sep
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se actualiza los item de una sep correctamente  o false en caso contrario
		//	  Description: este metodo actualiza los item de una sep asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_estcondat)
		{
			case "B": // si es de Bienes
				$ls_tabla="sep_dt_articulos";
			break;
			
			case "S": // si es de Servicios
				$ls_tabla="sep_dt_servicio";
			break;
		}		
		$ls_numdocdes="";
		$ls_sql=" UPDATE ".$ls_tabla."  ".
				" SET    estincite='NI', ".
				"        numdocdes='".$ls_numdocdes."' ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".
				"        numsol='".$as_numsol."' AND        ".
				"        numdocdes='".$as_numordcom."' AND  ".
				"        estincite='OC'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_update_item_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Se Actualizo el estatus de incorporacion del item (NI - no incorporado) de la sep ".$as_numsol."Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_item_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_liberar_solicitud_presupuestaria($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_solicitud_presupuestaria
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se liberaron y eliminaron correctamente el enlace de la orden de compra con la sep o false en caso contrario
		//	  Description: este metodo que liberar y elimina los enlace de la sep asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql=" SELECT  *       ".
		 	    " FROM   soc_enlace_sep ".
				" WHERE  codemp='".$this->ls_codemp."' AND ".      
				"	     numordcom='".$as_numordcom."' AND ". 
				"	     estcondat='".$as_estcondat."'  ";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_liberar_solicitud_presupuestaria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			 while($row=$this->io_sql->fetch_row($rs_data))	  
			 {					   		     
			    $ls_numsol = trim($row["numsol"]); 
				$lb_valido=$this->uf_update_item_sep($ls_numsol,$as_estcondat,$aa_seguridad,$as_numordcom);
				if($lb_valido)
				{
					$li_numrows = $this->uf_verificar_item_sep($ls_numsol,$as_estcondat);
					if ($li_numrows<=0)
					{
						$ls_sql=" UPDATE sep_solicitud ".
								" SET    estsol='C'    ".
								" WHERE  codemp='".$this->ls_codemp."' AND ".
								"        numsol='".$ls_numsol."' ";	
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_liberar_solicitud_presupuestaria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}
						else
						{
						   $lb_valido=$this->uf_update_item_sep($ls_numsol,$as_estcondat,$aa_seguridad,$as_numordcom);
						}
					}
					else
					{
						 $lb_valido=$this->uf_update_item_sep($ls_numsol,$as_estcondat,$aa_seguridad,$as_numordcom);
					}
			    }		
			}
		}
		return $lb_valido;
	}// end function uf_liberar_solicitud_presupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_item_sep($as_numsol,$as_estcondat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_item_sep
		//		   Access: private
		//	    Arguments: as_numsol  ---> numero de la solicitud
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//	      Returns: true si se eliminaron correctamente los detalles de la orden de compra o false en caso contrario
		//	  Description: este metodo verifica si existen item de la sep
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_estcondat)
		{
			case "B": // si es de Bienes
				$ls_tabla="sep_dt_articulos";
			break;
			
			case "S": // si es de Servicios
				$ls_tabla="sep_dt_servicio";
			break;
		}		
	    $ls_sql = "SELECT sep_solicitud.numsol 
					FROM  sep_solicitud, $ls_tabla 
				   WHERE  sep_solicitud.codemp='".$this->ls_codemp."' 
					 AND  sep_solicitud.numsol='".$as_numsol."'
					 AND  $ls_tabla.estincite<>'NI'
					 AND  sep_solicitud.codemp=$ls_tabla.codemp
					 AND  sep_solicitud.numsol=$ls_tabla.numsol";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_verificar_item_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
		  $li_numrows = $this->io_sql->num_rows($li_row);
		}
		return $li_numrows;
	}// end function uf_verificar_item_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_ultima_orden_compra($as_estcondat,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_ultima_orden_compra
		//		   Access: private
		//	    Arguments: $as_estcondat --->  Estatus de la orden de compra
		// 	      Returns: devuelve el ultimo numero de la orden de compra 
		//	  Description: Funcion que verifica si existe una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$lb_valido=true;
		$ls_sql=" SELECT numordcom ".
				" FROM   soc_ordencompra ".
				" WHERE  estcondat='".$as_estcondat."' ".
				" ORDER BY numordcom DESC LIMIT 1 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_buscar_ultima_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			 while($row=$this->io_sql->fetch_row($rs_data))	  
			 {					   		     
			    $ls_ultnumordcom = trim($row["numordcom"]);
				if($as_numordcom<$ls_ultnumordcom)
				{
				  $lb_valido=false;
				}
				elseif($as_numordcom==$ls_ultnumordcom)
				{
				  $lb_valido=true;
				} 
			 }
			 $this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_buscar_ultima_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_validacion_tepuy_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_validacion_tepuy_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Secci�n a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Funci�n que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 16/05/2008 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT value ".
				"  FROM tepuy_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO M�TODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
		        $lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_buscar_validacion_tepuy_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_orden_compra($as_numordcom,$as_estcondat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalle_orden_compra
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
	    //				   as_estcondat ---> Estatus de la  la orden de compra si es bien o servicio
		//                 aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se eliminaron correctamente la orden de compra o false en caso contrario
		//	  Description: este metodo elimina una orden de compra completa
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
	//print "orden ".$as_numordcom." EST:".$as_estcondat;
		$lb_valido=$this->uf_select_orden_compra($as_numordcom,$as_estcondat);
		if($lb_valido)
		{
			$lb_valido=$this->uf_buscar_validacion_tepuy_config("SOC","PERMISO","ELIMINAR_COMPRAS_INTERMEDIAS","tepuy_soc_p_registro_orden_compra.php","C");
	
			if(!$lb_valido)
			{
			   $lb_valido=$this->uf_buscar_ultima_orden_compra($as_estcondat,$as_numordcom);
			}
			$lb_valido=true; //permite eliminar cualquier orden sin preservar la integridad
			if($lb_valido)
			{
			  $lb_valido=$this->uf_delete_cuentas_presupuestarias($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			else
			{
				$this->io_mensajes->message("La Orden de Compra no puede ser Eliminada debe ser Anulada !!!");
			}
			if($lb_valido)
			{
			  $lb_valido=$this->uf_delete_cuentas_cargos($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			if($lb_valido)
			{
			  $lb_valido=$this->uf_delete_cargos($as_numordcom,$as_estcondat,$aa_seguridad);
			}
			if($lb_valido)
			{
			  $lb_valido=$this->uf_delete_detalle_orden_compra($as_numordcom,$as_estcondat,$aa_seguridad);
			}
		    if($lb_valido)
			{
			  $lb_valido=$this->uf_liberar_solicitud_presupuestaria($as_numordcom,$as_estcondat,$aa_seguridad);
			}
		    if($lb_valido)
			{
			  $la_enlace_sep=array();
			  $lb_valido=$this->uf_delete_enlace_sep($as_numordcom,$as_estcondat,$la_enlace_sep,$aa_seguridad);
			}
			if($lb_valido)
			{
				$ls_sql=" DELETE ".
						" FROM   soc_ordencompra ".
						" WHERE  codemp='".$this->ls_codemp."' AND ".
						"        numordcom='".$as_numordcom."' AND ".
						"        estcondat='".$as_estcondat."' "; 
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_delete_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Se elimino la orden de compra ".$as_numordcom." de tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////		
					if($lb_valido)
					{
						if($as_estcondat=='B')
						{
							$this->io_mensajes->message("La Orden de Compra fue Eliminada !!!");
							$this->io_sql->commit();
						}
						else
						{
							$this->io_mensajes->message("La Orden de Servicio fue Eliminada !!!");
							$this->io_sql->commit();
						}
					}
					else
					{
						if($as_estcondat=='B')
						{
							$lb_valido=false;
							$this->io_mensajes->message("Ocurrio un Error al Eliminada la Orden de Compra !!!"); 
							$this->io_sql->rollback();
						}
						else
						{
							$lb_valido=false;
							$this->io_mensajes->message("Ocurrio un Error al Eliminada la Orden de Servicio !!!"); 
							$this->io_sql->rollback();
						}
					}
				}
			}
		}
		else
		{
			$this->io_mensajes->message("La Orden de Compra o de Servicio no existe !!!"); 
		}
		return $lb_valido;
	}// end function uf_delete_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_item_no_incorporados($as_numsol,$as_estcondat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_item_no_incorporados
		//		   Access: private
		//	    Arguments: as_numsol  --->  N�mero de la solicitud
		//                 $as_estcondat --->  Estatus de la orden de compra
		// 	      Returns: true si se existe la orden de compra o false en caso contrario
		//	  Description: Funcion que verifica si existe una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_estcondat)
		{
		  case 'B':
				$ls_sql=" SELECT codart as codigoartser ".
						"   FROM sep_dt_articulos ".
						"  WHERE codemp='".$this->ls_codemp."' AND ".
						"        numsol = '".$as_numsol."'   AND ".
						"        estincite='NI' ";
		  break;
		  
		  case 'S':
				$ls_sql=" SELECT codser as codigoartser ".
						"   FROM sep_dt_servicio ".
						"  WHERE codemp='".$this->ls_codemp."' AND ".
						"        numsol = '".$as_numsol."'   AND ".
						"        estincite='NI' ";
		  break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_select_item_no_incorporados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_item_no_incorporados
	//-----------------------------------------------------------------------------------------------------------------------------------	

	function uf_update_sep_no_incorporadas($aa_enlace_sep,$as_numordcom,$as_tipordcom,$aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_sep_no_incorporadas
	//		   Access: private
	//	    Arguments: as_numordcom   : N�mero de la orden de compra
	//				   $aa_enlace_sep : Arreglo cargado con las Solicitudes de ejecucion presupuestarias almacenas 
	//                                  originalmente en la Orden de Compra.
	//                 $aa_seguridad  : Arreglo con los parametros de seguridad
	//	      Returns: True si se eliminaron correctamente la orden de compra o false en caso contrario
	//	  Description: M�todo que a partir de los enlaces existentes en la tabla de soc_enlace_sep originalmente dentro de la
	//                 Orden de compra, se verifica si a�n existe en dicha tabla y en caso de que no exista este enlace, debemos
	//                 verificar que en el detalle de la sep, el campo estincite(Estatus de la Incorporacion del Item) 
	//                 sea igual a NI=No incorporado, para devolver la SEP a estatus contabilizada, de lo contrario 
	//                 permanece en P=Procesada.
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creaci�n: 03/02/2008 								Fecha �ltima Modificaci�n : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  if ($as_tipordcom=='B')
	     {
		   $ls_tabla = 'sep_dt_articulos';
		 }
	  elseif($as_tipordcom=='S')
	     {
		   $ls_tabla = 'sep_dt_servicio';
		 }
	  $li_totrows = count($aa_enlace_sep);
	  for ($y=1;$y<=$li_totrows;$y++)
	      {
		    $ls_numsep = $aa_enlace_sep[$y]['numsep'];
			$ls_sql    = "SELECT numsol 
			                FROM soc_enlace_sep 
						   WHERE codemp    = '".$this->ls_codemp."' 
						     AND numordcom = '".$as_numordcom."' 
							 AND estcondat = '".$as_tipordcom."' 
							 AND numsol    = '".$ls_numsep."'";
		    $rs_data = $this->io_sql->select($ls_sql);
			if ($rs_data===false)
			   {
			     $lb_valido=false;
			  	 $this->io_mensajes->message("CLASE->uf_update_sep_no_incorporadas.php;M�TODO->uf_delete_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			   }
		    else
			   {
			     $li_numrows = $this->io_sql->num_rows($rs_data);
				 if ($li_numrows<=0)
				    {
					  $ls_sql = "SELECT numsol 
					               FROM $ls_tabla 
								  WHERE codemp='".$this->ls_codemp."' 
								    AND numsol='".$ls_numsep."' 
									AND estincite<>'NI'";
					  $rs_data = $this->io_sql->select($ls_sql);
					  if ($rs_data===false)
					     {
						   $lb_valido=false;
						   $this->io_mensajes->message("CLASE->uf_update_sep_no_incorporadas.php;M�TODO->uf_delete_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						 }
					  else
					     {
						   if ($li_numrows<=0)
							  {
							    $ls_sql = "UPDATE sep_solicitud 
								              SET estsol='C' 
											WHERE codemp='".$this->ls_codemp."'
								              AND numsol='".$ls_numsep."'";
							    $rs_data = $this->io_sql->select($ls_sql);
							    if ($rs_data===false)
								   {
								     $lb_valido=false;
								     $this->io_mensajes->message("CLASE->uf_update_sep_no_incorporadas.php;M�TODO->uf_delete_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
								   }
						      }
					     }
			        }
		       }
	      }
	  return $lb_valido;
	}	
  //-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_buscar_sep(&$as_numsol,&$as_coduniadm,&$as_denoadm,&$as_consol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_sep
		//		   Access: private
		//	    Arguments: as_numordcom  --->  N�mero de la orden de compra
		//                 as_coduniadm --->  C�digo de la unidad
		// 	      Returns: true si se existe la orden de compra o false en caso contrario
		//	  Description: Funcion que buscal el concepto de sep
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/09/2008 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql=" SELECT numsol,coduniadm,consol, ".
		        " (SELECT denuniadm from spg_unidadadministrativa where codemp='".$this->ls_codemp."' ".
				"  and spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) as denuniadm".
				"   FROM sep_solicitud ".
				"  WHERE codemp='".$this->ls_codemp."' AND ".
				"        numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_buscar_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$as_numsol=$row["numsol"];				
				$as_coduniadm=$row["coduniadm"];
				$as_denoadm=$row["denuniadm"];
				$as_consol=$row["consol"];
			}
		}
		return $lb_existe;
	}// end function uf_buscar_concepto
 //----------------------------------------------------------------------------------------------------------------------------- 	
	function uf_buscar_numerocotiza($as_numordcom,$as_estcondat,&$as_numanacot)
	{
		$lb_valido=true;
	    $ls_sql=" SELECT numanacot ".
				"   FROM soc_ordencompra ".
				"  WHERE codemp='".$this->ls_codemp."' AND ".
				"        numordcom = '".$as_numordcom."'   AND ".
				"        estcondat='".$as_estcondat."' ";
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_registro_orden_compra.php;M�TODO->uf_buscar_numerocotiza ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_existe=true;
					$as_numanacot=$row["numanacot"];				
				}
		}
		return $rs_data;
	}// end function uf_buscar_numerocotiza
	
	function uf_buscar_coduniadmsep()
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_coduniadmsep
		//		   Access: public
		//		 Argument: as_numero ---> n�mero de solicitud o la orden de compra
		//                 as_tipsol ---> tipo de solicitud sep o soc
		//	  Description: Funci�n que busca el codigo y la denominaion de la unidad administrativa de la sep.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 31/10/2008								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_solicitud.numsol,sep_solicitud.coduniadm,  ".
			        "        (SELECT denuniadm        ". 
					"         FROM  spg_unidadadministrativa ".
					"		  WHERE spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND ".
					"               spg_unidadadministrativa.codemp=sep_solicitud.codemp AND ".
					"               spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) AS denuniadm".
				    " FROM sep_solicitud,soc_dtsc_bienes ,sep_dt_articulos,soc_cotizacion,soc_cotxanalisis,soc_ordencompra".
					" WHERE sep_solicitud.codemp= '".$this->ls_codemp."'".
					" AND sep_solicitud.codemp= soc_dtsc_bienes.codemp ".
					" AND sep_solicitud.numsol= soc_dtsc_bienes.numsep ".
					" AND sep_solicitud.numsol=sep_dt_articulos.numsol ".
					" AND sep_dt_articulos.codart=soc_dtsc_bienes.codart ".
					" AND soc_dtsc_bienes.numsolcot=soc_cotizacion.numsolcot ".
					" AND soc_cotizacion.numcot=soc_cotxanalisis.numcot ".
					" AND soc_ordencompra.numanacot=soc_cotxanalisis.numanacot".
					" AND soc_ordencompra.cod_pro=soc_cotxanalisis.cod_pro "; 
				
		//print $ls_sql."<br>";
		$rs_data1=$this->io_sql->select($ls_sql);
		if($rs_data1===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data1;
	}// end function uf_buscar_coduniadmsep

}
?>
