<?php
session_start();
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
<title>Definici&oacute;n de Unidad Ejecutora</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_imprimir();"><img src="../../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div>    </td>
  </tr>
</table>
<?php
	require_once("class_folder/tepuy_spg_c_unidad.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funcion = new class_funciones();
	$dat=$_SESSION["la_empresa"];
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad = new tepuy_c_seguridad();
	
	$arre         = $_SESSION["la_empresa"];
	$ls_empresa   = $arre["codemp"];
	$ls_codemp    = $ls_empresa;
	$li_estmodest = $arre["estmodest"];
	if ($li_estmodest=='1')
	   {
	     $li_maxlenght_1 = '20';
	     $li_maxlenght_2 = '6';
	     $li_maxlenght_3 = '3';
	     $li_size        = '25';
	     $ls_ancho       = '50';
	     $ls_type        = 'hidden'; 
   	     $ls_style       = 'style=visibility:hidden';
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
	     $ls_ancho       = '70';
	     $ls_type        = 'text';
	     $ls_style       = '';
	   }

	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema     = "SPG";
	$ls_ventanas    = "tepuy_spg_d_unidad.php";
	$la_security[1] = $ls_empresa;
	$la_security[2] = $ls_sistema;
	$la_security[3] = $ls_logusr;
	$la_security[4] = $ls_ventanas;

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
	//Inclusión de la clase de seguridad.
	$nvo_estprog=new tepuy_spg_c_unidad($la_security);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("../../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();
	$siginc=new tepuy_include();
	$con=$siginc->uf_conectar();
	$ds=null;

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion  = $_POST["operacion"];
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_codestpro2 = $_POST["txtcodestpro2"];
		$ls_codestpro3 = $_POST["txtcodestpro3"];
		$ls_denestpro1 = $_POST["txtdenestpro1"];
		$ls_denestpro2 = $_POST["txtdenestpro2"];
		$ls_denestpro3 = $_POST["txtdenestpro3"];
		if ($li_estmodest=='2')
		   {
		     $ls_codestpro4 = $_POST["txtcodestpro4"];
		     $ls_codestpro5 = $_POST["txtcodestpro5"];
		     $ls_denestpro4 = $_POST["txtdenestpro4"];
		     $ls_denestpro5 = $_POST["txtdenestpro5"];
		   }
		else
		   {
		     $ls_codestpro4 = '00';
		     $ls_codestpro5 = '00';
		     $ls_denestpro4 = '';
		     $ls_denestpro5 = '';
		   }
		$ls_codunieje = $_POST["txtcodunieje"];
		$ls_denunieje = $_POST["txtdenunieje"];
		$ls_status    = $_POST["status"];		
		if(array_key_exists("estreq",$_POST))
		{
		$ls_estreq=$_POST["estreq"];
		}
		else
		{
		$ls_estreq=0;
		}
		$ls_coduniadm = $_POST["txtcoduniadm"];
	    $ls_denuniadm = "";
    }
	else
	{
		$ls_operacion  = "NUEVO";
		$ls_codestpro1 = "";
		$ls_codestpro2 = "";
		$ls_codestpro3 = "";
		$ls_codestpro4 = "";
		$ls_codestpro5 = "";
	    $ls_denestpro1 = "";
		$ls_denestpro2 = "";
		$ls_denestpro3 = "";
		$ls_denestpro4 = "";
		$ls_denestpro5 = "";
		$ls_codunieje  = "";
		$ls_denunieje  = "";
		$ls_estreq     = "";
		$ls_status     = "N";
		$ls_coduniadm  = "";
		$disabled      = "";
		$ls_denuniadm  = "";		
	}
	if($ls_operacion=="GUARDAR")
	{
		if(($ls_codunieje!="")&&($ls_denunieje!="")&&($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!=""))
		{
			$ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
  	        $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
	        $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
 			$lb_valido     = $nvo_estprog->uf_guardar_unidad_adm($ls_codemp,$ls_codunieje,$ls_denunieje,$ls_estreq,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_status,$ls_coduniadm);
			if($lb_valido)
			{
				$msg->message($nvo_estprog->is_msg_error);
				$ls_operacion="NUEVO";
				$disabled="readonly";				
			}
			else
			{
				$msg->message($nvo_estprog->is_msg_error);
				$disabled="";
			}
		}
		else
		{
			$msg->message("Debe completar todos los campos");
			$disabled="";
		}
	}
	elseif($ls_operacion=="BUSCAR")
	{
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_codestpro2 = $_POST["hicodest2"];
		$ls_codestpro3 = $_POST["hicodest3"];
		$ls_codestpro4 = $_POST["hicodest4"];
		$ls_codestpro5 = $_POST["hicodest5"];
		$disabled   = "readonly";
	}
	elseif($ls_operacion=="ELIMINAR")
	{
		if(($ls_codunieje!="")&&($ls_denunieje!="")&&($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!=""))
		{
			$lb_valido=$nvo_estprog->uf_delete_unidad_adm($ls_codunieje,$ls_denunieje,$ls_estreq,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_status);
			if($lb_valido)
			{
				$msg->message($nvo_estprog->is_msg_error);
				$ls_codestpro1 = "";
				$ls_codestpro2 = "";
				$ls_codestpro3 = "";
				$ls_codestpro4 = "";
				$ls_codestpro5 = "";
				$ls_codunieje  = "";
				$ls_denunieje  = "";
				$ls_estreq     = "";
				$disabled      = "";
				$ls_operacion  = "NUEVO";
			}
			else
			{
				$msg->message($nvo_estprog->is_msg_error);
				$disabled="";
			}
		}
		else
		{
			$msg->message("Debe completar todos los campos");
		}
	
	}
	if($ls_operacion=="NUEVO")
	{
		$disabled="";
		require_once("../../shared/class_folder/class_funciones_db.php");
		$fundb = new class_funciones_db($con);
		$ls_codunieje = $fundb->uf_generar_codigo(true,$dat["codemp"],"spg_unidadadministrativa","coduniadm");
		if($ls_codunieje=="")
		{
			$msg->message($fundb->is_msg_error);
		}
		$ls_coduniadm  = "";
		$ls_denuniadm  = "";
		$ls_denunieje  = "";
		$ls_estreq     = 0;
		$ls_status     = "N";
		$ls_codestpro1 = "";$ls_denestpro1 = "";
		$ls_codestpro2 = "";$ls_denestpro2 = "";
		$ls_codestpro3 = "";$ls_denestpro3 = "";		
	    $ls_codestpro4 = "";$ls_denestpro4 = "";
		$ls_codestpro5 = "";$ls_denestpro5 = "";
	}
	else
	{
	$disabled="";
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="665" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="663" height="221" valign="top"><form name="form1" method="post" action="">
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
          <table width="595" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2"> Unidad Ejecutora</td>
              </tr>
              <tr >
                <td height="18">&nbsp;</td>
                <td><input name="hidmaestro" type="hidden" id="hidmaestro" value="0"></td>
              </tr>
              <tr>
                <td width="143" height="22" style="text-align:right">Codigo</td>
                <td width="450" height="22"><div align="left" >
					  <input name="txtcodunieje" type="text" id="txtcodunieje" style="text-align:center " value="<?php print $ls_codunieje ?>" size="12" maxlength="10" onBlur="javascript:rellenar_cad(this.value,10,'cod')" <?php print $disabled ?>  onKeyPress="return keyRestrict(event,'1234567890');" >
</div></td>
              </tr>
              <tr >
                <td height="22" style="text-align:right">Denominaci&oacute;n</td>
                <td height="22"><div align="left">
                  <input name="txtdenunieje" type="text" id="txtdenunieje" style="text-align:left" value="<?php print $ls_denunieje ?>" size="85" maxlength="85"  >
                </div></td>
              </tr>
              <tr>
                <td height="22" style="text-align:right">Emite Requisici&oacute;n</td>
                <td height="22" style="text-align:left">
                  <?php
				  if($ls_estreq==1)
				  {
				  ?>
				  	<input name="estreq" type="checkbox" id="estreq" value="1" checked>
				  <?php
				  }
				  else
				  {
				  ?>
				 	 <input name="estreq" type="checkbox" id="estreq" value="1">
				  <?php
				  }
				  ?>
                </td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                <?php print $dat["nomestpro1"];     ?>				
                </div></td>
                <td height="22">	
				  <div align="left">
				    <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" readonly>
				    <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
                  <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" style="text-align:left" value="<?php print $ls_denestpro1 ?>" size="<?php print $ls_ancho ?>" readonly>			
                </div>              </td>
              </tr>
            <tr>
                <td height="22">
				<div align="right">
			  <?php print $dat["nomestpro2"] ; ?>				</div>				</td>
                <td height="22">
				 <div align="left" >
				   <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlenght_2 ?>" readonly>
				   <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
                   <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" style="text-align:left" value="<?php print $ls_denestpro2 ?>" size="<?php print $ls_ancho ?>" readonly>
              </div>				</td>
            </tr>
            <tr>
              <td height="22">
                <div align="right">
			  <?php print $dat["nomestpro3"] ; ?>				</div>			  </td>
              <td height="22">
			    <div align="left">
                <input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center" value="<?php print $ls_codestpro3 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlenght_3 ?>" readonly>
                <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
                <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" style="text-align:left" value="<?php print $ls_denestpro3 ?>" size="<?php print $ls_ancho ?>" readonly>
              </div></td>
            </tr>
			<?
            if ($li_estmodest=='2')
			   { ?>
				 <tr>
				  <td height="22"><div align="right"><?php print $dat["nomestpro4"]?></div></td>
				  <td height="22" align="left"><label>
					<input name="txtcodestpro4" type="text" id="txtcodestpro4" style="text-align:center" value="<?php print $ls_codestpro4 ?>" size="<?php print $li_size ?>" maxlength="2" readonly>
					<a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
					<input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" style="text-align:left" value="<?php print $ls_denestpro4 ?>" size="<?php print $ls_ancho ?>" readonly>
				  </label></td>
				</tr>
				<tr>
				  <td height="22"><div align="right"><?php print $dat["nomestpro5"]?></div></td>
				  <td height="22" align="left"><label>
					<input name="txtcodestpro5" type="text" id="txtcodestpro5" style="text-align:center" value="<?php print $ls_codestpro5 ?>" size="<?php print $li_size ?>" maxlength="2" readonly>
					<a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
					<input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" style="text-align:left" value="<?php print $ls_denestpro5 ?>" size="<?php print $ls_ancho ?>" readonly>
				  </label></td>
				</tr>			 
			 <?  
			   }
			?>
            <tr>
              <td height="22" style="text-align:right">Unidad Administradora</td>
              <td height="22"><div align="left">
                <input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm ?>" size="22" maxlength="5" >
                <a href="javascript:catalogo_unidad_administradora();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Buscar..." width="15" height="15" border="0"></a> 
                <label>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm ?>" size="50" maxlength="50">
                </label>
              </div></td>
            </tr>
            <tr>
              <td height="18">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
            <p>&nbsp;</p>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status; ?>">
          </p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
f = document.form1;
function cat()
{
	f.txtcuenta.readonly=false;
	f.operacion.value="CAT";
	window.open("tepuy_catdinamic_ctaspu.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="tepuy_spg_d_unidad.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }	 
}

function ue_guardar()
{
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
     f.operacion.value ="GUARDAR";
	 f.action="tepuy_spg_d_unidad.php";
	 f.submit();
   }
else
   {
     alert("No tiene permiso para realizar esta operación");
   }	
}

function ue_eliminar()
{
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
		{ 
	      f.operacion.value ="ELIMINAR";
	      f.action="tepuy_spg_d_unidad.php";
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
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
 	     window.open("tepuy_spg_cat_unidad.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=450,left=50,top=50,location=no,resizable=yes");
       }
    else
	   {
         alert("No tiene permiso para realizar esta operación");
	   }
}

function ue_imprimir()
{
	window.open("../reportes/tepuy_spg_rpp_unidad_ejecutora.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=300,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f.action="../tepuywindow_blank.php";
	f.submit();
}

function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	if(campo=="doc")
	{
		document.form1.txtdocumento.value=cadena;
	}
	if(campo=="cmp")
	{
		document.form1.txtcomprobante.value=cadena;
	}
	if(campo=="cod")
	{
		document.form1.txtcodunieje.value=cadena;
	}
}
	
function catalogo_unidad_administradora()
{
	pagina="tepuy_spg_cat_uniadm.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro1()
{
	pagina="tepuy_spg_cat_estpro1.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
    f.txtcodestpro2.value = "";
	f.txtdenestpro2.value = "";
	f.txtcodestpro3.value = "";
	f.txtdenestpro3.value = "";
    li_estmodest          = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
		 f.txtcodestpro4.value = "";
		 f.txtcodestpro5.value = "";
		 f.txtdenestpro4.value = "";
		 f.txtdenestpro5.value = "";
	   }
}

function catalogo_estpro2()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!=""))
	{
		pagina="tepuy_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Debe seleccionar una Estructura de Nivel 1 !!!");
	}
   	f.txtcodestpro3.value = "";
	f.txtdenestpro3.value = "";
    li_estmodest          = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
		 f.txtcodestpro4.value = "";
		 f.txtcodestpro5.value = "";
		 f.txtdenestpro4.value = "";
		 f.txtdenestpro5.value = "";
	   }
}

