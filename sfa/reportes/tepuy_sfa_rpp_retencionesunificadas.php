<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Unificadas
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	//ini_set('display_errors', 1);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	ini_set('memory_limit','512M');
	//define( 'WP_MAX_MEMORY_LIMIT', '1G' );
	//ini_set('display_errors', 1);
	ini_set('max_execution_time','0');
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: funciOn que guarda la seguridad de quien generO el reporte
		//	   Creado Por: Ing. Miguel Palencia/ 
		// Fecha CreaciOn: 17/09/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		
		$ls_descripcion="GenerO el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sfa->uf_load_seguridad_reporte("SFA","tepuy_sfa_r_retencionesunificadas.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numcon,$ad_fecrep,$as_perfiscal,$as_licagenteret,$as_diragenteret,
							   $as_dirsujret,$as_nomsujret,$as_rif,$as_numlic,$ai_estcmpret,$as_fecfac,$as_numficha,$as_numfac,$as_totcmp_con_iva,$as_numcom,$as_encabezado,$as_numsol,$imp_concepto,$concepto,&$io_pdf)
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
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha CreaciOn: 17/09/2017 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		if($as_encabezado==1)
		{
			//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
			
			$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,705,52,60); // Agregar Logo
			$io_pdf->addJpegFromFile('../../shared/imagebank/logo_barinas_2015.jpg',530,705,52,60); // Agregar Logo
			$io_pdf->Rectangle(410,720,105,20); /// ubi. y, ubi. x, ancho, alto
			$as_numcon1=substr($as_numcom,9,6);
			$io_pdf->addText(424,726,11,"Nro.: ".$as_numcon1); // Número de Orden de compra
		}	
		$io_pdf->ezSetY(705);
		$io_pdf->setStrokeColor(0,0,0);
		$la_data[1]=array('name'=>'<b>FECHA DE EMISION</b>');
		$la_data[2]=array('name'=>$ad_fecrep);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>540, // OrientaciOn de la tabla
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		$ad_perfiscal=substr($as_perfiscal,4,2)."/".substr($as_perfiscal,0,4);
		$io_pdf->ezSetY(675);
		$la_data[1]=array('name'=>'<b>PERIODO FISCAL</b>');
		$la_data[2]=array('name'=>$ad_perfiscal);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>540, // OrientaciOn de la tabla
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetY(715);
		$la_data[1]=array('name'=>'<b>DATOS DEL CONTRIBUYENTE</b>');			
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>510, // OrientaciOn de la tabla
						 'width'=>540, // Ancho de la tabla						 
						 'maxWidth'=>540,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>540))); // Ancho Minimo de la tabla
        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
		

		$la_data[1]=array('name'=>'<b>NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO</b>');
		$la_data[2]=array('name'=>$as_nomsujret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
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
		//$io_pdf->ezSetY(420);
		$la_data[1]=array('name'=>'<b>REGISTRO DE INFORMACION FISCAL DEL SUJETO RETENIDO (R.I.F.)</b>');
		$la_data[2]=array('name'=>$as_rif);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // OrientaciOn de la tabla
						 'width'=>320, // Ancho de la tabla						 
						 'maxWidth'=>320,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>320))); // Ancho Minimo de la tabla
        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
