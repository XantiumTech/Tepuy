<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_conexion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definición de Tipos de Unidades</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Control de Obras</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->

<!--  <tr>
    <td height="20" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	</td>
  </tr> -->
  <tr>
    <td height="42" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="tepuywindow_blank.php"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="tepuy_sob_d_organismo.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

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

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder/tepuy_sob_c_propietario.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_propietario=new tepuy_sob_c_propietario;
	$is_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_nompro=$_POST["txtnompro"];
		$ls_telpro=$_POST["txttelpro"];
		$ls_dirpro=$_POST["txtdirpro"];
		$ls_nomresppro=$_POST["txtnomresppro"];
		$ls_faxpro=$_POST["txtfaxpro"];
		$ls_emapro=$_POST["txtemapro"];
	}
	else
	{
		$ls_operacion="";
		$ls_codpro="";
		$ls_nompro="";
		$ls_telpro="";
		$ls_dirpro="";
		$ls_nomresppro="";
		$ls_faxpro="";
		$ls_emapro="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/tepuy_include.php");		
		require_once("../shared/class_folder/class_sql.php");
		
		$io_include=new tepuy_include();
		$con=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($con);
		$ls_codemp=$la_datemp["codemp"];
		$ls_codpro=$io_funcdb->uf_generar_codigo(true,$ls_codemp,"sob_propietario","codpro");
		$ls_nompro="";
		$ls_telpro="";
		$ls_dirpro="";
		$ls_nomresppro="";
		$ls_faxpro="";
		$ls_emapro="";
		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_propietario->uf_guardar_propietario($ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$la_seguridad);
		$ls_mensaje=$io_propietario->io_msgc;
		if($lb_valido===true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_codpro="";
		    $ls_nompro="";
		    $ls_telpro="";
		    $ls_dirpro="";
		    $ls_nomresppro="";
		    $ls_faxpro="";
		    $ls_emapro="";
		}
		else
		{
			if($lb_valido===0)
			{
				$ls_codpro="";
				$ls_nompro="";
				$ls_telpro="";
				$ls_dirpro="";
				$ls_nomresppro="";
				$ls_faxpro="";
				$ls_emapro="";
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
	
		}
		
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_propietario->uf_delete_propietario($ls_codpro,$la_seguridad);			
		$ls_mensaje=$io_propietario->io_msgc;
		if ($lb_valido===true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_codpro="";
		    $ls_nompro="";
		    $ls_telpro="";
		    $ls_dirpro="";
		    $ls_nomresppro="";
		    $ls_faxpro="";
		   $ls_emapro="";
		}
			
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="2" class="titulo-ventana">Definici&oacute;n de Organismos Ejecutores </td>
              </tr>
              <tr>
                <td ><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="hidstatus" type="hidden" id="hidstatus">				</td>
                <td >&nbsp;</td>
              </tr>
              <tr>
                <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
                <td width="334" ><input name="txtcodpro" type="text" id="txtcodpro" value="<? print  $ls_codpro?>" size="3" maxlength="3" style="text-align:center " readonly="true">                </td>
              </tr>
              <tr>
                <td width="134" height="22" align="right"><span class="style2">Nombre</span></td>
                <td width="334" ><input name="txtnompro" type="text" id="txtnompro"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_nompro?>" size="50" maxlength="50" >                </td>
              </tr>
              <tr>
                <td height="23" align="right">Persona Contacto</td>
                <td><input name="txtnomresppro"  type="text" id="txtnomresppro" value="<? print  $ls_nomresppro?>" size="50" maxlength="50"  onKeyPress="return(validaCajas(this,'x',event,50))" ></td>
              </tr>
              <tr>
                <td height="4" align="right">Direcci&oacute;n</td>
                <td><textarea name="txtdirpro" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)"  onKeyPress="return(validaCajas(this,'x',event,254))" cols="47" rows="2" id="txtdirpro" ><? print $ls_dirpro?></textarea></td>
              </tr>
              <tr align="left">
                <td height="22" align="right"><span class="style2">Tel&eacute;fono</span></td>
                <td><input name="txttelpro" id="txttelpro"  onKeyPress="return validaCajas(this,'i',event)"  value="<? print $ls_telpro?>" type="text" size="20" maxlength="20"></td>
              </tr>
              <tr>
                <td height="24" align="right">Fax</td>
                <td><input name="txtfaxpro" id="txtfaxpro"  onKeyPress="return validaCajas(this,'i',event)"  value="<? print $ls_faxpro?>" type="text" size="20" maxlength="20" ></td>
              </tr>
              <tr>
                <td height="4" align="right">E-mail</td>
                <td><input name="txtemapro"  onKeyPress="return validaCajas(this,'e',event)"  onChange="valida_Email(this)" type="text" id="txtemapro" value="<? print  $ls_emapro?>" size="50" maxlength="50"></td>
              </tr>
              <tr>
                <td height="8">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/***********************************************************************************************************************************/

		function ue_nuevo()
		{
			f=document.form1;
			li_incluir=f.incluir.value;
			if(li_incluir==1)
			{	
				  f.operacion.value="ue_nuevo";
				  f.txtcodpro.value="";
				  f.txtnompro.value="";
				  f.txttelpro.value="";
				  f.txtdirpro.value="";
				  f.txtnomresppro.value="";
				  f.txtfaxpro.value="";
				  f.txtemapro.value="";
				  f.action="tepuy_sob_d_organismo.php";
				  f.submit();
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}


		function ue_guardar()
		{
			f=document.form1;
			li_incluir=f.incluir.value;
			li_cambiar=f.cambiar.value;
			lb_status=f.hidstatus.value;
			if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
				 with(f)
				 { 
				  if (ue_valida_null(txtcodpro,"Código")==false)
				   {
					 txtcodpro.focus();
				   }
				   else
				   { 
					if (ue_valida_null(txtnompro,"Nombre")==false)
					 {
					  txtnompro.focus();
					 }
					 else
					 {
					  f.operacion.value="ue_guardar";
					  f.action="tepuy_sob_d_organismo.php";
					  f.submit();
					 }
				   }
				   }
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
   	    }					
					
		function ue_eliminar()
		{
		var borrar="";
		
		f=document.form1;
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if (f.txtcodpro.value=="")
			   {
				 alert("No ha seleccionado ningún registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("¿ Esta seguro de eliminar este registro ?"))
					   { 
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="tepuy_sob_d_organismo.php";
						 f.submit();
					   }
					else
					   { 
						 f=document.form1;
						 f.action="tepuy_sob_d_organismo.php";
						 alert("Eliminación Cancelada !!!");
						 f.txtcodpro.value="";
						 f.txtnompro.value="";
						 f.txttelpro.value="";
						 f.txtdirpro.value="";
						 f.txtnomresppro.value="";
						 f.txtfaxpro.value="";
						 f.txtemapro.value="";
						 f.submit();
					   }
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
			if(li_leer==1)
			{
				f.operacion.value="";			
				pagina="tepuy_cat_organismo.php";
				popupWin(pagina,"catalogo",600,250);
				//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no");
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}

/***********************************************************************************************************************************/

		function ue_cargarpropietario(cod,nom,tel,dir,nrp,fax,ema)
		{
			f=document.form1;
			f.hidstatus.value="C"
			f.txtcodpro.value=cod;
            f.txtnompro.value=nom;
            f.txttelpro.value=tel;
            f.txtdirpro.value=dir;
            f.txtnomresppro.value=nrp;
            f.txtfaxpro.value=fax;
            f.txtemapro.value=ema;
		}	
		
/***********************************************************************************************************************************/
				
		function EvaluateText(cadena, obj)
		{ 
		opc = false; 
			
			if (cadena == "%d")  
			  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
			  opc = true; 
			if (cadena == "%f"){ 
			 if (event.keyCode > 47 && event.keyCode < 58) 
			  opc = true; 
			 if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
			  if (event.keyCode == 46) 
			   opc = true; 
			} 
			 if (cadena == "%s") // toma numero y letras
			 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
			  opc = true; 
			 if (cadena == "%c") // toma numero y punto
			 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
			  opc = true; 
			if(opc == false) 
			 event.returnValue = false; 
		   } 		
		
</script>
</html>
