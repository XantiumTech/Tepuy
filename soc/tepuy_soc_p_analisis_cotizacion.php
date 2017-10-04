<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
$io_fun_compra = new class_funciones_soc();
require_once("class_folder/tepuy_soc_c_analisis_cotizacion.php");
$io_analisis_cotizacion = new tepuy_soc_c_analisis_cotizacion();

$io_fun_compra->uf_load_seguridad("SOC","tepuy_soc_p_analisis_cotizacion.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_compra->uf_select_config("SOC","REPORTE","FORMATO_ANACOT","tepuy_soc_rfs_analisis_cotizacion.php","C");
$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>An&aacute;lisis de Cotizaciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css"  rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/soc.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body onLoad='javascript=ue_cargar_grid();'>
<div align="center">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
	<tr>
	  <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="800" height="40"></td>
	</tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Orden de Compra -> <i>Cotizaciones</i></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	</table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7">&nbsp;</td>
    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
</td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--	<tr>
	  <td height="20" class="cd-menu" style="text-align:left "><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
	</tr>-->
	<tr>
	  <td height="36" bgcolor="#FFFFFF" class="toolbar"><label>
	  </label></td>
	</tr>
	<tr>
	  <td height="20" bgcolor="#FFFFFF" class="toolbar" style="text-align:left "><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a><a href="../soc/tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"></a></td>
	</tr>
  </table>
<p>&nbsp;</p>
<form name="formulario" id="formulario" method="post" action="" >
<?php 
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/06/2007			Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		/*Inicializando variables*/
		global $ls_numero,$ls_operacion,$ls_fecha,$ls_operacion,$ls_observacion,$ls_parametros;		
		global $io_funciondb, $ls_codemp,$ls_estatus;
		require_once("../shared/class_folder/class_funciones_db.php");
	    require_once("../shared/class_folder/tepuy_include.php");
	    $io_include	  = new tepuy_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_funciondb = new class_funciones_db($io_conexion); 		
				
		$ls_numero=$io_funciondb->uf_generar_codigo(true,$ls_codemp,"soc_analisicotizacion","numanacot");
		$ls_operacion="";
		$ls_fecha=date("d/m/Y");
		$ls_operacion="";
		$ls_observacion="";
		$ls_estatus="REGISTRO";
		$ls_parametros="";
	}
	
	function uf_load_parametros($ls_numero)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_parametros
		//		   Access: private
		//	  Description: Función que almacena los valores del analisis de cotizacion registrado para luego repintarlo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/11/2007			Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_parametros,$ls_numero,$ls_estatus,$ls_fecha, $ls_observacion;
		$ls_parametros ="&numanacot=".$ls_numero."&fecanacot=".$_POST["txtfecha"]."&obsana=".$_POST["txtobservacion"]."&numsolcot=".$_POST["txtnumsol1"]."&txttipsolcot1=".$_POST["txttipsolcot1"];
		//$ls_numero = $_POST["txtnumero"];
		$ls_estatus = $_POST["txtestatus"];
		$ls_fecha = $_POST["txtfecha"];
		$ls_observacion = $_POST["txtobservacion"];		
	}

