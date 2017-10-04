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
	function uf_select_iva()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: 
		//	Description: Funci�n que obtiene e imprime los I.V.A. Creados
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
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
		print "<select name='cmbiva' id='cmbiva' style='width:150px'> ";
		print "		<option value='' selected>---seleccione---</option> ";
		$ls_sql="SELECT codcar, dencar ".
		        "  FROM tepuy_cargos ".
				" ORDER BY codcar ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcar=$row["codcar"];
				$ls_dencar=$row["dencar"];
		  	    print "<option value='$ls_codcar'>".$ls_dencar."</option>";
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
<title>Cat&aacute;logo de Servicios</title>
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
<input name="campoorden" type="hidden" id="campoorden" value="codser">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Servicios </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="82" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="412" height="22"><div align="left">
          <input name="txtcodser" type="text" id="txtcodser" onKeyPress="javascript: ue_mostrar(this,event);" style="text-align:center" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdenser" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Seleccione I.V.A. </div></td>
        <td height="22"><div align="left">
	<?php uf_select_iva(); ?> 
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
function ue_search()
{
	codivasel=cmbiva.value; //recibo el iva que requiero agregar al articulo//
	f   = document.formulario;
	fop = opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_tipo=fop.tipo.value;
	//alert(codivasel);
	if((ls_tipo=="REPDES")||(ls_tipo=="REPHAS"))
	{
		codestpro1="";
		codestpro2="";
		codestpro3="";
		codestpro4="";
		codestpro5="";
	}
	else
	{
		codestpro1 = fop.txtcodestpro1.value;
		codestpro2 = fop.txtcodestpro2.value;
		codestpro3 = fop.txtcodestpro3.value;
		codestpro4 = fop.txtcodestpro4.value;
		codestpro5 = fop.txtcodestpro5.value;
	}
	codser     = f.txtcodser.value;
	denser     = f.txtdenser.value;
	orden      = f.orden.value;
	campoorden = f.campoorden.value;
	ls_codunieje = opener.document.formulario.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_soc_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=SERVICIOS&codser="+codser+"&denser="+denser+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+
			  "&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&orden="+orden+
			  "&campoorden="+campoorden+"&tipo="+ls_tipo+"&codunieje="+ls_codunieje);
}

function ue_aceptar(as_codser,as_denser)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Servicio no est� en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	codivasel=cmbiva.value; //recibo el iva que requiero agregar al articulo//
	total		   = ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	rowservicios=opener.document.formulario.totrowservicios.value;
	for(j=1;(j<=rowservicios)&&(valido);j++)
	{
		codsergrid=eval("opener.document.formulario.txtcodser"+j+".value");
		if(codsergrid==as_codser)
		{
			alert("El Servicio ya est� en la solicitud !!!");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Servicios del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	
	for(j=1;(j<rowservicios)&&(valido);j++)
	{
		codser     = eval("opener.document.formulario.txtcodser"+j+".value");
		denser     = eval("opener.document.formulario.txtdenser"+j+".value");
		canser     = eval("opener.document.formulario.txtcanser"+j+".value");
		ls_codiva  = eval("opener.document.formulario.txtcodiva"+j+".value");
		ls_numsep  = eval("opener.document.formulario.hidnumsep"+j+".value");
		parametros = parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+"&txtcanser"+j+"="+canser+"&txtcodiva"+j+"="+ls_codiva+"&hidnumsep"+j+"="+ls_numsep;
	}
	totalservicios   = eval(rowservicios+"+1");
	parametros       = parametros+"&txtcodser"+rowservicios+"="+as_codser+"&txtdenser"+rowservicios+"="+as_denser+"&txtcanser"+rowservicios+"=0,00"+"&totalservicios="+totalservicios;
	
	total=ue_calcular_total_fila_opener("txtcodpro");
	opener.document.formulario.totrowproveedores.value=total;
	rowproveedores = opener.document.formulario.totrowproveedores.value;
	for(j=1;(j<=rowproveedores)&&(valido);j++)
	{ 
		codpro     = eval("opener.document.formulario.txtcodpro"+j+".value");
		nompro     = eval("opener.document.formulario.txtnompro"+j+".value");
		dirpro     = eval("opener.document.formulario.txtdirpro"+j+".value");
		telpro     = eval("opener.document.formulario.txttelpro"+j+".value");
		parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
	}
	parametros = parametros+"&totalproveedores="+rowproveedores;

	//--------------------------------------------------------------------------
	// Incorporamos los detalles existentes de las SEP en el formulario
	//--------------------------------------------------------------------------
	li_totrowsep = ue_calcular_total_fila_opener("txtnumsep");
	opener.document.formulario.totrowsep.value = li_totrowsep;
	for(j=1;(j<=li_totrowsep)&&(valido);j++)
	{ 
		ls_numsep = eval("opener.document.formulario.txtnumsep"+j+".value");
		ls_densep = eval("opener.document.formulario.txtdensep"+j+".value");
		ld_monsep = eval("opener.document.formulario.txtmonsep"+j+".value");
		ls_unieje = eval("opener.document.formulario.txtunieje"+j+".value");
		ls_denuni = eval("opener.document.formulario.txtdenuni"+j+".value");
		
		parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+j+"="+ls_denuni;
	}
	parametros = parametros+"&totalsep="+li_totrowsep+"&tipo=SC";

	
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_solicitud_cotizacion_ajax.php",true);
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
		parametros=parametros+"&codivasel="+codivasel;
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=AGREGARSERVICIOS"+parametros);
		opener.document.formulario.totrowservicios.value=totalservicios;
	}
}

