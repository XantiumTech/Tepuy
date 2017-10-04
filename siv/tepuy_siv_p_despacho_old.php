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
require_once("class_funciones_inventario.php");
$io_fun_inventario=new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIV","tepuy_siv_p_despacho.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_inventario->uf_select_config("SIV","REPORTE","NOTA_DESPACHO","tepuy_siv_rfs_despachos.php","C");
//$ls_numorddes=$io_fun_inventario->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
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
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
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
		//    Description: Funcion que agrega una linea en blanco al final del grid del detalle de despacho
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*		$aa_object[$ai_totrows][1]="<input  name=txtdenart".$ai_totrows."     type=text   id=txtdenart".$ai_totrows." class=sin-borde size=25 maxlength=50 readonly <a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>".
								   "<input  name=txtcodart".$ai_totrows."     type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=50 readonly>".
								   "<input  name=txtctagas".$ai_totrows."     type=hidden id=txtctagas".$ai_totrows." class=sin-borde size=20 maxlength=50 readonly>".
								   "<input  name=txtctasep".$ai_totrows."     type=hidden id=txtctasep".$ai_totrows." class=sin-borde size=20 maxlength=20 readonly>"; */
		$aa_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=20 maxlength=50 readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<input  name=txtcodalm".$ai_totrows."     type=text   id=txtcodalm".$ai_totrows." class=sin-borde size=13 maxlength=10 readonly>".
								   "<a href='javascript: ue_catalmacen(".$ai_totrows.");'><img src='../shared/imagebank/tools20/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][3]="<select name=cmbunidad".$ai_totrows."     style='width:60px '><option value=D>Detal</option><option value=M>Mayor</option></select>";
		$aa_object[$ai_totrows][4]="<input  name=txtcansol".$ai_totrows."     type=text   id=txtcansol".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
		//$aa_object[$ai_totrows][5]="<input  name=txtpenart".$ai_totrows."     type=text   id=txtpenart".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
		$aa_object[$ai_totrows][5]="<input  name=txtcanart".$ai_totrows."     type=text   id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'  onBlur='javascript: ue_montosfactura(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][6]="<input  name=txtpreuniart".$ai_totrows."  type=text   id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>".
								   "<input  name=hidnumdocori".$ai_totrows."  type=hidden id=hidnumdocori".$ai_totrows.">";
		$aa_object[$ai_totrows][7]="<input  name=txtmontotart".$ai_totrows."  type=text   id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_dt_activo(".$ai_totrows.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a>";			
   }
   //--------------------------------------------------------------

   function uf_agregarlineablancacontable(&$aa_objectc,$ai_totrowsc)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancacontable
		//         Access: private
		//      Argumento: $aa_objectc  // arreglo de titulos 		
		//                 $ai_totrowsc // ultima fila pintada en el grid		
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid del detalle contable
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_objectc[$ai_totrowsc][1]="<input  name=txtdenartc".$ai_totrowsc."  type=text   id=txtdenartc".$ai_totrowsc."  class=sin-borde size=15 maxlength=50 readonly>".
								     "<input  name=txtcodartc".$ai_totrowsc."  type=hidden id=txtcodartc".$ai_totrowsc."  class=sin-borde size=20 maxlength=50 readonly>".
		$aa_objectc[$ai_totrowsc][2]="<input  name=txtsccuenta".$ai_totrowsc." type=text   id=txtsccuenta".$ai_totrowsc." class=sin-borde size=15              readonly>";
		$aa_objectc[$ai_totrowsc][3]="<input  name=txtdebhab".$ai_totrowsc."   type=text   id=txtdebhab".$ai_totrowsc."   class=sin-borde size=5               readonly>";
		$aa_objectc[$ai_totrowsc][4]="<input  name=txtmonto".$ai_totrowsc."    type=text   id=txtcansolc".$ai_totrowsc."  class=sin-borde size=12              readonly>";
   }
   //--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//         Access: private
		//      Argumento:  	
		//	      Returns:
		//    Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_numorddes,$ls_numsol,$ls_coduniadm,$ls_denuniadm,$ls_obsdes,$ld_fecdes;
		global $ls_codusu,$ls_readonly,$ls_codunides,$ls_denunides,$ls_coduniadm,$ls_denuniadms,$ls_checkedparc,$ls_checkedcomp;
		global $ls_nomproy,$ls_coduniadm,$ls_denuniadm,$ls_codmun,$ls_codpar,$ls_despar,$ls_nomdirec;
		global $ls_desmun,$ls_codpai,$ls_despai,$ls_codest,$ls_desest,$ls_direccion,$ls_rif,$ls_codproy;
		
		$ls_numorddes="";
		$ls_numsol="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_obsdes="";
		$ld_fecdes=date("d/m/Y");
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_codunides="";
		$ls_denunides="";
		$ls_checkedparc="";
		$ls_checkedcomp="";
		$ls_nomproy="";
		$ls_codproy="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_codmun="";
		$ls_desmun="";
		$ls_codpar="";
		$ls_despar="";
		$ls_nomdirec="";
		$ls_codpai="";
		$ls_despai="";
		$ls_codest="";
		$ls_desest="";
		$ls_direccion="";
		$ls_rif="";
   }

   function uf_titulosdespacho()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_titulosdespacho
		//         Access: private
		//      Argumento:  	
		//	      Returns:
		//    Description: Función que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title;
		
		$ls_titletable="Detalle del Despacho";
		$li_widthtable=800;
		$ls_nametable="grid";
		$lo_title[1]="Artículo";
		$lo_title[2]="Almacén";
		$lo_title[3]="Unidad";
		$lo_title[4]="Cant. Existente";
		//$lo_title[5]="Cant. Pendiente";
		$lo_title[5]="Cant. a Despachar";
		$lo_title[6]="Precio Unitario";
		$lo_title[7]="Total";
		$lo_title[8]="";
   }

   function uf_tituloscontable()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_tituloscontable
		//         Access: private
		//      Argumento:  	
		//	      Returns:
		//    Description: Función que carga las caracteristicas del grid de detalle contable
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titlecontable,$li_widthcontable,$ls_namecontable,$lo_titlecontable;
		
		$ls_titlecontable="Detalle Contable";
		$li_widthcontable=800;
		$ls_namecontable="grid";
		$lo_titlecontable[1]="Artículo";
		$lo_titlecontable[2]="Cuenta";
		$lo_titlecontable[3]="Debe/Haber";
		$lo_titlecontable[4]="Monto";
   }
   
   function uf_obtenervalorunidad($li_i)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalorunidad
		//         Access: private
		//      Argumento: $li_i  //  indica que opcion esta seleccionado en el combo	
		//	      Returns: Retorna el valor obtenido
		//    Description: Función que obtiene el contenido del combo cmbunidad o del campo txtunidad deacuerdo sea el caso 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
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
   
	function uf_incluircontable($as_codemp,$as_numorddes,$ad_fecdes,&$aa_objectc,$ai_totrowsc,$aa_seguridad,$io_fun_inventario,$io_siv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancacontable
		//         Access: private
		//      Argumento: $as_codemp         // codigo de empresa
		//                 $as_numorddes      // numero de orden de despacho
		//                 $ad_fecdes         // fecha del despacho	
		//                 $aa_objectc        // arreglo de titulos 		
		//                 $ai_totrowsc       // ultima fila pintada en el grid		
		//                 $aa_seguridad      // arreglo de seguridad
		//                 $io_fun_inventario // instancia de la clase de funciones de inventario	
		//                 $io_siv            // instancia de la clase tepuy_siv_c_despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que pinta nuevamente el grid de detalle contable con los datos que estaban en el ademas de 
		//                 activar el proceso de insert del mismo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_error=false;
		$lb_valido=true;
		for($li_j=1;$li_j<=$ai_totrowsc;$li_j++)
		{
			$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodartc".$li_j,"");
			$ls_denart=    $io_fun_inventario->uf_obtenervalor("txtdenartc".$li_j,"");
			$ls_sccuenta=  $io_fun_inventario->uf_obtenervalor("txtsccuenta".$li_j,"");
			$ls_debhab=    $io_fun_inventario->uf_obtenervalor("txtdebhab".$li_j,"");
			$li_montoc=    $io_fun_inventario->uf_obtenervalor("txtmonto".$li_j,"");
			$li_montoc=    str_replace(".","",$li_montoc);
			$li_montoc=    str_replace(",",".",$li_montoc);
			$li_montotot= $li_montoc;
			$lb_incluir=true;
/*			for($li_z=1;$li_z<=$ai_totrowsc;$li_z++)
			{
				$ls_sccuentaaux=  $io_fun_inventario->uf_obtenervalor("txtsccuenta".$li_z,"");
				$li_montoaux=     $io_fun_inventario->uf_obtenervalor("txtmonto".$li_z,"");
				$li_montoaux=     str_replace(".","",$li_montoaux);
				$li_montoaux=     str_replace(",",".",$li_montoaux);
				if(($ls_sccuentaaux==$ls_sccuenta)&&($li_z > $li_j))
				{$li_montotot=$li_montotot + $li_montoaux;}
				if(($ls_sccuentaaux==$ls_sccuenta)&&($li_z < $li_j))
				{$lb_incluir=false;}
				
			}*/
			if($lb_incluir)
			{
				$lb_valido=$io_siv->uf_siv_insert_dt_scg($as_codemp,$ls_codart,$as_numorddes,$ad_fecdes,$ls_sccuenta,$ls_debhab,
														 $li_montotot,$aa_seguridad);
			}
			if(!$lb_valido)
			{$lb_error=true;}
			
			$aa_objectc[$li_j][1]="<input  name=txtdenartc".$li_j."  type=text   id=txtdenartc".$li_j."  class=sin-borde size=50  value='".$ls_denart."'   readonly>".
								  "<input  name=txtcodartc".$li_j."  type=hidden id=txtcodartc".$li_j."  class=sin-borde size=30  value='".$ls_codart."'   readonly>";
			$aa_objectc[$li_j][2]="<input  name=txtsccuenta".$li_j." type=text   id=txtsccuenta".$li_j." class=sin-borde size=30  value='".$ls_sccuenta."' readonly>";
			$aa_objectc[$li_j][3]="<input  name=txtdebhab".$li_j."   type=text   id=txtdebhab".$li_j."   class=sin-borde size=15  value='".$ls_debhab."'   readonly style='text-align:center'>";
			$aa_objectc[$li_j][4]="<input  name=txtmonto".$li_j."    type=text   id=txtcansolc".$li_j."  class=sin-borde size=30  value='".number_format ($li_montoc,2,",",".")."' style='text-align:right' readonly>";
		}
		if($lb_error)
		{$lb_valido=false;}
		return $lb_valido;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Despacho de Suministros </title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
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
</script>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Inventario </td>
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
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.png" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=      new tepuy_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=  new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("tepuy_siv_c_despacho.php");
	$io_siv=  new tepuy_siv_c_despacho();
	require_once("tepuy_siv_c_articuloxalmacen.php");
	$io_art=  new tepuy_siv_c_articuloxalmacen();
    require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
	$io_keygen= new tepuy_c_generar_consecutivo();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_rifemp=$arre["rifemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = $io_fun_inventario->uf_obtenervalor("totalfilas",1);
	$li_totrowsc= $io_fun_inventario->uf_obtenervalor("totalfilasc",1);

	uf_titulosdespacho();
	uf_tituloscontable();
	$ls_operacion= $io_fun_inventario->uf_obteneroperacion();
	$ls_status=    $io_fun_inventario->uf_obtenervalor("hidestatus","");
	if ($ls_status=="C")
	{
		$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
		$li_catafilas= $io_fun_inventario->uf_obtenervalor("catafilas","");
	}
	
	$lb_cont=$io_siv->uf_siv_load_contabilizacion($ls_codemp,$li_value);
	if($li_value==0)
	{
		$ls_ok=true;
	}
	switch ($ls_operacion) 
	{

		case "NUEVO":
			uf_limpiarvariables();
		    $ls_numorddes=$io_keygen->uf_generar_numero_nuevo("SIV","siv_despacho","numorddes","SIV",15,"","codemp",$ls_codemp);
			if($ls_numorddes==false)
			{
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";
			}
			uf_agregarlineablanca($lo_object,1);
			uf_agregarlineablancacontable($lo_objectc,1);
		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$lb_descomp=true;
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm",""); 
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm",""); 
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes",""); 
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ls_nomproy= $io_fun_inventario->uf_obtenervalor("txtnomproy","");
  		    $ls_codproy= $io_fun_inventario->uf_obtenervalor("txtcodproy","");
			$ls_codmun=$io_fun_inventario->uf_obtenervalor("txtcodmun","");
			$ls_desmun=$io_fun_inventario->uf_obtenervalor("txtdesmun","");
			$ls_codpar=$io_fun_inventario->uf_obtenervalor("txtcodpar","");
			$ls_despar=$io_fun_inventario->uf_obtenervalor("txtdespar","");
			$ls_nomdirec=$io_fun_inventario->uf_obtenervalor("txtnomdirec","");
			$ls_codpai=$io_fun_inventario->uf_obtenervalor("txtcodpai","");
			$ls_despai=$io_fun_inventario->uf_obtenervalor("txtdespai","");
			$ls_codest=$io_fun_inventario->uf_obtenervalor("txtcodest","");
			$ls_desest=$io_fun_inventario->uf_obtenervalor("txtdesest","");
			$ls_direccion=$io_fun_inventario->uf_obtenervalor("txtdireccion","");
			$ld_fecdesaux= $io_func->uf_convertirdatetobd($ld_fecdes);
			$ls_estrevdes= "1";
			$ls_estdes=    "1";
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("rdtipodespacho","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
			}
			$lb_valido=$io_fec->uf_valida_fecha_periodo($ld_fecdes,$ls_codemp);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_siv->uf_siv_insert_despacho($ls_codemp,$ls_numorddes,$ls_numsol,$ls_coduniadm,$ld_fecdesaux,$ls_obsdes,
														   $ls_logusr,$ls_estdes,$ls_estrevdes,$ls_codunides,$ls_nomproy,$ls_codproy,
														   $ls_codmun,$ls_codpar,$ls_codpai,$ls_codest,
														   $ls_nomdirec,$ls_direccion,$la_seguridad);
				if($lb_valido)
				{
	
					$ls_nummov=0;
					$ls_nomsol="Despacho";
					$lb_valido=$io_siv->io_mov->uf_siv_insert_movimiento($ls_nummov,$ld_fecdesaux,$ls_nomsol,$ls_logusr,
																		  $la_seguridad);
					if($lb_valido)
					{
						$lb_exito=true;
						for($li_i=1;$li_i<=$li_totrows;$li_i++)
						{
							$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
							$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
							$ls_codalm=       $io_fun_inventario->uf_obtenervalor("txtcodalm".$li_i,"");
							$li_canorisolsep= $io_fun_inventario->uf_obtenervalor("txtcansol".$li_i,"");
							$li_existencia=   $io_fun_inventario->uf_obtenervalor("hidexistencia".$li_i,"");
							$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
							$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
							$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
							$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
							$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
							$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
							$ls_ctagas=       $io_fun_inventario->uf_obtenervalor("txtctagas".$li_i,"");
							$ls_ctasep=       $io_fun_inventario->uf_obtenervalor("txtctasep".$li_i,"");
							$li_canpenart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
							$li_canorisolsep= str_replace(".","",$li_canorisolsep);
							$li_canorisolsep= str_replace(",",".",$li_canorisolsep);
							$li_canart=       str_replace(".","",$li_canart);
							$li_canart=       str_replace(",",".",$li_canart);
							$li_preuniart=    str_replace(".","",$li_preuniart);
							$li_preuniart=    str_replace(",",".",$li_preuniart);
							$li_montotart=    str_replace(".","",$li_montotart);
							$li_montotart=    str_replace(",",".",$li_montotart);
							$li_canpenart=    str_replace(".","",$li_canpenart);
							$li_canpenart=    str_replace(",",".",$li_canpenart);
							$li_auxcanpenart=$li_canpenart;
							$li_canartaux=$li_canart;
							if($ls_unidad=="")
							{
								$ls_unidad= $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
								$ls_hidunidad= $io_fun_inventario->uf_obtenervalor("hidtxtuni".$li_i,"");
							}
							if($ls_unidad=="Mayor")
							{
								$ls_unidad="M";
								$li_canartaux=($li_canart*$li_unidad);
							}
							else
							{$ls_unidad="D";}
							if($ls_hidunidad=="Mayor")
							{
								$li_auxcanpenart=($li_canpenart*$li_unidad);
							}
							switch ($ls_unidad) 
							{
								case "M":
									$ls_unidadaux="Mayor";
								break;
								case "D":
									$ls_unidadaux="Detal";
								break;
							}
							$lo_object[$li_i][1]="<input name=txtdenart".$li_i."     type=text   id=txtdenart".$li_i."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input name=txtcodart".$li_i."     type=hidden id=txtcodart".$li_i."    class=sin-borde size=15 maxlength=25   value='".$ls_codart."' readonly>".
												 "<input name=txtctagas".$li_i."     type=hidden id=txtctagas".$li_i."    class=sin-borde size=20 maxlength=50   value='".$ls_ctagas."' readonly>".
												 "<input name=txtctasep".$li_i."     type=hidden id=txtctasep".$li_i."    class=sin-borde size=20 maxlength=20 value='".$ls_ctasep."' readonly>";
							$lo_object[$li_i][2]="<input name=txtcodalm".$li_i."     type=text   id=txtcodalm".$li_i."    class=sin-borde size=13 maxlength=10 value='". $ls_codalm."' readonly>".
												 "<a href='javascript: ue_catalmacen(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
							$lo_object[$li_i][3]="<input name=txtunidad".$li_i."     type=text   id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='". $ls_unidadaux."' readonly></div>".
												 "<input name='hidunidad".$li_i."'    type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
							$lo_object[$li_i][4]="<input name=txtcansol".$li_i."     type=text   id=txtcansol".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canorisolsep,2,",",".")."' readonly>".
												 "<input name=hidexistencia".$li_i." type=hidden id=hidexistencia".$li_i."                                     value='". $li_existencia."'>";
							$lo_object[$li_i][5]="<input name=txtpenart".$li_i."     type=text   id=txtpenart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canpenart,2,",",".")."' readonly>";
							$lo_object[$li_i][6]="<input name=txtcanart".$li_i."     type=text   id=txtcanart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canart,2,",",".")."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montosfactura(".$li_i.");'>";
							$lo_object[$li_i][7]="<input name=txtpreuniart".$li_i."  type=text   id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>".
												 "<input name=hidnumdocori".$li_i."  type=hidden id=hidnumdocori".$li_i.">";
							$lo_object[$li_i][8]="<input name=txtmontotart".$li_i."  type=text   id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' readonly>";
							$lo_object[$li_i][9]="<a href=javascript:uf_dt_activo(".$li_i.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a>";			
							if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							{
								$lb_valido=$io_siv->uf_siv_procesar_dt_despacho($ls_codemp,$ls_numorddes,$ls_codart,$ls_codalm,$ls_unidad,
																				$li_canorisolsep,$li_canartaux,$li_preuniart,$li_montotart, //monsubart
																				$li_montotart,$li_i,$ls_nummov,$ld_fecdesaux,
																				$ls_numsol,$li_auxcanpenart,$la_seguridad);
								if($lb_valido)
								{
									$lb_valido=$io_art->uf_siv_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$li_canartaux,
																						  $la_seguridad);
									if($lb_valido)
									{
										$lb_valido=$io_art->uf_siv_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
									} // fin  if($lb_valido)->uf_siv_disminuir_articuloxalmacen
								} //fin  if($lb_valido)->uf_siv_insert_dt_despacho
								if($li_canartaux<$li_auxcanpenart)
								{
									$lb_descomp=false;
								}
							}//  fin if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							else
							{
								$lb_descomp=false;
							}
							if(!$lb_valido)
							{$lb_exito=false;}
						}  // fin  for($li_i=1;$li_i<$li_totrows;$li_i++)
						if($li_value==1)
						{
							$lb_valido=uf_incluircontable($ls_codemp,$ls_numorddes,$ld_fecdesaux,$lo_objectc,
														  $li_totrowsc,$la_seguridad,$io_fun_inventario,$io_siv);
						}
					}  //fin  if($lb_valido) uf_siv_insert_movimiento
				}  //fin  if($lb_valido)
				if($lb_descomp)
				{
					$ls_estsep="D";
					$lb_valido=$io_siv->uf_siv_update_sep($ls_codemp,$ls_numsol,$ls_estsep);
				}
				else
				{
					$ls_estsep="L";
					$lb_valido=$io_siv->uf_siv_update_sep($ls_codemp,$ls_numsol,$ls_estsep); 
				}
				if(!$lb_exito)
				{$lb_valido=false;}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El despacho ha sido procesado");
					$ls_status="C";
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar el despacho");
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				$li_totrowsc=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_agregarlineablancacontable($lo_objectc,1);
				uf_limpiarvariables();
			}
		break;
		case "BUSCARDETALLESOLICITUD":
			//$ls_numorddes=  "";
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$ls_nomproy= $io_fun_inventario->uf_obtenervalor("txtnomproy","");
  		    $ls_codproy= $io_fun_inventario->uf_obtenervalor("txtcodproy","");
			$ls_codmun=$io_fun_inventario->uf_obtenervalor("txtcodmun","");
			$ls_desmun=$io_fun_inventario->uf_obtenervalor("txtdesmun","");
			$ls_codpar=$io_fun_inventario->uf_obtenervalor("txtcodpar","");
			$ls_despar=$io_fun_inventario->uf_obtenervalor("txtdespar","");
			$ls_nomdirec=$io_fun_inventario->uf_obtenervalor("txtnomdirec","");
			$ls_codpai=$io_fun_inventario->uf_obtenervalor("txtcodpai","");
			$ls_despai=$io_fun_inventario->uf_obtenervalor("txtdespai","");
			$ls_codest=$io_fun_inventario->uf_obtenervalor("txtcodest","");
			$ls_desest=$io_fun_inventario->uf_obtenervalor("txtdesest","");
			$ls_direccion=$io_fun_inventario->uf_obtenervalor("txtdireccion","");

			$data="";
			$li_totrows=0;
			$li_totrowsc=1;
			$ls_pendiente="";
 		    $ls_checkedcomp="";
			$ls_checkedparc="";
			$ls_readonlyrad="";
			$ld_fecdes1=$io_func->uf_convertirdatetobd($ld_fecdes);
			uf_agregarlineablancacontable($lo_objectc,1);
			if($ls_estsol=="L")
			{
				$lb_valido=$io_siv->uf_siv_obtener_dt_pendiente($ls_codemp,$ls_numsol,$li_totrows,$lo_object);
			}
			else
			{
				$lb_valido=$io_siv->uf_siv_obtener_dt_solicitud($ls_codemp,$ls_numsol,$li_totrows,$lo_object);
			}
			if (!$lb_valido)
			{
				uf_agregarlineablanca($lo_object,1);
				uf_agregarlineablancacontable($lo_objectc,1);
				uf_limpiarvariables();
				$io_msg->message("Debe definir una cuenta contable de gasto para los articulos de la solicitud");
				$li_totrows=1;
/*				$ls_checkedcomp="";
				$ls_checkedparc="";
				$ls_readonlyrad="";
*/			}
		break;
		case "BUSCARDETALLE":
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ls_checkedcomp="";
			$ls_checkedparc="";
			$ls_nomproy= $io_fun_inventario->uf_obtenervalor("txtnomproy","");
  		    $ls_codproy= $io_fun_inventario->uf_obtenervalor("txtcodproy","");
    		$ls_codmun=$io_fun_inventario->uf_obtenervalor("txtcodmun","");
			$ls_desmun=$io_fun_inventario->uf_obtenervalor("txtdesmun","");
			$ls_codpar=$io_fun_inventario->uf_obtenervalor("txtcodpar","");
			$ls_despar=$io_fun_inventario->uf_obtenervalor("txtdespar","");
			$ls_nomdirec=$io_fun_inventario->uf_obtenervalor("txtnomdirec","");
			$ls_codpai=$io_fun_inventario->uf_obtenervalor("txtcodpai","");
			$ls_despai=$io_fun_inventario->uf_obtenervalor("txtdespai","");
			$ls_codest=$io_fun_inventario->uf_obtenervalor("txtcodest","");
			$ls_desest=$io_fun_inventario->uf_obtenervalor("txtdesest","");
			$ls_direccion=$io_fun_inventario->uf_obtenervalor("txtdireccion","");
			
			$lb_valido=$io_siv->uf_siv_obtener_dt_despacho($ls_codemp,$ls_numorddes,$li_totrows,$lo_object);
			if($lb_valido)
			{  
				$lb_valido=$io_siv->uf_siv_obtener_dt_scg($ls_codemp,$ls_numorddes,$li_totrowsc,$lo_objectc);
			}
		break;
		case "CALCULARCONTABLE":
			uf_limpiarvariables();
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm",""); 
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm",""); 
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes",""); 
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$li_totrowsc=0;
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("rdtipodespacho","");
			$ls_nomproy= $io_fun_inventario->uf_obtenervalor("txtnomproy","");
  		    $ls_codproy= $io_fun_inventario->uf_obtenervalor("txtcodproy","");
			$ls_codmun=$io_fun_inventario->uf_obtenervalor("txtcodmun","");
			$ls_desmun=$io_fun_inventario->uf_obtenervalor("txtdesmun","");
			$ls_codpar=$io_fun_inventario->uf_obtenervalor("txtcodpar","");
			$ls_despar=$io_fun_inventario->uf_obtenervalor("txtdespar","");
			$ls_nomdirec=$io_fun_inventario->uf_obtenervalor("txtnomdirec","");
			$ls_codpai=$io_fun_inventario->uf_obtenervalor("txtcodpai","");
			$ls_despai=$io_fun_inventario->uf_obtenervalor("txtdespai","");
			$ls_codest=$io_fun_inventario->uf_obtenervalor("txtcodest","");
			$ls_desest=$io_fun_inventario->uf_obtenervalor("txtdesest","");
			$ls_direccion=$io_fun_inventario->uf_obtenervalor("txtdireccion","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
			}
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
				$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
				$ls_codalm=       $io_fun_inventario->uf_obtenervalor("txtcodalm".$li_i,"");
				$li_canorisolsep= $io_fun_inventario->uf_obtenervalor("txtcansol".$li_i,"");
				$li_existencia=   $io_fun_inventario->uf_obtenervalor("hidexistencia".$li_i,"");
				$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
				$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
				$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
				$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
				$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
				$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
				$ls_ctagas=       $io_fun_inventario->uf_obtenervalor("txtctagas".$li_i,"");
				$ls_ctasep=       $io_fun_inventario->uf_obtenervalor("txtctasep".$li_i,"");
				$li_canpenart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
				$li_hidpenart=    $io_fun_inventario->uf_obtenervalor("txthidpenart".$li_i,"");
				$li_canorisolsep= str_replace(".","",$li_canorisolsep);
				$li_canorisolsep= str_replace(",",".",$li_canorisolsep);
				$li_canart=       str_replace(".","",$li_canart);
				$li_canart=       str_replace(",",".",$li_canart);
				$li_preuniart=    str_replace(".","",$li_preuniart);
				$li_preuniart=    str_replace(",",".",$li_preuniart);
				$li_montotart=    str_replace(".","",$li_montotart);
				$li_montotart=    str_replace(",",".",$li_montotart);
				$li_canpenart=    str_replace(".","",$li_canpenart);
				$li_canpenart=    str_replace(",",".",$li_canpenart);
				if($ls_ctagas=="")
				{
					$li_totrowsc=1;				
					$li_totrows=1;				
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancacontable($lo_objectc,1);
					uf_limpiarvariables();
					uf_agregarlineablancacontable($lo_objectc,1);
					$lb_ok=false;
					$io_msg->message("Verifique que todos los articulos de la solicitud tengan cuenta contable de gasto asociada");
					break;
				}
				if($ls_unidad=="")
				{
					$ls_unidad= $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
					$ls_hidunidad= $io_fun_inventario->uf_obtenervalor("hidtxtuni".$li_i,"");
				}
				if($ls_unidad=="Mayor")
				{$ls_unidad="M";}
				else
				{$ls_unidad="D";}
				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$lo_object[$li_i][1]="<input name=txtdenart".$li_i."     type=text   id=txtdenart".$li_i."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
									 "<input name=txtcodart".$li_i."     type=hidden id=txtcodart".$li_i."    class=sin-borde size=15 maxlength=25 value='".$ls_codart."' readonly>".
								     "<input name=txtctagas".$li_i."     type=hidden id=txtctagas".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_ctagas."' readonly>".
								     "<input name=txtctasep".$li_i."     type=hidden id=txtctasep".$li_i."    class=sin-borde size=20 maxlength=20 value='".$ls_ctasep."' readonly>";
				$lo_object[$li_i][2]="<input name=txtcodalm".$li_i."     type=text   id=txtcodalm".$li_i."    class=sin-borde size=13 maxlength=10 value='". $ls_codalm."' readonly>".
								     "<a href='javascript: ue_catalmacen(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
				$lo_object[$li_i][3]="<input name=txtunidad".$li_i."     type=text   id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='". $ls_unidadaux."' style='text-align:center' readonly></div>".
									 "<input name=hidtxtuni".$li_i."     type=hidden id=hidtxtuni".$li_i."                                         value='". $ls_hidunidad ."'>".
									 "<input name=hidunidad".$li_i."     type=hidden id=hidunidad".$li_i."                                         value='". $li_unidad ."'>";
				$lo_object[$li_i][4]="<input name=txtcansol".$li_i."     type=text   id=txtcansol".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canorisolsep,2,",",".")."' style='text-align:right' readonly>".
									 "<input name=hidexistencia".$li_i." type=hidden id=hidexistencia".$li_i."                                     value='". $li_existencia."'>";
				$lo_object[$li_i][5]="<input name=txtpenart".$li_i."     type=text   id=txtpenart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canpenart,2,",",".")."'  style='text-align:right' readonly>".
 								     "<input name=txthidpenart".$li_i."  type=hidden id=txthidpenart".$li_i." class=sin-borde size=12 value='".$li_hidpenart."'>";
				$lo_object[$li_i][6]="<input name=txtcanart".$li_i."     type=text   id=txtcanart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canart,2,",",".")."'    style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montosfactura(".$li_i.");'>";
				$lo_object[$li_i][7]="<input name=txtpreuniart".$li_i."  type=text   id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' style='text-align:right' readonly>".
									 "<input name=hidnumdocori".$li_i."  type=hidden id=hidnumdocori".$li_i.">";
				$lo_object[$li_i][8]="<input name=txtmontotart".$li_i."  type=text   id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";
                $lo_object[$li_i][9]="<a href=javascript:uf_dt_activo(".$li_i.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a>";			
            	if($li_canart>0)
				{
					$li_totrowsc=$li_totrowsc + 1;
					$ls_debhab="D";
					$lo_objectc[$li_totrowsc][1]="<input  name=txtdenartc".$li_totrowsc."  type=text   id=txtdenartc".$li_totrowsc."  class=sin-borde size=50 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input  name=txtcodartc".$li_totrowsc."  type=hidden id=txtcodartc".$li_totrowsc."  class=sin-borde size=30 maxlength=50 value='".$ls_codart."' readonly>";
					$lo_objectc[$li_totrowsc][2]="<input  name=txtsccuenta".$li_totrowsc." type=text   id=txtsccuenta".$li_totrowsc." class=sin-borde size=30              value='".$ls_ctasep."' readonly>";
					$lo_objectc[$li_totrowsc][3]="<input  name=txtdebhab".$li_totrowsc."   type=text   id=txtdebhab".$li_totrowsc."   class=sin-borde size=15              value='".$ls_debhab."' readonly style='text-align:center'>";
					$lo_objectc[$li_totrowsc][4]="<input  name=txtmonto".$li_totrowsc."    type=text   id=txtcansolc".$li_totrowsc."  class=sin-borde size=30              value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";

	
					$li_totrowsc=$li_totrowsc + 1;
					$ls_debhab="H";
					$lo_objectc[$li_totrowsc][1]="<input  name=txtdenartc".$li_totrowsc."  type=text   id=txtdenartc".$li_totrowsc."  class=sin-borde size=50 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input  name=txtcodartc".$li_totrowsc."  type=hidden id=txtcodartc".$li_totrowsc."  class=sin-borde size=30 maxlength=50 value='".$ls_codart."' readonly>";
					$lo_objectc[$li_totrowsc][2]="<input  name=txtsccuenta".$li_totrowsc." type=text   id=txtsccuenta".$li_totrowsc." class=sin-borde size=30              value='".$ls_ctagas."' readonly>";
					$lo_objectc[$li_totrowsc][3]="<input  name=txtdebhab".$li_totrowsc."   type=text   id=txtdebhab".$li_totrowsc."   class=sin-borde size=15              value='".$ls_debhab."' readonly style='text-align:center'>";
					$lo_objectc[$li_totrowsc][4]="<input  name=txtmonto".$li_totrowsc."    type=text   id=txtcansolc".$li_totrowsc."  class=sin-borde size=30              value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";
				}
				$ls_ok=true;
			}
		break;
	}
