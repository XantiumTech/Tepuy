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
$io_fun_facturacion->uf_load_seguridad("SFA","tepuy_sfa_d_inventario_producto.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codpro,$ls_denpro,$ls_codtippro,$ls_dentippro,$ls_codunimed,$ls_denunimed,$ls_feccrepro,$ls_fecvenpro;
		global $ls_obspro,$ls_exipro,$ls_prepro;

		$ls_codpro="";
		$ls_denpro="";
		$ls_codtippro="";
		$ls_dentippro="";
		$ls_codunimed="";
		$ls_denunimed="";
		$ls_feccrepro=date("d/m/Y");
		$ls_fecvenpro=date("d/m/Y");
		$ls_obspro="";
		$ls_exipro="";
		$ls_prepro="";

   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Actualizaci&oacute;n del Inventario por Producto </title>
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
			$ls_readonly="";
		break;
		
		case "GUARDAR":
			$ls_valido= false;			
			$ls_codpro=$io_funciones_facturacion->uf_obtenervalor("txtcodpro","");
			$ls_denpro=$io_funciones_facturacion->uf_obtenervalor("txtdenpro","");
			$ls_codtippro=$io_funciones_facturacion->uf_obtenervalor("txtcodtippro","");
			$ls_codunimed=$io_funciones_facturacion->uf_obtenervalor("txtcodunimed","");
			$ls_dentippro=$io_funciones_facturacion->uf_obtenervalor("txtdentippro","");
			$ls_denunimed=$io_funciones_facturacion->uf_obtenervalor("txtdenunimed","");
			$ls_feccrepro=$io_funciones_facturacion->uf_obtenervalor("txtfeccrepro","");
			$ls_obspro=$io_funciones_facturacion->uf_obtenervalor("txtobspro","");
			$ls_exipro=$io_funciones_facturacion->uf_obtenervalor("txtexipro","");
			//$ls_prepro=$io_funciones_facturacion->uf_obtenervalor("txtprepro","");
			$ls_fecvenpro=$io_funciones_facturacion->uf_obtenervalor("txtfecvenpro","");
			//print "status".$ls_status;

			if(($ls_codpro=="")||($ls_feccrepro=="")||($ls_exipro=="")||($ls_fecvenpro==""))
			{
				$io_msg->message("Debe completar la informacion requerida");
				$disabled="disabled";
			}
			else
			{
				$antes=$ls_exipro;
				$ls_exipro=    str_replace(".","",$ls_exipro);
				$ls_exipro=    str_replace(",",".",$ls_exipro);
				//$ls_prepro=   str_replace(".","",$ls_prepro);
				//$ls_prepro=   str_replace(",",".",$ls_prepro);
				$ls_feccrepro=$io_func->uf_convertirdatetobd($ls_feccrepro);
				$ls_fecvenpro=$io_func->uf_convertirdatetobd($ls_fecvenpro);
				
				if ($ls_status=="C")
				{
					$existenciaactual=$io_sfa->uf_sfa_existencia_producto($ls_codemp,$ls_codpro,$existenciaactual,$la_seguridad);

					$actualizar=($existenciaactual+$antes);
					$lb_valido=$io_sfa->uf_sfa_update_inv_producto($ls_codemp, $ls_codpro,$existenciaactual,$la_seguridad);

					if($lb_valido)
					{
						$lb_valido=$io_sfa->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
					}
					if($lb_valido)
					{
						$io_msg->message("El Inventario del producto fue actualizado.");
						$disabled="";
						uf_limpiarvariables();
						$ls_readonly="readonly";
					}	
					else
					{
						$io_msg->message("El inventario del producto no pudo ser actualizado.");
						$disabled="disabled";
						uf_limpiarvariables();
						$ls_readonly="readonly";
					}
				}
				else
				{
					$lb_encontrado=$io_sfa->uf_sfa_select_producto_inventario($ls_codemp,$ls_codpro,$ls_feccrepro);
					if ($lb_encontrado)
					{
						$io_msg->message("Este producto ya ingreso al inventario en fecha: ".$io_func->uf_convertirfecmostrar($ls_feccrepro)); 
						$disabled="disabled";
					}
					else
					{
						$existenciaactual=$io_sfa->uf_sfa_existencia_producto($ls_codemp,$ls_codpro,$existenciaactual,$la_seguridad);
						$actualizar=($existenciaactual+$antes);
						$lb_valido=$io_sfa->uf_sfa_insert_producto_inventario($ls_codemp, $ls_codpro, $ls_denpro, $ls_codtippro, $ls_codunimed,$ls_feccrepro, $ls_obspro, $ls_exipro,$ls_fecvenpro, $la_seguridad);
						$lb_valido=$io_sfa->uf_sfa_update_inv_producto($ls_codemp, $ls_codpro,$actualizar,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_sfa->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
						}
						if ($lb_valido)
						{
							$io_msg->message("El Inventario del producto fue registrado.");
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
			$ls_feccrepro=$io_func->uf_convertirfecmostrar($ls_feccrepro);
			$ls_fecvenpro=$io_func->uf_convertirfecmostrar($ls_fecvenpro);

		break;

		case "ELIMINAR":
			$ls_codpro=    $io_funciones_facturacion->uf_obtenervalor("txtcodpro","");
			$ls_fecrecpro=$io_funciones_facturacion->uf_obtenervalor("txtfeccrepro","");
			$ls_exipro=$io_funciones_facturacion->uf_obtenervalor("txtexipro","");
			if(($ls_codpro=="")||($ls_fecrecpro=="")||($ls_exipro==""))
			{
				$io_msg->message("Debe seleccionar el producto ingresado al inventario");
				$disabled="disabled";
			}
			else
			{
				$lb_valido=$io_sfa->uf_sfa_delete_producto_inventario($ls_codemp,$ls_codpro,$ls_fecrecpro,$la_seguridad);
				if($lb_valido)
				{
					$io_msg->message("El ingreso del producto al inventario fue eliminado");
					uf_limpiarvariables();
					$ls_readonly="readonly";
				}	
				else
				{
					$io_msg->message("No se pudo eliminar el ingreso del producto al inventario.");
					uf_limpiarvariables();
					$ls_readonly="readonly";
				}
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table width="683" height="200" border="0" class="formato-blanco">
      <tr>
        <td height="15" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="200" colspan="3"><div align="left">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_facturacion->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_facturacion);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="620" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Actualizacici&oacute;n del Inventario del Producto </td>
              </tr>
           <!--   <tr class="formato-blanco">
                <td height="13" colspan="4"> <div align="center">Los Campos en (*) son necesarios para la Incluir el Producto </div></td>
              </tr> -->
              <tr class="formato-blanco">
                <td height="13" colspan="4"><input name="hidstatusc" type="hidden" id="hidstatusc">
                  <input name="hidstatus" type="hidden" id="hidstatus">
				  <input name="txtcatalogo" type="hidden" id="txtcatalogo" value="<?php print $li_catalogo?>">
                </td>
              </tr>
		<tr class="formato-blanco">
                <td height="22"><div align="right">C&oacute;digo</div></td>
                <td height="22"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="5" maxlength="5" <?php print $ls_readonly?> >
                <a href="javascript: ue_catpro();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denpro?>" size="40" readonly></td>
              </tr>
              <!--<tr>
                <td height="22"><div align="right"> Denominaci&oacute;n</div></td>
                <td height="22"><input name="txtdenpro" type="text" id="txtdenpro" value="<?php print $ls_denpro?>" size="45" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"></td>
                <td height="22">&nbsp;</td>
              </tr>-->
              <tr>
                <td height="22"><div align="right"> Tipo de Producto </div></td>
                <td height="22"><input name="txtcodtippro" type="text" id="txtcodtippro" value="<?php print $ls_codtippro?>" size="6" maxlength="4" readonly>
		 <input name="txtdentippro" type="text" class="sin-borde" id="txtdentippro" value="<?php print $ls_dentippro?>" size="40" readonly>
                <input name="txtobstippro" type="hidden" id="txtobstippro"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="21"><div align="right">Unidad de Medida</div></td>
                <td height="21"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="6" maxlength="4" readonly>
                 <input name="txtdenunimed" type="text" class="sin-borde" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="30" readonly>
                <input name="txtunidad" type="hidden" id="txtunidad">
                <input name="txtobsunimed" type="hidden" id="txtobsunimed"></td>
                <td height="21">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Fecha de Registro </div></td>
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
              <tr>
                <td height="22"><div align="right">Existencia Actual </div></td>
                <td height="22" colspan="3"><input name="txtexipro" type="text" id="txtexipro" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print number_format($ls_exipro,2,',','.');?>" size="12" read style="text-align:right "></td>
              </tr>
             <!-- <tr>
                <td height="22"><div align="right"> (*)Precio Venta </div></td>
                <td height="22" colspan="3"><input name="txtprepro" type="text" id="txtprepro" value="<?php print number_format($ls_prepro,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr> -->
              <tr>
                <td height="22"><div align="right">Fecha de Vencimiento </div></td>
                <td height="22" colspan="3"><input name="txtfecvenpro" type="text" id="txtfecvenpro"  value="<?php print $ls_fecvenpro?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true" style="text-align:center "></td>
              </tr>
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
function ue_catpro()
{
	window.open("tepuy_catdinamic_producto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("tepuy_sfa_cat_producto_inventario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		f.action="tepuy_sfa_d_inventario_producto.php";
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
	li_codpro=f.txtcodpro.value;
	li_exipro=f.txtexipro.value;
	li_exipro=li_exipro.replace(".","");
	li_exipro=li_exipro.replace(",",".");
	lb_status=f.hidstatusc.value;
//alert("aqui");
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		
		f.operacion.value="GUARDAR";
		f.action="tepuy_sfa_d_inventario_producto.php";
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
		if(confirm("¿Seguro desea eliminar el registro?"))
		{
			f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="tepuy_sfa_d_inventario_producto.php";
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
