<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Inicialización de Contadores de la Retenciones</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="class_folder/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Ordenación de Pagos</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="../js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
  <tr>
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="24" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20">
<!--<a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a>-->
<a href="../tepuywindow_blank.php"><img src="../../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/tepuy_c_check_relaciones.php"); 
require_once("class_folder/tepuy_cxp_c_inicio_contadores.php");

$io_servicioect = new tepuy_include();//Instanciando la tepuy_Include.
$conn           = $io_servicioect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase tepuy_include.
$io_sql         = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg         = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_funciondb   = new class_funciones_db($conn);
$io_grid        = new grid_param();
$io_ds          = new class_datastore(); //Instanciando la clase datastore
$io_chkrel      = new tepuy_c_check_relaciones($conn);
$lb_existe      = "";
$io_funcion     = new class_funciones(); 

/////////////////////////////////////////////////////////////////////////////
$io_contadores = new tepuy_cxp_c_inicio_contadores($conn);


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CXP";
	$ls_ventanas = "tepuy_cxp_d_inicio_de_contadores.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];
			$la_accesos["incluir"]  = $_POST["incluir"];
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = "";
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
//print "operacion".$_POST["operacion"];
if (array_key_exists("operacion",$_POST))
   {
     	$ls_operacion         = $_POST["operacion"];
     	$ls_id                = $_POST["txtid"]; 
	$ls_retencion     = $_POST["txtretencion"];  
  	$li_nro_inicial   = $_POST["txtnro_inicial"];	  
	$ls_tipodeduccion  = $_POST["radiotipodeduccion"];
	$ls_estado  = $_POST["radioestado"];    

   }
else
   {
     $ls_operacion    = "NUEVO";
     $ls_id           = ""; 
	 $ls_retencion      = "";
  	 $li_nro_inicial  = "";	  
 	 $ls_estatus      = "NUEVO";	  
   }
   $lb_empresa = true;
		
//Titulos de la tabla de Detalle Bienes
  $title[1]="Código"; $title[2]="Sistema"; $title[3]="Prefijo"; $title[4]="Numero Inicial";
  $title[5]="Numero Final"; $title[6]="Id Actual"; $title[7]="Edición"; 
  $grid="grid";	

//----------------------------------------------------------------------------------------------------------------------------------
if ($ls_operacion=="NUEVO")
{
	 $ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'cxp_contador','codcmp');
	 if(empty($ls_id))
	 {
	    $io_msg->message($io_funciondb->is_msg_error);
	 }    	 
	 $ls_retencion      = "";	  
	 $li_nro_inicial    = "";
	 $ls_estado         = "";
	 $ls_tipodeduccion  = "";
	 $ls_deduccion      = "";        
}
//----------------------------------------------------------------------------------------------------------------------------------
  if ($ls_operacion=="CARGAR")
  {       
	$ls_id           = $_POST["txtid"]; 
	$ls_retencion    = $_POST["txtretencion"];	  
  	$li_nro_inicial  = $_POST["txtnro_inicial"];
  	$ls_estado       = $_POST["radioestado"];	
	$ls_deduccion      = $_POST["radiotipodeduccion"];
	/*print "ID:".$ls_id;
	print "retencion:".$ls_retencion;
	print "Numero Inicial:".$li_nro_inicial;
	print "Deduccion:".$ls_deduccion;
  	print "Estado:".$ls_estado;*/
	 /*$ls_sql=" SELECT *                            ".
			 " FROM   cxp_contador           ".
			 " WHERE  codemp='".$ls_codemp."' AND codcmp='".$ls_id; 
	 $rs_data = $io_sql->select($ls_sql);       
	 if ($row=$io_sql->fetch_row($rs_data))
	 {
		$data        = $io_sql->obtener_datos($rs_data);
		$arrcols     = array_keys($data);
		$totcol      = count($arrcols);
		$io_ds->data = $data;
		$ls_id       = trim($data["codcmp"][$i]);    
		$ls_retencion= trim($data["nom"][$i]);    
		$li_nro_inicial = trim($data["numcmp"][$i]);
		$ls_tipo    = trim($data["tipo"][$i]);
		$ls_estado=trim($data["estado"][$i]);
		
	 }*/
  }   

