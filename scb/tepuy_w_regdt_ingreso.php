<?php
session_start();
$dat=$_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
  	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php

require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$msg          = new class_mensajes();
$siginc       = new tepuy_include();
$con          = $siginc->uf_conectar();
$fun          = new class_funciones();
$io_sql       = new class_sql($con);
$arre         = $_SESSION["la_empresa"];
$ls_empresa   = $arre["codemp"];

if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
	//$ls_documento=$_POST["txtdocumento"];
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
	$ls_mov_document=$_POST["mov_document"];
	$ls_mov_procede=$_POST["procede"];
	$ld_fecha=$_POST["fecha"];
	$ls_provbene=$_POST["provbene"];
	$ls_tipo=$_POST["tipo"];
	$ls_mov_descripcion=$_POST["descripcion"];
	$ls_codban=$_POST["codban"];
	$ls_ctaban=$_POST["ctaban"];
	$ls_cuenta_scg=$_POST["cuenta_scg"];
	$ls_codope=$_POST["mov_operacion"];
	$ldec_monto_mov=$_POST["monto"];
	$ldec_objret=$_POST["objret"];
	$ldec_retenido=$_POST["retenido"];
	$ls_chevau=$_POST["chevau"];
	$li_estint=$_POST["estint"];
	$li_cobrapaga=$_POST["cobrapaga"];
	$ls_estbpd=$_POST["estbpd"];
	$ls_nomproben=$_POST["txtnomproben"];
	$ls_estmov=$_POST["estmov"];
	$ls_codconmov=$_POST["codconmov"];
	$ls_estreglib=$_POST["tip_mov"];
	$ls_opener   =$_POST["opener"];
	$ls_estdoc   =$_POST["estdoc"];
	$ls_afectacion =$_POST["txtafectacion"];
	$ls_codfuefin =$_POST["codfuefin"];
}
else
{
	$ls_operacion="";
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ls_procedencia="SCBMOV";
	$ls_mov_document=$_GET["mov_document"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_provbene=$_GET["provbene"];
	$ls_tipo=$_GET["tipo"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_descripcion=$ls_mov_descripcion;
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$ldec_objret=$_GET["objret"];
	$ldec_retenido=$_GET["retenido"];
	$ls_chevau     =$_GET["chevau"];
	$li_estint     =$_GET["estint"];
	$li_cobrapaga  =$_GET["cobrapaga"];
	$ls_estbpd     =$_GET["estbpd"];
	$ls_nomproben  =$_GET["txtnomproben"];
	$ls_estmov     =$_GET["estmov"];
	$ls_codconmov  =$_GET["codconmov"];
	$ls_estreglib  =$_GET["tip_mov"];
	$ls_opener     =$_GET["opener"];
	$ls_estdoc     =$_GET["estdoc"];
	$ls_afectacion =$_GET["afectacion"];
	$ls_codfuefin =$_GET["codfuefin"];
}
if ($ls_codfuefin=="")
   {
     $ls_codfuefin="--";
   }

$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB",$ls_opener,$ls_permisos,&$la_seguridad,$la_permisos);

require_once("tepuy_scb_c_movbanco.php");
$in_classmovbanco=new tepuy_scb_c_movbanco($la_seguridad);

if($ls_operacion=="GUARDAR")
{
	$ldec_monto = $_POST["txtmoning"];
	$ls_estmov  = "N";

	if($ls_tipo=="P")
	{
		$ls_codpro =$ls_provbene;
		$ls_cedbene="----------";
	}
	else
	{
		$ls_cedbene=$ls_provbene;
		$ls_codpro ="----------";
	}
	
	$in_classmovbanco->io_sql->begin_transaction();
	
	$lb_valido                  = $in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret,$ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipo,$ls_codfuefin);
	$arr_movbco["codban"]       = $ls_codban;
	$arr_movbco["ctaban"]       = $ls_ctaban;
	$arr_movbco["mov_document"] = $ls_mov_document;
	$ld_fecdb                   = $fun->uf_convertirdatetobd($ld_fecha);
	$arr_movbco["codope"]       = $ls_codope;
	$arr_movbco["fecha"] 		= $ld_fecha;
	$arr_movbco["codpro"]		= $ls_codpro;
	$arr_movbco["cedbene"]		= $ls_cedbene;
	$arr_movbco["monto_mov"]	= $ldec_monto_mov;
	$arr_movbco["objret"]   	= $ldec_objret;
	$arr_movbco["retenido"] 	= $ldec_retenido;
	$arr_movbco["estmov"]		= $ls_estmov;
	$ls_cuenta					= $_POST["cuenta_ingreso"];
	$ld_monto       			= $_POST["txtmoning"];
	$ldec_monto					= str_replace(".","",$ld_monto);
	$ldec_monto					= str_replace(",",".",$ldec_monto);
	if($lb_valido)
	{
		$ls_operacioncon="H";
		if($li_cobrapaga==0)
			$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procedencia,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret,false,'00000');
		$ls_cuenta      = $_POST["cuenta_scg"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_denominacion= $_POST["txtdescripcion"];
		$ls_operacioncon= "D";
	
		$ld_mondeb      = $_POST["monto"];
		
		if($lb_valido)
		{
			$lb_valido    = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procedencia,$ls_descripcion,$ls_mov_document,$ls_operacioncon,$ld_mondeb,$ldec_objret,true,'00000');
			$ls_spicuenta = $_POST["txtcuenta"];
			$ls_desmov    = $_POST["txtdescripcion"];
			$ls_operacion = $_POST["txtafectacion"];
			$ldec_monto   = $_POST["txtmoning"];
			$ldec_monto   = str_replace(".","",$ldec_monto);
			$ldec_monto   = str_replace(",",".",$ldec_monto);

			$lb_valido=$in_classmovbanco->uf_procesar_dt_ingreso($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_spicuenta,$ls_mov_document,$ls_desmov,$ls_procedencia,$ldec_monto,$ls_operacion);

			if($lb_valido)
			{
				$in_classmovbanco->io_sql->commit();
				$ls_estdoc='C';
				if($li_cobrapaga==0)
					$ls_cadena="f.ddlb_spi.value=0";
				else
					$ls_cadena="f.ddlb_spi.value=1";
				?>
				<script language="javascript">
					f=opener.document.form1;
					f.operacion.value="CARGAR_DT";
					f.status_doc.value='C';//Cambio estatus a actualizable
					f.action="<?php print $ls_opener;?>";
					
					f.submit();
				</script>	
				<?php
			}
			else
			{
				$in_classmovbanco->io_sql->rollback();
				$msg->message($in_classmovbanco->is_msg_error);
			}
		}
		else
		{
			$msg->message($in_classmovbanco->is_msg_error);
			$in_classmovbanco->io_sql->rollback();
		}				
	} 	
	else
	{
		$msg->message($in_classmovbanco->is_msg_error);
		$in_classmovbanco->io_sql->rollback();
		?>
		<script language="javascript">
			close();
		</script>	
		<?php
	}			
	
}
switch ($ls_operacion) {
   case 'AAP':
       $ls_apertura="selected";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'AU':
       $ls_apertura="";
       $ls_aumento="selected";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'DI':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="selected";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado=""; 
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
	case 'PC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="selected";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CS':   
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="selected";
	   $ls_compromisogastocausado="";	   
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CG': 
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'GC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="selected";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;   
	case 'CP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="selected";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'PG':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="selected";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CCP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="selected";	   	   	   
	   break;
    default:
	   $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
       

}
?>
<form method="post" name="form1" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="2" class="titulo-celda">Entrada de Detalle de Ingreso </td>
  </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450" height="22"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print $ls_mov_document;?>" readonly></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td height="22"><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td height="22"><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly></td>
  </tr>
   <tr>
    <td height="22"><!--div align="right"><?php //print $dat["nomestpro1"];  ?></div></td-->
    <!--td>
      <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td><div align="right"><?php //print $dat["nomestpro2"] ; ?></div>      </td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="22" maxlength="6" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td><div align="right"><?php //print $dat["nomestpro3"] ; ?></div></td>
    <td>      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="22" maxlength="3" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
      </div></td>
  </tr-->
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td height="22"><input name="txtcuenta" type="text" id="txtcuenta" size="22" style="text-align:center"> 
    <a href="javascript:catalogo_cuentasSPI();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td height="22"><div align="left">
      <input name="txtafectacion" type="text" id="txtafectacion" value="<?php print $ls_afectacion?>" size="8" style="text-align:center" readonly>
