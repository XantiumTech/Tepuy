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
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private 
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   as_periodo_comp // Descripción del periodo del comprobante
	//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
function uf_print_cabecera_detalle($as_spg_cuenta,$as_denominacion,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_spg_cuenta //cuenta
	//	    		   as_denominacion // denominacion 
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$io_pdf->ezSetDy(-10);
	$la_data=array(array('name'=>'<b>Cuenta</b>  '.$as_spg_cuenta.'  ---  '.$as_denominacion.''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>2, // Sombra entre líneas
					 'fontSize' => 8, // Tamaño de Letras
					 'shadeCol'=>array(0.9,0.9,0.9),
					 'shadeCol2'=>array(0.9,0.9,0.9),
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>510, // Orientación de la tabla
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990); // Ancho Máximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
}// end function uf_print_cabecera_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>1, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								   'comprobante'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
								   'documento'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
								   'detalle'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
								   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
								   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
								   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'devengado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'cobrado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'cobrado_anticipado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la
								   'porcobrar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
	
	$la_columnas=array('fecha'=>'<b>Fecha</b>',
					   'comprobante'=>'<b>Comprobante</b>',
					   'documento'=>'<b>Documento</b>',
					   'detalle'=>'<b>Detalle</b>',
					   'previsto'=>'<b>Previsto</b>',
					   'aumento'=>'<b>Aumento</b>',
					   'disminución'=>'<b>Disminución</b>',
					   'montoactualizado'=>'<b>Monto Actualizado</b>',
					   'devengado'=>'<b>Devengado</b>',
					   'cobrado'=>'<b>Cobrado</b>',
					   'cobrado_anticipado'=>'<b>Cobrado Anticipado</b>',
					   'porcobrar'=>'<b>Por Cobrar</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_saldo_por_devengar,$ad_total_previsto,$ad_total_aumento,$ad_total_disminución,                                   $ad_total_monto_actualizado,$ad_total_monto_devengado,$ad_total_monto_cobrado,                                   $ad_total_monto_cobrado_anticipado,$ad_total_monto_por_cobrar,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 29/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'__________________________________________________________________________________________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>520, // Orientación de la tabla
						 'width'=>990); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('comprobante'=>'','documento'=>'<b>SALDO POR DEVENGAR</b>','detalle'=>$ad_saldo_por_devengar,
		                 'previsto'=>$ad_total_previsto,'aumento'=>$ad_total_aumento,'disminución'=>$ad_total_disminución,
						 'montoactualizado'=>$ad_total_monto_actualizado,'devengado'=>$ad_total_monto_devengado,
						 'cobrado'=>$ad_total_monto_cobrado,'cobrado_anticipado'=>$ad_total_monto_cobrado_anticipado,
						 'porcobrar'=>$ad_total_monto_por_cobrar);
		$la_columnas=array('comprobante'=>'','documento'=>'','detalle'=>'','previsto'=>'','aumento'=>'',
		                   'disminución'=>'','montoactualizado'=>'','devengado'=>'','cobrado'=>'','cobrado_anticipado'=>'',
						   'porcobrar'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>502, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la 
						               'documento'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la 
									   'detalle'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la 
									   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y   
									   'devengado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'cobrado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'cobrado_anticipado'=>array('justification'=>'right','width'=>80), // Justificación y ancho 
									   'porcobrar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		require_once("tepuy_spi_funciones_reportes.php");
		$io_function_report = new tepuy_spi_funciones_reportes();
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spi_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spi_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
        $fecdes=$_GET["txtfecdes"];
	    $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
        $fechas=$_GET["txtfechas"];
	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
	    
		$ls_orden=$_GET["rborden"];
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Mayor Analitico desde la Cuenta ".$ls_cuentades." hasta ".$ls_cuentahas." y desde la fecha ".$fecdes." hasta ".$fechas;
		$io_fun_ingreso->uf_load_seguridad_reporte("SPI","tepuy_spi_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>MAYOR ANALITICO  DESDE  ".$fecdes."  AL  ".$fechas." </b>";
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
     $lb_valido=$io_report->uf_spi_reporte_mayor_analitico($ldt_fecdes,$ldt_fechas,$ls_cuentades,
														   $ls_cuentahas,$ls_orden);
 
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
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_report->dts_reporte->group_noorder("spi_cuenta");
		$li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
		$ld_total_previsto = 0;
		$ld_total_aumento  = 0;		  
		$ld_total_disminucion = 0;		 
		$ld_total_devengado = 0;		 		   
		$ld_total_cobrado = 0;		 		   		  
		$ld_total_cobrado_anticipado = 0;
	    $ld_total_monto_actualizado=0;
	    $ld_total_por_cobrar=0;
	    $ld_sub_total_previsto=0;
	    $ld_sub_total_aumento=0;
	    $ld_sub_total_disminucion=0;
	    $ld_sub_total_devengado=0;
	    $ld_sub_total_cobrado=0;
	    $ld_sub_total_cobrado_anticipado=0;
	    $ld_sub_total_monto_actualizado=0;
	    $ld_sub_total_por_cobrar=0;
		for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		{
		  $li_tmp=($li_s+1);
		  $io_pdf->transaction('start'); // Iniciamos la transacción
		  $thisPageNum=$io_pdf->ezPageCount;
		  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  
		  if ($li_s<$li_totrow_det)
		  {
				$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp];  
		  }
		  elseif($li_s==$li_totrow_det)
		  {
				$ls_spi_cuenta_next='no_next'; 
		  }
		  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  if($li_totrow_det==1)
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  
		  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
		  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
		  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
		  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
		  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
		  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
		  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
		  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
		  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
		  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
		  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
		  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
		  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
		  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
		  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
		  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
		  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
		  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
		  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
          $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
		  $ld_monto_actualizado_aux=$ld_monto_actualizado;
		  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
		  {
		      $ld_monto_actualizado=0;
		  }
		  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
		  
		  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
		  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
		  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
		  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
		  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
		  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
		  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
		 
		  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
		  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
		  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
		  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
		  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
		  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
		  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
		  
		  
		 if (!empty($ls_spi_cuenta))
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
									'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
			 
		  }
		  else
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
									'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
		  }
		  if (!empty($ls_spi_cuenta_next))
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
								    'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
          	 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
			 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		     $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
			 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
			 $ld_subtotal_previsto=$ld_sub_total_previsto;
			 $ld_subtotal_aumento=$ld_sub_total_aumento;
			 $ld_subtotal_disminucion=$ld_sub_total_disminucion;
			 $ld_subtotal_devengado=$ld_sub_total_devengado;
			 $ld_subtotal_cobrado=$ld_sub_total_cobrado;
			 $ld_subtotal_cobrado_anticipado=$ld_sub_total_cobrado_anticipado;
			 $ld_subtotal_monto_actualizado=$ld_sub_total_monto_actualizado;
			 $ld_subtotal_por_cobrar=$ld_sub_total_por_cobrar;
			 
			 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
			                       number_format($ld_sub_total_previsto,2,',','.'),
								   number_format($ld_sub_total_aumento,2,',','.'),
			                       number_format($ld_sub_total_disminucion,2,',','.'),
								   number_format($ld_sub_total_monto_actualizado,2,',','.'),
								   number_format($ld_sub_total_devengado,2,',','.'),
								   number_format($ld_sub_total_cobrado,2,',','.'),
								   number_format($ld_sub_total_cobrado_anticipado,2,',','.'),
								   number_format($ld_sub_total_por_cobrar,2,',','.'),$io_pdf);
		    
			$ld_sub_total_previsto=0;
			$ld_sub_total_aumento=0;
			$ld_sub_total_disminucion=0;
			$ld_sub_total_devengado=0;
			$ld_sub_total_cobrado=0;
			$ld_sub_total_cobrado_anticipado=0;
			$ld_sub_total_monto_actualizado=0;
			$ld_sub_total_por_cobrar=0;
			 if ($io_pdf->ezPageCount==$thisPageNum)
			 {// Hacemos el commit de los registros que se desean imprimir
				$io_pdf->transaction('commit');
			 }
			 elseif($thisPageNum>1)
			 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				 $io_pdf->transaction('rewind');
				 $io_pdf->ezNewPage(); // Insertar una nueva página
				 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
				 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				 $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
				 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
				 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
                                       number_format($ld_subtotal_previsto,2,',','.'),
									   number_format($ld_subtotal_aumento,2,',','.'),
				                       number_format($ld_subtotal_disminucion,2,',','.'),
									   number_format($ld_subtotal_monto_actualizado,2,',','.'),
									   number_format($ld_subtotal_devengado,2,',','.'),
									   number_format($ld_subtotal_cobrado,2,',','.'),
									   number_format($ld_subtotal_cobrado_anticipado,2,',','.'),
									   number_format($ld_subtotal_por_cobrar,2,',','.'),$io_pdf);
				
				$ld_subtotal_previsto=0;
				$ld_subtotal_aumento=0;
				$ld_subtotal_disminucion=0;
				$ld_subtotal_devengado=0;
				$ld_subtotal_cobrado=0;
				$ld_subtotal_cobrado_anticipado=0;
				$ld_subtotal_monto_actualizado=0;
				$ld_subtotal_por_cobrar=0;
			 }
			unset($la_data);			
		  }//if		
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
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
?> 