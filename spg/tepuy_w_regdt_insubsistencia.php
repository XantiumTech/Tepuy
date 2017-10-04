<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
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
.Estilo1 {font-weight: bold}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
$li_estmodest=$dat["estmodest"];
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$msg=new class_mensajes();
$siginc=new tepuy_include();
$con=$siginc->uf_conectar();
$fun=new class_funciones();
$io_sql=new class_sql($con);
require_once("tepuy_spg_c_mod_presupuestarias.php");
$in_classcmp=new tepuy_spg_c_mod_presupuestarias();
$int_fec=new class_fecha();
/////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	$li_estmodest=$dat["estmodest"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="tepuy_spg_p_insubsistencias.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventana;
//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
	$ls_documento=$_POST["txtdocumento"];
    $ls_estpro1=$_POST["codestpro1"];
	$ls_estpro2=$_POST["codestpro2"];
	$ls_estpro3=$_POST["codestpro3"];
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
	$ls_operacionpre=$_POST["txtoperacion"];
	$ls_codfuefin    = $_POST["hidcodfuefin"];
	$ls_coduniadm    = $_POST["hidcoduniadm"];
	if($li_estmodest==2)
	{
		$ls_estpro4=$_POST["codestpro4"];
		$ls_estpro5=$_POST["codestpro5"];
	}		
}
else
{
	$ls_operacion="";
	$ls_documento=$_GET["comprobante"];
    $ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
	if($li_estmodest==2)
	{
		$ls_estpro4="";
		$ls_estpro5="";
	}
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ls_procedencia="SPGINS";
	$ls_descripcion=$_GET["descripcion"];
	$ls_comprobante=$_GET["comprobante"];
	$ls_proccomp   =$_GET["procede"];
	$ls_desccomp   =$_GET["descripcion"];
	$ld_fecha	   =$_GET["fecha"];
	$ls_tipo       =$_GET["tipo"];
	$ls_provbene   =$_GET["provbene"];
	$ls_operacionpre=$_GET["txtoperacion"];
	$ls_codfuefin    = $_GET["codfuefin"];
	$ls_coduniadm    = $_GET["coduniadm"];
}