///////////////// FACTURA /////////////
		if($imp_concepto==1)
		{
			////////////// CONCEPTO DE OBRAS ///////////////
			$la_data[1]=array('name'=>'<b>CONCEPTO</b>');
			$la_data[2]=array('name'=>$concepto);				
			$la_columna=array('name'=>'');		
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>305, // OrientaciOn de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>550))); // Ancho Minimo de la tabla
        		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			unset($la_data);
			unset($la_columna);
			unset($la_config);
			/////////// CONCEPTO DE OBRAS //////////
		}
		$la_data[1]=array('name'=>'<b>DATOS DE LA FACTURA</b>');				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>520, // OrientaciOn de la tabla
						 'width'=>540, // Ancho de la tabla						 
						 'maxWidth'=>540,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>540))); // Ancho Minimo de la tabla
        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);

		$la_data[1]=array('fecha'=>'<b>FECHA</b>','ficha'=>'<b>FICHA</b>','factura'=>'<b>Nro. DE FACTURA</b>','control'=>'<b>Nro. DE CONTROL</b>','total'=>'<b>MONTO TOTAL Bs.</b>');
		$la_columna=array('fecha'=>'<b>'.$as_fecfac.'.</b>','ficha'=>'<b>'.$as_numficha.'</b>','factura'=>'<b>'.$as_numfac.'</b>','control'=>'<b>'.$as_numcon.'</b>','total'=>'<b>'.$as_totcmp_con_iva.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // OrientaciOn de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'ficha'=>array('justification'=>'center','width'=>100),
									   'factura'=>array('justification'=>'center','width'=>100),
									   'control'=>array('justification'=>'center','width'=>100),
   						 			   'total'=>array('justification'=>'center','width'=>140))); 

        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);

		$la_data[1]=array('fecha'=>'<b>'.$as_fecfac.'</b>','ficha'=>'<b>'.$as_numficha.'</b>','factura'=>'<b>'.$as_numfac.'</b>','control'=>'<b>'.$as_numcon.'</b>','total'=>'<b>'.$as_totcmp_con_iva.'</b>');
		$la_columna=array('fecha'=>'<b>'.$as_fecfac.'.</b>','ficha'=>'<b>'.$as_numficha.'</b>','factura'=>'<b>'.$as_numfac.'</b>','control'=>'<b>'.$as_numcon.'</b>','total'=>'<b>'.$as_totcmp_con_iva.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // OrientaciOn de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'ficha'=>array('justification'=>'center','width'=>100),
									   'factura'=>array('justification'=>'center','width'=>100),
									   'control'=>array('justification'=>'center','width'=>100),
   						 			   'total'=>array('justification'=>'center','width'=>140))); 

        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);


/*		$la_data[1]=array('name'=>'<b>RETENCIONES VARIAS. ORDEN DE PAGO Nro. </b>'.'<b>'.$as_numsol.'</b>');
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>442, // OrientaciOn de la tabla
						 'width'=>540, // Ancho de la tabla						 
						 'maxWidth'=>540,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>540))); // Ancho Minimo de la tabla

        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);*/

		$la_data[1]=array('name'=>'');				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>540, // OrientaciOn de la tabla
						 'width'=>540, // Ancho de la tabla						 
						 'maxWidth'=>540,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>540))); // Ancho Minimo de la tabla

        	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);


