<?php
session_start();
	$ld_fecdes="01/".date("m")."/".date("Y");
	$ld_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Productos Incorporados al Inventario</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Productos Incorporados la Inventario</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    <table width="520" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="21"><div align="right">C&oacute;digo </div></td>
        <td width="217" height="21"><input name="txtcodpro" type="text" id="txtcodpro" onKeyPress="javascript: ue_mostrar(this,event);"></td>
        <td width="160" rowspan="3"><table width="145" border="0" align="right" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2"><div align="center">Fecha de Emisi&oacute;n </div></td>
          </tr>
          <tr>
            <td width="58" height="22"><div align="right">Desde</div></td>
            <td width="85"><input name="txtfecdes" type="text" id="txtfecdes" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fecdes;?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Hasta</div></td>
            <td><input name="txtfechas" type="text" id="txtfechas" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fechas;?>"></td>
          </tr>
        </table>
        <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdenpro" type="text" id="txtdenpro" size="35"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
	  <tr>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
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
	print "<td width=50>Código</td>";
	print "<td width=150>Denominacion</td>";
	print "<td width=100>Fecha</td>";
	print "<td width=100>Ingreso al Inv.</td>";
	print "<td width=100>Precio</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ld_fecdes=substr($ld_fecdes,6,4)."-".substr($ld_fecdes,3,2)."-".substr($ld_fecdes,0,2);
		$ld_fechas=substr($ld_fechas,6,4)."-".substr($ld_fechas,3,2)."-".substr($ld_fechas,0,2);
		$ls_sql="SELECT inv.*, MAX(pro.codtippro) as codtippro, MAX(pro.codunimed) AS codunimed,MAX(pro.denpro) AS denpro, MAX(pro.preproa) as preproa, ".
				"      (SELECT MAX(a.dentippro) FROM sfa_tipoproducto a, sfa_producto b".
				"        WHERE a.codtippro=b.codtippro) as dentippro,".
				"      (SELECT MAX(denunimed)  FROM siv_unidadmedida c, sfa_producto d".
				"        WHERE c.codunimed=d.codunimed) as denunimed".
				"  FROM sfa_producto_act inv, sfa_producto pro".
				" WHERE inv.codemp = '".$ls_codemp."'".
				"   AND inv.codpro LIKE '".$ls_codpro."'".
				"   AND pro.codpro LIKE '".$ls_codpro."'".
				"   AND pro.denpro LIKE '".$ls_denpro."'".
				"   AND inv.fecrecpro BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."' ".
				" ORDER BY inv.codpro";
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
				$ld_fecrecpro=  $row["fecrecpro"];
				$ld_fecrecpro1=	$io_fun->uf_convertirfecmostrar($ld_fecrecpro);
				$ls_obspro=     $row["obspro"];
				$li_exipro=     $row["exipro"];
				$li_exipro=     number_format($li_exipro,2,",",".");
				$ld_fecvenpro=  $row["fecvenpro"];
				$li_prepro=  $row["preproa"];
				$li_prepro=     number_format($li_prepro,2,",",".");

				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_denpro','$ls_codtippro','$ls_dentippro','$ls_codunimed',";
				print "'$ls_denunimed','$ld_fecrecpro','$ls_obspro','$li_exipro','$ld_fecvenpro','$ls_usuario');\">".$ls_codpro."</a></td>";
				print "<td>".$ls_denpro."</td>";
				print "<td>".$ld_fecrecpro1."</td>";
				print "<td>".$li_exipro."</td>";
				print "<td>".$li_prepro."</td>";
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
	function aceptar(ls_codpro,ls_denpro,ls_codtippro,ls_dentippro,ls_codunimed,ls_denunimed,ld_fecrecpro,ls_obspro,li_exipro,
	                 ld_fecvenpro,ls_usuario)
	{
		opener.document.form1.txtcodpro.value=   ls_codpro;
		opener.document.form1.txtdenpro.value=   ls_denpro;
		opener.document.form1.txtcodtippro.value=ls_codtippro;
		opener.document.form1.txtdentippro.value=ls_dentippro;
		opener.document.form1.txtcodunimed.value=ls_codunimed;
		opener.document.form1.txtdenunimed.value=ls_denunimed;
		opener.document.form1.txtfeccrepro.value=ld_fecrecpro;
		opener.document.form1.txtobspro.value=   ls_obspro;
		opener.document.form1.txtexipro.value=   li_exipro;
		//opener.document.form1.txteximinpro.value=li_minpro;
		

		opener.document.form1.hidstatusc.value="C";

		close();

	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_sfa_cat_producto_inventario.php";
		f.submit();
	}
</script>
</html>
