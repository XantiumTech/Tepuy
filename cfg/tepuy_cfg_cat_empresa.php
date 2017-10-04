<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Empresas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$in           = new tepuy_include();
$conn         = $in->uf_conectar();
$io_dsempresa = new class_datastore();
$io_sql       = new class_sql($conn);
$io_sql2      = new class_sql($conn);
$arr          = $_SESSION["la_empresa"];
$ls_sql       = " SELECT * FROM tepuy_empresa ";
$rs_empresa   = $io_sql->select($ls_sql);
$data         = $rs_empresa; 
if ($row=$io_sql->fetch_row($rs_empresa))
   {
	 $data=$io_sql->obtener_datos($rs_empresa); 
	 $io_sql->free_result($rs_empresa);
   }
$arrcols            = array_keys($data);
$totcol             = count($arrcols);
$io_dsempresa->data = $data;
$totrow             = $io_dsempresa->getRowCount("codemp");

////////////////////////////////////////////////////
$ls_sql2     = " SELECT COUNT(*) as total FROM tepuy_cmp ";
$rs_empresa2 = $io_sql2->select($ls_sql2);
$data2       = $rs_empresa2;
$totalfilas  = 0;
if ($rs_empresa2===false)
   {
	 print "Error en tepuy_cmp";
   }
else
   {
	 if ($row2=$io_sql2->fetch_row($rs_empresa2))
        {
	 	  $data2=$io_sql2->obtener_datos($rs_empresa2);
	      $totalfilas=$data2["total"][1];
        }
     $io_sql2->free_result($rs_empresa2);
   }

$ls_codemp=$_SESSION["la_empresa"]["codemp"];
$ls_periodo=$_SESSION["la_empresa"]["periodo"];

$ls_sql3= " SELECT  COUNT(*) as totalaper                                                                                     ".
		  "   FROM  tepuy_cmp                                                                                                ".
		  "  WHERE  codemp='".$ls_codemp."' AND procede='SPGAPR' AND comprobante='0000000APERTURA' AND fecha='".$ls_periodo."'";
$rs_empresa=$io_sql2->select($ls_sql3);
$total=0;
if ($rs_empresa===false)
   {
	 print "Error en tepuy_cmp";
   }
else
   {
	 if ($row=$io_sql2->fetch_row($rs_empresa))
	    {
		  $total=$row["totalaper"];
	    }
     $io_sql2->free_result($rs_empresa);
   }

$ls_sql4      = " SELECT COUNT(*) as totalctascg FROM scg_cuentas WHERE codemp='".$ls_codemp."'";
$rs_empresa   = $io_sql->select($ls_sql4);
$total_ctascg = 0;
if($rs_empresa===false)
{
	print "Error en tepuy_cmp";
}
else
{
	if ($row=$io_sql->fetch_row($rs_empresa))
	   {
		 $total_ctascg=$row["totalctascg"];
	   }
   	   $io_sql->free_result($rs_empresa);
}

$ls_sql5      = " SELECT COUNT(*) as totalctaspg FROM spg_cuentas WHERE codemp='".$ls_codemp."'";
$rs_empresa   = $io_sql->select($ls_sql5);
$total_ctaspg = 0;
if($rs_empresa===false)
{
	print "Error en tepuy_cmp";
}
else
{
	if ($row=$io_sql->fetch_row($rs_empresa))
	   {
		 $total_ctaspg=$row["totalctaspg"];
	   }
   	   $io_sql->free_result($rs_empresa);
}
////////////////////////////////////////////////////
?>
<br>
<table width="500" border="0" cellpadding="1"  cellspacing="1" class="fondo-tabla" align="center">
<tr class="titulo-celda">
  <td height="22" colspan="5">Cat&aacute;logo de Empresas</td>
  </tr>
