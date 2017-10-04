<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head >
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<title>Comprobante Presupuestario</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo14 {color: #006699; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo20 {font-size: 10px}
.Estilo21 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo24 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<body onUnload="javascript:uf_valida_cuadre();">
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
	function uf_valida_cuadre()
	{
		f=document.form1;
		ls_comprobante=f.txtcomprobante.value;
		ldec_diferencia=f.txtdiferencia.value;
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ls_operacion=="NUEVO")||(ls_operacion=="GUARDAR")||(ls_operacion==""))
		{
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado Contablemente");
				f.operacion.value="CARGAR_DT";
				f.action="tepuy_spg_p_comprobante.php";
				f.submit();
			}
		}
	}
</script>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
       <?php
	   if(array_key_exists("la_empresa(confinstr)",$_SESSION))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="13" align="center" class="toolbar">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="530" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
require_once("../shared/class_folder/class_tepuy_int_spg.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/tepuy_c_seguridad.php");
require_once("tepuy_spg_c_comprobante.php");
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	$io_seguridad= new tepuy_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="tepuy_spg_p_comprobante.php";
	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventana;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$lb_permisos=true;
		}
		else
		{
			$lb_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventana);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$io_include=new tepuy_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_function=new class_funciones();	
$io_fecha=new class_fecha();
$io_msg = new class_mensajes();
$io_function_db=new class_funciones_db($io_connect);
$in_classcmp=new tepuy_spg_c_comprobante();
$io_int_scg=new class_tepuy_int_scg();
$io_int_spg=new class_tepuy_int_spg();
$io_msg=new class_mensajes();
$io_grid=new grid_param();

$la_emp=$_SESSION["la_empresa"];
$li_estmodest=$la_emp["estmodest"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
 	$ls_procede = $_POST["txtproccomp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_status    = $_POST["status_actual"];
	$li_fila		 = 0;
}
else
{
	$ls_operacion="NUEVO";
	$_SESSION["ACTUALIZAR"]="NO";
	$_SESSION["ib_new"]	=true;
	$ls_fecha=date("d/m/Y");
	$li_fila = 0;
}
if($ls_operacion=="VALIDAFECHA")
{
	
	$readonly="";
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_tipo      = $_POST["tipo"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp=$la_emp["codemp"];

	$lb_valido=$io_fecha->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	
	if(!($lb_valido))
	{
		$io_msg->message($io_fecha->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
	  $lb_existe=$in_classcmp->uf_verificar_comprobante($ls_codemp,$ls_procede,$ls_comprobante);
	  if($lb_existe)
	  {
		 $io_msg->message(" El Comprobante ya existe. El Sistema generara un nuevo numero de Comprobante");
	     $ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
	  }
	}
	$li_fila = 0;
	$prov_sel= "";
	$bene_sel= "";
	$ning_sel= "selected";
	$totalpre= 1;
	$totalcon= 1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}
	$object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=10>";
	$object[1][2]  = "<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength>"; 
	$object[1][3]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$object[1][4]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:left>";
	$object[1][5]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$object[1][6]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$object[1][7]  = "<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
	$object[1][8]  = "";		
		
	$object2[1][1] = "<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
	$object2[1][2] = "<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
	$object2[1][3] = "<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:left size=$li_sizedes maxlength=$li_maxlengthdes>";
	$object2[1][4] = "<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
	$object2[1][5] = "<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
	$object2[1][6] = "<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] = "";

	uf_cargar_dt($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
}
	
  //Titulos de la tabla de Detalle Presupuestario.
  $title[1]="Cuenta";   $title[2]="Programatico";     $title[3]="Documento";    $title[4]="Descripción";   $title[5]="Procede"; $title[6]="Operación";     $title[7]="Monto";  $title[8]="Edición";
  $grid1="grid_SPG";	
   //Titulos de la tabla de Detalle Contable
  $title2[1]="Cuenta";   $title2[2]="Documento";     $title2[3]="Descripción";     $title2[4]="Procede";   	$title2[5]="D/H";   $title2[6]="Monto";   $title2[7]="Edición";
  //$object[$i][1]="<input type=text name=txtnumdocumento".$i." value=$documento class=sin-borde readonly>";$object[$i][2]="<input type=text name=txtcompromiso".$i." class=sin-borde value=$compromiso readonly>";$object[$i][3]="<input type=text name=txtcodigo".$i." value=$codigo class=sin-borde readonly>"; $object[$i][4]="<input type=text name=txtdenominacion".$i." value=$denominacion class=sin-borde readonly>";
  $grid2="grid_SCG";
  
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	
	$ls_procede   = "SPGCMP";
	$ls_status    = "N";
	$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
	$ls_provbene  = "----------";
	$ls_desproben = "";
	$ls_tipo      = "";
	$ls_descripcion = "";
	$ls_tipo      = "";
	$li_fila	  = 0;
	$ldec_mondeb  = 0;
	$ldec_diferencia=0;
	$ldec_monhab  = 0;	
	$ldec_totspg  = 0;
	$prov_sel     = "";
	$bene_sel     = "";
	$ning_sel     = "selected";
	$totalpre     = 1;
	$totalcon     = 1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}
	$object[1][1] = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][2] = "<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength>"; 
	$object[1][3] = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][4] = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:left>";
	$object[1][5] = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$object[1][6] = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
	$object[1][7] = "<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
	$object[1][8] = "";

	$object2[1][1] = "<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
	$object2[1][2] = "<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
	$object2[1][3] = "<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:left size=$li_sizedes maxlength=$li_maxlengthdes>";
	$object2[1][4] = "<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$object2[1][5] = "<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
	$object2[1][6] = "<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] = "";
	
}

