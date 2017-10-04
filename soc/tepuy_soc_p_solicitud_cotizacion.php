<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../tepuy_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
$io_fun_compra = new class_funciones_soc();
$io_fun_compra->uf_load_seguridad("SOC","tepuy_soc_p_solicitud_cotizacion.php",$ls_permisos,&$la_seguridad,$la_permisos);
//$ls_reporte = $io_fun_compra->uf_select_config("SOC","REPORTE","FORMATO_SOLCOT","tepuy_soc_rfs_solicitud_cotizacion.php","C");
$ls_reporte = "tepuy_soc_rfs_solicitud_cotizacion_waryna.php";

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
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 11/04/2007			Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fundb,$io_fun_compra,$ls_parametros,$ls_uniejeaso;
		global $ls_numsolcot,$ls_codemp,$ls_operacion,$ls_fecregsolcot,$ls_tipsolcot,$ls_cedpersol,$ls_codcarper,$ls_coduniadm,$ls_denuniadm,$ls_telpersol;
		global $ls_faxpersol,$li_totrowsep,$li_totrowproveedores,$li_totrowbienes,$li_totrowservicios,$ls_nompersol,$ls_obssolcot,$ls_consolcot;
		global $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_existe,$ls_estsolcot,$ls_estatus;
		
	    require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_funciones_db.php");
	    require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
		
		$io_include	   = new tepuy_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_funciondb  = new class_funciones_db($io_conexion); 
		$io_keygen     = new tepuy_c_generar_consecutivo();
		
		$ls_numsolcot    = $io_keygen->uf_generar_numero_nuevo('SOC','soc_sol_cotizacion','numsolcot','SOCSOL',15,"","","");
		$ls_fecregsolcot = date("d/m/Y");
		$ls_cedpersol    = "";
		$ls_obssolcot    = "";
		$ls_consolcot    = ""; 
		$ls_codcarper    = "";
		$ls_coduniadm    = "";
		$ls_deppersol    = "";
		$ls_nompersol	 = "";
		$ls_nomcarsol	 = "";
	    $ls_denuniadm    = "";
		$ls_uniejeaso    = "";
		$ls_telpersol    = "";
		$ls_faxpersol    = "";
		$ls_parametros   = "";
		$ls_codestpro1   = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = "";
		$li_totrowsep         = 0;
		$li_totrowproveedores = 0;
		$li_totrowbienes      = 0;
		$li_totrowservicios   = 0;
		$ls_operacion         = $io_fun_compra->uf_obteneroperacion();
		$ls_existe            = $io_fun_compra->uf_obtenerexiste();	
		$ls_tipsolcot         = "-";
		$ls_estatus           = "REGISTRO";
		unset($io_keygen);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 17/03/2007			Fecha �ltima Modificaci�n : 29/04/2007
		//////////////////////////////////////////////////////////////////////////////
	
		global $ls_estsolcot,$ls_numsolcot,$ls_tipsolcot,$ls_obssolcot,$ls_coduniadm,$ls_cedpersol,$ls_telpersol,$ls_faxpersol,$ls_codcarper,$ls_fecregsolcot;
		global $li_totrowsep,$li_totrowbienes,$li_totrowservicios,$li_totrowproveedores,$ls_estatus,$ls_consolcot,$ls_uniejeaso;
		
		
		$ls_estsolcot         = $_POST["hidestsolcot"];
		$ls_estatus           = $_POST["txtestsolcot"];
		$ls_numsolcot         = str_pad($_POST["txtnumsolcot"],15,0,0);
		$ls_tipsolcot         = $_POST["cmbtipsolcot"];
		$ls_obssolcot 	      = $_POST["txtobssolcot"];
		$ls_consolcot 	      = $_POST["txtconsolcot"];
		$ls_coduniadm 	 	  = str_pad($_POST["txtcoduniadm"],10,0,0);
		$ls_uniejeaso 	 	  = $_POST["txtuniejeaso"];
		$ls_cedpersol         = $_POST["txtcedper"];
		//$ls_cedpersol         = "000000";		
		$ls_telpersol 	      = $_POST["txttelpersol"];
		$ls_faxpersol         = $_POST["txtfaxpersol"];
		$ls_codcarper         = str_pad($_POST["txtcodcarper"],10,0,0);
		$ls_fecregsolcot 	  = $_POST["txtfecregsolcot"];
		$li_totrowsep         = $_POST["totrowsep"];
		$li_totrowbienes      = $_POST["totrowbienes"];
		$li_totrowservicios   = $_POST["totrowservicios"];
		$li_totrowproveedores = $_POST["totrowproveedores"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creaci�n: 06/05/2007								Fecha �ltima Modificaci�n : 06/05/2007
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrowbienes,$li_totrowservicios,$li_totrowsep,$li_totrowproveedores;
			
		for($li_i=1;($li_i<$li_totrowbienes);$li_i++)
		{
			$ls_codart = $_POST["txtcodart".$li_i];
			$ls_denart = $_POST["txtdenart".$li_i];
			$li_canart = $_POST["txtcanart".$li_i];
			$li_codiva = $_POST["txtcodiva".$li_i];
			$ls_numsep = $_POST["hidnumsep".$li_i];
			$as_parametros=$as_parametros."&txtcodart".$li_i."=".$ls_codart."&txtdenart".$li_i."=".$ls_denart."".
					   					  "&txtcanart".$li_i."=".$li_canart."&txtcodiva".$li_i."=".$li_codiva."&hidnumsep".$li_i."=".$ls_numsep;
		}
		$as_parametros=$as_parametros."&totalbienes=".$li_totrowbienes."";
		for($li_i=1;($li_i<$li_totrowservicios);$li_i++)
		{
			$ls_codser = $_POST["txtcodser".$li_i];
			$ls_denser = $_POST["txtdenser".$li_i];
			$ld_canser = $_POST["txtcanser".$li_i];
			$li_codiva = $_POST["txtcodiva".$li_i];
			$ls_numsep = $_POST["hidnumsep".$li_i];
			$as_parametros=$as_parametros."&txtcodser".$li_i."=".$ls_codser."&txtdenser".$li_i."=".$ls_denser."".
					  					  "&txtcanser".$li_i."=".$ld_canser."&txtcodiva".$li_i."=".$li_codiva."&hidnumsep".$li_i."=".$ls_numsep;
		}
		$as_parametros=$as_parametros."&totalservicios=".$li_totrowservicios."";
		for($li_i=1;($li_i<$li_totrowproveedores);$li_i++)
		{
			$ls_codpro = $_POST["txtcodpro".$li_i];
			$ls_nompro = $_POST["txtnompro".$li_i];
			$ls_dirpro = $_POST["txtdirpro".$li_i];
			$ls_telpro = $_POST["txttelpro".$li_i];
			$as_parametros=$as_parametros."&txtcodpro".$li_i."=".$ls_codpro."&txtnompro".$li_i."=".$ls_nompro."".
					  					  "&txtdirpro".$li_i."=".$ls_dirpro."&txttelpro".$li_i."=".$ls_telpro;
		}
		$as_parametros=$as_parametros."&totalproveedores=".$li_totrowproveedores;
		
		for($li_i=1;($li_i<$li_totrowsep);$li_i++)
		{
			$ls_numsep = $_POST["txtnumsep".$li_i];
			$ls_densep = $_POST["txtdensep".$li_i];
			$ld_monsep = $_POST["txtmonsep".$li_i];
			$ls_unieje = $_POST["txtunieje".$li_i];
			$ls_denuni = $_POST["txtdenuni".$li_i];
			$as_parametros=$as_parametros."&txtnumsep".$li_i."=".$ls_numsep."&txtdensep".$li_i."=".$ls_densep."".
			                              "&txtmonsep".$li_i."=".$ld_monsep."&txtunieje".$li_i."=".$ls_unieje."&txtdenuni".$li_i."=".$ls_denuni;
		}
		$as_parametros=$as_parametros."&totalsep=".$li_totrowsep;
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../soc/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<title>Solicitud de Cotizaci&oacute;n</title>
<link href="css/soc.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
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

<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/tepuy_soc_c_solicitud_cotizacion.php");
$io_soc=new tepuy_soc_c_solicitud_cotizacion("../");
uf_limpiarvariables();
switch($ls_operacion){
  case 'GUARDAR':
			uf_load_variables();
			$lb_valido=$io_soc->uf_guardar($ls_existe,$ls_numsolcot,$ls_tipsolcot,$ls_obssolcot,$ls_consolcot,$ls_uniejeaso,$ls_fecregsolcot,$ls_coduniadm,$ls_cedpersol,$ls_codcarper,$li_totrowbienes,
			                                $li_totrowservicios,$li_totrowsep,$li_totrowproveedores,$ls_telpersol,$ls_faxpersol,$la_seguridad);
			uf_load_data(&$ls_parametros);
			
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
			
  break;
	case "ELIMINAR":
		uf_load_variables();
		$lb_valido=$io_soc->uf_delete_solicitud_cotizacion($ls_numsolcot,$ls_tipsolcot,$li_totrowsep,$la_seguridad);
		if(!$lb_valido)
		{
			uf_load_data(&$ls_parametros);
			$ls_existe="TRUE";
		}
		else
		{
			uf_limpiarvariables();
			$ls_existe="FALSE";
		}
		break;
}

if ($ls_tipsolcot=='B')
   {
     $ls_selbie = "selected";
     $ls_selser = "";
   }
elseif($ls_tipsolcot=='S')
   {
     $ls_selbie = "";
     $ls_selser = "selected";
   }
else
   {
     $ls_selbie = "";
     $ls_selser = "";
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
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema" style="text-align:left">Ordenes de Compra</td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a e");?></b></span></div></td>
          </tr>
    </table>    
	</tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="../soc/js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" colspan="11" class="toolbar"></td>
  </tr>
  <tr style="text-align:left">
    <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0" title="Nuevo" /></a><span class="toolbar" style="text-align:left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0" title="Guardar" /></a></span><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0" title="Buscar" /></a><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir" /></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir" /></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda" /></a></td>
  </tr>
  </table>
  <p>&nbsp;</p>
<form id="formulario" name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_compra);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="800" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <input name="tipo" type="hidden" id="tipo" value="SC" />
        <td height="22" colspan="4"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
        Solicitud de Cotizaci&oacute;n 
        <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe ?>" />
        <input name="hidestsolcot" type="hidden" id="hidestsolcot" value="<?php print $ls_estsolcot ?>" /></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="13"><div align="right">Estatus</div></td>
        <td height="13" colspan="3"><label>
          <input name="txtestsolcot" type="text" class="sin-borde2" id="txtestsolcot" value="<?php print $ls_estatus ?>" readonly/>
        </label></td>
      </tr>
      <tr>
        <td width="109" height="22"><div align="right">N&uacute;mero</div></td>
        <td width="206" height="22"><label>
          <input name="txtnumsolcot" type="text" id="txtnumsolcot" style="text-align:center" value="<?php print $ls_numsolcot ?>" size="25" maxlength="15" read tabindex="0" />
        </label></td>
        <td width="336" height="22">&nbsp;</td>
        <td width="147" height="22"><strong>Fecha</strong>
        <input name="txtfecregsolcot" type="text" class="sin-borde" id="txtfecregsolcot" value="<?php print $ls_fecregsolcot ?>" size="15" maxlength="10" style="text-align:left" read /></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td height="22"><label>
          <select name="cmbtipsolcot" id="cmbtipsolcot" tabindex="1" onchange="javascript:ue_cargargrid()">
            <option value="-" >---seleccione---</option>
            <option value="B" <?php print $ls_selbie ?>>Bienes</option>
            <option value="S" <?php print $ls_selser ?>>Servicios</option>
          </select>
        </label></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" style="text-align:right">Concepto</td>
        <td height="13" colspan="3"><label>
          <textarea name="txtconsolcot" cols="122" id="txtconsolcot" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); "><?php print $ls_consolcot ?></textarea>
        </label></td>
      </tr>
      <tr>
        <td height="13" style="text-align:right">Unidades Asociadas </td>
        <td height="13" colspan="3"><textarea read name="txtuniejeaso" cols="122" id="txtuniejeaso" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); "><?php print $ls_uniejeaso ?></textarea>
        </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Observaci&oacute;n</td>
        <td height="22" colspan="3"><label>
          <input name="txtobssolcot" type="text" id="txtobssolcot" style="text-align:left" tabindex="2" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); " value="<?php print $ls_obssolcot ?>" size="125"/>
        </label></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="22" colspan="4">Datos del Solicitante </td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Personal</div></td>
        <td height="22" colspan="3"><label>
          <input name="txtcedper" type="text" id="txtcedper" style="text-align:center" value="<?php print $ls_cedpersol ?>" maxlength="10" readonly/>
          <a href="javascript: ue_catalogo('tepuy_soc_cat_personal.php?origen=SC');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtnompersol" type="text" class="sin-borde" id="txtnompersol" style="text-align:left" value="<?php print $ls_nompersol ?>" size="90" readonly />
          <input name="txtcodcarper" type="hidden" id="txtcodcarper" value="<?php print $ls_codcarper ?>" />
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Unidad Ejecutora </div></td>
        <td height="22" colspan="3"><label>
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm ?>" readonly />
          <a href="javascript:ue_catalogo('tepuy_soc_cat_unidad_ejecutora.php');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" /></a>
          <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" style="text-align:left" value="<?php print $ls_denuniadm ?>" size="90" readonly />
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">T&eacute;lefonos</div></td>
        <td height="22"><label>
          <input name="txttelpersol" type="text" id="txttelpersol" value="<?php print $ls_telpersol ?>" onKeyPress="return keyRestrict(event,'0123456789'+'-/() ');" />
        </label></td>
        <td height="22">Fax 
          <label>
          <input name="txtfaxpersol" type="text" id="txtfaxpersol" value="<?php print $ls_faxpersol ?>" onKeyPress="return keyRestrict(event,'0123456789'+'-/() ');"/>
        </label></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="4"><div align="center">
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" />
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2 ?>" />
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3 ?>" />
          <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" />
          <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5 ?>" />
