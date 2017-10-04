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
									   $ls_forpagcom,$ls_periodo,$ls_codadscito,$ls_orgadscito,$ls_codemp,$ls_nombre,
									   $ls_lugentnomdep,$ls_lugentdir,$ls_desest,$ls_denmun,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yozelin Barragan              Modificado Por: Ing. Gloriely Fr�itez
		// Fecha Creaci�n: 21/06/2007                  Fecha de Modificaci�n: 04/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(15,40,585,40);
		$io_pdf->line(480,920,480,980);
		$io_pdf->line(480,950,585,950);
        $io_pdf->Rectangle(15,920,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],17,922,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		if($as_estcondat=="B") 
        {
             $ls_titulo="Orden de Compra";	
			 $ls_titulo_grid="Bienes";
        }
        else
        {
             $ls_titulo="Orden de Servicio";
			 $ls_titulo_grid="Servicios";
        }
		
		$li_tm=$io_pdf->getTextWidth(14,$ls_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,945,14,'<b>'.$ls_titulo.'</b>'); // Agregar el t�tulo
		$io_pdf->addText(485,960,9," <b>No. </b>".$as_numordcom); // Agregar el t�tulo
		$io_pdf->addText(485,930,9,"<b>Fecha </b>".$ad_fecordcom); // Agregar el t�tulo
		$io_pdf->addText(540,990,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,984,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(15,60,570,95); 
		$io_pdf->addText(40,80,7,"<b>Lic. Mirna T. Vies de A.</b>"); // Agregar el t�tulo
		$io_pdf->addText(45,70,7,"Directora de la Zona."); // Agregar el t�tulo
		$io_pdf->addText(40,63,7,"Educativa Estado Lara"); // Agregar el t�tulo
		$io_pdf->line(140,60,140,155);	//VERTICAL	
		$io_pdf->addText(167,120,7,"Lic. Alexander Escudero"); // Agregar el t�tulo
		$io_pdf->addText(167,110,7,"Jefe Div. Admini. y Serv."); // Agregar el t�tulo
		$io_pdf->addText(175,73,7,"Lic. Juli�n Zavarce"); // Agregar el t�tulo
		$io_pdf->addText(165,63,7,"Jefe Div. Planif. y Presup."); // Agregar el t�tulo
		$io_pdf->line(270,60,270,155);	//VERTICAL		
		$io_pdf->addText(315,120,7,"Lic. Ana Castillo"); // Agregar el t�tulo
		$io_pdf->addText(305,110,7,"Jefe Coord. de Compras"); // Agregar el t�tulo
		$io_pdf->addText(310,73,7,"Lic. Ram�n Cabello"); // Agregar el t�tulo
		$io_pdf->addText(300,63,7,"Jefe Coord. de Contabilidad"); // Agregar el t�tulo
		$io_pdf->line(420,60,420,155);	//VERTICAL		
		$io_pdf->addText(470,120,7,"Lic. Aura M. Rodriguez"); // Agregar el t�tulo
		$io_pdf->addText(450,110,7,"Jefe Unidad de Verificai�n y Control"); // Agregar el t�tulo
		$io_pdf->addText(422,95,7,"Auditado Por:"); // Agregar el t�tulo
		$io_pdf->addText(455,63,7,"Unidad de Auditoria Delegada"); // Agregar el t�tulo
		$io_pdf->line(140,105,585,105); //HORIZONTAL	
			
		$ls_periodo=substr($ls_periodo,0,4);
		$io_pdf->Rectangle(15,825,570,90);  
		$io_pdf->addText(17,895,10,"A�o: ".$ls_periodo); // Agregar el t�tulo
		$io_pdf->addText(130,895,10,"Organismo: ".$ls_codadscito.'  '.$ls_orgadscito); // Agregar el t�tulo
		$io_pdf->addText(17,875,10,"Unidad Administradora: ".$ls_codemp.'  '.$ls_nombre); // Agregar el t�tulo
		$io_pdf->addText(17,855,10,"Unidad Solicitante: "); // Agregar el t�tulo
		$io_pdf->addText(17,835,10,"Municipio: ".$ls_denmun); // Agregar el t�tulo
		$io_pdf->addText(300,835,10,"Estado: ".$ls_desest); // Agregar el t�tulo
		$io_pdf->ezSetY(820);
		$la_data[1]=array('columna1'=>'<b>Proveedor</b>  '.$as_nombre.'<b>                  Rif</b> '.$as_rifpro,
		                 'columna2'=>'<b>Direccion</b> '.$as_dirpro.'');
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>250), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>320))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$ls_uniadm=$as_coduniadm."  -  ".$as_denuniadm;
		$la_data[1]=array('columna1'=>'<b>Unidad Ejecutora</b>    '.$ls_uniadm,'columna2'=>'<b>Forma de Pago</b>    '.$ls_forpagcom);
		$la_columnas=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>300), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>270))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$ls_fuefin=$as_codfuefin."  -  ".$as_denfuefin;
		$la_data[1]=array('columna1'=>'<b>Fuente Financiamiento</b>   '.$ls_fuefin,'columna2'=>'<b> Plazo de Entrega</b>    '.$as_diaplacom);
		$la_columnas=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>300), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>270))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

        $la_data[1]=array('columna1'=>'<b>Dependencia:</b>   '.$ls_lugentnomdep,'columna2'=>'<b>Direcci�n:</b>    '.$ls_lugentdir);
		$la_columnas=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>300), // Justificaci�n y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>270))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->Rectangle(15,155,570,95); // primer rect�ngulo inferior
		$io_pdf->line(15,235,585,235);   //linea horizontal debajo del primer cuadro
		$io_pdf->addText(25,240,8,"<b>FIANZA DE FIEL CUMPLIMIENTO</b>"); 
		$io_pdf->line(175,155,175,250);  // LINEA VERTICAL
		$io_pdf->addText(17,225,8,"Cuando el monto total de la Orden sea "); 
		$io_pdf->addText(17,215,8,"superior a 250 unidades tributarias, al "); 
	    $io_pdf->addText(17,205,8,"aprobarse se exigir� al beneficiario Fianza"); 
		$io_pdf->addText(17,195,8,"de Fiel Cumplimiento equivalente al 10% del");
		$io_pdf->addText(17,185,8,"monto total de la Orden Otorgada por un"); 
		$io_pdf->addText(17,175,8,"Banco o Compa��a de Seguro, Notariada");
		$io_pdf->addText(17,165,8,"y vigente.");
		$io_pdf->addText(210,240,8,"<b>CL�USULA PENAL</b>"); 
		$io_pdf->line(325,155,325,250);  // LINEA VERTICAL
		$io_pdf->addText(177,225,8,"Queda establecida la Cl�usula Penal"); 
		$io_pdf->addText(177,215,8,"seg�n la cual elproveedor pagar� al fisco");
		$io_pdf->addText(177,205,8,"el 0,5% sobre el monto de la mercanc�a");
		$io_pdf->addText(177,195,8,"respectiva por cada d�a h�bil del retardo"); 
		$io_pdf->addText(177,185,8,"en la entrega.");
		$io_pdf->addText(360,240,8,"<b>CL�USULA ESPECIAL</b>"); 
		$io_pdf->addText(327,225,8,"El organismo se reserva el derecho"); 
		$io_pdf->addText(327,215,8,"de anular unilateralmente la presente"); 
		$io_pdf->addText(327,205,8,"Orden de Compra, sin indemnizaci�n"); 
		$io_pdf->addText(327,195,8,"de conformidad con lo dispuesto en el"); 
		$io_pdf->addText(327,185,8,"reglamento que rige la materia.");
		$io_pdf->line(465,155,465,250);  // LINEA VERTICAL
		$io_pdf->addText(492,244,6,"<b>FIRMA DEL ANALISTA</b>"); 
		$io_pdf->addText(480,237,6,"<b>(CONTOL PRESUPUESTARIO)</b>");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		$la_data[1]=array('columna1'=>'<b>Concepto</b>         '.$as_conordcom);
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);		
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_tipordcom,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//                 $as_tipordcom = Tipo de la Orden B=Bienes y S=Servicios.
		//    Description: funci�n que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_estmodest, $ls_bolivares;
		if ($as_tipordcom=='B')
		   {
			 $ls_titulo_grid="Bienes";
		   }
		elseif($as_tipordcom=='S')
		   {
		     $ls_titulo_grid="Servicios";
		   }
		$io_pdf->ezSetDy(-10);
	    $la_datatitulo[1] = array('columna1'=>'<b> Detalle de '.$ls_titulo_grid.'</b>');
	    $la_columnas      = array('columna1'=>'');
	    $la_config        = array('showHeadings'=>0, // Mostrar encabezados
							      'fontSize'=>9, // Tama�o de Letras
							      'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
							      'showLines'=>1, // Mostrar L�neas
							      'shaded'=>2, // Sombra entre l�neas
							      'width'=>570, // Ancho de la tabla
							      'maxWidth'=>570, // Ancho M�ximo de la tabla
							      'xOrientation'=>'center', // Orientaci�n de la tabla
							      'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);
		unset($la_datatitulo);
		unset($la_columnas);
		unset($la_config);

        $io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>C�digo</b>',
			  			   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'unidad'=>'<b>Unidad de Medida</b>',
						   'cosuni'=>'<b>Costo '.$ls_bolivares.'</b>',
						   'baseimp'=>'<b>Sub-Total '.$ls_bolivares.'</b>',
						   'cargo'=>'<b>Cargo '.$ls_bolivares.'</b>',
							'montot'=>'<b>Total '.$ls_bolivares.'</b>');
		 $la_config=array('showHeadings'=>1, // Mostrar encabezados
						  'fontSize' => 9, // Tama�o de Letras
						  'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						  'showLines'=>1, // Mostrar L�neas
						  'shaded'=>0, // Sombra entre l�neas
						  'width'=>570, // Ancho de la tabla
						  'maxWidth'=>570, // Ancho M�ximo de la tabla
						  'xOrientation'=>'center', // Orientaci�n de la tabla
						  'cols'=>array('codigo'=>array('justification'=>'center','width'=>65), // Justificaci�n y ancho de la columna
										'denominacion'=>array('justification'=>'left','width'=>115), // Justificaci�n y ancho de la columna
										'cantidad'=>array('justification'=>'right','width'=>40), // Justificaci�n y ancho de la columna
										'unidad'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
										'cosuni'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
										'baseimp'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
										'cargo'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
										'montot'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
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
		
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Programatica";
		}
		$io_pdf->ezSetDy(-5);
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadedCol2'=>array(0.8,0.8,0.8), // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
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
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,$ls_observacion,&$io_pdf)
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
		
		$la_data[1]=array('titulo'=>'<b>Sub Total '.$ls_bolivares.'</b>','contenido'=>$li_subtot,);
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
		$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$li_totcar,);
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
		$la_data[1]=array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$li_montot,);
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
		$io_pdf->ezSetDy(-5);
		$la_data[1]=array('titulo'=>'<b> Son: '.$ls_monlet.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>1, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$io_pdf->ezSetDy(-5);
		$la_data[2]=array('obs'=>'<b>Observaciones:</b>   '.$ls_observacion);
		$la_columnas=array('obs'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('obs'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
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
	$ls_periodo=$_SESSION["la_empresa"]["periodo"];
	$ls_codadscito=$_SESSION["la_empresa"]["codorgsig"];
	$ls_orgadscito=$_SESSION["la_empresa"]["nomorgads"];
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nombre=$_SESSION["la_empresa"]["nombre"];
	$ls_estemp=$_SESSION["la_empresa"]["estemp"];
	$ls_ciuemp=$_SESSION["la_empresa"]["ciuemp"];

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
			$io_pdf=new Cezpdf('LEGAL','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(9.5,10,3,3); // Configuraci�n de los margenes en cent�metros
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
				$ls_lugentnomdep=$row["lugentnomdep"];
				$ls_lugentdir=$row["lugentdir"];  
				$ls_codpai=$row["codpai"]; 
				$ls_codest=$row["codest"];
				$ls_codmun=$row["codmun"]; 
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ls_observacion=$row["obsordcom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				if($ls_tiporeporte==0)
				{
					$ld_montotaux=$row["montotaux"];
					$ld_montotaux=number_format($ld_montotaux,2,",",".");
				}
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);
		        $rs_datos=$io_report->uf_select_pais_municipio_estado($ls_numordcom,$ls_estcondat,$ls_codpai,$ls_codest,$ls_codmun);
			    if ($row=$io_sql->fetch_row($rs_datos))
		       	{
				 $ls_despai=$row["despai"];
				 $ls_desest=$row["desest"];  
				 $ls_denmun=$row["denmun"];
				}
				if ($ls_codpai=='---')
				   {
				     $ls_despai='';					 
				   }
				if($ls_codest=='---')
				   {
				     $ls_desest='';
				   }
				if($ls_codmun=='---')
				   {
				     $ls_denmun='';
				   }
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                           $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,
										   $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ls_periodo,$ls_codadscito,$ls_orgadscito,
										   $ls_codemp,$ls_nombre,$ls_lugentnomdep,$ls_lugentdir,$ls_desest,$ls_denmun,&$io_pdf);
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
						$li_i++;
						$ls_codartser    = trim($row["codartser"]);
				        $ls_denunimed    = $row["denunimed"];   
						$ls_denartser    = $row["denartser"];
						$li_cantartser	 = $row["cantartser"];
						$ld_preartser	 = $row["preartser"];
						$ld_subtotartser = $ld_preartser*$li_cantartser;
						$ld_totartser	 = $row["monttotartser"];
						$ld_carartser	 = $ld_totartser-$ld_subtotartser;
						
						$li_cantartser=number_format($li_cantartser,2,",",".");
						$ld_preartser=number_format($ld_preartser,2,",",".");
						$ld_subtotartser=number_format($ld_subtotartser,2,",",".");
						$ld_totartser=number_format($ld_totartser,2,",",".");
						$ld_carartser=number_format($ld_carartser,2,",",".");
						$la_data[$li_i]=array('codigo'=>$ls_codartser,'denominacion'=>$ls_denartser,'cantidad'=>$li_cantartser,
											  'unidad'=>$ls_denunimed,'cosuni'=>$ld_preartser,'baseimp'=>$ld_subtotartser,
											  'cargo'=>$ld_carartser,'montot'=>$ld_totartser);
					}
					uf_print_detalle($la_data,$ls_estcondat,&$io_pdf);
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
								$ls_spg_cuenta=trim($row["spg_cuenta"]);
								$ld_monto=$row["monto"];
								$ld_monto=number_format($ld_monto,2,",",".");
								$ls_dencuenta="";
								$lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
			      }
		       }
	     	}
		}
		uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ld_montot,$ls_monto,$ls_observacion,&$io_pdf);
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