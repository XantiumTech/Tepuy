<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Empresas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"   rel="stylesheet" type="text/css">
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
<?php
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$in           = new tepuy_include();
$conn         = $in->uf_conectar();
$io_dsempresa = new class_datastore();
$io_sql       = new class_sql($conn);
$io_sql2      = new class_sql($conn);
$arr          = $_SESSION["la_empresa"];
$ls_sql       = " SELECT * FROM tepuy_empresa ";
$rs_empresa   = $io_sql->select($ls_sql);
$data         = $rs_empresa; 
if ($row=$io_sql->fetch_row($rs_empresa))
   {
	 $data=$io_sql->obtener_datos($rs_empresa); 
	 $io_sql->free_result($rs_empresa);
   }
$arrcols            = array_keys($data);
$totcol             = count($arrcols);
$io_dsempresa->data = $data;
$totrow             = $io_dsempresa->getRowCount("codemp");


?>
<br>
<table width="500" border="0" cellpadding="1"  cellspacing="1" class="fondo-tabla" align="center">
<tr class="titulo-celda">
  <td height="22" colspan="2">Cat&aacute;logo de Empresas</td>
  </tr>
<tr class="titulo-celdanew">
<td height="22">Código</td>
<td height="22">Nombre</td>
</tr>
<?php
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$codigo             	= $data["codemp"][$z];
	$nombre             	= $data["nombre"][$z];
	$ls_jefe_presupuesto 	= $data["jefe_presupuesto"][$z];
  	$ls_cargo_presupuesto	= $data["cargo_presupuesto"][$z];
	$ls_secreordenanza	= $data["secre_ordenanza"][$z];
	$ls_cargosecreordenanza	= $data["cargosecre_ordenanza"][$z];
  	$ls_firma1		= $data["firma1_ordenanza"][$z];
  	$ls_cargo1		= $data["cargo1_ordenanza"][$z];
  	$ls_firma2		= $data["firma2_ordenanza"][$z];
  	$ls_cargo2		= $data["cargo2_ordenanza"][$z];
  	$ls_firma3		= $data["firma3_ordenanza"][$z];
  	$ls_cargo3		= $data["cargo3_ordenanza"][$z];
  	$ls_firma4		= $data["firma4_ordenanza"][$z];
  	$ls_cargo4		= $data["cargo4_ordenanza"][$z];
  	$ls_firma5		= $data["firma5_ordenanza"][$z];
  	$ls_cargo5		= $data["cargo5_ordenanza"][$z];
  	$ls_firma6		= $data["firma6_ordenanza"][$z];
  	$ls_cargo6		= $data["cargo6_ordenanza"][$z];
  	$ls_firma7		= $data["firma7_ordenanza"][$z];
  	$ls_cargo7		= $data["cargo7_ordenanza"][$z];
  	$ls_firma8		= $data["firma8_ordenanza"][$z];
  	$ls_cargo8		= $data["cargo8_ordenanza"][$z];
  	$ls_firma9		= $data["firma9_ordenanza"][$z];
  	$ls_cargo9		= $data["cargo9_ordenanza"][$z];
  	$ls_firma10		= $data["firma10_ordenanza"][$z];
  	$ls_cargo10		= $data["cargo10_ordenanza"][$z];
  	$ls_firma11		= $data["firma11_ordenanza"][$z];
  	$ls_cargo11		= $data["cargo11_ordenanza"][$z];
