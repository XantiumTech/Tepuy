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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","tepuy_sno_d_constantes.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codcons,$ls_nomcon,$ls_unicon,$ld_topcon,$ld_valcon,$ls_aplica,$ls_personal,$ls_operacion,$ls_existe;
		global $ls_desnom,$ls_desper,$ls_reiniciable,$ls_constante,$io_fun_nomina,$li_calculada,$ls_especial;

		$ls_codcons="";
		$ls_nomcon="";
		$ls_unicon="Bs.";
		$ld_topcon="0";
		$ld_valcon="0";
		$ls_aplica="disabled";
		$ls_personal="disabled";
		$ls_reiniciable="";
		$ls_especial="";
		$ls_constante="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		require_once("tepuy_sno_c_calcularnomina.php");
		$io_calcularnomina=new tepuy_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/06/2006								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codcons, $ls_nomcon, $ls_unicon, $ld_topcon, $ld_valcon, $ls_reicon,$ls_conespseg,$io_fun_nomina;

		$ls_codcons=$_POST["txtcodcons"];
		$ls_nomcon=$_POST["txtnomcon"];
		$ls_unicon=$_POST["txtunicon"];
		$ld_topcon=$_POST["txttopcon"];
		$ld_valcon=$_POST["txtvalcon"];
		$ls_reicon=$io_fun_nomina->uf_obtenervalor("checkreicon","0");
		$ls_conespseg=$io_fun_nomina->uf_obtenervalor("chkconespseg","0");
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
<title>Definici&oacute;n de Constantes</title>
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
-->
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("tepuy_sno_c_constantes.php");
	$io_constante=new tepuy_sno_c_constantes();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_constante->uf_guardar($ls_existe,$ls_codcons,$ls_nomcon,$ls_unicon,$ld_topcon,$ld_valcon,$ls_reicon,$ls_conespseg,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				if($ls_reicon=="1")
				{
					$ls_reiniciable="checked";
				}
				if($ls_conespseg)
				{
					$ls_especial="checked";
				}
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_constante->uf_delete_constante($ls_codcons,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				if($ls_reicon=="1")
				{
					$ls_reiniciable="checked";
				}
				if($ls_conespseg)
				{
					$ls_especial="checked";
				}
			}
			break;

		case "BUSCAR":
			$ls_codcons=$_GET["txtcodcons"];
			$lb_valido=$io_constante->uf_load_constante($ls_existe,$ls_codcons,$ls_nomcon,$ls_unicon,$ld_topcon,$ld_valcon,$ls_reicon,$ls_conespseg);
			$ls_constante=" readonly";
			if($ls_reicon=="1")
			{
				$ls_reiniciable="checked";
			}
			if($ls_conespseg)
			{
				$ls_especial="checked";
			}
			$ls_personal="";
			$ls_aplica="";
			break;

		case "APLICAR":
			uf_load_variables();
			$lb_valido=$io_constante->uf_aplicar_valor($ls_codcons,$ld_valcon,$la_seguridad);
			$ls_constante=" readonly";
			if($ls_reicon=="1")
			{
				$ls_reiniciable="checked";
			}
			if($ls_conespseg)
			{
				$ls_especial="checked";
			}
			$ls_personal="";
			$ls_aplica="";
			break;
	}
	$io_constante->uf_destructor();
	unset($io_constante);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?PHP print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?PHP print $ls_desper;?></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	  </table>
	</td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu_nomina.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_importar();"><img src="../shared/imagebank/tools20/txt.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
   <!-- <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_importar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/txt.png" alt="Salir" width="20" height="20" border="0"></a></div></td>-->
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<div align="center">
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td valign="top">
		  <p>&nbsp;</p>
		  <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="2"><div align="center">Definici&oacute;n de Constantes</div></td>
              </tr>
              <tr >
                <td height="22">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="122" height="22"><div align="right">Codigo</div></td>
                <td width="456">
                  <div align="left">
                    <input name="txtcodcons" type="text" id="txtcodcons" value="<?php print $ls_codcons ?>" size="15" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);" <?php print $ls_constante;?>>
                    </div></td></tr>
              <tr >
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td>
                  <div align="left">
                    <input name="txtnomcon" type="text" id="txtnomcon" value="<?php print $ls_nomcon ?>" size="70" maxlength="30" onKeyUp="javascript: ue_validarcomillas(this);">
                    </div></td></tr>
              <tr>
                <td height="22"><div align="right">Unidad</div></td>
                <td>
                  <div align="left">
                    <input name="txtunicon" type="text" id="txtunicon"  value="<?php print $ls_unicon?>" size="22" maxlength="19" onKeyUp="javascript: ue_validarcomillas(this);">
                    </div></td></tr>
            <tr>
              <td height="22"><div align="right">Reinicializable a Cero </div></td>
              <td>
                
                <div align="left">
                  <input name="checkreicon" type="checkbox" class="sin-borde" id="checkreicon" value="1" "<?php print $ls_reiniciable; ?>">
                </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Constante Especial </div></td>
              <td><label>
                <input name="chkconespseg" type="checkbox" class="sin-borde" id="chkconespseg" value="1" "<?php print $ls_especial; ?>">
              (esta opci&oacute;n aplica a aquellas constantes que se pueden visualizar por usuario)</label></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Tope</div></td>
              <td>
                <div align="left">
                  <input name="txttopcon" type="text" id="txttopcon"  value="<?php print $ld_topcon ?>" size="25" maxlength="25" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">
                  </div></td></tr>
            <tr>
              <td height="22"><div align="right">Valor</div></td>
              <td>
                <div align="left">
                  <input name="txtvalcon" type="text" id="txtvalcon" value="<?php print  $ld_valcon ?>" size="25" maxlength="25" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">               	
                  <input name="btnaplicar" type="button" class="boton" id="btnaplicar" onClick="javascript: uf_aplicar();" value="Aplicar" <?php print  $ls_aplica;?>>
                 </div></td></tr>
            <tr>
              <td height="22"><div align="right"></div></td>
              <td><div align="left">
                <input name="operacion" type="hidden" id="operacion">
                <input name="existe" type="hidden" id="existe" value="<?php  print $ls_existe ?>">
				<input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td><div align="left">
                <input name="btnpersonal" type="button" class="boton" id="btnpersonal"  onClick="javascript: uf_personal();" value="Personal" <?php print  $ls_personal;?>>
              </div></td>
            </tr>
          </table>
		  <p>&nbsp;</p></td>
      </tr>
  </table>
  </form>
