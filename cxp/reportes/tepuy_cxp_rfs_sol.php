<?PHP
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
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ORGANISMO             : FUNDACITE FALCON
	// FECHA DE REALIZADO    : 18/01/2007
	// FECHA DE MODIFICACION : 18/04/2007
	// MODIFICACION          : 
	// PROBADA MODIFICACION  : 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$hidnumero,$ls_fecsol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�ulo del Reporte
		//	    		   
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci� que imprime los encabezados por p�ina
		//	   Creado Por: Ing. Selena Lucena
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->addText(510,760,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(125,710,460,40);
		$io_pdf->line(430,730,585,730);		
		$io_pdf->line(430,750,430,710);		
		$io_pdf->addText(443,732,11,"Nro.:     ".$hidnumero); // Nmero de Orden de compra
		$io_pdf->addText(443,712,11,"Fecha:  ".$ls_fecsol); // Agregar la Fecha
		$io_pdf->addText(230,725,13,"<b>".$as_titulo."</b>"); // Agregar el t�ulo

        $io_pdf->Rectangle(14,40,581,80);
		$io_pdf->line(14,107,594,107);		
		$io_pdf->line(14,94,594,94);		
		$io_pdf->line(150,40,150,120);		
		$io_pdf->line(290,40,290,120);		
		$io_pdf->line(440,40,440,120);				
		$io_pdf->addText(20,96,7,"FECHA :            /               /"); // Agregar el t�ulo
		$io_pdf->addText(155,96,7,"FECHA :            /               /"); // Agregar el t�ulo		
		$io_pdf->addText(295,96,7,"FECHA :            /               /"); // Agregar el t�ulo
		$io_pdf->addText(446,96,7,"FECHA :            /               /"); // Agregar el t�ulo

		$io_pdf->addText(20,85,7,"FIRMA:"); // Agregar el t�ulo
		$io_pdf->addText(155,85,7,"FIRMA:"); // Agregar el t�ulo
		$io_pdf->addText(295,85,7,"FIRMA:"); // Agregar el t�ulo
		$io_pdf->addText(445,85,7,"FIRMA:"); // Agregar el t�ulo

		
		$io_pdf->addText(50,110,7,"ADMINISTRACION"); // Agregar el t�ulo
		$io_pdf->addText(190,110,7,"CONTABILIDAD"); // Agregar el t�ulo
		$io_pdf->addText(340,110,7,"PRESUPUESTO"); // Agregar el t�ulo
		$io_pdf->addText(495,110,7,"DESPACHO"); // Agregar el t�ulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numsol,$as_fecsol,$as_consol,$as_observacion,$as_codfuefin,
	                           $as_denfuefin,$as_estatus,$as_codpro,$as_tipo,$as_nombre,$as_direccion,&$io_pdf)							   
				                  
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci� que imprime la cabecera de cada p�ina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$la_data=array(array('name'=>'<b>Proveedor: </b>'.$as_nombre),
		//			   array('name'=>'<b>Direccion: </b>'.$as_direccion),
        $la_data=array(array('name'=>'<b>Rif: </b>'.$as_direccion),
					   array('name'=>'<b>Beneficiario: </b>'.$as_nombre),
					   array('name'=>'<b>Estatus: </b>'.$as_estatus),
		               array('name'=>'<b>Fuente de Financiamiento: </b>'.$as_denfuefin),
					   array('name'=>'<b>Concepto: </b>'.$as_consol),
					   array('name'=>'<b>Observacion: </b>'.$as_observacion)
					  );			
					
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'xPos'=>310, // Orientaci� de la tabla
						 'width'=>560, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
	}
	//--------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($as_tipoencabenzadosol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci� que imprime la cabecera de cada p�ina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''.$as_tipoencabenzadosol)
					  );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientaci� de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>560))
						 ); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepciones($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_recepciones
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				

  	    $la_datasercon= array(array('codigo'=>"<b>Nro del DOCUMENTO</b>",'denominacion'=>"<b>FECHA DEL DOCUMENTO</b>",'feccom'=>"<b>FECHA DEL COMPROMISO</b>",'submonto'=>"<b>MONTO</b>",'monded'=>"<b>DEDUCCION</b>",'moncar'=>"<b>IMPUESTO</b>",'monto'=>"<b>MONTO</b>"));
		$la_columna=array('codigo'=>'<b>Codigo</b>',
  						  'denominacion'=>'<b>Denominacion</b>',
                          'feccom'=>'<b>Fecha del Compromiso</b>',
						  'submonto'=>'<b>Monto</b>',
                          'monded'=>'<b>Deduccion</b>',
                          'moncar'=>'<b>Impuesto</b>',
                          'monto'=>'<b>Monto</b>'
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize' => 10,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>2, // Sombra entre l�eas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre l�eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>100),
									   'denominacion'=>array('justification'=>'center','width'=>70),
                                       'feccom'=>array('justification'=>'center','width'=>70),
									   'submonto'=>array('justification'=>'center','width'=>80),
									   'monded'=>array('justification'=>'center','width'=>80),
									   'moncar'=>array('justification'=>'center','width'=>80),
                                       'monto'=>array('justification'=>'center','width'=>80)
									  )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);

									
		$la_columna=array('codigo'=>'<b>Codigo</b>',
						  'denominacion'=>'<b>Denominacion</b>',		
                          'feccom'=>'<b>Fecha del Compromiso</b>',			
                          'submonto'=>'<b>Monto</b>',	  						  
                          'monded'=>'<b>Deduccion</b>',
                          'moncar'=>'<b>Impuesto</b>',
                          'monto'=>'<b>Monto</b>'						  
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>8, // Tama� de Letras
						 'titleFontSize' =>9,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>1, // Sombra entre l�eas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>100),      // Justificaci� y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>70),  // Justificaci� y ancho de la columna
                                       'feccom'=>array('justification'=>'center','width'=>70),  
						 			   'submonto'=>array('justification'=>'right','width'=>80),
                                       'monded'=>array('justification'=>'right','width'=>80),
									   'moncar'=>array('justification'=>'right','width'=>80),        
                                       'monto'=>array('justification'=>'right','width'=>80)
									   )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallespg($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
	    $la_datasercon= array(array('estpro'=>"<b>ESTRUCTURA PRESUPUESTARIA</b>",'spg_cuenta'=>"<b>CUENTA PRESUPUESTARIA</b>",'denominacion'=>"<b>DENOMINACION</b>",'monto'=>"<b>MONTO </b>"));
		$la_columna=array('estpro'=>'<b>Estructura Presupuestaria</b>',
                          'spg_cuenta'=>'<b>Cuenta Presupuestaria</b>',
  						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto</b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize' => 10,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>2, // Sombra entre l�eas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre l�eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>162),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>84),
									   'denominacion'=>array('justification'=>'center','width'=>234),
									   'monto'=>array('justification'=>'center','width'=>80)
									  )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);

		$la_columna=array('estpro'=>'<b>Cuenta </b>',
                          'spg_cuenta'=>'<b>Cuenta </b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto </b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize' => 9,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>162),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>84),
									   'denominacion'=>array('justification'=>'left','width'=>234),
									   'monto'=>array('justification'=>'right','width'=>80)
									  )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
    //--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle_cuentas_scg($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		
		$la_datasercon= array(array('cuenta'=>"<b>CUENTA CONTABLE</b>",'denominacion'=>"<b>DENOMINACION</b>",'debe'=>"<b>DEBE </b>",'haber'=>"<b>HABER </b>"));
		$la_columna=array('cuenta'=>'<b>Cuenta Presupuestaria</b>',
  						  'denominacion'=>'<b>Denominacion�</b>',
						  'debe'=>'<b>Debe</b>',
						  'haber'=>'<b>Haber</b>'			
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize' => 10,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>2, // Sombra entre l�eas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre l�eas
						 'width'=>557, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100),
									   'denominacion'=>array('justification'=>'center','width'=>300),
									   'debe'=>array('justification'=>'center','width'=>80),   						 			  
									   'haber'=>array('justification'=>'center','width'=>80)
									  )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);


        $la_columna=array('cuenta'=>'<b>Cuenta</b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'debe'=>'<b>Debe</b>',
						  'haber'=>'<b>Haber</b>'	
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize'=>9,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>557, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho M�imo de la tabla
						 'xPos'=>310, // Orientaci� de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100),
									   'denominacion'=>array('justification'=>'left','width'=>300),
									   'debe'=>array('justification'=>'right','width'=>80),   						 			  
									   'haber'=>array('justification'=>'right','width'=>80)
									  )
						); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_debe,$ad_haber,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci� que imprime la cabecera de cada p�ina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>________________     _______________</b>'), 		              
   		               array('name'=>'       '.$ad_debe.'              '.$ad_haber.'')
					  );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>930, // Orientaci� de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_carded($la_datacar,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_recepciones
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
												
        $la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9,  // Tama� de Letras
						 'showLines'=>0,    // Mostrar L�eas
						 'shaded'=>0,       // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>900,      // Ancho de la tabla						 
						 'maxWidth'=>900);  // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		//-----------------------------------------------------------------------------------------------------------------																												
		$la_columnacar=array('denominacion'=>'<b>Denominacion</b>',						  
						     'monobjret'=>'<b>Monto Objeto Retencion y/o Base Imponible</b>',
							 'objret'=>'<b>Retencion y/o Impuesto</b>'						  						  
						    );
						 
		$la_configcar=array('showHeadings'=>1, // Mostrar encabezados
						    'fontSize' =>8, // Tama� de Letras
						    'titleFontSize' =>9,  // Tama� de Letras de los t�ulos
						    'showLines'=>1, // Mostrar L�eas
						    'shaded'=>0, // Sombra entre l�eas
						    'width'=>500, // Ancho de la tabla
						    'maxWidth'=>500, // Ancho M�imo de la tabla
						    'xPos'=>395, // Orientaci� de la tabla
						    'cols'=>array('denominacion'=>array('justification'=>'center','width'=>180),      // Justificaci� y ancho de la columna
						 			      'monobjret'=>array('justification'=>'center','width'=>100),  // Justificaci� y ancho de la columna
						 			      'objret'=>array('justification'=>'right','width'=>100)         // Justificaci� y ancho de la columna
									     )
						   ); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_datacar,$la_columnacar,'CARGOS Y DEDUCCIONES',$la_configcar);			
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pie_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf)
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
		
		$la_data=array(array('1'=>' ','2'=>' ','monto'=>'','4'=>' '),array('1'=>' ','2'=>' ','monto'=>'','4'=>' '));
		$la_columna=array('1'=>' ','2'=>' ','monto'=>'','4'=>' ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' =>8, // Tamaño de Letras
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('1'=>array('justification'=>'center','width'=>100),'2'=>array('justification'=>'center','width'=>190),
						 'monto'=>array('justification'=>'center','width'=>150),'4'=>array('justification'=>'center','width'=>50))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('data'=>"<b>".$ls_monto."</b>")
                       );
		$la_columna=array('data'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' =>8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600,
						 'cols'=>array('data'=>array('justification'=>'center','width'=>550)
                                      )
                        ); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");	
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in          =new tepuy_include();
	$con=$in->uf_conectar();
	$io_sql      =new class_sql($con);	
	$io_report   =new tepuy_cxp_class_report($con);
	$io_funciones=new class_funciones();			
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido   =false;
	$lb_validobie=false;
	$lb_validoser=false;
	//----------------------------------------------------  Par�etros del encabezado    -----------------------------------------------
	$ls_titulo="ORDEN DE PAGO";	
	//--------------------------------------------------  Par�etros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$hidnumero=$_GET["hidnumero"];
	//--------------------------------------------------------------------------------------------------------------------------------
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares Fuertes");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
	$lb_valido=$io_report->uf_select_solicitud_imprimir($ls_codemp,$hidnumero); 
		
	if($lb_valido==false) // Existe algn error �no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else 
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,4,3,3); // Configuraci� de los margenes en cent�etros		
		$io_pdf->ezStartPageNumbers(585,28,9,'','',1);
		$li_totrow=$io_report->ds_solicitud->getRowCount("numsol");	
		
		for($i=1;$i<=$li_totrow;$i++)
		{				 
				 $ls_pais      = "";
				 $ls_estado    = "";
				 $ls_parroquia = "";
				 $ls_municipio = "";
			  	 $ls_denfuefin = "";
                 $ls_moneda    = "";				 
				 		
				 $ls_numsol      = $io_report->ds_solicitud->data["numsol"][$i];					     		   				 
				 $ls_fecha       = $io_report->ds_solicitud->data["fecemisol"][$i];
				 $ls_fecsol      = substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);										 	 
				 $ls_estus       = "";
				 $ls_estus       = $io_report->ds_solicitud->data["estprosol"][$i];
				 $ls_consol      = $io_report->ds_solicitud->data["consol"][$i];			 
				 $ld_monto       = $io_report->ds_solicitud->data["monsol"][$i];				 			  
				 $ls_observacion = $io_report->ds_solicitud->data["obssol"][$i];
				 	
				 $ls_codfuefin   = $io_report->ds_solicitud->data["codfuefin"][$i];
				 $ls_denfuefin   = "";
				 
				 if($ls_codfuefin!="--")
				 {
				    $ls_denfuefin   = $io_report->uf_select_denfuefin($ls_codfuefin);				
				 }			
				 else
				 {
				    $ls_codfuefin="";
				 }			
				 $ls_tipdes      = $io_report->ds_solicitud->data["tipproben"][$i];
				 $ld_monto       = number_format($ld_monto,2,",",".");
				 if($i==1)
				 {
					uf_print_encabezado_pagina($ls_titulo,$hidnumero,$ls_fecsol,$io_pdf); 
				 }		
				 if($ls_estus=="E"){
					  $ls_estatus="Emitida";
				 }	
				 if($ls_estus=="C"){
					  $ls_estatus="Contabilizada";
				 }	
				 if($ls_estus=="A"){
					  $ls_estatus="Anulada";
				 }	
				 if($ls_estus=="S"){
					  $ls_estatus="Programacion de Pago";
				 }	
				 if($ls_estus=="P"){
					  $ls_estatus="Pagada";
				 }				 
				 
				 if($ls_tipdes=="P"){
				   $ls_proveedor=$io_report->ds_solicitud->data["cod_pro"][$i];
				 }
				 else
				 {
					if($ls_tipdes=="B"){
					   $ls_proveedor=$io_report->ds_solicitud->data["ced_bene"][$i];
					}              
				 }
	
				 if($ls_tipdes=="P"){           
					$ls_codpro=$io_report->ds_solicitud->data["cod_pro"][$i];			 
					$ls_tipo="Proveedor";
					$ls_nombre=$io_report->uf_select_nombre_pro($ls_codemp,$ls_codpro);	
					//$ls_dirpro=$io_report->uf_select_dirpro($ls_codemp,$ls_codpro);
			$ls_dirpro=$io_report->uf_select_rifprove($ls_codemp,$ls_codpro);				
				 }
				 else
				 {
					 if($ls_tipdes=="B"){
						$ls_codpro=$io_report->ds_solicitud->data["ced_bene"][$i];			 
						$ls_tipo="Beneficiario";
						$ls_nombre=$io_report->uf_select_nombre_bene($ls_codemp,$ls_codpro);
					//	$ls_dirpro=$io_report->uf_select_dirben($ls_codemp,$ls_codpro);
                        $ls_dirpro=$io_report->uf_select_rifbene($ls_codemp,$ls_codpro);
					 }
					 else
					 {
						 $ls_nombre="";                 
					 }
				 }								 
			 
				 uf_print_cabecera($ls_numsol,$ls_fecsol,$ls_consol,$ls_observacion,
				                   $ls_codfuefin,$ls_denfuefin,$ls_estatus,$ls_codpro,$ls_tipo,
								   $ls_nombre,$ls_dirpro,$io_pdf);
								
				 if($lb_valido)
				 {				        
				        $lb_validorec=$io_report->uf_select_rec_doc_solicitud($ls_codemp,$hidnumero);	
						if($lb_validorec)
						{										
						            $li_totrowdet=$io_report->ds_sol_dt->getRowCount("numsol");
									for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
									{
										  $ls_codigo = $io_report->ds_sol_dt->data["numrecdoc"][$li_s];				  
 			                              $ls_fecrec = $io_report->ds_sol_dt->data["fecemidoc"][$li_s];	
			 				              $ls_fecrec = substr($ls_fecrec,8,2)."/".substr($ls_fecrec,5,2)."/".substr($ls_fecrec,0,4);										 	 
				
 			                              $ld_montos = $io_report->ds_sol_dt->data["montotdoc"][$li_s];		
										  $ld_monded = $io_report->ds_sol_dt->data["mondeddoc"][$li_s];		
                                          $ld_moncar = $io_report->ds_sol_dt->data["moncardoc"][$li_s];		 
  									      $ldec_submonto = ($ld_montos+$ld_monded)-$ld_moncar;
										  $ldec_submonto = number_format($ldec_submonto,2,",",".");	
										  $ld_montos     = number_format($ld_montos,2,",",".");	
										  $ld_monded     = number_format($ld_monded,2,",",".");	
										  $ld_moncar     = number_format($ld_moncar,2,",",".");	
										  $la_data[$li_s]= array('codigo'=>$ls_codigo,'denominacion'=>$ls_fecrec,'feccom'=>$ls_fecrec,
										                         'submonto'=>$ldec_submonto,'monded'=>$ld_monded,'moncar'=>$ld_moncar,
                                                                 'monto'=>$ld_montos);
									}				
 	                                   $ls_tipoencabenzadosol = "RECEPCIONES DE DOCUMENTOS";
    						        uf_print_cabecera_detalle($ls_tipoencabenzadosol,$io_pdf);									
									uf_print_detalle_recepciones($la_data,$io_pdf); 
						}					
                             //-------------------------------Cuentas SPG-------------------------------------- 										                       									
							 $io_report->ds_spg_dt->reset_ds();
							 $lb_validospg=$io_report->uf_select_spg($ls_codemp,$hidnumero);
							 if ($lb_validospg)
							 {
								   $li_totdet=$io_report->ds_spg_dt->getRowCount("codestpro");								     
								   for ($b=1;$b<=$li_totdet;$b++)
								   {						
                                          $ls_codestpro      = $io_report->ds_spg_dt->data["codestpro"][$b];				  													  
										  $ls_cuentaspg      = $io_report->ds_spg_dt->data["spg_cuenta"][$b];
										  $ls_denominacionspg= $io_report->uf_select_denominacionspg($ls_codemp,$ls_cuentaspg);
										  $ls_montocuentaspg = $io_report->ds_spg_dt->data["montospg"][$b];
										  $ls_montocuentaspg = number_format($ls_montocuentaspg,2,",",".");	
										  $la_data1[$b]= array('estpro'=>$ls_codestpro,'spg_cuenta'=>$ls_cuentaspg,
															  'denominacion'=>$ls_denominacionspg,'monto'=>$ls_montocuentaspg);
									}
                                   $ls_tipoencabenzadosol = "CUENTAS PRESUPUESTARIAS";
    						       uf_print_cabecera_detalle($ls_tipoencabenzadosol,$io_pdf);									
								   uf_print_detallespg($la_data1,$io_pdf); 
						     }							  														
                               //-------------------------------Cuentas SCG------------------------------------ 										
                               $ld_debe =0;
							   $ld_haber=0;
							   $io_report->ds_scg_dt->reset_ds();
							   unset($la_data);
							   $lb_validospg=$io_report->uf_select_scg($ls_codemp,$hidnumero);
							   if ($lb_validospg)
							   {
								   $io_report->ds_scg_dt->group_by(array('0'=>'sc_cuenta','1'=>'columna'),array('0'=>'montoscg'),'montoscg');
								   $li_totdet=$io_report->ds_scg_dt->getRowCount("numsol");		  
								   for ($w=1;$w<=$li_totdet;$w++)
								   {											 		     											  							  			 
									  $ls_montocuentascgdeb="";
									  $ls_montocuentascghab="";
									  $ls_denominacionscg="";
									  $ls_cuentascg      = $io_report->ds_scg_dt->data["sc_cuenta"][$w];
									  $ls_denominacionscg= $io_report->uf_select_denominacionscg($ls_codemp,$ls_cuentascg);											  
									  $ls_columna        = $io_report->ds_scg_dt->data["columna"][$w];
									  
									  if($ls_columna=="D")
									  {
											$ls_montocuentascgdeb= $io_report->ds_scg_dt->data["montoscg"][$w];									
											$ld_debe = $ld_debe + $ls_montocuentascgdeb;							
									  }
									  else
									  {
											 $ls_montocuentascghab= $io_report->ds_scg_dt->data["montoscg"][$w];					
											 $ld_haber = $ld_haber + $ls_montocuentascghab;
									  }
									  if($ls_montocuentascgdeb!="")
									  {
										 $ls_montocuentascgdeb = number_format($ls_montocuentascgdeb,2,",",".");	
									  }
									  
									  if($ls_montocuentascghab!="")
									  {
										   $ls_montocuentascghab = number_format($ls_montocuentascghab,2,",",".");	
									  }
																				  
									  $la_data[$w]= array('cuenta'=>$ls_cuentascg,
														  'denominacion'=>$ls_denominacionscg,									                    																  
														  'debe'=>$ls_montocuentascgdeb,'haber'=>$ls_montocuentascghab);
								  }
                           $ls_tipoencabenzadosol = "CUENTAS CONTABLES";
				// modificado para que no imprima cuentas contables
					//	   uf_print_cabecera_detalle($ls_tipoencabenzadosol,$io_pdf);									
					//	   uf_print_detalle_cuentas_scg($la_data,$io_pdf);								    						    
						}												
						//-------------------------------CARGOS-------------------------------------- 										                       									
						$lb_validocar=$io_report->uf_select_sol_cargos($ls_codemp,$hidnumero);
						 
						if ($lb_validocar)
						{
							   $li_totdet=$io_report->ds_car_dt->getRowCount("numsol");								     
							
                               for ($b=1;$b<=$li_totdet;$b++)
							   {										  													  
									  $ls_codcar    = $io_report->ds_car_dt->data["codcar"][$b]; 
									  $ls_dencar    = $io_report->uf_select_dencar($ls_codemp,$ls_codcar);									  
									  $ld_monobjret = $io_report->ds_car_dt->data["monobjretcar"][$b];
									  $ld_objret    = $io_report->ds_car_dt->data["objretcar"][$b];
									  
									  $ld_monobjret = number_format($ld_monobjret,2,",",".");	
									  $ld_objret    = number_format($ld_objret,2,",",".");	
								
									  $la_datacar[$b]= array('codigo'=>$ls_codcar,'denominacion'=>$ls_dencar,
									                         'monobjret'=>$ld_monobjret,'objret'=>$ld_objret);
								}							  							   
						}													
						//-------------------------------DEDUCCIONES--------------------------------------
						$li_totdet=0;
						$lb_validoded=$io_report->uf_select_sol_deducciones($ls_codemp,$hidnumero);
						if ($lb_validoded)
						{
							   $li_totdet=$io_report->ds_ded_dt->getRowCount("numsol");								     
							   for ($c=1;$c<=$li_totdet;$c++)
							   {										
									  $ls_codded    = $io_report->ds_ded_dt->data["codded"][$c];									  
									  $ls_dended    = $io_report->uf_select_dended($ls_codemp,$ls_codded);																  									
									  $ld_monobjded = $io_report->ds_ded_dt->data["monobjretded"][$c];
									  $ld_objretded = $io_report->ds_ded_dt->data["objretded"][$c];
									  
									  $ld_monobjded = number_format($ld_monobjded,2,",",".");	
									  $ld_objretded = number_format($ld_objretded,2,",",".");	
									  
									  $la_datacar[$c]= array('codigo'=>$ls_codded,'denominacion'=>$ls_dended,
									                         'monobjret'=>$ld_monobjded,'objret'=>$ld_objretded);
								}							  							   
						}																					
						$ld_debe  = number_format($ld_debe,2,",",".");				
						$ld_haber = number_format($ld_haber,2,",",".");			
									
						uf_print_pie_cabecera($ld_debe,$ld_haber,&$io_pdf);														
				}
				if(!empty($la_datacar))
				{                  								
 		           uf_print_carded($la_datacar,&$io_pdf); 
 			    }
		//------------------------------------------------------------------
		//Asigno el monto a la clase numero-letras para la conversion.
		$ld_monto=str_replace('.','',$ld_monto);
		$ld_monto=str_replace(',','.',$ld_monto);
		$numalet->setNumero($ld_monto);
		//Obtengo el texto del monto enviado.
		$ls_monto= $numalet->letra();
		uf_print_encabezado_pie_pagina(number_format($ld_monto,2,",","."),$ls_nombre,$ls_monto,"Barquisimeto  ".date("d/m/Y"),$io_pdf); // Imprimimos el encabezado de la página
		//------------------------------------------------------------------
		unset($la_datacar);
		unset($la_dataded);
		}		
		$io_report->ds_solicitud->resetds("numsol");
		if($lb_valido) // Si no ocurrio ningn error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi� de los nmeros de p�ina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algn error
		{
			print("<script language=JavaScript>");
			print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print("close();");
			print("</script>");		
		}
	unset($io_pdf);
	}	
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
?> 
