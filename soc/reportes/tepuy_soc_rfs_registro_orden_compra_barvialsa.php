<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: ALCALDIA DEL MUNICIPIO PAEZ. ESTADO APURE
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
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_codpro,$as_nompro,$as_rifpro,$as_dirpro,
	                                    $as_telpro,$as_conordcom,$as_denfuefin,$as_coduniadm,$as_denuniadm,$as_obsordcom,$as_nomdep,$as_dirdep,
										$as_diaplacom,$as_seguro,$ad_porseg,$ad_monseg,$ls_forpagcom,$as_conent,$ad_monantpag,$ad_nomusu,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: 
		//   $as_estcondat : Tipo de la Orden de Compra (B) = Bienes, (S) = Servicios.
		//   $as_numordcom : N�mero que identifica a la Orden de Compra.
		//	 $ad_fecordcom : Fecha de Creaci�n de la Orden de Compra.
		//	    $as_codpro : C�digo del Proveedor asociado a la Orden de Compra.
		//	 	$as_nompro : Nombre del Proveedor.
		//	 	$as_rifpro : N�mero del Registro de Informaci�n Fiscal.
		//	 	$as_dirpro : Direcci�n del Proveedor.
		//	 	$as_telpro : Tel�fono de Ubicaci�n del Proveedor.
		//	 $as_denfuefin : Nombre de la Fuente de Financiamiento.
		//	 $as_coduniadm : C�dificaci�n de la Unidad Administrativa.
		//	 $as_denuniadm : Nombre de la Unidad Administrativa.
		//	 $as_obsordcom : Observaci�n de la Orden de Compras.
		//	 	$as_nomdep : Nombre de la Dependencia.
		//	 	$as_dirdep : Direcci�n de la Dependencia.
		//	 $as_diaplacom : D�as de Plazo para la entrega de los Bienes o Prestaci�n del Servicio.
		//	 $as_forpagcom : Forma de Cancelaci�n de la Orden de Compras. 
		//	 	$as_conent : Condiciones de la Entrega.
		//	 	   $io_pdf : Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Juniors Fraga.
		// Fecha Creaci�n: 10/10/2007.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(150,705,440,40);
		$io_pdf->line(400,705,400,745);
		$io_pdf->line(400,725,590,725);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		if(($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat=="")) 
        {
             $ls_titulo="Orden de Compra"; $antes="OC-";	
        }
        else
        {
             $ls_titulo="Orden de Servicio"; $antes="OS-";
        }
		$tm=230;
        $io_pdf->addText($tm,720,14,"<b>".$ls_titulo."</b>");
		$io_pdf->addText(450,730,10,"     <b> No.:</b> ".$antes.substr($as_numordcom,9,6));
		$io_pdf->addText(450,709,10,"  <b>Fecha:</b> ".$ad_fecordcom);
		//$io_pdf->addText(554,750,7,date("d/m/Y"));
        
		$io_pdf->ezSetY(698);
		/*$la_data=array(array('name'=>'<b>Proveedor: </b>'.$as_codpro.' - '.$as_nompro.'<b> - Rif: </b>'.$as_rifpro),
 		               array('name'=>'<b>Direcci�n: </b>'.$as_dirpro.'<b> - Tel�fono:</b> '.$as_telpro),
					   array('name'=>'<b>Fuente de Financiamiento: </b>'.$as_denfuefin),
					   array('name'=>'<b>Unidad Ejecutora: </b>'.$as_coduniadm.' - '.'<b>'.$as_denuniadm.'</b>'),
					   array('name'=>'<b>Observaci�n: </b> '.$as_obsordcom));*/
		$la_data=array(array('name'=>'<b>Unidad que Genera la Orden: </b>'.'<b>'.$as_denuniadm.'</b>'),
				array('name'=>'<b>Proveedor: </b>'.$as_nompro.'<b>  -  Rif: </b>'.$as_rifpro),
 		               array('name'=>'<b>Direcci�n: </b>'.$as_dirpro.'<b> - Tel�fono:</b> '.$as_telpro),
					   array('name'=>'<b>CONCEPTO: </b>'.$as_conordcom));  
