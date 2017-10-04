<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	echo "<script language=JavaScript>";
	echo "close();";
	echo "opener.document.form1.submit();";
	echo "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
  <p align="center">&nbsp;</p>
  	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Bancos</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="74" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="424" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" maxlength="3" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="70"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></td>
      </tr>
    </table>
	 <p align="center">
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = $_POST["codigo"];
	 $ls_nomban    = $_POST["denominacion"];
	 if (array_key_exists("procede",$_GET))
	    {
		  $ls_procede=$_GET["procede"];
	    }
	 else
	    {
		  $ls_procede='';
	    }
   }
else
   {
	 $ls_operacion="BUSCAR";
	 $ls_codban = "";
	 $ls_nomban = "";
	 if (array_key_exists("procede",$_GET))
	    {
		  $ls_procede=$_GET["procede"];
	    }
	 else
	    {
		  $ls_procede='';
	    }
   }
   
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=100 style=text-align:center>Código</td>";
echo "<td width=400 style=text-align:center>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql  = "SELECT codban, nomban
	               FROM scb_banco 
				  WHERE codemp = '".$ls_codemp."' 
				    AND codban like '%".$ls_codban."%' 
				    AND nomban like '%".$ls_nomban."%'
					AND codban<>'---'
			      ORDER BY codban ASC";
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
			           $ls_codban = trim($row["codban"]);
			           $ls_denban = $row["nomban"];
				       if (empty($ls_procede))
				          {
					        echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codban','$ls_denban');\">".$ls_codban."</a></td>";
				          }
				       else
				          {
					        echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar_aut('$ls_codban','$ls_denban');\">".$ls_codban."</a></td>";
				          }
				       echo "<td width=400 style=text-align:left>".$ls_denban."</td>";
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
?></p>
</form>
</body>
<script language="JavaScript">
  function aceptar(ls_codban,ls_nomban)
  {
    opener.document.form1.txtcodban.value		= ls_codban;
    opener.document.form1.txtdenban.value		= ls_nomban;
	opener.document.form1.txtcuenta.value		= "";
    opener.document.form1.txtdenominacion.value = "";
	close();
  }
  function aceptar_aut(ls_codban,ls_nomban)
  {
    opener.document.form1.codbanaut.value = ls_codban;
    opener.document.form1.nombanaut.value = ls_nomban;
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