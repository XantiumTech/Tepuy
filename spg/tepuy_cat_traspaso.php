<?php
session_start();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");

$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$int_scg    = new class_tepuy_int_scg();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();

$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobante Traspaso</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"

</head>

<body>
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion   = $_POST["operacion"];
	 $ls_comprobante = $_POST["txtdocumento"];
	 $ls_fecdesde    = $_POST["txtfechadesde"];
	 $ls_fechasta    = $_POST["txtfechahasta"];	
	 $ls_procedencia = $_POST["procede"];
   }
else
   {
     $ls_operacion = "";
 	 $ls_fecdesde  = '01/'.date("m/Y");
	 $ls_fechasta  = date("d/m/Y");
   }
?>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="6" style="text-align:center"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Comprobante de Traspasos</td>
      </tr>
      <tr>
        <td width="94" height="13" align="right">&nbsp;</td>
        <td height="13" colspan="2">&nbsp;</td>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Comprobante</td>
        <td height="22" colspan="2" style="text-align:left"><input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" maxlength="15" style="text-align:center"></td>
			<td width="95" height="22" align="right"><strong>Fecha</strong> </td>
            <td height="22" style="text-align:right">Desde</td>
            <td height="22" align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" value="<?php print $ls_fecdesde ?>" size="15" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Procedencia</td>
        <td width="113" height="22" align="left"><input name="procede" type="text" id="procede" value="SPGTRA" readonly ></td>
        <td width="85" height="22" align="left">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td width="40" height="22" style="text-align:right">Hasta</td>
        <td width="136" height="22" style="text-align:left"><input name="txtfechahasta" type="text" id="txtfechahasta" onBlur="valFecha(document.form1.txtfecha)" style="text-align:center" value="<?php print $ls_fechasta ?>" size="15" maxlength="10" datepicker="true"> </td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="3" align="left" >&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar..." width="20" height="20" border="0">Buscar</a></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="5">&nbsp;</td>
      </tr>
    </table>
<br>
<?php   
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=100>Comprobante</td>";
print "<td style=text-align:center width=280>Descripción</td>";
print "<td style=text-align:center width=50>Procede</td>";
print "<td style=text-align:center width=50>Fecha</td>";
print "<td style=text-align:center width=60>Proveedor</td>";
print "<td style=text-align:center width=60>Beneficiario</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
     $ls_fecdesde = $io_funcion->uf_convertirdatetobd($ls_fecdesde);
     $ls_fechasta = $io_funcion->uf_convertirdatetobd($ls_fechasta);
	 if ($ls_procedencia!="N")
		{
		 $ls_straux = " AND procede ='".$ls_procedencia."'";
		}

	 $ls_sql      = "SELECT procede,comprobante,descripcion,fecha,cod_pro,ced_bene,estapro,codfuefin,coduac 
	                   FROM tepuy_cmp_md 
		              WHERE codemp='".$ls_codemp."' 
				        AND comprobante like '%".$ls_comprobante."%'
				        AND fecha BETWEEN '".$ls_fecdesde."' AND '".$ls_fechasta."' $ls_straux";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $lb_valido = false;
		}
	 else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_numcom 	= $row["comprobante"];
			          $ls_descom 	= $row["descripcion"];
			          $ls_procom 	= $row["procede"];
			          $ls_feccom 	= $io_funcion->uf_convertirfecmostrar($row["fecha"]);
					  $ls_codpro 	= $row["cod_pro"];
			          $ls_cedben 	= $row["ced_bene"];
					  $ls_codfuefin = $row["codfuefin"];
					  $ls_coduniadm = $row["coduac"];
					  
				      if (($ls_codpro=="----------")&&($ls_cedben=="----------"))
				         {
					       $ls_tipproben = "-";
			 	         }
			 	      elseif(($ls_codpro=="----------")&&($ls_cedben!="----------"))
				         {
					       $ls_tipproben = "B";
				         }
				      elseif(($ls_codpro!="----------")&&($ls_cedben=="----------"))
				         {
					       $ls_tipproben = "P";
				         }
			          $li_estapro = $row["estapro"];	
			          print "<tr class=celdas-blancas>";
					  print "<td style=text-align:center width=100><a href=\"javascript: uf_aceptar('$ls_numcom','$ls_descom','$ls_procom','$ls_feccom','$ls_tipproben','$ls_codpro','$ls_cedben','$li_estapro','$ls_codfuefin','$ls_coduniadm');\">".$ls_numcom."</a></td>";
					  print "<td style=text-align:left   width=280>".$ls_descom."</td>";
					  print "<td style=text-align:center width=50>".$ls_procom."</td>";				
					  print "<td style=text-align:center width=50>".$ls_feccom."</td>";
					  print "<td style=text-align:center width=60>".$ls_codpro."</td>";
					  print "<td style=text-align:center width=60>".$ls_cedben."</td>";				
				      print "</tr>";	  
					}
			 }
		  else
	         {
			   $io_msg->message("No se han creado Comprobantes de Traspaso !!!");
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

  function uf_aceptar(comprobante,descripcion,procede,fecha,tipo,prov,bene,estapro,ls_codfuefin,ls_coduniadm)
  {
   	f					   = opener.document.form1;
	f.txtcomprobante.value = comprobante;
	f.txtdesccomp.value	   = descripcion;
	f.txtproccomp.value	   = procede;
	f.txtfecha.value	   = fecha;
	f.estapro.value		   = estapro;
	f.cmbfuefin.value 	   = ls_codfuefin;
	f.cmbuniadm.value 	   = ls_coduniadm;
	f.operacion.value 	   = "CARGAR_DT";
	f.action			   = "tepuy_spg_p_traspaso.php";
	f.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_traspaso.php";
	  f.submit();
  }

	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
		}
	
	}

	  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>