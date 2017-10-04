<?php
class tepuy_cxp_c_solicitudpago
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_cxp_c_solicitudpago($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_cxp_c_recepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 02/04/2007 								Fecha �ltima Modificaci�n : 
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
		require_once($as_path."shared/class_folder/tepuy_c_generar_consecutivo.php");
		$this->io_keygen= new tepuy_c_generar_consecutivo();
		require_once($as_path."shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}// end function tepuy_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 02/04/2007								Fecha �ltima Modificaci�n : 
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
	function uf_validar_fecha_solicitud($ad_fecemisol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_solicitud
		//		   Access: private
		//		 Argument: $ad_fecemisol // fecha de emision de solicitud de pago
		//	  Description: Funci�n que busca la fecha de la �ltima sep y la compara con la fecha actual
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 26/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol,fecemisol ".
				"  FROM cxp_solicitudes  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numsol DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_validar_fecha_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$row["fecemisol"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecemisol); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
								 $ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol     // N�mero de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codfuefin  // Codigo de Fuente de Financiamiento
		//				   as_tipproben  // Tipo Proveedor/Beneficiario 
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   as_codtipsol  // C�digo Tipo de solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   ai_monsol     // Monto de la Solicitud
		//				   as_obssol     // Observacion de la Solicitud
		//				   as_estsol     // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Filas de R.D.
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta la Solicitud de Pagos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 23/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_numsolaux=$as_numsol;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CXP","cxp_solicitudes","numsol","CXPSOP",15,"","","",&$as_numsol);
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO cxp_solicitudes (codemp, numsol, cod_pro, ced_bene, codfuefin, tipproben, fecemisol, consol,".
					"                             estprosol, monsol, obssol, estaprosol,procede)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_codpro."','".trim($as_cedbene)."',".
					" 			  '".$as_codfuefin."','".$as_tipproben."','".$ad_fecemisol."','".$as_consol."','".$as_estsol."',".
					"			  ".$ai_monsol.",'".$as_obssol."',0,'CXPSOP')";
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
				{
					$lb_valido=$this->uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
														  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
														  $ai_totrowrecepciones,$aa_seguridad);
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
				/*$this->io_rcbsf->io_ds_datos->insertRow("campo","monsolaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_monsol);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_solicitudes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
				*//////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones, $ad_fecemisol, $aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_historico_solicitud($as_numsol, $ad_fecemisol, $aa_seguridad);
				}			
				if($lb_valido)
				{	
					if($ls_numsolaux!=$as_numsol)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Solicitud: ".$as_numsol);
					}
					$lb_valido=true;
					$this->io_sql->commit();
					$this->io_mensajes->message("La Solicitud ha sido Registrada."); 
				}			
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Solicitud."); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones, $ad_fecemisol, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepciones
		//		   Access: private
		//	    Arguments: as_numsol            // N�mero de Solicitud 
		//				   as_cedbene           // Cedula de Beneficiario
		//				   as_codpro            // C�digo Proveedor
		//				   ai_totrowrecepciones // Total de Filas de R.D.
		//				   ad_fecemisol         // Fecha de emision de la solicitud de pago
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta las Recepciones de Documento de una  Solicitud de Pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$ls_numrecdoc=$_POST["txtnumrecdoc".$li_i];
			$ls_codtipdoc=$_POST["txtcodtipdoc".$li_i];
			$li_monto=$_POST["txtmontotdoc".$li_i];
			$li_monto=str_replace(".","",$li_monto);
			$li_monto=str_replace(",",".",$li_monto);
			$lb_existe=$this->uf_select_recepcion($ls_numrecdoc,$as_codpro,$as_cedbene,$ls_codtipdoc);
			$lb_valido=$this->uf_comparar_fechas($ls_numrecdoc,$as_codpro,$as_cedbene,$ls_codtipdoc,$ad_fecemisol);
			if((!$lb_existe)&&($lb_valido))
			{
				$ls_sql="INSERT INTO cxp_dt_solicitudes (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, monto)".
						"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_numrecdoc."','".$ls_codtipdoc."',".
						" 			  '".trim($as_cedbene)."','".$as_codpro."',".$li_monto.")";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_insert_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert� la Recepcion ".$ls_numrecdoc." a la Solicitud de Pago ".$as_numsol.
									 " Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
					/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numsol);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dt_solicitudes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					*//////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"E",$aa_seguridad);	
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																		  $ad_fecemisol,"E",$aa_seguridad);	
					}
				}
			}
			else
			{
				if($lb_existe)
				{
					$this->io_mensajes->message("La Recepcion de documentos ".$ls_numrecdoc." ya esta tomada en otra Solicitud de Pago"); 
						return false;
				}
				else
				{
					$this->io_mensajes->message("La Recepcion de documentos ".$ls_numrecdoc." tiene una fecha posterior a la Solicitud de Pago"); 
					return true;
				}
				//return false;
			}
			
		}
		return $lb_valido;
	}// end function uf_insert_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_comparar_fechas($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc,$ad_fecemisol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_comparar_fechas
		//		   Access: public
		//		 Argument: as_numrecdoc // N�mero de Recepci�n de Documentos
		//		 		   as_codpro    // C�digo del Proveedor 
		//		 		   as_cedbene   // C�dula del Beneficiario
		//		 		   as_codtipdoc // C�digo del Tipo de Documento
		//		 		   ad_fecemisol // Fecha de emision de la solicitud de pago
		//	  Description: Funci�n que verifica si la fecha de la solicitud de pago con la de la recepcion de documento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 09/04/2008								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;
		$ls_sql="SELECT fecregdoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".trim($as_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_comparar_fechas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fegregdoc=$row["fecregdoc"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fegregdoc,$ad_fecemisol);
			}
		}
		if(!$lb_valido)
		{
			$this->io_mensajes->message("La fecha de la Recepcion de Documentos ".$as_numrecdoc." es mayor que la fecha de la solicitud");
			//$lb_valido=true;
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: public
		//		 Argument: as_numrecdoc // N�mero de Recepci�n de Documentos
		//		 		   as_codpro    // C�digo del Proveedor 
		//		 		   as_cedbene   // C�dula del Beneficiario
		//		 		   as_codtipdoc // C�digo del Tipo de Documento
		//	  Description: Funci�n que verifica si una recepci�n existe � no en otra solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 03/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes ".
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"	AND cxp_dt_solicitudes.numrecdoc='".$as_numrecdoc."' ".
				"	AND cxp_dt_solicitudes.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_dt_solicitudes.cod_pro='".$as_codpro."' ".
				"   AND cxp_dt_solicitudes.ced_bene='".trim($as_cedbene)."'".
				"   AND cxp_solicitudes.estprosol<>'A'".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_update_estatus_procedencia($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_procedencia
		//		   Access: private
		//	    Arguments: as_numrecdoc // N�mero de Recepcion de Documentos
		//                 as_codtipdoc // Codigo de Tipo de Documento
		//				   as_cedbene   // Cedula de Beneficiario
		//				   as_codpro    // C�digo Proveedor
		//				   ls_estatus   // Estatus en que se desea colocar la R.D.
		//                 aa_seguridad // Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que actualiza el estatus de la Recepcion de Documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 25/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="UPDATE cxp_rd ".
				"   SET estprodoc = '".$ls_estatus."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numrecdoc = '".$as_numrecdoc."' ".
				"	AND codtipdoc = '".$as_codtipdoc."' ".
				"	AND ced_bene = '".trim($as_cedbene)."' ".
				"	AND cod_pro = '".$as_codpro."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_update_estatus_procedencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� en estatus de la recepcion <b>".$as_numrecdoc.
				             "</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_estatus_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_recepciones($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,
											 $as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_recepciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  // N�mero de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // C�digo Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta las Recepciones de Documento de una  Solicitud de Pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 25/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_select_historicord($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,$as_estatus);
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."',".
					" 			  '".trim($as_cedbene)."','".$as_codpro."','".$ad_fecemisol."','".$as_estatus."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_insert_historico_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� un Movimiento en el Historico de la Recepcion ".$as_numrecdoc.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_historico_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_solicitud($as_numsol, $ad_fecemisol, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_solicitud
		//		   Access: private
		//	    Arguments: as_numsol    // N�mero de Solicitud 
		//                 ad_fecemisol //  Fecha de emision de la solicitud
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta un movimiento en el historico de la solicitud de orden de pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 26/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_historico_solicitud (codemp, numsol, fecha, estprodoc)".
				"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ad_fecemisol."','R')";        
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_insert_historico_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� un Movimiento en el Historico de la Solicitud de Pago ".$as_numsol.
							 " Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_historico_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
						$ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad,$as_permisosadministrador)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_sep_p_solicitud.php)
		//	    Arguments: as_existe    // Fecha de Solicitud
		//				   as_numsol    // N�mero de Solicitud 
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedbene   // Codigo de Beneficiario
		//				   as_codfuefin // C�digo de Fuente de financiamiento
		//				   as_tipproben // Tipo de Proveedor / Beneficiario
		//				   as_consol    // Concepto de la Solicitud
		//				   ad_fecemisol // Fecha de Emision de la Solicitud
		//				   ai_monsol    // Total de la solicitud
		//				   as_obssol    // Observacion de la Solicitud
		//				   as_estsol    // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Recepciones de Documento asociadas
		//				   aa_seguridad // arreglo de las variables de seguridad
		//				   as_permisosadministrador  // Indica si el usuario tiene permiso de administrador
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia /Ing. Juniors Fraga
		// Fecha Creaci�n: 26/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$lb_encontrado=$this->uf_select_solicitud($as_numsol);
		$ai_monsol=str_replace(".","",$ai_monsol);
		$ai_monsol=str_replace(",",".",$ai_monsol);
		$ad_fecemisol=$this->io_funciones->uf_convertirdatetobd($ad_fecemisol);
		switch ($as_existe)
		{
			case "FALSE":
				if($as_permisosadministrador!=1)
				{
					$lb_valido=$this->uf_validar_fecha_solicitud($ad_fecemisol);
					if(!$lb_valido)
					{
						$this->io_mensajes->message("La Fecha esta la Solicitud es menor a la fecha de la Solicitud anterior.");
					// AQUI CONTINUA CON EL PROCESO DE GUARDAR LA ORDEN //
						//return false;
					}
				}
				$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecemisol,$this->ls_codemp);
				if (!$lb_valido)
				{
					$this->io_mensajes->message($this->io_fecha->is_msg_error); 
					$lb_valido=true;          
					//return false;
				}                    
				$lb_valido=$this->uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
													  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
													  $ai_totrowrecepciones,$aa_seguridad);
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_solicitud($as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
														  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
														  $ai_totrowrecepciones,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Solicitud no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_solicitud($as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
								 $ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol     // N�mero de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codfuefin  // Codigo de Fuente de Financiamiento
		//				   as_tipproben  // Tipo Proveedor/Beneficiario 
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   as_codtipsol  // C�digo Tipo de solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   ai_monsol     // Monto de la Solicitud
		//				   as_obssol     // Observacion de la Solicitud
		//				   as_estsol     // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Filas de R.D.
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta la Solicitud de Pagos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 23/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE cxp_solicitudes ".
				"   SET cod_pro	= '".$as_codpro."', ".
				"		ced_bene = '".trim($as_cedbene)."', ".
				"		consol = '".$as_consol."', ".
				"		codfuefin = '".$as_codfuefin."', ".
				"		tipproben = '".$as_tipproben."', ".
				"		monsol = ".$ai_monsol.", ".
				"		obssol = '".$as_obssol."' ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND numsol = '".$as_numsol."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_update_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
/*			$this->io_rcbsf->io_ds_datos->insertRow("campo","monsolaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_monsol);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numsol);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_solicitudes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////  
			if($lb_valido)
			{
				$rs_data=$this->uf_load_recepciones($as_numsol);
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_numrecdoc=$row["numrecdoc"];
					$ls_codtipdoc=$row["codtipdoc"];
					$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"R",$aa_seguridad);	
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																		  $ad_fecemisol,"R",$aa_seguridad);	
					}
					if($lb_valido)
					{
						$lb_existe=$this->uf_select_ndnc($as_numsol,$ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro);
						if($lb_existe)
						{
							$this->io_mensajes->message("La Solicitud tiene Notas de Debito/Credito asociadas ");
							$lb_valido=false;
							break;
						}
					}
				}
				if($rs_data===false)
				{
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			}	
			if($lb_valido)
			{	
					$lb_valido=$this->uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones,
															$ad_fecemisol, $aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Solicitud fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Solicitud."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ndnc($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ndnc
		//		   Access: private
		//	    Arguments: as_numsol    //  N�mero de Solicitud
		//				   as_numrecdoc // N�mero de Recepcion de documentos
		//				   as_codtipdoc // C�digo de tipo de documento
		//				   as_cedbene   // C�dula de beneficiario
		//				   as_codpro    // C�digo de proveedor
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si existen Notas de Debito/Credito asociadas
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 28/04/2008 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numdc ".
				"  FROM cxp_sol_dc ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'".
				"   AND numrecdoc='".$as_numrecdoc."'".
				"   AND codtipdoc='".$as_codtipdoc."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_select_ndnc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  N�mero de Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si la Solicitud de pago Existe
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 26/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
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
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_recepciones($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_recepciones
		//		   Access: public
		//		 Argument: as_numsol // N�mero de solicitud
		//	  Description: Funci�n que busca las recepciones de documentos asociadas a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 29/04/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT cxp_dt_solicitudes.numrecdoc, cxp_dt_solicitudes.monto, cxp_dt_solicitudes.codtipdoc, cxp_documento.dentipdoc ".
				"  FROM cxp_dt_solicitudes,cxp_documento ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol= '".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_load_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_solicitud($as_numsol,$as_codpro,$as_cedbene,$ad_fecemisol,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_solicitud
		//		   Access: public
		//	    Arguments: as_numsol     // N�mero de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Codigo de Beneficiario
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   ai_totrow     // total de recepciones asociadas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Pagos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 30/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecemisol=$this->io_funciones->uf_convertirdatetobd($ad_fecemisol);
		$lb_valido=$this->uf_verificar_solicitudeliminar($as_numsol);
	// CON ESTA MODIFICACION SE ELIMINA LA OPCION DE CONTROLAR LA ELIMINACION DE ORDENES INTERMEDIAS //
		$lb_valido=true;
		if($lb_valido)
		{
			$this->io_sql->begin_transaction();	
			$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecemisol,$this->ls_codemp);
			if (!$lb_valido)
			{
				$this->io_mensajes->message($this->io_fecha->is_msg_error);           
				return false;
			}
			$rs_data=$this->uf_load_recepciones($as_numsol);
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_codtipdoc=$row["codtipdoc"];
				$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"R",$aa_seguridad);	
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																	  $ad_fecemisol,"R",$aa_seguridad);	
				}
				if($lb_valido)
				{
					$lb_existe=$this->uf_select_ndnc($as_numsol,$ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro);
					if($lb_existe)
					{
						$this->io_mensajes->message("La Solicitud tiene Notas de Debito/Credito asociadas ");
						$lb_valido=false;
						break;
					}
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			}
			if($lb_valido)
			{
				$ls_sql="DELETE FROM cxp_solicitudes ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"	AND numsol = '".$as_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_delete_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino la solicitud de pago ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{	
						$this->io_mensajes->message("La Solicitud fue Eliminada.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
						$this->io_sql->rollback();
					}
				}
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
				$this->io_sql->rollback();
			}
		}
		else
		{
			$this->io_mensajes->message("No se pueden eliminar solicitudes intermedias, si la desea dejar sin efecto debe anular la solicitud"); 
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalles($as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numsol  // N�mero de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una solicitud de pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 30/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_dt_solicitudes ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND numsol = '".$as_numsol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_historico_solicitud ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� todos los detalles de la solicitud de pago ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_delete_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_historicord($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,$as_estatus)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_historicord
		//		   Access: private
		//	    Arguments: as_numrecdoc  // N�mero de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // C�digo Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//	  Description: Funci�n que verifica si existe un registro en el historico de la recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 01/05/2007								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_historico_rd  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."'".
				"   AND codtipdoc='".$as_codtipdoc."'".
				"   AND ced_bene='".trim($as_cedbene)."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND fecha='".$ad_fecemisol."'".
				"   AND estprodoc='".$as_estatus."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_select_historicord ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
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
	}// end function uf_select_historicord
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_solicitudeliminar($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_solicitudeliminar
		//		   Access: private
		//	    Arguments: as_numsol  //  N�mero de Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el numero de solicitud de pago es la ultima que esta registrado
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 26/04/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY fecemisol DESC, numsol DESC LIMIT 1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud M�TODO->uf_verificar_solicitudeliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_solicitud=$row["numsol"];
				if($ls_solicitud==$as_numsol)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_verificar_solicitudeliminar
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>
