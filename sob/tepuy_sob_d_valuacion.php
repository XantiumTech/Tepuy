<?php
session_start();
//	ini_set('display_errors', 1);
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Valuacion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
.Estilo1 {color: #006699}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
/******************************************/
/* FECHA: 25/03/2006                      */ 
/* AUTOR: Juniors Fraga                 */         
/******************************************/

/********************************************         SEGURIDAD       ****************************************************/
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="tepuy_sob_d_valuacion.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}
/*****************************************         SEGURIDAD      *********************************************************************/


/**************************************** DECLARACIONES  ********************************************************************************/
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/tepuy_sob_class_obra.php");
require_once("class_folder/tepuy_sob_class_asignacion.php");
require_once("class_folder/tepuy_sob_c_valuacion.php");
require_once("class_folder/tepuy_sob_c_funciones_sob.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
$io_asignacion=new tepuy_sob_class_asignacion();
$io_valuacion=new tepuy_sob_c_valuacion();
$io_obra=new tepuy_sob_class_obra();
$io_funcsob=new tepuy_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_function=new class_funciones();
$io_datastore=new class_datastore();

$ls_tituloretenciones="Retenciones Asignadas";
$li_anchoretenciones=600;
$ls_nametable="grid";
$la_columretenciones[1]="Código";
$la_columretenciones[2]="Descripción";
$la_columretenciones[3]="Cuenta";
$la_columretenciones[4]="Deducible";
$la_columretenciones[5]="Monto";
$la_columretenciones[6]="Total";
$la_columretenciones[7]="Edición";
$la_columretenciones[8]="";

$ls_titulopartidas="Datos de la Obra";
$li_anchopartidas=750;
$ls_nametable="grid2";
/*$la_columpartidas[1]="";
$la_columpartidas[2]="Código";
$la_columpartidas[3]="Partida";
$la_columpartidas[4]="Uni. Med.";
$la_columpartidas[5]="(Ref)Pre. Uni.";
$la_columpartidas[6]="Pre. Unitario";
$la_columpartidas[7]="Cant(A)";
$la_columpartidas[8]="Cant(V)";
$la_columpartidas[9]="Total";*/
$la_columpartidas[1]="";
$la_columpartidas[2]="Código";
$la_columpartidas[3]="Nombre de la Obra";//"Partida";
$la_columpartidas[4]="Monto Presupuestado";//"(Ref)Pre. Uni.";
$la_columpartidas[5]="I.V.A.";
$la_columpartidas[6]="Monto Total del Contrato";
$la_columpartidas[7]="Monto a Valuar (Base)";
$la_columpartidas[8]="Total Ejecutado+Valuacion";
$la_columpartidas[9]="";

$ls_titulocargos="Cargos";
$li_anchocargos=600;
$ls_nametable="grid3";
$la_columcargos[1]="Código";
$la_columcargos[2]="Denominación";
$la_columcargos[3]="Monto";
$la_columcargos[4]="Edición";

/****************************************************************************************************************************************/

/******************************************************	OBTENER VALORES DE LOS TXT *********************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_opemostrarA=$_POST["opemostrarA"];
	$ls_opemostrar=$_POST["opemostrar"];
	$ls_operacion=$_POST["operacion"];
	$ls_codval=$_POST["txtcodval"];
	$ls_numrecdoc=$_POST["txtnumrecdoc"];
	$ls_codcon=$_POST["txtcodcon"];
	$ls_fecinival=$_POST["txtfecinival"];
	$ls_fecfinval=$_POST["txtfecfinval"];
	$ls_obsval=$_POST["txtobsval"];
	$ls_fecha=$_POST["txtfecha"];
	$ls_estadoval=$_POST["txtestval"];
	$li_filasretenciones=$_POST["hidfilasretenciones"];
	$li_removerretenciones=$_POST["hidremoverretenciones"];
	$ls_amoant=$_POST["txtamoant"];
	$ls_amoactual=$_POST["txtamoactual"];
	$ls_poramoactual=$_POST["txtporamoactual"];
    $ls_amoobs=$_POST["txtamoobs"];
    $ls_amores=$_POST["txtamores"]; 
	$ls_amotot=$_POST["txtamotot"];
	$ls_totant=$_POST["hidtotant"]; 
    $ls_totcon=$_POST["hidtotcon"];
	$ls_desobr=$_POST["hiddesobr"];
	$ls_puncue=$_POST["hidpuncue"];
    $ls_estcon=$_POST["hidestcon"];  
    $ls_moncon=$_POST["hidmoncon"]; 
    $ls_feccon=$_POST["hidfeccon"];
	$ls_totcon=$_POST["hidtotcon"];
	$ls_totant=$_POST["hidtotant"]; 
	$ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_subtot=$_POST["txtsubtot"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
	$ls_montotval=$_POST["txtmontotval"];
	$ls_totreten=$_POST["txttotreten"];
	$ls_hidamototbd=$_POST["hidamototbd"]; 
	$ls_hidamoresbd=$_POST["hidamoresbd"];
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filascargos=$_POST["filascargos"];
	$ls_hidcodasi =$_POST["hidcodasi"];
	$ls_chk=$_POST["hidstatus"];
	$ls_codtipdoc=$_POST["txtcodtipdoc"];
	$ls_numref=$_POST["txtnumref"];
	$ls_numfactura=$_POST["txtnumfactura"];
	$ls_numrecdoc=$_POST["txtnumrecdoc"];
	
	$li_filaspartidas=$_POST["filaspartidas"];
	for($li_i=1;$li_i<$li_filaspartidas;$li_i++)
     {
			$ls_codigo=$_POST["txtcodpar".$li_i];
			$ls_nombre=$_POST["txtnompar".$li_i];
			//$ls_unidad=$_POST["txtnomuni".$li_i];
			$ls_preuni=$_POST["txtpreuni".$li_i];
			$ls_preunimod=$_POST["txtpreunimod".$li_i];
			$ls_canttot=$_POST["txtcanttot".$li_i];
			$ls_cantpar=$_POST["txtcantpar".$li_i];
			$ls_total=$_POST["txttotal".$li_i];
			$ls_canpareje=$_POST["canpareje".$li_i];
			$ls_codasi=$_POST["codasi".$li_i];
			$ls_codobr=$_POST["codobr".$li_i];
			
			if(!empty($_POST["flagpar".$li_i]))
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde>";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde>";
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        //$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";
			if($io_funcsob->uf_convertir_cadenanumero($ls_canttot)==0)
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        //$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";
			}
	}	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	//$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";	
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal1".$li_filaspartidas." type=hidden id=txttotal1".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";	
	
		
	if ($ls_operacion != "ue_cargarretenciones" && $ls_operacion != "ue_removerretenciones")
	{

		for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		{
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$ls_cuenta=$_POST["txtcueret".$li_i];
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$ls_monret=$_POST["txtmonret".$li_i];

			$ls_totret=$_POST["txttotret".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$ls_cubretotal=$_POST["cubretotal".$li_i];

			$ls_porded=$_POST["porded".$li_i];

			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:left size=20 readonly>";
//			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:left size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";

			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:left size=15 readonly><input name=deduccion".$li_i." type=hidden id=deduccion".$li_i." value='".$ls_deduccion."'>";
			$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:left size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this)>";

			$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:left size=15 readonly>";

		    $la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		$la_objectretenciones[$li_i][8]="<input name=txtcubretotal".$li_i." type=hidden id=txtcubretotal".$li_i." class=sin-borde value='".$ls_cubretotal."' style= text-align:left size=1 readonly>";
		}	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][8]="<input name=txtcubretotal".$li_filasretenciones." type=hidden id=txtcubretotal".$ls_cubretotal." class=sin-borde style= text-align:left size=1 readonly>";
	}
	
	if ($ls_operacion != "ue_cargarcargo" && $ls_operacion != "ue_removercargo")
	{

		$li_filascargos=$_POST["filascargos"];

		for($li_i=1;$li_i<$li_filascargos;$li_i++)
		{		
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_moncue=$_POST["txtmoncar".$li_i];
//			$ls_moncue=$io_funcsob->uf_convertir_cadenanumero($ls_moncue);
//			$ls_moncue=$io_funcsob->uf_convertir_numerocadena($ls_moncue);

			$ls_formula=$_POST["formu".$li_i];
			$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
			$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncue."' readonly>";
			$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

	
}
/*******************************************************************************************************************************************************/

/************************************************ INICIALIZA LAS VARIABLES SI NO HAY SUBMIT *******************************************************************************************************/
else
{
    $ls_opemostrar="";
	$ls_opemostrarA="";
	$ls_operacion="";
	$ls_codval="";
	$ls_codcon="";
	$ls_fecinival="";
	$ls_fecfinval="";
	$ls_obsval="";
	$ls_fecha="";
	$ls_estadoval="";
	$ls_amoant="0,00";
	$ls_amotot="0,00";
	$ls_poramoactual="0,00";
	$ls_amoactual="0,00";
    $ls_amoobs="";
    $ls_amores="0,00"; 
	$ls_totant=""; 
    $ls_totcon="";
	$ls_desobr="";
	$ls_puncue="";
    $ls_estcon="";  
    $ls_moncon=""; 
    $ls_feccon="";
	$ls_totcon="";
	$ls_totant=""; 
	$ls_hidamototbd=""; 
	$ls_hidamoresbd="";
	$li_removerretenciones="";
	$li_removercargo="";
	$ls_subtotpar="0,00"; 
	$ls_subtot="0,00"; 
	$ls_basimpval="0,00";
	$ls_montotval="0,00";
	$ls_totreten="0,00";
	$ls_hidcodasi="";
	$ls_chk="";
	$ls_codtipdoc="00005";
	$ls_dentipdoc="ORDEN DE PAGO";
	$ls_numref="";
	$ls_numfactura="";
	$ls_numrecdoc="";
	
	$li_filasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=20 readonly>";

	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=15 readonly><input name=formula1 type=hidden id=formula1>";

	$la_objectretenciones[1][5]="<input name=txtmonret1 type=text id=txtmonret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][6]="<input name=txttotret1 type=text id=txttotret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][8]="<input name=txtcubretotal type=hidden id=txtcubretotal class=sin-borde style= text-align:center size=1 readonly>";
    
	$li_filaspartidas=1;
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	//$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal1".$li_filaspartidas." type=hidden id=txttotal1".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	
	$li_filascargos=1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}
/***************************************************************************************************************************************************************************/


/************************************************ PREPARANDO INSERCION DE NUEVO REGISTRO ****************************************************************************/
if($ls_operacion=="ue_nuevo")
{
    require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/tepuy_include.php");		
	$io_include=new tepuy_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	$io_valuacion->uf_select_newcodigo($ls_codcon,&$ls_codval);
	$ls_fecinival="";
	$ls_fecfinval="";
	$ls_obsval="";
	$ls_fecha=date("d/m/Y");
	$ls_estadoval="EMITIDO";
	$lb_flag=$io_valuacion->uf_select_valanterior($ls_codcon,$ls_codval,$la_data);
    if($lb_flag)
	{
	  $ls_amoant=$io_funcsob->uf_convertir_numerocadena($la_data["amoval"][1]);
	  $ls_amotot=$io_funcsob->uf_convertir_numerocadena($la_data["amototval"][1]);
	  $ls_hidamototbd=$la_data["amototval"][1];
	  $ls_amores=$io_funcsob->uf_convertir_numerocadena($la_data["amoresval"][1]);
	  
	}
	else
	{
      $ls_hidamoant="0,00";
	  $ls_hidamotot="0,00";
 	  $ls_hidamores="0,00";
	}
////////////////////// NUEVO: PERMITE CARGAR LOS IVA DE LA OBRA /////////////////////////
   /************************CARGANDO CARGOS**************************************************/ 
	//uf_select_partidasasignadas($ls_codcon,&$la_partidas,&$li_totalfilas);
	//print "contrato: ".$ls_codcon;
    $lb_validoca=$io_valuacion->uf_select_cargoasignacion($ls_codcon,$la_cargos,$li_totalfilas);
	if($lb_validoca)
	{
	$io_datastore->data=$la_cargos;
	$li_filascargos=$io_datastore->getRowCount("codcar");
	//print "cargos: ".$li_filascargos;
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codcar",$li_i);
		$ls_nombre=$io_datastore->getValue("dencar",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_formula=$io_datastore->getValue("formula",$li_i);
		$ls_codestprog=$io_datastore->getValue("codestprog",$li_i);
		$ls_spg_cuenta=$io_datastore->getValue("spg_cuenta",$li_i);

		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";

		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";

//		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";


		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	/*****************************************************************************************/

	
	$li_filasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=15 readonly><input name=formula1 type=hidden id=formula1>";
	$la_objectretenciones[1][5]="<input name=txtmonret1 type=text id=txtmonret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][6]="<input name=txttotret1 type=text id=txttotret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		
/*	$li_filascargos=1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
*/	
	
}
/***************************************************************************************************************************************************************************/

/*************************************************INSERTAR CAMPO EN GRID RETENCIONES**************************************************************************************************************************/

elseif($ls_operacion=="ue_cargarretenciones")
{

	$li_filasretenciones=$li_filasretenciones+1;
// ESTABLE DESDE EL MONTO DEL ANTICIPO Y LO CLAVA EN EL TOTAL RETENCIONES: PERO LO HACE DOS VECES AL SELECCIONAR LA RETENCION (ANTICIPO)
	$tmpo = ($_POST["txtamoactual"] != "") ? $_POST["txtamoactual"] : 0;
	//$ls_totreten = $io_funcsob->uf_convertir_cadenanumero($tmpo);
	$ls_totreten=0;
	$tmpo1 = ($_POST["txtamototval"] != "") ? $_POST["txtmontotval"] : 0;
	$tmpo1 = $io_funcsob->uf_convertir_cadenanumero($tmpo1);
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];

		$ls_monret=$_POST["txtmonret".$li_i];

		$ls_totret=$_POST["txttotret".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$ls_cubretotal=$_POST["txtcubretotal".$li_i];

//ejecutando con javascript (agregar detalle...)
		$ls_porded = $io_valuacion->uf_select_porcretencion($ls_codigo);
		$ls_tipded = $io_valuacion->uf_select_tiporetencion($ls_codigo);
//print $ls_tipded; print $_POST["totalcargos"];

		if ($ls_tipded == 1){
			// asi lo dejo tua //
			//$ls_monret = $_POST["totalcargos"];
			$ls_monret = $io_funcsob->uf_convertir_cadenanumero($_POST["txtsubtot"]) - $io_funcsob->uf_convertir_cadenanumero($_POST["txtbasimpval"]);		
		}
		else
		{
			if ($ls_porded > 0.10)
			{
				//print "cargando a inicio 1";
				$ls_monret = $_POST["txtmontotval"];
				$ls_monret = $io_funcsob->uf_convertir_cadenanumero($ls_monret);
			}
			else
			{
				if($ls_cubretotal=='1')
				{
					$ls_monret = $_POST["txtmontotval"];
					$ls_monret = $io_funcsob->uf_convertir_cadenanumero($ls_monret);
				}
				else
				{
					$ls_monret = $_POST["txtbasimpval"];
					$ls_monret = $io_funcsob->uf_convertir_cadenanumero($ls_monret);
				}
			}
		}
				
		$ls_totret = $ls_monret * $ls_porded;
		$ls_totreten += $ls_totret;


		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_porded."'>";

//		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this)>";

		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_monret)."' style= text-align:center size=15 readonly>";

		$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_totret)."' style= text-align:center size=15 >";
		$la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		$la_objectretenciones[$li_i][8]="<input name=txtcubretotal".$li_i." type=hidden id=txtcubretotal".$li_i." class=sin-borde value='".$ls_cubretotal."' style= text-align:center size=1 >";
	}	
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
    $la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 >";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 >";
	$la_objectretenciones[$li_filasretenciones][8]="<input name=txtcubretotal".$li_filasretenciones." type=hidden id=txtcubretotal".$li_filasretenciones." class=sin-borde style= text-align:left size=1 >";
	

}
/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID RETENCIONES********************************************************************************************************************/
elseif($ls_operacion=="ue_removerretenciones")
{
	$li_filasretenciones=$li_filasretenciones-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filasretenciones;$li_i++)
	{
		if($li_i!=$li_removerretenciones)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$ls_cuenta=$_POST["txtcueret".$li_i];
			$ls_monret=$_POST["txtmonret".$li_i];
			$ls_totret=$_POST["txttotret".$li_i];
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$ls_cubretotal=$_POST["txtcubretotal".$li_i];

			$la_objectretenciones[$li_temp][1]="<input name=txtcodret".$li_temp." type=text id=txtcodret".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_temp][2]="<input name=txtdesret".$li_temp." type=text id=txtdesret".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_temp][3]="<input name=txtcueret".$li_temp." type=text id=txtcueret".$li_temp." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
			$la_objectretenciones[$li_temp][4]="<input name=txtdedret".$li_temp." type=text id=txtdedret".$li_temp." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_temp][5]="<input name=txtmonret".$li_temp." type=text id=txtmonret".$li_temp." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this)>";
			$la_objectretenciones[$li_temp][6]="<input name=txttotret".$li_temp." type=text id=txttotret".$li_temp." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
			$la_objectretenciones[$li_temp][7]="<a href=javascript:ue_removerretenciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			$la_objectretenciones[$li_temp][8]="<input name=txtcubretotal".$li_temp." type=hidden id=txtcubretotal".$li_temp." class=sin-borde value='".$ls_cubretotal."' style= text-align:center size=1 readonly>";
		}
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 >";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][8]="<input name=txtcubretotal".$li_filasretenciones." type=hidden id=txtcubretotal".$li_filasretenciones." class=sin-borde style= text-align:left size=1 readonly>";
}
/***************************************************************************************************************************************************************************/
/*************************************************INSERTAR CAMPO EN GRID CARGOS**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcargo")
{
	$ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
//print $ls_subtotpar;
	$ld_subtotpar=$io_funcsob->uf_convertir_cadenanumero($ls_subtotpar);

//	$ld_basimpval=$ld_subtotpar;
	$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);

	$ld_subtot=0;

	$total_cargos=0;
	
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos+1;
	
	for($li_i=1;$li_i<$li_filascargos;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcar".$li_i];
		$ls_nombre=$_POST["txtnomcar".$li_i];
		$ls_formula=$_POST["formu".$li_i];

		$ls_porcar = $io_valuacion->uf_select_porcar($ls_codigo);

//		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_basimpval,$lb_valido);

		$ld_result = $ld_basimpval * $ls_porcar / 100;

		$total_cargos += $ld_result;

//		$tmonto = $io_funcsob->uf_convertir_numerocadena($ld_result);

		$ld_subtot = $ld_subtot + $ld_result;
//		$ld_subtot = $tmonto;

		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly>";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_result)."' readonly>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	

	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";

	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>"."<input name=totalcargos type=hidden id=totalcargos value='" . $total_cargos . "'>";

	$la_objectcargos[$li_filascargos][5]=

     $ld_subtotal=$ld_subtotpar-$ld_basimpval;

	 $ld_resultado=$ld_basimpval+$ld_subtot+$ld_subtotal;  

//	 $ld_resultado= $ld_subtotpar + $ld_basimpval+$ld_subtot+$ld_subtotal;  

	 $ls_subtot=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
}

/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID CARGOS********************************************************************************************************************/
elseif($ls_operacion=="ue_removercargo")
{
    $ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
	$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
	$ld_subtotpar=$io_funcsob->uf_convertir_cadenanumero($ls_subtotpar);
	$ld_subtot=0;
	        
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos-1;
	$li_removercargo=$_POST["hidremovercargo"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		if($li_i!=$li_removercargo)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_formula=$_POST["formu".$li_i];
			$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_basimpval,$lb_valido);
		    $ld_subtot=$ld_subtot+$ld_result;
			$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
			$la_objectcargos[$li_temp][2]="<input name=txtnomcar".$li_temp." type=text id=txtnomcar".$li_temp." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_temp][3]="<input name=txtmoncar".$li_temp." type=text id=txtmoncar".$li_temp." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_result)."' readonly>";
			$la_objectcargos[$li_temp][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			
		}
	}
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

	 $ld_subtotal=$ld_subtotpar-$ld_basimpval;
	 $ld_resultado=$ld_basimpval+$ld_subtot+$ld_subtotal;  
	 $ls_subtot=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	
}

