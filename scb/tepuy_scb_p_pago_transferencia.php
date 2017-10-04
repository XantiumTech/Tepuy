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
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_p_pago_transferencia.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte  = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","tepuy_scb_rpp_pago_transferencia.php","C");//print $ls_reporte;
$ls_reporte1="tepuy_cheque.php";
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
<title>Pago por Transferencias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<body>
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
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="21"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" width="20" height="20" border="0" alt="Imprimir" title="Imprimir"></a></div></td>
    <td class="toolbar" width="23"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" width="20" height="20" border="0" alt="Salir" title="Salir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="664">&nbsp;</td>
  </tr>
</table>
<?php
require_once("class_funciones_banco.php");
require_once("tepuy_scb_c_emision_chq.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/ddlb_conceptos.php");
require_once("../shared/class_folder/grid_param.php");
	
$msg			 = new class_mensajes();	
$fun			 = new class_funciones();	
$lb_guardar		 = true;
$sig_inc		 = new tepuy_include();
$con			 = $sig_inc->uf_conectar();
$obj_con		 = new ddlb_conceptos($con);
$io_grid		 = new grid_param();
$in_class_emichq = new tepuy_scb_c_emision_chq();
$io_update 		 = new class_funciones_banco();
$io_fecha        = new class_fecha();
$ls_codemp 		 = $_SESSION["la_empresa"]["codemp"];
	
	require_once("tepuy_scb_c_movbanco.php");
	$in_classmovbco=new tepuy_scb_c_movbanco($la_seguridad);
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion="TR";
		$ls_documento=$_POST["txtdocumento"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		$ls_chevau=$_POST["txtchevau"];
		$ldec_montomov=$_POST["totalchq"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);
		$ls_estmov=$_POST["estmov"];		
		$ls_codconmov=$_POST["codconmov"];
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];	
		$ld_fecha=$_POST["txtfecha"];
		$ls_numchequera=$_POST["txtchequera"];
		$ls_fuente=$_POST["fuente"];
		$ls_lectura=$_POST["txtlectura"];

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
	}
	else
	{
	  $ls_operacion= "NUEVO" ;	
	  $ls_estmov="N";	
	  $ls_numchequera="-";
	  require_once("tepuy_scb_c_config.php");
	  $in_classconfig=new tepuy_scb_c_config($la_seguridad);
	  $ls_fuente=$in_classconfig->uf_select_fuente();	
	  $ls_lectura="";
	}	
	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	
	if($ls_operacion == "VERIFICAR_VAUCHER")
	{
		$ls_verifica=1;
		$ls_chevaux=$_POST["txtchevau"];
		$lb_existe=$in_classmovbco->uf_select_voucher($ls_chevaux);
		if($lb_existe)
		{
			$msg->message("Nº de Voucher ya existe, favor indicar otro");	
			$ls_chevau="";		
		}
		$ls_operacion="CARGAR_DT";
	}

