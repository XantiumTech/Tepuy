<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$ls_tipo=$io_fun_sfa->uf_obtenertipo("tipo","");
	//print "Tipo: ".$ls_tipo;
	unset($io_fun_sfa);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Clientes</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="cedcli">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Listado de Clientes</td>
    </tr>
  </table>
<br>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="64" height="22"><div align="right">C&eacute;dula</div></td>
        <td height="22"><div align="left">
          <input name="txtcedcli" type="text" id="txtcedcli" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div>          
          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="txtnomcli" type="text" id="txtnomcli" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td height="22"><div align="left">
          <input name="txtapecli" type="text" id="txtapecli" onKeyPress="javascript: ue_mostrar(this,event);">      
        </div></td>
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
fop = opener.document.formulario;
f   = document.formulario;


function ue_aceptar(ls_cedcli,ls_nomcli,ls_tipconpro)
{
	//alert("aqui");
	fop.txtcedcli.value = ls_cedcli;
	fop.txtnomcli.value = ls_nomcli;
	//fop.tipconpro.value = ls_tipconpro;
	close();
}

function ue_search()
{
	// Cargamos las variables para pasarlas al AJAX
	cedcli=f.txtcedcli.value;
	nomcli=f.txtnomcli.value;
	apecli=f.txtapecli.value;
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sfa_c_catalogo_ajax.php",true);
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
						divgrid.innerHTML = "La página no existe";
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
	alert();
	ajax.send("catalogo=CLIENTE&cedcli="+cedcli+"&nomcli="+nomcli+"&apecli="+apecli+"&tipo="+tipo+"&orden="+orden+
			  "&campoorden="+campoorden+"");
}

function aceptar_reportedesde(ls_cedpro,ls_nomcli)
{
	fop.txtcedclides.value = ls_cedcli;
	fop.txtnomclides.value = ls_nomcli;
	close();
}

function aceptar_reportehasta(ls_cedcli,ls_nomcli) 
{
	fop.txtcedclihas.value = ls_cedcli;
	fop.txtnomclihas.value = ls_nomcli;
	close();
}
</script>
</html>
