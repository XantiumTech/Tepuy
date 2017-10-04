<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("X-LIGHTTPD-SID: ".session_id()); 
   	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  
 
	//--------------Declaraciones e Inicializaciones-----------------------//
	require_once("tepuy_scb_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_tepuy_int.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_tepuy_int_scg.php");
	require_once("../../shared/class_folder/class_tepuy_int_spg.php");
	
	$int_spg	  =	new class_tepuy_int_spg();
	$in			  = new tepuy_include();
	$con		  = $in->uf_conectar();
	$io_sql		  = new class_sql($con);	
	$io_report    = new tepuy_scb_report($con);
	$io_funciones = new class_funciones();				
	$io_msg       = new class_mensajes();
	$io_fecha     = new class_fecha();
	$io_pdf       = new class_pdf("LETTER","landscape");
	
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm');
	$io_pdf->numerar_paginas(10);
	$io_pdf->set_margenes(8,20,12,12);
	//-------------------------------------------------//
	
	//------------Data-----------------// 
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
	$ls_nitemp=$_SESSION["la_empresa"]["nitemp"];
	if(array_key_exists("numlicemp",$_SESSION["la_empresa"]))
		$ls_licemp=$_SESSION["la_empresa"]["numlicemp"];
	else
		$ls_licemp="";
	$ls_diremp		 = $_SESSION["la_empresa"]["direccion"];
	$ls_codcom		 = $_GET["documentos"];
	$ls_mes			 = $_GET["mes"];
	$ls_agno		 = $_GET["agno"];
	$la_comprobantes = explode("-",$ls_codcom);
	$ls_fechainicio  = $ls_agno."-".$ls_mes."-01";
	$ls_fechafin     = $io_funciones->uf_convertirdatetobd($io_fecha->uf_last_day($ls_mes,$ls_agno));		
	$ls_tipbol       = 'Bs.';
	$ls_tiporeporte  = 0;
	$ls_tiporeporte  = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_scb_reportbsf.php");
		$io_report = new tepuy_scb_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	
	//------------------------------------------------//
