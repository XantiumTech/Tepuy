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
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->line(10,30,1000,30);
		
		//$io_pdf->rectangle(10,460,985,130);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],27,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->line(10,40,578,40);
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->addText(12,580,11,"<b>CONCEJO MUNICIPAL DE OBISPOS</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,770,10,"<b>ALCALDIA DEL MUNICIPIO OBISPOS</b>"); // Agregar Titulo

		$io_pdf->addText(237,716,9,"<b>Dirección de Presupuesto</b>"); // Agregar la Fecha		
		//$io_pdf->addText(65,692,10,"<b>Estado Barinas</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,565,10,"<b></b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el título
		
		//if($as_titulo1!="")

		$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,682,10,$as_titulo1); // Agregar el título
		
// ESTAS LINEAS SON NECESARIAS PARA QUE SE PUEDA MOSTRAR EL PDF //
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');


	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetDy(+13);
		$la_data=array(array('sector'=>'<b>Sector</b>','programa'=>'<b>Programa</b>','actividad'=>'<b>Actividad o Proyecto</b>','denominacion'=>'<b>Denominación</b>','unidad'=>'<b>Unidad Ejecutora</b>'));
		$la_columna=array('sector'=>'','programa'=>'','actividad'=>'','denominacion'=>'','unidad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'cols'=>array('sector'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la
									'programa'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la
									'actividad'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la
						 			   'denominacion'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la
						 			   'unidad'=>array('justification'=>'center','width'=>140))); // Justificación y ancho 

	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_cabecera


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetY(640);
		//$io_pdf->ezSetDy(+13);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('sector'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la
									'programa'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la
									'actividad'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la
						 			   'denominacion'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la
						 			   'unidad'=>array('justification'=>'left','width'=>140))); // Justificación y ancho 
		$la_columnas=array('sector'=>'<b>Sector</b>','programa'=>'<b>Programa</b>','actividad'=>'<b>Actividad</b>','denominacion'=>'<b>Denominación</b>','unidad'=>'<b>Unidad</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(580);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>460), // Justificación y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'pres_anual'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: 
		//    Description: Funcion que inserta un registro en seguridad cuando se imprime el reporte por pantalla.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Distribucion Ingresos Estimados";
	    $ls_variable    = $io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	    $aa_seguridad["ventanas"],$ls_descripcion);
		
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_fecha.php");
		

        
		$io_funciones = new class_funciones();			
		$io_fecha     = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
        global $ld_total_pres_anual_bsf;
		global $la_data_tot_bsf;
		
		 if($ls_tipoformato==1)
		 {
			require_once("tepuy_spg_reporte_comparados07_bsf.php");
			$io_report    = new tepuy_spg_reporte_comparados07_bsf();
		 }
		 else
		 {
			require_once("tepuy_spg_reporte_comparados07.php");
			$io_report    = new tepuy_spg_reporte_comparados07();
		 }	
		 	
		 require_once("../../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
                      
		 $io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$letra=11;

		$cmbnivel=$_GET["cmbnivel"];

		$ls_titulo=" <b> INDICE DE CATEGORIAS PROGRAMATICAS </b>";
		$ls_titulo1="";
		$ls_sector="01";
		if($cmbnivel=="s1")
		{
          		$li_cmbnivel="16";
			$desde=1;
			$hasta=55;
			
		}
          	else
		{
          		$li_cmbnivel=$cmbnivel;
		}
		
		$ls_etiqueta=$_GET["txtetiqueta"];
		
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		
		if($li_cmbnivel==1)
		{
			$ls_titulo1=" <b> DIRECCIÓN SUPERIOR DEL MUNICIPIO </b>";
			$desde=1;
			$hasta=22;
		}
		if($li_cmbnivel==2)
		{
			$ls_titulo1=" <b> SEGURIDAD Y DEFENSA </b>";
			$desde=1;
			$hasta=4;
			$ls_sector="02"; 
		}
		if($li_cmbnivel==6)
		{
			$ls_titulo1=" <b> TURISMO Y RECREACIÓN </b>";
			$desde=1;
			$hasta=2;
			$ls_sector="06"; 
		}
		if($li_cmbnivel==8)
		{
			$ls_titulo1=" <b> EDUCACIÓN </b>";
			$desde=1;
			$hasta=2;
			$ls_sector="08";
		}
		if($li_cmbnivel==9)
		{
			$ls_titulo1=" <b> DEPORTES </b>";
			$desde=1;
			$hasta=2;
			$ls_sector="09";
		}
		if($li_cmbnivel==11)
		{
			$ls_titulo1=" <b> DESARROLLO URBANO, VIVIENDA y SERVICIOS CONEXOS </b>";
			$desde=1;
			$hasta=12;
			$ls_sector="11";
		}
		if($li_cmbnivel==12)
		{
			$ls_titulo1=" <b> SALUD </b>";
			$desde=1;
			$hasta=2;
			$ls_sector="12";
		}
		if($li_cmbnivel==13)
		{
			$ls_titulo1=" <b> DESARROLLO SOCIAL Y PARTICIPACIÓN </b>";
			$desde=1;
			$hasta=3;
			$ls_sector="13";
 
		}
		if($li_cmbnivel==14)
		{
			$ls_titulo1=" <b> SEGURIDAD SOCIAL </b>";
			$desde=1;
			$hasta=4;
			$ls_sector="14";
		}
		if($li_cmbnivel==15)
		{
			$ls_titulo1=" <b> GASTOS NO CLASIFICADOS SECTORIALMENTE </b>";
			$desde=1;
			$hasta=2;
			$ls_sector="15";
		}
print $titulo;
		 
	//--------------------------------------------------------------------------------------------------------------------------------
    
	/*$lb_valido = uf_insert_seguridad();
	if ($lb_valido)
	   {*/
	     //Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )


         $lb_valido=$io_report->uf_spg_reportes_distribucion_categorias($li_cmbnivel,$desde,$hasta);
	     if ($lb_valido==false) // Existe algún error ó no hay registros
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
			  uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la página
			  $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página


			  $ld_total_pres_anual=0;
			  $ld_total_programada_acumulado=0;
			  $ld_total_monto_ejecutado=0;
			  $ld_total_ejecutado_acumulado=0;
			  $ld_total_variacion_absoluta=0;
			  $ld_total_porcentaje_variacion=0;
			  $ld_total_variacion_abs_acum=0;
			  $ld_total_porcentaje_variacion_acum=0;
			  $ld_total_reprog_prox_mes=0;
			  $li_total=$io_report->dts_reporte->getRowCount("spg_ep3");
			
			$posicion=0;
			  for ($z=$desde;$z<=$hasta;$z++)
			      {
	
				    $thisPageNum=$io_pdf->ezPageCount;
					$posicion=$posicion+1;
					
					
				    	$ls_codestpro1=$io_report->dts_reporte->data["codestpro1"][$z];
					$ls_denestpro1=$io_report->dts_reporte->data["denestpro1"][$z];
					$ls_codestpro2=$io_report->dts_reporte->data["codestpro2"][$z];
					$ls_denestpro2=$io_report->dts_reporte->data["denestpro2"][$z];
					$ls_codestpro3=$io_report->dts_reporte->data["codestpro3"][$z];
					$ls_denestpro3=$io_report->dts_reporte->data["denestpro3"][$z];
					$ls_responsable=$io_report->dts_reporte->data["responsable"][$z];
					if(($posicion==1)||($ls_sector!=$ls_codestpro1))
					{
						$ls_sector=$ls_codestpro1;
						if($ls_sector=="01"){$ls_titulo1="<b>DIRECCIÓN SUPERIOR DEL MUNICIPIO </b>";}
						if($ls_sector=="02"){$ls_titulo1="<b>SEGURIDAD Y DEFENSA </b>";}
						if($ls_sector=="06"){$ls_titulo1="<b>TURISMO Y RECREACIÓN </b>";}
						if($ls_sector=="08"){$ls_titulo1="<b>EDUCACIÓN </b>";}
						if($ls_sector=="09"){$ls_titulo1="<b>DEPORTES </b>";}
						if($ls_sector=="11"){$ls_titulo1="<b>DESARROLLO URBANO, VIVIENDA y SERVICIOS CONEXOS </b>";}
						if($ls_sector=="12"){$ls_titulo1=" <b>SALUD </b>";}
						if($ls_sector=="13"){$ls_titulo1="<b>DESARROLLO SOCIAL Y PARTICIPACIÓN </b>";}
						if($ls_sector=="14"){$ls_titulo1="<b>SEGURIDAD SOCIAL </b>";}
						if($ls_sector=="15"){$ls_titulo1="<b>GASTOS NO CLASIFICADOS SECTORIALMENTE </b>";}
						$ls_codestpro1="<b>".$ls_codestpro1."</b>";
						$la_data[$posicion]=array('sector'=>$ls_codestpro1,'programa'=>'','actividad'=>'','denominacion'=>$ls_titulo1,'unidad'=>'');
						$posicion=$posicion+1;
						
					}
					//print $ls_spg_cuenta;
				    	//$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$z];
					if($ls_codestpro3=='00')
					{
						$ls_denestpro3=$ls_denestpro2;
						$ls_codestpro3="";
					}
					//if($ls_codestpro1=="00"){$ls_codestpro1='<b>'.$ls_codestpro1.'</b>';}
					$la_data[$posicion]=array('sector'=>$ls_codestpro1,'programa'=>$ls_codestpro2,'actividad'=>$ls_codestpro3,'denominacion'=>$ls_denestpro3,'unidad'=>$ls_responsable);
			
			 }//for
			
			//uf_print_titulo_reporte($li_ano,$ls_meses,$ls_etiqueta,$io_pdf);
			//uf_print_titulo($ls_etiqueta,$io_pdf);
			uf_print_cabecera($io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			//uf_print_pie_cabecera($la_data_tot,$io_pdf);
			//uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);
			unset($la_data);
			//unset($la_data_tot);
			//unset($la_data_tot_bsf);
			if($z<$hasta)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
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
		}//else
	unset($io_report);
	unset($io_funciones);	
	/*   }
	else
	   {
	   
	   }*/

?> 
