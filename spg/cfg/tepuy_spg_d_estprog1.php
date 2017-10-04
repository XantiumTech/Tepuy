<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	
	$dat           = $_SESSION["la_empresa"];
	$ls_nomestpro1 = $dat["nomestpro1"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici�n de <?php print $ls_nomestpro1?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699#006699;
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
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuraci�n</td>
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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_imprimir();"><img src="../../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<p>
  <?php
	require_once("class_folder/tepuy_spg_c_estprog1.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_conect  = new tepuy_include();
    $conn       = $io_conect->uf_conectar();
	$io_funcion = new class_funciones(); 
	$io_msg     = new class_mensajes();
	$io_sql     = new class_sql($conn);

	/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();
	
	$arre                     = $_SESSION["la_empresa"];
	$ls_empresa               = $arre["codemp"];
	$ls_codemp                = $ls_empresa;
	$ls_logusr                = $_SESSION["la_logusr"];
	$ls_sistema               = "SPG";
	$ls_ventanas              = "tepuy_spg_d_estprog1.php";
	
	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
    $li_estmodest             = $arre["estmodest"];
    if ($li_estmodest=='1')
	   {
	     $li_maxlength = '20';
	     $li_size      = '25';
	     $ls_ancho     = '65';
	   }
	else
	   {
	     $li_maxlength = '2';
	     $li_size      = '5';
	     $ls_ancho     = '85';
	   }
	
	if (array_key_exists("permisos1",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos1"];
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
	$io_estpro1 = new tepuy_spg_c_estprog1($conn);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if  (array_key_exists("status",$_POST))
	{
  	  $ls_status=$_POST["status"];
	}
else
	{
	  $ls_status="NUEVO";	  
	}	

if ( array_key_exists("operacion",$_POST))
   {
	 $ls_operacion     = $_POST["operacion"];
	 $ls_codestpro1    = $_POST["txtcodestpro1"];
	 $ls_denestpro1    = $_POST["txtdenestpro1"];
	 $ls_clasificacion = $_POST["rbclasificacion"];
	 $disabled         = "";
	 $readonly         = "";
   }
else
   {
	 $ls_operacion="";
	 if (array_key_exists("txtcodestpro1",$_POST))
		{
		  $ls_codestpro1 = $_POST["txtcodestpro1"];
		  $ls_denestpro1 = $_POST["txtdenestpro1"];
		}
	 else
		{
		  $ls_codestpro1 = "";
		  $ls_denestpro1 = "";
		}
	 $ls_clasificacion = "P";
	 $disabled         = "disabled";
	 $readonly         = "";
   }

if ($ls_operacion == "NUEVO")
   {
	 $ls_codestpro1    = "";
	 $ls_denestpro1    = "";
	 $ls_clasificacion = "P";
	 $disabled         = "disabled";
	 $readonly         = "";
	 $ls_status        = "NUEVO";
   }
	
if ($ls_operacion == "GUARDAR")
   {
	 $ls_clasificacion = $_POST["rbclasificacion"];
	 $ls_codestpro1    = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
	 $lb_existe        = $io_estpro1->uf_spg_select_estprog1($ls_codemp,$ls_codestpro1); 
	 if (!$lb_existe)
	    {
          $lb_valido = $io_estpro1->uf_spg_insert_estprog1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$ls_clasificacion,$la_seguridad);		
		  if ($lb_valido)
		     {
			   $io_sql->commit();
			   $io_msg->message("Registro Incluido !!!");
			   $ls_codestpro1 = "";
			   $ls_denestpro1 = "";
	   		   $ls_status = "NUEVO";
			 }
		  else
		     {
			   $io_sql->rollback();
			   $io_msg->message($io_estpro1->is_msg_error);
			 }
		}
	 else
	    {
		  $lb_valido = $io_estpro1->uf_spg_update_estprog1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$ls_clasificacion,$la_seguridad);		
		  if ($lb_valido)
		     {
			   $io_sql->commit();
			   $io_msg->message("Registro Actualizado !!!");
			   $ls_codestpro1 = "";
			   $ls_denestpro1 = "";
	   		   $ls_status     = "NUEVO";
			 }
		  else
		     {
			   $io_sql->rollback();
			   $io_msg->message($io_estpro1->is_msg_error);
			 }
		}
   }	 
	
if ($ls_operacion == "ELIMINAR")
   {
     $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
	 $lb_valido     = $io_estpro1->uf_spg_delete_estpro1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$la_seguridad);
	 if ($lb_valido)
	    {
		  $io_sql->commit();
		  $io_msg->message("Registro Eliminado !!!");
		}
	 else
	    {
		  $io_sql->rollback();
		  $io_msg->message($io_estpro1->is_msg_error);
		}
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
	 $ls_status     = "NUEVO";
 	 $readonly      = "";
   }
	
if ($ls_operacion == "BUSCAR")
   {
     $ls_codestpro1=$_POST["txtcodestpro1"];
	 $ls_denestpro1=$_POST["txtdenestpro1"];
	 $ls_clasificacion=$_POST["rbclasificacion"];
	 $disabled="";
	 $readonly="readonly";
   }
	
	
?>
</p>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="625" height="230" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="622" height="226"><div align="center">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos1 id=permisos1 value='$ls_permisos'>");
	print("<input type=hidden name=leer      id=leer      value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir   id=incluir   value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar   id=cambiar   value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar  id=eliminar  value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir  id=imprimir  value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular    id=anular    value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar  id=ejecutar  value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='../tepuywindow_blank.php'");
	print("</script>");
}
?>
          <table width="565" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr class="titulo-ventana">
              <td height="22" colspan="3"><input name="hidmaestro" type="hidden" id="hidmaestro" value="Y">
              <?php print $ls_nomestpro1?></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td width="463" height="22" colspan="2"><input name="status" type="hidden" id="status" value="<?php print $ls_status ?>"></td>
            </tr>
            <tr class="formato-blanco">
              <td width="101" height="22"><div align="right" >
                  <p>Codigo</p>
              </div></td>
              <td height="22" colspan="2"><div align="left" >
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center " value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength ?>" onBlur="javascript:rellenar_cad(this.value,<?php print $li_maxlength ?>,'cod')" <?php print $readonly ?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');">
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Denominaci&oacute;n</div></td>
              <td height="22" colspan="2"><div align="left">
                  <input name="txtdenestpro1" type="text" id="txtdenestpro1" style="text-align:left" value="<?php print $ls_denestpro1 ?>" size="82" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz '+',-.;*&?�!�+()[]{}%@/'+'������');">
              </div></td>
            </tr>
            <?php
			
			if ($li_estmodest=='2')
			   { ?>
				 <tr class="formato-blanco">
				  <td height="22"><div align="right"></div></td>
				  <td height="22" colspan="2" align="left"><p>
				  <?php
					if($ls_clasificacion=='P')
					{
						$rb_pro="checked";
						$rb_accion="";
					}
					else
					{
						$rb_pro="";
						$rb_accion="checked";
					}
				?>
                  <label>
                  <input name="rbclasificacion" type="radio" value="P" <?php print $rb_pro;?>>
                    Proyecto</label>
                  <label>
                  <input type="radio" name="rbclasificacion" value="A"  <?php print $rb_accion;?>>
                    Acciones Centralizadas </label>
                  <br>
              </p></td>
            </tr>			   
              <?php
			  }
			  else
			  {
			  ?>			    
                  <input name="rbclasificacion" type="hidden" value="" >
                  <input type="hidden" name="rbclasificacion" value="">                    
			  <?php
			  
			  }
			  
			  ?>
			<tr class="formato-blanco">
              <td height="22" colspan="3">
                  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td align="center"><a href="javascript: uf_estprog2();"><?php print "Ir a ".$dat["nomestpro2"]?></a></td>
                    </tr>
                </table></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right" ></div></td>
              <td height="22" colspan="2"><div align="left" >
                  <input name="operacion" type="hidden" id="operacion">
              </div></td>
            </tr>
          </table>
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<div align="center"></div>
</body>
<script language="javascript">
f=document.form1;
function ue_nuevo()
{
  li_incluir = f.incluir.value;
  if (li_incluir==1)
	 {	
		f.operacion.value ="NUEVO";
		f.action="tepuy_spg_d_estprog1.php";
		f.submit();
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operaci�n");
	 }
}