if($ls_operacion=="CARGAR_DT")
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   =$_POST["txtprovbene"];	
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_tipo	   =$_POST["tipo"];
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;
	
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	
}	
if($ls_operacion=="GUARDAR")
{
	$ls_codemp=$la_emp["codemp"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo=$_POST["tipo"];
	$in_classcmp->io_int_int->is_tipo=$ls_tipo;
	$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
	$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$in_classcmp->io_int_int->ib_procesando_cmp=false;
	$in_classcmp->io_int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	
	if( $ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";
	}
	else
	{
		$ls_fuente = "-";
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";
		
	}
	$ls_codemp=$la_emp["codemp"];

	$lb_valido=$io_fecha->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	
	if(!($lb_valido))
	{
		$io_msg->message($io_fecha->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_procedencia,$ls_descripcion,$in_classcmp->io_int_int->is_cod_prov,$in_classcmp->io_int_int->is_ced_ben,$ls_tipo,1);
		if(!$lb_valido)
		{
			$io_msg->message($in_classcmp->is_msg_error);			
		}
	}
	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha);
}
if($ls_operacion=="ELIMINAR")
{
	$lb_valido=false;
	//require_once("../shared/class_folder/class_tepuy_int_int.php");
	//$int_int=new class_tepuy_int_int();
	$ls_codemp=$la_emp["codemp"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo=$_POST["tipo"];
	$in_classcmp->io_int_int->is_tipo=$ls_tipo;
	$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
	$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$in_classcmp->io_int_int->ib_procesando_cmp=false;
	$in_classcmp->io_int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	if( $ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";	
	}
	else
	{
		$ls_fuente = "-";
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";		
	}

	$lb_valido=$in_classcmp->io_int_int->uf_init_delete($ls_codemp,$ls_procedencia,$ls_comprobante, $in_classcmp->io_int_int->id_fecha,$ls_tipo,$in_classcmp->io_int_int->is_ced_ben,$in_classcmp->io_int_int->is_cod_prov,false );

	if (!$lb_valido) 
    {	
	   $io_msg->message("Comprobante no existe");	
	}	
	else
	{
	    $lb_valido = $in_classcmp->io_int_int->uf_int_init_transaction_begin();
		if(!$lb_valido)
		{
			$io_msg->message($in_classcmp->io_int_int->is_msg_error);
		}	
		if($lb_valido)
		{	
			$lb_valido = $in_classcmp->io_int_int->uf_init_end_transaccion_integracion($la_seguridad);
			if (!$lb_valido)
			{
				$io_msg->message("Error".$in_classcmp->io_int_int->is_msg_error);
				$in_classcmp->io_int_int->io_sql->rollback();
			}
			else
			{
				$io_msg->message("Comprobante eliminado satisfactoriamente");
 				$ls_comprobante =  $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
				$ls_fecha     = date("d/m/Y");
				$ls_provbene  = "";
				$ls_desproben  = "";
				$ls_tipo      = "-";
				$ls_descripcion = "";				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_desc_event="Elimino el comprobante presupuestario ".$ls_comprobante." de fecha ".$ld_fecha." y procedencia ".$ls_procedencia;
				$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$in_classcmp->io_int_int->io_sql->commit();
			}
		}
    }		
	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha);
}
if($ls_operacion=="DELETESPG")		
{
	$ls_comprobante= $_POST["txtcomprobante"];
	$ld_fecha      = $_POST["txtfecha"];
	$ls_proccomp   = $_POST["txtproccomp"];
	$ls_desccomp   = $_POST["txtdesccomp"];
	$ls_provbene   = $_POST["txtprovbene"];	
	$ls_tipo	   = $_POST["tipo"];
	$li_fila	   = $_POST["fila"];
	$ls_desproben  = $_POST["txtdesproben"];
		
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=="B")
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	else
	{
		$ls_bene="----------";
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";		
	}
	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==2)
	{
		$estprog[0]=substr($_POST["txtprogramatico".$li_fila],0,2);
		$estprog[1]=substr($_POST["txtprogramatico".$li_fila],2,2);
		$estprog[2]=substr($_POST["txtprogramatico".$li_fila],4,2);
		$estprog[3]=substr($_POST["txtprogramatico".$li_fila],6,2);
		$estprog[4]=substr($_POST["txtprogramatico".$li_fila],8,2);
	}
	else
	{
		$estprog[0]=substr($_POST["txtprogramatico".$li_fila],0,20);
		$estprog[1]=substr($_POST["txtprogramatico".$li_fila],20,6);
		$estprog[2]=substr($_POST["txtprogramatico".$li_fila],26,3);
		$estprog[3]="00";
		$estprog[4]="00";
	}
	$estprog[0]  = $io_function->uf_cerosizquierda($estprog[0],20);
	$estprog[1]  = $io_function->uf_cerosizquierda($estprog[1],6);
	$estprog[2]  = $io_function->uf_cerosizquierda($estprog[2],3);
	$estprog[3]  = $io_function->uf_cerosizquierda($estprog[3],2);
	$estprog[4]  = $io_function->uf_cerosizquierda($estprog[4],2);
	
	$ls_cuenta=$_POST["txtcuenta".$li_fila];	
	$ls_procede_doc=$_POST["txtprocede".$li_fila];
	$ls_descripcion=$_POST["txtdescripcion".$li_fila];
	$ls_documento=$_POST["txtdocumento".$li_fila];
	$ls_operacion=$_POST["txtoperacion".$li_fila];
	$ldec_monto_anterior=$_POST["txtmonto".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	
	$ls_mensaje=$io_int_spg->uf_operacion_codigo_mensaje($ls_operacion);
	$io_int_spg->is_codemp     = $la_emp["codemp"];
	$io_int_spg->id_fecha      = $io_function->uf_convertirdatetobd($ld_fecha);
	$io_int_spg->is_procedencia= $ls_proccomp;
	$io_int_spg->is_comprobante= $ls_comprobante;
	$io_int_spg->is_tipo       = $ls_tipo;
	$io_int_spg->is_cod_prov   = $ls_prov;
	$io_int_spg->is_ced_ben    = $ls_bene;
	$io_int_spg->ib_AutoConta  = true;
    $ls_denominacion="";	
	$ls_status="";	
	$ls_sc_cuenta="";	
	if ($ls_tipo=="B")  
	{ $ls_fuente = $ls_bene; }	
	else
	{ 
		if ($ls_tipo=="P")
		 {  
			$ls_fuente = $ls_prov; 
		 }	
		 else 
		 {  
			$ls_fuente = "----------"; 
		 } 
	}
	
	if(!$io_int_spg->uf_spg_select_cuenta($la_emp["codemp"],$estprog,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
	{  
	  return false;
	}
	
	$lb_valido=$io_int_spg->uf_int_spg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,$ls_fuente,$ls_prov,$ls_bene,
													     $estprog,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													     $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta);
	if($lb_valido)
	{
		$io_msg->message(" Registro Eliminado Satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacion." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$estprog[0]."-".$estprog[1]."-".$estprog[2]."-00-00; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$io_int_spg->io_sql->commit();
	}
	else
	{
 		$io_int_spg->io_sql->rollback();
		$io_msg->message(" Registro No Fue Eliminado ");
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

if($ls_operacion=="DELETESCG")		
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   =$_POST["txtprovbene"];	
	$ls_tipo	   =$_POST["tipo"];
	$li_fila	   =$_POST["fila"];
	$ls_desproben  = $_POST["txtdesproben"];
		
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	
	
	$ls_cuenta=$_POST["txtcontable".$li_fila];	
	$ls_procdoc=$_POST["txtprocdoc".$li_fila];
	$ls_desdoc=$_POST["txtdesdoc".$li_fila];
	$ls_docscg=$_POST["txtdocscg".$li_fila];
	$ls_debhab=$_POST["txtdebhab".$li_fila];
	$ldec_monto_anterior=$_POST["txtmontocont".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	
	//$ls_mensaje=$int_scg->uf_operacion_codigo_mensaje($ls_operacion);
	$io_int_scg->is_codemp=$la_emp["codemp"];
	$io_int_scg->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$io_int_scg->is_procedencia=$ls_proccomp;
	$io_int_scg->is_comprobante=$ls_comprobante;
	$io_int_scg->is_tipo=$ls_tipo;
	$io_int_scg->is_cod_prov=$ls_prov;
	$io_int_scg->is_ced_ben=$ls_bene;
	$io_int_scg->ib_AutoConta=true;
	
						
	$lb_valido=$io_int_scg->uf_scg_procesar_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$io_int_scg->id_fecha, $ls_cuenta, $ls_procdoc, $ls_docscg,$ls_debhab, $ldec_monto_anterior);
	if($lb_valido)
	{
		$io_msg->message(" Registro Eliminado Satisfactoriamente");
		$io_int_scg->io_sql->commit();
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento contable ".$ls_docscg." con operacion ".$ls_debhab." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
	}
	else
	{
 		$io_int_scg->io_sql->rollback();
		$io_msg->message(" Registro No Fue Eliminado ");
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

function uf_cargar_dt($ls_codemp,$ls_proccomp,$ls_comprobante,$ld_fecha)
{
	global $in_classcmp;
	global $la_emp;
	global $totalpre;
	global $totalcon;
	global $object;
	global $object2;
	global $ldec_mondeb;
	global $ldec_monhab;
	global $ldec_diferencia;
	global $ldec_totspg;
	$ldec_totspg=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$i=0;
	$rs_dtcmp=$in_classcmp->uf_cargar_dt_comprobante($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtcmp);
	$totalpre=1;
	$totalcon=1;
	if($li_numrows>0)
	{
	    $totalpre=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtcmp))
		{
			$i=$i+1;
			$ls_cuenta=$row["spg_cuenta"];
            $li_estmodest=$la_emp["estmodest"];
			
			if($li_estmodest==2)
			{
				$ls_programatico=substr($row["codest1"],-2).substr($row["codest2"],-2).substr($row["codest3"],-2).substr($row["codest4"],-2).substr($row["codest5"],-2);
			}
			else
			{
				 $ls_programatico=$row["codest1"].$row["codest2"].$row["codest3"];
			}
			$ls_documento=$row["documento"];
			$ls_descripcion=$row["descripcion"];
			$ls_procede=$row["procede_doc"];
			$ls_operacion=$row["operacion"];
			$ldec_monto=$row["monto"];
			if($li_estmodest==1)
			{
			   $li_size=32;
			   $li_maxlength=29;
			}
			else
			{
			   $li_size=40;
			   $li_maxlength=33;
			}
			$object[$i][1]="<input type=text name=txtcuenta".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][2]="<input type=text name=txtprogramatico".$i." value='".$ls_programatico."' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength>"; 
			$object[$i][3]="<input type=text name=txtdocumento".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][4]="<input type=text name=txtdescripcion".$i." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left>";
			$object[$i][5]="<input type=text name=txtprocede".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[$i][6]="<input type=text name=txtoperacion".$i." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[$i][7]="<input type=text name=txtmonto".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
			$object[$i][8] ="<a href=javascript:uf_delete_dt_presupuesto(".($i).");><img src=../shared/imagebank/tools15/eliminar.png width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";
			$ldec_totspg = $ldec_totspg + $ldec_monto;
		}
		$in_classcmp->io_sql->free_result($rs_dtcmp);
	}
	else
	{
        $li_estmodest=$la_emp["estmodest"];
		if($li_estmodest==1)
		{
		   $li_size=32;
		   $li_maxlength=29;
		}
		else
		{
		   $li_size=40;
		   $li_maxlength=33;
		}
		$object[1][1]="<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][2]="<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength>"; 
		$object[1][3]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][4]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:left>";
		$object[1][5]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][6]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
		$object[1][7]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
		$object[1][8] ="";
	}
