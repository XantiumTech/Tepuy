<?php
class tepuy_cxp_c_aprobacionsolicitudpago
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_cxp_c_aprobacionsolicitudpago($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_cxp_c_aprobacionsolicitudpago
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_sep_c_aprobacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
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
	function uf_load_solicitudes($as_numsol,$ad_fecemides,$ad_fecemihas,$as_tipproben,$as_proben,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_solicitudes
		//		   Access: public
		//		 Argument: as_numsol        // Numero de la solicitud de ejecucion presupuestaria
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipproben     // tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Función que busca las solicitudes de ordenes de pago a aprobar o reversar aprobacion
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(nombene,' ',apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="nombene||' '||apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,".
				"       cxp_solicitudes.estaprosol,cxp_solicitudes.fecemisol,".
				"       (CASE WHEN cxp_solicitudes.tipproben='B' THEN (SELECT ".$ls_cadena." ".
				"                                                        FROM rpc_beneficiario".
				"                                                       WHERE cxp_solicitudes.codemp=rpc_beneficiario.codemp".
				"                                                         AND cxp_solicitudes.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN cxp_solicitudes.tipproben='P' THEN (SELECT nompro".
				"                                                        FROM rpc_proveedor".
				"                                                       WHERE cxp_solicitudes.codemp=rpc_proveedor.codemp".
				"                                                         AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro)".
				"                                                ELSE 'NINGUNO'".
				"         END) AS nombre".
				"  FROM cxp_solicitudes".
				" WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."'".
				"   AND cxp_solicitudes.numsol LIKE '".$as_numsol."' ".
				"   AND cxp_solicitudes.fecemisol >= '".$ad_fecemides."' ".
				"   AND cxp_solicitudes.fecemisol <= '".$ad_fecemihas."' ".
				"   AND cxp_solicitudes.estprosol='E'".
				"   AND cxp_solicitudes.estaprosol='".$as_tipooperacion."'";
		if($as_tipproben=="B")
		{
			$ls_sql= $ls_sql." AND cxp_solicitudes.ced_bene LIKE '".$as_proben."'";
		}
		else
		{
			$ls_sql= $ls_sql." AND cxp_solicitudes.cod_pro LIKE'".$as_proben."'";
		}
		$ls_sql= $ls_sql." ORDER BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_load_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_solicitud($as_numsol,$as_estsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				"   AND estaprosol=".$as_estsol."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_validar_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_solicitud($as_numsol,$as_estsol,$ad_fecaprosol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol    //  Número de Solicitud
		//                 as_estsol    //  Estatus en que se desea colocar la solicitud
		//                 ad_fecaprosol //  Fecha de aprobacion de la solicitud
		//                 aa_seguridad //  Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecaprosol,$this->ls_codemp);
		if (!$lb_valido)
		{
			$this->io_mensajes->message($this->io_fecha->is_msg_error);           
			return false;
		}
		$ls_usuario=$_SESSION["la_logusr"];
		if($as_estsol==0)
		{
			$ad_fecaprsep="1900-01-01";
			$ls_usuario="";
		}
		$ad_fecaprosol=$this->io_funciones->uf_convertirdatetobd($ad_fecaprosol);
		$ls_sql="UPDATE cxp_solicitudes ".
				"   SET estaprosol = ".$as_estsol.", ".
				"       fecaprosol = '".$ad_fecaprosol."', ".
				"		usuaprosol = '".$ls_usuario."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numsol = '".$as_numsol."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_update_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estsol==1)
			{
				$ls_descripcion ="Aprobó la Solicitud de Pago <b>".$as_numsol."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Aprobacion de la Solicitud de Pago <b>".$as_numsol."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_solicitudes($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_solicitudes
		//		   Access: public
		//		 Argument: as_numsol        // Numero de la solicitud de orden de pago
		//	  Description: Función que verifica que una solicitud este en estatus de registro
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol".
				"  FROM cxp_solicitudes".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND numsol = '".$as_numsol."'".
				"   AND estprosol = 'E' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
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
	}// end function uf_validar_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>