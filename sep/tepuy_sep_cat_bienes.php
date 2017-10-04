<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: 
		//	Description: Función que obtiene e imprime los tipos de articulos
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<select name='cmbcodtipart' id='cmbcodtipart' style='width:150px'> ";
		print "		<option value='' selected>---seleccione---</option> ";
		$ls_sql="SELECT codtipart, dentipart ".
		        "  FROM siv_tipoarticulo ".
				" ORDER BY codtipart ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtipart=$row["codtipart"];
				$ls_dentipart=$row["dentipart"];
		  	    print "<option value='$ls_codtipart'>".$ls_dentipart."</option>";
			}
			$io_sql->free_result($rs_data);
		}
		print "</select>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bienes y Materiales</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #FF0000;
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
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codart">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Bienes y Materiales </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="82" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="412" height="22"><div align="left">
          <input name="txtcodart" type="text" id="txtcodart" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdenart" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo </div></td>
        <td height="22"><div align="left">
          <?php uf_select_tipo(); ?>
        </div></td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.png" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
	</table> 
	<p>
		<div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_codart,as_denart,as_denunimed,as_unidad,as_spg_cuenta,ai_precio,ai_totalcargos,ai_existecuenta)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el artículo no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	total=ue_calcular_total_fila_opener("txtcodart");
	opener.document.formulario.totrowbienes.value=total;
	rowbienes=opener.document.formulario.totrowbienes.value;
	for(j=1;(j<=rowbienes)&&(valido);j++)
	{
		codartgrid=eval("opener.document.formulario.txtcodart"+j+".value");
		if(codartgrid==as_codart)
		{
			alert("El Artículo ya está en la solicitud !!!");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Bienes del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	for(j=1;(j<rowbienes)&&(valido);j++)
	{
		codart=eval("opener.document.formulario.txtcodart"+j+".value");
		denart=eval("opener.document.formulario.txtdenart"+j+".value");
		canart=eval("opener.document.formulario.txtcanart"+j+".value");
		unimed=eval("opener.document.formulario.txtdenunimed"+j+".value");
		unidad=eval("opener.document.formulario.cmbunidad"+j+".value");
		preart    = eval("opener.document.formulario.txtpreart"+j+".value");
		subtotart = eval("opener.document.formulario.txtsubtotart"+j+".value");
		carart=eval("opener.document.formulario.txtcarart"+j+".value");
		totart=eval("opener.document.formulario.txttotart"+j+".value");
		spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
		unidadfisica=eval("opener.document.formulario.txtunidad"+j+".value");
		parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
				   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
				   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
				   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
				   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"&txtdenunimed"+j+"="+unimed+"";
	}
	totalbienes=eval(rowbienes+"+1");
	parametros=parametros+"&txtcodart"+rowbienes+"="+as_codart+"&txtdenart"+rowbienes+"="+as_denart+""+
			   "&txtcanart"+rowbienes+"=0,00&cmbunidad"+rowbienes+"=D"+
			   "&txtpreart"+rowbienes+"="+ai_precio+"&txtsubtotart"+rowbienes+"=0,00"+
			   "&txtcarart"+rowbienes+"=0,00&txttotart"+rowbienes+"=0,00"+
			   "&txtspgcuenta"+rowbienes+"="+as_spg_cuenta+"&txtunidad"+rowbienes+"="+as_unidad+""+
			   "&totalbienes="+totalbienes+"&txtdenunimed"+rowbienes+"="+as_denunimed+"";
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	rowcargos=opener.document.formulario.totrowcargos.value;
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{
		codservic=eval("opener.document.formulario.txtcodservic"+j+".value");
		codcar=eval("opener.document.formulario.txtcodcar"+j+".value");
		dencar=eval("opener.document.formulario.txtdencar"+j+".value");
		bascar=eval("opener.document.formulario.txtbascar"+j+".value");
		moncar=eval("opener.document.formulario.txtmoncar"+j+".value");
		subcargo=eval("opener.document.formulario.txtsubcargo"+j+".value");
		cuentacargo=eval("opener.document.formulario.cuentacargo"+j+".value");
		formulacargo=eval("opener.document.formulario.formulacargo"+j+".value");
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo;
	}
	totalcargos=eval(rowcargos);
	parametros=parametros+"&txtcodservic="+as_codart+"&totalcargos="+totalcargos;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		codpro=eval("opener.document.formulario.txtcodprogas"+j+".value");
		cuenta=eval("opener.document.formulario.txtcuentagas"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuegas"+j+".value");
		parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
				   "&txtmoncuegas"+j+"="+moncue;
	}
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	codestpro4=opener.document.formulario.txtcodestpro4.value;
	codestpro5=opener.document.formulario.txtcodestpro5.value;	
	programatica="";
	if(ai_existecuenta!=0)
	{
		programatica=codestpro1+codestpro2+codestpro3+codestpro4+codestpro5;
	}
	totalcuentas=eval(rowcuentas);
	parametros=parametros+"&txtcodprogas"+rowcuentas+"="+programatica+"&txtcuentagas"+rowcuentas+"="+as_spg_cuenta+
			   "&totalcuentas="+totalcuentas;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value");
		codpro=eval("opener.document.formulario.txtcodprocar"+j+".value");
		cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				   "&txtmoncuecar"+j+"="+moncue;
	}
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total+"&codprounidad="+codestpro1+codestpro2+codestpro3+codestpro4+codestpro5;
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
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
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
		ajax.send("proceso=AGREGARBIENES"+parametros);
		opener.document.formulario.totrowbienes.value=totalbienes;
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	codestpro4=opener.document.formulario.txtcodestpro4.value;
	codestpro5=opener.document.formulario.txtcodestpro5.value;
	codart=f.txtcodart.value;
	denart=f.txtdenart.value;
	codtipart=f.cmbcodtipart.value;
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
					divgrid.innerHTML = ajax.responseText;
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
	ajax.send("catalogo=BIENES&codart="+codart+"&denart="+denart+"&codtipart="+codtipart+"&codestpro1="+codestpro1+
			  "&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+
			  "&orden="+orden+"&campoorden="+campoorden);
}

function ue_close()
{
	close();
}
</script>
</html>
