<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Formas de pago</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Formas de Pago </td>
  </tr>
</table>
<div align="center">
<form name="form1" method="post" action="">
<?php
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	
	$io_in=new tepuy_include();
	$con=$io_in->uf_conectar();
	$io_ds=new class_datastore();
	$io_sql=new class_sql($con);
	$arr=$_SESSION["la_empresa"];
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
  	$ls_sql=" SELECT sfa_formapago.*, scg_cuentas.denominacion FROM sfa_formapago ".
		" INNER JOIN scg_cuentas ON sfa_formapago.sc_cuenta=scg_cuentas.sc_cuenta ".
		" WHERE sfa_formapago.codemp='".$ls_codemp."' ORDER BY sfa_formapago.codfor ASC";
	//print $ls_sql;
	$rs=$io_sql->select($ls_sql);
	$data=$rs;
	if ($row=$io_sql->fetch_row($rs))
	{
		 $data=$io_sql->obtener_datos($rs);
		 $arrcols=array_keys($data);
		 $totcol=count($arrcols);
		 $io_ds->data=$data;
		 $totrow=$io_ds->getRowCount("codfor");
		 print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		 print "<tr class=titulo-celda>";
		 print "<td>Código </td>";
		 print "<td>Denominación</td>";
		 print "<td>Cta. Contable</td>";
		 print "<td>Denominación de la Cta. ontable</td>";
		 print "</tr>";
		 for($z=1;$z<=$totrow;$z++)
			{
			  print "<tr class=celdas-blancas>";
			  $codigo=$data["codfor"][$z];
			  $denominacion=$data["denfor"][$z];
			  $forpag=$data["forpag"][$z];
			  $sc_cuenta=$data["sc_cuenta"][$z];
			  $sc_denominacion=$data["denominacion"][$z];
			  print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$forpag','$sc_cuenta','$sc_denominacion');\">".$codigo."</a></td>";
			  print "<td align=left>".$denominacion."</td>";
			  print "<td align=left>".$sc_cuenta."</td>";
			  print "<td align=left>".$sc_denominacion."</td>";
			  print "</tr>";			
		   }
		  $io_sql->free_result($rs);
		  print "</table>";
	}
	else
	{
		?>
		 <script language="javascript">
		 alert("No se han creado Formas de Pago !!!");
		// close();
		 </script> 
		<?php
	}
?>
  </form>
  <br>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,forpago,sc_cuenta,sc_denominacion)
  {
	fop= opener.document.form1;
	fop.hidestatus.value      = "GRABADO";	
	fop.txtcodigo.value       = codigo;
        fop.txtcodigo.readOnly    = true;
	fop.txtdenominacion.value = denominacion;
	fop.txtdenominacionabr.value = forpago;
	fop.txtcontable.value = sc_cuenta;
	fop.txtdencontable.value = sc_denominacion;
	close();
  }
</script>
</html>
