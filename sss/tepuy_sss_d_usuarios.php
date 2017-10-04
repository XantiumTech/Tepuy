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
require_once("class_funciones_seguridad.php");
$io_fun_activo=new class_funciones_seguridad();
$io_fun_activo->uf_load_seguridad("SSS","tepuywindow_sss_grupos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Usuarios</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/md5.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
      <tr>
  <td width="780" height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Seguridad</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
	</tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="11" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"><a href="javascript: ue_guardar();"></a><a href="javascript: ue_buscar();"></a><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"></a></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("tepuy_sss_c_usuarios.php");
	$io_sss= new tepuy_sss_c_usuarios();
	require_once("../shared/class_folder/tepuy_include.php");
	$io_in=new tepuy_include();
	$con=$io_in->uf_conectar();
	$ls_sql="SELECT codusu,nomtipusu FROM sss_tipousuario ORDER BY codusu";	
	$resultado=mysql_query($ls_sql);

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];

	$ls_fotowidth="121";
	$ls_fotoheight="94";
	$ls_foto ="blanco.jpg";

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_cedula="";
		$ls_nombre="";
		$ls_apellido="";
		$ls_telefono="";
		$ls_nota="";
		$ls_status="";
		$ls_ultingusu="";
		$ls_tipusu="";
	}
	if ($ls_operacion=="GUARDAR")
	{
		$ls_valido= false;
		$ls_codigo=  $_POST["txtcodigo"];
		$ls_cedula=  $_POST["txtcedula"];
		$ls_nombre=  $_POST["txtnombre"];
		$ls_apellido=$_POST["txtapellido"];
		$ls_telefono=$_POST["txttelefono"];
		$ls_nota=    $_POST["txtnota"];
		$ls_status=  $_POST["hidstatus"];
		$ls_fecha = date("Y/m/d h:i");
		$ls_tipusu=$_POST["cmbtipusu"];
/////////////////////////////////////////////////////////		
//datos del arhivo 
$ls_nomarch = $HTTP_POST_FILES['userfile']['name']; 
$ls_tiparch = $HTTP_POST_FILES['userfile']['type']; 
$ls_tamarch = $HTTP_POST_FILES['userfile']['size']; 
/////////////////////////////////////////////////////////		
		
 		if ($ls_status!="C")
		{
			$ls_passuser=$_POST["txtpassword"];
			$ls_repassword=$_POST["txtrepassword"];
		}
		
		if( ($ls_codigo=="")||($ls_nombre=="")||($ls_apellido==""))
			{
				$io_msg->message("Debe completar todos los campos");
			}
		else
			{
				if ($ls_status=="C")
				{
					$ls_nombreviejo=$_POST["txtloginviejo"];
					if($ls_codigo==$ls_nombreviejo)
					{
						$lb_valido=$io_sss->uf_sss_update_usuario($ls_empresa,$ls_codigo,$ls_cedula,$ls_nombre,$ls_apellido,
															      $ls_telefono,$ls_nota,$ls_nomarch,$ls_tipusu,$la_seguridad);
	
						if($lb_valido)
						{
							$io_msg->message("El usuario fue actulizado.");
							$ls_codigo="";
							$ls_nombre="";
							$ls_apellido="";
							$ls_cedula="";
							$ls_telefono="";
							$ls_nombre="";
							$ls_nota="";
							$ls_ultingusu="";
							$ls_tipusu="";
							if (!((strpos($ls_tiparch, "gif") || strpos($ls_tiparch, "jpeg")) && ($ls_tamarch < 100000))) { 
							}
							else
							{ 
								if (move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], "fotosusuarios/".$ls_nomarch))
								{}
							} 
						}	
						else
						{
							$io_msg->message("No se pudo actualizar el usuario");
						}
					}					
					else
					{
						$io_msg->message("No se puede cambiar el campo Login");
					}
				}
				else
				{
					$lb_encontrado=$io_sss->uf_sss_select_usuarios($ls_empresa,$ls_codigo);
					if ($lb_encontrado)
					{
						$io_msg->message("El usuario ya existe"); 
					}
					else
					{
						$ls_passencript=$_POST["txtpassencript"];
						//$ls_passencript= md5($ls_password);
						$lb_valido=$io_sss->uf_sss_insert_usuario($ls_fecha,$ls_empresa,$ls_codigo,$ls_nombre,$ls_apellido,$ls_cedula,
																  $ls_passencript,$ls_telefono,$ls_nota,$ls_nomarch,$ls_tipusu,$la_seguridad );

						if ($lb_valido)
						{
								$io_msg->message("El usuario fue registrado.");
								$ls_codigo="";
								$ls_nombre="";
								$ls_apellido="";
								$ls_cedula="";
								$ls_telefono="";
								$ls_nombre="";
								$ls_nota="";
								$ls_ultingusu="";
								$ls_tipusu="";
								//Grabar la foto en el servidor 
								if (!((strpos($ls_tiparch, "gif") || strpos($ls_tiparch, "jpeg")) && ($ls_tamarch < 100000))) { 
								}
								else
								{ 
									if (!((move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], "fotosusuarios/".$ls_nomarch))))
									{}
								} 
						}
						else
						{
								$io_msg->message("No se pudo registrar el usuario");
						}
					}
				}
			}
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$arr=$_SESSION["la_empresa"];
		$ls_empresa=$arr["codemp"];
		$ls_codigo=$_POST["txtcodigo"];
		
		$lb_valido=$io_sss->uf_sss_delete_usuario($ls_empresa,$ls_codigo,$la_seguridad);
		
		if($lb_valido)
		{
			$io_msg->message("El usuario fue eliminado.");
		}	
		else
		{
			$io_msg->message("No se pudo eliminar el usuario");
		}

		$ls_codigo="";
		$ls_nombre="";
		$ls_apellido="";
		$ls_cedula="";
		$ls_telefono="";
		$ls_nombre="";
		$ls_nota="";
		$ls_ultingusu="";
		$ls_tipusu="";
	}
	elseif ($ls_operacion=="MODIFICAR")
	{
		$ls_codigo=      $_POST["txtcodigo"];
		$ls_cedula=      $_POST["txtcedula"];
		$ls_nombre=      $_POST["txtnombre"];
		$ls_nombreviejo= $_POST["txtnombre"];
		$ls_apellido=    $_POST["txtapellido"];
		$ls_telefono=    $_POST["txttelefono"];
		$ls_nota=        $_POST["txtnota"];
		$ls_foto=        $_POST["hidfoto"];
		$ls_status=      $_POST["hidstatus"];
		$ls_ultingusu=   $_POST["txtultingusu"];
		$ls_fecha = date("Y/m/d h:i");
		$ls_tipusu=$_POST["cmbtipusu"];
		$ls_status="C";
	
	}
	elseif ($ls_operacion=="NUEVO")
	{
		$ls_codigo="";
		$ls_nombre="";
		$ls_apellido="";
		$ls_cedula="";
		$ls_telefono="";
		$ls_nombre="";
		$ls_nota="";
		$ls_ultingusu="";
		$ls_tipusu="";

	}
	
