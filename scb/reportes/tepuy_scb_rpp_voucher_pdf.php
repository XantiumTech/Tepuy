<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Estandar tepuy C.A.
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 echo "<script language=JavaScript>";
		 echo "opener.document.form1.submit();"	;	
		 echo "close();";
		 echo "</script>";		
	   }	
	$x_pos		   = 0;//mientras mas grande el numero, mas a la derecha.
	$y_pos		   = -1;//Mientras mas pequeño el numero, mas alto.
	$ls_directorio = "cheque_configurable";
	$ls_archivo	   = "cheque_configurable/medidas.txt";
	$li_medidas    = 16;

	function uf_inicializar_variables()
	{
		global $valores;
		global $ls_directorio;
		global $ls_archivo;	
		global $li_medidas;	
		if(!file_exists ($ls_directorio))
		{
			$lb_exito=mkdir($ls_directorio,0777);
			if(!$lb_exito)
			{
				print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			}
		}
		
		if((!file_exists ($ls_archivo)) || (filesize($ls_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($ls_archivo, "w");			
				$ls_contenido="138.00-6.00-32.00-24.00-32.00-28.00-32.00-34.00-32.00-43.00-77.00-43.00-137.00-65.00-131.00-70.00";
				fwrite($archivo,$ls_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($ls_archivo)) && (filesize($ls_archivo)>0))
		{
			$archivo = fopen($ls_archivo, "r");
			$contenido = fread($archivo, filesize($ls_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$li_medidas)
			{
				$archivo = fopen($ls_archivo, "w");
				fclose($archivo);			
				print "<script>";
				print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				print "close();";
				print "</script>";
			}
		}
		else
		{
			print "<script>";
			print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
			print "close();";
			print "</script>";
		}
	}// end function uf_inicializar_variables.
	
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		$ls_mover=24;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$ls_mover+$valores[1],10,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$ls_mover+$valores[3],11,"<b>$ls_nomproben</b>");
		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(156,730,11,"COMPROBANTE DE EGRESO"); // Agregar el título	
		$ls_monto_cortado=wordwrap($ls_monto,70,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			$io_pdf->add_texto($valores[4],$ls_mover+$valores[5],9,"<b>$la_arreglo[0]</b>");
		if(array_key_exists(1,$la_arreglo))
			$io_pdf->add_texto($valores[6],$ls_mover+$valores[7],9,"<b>$la_arreglo[1]</b>");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$ls_mover+$valores[9],9,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$ls_mover+$valores[11],9,"<b>$ls_anio</b>");	
		$io_pdf->add_texto($valores[12],$ls_mover+$valores[13],9,"<b>NO ENDOSABLE</b>");
		$io_pdf->add_texto($valores[14],$ls_mover+$valores[15],9,"<b>CADUCA A LOS ".$_SESSION["la_empresa"]["diacadche"]." DIAS</b>");		
	}// end function uf_print_encabezadopagina.

	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->setStrokeColor(0,0,0);
		//modifica la posicion de la tabla
		$li_pos=160;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$la_data=array(array('banco'=>'<b>Banco</b>  ','cheque'=>'<b>Cheque Nº</b>  ','cuenta'=>'<b>Cuenta Nº:</b>  '),
						array('banco'=>$ls_nomban,'cheque'=>$ls_numdoc,'cuenta'=>$ls_ctaban));
		$la_columna=array('banco'=>'','cheque'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('banco'=>array('justification'=>'left','width'=>230),'cheque'=>array('justification'=>'left','width'=>120),
						 'cuenta'=>array('justification'=>'left','width'=>230),'voucher'=>array('justification'=>'left','width'=>160))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('ordenes'=>'<b>Orden(es) de Pago(s):</b> '.$ls_solicitudes),
					   array('ordenes'=>'<b>Beneficiario:</b> '.$ls_nomproben),
					   array('ordenes'=>'<b>Concepto:</b> '.$ls_conmov));
		$la_columna=array('ordenes'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('ordenes'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera.

	function uf_print_detalle($la_title,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetDy(-5);
		$io_pdf->setStrokeColor(0,0,0);
		$la_data_title=array($la_title);
		$io_pdf->set_margenes(90,55,0,0);
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>350),'title2'=>array('justification'=>'center','width'=>230))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data_title,$la_columna,'',$la_config);	
		//Imprimo los detalles tanto `de presupuesto como contablwe del movimiento
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>195),
			 						   'spg_cuenta'=>array('justification'=>'center','width'=>80),
									   'monto_spg'=>array('justification'=>'right','width'=>75),
						 			   'scg_cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$la_columnas=array('estpro'=>'<b>Programatica</b>',
						   'spg_cuenta'=>'<b>Cuenta</b>',
						   'monto_spg'=>'<b>Monto</b>',
						   'scg_cuenta'=>'<b>Cuenta</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle.
	
	function uf_print_autorizacion($as_nomusu,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);		
		/*$io_pdf->Rectangle(11,43,580,105);
		$io_pdf->line(11,90,590,90);
		$io_pdf->line(11,74.6,590,74.6);		
		$io_pdf->line(127,90,127,148);
		$io_pdf->line(243,90,243,148);
		$io_pdf->line(359,90,359,148);
		$io_pdf->line(475,90,475,148);		
		$io_pdf->line(191,43,191,75);
		$io_pdf->line(310.5,43,310.5,75);
		$io_pdf->line(411,43,411,75);	
		
		$io_pdf->addText(16,137.6,9,'<b>Elaborado por:</b>');
		$io_pdf->addText(132,137.6,9,'<b>Revisado por:</b>');
		$io_pdf->addText(273,137.6,9,'<b>Contabilizado</b>');		
		$io_pdf->addText(384,137.6,9,'<b>Administración</b>');		
		$io_pdf->addText(508,137.6,9,'<b>Despacho</b>');
		$io_pdf->addText(258,78.85,10,'<b>Recibí Conforme</b>');
		
		$io_pdf->addText(16,63.27,10,'<b>Nombre:</b>');		
		$io_pdf->addText(196,63.27,10,'<b>Cédula de Identidad:</b>');		
		$io_pdf->addText(316,63.27,10,'<b>Fecha:</b>');
		$io_pdf->addText(416,63.27,10,'<b>Firma:</b>');*/

