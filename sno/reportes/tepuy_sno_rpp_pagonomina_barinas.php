<?php
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
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","tepuy_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
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
		// Fecha Creación: 26/04/2006 
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
		//$io_pdf->addText(732,550,8,"12/08/2016"); // Agregar la Fecha
		//$io_pdf->addText(738,543,7,"10:44 am"); // Agregar la Hora

		$li_tm=$io_pdf->getTextWidth(5,$ls_diremp." Telef.: ".$ls_telemp);
		$tm=450-($li_tm/2);
		$io_pdf->addText($tm,31,5,$ls_diremp." ".$ls_telemp);
		$li_tm=$io_pdf->getTextWidth(5,$ls_ciuemp.", ESTADO ".$ls_estemp);
		$tm=450-($li_tm/2);
		$io_pdf->addText($tm,25,5,$ls_ciuemp.", ESTADO ".$ls_estemp);		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
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
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>660))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data2,$la_columnas2,'',$la_config2);

		}
		$io_pdf->ezSetDy(-3);
		$la_columnas=array('programatica'=>'<b>Programática</b>',
						   'cuenta'=>'<b>Partida</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'monto'=>'<b>Monto a Imputar Bs.</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>320),// Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_tabla,$la_columnas,'',$la_config);

	//	$io_pdf->ezTable($la_tabla_aporte_deducciones,$la_columnas,'',$la_config);	
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
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>               Concepto</b>',
						   'asignacion'=>'<b>Asignación        </b>',
						   'deduccion'=>'<b>Deducción        </b>',
						   'aporte'=>'<b>Aporte Patronal  </b>',
						   'neto'=>'<b>Neto            </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_2016($la_data1,$la_columna1,$la_configuracion,$letra,&$io_pdf)
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
						 'width'=>820, // Ancho de la tabla
						 'maxWidth'=>820, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				       	 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_configuracion); // Just y ancho de la columna
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



	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_neto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignación
		//	   			   ai_totaldeduccion // Total Deduccción
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('totales'=>'<b>Totales '.$ls_bolivares.'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'aporte'=>$ai_totalaporte,'neto'=>$ai_total_neto));
		$la_columna=array('totales'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>180), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_piecabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totasi,$ai_totded,$ai_totapo,$ai_totgeneral,$ai_total_trabajadores,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
//////////////////////// PIE DE PAGINA ///////////////////////////////////////
		$io_pdf->Rectangle(20,60,570,75);
		$io_pdf->line(20,122,590,122); //linea que divide firmas y sellos
		$io_pdf->line(20,68,590,68); //linea superior de la RECEPCION
		$io_pdf->line(20,113,590,113);	// Linea que se encuentra en el nivel inferior de ELABORADO POR:

		$io_pdf->line(160,60,160,123);	// ESTAS LINEAS DIVIDEN LOS ESP. VERT. DE LAS FIRMAS QUE APRUEBAN	
		$io_pdf->line(310,60,310,123);		
		$io_pdf->line(435,60,435,123);
		//$io_pdf->line(490,115,490,173);		
					
		$io_pdf->addText(225,125,6,"<b>FIRMAS Y SELLOS PARA LA APROBACIÓN DE LA NOMINA DE PAGO</b>"); // Agregar el título
		$as_nomusu=$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];
		$li_tm=$io_pdf->getTextWidth(13,$as_nomusu);
		$tm=104-($li_tm/2);
		$io_pdf->addText(50,116,6,"ELABORADO POR:"); // Agregar el título
		$io_pdf->addText($tm,62,6,$as_nomusu); // Agregar el título
		// JEFE DE NOMINAS ///
		$ls_jefe_nominas=$_SESSION["la_empresa"]["jefe_nominas"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_jefe_nominas);
		$tm=274-($li_tm/2);

		$io_pdf->addText($tm,62,6,$ls_jefe_nominas); // Agregar el Nombre del Jefe de Contabilidad
		$ls_cargo_nominas=$_SESSION["la_empresa"]["cargo_nominas"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_cargo_nominas);
		$tm=298-($li_tm/2);

		$io_pdf->addText($tm,116,6,$ls_cargo_nominas); // Agregar el título
		// ADMINISTRADOR (A) ///
		$ls_jefe_administracion=$_SESSION["la_empresa"]["jefe_administracion"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_jefe_administracion);
		$tm=430-($li_tm/2);

		$io_pdf->addText($tm,62,6,$ls_jefe_administracion); // Agregar el Nombre del Jefe de Contabilidad
		$ls_cargo_administracion=$_SESSION["la_empresa"]["cargo_administracion"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_cargo_administracion);
		$tm=410-($li_tm/2);

		$io_pdf->addText($tm,116,6,$ls_cargo_administracion); // Agregar el título

		// GERENTE ///
		$ls_gerente=$_SESSION["la_empresa"]["gerente"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_gerente);
		$tm=560-($li_tm/2);
		$io_pdf->addText($tm,62,6,$ls_gerente); // Agregar el Nombre del Jefe de Contabilidad

		$ls_cargo_gerente=$_SESSION["la_empresa"]["cargorep"];
		$li_tm=$io_pdf->getTextWidth(13,$ls_cargo_gerente);
		$tm=538-($li_tm/2);

		$io_pdf->addText($tm,116,6,$ls_cargo_gerente); // Agregar el título

