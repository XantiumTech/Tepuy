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
	function uf_print_encabezado_pagina($as_fecdes,$as_fechas,$ad_montotpro,&$io_pdf)
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
		global $io_funcion;
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(15,30,595,30);				
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,694,55,50); // Agregar Logo
		$io_pdf->addText(915,595,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(921,585,10,"Hora: ".date("h:i a")); // Agregar la hora
		
		$la_data[1] = array('row1'=>'<b>MAYOR PRESUPUESTARIO</b>');
		$la_data[2] = array('row1'=>'<b>FONDOS EN ANTICIPO</b>');
		$la_data[3] = array('row1'=>'<b>POR PROYECTOS</b>');
		$la_data[4] = array('row1'=>'');
		
		$la_columna = array('row1'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>9,// Tamaño de Letras
						    'showLines'=>1,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>580,// Ancho de la tabla
						    'maxWidth'=>580,// Ancho Máximo de la tabla
						    'xPos'=>305,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('row1'=>array('justification'=>'center','width'=>580)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);
		
		$la_data[1] = array('row1'=>'<b>   '.strtoupper($_SESSION["la_empresa"]["nombre"]).'</b>');
		
		$la_columna = array('row1'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>9,// Tamaño de Letras
						    'showLines'=>1,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>580,// Ancho de la tabla
						    'maxWidth'=>580,// Ancho Máximo de la tabla
						    'xPos'=>305,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('row1'=>array('justification'=>'left','width'=>580)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);
		
		$ls_fecdes = $io_funcion->uf_convertirfecmostrar($as_fecdes);
		$ls_fechas = $io_funcion->uf_convertirfecmostrar($as_fechas);
		$ld_montotpro = number_format($ad_montotpro,2,',','.');
		$la_data[1] = array('col1'=>'<b>   Periodo: Desde </b>'.$ls_fecdes.' <b>AL</b> '.$ls_fechas,'col2'=>' <b>Cantidad Pag. Rep.:</b>           '.$ld_montotpro);
		
		$la_columna = array('col1'=>'','col2'=>'');
		$la_config  = array('showHeadings'=>0,
		                    'fontSize'=>9,// Tamaño de Letras
						    'showLines'=>1,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>580,// Ancho de la tabla
						    'maxWidth'=>580,// Ancho Máximo de la tabla
						    'xPos'=>305,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('col1'=>array('justification'=>'left','width'=>350),
										  'col2'=>array('justification'=>'left','width'=>230)));
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
	    
		$la_data[1] = array('codestpro1'=>'<b>Código del Proyecto</b>',
		                    'denestpro2'=>'<b>Acción Específica del Proyecto</b>',
							'mondetspg'=>'<b>MONTO</b>');

		$la_columna = array('codestpro1'=>'','denestpro2'=>'','mondetspg'=>'');

		$la_config=array('showHeadings'=>0,// Mostrar encabezados
						 'fontSize'=>9,// Tamaño de Letras
						 'titleFontSize'=>11,// Tamaño de Letras de los títulos
						 'showLines'=>1,// Mostrar Líneas
						 'colGap'=>1,// Mostrar Líneas						 
						 'width'=>580,// Ancho de la tabla
						 'maxWidth'=>580,// Ancho Máximo de la tabla
						 'xPos'=>305,// Orientación de la tabla
						 'shaded'=>0,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('codestpro1'=>array('justification'=>'center','width'=>130),
						               'denestpro2'=>array('justification'=>'center','width'=>350), // Justificación y ancho de la columna						 			   
						 			   'mondetspg'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);


		$la_columna=array('codestpro1'=>'','denestpro2'=>'','mondetspg'=>'');
						  
		$la_config=array('showHeadings'=>0,// Mostrar encabezados
						 'fontSize'=>9,// Tamaño de Letras
						 'titleFontSize'=>9,// Tamaño de Letras de los títulos
						 'showLines'=>1,// Mostrar Líneas
						 'colGap'=>1,// Mostrar Líneas						 
						 'width'=>580,// Ancho de la tabla
						 'maxWidth'=>580,// Ancho Máximo de la tabla
						 'xPos'=>305,// Orientación de la tabla
						 'shaded'=>0,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('codestpro1'=>array('justification'=>'center','width'=>130),
						               'denestpro2'=>array('justification'=>'left','width'=>350), // Justificación y ancho de la columna						 			   
						 			   'mondetspg'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columna,'',$la_config);
		unset($aa_data,$la_columna,$la_config);						  
	}// end function uf_print_detalles
	
    function uf_print_total($ad_montotpro,&$io_pdf)
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
	    
		$ld_montotpro = number_format($ad_montotpro,2,',','.');
		
		$la_data[1] = array('monto1'=>'<b>MONTO TOTAL</b>','monto2'=>'<b>'.$ld_montotpro.'</b>');

		$la_columna = array('monto1'=>'','monto2'=>'');
		$la_config  = array('showHeadings'=>0,// Mostrar encabezados
						    'fontSize'=>9,// Tamaño de Letras
						    'titleFontSize'=>9,// Tamaño de Letras de los títulos
						    'showLines'=>1,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>580,// Ancho de la tabla
						    'maxWidth'=>580,// Ancho Máximo de la tabla
						    'xPos'=>305,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						    'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						    'cols'=>array('monto1'=>array('justification'=>'right','width'=>480),
						                  'monto2'=>array('justification'=>'right','width'=>100)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);
	}// end function uf_print_total


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

		$la_data[1] = array('column1'=>'<b>ADMINISTRADOR</b>','column2'=>'<b>JEFE DE ZONA</b>');
	    $la_data[2] = array('column1'=>'','column2'=>'');
		$la_data[3] = array('column1'=>'','column2'=>'');
		$la_data[4] = array('column1'=>'','column2'=>'');
		$la_data[5] = array('column1'=>'<b>____________________________</b>','column2'=>'<b>____________________________</b>');
		$la_data[6] = array('column1'=>'','column2'=>'');

		$la_columna = array('column1'=>'','column2'=>'');
		$la_config  = array('showHeadings'=>1,
		                    'fontSize'=>10,// Tamaño de Letras
						    'showLines'=>0,// Mostrar Líneas
						    'colGap'=>1,// Mostrar Líneas						 
						    'width'=>580,// Ancho de la tabla
						    'maxWidth'=>580,// Ancho Máximo de la tabla
						    'xPos'=>305,// Orientación de la tabla
						    'shaded'=>0,// Sombra entre líneas
						    'cols'=>array('column1'=>array('justification'=>'center','width'=>290),
						                  'column2'=>array('justification'=>'center','width'=>290))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);	
	}// end function uf_print_firmas
	
require_once("tepuy_scb_report.php");
require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");	
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_funciones.php");

$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_report  = new tepuy_scb_report($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
$io_dsdata  = new class_datastore();

$ls_fecdes = $_GET["fecdes"];
$ls_fechas = $_GET["fechas"];
$ls_tipres = $_GET["tipres"];
$lb_valido = true;
$ls_titulo = "MAYOR PRESUPUESTARIO";	
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
     $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
     $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
     $io_pdf->ezSetCmMargins(1.5,5,3,3); // Configuración de los margenes en centímetros
     $io_pdf->ezStartPageNumbers(550,18,9,'','',1); // Insertar el número de página
     $li_numrows = $io_sql->num_rows($rs_data);
	 if ($li_numrows>0)
	    {
		  $li_totrows = 0;
		  while($row=$io_sql->fetch_row($rs_data))
			   {
				 $ls_codestpro  = $row["codestpro"];
				 $ls_codestpro1 = substr($ls_codestpro,0,20);
				 $ls_codestpro2 = substr($ls_codestpro,20,6);
				 $ls_codestpro3 = substr($ls_codestpro,26,3);
				 $ls_codestpro4 = substr($ls_codestpro,29,2);
				 $ls_codestpro5 = substr($ls_codestpro,31,2);				 
				 $la_denestpre  = $io_report->uf_load_denestpre($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);
				 $ls_denestpro2 = $la_denestpre["denestpre2"][1];
				 unset($la_denestpre);
 				 $ld_mondetspg  = $row["monto"]; 
				 $io_dsdata->insertRow("codestpro1",$ls_codestpro1);
				 $io_dsdata->insertRow("codestpro2",$ls_codestpro2);
				 $io_dsdata->insertRow("denestpro2",$ls_denestpro2);
				 $io_dsdata->insertRow("mondetspg",$ld_mondetspg);
			   }		
		  $io_dsdata->group_by(array('0'=>'codestpro1','1'=>'codestpro2'),array('0'=>'mondetspg'),'mondetspg');
          $io_dsdata->sortData('codestpro2');
		  $li_totrow = $io_dsdata->getRowCount('codestpro2'); 
		  $ls_codestpro1ant = "";$li_fila = 0;$ld_montotpro = 0;
		  for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		      {
			    $ls_codestpro1 = $io_dsdata->getValue("codestpro1",$li_i);
				$ls_codestpro2 = $io_dsdata->getValue("codestpro2",$li_i);
				$ls_denestpro2 = $io_dsdata->getValue("denestpro2",$li_i);
				$ld_mondetspg  = $io_dsdata->getValue("mondetspg",$li_i);
				if ($li_i==1)
				   {
				     $li_fila++;
					 $la_datrep[$li_fila] = array('codestpro1'=>$ls_codestpro1,
					 						      'denestpro2'=>$ls_codestpro2.' - '.$ls_denestpro2,
											      'mondetspg'=>number_format($ld_mondetspg,2,',','.'));
					 $ld_montotpro += $ld_mondetspg;
					 $ls_codestpro1ant = $ls_codestpro1;
				   }
			    else
				   {
				     if ($ls_codestpro1!=$ls_codestpro1ant)
					    {
						  uf_print_encabezado_pagina($ls_fecdes,$ls_fechas,$ld_montotpro,$io_pdf);
						  uf_print_detalles($la_datrep,$io_pdf);
						  uf_print_total($ld_montotpro,$io_pdf);
						  uf_print_firmas($io_pdf);
						  $ls_codestpro1ant = $ls_codestpro1;
						  $ld_montotpro = 0;
						  $li_fila = 1;
						  unset($la_datrep);
						  $io_pdf->ezNewPage();
						}
				     else
					    {
						  $li_fila++;
						}
					 $ld_montotpro += $ld_mondetspg;
					 $la_datrep[$li_fila] = array('codestpro1'=>$ls_codestpro1,
												  'denestpro2'=>$ls_codestpro2.' - '.$ls_denestpro2,
												  'mondetspg'=>number_format($ld_mondetspg,2,',','.'));
				   }
			    if ($li_i==$li_totrow)
				   {
				     uf_print_encabezado_pagina($ls_fecdes,$ls_fechas,$ld_montotpro,$io_pdf);
					 uf_print_detalles($la_datrep,$io_pdf);
					 uf_print_total($ld_montotpro,$io_pdf);
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