<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_tipo,$as_codnom,$as_codnomhas,$as_mesdesde,$as_meshasta)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print
		//		   Access : public 
		//	    Arguments : as_tipo  // Tipo de Llamada del cat�logo
		//	                as_codnom  // C�digo de N�mina
		//	                as_codnomhas  // C�digo de N�mina hasta
		//	                as_mesdesde  // Mes Desde donde se quiere filtrar
		//	                as_meshasta  // Mes Hasta donde se quiere filtrar
		//	  Description : Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por : Ing. Miguel Palencia
		// Fecha Creaci�n : 07/04/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
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
		print "<td width=50>A�o</td>";
		print "<td width=50>Per�odo</td>";
		print "<td width=200>Fecha de Inicio</td>";
		print "<td width=200>Fecha de Finalizaci�n</td>";
		print "</tr>";
		$ls_sql="SELECT sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ".
				"  FROM sno_hperiodo, sno_periodo ".
				" WHERE sno_periodo.cerper = 1 ".
				"   AND sno_hperiodo.codemp = '".$ls_codemp."' ".
				"   AND sno_hperiodo.codnom = '".$as_codnom."' ".
				"   AND sno_hperiodo.codperi <> '000' ".
				"   AND sno_hperiodo.codemp = sno_periodo.codemp ".
				"   AND sno_hperiodo.codnom = sno_periodo.codnom ".
				"   AND sno_hperiodo.codperi = sno_periodo.codperi ";
				
		if(($as_tipo=="repapopatdes")||($as_tipo=="repapopathas"))
		{
			$ls_sql="SELECT sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ".
					"  FROM sno_hperiodo, sno_periodo ".
					" WHERE sno_periodo.cerper = 1 ".
					"   AND sno_hperiodo.codemp = '".$ls_codemp."' ".
					"   AND sno_hperiodo.codperi <> '000' ".
					"   AND sno_hperiodo.codnom >= '".$as_codnom."' ".
					"   AND sno_hperiodo.codnom <= '".$as_codnomhas."' ".
					"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".$as_mesdesde."' ".
					"   AND substr(sno_hperiodo.fecdesper,6,2) <= '".$as_meshasta."' ".
					"   AND sno_hperiodo.codemp = sno_periodo.codemp ".
					"   AND sno_hperiodo.codnom = sno_periodo.codnom ".
					"   AND sno_hperiodo.codperi = sno_periodo.codperi ".
					" GROUP BY sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ";
		}		
		if(($as_tipo=="repcondes")||($as_tipo=="repconhas")||($as_tipo=="replisbandes")||($as_tipo=="replisbanhas"))
		{
			$ls_sql="SELECT sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ".
					"  FROM sno_hperiodo, sno_periodo ".
					" WHERE sno_periodo.cerper = 1 ".
					"   AND sno_hperiodo.codemp = '".$ls_codemp."' ".
					"   AND sno_hperiodo.codperi <> '000' ".
					"   AND sno_hperiodo.codnom >= '".$as_codnom."' ".
					"   AND sno_hperiodo.codnom <= '".$as_codnomhas."' ".
					"   AND sno_hperiodo.codemp = sno_periodo.codemp ".
					"   AND sno_hperiodo.codnom = sno_periodo.codnom ".
					"   AND sno_hperiodo.codperi = sno_periodo.codperi ".
					" GROUP BY sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ";
		}		
		$ls_sql=$ls_sql." ORDER BY sno_hperiodo.anocur, sno_hperiodo.codperi ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_anocur=$row["anocur"];
				$ls_codperi=$row["codperi"];
				$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($row["fecdesper"]);
				$ld_fechasper=$io_funciones->uf_convertirfecmostrar($row["fechasper"]);
				
				switch ($as_tipo)
				{
					case "": // tepuy_snorh_p_seleccionarhnomina
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_anocur','$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "repapopatdes": // tepuy_snorh_r_aportepatronal
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopatdes('$ls_codperi');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "repapopathas": // tepuy_snorh_r_aportepatronal
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopathas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "repcondes": // tepuy_snorh_r_conceptos
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcondes('$ls_codperi');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "repconhas": // tepuy_snorh_r_conceptos
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "replisbandes": // tepuy_snorh_r_listadobanco
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbandes('$ls_codperi','$ld_fecdesper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "replisbanhas": // tepuy_snorh_r_listadobanco
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbanhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;


					case "reprecpagcondes": // tepuy_snorh_r_recibopago
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagcondes('$ls_codperi','$ld_fecdesper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;

					case "reprecpagconhas": // tepuy_snorh_r_recibopago
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagconhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
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
		unset($ld_peractnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Per&iacute;odos</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Per&iacute;odos </td>
    </tr>
  </table>
<br>
    <br>
<?PHP
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_mesdesde=$io_fun_nomina->uf_obtenervalor_get("mesdesde","");
	$ls_meshasta=$io_fun_nomina->uf_obtenervalor_get("meshasta","");
	uf_print($ls_tipo,$ls_codnom,$ls_codnomhas,$ls_mesdesde,$ls_meshasta);
	unset($io_fun_nomina);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(anocur,codperi,fecdesper,fechasper)
{
	opener.document.form1.txtanocurnom.value=anocur;
	opener.document.form1.txtanocurnom.readOnly=true;
	opener.document.form1.txtperactnom.value=codperi;
	opener.document.form1.txtperactnom.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
	opener.document.form1.txtfecdesper.readOnly=true;
    opener.document.form1.txtfechasper.value=fechasper;
	opener.document.form1.txtfechasper.readOnly=true;
	close();
}

function aceptarrepapopatdes(codperi)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtperhas.value="";
	opener.document.form1.txtfecpro.value="";
	close();
}

function aceptarrepapopathas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
		opener.document.form1.txtfecpro.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de per�odo inv�lido.");
	}
}

function aceptarrepcondes(codperi)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtperhas.value="";
	close();
}

function aceptarrepconhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de per�odo inv�lido.");
	}
}

function aceptarreplisbandes(codperi,fecdesper)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
    opener.document.form1.txtperhas.value="";
    opener.document.form1.txtfechasper.value="";
	close();
}

function aceptarreplisbanhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
    	opener.document.form1.txtfechasper.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de per�odo inv�lido.");
	}
}

function aceptarreprecpagcondes(codperi,fecdesper)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
    opener.document.form1.txtperhas.value="";
    opener.document.form1.txtfechasper.value="";
	close();
}

function aceptarreprecpagconhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
    	opener.document.form1.txtfechasper.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de per�odo inv�lido.");
	}
}
</script>
</html>