<?php
session_start();
$dat=$_SESSION["la_empresa"];
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../../tepuy_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de Plan de Cuentas de Gasto.</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/disabled_keys.js"></script>
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
a:hover {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
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
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_tepuy_int.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("class_folder/tepuy_spg_c_planctas.php");
	require_once("../../shared/class_folder/class_tepuy_int_spg.php");
	$io_msg     = new class_mensajes();
	$sig_spgcta = new tepuy_spg_c_planctas();
	$in=     new tepuy_include();
	$con= $in->uf_conectar();
	$io_sql=  new class_sql($con);

    $int_spg= new class_tepuy_int_spg();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema     = "SPG";
	$ls_ventanas    = "tepuy_spg_d_copiarplandecuentas.php";
	$la_security[1] = $ls_empresa;
	$la_security[2] = $ls_sistema;
	$la_security[3] = $ls_logusr;
	$la_security[4] = $ls_ventanas;
    $li_estmodest   = $arre["estmodest"];
	$ls_nomestpro4  = $dat["nomestpro4"];
	$ls_nomestpro5  = $dat["nomestpro5"];
	if ($li_estmodest=='1')
	   {
	     $li_maxlenght_1 = '20';
	     $li_maxlenght_2 = '6';
	     $li_maxlenght_3 = '3';
	     $li_size        = '25';
	     $ls_ancho       = '65';
	     $ls_nomestpro4  = "";
	     $ls_nomestpro5  = "";
	     $ls_denestpro4  = "";
	     $ls_denestpro5  = "";
	   }
	else
	   {
	     $li_maxlenght_1 = '2';
	     $li_maxlenght_2 = '2';
	     $li_maxlenght_3 = '2';
	     $li_size        = '5';
	     $ls_ancho       = '85';
	   }

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisostepuy();
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
if  (array_key_exists("status",$_POST))
	{
  	  $ls_estatus=$_POST["status"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}	
	
	
	$ls_formato=trim($dat["formpre"]);
	$ls_formatoaux = str_replace( "-", "",$ls_formato);
	$li_size_cta=strlen($ls_formatoaux);
	//Arreglo que contiene los parametros de configuracion de la empresa
	$dat=$_SESSION["la_empresa"];

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion  = $_POST["operacion"];
		$ls_codestpro1 = $_POST["codestpro1"];
		$ls_codestpro2 = $_POST["codestpro2"];
		$ls_codestpro3 = $_POST["codestpro3"];
		$ls_codestpro1h=$_POST["codestpro1h"];	
		$ls_codestpro2h=$_POST["codestpro2h"];
		$ls_codestpro3h=$_POST["codestpro3h"];
		if ($li_estmodest=='2')
		   {
		   	 $ls_codestpro4 = $_POST["codestpro4"];
		     $ls_codestpro5 = $_POST["codestpro5"];
			 $ls_codestpro4h=$_POST["codestpro4h"];	
			 $ls_codestpro5h=$_POST["codestpro5h"];	
		   }
		else
		   {
		   	 $ls_codestpro4 = "";
		     $ls_codestpro5 = "";
			 $ls_codestpro4h = "";
		     $ls_codestpro5h = "";

		   }
	}
	else
	{
		$ls_operacion  = "NUEVO";
		$ls_codestpro1 = "";
		$ls_codestpro2 = "";
		$ls_codestpro3 = "";	
		$ls_codestpro4 = "";
		$ls_codestpro5 = "";	
		$ls_codestpro1h = "";
		$ls_codestpro2h = "";
		$ls_codestpro3h = "";
		$ls_codestpro4h = "";
		$ls_codestpro5h = "";
	}
	
	/////////////////////// N U E V O///////////////////////////////////////////////////////////////////
	if($ls_operacion=="NUEVO")
	{
		$ls_codestpro1 = "";
		$ls_codestpro2 = "";
		$ls_codestpro3 = "";	
		$ls_codestpro4 = "";
		$ls_codestpro5 = "";	
		$ls_codestpro1h = "";
		$ls_codestpro2h = "";
		$ls_codestpro3h = "";
		$ls_codestpro4h = "";
		$ls_codestpro5h = "";
	}
	/////////////////////// G U A R D A R///////////////////////////////////////////////////////////////////
	if($ls_operacion=="COPIAR")
	{
    	$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"]; 
		$ls_codestpro3=$_POST["codestpro3"];
		$ls_codestpro1h=$_POST["codestpro1h"];
		$ls_codestpro2h=$_POST["codestpro2h"]; 
		$ls_codestpro3h=$_POST["codestpro3h"];
		$li_error      = 0;
		$li_save       = 0;
		$io_sql->begin_transaction();
		if ($li_estmodest=='2') 
		   {
		    /* $ls_codestpro1=str_pad($ls_codestpro1,20,"0",STR_PAD_LEFT);
  	         $ls_codestpro2=str_pad($ls_codestpro2,6,"0",STR_PAD_LEFT);
	         $ls_codestpro3=str_pad($ls_codestpro3,3,"0",STR_PAD_LEFT);
			 $ls_codestpro1h=str_pad($ls_codestpro1h,20,"0",STR_PAD_LEFT);
  	         $ls_codestpro2h=str_pad($ls_codestpro2h,6,"0",STR_PAD_LEFT);
	         $ls_codestpro3h=str_pad($ls_codestpro3h,3,"0",STR_PAD_LEFT);*/
		     $ls_codestpro4 =$_POST["codestpro4"]; 
		     $ls_codestpro5 =$_POST["codestpro5"];  
			 $ls_codestpro4h=$_POST["codestpro4h"];	
			 $ls_codestpro5h=$_POST["codestpro5h"];	
		   }
		else
		   {
		     $ls_codestpro4="00";
		     $ls_codestpro5="00";
			 $ls_codestpro4h="00";
		     $ls_codestpro5h="00";
		   }
		if ($li_estmodest=='2')
		{
		   if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!="")&&($ls_codestpro4!="")&&($ls_codestpro5!=""))
			{
				$lb_valido=$sig_spgcta->uf_buscar_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);
				if($lb_valido)
				{
					$li_tot=$sig_spgcta->dts_reporte->getRowCount("spg_cuenta"); 
					for($z=1;$z<=$li_tot;$z++)
					{  
					  /*$ls_codestpro1=$sig_spgcta->dts_reporte->data["codestpro1"][$z];
					  $ls_codestpro2=$sig_spgcta->dts_reporte->data["codestpro2"][$z];
					  $ls_codestpro3=$sig_spgcta->dts_reporte->data["codestpro3"][$z];
					  $ls_codestpro4=$sig_spgcta->dts_reporte->data["codestpro4"][$z];
					  $ls_codestpro5=$sig_spgcta->dts_reporte->data["codestpro5"][$z];*/
					  $ls_spg_cuenta=$sig_spgcta->dts_reporte->data["spg_cuenta"][$z];
					  $ls_denominacion=$sig_spgcta->dts_reporte->data["denominacion"][$z];
					  $ls_status=$sig_spgcta->dts_reporte->data["status"][$z];
					  $ls_sccuenta=$sig_spgcta->dts_reporte->data["sc_cuenta"][$z];
					  $ls_nivel=$sig_spgcta->dts_reporte->data["nivel"][$z];
					  $ls_referencia=$sig_spgcta->dts_reporte->data["referencia"][$z];
					  $lb_valido=$sig_spgcta->uf_verificar_cuentas_destino($ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,
																		   $ls_codestpro5h,$ls_spg_cuenta);
					  if ($lb_valido)
					  {
						$li_error=$li_error+1; 
				      }
					  else
					  {
		     		 	  $ls_codestpro1h=str_pad($ls_codestpro1h,20,"0",STR_PAD_LEFT);
						  $ls_codestpro2h=str_pad($ls_codestpro2h,6,"0",STR_PAD_LEFT);
						  $ls_codestpro3h=str_pad($ls_codestpro3h,3,"0",STR_PAD_LEFT);
						  $lb_existe=$int_spg->uf_spg_insert_cuenta($ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,  
															$ls_codestpro5h,$ls_spg_cuenta,$ls_denominacion,$ls_sccuenta,
															$ls_status,$ls_nivel,$ls_referencia);
				          if ($lb_existe)
						   {
							$li_save=$li_save+1;
						   }
					   }// fin del else
				    }// fin del for
					if ($li_error>=1)
					{
					 $io_sql->rollback();
   					 $io_msg->message("No se puede Copiar. Ya existe $li_error cuenta Contable asociada a esa Estructura");
					}
					else
					{
					   $io_sql->commit();
					   $io_msg->message("$li_save Cuenta(s) guardada(s) ,$li_error Cuenta(s) con error");
					}
				}
				else
				{
				  $io_msg->message("No se encontraron cuentas contables asociada a esa estructura");
				}
		
			}
		}
		else
		{
			if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!=""))
			{
				$lb_valido=$sig_spgcta->uf_buscar_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);
				if($lb_valido)
				{
					$li_tot=$sig_spgcta->dts_reporte->getRowCount("spg_cuenta"); 
					for($z=1;$z<=$li_tot;$z++)
					{  
					  /*$ls_codestpro1=$sig_spgcta->dts_reporte->data["codestpro1"][$z];
					  $ls_codestpro2=$sig_spgcta->dts_reporte->data["codestpro2"][$z];
					  $ls_codestpro3=$sig_spgcta->dts_reporte->data["codestpro3"][$z];
					  $ls_codestpro4=$sig_spgcta->dts_reporte->data["codestpro4"][$z];
					  $ls_codestpro5=$sig_spgcta->dts_reporte->data["codestpro5"][$z];*/
					  $ls_spg_cuenta=$sig_spgcta->dts_reporte->data["spg_cuenta"][$z];
					  $ls_denominacion=$sig_spgcta->dts_reporte->data["denominacion"][$z];
					  $ls_status=$sig_spgcta->dts_reporte->data["status"][$z];
					  $ls_sccuenta=$sig_spgcta->dts_reporte->data["sc_cuenta"][$z];
					  $ls_nivel=$sig_spgcta->dts_reporte->data["nivel"][$z];
					  $ls_referencia=$sig_spgcta->dts_reporte->data["referencia"][$z];
					  $lb_valido=$sig_spgcta->uf_verificar_cuentas_destino($ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,
																		   $ls_codestpro5h,$ls_spg_cuenta);
					  if ($lb_valido)
					  {
						$li_error=$li_error+1; 
				      }
					  else
					  {
					      $ls_codestpro1h=str_pad($ls_codestpro1h,20,"0",STR_PAD_LEFT);
						  $ls_codestpro2h=str_pad($ls_codestpro2h,6,"0",STR_PAD_LEFT);
						  $ls_codestpro3h=str_pad($ls_codestpro3h,3,"0",STR_PAD_LEFT);
						  $lb_existe=$int_spg->uf_spg_insert_cuenta($ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,  
															$ls_codestpro5h,$ls_spg_cuenta,$ls_denominacion,
															$ls_sccuenta,$ls_status,$ls_nivel,$ls_referencia);
				 
						  if ($lb_existe)
						   {
							$li_save=$li_save+1;
						   }
					   }// fin del else
				    }// fin del for
					if ($li_error>=1)
					{
					 $io_sql->rollback();
   					 $io_msg->message("No se puede Copiar. Ya existe $li_error cuenta Contable asociada a esa Estructura");
					 
					}
					else
					{
					   $io_sql->commit();
					   $io_msg->message("$li_save Cuenta(s) guardada(s) ,$li_error Cuenta(s) con error");
					}
				}
				else
				{
				  $io_msg->message("No se encontraron cuentas contables asociada a esa estructura");
				}
		
			}
		}
		$ls_codestpro1 = "";
		$ls_codestpro2 = "";
		$ls_codestpro3 = "";	
		$ls_codestpro4 = "";
		$ls_codestpro5 = "";	
		$ls_codestpro1h = "";
		$ls_codestpro2h = "";
		$ls_codestpro3h = "";
		$ls_codestpro4h = "";
		$ls_codestpro5h = "";
	}