<input name="totrowsep" type="hidden" id="totrowsep" value="<?php print $li_totrowsep ?>" />
          <input name="totrowbienes" type="hidden" id="totrowbienes" value="<?php print $li_totrowbienes;?>" />
          <input name="botsep" type="button" class="celdas-azules" id="botsep" value="Solicitud de Ejecuci&oacute;n Presupuestaria"  onclick="javascript:ue_catalogosep();" />
          <input name="totrowservicios" type="hidden" id="totrowservicios" value="<?php print $li_totrowservicios;?>" />
          <input name="totrowproveedores" type="hidden" id="totrowproveedores" value="<?php print $li_totrowproveedores;?>" />
          <input name="parametros"    type="hidden" id="parametros"    value="<?php print $ls_parametros;?>" />
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>" />
        </div></td>
      </tr>
      <tr>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
       <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr> 
           <td align="center"><div id="bienesservicios"></div></td>
         </tr>
       </table>
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function ue_nuevo()
{
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="tepuy_soc_p_solicitud_cotizacion.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion !!!");
   	}
}

function ue_catalogo(ls_catalogo)
{
	li_totrowsep = ue_calcular_total_fila_local("txtnumsep");
	// abre el catalogo que se paso por parametros
	if (ls_catalogo=='tepuy_soc_cat_unidad_ejecutora.php')
	   {
	     ls_codunieje = f.txtcoduniadm.value;
		 if (ls_codunieje!='----------')
		    {
	          if (li_totrowsep==0)
			     {
			       window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=450,left=50,top=50,location=no,resizable=yes");
				 }
			  else
			     {
				   alert("La Unidad Ejecutora debe corresponder con la unidad de la SEP. No puede ser modificada !!!");
				 }
			}
	     else
		    {
			  alert("La Unidad Ejecutora No puede ser modificada. Existen diferente Unidades dentro de la Solicitud !!!");
			}
	   }
	else
	   {
	     window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=450,left=50,top=50,location=no,resizable=yes");
	   }
}

