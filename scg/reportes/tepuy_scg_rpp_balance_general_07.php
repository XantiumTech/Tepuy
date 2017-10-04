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
	ini_set('memory_limit','512M');
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
		// Fecha Creaci�n: 11/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","tepuy_scg_r_balance_general.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/class_folder/class_pdf.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	require_once("../../shared/class_folder/class_tepuy_int.php");
	require_once("../../shared/class_folder/class_tepuy_int_scg.php");
	require_once("../../shared/class_folder/class_tepuy_int_spi.php");
	require_once("../../shared/class_folder/class_tepuy_int_spg.php");
	require_once("../class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	require_once("tepuy_scg_class_instructivo07.php");
	$io_report  = new tepuy_scg_class_instructivo07();
	$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	$li_ano=substr($ldt_periodo,0,4);
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_nivel,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/07/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_etiqueta;
		global $ls_meses;
		$io_encabezado=$io_pdf->openObject();// primer cuadro del encabezado
		$io_pdf->saveState();
		$io_pdf->line(20,40,990,40);
		$io_pdf->rectangle(35,500,940,80);
		$io_pdf->addText(40,570,6,"C�DIGO PRESUPUESTARIO DEL ENTE: "); // Agregar el t�tulo
		$io_pdf->addText(40,560,6,"DENOMINACI�N DEL ENTE: ".$_SESSION["la_empresa"]["nombre"]); // Agregar el t�tulo
		$io_pdf->addText(40,550,6,"ORGANO DE ADSCRIPCI�N: ".strtoupper($_SESSION["la_empresa"]["nomorgads"])); // Agregar el t�tulo
		$io_pdf->addText(40,540,6,"PERIODO PRESUPUESTARIO: ".substr($_SESSION["la_empresa"]["periodo"],0,4)); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
		$tm=500-($li_tm/2);
		$io_pdf->addText($tm,515,9,$as_titulo); // Agregar el t�tulo
		
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo1);
		$tm=500-($li_tm/2);
		$io_pdf->addText($tm,505,7,$as_titulo1); // Agregar el t�tulo
		
		$io_pdf->rectangle(35,420,940,80); // segundo cuadro del encabezado		
		
		$io_pdf->line(120,500,120,420);//  Vertical
		$io_pdf->addText(60,430,7,"C�DIGO"); // Agregar el t�tulo
		
		$io_pdf->line(450,500,450,420);//  Vertical
		$io_pdf->addText(250,430,7,"DENOMINACI�N"); // Agregar el t�tulo
		$io_pdf->line(520,500,520,420);//  Vertical
		
		$io_pdf->addText(470,460,7,"SALDO"); // Agregar el t�tulo
		$io_pdf->addText(460,450,7,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->addText(470,440,7,"REAL A�O"); // Agregar el t�tulo
		$io_pdf->addText(470,430,7,"ANTERIOR"); // Agregar el t�tulo
		
		$io_pdf->line(590,500,590,420);//  Vertical
		$io_pdf->addText(530,450,7,"SALDO"); // Agregar el t�tulo
		$io_pdf->addText(530,440,7,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->addText(530,430,7,"APROBADO"); // Agregar el t�tulo
		
		$io_pdf->line(660,500,660,420);//  Vertical
		$io_pdf->addText(600,450,7,"SALDO"); // Agregar el t�tulo
		$io_pdf->addText(600,440,7,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->addText(600,430,7,"MODIFICADO"); // Agregar el t�tulo
		
		$io_pdf->line(660,455,900,455);// Horizontal
		$io_pdf->addText(665,440,7,"SALDO"); // Agregar el t�tulo
		$io_pdf->addText(665,430,7,"PROGRAMADO"); // Agregar el t�tulo		
		$io_pdf->line(720,455,720,420);//  Vertical
		
		$io_pdf->addText(730,440,7,"SALDO"); // Agregar el t�tulo
		$io_pdf->addText(730,430,7,"EJECUTADO"); // Agregar el t�tulo		
		$io_pdf->line(780,500,780,420);//  Vertical		
		
		$io_pdf->addText(690,460,7,"TRIMESTRE NRO.  ".$as_nivel); // Agregar el t�tulo
		$io_pdf->addText(790,480,6.5,"VARIACI�N SALDO EJECUTADO - "); // Agregar el t�tulo
		$io_pdf->addText(790,470,6.5,"SALDO PROGRAMADO EN EL"); // Agregar el t�tulo
		$io_pdf->addText(790,460,6.5,"TRIMESTRE NRO.  ". $as_nivel); // Agregar el t�tulo		
		$io_pdf->line(840,455,840,420);//  Vertical
		
		$io_pdf->addText(790,430,7,"ABSOLUTA"); // Agregar el t�tulo
		$io_pdf->addText(845,430,7,"PORCENTUAL"); // Agregar el t�tulo
		$io_pdf->line(900,500,900,420);//  Vertical
		
		$io_pdf->addText(910,460,5.5,"VARIACI�N SALDO"); // Agregar el t�tulo
		$io_pdf->addText(910,450,5.5,"EJECUTADO PERIODO"); // Agregar el t�tulo
		$io_pdf->addText(910,440,5.5,"N, MESES SALDO"); // Agregar el t�tulo
		$io_pdf->addText(910,430,5.5,"PERIODO N-1"); // Agregar el t�tulo
		$io_pdf->line(500,900,500,820);//  Vertical
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 11/07/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>506,
						 'cols'=>array('cuentas'=>array('justification'=>'center','width'=>85), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>330),
									   'saldo_real_ant'=>array('justification'=>'right','width'=>70),
									   'saldo_apro'=>array('justification'=>'right','width'=>70),
									   'saldo_mod'=>array('justification'=>'right','width'=>70),
									   'programado'=>array('justification'=>'right','width'=>60),
									   'saldo_ejecutado'=>array('justification'=>'right','width'=>60),
									   'absoluta'=>array('justification'=>'right','width'=>60),
									   'porcentaje'=>array('justification'=>'right','width'=>60),
									   'variacion'=>array('justification'=>'right','width'=>75))); 
		$la_columnas=array('cuentas'=>' ',
						   'denominacion'=>' ',
						   'saldo_real_ant'=>'',
						   'saldo_apro'=>'',
						   'saldo_mod'=>'',
						   'programado'=>'',
						   'saldo_ejecutado'=>'',						   
						   'absoluta'=>'',
						   'porcentaje'=>'',
						   'variacion'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// end function uf_print_detalle	//------------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ls_denominacion, $saldo_real, $saldo_apro, $saldo_mod, $programado,
	                           $ejecuatdo,$absoluta,$porcentaje, $variacion, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 14/07/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('cuentas'=>' ',
						   'denominacion'=>$ls_denominacion,
						   'saldo_real_ant'=>$saldo_real,
						   'saldo_apro'=>$saldo_apro,
						   'saldo_mod'=>$saldo_mod,
						   'programado'=> $programado,
						   'saldo_ejecutado'=> $ejecuatdo,						   
						   'absoluta'=>$absoluta,
						   'porcentaje'=>$porcentaje,
						   'variacion'=>$variacion);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>506,
						 'cols'=>array('cuentas'=>array('justification'=>'center','width'=>85), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>330),
									   'saldo_real_ant'=>array('justification'=>'right','width'=>70),
									   'saldo_apro'=>array('justification'=>'right','width'=>70),
									   'saldo_mod'=>array('justification'=>'right','width'=>70),
									   'programado'=>array('justification'=>'right','width'=>60),
									   'saldo_ejecutado'=>array('justification'=>'right','width'=>60),
									   'absoluta'=>array('justification'=>'right','width'=>60),
									   'porcentaje'=>array('justification'=>'right','width'=>60),
									   'variacion'=>array('justification'=>'right','width'=>75))); 
		$la_columnas=array('cuentas'=>' ',
						   'denominacion'=>' ',
						   'saldo_real_ant'=>'',
						   'saldo_apro'=>'',
						   'saldo_mod'=>'',
						   'programado'=>'',
						   'saldo_ejecutado'=>'',						   
						   'absoluta'=>'',
						   'porcentaje'=>'',
						   'variacion'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// end function uf_print_totales	
//------------------------------------------------------------------------------------------------------------------------------------
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
	//-----------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	   			
		$ls_titulo="<b>BALANCE GENERAL</b>";
		$ls_titulo1="<b>(En BS.)</b>";
		if (array_key_exists("cmbnivel",$_GET))
		{
			$ls_nivel=$_GET["cmbnivel"];
		}		
		switch($ls_nivel)
		{
			case "1":
				$ls_mesdes=01;
				$ls_meshas=03;
				$ls_trimenstre=1;
	        break;
			case "2":
				$ls_mesdes=04;
				$ls_meshas=06;
				$ls_trimenstre=2;
			break;	
			case "3":
				$ls_mesdes=07;
				$ls_meshas=09;
				$ls_trimenstre=3;
			break;
			case "4":
				$ls_mesdes=10;
				$ls_meshas=12;
				$ls_trimenstre=4;
			break;
		}	
      //--------------------------------------------------------------------------------------------------------------------------------
    // Cargar datastore con los datos del reporte
	$lb_valido=uf_insert_seguridad("<b>Instructivo 07 Comparado Balance General</b>"); // Seguridad de Reporte
	if($lb_valido)
	{   
		$lb_valido=$io_report->uf_balance_general_instructivo($ls_mesdes,$ls_meshas,$ls_nivel); 
	}
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}	
		else// Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new class_pdf('LEGAL','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(6.7,3,3,3); // Configuraci�n de los margenes en cent�metros
			uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_nivel,$io_pdf); // Imprimimos el encabezado de la p�gina
			$io_pdf->ezStartPageNumbers(980,50,10,'','',1); // Insertar el n�mero de p�gina	
			$io_pdf->ezStopPageNumbers(1,1);
			$total_saldo_real_ant3=0;
			$total_saldo_apro3=0;
			$total_saldo_mod3=0;
			$total_saldo_programado3=0;
			$total_saldo_ejecutado3=0;
			$total_var_absoluta3=0;
			$total_var_porcentaje3=0;
			$total_variacion3=0;
			///--------------------------------activos circulante--------------------------------------------------
			$li_tot=$io_report->ds_saldos_activos->getRowCount("sc_cuenta"); 
			$ls_j=0;
			$total_saldo_real_ant=0;
			$total_saldo_apro=0;
			$total_saldo_mod=0;
			$total_saldo_programado=0;
			$total_saldo_ejecutado=0;
			$total_var_absoluta=0;
			$total_var_porcentaje=0;
			$total_variacion=0;
			for($li_i=1;$li_i<=$li_tot;$li_i++)
			{
				$ls_cuenta           = $io_report->ds_saldos_activos->getValue("sc_cuenta",$li_i);	
				$ls_denominacion     = $io_report->ds_saldos_activos->getValue("denominacion",$li_i);
				$ls_saldo_real_ant   = $io_report->ds_saldos_activos->getValue("saldo_real_ant",$li_i);
				$ls_saldo_apro       = $io_report->ds_saldos_activos->getValue("saldo_apro",$li_i);
				$ls_saldo_mod        = $io_report->ds_saldos_activos->getValue("saldo_mod",$li_i);
				$ls_saldo_programado = $io_report->ds_saldos_activos->getValue("saldo_programado",$li_i);
				$ls_saldo_ejecutado  = $io_report->ds_saldos_activos->getValue("saldo_ejecutado",$li_i);
				$ls_var_absoluta     = $io_report->ds_saldos_activos->getValue("absoluta",$li_i);
				$ls_var_porcentaje   = $io_report->ds_saldos_activos->getValue("porcentual",$li_i);
				$ls_variacion        = $io_report->ds_saldos_activos->getValue("varia",$li_i);
				$ls_tipo             = $io_report->ds_saldos_activos->getValue("tipo",$li_i);							
				$la_data[$li_i]=array('cuentas'=>$ls_cuenta,'denominacion'=>$ls_denominacion,
				                      'saldo_real_ant'=>number_format($ls_saldo_real_ant,2,",","."), 
									  'saldo_apro'=>number_format($ls_saldo_apro,2,",","."),
									  'saldo_mod'=>number_format($ls_saldo_mod,2,",","."),
									  'programado'=>number_format($ls_saldo_programado,2,",","."),
									  'saldo_ejecutado'=>number_format($ls_saldo_ejecutado,2,",","."),
									  'absoluta'=>number_format(abs($ls_var_absoluta),2,",","."),
									  'porcentaje'=>number_format($ls_var_porcentaje,2,",","."),
									  'variacion'=>number_format(abs($ls_variacion),2,",","."));
				if ($ls_tipo==1)
				//if (($ls_tipo==1)||($ls_tipo==0))
				{
				 	$total_saldo_real_ant=$total_saldo_real_ant+$ls_saldo_real_ant;
					$total_saldo_apro=$total_saldo_apro+$ls_saldo_apro;
					$total_saldo_mod=$total_saldo_mod+$ls_saldo_mod;
					$total_saldo_programado=$total_saldo_programado+$ls_saldo_programado;
					$total_saldo_ejecutado=$total_saldo_ejecutado+$ls_saldo_ejecutado;
					$total_var_absoluta=$total_var_absoluta+$ls_var_absoluta;
					$total_var_porcentaje=$total_var_porcentaje+$ls_var_porcentaje;
					$total_variacion=$total_variacion+$ls_variacion ;
				}	
				if ($ls_tipo==8)				
				{
				 	$total_saldo_real_ant_T=$total_saldo_real_ant+$ls_saldo_real_ant;
					$total_saldo_apro_T=$total_saldo_apro+$ls_saldo_apro;
					$total_saldo_mod_T=$total_saldo_mod+$ls_saldo_mod;
					$total_saldo_programado_T=$total_saldo_programado+$ls_saldo_programado;					
				}					
			}
			uf_print_detalle($la_data,$io_pdf);
			unset($la_data);			
			unset($io_report->ds_saldos_activos);
			$ls_denominacion="TOTAL ACTIVO CIRCULANTE";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_saldo_real_ant,2,",","."), 
							 number_format($total_saldo_apro,2,",","."), 
							 number_format($total_saldo_mod,2,",","."), 
							 number_format($total_saldo_programado,2,",","."),
	                         number_format($total_saldo_ejecutado,2,",","."),
							 number_format(abs($total_var_absoluta),2,",","."),
							 number_format($total_var_porcentaje,2,",","."),
							 number_format(abs($total_variacion),2,",","."),&$io_pdf);
			//-------------------------------activos no circulantes--------------------------------------------------------	
			$li_tot2=$io_report->ds_saldos_no_activos->getRowCount("sc_cuenta"); 
			$ls_j=0;
			$total_saldo_real_ant2=0;
			$total_saldo_apro2=0;
			$total_saldo_mod2=0;
			$total_saldo_programado2=0;
			$total_saldo_ejecutado2=0;
			$total_var_absoluta2=0;
			$total_var_porcentaje2=0;
			$total_variacion2=0;
			for($li_i=1;$li_i<=$li_tot2;$li_i++)
			{
				$ls_cuenta           = $io_report->ds_saldos_no_activos->getValue("sc_cuenta",$li_i);	
				$ls_denominacion     = $io_report->ds_saldos_no_activos->getValue("denominacion",$li_i);
				$ls_saldo_real_ant   = $io_report->ds_saldos_no_activos->getValue("saldo_real_ant",$li_i);
				$ls_saldo_apro       = $io_report->ds_saldos_no_activos->getValue("saldo_apro",$li_i);
				$ls_saldo_mod        = $io_report->ds_saldos_no_activos->getValue("saldo_mod",$li_i);
				$ls_saldo_programado = $io_report->ds_saldos_no_activos->getValue("saldo_programado",$li_i);
				$ls_saldo_ejecutado  = $io_report->ds_saldos_no_activos->getValue("saldo_ejecutado",$li_i);
				$ls_var_absoluta     = $io_report->ds_saldos_no_activos->getValue("absoluta",$li_i);
				$ls_var_porcentaje   = $io_report->ds_saldos_no_activos->getValue("porcentual",$li_i);
				$ls_variacion        = $io_report->ds_saldos_no_activos->getValue("varia",$li_i);
				$ls_tipo             = $io_report->ds_saldos_no_activos->getValue("tipo",$li_i);	
				$la_data[$li_i]=array('cuentas'=>$ls_cuenta,'denominacion'=>$ls_denominacion,
				                      'saldo_real_ant'=>number_format($ls_saldo_real_ant,2,",","."), 
									  'saldo_apro'=>number_format($ls_saldo_apro,2,",","."),
									  'saldo_mod'=>number_format($ls_saldo_mod,2,",","."),
									  'programado'=>number_format($ls_saldo_programado,2,",","."),
									  'saldo_ejecutado'=>number_format($ls_saldo_ejecutado,2,",","."),
									  'absoluta'=>number_format(abs($ls_var_absoluta),2,",","."),
									  'porcentaje'=>number_format($ls_var_porcentaje,2,",","."),
									  'variacion'=>number_format(abs($ls_variacion),2,",","."));			 			
				//if (($ls_tipo==1)||($ls_tipo==0))
				if ($ls_tipo==1)
				{ 
				 	$total_saldo_real_ant2=$total_saldo_real_ant2+$ls_saldo_real_ant;
					$total_saldo_apro2=$total_saldo_apro2+$ls_saldo_apro;
					$total_saldo_mod2=$total_saldo_mod2+$ls_saldo_mod;
					$total_saldo_programado2=$total_saldo_programado2+$ls_saldo_programado;
					$total_saldo_ejecutado2=$total_saldo_ejecutado2+$ls_saldo_ejecutado;
					$total_var_absoluta2=$total_var_absoluta2+$ls_var_absoluta;
					$total_var_porcentaje2=$total_var_porcentaje2+$ls_var_porcentaje;
					$total_variacion2=$total_variacion2+$ls_variacion ;
				}					
			}
			uf_print_detalle($la_data,$io_pdf);
			unset($la_data);
			unset($io_report->ds_saldos_no_activos);
			$ls_denominacion="TOTAL ACTIVO NO CIRCULANTE";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_saldo_real_ant2,2,",","."),
							 number_format($total_saldo_apro2,2,",","."), 
							 number_format($total_saldo_mod2,2,",","."),
							 number_format($total_saldo_programado2,2,",","."),
							 number_format($total_saldo_ejecutado2,2,",","."),
							 number_format(abs($total_var_absoluta2),2,",","."),
							 number_format($total_var_porcentaje2,2,",","."),
							 number_format(abs($total_variacion2),2,",","."),&$io_pdf);
			//----------------------------------------------------------------------------------------------------------------
			$total_saldo_real_ant3=$total_saldo_real_ant+$total_saldo_real_ant2;
			$total_saldo_apro3=$total_saldo_apro+$total_saldo_apro2;
			$total_saldo_mod3=$total_saldo_mod+$total_saldo_mod2;
			$total_saldo_programado3=$total_saldo_programado+$total_saldo_programado2;
			$total_saldo_ejecutado3=$total_saldo_ejecutado+$total_saldo_ejecutado2;
			$total_var_absoluta3=$total_var_absoluta+$total_var_absoluta2;
			$total_var_porcentaje3=$total_var_porcentaje+$total_var_porcentaje2;
			$total_variacion3=$total_variacion+$total_variacion2;
			$ls_denominacion="      <b>TOTAL ACTIVOS</b>";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_saldo_real_ant_T,2,",","."), 
							 number_format($total_saldo_apro_T,2,",","."), 
							 number_format($total_saldo_mod_T,2,",","."),
							 number_format($total_saldo_programado_T,2,",","."),
							 number_format($total_saldo_ejecutado3,2,",","."),
							 number_format(abs($total_var_absoluta3),2,",","."),
							 number_format($total_var_porcentaje3,2,",","."),
							 number_format(abs($total_variacion3),2,",","."),&$io_pdf);
			//--------------------------------PASIVOS-----------------------------------------------------------------------------			
			$li_tot3=$io_report->ds_saldos_pasivos->getRowCount("sc_cuenta"); 
			$ls_j=0;
			$total_saldo_real_ant4=0;
			$total_saldo_apro4=0;
			$total_saldo_mod4=0;
			$total_saldo_programado4=0;
			$total_saldo_ejecutado4=0;
			$total_var_absoluta4=0;
			$total_var_porcentaje4=0;
			$total_variacion4=0;
			$total_saldo_real_ant5=0;
			$total_saldo_apro5=0;
			$total_saldo_mod5=0;
			$total_saldo_programado5=0;
			$total_saldo_ejecutado5=0;
			$total_var_absoluta5=0;
			$total_var_porcentaje5=0;
			$total_variacion5=0;	
			$total_pasivo1=0;
			$total_pasivo2=0;
			$total_pasivo3=0;
			$total_pasivo4=0;
			$total_pasivo5=0;
			$total_pasivo6=0;
			$total_pasivo7=0;
			$total_pasivo8=0;		
			for($li_i=1;$li_i<=$li_tot3;$li_i++)
			{
				$ls_cuenta           = $io_report->ds_saldos_pasivos->getValue("sc_cuenta",$li_i);	
				$ls_denominacion     = $io_report->ds_saldos_pasivos->getValue("denominacion",$li_i);
				$ls_saldo_real_ant   = $io_report->ds_saldos_pasivos->getValue("saldo_real_ant",$li_i);
				$ls_saldo_apro       = $io_report->ds_saldos_pasivos->getValue("saldo_apro",$li_i);
				$ls_saldo_mod        = $io_report->ds_saldos_pasivos->getValue("saldo_mod",$li_i);
				$ls_saldo_programado = $io_report->ds_saldos_pasivos->getValue("saldo_programado",$li_i);
				$ls_saldo_ejecutado  = $io_report->ds_saldos_pasivos->getValue("saldo_ejecutado",$li_i);
				$ls_var_absoluta     = $io_report->ds_saldos_pasivos->getValue("absoluta",$li_i);
				$ls_var_porcentaje   = $io_report->ds_saldos_pasivos->getValue("porcentual",$li_i);
				$ls_variacion        = $io_report->ds_saldos_pasivos->getValue("varia",$li_i);
				$suma				 = $io_report->ds_saldos_pasivos->getValue("suma",$li_i);
				$ls_tipo			 = $io_report->ds_saldos_pasivos->getValue("tipo",$li_i);
				if ($ls_tipo==2)
				{
					$total_saldo_real_ant4=$ls_saldo_real_ant;
					$total_saldo_apro4=$ls_saldo_apro;
					$total_saldo_mod4=$ls_saldo_mod;
					$total_saldo_programado4=$ls_saldo_programado;
					$total_saldo_ejecutado4=$ls_saldo_ejecutado;
					$total_var_absoluta4=$ls_var_absoluta;
					$total_var_porcentaje4=$ls_var_porcentaje;
					$total_variacion4=$ls_variacion;					
				}
				if ($ls_tipo==1)
				{
					$total_saldo_real_ant5=$ls_saldo_real_ant;
					$total_saldo_apro5=$ls_saldo_apro;
					$total_saldo_mod5=$ls_saldo_mod;
					$total_saldo_programado5=$ls_saldo_programado;
					$total_saldo_ejecutado5=$ls_saldo_ejecutado;
					$total_var_absoluta5=$ls_var_absoluta;
					$total_var_porcentaje5=$ls_var_porcentaje;
					$total_variacion5=$ls_variacion;					
				}
				$la_data[$li_i]=array('cuentas'=>$ls_cuenta,'denominacion'=>$ls_denominacion,
				                      'saldo_real_ant'=>number_format($ls_saldo_real_ant,2,",","."), 
									  'saldo_apro'=>number_format($ls_saldo_apro,2,",","."),
									  'saldo_mod'=>number_format($ls_saldo_mod,2,",","."),
									  'programado'=>number_format($ls_saldo_programado,2,",","."),
									  'saldo_ejecutado'=>number_format($ls_saldo_ejecutado,2,",","."),
									  'absoluta'=>number_format(abs($ls_var_absoluta),2,",","."),
									  'porcentaje'=>number_format($ls_var_porcentaje,2,",","."),
									  'variacion'=>number_format(abs($ls_variacion),2,",","."));						
			}			
			uf_print_detalle($la_data,$io_pdf);
			unset($la_data);			
			unset($io_report->ds_saldos_pasivos);
			$ls_denominacion="      <b>TOTAL  PASIVOS NO CIRCULATES </b>";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_saldo_real_ant4,2,",","."), 
							 number_format($total_saldo_apro4,2,",","."), 
							 number_format($total_saldo_mod4,2,",","."),
							 number_format($total_saldo_programado4,2,",","."),
							 number_format($total_saldo_ejecutado4,2,",","."),
							 number_format(abs($total_var_absoluta4),2,",","."),
							 number_format($total_var_porcentaje4,2,",","."),
							 number_format(abs($total_variacion4),2,",","."),&$io_pdf);				
			
			$ls_denominacion="      <b>TOTAL  PASIVOS  </b>";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_saldo_real_ant5,2,",","."), 
							 number_format($total_saldo_apro5,2,",","."), 
							 number_format($total_saldo_mod5,2,",","."),
							 number_format($total_saldo_programado5,2,",","."),
							 number_format($total_saldo_ejecutado5,2,",","."),
							 number_format(abs($total_var_absoluta5),2,",","."),
							 number_format($total_var_porcentaje5,2,",","."),
							 number_format(abs($total_variacion5),2,",","."),&$io_pdf);		
			//------------------------------PATRIMONIO------------------------------------------------------------------------
			$li_tot4=$io_report->ds_saldos_patrimonio->getRowCount("sc_cuenta"); 
			$ls_j=0;
			$total_saldo_real_ant=0;
			$total_saldo_apro=0;
			$total_saldo_mod=0;
			$total_saldo_programado=0;
			$total_saldo_ejecutado=0;
			$total_var_absoluta=0;
			$total_var_porcentaje=0;
			$total_variacion=0;
			
			$total_patrimonio1=0;
			$total_patrimonio2=0;
			$total_patrimonio3=0;
			$total_patrimonio4=0;
			$total_patrimonio5=0;
			$total_patrimonio6=0;
			$total_patrimonio7=0;
			$total_patrimonio8=0;
			for($li_i=1;$li_i<=$li_tot4;$li_i++)
			{
				$ls_cuenta           = $io_report->ds_saldos_patrimonio->getValue("sc_cuenta",$li_i);	
				$ls_denominacion     = $io_report->ds_saldos_patrimonio->getValue("denominacion",$li_i);
				$ls_saldo_real_ant   = $io_report->ds_saldos_patrimonio->getValue("saldo_real_ant",$li_i);
				$ls_saldo_apro       = $io_report->ds_saldos_patrimonio->getValue("saldo_apro",$li_i);
				$ls_saldo_mod        = $io_report->ds_saldos_patrimonio->getValue("saldo_mod",$li_i);
				$ls_saldo_programado = $io_report->ds_saldos_patrimonio->getValue("saldo_programado",$li_i);
				$ls_saldo_ejecutado  = $io_report->ds_saldos_patrimonio->getValue("saldo_ejecutado",$li_i);
				$ls_var_absoluta     = $io_report->ds_saldos_patrimonio->getValue("absoluta",$li_i);
				$ls_var_porcentaje   = $io_report->ds_saldos_patrimonio->getValue("porcentual",$li_i);
				$ls_variacion        = $io_report->ds_saldos_patrimonio->getValue("varia",$li_i);
				$ls_tipo             = $io_report->ds_saldos_patrimonio->getValue("tipo",$li_i);							
				$la_data[$li_i]=array('cuentas'=>$ls_cuenta,'denominacion'=>$ls_denominacion,
				                      'saldo_real_ant'=>number_format($ls_saldo_real_ant,2,",","."), 
									  'saldo_apro'=>number_format($ls_saldo_apro,2,",","."),
									  'saldo_mod'=>number_format($ls_saldo_mod,2,",","."),
									  'programado'=>number_format($ls_saldo_programado,2,",","."),
									  'saldo_ejecutado'=>number_format($ls_saldo_ejecutado,2,",","."),
									  'absoluta'=>number_format(abs($ls_var_absoluta),2,",","."),
									  'porcentaje'=>number_format($ls_var_porcentaje,2,",","."),
									  'variacion'=>number_format(abs($ls_variacion),2,",","."));
				if ($ls_tipo ==1)
				{
				 	$total_patrimonio1=$total_patrimonio1+$ls_saldo_real_ant;
					$total_patrimonio2=$total_patrimonio2+$ls_saldo_apro;
					$total_patrimonio3=$total_patrimonio3+$ls_saldo_mod;
					$total_patrimonio4=$total_patrimonio4+$ls_saldo_programado;
					$total_patrimonio5=$total_patrimonio5+$ls_saldo_ejecutado;
					$total_patrimonio6=$total_patrimonio6+$ls_var_absoluta;
					$total_patrimonio7=$total_patrimonio7+$ls_var_porcentaje;
					$total_patrimonio8=$total_patrimonio8+$ls_variacion ;
				}		
			}
			uf_print_detalle($la_data,$io_pdf);
			unset($la_data);			
			unset($io_report->ds_saldos_patrimonio);
			$ls_denominacion="        <b>  TOTAL  PATRIMONIO  INSTITUCIONAL </b>";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_patrimonio1,2,",","."), 
							 number_format($total_patrimonio2,2,",","."), 
							 number_format($total_patrimonio3,2,",","."),
							 number_format($total_patrimonio4,2,",","."),
							 number_format($total_patrimonio5,2,",","."),
							 number_format(abs($total_patrimonio6),2,",","."),
							 number_format($total_patrimonio7,2,",","."),
							 number_format(abs($total_patrimonio8),2,",","."),&$io_pdf);
			//-------------------------------------Patrimonio + pasiovos --------------------------------------------------
			$total_global1=0;
			$total_global2=0;
			$total_global3=0;
			$total_global4=0;
			$total_global5=0;
			$total_global6=0;
			$total_global7=0;
			$total_global8=0;
			
			$total_global1=$total_saldo_real_ant5+$total_patrimonio1;
			$total_global2=$total_saldo_apro5+$total_patrimonio2;
			$total_global3=$total_saldo_mod5+$total_patrimonio3;
			$total_global4=$total_saldo_programado5+$total_patrimonio4;
			$total_global5=$total_saldo_ejecutado5+$total_patrimonio5;
			$total_global6=$total_var_absoluta5+$total_patrimonio6;
			$total_global7=$total_var_porcentaje5+$total_patrimonio7;
			$total_global8=$total_variacion5+$total_patrimonio8;
			
			$ls_denominacion="        <b>  TOTAL  PASIVO Y PATRIMONIO  </b>";
			uf_print_totales($ls_denominacion, 
			                 number_format($total_global1,2,",","."), 
							 number_format($total_global2,2,",","."), 
							 number_format($total_global3,2,",","."),
							 number_format($total_global4,2,",","."),
							 number_format($total_global5,2,",","."),
							 number_format(abs($total_global6),2,",","."),
							 number_format($total_global7,2,",","."),
							 number_format(abs($total_global8),2,",","."),&$io_pdf);
			//---------------------------------------------------------------------------------------------------------------
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
		 }//else
		unset($io_report);
	    unset($io_funciones);			
?> 
