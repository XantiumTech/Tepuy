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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","tepuy_cxp_r_retencionesdeclaracioniva.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="declaracioninformativa";
	@mkdir($ls_ruta,0755);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Declaraci&oacute;n Informativa de Retenciones IVA</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>

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
<script language="javascript" src="js/funcion_cxp.js"></script>
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
<?php
	$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
	require_once("reportes/tepuy_cxp_class_report.php");
	$io_reporte=new tepuy_cxp_class_report("../");
	switch ($ls_operacion) 
	{
		case "GENERARTXT":
			$ls_mes=$_POST["cmbmes"];
			$ls_anio=$_POST["txtano"];
			$ls_quincena=$_POST["cmbquincena"];
			$ls_codemp=$_SESSION["la_empresa"]["codemp"];
			$ls_tiporeten=$io_reporte->uf_obtengo_retencion("SELECT codcmp FROM cxp_contador WHERE codemp='$ls_codemp' AND tipo='I'",'C');
			//print  $ls_tiporeten;
			$lb_valido=$io_reporte->uf_declaracioninformativa($ls_quincena,$ls_tiporeten,$ls_mes,$ls_anio,$la_seguridad,$nombredelarchivoiva);
			if($lb_valido)
			{
				$io_reporte->io_mensajes->message("El txt fué generado con el nombre: ".$nombredelarchivoiva);
			}
			else
			{
				$io_reporte->io_mensajes->message("Ocurrio un error al generar el TXT");
			}
			break;
	}
	unset($io_reporte);
?>
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
 <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/impresora.gif" alt="" width="20" height="20" border="0" title="Imprimir Relación"></a></div></td>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_generartxt();"><img src="../shared/imagebank/tools20/gendisk.png" alt="Generar" width="21" height="20" border="0" title="Generar txt"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar();"><img src="../shared/imagebank/tools20/download.png" alt="" width="20" height="20" border="0" title="Descargar txt"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
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
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="142"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Declaraci&oacute;n Informativa de Retenciones de IVA </td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Mes</div></td>
      <td width="208" height="22" align="center"><div align="left">
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
</div></td>
      <td width="66" height="22" align="center"><div align="right">A&ntilde;o</div></td>
      <td width="182" align="center"><div align="left">
        <input name="txtano" type="text" id="txtano" value="<?php print $ls_ano;?>" size="6" maxlength="4" readonly>
</div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Quincena</div></td>
      <td height="22" align="center">
        <div align="left">
          <select name="cmbquincena" id="cmbquincena">
            <option value="1" selected>Primera Quincena</option>
            <option value="2" >Segunda Quincena</option>
          </select>
          </div>
     </td>
      <td height="22" align="center">&nbsp;</td>
      <td height="22" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="33" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><input name="operacion" type="hidden"  id="operacion">
  		</td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)

function ue_generartxt()
{
	f=document.formulario;
	f.operacion.value="GENERARTXT";
	f.action="tepuy_cxp_r_retencionesdeclaracioniva.php";
	f.submit();	  
}

function ue_imprimir()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_mes=f.cmbmes.value;
		ls_anio=f.txtano.value;
		ls_quincena=f.cmbquincena.value;
		formato="tepuy_cxp_rpp_libro_iva.php";
		pagina="reportes/"+formato+"?mes="+ls_mes+"&anio="+ls_anio+"&quincena="+ls_quincena;
		window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}

function ue_descargar()
{
	pagina="tepuy_cxp_cat_directorio.php?ruta=declaracioninformativa";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,left=50,top=50");  
}
   
function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}
</script>
</html>
