<?php
session_start();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ls_codemp=$dat["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Administrativas</title>
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
</p>
  	 <table width="564" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="560" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administrativas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php

print "<table width=564 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Unidad Central</td>";
print "<td>Responsable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT coduac,denuac,tipuac,resuac
			 FROM   spg_ministerio_ua
			 WHERE  codemp='".$ls_codemp."' AND coduac like '".$ls_codigo."' AND denuac like '".$ls_denominacion."'" ;

	$rs_unidad=$SQL->select($ls_sql);
	$data=$rs_unidad;
	if($row=$SQL->fetch_row($rs_unidad))
	{
		$data=$SQL->obtener_datos($rs_unidad);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("coduac");
		$SQL->free_result($rs_unidad);
		$SQL->close();
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["coduac"][$z];
			$denominacion=$data["denuac"][$z];
			$estuac=$data["tipuac"][$z];
			$resuac=$data["resuac"][$z];
			print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$estuac','$resuac');\">".$codigo."</a></td>";
			print "<td>".$denominacion."</td>";
			print "<td align=center>".$estuac."</td>";
			print "<td align=center>".$resuac."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido unidades administradoras");		
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
  function aceptar(codigo,deno,estuac,resuac)
  {
    opener.document.form1.txtcoduniadm.value=codigo;
    opener.document.form1.txtdenuniadm.value=deno;
	opener.document.form1.txtestuac.value=estuac;
	opener.document.form1.txtresuac.value=resuac;
	close();
  }
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_spg_cat_unidad.php";
	f.submit();
}

</script>
</html>
