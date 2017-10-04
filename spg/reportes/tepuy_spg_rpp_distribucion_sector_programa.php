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

		//$io_pdf->addText(237,716,9,"<b>Dirección de Presupuesto</b>"); // Agregar Oficina	
		$ls_direccion=$_SESSION["la_empresa"]["cargo_presupuesto"];
		$ls_direccion=substr($ls_direccion,0,5)."CION ".substr($ls_direccion,9,strlen($ls_direccion));
		$io_pdf->addText(217,716,9,"<b>".$ls_direccion."</b>"); // Agregar Oficina
		//$io_pdf->addText(65,692,10,"<b>Estado Barinas</b>"); // Agregar Estado
		//$io_pdf->addText(15,565,10,"<b></b>"); // 
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el título

		$li_tm1=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm1=306-($li_tm1/2);
		$io_pdf->addText($tm1,680,11,$as_titulo1); // Agregar el título
		
		//$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		//$tm=306-($li_tm/2);
		//$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el título
// ESTAS LINEAS SON NECESARIAS PARA QUE SE PUEDA MOSTRAR EL PDF //
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');


	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_etiqueta,&$io_pdf)
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
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('sector'=>'<b>Sector</b>','programa'=>'<b>Programa</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>Asignación</b>'));
		$la_columna=array('sector'=>'','programa'=>'','denominacion'=>'','pres_anual'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'cols'=>array('sector'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la
						 			   'programa'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'center','width'=>305), // Justificación y ancho de la
						 			   'pres_anual'=>array('justification'=>'center','width'=>125))); // Justificación y ancho 

	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

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
		
		$io_pdf->ezSetY(648);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('sector'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'programa'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la
						 			   'denominacion'=>array('justification'=>'left','width'=>305), // Justificación y ancho de la 
						 			   'pres_anual'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de
		$la_columnas=array('sector'=>'<b>Sector</b>','programa'=>'<b>Programa</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'pres_anual'=>'<b>Asignado</b>');
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
						 'cols'=>array('total'=>array('justification'=>'right','width'=>425), // Justificación y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de la columna
		
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
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Distribucion de Presupuesto a nivel de Sectores y Programas";
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
		
		if($cmbnivel=="s1")
		{
          		$li_cmbnivel="16";
			$ls_titulo=" <b>RESUMEN DEL PRESUPUESTO DE GASTOS A NIVEL DE SECTORES Y PROGRAMAS </b>";
			$ls_titulo1=" <b></b>"; 
		}
          	else
		{
          		$li_cmbnivel=$cmbnivel;
			$ls_titulo=" <b>RESUMEN DEL PRESUPUESTO DE GASTOS A NIVEL DE SECTORES Y PROGRAMAS </b>";
			if($cmbnivel=="01"){$ls_titulo1=" <b> Sector: Direccion Superior del Municipio </b>";}
              		if($cmbnivel=="02"){$ls_titulo1=" <b> Sector: Seguridad y Defensa </b>";}
              		if($cmbnivel=="06"){$ls_titulo1=" <b> Sector: Turismo y Recreación </b>";}
              		if($cmbnivel=="08"){$ls_titulo1=" <b> Sector: Educación </b>";}
              		if($cmbnivel=="09"){$ls_titulo1=" <b> Sector: Deportes </b>";}
              		if($cmbnivel=="11"){$ls_titulo1=" <b> Sector: Desarrollo Urbano, Vivienda y Servicios Conexos </b>";}
              		if($cmbnivel=="12"){$ls_titulo1=" <b> Sector: Salud </b>";}
              		if($cmbnivel=="13"){$ls_titulo1=" <b> Sector: Desarrollo Social y Participación </b>";}
              		if($cmbnivel=="14"){$ls_titulo1=" <b> Sector: Seguridad Social </b>";}
              		if($cmbnivel=="15"){$ls_titulo1=" <b> Sector: Gastos no Clasificados Sectorialmente </b>";}
		}
		
		$ls_etiqueta=$_GET["txtetiqueta"];
		if($ls_etiqueta=="Mensual")
		{
			$ls_combo=$_GET["combo"];
			$ls_combomes=$_GET["combomes"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combomes,0,2); 
			$ls_cant_mes=1;
            $ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			$ls_combo=$ls_combo.$ls_combomes;
		}
		else
		{
			$ls_combo=$_GET["combo"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combo,2,2); 
			if($ls_etiqueta=="Bi-Mensual")
			{
				$ls_cant_mes=2;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Trimestral")
			{
				$ls_cant_mes=3;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Semestral")
			{
				$ls_cant_mes=6;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
		}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		//$ls_titulo=" <b>RESUMEN DEL PRESUPUESTO DE GASTOS A NIVEL DE SECTORES Y PROGRAMAS </b>".$li_cmbnivel; 
		      
	//--------------------------------------------------------------------------------------------------------------------------------
    
	/*$lb_valido = uf_insert_seguridad();
	if ($lb_valido)
	   {*/
	     //Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )


         $lb_valido=$io_report->uf_spg_reportes_distribucion_sector_programa($ls_combo,$li_cmbnivel);
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
			  $li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
			  for ($z=1;$z<=$li_total;$z++)
			      {
	
				    $thisPageNum=$io_pdf->ezPageCount;

				    $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
				    $ls_codestpro1=$io_report->dts_reporte->data["codestpro1"][$z];
				    $ls_codestpro2=$io_report->dts_reporte->data["codestpro2"][$z];

					//print $ls_spg_cuenta;
				//print $ls_codestpro1;
				//print $ls_codestpro2;

				    $ls_denominacion=$io_report->dts_reporte->data["denestpro2"][$z];
					$li_nivel=$io_report->dts_reporte->data["nivel"][$z];
					$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
					$ld_pres_anual=$ld_asignado;
					
					$ld_total_pres_anual=$ld_total_pres_anual+$ld_asignado;
						
					$ld_pres_anual=number_format($ld_pres_anual,2,",",".");
					
					$la_data[$z]=array('sector'=>$ls_codestpro1,'programa'=>$ls_codestpro2,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual);
										  
					$ld_pres_anual=str_replace('.','',$ld_pres_anual);
					
					if($z==$li_total)
					{
						 if($ls_tipoformato==1)
						 {

							 $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");

						 	 $la_data_tot[$z]=array('total'=>'<b>TOTAL BsF.</b>','pres_anual'=>$ld_total_pres_anual);
						}
						else
						{
							 $ld_total_pres_anual_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_pres_anual , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
	
			 				
							 $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
						 
							 $la_data_tot[$z]=array('total'=>'<b>TOTAL Bs.</b>','pres_anual'=>'<b>'.$ld_total_pres_anual.'</b>');
							
							
							
							 $ld_total_pres_anual_bsf=number_format($ld_total_pres_anual_bsf,2,",",".");

							 					
    						 $la_data_tot_bsf[$z]=array('total'=>'<b>TOTAL BsF.</b>','pres_anual'=>$ld_total_pres_anual_bsf);
						}
			        	}//if
			
			 }//for
			
			//uf_print_titulo_reporte($li_ano,$ls_meses,$ls_etiqueta,$io_pdf);
			//uf_print_titulo($ls_etiqueta,$io_pdf);
			uf_print_cabecera($ls_etiqueta,$io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			uf_print_pie_cabecera($la_data_tot,$io_pdf);
			//uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);
			unset($la_data);
			unset($la_data_tot);
			unset($la_data_tot_bsf);
			if($z<$li_total)
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
