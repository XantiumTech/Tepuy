<?php
session_start();
ini_set('display_errors', 1);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_folder/class_funciones_sfa.php");
$io_mov_sfa=new class_funciones_sfa();
$io_mov_sfa->uf_load_seguridad("SFA","tepuy_sfa_r_mov_inventario.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ld_fecemides=date("01/m/Y");
$ld_fecemihas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Resumen de Movimiento de Inventario </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/sfa.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-weight: bold}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Facturacion </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>-->
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
  <tr>
    <td height="42" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a><a href="../sfa/tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_mov_sfa->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_mov_sfa);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
  <table width="500" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="438" colspan="2" class="titulo-ventana">Resumen de Movimiento de Inventario </td>
    </tr>
  </table>
  <table width="494" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><table width="452" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr style="display:none">
          <td colspan="2"><strong>Reporte en</strong>
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select></td>
        </tr>
        <tr>
          <td colspan="2"><strong> Productos </strong></td>
        </tr>
        <tr>
            <td width="96" style="text-align:right">Producto Desde</td>
            <td style="text-align:left"><span class="Estilo2">
              <input name="txtcodprodes"    id="txtcodprodes" type=hidden value="<?php print $ls_cedclides ?>" size="22" maxlength="20" readonly="readonly" />
	<input name="txtdenprodes"    type=text id="txtdenprodes"    class="formato-blanco"  size=20 maxlength=30 readonly>
              <a href="javascript:ue_catalogo_producto('DESDE');"><img src="../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Productos" name="busartdes" width="15" height="15" border="0" class="weekend" id="busartdes" /></a></span></td>
            <td style="text-align:right"><span style="text-align:left">Hasta</span></td>
            <td style="text-align:left"><span class="Estilo2">
              <input name="txtcodprohas"  id="txtcodprohas" type=hidden value="<?php print $ls_cedclihas ?>" size="22" maxlength="20" readonly="readonly" />
	<input name="txtdenprohas"    type=text id="txtdenprohas"    class="formato-blanco"  size=20 maxlength=30 readonly>
            </span><a href="javascript:ue_catalogo_producto('HASTA');"><img src="../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Productos" name="busarthas" width="15" height="15" border="0" id="busarthas" />
            <input name="tipo" type="hidden" id="tipo" />
            </a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="33" colspan="4" align="center">      <div align="left">
        <table width="452" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><strong>Intervalo de Fechas </strong></td>
            <td width="63">&nbsp;</td>
            <td width="167">&nbsp;</td>
            <td width="56">&nbsp;</td>
          </tr>
          <tr>
            <td width="78"><div align="right">Desde</div></td>
            <td width="75"><input name="txtdesde" type="text" id="txtdesde"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecemides; ?>" size="12" maxlength="10"  datepicker="true"></td>
            <td><div align="right">Hasta</div></td>
            <td><div align="left">
                <input name="txthasta" type="text" id="txthasta"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecemihas; ?>" size="12" maxlength="10"  datepicker="true">
            </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center">&nbsp;</td>
<!--      <td colspan="3" align="left"><input name="chkexistencia" type="checkbox" class="sin-borde" id="chkexistencia" value="1">
      Mostrar Art&iacute;culos sin Existencia </td>
    </tr> 
    <tr>
      <td width="142" height="22" align="center"><div align="right" class="style1 style14"></div></td>
      <td colspan="2" align="left">        <div align="right" class="style1 style14"></div></td>
      <td width="322" align="center"><div align="left">
        <input name="hidunidad" type="hidden" id="hidunidad">
        <input name="hidstatus" type="hidden" id="hidstatus">
</div></td>-->
    </tr>
    <tr>
      <td colspan="4" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><div align="left">
        <table width="452" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="195"><div align="center"></div>              <div align="center"><strong>Producto</strong></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right"></div>              <div align="right"></div>              <div align="right">C&oacute;digo
                    <input name="radioordenart" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="244">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right"></div>              <div align="right"></div>              <div align="right">Denominaci&oacute;n
                    <input name="radioordenart" type="radio" class="sin-borde" value="radiobutton">
              </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="24" colspan="4" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f = document.formulario;

function ue_catalogo_producto(ls_tipo)
{
	//alert(ls_tipo);
	window.open("tepuy_sfa_cat_producto_reporte.php?tipo="+ls_tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
}
	
function uf_imprimir()
{
	valido=ue_comparar_intervalo();
	if(valido)
	{
		f=document.form1;
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{
			li_existencia=0;
			ld_desde=      f.txtdesde.value;
			ld_hasta=      f.txthasta.value;
			ls_codprodes=   f.txtcodprodes.value;
			ls_codprohas=   f.txtcodprohas.value;
			if ((ld_desde!="")&&(ld_hasta!=""))
			{
				if(f.radioordenart[0].checked)
				{
					li_ordenpro=0;
				}
				else
				{
					li_ordenpro=1;
				}
				//tipoformato=f.cmbbsf.value;
				window.open("reportes/tepuy_sfa_rpp_mov_inventario.php?ordenpro="+li_ordenpro+"&fecdesde="+ld_desde+"&fechasta="+ld_hasta+"&codprodes="+ls_codprodes+"&codprohas="+ls_codprohas,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe indicar un rango de fechas");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operaci�n");
		}
	}
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

//--------------------------------------------------------
//	Funci�n que da formato a la fecha colocando los separadores (/).
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}

//--------------------------------------------------------
//	Funci�n que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false; 
    var diad = f.txtdesde.value.substr(0, 2); 
    var mesd = f.txtdesde.value.substr(3, 2); 
    var anod = f.txtdesde.value.substr(6, 4); 
    var diah = f.txthasta.value.substr(0, 2); 
    var mesh = f.txthasta.value.substr(3, 2); 
    var anoh = f.txthasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
		
	} 
	return valido;
   } 

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>