<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco = new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_p_movbanco.php",$ls_permisos,&$la_seguridad,$la_permisos);
//$ls_reporte		   = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","tepuy_scb_rpp_voucher_pdf.php","C");
$ls_reporte		   = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","tepuy_scb_rpp_voucher_barinas.php","C");
$ls_reporteanulado = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER_ANULADO","tepuy_scb_rpp_voucher_anulado.php","C");
$ls_reporte_carta  = $io_fun_banco->uf_select_config("SCB","REPORTE","CARTA_ORDEN","tepuy_scb_rpp_cartaorden_pdf.php","C");
//$ls_reporte1   = "tepuy_cheque.php";
$ls_reporte1	 = "tepuy_scb_rpp_voucher_barinas.php";
$ls_reporte2	 = "tepuy_cheque.php";
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
<title>Movimiento de Banco</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<body onUnload="javascript:uf_valida_cuadre();">
<script language="javascript">
	function uf_valida_cuadre()
	{
		f=document.form1;
		ldec_diferencia=f.txtdiferencia.value;
		ldec_monto=f.txtmonto.value;
		ldec_monto=uf_convertir_monto(ldec_monto);
		ldec_haber=f.txthaber.value;
		ldec_haber=uf_convertir_monto(ldec_haber);
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ldec_diferencia!=0)&&((ls_operacion=="")||(ls_operacion=="GUARDAR")||(ls_operacion=='NUEVO')))
		{
			alert("Comprobante descuadrado Contablemente");
			f.operacion.value="CARGAR_DT";
			f.action="tepuy_scb_p_movbanco.php";
			f.submit();
		}
	}