$i=0;
$rs_dtscg=$in_classcmp->uf_cargar_dt_contable_cmp($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtscg);
//$totalpre=$li_numrows;

	if($li_numrows>0)
	{
	    $totalcon=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtscg))
		{
			$i=$i+1;+
			$ls_sc_cuenta=$row["sc_cuenta"];
			$ls_documento=$row["documento"];
			$ls_desdoc=$row["descripcion"];
			$ls_procdoc=$row["procede_doc"];
			$ls_debhab=$row["debhab"];
			$ldec_monto=$row["monto"];
			if($ls_debhab=="D")	
			{
				$ldec_mondeb=$ldec_mondeb+$ldec_monto;
			}
			else
			{
				$ldec_monhab=$ldec_monhab+$ldec_monto;
			}
			if($li_estmodest==1)
			{
			   $li_sizedoc=30;
			   $li_sizedes=40;
			}
			else
			{
			   $li_sizedoc=37;
			   $li_sizedes=41;
			}
			$object2[$i][1]="<input type=text name=txtcontable".$i." value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[$i][2]="<input type=text name=txtdocscg".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[$i][3]="<input type=text name=txtdesdoc".$i." value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[$i][4]="<input type=text name=txtprocdoc".$i." value='".$ls_procdoc."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[$i][5]="<input type=text name=txtdebhab".$i." value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[$i][6]="<input type=text name=txtmontocont".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28>";
			$object2[$i][7] ="<a href=javascript:uf_delete_dt_contable(".($i).");><img src=../shared/imagebank/tools15/eliminar.png width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
		}
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
		$in_classcmp->io_sql->free_result($rs_dtscg);
	}
	else
	{
            $li_estmodest=$la_emp["estmodest"];
			if($li_estmodest==1)
			{
			   $li_sizedoc=30;
			   $li_sizedes=40;
			}
			else
			{
			   $li_sizedoc=37;
			   $li_sizedes=41;
			}
			$object2[1][1]="<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[1][2]="<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[1][3]="<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[1][4]="<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[1][5]="<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[1][6]="<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
			$object2[1][7] ="";
	}

}
	if($ls_tipo=='P')
	{
	$prov_sel="checked";
	$bene_sel="";
	$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
	$prov_sel="";
	$bene_sel="checked";
	$ning_sel="";
	}
	else
	{
	$prov_sel="";
	$bene_sel="";
	$ning_sel="checked";
	}
	if($ls_status=="C")
	{
		$readonly="readonly";
	}
	else
	{
		$readonly="";
	}
	
