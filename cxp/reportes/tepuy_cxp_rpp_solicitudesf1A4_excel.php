<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Documentos
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	session_start();
    //	ini_set('display_errors', 1);
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0'); 

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

	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "ordenes_pago.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("tepuy_cxp_class_report.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();
	$io_in    = new tepuy_include();
	$con      = $io_in->uf_conectar();
	$io_sql   = new class_sql($con);
	$io_report= new tepuy_cxp_class_report("../../");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();		
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------	
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="LISTADO DE ORDENES DE PAGO";
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
			//-------formato para el reporte----------------------------------------------------------
			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('11');
			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_text_wrap();
			$lo_titulo->set_bold();
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('9');		
			$lo_datacenter= &$lo_libro->addformat();
			$lo_datacenter->set_font("Verdana");
			$lo_datacenter->set_align('center');
			$lo_datacenter->set_size('9');
			$lo_dataleft= &$lo_libro->addformat();
			$lo_dataleft->set_text_wrap();
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('9');
			$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');
		
			$lo_dataright2= &$lo_libro->addformat(array(num_format => '#,##'));
			$lo_dataright2->set_font("Verdana");
			$lo_dataright2->set_align('right');
			$lo_dataright2->set_size('9');	
			$lo_hoja->set_column(0,0,15);
			$lo_hoja->set_column(1,1,15);
			$lo_hoja->set_column(2,2,25);	
			$lo_hoja->set_column(3,3,60);
			$lo_hoja->set_column(4,4,25);
			$lo_hoja->set_column(5,5,14);	
			$lo_hoja->set_column(6,6,60);
			$lo_hoja->set_column(7,7,12);
			$lo_hoja->set_column(8,8,25);
			$lo_hoja->set_column(9,9,20);	
			$lo_hoja->write(0,3,$ls_titulo,$lo_encabezado);
			//$lo_hoja->write(1,3,$ls_periodo,$lo_encabezado);			
			$lo_hoja->write(4,0, "Nro. Ficha",$lo_titulo);	
			$lo_hoja->write(4,1, "Nro. Orden de Pago",$lo_titulo);
			$lo_hoja->write(4,2, "Codigo Presupuestario",$lo_titulo);
			$lo_hoja->write(4,3, "Denominación de la Partida",$lo_titulo);
			$lo_hoja->write(4,4, "Monto por Partida",$lo_titulo);
			$lo_hoja->write(4,5, "RIF",$lo_titulo);	
			$lo_hoja->write(4,6, "Proveedor/Beneficiario",$lo_titulo);
			$lo_hoja->write(4,7, "Fecha de Emisión",$lo_titulo);
			$lo_hoja->write(4,8, "Monto Neto",$lo_titulo);
			$lo_hoja->write(4,9, "Monto Retenido",$lo_titulo);
			//------------------------------------------------------------------------------------------------------
			$li_totrow=$io_report->DS->getRowCount("numsol");
			//print "total: ".$li_totrow;die();
			$li_totmonsol=0;
			$li_row=4;
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
				$ls_rif=$io_report->DS->data["rifproben"][$li_i];
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
							$li_row=$li_row+1;
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
								$lo_hoja->write($li_row, 2, $ls_codigopre, $lo_datacenter);
								$lo_hoja->write($li_row, 3, $ls_dencuenta, $lo_datacenter);
								$lo_hoja->write($li_row, 4, $ld_monto1, $lo_datacenter);
							}
							else
							{
								$lo_hoja->write($li_row, 0, $ls_ficha, $lo_datacenter);
								$lo_hoja->write($li_row, 1, $ls_numsol, $lo_datacenter);
								$lo_hoja->write($li_row, 2, $ls_codigopre, $lo_datacenter);
								$lo_hoja->write($li_row, 3, $ls_dencuenta, $lo_datacenter);
								$lo_hoja->write($li_row, 4, $ld_monto1, $lo_datacenter);
								$lo_hoja->write($li_row, 5, $ls_rif, $lo_datacenter);
								$lo_hoja->write($li_row, 6, $ls_nombre, $lo_datacenter);
								$lo_hoja->write($li_row, 7, $ld_fecemisol, $lo_datacenter);
								$lo_hoja->write($li_row, 8, $li_monsol, $lo_datacenter);
								$lo_hoja->write($li_row, 9, $li_monret, $lo_datacenter);

								$ls_cambia++;
							}
							
						}
					}
				} ///////////////////////////////////// FIN Cuenta de Gastos /////////////////////
				else
				{
					$li_s++;
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 0, $ls_ficha, $lo_datacenter);
					$lo_hoja->write($li_row, 1, $ls_numsol, $lo_datacenter);
					$lo_hoja->write($li_row, 2, $ls_codigopre, $lo_datacenter);
					$lo_hoja->write($li_row, 3, $ls_dencuenta, $lo_datacenter);
					$lo_hoja->write($li_row, 4, $ld_monto1, $lo_datacenter);
					$lo_hoja->write($li_row, 6, $ls_nombre, $lo_datacenter);
					$lo_hoja->write($li_row, 7, $ld_fecemisol, $lo_datacenter);
					$lo_hoja->write($li_row, 8, $li_monsol, $lo_datacenter);
					$lo_hoja->write($li_row, 9, $ld_monret, $lo_datacenter);
				}
			}
			$li_totmonsol=number_format($li_totmonsol,2,",",".");
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			//print "por aqui";die();
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"ordenes_pago.xls\"");
			header("Content-Disposition: inline; filename=\"ordenes_pago.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);		
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
			unset($io_pdf);
		}
		
	}

?>