/***************************************************************************************************************************************************************************/

/*******************************************INSERCION DE REGISTRO EN BD*******************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{

  $ld_fecha=$io_function->uf_convertirdatetobd($ls_fecha);
  $ld_fecinival=$io_function->uf_convertirdatetobd($ls_fecinival);
  $ld_fecfinval=$io_function->uf_convertirdatetobd($ls_fecfinval);
  $lb_valido=$io_valuacion->uf_guardar_valuacion($ls_codval,$ls_codcon,$ld_fecha,$ld_fecinival,$ld_fecfinval,$ls_obsval,$ls_amoactual,$ls_amoobs,$ls_amoant,$ls_amotot,$ls_amores,$ls_basimpval,$ls_montotval,$ls_subtotpar,$ls_totreten,$ls_subtot,$la_seguridad,$ls_numrecdoc,$ls_numref,$ls_numfactura);
//die();
  if($lb_valido)
  {
    /************************************PARTIDAS*******************************************/
	$li_partidas=1;
    	$la_partidas["codpar"][1]="";
	$la_partidas["canteje"][1]="";
	$la_partidas["cant"][1]="";
	$la_partidas["preref"][1]="";
	$la_partidas["preval"][1]="";
	$la_partidas["codasi"][1]="";
	$la_partidas["codobr"][1]="";
	
    for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
     {
	   if(!empty($_POST["flagpar".$li_i]))
	   {
		//////////// REVISAR 2015 ///////
	   	$la_partidas["codpar"][$li_partidas]=$_POST["txtcodpar".$li_i];
		$la_partidas["canteje"][$li_partidas]=$_POST["canpareje".$li_i];
	    $la_partidas["cant"][$li_partidas]=$_POST["txtcantpar".$li_i];
		$la_partidas["preref"][$li_partidas]=$_POST["txtpreuni".$li_i];
	    $la_partidas["preval"][$li_partidas]=$_POST["txtpreunimod".$li_i];
		$la_partidas["codasi"][$li_partidas]=$_POST["codasi".$li_i];
	    $la_partidas["codobr"][$li_partidas]=$_POST["codobr".$li_i];
		$li_partidas++;
	   }
	 }
	$io_valuacion->uf_update_dtpartidas($ls_codval,$ls_codcon,$la_partidas,$li_partidas,$la_seguridad); 
   /*****************************************************************************************/ 	 

   /***********************************CARGOS************************************************/ 	 
	 $la_cargos["codcar"][1]="";
	 $la_cargos["monto"][1]="";
	 $la_cargos["formula"][1]="";
	 
	 for ($li_i=1;$li_i<$li_filascargos;$li_i++)
	 {
	   $la_cargos["codcar"][$li_i]=$_POST["txtcodcar".$li_i];
	   $la_cargos["monto"][$li_i]=$_POST["txtmoncar".$li_i];
	   $la_cargos["formula"][$li_i]=$_POST["formu".$li_i];
	 }
	 $io_valuacion->uf_update_dtcargos($ls_codval,$ls_codcon,$ls_basimpval,$la_cargos,$li_filascargos,$la_seguridad); 
	
  /*****************************************************************************************/ 	 	 
  
  /***********************************RETENCIONES*******************************************/ 	 	
	$la_retenciones["codret"][1]="";
	$la_retenciones["monret"][1]="";
	$la_retenciones["montotret"][1]="";
	
    for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
     {
	   $la_retenciones["codret"][$li_i]=$_POST["txtcodret".$li_i];
	   $la_retenciones["monret"][$li_i]=$_POST["txtmonret".$li_i];
	   $la_retenciones["montotret"][$li_i]=$_POST["txttotret".$li_i];
	 }
	 $io_valuacion->uf_update_retenciones($ls_codval,$ls_codcon,$la_retenciones,$li_filasretenciones,$la_seguridad);
  /*****************************************************************************************/ 	    
  }
  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
}
/***************************************************************************************************************************************************************************/
elseif($ls_operacion=="PROCESAR")
{
	$lb_valido=$io_valuacion->uf_validar_contabilizado($ls_hidcodasi);
	if($lb_valido)
	{
		$ld_montotval=$io_funcsob->uf_convertir_cadenanumero($ls_montotval);
		$ld_totreten=$io_funcsob->uf_convertir_cadenanumero($ls_totreten);
//		$ld_totreten=$ls_totreten;
		$ld_totreten=$io_funcsob->uf_convertir_numerocadena($ls_totreten);
		$ld_totreten=$io_funcsob->uf_convertir_cadenanumero($ls_totreten);
		$ld_subtot=$io_funcsob->uf_convertir_cadenanumero($ls_subtot);
		$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
		
		//echo $ld_montotval." - ".$ld_totreten." - ". $ld_subtot ." - ".$ld_basimpval;$ls_codcon
		
		$lb_valido=$io_valuacion->uf_procesar_recepcion_documentos($ls_numrecdoc,$ls_numref,$ls_numfactura,$ls_codtipdoc,$ls_obsval,$ls_fecha,$ld_montotval,$ld_totreten,$ld_subtot,$ls_codcon,$ld_basimpval,$ls_hidcodasi,$ls_codval,$la_seguridad);
																   
	}
	else
	{
		 $io_msg->message("El contrato debe estar contabilizado");
	}
}
/*******************************************BUSCAR DATOS DE CONTRATO*********************************************************************/
elseif($ls_operacion=="ue_datcontrato")
{
   $ls_codcon=$_POST["txtcodcon"];
   $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
   $ls_desobr=$la_contrato["desobr"][1];
   $ls_puncue=$la_contrato["puncueasi"][1];
   $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
   $ls_moncon=$la_contrato["monto"][1];
   $ls_imponible=$la_contrato["imponible"][1];
   $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
   $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
   

   $lb_validop=$io_valuacion->uf_select_partidasasignadas($ls_codcon,&$la_partidas,&$li_totalfilas);
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		////////////////// NUEVO ////////////////////
		$ls_ejecutado=$io_obra->uf_obtengodatos("SELECT SUM(basimpval) as suma FROM sob_valuacion WHERE codemp='$ls_empresa' AND codcon='$ls_codcon'
 ",'B');
		////////////// OBTIENE EL MONTO DE OTRAS VALUACIONES /////////////
		if($ls_ejecutado==false){$ls_ejecutado=0.00;} 
		//////////////////////////////////////////////////////////////////
		//print "Ejecutado: ".$ls_ejecutado;
		/////////////////////////////////////////////
		    $ls_codigo=$io_datastore->getValue("codpar",$li_i);
			$ls_nombre=$io_datastore->getValue("nompar",$li_i);
			$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
			///////////////////// AQUI EL IVA ///////////////////////////
			$ls_preuni=$io_datastore->getValue("prerefparasi",$li_i);
			$ls_preuni=$ls_totcon-$ls_imponible;
			/////////////////////////////////////////////////////////////
			$ls_preunimod=$io_datastore->getValue("preparasi",$li_i);
			//////////////// AQUI EL TOTAL DEL CONTRATO CON IVA //////////
			$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
			$ls_canttot=$ls_totcon;
			/////////////////////////////////////////////////////////////
			$ls_canpareje=$io_datastore->getValue("canasipareje",$li_i);
			$ls_codasi=$io_datastore->getValue("codasi",$li_i);
			$ls_codobr=$io_datastore->getValue("codobr",$li_i);
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	    //    $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_ejecutado)."' class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_ejecutado)."' class=sin-borde size=15 style= text-align:center readonly>";	
			if($ls_canttot==0)
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        //$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			}
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas."><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	//$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal1".$li_filaspartidas." type=hidden id=txttotal1".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	}

////////////////////// NUEVO: PERMITE CARGAR LOS IVA DE LA OBRA /////////////////////////
   /************************CARGANDO CARGOS**************************************************/ 
	//uf_select_partidasasignadas($ls_codcon,&$la_partidas,&$li_totalfilas);
	//print "contrato: ".$ls_codcon;
    $lb_validoca=$io_valuacion->uf_select_cargoasignacion($ls_codcon,$la_cargos,$li_totalfilas);
	if($lb_validoca)
	{
	$io_datastore->data=$la_cargos;
	$li_filascargos=$io_datastore->getRowCount("codcar");
	//print "cargos: ".$li_filascargos;
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codcar",$li_i);
		$ls_nombre=$io_datastore->getValue("dencar",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_formula=$io_datastore->getValue("formula",$li_i);
		$ls_codestprog=$io_datastore->getValue("codestprog",$li_i);
		$ls_spg_cuenta=$io_datastore->getValue("spg_cuenta",$li_i);

		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";

		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";

//		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";


		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	/*****************************************************************************************/

//////////////////////////////////////////////////////////////////////////////////////////


}
/***********************************************************************************************************************************************/

/*******************************************ANULAR UNA VALUACION********************************************************************************/
elseif($ls_operacion=="ue_anular")
{   
   $io_valuacion->uf_select_estado($ls_codval,&$ls_estasi);
   if(($ls_estasi==1)||($ls_estasi==6))
   { 
      for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
       {
	    if(!empty($_POST["flagpar".$li_i]))
	     {
	    	$ls_codparG=$_POST["txtcodpar".$li_i];
	    	$ls_canejeG=$_POST["canpareje".$li_i];
	        $ls_canparG=$_POST["txtcantpar".$li_i];
	        $io_valuacion->uf_update_Actcantidaejecutada($ls_codasi,$ls_codparG,$ls_canparG,$ls_canejeG,$la_seguridad);
	     
	     }
	 }
     $io_valuacion->uf_update_estado($ls_codval,3,$la_seguridad);
	 $io_msg->message("Esta Valuacion fue Anulada!!");
   }
   else
   {
    $io_msg->message("Esta Valuacion no puede ser Anulada!!");
   }
  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
}
elseif($ls_operacion=="ue_eliminar")
{

  $ld_fecha=$io_function->uf_convertirdatetobd($ls_fecha);
  $ld_fecinival=$io_function->uf_convertirdatetobd($ls_fecinival);
  $ld_fecfinval=$io_function->uf_convertirdatetobd($ls_fecfinval);
  $lb_valido=$io_valuacion->uf_eliminar_valuacion($ls_codval,$ls_codcon,$ld_fecha,$ld_fecinival,$ld_fecfinval,$ls_obsval,$ls_amoactual,$ls_amoobs,$ls_amoant,$ls_amotot,$ls_amores,$ls_basimpval,$ls_montotval,$ls_subtotpar,$ls_totreten,$ls_subtot,$la_seguridad,$ls_numrecdoc);

  if($lb_valido)
  {
    /************************************PARTIDAS*******************************************/
	$li_partidas=1;
    $la_partidas["codpar"][1]="";
	$la_partidas["canteje"][1]="";
	$la_partidas["cant"][1]="";
	$la_partidas["preref"][1]="";
	$la_partidas["preval"][1]="";
	$la_partidas["codasi"][1]="";
	$la_partidas["codobr"][1]="";
	
    for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
     {
	   if(!empty($_POST["flagpar".$li_i]))
	   {
		//////////// REVISAR 2015 ///////
	   	$la_partidas["codpar"][$li_partidas]=$_POST["txtcodpar".$li_i];
		$la_partidas["canteje"][$li_partidas]=$_POST["canpareje".$li_i];
	    $la_partidas["cant"][$li_partidas]=$_POST["txtcantpar".$li_i];
		$la_partidas["preref"][$li_partidas]=$_POST["txtpreuni".$li_i];
	    $la_partidas["preval"][$li_partidas]=$_POST["txtpreunimod".$li_i];
		$la_partidas["codasi"][$li_partidas]=$_POST["codasi".$li_i];
	    $la_partidas["codobr"][$li_partidas]=$_POST["codobr".$li_i];
		$li_partidas++;
	   }
	 }
	$io_valuacion->uf_delete_dtpartidas($ls_codval,$ls_codcon,$la_partidas,$li_partidas,$la_seguridad); 
   /*****************************************************************************************/ 	 

   /***********************************CARGOS************************************************/ 	 
	 $la_cargos["codcar"][1]="";
	 $la_cargos["monto"][1]="";
	 $la_cargos["formula"][1]="";
	 
	 for ($li_i=1;$li_i<$li_filascargos;$li_i++)
	 {
	   $la_cargos["codcar"][$li_i]=$_POST["txtcodcar".$li_i];
	   $la_cargos["monto"][$li_i]=$_POST["txtmoncar".$li_i];
	   $la_cargos["formula"][$li_i]=$_POST["formu".$li_i];
	 }
	 $io_valuacion->uf_delete_dtcargos($ls_codval,$ls_codcon,$ls_basimpval,$la_cargos,$li_filascargos,$la_seguridad); 
	
  /*****************************************************************************************/ 	 	 
  
  /***********************************RETENCIONES*******************************************/ 	 	
	$la_retenciones["codret"][1]="";
	$la_retenciones["monret"][1]="";
	$la_retenciones["montotret"][1]="";
	
    for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
     {
	   $la_retenciones["codret"][$li_i]=$_POST["txtcodret".$li_i];
	   $la_retenciones["monret"][$li_i]=$_POST["txtmonret".$li_i];
	   $la_retenciones["montotret"][$li_i]=$_POST["txttotret".$li_i];
	 }
	 $io_valuacion->uf_delete_retenciones($ls_codval,$ls_codcon,$la_retenciones,$li_filasretenciones,$la_seguridad);
  /*****************************************************************************************/ 	    
  }
  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
}
/***************************************************************************************************************************************************************************/

/*******************************************CARGAR DATOS DE LA VALUACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarvaluacion")
{   

   $ls_codcon=$_POST["txtcodcon"];
   $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
   $ls_desobr=$la_contrato["desobr"][1];
   $ls_puncue=$la_contrato["puncueasi"][1];
   $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
   $ls_moncon=$la_contrato["monto"][1];
   $ls_imponible=$la_contrato["imponible"][1];
   $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
   $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;


   $ls_codval=$_POST["txtcodval"];
   $ls_numrecdoc=$_POST["txtnumrecdoc"];
   $ls_hidcodasi =$_POST["hidcodasi"];
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
//echo $ls_totant; // total anticipo
   /************************CARGANDO PARTIDAS*************************************************/ 
	$lb_validop=$io_valuacion->uf_select_allpartidas($ls_codval,$ls_hidcodasi,&$la_partidas,&$li_totalfilas);
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		////////////////// NUEVO ////////////////////
		$ls_ejecutado=$io_obra->uf_obtengodatos("SELECT SUM(basimpval) as suma FROM sob_valuacion WHERE codemp='$ls_empresa' AND codcon='$ls_codcon'
 ",'B');
		$ls_valuado=$io_obra->uf_obtengodatos("SELECT basimpval as suma FROM sob_valuacion WHERE codemp='$ls_empresa' AND codcon='$ls_codcon' AND codval='$ls_codval'",'B');
		////////////// OBTIENE EL MONTO DE OTRAS VALUACIONES /////////////
		if($ls_ejecutado==false){$ls_ejecutado=0.00;} 
		//////////////////////////////////////////////////////////////////
		//print "Ejecutado: ".$ls_ejecutado;
		/////////////////////////////////////////////
		    $ls_codigo=$io_datastore->getValue("codpar",$li_i);
			$ls_nombre=$io_datastore->getValue("nompar",$li_i);
			$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
			///////////////////// AQUI EL IVA ///////////////////////////
			$ls_preuni=$io_datastore->getValue("prerefparasi",$li_i);
			$ls_preuni=$ls_totcon-$ls_imponible;
			/////////////////////////////////////////////////////////////
			$ls_preunimod=$io_datastore->getValue("preparasi",$li_i);
			//////////////// AQUI EL TOTAL DEL CONTRATO CON IVA //////////
			$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
			$ls_canttot=$ls_totcon;
			/////////////////////////////////////////////////////////////
			$ls_canpareje=$io_datastore->getValue("canasipareje",$li_i);
			$ls_codasi=$io_datastore->getValue("codasi",$li_i);
			$ls_codobr=$io_datastore->getValue("codobr",$li_i);
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	    //    $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=15 style= text-align:center readonly>";
	    	$la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_valuado)."' class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_ejecutado)."' class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_ejecutado)."' class=sin-borde size=15 style= text-align:center readonly>";	
			if($ls_canttot==0)
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        //$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][6]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
	        $la_objectpartidas[$li_i][9]="<input name=txttotal1".$li_i." type=hidden id=txttotal1".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			}
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas."><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	//$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal1".$li_filaspartidas." type=hidden id=txttotal1".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	}
   /*****************************************************************************************/

   /************************CARGANDO CARGOS**************************************************/ 
    $lb_validoca=$io_valuacion->uf_select_cargos($ls_codval,$ls_codcon,$la_cargos,$li_totalfilas);
	if($lb_validoca)
	{
	$io_datastore->data=$la_cargos;
	$li_filascargos=$io_datastore->getRowCount("codcar");
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codcar",$li_i);
		$ls_nombre=$io_datastore->getValue("dencar",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_formula=$io_datastore->getValue("formula",$li_i);
		$ls_codestprog=$io_datastore->getValue("codestprog",$li_i);
		$ls_spg_cuenta=$io_datastore->getValue("spg_cuenta",$li_i);

		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";

		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";

//		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";


		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	/*****************************************************************************************/
   
   /************************CARGANDO RETENCIONES**************************************************/ 
	$lb_validor=$io_valuacion->uf_select_retenciones($ls_codval,$ls_codcon,$la_retenciones,$li_totalfilas);

	if($lb_validor)
	{
	 $io_datastore->data=$la_retenciones;
	 $li_filasretenciones=$io_datastore->getRowCount("codded");
	 for($li_i=1;$li_i<=$li_filasretenciones;$li_i++)
		{
			$ls_codigo=$io_datastore->getValue("codded",$li_i);
			$ls_descripcion=$io_datastore->getValue("dended",$li_i);
			$ls_cuenta=$io_datastore->getValue("sc_cuenta",$li_i);
			$ls_deduccion=$io_datastore->getValue("monded",$li_i);
			$ls_monret=$io_datastore->getValue("monret",$li_i);
			$ls_totret=$io_datastore->getValue("montotret",$li_i);
			$ls_formula=$io_datastore->getValue("formula",$li_i);

		$ls_porded = $io_valuacion->uf_select_porcretencion($ls_codigo);
		$ls_tipded = $io_valuacion->uf_select_tiporetencion($ls_codigo);

			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:left size=20 readonly>";
			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_deduccion)."' style= text-align:left size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_monret)."' style= text-align:left size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this);>";


			$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_totret)."' style= text-align:left size=15 readonly>";
		    $la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		$li_filasretenciones=$li_filasretenciones+1;	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
       }
	/*****************************************************************************************/
}
/***************************************************************************************************************************************************************************/

