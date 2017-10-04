<?Php
   session_start();
   if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }    
   $ls_logusr=$_SESSION["la_logusr"];
   include("../shared/class_folder/grid_param.php");
   include("../shared/class_folder/ddlb_generic.php");
   require_once("tepuy_mis_c_contabiliza.php");  
   require_once("class_folder/class_tepuy_sob_integracion.php");  
   $io_grid = new grid_param();
   $in_class_contabiliza = new tepuy_mis_c_contabiliza();
   $in_class_int = new class_tepuy_sob_integracion();
   $in_class_contabiliza->uf_load_seguridad("MIS","tepuy_mis_p_reverso_anticipo_sob.php",$ls_permisos,$la_seguridad,$la_permisos);
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
  if (array_key_exists("operacion",$_POST))
  {
     $ls_operacion=$_POST["operacion"];
	 $li_total_record=$_POST["hide_total_row"];
	 $ldt_fecha = $_POST["txtFecha"];
  }
  else
  {
     $ls_operacion="";
	 $li_total_record=0;  
	 $ldt_fecha = date("d/m/Y");	 
  }
  // recorrido del grid
  if ($ls_operacion=="PROCESAR")  
  {  
	    $ls_fecha=$_POST["txtFecha"];
		for($li_i=1;$li_i<=$li_total_record;$li_i++)
		{
			if(array_key_exists("chksel".$li_i,$_POST))
			{
				$ls_codcon   = $_POST["txtcodcon".$li_i];			
				$ls_codant   = $_POST["txtcodant".$li_i];
				$ls_fecant   = $_POST["txtfecant".$li_i];		
				$ls_conant   = $_POST["txtconant".$li_i];
				$lb_valido = $in_class_int->uf_procesar_reverso_anticipo( $ls_codcon, $ls_codant , $ls_fecha);
				if ($lb_valido) { $in_class_int->io_msg->message("El Anticipo Nº ".$ls_codant." fue reversado."); }
				else { $in_class_int->io_msg->message("Error en el reverso del Anticipo Nº ".$ls_codcon ); }		
			}
		}
  }
  $in_class_int->uf_destroy_objects();
  unset($in_class_int);
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
	$in_class_contabiliza->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
 ?>	
  <p>&nbsp;</p>
  <table width="502" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Reverso de Anticipos </td>
    </tr>
    <tr>
      <td width="120"  height="23"><div align="right">Fecha Reverso </div></td>
      <td width="380" ><div align="left">
        <input name="txtFecha" type="text" id="txtFecha" value="<?php print $ldt_fecha ?>" size="14" maxlength="10" datepicker="true" >
      </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
	  <?php
	     $title[1]="";$title[2]="Nº Contrato";$title[3]="Nº Anticipo";$title[4]="Fecha ";$title[5]="Observación"; 
	     $in_class_contabiliza->uf_select_sob_anticipos_contabilizar( $arr_object ,$li_total_record,1);
		 $io_grid->makegrid($li_total_record,$title,$arr_object,500,"Anticipos Contabilizados","grdsep" );
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
      <input name="hide_total_row" type="hidden" id="hide_total_row" value="<?php print $li_total_record;?>"></td>
      <td>&nbsp;</td>
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
		f.action="tepuy_mis_p_reverso_anticipo_sob.php";
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

