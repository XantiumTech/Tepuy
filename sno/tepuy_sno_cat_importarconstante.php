<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	ini_set('display_errors', 1);
	require_once("class_folder/class_funciones_nomina.php");
	$io_funciones_nomina= new class_funciones_nomina();
	$ls_codcons=$io_funciones_nomina->uf_obtenervalor_get("codcons","");
	//print "Codigo: ".$ls_codcons;
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_archivo,$ls_importar,$ls_procesar;
		if (array_key_exists("procesar",$_POST))
		{
			$ls_procesar= $_POST["procesar"];
			//print "Procesar".$ls_procesar;
		}
		else
		{
			$ls_procesar= 0;
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

   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Explorar Directorios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=  new tepuy_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("class_folder/class_funciones_nomina.php");
	$io_funciones_nomina= new class_funciones_nomina();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_operacion=$io_funciones_nomina->uf_obteneroperacion();
	require_once("tepuy_sno_c_constantes.php");
	$io_sno=new tepuy_sno_c_constantes();;
	//print "Operacion: ".$ls_operacion;
	switch ($ls_operacion) 
	{
		case "PROCESAR";
			uf_limpiarvariables();
			if($ls_procesar===0)
			{
				$io_msg->message("Debe seleccionar previamente un archivo ");
			}
			else
			{
				//$handle = opendir();
				$archivo="txt/txt/".$_FILES['txtarchivo']['name'];
				//print "Aqui: ".$_FILES['txtarchivo']['tmp_name'];
				//print "directorio: ".$handle;
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
				{ // Si logra eliminar o el archivo no existe el archivo procede a copiarlo 
					copy($_FILES['txtarchivo']['tmp_name'],"txt/txt/".$_FILES['txtarchivo']['name']);
					$nombre=$_FILES['txtarchivo']['name'];
					$ls_procesar= 1; // Para que al procesar puede validar
					//print "aqui:".$ls_procesar;
					//$io_msg->message("El archivo $nombre fue incorporado con exito");
					$ls_procesa=$io_sno->uf_sno_certifica_archivo($archivo);
					if(!$ls_procesa)
					{
						$io_msg->message("El archivo $archivo no puede ser procesado. Consulte al Administrador del Sistema");
					}
					else
					{
						$ls_proceso=$io_sno->uf_sno_importar_archivo($archivo,$ls_codcons);
						if($ls_proceso)
						{
							$io_msg->message("El archivo ".$archivo." se proceso satisfactoriamente");
						}
					}
				}
			}
			break;
	}
	uf_limpiarvariables();
	
?>
<!--<form name="form1" method="post" action=""> -->
<form name="form1" method="post" action="" enctype="multipart/form-data">

  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Explorar Archivos TXT </td>
	</tr>
		  <?php  
		   if($ls_operacion=="NUEVO")
		   {
		  ?>
	<td height="22"><div align="right">Seleccione Archivo:</div></td>
	<td colspan="1">
	<div align="left">
	<input name="txtarchivo" type="file" id="txtarchivo" accept=".txt"  onChange="ue_procesar()" size="200" maxlength="200" >
	<!--	<input name="userfile" type="file" accept=".txt"> <input type="submit" value="Enviar"> -->
	</div></td>
	</tr> 
		  <?php  
		   }
		  ?>
  </table>
 <input name="procesar" type="hidden" id="procesar" value="<? print $ls_procesar;?>">
<input name="txtimportar1" type="hidden" id="txtimportar1" value="<? print $ls_importar;?>" >
<br>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function ue_procesar()
{
	f=document.form1;
	ls_archivo=f.txtarchivo.value;
	ls_extension=f.txtarchivo.value.substr(-4,4);
	if(ls_extension.toUpperCase()!=".TXT")
	{
		alert("Tipo de archivo no corresponde");
	}
	else
	{
		//alert("Tipo de archivo si corresponde");
		f.txtimportar1.value=f.txtarchivo.value;
		f.procesar.value=1;
		f.operacion.value="PROCESAR";
		f.action="tepuy_sno_cat_importarconstante.php";
		f.submit();
	}
}
</script>
</html>
