<?php
session_start();
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];

$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];
if (array_key_exists("operacion",$_POST))		
   {
     $ls_operacion=$_POST["operacion"];  
   }
else
   {
     $ls_operacion="BUSCAR";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestaria</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
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
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria </td>
    </tr>
  </table>
  <div align="center">
    <br>
    <?php



print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
  {
	$ls_cadena =" SELECT * FROM spg_cuentas ".
		        " WHERE codemp = '".$ls_codemp."' AND spg_cuenta like '4%' ".
				" ORDER BY spg_cuenta";
	$rs_cta=$SQL->select($ls_cadena);
	$data=$rs_cta;
	if ($row=$SQL->fetch_row($rs_cta))
	   {
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("spg_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta=$data["spg_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			$codest1=$data["codestpro1"][$z];
			$codest2=$data["codestpro2"][$z];
			$codest3=$data["codestpro3"][$z];
			$scgcuenta=$data["sc_cuenta"][$z];
			$status=$data["status"][$z];
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td>".$cuenta."</td>";
				print "<td  align=left>".$codest1."</td>";
				print "<td  align=left>".$codest2."</td>";
				print "<td  align=left>".$codest3."</td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td><a href=\"javascript: aceptar('$cuenta');\">".$cuenta."</a></td>";
				print "<td  align=left>".$codest1."</td>";
				print "<td  align=left>".$codest2."</td>";
				print "<td  align=left>".$codest3."</td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
			}
			print "</tr>";			
		}
		$SQL->free_result($rs_cta);
		$SQL->close();
	}
	else
		{ ?>
			<script language="javascript">
			alert("No se han creado Cuentas de Gasto para la Estructura Programática seleccionada !!!");
		    </script>
  	    <?php
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

  function aceptar(cuenta)
  {
    fop    = opener.document.form1;
	objeto = fop.hidcompras.value;
	ls_cadena1 = fop.txtcuentabienes.value;
	ls_cadena2 = fop.txtcuentaservicios.value;
	if (objeto=='1')
	   {
	     ls_string = (ls_cadena1+cuenta);
		 fop.txtcuentabienes.value = ls_string;
	   }
	else
	   {
	     ls_string = (ls_cadena2+cuenta);
		 fop.txtcuentaservicios.value = ls_string;
	   }	
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cfg_cat_spgcuentas.php";
	  f.submit();
  }
</script>
</html>