?>
<?php
	if($ls_operacion!="MODIFICAR")
		{
?>

<p>&nbsp;</p>
<div align="center">
<table width="550" height="345" border="0" class="formato-blanco">
<?php
	}
	else
	{
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="345" border="0" class="formato-blanco">

<?php
	}
?>
    <tr>
      <td height="339"><div align="left">
          <form name="form1" method="post" action="" enctype="multipart/form-data", >
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<div align="center">
              <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td height="18" colspan="4" class="titulo-ventana">Definici&oacute;n de Usuarios</td>
                </tr>
                <tr class="formato-blanco">
                  <td width="141" height="26">&nbsp;</td>
                  <td colspan="3"><input name="txtempresa" type="hidden" id="txtempresa" value="<?php print $ls_empresa?>">
                      <input name="txtloginviejo" type="hidden" id="txtloginviejo" value="<?php print $ls_codigo?>">
                      <span class="toolbar">
                      <input name="hidstatus" type="hidden" id="hidstatus5" value="<?php print $ls_status?>">
                    </span> </td>
                </tr>
                <tr class="formato-blanco">
                  <td height="24"><div align="right">Login</div></td>
                  <td width="283" height="22"><div align="left">
                    <input name="txtcodigo" type="text" id="txtcodigo" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_codigo?>" size="30" maxlength="30">
                  </div></td>
				  <?php 
				  if($ls_foto=="")
				  {
					  $ls_foto="blanco.jpg";
				  }
				  ?>
                  <td width="97" rowspan="4"><img name="foto" id="foto" src="fotosusuarios/<?php print $ls_foto?>" width="121" height="94" class="formato-blanco"></td>
                  <td><input name="hidfoto" type="hidden" id="hidfoto"></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="24"><div align="right">C&eacute;dula</div></td>
                  <td height="22">
                    <div align="left">
                      <input name="txtcedula" type="text" id="txtcedula" value="<?php print $ls_cedula?>" size="15" maxlength="8"  onKeyUp="javascript: ue_validarnumero(this);">
                  </div></td>
                  <td width="43">&nbsp;</td>
                </tr>
                <tr class="formato-blanco">
                  <td height="25"><div align="right">Nombres</div></td>
                  <td height="22"><div align="left">
                    <input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nombre?>" size="40" maxlength="100" onKeyUp="javascript: ue_validarcomillas(this);">
                  </div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr class="formato-blanco">
                  <td height="23"><div align="right">Apellidos</div></td>
                  <td height="22"><div align="left">
                    <input name="txtapellido" type="text" id="txtapellido" value="<?php print $ls_apellido?>" size="40" maxlength="50" onKeyUp="javascript: ue_validarcomillas(this);">
                  </div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr class="formato-blanco">
                  <td height="25"><div align="right">Foto</div></td>
                  <td height="22" colspan="3"><div align="left">
                    <input name="userfile" type="file">
                  </div></td>
                </tr>

