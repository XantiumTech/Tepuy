<?php
class tepuy_sme_c_solicitud
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sme_c_solicitud($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sme_c_solicitud
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
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
		require_once($as_path."shared/class_folder/class_generar_id_process.php");
		$this->io_id_process=new class_generar_id_process();		
		require_once($as_path."shared/class_folder/class_datastore.php");
		$this->ds_detalle=new class_datastore();
		$this->io_dscuentas=new class_datastore();
		$this->io_dscargos=new class_datastore();
		$this->DS=new class_datastore();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_sme_c_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sme_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
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
	function uf_load_tiposolicitudservicio($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tiposolicitudayuda
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//$ls_sql="SELECT codtipsol, dentipsol, estope, modsep ".
		//		"  FROM sep_tiposolicitud WHERE codtipsol='05' ".
		//		" ORDER BY modsep, estope  ASC ";
		$ls_sql="SELECT T1.codtipsol, T1.dentipsol, T1.estope, T1.modsep,T2.codtar,T2.codnom ".
				"  FROM sep_tiposolicitud as T1, sme_montoseguntipo as T2 WHERE T1.codtipsol=T2.codtipsol".
				//" ORDER BY T1.codtipsol  ASC LIMIT 42,10";	
				" GROUP BY T1.codtipsol";	
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_tiposolicitudservicio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{print "<select name='cmbcodtipsol' id='cmbcodtipsol' onChange='javascript: ue_cargargrid();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipsol=$row["codtipsol"];
				$ls_codtar=$row["codtar"];
				$ls_codnom=$row["codnom"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_operacion="";
				switch($ls_estope)
				{
					case"R":// Precompromiso
						$ls_operacion="Precompromiso";
						break;
					case"O":// Compromiso
						$ls_operacion="Compromiso";
						break;
					case"S":// Sin Afectacion
						$ls_operacion="Sin Afectacion Presupuestaria";
						break;
				}
				if($as_seleccionado==$ls_codtipsol."-".$ls_modsep."-".$ls_estope."-".$ls_codtar."-".$ls_codnom)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtipsol."-".$ls_modsep."-".$ls_estope."-".$ls_codtar."-".$ls_codnom."' ".$ls_seleccionado.">".$ls_dentipsol." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tiposolicitudservicio

	function uf_datacombo($ls_sql,&$aa_data)
	{
	/***************************************************************************************/
	/*	Function:	    uf_datacombo                                                       */    
	/*  Fecha:          18/05/2006                                                         */        
	/*	Autor:          Juniors Fraga		                                               */     
	/***************************************************************************************/
		$lb_valido=false;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_numero_servicios_previos($as_codprovben,$as_codtar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_numero_servicios_previos($as_codprovben)
		//		   Access: public
		//	    Arguments: as_codprovben  // cedula del beneficiario 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que busca las solicitudes de Ejecución Presupuestaria
		//			que tengan las personas en fecha previos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/05/2015 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		//print "Beneficiario: ".$as_codprovben;
		//print "Codtar: ".$as_codtar;
		$ls_sql="SELECT sep_solicitud.numsol,sep_solicitud.consol,sep_solicitud.ced_bene,sep_solicitud.monto,sep_solicitud.fecregsol, ".
			" rpc_beneficiario.nombene,rpc_beneficiario.apebene ".
			"  FROM sep_solicitud, rpc_beneficiario WHERE sep_solicitud.codemp = '".$this->ls_codemp."' ".
			"	AND sep_solicitud.codtar = '".$as_codtar."' ".
			" AND sep_solicitud.ced_bene='".$as_codprovben."' AND rpc_beneficiario.ced_bene='".$as_codprovben.
			"' GROUP BY sep_solicitud.numsol ORDER BY sep_solicitud.numsol DESC";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		$totalsolicitudprevia = $this->io_sql->num_rows($rs_data);
		$this->io_sql->free_result($rs_data);
		return $totalsolicitudprevia;
	}// end function uf_numero_servicios_previos

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_trabajador($as_ced_fam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_numero_servicios_previos($as_codprovben)
		//		   Access: public
		//	    Arguments: as_ced_fam  // cedula del familiar
		//	      Returns: la cedula del trabajador
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/03/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_cedper="";
		$this->io_sql->begin_transaction();
		$ls_sql="SELECT T1.cedper FROM sno_personal as T1, sno_familiar as T2 ".
			"WHERE T1.codemp = '".$this->ls_codemp."' ".
			"	AND T2.cedfam = '".$as_ced_fam."' ".
			"   AND T1.codper=T2.codper ".
			" GROUP BY T1.cedper ORDER BY T1.codper ASC";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_cedper=$row["cedper"];
			}
			$this->io_sql->free_result($rs_data);
		return $ld_cedper;
	}// end function uf_buscar_trabajador



	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monto_tope_servicios_medicos($as_tipo_trab_fam,$as_codtar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_monto_tope_servicios_medicos($as_codprovben,$as_codtar)
		//		   Access: public
		//	    Arguments: as_codprovben  // cedula del beneficiario 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que busca las solicitudes de Ejecución Presupuestaria
		//			que tengan las personas en fecha previos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/05/2015 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$monto=0;
		$this->io_sql->begin_transaction();
		if($as_tipo_trab_fam=="T")
		{
			$ls_sql="SELECT sme_montoseguntipo.montotrabajador as monto FROM sme_montoseguntipo WHERE sme_montoseguntipo.codtar='".$as_codtar."' ";
		}
		else
		{
			$ls_sql="SELECT  sme_montoseguntipo.montofamiliar as monto FROM sme_montoseguntipo WHERE sme_montoseguntipo.codtar='".$as_codtar."' ";
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($rs_data))
			{
				$monto=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		return $monto;
	}// end function uf_monto_tope_servicios_medicos

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monto_consumo_servicios_medicos($as_tipo_trab_fam,$as_codtar,$as_codnom,$as_beneficiario)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_monto_tope_servicios_medicos($as_codprovben,$as_codtar)
		//		   Access: public
		//	    Arguments: as_codprovben  // cedula del beneficiario 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que busca las solicitudes de Ejecución Presupuestaria
		//			que tengan las personas en fecha previos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/05/2015 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$monto=0;
		$this->io_sql->begin_transaction();
		//print "Beneficiario: ".$as_codprovben;
		//print "Codtar: ".$as_codtar;
		if($as_tipo_trab_fam=="T")
		{
		$ls_sql="SELECT SUM(monto) total FROM sme_dt_beneficiario WHERE codemp='".$this->ls_codemp."' ".
				" AND codtar='".$as_codtar."' AND codnom='".$as_codnom."' "." AND cedper='".$as_beneficiario."' ".
				" GROUP BY codtar";
		}
		else
		{

		$ls_sql="SELECT SUM(monto) total FROM sme_dt_beneficiario WHERE codemp='".$this->ls_codemp."' ".
				" AND codtar='".$as_codtar."' AND codnom='".$as_codnom."' "." AND cedfam='".$as_beneficiario."' ".
				" GROUP BY codtar";
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($rs_data))
			{
				$monto=$row["total"];
			}
			$this->io_sql->free_result($rs_data);
		return $monto;
	}// end function uf_monto_tope_servicios_medicos

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_servicios_previos($as_codprovben,&$totalayudaprevia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_servicios_previos($as_codprovben)
		//		   Access: public
		//	    Arguments: as_codprovben  // cedula del beneficiario 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que busca las solicitudes de Ejecución Presupuestaria
		//			que tengan las personas en fecha previos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/05/2015 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_ayuda='1';
		$this->io_sql->begin_transaction();
		//print "Beneficiario: ".$as_codprovben);
		$ls_sql="SELECT sep_solicitud.numsol,sep_solicitud.consol,sep_solicitud.ced_bene,sep_solicitud.monto,sep_solicitud.fecregsol, ".
			" rpc_beneficiario.nombene,rpc_beneficiario.apebene ".
			"  FROM sep_solicitud, rpc_beneficiario WHERE sep_solicitud.codemp = '".$this->ls_codemp."' ".
			"	AND sep_solicitud.ayuda = '".$as_ayuda."' ".
			" AND sep_solicitud.ced_bene='".$as_codprovben."' AND rpc_beneficiario.ced_bene='".$as_codprovben.
			"' GROUP BY sep_solicitud.numsol ORDER BY sep_solicitud.numsol DESC";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		$totalayudaprevia = $this->io_sql->num_rows($rs_data);
		if($totalayudaprevia<=0)
		{
			//$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_buscar_servicios_previos->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=true;
		}
		else
		{

			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			$this->io_sql->free_result($rs_data);
			$lb_valido=false;
			//print "aqui";
			//if($row=$this->io_sql->fetch_row($rs_data))

			/*while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				$ls_monto=$row["monto"];
				//$ls_numsol=$this->io_dscuentas->getValue('numsol',$li_fila);
				//$ls_monto=$this->io_dscuentas->getValue('monto',$li_fila);
				//$ls_monto=str_replace(".","",$ls_monto);
				$ls_monto=number_format($ls_monto,2,",",".");
				$ls_fecregsol=$row["fecregsol"];
				$ls_fecha=substr($ls_fecregsol,8,2)."-".substr($ls_fecregsol,5,2)."-".substr($ls_fecregsol,0,4);
				$nombene=$row["nombene"];
				$apebene=$row["apebene"];
				$ls_beneficiario=trim($nombene)." ".trim($apebene);
				$Cargadatos[$i] = $verConfig["conf_nombre"];
				$mensaje="El Beneficiario(a) ".$ls_beneficiario." presenta una solicitud de ayuda en fecha ".$ls_fecha." por un monto de Bs. ".$ls_monto." ¿Procede a la asignación?";
				//print "Mensaje: ".$mensaje;

				$this->io_mensajes->confirm($mensaje,$lb_valido);
			// CAPTURO EL VALOR DE LA VARIABLE DE LA FUNCION JAVASCRIPT //
				$valido="<script>document.write(respuesta)</script>";
			// ESTO PERMITE SIMULAR LA CAPTURA PERO EN REALIDAD NO OCURRE NADA PORQUE //
			// RECORDEMOS QUE PHP CORRE DEL LADO DEL SERVIDOR Y JAVA DEL CLIENTE //
				//$valor="<script>document.write(valor)</script>";
				
				if($valor==0) // Consiguen una ayuda previa y decide no proceder a registrar esta
				{
					//print "mensaje: ".$valido;
					$this->io_sql->free_result($rs_data);
					return false;
				}
			} //end while
			$this->io_sql->free_result($rs_data);	*/
		}
		return $lb_valido;
	}// end function uf_buscar_servicios_previos


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_buscarcuentacargo($as_codcar)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_buscarcuentacargo
		//		   Access: public
		//		 Argument: as_codcar // Código del cargo (IVA)
		//	  Description: Función que busca las categorias programaticas de los cargos asociados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 20/06/2015
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codestpro FROM tepuy_cargos ".
			" WHERE codemp = '".$this->ls_codemp."' "." AND codcar = '".$as_codcar."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_buscarcuentacargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_codestpro=$row["codestpro"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ld_codestpro;
	}// end function uf_load_buscarcuentacargos

//-------------------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tiposolicitud($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tiposolicitud
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipsol, dentipsol, estope, modsep ".
				"  FROM sep_tiposolicitud ".
				//" ORDER BY modsep, estope  ASC ";
				" ORDER BY codtipsol ASC ";
		//print $ls_sql;	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_tiposolicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipsol' id='cmbcodtipsol' onChange='javascript: ue_cargargrid();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipsol=$row["codtipsol"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_operacion="";
				switch($ls_estope)
				{
					case"R":// Precompromiso
						$ls_operacion="Precompromiso";
						break;
					case"O":// Compromiso
						$ls_operacion="Compromiso";
						break;
					case"S":// Sin Afectacion
						$ls_operacion="Sin Afectacion Presupuestaria";
						break;
				}
				if($as_seleccionado==$ls_codtipsol."-".$ls_modsep."-".$ls_estope)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtipsol."-".$ls_modsep."-".$ls_estope."' ".$ls_seleccionado.">".$ls_dentipsol." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tiposolicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha_sep($ad_fecregsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_sep
		//		   Access: private
		//		 Argument: $ad_fecregsol // fecha de registro dee solicitud de la nueva sep
		//	  Description: Función que busca la fecha de la última sep y la compara con la fecha actual
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol,fecregsol ".
				"  FROM sep_solicitud  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numsol DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_fecha_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$row["fecregsol"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecregsol); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosbienes($as_codart,$as_codprounidad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosbienes
		//		   Access: public
		//		 Argument: as_codart // Código del artículo que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un artículo
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
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
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosservicios($as_codser,$as_codprounidad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosservicios
		//		   Access: public
		//		 Argument: as_codser // Código del artículo que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un servicio
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
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
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosservicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosservicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosconceptos($as_codcon,$as_codprounidad,$ad_codivasel)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosconceptos
		//		   Access: public
		//		 Argument: as_codcon // Código del concepto que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un Concepto
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1=substr($as_codprounidad,0,20);
		$ls_codestpro2=substr($as_codprounidad,20,6);
		$ls_codestpro3=substr($as_codprounidad,26,3);
		$ls_codestpro4=substr($as_codprounidad,29,2);
		$ls_codestpro5=substr($as_codprounidad,31,2);
		if($ad_codivasel=="")
		{
		$ls_sql="SELECT sep_conceptocargos.codconsep AS codigo, tepuy_cargos.codcar, tepuy_cargos.dencar, ".
				"		TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, tepuy_cargos.formula, tepuy_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM tepuy_cargos, sep_conceptocargos ".
                " WHERE sep_conceptocargos.codemp = '".$this->ls_codemp."' ".
				"   AND sep_conceptocargos.codconsep = '".$as_codcon."' ".
				"	AND tepuy_cargos.codemp = sep_conceptocargos.codemp ".
				"   AND tepuy_cargos.codcar = sep_conceptocargos.codcar ";
		}
		else
		{
		$ls_sql="SELECT tepuy_cargos.codcar, tepuy_cargos.dencar, TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, ".
						"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta, ".
			"tepuy_cargos.formula, tepuy_cargos.codestpro FROM tepuy_cargos WHERE tepuy_cargos.codcar = '".$ad_codivasel."'";
		/*$ls_sql="SELECT sep_conceptocargos.codconsep AS codigo, tepuy_cargos.codcar, tepuy_cargos.dencar, ".
				"		TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta, tepuy_cargos.formula, tepuy_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND tepuy_cargos.codemp = spg_cuentas.codemp ".
				"			AND tepuy_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM tepuy_cargos, sep_conceptocargos ".
                " WHERE sep_conceptocargos.codemp = '".$this->ls_codemp."' ".
	//			"   AND sep_conceptocargos.codconsep = '".$as_codcon."' ".
	//			"	AND tepuy_cargos.codemp = sep_conceptocargos.codemp ".
				"   AND tepuy_cargos.codcar = '".$ad_codivasel."'";*/
		}	
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosconceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosconceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Solicitu de ejecución Presupuestaria Existe
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM sep_solicitud ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro2  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro3  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro4  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro5  //  Còdigo de Estructura Programàtica
		//	    		   as_spgcuenta  //  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
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
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_load_bienes($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_articulos.codart, sep_dt_articulos.canart, sep_dt_articulos.unidad, sep_dt_articulos.monpre, ".
				"		sep_dt_articulos.monart, sep_dt_articulos.orden, TRIM(sep_dt_articulos.spg_cuenta) AS spg_cuenta, siv_articulo.denart, ".
				"		siv_unidadmedida.unidad AS unimed, siv_unidadmedida.denunimed ".				
                "  FROM sep_dt_articulos, siv_articulo, siv_unidadmedida ".
                " WHERE sep_dt_articulos.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_articulos.numsol = '".$as_numsol."' ".
				"   AND sep_dt_articulos.codemp = siv_articulo.codemp ".
				"   AND sep_dt_articulos.codart = siv_articulo.codart ".
				"	AND siv_articulo.codunimed = siv_unidadmedida.codunimed ".
				" ORDER BY sep_dt_articulos.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los servicios asociados a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_servicio.codser, sep_dt_servicio.canser, sep_dt_servicio.monpre, ".
				"		sep_dt_servicio.monser, sep_dt_servicio.orden, TRIM(sep_dt_servicio.spg_cuenta) AS spg_cuenta, soc_servicios.denser, ".
				"       siv_unidadmedida.denunimed   ".
                "  FROM sep_dt_servicio, soc_servicios ".
				"left join siv_unidadmedida on (soc_servicios.codunimed=siv_unidadmedida.codunimed)".
                " WHERE sep_dt_servicio.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_servicio.numsol = '".$as_numsol."' ".
				"   AND sep_dt_servicio.codemp = soc_servicios.codemp ".
				"   AND sep_dt_servicio.codser = soc_servicios.codser ".
				" ORDER BY sep_dt_servicio.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los conceptos asociados a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_concepto.codconsep, sep_dt_concepto.cancon, sep_dt_concepto.monpre, ".
				"		sep_dt_concepto.moncon, sep_dt_concepto.orden, TRIM(sep_dt_concepto.spg_cuenta) AS spg_cuenta, sep_conceptos.denconsep ".
                "  FROM sep_dt_concepto, sep_conceptos ".
                " WHERE sep_dt_concepto.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_concepto.numsol = '".$as_numsol."' ".
				"   AND sep_dt_concepto.codconsep = sep_conceptos.codconsep ".
				" ORDER BY sep_dt_concepto.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numsol, $as_tabla, $as_campo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//		 		   as_tabla // Tabla en la cual se va a buscar
		//		 		   as_campo // campo por el cual se va a buscar
		//	  Description: Función que busca los cargos asociados a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT ".$as_tabla.".".$as_campo." AS codigo, ".$as_tabla.".codcar, ".$as_tabla.".monbasimp, ".$as_tabla.".monimp, ".
				"       ".$as_tabla.".monto, ".$as_tabla.".formula, tepuy_cargos.dencar, TRIM(tepuy_cargos.spg_cuenta) AS spg_cuenta ".
				"  FROM ".$as_tabla.", tepuy_cargos ".
				" WHERE ".$as_tabla.".codemp = '".$this->ls_codemp."' ".
				"   AND ".$as_tabla.".numsol = '".$as_numsol."' ".
				"   AND ".$as_tabla.".codemp = tepuy_cargos.codemp ".
				"   AND ".$as_tabla.".codcar = tepuy_cargos.codcar ";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca las cuentas asociadas a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
				"		TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
				"		monto AS total ".
				"  FROM sep_cuentagasto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				" UNION ".
				"SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
				"		TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
				"		-monto AS total ".
				"  FROM sep_solicitudcargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_load_cuentas_cargo($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca las cuentas asociadas a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codcar,codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, TRIM(spg_cuenta) AS spg_cuenta, monto AS total ".
				"  FROM sep_solicitudcargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cuentas_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,
							     $as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								 $as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
								 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$aa_seguridad,$as_ayuda,$as_codpro,$as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprov  // Código de Proveedor 
		//				   as_cedben  // Código de Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas del Cargo
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=$this->io_id_process->uf_check_id("sep_solicitud","numsol",&$as_numsol);   		
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO sep_solicitud (codemp,numsol,codtipsol,coduniadm,fecregsol,estsol,consol,monto,".
					" 							monbasinm,montotcar,cod_pro,ced_bene,tipo_destino,codfuefin,estapro,ayuda,codtar)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_codtipsol."','".$as_coduniadm."',".
					" 			  '".$ad_fecregsol."','R','".$as_consol."',".$ai_total.",".$ai_subtotal.",".$ai_cargos.",".
					"			  '".$as_codprov."','".$as_cedben."','".$as_tipodestino."','".$as_codfuefin."',0,".$as_ayuda.",$as_codtar)";        //print $ls_sql;
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				 $this->io_sql->rollback();
				 if ($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
				 {
					/* $this->uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,
					 $as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
					 $as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
					 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$aa_seguridad);*/
					$this->uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_destino,$as_codpro,$ls_cedben,$as_consol,$ls_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,
$ai_totrowcuentascargo,$ls_tabla,$ls_campo,$ai_totrowservicios,$ai_totrowconceptos,$aa_seguridad,$es_ayuda,$as_codpro,$as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar);

				 }
				 else
				 {
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				 }
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
													   $as_codestpro5,$ai_totrowbienes,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														 $as_codestpro5,$ai_totrowservicios,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$ai_totrowconceptos,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
				}		
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$aa_seguridad);
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_dt_medicos($as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar,$as_codpro,$as_cedben,$as_numsol,$ad_fecregsol,$as_consol,$ai_total,$aa_seguridad);
				}

				if($lb_valido)
				{	
					$this->io_mensajes->message("La Solicitud fue registrada.");
					$this->io_sql->commit();
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
	function uf_insert_dt_medicos($as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar,$as_codpro,$as_cedben,$as_numsol,$ad_fecregsol,$as_consol,$ai_total,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_medicos
		//		   Access: private
		//	    Arguments: 	as_codtar 		//codigo servicio medico
		//					as_codnom 		//codigo de la nomina
		//					as_ced_trabajador // Cedula del Trabajador
		//					as_ced_familiar	// Cedula del familiar
		//					as_codpro 		// codigo del proveedor
		//					as_cedben		// cedula del beneficiario
		//				   	as_numsol  		// Número de Solicitud 
		//					ad_fecregsol	// Fecha del Registro
		//					as_consol		// Concepto
		//					ai_total 		// Monto Total
		//				   	aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los detalles del Servicio Medicos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/03/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sme_dt_beneficiario (codemp,codtar,codnom,cedper,cedfam,
							cod_pro,ced_bene,numsol,fecreg,consem,monto)".
				"	  VALUES ('".$this->ls_codemp."',".$as_codtar.",".$as_codnom.",'".$as_ced_trabajador."',".
				" 			  '".$as_ced_familiar."','".$as_codpro."','".$as_cedben."','".$as_numsol.
				"','".$ad_fecregsol."','".$as_consol."',".$ai_total.")";
		//print $ls_sql;
		//return false;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_dt_medicos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Detalle del Beneficiario ".$as_codtar." a la SME ".$as_numsol.
							 " Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_dt_medicos


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							  $ai_totrowbienes,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los bienes de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowbienes)&&($lb_valido);$li_i++)
		{
			$ls_codart=$_POST["txtcodart".$li_i];
			$ls_denart=$_POST["txtdenart".$li_i];
			$li_canart=$_POST["txtcanart".$li_i];
			$ls_unidad=$_POST["cmbunidad".$li_i];
			$li_preart=$_POST["txtpreart".$li_i];
			$li_subtotart=$_POST["txtsubtotart".$li_i];
			$li_carart=$_POST["txtcarart".$li_i];
			$li_totart=$_POST["txttotart".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$ls_unidadfisica=$_POST["txtunidad".$li_i];			
			$li_canart=str_replace(".","",$li_canart);
			$li_canart=str_replace(",",".",$li_canart);
			$li_preart=str_replace(".","",$li_preart);
			$li_preart=str_replace(",",".",$li_preart);			
			$li_totart=str_replace(".","",$li_totart);
			$li_totart=str_replace(",",".",$li_totart);
			$ls_sql="INSERT INTO sep_dt_articulos (codemp, numsol, codart, canart, unidad, monpre, monart, orden, codestpro1, ".
					"							   codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta,estincite,numdocdes)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codart."',".$li_canart.",".
					" 			  '".$ls_unidad."',".$li_preart.",".$li_totart.",".$li_i.",'".$as_codestpro1."','".$as_codestpro2."',".
					"			  '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$ls_spgcuenta."','NI','')";        
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Articulo ".$ls_codart." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							  	 $ai_totrowservicios,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los servicios de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowservicios)&&($lb_valido);$li_i++)
		{
			$ls_codser=$_POST["txtcodser".$li_i];
			$ls_denser=$_POST["txtdenser".$li_i];
			$ls_unidad=$_POST["txtdenunimed".$li_i];
			$li_canser=$_POST["txtcanser".$li_i];
			$li_preser=$_POST["txtpreser".$li_i];
			$li_subtotser=$_POST["txtsubtotser".$li_i];
			$li_carser=$_POST["txtcarser".$li_i];
			$li_totser=$_POST["txttotser".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$li_canser=str_replace(".","",$li_canser);
			$li_canser=str_replace(",",".",$li_canser);
			$li_preser=str_replace(".","",$li_preser);
			$li_preser=str_replace(",",".",$li_preser);			
			$li_totser=str_replace(".","",$li_totser);
			$li_totser=str_replace(",",".",$li_totser);
			$ls_sql="INSERT INTO sep_dt_servicio (codemp, numsol, codser, canser, monpre, monser, orden, codestpro1, codestpro2, ".
					"							  codestpro3, codestpro4, codestpro5, spg_cuenta,estincite,numdocdes)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codser."',".$li_canser.",".
					" 			  ".$li_preser.",".$li_totser.",".$li_i.",'".$as_codestpro1."','".$as_codestpro2."',".
					"			  '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$ls_spgcuenta."','NI','')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Servicio ".$ls_codser." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							  	 $ai_totrowconceptos,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los conceptos de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowconceptos)&&($lb_valido);$li_i++)
		{
			$ls_codcon=$_POST["txtcodcon".$li_i];
			$ls_dencon=$_POST["txtdencon".$li_i];
			$li_cancon=$_POST["txtcancon".$li_i];
			$li_precon=$_POST["txtprecon".$li_i];
			$li_subtotcon=$_POST["txtsubtotcon".$li_i];
			$li_carcon=$_POST["txtcarcon".$li_i];
			$li_totcon=$_POST["txttotcon".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$li_cancon=str_replace(".","",$li_cancon);
			$li_cancon=str_replace(",",".",$li_cancon);
			$li_precon=str_replace(".","",$li_precon);
			$li_precon=str_replace(",",".",$li_precon);			
			$li_totcon=str_replace(".","",$li_totcon);
			$li_totcon=str_replace(",",".",$li_totcon);
			$ls_sql="INSERT INTO sep_dt_concepto (codemp, numsol, codconsep, cancon, monpre, moncon, orden, codestpro1, codestpro2, ".
					"							  codestpro3, codestpro4, codestpro5, spg_cuenta)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcon."',".$li_cancon.",".
					" 			  ".$li_precon.",".$li_totcon.",".$li_i.",'".$as_codestpro1."','".$as_codestpro2."',".
					"			  '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$ls_spgcuenta."')";
			//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Concepto ".$ls_codcon." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcargos  // Total de Filas de los cargos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los cargos de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codart=$_POST["txtcodservic".$li_i];
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$ls_dencar=$_POST["txtdencar".$li_i];
			$li_bascar=$_POST["txtbascar".$li_i];
			$li_moncar=$_POST["txtmoncar".$li_i];
			$li_subcargo=$_POST["txtsubcargo".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$ls_cuentacargo=$_POST["cuentacargo".$li_i];			
			$li_bascar=str_replace(".","",$li_bascar);
			$li_bascar=str_replace(",",".",$li_bascar);			
			$li_moncar=str_replace(".","",$li_moncar);
			$li_moncar=str_replace(",",".",$li_moncar);
			$li_subcargo=str_replace(".","",$li_subcargo);
			$li_subcargo=str_replace(",",".",$li_subcargo);
			$ls_sql="INSERT INTO ".$as_tabla." (codemp, numsol, ".$as_campo.", codcar, monbasimp, monimp, monto, formula)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codart."','".$ls_codcar."',".
					" 			  ".$li_bascar.",".$li_moncar.",".$li_subcargo.",'".$ls_formulacargo."')";        
			//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$ls_codart." a la SEP ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
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
	function uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcuentas  // Total de Filas de las cuentas Presupuestarias
		//				   ai_totrowcuentascargo  // Total de Filas de las cuentas Presupuestarias del Cargo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowcuentas)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodprogas".$li_i];
			$ls_cuenta=$_POST["txtcuentagas".$li_i];
			$li_moncue=$_POST["txtmoncuegas".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);			
			$this->io_dscuentas->insertRow("codestpro",$ls_codpro);	
			$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
			$this->io_dscuentas->insertRow("moncue",$li_moncue);			
		}
		for($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodprocar".$li_i];
			$ls_cuenta=$_POST["txtcuentacar".$li_i];
			$li_moncue=$_POST["txtmoncuecar".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);
			$this->io_dscuentas->insertRow("codestpro",$ls_codpro);
			$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);
			$this->io_dscuentas->insertRow("moncue",$li_moncue);
		}
		$this->io_dscuentas->group_by(array('0'=>'codestpro','1'=>'cuenta'),array('0'=>'moncue'),'moncue');
		$li_total=$this->io_dscuentas->getRowCount('codestpro');	
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codpro=$this->io_dscuentas->getValue('codestpro',$li_fila);
			$ls_cuenta=$this->io_dscuentas->getValue('cuenta',$li_fila);
			$li_moncue=$this->io_dscuentas->getValue('moncue',$li_fila);
			$ls_codestpro1=substr($ls_codpro,0,20);
			$ls_codestpro2=substr($ls_codpro,20,6);
			$ls_codestpro3=substr($ls_codpro,26,3);
			$ls_codestpro4=substr($ls_codpro,29,2);
			$ls_codestpro5=substr($ls_codpro,31,2);
			$ls_sql="INSERT INTO sep_cuentagasto (codemp, numsol, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
					"							  spg_cuenta, monto)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codestpro1."','".$ls_codestpro2."',".
					" 			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."',".$li_moncue.")";        
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a la SEP ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcuentascargo  // Total de Filas de las cuentas Presupuestarias del Cargo
		//				   ai_totrowcargos  // Total de Filas de los Cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de los cargos de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$li_bascar=$_POST["txtbascar".$li_i];
			$li_moncar=$_POST["txtmoncar".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$li_bascar=str_replace(".","",$li_bascar);
			$li_bascar=str_replace(",",".",$li_bascar);			
			$li_moncar=str_replace(".","",$li_moncar);
			$li_moncar=str_replace(",",".",$li_moncar);
			$this->io_dscargos->insertRow("codcar",$ls_codcar);	
			$this->io_dscargos->insertRow("monobjret",$li_bascar);	
			$this->io_dscargos->insertRow("monret",$li_moncar);	
			$this->io_dscargos->insertRow("formula",$ls_formulacargo);	
		}
		$this->io_dscargos->group_by(array('0'=>'codcar'),array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];		
		if ($ls_tipafeiva=='P')
		   {
			 for ($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
			     { 
				   $ls_codcargo	  = $_POST["txtcodcargo".$li_i];
				   $ls_codpro	  = $_POST["txtcodprocar".$li_i];
				   $ls_cuenta	  = $_POST["txtcuentacar".$li_i];
				   $li_moncue	  = $_POST["txtmoncuecar".$li_i];
				   $li_row        = $this->io_dscargos->find("codcar",$ls_codcargo);		
				   $li_monobjret  = $this->io_dscargos->getValue("monobjret",$li_row);
				   $li_monret     = $this->io_dscargos->getValue("monret",$li_row);
				   $ls_formula    = $this->io_dscargos->getValue("formula",$li_row);		
				   $ls_codestpro1 = substr($ls_codpro,0,20);
				   $ls_codestpro2 = substr($ls_codpro,20,6);
			 	   $ls_codestpro3 = substr($ls_codpro,26,3);
				   $ls_codestpro4 = substr($ls_codpro,29,2);
				   $ls_codestpro5 = substr($ls_codpro,31,2);
				   $li_moncue     = str_replace(".","",$li_moncue);
				   $li_moncue     = str_replace(",",".",$li_moncue);		
				   $ls_sccuenta   = "";
				   $lb_valido=$this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
														      $ls_cuenta,&$ls_sccuenta);
				   if ($lb_valido)
				      {
					    $ls_sql="INSERT INTO sep_solicitudcargos (codemp, numsol, codcar, monobjret, monret, cod_pro, ced_bene, codestpro1,".
							    "                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
							    "								  formula, monto)".
							    "	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcargo."',".$li_monobjret.",".$li_monret.",".
							    "			  '".$as_codprov."','".$as_cedben."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
							    " 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."','".$ls_sccuenta."','".$ls_formula."',".
							    "			   ".$li_moncue.")";        
					    $li_row=$this->io_sql->execute($ls_sql);
					    if ($li_row===false)
					       {
						     $lb_valido=false;
						     $this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					       }
					    else
					       {
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
							 $ls_evento="INSERT";
							 $ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a los cargos ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
							 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
								 							$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
							 								$aa_seguridad["ventanas"],$ls_descripcion);
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					       }
				      }
				   else
				      {
					    $this->io_mensajes->message("ERROR-> La cuenta Presupuestaria ".$ls_cuenta." No tiene cuenta contable asociada."); 
				      }
			     }
		   }
		elseif($ls_tipafeiva=='C')
		   {
		     $li_totrowcre = $this->io_dscargos->getRowCount("codcar");
			 for ($li_i=1;$li_i<=$li_totrowcre;$li_i++)
			     {
				   $ls_codcargo	  = $_POST["txtcodcar".$li_i];
				   $li_row        = $this->io_dscargos->find("codcar",$ls_codcargo);		
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_row);
				   $ld_monret     = $this->io_dscargos->getValue("monret",$li_row);
				   $ls_formula    = $this->io_dscargos->getValue("formula",$li_row);
				   $ls_codestpro1 = '--------------------';
				   $ls_codestpro2 = '------';
				   $ls_codestpro3 = '---';
				   $ls_codestpro4 = '--';
				   $ls_codestpro5 = '--';
				   $ls_scgcta     = $_POST["cuentacargo".$li_i];
				   
				   $ls_sql="INSERT INTO sep_solicitudcargos (codemp, numsol, codcar, monobjret, monret, cod_pro, ced_bene, codestpro1,".
						   "                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
						   "							     formula, monto)".
						   "	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcargo."',".$ld_monobjret.",".$ld_monret.",".
						   "			  '".$as_codprov."','".$as_cedben."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
						   " 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_scgcta."','".$ls_scgcta."','".$ls_formula."',".
						   "			   ".$ld_monret.")";        
				   $rs_data=$this->io_sql->execute($ls_sql);
				   if ($li_row===false)
				      {
						$lb_valido = false;
				        $this->io_mensajes->message("CLASE->tepuy_sme_c_solicitud.php;MÉTODO->uf_insert_cargos (IVA Contable) ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				      }
				   else
					  {
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la Cuenta ".$ls_scgcta." al Cargo ".$ls_codcargo." para la SEP : ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
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
	function uf_update_solicitud($as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,$as_codtipsol,
							     $ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								 $as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
								 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprov  // Código de Proveedor 
		//				   as_cedben  // Código de Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas Cargos
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sep_solicitud ".
				"   SET coduniadm = '".$as_coduniadm."', ".
				"		tipo_destino = '".$as_tipodestino."', ".
				"		cod_pro	= '".$as_codprov."', ".
				"		ced_bene = '".$as_cedben."', ".
				"		consol = '".$as_consol."', ".
				"		codfuefin = '".$as_codfuefin."', ".
				"		monto = ".$ai_total.", ".
				"		monbasinm = ".$ai_subtotal.", ".
				"		montotcar = ".$ai_cargos." ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND numsol = '".$as_numsol."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_update_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			}	
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												   $as_codestpro5,$ai_totrowbienes,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												  	  $as_codestpro5,$ai_totrowconceptos,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												  	 $as_codestpro5,$ai_totrowservicios,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
			}		
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$aa_seguridad);
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
	function uf_delete_detalles($as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sep_dta_cargos ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND numsol = '".$as_numsol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dtc_cargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dts_cargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_cuentagasto ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_solicitudcargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_articulos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_concepto ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_servicio ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		//$lb_valido=true;
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_numsol,&$as_estsol,$as_operacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. MIguel Palencia
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
//				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"	    (SELECT (asignado-(comprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sep_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sep_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = sep_cuentagasto.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sep_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sep_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = sep_cuentagasto.spg_cuenta) AS existe ".		
				"  FROM sep_cuentagasto  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
						if($as_operacion!='S')
						{
							$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
                        }							
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
				$as_estsol="E";
			}
			else
			{
				$as_estsol="R";
			}
			$ls_sql="UPDATE sep_solicitud ".
					"   SET estsol='".$as_estsol."' ".
					" WHERE codemp = '".$this->ls_codemp."'".
					"	AND numsol = '".$as_numsol."' ";
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();			
			}
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprovben,$as_consol,
						$as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
						$as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$ai_totrowservicios,
						$ai_totrowconceptos,$aa_seguridad,&$as_estsol,$as_permisosadministrador,$es_ayuda,$as_codpro,$as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_sme_p_solicitud.php)
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprovben  // Código de Proveedor / Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas de los cargos
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//				   as_estsol  // Estatus de la solicitud
		//				   as_permisosadministrador  // Indica si el usuario tiene permiso de administrador
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la sep
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$lb_encontrado=$this->uf_select_solicitud($as_numsol);
		$ai_subtotal=str_replace(".","",$ai_subtotal);
		$ai_subtotal=str_replace(",",".",$ai_subtotal);
		$ai_cargos=str_replace(".","",$ai_cargos);
		$ai_cargos=str_replace(",",".",$ai_cargos);
		$ai_total=str_replace(".","",$ai_total);
		$ai_total=str_replace(",",".",$ai_total);
		$ls_operacion=substr($as_codtipsol,5,1);
		$ls_codtipsol=substr($as_codtipsol,0,2);
		$ls_tipo=substr($as_codtipsol,3,1);
		switch($ls_tipo)
		{
			case "B": // si es de Bienes
				$ls_tabla="sep_dta_cargos";
				$ls_campo="codart";
				break;
			case "S": // si es de Servicios
				$ls_tabla="sep_dts_cargos";
				$ls_campo="codser";
				break;
			case "O": // si es de Conceptos
				$ls_tabla="sep_dtc_cargos";
				$ls_campo="codconsep";
				break;
		}
		//print $ls_tabla;
		$ad_fecregsol=$this->io_funciones->uf_convertirdatetobd($ad_fecregsol);
		$ls_codprov="----------";
		$ls_cedben="----------";
		if($as_tipodestino=="P")
		{
			$ls_codprov=$as_codprovben;
		}
		if($as_tipodestino=="B")
		{
			$ls_cedben=$as_codprovben;
		}
		$as_destino="P";
		//return false; // abortar 
		switch ($as_existe)
		{
			case "FALSE":
					if($as_permisosadministrador!=1)
					{
						$lb_valido=$this->uf_validar_fecha_sep($ad_fecregsol);
					//	  permite grabar sep en cualquier fecha sin tomar o validar la anterior
						if(!$lb_valido)
						{
							$this->io_mensajes->message("La Fecha de esta Solicitud es menor a la fecha de la Solicitud anterior.");
							//return false;
					//desactive esto para que continue con el proceso de grabar la solicitud
						}
					}
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregsol,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error);           
						return false;
					}                    
					$lb_valido=$this->uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_destino,
														  $as_codpro,$ls_cedben,$as_consol,$ls_codtipsol,$ai_subtotal,$ai_cargos,
														  $ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,
														  $ai_totrowcuentascargo,$ls_tabla,$ls_campo,$ai_totrowservicios,
														  $ai_totrowconceptos,$aa_seguridad,$es_ayuda,$as_codpro,$as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar);
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_solicitud($as_numsol,$as_coduniadm,$as_codfuefin,$as_destino,
														  $ls_codpro,$ls_cedben,$as_consol,$ls_codtipsol,$ai_subtotal,$ai_cargos,
														  $ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,
														  $ai_totrowcuentascargo,$ls_tabla,$ls_campo,$ai_totrowservicios,
														  $ai_totrowconceptos,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Solicitud no existe, no la puede actualizar.");
				}
				break;
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_validar_cuentas($as_numsol,&$as_estsol,$ls_operacion);
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_solicitud($as_numsol,$as_codtar,$as_codnom,$as_ced_trabajador,$as_ced_familiar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_solicitud
		//		   Access: public
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//print "codtar: ".$as_codtar."codnom: ".$as_codnom;
		//return false;
		$this->io_sql->begin_transaction();
		//print "cargos".doubleval($as_cargos);
		//if(doubleval($as_cargos>0))
		//{				
			$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
		//}
		//else
		//{
		//	$ls_valido=true;
		//}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_solicitud ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"	AND numsol = '".$as_numsol."' ";
//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				$ls_sql="DELETE FROM sme_dt_beneficiario WHERE codemp = '".$this->ls_codemp."' ".
					"AND codtar=".$as_codtar." AND codnom=".$as_codnom." ".
					" AND cedper='".$as_ced_trabajador."' AND numsol='".$as_numsol."'";

				//print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_solicitud ERROR->sme_dt_beneficiario".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
				else
				{

					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
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
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_delete_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
    
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Miguel Palencia   
		// Modificado por: Ing. MIguel Palencia            
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 10/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM tepuy_config ".
			    " WHERE codemp='".$this->ls_codemp."' ".
			    "   AND codsis='SEP' ".
			    "   AND seccion='RELEASE' ".
			    "   AND entry='VALIDACION-PRESUPUESTARIA-FONCREI' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				/*print " <select name='cmbtipfor' id='cmbtipfor'> ";
				print " <option value='-' selected>-- Seleccione Uno --</option> ";
				print " <option value='F1' <?php if($ls_tipoformato=='F1'){ print 'selected';} ?> FORMATO 1</option> ";
				print " <option value='F2' <?php if($ls_tipoformato=='F2'){ print 'selected';} ?> FORMATO 2</option> ";
				print " </select> ";*/
				$lb_valido=true; 
			}
		}
		return rtrim($lb_valido);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="INSERT INTO tepuy_config(codemp, codsis, seccion, entry, value, type)VALUES ".
				"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$this->io_sql->commit();
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>
