<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Empresa</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style6 {color: #000000}

-->
</style>

</head>
<body link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Contabilidad Presupuestaria de Gastos</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="../js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>-->
  <tr>
    <td height="36"class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" width="121" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><a href="../tepuywindow_blank.php"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../class_folder/tepuy_cfg_c_empresa.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");

$io_include = new tepuy_include();
$ls_conect  = $io_include-> uf_conectar ();
$la_emp     = $_SESSION["la_empresa"];
$io_msg     = new class_mensajes(); //Instanciando  la clase mensajes 
$io_sql     = new class_sql($ls_conect); //Instanciando  la clase sql
$io_funcion = new class_funciones(); 
$io_fecha   = new class_fecha();
$io_empresa = new tepuy_cfg_c_empresa($ls_conect);

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	//$ls_sistema  = "CFG";
	$ls_sistema  = "SPG";
	$ls_ventanas = "tepuy_cfg_d_empresa.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos"];
			  $la_accesos["leer"]     = $_POST["leer"];
			  $la_accesos["incluir"]  = $_POST["incluir"];
			  $la_accesos["cambiar"]  = $_POST["cambiar"];
			  $la_accesos["eliminar"] = $_POST["eliminar"];
			  $la_accesos["imprimir"] = $_POST["imprimir"];
			  $la_accesos["anular"]   = $_POST["anular"];
			  $la_accesos["ejecutar"] = $_POST["ejecutar"];
		    }
	   }
	else
	   {
	     $la_accesos["leer"]     = "";
		 $la_accesos["incluir"]  = "";
		 $la_accesos["cambiar"]  = "";
		 $la_accesos["eliminar"] = "";
		 $la_accesos["imprimir"] = "";
		 $la_accesos["anular"]   = "";
		 $la_accesos["ejecutar"] = "";
		 $ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
     $ls_readonly  = '';   
     $ls_numlicemp = $_POST["txtnumlicemp"];
     $ls_modgenret = $_POST["radiocmp"];
   }
else
   {
     $ls_readonly  = 'readonly';   
     $ls_operacion = "";
     $ls_proyecto  = "checked";
     $ls_numlicemp = "";
     $ls_modgenret = 'B';
   }
$lr_datos['modgenret'] = $ls_modgenret; 
if ($ls_modgenret=='B')
   {
     $ls_impretban = 'checked';
     $ls_impretcxp = '';
   }   
else
   {
     $ls_impretban = '';
     $ls_impretcxp = 'checked';
   }
if (array_key_exists("radiocmpiva",$_POST))
   {
      $ls_estretiva = $_POST["radiocmpiva"];
	  $lr_datos['estretiva'] = $ls_estretiva;
   }
else
   {
      $ls_estretiva = 'C'; 
	  $lr_datos['estretiva'] = $ls_estretiva; 
   }
if ($ls_operacion=='BUSCAR')
{
   $ls_disacompiva = 'disabled';
}  
else
{
  $ls_disacompiva = 'enabled';
} 
if ($ls_estretiva=='B')
   {
     $ls_compivaban = 'checked';
     $ls_compivacxp = '';
   }   
else
   {
     if ($ls_estretiva=='C') 
	 { 
		$ls_compivaban = '';
		$ls_compivacxp = 'checked';
	 }
   }  
if (array_key_exists("modaper",$_POST))
   {
     $ls_modaper     = $_POST["modaper"];
     $ls_disapertura = 'disabled';
     $ls_distipcont = 'disabled';
  }
else
   {
     $ls_modaper     = false;
	 $ls_disapertura = '';
     $ls_distipcont = '';
   }
if (array_key_exists("hiddisabled",$_POST))
   {
     $li_disabled = $_POST["hiddisabled"];
   }
else
   {
	 $li_disabled = '0';
   }   
if  (array_key_exists("txtcodigo",$_POST))
	{
  	  $ls_codigo=$_POST["txtcodigo"];
      $lr_datos["codemp"]=$ls_codigo;
	}
else
	{
	  $ls_codigo="";
	}
if  (array_key_exists("txtnumlicemp",$_POST))
	{
  	  $ls_numlicemp          = $_POST["txtnumlicemp"];
      $lr_datos["numlicemp"] = $ls_numlicemp;
	}
else
	{
	  $ls_numlicemp = "";
	}
if (array_key_exists("txtnombre",$_POST))
   {
     $ls_nombre=$_POST["txtnombre"];
     $lr_datos["nombre"]=$ls_nombre;
   }
