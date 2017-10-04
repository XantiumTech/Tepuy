<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../tepuy_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_sfa.php");
$io_fun_factura = new class_funciones_sfa();
$io_fun_factura->uf_load_seguridad("SFA","tepuy_sfa_r_resumen_facturas.php",$ls_permisos,&$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_factura->uf_select_config("SFA","REPORTE","LISTADO_FACTURAS","tepuy_sfa_rpp_resumen_facturas_waryna.php","C");
require_once("class_folder/tepuy_sfa_c_factura.php");
$io_sfa= new tepuy_sfa_c_factura("../");
$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../soc/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<title>Resumen de Facturas</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
<link href="css/soc.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo2 {font-size: 14}
-->
</style>
</head>
<body>
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $ls_numfacturades = $_POST["txtnumfacturades"];
     $ls_numfacturahas = $_POST["txtnumfacturahas"];
     $ls_cedclides = $_POST["txtcedclides"];
	 $ls_cedclihas = $_POST["txtcedclihas"];
	 $ls_fecfacturades = $_POST["txtfecfacturades"];
	 $ls_fecfacturahas = $_POST["txtfecfacturahas"];
	 $ls_coduniadmdes = $_POST["txtcoduniadmdes"];
	 $ls_coduniadmhas = $_POST["txtcoduniadmhas"];
	 $ls_cedclides = $_POST["txtcodprodes"];
	 $ls_cedclihas = $_POST["txtcodarthas"];
	 $ls_codserdes = $_POST["txtcodserdes"];
	 $ls_codserhas = $_POST["txtcodserhas"];
	$ls_formapago=$_POST["cmbtippro"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_numfacturades = "";
	 $ls_numfacturahas = "";
     $ls_cedclides = "";
	 $ls_cedclihas = "";
     $ls_fecfacturades = '01/'.date("m/Y");
	 $ls_fecfacturahas = date("d/m/Y");
	 $ls_coduniadmdes = "";
	 $ls_coduniadmhas = "";
	 $ls_cedclides = "";
	 $ls_cedclihas = "";
	 $ls_codserdes = "";
	 $ls_codserhas = "";
	$ls_formapago="----";
   }

?>
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="800" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
    <tr>
      <td width="800" height="40" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema" style="text-align:left">Sistema de Facturación</td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a e");?></b></span></div></td>
          </tr>
      </table></td>
    </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--    <tr>
      <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="../soc/js/menu.js"></script></td>
    </tr> -->
    <tr>
      <td height="36" colspan="11" class="toolbar"></td>
    </tr>
    <tr style="text-align:left">
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><a href="javascript: ue_nuevo();"></a><span class="toolbar" style="text-align:left"><a href="javascript: ue_guardar();"></a></span><a href="javascript: ue_buscar();"></a><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir" /></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir" /></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda" /></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <form id="formulario" name="formulario" method="post" action="">
  <?php
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_factura->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_factura);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  ?>
    <table width="543" border="0" cellpa	//-----------------------------------------------------------------------------------------------------------------------------------
      <tr>
        <td height="22" colspan="6" class="titulo-celdanew"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
          Resumen de Facturas
          
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>" /></td>
      </tr>
      <tr>

      </tr>
      <tr style="visibility:hidden">
        <td height="13" colspan="2" style="text-align:right">Reporte en</td>
        <td height="13"><div align="left">
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected="selected">Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
        </div></td>

      </tr>
      <tr>	
        <td height="13" colspan="6"><table width="490" height="41" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
              <input name="tippro" type="hidden" id="tippro" value="<?php print $ls_tiporep; ?>">
            <td height="22" style="text-align:right">Tipo de Reporte</td>
            <td height="22" colspan="2"><div  id="tipoproducto" align="left">
               <select name="cmbtiprep" id="cmbtiprep">
                <option value="R">Resumen Detallado de Facturas</option>
                <option value="C">Consolidado de Facturas</option>
              </select>
            </div></td>
          </tr>

        </table></td>
      </tr>
      <tr>	
        <td height="13" colspan="6"><table width="490" height="41" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
              <input name="tippro" type="hidden" id="tippro" value="<?php print $ls_forpag; ?>">
            <td height="22" style="text-align:right">Forma de pago</td>
            <td height="22" colspan="2"><div  id="tipoproducto" align="left">
              <?php $io_sfa->uf_sfa_combo_formapago($ls_formapago); ?>
            </div></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Facturas </strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtnumfacturades" type="text" id="txtnumfacturades" value="<?php print $ls_numfacturades ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('tepuy_sfa_cat_facturas.php?tipo=DESDE');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtnumfacturahas" type="text" id="txtnumfacturahas" value="<?php print $ls_numfacturahas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('tepuy_sfa_cat_facturas.php?tipo=HASTA');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
        <td height="13" colspan="6"><table width="490" height="41" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="13" style="text-align:right"><div align="left"><strong>Tipo de Cliente </strong></div></td>
          </tr>
          <tr>
              <input name="tippro" type="hidden" id="tippro" value="<?php print $ls_tipocli; ?>">
            <td height="22" style="text-align:right">Filtro por Tipo de Cliente</td>
            <td height="22" colspan="2"><div  id="tipocliente" align="left">
               <select name="cmbtipcli" id="cmbtipcli">
                <option value="0">Ambas</option>
                <option value="2">Cliente</option>
                <option value="1">Trabajador	</option>
              </select>
            </div></td>
          </tr>
        </table></td>
      <tr>
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
          <tr>
            <td colspan="4" style="text-align:left"><strong>Clientes</strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtcedclides" type="hidden" id="txtcedclides" value="<?php print $ls_cedclides ?>"
 size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
	<input name="txtnomclides"    type=text id="txtnomclides"    class="formato-blanco"  size=20 maxlength=30 readonly>

              <a href="javascript: ue_catalogo('tepuy_catdinamic_cliente.php?tipo=REPDES');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtcedclihas" type="hidden" id="txtcedclihas" value="<?php print $ls_cedclihas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
	<input name="txtnomclihas"    type=text id="txtnomclihas"    class="formato-blanco"  size=20 maxlength=30 readonly>
              <a href="javascript: ue_catalogo('tepuy_catdinamic_cliente.php?tipo=REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