?> 
<form name="form1" method="post" action=""><div >
<?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		if (($lb_permisos)||($ls_logusr=="PSEGIS"))
		{
			print("<input type=hidden name=permisos id=permisos value='$lb_permisos'>");
			
		}
		else
		{
			
			print("<script language=JavaScript>");
			print(" location.href='tepuywindow_blank.php'");
			print("</script>");
		}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="780" height="293" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Comprobante Presupuestario </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="98" height="22">
        <p align="right"> Procedencia</p></td>
        <td width="519">
          <input name="txtproccomp" type="text" id="txtproccomp" value="<?php print $ls_procede?>" readonly="true" style="text-align:center" >
          <input name="status_actual" type="hidden" id="status_actual" value="<?php print $ls_status;?>"></td>
        <td width="161"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center"  value="<?php print $ls_fecha?>" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  <?php print $readonly;?>  size="15" maxlength="15" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Comprobante</p></td>
        <td><input name="txtcomprobante" type="text" id="txtcomprobante" onBlur="javascript: valid_cmp(document.form1.txtcomprobante.value);" maxlength="15" style="text-align:center" value="<?php print $ls_comprobante ?>" <?php print $readonly ;?>></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Descripci&oacute;n </p></td>
        <td colspan="2"><input name="txtdesccomp" type="text" id="txtdesccomp" size="120" value="<?php print $ls_descripcion;?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td><p>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="P" <?php print $prov_sel;?> onClick="javascript:uf_verificar_provbene();">
  Proveedor</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="B" <?php print $bene_sel;?> onClick="javascript:uf_verificar_provbene();">
  Beneficiario</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="-" <?php print $ning_sel;?> onClick="javascript:uf_verificar_provbene();">
  Ninguno</label>
          <input name="tipsel" type="hidden" id="tipsel" value="<?php print $ls_tipo;?>">
          <br>
        </p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">C&oacute;digo/C&eacute;dula</p></td>
        <td>
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" readonly >
          <a href="javascript:catprovbene()"><img  src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="60" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>" ></td>
        <td>&nbsp;</td>
      </tr>
      <tr >
        <td height="22" colspan="3"><div align="center"></div></td>
      </tr>
      <tr >
        <td height="22" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.png" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
      </tr>
        <tr>
        <td height="22" colspan="3">
		<div align="center"><?php $io_grid->makegrid($totalpre,$title,$object,820,'Detalles Presupuestarios',$grid1);?>
		  <input name="totpre" type="hidden" id="totpre" value="<?php print $totalpre?>">
		</div></td>
      </tr>
        <tr>
          <td height="17" colspan="3"><table width="805" border="0" cellpadding="0" cellspacing="0" class="celdas-blancas">
            <tr>
              <td width="650" height="22"><div align="right">Total Presupuestario </div></td>
              <td width="155"><div align="center">
                <input name="txttotspg" type="text" id="txttotspg" value="<?php print number_format($ldec_totspg,2,",",".");?>" size="28" style="text-align:right">
              </div></td>
            </tr>
          </table></td>
        </tr>
      <tr>
        <td height="22" colspan="3"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.png" width="15" height="15" border="0"></a><a href="javascript: uf_agregar_dtcon();">Agregar detalle Contable </a> </p>        </td>
      </tr>
        <tr>
          <td height="22" colspan="3">
		  <div align="center"><?php $io_grid->makegrid($totalcon,$title2,$object2,820,'Detalles Contable',$grid2);?> 
		    <input name="totcon" type="hidden" id="totcon" value="<?php print $totalcon?>">
		  </div></td>
        </tr>
	  <br>
      <tr>
        <td height="73" colspan="3" valign="top" bordercolor="#FFFFFF"><table width="805" border="0" align="center" cellpadding="0" cellspacing="0" class="celdas-blancas">
            <tr>
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div align="right">Debe</div></td>
              <td><div align="center">
                <input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<?php print number_format($ldec_mondeb,2,",",".");?>" size="28" readonly>
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div align="right">Haber</div></td>
              <td><div align="center">
                <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<?php print number_format($ldec_monhab,2,",",".");?>" size="28" readonly>
              </div></td>
            </tr>
            <tr>
              <td width="79" height="22">&nbsp;</td>
              <td width="100">&nbsp;</td>
              <td width="131">&nbsp;</td>
              <td width="212"><div align="right"> </div></td>
              <td width="118"><div align="center"></div>
              <div align="right">Diferencia</div></td>
              <td width="165"><div align="center">
                  <p>
                    <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print number_format($ldec_diferencia,2,",",".") ;?>" size="28" readonly>
                  </p>
              </div></td>
            </tr>
        </table></td>
      </tr>
    </table>
  <input name="operacion" type="hidden" id="operacion">
    <input name="totalpre" type="hidden" id="totalpre" value="<?php print $totpre; ?>" >
    <input name="totalcon" type="hidden" id="totalcon" value="<?php print $totcon; ?>" > 
    <input name="fila" type="hidden" id="fila" value="<?php print $li_fila;?>">
