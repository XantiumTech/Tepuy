<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//          FECHA DE MODIFICACION : 30/11/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_cmpmov,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_cmpmov // numero de comprobante de movimiento
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(928,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(934,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codemp,$as_nomemp,$as_depen,$as_distrito,$as_direccion,$as_servicio,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   as_codact   // codigo de activo
		//	    		   as_denact   // denominacion de activo
		//	    		   as_maract   // marca del activo
		//	    		   as_modact   // modelo del activo
		//	    		   ad_fecmpact // fecha de compra del activo
		//	    		   ai_costo    // costo del activo
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Costo Bs.:";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Costo Bs.F.:";
		}
		$la_data=array(array('name'=>'<b>Organismo:</b>  '.$as_codemp." - ".$as_nomemp.''),
		               array ('name'=>'<b>Servicio:</b>  '.$as_servicio.''),
					   array ('name'=>'<b>Unidad Administrativa:</b>  '.$as_depen.''),
					   array ('name'=>'<b>Distrito:</b>  '.$as_distrito.''),
					   array ('name'=>'<b>Direcci�n o Lugar:</b>  '.$as_direccion.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>954); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codgru'=>'<b>Grupo</b>',
		                  'codsubgru'=>'<b>Subgrupo</b>',
						  'codsec'=>'<b>Secci�n</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'ideact'=>'<b>Identificaci�n</b>',
						  //'seract'=>'<b>Serial</b>',
		                  'denact'=>'<b>Denominaci�n</b>',
						  //'maract'=>'<b>Marca</b>',						  
						  //'modact'=>'<b>Modelo</b>',		                 
						  //'estact'=>'<b>Estatus</b>',						  
						  'costo1'=>'<b>Valor Unitario</b>',
						  'costo'=>'<b>Valor Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna	
						               'codsubgru'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna	
									   'codsec'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna	
									   'cantidad'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna	
									   'ideact'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna	
									   //'seract'=>array('justification'=>'left','width'=>80),	               	 			 
						 			   'denact'=>array('justification'=>'left','width'=>300), // Justificaci�n y ancho de la columna						 			  
						 			   //'estact'=>array('justification'=>'left','width'=>70),
									   //'maract'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna		
									   //'modact'=>array('justification'=>'left','width'=>70),
									   
									   'costo1'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'costo'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
									   
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_montot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>900); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>""));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 8, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>900, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>900))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime las firmas
		//	   Creado Por: Ing. Arnaldo Su�rez
		// Fecha Creaci�n: 06/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-175);
		$la_data[1]=array('name1'=>'JEFE DE LA UNIDAD DE TRABAJO','name2'=>'REPONSABLE DE BIENES DE LA UNIDAD DE TRABAJO','name3'=>'UNIDAD DE TRABAJO');
		$la_data[2]=array('name1'=>'','name2'=>'','name3'=>'');
		$la_data[3]=array('name1'=>'Nombre y Apellido:___________________________________','name2'=>'Nombre y Apellido:___________________________________','name3'=>'Nombre y Apellido:___________________________________');
		$la_data[4]=array('name1'=>'Firma:                    ___________________________________','name2'=>'Firma:                    ___________________________________','name3'=>'Firma:                    ___________________________________');
		$la_data[5]=array('name1'=>'C.I:                         ___________________________________','name2'=>'C.I:                         ___________________________________','name3'=>'C.I:                         ___________________________________');
		$la_columna=array('name1'=>'',
						  'name2'=>'',
						  'name3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>930, // Ancho M�nimo de la tabla
						 'xPos'=>500, // Orientaci�n de la tabla
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>300), // Justificacion y ancho de la columna
  						 			   'name2'=>array('justification'=>'left','width'=>300),
									   'name3'=>array('justification'=>'left','width'=>300))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_firmas		//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos("../../");
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");
	$ld_fecha="";
	$ls_titulo="<b>INVENTARIO DE BIENES MUEBLES</b>";
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
		$ld_fecha="DESDE:".$ld_desde."  HASTA:".$ld_hasta."";
	}
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_distrito=$arre["estemp"];
	$ls_direccion=$arre["direccion"];
	/*$ls_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");*/
	/*$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");*/
	//$ls_status=$io_fun_activos->uf_obtenervalor_get("status","");
	//$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduni","");
	$ls_coddesde=$_GET["coddesde"];
	$ls_codhasta=$_GET["codhasta"];
	$ls_ordenact=$_GET["ordenact"];
	$ls_status=$_GET["status"];
	$ls_coduniadm=$_GET["coduni"]; 
	$ls_grupo=$_GET["grupo"];
	$ls_subgrupo=$_GET["subgrupo"];
	$ls_seccion=$_GET["seccion"];
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("tepuy_saf_class_reportbsf.php");
		$io_report=new tepuy_saf_class_reportbsf();
	}
	else
	{
		require_once("tepuy_saf_class_report.php");
		$io_report=new tepuy_saf_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_inventario_unidad($ls_coduniadm,$ld_desde,$ld_hasta,$ls_status,$ls_ordenact,$ls_coddesde,$ls_codhasta,$ls_grupo,$ls_subgrupo,$ls_seccion); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
	   
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Gener� un reporte de Activo. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","tepuy_saf_r_activo_bien.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el n�mero de p�gina
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina	
					
	        $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina	
					
			if($lb_valido)
			{			
				$li_totrow_det=$io_report->ds->getRowCount("codact");
				$la_data="";
				$i=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{  				
					$ls_codact=    $io_report->ds->data["codact"][$li_s];
					$ls_codgru=    $io_report->ds->data["codgru"][$li_s];
					$ls_codsubgru=    $io_report->ds->data["codsubgru"][$li_s];
					$ls_codsec=    $io_report->ds->data["codsec"][$li_s];
					$ls_seract=    $io_report->ds->data["seract"][$li_s];
					$ls_denact=    $io_report->ds->data["denact"][$li_s];
					$ls_maract=    $io_report->ds->data["maract"][$li_s];
			        $ls_modact=    $io_report->ds->data["modact"][$li_s];					
					$ls_denuniadm= $io_report->ds->data["denuniadm"][$li_s];									
					$ls_estact=    $io_report->ds->data["estact"][$li_s];					
					$li_costo=$io_report->ds->data["costo"][$li_s];
			        $li_costo=$io_fun_activos->uf_formatonumerico($li_costo);
					$ls_ideact=	    $io_report->ds->data["ideact"][$li_s];
					$ls_cantidad=	    $io_report->ds->data["cantidad"][$li_s];	
					$ls_servicio=	    $io_report->ds->data["servicio"][$li_s];						
					if($ls_estact=="R"){$ls_estact="Reasignado";}
					if($ls_estact=="I"){$ls_estact="Incorporado";}					
					$la_data[$li_s]=array('codact'=>$ls_codact,'codgru'=>$ls_codgru,'codsubgru'=>$ls_codsubgru,'codsec'=>$ls_codsec,'denact'=>$ls_denact,'seract'=>$ls_seract,'denuniadm'=>$ls_denuniadm,'estact'=>$ls_estact,'maract'=>$ls_maract,'modact'=>$ls_modact,'costo1'=>$li_costo,'costo'=>$li_costo,'ideact'=>$ls_ideact,'cantidad'=>$ls_cantidad,'servicio'=>$ls_servicio);
				}
				//$li_montot=$io_fun_activos->uf_formatonumerico($li_montot);
				if($la_data!="")
				{
					$i=$i +1;
					
					uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_denuniadm,$ls_distrito,$ls_direccion,$ls_servicio,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera(5,$io_pdf); // Imprimimos pie de la cabecera/////OJOJOJO
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva p�gina
						uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_denuniadm,$ls_distrito,$ls_direccion,$ls_servicio,$io_pdf);  // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						uf_print_pie_cabecera(5,$io_pdf); // Imprimimos pie de la cabecera
					}
				}			
			unset($la_data);			
		}
		uf_print_firmas($io_pdf);
		if(($lb_valido)&&($i>0))
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
		{
		   	print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}		
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 