/*******************************************CALCULAR RETENCION DE LA VALUACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_calcretencion")
{   
  $ls_subtot=$_POST["txtsubtot"];
  $ld_subtot=$io_funcsob->uf_convertir_cadenanumero($ls_subtot);
  $ld_acum=0;
  
  for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];
		$ls_monret=$_POST["txtmonret".$li_i];
		$ls_formula=$_POST["formula".$li_i];

		$ls_porded=$_POST["porded".$li_i];

		$ld_monret=$io_funcsob->uf_convertir_cadenanumero($ls_monret);

		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_monret,$lb_valido);

////////////////////
		$ls_porded = $io_valuacion->uf_select_porcretencion($ls_codigo);
		$ls_tipded = $io_valuacion->uf_select_tiporetencion($ls_codigo);
echo $ls_monret; // flag1
		if ($ls_tipded == 1){
			$ls_monret = $_POST["totalcargos"];		
		}
		else
		{
			if ($ls_porded > 0.10)
			{
				$ls_monret = $_POST["txttotalval"];
				//$ls_monret = $io_funcsob->uf_convertir_cadenanumero($ls_monret);
			}
			else
			{
				$ls_monret = $_POST["txtbasimpval"];
			}
		}
		$ld_result = $ls_monret * $ls_porded;		
////////////////////

		$ld_acum = $ld_acum + $ld_result;

		$ls_totret=$io_funcsob->uf_convertir_numerocadena($ld_result);

		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this);>";
		$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
 	}	 
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
    $la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	if($ld_acum<$ld_subtot)
	{
	 $ls_totreten=$io_funcsob->uf_convertir_numerocadena($ld_acum);
	 $ld_montotval=$ld_subtot-$ld_acum;
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_montotval);
	}
    else
	{
	 $io_msg->message("El total en retenciones supera el subtotal acumulado!!");
	}  
}


?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Control de Obras</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20">--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
  <?php
   /*********************************         SEGURIDAD               *************************************/
     if (($ls_permisos)||($ls_logusr=="PSEGIS"))
      {
	     print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	     print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	     print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	     print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
       	 print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	     print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	     print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	     print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
      }
      else
      {
	 	print("<script language=JavaScript>");
	    print(" location.href='tepuywindow_blank.php'");
	    print("</script>");
      }
   /************************************         SEGURIDAD          ***********************************************/
