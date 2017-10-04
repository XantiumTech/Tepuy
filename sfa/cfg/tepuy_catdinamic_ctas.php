<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="122" height="22" align="right">Cuenta</td>
        <td width="238" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="35" maxlength="25" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="60" maxlength="254" style="text-align:left">
<label></label>
<br>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td height="22" colspan="2" align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
      </tr>
    </table>
	<br>
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_ctascg    = $_POST["codigo"];
	 $ls_denctascg = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion = "";
	 $ls_ctascg    = "";
	 $ls_denctascg = "";
}
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center  width=120>Cuenta Contable</td>";
echo "<td style=text-align:center  width=380>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     require_once("../../shared/class_folder/class_sql.php");
	 require_once("../../shared/class_folder/tepuy_include.php");
	 require_once("../../shared/class_folder/class_mensajes.php");
	 $io_include = new tepuy_include();
	 $ls_conect  = $io_include->uf_conectar();
	 $io_sql     = new class_sql($ls_conect);
	 $io_msg     = new class_mensajes();	 
	 
	 $ls_sql = "SELECT sc_cuenta, denominacion, status
			      FROM scg_cuentas
                 WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				   AND sc_cuenta like '".$ls_ctascg."%'
				   AND denominacion like '%".$ls_denctascg."%'
				 ORDER BY sc_cuenta";
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
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_ctascg    = trim($row["sc_cuenta"]);
					  $ls_denctascg = $row["denominacion"];
					  $ls_estctascg = $row["status"];
					  if ($ls_estctascg=="S")
					     {
						   echo "<tr class=celdas-blancas>";
						   echo "<td style=text-align:center>".$ls_ctascg."</td>";
						 }
					  else
					     {
						   echo "<tr class=celdas-azules>";
						   echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_ctascg','$ls_denctascg','$ls_estctascg');\">".$ls_ctascg."</a></td>";
						 }
					  echo "<td style=text-align:left>".$ls_denctascg."</td>";
					  echo "</tr>";
					}
             }
		  else
		     {
			   $io_msg->message("No se han definido Cuentas Contables !!!");			 
			 }
		}
     //$io_sql->close();
   }
echo "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(cuenta,denominacion,status)
{
	opener.document.form1.txtcontable.value=cuenta;
	opener.document.form1.txtcontable.readOnly=true;
	opener.document.form1.txtdencontable.value=denominacion;
	close();
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_catdinamic_ctas.php";
	f.submit();
}
</script>
</html>
