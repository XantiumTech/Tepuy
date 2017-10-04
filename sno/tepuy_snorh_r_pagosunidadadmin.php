<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_logusr=$_SESSION["la_logusr"];	
	$io_fun_nomina->uf_load_seguridad("SNR","tepuy_snorh_r_pagosunidadadmin.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title >Reporte Consolidado de Pagos por Unidad Administrativa</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de N�mina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	 </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="631" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Reporte Consolidado de Pagos por Unidad Administrativa </td>
        </tr>
        <tr style="display:none">
          <td width="140" height="22"><div align="right"></div></td>
          <td><div align="left">
          </div>
          <td>        
          <td>        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina Desde </div></td>
          <td width="203"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>          </div>
          <td width="72"><div align="right">N&oacute;mina Hasta </div>
          <td width="206"><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> </div>        </tr>
        <tr>
          <td height="22"><div align="right">Periodo Desde </div></td>
          <td><div align="left">
            <input name="txtperdes" type="text" id="txtperdes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiododesde();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>          
            <input name="txtfecdesper" type="hidden" id="txtfecdesper">
          </div>
          <td><div align="right">Periodo Hasta          </div>
          <td><div align="left">
            <input name="txtperhas" type="text" id="txtperhas" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfechasper" type="hidden" id="txtfechasper">
          </div>        </tr>
        <tr>
          <td height="22"><div align="right">Unidad Desde</div></td>
          <td>        <div align="left">
            <input name="txtcodunides" type="text" id="txtcodunides" size="30" readonly>
            <a href="javascript: ue_buscarunidaddes();"><img id="banco" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div>          
          <td><div align="right">Unidad Hasta          </div>          
          <td> <input name="txtcodunihas" type="text" id="txtcodunihas" size="30"  readonly>
          <a href="javascript: ue_buscarunidadhas();"><img id="banco" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="6"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de la N&oacute;mina </div></td>
          <td colspan="5"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre de la N&oacute;mina </div></td>
          <td colspan="5"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="5"><div align="right">
            <input name="operacion" type="hidden" id="operacion">          		
			</div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codunides=f.txtcodunides.value;
		codunihas=f.txtcodunihas.value;		
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		codperdes=f.txtperdes.value;
		codperhas=f.txtperhas.value;			
		if((codnomdes!="")&&(codnomhas!="")&&(codperdes!="")&&(codperhas!="")&&(codunides!="")&&(codunihas!=""))
		{
			orden="";
			if(f.rdborden[0].checked)
			{
				orden="1";
			}
			if(f.rdborden[1].checked)
			{
				orden="2";
			}		
			pagina="reportes/tepuy_snorh_rpp_pagounidad.php?codunides="+codunides+"&codunihas="+codunihas;
			pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codperdes="+codperdes+"&codperhas="+codperhas;
			pagina=pagina+"&orden="+orden;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar las n�minas, los per�odos y las Unidades Administrativas.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}


function ue_buscarnominadesde()
{
	window.open("tepuy_snorh_cat_nomina.php?tipo=replisbandes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("tepuy_snorh_cat_nomina.php?tipo=replisbanhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una n�mina desde.");
	}
}
function ue_buscarperiododesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes!="")&&(codnomhas!=""))
	{
		window.open("tepuy_sno_cat_hperiodo.php?tipo=replisbandes&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes!="")&&(codnomhas!="")&&(f.txtperdes.value!=""))
	{
		window.open("tepuy_sno_cat_hperiodo.php?tipo=replisbanhas&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas y aun per�odo desde.");
	}
}

function ue_buscarunidaddes()
{
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	codperides=f.txtperdes.value;
	codperihas=f.txtperhas.value;
	if((codnomdes!="")&&(codnomhas!="")&&(codperides!="")&&(codperihas!=""))
	{
		window.open("tepuy_sno_cat_huni_ad.php?tipo=pagounides&codnomde="+codnomdes+"&codnomhas="+codnomhas+"&codperides="+codperides+"&codperihas="+codperihas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas y de periodo.");
	}
}
function ue_buscarunidadhas()
{
	codunides=f.txtcodunides.value;
	
	if(codunides!="")
	{
			window.open("tepuy_sno_cat_huni_ad.php?tipo=pagounihas&codnomde="+codnomdes+"&codnomhas="+codnomhas+"&codperides="+codperides+"&codperihas="+codperihas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de Banco desde");
	}
}

</script> 
</html>