</script>
<span class="toolbar"><a name="00"></a></span>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Caja y Banco</td>
            <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>

    <td height="20" width="30" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_imprimir('<?php print $ls_reporte; ?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_ayuda('<?php print $ls_reporte1; ?>');"><img src="../shared/imagebank/tools20/ayuda.png" alt="CHEQUE" title="Cheque" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_imprimircontable('<?php print $ls_reporte2; ?>');"><img src="../shared/imagebank/tools20/impresora01.png" alt="CHEQUE" title="Cheque" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="528">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_funciones_db.php");
    require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/grid_param.php");
	
	$msg            = new class_mensajes();	
	$fun            = new class_funciones();	
    $sig_inc        = new tepuy_include();
    $con            = $sig_inc->uf_conectar();
	$obj_spg        = new ddlb_operaciones_spg($con);
	$io_function_db = new class_funciones_db($con);	
	$obj_spi        = new ddlb_operaciones_spi($con);
	$obj_con        = new ddlb_conceptos($con);
	$io_grid        = new grid_param();
	
	$arre         = $_SESSION["la_empresa"];
	$ls_empresa   = $arre["codemp"];
	$as_estmodest = $_SESSION["la_empresa"]["estmodest"];

	require_once("tepuy_scb_c_movbanco.php");
	$in_classmovbco=new tepuy_scb_c_movbanco($la_seguridad);
	require_once("tepuy_scb_c_config.php");
	$in_classconfig=new tepuy_scb_c_config($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		$ls_estdoc=$_POST["status_doc"];
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre=0;	
			$ls_codconmov="---";
		}
		else
		{
			if(array_key_exists("ddlb_spg",$_POST))
			{			
				$ls_opepre=$_POST["ddlb_spg"];		
			}
			elseif(array_key_exists("ddlb_spi",$_POST))
			{
				$ls_opepre=$_POST["ddlb_spi"];
			}			
			else
			{
				$ls_opepre=$_POST["opepre"];
			}
		}
		if(array_key_exists("ddlb_conceptos",$_POST))
		{			
			$ls_codigoconcepto=$_POST["ddlb_conceptos"];		
		}
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		if(array_key_exists("ckbimpcheque",$_POST))
		{
		if($_POST["ckbimpcheque"]==1)
		{
			$ckbimpcheque   = "checked" ;	
			$ls_ckbimcheque = 1;
		}
		else
		{
			$ls_ckbimpcheque = 0;
			$ckbimpcheque="";
		}
		}
		else
		{
			$ls_ckbimcheque=0;
			$ckbimpcheque="";
		}
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		$lastspg = $_POST["lastspg"];
		$lastscg = $_POST["lastscg"];
		$lastspi = $_POST["lastspi"];
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ls_estmov=$_POST["estmov"];
		$ls_estcon=$_POST["estcon"];
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);

		$ls_codconmov=$_POST["codconmov"];

		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{
				$ls_codconmov=$ls_codigoconcepto;
			}
		}
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];
		$li_estint=$_POST["estint"];
		$ls_codfuefin=rtrim($_POST["txtftefinanciamiento"]);
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=rtrim($_POST["txtdenftefinanciamiento"]);
		if(array_key_exists("nocontabili",$_POST))
		{   $lb_nocontab="checked";  }
		else
		{   $lb_nocontab="";   }
		$ls_numcarord=$_POST["txtnumcarord"];
		if(($ls_mov_operacion=='CH')||($ls_mov_operacion=='TR'))
		{
			if(array_key_exists("txtchevau",$_POST))
			{	$ls_chevau=$_POST["txtchevau"];	}
			else
			{	$ls_chevau="";	}
		}
		else
		{	$ls_chevau="";	}
		$ls_estbpd='M';
		$li_estimpche=$_POST["estimpche"];
		
	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_estdoc="N";
		$li_estimpche=0;
	}


	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	if($ls_operacion=="CARGAR_DT")
	{
		uf_cargar_dt();
	}
	
	function uf_cargar_dt()
	{
		global $in_classmovbco;
		global $objectScg;
		global $li_row;
		global $ls_estmov;
		global $ls_estcon;
		global $ldec_mondeb;
		global $ldec_monhab;
		global $objectSpg;
		global $li_rows_spg;
		global $ldec_monspg;
		global $ldec_monspi;
		global $objectSpi;
		global $li_rows_spi;
		global $objectRet;
		global $li_rows_ret;
		global $ldec_montoret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		global $ls_chevau;
		$in_classmovbco->uf_cargar_dt($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_rows_spg,&$ldec_monspg,&$objectSpi,&$li_rows_spi,&$ldec_monspi);
		$ls_chevau=$in_classmovbco->uf_numero_voucher($_SESSION["la_empresa"]["codemp"],$ls_codban,$ls_cuenta_banco,$ls_docmov);
		$ldec_diferencia=round($ldec_mondeb,2)-round($ldec_monhab,2);
	}
	
	function uf_nuevo()
	{
		global $ls_estdoc;
		$ls_estdoc="N";
		global $ls_mov_operacion;
		$ls_mov_operacion="NC";
	    global $ls_opepre;
		$ls_opepre=0;		
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
		global $ls_estcon;
		$ls_estcon=0;
		$ls_estmov="N";
		global $ls_cuenta_banco;
		$ls_cuenta_banco="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="-";
		global $lastspg;
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $fun;
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		global $lastscg;
		$lastscg=0;
		global $lastret;
		$lastret=0;
		global $lastspi;
		$lastspi=0;
		global $ldec_mondeb;
		$ldec_mondeb=0;
		global $ldec_monhab;
		$ldec_monhab=0;
		global $ldec_diferencia;
		$ldec_diferencia=0;
		global $ldec_monspg;
		$ldec_monspg=0;
		global $ldec_monspi;
		$ldec_monspi=0;
		global $ldec_montomov;
		$ldec_montomov="";
		global $ldec_monobjret;
		$ldec_monobjret="";
		global $ldec_montoret;
		$ldec_montoret="";
		global $ls_codconmov;
		$ls_codconmov='---';
		global $ls_desmov;
		$ls_desmov="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $ldec_disponible;
		$ldec_disponible="";
		global $lb_nocontab;
		$lb_nocontab="";
		global $li_estint;
		$li_estint=0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $objectScg;
		global $objectSpg;
		global $objectSpi;
		global $objectRet;
		global $li_row_scg;
		global $ls_codfuefin;
		global $ls_denfuefin;
		$ls_codfuefin="";
		$ls_denfuefin="";
		$li_row_scg=1;


		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg."  id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg."    value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
		$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		global $li_temp_spg;
		$li_temp_spg=1;
		$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
		$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
		$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=10 maxlength=15>";
		$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
		$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right>";		
		$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		global $li_temp_spi;
		$li_temp_spi=1;
		$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=6 maxlength=5>";
		$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
		$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
		$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpi[$li_temp_spi][7]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.png alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	

	}

	$titleSpi[1]="Cuenta";     $titleSpi[2]="Descripci�n";  $titleSpi[3]="Procede";     $titleSpi[4]="Documento";   	$titleSpi[5]="Operaci�n";   $titleSpi[6]="Monto";   $titleSpi[7]="Edici�n";
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripci�n";   $title2[4]="Procede";   	   $title2[5]="Debe/Haber";    $title2[6]="Monto";      $title2[7]="Deduccion";   $title2[8]="Edici�n";
	$title[1]="Cuenta";        $title[2]="Programatico";    $title[3]="Documento";      $title[4]="Descripci�n";   $title[5]="Procede";        $title[6]="Operaci�n";  $title[7]="Monto";       $title[8]="Edici�n";
	
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	

	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
		$ls_numcarord="";
		$ls_docmov="";
	}
	if($ls_operacion=="PRINT_CARTAORDEN")
	{
		$ls_tipo=$_POST["rb_provbene"];
		uf_cargar_dt();	
		$ls_codigo=$in_classconfig->uf_buscar_seleccionado();
		if($ls_codigo!="000")//distinto de chequevoucher
			$ls_pagina="reportes/".$ls_reporte_carta."?codigo=$ls_codigo&codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numcarord&chevau=&codope=ND&tipproben=$ls_tipo";
		else
			$ls_pagina="reportes/".$ls_reporte."?codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_docmov&chevau=&codope=ND";			
		?>
		<script language="javascript">						
		window.open('<?php print $ls_pagina; ?>',"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
		</script>
		<?php 		
	}
	if($ls_operacion == "GUARDAR")
	{			
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		switch ($ls_tipo){
			case 'P':
				$ls_codpro=$ls_provbene;
				$ls_cedbene="----------";
				break;	
			case 'B':
				$ls_codpro="----------";
				$ls_cedbene=$ls_provbene;
				break;
			default:
				$ls_codpro="----------";
				$ls_cedbene="----------";
		}
		$in_classmovbco->io_sql->begin_transaction();
		$ls_desmov = rtrim(substr($ls_desmov,0,1000));
		$lb_valido=$in_classmovbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret, $ldec_montoret,$ls_chevau,$ls_estmov,$li_estint,"$ls_opepre",$ls_estbpd,'SCBMOV','',$ls_estdoc,$ls_tipo,$ls_codfuefin);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();			
		}
		else
		{
			$in_classmovbco->io_sql->rollback();					
		}
		$msg->message($in_classmovbco->is_msg_error);
		uf_cargar_dt();			
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}	
		else
		{
			$in_classmovbco->io_sql->rollback();			
		}
		$msg->message($in_classmovbco->is_msg_error);
		$ls_docmov="";
		uf_nuevo();
	}
	if($ls_operacion=="DELETESCG")
	{
		$li_row_delete=$_POST["delete_scg"];
		$ls_codded=$_POST["txtcodded".$li_row_delete];
		$ls_cuentascg=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$ldec_montoscg=$_POST["txtmontocont".$li_row_delete];
		$ldec_montoscg=str_replace(".","",$ldec_montoscg);
		$ldec_montoscg=str_replace(",",".",$ldec_montoscg);
		$arr_movbco["codban"]=$ls_codban;
		$arr_movbco["ctaban"]=$ls_cuenta_banco;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]=$ls_mov_operacion;
		$arr_movbco["fecha"]=$ld_fecha;
		$arr_movbco["estmov"]=$ls_estmov;
		if($ls_tipo=="P")
		{
			$arr_movbco["codpro"] =$ls_provbene;
			$arr_movbco["cedbene"]="----------";
		}
		else
		{
			$arr_movbco["cedbene"]=$ls_provbene;
			$arr_movbco["codpro"] ="----------";
		}
		$arr_movbco["monto_mov"]=$ldec_montomov;
		$arr_movbco["objret"]   =$ldec_monobjret;
		$arr_movbco["retenido"] =$ldec_montoret;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuentascg,$ls_debhab,$ls_codded,$ldec_montoscg,'SCG');
		if($ls_codded!="00000")
		{
			if(($ls_mov_operacion=="ND")||($ls_mov_operacion=="RE")||($ls_mov_operacion=="CH")||($ls_mov_operacion=='TR'))
			{
				$ls_operacioncon="H";
			}
			else
			{
				$ls_operacioncon="D";
			}
			$ldec_monto=$ldec_montoscg;
			$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_montoscg,$ldec_monobjret,'00000');
			$ldec_montoret=$ldec_montoret-$ldec_monto;
		}
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESPG")
	{
		$li_row_delete=$_POST["delete_spg"];
		$ls_cuenta_spg=$_POST["txtcuenta".$li_row_delete];
		$ls_programatica=$_POST["txtprogramatico".$li_row_delete];
		if($as_estmodest==2)
		{
			$ls_programatica=$fun->uf_cerosizquierda(substr($ls_programatica,0,2),20).$fun->uf_cerosizquierda(substr($ls_programatica,3,2),6).$fun->uf_cerosizquierda(substr($ls_programatica,6,2),3).substr($ls_programatica,9,2).substr($ls_programatica,12,2);	
		}
		else
		{
			$ls_programatica=$_POST["txtprogramatico".$li_row_delete].'0000';
		}
		$ls_numdoc=$_POST["txtdocumento".$li_row_delete];
		$ls_operacion=$_POST["txtoperacion".$li_row_delete];
		$ldec_montospg=$_POST["txtmonto".$li_row_delete];
		$ldec_montospg=str_replace(".","",$ldec_montospg);
		$ldec_montospg=str_replace(",",".",$ldec_montospg);
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_spg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ldec_montospg);
		
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESPI")
	{
		$li_row_delete=$_POST["delete_spi"];
		$ls_cuenta_spi=$_POST["txtcuentaspi".$li_row_delete];
		$ls_numdoc=$_POST["txtdocspi".$li_row_delete];
		$ls_operacion=$_POST["txtopespi".$li_row_delete];
		$ldec_montospg=$_POST["txtmontospi".$li_row_delete];
		$ldec_montospg=str_replace(".","",$ldec_montospg);
		$ldec_montospg=str_replace(",",".",$ldec_montospg);
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_spi($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta_spi,$ls_operacion,$ldec_montospg);
			
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion == "CAMBIO_OPERA")
	{
		uf_cargar_dt();	
	}
	if($ls_operacion == "VERIFICAR_VAUCHER")
	{
		$ls_chevaux=$_POST["txtchevau"];
		$lb_existe=$in_classmovbco->uf_select_voucher($ls_chevaux);
		if($lb_existe)
		{
			$msg->message("N� de Voucher ya existe, favor indicar otro");
			uf_cargar_dt();
		}
		else
		{
			uf_cargar_dt();
			$ls_chevau=$ls_chevaux;
		}
		
		
	}
	if($ls_mov_operacion=='TR')
	{
		$lb_tr="selected";
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
	}

	if($ls_mov_operacion=='ND')
	{
		$lb_nd="selected";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
		$lb_tr="";
	}
	if($ls_mov_operacion=='NC')
	{
		$lb_nd="";
		$lb_nc="selected";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
		$lb_tr="";
		if($li_estint==1)
		{
			$lb_checked="checked";
		}
		else
		{
			$lb_checked="";
		}
	}
	if($ls_mov_operacion=='DP')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="selected";
		$lb_re="";
		$lb_ch="";
		$lb_tr="";
	}
	if($ls_mov_operacion=='RE')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="selected";
		$lb_ch="";
		$lb_tr="";
	}
	if($ls_mov_operacion=='CH')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_tr="";
		$lb_re="";
		$lb_ch="selected";
		if(($ls_operacion=="NUEVO")||($ls_operacion=="CAMBIO_OPERA"))
		{
			$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
		}
	}
	if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
	
	if($ls_estdoc=='C')
	{
		$ls_readOnly_doc="readonly";
		$ls_readOnly_ban="readonly";
		$ls_readOnly_cta="readonly";
		$ls_readOnly_ctascg="readonly";
		$ls_readOnly_ope="onClick='return false'";		
	}
	else
	{
		$ls_readOnly_doc="";
		$ls_readOnly_ban="";
		$ls_readOnly_cta="";
		$ls_readOnly_ctascg="";
		$ls_readOnly_ope="";		
	}