<tr class="titulo-celdanew">
<td height="22">Código</td>
<td height="22">Nombre</td>
<td height="22">Siglas</td>
<td height="22">Dirección</td>
<td height="22">Teléfono</td>
</tr>
<?php
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$codigo             = $data["codemp"][$z];
	$nombre             = $data["nombre"][$z];
	$nomres				= $data["nomres"][$z];
	$titulo             = $data["titulo"][$z];
	$direccion          = $data["direccion"][$z];
	$ciuemp             = $data["ciuemp"][$z];
	$estemp             = $data["estemp"][$z];
	$zonpos             = $data["zonpos"][$z];
	$telefono           = $data["telemp"][$z];
	$fax                = $data["faxemp"][$z];
	$email              = $data["email"][$z];
	$website            = $data["website"][$z];
    $ls_nomorgads       = $data["nomorgads"][$z];
	$fecha              = $data["periodo"][$z];
	$periodo            = substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
	$enero              = $data["m01"][$z];
	$febrero            = $data["m02"][$z];
	$marzo              = $data["m03"][$z];
	$abril              = $data["m04"][$z];
	$mayo               = $data["m05"][$z];
	$junio              = $data["m06"][$z];
	$julio              = $data["m07"][$z];
	$agosto             = $data["m08"][$z];
	$septiembre         = $data["m09"][$z];
	$octubre            = $data["m10"][$z];
	$noviembre          = $data["m11"][$z];
	$diciembre          = $data["m12"][$z];
	$tipocontabilidad   = $data["esttipcont"][$z];
	$planunico          = $data["formplan"][$z];
	$contabilidad       = $data["formcont"][$z];
	$pgasto             = $data["formpre"][$z];
	$pingreso           = $data["formspi"][$z];
	$activo             = $data["activo"][$z];
	$pasivo             = $data["pasivo"][$z];
	$ingreso            = $data["ingreso"][$z];
	$gasto              = $data["gasto"][$z];
    $resultado          = $data["resultado"][$z];
	$capital            = $data["capital"][$z];
	$deudor             = $data["orden_h"][$z];
	$acreedor           = $data["orden_d"][$z];
	$presupuestogasto   = $data["gasto_p"][$z];
	$presupuestoingreso = $data["ingreso_p"][$z];
	$resultadoactual    = $data["c_resultad"][$z];
	$resultanterior     = $data["c_resultan"][$z];
	$desestpro1         = $data["nomestpro1"][$z];
	$desestpro2         = $data["nomestpro2"][$z];
	$desestpro3         = $data["nomestpro3"][$z];
	$desestpro4         = $data["nomestpro4"][$z];
	$desestpro5         = $data["nomestpro5"][$z];
	$haciendaactivo     = $data["activo_h"][$z];
   	$haciendapasivo     = $data["pasivo_h"][$z];
	$haciendaresul      = $data["resultado_h"][$z];
	$fiscalgasto        = $data["gasto_f"][$z];
	$ingresofiscal      = $data["ingreso_f"][$z];
	$li_traspasos       = $data["estvaltra"][$z];
	$li_valnivel        = $data["vali_nivel"][$z];
	$ls_cuentabienes    = $data["soc_gastos"][$z];
	$ls_cuentaservicios = $data["soc_servic"][$z];
	$ls_estmodape       = $data["estmodape"][$z];
	$li_estdesiva       = $data["estdesiva"][$z];
    $li_precomprometer  = $data["estprecom"][$z];	
	$ls_codorgsig       = $data["codorgsig"][$z];
	$ls_rifemp          = $data["rifemp"][$z];
	$ls_nitemp          = $data["nitemp"][$z];
	$li_numnivest       = $data["numniv"][$z];
	$li_estmodest       = $data["estmodest"][$z];
	$ld_salinipro       = $data["salinipro"][$z];
	$ld_salinipro       = number_format($ld_salinipro,2,',','.');
	$ld_salinieje       = $data["salinieje"][$z];
	$ld_salinieje       = number_format($ld_salinieje,2,',','.');
	$ls_numordcom       = $data["numordcom"][$z];	
	$ls_numordser       = $data["numordser"][$z];
	$ls_numsolpag       = $data["numsolpag"][$z];
	$ls_numlicemp       = $data["numlicemp"][$z];
    $ls_modgenret       = $data["modageret"][$z];
    $ls_concomiva       = $data["concomiva"][$z];
    $ls_estmodiva       = $data["estmodiva"][$z];
    $ls_cedben          = $data["cedben"][$z];
    $ls_nomben          = $data["nomben"][$z];
    $ls_scctaben        = $data["scctaben"][$z];
    $ls_tesoroactivo    = $data["activo_t"][$z];
    $ls_tesoropasivo    = $data["pasivo_t"][$z];
    $ls_tesororesul     = $data["resultado_t"][$z];
    $ls_ctafinanciera   = $data["c_financiera"][$z];
    $ls_ctafiscal       = $data["c_fiscal"][$z];
	$ls_codasiona       = $data["codasiona"][$z];
	$ls_confch          = $data["confi_ch"][$z];
	$ls_confiva         = $data["confiva"][$z];
	$li_diacadche       = $data["diacadche"][$z];
	$ls_ivss            = $data["nroivss"][$z];
	$ls_nomrep          = $data["nomrep"][$z];
	$ls_cedrep          = $data["cedrep"][$z];
	$ls_telfrep         = $data["telfrep"][$z];
	$ls_cargo           = $data["cargorep"][$z];
	$ls_estretiva       = $data["estretiva"][$z];
	$ls_confinstr       = $data["confinstr"][$z];
	
	print "<td align=center><a href=\"javascript: aceptar_empresa('$codigo','$nombre','$nomres','$titulo','$direccion',".
	"'$ciuemp','$estemp','$zonpos','$telefono','$fax','$email','$website','$periodo','$enero','$febrero','$marzo',".
	"'$abril','$mayo','$junio','$julio','$agosto','$septiembre','$octubre','$noviembre','$diciembre','$tipocontabilidad',".
	"'$planunico','$contabilidad','$pgasto','$pingreso','$activo','$pasivo','$ingreso','$gasto','$resultado','$capital',".
	"'$deudor','$acreedor','$presupuestogasto','$presupuestoingreso','$desestpro1','$desestpro2','$desestpro3','$desestpro4',".
	"'$desestpro5','$resultadoactual','$resultanterior','$haciendaactivo','$haciendapasivo','$haciendaresul','$fiscalgasto',".
	"'$ingresofiscal','$li_traspasos','$li_valnivel','$ls_cuentabienes','$ls_cuentaservicios','$ls_estmodape','$li_estdesiva',".
	"'$totalfilas','$total','$li_precomprometer','$total_ctascg','$total_ctaspg','$ls_codorgsig','$ls_rifemp','$ls_nitemp',".
	"'$li_numnivest','$li_estmodest','$ld_salinipro','$ld_salinieje','$ls_numordcom','$ls_numordser','$ls_numsolpag',".
	"'$ls_nomorgads','$ls_numlicemp','$ls_modgenret','$ls_concomiva','$ls_estmodiva','$ls_cedben','$ls_nomben','$ls_scctaben',".
	"'$ls_tesoroactivo','$ls_tesoropasivo','$ls_tesororesul','$ls_ctafinanciera','$ls_ctafiscal','$ls_codasiona',".
	"'$ls_confch','$ls_confiva','$li_diacadche','$ls_ivss','$ls_nomrep','$ls_cedrep','$ls_telfrep','$ls_cargo',".
	"'$ls_estretiva','$ls_confinstr');\">".$codigo."</a></td>";
	print "<td align=center>".$nombre."</td>";
	print "<td align=center>".$titulo."</td>";
	print "<td align=left>".$direccion."</td>";
	print "<td align=right>".$telefono."</td>";
	print "</tr>";			
}

