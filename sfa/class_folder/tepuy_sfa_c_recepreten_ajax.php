<?php
	session_start(); 
	ini_set('display_errors', 1);
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_sfa.php");
	$io_funciones_sfa=new class_funciones_sfa();
	require_once("../../shared/class_folder/class_datastore.php");
	$io_ds_spgcuentas=new class_datastore(); // Datastored de cuentas contables
	require_once("../../shared/class_folder/class_datastore.php");
	$io_ds_deducciones=new class_datastore(); // Datastored de Deducciones
	// proceso a ejecutar
	$ls_proceso=$io_funciones_sfa->uf_obtenervalor("proceso","");
	// Total de filas de las deducciones
	$li_totrowdeducciones=$io_funciones_sfa->uf_obtenervalor("totrowdeducciones","0");
	// sub total 
	$li_subtotal=$io_funciones_sfa->uf_obtenervalor("subtotal","0,00");
	// total de cargos
	$li_cargos=$io_funciones_sfa->uf_obtenervalor("cargos","0,00");
	//$li_cargos=1;
	// total de deducciones
	$li_deducciones=$io_funciones_sfa->uf_obtenervalor("deducciones","0,00");
	// cargar deducciones
	$li_cargardeducciones=$io_funciones_sfa->uf_obtenervalor("cargardeducciones","0,00");
	// total 
	$li_total=$io_funciones_sfa->uf_obtenervalor("total","0,00");
	// total general
	$li_totgeneral=$io_funciones_sfa->uf_obtenervalor("totgeneral","0,00");
	//print "proceso: ".$ls_proceso;
	switch($ls_proceso)
	{
		case "FACTURA":

			uf_print_cuentas_retenciones($li_totrowdeducciones,$li_cargardeducciones);
			break;
	}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_retenciones($ai_totrowdeducciones,$ai_cargardeducciones)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_retenciones
		//		   Access: private
		//	    Arguments: ai_totrowdeducciones // Total de Filas de las deducciones
		//				   ai_cargardeducciones // Cargar Deducciones
		//				   as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//	  Description: Método que carga el datastored de cuentas contables con las cuentas de las deducciones
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 05/08/2017 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sfa, $io_ds_deducciones;
		if($ai_cargardeducciones=="1")
		{
			if($ai_totrowdeducciones>0)
			{
				$ls_documento=trim($io_funciones_sfa->uf_obtenervalor("documento",""));
				// Recorrido del Grid de Deducciones
				for($li_fila=1;$li_fila<=$ai_totrowdeducciones;$li_fila++)
				{
					$ls_codded=trim($io_funciones_sfa->uf_obtenervalor("txtcodded".$li_fila,""));
					$li_monobjret=trim($io_funciones_sfa->uf_obtenervalor("txtmonobjret".$li_fila,"0,00"));
					$li_monret=trim($io_funciones_sfa->uf_obtenervalor("txtmonret".$li_fila,"0,00"));
					$ls_sccuenta=trim($io_funciones_sfa->uf_obtenervalor("sccuenta".$li_fila,""));
					$ls_porded=trim($io_funciones_sfa->uf_obtenervalor("porded".$li_fila,""));
					$ls_procede=trim($io_funciones_sfa->uf_obtenervalor("procededoc".$li_fila,""));
					$li_iva=$io_funciones_sfa->uf_obtenervalor("iva".$li_fila,"");
					$li_monto=str_replace(".","",$li_monret);
					$li_monto=str_replace(",",".",$li_monto);				
					if(($ai_generarcontable=="1")&&($as_estcontable=="1")&&(($li_iva!="1")||($ls_estretiva=="C")))
					{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
						$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_documento);			
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
						$io_ds_scgcuentas->insertRow("debhab","H");			
						$io_ds_scgcuentas->insertRow("estatus","A");			
						$io_ds_scgcuentas->insertRow("mondeb","0");			
						$io_ds_scgcuentas->insertRow("monhab",$li_monto);			
						$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
					}		
					$io_ds_deducciones->insertRow("documento",$ls_documento);			
					$io_ds_deducciones->insertRow("codded",$ls_codded);			
					$io_ds_deducciones->insertRow("monobjret",$li_monobjret);			
					$io_ds_deducciones->insertRow("monret",$li_monret);			
					$io_ds_deducciones->insertRow("sccuenta",$ls_sccuenta);			
					$io_ds_deducciones->insertRow("porded",$ls_porded);			
					$io_ds_deducciones->insertRow("procededoc",$ls_procede);			
					$io_ds_deducciones->insertRow("iva",$li_iva);			
				}
				$_SESSION["deducciones"]=$io_ds_deducciones->data;
			}
			else
			{
				unset($_SESSION["deducciones"]);
			}
		}
		else
		{
			if(array_key_exists("deducciones",$_SESSION))
			{
				$io_ds_deducciones->data=$_SESSION["deducciones"];
				$li_totrow=$io_ds_deducciones->getRowCount('sccuenta');	
				for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
				{
					$ls_documento=$io_ds_deducciones->getValue("documento",$li_fila);
					$ls_codded=$io_ds_deducciones->getValue("codded",$li_fila);
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_fila);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_fila);
					$ls_sccuenta=$io_ds_deducciones->getValue("sccuenta",$li_fila);
					$ls_procede=$io_ds_deducciones->getValue("procededoc",$li_fila);
					$li_iva=$io_ds_deducciones->getValue("iva",$li_fila);
					$li_monto=str_replace(".","",$li_monret);
					$li_monto=str_replace(",",".",$li_monto);							

					if(($ai_generarcontable=="1")&&($as_estcontable=="1")&&(($li_iva!="1")||($ls_estretiva=="C")))
					{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
						$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_documento);			
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
						$io_ds_scgcuentas->insertRow("debhab","H");			
						$io_ds_scgcuentas->insertRow("estatus","A");			
						$io_ds_scgcuentas->insertRow("mondeb","0");			
						$io_ds_scgcuentas->insertRow("monhab",$li_monto);
						$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
					}	
				}		
			}
		}
	}// end function uf_print_cuentas_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

?>
