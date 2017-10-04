<?php
class tepuy_sme_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sme_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sme_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia /Ing. Juniors Fraga
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_cargos=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_sme_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------
/****************************************************************************************************************************************/	
    function uf_select_extordinal($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_extordinal)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Miguel Palencia.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //$ls_codemp = $this->dts_empresa["codemp"];
	 $ls_codemp = $_SESSION["la_empresa"]["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT extordinal ".
             " FROM   spg_ep3".
             " WHERE  codemp='".$ls_codemp."' AND codestpro1='".$as_codestpro1."' "." AND codestpro2='".$as_codestpro2."' "." AND codestpro3='".$as_codestpro3."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_extordinal ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_extordinal=$row["extordinal"];
		}
		$this->io_sql->free_result($rs_data);   
	 }//else
	return $as_extordinal;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_ayuda($as_numsoldes,$as_numsolhas,$as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fegregdes,
								   $ad_fegreghas,$as_codunides,$as_codunihas,$as_tipsol,$ai_registrada,$ai_emitida,
								   $ai_contabilizada,$ai_procesada,$ai_anulada,$ai_despachada,$as_orden,$as_parroquia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes
		//         Access: public (tepuy_sep_rpp_solicitud)  
		//	    Arguments: as_numsoldes     // Numero de solicitud de inicio del parametro de Busqueda
		//	  			   as_numsolhas     // Numero de solicitud de fin del parametro de Busqueda
		//	  			   as_tipproben     // Indica si es proveedor o beneficiario
		//	  			   as_codprobendes  // Código del proveedor/beneficiario de inicio del parametro de Busqueda
		//	  			   as_codprobenhas  // Código del proveedor/beneficiario de fin del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de inicio del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de fin del parametro de Busqueda
		//	  			   as_codunides     // Codigo de unidad ejecutora de inicio del parametro de Busqueda
		//	  			   as_codunihas     // Codigo de unidad ejecutora de fin del parametro de Busqueda
		//	  			   as_tipsol        // Indica el tipo de solicitud (Bienes, Servicios, Conceptos)
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_emitida       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_contabilizada // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_procesada     // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_anulada       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_despachada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   as_orden         // Orden a mostrar en el reporte
		//	  			   as_parroquia     // Filtra el reporte por parroquia		  		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes 
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 11/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_numsoldes))
		{
			//$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol>='".$as_numsoldes."'";
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.codtipsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			//$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol<='".$as_numsolhas."'";
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.codtipsol<='".$as_numsolhas."'";
		}
		if(!empty($as_tipproben))
		{
			//$ls_criterio= $ls_criterio."   AND sep_solicitud.tipo_destino='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fegregdes))
		{
			$ad_fegregdes=$this->io_funciones->uf_convertirdatetobd($ad_fegregdes);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol>='".$ad_fegregdes."'";
		}
		if(!empty($ad_fegreghas))
		{
			$ad_fegreghas=$this->io_funciones->uf_convertirdatetobd($ad_fegreghas);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol<='".$ad_fegreghas."'";
		}
		if(!empty($as_codunides))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm>='".$as_codunides."'";
		}
		if(!empty($as_codunihas))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm<='".$as_codunihas."'";
		}
		if(!empty($as_tipsol))
		{
			$ls_criterio=$ls_criterio. " AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
									   " AND sep_tiposolicitud.modsep='".$as_tipsol."'";
		}
		if(($ai_registrada==1)or($ai_emitida==1)or($ai_contabilizada==1)or($ai_procesada==1)or($ai_anulada==1)or($ai_despachada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='R'";
					$lb_anterior=true;
				}
			}
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='E'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='E'";
				}
			}
			if($ai_contabilizada==1)

			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='A'";
					$lb_anterior=true;
				}
			}
			if($ai_despachada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='D'";
					$lb_anterior=true;
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		// FILTRA POR PARROQUIA //
		if(!empty($as_parroquia))
		{
			$ls_criterio=$ls_criterio. "  AND rpc_beneficiario.codpar='".$as_parroquia."'";
		}
		$ls_criterio=$ls_criterio."  AND sep_solicitud.ayuda='1' ";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
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
		$ls_sql="SELECT numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, estsol,".
				"       consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro, sep_solicitud.ced_bene,".
				"       spg_unidadadministrativa.denuniadm AS denuniadm, sep_solicitud.fecregsol, sep_solicitud.estapro, ".
				"       MAX(spg_unidadadministrativa.codestpro1) AS codestpro1, MAX(spg_unidadadministrativa.codestpro2) AS codestpro2,  ".
				"       MAX(spg_unidadadministrativa.codestpro3) AS codestpro3, MAX(spg_unidadadministrativa.codestpro4) AS codestpro4,  ".
				"       MAX(spg_unidadadministrativa.codestpro5) AS codestpro5, ".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena."".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS nombre, rpc_beneficiario.codpar as codpar".
				"  FROM sep_solicitud,sep_tiposolicitud,spg_unidadadministrativa,rpc_beneficiario".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."'".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp".
				"   AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm".
				"   ".$ls_criterio." ".
				" GROUP BY sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, fecregsol,".
				"          estsol, consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro,".
				"          sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm,sep_solicitud.estapro".
				" ORDER BY ".$as_orden.""; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_solicitudes_ayuda
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes($as_numsoldes,$as_numsolhas,$as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fegregdes,
								   $ad_fegreghas,$as_codunides,$as_codunihas,$as_tipsol,$ai_registrada,$ai_emitida,
								   $ai_contabilizada,$ai_procesada,$ai_anulada,$ai_despachada,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes
		//         Access: public (tepuy_sep_rpp_solicitud)  
		//	    Arguments: as_numsoldes     // Numero de solicitud de inicio del parametro de Busqueda
		//	  			   as_numsolhas     // Numero de solicitud de fin del parametro de Busqueda
		//	  			   as_tipproben     // Indica si es proveedor o beneficiario
		//	  			   as_codprobendes  // Código del proveedor/beneficiario de inicio del parametro de Busqueda
		//	  			   as_codprobenhas  // Código del proveedor/beneficiario de fin del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de inicio del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de fin del parametro de Busqueda
		//	  			   as_codunides     // Codigo de unidad ejecutora de inicio del parametro de Busqueda
		//	  			   as_codunihas     // Codigo de unidad ejecutora de fin del parametro de Busqueda
		//	  			   as_tipsol        // Indica el tipo de solicitud (Bienes, Servicios, Conceptos)
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_emitida       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_contabilizada // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_procesada     // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_anulada       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_despachada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   as_orden         // Orden a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes 
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 11/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_numsoldes))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol<='".$as_numsolhas."'";
		}
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND sep_solicitud.tipo_destino='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fegregdes))
		{
			$ad_fegregdes=$this->io_funciones->uf_convertirdatetobd($ad_fegregdes);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol>='".$ad_fegregdes."'";
		}
		if(!empty($ad_fegreghas))
		{
			$ad_fegreghas=$this->io_funciones->uf_convertirdatetobd($ad_fegreghas);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol<='".$ad_fegreghas."'";
		}
		if(!empty($as_codunides))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm>='".$as_codunides."'";
		}
		if(!empty($as_codunihas))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm<='".$as_codunihas."'";
		}
		if(!empty($as_tipsol))
		{
			$ls_criterio=$ls_criterio. " AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
									   " AND sep_tiposolicitud.modsep='".$as_tipsol."'";
		}
		if(($ai_registrada==1)or($ai_emitida==1)or($ai_contabilizada==1)or($ai_procesada==1)or($ai_anulada==1)or($ai_despachada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='R'";
					$lb_anterior=true;
				}
			}
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='E'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='E'";
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='A'";
					$lb_anterior=true;
				}
			}
			if($ai_despachada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='D'";
					$lb_anterior=true;
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		//$ls_criterio=$ls_criterio."  AND (sep_solicitud.ayuda='1'";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
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
		$ls_sql="SELECT numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, estsol,".
				"       consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro, sep_solicitud.ced_bene,".
				"       spg_unidadadministrativa.denuniadm AS denuniadm, sep_solicitud.fecregsol, sep_solicitud.estapro, ".
				"       MAX(spg_unidadadministrativa.codestpro1) AS codestpro1, MAX(spg_unidadadministrativa.codestpro2) AS codestpro2,  ".
				"       MAX(spg_unidadadministrativa.codestpro3) AS codestpro3, MAX(spg_unidadadministrativa.codestpro4) AS codestpro4,  ".
				"       MAX(spg_unidadadministrativa.codestpro5) AS codestpro5, ".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena."".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS nombre".
				"  FROM sep_solicitud,sep_tiposolicitud,spg_unidadadministrativa".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."'".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm".
				"   ".$ls_criterio." ".
				" GROUP BY sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, fecregsol,".
				"          estsol, consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro,".
				"          sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm,sep_solicitud.estapro".
				" ORDER BY ".$as_orden.""; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	function uf_buscar_trabajador($as_ced_fam,$cual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_numero_servicios_previos($as_codprovben)
		//		   Access: public
		//	    Arguments: as_ced_fam  // cedula del familiar
		//	      Returns: la cedula del trabajador
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/03/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_nomper="";
		$this->io_sql->begin_transaction();
		if($cual=="T")
		{
			$ls_sql="SELECT CONCAT( TRIM(T1.nomper),' ',TRIM(T1.apeper) ) as nombre FROM sno_personal as T1".
				" WHERE T1.codemp = '".$this->ls_codemp."' ".
				" AND T1.cedper = '".$as_ced_fam."' ".
				" GROUP BY T1.cedper ORDER BY T1.cedper ASC";
		}
		else
			$ls_sql="SELECT CONCAT( TRIM(T1.nomper),' ',TRIM(T1.apeper) ) as nombre FROM sno_personal as T1, sno_familiar as T2 ".
				" WHERE T1.codemp = '".$this->ls_codemp."' ".
				" AND T2.cedfam = '".$as_ced_fam."' ".
				" AND T1.codper=T2.codper ".
				" GROUP BY T1.cedper ORDER BY T1.cedper ASC";

		//print $ls_sql;
		$rs_result=$this->io_sql->select($ls_sql);
		if($fila=$this->io_sql->fetch_row($rs_result))
			{
				$ld_nomper=$fila["nombre"];
			}
			$this->io_sql->free_result($rs_result);
		return $ld_nomper;
	}// end function uf_buscar_trabajador


	function uf_select_usuario($as_usuario)
        {
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //       Function: uf_select_solicitud
                //         Access: public (tepuy_sep_p_solicitud)  
                //          Arguments: as_numsol     // Numero de solicitud 
                //            Returns: lb_valido True si se creo el Data stored correctamente ï¿½ False si no se creo
                //    Description: funciï¿½n que busca la informaciï¿½n de la una solicitud de ejecucion presupuestaria 
                //         Creado Por: Ing. Miguel Palencia
                // Fecha Creaciï¿½n: 12/03/2007                                                                   Fecha ï¿½ltima Modificaciï¿½n :  
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $lb_valido=true;
		$ls_sql="SELECT nomusu, apeusu FROM sss_usuarios WHERE codemp='".$this->ls_codemp."' AND codusu='".$as_usuario."'";
               //$ls_sql="SELECT nomusu, apeusu FROM sss_usuarios WHERE codusu='admin'";

                $rs_data=$this->io_sql->select($ls_sql);
                if($rs_data===false)
                {
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_usuarios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
                        return false;
                }
                else
                {
                        if($row=$this->io_sql->fetch_row($rs_data))
                        {
                                $as_usuario=$row["nomusu"]." ".$row["apeusu"];
                                return $as_usuario;
                        }
                        else
                        {
                                return false;
                        }
                        $this->io_sql->free_result($rs_data);
                }
	} //uf_select_usuario

	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_parroquia($as_codpar)
        {
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //       Function: uf_select_parroquia
                //         Access: public (tepuy_sme_class_report)  
                //          Arguments: as_codpar     // Codigo de la Parroquia
                //            Returns: lb_valido True si se creo el Data stored correctamente ï¿½ False si no se creo
                //    Description: funciï¿½n que busca el nombre de la Parroquia 
                //         Creado Por: Ing. Miguel Palencia
                // Fecha Creaciï¿½n: 30/05/2015
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $lb_valido=true;
		$ls_sql="SELECT denpar FROM tepuy_parroquia WHERE codpai='058' AND codest='006' AND codmun='004' AND codpar='".$as_codpar."'";
                $rs_data=$this->io_sql->select($ls_sql);
                if($rs_data===false)
                {
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_parroquia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
                        return false;
                }
                else
                {
                        if($row=$this->io_sql->fetch_row($rs_data))
                        {
                                $as_nompar=$row["denpar"];
                                return $as_nompar;
                        }
                        else
                        {
                                return false;
                        }
                        $this->io_sql->free_result($rs_data);
                }
	} //uf_select_parroquia
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol,$aayuda)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de ejecucion presupuestaria 
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 12/03/2007									Fecha Última Modificación :  
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
		$ls_sql="SELECT sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm,".
				"       sep_solicitud.codfuefin, sep_solicitud.fecregsol,  sep_solicitud.consol, sep_solicitud.monto,".
				"       sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro,".
				"       sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm,sep_solicitud.estsol,sep_solicitud.estapro,sep_solicitud.codaprusu,sep_solicitud.ayuda,".
				//"       (SELECT dentipsol".
				//"          FROM sep_tiposolicitud".
				//"         WHERE sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol) AS dentipsol,".
				"       (CASE WHEN sep_solicitud.ayuda='1' THEN (SELECT dentipayuda".
				"          FROM sep_tipoayuda".
				"         WHERE sep_solicitud.codtipsol=sep_tipoayuda.codtipayuda)".
				"        WHEN sep_solicitud.ayuda='0' THEN (SELECT dentipsol".
				"          FROM sep_tiposolicitud".
				"         WHERE sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol) END) AS dentipsol,".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena." ".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS nombre,".
				"       (SELECT denfuefin".
				"          FROM tepuy_fuentefinanciamiento".
				"         WHERE tepuy_fuentefinanciamiento.codfuefin<>'--'".
				"		    AND tepuy_fuentefinanciamiento.codemp=sep_solicitud.codemp".
				"			AND tepuy_fuentefinanciamiento.codfuefin=sep_solicitud.codfuefin) AS denfuefin, ".
				"  sme_dt_beneficiario.cedper as cedtra, sme_dt_beneficiario.cedfam as cedfam, sme_tiposervicio.dentipservicio".
				"  FROM sme_montoseguntipo, sme_dt_beneficiario, sep_solicitud, spg_unidadadministrativa, sme_tiposervicio ".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."' ".
				"   AND sep_solicitud.numsol='".$as_numsol."'".
				"   AND sep_solicitud.numsol=sme_dt_beneficiario.numsol ".// CAMBIO PARA FILTRAR  Servicios Medicos
				"   AND sep_solicitud.codtar=sme_montoseguntipo.codtar ". // FILTRA COD. NOMINA codtipservicio
				"   AND sme_tiposervicio.codtipservicio=sme_montoseguntipo.codtipservicio ". // FILTRA codtipservicio
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm";
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	function uf_select_dt_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_solicitud
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsoldes     // Numero de solicitud de inicio del parametro de Busqueda
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
		case "MYSQL":
		 $ls_cadena="CONVERT('' USING utf8) AS unidad";
		 break;
		case "POSTGRE":
		 $ls_cadena="CAST('' AS char(15)) as cuenta";
		 break;     
	   }
   		$ls_sql="SELECT codart AS codigo, canart AS cantidad, unidad,monpre, monart AS monto, 'B' AS tipo,".
				"       (SELECT denart".
				"          FROM siv_articulo".
				"         WHERE siv_articulo.codemp=sep_dt_articulos.codemp".
				"           AND siv_articulo.codart=sep_dt_articulos.codart) AS denominacion".
				"  FROM sep_dt_articulos".
				" WHERE sep_dt_articulos.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_articulos.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT codser AS codigo, canser AS cantidad, ".$ls_cadena.", monpre, monser AS monto, 'S' AS tipo,".
				"       (SELECT denser".
				"          FROM soc_servicios".
				"         WHERE soc_servicios.codemp=sep_dt_servicio.codemp".
				"           AND soc_servicios.codser=sep_dt_servicio.codser) AS denominacion".
				"  FROM sep_dt_servicio".
				" WHERE sep_dt_servicio.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_servicio.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT codconsep AS codigo, cancon AS cantidad, ".$ls_cadena.", monpre, moncon AS monto, 'C' AS tipo,".
				"       (SELECT denconsep".
				"          FROM sep_conceptos".
				"         WHERE sep_conceptos.codconsep=sep_dt_concepto.codconsep) AS denominacion".
				"  FROM sep_dt_concepto".
				" WHERE sep_dt_concepto.codemp='".$this->ls_codemp."'".
				"  AND sep_dt_concepto.numsol='".$as_numsol."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_cargos($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_cargos
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle de los cargos (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT MAX(codart) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM tepuy_cargos ".
				"		 WHERE tepuy_cargos.codemp=sep_dta_cargos.codemp ".
				"		   AND tepuy_cargos.codcar=sep_dta_cargos.codcar".
				"        GROUP BY tepuy_cargos.codcar) AS dencar".
				"  FROM sep_dta_cargos".
				" WHERE sep_dta_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dta_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dta_cargos.codemp, sep_dta_cargos.numsol,sep_dta_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codser) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM tepuy_cargos ".
				"		 WHERE tepuy_cargos.codemp=sep_dts_cargos.codemp ".
				"		   AND tepuy_cargos.codcar=sep_dts_cargos.codcar".
				"        GROUP BY tepuy_cargos.codcar) AS dencar".
				"  FROM sep_dts_cargos".
				" WHERE sep_dts_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dts_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dts_cargos.codemp, sep_dts_cargos.numsol,sep_dts_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codconsep) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM tepuy_cargos ".
				"		 WHERE tepuy_cargos.codemp=sep_dtc_cargos.codemp ".
				"		   AND tepuy_cargos.codcar=sep_dtc_cargos.codcar".
				"        GROUP BY tepuy_cargos.codcar) AS dencar".
				"  FROM sep_dtc_cargos".
				" WHERE sep_dtc_cargos.codemp='".$this->ls_codemp."'".
				"  AND sep_dtc_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dtc_cargos.codemp, sep_dtc_cargos.numsol,sep_dtc_cargos.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_cargos->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_spgcuentas($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_spgcuentas
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle de los cargos (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, monto".
		//		"  FROM sep_cuentagasto".
		//		" WHERE sep_cuentagasto.codemp='".$this->ls_codemp."'".
		//		"  AND  sep_cuentagasto.numsol='".$as_numsol."'";

		$ls_sql="SELECT sep_cuentagasto.codestpro1, sep_cuentagasto.codestpro2, sep_cuentagasto.codestpro3, ".
			  "sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, sep_cuentagasto.spg_cuenta, sep_cuentagasto.monto,".
			" max(spg_cuentas.denominacion) as denominacion FROM sep_cuentagasto, spg_cuentas".
			// el max es para que traiga solo la partida de la categoria programatica  //
				" WHERE sep_cuentagasto.codemp='".$this->ls_codemp."'".
				"  AND  sep_cuentagasto.numsol='".$as_numsol."'".
				"  AND  sep_cuentagasto.spg_cuenta=spg_cuentas.spg_cuenta".
				" GROUP BY sep_cuentagasto.numsol,spg_cuentas.spg_cuenta";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_cuentas->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_unidad($as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_unidad
		//         Access: public (tepuy_sep_p_solicitud)  
		//	    Arguments: as_codart     // codigo de articulo
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene las unidades de medida de un articulo
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT siv_unidadmedida.unidad".
				"  FROM siv_unidadmedida,siv_articulo".
				" WHERE siv_articulo.codemp='".$this->ls_codemp."'".
				"   AND siv_articulo.codart='".$as_codart."'".
				"   AND siv_articulo.codunimed=siv_unidadmedida.codunimed";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_unidad=$row["unidad"];
				return $li_unidad;
			}
			else
			{
				return false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_dt_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------
    
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_unidad_medida($as_codart,&$as_codunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_unidad_medida
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codart // codigo del articulo
		//                 as_codunimed // codigo unidad de medida (referencia)
		//    Description: Function que devuelve el codigo de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT codunimed ".
				  " FROM   siv_articulo ".
				  " WHERE  codemp='".$this->ls_codemp."' AND codart='".$as_codart."' "; 
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_codunimed=$row["codunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_unidad_medida
    //---------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_denominacion_unidad_medida($as_codart,$as_codunimed,&$as_denunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codart // codigo del articulo
		//                 as_codunimed // codigo unidad de medida (referencia)
		//                 as_denunimed // denominacion de la  unidad de medida (referencia)
		//    Description: Function que devuelve la denominacion de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT * ".
                  " FROM  siv_unidadmedida , siv_articulo ".
                  " WHERE siv_articulo.codemp='".$this->ls_codemp."' AND siv_unidadmedida.codunimed='".$as_codunimed."' AND ".
                  "       siv_articulo.codart='".$as_codart."' ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_denominacion_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denunimed=$row["denunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_denominacion_unidad_medida
   //---------------------------------------------------------------------------------------------------------------------------------	
	
   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_denominacionspg($as_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE  codemp='".$this->ls_codemp."'  AND  spg_cuenta='".$as_cuenta."' ";       
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_denominacionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["denominacion"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
   //---------------------------------------------------------------------------------------------------------------------------------	

   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_disponible($as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                              &$ad_monto_disponible)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_disponible
		//		   Access: private 
		//	    Arguments: $as_spgcuenta   // cuenta 
		//                 $as_codestpro1  //  codestpro1
		//                 $as_codestpro2  //  codestpro2
		//                 $as_codestpro3  //  codestpro3
		//                 $as_codestpro4  //  codestpro4
		//                 $as_codestpro5  //  codestpro5
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve el monto disponible de una cuenta especifica  
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		$ls_sql =" SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
	             " FROM   spg_cuentas ".
		   	   	 " WHERE  codemp = '".$this->ls_codemp."'     AND spg_cuenta = '".$as_spgcuenta."'      AND ".
				 "        codestpro1 = '".$as_codestpro1."'   AND codestpro2 = '".$as_codestpro2."'     AND ".
				 "        codestpro3 = '".$as_codestpro3."'   AND codestpro4 = '".$as_codestpro4."'     AND ".
				 "        codestpro5 = '".$as_codestpro5."'                                                 ".
				 " ORDER BY spg_cuenta ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_disponible ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ad_monto_disponible=$row["disponible"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_disponible
   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_unidad_medida_servicios($as_codser,&$as_codunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_unidad_medida_servicios
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codser // codigo del servicio
		//                 as_codunimed // codigo unidad de medida (referencia)
		//    Description: Function que devuelve el codigo de la unidad de medida de un servicio que tiene asociada el articulo
		//	   Creado Por: Ing.Gloriely Fréitez.
		// Fecha Creación: 22/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT codunimed ".
				  " FROM   soc_servicios".
				  " WHERE  soc_servicios.codemp='".$this->ls_codemp."' AND soc_servicios.codser='".$as_codser."' "; 
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_codunimed=$row["codunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_unidad_medida
	
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_denomin_unidad_medida_servicios($as_codart,$as_codunimed,&$as_denunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denomin_unidad_medida_servicios
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codart // codigo del articulo
		//                 as_codunimed // codigo unidad de medida (referencia)
		//                 as_denunimed // denominacion de la  unidad de medida (referencia)
		//    Description: Function que devuelve la denominacion de la unidad de medida de un servicio que tiene asociada el articulo
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 22/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT denunimed".
                  " FROM  siv_unidadmedida , soc_servicios ".
                  " WHERE soc_servicios.codemp='".$this->ls_codemp."' AND siv_unidadmedida.codunimed='".$as_codunimed."' AND ".
                  "       soc_servicios.codser='".$as_codart."' AND soc_servicios.codunimed=siv_unidadmedida.codunimed "; 
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_denominacion_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denunimed=$row["denunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_denomin_unidad_medida_servicios
    //---------------------------------------------------------------------------------------------------------------------------------	
	
}
?>
