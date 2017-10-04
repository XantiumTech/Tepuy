<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
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
  require_once("../shared/class_folder/class_mensajes.php");
  require_once("tepuy_mis_c_contabiliza.php");  
  require_once("class_folder/class_tepuy_soc_integracion.php");  
  include("../shared/class_folder/grid_param.php");

  $io_grid = new grid_param();
  $in_class_contabiliza = new tepuy_mis_c_contabiliza();
  $in_class_int = new class_tepuy_soc_integracion();
  
  /* Begin  Scrip Seguridad */
  require_once("../shared/class_folder/tepuy_c_seguridad.php");
  $io_seguridad= new tepuy_c_seguridad();
  $arre=$_SESSION["la_empresa"];
  $ls_empresa = $arre["codemp"];
  $ls_logusr  = $_SESSION["la_logusr"];
  $ls_sistema = "MIS";
  $ls_ventanas= "tepuy_mis_p_reverso_presoc.php";
  $la_seguridad["empresa"] = $ls_empresa;
  $la_seguridad["logusr"]  = $ls_logusr;
  $la_seguridad["sistema"] = $ls_sistema;
  $la_seguridad["ventanas"]= $ls_ventanas;
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
  /* end Scrip Seguridad */

  
  if (array_key_exists("operacion",$_POST))
  {
     $ls_operacion=$_POST["operacion"];
	 $li_total_record=$_POST["hide_total_row"];
  }
  else
  {
     $ls_operacion="";
	 $li_total_record=0;  
  }
  // recorrido del grid
  if ($ls_operacion=="PROCESAR")  
  {  
		for($li_i=1;$li_i<=$li_total_record;$li_i++)
		{
			if(array_key_exists("chksel".$li_i,$_POST))
			{
				$ls_numordcom=$_POST["txtnumordcom".$li_i];
				$ls_fecordcom=$_POST["txtfecordcom".$li_i];		
				$ls_obscom=$_POST["txtobscom".$li_i];
				$lb_valido = $in_class_int-> uf_reversar_precompromiso_ordencompra( $ls_numordcom );
				if($lb_valido)		
				{
					$in_class_int->io_msg->message("El Pre-Compromiso Orden de Compra fue Reversada");
				}
				else
				{
  	 			    $in_class_int->io_msg->message("Error en el Reverso del Pre-Compromiso de la  Orden de Compra.");
				}		
			}
		}
  }
?>
<table width="799" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="797" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>  
  </tr>
</table>
<form name="form1" method="post" action="">

	<?php
	/* Begin  Scrip Seguridad */
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
	  /* End  Scrip Seguridad */
	?>	

  <p>&nbsp;</p>
  <table width="502" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Reverso de Pre-Compromiso de la Orden de Compras </td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
	  <?php
	     $title[1]="";$title[2]="Nº Compras";$title[3]="Fecha Compra";$title[4]="Concepto"; 
	     $in_class_contabiliza->uf_select_ordencompra_contabilizar( $arr_object ,$li_total_record,5);
		 $io_grid->makegrid($li_total_record,$title,$arr_object,500,"Ordenes de Compra Pre-Comprometida","grdsep" );
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td width="120"><input name="operacion" type="hidden" id="operacion">
      <input name="hide_total_row" type="hidden" id="hide_total_row" value="<?php print $li_total_record;?>"></td>
      <td width="380">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>

<script language="javascript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		f.operacion.value ="PROCESAR";
  	    f.action="tepuy_mis_p_reverso_presoc.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

</script>

<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>

