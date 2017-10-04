<?php
session_start();
	//ini_set('display_errors', 1);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codtar,$ls_dentra,$ls_selected,$ls_selectedmar,$ls_selectedaer,$ls_selectedter,$ls_conceptopago;
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows,$ls_codtiptar,$ls_nomina,$ls_spgcuenta,$ls_codestpro;
		
		$ls_codtar="";
		$ls_dentra="";
		$ls_selected="";
		$ls_selectedmar="";
		$ls_selectedaer="";
		$ls_codtiptar="";
		$ls_nomina="";
		$ls_selectedter="";
		$ls_spgcuenta= "";
		$ls_codestpro= "";
		$ls_operacion="NUEVO";
		$ls_conceptopago="";
		
   }

   	//--------------------------------------------------------------
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Tarifas seg&uacute;n Tipo de Servicio M&eacute;dico</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../js/funcion_sme.js"></script>
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
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
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Control de Servicios M&eacute;dicos-> Tarifas</td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
    </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="../js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../../shared/class_folder/tepuy_include.php");
	$in=     new tepuy_include();
	$con= $in->uf_conectar();
	require_once("../../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../../shared/class_folder/grid_param.php");
	$in_grid=new grid_param();
	require_once("tepuy_sme_c_tipo_servicio.php");
	$io_serviciomedico  = new tepuy_sme_c_tipo_servicio($con); // Servicios Médicos

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "SME";
	$ls_ventanas = "tepuy_sme_d_monto_tipo_servicio.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

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
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";
		uf_limpiarvariables();
		//uf_agregarlineablanca($lo_object,1);
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$lb_empresa=true;
			$ls_codtar=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'sme_montoseguntipo','codtar');
			$ls_estatus="NUEVO";
			$ls_denominacion="";
		break;
		
		case "GUARDAR";
		
		$ls_valido= false;
		$ls_readonly="";
		$ls_status=$io_serviciomedico->uf_obtenervalor("hidstatus","");
		$ls_codtar=$io_serviciomedico->uf_obtenervalor("txtcodtar","");
		$ls_codtiptar=$io_serviciomedico->uf_obtenervalor("cmbcodtiptar","");
		$ls_nomina=$io_serviciomedico->uf_obtenervalor("cmbnomina","");
		//print "concepto antes: ".$ls_conceptopago;
		$ls_conceptopago=$io_serviciomedico->uf_obtenervalor("cmbcodtipsol","");
		//print "concepto despues: ".$ls_conceptopago;
		$ls_dentra=$io_serviciomedico->uf_obtenervalor("txtdentra","");
		$li_tartra=$io_serviciomedico->uf_obtenervalor("txttartra","");
		$li_tarfam=$io_serviciomedico->uf_obtenervalor("txttarfam","");
		$ls_spgcuenta=$io_serviciomedico->uf_obtenervalor("txtpresupuestaria","");
 		$ls_codestpro=$io_serviciomedico->uf_obtenervalor("txtcodestpro","");
		
		//print "status: ".$ls_status." cordart: ".$ls_codtar." combo 1: ".$ls_codtiptar." combo 2: ".$ls_nomina." trabajador: ".$li_tartra." Familiar: ".$li_tarfam;
		$li_tartraaux= str_replace(".","",$li_tartra);
		$li_tartraaux= str_replace(",",".",$li_tartraaux);
		$li_tarfamaux= str_replace(".","",$li_tarfam);
		$li_tarfamaux= str_replace(",",".",$li_tarfamaux);
		$ls_selected="selected";
		if( ($ls_codtar=="")||($ls_codtiptar=="00")||($ls_nomina=="00")||($ls_conceptopago=="00")||($ls_spgcuenta=="")||($ls_codestpro=="")||(strlen($li_tartra)<0)||(strlen($li_tarfam)<0))
			{
				$io_msg->message("Debe completar todos los datos");
			}
		else
			{
				if ($ls_status=="C")
				{
					$lb_valido=$io_serviciomedico->uf_sme_update_montotiposervicio($ls_codemp,$ls_codtar,$ls_codtiptar,$ls_nomina,$ls_dentra,$li_tartraaux,$li_tarfamaux,$ls_spgcuenta,$ls_codestpro,$ls_conceptopago,$la_seguridad);
					if($lb_valido)
					{
						$io_msg->message("El Servicio Medico fue actualizado");
						uf_limpiarvariables();
						
					}	
					else
					{
						$io_msg->message("El Servicio Medico no pudo ser actualizado");
					}
				}
				else
				{
					$lb_encontrado=$io_serviciomedico->uf_sme_select_montotiposervicio($ls_codemp,$ls_codtar,$ls_codtiptar,$ls_nomina);
					if ($lb_encontrado)
					{
						$io_msg->message("El Servicio Medico ya se encuentra registrado para esta Nomina"); 
					}
					else
					{
						$lb_valido=$io_serviciomedico->uf_sme_insert_montotiposervicio($ls_codemp,$ls_codtar,$ls_codtiptar,$ls_nomina,$ls_dentra,$li_tartraaux,$li_tarfamaux,$ls_spgcuenta,$ls_codestpro,$ls_conceptopago,$la_seguridad);

						if ($lb_valido)
						{
							$io_msg->message("El Servicio Medico fue registrado.");
							uf_limpiarvariables();
							//$lb_valido=$io_serviciomedico->uf_sme_load_montotiposervicio($ls_codemp,$ls_codtiptar,$li_totrows,$lo_object);
						}
						else
						{
							$io_msg->message("No se pudo registrar el Servicio Medico");
						}
					
					}
				}
				
			}
		break;

		case "ELIMINAR":
			$ls_status=$io_serviciomedico->uf_obtenervalor("hidstatus","");
			$ls_codtar=$io_serviciomedico->uf_obtenervalor("txtcodtar","");
			$ls_codtiptar=$io_serviciomedico->uf_obtenervalor("cmbcodtiptar","");
			$ls_codnom=$io_serviciomedico->uf_obtenervalor("cmbnomina","");
			$ls_status=$io_serviciomedico->uf_obtenervalor("hidstatus","");
			//print "status: ".$ls_status." codtar: ".$ls_codtar." codtiptar: ".$ls_codtiptar." nomina:".$ls_codnom;
			if($ls_status=="C")
			{
				$lb_valido=$io_serviciomedico->uf_sme_delete_montotiposervicio($ls_codemp,$ls_codtar,$ls_codtiptar,$ls_codnom,$la_seguridad);
				if($lb_valido)
				{
					$io_msg->message("El Servicio Medico fue eliminado");
					uf_limpiarvariables();
					$ls_readonly="readonly";
				}	
				else
				{
					$io_msg->message($io_serviciomedico->is_msg_error);
					$io_msg->message("No se pudo eliminar el Servicio Medico");
					uf_limpiarvariables();
				}
			}
		break;
		
		case "CARGARLISTADO":
			uf_limpiarvariables();
			$ls_codtar=$io_serviciomedico->uf_obtenervalor("txtcodtar","");
			$ls_codtiptar=$io_serviciomedico->uf_obtenervalor("cmbcodtiptar","");
			$li_totrows=$io_serviciomedico->uf_obtenervalor("totalfilas","");
			switch ($ls_codtiptar)
			{
				case "01";
					$ls_selectedaer="selected";
				break;
				case "02";
					$ls_selectedmar="selected";
				break;
				case "03";
					$ls_selectedter="selected";
				break;
				case "";
					$ls_selected="selected";
				break;
			}
			if($ls_codtiptar!="")
			{
				$lb_valido=$io_serviciomedico->uf_SME_load_sme($ls_codemp,$ls_codtiptar,$li_totrows,$lo_object);
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="575" height="264" border="0" class="formato-blanco">
    <tr>
      <td width="600" height="258"><div align="left">
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
<table width="514" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="22" colspan="2" class="titulo-ventana">Definici&oacute;n  de Tarifas seg&uacute;n Tipo Servicio M&eacute;dico y N&oacute;mina </td>
  </tr>
  <tr class="formato-blanco">
    <td width="143" height="13">&nbsp;</td>
    <td width="352">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">C&oacute;digo</div></td>
    <td height="22"><div align="left">
        <input name="txtcodtar" type="text" id="txtcodtar" onBlur="javascript: ue_rellenarcampo(this,2);"  onKeyPress="return keyRestrict(event,'1234567890'); " value="<?php print $ls_codtar?>" size="8" maxlength="4" style="text-align:center " read>
        <input name="hidstatus" type="hidden" id="hidstatus">
    </div></td>
  </tr>
         <tr> 
            <td height="22"><div align="right">Concepto de Pago</div></td>
            <td height="22" colspan="3"> 
              <?php $io_serviciomedico->uf_load_conceptopago($ls_conceptopago);?>            </td>
          </tr>
  <tr class="formato-blanco">
         <td width="66" height="22"><div align="right">Tipo de Servicio M&eacute;dico
          </div></td>
          <td width="113"><div align="left">
	<?php $io_serviciomedico->uf_cmb_tiposervicio($ls_codtiptar); //Combo que contiene tipos de Servicios Medicos y retorna la selecciona ?>
          </div></td>
  </tr>
  <tr class="formato-blanco">
         <td width="66" height="22"><div align="right">Tipo de N&oacute;mina
          </div></td>
          <td width="113"><div align="left">
	<?php $io_serviciomedico->uf_cmb_nomina($ls_nomina); //Combo que contiene tipos de nomina y retorna la selecciona ?>
          </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="11">&nbsp;</td>
  </tr>
  <tr>
    <td height="22"><div align="right">Monto para el Trabajador</div></td>
   <td height="22"><input name="txttartra" type="text" id="txttartra" size="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event));"> Bs.</td>

  </tr>
  <tr>
    <td height="22"><div align="right">Monto para el  Familiar</div></td>
   <td height="22"><input name="txttarfam" type="text" id="txttarfam" size="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event));"> Bs.</td>

  </tr>
      <tr>
                <td height="22" align="right">Partida Presupuestaria</td>
                <td height="22"><input name="txtpresupuestaria" type="text" id="txtpresupuestaria" style="text-align:center" value="<?php print $ls_spgcuenta ?>" size="25" maxlength="25" readonly>                </td> </tr>
      <tr>
		<td height="22" align="right">Categoria Programatica</td>
                <td height="22"><input name="txtcodestpro"      type="text" id="txtcodestpro"      style="text-align:center" value="<?php print $ls_codestpro ?>" size="40" maxlength="33" readonly>
                <a href="javascript:cat_presupuesto();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td>
              </tr>
  <tr class="formato-blanco">
    <td height="19"><div align="right">Observaci&oacute;n</div></td>
    <td rowspan="2">
      <textarea name="txtdentra" cols="55" id="txtdentra"></textarea>    </td>
  </tr>

</table>
<input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_cata()
{
	window.open("tepuy_cat_personal.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarlistado()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{	
		f.operacion.value="CARGARLISTADO";
		f.action="tepuy_sme_d_monto_tipo_servicio.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
 	ls_destino="DEFINICION";
	if(li_leer==1)
	{
		window.open("tepuy_sme_cat_montotiposervicio.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="tepuy_sme_d_monto_tipo_servicio.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_actualizar(ls_codtar,ls_dentra)
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{
		f.txtcodtar.value=ls_codtar;
		f.txtdentra.value=ls_dentra;
		f.hidstatus.value="C";
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="tepuy_sme_d_monto_tipo_servicio.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="tepuy_sme_d_monto_tipo_servicio.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cerrar()
{
	window.location.href="../tepuywindow_blank.php";
}
function cat_presupuesto()
{
	pagina="tepuy_cat_ctasspg.php";  
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");
}

</script> 
</html>