if(!array_key_exists("operacion",$_POST))//si es la primera vez q abre la pagina o cuando se presiona nuevo, limpiamos todas las variables
{
	uf_limpiarvariables();
}
else
{
	$ls_operacion=$_POST["operacion"];
	if($ls_operacion=="GUARDAR")//La operacion indica que se va a guardar
	{
		$lb_valido=$io_analisis_cotizacion->uf_insert_update($ls_numero,$la_seguridad);		
		uf_load_parametros($ls_numero);
		//uf_limpiarvariables();
	}
	elseif($ls_operacion=="ELIMINAR")
	{
		$lb_valido=$io_analisis_cotizacion->uf_delete($la_seguridad);
		uf_limpiarvariables();
	}
}
?>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_compra);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<br>
	<table width="800" border="0" cellpadding="0" cellspacing="0" class="formato-blanco"  >
	  <tr>
		<td height="22" colspan="8" class="titulo-celdanew">An&aacute;lisis de Cotizaciones</td>
	  </tr>
	  <tr style="visibility:hidden">
		<td colspan="3">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select></td>
		<td width="127" height="22">&nbsp;</td>
		<td width="205">&nbsp;</td>
		<td width="184">&nbsp;</td>
		<td width="7">&nbsp;</td>
		<td width="111">&nbsp;</td>
	  </tr>
	  <tr>
	    <td height="22" colspan="2" style="text-align:right">Estatus</td>
	    <td height="22" colspan="6" style="text-align:left "><input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="70" readonly="readonly"/>      
      </tr>
	  <tr>
		<td height="22" colspan="2"><div align="right">Nro An&aacute;lisis&nbsp;</div></td>
		<td height="22" colspan="2" style="text-align:left ">
		  <input name="txtnumero" type="text" id="txtnumero"  style="text-align:center " value="<?php print $ls_numero ?>" size="20" maxlength="15"  read>
		<td height="22"><div align="right">Fecha&nbsp;</div></td>
		<td height="22" colspan="3">
		  
			<input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ls_fecha ?>" size="15" maxlength="10" onKeyPress="javascript:currencyDate(this)" datepicker="true">
		  <div align="left"></div>		  <div align="left">
          </div></td>
	  </tr>
	  <tr>
		<td height="22" colspan="2"><div align="right">Observaci&oacute;n&nbsp;</div></td>
		<td height="22" colspan="6" style="text-align:left "><div align="left">
		  <input name="txtobservacion" type="text" id="txtobservacion" value="<?php print $ls_observacion ?>" size="127" maxlength="254">
		  </div></td>
	  </tr>
	
	  <tr>
		<td colspan="8"><div align="center">	</div></td>
	  </tr>
	  
	  <tr style="text-align:left ">
		<td colspan="8">		</td>
	  </tr>
	  <tr style="text-align:left ">
		<td colspan="8">		</td>
	  </tr>
	  <tr>
	    <td colspan="8">		</td>
      </tr>
	  <tr>
	    <td colspan="8">		</td>
      </tr>
	  <tr>
	    <td colspan="8" align="center">
		  <div align="left"><a href="javascript:ue_catalogocotizaciones();"><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Cotizaciones' width='20' height='20' border='0'>Agregar Cotizaciones</a>		  
		    <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>" />
		  </div>
	    <div id="cotizaciones">	</div>		</td>
      
	  </tr>
	  <tr>
	    <td colspan="8" align="center">&nbsp;	      </td>
      </tr>
	  <tr>
	    <td colspan="8" align="center"></td>
      </tr>
	  <tr>
	    <td width="62">&nbsp;        </td>
        <td width="11">&nbsp;</td>
        <td width="91">&nbsp;</td>
        <td><input name="Submit2"  type="button" class="boton" value="Análisis de Precios" width="99" style="text-align:center; cursor:auto "  title="Haga click para visualizar el análisis de precios ofrecidos por los proveedores" onClick="javascript=ue_analisis('analisis_precios');"></td>
        <td><input name="Submit3"  type="button" class="boton" value="An&aacute;lisis Cualitativo Proveedores" width="99" style="text-align:center; cursor:auto " title="Haga click para visualizar el análisis cualitativo de los proveedores" onClick="javascript=ue_analisis('analisis_cualitativo');"></td>
        <td><input name="Submit32"  type="button" class="boton" value="An&aacute;lisis Cualitativo Items" width="99" style="text-align:center; cursor:auto " title="Haga click para visualizar el an&aacute;lisis cualitativo de los proveedores por Item" onClick="javascript=ue_analisis('analisis_items');"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">
		<div align="center" id="items"></div>		</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="8">&nbsp;</td>
      </tr>
	  <tr>
		<td colspan="8"></td>
	  </tr>
	</table>
	<!-- Hiddens--->
	<!--input type="hidden" name="totalcotizaciones" -->
	<input type="hidden" name="operacion" value="<?PHP print $ls_operacion?>">
	<input type="hidden" name="catalogo" >
	<input type="hidden" name="parametros" value="<?PHP print $ls_parametros?>">
	
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }

function rellenar_cad(cadena,longitud)
{
  var mystring=new String(cadena);
  cadena_ceros="";
  lencad=mystring.length;
  total=longitud-lencad;
  for (i=1;i<=total;i++)
  {
	cadena_ceros=cadena_ceros+"0";
  }
  cadena=cadena_ceros+cadena;
  document.form1.txtnumero.value=cadena;
}

