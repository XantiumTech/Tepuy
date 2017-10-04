<?php
session_start();
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos("../");				
$li_row=$fun_activos->uf_obtenervalor_get("row","");
if($li_row=="")
{
	$li_row=$fun_activos->uf_obtenervalor("hidrow","");
}
$operacion=$fun_activos->uf_obteneroperacion();
$ls_destino=$fun_activos->uf_obtenervalor_get("destino","");
if($operacion=="BUSCAR")
{$ls_destino=$fun_activos->uf_obtenervalor("destino","");}

	//-----------------------------------------------------------------------------------------------------------------------------------
	// Función que obtiene e imprime los resultados de la busqueda
	function uf_imprimirresultados($as_coduniadm,$as_denuniadm,$ls_destino)
   	{
		require_once("../shared/class_folder/tepuy_include.php");
		$in=new tepuy_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		$ds=new class_datastore();
   		require_once("../shared/class_folder/class_funciones.php");
		$fun=new class_funciones();				
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=40>Código</td>";
		print "<td width=120>Denominación</td>";
		print "</tr>";
		$ls_sql="SELECT coduniadm, denuniadm".
		        "  FROM saf_unidadadministrativa".
				" WHERE codemp='".$ls_codemp."'".
				"   AND coduniadm like '".$as_coduniadm."'".
				"   AND denuniadm like '".$as_denuniadm."'".
				" ORDER BY coduniadm  ";
		$rs_per=$io_sql->select($ls_sql);
		$li_num=$io_sql->num_rows($rs_per);
		if($li_num>0)
		{
			while($row=$io_sql->fetch_row($rs_per))
			{
				print "<tr class=celdas-blancas>";
				$as_coduniadm=$row["coduniadm"];
				$as_denuniadm=$row["denuniadm"];
				switch ($ls_destino)
				{
					case "":
						print "<td><a href=\"javascript: aceptar('$as_coduniadm','$as_denuniadm');\">".$as_coduniadm."</a></td>";
						print "<td>".$as_denuniadm."</td>";
						print "</tr>";			
					break;
					case "activo":
						print "<td><a href=\"javascript: aceptar_activo('$as_coduniadm','$as_denuniadm');\">".$as_coduniadm."</a></td>";
						print "<td>".$as_denuniadm."</td>";
						print "</tr>";			
					break;
					case "reasignaciones":
						print "<td><a href=\"javascript: aceptar_reasignaciones('$as_coduniadm','$as_denuniadm');\">".$as_coduniadm."</a></td>";
						print "<td>".$as_denuniadm."</td>";
						print "</tr>";			
					break;
				}				
			}
		}
		print "</table>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidad Fisica</title>
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidad Fisica</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="20" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><input name="txtdenuniadm" type="text" id="txtdenuniadm" size="30"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left">
          <input name="hidrow" type="hidden" id="hidrow" value="<?php print $li_row; ?>">
          <input name="destino" type="hidden" id="destino" value="<?php print  $ls_destino; ?>">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	$ls_operacion=$fun_activos->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_coduniadm="%".$_POST["txtcoduniadm"]."%";
		$ls_denuniadm="%".$_POST["txtdenuniadm"]."%";

		uf_imprimirresultados($ls_coduniadm,$ls_denuniadm,$ls_destino);
	}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_coduniadm,ls_denuniadm)
{
	opener.document.form1.txtcoduniadm.value=ls_coduniadm;
	opener.document.form1.txtdenuniadm.value=ls_denuniadm;
	opener.document.form1.hidstatus.value="C";
	close();
}
function aceptar_activo(ls_coduniadm,ls_denuniadm)
{
	opener.document.form1.txtcoduni.value=ls_coduniadm;
	opener.document.form1.txtdenuni.value=ls_denuniadm;
	close();
}
function aceptar_reasignaciones(ls_coduniadm,ls_denuniadm)
{
	opener.document.form1.txtcoduniadm.value=ls_coduniadm;
	opener.document.form1.txtdenuniadm.value=ls_denuniadm;
	close();
}
function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="tepuy_saf_cat_unidadfisica.php";
  	f.submit();
}
</script>
</html>
