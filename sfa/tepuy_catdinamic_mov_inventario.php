<?php
session_start();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_nummovinv="%".$_POST["txtnummovinv"]."%";
		$ld_fecdes     = $_POST["txtfecdes"];
	  	$ld_fechas     = $_POST["txtfechas"];
    	}
	else
	{
		$ls_operacion="";
		$ls_nummovinv="% %";
		$ld_fecdes    = "01/".date("m")."/".date("Y");
		$ld_fechas    = date("d/m/Y");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimiento de Inventario</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">

</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Movimiento de Inventario</td>
    </tr>
  </table>
      <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="71"><div align="right">Numero </div></td>
        <td width="420" height="22"><div align="left">
          <input name="txtnummovinv" type="text" id="txtnummovinv" size="25" maxlength="15">
        </div></td>
      </tr>
      <td height="22"><div align="right">Fecha</div></td>
      <td height="22">Desde 
        <input name="txtfecdes" type="text" id="txtfecdes"  value="<?php print $ld_fecdes ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);">
        &nbsp;&nbsp; 
        Hasta
<input name="txtfechas" type="text" id="txtfechas" value="<?php print $ld_fechas ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left"></td>
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
	require_once("class_funciones_inventario.php");
	$fun_inv=new class_funciones_inventario("../");
	$in=     new tepuy_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_rifemp=$arre["rifemp"];

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
	

  
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=70>Orden de Inventario</td>";
	print "<td width=100 align='center'>Personal que Autorizo</td>";
	print "<td width=70>Fecha</td>";
	print "<td width=100>Personal que Ingreso los Datos</td>";
	print "<td width=160>Observaciones</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_fecdes=$io_func->uf_convertirdatetobd($_POST['txtfecdes']);
		$ls_fechas=$io_func->uf_convertirdatetobd($_POST['txtfechas']);
		 $ls_sql="SELECT sfa_movimiento.*, ".
				"      (SELECT nomper FROM sno_personal".
				"        WHERE sno_personal.cedper = sfa_movimiento.cedaut) AS nomper, ".
				"      (SELECT apeper FROM sno_personal".
				"        WHERE sno_personal.cedper = sfa_movimiento.cedaut) AS apeper, ".
				"      (SELECT nomusu FROM sss_usuarios".
				"        WHERE sfa_movimiento.usuario = sss_usuarios.codusu) AS nomusu, ".
				"      (SELECT apeusu FROM sss_usuarios".
				"        WHERE sfa_movimiento.usuario = sss_usuarios.codusu) AS apeusu ".
				"  FROM	sfa_movimiento ".
				"  WHERE codemp = '".$ls_codemp."'".
				"  AND nummov LIKE '".$ls_nummovinv."'".
				"  AND fecmov>='".$ls_fecdes."' "." AND fecmov<='".$ls_fechas."' ";
		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("nummov");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_nummovinv=	$data["nummov"][$z];
				$ls_numcontrol=	$data["numconint"][$z];
				$ls_cedaut= 	$data["cedaut"][$z];
				$ls_nomper= 	trim($data["nomper"][$z]);
				$ls_apeper= 	trim($data["apeper"][$z]);
				$ls_nombre= 	$ls_nomper." ".$ls_apeper;
				$ls_nomusu= 	trim($data["nomusu"][$z]);
				$ls_apeusu= 	trim($data["apeusu"][$z]);
				$ls_usuario= 	$ls_nomusu." ".$ls_apeusu;
				$ld_fecmov=	$data["fecmov"][$z];
				$ls_obsmov=	$data["obsmov"][$z];
				$li_totalmov=	$data["totalmov"][$z];
				$li_totalmov=number_format($li_totalmov,2,",",".");
				$ld_fecmov=$io_func->uf_convertirfecmostrar($ld_fecmov);
				print "<td><a href=\"javascript: aceptar('$ls_nummovinv','$ls_numcontrol','$ls_cedaut','$ls_nombre','$ls_obsmov','$ld_fecmov','$li_totalmov');\">".$ls_nummovinv."</a></td>";
				print "<td>".$ls_nombre."</td>";
				print "<td>".$ld_fecmov."</td>";
				print "<td>".$ls_usuario."</td>";
				print "<td>".$ls_obsmov."</td>";
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
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_nummov,ls_numcontrol,ls_cedaut,ls_nombre,ls_obsmov,ld_fecmov,li_totalmov)
	{ 
		opener.document.form1.txtnuminginv.value=ls_nummov;	
		opener.document.form1.txtnumcontrol.value=ls_numcontrol;
		opener.document.form1.txtperaut.value=ls_cedaut;	
		opener.document.form1.txtnomperaut.value=ls_nombre;
		opener.document.form1.txtobsmov.value=ls_obsmov;
		opener.document.form1.txtfecmovinv.value=ld_fecmov;
		opener.document.form1.txttotsuminv.value=li_totalmov;
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="tepuy_sfa_d_productos_inventario.php";
		opener.document.form1.submit();
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_catdinamic_mov_inventario.php"; //?destino=<?PHP print $ls_destino;?>&tipo=<?PHP print $ls_tipo;?>";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