function catalogo_estpro3()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	li_estmodest  = "<?php print $li_estmodest ?>";
	if((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3==""))
	{
    	pagina="tepuy_spg_cat_estpro3.php?submit=no&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
	}
	else
	{
	  if (li_estmodest=='2')
	     {
	       if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")) 
		      {
    	        pagina="tepuy_spg_cat_estpro3.php?submit=no&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2;
		        window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
			  }
		   else
		      {
			    alert("Debe seleccionar una Estructura de Nivel 2 !!!");  
			  }
	     }
	  else
	     {
		   pagina="tepuy_cat_public_estpro.php?submit=no";
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	     }
	}
	if (li_estmodest=='2')
	   {
		 f.txtcodestpro4.value = "";
		 f.txtcodestpro5.value = "";
		 f.txtdenestpro4.value = "";
		 f.txtdenestpro5.value = "";
	   }
}

function catalogo_estpro4()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!=""))
	   {
    	 pagina="tepuy_spg_cat_estpro4.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	     li_estmodest = "<?php print $li_estmodest ?>";
		 if (li_estmodest=='2')
		    {
			  f.txtcodestpro5.value = "";
			  f.txtdenestpro5.value = "";
		    }
	   }
    else
	   {
	     alert("Debe seleccionar una Estructura de Nivel 3 !!!");
	   }
}

function catalogo_estpro5()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
    ls_codestpro5 = f.txtcodestpro5.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_denestpro4 = f.txtdenestpro4.value;
	ls_denestpro5 = f.txtdenestpro5.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4!="")&&(ls_denestpro4!="")&&(ls_codestpro5==""))
	   {
    	 pagina="tepuy_spg_cat_estpro5.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	else
	   {
		 pagina="tepuy_cat_public_estpro.php?submit=si";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
}
</script>
</html>
