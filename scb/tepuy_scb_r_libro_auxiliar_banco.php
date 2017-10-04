<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_r_libro_auxiliar_banco.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte  = $io_fun_banco->uf_select_config("SCB","REPORTE","LIBRO_AUXILIAR_BANCO","tepuy_scb_rpp_libro_auxiliar_banco.php","C");
$ls_reporte2 = $io_fun_banco->uf_select_config("SCB","REPORTE","LIBRO_AUXBCO_DETALLADO","tepuy_scb_rpp_libro_auxiliar_banco_detallado.php","C");
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Libro Auxiliar de Banco</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>
<body>
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40" /></td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Caja y Banco</td>
        <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
      </tr>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td width="665" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" title="Imprimir" width="20" height="20" border="0" /></a></td>
    <td class="toolbar" width="22"><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0" /></a></td>
    <td class="toolbar" width="22"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda" /></td>
    <td colspan="3" class="toolbar">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form id="tepuy_scb_r_libro_auxiliar_banco.php" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>" />
      </span>Libro Auxiliar de Banco 
      <input name="txttipocuenta" type="hidden" id="txttipocuenta" />
      <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta" />
      <span class="Estilo1">
      <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg" style="text-align:center" size="24" readonly="readonly" />
      <input name="txtdisponible" type="hidden" id="txtdisponible" style="text-align:right"  size="24" readonly="readonly" />
      <input name="hidnomcar" type="hidden" id="hidnomcar" />
      </span></td>
    </tr>
    <tr>
      <td width="78">&nbsp;</td>
      <td width="170">&nbsp;</td>
      <td width="132">&nbsp;</td>
      <td width="153">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="3"><label>
        <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" size="10" readonly="readonly" />
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos" /></a> 
        <input name="txtdenban" type="text" id="txtdenban" size="65" class="sin-borde" readonly="readonly" />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" size="30" maxlength="25" readonly="readonly" /> 
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias" /></a> <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="45" maxlength="254" readonly="readonly" /></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Mes</td>
      <td height="22"><select name="cmbmes" id="cmbmes">
        <option value="01" selected="selected">Enero</option>
        <option value="02">Febrero</option>
        <option value="03">Marzo</option>
        <option value="04">Abril</option>
        <option value="05">Mayo</option>
        <option value="06">Junio</option>
        <option value="07">Julio</option>
        <option value="08">Agosto</option>
        <option value="09">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
      </select></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Responsable</td>
      <td height="22" colspan="3"><label>
        <input name="txtcedres" type="text" id="txtcedres" size="15" maxlength="10" readonly style="text-align:center" />
        <a href="javascript:uf_catalogo_personal();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0" /></a> 
        <input name="txtnomres" type="text" class="sin-borde" id="txtnomres" size="60" maxlength="254" readonly />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right"><input name="chktiprep" type="checkbox" class="sin-borde" id="chktiprep" value="D" /></td>
      <td height="22">Detallado de Fondos </td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f = document.form1;
function cat_bancos()
{
  pagina="tepuy_cat_bancos.php";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
}

function catalogo_cuentabanco()
{
  ls_codban=f.txtcodban.value;
  ls_denban=f.txtdenban.value;
  if ((ls_codban!=""))
     {
	   pagina="tepuy_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_denban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
     }
  else
     {
	   alert("Seleccione un Banco !!!");   
     }
}

function ue_imprimir()
{
  li_imprimir = f.imprimir.value;
  if (li_imprimir==1)
     {
	   ls_codban    = f.txtcodban.value;
	   ls_nomban    = f.txtdenban.value;
	   ls_ctaban    = f.txtcuenta.value;
	   ls_denctaban = f.txtdenominacion.value;
	   ls_dentipcta = f.txtdentipocuenta.value;
	   li_nummes    = f.cmbmes.value;
	   ls_cedres    = f.txtcedres.value;
	   ls_nomres    = f.txtnomres.value;
	   ls_nomcar    = f.hidnomcar.value;
	   if (ls_codban=='' || ls_nomban=='' || ls_ctaban=='' || ls_denctaban=='' || ls_cedres=='' || ls_nomres=='')
		  { 
		    alert("Complete los Datos del Reporte !!!");
		  }
	   else
		  {
			li_pos    = document.getElementById("cmbmes");
            ls_nommes = li_pos.options[li_pos.selectedIndex].text;
			if (f.chktiprep.checked==true)
			   {
			     ls_reporte = "<?php echo $ls_reporte2; ?>";
			   }			
			else
			   {
			     ls_reporte = "<?php echo $ls_reporte; ?>";
			   }
			pagina = "reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&nomban="+ls_nomban+"&denctaban="+ls_denctaban+"&mes="+li_nummes+"&dentipcta="+ls_dentipcta+"&nomres="+ls_nomres+"&cedres="+ls_cedres+"&nomcar="+ls_nomcar+"&nommes="+ls_nommes;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		  }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

function uf_catalogo_personal()
{
  window.open("tepuy_scb_cat_personal_responsable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
}
</script>	 
</html>