?>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="676" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="744"><?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_inventario);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?></td>
    </tr>
    <tr>
      <td><table width="654" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" class="titulo-ventana">Despacho de Suministros </td>
          </tr>
          <tr class="formato-blanco">
            <td width="129" height="19">&nbsp;</td>
            <td width="118"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                <input name="hidreadonly" type="hidden" id="hidreadonly">
                <input name="contable" type="hidden" id="contable" value="<?php print $li_value; ?>">
            <input name="hidok" type="hidden" id="hidok" value="<?php print $ls_ok ?>">
            <input name="txtrif" type="hidden" id="txtrif" value="<?php print $ls_rifemp ?>"></td>
            <td width="305"><div align="right">Fecha</div></td>
            <td width="100"><input name="txtfecdes" type="text" id="txtfecdes" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecdes ?>" size="17" maxlength="10" datepicker="true" style="text-align:center "></td>
          </tr>
	      <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right">N&uacute;mero de la Orden de Despacho </div></td>
            <td height="22"><input name="txtnumorddes" type="text" id="txtnumorddes" value="<?php print $ls_numorddes; ?>" size="22" maxlength="15" readonly></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right">N&uacute;mero de la Solicitud</div></td>
            <td height="22"><input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol; ?>" size="22" maxlength="15" style="text-align:center " read></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right">Unidad Solicitante </div></td>
           <td height="22" colspan="2"><!-- <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" size="15" maxlength="10" style="text-align:center " readonly>
              <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="50" readonly>            </td> -->
                <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" size="15" maxlength="10" style="text-align:center" readonly>
                <a href="javascript: ue_cataunidad_despachar(2);"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="50" readonly>  </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Unidad a Despachar </div></td>
            <td height="22" colspan="3"><div align="left">
                <input name="txtcodunides" type="text" id="txtcodunides" value="<?php print $ls_codunides; ?>" size="15" maxlength="10" style="text-align:center" readonly>
                <a href="javascript: ue_cataunidad_despachar(1);"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a>
                <input name="txtdenunides" type="text" class="sin-borde" id="txtdenunides" value="<?php print $ls_denunides; ?>" size="50" readonly>
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right">Observaci&oacute;n</div></td>
            <td colspan="3" rowspan="2"><textarea name="txtobsdes" cols="70" rows="3" id="textarea" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú ()@#!%/[]*-+_');"><?php print $ls_obsdes; ?></textarea></td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right"></div></td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td height="13" colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <?php
		   if($ls_rifemp=='G-20000009-0')
		   {
		  ?>
            <td height="20"><div align="right">Nombre del Proyecto </div></td>
            <td height="22"><input name="txtnomproy" type="text" id="txtnomproy" value="<?php print $ls_nomproy; ?>" size="22" maxlength="100" ></td>
            <td>C&oacute;digo del Proyecto 
            <input name="txtcodproy" type="text" id="txtcodproy" value="<?php print $ls_codproy; ?>" size="22" maxlength="15" onKeyPress="return keyRestrict(event, '1234567890');" ></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <!--<td height="20"><div align="right">Plantel/Unidad Administrativa </div></td>
            <td height="22"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm;?>" size="13" maxlength="10" readonly>
              <a href="javascript: ue_cataunidad();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></td>
            <td height="22"><a href="javascript: ue_catalogo('tepuy_soc_cat_unidad_ejecutora.php');"></a>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm;?>" size="60" readonly>
            <td>&nbsp;</td>-->
          </tr>
          
          <tr>
            <td height="20"><div align="right">Direcci&oacute;n </div></td>
            <td height="22" colspan="2"><a href="javascript: ue_catalogo('tepuy_soc_cat_unidad_ejecutora.php');">
              <input name="txtdireccion" type="text" id="txtdireccion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');" value="<?php print $ls_direccion;?>" size="75" maxlength="254">
            </a></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Pais</div></td>
            <td height="22" colspan="2"><input name="txtcodpai" type="text" id="txtcodpai" value="<?php print $ls_codpai;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdespai" type="text" class="sin-borde" id="txtdespai" value="<?php print $ls_despai;?>" size="60" maxlength="50" ></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Estado</div></td>
            <td height="22" colspan="2"><input name="txtcodest" type="text" id="txcodest" value="<?php print $ls_codest;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" value="<?php print $ls_desest;?>" size="60" maxlength="50" ></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Municipio</div></td>
            <td height="22" colspan="2"><input name="txtcodmun" type="text" id="txtcodmun" value="<?php print $ls_codmun;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" value="<?php print $ls_desmun;?>" size="60" maxlength="50" readonly></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Parroquia</div></td>
            <td height="22" colspan="2"><input name="txtcodpar" type="text" id="txtcodpar" value="<?php print $ls_codpar;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarparroquia();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdespar" type="text" class="sin-borde" id="txtdespar" value="<?php print $ls_despar;?>" size="60" maxlength="50" readonly></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Director/Responsable</div></td>
            <td height="22"><input name="txtnomdirec" type="text" id="txtnomdirec" value="<?php print $ls_nomdirec; ?>" size="22" maxlength="50" ></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
		 <?php
		 }
		 ?>
          <tr class="formato-blanco">
            <td height="17">&nbsp;</td>
            <td colspan="3" align="left">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
         <!--   <td height="17">&nbsp;</td>
            <td colspan="3" align="left">
                <input name="rdtipodespacho" type="radio" class="sin-borde" value="1"  onClick="ue_completa();" <?php print $ls_checkedcomp; ?>>
              Completa 
              <input name="rdtipodespacho" type="radio" class="sin-borde" value="0"  onClick="ue_parcial();" <?php print $ls_checkedparc; ?>>
            Parcial
            <input name="txtestsol" type="hidden" id="txtestsol" value="<?php print $ls_estsol; ?>"></td>
          </tr> -->
          <tr class="formato-blanco">
            <td height="28" colspan="4">

		<!-- aqui se carga el grid de los articulos -->
			<?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
			?></td>
          </tr>
		  <?php
		  	if($li_value==1)
			{
		   ?>
          <tr class="formato-blanco">
            <td height="28" colspan="4"><input name="btngenerar" type="button" class="boton" id="btngenerar" value="Generar Detalle Contables" onClick="javascript: ue_contable();"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="28" colspan="4"><p>
              <?php
					$in_grid->makegrid($li_totrowsc,$lo_titlecontable,$lo_objectc,$li_widthcontable,$ls_titlecontable,$ls_namecontable);
			}
				?>
            </p>
                <p>&nbsp; </p></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          <input name="filadelete" type="hidden" id="filadelete">
          <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
          <input name="totalfilasc" type="hidden" id="totalfilasc" value="<?php print $li_totrowsc;?>"></td>
    </tr>
  </table>
  <div align="center"></div>
