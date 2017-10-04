<?php
session_start();
//ini_set('display_errors', 1);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_folder/class_funciones_sfa.php");
$io_fun_sfa= new class_funciones_sfa();
$io_fun_sfa->uf_load_seguridad("SFA","tepuy_sfa_d_pedidos.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_sfa->uf_select_config("SFA","REPORTE","PEDIDOS","tepuy_sfa_r_pedidos.php","C");

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_seleccionarcombo
		//         Access: private
		//      Argumento: $as_valores      // valores que puede tomar el combo				
		//                 $as_seleccionado // item seleccionado				
		//                 $aa_parametro    // arreglo de seleccionados		
		//                 $li_total        // total de elementos en el combo
		//	      Returns:
		//    Description: Funcion que mantiene la seleccion de un combo despues de hacer un submit
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 		
		//                 $ai_totrows // ultima fila pintada en el grid		
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid cuando es una factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=20 maxlength=50 readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catproducto(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<input name=txtunimed".$ai_totrows." type=text id=txtunimed".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_articulospedido(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_articulospedido(".$ai_totrows.");'";
		$aa_object[$ai_totrows][5]="<input name=txtpreart".$ai_totrows."    type=text id=txtpreart".$ai_totrows."    class=sin-borde size=10 maxlength=12 readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtporiva".$ai_totrows." type=text id=txtporiva".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montospedido(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][7]="<input name=txtsubtotart".$ai_totrows." type=text id=txtsubtotart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][8]="<input name=txtsubivaart".$ai_totrows." type=text id=txtsubivaart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][9]="<input name=txttotart".$ai_totrows." type=text id=txttotart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][10]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][11]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";			
			
   }
   //--------------------------------------------------------------
   function uf_agregarlineablancaorden(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancaorden
		//         Access: private
		//      Argumento: $aa_object  // arreglo de titulos 		
		//                 $ai_totrows // ultima fila pintada en el grid		
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid cuando es una orden de compra
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows."    type=text id=txtcodart".$ai_totrows."    class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catproducto(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<input name=txtunimed".$ai_totrows." type=text id=txtunimed".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtpreart".$ai_totrows."    type=text id=txtpreart".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);' readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtporiva".$ai_totrows." type=text id=txtporiva".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][7]="<input name=txtsubtotart".$ai_totrows." type=text id=txtsubtotart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
		$aa_object[$ai_totrows][8]="<input name=txtsubivaart".$ai_totrows." type=text id=txtsubivaart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";





		$aa_object[$ai_totrows][9]="<input name=txttotart".$ai_totrows." type=text id=txttotart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
        $aa_object[$ai_totrows][10]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";

   }
   //--------------------------------------------------------------
   function uf_pintartituloorden(&$lo_object,&$lo_title)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintartituloorden
		//         Access: private
		//      Argumento: $lo_object  // arreglo de objetos
		//				   $lo_title   // arreglo de titulos 	
		//	      Returns:
		//    Description: Funci�n que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_title="";
		$lo_object="";
		$lo_title[1]="Producto";
		$lo_title[2]="Unidad de Medida";
		$lo_title[3]="Cant. Disponible";
		$lo_title[4]="Cant. a Facturar";
		$lo_title[5]="Precio Unit.";
		$lo_title[6]="% I.V.A.";
		$lo_title[7]="Sub-Total";
		$lo_title[8]="Impuesto";
		$lo_title[9]="Total";
		$lo_title[10]="";
		$lo_title[11]="";
   }
   //--------------------------------------------------------------
   function uf_pintardetalle($ai_totrows,$ls_estpro)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $ai_totrows    // cantidad de filas que tiene el grid
		//				   $ls_estpro     // indica que valor tiene el radiobutton 0--> Orden de compra 1--> Factura
		//				   $ls_checkedord // variable imprime o no "checked" para el radiobutton en la orden de compra
		//				   $ls_checkedped // variable imprime o no "checked" para el radiobutton en la factura
		//	      Returns:
		//    Description: Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/02/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $lo_object;
		if($ls_estpro==0)
		{
			$ls_checkedord="checked";
			$ls_checkedped="";
		}
		elseif($ls_estpro==1)
		{
			$ls_checkedord="";
			$ls_checkedped="checked";
		}
		else
		{
			$ls_checkedord="";
			$ls_checkedped="";
		}
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{	
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codart=    $_POST["txtcodart".$li_i];
			$ls_denart=    $_POST["txtdenart".$li_i];
			if (array_key_exists("la_logusr",$_POST))
			{
				$ls_unidad=    $_POST["txtunidad".$li_i];  
			}
			else
			{
				$ls_unidad=    "";
			}
			$li_canart=    $_POST["txtcanart".$li_i];
			$li_penart=    $_POST["txtpreart".$li_i];
			$li_preuniart= $_POST["txtporiva".$li_i];
			$li_canoriart= $_POST["txtcanoriart".$li_i];
			$li_monsubtotart= $_POST["txtsubtotart".$li_i];
			$li_monsubivaart= $_POST["txtsubivaart".$li_i];
			$li_montotart= $_POST["txttotart".$li_i];
			//uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
					
			$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
								 "<input name=txtcodart".$li_i."    type=hidden id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catproducto(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
			$lo_object[$li_i][2]="<div align='center'></div><input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
			$lo_object[$li_i][3]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."'  readonly>";
			$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$lo_object[$li_i][5]="<input name=txtpreart".$li_i."    type=text id=txtpreart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_penart."' readonly>";
			$lo_object[$li_i][6]="<input name=txtporiva".$li_i." type=text id=txtporiva".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
			$lo_object[$li_i][7]="<input name=txtsubtotart".$li_i." type=text id=txtsubtotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_monsubtotart."' style='text-align:right' readonly>";
			$lo_object[$li_i][8]="<input name=txtsubivaart".$li_i." type=text id=txtsubivaart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_monsubivaart."' style='text-align:right' readonly>";
			$lo_object[$li_i][9]="<input name=txttotart".$li_i." type=text id=txttotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
			$lo_object[$li_i][10]="";
			if($ls_estpro==1)
			{
				$lo_object[$li_i][10]="";
				$lo_object[$li_i][11]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";			
			}
  	    } 
		if($ls_estpro==1)
		{
		  uf_agregarlineablanca($lo_object,$ai_totrows);	
		}
		else
		{
		  uf_agregarlineablanca($lo_object,$ai_totrows+1);
		}
   }
  	//--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//         Access: private
		//      Argumento:  	
		//	      Returns:
		//    Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_numpedido,$ls_numconrec,$ls_cedcli,$ls_nomcli,$ls_codalm,$ls_nomfisalm,$ld_fecrec,$ls_obsped,$li_totped,$ls_status,$li_subtotped,$li_moniva,$ls_forpag,$ls_denforpag;
		global $ls_checkedord,$ls_checkedped,$ls_checkedparc,$ls_checkedcomp,$ls_codusu,$ls_readonly,$ls_readonlyrad,$li_totrows,$ls_hidsaverev;
		
		$ls_numpedido="";
		$ls_numconrec="";
		$ls_cedcli="";
		$ls_nomcli="";
		$ls_forpag="";
		$ls_denforpag="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fecrec="";
		$ls_obsped="";
		$li_totped="0,00";
		$li_subtotped="0,00";
		$li_moniva="0,00";
		$ls_checkedord="";
		$ls_checkedped="";
		$ls_checkedparc="";
		$ls_checkedcomp="";
		$ls_readonlyrad="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_status="";
		$li_totrows=1;
		$ls_hidsaverev="false";
   }
  	//--------------------------------------------------------------
   function uf_obtenervalorunidad($li_i)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalorunidad
		//         Access: private
		//      Argumento: $li_i  //  indica que opcion esta seleccionado en el combo	
		//	      Returns: Retorna el valor obtenido
		//    Description: Funci�n que obtiene el contenido del combo txtunimed o del campo txtunidad deacuerdo sea el caso 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 08/01/2015								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("txtunimed".$li_i,$_POST))
		{
			$ls_valor= $_POST["txtunimed".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtunidad".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor; 
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Elaboraci&oacute;n de Pedidos</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/sfa.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
</script></head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Facturaci�n </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>-->
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
  <tr>
    <td height="42" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
<!--    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td> -->
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_anular();"><img src="../shared/imagebank/tools20/anular.png" alt="Anular" width="20" height="20" border="0" title="Anular"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=  new tepuy_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("class_folder/tepuy_sfa_c_factura.php");
	$io_sfa= new tepuy_sfa_c_factura("../");
	require_once("tepuy_sfa_c_productos.php");
	$io_pro= new tepuy_sfa_c_productos();
	require_once("tepuy_sfa_c_movimientoinventario.php");
	$io_mov= new tepuy_sfa_c_movimientoinventario();
	require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
	$io_keygen= new tepuy_c_generar_consecutivo();

	require_once("class_folder/tepuy_sfa_c_factura.php");
	$io_sfa=new tepuy_sfa_c_factura("../");
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = $io_fun_sfa->uf_obtenervalor("totalfilas",1);
	$ls_hidsaverev = $io_fun_sfa->uf_obtenervalor("hidsaverev","");

	$ls_emp="";
	$ls_tabla="sfa_pedidos";
	$ls_columna="numpedido";
	//$ls_numpedido=$io_keygen->uf_generar_numero_nuevo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);  
    	$ls_numpedido=$io_keygen->uf_generar_numero_nuevo("SFA","sfa_pedidos","numpedido","SFA",10,"","","");
	if($ls_numpedido==false)
	{
		print "<script language=JavaScript>";
		print "location.href='tepuywindow_blank.php'";
		print "</script>";
	}
	$ls_titletable="Detalle del Pedido";
	$li_widthtable=900;
	$ls_nametable="grid";
	$lo_title[1]="Producto";
	$lo_title[2]="Unidad de Medida";
	$lo_title[3]="Cant. Disponible";
	$lo_title[4]="Cant. a Solicitar";
	$lo_title[5]="Precio Unit.";
	$lo_title[6]="% I.V.A.";
	$lo_title[7]="Sub-Total";
	$lo_title[8]="Impuesto";
	$lo_title[9]="Total";
	$lo_title[10]="";
	$lo_title[11]="";
	
	$ls_operacion= $io_fun_sfa->uf_obteneroperacion();
	$ls_status=    $io_fun_sfa->uf_obtenervalor("hidestatus","");
	if($ls_status=="C")
	{
		$ls_readonly=  $io_fun_sfa->uf_obtenervalor("hidreadonly","");
		$li_catafilas= $io_fun_sfa->uf_obtenervalor("catafilas","");
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_status="";
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$ls_numpedido=$io_keygen->uf_generar_numero_nuevo("SFA","sfa_pedidos","numpedido","SFA",10,"","","");
			$li_totrows=1;
			$ld_fecrec=date("d/m/Y");
		break;

		case "NUEVOPEDIDO":
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="";
			$ls_checkedped="checked";
			$ls_checkedparc="";
			$ls_checkedcomp="checked";
			$ls_readonlyrad="onClick='return false'";
			$ld_fecrec=date("d/m/Y");
		break;
		
		case "NUEVAORDEN":
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="checked";
			$ls_checkedped="";
			$ls_checkedparc="";
			$ls_checkedcomp="checked";
			$ls_readonlyrad="";
			$ls_readonly="readonly";
			uf_pintartituloorden($lo_object,$lo_title);
			uf_agregarlineablancaorden($lo_object,1);
			$ld_fecrec=date("d/m/Y");
		break;
		
		case "GUARDAR":
			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumpedido","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_forpag=    $io_fun_sfa->uf_obtenervalor("cmbforpag","");
			$ls_denforpag=    $io_fun_sfa->uf_obtenervalor("txtdenforpag","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			$li_totped= $io_fun_sfa->uf_obtenervalor("txttotped","");
			$li_subtotped=    $io_fun_sfa->uf_obtenervalor("txtsubtotped","");
			$li_moniva=    $io_fun_sfa->uf_obtenervalor("txttotivaped","");

			$li_subtotped= str_replace(".","",$li_subtotped);
			$li_subtotped= str_replace(",",".",$li_subtotped);
			$li_moniva= str_replace(".","",$li_moniva);
			$li_moniva= str_replace(",",".",$li_moniva);
			$li_totped= str_replace(".","",$li_totped);
			$li_totped= str_replace(",",".",$li_totped);

			$ls_readonly="readonly";
			$ls_numpedido=$io_func->uf_cerosizquierda($ls_numpedido,10);
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrecbd);
			if($lb_valido)
			{
				if ($ls_status!="C")
				{
					$lb_encontrado=$io_sfa->uf_sfa_select_factura($ls_codemp,$ls_numpedido);
					if ($lb_encontrado)
					{
						$io_msg->message("El numero de Pedido ya existe"); 
						uf_pintardetalle($li_totrows+1,$ls_estpro);
					}
					else
					{	
						$ls_numconrec="";  
						$io_sql->begin_transaction();
						$lb_valido=$io_sfa->uf_sfa_insert_pedidos($ls_codemp,$ls_numpedido,$ls_cedcli,$ld_fecrecbd,$ls_forpag,$ls_obsped,$li_subtotped,$li_moniva,$li_totped,$ls_codusu,&$ls_numpedido,$la_seguridad);	
						if ($lb_valido)
						{
							if($ls_estpro==0)
							{
								$li_totrowsaux=$li_totrows+1;
							}
							else
							{
								$li_totrowsaux=$li_totrows;
							}
							for($li_i=1;$li_i<$li_totrowsaux;$li_i++)
							{
								$ls_unidad=    uf_obtenervalorunidad($li_i);
								$li_unidad=    $io_fun_sfa->uf_obtenervalor("txtunimed".$li_i,"");
								$ls_codart=    $io_fun_sfa->uf_obtenervalor("txtcodart".$li_i,"");
								$li_canart=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
								$li_prodes=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
								$li_penart=    $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
								$li_preuniart= $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
								$li_canoriart= $io_fun_sfa->uf_obtenervalor("txtcanoriart".$li_i,"");
								$li_monsubtotart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
								$li_monsubivaart= $io_fun_sfa->uf_obtenervalor("txtsubivaart".$li_i,"");
								$li_monsubart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
								$li_montotart= $io_fun_sfa->uf_obtenervalor("txttotart".$li_i,"");
								$li_poriva= $io_fun_sfa->uf_obtenervalor("txtporiva".$li_i,"");
								$li_poriva= str_replace(",",".",$li_poriva);
								
								$li_canart=    str_replace(".","",$li_canart);
								$li_canart=    str_replace(",",".",$li_canart);
								$li_penart=    str_replace(".","",$li_penart);
								$li_penart=    str_replace(",",".",$li_penart);
								$li_preuniart= str_replace(".","",$li_preuniart);
								$li_preuniart= str_replace(",",".",$li_preuniart);
								$li_canoriart= str_replace(".","",$li_canoriart);
								$li_canoriart= str_replace(",",".",$li_canoriart);
								$li_monsubtotart= str_replace(".","",$li_monsubtotart);
								$li_monsubtotart= str_replace(",",".",$li_monsubtotart);
								$li_monsubivaart= str_replace(".","",$li_monsubivaart);
								$li_monsubivaart= str_replace(",",".",$li_monsubivaart);
								$li_montotart= str_replace(".","",$li_montotart);
								$li_montotart= str_replace(",",".",$li_montotart);
								if($li_canart!="")
								{
									$lb_valido=$io_sfa->uf_sfa_insert_dt_pedido($ls_codemp,$ls_numpedido,$ls_codart,$li_canoriart,$li_canart,$li_poriva,$li_preuniart,$li_monsubtotart,$li_monsubivaart,$li_montotart,$li_i,$la_seguridad);
									if($lb_valido)
									{
										$lb_valido=$io_sfa->uf_sfa_actualizar_cantidad_productos($ls_codemp,$ls_codart,$li_prodes,$la_seguridad,"-");
									}
								}
							}
						}
						
						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("La pedido Nro. ".$ls_numpedido." ha sido procesada");
							if($ls_estpro==0)
							{
								uf_pintartituloorden($lo_object,$lo_title);
							}
							uf_pintardetalle($li_totrowsaux,"1");
							$ls_status="C";
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo procesar el pedido...");
							uf_pintardetalle($li_totrowsaux,$ls_estpro);
						}
					}
				}
				else
				{
					$io_msg->message("El Pedido no puede ser modificado");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$li_totrows);
					uf_limpiarvariables();
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_limpiarvariables();
			}
		break;

		case "ANULAR":
			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumpedido","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_forpag=    $io_fun_sfa->uf_obtenervalor("cmbforpag","");
			$ls_denforpag=	$io_fun_sfa->uf_obtenervalor("txtdenforpag","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			$li_totped= $io_fun_sfa->uf_obtenervalor("txttotped","");
			$li_subtotped=    $io_fun_sfa->uf_obtenervalor("txtsubtotped","");
			$li_moniva=    $io_fun_sfa->uf_obtenervalor("txttotivaped","");

			$li_subtotped= str_replace(".","",$li_subtotped);
			$li_subtotped= str_replace(",",".",$li_subtotped);
			$li_moniva= str_replace(".","",$li_moniva);
			$li_moniva= str_replace(",",".",$li_moniva);
			$li_totped= str_replace(".","",$li_totped);
			$li_totped= str_replace(",",".",$li_totped);

			$ls_readonly="readonly";
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrecbd);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_sfa->uf_sfa_anular_pedido($ls_codemp,$ls_numpedido,$la_seguridad);	
				if ($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El pedido Nro. ".$ls_numpedido." ha sido anulado");
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo Anular el Pedido...");
				}
			}
		break;


		case "ELIMINAR":
			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumpedido","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_forpag=    $io_fun_sfa->uf_obtenervalor("cmbforpag","");
			$ls_denforpag=	$io_fun_sfa->uf_obtenervalor("txtdenforpag","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			$li_totped= $io_fun_sfa->uf_obtenervalor("txttotped","");
			$li_subtotped=    $io_fun_sfa->uf_obtenervalor("txtsubtotped","");
			$li_moniva=    $io_fun_sfa->uf_obtenervalor("txttotivaped","");

			$li_subtotped= str_replace(".","",$li_subtotped);
			$li_subtotped= str_replace(",",".",$li_subtotped);
			$li_moniva= str_replace(".","",$li_moniva);
			$li_moniva= str_replace(",",".",$li_moniva);
			$li_totped= str_replace(".","",$li_totped);
			$li_totped= str_replace(",",".",$li_totped);

			$ls_readonly="readonly";
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrecbd);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_sfa->uf_sfa_eliminar_pedido($ls_codemp,$ls_numpedido,$ls_cedcli,$ld_fecrecbd,$ls_forpag,$ls_obsped,$li_subtotped,$li_moniva,$li_totped,$ls_codusu,&$ls_numpedido,$la_seguridad);	
				if ($lb_valido)

				{
					if($ls_estpro==0)
					{
						$li_totrowsaux=$li_totrows+1;
					}
					else
					{
						$li_totrowsaux=$li_totrows;
					}
					for($li_i=1;$li_i<$li_totrowsaux;$li_i++)
					{
						$ls_unidad=    uf_obtenervalorunidad($li_i);
						$li_unidad=    $io_fun_sfa->uf_obtenervalor("txtunimed".$li_i,"");
						$ls_codart=    $io_fun_sfa->uf_obtenervalor("txtcodart".$li_i,"");
						$li_canart=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
						$li_prodes=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
						$li_penart=    $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
						$li_preuniart= $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
						$li_canoriart= $io_fun_sfa->uf_obtenervalor("txtcanoriart".$li_i,"");
						$li_monsubtotart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
						$li_monsubivaart= $io_fun_sfa->uf_obtenervalor("txtsubivaart".$li_i,"");
						$li_monsubart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
						$li_montotart= $io_fun_sfa->uf_obtenervalor("txttotart".$li_i,"");
						$li_poriva= $io_fun_sfa->uf_obtenervalor("txtporiva".$li_i,"");
						$li_poriva= str_replace(",",".",$li_poriva);
								
						$li_canart=    str_replace(".","",$li_canart);
						$li_canart=    str_replace(",",".",$li_canart);
						$li_penart=    str_replace(".","",$li_penart);
						$li_penart=    str_replace(",",".",$li_penart);
						$li_preuniart= str_replace(".","",$li_preuniart);
						$li_preuniart= str_replace(",",".",$li_preuniart);
						$li_canoriart= str_replace(".","",$li_canoriart);
						$li_canoriart= str_replace(",",".",$li_canoriart);
						$li_monsubtotart= str_replace(".","",$li_monsubtotart);
						$li_monsubtotart= str_replace(",",".",$li_monsubtotart);
						$li_monsubivaart= str_replace(".","",$li_monsubivaart);
						$li_monsubivaart= str_replace(",",".",$li_monsubivaart);
						$li_montotart= str_replace(".","",$li_montotart);
						$li_montotart= str_replace(",",".",$li_montotart);
						if($li_canart!="")
						{
							$lb_valido=$io_sfa->uf_sfa_eliminar_dt_pedido($ls_codemp,$ls_numpedido,$ls_codart,$li_canoriart,$li_canart,$li_poriva,$li_preuniart,$li_monsubtotart,$li_monsubivaart,$li_montotart,$li_i,$la_seguridad);
							if($lb_valido)
							{
								$lb_valido=$io_sfa->uf_sfa_actualizar_cantidad_productos($ls_codemp,$ls_codart,$li_prodes,$la_seguridad,"+");
							}
						}
					}
				}		
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El Pedido Nro.".$ls_numpedido." ha sido eliminado");
					if($ls_estpro==0)
					{
						uf_pintartituloorden($lo_object,$lo_title);
					}
					uf_pintardetalle($li_totrowsaux,"1");
					$ls_status="C";
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo Eliminar el Pedido...");
					uf_pintardetalle($li_totrowsaux,$ls_estpro);
				}
			}
		break;
		
		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			$ls_readonly="";

			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumpedido","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ls_codalm=    $io_fun_sfa->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_sfa->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_denforpag= $io_fun_sfa->uf_obtenervalor("txtdenforpag","");
			$ls_forpag=	$io_fun_sfa->uf_obtenervalor("cmbforpag","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			$li_totped=    $io_fun_sfa->uf_obtenervalor("txttotped","");
			$li_subtotped=    $io_fun_sfa->uf_obtenervalor("txtsubtotped","");
			$li_moniva=    $io_fun_sfa->uf_obtenervalor("txttotivaped","");

			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$ls_unidad= uf_obtenervalorunidad($li_i);
				$li_unidad=    $io_fun_sfa->uf_obtenervalor("hidunidad".$li_i,"");
				$ls_codart=    $io_fun_sfa->uf_obtenervalor("txtcodart".$li_i,"");
				$ls_denart=    $io_fun_sfa->uf_obtenervalor("txtdenart".$li_i,"");
				$li_canart=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
				$li_penart=    $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
				$li_preuniart= $io_fun_sfa->uf_obtenervalor("txtporiva".$li_i,"");
				$li_canoriart= $io_fun_sfa->uf_obtenervalor("txtcanoriart".$li_i,"");
				$li_monsubtotart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
				$li_monsubivaart= $io_fun_sfa->uf_obtenervalor("txtsubivaart".$li_i,"");
				$li_montotart= $io_fun_sfa->uf_obtenervalor("txttotart".$li_i,"");

				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				if (($ls_status=="C")&&($li_i<=$li_catafilas))
				{
					
				}
				else
				{
					$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
										 "<input name=txtcodart".$li_i."    type=hidden id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catproducto(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
					$lo_object[$li_i][3]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' readonly>";
					$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' readonly>";
					$lo_object[$li_i][5]="<input name=txtpreart".$li_i."    type=text id=txtpreart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_penart."'readonly>";
					$lo_object[$li_i][6]="<input name=txtporiva".$li_i." type=text id=txtporiva".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
					$lo_object[$li_i][7]="<input name=txtsubtotart".$li_i." type=text id=txtsubtotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_monsubtotart."' style='text-align:right' readonly>";
					$lo_object[$li_i][8]="<input name=txtsubivaart".$li_i." type=text id=txtsubivaart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_monsubivaart."' style='text-align:right' readonly>";
					$lo_object[$li_i][9]="<input name=txttotart".$li_i." type=text id=txttotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
					$lo_object[$li_i][10]="";
					$lo_object[$li_i][11]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";			
				}

			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
		break;
		
		case "ELIMINARDETALLE":
			$ls_readonly="";
			if(array_key_exists("radiotipo",$_POST))
			{
				$ls_radiotipo= $io_fun_sfa->uf_obtenervalor("radiotipo","");
				if($ls_radiotipo=="0")
				{
					$ls_checkedord="checked";
					$ls_checkedped="";
				}
				if ($ls_radiotipo=="1")
				{
					$ls_checkedord="";
					$ls_checkedped="checked";
				}
			}
			else
			{
					$ls_checkedord="";
					$ls_checkedped="";
			}
			if(array_key_exists("radiotipentrega",$_POST))
			{
				$ls_estrec= $io_fun_sfa->uf_obtenervalor("radiotipentrega","");
				if($ls_estrec==0)
				{
					$ls_checkedparc="checked";
					$ls_checkedcomp="";
					$ls_readonlyrad="";
				}
				else
				{
					$ls_checkedparc="";
					$ls_checkedcomp="checked";
					$ls_readonlyrad="onClick='return false'";
				}
			}
			else
			{
					$ls_checkedparc="";
					$ls_checkedcomp="";
					$ls_readonlyrad="";
			}

			$ls_numconrec="";
			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumordcom","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ls_codalm=    $io_fun_sfa->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_sfa->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			
			$li_totrows=$li_totrows-1;
			$li_rowdelete= $io_fun_sfa->uf_obtenervalor("filadelete","");
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_unidad= uf_obtenervalorunidad($li_i);
					$li_unidad=    $io_fun_sfa->uf_obtenervalor("hidunidad".$li_i,"");
					$ls_codart=    $io_fun_sfa->uf_obtenervalor("txtcodart".$li_i,"");
					$ls_denart=    $io_fun_sfa->uf_obtenervalor("txtdenart".$li_i,"");
					$li_canart=    $io_fun_sfa->uf_obtenervalor("txtcanart".$li_i,"");
					$li_penart=    $io_fun_sfa->uf_obtenervalor("txtpreart".$li_i,"");
					$li_preuniart= $io_fun_sfa->uf_obtenervalor("txtporiva".$li_i,"");
					$li_canoriart= $io_fun_sfa->uf_obtenervalor("txtcanoriart".$li_i,"");
					$li_monsubtotart= $io_fun_sfa->uf_obtenervalor("txtsubtotart".$li_i,"");
					$li_monsubivaart= $io_fun_sfa->uf_obtenervalor("txtsubivaart".$li_i,"");
					$li_montotart= $io_fun_sfa->uf_obtenervalor("txttotart".$li_i,"");
					uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
			
					$lo_object[$li_temp][1]="<input name=txtdenart".$li_temp."    type=text id=txtdenart".$li_temp."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$li_temp."    type=hidden id=txtcodart".$li_temp."    class=sin-borde size=20 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catproducto(".$li_temp.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_temp][2]="<input name=txtunidad".$li_temp."    type=text id=txtunidad".$li_temp."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtcanoriart".$li_temp." type=text id=txtcanoriart".$li_temp." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][4]="<input name=txtcanart".$li_temp."    type=text id=txtcanart".$li_temp."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][5]="<input name=txtpreart".$li_temp."    type=text id=txtpreart".$li_temp."    class=sin-borde size=10 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][6]="<input name=txtporiva".$li_temp." type=text id=txtporiva".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][7]="<input name=txtsubtotart".$li_temp." type=text id=txtsubtotart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_monsubtotart."' onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
					$lo_object[$li_temp][8]="<input name=txtsubivaart".$li_temp." type=text id=txtsubivaart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_monsubivaart."' onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
					$lo_object[$li_temp][9]="<input name=txttotart".$li_temp." type=text id=txttotart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
					$lo_object[$li_temp][10]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][11]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";			
				}
				else
				{
					$li_rowdelete= 0;
				}
			}
			if ($li_temp==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{				
				uf_agregarlineablanca($lo_object,$li_totrows);
			}

		break;
		
		case "BUSCARDETALLE":
			
			$ls_numpedido= $io_fun_sfa->uf_obtenervalor("txtnumpedido","");
			$ls_cedcli=    $io_fun_sfa->uf_obtenervalor("txtcedcli","");
			$ls_nomcli=    $io_fun_sfa->uf_obtenervalor("txtnomcli","");
			$ls_forpag=    $io_fun_sfa->uf_obtenervalor("cmbforpag","");
			$ls_denforpag= $io_fun_sfa->uf_obtenervalor("cmbforpag",$_POST["txtdenforpag"]);
			$ld_fecrec=    $io_fun_sfa->uf_obtenervalor("txtfecrec","");
			$ls_obsped=    $io_fun_sfa->uf_obtenervalor("txtobsped","");
			uf_pintartituloorden($lo_object,$lo_title);
		$lb_valido=$io_sfa->uf_sfa_obtener_dt_pedido($ls_codemp,$ls_numpedido,$li_totrows,$lo_object,$li_subtotped,$li_moniva,$li_totped,$ls_status);
			$li_totped=number_format($li_totped,2,',','.');
			$li_subtotped= number_format($li_subtotped,2,',','.');
			$li_moniva= number_format($li_moniva,2,',','.');
			$nuevo=($li_totrows+1);
			uf_agregarlineablanca($lo_object,$nuevo);
			uf_agregarlineablancaorden($lo_object,$nuevo);
		break;
		

	}