//----------------------------------------------------------------------------------------------------------------------------------
if ($ls_operacion=="GUARDAR")
{     
	$ls_id           = $_POST["txtid"]; 
	$ls_retencion    = $_POST["txtretencion"];	  
  	$li_nro_inicial  = $_POST["txtnro_inicial"];
  	$ls_estado       = $_POST["radioestado"];	
	$ls_deduccion      = $_POST["radiotipodeduccion"];	
		
	
	/*print "ID:".$ls_id;
	print "retencion:".$ls_retencion;
	print "Numero Inicial:".$li_nro_inicial;
	print "Deduccion:".$ls_deduccion;
  	print "Estado:".$ls_estado;*/
	$lb_existe=$io_contadores->uf_select_nro_control_id($ls_codemp,$ls_id);
	if ($lb_existe)
	{           
		 $lb_valido=$io_contadores->uf_update_contador($ls_codemp,$ls_id,$ls_retencion,$li_nro_inicial,$ls_deduccion,$ls_estado,$la_seguridad);
		 if ($lb_valido)
		 {
			$io_msg->message("Registro Actualizado !!!");
			$ls_retencion           = "";	  
			$li_nro_inicial       = "";	  
			$ls_estatus="NUEVO";
			$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'cxp_contador','codcmp');
		 }
		 else
		 {
		 	$io_msg->message("Error en Actualización !!!");
		 } 	 
	 } 
	 else
	 {  
		$lb_valido=$io_contadores->uf_guardar_contador($ls_codemp,$ls_id,$ls_retencion,$li_nro_inicial,$ls_deduccion,$ls_estado,$la_seguridad);
		if ($lb_valido)
		{
			$io_msg->message("Registro Incluido !!!");
			$ls_retencion           = "";	  
			$li_nro_inicial       = "";	  
			$ls_estatus="NUEVO";
			$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'cxp_contador','codcmp');
		}
          	else
		{
			$io_msg->message("Error en Inclusión !!!");
		}
	}
} 
            
if ($ls_operacion=="ELIMINAR")
   {
$ls_id           = $_POST["txtid"]; 
	$ls_retencion    = $_POST["txtretencion"];	  
  	$li_nro_inicial  = $_POST["txtnro_inicial"];
  	$ls_estado       = $_POST["radioestado"];	
	$ls_deduccion      = $_POST["radiotipodeduccion"];
	$lb_existe=$io_contadores->uf_select_nro_control_id($ls_codemp,$ls_id);
	if ($lb_existe)
	{           
		$lb_valido=$io_contadores->uf_delete_contador($ls_codemp,$ls_id,$ls_seguridad);
		if ($lb_valido)
		{
		        $io_msg->message("Registro Eliminado !!!");
			$ls_retencion           = "";	  
			$li_nro_inicial       = "";	  
			$ls_estatus="NUEVO";
			$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'cxp_contador','codcmp');
		}
		else
		{
			$io_msg->message("Error en la Eliminación !!!");
		} 	 
	  } 
	  else
	  {  
		$io_msg->message("Tipo de Contador No Existe !!!");
	  }   
  }
//----------------------------------------------------------------------------------------------------------------------------------
?>
<form name="form1" method="post" action="">
  <p>
    <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='../tepuywindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</p>
  <p>&nbsp;  </p>
  <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
      <tr> 
        <td height="22" colspan="2" class="titulo-ventana">Inicializacion de Contadores </td>
      </tr>
      <tr>
        <td height="22" ><input name="hidmaestro" type="hidden" id="hidmaestro" value="N"></td>
        <td width="400" height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
          <input name="lastrow"  type="hidden"   id="lastrow" value="<?php print $li_lastrow;?>">
          <input name="hiddensis" type="hidden" id="hiddensis" value="<?php print $ls_densis ?>">
          <input name="hiddenpro" type="hidden" id="hiddenpro" value="<?php print $ls_retencion?>"></td>
      </tr>
      <tr> 
        <td width="107" height="22" align="right">C&oacute;digo </td>
        <td height="22" ><input name="txtid" type="text" id="txtid" value="<?php print $ls_id ?>" size="10" maxlength="4" style="text-align:center" onBlur="javascript:rellenar_cadena(this.value,4,txtid);"  onKeyPress="return keyRestrict(event,'1234567890');" readonly>
        <input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion ?>"> </td>
      </tr>
      <tr> 
        <td height="22" align="right">Nombre de la Retencion </td>
        <td height="22"><p>
          <input name="txtretencion" id="txtretencion"  style="text-align:left" value="<?php print $ls_retencion ?>" type="text" size="40" maxlength="40"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');">
	</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Numero Inicial </div></td>
        <td height="22"><input name="txtnro_inicial" id="txtnro_inicial" value="<?php print $li_nro_inicial ; ?>" type="text" size="15" maxlength="15"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');" ></td>
 </tr>            
	<td height="22"><div align="right">Activar La Numeración</div></td>
