<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_coduniadm,$as_denuniadm, $as_codfuefin,
	                                   $as_denfuefin,$as_codigo,$as_nombre,$as_conordcom,$as_rifpro,$as_diaplacom,$as_dirpro,
									   $ls_forpagcom,$as_telfpro,$as_lugcom,$as_fechent,$as_estlugcom,$as_codpro, $as_coduniadm,
									   $as_denuniadm,$as_lugentnomdep, $as_concom,$ls_monant,$as_obscom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);		
        $io_pdf->Rectangle(460,670,125,44);
		$ls_rifemp= $_SESSION["la_empresa"]["rifemp"];
		$ls_email= $_SESSION["la_empresa"]["email"];
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,719,50,50); // Agregar Logo		
		$io_pdf->addText(40,710,7,'<b>RIF. <b>'.$ls_rifemp); // Agregar el t�tulo
		$io_pdf->addText(180,750,9,'<b>REPUBLICA BOLIVARIANA DE VENEZUELA<b>'); // Agregar el t�tulo
		$io_pdf->addText(120,735,9,'<b>MINISTERIO DEL PODER POPULAR PARA LA AGRICULTURA Y TIERRAS<b>'); // Agregar el t�tulo
		$io_pdf->addText(180,720,9,'<b>CVA LEANDER CARNES Y PESCADOS S.A<b>'); // Agregar el t�tulo
		$io_pdf->addText(120,705,9,'<b>Edif. CVA. Av. libertador entre calle 38 y 38, Zona Ind. I Barquisimeto, Edo. Lara<b>'); // Agregar el t�tulo
		$io_pdf->addText(150,690,9,'<b>Telefonos:0251-2371078 - 6110063 <b>'.'<b>'.$ls_email.'</b>'); // Agregar el t�tulo
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_cvaleander.jpg',525,719,50,50); // Agregar Logo
		if($as_estcondat=="B") 
        {
             $ls_titulo="ORDEN DE COMPRA";	
			 $ls_titulo_grid="Bienes";
        }
        else
        {
             $ls_titulo="ORDEN DE SERVICIO";
			 $ls_titulo_grid="Servicios";
        }
		
		$li_tm=$io_pdf->getTextWidth(14,$ls_titulo);
		$tm=250-($li_tm/2);
		$io_pdf->addText($tm,670,16,$ls_titulo); // Agregar el t�tulo
		$io_pdf->addText(470,700,9," <b>No. </b>".$as_numordcom); // Agregar el t�tulo
		$io_pdf->addText(470,680,9,"<b> Fecha </b>".$ad_fecordcom); // Agregar el t�tulo		
		// cuadro inferior
        $io_pdf->Rectangle(20,60,570,70); 
		$io_pdf->line(20,112,590,112);	//HORIZONTAL
		$io_pdf->addText(50,122,7,"COMPRAS"); // Agregar el t�tulo	
		$io_pdf->addText(21,115,5,"ELABORADO POR"); // Agregar el t�tulo		
		$io_pdf->line(135,60,135,130);	//VERTICAL	
		$io_pdf->addText(150,122,7,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->addText(136,115,5,"PROCESADO POR"); // Agregar el t�tulo
		$io_pdf->line(230,60,230,130);	//VERTICAL		
		$io_pdf->addText(240,122,7,"ADMINISTRAC. Y FINANZAS"); // Agregar el t�tulo
		$io_pdf->addText(230,115,5,"REVISADO POR:"); // Agregar el t�tulo
		$io_pdf->line(350,60,350,130);	//VERTICAL		
		$io_pdf->addText(370,122,7,"PRESIDENCIA"); // Agregar el t�tulo
		$io_pdf->addText(360,115,5,"AUTORIZADO POR:"); // Agregar el t�tulo
		$io_pdf->line(450,60,450,130);	//VERTICAL		
		$io_pdf->addText(500,122,7,"PROVEEDOR"); // Agregar el t�tulo
		$io_pdf->addText(452,115,5,"FIRMA, CEDULA, SELLO Y FECHA:"); // Agregar el t�tulo
		$io_pdf->ezSetY(670);
////////////////////////////////////////////////////////////tabla1///////////////////////////////////////////////////////////////
		$la_data[1]=array('columna1'=>'<b>Proveedor: </b>'.$as_codpro.' - '.$as_nombre. '<b> Rif del Proveedor :</b> '.$as_rifpro);
		$la_data[2]=array('columna1'=>'<b>Direccion: </b>'.$as_dirpro.'<b>  Tel�fono: </b> '.$as_telfpro);	
		$la_data[3]=array('columna1'=>'<b>Unidad Ejecutora: </b>'.$as_coduniadm.' '.'<b>'.$as_denuniadm.'</b>');	
		$la_data[4]=array('columna1'=>'<b>Concepto: </b>'.$as_conordcom);	
		$la_data[5]=array('columna1'=>'<b>Observaci�n: </b>'.$as_obscom);		
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);			
///////////////////////////////////////////////tabla4///////////////////////////////////////////////////////////////////////////////
		
		$la_data[1]=array('columna1'=>'<b>Dependencia:</b>',                  
		                  'columna2'=>'<b>Direcci�n de Entrega:</b>',
						  'columna3'=>'<b>Plazo de entrega:</b>');
		$la_data[2]=array('columna1'=>$as_lugentnomdep, 
						  'columna2'=>$as_lugcom,                  
		                  'columna3'=>$as_fechent);				
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>330),
									   'columna3'=>array('justification'=>'left','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $la_data_enc[1]=array('columna1'=>'<b>Condici�n de Entrega:</b>',                  
		                      'columna2'=>'<b>Forma de Pago:</b>',
						      'columna3'=>'<b>Anticipo de Pago:</b>');
		$la_data_enc[2]=array('columna1'=>$as_concom, 
						      'columna2'=>$ls_forpagcom,                  
		                      'columna3'=>$ls_monant);				
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>330),
									   'columna3'=>array('justification'=>'left','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_enc,$la_columna,'',$la_config);
		unset($la_data_enc);
		unset($la_columna);
		unset($la_config);	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_print_requision ($as_numsol,$as_denuniadm_req, &$io_pdf)
 {
	    $la_datatit[1]=array('columna1'=>'<b>REQUISICION</b>',
		                     'columna2'=>'<b>DEPARTAMENTO</b>');
		$la_columnas=array('columna1'=>'', 'columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>120),
						               'columna2'=>array('justification'=>'center','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('columna1'=>$as_numsol,
		                  'columna2'=>$as_denuniadm_req);
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
 }
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo_grid="Bienes";
		}
		else
		{
			$ls_titulo_grid="Servicios";
		}
		
		$io_pdf->ezSetDy(-5);
		$la_datatitulo[1]=array('columna1'=>'<b> Detalle de '.$ls_titulo_grid.'</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas						 
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);
		unset($la_datatitulo);
		unset($la_columnas);
		unset($la_config);
		
		$la_datatit[1]=array(  'columna2'=>'<b>C�digo</b>',
							   'columna3'=>'<b>Denominac�n</b>',
							   'columna4'=>'<b>Presentaci�n</b>',
						   	   'columna5'=>'<b>Cant.</b>',						   
						  	   'columna6'=>'<b>Precio </b>',
							   'columna7'=>'<b>Cargos </b>',
						   	   'columna8'=>'<b>Sub-Total </b>');
		$la_columnas=array('columna2'=>'', 'columna3'=>'','columna4'=>'','columna5'=>'',
		                   'columna6'=>'','columna7'=>'','columna8'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna2'=>array('justification'=>'center','width'=>115),
									   'columna3'=>array('justification'=>'left','width'=>127),
									   'columna4'=>array('justification'=>'center','width'=>60),
									   'columna5'=>array('justification'=>'center','width'=>35),
									   'columna6'=>array('justification'=>'center','width'=>76),
									   'columna7'=>array('justification'=>'center','width'=>78),
									   'columna8'=>array('justification'=>'center','width'=>78))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('codigo'=>'',
						   'denominacion'=>'',
						   'presentacion'=>'',
						   'cantidad'=>'',						   
						   'cosuni'=>'',
						   'cargo'=>'',
						   'baseimp'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>127), // Justificaci�n y ancho de la columna
									   'presentacion'=>array('justification'=>'center','width'=>60),
						 			   'cantidad'=>array('justification'=>'right','width'=>35), // Justificaci�n y ancho de la columna
						 			   'cosuni'=>array('justification'=>'right','width'=>76), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>78),
									   'baseimp'=>array('justification'=>'right','width'=>78))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b>Detalle de Presupuesto</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas						 
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		//$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array( 'columna1'=>'<b>Detall Presupuestario</b>',
		                       'columna2'=>'<b>Cuenta</b>',
							   'columna3'=>'<b>Denominaci�n</b>',
							   'columna4'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_columnas=array('columna1'=>'',
						   'columna2'=>'',
						   'columna3'=>'',
						   'columna4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'columna3'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
									   'columna4'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacio'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot ---> Subtotal del articulo
		//	    		   li_totcar -->  Total cargos
		//	    		   li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$io_pdf->ezSetDy(-2);			
		$la_data[1]=array('titulo'=>'<b>Sub Total: </b>','contenido'=>'<b>'.$li_subtot.'</b>');
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);		
			
		$la_data[1]=array('titulo'=>'<b>Cargo: </b>','contenido'=>'<b>'.$li_totcar.'</b>');
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Total: </b>','contenido'=>'<b>'.$li_montot.'</b>');
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data_enc[1]=array('columna1'=>'<b>Fianza de Fiel Cumpliento:</b>',                  
		                      'columna2'=>'<b>Fianza de Anticipo:</b>',
						      'columna3'=>'<b>Clausula Penal:</b>');	
		$la_data_enc[2]=array('columna1'=>'',                  
		                      'columna2'=>'',
						      'columna3'=>'');   
		$la_data_enc[3]=array('columna1'=>'',                  
		                      'columna2'=>'',
						      'columna3'=>'');   
		$la_data_enc[3]=array('columna1'=>'',                  
		                      'columna2'=>'',
						      'columna3'=>'');    			
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>155), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>220),
									   'columna3'=>array('justification'=>'left','width'=>195))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_enc,$la_columna,'',$la_config);
		unset($la_data_enc);
		unset($la_columna);
		unset($la_config);	
		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera_total($li_montot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot ---> Subtotal del articulo
		//	    		   li_totcar -->  Total cargos
		//	    		   li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);	
		$la_data[1]=array('titulo'=>'<b>Total</b>','contenido'=>'<b>'.$li_montot.'</b>');
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_monto_en_letras ($ls_monlet,$as_estlugcom,$as_tasa,$as_monto_d,
	                             $den_pais,$as_denestado,$as_denmuni,$as_denparro,$as_denmon,&$io_pdf)
	{
	   $io_pdf->ezSetDy(-5);
	   if ($as_estlugcom==0)
		 {
		  $as_lugar= "Nacional"; 
		 }
		else
		 {
		   $as_lugar= "Extranjero"; 
		 }
		 $la_datatit[1]=array( 'columna1'=>'<b>Lugar de Compra: </b>'.$as_lugar,
		                       'columna2'=>'<b>Moneda: </b>'.$as_denmon,
							   'columna3'=>'<b>Tasa de Cambio: </b>'.$as_tasa,
							   'columna4'=>'<b>Monto en divisa: </b>'.$as_monto_d);	
		$la_columnas=array('columna1'=>'',
						   'columna2'=>'',
						   'columna3'=>'',
						   'columna4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'columna3'=>array('justification'=>'center','width'=>150), // Justificaci�n y ancho de la columna
									   'columna4'=>array('justification'=>'right','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit[1]=array( 'columna1'=>'<b>Pa�s: </b>'.$den_pais,
		                       'columna2'=>'<b>Estado:</b>'.$as_denestado,
							   'columna3'=>'<b>Municipio:</b>'.$as_denmuni,
							   'columna4'=>'<b>Parroquia:</b>'.$as_denparro);
		$la_columnas=array('columna1'=>'',
						   'columna2'=>'',
						   'columna3'=>'',
						   'columna4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'columna3'=>array('justification'=>'center','width'=>150), // Justificaci�n y ancho de la columna
									   'columna4'=>array('justification'=>'right','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>MONTO TOTAL EN LETRAS (Bs.)</b>');
		$la_data[2]=array('titulo'=>'<b>'.$ls_monlet.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Funci�n que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Monto Bs.F.</b>','contenido'=>$li_montotaux,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("tepuy_soc_class_report.php");	
	require_once("../class_folder/class_funciones_soc.php");
	$in           = new tepuy_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new tepuy_soc_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];

	//Instancio a la clase de conversi�n de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
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
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_soc_class_reportbsf.php");
		$io_report=new tepuy_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
		$numalet->setMoneda("Bolivares Fuerte");
	}
		
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data= $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		$ls_descripcion="Gener� el Reporte de Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","tepuy_soc_p_registro_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(9.3,5,3.3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_diaplacom=$row["diaplacom"];
				$ls_forpagcom=$row["forpagcom"];
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ls_telfpro=$row["telpro"];
				$ls_lugcom=$row["lugentdir"];
				$ls_estlugcom=$row["estlugcom"];
				$ls_fecent=$row["fecent"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ls_observacion=$row["obsordcom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				$ls_lugentnomdep=$row["lugentnomdep"];
				$ls_concom=$row["concom"];
				$ls_monant=$row["monant"];
				$ls_tasa=$row["tascamordcom"];
				$ls_monto_d=$row["montotdiv"];
				$ls_pais=$row["codpai"];
				$ls_valido=$io_report->uf_select_pais($ls_pais,&$as_denominacion);
				if($as_denominacion==""){$as_denominacion="";}	
				$ls_estado=$row["codest"];
				$ls_valido=$io_report->uf_select_estado($ls_pais,$ls_estado,&$as_denestado);
				if($as_denestado==""){$as_denestado="";}	
				$ls_muni=$row["codest"];
				$ls_valido=$io_report->uf_select_municipio($ls_pais,$ls_estado,$ls_muni,&$as_denmuni);
				if($as_denmuni==""){$as_denmuni="";}
				$ls_parro=$row["codpar"];
				$ls_valido=$io_report->uf_select_parroquia($ls_pais,$ls_estado,$ls_muni,$ls_parro,&$as_denparro);
				if($as_denparro==""){$as_denparro="";}
				$ls_codmon=$row["codmon"];
				$ls_valido=$io_report->uf_select_moneda($ls_codmon,&$as_denmon);
														
				if($ls_tiporeporte==0)
				{
					$ld_montotaux=$row["montotaux"];
					$ld_montotaux=number_format($ld_montotaux,2,",",".");
				}
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				//$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
				$ls_monant=number_format($ls_monant,2,",",".");
				$ls_tasa=number_format($ls_tasa,2,",",".");
				$ls_monto_d=number_format($ls_monto_d,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);				
		 
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                           $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,
										   $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ls_telfpro,$ls_lugcom,$ls_fecent,
										   $ls_estlugcom,$ls_codpro,$ls_coduniadm,$ls_denuniadm,$ls_lugentnomdep,
										   $ls_concom, $ls_monant,$ls_observacion,&$io_pdf);
				/////////////////////////////datos de la requisici�n////////////////////////////////////////////
				$ls_empresa = $_SESSION["la_empresa"];
				$ls_req=$io_report->uf_select_soc_sep($ls_empresa,$ls_numordcom,$ls_estcondat);
				
				 if ($row_1=$io_sql->fetch_row($ls_req))
				  {
				    $ls_numsol=$row_1["numsol"];
					$ls_denuniadm_req=$row_1["denuniadm"];
					uf_print_requision ($ls_numsol,$ls_denuniadm_req, &$io_pdf);
				  }
				  else
				   {
				     $ls_numsol="";
					 $ls_denuniadm_req="";
					 //uf_print_requision ($ls_numsol,$ls_denuniadm_req, &$io_pdf);
				   }
				/////////////////////////////////////////////////////////////////////////////////////////////////
				/////DETALLE  DE  LA ORDEN DE COMPRA
			   $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
			   if ($lb_valido)
			   {
		     	 $li_totrows = $io_sql->num_rows($rs_datos);
				 if ($li_totrows>0)
				 {
				    $li_i = 0;
				    while($row=$io_sql->fetch_row($rs_datos))
					{
						$li_i=$li_i+1;
						$ls_codartser=$row["codartser"];
						$ls_denartser=$row["denartser"];
						if($ls_estcondat=="B")
						{
							$ls_unidad=$row["unidad"];
							$ls_denunidadmed=$row["denunimed"];	
						}
						else
						{
							$ls_unidad="";
							$ls_denunidadmed="";
						}
						if($ls_unidad=="D")
						{
						   $ls_unidad="Detal";
						}
						elseif($ls_unidad=="M")
						{
						   $ls_unidad="Mayor";
						}
						$li_cantartser=$row["cantartser"];
						$ld_preartser=$row["preartser"];
						$ld_subtotartser=$ld_preartser*$li_cantartser;
						$ld_totartser=$row["monttotartser"];
						$ld_carartser=$ld_totartser-$ld_subtotartser;
						
						
						$ld_preartser=number_format($ld_preartser,2,",",".");
						$ld_subtotartser=number_format($ld_subtotartser,2,",",".");
						$ld_totartser=number_format($ld_totartser,2,",",".");
						$ld_carartser=number_format($ld_carartser,2,",",".");
						
						$ls_descuento=$io_report->uf_select_descuentos($ls_numordcom,$ls_estcondat);
						$descuento=0;	
						if($row_1=$io_sql->fetch_row($ls_descuento))
						{
						  $descuento=$row["monto"];						 
						}
						else
						{
						  $descuento;					  
						}	
							
						$la_data[$li_i]=array('codigo'=>$ls_codartser,'denominacion'=>$ls_denartser,'cantidad'=>$li_cantartser,
											  'unidad'=>$ls_unidad,'cosuni'=>$ld_preartser,'baseimp'=>$ld_subtotartser,
											  'cargo'=>$ld_carartser,'montot'=>$ld_totartser, 'numero'=>$li_i, 
											  'presentacion'=>$ls_denunidadmed,'descuento'=>$descuento);
					}
					uf_print_detalle($la_data,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
					{
						 $li_totrows = $io_sql->num_rows($rs_datos_cuenta);
						 if ($li_totrows>0)
						 {
							$li_s = 0;
							while($row=$io_sql->fetch_row($rs_datos_cuenta))
							{
								$li_s=$li_s+1;
								$ls_codestpro1=trim($row["codestpro1"]);
								$ls_codestpro2=trim($row["codestpro2"]);
								$ls_codestpro3=trim($row["codestpro3"]);
								$ls_codestpro4=trim($row["codestpro4"]);
								$ls_codestpro5=trim($row["codestpro5"]);
								$ls_spg_cuenta=$row["spg_cuenta"];
								$ld_monto=$row["monto"];
								$ld_monto=number_format($ld_monto,2,",",".");
								$ls_dencuenta="";
								$lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$ls_spg_cuenta=substr($ls_spg_cuenta,0,3)."-".substr($ls_spg_cuenta,3,2)."-".substr($ls_spg_cuenta,5,2)."-".substr($ls_spg_cuenta,7,2);
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}
							//////////////////////////para calcular el monto total si tiene o no descuentos/////////////////
							$ls_monto_menos_desc=$ld_montot-$descuento;
							$ld_montot=number_format($ld_montot,2,",",".");
							$ls_monto_menos_desc=number_format($ls_monto_menos_desc,2,",",".");	
							/////////////////////////////////////////////////////////////////////////////////////////////////
							///uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ls_monto_menos_desc,$descuento,&$io_pdf);	
							uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							uf_print_piecabecera_total($ld_montot,&$io_pdf);
							unset($la_data);
						}
				     }
			      }
		       }
	     	}
		}
		uf_monto_en_letras ($ls_monto,$ls_estlugcom,$ls_tasa,$ls_monto_d,
		                    $as_denominacion,$as_denestado,$as_denmuni,$as_denparro,$as_denmon,&$io_pdf);
		///uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ld_montot,$ls_monto,&$io_pdf);		
		 
	} 	  	 
	if($lb_valido) // Si no ocurrio ning�n error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo alg�n error
	{
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		print(" close();");
		print("</script>");		
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_soc);
?>