function ue_cargargrid()
{
		// Cargamos las variables para pasarlas al AJAX
		ls_tipsolcot = f.cmbtipsolcot.value;
		f.totrowbienes.value=1;
		f.totrowservicios.value=1;
		f.totrowproveedores.value=1;
		f.totrowsep.value=0;
		f.txtconsolcot.value  = "";
		f.txtuniejeaso.value  = "";
		f.txtcoduniadm.value  = "";
		f.txtdenuniadm.value  = "";
		f.txtcodestpro1.value = "";
		f.txtcodestpro2.value = "";
		f.txtcodestpro3.value = "";
		f.txtcodestpro4.value = "";
		f.txtcodestpro5.value = "";
		
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('bienesservicios');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("tipo="+ls_tipsolcot+"&totalbienes=1&totalservicios=1&totalproveedores=1&totalsep=0"+"&proceso=LIMPIAR");
}

function ue_catalogobienes()
{
	// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
	li_totrowsep = f.totrowsep.value;
	ls_estsolcot = f.hidestsolcot.value;
	if (ls_estsolcot=="P")
	   {
	     alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	   }
	else
	   {
		 // Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		 if (li_totrowsep==0)
		    {
			  ls_codunieje  = f.txtcoduniadm.value;
			  ls_codestpro1 = f.txtcodestpro1.value;
			  ls_codestpro2 = f.txtcodestpro2.value;
			  ls_codestpro3 = f.txtcodestpro3.value;             
			  ls_codestpro4 = f.txtcodestpro4.value;
			  ls_codestpro5 = f.txtcodestpro5.value;
			  if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
				 {
				   window.open("tepuy_soc_cat_bienes.php?tipo=SC","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
				 }
			  else
				 {
				   alert("Debe seleccionar una Unidad Ejecutora !!!");
				 }
			}
		 else
		    {
			  alert("No pueden ser agregado Items a la Solicitud, Existen SEP dentro de la misma !!!");
			}
	   }
}

function ue_delete_bienes(fila)
{  
	ls_estsolcot = f.hidestsolcot.value;
	if(ls_estsolcot=="P")
	{
	  alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			codigo="";
			//---------------------------------------------------------------------------------
			// Cargar los Bienes y eliminar el seleccionado
			//---------------------------------------------------------------------------------
			// Obtenemos el total de filas de los bienes
			li_totrowbienes = ue_calcular_total_fila_local("txtcodart");
			f.totrowbienes.value = li_totrowbienes;
			li_i=0;
			for (j=1;(j<li_totrowbienes)&&(valido);j++)
			    {
			    	//ld_codiva = eval("f.txtcodiva"+j+".value");
			    	//alert(ld_codiva);
				  if (j!=fila)
				     {
					   li_i       = li_i+1;
					   ls_codart = eval("f.txtcodart"+j+".value");
					   ls_denart = eval("f.txtdenart"+j+".value");
					   ld_canart = eval("f.txtcanart"+j+".value");
					   ld_codiva = eval("f.txtcodiva"+j+".value");
					   //alert(li_i);
					   ls_numsep = eval("f.hidnumsep"+j+".value");
					   parametros = parametros+"&txtcodart"+li_i+"="+ls_codart+"&txtdenart"+li_i+"="+ls_denart+"&txtcanart"+li_i+"="+ld_canart+"&txtcodiva"+li_i+"="+ld_codiva+"&hidnumsep"+li_i+"="+ls_numsep;
				     }
			    } 
			li_i=li_i+1;
			parametros=parametros+"&totalbienes="+li_i+"";
			f.totrowbienes.value=li_i;
			
			li_totrowspro = ue_calcular_total_fila_local("txtcodpro");
			f.totrowproveedores.value = li_totrowspro;
			if (li_totrowspro>1)
			   {
			     for (j=1;(j<=li_totrowspro)&&(valido);j++)
					 { 
					   codpro     = eval("f.txtcodpro"+j+".value");
					   nompro     = eval("f.txtnompro"+j+".value");
					   dirpro     = eval("f.txtdirpro"+j+".value");
					   telpro     = eval("f.txttelpro"+j+".value");
					   parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
					 }
				 parametros = parametros+"&totalproveedores="+li_totrowspro;
			   }
			else
			   {
				 f.totrowproveedores.value=1;	
				 parametros=parametros+"&totalproveedores=1";
			   }			
			
		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de las SEP en el Formulario
		   //--------------------------------------------------------------------------------
            li_totrowsep      = ue_calcular_total_fila_local("txtnumsep");
			f.totrowsep.value = li_totrowsep;
			if (li_totrowsep>1)
			   {
			     for (j=1;(j<=li_totrowsep)&&(valido);j++)
					 { 
					   ls_numsep  = eval("f.txtnumsep"+j+".value");
					   ls_densep  = eval("f.txtdensep"+j+".value");
					   ld_monsep  = eval("f.txtmonsep"+j+".value");
					   ls_unieje  = eval("f.txtunieje"+j+".value");
					   ls_denuni  = eval("f.txtdenuni"+j+".value");
					   parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+li_i+"="+ls_denuni;
					 }
				 parametros=parametros+"&totalsep="+li_totrowsep;
			   }
			else
			   {
				 f.totrowproveedores.value=0;	
				 parametros=parametros+"&totalsep=0";
			   }			
			
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=MOSTRARBIENES"+parametros);
			}
		}
    }
}