else
   {
     $ls_nombre="";
   }

///////////////////////////////////////////////// NUEVO
if (array_key_exists("txtjefepresupuesto",$_POST))
   {
     $ls_jefe_presupuesto=$_POST["txtjefepresupuesto"];
     $lr_datos["jefe_presupuesto"]=$ls_jefe_presupuesto;
   }
else
   {
     $ls_jefe_presupuesto="";
   }
if (array_key_exists("txtcargopresupuesto",$_POST))
   {
     $ls_cargo_presupuesto=$_POST["txtcargopresupuesto"];
     $lr_datos["cargo_presupuesto"]=$ls_cargo_presupuesto;
   }
else
   {
     $ls_cargo_presupuesto="";
   }
if (array_key_exists("txtfirma1ordenanza",$_POST))
   {
     $ls_firma1=$_POST["txtfirma1ordenanza"];
     $lr_datos["firma1_ordenanza"]=$ls_firma1;
   }
else
   {
     $ls_firma1="";
   }
if (array_key_exists("txtcargo1ordenanza",$_POST))
   {
     $ls_cargo1=$_POST["txtcargo1ordenanza"];
     $lr_datos["cargo1_ordenanza"]=$ls_cargo1;
   }
else
   {
     $ls_cargo1="";
   }
if (array_key_exists("txtsecreordenanza",$_POST))
   {
     $ls_secreordenanza=$_POST["txtsecreordenanza"];
     $lr_datos["secre_ordenanza"]=$ls_secreordenanza;
   }
else
   {
     $ls_secreordenanza="";
   }
if (array_key_exists("txtcargosecreordenanza",$_POST))
   {
     $ls_cargosecreordenanza=$_POST["txtcargosecreordenanza"];
     $lr_datos["cargosecre_ordenanza"]=$ls_cargosecreordenanza;
   }
else
   {
     $ls_cargosecreordenanza="";
   }

if (array_key_exists("txtfirma2ordenanza",$_POST))
   {
     $ls_firma2=$_POST["txtfirma2ordenanza"];
     $lr_datos["firma2_ordenanza"]=$ls_firma2;
   }
else
   {
     $ls_firma2="";
   }
if (array_key_exists("txtcargo2ordenanza",$_POST))
   {
     $ls_cargo2=$_POST["txtcargo2ordenanza"];
     $lr_datos["cargo2_ordenanza"]=$ls_cargo2;
   }
else
   {
     $ls_cargo2_="";
   }
if (array_key_exists("txtfirma3ordenanza",$_POST))
   {
     $ls_firma3=$_POST["txtfirma3ordenanza"];
     $lr_datos["firma3_ordenanza"]=$ls_firma3;
   }
else
   {
     $ls_firma3="";
   }
if (array_key_exists("txtcargo3ordenanza",$_POST))
   {
     $ls_cargo3=$_POST["txtcargo3ordenanza"];
     $lr_datos["cargo3_ordenanza"]=$ls_cargo3;
   }
else
   {
     $ls_cargo3="";
   }
if (array_key_exists("txtfirma4ordenanza",$_POST))
   {
     $ls_firma4=$_POST["txtfirma4ordenanza"];
     $lr_datos["firma4_ordenanza"]=$ls_firma4;
   }
else
   {
     $ls_firma4="";
   }
if (array_key_exists("txtcargo4ordenanza",$_POST))
   {
     $ls_cargo4=$_POST["txtcargo4ordenanza"];
     $lr_datos["cargo4_ordenanza"]=$ls_cargo4;
   }
else
   {
     $ls_cargo4="";
   }
if (array_key_exists("txtfirma5ordenanza",$_POST))
   {
     $ls_firma5=$_POST["txtfirma5ordenanza"];
     $lr_datos["firma5_ordenanza"]=$ls_firma5;
   }
else
   {
     $ls_firma5="";
   }
if (array_key_exists("txtcargo5ordenanza",$_POST))
   {
     $ls_cargo5=$_POST["txtcargo5ordenanza"];
     $lr_datos["cargo5_ordenanza"]=$ls_cargo5;
   }
else
   {
     $ls_cargo5="";
   }
if (array_key_exists("txtfirma6ordenanza",$_POST))
   {
     $ls_firma6=$_POST["txtfirma6ordenanza"];
     $lr_datos["firma6_ordenanza"]=$ls_firma6;
   }