function ue_aceptar_servicio_orden_compra(as_codser,as_denser,ai_precio,as_spg_cuenta,ai_existecuenta,as_denunimed)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Servicio no est� en el formulario
	//---------------------------------------------------------------------------------
	codivasel=cmbiva.value; //recibo el iva que requiero agregar al articulo//
	valido=true;
	total=ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	rowservicios=opener.document.formulario.totrowservicios.value;
	for(j=1;(j<=rowservicios)&&(valido);j++)
	{
		codsergrid=eval("opener.document.formulario.txtcodser"+j+".value");
		if(codsergrid==as_codser)
		{
			alert("El Servicio ya est� en la Orden de Compra");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Servicios del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	for(j=1;(j<rowservicios)&&(valido);j++)
	{
		codser	     	= eval("opener.document.formulario.txtcodser"+j+".value");
		denser       	= eval("opener.document.formulario.txtdenser"+j+".value");
		canser       	= eval("opener.document.formulario.txtcanser"+j+".value");
		preser       	= eval("opener.document.formulario.txtpreser"+j+".value");
		subtotser    	= eval("opener.document.formulario.txtsubtotser"+j+".value");
		carser          = eval("opener.document.formulario.txtcarser"+j+".value");
		totser          = eval("opener.document.formulario.txttotser"+j+".value");
		spgcuenta       = eval("opener.document.formulario.txtspgcuenta"+j+".value");
		ls_numsolord    = eval("opener.document.formulario.txtnumsolord"+j+".value");
		ls_coduniadmsep = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
		ls_denuniadmsep = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
		ls_denunimed    = eval("opener.document.formulario.txtdenunimed"+j+".value");
        ls_estpreunieje = eval("opener.document.formulario.txtestpreunieje"+j+".value");
 
		parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
				   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
				   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
				   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+				   
				   "&txtnumsolord"+j+"="+ls_numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
				   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
				   "&txtestpreunieje"+j+"="+ls_estpreunieje;
	}
	ls_estpreunieje = "";
	ls_codunieje    = opener.document.formulario.txtcoduniadm.value;
	if (ls_codunieje!="" && ls_codunieje!='----------' && ls_codunieje!='---')
	   {
	     ls_codestpro1   = opener.document.formulario.txtcodestpro1.value;
		 ls_codestpro2   = opener.document.formulario.txtcodestpro2.value;
		 ls_codestpro3   = opener.document.formulario.txtcodestpro3.value;
		 ls_codestpro4   = opener.document.formulario.txtcodestpro4.value;
		 ls_codestpro5   = opener.document.formulario.txtcodestpro5.value;
		 ls_estpreunieje = ls_codestpro1+ls_codestpro2+ls_codestpro3+ls_codestpro4+ls_codestpro5;
	   }
	totalservicios=eval(rowservicios+"+1");
	parametros=parametros+"&txtcodser"+rowservicios+"="+as_codser+"&txtdenser"+rowservicios+"="+as_denser+""+
			   "&txtcanser"+rowservicios+"=0,00"+"&txtpreser"+rowservicios+"="+ai_precio+"&txtsubtotser"+rowservicios+"=0,00"+
			   "&txtcarser"+rowservicios+"=0,00&txttotser"+rowservicios+"=0,00"+"&txtspgcuenta"+rowservicios+"="+as_spg_cuenta+
			   "&totalservicios="+totalservicios+"&txtdenunimed"+rowservicios+"="+as_denunimed+
			   "&txtestpreunieje"+rowservicios+"="+ls_estpreunieje;
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
	parametros=parametros+"&txtcodservic="+as_codser+"&totalcargos="+totalcargos;
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
	tipconpro=opener.document.formulario.tipconpro.value;
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total+
	           "&codprounidad="+codestpro1+codestpro2+codestpro3+codestpro4+codestpro5+
			   "&tipo=OC"+"&tipconpro="+tipconpro;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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
		parametros=parametros+"&codivasel="+codivasel;	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=AGREGARSERVICIOS"+parametros);
		opener.document.formulario.totrowservicios.value=totalservicios;
	}
}

function ue_aceptar_reportedesde(as_codser)
{
	opener.document.formulario.txtcodserdes.value=as_codser;
	close();
}

function ue_aceptar_reportehasta(as_codser)
{
	opener.document.formulario.txtcodserhas.value=as_codser;
	close();
}

function ue_close()
{
	close();
}
</script>
</html>
