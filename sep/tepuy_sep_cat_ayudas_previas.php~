<?php
	session_start();
	ini_set('display_errors', 1);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$ls_tipo=$io_fun_sep->uf_obtenertipo();
	require_once("class_folder/tepuy_sep_c_solicitud.php");
	$io_sep=new tepuy_sep_c_solicitud("../");
	unset($io_fun_sep);
	$beneficiario=$_GET["beneficiario"];
	//print "Beneficiario: ".$beneficiario;
	$numero=$io_sep->uf_numero_ayudas_previas($beneficiario);
	//print "Cantidad:".$numero;
	if($numero<=0)
	{
		
		?>
		<script language=javascript>
		opener.document.formulario.operacion.value="GUARDAR";
		f=opener.document.formulario.operacion.value;
		opener.document.formulario.action="tepuy_sep_p_solicitud_ayuda.php";
		this.close();
		sigo=1;

		close();
		opener.document.formulario.submit();

		</script>
		<?php

	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Ayudas Previas</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<body onLoad="javascript: ue_search('<?php print $beneficiario; ?>');">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="coduniadm">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Lista de 
Ayudas Previas</td>
    </tr>
  </table>
<br>
 <td><div align="left"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Cerrar" width="20" height="20" border="0"> Aceptar</a>
<align="right"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Cerrar" width="20" height="20" border="0"> Rechazar</a></div></td>
      </tr>
  </table>
	<p>
		<div id="resultados" align="center"></div>	
	</p>
</form>
</body>
<script language="JavaScript">
function ue_aceptar()
{
	opener.document.formulario.operacion.value="GUARDAR";
	f=opener.document.formulario.operacion.value;
	opener.document.formulario.action="tepuy_sep_p_solicitud_ayuda.php";
	close();
	opener.document.formulario.submit();
}

function ue_cerrar()
{
	close();
}

function ue_search(beneficiario)
{
	f=document.formulario;	
	// Cargamos las variables para pasarlas al AJAX
	//cedbene=f.txtcedbene.value;
	//alert(cedbene);
	//nombene=f.txtnombene.value;
	//apebene=f.txtapebene.value;
	//tipo=f.tipo.value;
	//orden=f.orden.value;
	//campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sep_c_catalogo_ajax.php",true);
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
	que="BUSCARAYUDAPREVIA";
	//alert(beneficiario);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("codigo="+beneficiario+"&catalogo="+que);
	//ajax.send("tipo="+tiposolicitud+"&totalbienes=1&totalservicios=1&&totalconceptos=1&totalcargos=0"+
	//			  "&totalcuentas=1&totalcuentascargo=1&proceso=LIMPIARAYUDA");
}
</script>
</html>
