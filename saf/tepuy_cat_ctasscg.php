<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
        <td width="238" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre">
<label></label>
<br>
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
<?php
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";

	
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Contable</td>";
print "<td>Denominaci�n</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql = " SELECT sc_cuenta, denominacion, status, asignado, distribuir," . 
			  "        enero, febrero, marzo, abril, mayo, junio, julio, agosto, ".
			  "        septiembre, octubre, noviembre, diciembre,".
			  "        nivel, referencia ".
			  " FROM   scg_cuentas ".
			  " WHERE  codemp = '".$as_codemp."' AND ".
			  "        sc_cuenta like '".$ls_codigo."' AND ".
			  "        denominacion like '".$ls_denominacion."' ".
			  " ORDER BY sc_cuenta";
	$rs_cta=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($rs_cta);
	if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
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
	else
	{
		$io_msg->message("No se han creado Cuentas Contables");
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

  function aceptar(cuenta,denominacion,status)
  {
    opener.document.form1.txtctacon.value=cuenta;
	opener.document.form1.txtdenctacon.value=denominacion;
	//opener.document.form1.txtstatus.value=status;
	//opener.buscar();
	 close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_ctasscg.php";
	  f.submit();
  }

</script>
</html>