</div>
</form>
</body>
<script language="javascript">
//Funcion de carga de Catalogos

function cat()
{
	f=document.form1;
	f.txtcuenta.disabled=false;
	window.open("tepuy_catdinamic_ctas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function catprovbene()
{
f=document.form1;
if(f.tipo[0].checked==true)
{
	f.txtprovbene.disabled=false;	
	window.open("tepuy_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}
else if(f.tipo[1].checked==true)
{
	f.txtprovbene.disabled=false;	
	window.open("tepuy_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}
}

//Funciones de operaciones para el detalle del comprobante

function editar(fila,cuenta , deno , procede,documento,debhab,monto)
{
	f=document.form1;
	f.fila.value=fila;
	f.txtcuenta.disabled=false;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=deno;
	f.txtprocdoc.value=procede;
	f.txtdocumento.value=documento;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value ="EDITAR";
	f.action="tepuy_spg_p_comprobante.php";
	f.txtcuenta.focus(true	);
	f.submit();

}

function uf_save_mov()
{
	f=document.form1;
	f.operacion.value="AGREGAR";
	f.action="tepuy_spg_p_comprobante.php";
	f.submit();
}
function uf_cargar_dt()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="tepuy_spg_p_comprobante.php";
	f.submit();
}
function uf_del_mov(cuenta,desc,proc,doc,debhab,monto)
{
	f=document.form1;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="DELMOV";
	f.action="tepuy_spg_p_comprobante.php";
	f.submit();
}

function uf_upd_mov(fila,cuenta,desc,proc,doc,debhab,monto)
{
	f=document.form1;
	f.fila.value=fila;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="UPDMOV";
	f.action="tepuy_spg_p_comprobante.php";
	f.submit();
}
//Funciones de operaciones sobre el comprobante
function ue_nuevo()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		f.operacion.value="NUEVO";
		f.action="tepuy_spg_p_comprobante.php";
		f.submit();
	}
}
function ue_guardar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=="SPGCMP")
	{
		if(valida_campos())
		{
			
			ldec_diferencia=f.txtdiferencia.value;
			ldec_diferencia=uf_convertir_monto(ldec_diferencia);
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado");
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="tepuy_spg_p_comprobante.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este módulo");
	}
	
}
function ue_eliminar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=="SPGCMP")
	{
		if(confirm("Seguro desea eliminar el comprobante"))
		{
	
		f.operacion.value="ELIMINAR";
		f.action="tepuy_spg_p_comprobante.php";
		f.submit();
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este módulo");
	}	
}
function ue_buscar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		window.open("tepuy_cat_comprobantesspg.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_cerrar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia==0)
	{
		f.action="tepuywindow_blank.php";
		f.submit();
	}
	else
	{
		alert("Comprobante descuadrado contablemente");
	}
}
function ue_close()
{
	close()
}

