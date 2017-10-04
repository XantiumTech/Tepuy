<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Clientes</title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Clientes </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Cedula</div></td>
        <td width="431" height="22"><div align="left">
          <input name="cedula" type="text" id="cedula">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Apellidos</div></td>
        <td height="22"><div align="left">
          <input name="apellido" type="text" id="apellido">
        </div></td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar Cliente</a></div></td>
      </tr>
    </table>
	<br>
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$msg=new class_mensajes();
$io_sql=new class_sql($con);
$ds=new class_datastore();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cedula="%".$_POST["cedula"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
	$ls_apellido="%".$_POST["apellido"]."%";
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cédula </td>";
print "<td>Nombre del Cliente</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT cedcli,nomcli,apecli FROM sfa_cliente".
			" WHERE cedcli like '".$ls_cedula."'".
			" AND nomcli like '".$ls_nombre."' ".
			" AND apecli like '".$ls_apellido."' ".
			" order by cedcli";
	//print $ls_sql;
	$rs_cta=$io_sql->select($ls_sql);	
	
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		/*while($row=$io_sql->fetch_row($rs_cta))
		{
			$ls_codpro=$row["cod_pro"];
			if($ls_codpro!="----------")
			{
				print "<tr class=celdas-blancas>";
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
				print "<td>".$row["nompro"]."</td>";
				print "</tr>";					
			}
		}*/		
		$data=$io_sql->obtener_datos($rs_cta);
		$ds->data=$data;
		$li_rows=$ds->getRowCount("cedcli");
		for($li_index=1;$li_index<=$li_rows;$li_index++)
			{
			    $ls_cedcli=$data["cedcli"][$li_index];
				$ls_nomcli=$data["nomcli"][$li_index]." ".$data["apecli"][$li_index];
				print "<tr class=celdas-blancas>";				
				print "<td><a href=\"javascript: aceptar('$ls_cedcli','$ls_nomcli');\">".$ls_cedcli."</a></td>";
				print "<td>".$ls_nomcli."</td>";
				print "</tr>";					
			
			}	
	}
	else
	{
		$msg->message("No existen registros");
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
  function aceptar(cedcli,nomcli)
  {
    opener.document.form1.txtcedcli.value=cedcli;
    opener.document.form1.txtnomcli.value=nomcli;
	//opener.buscar();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_catdinamic_cli.php";
  f.submit();
  }
</script>
</html>