if($ls_operacion=="GUARDARPRE")
{
	$ls_codemp     =$dat["codemp"];
	$ls_comprobante=$_POST["comprobante"];
	$ld_fecha      =$_POST["fecha"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ls_provbene   =$_POST["provbene"];	
	$ls_tipo	   =$_POST["tipo"];
	$ls_bene="----------";
	$ls_prov="----------";
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	$lb_valido=$int_fec->uf_valida_fecha_periodo($ld_fecha,$ls_codemp);
	if(!($lb_valido))
	{
		$msg->message($int_fec->is_msg_error);
	}
	else
	{
		$in_classcmp->io_sql->begin_transaction();
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_proccomp,$ls_desccomp,&$ls_prov,
		                                               &$ls_bene,$ls_tipo,2,0,$ls_codfuefin,$ls_coduniadm);
		if(!$lb_valido)
		{
			$in_classcmp->io_sql->rollback();
			$msg->message($in_classcmp->is_msg_error);
		}
		else
		{
/*		    $ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
			$lb_valido=$in_classcmp->uf_update_bsf_tepuycmpmd(0,$ls_codemp,$ls_proccomp,$ls_comprobante,
				                                               $ld_fecdb,$la_security);*/
			if($lb_valido)
			{$in_classcmp->io_sql->commit();}
			else
			{$in_classcmp->io_sql->rollback();}
		}
		$arr_cmp["comprobante"]=$ls_comprobante;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
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
			$ls_est1        = $_POST["codestpro1"];
			$ls_est2        = $_POST["codestpro2"];
			$ls_est3        = $_POST["codestpro3"];
			if($li_estmodest==2)
			{
				$ls_est4        = $_POST["codestpro4"];
				$ls_est5        = $_POST["codestpro5"];
				$ls_est1=$fun->uf_cerosizquierda($ls_est1,20);
				$ls_est2=$fun->uf_cerosizquierda($ls_est2,6);
				$ls_est3=$fun->uf_cerosizquierda($ls_est3,3);
				$ls_est4=$fun->uf_cerosizquierda($ls_est4,2);
				$ls_est5=$fun->uf_cerosizquierda($ls_est5,2);
			}
			else
			{
				$ls_est4        = "00";
				$ls_est5        = "00";
			}
			$ls_documento   = $_POST["txtdocumento"];
			$ls_denominacion= $_POST["txtdenominacion"];
			$ls_procede     = $_POST["txtprocedencia"];
			$ls_operacionpre= $_POST["txtoperacion"];
			$ld_monto       = $_POST["txtmonto"];
			
			$ldec_monto=str_replace(".","",$ld_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$in_classcmp->io_sql->begin_transaction();
			$lb_valido=$in_classcmp->uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,
			                                                $ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,
															$ls_operacionpre,0,$ldec_monto,"P");
			if($lb_valido)
			{
			   $ls_codestpro=$ls_est1.$ls_est2.$ls_est3.$ls_est4.$ls_est5; 
			   $lb_valido=$in_classcmp->uf_convertir_tepuycmpmd($ls_codemp,$la_security);
			   if($lb_valido)
			   {													  
/*			     $lb_valido=$in_classcmp->uf_update_bsf_spgdtmpcmp($ldec_monto,$ls_codemp,$ls_procede,$ls_comprobante,$ld_fecdb,
			                                                       $ls_codestpro,$ls_cuenta,$ls_procede,$ls_documento,
                                                                   $ls_operacionpre,$la_security);*/
			   }													   
			   if($lb_valido)
			   {$in_classcmp->io_sql->commit();}
			   else
			   {$in_classcmp->io_sql->rollback();}
			}
			else
			{
				$in_classcmp->io_sql->rollback();
			}
		} 
		else
		{
			$ls_cuenta      = $_POST["txtcuenta"];
			$ls_est1        = $_POST["codestpro1"];
			$ls_est2        = $_POST["codestpro2"];
			$ls_est3        = $_POST["codestpro3"];
			if($li_estmodest==2)
			{
				$ls_est4        = $_POST["codestpro4"];
				$ls_est5        = $_POST["codestpro5"];
			}
			$ls_documento   = $_POST["txtdocumento"];
			$ls_denominacion= $_POST["txtdenominacion"];
			$ls_procede     = $_POST["txtprocedencia"];
			$ls_operacionpre= $_POST["txtoperacion"];
			$ld_monto       = $_POST["txtmonto"];
		}  
	}	
	?>
	<script language="javascript">
		f=opener.document.form1;
		f.operacion.value="CARGAR_DT";
		f.action="tepuy_spg_p_insubsistencias.php";
		f.submit();
	</script>	
	<?php      
}
?>
<form method="post" name="form1" action=""> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td colspan="2" class="titulo-celda">Entrada de Comprobante de Gastos </td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print $ls_documento;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>" onBlur="javascript: ue_validarcomillas(this)"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>"></td>
  </tr>
  <tr>
      <?php 
	  $li_estmodest  = $dat["estmodest"];
	  if($li_estmodest==1)
	  {
	  ?>    
	<td height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td>
      <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro2"] ; ?></div>      </td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="22" maxlength="6" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td>      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="22" maxlength="3" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
      </div></td>
	  <?php
	   }
	   else
	   {
	  ?>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td><input name="codestpro1" type="text" id="codestpro1" size="5" maxlength="2" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
        <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>
        <div align="left"> </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro2"] ; ?></div></td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="5" maxlength="2" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
        <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td><div align="left">
        <input name="codestpro3" type="text" id="codestpro3" size="5" maxlength="2" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro4"] ; ?></div></td>
    <td><div align="left">
        <input name="codestpro4" type="text" id="codestpro4" size="5" maxlength="2" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro5"] ; ?></div></td>
    <td><div align="left">
      <input name="codestpro5" type="text" id="codestpro5" size="5" maxlength="3" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
    </div></td>
    <?php 
	  }
	?>
  </tr>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" readonly="true" value="<?php print $ls_cuentaplan ;?>" size="22" style="text-align:center"> 
    <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left">
      <input name="txtoperacion" type="text" id="txtoperacion" style="text-align:center " size="22" maxlength="3" value ="<?php print $ls_operacionpre;?>" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto"    type="text"  id="txtmonto" style="text-align:right" size="22" onKeyPress="return(currency_Format(this,'.',',',event))"  onBlur="javascript:uf_format(this);"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.png" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/tools15/eliminar.png" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><input name="operacion"   type="hidden" id="operacion">
        <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
        <input name="procede"     type="hidden" id="procede"     value="<?php print $ls_proccomp;?>">
        <input name="fecha"       type="hidden" id="fecha"       value="<?php print $ld_fecha;?>">
        <input name="provbene"    type="hidden" id="provbene"    value="<?php print $ls_provbene;?>">
        <input name="tipo"        type="hidden" id="tipo"        value="<?php print $ls_tipo;?>">
        <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_desccomp;?>">
        <span class="Estilo1">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
        <input name="hidcodfuefin" type="hidden" id="hidcodfuefin" value="<?php print $ls_codfuefin ?>">
        <input name="hidcoduniadm" type="hidden" id="hidcoduniadm" value="<?php print $ls_coduniadm ?>">
        </span></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
  	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_estpro1=f.codestpro1.value;
	ls_estpro2=f.codestpro2.value;
	ls_estpro3=f.codestpro3.value;
	ls_descripcion=f.txtdescripcion.value;
	ls_procedencia=f.txtprocedencia.value;
	ls_documento=f.txtdocumento.value;
	ls_operacion=f.txtoperacion.value;
	ldec_monto=f.txtmonto.value;
	estmodest   = f.estmodest.value;
    if(estmodest==1)
	{
		if(uf_valida_campos())
		{
			f.operacion.value="GUARDARPRE";
			f.action="tepuy_w_regdt_insubsistencia.php";
			f.submit();
			}
		else
		{
			alert("Complete los detalles del movimiento");
		}
	}
	else
	{
		ls_estpro4=f.codestpro4.value;
		ls_estpro5=f.codestpro5.value;
		if(uf_valida_campos())
		{
			f.operacion.value="GUARDARPRE";
			f.action="tepuy_w_regdt_insubsistencia.php";
			f.submit();
		}
		else
		{
			alert("Complete los detalles del movimiento");
		}
	}	
  }
  function uf_close()
  {
	  close()
  }
	
  function uf_valida_campos()
  {
	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_estpro1=f.codestpro1.value;
	ls_estpro2=f.codestpro2.value;
	ls_estpro3=f.codestpro3.value;
	ls_descripcion=f.txtdescripcion.value;	
	ls_procedencia=f.txtprocedencia.value;
	ls_documento=f.txtdocumento.value;
	ls_operacion=f.txtoperacion.value;
	ldec_monto=f.txtmonto.value;
	if(ls_cuenta=="")
	{
		alert("Debe registrar la Cuenta de gasto");
		return false;
	}
	if(ls_estpro1=="")
	{
		alert("Debe completar la Estructura programatica ");
		return false;
	}
	if(ls_estpro2=="")
	{
		alert("Debe completar la Estructura Programatica ");
		return false;
	}
	if(ls_estpro3=="")
	{
		alert("Debe completar la Estructura Programatica ");
		return false;
	}
	if(ls_descripcion=="")
	{
		alert("Debe introducir la descripcion del movimiento");
		return false;
	}
	if(ls_procedencia=="")
	{	
		alert("Debe introducir la procedencia");
		return false;
	}
	if(ls_documento=="")
	{
		alert("Debe introducir el numero del documento");
		return false;
	}
	if(ls_operacion=="")
	{
		alert("Debe introducir la operación");
		return false;
	}
	if(ldec_monto=="")	
	{
		alert("Monto no puede ser 0");
		return false;
	}
	estmodest   = f.estmodest.value;
	if(estmodest==2)
	{
		ls_estpro4=f.codestpro4.value;
		ls_estpro5=f.codestpro5.value;
		if(ls_estpro4=="")
		{
			alert("Debe completar la Estructura programatica ");
			return false;
		}
		if(ls_estpro5=="")
		{
			alert("Debe completar la Estructura programatica ");
			return false;
		}
	}
   return true;
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

 function catalogo_cuentasSPG()
 {
   f=document.form1;
   codest1=f.codestpro1.value;
   codest2=f.codestpro2.value;
   codest3=f.codestpro3.value;
   estmodest   = f.estmodest.value;
   if(estmodest==1)
   {
	   if((codest1!="")&&(codest2!="")&&(codest3!=""))
	   {
		   pagina="tepuy_cat_ctasspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
	   }
	   else
	   {
		   alert("Debe completar la programatica");
	   }
	}  
	else
	{
	   codest4=f.codestpro4.value;
	   codest5=f.codestpro5.value;
	   if((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
	   {
		   pagina="tepuy_cat_ctasspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&hicodest4="+codest4
		   +"&hicodest5="+codest5;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
	   }
	   else
	   {
		   alert("Debe completar la programatica");
	   }
	}
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
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="tepuy_cat_public_estpro.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
		   alert("Seleccione la Estructura nivel 2");
		}
	}
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
			pagina="tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	codestpro4=f.codestpro4.value;
	denestpro4=f.denestpro4.value;
	codestpro5=f.codestpro5.value;
	denestpro5=f.denestpro5.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&
	   (codestpro4!="")&&(denestpro4!="")&&(codestpro5=="")&&(denestpro5==""))
	{
			pagina="tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
					+"&denestpro4="+denestpro4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="tepuy_cat_public_estprograma.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}

function currency_Format(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
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
   
function ue_validarcomillas(valor)
{
val = valor.value;
longitud = val.length;
texto = "";
textocompleto = "";
for(r=0;r<=longitud;r++)
{
texto = valor.value.substring(r,r+1);
if((texto != "'")&&(texto != '"')&&(texto != "\\"))
{
textocompleto += texto;
}
}
valor.value=textocompleto;
}
</script>
</html>