<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="tepuy_spg_r_listado_apertura.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
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
<title>Reporte de Listado de Apertura De Cuentas </title>
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
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
    <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Contabilidad Presupuestaria de Gasto</td>
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
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
    <td height="36" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.png" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/tepuy_include.php");
$io_in=new tepuy_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if (array_key_exists("codestpro1",$_POST))
   {
     $ls_codestpro1=$_POST["codestpro1"];	   
   }
else
   {
     $ls_codestpro1="";
   }
if (array_key_exists("codestpro2",$_POST))
   {
    $ls_codestpro2=$_POST["codestpro2"];	   
   }
else
   {
     $ls_codestpro2="";
   }
if (array_key_exists("codestpro3",$_POST))
   {
     $ls_codestpro3=$_POST["codestpro3"];	   
   }
else
   {
     $ls_codestpro3="";
   }
if (array_key_exists("codestpro4",$_POST))
   {
     $ls_codestpro4=$_POST["codestpro4"];	   
   }
else
   {
     $ls_codestpro4="";
   }
if (array_key_exists("codestpro5",$_POST))
   {
     $ls_codestpro5=$_POST["codestpro5"];	   
   }
else
   {
     $ls_codestpro5="";
   }
   if (array_key_exists("codestpro1h",$_POST))
   {
      $ls_codestpro1h=$_POST["codestpro1h"];	   
   }
else
   {
      $ls_codestpro1h="";
   }
if (array_key_exists("codestpro2h",$_POST))
   {
     $ls_codestpro2h=$_POST["codestpro2h"];	   
   }
else
   {
     $ls_codestpro2h="";
   }
if (array_key_exists("codestpro3h",$_POST))
   {
     $ls_codestpro3h=$_POST["codestpro3h"];	   
   }
else
   {
     $ls_codestpro3h="";
   }
if (array_key_exists("codestpro4h",$_POST))
   {
     $ls_codestpro4h=$_POST["codestpro4h"];	   
   }
else
   {
     $ls_codestpro4h="";
   }
if (array_key_exists("codestpro5h",$_POST))
   {
     $ls_codestpro5h=$_POST["codestpro5h"];	   
   }
else
   {
     $ls_codestpro5h="";
   }
   if	(array_key_exists("txtcodfuefindes",$_POST))
	{
	  $ls_codfuefindes=$_POST["txtcodfuefindes"];
    }
else
	{
	  $ls_codfuefindes="";
	} 
	
if	(array_key_exists("txtcodfuefinhas",$_POST))
	{
	  $ls_codfuefinhas=$_POST["txtcodfuefinhas"];
    }
else
	{
	  $ls_codfuefinhas="";
	} 
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
		print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
		print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
		print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
		print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
		print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
		print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
		print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
		
	}
	else
	{
		
		print("<script language=JavaScript>");
		print(" location.href='tepuywindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
  <table width="580" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Listado Aperturas De Cuentas </td>
    </tr>
  </table>
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="593"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr  style="display:none">
      <td colspan="3" align="center"><div align="left"><strong>Reporte en</strong>
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <p>
          <?php 
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
	   ?>
        </p>
        <table width="542" height="65" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
			<tr class="titulo-celdanew">
			  <td height="16" colspan="9" valign="top" class="titulo-celdanew"><span class="titulo-celdanew"><strong>Rango Codigo Programatico</strong></span></td>
		    </tr>
		    <tr class="formato-blanco">
		      <td width="38" height="18"><div align="right">Desde</div></td>
		      <td width="136" height="18"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20"></td>
		      <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
		      <td width="20"></td>
		      <td width="135"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6"></td>
		      <td width="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
		      <td width="20"></td>
		      <td width="136"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3"></td>
		      <td width="24"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
	        </tr>
		    <tr class="formato-blanco">
            <td height="14"><div align="right">Hasta</div></td>
            <td height="22"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="22" maxlength="20"></td>
            <td><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td></td>
            <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="22" maxlength="6"></td>
            <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td></td>
            <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="22" maxlength="3"></td>
            <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
        </table>
        <p>
          <?php 
		  }
		  else
		  {
		?>
        </p>
        <table width="550" height="65" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda">
            <td height="13" colspan="15" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
          </tr>
          <tr class="formato-blanco">
            <td width="41" height="18"><div align="right">Desde</div></td>
            <td width="50" height="18"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td width="40"></td>
            <td width="50"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td width="27"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td width="51"></td>
            <td width="50"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td width="20"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td width="40"><label></label></td>
            <td width="50"><input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_codestpro4 ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td width="29"><a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td width="40"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td width="50"><label>
              <input name="codestpro5" type="text" id="codestpro5" value="<?php print  $ls_codestpro5 ?>" size="5" maxlength="2" style="text-align:center">
            <a href="javascript:catalogo_estpro5();"></a></label></td>
            <td width="35"><a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
          </tr>
          <tr class="formato-blanco">
            <td height="29"><div align="right">Hasta</div></td>
            <td height="29"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td></td>
            <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td></td>
            <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td><label>
              <input name="codestpro4h" type="text" id="codestpro4h" value="<?php print  $ls_codestpro4h ?>" size="5" maxlength="2" style="text-align:center">
            </label></td>
            <td><a href="javascript:catalogo_estprohas4();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td><input name="codestpro5h" type="text" id="codestpro5h" value="<?php print  $ls_codestpro5h ?>" size="5" maxlength="2" style="text-align:center"></td>
            <td><a href="javascript:catalogo_estprohas5();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
          </tr>
        </table>
        <?php
		  }
		 ?>
      </div>        </td>
    </tr>
    
    
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="550" height="63" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="5"><strong>Intervalo de Fuente de Financiamiento </strong></td>
        </tr>
        <tr>
          <td width="96" height="38"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="167"><div align="left">
            <input name="txtcodfuefindes" type="text" id="txtcodfuefindes"  style="text-align:center" value="<?php print $ls_codfuefindes; ?>" size="10" readonly>
            <a href="javascript:catalogo_fuefindes();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
          <td width="94"><div align="right">Hasta</div></td>
          <td width="120"><input name="txtcodfuefinhas" type="text" id="txtcodfuefinhas" style="text-align:center" value="<?php print $ls_codfuefinhas; ?>" size="10" readonly>
            <a href="javascript:catalogo_fuefinhas();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          <td width="80"><a href="javascript:catalogo_fuefinhas();"></a></td>
        </tr>
      </table></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td colspan="3" align="center"><div align="right"><span class="Estilo1">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
        </span></a></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function uf_catalogoprov()
{
    f=document.form1;
    pagina="tepuy_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }  
        }
}

