<?Php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../tepuy_conexion.php';";
	print "close();";
	print "</script>";		
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Retenciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1" >
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Retenciones </td>
  </tr>
</table>
<br>
<form name="form1" method="post" action="">
  <div align="center">
<?Php
	require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/tepuy_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codcon=$_GET["codcon"];
	$ls_codemp=$la_empresa["codemp"];
	$io_msg=new class_mensajes();
	$io_funnum=new tepuy_sob_c_funciones_sob();	
	$io_conect=new tepuy_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);


   // ESTA ES LA QUE TENIA ANTES , SOLO BUSCABA LAS QUE TENIA ASIGNADA EL CONTRATO ///
	//$ls_sql="SELECT rc.codded,d.dended as dended,d.sc_cuenta as cuenta,d.monded as deducible,d.porded FROM sob_retencioncontrato rc, tepuy_deducciones d WHERE rc.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND rc.codded=d.codded AND rc.codcon='".$ls_codcon."' ORDER BY d.codded ASC";

	// CON ESTA METODOLOGIA APLICA LAS CREADAS EN EL MODULO DE RETENCIONES DEL SISTEMA DE ORDENES DE PAGO ///
	$ls_sql="SELECT codded,dended as dended,sc_cuenta as cuenta,monded as deducible,porded,cubretotal FROM tepuy_deducciones WHERE codemp='".$ls_codemp."' ORDER BY codded ASC";

//print $ls_sql;

	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codded");
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td>Código</td>";
		print "<td>Descripción</td>";
		print "<td>Cuenta</td>";
		print "<td>Deducible</td>";
		print "<td>Porcentaje</td>";
		print "</tr>";
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codded"][$li_z];			
			$ls_descripcion=$data["dended"][$li_z];
			$ls_cuenta=$data["cuenta"][$li_z];
			$ls_deducible=$data["deducible"][$li_z];
			$ls_formula=$data["formula"][$li_z];
			$ls_cubretotal=$data["cubretotal"][$li_z];

			$ls_porded=$data["porded"][$li_z];

			$ls_deducible=$io_funnum->uf_convertir_numerocadena($ls_deducible);
			print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_descripcion','$ls_cuenta','$ls_deducible','$ls_porded','$ls_cubretotal');\">".$ls_codigo."</a></td>";
			print "<td align=center>".$ls_descripcion."</td>";
			print "<td align=center>".$ls_cuenta."</td>";
			print "<td align=center>".$ls_deducible."</td>";
			print "<td align=center>".$ls_porded."</td>";
			print "</tr>";			
		}
		print "</table>";
	}
	else
	  {
		$io_msg->message("No se han creado Retenciones");
	  }
	$io_sql->free_result($rs_data);
	$io_sql->close();
?>
</div>
  </form>
</body>
<script language="JavaScript">
  function aceptar(codigo,descripcion,cuenta,deducible,porded,cubretotal)
  {
	//alert(cubretotal);
    opener.ue_cargarretenciones(codigo,descripcion,cuenta,deducible,porded,porded,cubretotal);
	close();
  }
</script>
</html>