if ($ls_operacion=="CARGAR_DT")
   {
      $in_class_emichq->uf_cargar_programaciones($ls_tipo,$ls_provbene,$ls_codban,$ls_cuenta_banco,&$object,&$li_rows,&$ls_desmov);
	  if (($li_rows > 0) && ($ls_codban!="") && ($ls_cuenta_banco!=""))
		 {
		   if ($_SESSION["la_empresa"]["confi_ch"]==1)
		      {
		  	    require_once("../shared/class_folder/class_mensajes.php");
		  	    $io_msg1   = new class_mensajes();
		  	    $ls_codusu = $_SESSION["la_logusr"];
				$ls_valor  = $io_update->uf_select_cheques($ls_codban,$ls_cuenta_banco,$ls_codusu,&$ls_numchequera);
		        if ($ls_valor!="")
			       {			      
				     $ls_documento = $ls_valor;
				     $ls_lectura   = "readonly";				
			       }
			    else
			       {
				     $ls_documento="";
				     ?>
	                 <script language="javascript">
			         alert("No tiene Chequera asociada !!!");	                 			
			         </script>			 
	                 <?php
			       }	
		      }		
		 }		
   }
	
	function uf_nuevo()
	{
		global $ls_mov_operacion;
		$ls_mov_operacion="TR";
	    global $la_seguridad;
		global $ls_opepre;
		$ls_opepre="";
		global $ls_documento;
		$ls_documento="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
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
		global $ls_chevau;
		require_once("tepuy_scb_c_movbanco.php");
		$in_classmovbanco=new tepuy_scb_c_movbanco($la_seguridad);
		global $ls_codemp;
		global $ldec_disponible;	
		$ldec_disponible="";	
		$ls_chevau = $in_classmovbanco->uf_generar_voucher($ls_codemp);
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $fun;
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
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
		global $li_rows;
		global $li_temp;
		global $object;
		global $ld_fecha;
		global $ls_impcheque;
		if(array_key_exists("la_deducciones",$_SESSION))
		{
			unset($_SESSION["la_deducciones"]);
		}
		$li_temp=1;	
		$li_rows=$li_temp;
		$ld_fecha=date("d/m/Y");
		$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick='return false;'>";
		$object[$li_temp][2]  = "<input type=text name=txtnumsol".$li_temp." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[$li_temp][3]  = "<input type=text name=txtconsol".$li_temp." value='' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
		$object[$li_temp][4]  = "<input type=text name=txtmonsol".$li_temp." value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
		$object[$li_temp][5]  = "<input type=text name=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";				
		$object[$li_temp][6]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=18 maxlength=18>";				
	}

	$title[1]="";   $title[2]="Solicitud";    $title[3]="Concepto Solicitud";   $title[4]="Monto Solicitud"; $title[5]="Monto Pendiente";  $title[6]="Monto a Pagar";  
	$grid="grid";	
 	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		$ls_estmov="N";	
		$ls_numchequera="-";	
		uf_nuevo();
	}
 
 if ($ls_operacion=="GUARDAR")
	{		
		if($ls_tipo=='P')
		{
			$ls_codpro=$ls_provbene;
			$ls_cedbene="----------";
		}
		else
		{
			$ls_codpro="----------";
			$ls_cedbene=$ls_provbene;
		}
		require_once("tepuy_scb_c_movbanco.php");
		$in_classmovbanco=new tepuy_scb_c_movbanco($la_seguridad);
				
		$li_totalRows				= $_POST["totalrows"];
		$arr_movbco["codban"]		= $ls_codban;
		$arr_movbco["ctaban"]		= $ls_cuenta_banco;
		$arr_movbco["mov_document"] = $ls_documento;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]	 = $ls_mov_operacion;
		$arr_movbco["fecha"]	 = $ld_fecha;
		$arr_movbco["codpro"]	 = $ls_codpro;
		$arr_movbco["cedbene"]	 = $ls_cedbene;
		$arr_movbco["monto_mov"] = $ldec_montomov;
		$arr_movbco["objret"]    = $ldec_monobjret;
		$arr_movbco["retenido"]  = $ldec_montoret;
		$arr_movbco["estmov"]    = $ls_estmov;
		$ls_codfuefin   		 = $_POST["txtcodfuefin1"];
		$lb_valido=$in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_documento,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,0,1,$ls_tipo,'SCBBCH','',"N",$ls_tipo,$ls_codfuefin);
		if ($lb_valido)
		   {
			 $lb_valido=$in_class_emichq->uf_actualizar_estatus_ch($ls_codban,$ls_cuenta_banco,$ls_documento,$ls_numchequera);
			 if (!$lb_valido)
			    {
				  $msg->message($in_class_emichq->is_msg_error);
			    }			
		   }
		
		$lb_pago=false;
		if($lb_valido)//Primer if
		{   
		    require_once("../shared/class_folder/evaluate_formula.php");
            $io_evaluate=new evaluate_formula();
			for($li_i=1;$li_i<=$li_totalRows;$li_i++)				
			{
				if(array_key_exists("chk".$li_i,$_POST))
				{
					$lb_pago=true;					
					$ls_numsol=$_POST["txtnumsol".$li_i];
					$ldec_monsol=$_POST["txtmonsol".$li_i];
					$ldec_monsol=str_replace(".","",$ldec_monsol);
					$ldec_monsol=str_replace(",",".",$ldec_monsol);
					$ldec_montopendiente=$_POST["txtmontopendiente".$li_i];
					$ldec_montopendiente=str_replace(".","",$ldec_montopendiente);
					$ldec_montopendiente=str_replace(",",".",$ldec_montopendiente);
					$ldec_monto=$_POST["txtmonto".$li_i];
					$ldec_monto=str_replace(".","",$ldec_monto);
					$ldec_monto=str_replace(",",".",$ldec_monto);
					$ls_codfuefin=$_POST["txtcodfuefin".$li_i];
					if($ldec_montopendiente==$ldec_monto)
					{
						$ls_estsol='C';	//Cancelado							
					}
					else
					{
						$ls_estsol='P';//Programado
					}
					$ld_fecemisol=$in_class_emichq->uf_select_fechasolicitud($ls_numsol);
					$lb_fechavalida=$io_fecha->uf_comparar_fecha($ld_fecemisol,$ld_fecha);
					if(!$lb_fechavalida){
					$msg->message("La Fecha de emision de la Transferencia no debe ser menor a la fecha de la Orden de Pago... Esta opcion es solo de Muestra pero va a ejecutar el proceso y queda a responsabilidad absoluta del Usuario. El Sistema TEPUY no ejerce control con respecto a este proceso...");
					$lb_fechavalida=true;} //valor que va a permitir no evaluar la fecha de pago con respecto a la programacion
					if($lb_fechavalida)
					{
						$lb_valido=$in_classmovbanco->uf_check_insert_fuentefinancimiento($_SESSION["la_empresa"]["codemp"],$ls_codban,$ls_cuenta_banco,$ls_documento,$ls_mov_operacion,$ls_estmov,$ls_codfuefin);
						if($lb_valido)
						{													
							$lb_valido=$in_class_emichq->uf_procesar_emision_chq($ls_codban,$ls_cuenta_banco,$ls_documento,$ls_mov_operacion,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estsol);
						}
						if($lb_valido)//Segundo
						{
							$ldec_montotot=($ldec_montomov-$ldec_montoret);
							$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,'SCBBCH',$ls_desmov,$ls_documento,'H',$ldec_montotot,$ldec_monobjret,true,'00000');
							if($lb_valido)//Tercer if
							{
								$ls_ctaprovbene=$in_class_emichq->uf_select_ctaprovbene($ls_tipo,$ls_provbene,&$as_codban,&$as_ctaban);
								$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctaprovbene,'CXPSOP',$ls_desmov,$ls_numsol,'D',$ldec_monto,$ldec_monobjret,true,'00000');
								
								if($lb_valido)//Cuarto if
								{	
									if(array_key_exists("la_deducciones",$_SESSION))
									{
										$la_deducciones=$_SESSION["la_deducciones"];
										$li_total=count($la_deducciones["Codded"]);
										for($i=1;$i<=$li_total;$i++)
										{
											if(array_key_exists("$i",$la_deducciones["Codded"]))
											{
												$ls_ctascg=$la_deducciones["SC_Cuenta"][$i];
												$ls_dended=$la_deducciones["Dended"][$i];
												$ls_codded=$la_deducciones["Codded"][$i];
												$ldec_objret=$la_deducciones["MonObjRet"][$i];
												$ldec_montoret=$la_deducciones["MonRet"][$i];
												$ls_formula=$la_deducciones["formula"][$i];
												$lb_bool=true;											//print "cod. ded:".$ls_codded;
												if($ls_codded!="")
												{
													/*$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctascg,'SCBBCH',$ls_dended,$ls_numsol,'H',$ldec_montoret,$ldec_objret,true,$ls_codded);*/
													$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctascg,'SCBBCH',$ls_dended,$ls_documento,'H',$ldec_montoret,$ldec_objret,true,$ls_codded);
												}
											}
										}
									}
						
								////////////////////////Detalles presupuestarios de la solicitud////////////////////////////////////////////	
									if($lb_valido)
									{								
										$ldec_monto_spg=0;
										$in_class_emichq->uf_buscar_dt_cxpspg($ls_numsol);
										if(array_key_exists("codestpro1",$in_class_emichq->ds_sol->data))
										{
										
											$li_total_rows=$in_class_emichq->ds_sol->getRowCount("codestpro1");
											for($li_x=1;$li_x<=$li_total_rows;$li_x++)
											{
												$ldec_monto_aux=$in_class_emichq->ds_sol->getValue("monto",$li_x);
												$ldec_monto_spg=$ldec_monto_spg + $ldec_monto_aux;
											}										
											$ldec_montospg2=0;
											for($li_y=1;$li_y<=$li_total_rows;$li_y++)
											{
												$ldec_monto_aux=$in_class_emichq->ds_sol->getValue("monto",$li_y);
												if($lb_valido)
												{							
													if($ls_estsol!="C")
													{
														  $ldec_MontoSpgDet = round(round($ldec_monto_aux , 2 ) *($ldec_monto  / $ldec_monto_spg),2);
														  $ldec_montospg2= $ldec_montospg2 + $ldec_MontoSpgDet;
													}
													else
													{
														$ldec_MontoSpgDet =round($ldec_monto_aux,2);
														$ldec_montospg2 = $ldec_montospg2 + $ldec_MontoSpgDet;
													}
												
													if( ($ldec_MontoSpgDet > $ldec_monto)&&($ls_estsol!="C"))
													{												
													   $ldec_MontoSpgDet = $ldec_monto;
													   $ldec_montospg2    = $ldec_MontoSpgDet;
													}
													if( ($ldec_montospg2 > $ldec_monto)&&($ls_estsol!="C"))
													{
													   $ldec_MontoSpgDet = $ldec_MontoSpgDet - ($ldec_montospg2 - $ldec_monto);
													}
													if(($ldec_montospg2 < $ldec_monto)&&($li_y==$li_total_rows)&&($ldec_montospg2!=$ldec_monto_spg))
													{
													   $ldec_MontoSpgDet = $ldec_MontoSpgDet + ($ldec_monto - $ldec_montospg2);
													}
													$ls_codestpro1=$in_class_emichq->ds_sol->getValue("codestpro1",$li_y);
													$ls_codestpro2=$in_class_emichq->ds_sol->getValue("codestpro2",$li_y);
													$ls_codestpro3=$in_class_emichq->ds_sol->getValue("codestpro3",$li_y);
													$ls_codestpro4=$in_class_emichq->ds_sol->getValue("codestpro4",$li_y);
													$ls_codestpro5=$in_class_emichq->ds_sol->getValue("codestpro5",$li_y);			
													$ls_programa=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
													$ls_cuentaspg =$in_class_emichq->ds_sol->getValue("spg_cuenta",$li_y);
													$ls_descripcion=$in_class_emichq->ds_sol->getValue("descripcion",$li_y);			
													$lb_valido=$in_classmovbanco->uf_procesar_dt_gasto($ls_codban,$ls_cuenta_banco,$ls_documento,$ls_mov_operacion,$ls_estmov,$ls_programa,$ls_cuentaspg,$ls_numsol,$ls_descripcion,'CXPSOP',$ldec_MontoSpgDet,'PG');
												}//End if								
											}//End for							
										}
									}//End quinto if
								}//Fin cuarto if
							}//fin tercer if
						}//Fin segundo if	
					} // end fechavalida
					else
					{
						//$msg->message("La Fecha de emision del cheque no debe ser menor a la fecha de la solicitud");
						$msg->message("La Fecha de emision del cheque no debe ser menor a la fecha de la solicitud... Esta opcion es solo de Muestra pero va a ejecutar el proceso y queda a responsabilidad absoluta del Usuario. El Sistema TEPUY no ejerce control con respecto a este proceso...");	//cambiado por solicitud de Yudexi	
						$lb_valido=false;
						break;
					}
				}//End if chk
			}//End for
		}//Fin primer if
		
		if($lb_valido && $lb_pago)
		{
			$ls_impcheque=$_POST["ckbimpcheque"];
			$in_classmovbanco->io_sql->commit();
			$msg->message("Movimiento registrado !!!");
			$ls_reporte1="tepuy_scb_rpp_voucher_barinas.php";
			$ls_reporte2="tepuy_cheque.php";
			?>
			<script language="javascript">

			ls_pagina2="reportes/<?php print $ls_reporte2 ?>?codban=<?php print $ls_codban ?>&ctaban=<?php print $ls_cuenta_banco ?>&numdoc=<?php print $ls_documento?>&chevau=<?php print $ls_chevau?>&impche=<?php print $ls_impcheque ?>&codope=CH";
			window.open(ls_pagina2,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");			
			</script>
			<script language="javascript">
			ls_pagina="reportes/<?php print $ls_reporte1 ?>?codban=<?php print $ls_codban ?>&ctaban=<?php print $ls_cuenta_banco ?>&numdoc=<?php print $ls_documento?>&chevau=<?php print $ls_chevau?>&impche=<?php print $ls_impcheque ?>&codope=CH";
			window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
			</script>
			<?php 
		}
		else
		{
			$in_classmovbanco->io_sql->rollback();
			if (!empty($in_classmovbanco->is_msg_error))
			   {
			     $msg->message($in_classmovbanco->is_msg_error."; No pudo registrarse el movimiento ");     
			   }
			else
			   {
			     $msg->message(" No pudo registrarse el movimiento ");
			   }
		}
		$ls_chevau="";
		uf_nuevo();			
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
?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4">Pago por Transferencia</td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="3"><table width="263" border="0" cellpadding="0" cellspacing="0" bgcolor="#E2E2E2" class="formato-blanco">
        <tr>
          <td width="261"><div align="center">
              <label>
              <input name="rb_provbene" type="radio" class="sin-borde" id="radio" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" value="P" checked <?php print $rb_p;?>>
                Proveedor</label>
              <label>
              <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
                Beneficiario</label>
              <label> </label>
              <input name="tipo" type="hidden" id="tipo">
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          <input name="txttitprovbene" type="text" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly>
      </div></td>
      <td height="22" colspan="3"><div align="left">
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
          <a href="javascript:catprovbene(2)"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.png" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="85" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
		  <input name="txtverifica" type="hidden" id="txtverifica">
      </div></td>
    </tr>
    <tr >
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr class="formato-azul" >
      <td height="22" colspan="4" style="text-align:center"><strong>Datos de la Transferencia</strong></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13" colspan="3">&nbsp;</td>
    </tr>
            <td width="91" height="14"><div align="right">
              <input name="ckbimpcheque" type="checkbox" id="ckbimpcheque" onChange="uf_cambio()"  value="1" checked <?php print $ckbimpcheque ?>>
            </div></td>
            <td colspan="2"><div align="left">Imprimir Soporte de la Transferencia </div></td>
          <tr>
            <td height="22"><div align="right">

			<?php 
			 $ls_impcheque="   Imprime";
			 if($ls_datap==true)
			 {
			?> 
              <input name="txtimpcheque" type="text" class="sin-borde" id="txtimpcheque" value="<?php print $ls_impcheque;  ?>" size="5" style="visibility:visible">
            <?php 
			 }
			 elseif($ls_datap==false)
			 {
			?>
              <input name="txtimpcheque" type="text" class="sin-borde" id="txtimpcheque" value="<?php print $ls_impcheque;  ?>" size="5" style="visibility:hidden">
			<?php 
			 }
			?>
    <tr>
      <td width="115" height="22" style="text-align:right">Banco</td>
      <td height="22" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="40" class="sin-borde" readonly>
      </td>
      <td height="22" style="text-align:right">Fecha</td>
      <td height="22" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha" value="<?php print $ld_fecha;?>" style="text-align:center" onKeyPress="javascript:currencyDate(this);" datepicker="true"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3" style="text-align:left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta Contable</td>
      <td width="376" height="22" style="text-align:left"><input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly></td>
      <td width="85" height="22" style="text-align:right">Disponible</td>
      <td width="184" height="22" style="text-align:left"><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="28" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nro. Referencia</td>
      <td height="22" style="text-align:left">
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" size="24" maxlength="15"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()[]{}#/');" <?php print $ls_lectura?> >

        <input name="estmovld" type="hidden" id="estmovld" value="<?php print $ls_estmov;?>">
        <input name="txtchequera" type="hidden" id="txtchequera" value="<?php print $ls_numchequera;?>">
		<input name="txtlectura" type="hidden" id="txtlectura" value="<?php print $ls_lectura;?>">
      </td> 
<!--      <td height="22" style="text-align:right">Nro. de Referencia</td>
      <td height="22" style="text-align:left"><input name="txtchevau" type="text" id="txtchevau" size="28" maxlength="25" value="<?php print $ls_chevau;?>" style="text-align:center" onChange="javascript:ue_verificar_vaucher()" onBlur="javascript:rellenar_cad(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'0123456789'); "></td>-->
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td height="22" colspan="3" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
    </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto Movimiento</td>
      <td height="22" colspan="3"><input name="txtconcepto" type="text" id="txtconcepto"  onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')" value="<?php print $ls_desmov;?>" size="117" maxlength="454">      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Total</td>
      <td height="22"><input name="totalchq" type="text" id="totalchq" style="text-align:right" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" readonly>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          
        M.O.R 
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_validar_monobjret(this);" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="28"></td>
      <td height="22" style="text-align:right">Monto Retenido</td>
      <td height="22"><input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly>
   <!--   <a href="javascript:uf_cat_deducciones();"><img src="../shared/imagebank/tools15/buscar.png" alt="Catalogo de deducciones" width="15" height="15" border="0">--></a></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13"></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>                                              
      <td height="22" colspan="4"><div align="center"><?php $io_grid->make_gridScroll($li_rows,$title,$object,762,'Solicitudes Programadas',$grid,100);?>
        <input name="fila_selected" type="hidden" id="fila_selected">
        <input name="totalrows" type="hidden" id="totalrows" value="<?php print $li_rows;?>">
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
    <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
	<input name="fuente" type="hidden" id="fuente" value="<?php print $ls_fuente;?>">	
  </p>
  </form>
</body>
<script language="javascript">
f=document.form1;
function ue_nuevo()
{
f.operacion.value ="NUEVO";
f.action="tepuy_scb_p_pago_transferencia.php";
f.submit();
}

function ue_guardar()
{
	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	//ls_chevau=f.txtchevau.value;
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	ls_concepto=f.txtconcepto.value;
	ldec_totalchq=f.totalchq.value;
	//alert(ldec_totalchq);
	ldec_totalchq=uf_convertir_monto(ldec_totalchq);
	if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_concepto!="")&&(ldec_totalchq>0))
	{
		f.operacion.value ="GUARDAR";
		f.action="tepuy_scb_p_pago_transferencia.php";
		f.submit();		
	}
	else
	{
		alert("Complete los datos de la Transferencia");
	}
}

