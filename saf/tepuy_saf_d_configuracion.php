<?
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
require_once("tepuy_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new tepuy_saf_c_activo();
$ls_rbtipocat= $io_saf_tipcat->uf_select_valor_config($ls_codemp);
$lb_cambio   = $io_saf_tipcat->uf_saf_select_activo($ls_codemp,'000000000000001');
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_rbcsc,$ls_rbcgr,$disabled,$lb_cambio;
		
		$ls_rbcsc="";
		$ls_rbcgr="";
		$ls_disabled="";
   }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Configuracion</title>
<script type="text/javascript" language="JavaScript1.2" src="shared/js/disabled_keys.js"></script>
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
<title >Definici&oacute;n de Activos</title>
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
</head>
<body>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="42" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
    <td class="toolbar" width="640">&nbsp;</td>
  </tr>
</table>
<p>
  <?
	require_once("tepuy_saf_c_activo.php");
	require_once("tepuy_saf_c_grupo.php");
	require_once("tepuy_saf_c_catalogo.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/tepuy_include.php");

	$io_saf   =  new tepuy_saf_c_activo();
	$io_saf_grupo = new tepuy_saf_c_grupo();
	$io_saf_catalogo = new tepuy_saf_c_catalogo();
	$in       =  new tepuy_include();
	$con      =  $in->uf_conectar();
	$io_msg   =  new class_mensajes();
	$io_func  =  new class_funciones();
   //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SAF";
	$ls_ventanas="tepuy_saf_d_configuracion.php";

	$la_seguridad["empresa"]=$ls_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_codemp,$ls_logusr,$ls_sistema,$ls_ventanas);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_rbcsc="";
		$ls_rbcgr="";
		$disabled="";
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		$ls_rbtipocat=$io_saf->uf_select_valor_config($ls_codemp);
		switch ($ls_rbtipocat) 
		{
			case '0':
		        uf_limpiarvariables();
			break;
			
			case '1':
				 $ls_rbcsc="checked";
				 $ls_disabled="disabled";
			break;
			
			case '2':
				$ls_rbcgr="checked";
				$ls_disabled="disabled";
			break;
		}
	}
	if($ls_operacion == "NUEVO")
	{
		uf_limpiarvariables();
	}
	if($ls_operacion == "GUARDAR")
	{
		if(array_key_exists("rbtipocat",$_POST))
		{
			$ls_rbtipocat=$_POST["rbtipocat"];
			switch ($ls_rbtipocat) 
			{
				case 'CSC':
					 $ls_rbtipocat="1";
					 $ls_rbcsc="checked";
				break;
				
				case 'CGR':
					$ls_rbtipocat="2";
					$ls_rbcgr="checked";
				break;
			}
		}
	    $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sistema="SAF"; 
		$ls_seccion="CATEGORIA"; 
		$ls_variable="TIPO-CATEGORIA-CSG-CGR"; 
		$ls_valor=$ls_rbtipocat; 
		$ls_tipo="C";
	    $lb_valido=$io_saf->uf_saf_guardar_configuracion($ls_codemp,$ls_sistema, $ls_seccion, $ls_variable, $ls_valor, $ls_tipo);
		if($lb_valido)
		{
		    $io_msg->message("Se guardo la configuracion con exito");  
		}
	}
?>
</p>
<p>&nbsp;</p>
<form id="form1" name="form1" method="post" action="">
          <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="500" border="0" align="center" class="formato-blanco">
    <tr>
      <td colspan="3" class="titulo-ventana"><div align="center">CONFIGURACION
        <input name="operacion" type="hidden" id="operacion" value="<?php  print $ls_operacion; ?>" />
      </div></td>
    </tr>
    <tr>
      <td width="142"><div align="right"></div></td>
      <td width="239">&nbsp;</td>
      <td width="103">&nbsp;</td>
    </tr>
	<?php 
	  $lb_valido=$io_saf->uf_select_config($ls_codemp);
	  if(!$lb_valido)
	  {
	?>
    <tr>
      <td><div align="right">Tipo de Categoria</div></td>
      <td><div align="left">
        <input name="rbtipocat" type="radio" class="sin-borde" value="CSC" <?php print $ls_rbcsc; ?>>
		Cat&aacute;logo SIGECOF
		<input name="rbtipocat" type="radio" class="sin-borde" value="CGR" <?php print $ls_rbcgr; ?>>
	  Categor&iacute;a CGR </div></td>
      <td>&nbsp;</td>
    </tr>
	<?php
	  }
	  else
	  {
	?>
    <tr>
      <td><div align="right">Tipo de Categoria</div></td>
      <td><div align="left">
        <input name="rbtipocat" type="radio" class="sin-borde" value="CSC" <?php print $ls_rbcsc; ?> disabled="disabled">
		 Cat&aacute;logo SIGECOF
		<input name="rbtipocat" type="radio" class="sin-borde" value="CGR" <?php print $ls_rbcgr; ?> disabled="disabled">
	     Categor&iacute;a CGR </div></td>
      <td>&nbsp;</td>
    </tr>
	<?php 
	  }
	?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
 
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
	if ((f.rbtipocat[0].checked)||(f.rbtipocat[1].checked))
	{
	 f.operacion.value ="GUARDAR";
	 f.action="tepuy_saf_d_configuracion.php";
	 f.submit();
	}
	else
	{
	 alert("Seleccione una de las opciones de Configuracion");
	} 
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}
</script>
</html>
