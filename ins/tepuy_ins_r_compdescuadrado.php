<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Listado de Comprobantes Descuadrados</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../ins/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Instala</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../ins/js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	
	require_once("../shared/class_folder/tepuy_include.php");
	$sig_inc=new tepuy_include();
	$con=$sig_inc->uf_conectar();

$la_emp=$_SESSION["la_empresa"];
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  
  <table width="381" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="379"></td>
    </tr>
    <tr class="titulo-ventana">
      <td width="379" height="13" colspan="4" align="center"><div align="center">Listado de Comprobantes Descuadrados  </div></td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      	<td colspan="4" align="center">Tipo de Comprobante   	    
      	  <select name="cmbprocede" id="cmbprocede">
      	    <option value=""selected="selected">Todos</option>
      	    <option value="SEP">Solicitudes de Ejecucion Presupuestaria</option>
      	    <option value="SOC">Ordenes de Compra</option>
      	    <option value="CXP">Cuentas por Pagar</option>
      	    <option value="SCB">Banco</option>
      	    <option value="SNO">Nomina</option>
      	    <option value="SAF">Activos Fijos</option>
      	    <option value="SPI">Presupuesto de Ingresos</option>
      	    <option value="SPG">Presupuesto de Gasto</option>
      	    <option value="SCG">Contabilidad Fiscal/Patrimonial</option>
          </select>   	    </td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><label>
      	<input name="botimprimir" type="button" class="boton" id="botimprimir" value="Imprimir" onClick="javascript:ue_imprimir();">
      </label></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right">     <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
  </table>
 
</table>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir()
{
  f=document.form1;
  procede=f.cmbprocede.value;
  pagina="reportes/tepuy_ins_rpp_comprobantesdescuadrados.php?procede="+procede;
  window.open(pagina,"comprobantes","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
