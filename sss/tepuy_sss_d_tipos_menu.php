<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Tipos de Menu del Sistema TEPUY</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../cxp/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración-> Tipos de Menu</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="30" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../cxp/js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("tepuy_sss_c_usuarios.php");
require_once("../shared/class_folder/class_funciones_db.php"); 
require_once("../shared/class_folder/class_mensajes.php"); 


$io_conect       = new tepuy_include();//Instanciando la tepuy_Include.
$conn            = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase tepuy_include.
$io_sql          = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg          = new class_mensajes();
$io_sss 	 = new tepuy_sss_c_usuarios($conn);//Instanciando la Clase tepuy Definiciones.
$io_dsclas       = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion      = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb    = new class_funciones_db($conn);
$lb_existe       = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	//$ls_sistema="SSS";
	//$ls_ventanas="tepuy_sss_d_tipos_menu.php";
	$ls_sistema="SPG";
	$ls_ventanas="tepuy_spg_d_uniadm.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
   }
else
	{
	  $ls_operacion="NUEVO";
	}

if (array_key_exists("txtcodigo",$_POST))
   {
     $ls_codigo=$_POST["txtcodigo"];
   }
else  
   {
     $ls_codigo="";
   }
if (array_key_exists("txtdenominacion",$_POST))
   {
     $ls_denominacion = $_POST["txtdenominacion"];
   }
else
   {
     $ls_denominacion = "";
   }
if (array_key_exists("hidestatus",$_POST))
   {
     $ls_estatus=$_POST["hidestatus"];
   }
else
   {
	  $ls_estatus="NUEVO";	  
   }
if (array_key_exists("txtmenu",$_POST))
   {
     $ls_menu = $_POST["txtmenu"];
   }
else
	{
	  $ls_menu = "";	  
	}
if (array_key_exists("chkunidad",$_POST))
   {
     $ls_tipouac = $_POST["chkunidad"];
   }
else
	{
	  $ls_tipouac = "D";	  
	}		