function ue_imprimir()
{
	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	ls_codope='CH';
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;	
	ls_chevau=f.txtchevau.value;	
	ls_impcheque=f.txtimpcheque.value;
	if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
	{
		ls_pagina="reportes/tepuy_cheque.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope+ls_impcheque;
		
		window.open(ls_pagina,"catalogo","menubar=yes,toolbar=yes,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Seleccione un documento válido, o que ya este registrado !!!");
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="tepuywindow_blank.php";
	f.submit();
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
{
	if(cadena!="")
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
		if(campo=="voucher")
		{
			document.form1.txtchevau.value=cadena;
		}
	}
}	

function cat_cheque()
{
  ls_codban=f.txtcodban.value;
  ls_ctaban=f.txtcuenta.value;	   
  if ((ls_codban!="")&&(ls_ctaban!=""))
     {
	   pagina="tepuy_cat_cheques.php?codban="+ls_codban+"&ctaban="+ls_ctaban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no"); 
     }
}
	
//Catalogo de cuentas contables
function catalogo_cuentabanco()
{
  ls_codban = f.txtcodban.value;
  ls_denban = f.txtdenban.value;
  if (ls_codban!="")
	 {
	   pagina="tepuy_cat_ctabanco2.php?codigo="+ls_codban+"&hidnomban="+ls_denban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
  else
	 {
	   alert("Seleccione el Banco !!!");   
	 }
}
	 
function cat_bancos()
{
  pagina="tepuy_cat_bancos.php";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
}
   
function catprovbene(tipo)
{
	f=document.form1;
	if(f.rb_provbene[0].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("tepuy_cat_prog_proveedores_trans.php?tipo="+tipo,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=450,left=50,top=50,location=no,resizable=yes");
	}
	else if(f.rb_provbene[1].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("tepuy_cat_prog_beneficiario_trans.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=450,left=50,top=50,location=no,resizable=yes");
	}	
}   

function uf_verificar_provbene(lb_checked,obj)
{
	f=document.form1;
    
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
	f.operacion.value="CARGAR_DT";	
	f.action="tepuy_scb_p_pago_transferencia.php";
	f.submit();
}

function uf_selected(li_i)
{
	f.fila_selected.value=li_i;
	ldec_monto= eval("f.txtmontopendiente"+li_i+".value");
	ls_numsol = eval("f.txtnumsol"+li_i+".value");
	uf_calcular();
	update_concepto();
}

function uf_actualizar_monto(li_i)
{
	f=document.form1;
	ldec_monto= eval("f.txtmonto"+li_i+".value");
	ldec_montopendiente= eval("f.txtmontopendiente"+li_i+".value");
	ldec_temp1=ldec_monto;
	ldec_temp2=ldec_montopendiente;
	while(ldec_temp1.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_temp1=ldec_temp1.replace(".","");
	}
	ldec_temp1=ldec_temp1.replace(",",".");
	while(ldec_temp2.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_temp2=ldec_temp2.replace(".","");
	}
	ldec_temp2=ldec_temp2.replace(",",".");

	if(parseFloat(ldec_temp1)<=parseFloat(ldec_temp2))
	{
		eval("f.txtmonto"+li_i+".value='"+uf_convertir(ldec_temp1)+"'");
	}
	else
	{
		alert("Monto a cancelar no puede ser mayor al monto pendiente");
		eval("f.txtmonto"+li_i+".value='"+ldec_montopendiente+"'");	
		eval("f.txtmonto"+li_i+".focus()");
	}
	uf_calcular();
}
	
function uf_calcular()
{
	f=document.form1;
	li_total=f.totalrows.value;
	ldec_total=0;
	for(i=1;i<=li_total;i++)
	{
		if(eval("f.chk"+i+".checked"))
		{
			ldec_monto=eval("f.txtmonto"+i+".value");
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			ldec_total=parseFloat(ldec_total)+parseFloat(ldec_monto);

		}
	}
	f.totalchq.value=uf_convertir(ldec_total);
	f.txtmonobjret.value=uf_convertir(ldec_total);
}
   
function uf_cat_deducciones() 
{
   f=document.form1;  
   if(f.fuente.value=="B")
   {
	   ls_documento=f.txtdocumento.value;
	   ldec_monto=f.totalchq.value;
	   ldec_monto=uf_convertir_monto(ldec_monto);//Lo convierto a decimal separado solo por punto( . )
	   ldec_monobjret=f.txtmonobjret.value;	
	   ls_origen="1";
	   ldec_monobjret=uf_convertir_monto(ldec_monobjret);//Lo convierto a decimal separado solo por punto( . )
	   if((ls_documento!="")&&(ldec_monto>0)&&(ldec_monobjret>0)&&(ldec_monto>=ldec_monobjret))   
	   {
		   ldec_monto=uf_convertir(ldec_monto);//Lo convierto a formato decimal con separdores de miles y decimales
		   ldec_monobjret=uf_convertir(ldec_monobjret);//Lo convierto a formato decimal con separdores de miles y decimales
		   pagina="tepuy_cat_deducciones.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&origen="+ls_origen
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	   }
	   else
	   {
			if(ls_documento=="")
			{
				alert("Introduzca un numero de documento");
			}
			else if(ldec_monto<=0)
			{
				alert("El monto de be ser mayor a cero(0)");
			}
			else if(ldec_monobjret<=0)
			{
				alert("La base imponible debe ser mayor a cero(0)");
			}
			else if(ldec_monto<ldec_monobjret)
			{
				alert("Base imponible no puede ser mayor al monto del documento");
				f.txtmonobjret.value=uf_convertir(ldec_monto);
			}
	   }
   }
   else
   {
		alert("Las retenciones municipales deben aplicarse a través del módulo de Ordenes de pago");
   }
}
	
function uf_validar_monobjret(txtmonobjret)
{
	f=document.form1;
	ldec_monobjret=txtmonobjret.value;
	ldec_monto=f.totalchq.value;
	ldec_monobjret=uf_convertir_monto(ldec_monobjret);
	ldec_monto=uf_convertir_monto(ldec_monto);
	if(ldec_monto>=ldec_monobjret)
	{
		txtmonobjret.value=uf_convertir(ldec_monobjret);
	}
	else
	{
		txtmonobjret.value=uf_convertir(ldec_monto);
		alert("Monto Objeto a Retención no puede ser mayor al monto total del Cheque.");
		txtmonobjret.focus();
	}	
}
	
function ue_verificar_vaucher()
{
	f.operacion.value="VERIFICAR_VAUCHER";
	f.submit();
}

function update_concepto()
{
  li_totchk  = 0;
  li_totrows = f.totalrows.value;
  for (i=1;i<=li_totrows;i++)
      {
	    if (eval("f.chk"+i+".checked"))
		   {
		     li_totchk++;
		     ls_concepto = eval("f.txtconsol"+i+".value");
		   }
	  }
  if (li_totchk==1)
     {
	   f.txtconcepto.value = ls_concepto;
	 } 
  else
     {
       f.txtconcepto.value="";
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
			//alert(ls_long);


  //  return false; 
   }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
