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
<title >Registro de Proveedores y Contratistas</title>
<meta http-equiv="imagetoolbar" content="no">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
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
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<?php
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="ls_gestor")&&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")
		&&($col!="la_ususeg")&&($col!="la_sistema")&&($col!="ls_port")&&($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="la_apeusu")&&($col!="la_nomusu")&&($col!="la_cedusu"))
		{
			unset($_SESSION["$col"]);
		}
	}
	
	// validaci�n de los release necesarios para que funcione el sistema de Registro de Proveedores y Contratistas.
	require_once("../shared/class_folder/tepuy_release.php");
    $io_release= new tepuy_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('rpc_beneficiario','tipconben');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }
     	$lb_valido=$io_release->io_function_db->uf_select_column('rpc_proveedor','tipconpro');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_table('rpc_espexprov');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.41 ");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }
		$lb_valido=$io_release->io_function_db->uf_select_column('rpc_beneficiario','tipcuebanben');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Release 3.25");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }

	}
?>

</body>
<script language="javascript">
function ue_cerrar()
{
	window.open("tepuywindow_blank.php","Blank","_self");
}
</script> 
</html>
