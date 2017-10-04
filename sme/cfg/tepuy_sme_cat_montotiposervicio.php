<?php
session_start();
//ini_set('display_errors', 1);
require_once("../class_folder/class_funciones_sme.php");
$io_serviciomedico=new class_funciones_sme();
require_once("../../shared/class_folder/tepuy_include.php");
$in1=    new tepuy_include();
$conn= $in1->uf_conectar();
require_once("tepuy_sme_c_tipo_servicio.php");
$io_serviciomedico1  = new tepuy_sme_c_tipo_servicio($conn); // Servicios Médicos
if(array_key_exists("hiddestino",$_POST))
{
	$ls_destino=$io_serviciomedico->uf_obtenervalor("hiddestino","");
}
else
{
	$ls_destino=$io_serviciomedico->uf_obtenervalor_get("destino","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tarifas de Servicios M&eacute;dicos </title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino; ?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="15" colspan="2" class="titulo-celda">Cat&aacute;logo de Tarifas de Servicios M&eacute;dicos </td>
    </tr>
  </table>
<br>
    <table width="501" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="131" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="368" height="22"><div align="left">
          <input name="txtcodtra" type="text" id="txtcodtra">
        </div>          <div align="right"></div>          <div align="right">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdentra" type="text" id="txtdentra">
</div></td>
      </tr>
<!--      <tr>
        <td><div align="right">Tipo</div></td>
        <td height="22"><select name="cmbcodtiptra" id="cmbcodtiptra">
          <option value="%%">-- Seleccione --</option>
          <option value="01">A&eacute;reo</option>
          <option value="02">Mar&iacute;timo</option>
          <option value="03">Terrestre</option>
        </select></td>
      </tr> -->
  <tr class="formato-blanco">
         <td width="66" height="22"><div align="right">Tipo de Servicio Médico
          </div></td>
          <td width="113"><div align="left">
	<?php $io_serviciomedico1->uf_cmb_tiposervicio($ls_tiptar); //Combo que contiene los tipo de servicio y retorna la selecciona ?>
 <!--   <td height="22"><select name="cmbcodtiptar" id="cmbcodtiptar">
      <option value="--" <?php print $ls_selected?>>-- Seleccione --</option>
      <option value="01" <?php print $ls_selectedaer?>>A&eacute;reo</option>
      <option value="02" <?php print $ls_selectedmar?>>Mar&iacute;timo</option>
      <option value="03" <?php print $ls_selectedter?>>Terrestre</option>
    </select></td> -->
          </div></td>
  </tr>
  <tr class="formato-blanco">
         <td width="66" height="22"><div align="right">Tipo de Nómina
          </div></td>
          <td width="113"><div align="left">
	<?php $io_serviciomedico1->uf_cmb_nomina($ls_nomina); //Combo que contiene tipos de nomina y retorna la selecciona ?>
          </div></td>
  </tr>
      <tr>
        <td>&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../../shared/class_folder/tepuy_include.php");
$in=     new tepuy_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
require_once("../../shared/class_folder/class_datastore.php");
$ds=     new class_datastore();
require_once("../../shared/class_folder/class_sql.php");
$io_sql= new class_sql($con);
require_once("../../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codtar="%".$_POST["txtcodtar"]."%";
	$ls_dentar="%".$_POST["txtdentar"]."%";
	$ls_codtiptar=$_POST["cmbcodtiptar"];
	$ls_nomina=$_POST["cmbnomina"];
	if($ls_codtiptar=="00"){$ls_codtiptar="%%";}
	if($ls_nomina=="00"){$ls_nomina="%%";}
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codtar="%%";
	$ls_codtiptar="%%";
	$ls_nomina="%%";
	$ls_dentar="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width= '40'>Código </td>";
print "<td width= '110'>Servicio Médico</td>";
print "<td width= '150'>Tipo de Nómina</td>";
print "<td width= '90'>Monto Trabajador</td>";
print "<td width= '90'>Monto familiar</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT T1.codtar,T1.codtipservicio,T1.codnom,T1.montotrabajador,T1.montofamiliar,T1.observacion,T1.spg_cuenta,T1.codestpro,".
			"T2.dentipservicio,T3.desnom, T4.codtipsol,T4.dentipsol ".
			" FROM sme_montoseguntipo as T1, sme_tiposervicio as T2, sno_nomina as T3, sep_tiposolicitud as T4 ".
			" WHERE T1.codtar LIKE '".$ls_codtar."'".
			"   AND T1.codtipservicio LIKE '".$ls_codtiptar."'".
			"   AND T1.codnom LIKE '".$ls_nomina."'".
			"   AND T1.codtipservicio=T2.codtipservicio AND T1.codnom=T3.codnom".
			"   AND T1.codtipsol=T4.codtipsol ORDER BY T1.codtar";
	//print $ls_sql;
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codtar");
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codtar= $data["codtar"][$z];
			$ls_codtiptar= trim($data["codtipservicio"][$z]);
			$ls_codnomina= trim($data["codnom"][$z]);
			$ls_spgcuenta= trim($data["spg_cuenta"][$z]);
			$ls_codestpro= trim($data["codestpro"][$z]);
			$ls_dentra= $data["dentipservicio"][$z];
			$ls_obser= $data["observacion"][$z];
			$ls_nomina= $data["desnom"][$z];
			$ls_codtipsol= $data["codtipsol"][$z];
			$ls_conceptopago= $data["dentipsol"][$z];
			$li_tartra= $data["montotrabajador"][$z];
			$li_tartra=number_format($li_tartra,2,',','.');
			$li_tarfam= $data["montofamiliar"][$z];
			$li_tarfam=number_format($li_tarfam,2,',','.');
			switch($ls_destino)
			{
				case"SOLICITUD":
					print " <td align='center'><a href=\"javascript: aceptar('$ls_codtar','$ls_dentra','$ls_nomina');\">".$ls_codtar."</a></td>";
					print "<td>".$ls_dentra."</td>";
					print "<td>".$ls_nomina."</td>";
					print "</tr>";			
				break;
				case"DEFINICION":
					print " <td align='center'><a href=\"javascript: aceptar_definicion('$ls_codtar','$ls_codtiptar','$ls_codnomina','$li_tartra','$li_tarfam','$ls_obser','$ls_spgcuenta','$ls_codestpro','$ls_codtipsol');\">".$ls_codtar."</a></td>";
					print "<td>".$ls_dentra."</td>";
					print "<td>".$ls_nomina."</td>";
					print "<td>".$li_tartra."</td>";
					print "<td>".$li_tarfam."</td>";
					print "</tr>";			
				break;
			}
		}
	}
	else
	{
		$io_msg->message("No hay registros.");
	}

}
print "</table>";

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">   
	function aceptar(ls_codtra,ls_codtiptra,ls_dentra)
	{
		opener.document.form1.txtcodasi.value=ls_codtra;
		opener.document.form1.txtdenasi.value=ls_dentra;
		opener.document.form1.txtproasi.value= "TRP";
		close();
	}

	function aceptar_definicion(ls_codtar,ls_codtiptar,ls_codnomina,li_tartra,li_tarfam,ls_obser,ls_presupuestaria,ls_codestpro,ls_codtipsol)
	{
		opener.document.form1.txtcodtar.value=ls_codtar;
		opener.document.form1.txtdentra.value=ls_obser;
		opener.document.form1.cmbcodtiptar.value=ls_codtiptar;
		opener.document.form1.cmbnomina.value=ls_codnomina;
		opener.document.form1.cmbcodtipsol.value=ls_codtipsol;
		opener.document.form1.txttartra.value= li_tartra;
		opener.document.form1.txttarfam.value= li_tarfam;
		opener.document.form1.txtpresupuestaria.value= ls_presupuestaria;
		opener.document.form1.txtcodestpro.value= ls_codestpro;
		opener.document.form1.txttartra.readonly=true;
		opener.document.form1.hidstatus.value="C";
		close();
	}

	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_sme_cat_montotiposervicio.php";
		f.submit();
	}
  

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
