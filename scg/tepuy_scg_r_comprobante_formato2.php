<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","tepuy_scg_r_comprobante_formato2.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_reporte=$io_fun_scg->uf_select_config("SCG","REPORTE","COMPROBANTE_FORMATO2","tepuy_scg_rpp_comprobante_formato2.php","C");
	$ls_reporte2=$io_fun_scg->uf_select_config("SCG","REPORTE","COMPROBANTE_FORMATO2_SINDT","tepuy_scg_rpp_comprobante_formato2_sindt.php","C");
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$li_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ldt_fecdes="01/01/".$li_ano;
	$ldt_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Listado de Comprobante  Formato 2</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 14px}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	   {
		  if($_SESSION["la_empresa"]["confinstr"]=='A')
		  {
      ?>  
			<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
	<?php
          }
          elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	      {
	 ?>
		  <td height="20"  colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
	 <?php
          }
          elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	      {
       ?>
	       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	 <?php
          }
	  }
	  else
	  {
	  ?>
	  	 <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
   <?php 
	}
	?>  
  </tr> -->
  <tr>
    <td width="780" height="36" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_search('<?php print $ls_codemp;?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_openexcel('<?php print $ls_codemp;?>');"><img src="../shared/imagebank/tools20/excel.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="530" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Listado de Comprobante</td>
    </tr>
  </table>
  <table width="530" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td align="center"><div align="right">Reporte en</div></td>
      <td width="144" align="center"><div align="left">
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
      </div></td>
      <td width="269" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">      <div align="left">
        <table width="480" height="33" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda">
            <td height="13" colspan="6" valign="top" class="titulo-celdanew">Intervalo de Cuentas </td>
          </tr>
          <tr class="formato-blanco">
            <td width="60" height="18"><div align="right">Desde</div></td>
            <td width="133" height="18"><input name="txtcuentadesde" type="text" id="codestpro122" value="" size="22" maxlength="20"></td>
            <td width="40"><div align="left"><a href="javascript:cat_desde()"><img src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
            <td width="85"><div align="right">Hasta</div></td>
            <td width="122"><input name="txtcuentahasta" type="text" id="txtcuentahasta" value="" size="22" maxlength="22"></td>
            <td width="38"><div align="left"><a href="javascript:cat_hasta()"><img src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="7"><strong class="titulo-celdanew">Intervalos de Fechas </strong></td>
            </tr>
          <tr>
            <td height="28"><div align="right"></div></td>
            <td height="28"><div align="right">Desde</div></td>
            <td colspan="2"><div align="left">
              <input name="txtfecdes" type="text" id="txtfecdes" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
            <td width="27">&nbsp;</td>
            <td width="51"><div align="right">Hasta</div></td>
            <td width="198"><div align="left">
              <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fechas ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
          </tr>
          <tr>
            <td height="13" colspan="7" class="titulo-celdanew">Ordenado por </td>
            </tr>
          <tr>
            <td width="29" height="22"><div align="right">
              <input name="rborden" type="radio" class="sin-borde" value="1" checked>
            </div></td>
		    <td colspan="3"><div align="left">Procede-Comprobante-Fecha</div></td>
		   <td><div align="right">
             <input name="rborden" type="radio" class="sin-borde" value="2">
</div></td>
            <td colspan="2">
              <div align="left">Comprobante-Fecha-Procede</div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">
              <input name="rborden" type="radio" class="sin-borde" value="3">
</div></td>
            <td colspan="3"><div align="left">Fecha-Procede-Comprobante</div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22" colspan="7" class="titulo-celdanew">&nbsp;</td>
            </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td colspan="5"><input name="chkmostrar" type="checkbox" class="sin-borde" id="chkmostrar" value="1" checked> 
              Mostrar Descripci&oacute;n de los Detalles.          
                <div align="center"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td colspan="3"><input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="reporte2" type="hidden" id="reporte2" value="<?php print $ls_reporte2;?>"></td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
function ue_search(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		txtcuentadesde = f.txtcuentadesde.value;
		txtcuentahasta = f.txtcuentahasta.value;
		txtfecdes 	   = f.txtfecdes.value;
		txtfechas 	   = f.txtfechas.value;
		tiporeporte	   = f.cmbbsf.value;
		ls_reporte1    = f.reporte.value;
		ls_reporte2    = f.reporte2.value;
		for (i=0;i<f.rborden.length;i++)
		{ 
		   if (f.rborden[i].checked) 
			  break; 
		} 
		document.opcion = f.rborden[i].value; 
		orden=document.opcion;
		if(f.chkmostrar.checked)
		{
			pagina="reportes/"+ls_reporte1+"?hidcodemp="+codemp+"&txtcuentadesde="+txtcuentadesde
					+"&txtcuentahasta="+txtcuentahasta+"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas+"&tiporeporte="+tiporeporte;
		}
		else
		{
			pagina="reportes/"+ls_reporte2+"?hidcodemp="+codemp+"&txtcuentadesde="+txtcuentadesde
					+"&txtcuentahasta="+txtcuentahasta+"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas+"&tiporeporte="+tiporeporte;
		}
		window.open(pagina,"Reportes","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_openexcel(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		txtcuentadesde  = f.txtcuentadesde.value;
		txtcuentahasta  = f.txtcuentahasta.value;
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		tiporeporte=f.cmbbsf.value;
		for (i=0;i<f.rborden.length;i++)
		{ 
		   if (f.rborden[i].checked) 
			  break; 
		} 
		document.opcion = f.rborden[i].value; 
		orden=document.opcion;
		if(f.chkmostrar.checked)
		{
			pagina="reportes/tepuy_scg_rpp_comprobante_formato2_excel.php?hidcodemp="+codemp+"&txtcuentadesde="+txtcuentadesde
					+"&txtcuentahasta="+txtcuentahasta+"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas+"&tiporeporte="+tiporeporte;
		}
		else
		{
			pagina="reportes/tepuy_scg_rpp_comprobante_formato2_sindt_excel.php?hidcodemp="+codemp+"&txtcuentadesde="+txtcuentadesde
					+"&txtcuentahasta="+txtcuentahasta+"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas+"&tiporeporte="+tiporeporte;
		}
		window.open(pagina,"Reportes","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function cat_desde()
{
	f=document.form1;
	window.open("tepuy_cat_scgall.php?obj=txtcuentadesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function cat_hasta()
{
	f=document.form1;
	window.open("tepuy_cat_scgall.php?obj=txtcuentahasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
   
function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}

//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>