function ue_guardar()
{
li_incluir    = f.incluir.value;
li_cambiar    = f.cambiar.value;
lb_status     = f.status.value;
ls_codestpro1 = f.txtcodestpro1.value;
ls_denestpro1 = f.txtdenestpro1.value;

if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
	 if ((ls_codestpro1!="") && (ls_denestpro1!=""))
	    {
		  f.operacion.value ="GUARDAR";
		  f.action="tepuy_spg_d_estprog1.php";
		  f.submit();
		}
     else
	    {
		  alert("Debe completar todos los campos !!!");
		}
  }
 else
  {
    alert("No tiene permiso para realizar esta operaci�n");
  }  
}

function ue_eliminar()
{
f           = document.form1;
li_eliminar = f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("� Esta seguro de eliminar este registro ?");
	 if (borrar==true)
	    { 
          f.operacion.value ="ELIMINAR";
          f.action="tepuy_spg_d_estprog1.php";
          f.submit();
  	    }
     else
	    {
		  alert("Eliminaci�n Cancelada !!!");
		}
   }
  else
   {
     alert("No tiene permiso para realizar esta operaci�n");
   }
}

function ue_buscar()
{
    f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     window.open("tepuy_spg_cat_estpro1.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
       }
	 else
	   {
 	     alert("No tiene permiso para realizar esta operaci�n");
	   }
}

function ue_imprimir()
{
	window.open("../reportes/tepuy_spg_rpp_distribucion_categorias.php?cmbnivel=s1","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=300,left=50,top=50,location=no,resizable=yes");
}
 
function ue_cerrar()
{
	f=document.form1;
	f.action="../tepuywindow_blank.php";
	f.submit();
}

function uf_estprog2()
{
	f             = document.form1;
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!=""))
	   {
		 f.action="tepuy_spg_d_estprog2.php";
		 f.submit();
 	   }
	else
	   {
		 alert("Debe seleccionar algun Sector para continuar");
	   }
}

//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		if (cadena!="")
		{
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
			document.form1.txtcodestpro1.value=cadena;
		}
	    }
	}

</script>
</html>
