<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Factura
//  ORGANISMO: MATADERO MUNICIPAL WARYNA. ESTADO WARYNA C.A.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_print_encabezado_pagina($as_numfactura,$ad_fecfactura,$as_cedcli,$as_nomcli,$as_rifcli,$as_forpagfac,$as_denforpagfac,$ad_nomusu,&$aa_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
/*		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(150,705,440,40);
		$io_pdf->line(400,705,400,745);
		$io_pdf->line(400,725,590,725);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$tm=230;
        $io_pdf->addText($tm,720,14,"<b>".$ls_titulo."</b>");
		$io_pdf->addText(450,730,10,"     <b> No.:</b> ".$antes.substr($as_numordcom,9,6));
		$io_pdf->addText(450,709,10,"  <b>Fecha:</b> ".$ad_fecordcom);
		//$io_pdf->addText(554,750,7,date("d/m/Y"));
*/        
		$io_pdf->ezSetY(662);

		$la_data[1]  = array('titulo'=>'<b>'.$as_nomcli.'</b>');
		$la_columnas = array('titulo'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 9, // Tamaño de Letras
						     'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						 	 'shaded'=>0, // Sombra entre líneas
							 'xPos'=>170, // Orientación de la tabla
						 	 'width'=>280, // Ancho de la tabla
						 	 'maxWidth'=>280, // Ancho Máximo de la tabla
						 	 'xOrientation'=>'center', // Orientación de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'left','width'=>280))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetY(645);
		$aa_columnas=array('rifcli'=>'','fecha'=>'');
		//$aa_data=array(array('rifcli'=>'<b>'.$as_rifcli.'</b>'),
 		 //              array('fecha'=>'<b>'.$ad_fecfactura.'</b>'));
		$aa_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>200, // Orientación de la tabla
						 'width'=>270, // Ancho de la tabla						 
						 'maxWidth'=>270,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('rifcli'=>array('justification'=>'left','width'=>150),     // Justificación y ancho de la columna
						 		'fecha'=>array('justification'=>'right','width'=>120)));     // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$aa_columna,'',$aa_config);
		unset($aa_data);
		unset($aa_columna);
		unset($aa_config);
		//$io_pdf->ezSetY(630);
		$col=57;
		$fil=615;
		$salto=30;
		if($as_forpagfac=='EF'){ $io_pdf->addText(($col+$salto),$fil,10,"<b> X </b> ");} 
		if($as_forpagfac=='TD'){ $io_pdf->addText(($col+$salto),$fil,10,"<b> X </b> ");}
		if($as_forpagfac=='TD'){ $salto=($salto+30);$io_pdf->addText(($col+$salto),$fil,10,"<b> X </b> ");}
		if($as_forpagfac=='CH'){ $salto=($salto+70);$io_pdf->addText(($col+$salto),$fil,10,"<b> X </b> ");}
	
		if(($as_forpagfac=='TR')||($as_forpagfac=='DN'))
		{ 
			$salto=($salto+80);
			$io_pdf->addText(($col+$salto),$fil,10,"<b> X </b> ");
			$salto=($salto+40);
			$io_pdf->addText(($col+$salto),$fil,10,"<b>".$as_denforpagfac."</b> ");
		}
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($aa_data1,$ld_exonerado,$ld_baseimponible,$ld_impuesto,$as_titulo_por,$ld_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: función que imprime el detalle 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		// convierte los caracteres de el monto exento de iva, el iva y la base imponible en formato numerico //
		$ld_impuesto	 = number_format($ld_impuesto,2,",",".");
		$ld_exonerado	 = number_format($ld_exonerado,2,",",".");
		$ld_baseimponible= number_format($ld_baseimponible,2,",",".");
               // MODIFICA FORMATO DEL IMPUESTO //
		//if(substr($ld_impuesto,3,2)<50){$as_titulo_por=substr($as_titulo_por,0,3)."00"; }else{$as_titulo_por=$as_titulo_por+1;$as_titulo_por=substr($as_titulo_por,0,3)."00";} 
		// si base imponible es igual a 0 no muestra porcentaje en 0.00 //
		if($ld_impuesto==0){$as_titulo_por="___";}		
		if($ld_baseimponible==0){$as_titulo_por="___";}
		//$li_pos=174;//155 /Antes 181 ahora 171
		//$io_pdf->convertir_valor_mm_px($li_pos);		
		//$io_pdf->ezSetY(-5);
		//$io_pdf->ezSetDy(-10);
		$io_pdf->ezSetY(590);
		
		$ls_titulo_grid = "Productos";
		$la_columnas=array('cantidad'=>'',
			               'denominacion'=>'',
			               'precio'=>'',
			               'montot'=>'');

			$la_config      = array('showHeadings'=>0, // Mostrar encabezados
						 		    'fontSize' => 9, // Tamaño de Letras
						 			'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 			'showLines'=>0, // Mostrar Líneas
						 			'shaded'=>0, // Sombra entre líneas
									 'xPos'=>200, // Orientación de la tabla
						 			'width'=>430, // Ancho de la tabla

						 			'maxWidth'=>430, // Ancho Máximo de la tabla

									'xOrientation'=>'center', // Orientación de la tabla

									'cols'=>array('cantidad'=>array('justification'=>'right','width'=>65),     // Justificación y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>230), // Justificación y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>80),       // Justificación y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>90)));     // Justificación y ancho de la columna



	/*	$la_columnas=array('renglon'=>'<b>Renglón</b>',
					'codigo'=>'<b>Código</b>',
			               'denominacion'=>'<b>Denominación</b>',
			               'cantidad'=>'<b>Cant.</b>',
			               'precio'=>'<b>Precio ('.$ls_bolivares.')</b>',
			               'unidad'=>'<b>Unidad</b>',
			               'subtotal'=>'<b>SubTotal ('.$ls_bolivares.')</b>',
			               'cargo'=>'<b>% I.V.A.</b>',
			               'iva'=>'<b>Impuesto('.$ls_bolivares.')</b>',
			               'montot'=>'<b>Total ('.$ls_bolivares.')</b>');

			$la_config      = array('showHeadings'=>1, // Mostrar encabezados
						 		    'fontSize' => 7.5, // Tamaño de Letras
						 			'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 			'showLines'=>1, // Mostrar Líneas
						 			'shaded'=>0, // Sombra entre líneas
						 			'width'=>570, // Ancho de la tabla
						 			'maxWidth'=>570, // Ancho Máximo de la tabla
									'xOrientation'=>'center', // Orientación de la tabla
									'cols'=>array('renglon'=>array('justification'=>'center','width'=>40),      // Justificación y ancho de la columna
												  'codigo'=>array('justification'=>'center','width'=>80),      // Justificación y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   			  'cantidad'=>array('justification'=>'right','width'=>43),     // Justificación y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>62),       // Justificación y ancho de la columna
						 			   			  'unidad'=>array('justification'=>'right','width'=>61),       // Justificación y ancho de la columna
												  'subtotal'=>array('justification'=>'right','width'=>65),     // Justificación y ancho de la columna
									  			  'cargo'=>array('justification'=>'right','width'=>45),        // Justificación y ancho de la columna
									  			  'iva'=>array('justification'=>'right','width'=>60),        // Justificación y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>65)));     // Justificación y ancho de la columna
*/
		//$io_pdf->ezTable($aa_data1,$la_columnas,'<b>Detalles de los '.$ls_titulo_grid.'</b>',$la_config);
		$io_pdf->ezTable($aa_data1,$la_columnas,'',$la_config);
		$io_pdf->ezSetDy(-247);
	    	unset($aa_data1);
		unset($la_columnas);
		unset($la_config);
		
		 
		$la_data[1]  = array('titulo'=>'','contenido'=>$ld_exonerado);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 9, // Tamaño de Letras
						     'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						 	 'shaded'=>0, // Sombra entre líneas
							 'xPos'=>200, // Orientación de la tabla
						 	 'width'=>440, // Ancho de la tabla
						 	 'maxWidth'=>440, // Ancho Máximo de la tabla
						 	 'xOrientation'=>'center', // Orientación de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-4.5);
		$la_data[1]  = array('titulo'=>'','contenido'=>$ld_baseimponible);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 9, // Tamaño de Letras
						     'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						 	 'shaded'=>0, // Sombra entre líneas
							  'xPos'=>200, // Orientación de la tabla
						 	 'width'=>440, // Ancho de la tabla
						 	 'maxWidth'=>440, // Ancho Máximo de la tabla
						 	 'xOrientation'=>'center', // Orientación de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-4.5);
		$la_data[1]  = array('titulo'=>'','contenido'=>$ld_impuesto);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 9, // Tamaño de Letras
						     'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						 	 'shaded'=>0, // Sombra entre líneas
							 'xPos'=>200, // Orientación de la tabla
						 	 'width'=>440, // Ancho de la tabla
						 	 'maxWidth'=>440, // Ancho Máximo de la tabla
						 	 'xOrientation'=>'center', // Orientación de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$io_pdf->ezSetDy(-4.5);		
		$la_data[1]  = array('titulo'=>'','contenido'=>$ld_montot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 9, // Tamaño de Letras
						     'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						     'shaded'=>0, // Sombra entre líneas
						     'xPos'=>200, // Orientación de la tabla
						     'width'=>440, // Ancho de la tabla
						     'maxWidth'=>440, // Ancho Máximo de la tabla
						     'xOrientation'=>'center', // Orientación de la tabla
						     'cols'=>array('titulo'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------


	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($as_lugcom,$as_codmoneda,$ad_tasa,$ad_mondiv,$as_pais,$as_estado,$as_municipio,$as_parroquia,$ld_montot,$ls_monlet,$as_obsordcom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: ld_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_pdf->ezSetDy(-5);
		$la_data[1] = array('monlet'=>'<b>MONTO TOTAL EN LETRAS ('.$ls_bolivares.')</b>','monnum'=>'<b>MONTO TOTAL ('.$ls_bolivares.')</b>');
		$la_data[2] = array('monlet'=>$ls_monlet,'monnum'=>$ld_montot);
		$la_columnas=array('monlet'=>'','monnum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>7, // Tamaño de Letras
						 'titleFontSize'=>12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('monlet'=>array('justification'=>'left','width'=>400),
						               'monnum'=>array('justification'=>'right','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data=array(array('name'=>'<b>Observación: </b> '.$as_obsordcom));

		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>570, // Ancho de la tabla						 						 					 
						 'maxWidth'=>570); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
	


	}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Monto Bs.F.</b>','contenido'=>$li_montotaux,);
		$la_columnas=array('titulo'=>'','contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("tepuy_sfa_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_sfa.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");

	$in           = new tepuy_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_sfa   = new class_funciones_sfa();
	$io_report    = new tepuy_sfa_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_orgrif	  = $_SESSION["la_empresa"]["rifemp"];
	$ls_orgnom    = $_SESSION["la_empresa"]["nombre"];
	$ls_orgtit	  = $_SESSION["la_empresa"]["titulo"];
	$ls_codemp	  = $_SESSION["la_empresa"]["codemp"];

	//Instancio a la clase de conversión de numeros a letras.
	require_once("../../shared/class_folder/class_numero_a_letra.php");
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
	$ls_bolivares="Bs.";
		
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numfactura=$io_fun_sfa->uf_obtenervalor_get("numfactura","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data   = $io_report->uf_select_factura_imprimir($ls_numfactura,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	// buscar factura
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		$ls_descripcion="Generó el Reporte de la Factura";
		$lb_valido=$io_fun_sfa->uf_load_seguridad_reporte("SFA","tepuy_sfa_d_facturar.php",$ls_descripcion);
		if($lb_valido)
		// generar reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
 			$io_pdf->ezSetCmMargins(9,7,3.3,3); // Configuración de los margenes en centímetros   

		    //	$io_pdf->ezStartPageNumbers(588,760,8,'','',1); 
			if ($row=$io_sql->fetch_row($rs_data))
			// Datos Factura
			{
				$ls_numfactura = $row["numfactura"];
				$ls_denforpagfac = $row["denforpag"];
				//$ls_cedcli	  = trim($row["cedcli"]);
				$ls_cedcli=number_format($row["cedcli"],0,',','.');
				
				$ls_nomcli	  = trim($row["nomcli"]);
				$ls_apecli	  = trim($row["apecli"]);
				$ls_nombre	  = $ls_nomcli." ".$ls_apecli;;
				$cliente=$ls_nomcli." ".$ls_apecli;
				$ls_rifcli	  = trim($row["rifcli"]);
				if(strlen($ls_rifcli)==0)
				{
					$ls_rifcli=$ls_cedcli;
				}
				$ls_dircli	  = $row["dircli"];
				$ls_telcli	  = $row["telcli"];
				$ls_celcli	  = $row["celcli"];
				$ls_email	  = $row["email"];
				$ld_fecfactura = $row["fecfactura"];
				$ls_obsfac = $row["obsfac"];
				$ld_monsubtot	= number_format($row["monsubtot"],2,',','.');
				$ld_monimp	= number_format($row["monimp"],2,',','.');
				$ld_montot	= number_format($row["montot"],2,',','.');
				$ls_estfac    = $row["estfac"];
				$ld_porseg    = $row["porsegcom"];
				$ls_nomusu    = $row["nomusu"].' '.$row["apeusu"];
				$ls_tipforpag = $row["tipforpag"];

				$ld_fecfactura = $io_funciones->uf_convertirfecmostrar($ld_fecfactura);
				$aa_data[1]  = array('rifcli'=>'<b>'.$ls_rifcli.'</b>','fecha'=>'<b>'.$ld_fecfactura.'</b>'); 
			uf_print_encabezado_pagina($ls_numfactura,$ld_fecfactura,$ls_cedcli,$ls_nombre,$ls_rifcli,$ls_tipforpag,$ls_denforpagfac,$ls_nomusu,$aa_data,&$io_pdf);
			   	/////DETALLE  DE  LA ORDEN DE COMPRA
			   	$rs_datos = $io_report->uf_select_detalle_factura_imprimir($ls_numfactura,&$lb_valido);
			   	if ($lb_valido)
				// Detalle Factura
			   	{
		     	 		$li_totrows = $io_sql->num_rows($rs_datos);
					if ($li_totrows>0)
					// Si encuentra Detalles de Factura
					{
				    		$li_i = 0;
						$exonerado=0;
						$baseimponible=0;
						$impuesto=0;
						$titulo_por="";
				    		while($row=$io_sql->fetch_row($rs_datos))
						// While de detalles
						{
							$li_i++;
		// aqui selecciono donde voy a anidar el iva, dependiendo del porcentaje que se aplique al articulo //
							//$porcarart=$row["porcarart"];
							//if($porcarart!=""){$titulo_por=$porcarart;}
				//////////////////////////////////////////////////////////////////////////////////////////////////////
							$ls_codartser=$row["codpro"];
							$ls_denartser=$row["denpro"];
							$ls_unidad=$row["denunimed"];
						
							$li_cantartser  = $row["cantpro"];
							$ld_preartser    = $row["preunipro"];
							$ld_subtotartser = $row["monsubpro"];
							$ld_moncarpro = $row["moncarpro"];
							$ld_totartser    = $row["montotpro"];
							$ld_porcarart=$row["poriva"];
							$ld_porcarart=number_format($ld_porcarart,2);
							$ld_porcarart=str_replace(',','',$ld_porcarart);
							//if($ld_carartser>0)
							$ld_subtotartser1=number_format($ld_subtotartser,2);
							$ld_subtotartser1=str_replace(',','',$ld_subtotartser1);
							//$ld_moncarpro=number_format($ld_moncarpro,2);
							$ld_moncarpro=str_replace(',','',$ld_moncarpro);
							$ld_totartser1=number_format($ld_totartser1,2);
							$ld_totartser1=str_replace(',','',$ld_totartser1);
							if($ld_porcarart!=0.00)
							{
								$baseimponible=$baseimponible+$ld_subtotartser; // acumula la base imponible
								$impuesto=$impuesto+$ld_moncar1; // acumula el iva
								$titulo_por=$porcarart; // graba el porcentaje de iva aplicado
							}
							else
							{
								$exonerado=$exonerado+$ld_subtotartser; // acumula los articulos exentos de iva
								//print "Exonerado: ".$exonerado." sub-total: ".$ld_subtotartser;
							}
							$ld_preartser    = number_format($ld_preartser,2,",",".");
							$ld_subtotartser = number_format($ld_subtotartser,2,",",".");
							$ld_totartser	 = number_format($ld_totartser,2,",",".");
							$ld_carartser	 = number_format($ld_carartser,2,",",".");
							/*$impuesto	 = number_format($impuesto,2,",",".");
							$exonerado	 = number_format($exonerado,2,",",".");
							$baseimponible	 = number_format($baseimponible,2,",",".");*/
					// MUESTRA . EN LUGAR DE , AL MOMENTO DE MOSTRAR EL PORCENTAJE DE I.V.A. //
							$titulo_por	 = number_format($titulo_por,2,".",",");
						

							$la_data[$li_i]  = array('cantidad'=>number_format($li_cantartser,2,',','.'),
						                         'denominacion'=>$ls_denartser,
									 'precio'=>$ld_preartser,
									 'montot'=>$ld_totartser); 

			/*				$la_data[$li_i]  = array('renglon'=>$li_i,
									 'codigo'=>$ls_codartser,
						                         'denominacion'=>$ls_denartser,
									 'cantidad'=>number_format($li_cantartser,2,',','.'),
									 'precio'=>$ld_preartser,
									 'unidad'=>$ls_unidad,
									 'subtotal'=>$ld_subtotartser,
									 'cargo'=>$ld_porcarart,
									 'iva'=>$ld_moncarpro,
									 'montot'=>$ld_totartser); */
						}// While de detalles
					}// Si encuentra Detalles de Factura
					
					//uf_print_detalle($ls_estcondat,$la_data,$ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);
					uf_print_detalle($la_data,$exonerado,$baseimponible,$impuesto,$titulo_por,$ld_montot,&$io_pdf);
					//unset($la_data);
				}// Detalle Factura
		       }// Datos Factura

		}// generar reporte
		//uf_print_piecabecera($ls_estcondat,$ls_lugcom,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_denpai,$ls_denest,$ls_denmun,$ls_denpar,$ld_montot,$ls_monto,$ls_obsordcom,&$io_pdf);
	 /*   if ($ls_tiporeporte==0)
		   {
			 uf_print_piecabeceramonto_bsf($ld_montotaux,&$io_pdf);
		   }*/
	}// buscar factura
	if($lb_valido) // Si no ocurrio ningún error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo algún error
	{
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		print(" close();");
		print("</script>");		
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_sfa);
?>
