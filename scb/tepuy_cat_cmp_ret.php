<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
$SQL=new class_sql($con);
$SQL_mov=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT * FROM tepuy_procedencias";
	$data=$sql->select($ls_sql);
	//$data=$rs_cta;
	if($row=$sql->fetch_row($data))
	{
		$data=$sql->obtener_datos($data);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds_procedencias->data=$data;
		
	}	
}

function uf_select_provbene($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);
	
	if($row=$sql->fetch_row($data))
	{
		$ls_provbene=$row[$ls_campo];
		
	}	
	else
	{
		$ls_provbene="";
	}
	$sql->free_result($data);
	return $ls_provbene;
}

function uf_select_data($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);
	
	if($row=$sql->fetch_row($data))
	{
		$ls_result=$row[$ls_campo];
		
	}	
	else
	{
		$ls_result="";
	}
	$sql->free_result($data);
	return $ls_result;
}

if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_documento="%".$_POST["txtdocumento"]."%";
//	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_fecdesde=$_POST["txtfechadesde"];
	$ls_fechasta=$_POST["txtfechahasta"];	
	$ls_procedencia=$_POST["procede"];
	$ls_provben	= "%".$_POST["txtprovbene"]."%";
	$ls_estmov=$_POST["estmov"];
	$ls_tipo=$_POST["tipo"];
	
}
else
{
	$ls_operacion="";
	$ls_estmov="-";
}
if($ls_estmov=="-")
{
	$lb_sel="selected";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}

if($ls_estmov=="N")
{
	$lb_sel="";
	$lb_selN="selected";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="C")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="selected";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="L")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="selected";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="A")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="selected";
	$lb_selO="";
}
if($ls_estmov=="O")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="selected";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobantes de Retenci&oacute;n</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
</p>
  <table width="531" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td width="528"  class="titulo-celda">Cat&aacute;logo de Comprobantes de Retenci&oacute;n </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p><br>
  </p>
  <div align="center">
    <table width="531" border="0" align="left" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="69" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Documento</td>
        <td colspan="2"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        
        </div></td>
			<?php
			if(array_key_exists("procede",$_POST))
			{
			$ls_procede_ant=$_POST["procede"];
			$sel_N="";
			}
			else
			{
			$ls_procede_ant="N";
			$sel_N="selected";
			}
			uf_cargar_procedencias($SQL);
			$li_rowcount=$ds_procedencias->getRowCount("procede");
			
			?>
			<td width="55" align="right">Fecha </td>
            <td align="left"><div align="right">Desde
        </div></td>
            <td align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  size="14" maxlength="10"  onKeyPress="currencyDate(this);" datepicker="true"></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td width="112" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
		</td>
        <td width="85" align="left"><input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="" size="14" maxlength="10"></td>
        <td><div align="right"></div></td>
        <td width="55"><div align="right">Hasta </div></td>
        <td width="153" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta" size="14" maxlength="10"  style="text-align:center"   onKeyPress="currencyDate(this);" datepicker="true"> </td>
      </tr>
      <tr>
        <td height="10"><div align="right">Procedencia</div></td>
        <td colspan="3" align="left" ><select name="procede" id="select">
          <option value="N" "<?php print $sel_N?>" >Ninguno</option>
          <?php
		  	for($li_i=1;$li_i<=$li_rowcount;$li_i++)
			{
				$ls_procede=$ds_procedencias->getValue("procede",$li_i);
				if($ls_procede_ant==$ls_procede)
				{
				?>
          <option value="<?php print $ls_procede?>" selected><?php print $ls_procede?></option>
          <?php
				}
				else
				{
				?>
          <option value="<?php print $ls_procede?>"><?php print $ls_procede?></option>
          <?php
				}
			}
			?>
        </select></td>
        <td><div align="right">Estatus</div></td>
        <td><div align="left">
          <select name="estmov" id="estmov">
              <option value="-" <?php print $lb_sel;?>>Ninguno</option>
			  <option value="N" <?php print $lb_selN;?>>No Contabilizado</option>
              <option value="C" <?php print $lb_selC;?>>Contabilizado</option>
              <option value="L" <?php print $lb_selL;?>>No Contabilizable</option>
              <option value="A" <?php print $lb_selA;?>>Anulado</option>
              <option value="O" <?php print $lb_selO;?>>Original</option>
          </select>
        </div></td>
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
	<br>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="200" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><?php

