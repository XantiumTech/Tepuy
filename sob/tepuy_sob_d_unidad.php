<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_conexion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definición de Tipos de Unidades</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Control de Obras</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

  <tr>
    <td height="20" class="cd-menu">
<!--	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script> -->
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
	</td>
  </tr>
  <tr>
    <td height="22" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="tepuy_sob_d_unidad.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder/tepuy_sob_c_unidad.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$is_msg=new class_mensajes();
	$io_unidad=new tepuy_sob_c_unidad();
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_coduni=$_POST["txtcoduni"];
		$ls_codtun=$_POST["txtcodtun"];
		$ls_nomtun=$_POST["txtnomtun"];
		$ls_nomuni=$_POST["txtnomuni"];
		$ls_desuni=$_POST["txtdesuni"];
	}
	else
	{
		$ls_operacion="";
		$ls_coduni="";
		$ls_codtun="";
		$ls_nomtun="";
		$ls_nomuni="";
		$ls_desuni="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/tepuy_include.php");		
		require_once("../shared/class_folder/class_sql.php");
		
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$io_funcdb=new class_funciones_db($con);
		$ls_codemp=$la_datemp["codemp"];
		$ls_coduni=$io_funcdb->uf_generar_codigo(true,$ls_codemp,"sob_unidad","coduni");
		$ls_codtun="";
		$ls_nomtun="";
		$ls_nomuni="";
		$ls_desuni="";
		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_unidad->uf_guardar_unidad($ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$la_seguridad);
		$ls_mensaje=$io_unidad->io_msgc;
		
		if ($lb_valido===true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_coduni="";
			$ls_codtun="";
			$ls_nomtun="";
			$ls_nomuni="";
			$ls_desuni="";
		}else
		{
			if($lb_valido==0)
			{
				$ls_coduni="";
				$ls_codtun="";
				$ls_nomtun="";
				$ls_nomuni="";
				$ls_desuni="";
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_unidad->uf_delete_unidad($ls_coduni,$ls_codtun,$la_seguridad);			
		if ($lb_valido===true)
		{
			$is_msg->message($io_unidad->io_msgc);
			$ls_coduni="";
			$ls_codtun="";
			$ls_nomtun="";
			$ls_nomuni="";
			$ls_desuni="";
		}
		
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

    <table width="518" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="195"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Unidades de Medida </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus">
			  </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcoduni" style="text-align:center " type="text" id="txtcoduni" value="<? print  $ls_coduni?>" size="3" maxlength="3"  readonly="true">
              </td>
            </tr>
			<tr>
              <td width="134" height="22" align="right"><span class="style2">Tipo de Unidad</span></td>
              <td width="334" ><input name="txtcodtun" type="text" style="text-align:center " id="txtcodtun" value="<? print  $ls_codtun?>" size="3" maxlength="3"  readonly="true">
              <a href="javascript:ue_cattipo();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomtun" type="text" id="txtnomtun" value="<? print $ls_nomtun;?>" class="sin-borde" size="40" readonly></td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Nombre</span></td>
              <td><input name="txtnomuni" id="txtnomuni" value="<? print $ls_nomuni?>" type="text" size="30" maxlength="30" onKeyPress="return(validaCajas(this,'x',event))"></td>
            </tr>
            <tr>
              <td height="4" align="right">Descripci&oacute;n</td>
              <td><textarea name="txtdesuni" cols="56" rows="2" id="txtdesuni" value="<? print $ls_desuni?>" onKeyDown="textCounter(this,254);" onKeyUp="textCounter(this,254);" onKeyPress="return(validaCajas(this,'x',event))"><? print $ls_desuni?></textarea></td>
            </tr>
            <tr>
              <td height="8">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{		
		f.operacion.value="ue_nuevo";
		f.txtcoduni.value="";
		f.txtcodtun.value="";
		f.txtnomtun.value="";
		f.txtnomuni.value="";
		f.txtdesuni.value="";
		f.action="tepuy_sob_d_unidad.php";
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
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(f)
		{
			if (ue_valida_null(txtcoduni,"Código")==false)
			{
				txtcoduni.focus();
			}
			else
			{ 
				if (ue_valida_null(txtcodtun,"Tipo de Unidad")==false)
				{
				txtcodtun.focus();
				}
				else
				{
					if (ue_valida_null(txtnomuni,"Nombre")==false)
					{
						txtnomuni.focus();
					}
					else
					{
						f.operacion.value="ue_guardar";
						f.action="tepuy_sob_d_unidad.php";
						f.submit();
					}
				} 
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}					
					
function ue_eliminar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(f)
		{
			if (ue_valida_null(txtcoduni,"Código")==false)
			{
				txtcoduni.focus();
			}
			else
			{
				if (confirm("¿ Esta seguro de eliminar este registro ?"))
				{ 		
					f.operacion.value="ue_eliminar";
					f.action="tepuy_sob_d_unidad.php";
					f.submit();
				}
				else
				{ 
					f=document.form1;
					f.action="tepuy_sob_d_unidad.php";
					alert("Eliminación Cancelada !!!");
					f.txtcoduni.value="";
					f.txtcodtun.value="";
					f.txtnomtun.value="";
					f.txtnomuni.value="";
					f.txtdesuni.value="";
					f.operacion.value="";
					f.submit();
				}
			}	   
		}
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
	if(li_leer==1)
	{
		f.operacion.value="";			
		pagina="tepuy_cat_unidades.php";
		popupWin(pagina,"catalogo",650,300);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/***************************************************************************************************************************/
		function ue_cargarunidades(c,d,e,x,g)
		{
			f=document.form1;
			f.txtcoduni.value=c;
			f.txtcodtun.value=d;
	        f.txtnomtun.value=e;
	        f.txtnomuni.value=x;
         	f.txtdesuni.value=g;
			f.hidstatus.value="C";
			
		}	
		
		function ue_cargartipounidad(h,i,j)
		{
			f=document.form1;
			f.txtcodtun.value=h;
	        f.txtnomtun.value=i;
	    }
/***********************************************************************************************************************************/        
		
		function ue_cattipo()
		{
            f=document.form1;
			if (f.hidstatus.value!="C")
			{
				f.operacion.value="";						
				pagina="tepuy_cat_tipounidades.php";
				popupWin(pagina,"catalogo",520,200);
				//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
			}
			else
			{
				alert("Este campo no puede ser modificado!!!");
			}
		}
/***********************************************************************************************************************************/

</script>
</html>