?>
<p>&nbsp;</p>
<div align="center">
  <table width="718" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="716" height="221" valign="top">
		<form name="form1" method="post" action="" >
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
		
          <p>&nbsp;</p>
          <table width="680" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2">Traspasos de Cuentas entre Estructuras Presupuestarias</td>
              </tr>
              <tr class="formato-blanco">
                <td width="292" height="22">&nbsp;</td>
                <td width="386" height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>">
                <input name="hidmaestro" type="hidden" id="hidmaestro" value="<?php print $ls_maestro ?>"></td>
              </tr>
              <tr>
                <td colspan="3" align="center"><div align="left">
                    <p>
                      <?php 
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
	   ?>
                    </p>
                  <table width="551" height="70" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
                      <!--DWLayoutTable-->
                      <!--<tr class="titulo-celda">
                        <td height="13" colspan="9" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
                      </tr>-->
                      <tr class="formato-blanco">
                        <td width="38" height="14">Origen</td>
                        <td width="136" height="22"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1; ?>" size="22" maxlength="20"></td>
                        <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
                        <td width="20"></td>
                        <td width="135"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2; ?>" size="22" maxlength="6"></td>
                        <td width="20"><a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
                        <td width="20"></td>
                        <td width="136"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3; ?>" size="22" maxlength="3"></td>
                        <td width="24"><a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="14"><div align="right">Destino</div></td>
                        <td height="22"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h; ?>" size="22" maxlength="20"></td>
                        <td><a href="javascript:catalogo_estprohas1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
                        <td></td>
                        <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h; ?>" size="22" maxlength="6"></td>
                        <td><a href="javascript:catalogo_estprohas2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
                        <td></td>
                        <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h; ?>" size="22" maxlength="3"></td>
                        <td><a href="javascript:catalogo_estprohas3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                      </tr>
                  </table>
                  <p>
          <?php 
		  }
		  else
		  {
		?>
                  </p>
                  <table width="550" height="65" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
                      <!--DWLayoutTable-->
                      <!--<tr class="titulo-celda">
                        <td height="13" colspan="15" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
                      </tr>-->
                      <tr class="formato-blanco">
                        <td width="41" height="18"><div align="right">Origen</div></td>
                        <td width="50" height="18"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
                        <td width="40"></td>
                        <td width="50"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td width="27"><a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
                        <td width="51"></td>
                        <td width="50"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td width="20"><a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                        <td width="40"><label></label></td>
                        <td width="50"><input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_codestpro4; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td width="29"><a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                        <td width="40"><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td width="50"><label>
                          <input name="codestpro5" type="text" id="codestpro5" value="<?php print  $ls_codestpro5; ?>" size="5" maxlength="2" style="text-align:center">
                        <a href="javascript:catalogo_estpro5();"></a></label></td>
                        <td width="35"><a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="29"><div align="right">Destino</div></td>
                        <td height="29"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td><a href="javascript:catalogo_estprohas1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
                        <td></td>
                        <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td><a href="javascript:catalogo_estprohas2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
                        <td></td>
                        <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td><a href="javascript:catalogo_estprohas3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                        <td><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td><label>
                          <input name="codestpro4h" type="text" id="codestpro4h" value="<?php print $ls_codestpro4h; ?>" size="5" maxlength="2" style="text-align:center">
                        </label></td>
                        <td><a href="javascript:catalogo_estprohas4();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                        <td><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td><input name="codestpro5h" type="text" id="codestpro5h" value="<?php print $ls_codestpro5h; ?>" size="5" maxlength="2" style="text-align:center"></td>
                        <td><a href="javascript:catalogo_estprohas5();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
                      </tr>
                  </table>
			<?php
			}
			?>
              </div></td>
              </tr>
  
                <tr>
                  <td height="13">&nbsp;</td>
                  <td height="13">&nbsp;</td>
                </tr>
                <tr>
                  <td height="13">&nbsp;</td>
                  <td height="13"><input name="btncopiar" type="button" class="boton" id="btncopiar" value="Copiar" "onClick='javascript: ue_copiar();'"></td>
                </tr>
                
            <tr class="formato-blanco">
              <td height="22" colspan="2"><p align="center">&nbsp;</p>              </td>
            </tr>
          </table>
            <p align="center">&nbsp;          </p>
          <p align="center">
              <input name="operacion"  type="hidden" id="operacion" >
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
  f = document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="tepuy_spg_d_copiarplandecuentas.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_copiar()
{
f = document.form1;
li_incluir = f.incluir.value;
li_estmodest  = "<?php print $li_estmodest ?>"; 
 if(li_estmodest==1)
	{
		codestpro1 =f.codestpro1.value;
		codestpro2 =f.codestpro2.value;
		codestpro3 =f.codestpro3.value;	
		codestpro1h =f.codestpro1h.value;
		codestpro2h =f.codestpro2h.value;
		codestpro3h =f.codestpro3h.value;
		if (li_incluir==1)
		{
			 if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h!=""))
			  {
				  f.operacion.value ="COPIAR";
				  f.action="tepuy_spg_d_copiarplandecuentas.php";
				  f.submit();
			  }
			  else
			  {
			   alert("Debe seleccionar la Estructura programática de origen y destino !!!");
			  }
		 }
		else
		{
		  alert("No tiene permiso para realizar esta operación !!!");
		}	
					
	}
	else
	{
		codestpro1 =f.codestpro1.value;
		codestpro2 =f.codestpro2.value;
		codestpro3 =f.codestpro3.value;	
		codestpro4 =f.codestpro4.value;
		codestpro5 =f.codestpro5.value;	
		codestpro1h =f.codestpro1h.value;
		codestpro2h =f.codestpro2h.value;
		codestpro3h =f.codestpro3h.value;
		codestpro4h =f.codestpro4h.value;
		codestpro5h =f.codestpro5h.value;
		if (li_incluir==1)
		{
		  if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!="")&&(codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h!="")&&(codestpro4h!="")&&(codestpro5h!=""))
			{
			  f.operacion.value ="COPIAR";
			  f.action="tepuy_spg_d_copiarplandecuentas.php";
			  f.submit();
			}
			else
			{
			  alert("Debe seleccionar la Estructura programática de origen y destino !!!");
			}
	    }
		else
		{
		  alert("No tiene permiso para realizar esta operación !!!");
		}	
	}	// fin del else
}

