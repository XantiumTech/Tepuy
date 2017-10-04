<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
	$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Catalogo de Estructura Presupuestaria ".$ls_nomestpro5;
			$li_len1=20;
			$li_len2=6;
			$li_len3=3;
			$li_len4=2;
			$li_len5=2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Catalogo de Estructura Program�tica ".$ls_nomestpro5;
			$li_len1=2;
			$li_len2=2;
			$li_len3=2;
			$li_len4=2;
			$li_len5=2;
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codestprog1, $as_codestprog2, $as_codestprog3, $as_codestprog4, $as_codestprog5, $as_denominacion, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: as_codestprog1  // C�digo de la estructura Program�tica 1
		//	  			 as_codestprog2  // C�digo de la estructura Program�tica 2
		//	  			 as_codestprog3  // C�digo de la estructura Program�tica 3 
		//	  			 as_codestprog4  // C�digo de la estructura Program�tica 4
		//	  			 as_codestprog5  // C�digo de la estructura Program�tica 5
		//				 as_denominacion // Denominaci�n de la estructura program�tica
		//				 as_tipo  // Tipo de Llamada del cat�logo
		//	Description: Funci�n que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina,$li_len1,$li_len2,$li_len3,$li_len4,$li_len5;
		
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro1"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro2"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro3"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro4"]."</td>";
		print "<td>C�digo </td>";
		print "<td>Denominaci�n</td>";
		print "</tr>";
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro5 ".
				"  FROM spg_ep5 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($as_codestprog1,20,"0",0)."' ".
				"   AND codestpro2 ='".str_pad($as_codestprog2,6,"0",0)."' ".
				"   AND codestpro3 ='".str_pad($as_codestprog3,3,"0",0)."' ".
				"   AND codestpro4 ='".str_pad($as_codestprog4,2,"0",0)."' ".
				"   AND codestpro5 like '".$as_codestprog5."' ".
				"   AND denestpro5 like '".$as_denominacion."' ".
				" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5 ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_codestpro5=substr($row["codestpro5"],(strlen($row["codestpro5"])-$li_len5),$li_len5);
				$denominacion=$row["denestpro5"];
				switch($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$denominacion');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro5)."</a></td>";
						print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
						print "</tr>";			
						break;

					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptarasignacion('$ls_codestpro5','$denominacion');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro5)."</a></td>";
						print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
						print "</tr>";			
						break;
					case "asignacionaporte":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptaraaporte('$ls_codestpro5','$denominacion');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro5)."</a></td>";

						print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	if(array_key_exists("codestpro1",$_GET))
	{
		$ls_codestprog1=$_GET["codestpro1"];
		$ls_denestprog1=$_GET["denestpro1"];
	}
	if(array_key_exists("txtcodestpro1",$_POST))
	{
		$ls_codestprog1=$_POST["txtcodestpro1"];
		$ls_denestprog1=$_POST["txtdenestpro1"];
	}
	if(array_key_exists("codestpro2",$_GET))
	{
		$ls_codestprog2=$_GET["codestpro2"];
		$ls_denestprog2=$_GET["denestpro2"];
	}
	if(array_key_exists("txtcodestpro2",$_POST))
	{
		$ls_codestprog2=$_POST["txtcodestpro2"];
		$ls_denestprog2=$_POST["txtdenestpro2"];
	}
	if(array_key_exists("codestpro3",$_GET))
	{
		$ls_codestprog3=$_GET["codestpro3"];
		$ls_denestprog3=$_GET["denestpro3"];
	}
	if(array_key_exists("txtcodestpro3",$_POST))
	{
		$ls_codestprog3=$_POST["txtcodestpro3"];
		$ls_denestprog3=$_POST["txtdenestpro3"];
	}
	if(array_key_exists("codestpro4",$_GET))
	{
		$ls_codestprog4=$_GET["codestpro4"];
		$ls_denestprog4=$_GET["denestpro4"];
	}
	if(array_key_exists("txtcodestpro4",$_POST))
	{
		$ls_codestprog4=$_POST["txtcodestpro4"];
		$ls_denestprog4=$_POST["txtdenestpro4"];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_titulo;?></title>
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
     	 	<td height="20" colspan="2" class="titulo-ventana"><?php print $ls_titulo;?></td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118" height="22"><div align="right"><?php print $ls_nomestpro1; ?></div></td>
        <td width="380"><div align="left"><?php print $ls_denestprog1; ?>
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestprog1; ?>" size="22" maxlength="20" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="hidden" class="sin-borde" id="txtdenestpro1" size="50" value="<?php print $ls_denestprog1; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro2; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog2; ?>
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print  $ls_codestprog2; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro2" type="hidden" id="txtdenestpro2" value="<?php print $ls_denestprog2; ?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro3; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog3; ?>
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print  $ls_codestprog3; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro3" type="hidden" id="txtdenestpro3" value="<?php print $ls_denestprog3; ?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro4; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog4; ?>
          <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print  $ls_codestprog4; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="<?php print $ls_denestprog4; ?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td><input name="txtcodestpro5" type="text" id="txtcodestpro5"  size="22" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo="%".$_POST["txtcodestpro5"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codestprog1, $ls_codestprog2, $ls_codestprog3,  $ls_codestprog4, $ls_codigo, $ls_denominacion, $ls_tipo);
	}
	else
	{
		$ls_codigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_codestprog1, $ls_codestprog2, $ls_codestprog3, $ls_codestprog4,  $ls_codigo, $ls_denominacion, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codestprog5,deno)
{
	opener.document.form1.txtdenestpro5.value=deno;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	close();
}

function aceptarasignacion(codestprog5,deno)
{
	opener.document.form1.txtdenestpro5.value=deno;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	close();
}

function aceptaraporte(codestprog5,deno)
{
	opener.document.form1.txtdenestpro55.value=deno;
	opener.document.form1.txtcodestpro55.value=codestprog5;
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_snorh_cat_estpre5.php?tipo=<?PHP print $ls_tipo;?>";
	f.submit();
}
</script>
</html>