function valida_campos()
{
f=document.form1;

ls_procede=f.txtproccomp.value;
ls_fecha=f.txtfecha.value;
ls_comprobante=f.txtcomprobante.value;
ls_desccomp=f.txtdesccomp.value;
ls_tipo=f.tipo.value;
ls_provbene=f.txtprovbene.value;
lb_valido=true;

if(ls_procede=="")
{
	alert("Introduzca la procedencia del comprobante");
	lb_valido=false;
}

if((ls_fecha=="")||(ls_fecha=="01/01/1900")||(ls_fecha=="01-01-1900"))
{
	alert("Introduzca una fecha valida");
	lb_valido=false;
}

if((ls_comprobante=="")||(ls_comprobante=="000000000000000"))
{
	alert("Introduzca un numero de comprobante");
	lb_valido=false;
}

if(ls_desccomp=="")
{
	alert("Debe registrar la descripcion del comporbante");
	lb_valido=false;
}

if((ls_tipo=="B")||(ls_tipo=="P"))
{
	if(ls_provbene=="")
	{
		alert("Debe seleccionar un Proveedor o Beneficiario");
		lb_valido=false;
	}
}
return 	lb_valido;

}

function valid_cmp(cmp)
{
rellenar_cad(cmp,15,"cmp");
f=document.form1;
f.operacion.value="VALIDAFECHA";
f.action="tepuy_spg_p_comprobante.php";
f.submit();
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

function uf_verificar_provbene()
{
	f=document.form1;
	ls_tipsel=f.tipsel.value;
	if(f.tipo[0].checked)
	{
		if(ls_tipsel!='P')
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipsel.value='P';
		}
	}
	if(f.tipo[1].checked)
	{
		if(ls_tipsel!='B')
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipsel.value='B';
		}
	}
	if(f.tipo[2].checked)
	{
		if(ls_tipsel!='-')
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="";
			f.tipsel.value='-';
		}
	}
}