function catalogo_estpro1()
{
	   pagina="tepuy_cat_public_estpro1.php?tipo=reporte";
	  // pagina="tepuy_spg_cat_estpro1.php?tipo=reporte";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	li_estmodest  = "<?php print $li_estmodest ?>"; 
	if(li_estmodest==1)
	{
		if(codestpro1!="")
		{
			//pagina="spg/tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
		    pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		
		if(codestpro1=='**')
		{
			pagina="tepuy_cat_estpro2.php?tipo=reporte";
			//pagina="spg/tepuy_cat_estpro2.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
			    //pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	li_estmodest  = "<?php print $li_estmodest ?>"; 
	if(li_estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			//pagina="../../spg/tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			//pagina="../../spg/tepuy_cat_public_estpro.php?tipo=reporte";
		    pagina="tepuy_cat_public_estpro.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				//pagina="../../spg/tepuy_cat_estpro3.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2;
				pagina="tepuy_cat_estpro3.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				//pagina="../../spg/tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
				pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			//pagina="../../spg/tepuy_cat_estpro4.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3;
			pagina="tepuy_cat_estpro4.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			//pagina="../../spg/tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&tipo=reporte";
			pagina="tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&tipo=reporte";

			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			//pagina="../../spg/tepuy_cat_estpro5.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4;
			pagina="tepuy_cat_estpro5.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			/*pagina="../../spg/tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporte";*/
			pagina="tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
function catalogo_estprohas1()
{
	   pagina="../../spg/tepuy_cat_public_estpro1.php?tipo=rephas";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estprohas2()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	li_estmodest  = <?php print $li_estmodest ?>;
	if(li_estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="../../spg/tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
		  alert("Seleccione nivel anterior");
		}
	}
	else
	{
		if(codestpro1=='**')
		{
			pagina="../../spg/tepuy_cat_estpro2.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="../../spg/tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estprohas3()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	li_estmodest  = <?php print $li_estmodest ?>;
	if(li_estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="../../spg/tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="../../spg/tepuy_cat_public_estpro.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="../../spg/tepuy_cat_estpro3.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="../../spg/tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estprohas4()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="../../spg/tepuy_cat_estpro4.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");

		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="../../spg/tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estprohas5()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	codestpro4=f.codestpro4h.value;
	codestpro5=f.codestpro5h.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="../../spg/tepuy_cat_estpro5.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="../../spg/tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}

function ue_cerrar()
{
	f.action="../tepuywindow_blank.php";
	f.submit();
}

function uf_rellenar_cuenta(longitud,li_i)
{
		cadena_ceros="";
		f=document.form1;
		cadena=	eval("f.txtcuentaspg"+li_i+".value");
		lencad=cadena.length;
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena+cadena_ceros;
		eval("document.form1.txtcuentaspg"+li_i+".value="+cadena);
}

</script>
</html>