//					   array('name'=>'<b>Observaci�n: </b> '.$as_obsordcom));

		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>570, // Ancho de la tabla						 						 					 
						 'maxWidth'=>570); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
	/*	$io_pdf->ezSetDy(-5);
		$la_data[1]  = array('columna1'=>'<b>Dependencia</b>','columna2'=>'<b>Direcci�n</b>','columna3'=>'<b>Plazo de Entrega</b>');
		$la_data[2]  = array('columna1'=>strtoupper($as_nomdep),'columna2'=>$as_dirdep,'columna3'=>''.$as_diaplacom);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 8, // Tama�o de Letras
						     'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						     'showLines'=>1, // Mostrar L�neas
						     'shaded'=>0, // Sombra entre l�neas
						     'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificaci�n y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
									       'columna3'=>array('justification'=>'left','width'=>190))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

        $la_data[1]  = array('columna1'=>'<b>Seguro</b>','columna2'=>'<b>Porcentaje</b>','columna3'=>'<b>Monto del Seguro</b>');
		$la_data[2]  = array('columna1'=>$as_seguro,'columna2'=>$ad_porseg,'columna3'=>$ad_monseg);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=>8, // Tama�o de Letras
						     'titleFontSize'=>8,  // Tama�o de Letras de los t�tulos
						     'showLines'=>1, // Mostrar L�neas
						     'shaded'=>0, // Sombra entre l�neas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho M�ximo de la tabla
						     'xOrientation'=>'center', // Orientaci�n de la tabla
						     'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificaci�n y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
										   'columna3'=>array('justification'=>'left','width'=>190))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]  = array('columna1'=>'<b>Condici�n de Entrega</b>','columna2'=>'<b>Forma de Pago</b>','columna3'=>'<b>Anticipo de Pago</b>');
		$la_data[2]  = array('columna1'=>$as_conent,'columna2'=>$ls_forpagcom,'columna3'=>$ad_monantpag);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=>8, // Tama�o de Letras
						     'titleFontSize'=>8,  // Tama�o de Letras de los t�tulos
						     'showLines'=>1, // Mostrar L�neas
						     'shaded'=>0, // Sombra entre l�neas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho M�ximo de la tabla
						     'xOrientation'=>'center', // Orientaci�n de la tabla
						     'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificaci�n y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
										   'columna2'=>array('justification'=>'left','width'=>190)
										   )); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);*/
		
		$io_pdf->Rectangle(20,47,570,135);
		$io_pdf->line(20,172,590,172); //linea que divide firmas y sellos
		$io_pdf->line(20,115,590,115); //linea superior de la RECEPCION
	/*	$io_pdf->line(20,160,590,160);		
		$io_pdf->line(160,120,160,180);		
		$io_pdf->line(290,120,290,180);		
		$io_pdf->line(430,120,430,180);		
		
		if (($as_estcondat=="B")  || ($as_estcondat=="-") || ($as_estcondat=="") )
           {
		     $io_pdf->addText(80,150,6,"COMPRAS");
	 	   }
		else
		   {
		     $io_pdf->addText(55,167,6,"SERVICIOS GENERALES");
	 	   }
		
		$io_pdf->addText(80,43,6,"");
		$io_pdf->addText(200,167,6,"PRESUPUESTO");
		$io_pdf->addText(200,43,6,"");
		$io_pdf->addText(315,172,6,"GERENCIA DE ADMINISTRACION");
		$io_pdf->addText(340,164,6,"  Y FINANZAS");
		$io_pdf->addText(475,167,6,"GERENCIA GENERAL");
		$io_pdf->addText(450,43,6,"");
		*/	  

//FORMATO ALCALDIA DE GUASDUALITO
 		//$io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(20,163,590,163);	// Linea que se encuentra en el nivel inferior de ELABORADO POR:	
		$io_pdf->line(160,115,160,173);	// ESTAS LINEAS DIVIDEN LOS ESP. VERT. DE LAS FIRMAS QUE CONFORMAN	
		$io_pdf->line(290,115,290,173);		
		$io_pdf->line(430,115,430,173);			
		$io_pdf->addText(230,175,6,"<b>FIRMAS Y SELLOS PARA LA APROBACI�N DE LA ORDEN DE COMPRA</b>"); // Agregar el t�tulo
		//$li_tm=$io_pdf->getTextWidth(13,$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]);
		$li_tm=$io_pdf->getTextWidth(13,$ad_nomusu);
		$tm=124-($li_tm/2);
		$io_pdf->addText(62,165,6,"ELABORADO POR:"); // Agregar el t�tulo
		$io_pdf->addText($tm,118,6,$ad_nomusu); // Agregar el t�tulo
		//$io_pdf->addText(187,104,6,"JEAN CARLOS OROZCO"); // Agregar el t�tulo
		//$io_pdf->addText(184,118,6,"LCDO. JHONNY MERCADO"); // Agregar el t�tulo
		$io_pdf->addText(184,118,6,"LCDA. JUDEXY BENITEZ"); // Agregar el t�tulo
		$io_pdf->addText(195,165,6,"JEFA DE COMPRAS"); // Agregar el t�tulo
		//$io_pdf->addText(320,118,6,"LCDA. LILIANA MONTILLA"); // Agregar el t�tulo
		$io_pdf->addText(320,118,6," LCDA. MARIA E. MEJIA"); // Agregar el t�tulo
		$io_pdf->addText(335,165,6,"ADMINISTRADORA"); // Agregar el t�tulo
//		$io_pdf->addText(440,122,7,"DESPACHO DEL ALCALDE"); // Agregar el t�tulo
		$ls_nomrep = $_SESSION["la_empresa"]["nomrep"];
		$ls_cargorep = $_SESSION["la_empresa"]["cargorep"];
		$li_tm=$io_pdf->getTextWidth(6,$ls_nomrep);
		$tm=510-($li_tm/2);
		$io_pdf->addText($tm,118,6,$ls_nomrep);