function EvaluateText(cadena, obj){ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f"){ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
 	 if (cadena == "%a") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==45)|| (event.keyCode ==47))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
   
   function  uf_agregar_dtpre()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = f.txtdesccomp.value;
	ls_provbene   = f.txtprovbene.value;	
	if(f.tipo[0].checked==true)
	{
		ls_tipo='P'
	}
	if(f.tipo[1].checked==true)
	{
		ls_tipo='B'
	}
	if(f.tipo[2].checked==true)
	{
		ls_tipo='-'
	}
	if(ls_proccomp=="SPGCMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo))
		{
			ls_pagina = "tepuy_w_regdt_presupuesto.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Complete los datos del comprobante");
		}
	}
	else
	{
		 alert("No puede editar un comprobante que no fue generado por este módulo");
	}
   }
    function  uf_agregar_dtcon()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = f.txtdesccomp.value;
	ls_provbene   = f.txtprovbene.value;	
	ls_tipo	      = f.tipo.value;
	if(ls_proccomp=="SPGCMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo!=""))
		{
			ls_pagina = "tepuy_w_regdt_contable.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=590,height=220,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Complete los datos del comprobante");
		}
	}
	else
	{
	     alert("No puede editar un comprobante que no fue generado por este módulo");
	}
   }
   
   function uf_delete_dt_presupuesto(row)
   {
	  f=document.form1;
	  ls_procede=f.txtproccomp.value;
	  if(ls_procede=="SPGCMP")
	  {
		  f.action="tepuy_spg_p_comprobante.php";
		  f.operacion.value="DELETESPG";
		 // grid_SPG.deleteRow(row);
		  f.fila.value=row;
		  f.submit();
	   }
	   else
	   {
	     alert("No puede editar un comprobante que no fue generado por este módulo");
	   }
    }  
  
   function uf_delete_dt_contable(row)
   {
	  f=document.form1;
	  ls_procede=f.txtproccomp.value;
	  if(ls_procede=="SPGCMP")
	  {
		  f.action="tepuy_spg_p_comprobante.php";
		  f.operacion.value="DELETESCG";
		 // grid_SPG.deleteRow(row);
		  f.fila.value=row;
		  f.submit();
	  }
	  else
	  {
	  	  alert("No puede editar un comprobante que no fue generado por este módulo");
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
   }
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>