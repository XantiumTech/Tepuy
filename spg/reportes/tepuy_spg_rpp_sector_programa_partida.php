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
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 22-10-2009
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->line(10,30,1000,30);
		
		//$io_pdf->rectangle(10,460,985,130);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],27,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->line(10,40,578,40);
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->addText(12,580,11,"<b>CONCEJO MUNICIPAL DE OBISPOS</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,770,10,"<b>ALCALDIA DEL MUNICIPIO OBISPOS</b>"); // Agregar Titulo

		$io_pdf->addText(237,716,9,"<b>Coordinación de Presupuesto</b>"); // Agregar la Fecha		
		//$io_pdf->addText(65,692,10,"<b>Estado Barinas</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,565,10,"<b></b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,698,10,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,688,10,$as_fecha); // Agregar el título

		$io_pdf->addText(490,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(490,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_programatica,$as_denestpro,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 22-10-2009
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(690);
		$sector=substr($as_programatica,18,2);
		$programa=substr($as_programatica,25,2);
		$actividad=substr($as_programatica,29,2);
		$la_data=array(array('name'=>'<b>Programatica</b> '.'Sector: '.$sector.' Programa: '.$programa),
		               array('name'=>'<b></b> '.$as_denestpro.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 22-10-2009
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        	$io_pdf->ezSetDy(1);
		$io_pdf->ezSetY(660);
		$la_data=array(array('cuenta'=>'<b>Partida</b>','denominacion'=>'<b>Denominación</b>','asignado'=>'<b>Asignado</b>'));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','asignado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'center','width'=>325), // Justificación y ancho de la
						 			   'asignado'=>array('justification'=>'center','width'=>125))); // Justificación y ancho 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
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
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 22-10-2009
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(645);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>325), // Justificación y ancho de la 
						 			   'asignado'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'asignado'=>'<b>Asignado</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalasignado,$ad_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación : 22/10/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>$ad_titulo,'asignado'=>$ad_totalasignado,'disminucion'=>$ad_titulo));
		$la_columna=array('total'=>'','asignado'=>'');//,'disminucion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'colGap'=>1,
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>425), // Justificación y ancho de la columna
						 	           'asignado'=>array('justification'=>'right','width'=>125)));//, // Justificación y ancho de la 
							//           'disminucion'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de la

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		require_once("tepuy_spg_reporte.php");
		$io_report = new tepuy_spg_reporte();
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		
    //--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];


		if($li_estmodest==1)
		{
			$ls_codestpro3_min = "000";
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro3h_max = "000";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min==""))
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
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max==""))
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
	 $ls_desc_event="Solicitud de Reporte Resumen de Creditos Presupuestarios del Programa a Nivel de Partidas";
	 $io_function_report->uf_load_seguridad_reporte("SPG","tepuy_spg_r_sector_programa_partida.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------------------------------------------------------------------------------------------------
		$ls_titulo="<b> Creditos Presupuestarios del Programa a Nivel de Partidas </b> "; 
		$ls_fecha="<b> Ejercicio Económico - Financiero 2010 </b>";      
		$ld_titulo="<b> Total </b>";
		$ld_titulo1="<b> Total Acumulado</b>";
  //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$ls_nivel=1;	

	 $lb_valido=$io_report->uf_spg_reporte_distribucion_sector_programa_partida($ls_codestpro1,$ls_codestpro2,$ls_codestpro1h,$ls_codestpro2h,$ls_nivel);
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
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
		    //$ls_codestpro3=substr($ls_programatica,26,3);
			$ls_codestpro3="00";

			$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];

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
			        $ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2;//." , ".$ls_denestpro3;
			        $ls_programatica_ant=$ls_codestpro1."-".$ls_codestpro2;//."-".$ls_codestpro3;
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
			        $ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2;//." , ".$ls_denestpro3;
			        $ls_programatica_ant=$ls_codestpro1."-".$ls_codestpro2;//."-".$ls_codestpro3;
				}
			}
			
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ls_denestpro5=$io_report->dts_reporte->data["denestpro5"][$z];
			$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			//$ld_disponible=$io_report->dts_reporte->data["disponible"][$z];
			$ls_status=$io_report->dts_reporte->data["status"][$z];
	
			$ld_totalasignado=$ld_totalasignado+$ld_asignado;
			$ld_totalgeneral=$ld_totalgeneral+$ld_asignado;

			if (!empty($ls_programatica))
		    {
				$ld_asignado=number_format($ld_asignado,2,",",".");
				//$ld_disponible=number_format($ld_disponible,2,",",".");
			   
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
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
			   
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
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
			   
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
								   'asignado'=>$ld_asignado);//,'disponibilidad'=>$ld_disponible);
				
		        
				$io_cabecera=$io_pdf->openObject();
			    uf_print_cabecera($io_cabecera,$ls_programatica_ant,$ls_denestpro_ant,$io_pdf);
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
				//$ld_totaldis=$ld_totaldisponible;
				$ld_totalasignado=0;
				$ld_totaldisponible=0;
				$io_pdf->stopObject($io_cabecera);
				$io_pdf->stopObject($io_encabezado);
			    if ((!empty($ls_programatica_next))&&($z<$li_tot))
				{
				 $io_pdf->ezNewPage(); // Insertar una nueva página
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