?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="6">Movimientos de Banco</td>
    </tr>
    <tr>
      <td height="19" colspan="6"><input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      <input name="estcon" type="hidden" id="estcon" value="<?php print $ls_estcon;?>">
      <input name="estimpche" type="hidden" id="estimpche" value="<?php print $li_estimpche?>"></td>
    </tr>
    <tr>
      <td width="107" height="22"><div align="right">Banco</div></td>
      <td colspan="3"><div align="left">
        <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
        <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>
      </div>        <div align="right"></div></td>
      <td width="95"><div align="right">Fecha</div></td>
      <td width="178"><div align="left">
          <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"   datepicker="true" >
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="3"><div align="left">
        <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="35" maxlength="254" readonly>
        <input name="txttipocuenta" type="hidden" id="txttipocuenta">
        <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </div></td>
      <td><div align="right"><strong>Disponible</strong></div></td>
      <td><div align="left">
        <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" size="24" value="<?php print $ldec_disponible;?>" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Referencia</div></td>
      <td colspan="3"><div align="left">
        <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24" maxlength="15" <?php print $ls_readOnly_doc?>>
      </div></td>
      <td><div align="right">Cta. Contable </div></td>
      <td><div align="left">
        <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td><div align="left">
          <select name="cmboperacion" id="select" onChange="javascript:uf_verificar_operacion();" style="width:120px"  >
            <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>
            <option value="NC" <?php print $lb_nc;?>>Nota Cr&eacute;dito</option>
            <option value="DP" <?php print $lb_dp;?>>Dep&oacute;sito</option>
            <option value="RE" <?php print $lb_re;?>>Retiro</option>
            <option value="CH" <?php print $lb_ch;?>>Cheque</option>
            <option value="TR" <?php print $lb_tr;?>>Pago de Orden con Transferencia</option>
          </select>
      </div></td>
      <td align="right"><?php if($ls_mov_operacion!="RE")print "Afectaci�n"; ?></td>
      <td width="146" align="left">
        <?php  
		
		if(($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH')||($ls_mov_operacion=='TR'))
				{
					$obj_spg->uf_cargar_ddlb_spg(0,$ls_opepre,$ls_mov_operacion); 	
				}
				elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
				{
					$obj_spi->uf_cargar_ddlb_spi(0,$ls_opepre,$ls_mov_operacion); 
				}				
				?>
        
          <input name="opepre" type="hidden" id="opepre" value="<?php print $ls_opepre;?>">      </td>
      <td align="right"><?php if(($ls_mov_operacion=="CH")||($ls_mov_operacion=='TR'))
								{
									print "Voucher";
								}
								?></td>
      <td style="text-align:left"><?php if(($ls_mov_operacion=="CH")||($ls_mov_operacion=='TR'))
								{?>
								  <input name="txtchevau" type="text" id="txtchevau" value="<?php print $ls_chevau ?>" style="text-align:center" size="28" maxlength="25" onChange="javascript:ue_verificar_vaucher();" onKeyPress="return keyRestrict(event,'0123456789'); "></td>
								<?php
								}
								?>
	  </td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto</div></td>
      <td colspan="2"><div align="left">
        <?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </div></td>
      <td colspan="2"><div align="right"> N&ordm; Carta Orden </div></td>
      <td><div align="left">
        <input name="txtnumcarord" type="text" id="txtnumcarord" value="<?php print $ls_numcarord;?>" size="24" maxlength="15" style="text-align:center" readonly>
</div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto Movimiento </div></td>
      <td colspan="5"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="129" maxlength="1000" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyz� .,*/-()$%&!�������[]{}<>')">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Destino</div></td>
      <td colspan="2">
	  	   <table width="244" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="234"><label>
                <input type="radio" name="rb_provbene" id="radio" value="P" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_p;?>>
Proveedor</label>
                <label>
                <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
Beneficiario</label>
                <label>
                <input name="rb_provbene" id="radio" type="radio"  class="sin-borde" value="-" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_n;?>>
Ninguno</label>
                <input name="tipo" type="hidden" id="tipo"></td>
            </tr>
          </table>      </td>
      <td colspan="3"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
      <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
      <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      <input name="txttitprovbene" type="hidden" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td width="188"><div align="left">
	  <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);uf_montoobjret(this);uf_verificar_monto(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" maxlength="22">
      </div></td>
      <td width="64"><div align="right">M.O.R.</div></td>
      <td>
        <div align="left">
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_format(this);javascript:uf_verificar_retenido();" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24" maxlength="22">
        </div></td>
      <td><div align="right">Monto Retenido</div></td>
      <td><div align="left">
        <input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" maxlength="22" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22">Fte. Financiamiento</td>
      <td><input name="txtftefinanciamiento" type="text" id="txtftefinanciamiento" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="3" maxlength="2" readonly>
        <a href="javascript: uf_cat_fte_financia();"><img src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Fuente de Financiamiento" width="15" height="15" border="0"></a>
        <input name="txtdenftefinanciamiento" type="text" class="sin-borde" id="txtdenftefinanciamiento" value="<?php print $ls_denfuefin;?>" readonly></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          		<?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="txtint" id="txtint" type="text" value="Interes" class="sin-borde" size="10" style="text-align:right" readonly>
         		<?php	
				}	
				?>
      </div></td>
      <td><div align="left">
	            <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="chkinteres" type="checkbox" id="chkinteres" value="1" style="width:15px; height:15px" onClick="uf_selec_interes(this);" <?php print $lb_checked;?>>
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td><div align="right">No Contabilizable </div></td>
      <td>
        <div align="left">
          <input name="nocontabili" type="checkbox" id="nocontabili" value="1" style="width:15px; height:15px" onClick="javascript:uf_nocontabili();" <?php print $lb_nocontab;?>>
        </div></td>
      <td><div align="right">Anular Movimiento </div></td>
      <td><div align="left">
        <input name="chkanula" type="checkbox" id="chkanula" value="1">
      </div></td>
    </tr>
<td width="91" height="14"><div align="right">
              <input name="ckbimpcheque" type="checkbox" id="ckbimpcheque" onChange="uf_cambio()"  value="1" checked <?php print $ckbimpcheque ?>>
            </div></td>
            <td colspan="2"><div align="left">Imprimir Cheque </div></td>
          <tr>
            <td height="22"><div align="right">

			<?php 
			// $ls_impcheque="1";
			 if($ls_datap==true)
			 {
			$ls_impcheque="1";
			?> 
              <input name="txtimpcheque" type="text" class="sin-borde" id="txtimpcheque" value="<?php print $ls_impcheque;  ?>" size="5" style="visibility:visible">
            <?php 
			 }
			 elseif($ls_datap==false)
			 {
			$ls_impcheque="0";
			?>
              <input name="txtimpcheque" type="text" class="sin-borde" id="txtimpcheque" value="<?php print $ls_impcheque;  ?>" size="5" style="visibility:hidden">
			<?php 
			 }
			?>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="21" colspan="6"><table width="613" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="203"><div align="center"><a href="#01">Detalle Contable/Retenciones </a></div></td>
          <td width="203"><div align="center"><a href="#03">Detalle Presupuesto de Ingreso </a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13" colspan="2">&nbsp;</td>
      <td height="13" colspan="2">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.png" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
        <input name="totpre"  type="hidden" id="totpre"  value="<?php print $totalpre?>">
        <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
        <input name="delete_spg" type="hidden" id="delete_spg">
		<input name="delete_spi" type="hidden" id="delete_spi">
		
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
          <td width="127"><input name="totspg" type="text" id="totspg" readonly value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp; </td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<img src="../shared/imagebank/tools/nuevo.png" width="15" height="15" border="0"><a href="javascript:ue_det_ingreso()">Agregar detalle Ingreso</a></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center">
        <a name="03"></a>
        <?php $io_grid->makegrid($li_rows_spi,$titleSpi,$objectSpi,770,'Detalle Ingresos',$gridSpi);?>
        <input name="totspi" type="hidden" id="totspi" value="<?php print $totalspi?>">
        <input name="lastspi" type="hidden" id="lastspi" value="<?php print $lastspi;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Ingreso </div></td>
          <td width="127"><input name="totspgi" type="text" id="totspgi" readonly value="<?php print number_format($ldec_monspi,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.png" alt="Detalle Contable" width="15" height="15" border="0">Agregar detalle Contable </a><a href="javascript: uf_agregar_dtret('$ls_mov_operacion');"><img src="../shared/imagebank/tools/nuevo.png" alt="Detalle Deducciones" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center">
        <a name="01" id="01"></a>
        <?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
          <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $totalcon?>">
          <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
          <input name="delete_scg" type="hidden" id="delete_scg" size=5>
          <input name="formatoanula" type="hidden" id="formatoanula" value="<?php print $ls_reporteanulado; ?>">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="82" height="20"><div align="right">Total Debe</div></td>
            <td width="128"><input name="txtdebe" type="text" id="txtdebe" value="<?php print number_format($ldec_mondeb,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
          <tr>
            <td height="20"><div align="right">Total Haber</div></td>
            <td><input name="txthaber" type="text" id="txthaber" value="<?php print number_format($ldec_monhab,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
          <tr>
            <td height="20"><div align="right">Diferencia</div></td>
            <td><input name="txtdiferencia" type="text" id="txtdiferencia" value="<?php print number_format($ldec_diferencia,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
    <input name="status_doc" type="hidden" id="status_doc" value="<?php print $ls_estdoc;?>">
  </p>
  </form>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="tepuy_scb_p_movbanco.php";
	f.submit();
}

