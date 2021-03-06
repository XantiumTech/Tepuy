<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los cat�logos del sistema SFA con la utilizaci�n del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
	session_start();
	//ini_set('display_errors', 1);
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	/*$ls_tipo   = $io_fun_sfa->uf_obtenervalor("tipo","");
	$ls_origen = $io_fun_sfa->uf_obtenervalor("origen","");
	$ls_codpro = $io_fun_sfa->uf_obtenervalor("codpro","");*/
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_fun_sfa->uf_obtenervalor("catalogo","");
	// aqui muestra el iva inicialmente seleccionado //
	//$ls_codivasel=$io_fun_sfa->uf_obtenervalor("codivasel","");
	//print "Catalogo: ".$ls_catalogo;
	//print "Iva: ".$ls_codivasel;
	switch($ls_catalogo)
	{
		case "PERSONAL":
			uf_print_personal();
		break;
		
		case "UNIDADEJECUTORA":
			uf_print_unidad_ejecutora();
		break;
		
		case "FACTURA":
			uf_print_productos();
		break;
		
		case "SERVICIOS":
			uf_print_servicios();
		break;
		
		case "CLIENTE":
			uf_print_cliente($ls_tipo);
		break;
		
		case "COTIZACION_ANALISIS":
			uf_print_cotizacion_analisis();
		break;
		
		case "FUENTE-FINANCIAMIENTO":
			uf_print_fuente_financiamiento();
		break;
		
		case "MODALIDAD-CLAUSULAS":
			uf_print_modalidad_clausulas();
		break;
		
		case "MONEDA":
			uf_print_moneda();
		break;
		
		case "COTIZACION_SOLICITUD":
			uf_print_solicitudes_cotizacion($ls_origen,$ls_codpro);
		break;
		
        	case "COTIZACION_REGISTRO":
			uf_print_cotizaciones($ls_origen,$ls_tipo);
		break;
		
		case "PRESUPUESTARIA-SOLICITUD":
			uf_print_sep($ls_tipo);
		break;
		
		case "ANALISIS":
			uf_print_analisis();
		break;
		
		case "FACTURA":
		   uf_print_factura();
		break;
		
		case "SOLICITUD-PRESUPUESTARIA":
			uf_print_solicitud_presupuestaria();
		break;
		
		case "CUENTAS-SPG":
		    uf_print_cuentas_spg();
		break;

		case "CUENTAS-CARGOS":
			uf_print_cuentas_cargos();
		break;

		case "RETENCIONESUNIFICADAS":
			uf_print_retencionesunificadas();
		break;
		case "RETENCIONES":
			uf_print_retenciones();
		break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retenciones()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retenciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que inprime el resultado de la busqueda de las retenciones a aplicar en la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa, $io_grid, $io_ds_deducciones;
		
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
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();
		//require_once("class_funciones_sfa.php");
		//$io_fun_sfa=new class_funciones_sfa();
		$ls_numfactura=$io_fun_sfa->uf_obtenervalor_get("numfactura","");
		$li_subtotal=$io_fun_sfa->uf_obtenervalor_get("subtotal","0,00");
		$li_cargos=$io_fun_sfa->uf_obtenervalor_get("cargos","0,00");
        	$ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_modageret = $_SESSION["la_empresa"]["modageret"];
		$li_fila=0;
		if($li_cargos>0) 
		{
			$ls_aux = " WHERE iva=1 OR (estretmun=1 OR islr=1 OR retaposol=1)";
		}
		else
		{
			$ls_aux = " WHERE iva=0 OR (estretmun=1 OR islr=1 OR retaposol=1)";
		}

		$ls_sql="SELECT codded,dended,formula,porded,monded,sc_cuenta,islr,iva,estretmun,retaposol,otras ".
			"  FROM tepuy_deducciones ".
			$ls_aux."  ".
			" ORDER BY codded ASC ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]=utf8_encode("C�digo");
			$lo_title[3]=utf8_encode("Denominaci�n");
			if($li_cargos>0)
			   {
			     $lo_title[4]="Porcentaje";
			     $lo_title[5]=utf8_encode("F�rmula"); 
			   }
			else
			   {
				 $lo_title[4]=utf8_encode("Monto Objeto Retenci�n"); 
				 $lo_title[5]=utf8_encode("Monto Retenci�n"); 
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
				if(($li_cargos>0)&&($li_iva=="1"))
				{
					$li_monobjret=number_format($li_cargos,2,',','.');
					
				}
				else
				{
					$li_monobjret=number_format($li_subtotal,2,',','.');
				}
				$li_row=$io_ds_deducciones->findValues(array('codded'=>$ls_codded),"codded");
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_row);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_row);
				}
				if($li_cargos>0)
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
			if($li_cargos>0)
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
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,680,"","griddeduccion");
			if($li_cargos>0)
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

	}
     	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_print_personal()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los conceptos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new tepuy_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
        
		$ls_codemp	   = $_SESSION['la_empresa']['codemp'];
		$ls_cedper 	   = $_POST['cedper'];
		$ls_nomper 	   = utf8_encode($_POST['nomper']);
		$ls_apeper 	   = utf8_encode($_POST['apeper']);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por C�dula'   align='center' onClick=ue_orden('cedper')>C&eacute;dula</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre'   align='center' onClick=ue_orden('nomper')>Nombre</td>";
		print "<td style='cursor:pointer' title='Ordenar por Apellido' align='center' onClick=ue_orden('apeper')>Apellido</td>";
		print "</tr>";
		$ls_sql = "SELECT DISTINCT max(CASE sno_nomina.racnom WHEN 1 THEN
                                sno_personalnomina.codcar ELSE sno_cargo.codcar END) AS codcar,
				          (SELECT nomper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,
				          (SELECT apeper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,
				          (SELECT cedper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as cedper
				     FROM sno_personalnomina, sno_nomina, sno_cargo,sno_asignacioncargo,sno_personal
				    WHERE sno_personal.cedper LIKE '%".$ls_cedper."%'
				      AND sno_personal.nomper LIKE '%".$ls_nomper."%'
				      AND sno_personal.apeper LIKE '%".$ls_apeper."%'
				      AND sno_nomina.espnom=0
				      AND sno_personalnomina.codemp = sno_nomina.codemp
				      AND sno_personalnomina.codnom = sno_nomina.codnom
				      AND sno_personalnomina.codper = sno_personal.codper
				      AND sno_personalnomina.codemp = sno_cargo.codemp
				      AND sno_personalnomina.codnom = sno_cargo.codnom
				      AND sno_personalnomina.codcar = sno_cargo.codcar
				      AND sno_personalnomina.codemp = sno_asignacioncargo.codemp
				      AND sno_personalnomina.codnom = sno_asignacioncargo.codnom
				      AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar
				    GROUP BY sno_personalnomina.codper,sno_nomina.racnom,sno_asignacioncargo.denasicar,sno_cargo.descar,codclavia
			 	    ORDER BY ".$ls_campoorden." ".$ls_orden." ";//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedper    = $row["cedper"];
				$ls_nomper    = utf8_encode($row["nomper"]);
				$ls_apeper    = utf8_encode($row["apeper"]);
				$ls_codcarper = trim($row["codcar"]);
				print "<tr class=celdas-blancas>";
				print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_cedper."','".$ls_nomper."','".$ls_apeper."','".$ls_codcarper."');\">".$ls_cedper."</a></td>";
				print "<td align='left'>".$ls_nomper."</td>";
				print "<td align='left'>".$ls_apeper."</td>";
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
	}// end function uf_print_personal
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_unidad_ejecutora()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de la unidad ejecutora (Unidad administrativa)
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia / Ing. Juniors Fraga 
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 05/05/2007
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
		print "<td width=60  style='cursor:pointer' title='Ordenar por C�digo'       align='center' onClick=ue_orden('coduniadm')>C&oacute;digo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominaci�n' align='center' onClick=ue_orden('denuniadm')>Denominaci&oacute;n</td>";
		print "</tr>";
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQL")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SOC','".$ls_logusr."',coduniadm) IN (SELECT CONCAT(codemp,codsis,codusu,substr(codintper,24,33)) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SOC')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SOC'||'".$ls_logusr."'||coduniadm IN (SELECT codemp||codsis||codusu||substr(codintper,24,33)
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SOC')";
		}
		$ls_sql="SELECT coduniadm, denuniadm, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5  ".
				"  FROM spg_unidadadministrativa ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND coduniadm <>'----------' ".
				"   AND coduniadm like '".$ls_coduniadm."' ".
				"   AND denuniadm like '".$ls_denuniadm."'  ".$ls_sql_seguridad." ".
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
				$ls_denuniadm  = $row["denuniadm"];
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
					
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
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
	function uf_print_productos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_productos
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el resultado de la busqueda de los bienes
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia / Ing. Juniors Fraga 
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 23/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include	  = new tepuy_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		

		$ls_codemp	  = $_SESSION['la_empresa']['codemp'];
		//$ls_tipo	  = $_POST['tipo'];
		$ls_codpro	  = "%".$_POST['codpro']."%";
		$ls_denpro	  = "%".$_POST['denpro']."%";
		$ls_codtippro = "%".$_POST['codtippro']."%";
		$ls_campoorden = $_POST['campoorden'];

		$ls_straux 	   = "";
		
		$ls_sql=" SELECT sfa_producto.codpro,sfa_producto.denpro,sfa_producto.preproa,sfa_producto.codunimed, ".
			" TRIM(sfa_producto.spi_cuenta) AS spg_cuenta, sfa_producto.exipro, siv_unidadmedida.denunimed, siv_unidadmedida.unidad ".
			"  FROM sfa_producto, siv_unidadmedida ".
			" WHERE sfa_producto.codemp='".$ls_codemp."' ".
			" AND sfa_producto.codpro like '".$ls_codpro."' ".
			" AND sfa_producto.denpro like '".$ls_denpro."' ".
			" AND sfa_producto.codtippro like '".$ls_codtippro."' ".
			" AND sfa_producto.codunimed = siv_unidadmedida.codunimed ".
			" ORDER BY ".$ls_campoorden." ASC ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('sfa_producto.codpro')>Codigo</td>";
			print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('sfa_producto.denpro')>Denominacion</td>";
			print "<td style='cursor:pointer' title='Ordenar por Unidad'       align='center' onClick=ue_orden('siv_unidadmedida.denunimed')>Unidad</td>";
			print "<td style='cursor:pointer' title='Ordenar por Existencia'   align='center' onClick=ue_orden('sfa_producto.exipro')>Unidad</td>";
			print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('sfa_producto.spg_cuenta')>Cuenta</td>";
			print "<td></td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro	 = trim($row["codpro"]);
				$ls_denpro	 = $row["denpro"];
				$li_costoa	 = number_format($row["preproa"],2,",",".");
				$ls_codunimed	 = $row["codunimed"];
				$ls_denunimed    = strtoupper($row["denunimed"]);
				$li_unidad	 = $row["unidad"];
				//$li_totalcargos	 = $row["totalcargos"];
				$ls_spg_cuenta	 = $row["spg_cuenta"];
				$li_existencia = number_format($row["exipro"],2,",",".");;

				$ls_estilo = "celdas-blancas";
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codpro."</td>";
				print "<td align='left'>".$ls_denpro."</td>";
				print "<td align='left'>".$ls_denunimed."</td>";
				print "<td align='center'>".$li_existencia."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td style='cursor:pointer'>";  
				//print "tipo: ".$ls_tipo;
				
				print "<a href=\"javascript: ue_aceptar_producto_factura('".$ls_codpro."','".$ls_denpro."','".$ls_denunimed."','".$li_unidad."','".$ls_spg_cuenta."','".$li_costoa."','".$li_existencia."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_productos
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
		// Modificado Por: Ing. Miguel Palencia / Ing. Juniors Fraga 
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 23/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new tepuy_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql       = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
        
		$ls_codemp    = $_SESSION['la_empresa']['codemp'];
		$ls_tipo	  = $_POST['tipo'];
		$ls_codser    = "%".$_POST['codser']."%";
		$ls_denser    = "%".$_POST['denser']."%";
		$ls_codunieje = "";
		if ($ls_tipo=='SC')
		   {
		     $ls_codunieje = $_POST['codunieje'];  
		   }
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_codestpro3 = $_POST['codestpro3'];
		$ls_codestpro4 = $_POST['codestpro4'];
		$ls_codestpro5 = $_POST['codestpro5'];
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_straux	   = "";
		
		if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
		   {
		     $ls_straux = ",(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				          "   WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				          "     AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				          "	    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
			   	          "	    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				          "		AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				          "		AND soc_servicios.codemp = spg_cuentas.codemp ".
				          "		AND soc_servicios.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ";
		   }
		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por C&oacute;digo'       align='center' onClick=ue_orden('codser')>C&oacute;digo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('denser')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'              align='center' onClick=ue_orden('preser')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'              align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql="SELECT soc_servicios.codser, 
		                soc_servicios.denser, 
						soc_servicios.preser,  
						TRIM(soc_servicios.spg_cuenta) as spg_cuenta, 
						(SELECT denunimed 
                           FROM siv_unidadmedida 
						  WHERE soc_servicios.codunimed=siv_unidadmedida.codunimed) as denunimed $ls_straux".
				"  FROM soc_servicios ".
				" WHERE soc_servicios.codemp = '".$ls_codemp."' ".
				"   AND soc_servicios.codser like '".$ls_codser."' ".
				"   AND soc_servicios.denser like '".$ls_denser."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);//print $ls_sql;
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codser	     = $row["codser"];
				$ls_denser	     = $row["denser"];
				$li_preser	     = number_format($row["preser"],2,",",".");
				$ls_spg_cuenta   = $row["spg_cuenta"];
				$li_existecuenta = 0;
				$ls_denunimed    = $row["denunimed"];
				if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
		           {				   
				     $li_existecuenta = $row["existecuenta"];
				   }
				if($li_existecuenta==0)
				{
					$ls_estilo = "celdas-blancas";
				}
				else
				{
					$ls_estilo = "celdas-azules";
				}
				if ($ls_tipo!='SC')
				   {
				     print "<tr class=".$ls_estilo.">";
		 	 	     print "<td align='center'>".$ls_codser."</td>";
				     print "<td align='left'>".$ls_denser."</td>";
				     print "<td align='left'>".$li_preser."</td>";
				     print "<td align='center'>".$ls_spg_cuenta."</td>";
					 print "<td style='cursor:pointer'>";
				   }
				
				if($ls_tipo=='SC')
				{
					if ($li_existecuenta==1)
					   {
                         print "<tr class=".$ls_estilo.">";
		 	 	         print "<td align='center'>".$ls_codser."</td>";
				         print "<td align='left'>".$ls_denser."</td>";
				         print "<td align='left'>".$li_preser."</td>";
				         print "<td align='center'>".$ls_spg_cuenta."</td>";
					     print "<td style='cursor:pointer'>";
						 print "<a href=\"javascript: ue_aceptar('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";					   
					   }
				}
				if($ls_tipo=='OC')
				{
					print "<a href=\"javascript: ue_aceptar_servicio_orden_compra('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."','".$ls_denunimed."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				}
				if($ls_tipo=='REPDES')
				{
					print "<a href=\"javascript: ue_aceptar_reportedesde('".$ls_codser."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				}
				if($ls_tipo=='REPHAS')
				{
					print "<a href=\"javascript: ue_aceptar_reportehasta('".$ls_codser."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				}
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
	function uf_print_cliente($ls_tipo)
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de Clientes
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia / Ing. Juniors Fraga 
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
		$ls_cedcli="%".$_POST['cedcli']."%";
		$ls_nomcli="%".$_POST['nomcli']."%";
		$ls_apecli="%".$_POST['apecli']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nompro')>Nombre</td>";
		//print "<td style='cursor:pointer' title='' align='center' >    </td>";
		print "<td></td>";
		print "</tr>";


        	$ls_sql="SELECT cedcli,nomcli,apecli,sc_cuenta,rifcli,dircli,telcli,tipconcli".
				"  FROM sfa_cliente  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cedcli <> '----------' ".
				"   AND cedcli like '".$ls_cedcli."' ".
				"   AND nomcli like '".$ls_nomcli."' ".
				"   AND apecli like '".$ls_apecli."' ". 
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
				$ls_cedcli    = $row["cedcli"];
				$ls_nomcli    = trim($row["nomcli"]).", "trim($row["apecli"]);
				$ls_sccuenta  = $row["sc_cuenta"];
				$ls_rifcli    = $row["rifcli"];
				$ls_dircli    = $row["dircli"];
				$ls_telcli    = $row["telcli"];
				$ls_tipconcli = $row["tipconcli"];
				switch ($ls_tipo)
				{
					case "SC":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codpro."</td>";
						print "<td>".$ls_nompro."</td>";
						print "<td style='cursor:pointer'>";
				        print "<a href=\"javascript: ue_aceptar_proveedor_solicitud_cotizacion('".$ls_codpro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."','".$ls_tipconpro."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";

						print "</tr>";

//////////////
						//	 print "<tr class=".$ls_estilo.">";
						//	 print "<td align='center'>".$ls_codart."</td>";
						//	 print "<td align='left'>".$ls_denart."</td>";
						//	 print "<td align='left'>".$ls_denunimed."</td>";
						//	 print "<td align='center'>".$ls_spg_cuenta."</td>";
						//	 print "<td style='cursor:pointer'>";
						//	 print "<a href=\"javascript: ue_aceptar_bienes_solicitud_cotizacion('".$ls_codart."','".$ls_denart."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
						 //    print "</tr>";		

///////////////
					break;
					case "RC":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codpro."</td>";
						print "<td>".$ls_nompro."</td>";
						print "<td style='cursor:pointer'>";
				        print "<a href=\"javascript: ue_aceptar_proveedor_registro_cotizacion('".$ls_codpro."','".$ls_nompro."','".$ls_tipconpro."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
						print "</tr>";
					break;
					case "":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_cedcli."</td>";
						print "<td>".$ls_nomcli."</td>";
						print "<td style='cursor:pointer'>";
				        print "<a href=\"javascript: ue_aceptar('".$ls_cedcli."','".$ls_nomcli."','".$ls_tipconcli."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar Cliente' width='15' height='15' border='0'></a></td>";
						print "</tr>";
					break;
				  	case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_cedcli','$ls_nomcli');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nomcli."</td>";
						print "</tr>";
					break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_codpro','$ls_nomcli');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nomcli."</td>";
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
	}// end function uf_print_cliente
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cotizacion_analisis()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cotizacion_analisis();
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime el las cotizaciones registradas asociadas a su solicitud para el an�lisis de cotizacion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/04/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;		
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
		$ls_numsolcot="%".$_POST['numsol']."%";
		$ls_numcot="%".$_POST['numcot']."%";
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_fecini=$io_funciones->uf_convertirdatetobd($_POST['fecini']);
		$ls_fecfin=$io_funciones->uf_convertirdatetobd($_POST['fecfin']);
		if ($ls_fecini!="")
			$ls_cadena1=" AND s.feccot>='$ls_fecini'";
		else
			$ls_cadena1="";
		if ($ls_fecfin!="")
			$ls_cadena2=" AND s.feccot<='$ls_fecfin'";
		else
			$ls_cadena2="";	
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT distinct s.numcot, s.numsolcot, p.nompro, s.feccot,s.poriva,s.montotcot,s.cod_pro, c. tipsolcot
					FROM soc_cotizacion s, rpc_proveedor p, soc_sol_cotizacion c
					WHERE s.codemp='$ls_codemp' 
					AND c.estcot='R'
					AND s.numcot like '$ls_numcot'
					AND s.numsolcot like '$ls_numsolcot'
					AND p.cod_pro like '$ls_codpro'
					AND s.codemp=p.codemp
					AND s.cod_pro=p.cod_pro
					AND c.codemp=s.codemp
					AND s.numsolcot=c.numsolcot		
					$ls_cadena1 $ls_cadena2
					ORDER BY ".$ls_campoorden." ".$ls_orden."";		
			
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
				print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td style='cursor:pointer' title='Ordenar por N� de Solicitud'       align='center' onClick=ue_orden('s.numsolcot')>No de Solicitud</td>";
				print "<td style='cursor:pointer' title='Ordenar por N� Cotizaci�n'         align='center' onClick=ue_orden('s.numcot')>No de Cotizacion</td>";
				print "<td style='cursor:pointer' title='Ordenar por Proveedor'             align='center' onClick=ue_orden('p.nompro')>Proveedor</td>";
				print "<td style='cursor:pointer' title='Ordenar por Fecha'                   align='center' onClick=ue_orden('s.feccot')   width=70>Fecha</td>";
				print "<td style='cursor:pointer' title='Ordenar por I.V.A.'                   align='center' onClick=ue_orden('s.poriva')>I.V.A.</td>";
				print "<td style='cursor:pointer' title='Ordenar por Monto Total'          align='center' onClick=ue_orden('s.montotcot') width=100>Monto Total</td>";
				print "<td></td>";
				print "</tr>";
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_tipsolcot=$row["tipsolcot"];
					$ls_numcot=$row["numcot"];
					$ls_numsolcot=$row["numsolcot"];
					$li_nompro=$row["nompro"];
					$ls_codpro=$row["cod_pro"];
					$ls_feccot=$io_funciones->uf_convertirfecmostrar($row["feccot"]);
					/*if($row["poriva"]!=0)
					{
						$ls_poriva="si";
					}
					else
					{
						$ls_poriva="no";
					}*/
					$ls_poriva=number_format($row["poriva"], 2, ',', '.');				
					$li_montotcot=number_format($row["montotcot"], 2, ',', '.');				
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_numsolcot."</td>";
					print "<td align='left'>".$ls_numcot."</td>";
					print "<td align='left'>".$li_nompro."</td>";
					print "<td align='center'>".$ls_feccot."</td>";
					print "<td align='center'>".$ls_poriva."</td>";
					print "<td align='right'>".$li_montotcot."</td>";
					print "<td style='cursor:pointer'>";
					print "<a href=\"javascript: ue_aceptar('".$ls_numsolcot."','".$ls_numcot."','".$li_nompro."','".$ls_codpro."','".$ls_feccot."',".
						  "'".$ls_poriva."','".$li_montotcot."','".$ls_tipsolcot."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_cotizacion_analisis
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuente_financiamiento()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_fuente_financiamiento
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
	function uf_print_modalidad_clausulas()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_modalidad_clausulas
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 19/04/2007 								Fecha �ltima Modificaci�n : 
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
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codtipmod')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denmodcla')>Denominacion</td>";
		print "</tr>";
		$ls_sql=" SELECT codtipmod, denmodcla       ".
				" FROM soc_modalidadclausulas       ".	
				" WHERE codemp='".$ls_codemp."' AND ".
				"       codtipmod <> '--' ".		
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
				$ls_codtipmod=$row["codtipmod"];
				$ls_denmodcla=utf8_encode($row["denmodcla"]);
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codtipmod','$ls_denmodcla');\">".$ls_codtipmod."</a></td>";
						print "<td align='left'>".$ls_denmodcla."</td>";
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
	}// end function uf_print_modalidad_clausulas
	//-----------------------------------------------------------------------------------------------------------------------------------
   	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_moneda()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_moneda
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 19/04/2007 								Fecha �ltima Modificaci�n : 
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
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codmon')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denmon')>Denominacion</td>";
		print "</tr>";
		$ls_sql=" SELECT * ".
				" FROM tepuy_moneda ".	
				" WHERE codmon <> '--' ".		
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
				$ls_codmon=$row["codmon"];
				$ls_denmon=utf8_encode($row["denmon"]);
				$ls_codpai=$row["codpai"];
				$ls_tascam=$row["tascam"];
				$ls_tascam=str_replace(".",",",$ls_tascam);
				$li_estmonpri=$row["estmonpri"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codmon','$ls_denmon','$ls_tascam');\">".$ls_codmon."</a></td>";
						print "<td align='left'>".$ls_denmon."</td>";
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
	}// end function uf_print_moneda
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudes_cotizacion($as_origen,$as_codpro)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes_cotizacion
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene todas las Solicitudes de Cotizaci�nes segun filtros.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 06/05/2007								Fecha �ltima Modificaci�n :06/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new tepuy_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numsolcot  = $_POST['numsolcot'];
		$ls_tipsolcot  = $_POST['tipsolcot'];
		$ls_fecdes     = $_POST['fecdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fechas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_filtro     = "";
		$ls_group      = "";
        $ls_tabla      = "";
		$ls_cadaux     = "";

		if (!empty($ls_numsolcot))
		   { 
		     $ls_filtro = " AND soc_sol_cotizacion.numsolcot ='".$ls_numsolcot."'";
		   }
		if (!empty($ls_tipsolcot) && $ls_tipsolcot!='-')
		   {
		     $ls_filtro = $ls_filtro." AND soc_sol_cotizacion.tipsolcot ='".$ls_tipsolcot."'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_filtro = $ls_filtro." AND soc_sol_cotizacion.fecsol BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		if ($as_origen=='RC')//Registro de Cotizaci�n.
		   {
		     if ($ls_tipsolcot=='B')
			    {
				  $ls_tabla = ', soc_dtsc_bienes';
  				  $ls_campo  = "codart";
				  $ls_straux = 'soc_dtsc_bienes';
				}
			 elseif($ls_tipsolcot=='S')
			    {
				  $ls_tabla  = ', soc_dtsc_servicios';
				  $ls_campo  = "codser";
				  $ls_straux = 'soc_dtsc_servicios';
				}
			 if (!empty($as_codpro))
			    {
				  $ls_filtro = $ls_filtro." AND $ls_straux.cod_pro='".$as_codpro."'";
				}
		     $ls_cadaux = " AND soc_sol_cotizacion.codemp=$ls_straux.codemp AND soc_sol_cotizacion.numsolcot=$ls_straux.numsolcot";
			 $ls_cadaux = $ls_cadaux." AND soc_sol_cotizacion.numsolcot NOT IN (SELECT numsolcot
                                               						              FROM soc_cotizacion
                                                                                 WHERE codemp='".$ls_codemp."'
                                                                                   AND cod_pro='".$as_codpro."'
                                                                                   AND tipcot='".$ls_tipsolcot."')";
		   }
		print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por N�mero'   align='center' onClick=ue_orden('numsolcot')>Nro. Solicitud</td>";
		print "<td width=400 style='cursor:pointer' title='Ordenar por Concepto' align='center' onClick=ue_orden('consolcot')>Concepto</td>";
		print "<td width=80  style='cursor:pointer' title='Ordenar por Fecha'    align='center' onClick=ue_orden('fecsol')>Fecha</td>";
		print "</tr>";
 		$ls_sql = " SELECT soc_sol_cotizacion.numsolcot,
						   max(soc_sol_cotizacion.fecsol) as fecsol,
						   max(soc_sol_cotizacion.obssol) as obssol,
						   max(soc_sol_cotizacion.consolcot) as consolcot,
						   max(soc_sol_cotizacion.uniejeaso) as uniejeaso,
						   max(soc_sol_cotizacion.estcot) as estcot,
						   max(soc_sol_cotizacion.codusu) as codusu,
						   max(soc_sol_cotizacion.cedper) as cedper,
						   max(soc_sol_cotizacion.codcar) as codcar,
						   max(soc_sol_cotizacion.soltel) as soltel,
						   max(soc_sol_cotizacion.solfax) as solfax,
						   max(soc_sol_cotizacion.coduniadm) as coduniadm,
						   max(spg_unidadadministrativa.denuniadm) as denuniadm,
						   max(soc_sol_cotizacion.tipsolcot) as tipsolcot,
						   max(spg_unidadadministrativa.codestpro1) as codestpro1,
						   max(spg_unidadadministrativa.codestpro2) as codestpro2,
						   max(spg_unidadadministrativa.codestpro3) as codestpro3,
						   max(spg_unidadadministrativa.codestpro4) as codestpro4,
						   max(spg_unidadadministrativa.codestpro5) as codestpro5,
						   max(sno_personal.nomper) as nomper,
						   max(sno_personal.apeper) as apeper
					  FROM soc_sol_cotizacion, spg_unidadadministrativa, sno_personal $ls_tabla
					 WHERE soc_sol_cotizacion.codemp='".$ls_codemp."' $ls_filtro $ls_cadaux AND soc_sol_cotizacion.cedper=sno_personal.cedper AND soc_sol_cotizacion.coduniadm=spg_unidadadministrativa.coduniadm
					 GROUP BY soc_sol_cotizacion.numsolcot
					 ORDER BY soc_sol_cotizacion.".$ls_campoorden." ".$ls_orden."";//print $ls_sql;

/* lequite esto
					   AND soc_sol_cotizacion.codemp=spg_unidadadministrativa.codemp
					   AND soc_sol_cotizacion.coduniadm=spg_unidadadministrativa.coduniadm
					   AND soc_sol_cotizacion.codemp=sno_personal.codemp
					   AND soc_sol_cotizacion.cedper=sno_personal.cedper
*/
		//print $ls_sql;
		//				      (SELECT rpc_proveedor.nompro FROM rpc_proveedor WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) AS nombre 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsolcot  = trim($row["numsolcot"]);
				$ls_tipsolcot  = $row["tipsolcot"];
				$ls_fecsolcot  = $io_funciones->uf_convertirfecmostrar($row["fecsol"]);
				$ls_obssolcot  = $row["obssol"];
				$ls_consolcot  = $row["consolcot"];
				$ls_uniejeaso  = $row["uniejeaso"];
				$ls_cedpersol  = $row["cedper"];
				$ls_nompersol  = $row["nomper"];
				$ls_apepersol  = $row["apeper"];				
				if (!empty($ls_apepersol))
				   {
				     $ls_nompersol = $ls_apepersol.", ".$ls_nompersol;
				   }
				$ls_codcarper  = $row["codcar"];
				$ls_soltel     = $row["soltel"];
				$ls_solfax     = $row["solfax"];
				$ls_coduniadm  = $row["coduniadm"];
				$ls_denuniadm  = $row["denuniadm"];
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
                $ls_estsolcot  = "R";
				$ls_strsql     = "SELECT numsolcot FROM soc_cotizacion WHERE codemp='".$ls_codemp."' AND numsolcot='".$ls_numsolcot."'";
				$rs_datos      = $io_sql->select($ls_strsql);
				if ($rs_datos===false)
				   {
				     $lb_valido = false;  
                     $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
				   }
				else
				   {
				     if ($row=$io_sql->fetch_row($rs_datos))
					    {
						  $ls_estsolcot = 'P';
						}
				   }
				print "<tr class=celdas-blancas>";
				switch ($as_origen){
				  case 'SC':
					print "<td align='center'><a href=\"javascript: ue_aceptar_solicitud('$ls_numsolcot','$ls_tipsolcot','$ls_fecsolcot','$ls_obssolcot','$ls_consolcot','$ls_uniejeaso',".
						  "'$ls_cedpersol','$ls_nompersol','$ls_codcarper','$ls_soltel','$ls_solfax','$ls_coduniadm','$ls_denuniadm','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3',".
						  "'$ls_codestpro4','$ls_codestpro5','$ls_estsolcot');\">".$ls_numsolcot."</a></td>";
				    break;
				  case 'RC': 
				 	  print "<td align='center'><a href=\"javascript: ue_aceptar_registro('$ls_numsolcot','$as_codpro','$ls_tipsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				  case "REPDES":
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_numsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				  case "REPHAS":
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_numsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				}
				print "<td align='left'>".$ls_consolcot."</td>";
				print "<td align='center'>".$ls_fecsolcot."</td>";
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
	}// end function uf_print_solicitudes_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cotizaciones($as_origen,$as_tipcot)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cotizaciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene todas las Solicitudes de Cotizaci�nes segun filtros.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 28/05/2007								Fecha �ltima Modificaci�n :28/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new tepuy_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numcot     = $_POST['numcot'];
		$ls_fecdes     = $_POST['fecdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fechas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_filtro     = "";
        $ls_filtro = " AND soc_cotizacion.numcot like '%".$ls_numcot."%'";
		if (!empty($as_tipcot) && $as_tipcot!='-')
		   {
		     $ls_filtro = $ls_filtro." AND soc_cotizacion.tipcot ='".$as_tipcot."'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_filtro = $ls_filtro." AND soc_cotizacion.feccot BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		print "<table width=680 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por N�mero'       align='center' onClick=ue_orden('numcot')>Nro. Cotizaci&oacute;n</td>";
		print "<td width=180 style='cursor:pointer' align='center'>Proveedor</td>";
		print "<td width=260 style='cursor:pointer' title='Ordenar por Observaci�n'  align='center' onClick=ue_orden('obscot')>Observaci&oacute;n</td>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Fecha'        align='center' onClick=ue_orden('feccot')>Fecha</td>";
		print "<td width=60>Estatus</td>";
		print "</tr>";
        $ls_sql = "SELECT soc_cotizacion.numcot,soc_cotizacion.cod_pro,soc_cotizacion.numsolcot,soc_cotizacion.feccot,
						  soc_cotizacion.obscot,soc_cotizacion.monsubtot,soc_cotizacion.monimpcot,soc_cotizacion.mondes,
						  soc_cotizacion.montotcot,soc_cotizacion.diaentcom,soc_cotizacion.estcot,soc_cotizacion.forpagcom,
						  soc_cotizacion.poriva,soc_cotizacion.estinciva,soc_cotizacion.tipcot,rpc_proveedor.nompro
					 FROM soc_cotizacion, rpc_proveedor
					WHERE soc_cotizacion.codemp='".$ls_codemp."' $ls_filtro
					  AND soc_cotizacion.codemp=rpc_proveedor.codemp
					  AND soc_cotizacion.cod_pro=rpc_proveedor.cod_pro
			     ORDER BY soc_cotizacion.".$ls_campoorden." ".$ls_orden."";//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numcot    = $row["numcot"];
				$ls_codpro    = $row["cod_pro"];
				$ls_numsolcot = $row["numsolcot"];
				$ls_fecregcot = $io_funciones->uf_convertirfecmostrar($row["feccot"]);
				$ls_obscot    = utf8_encode($row["obscot"]);
				$ld_monsubcot = number_format($row["monsubtot"],2,',','.');
				$ld_moncrecot = number_format($row["monimpcot"],2,',','.');
				$ld_mondescot = number_format($row["mondes"],2,',','.');
				$ld_montotcot = number_format($row["montotcot"],2,',','.');
				$li_diaent    = $row["diaentcom"];
				$ls_estcot    = $row["estcot"];
				$ls_forpag    = $row["forpagcom"];
				$ld_poriva    = number_format($row["poriva"],2,',','.');
				$li_estinciva = $row["estinciva"];
				$ls_nompro    = $row["nompro"];
				$ls_tipcot    = $row["tipcot"];
				if ($ls_estcot=='0')//R=REGISTRO.
				   {
				     $ls_estcot='REGISTRO';
				   }
				elseif($ls_estcot=='1')//P=PROCESADA.
				   {
				     $ls_estcot='PROCESADA';
				   }

				print "<tr class=celdas-blancas>";
				switch ($as_origen){
				  case 'RC':
				     print "<td align='center' width=120><a href=\"javascript: ue_aceptar('$ls_numcot','$ls_codpro','$ls_numsolcot','$ls_fecregcot','$ls_obscot','$ld_monsubcot','$ld_moncrecot','$ld_montotcot','$li_diaent','$ls_estcot','$ls_forpag','$ld_poriva','$li_estinciva','$ls_nompro','$ls_tipcot');\">".$ls_numcot."</a></td>";
				  break;
				  case 'REPDES':
				     print "<td align='center' width=120><a href=\"javascript: aceptar_reportedesde('$ls_numcot');\">".$ls_numcot."</a></td>";
				  break;
				  case 'REPHAS':
				     print "<td align='center' width=120><a href=\"javascript: aceptar_reportehasta('$ls_numcot');\">".$ls_numcot."</a></td>";
				  break;				  
				}
				print "<td align='left' width=180>".$ls_nompro."</td>";
				print "<td align='left' width=260>".$ls_obscot."</td>";
				print "<td align='center' width=60>".$ls_fecregcot."</td>";
				print "<td align='center' width=60>".$ls_estcot."</td>";
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
	}// end function uf_print_cotizaciones
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_sep($as_tipo)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_sep
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene todas las Solicitudes de Ejecuci�n Presupuestaria segun filtros.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 06/05/2007								Fecha �ltima Modificaci�n :06/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new tepuy_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numsol     = $_POST['numsep'];
		$ls_tipsolcot  = $_POST['hidtipsolcot'];
		if ($ls_tipsolcot=='B')
		   {
		     $ls_tabla = "sep_dt_articulos";
		   }
		elseif($ls_tipsolcot=='S')
		   {
		     $ls_tabla = "sep_dt_servicio";
		   }
		$ls_cadena = "";
		$ls_straux = "";
		if (!empty($ls_tabla))
		   {
		     $ls_cadena = ", $ls_tabla ";
		     $ls_straux = " AND $ls_tabla.estincite = 'NI' AND sep_solicitud.codemp=$ls_tabla.codemp AND sep_solicitud.numsol=$ls_tabla.numsol ";
		   }
		$ls_fecdes     = $_POST['fecregdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fecreghas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipdes     = $_POST['tipdes'];
		if ($ls_tipdes=='P')
		   {
		     $ls_straux = $ls_straux." AND sep_solicitud.tipo_destino = 'P'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_straux = $ls_straux." AND sep_solicitud.fecregsol BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por N�mero'            align='center' onClick=ue_orden('numsol')>Nro. Solicitud</td>";
		print "<td width=300 align='center'>Concepto</td>";
		print "<td width=200 align='center'>Proveedor</td>";
		print "<td width=70 style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('fecregsol')>Fecha</td>";
		print "<td width=80 align='center'>Estatus</td>";
		print "<td width=110  style='cursor:pointer' title='Ordenar por Monto'             align='center' onClick=ue_orden('monto')>Monto</td>";
		print "<td></td>";
		print "</tr>";
 		$ls_sql = "SELECT sep_solicitud.numsol, 
		 				  max(sep_solicitud.consol) as consol, 
						  max(sep_solicitud.fecregsol) as fecregsol, 
						  max(sep_solicitud.estsol) as estsol,
						  max(sep_solicitud.monto) as monto,
						  max(sep_solicitud.coduniadm) as unieje,
						  max(spg_unidadadministrativa.denuniadm) as denuniadm,
						  max(spg_unidadadministrativa.codestpro1) as codestpro1,
						  max(spg_unidadadministrativa.codestpro2) as codestpro2,
						  max(spg_unidadadministrativa.codestpro3) as codestpro3,
						  max(spg_unidadadministrativa.codestpro4) as codestpro4,
						  max(spg_unidadadministrativa.codestpro5) as codestpro5,
		                  max(rpc_proveedor.cod_pro) as cod_pro, 
						  max(rpc_proveedor.nompro) as nompro, 
						  max(rpc_proveedor.dirpro) as dirpro, 
						  max(rpc_proveedor.telpro) as telpro   
					 FROM sep_solicitud, spg_unidadadministrativa ,rpc_proveedor $ls_cadena
					WHERE sep_solicitud.codemp='".$ls_codemp."'
					  AND sep_solicitud.numsol like '%".$ls_numsol."%'
                                    AND sep_solicitud.estsol='C' 
                                    $ls_straux
					  AND sep_solicitud.codemp=rpc_proveedor.codemp
					  AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro
					  AND sep_solicitud.codemp=spg_unidadadministrativa.codemp
					  AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm
					GROUP BY sep_solicitud.numsol
					ORDER BY sep_solicitud.".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol = trim($row["numsol"]);
				$ls_consol = $row["consol"];
				$ls_nompro = $row["nompro"];
				$ls_fecsol = $io_funciones->uf_convertirfecmostrar($row["fecregsol"]);
				$ls_estsol = $row["estsol"];
				switch ($ls_estsol){
				  case 'R':
				    $ls_estsol = "REGISTRO";
				  break;
                             case 'E':
				    $ls_estsol = "EMITIDA";
				  break;				  
                             case 'P':
				    $ls_estsol = "PROCESADA";
				  break;
				  case 'C':
				    $ls_estsol = "CONTABILIZADA";
				  break;
				  case 'A':
				    $ls_estsol = "ANULADA";
				  break;
				}
				$ld_monsol = number_format($row["monto"],2,',','.');
				$ls_unieje = $row["unieje"];
				$ls_denuni = utf8_encode($row["denuniadm"]);
				$ls_codpro = trim($row["cod_pro"]);
				$ls_nompro = $row["nompro"];
				$ls_dirpro = $row["dirpro"];
				$ls_telpro = $row["telpro"]; 

				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
				
				print "<tr class=celdas-blancas>";
				print "<td align='center'>".$ls_numsol."</td>";
				print "<td align='left'>".$ls_consol."</td>";
				print "<td align='left'>".$ls_nompro."</td>";
				print "<td align='center'>".$ls_fecsol."</td>";			
				print "<td align='center'>".$ls_estsol."</td>";			
				print "<td align='right'>".$ld_monsol."</td>";
				print "<td style='cursor:pointer'>";
				switch ($as_tipo){
				  case 'SC':
			        print "<a href=\"javascript: ue_aceptar('".$ls_numsol."','".$ls_consol."','".$ld_monsol."','".$ls_unieje."','".$ls_denuni."','".$ls_codpro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				  case 'REPDES':
				     print "<a href=\"javascript: aceptar_reportedesde('".$ls_numsol."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				  case 'REPHAS':
				     print "<a href=\"javascript: aceptar_reportehasta('".$ls_numsol."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				}  
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
	}// end function uf_print_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_analisis()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_analisis();
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que obtiene e imprime los analisis de cotizacion previamente registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 09/06/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;		
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
		$ls_numsolcot="%".$_POST['numsol']."%";
		$ls_numanacot="%".$_POST['numanacot']."%";
		$ls_fecini=$io_funciones->uf_convertirdatetobd($_POST['fecini']);
		$ls_fecfin=$io_funciones->uf_convertirdatetobd($_POST['fecfin']);
		if ($ls_fecini!="")
			$ls_cadena1=" AND soc_analisicotizacion.fecanacot>='$ls_fecini'";
		else
			$ls_cadena1="";
		if ($ls_fecfin!="")
			$ls_cadena2=" AND soc_analisicotizacion.fecanacot<='$ls_fecfin'";
		else
			$ls_cadena2="";	
		$ls_orden=$_POST['orden'];
		$ls_campoorden="soc_analisicotizacion.".$_POST['campoorden'];
		$ls_sql="SELECT soc_analisicotizacion.numanacot,rpc_proveedor.nompro as nombre,soc_cotxanalisis.cod_pro as cod_pro, 
		                max(soc_analisicotizacion.fecanacot) as fecanacot,
						max(soc_analisicotizacion.obsana) as obsana,
						max(soc_analisicotizacion.numsolcot) as numsolcot,
						max(soc_analisicotizacion.tipsolcot) as tipsolcot,
						max(soc_analisicotizacion.estana) as estana ".
		//	"			(SELECT rpc_proveedor.nompro as nombre,soc_cotxanalisis.cod_pro as cod_pro FROM rpc_proveedor,soc_cotxanalisis WHERE rpc_proveedor.codemp=soc_cotxanalisis.codemp AND rpc_proveedor.cod_pro=soc_cotxanalisis.cod_pro) ".
// AND soc_analisicotizacion.numsolcot=soc_cotizacion.numsolcot AND soc_sol_cotizacion.numsolcot=soc_analisicotizacion.numsolcot) ".
				"	FROM soc_analisicotizacion, soc_cotizacion, soc_cotxanalisis,rpc_proveedor 
					WHERE soc_analisicotizacion.codemp='$ls_codemp' 
					AND soc_analisicotizacion.numanacot like '$ls_numanacot'
					AND soc_analisicotizacion.numsolcot like '$ls_numsolcot'
					AND soc_cotxanalisis.numanacot=soc_analisicotizacion.numanacot
					$ls_cadena1 $ls_cadena2
					AND rpc_proveedor.codemp=soc_cotxanalisis.codemp AND rpc_proveedor.cod_pro=soc_cotxanalisis.cod_pro
					GROUP BY soc_analisicotizacion.numanacot
					ORDER BY ".$ls_campoorden." ".$ls_orden."";
		//print $ls_sql;		
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
				print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td style='cursor:pointer' title='Ordenar por N� de Analisis'       align='center' onClick=ue_orden('numanacot')>No de Analisis</td>";
				print "<td style='cursor:pointer' title='Ordenar por N� Solicitud'         align='center' onClick=ue_orden('numsolcot')>No de Solicitud</td>";
				print "<td style='cursor:pointer' title='Ordenar por Proveedor'          align='center' onClick=ue_orden('nompro')>Proveedor</td>";

				print "<td style='cursor:pointer' title='Ordenar por Observacion'          align='center' onClick=ue_orden('obsana')>Observacion</td>";
				print "<td style='cursor:pointer' title='Ordenar por Fecha'                align='center' onClick=ue_orden('fecanacot')   width=70>Fecha</td>";
				print "<td style='cursor:pointer' title='Ordenar por Tipo'                 align='center' onClick=ue_orden('tipsolcot')>Tipo</td>";
				print "<td style='cursor:pointer' title='Ordenar por Estatus'              align='center' onClick=ue_orden('estana')>Estatus</td>";
				print "</tr>";
				while($row=$io_sql->fetch_row($rs_data))
				{
					if($row["tipsolcot"]=="B")
						$ls_tipsolcot="Bienes";
					else
						$ls_tipsolcot="Servicios";
					$ls_numanacot=$row["numanacot"];
					$ls_numsolcot=$row["numsolcot"];
					$ls_obsana=$row["obsana"];
					$ls_nompro=$row["nombre"];
					//$ejecute="SELECT nompro fron rpc_proveedor WHERE cod_pro='".$ls_codpro."'";
					//$ls_nompro=$this->uf_obtener_proveedor($ejecute);
					$ls_fecanacot=$io_funciones->uf_convertirfecmostrar($row["fecanacot"]);
					if($row["estana"] == 0)
						$ls_estatus="Registro";
					else
						$ls_estatus="Procesada";
					print "<tr class=celdas-blancas>";
					print "<td align='center'><a href=\"javascript:ue_aceptar('$ls_numanacot','$ls_fecanacot','$ls_obsana','$ls_numsolcot','".$row["tipsolcot"]."','$ls_estatus');\">".$ls_numanacot."</a></td>";
					//print "<td align='center'><a href=\"javascript:ue_aceptar('$ls_numanacot','$ls_fecanacot','$ls_nompro','$ls_obsana','$ls_numsolcot','".$row["tipsolcot"]."','$ls_estatus');\">".$ls_numanacot."</a></td>";
					print "<td align='left'>".$ls_numsolcot."</td>";
					print "<td align='left'>".$ls_nompro."</td>";
					print "<td align='left'>".$ls_obsana."</td>";
					print "<td align='center'>".$ls_fecanacot."</td>";
					print "<td align='center'>".$ls_tipsolcot."</td>";
					print "<td align='center'>".$ls_estatus."</td>";
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
	}// end function uf_print_cotizacion_analisis
	//-----------------------------------------------------------------------------------------------------------------------------------

//////// FUNCION QUE ME LLEVA EL CODIGO DE LA RETENCION////////////
function uf_obtengo_proveedor($as_sql)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_obtengo_proveedor
	// Access:			public
	//	Returns:		Boolean, Retorna el nombre del proveedor
	//  Fecha:          17/04/2015
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado=false;
	$rs_data=$this->io_sql->select($as_sql);
	if($rs_data===false)
	{
		print "Error en uf_obtengo_proveedor".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$li_estado=$la_row["nompro"];
		}		
	}	
	return $li_estado;
}
////////////////////////// FIN DE LA BUSQUEDA DEL NOMBRE DEL PROVEEDOR///////////////////////


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_factura()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_factura
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de las Ordenes de Compra
		//     Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 09/05/2007 								Fecha �ltima Modificaci�n : 
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
		$ls_numfactura="%".$_POST["numfactura"]."%";
		$ls_cedcli="%".$_POST["cedcli"]."%";
		//$ls_tipordcom=$_POST["tipordcom"];
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		//$ls_tipo=$_POST['tipo'];
		print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Nro. Factura' align='center' onClick=ue_orden('sfa_factura.numfactura')>Nro. de Orden</td>";
		print "<td width=150 style='cursor:pointer' title='Ordenar por Cliente' align='center' onClick=ue_orden('nomcli')>Cliente</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sfs_factura.estfac')>Estatus</td>";
		print "<td width=70 style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sfa_factura.fecfactura')>Fecha de Registro</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('sfa_factura.montot')>Monto</td>";
		print "</tr>";

		$ls_sql="SELECT sfa_factura.codemp, sfa_factura.numordcom, sfa_factura.estcondat, sfa_factura.fecordcom, ".
				"       sfa_factura.estsegcom, sfa_factura.porsegcom, sfa_factura.monsegcom, sfa_factura.forpagcom, ".
				"       sfa_factura.estcom, sfa_factura.diaplacom, sfa_factura.concom, sfa_factura.obscom, sfa_factura.monsubtot, ".
				"       sfa_factura.monbasimp, sfa_factura.monimp, sfa_factura.montot, sfa_factura.usuario,". 
				"      (SELECT sfa_cliente.nomcli,apecli FROM sfa_cliente ".
				"        WHERE sfa_cliente.codemp=sfa_factura.codemp AND sfa_cliente.cedcli=sfa_factura.cedcli) ".
				"      (SELECT sfa_cliente.trabajador FROM sfa_cliente ".
				"        WHERE sfa_cliente.codemp=sfa_factura.codemp AND sfa_cliente.cedcli=sfa_factura.cedcli) AS tirabajador ".
				"  FROM sfa_factura ".
				" WHERE sfa_factura.codemp = '".$ls_codemp."'".
				"   AND sfa_factura.numfactura like '".$ls_numordcom."'".
				"   AND ".$ls_cadena." sfa_factura.cedcli like '".$ls_codpro."' ".
				"   AND sfa_factura.fecfactura BETWEEN '".$ld_fecregdes."' AND '".$ld_fecreghas."' ".
				"   AND sfa_factura.numfactura>'000000000000000' ".
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
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				
				$ld_fecfactura    = $io_funciones->uf_convertirfecmostrar($row["fecfactura"]);
				$ls_estfac=$row["estfac"];
				$ls_trabajador=$row["trabajador"];
				$ls_forpagcom=$row["forpagcom"];
				$ls_obsfac=$row["obsfac"];

				$ld_monsubtot=number_format($row["monsubtot"],2,",",".");
				$ld_monbasimp=number_format($row["monbasimp"],2,",",".");
				$ld_monimp=number_format($row["monimp"],2,",",".");
				$ld_montot=number_format($row["montot"],2,",",".");
				$ls_cedcli=$row["cedcli"];
				$ls_nomcli=utf8_encode($row["nomcli"])." ".utf8_encode($row["apecli"]);
				$ls_usuario=$row["usuario"];
				$ld_prentdesde= $io_funciones->uf_convertirfecmostrar($row["fechentdesde"]);
				$ld_prenthasta= $io_funciones->uf_convertirfecmostrar($row["fechenthasta"]);
				
				switch ($ls_estfac)
				{
					case "0": // Deberian ir en letras(R) como estan en la sep y en cxp 
						$ls_estatus="REGISTRO";
					break;
						
					case "1":
						$ls_estatus="EMITIDA";
						break;
						
					case "2":
						$ls_estatus="PROCESADA";
						break;
						
					case "3":
						$ls_estatus="ANULADA";
						break;
				}
				print "<tr class=celdas-blancas>";
			    switch ($ls_tipo)
				{
					case "":
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numfactura','$ls_estfac','$ld_fecfac','$ls_forpagcom','$ls_obsfac','$ld_monsubtot','$ld_monbasimp','$ld_monimp','$ld_montot','$ls_usuario','$ls_trabajador');\">".$ls_numfactura."</a></td>";
					break;
					
					case "REPORTE-DESDE":
						print "<td align='center'><a href=\"javascript: ue_aceptar_reporte_desde('$ls_numordcom');\">".$ls_numordcom."</a></td>";
					break;
					
					case "REPORTE-HASTA":
						print "<td align='center'><a href=\"javascript: ue_aceptar_reporte_hasta('$ls_numordcom');\">".$ls_numordcom."</a></td>";
					break;
			   }
			   print "<td style=text-align:left>".$ls_nompro."</td>";
			   print "<td align='center'>".$ls_tipo_orden."</td>";
	 		   print "<td align='center'>".$ls_estatus."</td>";
			   print "<td align='left'>".$ld_fecordcom."</td>";
			   print "<td align='right'>".$ld_montot."</td>";
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
	}// end function uf_print_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------

   	function utf8_to_latin9($utf8str) { // replaces utf8_decode()
    			$trans = array("?"=>"?", "?"=>"?", "?"=>"?", "?"=>"?", "?"=>"?", "?"=>"?", "?"=>"?", "?"=>"?");
   			    $wrong_utf8str = strtr($utf8str, $trans);
   			    $latin9str = utf8_decode($wrong_utf8str);
    		    return $latin9str;
			}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitud_presupuestaria()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitud_presupuestaria
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de la Solicitud de ejecuci�n presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 17/03/2007 								Fecha �ltima Modificaci�n : 28/04/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		header("Content-type: text/html; charset=iso-8859-1"); 
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
		$ls_tipord=$_POST["tipord"];
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		if($ls_tipord=='B')
		{
		  $ls_tabla="sep_dt_articulos";
		}
		elseif($ls_tipord=='S')
		{
		  $ls_tabla="sep_dt_servicio";
		}
		print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Numero de Solicitud' align='center' onClick=ue_orden('sep_solicitud.numsol')>Numero de Solicitud</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Unidad Ejecutora' align='center' onClick=ue_orden('spg_unidadadministrativa.denuniadm')>Unidad Ejecutora</td>";
		print "<td width=70  style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sep_solicitud.fecregsol')>Fecha de Registro</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sep_solicitud.estsol')>Estatus</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql=" SELECT DISTINCT sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, sep_solicitud.codfuefin,".
                "        sep_solicitud.fecregsol, sep_solicitud.estsol, sep_solicitud.consol, sep_solicitud.monto, ".
                "        sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, ".
                "        sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm, tepuy_fuentefinanciamiento.denfuefin,   ".
                "        sep_solicitud.estapro, sep_tiposolicitud.estope, sep_tiposolicitud.modsep, ".
                "        spg_unidadadministrativa.codestpro1, spg_unidadadministrativa.codestpro2,  ".
                "        spg_unidadadministrativa.codestpro3, spg_unidadadministrativa.codestpro4,  ".
                "        spg_unidadadministrativa.codestpro5, ".$ls_tabla.".estincite,              ".
                "        (SELECT rpc_proveedor.nompro ".   
                "         FROM   rpc_proveedor  ".
                "         WHERE  rpc_proveedor.codemp=sep_solicitud.codemp AND ".
                "                rpc_proveedor.cod_pro=sep_solicitud.cod_pro)  AS nompro ".
                " FROM    sep_solicitud, spg_unidadadministrativa, tepuy_fuentefinanciamiento, sep_tiposolicitud, ".
				"         ".$ls_tabla." ".
				" WHERE   sep_solicitud.codemp='".$ls_codemp."' AND sep_solicitud.numsol like '".$ls_numsol."' AND ".
                "         sep_solicitud.coduniadm like '".$ls_coduniadm."' AND  ".
                "         (sep_solicitud.estsol='C' OR sep_solicitud.estsol='P') AND ".
      			"		  sep_solicitud.fecregsol between '".$ld_fecregdes."' AND '".$ld_fecreghas."' AND ".
      			"         sep_solicitud.codemp=spg_unidadadministrativa.codemp AND ".
      			"         sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm AND ".
      			"		  sep_solicitud.codemp=tepuy_fuentefinanciamiento.codemp AND  ".
      			"         sep_solicitud.codfuefin=tepuy_fuentefinanciamiento.codfuefin AND  ".
      			"         sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol  AND ".
				"         ".$ls_tabla.".numsol=sep_solicitud.numsol  AND  ".
                "         ".$ls_tabla.".estincite='NI' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";  
			//	print $ls_sql;
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
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=utf8_encode($row["nompro"]);
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
						$ls_estatus="DESPACHADA COMPLETA";
					break;
					
					case "L":
						$ls_estatus="DESPACHADA PARCIAL";
					break;

				}
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='left'>".$ls_numsol."</td>";
						print "<td align='left'>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td align='left'>".$ls_nompro."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codtipsol','$ls_coduniadm','$ls_codfuefin',".
											"'$ls_estsol','$ls_consol','$ls_tipo_destino','$ls_codpro','$ls_denuniadm',".
											"'$ls_denfuefin','$ls_nompro','$ls_estapro','$ld_fecregsol','$li_monto','$li_monbasinm',".
											"'$li_montotcar','$ls_estatus','$ls_estope','$ls_modsep','$ls_codestpro1','$ls_codestpro2',".
											"'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_solicitud_presupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_spg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_spg
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 06/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_spgcuenta=$_POST['spgcuenta'];
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1ue=$_POST['codestpro1'];
		$ls_codestpro2ue=$_POST['codestpro2'];
		$ls_codestpro3ue=$_POST['codestpro3'];
		$ls_codestpro4ue=$_POST['codestpro4'];
		$ls_codestpro5ue=$_POST['codestpro5'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
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
		if($ls_tipo=="B") // si es de bienes
		{
		   $ls_campo_buscar="soc_gastos";
		}
		elseif($ls_tipo=="S") // si es de Servicios
		{
			$ls_campo_buscar="soc_servic";
		}
		$ls_sql=" SELECT ".$ls_campo_buscar." AS cuenta ".
				" FROM   tepuy_empresa ".
				" WHERE  codemp = '".$ls_codemp."' ";

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
				$ls_codestpro_cta=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_disponible=number_format($row["disponible"],2,",",".");
				if($ls_codestpro_cta==$ls_codestpro1ue.$ls_codestpro2ue.$ls_codestpro3ue.$ls_codestpro4ue.$ls_codestpro5ue)
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
				print "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_codestpro_cta."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_cuentas_spg
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
		// Modificado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 20/03/2007								Fecha �ltima Modificaci�n : 06/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
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
				 $ls_spgcta     = trim($row["spg_cuenta"]);
				 $ls_denctaspg  = utf8_encode($row["denominacion"]);
				 $ls_codestpre1 = $row["codestpro1"];
				 $ls_codestpre2 = $row["codestpro2"];
				 $ls_codestpre3 = $row["codestpro3"];
				 $ls_codestpre4 = $row["codestpro4"];
				 $ls_codestpre5 = $row["codestpro5"];
				 $ls_codestpre  = $ls_codestpre1.$ls_codestpre2.$ls_codestpre3.$ls_codestpre4.$ls_codestpre5;
				 $ld_mondiscta  = number_format($row["disponible"],2,",",".");
				 if ($ls_codestpro1==$ls_codestpre1 && $ls_codestpro2==$ls_codestpre2 && $ls_codestpro3==$ls_codestpre3 &&
				     $ls_codestpro4==$ls_codestpre4 && $ls_codestpro5==$ls_codestpre5)
				    {
					  $ls_estilo = "celdas-azules";
			 	    }
				else
				    {
					  $ls_estilo = "celdas-blancas";
				    }
				if (!empty($ls_codestpre))
				   {
				     $ls_codestpro1 = substr($ls_codestpre1,-$li_len1);
					 $ls_codestpro2 = substr($ls_codestpre2,-$li_len2);
					 $ls_codestpro3 = substr($ls_codestpre3,-$li_len3);					 
					 $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
					 if ($_SESSION["la_empresa"]["estmodest"]==2)
					    {
				          $ls_codestpro4 = substr($ls_codestpre4,-$li_len4);
					      $ls_codestpro5 = substr($ls_codestpre5,-$li_len5);
						  $ls_codestpro = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						}					 	
				   }
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codestpro."</td>";
				print "<td align='center'>".$ls_spgcta."</td>";
				print "<td align='left'>".$ls_denctaspg."</td>";
				print "<td align='right'>".$ld_mondiscta."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codestpro."','".$ls_spgcta."','".$ls_denctaspg."','".$ls_codestpre."');\"><img src='../shared/imagebank/tools20/aprobado.png' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	function uf_print_cargos()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cargos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 12/05/2007 								Fecha �ltima Modificaci�n : 
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
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codcar')>Codigo</td>";
		print "<td width=200 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('dencar')>Denominacion</td>";
		print "<td width=140 style='cursor:pointer' title='Ordenar por Codigo Programatico' align='center' onClick=ue_orden('codestpro')>Programatica</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta' align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "</tr>";
		$ls_sql=" SELECT codcar,dencar,codestpro,spg_cuenta,porcar
				    FROM tepuy_cargos
				   ORDER BY $ls_campoorden $ls_orden";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
		  $ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		  switch($ls_modalidad)
		  {
		    case "1": // Modalidad por Proyecto
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
		  while($row=$io_sql->fetch_row($rs_data))
			   {
				 $ls_codcar     = $row["codcar"];
				 $ls_dencar     = utf8_encode($row["dencar"]);
				 $ls_codestpro  = $row["codestpro"];
				 $ls_codestpro1 = substr($ls_codestpro,0,20);
				 $ls_codestpro2 = substr($ls_codestpro,20,6);
				 $ls_codestpro3 = substr($ls_codestpro,26,3);
				 $ls_codestpro4 = substr($ls_codestpro,29,2);
				 $ls_codestpro5 = substr($ls_codestpro,31,2);
				 $ls_codestpro1 = substr($ls_codestpro1,-$li_len1);
				 $ls_codestpro2 = substr($ls_codestpro2,-$li_len2);
				 $ls_codestpro3 = substr($ls_codestpro3,-$li_len3);
				 $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				 if ($ls_modalidad==2)
				    {
					  $ls_codestpro4 = substr($ls_codestpro4,-$li_len4);
					  $ls_codestpro5 = substr($ls_codestpro5,-$li_len5);
					  $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
					}
				 $ls_spgcta = trim($row["spg_cuenta"]);
				 $li_porcar = $row["porcar"];
				 switch ($ls_tipo)
				 {
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codcar','$ls_dencar');\">".$ls_codcar."</a></td>";
						print "<td align='left' title='".$ls_dencar."'>".$ls_dencar."</td>";
						print "<td align='left'>".$ls_codestpro."</td>";
						print "<td align='left'>".$ls_spgcta."</td>";
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
	}// end function uf_print_moneda
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
