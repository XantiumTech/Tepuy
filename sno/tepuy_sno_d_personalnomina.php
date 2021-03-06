<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","tepuy_sno_d_personalnomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estper,$ls_codsubnom,$ls_dessubnom,$ls_codasicar,$ls_denasicar,$ls_codcar,$ls_descar;
		global $ls_codtab,$ls_destab,$ls_filtab,$ls_coltab,$ls_codpas,$ls_codgra,$li_sueper,$li_horper,$li_sueintper,$li_sueproper;
		global $ld_fecingper,$ld_fecculcontr,$ls_codded,$ls_desded,$ls_codtipper,$ls_destipper,$ls_codtabvac,$ls_dentabvac;
		global $ls_cueaboper,$ls_codcueban,$ls_codban,$ls_nomban,$ls_codage,$ls_nomage,$ls_coduniadm,$ls_desuniadm,$ls_codescdoc;
		global $ls_desescdoc,$ls_codcladoc,$ls_descladoc,$ls_codubifis,$ls_desubifis,$ls_dencueaboper,$la_tipcuebanper,$la_tipcestic;
		global $ls_pagoefectivo,$ls_pagobanco,$ls_operacion,$ls_existe,$io_fun_nomina,$ls_desnom,$ls_codnom,$li_rac,$li_subnomina;
		global $ls_sueldo,$ls_desper,$li_tipnom,$ls_conjub,$la_conjub,$ls_catjub,$la_catjub,$ls_codclavia,$ls_dencat,$li_calculada;
		global $li_camuniadm,$li_campasogrado,$li_implementarcodunirac,$ls_codunirac,$ls_tipcuebanper, $ls_pagotaquilla,$li_compensacion;
		global $li_loncueban, $li_valloncueban, $ld_fecascper,$ls_grado;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codper="";
		$ls_nomper="";
		$ls_estper="";
		$ls_codsubnom="";
		$ls_dessubnom="";
		$ls_codasicar="";
		$ls_denasicar="";
		$ls_codcar="";
		$ls_descar="";
		$ls_codtab="";
		$ls_destab="";
		$ls_filtab="";
		$ls_coltab="";				
		$ls_codpas="";
		$ls_codgra="";
		$li_sueper=0;
		$li_compensacion=0;
		$li_horper=7;
		$li_sueintper=0;
		$li_sueproper=0;
		$ld_fecingper="dd/mm/aaaa";
		$ld_fecculcontr="dd/mm/aaaa";
		$ld_fecascper="dd/mm/aaaa";
		$ls_codded="100";
		$ls_desded="Personal Fijo Tiempo Completo";
		$ls_codtipper="0103";
		$ls_destipper="Personal Administrativo";
		$ls_codtabvac="01";
		$ls_dentabvac="VACACIONES (EMPLEADOS)";
		$ls_cueaboper="";
		$ls_dencueaboper="";
  	    	$ls_codcueban="";
		$ls_codban="";
		$ls_nomban="";
		$ls_codage="";
		$ls_nomage="";
		$ls_coduniadm="";		
		$ls_desuniadm="";		
		$ls_codescdoc="0001";
		$ls_desescdoc="Ninguna";
		$ls_codcladoc="0000";
		$ls_descladoc="Ninguna";
		$ls_codubifis="0001";
		$ls_desubifis="Barinas";
		$la_tipcuebanper[0]="";
		$la_tipcuebanper[1]="";
		$la_tipcuebanper[2]="";
		$la_tipcestic[0]="";
		$la_tipcestic[1]="";
		$ls_conjub="";
		$la_conjub[1]="";
		$la_conjub[2]="";
		$la_conjub[3]="";
		$ls_catjub="";
		$la_catjub[1]="";
		$la_catjub[2]="";
		$la_catjub[3]="";
		$ls_pagoefectivo="";
		$ls_pagobanco="";
		$ls_pagotaquilla="";
		$ls_codclavia="I";
		$ls_dencat="VIATICO GENERAL";		
		$ls_codunirac="";
		$ls_tipcuebanper="";
		$ls_grado="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$li_subnomina=$_SESSION["la_nomina"]["subnom"];	
		$li_tipnom=$_SESSION["la_nomina"]["tipnom"];	
		$ls_sueldo="";
		if($li_rac=="1")
		{
			$ls_sueldo=" readonly";
		}
		require_once("tepuy_sno_c_calcularnomina.php");
		$io_calcularnomina=new tepuy_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
		require_once("tepuy_sno.php");
		$io_sno=new tepuy_sno();
		$li_camuniadm=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_UNIDAD_ADM_RAC","0","I"));
		$li_campasogrado=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_PASO_GRADO_RAC","0","I"));
		$li_implementarcodunirac=trim($io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
		$li_loncueban=trim($io_sno->uf_select_config("SNO","CONFIG","LONGITUD_CUENTA_BANCO","0","I"));
		$li_valloncueban=trim($io_sno->uf_select_config("SNO","CONFIG","VALIDAR_LONGITUD_CUEBANCO","0","I"));
		unset($io_sno);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 23/03/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper, $ls_nomper, $ls_estper, $ls_codsubnom, $ls_dessubnom, $ls_codasicar, $ls_denasicar, $ls_codtab, $ls_destab;
		global $ls_codpas, $ls_codgra, $ls_codcar, $ls_descar, $ls_coduniadm, $li_sueper, $li_horper, $li_sueintper, $li_sueproper;
		global $ld_fecingper, $ld_fecculcontr, $ls_codded, $ls_desded, $ls_codtipper, $ls_destipper, $ls_codtabvac, $ls_dentabvac;
		global $li_pagefeper, $li_pagbanper, $ls_codban, $ls_nomban, $ls_codcueban, $ls_tipcuebanper, $ls_cueaboper, $ls_dencueaboper;
		global $ls_codage, $ls_nomage, $ls_tipcestic, $ls_codescdoc, $ls_desescdoc, $ls_codcladoc, $ls_descladoc, $ls_codubifis, $ls_desubifis;
		global $io_fun_nomina,$ls_conjub,$ls_catjub,$ls_codclavia,$ls_dencat,$ls_desuniadm,$ls_codunirac, $li_pagtaqper,$li_compensacion;
		global $ld_fecascper,$ls_grado;

		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_estper=$_POST["txtestper"];
		$ls_codunirac=$io_fun_nomina->uf_obtenervalor("txtcodunirac","");
		$ls_codsubnom=$io_fun_nomina->uf_obtenervalor("txtcodsubnom","0000000000");
		$ls_dessubnom=$io_fun_nomina->uf_obtenervalor("txtdessubnom","Sin Subn�mina");
		$ls_codasicar=$io_fun_nomina->uf_obtenervalor("txtcodasicar","0000000");
		$ls_denasicar=$io_fun_nomina->uf_obtenervalor("txtdenasicar","Sin Asignaci�n de Cargo");
		$ls_codtab=$io_fun_nomina->uf_asignarvalor("txtcodtab","00000000000000000000");
		$ls_destab=$io_fun_nomina->uf_asignarvalor("txtdestab","Sin Tabla");
		$ls_codpas=$io_fun_nomina->uf_asignarvalor("txtcodpas","00");
		$ls_codgra=$io_fun_nomina->uf_asignarvalor("txtcodgra","00");
		$ls_codcar=$io_fun_nomina->uf_obtenervalor("txtcodcar","0000000000");
		$ls_descar=$io_fun_nomina->uf_obtenervalor("txtdescar","Sin Cargo");
		$ls_conjub=$io_fun_nomina->uf_obtenervalor("cmbconjub","0000");
		$ls_catjub=$io_fun_nomina->uf_obtenervalor("cmbcatjub","000");
		$ls_coduniadm=$_POST["txtcoduniadm"];
		$ls_desuniadm=$_POST["txtdesuniadm"];	
		$li_sueper=$_POST["txtsueper"];
		$li_compensacion=$_POST["txtcompensacion"];
		$li_horper=$_POST["txthorper"];
		$li_sueintper=$_POST["txtsueintper"];
		$li_sueproper=$_POST["txtsueproper"];
		$ld_fecingper=$_POST["txtfecingper"];
		$ld_fecascper=$_POST["txtfecascper"];
		$ld_fecculcontr=$_POST["txtfecculcontr"];
		$ls_codded=$_POST["txtcodded"];
		$ls_desded=$_POST["txtdesded"];
		$ls_codtipper=$_POST["txtcodtipper"];
		$ls_destipper=$_POST["txtdestipper"];
		$ls_codtabvac=$io_fun_nomina->uf_asignarvalor("txtcodtabvac","00");
		$ls_dentabvac=$io_fun_nomina->uf_asignarvalor("txtdentabvac","Sin Tabla de vacaciones");
		$li_pagefeper=$io_fun_nomina->uf_obtenervalor("chkpagefeper","0");
		$li_pagbanper=$io_fun_nomina->uf_obtenervalor("chkpagbanper","0");
		$li_pagtaqper=$io_fun_nomina->uf_obtenervalor("chkpagtaqper","0");
		$ls_codban=$_POST["txtcodban"];
		$ls_nomban=$_POST["txtnomban"];
		$ls_codcueban=$_POST["txtcodcueban"];
		$ls_tipcuebanper=$io_fun_nomina->uf_obtenervalor("cmbtipcuebanper",$_POST["txttipcuebanper"]);
		$ls_cueaboper=$_POST["txtcuecon"];
		$ls_dencueaboper=$_POST["txtdencuecon"];
		$ls_codage=$_POST["txtcodage"];
		$ls_nomage=$_POST["txtnomage"];
		$ls_tipcestic=$_POST["cmbtipcestic"];
		$ls_codclavia=$_POST["txtcodclavia"];
		$ls_dencat=$_POST["txtdencat"];		
		$ls_grado=$io_fun_nomina->uf_asignarvalor("txtgrado","0000");
		$ls_codescdoc=$io_fun_nomina->uf_asignarvalor("txtcodescdoc","0000");
		$ls_desescdoc=$io_fun_nomina->uf_asignarvalor("txtdesescdoc","Sin Escala Docente");
		$ls_codcladoc=$io_fun_nomina->uf_asignarvalor("txtcodcladoc","0000");
		$ls_descladoc=$io_fun_nomina->uf_asignarvalor("txtdescladoc","Sin Clasificaci�n Docente");
		$ls_codubifis=$io_fun_nomina->uf_asignarvalor("txtcodubifis","0000");
		$ls_desubifis=$io_fun_nomina->uf_asignarvalor("txtdesubifis","Sin Ubicaci�n f�sica");
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.keyCode == 17 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Asignaci&oacute;n de Personal a N&oacute;mina</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("tepuy_sno_c_personalnomina.php");
	$io_personalnomina=new tepuy_sno_c_personalnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_personalnomina->uf_guardar($ls_existe,$ls_codper,$ls_codsubnom,$ls_codasicar,$ls_codcar,$ls_codtab,$ls_codpas,
													  $ls_codgra,$ls_coduniadm,$li_sueper,$li_horper,$li_sueintper,$li_sueproper,
													  $ld_fecingper,$ld_fecculcontr,$ls_codded,$ls_codtipper,$ls_codtabvac,$li_pagefeper,
													  $li_pagbanper,$ls_codban,$ls_codcueban,$ls_tipcuebanper,$ls_cueaboper,$ls_codage,
													  $ls_tipcestic,$ls_codescdoc,$ls_codcladoc,$ls_codubifis,$ls_conjub,$ls_catjub,
													  $ls_codclavia,$ls_codunirac,$li_pagtaqper,$ld_fecascper,$ls_grado,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("A-C-L",$ls_tipcuebanper,$la_tipcuebanper,3);
				$io_fun_nomina->uf_seleccionarcombo("TA-TI",$ls_tipcestic,$la_tipcestic,2);
				$io_fun_nomina->uf_seleccionarcombo("001-002-003",$ls_catjub,$la_catjub,3);
				$io_fun_nomina->uf_seleccionarcombo("0001-0002-0003",$ls_conjub,$la_conjub,3);
				$ls_pagobanco=$io_fun_nomina->uf_obtenervariable($li_pagbanper,1,0,"checked","","");
				$ls_pagoefectivo=$io_fun_nomina->uf_obtenervariable($li_pagefeper,1,0,"checked","","");	
				$ls_pagotaquilla=$io_fun_nomina->uf_obtenervariable($li_pagtaqper,1,0,"checked","","");	
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_personalnomina->uf_delete_personalnomina($ls_codper,$ls_codasicar,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("A-C-L",$ls_tipcuebanper,$la_tipcuebanper,3);
				$io_fun_nomina->uf_seleccionarcombo("TA-TI",$ls_tipcestic,$la_tipcestic,2);
				$ls_pagobanco=$io_fun_nomina->uf_obtenervariable($li_pagbanper,1,0,"checked","","");
				$ls_pagoefectivo=$io_fun_nomina->uf_obtenervariable($li_pagefeper,1,0,"checked","","");				
				$ls_pagotaquilla=$io_fun_nomina->uf_obtenervariable($li_pagtaqper,1,0,"checked","","");	
			}
			break;

	}
	$io_personalnomina->uf_destructor();
	unset($io_personalnomina);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu_nomina.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Asignaci&oacute;n de Personal a N&oacute;mina </td>
      </tr>
      <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal </td>
      </tr>
      <tr>
        <td width="134" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="txtestper" type="text" class="sin-borde2" id="txtestper" value="<?php print $ls_estper;?>" size="20" maxlength="20" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="90" maxlength="120" readonly></td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Informaci&oacute;n de N&oacute;mina </div></td>
      </tr>
	  <?php 
		  if(($li_implementarcodunirac=="1")&&($li_rac=="1")) {?>
      <tr>
        <td height="22"><div align="right">C&oacute;digo RAC </div></td>
        <td colspan="3">
          <input name="txtcodunirac" type="text" id="txtcodunirac" size="12" maxlength="10" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codunirac;?>">        </td>
      </tr>
	 <?php }
		   if($li_subnomina=="1") {?>	  
      <tr>
        <td height="22"><div align="right">Subn&oacute;mina</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodsubnom" type="text" id="txtcodsubnom" value="<?php print $ls_codsubnom;?>" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscarsubnomina();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdessubnom" type="text" class="sin-borde" id="txtdessubnom" value="<?php print $ls_dessubnom;?>" size="63" maxlength="60" readonly>
        </div></td>
      </tr>
	 <?php }
	  	   if($li_rac=="0") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Cargo</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcar" type="text" id="txtcodcar" value="<?php print $ls_codcar;?>" size="13" maxlength="10"  readonly>
          <a href="javascript: ue_buscarcargo();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
          &nbsp;
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" value="<?php print $ls_descar;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
		 <?php 
		 	   if(($li_tipnom=="3")||($li_tipnom=="4"))
			   {
		 ?>	  
      <tr>
        <td height="22"><div align="right">Clasificaci�n Obrero</div></td>
        <td colspan="3"><div align="left">
          <input name="txtgrado" type="text" id="txtgrado" value="<?php print $ls_grado;?>" size="13" maxlength="4"  readonly>
          <a href="javascript: ue_buscarclasificacionobrero();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
        </div></td>
      </tr>

     <?php 
	 	   		}
		   }
	 	   else
		   {
	 ?>
      <tr>
        <td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodasicar" type="text" id="txtcodasicar" value="<?php print $ls_codasicar;?>" size="10" maxlength="7"  readonly>
          <a href="javascript: ue_buscarasignacioncargo();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="<?php print $ls_denasicar;?>" size="27" maxlength="24" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtab" type="text" id="txtcodtab" value="<?php print $ls_codtab;?>" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestab" type="text" class="sin-borde" id="txtdestab" value="<?php print $ls_destab;?>" size="60" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Grado
        </div></td>
        <td width="152"><div align="left">
		<input name="txtcodgra" type="text" id="txtcodgra" value="<?php print $ls_codgra;?>" size="18" maxlength="15" readonly>                      
     <?php
	  	   if($li_campasogrado=="1")
		   {
	 ?>	  
          <a href="javascript: ue_buscargrado();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
     <?php }?>
        <td width="172"><div align="right">
        </div></td>
        <td width="232"><div align="left">Paso
			<input name="txtcodpas" type="text" id="txtcodpas" value="<?php print $ls_codpas;?>" size="18" maxlength="15" readonly>          
        </div></td>
      </tr>
     <?php }?>
      <tr>
        <td height="22"><div align="right">Sueldo</div></td>
        <td><div align="left">
          <input name="txtsueper" type="text" id="txtsueper" value="<?php print $li_sueper;?>" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" <?php print $ls_sueldo;?>>
        </div></td>
        <td><div align="right">Compensacion</div></td>
        <td>
          <input name="txtcompensacion" type="text" value="<?php print $li_compensacion;?>" id="txtcompensacion" style="text-align:right" readonly>
        </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Unidad Administrativa </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm;?>" size="19" maxlength="16" readonly>
     <?php
	  	   if(($li_rac=="0") || ($li_camuniadm=="1"))
		   {
	 ?>	  
          <a href="javascript: ue_buscarunidadadministrativa();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <?php
	  	   }
	 ?>	  
          <input name="txtdesuniadm" type="text" class="sin-borde" id="txtdesuniadm" value="<?php print $ls_desuniadm;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded;?>" size="6" maxlength="3" readonly>
          <?php
	  	   if($li_rac=="0") {
	 ?>	  
          <a href="javascript: ue_buscardedicacion();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <?php
	  	   }
	 ?>	  
          <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" value="<?php print $ls_desded;?>" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Personal </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtipper" type="text" id="txtcodtipper" size="7" maxlength="4" value="<?php print $ls_codtipper;?>" readonly>
          <?php
	  	   if($li_rac=="0") {
	 ?>	  
          <a href="javascript: ue_buscartipopersonal();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <?php
	  	   }
	 ?>	  
          <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_destipper;?>" size="60" maxlength="100" readonly>
        </div></td>
      </tr>
	 <?php
	  	   if($li_tipnom=="7") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Categor&iacute;a</div></td>
        <td colspan="3">
          
            <div align="left">
              <select name="cmbcatjub" id="cmbcatjub">
                <option value="000" selected>--Seleccione--</option>
                <option value="001" <?php print $la_catjub[1];?>>Docente</option>
                <option value="002" <?php print $la_catjub[2];?>>Administrativo</option>
                <option value="003" <?php print $la_catjub[3];?>>Obrero</option>
              </select>
            </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Condici&oacute;n</div></td>
        <td colspan="3">
          
            <div align="left">
              <select name="cmbconjub" id="cmbconjub">
                <option value="0000" selected>--Seleccione--</option>
                <option value="0001" <?php print $la_conjub[1];?>>Jubilado</option>
                <option value="0002" <?php print $la_conjub[2];?>>Pensionado</option>
                <option value="0003" <?php print $la_conjub[3];?>>Sobreviviente</option>
              </select>
            </div></td>
        </tr>
	 <?php
	  	   }
	 ?>	  
      <tr>
        <td height="22"><div align="right">Sueldo Integral </div></td>
        <td><div align="left">
          <input name="txtsueintper" type="text" id="txtsueintper" value="<?php print $li_sueintper;?>" size="23" maxlength="20" style="text-align:right" readonly>
        </div></td>
        <td><div align="right">Sueldo Promedio</div></td>
        <td><div align="left">
          <input name="txtsueproper" type="text" id="txtsueproper" value="<?php print $li_sueproper;?>" size="23" maxlength="20" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Horas</div></td>
        <td colspan="3"><div align="left">
          <input name="txthorper" type="text" id="txthorper" value="<?php print $li_horper;?>" size="12" maxlength="9"  onKeyPress="return(ue_formatonumero(this,'.',',',event))">
        </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Ingreso a la n&oacute;mina </div></td>
        <td><div align="left">
          <input name="txtfecingper" type="text" id="txtfecingper" value="<?php print $ld_fecingper;?>" size="15" maxlength="10" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" datepicker="true">
        </div></td>
        <td><div align="right">Culminaci&oacute;n de Contrato </div></td>
        <td><div align="left">
          <input name="txtfecculcontr" type="text" id="txtfecculcontr" value="<?php print $ld_fecculcontr;?>" size="15" maxlength="10" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Ascenso </div></td>
        <td colspan="3"><input name="txtfecascper" type="text" id="txtfecascper" value="<?php print $ld_fecascper;?>" size="15" maxlength="10" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" datepicker="true"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tabla de Vacaciones </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtabvac" type="text" id="txtcodtabvac" size="5" maxlength="2" value="<?php print $ls_codtabvac;?>" readonly>
          <a href="javascript: ue_buscartablavacacion();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
          <input name="txtdentabvac" type="text" class="sin-borde" id="txtdentabvac" value="<?php print $ls_dentabvac;?>" size="60" maxlength="120" readonly>
        </div></td>
      </tr>
		<tr>
        <td height="22"><div align="right">Clasificaci�n de Viaticos</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodclavia" type="text" id="txcodclavia" value="<?php print $ls_codclavia;?>" size="7" maxlength="1" readonly>
          <a href="javascript: ue_buscarclasificacionviatico();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
          <input name="txtdencat" type="text" class="sin-borde" id="txtdencat" value="<?php print $ls_dencat;?>" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Escala Docente </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodescdoc" type="text" id="txcodescdoc" value="<?php print $ls_codescdoc;?>" size="7" maxlength="4" readonly>
          <a href="javascript: ue_buscarescaladocente();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
          <input name="txtdesescdoc" type="text" class="sin-borde" id="txtdesescdoc" value="<?php print $ls_desescdoc;?>" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Clasificaci&oacute;n Docente </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcladoc" type="text" id="txtcodcladoc" value="<?php print $ls_codcladoc;?>" size="7" maxlength="4" readonly>
          <a href="javascript: ue_buscarclasificaciondocente();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
          <input name="txtdescladoc" type="text" class="sin-borde" id="txtdescladoc" value="<?php print $ls_descladoc;?>" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Ubicaci&oacute;n F&iacute;sica</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodubifis" type="text" id="txtcodubifis" value="<?php print $ls_codubifis;?>" size="7" maxlength="4" readonly>
          <a href="javascript: ue_buscarubicacionfisica();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
          <input name="txtdesubifis" type="text" class="sin-borde" id="txtdesubifis" value="<?php print $ls_desubifis;?>" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Informaci&oacute;n de Pago</div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Pago en Efectivo &oacute; Cheque </div></td>
        <td>
          <div align="left">
            <input name="chkpagefeper" type="checkbox" class="sin-borde" id="chkpagefeper"  onClick="javascript: ue_camposefectivo();" value="1" <?php print $ls_pagoefectivo;?>>
            </div></td>
        <td><div align="right">Pago por Banco </div></td>
        <td><div align="left">
          <input name="chkpagbanper" type="checkbox" class="sin-borde" id="chkpagbanper" onClick="javascript: ue_camposbanco();" value="1" <?php print $ls_pagobanco;?>>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Pago por Taquilla 
          </div></td>
        <td colspan="3">
        <div align="left">
          <input name="chkpagtaqper" type="checkbox" class="sin-borde" id="chkpagtaqper" onClick="javascript: ue_campostaquilla();" value="1" <?php print $ls_pagotaquilla;?>>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Abono</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcuecon" type="text" id="txtcuecon" value="<?php print $ls_cueaboper;?>" size="28" maxlength="25" readonly>
          <a href="javascript: ue_buscarcuentaabono();"><img id="cuentaabono" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" value="<?php print $ls_dencueaboper;?>" size="50" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodban" type="text" id="txtcodban" value="<?php print $ls_codban;?>" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarbanco();"><img id="banco" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0" style="visibility:hidden "></a>
            <input name="txtnomban" type="text" class="sin-borde" id="txtnomban" value="<?php print $ls_nomban;?>" size="50" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Agencia</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodage" type="text" id="txtcodage" value="<?php print $ls_codage;?>" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscaragencia();"><img id="agencia" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0" style="visibility:hidden "></a>
          <input name="txtnomage" type="text" class="sin-borde" id="txtnomage" value="<?php print $ls_nomage;?>" size="50" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nro de Cuenta </div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodcueban" type="text" id="txtcodcueban" value="<?php print $ls_codcueban;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Cuenta </div></td>
        <td colspan="3"><div align="left">
          <select name="cmbtipcuebanper" id="cmbtipcuebanper" disabled>
            <option value="" selected>--Seleccione Una--</option>
            <option value="A" <?php print $la_tipcuebanper[0];?>>Ahorro</option>
            <option value="C" <?php print $la_tipcuebanper[1];?>>Corriente</option>
            <option value="L" <?php print $la_tipcuebanper[2];?>>Activos L&iacute;quidos</option>
          </select>
          
          <input name="txttipcuebanper" type="hidden" id="txttipcuebanper" value="<?php print $ls_tipcuebanper;?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cesta Ticket</div></td>
        <td colspan="3">
          <div align="left">
            <select name="cmbtipcestic" id="cmbtipcestic">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="TA" <?php print $la_tipcestic[0];?>>Tarjeta</option>
              <option value="TI" <?php print $la_tipcestic[1];?>>Ticket</option>
            </select>
            </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"><input name="operacion" type="hidden" id="operacion">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
          <input name="subnomina" type="hidden" id="subnomina" value="<?php print $li_subnomina;?>">
          <input name="camuniadm" type="hidden" id="camuniadm" value="<?php print $li_camuniadm;?>">
          <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
          <input name="codunirac" type="hidden" id="codunirac" value="<?php print $li_implementarcodunirac;?>">		  
		  <input type="hidden" name="loncueban" id="loncueban" value="<?php print $li_loncueban;?>">
          <input type="hidden" name="valloncueban" id="valloncueban" value="<?php print $li_valloncueban;?>"></td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		if(li_incluir==1)
		{	
			f.operacion.value="NUEVO";
			f.existe.value="FALSE";		
			f.action="tepuy_sno_d_personalnomina.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La n�mina ya se calcul� reverse y vuelva a intentar");
	}
}