//		$io_pdf->addText(482,118,6,"JOSE ALVARADO"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(6,$ls_cargorep);
		$tm=510-($li_tm/2);
//		$io_pdf->addText(492,165,6,"ALCALDE"); // Agregar el t�tulo
		$io_pdf->addText($tm,165,6,$ls_cargorep); // Agregar el t�tulo
//		$io_pdf->addText(498,125,7,"OMAR BARRIETO"); // Agregar el t�tulo
//		$io_pdf->addText(485,167,7,"CONTRALOR MUNICIPAL"); // Agregar el t�tulo


		$io_pdf->line(20,101,590,101); // LINEA QUE SE MUESTRA EN LA PARTE INFERIOR DE LA RECEPCION
		$io_pdf->addText(240,104,6,"<b>RECEPCION CONFORME DE LA ORDEN POR EL PROVEEDOR</b>");
		$io_pdf->line(20,92,590,92); // LINEA QUE SE MUESTRA EN LA PARTE INFERIOR DE LOS DATOS DE RECEPCION
		$io_pdf->line(220,47,220,101);		
		$io_pdf->line(360,47,360,101);	
		$io_pdf->addText(80,94,6,"APELLIDO (S) Y NOMBRE (S)");
		$io_pdf->addText(250,94,6,"CEDULA DE IDENTIDAD N�");
		$io_pdf->addText(405,94,6,"FIRMA, FECHA Y SELLO. RECIBIDO CONFORME");
		
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
				
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	//function uf_print_detalle($as_estcondat,$la_data,$ld_subtot,$ld_totcar,$ld_montot,&$io_pdf)
	function uf_print_detalle($as_estcondat,$la_data,$ld_exonerado,$ld_baseimponible,$ld_impuesto,$as_titulo_por,$ld_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		// convierte los caracteres de el monto exento de iva, el iva y la base imponible en formato numerico //
		$ld_impuesto	 = number_format($ld_impuesto,2,",",".");
		$ld_exonerado	 = number_format($ld_exonerado,2,",",".");
		$ld_baseimponible= number_format($ld_baseimponible,2,",",".");
               // MODIFICA FORMATO DEL IMPUESTO //
		//if(substr($ld_impuesto,3,2)<50){$as_titulo_por=substr($as_titulo_por,0,3)."00"; }else{$as_titulo_por=$as_titulo_por+1;$as_titulo_por=substr($as_titulo_por,0,3)."00";} 
		// si base imponible es igual a 0 no muestra porcentaje en 0.00 //
		if($ld_impuesto==0){$as_titulo_por="___";}		
		if($ld_baseimponible==0){$as_titulo_por="___";}
		$io_pdf->ezSetDy(-5);
		
		if ($as_estcondat=='B')
		   {
			$ls_titulo_grid = "Bienes";
		    
			$la_columnas=array('renglon'=>'<b>Rengl�n</b>','codigo'=>'<b>C�digo</b>',
				               'denominacion'=>'<b>Denominaci�n</b>',
				               'cantidad'=>'<b>Cant.</b>',
				               'precio'=>'<b>Precio ('.$ls_bolivares.')</b>',
				               'unidad'=>'<b>Unidad</b>',
				               'subtotal'=>'<b>SubTotal ('.$ls_bolivares.')</b>',
				               'cargo'=>'<b>Cargos ('.$ls_bolivares.')</b>',
				               'montot'=>'<b>Total ('.$ls_bolivares.')</b>');

			$la_config      = array('showHeadings'=>1, // Mostrar encabezados
						 		    'fontSize' => 7.5, // Tama�o de Letras
						 			'titleFontSize'=>8,  // Tama�o de Letras de los t�tulos
						 			'showLines'=>1, // Mostrar L�neas
						 			'shaded'=>0, // Sombra entre l�neas
						 			'width'=>570, // Ancho de la tabla
						 			'maxWidth'=>570, // Ancho M�ximo de la tabla
									'xOrientation'=>'center', // Orientaci�n de la tabla
									'cols'=>array('renglon'=>array('justification'=>'center','width'=>40),      // Justificaci�n y ancho de la columna
												  'codigo'=>array('justification'=>'center','width'=>40),      // Justificaci�n y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>140), // Justificaci�n y ancho de la columna
						 			   			  'cantidad'=>array('justification'=>'right','width'=>43),     // Justificaci�n y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>62),       // Justificaci�n y ancho de la columna
						 			   			  'unidad'=>array('justification'=>'right','width'=>51),       // Justificaci�n y ancho de la columna
												  'subtotal'=>array('justification'=>'right','width'=>65),     // Justificaci�n y ancho de la columna
									  			  'cargo'=>array('justification'=>'right','width'=>65),        // Justificaci�n y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>65)));     // Justificaci�n y ancho de la columna
		}
		elseif($as_estcondat=='S')
		{
			$ls_titulo_grid="Servicios";
			
			$la_columnas=array('codigo'=>'<b>C�digo</b>',
				               'denominacion'=>'<b>Denominaci�n</b>',
				               'cantidad'=>'<b>Cant</b>',
				               'precio'=>'<b>Precio ('.$ls_bolivares.')</b>',
				   			   'subtotal'=>'<b>SubTotal ('.$ls_bolivares.')</b>',
				   			   'cargo'=>'<b>Cargos ('.$ls_bolivares.')</b>',
				   			   'montot'=>'<b>Total ('.$ls_bolivares.')</b>');

		    $la_config      = array('showHeadings'=>1, // Mostrar encabezados
						 		    'fontSize' => 8, // Tama�o de Letras
						 			'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 			'showLines'=>1, // Mostrar L�neas
						 			'shaded'=>0, // Sombra entre l�neas
						 			'width'=>570, // Ancho de la tabla
						 			'maxWidth'=>570, // Ancho M�ximo de la tabla
						 			'xOrientation'=>'center', // Orientaci�n de la tabla
						 			'cols'=>array('codigo'=>array('justification'=>'center','width'=>60),      // Justificaci�n y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>175), // Justificaci�n y ancho de la columna
						 			   			  'cantidad'=>array('justification'=>'right','width'=>40),     // Justificaci�n y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>75),       // Justificaci�n y ancho de la columna
												  'subtotal'=>array('justification'=>'right','width'=>75),     // Justificaci�n y ancho de la columna
									  			  'cargo'=>array('justification'=>'right','width'=>70),        // Justificaci�n y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>75)));     // Justificaci�n y ancho de la columna
		} 
		$io_pdf->ezTable($la_data,$la_columnas,'<b>Detalles de los '.$ls_titulo_grid.'</b>',$la_config);
	    unset($la_data);
		unset($la_columnas);
		unset($la_config);
		 
		/*$la_data[1]  = array('titulo'=>'<b>Sub-Total '.$ls_bolivares.'</b>','contenido'=>$ld_subtot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 9, // Tama�o de Letras
						     'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						     'showLines'=>0, // Mostrar L�neas
						 	 'shaded'=>0, // Sombra entre l�neas
						 	 'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna*/
		$la_data[1]  = array('titulo'=>'Monto Total Bienes o Servicios Exentos o Exonerados del Impuesto al Valor Agregado '.$ls_bolivares.'','contenido'=>$ld_exonerado);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 8, // Tama�o de Letras
						     'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						     'showLines'=>0, // Mostrar L�neas
						 	 'shaded'=>0, // Sombra entre l�neas
						 	 'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]  = array('titulo'=>'Monto Total de la Base Imponible del Impuesto al Valor Agregado segun alicuota '.'<b>'.$as_titulo_por.'% </b>'.$ls_bolivares.'','contenido'=>$ld_baseimponible);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 8, // Tama�o de Letras
						     'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						     'showLines'=>0, // Mostrar L�neas
						 	 'shaded'=>0, // Sombra entre l�neas
						 	 'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]  = array('titulo'=>'Monto Total del Impuesto al Valor Agregado segun alicuota <b>'.$as_titulo_por.'%</b> '.$ls_bolivares.'','contenido'=>$ld_impuesto);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=> 8, // Tama�o de Letras
						     'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						     'showLines'=>0, // Mostrar L�neas
						 	 'shaded'=>0, // Sombra entre l�neas
						 	 'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		// aqui se imprime el total de cargos o monto de impuesto IVA //

		/*$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$ld_totcar);
		$la_columnas=array('titulo'=>'','contenido'=>'');
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
		unset($la_config);*/
		
		$la_data[1]  = array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$ld_montot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 9, // Tama�o de Letras
						     'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						     'showLines'=>0, // Mostrar L�neas
						     'shaded'=>0, // Sombra entre l�neas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho M�ximo de la tabla
						     'xOrientation'=>'center', // Orientaci�n de la tabla
						     'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
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
		//	   Creado Por: Ing. Miguel Palencia
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
			$ls_titulo="Estructura Program�tica";
		}
		
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta Presupuestaria</b>',
						   'denominacion'=>'<b>Denominaci�n</b>',
						   'monto'=>'<b>Monto ('.$ls_bolivares.')</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>145), // Justificaci�n y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>225), // Justificaci�n y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($as_estcondat,$as_lugcom,$as_codmoneda,$ad_tasa,$ad_mondiv,$as_pais,$as_estado,$as_municipio,$as_parroquia,$ld_montot,$ls_monlet,$as_obsordcom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: ld_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los totales
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_pdf->ezSetDy(-5);
		$la_data[1] = array('monlet'=>'<b>MONTO TOTAL EN LETRAS ('.$ls_bolivares.')</b>','monnum'=>'<b>MONTO TOTAL ('.$ls_bolivares.')</b>');
		$la_data[2] = array('monlet'=>$ls_monlet,'monnum'=>$ld_montot);
		$la_columnas=array('monlet'=>'','monnum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>7, // Tama�o de Letras
						 'titleFontSize'=>12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('monlet'=>array('justification'=>'left','width'=>400),
						               'monnum'=>array('justification'=>'right','width'=>170))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data=array(array('name'=>'<b>Observaci�n: </b> '.$as_obsordcom));

		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>570, // Ancho de la tabla						 						 					 
						 'maxWidth'=>570); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
	
	    //------------------------------------------------  Datos   ----------------------------------------------	
/*				
		if( ($as_estcondat=="B")  || ($as_estcondat=="-") || ($as_estcondat=="") )
        {			
				$la_data[1]=array('lugar'=>'<b>Lugar de Compra:   </b>'.$as_lugcom,'moneda'=>'<b>Moneda: </b>'.$as_codmoneda,'tasa'=>'<b>Tasa de Cambio: </b>'.$ad_tasa,'monto'=>'<b>Monto en Divisas: </b>'.$ad_mondiv);				
				$la_columna=array('lugar'=>'','moneda'=>'','tasa'=>'','monto'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8,   // Tama�o de Letras
								 'showLines'=>1,    // Mostrar L�neas
								 'shaded'=>0,       // Sombra entre l�neas
								 'xOrientation'=>'center', // Orientaci�n de la tabla
								 'width'=>570,      // Ancho de la tabla						 						 
								 'maxWidth'=>570,
								 'cols'=>array('lugar'=>array('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
											   'moneda'=>array('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
											   'tasa'=>array('justification'=>'left','width'=>144), // Justificaci�n y ancho de la columna
											   'monto'=>array('justification'=>'left','width'=>142)));
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);	
				
				$la_data[1]=array('pais'=>'<b>Pais: </b>'.$as_pais,'estado'=>'<b>Estado: </b>'.$as_estado,'municipio'=>'<b>Municipio: </b>'.$as_municipio,'parroquia'=>'<b>Parroquia: </b>'.$as_parroquia);				
				$la_columna=array('pais'=>'','estado'=>'','municipio'=>'','parroquia'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize'=>8,     // Tama�o de Letras
								 'showLines'=>1,    // Mostrar L�neas
								 'shaded'=>0,       // Sombra entre l�neas
								 'xOrientation'=>'center', // Orientaci�n de la tabla
								 'width'=>570,      // Ancho de la tabla						 						 			 
								 'maxWidth'=>570,
								 'cols'=>array('pais'=>array('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
											   'estado'=>array('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
											   'municipio'=>array('justification'=>'left','width'=>144), // Justificaci�n y ancho de la columna
											   'parroquia'=>array('justification'=>'left','width'=>142)));  
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);						
		}	*/
		//------------------------------------------------  Datos   ----------------------------------------------------------
        if( ($as_estcondat=="B")  || ($as_estcondat=="-") )
        {
				$ls_clafiecum="FIANZA DE FIEL CUMPLIMIENTO: AL APROBARSE ESTA ORDEN SE EXIGIRA AL BENEFICIARIO, FIANZA DE FIEL CUMPLIENTO EQUIVALENTE AL ______ %";
				$ls_clafiecum2= "DEL MONTO DE ESTA ORDEN, OTORGADA POR UN BANCO O COMPA�IA DE SEGUROS, VIGENTE HASTA LA TOTAL ENTREGA DE MERCANCIA";
		
				$ls_clapenal="CLAUSULA PENAL: QUEDA ESTABLECIDA LA CLAUSULA PENAL, SEGUN LA CUAL EL PROVEEDOR PAGARA AL FISCO EL  _______% SOBRE EL MONTO DE LA"; 
				$ls_clapenal2="MERCANCIA RESPECTIVA, POR CADA DIA HABIL DE RETARDO EN LA ENTREGA";
		
				$ls_claesp="CLAUSULA ESPECIAL: EL ORGANISMO SE RESERVA EL DERECHO DE ANULAR UNILATERALMENTE LA PRESENTE ORDEN DE COMPRA SIN INDEMNIZACION,"; 
				$ls_claesp2="DE CONFORMIDAD CON LAS PREVISIONES LEGALES QUE RIGEN LA MATERIA";
				
				$la_data=array(array('titulo'=>''));				
				$la_columna=array('titulo'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 5, // Tama�o de Letras
								 'showLines'=>0, // Mostrar L�neas
								 'shaded'=>0, // Sombra entre l�neas
								 'xOrientation'=>'center', // Orientaci�n de la tabla
								 'width'=>570, // Ancho de la tabla						 										 
								 'maxWidth'=>570,
								 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Ancho M�ximo de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);		
				
				$la_data[1]=array('clafie'=>$ls_clafiecum." ".$ls_clafiecum2,'clapen'=>$ls_clapenal." ".$ls_clapenal2,'claesp'=>$ls_claesp." ".$ls_claesp2);				
				$la_columna=array('clafie'=>'','clapen'=>'','claesp'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 5, // Tama�o de Letras
								 'showLines'=>1, // Mostrar L�neas
								 'shaded'=>0, // Sombra entre l�neas
								 'xOrientation'=>'center', // Orientaci�n de la tabla
								 'width'=>570, // Ancho de la tabla						 									 
								 'maxWidth'=>570,
								 'cols'=>array('clafie'=>array('justification'=>'left','width'=>190), // Justificaci�n y ancho de la columna
											   'clapen'=>array('justification'=>'left','width'=>190),
											   'claesp'=>array('justification'=>'left','width'=>190))); 
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);						
        }
	}

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_sep($la_datasep,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_sep
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Juniors Fraga.
		// Fecha Creaci�n: 19/10/2007. 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codigo'=>'<b>N� Ejecucion Presupuestaria</b>','denuniadm'=>'<b>Gerencia /Oficina Solicitante</b>');
		$la_config =array('showHeadings'=>1, // Mostrar encabezados
						  'fontSize'=> 8, // Tama�o de Letras
						  'titleFontSize'=>8,  // Tama�o de Letras de los t�tulos
						  'showLines'=>1, // Mostrar L�neas
						  'shaded'=>0, // Sombra entre l�neas
						  'xOrientation'=>'center', // Orientaci�n de la tabla
						  'width'=>570, // Ancho de la tabla						 										 
						  'maxWidth'=>570,
						  'cols'=>array('codigo'=>array('justification'=>'center','width'=>120),
						  				'denuniadm'=>array('justification'=>'left','width'=>450))
						); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datasep,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------

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
		$la_columnas=array('titulo'=>'','contenido'=>'');
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("tepuy_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");

	$in           = new tepuy_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new tepuy_soc_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_orgrif	  = $_SESSION["la_empresa"]["rifemp"];
	$ls_orgnom    = $_SESSION["la_empresa"]["nombre"];
	$ls_orgtit	  = $_SESSION["la_empresa"]["titulo"];
	$ls_codemp	  = $_SESSION["la_empresa"]["codemp"];

	//Instancio a la clase de conversi�n de numeros a letras.
	require_once("../../shared/class_folder/class_numero_a_letra.php");
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
	$rs_data   = $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
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
		    if ($ls_estcondat=='B')
			   {
 			     $io_pdf->ezSetCmMargins(9,7,3.3,3); // Configuraci�n de los margenes en cent�metros   
			   }
			elseif($ls_estcondat=='S')
			   {
			     $io_pdf->ezSetCmMargins(9,6.7,3.3,3); // Configuraci�n de los margenes en cent�metros   
			   }
	    	$io_pdf->ezStartPageNumbers(588,760,8,'','',1); 
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom = $row["numordcom"];
				$ls_estcondat = $row["estcondat"];
				$ls_coduniadm = $row["coduniadm"];
				$ls_denuniadm = $row["denuniadm"];
				$ls_codfuefin = $row["codfuefin"];
				$ls_denfuefin = $row["denfuefin"];
				$ls_diaplacom = $row["diaplacom"];
				$ls_forpagcom = $row["forpagcom"];
				$ls_codpro	  = $row["cod_pro"];
				$ls_nompro	  = $row["nompro"];
				$ls_rifpro	  = $row["rifpro"];
				$ls_dirpro	  = $row["dirpro"];
				$ls_telpro	  = $row["telpro"];
				$ld_fecordcom = $row["fecordcom"];
				$ls_conordcom = $row["obscom"];
				$ls_obsordcom = $row["obsordcom"];
				$ld_monsubtot = $row["monsubtot"];
				$ld_monimp	  = $row["monimp"];
				$ld_montot	  = $row["montot"];
				$li_seguro    = $row["estsegcom"];
				$ld_porseg    = $row["porsegcom"];
				$ld_nomusu    = $row["nomusu"].' '.$row["apeusu"];
				$ld_monseg    = number_format($row["monsegcom"],2,',','.');
				$ld_monantpag = number_format($row["monant"],2,',','.');
				if ($li_seguro==0)
				   {
				     $ls_seguro="No";
				   }
				else
				   {
				     $ls_seguro="Si";
				   }
				if ($ls_tiporeporte==0)
				   {
					 $ld_montotaux = $row["montotaux"];
					 $ld_montotaux = number_format($ld_montotaux,2,",",".");
				   }
				$ls_nomdep    = $row["lugentnomdep"];
				$ls_dirdep    = $row["lugentdir"];
				$ls_conentord = $row["concom"];
				$numalet->setNumero($ld_montot);
				$ls_monto     = $numalet->letra();
				$ld_montot    = number_format($ld_montot,2,",",".");
				$ld_monsubtot = number_format($ld_monsubtot,2,",",".");
				$ld_monimp    = number_format($ld_monimp,2,",",".");
				$ld_fecordcom = $io_funciones->uf_convertirfecmostrar($ld_fecordcom);
				$ld_tasa      = $row["tascamordcom"];
				$ld_mondiv    = $row["montotdiv"];			
				$ls_lugcom    = $row["estlugcom"];
				$ls_denpai    = "";
				$ls_denest    = "";
				$ls_denmun    = "";
				$ls_denpar    = "";
				$ls_moneda    = "";
				$ls_codpai    = $row["codpai"];
				if ($ls_codpai!='---')
				   {
				     $ls_denpai = $io_report->uf_select_denominacion('tepuy_pais','despai',"WHERE codpai='".$ls_codpai."'");
				   }
				$ls_codest = $row["codest"];
				if ($ls_codest!="---")
				   {
				     $ls_denest = $io_report->uf_select_denominacion('tepuy_estados','desest',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."'");   
				   }
				$ls_codmun = $row["codmun"];
				if ($ls_codmun!="---")
				   {
				     $ls_denmun = $io_report->uf_select_denominacion('tepuy_municipio','denmun',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."'");				   
				   }
				$ls_codpar = $row["codpar"];
				if ($ls_codpar!="---")
				   {
				     $ls_denpar = $io_report->uf_select_denominacion('tepuy_parroquia','denpar',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' AND codpar='".$ls_codpar."'");				   
				   }
				$ls_codmon = $row["codmon"];
				if ($ls_codmon!="---")
				   {
				     $ls_moneda = $io_report->uf_select_denominacion('tepuy_moneda','denmon',"WHERE codmon='".$ls_codmon."'");   
				   }
				
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_codpro,$ls_nompro,$ls_rifpro,$ls_dirpro,
	                                       $ls_telpro,$ls_conordcom,$ls_denfuefin,$ls_coduniadm,$ls_denuniadm,$ls_obsordcom,
										   $ls_nomdep,$ls_dirdep,$ls_diaplacom,$ls_seguro,$ld_porseg,$ld_monseg,$ls_forpagcom,$ls_conentord,
										   $ld_monantpag,$ld_nomusu,&$io_pdf);
			   /////DETALLE  DE  LA ORDEN DE COMPRA
			   $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
			   if ($lb_valido)
			   {
		     	 $li_totrows = $io_sql->num_rows($rs_datos);
				 if ($li_totrows>0)
				 {
				    $li_i = 0;
					$exonerado=0;
					$baseimponible=0;
					$impuesto=0;
					$titulo_por="";
				    while($row=$io_sql->fetch_row($rs_datos))
					{
						$li_i++;
		// aqui selecciono donde voy a anidar el iva, dependiendo del porcentaje que se aplique al articulo //
						//$porcarart=$row["porcarart"];
						//if($porcarart!=""){$titulo_por=$porcarart;}
				//////////////////////////////////////////////////////////////////////////////////////////////////////
						$ls_codartser=$row["codartser"];
						$ls_denartser=$row["denartser"];
						if($ls_estcondat=="B")
						{
							$ls_unidad=$row["unidad"];
							$ls_unidad=$row["denunimed"];
						}
						else
						{
							$ls_unidad="";
						}
						if($ls_unidad=="D")
						{
						   $ls_unidad="Detal";
						}
						elseif($ls_unidad=="M")
						{
						   $ls_unidad="Mayor";
						}
						$li_cantartser   = $row["cantartser"];
						$ld_preartser    = $row["preartser"];
						$ld_subtotartser = $ld_preartser*$li_cantartser;
						$ld_totartser    = $row["monttotartser"];
						$ld_carartser    = $ld_totartser-$ld_subtotartser;
						$porcarart="";
						//if($ld_carartser>0)
						$ld_subtotartser1=number_format($ld_subtotartser,4);
						$ld_subtotartser1=str_replace(',','',$ld_subtotartser1);
				//PERMITE LLEGAR HASTA DECIMAL 
				//$largo=strlen($ld_totartser);
				$hasta=strpos($ld_totartser, ".");
				$X1=substr($ld_totartser,0,$hasta);
				//$largo1=strlen($ld_totartser1);
				$hasta1=strpos($ld_subtotartser1, ".");
				$X2=substr($ld_subtotartser1,0,$hasta1);

				//print "una: ".$ld_totartser." otra ".$ld_subtotartser1;
				//print "una: ".$X1." otra ".$X2;
						//if($ld_totartser>$ld_subtotartser1)
						if($X1>$X2)
						{
							$rs_datos1 = $io_report->uf_select_extraer_iva($ls_codartser,$ls_estcondat,&$lb_valido);
							$li_totrows1 = $io_sql->num_rows($rs_datos1);
				 			if ($li_totrows1>0)
				 			{
				    				$li_i1 = 0;
								while($row=$io_sql->fetch_row($rs_datos1))
								{
									$li_i1++;
									$porcarart   = $row["porcar"];
								}
							}
							$baseimponible=$baseimponible+$ld_subtotartser; // acumula la base imponible
							$impuesto=$impuesto+$ld_carartser; // acumula el iva
							$titulo_por=$porcarart; // graba el porcentaje de iva aplicado
						}
						else
						{
							$exonerado=$exonerado+$ld_subtotartser; // acumula los articulos exentos de iva
							//print "Exonerado: ".$exonerado." sub-total: ".$ld_subtotartser;
						}
						$ld_preartser    = number_format($ld_preartser,2,",",".");
						$ld_subtotartser = number_format($ld_subtotartser,2,",",".");
						$ld_totartser	 = number_format($ld_totartser,2,",",".");
						$ld_carartser	 = number_format($ld_carartser,2,",",".");
						/*$impuesto	 = number_format($impuesto,2,",",".");
						$exonerado	 = number_format($exonerado,2,",",".");
						$baseimponible	 = number_format($baseimponible,2,",",".");*/
				// MUESTRA . EN LUGAR DE , AL MOMENTO DE MOSTRAR EL PORCENTAJE DE I.V.A. //
						$titulo_por	 = number_format($titulo_por,2,".",",");
						if ($ls_estcondat=='B')
						{
							$ls_codartser=substr($ls_codartser,14,6);
						}
						else
						{
							$ls_codartser=substr($ls_codartser,4,6);
						}
						$la_data[$li_i]  = array('renglon'=>$li_i,
									 'codigo'=>$ls_codartser,
						                         'denominacion'=>$ls_denartser,
											     'cantidad'=>number_format($li_cantartser,2,',','.'),
											     'precio'=>$ld_preartser,
											     'unidad'=>$ls_unidad,
												'subtotal'=>$ld_subtotartser,
											     'cargo'=>$ld_carartser,
											     'montot'=>$ld_totartser);
					}
					
					//uf_print_detalle($ls_estcondat,$la_data,$ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);
					uf_print_detalle($ls_estcondat,$la_data,$exonerado,$baseimponible,$impuesto,$titulo_por,$ld_montot,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
					{
						 $li_totrows = $io_sql->num_rows($rs_datos);
						 if ($li_totrows>0)
						 {
							$li_s = 0;
							while($row=$io_sql->fetch_row($rs_datos_cuenta))
							{
								$li_s++;
								$ls_codestpro1 = trim($row["codestpro1"]);
								$ls_codestpro2 = trim($row["codestpro2"]);
								$ls_codestpro3 = trim($row["codestpro3"]);
								$ls_codestpro4 = trim($row["codestpro4"]);
								$ls_codestpro5 = trim($row["codestpro5"]);
								$ls_spg_cuenta = trim($row["spg_cuenta"]);
								$ld_monto      = $row["monto"];
								$ld_monto      = number_format($ld_monto,2,",",".");
								$ls_dencuenta  = "";
								$lb_valido     = $io_report->uf_select_denominacionspg($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.'.'.$ls_codestpro2.'.'.$ls_codestpro3;
						$ls_codestpro=substr($ls_codestpro1,18,3).'.'.substr($ls_codestpro2,4,2).'.'.substr($ls_codestpro3,1,2);
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
								$ls_spg_anterior=$ls_spg_cuenta;
								$ls_spg_cuenta=substr($ls_spg_cuenta,0,7);
								
								if(substr($ls_spg_anterior,9,4)<>"0000") //AUXILIAR BLANCO
								{
									$ls_spg_cuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2).'.'.substr($ls_spg_anterior,9,4);
								}
								else
								if(substr($ls_spg_anterior,7,2)<>"00")
								{
									$ls_spg_cuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2);
								}
								else
								{
									$ls_spg_cuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2);
								}
								// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }if($porcarart!=""){$baseimponible=$baseimponible+$ld_subtotartser;}
			       $lb_validosep = true;
				   $li_totrow	 = 0;
				   $lb_validosep = $io_report->uf_select_soc_sep($ls_codemp,$ls_numordcom,$ls_estcondat);	
				   if ($lb_validosep)
					  {										
					    $li_totrow = $io_report->ds_soc_sep->getRowCount("numordcom");							
						for ($li_row=1;$li_row<=$li_totrow;$li_row++)
							{
							  $ls_numsep   		   = $io_report->ds_soc_sep->data["numsol"][$li_row];  											  
							  $ls_denunadm 		   = $io_report->ds_soc_sep->data["denuniadm"][$li_row];  											  
							  $la_datasep[$li_row] = array('codigo'=>$ls_numsep,'denuniadm'=>$ls_denunadm);
							}														
						uf_print_detalle_sep($la_datasep,$io_pdf); 
					  } 
				  }
		       }
	     	}
		}
		uf_print_piecabecera($ls_estcondat,$ls_lugcom,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_denpai,$ls_denest,$ls_denmun,$ls_denpar,$ld_montot,$ls_monto,$ls_obsordcom,&$io_pdf);
	 /*   if ($ls_tiporeporte==0)
		   {
			 uf_print_piecabeceramonto_bsf($ld_montotaux,&$io_pdf);
		   }*/
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