<!--	<input name="txtnomclides" type="text" class="sin-borde" id="txtnomclides" value="<?php print $ls_nomclides ?>" size="50" readonly>
	<input name="txtnomclihas" type="text" class="sin-borde" id="txtnomclihas" value="<?php print $ls_nomclihas ?>" size="50" readonly> -->
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="60" height="13">&nbsp;</td>
        <td width="26" height="13">&nbsp;</td>
        <td width="164" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="99" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:center"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Fecha</strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtfecfacturades" type="text" id="txtfecfacturades" value="<?php print $ls_fecfacturades ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" />
              <a href="javascript: ue_catalogo('tepuy_soc_cat_analisis_cotizacion.php?catalogo=txtnumanacotdes');"></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtfecfacturahas" type="text" id="txtfecfacturahas" value="<?php print $ls_fecfacturahas ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" />
              <a href="javascript: ue_catalogo('tepuy_soc_cat_analisis_cotizacion.php?catalogo=txtnumanacothas');"></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>cmbtipcli
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:center"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
         <tr>
            <td colspan="4" style="text-align:left"><div align="center"><strong>Todos</strong>
                <input name="esttip" type="radio" class="sin-borde" onClick="uf_deshabilitar()" value="T" checked="checked"/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Productos </strong>
  <input name="esttip" type="radio" class="sin-borde" value="P" onClick="uf_deshabilitar()"/>
 <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp; Servicios </strong>
  <input name="esttip" type="radio" class="sin-borde" value="S" onClick="uf_deshabilitar()"/> -->
            </div></td>
          </tr>
          <tr class="formato-blanco">
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
  <!--        <tr>
            <td width="96" style="text-align:right">Servicios Desde</td>
            <td width="170" style="text-align:left"><span class="Estilo2">
              <input name="txtcodserdes" type="text"  style="text-align:center" class="formato-blanco" id="txtcodserdes" value="<?php print $ls_codserdes ?>" size="22" maxlength="20" readonly="readonly" />
            </span><a href="javascript:ue_catalogo_servicios('REPDES');"><img src="../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Servicios" name="busserdes" width="15" height="15" border="0" id="busserdes" /></a></td>
            <td width="42" style="text-align:right"><span style="text-align:left">Hasta </span></td>
            <td width="180" style="text-align:left"><span class="Estilo2">
              <input name="txtcodserhas" type="text" style="text-align:center" class="formato-blanco" id="txtcodserhas" value="<?php print $ls_codserhas ?>" size="22" maxlength="20" readonly="readonly" />
            </span><a href="javascript:ue_catalogo_servicios('REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Servicios" name="busserhas" width="15" height="15" border="0" id="busserhas" /></a></td>
          </tr> -->
        </table></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="3"><strong>Estatus</strong></td>
          </tr>
          <tr>
            <td width="162"><input name="rdemi" type="checkbox" class="sin-borde" id="rdemi" value="1" />
              Emitida</td>
            <td width="155"><input name="rdpre" type="checkbox" class="sin-borde" id="rdpre" value="1" />              
              Anulada</td>
            <td width="171"><input name="rdcon" type="checkbox" class="sin-borde" id="rdcon" value="1" />
              <span class="Estilo2">Procesada</span></td>
          </tr>
          <tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" colspan="6" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