//FORMATO ALCALDIA DE GUASDUALITO
 		//$io_pdf->Rectangle(15,60,570,70);
		$io_pdf->Rectangle(20,47,570,135);
		$io_pdf->line(20,172,590,172); //linea que divide firmas y sellos
		$io_pdf->line(20,115,590,115); //linea superior de la RECEPCION
		$io_pdf->line(20,163,590,163);	// Linea que se encuentra en el nivel inferior de ELABORADO POR:

		$io_pdf->line(140,115,140,173);	// ESTAS LINEAS DIVIDEN LOS ESP. VERT. DE LAS FIRMAS QUE APRUEBAN	
		$io_pdf->line(260,115,260,173);		
		$io_pdf->line(365,115,365,173);
		$io_pdf->line(485,115,485,173);						
		$io_pdf->addText(240,175,6,"<b>FIRMAS Y SELLOS PARA LA APROBACIÓN DEL CHEQUE</b>"); // Agregar el título
		//$li_tm=$io_pdf->getTextWidth(13,$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]);

		$li_tm=$io_pdf->getTextWidth(13,$as_nomusu);
		$tm=128-($li_tm/2);
		$io_pdf->addText(50,165,6,"ELABORADO POR:"); // Agregar el título
		$io_pdf->addText($tm,118,6,$as_nomusu); // Agregar el título

		$io_pdf->addText(154,118,6,"LCDO. JEAN CARLOS OROZCO"); // Agregar el título
		$io_pdf->addText(175,165,6,"REVISADO POR:"); // Agregar el título
		$io_pdf->addText(277,118,6,"LCDO. RAFAEL LÓPEZ"); // Agregar el título
		$io_pdf->addText(278,165,6,"CONTABILIZADO POR:"); // Agregar el título