?>
</table>
</body>
<script language="JavaScript">
  function aceptar_empresa(codigo,nombre,nomres,titulo,direccion,ciuemp,estemp,zonpos,telefono,fax,email,website,periodo,
                           enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,
						   tipocontabilidad,planunico,contabilidad,pgasto,pingreso,activo,pasivo,ingreso,gasto,resultado,
                           capital,acreedor,deudor,presupuestogasto,presupuestoingreso,desestpro1,desestpro2,desestpro3,
						   desestpro4,desestpro5,resultadoactual,resultadoanterior,haciendaactivo,haciendapasivo,
						   haciendaresul,fiscalgasto,ingresofiscal,traspasos,valnivel,cuentabienes,cuentaservicios,
						   estmodape,estdesiva,totalfilas,total,li_precomprometer,total_ctascg,total_ctaspg,codorgsig,
						   rifemp,nitemp,niveles,li_estmodest,salinipro,salinieje,numordcom,numordser,numsolpag,
                           ls_nomorgads,ls_numlicemp,ls_modgenret,concomiva,estmodiva,cedben,nomben,scctaben,
						   tesoroactivo,tesoropasivo,tesororesul,ctafinanciera,ctafiscal,ls_codasiona,confch,
						   ls_confiva,li_diacadche,li_ivss,ls_nomrep,ls_cedrep,ls_telfrep,ls_cargo,ls_estretiva,ls_confinstr)
  {
    fop = opener.document.form1;	
    fop.txtcodigo.value    = codigo;
    fop.txtcodigo.readOnly = true;
	fop.txtnombre.value    = nombre;
	fop.txtnomres.value    = nomres;
	fop.txttitulo.value    = titulo;
	fop.txtrif.value       = rifemp;
	fop.txtnit.value       = nitemp;
    fop.txtdireccion.value = direccion;
    fop.txtciuemp.value    = ciuemp;
    fop.txtestemp.value    = estemp;
    fop.txtzonpos.value    = zonpos;
	fop.txttelefono.value  = telefono;
    fop.txtfax.value       = fax;
	fop.txtemail.value     = email;
    fop.txtwebsite.value   = website;
	fop.txtnumlicemp.value = ls_numlicemp;
	fop.txtnomorgads.value = ls_nomorgads;
	fop.txtperiodo.value   = periodo;
	fop.cmbnumniv.value    = niveles;
	fop.hidnumniv.value    = niveles;
	fop.txtivss.value      = li_ivss;
	fop.txtnomrep.value    =ls_nomrep;
	fop.txtcedrep.value    =ls_cedrep;
	fop.txttelfrep.value   =ls_telfrep;
	fop.txtcargo.value   =ls_cargo;
	if (enero=="1")
	   {
	     fop.chkenero.checked=true;
	   }  
	if (febrero=="1")
	   {
	     fop.chkfebrero.checked=true;
	   }
	   if (marzo=="1")
	   {
	     fop.chkmarzo.checked=true;
	   }
	   if (abril=="1")
	   {
	     fop.chkabril.checked=true;
	   }
	   if (mayo=="1")
	   {
	     fop.chkmayo.checked=true;
	   }
	   if (junio=="1")
	   {
	     fop.chkjunio.checked=true;
	   }
	   if (julio=="1")
	   {
	     fop.chkjulio.checked=true;
	   }
	   if (agosto=="1")
	   {
	     fop.chkagosto.checked=true;
	   }
	   if (septiembre=="1")
	   {
	     fop.chkseptiembre.checked=true;
	   }
	   if (octubre=="1")
	   {
	     fop.chkoctubre.checked=true;
	   }
	   if (noviembre=="1")
	   {
	     fop.chknoviembre.checked=true;
	   }
	   if (diciembre=="1")
	   {
	     fop.chkdiciembre.checked=true;
	   }
	   if (tipocontabilidad==1)
	      {
		    fop.hidtipcont.value=tipocontabilidad;
			fop.radiocontabilidad[0].checked=true;
		    fop.radiocontabilidad[0].disabled=true;
		    fop.radiocontabilidad[1].disabled=true;

		  }
	  else
	      {
		    fop.hidtipcont.value=tipocontabilidad;
			fop.radiocontabilidad[1].checked=true;
		    fop.radiocontabilidad[0].disabled=true;
		    fop.radiocontabilidad[1].disabled=true;
		  }	  
    if (ls_modgenret=='B')
       {
         fop.radiocmp[0].checked = true;
       }	
    else
       {
         fop.radiocmp[1].checked = true;
       } 
	if (ls_estretiva=='C')
       {
         fop.radiocmpiva[0].checked = true;
	   }	
    else
       {
         if (ls_estretiva=='B') 
		 { 
         	fop.radiocmpiva[1].checked = true;
         }
       }    
    fop.txtplanunico.value          = planunico;
    fop.txtcontabilidad.value       = contabilidad;
	fop.txtpgasto.value             = pgasto;
    fop.txtpingreso.value           = pingreso;
	fop.txtactivo.value             = activo;
	fop.txtpasivo.value             = pasivo;
	fop.txtingreso.value            = ingreso;
  	fop.txthaciendaactivo.value     = haciendaactivo;
   	fop.txthaciendapasivo.value     = haciendapasivo;
	fop.txthaciendaresul.value      = haciendaresul;
	fop.txtfiscalgasto.value        = fiscalgasto;
	fop.txtingresofiscal.value      = ingresofiscal;
	fop.txtgasto.value              = gasto;
    fop.txtresultado.value          = resultado;
	fop.txtcapital.value            = capital;
   	fop.txtordendeudor.value        = deudor;
	fop.txtordenacreedor.value      = acreedor;
	fop.txtpresupuestogasto.value   = presupuestogasto;
	fop.txtpresupuestoingreso.value = presupuestoingreso;
	fop.txtresultadoactual.value    = resultadoactual;
	fop.txtresultadoanterior.value  = resultadoanterior;
	fop.txtdesestpro1.value         = desestpro1;
	fop.txtdesestpro2.value         = desestpro2;
	fop.txtdesestpro3.value         = desestpro3;
	fop.txtdesestpro4.value         = desestpro4;
	fop.txtdesestpro5.value         = desestpro5;
	fop.cmbvalidacion.value         = valnivel;
	fop.txtcuentabienes.value       = cuentabienes;
	fop.txtcuentaservicios.value    = cuentaservicios;
	fop.txtnumordcom.value          = numordcom;
	fop.txtnumordser.value          = numordser;
	fop.txtnumsolpag.value          = numsolpag;
	if (li_estmodest==1)
	   {
		fop.radioestructura[0].checked=true;
	   }
    else
	   {
		fop.radioestructura[1].checked=true;
	   }	
	fop.hidestmodest.value = li_estmodest;
	if (traspasos==1)
	   {
	     fop.chkvalidacion.checked=true;   
	   }
   if (estmodape==0)
	  {
		fop.radioestmodape[0].checked=true;
	  }
  else
	  {
		fop.radioestmodape[1].checked=true;
	  }	
  if (estmodiva=="1")
	  {
	    fop.chkestmodiva.checked=true;
	  }
 else
	  {
	     fop.chkestmodiva.checked=false;
	  }
	if (totalfilas>0)
	   {
		 
		 if(total_ctascg>0)
		 {
			fop.txtplanunico.readOnly=true;
		 	fop.txtcontabilidad.readOnly=true;
			fop.hid_roscg.value="readonly";
		 }
		 else
		 {
			 fop.hid_roscg.value="";
		 }
		 if(total_ctaspg>0)	
		 {
		 	fop.txtpgasto.readOnly = true;
		    fop.hid_rospg.value    = "readonly";
	     }
		  else
		 {
			 fop.hid_rospg.value="";
		 }
		 fop.txtpingreso.readOnly=true;
       }
	if (totalfilas>0 || total_ctascg>0 || total_ctaspg>0 || total>0)
	   {
	     fop.hiddisabled.value = "1";
	   }
	if (total>0) 
	   {
	  	 fop.modaper.value=true;
	   }
	if (li_precomprometer==1)
	   {
	     fop.chkprecomprometer.checked=true;
	   }
	else
	   {
	     fop.chkprecomprometer.checked=false;
	   }
    if (estmodiva=="1")
	  {
	    fop.chkestmodiva.checked=true;
	  }
    else
	  {
	     fop.chkestmodiva.checked=false;
	  }
      
	 if (confch=='0')
       {
         fop.radiocheqauto[0].checked = true;
       }	
    else
       {
         fop.radiocheqauto[1].checked = true;
       }  
	fop.txtsalinipro.value     = salinipro;
	fop.txtsalinieje.value     = salinieje;
	fop.txtcodorg.value        = codorgsig;
	fop.txtconcomiva.value     = concomiva;
	fop.txtcedben.value        = cedben;
	fop.txtcedben.readOnly     = true;
	fop.txtnomben.value        = nomben;
	fop.txtnomben.readOnly     = true;
	fop.txtscctaben.value      = scctaben;
	fop.txtscctaben.readOnly   = true;
  	fop.txttesoroactivo.value  = tesoroactivo;
   	fop.txttesoropasivo.value  = tesoropasivo;
	fop.txttesororesul.value   = tesororesul;
   	fop.txtctafinanciera.value = ctafinanciera;
	fop.txtctafiscal.value     = ctafiscal;
	fop.txtcodasiona.value     = ls_codasiona;
	fop.hidestatus.value       = 'GRABADO';
	if (ls_confiva=='P')
    {
         fop.radioiva[0].checked = true;
    }	
    else if (ls_confiva=='C')
    {
         fop.radioiva[1].checked = true;
    }
	if (li_diacadche!=0 && li_diacadche!='')
	   {
	     fop.txtdiacadche.value = li_diacadche;
	   }  
	
	if (ls_confinstr=='V')
    {
         fop.radioinstr[0].checked = true;
    }	
    if (ls_confinstr=='N')
    {
         fop.radioinstr[1].checked = true;
    }  
	if (ls_confinstr=='A')
    {
         fop.radioinstr[2].checked = true;
    }     
	close();
	fop.submit();
  }
</script>
</html>