else
   {
     $ls_firma6="";
   }
if (array_key_exists("txtcargo6ordenanza",$_POST))
   {
     $ls_cargo6=$_POST["txtcargo6ordenanza"];
     $lr_datos["cargo6_ordenanza"]=$ls_cargo6;
   }
else
   {
     $ls_cargo6="";
   }
if (array_key_exists("txtfirma7ordenanza",$_POST))
   {
     $ls_firma7=$_POST["txtfirma7ordenanza"];
     $lr_datos["firma7_ordenanza"]=$ls_firma7;
   }
else
   {
     $ls_firma7="";
   }
if (array_key_exists("txtcargo7ordenanza",$_POST))
   {
     $ls_cargo7=$_POST["txtcargo7ordenanza"];
     $lr_datos["cargo7_ordenanza"]=$ls_cargo7;
   }
else
   {
     $ls_cargo3="";
   }
if (array_key_exists("txtfirma8ordenanza",$_POST))
   {
     $ls_firma8=$_POST["txtfirma8ordenanza"];
     $lr_datos["firma8_ordenanza"]=$ls_firma8;
   }
else
   {
     $ls_firma8="";
   }
if (array_key_exists("txtcargo8ordenanza",$_POST))
   {
     $ls_cargo8=$_POST["txtcargo8ordenanza"];
     $lr_datos["cargo8_ordenanza"]=$ls_cargo8;
   }
else
   {
     $ls_cargo8="";
   }

if (array_key_exists("txtfirma9ordenanza",$_POST))
   {
     $ls_firma9=$_POST["txtfirma9ordenanza"];
     $lr_datos["firma9_ordenanza"]=$ls_firma9;
   }
else
   {
     $ls_firma9="";
   }
if (array_key_exists("txtcargo9ordenanza",$_POST))
   {
     $ls_cargo9=$_POST["txtcargo9ordenanza"];
     $lr_datos["cargo9_ordenanza"]=$ls_cargo9;
   }
else
   {
     $ls_cargo9="";
   }
if (array_key_exists("txtfirma10ordenanza",$_POST))
   {
     $ls_firma10=$_POST["txtfirma10ordenanza"];
     $lr_datos["firma10_ordenanza"]=$ls_firma10;
   }
else
   {
     $ls_firma10="";
   }
if (array_key_exists("txtcargo10ordenanza",$_POST))
   {
     $ls_cargo10=$_POST["txtcargo10ordenanza"];
     $lr_datos["cargo10_ordenanza"]=$ls_cargo10;
   }
else
   {
     $ls_cargo10="";
   }
if (array_key_exists("txtfirma11ordenanza",$_POST))
   {
     $ls_firma11=$_POST["txtfirma11ordenanza"];
     $lr_datos["firma11_ordenanza"]=$ls_firma11;
   }
else
   {
     $ls_firma11="";
   }
if (array_key_exists("txtcargo11ordenanza",$_POST))
   {
     $ls_cargo11=$_POST["txtcargo11ordenanza"];
     $lr_datos["cargo11_ordenanza"]=$ls_cargo11;
   }
else
   {
     $ls_cargo11="";
   }
