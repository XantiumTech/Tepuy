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
	require_once("class_funciones_activos.php");
	$io_fun_activo=new class_funciones_activos("../");
	$io_fun_activo->uf_load_seguridad("SAF","tepuy_saf_p_entregaunidad.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("tepuy_saf_c_activo.php");
    $ls_codemp = $_SESSION["la_empresa"]["codemp"];
    $io_saf_tipcat= new tepuy_saf_c_activo();
    $ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_limpiarvariables()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//////////////////////////////////////////////////////////////////////////////
		global $ls_cmpent,$ls_coduniadm,$ls_denuniadm,$ls_codresact,$ls_codresnew,$ls_nomresact,$ls_nomresnew;
		global $ls_obsentuni,$ld_fecentuni;
		
		$ls_cmpent="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_codresact="";
		$ls_codresnew="";
		$ls_nomresact="";
		$ls_nomresnew="";
		$ls_obsentuni="";
		$ld_fecentuni= date("d/m/Y");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Entrega de Unidad </title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		var alt  = window.event.altKey;
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
          <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
    <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" colspan="8" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"></a><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("tepuy_saf_c_entregaunidad.php");
	$io_saf= new tepuy_saf_c_entregaunidad();
	require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
	$io_keygen= new tepuy_c_generar_consecutivo();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusureg=$_SESSION["la_logusr"];
	$ls_operacion=$io_fun_activo->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_cmpent= $io_keygen->uf_generar_numero_nuevo("SAF","saf_entregauniadm","cmpent","SAF",15,"","codemp",$ls_codemp);
		break;
		
		case "GUARDAR";
			$ls_cmpent= $io_fun_activo->uf_obtenervalor("txtcmpent","");
			$ls_coduniadm= $io_fun_activo->uf_obtenervalor("txtcoduniadm","");
			$ld_fecentuni= $io_fun_activo->uf_obtenervalor("txtfecentuni","");
			$ls_codresact= $io_fun_activo->uf_obtenervalor("txtcodresact","");
			$ls_codresnew= $io_fun_activo->uf_obtenervalor("txtcodresnew","");
			$ls_obsentuni= $io_fun_activo->uf_obtenervalor("txtobsentuni","");
			$ls_denuniadm= $io_fun_activo->uf_obtenervalor("txtdenuniadm","");
			$ls_nomresact= $io_fun_activo->uf_obtenervalor("txtnomresact","");
			$ls_nomresnew= $io_fun_activo->uf_obtenervalor("txtnomresnew","");
			$lb_valido=$io_saf->uf_saf_procesar_entregaunidad($ls_codemp,$ls_cmpent,$ld_fecentuni,$ls_coduniadm,$ls_obsentuni,
															  $ls_codusureg,$ls_codresact,$ls_codresnew,$la_seguridad);
			if ($lb_valido)
			{
				uf_limpiarvariables();
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="599" height="159" border="0" class="formato-blanco">
    <tr>
      <td width="620" height="153"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="584" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="3" class="titulo-ventana">Entrega de Unidad </td>
  </tr>
  <tr class="formato-blanco">
    <td width="113" height="19">&nbsp;</td>
    <td width="381"><div align="right">Fecha</div></td>
    <td width="88"><input name="txtfecentuni" type="text" id="txtfecentuni" style="text-align:center " value="<?php print $ld_fecentuni; ?>" size="13" maxlength="10" datepicker="true" onKeyPress="ue_separadores(this,'/',patron,true);"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Comprobante</div></td>
    <td height="22" colspan="2">        <input name="txtcmpent" type="text" id="txtcmpent" value="<?php print $ls_cmpent; ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center ">      
    <input name="hidstatus" type="hidden" id="hidstatus"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Ubicaci&oacute;n</div></td>
    <td height="22" colspan="2"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center " value="<?php print $ls_coduniadm; ?>" size="12" maxlength="10" >
      <a href="javascript:ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
      <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Responsable Actual </div></td>
    <td height="22" colspan="2"><input name="txtcodresact" type="text" id="txtcodresact" style="text-align:center " value="<?php print $ls_codresact; ?>" size="12" maxlength="10">
      <a href="javascript: ue_buscarresponsableactual();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
      <input name="txtnomresact" type="text" class="sin-borde" id="txtnomresact" value="<?php print $ls_nomresact; ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Nuevo Responsable </div></td>
    <td height="22" colspan="2"><input name="txtcodresnew" type="text" id="txtcodresnew" style="text-align:center " value="<?php print $ls_codresnew ?>" size="12" maxlength="10">
      <a href="javascript: ue_catapersonalnew();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
      <input name="txtnomresnew" type="text" class="sin-borde" id="txtnomresnew" value="<?php print $ls_nomresnew ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Observaciones</div></td>
    <td height="44" colspan="2" rowspan="2"><textarea name="txtobsentuni" cols="60" rows="3" id="txtobsentuni"><?php print $ls_obsentuni ?></textarea></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right"></div></td>
    </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_buscar()
{
	window.open("tepuy_catdinamic_tipoarticulo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarunidad()
{
	window.open("tepuy_saf_cat_unidadejecutora.php?destino=activo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarresponsableactual()
{
	window.open("tepuy_saf_cat_personal.php?destino=responsableactual","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catapersonalnew()
{
	f=document.form1;
	codres=f.txtcodresact.value;
	if(codres=="")
	{
		alert("Debe seleccionar el responsable actual");
	}
	else
	{
		window.open("tepuy_saf_cat_personal.php?destino=responsablenuevo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="tepuy_saf_p_entregaunidad.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_ejecutar=f.ejecutar.value;
	if((li_incluir==1)||(li_ejecutar==1))
	{
		coduniadm=f.txtcoduniadm.value;
		codresact=f.txtcodresact.value;
		codresnew=f.txtcodresnew.value;
		cmpent=f.txtcmpent.value;
		if((cmpent!="")&&(coduniadm!="")&&(codresact!="")&&(codresnew!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="tepuy_saf_p_entregaunidad.php";
			f.submit();
		}
		else
		{
			alert("Debe completar codigo de unidad, responsable actual y nuevo responsable");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
