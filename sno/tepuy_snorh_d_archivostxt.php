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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","tepuy_snorh_d_archivostxt.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codarch,$ls_denarch,$ls_activarcodigo,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codarch="";
		$ls_denarch="";
		$ls_activarcodigo="";
		$ls_titletable="Campos TXT";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="C�digo";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Inicio";
		$lo_title[4]="Longitud";
		$lo_title[5]="Editable";
		$lo_title[6]="Clave";
		$lo_title[7]="Actualizar";
		$lo_title[8]="Criterio";
		$lo_title[9]="Tipo";
		$lo_title[10]="Tabla";
		$lo_title[11]="Item";
		$lo_title[12]=" ";
		$lo_title[13]=" ";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Funci�n que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodcam".$ai_totrows." type=text id=txtcodcam".$ai_totrows." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtdescam".$ai_totrows." type=text id=txtdescam".$ai_totrows." class=sin-borde size=10 maxlength=20 onKeyUp='javascript: ue_validartexto(this);'>";
		$aa_object[$ai_totrows][3]="<input name=txtinicam".$ai_totrows." type=text id=txtinicam".$ai_totrows." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtloncam".$ai_totrows." type=text id=txtloncam".$ai_totrows." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<select name=cmbedicam".$ai_totrows." id=cmbedicam".$ai_totrows."><option value='0' >No</option><option value='1' >Si</option> </select>";
		$aa_object[$ai_totrows][6]="<select name=cmbclacam".$ai_totrows." id=cmbclacam".$ai_totrows."><option value='0' >No</option><option value='1' >Si</option> </select>";
		$aa_object[$ai_totrows][7]="<select name=cmbactcam".$ai_totrows." id=cmbactcam".$ai_totrows."><option value='0' >No</option><option value='1' >Si</option> </select>";
		$aa_object[$ai_totrows][8]="<textarea name=txtcricam".$ai_totrows." id=txtcricam".$ai_totrows." class=sin-borde cols='30' rows='2' onKeyUp='javascript: ue_validartexto(this);'></textarea>";
		$aa_object[$ai_totrows][9]="<select name=cmbtipcam".$ai_totrows." id=cmbtipcam".$ai_totrows."><option value='C'>Caracter</option><option value='N'>Numerico</option></select>";
		$aa_object[$ai_totrows][10]="<select name=cmbtabrelcam".$ai_totrows." id=cmbtabrelcam".$ai_totrows."><option value='sno_constantepersonal'>Constante persona</option></select>";
		$aa_object[$ai_totrows][11]="<select name=cmbiterelcam".$ai_totrows." id=cmbiterelcam".$ai_totrows."><option value=''>Informativo</option><option value='codcons'>Codigo Constante</option>".
								   "<option value='codper'>Codigo Personal</option><option value='moncon'>Monto Constante</option></select>";		
		$aa_object[$ai_totrows][12]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][13]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.png alt=Eliminar width=15 height=15 border=0></a>";			
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_codcam,$ls_descam,$li_inicam,$li_loncam,$ls_edicam,$la_edicam,$ls_clacam,$la_clacam, $ls_actcam,$la_actcam;		
		global $ls_tabrelcam,$ls_iterelcam,$la_iterelcam,$ls_cricam,$ls_tipcam,$la_tipcam;

		$li_codcam=$_POST["txtcodcam".$li_i];
		$ls_descam=$_POST["txtdescam".$li_i];
		$li_inicam=$_POST["txtinicam".$li_i];
		$li_loncam=$_POST["txtloncam".$li_i];
		$ls_cricam=$_POST["txtcricam".$li_i];
		$ls_edicam=$_POST["cmbedicam".$li_i];
		$la_edicam[0]="";
		$la_edicam[1]="";
		$ls_clacam=$_POST["cmbclacam".$li_i];
		$la_clacam[0]="";
		$la_clacam[1]="";
		$ls_actcam=$_POST["cmbactcam".$li_i];
		$la_actcam[0]="";
		$la_actcam[1]="";
		$ls_tabrelcam=$_POST["cmbtabrelcam".$li_i];
		$ls_iterelcam=$_POST["cmbiterelcam".$li_i];
		$la_iterelcam[0]="";
		$la_iterelcam[1]="";
		$la_iterelcam[2]="";
		$ls_tipcam=$_POST["cmbtipcam".$li_i];
		$la_tipcam[0]="";
		$la_tipcam[1]="";
		switch($ls_tipcam)
		{
			case "C":
				$la_tipcam[0]="selected";
				break;
			case "N":
				$la_tipcam[1]="selected";
				break;
		}
		switch($ls_edicam)
		{
			case "0":
				$la_edicam[0]="selected";
				break;
			case "1":
				$la_edicam[1]="selected";
				break;
		}
		switch($ls_clacam)
		{
			case "0":
				$la_clacam[0]="selected";
				break;
			case "1":
				$la_clacam[1]="selected";
				break;
		}
		switch($ls_actcam)
		{
			case "0":
				$la_actcam[0]="selected";
				break;
			case "1":
				$la_actcam[1]="selected";
				break;
		}
		switch($ls_iterelcam)
		{
			case "codcons":
				$la_iterelcam[0]="selected";
				break;
			case "codper":
				$la_iterelcam[1]="selected";
				break;
			case "moncon":
				$la_iterelcam[2]="selected";
				break;
		}
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
<title >Definici&oacute;n de Archivos TXT</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("tepuy_snorh_c_archivotxt.php");
	$io_archivo=new tepuy_snorh_c_archivotxt();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$io_archivo->io_sql->begin_transaction();
			$lb_valido=$io_archivo->uf_guardar($ls_existe,$ls_codarch,$ls_denarch,$la_seguridad);
			if($lb_valido)
			{
				$lb_valido=$io_archivo->uf_delete_campos($ls_codarch,$la_seguridad);
				for($li_i=1;($li_i<$li_totrows)&&($lb_valido);$li_i++)
				{
					uf_cargar_dt($li_i);
					$lb_valido=$io_archivo->uf_insert_archivotxt_campos($ls_codarch,$li_codcam,$ls_descam,$li_inicam,$li_loncam,$ls_edicam,$ls_clacam,$ls_actcam,
																		$ls_tabrelcam,$ls_iterelcam,$ls_cricam,$ls_tipcam,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_archivo->io_sql->commit();
				if($ls_existe=="TRUE")
				{
					$io_archivo->io_mensajes->message("El archivo txt fue Actualizado.");
				}
				else
				{
					$io_archivo->io_mensajes->message("El archivo txt fue Registrado.");
				}
			}
			else
			{
				$io_archivo->io_sql->rollback();
				$io_archivo->io_mensajes->message("Ocurrio un error al guardar el archivo txt.");
			}
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "ELIMINAR":
			$ls_codarch=$_POST["txtcodarch"];
			$lb_valido=$io_archivo->uf_delete_archivotxt($ls_codarch,$la_seguridad);
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "AGREGARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<input name=txtcodcam".$li_i." type=text id=txtcodcam".$li_i." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_codcam."'>";
				$lo_object[$li_i][2]="<input name=txtdescam".$li_i." type=text id=txtdescam".$li_i." class=sin-borde size=10 maxlength=20 onKeyUp='javascript: ue_validartexto(this);' value='".$ls_descam."'>";
				$lo_object[$li_i][3]="<input name=txtinicam".$li_i." type=text id=txtinicam".$li_i." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_inicam."'>";
				$lo_object[$li_i][4]="<input name=txtloncam".$li_i." type=text id=txtloncam".$li_i." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_loncam."'>";
				$lo_object[$li_i][5]="<select name=cmbedicam".$li_i." id=cmbedicam".$li_i."><option value='0' ".$la_edicam[0]." >No</option><option value='1' ".$la_edicam[1].">Si</option></select>";
				$lo_object[$li_i][6]="<select name=cmbclacam".$li_i." id=cmbclacam".$li_i."><option value='0' ".$la_clacam[0]." >No</option><option value='1' ".$la_clacam[1].">Si</option></select>";
				$lo_object[$li_i][7]="<select name=cmbactcam".$li_i." id=cmbactcam".$li_i."><option value='0' ".$la_actcam[0]." >No</option><option value='1' ".$la_actcam[1].">Si</option></select>";
				$lo_object[$li_i][8]="<textarea name=txtcricam".$li_i." id=txtcricam".$li_i." class=sin-borde cols='30' rows='2' onKeyUp='javascript: ue_validartexto(this);'>".$ls_cricam."</textarea>";
				$lo_object[$li_i][9]="<select name=cmbtipcam".$li_i." id=cmbtipcam".$li_i."><option value='C' ".$la_tipcam[0].">Caracter</option><option value='N' ".$la_tipcam[1].">Numerico</option></select>";
				$lo_object[$li_i][10]="<select name=cmbtabrelcam".$li_i." id=cmbtabrelcam".$li_i."><option value='sno_constantepersonal'>Constante persona</option></select>";
				$lo_object[$li_i][11]="<select name=cmbiterelcam".$li_i." id=cmbiterelcam".$li_i."><option value='' selected>Informativo</option><option value='codcons' ".$la_iterelcam[0].">Codigo Constante</option>".
								     "<option value='codper' ".$la_iterelcam[1].">Codigo Personal</option><option value='moncon' ".$la_iterelcam[2].">Monto Constante</option></select>";		
				$lo_object[$li_i][12]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][13]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.png alt=Eliminar width=15 height=15 border=0></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
					$lo_object[$li_temp][1]="<input name=txtcodcam".$li_temp." type=text id=txtcodcam".$li_temp." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_codcam."'>";
					$lo_object[$li_temp][2]="<input name=txtdescam".$li_temp." type=text id=txtdescam".$li_temp." class=sin-borde size=10 maxlength=20 onKeyUp='javascript: ue_validartexto(this);' value='".$ls_descam."'>";
					$lo_object[$li_temp][3]="<input name=txtinicam".$li_temp." type=text id=txtinicam".$li_temp." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_inicam."'>";
					$lo_object[$li_temp][4]="<input name=txtloncam".$li_temp." type=text id=txtloncam".$li_temp." class=sin-borde size=4 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_loncam."'>";
					$lo_object[$li_temp][5]="<select name=cmbedicam".$li_temp." id=cmbedicam".$li_temp."><option value='0' ".$la_edicam[0]." >No</option><option value='1' ".$la_edicam[1].">Si</option></select>";
					$lo_object[$li_temp][6]="<select name=cmbclacam".$li_temp." id=cmbclacam".$li_temp."><option value='0' ".$la_clacam[0]." >No</option><option value='1' ".$la_clacam[1].">Si</option></select>";
					$lo_object[$li_temp][7]="<select name=cmbactcam".$li_temp." id=cmbactcam".$li_temp."><option value='0' ".$la_actcam[0]." >No</option><option value='1' ".$la_actcam[1].">Si</option></select>";
					$lo_object[$li_temp][8]="<textarea name=txtcricam".$li_temp." id=txtcricam".$li_temp." class=sin-borde cols='30' rows='2' onKeyUp='javascript: ue_validartexto(this);'>".$ls_cricam."</textarea>";
					$lo_object[$li_temp][9]="<select name=cmbtipcam".$li_temp." id=cmbtipcam".$li_temp."><option value='C' ".$la_tipcam[0].">Caracter</option><option value='N' ".$la_tipcam[1].">Numerico</option></select>";
					$lo_object[$li_temp][10]="<select name=cmbtabrelcam".$li_temp." id=cmbtabrelcam".$li_temp."><option value='sno_constantepersonal'>Constante persona</option></select>";
					$lo_object[$li_temp][11]="<select name=cmbiterelcam".$li_temp." id=cmbiterelcam".$li_temp."><option value='' selected>Informativo</option><option value='codcons' ".$la_iterelcam[0].">Codigo Constante</option>".
										 "<option value='codper' ".$la_iterelcam[1].">Codigo Personal</option><option value='moncon' ".$la_iterelcam[2].">Monto Constante</option></select>";		
					$lo_object[$li_temp][12]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][13]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.png alt=Eliminar width=15 height=15 border=0></a>";			
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_archivo->uf_load_archivotxt_campos($ls_codarch,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	$io_archivo->uf_destructor();
	unset($io_archivo);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
	<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de N�mina</td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Archivos TXT </td>
        </tr>
        <tr>
          <td width="157" height="22">&nbsp;</td>
          <td width="387">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodarch" type="text" id="txtcodarch" size="6" maxlength="4" value="<?php print $ls_codarch;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" <?php print $ls_activarcodigo;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Denominaci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdenarch" type="text" id="txtdenarch" value="<?php print $ls_denarch;?>" size="60" maxlength="120" onKeyUp="ue_validarcomillas(this);">
          </div></td>
          </tr>
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
		f.action="tepuy_snorh_d_archivostxt.php";
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
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		ls_codarch=ue_validarvacio(f.txtcodarch.value);
		ls_denarch=ue_validarvacio(f.txtdenarch.value);
		li_total=f.totalfilas.value;
		if ((ls_codarch=="")||(ls_codarch=="")||(li_total=="0"))
		{
			alert("Debe llenar todos los datos.");
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="tepuy_snorh_d_archivostxt.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			ls_codarch = ue_validarvacio(f.txtcodarch.value);
			if (ls_codarch=="")
			{
				alert("Debe buscar el registro a eliminar.");
			}
			else
			{
				if(confirm("�Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="tepuy_snorh_d_archivostxt.php";
					f.submit();
				}
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
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
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/tepuy_hlp_snr_tablavacaciones.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("tepuy_snorh_cat_archivotxt.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
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
		li_codcamnew=eval("f.txtcodcam"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodcam"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("el campo ya existe");
				lb_valido=true;
			}
		}
		ls_codarch=ue_validarvacio(f.txtcodarch.value);
		ls_denarchc=ue_validarvacio(f.txtdenarch.value);
		li_codcam=eval("f.txtcodcam"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		ls_descam=eval("f.txtdescam"+li_row+".value");
		ls_descam=ue_validarvacio(ls_descam);
		li_inicam=eval("f.txtinicam"+li_row+".value");
		li_inicam=ue_validarvacio(li_inicam);
		li_loncam=eval("f.txtloncam"+li_row+".value");
		li_loncam=ue_validarvacio(li_loncam);
		if((ls_codarch=="")||(ls_denarchc=="")||(li_codcam=="")||(ls_descam=="")||(li_inicam=="")||(li_loncam==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="tepuy_snorh_d_archivostxt.php";
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
		li_codcam=eval("f.txtcodcam"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("�Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="tepuy_snorh_d_archivostxt.php";
				f.submit();
			}
		}
	}
}
</script> 
</html>