function ue_showouput()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		codestpro1  = f.codestpro1.value;
		codestpro2  = f.codestpro2.value;
		codestpro3  = f.codestpro3.value;
		codestpro1h = f.codestpro1h.value;
		codestpro2h = f.codestpro2h.value;
		codestpro3h = f.codestpro3h.value;
		estmodest   = f.estmodest.value;
		tipoformato = f.cmbbsf.value;
		txtcodfuefindes = f.txtcodfuefindes.value;
		txtcodfuefinhas = f.txtcodfuefinhas.value;
		if(estmodest==1)
		{
			   pagina="reportes/tepuy_spg_rpp_listado_apertura_pdf.php?codestpro1="+codestpro1
				+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h
				+"&codestpro3h="+codestpro3h+"&tipoformato="+tipoformato+"&txtcodfuefindes="+txtcodfuefindes+"&txtcodfuefinhas="+txtcodfuefinhas;
			   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=700,resizable=yes,location=no");
		}
		else
		{
			codestpro4  = f.codestpro4.value;
			codestpro5  = f.codestpro5.value;
			codestpro4h = f.codestpro4h.value;
			codestpro5h = f.codestpro5h.value;

		    pagina="reportes/tepuy_spg_rpp_listado_apertura_pdf.php?codestpro1="+codestpro1
			+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5
			+"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&codestpro4h="+codestpro4h
			+"&codestpro5h="+codestpro5h+"&tipoformato="+tipoformato+"&txtcodfuefindes="+txtcodfuefindes+"&txtcodfuefinhas="+txtcodfuefinhas;
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=700,resizable=yes,location=no");
		}
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}

function catalogo_estpro1()
{
	   pagina="tepuy_cat_public_estpro1.php?tipo=reporte";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		
		if(codestpro1=='**')
		{
			pagina="tepuy_cat_estpro2.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="tepuy_cat_public_estpro.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="tepuy_cat_estpro3.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="tepuy_cat_estpro4.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="tepuy_cat_estpro5.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
function catalogo_estprohas1()
{
	   pagina="tepuy_cat_public_estpro1.php?tipo=rephas";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estprohas2()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		if(codestpro1=='**')
		{
			pagina="tepuy_cat_estpro2.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estprohas3()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="tepuy_cat_public_estpro.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="tepuy_cat_estpro3.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estprohas4()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="tepuy_cat_estpro4.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");

		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="tepuy_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estprohas5()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	codestpro4=f.codestpro4h.value;
	codestpro5=f.codestpro5h.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="tepuy_cat_estpro5.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="tepuy_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
function catalogo_fuefindes()
{
    f=document.form1;
    pagina="tepuy_spg_cat_fuente.php?tipo=REPORTE_DESDE";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function catalogo_fuefinhas()
{
    f=document.form1;
    pagina="tepuy_spg_cat_fuente.php?tipo=REPORTE_HASTA";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
</script>
</html>
