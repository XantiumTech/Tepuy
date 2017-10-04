<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.Miguel Palencia        
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(10,40,775,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],9,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_fecha); // Agregar el título
		$io_pdf->addText(735,565,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(740,558,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
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
		global $ls_tipoformato;
		$io_pdf->ezSetDy(30);
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Bs.F.";
		}

	/* (	'codigo'=>$ls_codpro,
		'producto'=>$ls_denpro,
		'unidad'=>$ls_denunimed,
		'precioa'=>$li_precioa,
		'preciob'=>$li_preciob,
		'cospropro'=>$li_cospropro,
		'existencia'=>$li_exipro,
		'entradas'=>$li_entradas,
		'salidas'=>$li_salidas); */

		$la_columna=array('codigo'=>'<b>Código</b>',
						  'producto'=>'<b>Producto</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'precioa'=>'<b>Precio A '.$ls_titulo.'</b>',
						  'preciob'=>'<b>Precio B '.$ls_titulo.'</b>',
						  'cospropro'=>'<b>Monto Facturado '.$ls_titulo.'</b>',
						  'existencia'=>'<b>Existencia Actual</b>',
						  'entradas'=>'<b>Entradas</b>',
						  'salidas'=>'<b>Salidas</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>780, // Ancho de la tabla
						 'maxWidth'=>780, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>40), // Justificación y ancho de la columna
						 			   'producto'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'precioa'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'preciob'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'cospropro'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totent,$ai_totsal,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totent // Total Entradas
		//	   			   ai_totsal // Total Salidas
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		//$la_data=array(array('name'=>'_______________________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>730); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificación y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sfa.php");
	$io_fun_inventario=new class_funciones_sfa();
	//$ls_tipoformato=$io_fun_inventario->uf_obtenervalor_get("tipoformato",0);
	//global $ls_tipoformato;
	require_once("tepuy_sfa_class_report.php");
	$io_report=new tepuy_sfa_class_report();
	$ls_titulo_report="Bs.";
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_fecdes=$io_fun_inventario->uf_obtenervalor_get("fecdesde","");
	$ld_fechas=$io_fun_inventario->uf_obtenervalor_get("fechasta","");

	$ls_titulo="<b> Resumen de Movimiento de Inventario </b>";
	if($ld_fecdesde!="")
	{$ls_fecha="<b> Periodo ".$ld_fecdes." - ".$ld_fechas."</b>";}
	else
	{$ls_fecha="";}
	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codprodes=$io_fun_inventario->uf_obtenervalor_get("codprodes","");
	$ls_codprohas=$io_fun_inventario->uf_obtenervalor_get("codprohas","");
	$li_ordenpro=$io_fun_inventario->uf_obtenervalor_get("ordenpro","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=true;//$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codpro,$ld_fecdesde,$ld_fechasta,$li_total,$li_ordenart); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte de Resumen de Inventario Desde el Producto ".$ls_codprodes." hasta ".$ls_codprohas." Periodo de fechas ".$ld_fecdesde." - ".$ld_fechasta;
		$io_fun_inventario->uf_load_seguridad_reporte("SFA","tepuy_sfa_r_mov_inventario.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(760,50,10,'','',1); // Insertar el número de página
		$li_totrow=1;//$io_report->ds->getRowCount("codart");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totent=0;
			$li_totsal=0;
			//$ls_codmov=     $io_report->ds->data["nummov"][$li_i];
			//$ls_codpro=     $io_report->ds->data["codart"][$li_i];
			//$ls_denart=     $io_report->ds->data["denart"][$li_i];
			uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_inventario($ls_codemp,$ls_codprodes,$ls_codprohas,$ld_fecdes,$ld_fechas,$li_ordenpro); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds->getRowCount("codpro");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codpro=     $io_report->ds->data["codpro"][$li_s];
					$ls_denpro=     $io_report->ds->data["denpro"][$li_s];
					$ls_denunimed=  $io_report->ds->data["denunimed"][$li_s];
					//$li_unidad=     $io_report->ds->data["unidad"][$li_s];
					$li_exipro=     $io_report->ds->data["exipro"][$li_s];
					$li_precioa=	$io_report->ds->data["preproa"][$li_s];
					$li_preciob=	$io_report->ds->data["preprob"][$li_s];
					$li_montofacturado=	$io_report->ds->data["montofacturado"][$li_s];
					//$li_preuniart=  $io_report->ds->data["ultcosart"][$li_s];
					$li_entradas=   $io_report->ds->data["entradas"][$li_s];
					$li_salidas=    $io_report->ds->data["salidas"][$li_s];
					//$li_ultcosart=  $io_report->ds->data["ultcosart"][$li_s];
					//$li_cosproart=  $io_report->ds->data["cosproart"][$li_s];
					$li_exipro=($li_exipro);//   /$li_unidad);
					$li_exiproaux=$li_exipro;
					$li_exipro=number_format($li_exipro,2,",",".");
					$li_precioa=number_format($li_precioa,2,",",".");
					$li_preciob=number_format($li_preciob,2,",",".");
					$li_montofacturado=number_format($li_montofacturado,2,",",".");
					$li_entradas=number_format($li_entradas,2,",",".");
					$li_salidas=number_format($li_salidas,2,",",".");
					//$li_ultcosart=number_format($li_ultcosart,2,",",".");
					//$li_cosproart=number_format($li_cosproart,2,",",".");
					$la_data[$li_s]=array('codigo'=>$ls_codpro,'producto'=>$ls_denpro,'unidad'=>$ls_denunimed,'precioa'=>$li_precioa,'preciob'=>$li_preciob,'cospropro'=>$li_montofacturado,'existencia'=>$li_exipro,'entradas'=>$li_entradas,'salidas'=>$li_salidas);

				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if($li_numpag>1)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
					uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro

					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				}
			}
			unset($la_data);			
		}
		
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_inventario);
?> 
