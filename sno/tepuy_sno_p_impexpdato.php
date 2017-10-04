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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","tepuy_sno_p_impexpdato.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom, $ls_peractnom, $ld_fecdesper, $ld_fechasper, $ls_concsue1, $ls_concsue2, $ls_concsue3, $ls_concsue4;
		global $ls_conccaj1, $ls_conccaj2, $ls_conccaj3, $ls_conccaj4, $ls_concpreper, $ls_concpreesp, $ls_concmontepio;
		global $ls_concfianza, $ls_concprehip, $ls_operacion, $ls_accion,  $io_fun_nomina, $ls_desper, $li_calculada,$lb_mostrargrid;
		global $ls_codarch,$ls_denarch,$li_totrow;
		
		require_once("tepuy_sno.php");
		$io_sno=new tepuy_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		$ls_codarch="";
		$ls_denarch="";
		$li_totrow=0;
		$lb_mostrargrid=false;
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='tepuywindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_concsue1="";
		$ls_concsue2="";
		$ls_concsue3="";
		$ls_concsue4="";
		$ls_conccaj1="";
		$ls_conccaj2="";
		$ls_conccaj3="";
		$ls_conccaj4="";
		$ls_concpreper="";
		$ls_concpreesp="";
		$ls_concmontepio="";
		$ls_concfianza="";
		$ls_concprehip="";
		$ls_accion="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
		require_once("tepuy_sno_c_calcularnomina.php");
		$io_calcularnomina=new tepuy_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
<title >Importar/Exportar Datos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	require_once("tepuy_sno_c_impexpdato.php");
	$io_impexpdato=new tepuy_sno_c_impexpdato();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ls_accion=$_POST["accion"];
			switch($ls_accion)
			{
				case "1":// Importar Datos
					$ls_titletable="Campos";
					$li_widthtable=500;
					$ls_nametable="grid";
					$lo_title[1]="Código";
					$lo_campos[1][1]="";
					$ls_arctxt=$HTTP_POST_FILES["txtarctxt"]["tmp_name"]; 
					$ls_tiparctxt=$HTTP_POST_FILES["txtarctxt"]["type"]; 
					$ls_codarch=$_POST["txtcodarch"];
					$ls_denarch=$_POST["txtdenarch"];
					if($ls_tiparctxt=="text/plain")
					{
						$lb_valido=$io_impexpdato->uf_importardatos($ls_arctxt,$ls_codarch,&$lo_title,&$lo_campos,&$li_totrow,$la_seguridad);
						$lb_mostrargrid=true;
					}
					else
					{
						$io_impexpdato->io_mensajes->message("Tipo de archivo inválido. Solo se permiten archivos TXT.");
					}
					break;
					
				case "2":// Exportar Datos
					/*$ls_concsue1=$_POST["txtconcsue1"];
					$ls_concsue2=$_POST["txtconcsue2"];
					$ls_concsue3=$_POST["txtconcsue3"];
					$ls_concsue4=$_POST["txtconcsue4"];
					$ls_conccaj1=$_POST["txtconccaj1"];
					$ls_conccaj2=$_POST["txtconccaj2"];
					$ls_conccaj3=$_POST["txtconccaj3"];
					$ls_conccaj4=$_POST["txtconccaj4"];
					$ls_concpreper=$_POST["txtconcpreper"];
					$ls_concpreesp=$_POST["txtconcpreesp"];
					$ls_concmontepio=$_POST["txtconcmontepio"];
					$ls_concfianza=$_POST["txtconcfianza"];
					$ls_concprehip=$_POST["txtconcprehip"];
					$lb_valido=$io_impexpdato->uf_exportardatos($ls_concsue1,$ls_concsue2,$ls_concsue3,$ls_concsue4,$ls_conccaj1,
																$ls_conccaj2,$ls_conccaj3,$ls_conccaj4,$ls_concpreper,$ls_concpreesp,
																$ls_concmontepio,$ls_concfianza,$ls_concprehip,$la_seguridad);*/
					break;
			}
			break;

		case "GUARDAR":
			$ls_accion=$_POST["accion"];
			switch($ls_accion)
			{
				case "1":// Importar Datos
					$ls_codarch=$_POST["txtcodarch"];
					$ls_denarch=$_POST["txtdenarch"];
					$ls_codcons=$_POST["txtcodcons"];
					$li_totrow=$_POST["totrow"];
					$lb_valido=$io_impexpdato->uf_procesarimportardatos($ls_codarch,$ls_codcons,$li_totrow,$la_seguridad);
					break;
					
				case "2":// Exportar Datos
					break;
			}
			break;
		
		case "CARGARDATOS":
			$ls_accion=$_POST["accion"];
			break;
	}
	$io_impexpdato->uf_destructor();
	unset($io_impexpdato);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?PHP print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?PHP print $ls_desper;?></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	  </table>	</td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu_nomina.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr> -->
  <tr>
    <td width="780" height="42" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.png" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" enctype="multipart/form-data" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="750" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Importar/Exportar Datos</td>
        </tr>
        <tr>
          <td width="135" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="50"><input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>          </td>
          <td width="70"><div align="right">Fecha Inicio</div></td>
          <td width="83"><div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
          </div></td>
          <td width="65"><div align="right">Fecha Fin </div></td>
          <td width="333"><div align="left">
              <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Acci&oacute;n a Realizar </div></td>
          <td colspan="5"><div align="left">
            <input name="rdbaccion" type="radio" class="sin-borde" onClick="javascript: ue_cargardatos();" value="1" <?PHP if($ls_accion=="1"){ print "checked";}?>>
            Importar 
            <input name="rdbaccion" type="radio" class="sin-borde" onClick="javascript: ue_cargardatos();" value="2" <?PHP if($ls_accion=="2"){ print "checked";}?>>
            Exportar</div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