/*
	$nomres			= $data["nomres"][$z];
	$titulo             = $data["titulo"][$z];
	$direccion          = $data["direccion"][$z];
	$ciuemp             = $data["ciuemp"][$z];
	$estemp             = $data["estemp"][$z];
	$zonpos             = $data["zonpos"][$z];
	$telefono           = $data["telemp"][$z];
	$fax                = $data["faxemp"][$z];
	$email              = $data["email"][$z];
	$website            = $data["website"][$z];
    $ls_nomorgads       = $data["nomorgads"][$z];
	$fecha              = $data["periodo"][$z];
	$periodo            = substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
	$enero              = $data["m01"][$z];
	$febrero            = $data["m02"][$z];
	$marzo              = $data["m03"][$z];
	$abril              = $data["m04"][$z];
	$mayo               = $data["m05"][$z];
	$junio              = $data["m06"][$z];
	$julio              = $data["m07"][$z];
	$agosto             = $data["m08"][$z];
	$septiembre         = $data["m09"][$z];
	$octubre            = $data["m10"][$z];
	$noviembre          = $data["m11"][$z];
	$diciembre          = $data["m12"][$z];
	$tipocontabilidad   = $data["esttipcont"][$z];
	$planunico          = $data["formplan"][$z];
	$contabilidad       = $data["formcont"][$z];
	$pgasto             = $data["formpre"][$z];
	$pingreso           = $data["formspi"][$z];
	$activo             = $data["activo"][$z];
	$pasivo             = $data["pasivo"][$z];
	$ingreso            = $data["ingreso"][$z];
	$gasto              = $data["gasto"][$z];
    $resultado          = $data["resultado"][$z];
	$capital            = $data["capital"][$z];
	$deudor             = $data["orden_h"][$z];
	$acreedor           = $data["orden_d"][$z];
	$presupuestogasto   = $data["gasto_p"][$z];
	$presupuestoingreso = $data["ingreso_p"][$z];
	$resultadoactual    = $data["c_resultad"][$z];
	$resultanterior     = $data["c_resultan"][$z];
	$desestpro1         = $data["nomestpro1"][$z];
	$desestpro2         = $data["nomestpro2"][$z];
	$desestpro3         = $data["nomestpro3"][$z];
	$desestpro4         = $data["nomestpro4"][$z];
	$desestpro5         = $data["nomestpro5"][$z];
	$haciendaactivo     = $data["activo_h"][$z];
   	$haciendapasivo     = $data["pasivo_h"][$z];
	$haciendaresul      = $data["resultado_h"][$z];
	$fiscalgasto        = $data["gasto_f"][$z];
	$ingresofiscal      = $data["ingreso_f"][$z];
	$li_traspasos       = $data["estvaltra"][$z];
	$li_valnivel        = $data["vali_nivel"][$z];
	$ls_cuentabienes    = $data["soc_gastos"][$z];
	$ls_cuentaservicios = $data["soc_servic"][$z];
	$ls_estmodape       = $data["estmodape"][$z];
	$li_estdesiva       = $data["estdesiva"][$z];
    $li_precomprometer  = $data["estprecom"][$z];	
	$ls_codorgsig       = $data["codorgsig"][$z];
	$ls_rifemp          = $data["rifemp"][$z];
	$ls_nitemp          = $data["nitemp"][$z];
	$li_numnivest       = $data["numniv"][$z];
	$li_estmodest       = $data["estmodest"][$z];
	$ld_salinipro       = $data["salinipro"][$z];
	$ld_salinipro       = number_format($ld_salinipro,2,',','.');
	$ld_salinieje       = $data["salinieje"][$z];
	$ld_salinieje       = number_format($ld_salinieje,2,',','.');
	$ls_numordcom       = $data["numordcom"][$z];	
	$ls_numordser       = $data["numordser"][$z];
	$ls_numsolpag       = $data["numsolpag"][$z];
	$ls_numlicemp       = $data["numlicemp"][$z];
    $ls_modgenret       = $data["modageret"][$z];
    $ls_concomiva       = $data["concomiva"][$z];
    $ls_estmodiva       = $data["estmodiva"][$z];
    $ls_cedben          = $data["cedben"][$z];
    $ls_nomben          = $data["nomben"][$z];
    $ls_scctaben        = $data["scctaben"][$z];
    $ls_tesoroactivo    = $data["activo_t"][$z];
    $ls_tesoropasivo    = $data["pasivo_t"][$z];
    $ls_tesororesul     = $data["resultado_t"][$z];
    $ls_ctafinanciera   = $data["c_financiera"][$z];
    $ls_ctafiscal       = $data["c_fiscal"][$z];
	$ls_codasiona       = $data["codasiona"][$z];
	$ls_confch          = $data["confi_ch"][$z];
	$ls_confiva         = $data["confiva"][$z];
	$li_diacadche       = $data["diacadche"][$z];
	$ls_ivss            = $data["nroivss"][$z];
	$ls_nomrep          = $data["nomrep"][$z];
	$ls_cedrep          = $data["cedrep"][$z];
	$ls_telfrep         = $data["telfrep"][$z];
	$ls_cargo           = $data["cargorep"][$z];
	$ls_estretiva       = $data["estretiva"][$z];
	$ls_confinstr       = $data["confinstr"][$z];
	
	print "<td align=center><a href=\"javascript: aceptar_empresa('$codigo','$nombre','$nomres','$titulo','$direccion',".
	"'$ciuemp','$estemp','$zonpos','$telefono','$fax','$email','$website','$periodo','$enero','$febrero','$marzo',".
	"'$abril','$mayo','$junio','$julio','$agosto','$septiembre','$octubre','$noviembre','$diciembre','$tipocontabilidad',".
	"'$planunico','$contabilidad','$pgasto','$pingreso','$activo','$pasivo','$ingreso','$gasto','$resultado','$capital',".
	"'$deudor','$acreedor','$presupuestogasto','$presupuestoingreso','$desestpro1','$desestpro2','$desestpro3','$desestpro4',".
	"'$desestpro5','$resultadoactual','$resultanterior','$haciendaactivo','$haciendapasivo','$haciendaresul','$fiscalgasto',".
	"'$ingresofiscal','$li_traspasos','$li_valnivel','$ls_cuentabienes','$ls_cuentaservicios','$ls_estmodape','$li_estdesiva',".
	"'$totalfilas','$total','$li_precomprometer','$total_ctascg','$total_ctaspg','$ls_codorgsig','$ls_rifemp','$ls_nitemp',".
	"'$li_numnivest','$li_estmodest','$ld_salinipro','$ld_salinieje','$ls_numordcom','$ls_numordser','$ls_numsolpag',".
	"'$ls_nomorgads','$ls_numlicemp','$ls_modgenret','$ls_concomiva','$ls_estmodiva','$ls_cedben','$ls_nomben','$ls_scctaben',".
	"'$ls_tesoroactivo','$ls_tesoropasivo','$ls_tesororesul','$ls_ctafinanciera','$ls_ctafiscal','$ls_codasiona',".
	"'$ls_confch','$ls_confiva','$li_diacadche','$ls_ivss','$ls_nomrep','$ls_cedrep','$ls_telfrep','$ls_cargo',".
	"'$ls_estretiva','$ls_confinstr');\">".$codigo."</a></td>";*/
	print "<td align=center><a href=\"javascript: aceptar_empresa('$codigo','$nombre','$ls_jefe_presupuesto',".
  	"'$ls_cargo_presupuesto','$ls_firma1','$ls_cargo1','$ls_firma2','$ls_cargo2','$ls_secreordenanza','$ls_cargosecreordenanza','$ls_firma3','$ls_cargo3',".
	"'$ls_firma4','$ls_cargo4','$ls_firma5','$ls_cargo5','$ls_firma6','$ls_cargo6','$ls_firma7','$ls_cargo7',".
	"'$ls_firma8','$ls_cargo8','$ls_firma9','$ls_cargo9','$ls_firma10','$ls_cargo10','$ls_firma11','$ls_cargo11');\">".$codigo."</a></td>";
	print "<td align=center>".$nombre."</td>";
	print "</tr>";			
}