//////////////////////////////////////////////////////////////////////////////
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		//$la_data=array(array('titulo'=>'<b>Total Nómina '.$ls_bolivares.': </b>','asignacion'=>$ai_totasi,
		//					 'deduccion'=>$ai_totded,'aporte'=>$ai_totapo,'neto'=>$ai_totgeneral));
		$la_data=array(array('trabajador'=>'<b>Nro. Trabajadores: '.$ai_total_trabajadores.'</b>','titulo'=>'<b>Total Nómina '.$ls_bolivares.': </b>','asignacion'=>$ai_totasi,
							 'deduccion'=>$ai_totded,'aporte'=>$ai_totapo,'neto'=>$ai_totgeneral));
		$la_columna=array('trabajador'=>'','titulo'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'','neto'=>'');
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
						 'cols'=>array('trabajador'=>array('justification'=>'right','width'=>96),
									   'titulo'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>78), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>78), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>78), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>78))); // Justificación y ancho de la columna
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
	$ls_titulo="<b>Reporte General de Pago</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$fuente=$io_fun_nomina->uf_obtenervalor_get("fuente","");
	$orden_adm=$io_fun_nomina->uf_obtenervalor_get("ubicadmin","");
	if($orden_adm=="1")$ls_orden="1";
	$historico=$io_fun_nomina->uf_obtenervalor_get("historico","");
	//print "historico: ".$historico;
	//die();
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte

	// AQUI ARMA LA FILA DE LOS TITULOS DE LOS CONCEPTOS DE LA NOMINA //
	/// FORMATO HORIZONTAL /// TIPO NOMINA DE BARINAS // AHORRO DE PAPEL //

	if($lb_valido)
	{
		if($historico!='1')
		{
			$tabla="sno_salida";
			$tabla1="sno_concepto";
			$lb_valido=$io_report->uf_cargar_conceptos($columnas,$columnas1,$titulos,$configuracion,$posicion,$ls_conceptocero,$ls_conceptop2,$tabla,$tabla1); // Cargar el DS con los datos de la cabecera del detalle del reporte
		}
		else
		{
			$tabla="sno_hsalida";
			$tabla1="sno_hconcepto";
			$lb_valido=$io_report->uf_cargar_conceptos($columnas,$columnas1,$posicion,$ls_conceptocero,$ls_conceptop2,$tabla,$tabla1); // Cargar el DS con los datos de la cabecera del detalle del reporte
		}
	}

	if($lb_valido)
	{
		$nconceptos=count($columnas);
		$lb_valido=$io_report->uf_pagonomina_personal($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('OFICIO','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(820,40,9,'','',1); // Insertar el número de página
		$io_pdf->FitWindow=true;
		$li_totrow=$io_report->DS->getRowCount("codper");
		$totales[1]=array();
		$totales[1][' '] = "<b>Totales</b>";
		$totales[1]['Nro. Trabajadores']= "<b>".$li_totrow."</b>";

		$la_formato[1]=array();
		$la_formato[1]['Cédula']=10;
		$la_formato[1]['Apellidos y Nombres']=80;
		$la_columna=array();
		//				  'concepto'=>'<b>                              Concepto</b>',
		//				  'signo'=>'<b>Signo</b>',
		//				  'prenomina'=>'<b>Prenómina        </b>',
		//				  'anterior'=>'<b>Anterior         </b>');

		for($i=2;$i<=($nconceptos-1);$i++)
		{
			$totales[1][$columnas[$i]['concepto']]=0.00;
		//	print "columna: ".$columnas[$i]['concepto'];
		//	print "Total: ".$totales[1][$columnas[$i]['concepto']];
		}
		//die();
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totalnomina=0;
			$li_totsueldo=0;
			$li_totant=0;
			$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_apenomper=$io_report->DS->data["apeper"][$li_i].", ". $io_report->DS->data["nomper"][$li_i];
			$ls_descar=$io_report->DS->data["descar"][$li_i];
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ls_codcueban=$io_report->DS->data["codcueban"][$li_i];
			$ls_unidadadm=$io_report->DS->data["codprouniadm"][$li_i];
			//uf_print_cabecera($ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$ls_codcueban,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
			$la_data1[$li_i]=array();
			$la_data1[$li_i]['Cédula']=$ls_cedper;
			$la_data1[$li_i]['Apellidos y Nombres']=$ls_apenomper;
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				$recorrido=1;
				$posanterior=0;
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					
					$li_totant=0;
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					//$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
					$ls_nomcon=$io_report->DS_detalle->data["conprep"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
					$totalcol=abs($io_report->DS_detalle->data["valsal"][$li_s]);
					switch($ls_tipsal)
					{
						case "A": // Asignacion
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "V1": // Asignacion
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "W1":  // Asignacion
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "D": //Deducción
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "V2": // Deduccion
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",","."); 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "W2": //Deducción
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P1": //Deducción
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "V3": //Deducción
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_totsueldo=$li_totsueldo+$io_report->DS_detalle->data["valsal"][$li_s];
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "W3": //Deducción
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P2":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "V4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "W4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "R":
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte="";
							break;
					}
					for($i=2;$i<=(count($columnas)-1);$i++)
					{

						//print "valor columna: ".$columnas[$i]['concepto']. " Valor Bs. ".$li_asignacion; 
						//print "valor columna: ".$columnas[$i]['concepto']. " CONCEPTO--> ".$ls_nomcon;
						if($columnas[$i]['concepto']==$ls_nomcon)
						{
						/////////////////////////////////////////////////////
							//$posanterior=$posicion[$i]-1;
							$aux=$columnas[$i]['concepto'];
							//$la_data1[$li_i][$ls_nomcon] =$li_valprenom;
							//$la_data1[$li_i][$columnas[$i]['concepto']] =$li_valprenom;
							$subtotal=0.00;
							$subtotal=abs($totales[1][$columnas[$i]['concepto']]);
							$subtotal=$subtotal+$li_valsal; //$totalcol;
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
							$la_data1[$li_i][$columnas[$i]['concepto']] =$li_asignacion;
						/*	$subtotal=0.00;
							$subtotal=abs($totales[1][$columnas[$i]['concepto']]);
							$subtotal=$subtotal+$totalcol;
							//print "llevo:".$subtotal;
							$totales[1][$columnas[$i]['concepto']]=$subtotal;*/
						//print "posicion:".$posicion[$i]."<br>";
						//if($columnas[$i]['concepto']== "Salario") //"S.S.O.")
						//{
						//	print "llevo acumulado de ".$columnas[$i]['concepto']." ".abs($totales[1][$columnas[$i]['concepto']])."<br>";
						//	print "voy en ".$ls_codper." ".$ls_nomper. " y en la columna: ".$i." Pos ".$posicion[$i]." y el valor que llevo deberia ser: ".$li_valprenom."<br>";
						//}

						//	print "<b>ENTRE a ".$ls_codconc." ".$ls_nomcon." y MI VALOR SERA: </b>".$la_data1[$li_i][$ls_nomcon]." en el recorrido: ".$recorrido." y la posicion en columnas es: ".$posicion[$i]."<br>";
							
						}
					//$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'asignacion'=>$li_asignacion,
					//					  'deduccion'=>$li_deduccion,'aporte'=>$li_aporte,'neto'=>'');
					$recorrido++;
					}// 2do for
				// RELLENA EN BLANCOS LOS CONCEPTOS QUE NO APARECEN AL FINAL DEL ARRAY ls_data1 //
				//print "ultimo encontrado: ".$posanterior." De ".count($columnas)." faltan: ".((count($columnas)-1)-$posanterior);
				}
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
				$li_totsueldo=$li_totalasignacion-$li_totaldeduccion;
				$li_totsueldo=$io_fun_nomina->uf_formatonumerico($li_totsueldo);
				$la_data1[$li_i][$columnas[count($columnas)-1]['concepto']] =$li_totsueldo;
				$io_report->DS_detalle->resetds("codconc");
  			    //uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
				$li_total_neto=$li_totalasignacion-$li_totaldeduccion;
				$li_totasi=$li_totasi+$li_totalasignacion;
				$li_totded=$li_totded+$li_totaldeduccion;
				$li_totapo=$li_totapo+$li_totalaporte;
				$li_totgeneral=$li_totgeneral+$li_total_neto;
				$li_totalasignacion=number_format($li_totalasignacion,2,",",".");
				$li_totaldeduccion=number_format($li_totaldeduccion,2,",",".");
				$li_totalaporte=number_format($li_totalaporte,2,",",".");
				$li_total_neto=number_format($li_total_neto,2,",",".");
				//uf_print_piecabecera($li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$io_pdf); // Imprimimos el pie de la cabecera*/
			/*	if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$ls_codcueban,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_piecabecera($li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$io_pdf); // Imprimimos el pie de la cabecera*/
			//	}
			}
			//unset($la_data);
			//unset($la_data1);
		}
		//die();
		$io_report->DS->resetds("codper");
		$li_totasi=number_format($li_totasi,2,",",".");
		$li_totded=number_format($li_totded,2,",",".");
		$li_totapo=number_format($li_totapo,2,",",".");
		$li_totgeneral=number_format($li_totgeneral,2,",",".");
		uf_print_detalle_2016($la_data1,$titulos,$configuracion,$fuente,$io_pdf); // Imprimimos el detalle 2016 
		//uf_print_pie_cabecera($li_totalnomina,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
		$li_total_pre=$io_fun_nomina->uf_formatonumerico(abs($li_total_pre));
		$totales[1]['Neto a Cobrar']=$li_totgeneral;
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
		$io_report->uf_cargar_presupuesto_aporte_deducciones($tabla_aporte_deducciones,$tabla,"tipsal","valsal");
		$io_report->uf_cargar_presupuesto_asignaciones($tabla_asignaciones,$tabla,"tipsal","valsal");
		uf_print_gasto_2016($tabla_asignaciones,"RESUMEN PRESUPUESTARIO",'1',$io_pdf);
		uf_print_gasto_2016($tabla_aporte_deducciones,"RESUMEN PRESUPUESTARIO (Deducciones y Aportes)",'2',$io_pdf);

		//uf_print_piepagina($li_totasi,$li_totded,$li_totapo,$li_totgeneral,$li_totrow,$io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			ob_get_contents();
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