function ue_catalogoservicios()
{
	// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
	li_totrowsep = f.totrowsep.value;
	ls_estsolcot = f.hidestsolcot.value;
	if (ls_estsolcot=="P")
	   {
	     alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	   }
	else
	   {
		 if (li_totrowsep==0)
		    {
			  ls_codunieje  = f.txtcoduniadm.value;
			  ls_codestpro1 = f.txtcodestpro1.value;
			  ls_codestpro2 = f.txtcodestpro2.value;
			  ls_codestpro3 = f.txtcodestpro3.value;             
			  ls_codestpro4 = f.txtcodestpro4.value;
			  ls_codestpro5 = f.txtcodestpro5.value;	
			  if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
				 {
				   window.open("tepuy_soc_cat_servicios.php?tipo=SC","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
				 }
			  else
				 {
				   alert("Debe seleccionar una Unidad Ejecutora !!!");
				 }
			}
		 else
		    {
			  alert("No pueden ser agregado Items a la Solicitud, Existen SEP dentro de la misma !!!");
			}
       }
}

function ue_delete_servicios(fila)
{
	ls_estsolcot = f.hidestsolcot.value;
	if(ls_estsolcot=="P")
	{
	  alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			codigo="";
			//---------------------------------------------------------------------------------
			// Cargar los Servicios y eliminar el seleccionado
			//---------------------------------------------------------------------------------
			// Obtenemos el total de filas de los Servicios
			li_totrowservicios = ue_calcular_total_fila_local("txtcodser");
			li_i=0;
			for(j=1;(j<li_totrowservicios)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					ls_codser = eval("f.txtcodser"+j+".value");
					ls_denser = eval("f.txtdenser"+j+".value");
					ld_canser = eval("f.txtcanser"+j+".value");
					ls_numsep = eval("f.hidnumsep"+j+".value");
					parametros=parametros+"&txtcodser"+li_i+"="+ls_codser+"&txtdenser"+li_i+"="+ls_denser+"&txtcanser"+li_i+"="+ld_canser+"&hidnumsep"+li_i+"="+ls_numsep;
				}
			}
			li_i=li_i+1;
			parametros=parametros+"&totalservicios="+li_i+"";
			f.totrowservicios.value=li_i;
			
		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de los Proveedores en el formulario
		   //--------------------------------------------------------------------------------
			li_totrowproveedores = ue_calcular_total_fila_local("txtcodpro");
			f.totrowproveedores.value = li_totrowproveedores;
			if (li_totrowproveedores>1)
			   {
			     for (j=1;(j<=li_totrowproveedores)&&(valido);j++)
					 { 
					   codpro     = eval("f.txtcodpro"+j+".value");
					   nompro     = eval("f.txtnompro"+j+".value");
					   dirpro     = eval("f.txtdirpro"+j+".value");
					   telpro     = eval("f.txttelpro"+j+".value");
					   parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
					 }
				 parametros=parametros+"&totalproveedores="+li_totrowproveedores;
			   }
			else
			   {
				 f.totrowproveedores.value=1;	
				 parametros=parametros+"&totalproveedores=1";
			   }			

		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de las SEP en el Formulario
		   //--------------------------------------------------------------------------------
            li_totrowsep      = ue_calcular_total_fila_local("txtnumsep");
			f.totrowsep.value = li_totrowsep;
			if (li_totrowsep>1)
			   {
			     for (j=1;(j<=li_totrowsep)&&(valido);j++)
					 { 
					   ls_numsep  = eval("f.txtnumsep"+j+".value");
					   ls_densep  = eval("f.txtdensep"+j+".value");
					   ld_monsep  = eval("f.txtmonsep"+j+".value");
					   ls_unieje  = eval("f.txtunieje"+j+".value");
					   ls_denuni  = eval("f.txtdenuni"+j+".value");
					   parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+li_i+"="+ls_denuni;
					 }
				 parametros=parametros+"&totalsep="+li_totrowsep;
			   }
			else
			   {
				 f.totrowproveedores.value=0;	
				 parametros=parametros+"&totalsep=0";
			   }			
			
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=MOSTRARSERVICIOS"+parametros);
			}
		}
	}
}

