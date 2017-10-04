<?php
session_start();
//ini_set('display_errors', 1);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_facturacion.php");
$io_fun_facturacion=new class_funciones_facturacion();
$io_fun_facturacion->uf_load_seguridad("SFA","tepuy_sfa_d_registro_producto.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codpro,$ls_denpro,$ls_codtippro,$ls_feccrepro,$ls_obspro,$ls_exipro,$ls_eximinpro,$ls_eximaxpro,$ls_codunimed;
   		global $ls_preproa,$ls_preprob,$ls_preproc,$ls_preprod,$ls_fecvenpro,$ls_spg_cuenta,$ls_spg_denominacion,$li_pesart,$li_altart,$li_ancart,$li_proart;
		global $ls_fotart,$li_exiinipro,$li_ultcosart,$li_cosproart,$disabled,$ls_dentippro,$ls_denunimed;
   		global $ls_codcatsig,$ls_dencatsig,$li_estnum,$ls_sccuenta,$ls_densccuenta,$li_reoart;
		global $ls_fotowidth,$ls_fotoheight,$ls_foto,$lb_abrircargos,$ls_codmil,$ls_denmil,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docart;

		$ls_codpro="";
		$ls_denpro="";
		$ls_codtippro="";
		$ls_codunimed="";
		$ls_dentippro="";
		$ls_denunimed="";
		$ls_feccrepro=date("d/m/Y");
		$ls_obspro="";
		$ls_exipro="";
		$ls_eximinpro="";
		$ls_eximaxpro="";
		$li_reopro="";
		$li_codunimed="";
		$ls_preproa="";
		$ls_preprob="";
		$ls_preproc="";
		$ls_preprod="";
		$ls_fecvenpro="";
		$ls_spg_cuenta="";
		$ls_spg_denominacion="";
		$ls_sccuenta="";
		$ls_densccuenta="";
		$li_pespro="";
		$li_altpro="";
		$li_ancpro="";
		$li_propro="";
		$ls_fotpro="";
		$li_exiinipro="";
		$li_ultcospro="";
		$li_cospropro="";
		$ls_codcatsig="";
		$ls_dencatsig="";
		$li_estnum="";
		$lb_abrircargos=false;
		$disabled="disabled";
		$ls_fotowidth="121";
		$ls_fotoheight="94";
		$ls_foto="blanco.jpg";
		$ls_codmil="";
		$ls_denmil="";
		$ls_serpro="";
		$ls_fabpro="";
		$ls_ubipro="";
		$ls_docpro="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n del Producto </title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="css/sfa.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Facturación </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>	
  </tr>-->
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
  <tr>
    <td height="42" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=     new tepuy_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones($con);
	require_once("tepuy_sfa_c_producto.php");
	$io_sfa= new tepuy_sfa_c_producto();
	require_once("class_funciones_facturacion.php");
	$io_funciones_facturacion= new class_funciones_facturacion();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_fotowidth="121";
	$ls_fotoheight="94";
	$ls_foto ="blanco.jpg";
	$ls_operacion=$io_funciones_facturacion->uf_obteneroperacion();
	uf_limpiarvariables();
	$li_catalogo=$io_sfa->uf_sfa_select_catalogo($li_estnum,$li_estcmp);	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			//print 'producto:'.' '.$li_estnum;
			if($li_catalogo)
			{
				print("<script language=JavaScript>");
				print "window.open('tepuy_sfa_cat_sigecof.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";			
				print("</script>");
			}
			if($li_estnum==0)
			{
				$ls_emp="";
				$ls_codemp="";
				$ls_tabla="sfa_producto";
				$ls_columna="codpro";
			
				$ls_codpro=$io_fun->uf_generar_codigo_siv($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			}
			$ls_readonly="";
		break;
		
		case "GUARDAR":
			$ls_valido= false;
			if($li_catalogo)
			{
				$ls_readonly="readonly";
			}
			else
			{
				$ls_readonly="";
			}
			
			$ls_codpro=$io_funciones_facturacion->uf_obtenervalor("txtcodpro","");
			$ls_denpro=$io_funciones_facturacion->uf_obtenervalor("txtdenpro","");
			$ls_codtippro=$io_funciones_facturacion->uf_obtenervalor("txtcodtippro","");
			$ls_codunimed=$io_funciones_facturacion->uf_obtenervalor("txtcodunimed","");
			$ls_dentippro=$io_funciones_facturacion->uf_obtenervalor("txtdentippro","");
			$ls_denunimed=$io_funciones_facturacion->uf_obtenervalor("txtdenunimed","");
			$ls_feccrepro=$io_funciones_facturacion->uf_obtenervalor("txtfeccrepro","");
			$ls_obspro=$io_funciones_facturacion->uf_obtenervalor("txtobspro","");
			$ls_exipro=$io_funciones_facturacion->uf_obtenervalor("txtexipro","");
			$li_exiinipro=$io_funciones_facturacion->uf_obtenervalor("txtexiinipro","");
			$ls_eximinpro=$io_funciones_facturacion->uf_obtenervalor("txteximinpro","");
			$ls_eximaxpro=$io_funciones_facturacion->uf_obtenervalor("txteximaxpro","");
			$ls_preproa=$io_funciones_facturacion->uf_obtenervalor("txtpreproa","");
			$ls_preprob=$io_funciones_facturacion->uf_obtenervalor("txtpreprob","");
			$ls_preproc=$io_funciones_facturacion->uf_obtenervalor("txtpreproc","");
			$ls_preprod=$io_funciones_facturacion->uf_obtenervalor("txtpreprod","");
			$ls_fecvenpro=$io_funciones_facturacion->uf_obtenervalor("txtfecvenpro","");
			$ls_codcatsig=$io_funciones_facturacion->uf_obtenervalor("txtcodcatsig","");
			$ls_dencatsig=$io_funciones_facturacion->uf_obtenervalor("txtdencatsig","");
			$ls_spg_cuenta=$io_funciones_facturacion->uf_obtenervalor("txtspg_cuenta","");
			$ls_sccuenta=$io_funciones_facturacion->uf_obtenervalor("txtsccuenta","");
			$ls_densccuenta=$io_funciones_facturacion->uf_obtenervalor("txtspg_cuenta","");
			$li_pespro=$io_funciones_facturacion->uf_obtenervalor("txtpespro","");
			$li_altpro=$io_funciones_facturacion->uf_obtenervalor("txtaltpro","");
			$li_ancpro=$io_funciones_facturacion->uf_obtenervalor("txtancpro","");
			$li_propro=$io_funciones_facturacion->uf_obtenervalor("txtpropro","");
			$ls_status=$io_funciones_facturacion->uf_obtenervalor("hidstatusc","");
			$li_ultcospro=$io_funciones_facturacion->uf_obtenervalor("txtultcospro","");
			$li_cospropro=$io_funciones_facturacion->uf_obtenervalor("txtcospropro","");
			//$ls_nomfot=$HTTP_POST_FILES['txtfotpro']['name']; 
			$ls_serpro=$io_funciones_facturacion->uf_obtenervalor("txtserpro","");
			$ls_fabpro=$io_funciones_facturacion->uf_obtenervalor("txtfabpro","");
			$ls_ubipro=$io_funciones_facturacion->uf_obtenervalor("txtubipro","");
			$ls_docpro=$io_funciones_facturacion->uf_obtenervalor("txtdocpro","");
			/*if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codpro.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}*/
			/*$ls_tipfot=$HTTP_POST_FILES['txtfotart']['type']; 
			$ls_tamfot=$HTTP_POST_FILES['txtfotart']['size']; 
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotart']['tmp_name'];
			$ls_codmil=$io_funciones_facturacion->uf_obtenervalor("txtcodmil","");
			*/
			//if(($ls_codpro=="")||($ls_feccrepro=="")||($ls_codtippro=="")||($ls_codunimed=="")||($ls_denpro=="")||($li_exiinipro=="")||($ls_eximinpro=="")||($ls_eximaxpro=="")||($ls_spg_cuenta==""))
			if(($ls_codpro=="")||($ls_feccrepro=="")||($ls_codtippro=="")||($ls_codunimed=="")||($ls_denpro=="")||($ls_spg_cuenta==""))
			{
				$io_msg->message("Debe completar todos los campos requeridos");
				$disabled="disabled";
			}
			else
			{
				$lb_valido=$io_sfa->uf_sfa_select_cuentaspg($ls_codemp,$ls_spg_cuenta);
				if($lb_valido)
				{				
					$ls_exipro=    str_replace(".","",$ls_exipro);
					$ls_exipro=    str_replace(",",".",$ls_exipro);
					$li_exiinipro= str_replace(".","",$li_exiinipro);
					$li_exiinipro= str_replace(",",".",$li_exiinipro);
					$ls_eximinpro= str_replace(".","",$ls_eximinpro);
					$ls_eximinpro= str_replace(",",".",$ls_eximinpro);
					$ls_eximaxpro= str_replace(".","",$ls_eximaxpro);
					$ls_eximaxpro= str_replace(",",".",$ls_eximaxpro);
					$ls_preproa=   str_replace(".","",$ls_preproa);
					$ls_preproa=   str_replace(",",".",$ls_preproa);
					$ls_preprob=   str_replace(".","",$ls_preprob);
					$ls_preprob=   str_replace(",",".",$ls_preprob);
					$ls_preproc=   str_replace(".","",$ls_preproc);
					$ls_preproc=   str_replace(",",".",$ls_preproc);
					$ls_preprod=   str_replace(".","",$ls_preprod);
					$ls_preprod=   str_replace(",",".",$ls_preprod);
					$li_pespro=    str_replace(".","",$li_pespro);
					$li_pespro=    str_replace(",",".",$li_pespro);
					$li_altpro=    str_replace(".","",$li_altpro);
					$li_altpro=    str_replace(",",".",$li_altpro);
					$li_ancpro=    str_replace(".","",$li_ancpro);
					$li_ancpro=    str_replace(",",".",$li_ancpro);
					$li_propro=    str_replace(".","",$li_propro);
					$li_propro=    str_replace(",",".",$li_propro);
					$ls_feccrepro=$io_func->uf_convertirdatetobd($ls_feccrepro);
					$ls_fecvenpro=$io_func->uf_convertirdatetobd($ls_fecvenpro);
					if ($ls_status=="C")
					{
						$lb_valido=$io_sfa->uf_sfa_update_producto($ls_codemp, $ls_codpro, $ls_denpro, $ls_codtippro, $ls_codunimed,
																   $ls_feccrepro, $ls_obspro, $ls_exipro, $li_exiiniart, $ls_eximinpro,
																   $ls_eximaxpro, $ls_preproa, $ls_preprob, $ls_preproc, $ls_preprod, 
																   $ls_fecvenpro, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																   $li_proart, $ls_nomfot, $ls_codcatsig, $ls_sccuenta, $la_seguridad,
																   $ls_codmil,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docpro);
						if($lb_valido)
						{
							$lb_valido=$io_sfa->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
						}
		
						if($lb_valido)
						{
							$io_msg->message("El producto fue actualizado.");
							$disabled="";
							uf_limpiarvariables();
							$ls_readonly="readonly";
						}	
						else
						{
							$io_msg->message("El producto no pudo ser actualizado.");
							$disabled="disabled";
							uf_limpiarvariables();
							$ls_readonly="readonly";
						}
					}
					else
					{
						$lb_encontrado=$io_sfa->uf_sfa_select_producto($ls_codemp,$ls_codpro);
						if ($lb_encontrado)
						{
							$io_msg->message("El producto ya existe."); 
							$disabled="disabled";
	
						}
						else
						{
						//	print "precio a ".$ls_preproa." precio b ".$ls_preprob." precio c ".$ls_preproc." precio d: ".$ls_preprod;
							$lb_valido=$io_sfa->uf_sfa_insert_producto($ls_codemp, $ls_codpro, $ls_denpro, $ls_codtippro, $ls_codunimed,
																	   $ls_feccrepro, $ls_obspro, $ls_exipro, $li_exiiniart, $ls_eximinpro,
																	   $ls_eximaxpro, $ls_preproa, $ls_preprob, $ls_preproc, $ls_preprod, 
																	   $ls_fecvenpro, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																	   $li_proart, $ls_nomfot, $ls_codcatsig, $ls_sccuenta, $la_seguridad,
																	   $ls_codmil,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docpro);
	
							if($lb_valido)
							{
								$lb_valido=$io_sfa->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
							}
							if ($lb_valido)
							{
								$io_msg->message("El producto fue registrado.");
								$lb_abrircargos=true;
								//uf_limpiarvariables();
								$ls_readonly="readonly";
								$disabled="";
							}
							else
							{
								$io_msg->message("No se pudo incluir el producto.");
								$disabled ="disabled";
								//uf_limpiarvariables();
								$ls_readonly="read";
							}
						
						}
					}
				}
				else
				{
					$io_msg->message("Debe incluir una cuenta de ingreso valida");
					$disabled="disabled";
					$ls_readonly="readonly";
				}
			}
			$ls_feccrepro=$io_func->uf_convertirfecmostrar($ls_feccrepro);
			$ls_fecvenpro=$io_func->uf_convertirfecmostrar($ls_fecvenpro);

		break;

		case "ELIMINAR":
			$ls_codpro=    $io_funciones_facturacion->uf_obtenervalor("txtcodpro","");
		
			$lb_valido=$io_sfa->uf_sfa_delete_producto($ls_codemp,$ls_codpro, $la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("El producto fue eliminado.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			else
			{
				$io_msg->message("No se pudo eliminar el producto.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table width="683" height="647" border="0" class="formato-blanco">
      <tr>
        <td height="15" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="583" colspan="3"><div align="left">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_facturacion->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_facturacion);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="620" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Definici&oacute;n de Producto </td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4"> <div align="center">Los Campos en (*) son necesarios para la Incluir el Producto </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4"><input name="hidstatusc" type="hidden" id="hidstatusc">
                  <input name="hidstatus" type="hidden" id="hidstatus">
				  <input name="txtcatalogo" type="hidden" id="txtcatalogo" value="<?php print $li_catalogo?>">
                </td>
              </tr>
			  <tr class="formato-blanco">
			  <?php
			  	if($li_estnum)
				{?>
                <td height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td height="22"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="5" maxlength="5" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890');"  <?php if($li_estcmp==1){?> onBlur="ue_rellenarcampo(this,'20');"<?php } ?>></td>
  			  <?php
			  	}
				else
				{
			  ?>
                <td height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td height="22"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="5" maxlength="5" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz-');"  <?php if($li_estcmp==1){?> onBlur="ue_rellenarcampo(this,'20');"<?php } ?>></td>
			  <?php
			  	}
			  ?>
          <!--      <td width="104" rowspan="6"><div align="center"><img name="foto" id="foto" src="fotosarticulos/<?php print $ls_foto?>" width="121" height="94" class="formato-blanco"></div></td>
                <td>&nbsp;</td> -->
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Denominaci&oacute;n</div></td>
                <td height="22"><input name="txtdenpro" type="text" id="txtdenpro" value="<?php print $ls_denpro?>" size="45" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Tipo de Producto </div></td>
                <td height="22"><input name="txtcodtippro" type="text" id="txtcodtippro" value="<?php print $ls_codtippro?>" size="6" maxlength="4" readonly>
                <a href="javascript: ue_catatippro();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdentippro" type="text" class="sin-borde" id="txtdentippro" value="<?php print $ls_dentippro?>" size="40" readonly>
                <input name="txtobstippro" type="hidden" id="txtobstippro"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="21"><div align="right"> (*)Unidad de Medida</div></td>
                <td height="21"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="6" maxlength="4" readonly>
                  <a href="javascript: ue_cataunimed();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdenunimed" type="text" class="sin-borde" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="30" readonly>
                <input name="txtunidad" type="hidden" id="txtunidad">
                <input name="txtobsunimed" type="hidden" id="txtobsunimed"></td>
                <td height="21">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Fecha de Creaci&oacute;n </div></td>
                <td height="22"><input name="txtfeccrepro" type="text" id="txtfeccrepro" value="<?php print $ls_feccrepro?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="26"><div align="right">
                    <p>Observaciones</p>
                </div></td>
                <td colspan="3" rowspan="2"><textarea name="txtobspro" cols="45" rows="3" id="txtobspro" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obspro?></textarea></td>
              </tr>
              <tr>
                <td height="27">&nbsp;</td>
              </tr>
			  <?php
			  	if($li_catalogo==1)
				{?>
             <!-- <tr>
                <td height="22" align="right">(*)SIGECOF</td> -->
                <td height="22" colspan="3"><label>
                  <input name="txtcodcatsig" type="text" id="txtcodcatsig" style="text-align:center" value="<?php print $ls_codcatsig?>" size="25" readonly>
            <!--    <a href="javascript: ue_sigecof();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> -->
                  <input name="txtdencatsig" type="hidden" class="sin-borde" id="txtdencatsig" value="<?php print $ls_dencatsig?>" size="50" readonly>				  
                </label></td>
              </tr>
			  <?php
			  	}
			  ?>
              <tr>
                <td height="22"><div align="right"> (*)Partida de Ingreso </div></td>
                <td height="22" colspan="3"><input name="txtspg_cuenta" type="text" id="txtspg_cuenta" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_spg_cuenta?>" size="25" maxlength="25" readonly style="text-align:center ">

			  <?php
				if($li_catalogo!=1)
				{
				?>

             <!--       <a href="javascript: ue_cataspg();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td> -->
                <a href="javascript: ue_cataspg();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtspgdenominacion" type="text" class="sin-borde" id="txtspgdenominacion" value="<?php print $ls_spg_denominacion?>" size="40" readonly>
			  <?php
			  	}
			  ?>
              </tr>
             <!-- <tr>
                <td height="22"><div align="right">Catalogo Milco </div></td> -->
                <td height="22" colspan="3"><input name="txtcodmil" type="hidden" id="txtcodmil" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_codmil ?>" size="25" maxlength="25" readonly style="text-align:center ">
             <!--   <a href="javascript: ue_catalogo_milco();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> -->
                <input name="txtdenmil" type="hidden" class="sin-borde" id="txtdenmil" value="<?php print $ls_denmil?>" size="50" readonly>                </td>
               </tr>
             <tr>
                <td height="22"><div align="right"> Cuenta Contable</div></td> 
                <td height="22" colspan="3"><input name="txtsccuenta" type="text" id="txtsccuenta" value="<?php print $ls_sccuenta?>" size="25" style="text-align:center" readonly>
                 <a href="javascript: ue_catascg();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
                <input name="txtdensccuenta" type="text" class="sin-borde" id="txtdensccuenta"  value="<?php print $ls_densccuenta?>" size="40" readonly></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Existencia Actual </div></td>
                <td height="22" colspan="3"><input name="txtexipro" type="text" id="txtexipro" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print number_format($ls_exipro,2,',','.');?>" size="12" readonly style="text-align:right "></td>
              </tr>
           <!--   <tr>
                <td height="22"><div align="right"> (*)Existencia Inicial </div></td>
                <td height="22" colspan="3"><input name="txtexiinipro" type="text" id="txtexiinipro" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print number_format($li_exiiniart,2,',','.');?>" size="12" style="text-align:right "></td>
              </tr>-->
              <tr>
                <td height="22"><div align="right"> (*)Existencia M&iacute;nima </div></td>
                <td height="22" colspan="3"><input name="txteximinpro" type="text" id="txteximinpro" value="<?php print number_format($ls_eximinpro,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Existencia M&aacute;xima</div></td>
                <td height="22" colspan="3"><input name="txteximaxpro" type="text" id="txteximaxpro" value="<?php print number_format($ls_eximaxpro,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
             <!-- <tr>
                <td height="22"><div align="right">(*)Punto de Reorden </div></td> -->
                <td height="22" colspan="3"><input name="txtreopro" type="hidden" id="txtreopro" value="<?php print number_format($li_reoart,2,',','.');?>" size="12"  onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right"></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Precio A </div></td>
                <td height="22" colspan="3"><input name="txtpreproa" type="text" id="txtpreproa" value="<?php print number_format($ls_preproa,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Precio B </div></td>
                <td height="22" colspan="3"><input name="txtpreprob" type="text" id="txtpreprob" value="<?php print number_format($ls_preprob,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                    <p>Precio C</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpreproc" type="text" id="txtpreproc" value="<?php print number_format($ls_preproc,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Precio D </div></td>
                <td height="22" colspan="3"><input name="txtpreprod" type="text" id="txtpreprod" value="<?php print number_format($ls_preprod,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
     <!--         <tr>
                <td height="22"><div align="right">Serial</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtserpro" type="text" id="txtserpro" value="<?php print $ls_serart; ?>" size="30" maxlength="25"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Fabricante</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtfabpro" type="text" id="txtfabpro" value="<?php print $ls_fabart; ?>" size="50" maxlength="100"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ubicaci&oacute;n</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtubipro" type="text" id="txtubipro" value="<?php print $ls_ubiart; ?>" size="15" maxlength="10"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Documento</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtdocpro" type="text" id="txtdocpro" value="<?php print $ls_docart; ?>" size="25" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr> -->
              <tr>
                <td height="22"><div align="right">Fecha de Vencimiento </div></td>
                <td height="22" colspan="3"><input name="txtfecvenpro" type="text" id="txtfecvenpro"  value="<?php print $ls_fecvenpro?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true" style="text-align:center "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
<!--                  <p>Peso</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpespro" type="text" id="txtpespro" value="<?php print number_format($li_pesart,2,',','.');?>" size="12"onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                  Kg.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Altura</div></td>
                <td height="22" colspan="3"><input name="txtaltpro" type="text" id="txtaltpro" value="<?php print number_format($li_altart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                  mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ancho</div></td>
                <td height="22" colspan="3"><input name="txtancpro" type="text" id="txtancpro" value="<?php print number_format($li_ancart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Profundidad</div></td>
                <td height="22" colspan="3"><input name="txtpropro" type="text" id="txtpropro" value="<?php print number_format($li_proart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right"><p>Foto</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtfotpro" id="txtfotpro" type="file"></td>
              </tr> 
              <tr>
                <td height="22"><div align="right">
                    <p>Ultimo Costo</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtultcospro" type="text" id="txtultcospro"  value="<?php print number_format($li_ultcosart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Costo Promedio</div></td>
                <td height="22" colspan="3"><input name="txtcospropro" type="text" id="txtcospropro"  value="<?php print number_format($li_cosproart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>-->
            </table>
            <div align="center"> </div>
        </div></td>
      </tr>
     <tr>
        <td width="259" height="39">
          <div align="center">
            <input name="operacion" type="hidden" id="operacion4">
 <!--            <input name="btnregistrar" type="button" class="boton" id="btnregistrar" value="Registrar Componentes" onClick="javascript: ue_abrircomponentes(this);" <?php print $disabled?>>
        </div></td>
        <td width="200"><div >
          <input name="btncargos" type="button" class="boton" id="btncargos" value="Agregar I.V.A." onClick="javascript: ue_abrircargos(this);" <?php print $disabled?>>
        </div></td>
        <td width="175" align="center"><input name="btnregact" type="button" class="boton" id="btnregact" value="Registar Activo Fijo"  onClick="javascript: ue_abriractivo(this);"></td>
      </tr> -->
    </table>
  </form>
  <?php
  	if($lb_abrircargos)
	{
		print "<script language=JavaScript>";
		print "f=document.form1;";
		print "codpro=f.txtcodpro.value;";
		print "denpro=f.txtdenpro.value;";
		print "window.open('tepuy_sfa_d_cargos.php?codpro='+codart+'&denpro='+denart+'','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no');";
		print "</script>";
	}
  ?>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_catatippro()
{
	window.open("tepuy_catdinamic_tipoproducto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cataunimed()
{
	f=document.form1;
	ls_status=f.hidstatusc.value;
	if(ls_status!="C")
	{
		window.open("tepuy_catdinamic_unidadmedida.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		window.open("tepuy_catdinamic_unidadmedida.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

function ue_cataspg()
{
	window.open("tepuy_sfa_cat_ctasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catascg()
{
	window.open("tepuy_sfa_cat_ctasscg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_sigecof()
{
	window.open("tepuy_sfa_cat_sigecof.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catalogo_milco()
{
	window.open("tepuy_sfa_cat_milco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("tepuy_sfa_cat_producto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		f.action="tepuy_sfa_d_registro_producto.php";
		f.submit();
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
	li_eximinpro=f.txteximinpro.value;
	li_eximaxpro=f.txteximaxpro.value;
	li_eximinpro=li_eximinpro.replace(".","");
	li_eximinpro=li_eximinpro.replace(",",".");
	li_eximaxpro=li_eximaxpro.replace(".","");
	li_eximaxpro=li_eximaxpro.replace(",",".");
	lb_status=f.hidstatusc.value;
//alert("aqui");
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		
		/*if(parseFloat(li_eximinpro) <= parseFloat(li_eximaxpro))
		{*/
			f.operacion.value="GUARDAR";
			//alert("aqui");
			f.action="tepuy_sfa_d_registro_producto.php";
			f.submit();
		/*}
		else
		{
			alert("La existencia maxima no puede ser menor que la existencia minima");		
		}*/
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
		if(confirm("¿Seguro desea eliminar el registro?"))
		{
			f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="tepuy_sfa_d_registro_producto.php";
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
	window.location.href="tepuywindow_blank.php";
}

function ue_abrircomponentes()
{
	f=document.form1;
	codpro=ue_validarvacio(f.txtcodpro.value);
	denpro=ue_validarvacio(f.txtdenpro.value);
	if (codart!="")
	{
		window.open("tepuy_sfa_d_componentes.php?codpro="+codart+"&denpro="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar un articulo.");	
	}
}

function ue_abriractivo()
{
	f=document.form1;
	codpro=f.txtcodpro.value;
	denpro=f.txtdenpro.value;	
	li_catalogo=f.txtcatalogo.value;		
	if (li_catalogo==1)
	{ 
	  sigecof=f.txtcodcatsig.value;
	  densigecof=f.txtdencatsig.value;
	  spg_cta=f.txtspg_cuenta.value;
	
	   if (codart!="")
     	{  
		window.open("tepuy_sfa_d_registraractivo.php?codpro="+codart+"&denpro="+denart+"&sigecof="+sigecof+"&densigecof="+densigecof+"&spg_cta="+spg_cta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
    	}
	   else
	  {
	  alert("Debe seleccionar un articulo.");	
	  }
	}
	if (li_catalogo!=1)
	{	
      if (codart!="")
	  {   
	  window.open("tepuy_sfa_d_registraractivo.php?codpro="+codart+"&denpro="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
	  }
	  else
	  {
	  alert("Debe seleccionar un articulo.");	
	  }
	}
}

function ue_abrircargos()
{
	f=document.form1;
	codpro=ue_validarvacio(f.txtcodpro.value);
	denpro=ue_validarvacio(f.txtdenpro.value)
	if (codart!="")
	{
		window.open("tepuy_sfa_d_cargos.php?codpro="+codart+"&denpro="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un articulo.");	
	}
}

function ue_imprimirbarras()
{
	f=document.form1;
	codpro=ue_validarvacio(f.txtcodpro.value);
	window.open("genera_barras.php?codigo="+codart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
}
//--------------------------------------------------------
//	Función que limpia las cajas de texto de las fechas
//--------------------------------------------------------
function ue_limpiar(fecha)
{
	f=document.form1;
	if(fecha=="creacion")
	{
		f.txtfeccrepro.value="";
	}
	else
	{
		if(fecha=="vencimiento")
		{
			f.txtfecvenpro.value="";
		}
	}
	
}

function catalogo_estpro1()
{
	   pagina="tepuy_sfa_cat_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="tepuy_sfa_cat_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="tepuy_sfa_cat_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

//--------------------------------------------------------
//	Función que valida una fecha
//--------------------------------------------------------
function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
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

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/2005"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
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
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
