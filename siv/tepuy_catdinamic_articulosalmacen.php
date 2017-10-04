<?php
	session_start();
  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=str_replace(".",",",$as_valor);
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$as_valor = substr($as_valor,0,$li_poscoma+3);
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	if(array_key_exists("almacen",$_GET))
	{
		$ls_coddestino=$_GET["coddestino"];
		$ls_dendestino=$_GET["dendestino"];
		$ls_codalmacen=$_GET["almacen"];
		//print "almacen:".$ls_codalmacen;
	}
	else
	{
		$ls_coddestino="txtcodart";
		$ls_dendestino="txtdenart";
	}

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codart="%".$_POST["txtcodart"]."%";
		$ls_denart="%".$_POST["txtdenart"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_coddestino=$_POST["hidcoddestino"];
		$ls_dendestino=$_POST["hiddendestino"];
	}
	else
	{
		$ls_operacion="";
	
	}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Art&iacute;culo</title>
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
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidcoddestino" type="hidden" id="hidcoddestino" value="<?php print $ls_coddestino ?>">
    <input name="hiddendestino" type="hidden" id="hiddendestino" value="<?php print $ls_dendestino ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Art&iacute;culo</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodart" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenart" type="text" id="txtdenart">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new tepuy_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	$io_fun =new class_funciones();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];


	if (array_key_exists("linea",$_GET))
	{
		$li_linea=$_GET["linea"];
	}
	else
	{
		if(array_key_exists("hidlinea",$_POST))
		{
			$li_linea=$_POST["hidlinea"];

		}
		else
		{
			$li_linea="";
		}
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>Código</td>";
	print "<td>Unidad de Medida</td>";
	print "<td>Denominacion</td>";
	print "<td>Existencia</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT a.*, b.existencia, c.denunimed, ".
				"      (SELECT dentipart FROM siv_tipoarticulo".
				"        WHERE siv_tipoarticulo.codtipart = a.codtipart) AS dentipart,".
				"      (SELECT denunimed FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed = a.codunimed) AS denunimed,".
				"      (SELECT unidad FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed = a.codunimed) AS unidad".
				"  FROM siv_articulo a,siv_articuloalmacen b, siv_unidadmedida c".
				" WHERE a.codemp = '".$ls_codemp."'".
				"   AND a.codart LIKE '".$ls_codart."'".
				"   AND a.denart LIKE '".$ls_denart."'".
				" AND b.codalm='".$ls_codalmacen."' ".
				"   AND (a.codart=b.codart AND b.existencia>0) ".
				" AND a.codunimed=c.codunimed ".
				" ORDER BY a.codart";
		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_cta);
		if($li_row>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_codart=$row["codart"];
				$ls_denart=$row["denart"];
				$li_unidad=$row["unidad"];
				$li_existencia=$row["existencia"];
				$li_existencia=uf_formatonumerico($li_existencia);
				$li_ultcosart=$row["ultcosart"];
				$li_ultcosart=uf_formatonumerico($li_ultcosart);
				$li_denunimed=$row["denunimed"];
				print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$li_linea',$li_unidad,'$li_existencia','$li_ultcosart','$li_denunimed');\">".$ls_codart."</a></td>";
				print "<td>".$row["denunimed"]."</td>";
				print "<td>".$row["denart"]."</td>";
				print "<td>".$row["existencia"]."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No existen articulos asociados a esta busqueda");
		}
		
	}
	print "</table>";
?>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codart,ls_denart,li_linea,li_unidad,li_existencia,li_monto,li_denunimed)
	{
		
		obj=eval("opener.document.form1.txtcodart"+li_linea+"");
		obj.value=ls_codart;
		obj1=eval("opener.document.form1.txtdenart"+li_linea+"");
		obj1.value=ls_denart;
		//obj1=eval("opener.document.form1.txtunidad"+li_linea+"");
		//obj1.value=li_unidad;
		// NUEVO //
		//alert(li_existencia);
		obj1=eval("opener.document.form1.txtcanoriart"+li_linea+"");
		obj1.value=li_existencia;
		obj1=eval("opener.document.form1.txtpreuniart"+li_linea+"");
		obj1.value=li_monto;
		obj1=eval("opener.document.form1.txtcanart"+li_linea+"");
		obj1.value=0.00;
		obj1=eval("opener.document.form1.txtmontotart"+li_linea+"");
		obj1.value=0.00;
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		obj1=eval("opener.document.form1.txtcodalm");
		almacen=obj1.value
		//alert(almacen);
		f.action="tepuy_catdinamic_articulosalmacen.php?almacen="+almacen;
		f.submit();
	}
</script>
</html>
