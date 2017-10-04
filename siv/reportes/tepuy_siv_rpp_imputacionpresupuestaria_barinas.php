<?php
    session_start();
	//ini_set('display_errors', 1);
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo as_titulo1 // Título del Reporte
		//	    		   io_pdf    // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/03/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->setStrokeColor(0,0,0);
$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,725,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

		$io_pdf->addText(210,755,9,"REPUBLICA BOLIVARIANA DE VENEZUELA"); // Agregar el título 1
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$io_pdf->addText(175,745,9,$ls_nombre." DEL ESTADO BARINAS"); // Agregar el título 2
		$ls_rif=$_SESSION["la_empresa"]["rifemp"];
		$io_pdf->addText(265,736,9,"RIF-".$ls_rif); // Agregar el título 3		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=230-($li_tm/2);
		$io_pdf->addText($tm,710,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$tm=220-($li_tm/2);
		$io_pdf->addText($tm,697,11,"<b>".$as_titulo1."</b>"); // Agregar el título
	/*	$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,720,11,$ad_fecha); // Agregar el título*/
		//$io_pdf->addText(690,550,8,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(696,543,7,date("h:i a")); // Agregar la Hora

		$io_pdf->Rectangle(20,47,570,65);
		//$io_pdf->line(20,172,590,172); //linea que divide firmas y sellos
		$io_pdf->line(20,100,590,100); //linea superior de la RECEPCION
		//$io_pdf->line(20,163,590,163);	// Linea que se encuentra en el nivel inferior de ELABORADO POR:

		$io_pdf->line(200,47,200,112);	// ESTAS LINEAS DIVIDEN LOS ESP. VERT. DE LAS FIRMAS QUE APRUEBAN	
		$io_pdf->line(390,47,390,112);		
		//$io_pdf->line(385,47,385,100);
		//$io_pdf->line(490,47,490,65);	
		$nomusu=$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];
		$li_tm=$io_pdf->getTextWidth(13,$nomusu);
		$tm=136-($li_tm/2);
		$io_pdf->addText(80,105,6,"ELABORADO POR:"); // Agregar el título
		$io_pdf->addText($tm,57,6,$nomusu); // Agregar el título
		// COMPRAS ///
		$io_pdf->addText(260,105,6,"CONFORMADO POR:"); // Agregar el título
		$ls_jefe_compras=$_SESSION["la_empresa"]["jefe_compr"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_jefe_compras);
		$tm=360-($li_tm/2);

		$io_pdf->addText($tm,57,6,$ls_jefe_compras); // Agregar el Nombre del Jefe de Contabilidad
		$ls_cargo_compras=$_SESSION["la_empresa"]["cargo_compr"];
		//$li_tm=$io_pdf->getTextWidth(13,$ls_cargo_compras);
		$tm=360-($li_tm/2);

		$io_pdf->addText($tm,50,6,$ls_cargo_compras); // Agregar el título
		// CONTABILIDAD ///
		$io_pdf->addText(465,105,6,"APROBADO POR:"); // Agregar el título
		$ls_jefe_contabilidad=$_SESSION["la_empresa"]["jefe_contabilidad"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_jefe_contabilidad);
		$tm=530-($li_tm/2);

		$io_pdf->addText($tm,57,6,$ls_jefe_contabilidad); // Agregar el Nombre del Jefe de Contabilidad
		$ls_cargo_contabilidad=$_SESSION["la_empresa"]["cargo_contabilidad"];
		//$li_tm=$io_pdf->getTextWidth(13,$ls_cargo_contabilidad);
		$tm=530-($li_tm/2);

		$io_pdf->addText($tm,50,6,$ls_cargo_contabilidad); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_columna=array('partida'=>'<b>Part. </b>',
						  'generica'=>'<b>Gen.</b>',
						  'especifica'=>'<b>Esp.</b>',
						  'subespecifica'=>'<b>Sub-Esp.</b>',
						  'auxiliar'=>'<b>Aux</b>',
						  'denominacion'=>'<b>Denominación</b>',
						  'incorporacion'=>'<b>Incorporación</b>',
						  'desincorporacion'=>'<b>Desincorporación</b>',
						  'saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('partida'=>array('justification'=>'left','width'=>30), // Justificación y ancho de la columna
						 			   'generica'=>array('justification'=>'left','width'=>30),
									   'especifica'=>array('justification'=>'left','width'=>30),
									   'subespecifica'=>array('justification'=>'left','width'=>30), // Justificación y ancho de la columna
						 			   'auxiliar'=>array('justification'=>'left','width'=>35),
									   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
									   'incorporacion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'desincorporacion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'saldo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalleg
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'','totalin'=>'','totaldesin'=>'','totalsaldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>295), // Justificación y ancho de la columna
						 			   'totalin'=>array('justification'=>'right','width'=>80),
									'totaldesin'=>array('justification'=>'right','width'=>80),
									'totalsaldo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_siv_class_report.php");
	$io_report=new tepuy_siv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_coddesde=$io_fun_inventario->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_inventario->uf_obtenervalor_get("codhasta","");
	$ld_desde=$io_fun_inventario->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_inventario->uf_obtenervalor_get("hasta","");

	$ls_titulo="Inventario General de Almacén";
	$ls_titulo1="Desde ".$ld_desde." Hasta ".$ld_hasta;

/*	if($ls_coddesde!="")
	{$ls_fecha="Rango ".$ls_coddesde." - ".$ls_codhasta;}
	else
	{$ls_fecha="";}*/
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$li_numrows=0;
	$lb_valido=$io_report->uf_select_listadoimpu_presupuestaria($ls_codemp,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$li_numrows); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte Listado Imputación Presupuestaria del inventario desde ".$ls_coddesde." hasta ".$ls_codhasta;
		$io_fun_inventario->uf_load_seguridad_reporte("SIV","tepuy_siv_r_listadoarticulos.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(720,50,10,'','',1); // Insertar el número de página
		$li_totrow=$li_numrows;//$io_report->ds->getRowCount("spgcuenta"); // print $li_totrow;
		//print $li_totrow;die();
		$li_totalincorporado=0;
		$li_totaldesincorporado=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    	$io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_total=0;
			//$ls_codart= $io_report->ds->data["codart"][$li_i];
			//$ls_denart= $io_report->ds->data["denart"][$li_i];
			//$ls_precio= $io_report->ds->data["prearta"][$li_i];
			$ls_cuenta= $io_report->ds->data["spg_cuenta"][$li_i];
			$ls_denominacion=$io_report->uf_select_den_cuenta($ls_codemp,$ls_cuenta);
			$lb_valido1=$io_report->uf_select_salidasimpu_presupuestaria($ls_codemp,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$li_totalsalida,$ls_cuenta);
			$li_montosalida=0;
			if($lb_valido1)$li_montosalida=$li_totalsalida;
			//$ls_denominacion= $io_report->ds->data["denominacion"][$li_i];
			$li_montoentrada= $io_report->ds->data["montotart"][$li_i];
			$li_totalincorporado=$li_totalincorporado+$li_montoentrada;
			$li_totaldesincorporado=$li_totaldesincorporado+$li_montosalida;
			$li_incorporado = number_format($li_montoentrada,2,",",".");
			$li_desincorporado = number_format($li_montosalida,2,",",".");

			$li_saldo=$li_montoentrada-$li_montosalida;
			$li_saldo = number_format($li_saldo,2,",",".");
			$ls_partida=substr($ls_cuenta,0,3);
			$ls_generica=substr($ls_cuenta,3,2);
			$ls_especifica=substr($ls_cuenta,5,2);
			$ls_subespecifica=substr($ls_cuenta,7,2);
			$ls_auxiliar=substr($ls_cuenta,9,4);
			if($ls_especifica=="00")$ls_especifica="";
			if($ls_subespecifica=="00")$ls_subespecifica="";
			if($ls_auxiliar=="0000")$ls_auxiliar="";
			//$ls_contable= $io_report->ds->data["sc_cuenta"][$li_i];
			//$ls_denunimed= $io_report->ds->data["denunimed"][$li_i];
			//$ls_valorunidad= $io_report->ds->data["unidad"][$li_i];
			$la_data[$li_i]=array('partida'=>$ls_partida,'generica'=>$ls_generica,'especifica'=>$ls_especifica,'subespecifica'=>$ls_subespecifica,'auxiliar'=>$ls_auxiliar,'denominacion'=>$ls_denominacion,'incorporacion'=>$li_incorporado,'desincorporacion'=>$li_desincorporado,'saldo'=>$li_saldo);
								  
		}
		if($li_totrow>0)
		{
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			$li_totalsaldo=$li_totalincorporado-$li_totaldesincorporado;
			$li_totart=number_format($li_totrow,2,",",".");
			$li_totaldesincorporado=number_format($li_totaldesincorporado,2,",",".");
			$li_totalsaldo=number_format($li_totalsaldo,2,",",".");
			$li_totalincorporado = number_format($li_totalincorporado,2,",",".");
			$la_datat[1]=array('total'=>'<b>Totales</b>','totalin'=>$li_totalincorporado,'totaldesin'=>$li_totaldesincorporado,'totalsaldo'=>$li_totalsaldo);
			uf_print_totales($la_datat,$io_pdf); // Imprimimos el detalle
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}

		unset($la_data);			
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