?>
</table>
</body>
<script language="JavaScript">
  function aceptar_empresa(codigo,nombre,ls_jefe_presupuesto,ls_cargo_presupuesto,ls_firma1,ls_cargo1,ls_firma2,ls_cargo2,ls_secreordenanza,ls_cargosecreordenanza,ls_firma3,ls_cargo3,ls_firma4,ls_cargo4,ls_firma5,ls_cargo5,ls_firma6,ls_cargo6,ls_firma7,ls_cargo7,
ls_firma8,ls_cargo8,ls_firma9,ls_cargo9,ls_firma10,ls_cargo10,ls_firma11,ls_cargo11)
  {
    fop = opener.document.form1;	
    fop.txtcodigo.value    = codigo;
    fop.txtcodigo.readOnly = true;
	fop.txtnombre.value    = nombre;
	fop.txtjefepresupuesto.value    = ls_jefe_presupuesto;
	fop.txtcargopresupuesto.value   = ls_cargo_presupuesto;
	fop.txtsecreordenanza.value   = ls_secreordenanza;
	fop.txtcargosecreordenanza.value   = ls_cargosecreordenanza;
	fop.txtfirma1ordenanza.value    = ls_firma1;
	fop.txtcargo1ordenanza.value    = ls_cargo1;
	fop.txtfirma2ordenanza.value    = ls_firma2;
	fop.txtcargo2ordenanza.value    = ls_cargo2;
	fop.txtfirma3ordenanza.value    = ls_firma3;
	fop.txtcargo3ordenanza.value    = ls_cargo3;
	fop.txtfirma4ordenanza.value    = ls_firma4;
	fop.txtcargo4ordenanza.value    = ls_cargo4;   
	fop.txtfirma5ordenanza.value    = ls_firma5;
	fop.txtcargo5ordenanza.value    = ls_cargo5;
	fop.txtfirma6ordenanza.value    = ls_firma6;
	fop.txtcargo6ordenanza.value    = ls_cargo6;
	fop.txtfirma7ordenanza.value    = ls_firma7;
	fop.txtcargo7ordenanza.value    = ls_cargo7;
	fop.txtfirma8ordenanza.value    = ls_firma8;
	fop.txtcargo8ordenanza.value    = ls_cargo8;   
	fop.txtfirma9ordenanza.value    = ls_firma9;
	fop.txtcargo9ordenanza.value    = ls_cargo9;
	fop.txtfirma10ordenanza.value   = ls_firma10;
	fop.txtcargo10ordenanza.value   = ls_cargo10;
	fop.txtfirma11ordenanza.value   = ls_firma11;
	fop.txtcargo11ordenanza.value   = ls_cargo11;   
	close();
	fop.submit();
  }
</script>
</html>
