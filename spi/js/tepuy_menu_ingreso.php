<?php 
ini_set('precision ','20');
session_start(); 
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_logusr",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_tipocontabilidad=$_SESSION["la_empresa"]["esttipcont"];
?>
<html>
<head>
<title>M&oacute;dulos Sistemas Administrativos Tepuy , <?php print $_SESSION["ls_database"] ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

<img src="shared/imagebank/alcalde_fondo.png" align="right">

<?php 
	if($ls_tipocontabilidad=="1")
	{
	$menu="menu.js";
	}
?>
</body>
</html>
<script language="javascript">
function uf_abrir_help()
{
	window.open("hlp/index.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");	
}
function ue_cerrar()
{
	location.href = "tepuy_conexion.php";
}

</script>
