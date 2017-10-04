<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Relación de Retencion de IVA
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();
    ini_set('display_errors', 1);   
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
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/07/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_r_retencionesiva.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecdes,$as_fechas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/07/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,510,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=406-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_titulo); // Agregar el título
		$ls_periodo = "<b>Del :</b>".$as_fecdes."   "."<b>Al :</b>".$as_fechas;	
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=406-($li_tm/2);
		$io_pdf->addText($tm,515,11,$ls_periodo); // Agregar el título
		$io_pdf->addText(690,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(690,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_dended,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_codded // Código de Deduccion
		//	    		   as_dended // Deenominación de Deduccion
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 07/07/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$la_data   =array(array('retencion'=>'<b><i>Retención:<i></b>','denominacion'=>$as_dended));
		$la_columna=array('retencion'=>'','denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2,
						 'shadeCol2'=>array(0.86,0.86,0.86),
						 'colGap'=>1,
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('retencion'=>array('justification'=>'left','width'=>60),
						 		'denominacion'=>array('justification'=>'left','width'=>630))); // Justificación y ancho de la columna
		//$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('rif'=>'<b>Rif</b>','beneficiario'=>'<b>Beneficiario</b>','factura'=>'<b>N. Factura</b>','facturac'=>'<b>Factura Control</b>','fecha'=>'<b>Fecha</b>','comprobante'=>'<b>Comprobante</b>','base'=>'<b>Base Imponible</b>','monto'=>'<b>Monto I.V.A.</b>','montotdoc'=>'<b>Monto Total</b>','retencion'=>'<b>Retención</b>'));
		$la_columna=array('rif'=>'','beneficiario'=>'','factura'=>'','facturac'=>'','fecha'=>'','comprobante'=>'','base'=>'','monto'=>'','montotdoc'=>'','retencion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0,
						 'shadeCol2'=>array(0.86,0.86,0.86),
						 'colGap'=>1,
						 'width'=>720, // Ancho de la tabla
						 'maxWidth'=>720, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						  'cols'=>array('rif'=>array('justification'=>'center','width'=>60),
						 			   'beneficiario'=>array('justification'=>'center','width'=>150),
						 			   'factura'=>array('justification'=>'center','width'=>50),
						 			   'facturac'=>array('justification'=>'center','width'=>75),
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'comprobante'=>array('justification'=>'center','width'=>75),
						 			   'base'=>array('justification'=>'center','width'=>68), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>68), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'center','width'=>68))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo con todos los datos 
		//				   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 07/07/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('rif'=>'<b>Rif</b>','beneficiario'=>'<b>Beneficiario</b>','factura'=>'<b>N. Factura</b>',
				'facturac'=>'<b>Factura Control</b>','fecha'=>'<b>Fecha</b>','comprobante'=>'<b>Comprobante</b>',
				'base'=>'<b>Base Imponible</b>','monto'=>'<b>Monto I.V.A.</b>',
				'montotdoc'=>'<b>Monto Total</b>','retencion'=>'<b>Retención</b>','%retencion'=>'<b>% Retenido</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' =>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>720, // Ancho de la tabla
						 'maxWidth'=>720, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('rif'=>array('justification'=>'center','width'=>60),
						 			   'beneficiario'=>array('justification'=>'center','width'=>148),
						 			   'factura'=>array('justification'=>'center','width'=>50),
						 			   'facturac'=>array('justification'=>'center','width'=>75),
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'comprobante'=>array('justification'=>'center','width'=>75),
						 			   'base'=>array('justification'=>'center','width'=>68), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>68), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>82), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'center','width'=>56), // Justificación y ancho de la columna
						 			   '%retencion'=>array('justification'=>'center','width'=>38))); // Justificación y ancho de la columna
		//$la_columna=array('rif'=>'','beneficiario'=>'','factura'=>'','facturac'=>'','fecha'=>'','comprobante'=>'','base'=>'','monto'=>'','montotdoc'=>'','retencion'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ai_filas,$ai_totbase,$ai_total,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_filas // Total de Filas
		//				   ai_total // Monto total retenido
		//				   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/07/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
	    $la_data[1]=array('name'=>'_________________________________________________________________________________________________________________________________');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						// 'xPos'=>312, // Orientación de la tabla
						 'width'=>720); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('cantidad'=>'<b>Total de Retenciones :</b>','filas'=>$ai_filas,'totales'=>'<b>Total Montos '.$ls_titulo.'</b>','base'=>$ai_totbase,'monto'=>$ai_total);
	    $la_columna=array('cantidad'=>'','filas'=>'','totales'=>'','base'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' =>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'right','width'=>90),
						               'filas'=>array('justification'=>'left','width'=>20),
									   'totales'=>array('justification'=>'right','width'=>360),
									   'base'=>array('justification'=>'right','width'=>90),
									   'monto'=>array('justification'=>'right','width'=>160))); // Justificación y ancho de la columna
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	$io_report=new tepuy_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	require_once($as_path."../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();

	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_quincena=$io_fun_cxp->uf_obtenervalor_get("quincena","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	//$ls_titulo="<b>DATA PARA CREAR ARCHIVO (TXT) DE RETENCION DE I.V.A.</b>";
	$ls_titulo="<b>RELACIÓN DE RETENCION DE I.V.A. </b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_cxp_class_reportbsf.php");
		$io_report=new tepuy_cxp_class_reportbsf();
	}
	switch($ls_quincena)
	{
		case "1":
			$ld_fechadesde=$ls_anio."-".$ls_mes."-01";
			$ld_fechadesde1="01-".$ls_mes."-".$ls_anio;
			$ld_fechahasta=$ls_anio."-".$ls_mes."-15";
			$ld_fechahasta1="15-".$ls_mes."-".$ls_anio;
			$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."' AND fecrep <='".$ld_fechahasta."'";
			$complemento_encabezado=" PRIMERA QUINCENA DE ";
			break;
			
		case "2":
			$ld_fechadesde=$ls_anio."-".$ls_mes."-16";
			$ld_fechadesde1="16-".$ls_mes."-".$ls_anio;
			$ld_fechahasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
			$ld_fechahasta1=substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2)."-".$ls_mes."-".$ls_anio;
			$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."' AND fecrep <='".$ld_fechahasta."'";
			$complemento_encabezado="SEGUNDA QUINCENA DE ";
			break;               
	}
	$ls_periodofiscal=substr($ld_fechadesde,0,4).substr($ld_fechadesde,5,2);
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
	$complemento_encabezado="<b>".$complemento_encabezado.$mes." ".$ls_anio."</b>";
	$ls_titulo=$ls_titulo.$complemento_encabezado;
	$ls_periodo= $mes.' - '.$ls_anio;
	$ls_codreten=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='I'",'C');
	//print "Tipo Retencion: ".$ls_tiporeten; die();