<?php
	if($ls_operacion!="MODIFICAR")
		{
?>
                <tr class="formato-blanco">
                  <td height="25"><div align="right">Password</div></td>
                  <td colspan="3"><div align="left">
                    <input name="txtpassword" type="password" id="txtpassword" maxlength="50">
                    <input name="txtpassencript" type="hidden" id="txtpassencript">
                  </div></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="23"><div align="right">Verificar Password</div></td>
                  <td colspan="3"><div align="left">
                    <input name="txtrepassword" type="password" id="txtrepassword" onBlur="ue_verificar();" maxlength="50">
                  </div></td>
                </tr>
<?php
	}
?>
          <tr> 
            <td height="25"><div align="right">Menu de Usuario</div></td>
            <td width="223" height="23">
	  <select name="cmbtipusu" id="cmbtipusu">
	
	 <option value='-'>-- Seleccione Uno --</option>";
		<?php
			while($fila=mysql_fetch_row($resultado))
			{
				$ls_seleccionado="";
				if($as_seleccionado==$fila['0']."'->".$fila['1'])
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$fila['0']."'->".$fila['1']." ".$ls_seleccionado."</option>";
			}
		?> 
		<!--<?php $io_sss->uf_load_menu_usuario($ls_codtipsol);?> -->
	   </td>
          </tr>
	  </select></td>
                <tr class="formato-blanco">
                  <td height="28"><div align="right">Telefono</div></td>
                  <td colspan="3"><div align="left">
                    <input name="txttelefono" type="text" id="txttelefono" value="<?php print $ls_telefono?>" maxlength="20"  onKeyUp="ue_validartelefono(this)">
                  </div></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="18"><div align="right">Nota</div></td>
                  <td colspan="3"><div align="left">
                    <input name="txtnota" type="text" id="txtnota2" value="<?php print $ls_nota?>" size="70" onKeyUp="ue_validarcomillas(this)">
                  </div></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="22"><div align="right">Ultimo Ingreso </div></td>
                  <td colspan="3"><input name="txtultingusu" type="text" id="txtultingusu" value="<?php print $ls_ultingusu?>" readonly></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="13"><div align="right"></div></td>
                  <td colspan="3">&nbsp;</td>
                </tr>
              </table>
            </div>
            <p align="center">
              <input name="operacion" type="hidden" id="operacion">
</p>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_verificar()
{
	f=document.form1
	password=f.txtpassword.value;
	repassword=f.txtrepassword.value;
	if(password!=repassword)
	{
		alert("No coinciden los password");
		f.txtpassword.value="";
		f.txtrepassword.value="";
	}
	else
	{
		f.txtpassencript.value=calcMD5(password);
	}

}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("tepuy_sss_cat_usuarios.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
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
		f.action="tepuy_sss_d_usuarios.php";
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
		f.operacion.value="GUARDAR";
		f.action="tepuy_sss_d_usuarios.php";
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
		if(confirm("¿Seguro desea eliminar el Usuario?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="tepuy_sss_d_usuarios.php";
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


</script> 
</html>