f = document.formulario;

function ue_catalogo_producto(ls_tipo)
{
    f.tipo.value=ls_tipo
	window.open("tepuy_sfa_cat_producto_reporte.php?tipo="+ls_tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
}

function rellenar_cad(cadena,longitud,objeto)
{//1
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        = longitud-lencad;
	if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
			 {
			   cadena_ceros=cadena_ceros+"0";
			 }
	     cadena=cadena_ceros+cadena;
		 objeto.value=cadena;
	   }
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 
	var valido = false; 
    var diad = f.txtfecanades.value.substr(0, 2); 
    var mesd = f.txtfecanades.value.substr(3, 2); 
    var anod = f.txtfecanades.value.substr(6, 4); 
    var diah = f.txtfecanahas.value.substr(0, 2); 
    var mesh = f.txtfecanahas.value.substr(3, 2); 
    var anoh = f.txtfecanahas.value.substr(6, 4); 
    
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
		alert("El rango de fecha es invalido !!!");
	} 
	return valido;
   } 
   
function ue_imprimir()
{
		
	ls_numfacturades = f.txtnumfacturades.value;
	ls_numfacturahas = f.txtnumfacturahas.value;
	ls_cedclides    = f.txtcedclides.value;
	ls_cedclihas    = f.txtcedclihas.value;
	//alert("Desde: "+ls_cedclides+" Hasta: "+ls_cedclihas);
	ls_fecfacturades = f.txtfecfacturades.value;
	ls_fecfacturahas = f.txtfecfacturahas.value;
	ls_codprodes    = f.txtcodprodes.value;
	ls_codprohas    = f.txtcodprohas.value;
	ls_tiprep=f.cmbtiprep.value;
	ls_forpag= f.cmbforpag.value;
	tipo_cliente=f.cmbtipcli.value;
	//alert(tipo_cliente);
	if (f.rdemi.checked) //EMITIDA
	{ 
		rdemi = 1;
	}
	else
	{ 
		rdemi = 0;
	}
	
	if (f.rdpre.checked)  // ANULADA
	{ 
		rdanu = 1;
	}
	else
	{ 
		rdanu = 0;
	}

	if (f.rdcon.checked) //  COMPROMETIDA
	{ 
		rdcon = 1;
	}
	else
	{ 
		rdcon = 0;
	}

		ls_reporte  = f.formato.value;
		if(ls_tiprep=="R")
		{ 
			ls_reporte  = "tepuy_sfa_rpp_resumen_facturas.php";
		}
		else
		{ 
			ls_reporte  = "tepuy_sfa_rpp_consolidado_facturas.php";
		}
		//alert(ls_reporte);
		tiporeporte = f.cmbbsf.value;
		pagina="reportes/"+ls_reporte+"?numfacturades="+ls_numfacturades+"&numfacturahas="+ls_numfacturahas+"&fecfacturades="+ls_fecfacturades+"&fecfacturahas="+ls_fecfacturahas+"&cedclides="+ls_cedclides+"&cedclihas="+ls_cedclihas+"&rdanu="+rdanu+"&rdemi="+rdemi+"&rdcon="+rdcon+"&formapago="+ls_forpag+"&codprodes="+ls_codprodes+"&codprohas="+ls_codprohas+"&tipocliente="+tipo_cliente;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
		
}  