//		$io_pdf->addText(440,122,7,"DESPACHO DEL ALCALDE"); // Agregar el título
		$ls_nomrep = $_SESSION["la_empresa"]["nomrep"];
		$ls_cargorep = $_SESSION["la_empresa"]["cargorep"];
		$li_tm=$io_pdf->getTextWidth(6,$ls_nomrep);
		$tm=535-($li_tm/2);
		$io_pdf->addText($tm,118,6,$ls_nomrep);
//		$io_pdf->addText(482,118,6,"JOSE ALVARADO"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(6,$ls_cargorep);
		$tm=535-($li_tm/2);
		$io_pdf->addText($tm,165,6,"ALCALDE"); // Agregar el título
		$io_pdf->addText(380,118,6,"  LCDA. YURIBETH DAZA"); // Agregar el título
		$io_pdf->addText(395,165,6,"ADMINISTRACIÓN"); // Agregar el título


		$io_pdf->line(20,101,590,101); // LINEA QUE SE MUESTRA EN LA PARTE INFERIOR DE LA RECEPCION
		$io_pdf->addText(240,105,7,"<b>RECEPCION CONFORME DE LA ORDEN POR EL PROVEEDOR</b>");
		$io_pdf->line(20,92,590,92); // LINEA QUE SE MUESTRA EN LA PARTE INFERIOR DE LOS DATOS DE RECEPCION
		$io_pdf->line(220,47,220,101);		
		$io_pdf->line(360,47,360,101);	
		$io_pdf->addText(80,94,7,"APELLIDO (S) Y NOMBRE (S)");
		$io_pdf->addText(250,94,7,"CEDULA DE IDENTIDAD N°");
		$io_pdf->addText(405,94,7,"FIRMA, FECHA Y SELLO. RECIBIDO CONFORME");


		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
		$ls_diremp = $_SESSION["la_empresa"]["direccion"];
		$ls_telemp = $_SESSION["la_empresa"]["telemp"];
		$ls_ciuemp = $_SESSION["la_empresa"]["ciuemp"];
		$ls_estemp = $_SESSION["la_empresa"]["estemp"];
		
		$li_tm=$io_pdf->getTextWidth(5,"<b>".$ls_nomemp."</b> R.I.F.: ".$ls_rifemp);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,40,5,"<b>".$ls_nomemp."</b> R.I.F.: ".$ls_rifemp);
		$li_tm=$io_pdf->getTextWidth(5,$ls_diremp." ".$ls_telemp);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,35,5,$ls_diremp." ".$ls_telemp);
		$li_tm=$io_pdf->getTextWidth(5,$ls_ciuemp.", ESTADO ".$ls_estemp);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,30,5,$ls_ciuemp.", ESTADO ".$ls_estemp);
		$io_pdf->ezSetY(690);


		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_autorizacion.	

uf_inicializar_variables();
require_once("tepuy_scb_report.php");
require_once('../../shared/class_folder/class_pdf.php');
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_numero_a_letra.php");

$io_include   = new tepuy_include();
$ls_conect    = $io_include->uf_conectar();
$io_sql		  = new class_sql($ls_conect);	
$class_report = new tepuy_scb_report($ls_conect);
$io_funciones = new class_funciones();				
$ds_voucher	  = new class_datastore();	
$ds_dt_scg	  = new class_datastore();				
$ds_dt_spg	  = new class_datastore();
$numalet	  = new class_numero_a_letra();

