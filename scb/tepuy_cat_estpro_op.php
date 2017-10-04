<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
include("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);

$ls_codemp=$arr["codemp"];
$ls_estmodest=$arr["estmodest"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	//$ls_denestprog2=$_POST["denestpro2"];
	$ls_codigo="%".$_POST["txtcodestprog3"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	$ls_coduniadm=$_POST["coduniadm"];
	$ls_estuniadm=$_POST["estuniadm"];

}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
	$ls_coduniadm=$_GET["coduniadm"];
	$ls_estuniadm=$_GET["estuniadm"];

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?></title>
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
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["nomestpro3"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118"><div align="right">Codigo</div></td>
        <td width="380"><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="22" maxlength="3" style="text-align:center"></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Unidad Ejecutora</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if($ls_estuniadm=='C')
	{
	$ls_sql="SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.coduac as coduniadm
				FROM spg_ep1 a,spg_ministerio_ua b
				WHERE a.codemp=b.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1 like '".$ls_codigo."'
				AND a.denestpro1 like '".$ls_denominacion."'";
	}
	else
	{
		$ls_sql="SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.coduac as coduniadm
				FROM spg_ep1 a,spg_ministerio_ua b
				WHERE a.codemp=b.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1 like '".$ls_codigo."'
				AND a.denestpro1 like '".$ls_denominacion."' AND b.coduac='".$ls_coduniadm."'";
	}
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;

	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codestpro1");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codestprog1=$data["codestpro1"][$z];
			$denestprog1=$data["denestpro1"][$z];
			$coduniadm=$data["coduniadm"][$z];
			if($ls_estmodest==2)
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('".substr($codestprog1,-2)."','$denestprog1');\">".substr(trim($codestprog1),-2)."</td>";
				print "<td width=130 align=\"left\">".trim($denestprog1)."</td>";
				print "<td width=130 align=\"left\">".$coduniadm."</td>";
			}
			else
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codestprog1','$denestprog1');\">".trim($codestprog1)."</td>";
				print "<td width=130 align=\"left\">".trim($denestprog1)."</td>";
				print "<td width=130 align=\"left\">".$coduniadm."</td>";
			}
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

  function aceptar(codestprog1,denestprog1)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_estpro_op.php";
	  f.submit();
  }
</script>
</html>