function currencyDate(date)
{ 
	ls_date=date.value;
	li_long=ls_date.length;
	if (li_long==2)
	   {
	     ls_date   = ls_date+"/";
	 	 ls_string = ls_date.substr(0,2);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1)&&(li_string<=31))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value="";
			}
			
	   }
	if (li_long==5)
	   {
	     ls_date   = ls_date+"/";
		 ls_string = ls_date.substr(3,2);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1)&&(li_string<=12))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value=ls_date.substr(0,3);
			}
	   }
	if (li_long==10)
	   {
	     ls_string = ls_date.substr(6,4);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1900)&&(li_string<=2090))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value=ls_date.substr(0,6);
			}
	   }
} 

function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_deshabilitar()
{
   if (document.formulario.esttip[0].checked)
   {
	  document.formulario.txtcodprodes.value="";          
	  document.formulario.txtcodprohas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  document.formulario.txtcodprodes.disabled=true;	   
	  document.formulario.txtcodprohas.disabled=true;	   
	  document.formulario.txtcodserdes.disabled=true;	   
	  document.formulario.txtcodserhas.disabled=true;	   
	  eval("document.images['busartdes'].style.visibility='visible'");
	  eval("document.images['busarthas'].style.visibility='visible'");
	  eval("document.images['busserdes'].style.visibility='visible'");
	  eval("document.images['busserhas'].style.visibility='visible'");
   }
   
   if (document.formulario.esttip[1].checked)
   {
	  document.formulario.txtcodprodes.value="";
	  document.formulario.txtcodprohas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  
	  document.formulario.txtcodprodes.disabled=true;	   
	  document.formulario.txtcodprohas.disabled=true;	   
	  eval("document.images['busartdes'].style.visibility='visible'");
	  eval("document.images['busarthas'].style.visibility='visible'");
	  document.formulario.txtcodserdes.disabled=false;	   
	  document.formulario.txtcodserhas.disabled=false;	   
	  eval("document.images['busserdes'].style.visibility='hidden'");
	  eval("document.images['busserhas'].style.visibility='hidden'");
   }
   
   if (document.formulario.esttip[2].checked)
   {
	  document.formulario.txtcodprodes.value="";
	  document.formulario.txtcodprohas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  
	  document.formulario.txtcodprodes.disabled=false;	   
	  document.formulario.txtcodprohas.disabled=false;	   
	  eval("document.images['busartdes'].style.visibility='hidden'");
	  eval("document.images['busarthas'].style.visibility='hidden'");		  
	  document.formulario.txtcodserdes.disabled=true;	   
	  document.formulario.txtcodserhas.disabled=true;	   
	  eval("document.images['busserdes'].style.visibility='visible'");
	  eval("document.images['busserhas'].style.visibility='visible'");
   }
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
