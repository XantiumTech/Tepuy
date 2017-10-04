<?php
	session_start();
ini_set('display_errors', 1);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_ruta)
   {
		$lista = array();
		$handle = opendir($as_ruta);
    	print "<table width='500' border='0' cellpadding='1' cellspacing='0' class='formato-blanco' align='center'>";
		print "<tr class='titulo-celdanew'>";
		print "<td width='360' height='22'><div align='center'>Archivos Generados</div></td>";
		print "<td width='70' height='22'><div align='center'>Descargar</div></td>";
		print "<td width='70' height='22'><div align='center'>Eliminar</div></td>";
		print "</tr>";
		while ($file = readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
				print "<tr>";
				print "<td width='360' height='22'><div align='left'>".$file."</div></td>";
				print "<td width='70' height='22'><div align='center'><a href='tepuy_siv_cat_descarga.php?enlace=".$as_ruta."&file=".$file."'><img src='../shared/imagebank/tools20/download.png' width='20' height='20' border='0'></a></div></td>";
				print "<td width='70' height='22'><div align='center'><a href=\"javascript: ue_delete('$as_ruta','$file');\"><img src='../shared/imagebank/tools20/eliminar.png' width='20' height='20' border='0'></a></div></td>";
				print "</tr>";
			 }
		}
    	print "</table>";
		closedir($handle);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Explorar Directorios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="formulario" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Explorar Archivos TXT </td>
    </tr>
  </table>
<br>
<?php
	require_once("class_funciones_inventario.php");
	$io_fun_siv=new class_funciones_inventario();
	
	$ls_ruta=$io_fun_siv->uf_obtenervalor_get("ruta","");
	$ls_file=$io_fun_siv->uf_obtenervalor_get("archivo","");
	//print "Archivo: ".$ls_file;
	if($ls_file!="")
	{
		@unlink($ls_ruta."/".$ls_file);
	}
	uf_print($ls_ruta);
	unset($io_fun_siv);
?>
<br>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_delete(ruta,file)
{
	//alert(ruta+file);
	if(confirm("¿Está completamente seguro de eliminar este archivo?"))
	{
		f=document.formulario;
		f.action="tepuy_siv_cat_directorio.php?ruta="+ruta+"&archivo="+file+"";
		f.submit();
	}
}
</script>
</html>
