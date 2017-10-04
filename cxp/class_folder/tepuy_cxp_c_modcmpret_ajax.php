<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("../../shared/class_folder/class_datastore.php");
	$io_dscuentas=new class_datastore(); // Datastored de cuentas contables
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		

	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	if($ls_proceso=="BUSCARORDENRET")
	{
	// numero de orden de pago
	$ls_numsol=$io_funciones_cxp->uf_obtenervalor("numsol","");
	// fecha(registro) de inicio de busqueda
	$ld_fecemides=$io_funciones_cxp->uf_obtenervalor("fecemides","");
	// fecha(registro) de fin de busqueda
	$ld_fecemihas=$io_funciones_cxp->uf_obtenervalor("fecemihas","");
	// fecha de emision de la retencion
	$ls_fechaemision=$io_funciones_cxp->uf_obtenervalor("fechaemision","");
	}
	else
	{
	// total de filas de recepciones
	$li_totrowrecepciones=$io_funciones_cxp->uf_obtenervalor("totrowrecepciones",1);
	// numero del comprobante 
	$ls_numcom=$io_funciones_cxp->uf_obtenervalor("numcom","");
	}
	$ls_codret=$io_funciones_cxp->uf_obtenervalor("codret","");

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
		case "BUSCARORDENRET":
			//print "codret: ".$ls_codret;
			uf_print_solicitudesreten($ls_numsol,$ls_codret,$ld_fecemides,$ld_fecemihas,$ls_tipooperacion);
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
		//	  Description: M�todo que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 19/04/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_dscuentas;
		
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
			$ls_codret=trim($io_funciones_cxp->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_funciones_cxp->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_funciones_cxp->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_funciones_cxp->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_funciones_cxp->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_funciones_cxp->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_funciones_cxp->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_funciones_cxp->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_funciones_cxp->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_funciones_cxp->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_porret=trim($io_funciones_cxp->uf_obtenervalor("txtporret".$li_fila,"0.00"));
			$ls_totimp=trim($io_funciones_cxp->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_funciones_cxp->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_numsop=trim($io_funciones_cxp->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_funciones_cxp->uf_obtenervalor("txtnumdoc".$li_fila,""));
			

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$io_funciones->uf_cerosizquierda($li_fila,10)."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."'>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' >";
			$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' >";
			$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Cr�ditos !!!' width=15 height=15 border=0></a>";
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
		//	  Description: M�todo que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 19/04/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_dscuentas;
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
			$ls_codret=trim($io_funciones_cxp->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_funciones_cxp->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_funciones_cxp->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_funciones_cxp->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_funciones_cxp->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_funciones_cxp->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_funciones_cxp->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_funciones_cxp->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_funciones_cxp->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_funciones_cxp->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_totimp=trim($io_funciones_cxp->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_funciones_cxp->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_porret=trim($io_funciones_cxp->uf_obtenervalor("txtporret".$li_fila,"0,00"));
			$ls_numsop=trim($io_funciones_cxp->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_funciones_cxp->uf_obtenervalor("txtnumdoc".$li_fila,""));

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numope."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' readonly>";
			$lo_object[$li_fila][5]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][6]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][7]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Cr�ditos !!!' width=15 height=15 border=0></a>";
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
		//	    Arguments: as_numsol  // N�mero de Solicitud
		//                 ai_total   // Total de la Solicitud
		//	  Description: M�todo que busca las recepciones de documento asociadas y las imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 29/04/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp;

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
			$lo_object[$li_fila][8]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly; ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.png alt='Buscar Otros Cr�ditos !!!' width=15 height=15 border=0></a>";
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
	function uf_print_solicitudesreten($as_numsol,$as_codret,$ad_fecemides,$ad_fecemihas,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes
		//		   Access: private
		//		 Argument: as_numsol        // Numero de la solicitud de orden de Pago
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipproben     // Tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: M�todo que impirme el grid de las solicitudes a ser aprobadas o para reversar la aprovaci�n
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 02/05/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Nro. Orden de Pago";
		$lo_title[3]="Fecha Emision";
		$lo_title[4]="Proveedor / Beneficiario";
		//$lo_title[5]="Estatus de Aprobacion";
		$lo_title[5]="Monto Neto a Cobrar";
		$lo_title[6]="Monto Obj. Ret.";
		$lo_title[7]="Monto Retencion";
		$lo_title[8]="";
		$lo_title[9]="";
		$ad_fecemides=$io_funciones->uf_convertirdatetobd($ad_fecemides);
		$ad_fecemihas=$io_funciones->uf_convertirdatetobd($ad_fecemihas);
		$as_numsol="%".$as_numsol."%";
		$as_proben="%".$as_proben."%";
		require_once("tepuy_cxp_c_modcmpret.php");
		$io_modcmpret=new tepuy_cxp_c_modcmpret("../../");
		//print "codigo de la retencion: ".$as_codret;
		$rs_datasol=$io_modcmpret->uf_load_solicitudesreten($as_numsol,$as_codret,$ad_fecemides,$ad_fecemihas,$as_tipooperacion);
		$li_fila=0;
		while($row=$io_modcmpret->io_sql->fetch_row($rs_datasol))
		{
			$li_fila=$li_fila + 1;
			$ls_numsol=$row["numsol"];
			$ld_fecemisol=$row["fecemisol"];
			$ls_estprosol=$row["estprosol"];
			$ls_estaprosol=$row["estaprosol"];
			$ls_proben=utf8_encode($row["nombre"]);
			$li_monsol=number_format($row["monsol"],2,',','.');
			$li_monobjret=number_format($row["monobjret"],2,',','.');
			$li_monret=number_format($row["monret"],2,',','.');
			$tipoproben=$row["tipproben"];
			if($tipoproben=="P")
			{
				$cedcodpro=$row["cod_pro"];
			}
			else
			{
				$cedcodpro=$row["ced_bene"];
			}
			//print "codigo: ".$cedcodpro." Tipo: ".$tipoproben;
			if($ls_estaprosol==0)
			{
				$ls_estatus="No Aprobada";
			}
			else
			{
				$ls_estatus="Aprobada";
			}
			$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
			$lo_object[$li_fila][2]="<input type=text name=txtnumsol".$li_fila."    id=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_numsol."'    readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecemisol".$li_fila." id=txtfecemisol".$li_fila." class=sin-borde style=text-align:left   size=15 value='".$ld_fecemisol."' readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    id=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 value='".$ls_proben."'    readonly>"; 
		//	$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    id=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_estatus."'   readonly>";
			$lo_object[$li_fila][5]="<input type=text name=txtmonsol".$li_fila."    id=txtmonsol".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monsol."' 	  readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmonobjret".$li_fila."    id=txtobjret".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monobjret."' 	  readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtmonret".$li_fila."    id=txtmonret".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monret."' 	  readonly>";
			$lo_object[$li_fila][8]="<input type=hidden name=cedcodpro".$li_fila."  id=cedcodpro".$li_fila."    class=sin-borde style=text-align:right  size=0 value='".$cedcodpro."' 	  readonly>";
			$lo_object[$li_fila][9]="<input type=hidden name=tipoproben".$li_fila."    id=tipoproben".$li_fila."    class=sin-borde style=text-align:right  size=0 value='".$tipoproben."' 	  readonly>";
		}
		//print "Fila: ".$li_fila;
		if($li_fila==="0")
		{
			$io_aprobacion->io_mensajes->message("No se encontraron ordenes de pago para aplicar este tipo de retenci�n");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
			$lo_object[$li_fila][2]="<input type=text name=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecemisol".$li_fila." class=sin-borde style=text-align:left   size=15 readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 readonly>"; 
		//	$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
			$lo_object[$li_fila][5]="<input type=text name=txtmonsol".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmonobjret".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtmonret".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
			$lo_object[$li_fila][8]="<input type=hidden name=cedcodpro".$li_fila."     class=sin-borde style=text-align:right  size=0 readonly>";
			$lo_object[$li_fila][9]="<input type=hidden name=tipoproben".$li_fila."     class=sin-borde style=text-align:right  size=0 readonly>";
		}

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Listado de Ordenes de Pago","gridsolicitudes");
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

?>
