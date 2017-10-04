<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Bancos </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
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
include("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$ls_codemp=$arr["codemp"];

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
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Banco</td>";
print "<td>Denominación</td>";
print "<td>Cuenta</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.codban,a.nomban,b.ctaban,b.dencta,b.sc_cuenta 
			 FROM scb_banco a,scb_ctabanco b 
			 WHERE a.codemp='".$ls_codemp."' AND a.esttesnac=1 AND a.codban like '".$ls_codigo."' 
			 AND a.nomban like '".$ls_denominacion."' AND a.codemp=b.codemp AND a.codban=b.codban ORDER BY codban";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	if(($rs_cta===false))
	{
		$io_msg->message("Error en select");
	}
	else
	{
		if($row=$SQL->fetch_row($rs_cta))
		{
			$data=$SQL->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("codban");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$codigo=$data["codban"][$z];
				$denominacion=$data["nomban"][$z];
				$ctaban=$data["ctaban"][$z];
				$dencta=$data["dencta"][$z];
				$sc_cuenta=$data["sc_cuenta"][$z];
				print "<td><a href=\"javascript: aceptar('$codigo','$denominacion','$ctaban','$dencta','$sc_cuenta');\">".$codigo."</a></td>";
				print "<td>".$denominacion."</td>";
				print "<td>".$ctaban."</td>";
				print "<td>".$dencta."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No se han definido cuentas asociadas a banco de Tesoreria Nacional");
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
  function aceptar(codigo,deno,ctaban,dencta,sc_cuenta)
  {
    opener.document.form1.txtcodbansig.value=codigo;
    opener.document.form1.txtnombansig.value=deno;
	opener.document.form1.txtctatesoreria.value=ctaban;
    opener.document.form1.txtdenctatesoreria.value=dencta;
	opener.document.form1.txtcuenta_scg.value=sc_cuenta;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_bancos_tesoreria.php";
  f.submit();
  }
</script>
</html>
