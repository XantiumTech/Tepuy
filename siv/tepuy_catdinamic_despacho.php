<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Despacho</title>
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
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Despacho</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="71"><div align="right">Numero </div></td>
        <td width="420" height="22"><div align="left">
          <input name="txtnumorddes" type="text" id="txtnumorddes" size="25" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Solicitud</div></td>
        <td height="22"><div align="left">
          <input name="txtnumsol" type="text" id="txtnumsol" size="25" maxlength="15">
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
	require_once("class_funciones_inventario.php");
	$fun_inv=new class_funciones_inventario("../");
	$in=     new tepuy_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_rifemp=$arre["rifemp"];

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
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numorddes="%".$_POST["txtnumorddes"]."%";
		$ls_numsol="%".$_POST["txtnumsol"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
    }
	else
	{
		$ls_operacion="";
	}
	$ls_destino=$fun_inv->uf_obtenervalor_get("destino","");
	$ls_tipo=$fun_inv->uf_obtenervalor_get("tipo","");
  
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=70>Orden</td>";
	print "<td width=70 align='center'>Solicitud</td>";
	print "<td width=60>Fecha</td>";
	print "<td width=180>Unidad</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
	  if ($ls_tipo=="tipo")
	  {
		 $ls_sql="SELECT siv_despacho.*, ".
				"      (SELECT denuniadm FROM spg_unidadadministrativa".
				"        WHERE spg_unidadadministrativa.coduniadm = siv_despacho.codunides) AS denunides,".
				"      (SELECT denuniadm FROM spg_unidadadministrativa".
				"        WHERE spg_unidadadministrativa.coduniadm = siv_despacho.coduniadm) AS denuniadm,".
				"      (SELECT despai FROM tepuy_pais".
				"        WHERE tepuy_pais.codpai = siv_despacho.codpai) AS despai,".
				"      (SELECT desest FROM tepuy_estados".
				"        WHERE tepuy_estados.codpai = siv_despacho.codpai and tepuy_estados.codest=siv_despacho.codest) AS desest,".
			    "      (SELECT denmun FROM tepuy_municipio".
				"        WHERE tepuy_municipio.codpai = siv_despacho.codpai and tepuy_municipio.codest=siv_despacho.codest and ".
				"             tepuy_municipio.codmun = siv_despacho.codmun) AS denmun,".
			// NUEVO , EXTRAE NOMBRE DEL ALMACEN//
			//    	"  (SELECT nomfisalm FROM siv_almacen WHERE siv_despacho.codunides = siv_almacen.codalm ) AS denalmacen,".
				  "      (SELECT denpar FROM tepuy_parroquia".
				"        WHERE tepuy_parroquia.codpai = siv_despacho.codpai and tepuy_parroquia.codest=siv_despacho.codest and ".
				"             tepuy_parroquia.codmun = siv_despacho.codmun and tepuy_parroquia.codpar= siv_despacho.codpar) AS denpar".
				"  FROM	siv_despacho".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND estrevdes = 1".
				"   AND numorddes LIKE '".$ls_numorddes."'".
				"   AND numsol LIKE '".$ls_numsol."'"; //print $ls_sql;
	   }
	   else
	   { 
	  	$ls_sql="SELECT siv_despacho.*, ".
				"      (SELECT denuniadm FROM spg_unidadadministrativa".
				"        WHERE spg_unidadadministrativa.coduniadm = siv_despacho.codunides) AS denunides, ".
				"      (SELECT denuniadm FROM spg_unidadadministrativa".
				"        WHERE spg_unidadadministrativa.coduniadm = siv_despacho.coduniadm) AS denuniadm, ".
			// NUEVO , EXTRAE NOMBRE DEL ALMACEN//
			    	"  (SELECT nomfisalm FROM siv_almacen WHERE siv_despacho.coduniadm = siv_almacen.codalm ) AS denalmacen".
			    "  FROM	siv_despacho".
				" WHERE codemp = '".$ls_codemp."'".
				//"   AND estrevdes = 1".
				"   AND estdes = 1".
				"   AND numorddes LIKE '".$ls_numorddes."'".
				"   AND numsol LIKE '".$ls_numsol."'";
				//print $ls_sql;
	   }
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("numorddes");
		
			for($z=1;$z<=$totrow;$z++)
			{
				switch ($ls_destino)
				{ 
					case "":
						print "<tr class=celdas-blancas>";
						$ls_numorddes= $data["numorddes"][$z];
						$ls_numsol=    $data["numsol"][$z];
						$ls_codunialm= $data["coduniadm"][$z];
						$ls_denunialm= $data["denalmacen"][$z];
						$ld_fecdes=    $data["fecdes"][$z];
						$ls_obsdes=    $data["obsdes"][$z];
						// codigo del almacen y nombre del almacen
						$ls_codunides= $data["codunides"][$z];
						$ls_denunides= $data["denunides"][$z];
						//////////////////////////////////////////
						$ld_fecdes=$io_func->uf_convertirfecmostrar($ld_fecdes);
						print "<td><a href=\"javascript: aceptar('$ls_numorddes','$ls_numsol','$ls_codunialm','$ls_denunialm','$ld_fecdes',";
						print "                                  '$ls_codunides','$ls_denunides','$ls_obsdes');\">".$ls_numorddes."</a></td>";
						print "<td>".$ls_numsol."</td>";
						print "<td>".$ld_fecdes."</td>";
						print "<td>".$ls_denunialm."</td>";
						print "</tr>";	
					break;

					case "destino":
					    print "<tr class=celdas-blancas>";
						$ls_numorddes= $data["numorddes"][$z];
						$ls_numsol=    $data["numsol"][$z];
						$ls_codunialm= $data["coduniadm"][$z];
						$ls_denunialm= $data["denuniadm"][$z];
						$ld_fecdes=    $data["fecdes"][$z];
						$ls_obsdes=    $data["obsdes"][$z];
						$ls_codunides= $data["codunides"][$z];
						$ls_denunides= $data["denunides"][$z];
						$ls_nomproy= $data["nomproy"][$z];
						$ls_codproy= $data["codproy"][$z];
						$ls_codpai= $data["codpai"][$z];
						$ls_despai= $data["despai"][$z];
						$ls_codmun= $data["codmun"][$z];
						$ls_desmun= $data["denmun"][$z];
						$ls_codest= $data["codest"][$z];
						$ls_desest= $data["desest"][$z];
						$ls_codpar= $data["codpar"][$z];
						$ls_despar= $data["denpar"][$z];
						$ls_nomdir= $data["nomdir"][$z];
						$ls_direccion= $data["direccproy"][$z];
						$ld_fecdes=$io_func->uf_convertirfecmostrar($ld_fecdes);
						print "<td><a href=\"javascript: aceptar2('$ls_numorddes','$ls_numsol','$ls_codunialm','$ls_denunialm','$ld_fecdes',";
						print "                                  '$ls_codunides','$ls_denunides','$ls_obsdes','$ls_nomproy','$ls_codproy','$ls_codpai','$ls_codmun',";
						print "                                  '$ls_codest','$ls_codpar','$ls_nomdir','$ls_despai','$ls_desmun','$ls_desest',";
						print "                                  '$ls_despar','$ls_direccion');\">".$ls_numorddes."</a></td>";
						print "<td>".$ls_numsol."</td>";
						print "<td>".$ld_fecdes."</td>";
						print "<td>".$ls_denunialm."</td>";
						print "</tr>";	
					break;
				}// fin del switch		
			}
		}
		else
		{
			$io_msg->message("No hay registros");
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
	function aceptar(ls_numorddes,ls_numsol,ls_codunialm,ls_denunialm,ld_fecdes,ls_codunides,ls_denunides,ls_obsdes)
	{ 
		opener.document.form1.txtnumorddes.value=ls_numorddes;	
		opener.document.form1.txtnumsol.value=ls_numsol;
		opener.document.form1.txtcoduniadm.value=ls_codunides;	
		opener.document.form1.txtdenuniadm.value=ls_denunides;
		//opener.document.form1.txtcodunides.value=ls_codunides;alert("aqui");
		opener.document.form1.txtcodalm.value=ls_codunialm;
		//opener.document.form1.txtdenunides.value=ls_denunides;alert("aqui");
		opener.document.form1.txtnomfisalm.value=ls_denunialm;

		opener.document.form1.txtobsdes.value=ls_obsdes;
		opener.document.form1.txtfecdes.value=ld_fecdes;
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="tepuy_siv_p_despacho.php";
		opener.document.form1.submit();
		close();
	}
	
	function aceptar2(ls_numorddes,ls_numsol,ls_coduniadm,ls_denuniadm,ld_fecdes,ls_codunides,ls_denunides,ls_obsdes,
	                  ls_nomproy,ls_codproy,ls_codpai,ls_codmun,ls_codest,ls_codpar,ls_nomdir,
					  ls_despai,ls_desmun,ls_desest,ls_despar,ls_direccion)
	{ 
		opener.document.form1.txtnumorddes.value=ls_numorddes;
		opener.document.form1.txtnumsol.value=ls_numsol;
		opener.document.form1.txtcoduniadm.value=ls_coduniadm;
		opener.document.form1.txtdenuniadm.value=ls_denuniadm;
		opener.document.form1.txtcodunides.value=ls_codunides;
		opener.document.form1.txtdenunides.value=ls_denunides;
		opener.document.form1.txtobsdes.value=ls_obsdes;
		opener.document.form1.txtfecdes.value=ld_fecdes;
		opener.document.form1.txtnomproy.value=ls_nomproy;
		opener.document.form1.txtcodproy.value=ls_codproy;
		opener.document.form1.txtcodpai.value=ls_codpai;
        opener.document.form1.txtcodmun.value=ls_codmun;
		opener.document.form1.txtcodest.value=ls_codest;
		opener.document.form1.txtdespar.value=ls_despar;
		opener.document.form1.txtcodpar.value=ls_codpar;
		opener.document.form1.txtnomdirec.value=ls_nomdir;
		opener.document.form1.txtdespai.value=ls_despai;
		opener.document.form1.txtdesmun.value=ls_desmun;
		opener.document.form1.txtdesest.value=ls_desest;
		opener.document.form1.txtdespar.value=ls_despar;
		opener.document.form1.txtdireccion.value=ls_direccion;
		
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="tepuy_siv_p_despacho.php";
		opener.document.form1.submit();
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="tepuy_catdinamic_despacho.php?destino=<?PHP print $ls_destino;?>&tipo=<?PHP print $ls_tipo;?>";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
