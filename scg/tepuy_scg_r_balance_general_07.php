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
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","tepuy_scg_r_balance_general_07.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
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
<title>Balance General - Instructivo 07</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 14px}
.Estilo3 {font-size: 9px}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="75"></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
   <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	   {
		  if($_SESSION["la_empresa"]["confinstr"]=='A')
		  {
      ?>  
			<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
	<?php
          }
          elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	      {
	 ?>
		  <td height="20"  colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
	 <?php
          }
          elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	      {
       ?>
	       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	 <?php
          }
	  }
	  else
	  {
	  ?>
	  	 <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
   <?php 
	}
	?>  
  </tr> -->
  <tr>
    <td height="45" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.png" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="608" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Balance General - Instructivo 07 </td>
    </tr>
  </table>
  <table width="605" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="127"></td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr  style="display:none">
      <td height="17" align="center"><div align="right"><span class="Estilo2"></span>Reporte en</div></td>
      <td width="207" height="17" align="center"><div align="left">
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
      </div></td>
      <td width="265" height="17" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="40" colspan="3" align="center">      <div align="left">
        <table width="550" height="84" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="3"><strong class="titulo-celdanew">Rango de Emisi&oacute;n </strong></td>
          </tr>
          <tr>
            <td><div align="right"> </div></td>
            <td width="204" height="21"><div align="right">
            </div>              <div align="right">
              </div>
              <div align="left">            </div>              <div align="left">
              </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="191">
              <div align="right">
                <input name="txtetiqueta" type="text" class="sin-borde" id="txtetiqueta" size="10" maxlength="20" value="Tirmestral" readonly>
            </div></td>
            <td height="21"><select name="combo" size="1" id="combo">
                <option value="s1">Seleccione una opci&oacute;n</option>
                <option value="1">Enero   - Marzo </option>
                <option value="2">Abril   - Junio </option>
                <option value="3">Julio   - Septiembre </option>
                <option value="4">Octubre - Diciembre </option>             
            </select>
              <div align="left">            </div></td>
            <td width="153">&nbsp;</td>
          </tr>
          <tr>
            <td>
              <div align="right"></div></td>
            <td height="21"><div align="right"> </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left"><strong><span class="style14">        
      </span></strong></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="right"><span class="Estilo1">	
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
</span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.combo.value;
		cmbnivel=8;		
		if(cmbnivel=="s1") 
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			pagina="reportes/tepuy_scg_rpp_balance_general_07.php?cmbnivel="+cmbnivel;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}
</script>
</html>
