<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos SIGECOF</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="contorno" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Bancos SIGECOF </td>
       </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="90" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="408" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	$ls_sql=" SELECT * ".
	        " FROM tepuy_banco_sigecof  ".
			" WHERE codbansig like '".$ls_codigo."' AND denbansig like '".$ls_denominacion."'";
			$rs_banco=$SQL->select($ls_sql);
			$data=$rs_banco;
			if (($rs_banco===false))
			   {
				 $io_msg->message("No hay registros");
			   }
			else
			   {
				 if ($row=$SQL->fetch_row($rs_banco))
				    {
					  $data     = $SQL->obtener_datos($rs_banco);
					  $arrcols  = array_keys($data);
					  $totcol   = count($arrcols);
					  $ds->data = $data;
					  $totrow   = $ds->getRowCount("codbansig");
					  for ($z=1;$z<=$totrow;$z++)
					      {
						    print "<tr class=celdas-blancas>";
						    $codigo=$data["codbansig"][$z];
						    $nombre=$data["denbansig"][$z];
						    print "<td  style=text-align:center><a href=\"javascript: aceptar('$codigo','$nombre');\">".$codigo."</a></td>";
						    print "<td  style=text-align:left>".$nombre."</td>";
						    print "</tr>";			
				 	      }
			   	    }
				 else
				    {
					  $io_msg->message("No se han definido bancos");
				    }
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
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcodbancof.value=codigo;
    opener.document.form1.txtnombancof.value=deno;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_rpc_cat_bancos_sigecof.php";
  f.submit();
  }
</script>
</html>
