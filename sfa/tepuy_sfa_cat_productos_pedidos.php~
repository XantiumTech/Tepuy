<?php
	session_start();
	if(array_key_exists("coddestino",$_GET))
	{
		$ls_coddestino=$_GET["coddestino"];
		$ls_dendestino=$_GET["dendestino"];
	}
	else
	{
		$ls_coddestino="txtcodpro";
		$ls_dendestino="txtdenpro";
	}
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codart="%".$_POST["txtcodpro"]."%";
		$ls_denart="%".$_POST["txtdenpro"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_coddestino=$_POST["hidcoddestino"];
		$ls_dendestino=$_POST["hiddendestino"];
	}
	else
	{
		$ls_operacion="";
	
	}
	$linea=$_GET["linea"];
	//print "Linea: ".$linea;
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_iva()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: 
		//	Description: Función que obtiene e imprime los I.V.A. Creados
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<select name='cmbiva' id='cmbiva' style='width:150px'> ";
		//print "		<option value='' selected>---seleccione---</option> ";
		$ls_sql="SELECT porcar, dencar ".
		        "  FROM sfa_cargos ".
				" ORDER BY codcar ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcar=number_format($row["porcar"],2,",",".");
				$ls_dencar=$row["dencar"];
		  	    print "<option value='$ls_codcar'>".$ls_dencar."</option>";
			}
			$io_sql->free_result($rs_data);
		}
		print "</select>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: 
		//	Description: Función que obtiene e imprime los tipos de articulos
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_sfa;
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<select name='cmbcodtippro' id='cmbcodtippro' style='width:150px'> ";
		print "		<option value='' selected>---seleccione---</option> ";
		$ls_sql="SELECT codtippro, dentippro ".
		        "  FROM sfa_tipoproducto ".
				" ORDER BY codtippro ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtippro=$row["codtippro"];
				$ls_dentippro=$row["dentippro"];
		  	    print "<option value='$ls_codtippro'>".$ls_dentippro."</option>";
			}
			$io_sql->free_result($rs_data);
		}
		print "</select>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Productos a incluir en el Pedido </title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Productos a incluir en el Pedido</td>
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
          <input name="txtcodpro" type="text" id="txtcodpro">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenpro" type="text" id="txtdenpro">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo </div></td>
        <td height="22"><div align="left">
          <?php uf_select_tipo(); ?>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Seleccione I.V.A. </div></td>
        <td height="22"><div align="left">
	<?php uf_select_iva(); ?> 
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
	$ls_codpro	  = "%".$_POST['txtcodpro']."%";
	$ls_denpro	  = "%".$_POST['txtdenpro']."%";
	$ls_codtippro = "%".$_POST['codtippro']."%";
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
	print "<td width=100> Código</td>";
	print "<td width==300> Denominacion</td>";
	print "<td>Existencia</td>";
	print "<td>Precio</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql=" SELECT sfa_producto.codpro,sfa_producto.denpro,sfa_producto.preproa,sfa_producto.preprob, ".
			" sfa_producto.preproc, sfa_producto.preprod, sfa_producto.codunimed, ".
			" TRIM(sfa_producto.spi_cuenta) AS spg_cuenta, sfa_producto.exipro, siv_unidadmedida.denunimed, siv_unidadmedida.unidad ".
			"  FROM sfa_producto, siv_unidadmedida ".
			" WHERE sfa_producto.codemp='".$ls_codemp."' ".
			" AND sfa_producto.codpro like '".$ls_codpro."' ".
			" AND sfa_producto.denpro like '".$ls_denpro."' ".
			" AND sfa_producto.codtippro like '".$ls_codtippro."' ".
			" AND sfa_producto.codunimed = siv_unidadmedida.codunimed ".
			" ORDER BY codpro ASC ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_data);
		if($li_row>0)
		{
			$k=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				print "<tr class=celdas-blancas>";
				$ls_codpro=$row["codpro"];
				$ls_denpro=$row["denpro"];
				$li_denunimed=$row["denunimed"];
				$li_exipro=number_format($row["exipro"],2,",",".");
				$li_preproa=number_format($row["preproa"],2,",",".");
				$li_preprob=number_format($row["preprob"],2,",",".");
				$li_preproc=number_format($row["preproc"],2,",",".");
				$li_preprod=number_format($row["preprod"],2,",",".");
				$k++;
				$cmbprecio="cmbprecio".$k;
				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_denpro','$li_denunimed','$li_exipro','txtcodart','txtdenart','$li_linea','$cmbprecio','$k');\">".$ls_codpro."</a></td>";
				print "<td>".$ls_denpro."</td>";
				print "<td>".$li_exipro."</td>";
				print "<td><select name='$cmbprecio' id='$cmbprecio' style='width:150px'>";
				print "<option value='".$li_preproa."'>".$li_preproa."</option>";
				print "<option value='".$li_preprob."'>".$li_preprob."</option>";
				print "<option value='".$li_preproc."'>".$li_preproc."</option>";
				print "<option value='".$li_preprod."'>".$li_preprod."</option>";
				print "</select></td>";
			/*	print "<td>".$li_preproa."</td>";
				print "<td>".$li_preprob."</td>";
				print "<td>".$li_preproc."</td>";
				print "<td>".$li_preprod."</td>";*/
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
	function aceptar(ls_codart,ls_denart,li_unidad,li_exipro,ls_coddestino,ls_dendestino,li_linea,combo,li_k)
	{
		f=document.form1;
		li_poriva=f.cmbiva.value;
		//alert(li_linea);
		obj=eval("opener.document.form1."+ls_coddestino+li_linea+"");
		obj.value=ls_codart;
		obj1=eval("opener.document.form1."+ls_dendestino+li_linea+"");
		obj1.value=ls_denart;
		obj1=eval("opener.document.form1.txtunimed"+li_linea+"");
		obj1.value=li_unidad;
		obj1=eval("opener.document.form1.txtcanoriart"+li_linea+"");
		obj1.value=li_exipro;
		/////////  NUEVO ///////////
		obj1=eval("opener.document.form1.txtpreart"+li_linea+"");
		objx=eval("document.form1."+combo+"");
		obj1.value=objx.value;
		/////////////////////////////////
		obj1=eval("opener.document.form1.txtporiva"+li_linea+"");
		obj1.value=li_poriva;
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_sfa_cat_productos_pedidos.php";
		f.submit();
	}
</script>
</html>
