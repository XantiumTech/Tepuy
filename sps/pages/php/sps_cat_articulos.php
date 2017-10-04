<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_def_articulos_dao.php");
require_once("../../../shared/class_folder/class_funciones.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_def_articulos_dao();
$io_function = new class_funciones();

$ls_salida    = "";

if ($ls_operacion=="ue_inicializar")
{
  $lb_hay = $lo_dao->getArticulos("ORDER BY id_art ASC",$la_datos);
  
  if (!$lb_hay)
  {
    $ls_salida  ='&<table width="600" class="fondo-tabla" cellpadding="1" cellspacing="1">';
    $ls_salida .='<tr class="celdas-letras-rojas">';
    $ls_salida .='<td align="center" colspan="2">No se han Registrado Datos</td>';
    $ls_salida .='</tr>';
    $ls_salida .='</table>';
  }
  else
  {
	$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
	$ls_salida    = "$li_registros&"; 
    $ls_salida   .= '<table id="data_grid" width="600" class="fondo-tabla" cellpadding="1" cellspacing="1">';
	 
	for($i=1; $i<=$li_registros; $i++)
	{
	  $ls_id_art  = $la_datos["id_art"][$i];
	  $ls_numart  = $la_datos["numart"][$i];
      $ls_fecvig  = $io_function->uf_convertirfecmostrar($la_datos["fecvig"][$i]);
	  $ls_conart  = $la_datos["conart"][$i];
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100">'.$ls_id_art.'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_numart.'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_fecvig.'</td>';
	  $ls_salida .='<td align="center" width="200">'.$ls_conart.'</td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida.= '</table>';
  }
}
else
{
  //Asignamos las variables para el ordenamiento
  $pagesize = 20; // Registros a traerse de la bd
  if (array_key_exists('pagesize',$_GET))
  {$pagesize = $_GET['pagesize'];}
  $offset = 0; // Primer Registro a mostrar
  if (array_key_exists('offset',$_GET))
  {$offset = $_GET['offset'];}
  $sort_col = 'c�digo'; //Columna en la tabla HTML 
  if (array_key_exists('sort_col',$_GET))
  {$sort_col = $_GET['sort_col'];}
  
  // Asiganamos el campo para el ordenamiento
  // Hay que estar pendiente de comparalo en minusculas y con los acentos si lleva
  switch($sort_col)
  {
    case("c�digo"):
      $sort_col="id_art";
      break;
    case("articulo"):
      $sort_col="numart";
      break;
	case("fecha vig."):
      $sort_col="fecvig";
      break;
	case("concepto"):
      $sort_col="conart";
      break;
    default : $sort_col="id_art";
  }  

  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {
  	$sort_dir = $_GET['sort_dir'];
  }  
  //Hacemos la peticion de los registros
  $lb_hay = $lo_dao->getArticulos("ORDER BY $sort_col $sort_dir LIMIT $offset,$pagesize",$la_datos);
  if ($lb_hay)
  {
    $li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;  
    //Indicamos en la cabecera que devolveremos un documento xml
    header("Content-Type: text/xml");	
	
	//Escribimos el xml con el formato requerido por rico
	$ls_salida  = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
	$ls_salida .= '<ajax-response><response type="object" id="data_grid_updater"><rows update_ui="true">';  
    for ($i=1; $i<=$li_registros; $i++)
    {
      $li_i = 1;
	  $la_arr = array();
	  $la_arr[$li_i++] = $ls_id_art = $la_datos["id_art"][$i];
      $la_arr[$li_i++] = $ls_numart = $la_datos["numart"][$i];
      $la_arr[$li_i++] = $ls_fecvig = $io_function->uf_convertirfecmostrar($la_datos["fecvig"][$i]); 
	  $la_arr[$li_i++] = $ls_conart = $la_datos["conart"][$i];
      
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_id_art.'</a></td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>