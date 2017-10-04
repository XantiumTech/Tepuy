<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "f.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
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
<body>
<form name="form1" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Presupuestarias </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="148" height="22"><div align="right">Cuenta Presupuestaria </div></td>
        <td width="346" height="22"><div align="left">
          <input name="spgcuenta" type="text" id="spgcuenta" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdencue" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.png" alt="Cerrar" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
	</table> 
	<p>
  		<div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_programatica,as_cuenta,as_denominacion,as_codespro)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cuenta presupuestaria no esté en el form1
	//---------------------------------------------------------------------------------
	valido=true;
	// Obtenemos el total de filas de los Conceptos
	total=ue_calcular_total_fila_opener("codcon");
	f.totrowconceptos.value=total;
	// Obtenemos el total de filas de los servicios
	total=ue_calcular_total_fila_opener("codser");
	f.totrowservicios.value=total;
	// Obtenemos el total de filas de los bienes
	total=ue_calcular_total_fila_opener("codart");
	f.totrowbienes.value=total;
	// Obtenemos el total de filas de los cargos
	total=ue_calcular_total_fila_opener("codservic");
	f.totrowcargos.value=total;
	//obtener el numero de filas real de las Cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	f.totrowcuentas.value=total;
	rowcuentas=f.totrowcuentas.value;
	for(j=1;(j<=rowcuentas)&&(valido);j++)
	{
		cuentagrid=eval("f.txtcuentagas"+j+".value");
		programatica=eval("f.codprogas"+j+".value");
		if((cuentagrid==as_cuenta)&&(programatica==as_codespro))
		{
			alert("La cuenta presupuestaria ya está en la solicitud, se va reemplazar La Estructura.");
			eval("f.txtprogramaticagas"+j+".value='"+as_programatica+"'");
			eval("f.codprogas"+j+".value='"+as_codespro+"'");
			valido=false;			
		}
	}
	tiposolicitud=f.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
	parametros="";
	proceso="";
	if(tipo=="B")
	{
		proceso="AGREGARBIENES";
		//---------------------------------------------------------------------------------
		// Cargar los Bienes del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowbienes=f.totrowbienes.value;
		for(j=1;(j<rowbienes)&&(valido);j++)
		{
			codart=eval("f.codart"+j+".value");
			denart=eval("f.txtdenart"+j+".value");
			canart=eval("f.txtcanart"+j+".value");
			unidad=eval("f.cmbunidad"+j+".value");
			preart=eval("f.txtpreart"+j+".value");
			subtotart=eval("f.txtsubtotart"+j+".value");
			carart=eval("f.txtcarart"+j+".value");
			totart=eval("f.txttotart"+j+".value");
			spgcuenta=eval("f.spgcuenta"+j+".value");
			unidadfisica=eval("f.txtunidad"+j+".value");
			parametros=parametros+"&codart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&spgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"";
		}
		parametros=parametros+"&totalbienes="+rowbienes+"";
	}
	if(tipo=="S")
	{
		proceso="AGREGARSERVICIOS";
		//---------------------------------------------------------------------------------
		// Cargar los Servicios del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowservicios=f.totrowservicios.value;
		for(j=1;(j<rowservicios)&&(valido);j++)
		{
			codser=eval("f.codser"+j+".value");
			denser=eval("f.txtdenser"+j+".value");
			canser=eval("f.txtcanser"+j+".value");
			unimed=eval("f.txtdenunimed"+j+".value");
			preser=eval("f.txtpreser"+j+".value");
			subtotser=eval("f.txtsubtotser"+j+".value");
			carser=eval("f.txtcarser"+j+".value");
			totser=eval("f.txttotser"+j+".value");
			spgcuenta=eval("f.spgcuenta"+j+".value");
	
			parametros=parametros+"&codser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&spgcuenta"+j+"="+spgcuenta+"&txtdenunimed"+j+"="+unimed+"";
		}
		parametros=parametros+"&totalservicios="+rowservicios+"";
	}
	if(tipo=="O")
	{
		proceso="AGREGARCONCEPTOS";
		//---------------------------------------------------------------------------------
		// Cargar los Conceptos del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowconceptos=f.totrowconceptos.value;
		for(j=1;(j<rowconceptos)&&(valido);j++)
		{
			codcon=eval("f.codcon"+j+".value");
			dencon=eval("f.txtdencon"+j+".value");
			cancon=eval("f.txtcancon"+j+".value");
			precon=eval("f.txtprecon"+j+".value");
			subtotcon=eval("f.txtsubtotcon"+j+".value");
			carcon=eval("f.txtcarcon"+j+".value");
			totcon=eval("f.txttotcon"+j+".value");
			spgcuenta=eval("f.spgcuenta"+j+".value");
	
			parametros=parametros+"&codcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
					   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
					   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
					   "&txttotcon"+j+"="+totcon+"&spgcuenta"+j+"="+spgcuenta;
		}
		parametros=parametros+"&totalconceptos="+rowconceptos+"";
	}
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("codservic");
	f.totrowcargos.value=total;
	rowcargos=f.totrowcargos.value;
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{
		codservic=eval("f.codservic"+j+".value");
		codcar=eval("f.codcar"+j+".value");
		dencar=eval("f.txtdencar"+j+".value");
		bascar=eval("f.txtbascar"+j+".value");
		moncar=eval("f.txtmoncar"+j+".value");
		subcargo=eval("f.txtsubcargo"+j+".value");
		cuentacargo=eval("f.cuentacargo"+j+".value");
		formulacargo=eval("f.formulacargo"+j+".value");
		parametros=parametros+"&codservic"+j+"="+codservic+"&codcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo;
	}
	parametros=parametros+"&totalcargos="+rowcargos;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		codpro=eval("f.codprogas"+j+".value");
		cuenta=eval("f.txtcuentagas"+j+".value");
		moncue=eval("f.txtmoncuegas"+j+".value");
		parametros=parametros+"&codprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
				   "&txtmoncuegas"+j+"="+moncue;
	}
	totalcuentas=eval(rowcuentas+"+"+1);
	f.totrowcuentas.value=totalcuentas;	
	parametros=parametros+"&codprogas"+rowcuentas+"="+as_codespro+"&txtcuentagas"+rowcuentas+"="+as_cuenta+
			   "&totalcuentas="+totalcuentas;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	f.totrowcuentascargo.value=total;
	rowcuentas=f.totrowcuentascargo.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{  
		cargo=eval("f.codcargo"+j+".value");
		codpro=eval("f.codprocar"+j+".value");
		cuenta=eval("f.txtcuentacar"+j+".value");
		moncue=eval("f.txtmoncuecar"+j+".value");
		parametros=parametros+"&codcargo"+j+"="+cargo+"&codprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				   "&txtmoncuecar"+j+"="+moncue;
	}
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("f.txtsubtotal.value");
	cargos=eval("f.txtcargos.value");
	total=eval("f.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

function ue_search()
{
	f=document.form1;
	// Cargamos las variables para pasarlas al AJAX
	codest1=f.codest1.value;alert("aqui");
	codest2=f.codest2.value;
	codest3=f.codest3.value;
	codest4=f.codest4.value;
	codest5=f.codest5.value;
	spgcuenta=f.spgcuenta.value; 
	dencue=f.txtdencue.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
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
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=CUENTASSPG&spgcuenta="+spgcuenta+"&dencue="+dencue+"&codest1="+codest1+
			  "&codest2="+codest2+"&codest3="+codest3+"&codest4="+codest4+"&codest5="+codest5+
			  "&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo);
}

function ue_close()
{
	close();
}
</script>
</html>
