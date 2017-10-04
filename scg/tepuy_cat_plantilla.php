<?php
//session_id('8675309');
session_start();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
$SQL=new class_sql($con);
$SQL_cmp=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="565" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Comprobantes Contables </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="69" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Comprobante</td>
        <td width="254"><div align="left">
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
			<td width="78" align="right"> Procedencia	    </td>
            <td align="left" width="118">
			
              <select name="procede" id="procede">
			  
                <option value="N" "<? print $sel_N?>" >Ninguno</option>
				<?php
		  	for($li_i=1;$li_i<=$li_rowcount;$li_i++)
			{
				$ls_procede=$ds_procedencias->getValue("procede",$li_i);
				if($ls_procede_ant==$ls_procede)
				{
				?>
                <option value="<? print $ls_procede?>" selected><? print $ls_procede?></option>
                <?php
				}
				else
				{
				?>
                <option value="<? print $ls_procede?>"><? print $ls_procede?></option>
                <?php
				}
			}
			?>
              </select>
            </td>
            <td width="44"><a href="javascript: ue_search();"><img src="imagenes/Find24.gif" alt="Buscar" width="24" height="24" border="0"></a></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td><div align="left">
          <label>          
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="imagenes/search2.png" alt="Catalogo Proveedores/Beneficiarios" width="25" height="22" border="0"></a>
          <input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="">
</label></div></td>
        <td><div align="right">Fecha</div></td>
        <td><div align="left">
          <input name="txtfecha" type="text" id="txtfecha" size="10" onBlur="valFecha(document.form1.txtfecha)" style="text-align:center" >
        </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="15"><div align="right"></div></td>
        <td colspan="4"><div align="left">
          <table width="150" border="0" align="right" cellpadding="0" cellspacing="0" class="letras-peque&ntilde;as">
            <tr>
              <td><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15"></td>
              <td>buscar</td>
              <td><img src="../shared/imagebank/tools15/eliminar.png" width="15" height="15"></td>
              <td>cerrar ventana </td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <?php

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
if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante="%".$_POST["txtdocumento"]."%";
//	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["procede"];
	$ls_provben	= "%".$_POST["txtprovbene"]."%";
	$ls_tipo=$_POST["tipo"];
	
}
else
{
	$ls_operacion="";

}
print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
	print "<td>Comprobante</td>";
	print "<td>Descripcion Comprobante</td>";
	print "<td>Procede</td>";
	print "<td>Fecha</td>";
	print "<td>Proveedor</td>";
	print "<td>Beneficiario</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
		$ls_sql="SELECT procede,comprobante,descripcion,fecha,cod_pro,ced_bene FROM tepuy_cmp 
		WHERE codemp='".$as_codemp."' AND comprobante like '".$ls_comprobante."'";
		
		if(($ls_fecha!="")&&($ls_fecha!="01/01/1900"))
		{
			$ls_fecha=substr($ls_fecha,6,4)."/".substr($ls_fecha,3,2)."/".substr($ls_fecha,0,2);
			$ls_sql=$ls_sql." AND fecha='".$ls_fecha."'";
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
	$rs_cta=$SQL_cmp->select($ls_sql);
	$data=$rs_cta;
	if($row=$SQL_cmp->fetch_row($rs_cta))
	{
		$data=$SQL_cmp->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("comprobante");
		for($z=1;$z<=$totrow;$z++)
		{
			$ls_comprobante=$data["comprobante"][$z];
			$ls_descripcion=$data["descripcion"][$z];
			$ls_procedencia=$data["procede"][$z];
			print $ls_procedencia;
			$ls_fecha=$data["fecha"][$z];
			$ls_fecha=substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);
			$ls_prov=$data["cod_pro"][$z];
			$ls_bene=$data["ced_bene"][$z];
			print $ls_prov."   ".$ls_bene;
				if(($ls_prov=="----------")&&($ls_bene=="----------"))
				{
					$ls_tip="-";
				}
				elseif(($ls_prov=="----------")&&($ls_bene!="----------"))
				{
					$ls_tip="B";
				}
				elseif(($ls_prov!="----------")&&($ls_bene=="----------"))
				{
					$ls_tip="P";
				}
				print "Tipo=".$ls_tip;
			print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: uf_aceptar('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene');\">".$ls_comprobante."</a></td>";
				print "<td>".$ls_descripcion."</td>";
				print "<td>".$ls_procedencia."</td>";				
				print "<td>".$ls_fecha."</td>";
				print "<td>".$ls_prov."</td>";
				print "<td>".$ls_bene."</td>";				
			print "</tr>";			
		}
	}
	else
	{
		print "No se han creado Cuentas Contables";
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

  function uf_aceptar(comprobante,descripcion,procede,fecha,tipo,prov,bene)
  {
    opener.document.form1.txtcomprobante.value=comprobante;
	opener.document.form1.txtdesccomp.value=descripcion;
	opener.document.form1.txtproccomp.value=procede;
	opener.document.form1.txtfecha.value=fecha;
	opener.document.form1.tipo.value=tipo;
	if(tipo=="P")
	{
		opener.document.form1.txtprovbene.value=prov;
	}
	else if(tipo=="B")
	{
		opener.document.form1.txtprovbene.value=bene;
	}
	else
	{
		opener.document.form1.txtprovbene.value="";
	}
	opener.uf_cargar_dt();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_comprobantes.php";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_prov.php","Catalogo","_blank");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_bene.php","catalogo","_blank");
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
		  alert("Fecha inv�lida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
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
</html>
