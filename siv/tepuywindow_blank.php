<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_menu.php'";
	print "</script>";		
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
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<title >Sistema de Inventario</title>
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
<link href="css/siv.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Inventario</td>
    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    <td>  
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="2" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> 
  </tr> -->
</table>
<?php
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_port")&&($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="ls_gestor")
		   &&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")&&($col!="la_ususeg")&&($col!="la_sistema")
		   &&($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="la_apeusu")&&($col!="la_nomusu")&&($col!="la_cedusu"))
		{
			unset($_SESSION["$col"]);
		}
	}

	// validaci�n de los release necesarios poara que funcione el sistema de n�mina
	require_once("../shared/class_folder/tepuy_release.php");
    $io_release= new tepuy_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_despacho','codunides');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('tepuy_catalogo_milco');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_articulo','codmil');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_despacho','nomproy');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3_86");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_articulo','estact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 4_14");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_articulo','codact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 4_17");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_dt_recepcion','estregact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 4_18");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
/*	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
*/	
    
?>
</body>
</html>
