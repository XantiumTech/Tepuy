<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Chequeras</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
    <input name="operacion" type="hidden" id="operacion"></p>
  	 <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="21" colspan="2" class="titulo-celda">Cat&aacute;logo de Chequeras</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="104" height="22" style="text-align:right">Cuenta</td>
        <td width="494" height="22" style="text-align:left"><input name="cuenta" type="text" id="cuenta" style="text-align:center" size="35" maxlength="25" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú '+':;_#/%*-,.+(){}[]='); "></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú '+'¡!:;_°#@/?¿%&$*-,.+(){}[]='); " style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Banco</td>
        <td height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="3" onKeyPress="return keyRestrict(event,'1234567890'); "></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></td>
      </tr>
  </table>
<div align="center"><br>
<?php
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = "%".$_POST["codigo"]."%";
	 $ls_cuenta    = "%".$_POST["cuenta"]."%";
	 $ls_denctaban = $_POST["denominacion"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_codban    = "";
	 $ls_cuenta    = "";
	 $ls_denctaban = "";
   }
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=65>Chequera</td>";
print "<td style=text-align:center width=180>Banco</td>";
print "<td style=text-align:center width=155>Cuenta</td>";
print "<td style=text-align:center width=200>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT DISTINCT (scb_cheques.numchequera) as numchequera,
					   scb_cheques.codban as codban,
					   scb_cheques.ctaban as ctaban ,
					   scb_banco.nomban as nomban,
					   scb_ctabanco.dencta as dencta,
					   scb_tipocuenta.codtipcta as codtipcta,
					   scb_tipocuenta.nomtipcta as nomtipcta
			      FROM scb_cheques, scb_banco, scb_ctabanco, scb_tipocuenta
			     WHERE scb_cheques.codemp = '".$ls_codemp."'
				   AND scb_cheques.codban like '".$ls_codban."' 
				   AND scb_cheques.ctaban like '".$ls_cuenta."'
				   AND scb_ctabanco.dencta like '%".$ls_denctaban."%'
				   AND scb_cheques.codemp=scb_banco.codemp 
				   AND scb_cheques.codban=scb_banco.codban 
				   AND scb_cheques.codemp=scb_ctabanco.codemp  
				   AND scb_banco.codban=scb_ctabanco.codban
				   AND scb_cheques.ctaban=scb_ctabanco.ctaban
				   AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	 	{
		  $io_msg("Error en select");
		}
	 else
		{
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
		       while ($row=$io_sql->fetch_row($rs_data))
				     {
					   echo "<tr class=celdas-blancas>";
					   $codban	    = trim($row["codban"]);
					   $nomban	    = $row["nomban"];
					   $ctaban	    = trim($row["ctaban"]);
					   $dencta	    = $row["dencta"];
					   $codtipcta   = trim($row["codtipcta"]);
					   $nomtipcta   = $row["nomtipcta"];
					   $numchequera = trim($row["numchequera"]);
					   echo "<td style=text-align:center width=65><a href=\"javascript: aceptar('$numchequera','$codban','$nomban','$ctaban','$dencta','$codtipcta','$nomtipcta');\">".$numchequera."</a></td>";
					   echo "<td style=text-align:left   width=180>".$nomban."</td>";
					   echo "<td style=text-align:center width=155>".$ctaban."</td>";
					   echo "<td style=text-align:left   width=200>".$dencta."</td>";
					   echo "</tr>";			
					 }
			 } 
		  else
		     {
			   $io_msg->message("No se han definido Chequeras !!!");
			 }
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
function aceptar(numchequera,codban,nomban,ctaban,dencta,codtipcta,nomtipcta)
{
  opener.document.form1.txtchequera.value      = numchequera;
  opener.document.form1.txttipocuenta.value    = codtipcta;
  opener.document.form1.txtdentipocuenta.value = nomtipcta;
  opener.document.form1.txtcodban.value        = codban;
  opener.document.form1.txtdenban.value        = nomban;
  opener.document.form1.txtcuenta.value        = ctaban;
  opener.document.form1.txtdenominacion.value  = dencta;
  opener.document.form1.status.value           = 'G';
  opener.document.form1.operacion.value        = 'CARGAR';
  opener.document.form1.submit();
  close();
}
  
function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_scb_cat_cheques.php";
  f.submit();
}
</script>
</html>