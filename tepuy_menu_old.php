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
<title>M&oacute;dulos del Sistemas Administrativos Tepuy, DATA: <?php print $_SESSION["ls_database"] ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
<!--  <tr>
    <img src="shared/imagebank/alcalde_fondo.png" width="850" height="400">
  </tr> -->
<?php
	$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
	if($ls_rifemp=="G-200000285-9")
	{
		echo '<img src="shared/imagebank/alcalde_fondo.png" align="right">';
	}
	else
	{
		echo '<img src="shared/imagebank/fondo_tepuy.png" align="right">';
	}
?>
    <td class="toolbar" width="280"><div align="right"><a href="javascript: ue_cerrar();"><img src="shared/imagebank/iconos/exit.png" alt="Salir" width="48" height="48" border="0" title="Salir"></a></div></td>
<!--    <td class="toolbar" width="280"><div align="right"><a href="javascript: ue_abrir_help();"><img src="shared/imagebank/iconos/ayuda.png" alt="Ayuda" width="48" height="48" border="0" title="Ayuda"></a></div></td> -->
</table>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->

<?php
	require_once("shared/class_folder/tepuy_include.php");
	$io_in=new tepuy_include();
	$con=$io_in->uf_conectar();
	$ls_buscarusuario="SELECT usuario.tipusu,tipo.menu FROM sss_usuarios usuario, sss_tipousuario tipo WHERE usuario.codusu='".
			  $_SESSION["la_logusr"]."' AND usuario.tipusu=tipo.codusu"."";
	//print $ls_buscarusuario;
	$resultado=mysql_query($ls_buscarusuario);
	while($fila=mysql_fetch_row($resultado))
	{
		//print $fila['0'].$fila['1'];
		$ls_tipousuario=$fila['0'];
		//$menues='"'.$fila['1'].'"';
		$menues="'".$fila['1']."'";
		//print $ls_tipousuario;
		//print $menues;
	}
	mysql_free_result(); 
	//$ls_tipousuario=$_SESSION["la_tipusu"];
	//print $ls_buscarusuario;
	//print $_SESSION["la_logusr"];
	//print "sesion".$_SESSION["la_tipusu"];
	//print $ls_tipousuario;
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src='.$menues.' type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>';

/*	if($ls_tipousuario=="1")
	{
	$menues="menu.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>';

	}
	
	if($ls_tipousuario=="2")
	{
	$menues="menu_presupuesto.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_presupuesto.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}
	if($ls_tipousuario=="3")
	{
	$menues="menu_compras.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_compras.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>';

	}
	
	if($ls_tipousuario=="4")
	{
	$menues="menu_rrhh.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_rrhh.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}
	if($ls_tipousuario=="5")
	{
	$menues="menu_contabilidad.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_contabilidad.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>';

	}
	if($ls_tipousuario=="6")
	{
	$menues="menu_banco.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_banco.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}
	if($ls_tipousuario=="7")
	{
	$menues="menu_bienes.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_banco.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}
	if($ls_tipousuario=="8")
	{
	$menues="menu_ayudas.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu_ayudas.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}
/*	if($ls_tipousuario=="")
	{
	$menues="menu.js";
	echo '<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>'; 

	}*/


?>
<!--
<script type="text/javascript">MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script> 
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->


<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

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
