<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema SEP con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_cxp->uf_obtenervalor("catalogo","");
	//print $ls_catalogo;
	switch($ls_catalogo)
	{
		case "PROVEEDOR":
			uf_print_proveedor();
			break;
		case "BENEFICIARIO":
			uf_print_beneficiario();
			break;
		case "ESTRUCTURA1":
			uf_print_estructura1();
			break;
		case "ESTRUCTURA2":
			uf_print_estructura2();
			break;
		case "ESTRUCTURA3":
			uf_print_estructura3();
			break;
		case "ESTRUCTURA4":
			uf_print_estructura4();
			break;
		case "ESTRUCTURA5":
			uf_print_estructura5();
			break;
		case "CUENTASSPG":
			uf_print_cuentasspg();
			break;
		case "CUENTASSCG":
			uf_print_cuentasscg();
			break;
		case "OTROSCREDITOS":
			uf_print_otroscreditos();
			break;
		case "DEDUCCIONES":
			uf_print_deducciones();
			break;
		case "RECEPCION":
			uf_print_recepcion();
			break;
		case "COMPROMISOS":
			uf_print_compromisos();
			break;
		case "SOLICITUDPAGO":
			uf_print_solicitudespago();
			break;
		case "DTPRESUPUESTO":
			uf_print_dtpresupuestario();
			break;	
		case "DTCARGOS":
			uf_print_dtcargos();
			break;
		case "CALCULARCARGO":
			uf_calcular_cargo();
			break;			
		case "DTCONTABLE":
			uf_print_dtcontable();
			break;
	 	case "NOTAS":
			uf_print_notas();
			break;				
		case "RECEPCIONESNCND":
			uf_print_recepcionesncnd();
			break;
		case "FUENTEFINANCIAMIENTO":
			uf_print_fuentefinanciamiento();
			break;
		case "RETENCIONESISLR":
			//uf_print_retencionesislr();
			uf_print_retencionesislrnuevo();
			break;
		case "CATDEDUCCIONES":
			uf_print_catdeducciones();
			break;
		case "RETENCIONESIVA":
			uf_print_retencionesiva();
			break;
		case "RETENCIONESMUNICIPALES":
			uf_print_retencionesmunicipales();
			break;
		case "RETENCIONIVA":
			uf_print_retencioniva();
			break;	
		case "RETENCIONESAPORTE":
			uf_print_retencionesaporte();
			break;
		case "RETENCIONESTIMBREFISCAL":
			uf_print_retencionestimbrefiscal();
			break;
		case "retencionestimbrefiscal":
			uf_print_retencionestimbrefiscal();
			break;
		case "RETENCIONESUNIFICADAS":
			uf_print_retencionesunificadas();
			break;


	}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedor()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_rifpro="%".$_POST['rifpro']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
        $ls_sql="SELECT cod_pro, nompro, trim(sc_cuenta) AS sc_cuenta, rifpro, tipconpro, sc_cuenta, tipconpro, dirpro ".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."' ".
				"   AND nompro like '".$ls_nompro."' ".
				"   AND dirpro like '".$ls_dirpro."' ". 
				"   AND rifpro like '".$ls_rifpro."' ". 
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Proveedores","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='left' onClick=ue_orden('nompro')>Nombre</td>";
			print "<td style='cursor:pointer' title='Ordenar por RIF' align='center' onClick=ue_orden('rifpro')>Rif</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];//utf8_encode($row["nompro"]);
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_tipconpro=$row["tipconpro"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_tipconpro=$row["tipconpro"];
				$ls_dirprov=$row["dirpro"];
				$ls_rifpro=$row["rifpro"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro','$ls_rifpro','$ls_sccuenta','$ls_tipconpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "SOLICITUDPAGO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_solicitudpago('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "CMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_cmpretencion('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;

					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_modcmpretencion('$ls_codpro','$ls_nompro','$ls_rifpro','$ls_dirprov');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
				}
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
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_beneficiario()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_beneficiario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de beneficiarios
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
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
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT ced_bene, nombene, apebene, rifben, sc_cuenta, tipconben, dirbene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND ced_bene <> '----------' ".
				"   AND ced_bene like '".$ls_cedbene."' ".
				"   AND nombene like '".$ls_nombene."' ".
				"   AND apebene like '".$ls_apebene."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Beneficiarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Cedula' align='center' onClick=ue_orden('ced_bene')>Cedula </td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombene')>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["ced_bene"];
				$ls_nombene=utf8_encode($row["nombene"]." ".$row["apebene"]);
				$ls_rifben=$row["rifben"];
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_tipconben=$row["tipconben"];
				$ls_dirbene=$row["dirbene"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_rifben','$ls_sccuenta','$ls_tipconben');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					
					case "SOLICITUDPAGO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_solicitudpago('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
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

					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_modcmpretencion('$ls_cedbene','$ls_nombene','$ls_rifben','$ls_dirbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
				}					
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
	}// end function uf_print_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura1()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura1
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 1
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_codestpro1="%".$_POST['codestpro1']."%";
		$ls_denestpro1="%".$_POST['denestpro1']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1, denestpro1 ".
				"  FROM spg_ep1 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 like '".$ls_codestpro1."' ".
				"   AND denestpro1 like '".$ls_denestpro1."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro1')>".utf8_encode("Código")." </td>";
			print "<td style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro1')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_denestpro1=utf8_encode(rtrim($row["denestpro1"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1');\">".$ls_codestpro1."</a></td>";
						print "<td>".$ls_denestpro1."</td>";
						print "</tr>";			
						break;
				}
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
	}// end function uf_print_estructura1
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura2()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura2
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 2
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2="%".$_POST['codestpro2']."%";
		$ls_denestpro2="%".$_POST['denestpro2']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1, codestpro2, denestpro2 ".
				"  FROM spg_ep2 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ".
				"   AND codestpro2 like '".$ls_codestpro2."' ".
				"   AND denestpro2 like '".$ls_denestpro2."' ".
				" ORDER BY codestpro1, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=150 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro2')>".utf8_encode("Código")." </td>";
			print "<td width=250 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro2')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_denestpro2=utf8_encode(rtrim($row["denestpro2"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=130 align=\"left\">".trim($ls_denestpro2)."</td>";
						print "</tr>";
						break;
				}
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
	}// end function uf_print_estructura2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura3()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura3
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 3
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_criterio="";
		if($ls_codestpro1!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep3.codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ";
		}
		if($ls_codestpro2!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep3.codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ";
		}
		$ls_codestpro3="%".$_POST['codestpro3']."%";
		$ls_denestpro3="%".$_POST['denestpro3']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT spg_ep3.codestpro1, spg_ep3.codestpro2, spg_ep3.codestpro3, spg_ep3.denestpro3,".
				"       spg_ep1.denestpro1,spg_ep2.denestpro2 ".
				"  FROM spg_ep3,spg_ep2,spg_ep1 ".
				" WHERE spg_ep3.codemp='".$ls_codemp."' ".
				$ls_criterio.
				"   AND spg_ep3.codestpro3 like '".$ls_codestpro3."' ".
				"   AND spg_ep3.denestpro3 like '".$ls_denestpro3."' ".
				"   AND spg_ep1.codemp=spg_ep3.codemp".
				"   AND spg_ep1.codestpro1=spg_ep3.codestpro1".
				"   AND spg_ep2.codemp=spg_ep3.codemp".
				"   AND spg_ep2.codestpro1=spg_ep3.codestpro1".
				"   AND spg_ep2.codestpro2=spg_ep3.codestpro2".
				" ORDER BY spg_ep3.codestpro1, spg_ep3.codestpro2, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('spg_ep3.codestpro3')>".utf8_encode("Código")." </td>";
			print "<td width=200 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('spg_ep3.denestpro3')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_denestpro1=utf8_encode(rtrim($row["denestpro1"]));
				$ls_denestpro2=utf8_encode(rtrim($row["denestpro2"]));
				$ls_denestpro3=utf8_encode(rtrim($row["denestpro3"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro3."</td>";
						print "</tr>";			
						break;
				}
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
	}// end function uf_print_estructura3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura4()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura4
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 4
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 07/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4="%".$_POST['codestpro4']."%";
		$ls_denestpro4="%".$_POST['denestpro4']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,denestpro4 ".
				"  FROM spg_ep4 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ".
				"   AND codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ".
				"   AND codestpro3 ='".str_pad($ls_codestpro3,3,"0",0)."' ".
				"   AND codestpro4 like '".$ls_codestpro4."' ".
				"   AND denestpro4 like '".$ls_denestpro4."' ".
				" ORDER BY codestpro1,codestpro2,codestpro3, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro3"])." </td>";
			print "<td width=80 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro4')>".utf8_encode("Código")." </td>";
			print "<td width=180 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro4')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_denestpro4=rtrim(utf8_encode($row["denestpro4"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro4."</td>";
						print "</tr>";			
						break;

				}
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
	}// end function uf_print_estructura4
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura5()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura5
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 5
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 07/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_criterio="";
		if($ls_codestpro1!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ";
		}
		if($ls_codestpro2!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ";
		}
		if($ls_codestpro3!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro3 ='".str_pad($ls_codestpro3,3,"0",0)."' ";
		}
		if($ls_codestpro4!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro4 ='".str_pad($ls_codestpro4,2,"0",0)."' ";
		}
		$ls_codestpro5="%".$_POST['codestpro5']."%";
		$ls_denestpro5="%".$_POST['denestpro5']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, spg_ep5.codestpro4, spg_ep5.codestpro5, ".
				"		spg_ep1.denestpro1, spg_ep2.denestpro2, spg_ep3.denestpro3, spg_ep4.denestpro4, spg_ep5.denestpro5 ".
				"  FROM spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 ".
				" WHERE spg_ep5.codemp='".$ls_codemp."' ".
				$ls_criterio.
				"   AND spg_ep5.codestpro5 like '".$ls_codestpro5."' ".
				"   AND spg_ep5.denestpro5 like '".$ls_denestpro5."' ".
				"   AND spg_ep5.codemp = spg_ep1.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep1.codestpro1 ".
				"   AND spg_ep5.codemp = spg_ep2.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep2.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep2.codestpro2 ".
				"   AND spg_ep5.codemp = spg_ep3.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep3.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep3.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep3.codestpro3 ".
				"   AND spg_ep5.codemp = spg_ep4.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep4.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep4.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep4.codestpro3 ".
				"   AND spg_ep5.codestpro4 = spg_ep4.codestpro4 ".				
				" ORDER BY spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, spg_ep5.codestpro4, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro3"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro4"])." </td>";
			print "<td width=70 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('spg_ep5.codestpro5')>".utf8_encode("Código")." </td>";
			print "<td width=150 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('spg_ep5.denestpro5')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_codestpro5=substr($row["codestpro5"],(strlen($row["codestpro5"])-$li_len5),$li_len5);
				$ls_denestpro1=rtrim(utf8_encode($row["denestpro1"]));
				$ls_denestpro2=rtrim(utf8_encode($row["denestpro2"]));
				$ls_denestpro3=rtrim(utf8_encode($row["denestpro3"]));
				$ls_denestpro4=rtrim(utf8_encode($row["denestpro4"]));
				$ls_denestpro5=rtrim(utf8_encode($row["denestpro5"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_codestpro2',";
						print "'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3',";
						print "'$ls_denestpro4','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro5)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro5."</td>";
						print "</tr>";			
						break;
				}
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
	}// end function uf_print_estructura5
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasspg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasspg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 07/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_spgcuenta="%".$_POST['spgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1=str_pad($_POST['codestpro1'],20,0,0);
		$ls_codestpro2=str_pad($_POST['codestpro2'],6,0,0);
		$ls_codestpro3=str_pad($_POST['codestpro3'],3,0,0);
		$ls_codestpro4=str_pad($_POST['codestpro4'],2,0,0);
		$ls_codestpro5=str_pad($_POST['codestpro5'],2,0,0);
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		}
		$io_funciones_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_cuentas="";
		$ls_tipocuenta="";
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3, codestpro4, codestpro5, status, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible, sc_cuenta ".
			    "  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND codestpro1 = '".$ls_codestpro1."' ".
				"	AND codestpro2 = '".$ls_codestpro2."' ".
				"	AND codestpro3 = '".$ls_codestpro3."' ".
				"	AND codestpro4 = '".$ls_codestpro4."' ".
				"	AND codestpro5 = '".$ls_codestpro5."' ".
				"	AND spg_cuenta like '".$ls_spgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Presupuestarias ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Programatica'          align='center' onClick=ue_orden('codpro')>".$ls_titulo."</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Presupuestaria' align='center' onClick=ue_orden('spg_cuenta')>Presupuestaria</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable'       align='center' onClick=ue_orden('sc_cuenta')>Contable</td>";
			print "<td width=180 style='cursor:pointer' title='Ordenar por Denominacion'          align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Disponible'            align='center' onClick=ue_orden('disponible')>Disponible</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=trim($row["spg_cuenta"]);
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);

				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_disponible=number_format($row["disponible"],2,",",".");
				$ls_programatica="";
				$io_funciones_cxp->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
				if($ls_status=="C")
				{
					print "<tr class=celdas-azules>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_sccuenta."','".$li_disponible."');\">".$ls_programatica."</a></td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "<td align='right'>".$li_disponible."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_programatica."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "<td align='right'>".$li_disponible."</td>";
					print "</tr>";			
				}
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
	}// end function uf_print_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasscg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasscg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas contables
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 16/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_scgcuenta="%".$_POST['scgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT sc_cuenta, denominacion, status ".
			    "  FROM scg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND sc_cuenta like '".$ls_scgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Contables ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable' align='center' onClick=ue_orden('sc_cuenta')>Cuenta Contable</td>";
			print "<td width=400 style='cursor:pointer' title='Ordenar por Denominacion'    align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);
				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				if($ls_status=="C")
				{
					print "<tr class=celdas-azules>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_sccuenta."','".$ls_denominacion."');\">".$ls_sccuenta."</a></td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
				}
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
	}// end function uf_print_cuentasscg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_otroscreditos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_otroscreditos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de los creditos a aplicar en un compromiso en particular
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 15/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_grid, $io_ds_cargos;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_datastore.php");
		$io_ds_cargos=new class_datastore(); // Datastored de cuentas contables
		require_once("tepuy_cxp_c_recepcion.php");
		$io_recepcion=new tepuy_cxp_c_recepcion("../../");
				
		$ls_compromiso=$_POST['compromiso'];
		$li_baseimponible=$_POST['baseimponible'];
		$ls_procededoc=$_POST['procededoc'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_parcial=$_POST['parcial'];
		$li_fila=0;
		$ls_confiva=$_SESSION["la_empresa"]["confiva"];
		if($ls_confiva=="C")
		{
			$ls_sql="SELECT tepuy_cargos.codcar, tepuy_cargos.dencar,  tepuy_cargos.spg_cuenta,".
					"       tepuy_cargos.formula,  tepuy_cargos.porcar, '' as codestpro, '' as sc_cuenta".
					"  FROM tepuy_cargos, scg_cuentas".
					" WHERE tepuy_cargos.codemp='".$ls_codemp."'".
					"   AND tepuy_cargos.codemp=scg_cuentas.codemp".
					"   AND tepuy_cargos.spg_cuenta=scg_cuentas.sc_cuenta ".
					" ORDER BY tepuy_cargos.codcar";
		}
		else
		{
			$ls_sql="SELECT tepuy_cargos.codcar, tepuy_cargos.dencar, tepuy_cargos.codestpro, tepuy_cargos.spg_cuenta,".
					"       tepuy_cargos.formula, spg_cuentas.sc_cuenta,  tepuy_cargos.porcar ".
					"  FROM tepuy_cargos, spg_cuentas".
					" WHERE tepuy_cargos.codemp='".$ls_codemp."'".
					"   AND tepuy_cargos.codemp=spg_cuentas.codemp".
					"   AND substr(tepuy_cargos.codestpro,1,20) = spg_cuentas.codestpro1 ".
					"   AND substr(tepuy_cargos.codestpro,21,6) = spg_cuentas.codestpro2 ".
					"   AND substr(tepuy_cargos.codestpro,27,3) = spg_cuentas.codestpro3 ".
					"   AND substr(tepuy_cargos.codestpro,30,2) = spg_cuentas.codestpro4 ".
					"   AND substr(tepuy_cargos.codestpro,32,2) = spg_cuentas.codestpro5 ".
					"   AND tepuy_cargos.spg_cuenta=spg_cuentas.spg_cuenta ".
					" ORDER BY tepuy_cargos.codcar";
		}
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Otros Créditos ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]=utf8_encode("Código");
			$lo_title[3]=utf8_encode("Denominación");
			$lo_object[1][1]="";
			$lo_object[1][2]="";
			$lo_object[1][3]="";
			$lo_object[1][4]="";
			$lo_object[1][5]="";
			if ($ls_tipo=='CMPRET')
			   {
				 $lo_title[4]="Porcentaje"; 
				 $lo_title[5]=utf8_encode("Fórmula"); 
			   }
			else
			   {
				 $lo_title[4]="Base Imponible"; 
				 $lo_title[5]="Monto Impuesto"; 
				 $lo_title[6]="Monto Ajuste"; 
			     $lo_object[1][6]="";
			   }
			if(array_key_exists("cargos",$_SESSION))
			{
				$io_ds_cargos->data=$_SESSION["cargos"];
			}
			else
			{
				$lb_valido=$io_recepcion->uf_load_cargos_compromiso($ls_compromiso,$ls_procededoc,&$io_ds_cargos);
			}
			while($row=$io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$ls_codcar= $row["codcar"];
				$ls_dencar= $row["dencar"];
				$ls_formula= $row["formula"];
				$ls_codestpro= $row["codestpro"];
				$ls_spgcuenta= trim($row["spg_cuenta"]);
				$ls_scgcuenta= trim($row["sc_cuenta"]);
				$li_porcar= $row["porcar"];
				$ls_activo= "";
				$li_basimp= number_format($li_baseimponible,2,",",".");
				//$li_basimp="4.000,00";
				$li_monimp= "0,00";
				$ls_codfuefin= "--";
				$li_row= $io_ds_cargos->findValues(array('codcar'=>$ls_codcar."xx",'nrocomp'=>$ls_compromiso,'procededoc'=>$ls_procededoc),"codcar");
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_basimp=number_format($io_ds_cargos->getValue("baseimp",$li_row),2,",",".");
					$li_monimp=number_format($io_ds_cargos->getValue("monimp",$li_row),2,",",".");
					$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
				}
				else
				{
					$li_row=$io_ds_cargos->findValues(array('codpro'=>$ls_codestpro,'cuenta'=>$ls_spgcuenta),"codpro");
					if($li_row>0)
					{
						$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
					}
				}
				if($ls_parcial=="1")
				{
					if($ls_confiva=="C")
					{
						$li_row=$io_ds_cargos->findValues(array('cuenta'=>$ls_spgcuenta),"cuenta");
					}
					else
					{
						$li_row=$io_ds_cargos->findValues(array('codpro'=>$ls_codestpro,'cuenta'=>$ls_spgcuenta),"codpro");
					}
					if($li_row==-1)
					{
						$lb_existe=false;
					}
					else
					{
						$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
					}
				}
				if($lb_existe && empty($ls_tipo))
				{
					$li_fila=$li_fila+1;
					$lo_object[$li_fila][1]="<input name=chkcargos".$li_fila."  type=checkbox id=chkcargos".$li_fila." class=sin-borde  value='1' onClick=ue_calcular('".$li_fila."') ".$ls_activo.">";
					$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."  type=text id=txtcodcar".$li_fila."     class=sin-borde  style=text-align:center size=8 value='".$ls_codcar."' readonly>";
					$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."  type=text id=txtdencar".$li_fila."     class=sin-borde  style=text-align:center size=30 value='".$ls_dencar."' readonly>";
					$lo_object[$li_fila][4]="<input name=txtbaseimp".$li_fila." type=text id=txtbaseimp".$li_fila."    class=sin-borde  style=text-align:right  size=23 onBlur=ue_calcular('".$li_fila."'); onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_basimp."' >";
					$lo_object[$li_fila][5]="<input name=txtmonimp".$li_fila."  type=text id=txtmonimp".$li_fila."     class=sin-borde  style=text-align:right  size=23 value='".$li_monimp."' readonly>";
					$lo_object[$li_fila][6]="<input name=txtmonaju".$li_fila."  type=text id=txtmonaju".$li_fila."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>".
											"<input name=formula".$li_fila."    type=hidden id=formula".$li_fila."     value='".$ls_formula."'>".
											"<input name=codestpro".$li_fila."  type=hidden id=codestpro".$li_fila."   value='".$ls_codestpro."'>".
											"<input name=spgcuenta".$li_fila."  type=hidden id=spgcuenta".$li_fila."   value='".$ls_spgcuenta."'>".
											"<input name=sccuenta".$li_fila."   type=hidden id=sccuenta".$li_fila."    value='".$ls_scgcuenta."'>".
											"<input name=porcar".$li_fila."     type=hidden id=porcar".$li_fila."      value='".$li_porcar."'>".
											"<input name=procededoc".$li_fila." type=hidden id=procededoc".$li_fila."  value='".$ls_procededoc."'>".
											"<input name=codfuefin".$li_fila." type=hidden id=codfuefin".$li_fila."  value='".$ls_codfuefin."'>";
				}
			    elseif($ls_tipo=='CMPRET')
				{
				  $li_fila++;
				  $lo_object[$li_fila][1]="<input name=radiocargos           type=radio id=radiocargos".$li_fila." class=sin-borde  value='1'>";
				  $lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila." type=text  id=txtcodcar".$li_fila."   class=sin-borde  style=text-align:center size=7  value='".trim($ls_codcar)."' readonly>";
				  $lo_object[$li_fila][3]="<input name=txtdencar".$li_fila." type=text  id=txtdencar".$li_fila."   class=sin-borde  style=text-align:left   size=60 value='".$ls_dencar."'       readonly>";
				  $lo_object[$li_fila][4]="<input name=porcar".$li_fila."    type=text  id=porcar".$li_fila."      class=sin-borde  style=text-align:right  size=7  value='".number_format($li_porcar,2,',','.')."'       readonly>";
				  $lo_object[$li_fila][5]="<input name=formula".$li_fila."   type=text  id=formula".$li_fila."     class=sin-borde  style=text-align:left   size=20 value='".$ls_formula."'      readonly>";
				} 
			}
			$io_sql->free_result($rs_data);
			if ($ls_tipo=='CMPRET')
			   {
			     echo"<table width=534 border=0 align=center cellpadding=0 cellspacing=0>";
    			 echo "<tr>";
      			 echo "<td width=532 colspan=6 align=center bordercolor=#FFFFFF>";
        		 echo "<div align=center class=Estilo2>";
          		 echo "<p align=right>&nbsp;&nbsp;&nbsp;<a href='javascript: uf_aceptar_creditos($li_fila);'><img src='../shared/imagebank/tools20/aprobado.png' alt='Aceptar' width=20 height=20 border=0>Agregar Otros Cr&eacute;dito</a></p>";
      			 echo "</div></td>";
    			 echo "</tr>";
  				 echo "</table>";
			   }
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,580,"","gridcargos");
			if ($ls_tipo!='CMPRET')
			   {
				 print "  <table width='580' border='0' align='center' cellpadding='0' cellspacing='0'>";
				 print "    <tr>";
				 print "		<td  align='right'> ";
				 print "		   <a href='javascript:ue_ajustar();'><img src='../shared/imagebank/tools20/actualizar.png' width='20' height='20' border='0' title='Ajustar'>Ajustar</a>&nbsp;&nbsp;";
				 print "		   <a href='javascript:ue_aceptar();'><img src='../shared/imagebank/tools20/ejecutar.png' width='20' height='20' border='0' title='Procesar'>Procesar</a>&nbsp;&nbsp;";
				 print "		   <a href='javascript:ue_cerrar();'><img src='../shared/imagebank/tools/eliminar.png' width='20' height='20' border='0' title='Canccelar'>Cancelar</a>&nbsp;&nbsp;";
				 print "		</td>";
				 print "    </tr>";
				 print "  </table>";
			   }
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_otroscreditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_deducciones()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_deducciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cdeducciones a aplicar en la recepción de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 22/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_grid, $io_ds_deducciones;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_datastore.php");
		$io_ds_deducciones=new class_datastore(); // Datastored de cuentas contables
				
		$ls_numrecdoc=$_POST['numrecdoc'];
		$li_subtotal=$_POST['subtotal'];
		$li_cargos=$_POST['cargos'];
		$ls_procede=$_POST['procede'];
		$ls_presupuestario=$_POST['presupuestario'];
		$ls_contable=$_POST['contable'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_modageret = $_SESSION["la_empresa"]["modageret"];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
	
		$li_fila=0;
		
		if($ls_contable==1)
		{
			$ls_tipo="TODO";
		}
		
		if($ls_modageret=='C')
		{
			$ls_aux="";
		// se agrego otras=1 para que pueda aplicarse Retencion Timbre Fiscal a los documentos contables
			$ls_aux2=" OR estretmun=1 "	;		
			//$ls_aux2=" "	;		
		}
		else
		{
			$ls_aux=" WHERE estretmun<>1 ";
			$ls_aux2="";		
		}
		if ($ls_tipo=='CMPRETIVA') 
		   {
		     $ls_aux = " WHERE iva=1 AND estretmun=0 AND islr=0";
		   }
		elseif($ls_tipo=='CMPRETMUN')
		   {
		     $ls_aux = " WHERE estretmun=1 AND iva=0 AND islr=0";
		   }
		elseif($ls_tipo=='CMPRETFISCAL')
		   {
		     $ls_aux = " WHERE timbrefiscal=1 AND iva=0 AND islr=0";
		   }
		   elseif($ls_tipo=='CMPRETAPO')
		   {
		     $ls_aux = " WHERE estretmun=0 AND iva=0 AND islr=0 AND retaposol=1";
		   }
		   elseif($ls_tipo=='TODO')
		   {
		     $ls_aux="";
			// se agrego otras=1 para que pueda aplicarse Retencion Timbre Fiscal a los documentos contables
			$ls_aux2=" OR estretmun=1 OR otras=1 "	;		
		   }
//print "aux2: ".$ls_aux2." aux:".$ls_aux;

		if(($ls_contable=="1")&&(($ls_presupuestario=="3")||($ls_presupuestario=="4")))
		{
			$ls_sql="SELECT codded,dended,formula,porded,monded,sc_cuenta,islr,iva,estretmun,retaposol,otras ".
					"  FROM tepuy_deducciones ".
					" WHERE islr=1 ".
					$ls_aux2."  ".
					" ORDER BY codded ASC ";
		}
		else
		{
			$ls_sql="SELECT codded,dended,formula,porded,monded,sc_cuenta,islr,iva,estretmun,retaposol,otras ".
					"  FROM tepuy_deducciones ".
					$ls_aux.							   
					" ORDER BY codded ASC ";
		}
//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Deducciones ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]=utf8_encode("Código");
			$lo_title[3]=utf8_encode("Denominación");
			if ($ls_tipo=='CMPRETIVA' || $ls_tipo=='CMPRETMUN'|| $ls_tipo=='CMPRETAPO')
			   {
			     $lo_title[4]="Porcentaje";
			     $lo_title[5]=utf8_encode("Fórmula"); 
			   }
			else
			   {
				 $lo_title[4]=utf8_encode("Monto Objeto Retención"); 
				 $lo_title[5]=utf8_encode("Monto Retención"); 
			   }
			if(array_key_exists("deducciones",$_SESSION))
			{
				$io_ds_deducciones->data=$_SESSION["deducciones"];
			}	
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_fila=$li_fila+1;
			    $ls_codded=$row["codded"];
				$ls_dended=$row["dended"];
				$li_monded=$row["monded"];
				$ls_formula=$row["formula"];
				$ld_porded=$row["porded"];
				$ls_cuenta=$row["sc_cuenta"];				
				$li_iva=$row["iva"]; 
				$li_islr=$row["islr"]; 
				$li_estaposol=$row["retaposol"]; 
				$li_estretmun=$row["estretmun"];
				$li_otras=$row["otras"];
				$ls_activo=""; 
				$li_monobjret=0;
				$li_monret="0,00";
				if(($li_islr=='1')||($li_estretmun=='1')||($li_estaposol=='1')||($li_otras=='1'))
				{
					$li_monobjret=number_format($li_subtotal,2,',','.');
					
				}
				else
				{
					$li_monobjret=$li_cargos;
				}
				$li_row=$io_ds_deducciones->findValues(array('codded'=>$ls_codded),"codded");
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_row);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_row);
				}
				if ($ls_tipo!='CMPRETIVA' && $ls_tipo!='CMPRETMUN' && $ls_tipo!='CMPRETAPO')
				{
					$lo_object[$li_fila][1]="<input name=chkdeduccion".$li_fila."  type=checkbox id=chkdeduccion".$li_fila." class=sin-borde  value='1' onClick=javascript:ue_calcular('".$li_fila."') ".$ls_activo.">";
					$lo_object[$li_fila][2]="<input name=txtcodded".$li_fila."  type=text id=txtcodded".$li_fila."     class=sin-borde  style=text-align:center size=8 value='".$ls_codded."'   title ='".$ls_dended."' readonly><input name=txtmonded".$li_fila." type=hidden id=txtmonded".$li_fila." value='".$li_monded."'>";
					$lo_object[$li_fila][3]="<input name=txtdended".$li_fila."  type=text id=txtdended".$li_fila."     class=sin-borde  style=text-align:left size=60 value='".$ls_dended."'  title ='".$ls_dended."' readonly>";
					if(($li_monobjret=="0,00")&&($li_iva==1)&&($li_islr==0))
					{
						$lo_object[$li_fila][1]="<input name=chkdeduccion".$li_fila."  type=checkbox id=chkdeduccion".$li_fila." class=sin-borde  value='1' onClick=javascript:ue_calcular('".$li_fila."') ".$ls_activo." disabled>";
						$lo_object[$li_fila][4]="<input name=txtmonobjret".$li_fila." type=text id=txtmonobjret".$li_fila."    class=sin-borde  style=text-align:right  size=23 onBlur=ue_calcular('".$li_fila."'); onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_monobjret."' readonly>";
					}
					else
					{
						$lo_object[$li_fila][4]="<input name=txtmonobjret".$li_fila." type=text id=txtmonobjret".$li_fila."    class=sin-borde  style=text-align:right  size=23 onBlur=ue_calcular('".$li_fila."'); onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_monobjret."' >";
					}
					$lo_object[$li_fila][5]="<input name=txtmonret".$li_fila."  type=text id=txtmonret".$li_fila."     class=sin-borde  style=text-align:right  size=23 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_monret."' >".
											"<input name=formula".$li_fila."    type=hidden id=formula".$li_fila."     value='".$ls_formula."'>".
											"<input name=sccuenta".$li_fila."   type=hidden id=sccuenta".$li_fila."    value='".$ls_cuenta."'>".
											"<input name=porded".$li_fila."     type=hidden id=porded".$li_fila."      value='".$ld_porded."'>".
				 						    "<input name=txtiva".$li_fila."     type=hidden  id=txtiva".$li_fila."    	 value='".$li_iva."'>";
			    }
			    else
				{ 
				  $lo_object[$li_fila][1]="<input name=radiodeduccion        type=radio id=radiodeduccion".$li_fila." class=sin-borde>";
				  $lo_object[$li_fila][2]="<input name=txtcodded".$li_fila." type=text  id=txtcodded".$li_fila."      class=sin-borde  style=text-align:center size=7   value='".$ls_codded."'  readonly>";
				  $lo_object[$li_fila][3]="<input name=txtdended".$li_fila." type=text  id=txtdended".$li_fila."      class=sin-borde  style=text-align:left   size=60  value='".$ls_dended."'  readonly>";
				  $lo_object[$li_fila][4]="<input name=porded".$li_fila."    type=text  id=porded".$li_fila."    	  class=sin-borde  style=text-align:right  size=7   value='".number_format($ld_porded,2,',','.')."'  readonly >";
				  $lo_object[$li_fila][5]="<input name=formula".$li_fila."   type=text  id=formula".$li_fila."        class=sin-borde  style=text-align:left   size=50  value='".$ls_formula."' readonly>";
				}
			}
			$io_sql->free_result($rs_data);
			if ($ls_tipo=='CMPRETIVA' || $ls_tipo=='CMPRETMUN'|| $ls_tipo=='CMPRETAPO')
			   {
			     echo"<table width=534 border=0 align=center cellpadding=0 cellspacing=0>";
    			 echo "<tr>";
      			 echo "<td width=532 colspan=6 align=center bordercolor=#FFFFFF>";
        		 echo "<div align=center class=Estilo2>";
          		 echo "<p align=right>&nbsp;&nbsp;&nbsp;<a href='javascript: uf_aceptar_deducciones($li_fila);'><img src='../shared/imagebank/tools20/aprobado.png' alt='Aceptar' width=20 height=20 border=0>Agregar Deducciones</a></p>";
      			 echo "</div></td>";
    			 echo "</tr>";
  				 echo "</table>";
			   }
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,580,"","griddeduccion");
			if ($ls_tipo!='CMPRETIVA' && $ls_tipo!='CMPRETMUN'&& $ls_tipo!='CMPRETAPO')
			   {
				 print "  <table width='580' border='0' align='center' cellpadding='0' cellspacing='0'>";
				 print "    <tr>";
				 print "		<td  align='right'> ";
				 print "		   <a href='javascript:ue_aceptar();'><img src='../shared/imagebank/tools20/ejecutar.png' width='20' height='20' border='0' title='Procesar'>Procesar</a>&nbsp;&nbsp;";
				 print "		   <a href='javascript:ue_cerrar();'><img src='../shared/imagebank/tools/eliminar.png' width='20' height='20' border='0' title='Canccelar'>Cancelar</a>&nbsp;&nbsp;";
				 print "		</td>";
				 print "    </tr>";
				 print "  </table>";
			   }
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_recepcion()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_recepcion
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de recepciones de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$ls_numrecdoc="%".$_POST['numrecdoc']."%";
		$ls_estprodoc="%".$_POST['estprodoc']."%";
		$ls_codcla="%".$_POST['codcla']."%";
		$ls_procedencia=$io_funciones_cxp->uf_obtenervalor("procedencia","");
		if($ls_codcla=="%--%")
		{
			$ls_codcla="%%";
		}
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST['fecregdes']);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST['fecreghas']);
		$ls_tipdes=$_POST['tipdes'];
		$ls_codproben=$_POST['codproben'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		switch($ls_tipdes)
		{
			case "P":
				$ls_codpro=$ls_codproben;
				$ls_cedben="----------";
				break;
				
			case "B":
				$ls_codpro="----------";
				$ls_cedben=$ls_codproben;
				break;
			
			default:
				$ls_codpro="";
				$ls_cedben="";
				break;
		}
		$ls_criterio="";
		if($ls_procedencia!="RECEPCION")
		{
			if(($ls_codpro!="")&&($ls_cedben!=""))
			{
				$ls_criterio="   AND cod_pro='".$ls_codpro."'".
							 "   AND ced_bene='".$ls_cedben."'".
							 "   AND estaprord=1".
							 "   AND estprodoc='R' ";
			}
		}
		else
		{
			if(($ls_codpro!="")&&($ls_cedben!=""))
			{
				$ls_criterio="   AND cod_pro='".$ls_codpro."'".
							 "   AND ced_bene='".$ls_cedben."'";
			}
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
        $ls_sql="SELECT numrecdoc, numfac, cxp_rd.codtipdoc, ced_bene, cod_pro, codcla, dencondoc, fecemidoc, fecregdoc, fecvendoc, montotdoc, ".
				"		mondeddoc, moncardoc, tipproben, numref, estprodoc, procede, estlibcom, estaprord, estimpmun, ".
				"		estcon, estpre, cxp_documento.dentipdoc, cxp_rd.codfuefin, tepuy_fuentefinanciamiento.denfuefin, ".
				"		(CASE tipproben WHEN 'P' THEN (SELECT nompro FROM rpc_proveedor ".
				"									   WHERE rpc_proveedor.codemp = cxp_rd.codemp ".
				"										 AND rpc_proveedor.cod_pro = cxp_rd.cod_pro) ".
				"								 ELSE (SELECT ".$ls_cadena." FROM rpc_beneficiario ".
				"									    WHERE rpc_beneficiario.codemp = cxp_rd.codemp ".
				"									 	  AND rpc_beneficiario.ced_bene = cxp_rd.ced_bene) ".
				"		  END) AS nombre, ".
				"		(CASE tipproben WHEN 'P' THEN (SELECT rifpro FROM rpc_proveedor ".
				"									   WHERE rpc_proveedor.codemp = cxp_rd.codemp ".
				"										 AND rpc_proveedor.cod_pro = cxp_rd.cod_pro) ".
				"								 ELSE (SELECT rifben FROM rpc_beneficiario ".
				"									    WHERE rpc_beneficiario.codemp = cxp_rd.codemp ".
				"									 	  AND rpc_beneficiario.ced_bene = cxp_rd.ced_bene) ".
				"		  END) AS rif, ".
				"		(CASE tipproben WHEN 'P' THEN (SELECT trim(sc_cuenta) FROM rpc_proveedor ".
				"									   WHERE rpc_proveedor.codemp = cxp_rd.codemp ".
				"										 AND rpc_proveedor.cod_pro = cxp_rd.cod_pro) ".
				"								 ELSE (SELECT trim(sc_cuenta) FROM rpc_beneficiario ".
				"									    WHERE rpc_beneficiario.codemp = cxp_rd.codemp ".
				"									 	  AND rpc_beneficiario.ced_bene = cxp_rd.ced_bene) ".
				"		  END) AS sc_cuenta, ".
				"		(CASE tipproben WHEN 'P' THEN (SELECT tipconpro FROM rpc_proveedor ".
				"									   WHERE rpc_proveedor.codemp = cxp_rd.codemp ".
				"										 AND rpc_proveedor.cod_pro = cxp_rd.cod_pro) ".
				"								 ELSE (SELECT tipconben FROM rpc_beneficiario ".
				"									    WHERE rpc_beneficiario.codemp = cxp_rd.codemp ".
				"									 	  AND rpc_beneficiario.ced_bene = cxp_rd.ced_bene) ".
				"		  END) AS tipocont ".
				"  FROM cxp_rd, cxp_documento, tepuy_fuentefinanciamiento  ".
                " WHERE cxp_rd.codemp = '".$ls_codemp."' ".
				"   AND numrecdoc LIKE '".$ls_numrecdoc."' ".
				"   AND estprodoc LIKE '".$ls_estprodoc."' ".
				"   AND codcla LIKE '".$ls_codcla."' ".
				"   AND fecregdoc >= '".$ld_fecregdes."' ".
				"   AND fecregdoc <= '".$ld_fecreghas."' ".
				$ls_criterio.
				"	AND cxp_rd.codtipdoc = cxp_documento.codtipdoc ".
				"	AND cxp_rd.codemp = tepuy_fuentefinanciamiento.codemp ".
				"	AND cxp_rd.codfuefin = tepuy_fuentefinanciamiento.codfuefin ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
	//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Recepciones de Documentos ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
	//print "aqui";
			print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Ficha' align='center' onClick=ue_orden('numrecdoc')>".utf8_encode("Nro Ficha")."</td>";
			print "<td style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor/Beneficiario</td>";
			print "<td style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('fecregdoc')>Fecha Registro</td>";
			print "<td style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('estprodoc')>Estatus</td>";
			print "<td style='cursor:pointer' title='Ordenar por Monto Total' align='center' onClick=ue_orden('montotdoc')>Monto Total</td>";
			print "</tr>";
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_numfactura=$row["numfac"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_cedbene=$row["ced_bene"];
				$ls_codpro=$row["cod_pro"];
				$ls_codcla=$row["codcla"];
				$ls_dencondoc=utf8_encode($row["dencondoc"]);
				$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($row["fecemidoc"]);
				$ld_fecregdoc=$io_funciones->uf_convertirfecmostrar($row["fecregdoc"]);
				$ld_fecvendoc=$io_funciones->uf_convertirfecmostrar($row["fecvendoc"]);
				$li_montotdoc=$row["montotdoc"];
				$li_mondeddoc=$row["mondeddoc"];
				$li_moncardoc=$row["moncardoc"];
				$ls_tipproben=$row["tipproben"];
				$ls_numref=$row["numref"];
				$ls_estprodoc=$row["estprodoc"];
				$ls_procede=$row["procede"];
				$ls_estlibcom=$row["estlibcom"];
				$ls_estaprord=$row["estaprord"];
				$ls_estimpmun=$row["estimpmun"];
				$ls_nombre=utf8_encode($row["nombre"]);
				$ls_rif=$row["rif"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_tipocont=$row["tipocont"];
				$ls_estcon=$row["estcon"];
				$ls_estpre=$row["estpre"];
				$ls_dentipdoc=$row["dentipdoc"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				switch($ls_estprodoc)
				{
					case "R": 
						$ls_estatus="Recibida";
						break;
					case "E": 
						$ls_estatus="Emitida";
						break;
					case "C": 
						$ls_estatus="Contabilizada";
						break;
					case "A": 
						$ls_estatus="Anulada";
						break;
				}
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td with='100'><a href=\"javascript:ue_aceptar('".$ls_numrecdoc."','".$ls_numfactura."','".$ls_codtipdoc."','".$ls_cedbene."','".$ls_codpro."',";
						print "'".$ls_codcla."','".$ld_fecemidoc."','".$ld_fecregdoc."','".$ld_fecvendoc."','".$li_montotdoc."',";
						print "'".$li_mondeddoc."','".$li_moncardoc."','".$ls_tipproben."','".$ls_numref."','".$ls_estprodoc."','".$ls_procede."',";
						print "'".$ls_estlibcom."','".$ls_estaprord."','".$ls_estimpmun."','".$ls_nombre."','".$ls_rif."','".$ls_sccuenta."','".$ls_tipocont."',";
						print "'".$ls_estcon."','".$ls_estpre."','".$li_i."','".$ls_estatus."','".$ls_codfuefin."','".$ls_denfuefin."');\">".$ls_numrecdoc."</a></td>";
						print "<td with='200'>".$ls_nombre."</td>";
						print "<td with='100' align='center'>".$ld_fecregdoc."</td>";
						print "<td with='100' align='center'>".$ls_estatus."</td>";
						print "<td with='100' align='right'><input name='txtdencondoc".$li_i."' type='hidden' id='txtdencondoc".$li_i."' value='".$ls_dencondoc."'>".number_format($li_montotdoc,2,",",".")."</td>";
						print "</tr>";
					break;
					
					case "SOLICITUDPAGO":
						$li_montotdoc=number_format($li_montotdoc,2,",",".");
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar_solicitud('".$ls_numrecdoc."','".$ls_codtipdoc."','".$ls_dentipdoc."','".$li_montotdoc."','".$li_i."');\">".$ls_numrecdoc."</a></td>";
						print "<td with='200'>".$ls_nombre."</td>";
						print "<td with='100' align='center'>".$ld_fecregdoc."</td>";
						print "<td with='100' align='center'>".$ls_estatus."</td>";
//						print "<td with='100' align='right'>".$li_montotdoc."</td>";
						print "<td with='100' align='right'><input name='txtdencondoc".$li_i."' type='hidden' id='txtdencondoc".$li_i."' value='".$ls_dencondoc."'>".$li_montotdoc."</td>";
						print "</tr>";
						break;
				}
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
	}// end function uf_print_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_compromisos()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_compromisos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de compromisos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 09/05/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("tepuy_cxp_c_recepcion.php");
		$io_recepcion=new tepuy_cxp_c_recepcion("../../");		
		$ls_numdoc=$_POST['numdoc'];
		$ls_codtipdoc=substr($_POST["codtipdoc"],0,5);
		$ls_codigo=$_POST['codigo'];
		$ls_tipodes=$_POST['tipodes'];
		$ld_fechareg=$io_funciones->uf_convertirdatetobd($_POST['fechareg']);
		switch($ls_tipodes)
		{
			case "P":
				$ls_codprov=$ls_codigo;
				$ls_cedbene="----------";
				break;
			case "B":
				$ls_codprov="----------";
				$ls_cedbene=$ls_codigo;
				break;
		}
		$lb_valido=$io_recepcion->uf_select_solicitudes_pago($ls_numdoc,$ls_codtipdoc,$ls_codprov,$ls_cedbene);
		//print "numdoc".$ls_numdoc."codtipdoc".$ls_codtipdoc;
		if($lb_valido==true)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Compromisos ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			//print "tipo:".$ls_tipodes;
			$lb_valido=$io_recepcion->uf_load_comprobantes_positivos($ls_tipodes,$ls_codprov,$ls_cedbene,$ld_fechareg);
			if($lb_valido)
			{
				$li_totrow=$io_recepcion->io_ds_compromisos->getRowCount('comprobante');
				if($li_totrow>0)
				{
					print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "	<td align='center' >Comprobante</td>";
					print "	<td align='center' >Procede</td>";
					print "	<td align='center' >Fecha</td>";
					print "	<td align='left' >Descripción</td>";
					print "	<td align='rigth' >Total</td>";
					print "</tr>";
					for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
					{
						$ls_procede=$io_recepcion->io_ds_compromisos->data["procede"][$li_i];
						$ls_comprobante=$io_recepcion->io_ds_compromisos->data["comprobante"][$li_i];
						$li_total=$io_recepcion->io_ds_compromisos->data["total"][$li_i];				  
						$ls_descripcion=utf8_encode($io_recepcion->io_ds_compromisos->data["descripcion"][$li_i]);
						$ls_fecha=$io_recepcion->io_ds_compromisos->data["fecha"][$li_i];
						$li_monto_ajuste=0;
						$li_monto_causado=0;
						$li_monto_anulado=0;
						$li_monto_recepcion=0;
						$li_monto_ordenpago=0;
						$li_monto_cargo=0;
						$li_monto_solicitud=0;
						$li_disponible=0;
						$ls_numcomanu="";
						$lb_valido=$io_recepcion->uf_load_monto_ajustes($ls_comprobante,$ls_procede,$ls_tipodes,$ls_codprov,
																		$ls_cedbene,&$li_monto_ajuste);
						if($lb_valido)
						{
							$lb_valido=$io_recepcion->uf_load_monto_causados($ls_comprobante,$ls_procede,$ls_tipodes,$ls_codprov,
																			 $ls_cedbene,&$li_monto_causado);
						}
						if($lb_valido)
						{
							$lb_valido=$io_recepcion->uf_load_comprobantes_anulados($ls_comprobante,$ls_tipodes,$ls_codprov,
																					$ls_cedbene,$ld_fechareg,&$la_numcomanu);
						}
						if(($lb_valido)&&(!empty($la_numcomanu)))
						{
							$lb_valido=$io_recepcion->uf_load_monto_anulados($la_numcomanu,$ls_procede,$ls_tipodes,$ls_codprov,
																			 $ls_cedbene,&$li_monto_anulado);
						}
						if($lb_valido)
						{
							$lb_valido=$io_recepcion->uf_load_monto_recepciones($ls_comprobante,$ls_procede,
																				&$li_monto_recepcion);
						}
						if($lb_valido)
						{
							$lb_valido=$io_recepcion->uf_load_monto_ordenespago_directa($ls_comprobante,$ls_procede,
																						&$li_monto_ordenpago);
						}
						/*if($lb_valido)
						{
							$lb_valido=$io_recepcion->uf_load_monto_cargos($ls_comprobante,$ls_procede,&$li_monto_cargo);
						}*/
						if($lb_valido)
						{
//							$li_disponible=($li_total+$li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion-$li_monto_cargo;
							$li_disponible=($li_total+$li_monto_ajuste)-($li_monto_causado+$li_monto_anulado)-$li_monto_recepcion;
// 					print" DISPONIBLE->".$li_disponible." TOTAL->".$li_total." AJUSTE->".$li_monto_ajuste." CAUSADO->".$li_monto_causado." Anulado->".$li_monto_anulado." Recepcion->".$li_monto_recepcion."<br><br>";
							if($li_disponible>0)
							{
								$lb_valido=$io_recepcion->uf_load_acumulado_solicitudes($ls_numdoc,$ls_codtipdoc,$ls_codprov,
																						$ls_cedbene,&$li_monto_solicitud);
								if($lb_valido)
								{
									if($li_total==$li_monto_solicitud)
									{//Verificar que no existan solicitudes de pago con el monto igual a la RD.
										$lb_valido=false;
									}
								}
								//print "aqui";
								if($lb_valido)
								{
									print "<tr class=celdas-blancas>";
									print "	<td  width=110 align=center><a href=\"javascript: ue_aceptar('$ls_comprobante','$ls_procede',";
									print "  '$ls_fecha','$li_disponible','$li_monto_cargo','$li_i');\">".$ls_comprobante."</a></td>";
									print "	<td  width=80  align=center>".$ls_procede."</td>";
									print "	<td  width=80  align=center>".$io_funciones->uf_convertirfecmostrar($ls_fecha)."</td>";
									print " <td  width=330 align=left><textarea name=txtconcepto".$li_i." cols=50 rows=3 class=sin-borde id=txtconcepto".$li_i." readonly>".$ls_descripcion."</textarea></td>";
									print " <td  width=100 align=right>".number_format($li_disponible,2,',','.')."</td>";
									print "</tr>";
								}
							}
						}
					}
					print "</table>";
				}
				else
				{
        			$io_mensajes->message("ERROR->No hay comprobantes asociados a este Proveedor ó Beneficiario"); 
				}
			}
		}
		unset($io_mensajes);
		unset($io_funciones);
		unset($io_recepcion);
	}// end function uf_print_compromisos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudespago()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudespago
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de solicitudes de pago
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 29/04/2007 								Fecha Última Modificación : 
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
		$ls_numsol=$_POST['numsol'];
		$ld_fecdes=$_POST['fecemides'];
		$ld_fechas=$_POST['fecemihas'];
		$ls_tipdes=$_POST['tipdes'];
		$ls_codproben=$_POST['codproben'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_codpro="";
		$ls_cedben="";
		if($ls_tipo=='NCND')
		{
			$ls_aux=" AND (estprosol='C' OR estprosol='S')";			
		}
		else
		{
			$ls_aux="";
		}
		switch ($ls_tipdes)
		{
			case "P":
				$ls_codpro=trim($ls_codproben);
				$ls_cedben="----------";
			break;

			case "B":
				$ls_codpro="----------";
				$ls_cedben=trim($ls_codproben);
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
		$ls_sql="SELECT cxp_solicitudes.numsol, cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.sc_cuenta ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.sc_cuenta ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS sc_cuenta, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT scg_cuentas.denominacion ".
				"                                        FROM rpc_proveedor, scg_cuentas ".
				"                                       WHERE rpc_proveedor.codemp = scg_cuentas.codemp ".
				"										  AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
				"										  AND rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT scg_cuentas.denominacion ".
				"                                        FROM rpc_beneficiario, scg_cuentas ".
				"                                       WHERE rpc_beneficiario.codemp = scg_cuentas.codemp ".
				"										  AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
				"										  AND rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS denscg, ".
				"       (SELECT denfuefin".
				"		   FROM tepuy_fuentefinanciamiento".
				"         WHERE tepuy_fuentefinanciamiento.codemp=cxp_solicitudes.codemp".
				"           AND tepuy_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin".
				"  FROM cxp_solicitudes ".	
				" WHERE codemp='".$ls_codemp."' ".
				"   AND numsol LIKE '%".$ls_numsol."%' ".
				"   AND fecemisol >= '".$ld_fecdes."' ".
				"   AND fecemisol <= '".$ld_fechas."' ".
				"   AND cod_pro LIKE '%".$ls_codpro."%'".
				"   AND ced_bene LIKE '%".$ls_cedben."%'".$ls_aux.
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Orden de Pago ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else

		{
			print "<table width=520 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=60  style='cursor:pointer' title='Ordenar por Numero de Orden de Pago'       align='center' onClick=ue_orden('numsol')>".utf8_encode("Número de Orden de Pago")."</td>";
			print "<td width=300 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor/Beneficiario</td>";
			print "<td width=80  style='cursor:pointer' title='Ordenar por Fecha de Emision' align='center' onClick=ue_orden('fecemisol')>Fecha</td>";
			print "<td width=80  style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monsol')>Monto</td>";
			print "</tr>";
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_numsol=$row["numsol"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=utf8_encode($row["denfuefin"]);
				$ls_tipo_destino=$row["tipproben"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_denscg=$row["denscg"];
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
				$ls_consol=utf8_encode($row["consol"]);
				$ls_obssol=utf8_encode($row["obssol"]);
				$ls_estprosol=$row["estprosol"];
				$ls_estaprosol=$row["estaprosol"];
				$ld_fecemisol=$row["fecemisol"];
				$li_monsol=number_format($row["monsol"],2,',','.');
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				$ls_estatus="";
				switch ($ls_estprosol)
				{
					case "R":
						$ls_estatus="REGISTRO";
						break;
						
					case "S":
						$ls_estatus="PROGRAMACION DE PAGO";
						break;
						
					case "P":
						$ls_estatus="CANCELADA";
						break;

					case "A":
						$ls_estatus="ANULADA";
						break;
						
					case "C":
						$ls_estatus="CONTABILIZADA";
						break;
						
					case "E":
						$ls_estatus="EMITIDA";
						break;
				}
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codfuefin','$ls_denfuefin',";
						print "'$ls_codigo','$ls_nombre','$li_monsol','$ls_estprosol','$ls_estaprosol','$ld_fecemisol',";
						print "'$ls_estatus','$ls_tipo_destino','$li_i');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'><input name='txtconsol".$li_i."' type='hidden' id='txtconsol".$li_i."' value='".$ls_consol."'>".
							  "<input name='txtobssol".$li_i."' type='hidden' id='txtobssol".$li_i."' value='".$ls_obssol."'>".$li_monsol."</td>";
						print "</tr>";			
						break;
						
					case "NCND":
						if(!uf_chequear_cancelado($ls_numsol))
						{
							print "<tr class=celdas-blancas>";
							print "<td align='center'><a href=\"javascript: aceptarncnd('$ls_numsol','$ls_tipo_destino','$ls_codpro',";
							print "'$ls_cedbene','$ls_nombre','$ls_sccuenta','$ls_denscg');\">".$ls_numsol."</a></td>";
							print "<td align='left' width=230>".$ls_nombre."</td>";
							print "<td align='left'>".$ld_fecemisol."</td>";
							print "<td align='left'>".$li_monsol."</td>";
							print "</tr>";			
						}
						break;
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrepdes('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'>".$li_monsol."</td>";
						print "</tr>";	
						break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrephas('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'>".$li_monsol."</td>";
						print "</tr>";			
						break;
					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarmodcmpret('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'>".$li_monsol."</td>";
						print "</tr>";			
						break;
				}
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
	}// end function uf_print_solicitudespago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuentefinanciamiento()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_fuentefinanciamiento
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
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
		$ls_sql="SELECT codfuefin, denfuefin ".
				"  FROM tepuy_fuentefinanciamiento ".	
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codfuefin <> '--' ".		
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Fuentes de Financiamiento ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codfuefin')>Codigo</td>";
			print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denfuefin')>Denominacion</td>";
			print "</tr>";
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
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_fuentefinanciamiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesislr()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesislr
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de ISLR
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
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

		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ls_numsol=$_POST['numsol'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.fecemisol >= '".$ld_fecdes."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.fecmov >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.fecemisol <= '".$ld_fechas."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.fecmov <= '".$ld_fechas."'";
		}
		if($ls_codprodes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro >= '".$ls_codprodes."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.cod_pro >= '".$ls_codprodes."'";
		}
		if($ls_codprohas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro <= '".$ls_codprohas."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.cod_pro <= '".$ls_codprohas."'";
		}
		if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.ced_bene <= '".$ls_cedbenhas."'";
		}
		if($ls_numsol!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_solicitudes.numsol='".$ls_numsol."'";
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.numsol AS numero, cxp_solicitudes.consol AS concepto, cxp_rd.procede AS procede ".
				"  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, tepuy_deducciones ".
			    " WHERE cxp_solicitudes.codemp = '".$ls_codemp."' ".
				"   AND tepuy_deducciones.islr=1 ".
				$ls_criterio.
				"   AND cxp_solicitudes.estprosol<>'A'".
			    "   AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ".
			    "   AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
				"   AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
				"   AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
				"	AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
				"	AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
				"	AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
				"	AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
				"	AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"	AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"	AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"	AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"	AND cxp_rd_deducciones.codemp = tepuy_deducciones.codemp ".
				"	AND cxp_rd_deducciones.codded = tepuy_deducciones.codded ".
				" UNION ".
				"SELECT scb_movbco.numdoc AS numero, MAX(scb_movbco.conmov) AS concepto, MAX(scb_movbco.procede) AS procede ".
			    "  FROM scb_movbco, tepuy_deducciones, scb_movbco_scg ".
				" WHERE scb_movbco.codemp = '".$ls_codemp."' ".
				"   AND scb_movbco.codope = 'CH' ".
				"   AND scb_movbco.estmov <> 'A' ".
				"   AND scb_movbco.monret <> 0 ".
				"   AND tepuy_deducciones.islr = 1".
				$ls_criterio2.
				"    AND scb_movbco.codemp = scb_movbco_scg.codemp ".
				"    AND scb_movbco.codban = scb_movbco_scg.codban ".
				"    AND scb_movbco.ctaban = scb_movbco_scg.ctaban ".
				"    AND scb_movbco.numdoc = scb_movbco_scg.numdoc ".
				"    AND scb_movbco.codope = scb_movbco_scg.codope ".
				"    AND scb_movbco.estmov = scb_movbco_scg.estmov ".
				"    AND scb_movbco_scg.codemp = tepuy_deducciones.codemp ".
				"    AND scb_movbco_scg.codded = tepuy_deducciones.codded ".
				"  GROUP BY scb_movbco.numdoc ".
			    "  ORDER BY numero ";	
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones I.S.L.R. ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]="Nro Documento"; 
			$lo_title[3]="Concepto"; 
			$lo_title[4]="Procede"; 
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numero=$row["numero"];
				$ls_concepto=$row["concepto"];
				$ls_procede=$row["procede"];
				$lo_object[$li_totrow][1]="<input type=checkbox  name=checkcmp".$li_totrow."    id=checkcmp".$li_totrow."    value=1                  size=10  style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<input type=text      name=txtnumero".$li_totrow."   id=txtnumero".$li_totrow."   value='".$ls_numero."'   size=15  style=text-align:center  class=sin-borde readonly>"; 
				$lo_object[$li_totrow][3]="<input type=text      name=txtconcepto".$li_totrow." id=txtconcepto".$li_totrow." value='".$ls_concepto."' size=80 style=text-align:left    class=sin-borde readonly title='".$ls_concepto."' bgColor=#FF5500>";
				$lo_object[$li_totrow][4]="<input type=text      name=txtprocede".$li_totrow."  id=txtprocede".$li_totrow."  value='".$ls_procede."'  size=5   style=text-align:center  class=sin-borde readonly>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,550,'Comprobantes de Retención de I.S.L.R.','grid');
		}	unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesislr
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_catdeducciones()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_catdeducciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las deducciones
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 10/07/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codded="%".$_POST['codded']."%";
		$ls_dended="%".$_POST['dended']."%";
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT codded, dended ".
			    "  FROM tepuy_deducciones ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND codded like '".$ls_codded."' ".
				"   AND dended like '".$ls_dended."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Deducciones ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Código'       align='center' onClick=ue_orden('codded')>Código</td>";
			print "<td width=400 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('dended')>Denominacion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codded=trim($row["codded"]);
				$ls_dended=utf8_encode(rtrim($row["dended"]));
				if($ls_tipo=="rephas")
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'><a href=\"javascript: ue_aceptar_rephas('".$ls_codded."','".$ls_dended."');\">".$ls_codded."</a></td>";
					print "<td align='left'>".$ls_dended."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_codded."','".$ls_dended."');\">".$ls_codded."</a></td>";
					print "<td align='left'>".$ls_dended."</td>";
					print "</tr>";			
				}
				
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
	}// end function uf_print_catdeducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesiva()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesiva
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 12/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
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
		//print $ls_codemp;
		$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='I'",'C');
		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ls_numsol=$_POST['numsol'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		$ls_tabla="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if(($ls_codprodes!="")||($ls_codprohas!="")||($ls_cedbendes!="")||($ls_cedbenhas!=""))
		{
			$ls_tabla=",cxp_solicitudes";
			$ls_criterio=$ls_criterio."   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ";
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprodes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro >= '".$ls_codprodes."'";
		}
		if($ls_codprohas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro <= '".$ls_codprohas."'";
		}
		if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.nomsujret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".$ls_tabla.
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				$ls_criterio.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				" ORDER BY scb_cmp_ret.numcom ";
		//print $ls_lsq;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones IVA ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]="<input type=checkbox name=checkall id=checkall value=1 size=10 style=text-align:left  class=sin-borde onclick='javascript:uf_checkall();'>";
			$lo_title[2]="Nro Comprobante"; 
			$lo_title[3]="Fecha"; 
			$lo_title[4]="Proveedor / Beneficiario"; 
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_nomsujret=$row["nomsujret"];
				$ld_fecrep=$io_funciones->uf_convertirfecmostrar($row["fecrep"]);
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<input type=text     name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    size=15 style=text-align:center  class=sin-borde readonly>"; 
				$lo_object[$li_totrow][3]="<input type=text     name=txtfecrep".$li_totrow."    id=txtfecrep".$li_totrow."    value='".$ld_fecrep."'    size=10 style=text-align:center  class=sin-borde readonly>";
				$lo_object[$li_totrow][4]="<input type=text     name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' size=75 style=text-align:left    class=sin-borde readonly title='".$ls_nomsujret."' bgColor=#FF5500>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,550,'Comprobantes de Retención de IVA','grid');
		}	unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesiva
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesaporte()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesaporte
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de aporte social
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 12/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
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
		//print $ls_codemp;
		$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='O'",'C');
		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ls_numsol=$_POST['numsol'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		$ls_tabla="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if(($ls_codprodes!="")||($ls_codprohas!="")||($ls_cedbendes!="")||($ls_cedbenhas!=""))
		{
			$ls_tabla=",cxp_solicitudes";
			$ls_criterio=$ls_criterio."   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ";
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprodes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro >= '".$ls_codprodes."'";
		}
		if($ls_codprohas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.cod_pro <= '".$ls_codprohas."'";
		}
		if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.nomsujret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".$ls_tabla.
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				$ls_criterio.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				" ORDER BY scb_cmp_ret.numcom ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones Aporte Social ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]="<input type=checkbox name=checkall id=checkall value=1 size=10 style=text-align:left  class=sin-borde onclick='javascript:uf_checkall();'>";
			$lo_title[2]="Nro Comprobante"; 
			$lo_title[3]="Fecha"; 
			$lo_title[4]="Proveedor / Beneficiario"; 
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_nomsujret=$row["nomsujret"];
				$ld_fecrep=$io_funciones->uf_convertirfecmostrar($row["fecrep"]);
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<input type=text     name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    size=15 style=text-align:center  class=sin-borde readonly>"; 
				$lo_object[$li_totrow][3]="<input type=text     name=txtfecrep".$li_totrow."    id=txtfecrep".$li_totrow."    value='".$ld_fecrep."'    size=10 style=text-align:center  class=sin-borde readonly>";
				$lo_object[$li_totrow][4]="<input type=text     name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' size=75 style=text-align:left    class=sin-borde readonly title='".$ls_nomsujret."' bgColor=#FF5500>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,550,'Comprobantes de Retención de IVA','grid');
		}	unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesaporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesmunicipales()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesmunicipales
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 15/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='M'",'C');
		/*$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ld_fecdesde=$ls_anio."-".$ls_mes."-01";
		$ld_fechasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
		$ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND fecrep>='".$ld_fecdesde."' ".
				"   AND fecrep<='".$ld_fechasta."' ".
				"   AND codret='0000000002' ".
				" ORDER BY numcom";*/
$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		//$ls_tipo="0000000002";
		$ls_numsol=$_POST['numsol'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret >= '".$ls_codprobendes."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret <= '".$ls_codprobendes."'";
		}
	/*	if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}*/
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				$ls_criterio.
				" ORDER BY scb_cmp_ret.numcom "; 
		//print $ls_sql;	 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones Muncipales ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]="Comprobante";     
			$lo_title[3]="Codigo Proveedor/Beneficiario";   
			$lo_title[4]="Nombre Proveedor/Beneficiario";   
			$lo_title[5]="Dirección";  
			$lo_title[6]="R.I.F.";   
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			$lo_object[$li_totrow][5]="";
			$lo_object[$li_totrow][6]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_dirsujret=$row["dirsujret"];
				$ls_rif=$row["rif"];
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][3]="<div align=center><input type=text name=txtcodsujret".$li_totrow." id=txtcodsujret".$li_totrow." value='".$ls_codsujret."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][4]="<div align=left><input   type=text name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' class=sin-borde readonly style=text-align:left size=25 maxlength=80></div>";
				$lo_object[$li_totrow][5]="<div align=left><input   type=text name=txtdirsujret".$li_totrow." id=txtdirsujret".$li_totrow." value='".$ls_dirsujret."' class=sin-borde readonly style=text-align:left size=35 maxlength=200></div>";
				$lo_object[$li_totrow][6]="<div align=center><input type=text name=txtrif".$li_totrow."       id=txtrif".$li_totrow."       value='".$ls_rif."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,750,'Comprobantes de Retención Municipal','grid');
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesmunicipales
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionestimbrefiscal()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesmunicipales
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 15/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];

		/*$ls_mes=$_POST['mes'];

		$ls_anio=$_POST['anio'];
		$ld_fecdesde=$ls_anio."-".$ls_mes."-01";
		$ld_fechasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
		$ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif ".
				"  FROM scb_cmp_ret ".

				" WHERE codemp = '".$ls_codemp."' ".
				"   AND fecrep>='".$ld_fecdesde."' ".
				"   AND fecrep<='".$ld_fechasta."' ".
				"   AND codret='0000000002' ".
				" ORDER BY numcom";*/
		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		//$ls_tipo="0000000005";

		$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='T'",'C');
		//print $ls_tiporeten;
		$ls_numsol=$_POST['numsol'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":

				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret >= '".$ls_codprobendes."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret <= '".$ls_codprobendes."'";
		}
	/*	if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}*/
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				$ls_criterio.
				" ORDER BY scb_cmp_ret.numcom "; 
		//print $ls_sql;	 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones de Timbre Fiscal ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]="Comprobante";     
			$lo_title[3]="Codigo Proveedor/Beneficiario";   
			$lo_title[4]="Nombre Proveedor/Beneficiario";   
			$lo_title[5]="Dirección";  
			$lo_title[6]="R.I.F.";   
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			$lo_object[$li_totrow][5]="";
			$lo_object[$li_totrow][6]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_dirsujret=$row["dirsujret"];
				$ls_rif=$row["rif"];
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][3]="<div align=center><input type=text name=txtcodsujret".$li_totrow." id=txtcodsujret".$li_totrow." value='".$ls_codsujret."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][4]="<div align=left><input   type=text name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' class=sin-borde readonly style=text-align:left size=25 maxlength=80></div>";
				$lo_object[$li_totrow][5]="<div align=left><input   type=text name=txtdirsujret".$li_totrow." id=txtdirsujret".$li_totrow." value='".$ls_dirsujret."' class=sin-borde readonly style=text-align:left size=35 maxlength=200></div>";
				$lo_object[$li_totrow][6]="<div align=center><input type=text name=txtrif".$li_totrow."       id=txtrif".$li_totrow."       value='".$ls_rif."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,750,'Comprobantes de Retención de Timbre Fiscal','grid');
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionestimbrefiscal
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesunificadas()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesmunicipales
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 15/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		// NO IMPROTA EL TIPO DE RETENCION
		//$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='M'",'C');
		//$ls_municipal=uf_obtengo_retencion("SELECT nom FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='M'",'N');
		//$ls_iva=uf_obtengo_retencion("SELECT nom FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='I'",'N');
		//$ls_timbrefiscal=uf_obtengo_retencion("SELECT nom FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='T'",'N');
		//$ls_islr=uf_obtengo_retencion("SELECT nom FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='S'",'N');
		/*$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ld_fecdesde=$ls_anio."-".$ls_mes."-01";
		$ld_fechasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
		$ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND fecrep>='".$ld_fecdesde."' ".
				"   AND fecrep<='".$ld_fechasta."' ".
				"   AND codret='0000000002' ".
				" ORDER BY numcom";*/
		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		//$ls_tipo="0000000002";
		$ls_numsol=$_POST['numsol'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret >= '".$ls_codprobendes."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret <= '".$ls_codprobendes."'";
		}
	/*	if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}*/
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret, cxp_contador.nom,scb_dt_cmp_ret.numfac,scb_dt_cmp_ret.numsop,scb_cmp_ret.fecrep ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret, cxp_contador, cxp_solicitudes ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				//"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = cxp_contador.codcmp ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
				"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				$ls_criterio.
				//" ORDER BY scb_cmp_ret.numcom,scb_dt_cmp_ret.codret GROUP BY scb_cmp_ret.codsujret ";
				" GROUP BY scb_cmp_ret.codsujret,  scb_dt_cmp_ret.numsop ORDER BY scb_cmp_ret.fecrep ASC ";  
		//print $ls_sql;	 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones Unificadas ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			//$lo_title[2]="Comprobantes";
			//$lo_title[2]="Tipo";
			//$lo_title[4]="Codigo Proveedor/Beneficiario";
			$lo_title[2]="Fecha";
			$lo_title[3]="Nro. de Ficha";
			$lo_title[4]="Orden de Pago";
			$lo_title[5]="Nombre Proveedor/Beneficiario";   
			//$lo_title[7]="Dirección";  
			$lo_title[6]="R.I.F.";   
			$lo_title[7]="";
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			$lo_object[$li_totrow][5]="";
			$lo_object[$li_totrow][6]="";
			$lo_object[$li_totrow][7]="";
			//$lo_object[$li_totrow][8]="";
			$ls_numfacanterior="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_fecrep=$row["fecrep"];
				$ls_fecha=substr($ls_fecrep,8,2)."-".substr($ls_fecrep,5,2)."-".substr($ls_fecrep,0,4);
				$ls_nomcom=$row["nom"];
				$ls_numfac=$row["numfac"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_dirsujret=$row["dirsujret"];
				$ls_ordenpago=$row["numsop"];
				$ls_codret=$row["codret"];
				$ls_rif=$row["rif"];
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				//$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtfecha".$li_totrow."    id=txtfecha".$li_totrow."    value='".$ls_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				//$lo_object[$li_totrow][4]="<div align=center><input type=text name=txtcodsujret".$li_totrow." id=txtcodsujret".$li_totrow." value='".$ls_codsujret."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][3]="<div align=center><input type=text name=txtnumfac".$li_totrow." id=txtnumfac".$li_totrow." value='".$ls_numfac."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][4]="<div align=left><input   type=text name=txtordenpago".$li_totrow." id=txtordenpago".$li_totrow." value='".$ls_ordenpago."' class=sin-borde readonly style=text-align:left size=15 maxlength=15></div>";
				$lo_object[$li_totrow][5]="<div align=left><input   type=text name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' class=sin-borde readonly style=text-align:left size=50 maxlength=100></div>";
				//$lo_object[$li_totrow][6]="<div align=left><input   type=text name=txtdirsujret".$li_totrow." id=txtdirsujret".$li_totrow." value='".$ls_dirsujret."' class=sin-borde readonly style=text-align:left size=35 maxlength=200></div>";
				$lo_object[$li_totrow][6]="<div align=center><input type=text name=txtrif".$li_totrow."       id=txtrif".$li_totrow."       value='".$ls_rif."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][7]="<div align=center><input type=hidden name=txtcodret".$li_totrow."       id=txtcodret".$li_totrow."       value='".$ls_codret."'       class=sin-borde readonly style=text-align:center size=1 maxlength=1></div>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,750,'Comprobantes de Retención Unificado','grid');
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesunificadas
	//-----------------------------------------------------------------------------------------------------------------------------------




