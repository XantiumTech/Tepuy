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
	$io_fun_cxp->uf_load_seguridad("CXP","tepuy_cxp_r_cxpresumido.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fun_cxp,$ls_operacion,$ls_codtipsol,$ld_fecregdes,$ld_fecreghas,$ld_fecaprord,$li_totrow;
		
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
		$ls_codtipsol="";
		$ld_fecregdes=date("01/m/Y");
		$ld_fecreghas=date("d/m/Y");
		$ld_fecaprord=date("d/m/Y");
		$li_totrow=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrow,$ls_tipope,$ld_fecaprosol;
		
		$li_totrow = $_POST["totrow"];
		$ls_tipope = $_POST["rdtipooperacion"];
		$ld_fecaprord  =$_POST["txtfecaprord"];
   }
   //--------------------------------------------------------------
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
<title >Relaci&oacute;n de Orden de Pago</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("class_folder/tepuy_cxp_c_aprobacionrecepcion.php");
	$io_cxp=new tepuy_cxp_c_aprobacionrecepcion("../");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();		
	require_once("../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();		
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			uf_load_variables();
			$lb_valido=false;
			for($li_i=0;$li_i<=$li_totrow;$li_i++)
			{
				if (array_key_exists("chkaprobacion".$li_i,$_POST))
				{
					$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor("txtnumrecdoc".$li_i,"");
					$ld_fecregdoc=$io_fun_cxp->uf_obtenervalor("txtfecregdoc".$li_i,"");
					$ls_codpro=$io_fun_cxp->uf_obtenervalor("txtcodpro".$li_i,"");
					$ls_cedben=$io_fun_cxp->uf_obtenervalor("txtcedben".$li_i,"");
					$ls_codtipdoc=$io_fun_cxp->uf_obtenervalor("txtcodtipdoc".$li_i,"");
					switch ($ls_tipope)
					{
						case 0:
							$lb_valido=$io_fecha->uf_comparar_fecha($ld_fecregdoc,$ld_fecaprord);
							if($lb_valido)
							{
								$lb_existe=$io_cxp->uf_validar_estatus_recepcion($ls_numrecdoc,"1",$ls_codpro,$ls_cedben,$ls_codtipdoc);
								if(!$lb_existe)
								{
									$lb_valido=$io_cxp->uf_update_estatus_recepciones($ls_numrecdoc,1,$ls_codpro,$ls_cedben,
																					  $ls_codtipdoc,$ld_fecregdoc,$la_seguridad);
								}
								else
								{
									$io_mensajes->message("La Recepcion de Documentos ".$ls_numrecdoc." ya esta aprobada");
								}
							}
							else
							{
								$io_mensajes->message("La Fecha de Registro de la Solicitud ".$ls_numrecdoc." debe ser menor a la fecha de Aprobacion");
							}							
							break;
		
						case 1:
							$lb_existe=$io_cxp->uf_validar_recepciones($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc);
							if($lb_existe)
							{
								$lb_valido=$io_cxp->uf_update_estatus_recepciones($ls_numrecdoc,0,$ls_codpro,$ls_cedben,
																				  $ls_codtipdoc,$ld_fecregdoc,$la_seguridad);
							}
							else
							{
								$io_mensajes->message("La Recepcion de Documentos ".$ls_numrecdoc." debe estar en Registro");
							}
							break;
					}
				}
			}
			if($lb_valido)
			{
				$io_mensajes->message("El proceso se realizo con Exito");
			}
			else
			{
				$io_mensajes->message("No se pudo realizar el proceso");
			}
			uf_limpiarvariables();
			break;

	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-catclaro">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema" align="left">Orden de Pago </td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema" align="left">&nbsp;</td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_buscar();"></a><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprmir" width="20" height="20" border="0" title="Procesar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="578" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="561" colspan="2" class="titulo-ventana">Relaci&oacute;n de Orden de Pago </td>
  </tr>
</table>
<table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="573"></td>
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
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="33" colspan="3" align="center"><div align="left">
      <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="5"><strong>Fecha de Emisi&oacute;n </strong></td>
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
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><input name="chkexcluir" type="checkbox" class="sin-borde" id="chkexcluir" value="1"> 
      Excluir Proveedores/Beneficiarios con saldo cero</td>
  </tr>
</table>
<p align="center">

<div id="solicitudes" align="center"></div></p>
</form>   
<?php
	$io_cxp->uf_destructor();
	unset($io_cxp);
?>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
	function ue_cerrar()
	{
		location.href = "tepuywindow_blank.php";
	}

	function ue_imprimir()
	{
		f=document.formulario;
		li_imprimir=f.imprimir.value;
		excluir=0;
		if(li_imprimir==1)
		{
			fecemides=f.txtfecemides.value;
			fecemihas=f.txtfecemihas.value;
			if(f.chkexcluir.checked==true)
			{
				excluir=1;
			}
			tiporeporte=f.cmbbsf.value;
			pantalla="reportes/tepuy_cxp_rpp_cxpresumido.php?fecemides="+fecemides+"&fecemihas="+fecemihas+"&excluir="+excluir+"&tiporeporte="+tiporeporte+"";
		//	pantalla="reportes/tepuy_cxp_rpp_solicitudesf2.php?solicitudes="+ls_solicitudes+"";
			window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{alert("No tiene permiso para realizar esta operación");}
	}

</script> 
</html>
