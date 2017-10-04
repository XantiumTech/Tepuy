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
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 07/06/2006 
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

		$io_pdf->addText(237,716,9,"<b>Coordinaci�n de Presupuesto</b>"); // Agregar la Fecha		
		//$io_pdf->addText(65,692,10,"<b>Estado Barinas</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,565,10,"<b></b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el t�tulo

		$li_tm1=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm1=306-($li_tm1/2);
		$io_pdf->addText($tm1,680,11,$as_titulo1); // Agregar el t�tulo
		
		//$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		//$tm=306-($li_tm/2);
		//$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el t�tulo
// ESTAS LINEAS SON NECESARIAS PARA QUE SE PUEDA MOSTRAR EL PDF //
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');


	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_print_cabecera($as_etiqueta,$as_etiqueta1,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetDy(+13);
		
		$li_tm=$io_pdf->getTextWidth(11,$as_etiqueta);
		$tm=290-($li_tm/2);
		$linea=400;
		$io_pdf->addText($tm,$linea,20,$as_etiqueta); // Agregar el t�tulo

		$li_tm1=$io_pdf->getTextWidth(20,$as_etiqueta1);
		$tm1=306-($li_tm1/2);
		$linea1=370;
		$io_pdf->addText($tm1,$linea1,20,$as_etiqueta1); // Agregar el t�tul

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_cabecera
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
		// Fecha Creaci�n: 21/06/2007 
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
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
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
			/*$ls_titulo=" <b>RESUMEN DEL PRESUPUESTO DE GASTOS A NIVEL DE SECTORES Y PROGRAMAS </b>";
			if($cmbnivel=="01"){$ls_titulo1=" <b> Sector: Direccion Superior del Municipio </b>";}
              		if($cmbnivel=="02"){$ls_titulo1=" <b> Sector: Seguridad y Defensa </b>";}
              		if($cmbnivel=="06"){$ls_titulo1=" <b> Sector: Turismo y Recreaci�n </b>";}
              		if($cmbnivel=="08"){$ls_titulo1=" <b> Sector: Educaci�n </b>";}
              		if($cmbnivel=="09"){$ls_titulo1=" <b> Sector: Deportes </b>";}
              		if($cmbnivel=="11"){$ls_titulo1=" <b> Sector: Desarrollo Urbano, Vivienda y Servicios Conexos </b>";}
              		if($cmbnivel=="12"){$ls_titulo1=" <b> Sector: Salud </b>";}
              		if($cmbnivel=="13"){$ls_titulo1=" <b> Sector: Desarrollo Social y Participaci�n </b>";}
              		if($cmbnivel=="14"){$ls_titulo1=" <b> Sector: Seguridad Social </b>";}
              		if($cmbnivel=="15"){$ls_titulo1=" <b> Sector: Gastos no Clasificados Sectorialmente </b>";}*/
		}
		
		
 
         $lb_valido=$io_report->uf_spg_reportes_separador_programas($li_cmbnivel);
	     if ($lb_valido==false) // Existe alg�n error � no hay registros
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
	 		  $io_pdf->ezSetCmMargins(5,3,3,3); // Configuraci�n de los margenes en cent�metros
			  uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la p�gina
			  $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina


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

				    $ls_codestpro1=$io_report->dts_reporte->data["codestpro1"][$z];
					$ls_codestpro1="Sector: ".substr($ls_codestpro1,18,2);	
				    $ls_denestpro2=$io_report->dts_reporte->data["denestpro2"][$z];

					//print $ls_spg_cuenta;
				//print $ls_codestpro1;
				//print $ls_codestpro2;

				    $ls_denominacion=$io_report->dts_reporte->data["denestpro2"][$z];
uf_print_cabecera($ls_codestpro1,$ls_denestpro1,$io_pdf);
				if($z<$hasta)
				{
					 $io_pdf->ezNewPage(); // Insertar una nueva p�gina
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
				}//for			
			unset($io_pdf);
		}//else
	unset($io_report);
	unset($io_funciones);

?> 
