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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","tepuy_cxp_r_solicitudesf1.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_cxp->uf_select_config("CXP","REPORTE","FORMATO_SOLF1","tepuy_cxp_rpp_solicitudesf1.php","C");
	$ls_activo          = "checked";
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (array_key_exists("codestpro1",$_POST))
   {
     $ls_codestpro1=$_POST["codestpro1"];	   
   }
else
   {
     $ls_codestpro1="";
   }
if (array_key_exists("codestpro2",$_POST))
   {
    $ls_codestpro2=$_POST["codestpro2"];	   
   }
else
   {
     $ls_codestpro2="";
   }
if (array_key_exists("codestpro3",$_POST))
   {
     $ls_codestpro3=$_POST["codestpro3"];	   
   }
else
   {
     $ls_codestpro3="";
   }
if (array_key_exists("codestpro4",$_POST))
   {
     $ls_codestpro4=$_POST["codestpro4"];	   
   }
else
   {
     $ls_codestpro4="";
   }
if (array_key_exists("codestpro5",$_POST))
   {
     $ls_codestpro5=$_POST["codestpro5"];	   
   }
else
   {
     $ls_codestpro5="";
   }
if (array_key_exists("codestpro1h",$_POST))
   {
      $ls_codestpro1h=$_POST["codestpro1h"];	   
   }
else
   {
      $ls_codestpro1h="";
   }
if (array_key_exists("codestpro2h",$_POST))
   {
     $ls_codestpro2h=$_POST["codestpro2h"];	   
   }
else
   {
     $ls_codestpro2h="";
   }
if (array_key_exists("codestpro3h",$_POST))
   {
     $ls_codestpro3h=$_POST["codestpro3h"];	   
   }
else
   {
     $ls_codestpro3h="";
   }
if (array_key_exists("codestpro4h",$_POST))
   {
     $ls_codestpro4h=$_POST["codestpro4h"];	   
   }
else
   {
     $ls_codestpro4h="";
   }
if (array_key_exists("codestpro5h",$_POST))
   {
     $ls_codestpro5h=$_POST["codestpro5h"];    
   }
else
   {
     $ls_codestpro5h="";
   }
if (array_key_exists("txtcuentades",$_POST))
   {
     $ls_cuentades=$_POST["txtcuentades"];    
   }
else
   {
     $ls_cuentades="";
   }
if (array_key_exists("txtcuentahas",$_POST))
   {
     $ls_cuentahas=$_POST["txtcuentahas"];
   }
