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
require_once("class_funciones_inventario.php");
$io_fun_inventario= new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SFA","tepuy_sfa_d_reactualizar_inventario.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_inventario->uf_select_config("SFA","REPORTE","REACTUALIZAR_INVENTARIO","tepuy_sfa_r_reactualizar_inventario.php","C");

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

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
		// Fecha Creación: 08/01/2015								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenpro".$ai_totrows."    type=text id=txtdenpro".$ai_totrows."    class=sin-borde size=20 maxlength=50 readonly><input name=txtcodpro".$ai_totrows." type=hidden id=txtcodpro".$ai_totrows." class=sin-borde size=20 maxlength=20 '></a>";
		$aa_object[$ai_totrows][2]="<input name=txtunimed".$ai_totrows." type=text id=txtunimed".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcanpro".$ai_totrows."    type=text id=txtcanpro".$ai_totrows."    class=sin-borde size=10 maxlength=12; onKeyPress=return(ue_formatonumero(this,'.',',',event));' onBlur='javascript: ue_calcularcosto(".$ai_totrows.");' >";
		$aa_object[$ai_totrows][4]="<input name=txtcanfac".$ai_totrows." type=text id=txtcanfac".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyPress=return(ue_formatonumero(this,'.',',',event)); 'readonly > ";
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
		// Fecha Creación: 08/01/2015								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodpro".$ai_totrows."    type=text id=txtcodpro".$ai_totrows."    class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catproducto(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<input name=txtunimed".$ai_totrows." type=text id=txtunimed".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcanpro".$ai_totrows."    type=text id=txtcanpro".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtcanfac".$ai_totrows." type=text id=txtcanfac".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtcanres".$ai_totrows." type=text id=txtcanres".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";

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
		//    Description: Función que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/01/2015								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_title=" Lista de Productos para actualizar inventario ";
		$lo_object="";
		$lo_title[1]="Productos";
		$lo_title[2]="Unidad de Medida";
		$lo_title[3]="Cantidad Ingresada";
		$lo_title[4]="Cantidad Facturada";
		$lo_title[5]="Cantidad Existente";
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
		//				   $ls_checkedfac // variable imprime o no "checked" para el radiobutton en la factura
		//	      Returns:
		//    Description: Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/02/2015								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $lo_object,$lo_objexiste;
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{	
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codpro=    $_POST["txtcodpro".$li_i];
			$ls_denpro=    $_POST["txtdenpro".$li_i];
			if (array_key_exists("la_logusr",$_POST))
			{
				$ls_unidad=    $_POST["txtunimed".$li_i];  
			}
			else
			{
				$ls_unidad=    "";
			}
			$li_canpro=    $_POST["txtcanpro".$li_i];
			$li_canfac= $_POST["txtcanfac".$li_i];
			$li_canres= $_POST["txtcanres".$li_i];
					
			$lo_object[$li_i][1]="<input name=txtdenpro".$li_i."    type=text id=txtdenpro".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denpro."' readonly>".
								 "<input name=txtcodpro".$li_i."    type=hidden id=txtcodpro".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codpro."' readonly><a href='javascript: ue_catproducto(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo de Producto' width='18' height='18' border='0'></a>";
			$lo_object[$li_i][2]="<div align='center'></div><input name=txtunimed".$li_i."    type=text id=txtunimed".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
			$lo_object[$li_i][3]="<input name=txtcanpro".$li_i."    type=text id=txtcanpro".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canpro."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$lo_object[$li_i][4]="<input name=txtcanfac".$li_i." type=text id=txtcanfac".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_canfac."' readonly>";
			$lo_object[$li_i][4]="<input name=txtcanres".$li_i." type=text id=txtcanres".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_canres."' readonly>";
  	    } 
		if($ls_estpro==1)
		{
		 // uf_agregarlineablanca($lo_object,$ai_totrows+1);	
		  uf_agregarlineablanca($lo_object,$ai_totrows);	
		}
		else
		{
		  //uf_agregarlineablancaorden($lo_object,$ai_totrows+1);
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
		//    Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/01/2015								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codpro,$ls_denpro,$ls_status;
		global $ls_checkedord,$ls_checkedfac,$ls_checkedparc,$ls_checkedcomp,$ls_codusu,$ls_readonly,$ls_readonlyrad,$li_totrows,$ls_hidsaverev;
		
		$ls_numcontrol="";
		$ls_numovinv="";
		$ls_numinginv="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_cedperaut="";
		$ls_nomperaut="";
		$ls_fecmovinv="";
		$ls_obsmov="";
		$li_totsuminv="0,00";
		$ls_checkedord="";
		$ls_checkedfac="";
		$ls_checkedparc="";
		$ls_checkedcomp="";
		$ls_readonlyrad="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_status="";
		$li_totrows=1;
		$ls_hidsaverev="false";
		$ls_coduniadm="";
		$ls_denuniadm="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reactualizaci&oacute;n de Inventario</title>
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
</script></head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Facturación --> Actualización de Inventario</td>
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
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.png" alt="Procesar" width="20" height="20" border="0" title="Procesar"></a></div></td>
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
	//require_once("tepuy_sfa_c_recepcion.php");
	//$io_sfa= new tepuy_sfa_c_recepcion();
	require_once("tepuy_sfa_c_producto.php");
	$io_sfa= new tepuy_sfa_c_producto();
	require_once("tepuy_sfa_c_movimientoinventario.php");
	$io_mov= new tepuy_sfa_c_movimientoinventario();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = $io_fun_inventario->uf_obtenervalor("totalfilas",1);
	$ls_hidsaverev = $io_fun_inventario->uf_obtenervalor("hidsaverev","");

	$ls_titletable="Movimiento de Productos en el Inventario";
	$li_widthtable=800;
	$ls_nametable="grid";
	$lo_title[1]="Producto";
	$lo_title[2]="Unidad de Medida";
	$lo_title[3]="Cant. Ingresada";
	$lo_title[4]="Cant. Facturada";
	$lo_title[5]="Cant. Existente";

	$ls_operacion= $io_fun_inventario->uf_obteneroperacion();
	$ls_status=    $io_fun_inventario->uf_obtenervalor("hidestatus","");
	if($ls_status=="C")
	{
		$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
		$li_catafilas= $io_fun_inventario->uf_obtenervalor("catafilas","");
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_status="";
			uf_limpiarvariables();
			$io_mov->uf_sfa_select_inventario($ls_codemp,$li_totrows,$lo_object);
			//print "numero: ".$ls_numinginv;
			//uf_agregarlineablanca($lo_object,1);
			
			//$li_totrows=1;
		break;

		
		case "PROCESAR":
			if($li_totrows>0)
			{
				$lb_valido=false;
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$ls_codpro=       $io_fun_inventario->uf_obtenervalor("txtcodpro".$li_i,"");
					$ls_denpro=       $io_fun_inventario->uf_obtenervalor("txtdenpro".$li_i,"");
					$li_existencia=   $io_fun_inventario->uf_obtenervalor("txtcanres".$li_i,"");
					$li_canres=   $io_fun_inventario->uf_obtenervalor("txtcanres".$li_i,"");
					$li_canpro=       $io_fun_inventario->uf_obtenervalor("txtcanpro".$li_i,"");
					$li_canfac=    $io_fun_inventario->uf_obtenervalor("txtcanfac".$li_i,"");
					$li_canfac1=    $io_fun_inventario->uf_obtenervalor("txtcanfac".$li_i,"");
					$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunimed".$li_i,"");
					$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
					$ls_unidad=	  $io_fun_inventario->uf_obtenervalor("txtunimed".$li_i,"");
					$li_existencia= str_replace(".","",$li_existencia);
					$lb_valido=$io_mov->uf_sfa_update_inventario($ls_codemp,$ls_codpro,$li_existencia,$ls_logusr,$la_seguridad);
						
					$lo_object[$li_i][1]="<input name=txtdenpro".$li_i."    type=text id=txtdenpro".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denpro."' readonly>".
										 "<input name=txtcodpro".$li_i."    type=hidden id=txtcodpro".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codpro."' readonly></a>";
					$lo_object[$li_i][2]="<input name=txtunimed".$li_i."    type=text id=txtunimed".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."' value='". $ls_unidad ."'>";
					$lo_object[$li_i][3]="<input name=txtcanpro".$li_i."    type=text id=txtcanpro".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canpro."' readonly>";
			//		$lo_object[$li_i][5]="<input name=txtpenart".$li_i."    type=text id=txtpenart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_penart."'readonly>";
					$lo_object[$li_i][4]="<input name=txtcanfac".$li_i." type=text id=txtcanfac".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_canfac."' readonly>";
					$lo_object[$li_i][5]="<input name=txtcanres".$li_i." type=text id=txtcanres".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_canres."' readonly>";
				
				}  // fin  for($li_i=1;$li_i<$li_totrows;$li_i++)
				if($lb_valido)
				{
					$io_msg->message("la Actualizacion de los Productos se realizo con exito");
				}
				else
				{
					$io_msg->message("Imposible realizar la Actualizacion de los Productos");
				}
			}
			else
			{
				$io_msg->message("No existen productos para actualizar");
				$li_totrows=1;
				$li_totrowsc=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_agregarlineablancacontable($lo_objectc,1);
				uf_limpiarvariables();
			}

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
	$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_inventario);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="755" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="744" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana"> Reactualización de Inventario </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="156" height="19">&nbsp;</td>
                    <td width="373"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly">
                      <input name="txtnumovinv" type="hidden" id="txtnumovinv" value="<?php print $ls_numovinv ?>"></td>
                    <td height="28" colspan="4"><p>
                      <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                    </p>                      </td>
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
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 


function ue_procesar()
{
	f=document.form1;
	lb_valido=true;
	li_totfilas=f.totalfilas.value;
	//alert(li_totfilas);
	if(li_totfilas<=1)
	{
	   // if ((f.radiotipo[0].checked==true)||(f.radiotipo[1].checked==true))
		//{
			alert("La entrada de productos debe tener al menos 1 ");
			lb_valido=false;
		//}
	}

	if(lb_valido)
	{
		//ls_hidsaverev=f.hidsaverev.value;
		f.operacion.value="PROCESAR";
		f.action="tepuy_sfa_d_reactualizar_inventario.php";
		f.submit();
	}
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}



</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
