<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de salida  de Solicitud de Ejecucion Presupuestaria
	//  ORGANISMO: Ninguno en particular
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.Juniors Fraga         FECHA DE MODIFICACION : 14/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();
    //ini_set('display_errors', 1); 
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
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sme->uf_load_seguridad_reporte("SEP","tepuy_sme_p_solicitud.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,$ad_funcionario,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,21,590,21);
		//$io_pdf->line(480,700,480,772); //linea vertical
		//$io_pdf->line(480,735,585,735); //linea horizontal
        $io_pdf->Rectangle(15,700,570,72);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,280,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

/////////////////////////////////////  TAMA�O MEDIA CARTA
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica-Bold.afm'); // Seleccionamos el tipo de letra

//////////////////////////////////AGREGA TITULO  ///////////////////////////
		$tmx=364-10;
		$tmy=(612/10)+50;
		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$io_pdf->addText($tmy+115,$tmx-20,7,"REPUBLICA BOLIVARIANA DE VENEZUELA"); // Agregar el t�tulo
		$io_pdf->addText($tmy+126,$tmx-30,7,$ls_nomemp); // Agregar el t�tulo
		$io_pdf->addText($tmy+122,$tmx-40,7,"DIRECCION DE RECURSOS HUMANOS"); // Agregar el t�tulo

		$as_numficha=$as_numsol;
		$ls_ano = substr($_SESSION["la_empresa"]["periodo"],0,4);
		$io_pdf->addText($tmy+30,$tmx-60,10,"HOJA DE IMPUTACI�N PRESUPUESTARIA EJERCICIO FISCAL ".$ls_ano); // Agregar el t�tulo
		$io_pdf->addText(514,$tmx-40,7,"No.: ".$as_numsol); // Agregar el t�tulo
		//$io_pdf->addText(518,$tmx-50,7,"No. de Ficha: ".substr($as_numficha,9,6)); // Agregar el t�tulo
		$io_pdf->addText(528,$tmx-50,7,"Fecha: ".$ad_fecregsol); // Agregar el t�tulo
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra

/////////////////////////////////////////////////////////////////////



/////////////////////////////////////  TAMA�O CARTA
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION

		//$io_pdf->addText($tm,730,11,"SOLICITUD DE EJECUCION PRESUPUESTARIA"); // Agregar el t�tulo
		//$io_pdf->addText(485,748,9,"No. ".substr($as_numsol,9,6)); // Agregar el t�tulo
		//$io_pdf->addText(485,710,9,"Fecha ".$ad_fecregsol); // Agregar el t�tulo

/////////////////////////////////////////////////////////////////////
		//$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
/*        $io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
		$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el t�tulo
		$io_pdf->addText(42,63,7,"FIRMA / SELLO"); // Agregar el t�tulo
		$io_pdf->addText(157,122,7,"VERIFICADO POR"); // Agregar el t�tulo
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el t�tulo
		$io_pdf->addText(257,63,7,"ADMINISTRACI�N Y HACIENDA"); // Agregar el t�tulo
		$io_pdf->addText(440,122,7,"DESPACHO DEL ALCALDE"); // Agregar el t�tulo
		$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el t�tulo
*/
//FORMATO ALCALDIA DE BARINAS
 		$io_pdf->line(20,37,130,37); // ----- DEL FUNCIONARIO
 		$io_pdf->line(422,37,550,37); // ----- DEL DIRECTOR
		//$io_pdf->addText(55,30,7,"Funcionario"); // Agregar el t�tulo
		$io_pdf->addText(55,30,7,$ad_funcionario); // Agregar el t�tulo
		$ls_jefe_presupuesto  = $_SESSION["la_empresa"]["jefe_rrhh"];
		$ls_cargo_presupuesto = $_SESSION["la_empresa"]["cargo_rrhh"];

                $io_pdf->addText(400,30,7, $ls_jefe_presupuesto); // Agregar el t�tulo
                $io_pdf->addText(405,22,7,$ls_cargo_presupuesto); // Agregar el t�tulo

// Pie de P�gina
		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
		$ls_diremp = $_SESSION["la_empresa"]["direccion"];
		$ls_telemp = $_SESSION["la_empresa"]["telemp"];
		$ls_ciuemp = $_SESSION["la_empresa"]["ciuemp"];
		$ls_estemp = $_SESSION["la_empresa"]["estemp"];
		
		//$li_tm=$io_pdf->getTextWidth(5,"<b>".$ls_nomemp."</b> R.I.F.: ".$ls_rifemp);
		//$tm=296-($li_tm/2);
		//$io_pdf->addText($tm,40,5,"<b>".$ls_nomemp."</b> R.I.F.: ".$ls_rifemp);
		$dir1=substr($ls_diremp,0,155);
                //$dir1=substr($ls_diremp,0,102);
		//$dir2=substr($ls_diremp,103,151);
		$io_pdf->addText(10,14,5,$dir1);
		//$io_pdf->addText(10,11,5,$dir2);
		$io_pdf->addText(70,8,5,$ls_ciuemp.", Estado ".$ls_estemp." VENEZUELA");

//////////////////////////////////////////////

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numsol,$as_dentipsol,$as_denuniadm,$as_trabajador,$as_familiar,$as_codigo,$as_nombre,$as_consol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // numero de la solicitud de ejecucion presupuestaria
		//	   			   as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por concepto
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b> Tipo</b>','contenido'=>$as_dentipsol);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	// MUESTRA O ELIMINA LA UNIDAD EJECUTORA
		//$la_data[1]=array('titulo'=>'<b> Unidad Ejecutora</b>','contenido'=>$as_denuniadm);
		//$la_columnas=array('titulo'=>'',
		//				   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		//$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Datos del Trabajador</b>','contenido'=>$as_trabajador,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		if(strlen(trim($as_familiar))>0)
		{
			$la_data[1]=array('titulo'=>'<b>Datos del Familiar</b>','contenido'=>$as_familiar,);
			$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);
		}

		$la_data[1]=array('titulo'=>'<b>Proveedor</b>','contenido'=>$as_nombre,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Concepto</b>','contenido'=>$as_consol,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.2,
						 'innerLineThickness' =>0.2,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	// PARA LA ALCALDIA DE BARINAS NO SE MUESTRA EL TITULO DE LOS DETALLES
	/*	$io_pdf->ezSetDy(-5);
		$la_data[1]=array('titulo'=>'<b> Detalle de '.$as_dentipsol.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);*/


	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>C�digo</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'unidad'=>'<b>Unidad</b>',
						   'cosuni'=>'<b>Costo</b>',
						   'baseimp'=>'<b>Sub-Total</b>',
						   'cargo'=>'<b>Cargo</b>',
						   'montot'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras

						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>115), // Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>40), // Justificaci�n y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la columna
						 			   'cosuni'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>65), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cargos($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cargos
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Cargos </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>C�digo</b>',
						   'dencar'=>'<b>Denominaci�n</b>',
						   'monbasimp'=>'<b>Base Imp.</b>',
						   'monimp'=>'<b>Cargo</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificaci�n y ancho de la columna
						 			   'dencar'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'monbasimp'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'monimp'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>95))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest;
		if($ls_estmodest==2)
		{
			$ls_titcuentas="Estructura Presupuestaria";
		}
		else
		{
			$ls_titcuentas="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro1'=>'<b>'.'Sector'.'</b>','codestpro2'=>'<b>'.'Programa'.'</b>','codestpro3'=>'<b>'.'Actividad'.'</b>',
						   'cuenta'=>'<b>Partida</b>',
						   'denominacion'=>'<b>Descripci�n</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codestpro1'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'codestpro2'=>array('justification'=>'center','width'=>49), // Justificaci�n y ancho de la columna
						 			   'codestpro3'=>array('justification'=>'center','width'=>49), // Justificaci�n y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>102), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>230), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot // Subtotal del articulo
		//	    		   li_totcar  //  Total cargos
		//	    		   li_montot  // Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los totales
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/07
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==1)
		{
		   $ls_titsub="Bs.F.";
		   $ls_titcar="Bs.F.";
		   $ls_tittot="Bs.F.";
		}
		else
		{
		   $ls_titsub="Bs.";
		   $ls_titcar="Bs.";
		   $ls_tittot="Bs.";
		}	
		$la_data[1]=array('titulo'=>'<b>Sub Total  '.$ls_titsub.'</b>','contenido'=>$li_subtot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Cargos  '.$ls_titcar.'</b>','contenido'=>$li_totcar,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Total  '.$ls_tittot.'</b>','contenido'=>$li_montot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
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
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sme.php");
	$io_fun_sme=new class_funciones_sme();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	 $ls_numsol=$io_fun_sme->uf_obtenervalor_get("numsol","");
	 $ls_tipoformato=$io_fun_sme->uf_obtenervalor_get("tipoformato",0);
	//--------------------------------------------------------------------------------------------------------------------------------
	 global $ls_tipoformato;
	 require_once("tepuy_sme_class_report.php");
	 $io_report=new tepuy_sme_class_report();
	 //Instancio a la clase de conversi�n de numeros a letras.
	 include("../../shared/class_folder/class_numero_a_letra.php");
	 $numalet= new class_numero_a_letra();
	 //imprime numero con los valore por defecto
	 //cambia a minusculas
	 $numalet->setMayusculas(1);
	 //cambia a femenino
	 $numalet->setGenero(1);
	 //cambia moneda
	 if($ls_tipoformato==1)
	 {
		 $numalet->setMoneda("Bolivares Fuerte");
	     $ls_moneda="EN Bs.F.";
	 }
	 else
	 {
		 $numalet->setMoneda("Bolivares");
	     $ls_moneda="EN Bs.";
  	 }	
	 //cambia prefijo
	 $numalet->setPrefijo("***");
	 //cambia sufijo
	 $numalet->setSufijo("***");
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>SOLICITUD DE SERVICIO MEDICO  '.$ls_moneda.'</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('MEDIACARTA','portrait'); // Instancia de la clase PDF
			//$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(2,1,3,3); // Configuraci�n de los margenes en cent�metros
			//$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuraci�n de los margenes en cent�metros
			//$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina
			$io_pdf->ezStartPageNumbers(300,10,8,'','',1); // Insertar el n�mero de p�gina
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol=$io_report->DS->data["numsol"][$li_i];
				$ls_trabajador=$io_report->DS->data["cedtra"][$li_i];
				$nombre_trabajador=$io_report->uf_buscar_trabajador($ls_trabajador,"T");
				$ls_trabajador=$ls_trabajador." - ".$nombre_trabajador;
				$ls_familiar=$io_report->DS->data["cedfam"][$li_i];
				if(strlen(trim($ls_familiar))>0)
				{
					$nombre_familiar=$io_report->uf_buscar_trabajador($ls_familiar,"F");
					$ls_familiar=$ls_familiar." - ".$nombre_familiar;
				}
				$ls_dentipsol=$io_report->DS->data["dentipsol"][$li_i];
				$ls_denuniadm=$io_report->DS->data["denuniadm"][$li_i];
				$ls_denfuefin=$io_report->DS->data["denfuefin"][$li_i];
				$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
				$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecregsol=$io_report->DS->data["fecregsol"][$li_i];
				$ls_consol="GASTOS DE ".$io_report->DS->data["dentipservicio"][$li_i]." - ".$io_report->DS->data["consol"][$li_i];
				$li_monto=$io_report->DS->data["monto"][$li_i];
				$li_monbasimptot=$io_report->DS->data["monbasinm"][$li_i];
				$li_montotcar=$io_report->DS->data["montotcar"][$li_i];
				$ld_funcionario1=$io_report->DS->data["codaprusu"][$li_i];
				$ayuda=$io_report->DS->data["ayuda"][$li_i];
				if($ld_funcionario1!="")
				{
                                	$ld_funcionario=$io_report->uf_select_usuario($ld_funcionario1);
                                }
                                else
                                {
                                	$ld_funcionario="Funcionario";
                                }
				$numalet->setNumero($li_monto);
				$ls_monto= $numalet->letra();
				$li_monto=number_format($li_monto,2,",",".");
				$li_monbasimptot=number_format($li_monbasimptot,2,",",".");
				$li_montotcar=number_format($li_montotcar,2,",",".");
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($ld_fecregsol);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}

				uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecregsol,$ld_funcionario,&$io_pdf);
				uf_print_cabecera($ls_numsol,$ls_dentipsol,$ls_denuniadm,$ls_trabajador,$ls_familiar,$ls_codigo,$ls_nombre,$ls_consol,&$io_pdf);
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_dt_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle->getRowCount("codigo");
					$la_data="";
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_codigo=$io_report->ds_detalle->data["codigo"][$li_s];
						$ls_tipo=$io_report->ds_detalle->data["tipo"][$li_s];
						$ls_denominacion=$io_report->ds_detalle->data["denominacion"][$li_s];
						$ls_unidad=$io_report->ds_detalle->data["unidad"][$li_s];
						$li_cantidad=$io_report->ds_detalle->data["cantidad"][$li_s];
						$li_cosuni=$io_report->ds_detalle->data["monpre"][$li_s];
						$li_basimp=$li_cosuni*$li_cantidad;
						$li_monart=$io_report->ds_detalle->data["monto"][$li_s];
						$lb_valido=$io_report->uf_sep_select_unidad_medida($ls_codigo,$ls_codunimed);
					    $lb_valido=$io_report->uf_sep_select_denominacion_unidad_medida($ls_codigo,$ls_codunimed,$ls_denunimed);
						if(($ls_tipo=="B")&&($ls_unidad=="M"))
						{
							$li_unidad=$io_report->uf_select_dt_unidad($ls_codigo);
							$li_basimp=$li_cosuni*($li_cantidad*$li_unidad);
						}
						$li_cargos=($li_monart-$li_basimp);
						if($ls_unidad=="M")
						{
							$ls_unidad="MAYOR";
						}
						else
						{
							$ls_unidad="DETAL";
						}
						
						$li_cosuni=number_format($li_cosuni,2,",",".");
						$li_basimp=number_format($li_basimp,2,",",".");
						$li_monart=number_format($li_monart,2,",",".");
						$li_cargos=number_format($li_cargos,2,",",".");
						$la_data[$li_s]=array('codigo'=>$ls_codigo,'denominacion'=>$ls_denominacion,'cantidad'=>$li_cantidad,
											  'unidad'=>$ls_denunimed,'cosuni'=>$li_cosuni,'baseimp'=>$li_basimp,'cargo'=>$li_cargos,'montot'=>$li_monart);
					}
				//PARA LA ALCALDIA DE BARINAS NO SE MUESTRA LOS DETALLES //
					//uf_print_detalle($la_data,&$io_pdf);
					unset($la_data);
					$lb_valido=$io_report->uf_select_dt_cargos($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowcargos=$io_report->ds_cargos->getRowCount("codigo");
						$la_data="";
						for($li_s=1;$li_s<=$li_totrowcargos;$li_s++)
						{
							$ls_codigo=$io_report->ds_cargos->data["codcar"][$li_s];
							$ls_dencar=$io_report->ds_cargos->data["dencar"][$li_s];
							$li_monbasimp=$io_report->ds_cargos->data["monbasimp"][$li_s];
							$li_monimp=$io_report->ds_cargos->data["monimp"][$li_s];
							$li_montocar=$io_report->ds_cargos->data["monto"][$li_s];
							$li_monbasimp=number_format($li_monbasimp,2,",",".");
							$li_monimp=number_format($li_monimp,2,",",".");
							$li_montocar=number_format($li_montocar,2,",",".");
							$la_data[$li_s]=array('codigo'=>$ls_codigo,'dencar'=>$ls_dencar,'monbasimp'=>$li_monbasimp,
												  'monimp'=>$li_monimp,'monto'=>$li_montocar);
						}	
				// PARA LA ALCALDIA DE BARINAS NO SE MUESTRAN LOS DETALLES DE LOS IVA O CARGOS
						//uf_print_detalle_cargos($la_data,&$io_pdf);
						unset($la_data);
						$lb_valido=$io_report->uf_select_dt_spgcuentas($ls_numsol); // Cargar el DS con los datos del reporte
						if($lb_valido)
						{
							$li_totrowcuentas=$io_report->ds_cuentas->getRowCount("codestpro1");
							$la_data="";
							for($li_s=1;$li_s<=$li_totrowcuentas;$li_s++)
							{
								$ls_codestpro1=trim($io_report->ds_cuentas->data["codestpro1"][$li_s]);
								$ls_codestpro2=trim($io_report->ds_cuentas->data["codestpro2"][$li_s]);
								$ls_codestpro3=trim($io_report->ds_cuentas->data["codestpro3"][$li_s]);
								$ls_codestpro4=trim($io_report->ds_cuentas->data["codestpro4"][$li_s]);
								$ls_codestpro5=trim($io_report->ds_cuentas->data["codestpro5"][$li_s]);
								$ls_spgcuenta=$io_report->ds_cuentas->data["spg_cuenta"][$li_s];
								$ls_denominacion=$io_report->ds_cuentas->data["denominacion"][$li_s];
					$ext_ordinal=$io_report->uf_select_extordinal($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ext_extordinal);
								// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
								$ls_spg_anterior=$ls_spgcuenta;
								$ls_spgcuenta=substr($ls_spgcuenta,0,7);
								
								if(substr($ls_spg_anterior,9,4)<>"0000") //AUXILIAR BLANCO
								{
									$ls_spgcuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2).'.'.$ext_ordinal.substr($ls_spg_anterior,9,4);
								}
								else
								if(substr($ls_spg_anterior,7,2)<>"00")
								{
									$ls_spgcuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2);
								}
								else
								{
									$ls_spgcuenta=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2);
								}
								// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //



								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
									$ls_codestpro=substr($ls_codestpro1,18,3).'.'.substr($ls_codestpro2,4,2).'.'.substr($ls_codestpro3,1,2);
									$ls_codestpro1=substr($ls_codestpro1,18,3);
									$ls_codestpro2=substr($ls_codestpro2,4,2);
									$ls_codestpro3=substr($ls_codestpro3,1,2);
									
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,18,2)." - ".substr($ls_codestpro2,4,2)." - ".substr($ls_codestpro3,1,2)." - ".$ls_codestpro4." - ".$ls_codestpro5;
								}
								
								$li_montocta=$io_report->ds_cuentas->data["monto"][$li_s];
								$li_montocta=number_format($li_montocta,2,",",".");
								
								$la_data[$li_s]=array('codestpro1'=>$ls_codestpro1,'codestpro2'=>$ls_codestpro2,'codestpro3'=>$ls_codestpro3,'cuenta'=>$ls_spgcuenta,'denominacion'=>$ls_denominacion,'monto'=>$li_montocta);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
					}
				}
			}
		}
		uf_print_piecabecera($li_monbasimptot,$li_montotcar,$li_monto,$ls_monto,&$io_pdf);
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo alg�n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>