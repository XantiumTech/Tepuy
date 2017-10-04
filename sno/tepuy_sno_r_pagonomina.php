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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","tepuy_sno_r_hpagonomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("tepuy_sno.php");
	$io_sno=new tepuy_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];
	$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","PAGO_NOMINA","tepuy_sno_rpp_hpagonomina.php","C");
	unset($io_sno);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
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
<title >Reporte Pago de N&oacute;mina</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>	</td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu_nomina.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Pago de N&oacute;mina </td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en</div></td>
          <td height="20"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
<?php if($ls_subnom=='1')
{
?>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de subnomina </td>
        </tr>
        <tr>
          <td height="20"><div align="right"> Desde </div></td>
          <td height="20"><input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td height="20"><div align="right">Hasta </div></td>
          <td height="20"><input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
<?php } 
?>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="131" height="22"><div align="right"> Desde </div></td>
          <td width="130"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="135"><div align="right">Hasta </div></td>
          <td width="144"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Ubicaci&oacute;n F&iacute;sica</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodubifis" type="text" id="txtcodubifis" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarubicacionfisica();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesubifis" type="text" class="sin-borde" id="txtdesubifis" size="60" maxlength="100" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Estado</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodest" type="text" id="txcodest" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" value="" size="60" maxlength="50" readonly>
            <input name="txtcodpai" type="hidden" id="txtcodpai" value="058">
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Municipio</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodmun" type="text" id="txtcodmun" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" value="" size="60" maxlength="50" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Parroquia</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodpar" type="text" id="txtcodpar" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarparroquia();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdespar" type="text" class="sin-borde" id="txtdespar" value="" size="60" maxlength="50" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Formato Comprimido </div></td>
          <td><div align="left">
            <input name="chkcomprimido" type="checkbox" class="sin-borde" id="chkcomprmido" value="1" checked>
          </div></td>
            <td width="100"><div align="right"><span class="style1 style14">Tama�o de la Fuente</span></div></td>
            <td width="223" height="22"><select name="cmbfuente" id="cmbfuente">
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              </select></td>
           </tr>
        <tr>
          <td height="22"><div align="right">Quitar conceptos en cero </div></td>
          <td><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td><div align="right">Usar T&iacute;tulo de Concepto</div></td>
          <td><div align="left">
            <input name="chktituloconcepto" type="checkbox" class="sin-borde" id="chktituloconcepto" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Incluir Conceptos Reporte </div></td>
          <td><div align="left">
            <input name="chkconceptoreporte" type="checkbox" class="sin-borde" id="chkconceptoreporte" value="1">
          </div></td>
          <td><div align="right">Incluir Conceptos P2 </div></td>
          <td><div align="left">
            <input name="chkconceptop2" type="checkbox" class="sin-borde" id="chkconceptop2" value="1">
          </div></td>
        </tr>
<?php if($_SESSION["la_nomina"]["adenom"]=="1") {?>		
        <tr>
          <td height="22"><div align="right">Adelanto de Quincena </div></td>
          <td colspan="3"><div align="left">
            <input name="chkadelantoquincena" type="checkbox" class="sin-borde" id="chkadelantoquincena" value="1">
          </div></td>
        </tr>
<?php }?>		
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Unidad Administrativa </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
		    <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
 		   <input name="subnom" type="hidden" id="subnom" value="<?php print $ls_subnom;?>">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"> <div align="right"></div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "tepuywindow_blank_nomina.php";
}

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	fuente = f.cmbfuente.value;
	reporte="tepuy_sno_rpp_pagonomina_barinas_excel.php?conceptocero=1"+"&fuente="+fuente;
	if(li_imprimir==1)
	{	
		//pagina="reportes/"+reporte+"?conceptocero=1";
		pagina="reportes/"+reporte;
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=750,height=650,left=20,top=20,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	
	if(li_imprimir==1)
	{
		fuente = f.cmbfuente.value;
    		historico='0';
		if(f.chkcomprimido.checked)
		{
			reporte="tepuy_sno_rpp_pagonomina_barinas.php";
		}
		else
		{	
			reporte=f.reporte.value;
		}
    		//alert(reporte);	
		//reporte=f.reporte.value;
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		tiporeporte=f.cmbbsf.value;
		subnom=f.subnom.value;
		subnomdes="";
		subnomhas="";
		if(subnom=='1')
		{
			subnomdes=f.txtcodsubnomdes.value;
			subnomhas=f.txtcodsubnomhas.value;
		}
		if(codperdes<=codperhas)
		{
			conceptocero="";
			tituloconcepto="";
			conceptoreporte="";
			conceptop2="";
			if(f.rdborden[0].checked)
			{
				orden="1";
			}
			if(f.rdborden[1].checked)
			{
				orden="2";
			}
			if(f.rdborden[2].checked)
			{
				orden="3";
			}
			if(f.rdborden[3].checked)
			{
				orden="4";
			}
			if(f.chkconceptocero.checked)
			{
				conceptocero=1;
			}
			if(f.chktituloconcepto.checked)
			{
				tituloconcepto=1;
			}
			if(f.chkconceptoreporte.checked)
			{
				conceptoreporte=1;
			}
			if(f.chkconceptop2.checked)
			{
				conceptop2=1;
			}
			codubifis=f.txtcodubifis.value;
			codpai=f.txtcodpai.value;
			codest=f.txtcodest.value;
			codmun=f.txtcodmun.value;
			codpar=f.txtcodpar.value;
			pagina="reportes/"+reporte+"?codperdes="+codperdes+"&codperhas="+codperhas+"&orden="+orden;
			pagina=pagina+"&conceptocero="+conceptocero+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
			pagina=pagina+"&conceptop2="+conceptop2+"&tiporeporte="+tiporeporte+"&codubifis="+codubifis+"&codpai="+codpai;
			pagina=pagina+"&codest="+codest+"&codmun="+codmun+"&codpar="+codpar+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas;
			pagina=pagina+"&fuente="+fuente+"&historico="+historico;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("El rango del personal est� erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarpersonaldesde()
{
	window.open("tepuy_sno_cat_personalnomina.php?tipo=reppagnomdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	window.open("tepuy_sno_cat_personalnomina.php?tipo=reppagnomhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarubicacionfisica()
{
	window.open("tepuy_snorh_cat_ubicacionfisica.php?tipo=pagonomina","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestado()
{
	f=document.form1;
	f.txtcodubifis.value="";
    f.txtdesubifis.value="";
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("tepuy_snorh_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("tepuy_snorh_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_buscarparroquia()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	codmun=ue_validarvacio(f.txtcodmun.value);
	if((codpai!="")||(codest!="")||(codmun!=""))
	{
		window.open("tepuy_snorh_cat_parroquia.php?codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais, un estado y un municipio.");
	}
}
function ue_buscarsubnominadesde()
{
	window.open("tepuy_snorh_cat_subnomina.php?tipo=reportedesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominahasta()
{
	window.open("tepuy_snorh_cat_subnomina.php?tipo=reportehasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>