<?php
    	session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	ini_set('display_errors', 1);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,$as_numorddes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo    // Título del Reporte
		//	    		   as_numorddes // Numero de Orden de despacho
		//	    		   as_fecha     // Fecha 
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(576,530,150,40);
		$io_pdf->line(576,550,726,550);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],55,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=320;
		$io_pdf->addText($tm,545,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=490;
		$io_pdf->addText(580,535,11,"Fecha:"); // Agregar la fecha
		$io_pdf->addText(620,535,11,$as_fecha); // Agregar la fecha
		$io_pdf->addText(580,555,11,"No.:"); // Agregar la fecha
		$io_pdf->addText(620,555,11,$as_numorddes); // Agregar la fecha
		$io_pdf->addText(685,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,573,7,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        	$io_pdf->Rectangle(55,40,670,70);
		$io_pdf->line(55,53,725,53);		
		$io_pdf->line(55,97,725,97);		
		$io_pdf->line(200,40,200,110);		
		$io_pdf->line(360,40,360,110);		
		$io_pdf->line(550,40,550,110);		
		$io_pdf->addText(95,102,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(110,43,7,"ALMACÉN"); // Agregar el título
		$io_pdf->addText(245,102,7,"VERIFICADO POR"); // Agregar el título
		//$io_pdf->addText(250,43,7,"PRESUPUESTO"); // Agregar el título
		$ls_jefe_compras=$_SESSION["la_empresa"]["jefe_compr"];
		$ls_cargo_compras=$_SESSION["la_empresa"]["cargo_compr"];
		$li_tm=$io_pdf->getTextWidth(10,$ls_cargo_compras);
		$io_pdf->addText($li_tm+225,43,6,$ls_cargo_compras); // Agregar el título
		$io_pdf->addText(420,102,7,"AUTORIZADO POR"); // Agregar el título
		//$io_pdf->addText(400,43,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
		$io_pdf->addText(595,102,7,"MATERIALES RECIBIDOS"); // Agregar el título
		$io_pdf->addText(580,43,7,"FIRMA AUTOGRAFA, SELLO, FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numorddes,$as_numsol,$as_coduniadm,$as_denunidam,$as_obsdes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numtra    // numero de transaccion
		//	    		   as_codalmori // codigo de almacen origen
		//	    		   as_codalmdes // codigo de almacen destino
		//	    		   as_nomfisori // nombre fiscal de almacen origen
		//	    		   as_nomfisdes // nombre fiscal de almacen destino
		//	    		   as_obstra    // observaciones de la transferencia
		//	    		   ad_fecemi    // fecha de emision
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->line(160,468,160,511);
		$la_data=array(array('name'=>'<b>Solicitud</b>                      '.substr($as_numsol,10,5).''),
					   array('name'=>'<b>Unidad Solicitante</b>      '.$as_denunidam.''),
					   array ('name'=>'<b>Observaciones</b>                  '.$as_obsdes.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670); // Ancho Máximo de la tabla
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
		// Fecha Creación: 20/11/2016 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);

		$la_columna=array('codpro'=>'<b>Codigo</b>',
						  'denpro'=>'<b>Denominación</b>',
						  'unimed'=>'<b>Unidad de Medida</b>',
						  'exipro'=>'<b>Existencia</b>',
						  'canpro'=>'<b>Cantidad de Productos</b>',
						  'precio'=>'<b>Precio Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codpro'=>array('justification'=>'left','width'=>252), // Justificación y ancho de la columna
						 			   'denpro'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'unimed'=>array('justification'=>'left','width'=>45), // Justificación y ancho de la columna
						 			   'exipro'=>array('justification'=>'right','width'=>62), // Justificación y ancho de la columna
						 			   'canpro'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>82))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'totsol'=>'',
						  'totart'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>375), // Justificación y ancho de la columna
						 			   'totsol'=>array('justification'=>'right','width'=>62), // Justificación y ancho de la columna
						 			   'totart'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>82))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallecontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallecontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('denartc'=>'<b>Artículo</b>',
		                  'cuenta'=>'<b>Cuenta Contable</b>',
						  'debhab'=>'<b>Debe/Haber</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('denartc'=>array('justification'=>'left','width'=>319), // Justificación y ancho de la columna
						               'cuenta'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'debhab'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Contable</b>',$la_config);
	}// end function uf_print_detallecontable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totalescontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totalescontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'debe'=>'',
						  'haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>469),
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_sfa_class_report.php");
	$io_report=new tepuy_sfa_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_fecdes= $io_fun_inventario->uf_obtenervalor_get("fecmov","");

	$ls_titulo="Movimiento de Inventario";
	$ls_fecha=$ld_fecmov;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_numinginv= $io_fun_inventario->uf_obtenervalor_get("numinginv","");
	$ls_numcontrol= $io_fun_inventario->uf_obtenervalor_get("numcontrol","");
	$ld_fecmov= $io_fun_inventario->uf_obtenervalor_get("fecmov","");
	$ls_cedaut= $io_fun_inventario->uf_obtenervalor_get("cedaut","");
	$ls_nomaut= $io_fun_inventario->uf_obtenervalor_get("nomperaut","");
	$ls_logusr=$_SESSION["la_logusr"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_movimiento_inventario($ls_codemp,$ls_numinginv,$ls_numcontrol,$ld_fecmov,$ls_logusr)	; // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,4,3,3); // Configuración de los margenes en centímetros
		//uf_print_encabezado_pagina($ls_titulo,$ld_fecmov,$ls_numinginv,$io_pdf); // Imprimimos el encabezado de la página
		//$io_pdf->ezStartPageNumbers(730,50,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página
		$li_total=0;
		$li_totcanpro=0;
		$li_totproa=0;
		$li_totprob=0;
		$li_totproc=0;
		$li_totprod=0;

		//uf_print_cabecera($ls_numinginv,$ls_numcontint,$ld_fecmov,$ls_obsmov,&$io_pdf); // Imprimimos la cabecera del registro
		$rs_datos=$io_report->uf_sfa_select_dt_movimiento_inventario($ls_codemp,$ls_numinginv,$lb_valido); // Obtenemos el detalle del reporte
		if($lb_valido)
		{
			$li_totrows = $io_report->ds->num_rows($rs_datos);
			if ($li_totrows>0)
			// Si encuentra Detalles de Factura
			{
				$li_totproa= 0;
				$li_totprob= 0;
				$li_totproc= 0;
				$li_totprod= 0;
				while($row=$io_sql->fetch_row($rs_datos))
				{
					$ls_codpro=     $row["codpro"];
					$ls_denunimed=  $row["denunimed"];
					$ls_denpro=  $row["denpro"];
					$li_canpro=     $row["cantpro"];
					$li_exipro=     $row["exipro"];
					$li_preunipro=  $row["preunipro"];
					$li_preuniproa=  $row["preproa"];
					$li_preuniprob=  $row["preprob"];
					$li_preuniproc=  $row["preproc"];
					$li_preuniprod=  $row["preprod"];
					$li_totproa=  ($li_totproa+$li_preuniproa);
					$li_totprob=  ($li_totprob+$li_preuniprob);
					$li_totproc=  ($li_totproc+$li_preuniproc);
					$li_totprod=  ($li_totprod+$li_preuniprod);
					$li_totcanpro=($li_totcanpro + $li_canpro);
					$li_montotpro=($li_canpro * $li_preunipro);
					$li_canpro=number_format($li_canpro,2,",",".");
					$li_cansol=number_format($li_cansol,2,",",".");
					$li_exipro=number_format($li_exipro,2,",",".");
					$li_preunipro=number_format($li_preunipro,2,",",".");
					$li_montotpro=number_format($li_montotpro,2,",",".");
					$la_data[$li_s]=array('codpro'=>$ls_codpro,'denpro'=>$ls_denpro,'unimed'=>$ls_denunimed,'exipro'=>$li_exipro,'canpro'=>$li_canpro,'precio'=>$li_preunipro,'total'=>$li_montotpro);
				}

				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totproa=number_format($li_totproa,2,",",".");
				$li_totcanpro=number_format($li_totcanpro,2,",",".");
				$li_totproa=number_format($li_totproa,2,",",".");
				$li_totprob=number_format($li_totprob,2,",",".");
				$li_totproc=number_format($li_totproc,2,",",".");
				$li_totprod=number_format($li_totprod,2,",",".");
				$la_data1[1]=array('total'=>'<b>Totales</b>','totpro'=>$li_totcanpro,'totproa'=>$li_totproa,'totprob'=>$li_totprob);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				$ld_fechaaux=$io_funciones->uf_convertirdatetobd($ls_fecha);
				$lb_existe=$io_report->uf_siv_load_dt_contable($ls_codemp,$ls_numinginv,$ld_fechaaux); // Obtenemos el detalle del reporte
				if($lb_existe)
				{
					$li_montotdeb=0;
					$li_montothab=0;
					$li_totrow_det=$io_report->ds_detcontable->getRowCount("sc_cuenta");
					for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					{
						$ls_denartc=   $io_report->ds_detcontable->data["denart"][$li_s];
						$ls_cuenta=    $io_report->ds_detcontable->data["sc_cuenta"][$li_s];
						$ls_debhab=    $io_report->ds_detcontable->data["debhab"][$li_s];
						$li_monto=     $io_report->ds_detcontable->data["monto"][$li_s];
						if($ls_debhab=="D")
						{$li_montotdeb=$li_montotdeb+$li_monto;}
						else
						{$li_montothab=$li_montothab+$li_monto;}
						$li_monto=$io_fun_inventario->uf_formatonumerico($li_monto);
						$la_data[$li_s]=array('denartc'=>$ls_denartc,'cuenta'=>$ls_cuenta,'debhab'=>$ls_debhab,'monto'=>$li_monto);
					}
					$li_montotdeb=$io_fun_inventario->uf_formatonumerico($li_montotdeb);
					$li_montothab=$io_fun_inventario->uf_formatonumerico($li_montothab);
					$la_datatc[1]=array('total'=>"Total",'debe'=>"Debe ".$li_montotdeb,'haber'=>"Haber ".$li_montothab);
					uf_print_detallecontable($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_totalescontable($la_datatc,&$io_pdf);
				}
			}
			unset($la_data);			
			unset($la_datac);			
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 
