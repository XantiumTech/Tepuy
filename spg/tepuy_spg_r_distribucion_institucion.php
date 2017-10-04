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
	$ls_ventanas="tepuy_spg_r_distribucion_institucion.php";

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
<title>Distribucion del Presupuesto</title>
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
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
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

if(array_key_exists("concejal1",$_POST))
{
	$ls_concejal1=$_POST["concejal1"];
}
else
{
	$ls_concejal1="Alonzo Carrillo";
}

if(array_key_exists("concejal2",$_POST))
{
	$ls_concejal2=$_POST["concejal2"];
}
else
{
	$ls_concejal2="Suleimo Reimi";
}

if(array_key_exists("concejal3",$_POST))
{
	$ls_concejal3=$_POST["concejal3"];
}
else
{
	$ls_concejal3="Alba Busto";
}
if(array_key_exists("concejal4",$_POST))
{
	$ls_concejal4=$_POST["concejal4"];
}
else
{
	$ls_concejal4="Dr. Cergio Dell�n";
}
if(array_key_exists("concejal5",$_POST))
{
	$ls_concejal5=$_POST["concejal5"];
}
else
{
	$ls_concejal5="Alexis Alvarez";
}
if(array_key_exists("concejal6",$_POST))
{
	$ls_concejal6=$_POST["concejal6"];
}
else
{
	$ls_concejal6="Alexis Gonzalez";
}
if(array_key_exists("concejal7",$_POST))
{
	$ls_concejal7=$_POST["concejal7"];
}
else
{
	$ls_concejal7="Jes�s Aponte";
}

