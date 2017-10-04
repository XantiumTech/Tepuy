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
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,14,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_programatica,$as_denestpro,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(720);
		
		$ls_codestpro = "";
		$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
		if ($li_estmodest==1)
		   {
			 $ls_denrep = "PROYECTO";
			 /*$ls_codestpro1 = substr($as_programatica,0,20);
			 $ls_codestpro2 = substr($as_programatica,20,6);
			 $ls_codestpro3 = substr($as_programatica,26,3);
		     $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;*/
		   }
		elseif($li_estmodest==2)
		   {
			 $ls_denrep     = "PROGRAMATICA";
			 /*$ls_codestpro1 = substr($as_programatica,18,2);
			 $ls_codestpro2 = substr($as_programatica,24,2);
			 $ls_codestpro3 = substr($as_programatica,27,2);
			 $ls_codestpro4 = substr($as_programatica,29,2);
			 $ls_codestpro5 = substr($as_programatica,31,2);
	         $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;*/
		   }
		

		$la_data=array(array('name'=>'<b>'.$ls_denrep.'</b>  '.$as_programatica.''),
		               array('name'=>'<b></b> '.$as_denestpro.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'colGap'=>1, // separacion entre tablas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(664);
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','descripcion'=>'<b>Descripción</b>',
		                     'documento'=>'<b>Documento</b>','monto'=>'<b>Monto</b>'));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','descripcion'=>'','documento'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho  
						 			   'denominacion'=>array('justification'=>'center','width'=>140), // Justificación y ancho 
						 			   'descripcion'=>array('justification'=>'center','width'=>115), // Justificación y ancho  
						 			   'documento'=>array('justification'=>'center','width'=>90), // Justificación y ancho 
									   'monto'=>array('justification'=>'center','width'=>115))); // Justificación y ancho  
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(650);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la 
						 			   'descripcion'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la 
						 			   'documento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'descripcion'=>'<b>Descripción</b>',
						   'documento'=>'<b>Documento</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_programatica($ad_totalprogramatica,$as_denominacion,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalprogramatica // Total Programatica
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing.Yozelin Barragán
		// Fecha Creación : 18/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
		$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
		if ($li_estmodest==1)
		   {
			 $ls_denrep = "PROYECTO";
		   }
		elseif($li_estmodest==2)
		   {
			 $ls_denrep = "PROGRAMATICA";
		   }

		$la_data=array(array('total'=>'<b>SubTotal '.$as_denominacion.'</b>','monto'=>$ad_totalprogramatica));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 	           'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_total_programatica
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total,$as_denominacion,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing.Yozelin Barragán
		// Fecha Creación : 18/02/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total '.$as_denominacion.'</b>','monto'=>$ad_total));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//--------------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		 if($ls_tipoformato==1)
		 {
			require_once("tepuy_spg_reporte_bsf.php");
			$io_report = new tepuy_spg_reporte_bsf();
		 }
		 else
		 {
			require_once("tepuy_spg_reporte.php");
		    $io_report = new tepuy_spg_reporte();
		 }	
		 	
		 require_once("../../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
                      
		 $io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		$ls_codfuefindes = $_GET["txtcodfuefindes"];
		$ls_codfuefinhas = $_GET["txtcodfuefinhas"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
			  }
			}
			else
			{
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
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
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max=="")&&($ls_codestpro4h_max=="")&&
			   ($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
			  {
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
			  }
			}
			else
			{
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
			}
		}	
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
		$ldt_fecini=$li_ano."-"."01-01";
		$ldt_fecini_rep="01/01/".$li_ano;
		$ls_cmbmeshas = "01";
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Listado Apertura Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta;
		$io_function_report->uf_load_seguridad_reporte("SPG","tepuy_spg_r_listado_apertura.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		$ls_titulo=" <b>LISTADO DE APERTURAS</b> ";       
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
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
    $lb_valido=$io_report->uf_spg_reporte_select_apertura($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                      $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
														  $ls_codestpro4h,$ls_codestpro5h,$ldt_fecini,$ldt_fecfin,$ls_codfuefindes,
											              $ls_codfuefinhas,&$rs_data);
	 if($lb_valido===false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    //print "entre en el else de impresion del reporte<br>";
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
		$ld_total=0;
		if ($li_tot > 0) 
		{
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_cab->data["programatica"][$li_i];
			$ls_codestpro1=substr($ls_programatica,0,20);
		    $ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,20,6);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,26,3);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,29,2);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
				  $ls_denestpro4=$ls_denestpro4;
				}
				$ls_codestpro5=substr($ls_programatica,31,2);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
				  $ls_denestpro5=$ls_denestpro5;
				}
			    $ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
			    $ls_programatica=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
			}
			else
			{
			    $ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3);
			    $ls_programatica=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2);
			}
		    $io_cabecera=$io_pdf->openObject();
			uf_print_cabecera($io_cabecera,$ls_programatica,$ls_denestpro,$io_pdf); // Imprimimos la cabecera del registro
            $lb_valido=$io_report->uf_spg_reporte_apertura($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
	                                                       $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
  							                               $ldt_fecini,$ldt_fecfin,$ls_codfuefindes,$ls_codfuefinhas);
            if($lb_valido)
			{
			    $ld_totalprogramatica=0;
				$li_totrow_det=$io_report->dts_reporte->getRowCount("programatica");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					  $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$li_s];
					  $ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$li_s]);
					  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];  
					  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];  
					  $ld_monto=$io_report->dts_reporte->data["monto"][$li_s];  
					
					  $ld_totalprogramatica=$ld_totalprogramatica+$ld_monto;
					  $ld_total=$ld_total+$ld_monto;
					  $ld_monto=number_format($ld_monto,2,",",".");
					  $la_data[$li_s]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'descripcion'=>$ls_descripcion,'documento'=>$ls_documento,'monto'=>$ld_monto);
					  $ld_monto=str_replace('.','',$ld_monto);
					  $ld_monto=str_replace(',','.',$ld_monto);		
				}
									
		        $io_encabezado=$io_pdf->openObject();
                uf_print_cabecera_detalle($io_encabezado,$io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				if($ls_tipoformato==1)//BsF.
				{
					$ld_totalprogramatica=number_format($ld_totalprogramatica,2,",",".");
					uf_print_total_programatica($ld_totalprogramatica,'Bs.',$io_pdf); // Imprimimos el total programatica
				}
				else //Bs.
				{	
				//	$ld_totalprogramatica_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_totalprogramatica, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
					
					$ld_totalprogramatica=number_format($ld_totalprogramatica,2,",",".");
					uf_print_total_programatica($ld_totalprogramatica,'Bs.',$io_pdf); // Imprimimos el total programatica
	
				//	$ld_totalprogramatica_bsf=number_format($ld_totalprogramatica_bsf,2,",",".");
				//	uf_print_total_programatica($ld_totalprogramatica_bsf,'BsF.',$io_pdf); // Imprimimos el total programatica
					
				}
				$io_pdf->stopObject($io_encabezado);
			}
		    $io_pdf->stopObject($io_cabecera);
			if($li_i==$li_tot)
			{
			  
			  	if($ls_tipoformato==1)//BsF.
				{
				    //$ld_total=number_format($ld_total,2,",",".");
			  	    //uf_print_pie_cabecera($ld_total,'BsF.',$io_pdf); // Imprimimos pie de la cabecera
					
				}
				else//Bs.
				{
					 $ld_total_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
					 $ld_total=number_format($ld_total,2,",",".");
			  	 	 uf_print_pie_cabecera($ld_total,'Bs.',$io_pdf); // Imprimimos pie de la cabecera
					 //$ld_total_bsf=number_format($ld_total_bsf,2,",",".");
					 //uf_print_pie_cabecera($ld_total_bsf,'BsF.',$io_pdf); // Imprimimos pie de la cabecera
					
				}	
			 	 
			}
			unset($la_data);
			if($li_i<$li_tot)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
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
		else
		{
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
		 print("</script>");
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);		
?> 