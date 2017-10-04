<?php
session_start();
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
$ls_campo=$_GET["campo"];
//print "campo=".$ls_campo; // CAMPO 1 ES IGUAL A UNIDAD A DESPACHAR 2 ES UNIDAD SOLICITANTE
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Administrativas </title>
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="txtsubmit" type="hidden" id="txtsubmit" value="<?php print $ls_submit ?>">
  </p>
  	 <table width="535" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="531" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administrativas  </td>
    	</tr>
  </table>
	 <br>
	 <table width="535" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="422" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td height="13"><div align="right"></div></td>
        <td><div align="left">
        </div>
       </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php

print "<table width=564 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>C�digo </td>";
print "<td>Denominaci�n</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM spg_unidadadministrativa".
			" WHERE codemp='".$ls_codemp."'".
			"   AND coduniadm like '".$ls_codigo."'".
			"   AND denuniadm like '".$ls_denominacion."'";
	$rs_unidad=$io_sql->select($ls_sql);
	$data=$rs_unidad;
	if($row=$io_sql->fetch_row($rs_unidad))
	{
		$data=$io_sql->obtener_datos($rs_unidad);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("coduniadm");
		$io_sql->free_result($rs_unidad);
		$io_sql->close();
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["coduniadm"][$z];
			$ls_denominacion=$data["denuniadm"][$z];
			if ($ls_campo==1)
			{
				print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
			}
			else
			{
			    print "<td align=center><a href=\"javascript: aceptar_adm('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
			}
			print "<td>".$ls_denominacion."</td>";
			print "</tr>";			
		}
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codigo,ls_denominacion)
	{
		opener.document.form1.txtcodunides.value=ls_codigo;
		opener.document.form1.txtdenunides.value=ls_denominacion;
		close();
	}
	function aceptar_adm(ls_codigo,ls_denominacion)
	{
		opener.document.form1.txtcoduniadm.value=ls_codigo;
		opener.document.form1.txtdenuniadm.value=ls_denominacion;
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_siv_cat_unidad.php";
		f.submit();
	}
</script>
</html>
