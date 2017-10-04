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
		//$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el t�tulo

		$li_tm1=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm1=306-($li_tm1/2);
		//$io_pdf->addText($tm1,680,11,$as_titulo1); // Agregar el t�tulo
		
		//$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		//$tm=306-($li_tm/2);
		//$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el t�tulo
// ESTAS LINEAS SON NECESARIAS PARA QUE SE PUEDA MOSTRAR EL PDF //
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');


	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	

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
		
		$io_pdf->ezSetY(400);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 20, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de
		$la_columnas=array('titulo'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
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
	    $ls_descripcion = "Imprimio Separador de Contenido de la Ordenanza";
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

		$ls_ejercicio="<b> Ejercicio Econ�mico Financiero 2.010 </b>";
		$ls_titulo  =" <b> DISPOSICIONES GENERALES </b>";
		$ls_titulo1 =" <b> PRESUPUESTO DE INGRESOS </b>";
		$ls_titulo2 =" <b> PRESUPUESTO DE EGRESOS </b>";
		if($cmbnivel=="s1")
		{
			$desde=1;
			$hasta=3;

		}
		if($cmbnivel=="01")
		{         		
			$desde=1;
			$hasta=1;

		}
		if($cmbnivel=="02")
		{
          		$desde=2;
			$hasta=2;
		}
		if($cmbnivel=="03")
		{
			$desde=3;
          		$hasta=3;

		}

	$lb_valido=true;
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
			$ls_cabeza="";$ls_cabeza1="";
			  uf_print_encabezado_pagina($ls_cabeza,$ls_cabeza1,$io_pdf); // Imprimimos el encabezado de la p�gina
			  //$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina

			  $ubica=0;
			  for ($z=$desde;$z<=$hasta;$z++)
			      {

				$thisPageNum=$io_pdf->ezPageCount;
					
				for ($l=1;$l<=20;$l++)
				{
					$ubica=$ubica+1;
					if($l==1)
					{
						if($z==1){$la_data[$ubica]=array('titulo'=>$ls_titulo);}
						if($z==2){$la_data[$ubica]=array('titulo'=>$ls_titulo1);}
						if($z==3){$la_data[$ubica]=array('titulo'=>$ls_titulo2);}
						$ubica=$ubica+1;
					}
					else
					{
						if($z<$hasta){$la_data[$ubica]=array('titulo'=>' ');}
					}
				}
				}//for
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			if($z<$hasta)
			{
				 $io_pdf->ezNewPage(); // Insertar una nueva p�gina
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
			unset($io_report);
			unset($io_funciones);
		}//else

?> 
