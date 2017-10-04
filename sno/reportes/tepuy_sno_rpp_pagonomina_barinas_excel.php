<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 30/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo." en Excel. Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "pagonomina.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("tepuy_sno_class_report.php");
		$io_report=new tepuy_sno_class_report();
		$li_tipo=1;
		$ls_tabla="sno_salida";
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("tepuy_sno_class_report_historico.php");
		$io_report=new tepuy_sno_class_report_historico();
		$li_tipo=2;
		$ls_tabla="sno_thsalida";
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Reporte General de Pago";
	$ls_periodo="Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonomina_personal($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_titulo="NÓMINA DE PAGO";
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo1="Categoria Programática ";
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo1="Estructura Presupuestaria ";
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		$lo_encabezadop= &$lo_libro->addformat();
		$lo_encabezadop->set_bold();
		$lo_encabezadop->set_font("Verdana");
		$lo_encabezadop->set_align('center');
		$lo_encabezadop->set_size('13');
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
		$lo_dataright= &$lo_libro->addformat(array(num_format=> '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,12);
		$lo_hoja->set_column(2,2,50);
		$lo_hoja->set_column(3,3,40);
		$lo_hoja->set_column(4,4,40);
		$lo_hoja->set_column(5,5,20);
		$lo_hoja->set_column(6,6,15);
		$lo_hoja->set_column(7,7,25);
		$lo_hoja->set_column(8,8,40);
		$lo_hoja->set_column(9,9,20);
		$lo_hoja->set_column(10,10,20);
		$lo_hoja->set_column(11,11,20);
		$lo_hoja->set_column(12,12,15);
		$lo_hoja->set_column(13,13,15);
		$lo_hoja->set_column(14,14,15);
		$lo_hoja->write(0,3,$ls_titulo,$lo_encabezadop);
		$lo_hoja->write(1,3,$ls_periodo,$lo_encabezado);
		$lo_hoja->write(2,3,$ls_desnom,$lo_encabezado);
		$lo_hoja->write(4, 0, "Nro.",$lo_titulo);
		$lo_hoja->write(4, 1, "Cédula",$lo_titulo);
		$lo_hoja->write(4, 2, "Apellidos y Nombres",$lo_titulo);
		$lo_hoja->write(4, 3, "Cargo",$lo_titulo);
		$lo_hoja->write(4, 4, "Unidad Adscripción",$lo_titulo);
		$lo_hoja->write(4, 5, $ls_titulo1,$lo_titulo);
		$lo_hoja->write(4, 6, "Fecha Ingreso",$lo_titulo);
		$lo_hoja->write(4, 7, "Cuenta Bancaria",$lo_titulo);
		$lo_hoja->write(4, 8, "Ubicación Física",$lo_titulo);
		$lo_hoja->write(4, 9, "Estado",$lo_titulo);
		$lo_hoja->write(4, 10, "Municipio",$lo_titulo);
		$lo_hoja->write(4, 11, "Parroquia",$lo_titulo);
		$lo_hoja->write(4, 12, "Sueldo",$lo_titulo);
		$lo_hoja->write(4, 13, "Fecha de Ingreso a Nómina",$lo_titulo);
		//$lo_hoja->write(4, 14, "Fecha de Culminación de Contrato",$lo_titulo);
		$li_col=13; // columna en la que va el reporte
		// Buscamos los conceptos de asignación
		$lb_valido=$io_report->uf_pagonomina_concepto_excel($ls_tituloconcepto," AND (sigcon='A' OR sigcon='B')"); // Obtenemos el detalle del reporte
		if($lb_valido)
		{
			$lo_hoja->write(3, $li_col+1, "ASIGNACIONES",$lo_titulo);
			$li_totrow=$io_report->DS_detalle->getRowCount("codconc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_i];
				$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_i];
				$li_col++;
				$lo_hoja->set_column($li_col,$li_col,20);
				$lo_hoja->write(4, $li_col, $ls_nomcon,$lo_titulo);
				$io_report->DS_detalle2->insertRow("codconc",$ls_codconc);
				$io_report->DS_detalle2->insertRow("columna",$li_col);
			}
		}
		// Buscamos los conceptos de Deducción
		$lb_valido=$io_report->uf_pagonomina_concepto_excel($ls_tituloconcepto," AND (sigcon='D' OR sigcon='E')"); // Obtenemos el detalle del reporte
		if($lb_valido)
		{
			$lo_hoja->write(3, $li_col+1, "DEDUCCIONES",$lo_titulo);
			$li_totrow=$io_report->DS_detalle->getRowCount("codconc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_i];
				$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_i];
				$li_col++;
				$lo_hoja->set_column($li_col,$li_col,20);
				$lo_hoja->write(4, $li_col, $ls_nomcon,$lo_titulo);
				$io_report->DS_detalle2->insertRow("codconc",$ls_codconc);
				$io_report->DS_detalle2->insertRow("columna",$li_col);
			}
		}
		// Buscamos los conceptos de Aporte Patronal
		$lb_valido=$io_report->uf_pagonomina_concepto_excel($ls_tituloconcepto," AND sigcon='P'"); // Obtenemos el detalle del reporte
		if($lb_valido)
		{
			$li_totrow=$io_report->DS_detalle->getRowCount("codconc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_i];
				$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_i];
				$li_col++;
				$lo_hoja->set_column($li_col,$li_col,20);
				$lo_hoja->write(4, $li_col, $ls_nomcon,$lo_titulo);
				$io_report->DS_detalle2->insertRow("codconc",$ls_codconc."P1");
				$io_report->DS_detalle2->insertRow("columna",$li_col);
			}
			$lo_hoja->write(3, $li_col+1, "APORTE PATRONAL",$lo_titulo);
			$li_totrow=$io_report->DS_detalle->getRowCount("codconc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_i];
				$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_i];
				$li_col++;
				$lo_hoja->set_column($li_col,$li_col,20);
				$lo_hoja->write(4, $li_col, $ls_nomcon,$lo_titulo);
				$io_report->DS_detalle2->insertRow("codconc",$ls_codconc."P2");
				$io_report->DS_detalle2->insertRow("columna",$li_col);
			}
		}
		$li_final=$li_col;
		$li_row=4;
		$li_totrow=$io_report->DS->getRowCount("codper");
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			//$ls_cedper=$ls_nacper=$io_report->DS->data["nacper"][$li_i].$io_report->DS->data["cedper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_apenomper=$io_report->DS->data["apeper"][$li_i].", ". $io_report->DS->data["nomper"][$li_i];
			$ls_descar=$io_report->DS->data["descar"][$li_i];
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ld_fecingnom=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingnom"][$li_i]);
			$ld_feculcont=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecculcontr"][$li_i]);
			if ($ld_feculcont!='01/01/1900')
			{
				$ld_feculcont=$ld_feculcont;
			}
			else
			{
				$ld_feculcont="";
			}
			$ls_codcueban=$io_report->DS->data["codcueban"][$li_i];
			$ld_sueper=number_format($io_report->DS->data["sueper"][$li_i],2,",",".");
			$ls_coduniadm=$io_report->DS->data["minorguniadm"][$li_i]."-".$io_report->DS->data["ofiuniadm"][$li_i]."-".
						  $io_report->DS->data["uniuniadm"][$li_i]."-".$io_report->DS->data["depuniadm"][$li_i]."-".
						  $io_report->DS->data["prouniadm"][$li_i];
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			switch($ls_nacper)
			{
				case "V":
					$ls_nacper="Venezolano";
					break;
				case "E":
					$ls_nacper="Extranjero";
					break;
			}
			$ls_desubifis=$io_report->DS->data["desubifis"][$li_i];
			$ls_desest=$io_report->DS->data["desest"][$li_i];
			$ls_denmun=$io_report->DS->data["denmun"][$li_i];
			$ls_denpar=$io_report->DS->data["denpar"][$li_i];
			$codprouniadm=$io_report->DS->data["codprouniadm"][$li_i];
			$ls_codest1=substr($codprouniadm,0,20);
			$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
			$ls_codest2=substr($codprouniadm,20,6);
			$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
			$ls_codest3=substr($codprouniadm,26,3);
			$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
			$ls_codest4=substr($codprouniadm,29,2);
			$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
			$ls_codest5=substr($codprouniadm,31,2);
			$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
			
			switch($ls_modalidad)
			{
				case "1": // Modalidad por Proyecto
					//$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;
					$ls_programatica=substr($ls_codest1,18,2)."-".substr($ls_codest2,4,2)."-".substr($ls_codest3,1,2);
					break;
					
				case "2": // Modalidad por Presupuesto
					$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;
					break;
			}
			$li_row=$li_row+1;
			//$lo_hoja->write($li_row, 0, $ls_nacper, $lo_datacenter);
			$lo_hoja->write($li_row, 0, $li_i, $lo_datacenter);
			$lo_hoja->write($li_row, 1, $ls_cedper, $lo_datacenter);
			$lo_hoja->write($li_row, 2, $ls_apenomper, $lo_dataleft);
			$lo_hoja->write($li_row, 3, $ls_descar, $lo_dataleft);
			$lo_hoja->write($li_row, 4, $ls_desuniadm, $lo_dataleft);
			$lo_hoja->write($li_row, 5, $ls_programatica, $lo_dataleft);
			$lo_hoja->write($li_row, 6, $ld_fecingper, $lo_datacenter);
			$lo_hoja->write_string($li_row, 7, $ls_codcueban, $lo_dataleft);
			$lo_hoja->write($li_row, 8, $ls_desubifis, $lo_dataleft);
			$lo_hoja->write($li_row, 9, $ls_desest, $lo_dataleft);
			$lo_hoja->write($li_row, 10, $ls_denmun, $lo_dataleft);
			$lo_hoja->write($li_row, 11, $ls_denpar, $lo_dataleft);
			$lo_hoja->write($li_row, 12, $ld_sueper, $lo_dataright);
			$lo_hoja->write($li_row, 13, $ld_fecingnom, $lo_datacenter);
			$lo_hoja->write($li_row, 14, $ld_feculcont, $lo_datacenter);
			$li_col=14;
			// Buscamos las Asignaciones
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_tituloconcepto," AND (".$ls_tabla.".tipsal='A' OR ".$ls_tabla.".tipsal='V1' OR ".$ls_tabla.".tipsal='W1')"); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					$li_totalasignacion=$li_totalasignacion + $li_valsal;
					$li_find=$io_report->DS_detalle2->find("codconc",$ls_codconc);
					$li_col=$io_report->DS_detalle2->getValue("columna",$li_find);
					$lo_hoja->write($li_row, $li_col, $li_valsal, $lo_dataright);
				}
				$io_report->DS_detalle->resetds("codconc");
			}
			// Buscamos las Deducciones
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_tituloconcepto,"AND (".$ls_tabla.".tipsal='D' OR ".$ls_tabla.".tipsal='V2' OR ".$ls_tabla.".tipsal='W2')"); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
					$li_find=$io_report->DS_detalle2->find("codconc",$ls_codconc);
					$li_col=$io_report->DS_detalle2->getValue("columna",$li_find);
					$lo_hoja->write($li_row, $li_col, $li_valsal, $lo_dataright);
				}
				$io_report->DS_detalle->resetds("codconc");
			}
			// Buscamos los Aportes Empleado
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_tituloconcepto,"AND (".$ls_tabla.".tipsal='P1' OR ".$ls_tabla.".tipsal='V3' OR ".$ls_tabla.".tipsal='W3')"); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
					$li_find=$io_report->DS_detalle2->find("codconc",$ls_codconc."P1");
					$li_col=$io_report->DS_detalle2->getValue("columna",$li_find);
					$lo_hoja->write($li_row, $li_col, $li_valsal, $lo_dataright);
				}
				$io_report->DS_detalle->resetds("codconc");
			}
			// Buscamos los Aportes Patron
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_tituloconcepto,"AND (".$ls_tabla.".tipsal='P2' OR ".$ls_tabla.".tipsal='V4' OR ".$ls_tabla.".tipsal='W4')"); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					$li_totalaporte=$li_totalaporte + $li_valsal;
					$li_find=$io_report->DS_detalle2->find("codconc",$ls_codconc."P2");
					$li_col=$io_report->DS_detalle2->getValue("columna",$li_find);
					$lo_hoja->write($li_row, $li_col, $li_valsal, $lo_dataright);
				}
				$io_report->DS_detalle->resetds("codconc");
			}
			$li_col=$li_final;
			$li_total_neto=($li_totalasignacion-$li_totaldeduccion);
			$lo_hoja->write(3, $li_col+1, "TOTALES",$lo_titulo);
			$li_col++;
			$lo_hoja->set_column($li_col,$li_col,20);
			$lo_hoja->write(4, $li_col, "TOTAL ASIGNACIÓN",$lo_titulo);
			$lo_hoja->write($li_row, $li_col, $li_totalasignacion, $lo_dataright);
			$li_col++;
			$lo_hoja->set_column($li_col,$li_col,20);
			$lo_hoja->write(4, $li_col, "TOTAL DEDUCCIÓN",$lo_titulo);
			$lo_hoja->write($li_row, $li_col, $li_totaldeduccion, $lo_dataright);
			$li_col++;
			$lo_hoja->set_column($li_col,$li_col,20);
			$lo_hoja->write(4, $li_col, "TOTAL APORTE PATRON",$lo_titulo);
			$lo_hoja->write($li_row, $li_col, $li_totalaporte, $lo_dataright);
			$li_col++;
			$lo_hoja->set_column($li_col,$li_col,20);
			$lo_hoja->write(4, $li_col, "NETO A COBRAR",$lo_titulo);
			$lo_hoja->write($li_row, $li_col, $li_total_neto, $lo_dataright);
		}
		$io_report->DS->resetds("codper");
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"pagonomina.xls\"");
		header("Content-Disposition: inline; filename=\"pagonomina.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
