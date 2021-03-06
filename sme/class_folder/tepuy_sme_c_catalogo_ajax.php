<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los cat�logos del sistema SEP con la utilizaci�n del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
	session_start();
	//ini_set('display_errors', 1);     
	require_once("class_funciones_sme.php");
	$io_funciones_sme=new class_funciones_sme();
	// Tipo del catalogo que se requiere pintar
	
	$ls_catalogo=$io_funciones_sme->uf_obtenervalor("catalogo","");
 	$ls_cmbtipdes=$io_funciones_sme->uf_obtenervalor("cmbtipdes","");
	$ls_tipdes=substr($ls_cmbtipdes,0,1); // Tipo de Destino T=Trabajador F=Familiar
	$ls_codtar=substr($ls_cmbtipdes,2,2); // Codigo del Tipo de Servicio Medico definido en Tabla=sme_montoseguntipo
	$ls_codnom=substr($ls_cmbtipdes,5,4); // Codigo del Tipo de Servicio Medico definido en Tabla=sme_montoseguntipo
	//print "catalogo: ".$ls_catalogo;
	//print "Tipdes: ".$ls_tipdes;
	//print "Codtar: ".$ls_codtar;
	//print "codnom:".$ls_codnom;
	switch($ls_catalogo)
	{
		case "BIENES":
			uf_print_bienes();
			break;
		case "UNIDADEJECUTORA":
			uf_print_unidadejecutora();
			break;
		case "FUENTEFINANCIAMIENTO":
			uf_print_fuentefinanciamiento();
			break;
		case "PROVEEDOR":
			uf_print_proveedor();
			break;
		case "BENEFICIARIO":
			uf_print_beneficiario($ls_tipdes,$ls_codtar,$ls_codnom);
			break;
		case "CUENTASSPG":
			uf_print_cuentasspg();
			break;
		case "CUENTASCARGOS":
			uf_print_cuentas_cargos();
			break;
		case "SOLICITUD":
			uf_print_solicitud();
			break;
		case "SOLICITUDSME":
			uf_print_solicitudserviciosmedicos();
			break;
		case "SERVICIOS":
			uf_print_servicios();
			break;
		case "CONCEPTOSSME":
			uf_print_conceptos($ls_tipdes,$ls_codtar);
			break;
		case "CONCEPTOSserviciosS":
			uf_print_conceptosservicioss();
			break;
		case "BUSCARSMEPREVIA":
			$beneficiario=$io_funciones_sme->uf_obtenervalor("codigo","");
			$codtar=$io_funciones_sme->uf_obtenervalor("codtar",""); 
			$tipo_trab_fam=$io_funciones_sme->uf_obtenervalor("tipo_trab_fam",""); ; //$ls_cmbtipdes
			$montope=$io_funciones_sme->uf_obtenervalor("montope",""); ; //$montope
			//print "Beneficiarioxxxxxxxxxx: ".$beneficiario." codtar: ".$codtar."tipo_trab_fam: ".$tipo_trab_fam;
			uf_print_servicios_previos($beneficiario,$codtar,$tipo_trab_fam,$montope);
			//uf_print_beneficiario();
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios_previos($as_codprovben,$as_codtar,$tipo_trab_fam,$montope)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_print_servicios_previos($as_codprovben)
	//		   Access: public
	//	    Arguments: 	as_codprovben  // cedula del beneficiario 
	//					as_codtar // Codigo del Servicio Medico
	//					tipo_trab_fam // Trabajador=T o Familiar=F
	//				   aa_seguridad  // arreglo de las variables de seguridad
	//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
	//	  Description: Funcion que busca los beneficiarios del servicio medico
	//					que tengan las personas en fecha previos
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creaci�n: 18/05/2015 		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_sme;
	require_once("../../shared/class_folder/tepuy_include.php");
	$io_include=new tepuy_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	require_once("../../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();		
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
    $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td style='cursor:pointer' title='Ordenar por Numero' align='center' onClick=ue_orden('numsol')>Nro. de Solicitud </td>";
	print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombre1')>Nombre del Trabajador</td>";
	if($tipo_trab_fam=="F")
	{
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombre2')>Nombre del Familiar</td>";
	}
	print "<td style='cursor:pointer' title='Ordenar por Concepto' align='center' onClick=ue_orden('consem')>Concepto</td>";
	print "<td style='cursor:pointer' title='Ordenar por Fecha' align='center' onClick=ue_orden('fecreg')>Fecha</td>";
	print "<td style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
	print "<td style='cursor:pointer' title='Ordenar por Monto Resta' align='center' onClick=ue_orden('montoresta')>Monto Resta</td>";
	print "</tr>";
	//print "tipo_trab_fam: ".$tipo_trab_fam;
	/////////// BUSCA LOS DATOS DEL TRABAJADOR O DEL FAMILIAR /////////
	if($tipo_trab_fam=="F")
	{
		$ls_sql="SELECT CONCAT (TRIM(T1.nomfam),' ',TRIM(T1.apefam)) as nombre2, ".
				" T3.numsol, T3.fecreg, T3.monto, TRIM(T3.consem), CONCAT (TRIM(T2.nomper),' ',TRIM(T2.apeper)) as nombre1, T4.nompro ".
				" FROM sno_familiar as T1, sno_personal as T2, sme_dt_beneficiario as T3, rpc_proveedor as T4 ".
				" WHERE T1.codemp = '".$ls_codemp."' ".
				" AND T3.cedfam = '".$as_codprovben."' ".
				" AND T2.cedper=T3.cedper ".
				" AND T1.cedfam=T3.cedfam ".
				" GROUP BY T1.cedfam ORDER BY T1.cedfam ASC";
				$ls_ced=$ls_cedfam;
	}
	else
	{
		$ls_sql="SELECT CONCAT (TRIM(T1.nomper),' ',TRIM(T1.apeper)) as nombre1, ".
				" T2.numsol, T2.fecreg, T2.monto, TRIM(T2.consem) as consem, T3.nompro ".
				" FROM sno_personal as T1, sme_dt_beneficiario as T2, rpc_proveedor as T3 ".
				" WHERE T1.codemp = '".$ls_codemp."' ".
				" AND T1.cedper = '".$as_codprovben."' ".
				" AND T1.cedper = T2.cedper ".
				" AND T2.cod_pro = T3.cod_pro ".
				" GROUP BY T1.cedper ORDER BY T1.cedper ASC";
				$ls_ced=$ls_cedtra;
	}
	//////////////////////////////////////////////////////////////////
	//print $ls_sql;
	$rs_data=$io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
	}
	else
	{
		while($row=$io_sql->fetch_row($rs_data))
		{
			$ls_numsol=$row["numsol"];
			$ls_monto=$row["monto"];
			$montope=$montope-$ls_monto;
			$ls_consem=$row["consem"];
			$ls_monto=number_format($ls_monto,2,",",".");
			//$montope=number_format($montope,2,",",".");
			$ls_fecreg=$row["fecreg"];
			$ls_fecha=substr($ls_fecreg,8,2)."-".substr($ls_fecreg,5,2)."-".substr($ls_fecreg,0,4);
			$ls_nombre1=$row["nombre1"];
			if($tipo_trab_fam=="F")
			{
				$nombre2=$row["nombre2"];
			}
			print "<tr class=celdas-blancas>";
	//		print "<td><a href=\"javascript: aceptar('$ls_numsol','$ls_nombene','$ls_consol','$ls_fecha,'$ls_monto'');\">".$ls_numsol."</a></td>";
			print "<td>".$ls_numsol."</td>";			
			print "<td>".$ls_nombre1."</td>";
			if($tipo_trab_fam=="F")
			{
				print "<td>".$ls_nombre2."</td>";
			}
			print "<td>".$ls_consem."</td>";
			print "<td>".$ls_fecha."</td>";
			print "<td>".$ls_monto."</td>";
			print "<td>"."<b>".number_format($montope,2,",",".")."</b>"."</td>";
			
			print "</tr>";
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
	}// end function uf_print_servicios_previos


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los bienes
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codart="%".$_POST['codart']."%";
		$ls_denart="%".$_POST['denart']."%";
		$ls_codtipart="%".$_POST['codtipart']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT siv_articulo.codart,siv_articulo.denart,siv_articulo.ultcosart,siv_articulo.codunimed, TRIM(siv_articulo.spg_cuenta) AS spg_cuenta, ".
				"		siv_unidadmedida.denunimed, siv_unidadmedida.unidad, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND siv_articulo.codemp = spg_cuentas.codemp ".
				"			AND siv_articulo.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta, ".
				" 		(SELECT COUNT(codart) ".
                "   	   FROM tepuy_cargos, siv_cargosarticulo ".
                "  		  WHERE siv_cargosarticulo.codemp = siv_articulo.codemp ".
				"	  		AND siv_cargosarticulo.codart = siv_articulo.codart ".
				"    		AND tepuy_cargos.codemp = siv_cargosarticulo.codemp  ".
				"    		AND tepuy_cargos.codcar = siv_cargosarticulo.codcar)  AS totalcargos ".
				"  FROM siv_articulo, siv_unidadmedida ".
				" WHERE siv_articulo.codemp='".$ls_codemp."' ".
				"   AND siv_articulo.codart like '".$ls_codart."' ".
				"   AND siv_articulo.denart like '".$ls_denart."' ".
				"   AND siv_articulo.codtipart like '".$ls_codtipart."' ".
				"	AND siv_articulo.codunimed = siv_unidadmedida.codunimed ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('siv_articulo.codart')>Codigo</td>";
			print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('siv_articulo.denart')>Denominacion</td>";
			print "<td style='cursor:pointer' title='Ordenar por Unidad'       align='center' onClick=ue_orden('siv_unidadmedida.denunimed')>Unidad</td>";
			print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('siv_articulo.spg_cuenta')>Cuenta</td>";
			print "<td></td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codart=$row["codart"];
				$ls_denart=utf8_encode($row["denart"]);
				$li_ultcosart=number_format($row["ultcosart"],2,",",".");
				$ls_codunimed=$row["codunimed"];
				$ls_denunimed=$row["denunimed"];
				$li_unidad=utf8_encode($row["unidad"]);
				$li_totalcargos=$row["totalcargos"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_existecuenta=$row["existecuenta"];
				if($li_existecuenta==0)
				{
					$ls_estilo = "celdas-blancas";
				}
				else
				{
					$ls_estilo = "celdas-azules";
				}
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codart."</td>";
				print "<td align='left'>".$ls_denart."</td>";
				print "<td align='left'>".$ls_denunimed."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codart."','".$ls_denart."','".$ls_denunimed."','".$li_unidad."','".$ls_spg_cuenta."',".
					  "'".$li_ultcosart."','".$li_totalcargos."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_unidadejecutora()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de la unidad ejecutora (Unidad administrativa)
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_coduniadm="%".$_POST["coduniadm"]."%";
		$ls_denuniadm="%".$_POST["denuniadm"]."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('coduniadm')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denuniadm')>Denominacion</td>";
		print "</tr>";
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQL")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SEP','".$ls_logusr."',coduniadm) IN (SELECT CONCAT(codemp,codsis,codusu,substr(codintper,24,33)) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SEP'||'".$ls_logusr."'||coduniadm IN (SELECT codemp||codsis||codusu||substr(codintper,24,33)
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
		}
		
		$ls_sql="SELECT coduniadm, denuniadm, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5  ".
				"  FROM spg_unidadadministrativa ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND coduniadm <>'----------' ".
				"   AND coduniadm like '".$ls_coduniadm."' ".
				"   AND denuniadm like '".$ls_denuniadm."' ".$ls_sql_seguridad." ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";              
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_coduniadm  = trim($row["coduniadm"]);
				$ls_denuniadm  = utf8_encode($row["denuniadm"]);
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_coduniadm','$ls_denuniadm','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_coduniadm."</a></td>";
						print "<td align='left'>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;
					case "APROBACION":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_aprobacion('$ls_coduniadm','$ls_denuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;

					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportedesde('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;

					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportehasta('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;
						
					case "BUSCAR_CAT_SEP":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_catalogo_sep('$ls_coduniadm','$ls_denuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
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
	}// end function uf_print_unidadejecutora
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuentefinanciamiento()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codfuefin')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denfuefin')>Denominacion</td>";
		print "</tr>";
		$ls_sql="SELECT codfuefin, denfuefin ".
				"  FROM tepuy_fuentefinanciamiento ".	
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codfuefin <> '--' ".		
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=utf8_encode($row["denfuefin"]);
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codfuefin','$ls_denfuefin');\">".$ls_codfuefin."</a></td>";
						print "<td align='left'>".$ls_denfuefin."</td>";
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
	}// end function uf_print_fuentefinanciamiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedor()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_nompro="%".$_POST['nompro']."%";
		$ls_dirpro="%".$_POST['dirpro']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nompro')>Nombre</td>";
		print "</tr>";
        $ls_sql="SELECT cod_pro,nompro,sc_cuenta,rifpro".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."' ".
				"   AND nompro like '".$ls_nompro."' ".
				"   AND dirpro like '".$ls_dirpro."' ". 
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=utf8_encode($row["nompro"]);
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_rifpro=$row["rifpro"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
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
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_beneficiario($as_tipdes,$as_codtar,$as_codnom)
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_beneficiario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de beneficiarios
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_cedbene="%".$_POST['cedbene']."%";
		$ls_nombene="%".$_POST['nombene']."%";
		$ls_apebene="%".$_POST['apebene']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		//print "orden: ".$ls_orden." campoorden: ".$ls_campoorden;
		$ls_tipo=$_POST['tipo'];
		//print "tipdes= ".$as_tipdes." codtar= ".$as_codtar;
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Cedula' align='center' onClick=ue_orden('ced_bene')>Cedula </td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombene')>Nombre</td>";
		print "</tr>";
		if($as_tipdes==="T")
		{
	/*	$ls_sql="SELECT T1.ced_bene, T1.nombene, T1.apebene ".
				"  FROM rpc_beneficiario as T1, sno_personal as T2 ".
				" WHERE T1.codemp='".$ls_codemp."' ".
				"   AND T1.ced_bene <> '----------' ".
				"   AND T1.ced_bene like '".$ls_cedbene."' ".
				"   AND T1.ced_bene=T2.cedper ".
				"   AND T1.nombene like '".$ls_nombene."' ".
				"   AND T1.apebene like '".$ls_apebene."' ".
				" ORDER BY T1.".$ls_campoorden." ".$ls_orden.""; */
		$ls_sql="SELECT T1.cedper, T1.nomper, T1.apeper, T2.codnom ".
				"  FROM sno_personal as T1, sno_personalnomina as T2 ".
				" WHERE T1.codemp='".$ls_codemp."' ".
				"   AND T1.cedper <> '----------' ".
				"   AND T1.cedper like '".$ls_cedbene."' ".
				"   AND T1.codper=T2.codper ".
				"   AND T1.nomper like '".$ls_nombene."' ".
				"   AND T1.apeper like '".$ls_apebene."' ".
				"   AND T2.codnom like '".$as_codnom."' ".
				" ORDER BY T1.".$ls_campoorden." ".$ls_orden."";

		}
		else
		{
		$ls_sql="SELECT T1.cedfam as cedper, T1.nomfam as nomper, T1.apefam as apeper, T3.codnom ".
				"  FROM sno_familiar as T1, sno_personal as T2, sno_personalnomina as T3 ".
				" WHERE T1.codemp='".$ls_codemp."' ".
				"   AND T1.cedfam <> '----------' ".
				"   AND T1.cedfam like '".$ls_cedbene."' ".
				"   AND T1.codper=T2.codper ".
				"   AND T2.codper=T3.codper ".
				"   AND T1.nomfam like '".$ls_nombene."' ".
				"   AND T1.apefam like '".$ls_apebene."' ".
				"   AND T3.codnom like '".$as_codnom."' ".
				" ORDER BY T1.cedfam ".$ls_orden."";
		}
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["cedper"];
				$ls_nombene=utf8_encode($row["nomper"]." ".$row["apeper"]);
				//print "Tipo: ".$ls_tipo;
				switch ($ls_tipo)
				{
					case "": // cuando busca datos desde la solicitud
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportedesde('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportehasta('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					case "CMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_cmpretencion('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
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
	}// end function uf_print_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasspg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasspg
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$ls_spgcuenta=$_POST['spgcuenta'];
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		}
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria ";
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Program�tica ";
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Programatica' align='center' onClick=ue_orden('codpro')>".$ls_titulo."</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Disponible'   align='center' onClick=ue_orden('disponible')>Disponible</td>";
		print "<td></td>";
		print "</tr>";
		$ls_cuentas="";
		$ls_tipocuenta="";
		switch($ls_tipo)
		{
			case "B": // si es de bienes
				$ls_sql="SELECT soc_gastos AS cuenta ".
						"  FROM tepuy_empresa ".
						" WHERE codemp = '".$ls_codemp."' ";
				break;
			case "S": // si es de Servicios
				$ls_sql="SELECT soc_servic AS cuenta ".
						"  FROM tepuy_empresa ".
						" WHERE codemp = '".$ls_codemp."' ";
				break;
		}
		if($ls_tipo!="O")
		{
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
					$ls_cuentas=$row["cuenta"];
				}			
				$la_spg_cuenta=split(",",$ls_cuentas);
				$li_total=count($la_spg_cuenta);
				for($li_i=0;$li_i<$li_total;$li_i++)
				{
					if($la_spg_cuenta[$li_i]!="")
					{		
						if($li_i==0)
						{
							$ls_tipocuenta=$ls_tipocuenta." SUBSTR(TRIM(spg_cuenta),1,3) = '".trim($la_spg_cuenta[$li_i])."' ";
						}
						else
						{
							$ls_tipocuenta=$ls_tipocuenta."    OR SUBSTR(TRIM(spg_cuenta),1,3) = '".trim($la_spg_cuenta[$li_i])."'";
						}
					}
				}															
			}
		}
		if($ls_tipocuenta=="")
		{
			$ls_tipocuenta=" spg_cuenta like '%%' ";
		}
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3,codestpro4,codestpro5,status, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
			    "  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"   AND (".$ls_tipocuenta.")".
				"	AND spg_cuenta like '".$ls_spgcuenta."%' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				"   AND status ='C'  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_denominacion=utf8_encode($row["denominacion"]);
				$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_disponible=number_format($row["disponible"],2,",",".");
				if($ls_codestpro==$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5)
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}
				$ls_codest1=$row["codestpro1"];
				$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
				$ls_codest2=$row["codestpro2"];
				$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
				$ls_codest3=$row["codestpro3"];
				$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
				$ls_codest4=$row["codestpro4"];
				$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
				$ls_codest5=$row["codestpro5"];
				$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
				$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				$ls_programatica1=substr($ls_codest1,18,2).' - '.substr($ls_codest2,4,2).' - '.substr($ls_codest3,1,2);
				// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
				$ls_spg_anterior=$ls_spg_cuenta;
				$ls_spg_cuenta1=substr($ls_spg_cuenta,0,7);
				if(substr($ls_spg_anterior,9,4)<>"0000") //AUXILIAR BLANCO
				{
					$ls_spg_cuenta1=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2).'.'.substr($ls_spg_anterior,9,4);
				}
				else
				if(substr($ls_spg_anterior,7,2)<>"00")
				{
					$ls_spg_cuenta1=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2).'.'.substr($ls_spg_anterior,7,2);
				}
				else
				{
					$ls_spg_cuenta1=substr($ls_spg_anterior,0,3).'.'.substr($ls_spg_anterior,3,2).'.'.substr($ls_spg_anterior,5,2);
				}
				// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_programatica1."</td>";
				print "<td align='center'>".$ls_spg_cuenta1."</td>";
				print "<td align='left'>".$ls_denominacion."</td>";
				print "<td align='right'>".$li_disponible."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_codestpro."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_cargos
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que inprime el resultado de la busqueda de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 20/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5 ";
		}
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria ";
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Program�tica ";
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Programatica' align='center' onClick=ue_orden('spg_cuentas.codpro')>".$ls_titulo."</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuentas.spg_cuenta')>Cuenta</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('spg_cuentas.denominacion')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Disponible'   align='center' onClick=ue_orden('disponible')>Disponible</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql="SELECT TRIM(spg_cuentas.spg_cuenta) AS spg_cuenta , MAX(spg_cuentas.denominacion) AS denominacion, spg_cuentas.codestpro1, ".
			    "       spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5, MAX(status) AS status, ".
				"       (MAX(spg_cuentas.asignado)-(MAX(spg_cuentas.comprometido)+MAX(spg_cuentas.precomprometido))+MAX(spg_cuentas.aumento)-MAX(spg_cuentas.disminucion)) as disponible ".
			    "  FROM spg_cuentas, tepuy_cargos ".
				" WHERE spg_cuentas.codemp = '".$ls_codemp."'  ".
				"   AND spg_cuentas.status ='C'  ".
				"	AND spg_cuentas.codemp = tepuy_cargos.codemp ".
				"   AND spg_cuentas.codestpro1 = substr(tepuy_cargos.codestpro,1,20) ".
				"   AND spg_cuentas.codestpro2 = substr(tepuy_cargos.codestpro,21,6) ".
				"   AND spg_cuentas.codestpro3 = substr(tepuy_cargos.codestpro,27,3) ".
				"   AND spg_cuentas.codestpro4 = substr(tepuy_cargos.codestpro,30,2) ".
				"   AND spg_cuentas.codestpro5 = substr(tepuy_cargos.codestpro,32,2) ".
				"   AND spg_cuentas.spg_cuenta = tepuy_cargos.spg_cuenta ".
				" GROUP BY spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, ".
				"       spg_cuentas.codestpro5, spg_cuentas.spg_cuenta  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_denominacion=utf8_encode($row["denominacion"]);
				$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_disponible=number_format($row["disponible"],2,",",".");
				if($ls_codestpro==$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5)
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}
				$ls_codest1=$row["codestpro1"];
				$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
				$ls_codest2=$row["codestpro2"];
				$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
				$ls_codest3=$row["codestpro3"];
				$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
				$ls_codest4=$row["codestpro4"];
				$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
				$ls_codest5=$row["codestpro5"];
				$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
				$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_programatica."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td align='left'>".$ls_denominacion."</td>";
				print "<td align='right'>".$li_disponible."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_codestpro."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitud()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de la Solicitud de ejecuci�n presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: 
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 12/07/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_numsol="%".$_POST["numsol"]."%";
		$ls_coduniadm="%".$_POST["coduniadm"]."%";
		$ls_codtipsol=substr($_POST["codtipsol"],0,2);
		if($ls_codtipsol=="-") // no selecciono ninguna
		{
			$ls_codtipsol="";
		}
		$ls_codtipsol="%".$ls_codtipsol."%";
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_codigo=$_POST['codigo'];
		$ls_tipdes=$_POST['tipdes'];
		switch ($ls_tipdes)
		{
			case "P":
				$ls_tabla=", rpc_proveedor";
				$ls_cadena_provbene="AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro AND rpc_proveedor.cod_pro='".$ls_codigo."' ";
			break;
			
			case "B":
				$ls_tabla=", rpc_beneficiario";
				$ls_cadena_provbene="AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene AND rpc_beneficiario.ced_bene='".$ls_codigo."' ";
			break;
			
			case "-":
				$ls_tabla="";
				$ls_cadena_provbene="";
			break;
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100  style='cursor:pointer' title='Ordenar por Numero de Solicitud' align='center' onClick=ue_orden('sep_solicitud.numsol')>Numero de Solicitud</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Unidad Ejecutora' align='center' onClick=ue_orden('spg_unidadadministrativa.denuniadm')>Unidad Ejecutora</td>";
		print "<td width=70  style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sep_solicitud.fecregsol')>Fecha de Registro</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor / Beneficiario</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sep_solicitud.estsol')>Estatus</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
		print "</tr>";
		$ls_sql="SELECT sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, sep_solicitud.codfuefin, ".
				"		sep_solicitud.fecregsol, sep_solicitud.estsol, sep_solicitud.consol, sep_solicitud.monto, ".
				"		sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, ".
				"		sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm, tepuy_fuentefinanciamiento.denfuefin,".
				"       sep_solicitud.estapro, sep_tiposolicitud.estope, sep_tiposolicitud.modsep, spg_unidadadministrativa.codestpro1, ".
				"		spg_unidadadministrativa.codestpro2, spg_unidadadministrativa.codestpro3, spg_unidadadministrativa.codestpro4, ".
				"		spg_unidadadministrativa.codestpro5, ".
				"       (CASE tipo_destino WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                          FROM rpc_proveedor ".
				"                                         WHERE rpc_proveedor.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_proveedor.cod_pro=sep_solicitud.cod_pro) ".
				"                         WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                          FROM rpc_beneficiario ".
				"                                         WHERE rpc_beneficiario.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_beneficiario.ced_bene=sep_solicitud.ced_bene) ". 
				"                         ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM sep_solicitud, spg_unidadadministrativa, tepuy_fuentefinanciamiento, sep_tiposolicitud ".$ls_tabla." ".
				" WHERE sep_solicitud.codemp='".$ls_codemp."' ".
				"   AND sep_solicitud.numsol like '".$ls_numsol."' ".
				"   AND sep_solicitud.coduniadm like '".$ls_coduniadm."' ".
				"   AND sep_solicitud.codtipsol like '".$ls_codtipsol."' ".
				"   AND sep_solicitud.fecregsol between '".$ld_fecregdes."' AND '".$ld_fecreghas."' ".
				"   ".$ls_cadena_provbene." ".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp ".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm ".
				"   AND sep_solicitud.codemp=tepuy_fuentefinanciamiento.codemp ".
				"   AND sep_solicitud.codfuefin=tepuy_fuentefinanciamiento.codfuefin ".
				"   AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				$ls_codtipsol=$row["codtipsol"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_estsol=$row["estsol"];
				$ls_consol=utf8_encode($row["consol"]);
				$ls_tipo_destino=$row["tipo_destino"];
				switch ($ls_tipo_destino)
				{
					case "P":// proveedor
						$ls_codigo=$row["cod_pro"];
						break;	
					case "B":// beneficiario
						$ls_codigo=$row["ced_bene"];
						break;	
					case "-":// Ninguno
						$ls_codigo="----------";
						break;	
				}
				$ls_nombre=utf8_encode($row["nombre"]);
				$ls_denuniadm=utf8_encode($row["denuniadm"]);
				$ls_denfuefin=utf8_encode($row["denfuefin"]);
				$ls_estapro=$row["estapro"];
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($row["fecregsol"]);
				$li_monto=number_format($row["monto"],2,",",".");
				$li_monbasinm=number_format($row["monbasinm"],2,",",".");
				$li_montotcar=number_format($row["montotcar"],2,",",".");
				$ls_estope=$row["estope"];
				$ls_modsep=$row["modsep"];
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estatus="";
				switch ($ls_estsol)
				{
					case "R":
						$ls_estatus="REGISTRO";
						break;
						
					case "E":
						if($ls_estapro==0)
						{
							$ls_estatus="EMITIDA";
						}
						else
						{
							$ls_estatus="EMITIDA (APROBADA)";
						}
						break;
						
					case "A":
						$ls_estatus="ANULADA";
						break;
						
					case "C":
						$ls_estatus="CONTABILIZADA";
						break;
						
					case "P":
						$ls_estatus="PROCESADA";
						break;
						
					case "D":
						$ls_estatus="DESPACHADA";
						break;
					
					case "L":
						$ls_estatus="DESPACHADA PARCIALMENTE";
						break;
				}
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codtipsol','$ls_coduniadm','$ls_codfuefin',".
											"'$ls_estsol','$ls_tipo_destino','$ls_codigo','$ls_denuniadm',".
											"'$ls_denfuefin','$ls_nombre','$ls_estapro','$ld_fecregsol','$li_monto','$li_monbasinm',".
											"'$li_montotcar','$ls_estatus','$ls_estope','$ls_modsep','$ls_codestpro1','$ls_codestpro2',".
											"'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_consol');\">".$ls_numsol."</a></td>";
						print "<td><input name='txtconsol".$ls_consol."' type='hidden' id='txtconsol".$ls_numsol."' value='$ls_consol'>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td align='left'>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportedesde('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportehasta('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
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
	}// end function uf_print_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudserviciosmedicos()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de la Solicitud de ejecuci�n presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: 
		// Fecha Creaci�n: 02/06/2015
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_numsol="%".$_POST["numsol"]."%";
		$ls_coduniadm="%".$_POST["coduniadm"]."%";
		$ls_codtipsol=substr($_POST["codtipsol"],0,2);
		if($ls_codtipsol=="-") // no selecciono ninguna
		{
			$ls_codtipsol="";
		}
		$ls_codtipsol="%".$ls_codtipsol."%";
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_codigo=$_POST['codigo'];
		$ls_tipdes=$_POST['tipdes'];
		switch ($ls_tipdes)
		{
			case "P":
				$ls_tabla=", rpc_proveedor";
				$ls_cadena_provbene="AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro AND rpc_proveedor.cod_pro='".$ls_codigo."' ";
			break;
			
			case "B":
				$ls_tabla=", rpc_beneficiario";
				$ls_cadena_provbene="AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene AND rpc_beneficiario.ced_bene='".$ls_codigo."' ";
			break;
			
			case "-":
				$ls_tabla="";
				$ls_cadena_provbene="";
			break;
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100  style='cursor:pointer' title='Ordenar por Numero de Solicitud' align='center' onClick=ue_orden('sep_solicitud.numsol')>Numero de Solicitud</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Unidad Ejecutora' align='center' onClick=ue_orden('spg_unidadadministrativa.denuniadm')>Unidad Ejecutora</td>";
		print "<td width=70  style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sep_solicitud.fecregsol')>Fecha de Registro</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor / Beneficiario</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sep_solicitud.estsol')>Estatus</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
		print "</tr>";
		$ls_sql="SELECT sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, sep_solicitud.codfuefin, ".
				"		sep_solicitud.fecregsol, sep_solicitud.estsol, sep_solicitud.consol, sep_solicitud.monto, ".
				"		sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, sep_solicitud.codtar, ".
				"		sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm, tepuy_fuentefinanciamiento.denfuefin,".
				"       sep_solicitud.estapro, sep_tiposolicitud.estope, sep_tiposolicitud.modsep, spg_unidadadministrativa.codestpro1, ".
				"		spg_unidadadministrativa.codestpro2, spg_unidadadministrativa.codestpro3, spg_unidadadministrativa.codestpro4, ".
				"		spg_unidadadministrativa.codestpro5, sme_montoseguntipo.codnom, ".
				"       (CASE tipo_destino WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                          FROM rpc_proveedor ".
				"                                         WHERE rpc_proveedor.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_proveedor.cod_pro=sep_solicitud.cod_pro) ".
				"                         WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                          FROM rpc_beneficiario ".
				"                                         WHERE rpc_beneficiario.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_beneficiario.ced_bene=sep_solicitud.ced_bene) ". 
				"                         ELSE 'NINGUNO' END ) AS nombre, ".
// NUEVO //
/*				" (CASE codtartip WHEN 'T' THEN (SELECT sno_personal.nomper ".
				"                                          FROM sno_personal ".
				"                                         WHERE sno_personal.cedper=sme_dt_beneficiario.cedper ".
				"                                           AND sme_dt_beneficiario.numsol=sep_solicitud.numsol) ".
				"                         WHEN 'F' THEN (SELECT sno_familiar.nomfam ".
				"                                          FROM sno_familiar ".
				"                                         WHERE sno_familiar.cedfam=sme_dt_beneficiario.cedfam ".
				"                                           AND sno_personal.cedper=sme_dt_beneficiario.cedper ".
				"                                           AND sme_dt_beneficiario.numsol=sep_solicitud.numsol) ". 
				"                          ELSE '' END ) AS quienes, ". */
				"  sme_dt_beneficiario.cedper as cedtra, sme_dt_beneficiario.cedfam as cedfam".
				"  FROM sme_montoseguntipo, sme_dt_beneficiario,sep_solicitud, spg_unidadadministrativa, tepuy_fuentefinanciamiento, sep_tiposolicitud ".$ls_tabla."".
				" WHERE sep_solicitud.codemp='".$ls_codemp."' ".
				"   AND sep_solicitud.numsol like '".$ls_numsol."' ".
				"   AND sep_solicitud.codtar<>'00' ". // CAMBIO PARA FILTRAR SOLO Servicios Medicos
				"   AND sep_solicitud.numsol=sme_dt_beneficiario.numsol ".// CAMBIO PARA FILTRAR  Servicios Medicos
				"   AND sep_solicitud.codtar=sme_montoseguntipo.codtar ". // FILTRA COD. NOMINA
				"   AND sep_solicitud.coduniadm like '".$ls_coduniadm."' ".
				"   AND sep_solicitud.codtipsol like '".$ls_codtipsol."' ".
				"   AND sep_solicitud.fecregsol between '".$ld_fecregdes."' AND '".$ld_fecreghas."' ".
				"   ".$ls_cadena_provbene." ".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp ".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm ".
				"   AND sep_solicitud.codemp=tepuy_fuentefinanciamiento.codemp ".
				"   AND sep_solicitud.codfuefin=tepuy_fuentefinanciamiento.codfuefin ".
				"   AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				$ls_cedtra=$row["cedtra"];
				$ls_cedfam=$row["cedfam"];
			/////////// BUSCA LOS DATOS DEL TRABAJADOR O DEL FAMILIAR /////////
				if(strlen(trim($ls_cedfam))>0)
				{
					//$aux=$ls_cedtra;
					$ls_codtra=$io_funciones->uf_cerosizquierda($ls_codtra,10);
					$ls_sql1="SELECT CONCAT (TRIM(T1.nomfam),' ',TRIM(T1.apefam)) as nombre1 FROM sno_familiar as T1, sno_personal as T2 ".
					"WHERE T1.codemp = '0001' ".
					" AND T1.cedfam = '".$ls_cedfam."' ".
					" AND T2.cedper= '".$ls_cedtra."' ".
					" AND T1.codper=T2.codper ".
					" GROUP BY T1.cedfam ORDER BY T1.cedfam ASC";
					$ls_combo="F";
					$ls_ced=$ls_cedfam;
					//print $ls_sql1;
				}
				else
				{
					$ls_sql1="SELECT CONCAT (TRIM(nomper),' ',TRIM(apeper)) as nombre1 FROM sno_personal ".
					"WHERE codemp = '0001' ".
					" AND cedper = '".$ls_cedtra."' ".
					" GROUP BY cedper ORDER BY cedper ASC";
					$ls_combo="T";
					$ls_ced=$ls_cedtra;
				}
				//print $ls_sql1;
				$rs_result=$io_sql->select($ls_sql1);
				if($fila=$io_sql->fetch_row($rs_result))
				{
					$ls_quienes=$fila["nombre1"];
				}
				$io_sql->free_result($rs_result);
			//////////////////////////////////////////////////////////////////
				$ls_codtipsol=$row["codtipsol"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_estsol=$row["estsol"];
				$ls_codtar=$row["codtar"];
				$ls_codnom=$row["codnom"];
				$ls_consol=utf8_encode($row["consol"]);
				$ls_tipo_destino=$row["tipo_destino"];
				switch ($ls_tipo_destino)
				{
					case "P":// proveedor
						$ls_codigo=$row["cod_pro"];
						break;	
					case "B":// beneficiario
						$ls_codigo=$row["ced_bene"];
						break;	
					case "-":// Ninguno
						$ls_codigo="----------";
						break;	
				}
				$ls_nombre=utf8_encode($row["nombre"]);
				$ls_denuniadm=utf8_encode($row["denuniadm"]);
				$ls_denfuefin=utf8_encode($row["denfuefin"]);
				$ls_estapro=$row["estapro"];
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($row["fecregsol"]);
				$li_monto=number_format($row["monto"],2,",",".");
				$li_monbasinm=number_format($row["monbasinm"],2,",",".");
				$li_montotcar=number_format($row["montotcar"],2,",",".");
				$ls_estope=$row["estope"];
				$ls_modsep=$row["modsep"];
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estatus="";
				switch ($ls_estsol)
				{
					case "R":
						$ls_estatus="REGISTRO";
						break;
						
					case "E":
						if($ls_estapro==0)
						{
							$ls_estatus="EMITIDA";
						}
						else
						{
							$ls_estatus="EMITIDA (APROBADA)";
						}
						break;
						
					case "A":
						$ls_estatus="ANULADA";
						break;
						
					case "C":
						$ls_estatus="CONTABILIZADA";
						break;
						
					case "P":
						$ls_estatus="PROCESADA";
						break;
						
					case "D":
						$ls_estatus="DESPACHADA";
						break;
					
					case "L":
						$ls_estatus="DESPACHADA PARCIALMENTE";
						break;
				}
				//print "Tipo: ".$ls_tipo;
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codtipsol','$ls_coduniadm','$ls_codfuefin',".
											"'$ls_estsol','$ls_tipo_destino','$ls_codigo','$ls_denuniadm',".
											"'$ls_denfuefin','$ls_nombre','$ls_estapro','$ld_fecregsol','$li_monto','$li_monbasinm',".
											"'$li_montotcar','$ls_estatus','$ls_estope','$ls_modsep','$ls_codestpro1','$ls_codestpro2',".
											"'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_consol','$ls_codtar','$ls_codnom','$ls_combo','$ls_quienes','$ls_ced');\">".$ls_numsol."</a></td>";
						print "<td><input name='txtconsol".$ls_consol."' type='hidden' id='txtconsol".$ls_numsol."' value='$ls_consol'>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td align='left'>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportedesde('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportehasta('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
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
		unset($ls_codemp);	}// end function uf_print_solicitudserviciosmedicos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los servicios
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 02/06/2015								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codser="%".$_POST['codser']."%";
		$ls_denser="%".$_POST['denser']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codser')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denser')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'       align='center' onClick=ue_orden('preser')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql="SELECT codser, denser, preser, codunimed, TRIM(spg_cuenta) as spg_cuenta , ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND soc_servicios.codemp = spg_cuentas.codemp ".
				"			AND soc_servicios.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ,".
				"   (select denunimed from siv_unidadmedida where siv_unidadmedida.codunimed=soc_servicios.codunimed)as denunimed   ".
				"  FROM soc_servicios ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codser like '".$ls_codser."' ".
				"   AND denser like '".$ls_denser."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codser=$row["codser"];
				$ls_denser=utf8_encode($row["denser"]);
				$li_preser=number_format($row["preser"],2,",",".");
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_existecuenta=$row["existecuenta"];
				$ls_codunimed=$row["codunimed"];
                $ls_denunimed=$row["denunimed"];
				if($li_existecuenta==0)
				{
					$ls_estilo = "celdas-blancas";
				}
				else
				{
					$ls_estilo = "celdas-azules";
				}
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codser."</td>";
				print "<td align='left'>".$ls_denser."</td>";
				print "<td align='left'>".$li_preser."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."','".$ls_denunimed."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_conceptos($as_tipdes,$as_codtar)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los conceptos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
        	$ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codconsep="%".$_POST['codconsep']."%";
		$ls_denconsep="%".$_POST['denconsep']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		//print "tipdes=".$as_tipdes." codtar: ".$as_codtar;
	//////// BUSCO LA CUENTA PRESUPUESTARIA O LA CATEGORIA PROGRAMATICA DEL SERVICIO MEDICO////////////
		$ls_sql1="SELECT codtipsol,codestpro,spg_cuenta FROM sme_montoseguntipo WHERE codemp='$ls_codemp' AND codtar='$as_codtar' ";
		//print $ls_sql1;
		$rs_result=$io_sql->select($ls_sql1);
		if($rs_result===false)
		{
			$io_mensajes->message("ERROR->Tepuy_smc_c_catalogo_ajax ".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			if($la_row=$io_sql->fetch_row($rs_result))
			{
				$as_spg_cuenta=$la_row["spg_cuenta"];
				$ls_codestpro =$la_row["codestpro"];
				$ls_codestpro = str_replace("-", "", $ls_codestpro)."0000";
				$ls_codtipsol  =$la_row["codtipsol"];
			}
		}
		$io_sql->free_result($rs_result);		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	print "cuenta: ".$ls_spg_cuenta." codestpro: ".$ls_codestpro." codtipsol: ".$ls_codtipsol;

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codconsep')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denconsep')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'       align='center' onClick=ue_orden('monconsepe')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql="SELECT codconsep, denconsep, monconsepe, TRIM(spg_cuenta) as spg_cuenta, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND spg_cuentas.spg_cuenta = sep_conceptos.spg_cuenta) AS existecuenta ".
				"  FROM sep_conceptos ".
				" WHERE codconsep like '".$ls_codconsep."' ".
				"   AND denconsep like '".$ls_denconsep."' ".
				"   AND spg_cuenta='".$as_spg_cuenta."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codconsep=$row["codconsep"];
				$ls_denconsep=utf8_encode($row["denconsep"]);
				$li_monconsepe=number_format($row["monconsepe"],2,",",".");
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_existecuenta=$row["existecuenta"];
				if($li_existecuenta==0)
				{
					$ls_estilo = "celdas-blancas";
				}
				else
				{
					$ls_estilo = "celdas-azules";
				}
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codconsep."</td>";
				print "<td align='left'>".$ls_denconsep."</td>";
				print "<td align='left'>".$li_monconsepe."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codconsep."','".$ls_denconsep."','".$li_monconsepe."','".$ls_spg_cuenta."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_conceptosservicioss()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptosservicioss
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los conceptos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sme;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codconsep="%".$_POST['codconsep']."%";
		$ls_denconsep="%".$_POST['denconsep']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codconsep')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denconsep')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'       align='center' onClick=ue_orden('monconsepe')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		//$spg_cuenta="4070102010000";
		$as_codtipsol="001";
		$ls_sql1    = "SELECT * FROM sep_conceptos WHERE codconsep='".$as_codtipsol."'";
		//print $ls_sql1;
  		$rs_data1   = $io_sql->select($ls_sql1);
		$li_numrows = $io_sql->num_rows($rs_data1);
		//print "num: ".$li_numrows;
	   	if ($li_numrows>0)
		{
			while($row=$io_sql->fetch_row($rs_data1))
			{
		    	$as_codtiposol=$row["codconsep"];
			//print "aqui".$as_codtiposol;
			}
		    $io_sql->free_result($rs_data1);
		}

		$ls_sql="SELECT codconsep, denconsep, monconsepe, TRIM(spg_cuenta) as spg_cuenta, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND spg_cuentas.spg_cuenta = sep_conceptos.spg_cuenta) AS existecuenta ".
				"  FROM sep_conceptos ".
				//" WHERE codconsep like '".$ls_codconsep."' ".
				" WHERE codconsep = '".$as_codtiposol."' ".
				"   AND denconsep like '".$ls_denconsep."' ".
		//		"   AND spg_cuenta = '".$spg_cuenta."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codconsep=$row["codconsep"];
				$ls_denconsep=utf8_encode($row["denconsep"]);
				$li_monconsepe=number_format($row["monconsepe"],2,",",".");
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_existecuenta=$row["existecuenta"];
				if($li_existecuenta==0)
				{
					$ls_estilo = "celdas-blancas";
				}
				else
				{
					$ls_estilo = "celdas-azules";
				}
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codconsep."</td>";
				print "<td align='left'>".$ls_denconsep."</td>";
				print "<td align='left'>".$li_monconsepe."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codconsep."','".$ls_denconsep."','".$li_monconsepe."','".$ls_spg_cuenta."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_conceptosservicioss
	//-----------------------------------------------------------------------------------------------------------------------------------


?>
