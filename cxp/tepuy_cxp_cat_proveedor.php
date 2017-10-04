<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat�logo de Proveedores</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="cod_pro">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Proveedores</td>
    </tr>
  </table>
<br>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="64" height="22"><div align="right">C&oacute;digo</div></td>
        <td height="22"><div align="left">
          <input name="txtcodpro" type="text" id="txtcodpro" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div>          
          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="txtnompro" type="text" id="txtnompro" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      <tr>
        <td height="22"><div align="right">Direcci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdirpro" type="text" id="txtdirpro" onKeyPress="javascript: ue_mostrar(this,event);">      
        </div></td>
      <tr>
        <td height="22"><div align="right">R.I.F.</div></td>
        <td height="22"><input name="txtrifpro" type="text" id="txtrifpro" onKeyPress="javascript: ue_mostrar(this,event);"></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0"></a><a href="javascript: ue_search();">Buscar</a> </div></td>
  </table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function aceptar(codpro,nompro,rifpro,sccuenta,tipconpro)
{
	opener.document.formulario.txtcodigo.value=codpro;
	opener.document.formulario.txtnombre.value=nompro;
	opener.document.formulario.txtrifpro.value=rifpro;
	opener.document.formulario.codigocuenta.value=sccuenta;
	opener.document.formulario.tipocontribuyente.value=tipconpro;
	close();
}

function aceptar_solicitudpago(codpro,nompro)
{
	opener.document.formulario.txtcodigo.value=codpro;
	opener.document.formulario.txtnombre.value=nompro;
	close();
}

function aceptar_reportedesde(codpro,nompro) //Proceso que llena el campo de proveedor hasta en el reporte de solicitudes
{
	opener.document.formulario.txtcoddes.value=codpro;
	close();
}

function aceptar_reportehasta(codpro,nompro) //Proceso que llena el campo de proveedor hasta en el reporte de solicitudes
{
	opener.document.formulario.txtcodhas.value=codpro;
	close();
}

function aceptar_cmpretencion(codpro) //Proceso que llena el campo de proveedor desde y hasta en el cmp retencion
{
	opener.cargarcodpro(codpro);
	close();
}

function aceptar_modcmpretencion(codpro,nompro,rifpro,dirprov) //Proceso que llena el campo de proveedor desde y hasta en el cmp retencion
{
	opener.cargarcodpro(codpro,nompro,rifpro,dirprov);
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	codpro=f.txtcodpro.value;
	nompro=f.txtnompro.value;
	dirpro=f.txtdirpro.value;
	rifpro=f.txtrifpro.value;
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_cxp_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La p�gina no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
				
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=PROVEEDOR&codpro="+codpro+"&nompro="+nompro+"&dirpro="+dirpro+"&rifpro="+rifpro+"&tipo="+tipo+"&orden="+orden+
			  "&campoorden="+campoorden);
}
</script>
</html>