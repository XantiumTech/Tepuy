<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Documentos
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();
    ini_set('display_errors', 1);   
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
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","tepuy_cxp_r_solicitudesf1.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
	//	$io_pdf->line(15,40,785,40);
        $io_pdf->Rectangle(15,530,800,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=406-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$io_pdf->addText(735,570,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(741,564,6,date("h:i a")); // Agregar la Hora
/*		// cuadro inferior
        $io_pdf->Rectangle(15,60,760,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
	//	$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(42,63,7,"FIRMA / SELLO"); // Agregar el título
		$io_pdf->addText(157,122,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(257,63,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
	//	$io_pdf->addText(440,122,7,"CONTRALORIA INTERNA"); // Agregar el título
	//	$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título*/
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
			
	function uf_print_detalle($la_data,$ai_i,$ai_totmonsol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data      // arreglo de información
		//				   ai_i         // total de registros
		//				   li_totmonsol // total de solicitudes (Montos)
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 16/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('ficha'=>'<b>Ficha</b>','numsol'=>'<b>Orden de Pago</b>','codigopre'=>'<b>Código Presupuestario</b>',
							'nombrepre'=>'<b>Denominación de la Partida</b>','montopartida'=>'<b>Monto por Partida</b>','nombre'=>'<b>Proveedor/Beneficiario</b>','fecemisol'=>'<b>Fecha Emision</b>','monsol'=>'<b>Monto Neto '.$ls_titulo.'</b>','monret'=>'<b>Monto Ret.</b>');
		$la_columnas=array('ficha'=>'',
							'numsol'=>'',
							'codigopre'=>'',
							'nombrepre'=>'',
							'montopartida'=>'',
							'nombre'=>'',
							'fecemisol'=>'',
							'monsol'=>'',
							'monret'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>780, // Ancho de la tabla
						 'maxWidth'=>780, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('ficha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'numsol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			 'codigopre'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			'nombrepre'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 		'montopartida'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'center','width'=>80),
						 			   'monret'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('ficha'=>'',
							'numsol'=>'',
							'codigopre'=>'',
							'nombrepre'=>'',
							'montopartida'=>'',
							'nombre'=>'',
							'fecemisol'=>'',
							'monsol'=>'',
							'monret'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'width'=>780, // Ancho de la tabla
						 'maxWidth'=>780, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('ficha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'numsol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			'codigopre'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			'nombrepre'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 		'montopartida'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'right','width'=>80),
						 			   'monret'=>array('justification'=>'right','width'=>80))); //)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Total de Registros: </b>'.$ai_i,'total'=>'<b>Bs. '.$ai_totmonsol.'</b>');
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'total'=>'<b>Total '.$ls_titulo.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>780, // Ancho de la tabla
						 'maxWidth'=>780, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>595), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	$io_report=new tepuy_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>LISTADO DE ORDENES DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$li_emitida=$io_fun_cxp->uf_obtenervalor_get("emitida","");
	$li_contabilizada=$io_fun_cxp->uf_obtenervalor_get("contabilizada","");
	$li_anulada=$io_fun_cxp->uf_obtenervalor_get("anulada","");
	$li_propago=$io_fun_cxp->uf_obtenervalor_get("propago","");
	$li_pagada=$io_fun_cxp->uf_obtenervalor_get("pagada","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte","");
	$ls_codprodes1=$io_fun_cxp->uf_obtenervalor_get("codestpro1","");
	$ls_codprodes2=$io_fun_cxp->uf_obtenervalor_get("codestpro2","");
	$ls_codprodes3=$io_fun_cxp->uf_obtenervalor_get("codestpro3","");
	$ls_codprohas1=$io_fun_cxp->uf_obtenervalor_get("codestpro1h","");
	$ls_codprohas2=$io_fun_cxp->uf_obtenervalor_get("codestpro2h","");
	$ls_codprohas3=$io_fun_cxp->uf_obtenervalor_get("codestpro3h","");
	$ls_cuentades=$io_fun_cxp->uf_obtenervalor_get("cuentades","");
	$ls_cuentahas=$io_fun_cxp->uf_obtenervalor_get("cuentahas","");
	$ls_codprodes=$ls_codprodes1.$ls_codprodes2.$ls_codprodes3;
	$ls_codprohas=$ls_codprohas1.$ls_codprohas2.$ls_codprohas3;
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_cxp_class_reportbsf.php");
		$io_report=new tepuy_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitudesf1A4($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas,
													   $li_emitida,$li_contabilizada,$li_anulada,$li_propago,$li_pagada,$ls_codprodes,	$ls_codprohas,$ls_cuentades,$ls_cuentahas); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
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
			$io_pdf=new Cezpdf('A4','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3,2,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(758,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numsol");
			//print "total: ".$li_totrow;die();
			$li_totmonsol=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol=substr($io_report->DS->data["numsol"][$li_i],9,6);
				$ls_ficha=$io_report->DS->data["numrecdoc"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecemisol=$io_report->DS->data["fecemisol"][$li_i];
				$ls_estprosol=$io_report->DS->data["estprosol"][$li_i];
				$li_monsol=$io_report->DS->data["monsol"][$li_i];
				$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
				$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
				$li_monret=$io_report->DS->data["mondeddoc"][$li_i];
				switch ($ls_estprosol)
				{
					case 'E':
						$ls_denest='Emitida';
						break;
					case 'C':
						$ls_denest='Contabilizada';
						break;
					case 'A':
						$ls_denest='Anulada';
						break;
					case 'S':
						$ls_denest='Programacion de Pago';
						break;
					case 'P':
						$ls_denest='Pagada';
						break;
				}
				$li_totmonsol=$li_totmonsol+$li_monsol;
				$li_monsol=number_format($li_monsol,2,",",".");
				$li_monret=number_format($li_monret,2,",",".");
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				///////////////////////////////////// Cuenta de Gastos /////////////////////
				$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_ficha,$ls_cedbene,$ls_codpro,$ls_codprodes,	$ls_codprohas,&$valido); 
				if($valido)
				{
					$li_totrows=$io_report->io_sql->num_rows($rs_datos_cuenta);
					if ($li_totrows>0)
					{
						//$li_s = 0;
						$ls_cambia=0;
						while($row=$io_report->io_sql->fetch_row($rs_datos_cuenta))
						{
							$li_s++;
							$ls_codestpro = trim($row["codestpro"]);
							$ls_codestpro1=substr($ls_codestpro,0,20);
							$ls_codestpro2=substr($ls_codestpro,20,6);
							$ls_codestpro3=substr($ls_codestpro,26,3);
							$ls_codestpro=substr($ls_codestpro,18,2).'-'.substr($ls_codestpro,24,2).'-'.substr($ls_codestpro,27,2);
							$ls_spg_cuenta = trim($row["spg_cuenta"]);
							$ld_monto1      = $row["monto"];
							$ld_monto1      = number_format($ld_monto1,2,",",".");
			$io_report->uf_select_denominacionspg($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,'00','00',$ls_spg_cuenta,$ls_dencuenta);
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
							// FIN DE LA ELIMINACION DE CODIGOS CUANDO SEAN 00 //
							$ls_codigopre=$ls_codestpro." ".$ls_spg_cuenta;
							if ($ls_cambia>0)
					 		{
							$la_data[$li_s]=array('ficha'=>'','numsol'=>'','codigopre'=>$ls_codigopre,'nombrepre'=>$ls_dencuenta,'montopartida'=>$ld_monto1,'nombre'=>'','fecemisol'=>'','monsol'=>'','monret'=>'');
							}
							else
							{
							$la_data[$li_s]=array('ficha'=>$ls_ficha,'numsol'=>$ls_numsol,'codigopre'=>$ls_codigopre,'nombrepre'=>$ls_dencuenta,'montopartida'=>$ld_monto1,'nombre'=>$ls_nombre,'fecemisol'=>$ld_fecemisol,
									  'monsol'=>$li_monsol,'monret'=>$li_monret);
								$ls_cambia++;
							}
							
						}
					}
				} ///////////////////////////////////// FIN Cuenta de Gastos /////////////////////
				else
				{
					$li_s++;
					$la_data[$li_s]=array('ficha'=>$ls_ficha,'numsol'=>$ls_numsol,'nombre'=>$ls_nombre,'fecemisol'=>$ld_fecemisol,'denest'=>$ls_denest,
									  'monsol'=>$li_monsol);
				}
			}
			$li_totmonsol=number_format($li_totmonsol,2,",",".");
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			uf_print_detalle($la_data,$li_totrow,$li_totmonsol,&$io_pdf);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
		//	print "aqui";die();
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		
	}

?>
