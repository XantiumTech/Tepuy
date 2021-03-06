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
		// Fecha Creaci�n: 14/11/2009 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();

		$io_pdf->addText(510,762,6,"Direcci�n de Administraci�n"); // Direccion		
		$io_pdf->rectangle(20,640,570,120); //Margen Izq, Arriba, Ancho, Alto
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],27,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,695,52,60); // Agregar Logo
		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo_barinas_2015.jpg',535,692,52,60); // Agregar Logo
		//$io_pdf->line(10,40,578,100);
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$ls_entidad= strtoupper($_SESSION["la_empresa"]["estemp"]);
		$ls_ciudad= strtoupper($_SESSION["la_empresa"]["ciuemp"]);
		$ls_periodo= substr($_SESSION["la_empresa"]["periodo"],0,4);
		$io_pdf->addText(25,685,8,"[1] ENTIDAD: ".$ls_entidad); // ENTIDAD
		$io_pdf->addText(25,675,8,"MUNICIPIO: ".$ls_ciudad); // CIUDAD
		$io_pdf->addText(25,658,8,"[2] PRESUPUESTO: ".'<b>'.$ls_periodo.'</b>'); // PRESUPUESTO PERIODO		

		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,655,11,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,643,8,$as_titulo1); // Agregar el t�tulo
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		//$io_pdf->ezSety(500);


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
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/11/2009
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		//$io_pdf->saveState();
		//$io_pdf->ezSetY(600);
		$io_pdf->rectangle(20,580,570,60); //Margen Izq, Arriba, Ancho, Alto
		$io_pdf->line(19,615,180,615);	//Horizonta	
		$io_pdf->line(50,580,50,615); // 1era Vertical
		$io_pdf->line(90,580,90,615); // 2da Vertical
		$io_pdf->line(135,580,135,615); // 3era Vertical
		//$io_pdf->line(290,580,290,620);		
		$io_pdf->line(180,580,180,640);		

		//$io_pdf->line(400,580,400,620);	
		$io_pdf->line(500,580,500,640);
		$io_pdf->addText(80,628,8,"C O D I G O"); // CODIGO	
		$io_pdf->addText(100,618,8,"[3]"); //
		$io_pdf->addText(60,602,8,"SUB"); // SUB RAMO
		$io_pdf->addText(22,594,8,"RAMO"); // RAMO
		$io_pdf->addText(56,590,8,"RAMO"); // SUB RAMO
		$io_pdf->addText(104,594,8,"ESP."); // ESP.
		$io_pdf->addText(148,602,8,"SUB"); // SUB ESP
		$io_pdf->addText(148,590,8,"ESP."); // SUB ESP
		$io_pdf->addText(302,614,9,"D E N O M I N A C I O N"); // DENOMINACION
		$io_pdf->addText(348,602,8,"[4]"); //
		$io_pdf->addText(524,614,9,"M O N T O"); // MONTO
		$io_pdf->addText(544,602,8,"[5]"); //
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	//$io_pdf->ezSety(400);

	}// end function uf_print_cabecera


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/11/2009 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        	$io_pdf->ezSetDy(1);
		$io_pdf->ezSetY(580);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xPos'=>306, // Orientaci�n de la tabla
						 'cols'=>array('partida'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la
									'generica'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la
									'especifica'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la
									'sub-especifica'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la
						 			'denominacion'=>array('justification'=>'left','width'=>320), // Justificaci�n y ancho de la
						 			'pres_anual'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho 
		$la_columnas=array('partida'=>'<b>Partida</b>','generica'=>'<b>Generica</b>','especifica'=>'<b>Especifica</b>','sub-especifica'=>'<b>Sub-Especifica</b>','denominacion'=>'<b>Denominaci�n</b>','pres_anual'=>'<b>Estimado</b>');
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
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xPos'=>305,
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>480), // Justificaci�n y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'pres_anual'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);

		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xPos'=>305,
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('forma'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
		
		$la_columnas1=array('forma'=>'');
		$la_data_tot1[1]=array('forma'=>'<b>FORMA: 2102</b>');
		$io_pdf->ezTable($la_data_tot1,$la_columnas1,'',$la_config1);

		//$io_pdf->ezSetDy(-100);
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
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Distribucion de Presupuesto a nivel de partidas";
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
          		$li_cmbnivel="1";
		}
          	else
		{
          		$li_cmbnivel=$cmbnivel;
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
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
		
		if($li_cmbnivel==1)
		{
			$ls_titulo=" <b>PRESUPUESTO DE INGRESOS A NIVEL DE RAMO </b>"; 
		}
		if($li_cmbnivel==2)
		{
			$ls_titulo=" <b>PRESUPUESTO DE GINGRESOS A NIVEL DE SUB-RAMO </b>"; 
		}
		if($li_cmbnivel==3)
		{
			$ls_titulo=" <b>PRESUPUESTO DE INGRESOS A NIVEL DE ESPECIFICAS </b>"; 
		}
		if($li_cmbnivel==4)
		{
			$ls_titulo=" <b>PRESUPUESTO DE INGRESOS</b>"; 
		}
		$ls_titulo1="En Bol�vares";
		$mover=-5;
	//--------------------------------------------------------------------------------------------------------------------------------
    
	/*$lb_valido = uf_insert_seguridad();
	if ($lb_valido)
	   {*/
	     //Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )


         $lb_valido=$io_report->uf_spg_reportes_distribucion_ingresos($li_cmbnivel);
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
	 		  $io_pdf->ezSetCmMargins(7.4,3,3,3); // Configuraci�n de los margenes en cent�metros
			  uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la p�gina
			  $io_pdf->ezStartPageNumbers(580,40,10,'','',1); // Insertar el n�mero de p�gina


			  $ld_total_pres_anual=0;
			  $ld_total_programada_acumulado=0;
			  $ld_total_monto_ejecutado=0;
			  $ld_total_ejecutado_acumulado=0;
			  $ld_total_variacion_absoluta=0;
			  $ld_total_porcentaje_variacion=0;
			  $ld_total_variacion_abs_acum=0;
			  $ld_total_porcentaje_variacion_acum=0;
			  $ld_total_reprog_prox_mes=0;
			  $li_total=$io_report->dts_reporte->getRowCount("spi_cuenta");
			  for ($z=1;$z<=$li_total;$z++)
			      {
	
				    $thisPageNum=$io_pdf->ezPageCount;

				    $ls_spg_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$z];
				
					//print $ls_spg_cuenta;
				    $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$z];
					$li_nivel=$io_report->dts_reporte->data["nivel"][$z];
					$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
				// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
					$ls_partida=substr($ls_spg_cuenta,0,3);

					$ls_partida_gen=substr($ls_spg_cuenta,3,2);
					$ls_partida_vieja=$ls_partida_gen;
					$ls_partida_esp=substr($ls_spg_cuenta,5,2);
					$ls_partida_subesp=substr($ls_spg_cuenta,7,2);
					if($ls_partida_gen=="00"){$ls_partida_gen="";$ls_partida='<b>'.$ls_partida.'</b>';$ls_denominacion='<b>'.$ls_denominacion.'</b>';}
					if($ls_partida_esp=="00"){$ls_partida_esp="";}
				  	if($ls_partida_subesp=="00"){$ls_partida_subesp="";}
					$ld_pres_anual=$ld_asignado;  //programado de los meses
		
		
					if($li_nivel==1)
					{
						$ld_total_pres_anual=$ld_total_pres_anual+$ld_asignado;
					
					}	
					$ld_pres_anual=number_format($ld_pres_anual,2,",",".");
					if($ls_partida_vieja=="00"){$ld_pres_anual='<b>'.$ld_pres_anual.'</b>';}
					$la_data[$z]=array('partida'=>$ls_partida,'generica'=>$ls_partida_gen,'especifica'=>$ls_partida_esp,'sub-especifica'=>$ls_partida_subesp,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual);
										  
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
						 
							 $la_data_tot[$z]=array('total'=>'[6] <b>T O T A L  Bs.</b>','pres_anual'=>'<b>'.$ld_total_pres_anual.'</b>');
							
							
							
							 $ld_total_pres_anual_bsf=number_format($ld_total_pres_anual_bsf,2,",",".");

							 					
    						 $la_data_tot_bsf[$z]=array('total'=>'<b>TOTAL BsF.</b>','pres_anual'=>$ld_total_pres_anual_bsf);
						}
			        	}//if
			
			 }//for
			
			//uf_print_titulo_reporte($li_ano,$ls_meses,$ls_etiqueta,$io_pdf);
			//uf_print_titulo($ls_etiqueta,$io_pdf);
			uf_print_cabecera($io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			uf_print_pie_cabecera($la_data_tot,$io_pdf);
			//uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);
			unset($la_data);
			unset($la_data_tot);
			unset($la_data_tot_bsf);
			if($z<$li_total)
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
		}//else
	unset($io_report);
	unset($io_funciones);	
	/*   }
	else
	   {
	   
	   }*/

?> 
