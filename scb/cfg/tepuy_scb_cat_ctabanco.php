<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Bancarias</title>
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
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
       <tr>
         <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Bancarias</td>
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
        <td height="22" style="text-align:right">Banco</td>
        <td height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");

$io_include  = new tepuy_include();
$ls_conect   = $io_include->uf_conectar();
$io_msg      = new class_mensajes();
$io_sql		 = new class_sql($ls_conect);
$io_function = new class_funciones();
$ls_codemp   = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion	  = $_POST["operacion"];
	 $ls_codigo		  = "%".$_POST["codigo"]."%";
	 $ls_ctaban		  = "%".$_POST["cuenta"]."%";
	 $ls_denominacion = "%".$_POST["denominacion"]."%"; 
   }
else
   {
	 $ls_operacion	  = "BUSCAR";
	 $ls_codigo		  = "%%";
	 $ls_ctaban		  = "%%";
	 $ls_denominacion = "%%";
   }
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación Cta. Contable</td>";
print "<td>Apertura</td>";
print "<td>Cierre</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT a.ctaban as ctaban,a.dencta as dencta,a.sc_cuenta as sc_cuenta,d.denominacion as denominacion,
	                   a.codban as codban,c.nomban as nomban,a.codtipcta as codtipcta,b.nomtipcta as nomtipcta,a.fecapr as fecapr,
					   a.feccie as feccie,a.estact as estact, a.ctabanext as ctabanext
			      FROM scb_ctabanco a,scb_tipocuenta b,scb_banco c,scg_cuentas d 
			     WHERE a.codemp='".$ls_codemp."' 
			       AND a.codtipcta=b.codtipcta 
				   AND a.codban=c.codban 
				   AND a.codban like '".$ls_codigo."'  
				   AND a.ctaban like '".$ls_ctaban."'
			       AND a.sc_cuenta=d.sc_cuenta 
			       AND a.codemp=d.codemp";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
		{
		  $io_msg->message($io_function->uf_convertirmsg($io_sql->message));
		}
	 else
		{
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
		       while ($row=$io_sql->fetch_row($rs_data))
				     {
					   echo "<tr class=celdas-blancas>";
					   $codban		= $row["codban"];
					   $nomban		= $row["nomban"];
					   $ctaban		= $row["ctaban"];
					   $dencta		= $row["dencta"];
					   $codtipcta   = $row["codtipcta"];
					   $nomtipcta	= $row["nomtipcta"];
					   $ctabanext	= $row["ctabanext"];
					   $ctascg		= $row["sc_cuenta"];
					   $denctascg   = $row["denominacion"];
					   $fecapertura = $io_function->uf_convertirfecmostrar($row["fecapr"]);
					   $feccierre   = $io_function->uf_convertirfecmostrar($row["feccie"]);
					   $statuscta	= $row["estact"];
					   echo "<td><a href=\"javascript: aceptar('$codban','$nomban','$ctaban','$dencta','$ctascg','$denctascg','$fecapertura','$feccierre','$statuscta','$codtipcta','$nomtipcta','$ctabanext');\">".$ctaban."</a></td>";
					   echo "<td>".$dencta."</td>";
					   echo "<td>".$nomban."</td>";
					   echo "<td>".$nomtipcta."</td>";
					   echo "<td>".$ctascg."</td>";
					   echo "<td>".$denctascg."</td>";																			
					   echo "<td>".$fecapertura."</td>";					
					   echo "<td>".$feccierre."</td>";					
					   echo "</tr>";			
					 }
			 } 
		  else
		     {
			   $io_msg->message("No se han definido cuentas para el Banco seleccionado !!!");
			 }
		}
   }
echo "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,statuscta,codtipcta,nomtipcta,ctabanext)
  {
    opener.document.form1.txtcodigo.value=ctaban;
    opener.document.form1.txtdencta.value=dencta;
	opener.document.form1.txttipocuenta.value=codtipcta;
	opener.document.form1.txtdentipocuenta.value=nomtipcta;
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtdenban.value=nomban;
	opener.document.form1.txtcuentacontable.value=ctascg;
	opener.document.form1.txtdencuenta.value=denctascg;
	opener.document.form1.txtfechaapertura.value=fecapertura;
	opener.document.form1.txtfechacierre.value=feccierre;
	opener.document.form1.txtctaext.value=ctabanext;
	opener.document.form1.status.value='C';
	if(statuscta==1)
	{
		opener.document.form1.statuscta.checked=true;
	}
	else
	{
		opener.document.form1.statuscta.checked=false;
	}
	
	opener.document.form1.txtcodigo.readOnly=true;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_scb_cat_ctabanco.php";
  f.submit();
  }
</script>
</html>