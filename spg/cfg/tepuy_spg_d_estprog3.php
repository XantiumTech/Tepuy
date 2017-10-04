<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
$la_data       = $_SESSION["la_empresa"];
$ls_nomestpro3 = $la_data["nomestpro3"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <?php print $ls_nomestpro3 ?>  </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script language="javascript">
 if (document.all)
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

</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema Control de Gastos</td>
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
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_imprimir()";><img src="../../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
  </tr>
</table>
<?php
	require_once("class_folder/tepuy_spg_c_estprog2.php");
	require_once("class_folder/tepuy_spg_c_estprog3.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_conect     = new tepuy_include();
    $conn          = $io_conect->uf_conectar();
	$io_msg        = new class_mensajes();
	$io_funcion    = new class_funciones();
	$io_sql        = new class_sql($conn);
	$io_estpro2    = new tepuy_spg_c_estprog2($conn);
	$io_estpro3    = new tepuy_spg_c_estprog3($conn);
	$la_data       = $_SESSION["la_empresa"];
	$ls_nomestpro1 = $la_data["nomestpro1"];
	
	/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();
	
	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "SPG";
	$ls_ventanas = "tepuy_spg_d_estprog3.php";
	
	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
    	$li_estmodest             = $arre["estmodest"];

if ($li_estmodest=='1')
   {
	 $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_maxlength_3 = '3';
	 $li_size        = '25';
	 $li_ancho       = '60';
   }
else
   {
	 $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_maxlength_3 = '2';
	 $li_size        = '5';
	 $li_ancho       = '80';
   }
	
	if (array_key_exists("permisos3",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos3"];
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
		 $ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	if (array_key_exists("statusprog3",$_POST))
	   {
  	     $ls_status=$_POST["statusprog3"];
	   }
	else
	   {
	     $ls_status="NUEVO";	  
	   }
	
	if (array_key_exists("operacionestprog3",$_POST))
	   {
		 $ls_operacion  = $_POST["operacionestprog3"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_denestpro1 = $_POST["txtdenestpro1"];
		 $ls_denestpro2 = $_POST["txtdenestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 $ls_denestpro3 = $_POST["txtdenestpro3"];
		 $ls_responsable= $_POST["txtresponsable"];
		 $ls_extordinal = $_POST["txtextordinal"];
		 $ls_codfuefin  = "";
		 $ls_denfuefin  = "";
		 if ($li_estmodest=='1')
   		 {
			 $ls_codfuefin  = $_POST["txtcodigo"];
			 $ls_denfuefin  = $_POST["txtdenominacion"];
   		 }
		 $readonly      = "";
	   }
	else
	   {
		 $ls_operacion  = "";
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_denestpro1 = $_POST["txtdenestpro1"];
		 $ls_denestpro2 = $_POST["txtdenestpro2"];
		 $ls_codestpro3 = "";
		 $ls_denestpro3 = "";
		 $ls_responsable= "";
		$ls_extordinal = $_POST["txtextordinal"];
		 $ls_codfuefin  = "";
	     $ls_denfuefin  = "";
		 $readonly      = "";
	   }
	
	if ($ls_operacion == "NUEVO")
	   {
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_denestpro1 = $_POST["txtdenestpro1"];
		 $ls_denestpro2 = $_POST["txtdenestpro2"];
		 $ls_codestpro3 = "";
		 $ls_denestpro3 = "";
		 $ls_responsable= "";
		$ls_extordinal = $_POST["txtextordinal"];
		 $ls_codfuefin  = "";
	     $ls_denfuefin  = "";
		 $readonly      = "";
	   }

if ($ls_operacion == "GUARDAR")
   {

	 $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
	 $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
	 $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
	 $lb_encontrado = $io_estpro2->uf_spg_select_estprog2($ls_codemp,$ls_codestpro1,$ls_codestpro2);
	 if ($lb_encontrado)
	    {
		  $lb_existe     = $io_estpro3->uf_spg_select_estprog3($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3); 
		  if (!$lb_existe)
			 { 
			   $lb_valido = $io_estpro3->uf_spg_insert_estprog3($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_codfuefin,$ls_responsable,$ls_extordinal,$li_estmodest,$la_seguridad);		
			   if ($lb_valido)
				  {
				    $io_sql->commit();
				    $io_msg->message("Registro Incluido !!!");
				    if ($li_estmodest=='2')
					   {
						 $ls_codestpro1 = substr($ls_codestpro1,18,2);
						 $ls_codestpro2 = substr($ls_codestpro2,4,2);
					   }
				    $ls_codestpro3 = "";
				    $ls_denestpro3 = "";
					$ls_codfuefin  = "";
	    			$ls_denfuefin  = "";
				    $ls_status     = "NUEVO";
				  }
			   else
				  {
				    $io_sql->rollback();
				    $io_msg->message($io_estpro3->is_msg_error);
				  }
		 	 }
		  else
			 { 
			   $lb_valido = $io_estpro3->uf_spg_update_estprog3($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_codfuefin,$ls_responsable,$ls_extordinal,$la_seguridad);		
			   if ($lb_valido)
				  {
				    $io_sql->commit();
				    $io_msg->message("Registro Actualizado !!!");
				    if ($li_estmodest=='2')
					   {
						 $ls_codestpro1 = substr($ls_codestpro1,18,2);
						 $ls_codestpro2 = substr($ls_codestpro2,4,2);
					   }
				    $ls_codestpro3 = "";
				    $ls_denestpro3 = "";
				$ls_codfuefin  = "";
	     			$ls_denfuefin  = "";
				$ls_responsable= "";
				$ls_extordinal = "";
				    $ls_status     = "NUEVO"; 
				  }
			   else
				  {
				    $io_sql->rollback();
				    $io_msg->message($io_estpro3->is_msg_error);
				  }
			 }
		} 
	 else
	    {
		  $io_msg->message("Debe registrar la Estructura de Nivel 2 previamente !!!");
		}
   }	 
	
if ($ls_operacion == "ELIMINAR")
   {
     $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
	 $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
	 $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
	 $lb_valido     = $io_estpro3->uf_spg_delete_estprog3($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$li_estmodest,$la_seguridad);
	 if ($lb_valido)
	    {
		  $io_sql->commit();
		  $io_msg->message("Registro Eliminado !!!");
		}
	 else
	    {
		  $io_sql->rollback();
		  $io_msg->message($io_estpro3->is_msg_error);
		}
     if ($li_estmodest=='2')
	    {
	      $ls_codestpro1 = substr($ls_codestpro1,18,2);
	      $ls_codestpro2 = substr($ls_codestpro2,4,2);
	    }
	 $ls_codestpro3 = "";
	 $ls_denestpro3 = "";
	 $ls_codfuefin  = "";
	 $ls_denfuefin  = "";
	 $ls_responsable= "";
	 $ls_extordinal = "";
	 $ls_status     = "NUEVO";
	 $readonly      = "";
   }
	
if ($ls_operacion == "BUSCAR")
   {
     	 $ls_codestpro1 = $_POST["txtcodestpro1"];
   	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $ls_codestpro3 = $_POST["txtcodestpro3"];
	 $ls_denestpro3 = $_POST["txtdenestpro3"];
	 $ls_responsable = $_POST["txtresponsable"];
	 $ls_extordinal = $_POST["txtextordinal"];
	 $readonly      = "readonly";
   }
	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="663" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="661" height="221" valign="top">
		<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos3 id=permisos3 value='$ls_permisos'>");
	print("<input type=hidden name=leer      id=leer      value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir   id=incluir   value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar   id=cambiar   value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar  id=eliminar  value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir  id=imprimir  value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular    id=anular    value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar  id=ejecutar  value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='../tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
          <p>&nbsp;</p>
          <table width="603" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3"><input name="hidmaestro" type="hidden" id="hidmaestro" value="Y">
                <?php print $ls_nomestpro3?></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td width="484" height="22" colspan="2"><input name="statusprog3" type="hidden" id="statusprog3" value="<?php print $ls_status ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" align="right"><?php print $la_data["nomestpro1"]?></td>
                <td height="22" colspan="2" align="left"><input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" value="<?php print  $ls_codestpro1?>" readonly="" style="text-align:center">
                <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1?>" size="<?php print $li_ancho ?>" maxlength="80" readonly=""></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="22"><div align="right" >
                    <p><?php print $la_data["nomestpro2"]?></p>
                </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center " value="<?php print $ls_codestpro2 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_mexlength_2 ?>" <?php print $readonly?> readonly>
                    <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" style="text-align:left" value="<?php print $ls_denestpro2?>" size="<?php print $li_ancho ?>" maxlength="100" readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">
                  <p>Codigo</p>
                </div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print $ls_codestpro3 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_3 ?>" style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,<?php print $li_maxlength_3 ?>,'cod')" <?php print $readonly?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');" >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td height="22" colspan="2" align="left"><input name="txtdenestpro3" type="text" id="txtdenestpro3" value="<?php print $ls_denestpro3?>" size="82" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');"></td>
              </tr>
              <?php 
			  if ($li_estmodest=='1')
				     { ?>
			  
			
               <tr>

              <tr class="formato-blanco">
                <td height="22"><div align="right">Responsable</div></td>
                <td height="22" colspan="2" align="left"><input name="txtresponsable" type="text" id="txtresponsable" value="<?php print $ls_responsable?>" size="82" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');"></td>
              </tr>

              <tr class="formato-blanco">
                <td height="22"><div align="right">Extension del ordinal</div></td>
                <td height="22" colspan="2" align="left"><input name="txtextordinal" type="text" id="txtextordinal" value="<?php print $ls_extordinal?>" size="4" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');"></td>
              </tr>

            <td height="22"><div align="right">Fuente de Financiamiento</div></td>
            <td height="22" colspan="3"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="5" maxlength="2" readonly> 
              <a href="javascript: ue_catalogo('tepuy_spg_cat_fuentefinan.php');"><img src="../../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
              <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" value="<?php print $ls_denfuefin;?>" size="50" readonly></td>
          </tr>
              <?
					 }
			?>
              <tr class="formato-blanco">
                <td height="33" colspan="3">      <table width="506" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <?php 
				  if ($li_estmodest=='2')
				     { ?>
					   <tr>
						 <td width="246"><div align="center"><a href="javascript: uf_estprog2();"><?php print "Ir a ".$la_data["nomestpro2"]?></a></div></td>
						 <td width="244"><div align="center"><a href="javascript: uf_estprog4(<?php print $li_estmodest ?>);"><?php print "Ir a ".$la_data["nomestpro4"]?></a></div></td>
					   </tr>
				   <?
					 }
				   else
				     { ?>
					   <tr>
						 <td width="246"><div align="center"><a href="javascript: uf_estprog2();"><?php print "Ir a ".$la_data["nomestpro2"]?></a></div></td>
						 <td width="244"><div align="center"><a href="javascript: uf_estprog1();"><?php print "Ir a ".$la_data["nomestpro1"]?></a></div></td>
					   </tr>
				   <?
					 }
				   ?>
                </table>                  </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td height="22" colspan="2" align="left">&nbsp;</td>
              </tr>
          </table>
            <p align="center">
            <input name="operacionestprog3" type="hidden" id="operacionestprog3">
</p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
f = document.form1;
function ue_nuevo()
{
	f.operacionestprog3.value ="NUEVO";
	f.txtcodigo.value ="";
	f.txtdenominacion.value ="";
	f.txtdenestpro3.value ="";
	f.txtcodestpro3.value="";
	f.txtextordinal.value="";
	f.action="tepuy_spg_d_estprog3.php";
	f.submit();
}

function ue_guardar()
{
	li_incluir    = f.incluir.value;
    li_cambiar    = f.cambiar.value;
    lb_status     = f.statusprog3.value;
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro3 = f.txtdenestpro3.value;
    if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
       {
	     if ((ls_codestpro1!="") && (ls_codestpro2!="") && (ls_codestpro3!="") && (ls_denestpro3!=""))
	        {
 	          f.operacionestprog3.value = "GUARDAR";   
	          f.action                  = "tepuy_spg_d_estprog3.php";
 	          f.submit();
	        }	
	     else
		    {
			  alert("Debe completar todos los campos !!!");
		    }
       }
    else
       {
         alert("No tiene permiso para realizar esta operación !!!");
       }  
}

function ue_eliminar()
{
li_eliminar = f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
	    { 
          f.operacionestprog3.value ="ELIMINAR";
          f.action="tepuy_spg_d_estprog3.php";
          f.submit();
  	    }
     else
	    {
		  alert("Eliminación Cancelada !!!");
		}
   }
  else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}

function ue_buscar()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	window.open("tepuy_spg_cat_estpro3.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtpantalla=d_estprog3","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f.action="../tepuywindow_blank.php";
	f.submit();
}

function ue_imprimir()
{
	window.open("../reportes/tepuy_spg_rpp_distribucion_categorias.php?cmbnivel=s1","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=300,left=50,top=50,location=no,resizable=yes");
}

function uf_estprog1()
{
	f.action="tepuy_spg_d_estprog1.php";
	f.submit();
}

function uf_estprog2()
{
	f.action="tepuy_spg_d_estprog2.php";
	f.submit();
}
function uf_estprog4(li_estmodest)
{
	if (li_estmodest=='2')
	   {
	     f.action="tepuy_spg_d_estprog4.php";
	     f.submit();
       }
}
//Funcion de relleno con ceros a un textfield.
function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	if (cadena!="")
	   {
	     cadena_ceros = "";
	     lencad       = mystring.length;
	     total        = longitud-lencad;
	     for (i=1;i<=total;i++)
	         {
		       cadena_ceros=cadena_ceros+"0";
	         }
 	     cadena = cadena_ceros+cadena;
	     if (campo=="cod")
	        {
		      document.form1.txtcodestpro3.value=cadena;
	        }
	   }
}

function ue_catalogo(ls_catalogo)
{
	if(ls_catalogo=='tepuy_spg_cat_fuentefinan.php')
	{
		//f=document.formulario;
		//lb_existe=f.existe.value;
		window.open("tepuy_spg_cat_fuentefinan.php?txtpantalla=d_estprog5","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		/*if(lb_existe=="TRUE")
		{
			alert("La Unidad Ejecutora no se puede modificar.");		
		}
		else
		{
			if((f.totrowbienes.value>1)||(f.totrowservicios.value>1)||(f.totrowconceptos.value>1)||
			   (f.totrowcargos.value>0)||(f.totrowcuentas.value>1)||(f.totrowcuentascargo.value>1))
			{
				alert("La Unidad Ejecutora no se puede modificar, ya existen movimientos en la solicitud.");		
			}
			else
			{
				// abre el catalogo que se paso por parametros
				window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
			}
		}*/
	}
	else
	{
		// abre el catalogo que se paso por parametros
		//window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}
</script>
</html>
