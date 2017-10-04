<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../tepuy_inicio_sesion.php'";
	 print "</script>";		
   }
// validación de los release necesarios para que funcione la definicion de tepuy_empresa.
require_once("../shared/class_folder/tepuy_release.php");
$io_release= new tepuy_release();
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','modageret');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   }
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','nomres');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','concomiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','scctaben');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','estmodiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','activo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','pasivo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','resultado_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','c_financiera');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','c_fiscal');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
  

$lb_valido=true;

if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','codasiona');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
   
	$lb_valido=true;
	if ($lb_valido)
	{
		 $lb_valido=$io_release->io_function_db->uf_select_column('soc_tiposervicio','codmil');	
		 if ($lb_valido==false)
		 {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
		 }
	}
	

	if ($_SESSION["la_empresa"]["estmodest"]=='1')
	{
		$lb_valido=true;
		if ($lb_valido)
		{
				 $lb_valido=$io_release->io_function_db->uf_select_column('spg_ep3','codfuefin');;	
				 if ($lb_valido==false)
				 {
					  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
					  print "<script language=JavaScript>";
					  print "location.href='../index_modules.php'";
					  print "</script>";		
				 }
		}
	
	}else
	{
		$lb_valido=true;
		if ($lb_valido)
		{
			 $lb_valido=$io_release->io_function_db->uf_select_column('spg_ep5','codfuefin');
			 if ($lb_valido==false)
			 {
				  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD");
				  print "<script language=JavaScript>";
				  print "location.href='../index_modules.php'";
				  print "</script>";		
			 }
		}
	
	}
	$lb_valido=true;
	if ($lb_valido)
    {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','diacadche');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.31");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
    } 
    $lb_valido=true;
	if ($lb_valido)
    {
	 $lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','confi_ch');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release tepuy BD 3.34");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
    } 
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','confiva');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.47");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('tepuy_unidad_tributaria');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.54");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','nroivss');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_52");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','nomrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_53");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','cedrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_54");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','telfrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_55");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','cargorep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_73");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_banco','codsudeban');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_89");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_deducciones','tipopers');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_90");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','estretiva');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_94");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
  $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('cxp_documento','tipodocanti');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_95");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
 $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_empresa','confinstr');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_10");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('tepuy_deducciones','retaposol');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_51");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	
	}	
	unset($io_release);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
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
<head>
<title>tepuy - Módulo de Configuración</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<frameset rows="*" cols="166,*" framespacing="0" frameborder="NO" border="0">
	  <frame src="left.php" name="leftFrame" scrolling="YES" noresize>
	  <frame src="main.php" name="mainFrame">
  </frameset>
<noframes><body>
</body>
</noframes>
</html>