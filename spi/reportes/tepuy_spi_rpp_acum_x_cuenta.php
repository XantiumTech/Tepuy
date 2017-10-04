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
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 27/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,550,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
		
		$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 27/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(530);
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','previsto'=>'<b>Previsto</b>',
		                     'aumento'=>'<b>Aumento</b>','disminución'=>'<b>Disminución</b>','montoactualizado'=>'<b>Monto Actualizado</b>',
							 'devengado'=>'<b>Devengado</b>','cobrado'=>'<b>Cobrado</b>','por_devengar'=>'<b>Por Devengar</b>',
							 'porcobrar'=>'<b>Por Cobrar</b>'));
		
		$la_columna=array('cuenta'=>'','denominacion'=>'','previsto'=>'','aumento'=>'','disminución'=>'','montoactualizado'=>'',
		                  'devengado'=>'','cobrado'=>'','por_devengar'=>'','porcobrar'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'center','width'=>160), // Justificación y  
						 			   'previsto'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'center','width'=>90), // Justificación y ancho 
									   'devengado'=>array('justification'=>'center','width'=>90), // Justificación y ancho de 
									   'cobrado'=>array('justification'=>'center','width'=>90), // Justificación y ancho de 
									   'por_devengar'=>array('justification'=>'center','width'=>90), // Justificación  
									   'porcobrar'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la 
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 27/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la 
						 			   'previsto'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>90), // Justificación y ancho 
									   'devengado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'cobrado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'por_devengar'=>array('justification'=>'right','width'=>90), // Justificación  
									   'porcobrar'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la 
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'previsto'=>'<b>Previsto</b>',
						   'aumento'=>'<b>Aumento</b>',
						   'disminución'=>'<b>Disminución</b>',
						   'montoactualizado'=>'<b>Monto Actualizado</b>',
						   'devengado'=>'<b>Devengado</b>',
						   'cobrado'=>'<b>Cobrado</b>',
						   'por_devengar'=>'<b>Por Devengar</b>',
						   'porcobrar'=>'<b>Por Cobrar</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 27/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la 
						 			   'previsto'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la
									   'montoactualizado'=>array('justification'=>'right','width'=>90), // Justificación y ancho 
									   'devengado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'cobrado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'por_devengar'=>array('justification'=>'right','width'=>90), // Justificación  
									   'porcobrar'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la 
		$la_columnas=array('total'=>'',
						   'previsto'=>'',
						   'aumento'=>'',
						   'disminución'=>'',
						   'montoactualizado'=>'',
						   'devengado'=>'',
						   'cobrado'=>'',
						   'por_devengar'=>'',
						   'porcobrar'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("tepuy_spi_reporte.php");
		$io_report = new tepuy_spi_reporte();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_ingreso.php");
		$io_fun_ingreso=new class_funciones_ingreso();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_cmbmesdes = $_GET["cmbmesdes"];
		$ldt_fecini=$li_ano."-".$ls_cmbmesdes."-01";
		$ldt_fecini_rep="01"."/".$ls_cmbmesdes."/".$li_ano;
		$ls_cmbmeshas = $_GET["cmbmeshas"];
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $ls_cmbnivel="1";
		}
		else
		{
          $ls_cmbnivel=$cmbnivel;
		}
        $ls_subniv=$_GET["checksubniv"];
		if($ls_subniv==1)
		{
		  $lb_subniv=true;
		}
		else
		{
		  $lb_subniv=false;
		}
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Acumulado por Cuentas desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin;
		$io_fun_ingreso->uf_load_seguridad_reporte("SPI","tepuy_spi_r_acum_x_cuentas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
     //----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		$ls_titulo=" <b> ACUMULADO POR CUENTAS  DESDE LA FECHA ".$ldt_fecini_rep."  HASTA  ".$fecfin." </b> ";
		$ls_tiporeporte=$_GET["tiporeporte"];
		global $ls_tiporeporte;
		require_once("../../shared/ezpdf/class.ezpdf.php");
		
		if($ls_tiporeporte==1)
		{
			require_once("tepuy_spi_reportebsf.php");
			$io_report=new tepuy_spi_reportebsf();
		}              
    //--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	
    $lb_valido=$io_report->uf_spi_reporte_acumulado_cuentas($ldt_fecini,$ldt_fecfin,$ls_cmbnivel,$lb_subniv,$ai_MenorNivel);
 
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
		$io_pdf->ezSetCmMargins(3.4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$ld_total_previsto=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_devengado=0;
		$ld_total_cobrado=0;
		//$ld_total_cobrado_anticipado=0;
		$ld_total_monto_actualizado=0;
		$ld_total_por_cobrar=0;
		$ld_total_por_devengar=0;
		$li_tot=$io_report->dts_reporte->getRowCount("spi_cuenta");
		for($z=1;$z<=$li_tot;$z++)
		{
			  $thisPageNum=$io_pdf->ezPageCount;
		      $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$z];
		      $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$z];
			  $ls_nivel=$io_report->dts_reporte->data["nivel"][$z];
			  $ld_previsto=$io_report->dts_reporte->data["previsto"][$z];
			  $ld_aumento=$io_report->dts_reporte->data["aumento"][$z];
			  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$z];
			  $ld_devengado=$io_report->dts_reporte->data["devengado"][$z];
			  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$z];
			  //$ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$z];
			  $ls_status=$io_report->dts_reporte->data["status"][$z];
			  $ld_monto_actualizado=$ld_previsto+$ld_aumento-$ld_disminucion;
			  $ld_por_devengar=$ld_previsto+$ld_aumento-$ld_disminucion-$ld_devengado;
			  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
			  
			  if($ls_nivel==1)
			  {
			  	  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
				  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
				  //$ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
				  $ld_total_por_devengar=$ld_total_por_devengar+$ld_por_devengar;
				  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
			  }
			  $ld_previsto=number_format($ld_previsto,2,",",".");
			  $ld_aumento=number_format($ld_aumento,2,",",".");
			  $ld_disminucion=number_format($ld_disminucion,2,",",".");
			  $ld_devengado=number_format($ld_devengado,2,",",".");
			  $ld_cobrado=number_format($ld_cobrado,2,",",".");
			  //$ld_cobrado_anticipado=number_format($ld_cobrado_anticipado,2,",",".");
			  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
			  $ld_por_devengar=number_format($ld_por_devengar,2,",",".");
			  $ld_por_cobrar=number_format($ld_por_cobrar,2,",",".");
			
			  $la_data[$z]=array('cuenta'=>$ls_spi_cuenta,'denominacion'=>$ls_denominacion,'previsto'=>$ld_previsto,
			                      'aumento'=>$ld_aumento,'disminución'=>$ld_disminucion,'montoactualizado'=>$ld_monto_actualizado,
								  'devengado'=>$ld_devengado,'cobrado'=>$ld_cobrado,'por_devengar'=>$ld_por_devengar,
								  'porcobrar'=>$ld_por_cobrar);
			
			 $ld_previsto=str_replace('.','',$ld_previsto);
			 $ld_previsto=str_replace(',','.',$ld_previsto);		
			 $ld_aumento=str_replace('.','',$ld_aumento);
			 $ld_aumento=str_replace(',','.',$ld_aumento);		
			 $ld_disminucion=str_replace('.','',$ld_disminucion);
			 $ld_disminucion=str_replace(',','.',$ld_disminucion);		
			 $ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
			 $ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);
			 $ld_devengado=str_replace('.','',$ld_devengado);
			 $ld_devengado=str_replace(',','.',$ld_devengado);		
			 $ld_cobrado=str_replace('.','',$ld_cobrado);
			 $ld_cobrado=str_replace(',','.',$ld_cobrado);		
			 //$ld_cobrado_anticipado=str_replace('.','',$ld_cobrado_anticipado);
			 //$ld_cobrado_anticipado=str_replace(',','.',$ld_cobrado_anticipado);		
			 $ld_por_cobrar=str_replace('.','',$ld_por_cobrar);
			 $ld_por_cobrar=str_replace(',','.',$ld_por_cobrar);
			 $ld_por_devengar=str_replace('.','',$ld_por_devengar);
			 $ld_por_devengar=str_replace(',','.',$ld_por_devengar);

			if($z==$li_tot)
			{
			  
			  $ld_total_previsto=number_format($ld_total_previsto,2,",",".");
			  $ld_total_aumento=number_format($ld_total_aumento,2,",",".");
			  $ld_total_disminucion=number_format($ld_total_disminucion,2,",",".");
			  $ld_total_devengado=number_format($ld_total_devengado,2,",",".");
			  $ld_total_cobrado=number_format($ld_total_cobrado,2,",",".");
			  //$ld_total_cobrado_anticipado=number_format($ld_total_cobrado_anticipado,2,",",".");
			  $ld_total_monto_actualizado=number_format($ld_total_monto_actualizado,2,",",".");
			  $ld_total_por_devengar=number_format($ld_total_por_devengar,2,",",".");
			  $ld_total_por_cobrar=number_format($ld_total_por_cobrar,2,",",".");
	 
			  $la_data_tot[$z]=array('total'=>'<b>TOTAL</b>',
			                         'previsto'=>$ld_total_previsto,
									 'aumento'=>$ld_total_aumento,
									 'disminución'=>$ld_total_disminucion,
									 'montoactualizado'=>$ld_total_monto_actualizado,
									 'devengado'=>$ld_total_devengado,
									 'cobrado'=>$ld_total_cobrado,
									 'por_devengar'=>$ld_total_por_devengar,
									 'porcobrar'=>$ld_total_por_cobrar);
			}//if
		}//for
	    uf_print_cabecera($io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
        uf_print_pie_cabecera($la_data_tot,$io_pdf);		
		unset($la_data);
		unset($la_data_tot);			
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
	}
	unset($io_report);
	unset($io_funciones);
?> 