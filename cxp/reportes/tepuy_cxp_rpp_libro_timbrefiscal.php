<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Timbre Fiscal
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia/ 
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_r_libro_islr_timbrefiscal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha Creación: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/sateb.jpg',30,712,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],490,712,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=330-($li_tm/2);
		//$io_pdf->addText($tm,540,12,$as_titulo); // Agregar el título
		$io_pdf->addText($tm,690,11,$as_titulo); // columna, fila, tamaño de letra
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_agenteret,$as_rifagenteret,$as_diragenteret,$as_periodo,$as_totalordenes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_agenteret // agente de Retención
		//	    		   as_rifagenteret // Rif del Agente de Retención
		//       		   as_diragenteret // Dirección del agente de retención
		//	    		   as_periodo // Periodo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha Creación: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>NOMBRE DE LA INSTITUCION:</b>'."  ".$as_agenteret),
					   array('name'=>'<b>RIF:</b>'."  ".$as_rifagenteret),
					   array('name'=>'<b>DOMICILIO FISCAL:</b>'."  ".$as_diragenteret),
					   array('name'=>'<b>PERIODO:</b>'."  ".$as_periodo),
					   array('name'=>'<b>TOTAL ORDENES DE PAGO RENDIDAS: </b>'.$as_totalordenes));
		
		 
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>540))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);							 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabeceradetalle
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(595);
		$la_data2[1]=array('name1'=>'<b>ORDEN DE PAGO</b>',
				     'name2'=>'<b>BASE IMPONIBLE</b>',
				     'name3'=>'<b>MONTO DEL IMPUESTO 1x1000</b>');
		$io_pdf->line(300,623,300,609);	// ESTAS LINEAS DIVIDEN LOS ESP. VERT. DE LAS FIRMAS QUE APRUEBAN (1=columna,2=fila,3=columna,4=fila)
		$io_pdf->addText(305,612.5,9,"<b>FECHA DE ENTERAMIENTO: </b>"); // Agregar el título (1=columna, 2=fila, 3 tamaño de letra)
		$io_pdf->addText(430,612.5,9,$as_fecha); // Agregar el título (1=columna, 2=fila, 3 tamaño de letra)
		
		$la_config1=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'xPos'=>427.5,		// Inicio de columnas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						// 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>125),
								'name2'=>array('justification'=>'center','width'=>95),
								'name3'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,'','',$la_config1);
		unset($la_data2);
		unset($la_config1);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');


		$io_pdf->saveState();
		$la_data1[1]=array('nombre'=>'<b>Nombre Contribuyente</b>',
						  'rif'=>'<b>CI / RIF</b>',
						  'ordenpago'=>'<b>Número</b>',
						  'fechaordenpago'=>'<b>Fecha</b>',
						  'montobruto'=>'<b>Monto Bruto Orden de Pago</b>',
  						  'monimp'=>'<b> Retenido</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>175),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
									   'ordenpago'=>array('justification'=>'center','width'=>60), // Justificacion y ancho de
									   'fechaordenpago'=>array('justification'=>'center','width'=>65),
						 			   'montobruto'=>array('justification'=>'center','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'center','width'=>90))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>175),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
									   'ordenpago'=>array('justification'=>'center','width'=>60), // Justificacion y ancho de
									   'fechaordenpago'=>array('justification'=>'center','width'=>65),
						 			   'montobruto'=>array('justification'=>'right','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'right','width'=>90))); 
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
		
	}// end function uf_print_detalle
	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totbasimp,$ai_totmonimp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: 
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1[1]=array('total'=>'<b>TOTAL MONTO DEL IMPUESTO PAGADO Bs. </b>',
						  'total2'=>'<b>'.$ai_totmonimp.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>460), // Justificacion y ancho de la columna
						 			   'total2'=>array('justification'=>'right','width'=>95))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
	}// end function uf_print_total
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas($as_nombre,$as_cedula,$as_cargo,$as_telefono,$as_formapago,$as_numero,$as_fecha,$as_monto,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-20); //antes 560 ahora 
		$la_data2[0]=array('firma1'=>'<b>MODALIDAD DE PAGO DEL IMPUESTO 1X1000</b>');
		$la_columna2=array('firma1'=>'');
		$la_config2=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>278, // Orientación de la tabla
						 'width'=>500, // Ancho Máximo de la tabla
						 'maxWidth'=>500,
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la
		$io_pdf->ezTable($la_data2,$la_columna2,'',$la_config2);
		if($as_formapago=="1")
		{
			$pago="<b>Deposito</b>";
		}
		if($as_formapago=="2")
		{
			$pago="<b>Cheque de Gerencia</b>";
		}
		if($as_formapago=="3")
		{
			$pago="<b>Transferencia</b>";
		}

		$la_data1[0]=array('firma1'=>'Deposito, Cheque de Gerencia, Transferencia','firma2'=>'Numero','firma3'=>'Fecha','firma4'=>'Total Monto Pagado (Bs.)');
		$la_data1[1]=array('firma1'=>$pago,'firma2'=>$as_numero,'firma3'=>$as_fecha,'firma4'=>'<b>'.$as_monto.'</b>');
		$la_columna1=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'');
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>278, // Orientación de la tabla
						 'width'=>500, // Ancho Máximo de la tabla
						 'maxWidth'=>500,
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'lefth','width'=>200), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>80),
									   'firma3'=>array('justification'=>'center','width'=>80),
									   'firma4'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la
		$io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);
		$la_data5[0]=array('firma1'=>'<b>NOTA</b>: ANEXAR EL COMPROBANTE DE PAGO ORIGINAL');
		$la_columna5=array('firma1'=>'');
		$la_config5=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>278, // Orientación de la tabla
						 'width'=>500, // Ancho Máximo de la tabla
						 'maxWidth'=>500,
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'lefth','width'=>500))); // Justificación y ancho de la
		$io_pdf->ezTable($la_data5,$la_columna5,'',$la_config5);
		//$io_pdf->ezSetY(435); //antes 495
		$la_data3[0]=array('firma1'=>'<b>ELABORADO POR</b>');
		$la_columna3=array('firma1'=>'');
		$la_config3=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>178, // Orientación de la tabla
						 'width'=>300, // Ancho Máximo de la tabla
						 'maxWidth'=>300,
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>300))); // Justificación y ancho de la
		$io_pdf->ezTable($la_data3,$la_columna3,'',$la_config3);
		$la_data[0]=array('firma1'=>'Nombre y Apellido','firma2'=>$as_nombre);
		$la_data[1]=array('firma1'=>'Cedula de Identidad','firma2'=>$as_cedula);
		$la_data[2]=array('firma1'=>'Cargo','firma2'=>$as_cargo);
		$la_data[3]=array('firma1'=>'Fecha/Hora','firma2'=>$as_fecha);
		$la_data[4]=array('firma1'=>'Telefono','firma2'=>$as_telefono);
		$la_data[5]=array('firma1'=>'Firma                                            ','firma2'=>'Sello                                            ');
