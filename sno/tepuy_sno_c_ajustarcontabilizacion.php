<?PHP
class tepuy_sno_c_ajustarcontabilizacion
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_funciones;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_ajustarcontabilizacion()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_ajustarcontabilizacion
		//		   Access: public (tepuy_sno_p_manejoperiodo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia	
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: 					Fecha Última Modificación : 08/11/2006
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
        $this->ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		
	}// end function tepuy_sno_c_ajustarcontabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sno_p_manejoperiodo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_cierre_periodo2);
		unset($this->io_cierre_periodo3);
		unset($this->io_cierre_periodo4);
		unset($this->io_vacacion);
		unset($this->io_sno);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizado 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_contabilizado devuelve si esta contabilizado el perído anterior
		//	  Description: Función que se encarga de verificar si el período anterior está contabilizado
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 30/05/2006          Fecha última Modificacion : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_contabilizado=1;
			$ls_sql="SELECT conper, apoconper, ingconper, fidconper, ".
					"		(SELECT COUNT(sno_hsalida.codconc) ".
					"  		   FROM sno_hsalida ".
					" 		  WHERE sno_hsalida.codemp=sno_periodo.codemp ".
					"   		AND sno_hsalida.codnom=sno_periodo.codnom ".
					"   		AND sno_hsalida.codperi=sno_periodo.codperi ".
					"   		AND (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='P2') ".
					"   		AND sno_hsalida.valsal<>0) AS existeaporte, ".
					"		(SELECT COUNT(codperi) ".
					"  		   FROM sno_hperiodo ".
					" 		  WHERE codemp='".$this->ls_codemp."' ".
					"  			AND codnom='".$this->ls_codnom."' ".
					"   		AND codperi='".$this->ls_peractnom."') AS existehistorico ".
					"  FROM sno_periodo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_contabilizado=0;
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_contabilizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_conper=$row["conper"];
				$li_apoconper=$row["apoconper"];
				$li_ingconper=$row["ingconper"];
				$li_fidconper=$row["fidconper"];
				if(($li_conper=="1")||($li_apoconper=="1")||($li_ingconper=="1")||($li_fidconper=="1"))
				{
					$lb_contabilizado=1;
				}
				else
				{
					$lb_contabilizado=0;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
	   	return $lb_contabilizado;
    }// end function uf_contabilizado
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_ajustecontabilizacion($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_ajustecontabilizacion 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización del período
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$ls_codnom=$this->ls_codnom;
		$ls_codperi=$this->ls_peractnom;
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ld_fecdesper=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
		$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		$ls_codcom=$ls_anocurnom."-".$ls_codnom."-".$ls_codperi."-N"; // Comprobante de Conceptos
		$ls_codcomapo=$ls_anocurnom."-".$ls_codnom."-".$ls_codperi."-A"; // Comprobante de Aportes
		$ls_descripcion=$ls_desnom."- Período ".$ls_codperi." del ".$ld_fecdesper." al ".$ld_fechasper; // Descripción de Conceptos
		$ls_descripcionapo=$ls_desnom." APORTES - Período ".$ls_codperi." del ".$ld_fecdesper." al ".$ld_fechasper; // Descripción de Aportes
		$ls_cuentapasivo="";
		$ls_operacionnomina="";
		$ls_operacionaporte="";
		$ls_tipodestino="";
		$ls_codpro="";
		$ls_codben="";
		$li_gennotdeb="";
		$li_genvou="";
		$li_genrecdoc="";
		$li_genrecapo="";
		$li_tipdocnom="";
		$li_tipdocapo="";
		// Obtenemos la configuración de la contabilización de la nómina
		$lb_valido=$this->uf_load_configuracion_contabilizacion($ls_cuentapasivo,$ls_operacionnomina,$ls_operacionaporte,
																$ls_tipodestino,$ls_codpro,$ls_codben,$li_gennotdeb,$li_genvou,
																$li_genrecdoc,$li_genrecapo,$li_tipdocnom,$li_tipdocapo);
		if($lb_valido)
		{	// eliminamos la contabilización anterior 
			$lb_valido=$this->uf_delete_contabilizacion($ls_codperi);
		}														
		if($lb_valido)
		{ // insertamos la contabilización de presupuesto de conceptos
			$lb_valido=$this->uf_contabilizar_conceptos_spg($ls_codcom,$ls_operacionnomina,$ls_codpro,$ls_codben,
															$ls_tipodestino,$ls_descripcion,$li_genrecdoc,$li_tipdocnom,
															$li_gennotdeb,$li_genvou);
		}
		if($lb_valido)
		{// insertamos la contabilización de contabilidad de conceptos
			if($ls_operacionnomina!="O")// Si es compromete no genero detalles contables
			{
				$lb_valido=$this->uf_contabilizar_conceptos_scg($ls_codcom,$ls_operacionnomina,$ls_codpro,$ls_codben,
																$ls_tipodestino,$ls_descripcion,$ls_cuentapasivo,$li_genrecdoc,
																$li_tipdocnom,$li_gennotdeb,$li_genvou);
			}
		}
		if($lb_valido)
		{ // insertamos la contabilización de presupuesto de aportes
			$lb_valido=$this->uf_contabilizar_aportes_spg($ls_codcomapo,$ls_operacionaporte,$ls_codpro,$ls_codben,
														  $ls_tipodestino,$ls_descripcionapo,$ls_cuentapasivo,
														  $li_genrecapo,$li_tipdocapo,$li_gennotdeb,$li_genvou);
		}
		if($lb_valido)
		{// insertamos la contabilización de contabilidad de aportes
			if($ls_operacionaporte!="O")// Si es compromete no genero detalles contables
			{
				$lb_valido=$this->uf_contabilizar_aportes_scg($ls_codcomapo,$ls_codpro,$ls_codben,$ls_tipodestino,$ls_descripcionapo,
															  $li_genrecapo,$li_tipdocapo,$li_gennotdeb,$li_genvou,$ls_operacionaporte);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_contabilizacion($ls_codcom); // Nómina
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_contabilizacion($ls_codcomapo); // Aportes
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Ajustó la Contabilización del Año ".$this->ls_anocurnom." período ".$this->ls_peractnom." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
			$this->io_mensajes->message("El Ajuste de la contabilización fue procesado.");
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_mensajes->message("Ocurrio un error al ajustar la contabilización.");
		}
		return  $lb_valido;    
	}// end function uf_procesar_ajustecontabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_contabilizacion(&$as_cuentapasivo,&$as_modo,&$as_modoaporte,&$as_destino,&$as_codpro,&$as_codben,
												   &$ai_gennotdeb,&$ai_genvou,&$ai_genrecdoc,&$ai_genrecapo,&$ai_tipdocnom,&$ai_tipdocapo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_contabilizacion 
		//	    Arguments: as_cuentapasivo  //  cuenta pasivo a la que va la nómina
		//	    		   as_modo  //  modo de contabilización de la nómina
		//	    		   as_modoaporte  //  modo de contabilización de los aportes
		//	    		   as_destino  //  destino de la contabilización
		//	    		   as_codpro  //  código de proveedor
		//	    		   as_codben  // código de beneficiario
		//	    		   ai_gennotdeb  // generar nota de débito
		//	    		   ai_genvou  // generar voucher
		//	    		   ai_genrecdoc  // generar recepción de documento
		//	    		   ai_genrecapo  // generar recepción de documento de aporte
		//	    		   ai_tipdocnom  // Tipo de documento de nómina
		//	    		   ai_tipdocapo  // Tipo de documento de aporte
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que busca los datos de la configuración de la contabilización de la nómina
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$li_parametros=$this->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$as_cuentapasivo=$this->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","-------------------------","C");
				$as_modo=$this->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C");
				$as_modoaporte=$this->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
				$as_destino=$this->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C");
				$ai_gennotdeb=$this->io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I");
				$ai_genvou=$this->io_sno->uf_select_config("SNO","CONFIG","VOUCHER GENERAR","1","I");
				$ai_genrecdoc=str_pad($this->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"),1,"0");
				$ai_genrecapo=str_pad($this->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"),1,"0");
				$ai_tipdocnom=$this->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C");
				$ai_tipdocapo=$this->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C");
				switch (substr($as_destino,0,1))
				{
					case "P":
						$as_codpro=substr($as_destino,1,strlen($as_destino)-1);
						$as_destino="P";
						$as_codben="----------";
						break;
						
					case "B":
						$as_codben=substr($as_destino,1,strlen($as_destino)-1);
						$as_codpro="----------";
						$as_destino="B";
						break;
						
					default:
						$ls_con_descon=substr($as_destino,1,strlen($as_destino)-1);
						$as_destino=" ";
						$as_codpro="----------";
						$as_codben="----------";
				}
				break;
				
			case 1: // La contabilización es por nómina
				$as_cuentapasivo=trim($_SESSION["la_nomina"]["cueconnom"]);
				$as_modo=trim($_SESSION["la_nomina"]["consulnom"]);
				$as_modoaporte=trim($_SESSION["la_nomina"]["conaponom"]);
				$as_destino=trim($_SESSION["la_nomina"]["descomnom"]);
				$as_codpro=str_pad(trim($_SESSION["la_nomina"]["codpronom"]),10,"-");
				$as_codben=trim($_SESSION["la_nomina"]["codbennom"]);
				if(trim($as_codben)=="")
				{
					$as_codben=str_pad(trim($_SESSION["la_nomina"]["codbennom"]),10,"-");			
				}
				$ai_gennotdeb=trim($_SESSION["la_nomina"]["notdebnom"]);
				$ai_genvou=trim($_SESSION["la_nomina"]["numvounom"]);
				$ai_genrecdoc=str_pad(trim($_SESSION["la_nomina"]["recdocnom"]),1,"0");
				$ai_genrecapo=str_pad(trim($_SESSION["la_nomina"]["recdocapo"]),1,"0");
				$ai_tipdocnom=trim($_SESSION["la_nomina"]["tipdocnom"]);
				$ai_tipdocapo=trim($_SESSION["la_nomina"]["tipdocapo"]);
				break;
		}
		if(trim($as_destino)=="")
		{
			$lb_valido=false;
			$this->io_mensajes->message("ERROR-> La nómina debe tener una Destino de Contabilización (Proveedor ó Beneficiario).");
		}
		else
		{
			if($as_destino=="P") // Es un proveedor
			{
				if(trim($as_codpro)=="")
				{
					$lb_valido=false;
					$this->io_mensajes->message("ERROR-> Debe Seleccionar un Proveedor.");
				}
			}
			if($as_destino=="B") // Es un Beneficiario
			{
				if(trim($as_codpro)=="")
				{
					$lb_valido=false;
					$this->io_mensajes->message("ERROR-> Debe Seleccionar un Beneficiario. ");
				}
			}
		}
		if($ai_genrecdoc=="1") // Genera recepción de Documento de la Nómina.
		{
			if(trim($ai_tipdocnom)=="")
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Debe Seleccionar un Tipo de Documento,Para la Recepción de Documento de la Nómina. ");
			}
		}
		if($ai_genrecapo=="1") // Genera recepción de Documento de los aportes
		{
			if(trim($ai_tipdocapo)=="")
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Debe Seleccionar un Tipo de Documento,Para la Recepción de Documento de los Aportes. ");
			}
		}
		return  $lb_valido;    
	}// end function uf_load_configuracion_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_contabilizacion($as_peractnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_contabilizacion
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la contabilización de los conceptos en spg
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_dt_spg ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_peractnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_delete_conceptos_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM sno_dt_scg ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$as_peractnom."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_delete_conceptos_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}		
		}
		return $lb_valido;
    }// end function uf_delete_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
									 	   $as_programatica,$as_cueprecon,$ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,
										   $ai_gennotdeb,$ai_genvou,$as_codcomapo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_spg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilizacion si es de nómina ó de aporte
		//			       as_codconc // código del concepto
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_codestpro1=substr($as_programatica,0,20);
		$ls_codestpro2=substr($as_programatica,20,6);
		$ls_codestpro3=substr($as_programatica,26,3);
		$ls_codestpro4=substr($as_programatica,29,2);
		$ls_codestpro5=substr($as_programatica,31,2);
		$ls_sql="INSERT INTO sno_dt_spg(codemp,codnom,codperi,codcom,tipnom,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				"spg_cuenta,operacion,codconc,cod_pro,ced_bene,tipo_destino,descripcion,monto,estatus,estrd,codtipdoc,estnumvou,".
				"estnotdeb,codcomapo)VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codcom."',".
				"'".$as_tipnom."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',".
				"'".$as_cueprecon."','".$as_operacionnomina."','".$as_codconc."','".$as_codpro."','".$as_codben."','".$as_tipodestino."',".
				"'".$as_descripcion."',".$ai_monto.",".$li_estatus.",".$ai_genrecdoc.",'".$ai_tipdoc."',".$ai_genvou.",".$ai_gennotdeb.",".
				"'".$as_codcomapo."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_insert_contabilizacion_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,$as_cuenta,$as_operacion,
									 	   $ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,$ai_gennotdeb,$ai_genvou,$as_codcomapo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_scg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilización es aporte ó de conceptos
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   as_codconc  //  Código de concepto
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_sql="INSERT INTO sno_dt_scg(codemp,codnom,codperi,codcom,tipnom,sc_cuenta,debhab,codconc,cod_pro,ced_bene,tipo_destino,".
				"descripcion,monto,estatus,estrd,codtipdoc,estnumvou,estnotdeb,codcomapo)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
				"'".$this->ls_peractnom."','".$as_codcom."','".$as_tipnom."','".$as_cuenta."','".$as_operacion."','".$as_codconc."',".
				"'".$as_codpro."','".$as_codben."','".$as_tipodestino."','".$as_descripcion."',".$ai_monto.",".$li_estatus.",".
				"".$ai_genrecdoc.",'".$ai_tipdoc."',".$ai_genvou.",".$ai_gennotdeb.",'".$as_codcomapo."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_insert_contabilizacion_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										   $ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdocnom  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_tipnom="N"; // tipo de contabilización
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) AS total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) AS total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hconcepto.sigcon = 'E'".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hconcepto.sigcon = 'E'".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_contabilizar_conceptos_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("cueprecon");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_programatica=$this->DS->data["programatica"][$li_i];
					$ls_cueprecon=$this->DS->data["cueprecon"][$li_i];
					$li_total=round($this->DS->data["total"][$li_i],2);
					$ls_codconc="0000000001";
					$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
					$lb_valido=$this->uf_insert_contabilizacion_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,
																	$as_tipodestino,$as_descripcion,$ls_programatica,
																	$ls_cueprecon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecdoc,
																	$ai_tipdocnom,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
				}
				$this->DS->resetds("cueprecon");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_scg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										   $as_cuentapasivo,$ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos_scg 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_cuentapasivo  //  cuenta pasivo
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdocnom  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_tipnom="N";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.sigcon = 'E' ".
				"   AND sno_hconcepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.sigcon = 'E' ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND sno_hconcepto.sigcon = 'B' ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3' )".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		if($as_operacionnomina=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($ai_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
						$ls_cadena="CONVERT('".$as_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRE":
						$ls_cadena="CAST('".$as_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.",  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
						"  FROM sno_hpersonalnomina, sno_hsalida, sno_banco, scg_cuentas ".
						" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
						"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3' )".
						"   AND sno_hsalida.valsal <> 0 ".
						"   AND (sno_hpersonalnomina.pagbanper = 1 OR sno_hpersonalnomina.pagtaqper = 1) ".
						"   AND sno_hpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$as_cuentapasivo."' ".
						"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
						"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
						"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
						"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
						"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
						"   AND sno_hsalida.codemp = sno_banco.codemp ".
						"   AND sno_hsalida.codnom = sno_banco.codnom ".
						"   AND sno_hsalida.codperi = sno_banco.codperi ".
						"   AND sno_hpersonalnomina.codemp = sno_banco.codemp ".
						"   AND sno_hpersonalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else // Se genera Recepción de documentos
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_proveedor ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'P'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_thnomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_beneficiario ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'B'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_thnomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND sno_hpersonalnomina.pagbanper = 0 ".
					"   AND sno_hpersonalnomina.pagtaqper = 0 ".
					"   AND sno_hpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND scg_cuentas.codemp = sno_hpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_banco, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND (sno_hpersonalnomina.pagbanper = 1 OR sno_hpersonalnomina.pagtaqper = 1) ".
					"   AND sno_hpersonalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_banco.codemp ".
					"   AND sno_hsalida.codnom = sno_banco.codnom ".
					"   AND sno_hsalida.codperi = sno_banco.codperi ".
					"   AND sno_hpersonalnomina.codemp = sno_banco.codemp ".
					"   AND sno_hpersonalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND sno_hpersonalnomina.pagbanper = 0 ".
					"   AND sno_hpersonalnomina.pagtaqper = 0 ".
					"   AND sno_hpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND scg_cuentas.codemp = sno_hpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_contabilizar_conceptos_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_cuenta=$this->DS->data["cuenta"][$li_i];
					$ls_operacion=$this->DS->data["operacion"][$li_i];
					$li_total=abs(round($this->DS->data["total"][$li_i],2));
					$ls_codconc="0000000001";
					$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
					$lb_valido=$this->uf_insert_contabilizacion_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
																	$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
																	$ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
				}
				$this->DS->resetds("cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_conceptos_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_aportes_spg($as_codcom,$as_operacionaporte,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										 $as_cuentapasivo,$ai_genrecapo,$ai_tipdocapo,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_aportes_spg 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionaporte  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecapo  //  Generar recepción de documento
		//	    		   ai_tipdocapo  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_tipnom="A"; // tipo de contabilización
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, sum(sno_hsalida.valsal) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, sno_hconcepto.codpro, spg_cuentas.spg_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, sum(sno_hsalida.valsal) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_contabilizar_aportes_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'codconc','1'=>'programatica','2'=>'cueprepatcon'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("cueprepatcon");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_programatica=$this->DS->data["programatica"][$li_i];
					$ls_cueprepatcon=$this->DS->data["cueprepatcon"][$li_i];
					$li_total=abs(round($this->DS->data["total"][$li_i],2));
					$ls_codpro=$this->DS->data["codprov"][$li_i];
					$ls_cedben=$this->DS->data["cedben"][$li_i];
					$ls_codconc=$this->DS->data["codconc"][$li_i];
					if($ls_codpro=="----------")
					{
						$ls_tipodestino="B";
					}
					if($ls_cedben=="----------")
					{
						$ls_tipodestino="P";
					}
					$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
					$lb_valido=$this->uf_insert_contabilizacion_spg($as_codcom,$as_operacionaporte,$ls_codpro,$ls_cedben,
																	$ls_tipodestino,$as_descripcion,$ls_programatica,
																	$ls_cueprepatcon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecapo,
																	$ai_tipdocapo,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
				}
				$this->DS->resetds("cueprepatcon");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_aportes_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_aportes_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,$ai_genrecapo,$ai_tipdocapo,
										 $ai_gennotdeb,$ai_genvou,$as_operacionaporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_aportes_scg 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecapo  //  Generar recepción de documento
		//	    		   ai_tipdocapo  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_operacionaporte  //  operación del aporte
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_tipnom="A";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		if(($as_operacionaporte=="OC")&&($ai_genrecapo=="1"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_hconcepto.codprov <> '----------' ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"	AND sno_hconcepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_hconcepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_proveedor.sc_cuenta ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben  ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_hconcepto.cedben <> '----------' ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"	AND sno_hconcepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_hconcepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_beneficiario.sc_cuenta ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben  ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconpatcon ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_contabilizar_aportes_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'codconc','1'=>'cuenta','2'=>'operacion'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_cuenta=$this->DS->data["cuenta"][$li_i];
					$ls_operacion=$this->DS->data["operacion"][$li_i];
					$li_total=abs(round($this->DS->data["total"][$li_i],2));
					$ls_codpro=$this->DS->data["codprov"][$li_i];
					$ls_cedben=$this->DS->data["cedben"][$li_i];
					$ls_codconc=$this->DS->data["codconc"][$li_i];
					$ls_tipodestino="";
					if($ls_codpro=="----------")
					{
						$ls_tipodestino="B";
					}
					if($ls_cedben=="----------")
					{
						$ls_tipodestino="P";
					}
					$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
					$lb_valido=$this->uf_insert_contabilizacion_scg($as_codcom,$ls_codpro,$ls_cedben,$ls_tipodestino,$as_descripcion,
																	$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
																	$ai_genrecapo,$ai_tipdocapo,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
				}
				$this->DS->resetds("cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_aportes_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_contabilizacion($as_codcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_contabilizacion 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de verificar que lo mismo que esta por el debe tambien este por el haber en contabilidad
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 29/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_sql="SELECT debhab, sum(monto) as total ".
				"  FROM sno_dt_scg ".
				" WHERE codcom = '".$as_codcom."' ".
				" GROUP BY debhab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_verificar_contabilizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_debe=0;
			$li_haber=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_operacion=$row["debhab"];
				if($li_operacion=="D")
				{
					$li_debe=$row["total"];
				}
				else
				{
					$li_haber=$row["total"];
				}
			}
			$this->io_sql->free_result($rs_data);
			if($li_debe!=$li_haber)
			{
				$lb_valido=false;
				if(substr($as_codcom,14,1)=="A")
				{
					$ls_texto=" Aportes";
				}
				else
				{
					$ls_texto=" Nómina";
				}
				$this->io_mensajes->message("Los Monto en la Contabilización de ".$ls_texto." no cuadran. Debe=".$this->io_fun_nomina->uf_formatonumerico($li_debe)." Haber ".$this->io_fun_nomina->uf_formatonumerico($li_haber).". Verifique la información ");
			}
		}		
		return  $lb_valido;    
	}// end function uf_verificar_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_ajustecontabilizacion_proceso($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_ajustecontabilizacion_proceso 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización del período
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$ls_codnom=$this->ls_codnom;
		$ls_codperi=$this->ls_peractnom;
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ld_fecdesper=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
		$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		$ls_codcom=$ls_anocurnom."-".$ls_codnom."-".$ls_codperi."-N"; // Comprobante de Conceptos
		$ls_codcomapo=$ls_anocurnom."-".$ls_codnom."-".$ls_codperi."-A"; // Comprobante de Aportes
		$ls_descripcion=$ls_desnom."- Período ".$ls_codperi." del ".$ld_fecdesper." al ".$ld_fechasper; // Descripción de Conceptos
		$ls_descripcionapo=$ls_desnom." APORTES - Período ".$ls_codperi." del ".$ld_fecdesper." al ".$ld_fechasper; // Descripción de Aportes
		$ls_cuentapasivo="";
		$ls_operacionnomina="";
		$ls_operacionaporte="";
		$ls_tipodestino="";
		$ls_codpro="";
		$ls_codben="";
		$li_gennotdeb="";
		$li_genvou="";
		$li_genrecdoc="";
		$li_genrecapo="";
		$li_tipdocnom="";
		$li_tipdocapo="";
		// Obtenemos la configuración de la contabilización de la nómina
		$lb_valido=$this->uf_load_configuracion_contabilizacion($ls_cuentapasivo,$ls_operacionnomina,$ls_operacionaporte,
																$ls_tipodestino,$ls_codpro,$ls_codben,$li_gennotdeb,$li_genvou,
																$li_genrecdoc,$li_genrecapo,$li_tipdocnom,$li_tipdocapo);
		if($lb_valido)
		{	// eliminamos la contabilización anterior 
			$lb_valido=$this->uf_delete_contabilizacion($ls_codperi);
		}														
		if($lb_valido)
		{ // insertamos la contabilización de presupuesto de conceptos
			$lb_valido=$this->uf_load_conceptos_spg_normales();
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_conceptos_spg_proyecto();
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_conceptos_spg_proyectos($ls_codcom,$ls_operacionnomina,$ls_codpro,$ls_codben,
																    $ls_tipodestino,$ls_descripcion,$li_genrecdoc,$li_tipdocnom,
																    $li_gennotdeb,$li_genvou);
			}
		}
		if($lb_valido)
		{ // insertamos la contabilización de contabilidad de conceptos
			if($ls_operacionnomina!="O")// Si es compromete no genero detalles contables
			{
				$lb_valido=$this->uf_load_conceptos_scg_normales($ls_operacionnomina,$ls_cuentapasivo,$li_genrecdoc);
				if($lb_valido)
				{
					$lb_valido=$this->uf_load_conceptos_scg_proyecto();
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_conceptos_scg_proyectos($ls_codcom,$ls_operacionnomina,$ls_codpro,$ls_codben,
																		$ls_tipodestino,$ls_descripcion,$ls_cuentapasivo,
																		$li_genrecdoc,$li_tipdocnom,$li_gennotdeb,$li_genvou);
				}
			}
		}
		if($lb_valido)
		{ // insertamos la contabilización de presupuesto de los aportes
			$lb_valido=$this->uf_load_aportes_spg_normales();
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_aportes_spg_proyecto();
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_aportes_spg_proyectos($ls_codcomapo,$ls_operacionaporte,$ls_codpro,$ls_codben,
															  	  $ls_tipodestino,$ls_descripcionapo,$ls_cuentapasivo,
															  	  $li_genrecapo,$li_tipdocapo,$li_gennotdeb,$li_genvou);
			}
		}
		if($lb_valido)
		{ // insertamos la contabilización de contabilidad de los aportes
			if($ls_operacionaporte!="O")// Si es compromete no genero detalles contables
			{
				$lb_valido=$this->uf_load_aportes_scg_normales($ls_operacionaporte,$li_genrecapo);
				if($lb_valido)
				{
					$lb_valido=$this->uf_load_aportes_scg_proyecto();
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_aportes_scg_proyectos($ls_codcomapo,$ls_codpro,$ls_codben,$ls_tipodestino,
																	  $ls_descripcionapo,$li_genrecapo,$li_tipdocapo,$li_gennotdeb,
																	  $li_genvou,$ls_operacionaporte);
				}
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_contabilizacion($ls_codcom); // Nómina
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_contabilizacion($ls_codcomapo); // Aportes
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Ajustó la Contabilización del Año ".$this->ls_anocurnom." período ".$this->ls_peractnom." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
			$this->io_mensajes->message("El Ajuste de la contabilización fue procesado.");
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_mensajes->message("Ocurrio un error al ajustar la contabilización.");
		}
		return  $lb_valido;    
	}// end function uf_procesar_ajustecontabilizacion_proceso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos_spg_normales()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos_spg_normales 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos Normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) AS total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) AS total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hconcepto.sigcon = 'E'".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta as cueprecon, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hconcepto.sigcon = 'E'".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_conceptos_spg_normales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_conceptos_spg_normales
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos_spg_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos_spg_proyecto 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos por proyecto
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, sum(sno_hsalida.valsal) AS total, MAX(sno_hproyectopersonal.pordiames) AS pordiames  ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT  sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta, ".
				"		".$ls_cadena." as montoparcial, sum(sno_hsalida.valsal) AS total, MAX(sno_hproyectopersonal.pordiames) AS pordiames ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hconcepto.sigcon = 'E'".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta ".
				" ORDER BY codper, spg_cuenta, codproy ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_conceptos_spg_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,2)!=round($li_totalant,2))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),2);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprecon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],2);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$li_pordiamesant=$li_pordiames;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprecon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
			}
			if((number_format($li_acumulado,2,".","")!=number_format($li_totalant,2,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),2);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprecon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_conceptos_spg_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptos_spg_proyectos($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										   	   $ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos_spg_proyectos 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdocnom  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se lee el datastored y lo manda a insertar
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="N"; // tipo de contabilización
		$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon'),array('0'=>'total'),'total');
		$li_totrow=$this->DS->getRowCount("cueprecon");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_programatica=$this->DS->data["programatica"][$li_i];
			$ls_cueprecon=$this->DS->data["cueprecon"][$li_i];
			$li_total=round($this->DS->data["total"][$li_i],2);
			$ls_codconc="0000000001";
			$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
			if($li_total>0)
			{
				$lb_valido=$this->uf_insert_contabilizacion_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,
																$as_tipodestino,$as_descripcion,$ls_programatica,
																$ls_cueprecon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecdoc,
																$ai_tipdocnom,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
			}
		}
		$this->DS->reset_ds();
		return $lb_valido;    
	}// end function uf_insert_conceptos_spg_proyectos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos_scg_normales($as_operacionnomina,$as_cuentapasivo,$ai_genrecdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos_scg_normales 
		//	    Arguments: as_operacionnomina  //  Operación de la contabilización
		//	    		   as_cuentapasivo  //  cuenta pasivo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '1' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0".
				"   AND sno_hconcepto.intprocon = '0' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.sigcon = 'E' ".
				"   AND sno_hconcepto.intprocon = '1' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.sigcon = 'E' ".
				"   AND sno_hconcepto.intprocon = '0' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND sno_hconcepto.sigcon = 'B' ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total ".
				"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3' )".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon ".
				" GROUP BY scg_cuentas.sc_cuenta ";
		if($as_operacionnomina=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($ai_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
						$ls_cadena="CONVERT('".$as_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRE":
						$ls_cadena="CAST('".$as_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.",  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
						"  FROM sno_hpersonalnomina, sno_hsalida, sno_banco, scg_cuentas ".
						" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
						"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3' )".
						"   AND sno_hsalida.valsal <> 0 ".
						"   AND (sno_hpersonalnomina.pagbanper = 1 OR sno_hpersonalnomina.pagtaqper = 1) ".
						"   AND sno_hpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$as_cuentapasivo."' ".
						"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
						"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
						"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
						"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
						"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
						"   AND sno_hsalida.codemp = sno_banco.codemp ".
						"   AND sno_hsalida.codnom = sno_banco.codnom ".
						"   AND sno_hsalida.codperi = sno_banco.codperi ".
						"   AND sno_hpersonalnomina.codemp = sno_banco.codemp ".
						"   AND sno_hpersonalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else // Se genera Recepción de documentos
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_proveedor ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'P'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_thnomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_beneficiario ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'B'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_thnomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND sno_hpersonalnomina.pagbanper = 0 ".
					"   AND sno_hpersonalnomina.pagtaqper = 0 ".
					"   AND sno_hpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND scg_cuentas.codemp = sno_hpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_banco, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND (sno_hpersonalnomina.pagbanper = 1 OR sno_hpersonalnomina.pagtaqper = 1) ".
					"   AND sno_hpersonalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_banco.codemp ".
					"   AND sno_hsalida.codnom = sno_banco.codnom ".
					"   AND sno_hsalida.codperi = sno_banco.codperi ".
					"   AND sno_hpersonalnomina.codemp = sno_banco.codemp ".
					"   AND sno_hpersonalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta,  CAST('H' AS char(1)) as operacion, -sum(sno_hsalida.valsal) as total ".
					"  FROM sno_hpersonalnomina, sno_hsalida, scg_cuentas ".
					" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' OR sno_hsalida.tipsal = 'D' ".
					"    OR  sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3')".
					"   AND sno_hsalida.valsal <> 0".
					"   AND sno_hpersonalnomina.pagbanper = 0 ".
					"   AND sno_hpersonalnomina.pagtaqper = 0 ".
					"   AND sno_hpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND scg_cuentas.codemp = sno_hpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_conceptos_scg_normales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_conceptos_scg_normales
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos_scg_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos_scg_proyecto 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos por proyectos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total, ".
				"		".$ls_cadena." as montoparcial, sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, ".
				"		MAX(sno_hproyectopersonal.pordiames) AS pordiames, sno_hconcepto.codconc ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hconcepto.conprocon = '1' ".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hconcepto.codconc, sno_hproyectopersonal.codproy, scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(sno_hsalida.valsal) as total, ".
				"		".$ls_cadena." as montoparcial, sno_hproyectopersonal.codper, sno_hproyectopersonal.codproy, ".
				"		MAX(sno_hproyectopersonal.pordiames) AS pordiames, sno_hconcepto.codconc ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND sno_hconcepto.sigcon = 'E' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hconcepto.codconc, sno_hproyectopersonal.codproy, scg_cuentas.sc_cuenta ".
				" ORDER BY codper, codconc, codproy,  cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_conceptos_scg_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_codconcant="";
			$ls_operacionant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["cuenta"];
				$ls_operacion=$row["operacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,2)!=round($li_totalant,2))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),2);
							$this->DS->insertRow("operacion",$ls_operacionant);
							$this->DS->insertRow("cuenta",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],2);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$ls_codant=$ls_codper;
					$li_pordiamesant=$li_pordiames;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$li_totalant=$li_total;
				}
				$this->DS->insertRow("operacion",$ls_operacion);
				$this->DS->insertRow("cuenta",$ls_cuenta);
				$this->DS->insertRow("total",$li_montoparcial);
			}
			if((number_format($li_acumulado,2,".","")!=number_format($li_totalant,2,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),2);
				$this->DS->insertRow("operacion",$ls_operacionant);
				$this->DS->insertRow("cuenta",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_conceptos_scg_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptos_scg_proyectos($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										   	   $as_cuentapasivo,$ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos_scg_proyectos 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_cuentapasivo  //  cuenta pasivo
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdocnom  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_tipnom="N";
		$this->DS->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');
		$li_totrow=$this->DS->getRowCount("cuenta");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cuenta=$this->DS->data["cuenta"][$li_i];
			$ls_operacion=$this->DS->data["operacion"][$li_i];
			$li_total=abs(round($this->DS->data["total"][$li_i],2));
			$ls_codconc="0000000001";
			$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
			if($li_total>0)
			{
				$lb_valido=$this->uf_insert_contabilizacion_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
																$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
																$ai_genrecdoc,$ai_tipdocnom,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
			}
		}
		$this->DS->reset_ds();
		return  $lb_valido;    
	}// end function uf_insert_conceptos_scg_proyectos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_aportes_spg_normales()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_aportes_spg_normales 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, sum(sno_hsalida.valsal) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '1' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, sno_hconcepto.codpro, spg_cuentas.spg_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_hunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, sum(sno_hsalida.valsal) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '0'".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, sno_hunidadadmin.codprouniadm, spg_cuentas.spg_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_aportes_spg_normales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_aportes_spg_normales
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_aportes_spg_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_aportes_spg_proyecto 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_hsalida.valsal)*MAX(sno_hproyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_hproyecto.estproproy, spg_cuentas.spg_cuenta, sum(sno_hsalida.valsal) as total, ".
				"		".$ls_cadena." AS montoparcial,  MAX(sno_hconcepto.codprov) AS codprov, MAX(sno_hconcepto.cedben) AS cedben, sno_hconcepto.codconc, sno_hproyecto.codproy, ".
				"		sno_hproyectopersonal.codper, MAX(sno_hproyectopersonal.pordiames) AS pordiames ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur = '".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hproyecto.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta, sno_hconcepto.codconc ".
				" ORDER BY sno_hproyectopersonal.codper, sno_hproyecto.codproy, sno_hproyecto.estproproy, spg_cuentas.spg_cuenta, sno_hconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_aportes_spg_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			$ls_codconcant="";
			$ls_codprovant="";
			$ls_cedbenant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_codprov=$row["codprov"];
				$ls_cedben=$row["cedben"];
				$ls_codconc=$row["codconc"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,2)!=round($li_totalant,2))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),2);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("codprov",$ls_codprovant);
							$this->DS->insertRow("cedben",$ls_cedbenant);
							$this->DS->insertRow("codconc",$ls_codconcant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],2);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_codconcant=$ls_codconc;
					$ls_codprovant=$ls_codprov;
					$ls_cedbenant=$ls_cedben;
					$li_pordiamesant=$li_pordiames;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_codconcant=$ls_codconc;
					$ls_codprovant=$ls_codprov;
					$ls_cedbenant=$ls_cedben;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprepatcon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("codprov",$ls_codprov);
				$this->DS->insertRow("cedben",$ls_cedben);
				$this->DS->insertRow("codconc",$ls_codconc);
			}
			if((number_format($li_acumulado,2,".","")!=number_format($li_totalant,2,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),2);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("codprov",$ls_codprovant);
				$this->DS->insertRow("cedben",$ls_cedbenant);
				$this->DS->insertRow("codconc",$ls_codconcant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_aportes_spg_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_aportes_spg_proyectos($as_codcom,$as_operacionaporte,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
										     $as_cuentapasivo,$ai_genrecapo,$ai_tipdocapo,$ai_gennotdeb,$ai_genvou)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_aportes_spg_proyectos 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionaporte  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecapo  //  Generar recepción de documento
		//	    		   ai_tipdocapo  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="A"; // tipo de contabilización
		$this->DS->group_by(array('0'=>'codconc','1'=>'programatica','2'=>'cueprepatcon'),array('0'=>'total'),'total');
		$li_totrow=$this->DS->getRowCount("cueprepatcon");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_programatica=$this->DS->data["programatica"][$li_i];
			$ls_cueprepatcon=$this->DS->data["cueprepatcon"][$li_i];
			$li_total=abs(round($this->DS->data["total"][$li_i],2));
			$ls_codpro=$this->DS->data["codprov"][$li_i];
			$ls_cedben=$this->DS->data["cedben"][$li_i];
			$ls_codconc=$this->DS->data["codconc"][$li_i];
			if($ls_codpro=="----------")
			{
				$ls_tipodestino="B";
			}
			if($ls_cedben=="----------")
			{
				$ls_tipodestino="P";
			}
			$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
			$lb_valido=$this->uf_insert_contabilizacion_spg($as_codcom,$as_operacionaporte,$ls_codpro,$ls_cedben,
															$ls_tipodestino,$as_descripcion,$ls_programatica,
															$ls_cueprepatcon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecapo,
															$ai_tipdocapo,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
		}
		$this->DS->reset_ds();
		return  $lb_valido;    
	}// end function uf_insert_aportes_spg_proyectos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_aportes_scg_normales($as_operacionaporte,$ai_genrecapo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_aportes_scg_normales 
		//	    Arguments: as_operacionaporte  //  operación del aporte
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '1'".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
				"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
				"  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.intprocon = '0' ".
				"   AND sno_hconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		if(($as_operacionaporte=="OC")&&($ai_genrecapo=="1"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_hconcepto.codprov <> '----------' ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"	AND sno_hconcepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_hconcepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_proveedor.sc_cuenta ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben  ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_hconcepto.cedben <> '----------' ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"	AND sno_hconcepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_hconcepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_beneficiario.sc_cuenta ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben  ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, CAST('H' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
					"		sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_hsalida.valsal <> 0 ".
					"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
					"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
					"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
					"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
					"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
					"   AND scg_cuentas.codemp = sno_hconcepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconpatcon ".
					" GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, sno_hconcepto.cedben ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_aportes_scg_normales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_aportes_scg_normales
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_aportes_scg_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_aportes_scg_proyecto 
		//	    Arguments:
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos normales
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 18/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(abs(sno_hsalida.valsal))*MAX(sno_hproyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_hsalida.valsal))*MAX(sno_hproyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_hsalida.valsal)) as total, ".
				"		".$ls_cadena." AS montoparcial, MAX(sno_hconcepto.codprov) AS codprov, MAX(sno_hconcepto.cedben) AS cedben, sno_hconcepto.codconc, ".
				"		sno_hproyectopersonal.codper, sno_hproyecto.codproy, MAX(sno_hproyectopersonal.pordiames) AS pordiames ".
				"  FROM sno_hproyectopersonal, sno_hproyecto, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_hsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_hproyectopersonal.codemp = sno_hsalida.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hsalida.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hsalida.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hsalida.codperi ".
				"   AND sno_hproyectopersonal.codper = sno_hsalida.codper ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hproyectopersonal.codemp = sno_hproyecto.codemp ".
				"   AND sno_hproyectopersonal.codnom = sno_hproyecto.codnom ".
				"   AND sno_hproyectopersonal.anocur = sno_hproyecto.anocur ".
				"   AND sno_hproyectopersonal.codperi = sno_hproyecto.codperi ".
				"   AND sno_hproyectopersonal.codproy = sno_hproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_hconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_hproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_hproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_hproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_hproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_hproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_hproyectopersonal.codper, sno_hconcepto.codconc, sno_hproyecto.codproy, scg_cuentas.sc_cuenta ".
				" ORDER BY codper, codconc, codproy, sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Contabilización MÉTODO->uf_load_aportes_scg_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_operacionant="";
			$ls_codconcant="";
			$ls_codprovant="";
			$ls_cedbenant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["sc_cuenta"];
				$ls_operacion=$row["operacion"];
				$ls_codprov=$row["codprov"];
				$ls_cedben=$row["cedben"];
				$ls_codconc=$row["codconc"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,2)!=round($li_totalant,2))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),2);
							$this->DS->insertRow("operacion",$ls_operacionant);
							$this->DS->insertRow("cuenta",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("codprov",$ls_codprovant);
							$this->DS->insertRow("cedben",$ls_cedbenant);
							$this->DS->insertRow("codconc",$ls_codconcant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],2);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codant=$ls_codper;
					$ls_codconcant=$ls_codconc;
					$ls_codprovant=$ls_codprov;
					$ls_cedbenant=$ls_cedben;
					$li_pordiamesant=$li_pordiames;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$li_totalant=$li_total;
					$ls_codconcant=$ls_codconc;
					$ls_codprovant=$ls_codprov;
					$ls_cedbenant=$ls_cedben;
				}
				$this->DS->insertRow("operacion",$ls_operacion);
				$this->DS->insertRow("cuenta",$ls_cuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("codprov",$ls_codprov);
				$this->DS->insertRow("cedben",$ls_cedben);
				$this->DS->insertRow("codconc",$ls_codconc);
			}
			if((number_format($li_acumulado,2,".","")!=number_format($li_totalant,2,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),2);
				$this->DS->insertRow("operacion",$ls_operacionant);
				$this->DS->insertRow("cuenta",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("codprov",$ls_codprovant);
				$this->DS->insertRow("cedben",$ls_cedbenant);
				$this->DS->insertRow("codconc",$ls_codconcant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_load_aportes_scg_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_aportes_scg_proyectos($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,$ai_genrecapo,$ai_tipdocapo,
											 $ai_gennotdeb,$ai_genvou,$as_operacionaporte)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_aportes_scg_proyectos  
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecapo  //  Generar recepción de documento
		//	    		   ai_tipdocapo  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_operacionaporte  //  Operación con que se va a contabilizar los aportes
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 13/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="A";
		$this->DS->group_by(array('0'=>'codconc','1'=>'cuenta','2'=>'operacion'),array('0'=>'total'),'total');
		$li_totrow=$this->DS->getRowCount("cuenta");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cuenta=$this->DS->data["cuenta"][$li_i];
			$ls_operacion=$this->DS->data["operacion"][$li_i];
			$li_total=abs(round($this->DS->data["total"][$li_i],2));
			$ls_codpro=$this->DS->data["codprov"][$li_i];
			$ls_cedben=$this->DS->data["cedben"][$li_i];
			$ls_codconc=$this->DS->data["codconc"][$li_i];
			$ls_tipodestino="";
			if($ls_codpro=="----------")
			{
				$ls_tipodestino="B";
			}
			if($ls_cedben=="----------")
			{
				$ls_tipodestino="P";
			}
			$ls_codcomapo=substr($ls_codconc,2,8).$this->ls_peractnom.$this->ls_codnom;
			$lb_valido=$this->uf_insert_contabilizacion_scg($as_codcom,$ls_codpro,$ls_cedben,$ls_tipodestino,$as_descripcion,
															$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
															$ai_genrecapo,$ai_tipdocapo,$ai_gennotdeb,$ai_genvou,$ls_codcomapo);
		}
		$this->DS->reset_ds();
		return $lb_valido;    
	}// end function uf_insert_aportes_scg_proyectos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>