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
	require_once("class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$io_fun_sep->uf_load_seguridad("SEP","tepuy_sep_p_solicitud.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_sep->uf_select_config("SEP","REPORTE","FORMATO_SEP","tepuy_sep_rfs_solicitudes.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecregsol,$ls_numsol,$ls_coduniadm,$ls_denuniadm,$ls_codfuefin,$ls_denfuefin,$ls_tipoformato,$ls_tipafeiva;
   		global $ls_codprovben,$ls_nomprovben,$ls_consol,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5;
		global $ls_operacion,$ls_existe,$io_fun_sep,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$ls_codtipsol,$li_totrowcuentas;
		global $ls_mes, $ls_parametros,$ls_tipodestino,$ls_estsol,$ls_estapro,$ls_codtipsol,$li_totrowconceptos,$li_totrowcuentascargo;
		
		$ls_estatus="REGISTRO";
		$ls_estsol="R";
		$ld_fecregsol=date("d/m/Y");
		$ls_numsol="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_codprovben="";
		$ls_nomprovben="";
		$ls_consol="";
		$ls_codestpro1="";		
		$ls_codestpro2="";		
		$ls_codestpro3="";		
		$ls_codestpro4="";		
		$ls_codestpro5="";	
		$ls_codtipsol="";	
		$ls_parametros="";
		$ls_tipodestino="";
		$ls_operacion=$io_fun_sep->uf_obteneroperacion();
		$ls_existe=$io_fun_sep->uf_obtenerexiste();	
		$li_totrowbienes=0;
		$li_totrowservicios=0;
		$li_totrowconceptos=0;
		$li_totrowcargos=0;
		$li_totrowcuentas=0;
		$li_totrowcuentascargo=0;
		$ls_estapro="";
		$ls_codtipsol="";
		$ls_tipoformato="";
		$ls_tipafeiva=$_SESSION["la_empresa"]["confiva"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_fecregsol,$ls_numsol,$ls_coduniadm,$ls_denuniadm,$ls_codfuefin,$ls_denfuefin,$ls_tipodestino,$ls_codprovben;
		global $ls_nomprovben,$ls_consol,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5;
		global $ls_codtipsol,$li_subtotal,$li_cargos,$li_total,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$li_totrowcuentas;
		global $ls_estsol,$io_fun_sep,$li_totrowconceptos,$li_totrowcuentascargo,$ls_tipafeiva;
		
		$ls_estsol=$_POST["txtestsol"];
		$ld_fecregsol=$io_fun_sep->uf_obtenervalor("txtfecregsol",$_POST["txtfecha"]);
		$ls_numsol=$_POST["txtnumsol"];
		$ls_codtipsol=$io_fun_sep->uf_obtenervalor("cmbcodtipsol",$_POST["txttipsol"]);
		$ls_coduniadm=$_POST["txtcoduniadm"];
		$ls_denuniadm=$_POST["txtdenuniadm"];
		$ls_codfuefin=$_POST["txtcodfuefin"];
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=$_POST["txtdenfuefin"];
		$ls_tipodestino=$_POST["cmbtipdes"];
		$ls_codprovben=$_POST["txtcodigo"];
		$ls_nomprovben=$_POST["txtnombre"];
		$ls_consol=$_POST["txtconsol"];
		$ls_codestpro1=$_POST["txtcodestpro1"];	
		$ls_codestpro2=$_POST["txtcodestpro2"];	
		$ls_codestpro3=$_POST["txtcodestpro3"];		
		$ls_codestpro4=$_POST["txtcodestpro4"];		
		$ls_codestpro5=$_POST["txtcodestpro5"];	
		$li_totrowbienes=$_POST["totrowbienes"];
		$li_totrowservicios=$_POST["totrowservicios"];
		$li_totrowconceptos=$_POST["totrowconceptos"];
		$li_totrowcargos=$_POST["totrowcargos"];
		$li_totrowcuentas=$_POST["totrowcuentas"];
		$li_totrowcuentascargo=$_POST["totrowcuentascargo"];
		$li_subtotal=$_POST["txtsubtotal"];
		$li_cargos=$_POST["txtcargos"];
		$li_total=$_POST["txttotal"];
		$ls_tipafeiva=$_POST["hidtipafeiva"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_subtotal,$li_cargos,$li_total,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$li_totrowcuentas;
		global $li_totrowservicios,$li_totrowconceptos,$li_totrowcuentascargo;	
			
		for($li_i=1;($li_i<$li_totrowbienes);$li_i++)
		{
			$ls_codart=$_POST["txtcodart".$li_i];
			$ls_denart=$_POST["txtdenart".$li_i];
			$li_canart=$_POST["txtcanart".$li_i];
			$ls_denunimed=$_POST["txtdenunimed".$li_i];
			$ls_unidad=$_POST["cmbunidad".$li_i];
			$li_preart=$_POST["txtpreart".$li_i];
			$li_subtotart=$_POST["txtsubtotart".$li_i];
			$li_carart=$_POST["txtcarart".$li_i];
			$li_totart=$_POST["txttotart".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$ls_unidadfisica=$_POST["txtunidad".$li_i];			
			$as_parametros=$as_parametros."&txtcodart".$li_i."=".$ls_codart."&txtdenart".$li_i."=".$ls_denart."".
					   					  "&txtcanart".$li_i."=".$li_canart."&cmbunidad".$li_i."=".$ls_unidad."".
										  "&txtpreart".$li_i."=".$li_preart."&txtsubtotart".$li_i."=".$li_subtotart."".
										  "&txtcarart".$li_i."=".$li_carart."&txttotart".$li_i."=".$li_totart."&txtdenunimed".$li_i."=".$ls_denunimed."".
										  "&txtspgcuenta".$li_i."=".$ls_spgcuenta."&txtunidad".$li_i."=".$ls_unidadfisica."";
		}
		$as_parametros=$as_parametros."&totalbienes=".$li_totrowbienes."";
		for($li_i=1;($li_i<$li_totrowservicios);$li_i++)
		{
			$ls_codser=$_POST["txtcodser".$li_i];
			$ls_denser=$_POST["txtdenser".$li_i];
			$ls_denunimed=$_POST["txtdenunimed".$li_i];
			$li_canser=$_POST["txtcanser".$li_i];
			$li_preser=$_POST["txtpreser".$li_i];
			$li_subtotser=$_POST["txtsubtotser".$li_i];
			$li_carser=$_POST["txtcarser".$li_i];
			$li_totser=$_POST["txttotser".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$as_parametros=$as_parametros."&txtcodser".$li_i."=".$ls_codser."&txtdenser".$li_i."=".$ls_denser."".
					  					  "&txtcanser".$li_i."=".$li_canser."&txtpreser".$li_i."=".$li_preser."".
					  					  "&txtsubtotser".$li_i."=".$li_subtotser."&txtcarser".$li_i."=".$li_carser."".
					   					  "&txttotser".$li_i."=".$li_totser."&txtspgcuenta".$li_i."=".$ls_spgcuenta."".
										  "&txtdenunimed".$li_i."=".$ls_denunimed."";
		}
		$as_parametros=$as_parametros."&totalservicios=".$li_totrowservicios."";
		for($li_i=1;($li_i<$li_totrowconceptos);$li_i++)
		{
			$ls_codcon=$_POST["txtcodcon".$li_i];
			$ls_dencon=$_POST["txtdencon".$li_i];
			$li_cancon=$_POST["txtcancon".$li_i];
			$li_precon=$_POST["txtprecon".$li_i];
			$li_subtotcon=$_POST["txtsubtotcon".$li_i];
			$li_carcon=$_POST["txtcarcon".$li_i];
			$li_totcon=$_POST["txttotcon".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$as_parametros=$as_parametros."&txtcodcon".$li_i."=".$ls_codcon."&txtdencon".$li_i."=".$ls_dencon."".
					  					  "&txtcancon".$li_i."=".$li_cancon."&txtprecon".$li_i."=".$li_precon."".
					  					  "&txtsubtotcon".$li_i."=".$li_subtotcon."&txtcarcon".$li_i."=".$li_carcon."".
										  "&txttotcon".$li_i."=".$li_totcon."&txtspgcuenta".$li_i."=".$ls_spgcuenta;
		}
		$as_parametros=$as_parametros."&totalconceptos=".$li_totrowconceptos."";
		for($li_i=1;($li_i<=$li_totrowcargos);$li_i++)
		{
			$ls_codart=$_POST["txtcodservic".$li_i];
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$ls_dencar=$_POST["txtdencar".$li_i];
			$li_bascar=$_POST["txtbascar".$li_i];
			$li_moncar=$_POST["txtmoncar".$li_i];
			$li_subcargo=$_POST["txtsubcargo".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$ls_cuentacargo=$_POST["cuentacargo".$li_i];			
			$as_parametros=$as_parametros."&txtcodservic".$li_i."=".$ls_codart."&txtcodcar".$li_i."=".$ls_codcar.
					   					  "&txtdencar".$li_i."=".$ls_dencar."&txtbascar".$li_i."=".$li_bascar.
					   					  "&txtmoncar".$li_i."=".$li_moncar."&txtsubcargo".$li_i."=".$li_subcargo.
					  					  "&cuentacargo".$li_i."=".$ls_cuentacargo."&formulacargo".$li_i."=".$ls_formulacargo;
		}
		$as_parametros=$as_parametros."&totalcargos=".$li_totrowcargos;
		for($li_i=1;($li_i<$li_totrowcuentas);$li_i++)
		{
			$ls_codpro=$_POST["txtcodprogas".$li_i];
			$ls_cuenta=$_POST["txtcuentagas".$li_i];
			$li_moncue=$_POST["txtmoncuegas".$li_i];
			$as_parametros=$as_parametros."&txtcodprogas".$li_i."=".$ls_codpro."&txtcuentagas".$li_i."=".$ls_cuenta.
					   "&txtmoncuegas".$li_i."=".$li_moncue;
		}
		$as_parametros=$as_parametros."&totalcuentas=".$li_totrowcuentas;
		for($li_i=1;($li_i<$li_totrowcuentascargo);$li_i++)
		{
			$ls_codcargo=$_POST["txtcodcargo".$li_i];
			$ls_codpro=$_POST["txtcodprocar".$li_i];
			$ls_cuenta=$_POST["txtcuentacar".$li_i];
			$li_moncue=$_POST["txtmoncuecar".$li_i];
			$as_parametros=$as_parametros."&txtcodcargo".$li_i."=".$ls_codcargo."&txtcodprocar".$li_i."=".$ls_codpro.
						   "&txtcuentacar".$li_i."=".$ls_cuenta."&txtmoncuecar".$li_i."=".$li_moncue;
		}
		$as_parametros=$as_parametros."&totalcuentascargo=".$li_totrowcuentascargo;
		$as_parametros=$as_parametros."&subtotal=".$li_subtotal."&cargos=".$li_cargos."&total=".$li_total;
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
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Registro de Solicitud de ejecuci&oacute;n Presupuestaria</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/sep.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:9px;
	top:151px;
	width:214px;
	height:28px;
	z-index:1;
}
-->
</style>
</head>
<body>
<?php 
	require_once("class_folder/tepuy_sep_c_solicitud.php");
	$io_sep=new tepuy_sep_c_solicitud("../");
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
			$io_keygen= new tepuy_c_generar_consecutivo();
			$ls_numsol= $io_keygen->uf_generar_numero_nuevo("SEP","sep_solicitud","numsol","SEPSPC",15,"","","");
			if($ls_numsol==false)
			{
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";
			}
			unset($io_keygen);
			break;
			
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_sep->uf_guardar($ls_existe,$ld_fecregsol,&$ls_numsol,$ls_coduniadm,$ls_codfuefin,$ls_tipodestino,
										   $ls_codprovben,$ls_consol,$ls_codtipsol,$li_subtotal,$li_cargos,$li_total,
										   $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
										   $li_totrowbienes,$li_totrowcargos,$li_totrowcuentas,$li_totrowcuentascargo,
										   $li_totrowservicios,$li_totrowconceptos,$la_seguridad,&$ls_estsol,$la_permisos["administrador"],0);
			uf_load_data(&$ls_parametros);
			switch($ls_estsol)
			{
				case "R": 
					$ls_estatus="REGISTRO";
					break;
				case "E": 
					$ls_estatus="EMITIDA";
					break;
			}
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
			break;
			
		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_sep->uf_delete_solicitud($ls_numsol,$la_seguridad);
			if(!$lb_valido)
			{
				uf_load_data(&$ls_parametros);
				switch($ls_estsol)
				{
					case "R": 
						$ls_estatus="REGISTRO";
						break;
					case "E": 
						$ls_estatus="EMITIDA";
						break;
				}
				$ls_existe="TRUE";
			}
			else
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Solicitud 
            de Ejecuci&oacute;n Presupuestaria</td>
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
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="25"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="206"><div align="center"></div></td>
    <td class="toolbar" width="347">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="" method="post" name="formulario" id="formulario">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sep->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='../tepuy_menu.php'");
	unset($io_fun_sep);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136"><p>&nbsp;</p>
      <table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="4" class="titulo-ventana">Registro de Solicitud de Ejecuci&oacute;n 
              Presupuestaria              
              <input name="hidtipafeiva" type="hidden" id="hidtipafeiva" value="<?php echo $ls_tipafeiva ?>"></td>
          </tr>
          <tr style="display:none">
            <td height="22"><div align="right">Formato de Impresion en </div></td>
            <td><div align="left">
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected>Bs.</option>
                <option value="1">Bs.F.</option>
              </select>
            </div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td width="203" height="22"><div align="right">Estatus</div></td>
            <td width="354">
                <input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="20" readonly>            </td>
            <td width="89"><div align="right">Fecha</div></td>
            <td width="202"><input name="txtfecregsol" type="text" id="txtfecregsol" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecregsol;?>" size="15"  datepicker="true"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Solicitud</div></td>
            <td height="22" colspan="3"><input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol;?>" size="20" maxlength="15" style="text-align:center" onBlur="javascript: ue_rellenarcampo(this,15);" <?php if(($la_permisos["administrador"]!=1)||($ls_operacion!="NUEVO")){print "read";} ?>></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Tipo</div></td>
            <td height="22" colspan="3"> 
              <?php $io_sep->uf_load_tiposolicitud($ls_codtipsol);?>            </td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Unidad Ejecutora</div></td>
            <td height="22" colspan="3"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm;?>" size="13" maxlength="10" readonly> 
              <a href="javascript: ue_catalogo('tepuy_sep_cat_unidad_ejecutora.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
              <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm;?>" size="95" readonly></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Fuente de Financiamiento</div></td>
            <td height="22" colspan="3"><input name="txtcodfuefin" type="text" id="txtcodfuefin" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="5" maxlength="2" readonly> 
              <a href="javascript: ue_catalogo('tepuy_sep_cat_fuente.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> 
              <input name="txtdenfuefin" type="text" class="sin-borde" id="txtdenfuefin" value="<?php print $ls_denfuefin;?>" size="103" readonly></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Destino</div></td>
            <td height="22" colspan="3"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
                <option value="-" selected>-- Seleccione Uno --</option>
                <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
                <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
              </select> <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly>
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="70" maxlength="30" readonly>            </td>
          </tr>
          <tr> 
            <td height="24"> <div align="right">Concepto</div></td>
            <td height="22" colspan="3" rowspan="2"><textarea name="txtconsol" cols="90" rows="3" id="txtconsol" onKeyUp="ue_validarcomillas(this);" ><?php print $ls_consol;?></textarea></td>
          </tr>
          <tr> 
            <td height="11">&nbsp;</td>
          </tr>
          <tr>
		  <?php $lb_valido_formato=$io_sep->uf_select_config(); 
		   if($lb_valido_formato)
		   {
		  ?>
            <td height="22"><div align="right">Tipo de Formato de Impresion</div></td>
            <td height="22" colspan="3">
			<select name="cmbtipfor" id="cmbtipfor">
              <option value="-" selected>-- Seleccione Uno --</option>
              <option value="F1" <?php if($ls_tipoformato=='F1'){ print 'selected';} ?>>FORMATO 1</option>
              <option value="F2" <?php if($ls_tipoformato=='F2'){ print 'selected';} ?>>FORMATO 2</option>
              <option value="F3" <?php if($ls_tipoformato=='F3'){ print 'selected';} ?>>FORMATO 3</option>
            </select></td>
          </tr>   
		  <?php  
		   }
		  ?>
	    </table>
        <table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center"><div id="bienesservicios"></div></td>
          </tr>
        </table>
        <p> 
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>">
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2;?>">
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>">
          <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
          <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
          <input name="totrowbienes"  type="hidden" id="totrowbienes"     value="<?php print $li_totrowbienes;?>">
          <input name="totrowservicios"  type="hidden" id="totrowservicios"  value="<?php print $li_totrowservicios;?>">
          <input name="totrowconceptos"  type="hidden" id="totrowconceptos"  value="<?php print $li_totrowconceptos;?>">
          <input name="totrowcargos"  type="hidden" id="totrowcargos"  value="<?php print $li_totrowcargos;?>">
          <input name="totrowcuentas" type="hidden" id="totrowcuentas" value="<?php print $li_totrowcuentas;?>">
          <input name="totrowcuentascargo" type="hidden" id="totrowcuentascargo" value="<?php print $li_totrowcuentascargo;?>">
          <input name="parametros"    type="hidden" id="parametros"    value="<?php print $ls_parametros;?>">
          <input name="txtestsol"     type="hidden" id="txtestsol"     value="<?php print $ls_estsol;?>">
          <input name="txtestapro"    type="hidden" id="txtestapro"    value="<?php print $ls_estapro; ?>">
          <input name="txttipsol"     type="hidden" id="txttipsol"     value="<?php print $ls_codtipsol; ?>">
          <input name="txtfecha"      type="hidden" id="txtfecha"      value="<?php print $ld_fecregsol; ?>">
          <input name="formato"       type="hidden" id="formato"       value="<?php print $ls_reporte; ?>">
          <input name="hidtipfor"     type="hidden" id="hidtipfor"     value="<?php print $lb_valido_formato; ?>">
        </p></td>
    </tr>
</table>
</form>   
<?php
	$io_sep->uf_destructor();
	unset($io_sep);
?>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_nuevo()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="tepuy_sep_p_solicitud.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		estapro=f.txtestapro.value;
		if(estapro=="1")
		{
			valido=false;
			alert("La solicitud esta aprobada no la puede modificar.");
		}
		// Obtenemos el total de filas de los Conceptos
		total=ue_calcular_total_fila_local("txtcodcon");
		f.totrowconceptos.value=total;
		// Obtenemos el total de filas de los Servicios
		total=ue_calcular_total_fila_local("txtcodser");
		f.totrowservicios.value=total;
		// Obtenemos el total de filas de los bienes
		total=ue_calcular_total_fila_local("txtcodart");
		f.totrowbienes.value=total;
		// Obtenemos el total de filas de los cargos
		total=ue_calcular_total_fila_local("txtcodservic");
		f.totrowcargos.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentagas");
		f.totrowcuentas.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentacar");
		f.totrowcuentascargo.value=total;
		tiposolicitud=f.cmbcodtipsol.value;
		codtipsol=tiposolicitud.substr(0,2);
		tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
		operacion=tiposolicitud.substr(5,1); // Si es de precompromiso ó compromiso
		numero=ue_validarvacio(f.txtnumsol.value);
		unidad=ue_validarvacio(f.txtcoduniadm.value);
		fecha=ue_validarvacio(f.txtfecregsol.value);
		codigo=ue_validarvacio(f.txtcodigo.value);
		concepto=ue_validarvacio(f.txtconsol.value);
		tipodestino=ue_validarvacio(f.cmbtipdes.value);
		totalgeneral=0;
		totalcuenta=0;
		totalcuentacargo=0;
		if(valido)
		{
			valido=ue_validarcampo(numero,"El Número de Solicitud no puede estar vacio.",f.txtnumsol);
		}
		if(valido)
		{
			valido=ue_validarcampo(unidad,"La Unidad Ejecutora no puede estar vacia.",f.txtcoduniadm);
		}
		if(valido)
		{
			valido=ue_validarcampo(fecha,"La Fecha no puede estar vacia.",f.txtfecregsol);
		}
		if(valido)
		{
			if(operacion=="O")// Compromiso
			{
				if(codigo=="----------")
				{
					codigo="";
				}
				valido=ue_validarcampo(codigo,"SEP de compromiso. Debe seleccionar un Proveedor ó Beneficiario.",f.txtcodigo);
			}
		}
		if(valido)
		{
			if(tipodestino=="B")
			{
				valido=ue_validarcampo(codigo,"Debe seleccionar un Beneficiario.",f.txtcodigo);
			}
			if(tipodestino=="P")
			{
				valido=ue_validarcampo(codigo,"Debe seleccionar un Proveedor.",f.txtcodigo);
			}
		}
		if(valido)
		{
			valido=ue_validarcampo(concepto,"El concepto no puede estar vacio.",f.txtconsol);
		}
		if(valido)
		{
			if(tipo=="B") // Si la SEP es de Bienes
			{
				rowbienes=f.totrowbienes.value;
				if(rowbienes>1)
				{
					for(j=1;(j<rowbienes)&&(valido);j++)
					{
						codart=eval("document.formulario.txtcodart"+j+".value");
						canart=eval("document.formulario.txtcanart"+j+".value");
						preart=eval("document.formulario.txtpreart"+j+".value");
						totart=eval("document.formulario.txttotart"+j+".value");
						canart=ue_formato_calculo(canart);
						preart=ue_formato_calculo(preart);
						totart=ue_formato_calculo(totart);
						totalgeneral=eval(totalgeneral+"+"+totart);
						totalgeneral=redondear(totalgeneral,2);
						if((canart<=0)||(preart<=0))
						{
							alert("El Precio y La Cantidad del Bien "+codart+" Deben ser mayor que Cero.")
							valido=false;
						}
					}
				}
				else
				{
					alert("Debe Tener al menos un Bien Seleccionado.");
					valido=false;
				}
			}
			if(tipo=="S") // Si la SEP es de Servicios
			{
				rowservicios=f.totrowservicios.value;
				if(rowservicios>1)
				{
					for(j=1;(j<rowservicios)&&(valido);j++)
					{
						codser=eval("document.formulario.txtcodser"+j+".value");
						canser=eval("document.formulario.txtcanser"+j+".value");
						preser=eval("document.formulario.txtpreser"+j+".value");
						totser=eval("document.formulario.txttotser"+j+".value");
						canser=ue_formato_calculo(canser);
						preser=ue_formato_calculo(preser);
						totser=ue_formato_calculo(totser);
						totalgeneral=eval(totalgeneral+"+"+totser);
						totalgeneral=redondear(totalgeneral,2);
						if((canser<=0)||(preser<=0))
						{
							alert("El Precio y La Cantidad del Servicio "+codser+" Deben ser mayor que Cero.")
							valido=false;
						}
					}
				}
				else
				{
					alert("Debe Tener al menos un Servicio Seleccionado.");
					valido=false;
				}
			}
			if(tipo=="O") // Si la SEP es de Conceptos
			{
				rowconceptos=f.totrowconceptos.value;
				if(rowconceptos>1)
				{
					for(j=1;(j<rowconceptos)&&(valido);j++)
					{
						codcon=eval("document.formulario.txtcodcon"+j+".value");
						cancon=eval("document.formulario.txtcancon"+j+".value");
						precon=eval("document.formulario.txtprecon"+j+".value");
						totcon=eval("document.formulario.txttotcon"+j+".value");
						cancon=ue_formato_calculo(cancon);
						precon=ue_formato_calculo(precon);
						totcon=ue_formato_calculo(totcon);
						totalgeneral=eval(totalgeneral+"+"+totcon);
						totalgeneral=redondear(totalgeneral,2);
						if((cancon<=0)||(precon<=0))
						{
							alert("El Monto y La Cantidad del Concepto "+codcon+" Deben ser mayor que Cero.")
							valido=false;
						}
					}
				}
				else
				{
					alert("Debe Tener al menos un Concepto Seleccionado.");
					valido=false;
				}
			}
		}
		if(valido)
		{
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				codcar=eval("document.formulario.txtcodcar"+j+".value");
				moncar=eval("document.formulario.txtmoncar"+j+".value");
				moncar=ue_formato_calculo(moncar);
				if(moncar<=0)
				{
					alert("El Monto del Cargo "+codcar+" del item "+codservic+" Debe ser mayor que Cero.");
					valido=false;
				}
			}
		}
		if(valido)
		{
			total=ue_calcular_total_fila_local("txtcuentagas");
			f.totrowcuentas.value=total;
			rowcuentas=document.formulario.totrowcuentas.value;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				codpro=eval("document.formulario.txtcodprogas"+j+".value");
				cuenta=eval("document.formulario.txtcuentagas"+j+".value");
				moncue=eval("document.formulario.txtmoncuegas"+j+".value");
				moncue=ue_formato_calculo(moncue);
				totalcuenta=eval(totalcuenta+"+"+moncue);
				totalcuenta=redondear(totalcuenta,2);				
				if(moncue<=0)
				{
					alert("El Monto del la Cuenta Presupuestaria "+cuenta+" Debe ser mayor que Cero.");
					valido=false;
				}
				if(codpro=="")
				{
					alert("La Cuenta Presupuestaria "+cuenta+", Debe estar asignada a una Estructura.");
					valido=false;
				}
			}
		}
		ls_tipafeiva = f.hidtipafeiva.value;
		if(valido && ls_tipafeiva=='P')
		{
			total=ue_calcular_total_fila_local("txtcuentacar");
			f.totrowcuentascargo.value=total;
			rowcuentas=document.formulario.totrowcuentascargo.value;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				codpro=eval("document.formulario.txtcodprocar"+j+".value");
				cuenta=eval("document.formulario.txtcuentacar"+j+".value");
				moncue=eval("document.formulario.txtmoncuecar"+j+".value");
				moncue=ue_formato_calculo(moncue);
				totalcuentacargo=eval(totalcuentacargo+"+"+moncue);
				totalcuentacargo=redondear(totalcuentacargo,2);
				if(moncue<=0)
				{
					alert("El Monto del la Cuenta Presupuestaria del Cargo "+cuenta+" Debe ser mayor que Cero.");
					valido=false;
				}
				if(codpro=="")
				{
					alert("La Cuenta Presupuestaria del "+cuenta+", Debe estar asignada a una Estructura.");
					valido=false;
				}
			}
		}
		if(valido)
		{
			subtotal=f.txtsubtotal.value;
			subtotal=ue_formato_calculo(subtotal);
			cargos=f.txtcargos.value;
			cargos=ue_formato_calculo(cargos);
			
			if(totalcuenta!=subtotal)
			{
				alert("El Total de las Cuentas Presupuestarias es distinto al Subtotal.");
				valido=false;
			}
			if(totalcuentacargo!=cargos && ls_tipafeiva=='P')
			{
				alert("El Total de las Cuentas Presupuestarias de los cargos es distinto a los Otros Crèditos.");
				valido=false;
			}
			if (ls_tipafeiva=='P')
			{
			total=eval(totalcuenta+"+"+totalcuentacargo);
			total=redondear(total,2);
				if(totalgeneral!=total)
				{
					alert("El Total de las Cuentas Presupuestarias es distinto al total General.");
					valido=false;
				}
		    }
		}
		if(valido)
		{
			f.operacion.value="GUARDAR";
			f.action="tepuy_sep_p_solicitud.php";
			f.submit();		
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
}

function ue_eliminar()
{
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			estapro=f.txtestapro.value;
			if(estapro=="1")
			{
				alert("La solicitud esta aprobada no la puede eliminar.");
			}
			else
			{
				// Obtenemos el total de filas de los bienes
				total=ue_calcular_total_fila_local("txtcodart");
				f.totrowbienes.value=total;
				// Obtenemos el total de filas de los cargos
				total=ue_calcular_total_fila_local("txtcodservic");
				f.totrowcargos.value=total;
				// Obtenemos el total de filas de las cuentas
				total=ue_calcular_total_fila_local("txtcuenta");
				f.totrowcuentas.value=total;
				numsol = ue_validarvacio(f.txtnumsol.value);
				if (numsol!="")
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="tepuy_sep_p_solicitud.php";
						f.submit();
					}
				}
				else
				{
					alert("Debe buscar el registro a eliminar.");
				}
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

function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_sep_cat_solicitud.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=no");
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
	lb_existe=f.existe.value;
	if(li_imprimir==1)
	{
		if(lb_existe=="TRUE")
		{
			numsol=f.txtnumsol.value;
			formato=f.formato.value;
			tipoformato=f.cmbbsf.value;
			lb_valido=f.hidtipfor.value;
			formato="tepuy_sep_rfs_solicitudes_barinas.php";
			if(lb_valido)
			{
				li_tipfor=f.cmbtipfor.value;
				if(li_tipfor=="F1")
				{
			       window.open("reportes/"+formato+"?numsol="+numsol+"&tipoformato="+tipoformato+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				if(li_tipfor=="F2")
				{
			       formato_2="tepuy_sep_rfs_solicitudes_foncrei_formato2.php";
				   window.open("reportes/"+formato_2+"?numsol="+numsol+"&tipoformato="+tipoformato+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				if(li_tipfor=="F3")
				{
			       formato_3="tepuy_sep_rfs_solicitudes_foncrei_formato3.php";
				   window.open("reportes/"+formato_3+"?numsol="+numsol+"&tipoformato="+tipoformato+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				if(li_tipfor=="-")
				{
			       alert(" Por Favor Selecione un Formato");  
				}
			}
			else
			{
			   window.open("reportes/"+formato+"?numsol="+numsol+"&tipoformato="+tipoformato+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
		}
		else
		{
			alert("Debe existir un documento a imprimir");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_catalogo(ls_catalogo)
{
	if(ls_catalogo=='tepuy_sep_cat_unidad_ejecutora.php')
	{
		f=document.formulario;
		lb_existe=f.existe.value;
		if(lb_existe=="TRUE")
		{
			alert("La Unidad Ejecutora no se puede modificar.");		
		}
		else
		{
			if((f.totrowbienes.value>1)||(f.totrowservicios.value>1)||(f.totrowconceptos.value>1)||
			   (f.totrowcargos.value>0)||(f.totrowcuentas.value>1)||(f.totrowcuentascargo.value>1))
			{
				alert("La Unidad Ejecutora no se puede modificar, ya existen movimientos en la solicitud.");		
			}
			else
			{
				// abre el catalogo que se paso por parametros
				window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
			}
		}
	}
	else
	{
		// abre el catalogo que se paso por parametros
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_cargargrid()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Cargamos las variables para pasarlas al AJAX
		tiposolicitud=f.cmbcodtipsol.value;
		f.totrowbienes.value=1;
		f.totrowservicios.value=1;
		f.totrowconceptos.value=1;
		f.totrowcargos.value=0;

		f.totrowcuentas.value=1;
		f.totrowcuentascargo.value=1;
		f.txtcodigo.value="";
		f.txtnombre.value="";
		f.cmbtipdes.value="-";
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('bienesservicios');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("tipo="+tiposolicitud+"&totalbienes=1&totalservicios=1&&totalconceptos=1&totalcargos=0"+
				  "&totalcuentas=1&totalcuentascargo=1&proceso=LIMPIAR");
	}
}

function ue_cambiardestino()
{
	f=document.formulario;
	// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
	// dependiendo de esa información
	tiposolicitud=f.cmbcodtipsol.value;
	codtipsol=tiposolicitud.substr(0,2);
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
	operacion=tiposolicitud.substr(5,1); // Si es de precompromiso ó compromiso
	if(operacion=="O")// Compromiso
	{
		f.txtcodigo.value="";
		f.txtnombre.value="";
		tipdes=ue_validarvacio(f.cmbtipdes.value);
		if(tipdes!="-")
		{
			if(tipdes=="P")
			{
				window.open("tepuy_sep_cat_proveedor.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
			else
			{
				window.open("tepuy_sep_cat_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}	
		}
	}
	else
	{
		f.cmbtipdes.value="-";
		alert("La Solicitud de Ejecución presupuestaria debe ser de Tipo compromiso.");
	}
}

function ue_catalogoservicios()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_sep_cat_servicios.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Ejecutora");
		}
	}
}

function ue_catalogobienes()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_sep_cat_bienes.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Ejecutora");
		}
	}
}

function ue_catalogoconceptos()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_sep_cat_conceptos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Ejecutora");
		}
	}
}

function ue_catalogo_cuentas_spg()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Se carga el catalogo de Cuentas Presupuestarias, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_sep_cat_cuentasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Ejecutora");
		}
	}
}

function ue_catalogo_cuentas_cargos()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Se carga el catalogo de Cuentas Presupuestarias, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_sep_cat_cuentascargo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Ejecutora");
		}
	}
}

function ue_procesar_monto(tipo,fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		// Obtenemos el total de filas de los Conceptos
		total=ue_calcular_total_fila_local("txtcodcon");
		f.totrowconceptos.value=total;
		// Obtenemos el total de filas de los servicios
		total=ue_calcular_total_fila_local("txtcodser");
		f.totrowservicios.value=total;
		// Obtenemos el total de filas de los bienes
		total=ue_calcular_total_fila_local("txtcodart");
		f.totrowbienes.value=total;
		// Obtenemos el total de filas de los cargos
		total=ue_calcular_total_fila_local("txtcodservic");
		f.totrowcargos.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentagas");
		f.totrowcuentas.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentacar");
		f.totrowcuentascargo.value=total;
		if(tipo=="B")
		{
			// Cargamos los valores de la fila indicada
			codart=eval("f.txtcodart"+fila+".value");
			canart=eval("f.txtcanart"+fila+".value");
			preart=eval("f.txtpreart"+fila+".value");
			unidad=eval("f.cmbunidad"+fila+".value");
			canunidad=eval("f.txtunidad"+fila+".value");
			spgcuenta=eval("f.txtspgcuenta"+fila+".value");
			canart=ue_formato_calculo(canart);
			preart=ue_formato_calculo(preart);
			// Si es una fila que tiene Artículos
			if(codart!="")
			{
				// Si la cantidad de artículos ó el precio es mayor que cero calculamos
				if((canart>0)||(preart>0))
				{
					if(unidad=="M")
					{
						// si es al mayor multiplicamos la cantidad tipeada por la cantidad de la unidad
						canart=eval(canart+"*"+canunidad);
					}
					monnet=canart*preart;
					monnet=redondear(monnet,2);
					totalarticulo=0;
					eval("f.txtsubtotart"+fila+".value='"+uf_convertir(monnet)+"'");
					totalcargo=0;
					// Actualizamos los créditos de ese artículo si tiene
					ue_actualizar_creditos(codart,monnet);
					monnet=redondear(monnet,2);
					totalcargo=redondear(totalcargo,2);
					totalarticulo=eval(monnet+"+"+totalcargo);
					totalarticulo=redondear(totalarticulo,2);
					eval("f.txtcarart"+fila+".value='"+uf_convertir(totalcargo)+"'");
					eval("f.txttotart"+fila+".value='"+uf_convertir(totalarticulo)+"'");
					// Actualizamos las cuentas presupuestarias
					ue_actualizar_cuentas(tipo,spgcuenta);
					// Actualizamos los totales de la solicitud
					ue_actualizar_totales(tipo);
				}
			}
		}
		if(tipo=="S")
		{
			// Cargamos los valores de la fila indicada
			codser=eval("f.txtcodser"+fila+".value");
			canser=eval("f.txtcanser"+fila+".value");
			preser=eval("f.txtpreser"+fila+".value");
			canser=ue_formato_calculo(canser);
			preser=ue_formato_calculo(preser);
			spgcuenta=eval("f.txtspgcuenta"+fila+".value");
			// Si es una fila que tiene Artículos
			if(codser!="")
			{
				// Si la cantidad de artículos ó el precio es mayor que cero calculamos
				if((canser>0)||(preser>0))
				{
					monnet=canser*preser;
					monnet=redondear(monnet,2);
					totalservicio=0;
					eval("f.txtsubtotser"+fila+".value='"+uf_convertir(monnet)+"'");
					totalcargo=0;
					// Actualizamos los créditos de ese artículo si tiene
					ue_actualizar_creditos(codser,monnet);
					monnet=redondear(monnet,2);
					totalcargo=redondear(totalcargo,2);
					totalservicio=eval(monnet+"+"+totalcargo);
					totalservicio=redondear(totalservicio,2);
					eval("f.txtcarser"+fila+".value='"+uf_convertir(totalcargo)+"'");
					eval("f.txttotser"+fila+".value='"+uf_convertir(totalservicio)+"'");
					// Actualizamos las cuentas presupuestarias
					ue_actualizar_cuentas(tipo,spgcuenta);
					// Actualizamos los totales de la solicitud
					ue_actualizar_totales(tipo);
				}
			}
		}
		if(tipo=="O")
		{
			// Cargamos los valores de la fila indicada
			codcon=eval("f.txtcodcon"+fila+".value");
			cancon=eval("f.txtcancon"+fila+".value");
			precon=eval("f.txtprecon"+fila+".value");
			cancon=ue_formato_calculo(cancon);
			precon=ue_formato_calculo(precon);
			spgcuenta=eval("f.txtspgcuenta"+fila+".value");
			// Si es una fila que tiene Artículos
			if(codcon!="")
			{
				// Si la cantidad de artículos ó el precio es mayor que cero calculamos
				if((cancon>0)||(precon>0))
				{
					monnet=cancon*precon;
					monnet=redondear(monnet,2);
					totalconcepto=0;
					eval("f.txtsubtotcon"+fila+".value='"+uf_convertir(monnet)+"'");
					totalcargo=0;
					// Actualizamos los créditos de ese artículo si tiene
					ue_actualizar_creditos(codcon,monnet);
					monnet=redondear(monnet,2);
					totalcargo=redondear(totalcargo,2);
					totalconcepto=eval(monnet+"+"+totalcargo);
					totalconcepto=redondear(totalconcepto,2);
					eval("f.txtcarcon"+fila+".value='"+uf_convertir(totalcargo)+"'");
					eval("f.txttotcon"+fila+".value='"+uf_convertir(totalconcepto)+"'");
					// Actualizamos las cuentas presupuestarias
					ue_actualizar_cuentas(tipo,spgcuenta);
					// Actualizamos los totales de la solicitud
					ue_actualizar_totales(tipo);
				}
			}
		}
	}
}

function ue_actualizar_creditos(codigo,monto)
{
	f=document.formulario;
	rowcargo=f.totrowcargos.value;
	for(fila_cargo=1;(fila_cargo<=rowcargo);fila_cargo++)
	{
		codartcargo=eval("f.txtcodservic"+fila_cargo+".value");
		// Si el codigo del artículo que se esta actualizando es igual al actual
		if(codartcargo==codigo)
		{
			cuentacargo=eval("f.cuentacargo"+fila_cargo+".value");
			formula=eval("f.formulacargo"+fila_cargo+".value");
			formula=formula.replace("$LD_MONTO",monto);
			cargo=eval(formula);
			cargo=redondear(cargo,2);
			totalcargo=eval(totalcargo+"+"+cargo);
			subtotalcargo=eval(monto+"+"+cargo);
			subtotalcargo=redondear(subtotalcargo,2);
			eval("f.txtbascar"+fila_cargo+".value='"+uf_convertir(monto)+"'");
			eval("f.txtmoncar"+fila_cargo+".value='"+uf_convertir(cargo)+"'");
			eval("f.txtsubcargo"+fila_cargo+".value='"+uf_convertir(subtotalcargo)+"'");
		}
	}
}

function ue_actualizar_cuentas(tipo,spgcuentaact)
{
	f=document.formulario;
	rowcuentas=f.totrowcuentas.value;
	for(fila_cuenta=1;(fila_cuenta<rowcuentas);fila_cuenta++)
	{
		cuenta=eval("f.txtcuentagas"+fila_cuenta+".value");
		moncueact=eval("f.txtmoncuegas"+fila_cuenta+".value");
		moncue=0;
		lb_entro=false;
		if(tipo=="B")
		{
			// Recorremos los Bienes para colocar el total de las cuentas
			rowbienes=f.totrowbienes.value;
			for(fila_bienes=1;fila_bienes<rowbienes;fila_bienes++)
			{
				spgcuenta=eval("f.txtspgcuenta"+fila_bienes+".value");
				if((cuenta==spgcuenta)&&(cuenta==spgcuentaact))
				{
					montobienes=eval("f.txtsubtotart"+fila_bienes+".value");
					montobienes=ue_formato_calculo(montobienes);
					moncue=eval(moncue+"+"+montobienes);
					lb_entro=true;
				}
			}
		}
		if(tipo=="S")
		{
			// Recorremos los Bienes para colocar el total de las cuentas
			totrowservicios=f.totrowservicios.value;
			for(fila_servicios=1;fila_servicios<totrowservicios;fila_servicios++)
			{
				spgcuenta=eval("f.txtspgcuenta"+fila_servicios+".value");
				if((cuenta==spgcuenta)&&(cuenta==spgcuentaact))
				{
					montoservicios=eval("f.txtsubtotser"+fila_servicios+".value");
					montoservicios=ue_formato_calculo(montoservicios);
					moncue=eval(moncue+"+"+montoservicios);
					lb_entro=true;
				}
			}
		}
		if(tipo=="O")
		{
			// Recorremos los Bienes para colocar el total de las cuentas
			totrowconceptos=f.totrowconceptos.value;
			for(fila_conceptos=1;fila_conceptos<totrowconceptos;fila_conceptos++)
			{
				spgcuenta=eval("f.txtspgcuenta"+fila_conceptos+".value");
				if((cuenta==spgcuenta)&&(cuenta==spgcuentaact))
				{
					montoconceptos=eval("f.txtsubtotcon"+fila_conceptos+".value");
					montoconceptos=ue_formato_calculo(montoconceptos);
					moncue=eval(moncue+"+"+montoconceptos);
					lb_entro=true;				
				}
			}
		}
		if(lb_entro)
		{
			eval("f.txtmoncuegas"+fila_cuenta+".value='"+uf_convertir(moncue)+"'");
		}
		else
		{
			eval("f.txtmoncuegas"+fila_cuenta+".value='"+moncueact+"'");
		}
	}
	rowcuentas=f.totrowcuentascargo.value;
	for(fila_cuenta=1;(fila_cuenta<rowcuentas);fila_cuenta++)
	{
		cuenta=eval("f.txtcuentacar"+fila_cuenta+".value");
		cargo=eval("f.txtcodcargo"+fila_cuenta+".value");
		moncueact=eval("f.txtmoncuecar"+fila_cuenta+".value");
		moncue=0;
		lb_entro=false;
		// Recorremos los Cargos para colocar el total de las cuentas
		rowcargos=f.totrowcargos.value;
		for(fila_cargos=1;fila_cargos<=rowcargos;fila_cargos++)
		{
			spgcuenta=eval("f.cuentacargo"+fila_cargos+".value");
			codcar=eval("f.txtcodcar"+fila_cargos+".value");
			if((cuenta==spgcuenta)&&(cargo==codcar))
			{
				montocargo=eval("f.txtmoncar"+fila_cargos+".value");
				montocargo=ue_formato_calculo(montocargo);
				moncue=eval(moncue+"+"+montocargo);
			    moncue=redondear(moncue,2);
				lb_entro=true;
			}
		}
		if(lb_entro)
		{
			eval("f.txtmoncuecar"+fila_cuenta+".value='"+uf_convertir(moncue)+"'");
		}
		else
		{
			eval("f.txtmoncuecar"+fila_cuenta+".value='"+moncueact+"'");
		}
	}
}

function ue_actualizar_totales(tipo)
{
	f=document.formulario;
	subtotal=0;
	cargos=0;
	total=0;
	if(tipo=="B")
	{
		rowbienes=f.totrowbienes.value;
		// Recorremos los bienes y sumamos para colocarlo en los totales
		for(fila_bienes=1;fila_bienes<rowbienes;fila_bienes++)
		{
			montobienes=eval("f.txtsubtotart"+fila_bienes+".value");
			montobienes=ue_formato_calculo(montobienes);
			subtotal=eval(subtotal+"+"+montobienes);
			montocargos=eval("f.txtcarart"+fila_bienes+".value");
			montocargos=ue_formato_calculo(montocargos);
			cargos=eval(cargos+"+"+montocargos);
			cargos=redondear(cargos,2);
			montoarticulos=eval("f.txttotart"+fila_bienes+".value");
			montoarticulos=ue_formato_calculo(montoarticulos);
			total=eval(total+"+"+montoarticulos);
		}
	}
	if(tipo=="S")
	{
		rowservicios=f.totrowservicios.value;
		// Recorremos los servicios y sumamos para colocarlo en los totales
		for(fila_servicios=1;fila_servicios<rowservicios;fila_servicios++)
		{
			montoservicios=eval("f.txtsubtotser"+fila_servicios+".value");
			montoservicios=ue_formato_calculo(montoservicios);
			subtotal=eval(subtotal+"+"+montoservicios);
			montocargos=eval("f.txtcarser"+fila_servicios+".value");
			montocargos=ue_formato_calculo(montocargos);
			cargos=eval(cargos+"+"+montocargos);
			cargos=redondear(cargos,2);
			montoservicios=eval("f.txttotser"+fila_servicios+".value");
			montoservicios=ue_formato_calculo(montoservicios);
			total=eval(total+"+"+montoservicios);
		}
	}
	if(tipo=="O")
	{
		rowconceptos=f.totrowconceptos.value;
		// Recorremos los Conceptos y sumamos para colocarlo en los totales
		for(fila_conceptos=1;fila_conceptos<rowconceptos;fila_conceptos++)
		{
			montoconceptos=eval("f.txtsubtotcon"+fila_conceptos+".value");
			montoconceptos=ue_formato_calculo(montoconceptos);
			subtotal=eval(subtotal+"+"+montoconceptos);
			montocargos=eval("f.txtcarcon"+fila_conceptos+".value");
			montocargos=ue_formato_calculo(montocargos);
			cargos=eval(cargos+"+"+montocargos);
			cargos=redondear(cargos,2);
			montoconceptos=eval("f.txttotcon"+fila_conceptos+".value");
			montoconceptos=ue_formato_calculo(montoconceptos);
			total=eval(total+"+"+montoconceptos);
		}
	}
	subtotal=redondear(subtotal,2);
	cargos=redondear(cargos,2);
	total=redondear(total,2);
	f.txtsubtotal.value=uf_convertir(subtotal);
	f.txtcargos.value=uf_convertir(cargos);
	f.txttotal.value=uf_convertir(total);
}

function ue_delete_bienes(fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montobien=0;
			montocargo=0;
			cuentacargo="";
			codigo="";
			cuentabien="";	
			//---------------------------------------------------------------------------------
			// Cargar los Bienes y eliminar el seleccionado
			//---------------------------------------------------------------------------------
			// Obtenemos el total de filas de los bienes
			total=ue_calcular_total_fila_local("txtcodart");
			f.totrowbienes.value=total;
			rowbienes=f.totrowbienes.value;
			li_i=0;
			for(j=1;(j<rowbienes)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					parametros=parametros+"&txtcodart"+li_i+"="+codart+"&txtdenart"+li_i+"="+denart+""+
							   "&txtcanart"+li_i+"="+canart+"&cmbunidad"+li_i+"="+unidad+""+
							   "&txtpreart"+li_i+"="+preart+"&txtsubtotart"+li_i+"="+subtotart+""+
							   "&txtcarart"+li_i+"="+carart+"&txttotart"+li_i+"="+totart+""+
							   "&txtspgcuenta"+li_i+"="+spgcuenta+"&txtunidad"+li_i+"="+unidadfisica+"&txtdenunimed"+li_i+"="+unimed+"";
				}
				else
				{
					codigo=eval("document.formulario.txtcodart"+j+".value");
					cuentabien=eval("document.formulario.txtspgcuenta"+j+".value");
					montobien=eval("document.formulario.txtsubtotart"+j+".value");
					montobien=ue_formato_calculo(montobien);
				}
			}
			li_i=li_i+1;
			parametros=parametros+"&totalbienes="+li_i+"";
			f.totrowbienes.value=li_i;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			li_i=0;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				if(codservic!=codigo)
				{
					li_i=li_i+1;
					codcar=eval("document.formulario.txtcodcar"+j+".value");
					dencar=eval("document.formulario.txtdencar"+j+".value");
					bascar=eval("document.formulario.txtbascar"+j+".value");
					moncar=eval("document.formulario.txtmoncar"+j+".value");
					subcargo=eval("document.formulario.txtsubcargo"+j+".value");
					spgcargo=eval("document.formulario.cuentacargo"+j+".value");
					formulacargo=eval("document.formulario.formulacargo"+j+".value");
					parametros=parametros+"&txtcodservic"+li_i+"="+codservic+"&txtcodcar"+li_i+"="+codcar+
							   "&txtdencar"+li_i+"="+dencar+"&txtbascar"+li_i+"="+bascar+
							   "&txtmoncar"+li_i+"="+moncar+"&txtsubcargo"+li_i+"="+subcargo+
							   "&cuentacargo"+li_i+"="+spgcargo+"&formulacargo"+li_i+"="+formulacargo;
				}
				else
				{
					cuentacargo=eval("document.formulario.cuentacargo"+j+".value");
					codcargo=eval("document.formulario.txtcodcar"+j+".value");
					montocargo=eval("document.formulario.txtmoncar"+j+".value");
					montocargo=ue_formato_calculo(montocargo);
				}
			}
			f.totrowcargos.value=li_i;
			parametros=parametros+"&totalcargos="+li_i;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowbienes=f.totrowbienes.value;
			if(rowbienes>1)
			{
				total=ue_calcular_total_fila_local("txtcuentagas");
				f.totrowcuentas.value=total;
				rowcuentas=document.formulario.totrowcuentas.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cuenta=eval("document.formulario.txtcuentagas"+j+".value");
					codpro=eval("document.formulario.txtcodprogas"+j+".value");
					moncue=eval("document.formulario.txtmoncuegas"+j+".value");
					if(cuenta==cuentabien)
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montobien);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
							   "&txtmoncuegas"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				document.formulario.totrowcuentas.value=totalcuentas;	
				parametros=parametros+"&totalcuentas="+totalcuentas;
			}
			else
			{
				document.formulario.totrowcuentas.value=1;	
				parametros=parametros+"&totalcuentas=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowbienes=f.totrowbienes.value;
			if(rowbienes>1)
			{
				total=ue_calcular_total_fila_local("txtcuentacar");
				f.totrowcuentascargo.value=total;
				rowcuentas=f.totrowcuentascargo.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cargo=eval("document.formulario.txtcodcargo"+j+".value");
					cuenta=eval("document.formulario.txtcuentacar"+j+".value");
					codpro=eval("document.formulario.txtcodprocar"+j+".value");
					moncue=eval("document.formulario.txtmoncuecar"+j+".value");
					if((cuenta==cuentacargo)&&(codcargo==cargo))
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montocargo);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
							   "&txtmoncuecar"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				f.totrowcuentascargo.value=totalcuentas;	
				parametros=parametros+"&totalcuentascargo="+totalcuentas;
			}
			else
			{
				f.totrowcuentascargo.value=1;	
				parametros=parametros+"&totalcuentascargo=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			subtotal=ue_formato_calculo(subtotal);
			subtotal=eval(subtotal+"-"+montobien);
			cargos=ue_formato_calculo(cargos);
			cargos=eval(cargos+"-"+montocargo);
			total=ue_formato_calculo(total);
			total=eval(subtotal+"+"+cargos);
			subtotal=uf_convertir(subtotal);
			cargos=uf_convertir(cargos);
			total=uf_convertir(total);
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARBIENES&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_delete_servicios(fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montoservicios=0;
			montocargo=0;
			cuentacargo="";
			codigo="";
			cuentaservicios="";	
			//---------------------------------------------------------------------------------
			// Cargar los Servicios y eliminar el seleccionado
			//---------------------------------------------------------------------------------
			// Obtenemos el total de filas de los Servicios
			total=ue_calcular_total_fila_local("txtcodser");
			f.totrowservicios.value=total;
			rowservicios=f.totrowservicios.value;
			li_i=0;
			for(j=1;(j<rowservicios)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
	
					codser=eval("document.formulario.txtcodser"+j+".value");
					denser=eval("document.formulario.txtdenser"+j+".value");
					canser=eval("document.formulario.txtcanser"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					preser=eval("document.formulario.txtpreser"+j+".value");
					subtotser=eval("document.formulario.txtsubtotser"+j+".value");
					carser=eval("document.formulario.txtcarser"+j+".value");
					totser=eval("document.formulario.txttotser"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					parametros=parametros+"&txtcodser"+li_i+"="+codser+"&txtdenser"+li_i+"="+denser+""+
							   "&txtcanser"+li_i+"="+canser+"&txtpreser"+li_i+"="+preser+""+
							   "&txtsubtotser"+li_i+"="+subtotser+"&txtcarser"+li_i+"="+carser+""+
							   "&txttotser"+li_i+"="+totser+"&txtspgcuenta"+li_i+"="+spgcuenta+"&txtdenunimed"+li_i+"="+unimed+"";
				}
				else
				{
					codigo=eval("document.formulario.txtcodser"+j+".value");
					cuentaservicios=eval("document.formulario.txtspgcuenta"+j+".value");
					montoservicios=eval("document.formulario.txtsubtotser"+j+".value");
					montoservicios=ue_formato_calculo(montoservicios);
				}
			}
			li_i=li_i+1;
			parametros=parametros+"&totalservicios="+li_i+"";
			f.totrowservicios.value=li_i;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			li_i=0;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				if(codservic!=codigo)
				{
					li_i=li_i+1;
					codcar=eval("document.formulario.txtcodcar"+j+".value");
					dencar=eval("document.formulario.txtdencar"+j+".value");
					bascar=eval("document.formulario.txtbascar"+j+".value");
					moncar=eval("document.formulario.txtmoncar"+j+".value");
					subcargo=eval("document.formulario.txtsubcargo"+j+".value");
					spgcargo=eval("document.formulario.cuentacargo"+j+".value");
					formulacargo=eval("document.formulario.formulacargo"+j+".value");
					parametros=parametros+"&txtcodservic"+li_i+"="+codservic+"&txtcodcar"+li_i+"="+codcar+
							   "&txtdencar"+li_i+"="+dencar+"&txtbascar"+li_i+"="+bascar+
							   "&txtmoncar"+li_i+"="+moncar+"&txtsubcargo"+li_i+"="+subcargo+
							   "&cuentacargo"+li_i+"="+spgcargo+"&formulacargo"+li_i+"="+formulacargo;
				}
				else
				{
					cuentacargo=eval("document.formulario.cuentacargo"+j+".value");
					codcargo=eval("document.formulario.txtcodcar"+j+".value");
					montocargo=eval("document.formulario.txtmoncar"+j+".value");
					montocargo=ue_formato_calculo(montocargo);
				}
			}
			f.totrowcargos.value=li_i;
			parametros=parametros+"&totalcargos="+li_i;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowservicios=f.totrowservicios.value;
			if(rowservicios>1)       
			{
				total=ue_calcular_total_fila_local("txtcuentagas");
				f.totrowcuentas.value=total;
				rowcuentas=document.formulario.totrowcuentas.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cuenta=eval("document.formulario.txtcuentagas"+j+".value");
					codpro=eval("document.formulario.txtcodprogas"+j+".value");
					moncue=eval("document.formulario.txtmoncuegas"+j+".value");
					if(cuenta==cuentaservicios)
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montoservicios);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
							   "&txtmoncuegas"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				document.formulario.totrowcuentas.value=totalcuentas;	
				parametros=parametros+"&totalcuentas="+totalcuentas;
			}
			else
			{
				document.formulario.totrowcuentas.value=1;	
				parametros=parametros+"&totalcuentas=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowservicios=f.totrowservicios.value;
			if(rowservicios>1)       
			{
				total=ue_calcular_total_fila_local("txtcuentacar");
				f.totrowcuentascargo.value=total;
				rowcuentas=f.totrowcuentascargo.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cargo=eval("document.formulario.txtcodcargo"+j+".value");
					cuenta=eval("document.formulario.txtcuentacar"+j+".value");
					codpro=eval("document.formulario.txtcodprocar"+j+".value");
					moncue=eval("document.formulario.txtmoncuecar"+j+".value");
					if((cuenta==cuentacargo)&&(codcargo==cargo))
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montocargo);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
							   "&txtmoncuecar"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				f.totrowcuentascargo.value=totalcuentas;	
				parametros=parametros+"&totalcuentascargo="+totalcuentas;
			}
			else
			{
				f.totrowcuentascargo.value=1;	
				parametros=parametros+"&totalcuentascargo=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			subtotal=ue_formato_calculo(subtotal);
			subtotal=eval(subtotal+"-"+montoservicios);
			cargos=ue_formato_calculo(cargos);
			cargos=eval(cargos+"-"+montocargo);
			total=ue_formato_calculo(total);
			total=eval(subtotal+"+"+cargos);
			subtotal=uf_convertir(subtotal);
			cargos=uf_convertir(cargos);
			total=uf_convertir(total);
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARSERVICIOS&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_delete_conceptos(fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montoconceptos=0;
			montocargo=0;
			cuentacargo="";
			codigo="";
			cuentaconceptos="";	
			//---------------------------------------------------------------------------------
			// Cargar los Conceptos y eliminar el seleccionado
			//---------------------------------------------------------------------------------
			// Obtenemos el total de filas de los Conceptos
			total=ue_calcular_total_fila_local("txtcodcon");
			f.totrowconceptos.value=total;
			rowconceptos=f.totrowconceptos.value;
			li_i=0;
			for(j=1;(j<rowconceptos)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					codcon=eval("document.formulario.txtcodcon"+j+".value");
					dencon=eval("document.formulario.txtdencon"+j+".value");
					cancon=eval("document.formulario.txtcancon"+j+".value");
					precon=eval("document.formulario.txtprecon"+j+".value");
					subtotcon=eval("document.formulario.txtsubtotcon"+j+".value");
					carcon=eval("document.formulario.txtcarcon"+j+".value");
					totcon=eval("document.formulario.txttotcon"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
			
					parametros=parametros+"&txtcodcon"+li_i+"="+codcon+"&txtdencon"+li_i+"="+dencon+""+
							   "&txtcancon"+li_i+"="+cancon+"&txtprecon"+li_i+"="+precon+""+
							   "&txtsubtotcon"+li_i+"="+subtotcon+"&txtcarcon"+li_i+"="+carcon+""+
							   "&txttotcon"+li_i+"="+totcon+"&txtspgcuenta"+li_i+"="+spgcuenta;
				}
				else
				{
					codigo=eval("document.formulario.txtcodcon"+j+".value");
					cuentaconceptos=eval("document.formulario.txtspgcuenta"+j+".value");
					montoconceptos=eval("document.formulario.txtsubtotcon"+j+".value");
					montoconceptos=ue_formato_calculo(montoconceptos);
				}
			}
			li_i=li_i+1;
			parametros=parametros+"&totalconceptos="+li_i+"";
			f.totrowconceptos.value=li_i;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			li_i=0;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				if(codservic!=codigo)
				{
					li_i=li_i+1;
					codcar=eval("document.formulario.txtcodcar"+j+".value");
					dencar=eval("document.formulario.txtdencar"+j+".value");
					bascar=eval("document.formulario.txtbascar"+j+".value");
					moncar=eval("document.formulario.txtmoncar"+j+".value");
					subcargo=eval("document.formulario.txtsubcargo"+j+".value");
					spgcargo=eval("document.formulario.cuentacargo"+j+".value");
					formulacargo=eval("document.formulario.formulacargo"+j+".value");
					parametros=parametros+"&txtcodservic"+li_i+"="+codservic+"&txtcodcar"+li_i+"="+codcar+
							   "&txtdencar"+li_i+"="+dencar+"&txtbascar"+li_i+"="+bascar+
							   "&txtmoncar"+li_i+"="+moncar+"&txtsubcargo"+li_i+"="+subcargo+
							   "&cuentacargo"+li_i+"="+spgcargo+"&formulacargo"+li_i+"="+formulacargo;
				}
				else
				{
					cuentacargo=eval("document.formulario.cuentacargo"+j+".value");
					codcargo=eval("document.formulario.txtcodcar"+j+".value");
					montocargo=eval("document.formulario.txtmoncar"+j+".value");
					montocargo=ue_formato_calculo(montocargo);
				}
			}
			f.totrowcargos.value=li_i;
			parametros=parametros+"&totalcargos="+li_i;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowconceptos=f.totrowconceptos.value;
			if(rowconceptos>1)       
			{
				total=ue_calcular_total_fila_local("txtcuentagas");
				f.totrowcuentas.value=total;
				rowcuentas=document.formulario.totrowcuentas.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cuenta=eval("document.formulario.txtcuentagas"+j+".value");
					codpro=eval("document.formulario.txtcodprogas"+j+".value");
					moncue=eval("document.formulario.txtmoncuegas"+j+".value");
					if(cuenta==cuentaconceptos)
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montoconceptos);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
							   "&txtmoncuegas"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				document.formulario.totrowcuentas.value=totalcuentas;	
				parametros=parametros+"&totalcuentas="+totalcuentas;
			}
			else
			{
				document.formulario.totrowcuentas.value=1;	
				parametros=parametros+"&totalcuentas=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			rowconceptos=f.totrowconceptos.value;
			if(rowconceptos>1)       
			{
				total=ue_calcular_total_fila_local("txtcuentacar");
				f.totrowcuentascargo.value=total;
				rowcuentas=f.totrowcuentascargo.value;
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{
					cargo=eval("document.formulario.txtcodcargo"+j+".value");
					cuenta=eval("document.formulario.txtcuentacar"+j+".value");
					codpro=eval("document.formulario.txtcodprocar"+j+".value");
					moncue=eval("document.formulario.txtmoncuecar"+j+".value");
					if((cuenta==cuentacargo)&&(codcargo==cargo))
					{
						moncue=ue_formato_calculo(moncue);
						moncue=eval(moncue+"-"+montocargo);
						if(moncue<0)
						{
							moncue=0;
						}
						moncue=uf_convertir(moncue);
					}
					parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
							   "&txtmoncuecar"+j+"="+moncue;
				}
				totalcuentas=eval(rowcuentas);
				f.totrowcuentascargo.value=totalcuentas;	
				parametros=parametros+"&totalcuentascargo="+totalcuentas;
			}
			else
			{
				f.totrowcuentascargo.value=1;	
				parametros=parametros+"&totalcuentascargo=1";
			}
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			subtotal=ue_formato_calculo(subtotal);
			subtotal=eval(subtotal+"-"+montoconceptos);
			cargos=ue_formato_calculo(cargos);
			cargos=eval(cargos+"-"+montocargo);
			total=ue_formato_calculo(total);
			total=eval(subtotal+"+"+cargos);
			subtotal=uf_convertir(subtotal);
			cargos=uf_convertir(cargos);
			total=uf_convertir(total);
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARCONCEPTOS&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_delete_cargos(fila,tipo)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montobien=0;
			montoservicio=0;
			montoconcepto=0;
			montocargo=0;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			li_i=0;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				if(fila!=j)
				{
					li_i=li_i+1;
					codservic=eval("document.formulario.txtcodservic"+j+".value");
					codcar=eval("document.formulario.txtcodcar"+j+".value");
					dencar=eval("document.formulario.txtdencar"+j+".value");
					bascar=eval("document.formulario.txtbascar"+j+".value");
					moncar=eval("document.formulario.txtmoncar"+j+".value");
					subcargo=eval("document.formulario.txtsubcargo"+j+".value");
					spgcargo=eval("document.formulario.cuentacargo"+j+".value");
					formulacargo=eval("document.formulario.formulacargo"+j+".value");
					parametros=parametros+"&txtcodservic"+li_i+"="+codservic+"&txtcodcar"+li_i+"="+codcar+
							   "&txtdencar"+li_i+"="+dencar+"&txtbascar"+li_i+"="+bascar+
							   "&txtmoncar"+li_i+"="+moncar+"&txtsubcargo"+li_i+"="+subcargo+
							   "&cuentacargo"+li_i+"="+spgcargo+"&formulacargo"+li_i+"="+formulacargo;
				}
				else
				{
					codigo=eval("document.formulario.txtcodservic"+j+".value");
					cuentacargo=eval("document.formulario.cuentacargo"+j+".value");
					codcargo=eval("document.formulario.txtcodcar"+j+".value");
					montocargo=eval("document.formulario.txtmoncar"+j+".value");
					montocargo=ue_formato_calculo(montocargo);
				}
			}
			f.totrowcargos.value=li_i;
			parametros=parametros+"&totalcargos="+li_i;
			if(tipo=="B") // si es un Bien
			{
				proceso="AGREGARBIENES";
				//---------------------------------------------------------------------------------
				// Cargar los Bienes y eliminar el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodart");
				f.totrowbienes.value=total;
				rowbienes=f.totrowbienes.value;
				for(j=1;(j<rowbienes)&&(valido);j++)
				{
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					if(codart==codigo)
					{
						carart    = ue_formato_calculo(carart);
						carart    = carart-montocargo;
						subtotart = ue_formato_calculo(subtotart);
						totart    = subtotart+carart;
						carart    = uf_convertir(carart);
						subtotart = uf_convertir(subtotart);
						totart    = uf_convertir(totart);
					}
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalbienes="+rowbienes+"";
			}
			if(tipo=="S")
			{
				proceso="AGREGARSERVICIOS";
				//---------------------------------------------------------------------------------
				// Cargar los Servicios del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodser");
				f.totrowservicios.value=total;
				rowservicios=f.totrowservicios.value;
				for(j=1;(j<rowservicios)&&(valido);j++)
				{
					codser=eval("document.formulario.txtcodser"+j+".value");
					denser=eval("document.formulario.txtdenser"+j+".value");
					canser=eval("document.formulario.txtcanser"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					preser=eval("document.formulario.txtpreser"+j+".value");
					subtotser=eval("document.formulario.txtsubtotser"+j+".value");
					carser=eval("document.formulario.txtcarser"+j+".value");
					totser=eval("document.formulario.txttotser"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					if(codser==codigo)
					{
						carser=ue_formato_calculo(carser);
						carser=carser-montocargo;
						subtotser=ue_formato_calculo(subtotser);
						totser=subtotser+carser;
						carser=uf_convertir(carser);
						subtotser=uf_convertir(subtotser);
						totser=uf_convertir(totser);
					}
					parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
							   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
							   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
			}
			if(tipo=="O")
			{
				proceso="AGREGARCONCEPTOS";
				//---------------------------------------------------------------------------------
				// Cargar los Conceptos del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodcon");
				f.totrowconceptos.value=total;
				rowconceptos=f.totrowconceptos.value;
				for(j=1;(j<rowconceptos)&&(valido);j++)
				{
					codcon=eval("document.formulario.txtcodcon"+j+".value");
					dencon=eval("document.formulario.txtdencon"+j+".value");
					cancon=eval("document.formulario.txtcancon"+j+".value");
					precon=eval("document.formulario.txtprecon"+j+".value");
					subtotcon=eval("document.formulario.txtsubtotcon"+j+".value");
					carcon=eval("document.formulario.txtcarcon"+j+".value");
					totcon=eval("document.formulario.txttotcon"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					if(codcon==codigo)
					{
						carcon=ue_formato_calculo(carcon);
						carcon=carcon-montocargo;
						subtotcon=ue_formato_calculo(subtotcon);
						totcon=subtotcon+carcon;
						carcon=uf_convertir(carcon);
						subtotcon=uf_convertir(subtotcon);
						totcon=uf_convertir(totcon);
					}
					parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
							   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
							   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
							   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+spgcuenta;
				}
				parametros=parametros+"&totalconceptos="+rowconceptos+"";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentagas");
			f.totrowcuentas.value=total;
			rowcuentas=f.totrowcuentas.value;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				cuenta=eval("document.formulario.txtcuentagas"+j+".value");
				codpro=eval("document.formulario.txtcodprogas"+j+".value");
				moncue=eval("document.formulario.txtmoncuegas"+j+".value");
				parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
						   "&txtmoncuegas"+j+"="+moncue;
			}
			totalcuentas=eval(rowcuentas);
			f.totrowcuentas.value=totalcuentas;	
			parametros=parametros+"&totalcuentas="+totalcuentas;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentacar");
			f.totrowcuentascargo.value=total;
			rowcuentas=f.totrowcuentascargo.value;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				cargo=eval("document.formulario.txtcodcargo"+j+".value");
				cuenta=eval("document.formulario.txtcuentacar"+j+".value");
				codpro=eval("document.formulario.txtcodprocar"+j+".value");
				moncue=eval("document.formulario.txtmoncuecar"+j+".value");
				if((cuenta==cuentacargo)&&(codcargo==cargo))
				{
					moncue=ue_formato_calculo(moncue);
					moncue=eval(moncue+"-"+montocargo);
					if(moncue<0)
					{
						moncue=0;
					}
					moncue=uf_convertir(moncue);
				}
				parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
						   "&txtmoncuecar"+j+"="+moncue;
			}
			totalcuentas=eval(rowcuentas);
			f.totrowcuentascargo.value=totalcuentas;	
			parametros=parametros+"&totalcuentascargo="+totalcuentas;
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			subtotal=ue_formato_calculo(subtotal);
			cargos=ue_formato_calculo(cargos);
			cargos=eval(cargos+"-"+montocargo);
			total=ue_formato_calculo(total);
			total=eval(subtotal+"+"+cargos);
			subtotal=uf_convertir(subtotal);
			cargos=uf_convertir(cargos);
			total=uf_convertir(total);
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_delete_cuenta_gasto(fila,tipo)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montobien=0;
			montocargo=0;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				codcar=eval("document.formulario.txtcodcar"+j+".value");
				dencar=eval("document.formulario.txtdencar"+j+".value");
				bascar=eval("document.formulario.txtbascar"+j+".value");
				moncar=eval("document.formulario.txtmoncar"+j+".value");
				subcargo=eval("document.formulario.txtsubcargo"+j+".value");
				spgcargo=eval("document.formulario.cuentacargo"+j+".value");
				formulacargo=eval("document.formulario.formulacargo"+j+".value");
				parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
						   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
						   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
						   "&cuentacargo"+j+"="+spgcargo+"&formulacargo"+j+"="+formulacargo;
			}
			parametros=parametros+"&totalcargos="+rowcargos;
			if(tipo=="B") // si es un Bien
			{
				proceso="AGREGARBIENES";
				//---------------------------------------------------------------------------------
				// Cargar los Bienes y eliminar el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodart");
				f.totrowbienes.value=total;
				rowbienes=f.totrowbienes.value;
				for(j=1;(j<rowbienes)&&(valido);j++)
				{
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalbienes="+rowbienes+"";
			}
			if(tipo=="S")
			{
				proceso="AGREGARSERVICIOS";
				//---------------------------------------------------------------------------------
				// Cargar los Servicios del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodser");
				f.totrowservicios.value=total;
				rowservicios=f.totrowservicios.value;
				for(j=1;(j<rowservicios)&&(valido);j++)
				{
					codser=eval("document.formulario.txtcodser"+j+".value");
					denser=eval("document.formulario.txtdenser"+j+".value");
					canser=eval("document.formulario.txtcanser"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					preser=eval("document.formulario.txtpreser"+j+".value");
					subtotser=eval("document.formulario.txtsubtotser"+j+".value");
					carser=eval("document.formulario.txtcarser"+j+".value");
					totser=eval("document.formulario.txttotser"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
							   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
							   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
			}
			if(tipo=="O")
			{
				proceso="AGREGARCONCEPTOS";
				//---------------------------------------------------------------------------------
				// Cargar los Conceptos del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodcon");
				f.totrowconceptos.value=total;
				rowconceptos=f.totrowconceptos.value;
				for(j=1;(j<rowconceptos)&&(valido);j++)
				{
					codcon=eval("document.formulario.txtcodcon"+j+".value");
					dencon=eval("document.formulario.txtdencon"+j+".value");
					cancon=eval("document.formulario.txtcancon"+j+".value");
					precon=eval("document.formulario.txtprecon"+j+".value");
					subtotcon=eval("document.formulario.txtsubtotcon"+j+".value");
					carcon=eval("document.formulario.txtcarcon"+j+".value");
					totcon=eval("document.formulario.txttotcon"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
							   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
							   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
							   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+spgcuenta;
				}
				parametros=parametros+"&totalconceptos="+rowconceptos+"";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentagas");
			f.totrowcuentas.value=total;
			rowcuentas=f.totrowcuentas.value;
			li_i=0;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					cuenta=eval("document.formulario.txtcuentagas"+j+".value");
					codpro=eval("document.formulario.txtcodprogas"+j+".value");
					moncue=eval("document.formulario.txtmoncuegas"+j+".value");
					parametros=parametros+"&txtcodprogas"+li_i+"="+codpro+"&txtcuentagas"+li_i+"="+cuenta+
							   "&txtmoncuegas"+li_i+"="+moncue;
				}
			}
			totalcuentas=eval(li_i);
			f.totrowcuentas.value=totalcuentas;	
			parametros=parametros+"&totalcuentas="+totalcuentas;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentacar");
			f.totrowcuentascargo.value=total;
			rowcuentas=f.totrowcuentascargo.value;
			li_i=0;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				cargo=eval("document.formulario.txtcodcargo"+j+".value");
				cuenta=eval("document.formulario.txtcuentacar"+j+".value");
				codpro=eval("document.formulario.txtcodprocar"+j+".value");
				moncue=eval("document.formulario.txtmoncuecar"+j+".value");
				parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
						   "&txtmoncuecar"+j+"="+moncue;
			}
			parametros=parametros+"&totalcuentascargo="+rowcuentas;
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_delete_cuenta_cargo(fila,tipo)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montobien=0;
			montocargo=0;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			rowcargos=f.totrowcargos.value;
			for(j=1;(j<=rowcargos)&&(valido);j++)
			{
				codservic=eval("document.formulario.txtcodservic"+j+".value");
				codcar=eval("document.formulario.txtcodcar"+j+".value");
				dencar=eval("document.formulario.txtdencar"+j+".value");
				bascar=eval("document.formulario.txtbascar"+j+".value");
				moncar=eval("document.formulario.txtmoncar"+j+".value");
				subcargo=eval("document.formulario.txtsubcargo"+j+".value");
				spgcargo=eval("document.formulario.cuentacargo"+j+".value");
				formulacargo=eval("document.formulario.formulacargo"+j+".value");
				parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
						   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
						   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
						   "&cuentacargo"+j+"="+spgcargo+"&formulacargo"+j+"="+formulacargo;
			}
			parametros=parametros+"&totalcargos="+rowcargos;
			if(tipo=="B") // si es un Bien
			{
				proceso="AGREGARBIENES";
				//---------------------------------------------------------------------------------
				// Cargar los Bienes y eliminar el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodart");
				f.totrowbienes.value=total;
				rowbienes=f.totrowbienes.value;
				for(j=1;(j<rowbienes)&&(valido);j++)
				{
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalbienes="+rowbienes+"";
			}
			if(tipo=="S")
			{
				proceso="AGREGARSERVICIOS";
				//---------------------------------------------------------------------------------
				// Cargar los Servicios del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodser");
				f.totrowservicios.value=total;
				rowservicios=f.totrowservicios.value;
				for(j=1;(j<rowservicios)&&(valido);j++)
				{
					codser=eval("document.formulario.txtcodser"+j+".value");
					denser=eval("document.formulario.txtdenser"+j+".value");
					canser=eval("document.formulario.txtcanser"+j+".value");
					unimed=eval("document.formulario.txtdenunimed"+j+".value");
					preser=eval("document.formulario.txtpreser"+j+".value");
					subtotser=eval("document.formulario.txtsubtotser"+j+".value");
					carser=eval("document.formulario.txtcarser"+j+".value");
					totser=eval("document.formulario.txttotser"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
							   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
							   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+"&txtdenunimed"+j+"="+unimed+"";
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
			}
			if(tipo=="O")
			{
				proceso="AGREGARCONCEPTOS";
				//---------------------------------------------------------------------------------
				// Cargar los Conceptos del opener y el seleccionado
				//---------------------------------------------------------------------------------
				total=ue_calcular_total_fila_local("txtcodcon");
				f.totrowconceptos.value=total;
				rowconceptos=f.totrowconceptos.value;
				for(j=1;(j<rowconceptos)&&(valido);j++)
				{
					codcon=eval("document.formulario.txtcodcon"+j+".value");
					dencon=eval("document.formulario.txtdencon"+j+".value");
					cancon=eval("document.formulario.txtcancon"+j+".value");
					precon=eval("document.formulario.txtprecon"+j+".value");
					subtotcon=eval("document.formulario.txtsubtotcon"+j+".value");
					carcon=eval("document.formulario.txtcarcon"+j+".value");
					totcon=eval("document.formulario.txttotcon"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
							   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
							   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
							   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+spgcuenta;
				}
				parametros=parametros+"&totalconceptos="+rowconceptos+"";
			}
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentacar");
			f.totrowcuentascargo.value=total;
			rowcuentas=f.totrowcuentascargo.value;
			li_i=0;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					cargo=eval("document.formulario.txtcodcargo"+j+".value");
					cuenta=eval("document.formulario.txtcuentacar"+j+".value");
					codpro=eval("document.formulario.txtcodprocar"+j+".value");
					moncue=eval("document.formulario.txtmoncuecar"+j+".value");
					parametros=parametros+"&txtcodcargo"+li_i+"="+cargo+"&txtcodprocar"+li_i+"="+codpro+"&txtcuentacar"+li_i+"="+cuenta+
							   "&txtmoncuecar"+li_i+"="+moncue;
				}
			}
			totalcuentas=eval(li_i);
			f.totrowcuentascargo.value=totalcuentas;	
			parametros=parametros+"&totalcuentascargo="+totalcuentas;
			//---------------------------------------------------------------------------------
			// Cargar las Cuentas Presupuestarias del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtcuentagas");
			f.totrowcuentas.value=total;
			rowcuentas=f.totrowcuentas.value;
			li_i=0;
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{
				cuenta=eval("document.formulario.txtcuentagas"+j+".value");
				codpro=eval("document.formulario.txtcodprogas"+j+".value");
				moncue=eval("document.formulario.txtmoncuegas"+j+".value");
				parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
						   "&txtmoncuegas"+j+"="+moncue;
			}
			parametros=parametros+"&totalcuentas="+rowcuentas;
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			subtotal=f.txtsubtotal.value;
			cargos=f.txtcargos.value;
			total=f.txttotal.value;
			parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("bienesservicios");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
			}
		}
	}
}

function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
	tiposolicitud=f.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
	if(tipo=="B")
	{
		proceso="AGREGARBIENES";
	}
	if(tipo=="S")
	{
		proceso="AGREGARSERVICIOS";
	}
	if(tipo=="O")
	{
		proceso="AGREGARCONCEPTOS";
	}
	if(parametros!="")
	{
		divgrid = document.getElementById("bienesservicios");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/tepuy_sep_c_solicitud_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

</script> 
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
?>		  
</html>
