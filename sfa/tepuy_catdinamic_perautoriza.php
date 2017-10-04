<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Personal de N&oacute;mina </title>
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
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Listado de Personal de N&oacute;mina </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&eacute;dula</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodalm" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombres  </div></td>
        <td height="22"><div align="left">          <input name="txtnompersonal" type="text" id="txtnompersonal">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Apellidos  </div></td>
        <td height="22"><div align="left">          <input name="txtapepersonal" type="text" id="txtapepersonal">
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
		$ls_cedper="%".$_POST["txtcodalm"]."%";
		$ls_nomper="%".$_POST["txtnompersonal"]."%";
		$ls_apeper="%".$_POST["txtapepersonal"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_cedper="%%";
		$ls_nomper="%%";
		$ls_apeper="%%";
		$ls_status="%%";
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Cédula</td>";
	print "<td>Nombre y Apellidos</td>";
	print "<td>Cargo</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, ".
			" sno_personal.nomper, sno_personal.apeper, sno_personal.dirper, ".
			" sno_personal.telmovper, sno_cargo.descar ".
			" FROM sno_personal, sno_cargo, sno_personalnomina  ".
				" WHERE sno_personal.codemp = '".$ls_codemp."' ".
				" AND sno_personal.cedper LIKE '".$ls_cedper."' ".
				" AND sno_personal.nomper LIKE '".$ls_nomper."' ".
				" AND sno_personal.apeper LIKE '".$ls_apeper."' ".
				" AND sno_personal.estper='1' ".
				" AND sno_personalnomina.codemp = '".$ls_codemp."' ".
				" AND sno_personal.codemp = sno_personalnomina.codemp ".
				" AND sno_personal.codper = sno_personalnomina.codper ".
				" AND sno_personalnomina.staper='1' ".
				" AND sno_personalnomina.codemp = '".$ls_codemp."' ".
				" AND sno_cargo.codemp = sno_personalnomina.codemp ".
				" AND sno_cargo.codnom = sno_personalnomina.codnom ".
				" AND sno_cargo.codcar = sno_personalnomina.codcar ".
				" AND sno_cargo.codcar = '0000000001' ".
				" GROUP BY sno_personal.apeper ASC";
		//print $ls_sql;

				
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("cedper");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_cedper=	$data["cedper"][$z];
				$ls_nomper=	trim($data["nomper"][$z]);
				$ls_apeper=	trim($data["apeper"][$z]);
				$ls_nombre=$ls_nomper." ".$ls_apeper;
				$ls_telper=	$data["telper"][$z];
				$ls_dirper=	$data["dirper"][$z];
				$ls_cargo=	$data["descar"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_cedper','$ls_nombre','$ls_telper','$ls_dirper','$ls_cargo',";
				print "'$ls_status','$li_linea');\">".$ls_cedper."</a></td>";
				print "<td>".$ls_nombre."</td>";
				print "<td>".$ls_cargo."</td>";
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
	function aceptar(ls_cedper,ls_nombre,ls_telper,ls_dirper,ls_cargo,hidstatus,li_linea)
	{
		obj=eval("opener.document.form1.txtperaut"+li_linea+"");
		obj.value=ls_cedper;
		opener.document.form1.txtnomperaut.value=ls_nombre+" - "+ls_cargo;
		opener.document.form1.txttelperaut.value=ls_telper;
		opener.document.form1.txtdirperaut.value=ls_dirper;
		opener.document.form1.hidstatus.value="C";
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_catdinamic_perautoriza.php";
		f.submit();
	}
</script>
</html>
