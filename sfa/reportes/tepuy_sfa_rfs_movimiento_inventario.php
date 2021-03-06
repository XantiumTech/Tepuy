<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  del Movimiento del Inventario
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

	function uf_print_encabezado_pagina($as_nummov,$ad_fecmov,$as_cedaut,$as_nomaut,$ad_nomusu,$as_titulo,&$aa_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(430,705,160,40);
		$io_pdf->line(430,705,430,745);
		$io_pdf->line(430,725,590,725);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
        	$io_pdf->addText(120,680,14,"<b>".$as_titulo."</b>");
		$io_pdf->addText(455,730,10,"  <b> No.:</b> ".$as_nummov);
		$io_pdf->addText(455,709,10,"  <b>Fecha:</b> ".$ad_fecmov);
		$io_pdf->addText(554,750,7,date("d/m/Y"));
		$io_pdf->ezSetDy(120);

		$la_data[1]  = array('titulo'=>'Personal que Autoriz� el Ingreso');
		$la_columnas = array('titulo'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 10, // Tama�o de Letras
						     'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						     'showLines'=>1, // Mostrar L�neas
						 	 'shaded'=>0, // Sombra entre l�neas
							 //'xPos'=>140, // Orientaci�n de la tabla
						 	 'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna)); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$aa_columnas=array('cedaut'=>'<b> N. de C�dula </b>','nomaut'=>'<b> Nombres y Apellidos: </b>');
		$aa_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
					//	 'xPos'=>170, // Orientaci�n de la tabla
						 'width'=>570, // Ancho de la tabla						 
						 'maxWidth'=>570,
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('cedaut'=>array('justification'=>'lefth','width'=>170),     // Justificaci�n y ancho de la columna
						 		'nomaut'=>array('justification'=>'center','width'=>400)));     // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$aa_columnas,'',$aa_config);
		unset($aa_data);
		unset($aa_columna);
		unset($aa_config);

       		$io_pdf->Rectangle(55,40,500,70);
		$io_pdf->line(55,53,555,53);		
		$io_pdf->line(55,97,555,97);		
		$io_pdf->line(215,40,215,110);		
		$io_pdf->line(390,40,390,110);		
	//	$io_pdf->line(550,40,550,110);		
		$io_pdf->addText(105,102,7,"ELABORADO POR"); // Agregar el t�tulo
		$i=120; 
		$li_tm=$io_pdf->getTextWidth(10,$ad_nomusu);
		$i=$i-($li_tm/2)+30;
		$io_pdf->addText($i,43,6,$ad_nomusu); // Agregar el t�tulo
		$io_pdf->addText(275,102,7,"VERIFICADO POR"); // Agregar el t�tulo
		$io_pdf->addText(450,102,7,"AUTORIZADO"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_nomaut);
		$i=475; 
		$li_tm=$io_pdf->getTextWidth(10,$as_nomaut);
		$i=$i-($li_tm/2)+30;
		$io_pdf->addText($i,43,6,$as_nomaut); // Agregar el t�tulo $as_nomaut


		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

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
		// Fecha Creaci�n: 20/11/2016 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);

		$la_columna=array('codpro'=>'<b>Codigo</b>',
						  'denpro'=>'<b>Denominaci�n</b>',
						  'unimed'=>'<b>Unidad de Medida</b>',
						  'exipro'=>'<b>Existencia</b>',
						  'canpro'=>'<b>Cantidad de Productos</b>',
						  'precio'=>'<b>Precio Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codpro'=>array('justification'=>'left','width'=>60), // Justificaci�n y ancho de la columna
						 			   'denpro'=>array('justification'=>'left','width'=>140), // Justificaci�n y ancho de la columna
						 			   'unimed'=>array('justification'=>'left','width'=>60), // Justificaci�n y ancho de la columna
						 			   'exipro'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'canpro'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalles de los Productos </b>',$la_config);
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
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/11/2016 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'','totpro'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'xPos'=>425,
						 'maxWidth'=>400, // Ancho M�ximo de la tabla
						 'xOrientation'=>'left', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>330), // Justificaci�n y ancho de la columna
						 			   'totpro'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
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
	$ls_logusr=$_SESSION["la_logusr"];

		
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_nummov=	$io_fun_sfa->uf_obtenervalor_get("numinginv","");
	$ls_numcontrol=	$io_fun_sfa->uf_obtenervalor_get("numcontrol","");
	$ld_fecmov=	$io_fun_sfa->uf_obtenervalor_get("fecmov","");
	$ls_cedaut=	$io_fun_sfa->uf_obtenervalor_get("cedaut","");
	$ls_nomaut=	$io_fun_sfa->uf_obtenervalor_get("nomaut","");
	$lb_valido=true;

	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data   = $io_report->uf_select_movimiento_inventario($ls_codemp,$ls_nummov,$ld_fecmov,$ls_logusr,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_descripcion="Gener� el Reporte del Movimiento de Inventario";
		$titulo="RELACION DE INGRESO DE PRODUCTOS AL INVENTARIO";
		$lb_valido=$io_fun_sfa->uf_load_seguridad_reporte("SFA","tepuy_sfa_d_productos_inventario.php",$ls_descripcion);
		if($lb_valido)
		// generar reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
 			$io_pdf->ezSetCmMargins(9,7,3.3,3); // Configuraci�n de los margenes en cent�metros   

		    	$io_pdf->ezStartPageNumbers(588,760,8,'','',1); 
			if ($row=$io_sql->fetch_row($rs_data))
			// Datos Movimiento de Inventario
			{
				$ls_numcontrol = $row["numconint"];
				$ls_obsmov = $row["obsmov"];
				$ls_nomusu	  = trim($row["nomusu"])." ".trim($row["apeusu"]);
				$ls_cedaut=number_format($ls_cedaut,0,',','.');
				$aa_data[1]  = array('cedaut'=>$ls_cedaut,'nomaut'=>$ls_nomaut); 
				$ls_fecmov   = $io_funciones->uf_convertirfecmostrar($ls_fecmov);
			uf_print_encabezado_pagina($ls_nummov,$ld_fecmov,$ls_cedaut,$ls_nomaut,$ls_nomusu,$titulo,$aa_data,&$io_pdf);
			   	/////DETALLE  DEL MOVIMIENTO DE INVENTARIO
				
				//$rs_datos = $io_report->uf_select_detalle_factura_imprimir($ls_numfactura,&$lb_valido);
				$rs_datos=$io_report->uf_sfa_select_dt_movimiento_inventario($ls_codemp,$ls_nummov,$lb_valido); // Obtenemos el detalle del reporte
				if($lb_valido)
				{ // Si es valido los movimientos
					$li_totrows = $io_sql->num_rows($rs_datos);
					if ($li_totrows>0)
					// Si encuentra Detalles de Movimiento
					{
						$li_totproa= 0;
						$li_totprob= 0;
						$li_totproc= 0;
						$li_totprod= 0;
						$li_s=0;
						while($row=$io_sql->fetch_row($rs_datos))
						{
							$li_s++;
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
						$la_data1[1]=array('total'=>'<b>Cantidad de Productos Ingresados</b>','totpro'=>$li_totcanpro);
						uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
						$ld_fechaaux=$io_funciones->uf_convertirdatetobd($ls_fecha);
						unset($la_data);
					} // IF Detalles de Movimiento
				}// IF valido los movimientos
			} // IF Datos Movimiento de Inventario
		} // IF generar reporte
	} // Imprimir Reporte
	if($lb_valido) // Si no ocurrio ning�n error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo alg�n error
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
