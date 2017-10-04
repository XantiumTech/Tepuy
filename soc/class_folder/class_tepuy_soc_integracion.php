<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_tepuy_soc_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de presupuesto de  gasto y el sistema de compra.                      //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_tepuy_soc_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_solicitud;
	var $dts_recepcion;
	var $dts_ordencompra;
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_tepuy_int;
	var $io_fecha;
	var $io_msg;
	var $io_class_sep_int;
	var $io_seguridad;
	// NUEVO //
	var $dts_proveedor;
	var $dts_beneficiario;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_tepuy_soc_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_tepuy_sno_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 20/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_tepuy_int.php");
		require_once("../shared/class_folder/class_tepuy_int_int.php");
		require_once("../shared/class_folder/class_tepuy_int_spg.php");
		require_once("../shared/class_folder/class_tepuy_int_scg.php");
		require_once("../shared/class_folder/class_tepuy_int_spi.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_mensajes.php");
	//	require_once("class_folder/class_tepuy_sep_integracion.php");  
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("class_folder/class_funciones_mis.php");
	    $this->io_fun_mis=new class_funciones_mis();
	    $this->io_fecha = new class_fecha();
        $this->io_tepuy_int = new class_tepuy_int_int();
		$this->io_function = new class_funciones() ;
		$this->io_siginc = new tepuy_include();
		$this->io_connect = $this->io_siginc->uf_conectar();
		$this->io_sql = new class_sql($this->io_connect);		
		$this->dts_empresa = $_SESSION["la_empresa"];
		$this->dts_solicitud = new class_datastore();
		$this->dts_recepcion = new class_datastore();
		$this->dts_ordencompra = new class_datastore();
		$this->dts_beneficiario=new class_datastore(); //NUEVO
		$this->dts_proveedor=new class_datastore(); //NUEVO
		$this->io_msg = new class_mensajes();		
	//	$this->io_class_sep_int = new class_tepuy_sep_integracion();
		$this->io_seguridad=new tepuy_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
		$this->as_procedeaux="";
		$this->as_comprobanteaux="";
		$this->ad_fechaaux="";
		$this->as_codbanaux="";
		$this->as_ctabanaux="";
	}// end function class_tepuy_soc_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 20/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_tepuy_int) ) { unset($this->io_tepuy_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->dts_solicitud) ) { unset($this->dts_solicitud);  }	   
		if( is_object($this->dts_ordencompra) ) { unset($this->dts_ordencompra);  }	   	   
		if( is_object($this->dts_recepcion) ) { unset($this->dts_recepcion);  }	   	   	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_class_sep_int) ) { unset($this->io_class_sep_int);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_contabilizar_ordencompra($as_numordcom,$as_estcondat,$adt_fecha,$aa_seguridad)
	{
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_ordencompra
		//         Access: public
		//     Argumentos: as_numordcom // numero de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 adt_fecha // fecha de contabilizacion      
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Este metodo es el método principal que genera la contabilización de la orden de compra  
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// inicia transacción SQL
		$this->io_tepuy_int->uf_int_init_transaction_begin();
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$tipocontabilidad=$this->dts_empresa["esttipcont"];
		// Si existe un comprobante  tipo SEP pre-compromiso en presupuesto se manda a reversar 
		$lb_valido=$this->uf_reversar_en_gasto_solicitud_presupuestaria($as_numordcom,$as_estcondat,$adt_fecha,$aa_seguridad);
		if($lb_valido) 	
		{  // se genera la contabilización de la orden de compras
			$lb_valido=$this->uf_procesar_contabilizacion_compras($as_numordcom,$as_estcondat,$adt_fecha,$aa_seguridad);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			switch($as_estcondat)
			{
				case "-":
					$ls_estatus="Bienes/Servicios";
					break;
				case "B":
					$ls_estatus="Bienes";
					break;
				case "S":
					$ls_estatus="Servicios";
					break;
			}
			$ls_descripcion="Contabilizó la Orden de Compra <b>".$as_numordcom."</b>, Estatus <b>".$ls_estatus."</b>, ".
							"Fecha <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/// PARA LA CONVERSIÓN MONETARIA
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($lb_valido)
			{
			//	$lb_valido=$this->io_fun_mis->uf_convertir_tepuycmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
				$lb_valido=$this->io_fun_mis->uf_convertir_tepuycmp($this->as_procede,$this->as_comprobante,$adt_fecha,
																	 $this->as_codban,$this->as_ctaban,$aa_seguridad);
			}
			if($lb_valido)
			{
			//	$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
				$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$adt_fecha,
																	$this->as_codban,$this->as_ctaban,$aa_seguridad);
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_tepuy_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    } // end function uf_contabilizar_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_en_gasto_solicitud_presupuestaria($as_numordcom,$as_estcondat,$adt_fecordcom,$aa_seguridad)
	{
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_en_gasto_solicitud_presupuestaria
		//         Access: private
		//     Argumentos: as_numordcom // numero de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 adt_fecha // fecha de contabilizacion      
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Este metodo tiene como fin reversar el precompromiso generado por la solicitud sep 
		//                 en el sistema de gastos.          
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $lb_valido=$this->uf_obtener_data_orden_compra($as_numordcom,$as_estcondat);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_sql="SELECT soc_enlace_sep.numsol, sep_solicitud.fechaconta ".
                "  FROM sep_solicitud , soc_enlace_sep ".
                " WHERE soc_enlace_sep.codemp='".$ls_codemp."' ".
				"   AND soc_enlace_sep.numordcom='".$as_numordcom."' ".
				"   AND soc_enlace_sep.estcondat='".$as_estcondat."' ".
				"   AND sep_solicitud.codemp=soc_enlace_sep.codemp ".
				"   AND sep_solicitud.numsol=soc_enlace_sep.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_reversar_en_gasto_solicitud_presupuestaria ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data)and($lb_valido))
			{
				$ls_numsol=$row["numsol"];
				$ld_fechaconta=$row["fechaconta"];
				$ldt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecordcom);
				if($this->io_fecha->uf_comparar_fecha($ld_fechaconta,$ldt_fecha))
				{

					$lb_valido=$this->uf_reversar_precomprometido_solicitud_sep($ls_numsol,$adt_fecordcom);
					if(!$lb_valido)
					{ 
						$this->io_msg->message("ERROR -> Al reversar precompromiso SEP Nº=".$ls_numsol." ".$this->io_tepuy_int->is_msg_error);
						$lb_valido=false;
						return false;		   
					}
					else
					{
						$lb_valido=$this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$adt_fecordcom); 
						if(!$lb_valido)
						{
							$this->io_msg->message("ERROR -> ".$this->io_tepuy_int->is_msg_error);
							$lb_valido=false;
						}
						else
						{
							$lb_valido=$this->io_class_sep_int->uf_update_fecha_contabilizado_sep($ls_codemp,$ls_numsol,'',$adt_fecordcom);	
							if($lb_valido)
							{
								/////////////////////////////////         SEGURIDAD               /////////////////////////////		
								$ls_evento="PROCESS";
								$ls_descripcion="Reverso el Precompromiso de la Solicitud de Ejecución Presupuestaria <b>".$ls_numsol."</b> ";
								$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
																$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
																$aa_seguridad["ventanas"],$ls_descripcion);
								/////////////////////////////////         SEGURIDAD               /////////////////////////////
							}
							///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							/// PARA LA CONVERSIÓN MONETARIA
							///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							if($lb_valido)
							{
								$lb_valido=$this->io_fun_mis->uf_convertir_tepuycmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																					 $this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
							}
							if($lb_valido)
							{
								$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																					$this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
							}
							///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						}
					}
				}
				else
				{
					$this->io_msg->message("ERROR -> La orden de compra tiene una sep asociada nro ".$ls_numsol.", con fecha de contabilización mayor a la fecha de la contabilización de la orden de compra ");
					$lb_valido=false;
				}
			}		
		}
		return $lb_valido;		
	} // end function uf_reversar_compras_solicitud_presupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_orden_compra($as_numordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_orden_compra
		//         Access: private
		//     Argumentos: as_numordcom      // numero de la order de compra
		//                 as_estcondat      // representa si es de servicio , binenes o ambos
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Este metodo obtiene el registro de la orden de compra y lo guarada en un 
		//                 datastore publico para posteriores operaciones.    
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_sql="SELECT * ".
                "  FROM soc_ordencompra ".
                " WHERE codemp='".$ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_obtener_data_orden_compra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
                $this->dts_ordencompra->data=$this->io_sql->obtener_datos($rs_data);
			}
			else  
			{ 
			   $this->io_msg->message("La Orden de Compra ".$as_numordcom." no existe en la base de datos."); 
		    }
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_obtener_data_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_precomprometido_solicitud_sep($as_numsol,$adt_fecha)	
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_precomprometido_solicitud_sep
		//         Access: private
		//     Argumentos: as_numsol // numero de la sep 
		//				   adt_fecha // fecha de contabilizacion
		//	      Returns: Retorna un booleano
		//	  Description: Este método se encarga de preparar los datos básicos del comprobante de gasto 
		//                 y los detalles de gastos pero reverso (en negativo )
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $ls_codemp=$this->dts_empresa["codemp"];
		$ls_mensaje="R";
		$ls_numsol=$as_numsol;
        $ls_procede="SEPRPC"; 
        $ls_comprobante=$ls_numsol;
        $ls_tipo_destino="P";
		$ldt_fecha=$adt_fecha;
		$ls_codigo_destino=$this->dts_ordencompra->getValue("cod_pro",1);	
        $ls_descripcion=$this->dts_ordencompra->getValue("obscom",1);			
		if(empty($ls_descripcion))
		{
			$ls_descripcion="ninguno";
		}
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
		$this->io_tepuy_int->uf_int_config(true,false);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procedeaux=$ls_procede;
		$this->as_comprobanteaux=$ls_comprobante;
		$this->ad_fechaaux=$ldt_fecha;
		$this->as_codbanaux=$ls_codban;
		$this->as_ctabanaux=$ls_ctaban;
		$lb_valido=$this->io_tepuy_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,true,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if(!$lb_valido)
		{   
			$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error); 
			return false;		   		   
		}
        // Recorro la tabla que contiene los movimientos contables presupuestarios de la SEP 
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,monto ".
				"  FROM sep_cuentagasto ".
		        " WHERE codemp='".$ls_codemp."' ".
				"   AND numsol='".$ls_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_reversar_precomprometido_solicitud_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data)and($lb_valido))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];			  
				$ls_spg_cuenta=$row["spg_cuenta"];			
				$ldec_monto=$row["monto"];
				$ldec_monto=($ldec_monto*(-1));
				$ls_estructura_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				$lb_valido=$this->io_tepuy_int->uf_spg_insert_datastore($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																	     $ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_mensaje,
																	     $ldec_monto,$ls_comprobante,$ls_procede,$ls_descripcion);
				if(!$lb_valido)
				{
					$lb_valido=false;  
					break;
				}
			}
		    $this->io_sql->free_result($rs_data);	 
	   	}
		return $lb_valido;
	} // end function uf_reversar_precomprometido_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_contabilizacion_compras($as_numordcom,$as_estcondat,$adt_fecha,$aa_seguridad)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_compras
		//         Access: public
		//     Argumentos: as_numordcom      // numero de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 adt_fecha         // fecha de la contabilizacion    
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Este metodo tiene como fin contabilizar la orden de compra con gasto y contabilidad
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->dts_empresa["codemp"];
		$tipocontabilidad=$this->dts_empresa["esttipcont"];
		$lb_valido=$this->uf_obtener_data_orden_compra($as_numordcom,$as_estcondat);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_estcom=$this->dts_ordencompra->getValue("estcom",1);
		$ls_obscom=$this->dts_ordencompra->getValue("obscom",1); 
		$ls_cod_pro=$this->dts_ordencompra->getValue("cod_pro",1);	
        	$ls_mensaje="O";
		if ($ls_estcom!=1) 
		{
			$this->io_msg->message("ERROR-> La Orden de Compra ".$as_numordcom." debe estar en estatus EMITIDA para su contabilización.");
			return false;
		}
		// obtengo la fecha de la solicitud del datastore
		$ls_fecordcom=$this->dts_ordencompra->getValue("fecordcom",1);
		$ls_fecha_cont=$ls_fecordcom;
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		// aqui debe sustituirse $ls_fecha_cont por $adt_fecha (fecha seleccionada por el usuario)
		//$ls_fecordcom=$this->io_function->uf_convertirdatetobd($ls_fecordcom);
		//$ls_fecordcom=$adt_fecha;
        if(!$this->io_fecha->uf_comparar_fecha($ls_fecordcom,$adt_fecha))
		{
			$this->io_msg->message("ERROR-> La Fecha de Contabilizacion es menor que la fecha de Emision de la Orden de Compra Nº ".$as_numordcom);
			return false;
		}
		$ls_fecha_cont=$adt_fecha;
		// AQUI OBTENGO LA CUENTA CONTABLE DEL COMPROMISO LA QUE SE VA CONTRA LA CTA. CONTABLE DE LA PARTIDA PRESUPUESTARIA //
		// BUSCA LOS DATS DEL PROVEEDOR //
		/*if($ls_tipo_destino=="B") 
		{  
			$ls_codigo_destino=$ls_ced_bene;  
			$lb_valido=$this->uf_obtener_datos_beneficiario($ls_codemp,$ls_ced_bene);
			if(!$lb_valido)
			{
				return false;
			}
			$ls_scgcta_proben=$this->dts_beneficiario->getValue("sc_cuenta_comp",1);
		}
		if($ls_tipo_destino=="P") 
		{*/
			$ls_tipo_destino="P";
			$ls_codigo_destino=$ls_cod_pro;  
			$lb_valido=$this->uf_obtener_datos_proveedor($ls_codemp,$ls_cod_pro);
			if(!$lb_valido)
			{
				return false;
			}			
			$ls_scgcta_proben=$this->dts_proveedor->getValue("sc_cuenta_comp",1);
		//}
		//print "cuenta proben: ".$ls_scgcta_proben;
		//////////////////
        // obtengo el monto de la ORDEN DE COMPRA y la comparo con el monto de gasto acumulado		
        $ldec_sum_gasto=$this->uf_sumar_total_cuentas_gasto_ordencompra($as_numordcom,$as_estcondat);
        $ldec_sum_gasto=round($ldec_sum_gasto,2);
		$ldec_monto_compra=$this->dts_ordencompra->getValue("montot",1);
        $ldec_monto_compra=round($ldec_monto_compra,2);
		if(trim($_SESSION["la_empresa"]["confiva"])=="P") // si el iva es presupuestario
		{		
			if ($ldec_monto_compra!=$ldec_sum_gasto)
			{
				$this->io_msg->message("ERROR-> La Orden de Compra no esta cuadrado con el resumen presupuestario");
				return false;
			}       
		}
		// Si la Contabilidad es Fiscal, procede a realizar asiento contable //
		//if($tipocontabilidad==2)
		//{
			// Se agrego estos comandos para iniciar registro de sgc_dt_cmp//
			$ls_codban="---";
			$ls_ctaban="-------------------------";
			$li_tipo_comp=1; // comprobante Normal
			if($as_estcondat=="S")
			{
				$ls_procede="SOCCOS";
			}
			else
			{
				$ls_procede="SOCCOC";
			}
			//$this->as_comprobante=$ls_comprobante;
			//$this->ad_fecha=$ldt_fecha;
			//$this->as_codban=$ls_codban;
			//$this->as_ctaban=$ls_ctaban;
			$lb_valido=$this->io_tepuy_int->uf_int_init($ls_codemp,$ls_procede,$as_numordcom,$adt_fecha,$ls_obscom,$ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,$li_tipo_comp);
		//}
		$lb_valido=$this->uf_generar_comprobante_ordencompra($adt_fecha,$ls_scgcta_proben);  
		if(!$lb_valido)
		{
			return false;
		}
		else
		{
			$li_estatus_orden = 2;// emitida contabilizada 
			$lb_valido = $this->uf_update_estatus_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,$li_estatus_orden);
		}		
		if(!$lb_valido)
		{
			return false;
		}
		else
		{
			// aqui es donde realmente inia el proceso de contabilizacion con la fecha
			// en este momento de la elaboracion de la orden de compra
			// cuando deberia ser $adt_fecha
			$lb_valido=$this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$ls_fecha_cont); 
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR -> ".$this->io_tepuy_int->is_msg_error);			 
			}		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_soc($ls_codemp,$as_numordcom,$as_estcondat,$adt_fecha,'1900-01-01');
		}
		return  $lb_valido;
	} // end function uf_procesar_contabilizacion_compras
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sumar_total_cuentas_gasto_ordencompra($as_numordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sumar_total_cuentas_gasto_ordencompra
		//         Access: private
		//     Argumentos: as_numordcom      // numero de la order de compra
		//                 as_estcondat      // representa si es de servicio , binenes o ambos
		//	      Returns: Retorna un decimal valor monto
		//	  Description: Este método realiza una consulta sql para obtener la sumatoria de todos los movimiento de gasto
		//                 asociado a la compras de ejeciución presupuestaria en la tabla SOC_CuentaGasto
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ldec_monto=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_sql="SELECT COALESCE(SUM(monto),0) As monto ".
                "  FROM soc_cuentagasto ".
                " WHERE codemp='".$ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_sumar_total_cuentas_gasto_ordencompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ldec_monto;
	} // end function uf_sumar_total_cuentas_gasto_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_datos_beneficiario($as_codemp,$as_ced_bene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_datos_beneficiario
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_ced_bene  // Cédula del Beneficiario
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un beneficiario específico
		//	   Creado Por: Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
		        "  FROM rpc_beneficiario ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND ced_bene='".$as_ced_bene."'";				  
		$rs_data=$this->io_sql->select($ls_sql);		
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_datos_beneficiario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
				$this->dts_beneficiario->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("El Beneficiario no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_datos_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    	function uf_obtener_datos_proveedor($as_codemp,$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_datos_proveedores
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_cod_pro  // Código de Proveedor
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un proveedor específico
		//	   Creado Por: Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=true;		
		$ls_sql="SELECT * ".
		        "  FROM rpc_proveedor ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND cod_pro='".$as_cod_pro."'";				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_datos_proveedor ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
                $this->dts_proveedor->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("El proveedor no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_datos_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_generar_comprobante_ordencompra($adt_fecha,$as_scgcta_proben)	
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_comprobante_ordencompra
		//         Access: private
		//     Argumentos: adt_fecha fecha de la orden de compra
		//	      Returns: Retorna un booleano
		//	  Description: Este método se encarga de preparar los datos básicos del comprobante de gasto 
		//                 y los detalles de gastos pero reverso (en negativo )
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        	$ls_codemp=$this->dts_empresa["codemp"];
		$tipocontabilidad=$this->dts_empresa["esttipcont"];
		$ls_mensaje="O";
		$ls_numordcom=$this->dts_ordencompra->getValue("numordcom",1);
		$ls_estcondat=$this->dts_ordencompra->getValue("estcondat",1);
        	$ls_comprobante=$ls_numordcom;
        	$ls_tipo_destino="P";
		$ldt_fecha=$adt_fecha ; 
		$ls_codigo_destino=$this->dts_ordencompra->getValue("cod_pro",1);	
        	$ls_descripcion=$this->dts_ordencompra->getValue("obscom",1);			
		if($ls_estcondat=="S")
		{ // Servicios
			$ls_procede="SOCCOS";
		}
		else
		{ // Bienes
			$ls_procede="SOCCOC";
		}
		if(empty($ls_descripcion))
		{
			$ls_descripcion="ninguno";
		}		
        $this->io_tepuy_int->uf_int_config(true,false);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido = $this->io_tepuy_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													   $ls_tipo_destino,$ls_codigo_destino,true,$ls_codban,$ls_ctaban,
													   $li_tipo_comp);
		if (!$lb_valido)
		{   
			$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			return false;		   		   
		}
        // Recorro la tabla que contiene los movimientos contables presupuestarios de la COMPRA
	/*	$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta, SUM(monto) AS monto ".
				"  FROM soc_cuentagasto ".
		        " WHERE codemp='".$ls_codemp."' ".
				"   AND numordcom='".$ls_numordcom."' ".
				"   AND estcondat='".$ls_estcondat."' ".
				" GROUP BY codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta ";*/
	// PARA CREAR detalle en scg_dt_cmp //
		$ls_sql="SELECT a.codestpro1,a.codestpro2,a.codestpro3,a.codestpro4,a.codestpro5,a.spg_cuenta, SUM(a.monto) AS monto, b.sc_cuenta ".
				"  FROM soc_cuentagasto a, spg_cuentas b".
		        " WHERE a.codemp='".$ls_codemp."' ".
				" AND a.numordcom='".$ls_numordcom."' ".
				" AND a.estcondat='".$ls_estcondat."' ".
				" AND a.codestpro1=b.codestpro1 AND a.codestpro2=b.codestpro2 AND a.codestpro3=b.codestpro3 ".
				" AND a.codestpro4=b.codestpro4 AND a.codestpro5=b.codestpro5 AND a.spg_cuenta=b.spg_cuenta".
				" GROUP BY a.codestpro1, a.codestpro2, a.codestpro3, a.codestpro4, a.codestpro5, a.spg_cuenta ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_generar_comprobante_ordencompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;		   
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];			  
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_scg_cuenta=$row["sc_cuenta"];			
				$ldec_monto=$row["monto"];
				$ls_estructura_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				$lb_valido = $this->io_tepuy_int->uf_spg_insert_datastore($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		   $ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_mensaje,
																	 	   $ldec_monto,$ls_comprobante,$ls_procede,$ls_descripcion);
				if (!$lb_valido)
				{  
					$this->io_msg->message("ERROR->".$this->io_tepuy_int->is_msg_error);
					break;

				}
				else
				{
					// SI LA CONTABILIDAD ES FISCAL INICIAMOS EL PROCESO DE CONTABILIZACION //
					if($tipocontabilidad==2)
					{
						// CREA EL DATASTORE DE SCG //
						// INSERTA EN EL DEBE //
						$ls_debhab="D";
						$lb_valido=$this->io_tepuy_int->uf_scg_insert_datastore($ls_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,
																		 $ls_comprobante,$ls_procede,$ls_descripcion);
						// INSERTA EN EL HABER //
						$ls_debhab="H";
						$lb_valido=$this->io_tepuy_int->uf_scg_insert_datastore($ls_codemp,$as_scgcta_proben,$ls_debhab,$ldec_monto,
																		 $ls_comprobante,$ls_procede,$ls_descripcion);
						if($lb_valido===false)
						{
							$this->io_msg->message("".$this->io_tepuy_int->is_msg_error);
						}
					}
				}//if
			} //while
		    $this->io_sql->free_result($rs_data);	 
	   	}
		return $lb_valido;
	} // end function uf_generar_comprobante_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_ordencompra($as_codemp,$as_numordcom,$as_estcondat,$as_estcom)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_ordencompra
		//         Access: private
		//     Argumentos: as_codemp       // codigo de la empresa
		//                 as_numordcom    // numero de la orden de compra
		//                 as_estcom       // estatus de la orden de compra
		//	      Returns: Retorna un booleano
		//	  Description: Método que actualiza el estatus de la orden de compra
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE soc_ordencompra ".
		        "   SET estcom=".$as_estcom.
                " WHERE codemp='".$as_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_generar_comprobante_ordencompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;		   
		}
		$ls_sql="UPDATE soc_enlace_sep ".
				"	SET estordcom = ".$as_estcom." ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_generar_comprobante_ordencompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;		   
		}
		return $lb_valido;
	} // end function uf_update_estatus_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_ordencompra($as_numordcom,$as_estcondat,$ad_fechaconta,$aa_seguridad)	
	{
   	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_ordencompra
		//         Access: public
		//     Argumentos: as_numordcom    // numero de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un booleano
		//	  Description: Método que reversa la conmtabilización de la orden de compra y sep si lo tiene 
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ls_codemp = $this->dts_empresa["codemp"];		
		// inicia transacción SQL
        $this->io_tepuy_int->uf_int_init_transaction_begin();
		// ser revers la contabilización de la orden de compra
        $lb_valido=$this->uf_reversar_contabilizacion_ordencompra($as_numordcom,$as_estcondat,$ad_fechaconta,$aa_seguridad);
		if ($lb_valido)
		{
			// Verifico si la orden de compra proviene de una sep
			if($this->uf_verificar_ordencompra_sep($as_numordcom,$as_estcondat))
			{
				// Se deja intacto el precompromiso de la sep
				$lb_valido=$this->uf_reversar_sep_ordendecompra($ls_codemp,$as_numordcom,$as_estcondat,$aa_seguridad);
			}
	    }
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			switch($as_estcondat)
			{
				case "-":
					$ls_estatus="Bienes/Servicios";
					break;
				case "B":
					$ls_estatus="Bienes";
					break;
				case "S":
					$ls_estatus="Servicios";
					break;
			}
			$ls_descripcion="Reversó la Orden de Compra <b>".$as_numordcom."</b>, Estatus <b>".$ls_estatus."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_tepuy_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    } // end function uf_reversar_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_ordencompra($as_numordcom,$as_estcondat,$adt_fecha,$aa_seguridad)
	{
   	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_ordencompra
		//         Access: private
		//     Argumentos: as_numordcom    // numero de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 adt_fecha  // Fecha en que fue contabilizada la orden de compra
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un booleano
		//	  Description: Método que reversa la conmtabilización de la orden de compra
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_status=""; // estatus de la recepcion de documento
        $ls_codemp=$this->dts_empresa["codemp"];
		if($as_estcondat=="S")
		{ // Servicios
			$ls_procede="SOCCOS";
		}
		else
		{ // Bienes
			$ls_procede="SOCCOC";
		}
		$ls_comprobante=$this->io_tepuy_int->uf_fill_comprobante(trim($as_numordcom));
   	    $lb_valido=$this->uf_obtener_data_orden_compra($as_numordcom,$as_estcondat);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_estcom=$this->dts_ordencompra->getValue("estcom",1);
		$ls_obscom=$this->dts_ordencompra->getValue("obscom",1); 
        $ls_tipo_destino="P";
		$ls_ced_bene="----------"; 
		$ls_cod_pro=$this->dts_ordencompra->getValue("cod_pro",1);
		if($ls_estcom!=2) 
		{
			$this->io_msg->message("ERROR-> La Orden de Compra ".$as_numordcom." debe estar CONTABILIZADA.");
			return false;
		}
		if($this->uf_busca_ordencompra_en_recepcion($ls_codemp,$as_numordcom,$as_estcondat,$ls_procede,&$ls_status)) 
		{
			if($ls_status!='A')
			{
				$this->io_msg->message("Ya existe la compra en recepción de documento.");
				return false; 
			}
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_tepuy_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																$ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_check_close=false;
		$lb_valido=$this->io_tepuy_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			return false; 
		}
		$lb_valido=$this->uf_update_estatus_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,1);
    	if(!$lb_valido)
		{
			$this->io_msg->message("ERROR-> Al cambiar estatus Orden de Compra ");
			return false;
		}
		else
		{
			//print "aqui esta el detalle";
			$lb_valido = $this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$adt_fecha); 
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			}		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_soc($ls_codemp,$as_numordcom,$as_estcondat,'1900-01-01','1900-01-01');
		}
		return  $lb_valido;
	} // end function uf_reversar_contabilizacion_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_busca_ordencompra_en_recepcion($as_codemp,$as_numordcom,$as_estcondat,$as_procede,&$as_status)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_busca_ordencompra_en_recepcion
		//         Access: private
		//     Argumentos: as_codemp // codigo de la empresa 
		//				   as_numordcom // número de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 as_procede // Procede del documento	
		//                 as_status // estatus de la Orden de compra	
		//	      Returns: retorna un boolean
		//	  Description: Verifico si la orden de compra esta en recepcion de docuemnto
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe=false;
		$ls_sql="SELECT distinct cxp_rd.* ".
                "  FROM cxp_rd, cxp_rd_spg ".
                " WHERE cxp_rd.codemp='".$as_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procede."' ".
				"   AND cxp_rd.numrecdoc='".$as_numordcom."' ".
				"   AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_busca_ordencompra_en_recepcion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
               $this->dts_recepcion->data=$this->io_sql->obtener_datos($rs_data);			
			   $as_status=$row["estprodec"];
			   $lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_busca_ordencompra_en_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_ordencompra_sep($as_numordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_ordencompra_sep
		//         Access: private
		//     Argumentos: as_numordcom      // número de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//	      Returns: retorna un boolean
		//	  Description: Verifico si la orden de compra proviene de una sep
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe=false;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_sql="SELECT numsol ".
                "  FROM soc_enlace_sep ".
                " WHERE codemp='".$ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_verificar_ordencompra_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_verificar_ordencompra_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_sep_ordendecompra($as_codemp,$as_numordcom,$as_estcondat,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_sep_ordendecompra
		//         Access: private
		//     Argumentos: as_codemp // codigo de la empresa 
		//				   as_numordcom // número de la orden de compra
		//                 as_estcondat // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: retorna un boolean
		//	  Description: Actualiza los estatus de las sep en estatus emitada (de contabilizada->emitida)
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="SELECT soc_enlace_sep.numsol, sep_solicitud.fechaanula ".
                "  FROM soc_enlace_sep, sep_solicitud ".
                " WHERE soc_enlace_sep.codemp='".$as_codemp."' ".
				"   AND soc_enlace_sep.numordcom='".$as_numordcom."' ".
				"   AND soc_enlace_sep.estcondat='".$as_estcondat."' ".
				"   AND soc_enlace_sep.codemp = sep_solicitud.codemp ".
				"   AND soc_enlace_sep.numsol = sep_solicitud.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_reversar_sep_ordendecompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))and($lb_valido))
			{
				$ls_numsol=$row["numsol"];
				$ld_fechaanula=$row["fechaanula"];
				$lb_valido=$this->uf_delete_contabilizacion_reverso_sep($as_codemp,$ls_numsol,$ld_fechaanula,$aa_seguridad);
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion="Genero el Precompromiso de la Solicitud de Ejecución Presupuestaria <b>".$ls_numsol."</b> ";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}    
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	} // end function uf_reversar_sep_ordendecompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_contabilizacion_reverso_sep($as_codemp,$as_numsol,$adt_fecha,$aa_seguridad)
	{
   	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_contabilizacion_reverso_sep
		//         Access: private
		//     Argumentos: as_codemp // codigo empresa 
		//				   as_numsol // numero solicitud 
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un booleano
		//	  Description: Método que reversa la contabilización de la orden de compra
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_procede="SEPRPC"; 
		$ls_comprobante=$this->io_tepuy_int->uf_fill_comprobante(trim($as_numsol));
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$lb_valido=$this->io_tepuy_int->uf_obtener_comprobante($as_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																$ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,
																$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");   
			return false;
		}
		$lb_check_close=false;
		$lb_valido=$this->io_tepuy_int->uf_init_delete($as_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido )	
		{ 
			$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			return false; 
		}
	    $lb_valido = $this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$adt_fecha);	
	    if(!$lb_valido)
		{
			$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			return false; 
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_class_sep_int->uf_update_fecha_contabilizado_sep($as_codemp,$as_numsol,'','1900-01-01');			
		}	
		return  $lb_valido;
	} // end function uf_delete_contabilizacion_reverso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_anular_contabilizacion($as_numordcom,$as_estcondat,$ad_fechaconta,$adt_fecha_anula,$aa_seguridad)
	{ 
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anular_contabilizacion
		//         Access: public
		//     Argumentos: as_numordcom      // numero de la orden de compra
		//                 as_estcondat     // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)
		//                 ad_fechaconta   // fecha en que fue contabilizado el documento
		//                 adt_fecha_anula   // fecha de anulación
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Este metodo tiene como fin anular la contabilizacion de la orden de compra.
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 20/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->dts_empresa["codemp"];
		$ls_comprobante=$as_numordcom;
		if($as_estcondat=="S")
		{
			$ls_procede = "SOCCOS";
		}
		else
		{
			$ls_procede = "SOCCOC";
		}
		if($as_estcondat=="S")
		{
			$ls_procede_anula = "SOCAOS";
		}
		else
		{
			$ls_procede_anula = "SOCAOC";
		}
        $ldt_fecha_cmp="";
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ls_descripcion="Anulación de la Orden de Compra Nº ".$as_numordcom;
		$ldt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha_anula);		 				
	    $lb_valido=$this->uf_obtener_data_orden_compra($as_numordcom ,$as_estcondat);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_estcom=$this->dts_ordencompra->getValue("estcom",1);
		if($ls_estcom!=2) 
		{
			$this->io_msg->message("ERROR-> La Orden de Compra ".$as_numordcom." debe estar en estatus CONTABILIZADA.");
			return false;
		}
		if ($this->uf_busca_ordencompra_en_recepcion($ls_codemp,$as_numordcom,$as_estcondat,$ls_procede,&$ls_status)) 
		{
			if ($ls_status!='A')
			{
				$this->io_msg->message("ERROR-> Ya existe la Orden de Compra en recepción de documento Nº:".$this->dts_recepcion->getValue("numrecdoc",1));
				return false; 
			}
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
	    $lb_valido = $this->io_tepuy_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ad_fechaconta,
																  $ls_codban,$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,
																  &$ls_cod_pro);
		if (!$lb_valido) 
		{ 
 		   $this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
		  return false;
		}
		$this->as_procede=$ls_procede_anula;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha_anula;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido = $this->io_tepuy_int->uf_int_anular($ls_codemp,$ls_procede,$ls_comprobante,$ad_fechaconta,
														 $ls_procede_anula,$ldt_fecha_anula,$ls_descripcion,$ls_codban,
														 $ls_ctaban,$li_tipo_comp);
		if(!$lb_valido )	
		{ 
 		   $this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
		   return false; 
		}
		// inicia transacción SQL
        $this->io_tepuy_int->uf_int_init_transaction_begin();
	    $ls_estcom=3; // estatus en anulacion
        $lb_valido=$this->uf_update_estatus_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,$ls_estcom);
	    if($lb_valido)
	    {
			$lb_valido=$this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$adt_fecha_anula); 
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
			}		   
		}
		if($lb_valido)
		{
			// colocamos la fecha de anulacion
			$lb_valido=$this->uf_update_fecha_contabilizado_soc($ls_codemp,$as_numordcom,$as_estcondat,$ad_fechaconta,$ldt_fecha_anula);
		}
		if($lb_valido)
		{
			// reversa la el precompromiso en negativo
			$lb_valido=$this->uf_reversar_sep_ordendecompra($ls_codemp,$as_numordcom,$as_estcondat,$aa_seguridad);
		}
		if($lb_valido)
		{
			// Actualiza el estatus de la sep que esta asociada a la orden de compra
			$lb_valido=$this->uf_update_estatus_sep_ordendecompra($ls_codemp,$as_numordcom,$as_estcondat,"C");
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			switch($as_estcondat)
			{
				case "-":
					$ls_estatus="Bienes/Servicios";
					break;
				case "B":
					$ls_estatus="Bienes";
					break;
				case "S":
					$ls_estatus="Servicios";
					break;
			}
			$ls_descripcion="Anulo la Orden de Compra <b>".$as_numordcom."</b>, Estatus <b>".$ls_estatus."</b>, ".
							"Fecha Anulación <b>".$adt_fecha_anula."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_tepuycmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																 $this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_tepuy_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    } // end function uf_procesar_anular_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_sep_ordendecompra($as_codemp,$as_numordcom,$as_estcondat,$as_estatus)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_sep_ordendecompra
		//         Access: private
		//     Argumentos: as_codemp->codigo de la empresa ; $as_numordcom->número de la orden de compra
		//	      Returns: retorna un boolean
		//	  Description: Actualiza los estatus de las sep en estatus contabilizada
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="SELECT numsol ".
                "  FROM soc_enlace_sep ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_update_estatus_sep_ordendecompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data)) and ($lb_valido) )
			{
				$ls_numsol=$row["numsol"];
				$lb_valido=$this->io_class_sep_int->uf_update_estatus_contabilizado_sep($as_codemp,$ls_numsol,$as_estatus);	
			}    
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_update_estatus_sep_ordendecompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reverso_anulacion($as_numordcom,$as_estcondat,$ad_fechaconta,$ad_fechaanula,$tepuy_cmp,$aa_seguridad)
	{
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_anulacion
		//         Access: public
		//     Argumentos: as_numordcom      // numero de la orden de compra
		//                 as_estcondat     // tipo de orden de compra (B=Bienes,S=Servicio,-=Ambos)		
		//                 ad_fechaconta     // Fecha en que fue contabilizado el documento	
		//                 ad_fechaanula     // Fecha en que fue anulado el documento	
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Método que reversa una anulacion. 
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 20/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->dts_empresa["codemp"];
		$ls_comprobante=$as_numordcom;	
		$lb_valido=true;	
		if($as_estcondat=="S")
		{
			$ls_procede="SOCAOS";
		}
		else
		{
			$ls_procede="SOCAOC";
		}
		$ls_nuevoestcom='1';
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaanula;
	    $lb_valido=$this->uf_obtener_data_orden_compra($as_numordcom,$as_estcondat);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_estcom=$this->dts_ordencompra->getValue("estcom",1);
		if($ls_estcom!=3) 
		{
			$this->io_msg->message("ERROR-> La Orden de Compra ".$as_numordcom." no está ANULADA.");
			return false;
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		//print "proceso tepuy_cmp=".$tepuy_cmp;
		if($tepuy_cmp==='1')
		{
			$ls_nuevoestcom='2';
	    	if($lb_valido)
	    	{
				$lb_valido=$this->io_tepuy_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																	$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
			}
			if(!$lb_valido) 
			{ 
				$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
				return false;
			}
		
	    	if($lb_valido)
	    	{
				$lb_valido=$this->uf_verificar_sep_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,$ad_fechaconta,$aa_seguridad);
			}
		}
        $lb_check_close=false;
		if($tepuy_cmp==='1')
		{
	    	if($lb_valido)
	    	{
				$lb_valido=$this->io_tepuy_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
															$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
			}
			if(!$lb_valido )	
			{ 
				$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
				return false; 
			}
        	$this->io_tepuy_int->uf_int_init_transaction_begin();
	    	$ls_estcom=2; // estatus en comprometida
	    	if($lb_valido)
	    	{
	        	$lb_valido=$this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$ldt_fecha); 
	        	if(!$lb_valido)
		    	{
					$this->io_msg->message("ERROR-> ".$this->io_tepuy_int->is_msg_error);
		    	}		   
			}
		}	
	    if($lb_valido)
	    {
	        $lb_valido=$this->uf_update_estatus_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,$ls_nuevoestcom);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_soc($ls_codemp,$as_numordcom,$as_estcondat,'','1900-01-01');
		}
		if($lb_valido)
		{
			// Actualiza el estatus de la sep que esta asociada a la orden de compra
			$lb_valido=$this->uf_update_estatus_sep_ordendecompra($ls_codemp,$as_numordcom,$as_estcondat,"P");
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			switch($as_estcondat)
			{
				case "-":
					$ls_estatus="Bienes/Servicios";
					break;
				case "B":
					$ls_estatus="Bienes";
					break;
				case "S":
					$ls_estatus="Servicios";
					break;
			}
			$ls_descripcion="Reverso la Anulación la Orden de Compra <b>".$as_numordcom."</b>, Estatus <b>".$ls_estatus."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_tepuy_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    } // end function uf_procesar_reverso_anulacion
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_sep_ordencompra($as_codemp,$as_numordcom,$as_estcondat,$ad_fechaconta,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_sep_ordencompra
		//         Access: private
		//     Argumentos: as_codemp->codigo de la empresa ; $as_numordcom->número de la orden de compra
		//	      Returns: retorna un boolean
		//	  Description: Actualiza los estatus de las sep en estatus contabilizada
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 21/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=false;
		if($as_estcondat=="S")
		{ // Servicios
			$ls_procede="SOCCOS";
		}
		else
		{ // Bienes
			$ls_procede="SOCCOC";
		}
		$ls_comprobante=$this->io_tepuy_int->uf_fill_comprobante(trim($as_numordcom));
		$ldt_fecha=$ad_fechaconta;
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_tepuy_int->uf_obtener_comprobante($as_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,
																$ls_codban,$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		if(!$lb_valido) 
		{
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");			
			return false; 
		}
		$ls_sql="SELECT soc_enlace_sep.numsol, sep_solicitud.estsol, sep_solicitud.fechaconta ".
                "  FROM soc_enlace_sep, sep_solicitud ".
                " WHERE soc_enlace_sep.codemp='".$as_codemp."' ".
				"   AND soc_enlace_sep.numordcom='".$as_numordcom."' ".
				"   AND soc_enlace_sep.estcondat='".$as_estcondat."' ".
				"   AND soc_enlace_sep.codemp = sep_solicitud.codemp ".
				"   AND soc_enlace_sep.numsol = sep_solicitud.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_verificar_sep_ordencompra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data)) and ($lb_valido) )
			{
				$ls_numsol=$row["numsol"];
				//$ld_fechaconta=$row["fechaconta"];
				$ls_estsol=$row["estsol"];
				switch($ls_estsol)
				{
					case "R": // Registro
						$this->io_msg->message("ERROR-> La Solicitud ".$ls_numsol." esta Registrada. No se puede Reversar la Anulación.");			
						$lb_valido=false;
						return false;
						break;
						
					case "E": // Emitida
						$this->io_msg->message("ERROR-> La Solicitud ".$ls_numsol." esta Emitida. No se puede Reversar la Anulación.");			
						$lb_valido=false;
						return false;
						break;
						
					case "A": // Anulada
						$this->io_msg->message("ERROR-> La Solicitud ".$ls_numsol." esta anulada. No se puede Reversar la Anulación.");			
						$lb_valido=false;
						return false;
						break;
						
					case "P": // Procesada
						$this->io_msg->message("ERROR-> La Solicitud ".$ls_numsol." esta procesada. No se puede Reversar la Anulación.");			
						$lb_valido=false;
						return false;
						break;
						
					case "C": // Contabilizada
						$lb_valido=$this->uf_reversar_precomprometido_solicitud_sep($ls_numsol,$ldt_fecha);
						if(!$lb_valido)
						{ 
							$this->io_msg->message("ERROR -> Al reversar precompromiso SEP Nº=".$ls_numsol." ".$this->io_tepuy_int->is_msg_error);
							$lb_valido=false;
							return false;		   
						}
						else
						{
							$lb_valido=$this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad,$ldt_fecha); 
							if(!$lb_valido)
							{
								$this->io_msg->message("ERROR -> ".$this->io_tepuy_int->is_msg_error);
								$lb_valido=false;
							}
							else
							{
								$lb_valido=$this->io_class_sep_int->uf_update_fecha_contabilizado_sep($as_codemp,$ls_numsol,'',$ldt_fecha);	
								if($lb_valido)
								{
									/////////////////////////////////         SEGURIDAD               /////////////////////////////		
									$ls_evento="PROCESS";
									$ls_descripcion="Reverso el Precompromiso de la Solicitud de Ejecución Presupuestaria <b>".$ls_numsol."</b> ";
									$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
																	$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
																	$aa_seguridad["ventanas"],$ls_descripcion);
									/////////////////////////////////         SEGURIDAD               /////////////////////////////
								}
								///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								/// PARA LA CONVERSIÓN MONETARIA
								///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								if($lb_valido)
								{
									$lb_valido=$this->io_fun_mis->uf_convertir_tepuycmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																						 $this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
								}
								if($lb_valido)
								{
									$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																						$this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
								}
								///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							}
						}
						break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_verificar_sep_ordencompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_soc($as_codemp,$as_numordcom,$as_estcondat,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_soc
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numordcom  // numero de la orden de compras
		//                 as_estcondat  // Estatus si es de compras o de servicios
		//                 ad_fechaconta  // Fecha de contabilización 
		//                 ad_fechaanula  // Fecha de Anulación
		//	      Returns: ad_fechaanula True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing.
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_campo1="";
		$ls_campo2="";
		if($ad_fechaconta!="")
		{
			$ls_campo1=" fechaconta='".$ad_fechaconta."' ";
		}
		if($ad_fechaanula!="")
		{
			$ls_campo2=" fechaanula='".$ad_fechaanula."' ";
		}
		if($ls_campo1!="")
		{
			if($ls_campo2!="")
			{
				$ls_campos=$ls_campo1.", ".$ls_campo2;
			}
			else
			{
				$ls_campos=$ls_campo1;
			}
		}
		else
		{
			$ls_campos=$ls_campo2;
		}
		$ls_sql="UPDATE soc_ordencompra ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOC MÉTODO->uf_update_fecha_contabilizado_soc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_soc
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
