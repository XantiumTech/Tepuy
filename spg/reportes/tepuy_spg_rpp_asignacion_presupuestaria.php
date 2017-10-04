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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 22-10-2009
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*	$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->line(10,30,1000,30);
		
		//$io_pdf->rectangle(10,460,985,130);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],27,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

		$io_pdf->addText(237,716,9,"<b>Direcci�n de Presupuesto</b>"); // Agregar la Fecha		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,698,10,$as_titulo); // Agregar el t�tulo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		//$io_pdf->addText($tm,688,10,$as_fecha); // Agregar el t�tulo

		$io_pdf->addText(490,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(490,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->addText(22,584,7,"PARTIDA",270); // Columna, Altura,Tama�o, Texto, Giro
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');*/
		$io_encabezado=$io_pdf->openObject();
		$ls_ciudad = $_SESSION["la_empresa"]["ciuemp"];
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,690,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(110,740,10,"OFICINA CENTRAL DE PRESUPUESTO");
		$io_pdf->addText(110,728,10,"ENTIDAD FEDERAL: <b> BARINAS </b>");
		$io_pdf->addText(110,716,10,"MUNICIPIO: <b>".$ls_ciudad."</b>");
		$io_pdf->line(20,685,580,685);
		$io_pdf->line(40,654,40,590); // ANTES 44,684
		$io_pdf->line(59,654,59,590);
		$io_pdf->line(77,654,77,590);
		$io_pdf->line(95,654,95,590);
		$io_pdf->line(125,654,125,590);
		$io_pdf->line(479,654,479,590);
		//$io_pdf->line(279,380,279,460);
		//$io_pdf->line(349,380,349,460);
		//$io_pdf->line(419,380,419,450);
		//$io_pdf->line(489,380,489,430);
		//$io_pdf->line(559,380,559,430);
		//$io_pdf->line(629,380,629,450);
		//$io_pdf->line(699,380,699,430);
		//$io_pdf->line(769,380,769,430);
		//$io_pdf->line(839,380,839,430);
		//$io_pdf->line(919,380,919,460);
		//$io_pdf->line(349,450,919,450);
		//$io_pdf->line(419,430,919,430);
		$io_pdf->addText(33,594,7,"PARTIDA",270); // Columna, Altura,Tama�o, Texto, Giro
		$io_pdf->addText(52,594,7,"GEN�RICA",270);
		$io_pdf->addText(70,594,7,"ESPECIFICA",270);
		$io_pdf->addText(88,594,7,"SUB-ESPEC�FICA",270);
		$io_pdf->addText(110,594,7,"AUXILIAR",270);
		$io_pdf->addText(270,605,9,"DENOMINACION");
		$io_pdf->addText(498,610,9,"PRESUPUESTO");
		$io_pdf->addText(498,600,9,"    ASIGNADO");
		$io_pdf->rectangle(19,590,560,170);
		$io_pdf->rectangle(19,654,560,106);
		//$io_pdf->rectangle(60,684,560,76);
		
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,694,13,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_fecha);

		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,686,10,$as_fecha); // Agregar el t�tul
		
		$li_tm=$io_pdf->getTextWidth(10,'<b>'.$as_moneda.'</b>');
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,480,10,'<b>'.$as_moneda.'</b>'); // Agregar el t�tulo

		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_programatica,$as_denestpro1,$as_denestpro2,$as_denestpro3,$as_proyecto,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 22-10-2009
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(700);
		$sector=substr($as_programatica,18,2);
		$programa=substr($as_programatica,25,2);
		$actividad=substr($as_programatica,29,2);
		$io_pdf->addText(49,678,9,"Sector: "."<b>".$sector."</b> ".$as_denestpro1);
		$io_pdf->addText(35,668,9,"Programa: "."<b>".$programa."</b> ".$as_denestpro2);
		if($as_proyecto=='A')
		{
			$io_pdf->addText(38,658,9,"Actividad: "."<b>".$actividad."</b> ".$as_denestpro3);
		}
		else
		{
			$io_pdf->addText(38,658,9,"Proyecto: "."<b>".$actividad."</b> ".$as_denestpro3);
		}

		$la_data=array(array('name'=>'<b>Programatica</b> '.'Sector: '.$sector.' Programa: '.$programa.' Actividad: '.$actividad.''),
		               array('name'=>'<b></b> '.$as_denestpro.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho M�ximo de la tabla
		//$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 22-10-2009
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        	$io_pdf->ezSetDy(1);
		$io_pdf->ezSetY(500);
		//$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominaci�n</b>','asignado'=>'<b>Asignado</b>'));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','asignado'=>'');


		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la 
						 			   'denominacion'=>array('justification'=>'center','width'=>325), // Justificaci�n y ancho de la
						 			   'asignado'=>array('justification'=>'center','width'=>125))); // Justificaci�n y ancho 
		//$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezTable($la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------------
	// FORMATO ANTERIOR