</div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td height="22"><input name="txtmoning" type="text" id="txtmoning" style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))" > 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.png" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/tools15/eliminar.png" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td height="22"><input name="txtcuentascg" type="hidden" id="txtcuentascg">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
	  <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="operacion" type="hidden" id="operacion">
      <input name="mov_document" type="hidden" id="mov_document" value="<?php print $ls_mov_document;?>">
      <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
      <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
      <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
      <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope;?>">
      <input name="txtnomproben" type="hidden" id="txtnomproben" value="<?php print $ls_nomproben;?>">
      <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
      <input name="objret" type="hidden" id="objret" value="<?php print $ldec_objret;?>">
      <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_retenido;?>">
      <input name="chevau" type="hidden" id="chevau" value="<?php print $ls_chevau;?>">
      <input name="estint" type="hidden" id="estint" value="<?php print  $li_estint;?>">
      <input name="cobrapaga" type="hidden" id="cobrapaga" value="<?php print $li_cobrapaga;?>">
      <input name="estbpd" type="hidden" id="estbpd" value="<?php print $ls_estbpd;?>">
      <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_estreglib;?>">
      <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
      <input name="estdoc" type="hidden" id="estdoc" value="<?php print $ls_estdoc;?>">
	  <input name="cuenta_ingreso" type="hidden" id="cuenta_ingreso">
	  <input name="codfuefin" type="hidden" id="codfuefin" value="<?php print $ls_codfuefin;?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
  	f            = document.form1;
	ls_numdoc    = f.txtdocumento.value;
	ls_procede   = f.txtprocedencia.value;
	ls_cuenta    = f.txtcuenta.value;
	ls_operacion = f.txtafectacion.value;
	ldec_monto   = f.txtmoning.value;
	if((ls_numdoc!="")&&(ls_procede!="")&&(ls_cuenta!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
		f.operacion.value="GUARDAR";
		f.submit();
  	}
	else
	{
		alert("Complete todos los datos");
	}
  }
  
  function uf_close()
  {
	  close()
  }
	
 function agregar_scg(ls_cuenta,ls_descripcion,ls_documento,ldec_monto,ls_procede,ls_debhab)
 {
	f=document.form1;
	fop=opener.document.form1;
	li_total =fop.totcon.value;
	li_last  =fop.lastscg.value;	
  	li_newrow= parseInt(li_last,10)+1;
	ls_cuenta=f.txtcuentascg.value;
	lb_valido=false;
	for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
	{
		
		ls_cuenta_opener=eval("fop.txtcontable"+li_i+".value");
		if(ls_cuenta==ls_cuenta_opener)
		{
			ldec_monto_actual=eval("fop.txtmontocont"+li_i+".value");	
			while(ldec_monto_actual.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto_actual=ldec_monto_actual.replace(".","");
			}
			ldec_monto_actual=ldec_monto_actual.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			ldec_monto_nuevo=parseFloat(parseFloat(ldec_monto) + parseFloat(ldec_monto_actual));
			ldec_monto_nuevo=uf_convertir(ldec_monto_nuevo);
			eval("fop.txtmontocont"+li_i+".value='"+ldec_monto_nuevo+"'");	
			lb_valido=true;
		}
	}
	if((li_newrow<=li_total))
	{
		if(!lb_valido)
		{
		eval("fop.txtcontable"+li_newrow+".value='"+ls_cuenta+"'");
		eval("fop.txtdesdoc"+li_newrow+".value='"+ls_descripcion+"'");
		eval("fop.txtdocscg"+li_newrow+".value='"+ls_documento+"'");
		eval("fop.txtmontocont"+li_newrow+".value='"+ldec_monto+"'");
		eval("fop.txtdebhab"+li_newrow+".value='"+ls_debhab+"'");
		eval("fop.txtprocdoc"+li_newrow+".value='"+ls_procede+"'");
		fop.lastscg.value=li_newrow;
		}
		uf_calcular_montoscg();
	}
	else
	{
		alert("Debe agregar mas filas a la tabla");
	}
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
   	 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=770,height=400,resizable=yes,location=no");
 }
 
