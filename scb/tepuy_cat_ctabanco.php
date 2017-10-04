<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
<form name="form1" method="post" action="">
<?php

require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("tepuy_c_cuentas_banco.php");
$in		    = new tepuy_include();
$con	    = $in->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($con);
$io_funcion = new class_funciones();
$ls_codemp  = $arr["codemp"];
$io_ctaban  = new tepuy_c_cuentas_banco();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = $_POST["codigo"];
	 $ls_nomban       = $_POST["hidnomban"];
	 $ls_ctaban       = "%".$_POST["cuenta"]."%";
	 $ls_denominacion = "%".$_POST["denominacion"]."%";
   }
else
   {
	 $ls_operacion    = "BUSCAR";
	 $ls_codigo       = $_GET["codigo"];
	 $ls_nomban       = $_GET["hidnomban"];
	 $ls_ctaban       = "%%";
	 $ls_denominacion = "%%";
   }
?>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codigo;?>">
          <input name="hidnomban" type="hidden" id="hidnomban" value="<?php print $ls_nomban ?>">
          Cat&aacute;logo de Cuentas <?php print $ls_nomban ?></td>
    </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">Cuenta</td>
        <td width="431" height="22" style="text-align:left"><input name="cuenta" type="text" id="cuenta" size="35" maxlength="25" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="60"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></td>
      </tr>
  </table>
	 <p align="center">
<?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación Cta. Contable</td>";
print "<td>Apertura</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT scb_ctabanco.ctaban as ctaban, scb_ctabanco.dencta as dencta, scb_ctabanco.sc_cuenta as sc_cuenta,
					   scg_cuentas.denominacion as denominacion, scb_ctabanco.codban as codban, scb_banco.nomban as nomban,
					   scb_ctabanco.codtipcta as codtipcta, scb_tipocuenta.nomtipcta as nomtipcta, scb_ctabanco.fecapr as fecapr,
					   scb_ctabanco.feccie as feccie, scb_ctabanco.estact as estact
				  FROM scb_ctabanco, scb_tipocuenta, scb_banco, scg_cuentas
			     WHERE scb_ctabanco.codemp='".$ls_codemp."'
				   AND scb_ctabanco.codban like '%".$ls_codigo."%'
				   AND scb_ctabanco.ctaban like '".$ls_ctaban."'
				   AND scb_ctabanco.dencta like '".$ls_denominacion."'
			       AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta
				   AND scb_ctabanco.codemp=scb_banco.codemp
				   AND scb_ctabanco.codban=scb_banco.codban
				   AND scb_ctabanco.codemp=scg_cuentas.codemp		    
				   AND scb_ctabanco.sc_cuenta=scg_cuentas.sc_cuenta";
	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	   }
 	else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while ($row=$io_sql->fetch_row($rs_data))
			        {
					  print "<tr class=celdas-blancas>";
					  $ls_codban 	  = $row["codban"];
					  $ls_nomban 	  = $row["nomban"];
					  $ls_ctaban      = $row["ctaban"];
					  $ls_dencta      = $row["dencta"];
					  $ls_codtipcta   = $row["codtipcta"];
					  $ls_nomtipcta   = $row["nomtipcta"];
					  $ls_ctascg      = trim($row["sc_cuenta"]);
					  $ls_denctascg   = $row["denominacion"];
					  $ls_fecapertura = $io_funcion->uf_convertirfecmostrar($row["fecapr"]);
					  $ls_feccierre   = $io_funcion->uf_convertirfecmostrar($row["feccie"]);
					  $lb_valido      = $io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$adec_saldo);
					  if (!$lb_valido)
						 {
						   $io_msg->message($io_ctaban->is_msg_error);
						 }
					  $ldec_saldo = $adec_saldo;
					  $ls_status  = $row["estact"];
					  print "<td><a href=\"javascript: aceptar('$ls_ctaban','$ls_dencta','$ls_ctascg','$ls_codtipcta','$ls_nomtipcta','$ldec_saldo');\">".$ls_ctaban."</a></td>";
					  print "<td>".$ls_dencta."</td>";
					  print "<td>".$ls_nomban."</td>";
					  print "<td>".$ls_nomtipcta."</td>";
					  print "<td>".$ls_ctascg."</td>";
					  print "<td>".$ls_denctascg."</td>";																			
					  print "<td>".$ls_fecapertura."</td>";					
					  print "</tr>";			
					}
			} 
	     else
		    {
			  $io_msg->message("No se han Creado Cuentas Bancarias !!!");
			}
		}
}
print "</table>";
?>
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_ctaban,ls_dencta,ls_scgcta,ls_codtipcta,ls_nomtipcta,ld_saldo)
{
  opener.document.form1.txtcuenta.value		   = ls_ctaban;
  opener.document.form1.txtdenominacion.value  = ls_dencta;
  opener.document.form1.txttipocuenta.value    = ls_codtipcta;
  opener.document.form1.txtdentipocuenta.value = ls_nomtipcta;
  opener.document.form1.txtcuenta_scg.value    = ls_scgcta;
  opener.document.form1.txtdisponible.value    = uf_convertir(ld_saldo);	
  close();
}
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_ctabanco.php";
  f.submit();
  }
</script>
</html>