?>	
  <!-- Estos son los hidden de Datos del Contrato-->
  <input name="hiddesobr" type="hidden" id="hiddesobr" value="<? print $ls_desobr ?>">
  <input name="hidpuncue" type="hidden" id="hidpuncue" value="<? print $ls_puncue ?>">
  <input name="hidestcon" type="hidden" id="hidestcon" value="<? print $ls_estcon ?>">
  <input name="hidmoncon" type="hidden" id="hidmoncon" value="<? print $ls_moncon ?>">
  <input name="hidfeccon" type="hidden" id="hidfeccon" value="<? print $ls_feccon ?>">
  <input name="hidtotcon" type="hidden" id="hidtotcon" value="<? print $ls_totcon ?>">
  <input name="hidtotant" type="hidden" id="hidtotant" value="<? print $ls_totant ?>">  
  <input name="hidcodasi" type="hidden" id="hidcodasi" value="<? print $ls_hidcodasi ?>">  
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <th colspan="6" class="titulo-celdanew" scope="col">Datos del Contrato</th>
      </tr>
      <tr class="formato-blanco">
        <th colspan="6" scope="col">&nbsp;</th>
      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Contrato</div></td>
        <td colspan="4"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<? print $ls_codcon ?>" size="15" maxlength="12" readonly="true">
        <a href="javascript:ue_catcontrato();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>         </td>
      </tr>
      <tr class="formato-blanco">
      <td height="13" colspan="7" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato&nbsp;&nbsp;&nbsp;&nbsp; </a>
				   </div></td>
    </tr>
	   <?
	    if($ls_opemostrar=="MOSTRAR")
		 { 
		   $ls_codcon=$_POST["txtcodcon"];
           $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
           $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
           $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
           $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
           $ls_desobr=$la_contrato["desobr"][1];
           $ls_puncue=$la_contrato["puncueasi"][1];
           $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
           $ls_moncon=$la_contrato["monto"][1];
           $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
           $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
	    ?>
      <tr class="formato-blanco">
        <td height="13" colspan="7" align="center" valign="top" class="sin-borde"><table width="544" height="137" border="0" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td>&nbsp;</td>
            <td width="118">&nbsp;</td>
            <td width="62"><div align="right">Estatus</div></td>
            <td width="111"><span class="style6"><input name="txtestobr" type="text" class="celdas-grises" id="txtestobr"  style="text-align:left" value="<? print $ls_estcon ?>" size="20" maxlength="20" readonly="true">
            </span></td></tr>
          <tr>
            <td width="231"><div align="right"><span class="style6">Descripcion de la Obra</span></div></td>
            <td colspan="3"><span class="style6">
            <input name="txtdesobr" type="text" id="txtdesobr"  style="text-align:left" value="<? print $ls_desobr ?>" size="55" maxlength="254"  readonly="true">
</span><span class="style6"></span></td>
          </tr>
          <tr>
            <td><div align="right">Fecha Contrato </div></td>
            <td><span class="style6">
              <input name="txtfeccon" type="text" id="txtfeccon"  style="text-align:left" value="<? print $ls_feccon ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <!--<td><div align="right"><span class="style6">Pto. Cuenta </span></div></td>
            <td><span class="style6">
              <input name="txtpuncueasi" type="text" id="txtpuncuenasi"  style="text-align:left" value="<? print $ls_puncue  ?>" size="20" maxlength="20" readonly="true">
            </span></td>-->
          </tr>
          <tr>
            <td><div align="right"><span class="style6">Monto Contrato </span></div></td>
            <td><span class="style6">
              <input name="txtmoncon" type="text" id="txtmoncon"  style="text-align:left" value="<? print $io_funcsob->uf_convertir_numerocadena($ls_moncon) ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><div align="right">Anticipo</div></td>
            <td><span class="style6">
            <input name="txtmonant" type="text" id="txtmonant"  style="text-align:left" value="<? print $io_funcsob->uf_convertir_numerocadena($ls_totant) ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
            <td><div align="right">Contrato + Aumentos - Disminuciones </div></td>
            <td><span class="style6">
            <input name="txtmontotcon" type="text" id="txtmontotcon"  style="text-align:left" value="<? print $io_funcsob->uf_convertir_numerocadena($ls_totcon) ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>&nbsp;</td>
            <td><span class="style6">
            </span></td>
          </tr>
        </table></td>
    </tr>
	   <?
		}
	   ?>
      <tr class="formato-blanco">
        <td height="13" colspan="7" align="center" valign="top" class="sin-borde">&nbsp;</td>
      </tr>
	  <tr class="titulo-celdanew">
        <th height="14" colspan="6" class="titulo-celdanew" scope="col">Valuaci&oacute;n</th>
    </tr>
      <tr class="formato-blanco">
        <td><input name="operacion" type="hidden" id="operacion">
		<input name="opemostrar" type="hidden" id="opemostrar" value="<? print $ls_opemostrar ?>">
		<input name="opemostrarA" type="hidden" id="opemostrarA" value="<? print $ls_opemostrarA ?>">        </td>
        <td colspan="5"><?Php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
					$_SESSION["campoclave"]=$ls_codval;
					$_SESSION["contrato"]=$ls_codcon;
			?>
          <a href="javascript:ue_grabarfoto()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Incluir Fotos</a>&nbsp;&nbsp;&nbsp;
          <?Php
			}
			}
			?>
          <?Php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
			?>
          <a href="javascript:ue_verfotos()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Ver Fotos</a>
          <?Php
			}
			}
			?></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right">Estado</div></td>
        <td width="172"><span class="style6">
          <input name="txtestval" type="text" class="celdas-grises" id="txtestval"  style="text-align:left" value="<? print $ls_estadoval ?>" size="10" maxlength="10" readonly="true">
        </span></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td width="22"><div align="right">C&oacute;digo</div></td>
        <td width="83"><input name="txtcodval" type="text" id="txtcodval" style="text-align:center " value="<? print $ls_codval ?>" size="3" maxlength="3" readonly="true"> 
	<td width="158"><div align="right">N&uacute;mero de Ficha</div></td>
	<td width="155"><input name="txtnumrecdoc" type="text" id="txtnumrecdoc" style="text-align:center " value="<? print $ls_numrecdoc ?>" size="15" maxlength="15" read="true">          
        <td>&nbsp;</td>
       <tr class="formato-blanco"> 
               <td height="22">&nbsp;</td>
	<td width="158"><div align="right">N&uacute;mero de Factura</div></td>
	<td width="155"><input name="txtnumref" type="text" id="txtnumref" style="text-align:center " value="<? print $ls_numref ?>" size="15" maxlength="15" read="true">          

         <td width="158"><div align="right">Nro. Factura Control </div></td>
	<td width="155"><input name="txtnumfactura" type="text" id="txtfactura" style="text-align:center " value="<? print $ls_numfactura ?>" size="15" maxlength="15" read="true">  
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="4"><input name="txtfecha" type="text" id="txtfecha" value="<? print $ls_fecha ?>" size="10" maxlength="10" readonly="true" style="text-align:center"></td>
      </tr>
	  <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha Inicio </div></td>
        <td width="83"><input name="txtfecinival" type="text" id="txtfecinival"  style="text-align:left" value="<? print $ls_fecinival ?>" size="11" maxlength="11"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
        <td width="158"><div align="right">Fecha Fin </div></td>
        <td width="155"><input name="txtfecfinval" type="text" id="txtfecfinval"  style="text-align:left" value="<? print $ls_fecfinval ?>" size="11" maxlength="11"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Concepto de Pago de Valuacion</div></td>
        <td colspan="4"><textarea name="txtobsval" cols="80" rows="2" id="txtobsval" onKeyPress="return(validaCajas(this,'x',event))" onKeyDown="textCounter(this,254)" ><? print $ls_obsval ?></textarea></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="formato-blanco">
            <td width="15" height="13">&nbsp;</td>
            <td width="593"><div align="left"></div></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td colspan="2"><?php $io_grid->makegrid($li_filaspartidas,$la_columpartidas,$la_objectpartidas,$li_anchopartidas,$ls_titulopartidas,$ls_nametable);?>            </td>
          </tr>
          <input name="filaspartidas" type="hidden" id="filaspartidas" value="<? print $li_filaspartidas;?>">
          <tr class="formato-blanco">
            <td height="18" colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Monto Parcial de la Valuacion</div></td>
        <td><input name="txtsubtotpar" type="text" id="txtsubtotpar" size="20" maxlength="20" readonly="true" value="<? print $ls_subtotpar?>" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="2"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"><span class="Estilo1">Amortizacion Anticipo</span></a></td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">
		<table width="633" height="95" border="0" align="center" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td width="134" height="18"><div align="right">Porcentaje a Amortizar</div>            </td>
            <td width="75"><span class="style6">	
            <input name="txtporamoactual" type="text" id="txtporamoactual"  style="text-align: right" value="<? print $ls_poramoactual ?>" size="5" maxlength="5" onKeyPress="return(validaCajas(this,'d',event,21)) "  onBlur="javascript:ue_getformat(this)"  onKeyUp="javascript:uf_calcularamo()">
