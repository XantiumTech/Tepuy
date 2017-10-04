<?php
class tepuy_soc_c_generar_orden_analisis
{
  function tepuy_soc_c_generar_orden_analisis($as_path)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: tepuy_soc_c_generar_orden_analisis
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 05/08/2015 								Fecha Última Modificación : 29/05/2015 
	////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/tepuy_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/tepuy_c_generar_consecutivo.php");
		$io_include			= new tepuy_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new tepuy_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$this->io_dscuentas=new class_datastore();
		$this->io_dscargos=new class_datastore();
		$this->io_keygen    = new tepuy_c_generar_consecutivo(); 
  }

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_analisis_cotizacion($as_numanacot,$ad_fecdes,$ad_fechas,$as_tipanacot)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_analisis_cotizacion
		//		   Access: public
		//		 Argument: 
		//   $as_numanacot //Número del Análisis de Cotizacion
		//      $ad_fecdes //Fecha a partir del cual comenzará la búsqueda de los Análisis de Cotizacion
		//      $ad_fechas //Fecha hasta el cual comenzará la búsqueda de los Análisis de Cotizacion
		//   $as_tipanacot//Tipo del Analisis de Cotizacion B=Bienes , S=Servicios.
		//      $as_tipope //Tipo de la Operación a ejecutar A=Aprobacion, R=Reverso de la Aprobación.
		//	  Description: Función que busca los Analisis de Cotizacion que esten dispuestas para Aprobacion/Reverso.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/08/2015								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ls_straux = "";
		
        if (!empty($as_numordcom))
		   {
		     $ls_straux = " AND a.numanacot LIKE '%".$as_numanacot."%'";
		   } 
		if (!empty($ad_fecdes) && !empty($ad_fechas))
		   {  
		     $ld_fecdes = $this->io_funciones->uf_convertirdatetobd($ad_fecdes);
			 $ld_fechas = $this->io_funciones->uf_convertirdatetobd($ad_fechas);
			 $ls_straux = $ls_straux." AND a.fecanacot BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."'";
		   }
		if ($as_tipanacot!='-')
		   {  
		     $ls_straux = $ls_straux." AND tipsolcot='".$as_tipanacot."'";
		   }
		$ls_sql ="SELECT DISTINCT a.numanacot,a.obsana,a.fecanacot,a.tipsolcot,a.fecapro,b.nompro
				    FROM soc_analisicotizacion a,rpc_proveedor b, soc_cotxanalisis c
		           WHERE a.codemp='$this->ls_codemp' $ls_straux 
					 AND a.estana=1 
					AND b.codemp=c.codemp AND b.cod_pro=c.cod_pro AND a.numanacot=c.numanacot
					 AND a.numanacot NOT IN (SELECT numanacot 
					                           FROM soc_ordencompra 
											  WHERE codemp='$this->ls_codemp' AND estcom<>3) 
				 ORDER BY numanacot ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_soc_c_aprobacion_analisis_cotizacion.php->MÉTODO->uf_load_analisis_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_ordenes_compra
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($ai_totrows,$as_tipope,$ad_fecope,$aa_seguridad)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_guardar
	//		   Access: public
	//		 Argument: 
	//     $ai_totrows //Total de elementos cargados en el Grid de Analisis de Cotizacion
	//      $as_tipope //Tipo de la Operación a realizar A=Aprobación, R=Reverso de Aprobación.
	//      $ad_fecope //Fecha en la cual se ejecuta la Operación.
	//   $aa_seguridad //Arreglo de seguridad cargado de la informacion de usuario y pantalla.
	//	  Description: Función que recorre el grid de los analisis de cotizacion y genera las respectivas ordenes de compra
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 09/08/2015								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido 	= false;
	  $ls_tipafeiva = $_SESSION["la_empresa"]["confiva"]; 
	  $ls_numorden = $_POST["txtnumanacot"];
	  $ld_fecha		= $this->io_funciones->uf_convertirdatetobd($ad_fecope);
	  $this->io_sql->begin_transaction();

	  for ($i=1;$i<=$ai_totrows;$i++)
		  {
			if (array_key_exists("chk".$i,$_POST))
			   {
					$ls_numanacot = $_POST["txtnumanacot".$i];
					//print "tipo:".$ls_numanacot;
				 	if($_POST["txttipanacot".$i] == "Bienes")
					 	$ls_tipsolcot = "B";
					else
						$ls_tipsolcot = "S";		 	
					$ls_obsana = $_POST["txtobsanacot".$i];
					//print "cotizacion".$ls_numanacot." tipo:".$ls_tipsolcot;
					$la_ganadores=$this->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
					$li_totalganadores=count($la_ganadores);
					//print "totales:".count($la_ganadores);
					$ls_numordcom="";
					for($li_i=0;$li_i<$li_totalganadores;$li_i++)
					{
						
						$ls_proveedor		= $la_ganadores[$li_i]["cod_pro"];
						$ls_cotizacion		= $la_ganadores[$li_i]["numcot"]; //numero de cotizacion
						$ls_tipo_proveedor	= $la_ganadores[$li_i]["tipconpro"];
						// busca el concepto en la cotizacion //
						$ls_concepto1		= $la_ganadores[$li_i]["consolcot"]; //concepto de solicitud
						$this->uf_select_items($ls_cotizacion,$ls_proveedor,$ls_numanacot,$ls_tipsolcot,$la_items,$li_totrow);
						$this->uf_select_items_cotizacion($ls_cotizacion,$ls_proveedor,$ls_numanacot,$ls_tipsolcot,$la_items_cotizacion,$li_totrow_cotizacion);
						$this->uf_calcular_montos($li_totrow_cotizacion,$la_items_cotizacion,$la_totales,$ls_tipo_proveedor);
						$this->uf_viene_de_sep($ls_cotizacion,$ls_proveedor, $lb_viene_sep);
						$li_subtotal=$la_totales["subtotal"];
						$li_totaliva=$la_totales["totaliva"];
						$li_total=$la_totales["total"];	
						if ($ls_tipsolcot=='B')
						   {
						     $ls_camini = 'numordcom';
						   }
						elseif($ls_tipsolcot=='S')
						   {
						     $ls_camini = 'numordser';
						   }
						//print "numero: ".$ls_numorden;
						if ($ls_numorden=='')
						{
							$ls_numordcom=$this->io_keygen->uf_generar_numero_nuevo('SOC','soc_ordencompra','numordcom','SOCANA',15,$ls_camini,"estcondat",$ls_tipsolcot);
						}
						else
						{
							$ls_numordcom=$ls_numorden;
						}
						$ls_numsolaux = $ls_numordcom;
						$lb_valido=$this->uf_select_solicitud($ls_numanacot,$ls_concepto,$ls_unidad,$ls_uniejeaso);
						if($lb_valido)
						{
							$lb_valido = $this->uf_select_unidades_ejecutoras($ls_numanacot, $lb_viene_sep,$la_items, $li_totrow,$la_unidades,$ls_concepto,$ls_unidad);
							if($lb_valido)
							{
			//print "concepto: ".$ls_concepto1;
								//$lb_valido=$this->uf_insert_orden_compra($ls_proveedor,$li_total,$li_totaliva, $li_subtotal,$aa_seguridad,$ls_tipsolcot,$ls_numanacot,$ls_obsana,$ld_fecha,$ls_concepto,$ls_unidad,$ls_uniejeaso,&$ls_numordcom);
							$lb_valido=$this->uf_insert_orden_compra($ls_proveedor,$li_total,$li_totaliva, $li_subtotal,$aa_seguridad,$ls_tipsolcot,$ls_numanacot,$ls_obsana,$ld_fecha,$ls_concepto1,$ls_unidad,$ls_uniejeaso,&$ls_numordcom);
								if($lb_valido)
								{
									if($lb_viene_sep)
										$lb_valido=$this->uf_insert_enlace_sep($ls_numordcom,$ls_tipsolcot,0,$la_unidades,$aa_seguridad);								
									if($lb_valido)
									{			 	
										if($ls_tipsolcot=="B")
										{
											$lb_valido=$this->uf_insert_bienes($ls_numordcom,$aa_seguridad,$ls_proveedor,$ls_cotizacion,$la_items_cotizacion,$li_totrow_cotizacion);
										}
										elseif($ls_tipsolcot=="S")
										{
											$lb_valido=$this->uf_insert_servicios($ls_numordcom,$aa_seguridad,$ls_proveedor,$ls_cotizacion,$la_items_cotizacion,$li_totrow_cotizacion);
										}
										if($lb_valido && $ls_tipafeiva=='P')//Si la afectacion del Iva es Presupuestaria.
										{
											$lb_valido=$this->uf_insert_cuentas_presupuestarias($ls_numordcom,$ls_tipsolcot,$la_items,$li_totrow,$la_items_cotizacion,$li_totrow_cotizacion,$aa_seguridad,$ls_tipo_proveedor,$ls_cotizacion,$lb_viene_sep,$ls_proveedor);											
										}
										if($lb_valido && $ls_tipo_proveedor!="F") // si el proveedor es de tipo formal no se le calculan los cargos
										{
											$lb_valido=$this->uf_insert_cuentas_cargos($ls_numordcom,$ls_tipsolcot,$la_items,$li_totrow,$aa_seguridad,$lb_viene_sep);
										}
										if($lb_valido)
										{
											$ls_estcom=0;
											$lb_valido=$this->uf_validar_cuentas($ls_numordcom,$ls_estcom,$ls_tipsolcot);
										}
										if(!$lb_valido)
										{
											break;
										}
									}								
								}
							}
						}
					}
					
				 if (!$lb_valido)
					{
					  break;
					}
			   }
		  }
	   if ($lb_valido)
		  {
		  	if($ls_numsolaux!=$ls_numordcom)
			{
				$this->io_mensajes->message("Se Asigno el Numero a la Orden de Compra: ".$ls_numordcom);
			}
			$this->io_sql->commit();
			$this->io_mensajes->message("Operación realizada con Éxito !!!");
		    $this->io_sql->close();
		  }
	   else 
		  {
			$this->io_sql->rollback();
			$this->io_mensajes->message("Error Operación !!!");
		    $this->io_sql->close();
		  }
	}// end function uf_guardar
    //---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion_analisis($as_numanacot, $ls_tipanacot,&$as_numanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion_analisis
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 14/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;	
		if($ls_tipanacot == "B")
			$ls_tabla = "soc_dtac_bienes ";
		elseif($ls_tipanacot == "S")	
			$ls_tabla = "soc_dtac_servicios ";
		$ls_sql= "SELECT cxa.numcot, cxa.cod_pro, p.tipconpro,s.consolcot
				    FROM soc_cotxanalisis cxa, rpc_proveedor p, soc_sol_cotizacion s,soc_cotizacion r 
				   WHERE cxa.codemp='$this->ls_codemp' 
				     AND cxa.numanacot='$as_numanacot' 
				     AND cxa.codemp=p.codemp AND  cxa.cod_pro = p.cod_pro
				     AND r.numsolcot=s.numsolcot AND cxa.numcot=r.numcot
				     AND cxa.numcot IN (SELECT numcot FROM $ls_tabla WHERE codemp='$this->ls_codemp')";
		// NUM COT, COD_PROVEEDOR Y CONCEPTO DEL ANALISIS //
	/*	$ls_sql= "SELECT cxa.numcot, cxa.cod_pro, p.tipconpro, s.consolcot
				    FROM soc_cotxanalisis cxa, rpc_proveedor p, soc_sol_cotizacion s, ".$ls_tabla.
				"   WHERE cxa.codemp='$this->ls_codemp' 
				     AND cxa.numanacot='$as_numanacot' 
				     AND cxa.codemp=p.codemp AND  cxa.cod_pro = p.cod_pro".
				    // AND s.numsolcot=cxa.numcot
				 "    AND s.numsolcot =a.numcot AND a.codemp='$this->ls_codemp"."'";
				     //IN (SELECT numcot FROM $ls_tabla WHERE codemp='$this->ls_codemp')";	*/
		//print $ls_sql;	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cotizacion_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_proveedores[$li_i]=$row;					
				$li_i++;
			}																
		}
		return $aa_proveedores;
	}//fin de uf_select_cotizacion_analisis
	//---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------	
    function uf_insert_orden_compra($as_codpro,$ai_total,$ai_totaliva, $ai_subtotal,$aa_seguridad,$as_tipsolcot,$as_numanacot,$as_observacion,$ad_fecha,$as_concepto,$ls_unidad,$as_uniejeaso,&$ls_numordcom)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_orden_compra
	//	    Arguments: as_codpro  --->   Codigo del proveedor al cual se le esta haciendo la orden de compra
	//	      Returns: devuelve true si se inserto correctamente la cabecera de la orden de compra o false en caso contrario
	//	  Description: Funcion que que se encarga dde insertar una orden de compra
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 20/06/2015 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$ls_fecordcom=$this->io_funciones->uf_convertirdatetobd($ad_fecha);	
	if($as_tipsolcot=="B")  
	{		
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_ordencompra','numordcom','SOCANA',15,"","estcondat","B",$ls_numordcom);
		$ld_monsubtotbie=$ai_subtotal;
		$ld_monsubtotser=0;
	}
	else
	{
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_ordencompra','numordcom','SOCANA',15,"","estcondat","S",$ls_numordcom);
		$ld_monsubtotbie=0;
		$ld_monsubtotser=$ai_subtotal;
	}
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
		$ls_fecent = "1900-01-01";
		$as_rb_rblugcom = 0;
		$as_codmon='---';
		$as_codfuefin='--';
		$as_estcom=1; // MODIIFICADA tenia 0 y nos establece la orden en registro //
		$as_diaplacom=0;
		$as_codtipmod="--";
		
		$as_coduniadm=$ls_unidad;
		
		$ai_estsegcom=0;   	
		$ad_porsegcom=0;
		$ad_monsegcom=0;
		$as_forpag="-";
		$as_concom="-";
		$as_conordcom=$as_concepto;
		$ld_mondes=0;
		$as_codpai="---";
		$as_codest="---";
		$as_codmun="---";
		$as_codpar="---";
		$as_lugentnomdep="";
		$as_lugentdir="";
		$ad_antpag=0;
		$ad_tascamordcom=0;
		$ad_montotdiv=0;
		$as_obscom="";
		
		
		
		$ls_sql=" INSERT INTO soc_ordencompra (codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, ".
		        "                              fecordcom, estsegcom, porsegcom, monsegcom, forpagcom, estcom, diaplacom, ".
				"							   concom, obscom, monsubtotbie, monsubtotser, monsubtot, monbasimp, monimp, ".
				"							   mondes, montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, ".
				"							   lugentdir, monant, estlugcom, tascamordcom, montotdiv, estapro, fecaprord, ".
				"                              codusuapr, numpolcon, coduniadm, obsordcom, fecent,numanacot,codtipmod,uniejeaso) ".
				" VALUES ('".$this->ls_codemp."','".$ls_numordcom."','".$as_tipsolcot."','".$as_codpro."','".$as_codmon."', ".
				"         '".$as_codfuefin."','".$ls_fecordcom."','".$ai_estsegcom."',".$ad_porsegcom.",".
				"         '".$ad_monsegcom."','".$as_forpag."','".$as_estcom."','".$as_diaplacom."','".$as_concom."', ".
				"         '".$as_conordcom."',".$ld_monsubtotbie.",".$ld_monsubtotser.",".$ai_subtotal.",".$ld_monbasimp.", ".
				"         ".$ai_totaliva.",".$ld_mondes.",".$ai_total.",".$li_estpenalm.",'".$as_codpai."', ".
				"         '".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_lugentnomdep."','".$as_lugentdir."', ".
				"         ".$ad_antpag.",".$as_rb_rblugcom.",".$ad_tascamordcom.",".$ad_montotdiv.",".$li_estapro.", ".
				"         '".$ld_fecaprord."','".$ls_codusuapr."','".$ls_numpolcon."','".$as_coduniadm."','".$as_obscom."', ".
				"         '".$ls_fecent."','".$as_numanacot."','$as_codtipmod','".$as_uniejeaso."')";        
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_sql->rollback();
		    if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
				$this->uf_insert_orden_compra($as_codpro,$ai_total,$ai_totaliva, $ai_subtotal,$aa_seguridad,$as_tipsolcot,$as_numanacot,$as_observacion,$ad_fecha,$as_concepto,$ls_unidad,$as_uniejeaso,&$ls_numordcom);
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}			
						
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Orden de Compra ".$ls_numordcom." tipo ".$as_tipsolcot." de fecha".$ls_fecordcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	    }
	}
		return $lb_valido;
	}// fin uf_insert_orden_compra
	//---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------
	function  uf_select_items($as_numcot,$as_codpro,$as_numanacot,$as_tipsolcot,&$aa_items,&$li_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 10/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$aa_items=array();
		$lb_valido=false;
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dts.canart as cantidad, dt.preuniart as precio, dt.moniva,dt.montotart as monto,
					        d.obsanacot, d.numcot, d.cod_pro, dts.numsep, dts.codcar
					   FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt, soc_dtsc_bienes dts , soc_cotizacion c					  
					  WHERE d.codemp='$this->ls_codemp' 
					    AND d.numanacot='$as_numanacot' 
						AND dt.cod_pro='$as_codpro' 
						AND dt.numcot='$as_numcot' 				    
						AND d.codemp=a.codemp 
						AND a.codemp=p.codemp 
						AND p.codemp=dt.codemp
						AND dt.codemp=dts.codemp
						AND dts.codemp=c.codemp											
						AND dt.cod_pro=dts.cod_pro
						AND dt.codart=dts.codart											
						AND dt.cod_pro=c.cod_pro
						AND dt.numcot=c.numcot								
						AND c.numsolcot=dts.numsolcot												 
						AND	d.codart=a.codart 
						AND d.cod_pro=p.cod_pro 
						AND d.numcot=dt.numcot 
						AND d.cod_pro=dt.cod_pro 
						AND d.codart=dt.codart";				
		}
		else
		{
				
				$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dts.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					        d.obsanacot, d.numcot, d.cod_pro, dts.numsep, dts.codcar
					   FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt, soc_dtsc_servicios dts , soc_cotizacion c					  
					  WHERE d.codemp='$this->ls_codemp' 
					    AND d.numanacot='$as_numanacot' 
						AND dt.cod_pro='$as_codpro' 
						AND dt.numcot='$as_numcot' 				    
						AND d.codemp=a.codemp 
						AND a.codemp=p.codemp 
						AND p.codemp=dt.codemp
						AND dt.codemp=dts.codemp
						AND dts.codemp=c.codemp											
						AND dt.cod_pro=dts.cod_pro
						AND dt.codser=dts.codser											
						AND dt.cod_pro=c.cod_pro
						AND dt.numcot=c.numcot								
						AND c.numsolcot=dts.numsolcot												 
						AND	d.codser=a.codser
						AND d.cod_pro=p.cod_pro 
						AND d.numcot=dt.numcot 
						AND d.cod_pro=dt.cod_pro 
						AND d.codser=dt.codser";	
				
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		}
		return $aa_items;
	}
	//--------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_calcular_montos($ai_totrow,$aa_items,&$aa_totales,$as_tipo_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_montos
		//		   Access: public
		//		  return :	arreglo  montos totalizados
		//	  Description: Metodo que  devuelve arreglo  montos totalizados
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 09/08/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$li_subtotal=0;
		 	$li_totaliva=0;
		 	$li_total=0;
		 	$aa_totales=array();

			for($li_j=1;$li_j<=$ai_totrow;$li_j++)
		 	{
				$li_subtotal+=(($aa_items[$li_j]["precio"]) * ($aa_items[$li_j]["cantidad"]));
				if($as_tipo_proveedor != "F") //En caso de que el roveedor sea formal no se le calculan los cargos
					$li_totaliva+=$aa_items[$li_j]["moniva"];	
			}
			$li_total=$li_totaliva+$li_subtotal;		 
			$aa_totales["subtotal"]=$li_subtotal;
			$aa_totales["totaliva"]=$li_totaliva;
			$aa_totales["total"]=$li_total;
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numordcom,$aa_seguridad,$as_codpro,$ls_numcot,$aa_items_cotizacion,$ai_totrow_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//				   as_numanacot--->numero de analisis de cotizacion
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/06/2015 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
			
		for($li_i=1;($li_i<=$ai_totrow_cotizacion)&&($lb_valido);$li_i++)
		{
			$ls_codart    = $aa_items_cotizacion[$li_i]["codigo"];
			$ls_denart    = $aa_items_cotizacion[$li_i]["denominacion"];
			$li_canart    = $aa_items_cotizacion[$li_i]["cantidad"];
			$ld_preuniart = $aa_items_cotizacion[$li_i]["precio"];
			$ld_monsubart = ($aa_items_cotizacion[$li_i]["precio"]) * ($aa_items_cotizacion[$li_i]["cantidad"]);
			$ld_montotart = $aa_items_cotizacion[$li_i]["monto"];
			$ld_monimp    = $aa_items_cotizacion[$li_i]["moniva"];
			$la_data      = $this->uf_select_bienes_servicios($ls_codart,"B",$as_codpro,$ls_numcot);
			$ls_unidad    = $la_data["unidad"];	
			$ls_numsep    = trim($aa_items_cotizacion[$li_i]["numsep"]);
			if (empty($ls_numsep))
			   {
			     $ls_numsep = '---------------';
			   }
			
	        $ls_sql=" INSERT INTO soc_dt_bienes (codemp, numordcom, estcondat, codart, unidad, canart, ".
			        "							 penart, preuniart, monsubart, montotart,orden,numsol)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','B', ".
					"          '".$ls_codart."','".$ls_unidad."',".$li_canart.",0, ".
					"           ".$ld_preuniart.",".$ld_monsubart.",".$ld_montotart.",'".$li_i."','".$ls_numsep."')";                                                                       
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->Generar Ordenes - Analisis MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				print $this->io_sql->message;
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Articulo ".$ls_codart." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    }
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,"B",$aa_seguridad,$ls_codart,$ld_monsubart,$ld_monimp,$ld_montotart);
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numordcom,$aa_seguridad,$as_codpro,$ls_numcot,$aa_items_cotizacion,$ai_totrow_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//				   $as_numanacot--->numero de analisis de cotizacion
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/06/2015 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for ($li_i=1;($li_i<=$ai_totrow_cotizacion)&&($lb_valido);$li_i++)
		    {
			  $ls_codser    = $aa_items_cotizacion[$li_i]["codigo"];
			  $ls_denser    = $aa_items_cotizacion[$li_i]["denominacion"];
			  $ld_canser    = $aa_items_cotizacion[$li_i]["cantidad"];
			  $ld_preuniser = $aa_items_cotizacion[$li_i]["precio"];
			  $ld_monsubser = ($aa_items_cotizacion[$li_i]["precio"]) * ($aa_items_cotizacion[$li_i]["cantidad"]);
			  $ld_montotser = $aa_items_cotizacion[$li_i]["monto"];
			  $ld_monimp    = $aa_items_cotizacion[$li_i]["moniva"];
			  $ls_numsep    = trim($aa_items_cotizacion[$li_i]["numsep"]);
			  if (empty($ls_numsep))
			     {
			       $ls_numsep = '---------------';
			     }
			
	          $ls_sql=" INSERT INTO soc_dt_servicio (codemp, numordcom, estcondat, codser, canser, ".
			          "							 monuniser, monsubser, montotser, orden,numsol)".
                      "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','S', ".
					  "          '".$ls_codser."',".$ld_canser.", ".
					  "           ".$ld_preuniser.",".$ld_monsubser.",".$ld_montotser.",'".$li_i."','".$ls_numsep."')";
			  $li_row=$this->io_sql->execute($ls_sql);
			  if($li_row===false)
			  {
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->Generar Orden - Analisis MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			  }
			  else
			  {
			    if ($lb_valido)
				   {
					 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					 $ls_evento="INSERT";
					 $ls_descripcion ="Insertó el servicio ".$ls_codser." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			       }
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,"S",$aa_seguridad,$ls_codser,$ld_monsubser,$ld_monimp,$ld_montotser);
			  }
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$aa_items,$li_totrow,$aa_items_cotizacion,$ai_totrow_cotizacion,$aa_seguridad,$as_tipo_proveedor,$as_numcot,$ab_vienesep,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_presupuestarias
		//		   Access: private
		//	    Arguments: as_numordcom  ---> Número de la orden de compra 
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   as_items  	---> items de la orden de compra
		//				   aa_seguridad  ---> arreglo de las variables de seguridad
		//				   aa_numcot------>numero de cotizacion
		//				   ab_vienesep--->booleano que indica si la solicitud viene de sep o no
		//				   as_codpro----> codigo del proveedor
		//				   aa_items_cotizacion--->items sumarizados en la cotizacion
		//				   ai_totrow_cotizacion--->cantidad de elementos en el arreglo anterior
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por:  Ing. Miguel Palencia
		// Fecha Creación: 27/01/2016 								Fecha Última Modificación : 27/01/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_dscuentas->data=array();		
		for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
		    {
			  $ls_codart  = $aa_items[$li_i]["codigo"];
			  $ls_numsep  = $aa_items[$li_i]["numsep"];
			  $ls_codcar  = $aa_items[$li_i]["codcar"];
			  //print "articulo:".$ls_codart."cargo:".$ls_codcar;
			  $la_cuentas = $this->uf_select_cuentas_presupuestarias($as_numcot,$ls_codart,$ls_numsep,$as_estcondat,$ab_vienesep,$as_codpro);
			  $li_totrows = count($la_cuentas);
			  for ($li_j=0;$li_j<$li_totrows;$li_j++)
			      {
				    $ls_codestpro = trim($la_cuentas[$li_j]["programatica"]);
				    $ls_cuenta    = trim($la_cuentas[$li_j]["spg_cuenta"]);
				    $li_moncue    = ($aa_items[$li_i]["precio"]) * ($aa_items[$li_i]["cantidad"]);
				    $this->io_dscuentas->insertRow("codestpro1",substr($ls_codestpro,0,20));
				    $this->io_dscuentas->insertRow("codestpro2",substr($ls_codestpro,20,6));
				    $this->io_dscuentas->insertRow("codestpro3",substr($ls_codestpro,26,3));
				    $this->io_dscuentas->insertRow("codestpro4",substr($ls_codestpro,29,2));
				    $this->io_dscuentas->insertRow("codestpro5",substr($ls_codestpro,31,2));
				    $this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
				    $this->io_dscuentas->insertRow("moncue",$li_moncue);
				    $this->io_dscuentas->insertRow("coditem",$ls_codart);
			      }			
		    }
		//Por cada item se guarda su respectiva cuenta de cargo
		if ($as_tipo_proveedor!="F")// En caso de que el proveedor sea tipo formal, no se le calculan los cargos
		   {
		     for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			     {
				   $ls_codart=$aa_items[$li_i]["codigo"];
				   $ls_numsep=$aa_items[$li_i]["numsep"];
				   $ls_codcar  = $aa_items[$li_i]["codcar"];
				   if ($ab_vienesep)
					$la_cargos=$this->uf_select_cargos_sep($ls_codart,$ls_numsep,$as_estcondat);
				   else
					//print "aqui cambio";
					//print "codart:".$ls_codart." as_estcondat:".$as_estcondat;
					//$la_cargos=$this->uf_select_cargos_2016($ls_codart,$as_estcondat);
					//print "iva: ".$ls_codcar;
					if(trim(strlen($ls_codcar))>0) //||$as_estcondat=="S") añadio el servicio asociado al cargo en la solicitud
					{
						$la_cargos=$this->uf_select_cargos_2016($ls_codart,$as_numordcom,$as_estcondat);
				   		//print "ARTICULO:".$ls_codart."cuantos iva ".count($la_cargos);
				   		if (count($la_cargos)>0)
				      	{
							$ls_codestpro = trim($la_cargos["codestpro"]);
							$ls_cuenta    = trim($la_cargos["spg_cuenta"]);
							$ld_monto     = $aa_items[$li_i]["cantidad"] * $aa_items[$li_i]["precio"];
							$ls_formula   = str_replace('$LD_MONTO',$ld_monto,$la_cargos["formula"]);
							//print "ls_codestpro".$ls_codestpro;
							//print "monto".$ld_monto;
							
							eval('$li_moncue ='.$ls_formula.";");
							$this->io_dscuentas->insertRow("codestpro1",substr($ls_codestpro,0,20));
							$this->io_dscuentas->insertRow("codestpro2",substr($ls_codestpro,20,6));
							$this->io_dscuentas->insertRow("codestpro3",substr($ls_codestpro,26,3));
							$this->io_dscuentas->insertRow("codestpro4",substr($ls_codestpro,29,2));
							$this->io_dscuentas->insertRow("codestpro5",substr($ls_codestpro,31,2));
							$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
							$this->io_dscuentas->insertRow("moncue",$li_moncue);	
							$this->io_dscuentas->insertRow("coditem",$as_codart);		
				      	}
				    }  	
			     }
		   }
		// INSERTA LAS CUENTAS PRESUPUESTARIAS DE GASTO DE ACUERDO A LOS ITEM //
		if (count($this->io_dscuentas->data)>0)
		   {
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
				   $ls_cuenta     = $this->io_dscuentas->getValue('cuenta',$li_fila);
				   $li_moncue     = $this->io_dscuentas->getValue('moncue',$li_fila);
				   $ls_codestpro1 = $this->io_dscuentas->getValue('codestpro1',$li_fila);
				   $ls_codestpro2 = $this->io_dscuentas->getValue('codestpro2',$li_fila);
				   $ls_codestpro3 = $this->io_dscuentas->getValue('codestpro3',$li_fila);
				   $ls_codestpro4 = $this->io_dscuentas->getValue('codestpro4',$li_fila);
				   $ls_codestpro5 = $this->io_dscuentas->getValue('codestpro5',$li_fila);				
			 	   $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
				   
				   $ls_sql = "INSERT INTO soc_cuentagasto (codemp,numordcom,estcondat,codestpro1,codestpro2,codestpro3,
				                                           codestpro4,codestpro5,spg_cuenta, monto)
							  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codestpro1."',
							          '".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',
									  '".$ls_cuenta."',".$li_moncue.")";       
				//print $ls_sql;
				   $li_row = $this->io_sql->execute($ls_sql);
				   if ($li_row===false)
					  {
					    $lb_valido=false;
						$this->io_mensajes->message("CLASE->tepuy_soc_c_generar_orden_analisis.php;MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					  }
				   else
					  {
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codestpro." a la orden de compra ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					  }
			     }
		   }
		return $lb_valido;
	}// end function uf_insert_cuentas_presupuestarias
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentas_presupuestarias($as_numcot,$as_coditem,$as_numsep,$ls_tipsolcot,$ab_vienesep,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentas_presupuestarias
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con las cuentas de gasto asociadas a un item
		//	  Description: Metodo que  retorna  las cuentas de gasto asociadas a un item
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 23/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cuentas=array();
		$lb_valido=false;
		if($ab_vienesep)//Si viene de una sep
		{
			if($ls_tipsolcot=="B")
			{
				$ls_sql="SELECT a.spg_cuenta, a.codestpro1, a.codestpro2, a.codestpro3, a.codestpro4, a.codestpro5
						   FROM sep_dt_articulos a, soc_solcotsep s, soc_cotizacion c
						  WHERE 
						a.codemp = '$this->ls_codemp' AND
						s.codemp = '$this->ls_codemp' AND
						c.codemp = '$this->ls_codemp' AND
						
						c.numcot = '$as_numcot' AND
						c.cod_pro = '$as_codpro' AND
						a.codart = '$as_coditem' AND
						s.numsol = '$as_numsep' AND 
						
						c.numsolcot = s.numsolcot AND
						s.numsol = a.numsol";
			}
			else
			{
				$ls_sql="SELECT a.spg_cuenta, a.codestpro1, a.codestpro2, a.codestpro3, a.codestpro4, a.codestpro5
						FROM sep_dt_servicio a, soc_solcotsep s, soc_cotizacion c
						WHERE 
						a.codemp = '$this->ls_codemp' AND
						s.codemp = '$this->ls_codemp' AND
						c.codemp = '$this->ls_codemp' AND
						
						c.numcot = '$as_numcot' AND
						c.cod_pro = '$as_codpro' AND
						a.codser = '$as_coditem' AND
						s.numsol = '$as_numsep' AND 
						
						c.numsolcot = s.numsolcot AND
						s.numsol = a.numsol";
			}
		}
		else//Si no viene de una sep
		{
			
			if($ls_tipsolcot=="B")
			{
				$ls_sql="SELECT a.spg_cuenta, s.codestpro1, s.codestpro2, s.codestpro3, s.codestpro4, s.codestpro5
						FROM siv_articulo a, spg_unidadadministrativa s,soc_sol_cotizacion p, soc_cotizacion c
						WHERE 
						a.codemp = '$this->ls_codemp' AND
						s.codemp = '$this->ls_codemp' AND
						p.codemp = '$this->ls_codemp' AND
						c.codemp = '$this->ls_codemp' AND
						
						c.numcot = '$as_numcot' AND
						c.cod_pro= '$as_codpro' AND
						a.codart = '$as_coditem' AND
						
						c.numsolcot = p.numsolcot AND
						p.coduniadm = s.coduniadm";
			}
			else
			{
				$ls_sql="SELECT a.spg_cuenta, s.codestpro1, s.codestpro2, s.codestpro3, s.codestpro4, s.codestpro5
						FROM soc_servicios a, spg_unidadadministrativa s,soc_sol_cotizacion p, soc_cotizacion c
						WHERE 
						a.codemp = '$this->ls_codemp' AND
						s.codemp = '$this->ls_codemp' AND
						p.codemp = '$this->ls_codemp' AND
						c.codemp = '$this->ls_codemp' AND
						
						c.numcot = '$as_numcot' AND
						c.cod_pro= '$as_codpro' AND
						a.codser = '$as_coditem' AND
						
						c.numsolcot = p.numsolcot AND
						p.coduniadm = s.coduniadm";
			}
		}
		//print $ls_sql;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cuentas_presupuestarias".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			//print $this->io_sql->message;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cuentas[$li_i]["spg_cuenta"]=$row["spg_cuenta"];				
				$la_cuentas[$li_i]["programatica"]=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_i++;
			}			
		}
		
		return $la_cuentas;	
	}//fin de uf_select_cuentas_presupuestarias
    //---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$aa_items,$li_totrow,$aa_seguridad,$ab_vienesep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowcuentascargo  ---> filas del grid cuentas cargos
		//				   ai_totrowcargos  ---> filas del grid de los creditos
		//				   aa_seguridad  ---> variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: este metodo inserta la cuentas de los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Juniors Fraga, Ing Miguel Palencia
		// Fecha Creación: 24/06/2015 								Fecha Última Modificación : 25/04/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido    = true;
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];
		$this->io_dscargos->data=array();
		//print "orden: ".$as_numordcom;
		//print "estcondat: ".$as_estcondat;
		//print "item: ".count($aa_items);
		//print "totrow: ".$li_totrow;
		//print "largo $ab_viesesep: ".strlen($ab_vienesep);
		if (strlen($ab_vienesep)==0){$ab_vienesep=false;}
		for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
		    {
			  $ls_codart=$aa_items[$li_i]["codigo"];
			  $ls_numsep=$aa_items[$li_i]["numsep"];
			//print "codart: ".$ls_codart;
			//print "numsep: ".$ls_numsep;
			  if ($ab_vienesep)
			     {
				   $la_cargos=$this->uf_select_cargos_sep($ls_codart,$ls_numsep,$as_estcondat);
				 }
			  else
			     {
				//$as_coditem,$ad_numordcom,$ls_tipsolcot)
				   $la_cargos=$this->uf_select_cargos_2016($ls_codart,$as_numordcom,$as_estcondat);
				 }
			 // print "cargos: ".count($la_cargos);
				 $ls_codcar  = $la_cargos["codcar"];
			  if (count($la_cargos)&&strlen($ls_codcar!=0))
			     {
				   //$ls_codcar  = $la_cargos["codcar"];
				   $ld_bascar  = ($aa_items[$li_i]["precio"]) * ($aa_items[$li_i]["cantidad"]);
				   $ld_monto   = $aa_items[$li_i]["cantidad"] * $aa_items[$li_i]["precio"];
				   $ls_formula = str_replace('$LD_MONTO',$ld_monto,$la_cargos["formula"]);
				   eval('$ld_moncar ='.$ls_formula.";");
				   $ls_formulacargo=$la_cargos["formula"];		
				   $ls_codestpro  = $la_cargos["codestpro"];
				   $ls_spg_cuenta = trim($la_cargos["spg_cuenta"]);
				   $this->io_dscargos->insertRow("codcar",$ls_codcar);	
				   $this->io_dscargos->insertRow("monobjret",$ld_bascar);	
			 	   $this->io_dscargos->insertRow("monret",$ld_moncar);	
				   $this->io_dscargos->insertRow("formula",$ls_formulacargo);				   
				   $this->io_dscargos->insertRow("codestpro1",substr($ls_codestpro,0,20));
				   $this->io_dscargos->insertRow("codestpro2",substr($ls_codestpro,20,6));
				   $this->io_dscargos->insertRow("codestpro3",substr($ls_codestpro,26,3));
				   $this->io_dscargos->insertRow("codestpro4",substr($ls_codestpro,29,2));
				   $this->io_dscargos->insertRow("codestpro5",substr($ls_codestpro,31,2));
				   $this->io_dscargos->insertRow("spg_cuenta",$ls_spg_cuenta);
					//print "cat".$ls_codestpro."cargo: ".$ls_codcar." bascar:".$ld_bascar." monto: ".$ld_monto."!!!!";
			     }
		    }
		//print "que paso";
		$arr_group[0]="codestpro1";
		$arr_group[1]="codestpro2";
		$arr_group[2]="codestpro3";
		$arr_group[3]="codestpro4";
		$arr_group[4]="codestpro5";
		$arr_group[5]="spg_cuenta";
		$arr_group[6]="codcar";
		$this->io_dscargos->group_by($arr_group,array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$li_totrow=$this->io_dscargos->getRowCount("codcar");
		if ($ls_tipafeiva=='P')
		   {
			 for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			     {
				   $ls_codcargo   = $this->io_dscargos->getValue("codcar",$li_i);
				   if(strlen($ls_codcargo)==0)
				   {	
				   		//print "no tiene iva";
				   		return $lb_valido;
				   }
				   $ls_spg_cuenta = trim($this->io_dscargos->getValue("spg_cuenta",$li_i));
				   $ld_moncuecar  = $this->io_dscargos->getValue("monret",$li_i);
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_i);
				   $ld_monret	  = $this->io_dscargos->getValue("monret",$li_i);
				   $ls_formula	  = $this->io_dscargos->getValue("formula",$li_i);		
				   $ls_codestpro1 = $this->io_dscargos->getValue("codestpro1",$li_i);
				   $ls_codestpro2 = $this->io_dscargos->getValue("codestpro2",$li_i);
				   $ls_codestpro3 = $this->io_dscargos->getValue("codestpro3",$li_i);
				   $ls_codestpro4 = $this->io_dscargos->getValue("codestpro4",$li_i);
				   $ls_codestpro5 = $this->io_dscargos->getValue("codestpro5",$li_i);
				   $ls_sc_cuenta  = "";
				   $lb_valido     = $this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
				                                                    $ls_codestpro5,$ls_spg_cuenta,&$ls_sc_cuenta);
														   
				   if ($lb_valido)
				      {
					    $ls_sql = "INSERT INTO soc_solicitudcargos (codemp, numordcom,  estcondat, codcar, monobjret, monret, 
						                                            codestpro1,codestpro2, codestpro3, codestpro4, codestpro5,
																	spg_cuenta, sc_cuenta,formula, monto)
								   VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',
							               ".$ld_monobjret.",".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."',
										   '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_spg_cuenta."',
										   '".$ls_sc_cuenta."','".$ls_formula."',".$ld_moncuecar.")";
					    $li_row=$this->io_sql->execute($ls_sql);
					    if ($li_row===false)
					       {
						     $lb_valido=false;
						     $this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				 	       }
					    else
					       {
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
							 $ls_evento="INSERT";
							 $ls_descripcion ="Insertó la Cuenta ".$ls_spg_cuenta." de programatica ".$ls_codestpro." al cargos ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
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
		     for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			     {
				   $ls_codcargo   = $this->io_dscargos->getValue("codcar",$li_i);
				   $ls_codctascg  = $this->io_dscargos->getValue("spg_cuenta",$li_i);
				   $ld_moncuecar  = $this->io_dscargos->getValue("monret",$li_i);
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_i);
				   $ld_monret	  = $this->io_dscargos->getValue("monret",$li_i);
				   $ls_formula	  = $this->io_dscargos->getValue("formula",$li_i);
				   $ls_codestpro1 = '--------------------';
				   $ls_codestpro2 = '------';
				   $ls_codestpro3 = '---';
				   $ls_codestpro4 = '--';
				   $ls_codestpro5 = '--';
				   
		           $ls_sql        = "INSERT INTO soc_solicitudcargos (codemp,numordcom,estcondat,codcar,monobjret,monret,
				                                                      codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,
																	  spg_cuenta, sc_cuenta,formula, monto)
					    			 VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',
							    			 ".$ld_monobjret.",".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."',
							    			 '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_codctascg."',
							    			 '".$ls_codctascg."','".$ls_formula."',".$ld_moncuecar.")";

				   $rs_data = $this->io_sql->execute($ls_sql);
				   if ($rs_data===false)
				      {
					    $lb_valido = false;
						$this->io_mensajes->message("CLASE->tepuy_soc_c_generar_orden_analisis.php(Iva Contable);MÉTODO->uf_insert_cuentas_cargos (Iva Contable);ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					  } 
				   else
				      {
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						 $ls_evento="INSERT";
						 $ls_descripcion ="Insertó la Cuenta Contable ".$ls_codctascg." al cargo ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " de tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
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
	function uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numordcom ---> mumero de la orden de compra
		//				   as_estcom  ---> estatus de la orden de compra
		//                 as_estcondat ---> tipo de la orden de compra bienes o servicios
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creación: 17/03/2015								Fecha Última Modificación : 12/05/2015
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
			$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
				$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}			
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos_2016($as_coditem,$ad_numordcom,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos_2016
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con los cargos asociados al item
		//	  Description: Metodo que  retorna los cargos asociados al item
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 22/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if ($ls_tipsolcot=="B")
		   {				
			 $ls_sql = "SELECT MAX(s.codart)as codart, MAX(s.codcar) as codcar, MAX(c.formula) as formula,MAX(c.codestpro) as codestpro, MAX(c.spg_cuenta) as spg_cuenta 
					      FROM soc_dtsc_bienes s, soc_analisicotizacion a,tepuy_cargos c
					     WHERE s.codemp='$this->ls_codemp'
						   AND s.codart='$as_coditem'
						   AND s.codemp=c.codemp
						   AND s.numsolcot=a.numsolcot
					       AND s.codcar=c.codcar";
			//print $ls_sql;
		   }
		else
		   {
			 $ls_sql= "SELECT MAX(s.codser) as codser, MAX(s.codcar) as codcar, MAX(c.formula) as formula ,MAX(c.codestpro) as codestpro, MAX(c.spg_cuenta) as spg_cuenta ".
					//"     FROM soc_serviciocargo s, tepuy_cargos c
					"     FROM soc_dtsc_servicios s, soc_analisicotizacion a, tepuy_cargos c
					    WHERE s.codemp='$this->ls_codemp'
						  AND s.codser='$as_coditem'
						  AND s.codemp=c.codemp
						   AND s.numsolcot=a.numsolcot
					      AND s.codcar=c.codcar";			
		   }

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cargos_2016".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			//print "pasaron:".count($rs_data);
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cargos=$row;				
			}			
		}
		return $la_cargos;	
	}//fin de uf_select_cargos_2016
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos($as_coditem,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con los cargos asociados al item
		//	  Description: Metodo que  retorna los cargos asociados al item
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 22/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if ($ls_tipsolcot=="B")
		   {				
			 $ls_sql = "SELECT s.codart, s.codcar, c.formula,c.codestpro, c.spg_cuenta 
					      FROM siv_cargosarticulo s, tepuy_cargos c
					     WHERE s.codemp='$this->ls_codemp'
						   AND s.codart='$as_coditem'
						   AND s.codemp=c.codemp
					       AND s.codcar=c.codcar";
		   }
		else
		   {
			 $ls_sql= "SELECT s.codser, s.codcar, c.formula ,c.codestpro, c.spg_cuenta 
					     FROM soc_serviciocargo s, tepuy_cargos c
					    WHERE s.codemp='$this->ls_codemp'
						  AND s.codser='$as_coditem'
						  AND s.codemp=c.codemp
					      AND s.codcar=c.codcar";			
		   }
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cargos".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cargos=$row;				
			}			
		}
		return $la_cargos;	
	}//fin de uf_select_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos_sep($as_coditem,$as_numsep,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos_sep
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//				   $as_numsep--->numero de la sep a la cual esta asociada el item
		//				   $ls_tipsolcot--->Si es de bienes o de servicio
		//		return	 : arreglo con los cargos asociados al item, si la solicitud esta asociada a una sep
		//	  Description: Metodo que  retorna los cargos asociados al item, si la solicitud esta asociada a una sep
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 22/06/2015								Fecha Última Modificación : 13/11/2015
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if($ls_tipsolcot=="B")
		{				
			$ls_sql="SELECT dta.formula, dta.codcar, sc.codestpro1,sc.codestpro2,sc.codestpro3,sc.codestpro4,sc.codestpro5, sc.spg_cuenta
					FROM sep_dta_cargos dta, sep_solicitudcargos sc
					WHERE dta.codemp = '$this->ls_codemp' AND
						  dta.codart = '$as_coditem' AND
						  dta.numsol = '$as_numsep' AND
						  dta.codemp = sc.codemp AND
						  dta.numsol = sc.numsol AND
						  dta.codcar = sc.codcar";	
		}
		else
		{
			$ls_sql= "SELECT dta.formula, dta.codcar, sc.codestpro1,sc.codestpro2,sc.codestpro3,sc.codestpro4,sc.codestpro5, sc.spg_cuenta
					FROM sep_dts_cargos dta, sep_solicitudcargos sc
					WHERE dta.codemp = '$this->ls_codemp' AND
						  dta.codser = '$as_coditem' AND
						  dta.numsol = '$as_numsep' AND
						  dta.codemp = sc.codemp AND
						  dta.numsol = sc.numsol AND
						  dta.codcar = sc.codcar";			
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cargos_sep".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cargos["codestpro"]  = $row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];				
				$la_cargos["formula"]    = $row["formula"];
				$la_cargos["spg_cuenta"] = trim($row["spg_cuenta"]);
				$la_cargos["codcar"]     = $row["codcar"];
			}			
		}
		return $la_cargos;	
	}//fin de uf_select_cargos_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro2  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro3  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro4  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro5  --->  Còdigo de Estructura Programàtica
		//	    		   as_spgcuenta   --->  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2015 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_sccuenta="";
		$ls_sql="SELECT TRIM(sc_cuenta) as sc_cuenta ".
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
			$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
    //---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_bienes_servicios($as_coditem,$as_tipo,$as_codpro,$as_numcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_bienes_servicios
		//		   Access: public
		//		  return :	arreglo que contiene algunos datos basicos que faltan se los bienes/servicios
		//	  Description: Metodo que  devuelve algunos datos basicos que faltan se los bienes/servicios
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 21/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_datos=array();
		$lb_valido=false;
		if($as_tipo=="B")
		{
			$ls_sql= "SELECT a.spg_cuenta, d.unidad 
						FROM siv_articulo a, soc_dtcot_bienes d
						WHERE a.codemp='$this->ls_codemp' AND a.codemp=d.codemp
						AND a.codart='$as_coditem' AND d.cod_pro='$as_codpro' AND
						d.numcot='$as_numcot' AND a.codart=d.codart";				
		}
		else
		{
			$ls_sql= "SELECT a.spg_cuenta
						FROM soc_servicios a, soc_dtcot_servicio d
						WHERE a.codemp='$this->ls_codemp' AND a.codemp=d.codemp
						AND a.codser='$as_coditem' AND d.cod_pro='$as_codpro' AND
						d.numcot='$as_numcot' AND a.codser=d.codser";	
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_bienes_servicios".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_datos["spg_cuenta"]=$row["spg_cuenta"];	
				
				if(array_key_exists("unidad",$row))
					$aa_datos["unidad"]=$row["unidad"];					
			}																
		}
		return $aa_datos;
	}//fin de uf_select_cotizacion_analisis
    //---------------------------------------------------------------------------------------------------------------------------------------		
    
	//---------------------------------------------------------------------------------------------------------------------------------------
	function  uf_select_items_cotizacion($as_numcot,$as_codpro,$as_numanacot,$as_tipsolcot,&$aa_items,&$li_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis, de manera combinada en caso de que
		//					los items se repitan 
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis, de manera combinada en caso de que
		//					los items se repitan 
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 10/06/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$aa_items=array();
		$lb_valido=false;
		/*if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dt.canart as cantidad, dt.preuniart as precio, dt.moniva,dt.montotart as monto,
					        d.obsanacot, d.numcot, d.cod_pro
					   FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt 
					  WHERE d.codemp='$this->ls_codemp' 
					    AND d.numanacot='$as_numanacot' 
						AND dt.cod_pro='$as_codpro' 
						AND dt.numcot='$as_numcot' 
					    AND d.codemp=a.codemp 
						AND a.codemp=p.codemp 
						AND p.codemp=dt.codemp 
						AND	d.codart=a.codart 
						AND d.cod_pro=p.cod_pro 
						AND d.numcot=dt.numcot 
						AND d.cod_pro=dt.cod_pro 
						AND d.codart=dt.codart";				
		}
		else
		{
				$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dt.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					            d.obsanacot, d.numcot, d.cod_pro
					       FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt
				 	      WHERE d.codemp='$this->ls_codemp' 
						    AND d.numanacot='$as_numanacot' 
							AND d.codemp=a.codemp 
							AND dt.cod_pro='$as_codpro' 
							AND dt.numcot='$as_numcot' 
							AND a.codemp=p.codemp 
							AND p.codemp=dt.codemp 
							AND d.codser=a.codser 
							AND d.cod_pro=p.cod_pro 
							AND d.numcot=dt.numcot 
							AND d.cod_pro=dt.cod_pro 
							AND d.codser=dt.codser";				
		}*/
		
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT DISTINCT d.codart as codigo, a.denart as denominacion, p.nompro, dt.canart as cantidad, 
							dt.preuniart as precio, dt.moniva, dt.montotart as monto, d.obsanacot, d.numcot, d.cod_pro,
							soc_dtsc_bienes.numsep       
				       FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt, soc_sol_cotizacion, soc_dtsc_bienes, soc_cotizacion
					  WHERE d.codemp='".$this->ls_codemp."' 
					    AND d.numanacot='".$as_numanacot."' 
						AND dt.cod_pro='".$as_codpro."' 
						AND dt.numcot='".$as_numcot."' 
					    AND soc_cotizacion.codemp=soc_sol_cotizacion.codemp    
					    AND soc_cotizacion.numsolcot=soc_sol_cotizacion.numsolcot 
					    AND soc_sol_cotizacion.codemp=soc_dtsc_bienes.codemp
					    AND soc_sol_cotizacion.numsolcot=soc_dtsc_bienes.numsolcot
					    AND soc_dtsc_bienes.codemp=dt.codemp
					    AND soc_dtsc_bienes.codart=dt.codart
					    AND soc_dtsc_bienes.codemp=d.codemp
					    AND soc_dtsc_bienes.codart=d.codart  
					    AND d.codemp=soc_cotizacion.codemp
					    AND d.numcot=soc_cotizacion.numcot
					    AND d.codemp=a.codemp 
					    AND a.codemp=p.codemp 
					    AND p.codemp=dt.codemp 
					    AND d.codart=a.codart 
					    AND d.cod_pro=p.cod_pro 
					    AND d.numcot=dt.numcot 
					    AND d.cod_pro=dt.cod_pro 
					    AND d.codart=dt.codart";				
		}
		else
		{
				$ls_sql="SELECT DISTINCT d.codser as codigo, a.denser as denominacion, p.nompro, dt.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					            d.obsanacot, d.numcot, d.cod_pro, soc_dtsc_servicios.numsep
					       FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt, soc_sol_cotizacion, soc_dtsc_servicios, soc_cotizacion
				 	      WHERE d.codemp='".$this->ls_codemp."' 
						    AND d.numanacot='".$as_numanacot."'
							AND dt.cod_pro='".$as_codpro."'
							AND dt.numcot='".$as_numcot."' 
							AND soc_cotizacion.codemp=soc_sol_cotizacion.codemp    
							AND soc_cotizacion.numsolcot=soc_sol_cotizacion.numsolcot 
							AND soc_sol_cotizacion.codemp=soc_dtsc_servicios.codemp
							AND soc_sol_cotizacion.numsolcot=soc_dtsc_servicios.numsolcot
							AND soc_dtsc_servicios.codemp=dt.codemp
							AND soc_dtsc_servicios.codser=dt.codser
							AND soc_dtsc_servicios.codemp=d.codemp
							AND soc_dtsc_servicios.codser=d.codser  
							AND d.codemp=soc_cotizacion.codemp
							AND d.numcot=soc_cotizacion.numcot
							AND d.codemp=a.codemp 
							AND a.codemp=p.codemp 
							AND p.codemp=dt.codemp 
							AND d.codser=a.codser 
							AND d.cod_pro=p.cod_pro 
							AND d.numcot=dt.numcot 
							AND d.cod_pro=dt.cod_pro 
							AND d.codser=dt.codser";				
		}
		//print $ls_sql;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		}
		return $aa_items;
	}
	//--------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numordcom,$as_estcondat,$aa_seguridad,$as_coditem,$ad_monbasimp,$as_monimp,$as_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la orden de compra		
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los cargos correctamente o false en caso contrario
		//	  Description: Funcion que inserta los cargos de una Orden de Compra en la tabla segun el tipo de la orden 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por. Miguel Palencia 
		// Fecha Creación: 17/03/2015 								Fecha Última Modificación : 12/05/2015
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=$this->uf_select_cargos_2016($as_coditem,$as_numordcom,$as_estcondat);
		$lb_valido=true;
		//print "cargos: ".count($la_cargos);
		if(count($la_cargos)>0)
			{
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
			$ls_codcar=$la_cargos["codcar"];
			$ls_formulacargo=$la_cargos["formula"];	
			// EVALUA QUE EL ITEM DE LA ORDEN LLEVE I.V.A. //
			if(strlen(trim($ls_codcar))>0)
			{
				//print "iva: ".$ls_codcar." formula: ".$ls_formulacargo;
				$ls_sql="INSERT INTO ".$ls_tabla." (codemp, numordcom, estcondat, ".$ls_campo.", codcar, monbasimp, monimp, monto, formula)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$as_coditem."','".$ls_codcar."',".
					" 			  ".$ad_monbasimp.",".$as_monimp.",".$as_monto.",'".$ls_formulacargo."')";        
				//print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Cargo ".$ls_codcar." a la Orden de Compra ".$as_numordcom. "Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
	
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numanacot,&$as_concepto,&$as_unidad,&$as_uniejeaso)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: public
		//		  return : variable con el concepto de la colicitud de cotizacion y la unidad ejecutora
		//	  Description: Metodo que  devuelve el concepto de la colicitud de cotizacion y la unidad ejecutora
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 31/10/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_concepto = array();
		$lb_valido   = true;
		$ls_sql = "SELECT s.uniejeaso , s.coduniadm, s.consolcot
			         FROM soc_sol_cotizacion s, soc_analisicotizacion a
			        WHERE s.codemp = '$this->ls_codemp'
				      AND a.numanacot = '$as_numanacot'
				      AND a.codemp = s.codemp
				      AND a.numsolcot = s.numsolcot";
	
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_solicitud".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_concepto  = $row["consolcot"];	
				$as_uniejeaso = $row["uniejeaso"];
				$as_unidad    = $row["coduniadm"];
			}																
		}		
		return $lb_valido;
	}//fin de uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidades_ejecutoras($as_numanacot, $ab_viene_sep,$aa_items, $ai_totrow,&$aa_unidades, &$as_concepto,&$as_unidad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidades_ejecutoras
		//		   Access: public
		//			Param: as_numanacot---->numero del analisis de cotizacion
		//				   ab_viene_sep---->variable que indica si la solicitud posee sep asociadas.
		//				   aa_items---->arreglo con los items, es usado en caso de q la variable anterior venga en true
		//				   ai_totrow--->cantidad de items
		//		  return :	arreglo con la(s) unidad(es) ejecutora(s), una variable con el concepto de la colicitud de cotizacion
		//					y una variable con la unidad ejecutora a ser guardada en la cabecera de la orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 30/10/2015								Fecha Última Modificación : 11/11/2015
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_unidades = array();
		$lb_valido=true;
		$as_concepto="";
		
		if($ab_viene_sep)//Si la solicitud de cotizacion tiene asociada al menos una sep
		{
			$la_sep = array();
			//Se obtienen las sep a las cuales estan asociados los items que formaran parte de la orden de compra
			for($li_i=1; $li_i<=$ai_totrow; $li_i++)
			{
				$la_sep[$li_i] = $aa_items[$li_i]["numsep"];
			}
						
			$la_sep = array_unique($la_sep);//se eliminan los repetidos	
			sort($la_sep);//se reordena la matriz
			$li_j=0;
			for($li_i=0; $li_i<count($la_sep); $li_i++)
			{
				$ls_sep=$la_sep[$li_i];
				$ls_sql = "SELECT s.numsol, s.codunieje, u.denuniadm
							 FROM soc_solcotsep s, soc_analisicotizacion a, spg_unidadadministrativa u
							WHERE s.codemp = '$this->ls_codemp'
							  AND a.numanacot = '$as_numanacot'
							  AND s.numsol = '$ls_sep'
							  AND a.codemp = s.codemp
							  AND s.codemp = u.codemp
							  AND a.numsolcot = s.numsolcot
							  AND s.codunieje = u.coduniadm";		
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_mensajes->message("ERROR->uf_select_unidades_ejecutoras 1".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;	
				}
				else
				{				
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$aa_unidades[$li_j]=$row;	
						$as_concepto = $as_concepto."Nro. SEP:".$row["numsol"].".Unidad Ejecutora:".$row["codunieje"]." - ".$row["denuniadm"].";  ";	
						$li_j++;
					}																
				}
			}
			if(count($aa_unidades)==1)
			{
				$as_unidad = $aa_unidades[0]["codunieje"];
				$as_concepto="";
			}				
			else
			{
				$as_unidad = "----------";
			}
		}
		else//En caso de que la solicitud no este asociada a alguna sep, se busca la unidad ejecutora de la solicitud
		{
			$ls_sql = "SELECT c.coduniadm
						FROM soc_analisicotizacion a, soc_sol_cotizacion c
						WHERE a.codemp = '$this->ls_codemp'
						AND a.numanacot = '$as_numanacot'
						AND a.codemp = c.codemp
						AND a.numsolcot = c.numsolcot";
			$rs_data=$this->io_sql->select($ls_sql);					
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_select_unidades_ejecutoras 2".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{				
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$as_unidad = $row["coduniadm"];
				}																
			}			
		}		
		return $lb_valido;
	}//fin de uf_select_unidades_ejecutoras
	//---------------------------------------------------------------------------------------------------------------------------------------	

	//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$aa_unidades,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_enlace_sep
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowbienes  ---> total de filas de bienes
		//                 as_estcom   ---> estatus de la orden de compra 
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Modificado por: Ing. Miguel Palencia
		// Fecha Creación: 12/05/2015 								Fecha Última Modificación : 30/10/2015
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total = count($aa_unidades);
		for($li_fila=0;$li_fila<$li_total;$li_fila++)
		{
			$ls_numsol    = $aa_unidades[$li_fila]["numsol"];
			$ls_coduniadm = $aa_unidades[$li_fila]["codunieje"];
			$ls_sql=" INSERT INTO soc_enlace_sep (codemp, numordcom, estcondat, numsol, estordcom, coduniadm)".
					"  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."', ".
					"          '".$ls_numsol."',".$as_estcom.",'".$ls_coduniadm."')";                                                                       
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Generar Orden Analisis MÉTODO->uf_insert_enlace_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el enlace de la sep ".$ls_numsol." a la Orden de Compra  ".$as_numordcom." tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
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
	function uf_viene_de_sep($as_numcot,$as_codpro, &$ab_viene_sep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_viene_de_sep
		//		   Access: public
		//		  return :	variable que indica si la solicitud esta o no asociada a una sep
		//	  Description: Metodo que indica si la solicitud esta o no asociada a una sep
		//	   Creado Por: Ing. Miguel Palencia
		// 			Fecha: 11/11/2015								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ab_viene_sep = false;
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql = "SELECT s.numsol
					FROM soc_solcotsep s, soc_cotizacion c
					WHERE 
					s.codemp = '$this->ls_codemp' AND
					c.codemp = '$this->ls_codemp' AND
					c.numcot = '$as_numcot' AND
					c.cod_pro = '$as_codpro' AND
					s.numsolcot = c.numsolcot";
	
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_viene_de_sep".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{				
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ab_viene_sep = true;
				}																
			}
		}	
		return $lb_valido;
	}//fin de uf_viene_de_sep
}
?>