function ue_catalogosep()
{
    li_totrowsep = ue_calcular_total_fila_local("txtnumsep");
	ls_estsolcot = f.hidestsolcot.value;
	if (ls_estsolcot=="P")
	   {
	     alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	   }
	else
	   {
	     ls_codunieje = f.txtcoduniadm.value;
		 ls_tipsolcot = f.cmbtipsolcot.value;
 	     if (ls_tipsolcot!='-')
	        {
	          if (ls_codunieje=="")
			     {
			       pagina="tepuy_soc_cat_sep.php?hidtipsolcot="+ls_tipsolcot+"&tipo=SC";
	               window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=780,height=550,resizable=yes,location=no,left=50,top=50");
				 }
	          else
			     {
				   if (li_totrowsep==0)
				      {
				        alert("Si desea procesar Solicitudes de Ejecuci�n Presupuestaria, No indique Unidad Ejecutora ya que estas vienen definidas desde las Solicitudes de Ejecuci�n Presupuestaria !!!");					  
					  }
                   else
				      {
			            pagina="tepuy_soc_cat_sep.php?hidtipsolcot="+ls_tipsolcot+"&tipo=SC";
	                    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=780,height=550,resizable=yes,location=no,left=50,top=50");
					  }
				 }
			}
	     else
	        {
	          alert("Debe seleccionar el Tipo de Solicitud de Cotizaci�n !!!");
	        }
	   }
}

