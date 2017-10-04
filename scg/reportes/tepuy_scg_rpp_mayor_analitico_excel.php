<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","tepuy_scg_r_mayor_analitico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
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

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/tmp", "mayor_analitico.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../class_funciones_scg.php");
		$io_fun_scg=new class_funciones_scg();
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");	
		$in           = new tepuy_include();
		$con          = $in->uf_conectar();
		$io_sql       = new class_sql($con);	
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
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
		$ld_fecdesde=$_GET["fecdes"];
		$ld_fechasta=$_GET["fechas"];
		$ls_cuentadesde=$_GET["cuentadesde"];
		$ls_cuentahasta=$_GET["cuentahasta"];
		if(($ls_cuentadesde=="")&&($ls_cuentahasta==""))
		{
			if($io_report->uf_spg_reporte_select_cuenta($ls_cuentadesde,$ls_cuentahasta))
			{
				//$ls_cuentadesde=$ls_cuentadesde_min;
				//$ls_cuentahasta=$ls_cuentahasta_max;
			} 
		}
		$ls_orden=$_GET["orden"];
		switch ($ls_orden)
		{
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
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_fecha="Desde   ".$ld_fecdesde."   al   ".$ld_fechasta."";
		$ls_titulo="Mayor  Analitico";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data
	$lb_valido=uf_insert_seguridad("<b>Mayor Analítico en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cargar_cabecera_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuentadesde,$ls_cuentahasta,
		                                                          $ls_parm_orden,&$rs_data);
	}
	//---------------------------------------------------------------------------------------------------------------------------
	// Impresión de la información encontrada en caso de que exista
	if($lb_valido===false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
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
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,30);		
		$lo_hoja->set_column(4,4,20);
		$lo_hoja->set_column(5,5,13);
		$lo_hoja->set_column(6,8,30);
		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 3, $ldt_fecha,$lo_encabezado);
		$li_row=0;
	    while($row=$io_sql->fetch_row($rs_data))
		{
			$ls_cuenta=$row["sc_cuenta"];
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
			$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;
			$ld_saldo_ant=number_format($ld_saldo_ant,2,",",".");
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
			$lo_hoja->write($li_row, 1, $as_cuenta,$lo_datacenter);
			$lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, "Saldo Anterior ".$ls_bolivares,$lo_titulo);
			$lo_hoja->write($li_row, 1, $ld_saldo_ant,$lo_dataright);
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, "Procede",$lo_titulo);
			$lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
			$lo_hoja->write($li_row, 2, "Concepto",$lo_titulo);
			$lo_hoja->write($li_row, 3, "Codigo",$lo_titulo);
			$lo_hoja->write($li_row, 4, "Beneficiario",$lo_titulo);
			$lo_hoja->write($li_row, 5, "Documento",$lo_titulo);
			$lo_hoja->write($li_row, 6, "Fecha",$lo_titulo);
			$lo_hoja->write($li_row, 7, "Debe",$lo_titulo);
			$lo_hoja->write($li_row, 8, "Haber",$lo_titulo);
			$lo_hoja->write($li_row, 9, "Saldo Actual",$lo_titulo);
			
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
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 0, $ls_procede,$lo_datacenter);
					$lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
					$lo_hoja->write($li_row, 2, $ls_concepto,$lo_dataleft);
					$lo_hoja->write($li_row, 3, $ls_codproben,$lo_dataleft);
					$lo_hoja->write($li_row, 4, $ls_nombre,$lo_dataleft);
					$lo_hoja->write($li_row, 5, $ls_documento." ",$lo_datacenter);
					$lo_hoja->write($li_row, 6, $ld_fecmov,$lo_datacenter);
					$lo_hoja->write($li_row, 7, $ldec_mondeb,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ldec_monhab,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_saldo_final,$lo_dataright);
				}// while del detalle
				
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
   			    $ld_saldo_final=$ld_saldo;
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 5, "Total ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 6, $ld_totaldebe,$lo_dataright);
				$lo_hoja->write($li_row, 7, $ld_totalhaber,$lo_dataright);
				$lo_hoja->write($li_row, 8, $ld_saldo_final,$lo_dataright);
				$ld_totaldebe=0;
				$ld_totalhaber=0;
			}// if valido
		}
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"mayor_analitico.xls\"");
		header("Content-Disposition: inline; filename=\"mayor_analitico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
?> 