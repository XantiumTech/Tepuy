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
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_p_progpago.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Programaci&oacute;n de Pagos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>
<body>
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
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td class="toolbar" width="25"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("tepuy_scb_c_progpago.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/ddlb_meses.php");
	$io_cmbmes=new ddlb_meses();
	$io_retencion=new tepuy_scb_c_progpago;
	$msg        = new class_mensajes();	
	$fun        = new class_funciones();	
	$lb_guardar = true;
	$io_grid    = new grid_param();
	$ds_sol     = new class_datastore();
	$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
	$io_fecha   = new class_fecha();
	
	require_once("tepuy_scb_c_progpago.php");
	$io_sol=new tepuy_scb_c_progpago($la_seguridad);

	if (array_key_exists("chktipvia",$_POST))
	   {
		 $li_tipvia = $_POST["chktipvia"];	   
	   }
    else
	   {
	     $li_tipvia = 0;
	   }
	if ($li_tipvia=='0')
	   {
	     $ls_checked = '';
	   }
	else
	   {
	     $ls_checked = 'checked';
	   }

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion		= $_POST["operacion"];
		$ld_fecha           = $_POST["txtfecha"];
		$ls_codban			= $_POST["txtcodban"];
		$ls_denban			= $_POST["txtdenban"];
		$ls_cuenta_banco    = $_POST["txtcuenta"];
		$ls_dencuenta_banco = $_POST["txtdenominacion"];
		$ls_tipo            = $_POST["rb_provbene"];
		$ls_mes           = $_POST["mes"];
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;
		$ls_mov_operacion="NC";
	    $ls_seleccionado="";
		$ls_codban="";
		$ls_denban="";
		$ls_cuenta_banco="";
		$ls_dencuenta_banco="";	
		$ls_tipo='-';
		$ls_desproben="Ninguno";
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		$lastscg=0;
		$li_total=1;
		$i=1;
		$ldec_total_prog=0;
		
		$object[$i][1] = "<input type=checkbox name=chksel".$i."    id=chksel".$i."        value=1>";		
		$object[$i][2] = "<input type=text name=txtnumsol".$i."     id=txtnumsol".$i."     value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[$i][3] = "<input type=text name=txtmonsol".$i."     id=txtmonsol".$i."     value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
		$object[$i][4] = "<input type=text name=txtsaldo".$i."     id=txtsaldo".$i."     value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
		$object[$i][5] = "<input type=text name=txtfecsol".$i."     id=txtfecsol".$i."     value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
		$object[$i][6] = "<input type=text name=txtcodproben".$i."  id=txtcodproben".$i."  value=''  class=sin-borde readonly style=text-align:center size=60 maxlength=60>";			
		$object[$i][7] = "<input type=text name=txtfecprog".$i."    id=txtfecprog".$i."    value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
		
	}
	if($ls_tipo=='P')
	{
		$rb_prov	 = "checked";
		$rb_bene	 = "";
	    $ls_disabled = "disabled";
	}
	elseif($ls_tipo=='B')
	{
		$rb_prov	 = "";
		$rb_bene     = "checked";
	    $ls_disabled = "";
	}
	else
	{
		$rb_prov	 = "";
		$rb_bene	 = "";
	    $ls_disabled = "disabled";
	}
	//Declaraci�n de parametros del grid.
	$titleProg[1]="Check";   
	$titleProg[2]="Solicitud";     
	$titleProg[3]="Monto";     
	$titleProg[4]="Saldo";     
	$titleProg[5]="Fecha";  	
	$titleProg[6]="Proveedor\Beneficiario";  
	$titleProg[7]="Fecha Prog.";   
    $gridProg="grid_prog";
	
