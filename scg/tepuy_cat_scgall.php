<?php
session_start();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_obj=$_POST["obj"];
	
}
else
{
	$ls_operacion="";
	$ls_obj=$_GET["obj"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Contables </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="122" height="22" align="right">Cuenta</td>
        <td width="238"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a>
            <input name="obj" type="hidden" id="obj" value="<? print $ls_obj; ?>">
        </div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena =" SELECT sc_cuenta, denominacion, status, asignado, distribuir," . 
		        "        enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,".
		        "        nivel, referencia FROM scg_cuentas ".
		        " WHERE codemp = '".$as_codemp."' AND sc_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' ".
				" ORDER BY sc_cuenta";
		$rs_data=$SQL->select($ls_cadena);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($SQL->message)); 
		}
		else
		{
			$lb_existe=false;
			while($row=$SQL->fetch_row($rs_data))
			{
				$lb_existe=true;
				$cuenta=$row["sc_cuenta"];
				$denominacion=$row["denominacion"];
				$status=$row["status"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$status');\">".$cuenta."</a></td>";
				print "<td  align=left>".$denominacion."</td>";
				print "</tr>";			
			}
			if($lb_existe==false)
			{
				?>
					<script language="JavaScript">
					alert("No se han creado Cuentas Contables.....");
					close();
					</script>
				<?php
			}
		}
/*
	$rs_cta=$SQL->select($ls_cadena);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("sc_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta=$data["sc_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			$status=$data["status"][$z];
			print "<tr class=celdas-blancas>";
			print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$status');\">".$cuenta."</a></td>";
			print "<td  align=left>".$denominacion."</td>";
			print "</tr>";			
		}
	}
	else
	{
		?>
			<script language="JavaScript">
			alert("No se han creado Cuentas Contables.....");
			close();
			</script>
		<?php
	}*/
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,d,status)
  {
     opener.document.form1.<? print $ls_obj ?>.value=cuenta;
     opener.document.form1.<? print $ls_obj ?>.readOnly=true;
	 close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_scgall.php";
	  f.submit();
  }

</script>
</html>