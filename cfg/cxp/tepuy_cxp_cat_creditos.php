<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat�logo de Otros Cr�ditos</title>
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
<table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Otros Cr�ditos</td>
    </tr>
</table>
<div align="center"><br>
    <?php
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_conect     = new tepuy_include();
$con           = $io_conect->uf_conectar();
$io_dsotroscre = new class_datastore();
$io_sql        = new class_sql($con);
$arr           = $_SESSION["la_empresa"];
$ls_codemp     = $arr["codemp"];
$li_estmodest  = $arr["estmodest"];
$ls_confiva    = $_GET["confiva"];
$ls_sql        = "SELECT * FROM tepuy_cargos WHERE codemp='".$ls_codemp."' ORDER BY codcar ASC";
$rs_otroscre   = $io_sql->select($ls_sql);
$data          = $rs_otroscre;

if ($row=$io_sql->fetch_row($rs_otroscre))
   {
     $data                = $io_sql->obtener_datos($rs_otroscre);
	 $arrcols             = array_keys($data);
     $totcol              = count($arrcols);
     $io_dsotroscre->data = $data;
     $totrow              = $io_dsotroscre->getRowCount("codcar");
	 print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td width=40>C�digo</td>";
	 print "<td width=350>Denominaci�n</td>";
	 if($ls_confiva=='P')
	 {
		 print "<td width=200>Program�tica</td>";
	 }	 
	 print "<td width=50>Porcentaje</td>";
	 print "<td width=250>F�rmula</td>";
	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
		 {
			print "<tr class=celdas-blancas>";
			$codigo       = $data["codcar"][$z];
			$denominacion = $data["dencar"][$z];
			$codestpro    = $data["codestpro"][$z];
			if ($li_estmodest=='1')
			   {
			     $codestpro = substr($codestpro,0,20).'-'.substr($codestpro,20,6).'-'.substr($codestpro,26,3);    
			   }
			else
			   {
			     $codestpro = substr($codestpro,18,2).'-'.substr($codestpro,24,2).'-'.substr($codestpro,27,2).'-'.substr($codestpro,29,2).'-'.substr($codestpro,31,2);    
			   }
			$porcentaje   = $data["porcar"][$z];
			$formula      = $data["formula"][$z];
  		    $estlibcom    = $data["estlibcom"][$z];
			$spg_cuenta   = $data["spg_cuenta"][$z];
			print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion','$codestpro','$porcentaje','$formula','$estlibcom','$spg_cuenta','$ls_confiva');\">".$codigo."</a></td>";
			print "<td style=text-align:left>".$denominacion."</td>";
			if($ls_confiva=='P')
			{
				print "<td style=text-align:center>".$codestpro."</td>";
			}	
			print "<td style=text-align:right>".$porcentaje."</td>";
			print "<td style=text-align:right>".$formula."</td>";
			print "</tr>";			
		}
print "</table>";
$io_sql->free_result($rs_otroscre);
}
else
   {
   ?>
    <script language="javascript">
	alert("No se han creado Otros Cr�ditos !!!");
	close();
	</script>
  <?php
   }
?>
   <input name="confiva" type="hidden" id="confiva"  value="<?php print $ls_confiva?>">
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,programatica,porcentaje,formula,estatus,spg_cuenta,ls_confiva)
  {
    opener.document.form1.txtcodigo.value        = codigo;
    opener.document.form1.txtcodigo.readOnly     = true;
	opener.document.form1.txtdenominacion.value  = denominacion;
	if(ls_confiva=='P')
	{
		opener.document.form1.txtcodestpro.value = programatica;
	}
	opener.document.form1.txtporcentaje.value    = porcentaje;
	opener.document.form1.txtformula.value       = formula;
	opener.document.form1.txtpresupuestaria.value= spg_cuenta;
	opener.document.form1.hidestatus.value       ='GRABADO';
	opener.document.form1.chklibcompras.value    = estatus;
    if (estatus==1)
	   {
	     opener.document.form1.chklibcompras.checked = true;
	   }
	else
	   {
	     opener.document.form1.chklibcompras.checked = false;
	   }   
	close();
  }
</script>
</html>