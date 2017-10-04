<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_codper, $as_cedper, $as_nomper, $as_apeper, $as_tipo, $ai_subnomina)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_print
		//	Arguments:    as_codper  // Código de Personal
		//				  as_cedper  // Cédula de Pesonal
		//				  as_nomper  // Nombre de Personal
		//				  as_apeper // Apellido de Personal
		//				  as_tipo  // Tipo de Llamada del catálogo
		//				  ai_subnomina  // si tiene sub nómina=1 ó Nó =0
		//	Description:  Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		require_once("tepuy_sno.php");
		$io_sno=new tepuy_sno();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_tipnom=$_SESSION["la_nomina"]["tipnom"];	

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=40>Cédula</td>";
		print "<td width=280>Nombre y Apellido</td>";
		print "<td width=60>Estatus</td>";
		print "<td width=60>Culminación Contrato</td>";
		print "</tr>";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personalnomina.sueper, sno_personalnomina.horper, ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, ".
				"		sno_personalnomina.prouniadm, sno_personalnomina.pagbanper, sno_personalnomina.codban, sno_personalnomina.codcueban, ".
				"		sno_personalnomina.tipcuebanper, sno_personalnomina.codcar, sno_personalnomina.fecingper, sno_personalnomina.staper, ".
				"		sno_personalnomina.cueaboper, sno_personalnomina.fecculcontr, sno_personalnomina.codded, sno_personalnomina.codtipper, ".
				"		sno_personalnomina.quivacper, sno_personalnomina.codtabvac, sno_personalnomina.sueintper, sno_personalnomina.pagefeper, ".
				"		sno_personalnomina.sueproper, sno_personalnomina.codage, sno_personalnomina.fecegrper, sno_personalnomina.fecsusper, ".
				"		sno_personalnomina.cauegrper, sno_personalnomina.codescdoc, sno_personalnomina.codcladoc, sno_personalnomina.codubifis, ".
				"		sno_personalnomina.tipcestic, sno_personalnomina.conjub, sno_personalnomina.catjub, sno_personalnomina.codclavia, ".
				"		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personalnomina.codunirac, sno_personalnomina.pagtaqper, ".
				"		sno_unidadadmin.desuniadm, sno_dedicacion.desded, sno_tipopersonal.destipper, sno_subnomina.dessubnom, sno_personalnomina.grado,".
				"		sno_tablavacacion.dentabvac, sno_escaladocente.desescdoc, sno_clasificaciondocente.descladoc, sno_ubicacionfisica.desubifis, ".
				"		sno_personalnomina.fecascper, ".
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab, ".
				"		(SELECT moncomgra FROM sno_grado ".
				"		  WHERE sno_grado.codemp = sno_personalnomina.codemp ".
				"		    AND sno_grado.codnom = sno_personalnomina.codnom ".
				"		    AND sno_grado.codtab = sno_personalnomina.codtab ".
				"		    AND sno_grado.codpas = sno_personalnomina.codpas ".
				"		    AND sno_grado.codgra = sno_personalnomina.codgra) as compensacion, ".
				"		(SELECT denominacion FROM scg_cuentas ".
				"		   WHERE scg_cuentas.codemp = sno_personalnomina.codemp ".
				"			 AND scg_cuentas.SC_cuenta = sno_personalnomina.cueaboper ".
				"			 AND scg_cuentas.status = 'C') as dencueaboper, ".
				"		(SELECT nomban FROM scb_banco ".
				"		  WHERE scb_banco.codemp = sno_personalnomina.codemp ".
				"			AND scb_banco.codban = sno_personalnomina.codban) as nomban, ".
				"		(SELECT nomage FROM scb_agencias ".
				"		  WHERE scb_agencias.codemp = sno_personalnomina.codemp ".
				"			AND scb_agencias.codban = sno_personalnomina.codban ".
				"			AND scb_agencias.codage = sno_personalnomina.codage) as nomage, ".
				"		(SELECT dencat FROM scv_categorias ".
				"		  WHERE scv_categorias.codemp = sno_personalnomina.codemp ".
				"			AND scv_categorias.codcat = sno_personalnomina.codclavia) as dencat ".
				"  FROM sno_personalnomina, sno_personal, sno_subnomina, sno_unidadadmin, sno_dedicacion, sno_tipopersonal, ".
				"  		sno_tablavacacion, sno_escaladocente, sno_clasificaciondocente, sno_ubicacionfisica ".
				" WHERE sno_personalnomina.codemp = '".$ls_codemp."'".
				"   AND sno_personalnomina.codnom = '".$ls_codnom."' ".
				"   AND sno_personal.codper like '".$as_codper."' ".
				"   AND sno_personal.cedper like '".$as_cedper."' ".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."' ".
				"   AND sno_personal.estper = '1' ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_tablavacacion.codemp ".
				"	AND sno_personalnomina.codtabvac = sno_tablavacacion.codtabvac ".
				"   AND sno_personalnomina.codemp = sno_escaladocente.codemp ".
				"	AND sno_personalnomina.codescdoc = sno_escaladocente.codescdoc ".
				"   AND sno_personalnomina.codemp = sno_clasificaciondocente.codemp ".
				"	AND sno_personalnomina.codescdoc = sno_clasificaciondocente.codescdoc ".
				"	AND sno_personalnomina.codcladoc = sno_clasificaciondocente.codcladoc ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"	AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis ";
		if(($as_tipo=="prestamo")||($as_tipo=="movimientonominas")||($as_tipo=="vacaciondes")||
		   ($as_tipo=="vacacionhas")||($as_tipo=="personaproyecto"))
		{
			// solo para el personal Activo
			$ls_sql=$ls_sql."	AND sno_personalnomina.staper = '1' ";
		}
		$ls_sql=$ls_sql." ORDER BY sno_personal.codper ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_cedper=$row["cedper"];
				$ls_nomper=$row["nomper"]." ".$row["apeper"];
				$ls_estper=$row["staper"];
				$ls_codsubnom=$row["codsubnom"];
				$ls_dessubnom=$row["dessubnom"];
				$ls_codasicar=$row["codasicar"];
				$ls_denasicar=$row["denasicar"];
				$ls_codcar=$row["codcar"];
				$ls_descar=$row["descar"];
				$ls_codtab=$row["codtab"];
				$ls_destab=$row["destab"];
				$ls_codgra=$row["codgra"];
				$ls_codpas=$row["codpas"];
				$li_sueper=$row["sueper"];			
				$li_sueper=$io_fun_nomina->uf_formatonumerico($li_sueper);
				$li_compensacion=$row["compensacion"];			
				$li_compensacion=$io_fun_nomina->uf_formatonumerico($li_compensacion);
				$li_horper=$row["horper"];			
				$li_horper=$io_fun_nomina->uf_formatonumerico($li_horper);
				$li_sueintper=$row["sueintper"];			
				$li_sueintper=$io_fun_nomina->uf_formatonumerico($li_sueintper);
				$li_sueproper=$row["sueproper"];			
				$li_sueproper=$io_fun_nomina->uf_formatonumerico($li_sueproper);
				$ld_fecingper=$io_funciones->uf_formatovalidofecha($row["fecingper"]);				
				$ld_fecculcontr=$io_funciones->uf_formatovalidofecha($row["fecculcontr"]);				
				$ld_fecascper=$io_funciones->uf_formatovalidofecha($row["fecascper"]);				
				$ld_fecingper=$io_funciones->uf_convertirfecmostrar($ld_fecingper);				
				$ld_fecculcontr=$io_funciones->uf_convertirfecmostrar($ld_fecculcontr);				
				$ld_fecascper=$io_funciones->uf_convertirfecmostrar($row["fecascper"]);				
				$ls_coduniadm=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];			
				$ls_desuniadm=$row["desuniadm"];
				$ls_codded=$row["codded"];
				$ls_desded=$row["desded"];
				$ls_codtipper=$row["codtipper"];
				$ls_destipper=$row["destipper"];
				$ls_codtabvac=$row["codtabvac"];
				$ls_dentabvac=$row["dentabvac"];
				$li_pagefeper=$row["pagefeper"];
				$li_pagbanper=$row["pagbanper"];
				$li_pagtaqper=$row["pagtaqper"];
				$ls_codban=$row["codban"];
				$ls_codage=$row["codage"];
				$ls_codcueban=$row["codcueban"];
				$ls_tipcuebanper=$row["tipcuebanper"];
				$ls_tipcestic=$row["tipcestic"];
				$ls_codescdoc=$row["codescdoc"];
				$ls_desescdoc=$row["desescdoc"];
				$ls_codcladoc=$row["codcladoc"];
				$ls_descladoc=$row["descladoc"];
				$ls_codubifis=$row["codubifis"];
				$ls_desubifis=$row["desubifis"];
				$ls_cueaboper=$row["cueaboper"];
				$ls_dencueaboper=$row["dencueaboper"];
				$ls_nomban=$row["nomban"];
				$ls_nomage=$row["nomage"];
				$ls_conjub=$row["conjub"];
				$ls_catjub=$row["catjub"];
				$ls_dencat=$row["dencat"];
				$ls_codclavia=$row["codclavia"];
				$ls_codunirac=$row["codunirac"];
				$ls_grado=$row["grado"];
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="N/A";
						break;
					
					case "1":
						$ls_estper="Activo";
						break;
					
					case "2":
						$ls_estper="Vacaciones";
						break;
						
					case "3":
						$ls_estper="Egresado";
						break;
	
					case "4":
						$ls_estper="Suspendido";
						break;
				}
				$ls_contrato="";
				$ls_clase="";
				if(substr($row["fecculcontr"],0,10)=="1900-01-01")
				{
					$ls_contrato="NO APLICA";
				}
				else
				{
					$ld_feccontrato=$row["fecculcontr"];
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
					$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
					$li_incremento=0;
					switch($_SESSION["la_nomina"]["tippernom"])
					{
						case 0://Semanal
							$li_incremento=7;
							break;
			
						case 1://Quincenal
							$li_incremento=15;
							break;
			
						case 2://Mensual
							$li_incremento=30;
							break;
			
						case 3://Anual
							$li_incremento=365;
							break;
					}
					$ld_fechafinal=$io_sno->uf_suma_fechas($ld_fechasper,$li_incremento);
					if($io_fecha->uf_comparar_fecha($ld_fecdesper,$ld_feccontrato))
					{
						if($io_fecha->uf_comparar_fecha($ld_feccontrato,$ld_fechafinal))
						{
							$ls_contrato=$io_funciones->uf_convertirfecmostrar($row["fecculcontr"]);
							$ls_clase="class=texto-rojo";
						}
						else
						{
							$ld_fechafinal=$io_funciones->uf_convertirfecmostrar($ld_fechafinal);
							$ld_fechafinal=$io_sno->uf_suma_fechas($ld_fechafinal,$li_incremento);
							if($io_fecha->uf_comparar_fecha($ld_feccontrato,$ld_fechafinal))
							{
								$ls_contrato=$io_funciones->uf_convertirfecmostrar($row["fecculcontr"]);
								$ls_clase="class=texto-azul";
							}
							else
							{
								$ls_contrato=$io_funciones->uf_convertirfecmostrar($row["fecculcontr"]);
							}
						}
					}
				}
				switch ($as_tipo)
				{
					case "": // el llamado se hace desde tepuy_sno_d_personalnomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_nomper','$ls_estper','$ls_codasicar','$ls_denasicar',";
						print "'$ls_codcar','$ls_descar','$ls_codtab','$ls_destab','$ls_codgra','$ls_codpas',";
						print "'$li_sueper','$li_horper','$li_sueintper','$li_sueproper','$ld_fecingper','$ld_fecculcontr','$ls_coduniadm',";
						print "'$ls_desuniadm','$ls_codded','$ls_desded','$ls_codtipper','$ls_destipper','$ls_codtabvac','$ls_dentabvac',";
						print "'$li_pagefeper','$li_pagbanper','$ls_codsubnom','$ls_dessubnom','$ls_codban','$ls_codage','$ls_codcueban',";
						print "'$ls_tipcuebanper','$ls_tipcestic','$ls_codescdoc','$ls_codcladoc','$ls_codubifis','$ls_cueaboper',";
						print "'$ls_dencueaboper','$ls_nomban','$ls_nomage','$ls_desescdoc','$ls_descladoc','$ls_desubifis',";
						print "'$ai_subnomina','$li_tipnom','$ls_conjub','$ls_catjub','$ls_codclavia','$ls_dencat','$ls_codunirac','$li_pagtaqper',";
						print "'$li_compensacion','$ld_fecascper','$ls_grado');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;						
	
					case "nomina": // el llamado se hace desde tepuy_sno_d_personalnomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarnomina('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prestamo": // el llamado se hace desde tepuy_sno_p_prestamo.php
						$ld_sueper=$row["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprestamo('$ls_codper','$ls_nomper','$ld_sueper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "catprestamo": // el llamado se hace desde tepuy_sno_cat_prestamo.php
						$ld_sueper=$row["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatprestamo('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppagnomdes": // el llamado se hace desde tepuy_sno_r_pagonomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagnomdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppagnomhas": // el llamado se hace desde tepuy_sno_r_pagonomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagnomhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "cambioestatus": // el llamado se hace desde tepuy_sno_p_personalcambioestatus.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcambioestatus('$ls_codper','$ls_nomper','$ls_estper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prenominades": // el llamado se hace desde tepuy_sno_p_calcularprenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprenominades('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prenominahas": // el llamado se hace desde tepuy_sno_p_calcularprenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprenominahas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "movimientonominas": // el llamado se hace desde tepuy_sno_p_movimientonominas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarmovimientonominas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;

					case "vacaciondes": // el llamado se hace desde tepuy_sno_p_vacacionvencida.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarvacaciondes('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "vacacionhas": // el llamado se hace desde tepuy_sno_p_vacacionvencida.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarvacacionhas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;

					case "catvacacion": // el llamado se hace desde tepuy_sno_cat_vacacionprogramar.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatvacacion('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;	
	
					case "repprenomdes": // el llamado se hace desde tepuy_sno_r_prenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprenomdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repprenomhas": // el llamado se hace desde tepuy_sno_r_prenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprenomhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reprecpagdes": // el llamado se hace desde tepuy_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reprecpaghas": // el llamado se hace desde tepuy_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpaghas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisfirdes": // el llamado se hace desde tepuy_sno_r_listadofirmas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfirdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisfirhas": // el llamado se hace desde tepuy_sno_r_listadofirmas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfirhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppredes": // el llamado se hace desde tepuy_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppredes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repprehas": // el llamado se hace desde tepuy_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprehas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repdetpredes": // el llamado se hace desde tepuy_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetpredes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repdetprehas": // el llamado se hace desde tepuy_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetprehas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "personaproyecto": // el llamado se hace desde tepuy_sno_d_personaproyecto.php
						$ld_sueper=$row["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarpersonaproyecto('$ls_codper','$ls_nomper','$ls_desuniadm');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisprodes": // el llamado se hace desde tepuy_sno_r_listadoproyectospersonal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisprodes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisprohas": // el llamado se hace desde tepuy_sno_r_listadoproyectospersonal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisprohas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisbendes": // el llamado se hace desde tepuy_sno_r_listadobeneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbendes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisbenhas": // el llamado se hace desde tepuy_sno_r_listadobeneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbenhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
		unset($io_fecha);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal N&oacute;mina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Personal N&oacute;mina </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula</div></td>
        <td><div align="left">
          <input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
            <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$li_subnomina=$io_fun_nomina->uf_obtenervalor_get("subnom","0");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_print($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_tipo, $li_subnomina);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,nomper,estper,codasicar,denasicar,codcar,descar,codtab,destab,codgra,codpas,
				 sueper,horper,sueintper,sueproper,fecingper,fecculcontr,coduniadm,desuniadm,codded,desded,codtipper,
				 destipper,codtabvac,dentabvac,pagefeper,pagbanper,codsubnom,dessubnom,codban,codage,codcueban,tipcuebanper,
				 tipcestic,codescdoc,codcladoc,codubifis,cueaboper,dencueaboper,nomban,nomage,desescdoc,descladoc,desubifis,
				 subnomina,tipnom,conjub,catjub,codclavia,dencat,codunirac,pagtaqper,compensacion,fecascper,grado)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.images["personal"].style.visibility="hidden";
    opener.document.form1.txtnomper.value=nomper;
    opener.document.form1.txtestper.value=estper;
	if(opener.document.form1.rac.value=="0")
	{
    	opener.document.form1.txtcodcar.value=codcar;
    	opener.document.form1.txtdescar.value=descar;
		if((tipnom=="3")||(tipnom=="4")) // Obreros Fijos y Contratados
		{
			opener.document.form1.txtgrado.value=grado;
		}
	}
	else
	{
		opener.document.form1.txtcodasicar.value=codasicar;
		opener.document.form1.txtdenasicar.value=denasicar;
		opener.document.form1.txtcodtab.value=codtab;
		opener.document.form1.txtdestab.value=destab;
		opener.document.form1.txtcodgra.value=codgra;
		opener.document.form1.txtcodpas.value=codpas;
	}
	if(tipnom=="7") // Jubilados
	{
		opener.document.form1.cmbconjub.value=conjub;
		opener.document.form1.cmbcatjub.value=catjub;
	}
	opener.document.form1.txtsueper.value=sueper;
	opener.document.form1.txtcompensacion.value=compensacion;
    opener.document.form1.txthorper.value=horper;
    opener.document.form1.txtsueintper.value=sueintper;
    opener.document.form1.txtsueproper.value=sueproper;
    opener.document.form1.txtfecingper.value=fecingper;
    opener.document.form1.txtfecculcontr.value=fecculcontr;
    opener.document.form1.txtcoduniadm.value=coduniadm;
    opener.document.form1.txtdesuniadm.value=desuniadm;
    opener.document.form1.txtcodded.value=codded;
    opener.document.form1.txtdesded.value=desded;
    opener.document.form1.txtcodtipper.value=codtipper;
    opener.document.form1.txtdestipper.value=destipper;
    opener.document.form1.txtcodtabvac.value=codtabvac;
    opener.document.form1.txtdentabvac.value=dentabvac;
    opener.document.form1.txtfecascper.value=fecascper;
	if(subnomina==1)
	{
    	opener.document.form1.txtcodsubnom.value=codsubnom;
    	opener.document.form1.txtdessubnom.value=dessubnom;
	}
    opener.document.form1.txtcodban.value=codban;
    opener.document.form1.txtcodage.value=codage;
    opener.document.form1.txtcodcueban.value=codcueban;
    opener.document.form1.txtcodescdoc.value=codescdoc;
    opener.document.form1.txtdesescdoc.value=desescdoc;
    opener.document.form1.txtcodcladoc.value=codcladoc;
    opener.document.form1.txtdescladoc.value=descladoc;
    opener.document.form1.txtcodubifis.value=codubifis;
    opener.document.form1.txtdesubifis.value=desubifis;
    opener.document.form1.txttipcuebanper.value=tipcuebanper;
    opener.document.form1.cmbtipcuebanper.value=tipcuebanper;
    opener.document.form1.cmbtipcestic.value=tipcestic;
    opener.document.form1.txtcuecon.value=cueaboper;
    opener.document.form1.txtdencuecon.value=dencueaboper;
    opener.document.form1.txtnomban.value=nomban;
	if((opener.document.form1.rac.value=="1")&&(opener.document.form1.codunirac.value=="1"))
	{
	    opener.document.form1.txtcodunirac.value=codunirac;
	}
    opener.document.form1.txtnomage.value=nomage;
	opener.document.form1.txtcodclavia.value=codclavia;
	opener.document.form1.txtcodclavia.readOnly=true;
	opener.document.form1.txtdencat.value=dencat;
	opener.document.form1.txtdencat.readOnly=true;
	opener.document.form1.chkpagefeper.checked=false;
	opener.document.form1.chkpagtaqper.checked=false;
	opener.document.form1.chkpagbanper.checked=false;
	opener.document.images["cuentaabono"].style.visibility="hidden";
	opener.document.form1.cmbtipcuebanper.disabled=true;
	opener.document.form1.txtcodcueban.readOnly=true;
	opener.document.images["banco"].style.visibility="hidden";
	opener.document.images["agencia"].style.visibility="hidden";
	opener.document.images["cuentaabono"].style.visibility="hidden";
	if(pagefeper=="1")
	{
		opener.document.form1.chkpagefeper.checked=true;
		opener.document.images["cuentaabono"].style.visibility="visible";
	}
	if(pagbanper=="1")
	{
		opener.document.form1.chkpagbanper.checked=true;
		opener.document.form1.cmbtipcuebanper.disabled=false;
		opener.document.form1.txtcodcueban.readOnly=false;
		opener.document.images["banco"].style.visibility="visible";
		opener.document.images["agencia"].style.visibility="visible";
		opener.document.images["cuentaabono"].style.visibility="hidden";
	}
	if(pagtaqper=="1")
	{
		opener.document.form1.chkpagtaqper.checked=true;
		opener.document.images["banco"].style.visibility="visible";
	}
	opener.document.form1.existe.value="TRUE";		
	close();
}

function aceptarnomina(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarprestamo(codper,nomper,sueper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtsueper.value=sueper;
	close();
}

function aceptarcatprestamo(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarreppagnomdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreppagnomhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarcambioestatus(codper,nomper,estper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtestactper.value=estper;
	opener.document.form1.txtestactper.readOnly=true;
	close();
}

function aceptarprenominades(codper,nomper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
    opener.document.form1.txtnomperdes.value=nomper;
	opener.document.form1.txtnomperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
    opener.document.form1.txtnomperhas.value="";
	close();
}

function aceptarprenominahas(codper,nomper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		opener.document.form1.txtnomperhas.value=nomper;
		opener.document.form1.txtnomperhas.readOnly=true;
		opener.document.form1.operacion.value="BUSCAR";
		opener.document.form1.action="tepuy_sno_p_calcularprenomina.php";
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarmovimientonominas(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	opener.document.form1.operacion.value="BUSCAR";
	opener.document.form1.action="tepuy_sno_p_movimientonominas.php";
	opener.document.form1.submit();
	close();
}

function aceptarvacaciondes(codper,nomper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
    opener.document.form1.txtnomperdes.value=nomper;
	opener.document.form1.txtnomperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
    opener.document.form1.txtnomperhas.value="";
	close();
}

function aceptarvacacionhas(codper,nomper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		opener.document.form1.txtnomperhas.value=nomper;
		opener.document.form1.txtnomperhas.readOnly=true;
		opener.document.form1.operacion.value="BUSCAR";
		opener.document.form1.action="tepuy_sno_p_vacacionvencida.php";
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarcatvacacion(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarrepprenomdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepprenomhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreprecpagdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreprecpaghas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreplisfirdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisfirhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreppredes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepprehas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarrepdetpredes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepdetprehas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarpersonaproyecto(codper,nomper,desuniadm)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtuniadm.value=desuniadm;
	opener.document.form1.txtuniadm.readOnly=true;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.action="tepuy_sno_d_personaproyecto.php";
	opener.document.form1.submit();
	close();
}

function aceptarreplisprodes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisprohas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreplisbendes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisbenhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="tepuy_sno_cat_personalnomina.php?tipo=<?PHP print $ls_tipo;?>&subnom=<?PHP print $li_subnomina;?>";
  	f.submit();
}
</script>
</html>