//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesislrnuevo()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesmunicipales
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 15/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];

		/*$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ld_fecdesde=$ls_anio."-".$ls_mes."-01";
		$ld_fechasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
		$ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND fecrep>='".$ld_fecdesde."' ".
				"   AND fecrep<='".$ld_fechasta."' ".
				"   AND codret='0000000003' ".
				" ORDER BY numcom";	*/
		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		//$ls_tipo="0000000003";
		$ls_tiporeten=uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='S'",'C');
		$ls_numsol=$_POST['numsol'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret >= '".$ls_codprobendes."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret <= '".$ls_codprobendes."'";
		}
	/*	if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}*/
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tiporeten."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				$ls_criterio.
				" ORDER BY scb_cmp_ret.numcom "; 
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones ISLR ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]="Comprobante";     
			$lo_title[3]="Codigo Proveedor/Beneficiario";   
			$lo_title[4]="Nombre Proveedor/Beneficiario";   
			$lo_title[5]="Direccion";  
			$lo_title[6]="R.I.F.";   
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			$lo_object[$li_totrow][5]="";
			$lo_object[$li_totrow][6]="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numcom=$row["numcom"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_dirsujret=$row["dirsujret"];
				$ls_rif=$row["rif"];
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][3]="<div align=center><input type=text name=txtcodsujret".$li_totrow." id=txtcodsujret".$li_totrow." value='".$ls_codsujret."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][4]="<div align=left><input   type=text name=txtnomsujret".$li_totrow." id=txtnomsujret".$li_totrow." value='".$ls_nomsujret."' class=sin-borde readonly style=text-align:left size=25 maxlength=80></div>";
				$lo_object[$li_totrow][5]="<div align=left><input   type=text name=txtdirsujret".$li_totrow." id=txtdirsujret".$li_totrow." value='".$ls_dirsujret."' class=sin-borde readonly style=text-align:left size=35 maxlength=200></div>";
				$lo_object[$li_totrow][6]="<div align=center><input type=text name=txtrif".$li_totrow."       id=txtrif".$li_totrow."       value='".$ls_rif."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,750,'Comprobantes de Retención ISLR','grid');
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesislrnuevo
	//-----------------------------------------------------------------------------------------------------------------------------------



	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_recepcionesncnd()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcion que obtiene e imprime los resultados de la busqueda de las recepciones de documento asociadas
		//				   a la solicitud de pago seleccionada
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  08/04/2007 								Fecha ltima Modificacin : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
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
		$ls_numord=$_POST["numord"];
		$ls_tipo=$_POST["tipo"];
		$ls_codproben=$_POST["codproben"];
		$ls_orden    = $io_funciones_cxp->uf_obtenervalor("orden","");
		$ls_campoorden=$io_funciones_cxp->uf_obtenervalor("campoorden","");
		$li=0;
		$ls_aux="";
		$ls_codpro="";
		$ls_cedbene="";
		if($ls_tipo=='P')
		{
			$ls_destino="Proveedor";
			$ls_cedbene="";
			$ls_codpro=$ls_codproben;
			$ls_aux=" AND sol.cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipo=='B')
		{
			$ls_destino="Beneficiario";
			$ls_codpro="";
			$ls_cedbene=$ls_codproben;
			$ls_aux=" AND sol.ced_bene='".$ls_codproben."' ";
		}
		
		$ls_sql=" SELECT sol.numsol,dt.numrecdoc,dt.codtipdoc,dt.monto,sol.tipproben,sol.fecemisol,dt.codtipdoc,doc.dentipdoc,doc.estcon,doc.estpre".
				" FROM	 cxp_dt_solicitudes dt,cxp_solicitudes sol,cxp_documento doc".
				" WHERE  sol.codemp='".$ls_codemp."' AND sol.codemp=dt.codemp AND dt.codtipdoc=doc.codtipdoc".
				" AND    sol.numsol ='".$ls_numord."' ".$ls_aux." AND sol.numsol=dt.numsol ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error en catalogo de Recepciones de Documento","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Numero de Orden'     align='center' onClick=ue_orden('sol.numsol')>Numero de Orden</td>";
			print "<td style='cursor:pointer' title='Ordenar por Numero de Ficha' align='center' onClick=ue_orden('dt.numrecdoc')>Numero de Ficha</td>";
			print "<td style='cursor:pointer' title='Ordenar por Fecha'               align='center' onClick=ue_orden('sol.fecemisol')>Fecha de Emision</td>";
			print "<td style='cursor:pointer' title='Ordenar por Monto'               align='center' onClick=ue_orden('dt.monto')>Monto Recepcion</td>";
			print "<td style='cursor:pointer' title='Ordenar por Tipo Documento'      align='center' onClick=ue_orden('dt.codtipdoc')>Tipo Documento</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li++;
				$ls_numord=$row["numsol"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ld_fecha =$io_funciones->uf_convertirfecmostrar($row["fecemisol"]);
				$ls_tipproben=$row["tipproben"];
				$ldec_monto=$row["monto"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_dentipdoc=utf8_encode($row["dentipdoc"]);
				$li_estcon   =$row["estcon"];
				$li_estpre   =$row["estpre"];
				print "<tr class=celdas-blancas>";
				print "<td align='center'><a href=\"javascript: aceptar('$ls_codemp','$ls_numrecdoc','$ls_codtipdoc','$ls_dentipdoc','$ls_tipo','$ls_codpro','$ls_cedbene','$li_estcon','$li_estpre');\">".$ls_numord."</a></td>";
				print "<td align='center'>".$ls_numrecdoc."</td>";
				print "<td align='center'>".$ld_fecha."</td>";
				print "<td align='right'>".number_format($ldec_monto,2,",",".")."</td>";	
				print "<td align='center'>".$ls_dentipdoc."</td>";	
				print "</tr>";			
			}
			if($li==0)
			{
				$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();"); 				
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
	}// end function uf_print_reecepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dtpresupuestario()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_dtpresupuestario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcin que obtiene e imprime los resultados de la busqueda del detalle presupestario de la recepcion
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  08/04/2007 								Fecha ltima Modificacin : 03/06/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_modalidad=$_SESSION['la_empresa']['estmodest'];
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc",""); 
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc",""); 
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben",""); 
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota",""); 
		$li=0;
		$ls_aux="";
		$ls_codpro="";
		$ls_cedbene="";
		if($ls_tipproben=='P')
		{
			$ls_destino="Proveedor";
			$ls_cedbene="";
			$ls_codpro=$ls_codproben;
			$ls_aux=" AND rd.cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipproben=='B')
		{
			$ls_destino="Beneficiario";
			$ls_codpro="";
			$ls_cedbene=$ls_codproben;
			$ls_aux=" AND rd.ced_bene='".$ls_codproben."' ";
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_aux_estpro=" AND rd.codestpro=CONCAT(spg.codestpro1,spg.codestpro2,spg.codestpro3,spg.codestpro4,spg.codestpro5) ";
			$ls_aux_where =" AND CONCAT(rd.codestpro,rd.spg_cuenta,rd.ced_bene,rd.cod_pro,rd.codtipdoc,rd.numrecdoc,rd.numdoccom)
							 NOT IN (SELECT CONCAT(x.codestpro1,x.codestpro2,x.codestpro3,x.codestpro4,x.codestpro5,x.spg_cuenta,
							 					   x.ced_bene,x.cod_pro,x.codtipdoc,x.numrecdoc,x.numdoccom) FROM cxp_rd_cargos x) ";
		}
		else
		{
			$ls_aux_estpro=" AND rd.codestpro=spg.codestpro1||spg.codestpro2||spg.codestpro3||spg.codestpro4||spg.codestpro5 ";
			$ls_aux_where =" AND rd.codestpro||rd.spg_cuenta||rd.ced_bene||rd.cod_pro||rd.codtipdoc||rd.numrecdoc||rd.numdoccom
							 NOT IN (SELECT x.codestpro1||x.codestpro2||x.codestpro3||x.codestpro4||x.codestpro5||x.spg_cuenta||
							 					   x.ced_bene||x.cod_pro||x.codtipdoc||x.numrecdoc||x.numdoccom FROM cxp_rd_cargos x) ";
		}	
		$ls_sql=" SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom, rd.codestpro,
						 rd.spg_cuenta, rd.monto,spg.denominacion ,spg.sc_cuenta,scg.denominacion as denscg ".
				" FROM cxp_rd_spg rd,spg_cuentas spg,scg_cuentas scg ".
				" WHERE  rd.codemp='".$ls_codemp."' ".
				" AND    rd.numrecdoc='".$ls_numrecdoc."' AND rd.codtipdoc='".$ls_codtipdoc."' ".$ls_aux. 
				" AND rd.codemp=spg.codemp AND rd.spg_cuenta=spg.spg_cuenta AND rd.codemp=scg.codemp ".
				" AND spg.sc_cuenta=scg.sc_cuenta ".$ls_aux_estpro." ".$ls_aux_where.
				" ORDER BY rd.spg_cuenta ASC" ;

		$rs_data=$io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar detalles presupuestarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro=$row["codestpro"];
				$ls_codestproaux=$ls_codestpro;
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
						$ls_codestpro=substr($ls_codestpro,0,29);
						break;						
					case "2": // Modalidad por Programa
						$ls_codestpro1=substr(substr($ls_codestpro,0,20),-2);
						$ls_codestpro2=substr(substr($ls_codestpro,20,6),-2);
						$ls_codestpro3=substr(substr($ls_codestpro,26,3),-2);
						$ls_codestpro4=substr($ls_codestpro,29,2);
						$ls_codestpro5=substr($ls_codestpro,31,2);
						$ls_codestpro=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
						break;
				}
				$ls_spgcuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$ldec_montoant=uf_verificar_anterior($ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,$ls_codestproaux,$ls_spgcuenta);
				if($ls_tiponota=='NC')
				{
					$ldec_disponible=$ldec_monto-abs($ldec_montoant);
				}
				else
				{
					$ldec_disponible=$ldec_monto+abs($ldec_montoant);
				}
				//$ldec_disponible=$ldec_monto+$ldec_montoant;
				
				if($ldec_disponible>0)
				{
					$li++;			
					$ls_dencuenta=utf8_encode($row["denominacion"]);
					$ls_scgcuenta=$row["sc_cuenta"];
					$ls_denscg=utf8_encode($row["denscg"]);
					$lo_object[$li][1]="<input type=checkbox name=chk".$li."      id=chk".$li." class=sin-borde >";
					$lo_object[$li][2]="<input type=text name=txtcodestpro".$li."    class=sin-borde style=text-align:center size=37 value='".$ls_codestpro."'    readonly>";
					$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li."    class=sin-borde style=text-align:center size=16  value='".$ls_spgcuenta."'     readonly><input type=hidden name=txtscgcuenta".$li."  value='".$ls_scgcuenta."'><input type=hidden name=txtdenscgcuenta".$li."  value='".$ls_denscg."'>"; 
					$lo_object[$li][4]="<input type=text name=txtmonto".$li."        class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_disponible,2,",",".")."' onBlur='javascript:uf_format(this);uf_valida_monto($li);'  onKeyPress=return(ue_formatonumero(this,'.',',',event));><input type=hidden name=txtmontooriginal".$li." value='".$ldec_disponible."'>";
					$lo_object[$li][5]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left  size=50 value='".$ls_dencuenta."' readonly>";
				}
				else
				{
					if($ls_tiponota=='ND')
					{
						$li++;			
						$ls_dencuenta=utf8_encode($row["denominacion"]);
						$ls_scgcuenta=$row["sc_cuenta"];
						$ls_denscg=utf8_encode($row["denscg"]);
						$lo_object[$li][1]="<input type=checkbox name=chk".$li."      id=chk".$li." class=sin-borde >";
						$lo_object[$li][2]="<input type=text name=txtcodestpro".$li."    class=sin-borde style=text-align:center size=37 value='".$ls_codestpro."'    readonly>";
						$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li."    class=sin-borde style=text-align:center size=16  value='".$ls_spgcuenta."'     readonly><input type=hidden name=txtscgcuenta".$li."  value='".$ls_scgcuenta."'><input type=hidden name=txtdenscgcuenta".$li."  value='".$ls_denscg."'>"; 
						$lo_object[$li][4]="<input type=text name=txtmonto".$li."        class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_monto,2,",",".")."' onBlur='javascript:uf_format(this);uf_valida_monto($li);'  onKeyPress=return(ue_formatonumero(this,'.',',',event));><input type=hidden name=txtmontooriginal".$li." value='".$ldec_monto."'>";
						$lo_object[$li][5]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left  size=50 value='".$ls_dencuenta."' readonly>";
					}
				}

			}
			if($li==0)
			{
				$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();");
				$lo_object=array();				
			}
			// Titulos del Grid de Bienes
			$lo_title[1]=" ";
			$lo_title[2]="Codigo Programatico";
			$lo_title[3]="Codigo Estadistico";
			$lo_title[4]="Monto";
			$lo_title[5]="Denominaci&oacute;n";
			print "<input name=totalrows type=hidden id=totalrows value=$li>";
			$io_grid->makegrid($li,$lo_title,$lo_object,758,"Registrar Detalle Presupuestario","grid");
			$io_sql->free_result($rs_data);	
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_dtpresupuestario
	//--------------------------------------
	function uf_verificar_anterior($ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,$ls_codestproaux,$ls_spgcuenta)
	{
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		$io_function=new class_funciones();
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ldec_monto=0;
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipproben=='B')
		{
			$ls_aux=" AND ced_bene='".$ls_codproben."' ";
		}
		$ls_sql=" SELECT SUM(monto) as monto  ".
				"   FROM  cxp_dc_spg ".
				"  WHERE  codemp='".$ls_codemp."' ".
				"    AND  numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' ".$ls_aux. 
				"    AND  codestpro ='".$ls_codestproaux."' AND spg_cuenta='".$ls_spgcuenta."' ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al calcular disponible presupuestario","ERROR->".$io_function->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
			}
			$io_sql->free_result($rs_data);
		}		
		return $ldec_monto;	
	}
	
	function uf_verificar_contable($ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,$ls_scgcuenta,$ls_debhab)
	{
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ldec_monto=0;
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipproben=='B')
		{
			$ls_aux=" AND ced_bene='".$ls_codproben."' ";
		}
		$ls_sql=" SELECT SUM(monto) as monto ".
				"   FROM  cxp_dc_scg  ".
				"  WHERE  codemp='".$ls_codemp."' ".
				"    AND  numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' ".$ls_aux. 
				"    AND sc_cuenta='".$ls_scgcuenta."'  AND debhab='".$ls_debhab."' ";

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al calcular disponible contable","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
			}
			$io_sql->free_result($rs_data);
		}
		return $ldec_monto;	
	}
	
	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dtcontable()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_dtcontable
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcin que obtiene e imprime los resultados de la busqueda del detalle contable de la recepcion de documento
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  22/05/2007 								Fecha ltima Modificacin : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc",""); 
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc",""); 
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben",""); 
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota",""); 
		$ls_ctaprov=$io_funciones_cxp->uf_obtenervalor("ctaprov",""); 
		$li=0;
		$ls_aux="";
		$ls_codpro="";
		$ls_cedbene="";
		if($ls_tipproben=='P')
		{
			$ls_destino="Proveedor";
			$ls_cedbene="";
			$ls_codpro=$ls_codproben;
			$ls_aux=" AND rd.cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipproben=='B')
		{
			$ls_destino="Beneficiario";
			$ls_codpro="";
			$ls_cedbene=$ls_codproben;
			$ls_aux=" AND rd.ced_bene='".$ls_codproben."' ";
		}
	
		$ls_sql=" SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom,
						 rd.sc_cuenta, rd.monto,scg.denominacion,rd.debhab ".
				" FROM cxp_rd_scg rd,scg_cuentas scg ".
				" WHERE  rd.codemp='".$ls_codemp."' ".
				" AND    rd.numrecdoc='".$ls_numrecdoc."' AND rd.codtipdoc='".$ls_codtipdoc."' AND rd.sc_cuenta<>'$ls_ctaprov' ".$ls_aux. 
				" AND rd.codemp=scg.codemp AND rd.sc_cuenta=scg.sc_cuenta ".
				" AND CONCAT(rd.codtipdoc,rd.numrecdoc,rd.ced_bene,rd.cod_pro,rd.numdoccom,rd.sc_cuenta) NOT IN (SELECT CONCAT(x.codtipdoc,x.numrecdoc,x.ced_bene,x.cod_pro,x.numdoccom,x.sc_cuenta) FROM cxp_rd_deducciones x)".
				" ORDER BY rd.sc_cuenta ASC,rd.debhab ASC" ;

		$rs_data=$io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar detalles contables ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_scgcuenta=$row["sc_cuenta"];
				$ldec_monto=$row["monto"];
				$ls_dencuenta=utf8_encode($row["denominacion"]);
				$ls_debhab=$row["debhab"];
				$ldec_montoant=uf_verificar_contable($ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,$ls_scgcuenta,$ls_debhab);
				if($ls_tiponota=='NC')
				{
					$ldec_disponible=$ldec_monto-$ldec_montoant;
				}
				else
				{
					$ldec_disponible=$ldec_monto+$ldec_montoant;
				}
				if($ldec_disponible>0)
				{
					$li++;		
					if($ls_debhab=='D')
					{
						$ldec_mondeb=number_format($ldec_disponible,2,",",".");
						$ldec_monhab="0,00";
						$lb_enabledeb="";
						$lb_enablehab="readonly";
					}
					else
					{
						$ldec_monhab=number_format($ldec_disponible,2,",",".");
						$ldec_mondeb="0,00";
						$lb_enabledeb="readonly";
						$lb_enablehab="";
					}
					$lo_object[$li][1]="<input type=checkbox name=chkcont".$li."     id=chkcont".$li." class=sin-borde ><input type=hidden name=txtdebhab".$li." value='".$ls_debhab."'>";
					$lo_object[$li][2]="<input type=text name=txtscgcuenta".$li."    class=sin-borde style=text-align:center size=22 value='".$ls_scgcuenta."'    readonly>";
					$lo_object[$li][3]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left   size=54  value='".$ls_dencuenta."'     readonly>"; 
					$lo_object[$li][4]="<input type=text name=txtmondeb".$li."       class=sin-borde style=text-align:right  size=20 value='".$ldec_mondeb."' onBlur='javascript:uf_format(this);uf_valida_monto($li,'D')'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); ".$lb_enabledeb."><input type=hidden name=txtmontooriginaldeb".$li." value='".$ldec_disponible."'>";
					$lo_object[$li][5]="<input type=text name=txtmonhab".$li."       class=sin-borde style=text-align:right  size=20 value='".$ldec_monhab."' onBlur='javascript:uf_format(this);uf_valida_monto($li,'H')'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); ".$lb_enablehab."><input type=hidden name=txtmontooriginalhab".$li." value='".$ldec_disponible."'>";
				}
			}
			if($li==0)
			{
				$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();"); 	
				$lo_object=array();			
			}
			// Titulos del Grid de Bienes
			$lo_title[1]=" ";
			$lo_title[2]="Cuenta Contable";
			$lo_title[3]="Denominaci&oacute;n";
			$lo_title[4]="Debe";
			$lo_title[5]="Haber";
			print "<input name=totalrows type=hidden id=totalrows value=$li>";
			$io_grid->makegrid($li,$lo_title,$lo_object,758,"Registro de Detalle Contable","grid");
			$io_sql->free_result($rs_data);	
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_dtcontable
	//--------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_notas()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcion que obtiene e imprime los resultados de la busqueda de las notas de debito o credito
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  28/05/2007 								Fecha ltima Modificacin : 03/06/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
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
		$ls_numncnd  = "%".$io_funciones_cxp->uf_obtenervalor("numncnd","")."%";
		$ls_tipo     = $io_funciones_cxp->uf_obtenervalor("tipo","");
		$ls_codproben= $io_funciones_cxp->uf_obtenervalor("codproben","");
		$ls_dennota  = "%".$io_funciones_cxp->uf_obtenervalor("dennota","")."%";
		$ld_fecdesde = $io_funciones->uf_convertirdatetobd($io_funciones_cxp->uf_obtenervalor("fecdesde",""));
		$ld_fechasta = $io_funciones->uf_convertirdatetobd($io_funciones_cxp->uf_obtenervalor("fechasta",""));
		$ls_orden    = $io_funciones_cxp->uf_obtenervalor("orden","");
		$ls_campoorden=$io_funciones_cxp->uf_obtenervalor("campoorden","");
		$li=0;
		$ls_aux="";
		$ls_codpro="";
		$ls_cedbene="";
		if($ls_tipo=='P')
		{
			$ls_destino="Proveedor";
			$ls_cedbene="";
			$ls_codpro=$ls_codproben;
			$ls_aux=" AND cxp.cod_pro='".$ls_codproben."' ";			
			$ls_aux_nomben=" prov.nompro as nomproben,prov.sc_cuenta as sc_cuenta";
		}
		elseif($ls_tipo=='B')
		{
			$ls_destino="Beneficiario";
			$ls_codpro="";
			$ls_cedbene=$ls_codproben;
			$ls_aux=" AND cxp.ced_bene='".$ls_codproben."' ";
			if($_SESSION["ls_gestor"]=="MYSQL")
			{
				$ls_aux_nomben=" CONCAT(ben.nombene,'  ',ben.apebene) as nomproben,ben.sc_cuenta as sc_cuenta";
			}
			else
			{
				$ls_aux_nomben=" (ben.nombene||'  '||ben.apebene) as nomproben,ben.sc_cuenta as sc_cuenta";
			}
		}
		else
		{
			if($_SESSION["ls_gestor"]=="MYSQL")
			{
				$ls_nomben=" CONCAT(ben.nombene,'  ',ben.apebene)";
			}
			else
			{
				$ls_nomben="(ben.nombene||'  '||ben.apebene)";
			}
			
			$ls_aux_nomben=" (CASE cxp.cod_pro WHEN '----------' THEN ".$ls_nomben." ELSE prov.nompro END) as nomproben, (CASE cxp.cod_pro WHEN '----------' THEN ben.sc_cuenta ELSE prov.sc_cuenta END) as sc_cuenta ";
		}

		$ls_sql=" SELECT cxp.*,doc.dentipdoc,doc.estcon,doc.estpre,".$ls_aux_nomben." ,scg.denominacion as den_scg
				  FROM   cxp_sol_dc cxp,cxp_documento doc,rpc_proveedor prov,rpc_beneficiario ben,scg_cuentas scg
				  WHERE  cxp.codemp='".$ls_codemp."' AND cxp.numdc like '".$ls_numncnd."' AND cxp.desope like '".$ls_dennota."' 
				  AND    cxp.fecope BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".$ls_aux."  
				  AND    cxp.codemp=prov.codemp AND  cxp.codemp=ben.codemp AND cxp.codtipdoc=doc.codtipdoc AND cxp.cod_pro=prov.cod_pro AND cxp.ced_bene=ben.ced_bene  AND cxp.codemp=scg.codemp 
				  AND    (CASE cxp.cod_pro WHEN '----------' THEN ben.sc_cuenta ELSE prov.sc_cuenta END) =scg.sc_cuenta
				  ORDER  BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error en catalogo de Notas de Debito o Credito","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Numero de Nota'      align='center' onClick=ue_orden('cxp.numdc')>Numero de Nota</td>";
			print "<td style='cursor:pointer' title='Ordenar por Numero de Orden'     align='center' onClick=ue_orden('cxp.numsol')>Numero de Orden de Pago</td>";
			print "<td style='cursor:pointer' title='Ordenar por Numero de Ficha' align='center' onClick=ue_orden('cxp.numrecdoc')>Numero de Ficha</td>";
			print "<td style='cursor:pointer' title='Ordenar por Fecha'               align='center' onClick=ue_orden('cxp.fecope')>Fecha</td>";
			print "<td style='cursor:pointer' title='Ordenar por Proveedor'           align='center' onClick=ue_orden('cxp.cod_pro')>Proveedor</td>";
			print "<td style='cursor:pointer' title='Ordenar por Beneficiario'        align='center' onClick=ue_orden('cxp.ced_bene')>Beneficario</td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre Proveedor/Beneficiario'        align='center' onClick=ue_orden('nomproben')>Nombre Proveedor / Beneficiario</td>";
			print "<td style='cursor:pointer' title='Tipo de Nota'                    align='center' onClick=ue_orden('cxp.codope')>Tipo de Nota</td>";
			print "<td style='cursor:pointer' title='Ordenar por Monto'               align='center' onClick=ue_orden('cxp.monto')>Monto</td>";
			print "<td style='cursor:pointer' title='Ordenar por Descripcion'         align='center' onClick=ue_orden('cxp.desope')>Descripcion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li++;
				$ls_numncnd=trim($row["numdc"]);
				$ls_numord=$row["numsol"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ld_fecha =$io_funciones->uf_convertirfecmostrar($row["fecope"]);
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				$ls_desope=utf8_encode($row["desope"]);
				$ls_nomproben=utf8_encode($row["nomproben"]);
				$ls_cuentaprov=$row["sc_cuenta"];
				$ls_dencuentaprov=$row["den_scg"];
				$ls_codope=$row["codope"];
				if($ls_codope=='NC')
				{
					$ls_operacion="Nota de Credito"	;
				}
				else
				{
					$ls_operacion="Nota de Debito"	;				
				}
				if($ls_codpro=='----------')
				{
					$ls_tipproben='B';
				}
				else
				{
					$ls_tipproben='P';
				}
				$ldec_monto=$row["monto"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_dentipdoc=utf8_encode($row["dentipdoc"]);
				$li_estcon   =$row["estcon"];
				$li_estpre   =$row["estpre"];
				$li_estapro  =$row["estapr"];
				$ls_estnota  =$row["estnotadc"];
				//print "sss->".$ls_tipo;
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codemp','$ls_numncnd','$ld_fecha','$ls_numord','$ls_numrecdoc','$ls_codtipdoc','$ls_dentipdoc','$ls_tipproben','$ls_codpro','$ls_cedbene','$ls_nomproben','$li_estcon','$li_estpre','$ls_codope','$ls_cuentaprov','$ls_dencuentaprov','$ls_desope','$ls_estnota','$li_estapro');\">".$ls_numncnd."</a></td>";
						print "<td align='center'>".$ls_numord."</td>";
						print "<td align='center'>".$ls_numrecdoc."</td>";
						print "<td align='center'>".$ld_fecha."</td>";
						print "<td align='center'>".$ls_codpro."</td>";
						print "<td align='center'>".$ls_cedbene."</td>";
						print "<td align='center'>".$ls_nomproben."</td>";
						print "<td align='center'>".$ls_operacion."</td>";
						print "<td align='right'>".number_format($ldec_monto,2,",",".")."</td>";	
						print "<td align='center'>".$ls_desope."</td>";	
						print "</tr>";
						break;
						
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrepdes('$ls_numncnd');\">".$ls_numncnd."</a></td>";
						print "<td align='center'>".$ls_numord."</td>";
						print "<td align='center'>".$ls_numrecdoc."</td>";
						print "<td align='center'>".$ld_fecha."</td>";
						print "<td align='center'>".$ls_codpro."</td>";
						print "<td align='center'>".$ls_cedbene."</td>";
						print "<td align='center'>".$ls_nomproben."</td>";
						print "<td align='center'>".$ls_operacion."</td>";
						print "<td align='right'>".number_format($ldec_monto,2,",",".")."</td>";	
						print "<td align='center'>".$ls_desope."</td>";	
						print "</tr>";
						break;
						
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrephas('$ls_numncnd');\">".$ls_numncnd."</a></td>";
						print "<td align='center'>".$ls_numord."</td>";
						print "<td align='center'>".$ls_numrecdoc."</td>";
						print "<td align='center'>".$ld_fecha."</td>";
						print "<td align='center'>".$ls_codpro."</td>";
						print "<td align='center'>".$ls_cedbene."</td>";
						print "<td align='center'>".$ls_nomproben."</td>";
						print "<td align='center'>".$ls_operacion."</td>";
						print "<td align='right'>".number_format($ldec_monto,2,",",".")."</td>";	
						print "<td align='center'>".$ls_desope."</td>";	
						print "</tr>";
						break;
				}
			}
			if($li==0)
			{
				$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();"); 				
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
	}// end function uf_print_notas
	
	function uf_print_dtcargos()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_dtcargos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcin que obtiene e imprime los resultados de la busqueda del detalle de los cargos de la recepcion
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  02/06/2007 								Fecha ltima Modificacin : 03/06/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();		
		$ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_modalidad=$_SESSION['la_empresa']['estmodest'];
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc",""); 
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc",""); 
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben",""); 
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota",""); 
		$ldec_montodoc=$io_funciones_cxp->uf_obtenervalor("montodoc","0,00");
		$li=0;
		$ls_aux="";
		$ls_codpro="";
		$ls_cedbene="";
		if($ls_tipproben=="P")
		{
			$ls_destino="Proveedor";
			$ls_cedbene="";
			$ls_codpro=$ls_codproben;
			$ls_aux=" AND rd.cod_pro='".$ls_codproben."' ";			
		}
		elseif($ls_tipproben=="B")
		{
			$ls_destino="Beneficiario";
			$ls_codpro="";
			$ls_cedbene=$ls_codproben;
			$ls_aux=" AND rd.ced_bene='".$ls_codproben."' ";			
		}
		$ls_aux_estpro=" rd.codestpro1,rd.codestpro2,rd.codestpro3,rd.codestpro4,rd.codestpro5";			
		$ls_confiva=$_SESSION["la_empresa"]["confiva"];
		if($ls_confiva=="C")
		{
			$ls_sql="SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom,".
					"       rd.spg_cuenta, '--------------------' as codestpro1, '------' as codestpro2, '---' as codestpro3,".
					"       '--' as codestpro4,'--' as codestpro5,".
					"       scg.denominacion as denscg,rd.formula ,rd.monret,CAR.dencar ".
					"  FROM cxp_rd_cargos rd,scg_cuentas scg ,tepuy_cargos CAR ".
					" WHERE rd.codemp='".$ls_codemp."'".
					"   AND rd.numrecdoc='".$ls_numrecdoc."'". 
					"   AND rd.codtipdoc='".$ls_codtipdoc."'".
					$ls_aux.
					"   AND rd.codemp=scg.codemp".
					"   AND rd.spg_cuenta=scg.sc_cuenta".
					"   AND rd.codemp=scg.codemp".
					"   AND rd.codcar=CAR.codcar".
					"   AND rd.codemp=CAR.codemp";
   		}
		else
		{
			$ls_sql=" SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom, ".$ls_aux_estpro.",
							 rd.spg_cuenta, spg.denominacion ,spg.sc_cuenta,scg.denominacion as denscg,rd.formula ,rd.monret,CAR.dencar".
					" FROM cxp_rd_cargos rd,spg_cuentas spg,scg_cuentas scg ,tepuy_cargos CAR".
					" WHERE  rd.codemp='".$ls_codemp."' ".
					" AND    rd.numrecdoc='".$ls_numrecdoc."' AND rd.codtipdoc='".$ls_codtipdoc."' ".$ls_aux. 
					" AND rd.codemp=spg.codemp AND rd.spg_cuenta=spg.spg_cuenta AND rd.codemp=scg.codemp ".
					" AND spg.sc_cuenta=scg.sc_cuenta AND rd.codestpro1=spg.codestpro1 AND rd.codestpro2=spg.codestpro2 ".
					" AND rd.codestpro3=spg.codestpro3 AND rd.codestpro4=spg.codestpro4 AND rd.codestpro5=spg.codestpro5
					  AND rd.codcar=CAR.codcar AND rd.codemp=CAR.codemp" ;
		}
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar detalles presupuestarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)."  SQL: ".$ls_sql,true,"javascript: ue_close();"); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
						$ls_codestpro1=$row["codestpro1"];
						$ls_codestpro2=$row["codestpro2"];
						$ls_codestpro3=$row["codestpro3"];
						$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
						break;						
					case "2": // Modalidad por Programa
						$ls_codestpro1=substr($row["codestpro1"],-2);
						$ls_codestpro2=substr($row["codestpro2"],-2);
						$ls_codestpro3=substr($row["codestpro3"],-2);
						$ls_codestpro4=$row["codestpro4"];
						$ls_codestpro5=$row["codestpro5"];
						$ls_codestpro=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
						break;
				}
				$ls_spgcuenta=$row["spg_cuenta"];
				$ldec_baseimp=$ldec_montodoc;
				$ldec_montodoc=str_replace(".","",$ldec_montodoc);
				$ldec_montodoc=str_replace(",",".",$ldec_montodoc);
				if($ldec_montodoc>0)
				{
					$li++;		
					$ldec_monto="0,00";
					$ls_dencuenta=utf8_encode($row["dencar"]);
					if($ls_confiva=="C")
					{
						$ls_scgcuenta=$row["spg_cuenta"];
					}
					else
					{
						$ls_scgcuenta=$row["sc_cuenta"];
					}
					$ls_denscg=utf8_encode($row["denscg"]);
					$ls_formula=$row["formula"];
					$lo_object[$li][1]="<input type=checkbox name=chk".$li."      id=chk".$li." class=sin-borde onClick='javascript:ue_calcular($li);'>";
					$lo_object[$li][2]="<input type=text name=txtcodestpro".$li." class=sin-borde style=text-align:center size=37 value='".$ls_codestpro."' readonly><input type=hidden name=txtformula".$li."  value='".$ls_formula."'>";
					$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li." class=sin-borde style=text-align:center size=16 value='".$ls_spgcuenta."' readonly><input type=hidden name=txtscgcuenta".$li."  value='".$ls_scgcuenta."'><input type=hidden name=txtdenscgcuenta".$li."  value='".$ls_denscg."'>"; 
					$lo_object[$li][4]="<input type=text name=txtbaseimp".$li."   class=sin-borde style=text-align:right  size=20 value='".$ldec_baseimp."' onBlur='javascript:uf_format(this,true,$li);uf_valida_monto($li);'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
					$lo_object[$li][5]="<input type=text name=txtmonto".$li."     class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_monto,2,",",".")."' readonly>";
					$lo_object[$li][6]="<input type=text name=txtdencuenta".$li." class=sin-borde style=text-align:left   size=50 value='".$ls_dencuenta."' readonly>";
				}
			}
			if($li==0)
			{
				$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();"); 				
				$lo_object=array();
			}			
			// Titulos del Grid de Bienes
			$lo_title[1]=" ";
			$lo_title[2]="Codigo Programatico";
			$lo_title[3]="Codigo Estadistico";
			$lo_title[4]="Base Imponible";
			$lo_title[5]="Monto";
			$lo_title[6]="Denominaci&oacute;n";
			print "<input name=totalrows type=hidden id=totalrows value=$li>";
			$io_grid->makegrid($li,$lo_title,$lo_object,758,"Catalogo de Cargos","grid");
			$io_sql->free_result($rs_data);	
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_dtcargos
	//--------------------------------------

	function uf_calcular_cargo()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_cargo
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funcin que obtiene e imprime los resultados de la busqueda del detalle de los cargos de la recepcion y calcula en base a los nuevos montos
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin:  02/06/2007 								Fecha ltima Modificacin : 03/06/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();		
        require_once("../../shared/class_folder/evaluate_formula.php");
		$io_formula       = new evaluate_formula();
		$li_total=$io_funciones_cxp->uf_obtenervalor("total",0);
		for($li=1;$li<=$li_total;$li++)
		{
			$lb_chk=$io_funciones_cxp->uf_obtenervalor("chk".$li,0); 
			$ls_codestpro=$io_funciones_cxp->uf_obtenervalor("txtcodestpro".$li,""); 
			$ls_formula=$io_funciones_cxp->uf_obtenervalor("txtformula".$li,""); 
			$ls_spgcuenta=$io_funciones_cxp->uf_obtenervalor("txtspgcuenta".$li,""); 
			$ls_scgcuenta=$io_funciones_cxp->uf_obtenervalor("txtscgcuenta".$li,""); 
			$ls_denscg=$io_funciones_cxp->uf_obtenervalor("txtdenscgcuenta".$li,""); 
			$ldec_baseimp=$io_funciones_cxp->uf_obtenervalor("txtbaseimp".$li,""); 
			$ls_dencuenta=$io_funciones_cxp->uf_obtenervalor("txtdencuenta".$li,"");
			$ldec_baseaux=str_replace(".","",$ldec_baseimp);
			$ldec_baseaux=str_replace(",",".",$ldec_baseaux); 			
			if($lb_chk==1)
			{				
				if ($ldec_baseaux>0)
				{					
				  $ldec_monto = $io_formula->uf_evaluar_formula($ls_formula,$ldec_baseaux);
				}
				else
				{
				  $ldec_monto = 0;
				}
				$ldec_monto=round($ldec_monto,2);
				$lo_object[$li][1]="<input type=checkbox name=chk".$li."      id=chk".$li." class=sin-borde onClick='javascript:ue_calcular($li);' checked>";
			}
			else
			{
				$lo_object[$li][1]="<input type=checkbox name=chk".$li."      id=chk".$li." class=sin-borde onClick='javascript:ue_calcular($li);' >";
				$ldec_monto = 0;
			}
			$lo_object[$li][2]="<input type=text name=txtcodestpro".$li."    class=sin-borde style=text-align:center size=37 value='".$ls_codestpro."'    readonly><input type=hidden name=txtformula".$li."  value='".$ls_formula."'>";
			$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li."    class=sin-borde style=text-align:center size=16  value='".$ls_spgcuenta."'     readonly><input type=hidden name=txtscgcuenta".$li."  value='".$ls_scgcuenta."'><input type=hidden name=txtdenscgcuenta".$li."  value='".$ls_denscg."'>"; 
			$lo_object[$li][4]="<input type=text name=txtbaseimp".$li."        class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_baseaux,2,",",".")."' onBlur='javascript:uf_format(this,true,$li);'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$lo_object[$li][5]="<input type=text name=txtmonto".$li."        class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_monto,2,",",".")."' readonly>";
			$lo_object[$li][6]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left  size=50 value='".$ls_dencuenta."' readonly>";
		}
		if($li==0)
		{
			$io_mensajes->uf_mensajes_ajax("Informacion","No hay datos para mostrar",true,"javascript: ue_close();"); 				
		}
			// Titulos del Grid de Bienes
		$lo_title[1]=" ";
		$lo_title[2]="Codigo Programatico";
		$lo_title[3]="Codigo Estadistico";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto";
		$lo_title[6]="Denominaci&oacute;n";
		print "<input name=totalrows type=hidden id=totalrows value=".($li-1).">";
		print "<input name=selected type=hidden id=selected value=0>";
		$io_grid->makegrid(($li-1),$lo_title,$lo_object,758,"Catalogo de Cargos","grid");
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencioniva()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesiva
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 12/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
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

		$ls_tipproben=$_POST['tipproben'];
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		$ls_numsol=$_POST['numsol'];
		$ls_codprobendes=$_POST['codprobendes'];
		$ls_codprobenhas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret >= '".$ls_codprobendes."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.codsujret <= '".$ls_codprobendes."'";
		}
	/*	if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene >= '".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.ced_bene <= '".$ls_cedbenhas."'";
		}*/
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '".$ls_tipo."' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
			//	"   AND scb_dt_cmp_ret.numsop = cxp_solicitudes.numsol ".
				$ls_criterio.
				" ORDER BY scb_cmp_ret.numcom ";
		//print_r($ls_sql);
		$rs_data=$io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style=text-align:center width=100>Codigo</td>";
			print "<td style=text-align:center width=50>Fecha</td>";
			print "<td style=text-align:center width=450>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numcom=$row["numcom"];
				$ls_perfiscal=$row["perfiscal"];
				$ls_anofiscal=substr($ls_perfiscal,0,4);
				$ls_mesfiscal=substr($ls_perfiscal,4,6);
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_dirsujret=$row["dirsujret"];
				$ls_rifsujret=$row["rif"];
				$ls_codret=$row["codret"];
				$ld_fecrep=$io_funciones->uf_convertirfecmostrar($row["fecrep"]);
				$ls_estcmpret=$row["estcmpret"];
				print "<tr class=celdas-blancas>";
				print "<td style=text-align:center width=100><a href=\"javascript:ue_aceptar('$ls_numcom','$ls_anofiscal','$ls_mesfiscal','$ls_codsujret','$ls_nomsujret','$ls_dirsujret','$ls_rifsujret','$ls_codret','$ls_estcmpret');\">".$ls_numcom."</a></td>";
				print "<td style=text-align:center width=50>".$ld_fecrep."</td>";
				print "<td style=text-align:left   width=450>".$ls_nomsujret."</td>";
				print "</tr>";
				
			}
			$io_sql->free_result($rs_data);
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesiva
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_chequear_cancelado($as_numsol)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_chequear_cancelado
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que verifica si una solicitud esta cancelada.
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 28/08/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;
		
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
		$lb_pagado=false;
        $ls_sql="SELECT * ".
				"  FROM scb_prog_pago  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND numsol = '".$as_numsol."' ";
				
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Proveedores","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))
			{
				$ls_status=$row["estmov"];
				if($ls_status=='N'||$ls_status=='C')
				{
					$lb_pagado=true;
				}
			}
		}
		return $lb_pagado;
	}
	function uf_obtengo_retencion ($as_sql,$quetraigo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_contabilizado
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          17/04/2015
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado=false;
	require_once("../../shared/class_folder/tepuy_include.php");
	$io_include=new tepuy_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	require_once("../../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();		
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	$rs_data=$io_sql->select($as_sql);
	if($rs_data===false)
	{
		print "Error en uf_obtengogatos".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$io_sql->fetch_row($rs_data))
		{
			if($quetraigo==='C')
			{
				$li_estado=$la_row["codcmp"];
			}
			else
			{
				if($quetraigo==='T')			
				{
				$li_estado=$la_row["tipo"];
				}
				else
				{
				$li_estado=$la_row["nom"];
				}
			}
			
		}		
	}	
	return $li_estado;
}
////////////////////////// FIN DE LA BUSQUEDA DEL NRO DE CODIGO DE LA RETENCION QUE VIENE///////////////////////

?>