$ls_codemp  = $ls_empresa;
$lb_empresa = true;
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operacion  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//print "operacion".$ls_operacion;
if(($ls_operacion=="NUEVO")||($ls_operacion=="nuevo"))
{
	//print "nuevo";
	$lb_empresa="";
	$ls_codigo  = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sss_tipousuario','codusu');
	if(empty($ls_codigo))
	{
		$io_msg->message($io_funciondb->is_msg_error);
	}
	$ls_estatus="NUEVO";
}  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Operaciones de Insercion y Actualizacion            //////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   { 
	 //print "operacion:".$ls_operacion;
	 $lb_existe = $io_sss->uf_sss_select_tipo_menu($ls_codemp,$ls_codigo);
	 
	 if ($lb_existe)
        { 
		if($ls_estatus=="NUEVO")
		{
		//print "estatus:".$ls_estatus;
		$io_msg->message("Este Código de Tipo de Menu ya existe !!!");  
		$lb_valido=false;
		}
		elseif($ls_estatus=="GRABADO")
		{
	        $lb_valido=$io_sss->uf_sss_update_tipo_menu($ls_codemp,$ls_codigo,$ls_denominacion,$ls_menu,$la_seguridad);
			if ($lb_valido)
			{
	    	        $io_sql->commit();
			//$io_msg->message("Registro Actualizado !!!");
			$lb_empresa="";
	                $ls_codigo       = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sss_tipousuario','codusu');
			$ls_denominacion = "";
			$ls_menu  = "";
			$ls_tipouac      = "";
			$ls_estatus      = "NUEVO";
			}
			else
		        {
		        $io_sql->rollback();
			$io_msg->message("Error en Actualización !!!");
			}
		}  
	}
	else  
	{ 
		$lb_valido = $io_sss->uf_sss_insert_tipo_menu($ls_codemp,$ls_codigo,$ls_denominacion,$ls_menu,$la_seguridad);
		if ($lb_valido)
		{
		$io_sql->commit();
		//$io_msg->message("Registro Incluido !!!");
		$lb_empresa="";
		$ls_codigo = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sss_tipousuario','codusu');
		$ls_denominacion = "";
		$ls_menu  = "";
		$ls_tipouac      = "";
	  	$ls_estatus      = "NUEVO";
		}
		else
		{
   		$io_sql->rollback();
		$io_msg->message("Error en Inclusión !!!");
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////          Fin de las Operaciones de Insercion y Actualizacion      ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operaciones de Eliminar ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ELIMINAR")
   {
	$lb_existe = $io_sss->uf_sss_select_tipo_menu($ls_codemp,$ls_codigo);
	if ($lb_existe)
	{
		$lb_valido=$io_sss->uf_sss_delete_tipo_menu($ls_codemp,$ls_codigo,$ls_denominacion,$la_seguridad);
		if ($lb_valido)
		{
		$io_sql->commit();
		$io_msg->message("Registro Eliminado !!!");
		$lb_empresa      = "";
		$ls_codigo       = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sss_tipousuario','codusu');
 		$ls_estatus      = "NUEVO";
		$ls_denominacion = "";
		$ls_menu  = "";
		$ls_tipouac      = "";
		}
		else
		{
		$io_sql->rollback();
		$io_msg->message($io_sss->is_msg_error);
		}	 
	}
	else
	{
	$io_msg->message("Este Registro No Existe !!!");
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Operaciones de Eliminar en el Data Store////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
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
	print(" location.href='tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="527" height="229" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="566" height="153"><div align="center">
        <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
          <tr>
            <td height="22" colspan="2" class="titulo-ventana">Tipos de Menu </td>
          </tr>
          <tr>
            <td height="22" >&nbsp;</td>
            <td height="22" ><span class="style1">
              <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              <input name="hidmaestro" type="hidden" id="hidmaestro" value="1">
            </span></td>
          </tr>
          <tr>
            <td width="106" height="22" ><div align="right">C&oacute;digo</div></td>
            <td width="362" height="22" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codigo ?>" size="2" maxlength="2" readonly onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:center " onBlur="javascript:rellenar_cadena(this.value,2);">
                <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">            </td>
          </tr>
          <tr>
            <td height="22" align="right">Denominaci&oacute;n </td>
            <td height="22"><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion ?>" type="text" size="65" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',.-/&@'+'´áéíóú');"></td>
          </tr>
          <tr>
            <td height="22"><div align="right"><span class="style1"><b>Nombre del Menu (.js)</b></span></div></td>
            <td height="22"><span class="style1">
              <label>
              <input name="txtmenu" type="text" id="txtmenu" value="<?php print $ls_menu ?>" size="65" maxlength="60">
              </label>
            </span></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
  <div align="center">  </div>
</form>
</body>

<script language="JavaScript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	{	
	
	//f.operacion.value="ue_guardar";
	f.txtdenominacion.value = "";
	f.txtdenominacion.focus(true);
	f.txtmenu.value  = "";
	//f.chkunidad.checked     = true;
	f.operacion.value       = "NUEVO";
	//f.hidestatus.value	= "NUEVO";
	f.action                = "tepuy_sss_d_tipos_menu.php";
	//alert(f.operacion.value);
	f.submit();
	}
	else
	{
	alert("No tiene permiso para realizar esta operación");
	}
}


function ue_guardar()
{
var resul="";
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
//alert(lb_status);
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (campo_requerido(txtcodigo,"El código de Tipo de Menu debe estar lleno !!!")==false)
		       {
		         txtcodigo.focus();
			   }
			else
			   {
			     resul=rellenar_cadena(document.form1.txtcodigo.value,2);	   
				 if (campo_requerido(txtdenominacion,"El Nombre del Tipo de Menu debe estar lleno !!!")==false)
					{
					  txtdenominacion.focus();
					}
				 else
					{
					  f=document.form1;
					  f.operacion.value="ue_guardar";
					  f.action="tepuy_sss_d_tipos_menu.php";
					  f.submit();
					}
			   }
		  }			
    }
  else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}
					
function ue_eliminar()
{
var borrar="";

f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f=document.form1;
			   f.operacion.value="ELIMINAR";
			   f.action="tepuy_sss_d_tipos_menu.php";
			   f.submit();
		     }
		  else
		     { 
			   f=document.form1;
			   f.action="tepuy_sss_d_tipos_menu.php";
		 	   alert("Eliminación Cancelada !!!");
		     }
	    }
   }
 else
   {
     alert("No tiene permiso para realizar esta operacion");
   }
}
		
function campo_requerido(field,mensaje)
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
		
function rellenar_cadena(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	total=longitud-lencad;
	for (i=1;i<=total;i++)
		{
		  cadena_ceros=cadena_ceros+"0";
		}
	cadena=cadena_ceros+cadena;
	document.form1.txtcodigo.value=cadena;
}
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
         f.operacion.value="";			
	     pagina="tepuy_sss_cat_tipos_menu.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
		 alert("No tiene permiso para realizar esta operación");
	   }
	//alert(f.operacion.value);
}
</script>
</html>
