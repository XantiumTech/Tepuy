<?php
	session_start();
  	unset($_SESSION["parametros"]);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","tepuy_srh_p_asignar_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

 function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

   		global $ls_nroreg,$ls_fecha, $ls_obs, $ls_tipcon,$ls_codcon, $ls_descon,$ls_fechaaper, $ls_fechacie, $ls_codcar, $li_cantcar,$ls_estatus, $ls_activarcodigo,$ls_guardar,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows2,$ls_titletable2,$li_widthtable2,$ls_nametable2,$lo_title2;
	 	$ls_nroreg="";
		$ls_fecha="";
		$ls_obs="";
		$ls_codcon="";
		$ls_descon="";
		$ls_tipcon="";
		$ls_fechaaper="";
		$ls_fechacie="";
		$ls_codcar="";
		$li_cantcar="";
		$ls_estatus="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Personal Interno a Asignar a Concurso";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Nombre y Apellido";
		$lo_title[3]="Cargo";
		$lo_title[4]="Unidad Administrativa";
		$lo_title[5]="Buscar";
		$lo_title[6]="Agregar";
		$lo_title[7]="Eliminar";
		$ls_titletable2="Personal Externo a Asignar a Concurso";
		$li_widthtable2=550;
		$ls_nametable2="grid2";
		$lo_title2[1]="Cédula";
		$lo_title2[2]="Nombre y Apellido";
		$lo_title2[3]="Profesión";
		$lo_title2[4]="Nivel de Selección";
		$lo_title2[5]="Buscar";
		$lo_title2[6]="Agregar";
		$lo_title2[7]="Eliminar";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$li_totrows2=$io_fun_nomina->uf_obtenervalor("totalfilas2",1);
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
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10>";
		$aa_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=35 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=40 readonly>";
		$aa_object[$ai_totrows][5]="<a href=javascript:catalogo_personal(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";			
   }
   //--------------------------------------------------------------


 function uf_agregarlineablanca2(&$aa_object2,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object2[$ai_totrows][1]="<input name=txtcedper".$ai_totrows." type=text id=txtcedper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10>";
		$aa_object2[$ai_totrows][2]="<input name=txtnomsol".$ai_totrows." type=text id=txtnomsol".$ai_totrows." class=sin-borde size=35 readonly>";
		$aa_object2[$ai_totrows][3]="<input name=txtdespro".$ai_totrows." type=text id=txtdespro".$ai_totrows." class=sin-borde  size=30 readonly>";
		$aa_object2[$ai_totrows][4]="<input name=txtdenniv".$ai_totrows." type=text id=txtdenniv".$ai_totrows." class=sin-borde  size=30 readonly>";
		$aa_object2[$ai_totrows][5]="<a href=javascript:catalogo_solicitud_empleo(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
		$aa_object2[$ai_totrows][6]="<a href=javascript:uf_agregar_dt2(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object2[$ai_totrows][7]="<a href=javascript:uf_delete_dt2(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";			
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_carper, $ls_dep;

		$ls_codper=$_POST["txtcodper".$li_i];
		$ls_nomper=$_POST["txtnomper".$li_i];
	    $ls_carper=$_POST["txtcarper".$li_i];
		$ls_dep=$_POST["txtdep".$li_i];
			
   }
   
    function uf_cargar_dt2($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_cedper,$ls_nomsol,$ls_despro, $ls_denniv;

		$ls_cedper=$_POST["txtcedper".$li_i];
		$ls_nomsol=$_POST["txtnomsol".$li_i];
	    $ls_despro=$_POST["txtdespro".$li_i];
		$ls_denniv=$_POST["txtdenniv".$li_i];
			
   }
   //--------------------------------------------------------------

?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>tepuy - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>



<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
.Estilo14 {color: #006699; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo20 {font-size: 10px}
.Estilo21 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo24 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/tepuy_srh_js_asignar_concurso.js"></script>



</head>

<body onLoad="javascript:ue_nuevo_codigo();">
<?php 
	require_once("../../../class_folder/dao/tepuy_srh_c_asignar_concurso.php");
	$io_eval=new tepuy_srh_c_asignar_concurso("../../../../");
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			$li_totrows2=1;
			uf_agregarlineablanca($lo_object,1);
			uf_agregarlineablanca2($lo_object2,1);
			break;

		case "AGREGAR_PERSONA_INTERNA":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$ls_tipcon=$_POST["txtcodtipconcur"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fechaaper=$_POST["txtfechaaper"];
			$ls_fechacie=$_POST["txtfechacie"];
			$ls_codcar=$_POST["txtcodcar"];
			$li_cantcar=$_POST["txtcantcar"];			
			//$ls_estatus=$_POST["comooestatus"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			
			
			for($li_i=1;$li_i<=$li_totrows2;$li_i++)
			{
				uf_cargar_dt2($li_i);
				
				$lo_object2[$li_i][1]="<input name=txtcedper".$li_i." type=text id=txtcedper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_cedper."' readonly >";
				$lo_object2[$li_i][2]="<input name=txtnomsol".$li_i." type=text id=txtnomsol".$li_i." class=sin-borde size=35 value='".$ls_nomsol."' readonly>";
				$lo_object2[$li_i][3]="<input name=txtdespro".$li_i." type=text id=txtdespro".$li_i." class=sin-borde  size=30 value='".$ls_despro."'>";
				$lo_object2[$li_i][4]="<input name=txtdenniv".$li_i." type=text id=txtdenniv".$li_i." class=sin-borde  size=30 value='".$ls_denniv."'>";
				$lo_object2[$li_i][5]="<a href=javascript:catalogo_solicitud_empleo(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object2[$li_i][6]="<a href=javascript:uf_agregar_dt2(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object2[$li_i][7]="<a href=javascript:uf_delete_dt2(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodper".$li_i." type=text id=txtcodper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_codper."' readonly >";
				$lo_object[$li_i][2]="<input name=txtnomper".$li_i." type=text id=txtnomper".$li_i." class=sin-borde size=35 value='".$ls_nomper."' readonly>";
				$lo_object[$li_i][3]="<input name=txtcarper".$li_i." type=text id=txtcarper".$li_i." class=sin-borde  size=20 value='".$ls_carper."'readonly>";
				$lo_object[$li_i][4]="<input name=txtdep".$li_i." type=text id=txtdep".$li_i." class=sin-borde  size=40 value='".$ls_dep."' readonly>";
				$lo_object[$li_i][5]="<a href=javascript:catalogo_personal(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "AGREGAR_PERSONA_EXTERNA":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$ls_tipcon=$_POST["txtcodtipconcur"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fechaaper=$_POST["txtfechaaper"];
			$ls_fechacie=$_POST["txtfechacie"];
			$ls_codcar=$_POST["txtcodcar"];
			$li_cantcar=$_POST["txtcantcar"];			
			//$ls_estatus=$_POST["comooestatus"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodper".$li_i." type=text id=txtcodper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_codper."' readonly >";
				$lo_object[$li_i][2]="<input name=txtnomper".$li_i." type=text id=txtnomper".$li_i." class=sin-borde size=35 value='".$ls_nomper."' readonly>";
				$lo_object[$li_i][3]="<input name=txtcarper".$li_i." type=text id=txtcarper".$li_i." class=sin-borde  size=20 value='".$ls_carper."' readonly>";
				$lo_object[$li_i][4]="<input name=txtdep".$li_i." type=text id=txtdep".$li_i." class=sin-borde  size=40 value='".$ls_dep."' readonly>";
				$lo_object[$li_i][5]="<a href=javascript:catalogo_personal(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			
			$li_totrows2=$li_totrows2+1;			
			for($li_i=1;$li_i<$li_totrows2;$li_i++)
			{
				uf_cargar_dt2($li_i);
				
				$lo_object2[$li_i][1]="<input name=txtcedper".$li_i." type=text id=txtcedper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_cedper."' readonly >";
				$lo_object2[$li_i][2]="<input name=txtnomsol".$li_i." type=text id=txtnomsol".$li_i." class=sin-borde size=35 value='".$ls_nomsol."' readonly>";
				$lo_object2[$li_i][3]="<input name=txtdespro".$li_i." type=text id=txtdespro".$li_i." class=sin-borde  size=30 value='".$ls_despro."'>";
				$lo_object2[$li_i][4]="<input name=txtdenniv".$li_i." type=text id=txtdenniv".$li_i." class=sin-borde  size=30 value='".$ls_denniv."'>";
				$lo_object2[$li_i][5]="<a href=javascript:catalogo_solicitud_empleo(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object2[$li_i][6]="<a href=javascript:uf_agregar_dt2(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object2[$li_i][7]="<a href=javascript:uf_delete_dt2(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			uf_agregarlineablanca2($lo_object2,$li_totrows2);
			break;


		case "ELIMINAR_PERSONA_INTERNA":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$ls_tipcon=$_POST["txtcodtipconcur"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fechaaper=$_POST["txtfechaaper"];
			$ls_fechacie=$_POST["txtfechacie"];
			$ls_codcar=$_POST["txtcodcar"];
			$li_cantcar=$_POST["txtcantcar"];			
			//$ls_estatus=$_POST["comooestatus"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
				    $lo_object[$li_temp][1]="<input name=txtcodper".$li_temp." type=text id=txtcodper".$li_temp." class=sin-borde size=15 maxlength=10 readonly value='".$ls_codper."'>";
					$lo_object[$li_temp][2]="<input name=txtnomper".$li_temp." type=text id=txtnomper".$li_temp." class=sin-borde size=35 readonly value='".$ls_nomper."'>";
				    $lo_object[$li_temp][3]="<input name=txtcarper".$li_temp." type=text id=txtcarper".$li_temp." class=sin-borde size=20 value='".$ls_carper."'>";
				    $lo_object[$li_temp][4]="<input name=txtdep".$li_temp." type=text id=txtdep".$li_temp." class=sin-borde  size=40 value='".$ls_dep."' readonly>";
					$lo_object[$li_temp][5]="<a href=javascript:catalogo_personal(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				    $lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
		            $lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			
			for($li_i=1;$li_i<=$li_totrows2;$li_i++)
			{
				uf_cargar_dt2($li_i);
				
				$lo_object2[$li_i][1]="<input name=txtcedper".$li_i." type=text id=txtcedper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_cedper."' readonly >";
				$lo_object2[$li_i][2]="<input name=txtnomsol".$li_i." type=text id=txtnomsol".$li_i." class=sin-borde size=35 value='".$ls_nomsol."' readonly>";
				$lo_object2[$li_i][3]="<input name=txtdespro".$li_i." type=text id=txtdespro".$li_i." class=sin-borde  size=30 value='".$ls_despro."'>";
				$lo_object2[$li_i][4]="<input name=txtdenniv".$li_i." type=text id=txtdenniv".$li_i." class=sin-borde  size=30 value='".$ls_denniv."'>";
				$lo_object2[$li_i][5]="<a href=javascript:catalogo_solicitud_empleo(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object2[$li_i][6]="<a href=javascript:uf_agregar_dt2(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object2[$li_i][7]="<a href=javascript:uf_delete_dt2(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			
			break;
			
		case "ELIMINAR_PERSONA_EXTERNA":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$ls_tipcon=$_POST["txtcodtipconcur"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fechaaper=$_POST["txtfechaaper"];
			$ls_fechacie=$_POST["txtfechacie"];
			$ls_codcar=$_POST["txtcodcar"];
			$li_cantcar=$_POST["txtcantcar"];			
			//$ls_estatus=$_POST["comooestatus"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows2=$li_totrows2-1;
			$li_rowdelete2=$_POST["filadelete2"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete2))
				{		
					$li_temp++;			
					uf_cargar_dt2($li_i);
				    $lo_object2[$li_temp][1]="<input name=txtcedper".$li_temp." type=text id=txtcedper".$li_temp." class=sin-borde size=15 maxlength=10 readonly value='".$ls_cedper."'>";
					$lo_object2[$li_temp][2]="<input name=txtnomsol".$li_temp." type=text id=txtnomsol".$li_temp." class=sin-borde size=35 readonly value='".$ls_nomsol."'>";
				    $lo_object2[$li_temp][3]="<input name=txtdespro".$li_temp." type=text id=txtdespro".$li_temp." class=sin-borde size=30 value='".$ls_despro."'>";
				    $lo_object2[$li_temp][4]="<input name=txtdenniv".$li_temp." type=text id=txtdenniv".$li_temp." class=sin-borde  size=30 value='".$ls_denniv."'>";
					$lo_object2[$li_temp][5]="<a href=javascript:catalogo_solicutd_empleo(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				    $lo_object2[$li_temp][6]="<a href=javascript:uf_agregar_dt2(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
		            $lo_object2[$li_temp][7]="<a href=javascript:uf_delete_dt2(".$li_temp.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
				}
			}
			uf_agregarlineablanca2($lo_object2,$li_totrows2);
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodper".$li_i." type=text id=txtcodper".$li_i." class=sin-borde size=15  maxlength=10 value='".$ls_codper."' readonly >";
				$lo_object[$li_i][2]="<input name=txtnomper".$li_i." type=text id=txtnomper".$li_i." class=sin-borde size=35 value='".$ls_nomper."' readonly>";
				$lo_object[$li_i][3]="<input name=txtcarper".$li_i." type=text id=txtcarper".$li_i." class=sin-borde  size=20 value='".$ls_carper."'>";
				$lo_object[$li_i][4]="<input name=txtdep".$li_i." type=text id=txtdep".$li_i." class=sin-borde  size=40 value='".$ls_dep."'>";
				$lo_object[$li_i][5]="<a href=javascript:catalogo_personal(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			
			break;
			
		case "BUSCARDETALLE":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$ls_tipcon=$_POST["txtcodtipconcur"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fechaaper=$_POST["txtfechaaper"];
			$ls_fechacie=$_POST["txtfechacie"];
			$ls_codcar=$_POST["txtcodcar"];
			$li_cantcar=$_POST["txtcantcar"];			
			//$ls_estatus=$_POST["comooestatus"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$hay1 = false;
			$hay2 = false;
			$lb_valido=$io_eval->uf_srh_load_asignar_concurso_campos($ls_nroreg,$li_totrows,$lo_object,$hay1);
			if ($hay1) {
			  $li_totrows++;
			  uf_agregarlineablanca($lo_object,$li_totrows); }
			$lb_valido=$io_eval->uf_srh_load_asignar_concurso_campos2($ls_nroreg,$li_totrows2,$lo_object2,$hay2);
			if ($hay2)  {
			 $li_totrows2++;
			 uf_agregarlineablanca2($lo_object2,$li_totrows2); }
			break;
	}
	
	unset($io_eval);
?>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>

  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.png" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.png" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.png" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}
?>


<form name="form1" method="post" action=""  >
<div align="center"></div>
      <p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
      <table width="749" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="700" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
     
      <table width="817" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Asignaci&oacute;n de Personas a Concurso</td>
        </tr>
		 <tr class="titulo-celda">
		          <td height="22" colspan="9">Informaci&oacute;n del Concurso</td>
        <tr>
        <tr>
          <td width="81" height="22">&nbsp;</td>
          
          <td height="22" colspan="6">&nbsp;</td>
        </tr>
         <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td height="29" colspan="3"><input name="txtcodcon" type="text" id="txtcodcon" size="11" maxlength="10"  value="<?php print $ls_codcon?>" readonly style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus"> <a href="javascript:catalogo_concurso();"> <img src="../../../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Concurso</a>  </td>
    <td width="139"  class="sin-borde"><div id="existe" class="letras-pequeÃ±as" style="display:none"> 
		</div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Descrpci&oacute;n</div></td>
    <td height="28" colspan="4"><input name="txtdescon" type="text" id="txtdescon"  onKeyUp="ue_validarcomillas(this);" size="80" maxlength="254"  value="<?php print $ls_descon?>" readonly></td>
  </tr>
  
   <tr class="formato-blanco"> 
 <td height="28"><div align="right">Tipo</div></td>
  <td height="28" valign="middle"><input name="txtcodtipconcur" type="text" id="txtcodtipconcur"  size="16" maxlength="20"  value="<?php print $ls_tipcon?>" style="text-align:center"  readonly></td>
         
  </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Apertura</div></td>
  <td height="28" valign="middle"><input name="txtfechaaper" type="text" id="txtfechaaper"  value="<?php print $ls_fechaaper ?>"   size="16"   style="text-align:center" readonly > </td>
         
  </tr>
  
   <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Cierre</div></td>
  <td height="28" valign="middle"> <input name="txtfechacie" type="text" id="txtfechacie" size="16"    style="text-align:center" value="<?php print $ls_fechacie ?>" readonly >  </td>           
  </tr>
  
  <tr class="formato-blanco">
    <td height="28"><div align="right">Cargo</div></td>
    <td height="28" valign="middle"><input name="txtcodcar" type="text" id="txtcodcar"  size="16" maxlength="10"  style="text-align:center"  value="<?php print $ls_codcar ?>" readonly>      </td>
            <td colspan="2"> <input name="txtdescar" type="text" class="sin-borde" id="txtdescar"  size="60" maxlength="80"  readonly></td>
  </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Cantidad Cargos</div></td>
  <td height="28" valign="middle"><input name="txtcantcar" type="text" id="txtcantcar"  size="6" maxlength="5" value="<?php print $li_cantcar ?>" style="text-align:center"  readonly></td>
         
  </tr>
  
  
		
		  <tr>
		  <td width="81" height="22">&nbsp;</td>
          <td height="22" colspan="6">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Detalles de la Asignaci&oacute;n</td>
        </tr>
        <tr>
          <td width="81" height="22">&nbsp;</td>
          <td height="22" colspan="6">&nbsp;</td>
		  </tr>
		    <td height="29"><div align="right">Nro. Registro</div></td>
    <td width="107" height="29"><input name="txtnroreg" type="text" id="txtnroreg"  size="11" maxlength="10"  readonly 
	      value="<?php print $ls_nroreg?>" style="text-align:center "> </td>
	</tr>
		  <tr>
		 <td height="22"><div align="right">Fecha Asignaci&oacute;n</div></td>
          <td height="22" colspan="6"><input name="txtfecha" type="text" id="txtfecha" size="16"  style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		
		  <tr>
		  <td height="22" align="left"><div align="right">Observaci&oacute;n </div></td>
	  <td height="22" colspan="6"><textarea name="txtobs" cols="86" rows="5" id="txtobs"   onKeyUp= "ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_obs;?></textarea>            </td>
	      </tr>
		 
		<tr>
          <td height="22" >&nbsp;</td>
        </tr>
		<tr >
          <td><div align="right"></div></td>
          <td>
		   <input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td colspan="8">
		  	<div align="center">
			<?php
			        require_once("../../../../shared/class_folder/grid_param.php");
					$io_grid=new grid_param();
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
			    <p>&nbsp;</p>
			<div align="center">
			<?php
			        require_once("../../../../shared/class_folder/grid_param.php");
					$io_grid=new grid_param();
					$io_grid->makegrid($li_totrows2,$lo_title2,$lo_object2,$li_widthtable2,$ls_titletable2,$ls_nametable2);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			  <input name="totalfilas2" type="hidden" id="totalfilas2" value="<?php print $li_totrows2;?>">
              <input name="filadelete2" type="hidden" id="filadelete2">
			  <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
		  	</p>			</td>		  
          </tr>
      </table>	 
     
   <p>&nbsp;</p>
     
 </td> 
</table>

   <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="2">
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
	<input name="hidcontrol3" type="hidden" id="hidcontrol3" value="">
	 
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">

  </p>

</form>


</body>


</html>


