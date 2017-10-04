<?php
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,540,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=400-($li_tm/2);
		$io_pdf->addText($tm,530,12,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(677,585,9,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(684,575,9,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------	

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($as_cliente,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cliente // datos del Cliente
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    	$io_pdf->ezSetDy(-10);
		$la_data1=array(array('name'=>$as_cliente));				
		$la_columna1=array('name'=>'');		
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						// 'xPos'=>310, // Orientación de la tabla
						 'width'=>700, // Ancho de la tabla						 
						 'maxWidth'=>700, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>700))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);
		unset($la_data1);
		unset($la_columna1);
		unset($la_config1);
	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_listado($la_data,&$io_pdf)
	{	 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		global $ls_bolivares;
		$io_pdf->ezSetDy(-2);
		
				array('codpro'=>$ls_codpro,'denpro'=>$ls_denpro,'canpro'=>$li_sumcanpro,'monsubpro'=>$li_sumsubpro,'moncarpro'=>$li_sumcarpro,'montotpro'=>$li_sumtotpro);

		$la_columna=array('codpro'=>'<b>Código</b>',
						  'denpro'=>'<b>Denominación</b>',
						  'canpro'=>'<b>Cantidad</b>',
						  'monsubpro'=>'<b>Sub-Total</b>',
						  'moncarpro'=>'<b>Impuesto Bs.</b>',
						  'montotpro'=>'<b>Total Producto (Bs.)</b>');
					  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas						 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						// 'xPos'=>100, // Orientación de la tabla
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('codpro'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denpro'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'canpro'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 		//	   'preunipro'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'monsubpro'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
   						 		//	   'poriva'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'moncarpro'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'montotpro'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
						 		//	   'estatus'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
								//	   'estcondat'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 		//	   'estatus'=>array('justification'=>'center','width'=>50)));

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_resumen_factura($aa_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_detalle
		//		   Access: private 
		//	    Arguments: as_data // Registros a Imprimir
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los totales x factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/11/2016 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetDy(-2);
		$la_columna=array('titulo'=>'<b>TOTALES </b>',
		                  'cantidad'=>'<b>Cantidad Productos</b> ',
				   'total'=>'<b>Total Bs. </b> ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						// 'xPos'=>310, // Orientación de la tabla
						 'width'=>700, // Ancho de la tabla						 
						 'maxWidth'=>700, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>300),      // Justificaci? y ancho de la columna
						 		'cantidad'=>array('justification'=>'right','width'=>150), // Ancho Máximo de la tabla
						 		'total'=>array('justification'=>'right','width'=>240))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($aa_data,$la_columna,'',$la_config);
		unset($aa_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_pie_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_numreg,$ad_totmon,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
	    $io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>N° de Facturas: </b>'.$ad_numreg,
		                     'name1'=>'<b>Total '.$ls_bolivares.':</b> '.$ad_totmon));				
		$la_columna=array('name'=>'','name1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						// 'xPos'=>310, // Orientación de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>600),      // Justificaci? y ancho de la columna
						 			   'name1'=>array('justification'=>'right','width'=>150))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("tepuy_sfa_class_report.php");	
	require_once("../class_folder/class_funciones_sfa.php");
	$in           = new tepuy_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_sfa   = new class_funciones_sfa();
	$io_report    = new tepuy_sfa_class_report();
	$ls_bolivares="Bs.";
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=false;
	//----------------------------------------------------  Parámetros del encabezado    -----------------------------------------------
	$ls_titulo ="RESUMEN CONSOLIDADO DE FACTURAS EMITIDAS";	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	
	$ls_numfacturades=	$io_fun_sfa->uf_obtenervalor_get("numfacturades","");
	$ls_numfacturahas=	$io_fun_sfa->uf_obtenervalor_get("numfacturahas","");
	$ls_cedclides=		$io_fun_sfa->uf_obtenervalor_get("cedclides","");
	$ls_cedclihas=		$io_fun_sfa->uf_obtenervalor_get("cedclihas","");
	$ls_fecfacturades=	$io_fun_sfa->uf_obtenervalor_get("fecfacturades","");
	$ls_fecfacturahas=	$io_fun_sfa->uf_obtenervalor_get("fecfacturahas","");
	$ls_codprodes=		$io_fun_sfa->uf_obtenervalor_get("codprodes","");
	$ls_codprohas=		$io_fun_sfa->uf_obtenervalor_get("codprohas","");
	//print "Desde: ".$ls_cedclides." Hasta: ".$ls_cedclihas; die();
	
	$ls_rdemi=	$io_fun_sfa->uf_obtenervalor_get("rdemi","");
	$ls_rdcon=	$io_fun_sfa->uf_obtenervalor_get("rdcon","");
	$ls_rdanu=	$io_fun_sfa->uf_obtenervalor_get("rdanu","");
	
	$ls_formapago=trim($io_fun_sfa->uf_obtenervalor_get("formapago",""));
	$ls_tipocliente=$io_fun_sfa->uf_obtenervalor_get("tipocliente","");
	if($ls_formapago!="")
	{
		$titulo1=" ".$ls_formapago;
	}
	 $ls_titulo=$ls_titulo." DEL ".$ls_fecfacturades." AL ".$ls_fecfacturahas;
	//print "Desde: ".$ls_cedclides." Hasta: ".$ls_cedclihas; die();
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data = $io_report->uf_select_consolidado_facturas($ls_numfacturades,$ls_numfacturahas,$ls_cedclides,
                                                            $ls_cedclihas,$ls_fecfacturades,$ls_fecfacturahas,$ls_rdemi,$ls_rdcon,$ls_rdanu,
                                                            $ls_codprodes,$ls_codprohas,$ls_formapago,$ls_tipocliente,&$lb_valido);
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_descripcion="Generó el Reporte de Resumen de Facturas";
		$lb_valido=$io_fun_sfa->uf_load_seguridad_reporte("SFA","tepuy_sfa_r_resumen_facturas.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			//$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
			$io_pdf->ezStartPageNumbers(748,47,9,'','',1); // Insertar el número de página
			$ldec_monto=0;
			$li_i=0;
			$li_s = 0;
			$li_row=$io_sql->num_rows($rs_data);
			if ($li_row>0)
			{     
				$li_totsubtot	= 0;
				$li_totmonimp	= 0;
				$li_totmontot	= 0;
				$li_gensumtotpro=0;
				while($row=$io_sql->fetch_row($rs_data))
				{

				  $li_i=$li_i+1;
				  $ls_nombre	="";
				  $ls_numfactura= $row["numfactura"];
				  $ls_cedcli	= $row["cedcli"];
				  $ls_nombre	= trim($row["nomcli"])." ".trim($row["apecli"]);
				  $ls_formadepago=trim($row["formadepago"]);
				  $ls_estatus	= $row["estfac"];
				  $ls_fecfactura= $row["fecfactura"];
				  $li_monsubtot	= $row["monsubtot"];
				  $li_monbasimp = $row["monbasimp"];
				  $li_monimp	= $row["monimp"];
				  $li_montot	= $row["montot"];
				  $li_totsubtot	= $li_totsubtot+$li_monsubtot;
				  $li_totmonimp	= $li_totmonimp+$li_monimp;
				  $li_totmontot	= $li_totmontot+$li_montot;
				  $li_monsubtot = number_format($li_monsubtot,2,",",".");	
				  $li_monimp	= number_format($li_monimp,2,",",".");	
				  $li_montot	= number_format($li_montot,2,",",".");	
				 // $arg1= " SELECT nomcli, apecli FROM sfa_cliente  WHERE ";
				 // $arg3= " AND cedcli ='".$as_cedcli."' "; 
				 // $ls_nombre	= $io_report->uf_select_nombre_cliente($ls_cedcli,$arg1,$arg3);
				  $ls_fecfactura   = $io_funciones->uf_convertirfecmostrar($ls_fecfactura);	
				  
				  if($ls_estatus==1){
					 $status="Emitida";			 
				   }
				   
				  if($ls_estatus==2){
					 $status="Procesada";			 
				   }
			
				  if($ls_estatus==3){
					 $status="Anulada";			 
				   }
				$resumen="<b>"."Productos Factura al Cliente: "."</b>".$ls_nombre;
				uf_print_cabecera_detalle($resumen,$io_pdf);
				////////////////////////////Productos de la Factura /////////////////////
				$rs_datos_cuenta=$io_report->uf_select_dt_consolidado_factura($ls_cedcli,&$lb_valido); 
				if($lb_valido)
				{
					 $li_totrows = $io_sql->num_rows($rs_datos_cuenta);
					 if ($li_totrows>0)
					 {
						$ls_cambia=0;
						$li_sumcanpro=0;
						$li_sumsubpro=0;
						$li_sumcarpro=0;
						$li_sumtotpro=0;
						$codproant="";
						$llevo=0;
						$imprima=false;
						$li_totalgeneral=0;
						$li_totalgeneralpro=0;
						while($row=$io_sql->fetch_row($rs_datos_cuenta))
						{
							$li_s++;
							$ls_trabajador= $row["trabajador"];
							$ls_codpro	= trim($row["codpro"]);
							$ls_denpro	= trim($row["denpro"]);
							$ls_denunimed	= $row["denunimed"];
							$li_canpro	= $row["cantpro"];
							$li_porivapro	= $row["poriva"];
							$li_preunipro	= $row["preunipro"];
							$li_monsubpro	= $row["monsubpro"];
							$li_moncarpro	= $row["moncarpro"];
							$li_montotpro	= $row["montotpro"];
							if($li_s==1)
							{
								$codproant=$ls_codpro;
								$ls_codproant= $ls_codpro;
								$ls_denproant= $ls_denpro;
							}
						////////// subtotales del producto x  cliente  //////////

							if($codproant!=$ls_codpro)
							{
								//print "anterior: ".$ls_codproant." Este es: ".$ls_codpro;die();
								$li_sumcanpro	= number_format($li_sumcanpro,2,",",".");
								//$li_porivapro	= number_format($li_porivapro,2,",",".");
								//$li_preunipro	= number_format($li_preunipro,2,",",".");
								$li_sumsubpro	= number_format($li_sumsubpro,2,",",".");
								$li_sumcarpro	= number_format($li_sumcarpro,2,",",".");
								$li_sumtotpro	= number_format($li_sumtotpro,2,",",".");
							$la_data[$li_s]= array('codpro'=>$ls_codproant,'denpro'=>$ls_denproant,'canpro'=>$li_sumcanpro,'monsubpro'=>$li_sumsubpro,'moncarpro'=>$li_sumcarpro,'montotpro'=>$li_sumtotpro);
								$codproant=$ls_codpro;
								$ls_codproant= $ls_codpro;
								$ls_denproant= $ls_denpro;
								$li_sumcanpro=0;
								$li_sumsubpro=0;
								$li_sumcarpro=0;
								$li_sumtotpro=0;
							}

							$li_sumcanpro	= $li_sumcanpro+$li_canpro;	
							$li_sumsubpro	= $li_sumsubpro+$li_monsubpro;
							$li_sumcarpro	= $li_sumcarpro+$li_moncarpro;
							$li_sumtotpro	= $li_sumtotpro+$li_montotpro;
							$li_totalgeneralpro=$li_totalgeneralpro+$li_canpro;
							$li_totalgeneral=$li_totalgeneral+$li_montotpro;
						}
						////// cambio formato a los detalles del producto /////
						$li_sumcanpro	= number_format($li_sumcanpro,2,",",".");
						$li_sumsubpro	= number_format($li_sumsubpro,2,",",".");
						$li_sumcarpro	= number_format($li_sumcarpro,2,",",".");
						$li_sumtotpro	= number_format($li_sumtotpro,2,",",".");
						$la_data[$li_s]= array('codpro'=>$ls_codproant,'denpro'=>$ls_denproant,'canpro'=>$li_sumcanpro,'monsubpro'=>$li_sumsubpro,'moncarpro'=>$li_sumcarpro,'montotpro'=>$li_sumtotpro);
						$li_totalgeneralpro	= number_format($li_totalgeneralpro,2,",",".");
						$li_totalgeneral	= number_format($li_totalgeneral,2,",",".");
						$aa_data[1]=array('titulo'=>'<b> RESUMEN GENERAL</b>','cantidad'=>"Cantidad de Productos ".'<b>'.$li_totalgeneralpro.'</b>','total'=>" Total Facturado Bs. ".'<b>'.$li_totalgeneral.'</b>');
						uf_print_listado($la_data,$io_pdf);
						uf_print_resumen_factura($aa_data,$io_pdf);
						unset($la_data);

					}
				}
				//////////////////////////////////////////////////////////////////////////////////



		
				// $la_data[$li_i]= array('codigo'=>substr($ls_numord,9),'nombre'=>$ls_nombre,'estcondat'=>$ls_estcondat,
				//					 'fecha'=>$ls_fecha,'estatus'=>$status,'monto'=>$ld_monto);
			      }
			   // uf_print_listado($la_data,$io_pdf); // Imprimimos el detalle 		
				$li_gensumtotpro  = number_format($li_gensumtotpro,2,",",".");	
				//uf_print_pie_cabecera($li_i,$li_gensumtotpro,$io_pdf);		
				if($lb_valido) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					ob_end_clean();  // resuleve el problema cuando no se muestran las imagenes
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else  // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					print("close();");
					print("<
/script>");		
				}
				unset($io_pdf);
			}
			else
			{
				print("<script language=JavaScript>");
				print("alert('No hay nada que reportar');"); 
				print("close();");
				print("</script>");		
			}				
		}	
		unset($io_report);
		unset($io_funciones);
	}	
?> 
