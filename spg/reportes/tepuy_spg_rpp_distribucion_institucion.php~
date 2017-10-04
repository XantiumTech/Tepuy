<?php
    	session_start();  
	ini_set('display_errors', 1);
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
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

		$io_pdf->addText(480,762,6,"Dirección de Planificación y Presupuesto"); // Direccion
		$io_pdf->addText(530,86,8,"<b>Forma: 2100</b>"); // Direccion	
		$io_pdf->rectangle(20,640,570,120); //Margen Izq, Arriba, Ancho, Alto
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],27,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//../../shared/
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,695,52,60); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_barinas_2015.jpg',535,692,52,60); // Agregar Logo
		//$io_pdf->line(10,40,578,100);

			$io_pdf->closeObject();		
		
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$as_titulo= strtoupper($_SESSION["la_empresa"]["nombre"]);
		//$ls_ciudad= strtoupper($_SESSION["la_empresa"]["ciuemp"]);
		$ls_periodo= substr($_SESSION["la_empresa"]["periodo"],0,4);
		//$io_pdf->addText(25,685,8,"[1] ENTIDAD: ".$ls_entidad); // ENTIDAD
		//$io_pdf->addText(25,675,8,"MUNICIPIO: ".$ls_ciudad); // CIUDAD
		$io_pdf->addText(25,658,8,"PRESUPUESTO: ".'<b>'.$ls_periodo.'</b>'); // PRESUPUESTO PERIODO		

		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,655,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
		
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
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/11/2009
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		//$io_pdf->saveState();
		//$io_pdf->ezSetY(600);
		$io_pdf->rectangle(20,80,570,560); //Margen Izq, Arriba, Ancho, Alto
		$io_pdf->line(19,620,590,620);	//Horizonta 1
		$io_pdf->line(19,590,590,590);	//Horizonta 2
		$io_pdf->line(19,550,590,550);	//Horizonta 3
		$io_pdf->line(19,98,590,98);	//Horizonta 3

		$io_pdf->line(90,550,90,590); // 1era Vertical
		$io_pdf->line(165,550,165,590); // 2da Vertical
		$io_pdf->line(260,98,260,590);	// 3era Vertical
		$io_pdf->line(410,550,410,590);	// 4ta Vertical
		$io_pdf->line(500,80,500,590);	// 4ta Vertical

		$domicilio= strtoupper(trim($_SESSION["la_empresa"]["direccion"]));

		$io_pdf->addText(24,628,8,"<b>DOMICILIO LEGAL: </b>".$domicilio); // DOMICILIO
		$io_pdf->addText(24,608,8,"<b>FECHA DE CREACION: </b>"); // FECHA DE CREACION
		$creacion="02 DE ENERO DE 1990";
		$io_pdf->addText(24,598,8,$creacion); // FECHA DE CREACION
		$io_pdf->addText(38,580,8,"<b>CIUDAD</b>"); // CIUDAD
		$ciudad= strtoupper(trim($_SESSION["la_empresa"]["ciuemp"]));
		$io_pdf->addText(36,560,8,$ciudad); // CIUDAD
		$io_pdf->addText(110,580,8,"<b>ESTADO</b>"); // ESTADO
		$estado= strtoupper(trim($_SESSION["la_empresa"]["estemp"]));
		$io_pdf->addText(108,560,8,$estado); // ESTADO
		$io_pdf->addText(185,580,8,"<b>TELEFONOS</b>"); // TELEFONOS
		$telefonos= strtoupper(trim($_SESSION["la_empresa"]["telemp"]));
		$io_pdf->addText(183,560,8,$telefonos); // TELEFONOS
		$io_pdf->addText(306,580,8,"<b>DIR. INTERNET</b>"); // INTERNET
		$email= trim($_SESSION["la_empresa"]["email"]);
		$website=trim($_SESSION["la_empresa"]["website"]);
		$io_pdf->addText(264,570,8,$email); // MAIL
		$io_pdf->addText(284,560,8,$website); // WEB
		$io_pdf->addText(446,580,8,"<b>FAX</b>"); // FAX
		$fax= strtoupper(trim($_SESSION["la_empresa"]["faxemp"]));
		$io_pdf->addText(430,560,8,$fax); // FAX
		$io_pdf->addText(510,580,8,"<b>CODIGO POSTAL</b>"); // CODIGO POSTAL
		$postal= strtoupper(trim($_SESSION["la_empresa"]["zonpos"]));
		$io_pdf->addText(540,560,8,$postal); // CODIGO POSTAL
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
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Detalles de la Institucion";
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

	//-----------------------------------------------------------------------------------------------------------------------------		 

		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_titulo=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);

	//--------------------------------------------------  Parámetros funcionarios  -----------------------------------------

		$ls_funcionario[1]=strtoupper($_SESSION["la_empresa"]["gerente"]);
		$ls_cargo[1]	  ="ALCALDE"; //DEL MUNICIPIO ".strtoupper(trim($_SESSION["la_empresa"]["ciuemp"]));
		$ls_funcionario[2]=strtoupper($_SESSION["la_empresa"]["dirgeneral"]);
		$ls_cargo[2]	  =strtoupper(trim($_SESSION["la_empresa"]["cargodirgeneral"]));
		$ls_funcionario[6]=strtoupper($_SESSION["la_empresa"]["jefe_administracion"]);
		$ls_cargo[6]	  =strtoupper(trim($_SESSION["la_empresa"]["cargo_administracion"]));
		$ls_funcionario[9]=strtoupper($_SESSION["la_empresa"]["jefe_nominas"]);
		$ls_cargo[9]	  =strtoupper(trim($_SESSION["la_empresa"]["cargo_nominas"]));
		$ls_funcionario[13]=strtoupper($_SESSION["la_empresa"]["jefe_presupuesto"]);
		$ls_cargo[13]	  =strtoupper(trim($_SESSION["la_empresa"]["cargo_presupuesto"]));
		// CONCEJALES //
		for($i=1;$i<=11;$i++)
		{
			$concejal[$i]		=strtoupper($_SESSION["la_empresa"]["firma".$i."_ordenanza"]);
			$cargo_concejal[$i]	=strtoupper($_SESSION["la_empresa"]["cargo".$i."_ordenanza"]);
		}
		////////////////
		// LEO EL ARCHIVO DE FUNCIONARIOS EN EL ARCHIVO /reportes/funcionarios.txt //
		$file = fopen("funcionarios.txt", "r") or exit("El archivo Funcionarios No Existe!");
		//Leo el archvivo linea a linea
		$lb_valido=false;
		while(!feof($file))
		{
			$linea = fgets($file);
			$lb_valido=true;
			//print $linea;
			if(strlen(trim($linea))>0)
			{
				$pos=trim(substr($linea,0,2));
				$ls_funcionario[$pos]=utf8_decode(trim(strtoupper(substr($linea,53,31))));
				$ls_cargo[$pos]=utf8_decode(trim(strtoupper(substr($linea,3,49))));
			}//if
		}//while
		fclose($file);

	//--------------------------------------------------------------------------------------------------------------------------------

	if ($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('Revise el Archivo de Funcionarios');"); 
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
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$thisPageNum=$io_pdf->ezPageCount;

		uf_print_cabecera($io_pdf);
		$io_pdf->addText(26,535,7,"<b>".$ls_cargo[1]."</b>"); // Funcionario
		$io_pdf->addText(26,515,7,$ls_funcionario[1]); // Cargo

		$hasta=count($ls_cargo);
		$io_pdf->addText(26,485,7,"<b>PERSONAL DIRECTIVO DE LA ALCALDIA</b>"); // 
		$fila=460;
		for($i=2;$i<=$hasta;$i++)
		{
			$io_pdf->addText(26,$fila,7,"<b>".$ls_cargo[$i]."</b>"); // Funcionario
			$io_pdf->addText(266,$fila,7,$ls_funcionario[$i]); // Cargo

			if(strlen(trim($ls_cargo[$i]))>0)
			{
				$fila=$fila-10;
			}
		}
		$io_pdf->addText(26,275,7,"<b>CONCEJALES</b>"); // 
		$fila=260;
		$hasta=count($concejal);
		for($i=2;$i<=$hasta;$i++)
		{
			$io_pdf->addText(26,$fila,7,"<b>".$concejal[$i]."</b>"); // Funcionario
			$io_pdf->addText(266,$fila,7,$cargo_concejal[$i]); // Cargo

			if(strlen(trim($concejal[$i]))>0)
			{
				$fila=$fila-10;
			}
		}
		//uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		//unset($la_data);
		
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
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}//else
?> 
