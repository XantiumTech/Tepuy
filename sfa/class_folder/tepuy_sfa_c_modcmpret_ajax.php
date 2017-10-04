<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	require_once("../../shared/class_folder/class_datastore.php");
	$io_dscuentas=new class_datastore(); // Datastored de cuentas contables
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		

	// proceso a ejecutar
	$ls_proceso=$io_fun_sfa->uf_obtenervalor("proceso","");
	//print "Proceso: ".$ls_proceso;
	if($ls_proceso=="BUSCARFACTURARET")
	{
		// numero de factura
		$ls_numfactura=$io_fun_sfa->uf_obtenervalor("numfactura","");
		// fecha(registro) de inicio de busqueda
		$ld_fecemides=$io_fun_sfa->uf_obtenervalor("fecemides","");
		// fecha(registro) de fin de busqueda
		$ld_fecemihas=$io_fun_sfa->uf_obtenervalor("fecemihas","");
		// fecha de emision de la retencion
		$ls_fechaemision=$io_fun_sfa->uf_obtenervalor("fechaemision","");
		//print "factura: ".$ls_numfactura." fecha emision: ".$ls_fechaemision." Fecha Busqueda Desde: ".$ld_fecemides." Fechas Hasta: ".$ld_fecemihas;die();
	}
	else
	{
		// total de filas de recepciones
		$li_totrowrecepciones=$io_fun_sfa->uf_obtenervalor("totrowrecepciones",1);
		// numero del comprobante 
		$ls_numcom=$io_fun_sfa->uf_obtenervalor("numcom","");
	}
	$ls_codret=$io_fun_sfa->uf_obtenervalor("codret","");
	switch($ls_proceso)
	{
		case "AGREGARCMPRET":
			uf_print_dt_cmpret($li_totrowrecepciones);
			break;
		case "LOADDETALLECMP":
			uf_load_dt_cmpret($ls_numcom,$ls_codret);
			break;
		case "AGREGARCMPRETINS":
			uf_print_dt_cmpret_ins($li_totrowrecepciones);
			break;
		case "BUSCARFACTURARET":
			//print "codret: ".$ls_codret;
			uf_print_facturasreten($ls_numfactura,$ls_codret,$ld_fecemides,$ld_fecemihas,$ls_tipooperacion);
			break;
		case "RETENCIONESUNIFICADAS":
			uf_print_retencionesunificadas();
			break;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dt_cmpret_ins($ai_totrowrecepciones)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_recepciones
		//		   Access: private
		//	    Arguments: ai_totrowrecepciones // Total de filas de recepciones de documentos
		//				   ai_total             // Monto total
		//	  Description: Método que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_fun_sfa, $io_dscuentas;
		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		// Titulos el Grid
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Ficha";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Fecha"; 
		$lo_title[5]="Total con IVA"; 
		$lo_title[6]="Total sin IVA"; 
		$lo_title[7]="Base Imponible"; 
		$lo_title[8]="Porcentaje Impuesto"; 
		$lo_title[9]="Total Impuesto";
		$lo_title[10]="Impuesto Retenido"; 
		$lo_title[11]="Nro. Documento";  
		$lo_title[12]="Nro. Solicitud";  
		$lo_title[13]="Editar";
		
		// Recorrido del Grid de Recepciones
		for($li_fila=1;$li_fila<$ai_totrowrecepciones;$li_fila++)
		{
			$ls_codret=trim($io_fun_sfa->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_fun_sfa->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_fun_sfa->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_fun_sfa->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_fun_sfa->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_fun_sfa->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_fun_sfa->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_fun_sfa->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_fun_sfa->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_fun_sfa->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_fun_sfa->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_fun_sfa->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_porret=trim($io_fun_sfa->uf_obtenervalor("txtporret".$li_fila,"0.00"));
			$ls_totimp=trim($io_fun_sfa->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_fun_sfa->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_numsop=trim($io_fun_sfa->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_fun_sfa->uf_obtenervalor("txtnumdoc".$li_fila,""));
			

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$io_funciones->uf_cerosizquierda($li_fila,10)."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."'>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' >";
			$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' >";
			$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' readonly><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
						 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."'>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." value='".$ls_numnd."'>"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." value='".$ls_numnc."'>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' readonly size=15><a href=javascript:ue_cat_solicitud('".$li_fila."');><img src=../shared/imagebank/tools20/buscar.png alt=Buscar width=15 height=15 border=0 title=Buscar></a>";
			$lo_object[$li_fila][13]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly  value=''>";
		$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly  value=''>";
		$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly  value=''>";
		$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
		$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly size=15>";
		$lo_object[$li_fila][13]="<a><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dt_cmpret($ai_totrowrecepciones)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_recepciones
		//		   Access: private
		//	    Arguments: ai_totrowrecepciones // Total de filas de recepciones de documentos
		//				   ai_total             // Monto total
		//	  Description: Método que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_fun_sfa, $io_dscuentas;
		// Titulos el Grid
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Ficha";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Fecha"; 
		$lo_title[5]="Total con IVA"; 
		$lo_title[6]="Total sin IVA"; 
		$lo_title[7]="Base Imponible"; 
		$lo_title[8]="Porcentaje Impuesto"; 
		$lo_title[9]="Total Impuesto";
		$lo_title[10]="Iva Retenido"; 
		$lo_title[11]="Nro. Documento";
		$lo_title[12]="Nro. Solicitud";   
		$lo_title[13]="Editar";
		
		// Recorrido del Grid de Recepciones
		for($li_fila=1;$li_fila<$ai_totrowrecepciones;$li_fila++)
		{
			$ls_codret=trim($io_fun_sfa->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_fun_sfa->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_fun_sfa->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_fun_sfa->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_fun_sfa->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_fun_sfa->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_fun_sfa->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_fun_sfa->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_fun_sfa->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_fun_sfa->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_fun_sfa->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_fun_sfa->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_totimp=trim($io_fun_sfa->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_fun_sfa->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_porret=trim($io_fun_sfa->uf_obtenervalor("txtporret".$li_fila,"0,00"));
			$ls_numsop=trim($io_fun_sfa->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_fun_sfa->uf_obtenervalor("txtnumdoc".$li_fila,""));

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numope."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' readonly>";
			$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' readonly><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
					 			 	 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."' readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." value='".$ls_numnd."'>"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." value='".$ls_numnc."'>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' readonly size=15>";
			$lo_object[$li_fila][13]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly value=''>";
		$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
		$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly >"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly>";
		$lo_object[$li_fila][13]="<a><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_dt_cmpret($as_numcom,$as_codret)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_numfactura  // Número de Solicitud
		//                 ai_total   // Total de la Solicitud
		//	  Description: Método que busca las recepciones de documento asociadas y las imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_fun_sfa;

		// Titulos del Grid
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Ficha";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Fecha"; 
		$lo_title[5]="Total con IVA"; 
		$lo_title[6]="Total sin IVA"; 
		$lo_title[7]="Base Imponible"; 
		$lo_title[8]="Porcentaje Impuesto"; 
		$lo_title[9]="Total Impuesto";
		$lo_title[10]="Iva Retenido"; 
		$lo_title[11]="Nro. Documento";  
		$lo_title[12]="Nro. Solicitud"; 
		$lo_title[13]="Editar";
		
		$lo_object[0]="";
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("tepuy_cxp_c_modcmpret.php");
		$io_modcmpret=new tepuy_cxp_c_modcmpret("../../");
		$rs_data = $io_modcmpret->uf_load_dt_cmpret($as_numcom,$as_codret);
		$li_fila=0;
		
		while($row=$io_modcmpret->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_numope=trim($row["numope"]);
			$ls_numfac=trim($row["numfac"]);
			$ls_numcon=trim($row["numcon"]);
			$ls_fecfac=$io_funciones->uf_convertirfecmostrar($row["fecfac"]);
			$ls_totcmp_sin_iva=number_format($row["totcmp_sin_iva"],2,",",".");
			$ls_totcmp_con_iva=number_format($row["totcmp_con_iva"],2,",",".");
			$ls_basimp=number_format($row["basimp"],2,",",".");
			$ls_porimp=number_format($row["porimp"],2,",",".");
			$ls_totimp=number_format($row["totimp"],2,",",".");
			$ls_ivaret=number_format($row["iva_ret"],2,",",".");
			$ls_porret=$row["porimp"];
			$ls_numdoc=trim($row["numdoc"]);
			$ls_codret=trim($row["codret"]);
			$ls_numsop=trim($row["numsop"]);
			$ls_numnd=trim($row["numnd"]);
			$ls_numnc=trim($row["numnc"]);
			$ls_tiptrans=trim($row["tiptrans"]);
			
			
			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numope."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' readonly>";
			$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly; ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); ><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
					 			     "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."' readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." value='".$ls_numnd."'>"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." value='".$ls_numnc."'>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' size=13 readonly size=15>";
			$lo_object[$li_fila][13]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly value=''>";
		$lo_object[$li_fila][9]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][10]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
		$lo_object[$li_fila][11]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][12]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly size=15>";
		$lo_object[$li_fila][13]="<a><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		unset($io_modcmpret);		
		
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
		
	}// end function uf_load_dt_cmpret
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_facturasreten($as_numfactura,$as_codret,$ad_fecemides,$ad_fecemihas,$as_tipooperacion)
	{	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_facturasreten
		//		   Access: private
		//		 Argument: as_numfactura // Numero de la Factura
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Método que impirme el grid de las solicitudes a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/09/2017
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_fun_sfa, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Nro. de Factura";
		$lo_title[3]="Fecha Emision";
		$lo_title[4]="Cliente";
		$lo_title[5]="Monto Neto a Cobrar";
		$lo_title[6]="Monto Obj. Ret.";
		$lo_title[7]="Monto Retencion";
		$lo_title[8]="% Retenido";
		$lo_title[9]="";
		$lo_title[10]="";
		$ad_fecemides=$io_funciones->uf_convertirdatetobd($ad_fecemides);
		$ad_fecemihas=$io_funciones->uf_convertirdatetobd($ad_fecemihas);
		$as_numfactura="%".$as_numfactura."%";
		$as_proben="%".$as_proben."%";
		require_once("tepuy_sfa_c_modcmpret.php");
		$io_modcmpret=new tepuy_sfa_c_modcmpret("../../");
		//print "codigo de la retencion: ".$as_codret;
		$rs_datafactura=$io_modcmpret->uf_load_facturasreten($as_numfactura,$as_codret,$ad_fecemides,$ad_fecemihas,$as_tipooperacion);
		$li_fila=0;
		while($row=$io_modcmpret->io_sql->fetch_row($rs_datafactura))
		{
			$li_fila=$li_fila + 1;
			$ls_numfactura=$row["numfactura"];
			//print "Factura: ".$ls_numfactura;
			$ld_fecfactura=$row["fecfactura"];
			$ls_estfac=$row["estfac"];
			$ls_rifcli1=$row["rifcli"];
			$ls_cedcli=$row["cedcli"];
			if(strlen($ls_cedcli)<10)
			{
				$ls_cedcli=str_repeat(" ",(10-strlen($ls_cedcli))).$ls_cedcli;
			}
			$ls_dircli=$row["dircli"];
			$ls_rifcli=$ls_rifcli1."-".$ls_cedcli."-".$ls_dircli;
			$ls_codded=$row["codded"];
			$ls_cliente=utf8_encode($row["nombre"]);
			$li_monfactura=number_format($row["montot"],2,',','.');
			$li_monobjret=number_format($row["monobjret"],2,',','.');
			$li_monret=number_format($row["monret"],2,',','.');
			$li_porded=number_format(($row["porded"]*100),2,',','.');
			$ld_fecfactura=$io_funciones->uf_convertirfecmostrar($ld_fecfactura);

			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
			$lo_object[$li_fila][2]="<input type=text name=txtnumfactura".$li_fila."    id=txtnumfactura".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_numfactura."'    readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecfactura".$li_fila." id=txtfecfactura".$li_fila." class=sin-borde style=text-align:left   size=15 value='".$ld_fecfactura."' readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtcliente".$li_fila."    id=txtcliente".$li_fila."    class=sin-borde style=text-align:left   size=35 value='".$ls_cliente."'    readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtmonfactura".$li_fila."    id=txtmonfactura".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monfactura."' 	  readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmonobjret".$li_fila."    id=txtobjret".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monobjret."' 	  readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtmonret".$li_fila."    id=txtmonret".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monret."' 	  readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txtporret".$li_fila."    id=txtporret".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_porded."' 	  readonly>";
			$lo_object[$li_fila][9]="<input type=hidden name=txtrifcli".$li_fila."  id=txtrifcli".$li_fila."    class=sin-borde style=text-align:right  size=0 value='".$ls_rifcli."' 	  readonly>";
			$lo_object[$li_fila][10]="<input type=hidden name=txtcodded".$li_fila."  id=txtcodded".$li_fila."    class=sin-borde style=text-align:right  size=0 value='".$ls_codded."' 	  readonly>";
		}
		//print "Fila: ".$li_fila;
		if($li_fila==="0")
		{
			$io_aprobacion->io_mensajes->message("No se encontraron ordenes de pago para aplicar este tipo de retención");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
			$lo_object[$li_fila][2]="<input type=text name=txtnumfactura".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecfactura".$li_fila." class=sin-borde style=text-align:left   size=15 readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtcliente".$li_fila."    class=sin-borde style=text-align:left   size=35 readonly>"; 
		//	$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
			$lo_object[$li_fila][5]="<input type=text name=txtmonfactura".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmonobjret".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtmonret".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txtporret".$li_fila."     class=sin-borde style=text-align:right  size=10 readonly>";
			$lo_object[$li_fila][9]="<input type=hidden name=txtrifcli".$li_fila."     class=sin-borde style=text-align:right  size=0 readonly>";
			$lo_object[$li_fila][10]="<input type=hidden name=txtcodded".$li_fila."     class=sin-borde style=text-align:right  size=0 readonly>";
		}

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Listado de Facturas","gridsolicitudes");
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencionesunificadas()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesunificadas
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/09/2017
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		/*$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ld_fecdesde=$ls_anio."-".$ls_mes."-01";
		$ld_fechasta=$ls_anio."-".$ls_mes."-".substr($io_fecha->uf_last_day($ls_mes,$ls_anio),0,2);
		$ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif ".
				"  FROM sfa_cmp_retencion ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND fecrep>='".$ld_fecdesde."' ".
				"   AND fecrep<='".$ld_fechasta."' ".
				"   AND codret='0000000002' ".
				" ORDER BY numcom";*/
		$ld_fecdes=$_POST['fecdes'];
		$ld_fechas=$_POST['fechas'];
		$ls_mes=$_POST['mes'];
		$ls_anio=$_POST['anio'];
		$ls_tipo=$_POST['tipo'];
		//$ls_tipo="0000000002";
		$ls_numfactura=$_POST['numfactura'];
		$ls_cedclides=$_POST['codprobendes'];
		$ls_cedclihas=$_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND sfa_cmp_retencion.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND sfa_cmp_retencion.fecrep <= '".$ld_fechas."'";
		}
		if($ls_cedclides!="")
		{
			$ls_criterio=$ls_criterio."		AND sfa_cmp_retencion.cedcli >= '".$ls_cedclides."'";
		}
		if($ls_cedclides!="")
		{
			$ls_criterio=$ls_criterio."		AND sfa_cmp_retencion.cedcli <= '".$ls_cedclides."'";
		}

		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numfactura!="")
		{
			$ls_where=" AND sfa_dt_retenciones.numfactura='".$ls_numfactura."'";
		}				
		$ls_sql="SELECT DISTINCT sfa_cmp_retencion.numcom, sfa_cmp_retencion.feccomp, sfa_cmp_retencion.perfiscal, ". 					"sfa_cmp_retencion.cedcli, sfa_cmp_retencion.nomcli, sfa_cmp_retencion.dircli, sfa_cmp_retencion.rifcli, ".
				"sfa_cmp_retencion.estcmpret, sfa_dt_retenciones.codded, sfa_dt_retenciones.numfactura ". //, cxp_contador.nom ".
			"  FROM sfa_cmp_retencion, sfa_dt_retenciones, cxp_contador ".
			" WHERE sfa_cmp_retencion.codemp = '".$ls_codemp."' ".
				"   AND sfa_cmp_retencion.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"   AND sfa_cmp_retencion.codemp = sfa_dt_retenciones.codemp  ".
				//"   AND sfa_cmp_retencion.tipret = cxp_contador.codcmp ".
				"   AND sfa_cmp_retencion.codret = sfa_dt_retenciones.codded ".
				"   AND sfa_cmp_retencion.numfactura = sfa_dt_retenciones.numfactura ".
				$ls_criterio.
				" GROUP BY sfa_cmp_retencion.cedcli,  sfa_dt_retenciones.numfactura ORDER BY sfa_cmp_retencion.feccomp ASC ";  
		//print $ls_sql;	 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones Unificadas ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			//$lo_title[2]="Comprobantes";
			//$lo_title[2]="Tipo";
			//$lo_title[4]="Codigo Proveedor/Beneficiario";
			$lo_title[2]="Nro. de Factura";
			$lo_title[3]="Fecha";
			$lo_title[4]="Nombre del Cliente";   
			//$lo_title[7]="Dirección";  
			$lo_title[5]="R.I.F.";   
			$lo_title[6]="";
			$lo_title[7]="";
			$li_totrow=0;
			$lo_object[$li_totrow][1]="";
			$lo_object[$li_totrow][2]="";
			$lo_object[$li_totrow][3]="";
			$lo_object[$li_totrow][4]="";
			$lo_object[$li_totrow][5]="";
			$lo_object[$li_totrow][6]="";
			$lo_object[$li_totrow][7]="";
			//$lo_object[$li_totrow][8]="";
			$ls_numfacanterior="";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_totrow++;
				$ls_numfactura=$row["numfactura"];
				$ls_fecomp=$row["feccomp"];
				$ls_fecha=substr($ls_fecomp,8,2)."-".substr($ls_fecomp,5,2)."-".substr($ls_fecomp,0,4);
				$ls_cedcli=$row["cedcli"];
				$ls_nomcli=$row["nomcli"];
				$ls_dircli=$row["dircli"];
				//$ls_ordenpago=$row["numsop"];
				$ls_codret=$row["codded"];
				$ls_rifcli=$row["rifcli"];
				$lo_object[$li_totrow][1]="<input type=checkbox name=checkcmp".$li_totrow."     id=checkcmp".$li_totrow."     value=1                   size=10 style=text-align:left    class=sin-borde>"; 
				//$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtnumcom".$li_totrow."    id=txtnumcom".$li_totrow."    value='".$ls_numcom."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][2]="<div align=center><input type=text name=txtfactura".$li_totrow."    id=txtfactura".$li_totrow."    value='".$ls_numfactura."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				//$lo_object[$li_totrow][4]="<div align=center><input type=text name=txtcodsujret".$li_totrow." id=txtcodsujret".$li_totrow." value='".$ls_codsujret."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][3]="<div align=center><input type=text name=txtfecha".$li_totrow." id=txtfecha".$li_totrow." value='".$ls_fecha."' class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$lo_object[$li_totrow][4]="<div align=left><input   type=text name=txtnomcli".$li_totrow." id=txtnomcli".$li_totrow." value='".$ls_nomcli."' class=sin-borde readonly style=text-align:left size=50 maxlength=50></div>";
				$lo_object[$li_totrow][5]="<div align=left><input   type=text name=txtrifcli".$li_totrow." id=txtrifcli".$li_totrow." value='".$ls_rifcli."' class=sin-borde readonly style=text-align:left size=15 maxlength=15></div>";
				$lo_object[$li_totrow][6]="<div align=left><input   type=hidden name=txtdircli".$li_totrow." id=txtdircli".$li_totrow." value='".$ls_dircli."' class=sin-borde readonly style=text-align:left size=35 maxlength=200></div>";
				//$lo_object[$li_totrow][6]="<div align=center><input type=text name=txtrif".$li_totrow."       id=txtrif".$li_totrow."       value='".$ls_rif."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$lo_object[$li_totrow][7]="<div align=center><input type=hidden name=txtcodret".$li_totrow."       id=txtcodret".$li_totrow."       value='".$ls_codret."'       class=sin-borde readonly style=text-align:center size=1 maxlength=1></div>";
			}
			$io_sql->free_result($rs_data);
			$io_grid->makegrid($li_totrow,$lo_title,$lo_object,750,'Comprobantes de Retención Unificado','grid');
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesunificadas
//-----------------------------------------------------------------------------------------------------------------------------------------

?>
