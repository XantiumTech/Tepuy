<?Php
	session_start();
	//ini_set('display_errors', 1);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_mis.php");
	$oi_fun_integrador=new class_funciones_mis();
	$oi_fun_integrador->uf_load_seguridad("SFA","tepuy_mis_p_contabiliza_sfa.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/10/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ldt_fecha,$ls_mes,$ls_operacion,$lo_title,$li_totrows,$li_filadebe,$li_filahaber,$li_filaingreso,$oi_fun_integrador,$li_widthtable,$ls_titletable,$ls_nametable;
		global $ls_tipo_operacion,$io_ddlb,$ls_fecha,$ls_numdoc,$ls_fecdoc,$li_feccondep;
		
		$ls_fecha=date("d/m/Y");
		$array_fecha=getdate();	 
		$ls_numdoc="";
		$ls_fecdoc="";
		$ls_tipo_operacion="";
	   //     $lo_title[1]="";
		$lo_title[1]="";
		$lo_title[2]="Cuenta Contable";
		$lo_title[3]="Denominaci�n de la Cuenta";
		$lo_title[4]="Monto Debe";
		$lo_title[5]="Monto Haber";
		//$lo_title[6]="Detalle";
		$li_widthtable=700;
		$ls_titletable="Acumulado de Facturas por Contabilizar";
		$ls_nametable="grid";
		$ls_operacion =$oi_fun_integrador->uf_obteneroperacion();
		$li_totrows=$oi_fun_integrador->uf_obtenervalor("totalfilas",0);
		$li_filaingreso=$oi_fun_integrador->uf_obtenervalor("totalfilasingreso",0);
		$li_filadebe=$oi_fun_integrador->uf_obtenervalor("totalfiladebe",0);
		$li_filahaber=$oi_fun_integrador->uf_obtenervalor("totalfilahaber",0);
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
		// Fecha Creaci�n: 18/03/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_fecha,$ls_mes,$ls_numdoc,$ls_fecdoc,$ls_tipo_operacion;
		
		$ls_fecha=$_POST["txtfecha"];
		$ls_numdoc=$_POST["txtnumdoc"];
		$ls_fecdoc=$_POST["txtfecdoc"];
		$ls_mes   = $_POST["mes"];
		if($ls_tipo_operacion=="---")
		{
			$ls_tipo_operacion="";
		}
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Funci�n que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
	//	$aa_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px onClick='return false;'>";
		$aa_object[$ai_totrows][1]="<input type=hidden name=txtcuentascg".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$aa_object[$ai_totrows][2]="<input type=text name=txtcuentascg1".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$aa_object[$ai_totrows][3]="<input type=text name=txtdencuentascg".$ai_totrows." class=sin-borde readonly style=text-align:left size=60 maxlength=60>";
		$aa_object[$ai_totrows][4]="<input type=text name=txtmontotaldebe".$ai_totrows." class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
		$aa_object[$ai_totrows][5]="<input type=text name=txtmontotalhaber".$ai_totrows." class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
		//$aa_object[$ai_totrows][6]="<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Contabilizaci&oacute;n de Facturas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo2 {font-size: 36px}
-->

</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/report.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion.js"></script>
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
</head>
<body>
<?php

	require_once("../shared/class_folder/ddlb_generic.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_grid = new grid_param();
	require_once("tepuy_mis_c_contabiliza.php");  
	$in_class_contabiliza = new tepuy_mis_c_contabiliza();
	//require_once("class_folder/class_tepuy_scbmov_integracion.php");  	
	//$in_class = new class_tepuy_scbmov_integracion();  
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "PROCESAR":
			uf_load_variables();
			$lb_valido=false;
			$orden=0;
			$ls_procede="SFACMP";
			$ls_comprobante=$ls_procede."-".substr($ls_fecha,0,2).substr($ls_fecha,3,2).substr($ls_fecha,6,4);
			$ls_descripcion="Registro de ingresos por ventas del ".$ls_fecha;
			$ls_codban='---';
			$ls_ctaban='-------------------------';
			$ls_fechareg=substr($ls_fecha,6,4)."-".substr($ls_fecha,3,2)."-".substr($ls_fecha,0,2)." 00:00:00";
			$lb_valido=true;
			// Procede a generar el asiento de Contabilidad //
			$li_totalasiento=0;
			$ls_debe='D';
		//print "Filas Debe: ".$li_filadebe." Filas Haber: ".$li_filahaber." Filas ingresos:".$li_filaingreso." Totasl Filas: ".$li_totrows;
			for($li_i=1;$li_i<=$li_filadebe;$li_i++)
			{
				$ls_cuentascg    =$_POST["txtcuentascg".$li_i];		
				$ls_cuentascgaux =$_POST["txtcuentascg1".$li_i];
				$ls_dencuentascg =$_POST["txtdencuentascg".$li_i];
				$li_debe=$_POST["txtmontotaldebe".$li_i];
				$li_debeaux= str_replace(".","",$li_debe);
				$li_debeaux= str_replace(",",".",$li_debeaux);
				$li_totaldebe=$li_totaldebe+$li_debeaux;
		//		print "Documento: ".$ls_comprobante." cuenta scg: ".$ls_cuentascg." Denominacion: ".$ls_dencuentascg. " Monto Bs. ".$li_debeaux." Orden: ".$orden." Fecha: ".$ls_fechareg;
				$lb_valido=$in_class_contabiliza->uf_procesar_contabilizacion_sfa_scg($ls_procede,$ls_comprobante,$ls_fechareg,$ls_cuentascg,$ls_debe,$ls_descripcion,$ls_codban,$ls_ctaban,$li_debeaux,$orden);
				$orden=$orden+1;
			}
			if($lb_valido)
			{
				$desde=$li_filadebe+1;
				$ls_haber='H';
				for($li_i=$desde;$li_i<=$li_filahaber;$li_i++)
				{
					$ls_cuentascg    =$_POST["txtcuentascg".$li_i];		
					$ls_cuentascgaux =$_POST["txtcuentascg1".$li_i];
					$ls_dencuentascg =$_POST["txtdencuentascg".$li_i];
					$li_haber=$_POST["txtmontotalhaber".$li_i];
					$li_haberaux= str_replace(".","",$li_haber);
					$li_haberaux= str_replace(",",".",$li_haberaux);
		//		print "Documento: ".$ls_comprobante." cuenta scg: ".$ls_cuentascg." Denominacion: ".$ls_dencuentascg. " Monto Bs. ".$li_debeaux." Orden: ".$orden." Fecha: ".$ls_fechareg;
					$lb_valido=$in_class_contabiliza->uf_procesar_contabilizacion_sfa_scg($ls_procede,$ls_comprobante,$ls_fechareg,$ls_cuentascg,$ls_haber,$ls_descripcion,$ls_codban,$ls_ctaban,$li_haberaux,$orden);
					$orden=$orden+1;
				}
			}
			// Fin del Asiento de Contabilidad //

			//print "comienza ingresos: ".$li_filaingreso. "Final arreglo: ".$li_totrows;
			// Procede a generar el asiento de Contabilidad Presupuestaria de Ingresos //
			if($lb_valido)
			{
				$li_totalasiento=0;
				$ls_operacion="DC";
				$orden=0;
				for($li_i=$li_filaingreso;$li_i<=$li_totrows;$li_i++)
				{
					$orden=$orden+1;
					$ls_cuentascg    =$_POST["txtcuentascg".$li_i];		
					$ls_cuentascgaux =$_POST["txtcuentascg1".$li_i];
					$ls_dencuentascg =$_POST["txtdencuentascg".$li_i];
					$li_montotaldebe=$_POST["txtmontotaldebe".$li_i];
					$li_montotaldebeaux= str_replace(".","",$li_montotaldebe);
					$li_montotaldebeaux= str_replace(",",".",$li_montotaldebeaux);
					$li_totalasiento=$li_totalasiento+$li_montotaldebeaux;
			//	print "Documento: ".$ls_comprobante." cuenta scg: ".$ls_cuentascg." Denominacion: ".$ls_dencuentascg. " Monto Bs. ".$li_montotaldebeaux." Orden: ".$orden." Fecha: ".$ls_fechareg;
					$lb_valido=$in_class_contabiliza->uf_procesar_contabilizacion_sfa_spi_insert($ls_procede,$ls_comprobante,$ls_fechareg,$ls_cuentascg,$ls_operacion,$ls_descripcion,$ls_codban,$ls_ctaban,$li_montotaldebeaux,$orden);
				}
				if($lb_valido)
				{
					$lb_valido=$in_class_contabiliza->uf_procesar_contabilizar_tepuy_cmp($ls_procede,$ls_comprobante,$ls_fechareg,$ls_cuentascg,$ls_operacion,$ls_descripcion,$ls_codban,$ls_ctaban,$li_totalasiento,$orden);
					$in_class_contabiliza->io_mensajes->message("Documento  ".$ls_comprobante." fue contabilizado.");
				}
				else
				{
					$in_class_contabiliza->io_mensajes->message("No se pudo contabilizar el documento ".$ls_comprobante);
				}
			}
			else
			{
				$in_class_contabiliza->io_mensajes->message("No se pudo contabilizar el documento ".$ls_numdoc);
			}

			// Fin del Asiento de Contabilidad Presupuestaria de Ingresos //
			/////// Inicia proceso de Generacion del resumen tepuy_cmp ////////

			///// Final del proceso de Generacion del resumen tepuy_cmp ///////
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
		
		case "BUSCAR":
			uf_load_variables();
			$li_filaingreso=0;
			$li_filadebe=0;
			$li_filahaber=0;
	$in_class_contabiliza->uf_select_facturas_contabilizar($ls_fecha,$lo_object,$li_totrows,$li_filadebe,$li_filahaber,$li_filaingreso,"1");
			//print " aqui comienza los ingresos: ".$li_filaingreso;
			if($li_totrows==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	unset($in_class);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
        <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Caja y Banco -><i>Contabiliza Movimientos Bancarios</i></td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
        <tr>
          <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.png" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_integrador->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($oi_fun_integrador);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  </p>
  <p>&nbsp;</p>
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Contabilizaci&oacute;n de Facturas</td>
    </tr>
    <tr>
<!--      <td width="155" height="23"><div align="right">N&uacute;mero de Documento </div></td>
      <td width="593"><input name="txtnumdoc" type="text" id="txtnumdoc"  onKeyUp="javascript: ue_validarcomillas(this);" maxlength="15"></td>-->
    </tr> 

    <tr>
      <td width="155" height="23"><div align="right">Fecha de Cierre </div></td>
      <td width="593"><input name="txtfecha" type="text" id="txtfecha" value="<?PHP print $ls_fecha; ?>" size="12" maxlength="10" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" ></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
      <?php
		$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
		unset($io_grid);
	  ?>
      </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
	  <input name="totalfilasingreso" type="hidden" id="totalfilasingreso" value="<?php print $li_filaingreso;?>"></td>  
	  <input name="totalfiladebe" type="hidden" id="totalfiladebe" value="<?php print $li_filadebe;?>"></td>
	  <input name="totalfilahaber" type="hidden" id="totalfilahaber" value="<?php print $li_filahaber;?>"></td>  
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		// Para verificar que se selecciono algun comprobante
		lb_valido=false;
		li_total=f.totalfilas.value;
		if(li_total>0)
		{
			f.operacion.value ="PROCESAR";
			f.action="tepuy_mis_p_contabiliza_sfa.php";
			f.submit();
		}
		else
		{
			alert("No hay nada que contabilizar.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		f.operacion.value = "BUSCAR";
		f.action="tepuy_mis_p_contabiliza_sfa.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_verdetalle(numfactura)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	window.open("tepuy_mis_pdt_sfa.php?numfactura="+numfactura+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
