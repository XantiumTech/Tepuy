<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Pedidos Procesados</title>
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
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Listado de Pedidos Procesados</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">Numero de Pedido</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtnumpedido" type="text" id="txtnumpedido" size="15" maxlength="15">
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
		$ls_numpedido="%".$_POST["txtnumpedido"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=80>Pedido</td>";
	print "<td width=80>Estatus</td>";
	print "<td width=170 align='center'>Cliente</td>";
	print "<td width=80>Fecha</td>";
	print "<td width=100>Monto</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sfa_pedidos.*, ".
				"      (SELECT nomcli FROM sfa_cliente ".
				"        WHERE sfa_pedidos.cedcli = sfa_cliente.cedcli) AS nomcli, ".
				"      (SELECT apecli FROM sfa_cliente ".
				"        WHERE sfa_pedidos.cedcli = sfa_cliente.cedcli) AS apecli ".
			    "  FROM sfa_pedidos ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND numpedido like '".$ls_numpedido."' ".
				//"   AND estrevrec='1'".
				" ORDER BY numpedido";
		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_cta);
		if($li_row>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_numpedido	= $row["numpedido"];
				$ls_obsped	= $row["obsped"];
				$ls_forpag	= $row["forpagped"];
				$ls_cedcli	= $row["cedcli"];
				$ls_nomcli	= trim($row["nomcli"]);
				$ls_apecli	= trim($row["apecli"]);
				$ls_nombre	= $ls_nomcli." ".$ls_apecli;
				$li_montot	= number_format($row["montot"],2,",",".");
				$ls_estped	= $row["estped"];
				if($ls_estped=="1")
				{
				   $ls_estatus="<b>Por Facturar</b>";
				}
				if($ls_estped=="2")
				{
				   $ls_estatus="Facturada";
				}
				if($ls_estped=="3")
				{
				   $ls_estatus="Anulada";
				}
				$ld_fecped     = $row["fecpedido"];
				$ld_fecped     = $io_fun->uf_convertirfecmostrar($ld_fecped);
				print "<td><a href=\"javascript: aceptar('$ls_numpedido','$ls_cedcli','$ls_nombre','$ls_forpag','$ls_obsped','$ld_fecped');\">".$ls_numpedido."</a></td>";
				print "<td>".$ls_estatus."</td>";
				print "<td>".$ls_nomcli." ".$ls_apecli."</td>";
				print "<td>".$ld_fecped."</td>";
				print "<td>".$li_montot."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No se han procesado Pedidos");
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
	function aceptar(ls_numpedido,ls_cedcli,ls_nomcli,ls_forpag,ls_obsped,ld_fecped)
	{ 
		opener.document.form1.txtnumpedido.value=ls_numpedido;
		opener.document.form1.txtcedcli.value=ls_cedcli;
		opener.document.form1.txtnomcli.value=ls_nomcli;
		opener.document.form1.cmbforpag.value=ls_forpag;
		opener.document.form1.txtobsped.value=ls_obsped;
		opener.document.form1.txtfecrec.value=ld_fecped;
		opener.document.form1.hidestatus.value="C";

		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="tepuy_sfa_d_pedidos.php";
		opener.document.form1.submit();
		close();
	}
	

	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_catdinamic_pedidos.php";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