//		$la_data[4]=array('firma1'=>'_________________________________','firma2'=>'_________________________________');
//		$la_data[5]=array('firma1'=>'TESORERO / AGENTE DE RETENCION','firma2'=>'JEFE UNIDAD DE TRIBUTOS INTERNOS');
		//$la_data[6]=array('firma1'=>'','firma2'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>178, // Orientación de la tabla
						 'width'=>300, // Ancho Máximo de la tabla
						 'maxWidth'=>300,
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'lefth','width'=>150), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_firmas
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	$io_report=new tepuy_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_cxp_class_reportbsf.php");
		$io_report=new tepuy_cxp_class_reportbsf();
	}
	
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_nombre=$io_fun_cxp->uf_obtenervalor_get("nombre","");
	$ls_cedula=$io_fun_cxp->uf_obtenervalor_get("cedula","");
	$ls_cargo=$io_fun_cxp->uf_obtenervalor_get("cargo","");
	$ls_telefono=$io_fun_cxp->uf_obtenervalor_get("telefono","");
	$ls_formapago=$io_fun_cxp->uf_obtenervalor_get("formapago","");
	$ls_numero=$io_fun_cxp->uf_obtenervalor_get("numero","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_fechaent=$io_fun_cxp->uf_obtenervalor_get("fecha","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_codret=$io_fun_cxp->uf_obtenervalor_get("codret","");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>FORMATO DE RENDICION INFORMATIVA DEL IMPUESTO 1X1000 PARA ORGANOS Y ENTES PUBLICOS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	
	$mes="";
	switch ($ls_mes)
	{
		case '01':
			$mes='ENERO';
		break;
		case '02':
			$mes='FEBRERO';
		break;
		case '03':
			$mes='MARZO';
		break;
		case '04':
			$mes='ABRIL';
		break;
		case '05':
			$mes='MAYO';
		break;
		case '06':
			$mes='JUNIO';
		break;
		case '07':
			$mes='JULIO';
		break;
		case '08':
			$mes='AGOSTO';
		break;
		case '09':
			$mes='SEPTIEMBRE';
		break;
		case '10':
			$mes='OCTUBRE';
		break;
		case '11':
			$mes='NOVIEMBRE';
		break;
		case '12':
			$mes='DICIEMBRE';
		break;
	
	}
	$ls_periodo= $mes.' - '.$ls_anio;	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_contribuyentes_libro_timbrefiscal($ls_mes,$ls_anio,$ls_codret);
		if(!$lb_valido)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf = new Cezpdf("LETTER","portrait");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(4,3,3,3);
			$lb_valido=true;
			$li_totalbaseimp=0;
			$li_totalmontoimp=0;
			$li_i=0;
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			$li_totrow=$io_report->DS->getRowCount("nomsujret");
			for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		//	while (!$rs_data->EOF)
			{
				//$ls_numcon=$rs_data->fields["numcom"];
				$ls_numcon=$io_report->DS->data["numcom"][$li_i];
				//$ls_fecrep=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecfac"]);
				$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecfac"][$li_i]);
				//$ls_nomsujret=$rs_data->fields["nomsujret"];	
				$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];
				//$ls_rif=$rs_data->fields["rif"];	
				$ls_rif=$io_report->DS->data["rif"][$li_i];
				//$li_baseimp=$rs_data->fields["basimp"];
				$li_baseimp=$io_report->DS->data["basimp"][$li_i];
				//$li_totimp=$rs_data->fields["iva_ret"];
				$li_totimp=$io_report->DS->data["iva_ret"][$li_i];
				$li_totbruto=$io_report->DS->data["totcmp_con_iva"][$li_i];
				$li_numsop=$io_report->DS->data["numsop"][$li_i];
				$li_fechaaprosol=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecaprosol"][$li_i]);
				$ls_denmun='BARINAS';
				$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
				$li_totalmontoimp=$li_totalmontoimp + $li_totimp;					
				$la_data[$li_i]=array('nombre'=>$ls_nomsujret, 'rif'=>$ls_rif,
									'ordenpago'=>(int)$li_numsop,
									'fechaordenpago'=>$li_fechaaprosol,			                      
				//					'monto'=>number_format($li_baseimp,2,",","."),
				//					'montobruto'=>number_format($li_totbruto,2,",","."),
									'montobruto'=>number_format($li_baseimp,2,",","."),
									  'monimp'=>number_format($li_totimp,2,",","."));
			/*	$la_data[$li_i]=array('fecha'=>$ls_fecrep,'nombre'=>$ls_nomsujret, 'rif'=>$ls_rif,
									'ordenpago'=>$li_numsop,
									'fechaordenpago'=>$li_fechaaprosol,			                      
				//					'monto'=>number_format($li_baseimp,2,",","."),
									'montobruto'=>number_format($li_totbruto,2,",","."),
									  'monimp'=>number_format($li_totimp,2,",","."));*/
				//$rs_data->MoveNext();	

			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				
				uf_print_cabecera($ls_agenteret,$ls_rifagenteret,$ls_diragenteret,$ls_periodo,$li_totrow,&$io_pdf);
				uf_print_detalle($la_data,$ls_fechaent,&$io_pdf);
				uf_print_total(number_format($li_totalbaseimp,2,",","."),number_format($li_totalmontoimp,2,",","."),&$io_pdf);
				uf_print_firmas($ls_nombre,$ls_cedula,$ls_cargo,$ls_telefono,$ls_formapago,$ls_numero,$ls_fechaent,number_format($li_totalmontoimp,2,",","."),&$io_pdf);
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
				unset($la_data);
			}
			else  // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 
