<?php
session_start();
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$ls_tipo=$io_fun_sfa->uf_obtenertipo();
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
   <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
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
		$ls_tipo=$_POST["tipo"];
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
				"        WHERE sfa_tipoproducto.codtippro=sfa_producto.codtippro) as dentippro".
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

				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_denpro','$ls_tipo');\">".$ls_codpro."</a></td>";
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
	function aceptar(ls_codpro,ls_denpro,ls_tipo)
	{
	//alert(ls_tipo);
		if(ls_tipo=="DESDE")
		{
			opener.document.form1.txtcodprodes.value=ls_codpro;
			opener.document.form1.txtdenprodes.value=ls_denpro;
		}
		else
		{
			opener.document.form1.txtcodprohas.value=ls_codpro;
			opener.document.form1.txtdenprohas.value=ls_denpro;
		}

		close();

	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_sfa_cat_producto_reporte.php";
		f.submit();
	}
</script>
</html>
