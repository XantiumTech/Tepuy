<?PHP
    session_start();//ZONA EDUCATIVA DEL ESTADO LARA.
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;	
		print "close();";
		print "</script>";		
	}	
	//---------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 25/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_compra;
		$ls_descripcion="Generó el Reporte Análisis de Cotización";
		$lb_valido=$io_fun_compra->uf_load_seguridad_reporte("SOC","tepuy_soc_p_analisis_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_numanacot,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el banner del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->add_texto(140,5,14,"<b>ANÁLISIS DE PRECIOS</b>");
		$io_pdf->add_texto(325,18,10,"$ad_fecha");		
		$la_data[0]["1"]="<b>Nro.:</b>";
		$la_data[0]["2"]=$as_numanacot;
		$la_anchos_col = array(27,173);
		$la_justificaciones = array("left","left");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 11,
							   "lineas"        =>0,
							   "alineacion_col"=>$la_justificaciones);
		$io_pdf->ezSetDy(-50);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores($la_cotizaciones,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_proveedores
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el el listado de  proveedores participantes
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 18/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;		
		$li_totalcotizaciones=count($la_cotizaciones);
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Cotizaciones</b>";
		$la_anchos_col = array(335);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Nro. Solicitud</b>";
		$la_data[0]["2"]="<b>Nro. Cotización</b>";
		$la_data[0]["3"]="<b>Proveedor</b>";
		$la_data[0]["4"]="<b>Fecha</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";
		$la_data[0]["6"]="<b>I.V.A.</b>";

		$la_anchos_col = array(40,40,140,25,50,40);
		$la_justificaciones = array("center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//Imprimiendo columnas
		$la_data=array();
		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			$la_data[$li_i]["1"]=$la_cotizaciones[$li_i+1]["numsolcot"];
			$la_data[$li_i]["2"]=$la_cotizaciones[$li_i+1]["numcot"];
			$la_data[$li_i]["3"]=$la_cotizaciones[$li_i+1]["nompro"];
			$la_data[$li_i]["4"]=$io_funciones->uf_convertirfecmostrar($la_cotizaciones[$li_i+1]["feccot"]);
			$la_data[$li_i]["5"]=number_format($la_cotizaciones[$li_i+1]["montotcot"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_cotizaciones[$li_i+1]["poriva"],2,",",".");
		}
	
			$la_justificaciones=array();
			$la_justificaciones = array("center","center","left","center","right","right");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 10,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);	
	}//fin de uf_print_proveedores
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_items($as_tipsolcot,$la_items,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los items del analisis de cotizacion y su respectivo proveedor
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;		
		$li_totalitems=count($la_items);
		if($as_tipsolcot=="B")
			$ls_item="Bienes";
		else
			$ls_item="Servicios";
		
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>$ls_item</b>";
		$la_anchos_col = array(335);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(200,200,200),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Descripcion</b>";
		$la_data[0]["3"]="<b>Proveedor</b>";
		$la_data[0]["4"]="<b>Cant.</b>";
		$la_data[0]["5"]="<b>Precio/Unid. ".$ls_bolivares."</b>";
		$la_data[0]["6"]="<b>I.V.A. ".$ls_bolivares."</b>";
		$la_data[0]["7"]="<b>Monto Total ".$ls_bolivares."</b>";
		$la_data[0]["8"]="<b>Observación</b>";

		$la_anchos_col = array(25,35,75,30,35,35,35,65);
		$la_justificaciones = array("center","center","center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//Imprimiendo columnas
		$la_data=array();
		$li_totalprecio=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalitems;$li_i++)
		{
			$la_data[$li_i]["1"]=trim($la_items[$li_i+1]["codigo"]);
			$la_data[$li_i]["2"]=$la_items[$li_i+1]["denominacion"];
			$la_data[$li_i]["3"]=$la_items[$li_i+1]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_items[$li_i+1]["cantidad"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_items[$li_i+1]["precio"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_items[$li_i+1]["moniva"],2,",",".");
			$la_data[$li_i]["7"]=number_format($la_items[$li_i+1]["monto"],2,",",".");
			$la_data[$li_i]["8"]=$la_items[$li_i+1]["obsanacot"];
			$li_totalprecio+=$la_items[$li_i+1]["precio"];
			$li_totaliva+=$la_items[$li_i+1]["moniva"];
			$li_totalmonto+=$la_items[$li_i+1]["monto"];
		}
	
			$la_justificaciones=array();
			$la_justificaciones = array("center","left","left","right","right","right","right","left");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 9,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales ".$ls_bolivares."</b>";
		$la_data[0]["2"]="<b>".number_format($li_totalprecio,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(30,35,35,35);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(145,$la_data,$la_opciones);
	}//fin de uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_ganadores($as_numanacot,$as_tipsolcot,$aa_ganadores,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;				
				
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Resumen de Proveedores Ganadores</b>";
		$la_anchos_col = array(335);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(200,200,200),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Nombre</b>";
		$la_data[0]["3"]="<b>Subtotal ".$ls_bolivares."</b>";
		$la_data[0]["4"]="<b>Total Cargos ".$ls_bolivares."</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";

		$la_anchos_col = array(25,145,55,55,55);
		$la_justificaciones = array("center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		$la_data=array();
		$li_totalganadores=count($aa_ganadores);
		$li_totalsubtotal=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalganadores;$li_i++)
		{
			$ls_proveedor		= $aa_ganadores[$li_i]["cod_pro"];
			$ls_cotizacion		= $aa_ganadores[$li_i]["numcot"];
			$ls_tipo_proveedor	= $aa_ganadores[$li_i]["tipconpro"];
			$io_class_report->uf_select_items_proveedor($ls_cotizacion,$ls_proveedor,$as_numanacot,$as_tipsolcot,$la_items,$li_totrow); 
			$io_class_report->uf_calcular_montos($li_totrow,$la_items,$la_totales,$ls_tipo_proveedor);
			$la_data[$li_i]["1"]=$ls_proveedor;
			$la_data[$li_i]["2"]=$aa_ganadores[$li_i]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_totales["subtotal"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_totales["totaliva"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_totales["total"],2,",",".");
			$li_totalsubtotal+=$la_totales["subtotal"];
			$li_totaliva+=$la_totales["totaliva"];
			$li_totalmonto+=$la_totales["total"];
		}
		
		//Imprimiendo columnas
	
		$la_justificaciones=array();
		$la_justificaciones = array("center","left","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 9,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales ".$ls_bolivares."</b>";
		$la_data[0]["2"]="<b>".number_format($li_totalsubtotal,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(25,55,55,55);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(155,$la_data,$la_opciones);
	}//fin de uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_pagina($observacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_pagina
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el pie del reporte
		//	   Creado Por: Ing. Laura Cabré                  Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 17/06/2007                 Fecha de Modificación: 01/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(188);
		$la_data[4]=array('name'=>'<b>OBSERVACIÓN: </b> '.$observacion);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>735, // Ancho de la tabla						 
						 'xPos'=>510, // justificación de la tabla						 
						 'maxWidth'=>950,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>950))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(30,100,950,70); // primer rectángulo de firmas
		$io_pdf->addText(455,160,9,"<b>Comisión de Compras</b>");
		$io_pdf->addText(100,115,8,"Lic. Alexander Escudero");
		$io_pdf->addText(55,105,8,"Jefe de la División de Administración y Servicios");
		$io_pdf->addText(345,115,8,"Lic. Ana Castillo");
		$io_pdf->addText(310,105,8,"Jefe de la Coordinación de Compras");
		$io_pdf->addText(575,115,8,"Lic. Ramón Cabello");
		$io_pdf->addText(540,105,8,"Jefe de la Coordinación de Contabilidad");
		$io_pdf->addText(830,115,8,"Lic. Julián Zavarce");
		$io_pdf->addText(780,105,8,"Jefe de División de Planificación y Presupuesto");
		
		$io_pdf->Rectangle(30,30,950,70); 
		$io_pdf->addText(470,90,9,"<b>Observadores</b>");
		$io_pdf->addText(80,45,8,"Lic. Aura Marina Rodríguez");
		$io_pdf->addText(55,35,8,"Jefe de la Unidad de Verificación y Control");
		$io_pdf->addText(465,45,8,"T.S.U Walter Viscaya");
		$io_pdf->addText(435,35,8,"Coordinación de Escuelas Bolivarianas");
		$io_pdf->addText(830,45,8,"Lic. Eneida Jiménez");
		$io_pdf->addText(750,35,8,"Jefe Coordinación del Programa de Alimentación Escolar");
	}// end function uf_print_pie_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
  	
	require_once("tepuy_soc_class_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_class_report = new tepuy_soc_class_report();
	$io_funciones    = new class_funciones();
	$io_fun_compra   = new class_funciones_soc();
	$ls_tiporeporte=$io_fun_compra->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("tepuy_soc_class_reportbsf.php");
		$io_class_report=new tepuy_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	error_reporting(E_ALL);
	set_time_limit(3000);	
	$io_pdf=new class_pdf('LEGAL','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->numerar_paginas(7);	
	$io_pdf->set_margenes(10,30,3,3);	
	$ls_tipsolcot=$_GET["tipsolcot"];
	$ls_numanacot=$_GET["numanacot"];
	$ld_fecha=$_GET["fecha"];
	$ls_observacion=$_GET["observacion"];	
	$lb_valido=uf_insert_seguridad();
	if($lb_valido)
	{
		uf_print_encabezado_pagina($ls_numanacot,$ld_fecha,$io_pdf);
		$lb_valido=$io_class_report->uf_cargar_cotizaciones($ls_numanacot,$la_cotizaciones);
		if($lb_valido)
		{
			uf_print_proveedores($la_cotizaciones,$io_pdf);
			$lb_valido=$io_class_report->uf_select_items($ls_numanacot,$ls_tipsolcot,$la_items);
			if($lb_valido)
			{
				uf_print_items($ls_tipsolcot,$la_items,$io_pdf);
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_ganadores($ls_numanacot,$ls_tipsolcot,$la_ganadores,$io_pdf);
				uf_print_pie_pagina($ls_observacion,$io_pdf);
				$io_pdf->ezStream();
				unset($io_pdf);
			}
		}
	}
	if(!$lb_valido)
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que reportar');"); 
		print(" close();");
		print("</script>");	
	}
?> 