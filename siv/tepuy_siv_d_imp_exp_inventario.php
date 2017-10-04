<?php
session_start();
ini_set('display_errors', 1);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIV","tepuy_siv_d_imp_exp_inventario.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_selected,$ls_archivo,$ls_checkarticulos,$ls_checkasiento,$ls_importar,$ls_procesar;
		if (array_key_exists("procesar",$_POST))
		{
			$ls_procesar= $_POST["procesar"];
			//print "Procesar".$ls_procesar;
		}
		else
		{
			$ls_procesar= 0;
		}
		if (array_key_exists("cmbmetodo",$_POST))
		{
			$ls_selected= $_POST["cmbmetodo"];
		}
		else
		{
			$ls_selected= "--";
		}
		if (array_key_exists("txtexportar",$_POST))
		{
			$ls_archivo= $_POST["txtexportar"];
		}
		else
		{
			$ls_archivo= "cierre_inventario_".substr($_SESSION["la_empresa"]["periodo"],0,4).".txt";
		}
		if (array_key_exists("txtimportar1",$_POST))
		{
			$ls_importar= $_POST["txtimportar1"];
			//print "Importar".$ls_importar;
		}
		else
		{
			//$ls_importar= "";
		}
		if (array_key_exists("chkarticulos",$_POST))
		{
			$ls_checkarticulos= $_POST["chkarticulos"];
		}
		else
		{
			$ls_checkarticulos= 1;
		}
		if (array_key_exists("chkasiento",$_POST))
		{
			$ls_checkasiento= $_POST["chkasiento"];
		}
		else
		{
			$ls_checkasiento= 1;
		}
		//print "Metodo: ".$ls_selected;
		

   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Importar/Exportar Inventario</title>
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
<link href="css/siv.css" rel="stylesheet" type="text/css">
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
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Inventario </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>	 </td>
  </tr>
  
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>-->
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
   <tr>
    <td height="42" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>

    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.png" alt="Procesar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="27"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="23"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="366"><div align="center"></div></td>
    <td class="toolbar" width="92"><div align="center"></div></td>
    <td class="toolbar" width="92"><div align="center"></div></td>
    <td class="toolbar" width="132">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=  new tepuy_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("tepuy_siv_c_configuracion.php");
	$io_siv= new tepuy_siv_c_configuracion();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("class_funciones_inventario.php");
	$io_funciones_inventario= new class_funciones_inventario();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_operacion=$io_funciones_inventario->uf_obteneroperacion();
	//print "Operacion: ".$ls_operacion;
	switch ($ls_operacion) 
	{
		case "NUEVO":
		break;

		case "CARGAR":
			//print "1: ".$_FILES['txtimportar']['name'] ." 2: "."txt/".$HTTP_POST_FILES['txtimportar']['name'];
			$archivo="txt/".$_FILES['txtimportar']['name'];
			$valido=true;
			if (file_exists($archivo))
			{ // Si el archivo ya existe en el directorio
				if(@unlink("$archivo")===false)
				{ // Si falla la eliminacion del archivo
					$io_msg->message("El archivo $archivo no puede ser reemplazado, consulte al Administrador del Sistema");
					$valido=false;
				}
			}
			if($valido)
			{ // Si logra incorporar el archivo procede a validarlo
				copy($_FILES['txtimportar']['tmp_name'],"txt/".$_FILES['txtimportar']['name']);
				$nombre=$_FILES['txtimportar']['name'];
				$ls_procesar= 1; // Para que al procesar puede validar
				//print "aqui:".$ls_procesar;
				$io_msg->message("El archivo $nombre fue incorporado con exito");
				$ls_procesa=$io_siv->uf_siv_certifica_archivo($ls_codemp,$archivo,$la_seguridad);
				if(!$ls_procesa)
				{
					$io_msg->message("El archivo $archivo no puede ser procesado. Consulte al Administrador del Sistema");
				}
			}
			uf_limpiarvariables();
		break;
		
		case "PROCESAR";
			uf_limpiarvariables();
			$li_chkarticulos=$io_funciones_inventario->uf_obtenervalor("chkarticulos",0);
			$li_chkasiento=$io_funciones_inventario->uf_obtenervalor("chkasiento","");
			//$li_estnum=$io_funciones_inventario->uf_obtenervalor("rdcodigo","");
			//$li_estcont=$io_funciones_inventario->uf_obtenervalor("chkcontabilizar",0);
			//$li_estcmp=$io_funciones_inventario->uf_obtenervalor("chkestcmp",0);
			//print "Procesar";
			if($ls_procesar===0)
			{
				$io_msg->message("Debe seleccionar previamente un archivo ");
			}
			else
			{
				switch ($ls_selected)
				{
					case"IM":
						$ls_archivo=$io_funciones_inventario->uf_obtenervalor("txtimportar1",0);
						if(substr(trim($ls_archivo),1,1)==":")
						{
							$file=substr($ls_archivo,12,strlen($ls_archivo));
						}
						//print "Empresa:".$ls_codemp." Archivo Importar: ".$ls_archivo;
						$ls_procesa=$io_siv->uf_siv_importar_archivo($ls_codemp,$ls_archivo,$la_seguridad);
						if($ls_procesa)
						{
							$io_msg->message("El archivo ".$file." se proceso satisfactoriamente");
						}
					break;
					case"EX":
						//print "Archivo Exportar: ".$ls_archivo;
						$ls_procesa=$io_siv->uf_siv_exportar_archivo($ls_codemp,$ls_archivo,$li_chkarticulos,$li_chkasiento,$la_seguridad);
						if($ls_procesa)
						{
							$io_msg->message("El archivo $ls_archivo se proceso satisfactoriamente");
			//			echo "<script>document.location.href='tepuy_siv_cat_directorio.php?archivo=$ls_archivo';</script>\n";
							$ls_ruta="txt";
echo "<script>window.open('tepuy_siv_cat_directorio.php?ruta=$ls_ruta','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no');</script>\n";

							//$io_siv->uf_descargar_archivo($ls_archivo);
						}
					break;
				}
				if ($ls_procesa)
				{
					//$lb_valido=$io_siv->uf_procesa_archivo($ls_codemp,$ls_selected,$ls_archivo,$la_seguridad);
				}
			}
	}
	uf_limpiarvariables();
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="512" height="134" border="0" class="formato-blanco">
    <tr>
      <td width="538" height="130"><div align="left">
         <!-- <form name="form1" method="post" action="enviar.php" enctype="multipart/form-data">-->
           <form name="form1" method="post" action="" enctype="multipart/form-data"> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="454" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="2" class="titulo-ventana">Importar/Exportar Inventario</td>
  </tr>
  <tr class="formato-blanco">
    <td width="135" height="13">&nbsp;</td>
    <td>
      <div align="left"></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Operación</div></td>
    <td><div align="left">

		  <?php  
		   if($ls_selected)
		   {
		  ?>
		<select name="cmbmetodo" id="cmbmetodo" onChange="javascript: ue_cambiarmetodo();">
		<option value="--" selected>-- Seleccione Uno --</option>
		<option value="IM" <?php if($ls_selected=='IM'){ print 'selected';} ?>>Importar</option>
		<option value="EX" <?php if($ls_selected=='EX'){ print 'selected';} ?>>Exportar</option>
		</select></td>
  </tr>   
		  <?php  
		   }
		  ?>

    </div></td>
  </tr>
		  <?php  
		   if($ls_selected=="IM")
		   {
		  ?>
                <td height="13" colspan="4"> <div align="center">Importante!!! Una vez procesado el asiento inicial no podr&aacute; volver a intentarlo </div></td>
			<tr>
			<td height="22"><div align="right">Seleccione Archivo:</div></td>
			<td colspan="2">
			<div align="left">
			<input name="txtimportar" type="file" id="txtimportar" accept=".txt"  onChange="ue_cargar()" size="200" maxlength="200" >
			</div></td>
			</tr> 
		<tr class="formato-blanco">
			<td height="22">&nbsp;</td>
			<td>
	<!--			<p align="left">
			<input name="chkarticulos" type="checkbox" class="sin-borde" id="chkarticulos" value="1" checked <?php print $ls_checkarticulos ?>>
Importar Art&iacute;culos </p>    </td>
			</tr>
			<tr class="formato-blanco">
			<td height="22"><div align="right"></div></td>
			<td><div align="left">
			<input name="chkasiento" type="checkbox" class="sin-borde" id="chkasiento" value="1" checked <?php print $ls_checkasiento ?>>
     Importar Asiento Inicial </div></td>
			</tr> -->
<!--<input name="boton" type="submit" id="boton" value="Enviar">
</form>
<? echo "<img src=\"".$HTTP_POST_FILES['txtimportar']['name']."\">" ?> -->
		  <?php  
		   }
		  ?>
		  <?php  
		   if($ls_selected=="EX")
		   {
		  ?>
			<tr>
			<td height="22"><div align="right">Nombre del Archivo:</div></td>
			<td colspan="2">
			<div align="left">
                      <input name="txtexportar" type="text" id="txtexportar"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz')" onBlur="javascript: ue_verificar_rd();" value="<?php print $ls_archivo;?>" size="50" maxlength="50" >
			</div></td>
			</tr>
			<tr class="formato-blanco">
			<td height="22">&nbsp;</td>
			<td>
			<p align="left">
			<input name="chkarticulos" type="checkbox" class="sin-borde" id="chkarticulos" value="1" checked <?php print $ls_checkarticulos ?>>
Exportar Art&iacute;culos </p>    </td>
			</tr>
			<tr class="formato-blanco">
			<td height="22"><div align="right"></div></td>
			<td><div align="left">
			<input name="chkasiento" type="checkbox" class="sin-borde" id="chkasiento" value="1" checked <?php print $ls_checkasiento ?>>
     Exportar Cierre de Inventario </div></td>
			</tr>

		  <?php  
		   }
		  ?>


        </tr>
  <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
 <input name="procesar" type="hidden" id="procesar" value="<? print $ls_procesar;?>">
