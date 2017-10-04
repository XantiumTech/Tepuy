<?Php
    session_start();
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
	$oi_fun_integrador->uf_load_seguridad("MIS","tepuy_mis_p_contabiliza_ncd.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_operacion,$lo_title,$li_totrows,$oi_fun_integrador,$li_widthtable,$ls_titletable,$ls_nametable;
		global $ls_operacion,$io_ddlb,$ls_numdoc,$ls_fecdoc;
		
		$la_value=array("ND","NC");
		$la_text=array("Nota D�bito","Nota Cr�dito");
		$ls_operacion="";
        $lo_title[1]="";
		$lo_title[2]="N� Solicitud";
		$lo_title[3]="N� Recepci�n";
		$lo_title[4]="N� Nota";
		$lo_title[5]="Fecha Documento";
		$lo_title[6]="Descripci�n";
		$lo_title[7]="Detalle";
		$li_widthtable=700;
		$ls_titletable="Notas D�bito/Cr�dito por Contabilizar";
		$ls_nametable="grid";
		$ls_operacion =$oi_fun_integrador->uf_obteneroperacion();
		$li_totrows=$oi_fun_integrador->uf_obtenervalor("totalfilas",0);
		$io_ddlb=new ddlb_generic($la_value,$la_text,$ls_operacion,"cmboperacion","","150");
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
   		global $ls_numsol,$ls_numrecdoc,$ls_operacion,$ls_fecope,$ls_fecapr;
		
		$ls_numsol=$_POST["txtnumsol"];
		$ls_numrecdoc=$_POST["txtnumrecdoc"];
		$ls_operacion=$_POST["cmboperacion"];
		$ls_fecope=$_POST["txtfecope"];
		$ls_fecapr=$_POST["txtfecapr"];
		if($ls_operacion=="---")
		{
			$ls_operacion="";
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
		$aa_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
		$aa_object[$ai_totrows][2]="<input type=text name=txtnumsol".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$aa_object[$ai_totrows][3]="<input type=text name=txtnumrecdoc".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";				
		$aa_object[$ai_totrows][4]="<input type=text name=txtnumdc".$ai_totrows." class=sin-borde readonly style=text-align:left size=15 maxlength=15>";
		$aa_object[$ai_totrows][5]="<input type=text name=txtfecope".$ai_totrows." class=sin-borde readonly style=text-align:left size=15 maxlength=15>";				
		$aa_object[$ai_totrows][6]="<input type=text name=txtdesope".$ai_totrows." class=sin-borde readonly style=text-align:center size=30 maxlength=30>";
		$aa_object[$ai_totrows][7]="<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Contabilizar Notas de D&eacute;bito/Cr&eacute;dito en Cuentas por Pagar</title>
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
	require_once("class_folder/class_tepuy_cxp_integracion.php");  	
	$in_class = new class_tepuy_cxp_integracion();  
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "PROCESAR":
			uf_load_variables();
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if(array_key_exists("chksel".$li_i,$_POST))
				{
					$ls_numsol=$_POST["txtnumsol".$li_i];
					$ls_numrecdoc=$_POST["txtnumrecdoc".$li_i];		
					$ls_codtipdoc=$_POST["txtcodtipdoc".$li_i];
					$ls_ced_bene=$_POST["txtced_bene".$li_i];
					$ls_cod_pro=$_POST["txtcod_pro".$li_i];
					$ls_codope=$_POST["txtcodope".$li_i];
					$ls_numdc=$_POST["txtnumdc".$li_i];
					$ld_fecope=$_POST["txtfecope".$li_i];
					$lb_valido=$in_class->uf_procesar_contabilizacion_creditos_debitos($ls_numsol,$ls_numrecdoc,$ls_codtipdoc,
																					   $ls_ced_bene,$ls_cod_pro,$ls_codope,
																					   $ls_numdc,$ld_fecope,$la_seguridad);
					if($lb_valido)
					{
						$in_class->io_msg->message("La Nota de Cr�dito/D�bito  ".$ls_numsol."-".$ls_numdc." fue contabilizada.");
					}
					else
					{
						$in_class->io_msg->message("No se pudo contabilizar la  Nota de Cr�dito/D�bito  ".$ls_numsol."-".$ls_numdc);
					}		
				}
			}
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
		
		case "BUSCAR":
			uf_load_variables();
	    	$in_class_contabiliza->uf_select_notas_contabilizar($ls_numsol,$ls_numrecdoc,$ls_operacion,$ls_fecope,$ls_fecapr,
																$lo_object,$li_totrows,"E");
			if($li_totrows==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$in_class->uf_destroy_objects();
	unset($in_class);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">M&oacute;dulo Integrador -<em> Cuentas por Pagar </em></td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
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
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_integrador->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($oi_fun_integrador);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
  <p>&nbsp;</p>
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Contabilizaci&oacute;n de Notas Cr&eacute;dito/D&eacute;bito de Cuentas por Pagar </td>
    </tr>
    <tr>
      <td width="155"  height="23"><div align="right">N&uacute;mero de Solicitud </div></td>
      <td width="593" ><div align="left">
          <input name="txtnumsol" type="text" id="txtnumsol" onKeyUp="javascript: ue_validarcomillas(this);" maxlength="15">
      </div></td>
    </tr>
    <tr>
      <td  height="23"><div align="right">N&uacute;mero de Recepci&oacute;n </div></td>
      <td ><div align="left">
        <input name="txtnumrecdoc" type="text" id="txtnumrecdoc" onKeyUp="javascript: ue_validarcomillas(this);" maxlength="15">
      </div></td>
    </tr>
	<tr>
      <td width="155" height="23"><div align="right">Operaci&oacute;n</div></td>
      <td width="593">
      <?php $io_ddlb->uf_cargar_combo();?>	  </td>
    </tr>
    <tr>
      <td  height="23"><div align="right">Fecha Operaci&oacute;n </div></td>
      <td ><div align="left">
          <input name="txtfecope" type="text" id="txtfecope" size="14" maxlength="10" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" >
      </div></td>
    </tr>
    <tr>
      <td  height="23"><div align="right">Fecha Aprobaci&oacute;n </div></td>
      <td ><div align="left">
          <input name="txtfecapr" type="text" id="txtfecapr" size="14" maxlength="10" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" >
      </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
      <?php
		 $io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	  ?>
      </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
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
		for(li_i=1;((li_i<=li_total)&&(lb_valido==false));li_i++)
		{
			lb_valido=eval("f.chksel"+li_i+".checked");
		}
		if(lb_valido)
		{
			f.operacion.value ="PROCESAR";
			f.action="tepuy_mis_p_contabiliza_ncd.php";
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
		f.action="tepuy_mis_p_contabiliza_ncd.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_verdetalle(numsol)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	window.open("tepuy_mis_pdt_ncd.php?numsol="+numsol+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>