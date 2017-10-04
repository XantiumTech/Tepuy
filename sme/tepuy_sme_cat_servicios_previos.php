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
	require_once("class_folder/class_funciones_sme.php");
	$io_fun_sme=new class_funciones_sme();
	$ls_tipo=$io_fun_sme->uf_obtenertipo();
	require_once("class_folder/tepuy_sme_c_solicitud.php");
	$io_sme=new tepuy_sme_c_solicitud("../");
	unset($io_fun_sme);
	$beneficiario=$_GET["beneficiario"];
	$codtar=$_GET["codtar"];
	$codnom=$_GET["codnom"];
	$total=$_GET["total"];
	$tipo_trab_fam=$_GET["tipotrafam"];
	$juntos=$beneficiario.$codtar.$tipo_trab_fam;
	$largo=strlen($juntos);
	if($largo<10){$largo="0".$largo;}

	//print "Beneficiario: ".$beneficiario." Codtar: ".$codtar." Total Bs. ".$total." Tipo Trab. Fam: ".$tipo_trab_fam;
	$montope=$io_sme->uf_monto_tope_servicios_medicos($tipo_trab_fam,$codtar);
	//$montope=number_format($montope,2,",",".");
	//print "Monto Tope--> ".$montope;
	$montoconsumido=$io_sme->uf_monto_consumo_servicios_medicos($tipo_trab_fam,$codtar,$codnom,$beneficiario);
	//print "Monto Consumido Acumulado --> ".$montoconsumido;
	//$numero=$io_sme->uf_numero_servicios_previos($beneficiario,$codtar);
	//die();
	//print "Cantidad:".$numero;
	if($montoconsumido<=0)
	{
		
		?>
		<script language=javascript>
		opener.document.formulario.operacion.value="GUARDAR";
		f=opener.document.formulario.operacion.value;
		opener.document.formulario.action="tepuy_sme_p_solicitud.php";
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
<title>Solicitud de Servicios Medicos Previas</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sme.js"></script>
<body onLoad="javascript: ue_search('<?php print $largo.$beneficiario.$codtar.$tipo_trab_fam.$montope; ?>');">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="coduniadm">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Lista de 
Servicios Medicos Previos</td>
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
	opener.document.formulario.action="tepuy_sme_p_solicitud.php";
	close();
	opener.document.formulario.submit();
}

function ue_cerrar()
{
	close();
}

function ue_search(beneficiario1)
{
	f=document.formulario;	
	// Cargamos las variables para pasarlas al AJAX
	//cedbene=f.txtcedbene.value;
	largo1=beneficiario1.length;
	largo=beneficiario1.substr(beneficiario1,2);
	largo1=largo1-largo;
	//alert(largo1);
	//alert(largo)
	largo=largo-3;
	codtar=beneficiario1.substr(largo,2);
	tipo_trab_fam=beneficiario1.substr(largo+2,1);
	beneficiario=beneficiario1.substr(2,largo);
	montope=beneficiario1.substr(largo+5,largo1);
	//alert(montope);
	//alert(tipo_trab_fam);
	//alert(beneficiario1);
	//nombene=f.txtnombene.value;
	//apebene=f.txtapebene.value;
	//tipo=f.tipo.value;
	//orden=f.orden.value;
	//campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sme_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
	que="BUSCARSMEPREVIA";
	//alert(beneficiario);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("codigo="+beneficiario+"&codtar="+codtar+"&catalogo="+que+"&tipo_trab_fam="+tipo_trab_fam+"&montope="+montope);
	//ajax.send("tipo="+tiposolicitud+"&totalbienes=1&totalservicios=1&&totalconceptos=1&totalcargos=0"+
	//			  "&totalcuentas=1&totalcuentascargo=1&proceso=LIMPIARAYUDA");
}
</script>
</html>