<input name="txtimportar1" type="hidden" id="txtimportar1" value="<? print $ls_importar;?>" >
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
function ue_mostrar(ls_id,ls_accion)
{
	f=document.form1;
	if(ls_accion==1)
	{
		document.getElementById(ls_id).style.visibility="visible";
	}
	else
	{
		document.getElementById(ls_id).style.visibility="hidden";	
	}
}

//Funciones de operaciones sobre el formulario
function ue_cargar()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	//f.procesar.value=1;
	f.txtimportar1.value=f.txtimportar.value;
	//alert(f.txtimportar1.value);
	f.action="tepuy_siv_d_imp_exp_inventario.php";
	f.submit(); 
}

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="tepuy_siv_d_imp_exp_inventario.php";
	f.submit();
}

function ue_procesar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	metodo=f.cmbmetodo.value;
	//alert(metodo);
	//alert(f.txtimportar1.value);
	if(li_incluir==1)
	{	
		if(metodo!="--")
		{
			proceso=0;
			if(metodo=="IM")
			{
				if(f.txtimportar1.value!="")
				{
					if(f.txtimportar1.value.substr(-4,4)!=".txt")
					{
						alert("Tipo de archivo no corresponde");
					}
					else
					{
						proceso=1;
					}
				}
				else
				{
					alert("Debe seleccionar previamente un archivo");
				}

			}
			if(metodo=="EX")
			{
				if(f.txtexportar.value!="")
				{
					if(f.txtexportar.value.substr(-4,4)!=".txt")
					{
						alert("Tipo de archivo no corresponde");
					}
					else
					{
						if((f.chkarticulos.checked==true)||(f.chkasiento.checked==true))
						{
							proceso=1;
						}
						else
						{
							alert("Debe seleccionar información a exportar");
						}
					}
				}
				else
				{
					alert("Debe asignarle el nombre al archivo a procesar");
				}
			}
			if(proceso==1)
			{
				f.operacion.value="PROCESAR";
				f.procesar.value=1;
			}
			f.action="tepuy_siv_d_imp_exp_inventario.php";
			f.submit();
		}
		else
		{
		    alert("Debe Seleccionar tipo de operación");
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

function ue_cambiarmetodo()
{
	f=document.form1;
	metodo=f.cmbmetodo.value;
	//alert(metodo);
	/*if(operacion=="O")// Compromiso
	{
	
	}*/
	f.action="tepuy_siv_d_imp_exp_inventario.php";
	f.submit();
}
</script> 
</html>
