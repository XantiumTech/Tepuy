<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_ano=substr($ls_cargorep = $_SESSION["la_empresa"]["periodo"],0,4);//date('Y');
	$ls_mes=date('m');
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$io_fun_sfa->uf_load_seguridad("SFA","tepuy_sfa_r_retencionesunificadas.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_sfa->uf_select_config("SFA","REPORTE","FORMATO_RETMUN","tepuy_sfa_rpp_retencionesunificadas.php","C");
	$ls_activo          = "checked";
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Retenciones Unificadas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sfa.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="js/funcion_sfa.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Facturación</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>    </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;	</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sfa->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_sfa);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="142"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Reporte de Retenciones Unificadas </td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" colspan="4" align="center"><div align="left">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
</div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Mes</div></td>
      <td width="208" height="22" align="center"><div align="left">
        <label>
        <select name="cmbmes" id="cmbmes">
          <option value="01" <?php if($ls_mes=="01"){ print "selected";} ?>>ENERO</option>
          <option value="02" <?php if($ls_mes=="02"){ print "selected";} ?>>FEBRERO</option>
          <option value="03" <?php if($ls_mes=="03"){ print "selected";} ?>>MARZO</option>
          <option value="04" <?php if($ls_mes=="04"){ print "selected";} ?>>ABRIL</option>
          <option value="05" <?php if($ls_mes=="05"){ print "selected";} ?>>MAYO</option>
          <option value="06" <?php if($ls_mes=="06"){ print "selected";} ?>>JUNIO</option>
          <option value="07" <?php if($ls_mes=="07"){ print "selected";} ?>>JULIO</option>
          <option value="08" <?php if($ls_mes=="08"){ print "selected";} ?>>AGOSTO</option>
          <option value="09" <?php if($ls_mes=="09"){ print "selected";} ?>>SEPTIEMBRE</option>
          <option value="10" <?php if($ls_mes=="10"){ print "selected";} ?>>OCTUBRE</option>
          <option value="11" <?php if($ls_mes=="11"){ print "selected";} ?>>NOVIEMBRE</option>
          <option value="12" <?php if($ls_mes=="12"){ print "selected";} ?>>DICIEMBRE</option>
        </select>
        </label>
</div></td>
      <td width="66" height="22" align="center"><div align="right">A&ntilde;o</div></td>
      <td width="182" align="center"><div align="left">
        <label>
        <input name="txtano" type="text" id="txtano" value="<?php print $ls_ano;?>" size="6" maxlength="4" readonly>
        </label>
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Clientes </td>
    </tr>
        <tr>
          <td height="22"><div align="right">Desde
            <input name="txtcedclides" type="hidden" id="txtcedclides" value="<?php print $ls_cedclides ?>"
 size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
	<input name="txtnomclides"    type=text id="txtnomclides"    class="formato-blanco"  size=20 maxlength=30 readonly>

              <a href="javascript: ue_catalogo('tepuy_catdinamic_cliente.php?tipo=REPDES');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
          <td><div align="right">Hasta</div></td>
          <td><input name="txtcedclihas" type="hidden" id="txtcedclihas" value="<?php print $ls_cedclihas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
	<input name="txtnomclihas"    type=text id="txtnomclihas"    class="formato-blanco"  size=20 maxlength=30 readonly>
              <a href="javascript: ue_catalogo('tepuy_catdinamic_cliente.php?tipo=REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
        </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Facturas </td>
    </tr>
        <tr>
           <td height="22"><div align="right">Desde
            <input name="txtnumfacturades" type="text" id="txtnumfacturades" value="<?php print $ls_numfacturades ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('tepuy_sfa_cat_facturas_reten.php?tipo=DESDE');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
             <td><div align="right">Hasta</div></td>
            <td><input name="txtnumfacturahas" type="text" id="txtnumfacturahas" value="<?php print $ls_numfacturahas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('tepuy_sfa_cat_facturas_reten.php?tipo=HASTA');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
        </tr>

      </table></td>
    </tr>
    <tr>
      <td height="33" colspan="4" align="center">      <div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="5"><strong>Rango de Fecha </strong></td>
            </tr>
          <tr>
            <td width="136"><div align="right">Desde</div></td>
            <td width="101"><input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
            <td width="42"><div align="right">Hasta</div></td>
            <td width="129"><div align="left">
                <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
            </div></td>
            <td width="101">&nbsp;</td>
          </tr>
          <tr>
    <td height="22" colspan="3" align="center"><input name="chkincluir" type="checkbox" class="sin-borde" id="chkincluir" value="1" checked <?php print $ls_activo; ?>> 
      Incluir Logo/Numeracion de Comprobante Unificado</td>
  </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0">Buscar Documentos</a></div></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
  		<div id="resultados" align="center"></div>	</td>
    </tr>
  </table>
	<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_limpiarproben()
{
	f=document.formulario;
	f.txtcedclides.value="";
	f.txtcedclihas.value="";
}

function ue_catalogo_solicitud()
{
	tipo="REPDES";
	window.open("tepuy_cxp_cat_solicitudpago.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
}

	
function ue_print()
{
	f=document.formulario;
	incluir=0;
	if(f.chkincluir.checked==true)
	{
		incluir=1;
	}
	//alert(incluir);
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_clientes="";
		totrow=ue_calcular_total_fila_local("txtfactura");
		//alert(totrow);
		f.total.value=totrow;
		ls_mes=f.cmbmes.value;
		ls_anio=f.txtano.value;
		for(li_i=1;li_i<=totrow;li_i++)
		{
			if(eval("f.checkcmp"+li_i+".checked==true"))
			{
				ls_documento=eval("f.txtfactura"+li_i+".value");
				ls_rifcli=eval("f.txtrifcli"+li_i+".value");
				ls_dircli=eval("f.txtdircli"+li_i+".value");
				ls_nomcli=eval("f.txtnomcli"+li_i+".value");
				//Agregado el 22-09-2016 por Ing. Arnaldo Paredes para enviar la fecha de la Retención
				ls_fecfactura=eval("f.txtfecha"+li_i+".value");
				//alert(ls_fecfactura);
				ls_codret=eval("f.txtcodret"+li_i+".value");
				//alert(ls_codret);
				if(ls_clientes.length>0)
				{
					ls_clientes = ls_clientes+"."+ls_documento;
					//ls_codretenciones = ls_codretenciones+"-"+ls_codret;
				}
				else
				{
					ls_clientes = ls_documento;
					//ls_codretenciones = ls_codret;
				}
				//alert(ls_clientes);
			}
		}
		if(ls_clientes!="")
		{
			tiporeporte=f.cmbbsf.value;
			formato=f.formato.value;
			formato="tepuy_sfa_rpp_retencionesunificadas.php";
			//pagina="reportes/"+formato+"?proveedores="+ls_clientes+"&mes="+ls_mes+"&anio="+ls_anio+"&tiporeporte="+tiporeporte+"&ls_numfactura="+ls_numfactura+"&encabezado="+incluir;
			//Modificado el 22-09-2016 por Ing. Arnaldo Paredes para enviar la fecha de la Retención
			pagina="reportes/"+formato+"?clientes="+ls_clientes+"&mes="+ls_mes+"&anio="+ls_anio+"&tiporeporte="+tiporeporte+"&encabezado="+incluir;
			//alert(pagina);
			window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
		}
		else
		{
			alert("Debe seleccionar al menos un Número de Documento.");	   
		}	  
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX

	cedclides=f.txtcedclides.value;
	cedclihas=f.txtcedclihas.value;
	fecdes=f.txtfecdes.value;
	fechas=f.txtfechas.value;
	numfacturades=f.txtnumfacturades.value;
	numfacturahas=f.txtnumfacturahas.value;
	mes=f.cmbmes.value;
	anio=f.txtano.value;
	//alert(mes+anio);
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sfa_c_modcmpret_ajax.php",true);

	ajax.onreadystatechange=function()
	{
		if (ajax.readyState==4) {
					divgrid.innerHTML = ajax.responseText
					}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	//alert("aqui");
	ajax.send("proceso=RETENCIONESUNIFICADAS"+"&cedclides="+cedclides+"&cedclihas="+cedclihas+"&fecdes="+fecdes+"&fechas="+fechas+"&mes="+mes+"&anio="+anio);
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

function uf_checkall()
{
	f=document.formulario;
	totrow=ue_calcular_total_fila_local("txtnumcom");
	f.total.value=totrow;
	if(f.checkall.checked==true)
	{
		for(li_i=1;li_i<=totrow;li_i++)
		{
			eval("f.checkcmp"+li_i+".checked=true");
		}
	}
	else
	{
		for(li_i=1;li_i<=totrow;li_i++)
		{
			eval("f.checkcmp"+li_i+".checked=false");
		}
	}
}
</script>
</html>