if ($ls_operacion == "GUARDAR")
   {
	 $li_total    = $_POST["totsol"];
	 $lb_valido   = true;
	 $io_dssolvia = new class_datastore();
	 $io_sol->SQL->begin_transaction();
	 for ($i=0;($i<=$li_total)&&($lb_valido);$i++)
	     {
		   if (array_key_exists("chksel".$i,$_POST))
		      {
			    $ls_numsol    = $_POST["txtnumsol".$i];
				$ldec_monto   = $_POST["txtmonsol".$i];
				$li_saldo     = $_POST["txtsaldo".$i];
				$ls_codban    = $_POST["txtcodban"];
				$ls_ctaban    = $_POST["txtcuenta"];
				$ls_provbene  = $_POST["txtcodproben".$i];
				$ls_tipo      = $_POST["rb_provbene"];
				$ld_fecpropag = $_POST["txtfecprog".$i];
				$ld_fecsol    = $_POST["txtfecsol".$i];
				if ($ld_fecpropag!="")	
				   {
					 $lb_fechaok=$io_fecha->uf_comparar_fecha($ld_fecsol,$ld_fecpropag);
					 if($lb_fechaok)
					 {
						$ls_estmov='P';				
						$lb_valido=$io_sol->uf_procesar_programacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo,$li_tipvia);
					 }
					 else
					 {
					 	$msg->message("ERROR-> La Fecha de Programacion no debe ser menor a la fecha de la solicitud");			
					 }
				   }
				else
				   {
					 $msg->message("Seleccione la, solicitud a programar y asigne la fecha de programaci�n");			
				   }
		      }			
	     }		
	if ($lb_valido)
	   {
		 $io_sol->SQL->commit();	
		 $msg->message("El movimiento fue registrado");
	   }
	else
	   {
		 $io_sol->SQL->rollback();
		 $msg->message($io_sol->is_msg_error);
	   }
   }

	if(($ls_operacion=="CAMBIO_TIPO")||($ls_operacion=="GUARDAR"))
	{
		//Cargo los datos de las programaciones.
		//print "mes->".$ls_mes;
		$data=$io_sol->uf_cargar_solicitudes($ls_codemp,$ls_tipo,$li_tipvia,$ls_mes);		
		$ldec_total_prog=0;
		if($data!="")
		{
			$ds_sol->data=$data;
			$li_total=$ds_sol->getRowCount("numsol");
			for($i=1;$i<=$li_total;$i++)
			{
				$ls_numsol  	= trim($ds_sol->getValue("numsol",$i));
				$ldec_monsol	= trim($ds_sol->getValue("monsol",$i));
				$ldec_moncan	= 0;
				$ld_fecsol  	= $fun->uf_convertirfecmostrar(trim($ds_sol->getValue("fecemisol",$i)));
				$ls_tipproben   = $ds_sol->getValue("tipproben",$i);
				$ls_provbene	= trim($ds_sol->getValue("codproben",$i));
				$li_montonotas=0;
				$lb_valido=$io_sol->uf_load_notas_asociadas($ls_codemp,$ls_numsol,$li_montonotas);	
				$li_saldo=$ldec_monsol+$li_montonotas;
				if ($ls_tipproben=='P')
				   {
				     $ls_nomprovbene = $ds_sol->getValue("nomproben",$i);
				   }
				else
				   {
				     $ls_nomben = $ds_sol->getValue("nombene",$i);
					 $ls_apeben = $ds_sol->getValue("apebene",$i);
				     if (!empty($ls_apeben))
					    {
						  $ls_nomprovbene = $ls_apeben.', '.$ls_nomben;
						}
				     else
					    {
						  $ls_nomprovbene = $ls_nomben;
						}
				   }
				$ld_fecpropag   = '';
				$ls_procede     = $ds_sol->getValue("procede",$i);
				$object[$i][1]  = "<input type=checkbox name=chksel".$i."      id=chksel".$i."        value=1 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>";		
				$object[$i][2]  = "<input type=text   name=txtnumsol".$i."     id=txtnumsol".$i."     value='".$ls_numsol."'     class=sin-borde readonly style=text-align:center size=15 maxlength=15 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>";
				$object[$i][3]  = "<input type=text   name=txtmonsol".$i."     id=txtmonsol".$i."     value='".number_format($ldec_monsol,2,",",".")."'    class=sin-borde readonly style=text-align:right size=18 maxlength=18 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>";
				$object[$i][4]  = "<input type=text   name=txtsaldo".$i."     id=txtsaldo".$i."     value='".number_format($li_saldo,2,",",".")."'    class=sin-borde readonly style=text-align:right size=18 maxlength=18 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>";
				$object[$i][5]  = "<input type=text   name=txtfecsol".$i."     id=txtfecsol".$i."     value='".$ld_fecsol."'     class=sin-borde readonly style=text-align:center size=10 maxlength=10 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>"; 
				$object[$i][6]  = "<input type=hidden name=txtcodproben".$i."  id=txtcodproben".$i."  value='".$ls_provbene."'><input type=text name=txtnomprovbene".$i." value='".$ls_nomprovbene."'    class=sin-borde readonly style=text-align:left size=60 maxlength=60 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);>";			
				$object[$i][7]  = "<input type=text   name=txtfecprog".$i."    id=txtfecprog".$i."    value='".$ld_fecpropag."'  class=sin-borde readonly style=text-align:center size=10 maxlength=10 onClick=javascript:uf_registrar($i,'$ls_numsol','$li_saldo','$ld_fecsol','$ls_provbene',this);><input type=hidden name=hidprocede".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:left size=10 maxlength=6;>";			
			}
		}	
		else
		{
				$i=1;
				$object[$i][1] = "<input type=checkbox name=chksel".$i."    id=chksel".$i."        value=1>";		
				$object[$i][2] = "<input type=text name=txtnumsol".$i."     id=txtnumsol".$i."     value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$i][3] = "<input type=text name=txtmonsol".$i."     id=txtmonsol".$i."     value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
				$object[$i][4] = "<input type=text name=txtsaldo".$i."      id=txtsaldo".$i."     value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
				$object[$i][5] = "<input type=text name=txtfecsol".$i."     id=txtfecsol".$i."     value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
				$object[$i][6] = "<input type=text name=txtcodproben".$i."  id=txtcodproben".$i."  value=''  class=sin-borde readonly style=text-align:center size=60 maxlength=60>";			
				$object[$i][7] = "<input type=text name=txtfecprog".$i."    id=txtfecprog".$i."    value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10><input type=hidden name=hidprocede".$i." value='' class=sin-borde readonly style=text-align:left size=10 maxlength=6;>";			
				$li_total=1;
		}
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
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla">
    <tr class="titulo-ventana">
      <td height="22" colspan="4">Programaci&oacute;n de Pagos 
      <input name="operacion" type="hidden" id="operacion"></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
        <tr>
          <td width="66" height="22"><div align="right">Buscar las del Mes de:
          </div></td>
          <td width="113"><div align="left">
            	<?php $io_retencion->uf_cmb_mes($ls_mes); //Combo que contiene los meses del a�o y retorna selecciona el que el ususario tenga acutalmente ?>
          </div></td>
              </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="2"><p>
        <label>
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="P" <?php print $rb_prov;?>>
  Proveedor</label>
        <label>
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="B" <?php print $rb_bene;?>>
  Beneficiario</label>
        <br>
      </p></td>
      <td width="208" height="22">Tipo Vi&aacute;tico 
        <label>
        <input name="chktipvia" type="checkbox" class="sin-borde" id="chktipvia" value="1" <?php print $ls_checked; ?><?php print $ls_disabled ?> onClick="javascript:uf_cambiar();">
      </label></td>
    </tr>
    <tr>
      <td width="75" height="22"><div align="right">Banco</div></td>
      <td height="22" colspan="3"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
      <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="70" class="sin-borde" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td height="22" colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
        <input name="txtdenominacion"  type="text"   class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
        <input name="txttipocuenta"    type="hidden" id="txttipocuenta">
        <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      <input name="txtcuenta_scg"    type="hidden" id="txtcuenta_scg"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Disponible</div></td>
      <td height="22"><div align="left">
        <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" size="22" readonly>
      </div></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>

    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr class="formato-azul">
      <td height="22" colspan="4"><div align="center"><strong>Detalles Solicitudes </strong></div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Solicitud</div></td>
      <td width="284" height="22"><div align="left">
        <input name="txtnumsol" type="text" id="txtnumsol" style="text-align:center" size="22" readonly >
</div></td>
      <td width="211" height="22"><div align="right">Fecha</div></td>
      <td height="22"><div align="left">
        <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" size="22" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Prov/Bene</div></td>
      <td height="22"> <div align="left">
           <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" size="22" readonly>
</div></td>
      <td height="22"> <div align="right">Fecha Programada </div></td>
      <td height="22"> <input name="txtfecpropag" type="text" id="txtfecpropag" size="22" maxlength="10" style="text-align:center" onKeyPress="javascript:currencyDate(this);" datepicker="true">
      <label></label></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td height="22"><div align="left">
        <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" readonly>
</div></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;&nbsp;</td>
      <td height="22"><a href="javascript:uf_procesar();"><img src="../shared/imagebank/aprobado.png" alt="Aceptar" width="15" height="15" border="0">Procesar Programaci&oacute;n</a> <img src="../shared/imagebank/mas.gif" alt="Aplicar a Todas" width="9" height="17"><a href="javascript:uf_aplicar_all();">Aplicar Fecha a Todas</a> </td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>                                              
      <td height="22" colspan="4"><div align="center"><?php $io_grid->make_gridScroll($li_total,$titleProg,$object,762,'Solicitudes a Programar ',$gridProg,120);?>
          <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_total?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right"><strong>Total Programaci&oacute;n </strong></div></td>
      <td height="22">
        <div align="left">
          <input name="txttotalprog" type="text" id="txttotalprog" style="text-align:right" value="<?php print number_format($ldec_total_prog,2,",",".");?>" readonly>
        </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  </form>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="tepuy_scb_p_progpago.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	ls_codban=f.txtcodban.value;
	ls_cuenta=f.txtcuenta.value;
	/*if(!"<?php print $lb_guardar;?>")
	{
	alert("No tiene derechos para registrar cheques");
	}
	else
	{
	*/
	ldec_monto=f.txttotalprog.value;
	while(ldec_monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_monto=ldec_monto.replace(".","");
	}
	ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
	
	if((ldec_monto!="")&&(ldec_monto>0))
	{
		if((ls_codban!="")&&(ls_cuenta!=""))
		{
			f.operacion.value ="GUARDAR";
			f.action="tepuy_scb_p_progpago.php";
			f.submit();
		}
		else
		{
			alert("Seleccione el banco y la cuenta");
		}
	}
	else
	{
		alert("Monto Programado debe ser mayor a 0");
	}
//}
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
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="chequera")
		{
			document.form1.txtchequera.value=cadena;
		}
		if(campo=="numcheque")
		{
			document.form1.txtnumcheque.value=cadena;
		}
		if(campo=="desde")
		{
			document.form1.txtdesde.value=cadena;
		}
		if(campo=="hasta")
		{
			document.form1.txthasta.value=cadena;
		}
		
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="tepuy_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	  
	 }
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="tepuy_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
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
   
   
   
  function currencyNumber(number)
  { 

	var ls_number=number;
	li_long=ls_number.length;
	f=document.form1;
	li_ocurrencia=ls_number.indexOf('.');		 

		if(li_ocurrencia>0)
		{
			li_ant=ls_number.substr(ls_number,li_ocurrencia,li_long-li_ocurrencia);
			alert(li_ant);
			alert(ls_number.substr(ls_number,0,li_long-li_ant));
			
		}
			//alert(ls_long);
  //  return false; 
   }
   function uf_verificar_operacion()
   {
   	f=document.form1;
	f.operacion.value="CAMBIO_OPERA";
	f.submit();   
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
	if((f.rb_provbene[2].checked)&&(obj!='N'))
	{
		f.txtprovbene.value="----------";
		f.txtdesproben.value="Ninguno";
		f.tipo.value='N';
		f.txttitprovbene.value="";
	}
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
			ls_tipo='N'
		}
		
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo))
		{
			ls_pagina = "tepuy_w_regdt_presupuesto.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=585,height=245,left=50,top=50,location=no,resizable=no,dependent=yes");
		}
		else
		{
			alert("Complete los datos del comprobante");
		}

   }
   
   function  uf_agregar_dtcon()
   {
   		f=document.form1;
		total=f.totcon.value;
		ls_pagina = "tepuy_w_regdt_contable.php?txtprocedencia=SCBMOV&totalcon="+total;
		window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=182,left=50,top=50,location=no,resizable=no,dependent=yes");
   }
 
   function uf_objeto(obj)
   {
   		alert(obj.name);   
   }
   
   function uf_registrar(fila,ls_numsol,ldec_monto,ld_fecsol,ls_provbene,obj)
   {
   		f=document.form1;
		
		if((obj.name!=('chksel'+fila)))
		{
			f.txtnumsol.value=ls_numsol;
			f.txtmonto.value =uf_convertir(ldec_monto);
			f.txtprovbene.value=ls_provbene;
			f.txtfecha.value = ld_fecsol;
			f.fila.value=fila;
			eval("f.chksel"+fila+".checked=false");
		}
		else
		{
			if(eval("f.chksel"+fila+".checked"))
			{
				f.txtnumsol.value=ls_numsol;
				f.txtmonto.value =uf_convertir(ldec_monto);
				f.txtprovbene.value=ls_provbene;
				f.txtfecha.value = ld_fecsol;
				f.fila.value=fila;
			}
			else
			{
				f.txtnumsol.value="";
				f.txtprovbene.value="";
				f.txtfecha.value = "";
				f.fila.value=0;
				f.txtmonto.value =uf_convertir(0);
			}
		}
		uf_calcular_total();
   }
   
   function uf_calcular_total()
   {
		f=document.form1;
		ldec_total=0;
		li_total=f.totsol.value;
		for(i=1;i<=li_total;i++)
		{
			if(eval("f.chksel"+i+".checked"))
			{				
				ldec_monto=eval("f.txtmonsol"+i+".value");
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
				ldec_total=parseFloat(ldec_monto) + parseFloat(ldec_total);
			}
		}	
		f.txttotalprog.value=uf_convertir(ldec_total);
   }

   function fill_cad(cadena,longitud)
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
	   return cadena;
   }
   function uf_procesar()
   {
   		f=document.form1;
		li_totsol=f.totsol.value;
		fila=f.fila.value;
		ldec_monto=f.txtmonto.value;
		ls_numsol=f.txtnumsol.value;
		ls_provbene=f.txtprovbene.value;
		ls_fecha=f.txtfecha.value;
		ld_fecprog=f.txtfecpropag.value;
		li_totsol=f.totsol.value;
		if(ls_numsol!="")
		{
				
			lb_valido=uf_verificar_fechas(ls_fecha,ld_fecprog);
			if(!lb_valido)
			{
				alert("Fecha de programaci�n debe ser mayor a la de la solicitud");
				return;
			}
			else
			{
				var ld_fecnow=new Date();
				ld_fec=fill_cad(ld_fecnow.getDate(),2)+"/"+fill_cad((ld_fecnow.getMonth()+1),2)+"/"+ld_fecnow.getFullYear();
				lb_valido=uf_verificar_fechas(ld_fec,ld_fecprog);
			}
			if((ldec_monto!="")&&(ls_numsol!="")&&(ls_fecha!="")&&(ls_provbene!=""))
			{
					eval("f.chksel"+fila+".checked=true");
					eval("f.txtfecprog"+fila+".value='"+ld_fecprog+"'");
					f.txtmonto.value="";
					f.txtnumsol.value="";
					f.txtfecha.value="";
					f.txtprovbene.value="";
	
			}
			else
			{
				alert("Complete o seleccione los datos de la solicitud a programar");
			}
			uf_calcular_total();
		}
			
   }
   function uf_aplicar_all()
   {	
	  	f=document.form1;
		li_totsol=f.totsol.value;
		ld_fecprog=f.txtfecpropag.value;
		ls_fecha=f.txtfecha.value;
		li_totsol=f.totsol.value;
		lb_valido=uf_verificar_fechas(ls_fecha,ld_fecprog);
		if(!lb_valido)
		{
			alert("Fecha de programaci�n debe ser mayor a la de la solicitud");
			return;
		}
		else
		{
			var ld_fecnow=new Date();
			ld_fec=fill_cad(ld_fecnow.getDate(),2)+"/"+fill_cad((ld_fecnow.getMonth()+1),2)+"/"+ld_fecnow.getFullYear();
			lb_valido=uf_verificar_fechas(ld_fec,ld_fecprog);
			
			/*if(!lb_valido)
			{
				alert("Fecha de  programaci�n debe ser mayor a actual");
				return;
			}*/
		}
		for(li_i=1;li_i<=li_totsol;li_i++)
		{
			if(eval("f.chksel"+li_i+".checked==true"))
			{
				eval("f.txtfecprog"+li_i+".value='"+ld_fecprog+"'");
			}
		}
		uf_calcular_total();		  
   }
   
   function uf_verificar_fechas(ld_fec1,ld_fec2)
   {
		ls_dia=ld_fec1.substr(0,2);
		li_dia1 =parseInt(ls_dia,10);
		ls_mes=ld_fec1.substr(3,2);
		li_mes1 =parseInt(ls_mes,10);
		ls_agno=ld_fec1.substr(6,4);
		li_agno1=parseInt(ls_agno,10);
		ls_dia  =ld_fec2.substr(0,2);
		li_dia2 =parseInt(ls_dia,10);
		ls_mes  =ld_fec2.substr(3,2);
		li_mes2 =parseInt(ls_mes,10);
		ls_agno=ld_fec2.substr(6,4);
		li_agno2=parseInt(ls_agno,10);

	   if(li_agno2>=li_agno1)
	   {
			if(li_mes2>li_mes1)
			{
				return true;
			}
			else if(li_mes2==li_mes1)
			{
				if(li_dia2>=li_dia1)
				{
					return true;
				}
				else if((li_dia2<li_dia1)&&(li_agno2>li_agno1))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else if((li_mes2<li_mes1)&&(li_agno2>li_agno1))
			{	
				return true;
			}
			else
			{
				return false;
			}   		
	   }
	   else
	   {
			return false;
	   }
   
   }
   function currencyFormat(fld, milSep, decSep, e)
   { 
		var sep = 0; 
		var key = ''; 
		var i = j = 0; 
		var len = len2 = 0; 
		var strCheck = '0123456789'; 
		var aux = aux2 = ''; 
		var whichCode = (window.Event) ? e.which : e.keyCode; 
		if (whichCode == 13) return true; // Enter 
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
	
	function uf_cambiar()
	{
		f=document.form1;
		if (f.rb_provbene[1].selected==true)
		   {
		     f.chktipvia.readOnly=false;
		   }
		else
		   {
		     f.chktipvia.readOnly=true;
		   }
		f.operacion.value="CAMBIO_TIPO";
		f.action="tepuy_scb_p_progpago.php";
		f.submit();
	}
	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>