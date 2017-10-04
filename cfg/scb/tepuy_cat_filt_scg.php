<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion    = $_POST["operacion"];
	$ls_codigo       = $_POST["codigo"];
	$ls_denominacion = "%".$_POST["nombre"]."%";
	$ls_filtro       = $_POST["filtro"];
	//$ls_filtro_1   = $_POST["filtro_1"];
	//$ls_filtro_2   = $_POST["filtro_2"];
}
else
{
	$ls_operacion = "";
	$ls_filtro    = "";
	$ls_codigo    = "";
	//$ls_filtro_1  = $_GET["filtro_1"];
	//$ls_filtro_2  = $_GET["filtro_2"];
}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="122">Cuenta</td>
        <td width="238"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codigo;  ?>">        
        </div></td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
        <input name="filtro" type="hidden" id="filtro" value="<?php print $ls_filtro;?>">
        </td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena =" SELECT sc_cuenta, denominacion, status           ". 
				" FROM   scg_cuentas                               ".
		        " WHERE  codemp = '".$as_codemp."' AND             ".
				"        (sc_cuenta like '".$ls_filtro."%') AND    ".
				"        denominacion like '".$ls_denominacion."'  ".
				" ORDER BY sc_cuenta";
	$rs_cta=$SQL->select($ls_cadena);
	while($row=$SQL->fetch_row($rs_cta))
	{
		$cuenta=$row["sc_cuenta"];
		$denominacion=$row["denominacion"];
		$status=$row["status"];
		if($status=="S")
		{
			print "<tr class=celdas-blancas>";
			print "<td>".$cuenta."</td>";
			print "<td  align=left>".$denominacion."</td>";
		}
		else
		{
			print "<tr class=celdas-azules>";
			print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$status');\">".$cuenta."</a></td>";
			print "<td  align=left>".$denominacion."</td>";
		}
		print "</tr>";			
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

  function aceptar(cuenta,d,status)
  {
    opener.document.form1.txtcuentacontable.value=cuenta;
	opener.document.form1.txtdencuenta.value=d;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_filt_scg.php";
	  f.submit();
  }

</script>
</html>
