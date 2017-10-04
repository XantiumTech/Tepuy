<?php
    session_start();   
	ini_set('memory_limit','1204M');
	ini_set('max_execution_time ','0');
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "spi_mayor_analitico.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
		require_once("tepuy_spi_reporte.php");
		$io_report = new tepuy_spi_reporte();
		require_once("tepuy_spi_funciones_reportes.php");
		$io_function_report = new tepuy_spi_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
    //--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spi_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestarias');"); 
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
		   if($io_function_report->uf_spi_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestarias');"); 
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
				
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Se genero el Reporte Mayor Analitico desde la fecha ".$fecdes." hasta ".$fechas." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPI","tepuy_spi_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ldt_fecha="Desde   ".$fecdes."   al   ".$fechas."";
		$ls_titulo="MAYOR ANALITICO"; 
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
 	 
	 $lb_valido=$io_report->uf_spi_reporte_mayor_analitico($ldt_fecdes,$ldt_fechas,$ls_cuentades,$ls_cuentahas,$ls_orden);
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
		$io_report->dts_reporte->group_noorder("spi_cuenta");
		$li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
		for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		{
		  $li_tmp=($li_s+1); // Iniciamos la transacción
		  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  
		  if ($li_s<$li_totrow_det)
		  {
				$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp];  
		  }
		  elseif($li_s==$li_totrow_det)
		  {
				$ls_spi_cuenta_next='no_next'; 
		  }
		  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  if($li_totrow_det==1)
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  
		  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
		  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
		  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
		  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
		  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
		  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
		  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
		  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
		  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
		  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
		  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
		  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
		  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
		  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
		  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
		  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
		  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
		  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
		  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
          $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
		  $ld_monto_actualizado_aux=$ld_monto_actualizado;
		  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
		  {
		      $ld_monto_actualizado=0;
		  }
		  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
		  
		  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
		  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
		  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
		  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
		  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
		  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
		  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
		  
				  
		  if (!empty($ls_spi_cuenta))
		  {
					 $li_row=$li_row+1;
	 				 $ls_cuenta_act=$ls_spi_cuenta;
					 $lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
					 $lo_hoja->write($li_row, 1, $ls_cuenta_act,$lo_datacenter);
					 $lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
					 $li_row=$li_row+1; 
				     $lo_hoja->write($li_row, 0, "Fecha",$lo_titulo);
					 $lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
					 $lo_hoja->write($li_row, 2, "Documento",$lo_titulo);
					 $lo_hoja->write($li_row, 3, "Detalle",$lo_titulo);
					 $lo_hoja->write($li_row, 4, "Previsto",$lo_titulo);
					 $lo_hoja->write($li_row, 5, "Aumento",$lo_titulo);
					 $lo_hoja->write($li_row, 6, "Disminucion",$lo_titulo);
					 $lo_hoja->write($li_row, 7, "Monto Actualizado",$lo_titulo);
					 $lo_hoja->write($li_row, 8, "Devengado",$lo_titulo);
					 $lo_hoja->write($li_row, 9, "Cobrado",$lo_titulo);
					 $lo_hoja->write($li_row, 10, "Cobrado Anticipado",$lo_titulo);
					 $lo_hoja->write($li_row, 11, "Por Cobrar",$lo_titulo);
					 
					 $ld_sub_total_previsto=0;
		             $ld_sub_total_aumento=0;
		             $ld_sub_total_disminucion=0;
		             $ld_sub_total_devengado=0;
		             $ld_sub_total_cobrado=0;
		             $ld_sub_total_cobrado_anticipado=0;
		             $ld_sub_total_monto_actualizado=0;
		             $ld_sub_total_por_cobrar=0;
					 //print "filas --->>".$li_row."<br>";
		 }
		  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
		  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
		  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
		  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
		  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
		  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
		  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
				 
				 
				 
				  $li_row=$li_row+1; //print "entro al detalle"."<br>"; 
				  $lo_hoja->write($li_row, 0, $ls_fecha,$lo_datacenter); 
				  $lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
				  $lo_hoja->write($li_row, 2, $ls_documento,$lo_dataleft);
				  $lo_hoja->write($li_row, 3, $ls_descripcion." ",$lo_dataleft);
				  $lo_hoja->write($li_row, 4, $ld_previsto,$lo_dataright);
				  $lo_hoja->write($li_row, 5, $ld_aumento,$lo_dataright);
				  $lo_hoja->write($li_row, 6, $ld_disminucion,$lo_dataright);
				  $lo_hoja->write($li_row, 7, $ld_monto_actualizado,$lo_dataright);
				  $lo_hoja->write($li_row, 8, $ld_devengado,$lo_dataright);
				  $lo_hoja->write($li_row, 9, $ld_cobrado,$lo_dataright);
				  $lo_hoja->write($li_row, 10, $ld_cobrado_anticipado,$lo_dataright);
				  $lo_hoja->write($li_row, 11, $ld_por_cobrar,$lo_dataright);
		 if (!empty($ls_spi_cuenta_next))
		 {
		  	$li_row=$li_row+1;
			$lo_hoja->write($li_row, 2, "SALDO POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			$lo_hoja->write($li_row, 3, $ld_sub_total_monto_actualizado,$lo_dataright);
			$lo_hoja->write($li_row, 4, $ld_sub_total_previsto,$lo_dataright);
			$lo_hoja->write($li_row, 5, $ld_sub_total_aumento,$lo_dataright);
			$lo_hoja->write($li_row, 6, $ld_sub_total_disminucion,$lo_dataright);
			$lo_hoja->write($li_row, 7, $ld_sub_total_monto_actualizado,$lo_dataright);
			$lo_hoja->write($li_row, 8, $ld_sub_total_devengado,$lo_dataright);
			$lo_hoja->write($li_row, 9, $ld_sub_total_cobrado,$lo_dataright);
			$lo_hoja->write($li_row, 10,$ld_sub_total_cobrado_anticipado,$lo_dataright);
			$lo_hoja->write($li_row, 11,$ld_sub_total_cobrado_anticipado,$lo_dataright);
			$ls_cuenta_next="";
			$ls_cuenta_ant="";
		 }  
				  				 
    	 if($li_s==$li_totrow_det)
		 {
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 2, "TOTAL POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
					$lo_hoja->write($li_row, 3, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_total_previsto,$lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_total_aumento,$lo_dataright);
					$lo_hoja->write($li_row, 6, $ld_total_disminucion,$lo_dataright);
					$lo_hoja->write($li_row, 7, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ld_total_devengado,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_total_cobrado,$lo_dataright);
					$lo_hoja->write($li_row, 10,$ld_total_cobrado_anticipado,$lo_dataright);
					$lo_hoja->write($li_row, 11,$ld_total_por_cobrar,$lo_dataright);
					$ls_cuenta_next="";
					$ls_cuenta_ant="";	  	  
		 }//if 
		}//for
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"spi_mayor_analitico.xls\"");
		header("Content-Disposition: inline; filename=\"spi_mayor_analitico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}//else
?> 