function ue_nuevo()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
	  location.href="tepuy_soc_p_analisis_cotizacion.php";
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_catalogocotizaciones()
{
	f = document.form1;	
	pagina="tepuy_soc_cat_cotizaciones_para_analisis.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=500,resizable=yes,location=no,left=0,top=0,status=yes");
}

function ue_delete_cotizacion(fila)
{
	f=document.formulario;	
	if(confirm("¿Está seguro de eliminar la cotización?"))
	{
		parametros="";
		//Determinando el total de filas
		total=ue_calcular_total_fila_local("txtnumcot");
		//f.totalcotizaciones.value=f.totalcotizaciones.value-1;		
			//---------------------------------------------------------------------------------
			// Cargar las las cotizaciones sin la fila eliminada
			//---------------------------------------------------------------------------------
			totalcotizaciones=ue_calcular_total_fila_local("txtnumcot");		
			li_i=0;		
			for(j=1; j<=totalcotizaciones ; j++)
			{
				if(j!=fila)
				{
					li_i = li_i+1;
					numsol=eval("document.formulario.txtnumsol"+j+".value");
					numcot=eval("document.formulario.txtnumcot"+j+".value");
					nompro=eval("document.formulario.txtnompro"+j+".value");
					fecha=eval("document.formulario.txtfecha"+j+".value");
					monto=eval("document.formulario.txtmonto"+j+".value");
					iva=eval("document.formulario.txtiva"+j+".value");
					codpro=eval("document.formulario.txtcodpro"+j+".value");
					tipsolcot=eval("document.formulario.txttipsolcot"+j+".value");
					parametros=parametros+"&txtnumsol"+li_i+"="+numsol+"&txtnumcot"+li_i+"="+numcot+"&txtnompro"+li_i+"="+nompro+"&txtcodpro"+li_i+"="+codpro+
							   "&txtfecha"+li_i+"="+fecha+"&txtmonto"+li_i+"="+monto+"&txtiva"+li_i+"="+iva+"&txttipsolcot"+li_i+"="+tipsolcot;						  
				}
				else//Este else se hace para que si se elimina una coizacion se elimine tambien del grid de bienes/servicios
				{
					totalitems=f.totalitems.value;
					numcot=eval("document.formulario.txtnumcot"+j+".value");
					codpro=eval("document.formulario.txtcodpro"+j+".value");
					for(li_k=1;li_k<=totalitems;li_k++)
					{
						numcotsele=eval("document.formulario.txtnumcotsele"+li_k+".value");
						codprosele=eval("document.formulario.txtcodproselec"+li_k+".value");
						if((numcot==numcotsele) && (codpro==codprosele))
						{
							eval("document.formulario.txtnomproitem"+li_k+".value=''");
							eval("document.formulario.txtcodproselec"+li_k+".value=''");
							eval("document.formulario.txtcanselec"+li_k+".value=''");
							eval("document.formulario.txtpreuniselec"+li_k+".value=''");
							eval("document.formulario.txtivaselec"+li_k+".value=''");
							eval("document.formulario.txtmonselec"+li_k+".value=''");
							eval("document.formulario.txtobservacion"+li_k+".value=''");
							eval("document.formulario.txtnumcotsele"+li_k+".value=''");		
						}
					}
				}
			}			
			parametros=parametros+"&totalcotizaciones="+li_i;	
			if(li_i==1)//En caso de que no queden mas cotizaciones en el grid, la tabla de items desaparece
			{
				divgrid = document.getElementById('items');
				divgrid.innerHTML="";
			}
			if(parametros!="")
			{
				divgrid = document.getElementById("cotizaciones");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_soc_c_analisis_cotizacion_ajax.php",true);
				ajax.onreadystatechange=function()
				 {
					if (ajax.readyState==4) 
					{
						divgrid.innerHTML = ajax.responseText;
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARCOTIZACIONES"+parametros);
			}
		}
}
function uf_ver_cotizacion(li_i)
{
	f = document.form1;	
	numsolcot=eval("document.formulario.txtnumsol"+li_i+".value");
	numcot=eval("document.formulario.txtnumcot"+li_i+".value");
	codpro=eval("document.formulario.txtcodpro"+li_i+".value");
	nompro=eval("document.formulario.txtnompro"+li_i+".value");
	pagina="tepuy_soc_cat_detalle_cotizaciones.php?numsolcot="+numsolcot+"&numcot="+numcot+"&codpro="+codpro+"&nompro="+nompro;
	window.open(pagina,li_i,"menubar=no,toolbar=no,scrollbars=yes,width=890,height=400,resizable=yes,left=100,top=50,status=no");
}


function ue_cargar_grid()
{
		parametros = document.formulario.parametros.value;
		if(parametros == "")
			{
			// Div donde se van a cargar los resultados
			divgrid = document.getElementById("cotizaciones");
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/tepuy_soc_c_analisis_cotizacion_ajax.php",true);
			ajax.onreadystatechange=function()
			{
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
						}//1
						else
						{
							if(ajax.status==404)
							{
								divgrid.innerHTML = "La página no existe";
							}//
							else
							{//mostramos el posible error     
								divgrid.innerHTML = "Error:".ajax.status;
							}
						}//					
					}//
				}	//	
			}	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("proceso=LIMPIARCOTIZACIONES");
		}
}

