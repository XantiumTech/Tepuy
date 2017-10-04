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
$io_funciones = new class_funciones();

$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante=$_POST["txtdocumento"];
	$ls_fecdesde=$_POST["txtfechadesde"];
	$ls_fechasta=$_POST["txtfechahasta"];	
	$ls_procedencia=$_POST["procede"];
	$ls_traspaso="";
	$ls_rectificion="";
	$ls_insubsistencia="";
	$ls_credito="";
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
}
else
{
	$ls_operacion="";
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
	$ls_traspaso="";
	$ls_rectificion="";
	$ls_insubsistencia="";
	$ls_credito="";
	$ls_procedencia='SPGTRA';
	$ls_fecdesde="01/01"."/".date("Y");
	$ls_fechasta=date("d/m/Y");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Comprobantes</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
</p>
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Comprobantes</div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="94" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Comprobante</td>
        <td colspan="2"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        
        </div></td>
			<td width="95" align="right">Fecha </td>
            <td align="left"><div align="right">Desde
        </div></td>
            <td align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fecdesde;?>" size="15" maxlength="10" datepicker="true" ></td>
      </tr>
      <tr>
        <td><div align="right">Procedencia</div></td>
        <td width="113" align="left"><div align="left">
          <select name="procede" id="select">
            <option value="SPGTRA" <?php  if($ls_traspaso=="SPGTRA") { print "selected";} ?>>Traspaso</option>
            <option value="SPGREC" <?php  if($ls_rectificion=="SPGREC") { print "selected";} ?>>Rectificaciones</option>
            <option value="SPGINS" <?php  if($ls_insubsistencia=="SPGINS") { print "selected";} ?>>Insubsistencia</option>
            <option value="SPGCRA" <?php  if($ls_credito=="SPGCRA") { print "selected";} ?>>Credito Adicional</option>
          </select>
        </div></td>
        <td width="85" align="left">&nbsp;</td>
        <td><div align="right"></div></td>
        <td width="40"><div align="right">Hasta </div></td>
        <td width="136" align="left"><input name="txtfechahasta"  style="text-align:center" type="text" id="txtfechahasta" onBlur="valFecha(document.form1.txtfecha)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fechasta;?>" size="15" maxlength="10" datepicker="true" > </td>
      </tr>
      
      <tr>
        <td height="15"><div align="left"></div></td>
        <td colspan="5"><div align="left">
          <table width="72" border="0" align="right" cellpadding="0" cellspacing="0" class="letras-peque&ntilde;as">
            <tr>
              <td width="28"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0"></a></td>
              <td width="44"><a href="javascript: ue_search();">Buscar</a></td>
              </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <?php
if(($ls_tipocat=="")||($ls_tipocat=="rep_ejecucion"))
{
	print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
		print "<td>Comprobante</td>";
		print "<td>Descripcion Comprobante</td>";
		print "<td>Procede</td>";
		print "<td>Fecha</td>";
	print "</tr>";
}
if($ls_operacion=="BUSCAR")
{
		$ls_fecdesde=$io_funciones->uf_convertirdatetobd($ls_fecdesde);
		$ls_fechasta=$io_funciones->uf_convertirdatetobd($ls_fechasta);
		if($ls_tipocat=="rep_ejecucion")
		{
		  $ls_estapro=0; // No Aprobadas
		  $ls_sql=" SELECT distinct a.procede,a.codemp,a.comprobante,a.fecha,a.ced_bene,a.cod_pro,a.descripcion, ".
                  "        a.tipo_destino,a.total ".
                  " FROM tepuy_cmp_md a,spg_dtmp_cmp b ".
                  " WHERE a.codemp='".$as_codemp."' AND a.comprobante like '%".$ls_comprobante."%' AND  ".
				  "       a.procede like '%".$ls_procedencia."%' AND  a.fecha BETWEEN '".$ls_fecdesde."' AND '".$ls_fechasta."' AND".
                  "       a.estapro='".$ls_estapro."' AND a.tipo_comp=2 AND ".
				  "       a.procede=b.procede AND a.comprobante=b.comprobante AND ".
                  "       a.fecha=b.fecha AND a.codemp=b.codemp   ".
                  " ORDER BY a.fecha, a.comprobante, a.procede ";
		}
		elseif($ls_tipocat=="")
		{
		  $ls_estapro=1;  // Aprobadas
		  $ls_sql=" SELECT distinct a.procede,a.codemp,a.comprobante,a.fecha,a.ced_bene,a.cod_pro,a.descripcion, ".
		          "        a.tipo_destino,a.total ".
		 		  " FROM  tepuy_cmp a,spg_dt_cmp b, tepuy_cmp_md c ".
		  		  " WHERE a.procede=b.procede AND a.tipo_comp=2 AND a.comprobante=b.comprobante AND a.fecha=b.fecha AND ".
				  "       a.codemp=b.codemp AND a.codemp='".$as_codemp."' AND a.comprobante like '%".$ls_comprobante."%' AND ".
				  "	      a.procede like '%".$ls_procedencia."%' AND a.fecha>='".$ls_fecdesde."' AND a.fecha<='".$ls_fechasta."' ".
				  "       AND c.estapro=1         ".
				  "       AND c.codemp=a.codemp   ".
				  "       AND c.procede=a.procede ".
				  "       AND c.comprobante=a.comprobante ".
				 "        AND c.fecha=a.fecha             ";
		}
		//print $ls_sql."<br>";
		$rs_data=$io_sql->select($ls_sql);
		$li_numrows = $io_sql->num_rows($rs_data);
		if($li_numrows>0)
		{
		    while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_comprobante=$row["comprobante"];
				$ls_descripcion=utf8_encode($row["descripcion"]);
				$ls_procedencia=$row["procede"];
				$ls_fecha=$row["fecha"];
				$ldt_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
				$ldec_monto=number_format($row["total"],2,",",".");
				if($ls_tipocat=="")
				{
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript:uf_aceptar('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ldt_fecha');\">".$ls_comprobante."</a></td>";
					print "<td>".$ls_descripcion."</td>";				
					print "<td>".$ls_procedencia."</td>";
					print "<td>".$ldt_fecha."</td>";
					print "</tr>";		
			    }		
			    if($ls_tipocat=="rep_ejecucion")
			    {
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript:uf_aceptar_rep_ejecucion('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ldt_fecha');\">".$ls_comprobante."</a></td>";
					print "<td>".$ls_descripcion."</td>";				
					print "<td>".$ls_procedencia."</td>";
					print "<td>".$ldt_fecha."</td>";
					print "</tr>";		
			   }
		  }
		}
		else
		{
			?>
			<script language="JavaScript">
			alert("No se han creado Comprobantes Presupuestarios.....");
			//close();
			</script>
			<?php
		  $io_sql->free_result($rs_data);	
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

   function uf_aceptar(comprobante,descripcion,procede,fecha)
   {
		f=opener.document.form1;
		f.txtcomprobante.value=comprobante;
		f.txtcomprobante.readOnly=true;
		f.txtprocede.value=procede;
		f.txtprocede.readOnly=true;
		f.txtfecha.value=fecha;
		f.txtfecha.readOnly=true;
		close(); 
   }
   function uf_aceptar_rep_ejecucion(comprobante,descripcion,procede,fecha)
   {
		f=opener.document.form1;
		f.txtcomprobante.value=comprobante;
		f.txtcomprobante.readOnly=true;
		f.txtprocede.value=procede;
		f.txtprocede.readOnly=true;
		f.txtfecha.value=fecha;
		f.txtfecha.readOnly=true;
		close(); 
   }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_comprobantes_modificaciones.php?tipocat=<?php print $ls_tipocat; ?>";
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
	
	function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
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
