<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de Servicios M&eacute;dicos</title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipos de Servcios M&eacute;dicos</td>
  </tr>
</table>
<div align="center"><br>
<?php
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
$ls_menu =$_GET['menu'];
$variable=$_GET['tipo'];
$io_in=new tepuy_include();
$con=$io_in->uf_conectar();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * ".
        " FROM sme_tiposervicio ".
		" ORDER BY codtipservicio ASC ";
$rs=$io_sql->select($ls_sql);
if ($row=$io_sql->fetch_row($rs))
{
     $data=$rs;
	 $data=$io_sql->obtener_datos($rs);
     $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_ds->data=$data;
     $totrow=$io_ds->getRowCount("codtipservicio");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código </td>";
	 print "<td>Denominación</td>";
	 print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 { 
		$ls_estatus="";
		print "<tr class=celdas-blancas>";
		$codigo       = $data["codtipservicio"][$z];
		$denominacion = $data["dentipservicio"][$z];
		if($ls_menu=="ACEPTARDESDE")
		{
			print "<td align=center><a href=\"javascript: ue_aceptar_reportedesde('$codigo','$denominacion');\">".$codigo."</a></td>";
		}
		else
		{
			if($ls_menu=="ACEPTARHASTA")
			{
				print "<td align=center><a href=\"javascript: ue_aceptar_reportehasta('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
			else
			{
				print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
		}
		print "<td align=left>".$denominacion."</td>";
		print "</tr>";			
	}
	$io_sql->free_result($rs);
	print "</table>";
}
else
{ 
    ?>
	<script language="javascript">
	alert("No se han creado Tipos de Servicios Médicos!!!");
	close();
	</script>
	<?php
}	
?>
</div>
</body>
<script language="JavaScript">
function ue_aceptar_reportedesde(ls_numsol)
{
	opener.document.formulario.txttipoayudades.value=ls_numsol;
	close();
}

function ue_aceptar_reportehasta(ls_numsol)
{
	opener.document.formulario.txttipoayudahas.value=ls_numsol;
	close();
}
  function aceptar(codigo,denominacion)
  {
     opener.document.form1.txtcodigo.value=codigo;
     opener.document.form1.txtdenominacion.value=denominacion;
	 
 	 opener.document.form1.hidestatus.value="GRABADO";
	 close();
  }
</script>
</html>
