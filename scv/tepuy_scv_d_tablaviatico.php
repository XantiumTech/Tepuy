<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	//ini_set('display_errors', 1);
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_viaticos.php");
	$io_fun_viatico=new class_funciones_viaticos();
	$io_fun_viatico->uf_load_seguridad("SCV","tepuy_scv_d_tablaviatico.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_tipoviatico,$ls_denviatico,$ls_activarcodigo,$ls_adelantaquincena,$ls_desincorporarnomina;
		global $ls_adelantaretencion,$ls_bonoautomatico,$la_periodo,$li_totrows,$ls_operacion,$ls_existe,$io_fun_viatico;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_tipoviatico="";
		$ls_denviatico="Tabla de Viaticos";
		$ls_activarcodigo="";
		$la_periodo[0]="";
		$la_periodo[1]="";
		$ls_adelantaquincena="";
		$ls_adelantaretencion="";
		$ls_bonoautomatico="";
		$ls_anoserpre="";
		$ls_titletable="Tabla de Viaticos";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Categoria";
		$lo_title[2]="Cant. Salario Minimo";
		$lo_title[3]="Hasta Salario Minimo";
		$lo_title[4]="U.T. Fuera del Estado";
		$lo_title[5]="U.T. Dentro del Estado";
		$lo_title[6]=" ";
		$lo_title[7]=" ";
		$li_totrows=$io_fun_viatico->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_viatico->uf_obtenerexiste();
		//print "operacion".$ls_operacion;
		$ls_operacion=$io_fun_viatico->uf_obteneroperacionvia();
		//if($ls_operacion="NUEVO"){$ls_operacion="BUSCARDETALLE";}else{}
		//$ls_operacion=$io_fun_viatico->uf_obteneroperacionvia();
		//print "operacion".$ls_operacion;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodviatico".$ai_totrows." type=text id=txlappervac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtcantsalarioini".$ai_totrows." type=text id=txtcantsalarioini".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][3]="<input name=txtcantsalariofin".$ai_totrows." type=text id=txtcantsalariofin".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtutfuera".$ai_totrows." type=text id=txtutfuera".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtutdentro".$ai_totrows." type=text id=txtutdentro".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0></a>";			
   }
   //--------------------------------------------------------------
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
<title >Definici&oacute;n de Tabla de Viaticos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_viatico.js"></script>
<link href="js/viatico.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("tepuy_scv_c_tablaviatico.php");
	$io_tablavia=new tepuy_scv_c_tablaviatico();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	//$ls_operacion=$io_fun_viatico->uf_obteneroperacionvia();
	//print "opera:".$ls_operacion;//=$io_fun_viatico->uf_obteneroperacionvia();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
		 	/*$ls_tipoviatico=$_POST["txtcodviatico"];
			$ls_denviatico=$_POST["txtdenviatico"];
			$ls_pertabvac=$io_fun_viatico->uf_obtenervalor("cmbpertabvac","");
		 	$li_adequitabvac=$io_fun_viatico->uf_obtenervalor("chkadequitabvac","0");
			$li_aderettabvac=$io_fun_viatico->uf_obtenervalor("chkaderettabvac","0");			
		 	$li_bonauttabvac=$io_fun_viatico->uf_obtenervalor("chkbonauttabvac","0");
		 	$li_anoserpre=$io_fun_viatico->uf_obtenervalor("chkanoserpre","0");
			$io_tablavia->io_sql->begin_transaction();
			$lb_valido=$io_tablavia->uf_guardar($ls_existe,$ls_tipoviatico,$ls_denviatico,$ls_pertabvac,$li_adequitabvac,$li_aderettabvac,
											 	$li_bonauttabvac,$li_anoserpre,$la_seguridad);
			*/
			$lb_valido=true;
			//print "Total:".$li_totrows;
			for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
			{
				$li_lappervac=$_POST["txtcodviatico".$li_i];
				$li_diadisvac=$_POST["txtcantsalarioini".$li_i];
				$li_diaadidisvac=$_POST["txtcantsalariofin".$li_i];
				$li_diabonvac=$_POST["txtutfuera".$li_i];
				$li_diaadibonvac=$_POST["txtutdentro".$li_i];
				$lb_valido=$io_tablavia->uf_guardar_itemviatico($li_lappervac,$li_diadisvac,$li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,$ls_existe,$la_seguridad);
			}
			if($lb_valido)
			{
				$io_tablavia->io_sql->commit();
				if($ls_existe=="TRUE")
				{
					$io_tablavia->io_mensajes->message("La tabla de Viaticos fue Actualizada.");
				}
				else
				{
					$io_tablavia->io_mensajes->message("La tabla de Viaticos fue Registrada.");
				}
			}
			else
			{
				$io_tablavia->io_sql->rollback();
				$io_tablavia->io_mensajes->message("Ocurrio un error al guardar la tabla de viaticos.");
			}
			$_POST["operacion"]="BUSCARDETALLE";
			uf_limpiarvariables();
			//print "operacion".$_POST["operacion"];
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			header("Refresh:0"); // Refresca la Pagina y vuelve a Mostrar la Tabla
			break;


		case "AGREGARDETALLE":
		 	/*$ls_tipoviatico=$_POST["txtcodviatico"];
			$ls_denviatico=$_POST["txtdenviatico"];*/
			$ls_activarcodigo="readOnly";
			/*$ls_pertabvac=$io_fun_viatico->uf_obtenervalor("cmbpertabvac","");
			$io_fun_viatico->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_viatico->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_viatico->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_viatico->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_viatico->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_viatico->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_viatico->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_viatico->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_viatico->uf_obtenervariable($li_anoserpre,1,0,"checked","","");*/
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$li_lappervac=$_POST["txtcodviatico".$li_i];
				$li_diadisvac=$_POST["txtcantsalarioini".$li_i];
				$li_diaadidisvac=$_POST["txtcantsalariofin".$li_i];
				$li_diabonvac=$_POST["txtutfuera".$li_i];
				$li_diaadibonvac=$_POST["txtutdentro".$li_i];
				$lo_object[$li_i][1]="<input name=txtcodviatico".$li_i." type=text id=txtcodviatico".$li_i." class=sin-borde size=6 value='".$li_lappervac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_i][2]="<input name=txtcantsalarioini".$li_i." type=text id=txtcantsalarioini".$li_i." class=sin-borde size=6 value='".$li_diadisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][3]="<input name=txtcantsalariofin".$li_i." type=text id=txtcantsalariofin".$li_i." class=sin-borde size=6 value='".$li_diaadidisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][4]="<input name=txtutfuera".$li_i." type=text id=txtutfuera".$li_i." class=sin-borde size=6 value='".$li_diabonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$lo_object[$li_i][5]="<input name=txtutdentro".$li_i." type=text id=txtutdentro".$li_i." class=sin-borde size=6 value='".$li_diaadibonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	//$ls_tipoviatico=$_POST["txtcodtabvac"];
			//$ls_denviatico=$_POST["txtdenviatico"];
			$ls_activarcodigo="readOnly";
			/*$ls_pertabvac=$io_fun_viatico->uf_obtenervalor("cmbpertabvac","");
			$io_fun_viatico->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_viatico->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_viatico->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_viatico->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_viatico->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_viatico->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_viatico->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_viatico->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_viatico->uf_obtenervariable($li_anoserpre,1,0,"checked","","");*/
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			//print "Linea a eliminar: ".$li_rowdelete;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$li_lappervac=$_POST["txtcodviatico".$li_i];
					$li_diadisvac=$_POST["txtcantsalarioini".$li_i];
					$li_diaadidisvac=$_POST["txtcantsalariofin".$li_i];
					$li_diabonvac=$_POST["txtutfuera".$li_i];
					$li_diaadibonvac=$_POST["txtutdentro".$li_i];
					$lo_object[$li_temp][1]="<input name=txtcodviatico".$li_temp." type=text id=txtcodviatico".$li_temp." class=sin-borde size=6 value='".$li_lappervac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][2]="<input name=txtcantsalarioini".$li_temp." type=text id=txtcantsalarioini".$li_temp." class=sin-borde size=6 value='".$li_diadisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_temp][3]="<input name=txtcantsalariofin".$li_temp." type=text id=txtcantsalariofin".$li_temp." class=sin-borde size=6 value='".$li_diaadidisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_temp][4]="<input name=txtutfuera".$li_temp." type=text id=txtutfuera".$li_temp." class=sin-borde size=6 value='".$li_diabonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
					$lo_object[$li_temp][5]="<input name=txtutdentro".$li_temp." type=text id=txtutdentro".$li_temp." class=sin-borde size=6 value='".$li_diaadibonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
					$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$ls_tipoviatico=$_POST["txtcodviatico".$li_i];
					$lb_valido=$io_tablavia->uf_delete_itemviatico($ls_tipoviatico,$la_seguridad);
					if($lb_valido)
					{
					}			
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_tipoviatico=$_POST["txtcodtabvac"];
			$ls_denviatico=$_POST["txtdenviatico"];
			$ls_activarcodigo="readOnly";
			$ls_pertabvac=$io_fun_viatico->uf_obtenervalor("cmbpertabvac","");
			$io_fun_viatico->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_viatico->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_viatico->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_viatico->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_viatico->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_viatico->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_viatico->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_viatico->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_viatico->uf_obtenervariable($li_anoserpre,1,0,"checked","","");
			$lb_valido=$io_tablavia->uf_load_tablaviatico($ls_tipoviatico,$li_totrows,$lo_object);
			if ($lb_valido==false)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$io_tablavia->uf_destructor();
	unset($io_tablavia);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	 </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <!--<td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>-->
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
  <!--  <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>-->
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viatico->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_viatico);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Tabla de Viaticos </td>
        </tr>
        <tr>
          <td width="157" height="22">&nbsp;</td>
          <td width="387">&nbsp;</td>
        </tr>
        <tr>
        <!--  <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodtabvac" type="text" id="txtcodtabvac" size="1" maxlength="2" value="<?php print $ls_tipoviatico;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,2);" <?php print $ls_activarcodigo;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Denominaci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdenviatico" type="text" id="txtdenviatico" value="<?php print $ls_denviatico;?>" size="30" maxlength="30" onKeyUp="ue_validarcomillas(this);">
          </div></td>
          </tr>-->
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
        <tr>
          <td colspan="2">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			</p>			</td>		  
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f=document.form1;
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="tepuy_scv_d_tablaviatico.php";
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
	lb_existe=f.existe.value;
	lb_valido=false;
	//alert(lb_existe);
	li_total=f.totalfilas.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		for(li_i=1;li_i<=li_total-1&&lb_valido!=true;li_i++)
		{
			li_lappervac=eval("f.txtcodviatico"+li_i+".value");
			li_lappervac=ue_validarvacio(li_lappervac);
			li_diadisvac=eval("f.txtcantsalarioini"+li_i+".value");
			li_diadisvac=ue_validarvacio(li_diadisvac);
			li_diaadidisvac=eval("f.txtcantsalariofin"+li_i+".value");
			li_diaadidisvac=ue_validarvacio(li_diaadidisvac);
			li_diabonvac=eval("f.txtutfuera"+li_i+".value");
			li_diabonvac=ue_validarvacio(li_diabonvac);
			li_diaadibonvac=eval("f.txtutdentro"+li_i+".value");
			li_diaadibonvac=ue_validarvacio(li_diaadibonvac);
			if((li_lappervac=="")||(li_diadisvac=="")||(li_diabonvac=="")||(li_diaadidisvac=="")||(li_diaadibonvac==""))
			{
				alert("Debe llenar todos los datos");
				//alert(li_i);
				lb_valido=true;
			}
		}
		if(!lb_valido)
		{
			f.operacion.value="GUARDAR";
			f.action="tepuy_scv_d_tablaviatico.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}


function ue_cerrar()
{
	location.href = "tepuywindow_blank.php";
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/tepuy_hlp_snr_tablaviaticoes.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_scv_cat_tablaviatico.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_lappervacnew=eval("f.txtcodviatico"+li_row+".value");
		//alert(li_lappervacnew);
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_lappervac=eval("f.txtcodviatico"+li_i+".value");
			if((li_lappervac==li_lappervacnew)&&(li_i!=li_row))
			{
				alert("el periodo ya existe");
				lb_valido=true;
			}
		}

		li_lappervac=eval("f.txtcodviatico"+li_row+".value");
		li_lappervac=ue_validarvacio(li_lappervac);
		li_diadisvac=eval("f.txtcantsalarioini"+li_row+".value");
		li_diadisvac=ue_validarvacio(li_diadisvac);
		li_diaadidisvac=eval("f.txtcantsalariofin"+li_row+".value");
		li_diaadidisvac=ue_validarvacio(li_diaadidisvac);
		li_diabonvac=eval("f.txtutfuera"+li_row+".value");
		li_diabonvac=ue_validarvacio(li_diabonvac);
		li_diaadibonvac=eval("f.txtutdentro"+li_row+".value");
		li_diaadibonvac=ue_validarvacio(li_diaadibonvac);
	
		if((li_lappervac=="")||(li_diadisvac=="")||(li_diabonvac=="")||(li_diaadidisvac=="")||(li_diaadibonvac==""))
		{
			alert("Debe llenar todos los datos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="tepuy_scv_d_tablaviatico.php";
			f.submit();
		}
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_lappervac=eval("f.txtcodviatico"+li_row+".value");
		li_lappervac=ue_validarvacio(li_lappervac);
		if(li_lappervac=="")
		{
			alert("la fila a eliminar no debe estar vacio el Item");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE";
				f.action="tepuy_scv_d_tablaviatico.php";

				f.submit();
			}
		}
	}
}
</script> 
</html>
