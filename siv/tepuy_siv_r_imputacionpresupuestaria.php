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
require_once("class_funciones_inventario.php");
$io_fun_inventario=new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIV","tepuy_siv_r_imputacionpresupuestaria.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_inventario->uf_select_config("SIV","REPORTE","IMPUTACION_PRESU","tepuy_siv_rpp_imputacionpresupuestaria_barinas.php","C");
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_ano=substr($ls_cargorep = $_SESSION["la_empresa"]["periodo"],0,4);//date('Y');
//$ls_mes=date('m');
$ld_fecemides=date("01/01/").$ls_ano;
$ld_fecemihas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado Imputación presupuestaria del Inventario</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="css/siv.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-weight: bold}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>-->
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
  <tr>
    <td height="42" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a><a href="../siv/tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
 
  
require_once("../shared/class_folder/tepuy_include.php");
$io_in=new tepuy_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();

require_once("tepuy_siv_c_articulo.php");
$io_siv=new tepuy_siv_c_articulo();

$ls_year=date("Y");
$ld_fecdes="01/01/".$ls_year;
$ld_fechas=date("d/m/Y");
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
$li_catalogo=$io_siv->uf_siv_select_catalogo($li_estnum,$li_estcmp);

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="517" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="518" colspan="2" class="titulo-ventana">Listado  de Imputaci&oacute;n Presupuestaria del Inventario </td>
    </tr>
  </table>
  <table width="517" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="center"></div>
      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong> Art&iacute;culos </strong></td>
        </tr>
        <tr>
          <td width="89"><div align="right">Desde</div></td>
          <td width="409" height="22"><div align="left">
              <input name="txtcoddesde" type="text" id="txtcoddesde" size="24" maxlength="20"  style="text-align:center ">
              <a href="javascript:uf_catalogoarticulo('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> 
              <input name="txtdendesde" type="text" class="sin-borde" id="txtdenart2" size="40" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td height="22"><div align="left">
              <input name="txtcodhasta" type="text" id="txtcodprov22" size="24" maxlength="20"  style="text-align:center">
              <a href="javascript:uf_catalogoarticulo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> 
              <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta2" size="40" readonly>
              <input name="hidunidad" type="hidden" id="hidunidad">
          </div>
            <div align="left"> </div></td>
        </tr> 
    <tr>
      <td height="33" colspan="4" align="center">      <div align="left">
        <table width="482" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><strong>Intervalo de Fechas </strong></td>
            <td width="63">&nbsp;</td>
            <td width="167">&nbsp;</td>
            <td width="56">&nbsp;</td>
          </tr>
          <tr>
            <td width="78"><div align="right">Desde</div></td>
            <td width="75"><input name="txtdesde" type="text" id="txtdesde"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecemides; ?>" size="12" maxlength="10"  datepicker="true"></td>
            <td><div align="right">Hasta</div></td>
            <td><div align="left">
                <input name="txthasta" type="text" id="txthasta"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecemihas; ?>" size="12" maxlength="10"  datepicker="true">
            </div></td>
            <td>&nbsp;</td>
          </tr>
        <tr>
          <td height="13" colspan="2">&nbsp;</td>
          </tr>
	<?php
	if($li_catalogo==1)
	{
	?>
	<?php
	}
	?>
      </table></td>
    </tr>
<!--    <tr>
      <td width="99" height="12" align="center"><div align="right" class="style1 style14"></div></td>
      <td colspan="2" align="left">&nbsp;        </td>
      <td width="70" align="center"><div align="right" class="style1 style14"></div></td>
      <td width="336" align="center"><div align="left">
        <input name="hidstatus" type="hidden" id="hidstatus">
      </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left">
        <table width="478" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="5"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="84" height="33"><div align="right"></div>              
          <div align="right">Art&iacute;culo
                    <input name="rdorden" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="115"><div align="right"> 
              <p>Denominacion 
                <input name="rdorden" type="radio" class="sin-borde" value="radiobutton">
                </p>
              </div></td>
            <td width="81"><div align="right"></div></td>
            <td width="95"><div align="right"></div></td>
			<?php
			if($li_catalogo==1)
			{
			?>
		    <td width="101"><div align="right">
		      <label></label>
            </div></td>
			<?php
			}
			?>
          </tr>
        </table>
      </div></td>
    </tr> -->
          <tr> 
            <td height="22"><div align="right">Tipo de Reporte</div></td>
            <td height="22" colspan="3"><div align="left">
              <select name="cmbtiprep" id="cmbtiprep" >
                <option value="1">Imputación Presupuestaria</option>
                <option value="2">Resumen de Contabilidad</option>
              </select> 
          </tr>
    <tr>
      <td height="24" colspan="5" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
<!--    <tr>
      <td colspan="5" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr> -->
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<!-- <p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p> -->
</body>
<script language="JavaScript">

	function uf_catalogoarticulo(ls_coddestino,ls_dendestino)
	{
		window.open("tepuy_catdinamic_articulom.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
    function uf_imprimir(ls_reporte)
	{
		f=document.form1;
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{
			ls_coddesde= f.txtcoddesde.value;
			ls_codhasta= f.txtcodhasta.value;
			ld_desde=    f.txtdesde.value;
			ld_hasta=    f.txthasta.value;
			ls_tiprep=   f.cmbtiprep.value;
			if(ls_tiprep==1)
			{
				ls_reporte="tepuy_siv_rpp_imputacionpresupuestaria_barinas.php";
			}
			else
			{
				ls_reporte="tepuy_siv_rpp_resumencontabilidad_barinas.php";
			}
			window.open("reportes/"+ls_reporte+"?desde="+ld_desde+"&hasta="+ld_hasta+"&coddesde="+ls_coddesde+"&codhasta="+ls_codhasta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			f.action="tepuy_siv_r_imputacionpresupuestaria.php";
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