%            </span></td>
            <td width="24"> <div align="right">Total</div></td>
            <td width="105"><span class="style6">
              <input name="txtamoactual" type="text" id="txtamoactual"  style="text-align: right" value="<? print $ls_amoactual ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td width="145"><div align="right">Monto Anticipo </div></td>
            <td width="120"><span class="style6">
              <input name="txttotant" type="text" id="txttotant"  style="text-align: right" value="<? print $io_funcsob->uf_convertir_numerocadena($ls_totant) ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
              <td height="18"><div align="right"><span class="style6">Observacion</span></div></td>
            <td colspan="3" rowspan="3" valign="top"><span class="style6">
              <textarea name="txtamoobs" cols="25" rows="2" id="txtamoobs" style="text-align:left" onKeyPress="return(validaCajas(this,'x',event))" onKeyDown="textCounter(this,254)" ><? print $ls_amoobs ?></textarea>
            </span><span class="style6">            </span></td>
              <td><div align="right"><span class="style6">Amortizaci&oacute;n Anterior </span></div></td>
              <td><span class="style6">
                <input name="txtamoant" type="text" id="txtamoant"  style="text-align: right" value="<? print $ls_amoant ?>" size="20" maxlength="20" readonly="true">
              </span></td>
          </tr>
          <tr>
            <td height="18"><div align="right"></div></td>
            <td><div align="right">Total Amortizado </div></td>
            <td><span class="style6">
            <input name="txtamotot" type="text" id="txtamotot"  style="text-align: right" value="<? print $ls_amotot ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
            <td height="21"><div align="right"></div></td>
            <td><div align="right">Resta por Amortizar</div></td>
            <td><span class="style6">
              <input name="txtamores" type="text" id="txtamores"  style="text-align: right" value="<? print $ls_amores ?>" size="20" maxlength="20" readonly="true"> 
            </span></td>
          </tr>
        </table>		</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="formato-blanco">
            <td width="14" height="11">&nbsp;</td>
            <td width="593"><a href="javascript:ue_catcargos();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catcargos();">Agregar Detalle </a></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td height="11" colspan="2">
              <?php $io_grid->makegrid($li_filascargos,$la_columcargos,$la_objectcargos,$li_anchocargos,$ls_titulocargos,$ls_nametable);?>            </td>
            <input name="filascargos" type="hidden" id="filascargos" value="<? print $li_filascargos;?>">
            <input name="hidremovercargo" type="hidden" id="hidremovercargo" value="<? print $li_removercargo;?>">
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Base Imponible</div></td>
        <td><input name="txtbasimpval" type="text" id="txtbasimpval" size="20" maxlength="20" value="<? print $ls_basimpval?>" onKeyPress="return(currencyFormat(this,'.',',',event))" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Sub-Total</div></td>
        <td><input name="txtsubtot" type="text" id="txtsubtot" size="20" maxlength="20" readonly="true" value="<? print $ls_subtot;?>" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
          <tr class="formato-blanco">
            <td width="15" height="13">&nbsp;</td>
            <td width="593"><div align="left"><a href="javascript:ue_catretenciones();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catretenciones();">Agregar Detalle</a></div></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td colspan="2"><?php $io_grid->makegrid($li_filasretenciones,$la_columretenciones,$la_objectretenciones,$li_anchoretenciones,$ls_tituloretenciones,$ls_nametable);?>            </td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total Retenciones </div></td>