<?PHP 	if($ls_accion=="1")
		{ 
?>		
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Archivo a Importar</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Archivo TXT </div></td>
          <td colspan="5"><div align="left">
            <input name="txtarctxt" type="file" id="txtarctxt" size="50" maxlength="200">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Archivo </div></td>
          <td colspan="5"><div align="left">
            <input name="txtcodarch" type="text" size="6" maxlength="4" value="<? print $ls_codarch; ?>" readonly>
            <a href="javascript: ue_buscararchivo();"><img id="archivo" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenarch" type="text" class="sin-borde" id="txtdenarch" size="60" maxlength="120" value="<? print $ls_denarch; ?>" readonly>
          </div></td>
        </tr>
		  <?php if($lb_mostrargrid)
		  		{
		   ?>
        <tr>
          <td height="22" align="right">Constante</td>
          <td colspan="5"><input name="txtcodcons" type="text" id="txtcodcons" size="15" maxlength="10" readonly>
            <a href="javascript: ue_buscarconstante();"><img id="contante" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a>
			</td>
        </tr>
        <tr>
          <td height="22" colspan="6" align="center">
		  <?php 	
		  	$io_grid->makegrid($li_totrow,$lo_title,$lo_campos,$li_widthtable,$ls_titletable,$ls_nametable);
			unset($io_grid);
		   ?>
		   </td>
          </tr>
		  <?php
		  		}
		   ?>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
<?PHP 	}
		if($ls_accion=="2")
		{
?>		
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Conceptos</td>
        </tr>
        <tr>
          <td height="22" colspan="6"><table width="600" border="0" align="center" cellpadding="1" cellspacing="0">
            <tr>
              <td width="127" height="22"><div align="right">Sueldos</div></td>
              <td width="169"><div align="left">
                <input name="txtconcsue1" type="text" id="txtconcsue1" value="<?php print $ls_concsue1;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcsue1');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td width="148"><div align="right">Aporte Caja de Ahorro </div></td>
              <td width="148"><div align="left">
                <input name="txtconccaj1" type="text" id="txtconccaj1" value="<?php print $ls_conccaj1;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconccaj1');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Sueldos</div></td>
              <td><div align="left">
                <input name="txtconcsue2" type="text" id="txtconcsue2" value="<?php print $ls_concsue2;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcsue2');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right">Aporte Caja de Ahorro</div></td>
              <td><div align="left">
                <input name="txtconccaj2" type="text" id="txtconccaj2" value="<?php print $ls_conccaj2;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconccaj2');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Sueldos</div></td>
              <td><div align="left">
                <input name="txtconcsue3" type="text" id="txtconcsue3" value="<?php print $ls_concsue3;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcsue3');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right">Aporte Caja de Ahorro</div></td>
              <td><div align="left">
                <input name="txtconccaj3" type="text" id="txtconccaj3" value="<?php print $ls_conccaj3;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconccaj3');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Sueldos</div></td>
              <td><div align="left">
                <input name="txtconcsue4" type="text" id="txtconcsue4" value="<?php print $ls_concsue4;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcsue4');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right">Aporte Caja de Ahorro</div></td>
              <td><div align="left">
                <input name="txtconccaj4" type="text" id="txtconccaj4" value="<?php print $ls_conccaj4;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconccaj4');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Prestamos Personales </div></td>
              <td><div align="left">
                <input name="txtconcpreper" type="text" id="txtconcpreper" value="<?php print $ls_concpreper;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcpreper');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right">Prestamos Especiales </div></td>
              <td><div align="left">
                <input name="txtconcpreesp" type="text" id="txtconcpreesp" value="<?php print $ls_concpreesp;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcpreesp');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Montepio</div></td>
              <td><div align="left">
                <input name="txtconcmontepio" type="text" id="txtconcmontepio" value="<?php print $ls_concmontepio;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcmontepio');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right">Fianza</div></td>
              <td><div align="left">
                <input name="txtconcfianza" type="text" id="txtconcfianza" value="<?php print $ls_concfianza;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcfianza');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Prestamos Hipotecarios </div></td>
              <td><div align="left">
                <input name="txtconcprehip" type="text" id="txtconcprehip" value="<?php print $ls_concprehip;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarconcepto('txtconcprehip');"><img id="concepto" src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              <td><div align="right"></div></td>
              <td><div align="left"></div></td>
            </tr>
<?PHP 	}?>		
          </table>		  </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><input name="operacion" type="hidden" id="operacion"><input name="accion" type="hidden" id="accion">
		  <input name="totrow" type="hidden" id="totrow" value="<?php print $li_totrow;?>">
		  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">            </td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cargardatos()
{
	f=document.form1;
	f.accion.value="";
	if(f.rdbaccion[0].checked)// Importar Datos
	{
		f.accion.value="1";
		f.operacion.value="CARGARDATOS";
		f.action="tepuy_sno_p_impexpdato.php";
		f.submit();
	}
	if(f.rdbaccion[1].checked)// Exportar Datos
	{
		f.accion.value="2";
	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		f.accion.value="";
		if(f.rdbaccion[0].checked)// Importar Datos
		{
			f.accion.value="1";
		}
		if(f.rdbaccion[1].checked)// Exportar Datos
		{
			f.accion.value="2";
		}
		if(f.accion.value!="")
		{
			if(f.accion.value=="1")
			{
				arctxt=ue_validarvacio(f.txtarctxt.value);
				codarch=ue_validarvacio(f.txtcodarch.value);
				if((arctxt!="")&&(codarch!=""))
				{
					f.operacion.value="PROCESAR";
					f.action="tepuy_sno_p_impexpdato.php";
					f.submit();			
				}
				else
				{
					alert("Debe seleccionar el archivo a Importar.");
				}
			}
			/*if(f.accion.value=="2")
			{
				concsue1 = ue_validarvacio(f.txtconcsue1.value);
				concsue2 = ue_validarvacio(f.txtconcsue2.value);
				concsue3 = ue_validarvacio(f.txtconcsue3.value);
				concsue4 = ue_validarvacio(f.txtconcsue4.value);
				conccaj1 = ue_validarvacio(f.txtconccaj1.value);
				conccaj2 = ue_validarvacio(f.txtconccaj2.value);
				conccaj3 = ue_validarvacio(f.txtconccaj3.value);
				conccaj4 = ue_validarvacio(f.txtconccaj4.value);
				concpreper = ue_validarvacio(f.txtconcpreper.value);
				concpreesp = ue_validarvacio(f.txtconcpreesp.value);
				concmontepio = ue_validarvacio(f.txtconcmontepio.value);
				concfianza = ue_validarvacio(f.txtconcfianza.value);
				concprehip = ue_validarvacio(f.txtconcprehip.value);
				if ((concsue1!="")||(concsue2!="")||(concsue3!="")||(concsue4!="")|| (conccaj1!="")||(conccaj2!="")|| (conccaj3!="")||
					(conccaj4!="")||(concpreper!="")||(concpreesp!="")||(concmontepio!="")|| (concfianza!="")||(concprehip!=""))
				{
					f.operacion.value="PROCESAR";
					f.action="tepuy_sno_p_impexpdato.php";
					f.submit();
				}
				else
				{
					alert("Debe seleccionar al menos un Concepto.");
				}
			}*/
		}
		else
		{
			alert("Debe seleccionar una acción.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_guardar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		f.accion.value="";
		if(f.rdbaccion[0].checked)// Importar Datos
		{
			f.accion.value="1";
		}
		if(f.rdbaccion[1].checked)// Exportar Datos
		{
			f.accion.value="2";
		}
		if(f.accion.value!="")
		{
			if(f.accion.value=="1")
			{
				totrow=ue_validarvacio(f.totrow.value);
				codcons=ue_validarvacio(f.txtcodcons.value);
				if((codcons!="")&&(totrow>0))
				{
					f.operacion.value="GUARDAR";
					f.action="tepuy_sno_p_impexpdato.php";
					f.submit();			
				}
				else
				{
					alert("Debe seleccionar el archivo a Importar.");
				}
			}
		}
		else
		{
			alert("Debe seleccionar una acción.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cerrar()
{
	location.href = "tepuywindow_blank_nomina.php";
}

function ue_buscarconcepto(campo)
{
	window.open("tepuy_sno_cat_concepto.php?tipo=IMPORTAR&campo="+campo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscararchivo()
{
	window.open("tepuy_snorh_cat_archivotxt.php?tipo=IMPORTAR","Archivos","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarconstante()
{
	window.open("tepuy_sno_cat_constantes.php?tipo=IMPORTAR","Archivos","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>