?>

<p>&nbsp;</p>
<div align="center">
  <table width="767" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="669" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sfa->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_sfa);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="755" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="744" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Elaboraci&oacute;n de Pedidos</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="156" height="19">&nbsp;</td>
                    <td width="373"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly">
                      <input name="txtnumconrec" type="hidden" id="txtnumconrec" value="<?php print $ls_numconrec ?>"></td>
                    <td width="65"><div align="right"></div></td>
                    <td width="148">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right"> </div></td>
                    <td><div align="right">Fecha</div></td>
                    <td><input name="txtfecrec" type="text" id="txtfecrec" style="text-align:center " onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecrec ?>" size="17" maxlength="10" datepicker="true"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">(*) Numero de Pedido  </div></td>
                    <td height="22" colspan="3"><div align="left">
                      <label>
                      <input name="txtnumpedido" type="text" id="txtnumpedido" style="text-align:center" value="<?php print $ls_numpedido ; ?>" size="20" maxlength="15" read>
                      </label></div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">(*) Cliente</div></td>
                    <td height="22" colspan="3">
                      <div align="left">
                        <input name="txtcedcli" type="text" id="txtcedcli" value="<?php print $ls_cedcli?>" size="15" maxlength="10" style="text-align:center " readonly>
                          <a href="javascript: ue_catcliente();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                          <input name="txtnomcli" type="text" class="sin-borde" id="txtnomcli" value="<?php print $ls_nomcli ?>" size="50" readonly>
                      </div></td>
                  </tr>
          <tr> 
              <input name="denforpag" type="hidden" id="denforpag" value="<?php print $ls_denforpag; ?>">
            <td height="22" style="text-align:right">Forma de Pago</td>
            <td height="22" colspan="2"><div  id="forpag" align="left">
              <?php $io_sfa->uf_sfa_combo_formapago($ls_forpag); ?>
            </div></td>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">Notas</div></td>
                    <td colspan="3" rowspan="2">
                      <div align="left">
                        <textarea name="txtobsped" cols="97" rows="3" id="txtobsped"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obsped ?></textarea>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20">&nbsp;</td>
                    </tr>

                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    <td colspan="3">                      <input name="txtdesalm" type="hidden" id="txtdesalm">
                      <input name="txttelalm" type="hidden" id="txttelalm">
                      <input name="txtubialm" type="hidden" id="txtubialm">
                      <input name="txtnomresalm" type="hidden" id="txtnomresalm">
                      <input name="txttelresalm" type="hidden" id="txttelresalm">
                      <input name="hidstatus" type="hidden" id="hidstatus">
                      <input name="hidsaverev" type="hidden" id="hidsaverev" value="<?php print $ls_hidsaverev; ?>"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28" colspan="4"><p>
                      <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                    </p>                      </td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="28">&nbsp;</td>
                    <td height="28">&nbsp;</td>
                    <td height="28"><div align="right">Sub-Total</div></td>
                    <td height="28"><input name="txtsubtotped" type="text" id="txtsubtotped" value="<? print $li_subtotped; ?>" size="17" style="text-align:right" readonly></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28">&nbsp;</td>
                    <td height="28">&nbsp;</td>
                    <td height="28"><div align="right">Total Impuesto</div></td>
                    <td height="28"><input name="txttotivaped" type="text" id="txttotivaped" value="<? print $li_moniva; ?>" size="17" style="text-align:right" readonly></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28">&nbsp;</td>
                    <td height="28">&nbsp;</td>
                    <td height="28"><div align="right">Total</div></td>
                    <td height="28"><input name="txttotped" type="text" id="txttotped" value="<? print $li_totped; ?>" size="17" style="text-align:right" readonly></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input name="operacion" type="hidden" id="operacion">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="filadelete" type="hidden" id="filadelete">
            <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
	    <input name="txtforpag"     type="hidden" id="txtforpag"     value="<?php print $ls_forpag; ?>">
	    <input name="txtdenforpag"     type="hidden" id="txtdenforpag"     value="<?php print $ls_denforpag; ?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_catproducto(li_linea)
{
	window.open("tepuy_sfa_cat_productos_pedidos.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catalmacen()
{
	window.open("tepuy_catdinamic_almacen.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catcliente()
{
	f=document.form1;
		window.open("tepuy_catdinamic_cli.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cataorden()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.txtnumpedido.value="";
		f.txtcedcli.value="";
		f.txtnomcli.value="";
		f.txtobsped.value="";
		f.operacion.value="NUEVOPEDIDO";
		f.action="tepuy_sfa_d_pedidos.php";
		f.submit();
	}
	else
	{
		f.radiotipo[0].checked=false;
		f.radiotipo[1].checked=false;
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("tepuy_catdinamic_pedidos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="tepuy_sfa_d_pedidos.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_estatus=f.hidestatus.value;
		if(ls_estatus==2)
		{
			alert("Este pedido posee una factura Contacte al Administrador");
		}
		else
		{
			numpedido=  f.txtnumpedido.value;
			numpedido=ue_validarvacio(numpedido);
			cedcli=    f.txtcedcli.value;
			cedcli=ue_validarvacio(cedcli);
			nomcli=    f.txtnomcli.value;
			nomcli=ue_validarvacio(nomcli);
			li_total=f.totalfilas.value;
			if((numpedido!="")&&(nomcli!="")&&(cedcli!="")&&(li_total>0))
			{
				
				fecfac=    f.txtfecrec.value;
				obsfac=    f.txtobsped.value;
				ls_reporte="tepuy_sfa_r_pedido_waryna.php";
				window.open("reportes/"+ls_reporte+"?numpedido="+numpedido+"&fecfac="+fecfac+"&obsfac="+obsfac+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe existir un documento a imprimir");
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function uf_agregar_dt(li_row)
{
	f=document.form1;

	ls_numpedido=eval("f.txtnumpedido.value");
	ls_numpedido=ue_validarvacio(ls_numpedido);
	ls_cedcli=eval("f.txtcedcli.value");
	ls_cedcli=ue_validarvacio(ls_cedcli);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	
	if((ls_numpedido=="")||(ls_cedcli=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales marcados con (*)");
		lb_valido=true;
	}
	ls_codnewart= eval("f.txtcodart"+li_row+".value");
	ls_codnewuni= eval("f.txtunimed"+li_row+".value");
	ls_codnewcan= eval("f.txtcanart"+li_row+".value");
	ls_codnewpen= eval("f.txtpreart"+li_row+".value");
	ls_codnewpre= eval("f.txtporiva"+li_row+".value");
	ls_codnewori= eval("f.txtcanoriart"+li_row+".value");
	ls_codnewmon= eval("f.txtsubtotart"+li_row+".value");
	ls_codnewmon= eval("f.txttotart"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;
	
	for(li_i=1;li_i<li_total&&lb_valido!=true;li_i++)
	{
		ls_codart=    eval("f.txtcodart"+li_i+".value");
		ls_unidad=    eval("f.txtunidad"+li_i+".value");
		ls_canart=    eval("f.txtcanart"+li_i+".value");
		ls_penart=    eval("f.txtpreart"+li_i+".value");
		ls_preuniart= eval("f.txtporiva"+li_i+".value");
		ls_canoriart= eval("f.txtcanoriart"+li_i+".value");
		ls_montotord= eval("f.txtsubtotart"+li_i+".value");
		if((ls_codart==ls_codnewart)&&(ls_unidad==ls_codnewuni)&&(li_i!=li_row))
		{
			alert("El Producto se encuentra agregado en el pedido");
			lb_valido=true;
		}
	}

	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_unidad=eval("f.txtunimed"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_preart=eval("f.txtpreart"+li_row+".value");
	ls_preart=ue_validarvacio(ls_preart);
	ls_ivaart=eval("f.txtporiva"+li_row+".value");
	ls_ivaart=ue_validarvacio(ls_ivaart);
	ls_subtotart=eval("f.txtsubtotart"+li_row+".value");
	ls_subtotart=ue_validarvacio(ls_subtotart);
	ls_subivaart=eval("f.txtsubivaart"+li_row+".value");
	ls_subivaart=ue_validarvacio(ls_subivaart);
	ls_montotart=eval("f.txttotart"+li_row+".value");
	ls_montotart=ue_validarvacio(ls_montotart);

	if((ls_codart=="")||(ls_unidad=="")||(ls_canoriart=="")||(ls_canart=="")||(ls_preart=="")||(ls_subtotart=="")||(ls_montotart==""))
	{
		alert("Debe Revisar los datos del Producto que intenta procesar");
		lb_valido=true;
	}

	ls_canart=ue_formato_operaciones(ls_canart);
	ls_preart=ue_formato_operaciones(ls_preart);
	li_aux1=(parseFloat(ls_canart) * parseFloat(ls_preart));
	li_aux=(parseFloat(ls_canart) * parseFloat(ls_preart))*(parseFloat(ls_ivaart)/100);
	li_aux=li_aux+li_aux1;
	li_aux=uf_convertir(li_aux);
	//alert(li_aux+" "+ls_montotart);
	if (li_aux!=ls_montotart)
	{
		alert("No concuerdan las cantidades de articulos");
		lb_valido=true;	
	}
	//alert("aqui voy");


	//if(!lb_valido) // Linea Correcta
	if(!lb_valido)
	{
		ue_calculartotal();
		//alert(ls_numpedido);
		f.operacion.value="AGREGARDETALLE";
		f.action="tepuy_sfa_d_pedidos.php";
		f.submit();
	}
}
function uf_delete_dt(li_row)
{
	f=document.form1;
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_penart=eval("f.txtpreart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	ls_preuniart=eval("f.txtporiva"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	ls_montotord=eval("f.txtsubtotart"+li_row+".value");
	ls_montotord=ue_validarvacio(ls_montotord);

	if((ls_codart=="")||(ls_canart=="")||(ls_penart=="")||(ls_preuniart=="")||(ls_canoriart=="")||(ls_montotord==""))
	{
		alert("No deben tener campos vacios");
		lb_valido=true;
	}
	else
	{
		li_fila=f.totalfilas.value;
		if(li_fila!=li_row)
		{
			if(confirm("�Desea eliminar el Registro actual?"))
			{	
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="tepuy_sfa_d_pedidos.php";
				f.submit();
			}
		}
	}
}

function ue_guardar()
{
	f=document.form1;
	lb_valido=true;
	li_totfilas=f.totalfilas.value;
	ls_numpedido=eval("f.txtnumpedido.value");
	ls_numpedido=ue_validarvacio(ls_numpedido);
	ls_cedcli=eval("f.txtcedcli.value");
	ls_cedcli=ue_validarvacio(ls_cedcli);
	ls_nomcli=eval("f.txtnomcli.value");
	ls_nomcli=ue_validarvacio(ls_nomcli);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	if ((ls_numpedido=="")||(ls_cedcli=="")||(ls_nomcli=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales (*)");
		lb_valido=false;
	}
	else
	{
		li_totfilas=li_totfilas+1;
	}

    if(li_totfilas<=1)
	{
		alert("Debe ingresar al menos 1 prodcuto a la Factura");
		lb_valido=false;
	}

	if(lb_valido)
	{
			f.operacion.value="GUARDAR";
			f.action="tepuy_sfa_d_pedidos.php";
			f.submit();
	}
}

function ue_eliminar()
{
	f=document.form1;
	ls_estatus=f.hidestatus.value;
	if(ls_estatus==1)
	{
		if(confirm("�Seguro desea eliminar el Pedido?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="tepuy_sfa_d_pedidos.php";
			f.submit();
		}
	}
	else
	{
		alert("El pedido ya se encuentra Facturado. Contacte al Administrador de Sistema");
	}
}

function ue_anular()
{
	f=document.form1;
	ls_estatus=f.hidestatus.value;
	if(ls_estatus==1)
	{
		if(confirm("�Seguro desea anular el Pedido?"))
		{
			f.operacion.value="ANULAR";
			f.action="tepuy_sfa_d_pedidos.php";
			f.submit();
		}
	}
	else
	{
		alert("El pedido ya se encuentra Facturado. Contacte al Administrador del Sistema");
	}
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

//--------------------------------------------------------
//	Funci�n que calcula cuantos articulos quedaran pendientes
//	de la orden de compra
//--------------------------------------------------------
function ue_calcularpendiente(li_row)
{
	f=document.form1;
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	
	ls_unidad=eval("f.txtunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);

	li_unidad=eval("f.hidunidad"+li_row+".value");
	li_unidad=ue_validarvacio(li_unidad);
	
	ls_penart=eval("f.txtpreart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	
	ls_hidpenart=eval("f.hidpendiente"+li_row+".value");
	ls_hidpenart=ue_validarvacio(ls_hidpenart);


	

	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	
	ls_preuniart=eval("f.txtporiva"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	li_cero="0,00";
	
	ls_canoriart=ue_formato_operaciones(ls_canoriart);
	ls_canart=ue_formato_operaciones(ls_canart);
	ls_preuniart=ue_formato_operaciones(ls_preuniart);

	if((parseFloat(ls_canoriart) < parseFloat(ls_canart)))
	{
		ls_canoriart=uf_convertir(ls_canoriart);
		ls_canart=uf_convertir(ls_canart);
		alert("La cantidad recibida no puede ser mayor que la ordenada");
		obj=eval("f.txtcanart"+li_row+"");
		obj.value=li_cero;
	}
	else
	{
		if((parseFloat(ls_hidpenart) < parseFloat(ls_canart)))
		{
			alert("La cantidad recibida no puede ser mayor que pendiente");
			obj=eval("f.txtcanart"+li_row+"");
			obj.value=li_cero;
		}
		else
		{
			if(ls_canart!="")
			{
				li_pendiente=(parseFloat(ls_hidpenart) - parseFloat(ls_canart));
				li_pendiente=uf_convertir(li_pendiente); 
				if (li_pendiente=="0,00")
				{
				   f.radiotipentrega[0].checked=true;
				   f.radiotipentrega[1].disabled=true;
				}
				obj=eval("f.txtpreart"+li_row+"");
				obj.value=li_pendiente;
				li_totart=(parseFloat(ls_preuniart) * parseFloat(ls_canart));
				li_totart=uf_convertir(li_totart);
			}
		}

		if(ls_hidpenart=="")
		{
			if(ls_canart!="")
			{
				li_pendiente=(parseFloat(ls_canoriart) - parseFloat(ls_canart));
				li_pendiente=uf_convertir(li_pendiente); 
				if (li_pendiente=="0,00")
				{
				   f.radiotipentrega[0].checked=true;
				   f.radiotipentrega[1].disabled=true;
				}
				obj=eval("f.txtpreart"+li_row+"");
				obj.value=li_pendiente;
				li_unidad=eval("f.hidunidad"+li_row+".value");
			}
		}
	}
	if((ls_canart!="")&&(ls_preuniart!=""))
	{
		li_unidad=eval("f.hidunidad"+li_row+".value");
		if(ls_unidad=="Mayor")
		{
			ls_canart=parseFloat(ls_canart) * parseFloat(li_unidad);
		}
		li_montot=parseFloat(ls_canart) * parseFloat(ls_preuniart);
		li_montot=uf_convertir(li_montot);
		obj=eval("f.txtsubtotart"+li_row+"");
		obj.value=li_montot;
	}
	else
	{
		ls_blanco="0,00";
		obj=eval("f.txtsubtotart"+li_row+"");
		obj.value=ls_blanco;
		ls_canoriginal=uf_convertir(ls_canoriart)
		if(ls_canart!="")
		{
			obj=eval("f.txtpreart"+li_row+"");
			obj.value=ls_canoriginal;
		}
	}
	ue_calculartotal();
}

//--------------------------------------------------------
//	Funci�n que llena por defecto campos del grid 
//	cuando la entrada de suministros es por una factura
//--------------------------------------------------------
function ue_articulospedido(li_row)
{
	f=document.form1;
	/*if(f.radiotipo[0].checked==true)
	{
		li_preuniart=eval("f.txtporiva"+li_row+".value");
		li_preuniart=ue_validarvacio(li_preuniart);
		li_canoriart=eval("f.txtcanoriart"+li_row+".value");
		li_canoriart=ue_validarvacio(li_canoriart);
		li_canart=eval("f.txtcanart"+li_row+".value");
		li_canart=ue_validarvacio(li_canart);
		obj=eval("f.txtcanart"+li_row+"");
		obj.value=li_canoriart;
		obj=eval("f.txtpreart"+li_row+"");
		obj.value="0,00";
		if((li_canart!="")&&(ls_preuniart!=""))
		{
			li_unidad=eval("f.hidunidad"+li_row+".value");
			li_unidad= ue_formato_operaciones(li_unidad);
			li_canart= ue_formato_operaciones(li_canart);
			if(ls_unidad=="M")
			{
				li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
			}
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			eval("f.txtsubtotart"+li_row+".value='"+li_montot+"'");
		}
	}
	else
	{*/
		//li_preuniart=eval("f.txtporiva"+li_row+".value");
		//li_preuniart=ue_validarvacio(li_preuniart);
		
		li_poriva=eval("f.txtporiva"+li_row+".value");
		li_poriva=ue_validarvacio(li_poriva);
		li_canoriart=eval("f.txtcanoriart"+li_row+".value");
		li_canoriart=ue_validarvacio(li_canoriart);
		li_canart=eval("f.txtcanart"+li_row+".value");
		li_canart=ue_validarvacio(li_canart);
		li_preart=eval("f.txtpreart"+li_row+".value");
		li_preart=ue_validarvacio(li_preart);


		li_canoriartaux=parseFloat(uf_convertir_monto(li_canoriart));
		li_canartaux=parseFloat(uf_convertir_monto(li_canart));
		//li_canoriart=ue_formato_calculo(li_canoriart);
		li_poriva=uf_convertir_monto(li_poriva);
		li_poriva=parseFloat(li_poriva)/100;
		//alert("original: "+li_canoriart+" y a facturar: "+li_canart);
		//alert("IVA: "+li_poriva);
		if(li_canoriartaux>=li_canartaux)
		{
			//obj=eval("f.txtcanart"+li_row+"");
			//obj.value=li_canoriart;
			//obj=eval("f.txtpreart"+li_row+"");
			//obj.value="0,00";
			li_canart=ue_formato_calculo(li_canart);
			li_preart=ue_formato_calculo(li_preart);
			li_subtot=parseFloat(li_canart) * parseFloat(li_preart);
			obj=eval("f.txtsubtotart"+li_row+"");
			obj.value=uf_convertir(li_subtot);
			//alert(li_subtot+" "+li_preart);
			li_subiva=parseFloat(li_subtot) * parseFloat(li_poriva);
			obj=eval("f.txtsubivaart"+li_row+"");
			obj.value=uf_convertir(li_subiva);
			li_totart=li_subtot+li_subiva;//(parseFloat(li_canart)*parseFloat(li_preart))*parseFloat(li_poriva);
			obj=eval("f.txttotart"+li_row+"");
			obj.value=uf_convertir(li_totart);
		// Establecer el Total General //
			

		}
		else
		{
			alert("La Cantidad a facturar es mayor que la existente");
			obj=eval("f.txtcanart"+li_row+"");
			obj.value="0,00";
		}
		
		if((li_canoriart!="")&&(ls_preuniart!=""))
		{
			//li_unidad=eval("f.hidunidad"+li_row+".value");
			//li_unidad= ue_formato_operaciones(li_unidad);
			li_canoriart= ue_formato_operaciones(li_canoriart);
			//if(ls_unidad=="M")
			//{
				li_canoriart=parseFloat(li_canoriart) * parseFloat(li_unidad);
			//}
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canoriart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);

			eval("f.txtsubtotart"+li_row+".value='"+li_montot+"'");
		}
	//}
}
//--------------------------------------------------------
//	Funci�n que calcula el monto total por articulo 
//	cuando la entrada de suministros es por una factura
//--------------------------------------------------------
function ue_montospedido(li_row)
{
	f=document.form1;
	ls_unidad=eval("f.txtunimed"+li_row+".value");
	li_unidad=eval("f.hidunidad"+li_row+".value");
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_preuniart=eval("f.txtporiva"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	if((ls_canart!="")&&(ls_preuniart!=""))
	{
		ls_preuniart=ue_formato_operaciones(ls_preuniart);
		ls_canart=   ue_formato_operaciones(ls_canart);
		li_unidad=   ue_formato_operaciones(li_unidad);
		if(ls_unidad=="M")
		{
			ls_canart=parseFloat(ls_canart) * parseFloat(li_unidad);
		}
		li_montot=parseFloat(ls_canart) * parseFloat(ls_preuniart);
		li_montot=uf_convertir(li_montot);
		obj=eval("f.txtsubtotart"+li_row+"");
		obj.value=li_montot;
	}
}

function ue_completa()
{
	f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		li_canoriart= eval("f.txtcanoriart"+li_i+".value");
		li_penart= eval("f.txtpreart"+li_i+".value");
		li_preuniart= eval("f.txtporiva"+li_i+".value");
		if(li_penart=="0,00")
		{
			obj=eval("f.txtcanart"+li_i+"");
			obj.value=li_canoriart;
			obj=eval("f.txtpreart"+li_i+"");
			obj.value="0,00";
			li_canoriart=   ue_formato_operaciones(li_canoriart);
			li_preuniart=   ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canoriart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtsubtotart"+li_i+"");
			obj.value=li_montot;
		}
		else
		{
			obj=eval("f.txtcanart"+li_i+"");
			obj.value=li_penart;
			obj=eval("f.txtpreart"+li_i+"");
			obj.value="0,00";
			li_penart=    ue_formato_operaciones(li_penart);
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_penart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtsubtotart"+li_i+"");
			obj.value=li_montot;
		}
	}	
	ue_calculartotal();
}

function ue_parcial()
{
    f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		ls_hidpenart=eval("f.hidpendiente"+li_i+".value");
		ls_hidpenart=ue_validarvacio(ls_hidpenart);
		if(ls_hidpenart!="")
		{
			li_pendiente=uf_convertir(ls_hidpenart);
			obj=eval("f.txtpreart"+li_i+"");
			obj.value=li_pendiente;
		}
		else
		{
			obj=eval("f.txtpreart"+li_i+"");
			obj.value="0,00";
		}
		obj=eval("f.txtcanart"+li_i+"");
		obj.value="";
		obj=eval("f.txtsubtotart"+li_i+"");
		obj.value="";
	}
	f.txttotped.value="0,00";
}

//--------------------------------------------------------------
//	Funci�n que calcula el total de la recepcion de suministros
//--------------------------------------------------------------
function ue_calculartotal()
{
	f=document.form1;
	li_totalrow=f.totalfilas.value;
	li_total=0;
	li_subtotalacum=0;
	li_subtotivaacum=0;
	li_totalacum=0;
	for(li_i=1;li_i<=li_totalrow;li_i++)
	{
		li_subtotal=eval("f.txtsubtotart"+li_i+".value");
		li_subtotiva=eval("f.txtsubivaart"+li_i+".value");
		li_total=eval("f.txttotart"+li_i+".value");
		//alert("SubTotal: "+li_subtotal+" IVA "+li_subtotiva+" Total: "+li_total);
		if(li_subtotal!="")
		{
			li_subtotal= ue_formato_operaciones(li_subtotal);
			li_subtotalacum=li_subtotalacum + parseFloat(li_subtotal);
			////////////////////////////////////////////////////
			li_subtotiva= ue_formato_operaciones(li_subtotiva);
			li_subtotivaacum=li_subtotivaacum + parseFloat(li_subtotiva);
			////////////////////////////////////////////////////
			li_total= ue_formato_operaciones(li_total);
			li_totalacum=li_totalacum + parseFloat(li_total);

		}
	}
	//alert("SubTotal: "+li_subtotalacum+" IVA "+li_subtotivaacum+" Total: "+li_totalacum);
	li_subtotal=uf_convertir(li_subtotalacum);
	f.txtsubtotped.value=li_subtotal;
	li_subtotiva=uf_convertir(li_subtotivaacum);
	f.txttotivaped.value=li_subtotiva;
	li_total=uf_convertir(li_totalacum);
	f.txttotped.value=li_total;
}


//--------------------------------------------------------
//	Funci�n que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
//--------------------------------------------------------
//	Funci�n que valida que solo se incluyan n�meros en los textos
//--------------------------------------------------------
function ue_validarnumerosinpunto(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
