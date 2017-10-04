<?php
    session_start();   
	ini_set('memory_limit','256M');
	ini_set('max_execution_time ','0');
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "spg_mayor_analitico.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
		require_once("tepuy_spg_reporte.php");
		$io_report = new tepuy_spg_reporte();
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
    //--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min=="")&&($ls_codestpro4_min=="")&&
			   ($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max=="")&&($ls_codestpro4h_max=="")&&
			   ($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}	
		
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
        $fecdes=$_GET["txtfecdes"];
	    $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
        $fechas=$_GET["txtfechas"];
	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_orden=$_GET["rborden"];
		
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Se genero el Reporte Mayor Analitico desde la fecha ".$fecdes." hasta ".$fechas." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPG","tepuy_spg_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ldt_fecha="Desde   ".$fecdes."   al   ".$fechas."";
		$ls_titulo="MAYOR ANALITICO"; 
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_select_mayor_analitico($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                              $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																  $ls_codestpro4h,$ls_codestpro5h,$ldt_fecdes,$ldt_fechas,
																  $ls_cuentades,$ls_cuentahas,$ls_orden);
 		$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
		$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
		$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
		$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
		$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
		
		$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
		$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
		$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
		$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
		$lo_titulo->set_size('9');
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,13);
		$lo_hoja->set_column(5,7,30);
		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 3, $ldt_fecha,$lo_encabezado);
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
		for($z=1;$z<=$li_tot;$z++)
		{
			$ld_total_asignado=0;
			$ld_total_aumento=0;
			$ld_total_disminucion=0;
			$ld_total_monto_actualizado=0;
			$ld_total_compromiso=0;
			$ld_total_precompromiso=0;
			$ld_total_compromiso=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_por_paga=0;
            $ld_total_saldo_comprometer=0;
			$ls_programatica=$io_report->dts_cab->data["programatica"][$z];
		    $ls_codestpro1=substr($ls_programatica,0,20);
		    $ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
		
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,20,6);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,26,3);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,29,2);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
				  $ls_denestpro4=$ls_denestpro4;
				}
				$ls_codestpro5=substr($ls_programatica,31,2);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
				  $ls_denestpro5=$ls_denestpro5;
				}
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
			    $ls_programatica=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
			}
			else
			{
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
			    $ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
			}
			$lb_valido=$io_report->uf_spg_reporte_mayor_analitico($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
			                                                      $ls_codestpro5,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																  $ls_codestpro4,$ls_codestpro5,$ldt_fecdes,$ldt_fechas,
																  $ls_cuentades,$ls_cuentahas,$ls_orden);
			if($lb_valido)
			{
		      $li_row=2;
			  $li_row=$li_row+1;
			  $lo_hoja->write($li_row, 0, "Programatica",$lo_titulo);
			  $lo_hoja->write($li_row, 1, $ls_programatica,$lo_datacenter);
			  $lo_hoja->write($li_row, 2, $ls_denestpro,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
              $io_report->dts_reporte->group_noorder("spg_cuenta");
			  $li_totrow_det=$io_report->dts_reporte->getRowCount("spg_cuenta");
  	  		  $ls_cuenta_ant="";
			  for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			  {
				  $li_tmp=($li_s+1);
				  $ls_programatica=$io_report->dts_reporte->data["programatica"][$li_s];
				  $ls_nombre_prog=$io_report->dts_reporte->data["nombre_prog"][$li_s];
				  $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$li_s];
				  if ($i<$li_tot)
				  {
						$ls_spg_cuenta_next=trim($io_report->dts_reporte->data["spg_cuenta"][$li_tmp]);
				  }
				  elseif($i=$li_tot)
				  {
						$ls_spg_cuenta_next='no_next';
				  }
				  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
				  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
				  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
				  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
				  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
				  $ls_ben=$io_report->dts_reporte->data["ben_nombre"][$li_s];
				  $ls_pro=$io_report->dts_reporte->data["nompro"][$li_s];
				// AQUI DEBO ESTABLECER SI ES BENEFICIARIO O PROVEEDOR Y CLAVAR EL VALOR //
				$blanco="Beneficiario Nulo";
				$blanco1="Ninguno";
				if($blanco=rtrim($ls_ben) && $blanco1!=rtrim($ls_pro)) {$ls_beneficiario=$ls_pro;}else{$ls_beneficiario=$ls_ben;}
				//////////////////////////////////////////////////////////////////////////
				  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
				  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
				  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
				  $ld_asignado=$io_report->dts_reporte->data["asignado"][$li_s];
				  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
				  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
				  $ld_monto_actualizado=$io_report->dts_reporte->data["monto_actualizado"][$li_s];
				  $ld_precompromiso=$io_report->dts_reporte->data["precompromiso"][$li_s];
				  $ld_compromiso=$io_report->dts_reporte->data["compromiso"][$li_s];
				  $ld_causado=$io_report->dts_reporte->data["causado"][$li_s];
				  $ld_pagado=$io_report->dts_reporte->data["pagado"][$li_s];
				 
				  $ld_por_paga=$ld_causado-$ld_pagado;
				  $ld_total_asignado=$ld_total_asignado+$ld_asignado;
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
				  $ld_por_comprometer=$ld_monto_actualizado-$ld_precompromiso-$ld_compromiso;
				  
				  if($ls_cuenta_ant=="")
			      {
					$li_row=$li_row+1;
	 				$ls_cuenta_ant=$ls_spg_cuenta;
					$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
					$lo_hoja->write($li_row, 1, $ls_cuenta_ant,$lo_datacenter);
					$lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
					$li_row=$li_row+1;
				        $lo_hoja->write($li_row, 0, "Fecha",$lo_titulo);
					$lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
					$lo_hoja->write($li_row, 2, "Documento",$lo_titulo);
					$lo_hoja->write($li_row, 3, "Beneficiario",$lo_titulo);
					$lo_hoja->write($li_row, 4, "Detalle",$lo_titulo);
					$lo_hoja->write($li_row, 5, "Asignado",$lo_titulo);
					$lo_hoja->write($li_row, 6, "Aumento",$lo_titulo);
					$lo_hoja->write($li_row, 7, "Disminucion",$lo_titulo);
					$lo_hoja->write($li_row, 8, "Monto Actualizado",$lo_titulo);
					$lo_hoja->write($li_row, 9, "Pre Comprometido",$lo_titulo);
					$lo_hoja->write($li_row, 10, "Comprometido",$lo_titulo);
					$lo_hoja->write($li_row, 11, "Causado",$lo_titulo);
					$lo_hoja->write($li_row, 12, "Pagado",$lo_titulo);
					$lo_hoja->write($li_row, 13, "Por Pagar",$lo_titulo);
				  }
				  $li_row=$li_row+1;
				  $lo_hoja->write($li_row, 0, $ls_fecha,$lo_datacenter);
				  $lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
				  $lo_hoja->write($li_row, 2, $ls_documento,$lo_dataleft);
				  $lo_hoja->write($li_row, 3, $ls_beneficiario,$lo_dataleft);
				  $lo_hoja->write($li_row, 4, $ls_descripcion." ",$lo_dataleft);
				  $lo_hoja->write($li_row, 5, $ld_asignado,$lo_dataright);
				  $lo_hoja->write($li_row, 6, $ld_aumento,$lo_dataright);
				  $lo_hoja->write($li_row, 7, $ld_disminucion,$lo_dataright);
				  $lo_hoja->write($li_row, 8, $ld_monto_actualizado,$lo_dataright);
				  $lo_hoja->write($li_row, 9, $ld_precompromiso,$lo_dataright);
				  $lo_hoja->write($li_row, 10, $ld_compromiso,$lo_dataright);
				  $lo_hoja->write($li_row, 11, $ld_causado,$lo_dataright);
				  $lo_hoja->write($li_row, 12, $ld_pagado,$lo_dataright);
				  $lo_hoja->write($li_row, 13, $ld_por_paga,$lo_dataright);
				  
				  if ((!empty($ls_spg_cuenta_next)) or ($li_s==$li_totrow_det))
				  {
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 2, "Saldo Por Comprometer",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
					$lo_hoja->write($li_row, 4, $ld_total_saldo_comprometer,$lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_total_asignado,$lo_dataright);
					$lo_hoja->write($li_row, 6, $ld_total_aumento,$lo_dataright);
					$lo_hoja->write($li_row, 7, $ld_total_disminucion,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_total_precompromiso,$lo_dataright);
					$lo_hoja->write($li_row, 10, $ld_total_compromiso,$lo_dataright);
					$lo_hoja->write($li_row, 11, $ld_total_causado,$lo_dataright);
					$lo_hoja->write($li_row, 12, $ld_total_pagado,$lo_dataright);
					$lo_hoja->write($li_row, 13, $ld_total_por_paga,$lo_dataright);
					$ls_cuenta_next="";
					$ls_cuenta_ant="";
				  }//if
			 }//for
          }//if
		}//for
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"spg_mayor_analitico.xls\"");
		header("Content-Disposition: inline; filename=\"spg_mayor_analitico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}//else
?> 
