<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}

	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		
		$ls_descripcion="Generó el Reporte de Formato de salida de ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","tepuy_soc_p_solicitud_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsolcot,$as_fecsolcot,$as_dentipsolcot,$as_obssolcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   hidnumero // Número de solicitud
		//	    		   ls_fecsolcot // Número de solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(40,705,550,62);
		$io_pdf->line(450,766,450,705);
		$io_pdf->line(450,735,590,735);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],46,706.5,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);		
		$io_pdf->addText(240,720,10,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(460,745,10,"<b>   No.:</b>");      // Agregar texto
		$io_pdf->addText(495,745,10,$as_numsolcot); // Agregar Numero de la solicitud
		$io_pdf->addText(450,715,10,"<b>  Fecha:</b>"); // Agregar texto
		$io_pdf->addText(495,715,10,$as_fecsolcot); // Agregar la Fecha
		//$io_pdf->addText(555,980,7,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(560,970,7,date("h:i a")); // Agregar la hora
		
		$io_pdf->ezSetY(700);
		//$la_data=array(array('name'=>'<b>                  TIPO:  </b>'.$as_dentipsolcot),
		//			   array('name'=>'<b>OBSERVACIÓN: </b> '.$as_obssolcot1));
		$la_data=array(array('name'=>'<b>                  TIPO:  </b>'.$as_dentipsolcot));
		//			   array('name'=>'<b>OBSERVACIÓN: </b> '.$as_obssolcot1));								
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'titleFontSize' => 11,
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_proveedor($as_codpro,$as_nompro,$as_dirpro,$as_telpro,$as_email,$as_rifpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_proveedor
		//		   Access: private 
		//	    Arguments: as_numsolcot // Número
		//	    		   as_fecsolcot // Fecha
		//	    		   as_obssolcot // Observación
		//	    		   as_codpro // Código de Proveedor
		//	    		   as_nompro // Nombre de Proveedor
		//	    		   as_dirpro // Dirección de Proveedor
		//	    		   as_telpro // Teléfono de Proveedor
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 19/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data=array(array('name'=>'<b>Nombre o Razón Social: </b>'.$as_codpro.'  -  '.$as_nompro),
 		               array('name'=>'<b>Dirección: </b>'.$as_dirpro),
					   array('name'=>'<b>Teléfono: </b> '.$as_telpro.'.                           <b>E-Mail</b>: '.$as_email.'                                                                  <b>RIF: </b>'.$as_rifpro));				
		
		$io_pdf->ezSetDy(-10);
		$la_columna=array('name'=>'<b>DATOS DEL PROVEEDOR</b>');		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'titleFontSize' => 8,
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
	}// end function uf_print_cabecera

    //--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_encabezadosolicitud($as_dentipsolcot,$as_concepto,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_proveedor
		//		   Access: private 
		//	    Arguments: 
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime el texto que está en la solicitud de cotización
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 01/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 
       /*  $io_pdf->addText(60,795,10,"<i>Por medio de la presente permítame saludarles y al mismo tiempo, aprovecho la oportunidad para invitar su empresa a</i>"); // Agregar titulo
         $io_pdf->addText(42,785,10,"<i>cotizar los rubros que se relacionan a continuación así mismo se les notifica los lineamientos a seguir:</i> "); // Agregar titulo
		 $io_pdf->addText(55,770,10,"   <i>1. Deben estar inscritos en nuestro registro de proveedores con la documentación actualizada.</i>"); // Agregar titulo
		 $io_pdf->addText(55,755,10,"   <i>2. Es de <b>carácter obligatorio</b> que para participar en dicho proceso; debe entregar la muestra de los productos que su</i>"); // Agregar titulo
		 $io_pdf->addText(55,745,10,"   <i>    empresa presenta en la cotización, de lo contrario serán excluido de dicho proceso.</i>"); 
		 $io_pdf->addText(55,730,10,"   <i>3. Especificar los rubros claramente en cuanto a su calidad y dimensiones.</i>"); // Agregar titulo
	     $io_pdf->addText(55,715,10,"   <i>4. La cotización debe venir en sobre cerrado y marcado con sello húmedo.</i>"); // Agregar titulo
		 $io_pdf->addText(55,700,10,"   <i>5. Debe estar inscrito Registro Nacional de Contratista.</i>"); // Agregar titulo*/
         $io_pdf->addText(60,605,10,"<i>Me es grato dirigirme a usted (es) con la finalidad de hacerle la invitacion para participar en la cotizacion de precios, la</i>"); // Agregar titulo
         $io_pdf->addText(42,595,10,"<i>cual se hara mediante el procedimiento de consulta de precios bajo la modalidad de <b>Orden de ".$as_dentipsolcot."</b>, de conformidad </i> "); // Agregar titulo
         $io_pdf->addText(42,585,10,"<i>con lo dispuesto en art. 73 de la Ley de Contrataciones Publicas, referente a: </i> "); // Agregar titulo
	$as_concepto1=""; //substr($as_concepto,0,81);
	$as_concepto2=""; //substr($as_concepto,81,80);
	$as_concepto3="";
	$as_concepto4="";
	$as_concepto5="";
/// NUEVO //

	$array_cadena=str_word_count( $as_concepto,1,'àáéèíìóòúùñÑ0123456789()/?¡!¿@');
	$linea=1;
	$auxiliar="";
	foreach ($array_cadena as $palabra)
	{
		$auxiliar=$auxiliar.$palabra." ";
		if(strlen($auxiliar)>80)
		{
			$linea++;
			$auxiliar="";
		}
		switch ($linea)
		{
		   case 1:
		 	$as_concepto1=$as_concepto1.$palabra." ";
			break;
		   case 2:
		 	$as_concepto2=$as_concepto2.$palabra." ";
			break;
		   case 3:
		 	$as_concepto3=$as_concepto3.$palabra." ";
			break;
		   case 4:
		 	$as_concepto4=$as_concepto4.$palabra." ";
			break;
		   case 5:
		 	$as_concepto5=$as_concepto5.$palabra." ";
			break;
		}
	}

///////////
	for ( $i = 1 ; $i <= $linea ; $i ++)
	{
		switch ($i)
		{
		   case 1:
		 	$io_pdf->addText(42,575,10,"<i><b>".$as_concepto1."</b></i> "); // Agregar titulo 
			$xa=560;
			break;
		   case 2:
		 	$io_pdf->addText(42,565,10,"<i><b>".$as_concepto2."</b></i> "); // Agregar titulo 
			$xa=550;
			break;
		   case 3:
		 	$io_pdf->addText(42,555,10,"<i><b>".$as_concepto3."</b></i> "); // Agregar titulo 
			$xa=540;
			break;
		   case 4:
		 	$io_pdf->addText(42,545,10,"<i><b>".$as_concepto4."</b></i> "); // Agregar titulo 
			$xa=530;
			break;
		   case 5:
		 	$io_pdf->addText(42,530,10,"<i><b>".$as_concepto5."</b></i> "); // Agregar titulo 
			$xa=520;
			break;
		}

	}
         //$io_pdf->addText(42,570,10,"<i><b>".$as_concepto1."</b></i> "); // Agregar titulo
	 //$io_pdf->addText(42,560,10,"<i><b>".$as_concepto2."</b></i> "); // Agregar titulo
       //  $io_pdf->addText(42,$xa,10,"<i>Se exige como condicion de entrega lo siguiente: </i> "); // Agregar titulo
	$io_pdf->addText(55,$xa,10,"   <i><b>1.</b> El bien o servicio objeto de la contratacion sean transportados por la empresa adjudicada.</i>"); // Agregar titulo
	$xa=$xa-10;
	$io_pdf->addText(55,$xa,10,"   <i><b>2.</b> Deben estar inscritos en nuestro registro de proveedores con la documentación actualizada.</i>"); // Agregar titulo
	$xa=$xa-10;
	$io_pdf->addText(55,$xa,10,"   <i><b>3.</b> Especificar los rubros claramente en cuanto a su calidad y dimensiones.</i>"); // Agregar titulo
	$xa=$xa-10;
	$io_pdf->addText(55,$xa,10,"   <i><b>4.</b> La cotización debe venir en sobre cerrado y marcado con sello húmedo.</i>"); // Agregar titulo
	$xa=$xa-10;
	$io_pdf->addText(55,$xa,10,"   <i><b>5.</b> Debe estar inscrito en el Servicio Nacional de Contratista y demás requisitos exigidos por la Alcaldía.</i>"); // Agregar titulo

	}// end function uf_print_encabezadosolicitud



	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Miguel Palencia.                
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$io_pdf->ezSetY(485);
		//$io_pdf->ezSetDy(5);
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'unidad'=>'<b>Unidad de Medida</b>',
						  'denominacion'=>'<b>                                                  Denominación</b>',
  						  'cantidad'=>'<b>Cantidad</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>50),      // Justificación y ancho de la columna
						 				'unidad'=>array('justification'=>'left','width'=>95), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>305), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>DETALLE DE LOS MATERIALES, SUMINISTROS O SERVICIOS REQUERIDOS</b>',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_pie($io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie
		//		   Access: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime la nota final y la firma
		//	   Creado Por: Ing. Miguel Palencia.                
		// Fecha Creación: 01/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$la_data[2]=array('name'=>'<b><i>NOTA: Se le agradece presentar la cotización en lapso NO mayor de tres (3) días.</b></i>');				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'maxWidth'=>548);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->addText(65,195,10,"     <i>Debera presentar su oferta dentro de los tres (3) dias habiles luego de recibir este oficio.   Cualquier aclaratoria a
 </i>"); // Agregar titulo
		$io_pdf->addText(55,185,10,"<i>la presente invitacion podra ser hecha al siguiente dia habil a la aceptacion de la misma y sera respondida al siguiente
 </i>"); // Agregar titulo
		$io_pdf->addText(55,175,10,"<i>dia habil de recibirla;  por ante  la  Unidad  de  Compras  y  Suministros.   El criterio utilizado para la adjudicacion de la </i>"); // Agregar titulo
		$io_pdf->addText(55,165,10,"<i>contratacion  requerida,  sera  la  que  presenta  mejor  oferta  de  precios,  calidad y tiempo de entrega en cuanto a la
 </i>"); // Agregar titulo
		$io_pdf->addText(55,155,10,"<i>totalidad del material o servicio requerido. </i>"); // Agregar titulo


		$io_pdf->addText(65,135,10,"   <i>Sin mas a que hacer referencia, se suscribe de usted (es) </i>"); // Agregar titulo
	  	$io_pdf->addText(260,120,10,"   <i>Atentamente,</i>");
		// COMPRAS ///
		$ls_jefe_compras=$_SESSION["la_empresa"]["jefe_compr"];
		$li_tm=$io_pdf->getTextWidth(10,$ls_jefe_compras);
		$tm=320-($li_tm/2);

		$io_pdf->addText($tm,85,10,$ls_jefe_compras); // Agregar el Nombre del Jefe de Contabilidad
		$ls_cargo_compras=$_SESSION["la_empresa"]["cargo_compr"];
		$li_tm=$io_pdf->getTextWidth(10,$ls_cargo_compras);
		$tm=320-($li_tm/2);

		$io_pdf->addText($tm,75,10,$ls_cargo_compras); // Agregar el título

		$io_pdf->addText(135,55,12,'<b><i></b></i>');
		$io_pdf->addText(215,50,12,'<b><i></b></i>');
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("tepuy_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/tepuy_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in           = new tepuy_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_report    = new tepuy_soc_class_report($con);
	$io_funciones = new class_funciones();
	$io_fun_soc	  = new class_funciones_soc();
	$ls_numsolcot = $_GET["numsolcot"];
	$ls_tipsolcot = $_GET["tipsolcot"];
	$ls_fecsolcot = $_GET["fecsolcot"];
	if ($ls_tipsolcot=='B')
	   {
	     $ls_tabla = "soc_dtsc_bienes";
	     $ls_campo = "codart";
	     $ls_table = "siv_articulo"; 
	     $ls_tipo  = "Bienes"; 
	   }
	elseif($ls_tipsolcot=='S')
	   {
	     $ls_tabla = "soc_dtsc_servicios";
	     $ls_campo = "codser";
	     $ls_table = "soc_servicios";
	     $ls_tipo  = "Servicios"; 
	   }
	$ls_codemp = $_SESSION["la_empresa"]["codemp"];
	$ls_titulo = "SOLICITUD DE COTIZACIÓN";

	$lb_valido = uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if ($lb_valido)
	   {
	     $rs_data = $io_report->uf_load_cabecera_formato_solicitud_cotizacion($ls_numsolcot,$ls_tipsolcot,$ls_fecsolcot,$ls_tabla,&$lb_valido);
	     if (!$lb_valido)
		    {
			  print("<script language=JavaScript>");
			  print(" alert('No hay nada que Reportar !!!');"); 
			  print(" close();");
			  print("</script>");
		    }
	     else
	        {
	          $li_numrows = $io_sql->num_rows($rs_data);
		      if ($li_numrows>0)
		         {
				   error_reporting(E_ALL);
				   set_time_limit(1800);
				   //$io_pdf = new Cezpdf('LEGAL','portrait'); // Instancia de la clase PDF
				   $io_pdf = new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
				   $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
				   $io_pdf->ezSetCmMargins(5,6,1,1); // Configuración de los margenes en centímetros
				   $io_pdf->ezStartPageNumbers(550,30,10,'','',1); // Insertar el número de página
				   $li_count = 0; 
				  
				   while (($row=$io_sql->fetch_row($rs_data)) && $lb_valido)
						 {
                           $li_count++;
					       if ($li_count>1)
					          {
						        $io_pdf->ezNewPage(); 					  
						      }   
 					  	   $ls_codpro    = $row["cod_pro"];
					  	   $ls_nompro    = $row["nompro"];
						   $ls_dirpro    = $row["dirpro"];
						   $ls_telpro    = $row["telpro"];
						   $ls_obssolcot = $row["obssol"];
						   $ls_fecsolcot = $row["fecsol"];
						   $ls_mailpro   = $row["email"];
						   $ls_rifpro    = $row["rifpro"];
						   $ls_concepto    = $row["concepto"];
						   $ls_fecsolcot = $io_funciones->uf_convertirfecmostrar($ls_fecsolcot);
						   $rs_datos     = $io_report->uf_load_dt_solicitud_cotizacion($ls_numsolcot,$ls_codpro,$ls_tabla,$ls_table,$ls_campo,&$lb_valido);
						   if ($lb_valido)
					          {
					     	    $li_totrows = $io_sql->num_rows($rs_datos);
							    if ($li_totrows>0)
							       { 
							         $li_i = 0;
								     while($row=$io_sql->fetch_row($rs_datos))
								          {
									        $li_i++;
										    $ls_codigo       = trim($row["codite"]);
										    $ls_denite       = $row["denite"];
										    $ld_canite       = number_format($row["canite"],2,',','.');
										    $ls_denunimed    = $row["denunimed"];
									        $la_datos[$li_i] = array('codigo'=>$ls_codigo,'unidad'=>$ls_denunimed,'denominacion'=>$ls_denite,'cantidad'=>$ld_canite);
									      }
								   }
						        else
							       {
							         $lb_valido = false;
							       }
						      }
					       uf_print_encabezado_pagina($ls_titulo,$ls_numsolcot,$ls_fecsolcot,$ls_tipo,$ls_obssolcot,$io_pdf);
					       uf_print_datos_proveedor($ls_codpro,$ls_nompro,$ls_dirpro,$ls_telpro,$ls_mailpro,$ls_rifpro,$io_pdf);
						   uf_print_encabezadosolicitud($ls_tipo,$ls_concepto,&$io_pdf);
						   uf_print_detalle($la_datos,$io_pdf);
						   uf_print_pie($io_pdf);
	           		       $io_pdf->setStrokeColor(0,0,0);
					       $io_pdf->line(20,50,580,50);
					     }
			        $io_pdf->ezStopPageNumbers(1,1);
			        $io_pdf->ezStream();
			     }
		      else
		         {
			       print("<script language=JavaScript>");
			       print(" alert('No hay nada que Reportar');"); 
			       print(" close();");
			       print("</script>");
			     }
	        } 
	   }			
?>
