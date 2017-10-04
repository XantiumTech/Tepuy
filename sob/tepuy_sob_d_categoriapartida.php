<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_conexion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Categor&iacute;as de Partidas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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

<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<!--?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?-->
<?Php
	require_once("class_folder/tepuy_sob_c_categoriapartida.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/tepuy_sob_class_mensajes.php");
	$io_mensaje=new tepuy_sob_class_mensajes();
	$io_categoriapartida=new tepuy_sob_c_categoriapartida();
	$is_msg=new class_mensajes();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="tepuy_sob_d_categoriapartida.php";

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
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codpar=$_POST["txtcodpar"];		
		$ls_nompar=$_POST["txtnompar"];				
	}
	else
	{
		$ls_operacion="";
		$ls_codpar="";
		$ls_nompar="";		
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/tepuy_include.php");		
		$io_include=new tepuy_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_empresa=$_SESSION["la_empresa"];
		$ls_codpar=$io_funcdb->uf_generar_codigo(false,0,"sob_categoriapartida","codcatpar",4);
		$ls_nompar="";	
		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_categoriapartida->uf_guardar_categoriapartida($ls_codpar,$ls_nompar,$la_seguridad);		
		if ($lb_valido===true)
		{
			$ls_codpar="";
			$ls_nompar="";
			$is_msg->message($io_categoriapartida->is_msg_error);
		}
		else
		{
			if($lb_valido===0)
			{
				$ls_codpar="";
				$ls_nompar="";				
			}				
			else
			{			
				$is_msg->message($io_categoriapartida->is_msg_error);				
			}
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_categoriapartida->uf_eliminar_categoriapartida($ls_codpar,$la_seguridad);			
		if ($lb_valido===true)
		{
			$ls_codpar="";
			$ls_nompar="";
			$io_mensaje->eliminar();
		}		
		else
		{
			if(!$lb_valido===0)
				$io_mensaje->error_eliminar();
		}
	}
	
?>

    <table width="518" height="161" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
<?
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
      <tr>
        <td width="516" height="159"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Categor&iacute;as de Partidas </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcodpar" type="text" style="text-align:center " id="txtcodpar" value="<? print  $ls_codpar?>" size="4" maxlength="4" readonly="true">
                  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				  <input type="hidden" name="hidstatus" id="hidstatus"></td>
            </tr>
            <tr align="left">
              <td height="49" align="right"><span class="style2">Descripci&oacute;n</span></td>
              <td><textarea name="txtnompar" cols="50" rows="2" id="txtnompar"  onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" onKeyPress="return(validaCajas(this,'x',event,254))"><? print $ls_nompar?></textarea></td>
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

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
  {	
	  f.operacion.value="ue_nuevo";
	  f.txtcodpar.value="";
	  f.txtnompar.value=""; 
	  f.txtnompar.focus(true);
	  f.action="tepuy_sob_d_categoriapartida.php";
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
var resul="";
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidstatus.value;
if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
{   

 with (form1)
 {
	   if (ue_valida_null(txtcodpar,"Código")==false)
		{
		  txtcodpar.focus();
		}
	 	else
		{ 
		  resul=rellenar_cad(document.form1.txtcodpar.value,3);
		  if (ue_valida_null(txtnompar,"Descripción")==false)
			 {
			   txtnompar.focus();
			 }
			 else
			 {					 	
				   f=document.form1;
				   f.operacion.value="ue_guardar";
				   f.action="tepuy_sob_d_categoriapartida.php";
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
			
function ue_eliminar()
{
var borrar="";

f=document.form1;
li_eliminar=f.eliminar.value;
if(li_eliminar==1)
{	
	if (f.txtcodpar.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar !!!");
	   }
		else
		{
			borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   { 
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="tepuy_sob_d_categoriapartida.php";
				 f.submit();
			   }
			else
			   { 
				 f=document.form1;
				 f.action="tepuy_sob_d_categoriapartida.php";
				 alert("Eliminación Cancelada !!!");
				 f.txtcodpar.value="";
				 f.txtnompar.value="";			
				 f.submit();
			   }
		}	   
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function EvaluateText(cadena, obj)
{ 
opc = false; 
	
	if (cadena == "%d")  
	  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
	  opc = true; 
	if (cadena == "%f"){ 
	 if (event.keyCode > 47 && event.keyCode < 58) 
	  opc = true; 
	 if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
	  if (event.keyCode == 46) 
	   opc = true; 
	} 
	 if (cadena == "%s") // toma numero y letras
	 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
	  opc = true; 
	 if (cadena == "%c") // toma numero y punto
	 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
	  opc = true; 
	if(opc == false) 
	 event.returnValue = false; 
   } 

function rellenar_cad(cadena,longitud)
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
		document.form1.txtcodpar.value=cadena;
}

function ue_buscar()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="tepuy_cat_categoriapartida.php";
	popupWin(pagina,"catalogo",600,200);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=200,resizable=yes,location=no,top=0,left=0");
}

function ue_cargarcategoriapartida(codigo,descripcion)
{
	f=document.form1;
	f.txtcodpar.value=codigo;
	f.txtnompar.value=descripcion;	
	f.hidstatus.value="C";
}
</script>
</html>