for($li_k=0;$li_k<count($la_comprobantes);$li_k++)
{

//--------------------------Data de cada uno de los comprobantes----------------------------------------//
$la_datacomprobantes=array();
$la_datacomprobantes=$io_report->uf_comprobante_retencion($la_comprobantes[$li_k],$ls_fechainicio,$ls_fechafin);
//------------------------------------------------------------------------------------------------------//

 
 //-------------Encabezado----------------------//
 $li_ancho=$_SESSION["ls_width"];
 $io_pdf->convertir_valor_px_mm($li_ancho);
 $io_pdf->add_imagen('../../shared/imagebank/'.$_SESSION["ls_logo"],0,0,$li_ancho);
 //$io_pdf->add_texto($li_ancho+3,4,14,"<b>COMPROBANTE DE RETENCION DEL IMPUESTO MUNICIPAL $ls_tipbol</b>");
 $io_pdf->add_texto($li_ancho+3,4,14,"<b>COMPROBANTE DE RETENCION DEL IMPUESTO MUNICIPAL</b>");
 //$io_pdf->add_texto($li_ancho+3,10,9,"ARTICULO 114 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS");
 //$li_anchotitulo=$io_pdf->getTextWidth(9,"ARTICULO 114 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_anchotitulo);
 $la_data=array();//Nro de Comprobante
 $la_data[0]["1"]="<b>Nº COMPROBANTE</b>";
 $la_data[1]["1"]="$la_comprobantes[$li_k]";
 $la_anchos_col = array(50);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>2);
 $io_pdf->ezSetDy(-30);
 $io_pdf->add_tabla($li_anchotitulo+16+$li_ancho,$la_data,$la_opciones);
 $io_pdf->ezSetDy(28.5);
 $la_data=array();//Fecha
 $la_data[0]["1"]="<b>FECHA</b>";
 $la_data[1]["1"]=date("d/m/Y");
 $la_anchos_col = array(30);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>2);
 $io_pdf->add_tabla($li_anchotitulo+16+$li_ancho+55,$la_data,$la_opciones);
 //--------------------Anulado--------------------------------//
 $ls_estcmpret=$la_datacomprobantes["estcmpret"][1];
 if($ls_estcmpret==2)
 {
 	$io_pdf->add_texto(90,18,15,"<b>-----ANULADO-----<b>");
 }
 //-------------------------Fila 1---------------------------//
 $io_pdf->ezSetDy(-10);
 $li_posi=$io_pdf->get_alto_disponible()+20;
 $io_pdf->convertir_valor_mm_px($li_posi);
 $la_data=array();//Columna 1
 $la_data[0]["1"]="<b>DATOS DEL AGENTE DE RETENCION</b>";
 $la_data[1]["1"]="";
 $la_data[2]["1"]="<b>NOMBRE O RAZÓN SOCIAL:</b>  ".$ls_nomemp;
 $la_data[4]["1"]="<b>R.I.F:</b>  ".$ls_rifemp;
 $la_data[5]["1"]="<b>NIT:</b>".$ls_nitemp;
 $la_data[6]["1"]="<b>DOMICILIO FISCAL:</b>  ".$ls_diremp;
 $la_anchos_col = array(260);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 10,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);

 $li_pos=$io_pdf->y-8;
 $io_pdf->ezSetY($li_pos);
 $la_data=array();//Columna 1
 $la_data[0]["1"]="<b>DATOS DEL AGENTE SUJETO A RETENCIÓN</b>  ";
 $la_data[1]["1"]="";
 $la_data[2]["1"]="<b>NOMBRE O RAZÓN SOCIAL:</b>  ".$la_datacomprobantes["nomsujret"][1];
 $la_data[3]["1"]="<b>R.I.F:</b>  ".$la_datacomprobantes["rif"][1] ;
 $la_data[4]["1"]="<b>NIT:</b>".$la_datacomprobantes["nit"][1];
 $la_data[5]["1"]="<b>DOMICILIO FISCAL:</b> ".$la_datacomprobantes["dirsujret"][1];
 $la_anchos_col = array(260);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);

 //-------------------------Encabezado de la Tabla---------------------------//*/
 $la_data=array();
/* $li_pos=$io_pdf->y-8;
 $io_pdf->ezSetY($li_pos);*/
 $io_pdf->ezSetY(355);
