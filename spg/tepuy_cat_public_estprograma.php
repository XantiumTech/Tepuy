<?php
	session_start();
	$la_empresa=$_SESSION["la_empresa"];
	include("../shared/class_folder/tepuy_include.php");
	$io_include=new tepuy_include();
	$io_connect=$io_include->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_function=new class_funciones();
	require_once("../shared/class_folder/class_datastore.php");
	$ds=new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_connect);

	$ls_codemp=$la_empresa["codemp"];
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo="%".$_POST["txtcodestprog3"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_codigo="%%";
		$ls_denominacion="%%";
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 5 <?php print $la_empresa["nomestpro5"] ?></title>
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
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118"><div align="right">Codigo</div></td>
        <td width="380"><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="5" maxlength="2" style="text-align:center"></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="80" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
	print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>".$la_empresa["nomestpro1"]."</td>";
	print "<td>".$la_empresa["nomestpro2"]."</td>";
	print "<td>".$la_empresa["nomestpro3"]."</td>";
	print "<td>".$la_empresa["nomestpro4"]."</td>";
	print "<td>Código </td>";
	print "<td>Denominación</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 			    "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3, ".
                "        d.codestpro4 as codestpro4,d.denestpro4 as denestpro4,e.codestpro5 as codestpro5, ".
                "        e.denestpro5 as denestpro5 ".
                " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c,spg_ep4 d,spg_ep5 e ".
                " WHERE  a.codemp=b.codemp AND b.codemp=c.codemp AND c.codemp=d.codemp AND d.codemp=e.codemp AND ".
                "        e.codemp='".$ls_codemp."'   AND a.codestpro1=b.codestpro1 AND a.codestpro1=c.codestpro1  AND ".
                "        a.codestpro1=d.codestpro1  AND  a.codestpro1=e.codestpro1  AND  b.codestpro2=c.codestpro2  AND ".
                "        b.codestpro2=d.codestpro2  AND  b.codestpro2=d.codestpro2  AND  b.codestpro2=e.codestpro2  AND ".
                "        c.codestpro3=d.codestpro3  AND  c.codestpro3=e.codestpro3  AND  d.codestpro4=e.codestpro4  AND ".
			    "        e.codestpro5 like '".$ls_codigo."' AND e.denestpro5 like '".$ls_denominacion."' ";
		$rs_data=$io_sql->select($ls_sql);
		$data=$rs_data;
		
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$li_arrcols=array_keys($data);
			$li_totcol=count($li_arrcols);
			$ds->data=$data;
			$li_totrow=$ds->getRowCount("codestpro5");
		
			for($z=1;$z<=$li_totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$codestprog1=$data["codestpro1"][$z];
				$denestprog1=$data["denestpro1"][$z];
				$codestprog2=$data["codestpro2"][$z];
				$denestprog2=$data["denestpro2"][$z];
				$codestprog3=$data["codestpro3"][$z];
				$denestprog3=$data["denestpro3"][$z];
				$codestprog4=$data["codestpro4"][$z];
				$denestprog4=$data["denestpro4"][$z];
				$codigo=$data["codestpro5"][$z];
				$denominacion=$data["denestpro5"][$z];
				
				$codestprog1=substr($codestprog1,18,2);
				$codestprog2=substr($codestprog2,4,2);
				$codestprog3=substr($codestprog3,1,2);
				$codestprog4=substr($codestprog4,0,2);
				$codigo=substr($codigo,0,2);
				
				if($ls_tipo=="")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog3)."</a></td>";
		
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog4)."</a></td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="apertura")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4','$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4','$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4','$codigo','$denominacion');\">".
					trim($codestprog3)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4','$codigo','$denominacion');\">".
					trim($codestprog4)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4','$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="progrep")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog3)."</a></td>";
		
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codestprog4)."</a></td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$codestprog1','$denestprog1', 
					'$codestprog2','$denestprog2','$codestprog3','$denestprog3','$codestprog4','$denestprog4',
					'$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog3)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog4)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codigo)."</a></td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog3)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codestprog4)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo');\">".
					trim($codigo)."</a></td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
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

  function aceptar(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                   codestprog5,denestprog5)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
	opener.document.form1.codestpro3.value=codestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
	opener.document.form1.codestpro4.value=codestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	opener.document.form1.codestpro5.value=codestprog5;
	close();
  }
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                            codestprog5,denestprog5)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
	opener.document.form1.codestpro3.value=codestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
	opener.document.form1.codestpro4.value=codestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	opener.document.form1.codestpro5.value=codestprog5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                           codestprog5,denestprog5)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
	opener.document.form1.codestpro3.value=codestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
	opener.document.form1.codestpro4.value=codestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	opener.document.form1.codestpro5.value=codestprog5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro4.value=codestprog4;
	opener.document.form1.codestpro5.value=codestprog5;
	opener.document.form1.codestpro5.readOnly=true;
	close();
  }
  
  function aceptar_rephas(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5)
  {
	opener.document.form1.codestpro1h.value=codestprog1;
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro3h.value=codestprog3;
	opener.document.form1.codestpro4h.value=codestprog4;
	opener.document.form1.codestpro5h.value=codestprog5;
	opener.document.form1.codestpro5h.readOnly=true;
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="tepuy_cat_public_estprograma.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>