//imprime numero con los valore por defecto
//cambia a minusculas
$numalet->setMayusculas(1);
//cambia a femenino
$numalet->setGenero(1);
//cambia moneda
$numalet->setMoneda("Bolivares");
//cambia prefijo
$numalet->setPrefijo("***");
//cambia sufijo
$numalet->setSufijo("***");

	$ls_codemp = $_SESSION["la_empresa"]["codemp"];
	$ls_codban = $_GET["codban"];
	$ls_ctaban = $_GET["ctaban"];
	$ls_numdoc = $_GET["numdoc"];
	$ls_chevau = $_GET["chevau"];
	$ls_codope = $_GET["codope"];


	$data 	   = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$class_report->SQL->begin_transaction();
	if (!$lb_valido)
	   {
		 print "Error al actualizar";
		 $class_report->is_msg_error;	
		 $class_report->SQL->rollback();
	   }
	else
	   {
		 $class_report->SQL->commit();
	   }
	$ds_voucher->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->set_margenes(0,55,0,0);
	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacción
	$thisPageNum=$io_pdf->ezPageCount;
	$ls_codusu	= $ds_voucher->data["codusu"][1];
	$ls_nomusu 	= $class_report->uf_cargar_usuario($ls_codusu);
	uf_print_autorizacion($ls_nomusu,$io_pdf);	
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		unset($la_data);
		$li_totprenom = 0;
		$ldec_mondeb  = 0;
		$ldec_monhab  = 0;
		$li_totant	  = 0;
		$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
		$ls_codban		= $ds_voucher->data["codban"][$li_i];
		$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
		$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
		$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
		$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
		$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
		$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
		$ldec_total		= $ldec_monto-$ldec_monret;
		//Asigno el monto a la clase numero-letras para la conversion.
		$numalet->setNumero($ldec_total);
		//Obtengo el texto del monto enviado.
		$ls_monto= $numalet->letra();
		uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(570,30,10,'','',1); // Insertar el número de página
		uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
		
		$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
		$la_items = array('0'=>'scg_cuenta');
		$la_suma  = array('0'=>'monto');
		$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
		$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
		
		$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
		$la_suma  = array('0'=>'monto');
		$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
		$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a imprimirlos.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($li_totrow_det>=$li_totrow_spg)
		   {
			 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			     {
				   $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
				   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
				   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
				   if ($ls_debhab=='D')
				      {
					    $ldec_mondeb = number_format($ldec_monto,2,",",".");
					    $ldec_monhab = "";
				      }
				   else
				      {
					    $ldec_monhab = number_format($ldec_monto,2,",",".");
					    $ldec_mondeb = "";
				      }
				   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					       {
						     $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
						     $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
						     $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					       }
					    else
						   {
							 $ls_cuentaspg   = "";	
							 $ls_estpro      = "";	  
							 $ldec_monto_spg = "";
						   }
					  }
				   else
				      {
						$ls_cuentaspg   = "";	
						$ls_estpro      = "";	  
						$ldec_monto_spg = "";
				      }
				   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			     }
		   }
		if ($li_totrow_spg>$li_totrow_det)
		   {
			 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
			     {
				   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
					       {
							 $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
							 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
							 if ($ls_debhab=='D')
								{
								  $ldec_mondeb = number_format($ldec_monto,2,",",".");
								  $ldec_monhab = "";
								}
							 else
							    {
								  $ldec_monhab = number_format($ldec_monto,2,",",".");
								  $ldec_mondeb = "";
							    }
						   }
					    else
						   {
						     $ls_scg_cuenta = "";
						  	 $ls_debhab 	= "";
						   	 $ldec_monto	= "";
							 $ldec_mondeb	= "";
							 $ldec_monhab   = "";					
						   }
				      }
				   else
				      {
					    $ls_scg_cuenta = "";
					    $ls_debhab 	   = "";
						$ldec_monto	   = "";
						$ldec_mondeb   = "";
						$ldec_monhab   = "";					
					  }
				   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						   {
							 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
							 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
							 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						   }
					    else
						   {
						     $ls_cuentaspg   = "";	
							 $ls_estpro      = "";	  
						 	 $ldec_monto_spg = "";
						   }
				      }
				   else
					  {
					    $ls_cuentaspg   = "";	
					    $ls_estpro      = "";	  
					    $ldec_monto_spg = "";
					  }
				   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			     }
		   }
		if (empty($la_data))
		   {
			 $ls_cuentaspg	 = '';
			 $ls_estpro		 = '';
			 $ldec_monto_spg = '';
			 $ls_scg_cuenta  = '';
			 $ldec_mondeb	 = '';
			 $ldec_monhab	 = '';
			 $la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			 $la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			 $la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
		   }
		//ELIMINA IMPRIMIR DETALLES PRESUPUESTARIOS
		//uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf);
	}
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 
