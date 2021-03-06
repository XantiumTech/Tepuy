<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los cat�logos del sistema SEP con la utilizaci�n del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    	session_start();   
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_sfa.php");
	$io_funciones_sfa=new class_funciones_sfa();
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_sfa->uf_obtenervalor("catalogo","");
	//print $ls_catalogo;
	switch($ls_catalogo)
	{
		case "RETENCIONES":
			uf_print_retenciones();
			break;

	}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retenciones($ls_numfactura,$li_subtotal,$li_cargos)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retenciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: M�todo que inprime el resultado de la busqueda de las retenciones a aplicar en la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_sfa, $io_grid, $io_ds_deducciones;
		
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
		$ls_numfactura=$io_funciones_sfa->uf_obtenervalor("numfactura","");
		$li_subtotal=$io_funciones_sfa->uf_obtenervalor("subtotal","0,00");
		$li_cargos=$io_funciones_sfa->uf_obtenervalor("cargos","0,00");
		//print "Factura: ".$ls_numfactura. " Sub-Total: ".$li_subtotal." I.V.A.".$li_cargos;
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
			     $lo_title[4]="Monto Objeto Retenci�n";
			     $lo_title[5]=utf8_encode("Monto Retenci�n"); 
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
					$li_monobjret=$li_cargos;//number_format($li_cargos,2,',','.');
					
				}
				else
				{
					$li_monobjret=$li_subtotal;
				}
				//print "ded: ".$ls_codded;
				$li_row=$io_ds_deducciones->findValues(array('codded'=>$ls_codded),"codded");
				//print "li_row: ".$li_row." ";
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_row);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_row);
				}
				if(($li_cargos>0)||($li_subtotal>0))
				{
					$lo_object[$li_fila][1]="<input name=chkdeduccion".$li_fila."  type=checkbox id=chkdeduccion".$li_fila." class=sin-borde  value='1' onClick=javascript:ue_calcular('".$li_fila."') ".$ls_activo.">";
					$lo_object[$li_fila][2]="<input name=txtcodded".$li_fila."  type=text id=txtcodded".$li_fila."     class=sin-borde  style=text-align:center size=8 value='".$ls_codded."'   title ='".$ls_dended."' readonly><input name=txtmonded".$li_fila." type=hidden id=txtmonded".$li_fila." value='".$li_monded."'>";
					$lo_object[$li_fila][3]="<input name=txtdended".$li_fila."  type=text id=txtdended".$li_fila."     class=sin-borde  style=text-align:left size=60 value='".$ls_dended."'  title ='".$ls_dended."' readonly>";
					//print "Monto Obj.".$li_monobjret;
					if(($li_monobjret=="0,00")&&($li_iva==1))
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
          	//	 echo "<p align=right>&nbsp;&nbsp;&nbsp;<a href='javascript: uf_aceptar_deducciones($li_fila);'><img src='../shared/imagebank/tools20/aprobado.png' alt='Aceptar' width=20 height=20 border=0>Agregar Deducciones</a></p>";
      			 echo "</div></td>";
    			 echo "</tr>";
  				 echo "</table>";
			   }
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,680,"","griddeduccion");
			if(($li_cargos>0)||($li_subtotal>0))
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

////////////////////////// FIN DE LA BUSQUEDA DEL NRO DE CODIGO DE LA RETENCION QUE VIENE///////////////////////

?>
