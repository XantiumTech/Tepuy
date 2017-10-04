<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Estructura Presupuestaria ";
			$li_len1=20;
			$li_len2=6;
			$li_len3=3;
			$li_len4=2;
			$li_len5=2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Estructura Programática ";
			$li_len1=2;
			$li_len2=2;
			$li_len3=2;
			$li_len4=2;
			$li_len5=2;
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codasicar, $as_denasicar, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_print
		//	Arguments:    as_codasicar  // Código de Asignación de Cargo
		//				  as_denasicar // Denominación de Asignación de Cargo
		//				  as_tipo  // Tipo de Llamada del catálogo
		//	Description:  Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina,$li_len1, $li_len2, $li_len3, $li_len4, $li_len5;
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=40>Código</td>";
		print "<td width=400>Denominación</td>";
		print "<td width=60>Disponibilidad</td>";
		print "</tr>";
		$ls_sql="SELECT sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, sno_asignacioncargo.claasicar, ".
				"		sno_asignacioncargo.minorguniadm, sno_asignacioncargo.ofiuniadm, sno_asignacioncargo.uniuniadm, ".
				"		sno_asignacioncargo.depuniadm, sno_asignacioncargo.prouniadm, sno_asignacioncargo.codtab, ".
				"		sno_asignacioncargo.codpas, sno_asignacioncargo.codgra, sno_asignacioncargo.codded, ".
				"		sno_asignacioncargo.codtipper, sno_asignacioncargo.numvacasicar, sno_asignacioncargo.numocuasicar, ".
				"		sno_asignacioncargo.codproasicar, sno_tabulador.destab, sno_grado.monsalgra, sno_grado.moncomgra, ".
				"		sno_dedicacion.desded, sno_tipopersonal.destipper, sno_unidadadmin.desuniadm, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp=sno_asignacioncargo.codemp".
				"		    AND spg_ep1.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20)) as denestpro1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp=sno_asignacioncargo.codemp".
				"		    AND spg_ep2.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
				"		    AND spg_ep2.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6)) as denestpro2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp=sno_asignacioncargo.codemp".
				"		    AND spg_ep3.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
				"		    AND spg_ep3.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
				"		    AND spg_ep3.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3)) as denestpro3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep4.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
				"		    AND spg_ep4.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
				"		    AND spg_ep4.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3) ".
				"		    AND spg_ep4.codestpro4=substr(sno_asignacioncargo.codproasicar,30,2)) as denestpro4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep5.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
				"		    AND spg_ep5.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
				"		    AND spg_ep5.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3) ".
				"		    AND spg_ep5.codestpro4=substr(sno_asignacioncargo.codproasicar,30,2) ".
				"		    AND spg_ep5.codestpro5=substr(sno_asignacioncargo.codproasicar,32,2)) as denestpro5 ".
				"  FROM sno_asignacioncargo, sno_tabulador, sno_grado, sno_dedicacion,sno_tipopersonal,  sno_unidadadmin  ".
		        " WHERE sno_asignacioncargo.codemp='".$ls_codemp."'".
				"   AND sno_asignacioncargo.codnom='".$ls_codnom."'".
				"   AND sno_asignacioncargo.codasicar like '".$as_codasicar."'".
				"   AND sno_asignacioncargo.denasicar like '".$as_denasicar."' ".
				"	AND sno_asignacioncargo.codemp=sno_tabulador.codemp ".
		        "   AND	sno_asignacioncargo.codnom=sno_tabulador.codnom ".
		        "   AND	sno_asignacioncargo.codtab=sno_tabulador.codtab ".
				"	AND sno_asignacioncargo.codemp=sno_grado.codemp ".
				" 	AND	sno_asignacioncargo.codnom=sno_grado.codnom ".
				" 	AND	sno_asignacioncargo.codtab=sno_grado.codtab ".
				" 	AND	sno_asignacioncargo.codpas=sno_grado.codpas ".
				" 	AND	sno_asignacioncargo.codgra=sno_grado.codgra ".
				"	AND sno_asignacioncargo.codemp=sno_dedicacion.codemp ".
				" 	AND	sno_asignacioncargo.codded=sno_dedicacion.codded ".
				"	AND sno_asignacioncargo.codemp=sno_tipopersonal.codemp ".
				" 	AND sno_asignacioncargo.codded=sno_tipopersonal.codded ".
				" 	AND	sno_asignacioncargo.codtipper=sno_tipopersonal.codtipper ".
				"	AND sno_asignacioncargo.codemp=sno_unidadadmin.codemp ".
				" 	AND	sno_asignacioncargo.minorguniadm=sno_unidadadmin.minorguniadm ".
				" 	AND	sno_asignacioncargo.ofiuniadm=sno_unidadadmin.ofiuniadm ".
				" 	AND	sno_asignacioncargo.uniuniadm=sno_unidadadmin.uniuniadm ".
				" 	AND	sno_asignacioncargo.depuniadm=sno_unidadadmin.depuniadm ".
				" 	AND	sno_asignacioncargo.prouniadm=sno_unidadadmin.prouniadm ".
				" ORDER BY sno_asignacioncargo.codasicar ";
		if($as_tipo=="movimiento")
		{
			$ls_sql="SELECT sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, sno_asignacioncargo.claasicar, ".
					"		sno_asignacioncargo.minorguniadm, sno_asignacioncargo.ofiuniadm, sno_asignacioncargo.uniuniadm, ".
					"		sno_asignacioncargo.depuniadm, sno_asignacioncargo.prouniadm, sno_asignacioncargo.codtab, ".
					"		sno_asignacioncargo.codpas, sno_asignacioncargo.codgra, sno_asignacioncargo.codded, ".
					"		sno_asignacioncargo.codtipper, sno_asignacioncargo.numvacasicar, sno_asignacioncargo.numocuasicar, ".
					"		sno_asignacioncargo.codproasicar, sno_tabulador.destab, sno_grado.monsalgra, sno_grado.moncomgra, ".
					"		sno_dedicacion.desded, sno_tipopersonal.destipper, sno_unidadadmin.desuniadm, ".
					"		(SELECT denestpro1 ".
					"		   FROM spg_ep1 ".
					"		  WHERE spg_ep1.codemp=sno_asignacioncargo.codemp".
					"		    AND spg_ep1.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20)) as denestpro1, ".
					"		(SELECT denestpro2 ".
					"		   FROM spg_ep2 ".
					"		  WHERE spg_ep2.codemp=sno_asignacioncargo.codemp".
					"		    AND spg_ep2.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
					"		    AND spg_ep2.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6)) as denestpro2, ".
					"		(SELECT denestpro3 ".
					"		   FROM spg_ep3 ".
					"		  WHERE spg_ep3.codemp=sno_asignacioncargo.codemp".
					"		    AND spg_ep3.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
					"		    AND spg_ep3.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
					"		    AND spg_ep3.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3)) as denestpro3, ".
					"		(SELECT denestpro4 ".
					"		   FROM spg_ep4 ".
					"		  WHERE spg_ep4.codemp=sno_unidadadmin.codemp".
					"		    AND spg_ep4.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
					"		    AND spg_ep4.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
					"		    AND spg_ep4.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3) ".
					"		    AND spg_ep4.codestpro4=substr(sno_asignacioncargo.codproasicar,30,2)) as denestpro4, ".
					"		(SELECT denestpro5 ".
					"		   FROM spg_ep5 ".
					"		  WHERE spg_ep5.codemp=sno_unidadadmin.codemp".
					"		    AND spg_ep5.codestpro1=substr(sno_asignacioncargo.codproasicar,1,20) ".
					"		    AND spg_ep5.codestpro2=substr(sno_asignacioncargo.codproasicar,21,6) ".
					"		    AND spg_ep5.codestpro3=substr(sno_asignacioncargo.codproasicar,27,3) ".
					"		    AND spg_ep5.codestpro4=substr(sno_asignacioncargo.codproasicar,30,2) ".
					"		    AND spg_ep5.codestpro5=substr(sno_asignacioncargo.codproasicar,32,2)) as denestpro5 ".
					"  FROM sno_asignacioncargo, sno_tabulador, sno_grado, sno_dedicacion,sno_tipopersonal,  sno_unidadadmin  ".
					" WHERE sno_asignacioncargo.codemp='".$ls_codemp."'".
					"   AND sno_asignacioncargo.codasicar like '".$as_codasicar."'".
					"   AND sno_asignacioncargo.denasicar like '".$as_denasicar."' ".
					"	AND sno_asignacioncargo.codemp=sno_tabulador.codemp ".
					"   AND	sno_asignacioncargo.codnom=sno_tabulador.codnom ".
					"   AND	sno_asignacioncargo.codtab=sno_tabulador.codtab ".
					"	AND sno_asignacioncargo.codemp=sno_grado.codemp ".
					" 	AND	sno_asignacioncargo.codnom=sno_grado.codnom ".
					" 	AND	sno_asignacioncargo.codtab=sno_grado.codtab ".
					" 	AND	sno_asignacioncargo.codpas=sno_grado.codpas ".
					" 	AND	sno_asignacioncargo.codgra=sno_grado.codgra ".
					"	AND sno_asignacioncargo.codemp=sno_dedicacion.codemp ".
					" 	AND	sno_asignacioncargo.codded=sno_dedicacion.codded ".
					"	AND sno_asignacioncargo.codemp=sno_tipopersonal.codemp ".
					" 	AND sno_asignacioncargo.codded=sno_tipopersonal.codded ".
					" 	AND	sno_asignacioncargo.codtipper=sno_tipopersonal.codtipper ".
					"	AND sno_asignacioncargo.codemp=sno_unidadadmin.codemp ".
					" 	AND	sno_asignacioncargo.minorguniadm=sno_unidadadmin.minorguniadm ".
					" 	AND	sno_asignacioncargo.ofiuniadm=sno_unidadadmin.ofiuniadm ".
					" 	AND	sno_asignacioncargo.uniuniadm=sno_unidadadmin.uniuniadm ".
					" 	AND	sno_asignacioncargo.depuniadm=sno_unidadadmin.depuniadm ".
					" 	AND	sno_asignacioncargo.prouniadm=sno_unidadadmin.prouniadm ".
					" ORDER BY sno_asignacioncargo.codasicar ";
		}
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codasicar=$row["codasicar"];
				$ls_denasicar=$row["denasicar"];
				$ls_coduniadm=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];
				$ls_claasicar=$row["claasicar"];
				$ls_codtab=$row["codtab"];
				$ls_codpas=$row["codpas"];
				$ls_codgra=$row["codgra"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];		
				$li_numvacasicar=$row["numvacasicar"];
				$li_numocuasicar=$row["numocuasicar"];
				$li_disponasicar=($li_numvacasicar-$li_numocuasicar);
				$ls_destab=$row["destab"];
				$ls_desded=$row["desded"];
				$ls_destipper=$row["destipper"];
				$ls_desuniadm=$row["desuniadm"];
				$li_monsalgra=$row["monsalgra"];
				$li_moncomgra=$row["moncomgra"];
				$li_monsalgra=$io_fun_nomina->uf_formatonumerico($li_monsalgra);
				$li_moncomgra=$io_fun_nomina->uf_formatonumerico($li_moncomgra);
				$ls_codproasicar=$row["codproasicar"];
				$ls_codest1=substr($ls_codproasicar,0,20);
				$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
				$ls_codest2=substr($ls_codproasicar,20,6);
				$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
				$ls_codest3=substr($ls_codproasicar,26,3);
				$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
				$ls_codest4=substr($ls_codproasicar,29,2);
				$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
				$ls_codest5=substr($ls_codproasicar,31,2);
				$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
				$ls_denestpro1=$row["denestpro1"];
				$ls_denestpro2=$row["denestpro2"];
				$ls_denestpro3=$row["denestpro3"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_denestpro5=$row["denestpro5"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codasicar','$ls_denasicar','$ls_coduniadm','$ls_claasicar',";
						print "'$ls_codtab','$ls_codpas','$ls_codgra','$ls_codded','$ls_codtipper','$li_numvacasicar',";
						print "'$li_numocuasicar','$ls_destab','$ls_desded','$ls_destipper','$ls_desuniadm','$li_monsalgra',";
						print "'$li_moncomgra','$ls_codest1','$ls_codest2','$ls_codest3','$ls_codest4','$ls_codest5','$ls_denestpro1',";
						print "'$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$li_disponasicar');\">".$ls_codasicar."</a></td>";
						print "<td>".$ls_denasicar."</td>";
						print "<td>".$li_disponasicar."</td>";
						print "</tr>";			
						break;
					
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codasicar','$ls_denasicar','$ls_codtab','$ls_destab',";
						print "'$ls_codpas','$ls_codgra','$li_monsalgra','$ls_codded','$ls_codtipper','$ls_desded','$ls_destipper',";
						print "'$ls_coduniadm','$ls_desuniadm','$li_disponasicar','$li_moncomgra');\">".$ls_codasicar."</a></td>";
						print "<td>".$ls_denasicar."</td>";
						print "<td>".$li_disponasicar."</td>";
						print "</tr>";			
						break;

					case "aplicarconcepto":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptaraplicarconcepto('$ls_codasicar','$ls_denasicar');\">".$ls_codasicar."</a></td>";
						print "<td>".$ls_denasicar."</td>";
						print "<td>".$li_disponasicar."</td>";
						print "</tr>";			
						break;
					
					case "movimiento":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarmovimiento('$ls_codasicar','$ls_denasicar','$ls_codtab','$ls_destab',";
						print "'$ls_codpas','$ls_codgra','$li_monsalgra','$ls_codded','$ls_codtipper','$ls_desded','$ls_destipper',";
						print "'$ls_coduniadm','$ls_desuniadm','$li_disponasicar');\">".$ls_codasicar."</a></td>";
						print "<td>".$ls_denasicar."</td>";
						print "<td>".$li_disponasicar."</td>";
						print "</tr>";			
						break;
					
					case "importar":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarimportar('$ls_codasicar','$ls_denasicar','$ls_codtab','$ls_destab',";
						print "'$ls_codpas','$ls_codgra','$li_disponasicar','$li_monsalgra');\">".$ls_codasicar."</a></td>";
						print "<td>".$ls_denasicar."</td>";
						print "<td>".$li_disponasicar."</td>";
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
		unset($ls_codnom);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Asignaci&oacute;n de Cargo</title>
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
  <table width="500" height="20" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-ventana">Cat&aacute;logo de Asignaci&oacute;n de Cargo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodasicar" type="text" id="txtcodasicar" size="30" maxlength="7" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenasicar" type="text" id="txtdenasicar" size="30" maxlength="24" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
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
		$ls_codasicar="%".$_POST["txtcodasicar"]."%";
		$ls_denasicar="%".$_POST["txtdenasicar"]."%";
		uf_print($ls_codasicar, $ls_denasicar, $ls_tipo);
	}
	else
	{
		$ls_codasicar="%%";
		$ls_denasicar="%%";
		uf_print($ls_codasicar, $ls_denasicar, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codasicar,denasicar,coduniadm,claasicar,codtab,codpas,codgra,codded,codtipper,numvacasicar,numocuasicar,destab,desded,
 				 destipper,desuniadm,monsalgra,moncomgra,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro1,denestpro2,
				 denestpro3,denestpro4,denestpro5,disponasicar)
{
	opener.document.form1.txtcodasicar.value=codasicar;
	opener.document.form1.txtcodasicar.readOnly=true;	
	opener.document.form1.txtdenasicar.value=denasicar;
	opener.document.form1.txtcoduniadm.value=coduniadm;
	opener.document.form1.txtcoduniadm.readOnly=true;	
	opener.document.form1.txtclaasicar.value=claasicar;
	opener.document.form1.txtcodtab.value=codtab;
	opener.document.form1.txtcodtab.readOnly=true;	
	opener.document.form1.txtcodpas.value=codpas;
	opener.document.form1.txtcodpas.readOnly=true;	
	opener.document.form1.txtcodgra.value=codgra;
	opener.document.form1.txtcodgra.readOnly=true;	
	opener.document.form1.txtcodded.value=codded;
	opener.document.form1.txtcodded.readOnly=true;	
	opener.document.form1.txtcodtipper.value=codtipper;
	opener.document.form1.txtcodtipper.readOnly=true;	
	opener.document.form1.txtnumvacasicar.value=numvacasicar;
	opener.document.form1.txtnumocuasicar.value=numocuasicar;
	opener.document.form1.txtnumocuasicar.readOnly=true;	
	opener.document.form1.txtdestab.value=destab;
	opener.document.form1.txtdestab.readOnly=true;	
	opener.document.form1.txtdesded.value=desded;
	opener.document.form1.txtdestab.readOnly=true;	
	opener.document.form1.txtdestipper.value=destipper;
	opener.document.form1.txtdestipper.readOnly=true;	
	opener.document.form1.txtdesuniadm.value=desuniadm;
	opener.document.form1.txtdesuniadm.readOnly=true;	
	opener.document.form1.txtmonsalgra.value=monsalgra;
	opener.document.form1.txtmonsalgra.readOnly=true;	
	opener.document.form1.txtmoncomgra.value=moncomgra;
	opener.document.form1.txtmoncomgra.readOnly=true;	
	opener.document.form1.txtcodestpro1.value=codestpro1;
	opener.document.form1.txtcodestpro1.readOnly=true;	
	opener.document.form1.txtdenestpro1.value=denestpro1;
	opener.document.form1.txtdenestpro1.readOnly=true;	
	opener.document.form1.txtcodestpro2.value=codestpro2;
	opener.document.form1.txtcodestpro2.readOnly=true;	
	opener.document.form1.txtdenestpro2.value=denestpro2;
	opener.document.form1.txtdenestpro2.readOnly=true;	
	opener.document.form1.txtcodestpro3.value=codestpro3;
	opener.document.form1.txtcodestpro3.readOnly=true;	
	opener.document.form1.txtdenestpro3.value=denestpro3;
	opener.document.form1.txtdenestpro3.readOnly=true;	
	opener.document.form1.txtcodestpro4.value=codestpro4;
	opener.document.form1.txtcodestpro4.readOnly=true;	
	opener.document.form1.txtdenestpro4.value=denestpro4;
	opener.document.form1.txtdenestpro4.readOnly=true;	
	opener.document.form1.txtcodestpro5.value=codestpro5;
	opener.document.form1.txtcodestpro5.readOnly=true;	
	opener.document.form1.txtdenestpro5.value=denestpro5;
	opener.document.form1.txtdenestpro5.readOnly=true;	
	opener.document.form1.txtdisponasicar.value=disponasicar;
	opener.document.form1.txtdisponasicar.readOnly=true;	
	opener.document.form1.coduniadmant.value=coduniadm;	
	opener.document.form1.codtabant.value=codtab;
	opener.document.form1.codpasant.value=codpas;
	opener.document.form1.codgraant.value=codgra;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarasignacion(codasicar,denasicar,codtab,destab,codpas,codgra,monsalgra,codded,codtipper,desded,destipper,
						   coduniadm,desuniadm,disponasicar,moncomgra)
{
	if(disponasicar>0)
	{
		opener.document.form1.txtcodasicar.value=codasicar;
		opener.document.form1.txtcodasicar.readOnly=true;	
		opener.document.form1.txtdenasicar.value=denasicar;
		opener.document.form1.txtdenasicar.readOnly=true;	
		opener.document.form1.txtcodtab.value=codtab;
		opener.document.form1.txtcodtab.readOnly=true;	
		opener.document.form1.txtdestab.value=destab;
		opener.document.form1.txtdestab.readOnly=true;	
		opener.document.form1.txtcodpas.value=codpas;
		opener.document.form1.txtcodpas.readOnly=true;	
		opener.document.form1.txtcodgra.value=codgra;
		opener.document.form1.txtcodgra.readOnly=true;	
		opener.document.form1.txtsueper.value=monsalgra;
		opener.document.form1.txtsueper.readOnly=true;	
		opener.document.form1.txtcompensacion.value=moncomgra;
		opener.document.form1.txtcompensacion.readOnly=true;	
		opener.document.form1.txtcodded.value=codded;
		opener.document.form1.txtcodded.readOnly=true;	
		opener.document.form1.txtcodtipper.value=codtipper;
		opener.document.form1.txtcodtipper.readOnly=true;	
		opener.document.form1.txtdesded.value=desded;
		opener.document.form1.txtdesded.readOnly=true;	
		opener.document.form1.txtdestipper.value=destipper;
		opener.document.form1.txtdestipper.readOnly=true;		
		opener.document.form1.txtcoduniadm.value=coduniadm;
		opener.document.form1.txtcoduniadm.readOnly=true;	
		opener.document.form1.txtdesuniadm.value=desuniadm;
		opener.document.form1.txtdesuniadm.readOnly=true;		
		close();
	}
	else
	{
		alert("Esta asignación de cargo no tiene disponibilidad.");
	}
}

function aceptarmovimiento(codasicar,denasicar,codtab,destab,codpas,codgra,monsalgra,codded,codtipper,desded,destipper,
						   coduniadm,desuniadm,disponasicar)
{
	if(disponasicar>0)
	{
		opener.document.form1.txtcodasicar.value=codasicar;
		opener.document.form1.txtcodasicar.readOnly=true;	
		opener.document.form1.txtdenasicar.value=denasicar;
		opener.document.form1.txtdenasicar.readOnly=true;	
		opener.document.form1.txtcodtab.value=codtab;
		opener.document.form1.txtcodtab.readOnly=true;	
		opener.document.form1.txtdestab.value=destab;
		opener.document.form1.txtdestab.readOnly=true;	
		opener.document.form1.txtcodpas.value=codpas;
		opener.document.form1.txtcodpas.readOnly=true;	
		opener.document.form1.txtcodgra.value=codgra;
		opener.document.form1.txtcodgra.readOnly=true;	
		opener.document.form1.txtsueper.value=monsalgra;
		opener.document.form1.txtsueper.readOnly=true;		
		opener.document.form1.txtcodded.value=codded;
		opener.document.form1.txtcodded.readOnly=true;		
		opener.document.form1.txtdesded.value=desded;
		opener.document.form1.txtdesded.readOnly=true;		
		opener.document.form1.txtcodtipper.value=codtipper;
		opener.document.form1.txtcodtipper.readOnly=true;		
		opener.document.form1.txtdestipper.value=destipper;
		opener.document.form1.txtdestipper.readOnly=true;		
		opener.document.form1.txtcoduniadm.value=coduniadm;
		opener.document.form1.txtcoduniadm.readOnly=true;		
		opener.document.form1.txtdesuniadm.value=desuniadm;
		opener.document.form1.txtdesuniadm.readOnly=true;
		close();
	}
	else
	{
		alert("Esta asignación de cargo no tiene disponibilidad.");
	}
}

function aceptarimportar(codasicar,denasicar,codtab,destab,codpas,codgra,disponasicar,monsalgra)
{
	if(disponasicar>0)
	{
		opener.document.form1.txtcodasicar.value=codasicar;
		opener.document.form1.txtcodasicar.readOnly=true;	
		opener.document.form1.txtdenasicar.value=denasicar;
		opener.document.form1.txtdenasicar.readOnly=true;	
		opener.document.form1.txtcodtab.value=codtab;
		opener.document.form1.txtcodtab.readOnly=true;	
		opener.document.form1.txtdestab.value=destab;
		opener.document.form1.txtdestab.readOnly=true;	
		opener.document.form1.txtcodpas.value=codpas;
		opener.document.form1.txtcodpas.readOnly=true;	
		opener.document.form1.txtcodgra.value=codgra;
		opener.document.form1.txtcodgra.readOnly=true;	
		opener.document.form1.txtsueper.value=monsalgra;
		close();
	}
	else
	{
		alert("Esta asignación de cargo no tiene disponibilidad.");
	}
}

function aceptaraplicarconcepto(codasicar,denasicar)
{
	opener.document.form1.txtcodasicar.value=codasicar;
	opener.document.form1.txtcodasicar.readOnly=true;	
	opener.document.form1.txtdenasicar.value=denasicar;
	opener.document.form1.txtdenasicar.readOnly=true;	
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
  	f.action="tepuy_sno_cat_asignacioncargo.php?tipo=<?PHP print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