print "<table width=531 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
	print "<td>Documento</td>";
	print "<td>Retención</td>";
	print "<td>Fecha</td>";
	print "<td>Periodo</td>";
	print "<td>Proveedor</td>";
	print "<td>Nombre Proveedor</td>";
	print "<td>RIF</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
		$ls_sql="SELECT * FROM scb_cmp_ret 
				 WHERE codemp='".$as_codemp."' AND codret='0000000003'";
	//	print $ls_sql;
		/*if((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
		{
			$ls_fecdesde=$fun->uf_convertirdatetobd($ls_fecdesde);
			$ls_fechasta=$fun->uf_convertirdatetobd($ls_fechasta);
			$ls_sql=$ls_sql." AND fecha>='".$ls_fecdesde."' AND fecha<='".$ls_fechasta."'";
		}
		if($ls_procedencia!="N")
		{
			$ls_sql=$ls_sql." AND Procede ='".$ls_procedencia."'";
		}

		if(($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND cod_pro like '".$ls_provben."'";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND ced_bene like'".$ls_provben."'";
		}
		if($ls_estmov!="-")
		{
			$ls_sql=$ls_sql." AND EstMov='".$ls_estmov."'";
		}*/
		
	$rs_cmp=$SQL_mov->select($ls_sql);

	if($row=$SQL_mov->fetch_row($rs_cmp))
	{
		$data=$SQL_mov->obtener_datos($rs_cmp);
		$ds->data=$data;
		$totrow=$ds->getRowCount("numcom");
		for($z=1;$z<=$totrow;$z++)
		{
			$ls_documento = $data["numcom"][$z];
			$ls_codret	  = $data["codret"][$z];      
			$ls_fecha     = $data["fecrep"][$z];
			$ls_fecha     = $fun->uf_convertirfecmostrar($ls_fecha);
			$ls_perfiscal = $data["perfiscal"][$z];
			$ls_sujret	  = $data["codsujret"][$z];
			$ls_nomsujret = $data["nomsujret"][$z];
			$ls_rif       = $data["rif"][$z];
			$ls_direccion = $data["dirsujret"][$z];
			$ls_estcmpret = $data["estcmpret"][$z];
			print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codret','$ls_fecha','$ls_perfiscal','$ls_sujret','$ls_nomsujret','$ls_rif','$ls_direccion','$ls_estcmpret');\">".$ls_documento."</a></td>";
				print "<td>".$ls_codret."</td>";
				print "<td>".$ls_fecha."</td>";				
				print "<td>".$ls_perfiscal."</td>";
				print "<td>".$ls_sujret."</td>";
				print "<td align=left>".$ls_nomsujret."</td>";	
				print "<td>".$ls_rif."</td>";	
			print "</tr>";			
		}
		$SQL_mov->free_result($rs_cmp);	
	}
	else
	{
		$msg->message("No se han creado Comprobantes de Retención");
		
	}
}
print "</table>";
?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function uf_aceptar(ls_documento,ls_codret,ls_fecha,ls_perfiscal,ls_sujret,ls_nomsujret,ls_rif,ls_direccion,ls_estcmpret)
  {
   	f=opener.document.form1;
	f.txtcomprobante.value=ls_documento;
	ls_mes=ls_perfiscal.substr(4,2);
	ls_agno=ls_perfiscal.substr(0,4);
	f.mes.value=ls_mes;
	f.agno.value=ls_agno;
	f.txtcodigo.value=ls_sujret;
	f.txtnombre.value=ls_nomsujret;
	f.txtrif.value=ls_rif;
	f.txtdireccion.value=ls_direccion;
	f.txtcodret.value=ls_codret;
	f.operacion.value="CARGAR_DT";
	f.estcmpret.value=ls_estcmpret;
	f.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_cmp_ret.php";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_prov.php","_blank","width=502,height=350");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_bene.php","_blank","width=502,height=350");
		}
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
  }
   
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
