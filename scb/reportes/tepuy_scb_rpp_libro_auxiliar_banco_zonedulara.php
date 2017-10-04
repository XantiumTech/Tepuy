<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "opener.document.form1.submit();";
		 print "close();";
		 print "</script>";		
	   }
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');  
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$ls_banco,$ls_nomtipcta,$ls_ctaban,$as_denctaban,$as_nommes,$as_nomres,$as_cedres,$as_dencar,&$io_pdf)
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
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(15,40,770,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,580,12,$as_titulo);// Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_denctaban);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,565,11,$as_denctaban); // Agregar el título
		$io_pdf->addText(725,585,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(725,575,8,date("h:i a")); // Agregar la Hora
        $io_pdf->addText(510,550,8,'Banco: <b>'.$ls_banco.'</b>'); // Agregar la Fecha
		$io_pdf->addText(510,540,8,$ls_nomtipcta.' N°: '.$ls_ctaban); // Agregar la Hora
		
		$io_pdf->Rectangle(15,525,760,70);
		
		$io_pdf->ezSetY(520);
		$la_data=array(array('title'=>'Mes: <b>'.strtoupper($as_nommes).'</b>','title2'=>''),
					   array('title'=>'Organismo','title2'=>''),
					   array('title'=>'Código: 10','title2'=>'Denominación: <b>Ministerio Del Poder Popular para la Educación</b>'),
					   array('title'=>'Unidad Administradora','title2'=>''),
					   array('title'=>'Código: <b>2006</b>','title2'=>'Denominación: <b>'.$_SESSION["la_empresa"]["nombre"].'</b>'),
					   array('title'=>'Identificación del Funcionario Responsable','title2'=>''),
					   array('title'=>'Apellidos y Nombres: <b>'.$as_nomres.'</b>                         <b>C.I.:</b> '.$as_cedres,'title2'=>'Denominación del Cargo: <b>'.$as_dencar.'</b>'));
		
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>400, // Orientación de la tabla
						 'width'=>760, // Ancho de la tabla
						 'maxWidth'=>760,
						 'fontSize'=>9,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>380),
						               'title2'=>array('justification'=>'left','width'=>380))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		unset($la_data,$la_columna,$la_config);		
 		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($li_totrow,&$io_pdf)
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
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($la_data,$la_columna,$la_config);
		if($li_totrow>0)
			{
			$la_data[0]=array('item'=>'<b>N°</b>','fecha'=>'<b>Fecha</b>','numdoc'=>'<b>Documento</b>','operacion'=>'<b>Operación</b>','beneficiario'=>'<b>Proveedor/Beneficiario</b>','descripcion'=>'<b>                         Descripción</b>','debito'=>'<b>Débitos</b>','credito'=>'<b>Créditos</b>','saldo'=>'<b>Saldo</b>','nropag'=>'<b>N° de Pago</b>');
			$la_columna=array('item'=>'','fecha'=>'','numdoc'=>'','operacion'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'','nropag'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>2, // Mostrar Líneas
							 'shaded'=>2, // Sombra entre líneas
							 'shadeCol2'=>array(0.85,0.85,0.85), // Color de la sombra
							 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>760, // Ancho de la tabla
							 'colGap'=>1,
							 'maxWidth'=>760,
							 'fontSize'=>9,
							 'cols'=>array('item'=>array('justification'=>'center','width'=>20),
							               'fecha'=>array('justification'=>'center','width'=>60),
										   'numdoc'=>array('justification'=>'center','width'=>80),
										   'operacion'=>array('justification'=>'center','width'=>55),										   
										   'descripción'=>array('justification'=>'center','width'=>225),									   
										   'debito'=>array('justification'=>'right','width'=>80),
										   'credito'=>array('justification'=>'right','width'=>80),
										   'saldo'=>array('justification'=>'right','width'=>80),
										   'nropag'=>array('justification'=>'center','width'=>80))); // Ancho Máximo de la tabla	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 
		}
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_columna,$la_config,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_totales($ldec_debe,$ldec_haber,$ldec_saldoactual,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
		$la_data=array(array('item'=>'','fecha'=>'','numdoc'=>'','operacion'=>'','descripcion'=>'<b>Totales:</b>','debito'=>$ldec_debe,'credito'=>$ldec_haber,'saldo'=>$ldec_saldoactual,'nropag'=>''));
		$la_columna=array('item'=>'','fecha'=>'','numdoc'=>'','operacion'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'','nropag'=>'');
		$la_config = array('showHeadings'=>0, // Mostrar encabezados
						   'showLines'=>2, // Mostrar Líneas
						   'shaded'=>0, // Sombra entre líneas
 						   'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						   'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						   'xOrientation'=>'center', // Orientación de la tabla
						   'width'=>760, // Ancho de la tabla
						   'maxWidth'=>760,
						   'colGap'=>1,
						   'fontSize'=>9,
						   'cols'=>array('item'=>array('justification'=>'center','width'=>20),
						                 'fecha'=>array('justification'=>'center','width'=>60),
						                 'numdoc'=>array('justification'=>'center','width'=>80),
										 'operacion'=>array('justification'=>'center','width'=>55),						 			     
									     'descripcion'=>array('justification'=>'right','width'=>225),									   
									     'debito'=>array('justification'=>'right','width'=>80),
										 'credito'=>array('justification'=>'right','width'=>80),
									     'saldo'=>array('justification'=>'right','width'=>80),
										 'nropag'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once("tepuy_scb_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_sql.php");
	require_once('../../shared/class_folder/class_fecha.php');
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
	
	$io_include   = new tepuy_include();
	$io_conect    = $io_include->uf_conectar();
	$io_sql       = new class_sql($io_conect);	
	$io_report    = new tepuy_scb_report($io_conect);
	$io_funcion   = new class_funciones();				
	$ds_edocta    = new class_datastore();
	$io_fecha     = new class_fecha(); 	
	
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ls_codban    = $_GET["codban"];
	$ls_nomban    = $_GET["nomban"];
	$ls_ctaban    = $_GET["ctaban"];
	$ls_denctaban = $_GET["denctaban"];
	$li_nummes    = $_GET["mes"];
	$ls_cedres    = $_GET["cedres"];
	$ls_nomres    = $_GET["nomres"];
	$ls_dencar    = $_GET["nomcar"];
	$ls_nommes    = $_GET["nommes"];
	$li_anio      = date('Y');
	$ld_fecdesde  = $li_anio.'-'.$li_nummes.'-01';
	$ld_fechasta  = $io_funcion->uf_convertirdatetobd($io_fecha->uf_last_day($li_nummes,$li_anio));
	
	$data = $io_report->uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,'F',$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber,true,'C','---');
	$ds_edocta->data=$data;
	$li_totrows = $ds_edocta->getRowCount("numdoc");

	set_time_limit(0);
    $io_pdf=new class_pdf('LETTER','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(6,2,3.5,3.5); // Configuración de los margenes en centímetros
	$ls_nomtipcta     = $io_report->uf_select_data($io_sql,"SELECT nomtipcta FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.ctaban='".$ls_ctaban."' AND c.codtipcta=t.codtipcta ","nomtipcta");	
	$ldec_saldoactual = ($ldec_saldoant+$ldec_total_debe-$ldec_total_haber);
	$ld_saldo         = $ldec_saldoant;
	$ldec_saldoant    = number_format($ldec_saldoant,2,",",".");
	uf_print_encabezado_pagina("<b>LIBRO AUXILIAR DE BANCO</b>","<b>Del</b> $ld_fecdesde <b>al</b> $ld_fechasta",$ls_nomban,$ls_nomtipcta,$ls_ctaban,$ls_denctaban,$ls_nommes,$ls_nomres,$ls_cedres,$ls_dencar,$io_pdf); // Imprimimos el encabezado de la página
	uf_print_cabecera($li_totrows,$io_pdf); // Imprimimos 
	$io_pdf->ezStartPageNumbers(720,28,10,'','',1); // Insertar el número de página
	if ($li_totrows>0)
	   {	
		 for ($li_i=1;$li_i<=$li_totrows;$li_i++)
		     {
		  	   $io_pdf->transaction('start'); // Iniciamos la transacción
			   $thisPageNum   = $io_pdf->ezPageCount;
			   $ldec_mondeb   = 0;
			   $ldec_monhab   = 0;
			   $li_totant     = 0;
			   $ls_numdoc      = $ds_edocta->getValue("numdoc",$li_i);
			   $ls_nomproben   = $ds_edocta->getValue("beneficiario",$li_i);		
			   $ls_conmov	   = $ds_edocta->getValue("conmov",$li_i);
			   $ls_operacion   = $ds_edocta->getValue("operacion",$li_i);
			   $ld_fecmov      = $io_funcion->uf_convertirfecmostrar($ds_edocta->getValue("fecmov",$li_i));
			   $ld_montotdeb   = $ds_edocta->getValue("debitos",$li_i);
			   $ld_montotcre   = $ds_edocta->getValue("creditos",$li_i);
			   $ld_saldo       = ($ld_saldo+$ld_montotdeb-$ld_montotcre);
			   $ld_montotdeb   = number_format($ld_montotdeb,2,",",".");
			   $ld_montotcre   = number_format($ld_montotcre,2,",",".");
			   $la_data[$li_i] = array('item'=>$li_i,'fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'descripcion'=>$ls_conmov,'debito'=>$ld_montotdeb,'credito'=>$ld_montotcre,'saldo'=>number_format($ld_saldo,2,",","."),'nropag'=>'');
		     }
		 $la_columna = array('item'=>'','fecha'=>'','numdoc'=>'','operacion'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'','nropag'=>'');
		 $la_config  = array('showHeadings'=>0, // Mostrar encabezados
						     'showLines'=>2, // Mostrar Líneas
						     'shaded'=>2, // Sombra entre líneas
						     'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
						     'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
						     'xOrientation'=>'center', // Orientación de la tabla
						     'width'=>760, // Ancho de la tabla
						     'maxWidth'=>760,
							 'colGap'=>1,
						     'fontSize'=>8,
						     'cols'=>array('item'=>array('justification'=>'center','width'=>20),
						                   'fecha'=>array('justification'=>'center','width'=>60),
									       'numdoc'=>array('justification'=>'center','width'=>80),								    
									       'operacion'=>array('justification'=>'center','width'=>55),
										   'descripción'=>array('justification'=>'center','width'=>225),
									       'debito'=>array('justification'=>'right','width'=>80),
										   'credito'=>array('justification'=>'right','width'=>80),
									       'saldo'=>array('justification'=>'right','width'=>80),
										   'nropag'=>array('justification'=>'center','width'=>80))); // Ancho Máximo de la tabla
		$io_pdf->ezSetCmMargins(7.2,2,3.5,3.5); // Configuración de los margenes en centímetros
		uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		uf_print_totales(number_format($ldec_total_debe,2,",","."),number_format($ldec_total_haber,2,",","."),number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
		$io_pdf->transaction('commit');
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf,$io_report,$io_funcion);		
	 }
  else
     {
	   print("<script language=JavaScript>");
	   print(" alert('No hay nada que Reportar');"); 
	   print(" close();");
	   print("</script>");
	 }
?> 