<!--   evito volver a convertir numero a cadena que seria como dividir entre 100
     <td><input name="txttotreten" type="text" id="txttotreten" value="<? print $io_funcsob->uf_convertir_numerocadena($ls_totreten); ?>" style="text-align:right "></td> -->
	<td><input name="txttotreten" type="text" id="txttotreten" value="<? $io_funcsob->uf_convertir_cadenanumero($ls_totreten);print $io_funcsob->uf_convertir_numerocadena($ls_totreten); ?>" style="text-align:right "></td> 
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total Valuacion</div></td>
        <td><input name="txtmontotval" type="text" id="txtmontotval" value="<? print $ls_montotval?>" readonly="true" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6" class="titulo-celdanew">Recepci&oacute;n de Documentos </td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Tipo de Documento</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodtipdoc" type="text" id="txtcodtipdoc" value="<?php print $ls_codtipdoc; ?>" size="10" readonly>
          <a href="javascript: ue_buscartipodocumento();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar tipo de Documento" width="15" height="15" border="0"></a>
          <input name="txtdentipdoc" type="text" class="sin-borde" id="txtdentipdoc" value="<?php print $ls_dentipdoc; ?>" size="40" readonly>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="btngenr" type="button" class="boton" id="btngenr" value="Generar Recepci&oacute;n de Documentos" onClick="javascript: ue_generar_recepcion();"></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"></td>
      </tr>
    </table>
    <div align="center"></div>
	<input name="hidstatus" type="hidden" id="hidstatus">
	<input name="hidfilasretenciones" type="hidden" id="hidfilasretenciones" value="<? print $li_filasretenciones;?>">
	<input name="hidremoverretenciones" type="hidden" id="hidremoverretenciones" value="<? print $li_removerretenciones;?>">
	
	<!-- HIDDEN VALORES A GUARDAR EN LA BD-->
	<input name="hidamototbd" type="hidden" id="hidamototbd" value="<? print $ls_hidamototbd;?>">
	<input name="hidamoresbd" type="hidden" id="hidamoresbd" value="<? print $ls_hidamoresbd;?>">
  </form>
</body>
<script language="javascript">

/*******************************************CATALOGOS********************************************************************************************************/
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";	
	var opener="valuacion"		
	pagina="tepuy_cat_contrato.php?opener="+opener;
	popupWin(pagina,"catalogo",850,500);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catretenciones()
{
	f=document.form1;
	if(f.txtcodcon.value!="")
	{
		f.operacion.value="";
		ls_codcon=f.txtcodcon.value;			
		pagina="tepuy_cat_retencontrato.php?codcon="+ls_codcon;
		popupWin(pagina,"catalogo",600,250);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
	}
	else
	{
		alert("Debe seleccionar un Contrato!!");
	}
}

function ue_catcargos()
{
	f=document.form1;
	if((f.txtbasimpval.value=="0,00")||(f.txtbasimpval.value==""))
	 {
	  alert("Aun no ha indicado la base imponible a la cual se le aplicaran los cargos!!")
	 }
	 else
	 {
	   f.operacion.value="";
	   pagina="tepuy_cat_cargos.php";
	   popupWin(pagina,"catalogo",650,400);
	   //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes"); 
	 }
}

