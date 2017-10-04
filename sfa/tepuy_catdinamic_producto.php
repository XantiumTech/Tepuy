<?php
session_start();
	if(array_key_exists("coddestino",$_GET))
	{
		$ls_coddestino=$_GET["coddestino"];
		$ls_dendestino=$_GET["dendestino"];
	}
	else
	{
		$ls_coddestino="txtcodpro";
		$ls_dendestino="txtdenpro";
	}

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_coddestino=$_POST["hidcoddestino"];
		$ls_dendestino=$_POST["hiddendestino"];
		$ls_codpro="%".$_POST["txtcodpro"]."%";
		$ls_denpro="%".$_POST["txtdenpro"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_codpro="%%";
		$ls_denpro="%%";
		$ls_status="%%";
	
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Productos </title>
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
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
    <input name="hidcoddestino" type="hidden" id="hidcoddestino" value="<?php print $ls_coddestino ?>">
    <input name="hiddendestino" type="hidden" id="hiddendestino" value="<?php print $ls_dendestino ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Productos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
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
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new tepuy_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);

	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>C�digo</td>";
	print "<td>Denominaci�n</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sfa_producto.*, ".
				"      (SELECT dentippro FROM sfa_tipoproducto".
				"        WHERE sfa_tipoproducto.codtippro=sfa_producto.codtippro) as dentippro,".
				"      (SELECT denunimed FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed=sfa_producto.codunimed) as denunimed".
				"  FROM sfa_producto".
				" WHERE codpro like '".$ls_codpro."' ".
				"   AND denpro like '".$ls_denpro."' ".
				" ORDER BY codpro";
		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("codpro");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codpro=$data["codpro"][$z];
				$ls_denpro=$data["denpro"][$z];
				$ls_codtippro=$data["codtippro"][$z];
				$ls_dentippro=$data["dentippro"][$z];
				$ls_codunimed=$data["codunimed"][$z];
				$ls_denunimed=$data["denunimed"][$z];

				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_denpro','$ls_codtippro','$ls_dentippro','$ls_codunimed','$ls_denunimed');\">".$ls_codpro."</a></td>";
				print "<td>".$data["denpro"][$z]."</td>";
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
	function aceptar(ls_codpro,ls_denpro,ls_codtippro,ls_dentippro,ls_codunimed,ls_denunimed)
	{
		opener.document.form1.txtcodpro.value=   ls_codpro;
		opener.document.form1.txtdenpro.value=   ls_denpro;
		opener.document.form1.txtcodtippro.value=   ls_codtippro;
		opener.document.form1.txtdentippro.value=   ls_dentippro;
		opener.document.form1.txtcodunimed.value=   ls_codunimed;
		opener.document.form1.txtdenunimed.value=   ls_denunimed;
		opener.document.form1.hidstatus.value="C";
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_catdinamic_tipoproducto.php";
		f.submit();
	}
</script>
</html>