else
   {
     $ls_cuentahas="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Recepciones de Documentos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Orden de Pago </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>    </td>
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
   <td height="20" width="24" class="toolbar"><div align="center"><a href="javascript: uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.png" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
   <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
   <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
   <td class="toolbar" width="255"><div align="center"></div></td>
   <td class="toolbar" width="71">&nbsp;</td>
   <td class="toolbar" width="71"><div align="center"></div></td>
   <td class="toolbar" width="71"><div align="center"></div></td>
   <td class="toolbar" width="71"><div align="center"></div></td>
   <td class="toolbar" width="71"><div align="center"></div></td>
   <td class="toolbar" width="68"><div align="center"></div></td>
   <td class="toolbar" width="3">&nbsp;</td>
 </tr>
<!--  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.png" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>-->
</table>
</div> 
<p>&nbsp;	</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="573"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center" class="titulo-ventana">Reporte de Solicitudes de Pago </td>
  </tr>
  <tr style="visibility:hidden">
    <td height="22" colspan="3" align="center"><div align="left">Reporte en
      <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
    </div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="199" height="22"><div align="right">
          <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();" checked>
          Todos</div></td>
        <td width="89" height="22"><div align="center">
          <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
          Proveedor</div></td>
        <td width="215" height="22"><div align="left">
          <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
          Beneficiario</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Desde
          <input name="txtcoddes" type="text" id="txtcoddes" size="20" readonly>
              <a href="javascript: ue_catalogo_proben('REPDES');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></div></td>
        <td><div align="right">Hasta</div></td>
        <td><input name="txtcodhas" type="text" id="txtcodhas" size="20" readonly>
            <a href="javascript: ue_catalogo_proben('REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td>
       </tr>
    </table></td>
  </tr>

  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="33" colspan="3" align="center"><div align="left">
      <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="13" colspan="9"valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
        </tr>
        <tr>
		    <tr class="formato-blanco">
		      <td width="38" height="18"><div align="right">Desde</div></td>
		      <td width="136" height="18"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20"></td>
		      <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
		      <td width="20"></td>
		      <td width="135"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6"></td>
		      <td width="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
		      <td width="20"></td>
		      <td width="136"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3"></td>
		      <td width="24"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
	        </tr>
	        		    <tr class="formato-blanco">
            <td height="29"><div align="right">Hasta</div></td>
            <td height="29"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="22" maxlength="20"></td>
            <td><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td></td>
            <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="22" maxlength="6"></td>
            <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td></td>
            <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="22" maxlength="3"></td>
            <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
      </table>
    </div></td>

   <tr>
    <td height="33" colspan="3" align="center"><div align="left">
      <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="13" colspan="9"valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Intervalo de Cuentas </strong></td>
        </tr>
        <tr>
        <tr class="formato-blanco">

            <td width="96" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="167"><div align="left">
              <input name="txtcuentades" readonly="true" style="text-align:center" type="text" id="txtcuentades" value="<?php print $ls_cuentades; ?>">
              <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="94">
              <div align="right"></div>                <div align="right">Hasta</div></td>
            <td width="120"><input name="txtcuentahas" readonly="true" style="text-align:center" type="text" id="txtcuentahas" value="<?php print $ls_cuentahas; ?>"></td>
            <td width="80"><a href="javascript:catalogo_cuentahas();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          </tr>
        </table>

  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="33" colspan="3" align="center"><div align="left">
      <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="5"><strong>Fecha de Registro </strong></td>
        </tr>
        <tr>
          <td width="136"><div align="right">Desde</div></td>
          <td width="101"><input name="txtfecemides" type="text" id="txtfecemides"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
          <td width="42"><div align="right">Hasta</div></td>
          <td width="129"><div align="left">
            <input name="txtfecemihas" type="text" id="txtfecemihas"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
          </div></td>
          <td width="101">&nbsp;</td>
        </tr>
                  <tr>
    <td height="22" colspan="3" align="center"><input name="chkincluircatpro" type="checkbox" class="sin-borde" id="chkincluircatpro" value="1" checked <?php print $ls_activo; ?>> 
      Incluir Categorias Programaticas y Partidas</td>
  </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><div align="left" class="style14"></div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><table width="511" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="3"><strong>Estatus</strong></td>
      </tr>
      <tr>
        <td width="148"><input name="chkestsol" type="checkbox" class="sin-borde" id="chkestsol" value="1">
          Emitida </td>
        <td width="169"><input name="chkestsol" type="checkbox" class="sin-borde" id="chkestsol" value="1">
          Contabilizada</td>
        <td width="192"><input name="chkestsol" type="checkbox" class="sin-borde" id="chkestsol" value="1">
          Anulada</td>
      </tr>
      <tr>
        <td><input name="chkestsol" type="checkbox" class="sin-borde" id="chkestsol" value="1">
          Programaci&oacute;n de Pago </td>
        <td><input name="chkestsol" type="checkbox" class="sin-borde" id="chkestsol" value="1">
          Pagada</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
<p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
<input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
	function ue_limpiarproben()
	{
		f=document.formulario;
		f.txtcoddes.value="";
		f.txtcodhas.value="";
	}

	function ue_catalogo(ls_catalogo)
	{
		// abre el catalogo que se paso por parametros
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}


  function catalogo_cuentas()
{
  f=document.formulario;
  codestpro1  = f.codestpro1.value;
  codestpro2  = f.codestpro2.value;
  codestpro3  = f.codestpro3.value;
  codestpro1h = f.codestpro1h.value;
  codestpro2h = f.codestpro2h.value;
  codestpro3h = f.codestpro3h.value;
  pagina="tepuy_cat_ctasrep.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
        +"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h;
    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");

}

function catalogo_cuentahas()
{
  f=document.formulario;
  codestpro1  = f.codestpro1.value;
  codestpro2  = f.codestpro2.value;
  codestpro3  = f.codestpro3.value;
  codestpro1h = f.codestpro1h.value;
  codestpro2h = f.codestpro2h.value;
  codestpro3h = f.codestpro3h.value;
    pagina="tepuy_cat_ctasrephas.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
         +"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h;
    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}


	function ue_catalogo_proben(ls_tipo)
	{
		f=document.formulario;
		valido=true;		
		if(f.rdproben[0].checked)
		{
			valido=false;
		}
		if(f.rdproben[1].checked)
		{
			ls_catalogo="tepuy_cxp_cat_proveedor.php?tipo="+ls_tipo+"";
		}
		if(f.rdproben[2].checked)
		{
			ls_catalogo="tepuy_cxp_cat_beneficiario.php?tipo="+ls_tipo+"";
		}
		if(valido)
		{		
			window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe indicar si es Proveedor ó Beneficiario");
		}
	}
	
	function uf_imprimir()
	{
			f=document.formulario;
			li_imprimir=f.imprimir.value;
			if(li_imprimir==1)
			{
				if(f.rdproben[0].checked)
				{
					tipproben="";
				}
				else
				{
					if(f.rdproben[1].checked)
					{
						tipproben="P";
					}
					else
					{
						tipproben="B";
					}
				}
				codprobendes=f.txtcoddes.value;
				codprobenhas=f.txtcodhas.value;
				fecemides=f.txtfecemides.value;
				fecemihas=f.txtfecemihas.value;
				codestpro1 =f.codestpro1.value;
				codestpro2 =f.codestpro2.value;
				codestpro3 =f.codestpro3.value;
				codestpro1h=f.codestpro1h.value;
				codestpro2h=f.codestpro2h.value;
				codestpro3h=f.codestpro3h.value;
        cuentades = f.txtcuentades.value;
        cuentahas = f.txtcuentahas.value;
				if(f.chkestsol[0].checked)
				{emitida=1;}
				else
				{emitida=0;}
				if(f.chkestsol[1].checked)
				{contabilizada=1;}
				else
				{contabilizada=0;}
				if(f.chkestsol[2].checked)
				{anulada=1;}
				else
				{anulada=0;}
				if(f.chkestsol[3].checked)
				{propago=1;}
				else
				{propago=0;}
				if(f.chkestsol[4].checked)
				{pagada=1;}
				else
				{pagada=0;}
				tiporeporte=f.cmbbsf.value;
				if(f.chkincluircatpro.checked==true)
				{
					formato="tepuy_cxp_rpp_solicitudesf1A4.php";
				}
				else
				{
					formato=f.formato.value;
				}
				pantalla="reportes/"+formato+"?tipproben="+tipproben+"&codprobendes="+codprobendes+"&codprobenhas="+codprobenhas+"&fecemides="+fecemides+"&fecemihas="+fecemihas+"&emitida="+emitida+"&contabilizada="+contabilizada+"&anulada="+anulada+"&propago="+propago+"&pagada="+pagada+"&tiporeporte="+tiporeporte+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&cuentades="+cuentades+"&cuentahas="+cuentahas+"";
				window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{alert("No tiene permiso para realizar esta operación");}
	}

function ue_openexcel()
{
	f = document.formulario;
	li_imprimir = f.imprimir.value;
	if(li_imprimir==1)
	{
		if(f.rdproben[0].checked)
		{
			tipproben="";
		}
		else
		{
			if(f.rdproben[1].checked)
			{
				tipproben="P";
			}
			else
			{
				tipproben="B";
			}
		}
		codprobendes=f.txtcoddes.value;
		codprobenhas=f.txtcodhas.value;
		fecemides=f.txtfecemides.value;
		fecemihas=f.txtfecemihas.value;
		codestpro1 =f.codestpro1.value;
		codestpro2 =f.codestpro2.value;
		codestpro3 =f.codestpro3.value;
		codestpro1h=f.codestpro1h.value;
		codestpro2h=f.codestpro2h.value;
		codestpro3h=f.codestpro3h.value;
	        cuentades = f.txtcuentades.value;
	        cuentahas = f.txtcuentahas.value;
		if(f.chkestsol[0].checked)
			{emitida=1;}
		else
			{emitida=0;}
		if(f.chkestsol[1].checked)
			{contabilizada=1;}
		else
			{contabilizada=0;}
		if(f.chkestsol[2].checked)
			{anulada=1;}
		else
			{anulada=0;}
		if(f.chkestsol[3].checked)
			{propago=1;}
		else
			{propago=0;}
		if(f.chkestsol[4].checked)
			{pagada=1;}
		else
			{pagada=0;}
		formato="tepuy_cxp_rpp_solicitudesf1A4_excel.php";
		pagina="reportes/"+formato+"?tipproben="+tipproben+"&codprobendes="+codprobendes+"&codprobenhas="+codprobenhas+"&fecemides="+fecemides+"&fecemihas="+fecemihas+"&emitida="+emitida+"&contabilizada="+contabilizada+"&anulada="+anulada+"&propago="+propago+"&pagada="+pagada+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&cuentades="+cuentades+"&cuentahas="+cuentahas+"";
		//alert(pagina);
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}

	function ue_cerrar()
	{
		window.location.href="tepuywindow_blank.php";
	}

	function catalogo_estpro1()
{
	   pagina="tepuy_cat_public_estpro1.php?tipo=reporte";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.formulario;
	codestpro1=f.codestpro1.value;
	//alert(codestpro1);
	if(codestpro1!="")
	{
		pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.formulario;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;

		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="tepuy_cat_public_estpro.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}

}
function catalogo_estprohas1()
{
	   pagina="tepuy_cat_public_estpro1.php?tipo=rephas";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estprohas2()
{
	f=document.formulario;
	codestpro1h=f.codestpro1h.value;
	if((codestpro1h!=""))
	{
		pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1h+"&tipo=rephas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estprohas3()
{
	f=document.formulario;
	codestpro1h=f.codestpro1h.value;
	codestpro2h=f.codestpro2h.value;
	codestpro3h=f.codestpro3h.value;

		if((codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h==""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1h+"&codestpro2="+codestpro2h+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="tepuy_cat_public_estpro.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}

}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