/*	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 22-10-2009
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(592);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho M�ximo de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>325), // Justificaci�n y ancho de la 
						 			   'asignado'=>array('justification'=>'right','width'=>125))); // Justificaci�n y ancho de
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominaci�n</b>',
						   'asignado'=>'<b>Asignado</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle*/
	//--------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------
	// FORMATO NUEVO
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n: 22-10-2009
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(592);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						// 'yPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('par'=>array('justification'=>'left','width'=>22), // Justificaci�n y ancho de la columna
									'gen'=>array('justification'=>'left','width'=>18), // Justificaci�n y ancho de la columna
									'esp'=>array('justification'=>'left','width'=>18), // Justificaci�n y ancho de la columna
								'subesp'=>array('justification'=>'left','width'=>18), // Justificaci�n y ancho de la columna
									'aux'=>array('justification'=>'left','width'=>30), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>354), // Justificaci�n y ancho de la 
						 			   'asignado'=>array('justification'=>'right','width'=>99))); // Justificaci�n y ancho de
		$la_columnas=array('par'=>'<b>Par</b>','gen'=>'<b>Gen</b>','esp'=>'<b>Esp</b>','subesp'=>'<b>Sub</b>','aux'=>'<b>Aux</b>',
						   'denominacion'=>'<b>Denominaci�n</b>',
						   'asignado'=>'<b>Asignado</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalasignado,$ad_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creaci�n : 22/10/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>$ad_titulo,'asignado'=>$ad_totalasignado,'disminucion'=>$ad_titulo));
		$la_columna=array('total'=>'','asignado'=>'');//,'disminucion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>580, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'colGap'=>1,
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>460), // Justificaci�n y ancho de la columna
						 	           'asignado'=>array('justification'=>'right','width'=>99)));//, // Justificaci�n y ancho de la 
							//           'disminucion'=>array('justification'=>'right','width'=>125))); // Justificaci�n y ancho de la

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>550, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("tepuy_spg_reporte.php");
		$io_report = new tepuy_spg_reporte();
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		
    //--------------------------------------------------  Par�metros para Filtar el Reporte  --------------------------------------
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];

		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];

		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min=="")&&($ls_codestpro4_min=="")&&
			   ($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max=="")&&($ls_codestpro4h_max=="")&&
			   ($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}	
		
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
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
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
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
	   $fechas=$_GET["txtfechas"];
	   if (!empty($fechas))
	   {
			$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
	   }	else {  $ldt_fechas=""; } 
	   
       $li_ckbhasfec=$_GET["ckbhasfec"];
       $li_ckbctasinmov=$_GET["ckbctasinmov"];
	   
	   if($li_ckbhasfec==1)
	   {
		  $ldt_ano=substr($ldt_fechas,0,4);
		  $ldt_fecdes=$ldt_ano."-01"."-01";
		  
	   }
	   else
	   {
		  $ldt_fecdes="00-00-0000";		$io_pdf->line(10,680,70,40);
	   }
	   $ls_fecha_titulo=$io_funciones->uf_convertirfecmostrar($ldt_fechas);
	   $ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
	   $ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
	   $ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
	   $ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
	   $ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
	
	   $ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
	   $ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
	   $ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
	   $ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
	   $ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
	 /////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
	 $ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
	 $ls_desc_event="Solicitud de Reporte disponibilidad Presupuestaria Desde la Cuenta ".$ls_cuentades." hasta ".$ls_cuentahas." ,  Desde la Programatica  ".$ls_programatica_desde." hasta ".$ls_programatica_hasta;
	 $io_function_report->uf_load_seguridad_reporte("SPG","tepuy_spg_r_disponibilidad.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //----------------------------------------------------  Par�metros del encabezado  ----------------------------------------------------------------------------------------------------------------------------------------
		//$ls_titulo="<b>  DISTRIBUCION PRESUPUESTARIA </b> ";
		$ls_titulo="<b>  CR�DITOS PRESUPUESTARIOS  </b> ";
		$ls_fecha1=$_SESSION["la_empresa"]["periodo"];
		$ls_fecha1=substr($ls_fecha1,0,4);
		$ls_fecha="<b> Ejercicio Econ�mico - Financiero ".$ls_fecha1." </b>";      
		$ld_titulo="<b> Total </b>";
		$ld_titulo1="<b> Total Acumulado</b>";
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=$io_report->uf_spg_reporte_disponibilidad_cuenta($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                             $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																 $ls_codestpro4h,$ls_codestpro5h,$ldt_fecdes,$ldt_fechas,
								                                 $ls_cuentades,$ls_cuentahas,$li_ckbctasinmov,$li_ckbhasfec);
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(7,3,3,3); // La modificacion a 7 antes 5. me resolvio el montaje de datos,margen inferiorConfiguraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
		$io_report->dts_reporte->group_noorder("programatica");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_totalasignado=0;
		
		$ld_totaldisponible=0;
		$ld_totalgeneral=0;

		for($z=1;$z<=$li_tot;$z++)
		{
		    $li_tmp=($z+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_reporte->data["programatica"][$z];
		    $ls_codestpro1=substr($ls_programatica,0,20);
		    $ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
		    if($lb_valido)
		    {
			  $ls_denestpro1=trim($ls_denestpro1);
		    }
		    $ls_codestpro2=substr($ls_programatica,20,6);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
			  $ls_denestpro2=trim($ls_denestpro2);
		    }
		    $ls_codestpro3=substr($ls_programatica,26,3);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_proyecto);
			  $ls_denestpro3=trim($ls_denestpro3);
			  $ls_proyecto=trim($ls_proyecto);
		    }
			$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
			// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
				$ls_spg_anterior=$ls_spg_cuenta;
				if(substr($ls_spg_anterior,9,4)=="0000")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,9);
				}
				if(substr($ls_spg_cuenta,7,2)=="00")
				{
				$ls_spg_anterior=$ls_spg_cuenta;
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,7);
				}
				if(substr($ls_spg_cuenta,5,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,5);
				}
				if(substr($ls_spg_cuenta,3,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,3);
				}
			// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //
		// PERMITE AGREGARLE PUNTOS A LA CUENTA PRESUPUESTARIA //
				$hasta=strlen($ls_spg_cuenta);
				// NUEVO FORMATO  
				$ls_par=substr($ls_spg_cuenta,0,3);
				$ls_gen=substr($ls_spg_cuenta,3,2);if($ls_gen=="00"){$ls_gen='';}
				$ls_esp=substr($ls_spg_cuenta,5,2);if($ls_esp=="00"){$ls_esp='';}
				$ls_subesp=substr($ls_spg_cuenta,7,2);if($ls_subesp=="00"){$ls_subesp='';}
				$ls_aux=substr($ls_spg_cuenta,9,4); if($ls_aux=="0000"){$ls_aux='';}

				//$ls_spg_cuenta=$ls_spg_cuenta;
				if($hasta==13)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2).".".substr($ls_spg_cuenta,7,2).".".substr($ls_spg_cuenta,9,4);
				}
				if($hasta==9)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2).".".substr($ls_spg_cuenta,7,2);
				}
				if($hasta==7)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2);
				}
				if($hasta==5)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2);
				}
				if($hasta==3)
				{
					$ls_spg_cuenta1="<b>".substr($ls_spg_cuenta,0,3)."</b>";
					$ls_denominacion="<b>".$ls_denominacion."</b>";
					//$ld_asignado="<b>".$ld_asignado."</b>";
					//$ld_disponible="<b>".$ld_disponible."</b>";
				}
				$ls_spg_cuenta=$ls_spg_cuenta1;
		// AGREGA . A LA CTA. PRESUPUESTARIA//
		    if ($z<$li_tot)
		    {
				$ls_programatica_next=$io_report->dts_reporte->data["programatica"][$li_tmp]; 
		    }
		    elseif($z=$li_tot)
		    {
				$ls_programatica_next='no_next';
		    }
			//print "PROGRAM --->".$ls_programatica."<br>";
			//print " PROGRAM NEXT-->".$ls_programatica_next."<br>";
			if(empty($ls_programatica_next)&&(!empty($ls_programatica)))
			{ //print "entrooooo if"."<br>";
			    $ls_programatica_ant=$io_report->dts_reporte->data["programatica"][$z];
				if($li_estmodest==2)
				{
					$ls_codestpro4=substr($ls_programatica,29,2);
					if($lb_valido)
					{
					  $ls_denestpro4="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
					  $ls_denestpro4=trim($ls_denestpro4);
					}
					$ls_codestpro5=substr($ls_programatica,31,2);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
					  $ls_denestpro5=trim($ls_denestpro5);
					}
					$ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
			        $ls_programatica_ant=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
				}
				else
				{
			        $ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
				$ls_denestpro1_ant=$ls_denestpro1;
				$ls_denestpro2_ant=$ls_denestpro2;
				$ls_denestpro3_ant=$ls_denestpro3;
			        $ls_programatica_ant=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
				}
			}
			elseif(!empty($ls_programatica))
			{//print "entrooooo else"."<br>";
			    $ls_programatica_ant=$io_report->dts_reporte->data["programatica"][$z];
				if($li_estmodest==2)
				{
					$ls_codestpro4=substr($ls_programatica,29,2);
					if($lb_valido)
					{
					  $ls_denestpro4="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
					  $ls_denestpro4=trim($ls_denestpro4);
					}
					$ls_codestpro5=substr($ls_programatica,31,2);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
					  $ls_denestpro5=trim($ls_denestpro5);
					}
					$ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
			        $ls_programatica_ant=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
				}
				else
				{
			        $ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
			        $ls_programatica_ant=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
				}
			}
			
			/*if($li_tot==1)
			{
			    $ls_programatica_ant=$io_report->dts_reporte->data["programatica"][$z];
				if($li_estmodest==2)
				{
					$ls_codestpro4=substr($ls_programatica,29,2);
					if($lb_valido)
					{
					  $ls_denestpro4="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
					  $ls_denestpro4=trim($ls_denestpro4);
					}
					$ls_codestpro5=substr($ls_programatica,31,2);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
					  $ls_denestpro5=trim($ls_denestpro5);
					}
					$ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
			        $ls_programatica_ant=substr($ls_codestpro1,-2).substr($ls_codestpro2,-2).substr($ls_codestpro3,-2).substr($ls_codestpro4,-2).substr($ls_codestpro5,-2);
				}
				else
				{
			        $ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
			        $ls_programatica_ant=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
				}
			}*/
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ls_denestpro5=$io_report->dts_reporte->data["denestpro5"][$z];
			$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			//$ld_disponible=$io_report->dts_reporte->data["disponible"][$z];
			$ls_status=$io_report->dts_reporte->data["status"][$z];
	
			if($ls_status=="C")
			{
				$ld_totalasignado=$ld_totalasignado+$ld_asignado;
				$ld_totalgeneral=$ld_totalgeneral+$ld_asignado;
				//$ld_totaldisponible=$ld_totaldisponible+$ld_disponible;
			}
			if (!empty($ls_programatica))
		    {
				$ld_asignado=number_format($ld_asignado,2,",",".");
				//$ld_disponible=number_format($ld_disponible,2,",",".");
			   
			//	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
			//					   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
			// NUEVO FORMATO  
				$la_data[$z]=array('par'=>$ls_par,'gen'=>$ls_gen,'esp'=>$ls_esp,'subesp'=>$ls_subesp,'aux'=>$ls_aux,'denominacion'=>$ls_denominacion,
								   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
			   
				$ld_asignado=str_replace('.','',$ld_asignado);
				$ld_asignado=str_replace(',','.',$ld_asignado);		
				//$ld_disponible=str_replace('.','',$ld_disponible);
				//$ld_disponible=str_replace(',','.',$ld_disponible);		
			}
			else
			{
				$ld_asignado=number_format($ld_asignado,2,",",".");
				//$ld_disponible=number_format($ld_disponible,2,",",".");
			   
			//	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
			//					   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
			// NUEVO FORMATO  
				$la_data[$z]=array('par'=>$ls_par,'gen'=>$ls_gen,'esp'=>$ls_esp,'subesp'=>$ls_subesp,'aux'=>$ls_aux,'denominacion'=>$ls_denominacion,
								   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
			   
				$ld_asignado=str_replace('.','',$ld_asignado);
				$ld_asignado=str_replace(',','.',$ld_asignado);		
				//$ld_disponible=str_replace('.','',$ld_disponible);
				//$ld_disponible=str_replace(',','.',$ld_disponible);		
			}
			if (!empty($ls_programatica_next))
			{
				$ld_asignado=number_format($ld_asignado,2,",",".");
				//$ld_disponible=number_format($ld_disponible,2,",",".");
			 // FORMATO ANTERIOR  
			//	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
			//					   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
			// NUEVO FORMATO  
				$la_data[$z]=array('par'=>$ls_par,'gen'=>$ls_gen,'esp'=>$ls_esp,'subesp'=>$ls_subesp,'aux'=>$ls_aux,'denominacion'=>$ls_denominacion,
								   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);

		        
				$io_cabecera=$io_pdf->openObject();
			    uf_print_cabecera($io_cabecera,$ls_programatica_ant,$ls_denestpro1_ant,$ls_denestpro2_ant,$ls_denestpro3_ant,$ls_proyecto,$io_pdf);
				$io_encabezado=$io_pdf->openObject();
				uf_print_cabecera_detalle($io_encabezado,$io_pdf);
 				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$ld_totalasignado=number_format($ld_totalasignado,2,",",".");
				$ld_totalgeneral=number_format($ld_totalgeneral,2,",",".");
				//$ld_totaldisponible=number_format($ld_totaldisponible,2,",",".");
				uf_print_pie_cabecera($ld_totalasignado,$ld_titulo,$io_pdf);
				uf_print_pie_cabecera($ld_totalgeneral,$ld_titulo1,$io_pdf);				
				$ld_totalasi=$ld_totalasignado;
				$ld_totalgeneral=str_replace('.','',$ld_totalgeneral);
				$ld_totalgeneral=str_replace(',','.',$ld_totalgeneral);
			$ld_totalasi=str_replace('.','',$ld_totalasi);
			$ld_totalasi=str_replace(',','.',$ld_totalasi);
				//$ld_totaldis=$ld_totaldisponible;
				$ld_totalasignado=0;
				$ld_totaldisponible=0;
				$io_pdf->stopObject($io_cabecera);
				$io_pdf->stopObject($io_encabezado);
			    if ((!empty($ls_programatica_next))&&($z<$li_tot))
				{
				 $io_pdf->ezNewPage(); // Insertar una nueva p�gina
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
