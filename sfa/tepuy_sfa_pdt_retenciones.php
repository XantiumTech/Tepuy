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
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	//$ls_tipo=$io_fun_sfa->uf_obtenertipo();
	$ls_numfactura=$io_fun_sfa->uf_obtenervalor_get("numfactura","");
	$li_subtotal=$io_fun_sfa->uf_obtenervalor_get("subtotal","0,00");
	//$ls_procede=$io_fun_sfa->uf_obtenervalor_get("procede","CXPRCD");
	$li_cargos=$io_fun_sfa->uf_obtenervalor_get("cargos","0,00");
	$ls_modageret = $_SESSION["la_empresa"]["modageret"];
	unset($io_fun_sfa);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Retenciones alicables a la Factura</title>
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
<body onLoad="javascript: ue_search();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codcar">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="numfactura" type="hidden" id="numfactura" value="<?php print $ls_numfactura; ?>">
<input name="subtotal" type="hidden" id="subtotal" value="<?php print $li_subtotal; ?>">
<input name="cargos" type="hidden" id="cargos" value="<?php print $li_cargos; ?>">
<input name="totrow" type="hidden" id="totrow">
  <table width="580" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Retenciones aplicables a la Factura</td>
    </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
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
	if(marcado==true)
	{
		baseimponible=eval("f.txtmonobjret"+fila+".value");
		baseimponible=ue_formato_calculo(baseimponible);
		deducible=eval("f.txtmonded"+fila+".value");
		deducible=ue_formato_calculo(deducible);
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
				alert("El monto de la Deducci�n es menor � Igual a Cero");
				eval("f.chkdeduccion"+fila+".checked=false;");
			}
		}
	}
	else
	{
		eval("f.txtmonret"+fila+".value='0,00'"); 
	}
}

function ue_search()
{
	f=document.formulario;
	numfactura=opener.document.form1.txtnumfactura.value;
	subtotal=opener.document.form1.txtsubtotfac.value;
	cargos=opener.document.form1.txttotivafac.value;
	// Cargamos las variables para pasarlas al AJAX
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sfa_c_retencion_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La p�gina no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
				
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	//alert(numfactura+subtotal+cargos);
	ajax.send("catalogo=RETENCIONES&numfactura="+numfactura+"&subtotal="+subtotal+"&cargos="+cargos);
}

function ue_aceptar()
{
	f=document.formulario;
	//---------------------------------------------------------------------------------
	// recorremos el arreglo de las Retenciones para cargar las cuentas
	//---------------------------------------------------------------------------------
	li_deducciones=0;
	parametros="";
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
			parametros=parametros+"&txtcodded"+li_i+"="+codded+"&txtmonobjret"+li_i+"="+monobjret+""+
					   "&txtmonret"+li_i+"="+monret+"&sccuenta"+li_i+"="+sccuenta+"&porded"+li_i+"="+porded+"&iva"+li_i+"="+iva;
		}
	}
	parametros=parametros+"&totrowdeducciones="+li_i+"";
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	ls_numfactura=opener.document.form1.txtnumfactura.value;
	li_subtotal=ue_formato_calculo(opener.document.form1.txtsubtotfac.value);
	li_cargos=ue_formato_calculo(opener.document.form1.txttotivafac.value);
	li_total=eval(li_subtotal+"+"+li_cargos);
	li_totalgeneral=eval(li_total+"-"+li_deducciones);
	li_totalgeneral1=uf_convertir(li_totalgeneral);
	li_deducciones1=uf_convertir(li_deducciones);
	//alert(li_totalgeneral1+"Deducciones: "+li_deducciones1);
	opener.document.form1.txtdeducciones.value=li_deducciones1;
	opener.document.form1.txttotalgener.value=li_totalgeneral1;
	ls_proceso="FACTURA";
	parametros=parametros+"&subtotal="+li_subtotal+"&cargos="+li_cargos+"&deducciones="+li_deducciones+"&total="+li_total+"";
	parametros=parametros+"&numfactura="+ls_numfactura+"&totgeneral="+li_totalgeneral+"&cargardeducciones=1"+"";
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_sfa_c_recepreten_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText
					}
					else
					{
						if(ajax.status==404)
						{
							divgrid.innerHTML = "La p�gina no existe";
						}
						else
						{//mostramos el posible error     
							divgrid.innerHTML = "Error:".ajax.status;
						}
					}
					
				}
			}
		}	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		//alert(parametros);
		ajax.send("proceso="+ls_proceso+""+parametros);
		opener.document.formulario.totrowspg.value=totrowspg;
	}
	close();
}

function uf_aceptar_deducciones(li_totrows)
{
  f         = document.formulario;
  fop       = opener.document.formulario;
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