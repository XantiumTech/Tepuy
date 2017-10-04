<?PHP
    session_start();   
/*	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}*/
	ini_set('memory_limit','512M');
	ini_set('display_errors', 1);
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_titulo2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 04/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo." ".$as_titulo2;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","tepuy_snorh_r_retencion_ari.php",$ls_descripcion);
		return $lb_valido;
	}
	//-------------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "Relacion_Ingresos.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_snorh_class_report.php");
	$io_report=new tepuy_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="Comprobante de Asignaciones AR-I";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$li_total=$io_fun_nomina->uf_obtenervalor_get("total","0");
	for($li_i=1;$li_i<=$li_total;$li_i++)
	{
		$la_nominas[$li_i]=$io_fun_nomina->uf_obtenervalor_get("codnom".$li_i,"");
	}
	$ls_codperdes      = $io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas      = $io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_conceptoaporte=$io_fun_nomina->uf_obtenervalor_get("conceptoaporte","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_titulo1="Periodo 01/01/".$ls_ano." al 31/12/".$ls_ano;
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	$ls_institucion=$_SESSION["la_empresa"]["nombre"];
	$ls_direccion=$_SESSION["la_empresa"]["direccion"];
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_snorh_class_reportbsf.php");
		$io_report=new tepuy_snorh_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=true;
	//$lb_valido=uf_insert_seguridad($ls_titulo,$ls_titulo2); // Seguridad de Reporte
	//if($lb_valido)
	//{
		$lb_valido=$io_report->uf_retencionari_personal($la_nominas,$li_total,$ls_ano,$ls_orden,$ls_codperdes,$ls_codperhas); // Cargar el DS con los datos del reporte
	//}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{

		//-------formato para el reporte----------------------------------------------------------
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
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
		
		$lo_dataright2= &$lo_libro->addformat(array(num_format => '#,##'));
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('9');	
		$lo_hoja->set_column(0,0,10);
		$lo_hoja->set_column(1,2,60);	
		$lo_hoja->set_column(2,3,20);
		$lo_hoja->set_column(3,4,50);
		$lo_hoja->write(0,1,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,1,$ls_titulo1,$lo_encabezado);
		$lo_hoja->write(3,1,"DATOS DE LA INSTITUCIÓN",$lo_encabezado);
		$lo_hoja->write(3,2,$ls_institucion,$lo_encabezado);

		$lo_hoja->write(4,1,"DIRECCIÓN",$lo_encabezado);
		$lo_hoja->write(4,2,$ls_direccion,$lo_encabezado);

		$lo_hoja->write(5,1,"RIF",$lo_encabezado);
		$lo_hoja->write(5,2,$_SESSION["la_empresa"]["rifemp"],$lo_encabezado);
		
		//------------------------------------------------------------------------------------------------------
		$li_totrow=$io_report->DS->getRowCount("codper");
		//print "Total: ".$li_totrow; die();
		$fila=5;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$fila=$fila+2;
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];

			$lo_hoja->write($fila,1,"BENEFICIARIO DE LAS REMUNERACIONES",$lo_encabezado);
			$fila=$fila+1;
			$lo_hoja->write($fila,1,"Apellidos y Nombres: ",$lo_encabezado);
			$lo_hoja->write($fila,2,$ls_cedper." ".$ls_nomper,$lo_encabezado);
			$lb_valido=$io_report->uf_asignacionesari_meses($ls_codper,$la_nominas,$li_total,$ls_ano); // Obtenemos el detalle del reporte
			$fila=$fila+1;
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("periodo");
				//print "Total: ".$li_totrow_det;die();
				$lo_hoja->write($fila,0, "Período",$lo_titulo);	
				$lo_hoja->write($fila,1, "Remuneraciones Pagadas o Abonas en Cuenta",$lo_titulo);
				$lo_hoja->write($fila,2, "Código de la Nómina",$lo_titulo);	
				$lo_hoja->write($fila,3, "Denominación de la Nómina",$lo_titulo);

				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$fila=$fila+1;
					$ls_periodo=$io_report->DS_detalle->data["periodo"][$li_s];
					$ls_asignaciones=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["asignaciones"][$li_s]));
					$ls_codnom=$io_report->DS_detalle->data["nomina"][$li_s];
					$ls_dennom=$io_report->DS_detalle->data["denominacion"][$li_s];
					//$li_porisr=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["porisr"][$li_s]);
					//$li_porisr=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["porisr"][$li_s]*100);
					//$li_isr = ($io_report->DS_detalle->data["islr"][$li_s] * $io_report->DS_detalle->data["porisr"][$li_s])/100;
					$li_ariacum=$li_ariacum+abs($io_report->DS_detalle->data["asignaciones"][$li_s]);
					$li_ari=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["asignaciones"][$li_s]));
					$li_ari_acumulado=$io_fun_nomina->uf_formatonumerico($li_ariacum);
					$li_ariacum=$li_ariacum+$li_ari_acumulado;
					//$ls_mes=strtoupper(substr($io_fecha->uf_load_nombre_mes($ls_codisr),0,3));
					if($li_ari<>"0,00")
					{
						$lb_ari=true;
					}
					//print "Periodo: ".$ls_periodo." Asignaciones: ".$ls_asignaciones;die();
					$lo_hoja->write($fila, 0, $ls_periodo, $lo_datacenter);
				 	$lo_hoja->write($fila, 1, $ls_asignaciones, $lo_dataright);
				 	$lo_hoja->write($fila, 2, $ls_codnom, $lo_datacenter);
				 	$lo_hoja->write($fila, 3, $ls_dennom, $lo_datacenter);
				}
				$fila=$fila+1;
				$li_ariacum=number_format($li_ariacum,2,",", ".");
				$lo_hoja->write($fila,0,"Total Bs.",$lo_encabezado);
				$lo_hoja->write($fila,1,$li_ariacum,$lo_dataright);
				$fila=$fila+1;
				if(($lb_valido)&&($lb_ari))// Si no ocurrio ningún error
				{
					if($ls_conceptoaporte=="1") // Si solicita que se muestren los conceptos de aporte
					{
						$lb_valido=$io_report->uf_retencionarc_aporte($ls_codper,$la_nominas,$li_total,$ls_ano);
						$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
						$fila=$fila+2;
						$lo_hoja->write($fila,1,"RESUMEN DE RETENCIONES",$lo_encabezado);
						$fila=$fila+1;
						$lo_hoja->write($fila, 0,'Código', $lo_titulo);
						$lo_hoja->write($fila, 1,'Denominación', $lo_titulo);
						$lo_hoja->write($fila, 2,"Monto", $lo_titulo);
						$li_monto_total=0;
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$fila=$fila+1;
							$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
							$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
							$li_monto=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["monto"][$li_s]));
							$monto=abs($io_report->DS_detalle->data["monto"][$li_s]);
							$li_monto_total=$li_monto_total+$monto;
							$lo_hoja->write($fila, 0,$ls_codconc, $lo_dataright);
							$lo_hoja->write($fila, 1,$ls_nomcon, $lo_dataright);
							$lo_hoja->write($fila, 2,$li_monto, $lo_dataright);
						}
						$io_report->DS_detalle->resetds("codconc");
						$fila=$fila+1;
						$li_monto_total=number_format($li_monto_total,2,",", ".");
						$lo_hoja->write($fila,1,"Total Bs.",$lo_encabezado);
						$lo_hoja->write($fila,2,$li_monto_total,$lo_dataright);
					}
				}
			}
		}
		$io_report->DS->resetds("codper");
		if($lb_valido) // Si no ocurrio ningún error
		{
			
			//$lo_hoja->insert_bitmap(0, 0, "../shared/imagebank/".$_SESSION["ls_logo"]);
			$bitmap=substr($_SESSION["ls_logo"]);
			$largo=strlen($bitmap)-3;
			$bitmap=substr($bitmap,0,$largo)."bmp";
			$lo_hoja->write('A9', "Images");
			$lo_hoja->insert_bitmap(0,0, "php.bmp", 16, 8);
			
			//$lo_hoja->insert_bitmap(0, 0, $bitmap, 0, 0, 1, 1);
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"Relacion_Ingresos.xls\"");
			header("Content-Disposition: inline; filename=\"Relacion_Ingresos.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);		
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
			unset($io_pdf);
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
	}
?> 
