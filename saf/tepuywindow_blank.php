<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_menu.php'";
	print "</script>";		
}
require_once("tepuy_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new tepuy_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Sistema de Activos Fijos</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
	// validación de los release necesarios poara que funcione el sistema de nómina
	require_once("../shared/class_folder/tepuy_release.php");
    $io_release= new tepuy_release();
	$lb_valido=true;
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
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','codestpro1');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','codrespri');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','codresuso');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','coduniadm');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','tiprespri');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','tipresuso');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','fecentact');	
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
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dt_movimiento','estcat');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.33");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
    $lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_item');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.35");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		} 
    }
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','codite');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.36");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_cambioresponsable','codact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.37");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dta','estactpre');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.59");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	} 
    $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dta','codunipre');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.60");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_prestamo');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.57");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}  
    $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_prestamo');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.57");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_autsalida');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.58");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_autsalida');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.58");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_entrega');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.87");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_entrega');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.88");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	} 
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','ubigeoact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_3_35");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','tipinm');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_4_38");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_edificios');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_4_39");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_tipoestructura');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_4_40");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_componente');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_4_41");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_edificiotipest');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 2008_4_42");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}
	}
?>
</body>
</html>
