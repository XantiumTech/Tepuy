<?php
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
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time','0');
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","tepuy_scg_r_mayor_analitico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_titulo); // Agregar el t�tulo

		$io_pdf->addText(500,720,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cuenta,$as_denominacion,$ad_saldo_ant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_cuenta // cuenta
		//	    		   as_denominacion // denominacion
		//	    		   io_pdf // Objeto PDF
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Cuenta</b> '.$as_cuenta.'  -----  '.$as_denominacion.''),
		               array('name'=>'<b>Saldo Anterior '.$ls_bolivares.'</b> '.$ad_saldo_ant.' '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar L�neas
						 'fontSize' => 7, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>305, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>0.5, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la columna
						 			   'comprobante'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'concepto'=>array('justification'=>'left','width'=>80), // Justificaci�n y ancho de la columna
									   'codproben'=>array('justification'=>'left','width'=>35), // Justificaci�n y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>50), // Justificaci�n y ancho de la columna
						 			   'documento'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>35), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna

		$la_columnas=array('procede'=>'<b>Procede</b>',
						   'comprobante'=>'<b>Comprobante</b>',
						   'concepto'=>'<b>Concepto</b>',
						   'codproben'=>'<b>Codigo</b>',
						   'nombre'=>'<b>Beneficiario</b>',
						   'documento'=>'<b>Documento</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>',
						   'saldo'=>'<b>Saldo Actual</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totaldebe,$ad_totalhaber,$ad_totalsaldo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>310, // Orientaci�n de la tabla
						 'width'=>550); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>'<b><i>Total '.$ls_bolivares.' </i></b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber,'saldo'=>$ad_totalsaldo));
		$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>550, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>305, // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>340), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>530, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");	
		$in           = new tepuy_include();
		$con          = $in->uf_conectar();
		$io_sql       = new class_sql($con);	

		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../class_funciones_scg.php");
		$io_fun_scg=new class_funciones_scg();
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("tepuy_scg_reporte.php");
				$io_report  = new tepuy_scg_reporte();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("tepuy_scg_reportebsf.php");
				$io_report  = new tepuy_scg_reportebsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scg[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
		$ld_fecdesde=$_GET["fecdes"];
		$ld_fechasta=$_GET["fechas"];
		$ls_cuentadesde_min=$_GET["cuentadesde"];
		$ls_cuentahasta_max=$_GET["cuentahasta"];
		if(($ls_cuentadesde_min=="")&&($ls_cuentahasta_max==""))
		{
		   if($io_report->uf_spg_reporte_select_cuenta($ls_cuentadesde_min,$ls_cuentahasta_max))
		   {
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		   } 
		}
		else
		{
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		}
		$ls_orden=$_GET["orden"];
		switch ($ls_orden){
			case 'COM':
				$ls_parm_orden=" scg_dt_cmp.comprobante ";
				break;
			case 'FEC':
				$ls_parm_orden=" scg_dt_cmp.fecha ";
				break;
			case 'CTA':	
				$ls_parm_orden=" scg_dt_cmp.sc_cuenta ";
				break;				
		}

	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
		$ldt_fecha="<b> Desde   ".$ld_fecdesde."   al   ".$ld_fechasta." </b>" ;
		$ls_titulo="<b> Mayor  Analitico</b>  ".$ldt_fecha;       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=uf_insert_seguridad("<b>Mayor Anal�tico en PDF</b>"); // Seguridad de Reporte
	 if($lb_valido)
	 {
		$lb_valido=$io_report->uf_cargar_cabecera_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuentadesde,$ls_cuentahasta,
		                                                          $ls_parm_orden,&$rs_data);
	 }
	 if($lb_valido==false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1);
		$li_numrowtot=$io_sql->num_rows($rs_data);
	    while($row=$io_sql->fetch_row($rs_data))
		{
			$ls_cuenta=trim($row["sc_cuenta"]);
			$ls_denominacion=$row["denominacion"];
			$ld_saldo_ant=$row["saldo_ant"];
			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scg[$li-1];
				$li_act=$ia_niveles_scg[$li];
				$li_fila=$li_act-$li_ant;
				$li_len=strlen($ls_cuenta);
				$li_totfil=$li_totfil+$li_fila;
				$li_inicio=$li_len-$li_totfil;
				if($li==$li_total)
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila);
				}
				else
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
				}
			}
			$li_fila=$ia_niveles_scg[1]+1;
			$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;//echo "Cuenta 1 => ".$as_cuenta.'<br>';
			uf_print_cabecera($as_cuenta,$ls_denominacion,number_format($ld_saldo_ant,2,",","."),$io_pdf);
		    $lb_valido=$io_report->uf_cargar_mayor_detalle_analitico($ld_fecdesde,$ld_fechasta,$ls_cuenta,$ls_cuenta,
		                                                             $ls_parm_orden,&$rs_detalle_data);
			if($lb_valido)
			{
				$i=0;
				$ld_totaldebe=0;
				$ld_totalhaber=0;
				$ld_totalsaldo=0;
				$ld_saldo=0;
				$ldec_mondeb=0;
				$ldec_monhab=0;
				while($row=$io_sql->fetch_row($rs_detalle_data))
				{
					$i++;
					$ls_comprobante=$row["comprobante"];
					$ls_codpro=$row["cod_pro"];
					$ls_cedbene=$row["ced_bene"];
					$ls_nompro=$row["nompro"];
					$ls_nombene=$row["apebene"].", ".$row["nombene"];			
					if($ls_codpro!="----------")
					{
						$ls_nombre=$ls_nompro;
						$ls_codproben=$ls_codpro;
					}	
					if($ls_cedbene!="----------")
					{
						$ls_nombre=$ls_nombene;
						$ls_codproben=$ls_cedbene;
					}	
					if(($ls_codpro=="----------")and($ls_cedbene=="----------"))
					{
					    $ls_nombre="Ninguno";
						$ls_codproben="----------";
					}	
					$ls_documento=$row["documento"];
					$ls_procede=$row["procede"];
					$ls_concepto=$row["descripcion"];
					$ldec_monto=$row["monto"];
					$fecmov=$row["fecha"];
					$ld_fecmov=$io_funciones->uf_convertirfecmostrar($fecmov);
					$ls_debhab=$row["debhab"];
					$ld_saldo_ant=$row["saldo_ant"];
					if($ls_debhab=='D')
					{
						$ldec_mondeb=$ldec_monto;
						$ldec_monhab=0;		
						$ld_totaldebe=$ld_totaldebe+$ldec_mondeb;
					}
					elseif($ls_debhab=='H')
					{
						$ldec_monhab=$ldec_monto;		
						$ldec_mondeb=0;
						$ld_totalhaber=$ld_totalhaber+$ldec_monhab;
					}
					if ($i==1)
					{
					  $ld_saldo=$ld_saldo_ant+$ldec_mondeb-$ldec_monhab;
					}
					else
					{
						if($ls_debhab=='D')
						{
							$ld_saldo=$ld_saldo+$ldec_monto;
						}
						elseif($ls_debhab=='H')
						{
							$ld_saldo=$ld_saldo-$ldec_monto;
						}
					}
					$ldec_mondeb=abs($ldec_mondeb);
					$ldec_monhab=abs($ldec_monhab);
					$ldec_mondeb=number_format($ldec_mondeb,2,",",".");
					$ldec_monhab=number_format($ldec_monhab,2,",",".");
					if($ld_saldo<0)
					{
					  $ld_saldo_aux=abs($ld_saldo); 
					  $ld_saldo_aux=number_format($ld_saldo_aux,2,",",".");
					  $ld_saldo_final="(".$ld_saldo_aux.")";
					}
					else
					{
					  $ld_saldo_aux=number_format($ld_saldo,2,",",".");
					  $ld_saldo_final=$ld_saldo_aux;
					}
					$la_data[$i]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'concepto'=>$ls_concepto,
									   'codproben'=>$ls_codproben,'nombre'=>$ls_nombre,'documento'=>$ls_documento,
									   'fecha'=>$ld_fecmov,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,'saldo'=>$ld_saldo_final);
				
				}// while del detalle
				$ld_saldo_ant=number_format($ld_saldo_ant,2,",",".");
				$ld_saldo_anterior=$ld_saldo_ant;
				
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$ld_totalsaldo_final=$ld_saldo_final;
				if($ld_totaldebe<0)
				{
				   $ld_totaldebe_aux=abs($ld_totaldebe);
				   $ld_totaldebe_aux=number_format($ld_totaldebe_aux,2,",",".");
				   $ld_totaldebe="(".$ld_totaldebe_aux.")";
				}
				else
				{
				  $ld_totaldebe=number_format($ld_totaldebe,2,",",".");
				}
				if($ld_totalhaber<0)
				{
				   $ld_totalhaber_aux=abs($ld_totalhaber);
				   $ld_totalhaber_aux=number_format($ld_totalhaber_aux,2,",",".");
				   $ld_totalhaber="(".$ld_totalhaber_aux.")";
				}
				else
				{
				  $ld_totalhaber=number_format($ld_totalhaber,2,",",".");
				}
				uf_print_pie_cabecera($ld_totaldebe,$ld_totalhaber,$ld_totalsaldo_final,$io_pdf);
				$ld_totalde=$ld_totaldebe;
				$ld_totalha=$ld_totalhaber;
				$ld_totalsal=$ld_totalsaldo_final;
				$ld_totaldebe=0;
				$ld_totalhaber=0;
				unset($la_data);						
			}// if valido
	    }//while
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 