/* $la_data[0]["1"]="<b>Nº</b>";
 $la_data[0]["2"]="<b>Orden de Pago</b>";
 $la_data[0]["3"]="<b>Fecha Factura</b>";
 $la_data[0]["4"]="<b>Número de Factura</b>";
 $la_data[0]["5"]="<b>Número Control de Factura</b>";
 $la_data[0]["6"]="<b>Monto de la Operación</b>"; 
 $la_data[0]["7"]="<b>Alícuota</b>";
 $la_data[0]["8"]="<b>Impuesto Retenido</b>";
 $la_anchos_col = array(12.6,43.12,22.83,39.32,39.78,45.66,20.29,39);
 $la_justificaciones = array("center","center","center","center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));*/
 $la_data[0]["1"]="<b>MONTO PAGADO O ABONADO EN CUENTA</b>";
 $la_data[0]["2"]="<b>CANTIDAD OBJETO DE RETENCION</b>";
 $la_data[0]["3"]="<b>% APLICADO</b>";
 $la_data[0]["4"]="<b>TIPO DE RETENCIÓN</b>";
 $la_data[0]["5"]="<b>TOTAL IMPUESTO RETENIDO</b>";
 $la_anchos_col = array(50,60,20.6,75,55);
 $la_justificaciones = array("center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  $io_pdf->add_tabla(-1,$la_data,$la_opciones);
  
  //--------------------------------Detalle de la Tabla----------------------------------------//
 $li_totalfilas=floor((count($la_datacomprobantes, COUNT_RECURSIVE) / count($la_datacomprobantes)) - 1);
/* $li_pos=$io_pdf->y-2;
 $io_pdf->ezSetY($li_pos);*/
 $la_data=array();
 $la_anchos_col="";
 $la_opciones=""; 
 $li_aux=0;
 $ls_tiporetencion="<b> Retencion 1 x 1.000</b>";
 for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
 {
	$la_data[$li_aux]["1"]=number_format(($la_datacomprobantes["montoret"][$li_i])-($la_datacomprobantes["iva_ret"][$li_i]),2,",",".");
	$la_data[$li_aux]["2"]=number_format($la_datacomprobantes["montoret"][$li_i],2,",",".");
	$la_data[$li_aux]["3"]=number_format($la_datacomprobantes["porimp"][$li_i],2,",",".");
	$la_data[$li_aux]["4"]=$ls_tiporetencion;
	$la_data[$li_aux]["5"]=number_format($la_datacomprobantes["iva_ret"][$li_i],2,",",".");
	//$la_data[$li_aux]["6"]=$la_datacomprobantes["numdoc"][$li_i];
	$la_fecha=$io_funciones->uf_convertirfecmostrar($la_datacomprobantes["fecfac"][$li_i]);
	
	$li_aux+=1;	
 }
 $la_anchos_col = array(50,60,20.6,75,55);
 $la_justificaciones = array("center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
				   "anchos_col"  => $la_anchos_col,
				   "tamano_texto"=> 9,
				   "alineacion_col"=>$la_justificaciones,
				   "margen_vertical"=>0,
				   "grosor_lineas_externas"=>0.1,
				   "grosor_lineas_internas"=>0.1,
					"margen_vertical"=>2,
					"color_fondo"=>array(255,255,255));
 $io_pdf->add_tabla(-1,$la_data,$la_opciones);
 
//--------------------------Agregar numerod e cheque------------------------------//
 $la_data=array();
 $la_data[0]["1"]="<b>NUMERO DE CHEQUE: </b>".$la_datacomprobantes["numdoc"][$li_i];
 $la_data[0]["2"]="<b>FECHA: </b>".$la_fecha;
 $la_anchos_col = array(130.6,130);
 $la_justificaciones = array("left","left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(255,255,255));
  $io_pdf->add_tabla(-1,$la_data,$la_opciones);
 
/* $io_pdf->ezSetDy(14);
 $la_data=array();
 $la_data[0]["1"]="<b>".number_format($ls_totaliva,2,",",".")."</b>";
 $la_anchos_col = array(39);
 $la_justificaciones = array("right");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  $io_pdf->add_tabla(222.6,$la_data,$la_opciones);*/
 
  //--------------------------Agregar Firma-------------------------------//
/*  $li_pos=$io_pdf->get_alto_usado();
   $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
  $io_pdf->add_tabla(-110,array(""),$la_opciones);
  $io_pdf->add_linea(82,$li_pos+20,164,$li_pos+20);
  $io_pdf->add_texto(88,$li_pos+21,9,"FIRMA Y SELLO DEL AGENTE DE RETENCION \n                          $ls_nomemp");*/
	$io_pdf->ezSetY(160);
	$la_data[0]=array('firma1'=>'','firma2'=>'');
	$la_data[1]=array('firma1'=>'','firma2'=>'');
	$la_data[2]=array('firma1'=>'___________________________________','firma2'=>'________________________________________');
	$la_data[3]=array('firma1'=>'  RECIBI CONFORME','firma2'=>'FIRMA Y SELLO');
	$la_data[4]=array('firma1'=>'','firma2'=>'VIALIDAD Y CONSTRUCCIONES SUCRE,S.A');
	$la_data[5]=array('firma1'=>'','firma2'=>'');
	$la_columna=array('firma1'=>'','firma2'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 10, // Tamaño de Letras
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'width'=>500, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'cols'=>array('firma1'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
								   'firma2'=>array('justification'=>'center','width'=>250))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
  if(($li_k+1)<count($la_comprobantes))
  {
  		$io_pdf->ezNewPage();
  }
 }
 $io_pdf->ezStream();	
?> 