function ue_catalogoproveedores()
{
	window.open("tepuy_soc_cat_proveedor.php?tipo=SC","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
}

function ue_delete_proveedor(fila)
{
	ls_estsolcot = f.hidestsolcot.value;
	if(ls_estsolcot=="P")
	{
	  alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	}
	else
	{
	  if (confirm("�Desea eliminar el Registro actual?"))
		 {
		   valido=true;
		   parametros="";
		   //---------------------------------------------------------------------------------
		   // Cargar los Proveedores y eliminar el seleccionado
		   //---------------------------------------------------------------------------------
		   // Obtenemos el total de filas de los proveedores
		   ls_tipsolcot = f.cmbtipsolcot.value;
	
		   li_totrowspro = ue_calcular_total_fila_local("txtcodpro");
   		   li_i=0;
		   for (j=1;(j<li_totrowspro)&&(valido);j++)
			   {
				 if (j!=fila)
					{
					  li_i       = li_i+1;
					  codpro     = eval("f.txtcodpro"+j+".value");
					  nompro     = eval("f.txtnompro"+j+".value");
					  dirpro     = eval("f.txtdirpro"+j+".value");
					  telpro     = eval("f.txttelpro"+j+".value");
					  parametros = parametros+"&txtcodpro"+li_i+"="+codpro+"&txtnompro"+li_i+"="+nompro+"&txtdirpro"+li_i+"="+dirpro+"&txttelpro"+li_i+"="+telpro;
					}
			   }
		   li_i=li_i+1;
		   parametros=parametros+"&totalproveedores="+li_i+"";
		   f.totrowproveedores.value=li_i;
				
		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de los Bienes/Materiales en el formulario
		   //--------------------------------------------------------------------------------
		   li_totrowbienes = ue_calcular_total_fila_local("txtcodart");
		   f.totrowbienes.value = li_totrowbienes;
		   if (li_totrowbienes>1)
			  {
				for (j=1;(j<=li_totrowbienes)&&(valido);j++)
					{ 
					  ls_codart  = eval("f.txtcodart"+j+".value");
					  ls_denart  = eval("f.txtdenart"+j+".value");
					  ls_canart  = eval("f.txtcanart"+j+".value");
					  ls_codiva  = eval("f.txtcodiva"+j+".value");
					  ls_numsep  = eval("f.hidnumsep"+j+".value");
					  parametros = parametros+"&txtcodart"+j+"="+ls_codart+"&txtdenart"+j+"="+ls_denart+"&txtcanart"+j+"="+ls_canart+"&txtcodiva"+j+"="+ls_codiva+"&hidnumsep"+j+"="+ls_numsep;		   
					}
				parametros  = parametros+"&totalbienes="+li_totrowbienes;		   
			  }
				
		  //--------------------------------------------------------------------------------
		  // Incorporamos los detalles existentes de los Servicios en el formulario
		  //--------------------------------------------------------------------------------
		  li_totrowservicios = ue_calcular_total_fila_local("txtcodser");
		  f.totrowservicios.value = li_totrowservicios;
		   if (li_totrowservicios>1)
			  {
				for (j=1;(j<=li_totrowservicios)&&(valido);j++)
					{
					  ls_codser  = eval("f.txtcodser"+j+".value");
					  ls_denser  = eval("f.txtdenser"+j+".value");
					  ls_canser  = eval("f.txtcanser"+j+".value");
					  ls_codiva  = eval("f.txtcodiva"+j+".value");
					  ls_numsep  = eval("f.hidnumsep"+j+".value");
					  parametros = parametros+"&txtcodser"+j+"="+ls_codser+"&txtdenser"+j+"="+ls_denser+"&txtcanser"+j+"="+ls_canser+"&txtcodiva"+j+"="+ls_codiva+"&hidnumsep"+j+"="+ls_numsep;
					}
				parametros = parametros+"&totalservicios="+li_totrowservicios;
			  }   
		  

		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de las SEP en el Formulario
		   //--------------------------------------------------------------------------------
            li_totrowsep      = ue_calcular_total_fila_local("txtnumsep");
			f.totrowsep.value = li_totrowsep;
			if (li_totrowsep>1)
			   {
			     for (j=1;(j<=li_totrowsep)&&(valido);j++)
					 { 
					   ls_numsep  = eval("f.txtnumsep"+j+".value");
					   ls_densep  = eval("f.txtdensep"+j+".value");
					   ld_monsep  = eval("f.txtmonsep"+j+".value");
					   ls_unieje  = eval("f.txtunieje"+j+".value");
					   ls_denuni  = eval("f.txtdenuni"+j+".value");
					   parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+li_i+"="+ls_denuni;
					 }
				 parametros=parametros+"&totalsep="+li_totrowsep;
			   }
			else
			   {
				 f.totrowproveedores.value=0;	
				 parametros=parametros+"&totalsep=0";
			   }			

		   if ((parametros!="")&&(valido))
			  {
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("tipo="+ls_tipsolcot+"&proceso=AGREGARPROVEEDORES"+parametros);
			  }
		 }
     }
}

function ue_delete_sep(fila)
{  
	ls_tipsolcot = f.cmbtipsolcot.value;
	ls_estsolcot = f.hidestsolcot.value;
	ls_codunieje = f.txtcoduniadm.value;
	ls_opesep    = "DELETE";
	if (ls_estsolcot=="P")
	   {
	     alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
	   }
	else
	   {
	     if (confirm("�Desea eliminar el Registro actual?"))
		    {
			  valido     = true;
			  parametros = "";
			  codigo     = "";
			  //---------------------------------------------------------------------------------
			  // Cargar las SEP y eliminar el seleccionado
			  //---------------------------------------------------------------------------------
			  // Obtenemos el total de filas de las SEP.
			  li_totrowsep      = ue_calcular_total_fila_local("txtnumsep");
			  f.totrowsep.value = li_totrowsep;
			
			  li_i=0;
			  ls_uniejeaso = "";
			  ls_unieje    = "";
			  ls_denant    = "";
			  ls_uniant    = "";
			  lb_unieje    = true
			  for (j=1;(j<=li_totrowsep)&&(valido);j++)
			      {
				    if (j!=fila)
				       {
					     li_i      = li_i+1;
					     ls_numsep = eval("f.txtnumsep"+j+".value");
					     ls_densep = eval("f.txtdensep"+j+".value");
					     ld_monsep = eval("f.txtmonsep"+j+".value");
						 ls_unieje = eval("f.txtunieje"+j+".value");
						 ls_denuni = eval("f.txtdenuni"+j+".value");
						 if (li_i==1)
						    {
							  ls_uniant = ls_unieje;
							  ls_denant = ls_denuni;
							}
						 else
						    {
							  if (ls_uniant!=ls_unieje && lb_unieje==true && ls_unieje!="")
							     {
								   lb_unieje = false;
								 }
							}
						 ls_denuni = eval("f.txtdenuni"+j+".value");
						 if (ls_unieje!="" && ls_unieje!='----------')
						    {
							  ls_uniejeaso = ls_uniejeaso+" "+"Nro. SEP:"+ls_numsep+". Unidad Ejecutora: "+ls_unieje+" - "+ls_denuni+";";
							}
						 parametros = parametros+"&txtnumsep"+li_i+"="+ls_numsep+"&txtdensep"+li_i+"="+ls_densep+"&txtmonsep"+li_i+"="+ld_monsep+"&txtunieje"+li_i+"="+ls_unieje+"&txtdenuni"+li_i+"="+ls_denuni;
				       }
			        else
				       {
					     ls_numsepdel = eval("f.txtnumsep"+j+".value");
					   }
				  } 
			   if (!lb_unieje && ls_codunieje!="")
				  { 
				    f.txtcoduniadm.value = "----------";
					f.txtdenuniadm.value = "NINGUNA";
				  }
               else
			      {
				    f.txtcoduniadm.value = ls_uniant;
					f.txtdenuniadm.value = ls_denant;
				  }
			  parametros=parametros+"&totalsep="+li_i+"";
			  f.totrowbienes.value=li_i;
	          f.txtuniejeaso.value = ls_uniejeaso;
			    
		   //--------------------------------------------------------------------------------
		   // Incorporamos los detalles existentes de los Proveedores en el Formulario
		   //--------------------------------------------------------------------------------
 		   // Obtenemos el total de filas de los Proveedores.
			li_totrowspro = ue_calcular_total_fila_local("txtcodpro");
			f.totrowproveedores.value = li_totrowspro;
			if (li_totrowspro>1)
			   {
			     for (j=1;(j<=li_totrowspro)&&(valido);j++)
					 { 
					   codpro     = eval("f.txtcodpro"+j+".value");
					   nompro     = eval("f.txtnompro"+j+".value");
					   dirpro     = eval("f.txtdirpro"+j+".value");
					   telpro     = eval("f.txttelpro"+j+".value");
					   parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
					 }
				 parametros = parametros+"&totalproveedores="+li_totrowspro;
			   }
			else
			   {
				 f.totrowproveedores.value=1;	
				 parametros=parametros+"&totalproveedores=1";
			   }			
			
		    //--------------------------------------------------------------------------------
		    // Incorporamos los detalles existentes de los Bienes/Materiales en el Formulario
		    //--------------------------------------------------------------------------------
            li_totrowbienes = ue_calcular_total_fila_local("txtcodart");
		    f.totrowbienes.value = li_totrowbienes;
           
			if (li_totrowbienes>1)
			   {
			     li_row = 0;
				 for (j=1;(j<=li_totrowbienes)&&(valido);j++)
					 { 
					   ls_codart  = eval("f.txtcodart"+j+".value");
					   ls_denart  = eval("f.txtdenart"+j+".value");
					   ld_canart  = eval("f.txtcanart"+j+".value");
					   ls_numsep  = eval("f.hidnumsep"+j+".value");
					   if (ls_numsep!=ls_numsepdel)
					      {
					        li_row++;
							parametros = parametros+"&txtcodart"+li_row+"="+ls_codart+"&txtdenart"+li_row+"="+ls_denart+"&txtcanart"+li_row+"="+ld_canart+"&hidnumsep"+li_row+"="+ls_numsep;		   
						  }
					 }
				 parametros=parametros+"&totalbienes="+li_row;
			   }
			else
			   {
				 f.totrowbienes.value=1;	
				 parametros=parametros+"&totalbienes=1";
			   }			

		  //--------------------------------------------------------------------------------
		  // Incorporamos los detalles existentes de los Servicios en el formulario
		  //--------------------------------------------------------------------------------
		  li_totrowservicios = ue_calcular_total_fila_local("txtcodser");
		  f.totrowservicios.value = li_totrowservicios;
		  if (li_totrowservicios>1)
			 {
			   li_row = 0;
			   for (j=1;(j<=li_totrowservicios)&&(valido);j++)
				   {
				     ls_codser = eval("f.txtcodser"+j+".value");
					 ls_denser = eval("f.txtdenser"+j+".value");
					 ls_canser = eval("f.txtcanser"+j+".value");
					 ls_numsep = eval("f.hidnumsep"+j+".value");
					 if (ls_numsep!=ls_numsepdel)
					    {
					      li_row++;
						  parametros = parametros+"&txtcodser"+li_row+"="+ls_codser+"&txtdenser"+li_row+"="+ls_denser+"&txtcanser"+li_row+"="+ls_canser+"&hidnumsep"+li_row+"="+ls_numsep;
					    }
				   }
			   parametros = parametros+"&totalservicios="+li_row;
			 }
		   else
			 {
			   f.totrowservicios.value=1;	
			   parametros=parametros+"&totalservicios=1";
			 }
			
		  if ((parametros!="")&&(valido))
			 {
			   divgrid = document.getElementById("bienesservicios");
			   ajax=objetoAjax();
			   ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
			   ajax.onreadystatechange=function() {
			   if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
			   }
			   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			   ajax.send("tipo="+ls_tipsolcot+"&numsep="+ls_numsepdel+"&opesep="+ls_opesep+"&proceso=AGREGARSEP"+parametros);
			 }
		}
    }
}

function ue_guardar()
{
	li_totrowpro = f.totrowproveedores.value;
	li_incluir   = f.incluir.value;
	li_cambiar   = f.cambiar.value;
	lb_existe    = f.existe.value;
	parametros   = "";
	if (((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	   {
	     lb_valido    = true;
		 ls_estsolcot = f.hidestsolcot.value;
		 if (ls_estsolcot=="P")
		    {
			  lb_valido = false;
			  alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
		    }
	     else
		    {
			  // Obtenemos el total de filas del las solicitudes de ejecucion presupuestarias.
			  li_totrowsep      = ue_calcular_total_fila_local("txtnumsep");
			  f.totrowsep.value = li_totrowsep;
			  // Obtenemos el total de filas de los bienes
			  li_rowbienes = ue_calcular_total_fila_local("txtcodart");
			  f.totrowbienes.value = li_rowbienes;
			  // Obtenemos el total de filas de los Servicios
			  li_rowservicios = ue_calcular_total_fila_local("txtcodser");
			  f.totrowservicios.value = li_rowservicios;
			  // Obtenemos el total de filas de los proveedores
			  li_totrowpro = ue_calcular_total_fila_local("txtcodpro");
			  f.totrowproveedores.value = li_totrowpro;
		      ls_numsolcot = ue_validarvacio(f.txtnumsolcot.value);
			  ls_tipsolcot = ue_validarvacio(f.cmbtipsolcot.value); 
			  if (ls_tipsolcot!='-')
			     {
			       ls_consolcot    = ue_validarvacio(f.txtconsolcot.value); 
				   ls_fecregsolcot = ue_validarvacio(f.txtfecregsolcot.value);
			       ls_cedpersol    = ue_validarvacio(f.txtcedper.value); 
				   ls_coduniadm    = ue_validarvacio(f.txtcoduniadm.value);

				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_numsolcot,"El N�mero de Solicitud no puede estar vacio.",f.txtnumsolcot);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_consolcot,"El Concepto de la Solicitud no puede estar vacia.",f.txtconsolcot);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_fecregsolcot,"La Fecha no puede estar vacia.",f.txtfecregsolcot);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_cedpersol,"La C�dula del Personal solicitante no puede estar vacia.",f.txtcedper);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_coduniadm,"La Unidad Ejecutora no puede estar vacia.",f.txtcoduniadm);
					  }

				   if (lb_valido)
				      {
					   if (li_totrowsep>1)
					      {
						    for (y=1;y<li_totrowsep;y++)
							    {
							   	  ls_numsol  = eval("f.txtnumsep"+y+".value");
							      ls_consep  = eval("f.txtdensep"+y+".value");
							      ld_monsep  = eval("f.txtmonsep"+y+".value");
							      parametros = parametros+"&txtnumsep"+y+"="+ls_numsol+"&txtdensep"+y+"="+ls_consep+"&txtmonsep"+y+"="+ld_monsep;
							      li_row = 0;
								  if (ls_tipsolcot=='B')
								     {
									   li_row = li_rowbienes; 
									 }
								  else
								     {
									   li_row = li_rowservicios; 
									 }
								  lb_valido = false; 
								  for (j=1;j<li_row;j++)
								      {
									    ls_numsep = eval("f.hidnumsep"+j+".value");
									    if (ls_numsol==ls_numsep)
									       {
										     lb_valido = true;
										     break;
										   }
									 }
								  if (!lb_valido)
								     {
									   alert("No existe detalle asociado a la SEP Nro. "+ls_numsol+", elimine el registro !!!");
									 }
								}
	      					parametros = parametros+"&totalsep="+li_totrowsep;	   
						  }

						if (ls_tipsolcot=='B' && lb_valido)//Solicitud de Cotizaci�n de Tipo Bienes.
						   {
							 if (li_rowbienes>1)
							    {
								  for (j=1;j<li_rowbienes;j++)
								      {
									    ls_codart = eval("f.txtcodart"+j+".value");
									    ls_codiva = eval("f.txtcodiva"+j+".value");
									//alert(ls_codiva);
									    ls_denart = eval("f.txtdenart"+j+".value");
										ls_numsep = eval("f.hidnumsep"+j+".value");
										ld_canart = eval("f.txtcanart"+j+".value");
									    ld_canart = ue_formato_calculo(ld_canart);
									    if (ld_canart<=0)
									       {
										     alert("La Cantidad del Bien/Material "+ls_codart+" - "+ls_denart+" debe ser mayor que Cero.")
										     lb_valido = false;
									       }
								      }
							    }
							 else
							    {
								  alert("La Solicitud de Cotizaci�n debe tener al menos un Bien/Material incorporado !!!");
								  lb_valido = false;
							    }
						   }
					     else
						   {
							 if (ls_tipsolcot=='S' && lb_valido)
							    {
							      if (li_rowservicios>1)
							         {
								       for (j=1;j<li_rowservicios;j++)
								           {
									         ls_codser = eval("f.txtcodser"+j+".value");
										     ls_denser = eval("f.txtdenser"+j+".value");
										     ls_numsep = eval("f.hidnumsep"+j+".value");
									  	     ld_canser = eval("f.txtcanser"+j+".value");
									         ld_canser = ue_formato_calculo(ld_canser);
									         if (ld_canser<=0)
									            {
										          alert("La Cantidad del Servicio "+ls_codser+" - "+ls_denser+" debe ser mayor que Cero.")
										          lb_valido = false;
								 	            }
								           }
							         }
							      else
							         {
								       alert("La Solicitud de Cotizaci�n debe tener al menos un Servicio incorporado !!!");
								       lb_valido = false;
							         }
								}
						   } 
					   if (lb_valido)
						  {
							if (li_totrowpro<=1)
							   {
							     lb_valido = false;
							     alert("La Solicitud de Cotizaci�n debe tener al menos un Proveedor incorporado !!!");
							   }
						  }
					  } 
				   if (lb_valido)
					  {
						f.operacion.value="GUARDAR";
						f.action="tepuy_soc_p_solicitud_cotizacion.php";
						f.submit();		
					  }
				 }
			  else
			     {
	               alert("Debe seleccionar el Tipo de Solicitud de Cotizaci�n !!!");
				 }
			}
	   }
    else
	   {
 	     alert("No tiene permiso para realizar esta operaci�n !!!");
	   }
}

