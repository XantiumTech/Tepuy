<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
	}
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$as_reporte,$as_mes1,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$li_tm1=$io_pdf->getTextWidth(11,$as_periodo);
		require_once("../../shared/class_folder/class_fecha.php"); 
		//$this->io_fecha=new class_fecha(); 
		$io_fecha=new class_fecha();
		//$hoy=date("d/m/Y");
		$dia=$io_fecha->uf_last_day(substr($as_mes1,3,2),substr($as_mes1,6,4));
		//$ano=substr($as_mes1,6,4);
		$mes1=(substr($dia,3,2)+1);
		$len=1;
       		for ($i=0;$i<$len;$i++)   {  $mes1="0".$mes1;   }
		$nuevafecha="05/".$mes1.substr($as_mes1,5,5);
		//$nuevafecha=date("m/d/Y",strtotime($nuevafecha));
		if($as_reporte=="V") // Formato Vertical
		{
			$io_pdf->line(20,40,578,40);
			$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
			$tm=306-($li_tm/2);
			$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
			$tm1=306-($li_tm1/2);
			$io_pdf->addText($tm1,718,11,$as_periodo); // Agregar el t�tulo
			//$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(500,730,10,$nuevafecha); // Agregar la Fecha
		}
		else
		{
			$io_pdf->line(20,32,778,32);
			$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,540,65,45); // Agregar Logo
			$tm=406-($li_tm/2);
			$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el t�tulo
			$tm1=406-($li_tm1/2);
			$io_pdf->addText($tm1,528,11,$as_periodo); // Agregar el t�tulo
			//$io_pdf->addText(700,570,10,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(700,570,10,$nuevafecha); // Agregar la Fecha
		}

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_banco,$ls_ctaban,$ls_nomtipcta,$ldec_saldoant,$li_totrow,$as_reporte,&$io_pdf)
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
		//    Description: funci�n que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		if($as_reporte=="V") // Formato Vertical
		{
		$la_data=array(array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco),
					   array('title'=>'<b>Tipo Cuenta:</b>','title2'=>$ls_nomtipcta),
					   array('title'=>'<b>Cuenta:</b>   ','title2'=>$ls_ctaban),
					   array('title'=>'<b>Saldo Anterior:</b>','title2'=>$ldec_saldoant));

		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>176, // Orientaci�n de la tabla
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>85),'title2'=>array('justification'=>'left','width'=>195))); // Ancho M�ximo de la tabla
		}
		else
		{
			$la_data=array(array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco,'title3'=>'<b>Cuenta:</b>   ','title4'=>$ls_ctaban, 'title5'=>'<b>Saldo Anterior:</b>   ','title6'=>$ldec_saldoant));
			//		   array('title'=>'<b>Tipo Cuenta:</b>','title2'=>$ls_nomtipcta),
			//		   array('title'=>'<b>Cuenta:</b>   ','title2'=>$ls_ctaban),
			//		   array('title'=>'<b></b>','title2'=>$ldec_saldoant));

				$la_columna=array('title'=>'','title2'=>'','title3'=>'','title4'=>'','title5'=>'','title6'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>396, // Orientaci�n de la tabla
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560,
						 'cols'=>array('title'=>array('justification'=>'right','width'=>85),'title2'=>array('justification'=>'left','width'=>195),'title3'=>array('justification'=>'right','width'=>80),'title4'=>array('justification'=>'left','width'=>165),'title5'=>array('justification'=>'right','width'=>105),'title6'=>array('justification'=>'right','width'=>135))); // Ancho M�ximo de la tabla
		}
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		if($li_totrow>0)
		{
			$la_data=array(array('title'=>'<b>Detalles del Estado de Cuenta</b>  '));
			$la_columna=array('title'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>0, // Sombra entre l�neas
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'width'=>540, // Ancho de la tabla
							 'maxWidth'=>540,
							 'cols'=>array('title'=>array('justification'=>'center','width'=>540))); // Ancho M�ximo de la tabla
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
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',4);//Inserto una linea en blanco
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_totales($ldec_debe,$ldec_haber,$ldec_saldoactual,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('operacion'=>'','cantidad'=>'<b>Totales:</b>','debito'=>$ldec_debe,'credito'=>$ldec_haber),
					   array('operacion'=>'','cantidad'=>'','debito'=>'<b>Saldo Actual:</b>','credito'=>$ldec_saldoactual));							  
		$la_columna=array('operacion'=>'','cantidad'=>'','debito'=>'','credito'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('operacion'=>array('justification'=>'center','width'=>135),'cantidad'=>array('justification'=>'center','width'=>135),
									   'debito'=>array('justification'=>'right','width'=>135),'credito'=>array('justification'=>'right','width'=>135))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("tepuy_scb_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");

	$in			  = new tepuy_include();
	$con		  = $in->uf_conectar();
	$io_sql		  = new class_sql($con);	
	$io_report    = new tepuy_scb_report($con);
	$io_funciones = new class_funciones();				
	$ds_edocta    = new class_datastore();	
	
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_codban      = $_GET["codban"];
	$ls_ctaban      = $_GET["ctaban"];
	$ld_fecdesde    = $_GET["fecdes"];		
	$ld_fechasta    = $_GET["fechas"];
	$ls_orden       = $_GET["orden"];	
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	$ls_reporte = $_GET["reporte"];
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("tepuy_scb_reportbsf.php");
		 $io_report = new tepuy_scb_reportbsf($con);
		 $ls_tipbol = 'Bs.F.';
	   }
	$data=$io_report->uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,$ls_orden,$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber,false,'C','---');
	$ds_edocta->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);

	$li_totrow=$ds_edocta->getRowCount("numdoc");
	if($ls_reporte=="V")
	{
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	}
	else
	{
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
	}
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	if($ls_reporte=="V")
	{
	$io_pdf->ezSetCmMargins(3.5,1,1.5,1.5); // Configuraci�n de los margenes en cent�metros //1=superior 2=derecho 3=izquierdo 4=abajo
	}
	else
	{
	$io_pdf->ezSetCmMargins(3.5,2,2.5,2.5); // Configuraci�n de los margenes en cent�metros //1=superior 2=derecho 3=izquierdo 4=abajo
	}
	uf_print_encabezado_pagina("<b>Estado de Cuenta $ls_tipbol</b>","<b>Del $ld_fecdesde al $ld_fechasta</b>",$ls_reporte,$ld_fechasta,$io_pdf); // Imprimimos el encabezado de la p�gina
	if($ls_reporte=="V") // Formato Vertical
	{
		$io_pdf->ezStartPageNumbers(560,22,10,'','',1); // Insertar el n�mero de p�gina
	}
	else // formato Horizonal
	{
		$io_pdf->ezStartPageNumbers(760,22,10,'','',1); // Insertar el n�mero de p�gina
	}
	$ldec_saldoactual = $ldec_saldoant+$ldec_total_debe-$ldec_total_haber;
	$ldec_saldo       = $ldec_saldoant;
	//$ldec_saldorep	  = $ldec_saldo;
	$ldec_saldoant    = number_format($ldec_saldoant,2,",",".");
	$ls_nomban        = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
	$ls_nomtipcta     = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.codtipcta=t.codtipcta AND c.ctaban='".$ls_ctaban."'","nomtipcta");
	uf_print_cabecera($ls_nomban,$ls_ctaban,$ls_nomtipcta,$ldec_saldoant,$li_totrow,$ls_reporte,$io_pdf); // Imprimimos la cabecera del registro
	$li_pos=0;
	if($li_totrow>0)
	{
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$io_pdf->transaction('start'); // Iniciamos la transacci�n
			$thisPageNum  = $io_pdf->ezPageCount;
			$li_totprenom = 0;
			$ldec_mondeb  = 0;
			$ldec_monhab  = 0;
			$li_totant    = 0;
			$li_pos       = $li_pos+1;
			$ls_numdoc        = $ds_edocta->getValue("numdoc",$li_i);
			$ls_codban        = $ds_edocta->getValue("codban",$li_i);
			$ls_nomban        = $ds_edocta->getValue("nomban",$li_i);
			$ls_ctaban        = $ds_edocta->getValue("ctaban",$li_i);
			$ls_nomproben     = $ds_edocta->getValue("beneficiario",$li_i);		
			$ls_conmov        = $ds_edocta->getValue("conmov",$li_i);
			$ldec_monret      = $ds_edocta->getValue("monret",$li_i);
			$ldec_monto       = $ds_edocta->getValue("monto",$li_i);
			$ls_nomtipcta     = $ds_edocta->getValue("nomtipcta",$li_i);
			//$ls_operacion     = $ds_edocta->getValue("operacion",$li_i);
			$ls_operacion     = $ds_edocta->getValue("codope",$li_i);
			$ld_fecmov        = $io_funciones->uf_convertirfecmostrar($ds_edocta->getValue("fecmov",$li_i));
			$ldec_total       = $ldec_monto-$ldec_monret;
			$ldec_debitos     = $ds_edocta->getValue("debitos",$li_i);
			$ldec_creditos    = $ds_edocta->getValue("creditos",$li_i);
			$ldec_saldo       = $ldec_saldo+$ldec_debitos-$ldec_creditos;
			$ldec_saldorep	  = $ldec_saldo;
			$ldec_saldorep      = number_format($ldec_saldorep,2,",",".");
			$ldec_mondeb      = number_format($ldec_debitos,2,",",".");
			$ldec_monhab      = number_format($ldec_creditos,2,",",".");
			//$la_data[$li_pos] = array('fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'beneficiario'=>$ls_nomproben,'debito'=>$ldec_mondeb,'credito'=>$ldec_monhab);
			$la_data[$li_pos] = array('fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'beneficiario'=>$ls_nomproben,'concepto'=>$ls_conmov,'debito'=>$ldec_monhab,'credito'=>$ldec_mondeb,'saldo'=>$ldec_saldorep);
			$la_columna=array('fecha'=>'<b>Fecha</b>  ','operacion'=>'<b>Op. </b>  ','numdoc'=>'<b>N� Documento</b>  ','beneficiario'=>'<b>Beneficiario </b>  ','concepto'=>'<b>Concepto de Pago </b>','debito'=>'<b>Debitos </b>  ','credito'=>'<b>Creditos </b>  ','saldo'=>'<b>Saldo </b>  ');
			if($ls_reporte=="V") // formato Vertical
			{
					$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
							 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'width'=>560, // Ancho de la tabla
							 'maxWidth'=>570,
							 'fontSize'=>7,
							 'cols'=>array('fecha'=>array('justification'=>'center','width'=>48),'operacion'=>array('justification'=>'center','width'=>22),
										   'numdoc'=>array('justification'=>'center','width'=>70),'beneficiario'=>array('justification'=>'left','width'=>106),'concepto'=>array('justification'=>'left','width'=>106),
										   'debito'=>array('justification'=>'right','widht'=>82),'credito'=>array('justification'=>'right','width'=>82),
										'saldo'=>array('justification'=>'right','width'=>82))); // Ancho M�ximo de la tabla
			}
			else  // dormato Horizontal
			{
					$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
							 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'width'=>760, // Ancho de la tabla
							 'maxWidth'=>770,
							 'fontSize'=>6.8,
							 'cols'=>array('fecha'=>array('justification'=>'center','width'=>48),'operacion'=>array('justification'=>'center','width'=>22),
										   'numdoc'=>array('justification'=>'center','width'=>70),'beneficiario'=>array('justification'=>'left','width'=>154),'concepto'=>array('justification'=>'left','width'=>256),
										   'debito'=>array('justification'=>'right','widht'=>82),'credito'=>array('justification'=>'right','width'=>82),
										'saldo'=>array('justification'=>'right','width'=>82))); // Ancho M�ximo de la tabla
			}
		}
	
		uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		uf_print_totales(number_format($ldec_total_haber,2,",","."),number_format($ldec_total_debe,2,",","."),	number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
	 }
	$io_pdf->transaction('commit');
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
?> 
