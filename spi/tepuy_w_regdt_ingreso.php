<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<title>Entrada de Comprobante de Gastos</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
require_once("../shared/class_folder/ddlb_generic_bd.php");
require_once("class_folder/class_funciones_spi.php");
$io_function=new class_funciones();	
$io_include=new tepuy_include();	
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_msg = new class_mensajes();
$ddlb_operaciones=new ddlb_generic_bd($io_connect);
require_once("tepuy_spi_c_comprobante.php");
$in_classcmp=new tepuy_spi_c_comprobante();
require_once("../shared/class_folder/tepuy_c_seguridad.php");
$io_seguridad= new tepuy_c_seguridad();
$io_class_spi = new class_funciones_spi();

    /////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SPI";
	$ls_ventana="tepuy_spi_p_comprobante.php";
	$la_seguridad[1]=$ls_empresa;
	$la_seguridad[2]=$ls_sistema;
	$la_seguridad[3]=$ls_logusr;
	$la_seguridad[4]=$ls_ventana;
	//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
	$ls_documento=$_POST["txtdocumento"];
	$ls_cuentaplan=$_POST["txtcuenta"];
	$ls_denominacion=$_POST["txtdenominacion"];
	$ls_procedencia=$_POST["txtprocedencia"];
	$ls_descripcion=$_POST["txtdescripcion"];
	$ls_comprobante=$_POST["comprobante"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ld_fecha	   =$_POST["fecha"];
	$ls_tipo       =$_POST["tipo"];
	$ls_provbene   =$_POST["provbene"];
}
else
{
	$ls_operacion="";
	$ls_documento="000000000000001";
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ls_procedencia="SPICMP";
	$ls_descripcion="";
	$ls_comprobante=$_GET["comprobante"];
	$ls_proccomp   =$_GET["procede"];
	$ls_desccomp   =$_GET["descripcion"];
	$ld_fecha	   =$_GET["fecha"];
	$ls_tipo       =$_GET["tipo"];
	$ls_provbene   =$_GET["provbene"];
}

$_SESSION["fechacomprobante"] = $ld_fecha;

if($ls_operacion=="GUARDARPRE")
{
	$ls_comprobante=$_POST["comprobante"];
	$ld_fecha      =$_POST["fecha"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ls_provbene   =$_POST["provbene"];	
	$ls_tipo	   =$_POST["tipo"];
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
	}
	$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_proccomp,$ls_desccomp,&$ls_prov,&$ls_bene,$ls_tipo,1,$ls_codban,$ls_ctaban);
	
	$arr_cmp["comprobante"]=$ls_comprobante;
	$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
	$arr_cmp["fecha"]      =$ld_fecdb;
	$arr_cmp["procedencia"]=$ls_proccomp;
	$arr_cmp["descripcion"]=$ls_desccomp;
	$arr_cmp["proveedor"]  =$ls_prov;
	$arr_cmp["beneficiario"]=$ls_bene;
	$arr_cmp["tipo"]       =$ls_tipo;
	$arr_cmp["codemp"]     =$dat["codemp"];
	$arr_cmp["tipo_comp"]  =1;
	if($lb_valido)
	{
		$ls_cuenta      = $_POST["txtcuenta"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_denominacion= $_POST["txtdescripcion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacionpre= $_POST["ddlb_operacion"];
		$ld_monto       = $_POST["txtmonto"];
		$ldec_monto=str_replace(".","",$ld_monto);
		$ldec_monto=str_replace(",",".",$ldec_monto);
		//$in_classcmp->io_sql->begin_transaction();
		$lb_valido=$in_classcmp->uf_guardar_movimientos($arr_cmp,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacionpre,0,$ldec_monto,"C",$ls_codban,$ls_ctaban);
		if($lb_valido)
		{	
		     $lb_valido = $io_class_spi->uf_convertir_tepuycmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
			 if ($lb_valido)
		     {
		    	$lb_valido = $io_class_spi->uf_convertir_spidtcmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
		     }
		     if($lb_valido)
			 {
			    $lb_valido=$io_class_spi->uf_convertir_scgdtcmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
			 }
			 if ($lb_valido)
		     {
		      $in_classcmp->io_sql->commit();
		     }
		  	else
			  {
				$in_classcmp->io_sql->rollback(); 
 
			  }  
			 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			if($in_classcmp->io_int_spi->is_log_transacciones!="")
			{
				$ls_desc_event=$in_classcmp->io_int_spi->is_log_transacciones.",Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta;
			}
			else
			{
				$ls_desc_event="Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta." ; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
			}
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}
		
		else	
		{	
			$in_classcmp->io_sql->rollback(); 
		}
	} 
	else
	{
		$ls_cuenta      = $_POST["txtcuenta"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_denominacion= $_POST["txtdescripcion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacionpre= $_POST["ddlb_operacion"];
		$ld_monto       = $_POST["txtmonto"];
	}   
	?>
	<script language="javascript">
		f=opener.document.form1;
		f.operacion.value="CARGAR_DT";
		f.action="tepuy_spi_p_comprobante.php";
		f.submit();
	</script>
	<?      
}
?>
<form method="post" name="form1" action=""> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td colspan="2" class="titulo-celda">Entrada de Comprobante de Ingresos </td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<? print $ls_documento;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<? print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<? print $ls_procedencia;?>" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" value="<? print $ls_cuentaplan ?>" size="22" style="text-align:center" readonly="true"> 
    <a href="javascript:catalogo_cuentasSPI();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<? print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left">
      <?
		$ddlb_operaciones->uf_cargar_ddlb("operacion,denominacion","operacion","denominacion","spi_operaciones"," WHERE reservado=0","ddlb_operacion",250,$ls_operacion);
	  ?>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="javascript:uf_format(this);"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.png" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/eliminar.png" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><input name="operacion" type="hidden" id="operacion">
      <input name="comprobante" type="hidden" id="comprobante" value="<? print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<? print $ls_proccomp;?>">
      <input name="fecha" type="hidden" id="fecha" value="<? print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<? print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<? print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<? print $ls_desccomp;?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
  	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_descripcion=f.txtdescripcion.value;
	ls_procedencia=f.txtprocedencia.value;
	ls_documento=f.txtdocumento.value;
	ls_operacion=f.ddlb_operacion.value;
	ldec_monto=f.txtmonto.value;
	if((ls_cuenta!="")&&(ls_descripcion!="")&&(ls_procedencia!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
	f.operacion.value="GUARDARPRE";
	f.action="tepuy_w_regdt_ingreso.php";
	f.submit();
	}
	else
	{
		alert("Complete los detalles del movimiento");
	}
  }
  function uf_close()
  {
	  close()
  }
	
function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
	alert("Introduzca un numero comprobante valido");
	cmp.focus();
	}
	else
	{
	rellenar_cad(cmp.value,15,"doc");
	}
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;	
	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	if(campo=="doc")
	{
		document.form1.txtdocumento.value=cadena;
	}
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}
}

 function catalogo_cuentasSPI()
 {
   f=document.form1;
   pagina="tepuy_cat_ctasspi.php";
   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
 } 
 
function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}

function currencyFormat(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789-'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }
</script>
</html>