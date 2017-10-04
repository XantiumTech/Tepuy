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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Personal Responsable</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
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
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="383" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="6" class="titulo-celda">Cat&aacute;logo de Personal Responsable 
      <input name="operacion" type="hidden" id="operacion" /></td>
    </tr>
    <tr>
      <td width="55">&nbsp;</td>
      <td width="74">&nbsp;</td>
      <td width="65">&nbsp;</td>
      <td width="65">&nbsp;</td>
      <td width="59">&nbsp;</td>
      <td width="63">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">C&eacute;dula</td>
      <td height="22" colspan="2"><label>
        <input name="txtcedper" type="text" id="txtcedper" size="15" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789'); " style="text-align:center" />
      </label></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nombre</td>
      <td height="22" colspan="5"><label>
        <input name="txtnomper" type="text" id="txtnomper" size="55" maxlength="254" style="text-align:left" />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Apellido</td>
      <td height="22" colspan="5"><label>
        <input name="txtapeper" type="text" id="txtapeper" style="text-align:left" size="55" maxlength="254" />
      </label></td>
    </tr>
    <tr style="text-align:right">
      <td height="22" colspan="6"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0" />Buscar</a></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p align="center">
    <?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
   }
else
   {
     $ls_operacion = "BUSCAR";
   }
if ($ls_operacion=='BUSCAR')
   {
	 print "<table width=450 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td style=text-align:center width=100>Cédula</td>";
	 print "<td style=text-align:center width=350>Nombre</td>";
	 print "</tr>";

     if (array_key_exists("operacion",$_POST))
	    {
		  $ls_cedper = $_POST["txtcedper"];
		  $ls_nomper = $_POST["txtnomper"];
		  $ls_apeper = $_POST["txtapeper"];		
		}
	 else
	    {
		  $ls_cedper = "";
		  $ls_nomper = "";
		  $ls_apeper = "";
		}
	 $ls_sql = "SELECT DISTINCT max(CASE sno_nomina.racnom WHEN 1 THEN
					   sno_personalnomina.codcar ELSE sno_cargo.codcar END) AS codcar,
					   (SELECT nomper FROM sno_personal
						 WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,
					   (SELECT apeper FROM sno_personal
						 WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,
					   (SELECT cedper FROM sno_personal
						 WHERE sno_personal.codper=sno_personalnomina.codper) as cedper,sno_cargo.descar
				  FROM sno_personalnomina, sno_nomina, sno_cargo,sno_asignacioncargo,sno_personal
				 WHERE sno_personal.cedper LIKE '%".$ls_cedper."%'
				   AND UPPER(sno_personal.nomper) LIKE '%".strtoupper($ls_nomper)."%'
				   AND UPPER(sno_personal.apeper) LIKE '%".strtoupper($ls_apeper)."%'
				   AND sno_nomina.espnom=0
				   AND sno_personalnomina.codemp = sno_nomina.codemp
				   AND sno_personalnomina.codnom = sno_nomina.codnom
				   AND sno_personalnomina.codper = sno_personal.codper
				   AND sno_personalnomina.codemp = sno_cargo.codemp
				   AND sno_personalnomina.codnom = sno_cargo.codnom
				   AND sno_personalnomina.codcar = sno_cargo.codcar
				   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp
				   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom
				   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar
				 GROUP BY sno_personalnomina.codper,sno_nomina.racnom,sno_asignacioncargo.denasicar,sno_cargo.descar,codclavia
				 ORDER BY cedper ASC";
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
					   $ls_cedper = trim($row["cedper"]);
					   $ls_nomper = rtrim($row["nomper"]);
					   $ls_apeper = rtrim($row["apeper"]);
					   if (!empty($ls_apeper))
					      {
						    $ls_nomper = $ls_apeper.', '.$ls_nomper;
						  }
					   $ls_nomcar = $row["descar"];
					   echo "<td width=100 style=text-align:center><a href=\"javascript: uf_aceptar('$ls_cedper','$ls_nomper','$ls_nomcar');\">".$ls_cedper."</a></td>";
					   echo "<td width=350 style=text-align:left title='".$ls_nomper."'>".$ls_nomper."</td>";
	                   echo "</tr>";
					 }
			 }
		  else
		     {
			   $io_msg->message("No se encontraron registros !!!");
			 }
		}
   }
?>
  </p>
</form>
</body>
<script language="javascript">
f = document.form1;
function ue_buscar()
{
  f.operacion.value = 'BUSCAR';
  f.action = "tepuy_scb_cat_personal_responsable.php";
  f.submit();
}

function uf_aceptar(as_cedres,as_nomres,as_nomcar)
{
  fop = opener.document.form1;
  fop.txtcedres.value = as_cedres;
  fop.txtnomres.value = as_nomres;
  fop.hidnomcar.value = as_nomcar;
  close();
}
</script>
</html>