if(array_key_exists("director1",$_POST))
{
	$ls_director1=$_POST["director1"];
}
else
{
	$ls_director1="Lic. Fannisbel Zambrano";
}
if(array_key_exists("director2",$_POST))
{
	$ls_director2=$_POST["director2"];
}
else
{
	$ls_director2="Lic. Maria Alejandra Mosquera";
}
if(array_key_exists("director3",$_POST))
{
	$ls_director3=$_POST["director3"];
}
else
{
	$ls_director3="Lic. Jos� Paredes";
}
if(array_key_exists("director4",$_POST))
{
	$ls_director4=$_POST["director4"];
}
else
{
	$ls_director4="Sra. Marlene Dur�n";
}
if(array_key_exists("director5",$_POST))
{
	$ls_director5=$_POST["director5"];
}
else
{
	$ls_director5="Ing. Jean Peare Echeverry";
}
if(array_key_exists("director6",$_POST))
{
	$ls_director6=$_POST["director6"];
}
else
{
	$ls_director6="Lic. Carlos Gonzalez";
}
if(array_key_exists("director7",$_POST))
{
	$ls_director7=$_POST["director7"];
}
else
{
	$ls_director7="Gilber";
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
  <table width="608" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Portada de la Instituci�n </td>
    </tr>
  </table>
  <table width="605" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="109"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
           <p>

           </p>
           <table width="553" height="62" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
			<tr class="titulo-celdanew">
			<td height="13" colspan="9" valign="top"><strong>Concejales 
			</strong></td>
            </tr>
		    <tr class="formato-blanco">
		      <td width="38" height="18"><div align="right">Presidente</div></td>
		      <td width="136" height="18"><input name="concejal1" style="text-align:center" type="text" id="concejal1" value="<?php print $ls_concejal1 ?>" size="30" maxlength="20"></td>
	        </tr>
		    <tr class="formato-blanco">
            <td height="14"><div align="right">Vice-Presidente</div></td>
            <td height="22"><input name="concejal2" style="text-align:center" type="text" id="concejal2" value="<?php print $ls_concejal2 ?>" size="30" maxlength="20"></td>
            </tr>
    			<tr class="formato-blanco">
            <td height="14"><div align="right">Concejal</div></td>
            <td height="22"><input name="concejal3" style="text-align:center" type="text" id="concejal3" value="<?php print $ls_concejal3 ?>" size="30" maxlength="20"></td>
            </tr>
    			<tr class="formato-blanco">
            <td height="14"><div align="right">Concejal</div></td>
            <td height="22"><input name="concejal4" style="text-align:center" type="text" id="concejal4" value="<?php print $ls_concejal4 ?>" size="30" maxlength="20"></td>
            </tr>
    			<tr class="formato-blanco">
            <td height="14"><div align="right">Concejal</div></td>
            <td height="22"><input name="concejal5" style="text-align:center" type="text" id="concejal5" value="<?php print $ls_concejal5 ?>" size="30" maxlength="20"></td>
            </tr>
    <tr class="formato-blanco">
            <td height="14"><div align="right">Concejal</div></td>
            <td height="22"><input name="concejal6" style="text-align:center" type="text" id="concejal6" value="<?php print $ls_concejal6 ?>" size="30" maxlength="20"></td>
            </tr>
    <tr class="formato-blanco">
            <td height="14"><div align="right">Concejal</div></td>
            <td height="22"><input name="concejal7" style="text-align:center" type="text" id="concejal7" value="<?php print $ls_concejal7 ?>" size="30" maxlength="20"></td>
            </tr>
        </table>
           <p>

</p>
      </div>        </td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="center"><table width="550" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="5"><strong>Directores </strong></td>
        </tr>
        <tr>
            <tr class="formato-blanco">
            <td height="14"><div align="right">Director de Administraci�n</div></td>
            <td height="22"><input name="director1" style="text-align:center" type="text" id="director1" value="<?php print $ls_director1 ?>" size="30" maxlength="20"></td>
            </tr>
	<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Presupuesto</div></td>
            <td height="22"><input name="director2" style="text-align:center" type="text" id="director2" value="<?php print $ls_director2 ?>" size="30" maxlength="20"></td>
            </tr>
	<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Personal</div></td>
            <td height="22"><input name="director3" style="text-align:center" type="text" id="director3" value="<?php print $ls_director3 ?>" size="30" maxlength="20"></td>
            </tr>
	<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Des. Social</div></td>
            <td height="22"><input name="director4" style="text-align:center" type="text" id="director4" value="<?php print $ls_director4 ?>" size="30" maxlength="20"></td>
            </tr>
<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Ingenieria</div></td>
            <td height="22"><input name="director5" style="text-align:center" type="text" id="director5" value="<?php print $ls_director5 ?>" size="30" maxlength="20"></td>
            </tr>
<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Catastro</div></td>
            <td height="22"><input name="director6" style="text-align:center" type="text" id="director6" value="<?php print $ls_director6 ?>" size="30" maxlength="20"></td>
            </tr>
<tr class="formato-blanco">
            <td height="14"><div align="right">Director de Protecci�n Civil</div></td>
            <td height="22"><input name="director7" style="text-align:center" type="text" id="director7" value="<?php print $ls_director7 ?>" size="30" maxlength="20"></td>
            </tr>
            <td><div align="right"></div></td>
            <td><div align="left">
            </div></td>
          </tr>
        </table>
        </div></td>
    </tr>
    
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_desaparecer(objeto)
{
    eval("document.form1."+objeto+".style.visibility='hidden'");
}
function uf_aparecer(objeto)
{
    eval("document.form1."+objeto+".style.visibility='visible'");
}


function ue_showouput()
{
		f=document.form1;		
		concejal1  = f.concejal1.value;
		concejal2  = f.concejal2.value;
		concejal3  = f.concejal3.value;
		concejal4  = f.concejal4.value;
		concejal5  = f.concejal5.value;
		concejal6  = f.concejal6.value;
		concejal7  = f.concejal7.value;
		director1  = f.director1.value;
		director2  = f.director2.value;
		director3  = f.director3.value;
		director4  = f.director4.value;
		director5  = f.director5.value;
		director6  = f.director6.value;
		director7  = f.director7.value;
		pagina="reportes/tepuy_spg_rpp_distribucion_institucion.php?concejal1="+concejal1
							+"&concejal2="+concejal2+"&concejal3="+codestpro3+"&concejal4="+concejal4
							+"&concejal5="+concejal5+"&concejal6="+concejal6+"&concejal7="+concejal7
							+"&director1="+director1+"&director2="+director2+"&director3="+director3+"&director4="+director4
							+"&director5="+director5+"&director6="+director6+"&director7="+director7;
				  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>
