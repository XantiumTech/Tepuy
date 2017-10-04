<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Contadores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Contadores </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431"><div align="left">
          <input name="txtid" type="text" id="txtid">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Retencion</div></td>
        <td><div align="left">
          <input name="txtreten" type="text" id="txtreten" size="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
 <?php
//$ls_operacion="BUSCAR";
require_once("../../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$ds=new class_datastore();
require_once("../../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
require_once("class_folder/tepuy_cxp_c_inicio_contadores.php");
$io_contadores = new tepuy_cxp_c_inicio_contadores($con);

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_id="%".$_POST["txtid"]."%";
	$ls_retencion="%".$_POST["txtretencion"]."%";
}
else
{
	$ls_operacion="";
	$ls_id="";
	$ls_retencion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Codigo</td>";
print "<td>Retencion</td>";
print "<td>Numero de Control</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT * FROM   cxp_contador WHERE  codemp='".$ls_codemp."' ORDER BY codcmp";
	//print $ls_sql;
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codcmp");
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_id=$data["codcmp"][$z];
			$ls_retencion=$data["nom"][$z];
			$ls_nro_inicial=$data["numcmp"][$z];
			$ls_tipo=$data["tipo"][$z];
			$ls_estado=$data["estado"][$z];
			print "<td align=center><a href=\"javascript: aceptar('$ls_id','$ls_retencion','$ls_nro_inicial','$ls_tipo','$ls_estado');\">".$ls_id."</a></td>";
			print "<td align=center>".$ls_retencion."</td>";		
			print "<td align=center>".$ls_nro_inicial."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido Contadores");
	}
}
print "</table>";
?>
</div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(id,retencion,nro_inicial,tipo,estado)
  {
		opener.document.form1.txtid.value           = id;
		opener.document.form1.txtretencion.value    = retencion;
		opener.document.form1.txtnro_inicial.value  = nro_inicial;
		opener.document.form1.radiotipodeduccion.value= tipo;
		//opener.document.form1.chkestado.value      = estado;
		opener.document.form1.radioestado.value= estado;		
		//alert(opener.document.form1.chkestado.value);
		opener.document.form1.operacion.value       = "CARGAR";
		opener.document.form1.hidestatus.value      = "GRABADO";
		opener.document.form1.submit(); 
		close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cxp_cat_contadores.php";
	  f.submit();
  }
</script>
</html>
