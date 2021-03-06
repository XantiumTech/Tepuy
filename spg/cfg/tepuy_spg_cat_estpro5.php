<?php
session_start();
$la_empresa=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");

$in         = new tepuy_include();
$con        = $in->uf_conectar();
$io_msg     = new class_mensajes();
$ds         = new class_datastore();
$io_sql     = new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $la_empresa["codemp"];
$li_estmodest = $la_empresa["estmodest"];

if ($li_estmodest=='1')
   {
	 $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_maxlength_3 = '3';
	 $li_size        = '25';
	 $li_ancho       = '70';
   }
else
   {
	 $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_maxlength_3 = '2';
	 $li_size        = '5';
	 $li_ancho       = '90';
   }

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion  = $_POST["operacion"];
	$ls_codestpro1 = $_POST["codigo"];
	$ls_denestpro1 = $_POST["txtdenestpro1"];
	$ls_codestpro2 = $_POST["txtcodestpro2"];
	$ls_denestpro2 = $_POST["txtdenestpro2"];
	$ls_codestpro3 = $_POST["txtcodestpro3"];
	$ls_denestpro3 = $_POST["txtdenestpro3"];
	$ls_codestpro4 = $_POST["txtcodestpro4"];
	$ls_denestpro4 = $_POST["txtdenestpro4"];
	$ls_codestpro5 = $_POST["txtcodestpro5"];
	$ls_denestpro5 = $_POST["txtdenestpro5"];
}
else
{
	$ls_operacion  = "BUSCAR";
	$ls_codestpro1 = $_GET["txtcodestpro1"];
	$ls_denestpro1 = $_GET["txtdenestpro1"];
	$ls_codestpro2 = $_GET["txtcodestpro2"];
	$ls_denestpro2 = $_GET["txtdenestpro2"];
	$ls_codestpro3 = $_GET["txtcodestpro3"];
	$ls_denestpro3 = $_GET["txtdenestpro3"];
	$ls_codestpro4 = $_GET["txtcodestpro4"];
	$ls_denestpro4 = $_GET["txtdenestpro4"];
	$ls_codestpro5 = "";
	$ls_denestpro5 = "";
}
$ls_pantalla   = $_GET["txtpantalla"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 5 <?php print $la_empresa["nomestpro5"] ?></title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
        Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="432" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="70" maxlength="70" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print  $ls_codestpro2 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_2 ?>" readonly style="text-align:center">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="70" class="sin-borde" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td height="22"><label>
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print  $ls_codestpro3 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_3 ?>" style="text-align:center">
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" style="text-align:left" value="<?php print $ls_denestpro3 ?>" size="70" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro4"]?></div></td>
        <td height="22"><label>
          <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $li_size ?>" maxlength="2" style="text-align:center">
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="70" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><input name="txtcodestpro5" type="text" id="txtcodestpro5"  size="<?php print $li_size ?>" maxlength="2" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro5" type="text" id="txtdenestpro5"  size="72" maxlength="100" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><input name="txtpantalla" type="hidden" id="textpantalla"  size="18" maxlength="100" style="text-align:left" value="<?=$ls_pantalla?>"></td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td  width=250>".$la_empresa["nomestpro1"]."</td>";
print "<td  width=150>".$la_empresa["nomestpro2"]."</td>";
print "<td  width=50>".$la_empresa["nomestpro3"]."</td>";
print "<td  width=50>".$la_empresa["nomestpro4"]."</td>";
print "<td  width=50>C�digo </td>";
print "<td  width=250>Denominaci�n</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$as_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
	$as_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
	$as_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
	$ls_sql =" SELECT ep5.codestpro1,ep5.codestpro2,ep5.codestpro3,ep5.codestpro4,ep5.codestpro5,ep5.denestpro5,ff.codfuefin,ff.denfuefin".
	         " FROM spg_ep5 as ep5, tepuy_fuentefinanciamiento as ff ".
			 " WHERE ep5.codfuefin= ff.codfuefin".
			 " AND ep5.codemp='".$ls_codemp."'  AND  ep5.codestpro1 like '%".$as_codestpro1."%' AND ep5.codestpro2 like '%".$as_codestpro2."%'        AND  ".
			 " ep5.codestpro3 like '%".$as_codestpro3."%' AND ep5.codestpro4 like '%".$ls_codestpro4."%' AND ep5.codestpro5 like '%".$ls_codestpro5."%' AND  ".
			 " ep5.denestpro5 like '%".$ls_denestpro5."%'  ";
	//print($ls_sql);
	$rs_data = $io_sql->select($ls_sql);
	$data    = $rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data     = $io_sql->obtener_datos($rs_data);
		$arrcols  = array_keys($data);
		$totcol   = count($arrcols);
		$ds->data = $data;
		$totrow   = $ds->getRowCount("codestpro5");
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codestpro1 = $data["codestpro1"][$z];
			$ls_codestpro2 = $data["codestpro2"][$z];
			$ls_codestpro3 = $data["codestpro3"][$z];
			if ($li_estmodest=='2')
			   {
				 $ls_codestpro1 = substr($ls_codestpro1,18,2);
				 $ls_codestpro2 = substr($ls_codestpro2,4,2);
				 $ls_codestpro3 = substr($ls_codestpro3,1,2); 
			   }
			$ls_codestpro4 = $data["codestpro4"][$z];
			$ls_codestpro5 = $data["codestpro5"][$z];
			$ls_denestpro5 = $data["denestpro5"][$z];
			$ls_codfuefin = $data["codfuefin"][$z];
			$ls_denfuefin = $data["denfuefin"][$z];
			print "<td width=250 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin');\">".trim($ls_codestpro1)."</td>";
			print "<td width=150 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin');\">".trim($ls_codestpro2)."</td>";
			print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin');\">".trim($ls_codestpro3)."</td>";
			print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin');\">".trim($ls_codestpro4)."</a></td>";
			print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin');\">".trim($ls_codestpro5)."</a></td>";
			print "<td width=250 align=\"left\">".trim($ls_denestpro5)."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido ".$la_empresa["nomestpro5"]);
		print "<script language=JavaScript>";
		print " close();";
		print "<script>";
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
function aceptar(ls_codestpro5,ls_denestpro5,ls_codfuefin,ls_denfuefin,ls_pantalla)
{
	fop                        = opener.document.form1;
	fop.txtcodestpro5.value    = ls_codestpro5;
	fop.txtcodestpro5.readOnly = true;
	fop.txtdenestpro5.value    = ls_denestpro5; 
    ls_maestro                 = fop.hidmaestro.value; 
	ls_pantalla                = document.form1.txtpantalla.value
	if(ls_pantalla=='d_estprog5')
	{
		fop.txtcodigo.value     = ls_codfuefin;
		fop.txtcodigo.readOnly  = true;
		fop.txtdenominacion.value    = ls_denfuefin;
	
	
	}
	if (ls_maestro=='Y')
	   {
	     fop.opeestpro5.value  = "BUSCAR";
	     fop.statusprog5.value = 'C';
	   }
	fop.txtdenestpro5.focus(); 
	close();
}
  
function ue_search()
{
	f                 = document.form1;
	f.operacion.value = "BUSCAR";
	f.action          = "tepuy_spg_cat_estpro5.php";
	f.submit();
}
</script>
</html>