<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
	require_once("class_folder/class_funciones_rpc.php");
	$io_rpc=new class_funciones_rpc();
	$ls_reporte=$io_rpc->uf_select_config("RPC","REPORTE","LISTADO_BENEFICIARIOS","tepuy_rpc_rpp_beneficiario.php","C");
	unset($io_rpc);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Beneficiarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Control de Ayudas -> <i>Beneficiarios</i></td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js_ayuda/menu_ayuda.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

<!--  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="36" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.png" alt="Eliminar" width="20" height="20" border="0"><a href="tepuywindow_blank_ayuda.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
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
if (array_key_exists("txtcedula1",$_POST))
   {
     $ls_cedula1=$_POST["txtcedula1"];	   
   }
else
   {
     $ls_cedula1="";
   }
if (array_key_exists("txtcedula2",$_POST)) 
   {  
     $ls_cedula2 =$_POST["txtcedula2"];	  
   }
else
   {
     $ls_cedula2="";
  }
if  (array_key_exists("radioorden",$_POST))
	{
	  $li_orden=$_POST["radioorden"];
    }
else
	{
	  $li_orden="";
	}	
if (array_key_exists("total",$_POST)) 
   {
     $totrow=$_POST["total"];	   
   }
else
   {
     $totrow="";
   }
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  
  <table width="391" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="89"></td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="22" colspan="3" align="center">Listado de Beneficiarios</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center"><table width="364" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><strong>Intervalo de Beneficiarios</strong></td>
          </tr>
        <tr>
          <td width="60" height="23"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="125"><input name="txtcedula1" type="text" id="txtcedula1" value="<?php print $ls_cedula1 ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogobene();"><img src="../shared/imagebank/tools15/buscar.png" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=1"></a></td>
          <td width="65"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td width="114"><input name="txtcedula2" type="text" id="txtcedula2" value="<?php print $ls_cedula2 ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogobene();"><img src="../shared/imagebank/tools15/buscar.png" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"><strong><span class="style14">
            <input name="hidrango" type="hidden" id="hidrango">
            </span></strong></a></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="19" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="38" colspan="3" align="left"><div align="center">
        <table width="364" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="6"><strong>Ordenado Por</strong></td>
            </tr>
          <tr>
            <td width="53"><div align="right">
              <?php 	 
      if(($li_orden=="0")||($li_orden==""))
	    {
		    $ls_codigo   ="checked";		
		    $ls_nombre   ="";
			$ls_apellido ="";
        }
      elseif($li_orden=="1")
	    {
            $ls_codigo  ="";
		    $ls_nombre  ="checked";
			$ls_apellido="";		  
        }
	   else
	     {
 	       $ls_codigo  ="";
		   $ls_nombre  ="";
		   $ls_apellido="checked";	
		 }	
	  ?>
              <input name="radioorden" type="radio" value="0" checked  <?php print $ls_codigo ?>>
            </div></td>
            <td width="74">C&eacute;dula</td>
            <td width="27"><div align="right">
              <input name="radioorden" type="radio" value="1"  <?php print $ls_nombre ?>>
            </div></td>
            <td width="68">Nombre</td>
            <td width="44"><div align="center">
              <input name="radioorden" type="radio" value="2" <?php print $ls_apellido ?>>
            </div></td>
            <td width="96">Apellido<a href="javascript: ue_showouput();">
              <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
            </a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"></td>
    </tr>
  </table>
  <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
</form>      
</body>
<script language="JavaScript">
function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for (i=1;i<=total;i++)
	    {
		  cadena_ceros=cadena_ceros+"0";
	    }
	cadena=cadena_ceros+cadena;
 	if (objeto=="txtcedula1")
	   {
	 	 document.form1.txtcedula1.value=cadena;
	   }
	 else
	   {
	     document.form1.txtcedula2.value=cadena;
	   }  
}


function uf_catalogobene()
{
    f=document.form1;
    f.operacion.value="BUSCAR";
    pagina="tepuy_catdin_bene.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=720,height=500,resizable=yes,location=no");
}


function ue_showouput()
{
	f       = document.form1;
	cedula1 = f.txtcedula1.value;
	cedula2 = f.txtcedula2.value;
	if (cedula1<=cedula2)
	{
	if (f.radioorden[0].checked==true)
	   {
	     orden=f.radioorden[0].value;
	   }
	else
	   {
	     if (f.radioorden[1].checked==true)
	        {
	          orden=f.radioorden[1].value;
	        }
	     else
	        {
	          orden=f.radioorden[2].value;
	        } 
	   }     
			reporte=f.reporte.value;
	pagina="reportes/"+reporte+"?hidorden="+orden+"&hidcedula1="+cedula1+"&hidcedula2="+cedula2;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
    }
  else
    {
	  alert("Error en Rango de C�dulas !!!");
	}
}

function ue_imprimir()
{
}
</script>
</html>
