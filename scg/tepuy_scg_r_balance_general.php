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
	$io_fun_scg->uf_load_seguridad("SCG","tepuy_scg_r_balance_general.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	///$lb_valido=$io_fun_scg->uf_convertir_scgsaldos($la_seguridad);
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
<title>Balance General</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
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
    <td width="780" height="36" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_search('<? print $ls_codemp; ?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_openexcel('<? print $ls_codemp; ?>');"><img src="../shared/imagebank/tools20/excel.png" alt="Imprimir" width="20" height="18" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
<?php
	
	if(array_key_exists("txtfechasta",$_POST))
	{
		$ld_fechas=$_POST["txtfechasta"];
	}
	else
	{
		$ld_fechas=date("d/m/Y");
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
<table width="395" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    
    <tr>
      <td height="24" colspan="2" align="center" class="titulo-ventana">Balance General </td>
    </tr>
    <tr  style="display:none">
      <td width="67" height="24" align="center"><div align="left">
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
      </div></td>
      <td width="325" height="24" align="center">&nbsp;</td>
    </tr>
  
      <td height="72" colspan="2" align="center"><div align="left" class="style14"></div>        
        <table width="388" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="14" colspan="4"><strong class="titulo-celdanew"> Fecha  </strong></td>
          </tr>
          <tr>
            
            <td width="32" height="41">&nbsp;</td>   
            <td width="65"><div align="right">Hasta</div></td>
            <td width="280"><div align="left">
                <input name="txtfechasta" type="text" id="txtfechasta"  align="center" onKeyPress="currencyDate(this);" value="<?php print  $ld_fechas; ?>" size="15" maxlength="10"  datepicker="true">
            </div></td>
		  <tr class="titulo-celdanew">
            <td height="13" align="center" colspan="4">Nivel de la Cuenta</td>
      </tr>
          
          <tr>
		     <td width="32">&nbsp;</td> 
            <td height="37"><div align="right">Nivel</div></td>
            <td height="37" colspan="2"><div align="left">
              <select name="cmbnivel" id="cmbnivel">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
</div></td>
          </tr>
        </table></td>
    </tr>
  </table>
        </td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
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
}


function ue_search(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.cmbnivel.value;
		/*cmbmes=f.cmbmes.value;
		cmbagno=f.cmbagno.value;*/
		fechas=f.txtfechasta.value;
		tiporeporte=f.cmbbsf.value;
		if(cmbnivel=="s1")
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			pagina="reportes/tepuy_scg_rpp_balance_general.php?hidcodemp="+codemp+"&fechas="+fechas;
			pagina=pagina+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_openexcel(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.cmbnivel.value;
		/*cmbmes=f.cmbmes.value;
		cmbagno=f.cmbagno.value;*/
		fechas=f.txtfechasta.value;
		tiporeporte=f.cmbbsf.value;
		if(cmbnivel=="s1")
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			pagina="reportes/tepuy_scg_rpp_balance_general_excel.php?hidcodemp="+codemp+"&fechas="+fechas;
			pagina=pagina+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}
   
function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
