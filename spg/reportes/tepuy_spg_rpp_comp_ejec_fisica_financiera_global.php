<?php
    session_start();   
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
	
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		
		$io_pdf->rectangle(10,470,985,130);
	//	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],65,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	$io_pdf->addJpegFromFile('../../shared/imagebank/bandera.jpg',15,531,65,65); // Agregar Logo
		//$io_pdf->line(10,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/letras.jpg',85,530,285,65); // Agregar Logo
	$io_pdf->addJpegFromFile('../../shared/imagebank/escudo.jpg',925,530,65,65); // Agregar Logo

	$io_pdf->addJpegFromFile('../../shared/imagebank/linea.jpg',11,523,983,9); // Agregar Logo


		//$io_pdf->line(10,40,578,40);
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->addText(12,580,11,"<b>CONCEJO MUNICIPAL DE OBISPOS</b>"); // Agregar la Fecha
//		$io_pdf->addText(15,570,11,"<b>ALBERTO ARVELO TORREALBA</b>"); // Agregar la Fecha
//		$io_pdf->addText(65,518,11,"<b>Estado Barinas</b>"); // Agregar la Fecha
		$io_pdf->addText(15,565,11,"<b></b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,514,16,$as_titulo); // Agregar el título
		
		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$ai_ano,$as_meses_trimestre,$as_etiqueta,$as_denestpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
        //$io_pdf->ezSetDy(-20);
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		// SE MODIFICO PARA QUE SOLO APAREZCA SECTOR, PROGRAMA Y ACTIVIDAD
		$as_programatica_auxiliar=substr($as_programatica,18,3).substr($as_programatica,25,3).substr($as_programatica,29,2);
		$la_data=array(array('name'=>'<b>Programatica    </b>'.'<b>'.$as_programatica_auxiliar.'</b>'),
		               array('name'=>''.'<b>'.$as_denestpro.'</b>'),       
		               array('name'=>'<b>'.$ls_etiqueta.'  </b>'.'<b>'.$as_meses_trimestre.'</b>'.'<b>           Presupuesto   </b> '.'<b>'.$ai_ano.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>260,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>500),
						               'name'=>array('justification'=>'left','width'=>500),
									   'name'=>array('justification'=>'left','width'=>500)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($io_titulo,$as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(451);
		//$io_pdf->ezSetDy(-20);
		$la_data=array(array('name1'=>'<b>MODIFICACIONES PRESUPUESTARIAS</b>'));
		$la_columna=array('name1'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>439,		// Inicio de columnas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');


		$io_pdf->saveState();
		$io_pdf->ezSetY(451);
		//$io_pdf->ezSetDy(-20);
		$la_data=array(array('name1'=>'<b>EJECUTADO '.strtoupper($as_etiqueta).' ACUMULADO</b>','name2'=>'<b> DISPONIBILIDAD </b>'));
		$la_columna=array('name1'=>'','name2'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>799,		// Inicio de columnas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>320),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
	//	$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>Presupuesto Inicial</b>',
	//	                     'aumento'=>'<b>Aumentos</b>','disminucion'=>'<b>Disminuciones</b>','actualizado'=>'<b>Presupuesto Actualizado</b>','compromiso'=>'<b>Compromiso</b>','causado'=>'<b>Causado</b>',
	//						 'pagado'=>'<b>Pagado</b>','deuda'=>'<b>Deuda</b>','disp_trim_ant'=>'<b>'.$ls_etiqueta.'  Anterior</b>',
	//						 'disp_fecha'=>'<b>A la Fecha</b>'));
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>Presupuesto Inicial</b>',
		                     'aumento'=>'<b>Aumentos</b>','disminucion'=>'<b>Disminuciones</b>','actualizado'=>'<b>Presupuesto Actualizado</b>','compromiso'=>'<b>Compromiso</b>','causado'=>'<b>Causado</b>',
							 'pagado'=>'<b>Pagado</b>','deuda'=>'<b>Deuda</b>',
							 'disp_fecha'=>'<b>A la Fecha</b>'));

//		$la_columna=array('cuenta'=>'','denominacion'=>'','pres_anual'=>'','aumento'=>'','disminucion'=>'','actualizado'=>'','compromiso'=>'','causado'=>'',
//		                  'pagado'=>'','deuda'=>'','disp_trim_ant'=>'','disp_fecha'=>'');
		$la_columna=array('cuenta'=>'','denominacion'=>'','pres_anual'=>'','aumento'=>'','disminucion'=>'','actualizado'=>'','compromiso'=>'','causado'=>'',
		                  'pagado'=>'','deuda'=>'','disp_fecha'=>'');

		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'aumento'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'actualizado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'deuda'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
							//		   'disp_trim_ant'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'actualizado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'deuda'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
							//		   'disp_trim_ant'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b></b>',
						   'denominacion'=>'<b></b>',
						   'pres_anual'=>'<b></b>',
						   'aumento'=>'<b></b>',
						   'disminucion'=>'<b></b>',
						   'actualizado'=>'<b></b>',
						   'compromiso'=>'<b></b>',
						   'causado'=>'<b></b>',
						   'pagado'=>'<b></b>',
						   'deuda'=>'<b></b>',
//						   'disp_trim_ant'=>'<b></b>',
						   'disp_fecha'=>'<b></b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>260), // Justificación y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'actualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'deuda'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
//									   'disp_trim_ant'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'pres_anual'=>'',
						   'aumento'=>'',
						   'disminucion'=>'',
						   'actualizado'=>'',
						   'compromiso'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'deuda'=>'',
//						   'disp_trim_ant'=>'',
						   'disp_fecha'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("tepuy_spg_reporte_comparados.php");
		$io_report = new tepuy_spg_reporte_comparados();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $li_cmbnivel="1";
		}
		else
		{
          $li_cmbnivel=$cmbnivel;
		}
        $li_ctasinmov=$_GET["ckbctasinmov"];
		if($li_ctasinmov==1)
		{
		  $lb_ctasinmov=true;
		}
		else
		{
		  $lb_ctasinmov=false;
		}
        $li_ominoprog=$_GET["ckbominoprog"];
		if($li_ominoprog==1)
		{
		  $lb_ominoprog=true;
		}
		else
		{
		  $lb_ominoprog=false;
		}
		$ls_etiqueta=$_GET["txtetiqueta"];
		if($ls_etiqueta=="Mensual")
		{
			$ls_combo=$_GET["combo"];
			$ls_combomes=$_GET["combomes"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combomes,0,2); 
			$ls_cant_mes=1;
            $ls_meses=$io_function_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			$ls_combo=$ls_combo.$ls_combomes;
		}
		else
		{
			$ls_combo=$_GET["combo"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combo,2,2); 
			if($ls_etiqueta=="Bi-Mensual")
			{
				$ls_cant_mes=2;
				$ls_meses=$io_function_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Trimestral")
			{
				$ls_cant_mes=3;
				$ls_meses=$io_function_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Semestral")
			{
				$ls_cant_mes=6;
				$ls_meses=$io_function_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
		}
        $lb_ckbformil=$_GET["ckbformil"];		
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>EJECUCIÓN FÍSICA Y FINANCIERA ".strtoupper($ls_etiqueta)." DEL PRESUPUESTO DE GASTOS</b>";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	
     $lb_valido=$io_report->uf_spg_reportes_select_ejecucion_financiera_global();
	
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    	error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(6.8,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
		
		//MODIFICACION
		$total_general_pres_anual=0;
		$total_general_compromiso=0;
		$total_general_causado=0;
		$total_general_pagado=0;
		$total_general_programado=0;
		$total_general_aumento=0;
		$total_general_disminucion=0;
		$total_general_actualizado=0;
		$total_general_deuda=0;
		$total_general_prog_t_ant=0;
		$total_general_disp_fecha=0;
		$desde_aqui=1;
		//////
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{		
	        	$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
		
	 //aqui es donde se filtran los datos para el reporte
		$lb_valido=$io_report->uf_spg_reportes_comparados_ejecucion_financiera_global( $lb_ctasinmov,$lb_ominoprog, $ls_combo,$li_cmbnivel,$ls_cant_mes);
			if($li_estmodest==2)
			{
				$ls_codestpro1=substr($ls_codestpro1,-2);
				$ls_codestpro2=substr($ls_codestpro2,-2);
				$ls_codestpro3=substr($ls_codestpro3,-2);
				$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
			}
			else
			{
				$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
			} 
		  if($lb_valido==false) // Existe algún error ó no hay registros
		  {
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
		  }
		  else
		  {
			$ld_pres_anual=0;
			$ld_programado=0;
			$ld_porc_comprometer=0;
			$ld_porc_causado=0;
			$ld_porc_pagado=0;
			$ld_total_pres_anual=0;
			$ld_total_compromiso=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_programado=0;
			$ld_total_aumento=0;
			$ld_total_disminucion=0;
			$ld_total_actualizado=0;
			$ld_total_deuda=0;
			$ld_total_prog_t_ant=0;
			$ld_total_disp_fecha=0;

			
			$li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
			
			$thisPageNum=$io_pdf->ezPageCount;
			
			for($z=$desde_aqui;$z<=$li_total;$z++)
			{
				  
				  $ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
				  $lx_programa=$io_report->dts_reporte->data["programa"][$z];
			$paso=0;
			if((substr($ls_programatica,0,31))!=(substr($lx_programa,0,31)))
			{
				$paso=1;
			// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
				if(substr($ls_spg_cuenta,7,2)=="00")
				{
				$ls_spg_anterior=$ls_spg_cuenta;
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,7);
				}
				if(substr($ls_spg_anterior,9,4)<>"0000")
				{
				$ls_spg_cuenta=$ls_spg_anterior;
				}
				if(substr($ls_spg_cuenta,5,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,5);
				}
				if(substr($ls_spg_cuenta,3,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,3);
				}
			// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //

				$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
				$li_nivel=$io_report->dts_reporte->data["nivel"][$z];
				$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
				$ld_monto_programado=$io_report->dts_reporte->data["monto_programado"][$z];
				$ld_monto_aumento=$io_report->dts_reporte->data["aumento"][$z];
				$ld_monto_disminucion=$io_report->dts_reporte->data["disminucion"][$z];
				$ld_aumdisacum=$io_report->dts_reporte->data["aumdis_acumulado"][$z];
				//$ld_monto_actualizado=($ld_asignado+$ld_monto_aumento)-$ld_monto_disminucion;
				$ld_monto_actualizado=($ld_asignado+$ld_aumdisacum);
				$ld_monto_acumulado=$io_report->dts_reporte->data["monto_acumulado"][$z];
				$ld_aumdismes=$io_report->dts_reporte->data["aumdis_mes"][$z];
				
				$ld_monto_ejecutado=$io_report->dts_reporte->data["ejecutado_mes"][$z];
				$ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acum"][$z];
				$ld_comprometer=$io_report->dts_reporte->data["compromiso"][$z];
	// MODIFICACION ESPECIAL REPORTE 2009 // MEMORIA Y CUENTA
				$ld_causado=$io_report->dts_reporte->data["causado"][$z];
				$ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
/*			$ld_causado=$ld_comprometer;
			$ld_pagado=$ld_comprometer;
			$VIEJO1=$ld_comprometer;
print $ls_codestpro1;
print $ls_codestpro2;
if(($ls_codestpro1=='00000000000000000011') && ($ls_codestpro2=='000004') )
		  {
				$ld_causado=$io_report->dts_reporte->data["causado"][$z];
				$ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
		  }
if(($ls_spg_cuenta=='4040202000086')||($ls_spg_cuenta=='4040202000065')||($ls_spg_cuenta=='4040202000087')|| ($ls_spg_cuenta=='4040202000019')||($ls_spg_cuenta=='4040202000025')||($ls_spg_cuenta=='4040202000028')||($ls_spg_cuenta=='4041503000002') ||($ls_spg_cuenta=='4040202000065'))
		  {
				$ld_causado=$VIEJO1;
				$ld_pagado=$VIEJO1;
		  }

*/
				$ld_monto_deuda=($ld_comprometer-$ld_pagado);
				$ld_ejec_t_ant=$io_report->dts_reporte->data["ejec_t_ant"][$z];
				//$ld_compr_t_ant=$io_report->dts_reporte->data["compr_t_ant"][$z];
				//$ld_dispon_fecha=$io_report->dts_reporte->data["disponible_fecha"][$z];
				$ld_dispon_fecha=($ld_monto_actualizado-$ld_comprometer);
				  
				if($lb_ckbformil==1)
				  {
					//$ld_pres_anual=($ld_asignado+$ld_aumdismes+$ld_aumdisacum)*1000;
					$ld_pres_anual=$ld_asignado*1000;
					$ld_programado=$ld_monto_programado*1000;
					$ld_aumento=$ld_monto_aumento*1000;
					$ld_disminucion=$ld_monto_disminucion*1000;	  	  
					$ld_actualizado=$ld_monto_actualizado*1000;
					$ld_comprometer=$ld_comprometer*1000;
					$ld_causado=$ld_causado*1000;	  
					$ld_pagado=$ld_pagado*1000;
					$ld_dispon_fecha=$ld_dispon_fecha*1000;
					$ld_ejec_t_ant=$ld_ejec_t_ant*1000;
					$ld_deuda=$ld_monto_deuda*1000;
					//totales
					if($li_nivel==1)
					{
						 $ld_total_pres_anual=($ld_total_pres_anual+$ld_pres_anual);
						 $ld_total_programado=($ld_total_programado+$ld_monto_programado);
						 $ld_total_aumento=($ld_total_aumento+$ld_monto_aumento);
						 $ld_total_disminucion=($ld_total_disminucion+$ld_monto_disminucion);
						 $ld_total_actualizado=($ld_total_actualizado+$ld_monto_actualizado);
						 $ld_total_compromiso=($ld_total_compromiso+$ld_comprometer);
						 $ld_total_causado=($ld_total_causado+$ld_causado);
						 $ld_total_pagado=($ld_total_pagado+$ld_pagado);
						 $ld_total_deuda=($ld_total_deuda+$ld_monto_deuda);
						 $ld_total_prog_t_ant=($ld_total_prog_t_ant+$ld_ejec_t_ant);
						 $ld_total_disp_fecha=($ld_total_disp_fecha+$ld_dispon_fecha);
					//MODIFICACION
						$total_general_pres_anual=$total_general_pres_anual+$ld_total_pres_anual;
						$total_general_compromiso=$total_general_compromiso+$ld_total_compromiso;
						$total_general_causado=$total_general_causado+$ld_total_causado;
						$total_general_pagado=$total_general_pagado+$ld_total_pagado;
						$total_general_programado=$total_general_programado+$ld_total_programado;
						$total_general_aumento=$total_general_aumento+$ld_total_aumento;
						$total_general_disminucion=$total_general_disminucion+$ld_total_disminucion;
						$total_general_actualizado=$total_general_actualizado+$ld_total_actualizado;
						$total_general_deuda=$total_general_deuda+$ld_total_deuda;
						$total_general_prog_t_ant=$total_general_prog_t_ant+$ld_total_prog_t_ant;
						$total_general_disp_fecha=$total_general_disp_fecha+$ld_total_disp_fecha;
					  }
			  }
			  else  
			  {
				  //$ld_pres_anual=$ld_asignado+$ld_aumdismes+$ld_aumdisacum;
				  $ld_pres_anual=$ld_asignado;
				  $ld_programado=$ld_monto_programado;	  
				  $ld_aumento=$ld_monto_aumento;
				  $ld_disminucion=$ld_monto_disminucion;
				  $ld_actualizado=$ld_monto_actualizado;
				  $ld_comprometer=$ld_comprometer;
				  $ld_causado=$ld_causado;	  
				  $ld_pagado=$ld_pagado;
				  $ld_deuda=$ld_monto_deuda;
				  
				  //totales
				  if($li_nivel==1)
				  {
					 $ld_total_pres_anual=$ld_total_pres_anual+$ld_pres_anual;
					 $ld_total_programado=$ld_total_programado+$ld_monto_programado;
					 $ld_total_aumento=$ld_total_aumento+$ld_monto_aumento;
					 $ld_total_disminucion=$ld_total_disminucion+$ld_monto_disminucion;
					 $ld_total_actualizado=$ld_total_actualizado+$ld_monto_actualizado;
					 $ld_total_compromiso=$ld_total_compromiso+$ld_comprometer;
					 $ld_total_causado=$ld_total_causado+$ld_causado;
					 $ld_total_pagado=$ld_total_pagado+$ld_pagado;
					 $ld_total_deuda=$ld_total_deuda+$ld_deuda;
					 $ld_total_prog_t_ant=$ld_total_prog_t_ant+$ld_ejec_t_ant;
					 $ld_total_disp_fecha=$ld_total_disp_fecha+$ld_dispon_fecha;
				//	 $total_general_pres_anual=$total_general_pres_anual+$ld_total_pres_anual;
					//MODIFICADO
					$total_general_pres_anual=$total_general_pres_anual+$ld_pres_anual;
					$total_general_compromiso=$total_general_compromiso+$ld_comprometer;
					$total_general_causado=$total_general_causado+$ld_causado;
					$total_general_pagado=$total_general_pagado+$ld_pagado;
					$total_general_programado=$total_general_programado+$ld_programado;
					$total_general_aumento=$total_general_aumento+$ld_aumento;
					$total_general_disminucion=$total_general_disminucion+$ld_disminucion;
					$total_general_actualizado=$total_general_actualizado+$ld_actualizado;
					$total_general_deuda=$total_general_deuda+$ld_deuda;
					$total_general_prog_t_ant=$total_general_prog_t_ant+$ld_ejec_t_ant;
					$total_general_disp_fecha=$total_general_disp_fecha+$ld_dispon_fecha;
					///
				  }
				}//else
				  $ld_pres_anual=number_format($ld_pres_anual,2,",",".");
				  $ld_programado=number_format($ld_programado,2,",",".");
				  $ld_aumento=number_format($ld_aumento,2,",",".");
				  $ld_disminucion=number_format($ld_disminucion,2,",",".");
				  $ld_actualizado=number_format($ld_actualizado,2,",",".");
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  $ld_deuda=number_format($ld_deuda,2,",",".");
				  $ld_ejec_t_ant=number_format($ld_ejec_t_ant,2,",",".");
				  $ld_dispon_fecha=number_format($ld_dispon_fecha,2,",",".");
				//MODIFICADO
				$total_general_pres_anual=number_format($total_general_pres_anual,2,",",".");
				$total_general_programado=number_format($total_general_programado,2,",",".");
				$total_general_aumento=number_format($total_general_aumento,2,",",".");
				$total_general_disminucion=number_format($total_general_disminucion,2,",",".");
				$total_general_actualizado=number_format($total_general_actualizado,2,",",".");
				$total_general_compromiso=number_format($total_general_compromiso,2,",",".");
				$total_general_causado=number_format($total_general_causado,2,",",".");
				$total_general_pagado=number_format($total_general_pagado,2,",",".");
				$total_general_deuda=number_format($total_general_deuda,2,",",".");
				$total_general_prog_t_ant=number_format($total_general_prog_t_ant,2,",",".");
				$total_general_disp_fecha=number_format($total_general_disp_fecha,2,",",".");
				
//				  $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual,'aumento'=>$ld_aumento,'disminucion'=>$ld_disminucion,'actualizado'=>$ld_actualizado,'compromiso'=>$ld_comprometer,'causado'=>$ld_causado,'pagado'=>$ld_pagado,'deuda'=>$ld_deuda,'disp_trim_ant'=>$ld_ejec_t_ant,'disp_fecha'=>$ld_dispon_fecha);
				  $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual,'aumento'=>$ld_aumento,'disminucion'=>$ld_disminucion,'actualizado'=>$ld_actualizado,'compromiso'=>$ld_comprometer,'causado'=>$ld_causado,'pagado'=>$ld_pagado,'deuda'=>$ld_deuda,'disp_fecha'=>$ld_dispon_fecha);
									  

				 $ld_pres_anual=str_replace('.','',$ld_pres_anual);
				 $ld_pres_anual=str_replace(',','.',$ld_pres_anual);		
				 $ld_programado=str_replace('.','',$ld_programado);
				 $ld_programado=str_replace(',','.',$ld_programado);		
				 $ld_aumento=str_replace('.','',$ld_aumento);
				 $ld_aumento=str_replace(',','.',$ld_aumento);
				 $ld_disminucion=str_replace('.','',$ld_disminucion);
				 $ld_disminucion=str_replace(',','.',$ld_disminucion);
				 $ld_actualizado=str_replace('.','',$ld_actualizado);
				 $ld_actualizado=str_replace(',','.',$ld_actualizado);
				 $ld_comprometer=str_replace('.','',$ld_comprometer);
				 $ld_comprometer=str_replace(',','.',$ld_comprometer);		
				 $ld_causado=str_replace('.','',$ld_causado);
				 $ld_causado=str_replace(',','.',$ld_causado);
				 $ld_pagado=str_replace('.','',$ld_pagado);
				 $ld_pagado=str_replace(',','.',$ld_pagado);
				 $ld_deuda=str_replace('.','',$ld_deuda);
				 $ld_deuda=str_replace(',','.',$ld_deuda);

				 $ld_ejec_t_ant=str_replace('.','',$ld_ejec_t_ant);
				 $ld_ejec_t_ant=str_replace(',','.',$ld_ejec_t_ant);		
				 $ld_dispon_fecha=str_replace('.','',$ld_dispon_fecha);
				 $ld_dispon_fecha=str_replace(',','.',$ld_dispon_fecha);
				//modificado
				$total_general_pres_anual=str_replace('.','',$total_general_pres_anual);
				$total_general_pres_anual=str_replace(',','.',$total_general_pres_anual);
				$total_general_programado=str_replace('.','',$total_general_programado);
				$total_general_programado=str_replace(',','.',$total_general_programado);
				$total_general_aumento=str_replace('.','',$total_general_aumento);
				$total_general_aumento=str_replace(',','.',$total_general_aumento);
				$total_general_disminucion=str_replace('.','',$total_general_disminucion);
				$total_general_disminucion=str_replace(',','.',$total_general_disminucion);
				$total_general_actualizado=str_replace('.','',$total_general_actualizado);
				$total_general_actualizado=str_replace(',','.',$total_general_actualizado);
				$total_general_compromiso=str_replace('.','',$total_general_compromiso);
				$total_general_compromiso=str_replace(',','.',$total_general_compromiso);
				$total_general_causado=str_replace('.','',$total_general_causado);
				$total_general_causado=str_replace(',','.',$total_general_causado);
				$total_general_pagado=str_replace('.','',$total_general_pagado);
				$total_general_pagado=str_replace(',','.',$total_general_pagado);
				$total_general_deuda=str_replace('.','',$total_general_deuda);
				$total_general_deuda=str_replace(',','.',$total_general_deuda);
				$total_general_prog_t_ant=str_replace('.','',$total_general_prog_t_ant);
				$total_general_prog_t_ant=str_replace(',','.',$total_general_prog_t_ant);
				$total_general_disp_fecha=str_replace('.','',$total_general_disp_fecha);
				$total_general_disp_fecha=str_replace(',','.',$total_general_disp_fecha);
				////

				 if($z==$li_total)
				 {
					  $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
					  $ld_total_programado=number_format($ld_total_programado,2,",",".");
					  $ld_total_aumento=number_format($ld_total_aumento,2,",",".");
					  $ld_total_disminucion=number_format($ld_total_disminucion,2,",",".");
					  $ld_total_actualizado=number_format($ld_total_actualizado,2,",",".");
					  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
					  $ld_total_causado=number_format($ld_total_causado,2,",",".");
					  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
					  $ld_total_deuda=number_format($ld_total_deuda,2,",",".");
					  $ld_total_prog_t_ant=number_format($ld_total_prog_t_ant,2,",",".");
					  $ld_total_disp_fecha=number_format($ld_total_disp_fecha,2,",",".");

					////////////
							   
//					  $la_data_tot[$z]=array('total'=>'<b>TOTALES</b>','pres_anual'=>$ld_total_pres_anual,'aumento'=>$ld_total_aumento,'disminucion'=>$ld_total_disminucion,'actualizado'=>$ld_total_actualizado,
//											 'compromiso'=>$ld_total_compromiso,'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,'deuda'=>$ld_total_deuda,
//'disp_trim_ant'=>$ld_total_prog_t_ant,
//											 'disp_fecha'=>
//$ld_total_disp_fecha);
					  $la_data_tot[$z]=array('total'=>'<b>TOTALES</b>','pres_anual'=>$ld_total_pres_anual,'aumento'=>$ld_total_aumento,'disminucion'=>$ld_total_disminucion,'actualizado'=>$ld_total_actualizado,
											 'compromiso'=>$ld_total_compromiso,'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,'deuda'=>$ld_total_deuda,

											 'disp_fecha'=>
$ld_total_disp_fecha);

					//aqui intento clavar el total general						
					if($li_i==$li_tot)
						{
						//MODIFICADO
					$total_general_pres_anual=number_format($total_general_pres_anual,2,",",".");
					$total_general_programado=number_format($total_general_programado,2,",",".");
					$total_general_aumento=number_format($total_general_aumento,2,",",".");
					$total_general_disminucion=number_format($total_general_disminucion,2,",",".");
					$total_general_actualizado=number_format($total_general_actualizado,2,",",".");
					$total_general_compromiso=number_format($total_general_compromiso,2,",",".");
					$total_general_causado=number_format($total_general_causado,2,",",".");
					$total_general_pagado=number_format($total_general_pagado,2,",",".");
					$total_general_deuda=number_format($total_general_deuda,2,",",".");
					$total_general_prog_t_ant=number_format($total_general_prog_t_ant,2,",",".");
					$total_general_disp_fecha=number_format($total_general_disp_fecha,2,",",".");

//									$la_data_tot[$z+1]=array('total'=>'<b>TOTAL GENERAL</b>','pres_anual'=>$total_general_pres_anual,'aumento'=>$total_general_aumento,'disminucion'=>$total_general_disminucion,'actualizado'=>$total_general_actualizado,
//											 'compromiso'=>$total_general_compromiso,'causado'=>$total_general_causado,'pagado'=>$total_general_pagado,'deuda'=>$total_general_deuda,
//'disp_trim_ant'=>$total_general_prog_t_ant,
//											 'disp_fecha'=>$total_general_disp_fecha);
									$la_data_tot[$z+1]=array('total'=>'<b>TOTAL GENERAL</b>','pres_anual'=>$total_general_pres_anual,'aumento'=>$total_general_aumento,'disminucion'=>$total_general_disminucion,'actualizado'=>$total_general_actualizado,
											 'compromiso'=>$total_general_compromiso,'causado'=>$total_general_causado,'pagado'=>$total_general_pagado,'deuda'=>$total_general_deuda,
											 'disp_fecha'=>$total_general_disp_fecha);

					}
				}//if
			}//if
			  }//for
			if($z>0)
			{
            $io_encabezado=$io_pdf->openObject();
			uf_print_titulo_reporte($io_encabezado,$ls_programatica,$li_ano,$ls_meses,$ls_etiqueta,$ls_denestpro,$io_pdf);
            $io_titulo=$io_pdf->openObject();
			uf_print_titulo($io_titulo,$ls_etiqueta,$io_pdf);
		    $io_cabecera=$io_pdf->openObject();
			uf_print_cabecera($io_cabecera,$ls_etiqueta,$io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		 	uf_print_pie_cabecera($la_data_tot,$io_pdf);
			$io_pdf->stopObject($io_encabezado);
			$io_pdf->stopObject($io_titulo);
			$io_pdf->stopObject($io_cabecera);
			}
			}
			unset($la_data);
			unset($la_data_tot);
			if($li_i<$li_tot)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			} 
			$desde_aqui=$z;
			
		}//for				
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
			$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
			echo '<html><body>';
			echo trim($ls_pdfcode);
			echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}//else
	unset($io_report);
	unset($io_funciones);
?> 
