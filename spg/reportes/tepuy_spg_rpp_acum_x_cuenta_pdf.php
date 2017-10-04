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
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');

//------------------------------------------------------------------------------------------------------------------------------
		require_once ("tepuy_spg_class_tcpdf.php");
		require_once("tepuy_spg_funciones_reportes.php");
		$io_function_report = new tepuy_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_nombrearchivo="acumulado_por_cuentas.txt";
		$lo_archivo=@fopen("$ls_nombrearchivo","a+");
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		$ls_tipoformato=$_GET["tipoformato"];
		$ls_codfuefindes=$_GET["txtcodfuefindes"];
		$ls_codfuefinhas=$_GET["txtcodfuefinhas"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		 if($ls_tipoformato==1)
		 {
			require_once("tepuy_spg_reporte_bsf.php");
			$io_report = new tepuy_spg_reporte_bsf();
		 }
		 else
		 {
			require_once("tepuy_spg_reporte.php");
		    $io_report = new tepuy_spg_reporte();
		 }	
		 	
		 require_once("../../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
                      
		 $io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		 
//------------------------------------------------------------------------------------------------------------------------------		
	
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max))
			  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			
			if(($ls_codestpro1_min=='**') ||($ls_codestpro1_min==''))
			{
				$ls_codestpro1_min='';
			}
			else
			{
			    $ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
			}
			if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
			{
				$ls_codestpro2_min='';
			}
			else
			{
				$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
			
			}
			if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
			{
				$ls_codestpro3_min='';
			}
			else
			{
			
				$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
			}
			if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
			{
				$ls_codestpro4_min='';
			}
			else
			{
				$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
	
			
			}
			if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
			{
				$ls_codestpro5_min='';
			}else
			{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
			}
			
			
			if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
			{
				$ls_codestpro1h_max='';
			}
			else
			{
				$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
			}
			if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
			{
				$ls_codestpro2h_max='';
			}else
			{
				$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
			}
			if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
			{
				$ls_codestpro3h_max='';
			}else
			{
				$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
			}
			if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
			{
				$ls_codestpro4h_max='';
			}else
			{
				$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
			}
			if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
			{
				$ls_codestpro5h_max='';
			}else
			{
				$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
			}
			
			if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min=="")||($ls_codestpro4_min=="")||($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,$ls_codestpro3_min,$ls_codestpro4_min,$ls_codestpro5_min))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,$ls_codestpro3h_max,$ls_codestpro4h_max,$ls_codestpro5h_max))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}	
		$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
		$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
		$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
		$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
		$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
		
		$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
		$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
		$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
		$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
		
		$ls_cmbmesdes = $_GET["cmbmesdes"];
		$ldt_fecini=$li_ano."-".$ls_cmbmesdes."-01";
		$ldt_fecini_rep="01/".$ls_cmbmesdes."/".$li_ano;
		$ls_cmbmeshas = $_GET["cmbmeshas"];
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $ls_cmbnivel="1";
		}
		else
		{
          $ls_cmbnivel=$cmbnivel;
		}
        $ls_subniv=$_GET["checksubniv"];
		if($ls_subniv==1)
		{
		  $lb_subniv=true;
		}
		else
		{
		  $lb_subniv=false;
		}
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		if($li_estmodest==1)
		{
			$ls_programatica_desde1=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_programatica_hasta1=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		}
		else
		{
			$ls_programatica_desde1=substr($ls_codestpro1,-2).substr($ls_codestpro2,-2).substr($ls_codestpro3,-2).substr($ls_codestpro4,-2).substr($ls_codestpro5,-2);
			$ls_programatica_hasta1=substr($ls_codestpro1h,-2).substr($ls_codestpro2h,-2).substr($ls_codestpro3h,-2).substr($ls_codestpro4h,-2).substr($ls_codestpro5h,-2);
		}
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
		
	    $ls_codfuefindes=$_GET["txtcodfuefindes"];
	    $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
		
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Acumulado por Cuentas desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta;
		$io_function_report->uf_load_seguridad_reporte("SPG","tepuy_spg_r_acum_x_cuentas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
			$ls_titulo="   ACUMULADO POR CUENTAS DESDE FECHA  ".$ldt_fecini_rep."  HASTA  ".$fecfin;  
			$ls_titulo1="  DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA ".$ls_programatica_hasta1;  
    //------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=$io_report->uf_spg_reporte_acumulado_cuentas2($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                        $ls_codestpro4h,$ls_codestpro5h,$ldt_fecini,$ldt_fecfin,$ls_cmbnivel,
															$lb_subniv,$ai_MenorNivel,$ls_cuentades,$ls_cuentahas,
															$ls_codfuefindes,$ls_codfuefinhas,$rs_data,$ls_rango_estructura);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		error_reporting(E_ALL);
		set_time_limit(3600);
		$io_tcpdf= new tepuy_spg_class_tcpdf ("L", PDF_UNIT, "legal", true);
		$io_tcpdf->AliasNbPages();		
		$io_tcpdf->SetHeaderData($_SESSION["ls_logo"], 45, date("d/m/Y"), date("h:i a"));
		$io_tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$io_tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$io_tcpdf->SetMargins(3, PDF_MARGIN_TOP,3);
		$io_tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$io_tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$io_tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$io_tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->AddPage();
		$io_tcpdf->SetFont("helvetica","B",8);
	 	$io_tcpdf->Cell(0,10,$ls_titulo,0,0,'C');
		$io_tcpdf->Ln(3);
		$io_tcpdf->Cell(0,10,$ls_titulo1,0,0,'C');
		$io_tcpdf->Ln();
		
		$ld_total_asignado=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_monto_actualizado=0;
		$ld_total_compromiso=0;
		$ld_total_precompromiso=0;
		$ld_total_compromiso=0;
		$ld_total_saldo_comprometer=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_por_paga=0;
		$ld_aumento=0;
	    $ld_disminucion=0;
	    $ld_precompromiso=0;
	    $ld_compromiso=0;
	    $ld_causado=0;
	    $ld_pagado=0;
		$ld_monto_actualizado=0;
  	    $ld_saldo_comprometer=0;
		$ld_por_paga=0;
		$lb_valido2=false;			
		$li_tot=$io_report->SQL->num_rows($rs_data);
		$z=0;
		
		while($row=$io_report->SQL->fetch_row($rs_data))
		{
			  
		      $ls_spg_cuenta=$row["spg_cuenta"];
		      $ls_denominacion=trim(utf8_encode($row["denominacion"]));
			  $ls_nivel=$row["nivel"];
			  $ld_asignado=$row["asignado"];		  
			 
			  
			  $lb_valido2=$io_report->uf_spg_reporte_detalle_acumulado_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
			                                                                   $ls_codestpro4,$ls_codestpro5,
																			   $ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                                           $ls_codestpro4h,$ls_codestpro5h,
																			   $ls_spg_cuenta,$ldt_fecfin,$rs_data2);
															
			
			  while($row2=$io_report->SQL->fetch_row($rs_data2))
			  {
				  $ld_aumento=$ld_aumento+$row2["aumento"];
				  $ld_disminucion=$ld_disminucion+$row2["disminucion"];
				  $ld_precompromiso=$ld_precompromiso+$row2["precompromiso"];
				  $ld_compromiso=$ld_compromiso+$row2["compromiso"];
				  $ld_causado=$ld_causado+$row2["causado"];
				  $ld_pagado=$ld_pagado+$row2["pagado"];
				  $ld_monto_actualizado=$ld_asignado+$ld_aumento-$ld_disminucion;
				  $ld_saldo_comprometer=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_precompromiso-$ld_compromiso;
				  $ld_por_paga=$ld_causado-$ld_pagado;
			  	 
			  }
			  
			  
			  
			   if($ls_nivel==1)
			   {  
			  	  $ld_total_asignado=$ld_total_asignado+$ld_asignado;
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
				  $ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_saldo_comprometer;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
			  }
			  
			  $ld_asignado=number_format($ld_asignado,2,",",".");
			  $ld_aumento=number_format($ld_aumento,2,",",".");
			  $ld_disminucion=number_format($ld_disminucion,2,",",".");
			  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
			  $ld_precompromiso=number_format($ld_precompromiso,2,",",".");
			  $ld_compromiso=number_format($ld_compromiso,2,",",".");
			  $ld_saldo_comprometer=number_format($ld_saldo_comprometer,2,",",".");
			  $ld_causado=number_format($ld_causado,2,",",".");
			  $ld_pagado=number_format($ld_pagado,2,",",".");
			  $ld_por_paga=number_format($ld_por_paga,2,",",".");
			  $ls_spg_cuenta=trim($ls_spg_cuenta);
			  $la_data[$z]=array($ls_spg_cuenta,utf8_encode($ls_denominacion),$ld_asignado,$ld_aumento,$ld_disminucion,$ld_monto_actualizado,
								 $ld_precompromiso,$ld_compromiso,$ld_saldo_comprometer,$ld_causado,$ld_pagado,$ld_por_paga);
             
			 $ls_cadena=$ls_spg_cuenta."/".$ls_denominacion."/".$ld_asignado."/".$ld_aumento."/".$ld_disminucion."/".$ld_monto_actualizado."/".$ld_precompromiso."/".$ld_compromiso."/".$ld_saldo_comprometer."/".$ld_saldo_comprometer."/".$ld_causado."/".$ld_pagado."/".$ld_por_paga."\r\n";
			 if ($lo_archivo)			
			 {
				@fwrite($lo_archivo,$ls_cadena);
			 }
			 $ld_asignado=str_replace('.','',$ld_asignado);
			 $ld_asignado=str_replace(',','.',$ld_asignado);		
			 $ld_aumento=str_replace('.','',$ld_aumento);
			 $ld_aumento=str_replace(',','.',$ld_aumento);		
			 $ld_disminucion=str_replace('.','',$ld_disminucion);
			 $ld_disminucion=str_replace(',','.',$ld_disminucion);		
			 $ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
			 $ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);	
			 $ld_precompromiso=str_replace('.','',$ld_precompromiso);
			 $ld_precompromiso=str_replace(',','.',$ld_precompromiso);		
			 $ld_compromiso=str_replace('.','',$ld_compromiso);
			 $ld_compromiso=str_replace(',','.',$ld_compromiso);		
			 $ld_saldo_comprometer=str_replace('.','',$ld_saldo_comprometer);
			 $ld_saldo_comprometer=str_replace(',','.',$ld_saldo_comprometer);		
			 $ld_causado=str_replace('.','',$ld_causado);
			 $ld_causado=str_replace(',','.',$ld_causado);
			 $ld_pagado=str_replace('.','',$ld_pagado);
			 $ld_pagado=str_replace(',','.',$ld_pagado);
			 $ld_por_paga=str_replace('.','',$ld_por_paga);
			 $ld_por_paga=str_replace(',','.',$ld_por_paga);
			 $ld_aumento=0;
			 $ld_disminucion=0;
			 $ld_precompromiso=0;
			 $ld_compromiso=0;
			 $ld_causado=0;
			 $ld_pagado=0;
			 $ld_monto_actualizado=0;
			 $ld_saldo_comprometer=0;
			 $ld_por_paga=0;
			 		
			if($z==($li_tot-1))
			{
				  $ld_total_asignado=number_format($ld_total_asignado,2,",",".");
				  $ld_total_aumento=number_format($ld_total_aumento,2,",",".");
				  $ld_total_disminucion=number_format($ld_total_disminucion,2,",",".");
				  $ld_total_monto_actualizado=number_format($ld_total_monto_actualizado,2,",",".");
				  $ld_total_precompromiso=number_format($ld_total_precompromiso,2,",",".");
				  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
				  $ld_total_saldo_comprometer=number_format($ld_total_saldo_comprometer,2,",",".");
				  $ld_total_causado=number_format($ld_total_causado,2,",",".");
				  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
				  $ld_total_por_paga=number_format($ld_total_por_paga,2,",",".");
		 
				  $la_data_tot[$z]=array('TOTAL Bs.',$ld_total_asignado,$ld_total_aumento,$ld_total_disminucion,
				  						 $ld_total_monto_actualizado,$ld_total_precompromiso,$ld_total_compromiso,
										 $ld_total_saldo_comprometer,$ld_total_causado,
										 $ld_total_pagado,$ld_total_por_paga);
			}//if
			  
			  
			  //$rs_data->MoveNext();	
			  $z=$z+1;
		}//fin del while 
		
		if($lb_valido2)
		{
			$io_tcpdf->uf_print_cabecera_acumulado();
			$io_tcpdf->uf_print_detalle_acumulado($la_data); // Imprimimos el detalle 
			$io_tcpdf->uf_print_total_acumulado($la_data_tot);//Bs		
			unset($la_data);
			unset($la_data_tot);			
			$io_tcpdf->Output("tepuy_spg_rpp_acum_x_cuenta.pdf", "I");	
			unset($io_tcpdf);
		}
		else		
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay nada que reportar');"); 
			    print(" close();");
				print("</script>");
		   }
		
		}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);		
?> 