function ue_buscar()
{
 f=document.form1;
 pagina="tepuy_cat_valuacion.php?estado=";
 popupWin(pagina,"catalogo",750,450);
 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
} 
function ue_buscartipodocumento()
{
	window.open("tepuy_sob_cat_tipodocumentos.php?tipo=valuacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_grabarfoto()
{
	var opener="valuacion";
	pagina="tepuy_sob_d_grabarfotos.php?opener="+opener;
	popupWin(pagina,"catalogo",520,220);
}

function ue_verfotos()
{
	var opener="valuacion";
	var codigoval=document.form1.txtcodval.value;
	var codigocon=document.form1.txtcodcon.value;
	pagina="tepuy_sob_d_verfotos.php?opener="+opener+"&campocodigo="+codigoval+"&contrato="+codigocon;
	popupWin(pagina,"catalogo2",800,800);

}
/*************************************************************************************************************************************************/
function ue_generar_recepcion()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		conval=f.txtcodval.value;
		numrecdoc=f.txtnumrecdoc.value;
		if((conval!="")&&(numrecdoc!=""))
		{
			f.operacion.value="PROCESAR";
			f.action="tepuy_sob_d_valuacion.php";
			f.submit();
		}
		else
	   	{
		alert("Debe establecer Numero de Ficha del Documento");
		}
		
	}
	else
   	{alert("No tiene permiso para realizar esta operacion");}
}

/*******************************************CARGAR Y REMOVER DATOS********************************************************************************************************/
function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins,ls_fecfincon,ls_precon,ls_baseimp)
{
		
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.operacion.value="ue_datcontrato";
	f.submit();
}

function ue_cargarvaluacion(ls_codval,ls_codasi,ls_codcon,ls_numrecdoc,ls_numref,ls_numfactura,ls_fecha,ls_fecinival,ls_fecfinval,ls_obsval,ls_amoval,ls_obsamoval,ls_amoantval,ls_amototval,ls_amoresval,ls_basimpval,ls_montotval,ls_subtotpar,ls_totreten,ls_subtot,ls_nomestval)
{
    f=document.form1;
	f.txtnumrecdoc.value=ls_numrecdoc;
	f.txtnumref.value=ls_numref;
	f.txtnumfactura.value=ls_numfactura;
	f.txtcodval.value=ls_codval;
	f.txtcodcon.value=ls_codcon;
	f.txtfecha.value=ls_fecha;
	f.txtfecinival.value=ls_fecinival;
	f.txtfecfinval.value=ls_fecfinval;
	f.txtobsval.value=ls_obsval;
	ls_totant= parseInt(ls_amototval)+parseInt(ls_amoresval);

	f.txtamoactual.value=uf_convertir(ls_amoval);
	//f.txtporamoactual.value=ls_totant;
	f.txtporamoactual.value=uf_convertir(ls_amoval * 100 / ls_totant);

	f.txtamoobs.value=ls_obsamoval;
	f.txtamoant.value=uf_convertir(ls_amoantval);
	
	f.txtamotot.value=uf_convertir(ls_amototval);

	f.txtamores.value=uf_convertir(ls_amoresval);
	f.txtsubtotpar.value=uf_convertir(ls_subtotpar);
	f.txtbasimpval.value=uf_convertir(ls_basimpval);
	f.txtmontotval.value=uf_convertir(ls_montotval);
	//f.txttotreten.value=uf_convertir(ls_totreten);
	f.txttotreten.value=ls_totreten;
	f.txtsubtot.value=uf_convertir(ls_subtot);
	f.txtestval.value=ls_nomestval;	
	f.hidcodasi.value=ls_codasi;
	f.hidstatus.value="C";
	f.operacion.value="ue_cargarvaluacion";
	f.action="tepuy_sob_d_valuacion.php";
	f.submit();
}

function ue_cargarretenciones(codigo,descripcion,cuenta,deducible,monto,formula,cubretotal)
{
	//alert(cubretotal);
	f=document.form1;
	f.operacion.value="ue_cargarretenciones";
	lb_existe=false;
	
	for(li_i=1;li_i<=f.hidfilasretenciones.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodret"+li_i+".value");
		if(ls_codigo==codigo)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{	//alert(f.hidfilasretenciones.value);
		eval("f.txtcodret"+f.hidfilasretenciones.value+".value='"+codigo+"'");
		eval("f.txtdesret"+f.hidfilasretenciones.value+".value='"+descripcion+"'");
		eval("f.txtcueret"+f.hidfilasretenciones.value+".value='"+cuenta+"'");
		eval("f.txtdedret"+f.hidfilasretenciones.value+".value='"+deducible+"'");
		eval("f.txtmonret"+f.hidfilasretenciones.value+".value='"+monto+"'");
		eval("f.formula"+f.hidfilasretenciones.value+".value='"+formula+"'");
		eval("f.txtcubretotal"+f.hidfilasretenciones.value+".value='"+cubretotal+"'");
		f.submit();
	}
}

function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.hidremoverretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="tepuy_sob_d_valuacion.php";
	f.submit();
}

function ue_cargarcargo(cod,nom,formula)
{
	f=document.form1;
	f.operacion.value="ue_cargarcargo";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filascargos.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodcar"+li_i+".value");
		if(ls_codigo==cod)
		{
			alert("El Cargo ya ha sido cargado!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodcar"+f.filascargos.value+".value='"+cod+"'");
		eval("f.txtnomcar"+f.filascargos.value+".value='"+nom+"'");
		eval("f.formu"+f.filascargos.value+".value='"+formula+"'");
    	f.submit();
	}
}

function ue_removercargo(li_fila)
{
	f=document.form1;
	f.hidremovercargo.value=li_fila;
	f.operacion.value="ue_removercargo"
	f.action="tepuy_sob_d_valuacion.php";
	f.submit();
}

/*************************************************************************************************************************************************/

/*********************************GENERAR NUEVO***********************************************************************************************************/
function ue_nuevo()
 {
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
   {		 
    if(f.txtcodcon.value=="")
     {
	   alert("Debe inidicar a que contrato le hara la valuacion!!");
   	 }
     else
	 {
	   f.operacion.value="ue_nuevo";
	   f.txtfecfinval.value="";
	   f.txtfecinival.value="";
	   f.txtobsval.value="";
	   f.action="tepuy_sob_d_valuacion.php";
	   f.submit();
	 }
   }
   else
   {
     alert("No tiene permiso para realizar esta operacion");
   } 
 }
/*************************************************************************************************************************************************/


/*************************************************************************************************************************************************/
function ue_guardar()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  li_cambiar=f.cambiar.value;
  lb_status=f.hidstatus.value;
  
  if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {	
	if (ue_valida_null(f.txtnumrecdoc,"Nro. de Ficha")==false)
	 {
	   f.txtnumrecdoc.focus();
	 }
	else
	 {
	if (ue_valida_null(f.txtcodcon,"Codigo Contrato")==false)
	 {
	   f.txtcodcon.focus();
	 }
	 else
	 {
	  if (ue_valida_null(f.txtcodval,"Codigo Valuacion")==false)
	   {
	     f.txtcodval.focus();
	   }
	   else
	   {
	    if (ue_valida_null(f.txtfecinival,"Fecha Inicio")==false)
	     {
	       f.txtfecinival.focus();
	     }
	     else
	     {
	      if (ue_valida_null(f.txtfecfinval,"Fecha Fin")==false)
	       {
	         f.txtfecfinval.focus();
	       }
	       else
	       {
	        if ((f.txtsubtotpar.value=="")||(f.txtsubtotpar.value=="0,00"))
	         {
	           alert("Revise el monto de la Valuación...!!");
	         }
	         else
	         {
	         	    if(ue_comparar_intervalo("txtfecinival","txtfecfinval","La fecha de inicio de la Valuación debe ser menor de la de finalización!!!"))
					{
						f.action="tepuy_sob_d_valuacion.php";
						f.operacion.value="ue_guardar";
						f.submit();
					}
				 }
	        }
	       }
		 } 
	   }
     } }
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	} 
 		  	
}	
/*************************************************************************************************************************************************/


/******************************************VALIDACIONES****************************************************************************************/
function uf_validacaracter(cadena, obj)
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 

   if (cadena == "%e")//toma el @, el punto y caracteres. Para Email
   if ((event.keyCode > 63 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode ==46)||(event.keyCode ==95)||(event.keyCode > 47 && event.keyCode < 58))  
   opc = true;    

   if (cadena == "%f")//Toma solo numeros
   { 
     if (event.keyCode > 47 && event.keyCode < 58) 
     opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
     if (event.keyCode == 46) 
     opc = true; 
   } 
   
   if (cadena == "%s") // toma numero y letras
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)||(event.keyCode ==47)||(event.keyCode ==35)||(event.keyCode ==45)) 
   opc = true; 
   
   if (cadena == "%c") // toma numero, punto y guion. Para telefonos
   if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode > 44 && event.keyCode < 47))
   opc = true; 
   
   if(opc == false) 
   event.returnValue = false;
 }

function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}

function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
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
	if (fld.id != "txtbasimpval" && fld.id != "txtporamoactual")
	  {
	   if (fld.id == "txtporamoactual")
	   { 
         uf_calcularamo();
 	   }
	   else
	   {
	    txt=fld.id.charAt(3);
		if(txt!="m")
		{
		  ue_subtotal();
		}
	   }	
	  }	
    return false; 
   } 
/*************************************************************************************************************************************************/

