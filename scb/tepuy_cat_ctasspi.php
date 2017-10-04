<?
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_codscg	= $_POST["txtcuentascg"]."%";
}
else
{
	$ls_operacion="";
	$ls_codscg="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas de Ingreso</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Ingreso</div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right">Codigo</td>
        <td width="135" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="328" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2" style="text-align:left"><input name="nombre" type="text" id="nombre" size="72"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?

print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Ingreso</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
							
	$ls_cadena ="SELECT codemp,spi_cuenta,denominacion,status,sc_cuenta,(previsto+aumento-disminucion) as disponible 
				 FROM spi_cuentas 
		   		 WHERE codemp = '".$as_codemp."' AND spi_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' 
				 ORDER BY spi_cuenta";
	$rs_data = $SQL->select($ls_cadena);
	if($rs_data==false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		$li_numrows = $SQL->num_rows($rs_data);
		if ($li_numrows>0)
		   {
		     while ($row=$SQL->fetch_row($rs_data))
		           {
				     $cuenta       = $row["spi_cuenta"];
					 $denominacion = $row["denominacion"];
					 $scgcuenta    = $row["sc_cuenta"];
					 $status       = $row["status"];
					 $disponible   = $row["disponible"];
					 if ($status=="S")
				        {
						  print "<tr class=celdas-blancas>";
						  print "<td>".$cuenta."</td>";
						  print "<td  align=left>".$denominacion."</td>";
						  print "<td  align=center>".$scgcuenta."</td>";
						  print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
				        }
					 else
				        {
						  print "<tr class=celdas-azules>";
						  print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$status');\">".$cuenta."</a></td>";
						  print "<td  align=left>".$denominacion."</td>";
						  print "<td  align=center>".$scgcuenta."</td>";
						  print "<td  align=center>".number_format($disponible,2,",",".")."</td>";				
				        }
				     print "</tr>";			
			       }
 		     $SQL->free_result($rs_data);
			 $SQL->close();
		   }
		else
		   {
			 ?>
			 <script language="JavaScript">
			 alert("No se han creado Cuentas.....");
			 close();
			 </script>
			 <?
		   }
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
  function aceptar(cuenta,deno,scgcuenta,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.cuenta_ingreso.value=scgcuenta;
  //	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_ctasspi.php";
	  f.submit();
  }	
</script>
</html>
