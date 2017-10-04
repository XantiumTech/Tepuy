<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Resumen de los pagos efectuados.
//  ORGANISMO: Ninguno en particular.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "close();";
		 print "opener.document.form1.submit();";		
		 print "</script>";		
	   }
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_codemp,$as_titulo,$ad_totpagefe,$as_codestpro,$as_fechas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//                 $ad_totpagefe = Monto Total de los Pagos Efectuados para una Estructura Presupuestaria.
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el encabezado del Reporte de las Solicitudes de Cotización.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_report;

		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(10,30,980,30);				
		$io_pdf->rectangle(10,440,988,130);
		$ls_ano = substr($as_fechas,-4);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,470,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(915,595,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(921,585,10,"Hora: ".date("h:i a")); // Agregar la hora
		$ls_codestpro1 = substr($as_codestpro,0,20);
		$ls_codestpro2 = substr($as_codestpro,20,6);
		$ls_codestpro3 = substr($as_codestpro,26,3);
		$ls_codestpro4 = substr($as_codestpro,29,2);
		$ls_codestpro5 = substr($as_codestpro,31,2);
		$la_denestpre  = $io_report->uf_load_denestpre($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);
		
		$ls_denestpro1 = $la_denestpre["denestpre1"][1];
		$ls_denestpro2 = $la_denestpre["denestpre2"][1];
		$ls_denestpro3 = $la_denestpre["denestpre3"][1];
		$ls_denestpro4 = $la_denestpre["denestpre4"][1];
		$ls_denestpro5 = $la_denestpre["denestpre5"][1];
		
		$la_data    = array(array('row1'=>'<b>   '.$as_titulo.' DE LA ZONA EDUCATIVA DEL ESTADO: </b>','row2'=>'<b>'.$_SESSION["la_empresa"]["estemp"].'</b>','row3'=>'<b>NÚMERO</b>','row4'=>'','row5'=>'<b>AÑO: </b>','row6'=>'<b>'.$ls_ano.'</b>'));
		$la_columna = array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>9,// Tamaño de Letras
						    'showLines'=>0,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>700,// Ancho de la tabla
						    'maxWidth'=>700,// Ancho Máximo de la tabla
						    'xPos'=>505,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('row1'=>array('justification'=>'left','width'=>490),
						                  'row2'=>array('justification'=>'center','width'=>100),
						 			      'row3'=>array('justification'=>'center','width'=>100),
						 			      'row4'=>array('justification'=>'center','width'=>100),
										  'row5'=>array('justification'=>'center','width'=>100),
   						 			      'row6'=>array('justification'=>'center','width'=>100)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config,$la_denestpre);
		
		$la_data[1] = array('column1'=>'','column2'=>'','column3'=>'');
		$la_data[2] = array('column1'=>'<b>'.strtoupper($_SESSION["la_empresa"]["nomestpro1"]).':</b>','column2'=>'<b>'.$ls_codestpro1.' - </b>','column3'=>'<b>'.$ls_denestpro1.'</b>');
		$la_data[3] = array('column1'=>'<b>'.strtoupper($_SESSION["la_empresa"]["nomestpro2"]).':</b>','column2'=>'<b>'.$ls_codestpro2.' - </b>','column3'=>'<b>'.$ls_denestpro2.'</b>');
		$la_data[4] = array('column1'=>'<b>'.strtoupper($_SESSION["la_empresa"]["nomestpro3"]).':</b>','column2'=>'<b>'.$ls_codestpro3.' - </b>','column3'=>'<b>'.$ls_denestpro3.'</b>');
		$la_data[5] = array('column1'=>'','column2'=>'','column3'=>'');

		$la_columna = array('column1'=>'','column2'=>'','column3'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>10,// Tamaño de Letras
						    'showLines'=>0,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>700,// Ancho de la tabla
						    'maxWidth'=>700,// Ancho Máximo de la tabla
						    'xPos'=>505,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('column1'=>array('justification'=>'right','width'=>400),
						                  'column2'=>array('justification'=>'right','width'=>295), // Justificación y ancho de la columna						 			   
						 			      'column3'=>array('justification'=>'left','width'=>295))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);		

		$la_data[1] = array('column1'=>'','column2'=>'','column3'=>'');
		$la_data[2] = array('column1'=>'<b>PAGOS EFECTUADOS:</b>','column2'=>'<b>'.number_format($ad_totpagefe,2,',','.').'</b>','column3'=>'');
		$la_data[3] = array('column1'=>'','column2'=>'','column3'=>'');

		$la_columna = array('column1'=>'','column2'=>'','column3'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>10,// Tamaño de Letras
						    'showLines'=>0,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>700,// Ancho de la tabla
						    'maxWidth'=>700,// Ancho Máximo de la tabla
						    'xPos'=>380,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('column1'=>array('justification'=>'right','width'=>150),
						                  'column2'=>array('justification'=>'right','width'=>150), // Justificación y ancho de la columna						 			   
						 			      'column3'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------	

    function uf_print_detalles($aa_data,&$io_pdf)
    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalles
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el encabezado del Reporte de las Solicitudes de Cotización.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
		$la_data[1] = array('item'=>'',
		                    'numche'=>'<b>CHEQUE N°</b>',
						    'fecche'=>'<b>FECHA</b>',
						    'numordcom'=>'<b>ORDEN NÚMERO</b>',
						    'nomproben'=>'<b>NOMBRE O RAZÓN SOCIAL</b>',
						    'rifpro'=>'<b>R.I.F/C.I</b>',
						    'denctaspg'=>'<b>DESCRIPCIÓN</b>',
						    'spgcta'=>'<b>IMPUTACIÓN PRESUPUESTARIA</b>',
						    'monsubtot'=>'<b>SUB-TOTAL</b>',
						    'montotiva'=>'<b>MONTO I.V.A</b>',
						    'monretiva'=>'<b>I.V.A RETENIDO</b>',
						    'monretislr'=>'<b>MONTO I.S.L.R</b>',
						    'montotche'=>'<b>MONTO CHEQUE</b>');

		$la_columna = array('item'=>'','numche'=>'','fecche'=>'','numordcom'=>'','nomproben'=>'','rifpro'=>'','denctaspg'=>'',
		                    'spgcta'=>'','monsubtot'=>'','montotiva'=>'','monretiva'=>'','monretislr'=>'','montotche'=>'');

		$la_config=array('showHeadings'=>0,// Mostrar encabezados
						 'fontSize'=>9,// Tamaño de Letras
						 'titleFontSize'=>11,// Tamaño de Letras de los títulos
						 'showLines'=>1,// Mostrar Líneas
						 'colGap'=>1,// Mostrar Líneas						 
						 'width'=>700,// Ancho de la tabla
						 'maxWidth'=>700,// Ancho Máximo de la tabla
						 'xPos'=>505,// Orientación de la tabla
						 'shaded'=>0,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('item'=>array('justification'=>'center','width'=>15),
						               'numche'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna						 			   
						 			   'fecche'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'numordcom'=>array('justification'=>'center','width'=>65),
   						 			   'nomproben'=>array('justification'=>'center','width'=>140),
									   'rifpro'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna						 			   
						 			   'denctaspg'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'spgcta'=>array('justification'=>'center','width'=>90),
   						 			   'monsubtot'=>array('justification'=>'center','width'=>70),
									   'montotiva'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna						 			   
						 			   'monretiva'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'monretislr'=>array('justification'=>'center','width'=>60),
   						 			   'montotche'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);


		$la_columna=array('item'=>'','numche'=>'','fecche'=>'','numordcom'=>'','nomproben'=>'','rifpro'=>'','denctaspg'=>'',
						  'spgcta'=>'','monsubtot'=>'','montotiva'=>'','monretiva'=>'','monretislr'=>'','montotche'=>'');
						  
		$la_config=array('showHeadings'=>0,// Mostrar encabezados
						 'fontSize'=>9,// Tamaño de Letras
						 'titleFontSize'=>9,// Tamaño de Letras de los títulos
						 'showLines'=>1,// Mostrar Líneas
						 'colGap'=>1,// Mostrar Líneas						 
						 'width'=>700,// Ancho de la tabla
						 'maxWidth'=>700,// Ancho Máximo de la tabla
						 'xPos'=>505,// Orientación de la tabla
						 'shaded'=>0,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('item'=>array('justification'=>'center','width'=>15),
						               'numche'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna						 			   
						 			   'fecche'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'numordcom'=>array('justification'=>'center','width'=>65),
   						 			   'nomproben'=>array('justification'=>'left','width'=>140),
									   'rifpro'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna						 			   
						 			   'denctaspg'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'spgcta'=>array('justification'=>'center','width'=>90),
   						 			   'monsubtot'=>array('justification'=>'right','width'=>70),
									   'montotiva'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna						 			   
						 			   'monretiva'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'monretislr'=>array('justification'=>'right','width'=>60),
   						 			   'montotche'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columna,'',$la_config);
		unset($aa_data,$la_columna,$la_config);						  
	}// end function uf_print_detalles
	
    function uf_print_totales($ad_montotsub,$ad_totmoniva,$ad_totretiva,$ad_totretisl,$ad_totmonche,&$io_pdf)
    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la sumatoria de los detalles asociados a una Estructura Presupuestaria.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
		$ld_montotsub = number_format($ad_montotsub,2,',','.');
		$ld_totmoniva = number_format($ad_totmoniva,2,',','.');
		$ld_totretiva = number_format($ad_totretiva,2,',','.');
		$ld_totretisl = number_format($ad_totretisl,2,',','.');
		$ld_totmonche = number_format($ad_totmonche,2,',','.');
		
		$la_data[1] = array('item'=>'',
		                    'numche'=>'',
						    'fecche'=>'',
						    'numordcom'=>'',
						    'nomproben'=>'',
						    'rifpro'=>'',
						    'denctaspg'=>'',
						    'spgcta'=>'<b>TOTAL Bs....</b>',
						    'monsubtot'=>'<b>'.$ld_montotsub.'</b>',
						    'montotiva'=>'<b>'.$ld_totmoniva.'</b>',
						    'monretiva'=>'<b>'.$ld_totretiva.'</b>',
						    'monretislr'=>'<b>'.$ld_totretisl.'</b>',
						    'montotche'=>'<b>'.$ld_totmonche.'</b>');

		$la_columna = array('item'=>'','numche'=>'','fecche'=>'','numordcom'=>'','nomproben'=>'','rifpro'=>'','denctaspg'=>'',
		                    'spgcta'=>'','monsubtot'=>'','montotiva'=>'','monretiva'=>'','monretislr'=>'','montotche'=>'');

		$la_config=array('showHeadings'=>0,// Mostrar encabezados
						 'fontSize'=>9,// Tamaño de Letras
						 'titleFontSize'=>11,// Tamaño de Letras de los títulos
						 'showLines'=>1,// Mostrar Líneas
						 'colGap'=>1,// Mostrar Líneas						 
						 'width'=>990,// Ancho de la tabla
						 'maxWidth'=>990,// Ancho Máximo de la tabla
						 'xPos'=>505,// Orientación de la tabla
						 'shaded'=>0,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('item'=>array('justification'=>'center','width'=>15),
						               'numche'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna						 			   
						 			   'fecche'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'numordcom'=>array('justification'=>'center','width'=>65),
   						 			   'nomproben'=>array('justification'=>'center','width'=>140),
									   'rifpro'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna						 			   
						 			   'denctaspg'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'spgcta'=>array('justification'=>'left','width'=>90),
   						 			   'monsubtot'=>array('justification'=>'right','width'=>70),
									   'montotiva'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna						 			   
						 			   'monretiva'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'monretislr'=>array('justification'=>'right','width'=>60),
   						 			   'montotche'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);
	}// end function uf_print_detalles

	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la sumatoria de los detalles asociados a una Estructura Presupuestaria.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	    $la_data[1] = array('column1'=>'','column2'=>'','column3'=>'');
		$la_data[2] = array('column1'=>'','column2'=>'','column3'=>'');
		$la_data[3] = array('column1'=>'<b>____________________________</b>','column2'=>'<b>____________________________</b>','column3'=>'<b>________________________</b>');
		$la_data[4] = array('column1'=>'<b>            ELABORADO POR:</b>','column2'=>'<b>  REVISADO POR:</b>','column3'=>'<b>  APROBADO POR:</b>');
		$la_data[5] = array('column1'=>'<b>             DURÁN WILMER</b>','column2'=>'<b>LIC. RAMÓN CABELLO</b>','column3'=>'<b>LIC. ALEXANDER ESCUDERO</b>');
		$la_data[6] = array('column1'=>'<b>              C.I. 14.750.231</b>','column2'=>'<b>C.I. 13.843.266</b>','column3'=>'<b>C.I. 14.649.792</b>');

		$la_columna = array('column1'=>'','column2'=>'','column3'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>10,// Tamaño de Letras
						    'showLines'=>0,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>700,// Ancho de la tabla
						    'maxWidth'=>700,// Ancho Máximo de la tabla
						    'xPos'=>505,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('column1'=>array('justification'=>'left','width'=>400),
						                  'column2'=>array('justification'=>'center','width'=>295), // Justificación y ancho de la columna						 			   
						 			      'column3'=>array('justification'=>'center','width'=>295))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);	
	}// end function uf_print_firmas
	
require_once("tepuy_scb_report.php");
require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("../../shared/class_folder/class_sql.php");	
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_funciones.php");

$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_report  = new tepuy_scb_report($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

$ls_fecdes = $_GET["fecdes"];
$ls_fechas = $_GET["fechas"];
$ls_tipres = $_GET["tipres"];
$lb_valido = true;
$ls_titulo = "RESUMEN DE FONDOS EN ANTICIPO";	
$rs_data   = $io_report->uf_print_mayor_presupuestario($ls_fecdes,$ls_fechas,$ls_tipres,$lb_valido);
if ($lb_valido==false) // Existe algún error ó no hay registros
   {
     echo("<script language=JavaScript>");
 	 echo(" alert('No hay nada que Reportar !!!');"); 
	 echo(" close();");
	 echo("</script>");
   }
else
   {
     error_reporting(E_ALL);
     set_time_limit(1800);
     $io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
     $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
     $io_pdf->ezSetCmMargins(1.5,5,3,3); // Configuración de los margenes en centímetros
     $io_pdf->ezStartPageNumbers(550,18,9,'','',1); // Insertar el número de página
     $li_numrows = $io_sql->num_rows($rs_data);
	 if ($li_numrows>0)
	    {
		  $ls_numcheant = $ls_codestproant = "";$li_totrows = 0;
		  $li_i = $ld_montotsub = $ld_totmoniva = $ld_totretiva = $ld_totretisl = $ld_totmonche = 0;
		  while($row=$io_sql->fetch_row($rs_data))
			   {
				 $li_totrows++;
				 $ls_codestpro = trim($row["codestpro"]);
				 $ls_numrecdoc = $row["numrecdoc"];
				 $ls_codtipdoc = $row["codtipdoc"];
				 $ls_codpro    = $row["cod_pro"];
				 $ls_cedben    = $row["ced_bene"];				 
				 $ls_numche    = $row["numdoc"];
				 $ls_codban    = $row["codban"];
				 $ls_ctaban    = $row["ctaban"];
				 $ls_codope    = $row["codope"];
				 $ls_estmov    = $row["estmov"];
				 $ls_fecche    = $io_funcion->uf_convertirfecmostrar($row["fecmov"]);
				 $ls_procede   = $row["procede_doc"];
				 $ls_numdoccom = $row["numdoccom"];
				 if ($ls_procede=='SOCCOC')
				    {
					  $ls_numordcom = 'O/C '.substr($ls_numdoccom,-5);
					  $ls_tipordcom = 'B';
					}
			     elseif($ls_procede=='SOCCOS')
					{
					  $ls_numordcom = 'O/S '.substr($ls_numdoccom,-5);
					  $ls_tipordcom = 'S';
					} 			
				 $ls_nomproben = $row["nomproben"];
				 $ls_rifproben = $row["rifproben"];
				 $ls_denctaspg = $row["denominacion"];
				 $ls_codestpro = $row["codestpro"];
				 $ls_ctaspg    = $row["spg_cuenta"];
				 $ld_montotiva = 0;
				 $ld_montotiva = $io_report->uf_load_monto_impuesto($ls_numdoccom,$ls_tipordcom,$ls_ctaspg,$ls_codestpro);				 
				 $ld_monsubtot = $row["monto"];
				 if ($ls_numche!=$ls_numcheant)
				    {
				      $ls_numcheant = $ls_numche;
					  //$ld_monretiva = $io_report->uf_load_retenciones_cxp($ls_codemp,$ls_codban,$ls_ctaban,$ls_numche,$ls_codope,$ls_estmov,$ls_tipretislr,'IVA');
				      //$ld_monretisl = $io_report->uf_load_retenciones_cxp($ls_codemp,$ls_codban,$ls_ctaban,$ls_numche,$ls_codope,$ls_estmov,$ls_tipretislr,'ISLR');
				      $ld_monretiva = $io_report->uf_load_retencion($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,$ls_numdoccom,$ls_procede,'IVA');
					  $ld_monretisl = $io_report->uf_load_retencion($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,$ls_numdoccom,$ls_procede,'ISLR');
					  $ld_montotche = $row["montotche"];					
					}
				 else
				    {					  
					  $ld_monretiva = $ld_monretisl = $ld_montotche = 0;					
					}
				 
				 if ($li_totrows==1)
				    {
					  $li_i++;
					  $ld_montotsub += $ld_monsubtot;
					  $ld_totmoniva += $ld_montotiva;
					  $ld_totretiva += $ld_monretiva;
				      $ld_totretisl += $ld_monretisl;
				      $ld_totmonche += $ld_montotche;

					  $ls_codestproant = $ls_codestpro;
					  $la_detrep[$li_i] = array('item'=>str_pad($li_i,2,0,0),
				                                'numche'=>$ls_numche,
										        'fecche'=>$ls_fecche,
											    'numordcom'=>$ls_numordcom,
											    'nomproben'=>$ls_nomproben,
											    'rifpro'=>$ls_rifproben,
											    'denctaspg'=>$ls_denctaspg,
											    'spgcta'=>$ls_ctaspg,
											    'monsubtot'=>number_format($ld_monsubtot,2,',','.'),
											    'montotiva'=>number_format($ld_montotiva,2,',','.'),
											    'monretiva'=>number_format($ld_monretiva,2,',','.'),
											    'monretislr'=>number_format($ld_monretisl,2,',','.'),
											    'montotche'=>number_format($ld_montotche,2,',','.'));
					}
				 else
				    { 
					  if ($ls_codestpro!=$ls_codestproant)
					     {
						   uf_print_encabezado_pagina($ls_codemp,$ls_titulo,$ld_totmonche,$ls_codestproant,$ls_fechas,$io_pdf);
					       uf_print_detalles($la_detrep,$io_pdf);
		                   uf_print_totales($ld_montotsub,$ld_totmoniva,$ld_totretiva,$ld_totretisl,$ld_totmonche,$io_pdf);
						   uf_print_firmas($io_pdf);
					       $ls_codestproant = $ls_codestpro;
						   $ld_montotsub = $ld_totmoniva = $ld_totretiva = $ld_totretisl = $ld_totmonche = 0;
					       unset($la_detrep,$la_datestpre);
						   $li_i = 1;
						   $io_pdf->ezNewPage();
						 }
					  else
					     {
						   $li_i++;
						 }
					  $ld_montotsub += $ld_monsubtot;
					  $ld_totmoniva += $ld_montotiva;
					  $ld_totretiva += $ld_monretiva;
				      $ld_totretisl += $ld_monretisl;
				      $ld_totmonche += $ld_montotche;

					  $la_detrep[$li_i] = array('item'=>str_pad($li_i,2,0,0),
				                                'numche'=>$ls_numche,
										        'fecche'=>$ls_fecche,
											    'numordcom'=>$ls_numordcom,
											    'nomproben'=>$ls_nomproben,
											    'rifpro'=>$ls_rifproben,
											    'denctaspg'=>$ls_denctaspg,
											    'spgcta'=>$ls_ctaspg,
											    'monsubtot'=>number_format($ld_monsubtot,2,',','.'),
											    'montotiva'=>number_format($ld_montotiva,2,',','.'),
											    'monretiva'=>number_format($ld_monretiva,2,',','.'),
											    'monretislr'=>number_format($ld_monretisl,2,',','.'),
											    'montotche'=>number_format($ld_montotche,2,',','.'));
					}						  
				 if ($li_totrows==$li_numrows)
				    { 
					  uf_print_encabezado_pagina($ls_codemp,$ls_titulo,$ld_totmonche,$ls_codestpro,$ls_fechas,$io_pdf);
					  uf_print_detalles($la_detrep,$io_pdf);
		              uf_print_totales($ld_montotsub,$ld_totmoniva,$ld_totretiva,$ld_totretisl,$ld_totmonche,$io_pdf);
					  uf_print_firmas($io_pdf);				 
					}
			   }		 
		}
	 else
	    {
		  echo("<script language=JavaScript>");
		  echo(" alert('No hay nada que Reportar !!!');"); 
		  echo(" close();");
		  echo("</script>");		
		}
   }
if ($lb_valido)
   {
     $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
	 $io_pdf->ezStream(); // Mostramos el reporte
   }
else
   {
     echo("<script language=JavaScript>");
	 echo(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
	 echo(" close();");
     echo("</script>");		
   }
unset($io_report,$io_funcion);
?>	