/************************************************************************************************************************************************/
 function ue_calcretencion(c)
 {
	f=document.form1;
	ld_subtot=parseFloat(uf_convertir_monto(f.txtsubtot.value));
	ld_monret=parseFloat(uf_convertir_monto(c.value));
	if(ld_monret<ld_subtot)
	{
	 f.action="tepuy_sob_d_valuacion.php";
	 f.operacion.value="ue_calcretencion";
	 f.submit();
	}
	else
	{
	 alert("El monto objeto de retencion debe ser menor al subtotal acumulado");
	 c.value="0,00";
	}
 }	
 function uf_calcularamo() 
 { 
   f=document.form1;
   if(f.txtporamoactual.value=="")
   {
     alert("Por favor indique el procentaje de la Amortización!!");
   }
   else
   {
     ld_poramo=parseFloat(uf_convertir_monto(f.txtporamoactual.value));
	 if((f.txtsubtotpar.value=="")||(f.txtsubtotpar.value=="0,00"))
      {
        alert("Debe establecer el Monto a Valuar!!")
		f.txtporamoactual.value="0,00";
		f.txtamoactual.value="0,00";
	  }
      else
      {
        ld_totpar=parseFloat(uf_convertir_monto(f.txtsubtotpar.value));
	ld_totant=parseFloat(f.hidtotant.value);
		// ASI ESTABA
		//ld_amoactual=ld_totpar*(ld_poramo/100);
		ld_amoactual=ld_totant*(ld_poramo/100);
	 if(f.hidtotant.value=="")
         {
           ld_totant=0;
         }
         else
         {
           ld_totant=parseFloat(f.hidtotant.value);
         }
   
        if(ld_amoactual>ld_totant)
         {
           alert("La Amortizacion supera el Monto del Anticipo!!");
		   f.txtporamoactual.value="0,00";
			 f.txtamoactual.value="0,00";
			f.txtamotot.value="0,00";
			aqui=parseFloat(uf_convertir_monto(f.txtamoant.value));
			aqui1=parseFloat(uf_convertir_monto(f.txtamotot.value));
		 	// asi estaba antes
			ld_resta = ld_totant-(aqui+aqui1);
			//ld_resta = ld_totant - (ld_totant*(ld_poramo/100)+aqui);
			// resta por amortizar
		      	f.txtamores.value=uf_convertir(ld_resta);
	     }
         else
	     {
	      if(f.hidamototbd.value=="")
           {
             ld_amotot=0;
           }
           else
           {
             ld_amotot=parseFloat(f.hidamototbd.value);
           }
		   ld_totalamo=ld_amoactual+ld_amotot;
			casi=ld_totant-ld_totalamo;
			if(casi=0,01)
			{
				ld_totalamo=ld_totant-0,01;
			}
		   if(ld_totalamo>ld_totant)
            {
             alert("La Suma de las Amortizaciones supera el Monto del Anticipo!!");
			 f.txtporamoactual.value="0,00";
			 f.txtamoactual.value="0,00";
			
	        }
		    else
		    {

		      ld_resta=ld_totant-ld_totalamo;

			  ld_subto=ld_totpar-ld_amoactual;

//		      f.txtamoactual.value=uf_convertir(ld_amoactual);
		// total anticipo
		f.txtamoactual.value = uf_convertir( ld_totant*(ld_poramo/100) );

//		      f.txtamotot.value=uf_convertir(ld_totalamo);

		// total amortizado
		      f.txtamotot.value = f.txtamoactual.value;

// AQUI CONVIERTO A NUMERO EL MONTO DEL ANTICIPO ANTERIOR PARA RESTAR MAS ABAJO //
			aqui=parseFloat(uf_convertir_monto(f.txtamoant.value));
		 // asi estaba antes
		//  ld_resta = ld_totant - ld_totant*(ld_poramo/100);
			ld_resta = ld_totant - (ld_totant*(ld_poramo/100)+aqui);
			

		// resta por amortizar
		      f.txtamores.value=uf_convertir(ld_resta);

			  f.hidamoresbd.value=ld_resta;

//		  f.txtsubtot.value=uf_convertir(ld_subto);

		  f.txtsubtot.value=uf_convertir(ld_subto);
//	          f.txtbasimpval.value=uf_convertir(ld_subto);
//	          f.txtmontotval.value=uf_convertir(ld_subto);
//////// AQUI INGRESAMOS LA AMORTIZACION EN EL CUADRO DE RETENCIONES ///////////////
		/*codigo="00005";
		descripcion="AMORTIZACION";
		cuenta="11128000001";
		deducible=0,00;
		monto=uf_convertir( ld_totant*(ld_poramo/100) );
		formula="";
		monto1=uf_convertir( ld_totpar );
		eval("f.txtcodret"+f.hidfilasretenciones.value+".value='"+codigo+"'");
		eval("f.txtdesret"+f.hidfilasretenciones.value+".value='"+descripcion+"'");
		eval("f.txtcueret"+f.hidfilasretenciones.value+".value='"+cuenta+"'");
		eval("f.txtdedret"+f.hidfilasretenciones.value+".value='"+deducible+"'");
		eval("f.txtmonret"+f.hidfilasretenciones.value+".value='"+monto1+"'");
		eval("f.txttotret"+f.hidfilasretenciones.value+".value='"+monto+"'");
		eval("f.formula"+f.hidfilasretenciones.value+".value='"+formula+"'");*/
		//f.hidfilasretenciones.value=2;
		
////////////////////////////////////////////////////////////////////////////////////
		    }
	  }
     }
   }
 } 

function ue_subtotal()
{
	f=document.form1;
	li_filasparitdas=f.filaspartidas.value;
	ld_subtotal=0;
	ls_cero="0,00"
	ls_subtotpar=f.txtsubtotpar.value;
	for(li_i=1;li_i<=li_filasparitdas;li_i++)
	{
	  if(eval("f.flagpar"+li_i+".checked==true"))
		{
		 if(eval("f.txtcantpar"+li_i+".value")=="")
		  {
		   ld_cantpar=0;
		   alert("No le coloco monto a valuar");
		  }
		  else
		   {
			ls_subtotpar=parseFloat(uf_convertir_monto(eval("f.txttotal"+li_i+".value")));
			ls_subtotparantes=parseFloat(uf_convertir_monto(eval("f.txttotal1"+li_i+".value")));
			ls_antes=parseFloat(uf_convertir_monto(eval("f.txttotal1"+li_i+".value")));
			//alert(ls_subtotpar);
		     	ld_cantpar=parseFloat(uf_convertir_monto(eval("f.txtcantpar"+li_i+".value")));
		    
			 //tomando el precio unitario de la partida
		     	if(eval("f.txtpreunimod"+li_i+".value")=="")
		      	{
		       		ld_preuni=0;
		      	}
		      	else
		       	{
		        	ld_preuni=parseFloat(uf_convertir_monto(eval("f.txtpreunimod"+li_i+".value")));
		       	}
			// FIN if
		      	//tomando la cantidad asignada de la partida txtpreunimod
			//if(eval("f.txtcanttot"+li_i+".value")=="") ANTES
		      	if(eval("f.txtpreunimod"+li_i+".value")=="")
		       	{
		        	ld_canttot=0;
		       	}
		       	else
		        {
				/////// ANTES ////
		        	//ld_canttot=parseFloat(uf_convertir_monto(eval("f.txtcanttot"+li_i+".value")));
			  	ld_canttot=parseFloat(uf_convertir_monto(eval("f.txtpreunimod"+li_i+".value")));
			  	ld_canttot=ld_canttot+ls_subtotparantes;
		        }
			// FIN if
		  
			if(ld_canttot<ld_cantpar)
		        {
		        	alert("La cantidad a valuar supera el monto disponible!! Recuerde: aplique solo la Base Imponible");
		        	eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
				///////////////// ANTES //////////////////
		        	//eval("f.txttotal"+li_i+".value='"+ls_cero+"'");
				//eval("f.txttotal"+li_i+".value='"+ls_cero+"'");
				/////// AHORA //////////
				eval("f.txttotal"+li_i+".value='"+ls_subtotparantes+"'");
				ld_totalx="0,00"
		        }
		        else
		        {
				////// ANTES/////////
		          	//ld_totpar=ld_preuni*ld_cantpar;
				//ld_totpar=ld_cantpar+ls_subtotpar;
				//////////////////////
				// AHORA //
				ld_totpar=ld_cantpar+ls_antes; // NO EVALUA NADA
				ls_totp=uf_convertir(ld_totpar); // ACTUALIZA TOTAL ACUMULADO + VALUADO en la OBRA
				//ld_subtotal=ld_subtotal+ld_totpar;
				/// TOTAL VALUACION //
				ld_totalx=ld_cantpar;
				///////////////////////////
		          	//eval("f.txttotal"+li_i+".value='"+ls_totp+"'");
				eval("f.txttotal"+li_i+".value='"+ls_totp+"'");
		         }
			// FIN if
		    }
		  }
		  else
			{
			  if((eval("f.txtcantpar"+li_i+".value")!=""))
		        {
				 if((eval("f.txtcantpar"+li_i+".value")!="0,00"))
				   {
				     alert("Seleccione la Obra antes de colocarle la cantidad a Valuar!!");
				     eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
				   }
				 }
			}
	}	
	//// Total de esta Valuacion ////
	f.txtsubtotpar.value=uf_convertir(ld_totalx);
	//// Total Valuado de la Obra
	//// ANTES  ///
	//ld_subtotal=ld_subtotal+"f.txtsubtot.value";
	f.txtsubtot.value=uf_convertir(ld_totalx);
	f.txtbasimpval.value=uf_convertir(ld_totalx);
	f.txtmontotval.value=uf_convertir(ld_totalx);
}
 
/************************************************************************************************************************************************/
function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!");
	}
	else
	{
	   
		if (f.opemostrar.value == "")
		{
			f.opemostrar.value = "MOSTRAR";
	    }
		else
		{
		  if (f.opemostrar.value == "MOSTRAR")
		   {
		     f.opemostrar.value = "";
		   } 
		}
	
	f.submit();
	}
}
function ue_eliminar()  
{
  f=document.form1;
  li_eliminar=f.eliminar.value;
  if(li_eliminar==1)
   {		
	if (f.txtcodval.value=="")
	{
		alert("Debe seleccionar el Contrato a Eliminar!!");
	}
	else
	{
	 f.action="tepuy_sob_d_valuacion.php";
	 f.operacion.value="ue_eliminar";
	 f.submit();
	}
   }
   else
   {
    alert("No tiene permiso para realizar esta operacion");
   }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
