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
		// Fecha Creación: 14/11/2009 
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

		$io_pdf->addText(237,716,9,"<b>Coordinación de Presupuesto</b>"); // Agregar la Fecha		
		//$io_pdf->addText(65,692,10,"<b>Estado Barinas</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,565,10,"<b></b>"); // Agregar la Fecha
		
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
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/11/2009 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetY(400);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 16, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificación y ancho de
		$la_columnas=array('titulo'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: 
		//    Description: Funcion que inserta un registro en seguridad cuando se imprime el reporte por pantalla.
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 14/11/2009 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Separador de Programas del Sector";
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
		$ls_titulo="";
		$ls_titulo1="";
		$desde=1;
		
		if($cmbnivel=="s1")
		{
          		$li_cmbnivel="16";
			$ls_titulo=" <b></b>";
			$ls_titulo1=" <b></b>";
			$hasta=23; 
		}
          	else
		{
          		$li_cmbnivel=$cmbnivel;
			$ls_titulo=" <b></b>";
			if($cmbnivel=="01"){$hasta=9;}
              		if($cmbnivel=="02"){$hasta=2;}
              		if($cmbnivel=="06"){$hasta=1;}
              		if($cmbnivel=="08"){$hasta=1;}
              		if($cmbnivel=="09"){$hasta=1;}
              		if($cmbnivel=="11"){$hasta=4;}
              		if($cmbnivel=="12"){$hasta=1;}
              		if($cmbnivel=="13"){$hasta=1;}
              		if($cmbnivel=="14"){$hasta=2;}
              		if($cmbnivel=="15"){$hasta=1;}
		}
		
		
 
         $lb_valido=$io_report->uf_spg_reportes_separador_programas($li_cmbnivel,$desde,$hasta);
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
			  //$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página


			  $li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
			//print "desde: ".$desde." Hasta: ".$hasta;
			$ubica=0;
			  for ($z=$desde;$z<=$hasta;$z++)
			  {
				//print "Z ".$z. "Hasta ".$hasta;
				$thisPageNum=$io_pdf->ezPageCount;

				$ls_codestpro1=$io_report->dts_reporte->data["codestpro1"][$z];
				$ls_denestpro1=$io_report->dts_reporte->data["denestpro1"][$z];
				$ls_denestpro2=$io_report->dts_reporte->data["denestpro2"][$z];
				//$ls_codestpro1="";
				$ls_codestpro1="Sector: ".substr($ls_codestpro1,18,2)." ".$ls_denestpro1;
				/*print $ls_spg_cuenta;
				print $ls_codestpro1;
				print $ls_denestpro2;	*/
				for ($l=1;$l<=24;$l++)
				{
					$ubica=$ubica+1;
					if($l==1)
					{
					$la_data[$ubica]=array('titulo'=>$ls_codestpro1);
					$ubica=$ubica+1;
					$la_data[$ubica]=array('titulo'=>$ls_denestpro2);
					$ubica=$ubica+1;
					}
					else
					{
					if($z<$hasta){$la_data[$ubica]=array('titulo'=>' ');}
					}
				}
				/*uf_print_cabecera($ls_codestpro1,"",$io_pdf);

				if($z<$hasta)
				{
					 $io_pdf->ezNewPage(); // Insertar una nueva página
				} 
				$io_pdf->ezStopPageNumbers(1,1);*/
				
				//unset($io_pdf);
			}//for
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			if($z<$hasta)
			{
				 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
			$io_pdf->ezStopPageNumbers(1,1);
			unset($la_data);
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

?> 