function ue_buscar()
{
	li_leer      = f.leer.value;
	ls_tipsolcot = f.cmbtipsolcot.value;
	if (li_leer==1)
       {
		 window.open("tepuy_soc_cat_solicitud_cotizacion.php?origen=SC&tipsolcot="+ls_tipsolcot,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=no");
	   }
	else
   	   {
 		 alert("No tiene permiso para realizar esta operacion !!!");
       }
}

function ue_reload()
{
	parametros   = f.parametros.value;
	ls_tipsolcot = f.cmbtipsolcot.value;// Para saber si es de bienes, servicios � conceptos
	if(ls_tipsolcot=="B")
	{
		proceso="AGREGARBIENES";
	}
	if(ls_tipsolcot=="S")
	{
		proceso="AGREGARSERVICIOS";
	}

	if(parametros!="")
	{
		divgrid = document.getElementById("bienesservicios");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+parametros);
	}
}

function ue_eliminar()
{
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			ls_estsolcot = f.hidestsolcot.value;
			if(ls_estsolcot=="P")
			{
			  alert("La Solicitud de Cotizaci�n ya ha sido procesada, No puede ser modificada !!!.");
			}
			else
			{
				ls_numsolcot = ue_validarvacio(f.txtnumsolcot.value);
				if (ls_numsolcot!="")
				{
					if(confirm("�Desea eliminar el Registro actual?"))
					{
						f.operacion.value       = "ELIMINAR";
						f.action                = "tepuy_soc_p_solicitud_cotizacion.php";
			            li_totrowbienes         = ue_calcular_total_fila_local("txtcodart");
			            li_totrowiva            = ue_calcular_total_fila_local("txtcodiva");
			            f.totrowbienes.value    = li_totrowbienes;
						li_totrowservicio       = ue_calcular_total_fila_local("txtcodser");
			            f.totrowservicios.value = li_totrowservicio;
						li_totrowsep            = ue_calcular_total_fila_local("txtnumsep");
			            f.totrowsep.value       = li_totrowsep;
						f.submit();
					}
				}
				else
				{
					alert("Debe buscar el registro a eliminar.");
				}
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n !!!");
   	}
}

function ue_imprimir()
{
	li_imprimir = f.imprimir.value;
	lb_existe   = f.existe.value;
	if(li_imprimir==1)
	{
		if(lb_existe=="TRUE")
		{
			ls_numsolcot = f.txtnumsolcot.value;
			ls_fecsolcot = f.txtfecregsolcot.value;
			ls_tipsolcot = f.cmbtipsolcot.value;
			if (ls_tipsolcot!='-')
			   {
			     ls_formato   = f.formato.value;
			     window.open("reportes/"+ls_formato+"?numsolcot="+ls_numsolcot+"&tipsolcot="+ls_tipsolcot+"&fecsolcot="+ls_fecsolcot,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			   }
		    else
			   {
			     alert("Debe seleccionar un Tipo de Solicitud v�lida (Bienes/Servicios) !!!");
			   }
		}
		else
		{
			alert("Debe existir un documento a imprimir");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operaci�n !!!");
	}
}

function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}
</script>
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
?>
</html>