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
	require_once("class_folder/class_funciones_soc.php");
	$io_fun_soc=new class_funciones_soc();
	$io_fun_soc->uf_load_seguridad("SOC","tepuy_soc_p_registro_orden_compra.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_soc->uf_select_config("SOC","REPORTE","FORMATO_SOC","tepuy_soc_rfs_registro_orden_compra.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$li_diasem = date('w');
	switch ($li_diasem)
	{
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
	
   //------------------------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 15/04/2007					Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecordcom,$ls_numordcom,$ls_numordcomser,$ls_numordcombie,$ls_rb_bienes,$ls_rb_servicios,$ls_rbtipord;
		global $ls_tipsol,$ls_codprov,$ls_coduniadm,$ls_denuniadm,$ls_codfuefin,$ls_denfuefin,$ls_forpag,$ld_antpag,$ls_rb_nacional;
		global $ls_rb_exterior,$ls_concom,$ls_codtipmod,$ls_denmodcla,$ls_conordcom,$ls_obscom,$ls_lugentnomdep,$ls_lugentdir;
		global $ls_diaplacom,$ld_porsegcom,$ld_monsegcom,$ls_despai,$ls_desest,$ls_codpai,$ls_denmun,$ls_denpar,$ls_uniejeaso,$ls_readonly;
		global $ls_codest,$ls_codmun,$ls_codpar,$ls_codmon,$ls_denmon,$ld_tascamordcom,$ld_montotdiv,$ls_nomprov,$ls_tipo,$ls_fecentordcom;
		
   		global $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_rb_lugcom,$ls_tipconpro,$ls_tipafeiva;
		global $ls_operacion,$ls_existe,$io_fun_soc,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$ls_estsegcom;
		global $ls_parametros,$ls_estcom,$ls_estapro,$ls_codtipsol,$li_totrowcuentascargo,$li_totrowcuentas,$li_estsegcom;
		global $ls_perentdesde,$ls_perenthasta;
		
		require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
        $io_id_process_soc= new tepuy_c_generar_consecutivo();
		$ls_estatus="REGISTRO";
		$ls_estcom="R";
		$ld_fecordcom=date("d/m/Y");
		$ls_numordcom="";
		$ls_administrativo="";
	    $ls_logusr	  = $_SESSION["la_logusr"];
		$ls_codemp	  = $_SESSION["la_empresa"]["codemp"];
	    $ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];
		$lb_valido=$io_fun_soc->uf_soc_select_administrador($ls_codemp,$ls_logusr,$ls_administrativo);
	    if (($lb_valido)&&($ls_administrativo==1))
	       {
		     $ls_readonly = "";                           
	       }
	    else
	       {
		     $ls_readonly = "readonly";                           
	       }
		$ls_numordcombie=$io_id_process_soc->uf_generar_numero_nuevo('SOC','soc_ordencompra','numordcom','SOCCOC',15,'numordcom','estcondat','B');
		$ls_numordcomser=$io_id_process_soc->uf_generar_numero_nuevo('SOC','soc_ordencompra','numordcom','SOCCOS',15,'numordser','estcondat','S');
		$ls_rb_bienes="";
		$ls_rb_servicios="";
		$ls_rbtipord="";
		$ls_tipsol="";
		$ls_codprov="";
		$ls_nomprov="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_forpag="";
		$ld_antpag="0,00";
        $ls_rb_nacional="checked";
		$ls_rb_exterior="";
        $ls_concom="";
        $ls_codtipmod="";
		$ls_denmodcla="";
		$ls_conordcom="";
		$ls_obscom="";
		$ls_lugentnomdep="";
		$ls_lugentdir="";
		$ls_diaplacom="";
		$ld_porsegcom="0,00";
		$ld_monsegcom="0,00";
		$ls_despai="";
		$ls_desest="";
		$ls_denmun="";
		$ls_denpar="";
		$ls_codpai="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codmon="";
		$ls_denmon="";
		$ld_tascamordcom="0,00";
		$ld_montotdiv="0,00";
		$ls_tipo="OC";
		$li_estsegcom="";
		
		$ls_codestpro1="";		
		$ls_codestpro2="";		
		$ls_codestpro3="";		
		$ls_codestpro4="";		
		$ls_codestpro5="";	
		$ls_codtipsol="";	
		$ls_parametros="";
		$ls_operacion=$io_fun_soc->uf_obteneroperacion();
		$ls_existe=$io_fun_soc->uf_obtenerexiste();	
		$li_totrowbienes=0;
		$li_totrowservicios=0;
		$li_totrowcargos=0;
		$li_totrowcuentas=0;
		$li_totrowcuentascargo=0;
		$ls_estapro="";
		$ls_codtipsol="";
		$ls_tipconpro="";
		$ls_uniejeaso="";
		$ls_fecentordcom="";
		$ls_perentdesde="";
		$ls_perenthasta="";
   }
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 19/04/2007				Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecordcom,$ls_numordcom,$ls_rb_bienes,$ls_rb_servicios,$ls_tipsol,$ls_codprov,$ls_nomprov;
		global $ls_coduniadm,$ls_denuniadm,$ls_codfuefin,$ls_denfuefin,$ls_forpag,$ld_antpag,$ls_rb_nacional,$ls_rb_exterior;
		global $ls_concom,$ls_codtipmod,$ls_denmodcla,$ls_conordcom,$ls_obscom,$ls_lugentnomdep,$ls_lugentdir,$ls_diaplacom;
		global $ld_porsegcom,$ld_monsegcom,$ls_despai,$ls_desest,$ls_codpai,$ls_denmun,$ls_denpar,$ls_codpai,$ls_codest;
		global $ls_codmun,$ls_codpar,$ls_codmon,$ls_denmon,$ld_tascamordcom,$ld_montotdiv,$li_estsegcom,$ls_rbtipord,$ls_tipafeiva;
		global $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_rb_lugcom,$ls_tipsol,$ls_fecentordcom;
		global $ls_codtipsol,$li_totrowservicios,$li_totrowcargos,$li_subtotal,$li_cargos,$li_total,$li_totrowbienes,$ls_uniejeaso;
		global $li_totrowcuentas,$ls_estcom,$io_fun_soc,$li_totrowconceptos,$li_totrowcuentascargo,$ls_numsoldel,$ls_tipconpro;
		global $ls_perentdesde,$ls_perenthasta;
		
		$ls_estcom=$_POST["txtestcom"];
		$ld_fecordcom=$io_fun_soc->uf_obtenervalor("txtfecordcom",$_POST["txtfecha"]);
		$ls_numordcom=$_POST["txtnumordcom"];
		$ls_tipsol=$_POST["txttipsol"];
		$ls_coduniadm=$_POST["txtcoduniadm"];
		$ls_denuniadm=$_POST["txtdenuniadm"];
		$ls_codfuefin=$_POST["txtcodfuefin"];
		$ls_denfuefin=$_POST["txtdenfuefin"];
		$ls_rbtipord=$_POST["tipord"];
		if($ls_rbtipord=="B")
		{
		  $ls_rb_bienes="checked";
		}
		if($ls_rbtipord=="S")
		{
		  $ls_rb_servicios="checked";
		}
		$ls_codprov=$_POST["txtcodprov"];
		$ls_nomprov=$_POST["txtnomprov"];
		$ls_forpag=$_POST["cmbforpag"];
		$ld_antpag=$_POST["txtantpag"];
        $ls_rb_lugcom=$_POST["rblugcom"];
        if($ls_rb_lugcom=="N")
		{
		   $ls_rb_nacional="checked";
		}
        if($ls_rb_lugcom=="E")
		{
		   $ls_rb_exterior="checked";
		}
        $ls_concom=$_POST["cbmconcom"];
        $ls_codtipmod=$_POST["txtcodtipmod"];
		$ls_denmodcla=$_POST["txtdenmodcla"];
		$ls_conordcom=$_POST["txtconordcom"];
		$ls_obscom=$_POST["txtobscom"];
		$ls_lugentnomdep=$_POST["txtlugentnomdep"];
		$ls_lugentdir=$_POST["txtlugentdir"];
		$ls_diaplacom=$_POST["txtdiaplacom"];
		$ld_porsegcom=$_POST["txtporsegcom"];
		$ld_monsegcom=$_POST["txtmonsegcom"];
		$ls_codpai=substr($_POST["cmbpais"],0,3);
		$ls_despai=$_POST["despai"];
		$ls_codest=substr($_POST["cmbestado"],0,3);
		$ls_desest=$_POST["desest"];
		$ls_codmun=substr($_POST["cmbmunicipio"],0,3);
		$ls_denmun=$_POST["desmun"];
		$ls_codpar=substr($_POST["cmbparroquia"],0,3);
		$ls_denpar=$_POST["cmbparroquia"];
		$ls_codmon=$_POST["txtcodmon"];
		$ls_denmon=$_POST["txtdenmon"];
		$ld_tascamordcom=$_POST["txttascamordcom"];
		$ls_fecentordcom=$_POST["txtfecentordcom"];
		$ld_montotdiv=$_POST["txtmontotdiv"];
		
		if (array_key_exists("chkbestsegcom",$_POST))
	    {
			 $li_estsegcom      = $_POST["chkbestsegcom"];
			 $ls_chkbestsegcom  = "checked";
	    }
		else
	    {
			 $li_estsegcom      = 0;
			 $ls_chkbestsegcom  = "";
	    }
		$ls_tipsol=$_POST["txttipsol"];
		$ls_codestpro1=$_POST["txtcodestpro1"];	
		$ls_codestpro2=$_POST["txtcodestpro2"];	
		$ls_codestpro3=$_POST["txtcodestpro3"];		
		$ls_codestpro4=$_POST["txtcodestpro4"];		
		$ls_codestpro5=$_POST["txtcodestpro5"];	
		$li_totrowbienes=$_POST["totrowbienes"];
		$li_totrowservicios=$_POST["totrowservicios"];
		$li_totrowcargos=$_POST["totrowcargos"];
		$li_totrowcuentas=$_POST["totrowcuentas"];
		$li_totrowcuentascargo=$_POST["totrowcuentascargo"];
		$li_subtotal=$_POST["txtsubtotal"];
		$li_cargos=$_POST["txtcargos"];
		$li_total=$_POST["txttotal"];
		$ls_numsoldel = $_POST["numsoldel"];
		$ls_tipconpro = $_POST["tipconpro"];
		$ls_uniejeaso = $_POST["txtuniejeaso"];
		$ls_tipafeiva = $_POST["hidtipafeiva"];
		$ls_perentdesde = $_POST["txtperentdesde"];
		$ls_perenthasta = $_POST["txtperenthasta"];
   }
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		//Modificador Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 19/04/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_subtotal,$li_cargos,$li_total,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$li_totrowcuentas;
		global $li_totrowservicios,$li_totrowconceptos,$li_totrowcuentascargo;	
			
		for($li_i=1;($li_i<$li_totrowbienes);$li_i++)
		{
			$ls_codart		 = $_POST["txtcodart".$li_i];
			$ls_denart		 = $_POST["txtdenart".$li_i];
			$li_canart		 = $_POST["txtcanart".$li_i];
			$ls_denunimed	 = $_POST["txtdenunimed".$li_i];
			$ls_unidad		 = $_POST["cmbunidad".$li_i];
			$li_preart		 = $_POST["txtpreart".$li_i];
			$li_subtotart	 = $_POST["txtsubtotart".$li_i];
			$li_carart		 = $_POST["txtcarart".$li_i];
			$li_totart		 = $_POST["txttotart".$li_i];
			$ls_spgcuenta	 = $_POST["txtspgcuenta".$li_i];			
			$ls_unidadfisica = $_POST["txtunidad".$li_i];	
			$ls_numsolord	 = $_POST["txtnumsolord".$li_i];	
			$ls_coduniadmsep = $_POST["txtcoduniadmsep".$li_i];	
			$ls_denuniadmsep = $_POST["txtdenuniadmsep".$li_i];	
			$ls_estpreunieje = $_POST["txtestpreunieje".$li_i];	
			
			$as_parametros=$as_parametros."&txtcodart".$li_i."=".$ls_codart."&txtdenart".$li_i."=".$ls_denart."".
					   					  "&txtcanart".$li_i."=".$li_canart."&cmbunidad".$li_i."=".$ls_unidad."".
										  "&txtpreart".$li_i."=".$li_preart."&txtsubtotart".$li_i."=".$li_subtotart."".
										  "&txtcarart".$li_i."=".$li_carart."&txttotart".$li_i."=".$li_totart."".
										  "&txtspgcuenta".$li_i."=".$ls_spgcuenta."&txtunidad".$li_i."=".$ls_unidadfisica."".
										  "&txtdenunimed".$li_i."=".$ls_denunimed."&txtdenuniadmsep".$li_i."=".$ls_denuniadmsep."".
										  "&txtnumsolord".$li_i."=".$ls_numsolord."&txtcoduniadmsep".$li_i."=".$ls_coduniadmsep."".
										  "&txtestpreunieje".$li_i."=".$ls_estpreunieje;
		}
		$as_parametros=$as_parametros."&totalbienes=".$li_totrowbienes."";
		for($li_i=1;($li_i<$li_totrowservicios);$li_i++)
		{
			$ls_codser		 = $_POST["txtcodser".$li_i];
			$ls_denser		 = $_POST["txtdenser".$li_i];
			$li_canser		 = $_POST["txtcanser".$li_i];
			$li_preser		 = $_POST["txtpreser".$li_i];
			$li_subtotser	 = $_POST["txtsubtotser".$li_i];
			$li_carser		 = $_POST["txtcarser".$li_i];
			$li_totser		 = $_POST["txttotser".$li_i];
			$ls_spgcuenta	 = $_POST["txtspgcuenta".$li_i];		
			$ls_numsolord 	 = $_POST["txtnumsolord".$li_i];	
			$ls_coduniadmsep = $_POST["txtcoduniadmsep".$li_i];	
			$ls_denunimed    = $_POST["txtdenunimed".$li_i];
			$ls_denuniadmsep = $_POST["txtdenuniadmsep".$li_i];	
			$ls_estpreunieje = $_POST["txtestpreunieje".$li_i];	
				
			$as_parametros=$as_parametros."&txtcodser".$li_i."=".$ls_codser."&txtdenser".$li_i."=".$ls_denser."".
					  					  "&txtcanser".$li_i."=".$li_canser."&txtpreser".$li_i."=".$li_preser."".
					  					  "&txtsubtotser".$li_i."=".$li_subtotser."&txtcarser".$li_i."=".$li_carser."".
					   					  "&txttotser".$li_i."=".$li_totser."&txtspgcuenta".$li_i."=".$ls_spgcuenta."".
										  "&txtnumsolord".$li_i."=".$ls_numsolord."&txtcoduniadmsep".$li_i."=".$ls_coduniadmsep."".
										  "&txtdenunimed".$li_i."=".$ls_denunimed."&txtestpreunieje".$li_i."=".$ls_estpreunieje."".
										  "&txtestpreunieje".$li_i."=".$ls_estpreunieje;
		}
		$as_parametros=$as_parametros."&totalservicios=".$li_totrowservicios."";
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
   //------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title >Registro de Orden de Compra</title>
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="imagetoolbar" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/soc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php 
	require_once("class_folder/tepuy_soc_c_registro_orden_compra.php");
	$io_soc=new tepuy_soc_c_registro_orden_compra("../");
	uf_limpiarvariables();
	//print "operacion: ".
	//$ls_operacion="ELIMINAR";
	switch ($ls_operacion) 
	{
			case "GUARDAR":
				uf_load_variables();
				$ls_tipsol= $_POST["txttipsol"]; 
                for($li_i=1;($li_i<$li_totrowbienes);$li_i++)
				{
					$ls_numsol=$_POST["txtnumsolord".$li_i];	
				} 
			    if($ls_tipsol=="SEP")
				{
				 // $ls_uniejeaso="";
				  $ls_conordcom="";
				  $io_soc->uf_buscar_sep($ls_numsol,&$ls_coduni,&$ls_denoadm,&$ls_consol);
				 // $ls_uniejeaso=$ls_uniejeaso." "."Nro. SEP:".$ls_numsol."Unidad Solicitante: ".$ls_coduni." - ".$ls_denoadm.";";
				  $ls_conordcom=$ls_conordcom." ".$ls_consol;
				}
				$lb_valido=$io_soc->uf_guardar($ls_estcom,$ld_fecordcom,$li_estsegcom,$ls_numordcom,$ls_coduniadm,$ls_codfuefin,
				                               $ls_rbtipord,$ls_codprov,$ls_forpag,$ld_antpag,$ls_rb_lugcom,$ls_concom,$ls_codtipmod,
											   $ls_conordcom,$ls_obscom,$ls_lugentnomdep,$ls_lugentdir,$ls_diaplacom,$ld_porsegcom,
											   $ld_monsegcom,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_codmon,$ld_tascamordcom,
											   $ld_montotdiv,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											   $ls_codestpro5,$li_totrowbienes,$li_totrowservicios,$li_totrowcargos,$li_totrowcuentas,
											   $li_totrowcuentascargo,$li_subtotal,$li_cargos,$li_total,$la_seguridad,$ls_existe,
											   $ls_tipsol,$ls_numsoldel,$ls_uniejeaso,$ls_fecentordcom,$ls_perentdesde,$ls_perenthasta);
				uf_load_data($ls_parametros);
				switch($ls_estcom)
				{
					case "0": // R
						$ls_estatus="REGISTRO";
						break;
					case "1": // E 
						$ls_estatus="EMITIDA";
						break;
				}
				if($lb_valido)
				{
					$ls_existe="TRUE";
				}
			break;
			
			case "ELIMINAR";  //PARA PODER ELIMINAR UNA ORDEN DEBO COLOCAR NUEVO Y ACTIVAS $ls_rbtipord
				uf_load_variables();
				//$li_i=1;
				//$ls_numordcom=$_POST["txtnumsolord".$li_i];
				//$ls_numordcom="000000000006002";
				//print "aqui: ".$ls_numordcom;
				//$ls_rbtipord="B";
				$lb_valido=$io_soc->uf_delete_orden_compra($ls_numordcom,$ls_rbtipord,$la_seguridad);
				if(!$lb_valido)
				{
					uf_load_data(&$ls_parametros);
					switch($ls_estcom)
					{
						case "0": // R
							$ls_estatus="REGISTRO";
							break;
						case "1": // E
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
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="850" height="40"></td>
  </tr>
  <tr>
    <td height="40" colspan="11" bgcolor="#E7E7E7">
		<table width="843" height="22" border="0" align="center" cellpadding="0" cellspacing="0">
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Orden de Compra </td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a e");?></b></span></div></td>
  	    </table>
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="20"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="688">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="" method="post" name="formulario" id="tepuy_soc_p_registro_orden_compra.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_soc->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_soc);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136"><p>&nbsp;</p>
      <table width="850" height="22" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="6" class="titulo-ventana">Registro de  Orden de Compra 
            <input name="hidtipafeiva" type="hidden" id="hidtipafeiva" value="<?php echo $ls_tipafeiva ?>"></td>
          </tr>
          <tr style="visibility:hidden">
            <td height="22"><div align="left">Reporte en
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected="selected">Bs.</option>
                <option value="1">Bs.F.</option>
              </select>
            </div></td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td width="154" height="22" style="text-align:right">Estatus</td>
            <td colspan="2">
                <input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="30" readonly>            </td>
            <td colspan="2" style="text-align:right">Fecha</td>
            <td width="217"><input name="txtfecordcom" type="text" id="txtfecordcom" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecordcom;?>" size="15"  datepicker="true"></td>
          </tr>
          <tr>
            <td align="right">Tipo de Orden </td>
            <td width="170" align="right"><div align="left">
              <input name="rbtipord" type="radio" class="sin-borde"  onClick="javascript: ue_generar_numero('<?php echo $ls_read ?>');" value="B" <?php print $ls_rb_bienes; ?>>
              <span class="sin-borde3">Bienes</span>
              <input name="rbtipord" type="radio" class="sin-borde"  onClick="javascript: ue_generar_numero('<?php echo $ls_read ?>');" value="S" <?php print $ls_rb_servicios; ?>>
            <span class="sin-borde3">              Servicios</span>              </div></td>
            <td colspan="3" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">N&uacute;mero</td>
            <td height="22"><input name="txtnumordcom" type="text" id="txtnumordcom" value="<?php print $ls_numordcom;?>" size="18" maxlength="15" style="text-align:center" onBlur="javascript:rellenar_cad(this.value,15,this)" onKeyPress="return keyRestrict(event,'1234567890'); " read></td>
            <td width="91" height="22">&nbsp;</td>
            <td height="22" colspan="3"></a><a href="javascript: ue_catalogo_proveedor();"></a></td></tr>
          <tr>
            <td height="22" style="text-align:right">Proveedor</td>
            <td height="22" colspan="5"><input name="txtcodprov" type="text" id="txtcodprov" value="<?php print $ls_codprov;?>" size="15" maxlength="10" readonly style="text-align:center">
            <a href="javascript: ue_catalogo_proveedor();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtnomprov" type="text" class="sin-borde" id="txtnomprov" value="<?php print $ls_nomprov;?>" size="100" maxlength="50" read></td>
          </tr>
          <tr>
            <td height="22"><div align="right"><a href="javascript: ue_cargar_solicitud();"><img src="../shared/imagebank/tools20/presupuestaria.gif"  alt="Buscar" width="20" height="20" border="0"></a></div></td>
            <td height="22" colspan="5"><a href="javascript: ue_cargar_solicitud();"><span class="sin-borde3">Solicitud de Ejecucion Presupuestaria</span></a></td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Unidad Solicitante</td>
            <td height="22" colspan="5"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_catalogo('tepuy_soc_cat_unidad_ejecutora.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm;?>" size="100" readonly>
                <a href="javascript: ue_catalogo('tepuy_soc_cat_modalidad_clausulas.php');"></a> </td>
          </tr>
          
          <tr>
            <td height="22" style="text-align:right">Fuente de Financiamiento</td>
            <td height="22" colspan="5"><input name="txtcodfuefin" type="text" id="txtcodfuefin" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="5" maxlength="2" readonly>
                <a href="javascript: ue_catalogo('tepuy_soc_cat_fuente_financiamiento.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdenfuefin" type="text" class="sin-borde" id="txtdenfuefin" value="<?php print $ls_denfuefin;?>" size="110" readonly></td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Lugar de la Compra</td>
            <td height="22"><input name="rblugcom" type="radio" class="sin-borde" value="N" <?php print $ls_rb_nacional; ?>>
            <span class="sin-borde3">Nacional
            <input name="rblugcom" type="radio" class="sin-borde" value="E" <?php print $ls_rb_exterior; ?>>
            Extranjero</span></td>
            <td height="22" style="text-align:right">Condici&oacute;n</td>
            <td height="22"><select name="cbmconcom"  style="width:120px" id="cbmconcom">
              <option value="-">---seleccione---</option>
              <option value="CIF"  <?php if($ls_concom=="FAS"){ print 'selected';}  ?>>CIF</option>
              <option value="FAS"  <?php if($ls_concom=="FAS"){ print 'selected';}  ?>>FAS</option>
              <option value="FOB"  <?php if($ls_concom=="FOB"){ print 'selected';}  ?>>FOB</option>
              <option value="OTRO" <?php if($ls_concom=="OTRO"){ print 'selected';} ?>>OTROS</option>
                        </select></td>
            <td>&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr> 
            <td height="22" style="text-align:right">Forma de Pago</td>
            <td height="22">
              <select name="cmbforpag"  style="width:120px" id="cmbforpag">
                <option value="-">---seleccione---</option>
                <option value="CONTADO" <?php if($ls_forpag=="CONTADO"){ print 'selected';} ?>>CONTADO</option>
                <option value="CREDITO" <?php if($ls_forpag=="CREDITO"){ print 'selected';} ?>>CREDITO</option>
                <option value="CHEQUE"  <?php if($ls_forpag=="CHEQUE"){ print 'selected';} ?>>CHEQUE</option>
                <option value="CARCRE"  <?php if($ls_forpag=="CARCRE"){ print 'selected';} ?>>CARTA DE CREDITO</option>
                <option value="ABOCUE"  <?php if($ls_forpag=="ABOCUE"){ print 'selected';} ?>>ABONO EN CUENTA</option>
                <option value="OTROS"   <?php if($ls_forpag=="OTROS"){ print 'selected';}  ?>>OTROS</option>
			  </select>            </td>
            <td height="22" style="text-align:right">Anticipo de Pago</td>
            <td width="132" height="22"><input name="txtantpag"  style="text-align:right" type="text" id="txtantpag" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php  print $ld_antpag; ?>" size="25" maxlength="25"></td>
            <td width="84">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Modalidad de la Clausula</td>
            <td height="22" colspan="5"><div align="left">
              <input name="txtcodtipmod" type="text" id="txtcodtipmod" value="<?php  print $ls_codtipmod; ?>" size="10" readonly style="text-align:center">
              <a href="javascript: ue_catalogo('tepuy_soc_cat_modalidad_clausulas.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdenmodcla" type="text"  style="text-align:left "class="sin-borde" id="txtdenmodcla" value="<?php  print  $ls_denmodcla; ?>" size="50" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="35" style="text-align:right">Concepto</td>
            <td height="35" colspan="5"><label>
              <textarea name="txtconordcom" cols="90" wrap="physical" id="txtconordcom" style="text-align:left" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); "><?php print $ls_conordcom ?></textarea>
            </label></td>
          </tr>
          <tr>
            <td height="35" style="text-align:right">Unidades Asociadas</td>
            <td height="35" colspan="5"><textarea read name="txtuniejeaso" cols="90" id="txtuniejeaso" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz&aacute;&eacute;&iacute;&oacute;&uacute; '+'&iexcl;!:;_&deg;#@/?&iquest;%&$*-,.+(){}[]='); "><?php print $ls_uniejeaso ?></textarea></td>
          </tr>
          <tr>
            <td height="35" style="text-align:right">Observaci&oacute;n</td>
            <td height="35" colspan="5"><label>
              <textarea name="txtobscom" cols="90" id="txtobscom" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); "><?php print $ls_obscom ?></textarea>
            </label></td>
          </tr>
          <tr>
            <td height="13" colspan="3" class="titulo-celdanew">CONDICIONES DE LA ENTREGA </td>
            <td height="13" colspan="3" class="titulo-celdanew"><div align="center">SEGURO</div></td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Unidad Beneficiada</td>
            <td height="22" colspan="3"><div align="left">
              <label>
              <input name="txtlugentnomdep" type="text" id="txtlugentnomdep" value="<?php echo $ls_lugentnomdep; ?>" size="20" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); ">
              </label>
</div></td>
            <td height="22" style="text-align:right">Seguro</td>
            <td height="22"><div align="left">
              <input name="chkbestsegcom" type="checkbox" class="sin-borde" id="chkbestsegcom" value="1">
            </div></td>
          </tr>
          <tr>
            <td height="20" style="text-align:right">Direcci&oacute;n</td>
            <td height="20" colspan="3"><input name="txtlugentdir" type="text" id="txtlugentdir" value="<?php echo $ls_lugentdir; ?>" size="20" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� '+'�!:;_�#@/?�%&$*-,.+(){}[]='); "></td>
            <td height="20" style="text-align:right">Porcentaje</td>
            <td height="20"><div align="left">
              <input name="txtporsegcom" type="text" id="txtporsegcom"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php  print $ld_porsegcom;  ?>" size="15" maxlength="10">
            </div></td>
          </tr>
          <tr>
            <td height="20" style="text-align:right">Fecha de Entrega</td>
            <td height="20" colspan="2"><label>
              <input name="txtfecentordcom" type="text" id="txtfecentordcom" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php echo $ls_fecentordcom ?>" size="15" maxlength="10" datepicker="true">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plazo en D&iacute;as </label></td>
            <td height="20"><input name="txtdiaplacom"  style="text-align:right" type="text" id="txtdiaplacom" value="<?php echo $ls_diaplacom; ?>" size="8" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890');"></td>
            <td height="20" style="text-align:right">Monto</td>
            <td height="20"><input name="txtmonsegcom"  style="text-align:right" type="text" id="txtmonsegcom" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php  print  $ld_monsegcom; ?>" size="25" maxlength="25"></td>
          </tr>
          <tr>
            <td height="20" style="text-align:right">Per&iacute;odo de Entrega Desde </td>
            <td height="20"><input name="txtperentdesde" type="text" id="txtperentdesde" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php echo $ls_perentdesde ?>" size="15" maxlength="10" datepicker="true"></td>
            <td height="20"><div align="right">Hasta</div></td>
            <td height="20"><input name="txtperenthasta" type="text" id="txtperenthasta" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php echo $ls_perenthasta ?>" size="15" maxlength="10" datepicker="true"></td>
            <td height="20" style="text-align:right">&nbsp;</td>
            <td height="20">&nbsp;</td>
          </tr>
          <tr>
            <td height="14" colspan="3" class="titulo-celdanew">UBICACION GEOGRAFICA </td>
            <td height="14" colspan="3" class="titulo-celdanew"><div align="center">MONEDA EXTRANJERA </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">
              <input name="despai"        type="hidden" id="despai"        value="<?php print $ls_despai; ?>">
              Pais</div></td>
            <td height="22" colspan="2"><div  id="pais" align="left">
              <?php $io_soc->uf_soc_combo_paises($ls_codpai."-".$ls_despai); ?>
            </div></td>
            <td height="22"></td>
            <td height="22" style="text-align:right">Tipo Moneda</td>
            <td height="22"><div align="left">
              <input name="txtcodmon" type="text" id="txtcodmon" value="<?php  print $ls_codmon;  ?>" size="5" maxlength="3" readonly style="text-align:center">
              <a href="javascript: ue_catalogo('tepuy_soc_cat_moneda.php');"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdenmon" type="text" class="sin-borde" id="txtdenmon" value="<?php  print $ls_denmon;  ?>" size="22" maxlength="50" readonly>
              </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">
              <input name="desest"        type="hidden" id="desest"        value="<?php print $ls_desest; ?>">
              Estado</div></td>
            <td height="22" colspan="2"><div id="estado" align="left">
              <?php $io_soc->uf_soc_combo_estado($ls_desest,$ls_codpai);?>
            </div></td>
            <td height="22"></td>
            <td height="22" style="text-align:right">Tasa de Cambio</td>
            <td height="22"><input name="txttascamordcom"  style="text-align:right" type="text" id="txttascamordcom" value="<?php  print $ld_tascamordcom; ?>" size="15" maxlength="10" readonly>            </td>
          </tr>
          <tr>
            <td height="22"><div align="right">
              <input name="desmun"        type="hidden" id="desmun"        value="<?php print $ls_denmun; ?>">
              Municipio</div></td>
            <td height="22" colspan="2"><div  id="municipio" align="left">
              <?php $io_soc->uf_soc_combo_municipio($ls_denmun,$ls_codpai,$ls_codest);?>
            </div></td>
            <td height="22" colspan="2" style="text-align:right">Monto en Divisas</td>
            <td height="22"><input name="txtmontotdiv" style="text-align:right" type="text" id="txtmontotdiv" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php  print $ld_montotdiv;  ?>" size="25" maxlength="25">            </td>
          </tr>
          <tr>
            <td height="21" style="text-align:right">Parroquia</td>
            <td height="21" colspan="2"><div id="parroquia" align="left">
              <?php $io_soc->uf_soc_combo_parroquia($ls_denpar,$ls_codpai,$ls_codest,$ls_codmun);?>
            </div></td>
            <td height="21">&nbsp;</td>
            <td height="21">&nbsp;</td>
            <td height="21">&nbsp;</td>
          </tr>
        </table>
        <table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center"><div id="bienesservicios"></div></td>
          </tr>
        </table>
        <p>
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          <input name="numordcomser"  type="hidden" id="numordcomser"  value="<?php print $ls_numordcomser; ?>">
          <input name="numordcombie"  type="hidden" id="numordcombie"  value="<?php print $ls_numordcombie; ?>">
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>">
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2;?>">
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>">
          <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
          <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
          <input name="totrowbienes"  type="hidden" id="totrowbienes"     value="<?php print $li_totrowbienes;?>">
          <input name="totrowservicios"  type="hidden" id="totrowservicios"  value="<?php print $li_totrowservicios;?>">
          <input name="totrowcargos"  type="hidden" id="totrowcargos"  value="<?php print $li_totrowcargos;?>">
          <input name="totrowcuentas" type="hidden" id="totrowcuentas" value="<?php print $li_totrowcuentas;?>">
          <input name="totrowcuentascargo" type="hidden" id="totrowcuentascargo" value="<?php print $li_totrowcuentascargo;?>">
          <input name="parametros"    type="hidden" id="parametros"    value="<?php print $ls_parametros;?>">
          <input name="txtestcom"     type="hidden" id="txtestcom"     value="<?php print $ls_estcom;?>">
          <input name="txtestapro"    type="hidden" id="txtestapro"    value="<?php print $ls_estapro; ?>">
          <input name="txttipsol"     type="hidden" id="txttipsol"     value="<?php print $ls_tipsol; ?>">
          <input name="tipord"        type="hidden" id="tipord"        value="<?php print $ls_rbtipord; ?>">
          <input name="txtfecha"      type="hidden" id="txtfecha"      value="<?php print $ld_fecordcom; ?>">
          <input name="formato"       type="hidden" id="formato"       value="<?php print $ls_reporte; ?>">
          <input name="tipo"          type="hidden" id="tipo"          value="<?php print $ls_tipo; ?>">
          <input name="numsoldel"     type="hidden" id="numsoldel">
          <input name="tipconpro"     type="hidden" id="tipconpro"     value="<?php print $ls_tipconpro; ?>">
      </p></td>
    </tr>
</table>
</form>   
<?php
	$io_soc->uf_destructor();
	unset($io_soc);
?>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_cargar_solicitud()
{
  f            = document.formulario;
  ls_codpro    = f.txtcodprov.value;
  ls_tipconpro = f.tipconpro.value;
  ls_tipordcom = f.tipord.value;
  ls_tipsol    = f.txttipsol.value;
  if ((ls_tipsol=="SEP")||(ls_tipsol==""))
     {
	   if (ls_tipordcom=='B' || ls_tipordcom=='S')
	      {
			if (ls_codpro!="")
			   {
                 if (ls_tipconpro!="")
				    {
				      window.open("tepuy_soc_cat_solicitud_presupuestaria.php?tipord="+ls_tipordcom,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=no");			   					  
					}
				 else
				    {
					  alert("El Proveedor no tiene Tipo de Contribuyente asociado !!!");
					}
			   }
			else
			   {
				 alert("Debe seleccionar un Proveedor !!!");
			   }
          }
	   else
	      {
	        alert("Debe seleccionar Tipo de Orden, Bienes o Servicios !!!");
	      }		
     }
  else
     {
	   alert("Si desea procesar Solicitudes de Ejecuci�n Presupuestaria, No indique Unidad d Solicitante ya que estas vienen definidas desde las mismas !!!");
     }
}

function ue_catalogo(ls_catalogo)
{
	if(ls_catalogo=='tepuy_soc_cat_unidad_ejecutora.php')
	{
		f=document.formulario;
        ls_tipsol=f.txttipsol.value;
        ls_tipo=f.tipo.value;
		lb_existe=f.existe.value;
		if((ls_tipsol=="SEP")||(lb_existe=="TRUE"))
		{
			window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
			//alert("La Unidad Solicitante no se puede modificar.");		
		}
		else
		{
			if((f.totrowbienes.value>1)||(f.totrowservicios.value>1)||(f.totrowcargos.value>0)||
			(f.totrowcuentas.value>1)||(f.totrowcuentascargo.value>1))
			{
				alert("La Unidad Solicitante no se puede modificar, ya existen movimientos en la solicitud.");		
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

function ue_catalogo_proveedor()
{
	// abre el catalogo que se paso por parametros
	f            = document.formulario;
	lb_existe    = f.existe.value;
    ls_tipordcom = "";
	if (f.rbtipord[0].checked==true)
	   {
	     ls_tipordcom = "B";
	   }
	else
	   {
	     if (f.rbtipord[1].checked==true)
		    {
			  ls_tipordcom = "S";
			}
	   }
	if (ls_tipordcom=="")
	   {
	     alert("Debe seleccionar Tipo de Orden, Bienes y/o Servicios !!!");
	   }
	else
	   {
	     if (lb_existe!="TRUE")
	        { 
		      window.open("tepuy_soc_cat_proveedor.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	        }	
	   }
}

function ue_nuevo()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="tepuy_soc_p_registro_orden_compra.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cambiar_estado()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		// Cargamos las variables para pasarlas al AJAX
		cmbpais=f.cmbpais.value;
		tipo="ESTADOS";
		despai=f.despai.value;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('estado');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("tipo="+tipo+"&cmbpais="+cmbpais+"&despai="+despai+"&proceso=CARGAR-COMBO");
	}
}

function ue_cambiar_municipio()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		// Cargamos las variables para pasarlas al AJAX
		cmbpais=f.cmbpais.value;
		cmbestado=f.cmbestado.value;
		tipo="MUNICIPIOS";
		desest=f.desest.value;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('municipio');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("tipo="+tipo+"&cmbpais="+cmbpais+"&cmbestado="+cmbestado+"&desest="+desest+"&proceso=CARGAR-COMBO");
	}
}

function ue_cambiar_parroquia()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		// Cargamos las variables para pasarlas al AJAX
		cmbpais=f.cmbpais.value;
		cmbestado=f.cmbestado.value;
		cmbmunicipio=f.cmbmunicipio.value;
		tipo="PARROQUIAS";
		desmun=f.desmun.value;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('parroquia');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("tipo="+tipo+"&cmbpais="+cmbpais+"&cmbestado="+cmbestado+"&cmbmunicipio="+cmbmunicipio+"&desmun="+desmun+"&proceso=CARGAR-COMBO");
	}
}

function ue_generar_numero(ls_readonly)
{
	f=document.formulario;
	lb_existe  = f.existe.value;
	li_estapro = f.txtestapro.value;
	if (li_estapro==1)
	   {
	     alert("La Orden de Compra est� aprobada no puede ser modificada !!!");
 	   }
	else
	   {
	     if (f.rbtipord[0].checked)
			{  
			  f.tipord.value="B";
			  numordcombie=f.numordcombie.value;
			  if (numordcombie==false)
			     {
				   alert("Este documento est� configurado para el manejo de Prefijos, y en este momento Ud. No tiene acceso a ninguno. Por favor dir�jase al Administrador del Sistema");
				   location.href='tepuywindow_blank.php';
				 }
			  else
			     {
				   f.txtnumordcom.value=numordcombie;
				   if (ls_readonly=='')
					  {
					    f.txtnumordcom.readOnly = false;
					  }
				   else
					  {
					    f.txtnumordcom.readOnly = true;
					  }
				   f.rbtipord[0].disabled=true;  
				   f.rbtipord[1].disabled=true;  
				 }
			} 
		 if (f.rbtipord[1].checked)
			{ 		    	
			  f.tipord.value="S";  
			  numordcomser=f.numordcomser.value;
			  if (numordcomser==false)
			     {
				   alert("Este documento est� configurado para el manejo de Prefijos, y en este momento Ud. No tiene acceso a ninguno. Por favor dir�jase al Administrador del Sistema");
				   location.href='tepuywindow_blank.php';
				 }
			  else
			     {
				   f.txtnumordcom.value=numordcomser;
				   if (ls_readonly=='')
					  {
					    f.txtnumordcom.readOnly = false;
					  }
				   else
					  {
					    f.txtnumordcom.readOnly = true;
					  }
				   f.rbtipord[0].disabled=true;
				   f.rbtipord[1].disabled=true;
				 }
			} 	   
			tipordcom=f.tipord.value;
			f.totrowbienes.value=1;
			f.totrowservicios.value=1;
			f.totrowcargos.value=0;
			f.totrowcuentas.value=1;
			f.totrowcuentascargo.value=1;
			// Div donde se van a cargar los resultados
			divgrid = document.getElementById('bienesservicios');
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde est�n los m�todos para buscar y pintar los resultados
			ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					divgrid.innerHTML = ajax.responseText
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("tipo="+tipordcom+"&totalbienes=1&totalservicios=1&totalcargos=0"+"&totalcuentas=1&totalcuentascargo=1&proceso=LIMPIAR");
	        if (lb_existe!="TRUE")
	           { 
		         window.open("tepuy_soc_cat_proveedor.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	           }	
	      }
}

function ue_catalogo_bienes()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;	
		ls_tipo=f.tipo.value;
	    tipconpro=f.tipconpro.value;
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_soc_cat_bienes.php?tipo="+ls_tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
        
		}
		else
		{
			alert("Debe seleccionar una Unidad Solicitante");
		}
	}
}


function ue_catalogo_servicios()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		// Se carga el catalogo de Bienes, Si no se ha elegido la unidad ejecutora no se carga
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;             
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;
		ls_tipo=f.tipo.value;
	    tipconpro=f.tipconpro.value;
		if ((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			window.open("tepuy_soc_cat_servicios.php?tipo="+ls_tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Solicitante");
		}
	}
}



function ue_procesar_monto(tipo,fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
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
			// Si es una fila que tiene Art�culos
			if(codart!="")
			{
				// Si la cantidad de art�culos � el precio es mayor que cero calculamos
				if((canart>0)||(preart>0))
				{
					if(unidad=="M")
					{
						// si es al mayor multiplicamos la cantidad tipeada por la cantidad de la unidad
						canart=eval(canart+"*"+canunidad);
					}
					monnet=canart*preart;
					totalarticulo=0;
					monnet=redondear(monnet,2);
					eval("f.txtsubtotart"+fila+".value='"+uf_convertir(monnet)+"'");
					totalcargo=0;
					// Actualizamos los cr�ditos de ese art�culo si tiene
					ue_actualizar_creditos(codart,monnet);
					totalarticulo=eval(monnet+"+"+totalcargo);
					totalarticulo=redondear(totalarticulo,2);
					totalcargo=redondear(totalcargo,2);
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
			// Si es una fila que tiene Servicios
			if(codser!="")
			{
				// Si la cantidad de servicios � el precio es mayor que cero calculamos
				if((canser>0)||(preser>0))
				{
					monnet=canser*preser;
					totalservicio=0;
					eval("f.txtsubtotser"+fila+".value='"+uf_convertir(monnet)+"'");
					totalcargo=0;
					// Actualizamos los cr�ditos de ese art�culo si tiene
					ue_actualizar_creditos(codser,monnet);
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
	}
}

function ue_actualizar_creditos(codigo,monto)
{
	f=document.formulario;
	rowcargo=f.totrowcargos.value;
	for(fila_cargo=1;(fila_cargo<=rowcargo);fila_cargo++)
	{
		codartcargo=eval("f.txtcodservic"+fila_cargo+".value");
		// Si el codigo del art�culo que se esta actualizando es igual al actual
		if(codartcargo==codigo)
		{
			cuentacargo=eval("f.cuentacargo"+fila_cargo+".value");
			formula=eval("f.formulacargo"+fila_cargo+".value");
			formula=formula.replace("$LD_MONTO",monto);
			cargo=redondear(eval(formula),2);
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
		ls_codestpro = eval("f.txtcodprogas"+fila_cuenta+".value");
		cuenta		 = eval("f.txtcuentagas"+fila_cuenta+".value");
		moncueact    = eval("f.txtmoncuegas"+fila_cuenta+".value");
		moncue=0;
		lb_entro=false;
		if(tipo=="B")
		{
			// Recorremos los Bienes para colocar el total de las cuentas
			rowbienes=f.totrowbienes.value;
			for(fila_bienes=1;fila_bienes<rowbienes;fila_bienes++)
			{
				spgcuenta		= eval("f.txtspgcuenta"+fila_bienes+".value");
				ls_estpreunieje = eval("f.txtestpreunieje"+fila_bienes+".value");
				if((cuenta==spgcuenta)&&(cuenta==spgcuentaact)&&(ls_codestpro==ls_estpreunieje))
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
				ls_estpreunieje = eval("f.txtestpreunieje"+fila_servicios+".value");
				if((cuenta==spgcuenta)&&(cuenta==spgcuentaact)&&(ls_codestpro==ls_estpreunieje))
				{
					montoservicios=eval("f.txtsubtotser"+fila_servicios+".value");
					montoservicios=ue_formato_calculo(montoservicios);
					moncue=eval(moncue+"+"+montoservicios);
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
			montoservicios=eval("f.txttotser"+fila_servicios+".value");
			montoservicios=ue_formato_calculo(montoservicios);
			total=eval(total+"+"+montoservicios);
		}
	}
	subtotal=redondear(subtotal,2);
	cargos=redondear(cargos,2);
	total=redondear(total,2);
	f.txtsubtotal.value=uf_convertir(subtotal);
	f.txtcargos.value=uf_convertir(cargos);
	f.txttotal.value=uf_convertir(total);
}

function ue_catalogo_cuentas_spg()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
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
			window.open("tepuy_soc_cat_cuentas_spg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Solicitante");
		}
	}
}

function ue_catalogo_cuentas_cargos()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
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
			window.open("tepuy_soc_cat_cuentas_cargos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
		else
		{
			alert("Debe seleccionar una Unidad Solicitante");
		}
	}
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_soc_cat_orden_compra.php?origen=OC","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,location=no,resizable=no");
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

function writetostatus(input)
{
    window.status=input
    return true
}

function ue_delete_bienes(fila)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	ls_codunieje = f.txtcoduniadm.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
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
			total = ue_calcular_total_fila_local("txtcodart");
			f.totrowbienes.value = total;
			rowbienes = f.totrowbienes.value;
			li_i = 0;
			lb_unieje = true
			ls_uniejeaso = "";
		    ls_coduniadmsep  = "";
		    ls_denant    = "";
		    ls_uniant    = "";
			totfila=0;
			for(j=1;(j<rowbienes)&&(valido);j++)
			{
			    totfila++;
				if(j!=fila)
				{
					li_i++;
					numsolord		= eval("document.formulario.txtnumsolord"+j+".value");
					codart			= eval("document.formulario.txtcodart"+j+".value");
					denart			= eval("document.formulario.txtdenart"+j+".value");
					canart			= eval("document.formulario.txtcanart"+j+".value");
					unidad			= eval("document.formulario.cmbunidad"+j+".value");
					preart			= eval("document.formulario.txtpreart"+j+".value");
					subtotart		= eval("document.formulario.txtsubtotart"+j+".value");
					carart			= eval("document.formulario.txtcarart"+j+".value");
					totart			= eval("document.formulario.txttotart"+j+".value");
					spgcuenta		= eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica	= eval("document.formulario.txtunidad"+j+".value");
					ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
					ls_denunimed    = eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					if (li_i==1)
					   {
					     ls_uniant = ls_coduniadmsep;
						 ls_denant = ls_denuniadmsep;
					   }
					else
					   {
					     if (ls_uniant!=ls_coduniadmsep && lb_unieje==true && ls_coduniadmsep!="")
						    {
							  lb_unieje = false;
						    }
				 	   }
				    ls_denuniadmsep = eval("f.txtdenuniadmsep"+j+".value");
				    if (ls_coduniadmsep!="" && ls_coduniadmsep!='----------')
					   {
					     ls_uniejeaso = ls_uniejeaso+" "+"Nro. SEP:"+numsolord+". Unidad Solicitante: "+ls_coduniadmsep+" - "+ls_denuniadmsep+";";
					   }
					parametros=parametros+"&txtcodart"+li_i+"="+codart+"&txtdenart"+li_i+"="+denart+""+
							   "&txtcanart"+li_i+"="+canart+"&cmbunidad"+li_i+"="+unidad+""+
							   "&txtpreart"+li_i+"="+preart+"&txtsubtotart"+li_i+"="+subtotart+""+
							   "&txtcarart"+li_i+"="+carart+"&txttotart"+li_i+"="+totart+""+
							   "&txtspgcuenta"+li_i+"="+spgcuenta+"&txtunidad"+li_i+"="+unidadfisica+""+
							   "&txtnumsolord"+li_i+"="+numsolord+"&txtcoduniadmsep"+li_i+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+li_i+"="+ls_denuniadmsep+"&txtdenunimed"+li_i+"="+ls_denunimed+""+
							   "&txtestpreunieje"+li_i+"="+ls_estpreunieje;
				}
				else
				{
					codigo=eval("document.formulario.txtcodart"+j+".value");
					cuentabien=eval("document.formulario.txtspgcuenta"+j+".value");
					montobien=eval("document.formulario.txtsubtotart"+j+".value");
					montobien=ue_formato_calculo(montobien);
					numsolord=eval("document.formulario.txtnumsolord"+j+".value");
					if(numsolord!="")
					{
					  numsoldel=eval("document.formulario.numsoldel.value");
					  if(numsoldel!="")
					  {
					    ls_numsoldel=numsoldel+","+numsolord+"-"+codigo+",";
					  }
					  else
					  {
					    ls_numsoldel=numsolord+"-"+codigo;
					  }	
					  f.numsoldel.value=ls_numsoldel;
					}
				}
			}
			if (!lb_unieje && ls_codunieje!="")
			{ 
				f.txtcoduniadm.value = "----------";
				f.txtdenuniadm.value = "NINGUNA";
			}
			else
			{
				if (ls_uniant!="" && rowbienes>1)
				   {
					 f.txtcoduniadm.value = ls_uniant;
					 f.txtdenuniadm.value = ls_denant;
				   }
			}
			li_i=li_i+1;
			parametros=parametros+"&totalbienes="+li_i+"";
			f.totrowbienes.value=li_i;
	        f.txtuniejeaso.value = ls_uniejeaso;
			/////agregado 26/09/08////
			if(totfila==1)
			{
			  f.txttipsol.value="SOC";
			  f.txtcoduniadm.value=""; 
			  f.txtdenuniadm.value="";
			  f.txtconordcom.value="";
			}
			//////////////////////////
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y eliminar el seleccionado
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
				  montocargo=eval("document.formulario.txtmoncar"+j+".value"); ;
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
			rowcargos=f.totrowcargos.value;
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
				ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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
	ls_codunieje = f.txtcoduniadm.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
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
			lb_unieje 		= true
			ls_uniejeaso    = "";
		    ls_coduniadmsep = "";
		    ls_denant       = "";
		    ls_uniant       = "";
            totfila=0;
			for(j=1;(j<rowservicios)&&(valido);j++)
			{
			    totfila++;
				if(j!=fila)
				{
					li_i++;
					numsolord       = eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
					codser			= eval("document.formulario.txtcodser"+j+".value");
					denser			= eval("document.formulario.txtdenser"+j+".value");
					canser			= eval("document.formulario.txtcanser"+j+".value");
					preser			= eval("document.formulario.txtpreser"+j+".value");
					subtotser		= eval("document.formulario.txtsubtotser"+j+".value");
					carser			= eval("document.formulario.txtcarser"+j+".value");
					totser			= eval("document.formulario.txttotser"+j+".value");
					spgcuenta		= eval("document.formulario.txtspgcuenta"+j+".value");
					ls_denunimed    = eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					parametros=parametros+"&txtcodser"+li_i+"="+codser+"&txtdenser"+li_i+"="+denser+""+
										  "&txtcanser"+li_i+"="+canser+"&txtpreser"+li_i+"="+preser+""+
										  "&txtsubtotser"+li_i+"="+subtotser+"&txtcarser"+li_i+"="+carser+""+
										  "&txttotser"+li_i+"="+totser+"&txtspgcuenta"+li_i+"="+spgcuenta+""+
										  "&txtnumsolord"+li_i+"="+numsolord+"&txtcoduniadmsep"+li_i+"="+ls_coduniadmsep+""+
										  "&txtdenuniadmsep"+li_i+"="+ls_denuniadmsep+"&txtdenunimed"+li_i+"="+ls_denunimed+""+
										  "&txtestpreunieje"+li_i+"="+ls_estpreunieje;
					if (li_i==1)
					   {
				         ls_uniant = ls_coduniadmsep;
				         ls_denant = ls_denuniadmsep;
					   }
					else
					   {
					     if (ls_uniant!=ls_coduniadmsep && lb_unieje==true && ls_coduniadmsep!="")
					        {
						      lb_unieje = false;
					        }
					   }
				    if (ls_coduniadmsep!="" && ls_coduniadmsep!='----------')
					   {
					     ls_uniejeaso = ls_uniejeaso+" "+"Nro. SEP:"+numsolord+". Unidad Solicitante: "+ls_coduniadmsep+" - "+ls_denuniadmsep+";";
					   }
				}
				else
				{
				  codigo          = eval("document.formulario.txtcodser"+j+".value");
				  cuentaservicios = eval("document.formulario.txtspgcuenta"+j+".value");
				  montoservicios  = eval("document.formulario.txtsubtotser"+j+".value");
				  montoservicios  = ue_formato_calculo(montoservicios);
				  numsolord       = eval("document.formulario.txtnumsolord"+j+".value");
				  ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
				  ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
				  if (numsolord!="")
					 {
					   numsoldel=eval("document.formulario.numsoldel.value");
					   if (numsoldel!="")
					      {
					        ls_numsoldel=numsoldel+","+numsolord+"-"+codigo+",";
					      }
					   else
					  	  {
					        ls_numsoldel=numsolord+"-"+codigo;
					  	  }	
					   f.numsoldel.value=ls_numsoldel;
					 }
				}
			}
			if (!lb_unieje && ls_codunieje!="")
			{ 
				f.txtcoduniadm.value = "----------";
				f.txtdenuniadm.value = "NINGUNA";
			}
			else
			{
				if (ls_uniant!="" && rowservicios>1)
				   {
					 f.txtcoduniadm.value = ls_uniant;
					 f.txtdenuniadm.value = ls_denant;
				   }
			}
			
			li_i=li_i+1;
			parametros=parametros+"&totalservicios="+li_i+"";
			f.totrowservicios.value=li_i;
			f.txtuniejeaso.value = ls_uniejeaso;
			/////agregado 09/09/08////
			if(totfila==1)
			{
			  f.txttipsol.value="SOC";
			}
			//////////////////////////
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
					li_i++;
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
			rowcargos=f.totrowcargos.value;
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
				ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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

function ue_delete_cargos(fila,tipo)
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	if(estapro=="1")
	{
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
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
					numsolord=eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep=eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep=eval("document.formulario.txtdenuniadmsep"+j+".value");
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					ls_denunimed = eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					if(codart==codigo)
					{
						carart=ue_formato_calculo(carart);
						carart=carart-montocargo;
						subtotart=ue_formato_calculo(subtotart);
						totart=subtotart+carart;
						carart=uf_convertir(carart);
						subtotart=uf_convertir(subtotart);
						totart=uf_convertir(totart);
					}
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
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
					numsolord		= eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
					codser		    = eval("document.formulario.txtcodser"+j+".value");
					denser			= eval("document.formulario.txtdenser"+j+".value");
					canser			= eval("document.formulario.txtcanser"+j+".value");
					preser			= eval("document.formulario.txtpreser"+j+".value");
					subtotser		= eval("document.formulario.txtsubtotser"+j+".value");
					carser			= eval("document.formulario.txtcarser"+j+".value");
					totser			= eval("document.formulario.txttotser"+j+".value");
					spgcuenta    	= eval("document.formulario.txtspgcuenta"+j+".value");
					ls_denunimed 	= eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
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
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
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
				ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
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
					numsolord=eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep=eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep=eval("document.formulario.txtdenuniadmsep"+j+".value");
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica= eval("document.formulario.txtunidad"+j+".value");
					ls_denunimed = eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
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
					numsolord		= eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
					codser			= eval("document.formulario.txtcodser"+j+".value");
					denser			= eval("document.formulario.txtdenser"+j+".value");
					canser			= eval("document.formulario.txtcanser"+j+".value");
					preser			= eval("document.formulario.txtpreser"+j+".value");
					subtotser		= eval("document.formulario.txtsubtotser"+j+".value");
					carser			= eval("document.formulario.txtcarser"+j+".value");
					totser			= eval("document.formulario.txttotser"+j+".value");
					spgcuenta		= eval("document.formulario.txtspgcuenta"+j+".value");
					ls_denunimed 	= eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
							   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
							   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
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
				ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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
		alert("La Orden de Compra esta aprobada no la puede modificar !!!");
	}
	else
	{
		if(confirm("�Desea eliminar el Registro actual?"))
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
					numsolord=eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep=eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep=eval("document.formulario.txtdenuniadmsep"+j+".value");
					codart=eval("document.formulario.txtcodart"+j+".value");
					denart=eval("document.formulario.txtdenart"+j+".value");
					canart=eval("document.formulario.txtcanart"+j+".value");
					unidad=eval("document.formulario.cmbunidad"+j+".value");
					preart=eval("document.formulario.txtpreart"+j+".value");
					subtotart=eval("document.formulario.txtsubtotart"+j+".value");
					carart=eval("document.formulario.txtcarart"+j+".value");
					totart=eval("document.formulario.txttotart"+j+".value");
					spgcuenta=eval("document.formulario.txtspgcuenta"+j+".value");
					unidadfisica=eval("document.formulario.txtunidad"+j+".value");
					ls_denunimed = eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
							   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
							   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
							   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
							   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
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
					numsolord		= eval("document.formulario.txtnumsolord"+j+".value");
					ls_coduniadmsep = eval("document.formulario.txtcoduniadmsep"+j+".value");
					ls_denuniadmsep = eval("document.formulario.txtdenuniadmsep"+j+".value");
					codser			= eval("document.formulario.txtcodser"+j+".value");
					denser			= eval("document.formulario.txtdenser"+j+".value");
					canser			= eval("document.formulario.txtcanser"+j+".value");
					preser			= eval("document.formulario.txtpreser"+j+".value");
					subtotser		= eval("document.formulario.txtsubtotser"+j+".value");
					carser			= eval("document.formulario.txtcarser"+j+".value");
					totser			= eval("document.formulario.txttotser"+j+".value");
					spgcuenta	 	= eval("document.formulario.txtspgcuenta"+j+".value");
					ls_denunimed 	= eval("document.formulario.txtdenunimed"+j+".value");
					ls_estpreunieje = eval("document.formulario.txtestpreunieje"+j+".value");
					parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
							   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
							   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
							   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+
							   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+ls_coduniadmsep+""+
							   "&txtdenuniadmsep"+j+"="+ls_denuniadmsep+"&txtdenunimed"+j+"="+ls_denunimed+""+
							   "&txtestpreunieje"+j+"="+ls_estpreunieje;
				}
				parametros=parametros+"&totalservicios="+rowservicios+"";
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
				ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
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
			alert("La Orden de Compra esta aprobada no la puede modificar !!!");
		}
		else
		{
			// Obtenemos el total de filas de los bienes
			total=ue_calcular_total_fila_local("txtcodart");
			f.totrowbienes.value=total;
			// Obtenemos el total de filas de los Servicios
			total=ue_calcular_total_fila_local("txtcodser");
			f.totrowservicios.value=total;
			// Obtenemos el total de filas de los cargos
			total=ue_calcular_total_fila_local("txtcodservic");
			f.totrowcargos.value=total;
			// Obtenemos el total de filas de las cuentas
			total=ue_calcular_total_fila_local("txtcuentagas");
			f.totrowcuentas.value=total;
			// Obtenemos el total de filas de las cuentas
			total=ue_calcular_total_fila_local("txtcuentacar");
			f.totrowcuentascargo.value=total;
            
			tipord=f.tipord.value;
            tipsol=f.txttipsol.value;
			numordcom=ue_validarvacio(f.txtnumordcom.value);
			coduniadm=ue_validarvacio(f.txtcoduniadm.value);
			fecordcom=ue_validarvacio(f.txtfecordcom.value);
			
			codprov=ue_validarvacio(f.txtcodprov.value);
			conordcom=ue_validarvacio(f.txtconordcom.value);
			totalgeneral=0;
			totalcuenta=0;
			totalcuentacargo=0;
			
			if(valido)
			{
				valido=ue_validarcampo(numordcom,"El N�mero de la Orden de Compra no puede estar vacio.",f.txtnumordcom);
			}
			if(valido)
			{
				if(tipsol=='SOC')
				{
				    valido=ue_validarcampo(coduniadm,"La Unidad Solicitante no puede estar vacia.",f.txtcoduniadm);
				}
			}
			if(valido)
			{
				valido=ue_validarcampo(fecordcom,"La Fecha de la Orden de Compra no puede estar vacia.",f.txtfecordcom);
			}
			if(valido)
			{
				valido=ue_validarcampo(codprov,"El codigo del Proveedor no puede estar vacio.",f.txtcodprov);
			}
			if(valido)
			{
				valido=ue_validarcampo(conordcom,"El concepto no puede estar vacio.",f.txtconordcom);
			}
			if (valido)
			   {
			     ls_fecentordcom = f.txtfecentordcom.value;
			     if (ls_fecentordcom!='')
				    {
					  valido = ue_comparar_fechas(fecordcom,ls_fecentordcom);
					  if (!valido)
					     {
						   alert("La Fecha de Entrega debe ser mayor o igual a la Fecha de Emisi�n de la Orden de Compra !!!");
						 }
					}
			   }
			if (valido)
			   {
			     ls_perentdesde = f.txtperentdesde.value;
			     if (ls_perentdesde!='')
				    {
					  valido = ue_comparar_fechas(fecordcom,ls_perentdesde);
					  if (!valido)
					     {
						   alert("El Per�odo Entrega desde debe ser mayor o igual a la Fecha de Emisi�n de la Orden de Compra !!!");
						 }
					}
			   }
			 if (valido)
			   {
			     ls_perenthasta = f.txtperenthasta.value;
			     if (ls_perenthasta!='')
				    {
					  valido = ue_comparar_fechas(fecordcom,ls_perenthasta);
					  if (!valido)
					     {
						   alert("El Per�odo Entrega hasta debe ser mayor o igual a la Fecha de Emisi�n de la Orden de Compra !!!");
						 }
					}
			   }
			if(valido)
			{
				if(tipord=="B") // Si la orden de compra es de Bienes
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
				if(tipord=="S") // Si la orden de compra es de Servicios
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
			}
			if(valido)  //  Verificar los creditos 
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
						alert("El Monto de la Cuenta Presupuestaria "+cuenta+" Debe ser mayor que Cero.");
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
					alert("El Total de las Cuentas Presupuestarias de los cargos es distinto a los Otros Cr�ditos.");
					valido=false;
				}
				if (ls_tipafeiva=='P')
				   {
					 total = eval(totalcuenta+"+"+totalcuentacargo);
					 total = redondear(total,2);
					 if (totalgeneral!=total)
					    {
						  alert("El Total de las Cuentas Presupuestarias es distinto al total General.");
						  valido=false;
				 	    }
				   }
			}
			if(valido)
			{
				f.operacion.value="GUARDAR";
				f.action="tepuy_soc_p_registro_orden_compra.php";
				f.submit();		
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operaci�n.");
	}
}

function ue_eliminar()
{
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	lb_existe=f.existe.value;
    if((lb_existe=='TRUE')&&(li_eliminar==1))
	{
		li_estapro=f.txtestapro.value;
		ls_estatus=f.txtestatus.value;
		ls_numordcom=f.txtnumordcom.value;
		
		// Obtenemos el total de filas de los bienes
		total=ue_calcular_total_fila_local("txtcodart");
		f.totrowbienes.value=total;
		// Obtenemos el total de filas de los Servicios
		total=ue_calcular_total_fila_local("txtcodser");
		f.totrowservicios.value=total;
		// Obtenemos el total de filas de los cargos
		total=ue_calcular_total_fila_local("txtcodservic");
		f.totrowcargos.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentagas");
		f.totrowcuentas.value=total;
		// Obtenemos el total de filas de las cuentas
		total=ue_calcular_total_fila_local("txtcuentacar");
		f.totrowcuentascargo.value=total;
		
		if(ls_numordcom=="")
		{
			alert("No ha seleccionada ningun registro a eliminar.");
		}
		else
		{
			if((li_estapro=="1")&&((ls_estatus!='REGISTRO')||(ls_estatus!='EMITIDA')))
			{
				alert("La Orden de Compra esta aprobada no la puede modificar !!!");
			}
			else
			{
				lb_borrar=confirm(" Esta seguro de eliminar este registro ?");
				if(lb_borrar==true)
				{
					//alert("Eliminacion aceptada !!!");
					f.operacion.value="ELIMINAR";
					f.action="tepuy_soc_p_registro_orden_compra.php";
					f.submit();		
				}
				else
				{
				   alert("Eliminacion Cancelada !!!");
				}
			}
		}	
	}
	else
	{
		alert("No tiene permiso para realizar esta operaci�n.");
	}
}

function ue_imprimir()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
    tipord=f.tipord.value;
	lb_existe=f.existe.value;
	if(li_imprimir==1)
	{
		if(lb_existe=="TRUE")
		{
			numordcom=f.txtnumordcom.value;
			
			formato=f.formato.value;
			//establece el nuevo formato de la orden de compra
			//formato="tepuy_soc_rfs_registro_orden_compra_barvialsa.php";
                       // formato="tepuy_soc_rfs_registro_orden_compra_barinas.php";
			 formato="tepuy_soc_rfs_registro_orden_compra_waryna.php";
			tiporeporte=f.cmbbsf.value;
			window.open("reportes/"+formato+"?numordcom="+numordcom+"&tipord="+tipord+"&tiporeporte="+tiporeporte,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
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

function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
    tipord=f.tipord.value;
	if(tipord=="B")
	{
		proceso="AGREGARBIENES";
	}
	if(tipord=="S")
	{
		proceso="AGREGARSERVICIOS";
	}
	if(parametros!="")
	{
		divgrid = document.getElementById("bienesservicios");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/tepuy_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

function rellenar_cad(cadena,longitud,objeto)
{//1
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        = longitud-lencad;
	if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
			 {
			   cadena_ceros=cadena_ceros+"0";
			 }
	     cadena=cadena_ceros+cadena;
		 objeto.value=cadena;
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