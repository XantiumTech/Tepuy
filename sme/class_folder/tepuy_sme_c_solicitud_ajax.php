<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_sme.php");
	$io_funciones_sme=new class_funciones_sme();
	// tipo de SEP si es de BIENES � de SERVICIOS
	$ls_tipo=$io_funciones_sme->uf_obtenervalor("tipo","-");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_sme->uf_obtenervalor("proceso","");
	// total de filas de Bienes
	$li_totalbienes=$io_funciones_sme->uf_obtenervalor("totalbienes","1");
	// total de filas de Servicios
	$li_totalservicios=$io_funciones_sme->uf_obtenervalor("totalservicios","1");
	// total de filas de Servicios
	$li_totalconceptos=$io_funciones_sme->uf_obtenervalor("totalconceptos","1");
	// total de filas de Cargos
	$li_totalcargos=$io_funciones_sme->uf_obtenervalor("totalcargos","1");
	// total de filas de Cuentas
	$li_totalcuentas=$io_funciones_sme->uf_obtenervalor("totalcuentas","1");
	// total de filas de Cuentas cargos
	$li_totalcuentascargo=$io_funciones_sme->uf_obtenervalor("totalcuentascargo","1");
	// Indica si se deben cargar los cargos de un bien � servicios � si solo se deben pintar
	$ls_cargarcargos=$io_funciones_sme->uf_obtenervalor("cargarcargos","1");
	// Valor del Subtotal de la SEP
	$li_subtotal=$io_funciones_sme->uf_obtenervalor("subtotal","0,00");
	// Valor del Cargo de la SEP
	$li_cargos=$io_funciones_sme->uf_obtenervalor("cargos","0,00");
	// Valor del Total de la SEP
	$li_total=$io_funciones_sme->uf_obtenervalor("total","0,00");
	// N�mero de solicitud si se va a cargar
	$ls_numsol=$io_funciones_sme->uf_obtenervalor("numsol","");
	$ls_codivasel = $io_funciones_sme->uf_obtenervalor("codivasel","");
	//print "codigo iva: ".$ls_codivasel;
	$ls_titulo="";
	$la_cuentacargo[0]="";
	$li_cuenta=1;
	
	$ls_tipafeiva=$_SESSION["la_empresa"]["confiva"];
	//print "tipo: ".$ls_proceso;
	switch($ls_proceso)
	{

		case "LIMPIARSME":
			// Conceptos
			$ls_titulo="Conceptos";
			uf_print_conceptos($li_totalconceptos);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"O");
			uf_print_cuentas_gasto($li_totalcuentas,"O");
			if ($ls_tipafeiva=='P')
			{
				uf_print_cuentas_cargo($li_totalcuentascargo,$ls_cargarcargos,"O");
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total);
			break;

			
		case "AGREGARCONCEPTOS":
			$ls_titulo="Conceptos";
			uf_print_conceptos($li_totalconceptos);
			//uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"O");
			uf_print_creditos_iva($ls_titulo,$li_totalcargos,$ls_cargarcargos,"O",$ls_codivasel);
			uf_print_cuentas_gasto($li_totalcuentas,"O");
			if ($ls_tipafeiva=='P')
			{
			uf_print_cuentas_cargo($li_totalcuentascargo,$ls_cargarcargos,"O");
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total);
			break;

		case "LOADCONCEPTOS":
			$ls_titulo="Conceptos";
			uf_load_conceptos($ls_numsol);
			uf_load_creditos($ls_titulo,$ls_numsol,"O");
			uf_load_cuentas($ls_numsol,"O");
			if ($ls_tipafeiva=='P')
			{
			uf_load_cuentas_cargo($ls_numsol,"O");
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: M�todo que imprime el grid de los Bienes
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad de Medida";
		$lo_title[5]="Unidad";
		$lo_title[6]="Precio/Unid.";
		$lo_title[7]="Sub-Total";
		$lo_title[8]="Cargos"; 
		$lo_title[9]="Total";
		$lo_title[10]="";
		// Recorrido de todos los Bienes del Grid
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codart=$io_funciones_sme->uf_obtenervalor("txtcodart".$li_fila,"");
			$ls_denart=$io_funciones_sme->uf_obtenervalor("txtdenart".$li_fila,"");
			$li_canart=$io_funciones_sme->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			$ls_unimed=$io_funciones_sme->uf_obtenervalor("txtdenunimed".$li_fila,"");
			$ls_unidad=$io_funciones_sme->uf_obtenervalor("cmbunidad".$li_fila,"M");
			$li_preart=$io_funciones_sme->uf_obtenervalor("txtpreart".$li_fila,"0,00");
			$li_subtotart=$io_funciones_sme->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
			$li_carart=$io_funciones_sme->uf_obtenervalor("txtcarart".$li_fila,"0,00");
			$li_totart=$io_funciones_sme->uf_obtenervalor("txttotart".$li_fila,"0,00");
			$ls_spgcuenta=$io_funciones_sme->uf_obtenervalor("txtspgcuenta".$li_fila,"");
			$li_unidad=$io_funciones_sme->uf_obtenervalor("txtunidad".$li_fila,"");
			if($ls_unidad=="M") // Si es al Mayor
			{
				$ls_maysel="selected";
				$ls_detsel="";
			}
			else // Si es al Detal
			{
				$ls_maysel="";
				$ls_detsel="selected";
			}
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22  value='".$ls_codart."'    readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left    size=20 value='".$ls_denart."'    readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right   size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center size=10  value='".$ls_unimed."' title='".$ls_unimed."' readonly>"; 
			$lo_object[$li_fila][5]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][6]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right   size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][7]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right   size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right   size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][9]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right   size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."     value='".$li_unidad."'>";
			if($li_fila==$ai_total)// si el la �ltima fila no pinto el eliminar
			{
				$lo_object[$li_fila][10]="";
			}
			else
			{
				$lo_object[$li_fila][10]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='895' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,895,"Detalle de Bienes","gridbienes");
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: M�todo que imprime el grid de los servicios
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad de Medida";
		$lo_title[5]="Precio";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cargos";
		$lo_title[8]="Total";
		$lo_title[9]="";
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codser=$io_funciones_sme->uf_obtenervalor("txtcodser".$li_fila,"");
			$ls_denser=$io_funciones_sme->uf_obtenervalor("txtdenser".$li_fila,"");
			$li_canser=$io_funciones_sme->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			$ls_unimed=$io_funciones_sme->uf_obtenervalor("txtdenunimed".$li_fila,"");
			$li_preser=$io_funciones_sme->uf_obtenervalor("txtpreser".$li_fila,"0,00");
			$li_subtotser=$io_funciones_sme->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
			$li_carser=$io_funciones_sme->uf_obtenervalor("txtcarser".$li_fila,"0,00");
			$li_totser=$io_funciones_sme->uf_obtenervalor("txttotser".$li_fila,"0,00");
			$ls_spgcuenta=$io_funciones_sme->uf_obtenervalor("txtspgcuenta".$li_fila,"");
			/*if($ls_unimed=="")
			{
			 $ls_unimed="--";
			}*/
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center size=10  value='".$ls_unimed."' title='".$ls_unimed."' readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			if($li_fila==$ai_total)// si el la �ltima fila no pinto el eliminar
			{
				$lo_object[$li_fila][9]="";
			}
			else
			{
				$lo_object[$li_fila][9] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_conceptos($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: M�todo que imprime el grid de los conceptos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Monto";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";

		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codcon=$io_funciones_sme->uf_obtenervalor("txtcodcon".$li_fila,"");
			$ls_dencon=$io_funciones_sme->uf_obtenervalor("txtdencon".$li_fila,"");
			$ld_cancon=$io_funciones_sme->uf_obtenervalor("txtcancon".$li_fila,"0,00");   
			$ld_precon=$io_funciones_sme->uf_obtenervalor("txtprecon".$li_fila,"0,00");    
			$ld_subtotcon=$io_funciones_sme->uf_obtenervalor("txtsubtotcon".$li_fila,"0,00");
			$ld_totcon=$io_funciones_sme->uf_obtenervalor("txttotcon".$li_fila,"0,00");    
			$ld_carcon=$io_funciones_sme->uf_obtenervalor("txtcarcon".$li_fila,"0,00");
			$ls_spgcuenta=$io_funciones_sme->uf_obtenervalor("txtspgcuenta".$li_fila,"");		
			
			$lo_object[$li_fila][1]="<input name=txtcodcon".$li_fila."     type=text id=txtcodcon".$li_fila."     class=sin-borde   size=15 value='".$ls_codcon."'     style=text-align:center readonly>";
			$lo_object[$li_fila][2]="<input name=txtdencon".$li_fila."     type=text id=txtdencon".$li_fila."     class=sin-borde   size=30 value='".$ls_dencon."'     style=text-align:left   readonly>";
			$lo_object[$li_fila][3]="<input name=txtcancon".$li_fila."     type=text id=txtcancon".$li_fila."     class=sin-borde   size=9  value='".$ld_cancon."'     style=text-align:right onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][4]="<input name=txtprecon".$li_fila."     type=text id=txtprecon".$li_fila."     class=sin-borde   size=15 value='".$ld_precon."'     style=text-align:right  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input name=txtsubtotcon".$li_fila."  type=text id=txtsubtotcon".$li_fila."  class=sin-borde   size=15 value='".$ld_subtotcon."'  style=text-align:right  readonly>";
			$lo_object[$li_fila][6]="<input name=txtcarcon".$li_fila."     type=text id=txtcarcon".$li_fila."     class=sin-borde   size=10 value='".$ld_carcon."'     style=text-align:right  readonly>";
			$lo_object[$li_fila][7]="<input name=txttotcon".$li_fila."     type=text id=txttotcon".$li_fila."     class=sin-borde   size=15 value='".$ld_totcon."'     style=text-align:right  readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta value='".$ls_spgcuenta."'>";
			if($li_fila==$ai_total)// si el la �ltima fila no pinto el eliminar
			{
				$lo_object[$li_fila][8]="";
			}
			else
			{
				$lo_object[$li_fila][8]="<a href=javascript:ue_delete_conceptos('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoconceptos();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Detalle Conceptos'>Agregar Detalle Conceptos</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Conceptos","gridconceptos");
	}// end function uf_print_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos_iva($as_titulo,$ai_total,$as_cargarcargos,$as_tipo,$as_codivasel)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_cargarcargos // Si cargamos los cargos � solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que imprime el grid de cr�ditos y busca los creditos de un Bien, un Servicio � un concepto
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo, $li_cuenta;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto del Cargo";
		$lo_title[6]="Sub-Total";
		$lo_title[7]=""; 
		$lo_object[0]="";
		// Recorrido de el grid de Cargos
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codservic=$io_funciones_sme->uf_obtenervalor("txtcodservic".$li_fila,"");
			$ls_codcar=$io_funciones_sme->uf_obtenervalor("txtcodcar".$li_fila,"");
			$ls_dencar=$io_funciones_sme->uf_obtenervalor("txtdencar".$li_fila,"");
			$li_bascar=$io_funciones_sme->uf_obtenervalor("txtbascar".$li_fila,"");
			$li_moncar=$io_funciones_sme->uf_obtenervalor("txtmoncar".$li_fila,"");
			$li_subcargo=$io_funciones_sme->uf_obtenervalor("txtsubcargo".$li_fila,"");
			$ls_spg_cuenta=$io_funciones_sme->uf_obtenervalor("cuentacargo".$li_fila,"");
			$ls_formula=$io_funciones_sme->uf_obtenervalor("formulacargo".$li_fila,"");
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
			$lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		if ($as_tipo=='B')
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S')
		   {
		     $li_size = 840;
		   }
		elseif($as_tipo=='O')
		   {
		     $li_size = 840;
		   }
		if($as_cargarcargos=="1")
		{	// Si se deben cargar los cargos Buscamos el C�digo del �ltimo Bien cargado 
			// y obtenemos los cargos de dicho Bien
			require_once("tepuy_sme_c_solicitud.php");
			$io_solicitud=new tepuy_sme_c_solicitud("../../");
			$ls_codigo=$io_funciones_sme->uf_obtenervalor("txtcodservic","");
			$ls_codprounidad=$io_funciones_sme->uf_obtenervalor("codprounidad","");
			$ls_codprounidad=$io_solicitud->uf_load_buscarcuentacargo($as_codivasel);
			switch ($as_tipo)
			{
				case "B":
					$rs_data = $io_solicitud->uf_load_cargosbienes($ls_codigo,$ls_codprounidad);
					break;
				case "S":
					$rs_data = $io_solicitud->uf_load_cargosservicios($ls_codigo,$ls_codprounidad);
					break;
				case "O":
					//$rs_data = $io_solicitud->uf_load_cargosconceptos($ls_codigo,$ls_codprounidad);
					$rs_data = $io_solicitud->uf_load_cargosconceptos($ls_codigo,$ls_codprounidad,$as_codivasel);
					break;
			}
			while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
			{
				$lb_existecargo=true;
				//$ls_codservic=$row["codigo"];
				$ls_codservic=$ls_codigo;
				$ls_codcar=$row["codcar"];
				$ls_dencar=$row["dencar"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_formula=$row["formula"];
				$li_bascar="0,00";
				$li_moncar="0,00";
				$li_subcargo="0,00";
				$ls_existecuenta=$row["existecuenta"];
				if($ls_spg_cuenta!="")
				{// Si la cuenta presupuestaria es diferente de blanco llenamos un arreglo de cuentas
					$la_cuentacargo[$li_cuenta]["cargo"]=$ls_codcar;
					$la_cuentacargo[$li_cuenta]["cuenta"]=$ls_spg_cuenta;
					if($ls_existecuenta==0)
					{
						$la_cuentacargo[$li_cuenta]["programatica"]="";
					}
					else
					{
						$la_cuentacargo[$li_cuenta]["programatica"]=$ls_codprounidad;
					}
					$li_cuenta++;
				}
				$ai_total++;
				$lo_object[$ai_total][1]="<input name=txtcodservic".$ai_total." type=text id=txtcodservic".$ai_total." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>";
				$lo_object[$ai_total][2]="<input name=txtcodcar".$ai_total."    type=text id=txtcodcar".$ai_total."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
				$lo_object[$ai_total][3]="<input name=txtdencar".$ai_total."    type=text id=txtdencar".$ai_total."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
				$lo_object[$ai_total][4]="<input name=txtbascar".$ai_total."    type=text id=txtbascar".$ai_total."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
				$lo_object[$ai_total][5]="<input name=txtmoncar".$ai_total."    type=text id=txtmoncar".$ai_total."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
				$lo_object[$ai_total][6]="<input name=txtsubcargo".$ai_total."  type=text id=txtsubcargo".$ai_total."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
										 "<input name=cuentacargo".$ai_total."  type=hidden id=cuentacargo".$ai_total."  value='".$ls_spg_cuenta."'>".
										 "<input name=formulacargo".$ai_total." type=hidden id=formulacargo".$ai_total." value='".$ls_formula."'>";
				$lo_object[$ai_total][7]="<a href=javascript:ue_delete_cargos('".$ai_total."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			}
		}		
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,$li_size,"Cr&eacute;ditos","gridcreditos");
		unset($io_solicitud);		
	}// end function uf_print_creditos_iva
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos($as_titulo,$ai_total,$as_cargarcargos,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_cargarcargos // Si cargamos los cargos � solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que imprime el grid de cr�ditos y busca los creditos de un Bien, un Servicio � un concepto
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo, $li_cuenta;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto del Cargo";
		$lo_title[6]="Sub-Total";
		$lo_title[7]=""; 
		$lo_object[0]="";
		// Recorrido de el grid de Cargos
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codservic=$io_funciones_sme->uf_obtenervalor("txtcodservic".$li_fila,"");
			$ls_codcar=$io_funciones_sme->uf_obtenervalor("txtcodcar".$li_fila,"");
			$ls_dencar=$io_funciones_sme->uf_obtenervalor("txtdencar".$li_fila,"");
			$li_bascar=$io_funciones_sme->uf_obtenervalor("txtbascar".$li_fila,"");
			$li_moncar=$io_funciones_sme->uf_obtenervalor("txtmoncar".$li_fila,"");
			$li_subcargo=$io_funciones_sme->uf_obtenervalor("txtsubcargo".$li_fila,"");
			$ls_spg_cuenta=$io_funciones_sme->uf_obtenervalor("cuentacargo".$li_fila,"");
			$ls_formula=$io_funciones_sme->uf_obtenervalor("formulacargo".$li_fila,"");
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
			$lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		if ($as_tipo=='B')
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S')
		   {
		     $li_size = 840;
		   }
		elseif($as_tipo=='O')
		   {
		     $li_size = 840;
		   }
		if($as_cargarcargos=="1")
		{	// Si se deben cargar los cargos Buscamos el C�digo del �ltimo Bien cargado 
			// y obtenemos los cargos de dicho Bien
			require_once("tepuy_sme_c_solicitud.php");
			$io_solicitud=new tepuy_sme_c_solicitud("../../");
			$ls_codigo=$io_funciones_sme->uf_obtenervalor("txtcodservic","");
			$ls_codprounidad=$io_funciones_sme->uf_obtenervalor("codprounidad","");
			switch ($as_tipo)
			{
				case "B":
					$rs_data = $io_solicitud->uf_load_cargosbienes($ls_codigo,$ls_codprounidad);
					break;
				case "S":
					$rs_data = $io_solicitud->uf_load_cargosservicios($ls_codigo,$ls_codprounidad);
					break;
				case "O":
					$rs_data = $io_solicitud->uf_load_cargosconceptos($ls_codigo,$ls_codprounidad);
					break;
			}
			while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
			{
				$lb_existecargo=true;
				$ls_codservic=$row["codigo"];
				$ls_codcar=$row["codcar"];
				$ls_dencar=$row["dencar"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_formula=$row["formula"];
				$li_bascar="0,00";
				$li_moncar="0,00";
				$li_subcargo="0,00";
				$ls_existecuenta=$row["existecuenta"];
				if($ls_spg_cuenta!="")
				{// Si la cuenta presupuestaria es diferente de blanco llenamos un arreglo de cuentas
					$la_cuentacargo[$li_cuenta]["cargo"]=$ls_codcar;
					$la_cuentacargo[$li_cuenta]["cuenta"]=$ls_spg_cuenta;
					if($ls_existecuenta==0)
					{
						$la_cuentacargo[$li_cuenta]["programatica"]="";
					}
					else
					{
						$la_cuentacargo[$li_cuenta]["programatica"]=$ls_codprounidad;
					}
					$li_cuenta++;
				}
				$ai_total++;
				$lo_object[$ai_total][1]="<input name=txtcodservic".$ai_total." type=text id=txtcodservic".$ai_total." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>";
				$lo_object[$ai_total][2]="<input name=txtcodcar".$ai_total."    type=text id=txtcodcar".$ai_total."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
				$lo_object[$ai_total][3]="<input name=txtdencar".$ai_total."    type=text id=txtdencar".$ai_total."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
				$lo_object[$ai_total][4]="<input name=txtbascar".$ai_total."    type=text id=txtbascar".$ai_total."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
				$lo_object[$ai_total][5]="<input name=txtmoncar".$ai_total."    type=text id=txtmoncar".$ai_total."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
				$lo_object[$ai_total][6]="<input name=txtsubcargo".$ai_total."  type=text id=txtsubcargo".$ai_total."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
										 "<input name=cuentacargo".$ai_total."  type=hidden id=cuentacargo".$ai_total."  value='".$ls_spg_cuenta."'>".
										 "<input name=formulacargo".$ai_total." type=hidden id=formulacargo".$ai_total." value='".$ls_formula."'>";
				$lo_object[$ai_total][7]="<a href=javascript:ue_delete_cargos('".$ai_total."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			}
		}		
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,$li_size,"Cr&eacute;ditos","gridcreditos");
		unset($io_solicitud);		
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_gasto($ai_total,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_gasto
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Estructura Programatica";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		$lo_title[4]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			//$ls_codpro=trim($io_funciones_sme->uf_obtenervalor("txtcodprogas".$li_fila,""));
			$ls_codpro=trim($io_funciones_sme->uf_obtenervalor("txtcodprogas".$li_fila,""));
		//$ls_codpro=substr($ls_codpro,18,2);
			$ls_cuenta=trim($io_funciones_sme->uf_obtenervalor("txtcuentagas".$li_fila,""));
			$li_moncue=trim($io_funciones_sme->uf_obtenervalor("txtmoncuegas".$li_fila,"0,00"));
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);							
			if($ls_cuenta!="")
			{
				$io_dscuentas->insertRow("codprogas",$ls_codpro);			
				$io_dscuentas->insertRow("cuentagas",$ls_cuenta);			
				$io_dscuentas->insertRow("moncuegas",$li_moncue);			
			}
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codprogas','1'=>'cuentagas'),array('0'=>'moncuegas'),'moncuegas');
		$li_total=$io_dscuentas->getRowCount('codprogas');	
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codpro=$io_dscuentas->getValue('codprogas',$li_fila);
			$ls_cuenta=$io_dscuentas->getValue('cuentagas',$li_fila);
			$li_moncue=number_format($io_dscuentas->getValue('moncuegas',$li_fila),2,",",".");
			$ls_codest1=substr($ls_codpro,0,20);
			$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
			$ls_codest2=substr($ls_codpro,20,6);
			$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
			$ls_codest3=substr($ls_codpro,26,3);
			$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
			$ls_codest4=substr($ls_codpro,29,2);
			$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
			$ls_codest5=substr($ls_codpro,31,2);
			$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
				$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
				$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>".
										"<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codpro."'>";
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtprogramaticagas".$ai_total." type=text id=txtprogramaticagas".$ai_total." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtcuentagas".$ai_total."       type=text id=txtcuentagas".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtmoncuegas".$ai_total."       type=text id=txtmoncuegas".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtcodprogas".$ai_total."       type=hidden id=txtcodprogas".$ai_total."  value=''>";        
	    print "<p>&nbsp;</p>";
		if ($as_tipo=='B') 
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S' || $as_tipo=='O')
		   { 
		     $li_size = 840;
		   }
		print "  <table width='".$li_size."' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,$li_size,"Cuentas","gridcuentas");
		unset($io_dscuentas);
	}// end function uf_print_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargo($ai_total,$as_cargarcargos,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_cargarcargos // Si cargamos los cargos � solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Cargo";
		$lo_title[2]="Estructura Programatica";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		$lo_title[5]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias del Cargo
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_cargo=trim($io_funciones_sme->uf_obtenervalor("txtcodcargo".$li_fila,""));
			$ls_codpro=trim($io_funciones_sme->uf_obtenervalor("txtcodprocar".$li_fila,""));
			$ls_cuenta=trim($io_funciones_sme->uf_obtenervalor("txtcuentacar".$li_fila,""));
			$li_moncue=trim($io_funciones_sme->uf_obtenervalor("txtmoncuecar".$li_fila,"0,00"));
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);							
			if($ls_cuenta!="")
			{
				$io_dscuentas->insertRow("codcargo",$ls_cargo);			
				$io_dscuentas->insertRow("codprocar",$ls_codpro);			
				$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
				$io_dscuentas->insertRow("moncuecar",$li_moncue);			
			}
		}
		if($as_cargarcargos=="1")
		{	
			$li_cuenta=count($la_cuentacargo)-1;
			for($li_fila=1;($li_fila<=$li_cuenta);$li_fila++)
			{
				$ls_cargo=trim($la_cuentacargo[$li_fila]["cargo"]);
				$ls_cuenta=trim($la_cuentacargo[$li_fila]["cuenta"]);
				$ls_programatica=trim($la_cuentacargo[$li_fila]["programatica"]);
				$li_moncue="0.00";
				if($ls_cuenta!="")
				{
					$io_dscuentas->insertRow("codcargo",$ls_cargo);			
					$io_dscuentas->insertRow("codprocar",$ls_programatica);			
					$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
					$io_dscuentas->insertRow("moncuecar",$li_moncue);
				}			
			}
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codprocar','2'=>'cuentacar'),array('0'=>'moncuecar'),'moncuecar');
		$li_total=$io_dscuentas->getRowCount('codcargo');	
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_cargo=$io_dscuentas->getValue('codcargo',$li_fila);
			$ls_codpro=$io_dscuentas->getValue('codprocar',$li_fila);
			$ls_cuenta=$io_dscuentas->getValue('cuentacar',$li_fila);
			$li_moncue=number_format($io_dscuentas->getValue('moncuecar',$li_fila),2,",",".");
			$ls_codest1=substr($ls_codpro,0,20);
			$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
			$ls_codest2=substr($ls_codpro,20,6);
			$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
			$ls_codest3=substr($ls_codpro,26,3);
			$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
			$ls_codest4=substr($ls_codpro,29,2);
			$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
			$ls_codest5=substr($ls_codpro,31,2);
			$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$ls_cargo."' readonly>";
				$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
				$lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>".
										"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codpro."'>";
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtcodcargo".$ai_total." type=text id=txtcodcargo".$ai_total." class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtprogramaticacar".$ai_total." type=text id=txtprogramaticacar".$ai_total." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtcuentacar".$ai_total."       type=text id=txtcuentacar".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtmoncuecar".$ai_total."       type=text id=txtmoncuecar".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][5]="<input name=txtcodprocar".$ai_total."       type=hidden id=txtcodprocar".$ai_total."  value=''>";        

		print "<p>&nbsp;</p>";
		if ($as_tipo=='B') 
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S' || $as_tipo=='O')
		   { 
		     $li_size = 840;
		   }
		print "  <table width='".$li_size."' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Cargos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,$li_size,"Cuentas Cargos","gridcuentascargos");
		unset($io_dscuentas);
	}// end function uf_print_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_subtotal,$ai_cargos,$ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal // Valor del subtotal
		//				   ai_cargos // Valor total de los cargos
		//				   ai_total // Total de la solicitu de pago
		//	  Description: M�todo que imprime los totales de la SEP
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='840' height='116' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
		print "        <tr class='titulo-celdanew'>";
		print "          <td height='22' colspan='4'><div align='center'>Totales</div></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='128' height='13'>&nbsp;</td>";
		print "          <td width='113' height='13' align='left'></td>";
		print "          <td width='368' height='13' align='right'><div align='right'></div></td>";
		print "          <td width='239' height='13' align='left'>&nbsp;</td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><strong>Subtotal&nbsp;&nbsp;</strong></td>";
		print "          <td height='22'><input name='txtsubtotal'  type='text' class='titulo-conect' id='txtsubtotal' style='text-align:right' value='".$ai_subtotal."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><div align='right'><strong>Otros Cr&eacute;ditos&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txtcargos' type='text' class='titulo-conect' id='txtcargos' style='text-align:right' value='".$ai_cargos."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotal' type='text' class='titulo-conect' id='txttotal' style='text-align:right' value='".$ai_total."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
	}// end function uf_print_total
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: M�todo que busca los bienes de la solicitud y los imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad de Medida";
		$lo_title[5]="Unidad";
		$lo_title[6]="Precio/Unid.";
		$lo_title[7]="Sub-Total";
		$lo_title[8]="Cargos"; 
		$lo_title[9]="Total";
		$lo_title[10]="";
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_bienes($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codart=trim($row["codart"]);
			$ls_denart=utf8_encode($row["denart"]);
			$ls_unimed=$row["denunimed"];
			$ls_unidad=$row["unidad"];
			$li_canart=$row["canart"];
			$li_preart=$row["monpre"];
			$li_totart=$row["monart"];
			$ls_spgcuenta=$row["spg_cuenta"];
			$li_unimed=$row["unimed"];
			if($ls_unidad=="M") // Si es al Mayor
			{
				$ls_maysel="selected";
				$ls_detsel="";
				$li_subtotart=$li_preart*($li_canart*$li_unimed);
			}
			else // Si es al Detal
			{
				$ls_maysel="";
				$ls_detsel="selected";
				$li_subtotart=$li_preart*$li_canart;
			}
			$li_carart=$li_totart-$li_subtotart;
			$li_subtotart=number_format($li_subtotart,2,",",".");
			$li_totart=number_format($li_totart,2,",",".");
			$li_canart=number_format($li_canart,2,",",".");
			$li_preart=number_format($li_preart,2,",",".");
			$li_carart=number_format($li_carart,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left    size=20 value='".$ls_denart."'  readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right   size=8  value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center  size=10  value='".$ls_unimed."' readonly>"; 			
			$lo_object[$li_fila][5]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][6]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][7]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][9]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."     value='".$li_unimed."'>";
			$lo_object[$li_fila][10]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value=''  readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value=''  readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value=''   onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center  size=10  value='' readonly>"; 			
  	    $lo_object[$li_fila][5]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
		$lo_object[$li_fila][6]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
		$lo_object[$li_fila][7]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value=''  readonly>";
		$lo_object[$li_fila][8]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  readonly>";
		$lo_object[$li_fila][9]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value=''  readonly>".
								" <input type=hidden name=txtspgcuenta".$li_fila."  value=''> ".
								" <input type=hidden name=txtunidad".$li_fila."     value=''>";
		$lo_object[$li_fila][10]="";
		print "<p>&nbsp;</p>";
		print "  <table width='895' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.png' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_solicitud);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,895,"Detalle de Bienes","gridbienes");
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: M�todo que busca los servicios de la solicitud y los imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad de Medida";
		$lo_title[5]="Precio";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cargos";
		$lo_title[8]="Total";
		$lo_title[9]="";
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_servicios($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codser=$row["codser"];
			$ls_denser=utf8_encode($row["denser"]);
			$li_canser=$row["canser"];
			$ls_unimed=$row["denunimed"];
			$li_preser=$row["monpre"];
			$li_subtotser=$li_preser*$li_canser;
			$li_totser=$row["monser"];
			$li_carser=$li_totser-$li_subtotser;
			$ls_spgcuenta=$row["spg_cuenta"];
			$li_canser=number_format($li_canser,2,",",".");
			$li_preser=number_format($li_preser,2,",",".");
			$li_subtotser=number_format($li_subtotser,2,",",".");
			$li_carser=number_format($li_carser,2,",",".");
			$li_totser=number_format($li_totser,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
		    $lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center  size=10  value='".$ls_unimed."' readonly>"; 			
			$lo_object[$li_fila][5]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			$lo_object[$li_fila][9] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtdenunimed".$li_fila." class=sin-borde style=text-align:center  size=10  value='' readonly>"; 			
		$lo_object[$li_fila][5]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
		$lo_object[$li_fila][6]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][8]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''> ";
		$lo_object[$li_fila][9] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_solicitud);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: M�todo que busca los conceptos de la solicitud y los imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Monto";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_conceptos($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codcon=$row["codconsep"];
			$ls_dencon=utf8_encode($row["denconsep"]);
			$li_cancon=$row["cancon"];
			$li_precon=$row["monpre"];
			$li_subtotcon=$li_precon*$li_cancon;
			$li_totcon=$row["moncon"];
			$li_carcon=$li_totcon-$li_subtotcon;
			$ls_spgcuenta=$row["spg_cuenta"];
			$li_cancon=number_format($li_cancon,2,",",".");
			$li_precon=number_format($li_precon,2,",",".");
			$li_subtotcon=number_format($li_subtotcon,2,",",".");
			$li_carcon=number_format($li_carcon,2,",",".");
			$li_totcon=number_format($li_totcon,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codcon."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdencon".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_dencon."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcancon".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_cancon."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtprecon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_precon."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotcon".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotcon."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarcon".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carcon."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotcon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totcon."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_conceptos('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdencon".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcancon".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtprecon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
		$lo_object[$li_fila][5]="<input type=text name=txtsubtotcon".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][6]="<input type=text name=txtcarcon".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txttotcon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''> ";
		$lo_object[$li_fila][8] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoconceptos();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Detalle Conceptos'>Agregar Detalle Conceptos</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Conceptos","gridconceptos");
	}// end function uf_load_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_creditos($as_titulo,$as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_numsol  // N�mero de Solicitud
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que busca los creditos de una solicitud y las imprime
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo, $li_cuenta;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto del Cargo";
		$lo_title[6]="Sub-Total";
		$lo_title[7]=""; 
		$lo_object[0]="";
		switch($as_tipo)
		{
			case "B": // Si es de Bienes
			    $li_size  = 895;
				$ls_tabla = "sep_dta_cargos";
				$ls_campo = "codart";
				break;
			case "S": // Si es de Servicios
			    $li_size = 840;
				$ls_tabla = "sep_dts_cargos";
				$ls_campo = "codser";
				break;
			case "O": // Si es de Conceptos
				$li_size = 840;	
				$ls_tabla = "sep_dtc_cargos";
				$ls_campo = "codconsep";
				break;
		}
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_cargos($as_numsol,$ls_tabla,$ls_campo);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codservic=$row["codigo"];
			$ls_codcar=$row["codcar"];
			$ls_dencar=utf8_encode($row["dencar"]);
			$li_bascar=number_format($row["monbasimp"],2,",",".");
			$li_moncar=number_format($row["monimp"],2,",",".");
			$li_subcargo=number_format($row["monto"],2,",",".");
			$ls_spg_cuenta=$row["spg_cuenta"];
			$ls_formula=$row["formula"];
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
			$lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,$li_size,"Cr&eacute;ditos","gridcreditos");
		unset($io_solicitud);		
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: private
		//	    Arguments: as_numsol  // N�mero de Solicitud
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que busca las cuentas presupuestarias asociadas a una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Estructura Programatica";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		$lo_title[4]=""; 
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$io_dscuentas = $io_solicitud->uf_load_cuentas($as_numsol);
		$li_fila=0;
		if($io_dscuentas!=false)
		{
			$li_totrow=$io_dscuentas->getRowCount("spg_cuenta");
			for($li_i=1;($li_i<=$li_totrow);$li_i++)
			{
				$li_monto=$io_dscuentas->data["total"][$li_i];
				if($li_monto>0)
				{
					$li_fila++;
					$ls_codpro=$io_dscuentas->data["codestpro1"][$li_i].$io_dscuentas->data["codestpro2"][$li_i].
							   $io_dscuentas->data["codestpro3"][$li_i].$io_dscuentas->data["codestpro4"][$li_i].
							   $io_dscuentas->data["codestpro5"][$li_i];
					$ls_cuenta=$io_dscuentas->data["spg_cuenta"][$li_i];
					$li_moncue=number_format($io_dscuentas->data["total"][$li_i],2,",",".");
					$ls_codest1=substr($ls_codpro,0,20);
					$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
					$ls_codest2=substr($ls_codpro,20,6);
					$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
					$ls_codest3=substr($ls_codpro,26,3);
					$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
					$ls_codest4=substr($ls_codpro,29,2);
					$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
					$ls_codest5=substr($ls_codpro,31,2);
					$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
					$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
					$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
					$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
					$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
					$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>".
											"<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codpro."'>";
				}
			}
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value=''>";        

		print "<p>&nbsp;</p>";
		print "<p>&nbsp;</p>";
		if ($as_tipo=='B') 
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S' || $as_tipo=='O')
		   { 
		     $li_size = 840;
		   } 
		print "  <table width='".$li_size."' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,$li_size,"Cuentas","gridcuentas");
		unset($io_solicitud);
	}// end function uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_cargo($as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: private
		//	    Arguments: as_numsol  // N�mero de Solicitud
		//                 as_tipo // Tipo de SEP si es de bienes � de servicios
		//	  Description: M�todo que busca las cuentas asociadas a los cargos de una solicitud
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creaci�n: 17/03/2007								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sme, $la_cuentacargo;
		// Titulos el Grid
		$lo_title[1]="Cargo";
		$lo_title[2]="Estructura Programatica";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		$lo_title[5]=""; 
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		require_once("tepuy_sme_c_solicitud.php");
		$io_solicitud=new tepuy_sme_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_cuentas_cargo($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codcargo=$row["codcar"];
			$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_cuenta=$row["spg_cuenta"];
			$li_moncue=number_format($row["total"],2,",",".");
			$ls_codest1=substr($ls_codpro,0,20);
			$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
			$ls_codest2=substr($ls_codpro,20,6);
			$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
			$ls_codest3=substr($ls_codpro,26,3);
			$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
			$ls_codest4=substr($ls_codpro,29,2);
			$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
			$ls_codest5=substr($ls_codpro,31,2);
			$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$ls_codcargo."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
			$lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>".
									"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codpro."'>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value=''>";        

		print "<p>&nbsp;</p>";
		if ($as_tipo=='B') 
		   {
		     $li_size = 895;
		   }
		elseif($as_tipo=='S' || $as_tipo=='O')
		   { 
		     $li_size = 840;
		   } 
		print "  <table width='".$li_size."' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.png' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Cargos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,$li_size,"Cuentas Cargos","gridcuentascargos");
		unset($io_solicitud);
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