</div>
</body>
<script language="javascript">

function ue_importar()
{
	f=document.form1;
	ls_codcons=f.txtcodcons.value;
	//alert(ls_codcons);
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		codcons=ue_validarvacio(f.txtcodcons.value);
		nomcon=ue_validarvacio(f.txtnomcon.value);
		if((codcons!="")&&(nomcon!=""))
		{
			window.open("manuales/formato_archivo_domicializacion_ descuento.pdf","formulas","menubar=no,toolbar=no,scrollbars=yes,width=750,height=600,left=50,top=50,location=no,resizable=yes");
			window.open("tepuy_sno_cat_importarconstante.php?codcons="+ls_codcons+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=200,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("La constante debe estar previamente definida...!");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_nuevo()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.operacion.value ="NUEVO";
			f.existe.value="FALSE";		
			f.action="tepuy_sno_d_constantes.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}
function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_existe=f.existe.value;
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codcons=ue_validarvacio(f.txtcodcons.value);
			nomcon=ue_validarvacio(f.txtnomcon.value);
			unicon=ue_validarvacio(f.txtunicon.value);
			topcon=ue_validarvacio(f.txttopcon.value);
			valcon=ue_validarvacio(f.txtvalcon.value);
			if((codcons!="")&&(nomcon!="")&&(topcon!="")&&(unicon!="")&&(valcon!=""))
			{
				topcon=f.txttopcon.value;
				valcon=f.txtvalcon.value;
				topcon=topcon.replace(",",".");
				valcon=valcon.replace(",",".");
				if(topcon!=0)
				{
					if(parseFloat(valcon)>parseFloat(topcon))
					{
					  alert(" El Valor no es Valido. Por Favor Revise el valor no puede ser mayor al tope ");
					}
					else
					{
						f.operacion.value ="GUARDAR";
						f.action="tepuy_sno_d_constantes.php";
						f.submit();
					}	
				}
				else
				{
					f.operacion.value ="GUARDAR";
					f.action="tepuy_sno_d_constantes.php";
					f.submit();
				}
			}
			else
			{
				alert("debe llenar todos los campos.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if(f.existe.value=="TRUE")
			{
				resp=confirm(" Desea eliminar el registro actual ?");
				if(resp==true)
				{
					f=document.form1;
					f.operacion.value ="ELIMINAR";
					f.action="tepuy_sno_d_constantes.php";
					f.submit();
				}	
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_sno_cat_constantes.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="tepuywindow_blank_nomina.php";
	f.submit();
}

function uf_personal()
{
	f=document.form1;
	cod=f.txtcodcons.value;
	ls_tope=f.txttopcon.value;
	ls_nombre=f.txtnomcon.value;  
	pagina="tepuy_sno_d_constpersonal.php?txtcodcons="+cod+"&txttopcon="+ls_tope+"&txtnomcon="+ls_nombre;
	location.href=pagina;
}

function uf_aplicar()
{
   f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
	   if (f.txtvalcon.value!="")
	   {
			topcon=f.txttopcon.value;
			valcon=f.txtvalcon.value;
			topcon=topcon.replace(",",".");
			valcon=valcon.replace(",",".");
			if(topcon!=0)
			{
				if(parseFloat(valcon)>parseFloat(topcon))
				{
					alert(" El Valor no es Valido. Por Favor Revise el valor no puede ser mayor al tope ");
				}
				else
				{
					f=document.form1;
					f.operacion.value ="APLICAR";
					f.action="tepuy_sno_d_constantes.php";
					f.submit();
				}	
			}
			else
			{
				f=document.form1;
				f.operacion.value ="APLICAR";
				f.action="tepuy_sno_d_constantes.php";
				f.submit();
			}	
	   }
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}
</script>
</html>
