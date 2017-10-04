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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,$as_numorddes,$as_codproy,$as_nomproy,$as_denplantel,$as_direccion,
		                           $as_desmun,$as_desparr,$as_nomdirec,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo    // T�tulo del Reporte
		//	    		   as_numorddes // Numero de Orden de despacho
		//	    		   as_fecha     // Fecha 
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang     Modificado por: Ing. Gloriely Fr�itez
		// Fecha Creaci�n: 26/04/2006                Fecha Creaci�n: 29/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(420,915,150,40);
		$io_pdf->line(420,935,570,935);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],17,910,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	/*	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=320;*/
		$io_pdf->addText(235,930,11,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		//$io_pdf->addText($tm,545,11,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=490;
		$io_pdf->ezSetY(905);
		$io_pdf->addText(425,920,11,"Fecha:"); // Agregar la fecha
		$io_pdf->addText(460,920,11,$as_fecha); // Agregar la fecha
		$io_pdf->addText(425,940,11,"No.:"); // Agregar la fecha
		$io_pdf->addText(450,940,11,$as_numorddes); // Agregar la fecha
		$io_pdf->addText(530,970,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(540,960,7,date("h:i a")); // Agregar la Hora
	    $la_data=array(array('name'=>'NOMBRE DEL PROYECTO: '.$as_nomproy.'     COD. PROYECTO: '.$as_codproy.''),
				       array('name'=>'PLANTEL/UNIDAD ADMINISTRATIVA: '.$as_denplantel.''),
					   array('name'=>'DIRECCI�N: '.$as_direccion.''),
					   array('name'=>'MUNICIPIO: '.$as_desmun.'     PARROQUIA:'.$as_desparr.''),
					   array('name'=>'DIRECTOR/RESPONSABLE: '.$as_nomdirec.''));
		
		$la_columnas=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 10, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.5,0.5,0.5),// Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>325); // Justificaci�n y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		// cuadro inferior
		$io_pdf->Rectangle(30,180,550,70);	//primer rect�ngulo
		$io_pdf->line(30,235,580,235);  // linea horizontal	
		$io_pdf->line(320,110,320,250); //linea vertical
		$io_pdf->Rectangle(30,110,550,70);
		$io_pdf->addText(95,240,7,"COORDINADOR DE DOTACI�N (Firma y Sello)"); // Agregar el t�tulo
		$io_pdf->addText(90,185,7,"Fecha ____/____/_____       Hora:___________"); // Agregar el t�tulo
		$io_pdf->addText(390,240,7,"JEFE DE ALMAC�N (Firma y Sello)"); // Agregar el t�tulo
		$io_pdf->addText(380,185,7,"Fecha ____/____/_____       Hora:___________"); // Agregar el t�tulo
		$io_pdf->line(30,165,580,165);  // linea horizontal	
		$io_pdf->addText(110,170,7,"RESPONSABLE (Firma y Sello)"); // Agregar el t�tulo
		$io_pdf->addText(90,113,7,"Fecha ____/____/_____       Hora:___________"); // Agregar el t�tulo
		$io_pdf->addText(390,170,7,"COMUNIDAD EDUCATIVA (Firma y Sello)"); // Agregar el t�tulo
		$io_pdf->addText(380,113,7,"Fecha ____/____/_____       Hora:___________"); // Agregar el t�tulo
		$io_pdf->addText(30,102,7,"REALIZADO POR: " .$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]); // Agregar el t�tulo
		$io_pdf->addText(245,92,7,"Fecha ____/____/____"); // Agregar el t�tulo
		$io_pdf->addText(250,80,7,"Hora:____________"); // Agregar el t�tulo
		$io_pdf->addText(245,60,7,"Fecha ____/____/____"); // Agregar el t�tulo
		$io_pdf->addText(250,43,7,"Hora:___________"); // Agregar el t�tulo
		$io_pdf->Rectangle(320,40,260,70);
	    $io_pdf->line(320,97,580,97);  // linea horizontal
		$io_pdf->addText(390,102,7,"DEPARTAMENTO DE SEGURIDAD"); // Agregar el t�tulo
		$io_pdf->addText(380,43,7,"Fecha ____/____/_____       Hora:___________"); // Agregar el t�tulo
		
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
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->line(160,468,160,511);
		//$io_pdf->ezSetY(800);
		$io_pdf->ezSetDy(-5);
		$la_data=array(array('name'=>'<b>Solicitud</b>                             '.$as_numsol.''),
					   //array('name'=>'<b>Unidad Administrativa</b>      '.$as_denunidam.''),
					   array ('name'=>'<b>Observaciones</b>                  '.$as_obsdes.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		//$io_pdf->ezSetY(750);
		$la_columna=array('renglon'=>'<b>Rengl�n</b>',
		                  'articulo'=>'<b>Art�culo</b>',
						  'almacen'=>'<b>Almac�n</b>',
						  'impu'=>'<b>Imputaci�n Presupuestaria</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'solicitada'=>'<b>Solicitada</b>',
						  'despachada'=>'<b>Despachada</b>',
						  'precio'=>'<b>Precio Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('renglon'=>array('justification'=>'center','width'=>35),
						               'articulo'=>array('justification'=>'left','width'=>80), // Justificaci�n y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>110), // Justificaci�n y ancho de la columna
						 			   'impu'=>array('justification'=>'left','width'=>65),
									   'unidad'=>array('justification'=>'left','width'=>45), // Justificaci�n y ancho de la columna
						 			   'solicitada'=>array('justification'=>'right','width'=>52), // Justificaci�n y ancho de la columna
						 			   'despachada'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>50), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>62))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia        Modificado Por: Ing. Gloriely Fr�itez     
		// Fecha Creaci�n: 06/07/2006                 Fecha Modificaci�n: 28/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$la_columna=array('total'=>'',
						  'totsol'=>'',
						  'totart'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>265), // Justificaci�n y ancho de la columna
						 			   'totsol'=>array('justification'=>'right','width'=>62), // Justificaci�n y ancho de la columna
						 			   'totart'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>82))); */// Justificaci�n y ancho de la columna
		$la_columna=array('total'=>'',
		                  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>497), // Justificaci�n y ancho de la columna
						 			  'totmon'=>array('justification'=>'right','width'=>62))); 		
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>660, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallecontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallecontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('denartc'=>'<b>Art�culo</b>',
		                  'cuenta'=>'<b>Cuenta Contable</b>',
						  'debhab'=>'<b>Debe/Haber</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('denartc'=>array('justification'=>'left','width'=>319), // Justificaci�n y ancho de la columna
						               'cuenta'=>array('justification'=>'left','width'=>150), // Justificaci�n y ancho de la columna
						 			   'debhab'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>125))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Contable</b>',$la_config);
	}// end function uf_print_detallecontable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totalescontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totalescontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'debe'=>'',
						  'haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>469),
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_siv_class_report.php");
	$io_report=new tepuy_siv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ld_fecdes= $io_fun_inventario->uf_obtenervalor_get("fecdes","");

	$ls_titulo="NOTA DE DESPACHO";
	$ls_fecha=$ld_fecdes;
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_numorddes= $io_fun_inventario->uf_obtenervalor_get("numorddes","");
	$ls_codproy= $io_fun_inventario->uf_obtenervalor_get("codproy","");
	$ls_nomproy= $io_fun_inventario->uf_obtenervalor_get("nomproy","");
	$ls_codunisol= $io_fun_inventario->uf_obtenervalor_get("coduniadm","");
	$ls_denunisol= $io_fun_inventario->uf_obtenervalor_get("denunidam","");
	$ls_codplantel= $io_fun_inventario->uf_obtenervalor_get("codunides","");
	$ls_denplantel= $io_fun_inventario->uf_obtenervalor_get("denunides","");
	$ls_codmun= $io_fun_inventario->uf_obtenervalor_get("codmun","");
	$ls_desmun= $io_fun_inventario->uf_obtenervalor_get("desmun","");
	$ls_codparr= $io_fun_inventario->uf_obtenervalor_get("codpar","");
	$ls_desparr= $io_fun_inventario->uf_obtenervalor_get("despar","");
	$ls_nomdirec= $io_fun_inventario->uf_obtenervalor_get("nomdirec","");
	$ls_codpai= $io_fun_inventario->uf_obtenervalor_get("codpai","");
	$ls_despai= $io_fun_inventario->uf_obtenervalor_get("despai","");
	$ls_codest= $io_fun_inventario->uf_obtenervalor_get("codest","");
	$ls_desest= $io_fun_inventario->uf_obtenervalor_get("desest","");
	$ls_direccion= $io_fun_inventario->uf_obtenervalor_get("direccion","");
	$ld_desde="";
	$ld_hasta="";
	$li_ordenfec=0;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_despachos($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec)	; // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
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
		//$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf=new Cezpdf('LEGAL','portrait');  // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(7,10,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$ls_numorddes,$ls_codproy,$ls_nomproy,$ls_denplantel,$ls_direccion,
		                           $ls_desmun,$ls_desparr,$ls_nomdirec,$io_pdf); // Imprimimos el encabezado de la p�gina
		//$io_pdf->ezStartPageNumbers(730,50,10,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=1;//$io_report->ds->getRowCount("codper");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_total=0;
			$li_totcanart=0;
			$li_totcansol=0;
			$ls_numsol=$io_report->ds->data["numsol"][$li_i];
			$ls_coduniadm=$io_report->ds->data["coduniadm"][$li_i];
			$ls_denunidam=$io_report->ds->data["denuniadm"][$li_i];
			$ls_obsdes=$io_report->ds->data["obsdes"][$li_i];
			uf_print_cabecera($ls_numorddes,$ls_numsol,$ls_coduniadm,$ls_denunidam,$ls_obsdes,&$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_despacho($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				$ls_renglon=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $ls_renglon++;
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_cansol=     $io_report->ds_detalle->data["canorisolsep"][$li_s];
					$li_preuniart=  $io_report->ds_detalle->data["preuniart"][$li_s];
					$li_montotart=  $io_report->ds_detalle->data["montotart"][$li_s];						
					$ls_unidad=     $io_report->ds_detalle->data["unidad"][$li_s];
					$ls_spgcuenta=     $io_report->ds_detalle->data["spg_cuenta"][$li_s];
					$li_total=$li_total + $li_montotart;
					$li_totcanart=$li_totcanart + $li_canart;
					$li_totcansol=$li_totcansol + $li_cansol;
					if($ls_unidad=="D"){$ls_unidad="Detal";}
					else{$ls_unidad="Mayor";}

					$li_canart=number_format($li_canart,2,",",".");
					$li_cansol=number_format($li_cansol,2,",",".");
					$li_preuniart=number_format($li_preuniart,2,",",".");
					$li_montotart=number_format($li_montotart,2,",",".");

					$la_data[$li_s]=array('renglon'=>$ls_renglon,'articulo'=>$ls_denart,'almacen'=>$ls_nomfisalm,'impu'=>$ls_spgcuenta,'unidad'=>$ls_unidad,'solicitada'=>$li_cansol,'despachada'=>$li_canart,'precio'=>$li_preuniart,'total'=>$li_montotart);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_total=number_format($li_total,2,",",".");
				$li_totcanart=number_format($li_totcanart,2,",",".");
				$li_totcansol=number_format($li_totcansol,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','totsol'=>$li_totcansol,'totart'=>$li_totcanart,'vacio'=>'--','totmon'=>$li_total);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				$ld_fechaaux=$io_funciones->uf_convertirdatetobd($ls_fecha);
				$lb_existe=$io_report->uf_siv_load_dt_contable($ls_codemp,$ls_numorddes,$ld_fechaaux); // Obtenemos el detalle del reporte
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
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 