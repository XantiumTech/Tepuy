<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Proveedores</title>
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
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Proveedores </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scf.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Nombre del Proveedor</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT cod_pro,nompro ".
	        " FROM  rpc_proveedor ".
			" WHERE cod_pro like '".$ls_codigo."' AND ".
			"       nompro like '".$ls_nombre."' ";
	$rs_cta=$SQL->select($ls_sql);
	$li_total=$SQL->num_rows($rs_cta);
	if($li_total>=0)
	{
		while($row=$SQL->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$row["cod_pro"];
			$ls_nombre=$row["nompro"];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_nombre."</td>";
			print "</tr>";			
		}
	}
	else
	{
		?>
			<script language="JavaScript">
			alert("No se han creado Proveedores.....");
			close();
			</script>
		<?php
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
  function aceptar(codigo,denominacion)
  {
	  opener.document.form1.txtprovbene.value=codigo;
	  opener.document.form1.txtprovbene.value=denominacion;
	  close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_catdinamic_prov.php";
	  f.submit();
  }
</script>
</html>
