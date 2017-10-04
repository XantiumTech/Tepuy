<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Orden de Pago
//  ORGANISMO: ALCALDIA DEL MUNICIPO BARINAS
//		FORMATO PRE-IMPRESO
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_p_solicitudpago.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,$as_nompro,$as_dirpro,$as_estatus,$as_denfuefin,$as_consol,$as_obssol,&$io_pdf)
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
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->Rectangle(130,710,455,40);
		//$io_pdf->line(430,730,585,730);		
		//$io_pdf->line(430,750,430,710);	
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$li_tm=$io_pdf->getTextWidth(13,$as_titulo);
		//$tm=296-($li_tm/2);
		//$io_pdf->addText($tm,730,13,$as_titulo); // Agregar el t�tulo
		//$io_pdf->addText(443,732,11,"Nro.:     ".$as_numsol); // N�mero de Orden de compra
		//$io_pdf->addText(443,712,11,"Fecha:  ".$ad_fecregsol); // Agregar la Fecha
		//$io_pdf->addText(510,760,7,date("d/m/Y")); // Agregar la Fecha
		
		/*$io_pdf->Rectangle(20,45,570,110);
		$io_pdf->line(20,135,590,135);		
		$io_pdf->line(20,94,590,94);		
		$io_pdf->line(20,80,590,80);		
		$io_pdf->line(213,94,213,155);		
		$io_pdf->line(400,94,400,155);		
		$io_pdf->line(304,45,304,94);		
		$io_pdf->addText(70,138,7,"CUENTAS POR PAGAR");
		$io_pdf->addText(275,138,7,"PRESUPUESTO");
		$io_pdf->addText(465,138,7,"CONTABILIDAD");
		$io_pdf->addText(80,84,7,"GERENCIA DE ADMINISTRACI�N Y FINANZAS");
		$io_pdf->addText(405,84,7,"GERENCIA GENERAL");*/

 		/*$io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
		$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el t�tulo
		$io_pdf->addText(42,63,7,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->addText(157,122,7,"CONTABILIDAD"); // Agregar el t�tulo
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el t�tulo
		$io_pdf->addText(257,63,7,"ADMINISTRACI�N Y HACIENDA"); // Agregar el t�tulo
		$io_pdf->addText(440,122,7,"DESPACHO DEL ALCALDE"); // Agregar el t�tulo
		$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo*/
		
		$io_pdf->ezSetY(520);
		$la_data=array(array('name'=>'<b></b>'.$as_nompro),	   		               
					   array('name'=>'<b></b>'.$as_consol));			
					
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0,
						 'fontSize' => 9,
						 'showLines'=>0,
						 'shaded'=>0,
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'xOrientation'=>'center',
						 'rowGap'=>9, // Espacio entre Lineas
						 'width'=>570,
						 'maxWidth'=>570);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
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
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numrecdoc'=>'<b>Nro. del DOCUMENTO</b>','fecemisol'=>'<b>FECHA DEL DOCUMENTO</b>','subtotdoc'=>'<b>MONTO</b>',
							 'moncardoc'=>'<b>IMPUESTO</b>','mondeddoc'=>'<b>DEDUCCI�N</b>','montotdoc'=>'<b>TOTAL</b>');
		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','subtotdoc'=>'','moncardoc'=>'','mondeddoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=> 9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificaci�n y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>92), // Justificaci�n y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>92))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'<b>RECEPCIONES DE DOCUMENTOS</b>',$la_config);

		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','subtotdoc'=>'','moncardoc'=>'','mondeddoc'=>'','montotdoc'=>'');
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
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificaci�n y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
		global $ls_tiporeporte;
		if($ls_estmodest==1){$ls_titcuentas="ESTRUCTURA PRESUPUESTARIA";}
		else{$ls_titcuentas="Estructura Programatica";}
		if($ls_tiporeporte==1){$ls_titulo=" Bs.F.";}else{$ls_titulo=" Bs.";}
		
		/*$la_datatit[1]=array('titulo'=>'<b>CUENTAS PRESUPUESTARIAS</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		
		$la_data[1]  = array('codestpro'=>'<b>'.$ls_titcuentas.'</b>',
						     'spg_cuenta'=>'<b>CUENTA PRESUPUESTARIA</b>',
						     'denominacion'=>'<b>DENOMINACI�N</b>',
						     'monto'=>'<b>MONTO</b>');
		$la_columnas=array('codestpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>185), // Justificaci�n y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>95), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);*/
	
	//$la_columnas=array('codestpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
	$la_columnas=array('codestpro1'=>'','codestpro2'=>'','codestpro3'=>'','partida'=>'','generica'=>'','especifica'=>'','subespecifica'=>'','ordinal'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>320, // Ancho de la tabla
						 'maxWidth'=>320, // Ancho M�ximo de la tabla
						 'colGap'=>0,
						 'xPos'=>210, // sepaacion del margen
						 //'xOrientation'=>'left', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>6.5,
						 'cols'=>array('codestpro1'=>array('justification'=>'center','width'=>25), // Justificaci�n y ancho de la columna
								'codestpro2'=>array('justification'=>'center','width'=>25),
								'codestpro3'=>array('justification'=>'center','width'=>25),
								'partida'=>array('justification'=>'center','width'=>35), // Justificaci�n y ancho de la columna
								'generica'=>array('justification'=>'center','width'=>25),
								'especifica'=>array('justification'=>'center','width'=>25),
								'subespecifica'=>array('justification'=>'center','width'=>25),
								'ordinal'=>array('justification'=>'center','width'=>40),
								'monto'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	
	
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
		// Fecha Creaci�n: 27/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$la_datatit[1]=array('titulo'=>'<b>CUENTAS CONTABLES</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('sc_cuenta'=>'<b>CUENTA CONTABLE</b>',
		                  'denominacion'=>'<b>DENOMINACI�N</b>',
						  'mondeb'=>'<b>DEBE</b>',
						  'monhab'=>'<b>HABER</b>');
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>290), // Justificaci�n y ancho de la columna
						 			   'mondeb'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'monhab'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>290), // Justificaci�n y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		
		$la_datatot[1]=array('titulo'=>'<b>Totales '.$ls_titulo.'</b>','totdeb'=>$ai_totdeb,'tothab'=>$ai_tothab);
		$la_columnas=array('titulo'=>'','totdeb'=>'','tothab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>390), // Justificaci�n y ancho de la columna
						 			   'totdeb'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'tothab'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_carded($la_datacar,$deducciones,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_recepciones
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$io_pdf->ezSetDy(-110);							
        $la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9,  // Tama�o de Letras
						 'showLines'=>0,    // Mostrar L�neas
						 'shaded'=>0,       // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>900,      // Ancho de la tabla						 
						 'maxWidth'=>900);  // Ancho M�nimo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		//-----------------------------------------------------------------------------------------------------------------																												
		$la_columnacar=array('denominacion'=>'<b>Denominacion</b>',						  
						     'monobjret'=>'<b>Monto Objeto Retencion y/o Base Imponible</b>',
							 'objret'=>'<b>Retencion y/o Impuesto</b>');
						 
		$la_configcar=array('showHeadings'=>1, // Mostrar encabezados
						    'fontSize' =>6, // Tama�o de Letras
						    'titleFontSize' =>7,  // Tama�o de Letras de los t�tulos
						    'showLines'=>1, // Mostrar L�eas
						    'shaded'=>0, // Sombra entre l�neas
						    'width'=>220, // Ancho de la tabla
						    'maxWidth'=>220, // Ancho M�nimo de la tabla
						    'xPos'=>495, // Orientaci�n de la tabla
						    'cols'=>array('denominacion'=>array('justification'=>'center','width'=>90),
						 			      'monobjret'=>array('justification'=>'center','width'=>70),
						 			      'objret'=>array('justification'=>'right','width'=>60)));
		$io_pdf->ezTable($la_datacar,$la_columnacar,'<b>TOTAL RETENCIONES:      Bs.  '.$deducciones.'</b>',$la_configcar);			
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_bsf($ai_monsolaux,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: ai_monsolaux // Monto Auxiliar en Bs.F.
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funcion que imprime el monto total de la solicitud en Bs.F.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 26/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(+250);
		//$la_datatot[1]=array('titulo'=>'<b>Total A Pagar Bs.</b>','monto'=>$ai_monsolaux);
		$la_datatot[1]=array('titulo'=>'','monto'=>'<b>'.$ai_monsolaux.'</b>');
		$la_columnas=array('titulo'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 //'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>285, // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>480), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_total_bsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_monto_letras($as_monto,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: as_monto : Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data=array(array('1'=>' ','2'=>' ','monto'=>'','4'=>' '),array('1'=>' ','2'=>' ','monto'=>'','4'=>' '));
		$la_columna=array('1'=>' ','2'=>' ','monto'=>'','4'=>' ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'fontSize' =>8, // Tama�o de Letras
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('1'=>array('justification'=>'center','width'=>100),'2'=>array('justification'=>'center','width'=>190),
						 'monto'=>array('justification'=>'center','width'=>150),'4'=>array('justification'=>'center','width'=>50))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('data'=>"<b>".$as_monto."</b>")
                       );
		$la_columna=array('data'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' =>8, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientaci�n de la tabla
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600,
						 'cols'=>array('data'=>array('justification'=>'center','width'=>550))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_cxp.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_numero_a_letra.php");
	
	$io_funciones = new class_funciones();				
	$io_fun_cxp   = new class_funciones_cxp();
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	//Instancio a la clase de conversi�n de numeros a letras.
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
		$ls_titcuentas="Estructura Program�tica";
	}
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>ORDEN DE PAGO</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_numsol      = $io_fun_cxp->uf_obtenervalor_get("numsol","");
	$ls_tiporeporte = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
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

		$lb_valido=$io_report->uf_select_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
//print $ls_numsol;
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
			$io_pdf->ezSetCmMargins(7.5,6,3.3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,36,7,'','',1); // Insertar el n�mero de p�gina
			$li_totrow=$io_report->DS->getRowCount("numsol");//print_r($io_report->DS->data);
			$ld_total_deducciones=0;
			for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			    {
				  $ls_numsol    = $io_report->DS->data["numsol"][$li_i];
				  $ls_denfuefin = $io_report->DS->data["denfuefin"][$li_i];
				  if ($ls_denfuefin=='---seleccione---'){$ls_denfuefin='N/D';};
				  $ls_nompro    = $io_report->DS->data["nombre"][$li_i];
				  $ls_dirpro    = $io_report->DS->data["dirproben"][$li_i];
				  $ld_fecemisol = trim($io_report->DS->data["fecemisol"][$li_i]);
				  $ls_consol	= $io_report->DS->data["consol"][$li_i];
				  $ls_obssol    = $io_report->DS->data["obssol"][$li_i];
				  $li_monsol    = $io_report->DS->data["monsol"][$li_i];
				  $ls_estprosol = $io_report->DS->data["estprosol"][$li_i];
				  if($ls_estprosol=="E"){
					  $ls_estprosol="Emitida";
				  }	
				  if($ls_estprosol=="C"){
					  $ls_estprosol="Contabilizada";
				  }	
				  if($ls_estprosol=="A"){
					  $ls_estprosol="Anulada";
				  }	
				  if($ls_estprosol=="S"){
					  $ls_estprosol="Programaci�n de Pago";
				  }	
				  if($ls_estprosol=="P"){
					  $ls_estprosol="Pagada";
				  }
				  
				  $numalet->setNumero($li_monsol);
				  $ls_monto     = $numalet->letra();
				  $li_monsol    = number_format($li_monsol,2,",",".");
				  $ld_fecemisol = $io_funciones->uf_convertirfecmostrar($ld_fecemisol);
            	  if ($ls_tiporeporte==0)
				     {
					   $li_monsolaux=$io_report->DS->data["monsolaux"][$li_i];
					   $li_monsolaux=number_format($li_monsolaux,2,",",".");
				     }
				uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecemisol,$ls_nompro,$ls_dirpro,$ls_estprosol,$ls_denfuefin,$ls_consol,$ls_obssol,&$io_pdf);
                //////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_rec_doc_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle_rec->getRowCount("numrecdoc");
					$la_data="";
					$li_totsubtot=0;
					$li_tottot=0;
					$li_totcar=0;
					$li_totded=0;
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_numrecdoc	 = trim($io_report->ds_detalle_rec->data["numrecdoc"][$li_s]);
						$ld_fecemidoc    = $io_report->ds_detalle_rec->data["fecemidoc"][$li_s];
						$ls_numdoccomspg = trim($io_report->ds_detalle_rec->data["numdoccomspg"][$li_s]);
						$li_mondeddoc	 = $io_report->ds_detalle_rec->data["mondeddoc"][$li_s];
						$li_moncardoc	 = $io_report->ds_detalle_rec->data["moncardoc"][$li_s];
						$li_montotdoc	 = $io_report->ds_detalle_rec->data["montotdoc"][$li_s];
						$li_subtotdoc	 = ($li_montotdoc-$li_moncardoc+$li_mondeddoc);
						$li_totsubtot	 = $li_totsubtot + $li_subtotdoc;
						$li_tottot		 = $li_tottot + $li_montotdoc;
						$li_totcar		 = $li_totcar + $li_moncardoc;
						$li_totded		 = $li_totded + $li_mondeddoc;

						$ld_fecemidoc   = trim($io_funciones->uf_convertirfecmostrar($ld_fecemidoc));
						$li_mondeddoc   = number_format($li_mondeddoc,2,",",".");
						$li_moncardoc   = number_format($li_moncardoc,2,",",".");
						$li_montotdoc   = number_format($li_montotdoc,2,",",".");
						$li_subtotdoc   = number_format($li_subtotdoc,2,",",".");
						$la_data[$li_s] = array('numrecdoc'=>$ls_numrecdoc,'fecemisol'=>$ld_fecemidoc,'mondeddoc'=>$li_mondeddoc,
											    'moncardoc'=>$li_moncardoc,'montotdoc'=>$li_montotdoc,'subtotdoc'=>$li_subtotdoc);
					}
					
					$li_totsubtot=number_format($li_totsubtot,2,",",".");
					$li_tottot=number_format($li_tottot,2,",",".");
					$li_totcar=number_format($li_totcar,2,",",".");
					$li_totded=number_format($li_totded,2,",",".");
					//uf_print_detalle_recepcion($la_data,$li_totsubtot,$li_tottot,$li_totcar,$li_totded,&$io_pdf);
					unset($la_data);
					//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
					//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_spg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
						$la_data="";
						$li_totpre=0;
						for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
						{
							$ls_codestpro    = $io_report->ds_detalle_spg->data["codestpro"][$li_s];
							$ls_spgcuenta    = trim($io_report->ds_detalle_spg->data["spg_cuenta"][$li_s]);
							$ls_denominacion = $io_report->ds_detalle_spg->data["denominacion"][$li_s];
							$li_monto		 = $io_report->ds_detalle_spg->data["monto"][$li_s];
							$li_totpre		 = $li_totpre+$li_monto;
							$li_monto		 = number_format($li_monto,2,",",".");
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
								$ls_codestpro2 = substr($ls_codestpro,24,2);
								$ls_codestpro3 = substr($ls_codestpro,27,2);
								$ls_codestpro4 = substr($ls_codestpro,29,2);
								$ls_codestpro5 = substr($ls_codestpro,31,2);
							//	$la_data[$li_s]= array('codestpro'=>$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3,'spg_cuenta'=>$ls_spgcuenta,
							//						   'denominacion'=>$ls_denominacion,'monto'=>$li_monto);
		$la_data[$li_s]=array('codestpro1'=>$ls_codestpro1,'codestpro2'=>$ls_codestpro2,'codestpro3'=>$ls_codestpro3,'partida'=>$ls_partida,'generica'=>$ls_generica,'especifica'=>$ls_especifica,'subespecifica'=>$ls_subespecifica,'ordinal'=>$ls_ordinal,'monto'=>$li_monto);
							}
							else
							{
								$ls_codestpro1 = substr($ls_codestpro,18,2);	
								$ls_codestpro2 = substr($ls_codestpro,24,2);
								$ls_codestpro3 = substr($ls_codestpro,27,2);
								$ls_codestpro4 = substr($ls_codestpro,29,2);
								$ls_codestpro5 = substr($ls_codestpro,31,2);
								$ls_titcuentas="Estructura Programatica";
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3." - ".$ls_codestpro4." - ".$ls_codestpro5,'spg_cuenta'=>$ls_spgcuenta,
													  'denominacion'=>$ls_denominacion,'monto'=>$li_monto);
						
							}
						}	
						$li_totpre=number_format($li_totpre,2,",",".");
						uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
						unset($la_data);
					}
					//////////////////////////      GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					//////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_scg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$io_report->ds_detalle_scg->group_by(array('0'=>'sc_cuenta','1'=>'debhab'),array('0'=>'monto'),'monto');	
						$li_totrowscg = $io_report->ds_detalle_scg->getRowCount("sc_cuenta");
						$la_data="";
						$li_totdeb=0;
						$li_tothab=0;
						for($li_s=1;$li_s<=$li_totrowscg;$li_s++)
						{
							$ls_sccuenta	 = trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
							$ls_debhab		 = trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
							$ls_denominacion = trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
							$li_monto		 = $io_report->ds_detalle_scg->data["monto"][$li_s];
							if($ls_debhab=="D")
							{
								$li_montodebe = $li_monto;
								$li_montohab  = "";
								$li_totdeb    = $li_totdeb+$li_montodebe;
								$li_montodebe = number_format($li_montodebe,2,",",".");
							}
							else
							{
								$li_montodebe = "";
								$li_montohab  = $li_monto;
								$li_tothab    = $li_tothab+$li_montohab;
								$li_montohab  = number_format($li_montohab,2,",",".");
							}
							$la_data[$li_s]=array('sc_cuenta'=>$ls_sccuenta,'denominacion'=>$ls_denominacion,
												  'mondeb'=>$li_montodebe,'monhab'=>$li_montohab);
						}	
						$li_totdeb=number_format($li_totdeb,2,",",".");
						$li_tothab=number_format($li_tothab,2,",",".");
						//uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
						//unset($la_data);
					}
                    
					//////////////////////////         GRID DETALLE OTROS CREDITOS	    //////////////////////////////////////
				    $li_a = 0;
					$lb_validocar=$io_report->uf_select_sol_cargos($ls_codemp,$ls_numsol);
					/*if ($lb_validocar)
					   {
					     $li_totdet = $io_report->ds_car_dt->getRowCount("numsol");								     
						 for ($li_a=1;$li_a<=$li_totdet;$li_a++)
						     {										  													  
							   $ls_codcar         = $io_report->ds_car_dt->data["codcar"][$li_a]; 
							   $ls_dencar         = $io_report->ds_car_dt->data["dencar"][$li_a];
							   $ld_monobjret      = $io_report->ds_car_dt->data["monobjretcar"][$li_a];
							   $ld_objret         = $io_report->ds_car_dt->data["objretcar"][$li_a];
							   $ld_monobjret      = number_format($ld_monobjret,2,",",".");	
							   $ld_objret         = number_format($ld_objret,2,",",".");	
							   $la_datacar[$li_a] = array('codigo'=>$ls_codcar,'denominacion'=>$ls_dencar,'monobjret'=>$ld_monobjret,'objret'=>$ld_objret);
							 }							  							   
					   }*/
						//-------------------------------DEDUCCIONES--------------------------------------
						$li_totdet=0;
						$lb_validoded = $io_report->uf_select_sol_deducciones($ls_codemp,$ls_numsol);
						if ($lb_validoded)
						   {
						     $li_totdet = $io_report->ds_ded_dt->getRowCount("numsol");								     
							 for ($li_x=1;$li_x<=$li_totdet;$li_x++)
							     {										
                                   $li_a++;
								   $ls_codded         = $io_report->ds_ded_dt->data["codded"][$li_x];									  
							       $ls_dended         = $io_report->ds_ded_dt->data["dended"][$li_x];
								   $ld_monobjded   	  = $io_report->ds_ded_dt->data["monobjretded"][$li_x];
								   $ld_objretded   	  = $io_report->ds_ded_dt->data["objretded"][$li_x];
								$ld_total_deducciones=$ld_total_deducciones+$ld_objretded;
								   $ld_monobjded      = number_format($ld_monobjded,2,",",".");	
								   $ld_objretded      = number_format($ld_objretded,2,",",".");
								   $la_datacar[$li_a] = array('codigo'=>$ls_codded,'denominacion'=>$ls_dended,
									                         'monobjret'=>$ld_monobjded,'objret'=>$ld_objretded);
								 } 							   
						}
				}
			}
		}
		if (!empty($la_datacar))
		   {
			$ld_total_deducciones      = number_format($ld_total_deducciones,2,",",".");
 			uf_print_carded($la_datacar,$ld_total_deducciones,&$io_pdf); 
 		   }
		if($ls_tiporeporte==0)
		{
			uf_print_total_bsf($li_monsol,&$io_pdf);
		}
        //uf_print_monto_letras($ls_monto,$io_pdf);
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