function catalogo_estpro1()
{
	   pagina="tepuy_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="tepuy_cat_public_estpro.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

   function  uf_cambiar()
	{
		f=document.form1;
		fop=opener.document.form1;
		li_newtotal=f.totalpre.value;
		fop.totpre.value=li_newtotal;
		fop.operacion.value="RECARGAR"
		fop.submit();
		
	}
function uf_calcular_montoscg()
  {
  	f=document.form1;
	ldec_mondeb=0;
	ldec_monhab=0;
	li_total=fop.lastscg.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
			ls_debhab=eval("fop.txtdebhab"+li_i+".value");
			ldec_monto=eval("fop.txtmontocont"+li_i+".value");	
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			if(ls_debhab=="D")
			{
				ldec_mondeb=parseFloat(ldec_mondeb)+parseFloat(ldec_monto);
				
			}
			else
			{
				ldec_monhab=parseFloat(ldec_monhab) + parseFloat(ldec_monto);
				
			}
			
	}
	ldec_diferencia=parseFloat(ldec_mondeb)-parseFloat(ldec_monhab);
	ldec_mondeb=uf_convertir(ldec_mondeb);
	fop.txtdebe.value=ldec_mondeb;	
	ldec_monhab=uf_convertir(ldec_monhab);
	fop.txthaber.value=ldec_monhab;	
	ldec_diferencia=uf_convertir(ldec_diferencia);
	fop.txtdiferencia.value=ldec_diferencia;	
  }
  
 /* function uf_calcular_montospg()
  {
  	f=document.form1;
	ldec_monspg=0;
	li_total=fop.lastspg.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
			ldec_monto=eval("fop.txtmoning"+li_i+".value");	
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			ldec_monspg=parseFloat(ldec_monspg)+parseFloat(ldec_monto);
	}
	ldec_monspg=uf_convertir(ldec_monspg);
	fop.totspg.value=ldec_monspg;		
	
  }*/
  
   function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }
   
   function uf_validar_cantidad()
   {
		f=opener.document.form1;
		f2=document.form1;
		ldec_monto_mov     = parseFloat(uf_convertir_monto(f.txtmonto.value));
		ldec_totspi        = parseFloat(uf_convertir_monto(f.totspgi.value));
		ldec_monto_guardar = parseFloat(uf_convertir_monto(f2.txtmoning.value));
		if((ldec_monto_guardar + ldec_totspi) > ldec_monto_mov)
		{
			alert("El monto total del movimiento de ingreso supera el monto total");
			f2.txtmoning.value="0,00";
		}
		
   }  
</script>
</html>
