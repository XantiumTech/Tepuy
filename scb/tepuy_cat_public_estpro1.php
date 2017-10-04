<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de <?php print $_SESSION["la_empresa"]["nomestpro1"] ?></title>
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
  <p align="center">&nbsp;</p>
  	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda" style="text-align:center"><input name="operacion" type="hidden" id="operacion">Cat&aacute;logo de <?php print $_SESSION["la_empresa"]["nomestpro1"] ?></td>
       </tr>
      <tr>
        <td style="text-align:right">&nbsp;</td>
        <td style="text-align:left">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="431" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" style="text-align:left" size="75" maxlength="254"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
      </tr>
    </table>
	 <p>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");
$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg		= new class_mensajes();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["codigo"];
	 $ls_denestpro1 = $_POST["denominacion"];
   }
else
   {
     $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
   }
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=150 style=text-align:center>C&oacute;digo</td>";
print "<td width=400 style=text-align:left>Denominaci&oacute;n</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT codestpro1,denestpro1 
	              FROM spg_ep1 
				 WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'
				   AND codestpro1 like '%".$ls_codestpro1."%'
				   AND denestpro1 like '%".$ls_denestpro1."%'";
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
					   $ls_codestpro1 = trim($row["codestpro1"]);
					   $ls_denestpro1 = $row["denestpro1"];
					   if ($_SESSION["la_empresa"]["estmodest"]==2)
						  {
						    echo "<td width=150 style=text-align:center><a href=\"javascript: aceptar('".substr($ls_codestpro1,-2)."','$ls_denestpro1');\">".substr($ls_codestpro1,-2)."</a></td>";
						  }
					   else
						  {
							echo "<td width=150 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1');\">".$ls_codestpro1."</a></td>";
						  }
					   echo "<td width=400 style=text-align:left>".$ls_denestpro1."</td>";
					   echo "</tr>";			
					 }
			 } 
		  else
		     {
			   $io_msg->message("No se han definido Estructuras para este Nivel !!!");
			 }
		}
   }
echo "</table>";	
?>
    </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codestpro1,ls_denestpro1)
  {
    ls_opener = opener.document.form1.id;
	if (ls_opener!='tepuy_scb_r_mayor_presupuestario')
	   {
		 opener.document.form1.codestpro1.value = ls_codestpro1;
		 opener.document.form1.denestpro1.value = ls_denestpro1;
		 opener.document.form1.codestpro2.value = "";
		 opener.document.form1.denestpro2.value = "";
		 opener.document.form1.codestpro3.value = "";
		 opener.document.form1.denestpro3.value = "";
	     if ("<?php $_SESSION["la_empresa"]["estmodest"];?>"==2)
	        {
			  opener.document.form1.codestpro4.value = "";
			  opener.document.form1.denestpro4.value = "";
			  opener.document.form1.codestpro5.value = "";
			  opener.document.form1.denestpro5.value = "";
	        }
	   }
	else
	   {
		 opener.document.form1.txtcodestpro1.value = ls_codestpro1;
		 opener.document.form1.txtdenestpro1.value = ls_denestpro1;
	   }
	close();
  }
  
function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_public_estpro1.php";
  f.submit();
}
</script>
</html>