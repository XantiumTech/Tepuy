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
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_r_mayor_presupuestario.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte  = $io_fun_banco->uf_select_config("SCB","REPORTE","MAYOR_PRESUPUESTARIO","tepuy_scb_rpp_mayor_presupuestario.php","C");//Detallado.
$ls_reporte1 = $io_fun_banco->uf_select_config("SCB","REPORTE","PRESUPUESTARIO_ESTRUCTURA","tepuy_scb_rpp_mayorpre_estructura.php","C");//Por Estructura.
$ls_reporte2 = $io_fun_banco->uf_select_config("SCB","REPORTE","PRESUPUESTARIO_PARTIDA","tepuy_scb_rpp_mayorpre_partida.php","C");//Por Partida.
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
<title>Mayor Presupuestario</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<?php
if (array_key_exists("operacion",$_POST))
   {
     $ls_fecdes = $_POST["txtfecdes"];
	 $ls_fechas = $_POST["txtfechas"];
     $ls_codope = $_POST["operacion"];
   }
else
   {
     $ls_fecdes = "";
	 $ls_fechas = "";
	 $ls_codope = "";
   }
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Caja y Banco</td>
            <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
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
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" width="20" height="20" border="0" alt="Imprimir" title="Imprimir"></a></div></td>
    <td class="toolbar" width="23"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="664">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form id="tepuy_scb_r_mayor_presupuestario" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="434" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="4" class="titulo-celda">Mayor Presupuestario </td>
    </tr>
    <tr>
      <td width="6">&nbsp;</td>
      <td width="171">&nbsp;</td>
      <td width="135">&nbsp;</td>
      <td width="120">&nbsp;</td>
    </tr>
    <tr>
      <td height="52" colspan="4"><table width="402" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4" style="text-align:left"><strong>Fecha:</strong></td>
        </tr>
        <tr>
          <td width="71" height="28" style="text-align:right">Desde</td>
          <td width="193" style="text-align:left"><input name="txtfecdes" type="text" id="txtfecdes" value="<?php print $ls_fecdes ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" /></td>
          <td width="49" style="text-align:right">Hasta</td>
          <td width="128" style="text-align:left"><input name="txtfechas" type="text" id="txtfechas" value="<?php print $ls_fechas ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" /></td>
        </tr>
      </table></td>
    </tr>

    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="55" colspan="4" style="text-align:center"><table width="402" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4" style="text-align:left"><strong>Resumen:</strong></td>
        </tr>
        <tr>
          <td width="19" height="28" style="text-align:right">&nbsp;</td>
          <td width="128" style="text-align:left"><label>
            <input name="radtipres" type="radio" class="sin-borde" value="D" checked="checked" />
          Detallado</label></td>
          <td width="137" style="text-align:left"><label>
            <input name="radtipres" type="radio" class="sin-borde" value="E" />
          Por Estructura </label></td>
          <td width="116" style="text-align:left"><label>
            <input name="radtipres" type="radio" class="sin-borde" value="P" />
          Por P&aacute;rtida </label></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f = document.form1;
function ue_imprimir()
{
  as_reporte  = "<?php print $ls_reporte; ?>";
  as_reporte1 = "<?php print $ls_reporte1; ?>";
  as_reporte2 = "<?php print $ls_reporte2; ?>";
  li_imprimir = f.imprimir.value;
  if (li_imprimir==1)
     {
	   ls_fecdes = f.txtfecdes.value;
	   ls_fechas = f.txtfechas.value;
	   if (ls_fecdes!="" && ls_fechas!="")
	      {
		    lb_valido = ue_comparar_intervalo();
		    if (lb_valido)
			   {
				 if (f.radtipres[0].checked==true)
				    {
					  ls_tipres  = f.radtipres[0].value;
					  ls_reporte = as_reporte;
					}
				 else if (f.radtipres[1].checked==true)
				    {
					  ls_tipres  = f.radtipres[1].value;
					  ls_reporte = as_reporte1;
					}
				 else
				    {
					  ls_tipres  = f.radtipres[2].value;
					  ls_reporte = as_reporte2;
					}
				 pagina="reportes/"+ls_reporte+"?fecdes="+ls_fecdes+"&fechas="+ls_fechas+"&tipres="+ls_tipres;
				 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
			   }		  
		  }
	   else
	      {
		    alert("Debe completar el Rango de Fechas para la B�squeda !!!");
		  }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operaci�n !!!"); 
	 }
}

function ue_comparar_intervalo()
{ 
  var valido = false; 
  var diad = f.txtfecdes.value.substr(0, 2); 
  var mesd = f.txtfecdes.value.substr(3, 2); 
  var anod = f.txtfecdes.value.substr(6, 4); 
  var diah = f.txtfechas.value.substr(0, 2); 
  var mesh = f.txtfechas.value.substr(3, 2); 
  var anoh = f.txtfechas.value.substr(6, 4); 
  
  if (anod < anoh)
     {
	   valido = true; 
     }
  else 
     { 
       if (anod == anoh)
          { 
            if (mesd < mesh)
			   {
			     valido = true; 
			   }
            else 
               { 
		         if (mesd == mesh)
		            {
					  if (diad <= diah)
					     {
					       valido = true; 
					     }
		            }
               } 
          } 
     } 
  if (valido==false)
     {
	   alert("Error en Rango de Fechas, Por Favor Verifique !!!");
     }  
  return valido;
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