function ue_guardar()
{
	f            = document.form1;
	ls_status    = f.estmov.value;
	if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_codban    = f.txtcodban.value;
		ls_ctaban    = f.txtcuenta.value;
		ls_operacion = f.cmboperacion.value;		
		ls_documento = f.txtdocumento.value;
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_monobjret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_nomproben=f.txtdesproben.value;
		if(f.chkanula.checked==true)
		{
			lb_anula=true;
		}
		else
		{
			lb_anula=false;
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if((ls_operacion=="CH")||(ls_operacion=="TR"))
		{
			ls_chevau=f.txtchevau.value;
			lb_valido=true;
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ldec_objret=ldec_monobjret;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		while(ldec_monret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monret=ldec_monret.replace(".","");
		}
		ldec_monret=ldec_monret.replace(",",".");

		if((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_documento!="")&&( ((ldec_monto==0)&&(ls_operacion=='CH'))||(lb_anula) ))
		{
			ls_status_doc=f.status_doc.value;
			if(ls_status_doc != "C")
			{
				if(confirm("El Movimiento se registrara como Anulado,desea Continuar?"))
				{
					f.operacion.value ="GUARDAR";
					f.action="tepuy_scb_p_movbanco.php";
					f.estmov.value='A';
					f.submit();
				}
			}
			else
			{				
				alert("El movimiento ser� actualizado, pero mantendr� su estatus original");
				f.operacion.value ="GUARDAR";
				f.action="tepuy_scb_p_movbanco.php";
				f.submit();			
			}			
		}
		else if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		{
			f.operacion.value ="GUARDAR";
			f.action="tepuy_scb_p_movbanco.php";
			f.submit();
		}
		else
		{
			alert("No ha completado los datos");
		}
	}
	else
	{
		alert("No puede realizar esta operacion el movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}		
}

function ue_eliminar()
{
	f=document.form1;
	ls_status=f.estmov.value;
	ls_operacion=f.cmboperacion.value;
	ls_numcarord=f.txtnumcarord.value;
	ls_estcon=f.estcon.value;
	if((ls_operacion!="CH") && (ls_numcarord==""))
	{
	  if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P')&& (ls_estcon!='1'))
	  {
			ls_operacion=f.cmboperacion.value;		
			li_lastscg=f.lastscg.value;
			li_newrow=parseInt(li_lastscg,10)+1;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			ls_descripcion=f.txtconcepto.value;
			ls_procede="SCBMOV";
			ls_documento=f.txtdocumento.value;
			ldec_monto=f.txtmonto.value;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			ld_fecha=f.txtfecha.value;
			ls_codban=f.txtcodban.value;
			ls_ctaban=f.txtcuenta.value;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			total=f.totcon.value;
			ldec_monobjret=f.txtmonobjret.value;
			ldec_monret=f.txtretenido.value;
			ls_nomproben=f.txtdesproben.value;
			if(f.nocontabili.checked==true)
			{
				ls_estmov="L";
			}
			else
			{
				ls_estmov="N";
			}
		
			if((ls_operacion=="CH")||(ls_operacion=="ND"))
			{			
				li_cobrapaga=f.ddlb_spg.value;			
			}
			else if((ls_operacion=="DP")||(ls_operacion=="NC"))
			{
				li_cobrapaga=f.ddlb_spi.value;
			}
			if(ls_operacion=="CH")
			{
				ls_chevau=f.txtchevau.value;
				lb_valido=true;
			}
			else
			{
				ls_chevau=" ";
				lb_valido=true;
			}
			if(ls_operacion=="NC")
			{
				if(f.chkinteres.checked)
				{
					li_estint=1;
				}
				else
				{
					li_estint=0;
				}
			}
			else
			{
				li_estint=0;
			}
			if(f.rb_provbene[0].checked)
			{
				ls_tipo="P";
			}
			if(f.rb_provbene[1].checked)
			{
				ls_tipo="B";
			}
			if(f.rb_provbene[2].checked)
			{
				ls_tipo="-";
			}
			ls_provbene=f.txtprovbene.value;
			ldec_objret=ldec_monobjret;
			while(ldec_objret.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_objret=ldec_objret.replace(".","");
			}
			ldec_objret=ldec_objret.replace(",",".");
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			while(ldec_monret.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monret=ldec_monret.replace(".","");
			}
			ldec_monret=ldec_monret.replace(",",".");
			if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
			{
				if(confirm("Esta seguro de Eliminar el Documento?,\n esta operaci�n no se puede deshacer." ))
				{
					f.operacion.value ="ELIMINAR";
					f.action="tepuy_scb_p_movbanco.php";
					f.submit();
				}
			}
			else
			{
				alert("Seleccione un documento valido, o que ya este registrado");
			}
		}
		else
		{
			if(ls_status=='C')
			{
				alert("No puede eliminar el movimiento, ya fue Contabilizado");
			}
			else if((ls_status=='O') || (ls_status=='A'))
			{
				alert("No puede eliminar el movimiento, Anulado");
			}
			else if(ls_status=='P')
			{
				alert("No puede eliminar el movimiento, ya fue Procesado !!!");
			}
			else
			{
				alert("No puede eliminar el movimiento, ya fue Conciliado");
			}
		}
	}
	else
	{
		if(ls_operacion=="CH")
		{
			alert("Los Cheques deben ser eliminados a trav�s de Eliminaci�n de Cheques no Contabilizados");
		}
		else
		{
			alert("Las Carta Orden deben ser eliminadas a trav�s de Eliminaci�n de Carta Orden no Contabilizada");
		}
	}
}

function ue_imprimir(ls_reporte)
{
	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	ls_codope=f.cmboperacion.value;
	ls_reporteanulado=f.formatoanula.value;
	ls_status=f.estmov.value;
	ls_numcarord=f.txtnumcarord.value;
	//ls_impcheque=f.ckbimpcheque.value;
	//ls_impcheque=f.txtimpcheque.value;
	if(f.ckbimpcheque.checked==true)
	{
		ls_impcheque=1;
	}
	else
	{
		ls_impcheque=0;
	}
	ls_reporte="tepuy_scb_rpp_voucher_waryna.php";
	if(ls_codope=='CH')
	{
		ls_chevau=f.txtchevau.value;
	}
	else
	{
		ls_chevau="";
	}
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	if(ls_numcarord=="")
	{	
		if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
		{
			if(ls_status!="A")
			{
				ls_pagina="reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&impche="+ls_impcheque+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				ls_pagina="reportes/"+ls_reporteanulado+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
		}
		else
		{
			if(ls_status=="A")
				alert("El documento est� anulado y no puede ser impreso");
			else
				alert("Seleccione un documento valido, o que ya este registrado");
		}
	}
	else
	{
		f.operacion.value="PRINT_CARTAORDEN";
		f.submit();
	}
}

function ue_imprimircontable(ls_reporte)
{
	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	ls_codope=f.cmboperacion.value;
	ls_reporteanulado=f.formatoanula.value;
	ls_status=f.estmov.value;
	ls_numcarord=f.txtnumcarord.value;
	//ls_impcheque=f.ckbimpcheque.value;
	//ls_impcheque=f.txtimpcheque.value;
	if(f.ckbimpcheque.checked==true)
	{
		ls_impcheque=1;
	}
	else
	{
		ls_impcheque=0;
	}
	ls_reporte="tepuy_cheque.php";
	if(ls_codope=='CH')
	{
		ls_chevau=f.txtchevau.value;
	}
	else
	{
		ls_chevau="";
	}
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	if(ls_numcarord=="")
	{	
		if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
		{
			if(ls_status!="A")
			{
				ls_pagina="reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&impche="+ls_impcheque+"&codope="+ls_codope;

				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				ls_pagina="reportes/"+ls_reporteanulado+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
		}
		else
		{
			if(ls_status=="A")
				alert("El documento est� anulado y no puede ser impreso");
			else
				alert("Seleccione un documento valido, o que ya este registrado");
		}
	}
	else
	{
		f.operacion.value="PRINT_CARTAORDEN";
		f.submit();
	}
}


function ue_ayuda(ls_reporte1)
{
	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	ls_codope=f.cmboperacion.value;
	ls_status=f.estmov.value;
	ls_numcarord=f.txtnumcarord.value;
	if(ls_codope=='CH')
	{
		ls_chevau=f.txtchevau.value;
	}
	else
	{
		ls_chevau="";
	}
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	if(ls_numcarord=="")
	{	
		if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!="")&&(ls_status!="A"))
		{
			ls_pagina="reportes/"+ls_reporte1+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
			window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			if(ls_status=="A")
				alert("El documento est� anulado y no puede ser impreso");
			else
				alert("Seleccione un documento valido, o que ya este registrado");
		}
	}
	else
	{
		f.operacion.value="PRINT_CARTAORDEN";
		f.submit();
	}
}


function ue_buscar()
{
	var x=document.body.clientWidth;
	var y=(document.body.clientHeight)-200;
	window.open("tepuy_cat_mov_bancario.php?opener=tepuy_scb_p_movbanco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width="+x+",height="+y+",left=0,top=0,location=no,resizable=yes");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="tepuywindow_blank.php";
	f.submit();
}

function rellenar_cad(cadena,longitud,campo)
{
	if (cadena!="")
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
		if(campo=="chevau")
		{
			document.form1.txtchevau.value=cadena;
		}
	}
}
	
function catalogo_cuentabanco()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	   if((ls_codban!=""))
	   {
		   pagina="tepuy_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=720,height=400,resizable=yes,location=no");
	   }
	   else
	   {
			alert("Debe seleccionar el Banco asociado a la cuenta");   
	   }
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o esta Anulado");   
	}
}
	 	 
