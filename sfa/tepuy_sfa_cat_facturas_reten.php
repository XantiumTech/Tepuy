<?php
session_start();
ini_set('display_errors', 1);
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$ls_tipo=$io_fun_sfa->uf_obtenertipo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Facturas sujetas a Retencion</title>
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
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
      <td width="496" colspan="2" class="titulo-celda">Listado de Facturas sujetas a Retencion</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">Numero de Factura</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtnumfactura" type="text" id="txtnumfactura" size="15" maxlength="15">
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
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in=     new tepuy_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	$io_fun= new class_funciones();

	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

	if (array_key_exists("linea",$_GET))
	{
		$li_linea=$_GET["linea"];
	}
	else
	{
		if(array_key_exists("hidlinea",$_POST))
		{
			$li_linea=$_POST["hidlinea"];
		}
		else
		{
			$li_linea="";
		}
	}
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numfactura="%".$_POST["txtnumfactura"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_tipo=$_POST["tipo"];
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=80>Factura</td>";
	print "<td width=70>Estatus</td>";
	print "<td width=180 align='center'>Cliente</td>";
	print "<td width=80>Fecha</td>";
	print "<td width=100>Monto</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sfa_cmp_retencion.*, ".
				"      (SELECT montot FROM sfa_factura ".
				"        WHERE sfa_cmp_retencion.numfactura = sfa_factura.numfactura) AS montot, ".
				"      (SELECT estfac FROM sfa_factura ".
				"        WHERE sfa_cmp_retencion.numfactura = sfa_factura.numfactura) AS estfac, ".
				"      (SELECT nomcli FROM sfa_cliente ".
				"        WHERE sfa_cmp_retencion.cedcli = sfa_cliente.cedcli) AS nomcli, ".
				"      (SELECT apecli FROM sfa_cliente ".
				"        WHERE sfa_cmp_retencion.cedcli = sfa_cliente.cedcli) AS apecli ".
				"  FROM sfa_cmp_retencion ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND numfactura like '".$ls_numfactura."' ".
				"   AND estcmpret!='2'".
				" ORDER BY numfactura";
		//print $ls_sql;die();
		//print "Tipo: ".$ls_tipo;
		$rs_cta=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_cta);
		if($li_row>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_numfactura	= $row["numfactura"];
				//$ls_obsfac	= $row["obsfac"];
				//$ls_forpag	= $row["forpagfac"];
				$ls_cedcli	= $row["cedcli"];
				$ls_nomcli	= trim($row["nomcli"]);
				$ls_apecli	= trim($row["apecli"]);
				$ls_nombre	= $ls_nomcli." ".$ls_apecli;
				$li_montot	= number_format($row["montot"],2,",",".");
				$ls_estfac	= $row["estfac"];
				if($ls_estfac=="1")
				{
				   $ls_estatus="Por Emisión";
				}
				if($ls_estfac=="2")
				{
				   $ls_estatus="Emitida";
				}
				
				$ls_feccomp     = $row["feccomp"];
				$ls_feccomp     = $io_fun->uf_convertirfecmostrar($ls_feccomp);
				print "<td><a href=\"javascript: aceptar('$ls_numfactura','$ls_cedcli','$ls_nombre','$ls_feccomp','$ls_tipo');\">".$ls_numfactura."</a></td>";
				print "<td>".$ls_estatus."</td>";
				print "<td>".$ls_nomcli." ".$ls_apecli."</td>";
				print "<td>".$ls_feccomp."</td>";
				print "<td>".$li_montot."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No se han procesado facturas");
		}
	}
	print "</table>";
?>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_numfactura,ls_cedcli,ls_nomcli,ld_feccomp,tipo)
	{
		if(tipo=="DESDE")
		{
			opener.document.formulario.txtnumfacturades.value=ls_numfactura;
		}
		else
		{
			opener.document.formulario.txtnumfacturahas.value=ls_numfactura;
		}
		/*opener.document.form1.txtcedcli.value=ls_cedcli;
		opener.document.form1.txtnomcli.value=ls_nomcli;
		opener.document.form1.cmbforpag.value=ls_forpag;
		opener.document.form1.txtobsfac.value=ls_obsfac;
		opener.document.form1.txtfecrec.value=ld_fecfac;
		opener.document.form1.hidestatus.value="C";*/

		/*opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="tepuy_sfa_d_facturar.php";
		opener.document.form1.submit();*/
		close();
	}
	

	
	function ue_search()
  	{
		f=document.form1;
		//alert(f.tipo.value);
		f.operacion.value="BUSCAR";
		f.action="tepuy_sfa_cat_facturas_reten.php";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
