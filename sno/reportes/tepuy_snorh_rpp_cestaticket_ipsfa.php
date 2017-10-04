<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // descripción de la nómina
		//	    		   as_periodo // período actual de la nómina
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","tepuy_snorh_r_cestaticket.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,570,9,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo2);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,560,9,$as_titulo2); // Agregar el título2
		$li_tm=$io_pdf->getTextWidth(9,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,9,$as_desnom); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,9,$as_periodo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_coduniadm,$as_desuniadm,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_coduniadm // Código de Unidad Administrativa
		//	   			   as_desuniadm // Nombre de Unidad Administrativa
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>GERENCIA:</b> '.$as_coduniadm.' - '.$as_desuniadm.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>700))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>            Apellidos y Nombres</b>',
						   'personal'=>'<b>Tipo Personal</b>',
						   'ubicacion'=>'<b>Ubicación</b>',
						   'ticket'=>'<b>Tickets</b>',
						   'diario'=>'<b>Monto Diario</b>',
						   'valor'=>'<b>Monto Mensual     </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'ubicacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'diario'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_ticket,$as_desuniadm,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total 
		//	   			   ai_ticket // Ticket
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>TOTAL POR UBICACIÓN '.$as_desuniadm.'    </b>','ticket'=>$ai_ticket,'total'=>$ai_total));
		$la_columna=array('name'=>'','ticket'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>250), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>340))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	$ls_titulo="<b>INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA</b>";
	$ls_titulo2="<b>REPORTE DE PERSONAL PARA EL CONTROL DEL PROGRAMA DE ALIMENTACIÓN</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_orden="5";
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_rango= "Nómina Desde: ".$ls_codnomdes." Nómina Hasta: ".$ls_codnomhas;
	$ls_periodo= "Año: ".$ls_ano." Mes: ".$io_fecha->uf_load_nombre_mes($ls_mes);
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_snorh_class_reportbsf.php");
		$io_report=new tepuy_snorh_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cestaticket_personal($ls_codnomdes,$ls_codnomhas,$ls_ano,$ls_mes,$ls_codperi,$ls_codconcdes,
														$ls_codconchas,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.2,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ls_rango,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("cedper");
		$ls_coduniadmact="";
		$ls_desuniadmact="";
		$ls_desuniadm="";
		$ls_coduniadm="";
		$li_contador=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_coduniadm=$io_report->DS->data["minorguniadm"][$li_i].$io_report->DS->data["ofiuniadm"][$li_i].$io_report->DS->data["uniuniadm"][$li_i].
			            $io_report->DS->data["depuniadm"][$li_i].$io_report->DS->data["prouniadm"][$li_i];
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];
			if($ls_coduniadm!=$ls_coduniadmact)
			{
				if($li_i>1)
				{
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					unset($la_data);
					$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
					uf_print_piecabecera($li_total,$li_contador,$ls_desuniadmact,$io_pdf); // Imprimimos el fin del reporte
				}
 				uf_print_cabecera($ls_coduniadm,$ls_desuniadm,&$io_cabecera,&$io_pdf);
 				$ls_coduniadmact=$ls_coduniadm;
				$ls_desuniadmact=$ls_desuniadm;
				$li_contador=0;
				$li_total=0;
			}
			$li_contador++;
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_desnom=$io_report->DS->data["desnom"][$li_i];
			$ls_ubicacion="";
			$li_ticket=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["valsal"][$li_i])/abs($io_report->DS->data["moncestic"][$li_i]));
			$li_moncestic=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["moncestic"][$li_i]);
			$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["valsal"][$li_i]));
			$li_total=$li_total+abs($io_report->DS->data["valsal"][$li_i]);
			$la_data[$li_contador]=array('nro'=>$li_contador,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$ls_desnom,
			                             'ubicacion'=>$ls_ubicacion,'ticket'=>$li_ticket,'diario'=>$li_moncestic,'valor'=>$li_valsal);
		}
		$io_report->DS->resetds("cedper");
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
		uf_print_piecabecera($li_total,$li_contador,$ls_desuniadmact,$io_pdf); // Imprimimos el fin del reporte
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
