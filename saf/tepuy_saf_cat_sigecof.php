<?
session_start();
require_once("class_funciones_activos.php");
$io_fact= new class_funciones_activos("../");
if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
	$ls_dendestino=$_POST["dendestino"];
}
else
{
	$ls_coddestino=$io_fact->uf_obtenervalor_get("coddestino","txtcatalogo");
	$ls_dendestino=$io_fact->uf_obtenervalor_get("dendestino","txtdencat");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo SIGECOF</title>
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
    <input name="dendestino" type="hidden" id="dendestino" value="<?php print $ls_dendestino ?>">
    <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo  SIGECOF </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcatalogo" type="text" id="txtnombre2">
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
  <br>
    <?
	require_once("../shared/class_folder/tepuy_include.php");
	$in=new tepuy_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_datastore.php");
	$ds=new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	$arr=$_SESSION["la_empresa"];
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_catalogo="%".$_POST["txtcatalogo"]."%";
		$ls_denominacion="%".$_POST["txtdenominacion"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='80' align='center'>Código</td>";
print "<td>Denominación</td>";
print "<td  width='80' align='center'>Cta. Presupuestaria</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM saf_catalogo".
			" WHERE catalogo like '".$ls_catalogo."'".
			" AND dencat like '".$ls_denominacion."'";
	//		" AND spg_cuenta like '404%' ";
    $rs_cta=$io_sql->select($ls_sql);
	if($row=$io_sql->fetch_row($rs_cta))
	{
	    while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_catalogo=$row["catalogo"];
			$ls_denominacion=$row["dencat"];
			$ls_cuenta=$row["spg_cuenta"];
			print "<td align='center'><a href=\"javascript: aceptar('$ls_catalogo','$ls_denominacion','$ls_cuenta','$ls_status',".
				  "'$ls_coddestino','$ls_dendestino');\">".$ls_catalogo."</a></td>";
			print "<td>".$row["dencat"]."</td>";
			print "<td align='center'>".$row["spg_cuenta"]."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se encontraron Registros");
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
	function aceptar(ls_codigo,ls_denominacion,ls_cuenta,ls_status,ls_coddestino,ls_dendestino)
	{
		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=ls_codigo;
		obj=eval("opener.document.form1."+ls_dendestino+"");
		obj.value=ls_denominacion;
		opener.document.form1.txtcuenta.value=ls_cuenta;
		opener.document.form1.hidstatus.value="C";
		close();
	}

	function ue_search()
	{
		f=document.form1;
		ls_codigo=f.txtcatalogo.value;
		ls_denominacion=f.txtdenominacion.value;
		f.operacion.value="BUSCAR";
		f.action="tepuy_saf_cat_sigecof.php";
		f.submit();
	}
</script>
</html>