//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_contribuyentes_libro_iva($ls_codemp,$ls_mes,$ls_anio,$ls_codreten,$ls_periodofiscal,$ld_fechadesde,$ld_fechahasta);
	}
	if($lb_valido===false)
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
		//$io_pdf=new Cezpdf('LETTER','portrait');
		$io_pdf=new Cezpdf('LETTER','landscape');
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
		$io_pdf->ezSetCmMargins(4.4,3,3,3);                          
		uf_print_encabezado_pagina($ls_titulo,$ld_fechadesde1,$ld_fechahasta1,&$io_pdf);
		//uf_print_cabecera($ls_dended,&$io_pdf);
		$li_totiva=0;
		$li_totret=0;
		$li_totrow=$io_report->DS->getRowCount("numsol");
		///print "Total: ".$li_totrow;die();
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_rifpro= $io_report->DS->data["cedula_rif"][$li_i];
			$ls_numsol= $io_report->DS->data["numsol"][$li_i];
			$ls_nombre= $io_report->DS->data["nombre"][$li_i];
			$ls_fechafactura= $io_report->DS->data["fecemidoc"][$li_i];
			$ls_factura= $io_report->DS->data["factura"][$li_i];
			$ls_facturac= $io_report->DS->data["facturac"][$li_i];
			$ls_comprobante= $io_report->DS->data["numcom"][$li_i];
			$ld_fecemisol= $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemisol"][$li_i]);
			$ls_fechafactura= $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
			$ls_montoiva=$io_report->DS->data["montoiva"][$li_i];
			$li_1=$io_report->DS->data["mon_obj_ret"][$li_i];
			$li_2=$io_report->DS->data["base"][$li_i];
			$suma=$li_2+$ls_montoiva; //$li_2;
			$li_pormonret=($io_report->DS->data["monret"][$li_i]*100)/$ls_montoiva;
			$li_pormonret=number_format($li_pormonret,0,',','.');
			$li_monsol= number_format($io_report->DS->data["montoiva"][$li_i],2,',','.');
			$li_base= number_format($io_report->DS->data["base"][$li_i],2,',','.');
			$li_montotdoc= number_format($suma,2,',','.');
			$li_monret= number_format($io_report->DS->data["monret"][$li_i],2,',','.');
			$li_totiva= $li_totiva+$io_report->DS->data["mon_obj_ret"][$li_i];
			$li_totret= $li_totret+$io_report->DS->data["monret"][$li_i];
			$la_data[$li_i]=array('rif'=>$ls_rifpro,'beneficiario'=>$ls_nombre,'factura'=>$ls_factura,'facturac'=>$ls_facturac,'fecha'=>$ls_fechafactura,'comprobante'=>$ls_comprobante,'base'=>$li_base,'monto'=>$li_monsol,'montotdoc'=>$li_montotdoc,'retencion'=>$li_monret,'%retencion'=>$li_pormonret);
		}
		uf_print_detalle($la_data,&$io_pdf); // Imprimimos el detalle  
		unset($la_data);
		$li_totiva=number_format($li_totiva,2,',','.');
		$li_totret=number_format($li_totret,2,',','.');
		uf_print_totales($li_totrow,$li_totiva,$li_totret,$io_pdf);
		
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
	//	unset($io_pdf);
	}
//	unset($io_report);
//	unset($io_funciones);
//	unset($io_fun_cxp);
?> 
