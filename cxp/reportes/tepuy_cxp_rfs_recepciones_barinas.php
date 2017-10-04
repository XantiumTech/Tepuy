<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Recepciones de Documentos
//  ORGANISMO: ALCALDIA DEL MUNICIPIO BARINAS
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
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_p_recepcioncontable.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->line(480,700,480,760);
		$io_pdf->line(480,730,585,730);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(485,740,9,"No. de Ficha".$as_numsol); // Agregar el t�tulo
		$io_pdf->addText(485,710,9,"Fecha ".$ad_fecregsol); // Agregar el t�tulo
		//$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
		$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el t�tulo
		$io_pdf->addText(42,63,7,"FIRMA / SELLO"); // Agregar el t�tulo
		$io_pdf->addText(157,122,7,"VERIFICADO POR"); // Agregar el t�tulo
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el t�tulo
		$io_pdf->addText(257,63,7,"ADMINISTRACI�N Y FINANZAS"); // Agregar el t�tulo
		$io_pdf->addText(440,122,7,"CONTRALORIA INTERNA"); // Agregar el t�tulo
		$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numrecdoc,$as_dentipdoc,$as_nombre,$as_proben,$ad_fecemidoc,$ad_fecrecdoc,$as_dencondoc,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numrecdoc // Numero de la Recepcion de Documentos
		//	   			   as_dentipdoc // Denominacion de tipo de documento
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_proben    // Indica si es  Proveedor / Beneficiario
		//	   			   ad_fecemidoc // Fecha de Emision de la Factura
		//	   			   ad_fecrecdoc // Fecha de recepcion del documento
		//	   			   as_dencondoc // Concepto de la Recepcion de Documentos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por concepto
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_proben=="P")
		{
			$la_data[1]=array('titulo'=>'<b>Proveedor:</b>','contenido'=>$as_nombre);
		}
		else
		{
			$la_data[1]=array('titulo'=>'<b>Beneficiario:</b>','contenido'=>$as_nombre);
		}
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Documento:</b>','contenido'=>$as_dentipdoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Fecha de Emision:</b>','contenido'=>$ad_fecemidoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Concepto:</b>','contenido'=>$as_dencondoc,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($la_data,$ai_totsubtot,$ai_tottot,$ai_totcar,$ai_totded,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//				   ai_totsubtot // acumulado del subtotal
		//				   ai_tottot // acumulado del total
		//				   ai_totcar // acumulado de los cargos
		//				   ai_totded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle de la recepcion de documentos
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numrecdoc'=>'<b>Factura</b>','fecemisol'=>'<b>Fecha</b>','subtotdoc'=>'<b>Monto</b>',
							 'moncardoc'=>'<b>Cargos</b>','mondeddoc'=>'<b>Deducciones</b>','montotdoc'=>'<b>Total</b>');
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificaci�n y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>92))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificaci�n y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('numrecdoc'=>'<b>Totales</b>','subtotdoc'=>$ai_totsubtot,
							 'moncardoc'=>$ai_totcar,'mondeddoc'=>$ai_totded,'montotdoc'=>$ai_tottot);
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'right','width'=>200), // Justificaci�n y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ai_totpre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de informaci�n
		//	    		   ai_totpre // monto total de presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle presupuestario
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest;
		if($ls_estmodest==1)
		{
			$ls_titcuentas="Estructura Presupuestaria";
		}
		else
		{
			$ls_titcuentas="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('numrecdoc'=>'<b>Compromiso</b>',
						   'codestpro'=>'<b>'.$ls_titcuentas.'</b>',
						   'spg_cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
									   'codestpro'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>110))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Totales</b>','totpre'=>'<b>'.$ai_totpre.'</b>');
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totpre'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>460), // Justificaci�n y ancho de la columna
						 			   'totpre'=>array('justification'=>'right','width'=>110))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,$ai_totdeb,$ai_tothab,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de informaci�n
		//	    		   si_totdeb // total monto debe
		//	    		   si_tothab // total monto haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle contable
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Contable </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('numrecdoc'=>'<b>Compromiso</b>',
						   'sc_cuenta'=>'<b>Cuenta</b>',
						   'densc_cuenta'=>'<b>Denominacion</b>',
						   'mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'sc_cuenta'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'densc_cuenta'=>array('justification'=>'center','width'=>180), // Justificaci�n y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Totales</b>',
							 'totdeb'=>'<b>'.$ai_totdeb.'</b>',
							 'tothab'=>'<b>'.$ai_tothab.'</b>');
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totdeb'=>'<b>Deducciones</b>',
						   'tothab'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>370), // Justificaci�n y ancho de la columna
						 			   'totdeb'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'tothab'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ai_montotdoc,$ai_mondeddoc,$ai_moncardoc,$ai_monsubdoc,$ai_montotcar,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_montotdoc // Monto Total del Documento
		//	   			   ai_mondeddoc // Monto Deduccion del Documento
		//	   			   ai_moncardoc // Monto Cargos del Documento
		//	   			   ai_monsubdoc // Monto Sub-Total (Sin Cargos ni Deducciones)
		//	   			   ai_montotcar // Monto Sub-Total Incluyendo Cargos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los montos totales del documento
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','contenido'=>$ai_monsubdoc);
		$la_data[2]=array('titulo'=>'<b>I.V.A.</b>','contenido'=>$ai_moncardoc);
		$la_data[3]=array('titulo'=>'<b>Total</b>','contenido'=>$ai_montotcar);
		$la_data[4]=array('titulo'=>'<b>Retenciones</b>','contenido'=>$ai_mondeddoc);
		$la_data[5]=array('titulo'=>'<b>Total General</b>','contenido'=>$ai_montotdoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>460), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>110))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	$io_report=new tepuy_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//Instancio a la clase de conversi�n de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
		
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RECEPCION DE DOCUMENTOS</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor_get("numrecdoc","");
	$ls_codpro=$io_fun_cxp->uf_obtenervalor_get("codpro","");
	$ls_cedben=$io_fun_cxp->uf_obtenervalor_get("cedben","");
	$ls_codtipdoc=$io_fun_cxp->uf_obtenervalor_get("codtipdoc","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_cxp_class_reportbsf.php");
		$io_report=new tepuy_cxp_class_reportbsf();
	}
	else
	{
		require_once("tepuy_cxp_class_report.php");
		$io_report=new tepuy_cxp_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_recepcion($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe alg�n error � no hay registros
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
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina
			$li_totrow=$io_report->DS->getRowCount("numrecdoc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numrecdoc=$io_report->DS->data["numrecdoc"][$li_i];
				$ls_dentipdoc=$io_report->DS->data["dentipdoc"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecemidoc=$io_report->DS->data["fecemidoc"][$li_i];
				$ld_fecrecdoc=$io_report->DS->data["fecregdoc"][$li_i];
				$ls_dencondoc=$io_report->DS->data["dencondoc"][$li_i];
				$li_montotdoc=$io_report->DS->data["montotdoc"][$li_i];
				$li_mondeddoc=$io_report->DS->data["mondeddoc"][$li_i];
				$li_moncardoc=$io_report->DS->data["moncardoc"][$li_i];
				$li_monsubdoc=($li_montotdoc-$li_moncardoc+$li_mondeddoc);
				$li_montotcar=($li_montotdoc+$li_mondeddoc);
				$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
				$ld_fecrecdoc=$io_funciones->uf_convertirfecmostrar($ld_fecrecdoc);
				uf_print_encabezado_pagina($ls_titulo,$ls_numrecdoc,$ld_fecrecdoc,&$io_pdf);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
					uf_print_cabecera($ls_numrecdoc,$ls_dentipdoc,$ls_nombre,"P",$ld_fecemidoc,$ld_fecrecdoc,$ls_dencondoc,&$io_pdf);
				}
				else
				{
					$ls_codigo=$ls_cedben;
					uf_print_cabecera($ls_numrecdoc,$ls_dentipdoc,$ls_nombre,"B",$ld_fecemidoc,$ld_fecrecdoc,$ls_dencondoc,&$io_pdf);
				}						
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionspg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
					$la_data="";
					$li_totpre=0;
					for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
					{
						$ls_codestpro=$io_report->ds_detalle_spg->data["codestpro"][$li_s];
						//$ls_denestpro=$io_report->ds_detalle_spg->data["denominacion"][$li_s];
						$ls_spgcuenta=$io_report->ds_detalle_spg->data["spg_cuenta"][$li_s];
						$ls_spgcuentaoriginal=$ls_spgcuenta;
						$ls_numrecdoc=$io_report->ds_detalle_spg->data["numrecdoc"][$li_s];
						$ls_numdoccom=$io_report->ds_detalle_spg->data["numdoccom"][$li_s];
						$li_monto=$io_report->ds_detalle_spg->data["monto"][$li_s];
						$li_totpre=$li_totpre+$li_monto;
						$li_monto=number_format($li_monto,2,",",".");
					// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
						$ls_spg_anterior=$ls_spgcuenta;
						if(substr($ls_spg_anterior,9,4)=="0000")
						{
						$ls_spgcuenta=substr($ls_spgcuenta,0,9);
						}
						if(substr($ls_spgcuenta,7,2)=="00")
						{
						$ls_spg_anterior=$ls_spgcuenta;
						$ls_spgcuenta=substr($ls_spgcuenta,0,7);
						}
						if(substr($ls_spgcuenta,5,2)=="00")
						{
						$ls_spgcuenta=substr($ls_spgcuenta,0,5);
						}
						if(substr($ls_spgcuenta,3,2)=="00")
						{
						$ls_spgcuenta=substr($ls_spgcuenta,0,3);
						}
					// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //
					// PERMITE AGREGARLE PUNTOS A LA CUENTA PRESUPUESTARIA //
						$hasta=strlen($ls_spgcuenta);
						//$ls_spgcuenta=$ls_spgcuenta;
						if($hasta==13)
						{
							$ls_spgcuenta1=substr($ls_spgcuenta,0,3).".".substr($ls_spgcuenta,3,2).".".substr($ls_spgcuenta,5,2).".".substr($ls_spgcuenta,7,2).".".substr($ls_spgcuenta,9,4);
							$ls_partida=substr($ls_spgcuenta,0,3);
							$ls_generica=substr($ls_spgcuenta,3,2);
							$ls_especifica=substr($ls_spgcuenta,5,2);
							$ls_subespecifica=substr($ls_spgcuenta,7,2);
							$ls_ordinal=substr($ls_spgcuenta,9,4);
						}
						if($hasta==9)
						{
							$ls_spgcuenta1=substr($ls_spgcuenta,0,3).".".substr($ls_spgcuenta,3,2).".".substr($ls_spgcuenta,5,2).".".substr($ls_spgcuenta,7,2);
							$ls_partida=substr($ls_spgcuenta,0,3);
							$ls_generica=substr($ls_spgcuenta,3,2);
							$ls_especifica=substr($ls_spgcuenta,5,2);
							$ls_subespecifica=substr($ls_spgcuenta,7,2);
							$ls_ordinal="";
						}
						if($hasta==7)
						{
							$ls_spgcuenta1=substr($ls_spgcuenta,0,3).".".substr($ls_spgcuenta,3,2).".".substr($ls_spgcuenta,5,2);
							$ls_partida=substr($ls_spgcuenta,0,3);
							$ls_generica=substr($ls_spgcuenta,3,2);
							$ls_especifica=substr($ls_spgcuenta,5,2);
							$ls_subespecifica="";
							$ls_ordinal="";
						}
						$ls_spgcuenta=$ls_spgcuenta1;
						// AGREGA . A LA CTA. PRESUPUESTARIA//

						if($ls_estmodest==1)
						{
							$ls_codestpro1 = substr($ls_codestpro,0,20);	
							$ls_codestpro2 = substr($ls_codestpro,20,6);
							$ls_codestpro3 = substr($ls_codestpro,26,3);
							$ls_codestpro1 = substr($ls_codestpro,18,2);
							$ls_codestpro1old = substr($ls_codestpro,0,20);	
							$ls_codestpro2old = substr($ls_codestpro,20,6);
							$ls_codestpro3old = substr($ls_codestpro,26,3);
							$ls_codestpro2 = substr($ls_codestpro,24,2);
							$ls_codestpro3 = substr($ls_codestpro,27,2);
							$ls_codestpro4 = substr($ls_codestpro,29,2);
							$ls_codestpro5 = substr($ls_codestpro,31,2);
		$ejecuto="SELECT denominacion FROM spg_cuentas WHERE codestpro1='$ls_codestpro1old' AND codestpro2='$ls_codestpro2old' and codestpro3='$ls_codestpro3old' AND codestpro4='$ls_codestpro4' AND codestpro5='$ls_codestpro5' AND spg_cuenta='$ls_spgcuentaoriginal'";
$denominacion=$io_report->uf_obtengo_denominacion($ejecuto);	
							$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'codestpro'=>$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3,
												  'spg_cuenta'=>$ls_spgcuenta,'denominacion'=>$denominacion,'monto'=>$li_monto);
						}
						else
						{
							$ls_codestpro1 = substr($ls_codestpro,18,2);	
							$ls_codestpro2 = substr($ls_codestpro,24,2);
							$ls_codestpro3 = substr($ls_codestpro,27,2);
							$ls_codestpro4 = substr($ls_codestpro,29,2);
							$ls_codestpro5 = substr($ls_codestpro,31,2);
							$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'codestpro'=>$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3." - ".$ls_codestpro4." - ".$ls_codestpro5,
												  'spg_cuenta'=>$ls_spgcuenta,'denominacion'=>$denominacion,'monto'=>$li_monto);
						}
					}	
					$li_totpre=number_format($li_totpre,2,",",".");
					uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
					unset($la_data);
				}
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
//////////////////////////      GRID DETALLE CONTABLE	   	//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionscg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowscg=$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
					$la_data="";
					$li_totdeb=0;
					$li_tothab=0;
					for($li_s=1;$li_s<=$li_totrowscg;$li_s++)
					{
						$ls_sccuenta=trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
						$ls_densccuenta=trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
						$ls_debhab=trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
						$ls_numrecdoc=trim($io_report->ds_detalle_scg->data["numrecdoc"][$li_s]);
						$li_monto=$io_report->ds_detalle_scg->data["monto"][$li_s];
						$ls_numdoccom=$io_report->ds_detalle_scg->data["numdoccom"][$li_s];
						if($ls_debhab=="D")
						{
							$li_montodebe=$li_monto;
							$li_montohab="";
							$li_totdeb=$li_totdeb+$li_montodebe;
							$li_montodebe=number_format($li_montodebe,2,",",".");
						}
						else
						{
							$li_montodebe="";
							$li_montohab=$li_monto;
							$li_tothab=$li_tothab+$li_montohab;
							$li_montohab=number_format($li_montohab,2,",",".");
						}
						$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'sc_cuenta'=>$ls_sccuenta,'densc_cuenta'=>$ls_densccuenta,
											  'mondeb'=>$li_montodebe,'monhab'=>$li_montohab);
					}	
					$li_totdeb=number_format($li_totdeb,2,",",".");
					$li_tothab=number_format($li_tothab,2,",",".");
					uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
					unset($la_data);
				}
				$li_montotdoc=number_format($li_montotdoc,2,",",".");
				$li_mondeddoc=number_format($li_mondeddoc,2,",",".");
				$li_moncardoc=number_format($li_moncardoc,2,",",".");
				$li_monsubdoc=number_format($li_monsubdoc,2,",",".");
				$li_montotcar=number_format($li_montotcar,2,",",".");
				uf_print_totales($li_montotdoc,$li_mondeddoc,$li_moncardoc,$li_monsubdoc,$li_montotcar,&$io_pdf);
			}
		}
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo alg�n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}




?>