<?php
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
	ini_set('memory_limit','512M');
	//define( 'WP_MAX_MEMORY_LIMIT', '1G' );
	ini_set('display_errors', 1);
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_prenomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_hprenomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(30,40,780,40);
		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
		$ls_diremp = $_SESSION["la_empresa"]["direccion"];
		$ls_telemp = $_SESSION["la_empresa"]["telemp"];
		$ls_ciuemp = $_SESSION["la_empresa"]["ciuemp"];
		$ls_estemp = $_SESSION["la_empresa"]["estemp"];
		// variables despues del llamar el (logo, 22,695,52,60)
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=446-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		// RIF //
		//$ls_rif=$_SESSION["la_empresa"]["rifemp"];
		$io_pdf->addText(50,514,6,"RIF-".$ls_rifemp); // Agregar el título 3
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=446-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=446-($li_tm/2);
		$io_pdf->addText($tm,520,10,$as_desnom); // Agregar el título
		$io_pdf->addText(732,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(738,543,7,date("h:i a")); // Agregar la Hora

		$li_tm=$io_pdf->getTextWidth(5,$ls_diremp." Telef.: ".$ls_telemp);
		$tm=450-($li_tm/2);
		$io_pdf->addText($tm,31,5,$ls_diremp." ".$ls_telemp);
		$li_tm=$io_pdf->getTextWidth(5,$ls_ciuemp.", ESTADO ".$ls_estemp);
		$tm=450-($li_tm/2);
		$io_pdf->addText($tm,25,5,$ls_ciuemp.", ESTADO ".$ls_estemp);

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codper,$as_nomper,$pase,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // código del personal
		//	    		   as_nomper // Nombres y apellidos del personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Personal</b>  '.$as_codper.' - '.$as_nomper.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_gasto_2016($la_tabla,$titulo_res,$pase,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // cédula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   as_descar // descripción del cargo
		//	    		   as_desuniadm // descripción de la unidad administrativa
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   as_codcueban // código de lla cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//		$la_data=array(array('programatica'=>'<b>Categoria prográmatica</b>','cuenta'=>'<b>Partida Presupuestaria </b>','denominacion'=>'<b>Denominación</b>','monto'=>'<b>Monto a Imputar Bs.</b>'));
		if($pase=="1")
		{
			$io_pdf->ezSetDy(-40);
		}
		else
		{
			$io_pdf->ezSetDy(-10);
		}
		if(strlen($titulo_res)>0)
		{
			$la_data2=array(array('titulo'=>'<b>'.$titulo_res.'</b>'));
			$la_columnas2=array('titulo'=>'<b>'.$titulo_res.'</b>');
			
			$la_config2=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>750))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data2,$la_columnas2,'',$la_config2);

		}
		$io_pdf->ezSetDy(-3);
		$la_columnas1=array('programatica'=>'<b>Proyecto</b>',
						   'cuenta'=>'<b>Partida</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'monto'=>'<b>Monto a Imputar Bs.</b>');
		$la_config1=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>320),// Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_tabla,$la_columnas1,'',$la_config1);
	
	}// end function uf_print_gasto_2016

	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_2016($la_data1,$la_columna1,$letra,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		//$la_columna=array('codigo'=>'<b>Código</b>',
		//				  'concepto'=>'<b>                              Concepto</b>',
		//				  'signo'=>'<b>Signo</b>',
		//				  'prenomina'=>'<b>Prenómina        </b>',
		//				  'anterior'=>'<b>Anterior         </b>');
		$options = array('fontSize' => $letra, // Tamaño de Letras
				'titleFontSize' => $letra,  // Tamaño de Letras de los títulos
				'showLines'=>2,
				'shadeCol'=>array(0.9,0.9,0.9),'xOrientation'=>'center','width'=>815,); 

		/*$prueba=array();
		for($j=0;$j<=(count($columnas)-1);$j++)
		{
			$aux1=$columnas1[$j]['codigo'];
			$aux=$columnas[$j]['concepto'];
			$la_data1[$li_i][$aux] ="";
			//print "cargue vacio el concepto ".$aux1.$aux." valor ".$la_data1[$li_i][$aux]."recorrido:".$recorrido."<br>";
		}*/

		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 5, // Tamaño de Letras
						 'titleFontSize' => 5,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>840, // Ancho de la tabla
						 'maxWidth'=>840, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>
				array('cedula'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
			 		'nomper'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
			 		'SUELDO'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
			 		'Compensacion por Antiguedad'=>array('justification'=>'right','width'=>60), 
		 			'Prima Profesionalización'=>array('justification'=>'right','width'=>60), 
			 		'Prima Fiscal'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'Otras Compensaciones'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'S.S.O.'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'F.P.J.'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'F.A.O.V.'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'P.I.E.'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'Credito de Vivienda'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'SOVENPFA'=>array('justification'=>'right','width'=>60), // Just y ancho de la columna
			 		'SUEPUMB'=>array('justification'=>'right','width'=>60))); // Just y ancho de la columna
		//echo is_array($la_config) ? 'Si es Array' : 'No es un array';
		//die();
		//$io_pdf->ezTable($la_data,$la_columna,'',$options);//$la_config);
		$io_pdf->ezTable($la_data1, $la_columnas, '', $options); 
	}// end function uf_print_detalle_2016 
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina_2016($la_totales,$letra,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		//$la_columna=array('codigo'=>'<b>Código</b>',
		//				  'concepto'=>'<b>                              Concepto</b>',
		//				  'signo'=>'<b>Signo</b>',
		//				  'prenomina'=>'<b>Prenómina        </b>',
		//				  'anterior'=>'<b>Anterior         </b>');
		$options = array('fontSize' => $letra, // Tamaño de Letras
				'titleFontSize' => $letra,  // Tamaño de Letras de los títulos
				'showLines'=>2,
				'shaded'=>1, // Sombra entre líneas
				'shadeCol'=>array(0.9,0.9,0.9),'xOrientation'=>'center','width'=>815,); 

		$io_pdf->ezTable($la_totales, '', '', $options);
	}// end function uf_print_piepagina_2016 
	//--------------------------------------------------------------------------------------------------------------------------------


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'concepto'=>'<b>                              Concepto</b>',
						  'signo'=>'<b>Signo</b>',
						  'prenomina'=>'<b>Prenómina        </b>',
						  'anterior'=>'<b>Anterior         </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'concepto'=>array('justification'=>'left','width'=>180), // Justificación y ancho de la columna
						 			   'signo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totprenom,$ai_totant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totprenom // Total Prenómina
		//	   			   ai_totant // Total Anterior
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_pdf->ezSetDy(1);
		$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','prenomina'=>$ai_totprenom,'anterior'=>$ai_totant));
		$la_columna=array('total'=>'','prenomina'=>'','anterior'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>340), // Justificación y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->setColor(0,0,0);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totpre,$ai_tothis,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totpre // Total de Prenómina
		//	   			   ai_tothis // Total de Histórico
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;

		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total Prenómina '.$ls_bolivares.': </b>','prenomina'=>$ai_totpre,'historico'=>$ai_tothis));
		$la_columna=array('titulo'=>'','prenomina'=>'','historico'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>340), // Justificación y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'historico'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("tepuy_sno_class_report.php");
				$io_report=new tepuy_sno_class_report();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("tepuy_sno_class_report_historico.php");
				$io_report=new tepuy_sno_class_report_historico();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.";
			break;

		case "1":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("tepuy_sno_class_reportbsf.php");
				$io_report=new tepuy_sno_class_reportbsf();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("tepuy_sno_class_report_historicobsf.php");
				$io_report=new tepuy_sno_class_report_historicobsf();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Reporte de Prenómina</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$fuente=$io_fun_nomina->uf_obtenervalor_get("fuente","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte

	// AQUI ARMA LA FILA DE LOS TITULOS DE LOS CONCEPTOS DE LA NOMINA //
	/// FORMATO HORIZONTAL /// TIPO NOMINA DE BARINAS // AHORRO DE PAPEL //

	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cargar_conceptos($columnas,$columnas1,$titulos,$configuracion,$posicion,$ls_conceptocero,$ls_conceptop2,"sno_prenomina","sno_concepto"); // Cargar el DS con los datos de la cabecera del detalle del reporte
	}

	if($lb_valido)
	{
		//print "Array: ".$columnas;
		//print "Array: ".$cols; die();
		//print "Cantidad de Conceptos: ".$nconceptos;
		/*for($i=1;$i<=$nconceptos;$i++)
		{
			print $conceptos[$i];
		}
		die();*/
		$nconceptos=count($columnas);
		//print "Cantidad de Conceptos: ".$nconceptos;
		$lb_valido=$io_report->uf_prenomina_personal($ls_codperdes,$ls_codperhas,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		error_reporting(E_ALL);
		set_time_limit(5800);
		$io_pdf=new Cezpdf('A4','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(820,40,9,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		$totales[1]=array();
		$totales[1][' '] = "<b>Totales</b>";
		$totales[1]['Nro. Trabajadores']= "<b>".$li_totrow."</b>";
		for($i=2;$i<=($nconceptos-1);$i++)
		{
			$totales[1][$columnas[$i]['concepto']]=0.00;
			//print "columna: ".$columnas[$i]['concepto'];
			//print "Total: ".$totales[1][$columnas[$i]['concepto']];
		}
		$li_total_pre=0;
		$li_total_his=0;
		//$li_totrow=700;
		//if ($li_totrow>500)
		//{
		//print "Total registro: ".$li_totrow;

		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totprenom=0;
			$li_totsueldo=0;
			$li_totant=0;
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];

			//uf_print_cabecera($ls_codper,$ls_nomper,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_prenomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2); // Obtenemos el detalle del reporte
			$la_data1[$li_i]=array();
			$la_data1[$li_i]['Cédula']=$ls_cedper;
			$la_data1[$li_i]['Apellidos y Nombres']=$ls_nomper;
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
				//for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				//$li_totrow_det=200;
				$recorrido=1;
				$posanterior=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					//$totalcol=0.00;
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					//$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
					$ls_nomcon=$io_report->DS_detalle->data["conprep"][$li_s];
					$ls_tipprenom=rtrim($io_report->DS_detalle->data["tipprenom"][$li_s]);
					switch($ls_tipprenom)
					{
						case "A": // asignación
							$ls_tipprenom="ASIGNACIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "V1": // asignación
							$ls_tipprenom="ASIGNACIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "W1": // asignación
							$ls_tipprenom="ASIGNACIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "D": // deducción
							$ls_tipprenom="DEDUCCIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "V2": // deducción
							$ls_tipprenom="DEDUCCIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "W2": // deducción
							$ls_tipprenom="DEDUCCIÓN";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "P1": // aporte
							$ls_tipprenom="APORTE PATRONAL";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "V3": // aporte
							$ls_tipprenom="APORTE PATRONAL";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "W3": // aporte
							$ls_tipprenom="APORTE PATRONAL";
							$li_totprenom=$li_totprenom+$io_report->DS_detalle->data["valprenom"][$li_s];
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valprenom"][$li_s];
							$totalcol=abs($io_report->DS_detalle->data["valprenom"][$li_s]);
							$li_totant=$li_totant+$io_report->DS_detalle->data["valhis"][$li_s];
							break;

						case "P2": // aporte
							$ls_tipprenom="APORTE PATRONAL";
							break;

						case "R": // Reporte
							$ls_tipprenom="REPORTE";
							break;					

						case "B": // Reintegro de Deducción
							$ls_tipprenom="REINTEGRO DE DEDUCCIÓN";
							break;					

						case "E": // Reintegro de Asignación
							$ls_tipprenom="REINTEGRO DE ASIGNACIÓN";
							break;
					}
					$li_valprenom=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valprenom"][$li_s]));
					$li_valhis=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valhis"][$li_s]));
					for($i=2;$i<=(count($columnas)-1);$i++)
					{

				//		print "valor columna: ".$columnas[$i]['concepto']. " Valor Bs. ".$li_valhis; 
				//		print "valor columna: ".$columnas[$i]['concepto']. " CONCEPTO--> ".$ls_nomcon;
						if($columnas[$i]['concepto']==$ls_nomcon)
						{
						/////////////////////////////////////////////////////
							//$posanterior=$posicion[$i]-1;
							$aux=$columnas[$i]['concepto'];
							//$la_data1[$li_i][$ls_nomcon] =$li_valprenom;
							//$la_data1[$li_i][$columnas[$i]['concepto']] =$li_valprenom;
							$subtotal=0.00;
							$subtotal=abs($totales[1][$columnas[$i]['concepto']]);
							$subtotal=$subtotal+$totalcol;
							//print "llevo:".$subtotal;
							$totales[1][$columnas[$i]['concepto']]=$subtotal;
						// RELLENA EN BLANCOS LOS CONCEPTOS QUE NO APARECEN //
							if(($posicion[$i]-$posanterior)>1)
							{
								$aqui=$posanterior;
								$aqui++;
								$aqui++;
								for($j=$aqui;$j<=($posicion[$i]-1);$j++)
								{
									$aux1=$columnas1[$j]['codigo'];
									$aux=$columnas[$j]['concepto'];
									$la_data1[$li_i][$aux] ="";
									$subtotal=0.00;
									$totalcol=0.00;
									//$subtotal=$totales[1][$columnas[$j]['concepto']];
									//$subtotal=$subtotal+$totalcol;
									//$totales[1][$columnas[$j]['concepto']]=$subtotal;
				//print "cargue vacio el concepto ".$aux1.$aux." valor ".$la_data1[$li_i][$aux]."recorrido:".$recorrido."<br>";
								}
							}
							//print "columna: ".$columnas[$i]['concepto'];
						/////////////////////////////////////////////////////
							$posanterior=$posicion[$i]-1;
							$aux=$columnas[$i]['concepto'];
							//$la_data1[$li_i][$ls_nomcon] =$li_valprenom;
							$la_data1[$li_i][$columnas[$i]['concepto']] =$li_valprenom;
						/*	$subtotal=0.00;
							$subtotal=abs($totales[1][$columnas[$i]['concepto']]);
							$subtotal=$subtotal+$totalcol;
							//print "llevo:".$subtotal;
							$totales[1][$columnas[$i]['concepto']]=$subtotal;*/
						//print "posicion:".$posicion[$i]."<br>";
						//if($columnas[$i]['concepto']== "Neto a Cobrar ") //"S.S.O.")
						//{
						//	print "llevo acumulado de ".$columnas[$i]['concepto']." ".abs($totales[1][$columnas[$i]['concepto']])."<br>";
						//	print "voy en ".$ls_codper." ".$ls_nomper. " y en la columna: ".$i." Pos ".$posicion[$i]." y el valor que llevo deberia ser: ".$li_valprenom."<br>";
						//}

						//	print "<b>ENTRE a ".$ls_codconc." ".$ls_nomcon." y MI VALOR SERA: </b>".$la_data1[$li_i][$ls_nomcon]." en el recorrido: ".$recorrido." y la posicion en columnas es: ".$posicion[$i]."<br>";
							
						}
					}
					$recorrido++;

				//print "columnas data:".count($la_data1[$li_i]['monto']); die();
				//print "data-> ced: ".$la_data1[$li_i]['cedula']." nombre: ".$la_data1[$li_i]['nomper']."Monto: ".$la_data1[$li_i][$ls_nomcon];
				//die();
				//	$la_data[$li_s]=array('codigo'=>$ls_codconc,'concepto'=>$ls_nomcon,'signo'=>$ls_tipprenom,'prenomina'=>$li_valprenom,'anterior'=>$li_valhis);
				} // 2do for
				// RELLENA EN BLANCOS LOS CONCEPTOS QUE NO APARECEN AL FINAL DEL ARRAY ls_data1 //
				//print "ultimo encontrado: ".$posanterior." De ".count($columnas)." faltan: ".((count($columnas)-1)-$posanterior);

				if($posanterior<(count($columnas)-1))
				{
					$posanterior++;$posanterior++;
					for($j=$posanterior;$j<=(count($columnas)-2);$j++)
					{
						$aux1=$columnas1[$j]['codigo'];
						$aux=$columnas[$j]['concepto'];
						$la_data1[$li_i][$aux] ="";
						$subtotal=0.00;
						$totalcol=0.00;
						//$subtotal=$totales[1][$columnas[$j]['concepto']];
						//$subtotal=$subtotal+$totalcol;
						//$totales[1][$columnas[$j]['concepto']]=$subtotal;
				//	print "cargue vacio el concepto ".$aux1.$aux." valor ".$la_data1[$li_i][$aux]."recorrido:".$recorrido."<br>";
					}
				}
				$li_totsueldo=$io_fun_nomina->uf_formatonumerico($li_totsueldo);
				$la_data1[$li_i][$columnas[count($columnas)-1]['concepto']] =$li_totsueldo;
				//die();
				/////////////////////////////////////////////////////
				//         CHEQUEO LA CARGA DE DATOS DEL $la_data1 del la primera persona de la nomina     //
				/*print "cedula:".$la_data1[$li_i]['cedula']." Nombre:".$la_data1[$li_i]['nomper'];
				for($j=2;$j<=(count($columnas)-1);$j++)
				{
					$as_nomcon=$columnas[$j]['concepto'];

					print "Concepto: ".$as_nomcon." Valor:".$la_data1[$li_i][$as_nomcon];
				}

				die();
				///////////////////////////////////////////////////////////////				
*/
				//die();
				$io_report->DS_detalle->resetds("codconc");
				$li_total_pre=$li_total_pre+$li_totprenom;
				$li_total_his=$li_total_his+$li_totant;
				$li_totprenom=$io_fun_nomina->uf_formatonumerico(abs($li_totprenom));
				$li_totant=$io_fun_nomina->uf_formatonumerico(abs($li_totant));
				//uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			/*	uf_print_detalle_2016($la_data1,$columnas,$io_pdf); // Imprimimos el detalle 2016 
				uf_print_pie_cabecera($li_totprenom,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					//uf_print_cabecera($ls_codper,$ls_nomper,$io_pdf); // Imprimimos la cabecera del registro
					//uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_detalle_2016($la_data1,$columnas,$io_pdf);
					uf_print_pie_cabecera($li_totprenom,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
			
				} */
			}
			//unset($la_data);
		} // 1er For
		$io_report->DS->resetds("codper");
		// PERMITE HACER EL RECORRIDO COMPLETO DEL ARRAY DE DATOS //
		//echo is_array($columnas) ? 'Si es Array' : 'No es un array';
		//die();
		/*print "Numero de personas en la nomina: ".$li_totrow;
		for($i=1;$i<=$li_totrow;$i++)
		{
			print "cedula:".$la_data1[$i]['Cédula']." Nombre:".$la_data1[$i]['Apellidos y Nombres']."<br>";
			for($j=2;$j<=(count($columnas)-1);$j++)
			{
				$as_nomcon=$columnas[$j]['concepto'];
				$as_codconc=$columnas1[$j]['codigo'];
				print "Concepto: ".$as_nomcon." Valor:".$la_data1[$i][$as_nomcon]."<br>";
			}
			print "<br>";
		}
		die(); */
		////////////////////////////////////////////////////////////
		//$io_pdf->ezTable($la_data1, '', '', '');
		//$io_pdf->ezText("\n\n\n", 10);
		//$io_pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
		//$io_pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
		//ob_end_clean();
		//$io_pdf->ezStream();
		uf_print_detalle_2016($la_data1,$columnas,$fuente,$io_pdf); // Imprimimos el detalle 2016 
		//uf_print_pie_cabecera($li_totprenom,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
		$li_total_pre=$io_fun_nomina->uf_formatonumerico(abs($li_total_pre));
		$totales[1]['Neto a Cobrar']=$li_total_pre;
		//$li_total_his=$io_fun_nomina->uf_formatonumerico(abs($li_total_his));
	// Estable Formato a los Totales Acumulados //
		for($i=2;$i<=($nconceptos-2);$i++)
		{
			$total=$totales[1][$columnas[$i]['concepto']];
			$total=$io_fun_nomina->uf_formatonumerico(abs($total));
			$totales[1][$columnas[$i]['concepto']]=$total;
			//print $totales[$i]['total']."<br>";
		}
		//die();
		//uf_print_piepagina($li_total_pre,$li_total_his,$io_pdf);
		uf_print_piepagina_2016($totales,$fuente,$io_pdf);
		$tabla_asignaciones[1]=array();
		$tabla_aporte_deducciones[1]=array();
		$io_report->uf_cargar_presupuesto_aporte_deducciones($tabla_aporte_deducciones,"sno_prenomina","tipprenom","valprenom");
		$io_report->uf_cargar_presupuesto_asignaciones($tabla_asignaciones,"sno_prenomina","tipprenom","valprenom");
		//$tabla_asignaciones[1]=array();
		uf_print_gasto_2016($tabla_asignaciones,"RESUMEN PRESUPUESTARIO (Neto a Cobrar)",'1',$io_pdf);
		uf_print_gasto_2016($tabla_aporte_deducciones,"RESUMEN PRESUPUESTARIO (Deducciones y Aportes)",'2',$io_pdf);

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
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
