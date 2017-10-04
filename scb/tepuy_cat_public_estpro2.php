<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");

$io_include   = new tepuy_include();
$ls_conect    = $io_include->uf_conectar();
$io_msg       = new class_mensajes();
$io_sql       = new class_sql($ls_conect);
$la_empresa	  = $_SESSION["la_empresa"];
$ls_codemp	  = $la_empresa["codemp"];
$ls_estmodest = $la_empresa["estmodest"];

if (array_key_exists("operacion",$_POST)) 
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
   }
else 
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["codestpro1"];
	 $ls_denestpro1 = $_GET["denestpro1"];
	 $ls_codestpro2 = "";
	 $ls_denestpro2 = "";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de <?php print $la_empresa["nomestpro2"] ?></title>
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
  	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de <?php print $la_empresa["nomestpro2"] ?></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="137" height="22" style="text-align:right"><?php print $la_empresa["nomestpro1"]?></td>
        <td width="461" height="22">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="50" value="<?php print $ls_denestpro1 ?>" readonly style="text-align:left" title="<?php print $ls_denestpro1 ?>">
        </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo</td>
        <td height="22"><input name="txtcodestpro2" type="text" id="txtcodestpro2" size="22" maxlength="6"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdenestpro2" type="text" id="txtdenestpro2" size="72" maxlength="100" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
      </tr>
  </table>
	 <p align="center">
	   <?php
echo "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=150 style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
echo "<td width=100 style=text-align:center>C&oacute;digo</td>";
echo "<td width=300 style=text-align:center>Denominaci&oacute;n</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_codestpro1 = str_pad($ls_codestpro1,20,0,0);
	 $ls_sql="SELECT codestpro1,codestpro2,denestpro2 
	            FROM spg_ep2 
			   WHERE codemp='".$ls_codemp."'
			     AND codestpro1 ='".$ls_codestpro1."'
				 AND codestpro2 like '%".$ls_codestpro2."%'
				 AND denestpro2 like '%".$ls_denestpro2."%'";
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
					   $ls_codestpro1 = $row["codestpro1"];
					   $ls_codestpro2 = $row["codestpro2"];
					   if ($_SESSION["la_empresa"]["estmodest"]==2)
					      {
						    $ls_codestpro1 = substr($ls_codestpro1,-2);
					        $ls_codestpro2 = substr($ls_codestpro2,-2);
						  }
					   $ls_denestpro2 = $row["denestpro2"];
					   echo "<td width=150 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2');\">".trim($ls_codestpro1)."</td>";
					   echo "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2');\">".trim($ls_codestpro2)."</a></td>";
					   echo "<td width=300 style=text-align:center>".trim($ls_denestpro2)."</td>";
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
  function aceptar(ls_codestpro2,ls_denestpro2)
  {
    ls_opener = opener.document.form1.id;
	if (ls_opener=='tepuy_scb_r_mayor_presupuestario')
	   {
		 opener.document.form1.txtcodestpro2.value = ls_codestpro2;
		 opener.document.form1.txtdenestpro2.value = ls_denestpro2;
	   }
	else
	   {
		 opener.document.form1.denestpro2.value = ls_denestpro2;
		 opener.document.form1.codestpro2.value = ls_codestpro2;
		 opener.document.form1.denestpro3.value = "";
		 opener.document.form1.codestpro3.value = "";
		 if ("<?php print $ls_estmodest;?>"==2)
		    {
			  opener.document.form1.codestpro4.value="";
			  opener.document.form1.denestpro4.value="";
			  opener.document.form1.codestpro5.value="";
			  opener.document.form1.denestpro5.value="";
	 	    }
	   }
    close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_public_estpro2.php";
  f.submit();
  }
</script>
</html>
