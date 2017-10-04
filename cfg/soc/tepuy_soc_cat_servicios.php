<?php
session_start();
$arremp=$_SESSION["la_empresa"];
$ls_codemp=$arremp["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Servicios</title>
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
<table width="500" height="22" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Servicios </td>
  </tr>
</table>
<br>
<form name="form1" method="post" action=""  >
  <div align="center">
    <?php
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_in   = new tepuy_include();
$con     = $io_in->uf_conectar();
$io_ds   = new class_datastore();
$io_sql  = new class_sql($con);
$arr     = $_SESSION["la_empresa"];
$ls_sql  = " SELECT a.*,b.dentipser, ".
           " (select denunimed from siv_unidadmedida where a.codunimed=siv_unidadmedida.codunimed)As denunimed,".
		   " (select unidad from siv_unidadmedida where a.codunimed=siv_unidadmedida.codunimed) As unidad".
		   " FROM soc_servicios a, soc_tiposervicio b ".
           "  WHERE  a.codemp='".$ls_codemp."' AND a.codtipser=b.codtipser   ".
		   "  ORDER BY a.codser ASC                                          "; 
$rs_data = $io_sql->select($ls_sql);
$data    = $rs_data;
if($row=$io_sql->fetch_row($rs_data))
  {
	$data        = $io_sql->obtener_datos($rs_data);
    $arrcols     = array_keys($data);
    $totcol      = count($arrcols);
    $io_ds->data = $data;
    $totrow      = $io_ds->getRowCount("codser");
    print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>"; 
    print "<tr class=titulo-celda>"; 
    print "<td>C&oacute;digo </td>"; 
    print "<td>Denominaci&oacute;n</td>";
    print "</tr>";
    for($z=1;$z<=$totrow;$z++)
       {
	     print "<tr class=celdas-blancas>";
		 $ls_codigo       = $data["codser"][$z];
		 $ls_codtipser    = $data["codtipser"][$z];
		 $ls_dentipser    = $data["dentipser"][$z];
		 $ls_denominacion = $data["denser"][$z];
		 $ld_precio       = $data["preser"][$z];    
		 $ld_precio       = number_format($ld_precio,2,',','.');
		 $ls_spgcuenta    = $data["spg_cuenta"][$z]; 
		 $ls_codunimed   = $data["codunimed"][$z];
		 $ls_denunimed  = $data["denunimed"][$z]; 
		 print "<td  align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_codtipser','$ls_dentipser','$ls_denominacion','$ld_precio','$ls_spgcuenta','$ls_codunimed','$ls_denunimed');\">".$ls_codigo."</a></td>";
		 print "<td  align=left>".$ls_denominacion."</td>";
		 print "</tr>";			
	   }
$io_sql->free_result($rs_data);
print "</table>";
}
else
{
?>
 <script language="javascript">
 alert("No se han creado Servicios !!!");
 close();
 </script> 
<?php
}
?>
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,ls_codtipser,ls_dentipser,denominacion,precio,spg_cuenta,codunimed,denunimed)
  {
    fop                       = opener.document.form1;
	fop.txtcodigo.value       = codigo;
    fop.txtcodtipser.value    = ls_codtipser;
	fop.txtdentipser.value    = ls_dentipser;
	fop.readOnly              = true;
	fop.txtdenominacion.value = denominacion;
    fop.txtprecio.value       = precio;
    fop.txtcuenta.value       = spg_cuenta;
	fop.txtunimed.value       = codunimed;
	fop.txtdenunimed.value       = denunimed;
	fop.operacion.value       = "CARGAR";
	fop.hidestatus.value      = "GRABADO";
	fop.submit(); 
	close();
  }
</script>
</html>