function ue_analisis(catalogo)//abre el catalogo del analisis de precios
{
	f=document.formulario;
	totalcotizaciones=ue_calcular_total_fila_local("txtnumcot") - 1;
	if(totalcotizaciones >= 1)
	{
		tipsolcot=f.txttipsolcot1.value;
		parametros="";
		for(li_i=1;li_i<=totalcotizaciones;li_i++)
		{
			numsolcot=eval("document.formulario.txtnumsol"+li_i+".value");
			numcot=eval("document.formulario.txtnumcot"+li_i+".value");
			codpro=eval("document.formulario.txtcodpro"+li_i+".value");
			nompro=eval("document.formulario.txtnompro"+li_i+".value");
			parametros=parametros+"&codpro"+li_i+"="+codpro+"&nompro"+li_i+"="+nompro+"&numsolcot"+li_i+"="+numsolcot+"&numcot"+li_i+"="+numcot;
		}
		parametros=parametros+"&tipsolcot="+tipsolcot+"&totalcotizaciones="+totalcotizaciones;
		if(catalogo=="analisis_precios")//
		{
			pagina="tepuy_soc_cat_analisis_precios.php?"+parametros;
		}
		else if(catalogo=="analisis_cualitativo")
		{
			pagina="tepuy_soc_cat_analisis_cualitativo.php?"+parametros;
		}			
		else
		{
			pagina="tepuy_soc_cat_analisis_cualitativo_items.php?"+parametros;
		}	
		
		window.open(pagina,catalogo,"menubar=no,toolbar=no,scrollbars=yes,width=890,height=400,resizable=yes,left=100,top=50,status=no");
	}
	else
	{
		alert("Seleccione al menos una cotización para el análisis");
	}	
}

function ue_catalogo_proveedores(li_item)//abre el catalogo de proveedores por bien/servicio
{
	f=document.formulario;
	numsolcot=f.txtnumsol1.value;
	coditem=eval("document.formulario.txtcoditem"+li_item+".value");
	tipsolcot=f.txttipsolcot1.value;
	totalcotizaciones=ue_calcular_total_fila_local("txtnumcot");
	parametros="";
	for(li_i=1;li_i<=totalcotizaciones;li_i++)
	{
		codpro=eval("document.formulario.txtcodpro"+li_i+".value");
		parametros=parametros+"&codpro"+li_i+"="+codpro;
	}	
	parametros=parametros+"&numsolcot="+numsolcot+"&coditem="+coditem+"&item="+li_item+"&tipsolcot="+tipsolcot+"&totalcotizaciones="+totalcotizaciones;
	pagina="tepuy_soc_cat_proveedor_para_analisis.php?"+parametros;
	window.open(pagina,"proveedores","menubar=no,toolbar=no,scrollbars=yes,width=890,height=400,resizable=yes,left=100,top=50,status=no");	
}
function ue_guardar()//Funcion que se encarga de guardar el analisis de cotizacion
{
	f=document.formulario;
	totalcotizaciones=ue_calcular_total_fila_local("txtnumcot");
	totalitem=ue_calcular_total_fila_local("txtcoditem");
	lb_valido=true;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_catalogo=f.catalogo.value;
	
	if(((lb_catalogo=="T")&&(li_cambiar==1))||(lb_catalogo=="")&&(li_incluir==1))
	{
		if(f.txtnumero.value=="")
		{
			alert("El número de análisis no puede estar vacío");
			lb_valido=false;
		}
		else if(f.txtfecha.value=="")
		{
			alert("La fecha no puede esta vacía");
			lb_valido=false;
		}
		else if(f.txtobservacion.value=="")
		{
			//alert("La Observación no puede estar vacía");
			//lb_valido=false;
		}
		else if(totalcotizaciones==1)
		{
			alert("Seleccione al menos una cotización");
			lb_valido=false;
		}
		else 
		{
			for(li_i=1;li_i<totalitem;li_i++)
			{
				txtnompro=eval("f.txtnomproitem"+li_i+".value");
				txtobs=eval("f.txtobservacion"+li_i+".value");
				txtitem=eval("f.txtnomitem"+li_i+".value");
				if(txtnompro=="")
				{
					alert("Debe seleccionar un proveedor para el item "+txtitem+"");
					lb_valido=false;
					break;
				}
				else if(txtnompro!="" && txtobs=="")
				{
					alert("El campo observacion del ítem "+txtitem+" no puede estar vacío");
					lb_valido=false;
					break;
				}
			}
		}
		
		if(lb_valido)
		{
			f.operacion.value="GUARDAR";
			f.action="tepuy_soc_p_analisis_cotizacion.php";
			f.submit();
		}	
	}
}


