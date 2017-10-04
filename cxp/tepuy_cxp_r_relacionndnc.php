<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","tepuy_cxp_r_relacionndnc.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Relaci&oacute;n de Notas de Debito/Cr&eacute;dito </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Orden de Pago </td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
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
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="599" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="595" colspan="2" class="titulo-ventana">Reporte de Relaci&oacute;n de Notas de Debito/Cr&eacute;dito </td>
    </tr>
  </table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="598"></td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" colspan="3" align="center"><div align="left">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
            </select>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="199" height="22"><div align="left"><strong>Solicitudes de Pago </strong></div></td>
          <td width="89" height="22"><div align="center"></div></td>
          <td width="215" height="22"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Desde
            <input name="txtnumsoldes" type="text" id="txtnumsoldes" size="20" readonly>
              <a href="javascript: ue_catalogo_solicitudes('REPDES');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Hasta</div></td>
          <td><input name="txtnumsolhas" type="text" id="txtnumsolhas" size="20" readonly>
            <a href="javascript: ue_catalogo_solicitudes('REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td>
        </tr>

      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="199" height="22"><div align="left"><strong>Notas de Debito/Credito </strong></div></td>
          <td width="89" height="22"><div align="center"></div></td>
          <td width="215" height="22"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">
            <input name="rdndnc" type="radio" class="sin-borde" value="radiobutton" checked>
          Todas</div></td>
          <td><input name="rdndnc" type="radio" class="sin-borde" value="radiobutton">
            Debito</td>
          <td><input name="rdndnc" type="radio" class="sin-borde" value="radiobutton">
            Credito</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Desde
            <input name="txtnumdcdes" type="text" id="txtnumdcdes" size="20" readonly>
              <a href="javascript: ue_catalogo_notas('REPDES');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Hasta</div></td>
          <td><input name="txtnumdchas" type="text" id="txtnumdchas" size="20" readonly>
            <a href="javascript: ue_catalogo_notas('REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center">      <div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="5"><strong>Fecha de Registro </strong></td>
            </tr>
          <tr>
            <td width="136"><div align="right">Desde</div></td>
            <td width="101"><input name="txtfecregdes" type="text" id="txtfecregdes"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
            <td width="42"><div align="right">Hasta</div></td>
            <td width="129"><div align="left">
                <input name="txtfecreghas" type="text" id="txtfecreghas"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
            </div></td>
            <td width="101">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="3"><strong>Estatus</strong></td>
        </tr>
        <tr>
          <td width="183" height="22"><div align="right">
            <input name="chkemitida" type="checkbox" class="sin-borde" id="chkemitida" value="checkbox">
          Emitida</div></td>
          <td width="132" height="22">
            <div align="center">
              <input name="chkcontab" type="checkbox" class="sin-borde" id="chkcontab" value="checkbox">
            Contabilizada</div>
            <div align="left"></div></td>
          <td width="194" height="22">            <input name="chkanulada" type="checkbox" class="sin-borde" id="chkanulada" value="checkbox">
            Anulada          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
  </table>
    <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
	function ue_catalogo_solicitudes(ls_tipo)
	{
		ls_catalogo="tepuy_cxp_cat_solicitudpago.php?tipo="+ls_tipo+"";
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
		
	function ue_catalogo_notas(ls_tipo)
	{
		ls_catalogo="tepuy_cxp_cat_notas.php?tipo="+ls_tipo+"";
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
	}
	function uf_imprimir()
	{
		f=document.formulario;
		li_imprimir=f.imprimir.value;
		emitida=0;
		contabilizada=0;
		anulada=0;
		if(li_imprimir==1)
		{
			if(f.rdndnc[0].checked)
			{
				tipndnc="";
			}
			else
			{
				if(f.rdndnc[1].checked)
				{
					tipndnc="D";
				}
				else
				{
					tipndnc="C";
				}
			}
			if(f.chkemitida.checked==true)
				emitida=1;
			if(f.chkcontab.checked==true)
				contabilizada=1;
			if(f.chkanulada.checked==true)
				anulada=1;
			numsoldes=f.txtnumsoldes.value;
			numsolhas=f.txtnumsolhas.value;
			ndncdes=f.txtnumdcdes.value;
			ndnchas=f.txtnumdchas.value;
			fecregdes=f.txtfecregdes.value;
			fecreghas=f.txtfecreghas.value;
			tiporeporte=f.cmbbsf.value;
			pantalla="reportes/tepuy_cxp_rpp_relacionndnc.php?tipndnc="+tipndnc+"&numsoldes="+numsoldes+"&numsolhas="+numsolhas+
					 "&ndncdes="+ndncdes+"&ndnchas="+ndnchas+"&fecregdes="+fecregdes+"&fecreghas="+fecreghas+"&emitida="+emitida+
					 "&contabilizada="+contabilizada+"&anulada="+anulada+"&tiporeporte="+tiporeporte+"";
			window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{alert("No tiene permiso para realizar esta operación");}
	}

	function ue_cerrar()
	{
		window.location.href="tepuywindow_blank.php";
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