<?php 
	switch ($ls_estado)
	{
		case"S":
		     $ls_estadosi="checked";
		     $ls_estadono="";
		break;
		default:
		     $ls_estadono="checked";
		     $ls_estadosi="";
		break;
	}
	?>
         <td width="80" ><input name="radioestado" type="radio" class="sin-borde" onClick="cambiarestatus()" value="S" <?php print $ls_estadosi ?> >
      Si
         <input name="radioestado" type="radio" class="sin-borde" onClick="cambiarestatus()" value="N" <?php print $ls_estadono?>>
      No
      </tr>
        <td height="22"><div align="right">Tipo de Deduccion </div></td>
	<?php 
	switch ($ls_tipodeduccion)
	{
		case"S":
		     $ls_deducisrl="checked";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
		     $ls_deductimbre="";
		     $ls_deducotra="";
		break;
		case"I":
	             $ls_deducisrl="";
		     $ls_deduciva="checked";
		     $ls_deducmunicipal="";
		     $ls_deductimbre="";
		     $ls_deducotra="";
		
		break;
		case"M":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="checked";
	  	     $ls_deductimbre="";
		     $ls_deducotra="";
		break;
		case"T":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
	  	     $ls_deductimbre="checked";
		     $ls_deducotra="";
		break;
		case"O":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
	  	     $ls_deductimbre="";
		     $ls_deducotra="checked";
		break;
		default:
		     $ls_deducisrl="checked";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
		     $ls_deductimbre="";
		     $ls_deducotra="checked";
		break;
	}
	?>
         <td width="80" ><input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="S" <?php print $ls_deducisrl ?> >
      I.S.L.R.
         <input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="I" <?php print $ls_deduciva?>>
      Ret. IVA
         <input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="M" <?php print $ls_deducmunicipal ?>>
      Ret. Municipal
         <input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="T" <?php print $ls_deductimbre ?>>
      Timbre Fiscal 
         <input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="O" <?php print $ls_deducotra ?>>
      Otras
      </tr>
</form>
</body>
<script language="JavaScript">
function uf_actualizar(fila)
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	f.filaact.value=fila;
	lb_status=f.hidestatus.value;
	lb_valido=false;
	li_total=f.totrows.value;
	tildados=0;
	if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
	{
		for(li_i=1;(li_i<=li_total);li_i++)
		{
			lb_valido=eval("f.chk"+li_i+".checked");
			if(lb_valido)
			{
				tildados=tildados+1;
			}
		}
		if(tildados>0)
		{
			if(tildados>1)
			{
				alert("Debe Seleccionar solo un codigo.");
			}
			else
			{
				 f.operacion.value="ACTUALIZAR";
				 f.action="tepuy_cxp_d_inicio_de_contadores.php";
				 f.submit();
			}
		}	
	}
	else
	{
     alert("No tiene permiso para realizar esta operación");
	}		
} 		

function ue_nuevo()
{
f=document.form1;
li_incluir=f.incluir.value;
if (li_incluir==1)
   {	
     f.operacion.value="NUEVO";
	 f.txtid.value="";
	// f.txtdenominacion.value="";
	 //f.txtdenominacion.focus(true);
	 f.action="tepuy_cxp_d_inicio_de_contadores.php";	
	 f.submit(); 
   }
else
   {
     alert("No tiene permiso para realizar esta operación");
   }   
}


function ue_guardar()
{//1
var resul="";					   
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
//alert(li_cambiar);
if ((li_cambiar==1)||(li_incluir==1))
   {
   	with (document.form1)
	{
	if (campo_requerido(txtretencion,"El Nombre de la Retención debe estar lleno !!")==false)
		{
		txtretencion.focus();
		}
		else
		{ 
			if (campo_requerido(txtnro_inicial,"El Contador Inicial debe estar lleno !!")==false)
			{
		        	txtnro_inicial.focus();
		        }
 	            	else
		        {
			if (campo_requerido(radiotipodeduccion,"Debe Seleccionar el tipo de Deducción !!")==false)
				{
					radiotipodeduccion.focus();
				}
			else
				{
				   f.operacion.value="GUARDAR";
				   f.action="tepuy_cxp_d_inicio_de_contadores.php";
				   f.submit();
		                }
			}
		}
	}
	}
else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}	 					
					
function ue_eliminar()
{
var borrar="";
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtid.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f=document.form1;
			   f.operacion.value="ELIMINAR";
			   f.action="tepuy_cxp_d_inicio_de_contadores.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
		     }
  	    }	   
    }
  else
    {
      alert("No tiene permiso para realizar esta operación");
	}
}	
		
function campo_requerido(field,mensaje)
{
  with (field) 
		{
		if (value==null||value=="")
		   {
			 alert(mensaje);
			 return false;
		   }
		else
		   {
			 return true;
		   }
		}
}
	
function rellenar_cadena(cadena,longitud,txt)
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
	document.form1.txt.value=cadena;
}
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
		 pagina="tepuy_cxp_cat_contadores.php";
		 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
       {
         alert("No tiene permiso para realizar esta operación");
	   }
}

function uf_delete(li_row)
 {     
    var borrar="";
    f=document.form1;
    f=document.form1;
    f.filadel.value=li_row;          
    f.operacion.value="DELETEROW"
    f.action="tepuy_soc_d_servicio.php";
    f.submit();
  }
  
function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8)  return true; // Enter 
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
   
function uf_catalogo_sistemas()
{
	f=document.form1;
	f.txtretencion.value="";
	f.txtdenpro.value="";
	pagina="tepuy_cxp_cat_sistemas_contadores.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
function uf_catalogo_procede()
{
	f=document.form1;
	codsis=f.txtcodsis.value;
	if(codsis=="")
	{
	  alert( " Por Favor Seleccione un Sistema");
	}
	else
	{
		pagina="tepuy_cxp_cat_procede_contadores.php?codsis="+codsis;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	}	
}
</script>
</html>