function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_soc_cat_analisis_cotizacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		if(confirm("Esta Seguro de Eliminar Este  Análisis de Cotización.?"))
		{
			f.operacion.value="ELIMINAR"
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
		
}

function ue_imprimir()
{	
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_numanacot=f.txtnumero.value;
		ls_tipsolcot=f.txttipsolcot1.value;
		ls_fecha=f.txtfecha.value;
		ls_observacion=f.txtobservacion.value;
		tiporeporte= f.cmbbsf.value;
		 ls_formato   = f.formato.value;
		window.open("reportes/"+ls_formato+"?numanacot="+ls_numanacot+"&tipsolcot="+ls_tipsolcot+"&fecha="+ls_fecha+"&tiporeporte="+tiporeporte+"&observacion="+ls_observacion,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=1000,height=800,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
	
}

function ue_recargar_pagina()
{
	parametros = document.formulario.parametros.value;	
	document.formulario.catalogo.value = "T";
	// Div donde se van a cargar los resultados	
		divgrid1 = document.getElementById("cotizaciones");
	// Instancia del Objeto AJAX
		ajax1=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax1.open("POST","class_folder/tepuy_soc_c_analisis_cotizacion_ajax.php",true);
		ajax1.onreadystatechange=function()
		{
			if(ajax1.readyState==1)
			{
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax1.readyState==4)
				{
					if(ajax1.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid1.innerHTML = ajax1.responseText
					}
					else
					{
						if(ajax1.status==404)
						{
							divgrid1.innerHTML = "La página no existe";
						}
						else
						{//mostramos el posible error     
							divgrid1.innerHTML = "Error:".ajax1.status;
						}
					}
					
				}
			}
		}
		ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax1.send("proceso=CARGARCOTIZACIONES"+parametros);
		//opener.document.formulario.totrowbienes.value=totalbienes;
		
		// Div donde se van a cargar los resultados	
		divgrid2 = document.getElementById("items");
		// Instancia del Objeto AJAX
		ajax2=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax2.open("POST","class_folder/tepuy_soc_c_analisis_cotizacion_ajax.php",true);
		ajax2.onreadystatechange=function()
		{
			if(ajax2.readyState==1)
			{
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax2.readyState==4)
				{
					if(ajax2.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid2.innerHTML = ajax2.responseText
					}
					else
					{
						if(ajax2.status==404)
						{
							divgrid2.innerHTML = "La página no existe";
						}
						else
						{//mostramos el posible error     
							divgrid2.innerHTML = "Error:".ajax2.status;
						}
					}
					
				}
			}
		}
		ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax2.send("proceso=CARGARITEMS"+parametros);
		//opener.document.formulario.totrowbienes.value=totalbienes;
}	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<?php
	if(($ls_operacion=="GUARDAR"))
	{
		print "<script language=JavaScript>";
		print "   ue_recargar_pagina();";
		print "</script>";
	}
?>
</html>
