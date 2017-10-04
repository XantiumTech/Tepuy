<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Productos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Productos</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodpro" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenpro" type="text" id="txtdenpro">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in     =new tepuy_include();
	$con    =$in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg =new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun =new class_funciones();
	require_once("../shared/class_folder/class_datastore.php");
	$ds     =new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql =new class_sql($con);
	require_once("tepuy_sfa_c_producto.php");
	$io_sfa= new tepuy_sfa_c_producto();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_estnum="";
	$li_catalogo=$io_sfa->uf_sfa_select_catalogo($li_estnum,$li_estcmp);
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codpro="%".$_POST["txtcodpro"]."%";
		$ls_denpro="%".$_POST["txtdenpro"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>Código</td>";
	print "<td>Denominacion</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sfa_producto.*,".
				"      (SELECT dentippro FROM sfa_tipoproducto".
				"        WHERE sfa_tipoproducto.codtippro=sfa_producto.codtippro) as dentippro,".
				"      (SELECT denominacion FROM scg_cuentas".
				"        WHERE scg_cuentas.sc_cuenta=sfa_producto.sc_cuenta) as densccuenta,".
				"      (SELECT denominacion FROM spi_cuentas".
				"        WHERE spi_cuentas.spi_cuenta=sfa_producto.spi_cuenta) as denspicuenta,".
				"      (SELECT denunimed FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed=sfa_producto.codunimed) as denunimed".
				"  FROM sfa_producto".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND codpro LIKE '".$ls_codpro."'".
				"   AND denpro LIKE '".$ls_denpro."'".
				" ORDER BY codpro";
		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$li_num=$io_sql->num_rows($rs_cta);
		if($li_num>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_codpro=     $row["codpro"];
				$ls_denpro=     $row["denpro"];
				$ls_codtippro=  $row["codtippro"];
				$ls_dentippro=  $row["dentippro"];
				$ls_codunimed=  $row["codunimed"];
				$ls_denunimed=  $row["denunimed"];
				$ld_feccrepro=  $row["feccrepro"];
				$ls_obspro=     $row["obspro"];
				$li_exipro=     $row["exipro"];
				$li_exipro=     number_format($li_exipro,2,",",".");
				$li_exiinipro=  $row["exiinipro"];
				$li_exiinipro=  number_format($li_exiinipro,2,",",".");
				$li_minpro=     $row["minpro"];
				$li_minpro=     number_format($li_minpro,2,",",".");
				$li_maxpro=     $row["maxpro"];
				$li_maxpro=     number_format($li_maxpro,2,",",".");
				/*$li_reopro=     $row["reopro"];
				$li_reopro=     number_format($li_reopro,2,",","."); */
				$li_preproa=    $row["preproa"];
				$li_preproa=    number_format($li_preproa,2,",",".");
				$li_preprob=    $row["preprob"];
				$li_preprob=    number_format($li_preprob,2,",",".");
				$li_preproc=    $row["preproc"];
				$li_preproc=    number_format($li_preproc,2,",",".");
				$li_preprod=    $row["preprod"];
				$li_preprod=    number_format($li_preprod,2,",",".");
				$ld_fecvenpro=  $row["fecvenpro"];
				/*$ls_codcatsig=  $row["codcatsig"];
				$ls_dencatsig=  $row["dencatsig"]; */
				$ls_spg_cuenta= $row["spi_cuenta"];
				$ls_spg_denominacion= $row["denspicuenta"];
				$ls_sccuenta=   $row["sc_cuenta"];
				$ls_scdencuenta=$row["densccuenta"];
				/*$ls_serpro= $row["serpro"];
				$ls_fabpro= $row["fabpro"];
				$ls_ubipro= $row["ubipro"];
				$ls_docpro= $row["docpro"];
				$li_pespro=     $row["pespro"];
				$li_pespro=     number_format($li_pespro,2,",",".");
				$li_altpro=     $row["altpro"];
				$li_altpro=     number_format($li_altpro,2,",",".");
				$li_ancro=     $row["ancpro"];
				$li_ancpro=     number_format($li_ancpro,2,",",".");
				$li_propro=     $row["propro"];
				$li_propro=     number_format($li_propro,2,",",".");*/
				$li_ultcospro=  $row["ultcospro"];
				$li_ultcospro=  number_format($li_ultcospro,2,",",".");
				/*$li_cospropro=  $row["cospropro"];
				$li_cospropro=  number_format($li_cospropro,2,",",".");
				$ls_fotpro=     $row["fotpro"];
				if($ls_fotpro=="")
					$ls_fotpro="blanco.jpg";
				$ld_feccrepro=$io_fun->uf_convertirfecmostrar($ld_feccrepro);
				$ld_fecvenpro=$io_fun->uf_convertirfecmostrar($ld_fecvenpro);
				$ls_codmil=$row["codmil"];
				$ls_denmil=$row["denmil"];
				*/
				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_denpro','$ls_codtippro','$ls_dentippro','$ls_codunimed',";
				print "'$ls_denunimed','$ld_feccrepro','$ls_obspro','$li_exipro','$li_exiinipro','$li_minpro','$li_maxpro','$li_preproa',";
				print "'$li_preprob','$li_preproc','$li_preprod','$ld_fecvenpro','$ls_spg_cuenta','$ls_spg_denominacion',";
				//print "'$li_ancpro','$li_propro','$li_ultcospro','$li_cospropro','$ls_fotpro','$ls_codcatsig','$ls_dencatsig',";
				print "'$ls_sccuenta','$ls_scdencuenta');\">".$ls_codpro."</a></td>";
				print "<td>".$ls_denpro."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No hay registros");
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
	function aceptar(ls_codpro,ls_denpro,ls_codtippro,ls_dentippro,ls_codunimed,ls_denunimed,ld_feccrepro,ls_obspro,li_exipro,
	                 li_exiinipro,li_minpro,li_maxpro,li_preproa,li_preprob,li_preproc,li_preprod,ld_fecvenpro,ls_spg_cuenta,ls_spg_denominacion,
					 ls_sccuenta,ls_scdencuenta)
	{
		opener.document.form1.txtcodpro.value=   ls_codpro;
		opener.document.form1.txtdenpro.value=   ls_denpro;
		opener.document.form1.txtcodtippro.value=ls_codtippro;
		opener.document.form1.txtdentippro.value=ls_dentippro;
		opener.document.form1.txtcodunimed.value=ls_codunimed;
		opener.document.form1.txtdenunimed.value=ls_denunimed;
		opener.document.form1.txtfeccrepro.value=ld_feccrepro;
		opener.document.form1.txtobspro.value=   ls_obspro;
		opener.document.form1.txtexipro.value=   li_exipro;

		//opener.document.form1.txtexiiniart.value=li_exiinipro;
		opener.document.form1.txteximinpro.value=li_minpro;
		opener.document.form1.txteximaxpro.value=li_maxpro;
		//opener.document.form1.txtreoart.value=   li_reopro;
		opener.document.form1.txtpreproa.value=  li_preproa;
		opener.document.form1.txtpreprob.value=  li_preprob;
		opener.document.form1.txtpreproc.value=  li_preproc;
		opener.document.form1.txtpreprod.value=  li_preprod;
		opener.document.form1.txtfecvenpro.value=ld_fecvenpro;
		opener.document.form1.txtspg_cuenta.value=ls_spg_cuenta;
		opener.document.form1.txtspgdenominacion.value=ls_spg_denominacion;
		//opener.document.form1.txtpesart.value=   li_pespro;
		//opener.document.form1.txtaltart.value=   li_altpro;
		//opener.document.form1.txtancart.value=   li_ancpro;
		//opener.document.form1.txtproart.value=   li_propro;
		//opener.document.form1.txtultcosart.value=li_ultcospro;
		//opener.document.form1.txtcosproart.value=li_cospropro;
		opener.document.form1.txtsccuenta.value=ls_sccuenta;
		opener.document.form1.txtdensccuenta.value=ls_scdencuenta;
		/*if(ls_fotart!="")
		{opener.document.images["foto"].src="fotosarticulos/"+ls_fotpro;}
		else
		{opener.document.images["foto"].src="fotosarticulos/blanco.jpg";} */
		opener.document.form1.hidstatusc.value="C";
		//opener.document.form1.btnregistrar.disabled=false;
		//opener.document.form1.btncargos.disabled=false;
		//opener.document.form1.txtcodmil.value=ls_codmil;
		//opener.document.form1.txtdenmil.value=ls_denmil;
		//opener.document.form1.txtserart.value=ls_serpro;
		//opener.document.form1.txtfabart.value=ls_fabpro;
		//opener.document.form1.txtubiart.value=ls_ubipro;
		//opener.document.form1.txtdocart.value=ls_docpro;
		/*if(li_catalogo==1)
		{
			opener.document.form1.txtcodcatsig.value= ls_codcatsig;
			opener.document.form1.txtdencatsig.value= ls_dencatsig;		
		}*/
		close();
		/*if(li_exiart<=li_minpro)
		{alert("El artículo esta por debajo del mínimo requerido en el almacén");}
		else
		{
			if(li_exipro<=li_reopro)
			{alert("El artículo esta por debajo de su punto de reorden");}
		}*/
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_sfa_cat_producto.php";
		f.submit();
	}
</script>
</html>
