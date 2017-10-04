<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos</title>
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
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Bancos</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="3">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
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

$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg		= new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
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
print "<td style=text-align:center width=100>Código</td>";
print "<td style=text-align:center width=400>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT codban,nomban,gerban,dirban,telban,movcon,conban 
	              FROM scb_banco 
				 WHERE codemp='".$ls_codemp."' 
				   AND codban like '".$ls_codigo."' 
				   AND nomban like '".$ls_denominacion."'
				   AND codban<>'---' 
				 ORDER BY codban ";
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
					   echo "<tr class=celdas-blancas>";
					   $codigo		 = $row["codban"];
					   $denominacion = $row["nomban"];
					   $gerente		 = $row["gerban"];
					   $direccion 	 = $row["dirban"];
					   $telefono  	 = $row["telban"];
					   $celular   	 = $row["movcon"];
					   $email		 = $row["conban"];
					   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$codigo','$denominacion','$direccion','$gerente','$telefono','$celular','$email');\">".$codigo."</a></td>";
					   echo "<td style=text-align:left width=400>".$denominacion."</td>";
					   echo "</tr>";			
					 }
			 } 
		  else
		     {
			   $io_msg->message("No se han definido Bancos !!!");
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
  function aceptar(ls_codban,ls_denban)
  {
    opener.document.form1.txtcodban.value = ls_codban;
    opener.document.form1.txtdenban.value = ls_denban;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_bancos.php";
  f.submit();
  }
</script>
</html>