///////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="GUARDAR")
   { 
	 $lb_existe = $io_empresa->uf_select_empresa();
	 if ($lb_existe)
        {
		  $io_empresa->uf_update_empresa($lr_datos,$la_seguridad);
  	    }  
	 else  //Si no existe 
	    {  
		  $io_empresa->uf_insert_empresa();
	    } 
$ls_codigo          	= "";
$ls_nombre          	= "";
$ls_jefe_presupuesto	= "";
$ls_cargo_presupuesto	= "";
$ls_titulo          	= "";
$ls_firma1		= "";
$ls_cargo1		= "";
$ls_secreordenanza	= "";
$ls_cargosecreordenanza	= "";
$ls_firma2		= "";
$ls_cargo2		= "";
$ls_firma3		= "";
$ls_cargo3		= "";
$ls_firma4		= "";
$ls_cargo4		= "";
$ls_firma5		= "";
$ls_cargo5		= "";
$ls_firma6		= "";
$ls_cargo6		= "";
$ls_firma7		= "";
$ls_cargo7		= "";
$ls_firma8		= "";
$ls_cargo8		= "";
$ls_firma9		= "";
$ls_cargo9		= "";
$ls_firma10		= "";
$ls_cargo10		= "";
$ls_firma11		= "";
$ls_cargo11		= "";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
  </p>
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{	
	print("<script language=JavaScript>");
	print(" location.href='../tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="755" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="titulo-ventana">
        <th height="22" colspan="6" class="titulo-ventana" scope="col">Empresa&nbsp;</th>
    </tr>
      <tr class="formato-blanco">
        <td height="22" colspan="6"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
      </tr>
      <tr class="formato-blanco">
        <td width="126" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="132" height="22"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo ?>" size="8" maxlength="4" readonly></td>
        <td width="34" height="22"><input name="operacion" type="hidden" id="operacion"></td>
        <td width="117" height="22">&nbsp;</td>
        <td width="17" height="22">&nbsp;</td>
        <td width="327" height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Instituci&oacute;n</div></td>
        <td height="22" colspan="5"><input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nombre ?>" size="75" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left" readonly></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Representate de Presupuesto </div></td>
        <td height="22" colspan="5"><input name="txtjefepresupuesto" type="text" id="txtjefepresupuesto" value="<?php print $ls_jefe_presupuesto ?>" size="75" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'-()*/.,;@{}&#?&iquest;!&iexcl;');" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Cargo del Representante de Presupuesto</div></td>
        <td height="22" colspan="5"><input name="txtcargopresupuesto" type="text" id="txtcargopresupuesto" value="<?php print $ls_cargo_presupuesto ?>" size="75" style="text-align:left" maxlength="100"></td>
      </tr>

      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Presidente del Concejo</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma1ordenanza" type="text" id="txtfirma1ordenanza" value="<?php print $ls_firma1 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo1ordenanza" type="text" id="txtcargo1ordenanza" value="<?php print $ls_cargo1 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Vice-Presidente del Concejo</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma2ordenanza" type="text" id="txtfirma2ordenanza" value="<?php print $ls_firma2 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo2ordenanza" type="text" id="txtcargo2ordenanza" value="<?php print $ls_cargo2 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Secretario</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtsecreordenanza" type="text" id="txtsecreordenanza" value="<?php print $ls_secreordenanza ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargosecreordenanza" type="text" id="txtcargosecreordenanza" value="<?php print $ls_cargosecreordenanza ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (1)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma3ordenanza" type="text" id="txtfirma3ordenanza" value="<?php print $ls_firma3 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo3ordenanza" type="text" id="txtcargo3ordenanza" value="<?php print $ls_cargo3 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (2)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma4ordenanza" type="text" id="txtfirma4ordenanza" value="<?php print $ls_firma4 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo4ordenanza" type="text" id="txtcargo4ordenanza" value="<?php print $ls_cargo4 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (3)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma5ordenanza" type="text" id="txtfirma5ordenanza" value="<?php print $ls_firma5 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo5ordenanza" type="text" id="txtcargo5ordenanza" value="<?php print $ls_cargo5 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (4)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma6ordenanza" type="text" id="txtfirma6ordenanza" value="<?php print $ls_firma6 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo6ordenanza" type="text" id="txtcargo6ordenanza" value="<?php print $ls_cargo6 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (5)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma7ordenanza" type="text" id="txtfirma7ordenanza" value="<?php print $ls_firma7 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo7ordenanza" type="text" id="txtcargo7ordenanza" value="<?php print $ls_cargo7 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (6)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma8ordenanza" type="text" id="txtfirma8ordenanza" value="<?php print $ls_firma8 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo8ordenanza" type="text" id="txtcargo8ordenanza" value="<?php print $ls_cargo8 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="7">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (7)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma9ordenanza" type="text" id="txtfirma9ordenanza" value="<?php print $ls_firma9 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo9ordenanza" type="text" id="txtcargo9ordenanza" value="<?php print $ls_cargo9 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (8)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma10ordenanza" type="text" id="txtfirma10ordenanza" value="<?php print $ls_firma10 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo10ordenanza" type="text" id="txtcargo10ordenanza" value="<?php print $ls_cargo10 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center"><div align="center">Concejal (9)</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="766" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="109" height="22"><input name="txtfirma11ordenanza" type="text" id="txtfirma11ordenanza" value="<?php print $ls_firma11 ?>" size="65" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Cargo </div></td>
            <td height="22"><input name="txtcargo11ordenanza" type="text" id="txtcargo11ordenanza" value="<?php print $ls_cargo11 ?>" size="65" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left"></td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="7">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      
      <tr class="formato-blanco">
        <td height="22" colspan="6"><div align="center"><a href="#top">Volver Arriba </a></div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
    </table>
    <div align="center"></div>
  </form>
</body>
<script language="javascript">
function uf_catalogo_beneficiario()
{
	f      = document.form1;
	pagina = "tepuy_catdinamic_bene.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

/*Function:  ue_guardar()
	 *
	 *Descripción: Función que se llama al presionar la opcion de "Grabar" en el toolbar o en el menu la cual realiza primero
	               la verificacion de que las cajas de textos no esten vacias. En caso de que exista un campo vacio se enviara
				   un mensaje Javascript al usuario indicandole que campo(s) debe rellenar apoyandose en la funcion valida_null y en caso de que todos los campos estén llenos
				   se procede al llamado del codigo PHP respectivo a si la opcion es "GUARDAR".*/
function ue_guardar()
{//1
var resul="";
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
   { //1
     with (document.form1)
	      { //2
	        if (valida_null(txtnombre,"El Nombre de la Empresa esta vacio !!!")==false)
		       {//3
		         f.txtnombre.focus();
		       }//3
		    else
		       {//4
		         if (valida_null(txtjefepresupuesto,"El Jefe de Presupuesto esta vacio !!!")==false)
		            {//5
			          f.txtjefepresupuesto.focus();
		            } //5
		         else
		            {//6
		              if (valida_null(txtcargopresupuesto,"El Cargo de Presupuesto esta vacio !!!")==false)
		                 {//7
			               f.txtcargopresupuesto.focus();
		                 }//7
			      else
				{//8
					f.operacion.value="GUARDAR";
					f.action="tepuy_cfg_d_empresa.php";
					f.submit();
				}//8
		            }//6
			}//4
	   	}//2
} //1
else
  {
	  alert("No tiene permiso para realizar esta operación");
  }
}//1

/*Function:  valida_null(field , mensaje)
	 *
	 *Descripción:   Función que se encarga de evaluar al objeto "field" para verificar si esta o no en blanco, en caso de que el objeto 
	                 este vacio se imprime el mensaje y se devuelve false,en caso contrario se devuelve true.
	  *Argumentos:   field: Objeto el cual va a ser chequeado su condicion de vacio. Ejempo: txtcedula.  
	                 mensaje: Cadena de caracteres que se mostrara al usuario en caso de que el contenido del objeto sea igual a null o
					 igual a vacio(blanco).*/
function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	
/*Fin de la Funcion valida_null*/


/*Function:  ue_buscar()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de los proveedores*/   
   function ue_buscar()
	{
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
	    f.operacion.value="BUSCAR";
		pagina="tepuy_cfg_cat_empresa.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
	   }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
    }
/*Fin de la Función ue_buscar()*/

function currencyFormat(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8)  return true; // Enter 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }

	
	function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}

	function valSep(oTxt)
		{ 
			var bOk = false; 
			var sep1 = oTxt.value.charAt(2); 
			var sep2 = oTxt.value.charAt(5); 
			bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
			bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
			return bOk; 
		} 
		
		function finMes(oTxt)
		{ 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			var nAno = parseInt(oTxt.value.substr(6), 10); 
			var nRes = 0; 
			switch (nMes)
			{ 
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
		
		function valDia(oTxt)
		{ 
		   var bOk = false; 
		   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
		   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
		   return bOk; 
		} 
		
		function valMes(oTxt)
		{ 
			var bOk = false; 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
			return bOk; 
		} 
		
		function valAno(oTxt)
		{ 
			var bOk = true; 
			var nAno = oTxt.value.substr(6); 
			bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
			if (bOk)
			   { 
				 for (var i = 0; i < nAno.length; i++)
				     { 
				       bOk = bOk && esDigito(nAno.charAt(i)); 
				     } 
			   } 
		 return bOk; 
		 } 
		
		 function valFecha(oTxt)
		 { 
			var bOk = true; 
			if (oTxt.value !="")
			   { 
				bOk = bOk && (valAno(oTxt)); 
				bOk = bOk && (valMes(oTxt)); 
				bOk = bOk && (valDia(oTxt)); 
				bOk = bOk && (valSep(oTxt)); 
				if (!bOk)
				   { 
					 alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
					 oTxt.value = "01/01/1900"; 
					 oTxt.focus(); 
				   } 
			   }
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
		if (li_long==10)
		   {
			 ls_string=ls_date.substr(6,4);
			 li_string=parseInt(ls_string,10);
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
   
/*  esta funcion desabilita los radiobuttom    
     onclick="deshabilitar(this.form,1)" en el metodo del mismo */
function deshabilitar()
{ 
    f=document.form1;
	if(f.radioestmodape[0].checked = true)
	{
	  f.radioestmodape[0].checked = true 
	  f.radioestmodape[0].blur(); 
	  f.radioestmodape[1].blur(); 
	}
	else
	{
	  f.radioestmodape[1].checked = true 
	  f.radioestmodape[0].blur(); 
	  f.radioestmodape[1].blur(); 
	}
}

function uf_catalogo_scgcuentas()
{
	f            = document.form1;
	ls_contabilidad=f.hidtipcont.value;
	if(ls_contabilidad=='2')
	{
		ls_resultado = f.txthaciendapasivo.value;
	}else
	{
		ls_resultado = f.txtresultado.value;
	}
	
	pagina       = "tepuy_cfg_cat_scgcuentas.php?txtresultado="+ls_resultado;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_scgcuentas_financiera()
{
	f            = document.form1;
	ls_ctafinan  = "13";
	pagina       = "tepuy_cfg_cat_scgcuentas_financiera.php?txtresultado="+ls_ctafinan;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_scgcuentas_fiscal()
{
	f            = document.form1;
	ls_ctafiscal = "21";
	pagina       = "tepuy_cfg_cat_scgcuentas_financiera.php?txtresultado="+ls_ctafiscal;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_spgcuentas()
{
	pagina="tepuy_cfg_cat_spgcuentas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,top=0,left=0");
} 

function uf_cambio_niveles ()
{
  f                 = document.form1;
  li_nivel          = f.cmbnumniv.value;
  f.hidnumniv.value = li_nivel;
  if (li_nivel=='1')
     {
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = true;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = true;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = true;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = true;
	 }
  else
     {
	   if (li_nivel=='2')
	      {
			f.txtdesestpro1.readOnly = false;
			f.txtdesestpro2.readOnly = false;
			f.txtdesestpro3.value    = "";
		    f.txtdesestpro3.readOnly = true;
		    f.txtdesestpro4.value    = "";
		    f.txtdesestpro4.readOnly = true;
		    f.txtdesestpro5.value    = "";
		    f.txtdesestpro5.readOnly = true;
		  }
	   else
	      {
		    if (li_nivel=='3')
			   {
	             f.txtdesestpro1.readOnly = false;
	             f.txtdesestpro2.readOnly = false;
	             f.txtdesestpro3.readOnly = false;
				 f.txtdesestpro4.value    = "";
				 f.txtdesestpro4.readOnly = true;
				 f.txtdesestpro5.value    = "";
				 f.txtdesestpro5.readOnly = true;
			   }
		     else
			   {
				 if (li_nivel=='4')
				    {
					  f.txtdesestpro1.readOnly = false;
					  f.txtdesestpro2.readOnly = false;
					  f.txtdesestpro3.readOnly = false;
					  f.txtdesestpro4.readOnly = false;
					  f.txtdesestpro5.value    = "";
					  f.txtdesestpro5.readOnly = true;
				    }
			     else
				    {
					  f.txtdesestpro1.readOnly = false;
					  f.txtdesestpro2.readOnly = false;
					  f.txtdesestpro3.readOnly = false;
					  f.txtdesestpro4.readOnly = false;
					  f.txtdesestpro5.readOnly = false;
					}
			   }
		  } 
	 }
}

function uf_cambio()
{
  f                        = document.form1;
  if (f.radioestructura[0].checked == true)
     {
	   li_tipo = f.radioestructura[0].value;
	 }
  else
     {
	   li_tipo = f.radioestructura[1].value;
	 }
  f.hidestmodest.value = li_tipo; 
  if (li_tipo=='1')
     {
	   f.cmbnumniv.value        = '3';
	   f.cmbnumniv.disabled     = true;
	   f.txtdesestpro1.value    = "";
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = false;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = false;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = true;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = true;
	   f.txtdesestpro1.focus();	 
	 }
  else
     {
	   f.cmbnumniv.value        = '5';
	   f.cmbnumniv.disabled     = true;
	   f.txtdesestpro1.value    = "";
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = false;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = false;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = false;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = false;
	   f.txtdesestpro1.focus();	 
	 }
}
</script>
</html>