///////////////// FIN DE LOS DATOS DE LA FACTURA ///////////////
		//$io_pdf->ezSetY(350);
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
		unset($la_config);	*/							 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totbasimp,$ai_totmonimp,$as_rifagenteret,$as_titulo_impuesto,$as_impuesto,$as_alicuota,$as_base,$as_abonado,&$io_pdf)
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
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha CreaciOn: 17/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(365); // mientras mas alto sube
		$la_data1[1]=array('impuesto'=>'<b>'.$as_titulo_impuesto.'</b>',
						  'monto'=>'<b>'.$as_abonado.'</b>',
						  'base'=>'<b>'.$as_base.'</b>',
						  'alicuota'=>'<b>'.$as_alicuota.'</b>',
  						  'total'=>'<b>Total Impuesto Retenido</b>');
		$la_columna=array('impuesto'=>'<b>'.$as_titulo_impuesto.'.</b>',
						  'monto'=>'<b>'.$as_abonado.'</b>',
						  'base'=>'<b>'.$as_base.'</b>',
						  'alicuota'=>'<b>'.$as_alicuota.'</b>',
  						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 //'shaded'=>2, // Sombra entre líneas
						 //'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // OrientaciOn de la tabla
						 'cols'=>array('impuesto'=>array('justification'=>'center','width'=>100), // Justificacion y ancho de la columna
									   'monto'=>array('justification'=>'center','width'=>120),
						 			   'base'=>array('justification'=>'center','width'=>120),
						 			   'alicuota'=>array('justification'=>'center','width'=>100),
   						 			   'total'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('impuesto'=>'<b>'.$as_impuesto.'</b>',
  						  'monto'=>'<b>'.$as_abonado.'</b>',
						  'base'=>'<b>'.$as_base.'</b>',
						  'alicuota'=>'<b>'.$as_alicuota.'</b>',
						  'total'=>'<b>Total Impuesto Retenido</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Mínimo de la tabla
						 'xPos'=>305, // OrientaciOn de la tabla
						 'cols'=>array('impuesto'=>array('justification'=>'center','width'=>100), // Justificacion y ancho de la columna
									   'monto'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
						 			   'base'=>array('justification'=>'right','width'=>120),
						 			   'alicuota'=>array('justification'=>'center','width'=>100),
   						 			   'total'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('total'=>'<b>Total Bs. de '.$as_titulo_impuesto.' Retenido</b>',		
						   'monto'=>'<b>'.$ai_totmonimp.'</b>');
		$la_columna=array('total'=>'<b>total</b>',		
						  'monto'=>'<b>monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 //'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>300, // Ancho de la tabla
						 'maxWidth'=>300, // Ancho Mínimo de la tabla
						 'xPos'=>425, // OrientaciOn de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>200), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);


	}// end function uf_print_detalle

	function uf_print_totales($as_total_retenido,$as_neto_a_cobrar,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		/////////////////// TOTAL GENERAL DE LAS RETENCIONES //////////////////////////
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciOn que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / 
		// Fecha CreaciOn: 17/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
		$la_data1[1]=array('total'=>'<b>Total Retenciones Bs. </b>','monto'=>'<b>'.$as_total_retenido.'</b>');
		$la_columna=array('total'=>'<b>total</b>','monto'=>'<b>monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 //'shaded'=>2, // Sombra entre líneas
						 //'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>300, // Ancho de la tabla
						 'maxWidth'=>300, // Ancho Mínimo de la tabla
						 'xPos'=>425, // OrientaciOn de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>200), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100))); 

		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('total'=>'<b>Neto a Cobrar Bs. </b>','monto'=>'<b>'.$as_neto_a_cobrar.'</b>');
		$la_columna=array('total'=>'<b>total</b>','monto'=>'<b>monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 //'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>300, // Ancho de la tabla
						 'maxWidth'=>300, // Ancho Mínimo de la tabla
						 'xPos'=>425, // OrientaciOn de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>200), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);

	}// end function uf_print_totales

////---------------MONTO EN LETRAS ----------------------------
	function uf_print_monto_en_letras($as_monto,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: as_monto : Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 17/09/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_data=array(array('1'=>' ','2'=>' ','monto'=>'','4'=>' '),array('1'=>' ','2'=>' ','monto'=>'','4'=>' '));
		$la_columna=array('1'=>' ','2'=>' ','monto'=>'','4'=>' ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' =>9, // Tamaño de Letras
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('1'=>array('justification'=>'center','width'=>100),'2'=>array('justification'=>'center','width'=>150),
						 'monto'=>array('justification'=>'center','width'=>150),'4'=>array('justification'=>'center','width'=>50))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('data'=>"<b>".$as_monto."</b>")
                       );
		$la_columna=array('data'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' =>8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600,
						 'cols'=>array('data'=>array('justification'=>'center','width'=>550))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('firma'=>'_________________________________________________');	
		$la_data1[2]=array('firma'=>'FIRMA Y SELLO DEL AGENTE DE RETENCION');	
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
		$io_pdf->ezSetDy(-40);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_monto_en_letras
////////////////////////////////////////////////FIN DE FUNCIONES //////////////////////////////////////////
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_sfa_class_report.php");
	$io_report=new tepuy_sfa_class_report();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_iva=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='I'",'C');
	$ls_municipal=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='M'",'C');
	$ls_timbrefiscal=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='T'",'C');
	$ls_islr=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='S'",'C');
	$ls_otros=$io_report->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='O'",'C');
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$ls_tiporeporte=$io_fun_sfa->uf_obtenervalor_get("tiporeporte",0);
	$ls_imprimencabezado=$io_fun_sfa->uf_obtenervalor_get("encabezado",0);
	require_once("../../shared/class_folder/class_numero_a_letra.php");
	global $ls_tiporeporte;
	//Instancio a la clase de conversión de numeros a letras.
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	if($ls_tiporeporte==1)
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO VARIOS</b>";
	}
	else
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO VARIOS</b>";
	}
	$ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_clientes=$io_fun_sfa->uf_obtenervalor_get("cliente","");
	//$ls_codretenciones=$io_fun_sfa->uf_obtenervalor_get("codretenciones","");
	$ls_mes=$io_fun_sfa->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_sfa->uf_obtenervalor_get("anio","");
	$ls_numfactura=$io_fun_sfa->uf_obtenervalor_get("ls_numfactura","");
	//Agregado el 22-09-2016 por Ing. Arnaldo Paredes para obtener la fecha de la Retención
	$ls_fecfactura=$io_fun_sfa->uf_obtenervalor_get("ls_fecfactura","");
	$ls_fecfactura=$io_funciones->uf_formatovalidofecha($ls_fecfactura);
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_licagenteret=$_SESSION["la_empresa"]["numlicemp"];
	$imprimiocabecera=0;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_clientes=split("\.",$ls_clientes);
		//$la_codretenciones=split('-',$ls_codretenciones);
		$la_datos=array_unique($la_clientes);
		//$la_datos1=array_unique($la_codretenciones);
		$li_totrow=count($la_datos);
		//sort($la_datos,SORT_STRING);
		//print(" alert('$la_datos[0]');");
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
			$io_pdf = new Cezpdf("LETTER","portrait");
			//$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Courier-Bold.afm');
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$lb_valido=true;
			//for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			for ($li_z=0;($li_z<$li_totrow);$li_z++)
			{
				//$ls_codret1=$la_datos1[$li_z];
				$ld_rif=$la_datos[$li_z];
				//// ESTE ES PARA IMPRIMIR LAS RETENCIONES DE TIMBRE FISCAL DEL PROVEEDOR ///
				//$lb_valido=$io_report->uf_retenciones_del_proveedor_unificadas($ld_rif,$ls_mes,$ls_anio,$ls_numfactura);
				//Modificado el 22-09-2016 por Ing. Arnaldo Paredes para incluir la fecha de la Retención
				$lb_valido=$io_report->uf_retenciones_del_cliente_unificadas($ld_rif,$ls_mes,$ls_anio,$ls_numfactura,$ls_fecfactura);
				//if($ls_imprimencabezado==1)
				//{
				//	$ls_numero=$io_report->DS->data["numcom"][1];
				//  	uf_print_encabezado($ls_titulo,$ls_numero,$io_pdf);
				//}

				if($lb_valido)
				{
					$ls_numcom=$io_report->DS->data["numcom"][1];
					$ls_numsol=substr($io_report->DS->data["numsol"][1],9,6);
					$ls_documento=$io_report->DS->data["numcon"][1];
					$ls_numficha=$io_report->DS->data["numficha"][1];
					$ls_numfac=$io_report->DS->data["numfac"][1];
					$totcmp_con_iva=$io_report->DS->data["totcmp_con_iva"][1];
					$totcmp_con_iva=number_format($totcmp_con_iva,2,",",".");
					$ls_codret=$io_report->DS->data["codret"][1];
					$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][1]);
					$ls_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecfac"][1]);
					$ls_perfiscal=$io_report->DS->data["perfiscal"][1];						
					$ls_codsujret=$io_report->DS->data["codsujret"][1];			     
					$ls_nomsujret=$io_report->DS->data["nomsujret"][1];	
					$ls_rif=$io_report->DS->data["rif"][1];
					$ls_dirsujret=$io_report->DS->data["dirsujret"][1];
					$li_estcmpret=$io_report->DS->data["estcmpret"][1];
					$ls_numlic=$io_report->DS->data["numlic"][1];
					$procede=$io_report->DS->data["procede"][1];
					$concepto=$io_report->DS->data["consol"][1];
					$ls_concepto=0;
					if($procede=="SOBCON")
					{
						$ls_concepto=1;
					}
					// BUSCA LA CANTIDAD DE RETENCIONES DEL PROVEEDOR PARA LA FACTURA ////
					$li_total=$io_report->DS->getRowCount("numcom");
					
					uf_print_cabecera($ls_documento,$ls_fecrep,$ls_perfiscal,$ls_licagenteret,$ls_diragenteret,$ls_dirsujret,$ls_nomsujret,$ls_rif,$ls_numlic,$li_estcmpret,$ls_fecfac,$ls_numficha,$ls_numfac,$totcmp_con_iva,$ls_numcom,$ls_imprimencabezado,$ls_numsol,$ls_concepto,$concepto,&$io_pdf);
					$total_retenciones=0;
					//print $li_total; die();
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numcom=$io_report->DS->data["numcom"][$li_i];
						//$ls_numfac=$io_report->DS->data["numfac"][$li_i];		 								
						$ls_codret=$io_report->DS->data["codret"][$li_i];			   
						$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][$li_i]);
						//$ls_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecfac"][$li_i]);
						$ls_perfiscal=$io_report->DS->data["perfiscal"][$li_i];						
						$ls_codsujret=$io_report->DS->data["codsujret"][$li_i];			     
						$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif=$io_report->DS->data["rif"][$li_i];	
						$ls_dirsujret=$io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret=$io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic=$io_report->DS->data["numlic"][$li_i];

					//}											
					//print "Cod. Retencion: ".$ls_codret;die();
					switch(true)
					{
						case ($ls_codret==$ls_otros):
							$lb_valido=$io_report->uf_retencionesotros_detalles($ls_numcom,$ls_otros,1);
							$titulo_impuesto="Otros";
							$ls_impuesto="XXXX";
							$ls_alicuota="PORCENTAJE DE RETENCION";
							$base="BASE IMPONIBLE";
							$abonado="MONTO PAGADO O ABONADO EN CUENTA";
							break;
						case ($ls_codret==$ls_timbrefiscal):
							$lb_valido=$io_report->uf_retencionestimbrefiscal_detalles($ls_numcom,$ls_timbrefiscal,1);
							$titulo_impuesto="TIMBRE FISCAL";
							$ls_impuesto="S.A.T.E.B.";
							$ls_alicuota="ALICUOTA APLICADA %";
							$base="BASE IMPONIBLE";
							$abonado="MONTO PAGADO O ABONADO EN CUENTA";
							break;
						case ($ls_codret==$ls_municipal):
							$lb_valido=$io_report->uf_retencionesmunicipales_detalles($ls_numcom,$ls_municipal,1);
							$titulo_impuesto="I.S.A.E.";
							$ls_impuesto="Actividad Economica";
							$ls_alicuota="ALICUOTA APLICADA %";
							$base="BASE IMPONIBLE";
							$abonado="MONTO PAGADO O ABONADO EN CUENTA";
							break;
						case ($ls_codret==$ls_islr):
							$lb_valido=$io_report->uf_retencionesislr_detalles($ls_numcom,$ls_islr,1);
							$titulo_impuesto="I.S.L.R.";
							$ls_impuesto="Art. 9";
							$ls_alicuota="PORCENTAJE DE RETENCION";
							$base="BASE IMPONIBLE";
							$abonado="MONTO PAGADO O ABONADO EN CUENTA";
							break;
						case ($ls_codret==$ls_iva):
							$lb_valido=$io_report->uf_retencionesiva_detalle_unificadas($ls_numcom,$ls_iva,1);
							$titulo_impuesto="IVA -  G.O. 38.136";
							$ls_impuesto="Art. 7";
							$ls_alicuota="I.V.A. APLICADO";
							$base="I.V.A FACTURADO";
							$abonado="PORCENTAJE RET. APLICADO";
							break;
					}
					
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$la_total=$io_report->ds_detalle->getRowCount("numfac");
						$neto_a_cobrar=0;
						//print "cod. Ret: ".$ls_codret." Son: ".$la_total;die();
						$la_data=array();
						for($la_i=1;$la_i<=$la_total;$la_i++)
						{

							$ls_numsop=$io_report->ds_detalle->data["numsop"][$la_i];
							$ls_monsol=$io_report->ds_detalle->data["monsol"][$la_i];
							$neto_a_cobrar=$ls_monsol;
							$ld_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecfac"][$la_i]);
							$ls_numfac=$io_report->ds_detalle->data["numfac"][$la_i];
							$ls_numref=$io_report->ds_detalle->data["numcon"][$la_i];
							if($ls_codret==$ls_otros)$ls_impuesto=$io_report->ds_detalle->data["dended"][$la_i];
							if($ls_codret==$ls_iva)
							{
								$li_baseimp=$io_report->ds_detalle->data["totimp"][$la_i];	
								$li_montototal=$io_report->ds_detalle->data["montototal"][$la_i];
								$li_porimp=$io_report->ds_detalle->data["porimp"][$la_i];
								$li_totimp=$io_report->ds_detalle->data["iva_ret"][$la_i];
								$la_totivaret=$li_totimp;
							}							
							else
							{
								$li_baseimp=$io_report->ds_detalle->data["basimp"][$la_i];	
								$li_montototal=$io_report->ds_detalle->data["montototal"][$la_i];
								$li_porimp=$io_report->ds_detalle->data["porimp"][$la_i];
								$li_totimp=$io_report->ds_detalle->data["totimp"][$la_i];
								$la_totivaret=$li_totimp;
							}
							//$li_montototal=$io_report->ds_detalle->data["montototal"][$la_i];
							if($la_i==1)
							{
								//uf_print_cabecera_factura($ls_numref,$ls_numfac,$ld_fecfac,&$io_pdf);
								
							}
							
							$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp=$li_totalmontoimp + $li_totimp;
							if($ls_codret==$ls_iva)
							{
								//$li_totalmontoimp*100
								$li_montototal=(($li_totalmontoimp*100)/$li_totalbaseimp);
								//$total_retenciones=$total_retenciones+$li_totalmontoimp;
							}
							else
							{
								$li_montototal=$ls_monsol;
								$li_porimp=($li_porimp*100);
							}
							$total_retenciones=$total_retenciones+$li_totimp; //$la_totivaret;
							$li_baseimp=number_format($li_baseimp,2,",",".");
							$li_montototal=number_format($li_montototal,2,",",".");
							if($li_porimpreal==0)
							{
								$li_porimpreal="1x1000";
								//if($ls_codret==$ls_otros)$li_porimpreal=0;
							}
							else
							{							
								$li_porimpreal=number_format($li_porimp,2,",",".");
							}			
							$li_totimp=number_format($li_totimp,2,",",".");
							$li_totivaret=number_format($la_totivaret,2,",",".");

							//$la_data[$li_i]=array('numope'=>$li_i,'fecfac'=>$ld_fecfac,'tipinst'=>'Factura','numfactura'=>$ls_numfac,'baseimp'=>$li_baseimp,'impret'=>$li_totimp);
							$la_data[$la_i]=array('impuesto'=>$ls_impuesto,'monto'=>$li_montototal,'base'=>$li_baseimp,'alicuota'=>$li_porimpreal,'total'=>$li_totivaret);

							
						}
						  $numalet->setNumero($neto_a_cobrar);
				  		  $ls_monto= $numalet->letra();			  
  						  $li_totalbaseimp= number_format($li_totalbaseimp,2,",","."); 
  						  $li_totalmontoimp= number_format($li_totalmontoimp,2,",","."); 
						  $neto_a_cobrar= number_format($neto_a_cobrar,2,",","."); 
						 //$total_retenciones= number_format($total_retenciones,2,",",".");
						  //uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$ls_rifagenteret,&$io_pdf);

						uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$ls_rifagenteret,$titulo_impuesto,$ls_impuesto,$ls_alicuota,$base,$abonado,&$io_pdf);
						
					} //if						 
					} //next
					$total_retenciones= number_format($total_retenciones,2,",",".");
					uf_print_totales($total_retenciones,$neto_a_cobrar,&$io_pdf);
					
					uf_print_monto_en_letras($ls_monto,&$io_pdf);
				}

				//// FINAL DEL PROCESO DE IMPRIMIR LAS RETENCIONES DEL PROVEEDOR ///
 						 						  
				//unset($la_data);
				//$io_report->DS->reset_ds();
				if($li_z<($li_totrow-1))
				{
					$io_pdf->ezNewPage(); 					  
				}		

			}
				//unset($la_data);
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresiOn de los números de página
				ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
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
	unset($io_fun_sfa);
?> 