function cat_bancos()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
	 pagina="tepuy_cat_bancos.php";
	 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o esta Anulado");   
	}
}

function cat_conceptos()
{
   f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		pagina="tepuy_cat_conceptos.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o esta Anulado");   
	}
}
	
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
   
function uf_verificar_operacion()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		f.operacion.value="CAMBIO_OPERA";
		f.opepre.value=f.cmboperacion.value;
		f.submit();   
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_desaparecer(objeto)
{
  eval("document.form1."+objeto+".style.visibility='hidden'");
}

function uf_aparecer(objeto)
{
  eval("document.form1."+objeto+".style.visibility='visible'");
}
   
function catprovbene()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		if(f.rb_provbene[0].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}   

function uf_verificar_provbene(lb_checked,obj)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		if((f.rb_provbene[0].checked)&&(obj!='P'))
		{
			f.tipo.value='P';
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.txttitprovbene.value="Proveedor";
		}
		if((f.rb_provbene[1].checked)&&(obj!='B'))
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipo.value='B';
			f.txttitprovbene.value="Beneficiario";
		}
		if((f.rb_provbene[2].checked)&&(obj!='N'))
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="Ninguno";
			f.tipo.value='N';
			f.txttitprovbene.value="";
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}

function  uf_agregar_dtcon()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_operacion=f.cmboperacion.value;		
		ls_estdoc=f.status_doc.value;
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_monobjret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_nomproben=f.txtdesproben.value;
		ls_codconmov=f.ddlb_conceptos.value;
		ls_codfuefin=f.txtftefinanciamiento.value;
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}

		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			lb_valido=true;
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ldec_objret=ldec_monobjret;

		ldec_objret=uf_convertir_monto(ldec_objret);

		ldec_monto=uf_convertir_monto(ldec_monto);

		ldec_monret=uf_convertir_monto(ldec_monret);
		if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		{
			ls_pagina = "tepuy_w_regdt_contable.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+
						"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+
						"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_monobjret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+
						"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
						"&tip_mov= &opener=tepuy_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=210,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Complete los datos del Movimiento");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function  uf_agregar_dtpre()
{
	 f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{		 
		ls_operacion=f.cmboperacion.value;
		ldec_totspg = f.totspg.value;	
		ls_estdoc=f.status_doc.value;
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_objret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_codconmov=f.ddlb_conceptos.value;
		ls_codfuefin=f.txtftefinanciamiento.value;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		while(ldec_monret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monret=ldec_monret.replace(".","");
		}
		ldec_monret=ldec_monret.replace(",",".");
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;
			if(li_cobrapaga==1)
			{
				ls_afectacion='PG';
			}
			else
			{
				ls_afectacion='CCP';
			}
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
			if(li_cobrapaga==1)
			{
				ls_afectacion='COB';
			}
			else
			{
				ls_afectacion='DC';
			}
		}
		else
		{
			li_cobrapaga=0;
			ls_afectacion='CCP';
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			lb_valido=true;
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ls_nomproben=f.txtdesproben.value;
		if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		{
			if((ls_operacion!="NC")&&(ls_operacion!="DP"))
			{
				ls_pagina = "tepuy_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+
							"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+
							"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+
							"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+"&estbpd=M&txtnomproben="+ls_nomproben+
							"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=tepuy_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=580,height=350,left=50,top=50,location=no,resizable=yes,dependent=yes");
			}
			else
			{
				alert("El Movimiento no puede registrar un gasto");			
			}
		}
		else
		{
			alert("Complete los datos del Movimiento ");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function  uf_agregar_dtret(operacion)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_estdoc=f.status_doc.value;
		ldec_monobjret=f.txtmonobjret.value;
		ls_operacion=f.cmboperacion.value;		
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_objret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_codconmov=f.ddlb_conceptos.value;
		ls_codfuefin=f.txtftefinanciamiento.value;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		if(ldec_monto==0)
		{
			alert("Monto del movimiento no puede ser igual a cero");
			lb_valido=false;
		}
		else
		{
			lb_valido=true;
		}
		if((ldec_objret==0)||(lb_valido==false))
		{
			alert("Monto Objeto a Retenci�n no debe ser igual a cero");
			lb_valido=false;
		}
		else
		{
			lb_valido=true;
		}
		
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			lb_valido=true;
		}
		else
		{
			ls_chevau=" ";
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ls_nomproben=f.txtdesproben.value;
		if(lb_valido)
		{
			if((ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0)&&(ldec_objret>0))
			{
				if(ls_operacion=="CH")
				{
					ls_pagina = "tepuy_w_regdt_deducciones.php?txtdocumento="+ls_documento+"&txtprocede=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+
								"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+
								"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+
								"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+
								"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
								"&tip_mov= &opener=tepuy_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
					window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
				}
				else
				{
					alert("Al Movimiento no aplican retenciones");
				}
			}
			else
			{
				alert("Complete los datos del Movimiento");
			}
		}
	}
	else
	{
	  alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_objeto(obj)
{
  alert(obj.name);
}
   
function uf_delete_Scg(row)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_cuenta=eval("f.txtcontable"+row+".value");
		ls_documento=eval("f.txtdocscg"+row+".value");
		ls_descripcion=eval("f.txtdesdoc"+row+".value");
		ls_procede=eval("f.txtprocdoc"+row+".value");
		ls_debhab=eval("f.txtdebhab"+row+".value");
		ldec_montocont=eval("f.txtmontocont"+row+".value");
		if((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_debhab!=""))
		{
			f.operacion.value="DELETESCG";
			f.delete_scg.value=row;
			f.action="tepuy_scb_p_movbanco.php";
			f.submit();
		}
		else
		{
			alert("No hay datos para eliminar");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_delete_Spg(row)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_cuenta=eval("f.txtcuenta"+row+".value");
		ls_estprog=eval("f.txtprogramatico"+row+".value");
		ls_documento=eval("f.txtdocumento"+row+".value");
		ls_descripcion=eval("f.txtdescripcion"+row+".value");
		ls_procede=eval("f.txtprocede"+row+".value");
		ls_operacion=eval("f.txtoperacion"+row+".value");
		ldec_monto=eval("f.txtmonto"+row+".value");
		if((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
		{
			f.operacion.value="DELETESPG";
			f.delete_spg.value=row;
			f.action="tepuy_scb_p_movbanco.php";
			f.submit();
		}
		else
		{
			alert("No hay datos para eliminar");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_delete_Spi(row)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		ls_cuenta=eval("f.txtcuentaspi"+row+".value");
		ls_descripcion=eval("f.txtdescspi"+row+".value");
		ls_procede=eval("f.txtprocspi"+row+".value");
		ls_documento=eval("f.txtdocspi"+row+".value");
		ls_operacion=eval("f.txtopespi"+row+".value");
		ldec_monto=eval("f.txtmontospi"+row+".value");
		if((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
		{
			f.operacion.value="DELETESPI";
			f.delete_spi.value=row;
			f.submit();
		}
		else
		{
			alert("No hay datos para eliminar");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_delete_Ret(row)
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{
		f.operacion.value="DELETERET";
		f.delete_ret.value=row;
		f.action="tepuy_scb_p_movbanco.php";
		f.submit();
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}
}
   
function uf_format(obj)
{
	ldec_monto=uf_convertir(obj.value);
	obj.value=ldec_monto;
}
   
function uf_validar_campos(operacion)
{
	f=document.form1;
	ls_documento=f.txtdocumento.value;
	if(ls_documento=="")
	{
		alert("Debe introducir un numero de documento");
		return false;	
	}
	
	ls_codban=f.txtcodban.value;
	ls_cuentaban=f.txtcuenta.value;
	if((ls_codban=="")&&(ls_ctaban==""))
	{
		alert("Seleccione el banco y la cuenta");
	}
	ls_cuenta_scg=f.txtcuenta_scg.value;
	ld_fecha=f.txtfecha.value;
	ls_concepto=f.txtconcepto.value;
	if(f.rb_provbene[0].checked)
	{
		ls_tipo_dest="P";
	}
	if(f.rb_provbene[1].checked)
	{
		ls_tipo_dest="B";
	}
	if(f.rb_provbene[2].checked)
	{
		ls_tipo_dest="N";
	}
	ls_provbene=f.txtprovbene.value;
	ldec_monto=f.txtmonto.value;
	ldec_montoobjret=f.txtmonobjret.value;
	ldec_montoret=f.txtretenido.value;
	ldec_diferencia=f.txtdiferencia.value;
}
   
function uf_selec_interes(obj)
{
	f=document.form1;
	
	if(obj.checked==true)
	{
		f.estint.value=1;
	}
	else
	{
		f.estint.value=0;
	}
}
   
function uf_verificar_retenido()
{
	f=document.form1;

	ldec_monret=f.txtretenido.value;
	ldec_monret=uf_convertir_monto(ldec_monret);
	ldec_monobjret=f.txtmonobjret.value;
	ldec_monobjret=uf_convertir_monto(ldec_monobjret);
	ldec_monto=f.txtmonto.value;
	ldec_monto=uf_convertir_monto(ldec_monto);
	ldec_monto     = parseFloat(ldec_monto);
	ldec_monobjret = parseFloat(ldec_monobjret);
	if(ldec_monto>ldec_monobjret)
	{
		if(ldec_monret>0)
		{
			f.txtmonobjret.readOnly=true;
			alert("Error no puede modificar el monto objeto a retenci�n porque ya se realizaron retenciones en base al mismo");
		}			
	}
	else
	{
		alert("Monto Objeto a Retenci�n no puede ser mayor al monto del movimiento !!!");
		f.txtmonobjret.value=uf_convertir(ldec_monto);
	}	
	
}
   
function  uf_nocontabili()
{   
	f=document.form1;
	lb_status=f.nocontabili.checked;
	if(lb_status)
	{
		f.estmov.value='L';
	}  
} 
   
function  uf_montoobjret(obj)
{
	f=document.form1;
	ldec_monto=obj.value;
	f.txtmonobjret.value=ldec_monto;
}
  
function ue_verificar_vaucher()
{
	f=document.form1;
	rellenar_cad(f.txtchevau.value,25,'chevau');
	f.operacion.value="VERIFICAR_VAUCHER";
	f.submit();
}
  
function ue_det_ingreso()
{
	 f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')&&(ls_status!='P'))
	{		 
		ls_operacion=f.cmboperacion.value;		
		ls_estdoc=f.status_doc.value;
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_objret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_codconmov=f.ddlb_conceptos.value;
		ls_codfuefin=f.txtftefinanciamiento.value;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		while(ldec_monret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monret=ldec_monret.replace(".","");
		}
		ldec_monret=ldec_monret.replace(",",".");
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;
			if(li_cobrapaga==1)
			{
				ls_afectacion='PG';
			}
			else
			{
				ls_afectacion='CCP';
			}
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
			if(li_cobrapaga==1)
			{
				ls_afectacion='COB';
			}
			else
			{
				ls_afectacion='DC';
			}
		}
		else
		{
			li_cobrapaga=0;
			ls_afectacion='CCP';
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			lb_valido=true;
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ls_nomproben=f.txtdesproben.value;
		if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		{
			if((ls_operacion=="NC")||(ls_operacion=="DP"))
			{
				ls_pagina = "tepuy_w_regdt_ingreso.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=tepuy_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=250,left=50,top=50,location=no,resizable=yes,dependent=yes");
			}
			else
			{
				alert("El Movimiento no puede registrar un ingreso");			
			}
		}
		else
		{
			alert("Complete los datos del Movimiento ");
		}
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado, Procesado o Anulado !!!");
	}

}
  
function uf_verificar_monto(monto)
{
	f=document.form1;
	ld_debe=parseFloat(uf_convertir_monto(f.txtdebe.value));
	ld_haber=parseFloat(uf_convertir_monto(f.txthaber.value));
	ls_max=Math.max(ld_debe,ld_haber);
	ld_monto=parseFloat(uf_convertir_monto(monto.value));
	if(ld_monto<ls_max)
	{
		alert("El monto total del movimiento no debe ser inferior a los asientos contables realizados");
		monto.value="0,00";
	}
}

function uf_cat_fte_financia()
{
	f=document.form1;
	ls_status=f.estmov.value;
	if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	{
	  pagina="tepuy_sep_cat_fuente.php";
	  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");
	}  
}  
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