function ue_guardar()
{
	valido=true;
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_existe=f.existe.value;
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codper = ue_validarvacio(f.txtcodper.value);
			sueper = ue_validarvacio(f.txtsueper.value);
			horper = ue_validarvacio(f.txthorper.value);
			coduniadm = ue_validarvacio(f.txtcoduniadm.value);
			if(f.rac.value=="0")
			{
				codcar = ue_validarvacio(f.txtcodcar.value);
			}
			else
			{
				codcar = ue_validarvacio(f.txtcodasicar.value);
			}
			codded = ue_validarvacio(f.txtcodded.value);
			codtipper = ue_validarvacio(f.txtcodtipper.value);
			codtabvac = ue_validarvacio(f.txtcodtabvac.value);
			f.txtfecingper.value = ue_validarfecha(f.txtfecingper.value);
			f.txtfecculcontr.value = ue_validarfecha(f.txtfecculcontr.value);
			f.txtfecascper.value = ue_validarfecha(f.txtfecascper.value);
			fecingper = ue_validarvacio(f.txtfecingper.value);
			if ((codper!="")&&(sueper!="")&&(horper!="")&&(coduniadm!="")&&(fecingper!="")&&(codcar!="")&&(codded!="")&&(codtipper!="")
				&&(codtabvac!=""))
			{
				if (f.chkpagbanper.checked)
				{
					codban = ue_validarvacio(f.txtcodban.value);
					codage = ue_validarvacio(f.txtcodage.value);
					codcueban = ue_validarvacio(f.txtcodcueban.value);
					tipcueban = ue_validarvacio(f.cmbtipcuebanper.value);
					loncueban = ue_validarvacio(f.loncueban.value);
					valloncueban = ue_validarvacio(f.valloncueban.value);
					if (!((codban!="")&&(codage!="")&&(codcueban!="")&&(tipcueban!="")))
					{
						valido=false;
						alert("Debe llenar todos los datos del banco.");
					}
					if(valloncueban=="1")
					{
						if(codcueban.length!=loncueban)
						{
							valido=false;
							alert("La Longitud de la cuenta de banco no coincide con la definida en configuraci�n.");
						}
					}
					
				}
				if (f.chkpagefeper.checked)
				{
					cueaboper = ue_validarvacio(f.txtcuecon.value);
					if (cueaboper=="")
					{
						valido=false;
						alert("Debe llenar la cuenta abono.");
					}
				}
				if (f.chkpagtaqper.checked)
				{
					codban = ue_validarvacio(f.txtcodban.value);
					if (codban=="")
					{
						valido=false;
						alert("Debe llenar los datos del banco.");
					}
				}
				if((f.chkpagbanper.checked==false)&&(f.chkpagefeper.checked==false)&&(f.chkpagtaqper.checked==false))
				{
					valido=false;
					alert("Debe seleccionar una forma de pago.");
				}
			}
			else
			{
				valido=false;
				alert("Debe llenar todos los datos del personal.");
			}
			codescdoc = ue_validarvacio(f.txtcodescdoc.value);
			codcladoc = ue_validarvacio(f.txtcodcladoc.value);
			if(codescdoc!="")
			{
				if(codcladoc=="")
				{
					valido=false;
					alert("Debe llenar la Clasificaci�n Docente.");
				}
			}
			subnomina=f.subnomina.value;
			if(subnomina=="1")
			{
				codsubnom = ue_validarvacio(f.txtcodsubnom.value);
				if(codsubnom=="")
				{
					valido=false;
					alert("Debe seleccionar una subn�mina.");
				}
			}
			if(valido)
			{
					f.operacion.value="GUARDAR";
					f.action="tepuy_sno_d_personalnomina.php";
					f.submit();			
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La n�mina ya se calcul� reverse y vuelva a intentar");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if(f.existe.value=="TRUE")
			{
				codper = ue_validarvacio(f.txtcodper.value);
				if (codper!="")
				{
					if(confirm("�Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="tepuy_sno_d_personalnomina.php";
						f.submit();
					}
				}
				else
				{
					alert("Debe buscar el registro a eliminar.");
				}
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La n�mina ya se calcul� reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	location.href = "tepuywindow_blank_nomina.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_sno_cat_personalnomina.php?subnom=<?php print $li_subnomina;?>","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarpersonal()
{
	f=document.form1;
	if ((f.existe.value=="")||(f.existe.value=="FALSE"))
	{
		window.open("tepuy_snorh_cat_personal.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarsubnomina()
{
	window.open("tepuy_snorh_cat_subnomina.php?txtcodigo=<?php print $ls_codnom;?>&tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarasignacioncargo()
{
	window.open("tepuy_sno_cat_asignacioncargo.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcargo()
{
	window.open("tepuy_sno_cat_cargo.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarunidadadministrativa()
{
	f=document.form1;
	if((f.rac.value=="0")||(f.camuniadm.value=="1"))
	{
		window.open("tepuy_snorh_cat_uni_ad.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscardedicacion()
{
	f=document.form1;
	if(f.rac.value=="0")
	{
		window.open("tepuy_snorh_cat_dedicacion.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscartipopersonal()
{
	f=document.form1;
	if(f.rac.value=="0")
	{
		codded = ue_validarvacio(f.txtcodded.value);
		if (codded!="")
		{
			window.open("tepuy_snorh_cat_tipopersonal.php?tipo=asignacion&codded="+codded+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe Seleccionar la dedicaci�n");
		}
	}
}

function ue_buscartablavacacion()
{
	window.open("tepuy_snorh_cat_tablavacacion.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarescaladocente()
{
	window.open("tepuy_snorh_cat_escaladocente.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarclasificaciondocente()
{
	f=document.form1;
	codescdoc = ue_validarvacio(f.txtcodescdoc.value);
	if(codescdoc!="")
	{
		window.open("tepuy_snorh_cat_clasifidocente.php?tipo=asignacion&codescdoc="+codescdoc+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar la Escala Docente");
	}
}

function ue_buscarubicacionfisica()
{
	window.open("tepuy_snorh_cat_ubicacionfisica.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarclasificacionviatico()
{
	window.open("tepuy_snorh_cat_clasificacionviaticos.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


function ue_buscarbanco()
{
	f=document.form1;
	if((f.chkpagbanper.checked)||(f.chkpagtaqper.checked))
	{
		window.open("tepuy_snorh_cat_banco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscaragencia()
{
	f=document.form1;
	codban = ue_validarvacio(f.txtcodban.value);
	if(f.chkpagbanper.checked)
	{
		if(codban!="")
		{
			window.open("tepuy_snorh_cat_agencia.php?txtcodban="+codban+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe seleccionar el banco");
		}
	}
}

function ue_buscarcuentaabono()
{
	f=document.form1;
	if(f.chkpagefeper.checked)
	{
		window.open("tepuy_sno_cat_cuentacontable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}
//--------------------------------------------------------
//	Funci�n que habilita campos de Banco
//--------------------------------------------------------
function ue_camposbanco()
{
	f=document.form1;
	if(f.chkpagbanper.checked)
	{
		f.cmbtipcuebanper.disabled=false;
		f.txtcodcueban.readOnly=false;
		document.images["banco"].style.visibility="visible";
		document.images["agencia"].style.visibility="visible";
		document.images["cuentaabono"].style.visibility="hidden";
		f.chkpagefeper.checked=false;
		f.chkpagtaqper.checked=false;
		f.txtcuecon.value="";
		f.txtdencuecon.value="";
	}
	else
	{
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["banco"].style.visibility="hidden";
		document.images["agencia"].style.visibility="hidden";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
	}
}

//--------------------------------------------------------
//	Funci�n que habilita campos de Banco
//--------------------------------------------------------
function ue_camposefectivo()
{
	f=document.form1;
	if(f.chkpagefeper.checked)
	{
		f.chkpagbanper.checked=false;
		f.chkpagtaqper.checked=false;
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["cuentaabono"].style.visibility="visible";
		document.images["banco"].style.visibility="hidden";
		document.images["agencia"].style.visibility="hidden";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
		f.txttipcuebanper.value="";
	}
	else
	{
		document.images["cuentaabono"].style.visibility="hidden";
		f.txtcuecon.value="";
		f.txtdencuecon.value="";
	}
}

//--------------------------------------------------------
//	Funci�n que habilita campos de taquilla
//--------------------------------------------------------
function ue_campostaquilla()
{
	f=document.form1;
	if(f.chkpagtaqper.checked)
	{
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["banco"].style.visibility="visible";
		document.images["agencia"].style.visibility="hidden";
		document.images["cuentaabono"].style.visibility="hidden";
		f.chkpagefeper.checked=false;
		f.chkpagbanper.checked=false;
		f.txtcuecon.value="";
		f.txtdencuecon.value="";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
		f.txttipcuebanper.value="";
	}
	else
	{
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["banco"].style.visibility="hidden";
		document.images["agencia"].style.visibility="hidden";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
	}
}


function ue_buscargrado()
{
	f=document.form1;
	codtab = ue_validarvacio(f.txtcodtab.value);
	if (codtab!="")
	{
		window.open("tepuy_sno_cat_grado.php?tipo=asignacionpersonal&tab="+codtab+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar la tabla");
	}
}

function ue_buscarclasificacionobrero()
{
	window.open("tepuy_snorh_cat_clasificacionobrero.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>
