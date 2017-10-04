<?php
session_start();
if(array_key_exists("tipo",$_GET))
{
	$ls_tipcau=$_GET["tipo"];
}
else
{
	$ls_tipcau=$_POST["hidtipcau"];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Causas de Movimientos</title>
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
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Causas de Movimientos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
          <input name="hidtipcau" type="hidden" id="hidtipcau" value="<?php print $ls_tipcau; ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenominacion" type="text" id="txtdenominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
    <div align="center"><br>
<?php
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
require_once("tepuy_saf_c_activo.php");
$io_saf = new tepuy_saf_c_activo();
	
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_denominacion="%".$_POST["txtdenominacion"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
    $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$li_estcat=$io_saf->uf_select_valor_config($ls_codemp);
	$ls_sql="SELECT * FROM saf_causas ".
			" WHERE codcau like '".$ls_codigo."' ".
			"   AND dencau like '".$ls_denominacion."' ".
			"   AND tipcau= '".$ls_tipcau."' ".
			"   AND estcat='".$li_estcat."' ".
			" ORDER BY codcau ";
    $rs_cta=$io_sql->select($ls_sql);
	$li_numrows=$io_sql->num_rows($rs_cta);
	if($li_numrows>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$row["codcau"];
			$ls_denominacion=$row["dencau"];
			$ls_tipo=$row["tipcau"];
			$ls_contable=$row["estafecon"];
			$ls_presupuestaria=$row["estafepre"];
			$ls_explicacion=$row["expcau"];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_tipo','$ls_contable','$ls_presupuestaria','$ls_explicacion');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_denominacion."</td>";
			print "</tr>";			
		}
	}
}
print "</table>";
?>
      </div>
    </div>
</form>
<p align="center">&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,denominacion,tipo,contable,presup,explicacion)
{
	opener.document.form1.txtcodcau.value=codigo;
	opener.document.form1.txtdencau.value=denominacion;
	close();
}
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_saf_cat_causasmovimiento.php";
	f.submit();
}
</script>
</html>