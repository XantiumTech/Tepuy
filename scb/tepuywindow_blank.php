<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_menu.php'";
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
<title >Sistema de Bancos</title>
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
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
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
</table>
<?php
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("../shared/class_folder/tepuy_include.php");
	$in=new tepuy_include();
	$con=$in->uf_conectar();
	$funciones_db=new class_funciones_db($con);
	$lb_origen=$funciones_db->uf_select_column("scb_cmp_ret","origen");
	$lb_codintper=$funciones_db->uf_select_column("sss_registro_eventos","codintper");
	$lb_tabla_cartaorden=$funciones_db->uf_select_table("scb_cartaorden");
	if((!$lb_origen) || (!$lb_codintper) || (!$lb_tabla_cartaorden))
	{
		print "<script>";
		print "alert('Debe ejecutar el release');";
		print "location.href='../tepuy_menu.php'";
		print "</script>";
		
	}	
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
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/tepuy_release.php");
       $io_release= new tepuy_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_dt_movbco','ctabanbene');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		} 
       }
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_cartaorden','archrtf');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Release 3.04 ");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		} 
     }
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_movbco_fuefinanciamiento');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Release 3.10 ");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		} 
     }
     if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('rpc_beneficiario','tipcuebanben');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Release 3.25");
			 print "<script language=JavaScript>";
			 print "location.href='../tepuy_menu.php'";
			 print "</script>";		
		   }
      }
	
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_cheques','estins');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Release 3.26 ");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		} 
    }
    if($lb_valido)
	{
		switch($_SESSION["ls_gestor"])
	  	{
			case "MYSQL":
				$lb_valido=$io_release->io_function_db->uf_select_type_columna('scb_movbco_spi','desmov','longtext');	
			 break;
				   
			case "POSTGRE":
				$lb_valido=$io_release->io_function_db->uf_select_type_columna('scb_movbco_spi','desmov','text');
				   								
			break;  				  
	    }			
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_12");
			print "<script language=JavaScript>";
			print "location.href='../tepuy_menu.php'";
			print "</script>";		
		}		
	}
?>
</body>
</html>
