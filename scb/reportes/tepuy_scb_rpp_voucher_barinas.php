<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;	
		print "close();";
		print "</script>";		
	}	
	$x_pos=0;//mientras mas grande el numero, mas a la derecha.
	$y_pos=-1;//Mientras mas peque�o el numero, mas alto.
	$ls_directorio="cheque_configurable";
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//-------------------------------------------------------------------------------------------------
	function uf_inicializar_variables()
	{
		global $valores;
		global $ls_directorio;
		global $ls_archivo;	
		global $li_medidas;	
		if(!file_exists ($ls_directorio))
		{
			$lb_exito=mkdir($ls_directorio,0777);
			if(!$lb_exito)
			{
				print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			}
		}
		
		if((!file_exists ($ls_archivo)) || (filesize($ls_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($ls_archivo, "w");			
				$ls_contenido="138.00-6.00-32.00-24.00-32.00-28.00-32.00-34.00-32.00-43.00-77.00-43.00-137.00-65.00-131.00-70.00";
				fwrite($archivo,$ls_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($ls_archivo)) && (filesize($ls_archivo)>0))
		{
			$archivo = fopen($ls_archivo, "r");
			$contenido = fread($archivo, filesize($ls_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$li_medidas)
			{
				$archivo = fopen($ls_archivo, "w");
				fclose($archivo);			
				print "<script>";
				print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				print "close();";
				print "</script>";
			}
		}
		else
		{
			print "<script>";
			print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
			print "close();";
			print "</script>";
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf,$x,$ls_imprimocheque)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		//if($valores[1]==0.01){$valores[1]=-4.5;}
		//Imprimo el monto
		if($ls_imprimocheque>0){$io_pdf->add_texto($valores[0],$valores[1],14,"<b>***".$ldec_monto."***</b>");}
		$ls_monto_cortado=wordwrap($ls_monto,65,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			if($ls_imprimocheque>0){$io_pdf->add_texto($valores[4],$valores[5],11,"<b>$la_arreglo[0]</b>");}
		if(array_key_exists(1,$la_arreglo))
			if($ls_imprimocheque>0){$io_pdf->add_texto($valores[6],$valores[7],11,"<b>$la_arreglo[1]</b>");}
		//Beneficiario del Cheque
		if($ls_imprimocheque>0){$io_pdf->add_texto($valores[2],$valores[3],12,"<b>***$ls_nomproben***</b>");}
		//$ls_anio=substr($ls_fecha,-4);
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		if($ls_imprimocheque>0){$io_pdf->add_texto($valores[8],$valores[9],11,"<b>$ls_fecha_corta</b>");}
		if($ls_imprimocheque>0){$io_pdf->add_texto($valores[10],$valores[11],11,"<b>$ls_anio</b>");}
		//Informacion de Caducidad	
      //  $io_pdf->add_texto($valores[14],$valores[15],9,"<b>CADUCA A LOS 90 DIAS</b>");
		//No Endosable
		if($ls_imprimocheque>0){$io_pdf->add_texto($valores[12],$valores[13],12,"<b>NO ENDOSABLE</b>");}
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_nomproben,$ls_fecmov,$ls_conmov,$as_chevau,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheque
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// AQUI MODIFICAS DESCRIPCION, NUMERO DE CHEQUE Y BANCO

	//	$ls_conmov = wordwrap($ls_conmov,75);
	//	$io_pdf->add_texto(12,84,11,"<b>$ls_conmov</b>");//Antes 84 para bajar hay que sumar en la segunda columna
		
		$li_pos=153;//Antes 174 para bajar restar
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		$la_data=array(array('cheque'=>'','banco'=>''),
					   array('cheque'=>"Cheque Nro. "."<b>$ls_numdoc</b>",'banco'=>"    "."<b>$ls_nomban </b>"."<b>".substr($ls_ctaban,-4)."</b>"));
		$la_columna=array('cheque'=>'','banco'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' =>10, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>300, // Orientaci�n de la tabla
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570,
						 'cols'=>array('cheque'=>array('justification'=>'left','width'=>240),
						 'banco'=>array('justification'=>'left','width'=>290))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		// ESTA PROXIMA LINEA IMPRIME NUMERO DE VOUCHER //
		//$io_pdf->add_texto(8,240,10,'<b>'.substr($as_chevau,-8).'</b>');//antes 235 ahora 240
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$x_pos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// MODIFICAS LA UBICACION DE LAS CUENTAS CONTABLES
		//$io_pdf->ezSety(435);
		$io_pdf->ezSety(430); //menos para bajar antes 420
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tama�o de Letras
						 'titleFontSize'=>10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						 'colGap'=>1, //Separacion de los caracteres entre las barras de las tablas.
						 'xPos'=>258, // Orientaci�n de la tabla
						 'cols'=>array('scg_cuenta'=>array('justification'=>'center','width'=>150),
			 						   'denctascg'=>array('justification'=>'left','width'=>175),
			 						   'debe'=>array('justification'=>'right','width'=>90),									   
									   'haber'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$la_columnas=array('scg_cuenta'=>'','denctascg'=>'','debe'=>'','haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_totales($ad_montotdeb,$ad_montothab,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		    Acess: private 
		//	    Arguments: $ad_montotdeb : Monto total de los detalles de los D�bitos.
		//                 $ad_montothab : Monto total de los detalles de los Cr�ditos.
		//	   			   io_pdf // Objeto PDF
		//    Description: Imprime la totalizacion de los montos de debitos y los creditos al final del voucher.
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 29/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// UBICACION DE LOS TOTALES SUBIR EN LA 2DA. COLUMNA BAJA LA IMPRESION
		// se ajusto -10 en la segunda
	     
		 $io_pdf->add_texto(128,75,12,'<b>'.number_format($ad_montotdeb,2,',','.').'</b>'); //antes 67 ahora 115
		 $io_pdf->add_texto(160,75,12,'<b>'.number_format($ad_montothab,2,',','.').'</b>'); //antes 67 ahora 116
	}

	function uf_print_firmas($ad_usuario,$ad_contabilidad,$ad_gerente,$ad_presidente,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		    Acess: private 
		//	    Arguments: $ad_montotdeb : Monto total de los detalles de los D�bitos.
		//                 $ad_montothab : Monto total de los detalles de los Cr�ditos.
		//	   			   io_pdf // Objeto PDF
		//    Description: Imprime la totalizacion de los montos de debitos y los creditos al final del voucher.
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 29/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	     // con menos en la segunda columna sube
		 //$io_pdf->add_texto(3,84,6,$ad_sql);
		$io_pdf->add_texto(3,82,10,'<b>'.$ad_usuario.'</b>'); // antes la 2=72
		$io_pdf->add_texto(26,76,10,'<b>'.$ad_gerente.'</b>'); // antes la 2=66
		$io_pdf->add_texto(65,82,10,'<b>'.$ad_presidente.'</b>'); // antes la 2=72
		$io_pdf->add_texto(29,94,10,'<b>'.$ad_contabilidad.'</b>'); // se le ajusto +10 en la segunda a todos
		//$io_pdf->add_texto(3,94,6,$ad_sql1);
		//$io_pdf->add_texto(3,106,6,$ad_sql2);
	}
	
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//require_once("../../shared/ezpdf/class.ezpdf.php");
	 uf_inicializar_variables();
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	$in=new tepuy_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	require_once("tepuy_scb_report.php");
	$class_report=new tepuy_scb_report($con);
	$io_funciones=new class_funciones();				
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversi�n de numeros a letras.
	require_once("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_presidente=$_SESSION["la_empresa"]["nomrep"];
	$ls_impcheque=$_GET["impche"];
	if($ls_impcheque==''){$ls_impcheque=0;}
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_chevau=$_GET["chevau"];
	$ls_codope=$_GET["codope"];
	$ls_usuario="";
	//$ls_gerente="Lcda. Liliana Montilla";
	//$ls_gerente=" Lcda. Maria E. Mejia";
	//$ls_contabilidad="Lcda. Inghaid Garcia";
		

	$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$class_report->SQL->begin_transaction();
	$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	if(!$lb_valido)
	{
		print "Error al actualizar";
		$class_report->is_msg_error;	
		$class_report->SQL->rollback();
	}
	else
	{
		$class_report->SQL->commit();
	}
	$ds_voucher->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF TAMA�O CARTA
	//$io_pdf=new class_pdf('A4','portrait'); // Instancia de la clase PDF TIPO A4
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Courier.afm'); // Seleccionamos el tipo de letra	
	//$io_pdf->set_margenes(0,0,$x_pos,0);
	$io_pdf->set_margenes(-1.8,0,$x_pos,0); //el -1.8 margen Superior		
	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacci�n
	$thisPageNum=$io_pdf->ezPageCount;
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		unset($la_data);
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		$ls_numdoc=$ds_voucher->data["numdoc"][$li_i];
		$ls_codban=$ds_voucher->data["codban"][$li_i];
		$ls_codusu=$ds_voucher->data["codusu"][$li_i];
		$ls_nomban=$class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		$ls_nomusu=$class_report->uf_select_data($io_sql,"SELECT * FROM sss_usuarios WHERE codusu ='".$ls_codusu."' AND codemp='".$ls_codemp."'","nomusu");
		$ls_apeusu=$class_report->uf_select_data($io_sql,"SELECT * FROM sss_usuarios WHERE codusu ='".$ls_codusu."' AND codemp='".$ls_codemp."'","apeusu");
		$ls_usuario=$ls_nomusu." ".$ls_apeusu;
		$ls_ctaban=$ds_voucher->data["ctaban"][$li_i];
		$ls_chevau=$ds_voucher->data["chevau"][$li_i];
		$ls_tipodestino=$ds_voucher->data["tipo_destino"][$li_i];
		$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ls_nomproben=$ds_voucher->data["nomproben"][$li_i];
		$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$ls_conmov=$ds_voucher->getValue("conmov",$li_i);
		$ldec_monret=$ds_voucher->getValue("monret",$li_i);
		$ldec_monto=$ds_voucher->getValue("monto",$li_i);
		$ldec_total=$ldec_monto-$ldec_monret;
		$ls_monto=$numalet->uf_convertir_letra($ldec_total,'','');
		$io_encabezado=$io_pdf->openObject();
		uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos,$ls_impcheque); // Imprimimos el encabezado de la p�gina
		uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_nomproben,$ld_fecmov,$ls_conmov,$ls_chevau,$io_pdf); // Imprimimos la cabecera del registro
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');


		$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		$hay_partida=$ds_dt_spg->getRowCount("spg_cuenta");
		$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_tipodestino,$hay_partida); // Obtenemos el detalle del reporte
		//$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		$la_campos=array("scg_cuenta");
		$la_monto=array("monto");
		$ds_dt_scg->group_by($la_campos,$la_monto,"scg_cuenta");
		$la_campos=array("spg_cuenta","estpro");
		$ds_dt_spg->group_by($la_campos,$la_monto,"spg_cuenta");
		$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
		$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
		$la_contable = array();
		$ld_montotdeb = $ld_montothab = 0;
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
		if($li_totrow_det>=$li_totrow_spg)
		{
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
				$ls_scg_cuenta = substr($ls_scg_cuenta,0,1).'-'.substr($ls_scg_cuenta,1,2).'-'.substr($ls_scg_cuenta,3,2).'-'.substr($ls_scg_cuenta,5,2).'-'.substr($ls_scg_cuenta,7,2).'-'.substr($ls_scg_cuenta,9,4);
				$ls_denctascg  = $ds_dt_scg->data["denominacion"][$li_s];
				$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
				$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
				if($ls_debhab=='D')
				{
					$ld_montotdeb += $ldec_monto;
					$ldec_mondeb=number_format($ldec_monto,2,",",".");
					$ldec_monhab=" ";
				}
				else
				{
					$ld_montothab += $ldec_monto;
					$ldec_monhab=number_format($ldec_monto,2,",",".");
					$ldec_mondeb=" ";
				}
				if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
				{
					if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					{
						$ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
						$ls_denctaspg   = $ds_dt_spg->getValue("denominacion",$li_s);	
						$ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
						$ls_codestpro1  = substr($ls_estpro,14,6);
						$ls_codestpro2  = substr($ls_estpro,21,6);
						$ls_estpro      = $ls_codestpro1.'-'.$ls_codestpro2;
						$ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					    $ls_denctaspg = "";
					}
				}
				else
				{
					$ls_cuentaspg=" ";	
					$ls_estpro=" ";	  
					$ldec_monto_spg=" ";
					$ls_denctaspg = "";
				}
				$la_data[$li_s]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>"<b>$ls_scg_cuenta</b>",'denctascg'=>"<b>$ls_denctascg</b>",'debe'=>"<b>$ldec_mondeb</b>",'haber'=>"<b>$ldec_monhab</b>");
			}
		}
		
		if($li_totrow_spg>$li_totrow_det)
		{
			for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
			{
				if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
				{
					if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
					{
						$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
						$ls_scg_cuenta = substr($ls_scg_cuenta,0,1).'-'.substr($ls_scg_cuenta,1,2).'-'.substr($ls_scg_cuenta,3,2).'-'.substr($ls_scg_cuenta,5,2).'-'.substr($ls_scg_cuenta,7,2).'-'.substr($ls_scg_cuenta,9,3);
						$ls_denctascg  = $ds_dt_scg->data["denominacion"][$li_s];
						$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
						$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
						if($ls_debhab=='D')
						{
							$ld_montotdeb += $ldec_monto;
							$ldec_mondeb=number_format($ldec_monto,2,",",".");
							$ldec_monhab=" ";
						}
						else
						{
							$ld_montothab += $ldec_monto;
							$ldec_monhab=number_format($ldec_monto,2,",",".");
							$ldec_mondeb=" ";
						}
					}
					else
					{
						$ls_scg_cuenta="";
						$ls_denctascg="";
						$ls_debhab = "";
						$ldec_monto= "";
						$ldec_mondeb="";
						$ldec_monhab="";					
					}
				}
				else
				{
					$ls_scg_cuenta="";
					$ls_denctascg ="";
					$ls_debhab = "";
					$ldec_monto= "";
					$ldec_mondeb="";
					$ldec_monhab="";					
				}
				if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
				{
					if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					{
						$ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
						$ls_denctaspg   = $ds_dt_spg->getValue("denominacion",$li_s);	
						$ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
						$ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
				}
				else
				{
					$ls_cuentaspg=" ";	
					$ls_estpro=" ";	  
					$ldec_monto_spg=" ";
				}
				$la_data[$li_s]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>"<b>$ls_scg_cuenta</b>",'denctascg'=>"<b>$ls_denctascg.</b>",'debe'=>"<b>$ldec_mondeb</b>",'haber'=>"<b>$ldec_monhab</b>");
			}
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(empty($la_data))
		{
			$ls_cuentaspg='';
			$ls_estpro='';
			$ldec_monto_spg='';
			$ls_scg_cuenta='';
			$ls_denctascg="";
			$ldec_mondeb='';
			$ldec_monhab='';
			$la_data[1]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			$la_data[2]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			$la_data[3]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
		}
		//codigo para unir las cuentas iguales
		$la_dataaux=array();
		for($li_k=1;$li_k<=count($la_data);$li_k++)
		{
			$lb_existe=false;
			$li_pos=0;
			for($li_l=1;$li_l<=count($la_dataaux);$li_l++)
			{
				if(($la_data[$li_k]["spg_cuenta"]==$la_dataaux[$li_l]["spg_cuenta"])
					&& ($la_data[$li_k]["estpro"]==$la_dataaux[$li_l]["estpro"])
					&& ($la_data[$li_k]["scg_cuenta"]==$la_dataaux[$li_l]["scg_cuenta"]))
				{
					$li_pos=$li_i;
					$lb_existe=true;
				}
			}
			if(!$lb_existe)
			{
				$li_index=count($la_dataaux)+1;
				$la_dataaux[$li_index]=$la_data[$li_k];
			}
			else
			{
				
				$ls_monto_spg1   = uf_convertir($la_dataaux[$li_pos]["monto_spg"]);
				$ls_monto_spg2   = uf_convertir($la_data[$li_k]["monto_spg"]);
				$ls_monto_debe1  = uf_convertir($la_dataaux[$li_pos]["debe"]);
				$ls_monto_debe2  = uf_convertir($la_data[$li_k]["debe"]);
				$ls_monto_haber1 = uf_convertir($la_dataaux[$li_pos]["haber"]);
				$ls_monto_haber2 = uf_convertir($la_data[$li_k]["haber"]);
				$la_dataaux[$li_pos]["monto_spg"] = number_format(($ls_monto_spg1 + $ls_monto_spg2),2,",",".");
				if (($ls_monto_debe1 + $ls_monto_debe2) != 0)
					$la_dataaux[$li_pos]["debe"] = number_format(($ls_monto_debe1 + $ls_monto_debe2),2,",",".");
				else
					$la_dataaux[$li_pos]["debe"]="";
				if(($ls_monto_haber1 + $ls_monto_haber2) != 0)
					$la_dataaux[$li_pos]["haber"] = number_format(($ls_monto_haber1 + $ls_monto_haber2),2,",",".");
				else
					$la_dataaux[$li_pos]["haber"]="";
			}
			
		}
		
		$io_pdf->y=190;
		$io_pdf->y=440;	
		$io_pdf->set_margenes(138,70,$x_pos,0);//print_r ($la_dataaux);
		//uf_print_detalle($la_dataaux,$io_pdf,$x_pos); // Imprimimos el detalle 	
	    //uf_print_totales($ld_montotdeb,$ld_montothab,$io_pdf);
	//    uf_print_firmas($ls_usuario,$ls_contabilidad,$ls_gerente,$ls_presidente,$io_pdf,$sql,$sql1,$sql2);
    	    //uf_print_firmas($ls_usuario,$ls_contabilidad,$ls_gerente,$ls_presidente,$io_pdf);
	}
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 
