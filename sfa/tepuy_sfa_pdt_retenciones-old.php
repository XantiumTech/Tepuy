<?php
	session_start();
	ini_set('display_errors', 1);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_modageret = $_SESSION["la_empresa"]["modageret"];
	//print "factura: ".$ls_numfactura." Sub-Total: ".$li_subtotal." I.V.A.:".$li_cargos." Modalida de Agente: ".$ls_modageret." Tipo: ".$ls_tipo;
	unset($io_fun_sfa);
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retenciones()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retenciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cdeducciones a aplicar en la recepción de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 22/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_grid, $io_ds_deducciones;
		
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../shared/class_folder/class_datastore.php");
		$io_ds_deducciones=new class_datastore(); // Datastored de cuentas contables
		require_once("../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();
		require_once("class_folder/class_funciones_sfa.php");
		$io_fun_sfa=new class_funciones_sfa();
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
			$lo_title[2]=utf8_encode("Código");
			$lo_title[3]=utf8_encode("Denominación");
			if($li_cargos>0)
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
			print "objeto: ".$lo_object[1][1];
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
	}// end function uf_print_retenciones
	//-----------------------------------------------------------------------------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Retenciones aplicables a la Factura</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sfa.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<!--<body onLoad="javascript: ue_search();"> -->
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codcar">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<input name="numfactura" type="hidden" id="numfactura" value="<?php print $ls_numfactura; ?>">
<input name="subtotal" type="hidden" id="subtotal" value="<?php print $li_subtotal; ?>">
<input name="cargos" type="hidden" id="cargos" value="<?php print $li_cargos; ?>">
<!--<input name="procede" type="hidden" id="procede" value="<?php print $ls_procede; ?>"> -->
<input name="totrow" type="hidden" id="totrow">
  <table width="580" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Listado de Retenciones aplicables a la Factura</td>
    </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
<?php
uf_print_retenciones();
?>
</form>      
</body>
<script language="JavaScript">
function ue_cerrar()
{
	close();
}

function ue_calcular(fila)
{
	f=document.formulario;
	marcado=eval("f.chkdeduccion"+fila+".checked");
	//alert(marcado);
	if(marcado==true)
	{
		baseimponible=eval("f.txtmonobjret"+fila+".value");
		//alert(baseimponible);
		baseimponible=ue_formato_calculo(baseimponible);
		deducible=eval("f.txtmonded"+fila+".value");
		deducible=ue_formato_calculo(deducible);
		//alert(deducible);
		if(parseFloat(baseimponible)>0)
		{
			formula=eval("f.formula"+fila+".value");
			while(formula.indexOf("$LD_MONTO")!=-1)
			{ 
				formula=formula.replace("$LD_MONTO",baseimponible);
			} 
			while(formula.indexOf("ROUND")!=-1)
			{ 
				formula=formula.replace("ROUND","redondear");
			} 
			formula=formula.replace("IIF","ue_iif");
			deduccion=eval(formula);
			//deduccion=eval(deduccion+"-"+deducible);
			deduccion=eval(deduccion+"-"+deducible/10000);
			if(deduccion>0)
			{
				deduccion=redondear(deduccion,2);
				deduccion=uf_convertir(deduccion);
				eval("f.txtmonret"+fila+".value='"+deduccion+"'"); 
			}
			else
			{
				alert("El monto de la Retención es menor ó Igual a Cero");
				eval("f.chkdeduccion"+fila+".checked=false;");
			}
		}
	}
	else
	{
		eval("f.txtmonret"+fila+".value='0,00'"); 
	}
}


function ue_aceptar()
{
	f=document.formulario;
	//---------------------------------------------------------------------------------
	// recorremos el arreglo de las Retenciones para cargar las cuentas
	//---------------------------------------------------------------------------------
	li_deducciones=0;
	totrow=ue_calcular_total_fila_local("txtcodded");
	//alert(totrow);
	li_i=0;
	for(j=1;(j<=totrow);j++)
	{
		marcado=eval("f.chkdeduccion"+j+".checked");
		monto=ue_formato_calculo(eval("f.txtmonret"+j+".value"));
		montoobj=ue_formato_calculo(eval("f.txtmonobjret"+j+".value"));
		if((marcado==true)&&(parseFloat(monto)>0)&&(parseFloat(montoobj)>=parseFloat(monto)))
		{
			li_i=li_i+1;
			codded=eval("f.txtcodded"+j+".value");
			monobjret=eval("f.txtmonobjret"+j+".value");
			monret=eval("f.txtmonret"+j+".value");
			sccuenta=eval("f.sccuenta"+j+".value");
			porded=eval("f.porded"+j+".value");
			iva=eval("f.txtiva"+j+".value");
			li_deducciones=eval(li_deducciones+"+"+ue_formato_calculo(monret));
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	li_subtotal=ue_formato_calculo(opener.document.form1.txtsubtotfac.value);
	li_cargos=ue_formato_calculo(opener.document.form1.txttotivafac.value);
	li_total=eval(li_subtotal+"+"+li_cargos);
	li_totalgeneral=eval(li_total+"-"+li_deducciones);
	opener.document.form1.txtdeducciones.value=li_deducciones;
	opener.document.form1.txttotalgener.value=li_totalgeneral;
	close();
}

function uf_aceptar_deducciones(li_totrows)
{
  f         = document.form1;
  fop       = opener.document.form1;
  li_filsel = 0;
  li_filope = fop.hidfilsel.value;
  ls_tipcmp = f.tipo.value;
  lb_valido = false;
  for (i=1;i<=li_totrows;i++)
      {
	    if (eval("f.radiodeduccion"+i+".checked==true"))
		   {
		     lb_valido=true;
			 li_filsel=i;
			 break;
		   }
	  }
  if (lb_valido)
     {
       ld_porded = eval("f.porded"+li_filsel+".value");
	   ls_forded = eval("f.formula"+li_filsel+".value");
	   if (ls_tipcmp=='CMPRETIVA')
	      {
		    ld_moniva = eval("fop.txttotimp"+li_filope+".value");
	        ld_monto  = ue_formato_calculo(ld_moniva);
		  }
	   else
	      {
		    ld_basimp = eval("fop.txtbasimp"+li_filope+".value");
	        ld_monto  = ue_formato_calculo(ld_basimp);
		  }
	   while(ls_forded.indexOf("$LD_MONTO")!=-1)
			{ 
			  ls_forded=ls_forded.replace("$LD_MONTO",ld_monto);
			}
	   while(ls_forded.indexOf("ROUND")!=-1)
			{ 
			  ls_forded=ls_forded.replace("ROUND","redondear");
			}
	   ls_forded = ls_forded.replace("IIF","ue_iif");
	   ld_totret = eval(ls_forded);
	   ld_totret = redondear(ld_totret,3);
	   ld_totret = uf_convertir(ld_totret);
	   ld_porded  = ue_formato_calculo(ld_porded);
	   eval("fop.txtivaret"+li_filope+".value='"+ld_totret+"'");
	   eval("fop.txtporret"+li_filope+".value='"+ld_porded+"'");
	   close();
	 }
}
</script>
</html>
