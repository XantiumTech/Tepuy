<?
	session_start();
	require_once("../../class_folder/dao/tepuy_srh_c_asignar_concurso.php");
	require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_asignacion= new tepuy_srh_c_asignar_concurso('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","tepuy_srh_p_asignar_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";
	
	
if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nroreg="%%";
			$ls_codcon="%%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
		    header('Content-type:text/xml');
			print $io_asignacion->uf_srh_buscar_asignar_concurso($ls_nroreg,$ls_fecha1, $ls_fecha2,$ls_codcon);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
			header('Content-type:text/xml');			
			print $io_asignacion->uf_srh_buscar_asignar_concurso($ls_nroreg,$ls_fecha1, $ls_fecha2,$ls_codcon);
		}
		
		elseif($evento=="createXML_Persona_Concurso")
		{
			$ls_codper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_hidcodcon=$_REQUEST['hidcodcon'];			 
			
		    header('Content-type:text/xml');			
			print $io_asignacion->uf_srh_buscar_personal_concurso($ls_codper,$ls_apeper,$ls_nomper,$ls_hidcodcon);
		}
		
		elseif($evento=="buscar_Persona_Concurso")
		{
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_hidcodcon=$_REQUEST['hidcodcon'];
					
			header('Content-type:text/xml');			
			print $io_asignacion->uf_srh_buscar_personal_concurso($ls_codper,$ls_apeper,$ls_nomper,$ls_hidcodcon);
		}
			
}



require_once("../../class_folder/utilidades/JSON.php");	
$io_json = new JSON();	


if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
elseif (array_key_exists("operacion",$_POST))
{
  $ls_operacion = $_POST["operacion"];
}
else
{
	$ls_operacion = "";
}


if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_sol = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_asignacion->uf_srh_guardarasignar_concurso($io_sol,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Asignacion de Personas a Concurso fue Actualizada';	}
	else { $ls_salida = 'La Asignacion de Personas a Concurso fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Asignacion de Personas a Concurso';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_asignacion->uf_srh_eliminarasignar_concurso($_GET["nroreg"], $la_seguridad);
  $ls_salida = 'La Asignacion de Personas a Concurso fue Eliminada';
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{    
    $ls_salida = $io_asignacion->uf_srh_getProximoCodigo();  
}


echo utf8_encode($ls_salida);


?>