</form>
<p>&nbsp;</p>
<div align="center"></div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones  

function ue_catarticulo(li_linea)
{
	window.open("tepuy_catdinamic_articulosalmacen.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cataunidad()
{
  window.open("tepuy_siv_cat_unidad.php?destino=plantel","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cataunidad_despachar(campo)
{
	//alert(campo);
  window.open("tepuy_siv_cat_unidad.php?&campo="+campo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catarticulos(li_linea)
{
	window.open("tepuy_catdinamic_articulom.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catalmacen(li_linea)
{
	f=document.form1;
	ls_articulo= eval("f.txtcodart"+li_linea+".value");
	if (ls_articulo=="")
	{
		alert("Debe existir un articulo");
	}
	else
	{
		window.open("tepuy_catdinamic_almacendespacho.php?linea="+li_linea+"&codart="+ls_articulo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=425,height=200,left=180,top=160,location=no,resizable=yes");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		ls_rif=    f.txtrif.value;
		if(ls_rif!='G-20000009-0')
		{
		  window.open("tepuy_catdinamic_despacho.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	    }
		else
		{
		 window.open("tepuy_catdinamic_despacho.php?destino=destino&tipo=tipo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_imprimir(ls_reporte)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_ordendes=  f.txtnumorddes.value;
		if(ls_ordendes!="")
		{
			numsol=    f.txtnumsol.value;
			coduniadm= f.txtcoduniadm.value;
			denunidam= f.txtdenuniadm.value;
			fecdes=    f.txtfecdes.value;
			obsdes=    f.txtobsdes.value;
			codunides=f.txtcodunides.value;
			denunides= f.txtdenunides.value;
			coduniadm=f.txtcoduniadm.value;
			denuniadm= f.txtdenuniadm.value;
			ls_rif=    f.txtrif.value;
			if(ls_rif=='G-20000009-0')
		    {
			nomproy= f.txtnomproy.value;
  		    codproy= f.txtcodproy.value;
			codmun=f.txtcodmun.value;
			desmun=f.txtdesmun.value;
			codpar=f.txtcodpar.value;
			despar=f.txtdespar.value;
			nomdirec=f.txtnomdirec.value;
			codpai=f.txtcodpai.value;
			despai=f.txtdespai.value;
			codest=f.txtcodest.value;
			desest=f.txtdesest.value;
			direccion=f.txtdireccion.value;
			pagina="reportes/"+ls_reporte+"?numorddes="+ls_ordendes+"&numsol="+numsol+"&coduniadm="+coduniadm+"&fecdes="+fecdes;
		    pagina=pagina+"&obsdes="+obsdes+"&coduniadm="+coduniadm+"&denunidam="+denunidam+"&codproy="+codproy+"&nomproy="+nomproy;
			pagina=pagina+"&codmun="+codmun+"&desmun="+desmun+"&codpar="+codpar+"&despar="+despar;
			pagina=pagina+"&nomdirec="+nomdirec+"&codpai="+codpai+"&codunides="+codunides+"&denunides="+denunides;
			pagina=pagina+"&despai="+despai+"&codest="+codest+"&desest="+desest+"&direccion="+direccion+"";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		    }
			else
			{
			pagina="reportes/"+ls_reporte+"?numorddes="+ls_ordendes+"&numsol="+numsol+"&coduniadm="+coduniadm+"&fecdes="+fecdes;
		    pagina=pagina+"&obsdes="+"";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
		}
		else
		{alert("Debe existir un documento a imprimir");}
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		window.open("tepuy_catdinamic_sol_eje_pre.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_guardar()
{
	f=document.form1;
	ls_numorddes=eval("f.txtnumorddes.value"); 
	ls_numorddes=ue_validarvacio(ls_numorddes);
	li_totfilas= f.totalfilas.value;
	lb_ok=       f.hidok.value;
	li_contable= f.contable.value;
	li_procesar= f.ejecutar.value;
	if(li_procesar==1)
	{ 
		if((li_contable==0)||(lb_ok==true))
		{
			if(ls_numorddes!="")
			{
				lb_valido=true;
				ls_numsol=eval("f.txtnumsol.value");
				ls_numsol=ue_validarvacio(ls_numsol);
				ls_coduniadm=eval("f.txtcoduniadm.value");
				ls_coduniadm=ue_validarvacio(ls_coduniadm);
				ls_fecdes=eval("f.txtfecdes.value");
				ls_fecdes=ue_validarvacio(ls_fecdes);
				ls_rif=    f.txtrif.value;
				if(ls_rif=='G-20000009-0')
		        {
					ls_nomproy= eval("f.txtnomproy.value");
					ls_nomproy=ue_validarvacio(ls_nomproy);
					ls_codproy= eval("f.txtcodproy.value");
					ls_codproy= ue_validarvacio(ls_codproy);
					ls_codmun=eval("f.txtcodmun.value");
					ls_codmun= ue_validarvacio(ls_codmun);
					ls_desmun=eval("f.txtdesmun.value");
					ls_desmun= ue_validarvacio(ls_desmun);
					ls_codpar=eval("f.txtcodpar.value");
					ls_codpar= ue_validarvacio(ls_codpar);
					ls_despar=eval("f.txtdespar.value");
					ls_despar= ue_validarvacio(ls_despar);
					ls_nomdirec=eval("f.txtnomdirec.value");
					ls_nomdirec= ue_validarvacio(ls_nomdirec);
					ls_codpai=eval("f.txtcodpai.value");
					ls_codpai= ue_validarvacio(ls_codpai);
					ls_despai=eval("f.txtdespai.value");
					ls_despai= ue_validarvacio(ls_despai);
					ls_codest=eval("f.txtcodest.value");
					ls_codest= ue_validarvacio(ls_codest);
					ls_desest=eval("f.txtdesest.value");
					ls_desest= ue_validarvacio(ls_desest);
					ls_direccion=eval("f.txtdireccion.value");
					ls_direccion= ue_validarvacio(ls_direccion);
				}
				if ((ls_numsol=="")||(ls_coduniadm=="")||(ls_fecdes==""))
				{
					alert("Debe llenar los campos principales");
					lb_valido=false;
				}
				lb_blancos=true;
				li_blancos=0;
				for(li_i=1;li_i<=li_totfilas;li_i++)
				{
					ls_denart=    eval("f.txtdenart"+li_i+".value");
					ls_denart=ue_validarvacio(ls_denart);
					ls_codart=    eval("f.txtcodart"+li_i+".value");
					ls_codart=ue_validarvacio(ls_codart);
					ls_codalm=    eval("f.txtcodalm"+li_i+".value");
					ls_codalm=ue_validarvacio(ls_codalm);
					ls_unidad=    eval("f.txtunidad"+li_i+".value");
					ls_unidad=ue_validarvacio(ls_unidad);
					ls_canart=    eval("f.txtcanart"+li_i+".value");
					ls_canart=ue_validarvacio(ls_canart);
					ls_cansol=    eval("f.txtcansol"+li_i+".value");
					ls_cansol=ue_validarvacio(ls_cansol);
					ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
					ls_preuniart=ue_validarvacio(ls_preuniart);
					ls_montotart= eval("f.txtmontotart"+li_i+".value");
					ls_montotart=ue_validarvacio(ls_montotart);
					if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_codalm==""))
					{
						lb_blancos=false;
						li_blancos=li_blancos + 1;
					}
				}
				if((!lb_blancos)&&(lb_valido))
				{
					if(li_blancos==li_totfilas)
					{alert("Debe despachar al menos 1 artículo");}
					else
					{
						if(confirm("¿Desea continuar sin despachar todos los articulos?"))
						{
							lb_blancos=true;
						}
					}
				}
				if((lb_valido)&&(lb_blancos))
				{
					f.operacion.value="GUARDAR";
					f.action="tepuy_siv_p_despacho.php";
					f.submit();
				}
			}
			else
			{alert("No se puede modificar este registro");}
		}
		else
		{alert ("Debe actualizar el detalle contable");}
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

function uf_dt_activo(li_row)
{
	f=document.form1;
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_denart=eval("f.txtdenart"+li_row+".value");
	li_canart=eval("f.txtcanart"+li_row+".value");
	ls_estatus=f.hidestatus.value;
	ls_numorddes=f.txtnumsol.value;
	li_canart=ue_formato_operaciones(li_canart);
	fecha=f.txtfecdes.value;	
	if((ls_codart!="")&&(li_canart>0))
	{
		if(ls_estatus=="C")
		{
			window.open("tepuy_siv_pdt_incorporaractivos.php?codart="+ls_codart+"&canart="+li_canart+"&denart="+ls_denart+"&numorddes="+ls_numorddes+"&fecha="+fecha,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=600,left=30,top=30,location=no,resizable=yes");
		}
		else
		{
			alert("El movimiento debe estar registrado");
		}
	}
	else
	{
		alert("Debe exisistir mas de 1 articulo en el movimiento");
	}
}

function ue_montosfactura(li_row)
{
//--------------------------------------------------------
//	Función que calcula el monto total por articulo multiplicando la cantidad de articulos a despachar por el costo
//  unitario de cada uno de ellos, ademas verifica que la cantidad a despachar no sea mayor a la existencia en el almacen
//   que se ha indicado e igualmente no sea mayor a la cantidad solicitada.
//--------------------------------------------------------
	f=document.form1;
	lb_valido=true;
	ls_unisol=eval("f.txtunidad"+li_row+".value");
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	li_unidad=eval("f.hidunidad"+li_row+".value");
	li_unidad=ue_validarvacio(li_unidad);
	li_existencia=eval("f.hidexistencia"+li_row+".value");
	li_existencia=ue_validarvacio(li_existencia);
	li_canart=eval("f.txtcanart"+li_row+".value");
	li_canart=ue_validarvacio(li_canart);
	li_cansol=eval("f.txtcansol"+li_row+".value");
	li_cansol=ue_validarvacio(li_cansol);
	li_preuniart=eval("f.txtpreuniart"+li_row+".value");
	li_preuniart=ue_validarvacio(li_preuniart);
	li_canpendes=eval("f.txthidpenart"+li_row+".value");
	li_canpendes=ue_validarvacio(li_canpendes);
	li_preuniart=ue_formato_operaciones(li_preuniart);
	li_canart=ue_formato_operaciones(li_canart);
	li_cansol=ue_formato_operaciones(li_cansol);
	ls_estsol=f.txtestsol.value;
	f.hidok.value=false;
	li_canartaux=li_canart;
	if(ls_unidad=="Mayor")
	{
		li_canartaux=parseFloat(li_canart) * parseFloat(li_unidad);
	}
	if(parseFloat(li_existencia)<parseFloat(li_canartaux))
	{
		eval("f.txtcanart"+li_row+".value=''");
		eval("f.txtmontotart"+li_row+".value=''");
		alert("No hay suficientes, el maximo es de "+li_existencia+" articulos al detal");
		lb_valido=false;
	}
	if ((lb_valido==true)&&(li_canart!="")&&(li_preuniart!=""))
	{
		switch(ls_unisol)
		{
			case "Mayor":
				if(ls_unidad=="Detal")
				{
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					li_penart=(parseFloat(li_penart)/parseFloat(li_unidad));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				else
				{
					li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
					li_canart=String(li_canart);
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					li_penart=(parseFloat(li_penart)/parseFloat(li_unidad));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				
				}
				li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
				li_montot=uf_convertir(li_montot);
				obj=eval("f.txtmontotart"+li_row+"");
				obj.value=li_montot;
			break;
			case "Detal":
				if(ls_unidad=="Mayor")
				{
					li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
					li_canart=String(li_canart);
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				else
				{
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
				li_montot=uf_convertir(li_montot);
				obj=eval("f.txtmontotart"+li_row+"");
				obj.value=li_montot;
			break;
		}
	}
}

function ue_contable()
{
//--------------------------------------------------------
// Funcion que genera los asientos contables del despacho
//--------------------------------------------------------

	f=document.form1;
	li_totfilas=  f.totalfilas.value;
	li_totfilasc= f.totalfilasc.value;
	ls_numorddes= f.txtnumorddes.value;
	lb_blancos=   true;
	li_blancos=0;
	if(ls_numorddes!="")
	{
		for(li_i=1;li_i<=li_totfilas;li_i++)
		{
			ls_denart=    eval("f.txtdenart"+li_i+".value");
			ls_denart=ue_validarvacio(ls_denart);
			ls_codart=    eval("f.txtcodart"+li_i+".value");
			ls_codart=ue_validarvacio(ls_codart);
			ls_codalm=    eval("f.txtcodalm"+li_i+".value");
			ls_codalm=ue_validarvacio(ls_codalm);
			ls_unidad=    eval("f.txtunidad"+li_i+".value");
			ls_unidad=ue_validarvacio(ls_unidad);
			ls_canart=    eval("f.txtcanart"+li_i+".value");
			ls_canart=ue_validarvacio(ls_canart);
			ls_cansol=    eval("f.txtcansol"+li_i+".value");
			ls_cansol=ue_validarvacio(ls_cansol);
			ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
			ls_preuniart=ue_validarvacio(ls_preuniart);
			ls_montotart= eval("f.txtmontotart"+li_i+".value");
			ls_montotart=ue_validarvacio(ls_montotart);
			if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_codalm==""))
			{
				lb_blancos=false;
				li_blancos=li_blancos + 1;
			}
		}
		if((!lb_blancos)&&(lb_valido))
		{
			if(li_blancos!=li_totfilas)
			{lb_blancos=true;}
		}
		if(lb_blancos)
		{
			f.operacion.value="CALCULARCONTABLE";
			f.action="tepuy_siv_p_despacho.php";
			f.submit();
		}
	}
	else
	{alert("No se puede modificar este despacho");}
}

function ue_completa()
{
	f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		ls_unisol=eval("f.txtunidad"+li_i+".value");
		li_cansol=eval("f.txtcansol"+li_i+".value");
		li_canpendes=eval("f.txthidpenart"+li_i+".value");
		li_preuniart=eval("f.txtpreuniart"+li_i+".value");
		ls_unidad=eval("f.txtunidad"+li_i+".value");
		li_unidad=eval("f.hidunidad"+li_i+".value");
		if(li_preuniart!="")
		{
			if(ls_unisol=="Mayor")
			{
				obj=eval("f.cmbunidad"+li_i+".options[1]");
				obj.selected=true;
				li_canpendes=(parseFloat(li_canpendes)/parseFloat(li_unidad));
				li_canpendes=uf_convertir(li_canpendes);
				obj=eval("f.txtcanart"+li_i+"");
				obj.value=li_canpendes;
			}
			else
			{
				li_canpendes=uf_convertir(li_canpendes);
				obj=eval("f.cmbunidad"+li_i+".options[0]");
				obj.selected=true;
				obj=eval("f.txtcanart"+li_i+"");
				obj.value=li_canpendes;
			}
			obj=eval("f.txtpenart"+li_i+"");
			obj.value="0,00";
			li_canpendes=   ue_formato_operaciones(li_canpendes);
			li_preuniart=   ue_formato_operaciones(li_preuniart);
			if(ls_unidad=="Mayor")
			{
				li_canpendes=parseFloat(li_canpendes) * parseFloat(li_unidad);
				li_canpendes=String(li_canpendes);
			}
			li_montot=parseFloat(li_canpendes) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtmontotart"+li_i+"");
			obj.value=li_montot;
		}
		else
		{
			alert("Debe indicar el almacén del que desea despachar cada artículo.");
			f.rdtipodespacho[0].checked=false;			
			break;
		}
	}	
}

function ue_parcial()
{
    f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		obj=eval("f.txtpenart"+li_i+"");
		obj.value="";
		obj=eval("f.txtcanart"+li_i+"");
		obj.value="";
		obj=eval("f.txtmontotart"+li_i+"");
		obj.value="";
	}
}


//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
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

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("tepuy_snorh_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_buscarparroquia()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	codmun=ue_validarvacio(f.txtcodmun.value);
	if((codpai!="")||(codest!="")||(codmun!=""))
	{
		window.open("tepuy_snorh_cat_parroquia.php?codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais, un estado y un municipio.");
	}
}

function ue_buscarpais()
{
	window.open("tepuy_snorh_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("tepuy_snorh_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}


</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
