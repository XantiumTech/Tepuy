<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Municipales
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
		//    Description: funciOn que guarda la seguridad de quien generO el reporte
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha CreaciOn: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="GenerO el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_r_retencionesislr.php",$ls_descripcion);
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
		//    Description: funciOn que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha CreaciOn: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,12,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,"ARTICULO 111 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS");
		$tm=396-($li_tm/2);
		$io_pdf->addText(125,100,12,'____________________________');
		$io_pdf->addText(522,100,12,'____________________________');
		$io_pdf->addText(132,86,12,'   AGENTE DE RETENCION');
		$io_pdf->addText(570,86,12,'	BENEFICIARIO');
		//$io_pdf->addText($tm,530,9,"ARTICULO 111 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS"); // Agregar el título
		//$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
// pie de pagina articulo de retencion de Impuesto Municipal
	$as_pie='BASE LEGAL: RETENCION DE IMPUESTO SOBRE LA RENTA. ';
	$as_pie1='Reglamento Parcial de la Ley de Impuesto sobre la Renta en Materia de Retenciones.';
	$as_pie2='(Gaceta Oficial Nro. 36.203 de fecha 12 de Mayo de 1.997).';
	$io_pdf->Rectangle(30,50,735,28);	
		$io_pdf->addText(32,65,8,"<b>".$as_pie."</b>".$as_pie1); // Agregar el t�ulo
		$io_pdf->addText(32,55,8,$as_pie2); // Agregar el t�ulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numcon,$ad_fecrep,$as_agenteret,$as_rifagenteret,$as_perfiscal,$as_licagenteret,$as_diragenteret,
							   $as_nomsujret,$as_rif,$as_numlic,$ai_estcmpret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_numcon // Número de Comprobante
		//	    		   ad_fecrep // Fecha del comprobante
		//	    		   as_agenteret // agente de RetenciOn
		//	    		   as_rifagenteret // Rif del Agente de RetenciOn
		//	    		   as_perfiscal // Período Fiscal
		//	    		   as_licagenteret // Número de licencia de agente de retenciOn
		//	    		   as_diragenteret // DirecciOn del agente de retenciOn
		//	    		   as_nomsujret // Nombre del sujeto retenido
		//	    		   as_rif // Rif del sujeto retenido
		//	    		   as_numlic // Número de Licencia del sujeto retenido
		//	    		   ai_estcmpret // Estatus del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciOn que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha CreaciOn: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->setStrokeColor(0,0,0);
		if($ai_estcmpret==2)
		{
		    $io_pdf->Rectangle(45,495,180,30);		
			$io_pdf->addText(90,505,15,"<b> ANULADO </b>"); 
		}	
		$io_pdf->ezSetY(525);
		/*$la_data[1]=array('name'=>'<b>NRO COMPROBANTE </b>');
		$la_data[2]=array('name'=>$as_numcon);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>500, // OrientaciOn de la tabla
						 'width'=>140, // Ancho de la tabla						 
						 'maxWidth'=>140,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>140))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);	*/							 
		$io_pdf->ezSetY(525);
		$la_data[1]=array('name'=>'<b>FECHA</b>');
		//$la_data[2]=array('name'=>date("d/m/Y"));
		$la_data[2]=array('name'=>$ad_fecrep);
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>730, // OrientaciOn de la tabla antes 630
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetY(490);
		$la_data[1]=array('name'=>'<b>NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION</b>');
		$la_data[2]=array('name'=>$as_agenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // OrientaciOn de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(490);
		$la_data[1]=array('name'=>'<b>REGISTRO DE INFORMACION FISCAL DEL AGENTE DE RETENCION (R.I.F.)</b>');
		$la_data[2]=array('name'=>$as_rifagenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>515, // OrientaciOn de la tabla
						 'width'=>320, // Ancho de la tabla						 
						 'maxWidth'=>320,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>320))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(490);
		$la_data[1]=array('name'=>'<b>PERIODO FISCAL</b>');
		$la_data[2]=array('name'=>$as_perfiscal);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>730, // OrientaciOn de la tabla
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(455);
		/*$la_data[1]=array('name'=>'<b>LICENCIA FUNCIONAMIENTO AGENTE DE RETENCION</b>');
		$la_data[2]=array('name'=>$as_licagenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // OrientaciOn de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(420);*/
		$la_data[1]=array('name'=>'<b>DIRECCION FISCAL DEL AGENTE DE RETENCION</b>');
		$la_data[2]=array('name'=>$as_diragenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>405, // OrientaciOn de la tabla
						 'width'=>740, // Ancho de la tabla						 
						 'maxWidth'=>740,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>740))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(420); //antes  385
		$la_data[1]=array('name'=>'<b>NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO</b>');
		$la_data[2]=array('name'=>$as_nomsujret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // OrientaciOn de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(420); //antes  385
		$la_data[1]=array('name'=>'<b>REGISTRO DE INFORMACION FISCAL DEL SUJETO RETENIDO (R.I.F.)</b>');
		$la_data[2]=array('name'=>$as_rif);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>515, // OrientaciOn de la tabla
						 'width'=>320, // Ancho de la tabla						 
						 'maxWidth'=>320,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>320))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$io_pdf->ezSetY(350);
		/*$la_data[1]=array('name'=>'<b>LICENCIA FUNCIONAMIENTO SUJETO RETENIDO</b>');
		$la_data[2]=array('name'=>$as_numlic);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // OrientaciOn de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);*/								 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totbasimp,$ai_totmonimp,$as_rifagenteret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible
		//	    		   as_rifagenteret // Rif del Agente de RetenciOn
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciOn que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha CreaciOn: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(365); //antes  315
		$la_data1[1]=array('numero'=>'<b>N.</b>',
						  'numsop'=>'<b>N. Orden de Pago</b>',
						  'fecfac'=>'<b>Fecha Factura</b>',
						  'numfac'=>'<b>Numero de Factura</b>',
  						  'numref'=>'<b>Num. Ctrol de Factura</b>',		
						  'baseimp'=>'<b>Monto de la Operacion</b>',
						  'porimp'=>'<b>Alicuota</b>',
						  'totimp'=>'<b>Impuesto Retenido</b>');
		$la_columna=array('numero'=>'<b>N.</b>',
						  'numsop'=>'<b>N. Orden de Pago</b>',
						  'fecfac'=>'<b>Fecha Factura</b>',
						  'numfac'=>'<b>Numero de Factura</b>',
  						  'numref'=>'<b>Num. Ctrol de Factura</b>',		
						  'baseimp'=>'<b>Monto de la Operacion</b>',
						  'porimp'=>'<b>Alicuota</b>',
						  'totimp'=>'<b>Impuesto Retenido</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // OrientaciOn de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>50), // Justificacion y ancho de la columna
									   'numsop'=>array('justification'=>'center','width'=>110),
						 			   'fecfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'numfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
									   'numref'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'baseimp'=>array('justification'=>'center','width'=>100),
						 			   'porimp'=>array('justification'=>'center','width'=>50),
   						 			   'totimp'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('numero'=>'<b>N.</b>',
						  'numsop'=>'<b>N. Orden de Pago</b>',
						  'fecfac'=>'<b>Fecha Factura</b>',
						  'numfac'=>'<b>Numero de Factura</b>',
  						  'numref'=>'<b>Num. Ctrol de Factura</b>',		
						  'baseimp'=>'<b>Monto de la Operacion</b>',
						  'porimp'=>'<b>Alicuota</b>',
						  'totimp'=>'<b>Impuesto Retenido</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // OrientaciOn de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>50), // Justificacion y ancho de la columna
									   'numsop'=>array('justification'=>'center','width'=>110),
						 			   'fecfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'numfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
									   'numref'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>100),
						 			   'porimp'=>array('justification'=>'right','width'=>50),
   						 			   'totimp'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('total'=>'<b>Total</b>',		
						   'monto'=>'<b>'.$ai_totbasimp.'</b>',
						   'imponible'=>'<b>'.$ai_totmonimp.'</b>');
		$la_columna=array('total'=>'<b>total</b>',		
						  'monto'=>'<b>monto</b>',		
						  'imponible'=>'<b>monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>360, // Ancho de la tabla
						 'maxWidth'=>360, // Ancho Mínimo de la tabla
						 'xPos'=>595, // OrientaciOn de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100),
   						 			   'imponible'=>array('justification'=>'right','width'=>150))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		/*$la_data1[1]=array('firma'=>'_________________________________________________');	
		$la_data1[2]=array('firma'=>'FIRMA Y SELLO DEL AGENTE DE RETENCION');	
		$la_data1[3]=array('firma'=>'RIF: '.$as_rifagenteret);	
		$la_columna=array('firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // OrientaciOn de la tabla
						 'cols'=>array('firma'=>array('justification'=>'center','width'=>250))); 
		$io_pdf->ezSetDy(-50);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);*/
	}// end function uf_print_detalle
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	$io_report=new tepuy_cxp_class_report();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_tiporeten=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='S'",'C');
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	if($ls_tiporeporte==1)
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO SOBRE LA RENTA</b>";
	}
	else
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO SOBRE LA RENTA</b>";
	}
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes=$io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_licagenteret=$_SESSION["la_empresa"]["numlicemp"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_comprobantes=split('-',$ls_comprobantes);
		$la_datos=array_unique($la_comprobantes);
		$li_totrow=count($la_datos);
		sort($la_datos,SORT_STRING);
		if($li_totrow<=0)
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
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$lb_valido=true;
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				$ls_numcom=$la_datos[$li_z];
				$lb_valido=$io_report->uf_retencionesmunicipales_proveedor($ls_numcom,$ls_tiporeten,$ls_mes,$ls_anio);
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numcom");
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numcon=$io_report->DS->data["numcom"][$li_i];		 								
						$ls_codret=$io_report->DS->data["codret"][$li_i];			   
						$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][$li_i]);
						$ls_perfiscal=$io_report->DS->data["perfiscal"][$li_i];						
						$ls_codsujret=$io_report->DS->data["codsujret"][$li_i];			     
						$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif=$io_report->DS->data["rif"][$li_i];	
						$ls_dirsujret=$io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret=$io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic=$io_report->DS->data["numlic"][$li_i];									
					}											
					uf_print_cabecera($ls_numcon,$ls_fecrep,$ls_agenteret,$ls_rifagenteret,$ls_perfiscal,$ls_licagenteret,
									  $ls_diragenteret,$ls_nomsujret,$ls_rif,$ls_numlic,$li_estcmpret,&$io_pdf);
					$lb_valido=$io_report->uf_retencionesislr_detalles($ls_numcom,$ls_tiporeten);
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$li_total=$io_report->ds_detalle->getRowCount("numficha");		   
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{
							$ls_numsop=$io_report->ds_detalle->data["numsop"][$li_i];					
							$ld_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecfac"][$li_i]);
							$ls_numficha=$io_report->ds_detalle->data["numficha"][$li_i];
							$ls_numfac=$io_report->ds_detalle->data["numfac"][$li_i];
							$ls_numref=$io_report->ds_detalle->data["numcon"][$li_i];	              
							$li_baseimp=$io_report->ds_detalle->data["basimp"][$li_i];	
							$li_porimp=$io_report->ds_detalle->data["porimp"][$li_i];	
							$li_totimp=$io_report->ds_detalle->data["totimp"][$li_i];	

							$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp=$li_totalmontoimp + $li_totimp;	
							$li_baseimp=number_format($li_baseimp,2,",",".");			
							$li_porimp=number_format($li_porimp,4,",",".");			
							$li_totimp=number_format($li_totimp,2,",",".");							
							$la_data[$li_i]=array('numero'=>$li_i,'numsop'=>$ls_numsop, 'fecfac'=>$ld_fecfac,'numfac'=>$ls_numfac,
												  'numref'=>$ls_numref,'baseimp'=>$li_baseimp,'porimp'=>$li_porimp,'totimp'=>$li_totimp);														
						  }																		 																						  
  						  $li_totalbaseimp= number_format($li_totalbaseimp,2,",","."); 
  						  $li_totalmontoimp= number_format($li_totalmontoimp,2,",","."); 
						  uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$ls_rifagenteret,&$io_pdf); 						 						  
						  unset($la_data);							 
					}
				}
				$io_report->DS->reset_ds();
				if($li_z<($li_totrow-1))
				{
					$io_pdf->ezNewPage(); 					  
				}		

			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresiOn de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else  // Si hubo algún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresiOn de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
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