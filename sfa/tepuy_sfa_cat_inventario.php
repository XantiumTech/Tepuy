<?php
session_start();
ini_set('display_errors', 1);
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_soc=new class_funciones_sfa();
	$ls_tipo=$io_fun_soc->uf_obtenertipo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Movimientos de Inventario</title>
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
      <td width="496" colspan="2" class="titulo-celda">Listado de Movimientos de Inventario</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">Numero de Movimiento de Inventario</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtnummovimiento" type="text" id="txtnummovimiento" size="15" maxlength="15">
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
		$ls_nummovimiento="%".$_POST["txtnummovimiento"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_tipo=$_POST["tipo"];
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=80>Movimiento</td>";
	print "<td width=220>Observacions</td>";
	print "<td width=80>Fecha</td>";
	print "<td width=120 align='center'>Usuario que ingreso</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sfa_movimiento.*, ".
				"      (SELECT nomusu FROM sss_usuarios ".
				"        WHERE sfa_movimiento.usuario = sss_usuarios.codusu) AS nomusu, ".
				"      (SELECT apeusu FROM sss_usuarios ".
				"        WHERE sfa_movimiento.usuario = sss_usuarios.codusu) AS apeusu ".
			    "  FROM sfa_movimiento ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND nummov like '".$ls_nummovimiento."' ".
				//"   AND estrevrec='1'".
				" ORDER BY nummov";
		//print $ls_sql;
		//print "Tipo: ".$ls_tipo;
		$rs_cta=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_cta);
		if($li_row>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_nummovimiento= $row["nummov"];
				$ls_obsmov	= $row["obsmov"];
				$ls_cedaut	= $row["cedaut"];
				$ls_nomusu	= trim($row["nomusu"]);
				$ls_apeusu	= trim($row["apeusu"]);
				$ls_nombre	= $ls_nomusu." ".$ls_apeusu;
				$li_montot	= number_format($row["totalmov"],2,",",".");
				$ld_fecmov     = $row["fecmov"];
				$ld_fecmov     = $io_fun->uf_convertirfecmostrar($ld_fecmov);
				print "<td><a href=\"javascript: aceptar('$ls_nummovimiento','$ls_obsmov','$ld_fecmov','$ls_nombre','$ls_tipo');\">".$ls_nummovimiento."</a></td>";
				print "<td>".$ls_obsmov."</td>";
				print "<td>".$ld_fecmov."</td>";
				print "<td>".$ls_nombre."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No se han procesado Movimiento");
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
	function aceptar(ls_nummovimiento,ls_obsfac,ls_fecmov,ls_usuario,tipo)
	{
		if(tipo=="DESDE")
		{
			opener.document.formulario.txtnummovdes.value=ls_nummovimiento;
		}
		else
		{
			opener.document.formulario.txtnummovhas.value=ls_nummovimiento;
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
		f.action="tepuy_sfa_cat_inventario.php";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
