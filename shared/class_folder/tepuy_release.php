<?php
////////////////////////////////////////////////////////////////////////////////////////////////////
//           CLASS:  tepuy_release
//	         Access:  public
//     Programador:  Ing. Miguel Palencia
//     Description:  Clase que tiene comom funci� actualizar los nuevos campos y tablas de la base 
//                   de datos tepuy.db
////////////////////////////////////////////////////////////////////////////////////////////////////
class tepuy_release
{
	var $io_function;
	var $io_function_db;
	var $io_msg;
	var $io_include;
	var $io_connect;
	var $io_sql;
	
	function tepuy_release()
	{
		require_once("class_sql.php");  
		require_once("tepuy_include.php");
		require_once("class_funciones.php");
		require_once("class_mensajes.php");
		require_once("class_funciones_db.php"); 
		require_once("tepuy_c_seguridad.php");
		$this->io_function=new class_funciones();	
		$this->io_msg=new class_mensajes();
		$this->io_include=new tepuy_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_function_db=new class_funciones_db($this->io_connect);					
		$this->io_seguridad=new tepuy_c_seguridad();		
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];		
	} //  end contructor

	function uf_destructor()
	{	
		unset($this->io_function);	
		unset($this->io_msg);				
		unset($this->io_include);				
		unset($this->io_connect);				
		unset($this->io_sql);	
		unset($this->io_seguridad);	
	} // end function uf_destructor

    function uf_check_update($aa_seguridad) // main()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_update
		//		   Access: public 
		//	  Description: chequea los updates
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�: 06/07/2006 								Fecha �tima Modificaci� : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();	   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=$this->io_function_db->uf_select_column('soc_sol_cotizacion','cedper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.1 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_1();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_enlace_sep','estordcom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.2 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_2();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_periodo','peradi');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.3 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_3();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hperiodo','peradi');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.4 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_4();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thperiodo','peradi');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.5 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_5();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_partes','vidautil');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.6 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_6();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_despacho','codunides');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.7 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_7();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_dt_despacho','canpenart');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.8 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_8();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_tiposervicio','obstipser');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.9 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_9();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_partes','cmpmov');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.10 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_10();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_partes','monto');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.11 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_11();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_deducciones','otras');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.12 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_12();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_ctrl_numero','nro_actual');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.13 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_13();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','nomres');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.14 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_14();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_tablavacacion','anoserpre');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.15 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_15();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','concomiva');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.16 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_16();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_metodobanco','numconnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.17 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_17();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_ordencompra','obsordcom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.18 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_18();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_constanciatrabajo','marinfcont');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.19 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_19();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_fideiperiodo','diafid');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.20 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_20();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_fideicomiso','capantcom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.21 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_21();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sep_solicitudcargos','monto');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.22 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_22();
			if($lb_valido)
			{
				$lb_valido = $this->uf_create_release_ajustar_programatica_cargos();
			}
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_cestaticket','codcli');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.23 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_23();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_ordencompra','fecent');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.24 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_24();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_prog_pago','esttipvia');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.25 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_25();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('scb_dt_movbco');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.26 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_26();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','cedben');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.27 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_27();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','estmodiva');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.28 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_28();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_ubicacionfisica','codpai');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.29 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_29();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_ubicacionfisica','dirubifis');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.30 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_30();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('scb_cmp_ret_op');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.31 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_31();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('scb_dt_cmp_ret_op');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.32 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_32();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_dt_movbco','ctabanbene');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.33 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_33();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_constanciatrabajo','tamletpiecont');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.34 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_34();  
			
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_estudiorealizado','aprestrea');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.35 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_35();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','turper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.36 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_36();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','hcmper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.37 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_37();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_familiar','hcfam');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.38 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_38();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_estudiorealizado','horestrea');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.39 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_39();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','tipsanper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.40 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_40();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('rpc_espexprov');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.41 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_41();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_movbco','nrocontrolop');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.42 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_42();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_movbco_spgop','baseimp');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.43 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_43();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','conpernom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.44 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_44();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','conpernom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.45 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_45();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','conpernom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.46 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_46();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_constanciatrabajo','arcrtfcont');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.47 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_47();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sep_solicitud','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.48 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_48();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_ordencompra','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.49 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_49();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_solicitudes','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.50 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_50();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_sol_dc','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.51 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_51();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_movbco','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.52 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_52();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_movcol','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.53 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_53();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sob_asignacion','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.54 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_54();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sob_contrato','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.55 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_55();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_dt_scg','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.56 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_56();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_dt_spg','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.57 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_57();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_depreciacion','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.58 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_58();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_cmp_md','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.59 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_59();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','activo_t');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.60 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_60();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$this->io_msg->message(" Release Versión 2.61 ");				   	   
		//$lb_valido=$this->uf_create_release_db_libre_V_2_61();  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$this->io_msg->message(" Release Versión 2.62 ");				   	   
		//$lb_valido=$this->uf_create_release_db_libre_V_2_62();  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','conpronom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.63 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_63();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','conpronom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.64 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_64();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','conpronom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.65 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_65();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_existe = $this->io_function_db->uf_select_column('sno_concepto','conprocon');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.66 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_66();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hconcepto','conprocon');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.67 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_67();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thconcepto','conprocon');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.68 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_68();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_proyecto');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.69 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_69();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_proyectopersonal');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.70 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_70();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_hproyecto');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.71 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_71();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_hproyectopersonal');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.72 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_72();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_thproyecto');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.73 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_73();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_thproyectopersonal');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.74 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_74();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_proyectopersonal','pordiames');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.75 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_75();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hproyectopersonal','pordiames');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.76 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_76();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thproyectopersonal','pordiames');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.77 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_77();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_rd_scg','estasicon');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.78 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_78();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_componente');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.79 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_79();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_rango');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.80 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_80();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','codcom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.81 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_81();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','numexpper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.82 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_82();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_rd','codfuefin');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.83 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_83();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_rd_spg','codfuefin');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.84 ");				   	   

			$lb_valido=$this->uf_create_release_db_libre_V_2_84();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','codasiona');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.85 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_85();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','titrepnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.86 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_86();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','titrepnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.87 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_87();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','titrepnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.88 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_88();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_ctrl_numero','codusu');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.89 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_89();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personalnomina','codunirac');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.90 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_90();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hpersonalnomina','codunirac');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.91 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_91();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thpersonalnomina','codunirac');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.92 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_92();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_ctrl_numero','estact');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.93 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_93();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_cmp_md','codfuefin');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.94 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_94();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','codorgcestic');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.95 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_95();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','codorgcestic');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.96 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_96();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','codorgcestic');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.97 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_97();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personalnomina','pagtaqper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.98 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_98();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hpersonalnomina','pagtaqper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 2.99 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2_99();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thpersonalnomina','pagtaqper');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.00 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_00();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_metodobanco','pagtaqnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.01 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_01();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','codpainac');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.02 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_02();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_rd_deducciones','estcmp');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.03 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_03();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_cartaorden','archrtf');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.04 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_04();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_config','estcmp');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.05 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_05();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_articulo','serart');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.06 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_06();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('tepuy_catalogo_milco');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.07 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_07();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_articulo','codmil');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.08 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_08();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_tiposervicio','codmil');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.09 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_09();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('scb_movbco_fuefinanciamiento');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.10");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_10();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('spg_ep3','codfuefin');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.11 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_11();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('spg_ep5','codfuefin');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.12 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_12();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_constante','conespseg');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.13 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_13();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hconstante','conespseg');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.14 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_14();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thconstante','conespseg');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.15 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_15();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_archivotxt');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.16 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_16();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_archivotxtcampo');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.17 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_17();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_tabulador','maxpasgra');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.18 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_18();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_htabulador','maxpasgra');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.19 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_19();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thtabulador','maxpasgra');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.20 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_20();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_beneficiario');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.21 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_21();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_beneficiario','forpagben');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.22 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_22();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_archivotxtcampo','tipcam');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.23 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_23();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_beneficiario','nacben');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.24 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_24();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('rpc_beneficiario','tipcuebanben');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.25 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_25();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_cheques','estins');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.26 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_26();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_ordencompra','uniejeaso');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.27 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_27();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_enlace_sep','coduniadm');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.28 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_28();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_dt_bienes','coduniadm');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.29 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_29();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_dt_servicio','coduniadm');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.30 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_30();  
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','diacadche');
		if (!$lb_existe)
		{
			$this->io_msg->message(" Release Versión 3.31 ");				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_31();  
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_movimiento','codrespri');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.32 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_32();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_dt_movimiento','estcat');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.33 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_33();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','confi_ch');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.34 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_34();
			                
		}
		
		$lb_existe = $this->io_function_db->uf_select_table('saf_item');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.35 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_35();
		}
		$lb_existe = $this->io_function_db->uf_select_column('saf_activo','codite');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.36 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_36();
		}
		$lb_existe = $this->io_function_db->uf_select_column('saf_cambioresponsable','codact');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.37 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_37();
		}
		$lb_existe = $this->io_function_db->uf_select_column('sno_personalnomina','fecascper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.38 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_38();
		}
		$lb_existe = $this->io_function_db->uf_select_column('sno_hpersonalnomina','fecascper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.39 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_39();
		}
		$lb_existe = $this->io_function_db->uf_select_column('sno_thpersonalnomina','fecascper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.40 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_40();
		}
		$lb_existe = $this->io_function_db->uf_select_column('scb_movbco','conanu');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.41 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_41();
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('scv_dt_personal','codnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.42 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_42();
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_prestamos','tipcuopre');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.43 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_43();
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_hprestamos','tipcuopre');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.44 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_44();
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_thprestamos','tipcuopre');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.45 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_45();
		}
		$lb_existe = $this->io_function_db->uf_select_table('sno_sueldominimo');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.46 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_46();
		}
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','confiva');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.47 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_47();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_clasificacionobrero');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.48 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_48();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_hclasificacionobrero');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.49 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_49();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sno_thclasificacionobrero');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.50 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_50();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personalnomina','grado');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.51 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_51();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hpersonalnomina','grado');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.52 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_52();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thpersonalnomina','grado');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.53 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_53();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe = $this->io_function_db->uf_select_table('tepuy_unidad_tributaria');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 3.54 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_54();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','estvaldis');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.55 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_55();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_existe = $this->io_function_db->uf_select_column('scb_cheques','orden');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.56 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_56();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_table('saf_prestamo');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.57 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_57();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_table('saf_autsalida');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.58 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_58();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_column('saf_dta','estactpre');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.59 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_59();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_column('saf_dta','codunipre');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.60 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_60();
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('scb_cheques','codusu');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 3.61 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_61();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_dt_scg','fechaconta');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 2008_3_62 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_62();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_dt_scg','fechaanula');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 2008_3_63 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_63();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sss_permisos_internos_grupos');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 2008_3_64 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_64();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sss_derechos_grupos','codintper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 2008_3_65 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_65();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','confidnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_66 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_66();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','confidnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_67 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_67();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','confidnom');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_68 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_68();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_periodo','ingconper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_69 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_69();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hperiodo','ingconper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_70 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_70();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thperiodo','ingconper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_71 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_71();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_fideiconfigurable','cueprefid');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_72 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_72();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_servicios','codunimed');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_73 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_73();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_unidadmedida','tiposep');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_74 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_74();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_concepto','repacucon');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_75 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_75();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hconcepto','repacucon');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_76 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_76();
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thconcepto','repacucon');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_77 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_77();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','nomrep');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_78 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_78();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','cedrep');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_79 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_79();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','telfrep');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_80 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_80();
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','cargorep');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_81 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_81();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','nroivss');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_82 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_82();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_clasificacionobrero','anovig');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_83 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_83();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hclasificacionobrero','anovig');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_84 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_84();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thclasificacionobrero','anovig');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_85 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_85();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_despacho','nomproy');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_86 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_86();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_entrega');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_87 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_87();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_dt_entrega');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_88 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_3_88();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scb_banco','codsudeban');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_89 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_89();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_deducciones','tipopers');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_90 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_90();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_beneficiario','nexben');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_91 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_91();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','enviorec');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_92 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_92();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_beneficiario','cedaut');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_93 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_93();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','estretiva');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_94 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_94();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('cxp_documento','tipodocanti');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_95 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_95();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$lb_existe = $this->io_function_db->uf_select_column('srh_departamento','minorguniadm');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_96 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_96();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('srh_movimiento_personal','minorguniadm');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_97 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_97();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('srh_movimiento_personal','codunivi');
		if ($lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_98 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_98();
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_ordencompra','fechentdesde');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_3_99 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_3_99();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		$lb_existe = $this->io_function_db->uf_select_column('sno_nomina','divcon');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_00 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_00();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		$lb_existe = $this->io_function_db->uf_select_column('sno_concepto','quirepcon');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_01 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_01();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		$lb_existe = $this->io_function_db->uf_select_column('sno_salida','priquisal');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_02 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_02();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('srh_defcontrato');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Versión 2008_4_03 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_03();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_permiso','tothorper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_04 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_04();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_metodobanco','nroref');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_05 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_05();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_concepto','asifidper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_06 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_06();
		}
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_hconcepto','asifidper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_07 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_07();
		}
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_thconcepto','asifidper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_08 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_08();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('scg_casa_presu');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_09 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_09();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','confinstr');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_10 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_10();
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('scg_pc_reporte','saldo_real_ant');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_11 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_11();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		switch($_SESSION["ls_gestor"])
	  	{
			case "MYSQL":
				$lb_existe=$this->io_function_db->uf_select_type_columna('scb_movbco_spi','desmov','longtext');		
			 break;
				   
			case "POSTGRE":
				$lb_existe=$this->io_function_db->uf_select_type_columna('scb_movbco_spi','desmov','text');
				   								
			break;  				  
	    }	
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_12 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_12();
		}	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','talcamper');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_13 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_13();
		}		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_articulo','estact');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_14 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_14();
		}		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','anoservprecont');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_15 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_15();
		}		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sno_personal','anoservprefijo');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_16 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_16();
		}		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_articulo','codact');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_17 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_17();
		}		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_dt_recepcion','estregact');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_18 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_18();
		}		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch($_SESSION["ls_gestor"])
	  	{
			case "MYSQL":
				$lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spgop','desmov','longtext');		
			 break;
				   
			case "POSTGRE":
				$lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spgop','desmov','text');
				   								
			break;  				  
	    }	
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_19 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_19();
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sob_valuacion','estgenrd');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_20 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_20();
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sob_documento');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_21 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_21();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_anticipos');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_22 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_22();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_antiguedad');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_23 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_23();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_articulos');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_24 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_24();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_carta_anticipos');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_25 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_25();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_causaretiro');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_26 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_26();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_configuracion');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_27 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_27();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_deuda_anterior');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_28 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_28();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_dt_liquidacion');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_29 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_29();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_dt_scg');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_30 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_30();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_dt_spg');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_31 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_31();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_liquidacion');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_32 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_32();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_sueldos');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_33 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_33();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('sps_tasa_interes');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_34 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_34();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_movimiento','ubigeoact');	
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_35 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_35();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('soc_solicitudcargos','numsol');	
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_36 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_36();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_empresa','estmodpartsoc');	
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_37 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_37();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('saf_activo','tipinm');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_38 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_38();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_edificios');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_39 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_39();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_tipoestructura');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_40 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_40();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_componente');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_41 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_41();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('saf_edificiotipest');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_42 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_42();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_constraint('scv_rutas','ak_key_2_scv_ruta');
		if ($lb_existe)
		{
		    $this->io_msg->message(utf8_encode(" Release Version 2008_4_43 "));	
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_43();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('siv_segmento');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_44 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_44();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('siv_familia');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_45 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_45();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('siv_clase');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_46 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_46();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_table('siv_producto');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_47 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_47();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('siv_articulo','codprod');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_48 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_48();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$tamano1=$this->io_function_db->uf_tamano_type_columna('tepuy_ctrl_numero','codusu');
		if ($tamano1=="10")
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_49 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_49();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('sob_acta','nomresact');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_50 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_50();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = $this->io_function_db->uf_select_column('tepuy_deducciones','retaposol');
		if (!$lb_existe)
		{
			$this->io_msg->message(utf8_encode(" Release Version 2008_4_51 "));				   	   
			$lb_valido=$this->uf_create_release_db_libre_V_2008_4_51();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{  		    
			$lb_valido = $this->uf_create_release_insert_tepuy_procedencias();
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{  		    
			$lb_valido = $this->uf_cambiar_tipo_data();
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			$lb_valido=$this->insertar_activos();
		}
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Ejecutó el release ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	} // end function 

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_1()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_sol_cotizacion ". 
					"  ADD COLUMN cedper VARCHAR(10) AFTER codusu, ". 
					"  ADD COLUMN codcar CHAR(10) AFTER cedper, ". 
					"  ADD COLUMN soltel VARCHAR(100) AFTER codcar, ". 
					"  ADD COLUMN solfax VARCHAR(100) AFTER soltel, ". 
					"  ADD COLUMN coduniadm CHAR(10) AFTER solfax; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_sol_cotizacion ". 
					"  ADD COLUMN cedper VARCHAR(10), ". 
					"  ADD COLUMN codcar CHAR(10) , ". 
					"  ADD COLUMN soltel VARCHAR(100) , ". 
					"  ADD COLUMN solfax VARCHAR(100) , ". 
					"  ADD COLUMN coduniadm CHAR(10) ; ";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.1");			 		 
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_2()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_enlace_sep ADD COLUMN estordcom SMALLINT UNSIGNED NOT NULL AFTER numsol;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_enlace_sep ADD COLUMN estordcom int2; ";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.2");			 		 
				$lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE soc_enlace_sep SET estordcom=(SELECT estcom FROM soc_ordencompra ".
					"									   WHERE soc_ordencompra.codemp = soc_enlace_sep.codemp ".
					"									     AND soc_ordencompra.numordcom = soc_enlace_sep.numordcom ".
					"									     AND soc_ordencompra.estcondat = soc_enlace_sep.estcondat)";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.2");			 		 
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_3()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_periodo ADD COLUMN peradi SMALLINT(6) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_periodo ADD COLUMN peradi int2 DEFAULT 0;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.3");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_4()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hperiodo ADD COLUMN peradi SMALLINT(6) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hperiodo ADD COLUMN peradi int2 DEFAULT 0;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.4");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_5()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thperiodo ADD COLUMN peradi SMALLINT(6) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thperiodo ADD COLUMN peradi int2 DEFAULT 0;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.5");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_6()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN vidautil DOUBLE(6,2) NOT NULL, ADD COLUMN cossal DOUBLE(19,4) NOT NULL;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN vidautil FLOAT8 NOT NULL , ADD COLUMN cossal FLOAT8 NOT NULL;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.6");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_7()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE siv_despacho ADD COLUMN codunides VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE siv_despacho ADD COLUMN codunides varchar(10);";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.7 ");
				$lb_valido=false;
			}
		}
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE saf_dta MODIFY COLUMN seract VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '000000000000000';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE saf_dta ALTER seract TYPE varchar(20);ALTER TABLE saf_dta ALTER COLUMN seract SET STATISTICS -1;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.7");			 		 
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_8()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE siv_dt_despacho ADD COLUMN canpenart DOUBLE(8,2) DEFAULT 0.00;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE siv_dt_despacho ADD COLUMN canpenart float8 DEFAULT 0.00;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.8");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_9()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_tiposervicio ADD COLUMN obstipser VARCHAR(1000);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_tiposervicio ADD COLUMN obstipser varchar(1000);";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.9");			 		 
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_10()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN cmpmov VARCHAR(15) NOT NULL DEFAULT '000000000000000';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN cmpmov varchar(15) NOT NULL DEFAULT 000000000000000;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.10");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_11()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN monto DOUBLE(19,4);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE saf_partes ADD COLUMN monto float8;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.11");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_12()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_deducciones ADD COLUMN otras SMALLINT(6) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_deducciones ADD COLUMN otras int2; ".
					"ALTER TABLE tepuy_deducciones ALTER COLUMN otras SET DEFAULT 0;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.12");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_13()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN nro_actual VARCHAR(15) NOT NULL;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN nro_actual varchar(15); ".
					"ALTER TABLE tepuy_ctrl_numero ALTER COLUMN nro_actual SET NOT NULL;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.13");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_14()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN nomres varchar(20);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN nomres varchar(20);";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.14");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_15()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_tablavacacion ADD COLUMN anoserpre SMALLINT(6) NOT NULL DEFAULT 1;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_tablavacacion ADD COLUMN anoserpre int2 NOT NULL DEFAULT 1;";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.15");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_16()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN concomiva VARCHAR(6);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN concomiva varchar(6);";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.16");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_2_17()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_metodobanco ADD COLUMN numconnom VARCHAR(8);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_metodobanco ADD COLUMN numconnom VARCHAR(8);";
			break;
		}
		if($ls_sql!="")		     			  
		{
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.17");
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_18()
	{
		$lb_valido=true;
		$ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
			   $ls_sql="ALTER TABLE soc_ordencompra ADD COLUMN obsordcom VARCHAR(500); ";
               break;
			   
	   		case "POSTGRE":
			   $ls_sql="ALTER TABLE soc_ordencompra ADD COLUMN obsordcom VARCHAR(500);";
               break;
		}			     			  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.18");
			 $lb_valido=false;
		}
       return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_19()
	{
		$lb_valido=true;
		$ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
			   $ls_sql="ALTER TABLE sno_constanciatrabajo  ".
			   		   "  ADD COLUMN marinfcont DOUBLE DEFAULT 3.00, ".
 					   "  ADD COLUMN marsupcont DOUBLE DEFAULT 4.00, ".
					   "  ADD COLUMN titcont TEXT, ".
					   "  ADD COLUMN piepagcont TEXT;";
               break;
			   
	   		case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_constanciatrabajo  ".
			   		   "  ADD COLUMN marinfcont FLOAT8 DEFAULT 3.00, ".
 					   "  ADD COLUMN marsupcont FLOAT8 DEFAULT 4.00, ".
					   "  ADD COLUMN titcont TEXT DEFAULT ' ', ".
					   "  ADD COLUMN piepagcont TEXT DEFAULT ' ';";
               break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.19");
			 $lb_valido=false;
		}
       return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_20()
	{
		$lb_valido=true;
		$ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
			   $ls_sql="ALTER TABLE sno_fideiperiodo  ".
			   		   "  ADD COLUMN diafid INTEGER NOT NULL DEFAULT 0, ".
					   "  ADD COLUMN diaadi INTEGER NOT NULL DEFAULT 0; ";
               break;

	   		case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_fideiperiodo  ".
			   		   "  ADD COLUMN diafid int2 DEFAULT 0, ".
					   "  ADD COLUMN diaadi int2 DEFAULT 0; ";
               break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.20");
			 $lb_valido=false;
		}
       return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_21()
	{
		$lb_valido=true;
		$ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
			   $ls_sql="ALTER TABLE sno_fideicomiso ".
			   		   "  ADD COLUMN capantcom VARCHAR(1) NOT NULL DEFAULT 0;";
               break;

	   		case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_fideicomiso ".
			   		   "  ADD COLUMN capantcom varchar(1) NOT NULL DEFAULT 0;";
               break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.21");
			 $lb_valido=false;
		}
       return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_22()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE sep_solicitudcargos ".
					" ADD COLUMN monto DOUBLE(19,4) NOT NULL; ";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE sep_solicitudcargos ".
					" ADD COLUMN monto float8 NOT NULL DEFAULT 0; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.22");
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql=" ALTER TABLE sep_solicitudcargos         ".
						" DROP PRIMARY KEY,                       ". 
						" ADD PRIMARY KEY(codemp, numsol,         ".
						"                 codcar, codestpro1,     ".
						"                 codestpro2, codestpro3, ".
						"                 codestpro4, codestpro5, ".
						"                 spg_cuenta)             ";
				break;
				
				case "POSTGRE":
				$ls_sql=" ALTER TABLE sep_solicitudcargos DROP CONSTRAINT pk_sep_solicitudcargos;  ".
						" ALTER TABLE sep_solicitudcargos ADD CONSTRAINT pk_sep_solicitudcargos    ".
						" PRIMARY KEY (codemp, numsol, codcar, codestpro1, codestpro2, codestpro3, ".
						"              codestpro4, codestpro5, spg_cuenta) ";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.22");
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql=" UPDATE sep_solicitudcargos SET  monto = monret ";
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2.22");
				$lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_23()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_cestaticket ".
					"  ADD COLUMN codcli VARCHAR(15), ".
					"  ADD COLUMN codprod VARCHAR(15), ".
					"  ADD COLUMN punent VARCHAR(15);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_cestaticket ".
					"  ADD COLUMN codcli VARCHAR(15), ".
					"  ADD COLUMN codprod VARCHAR(15), ".
					"  ADD COLUMN punent VARCHAR(15); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.23");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_24()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_ordencompra ADD COLUMN fecent DATE;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_ordencompra ADD COLUMN fecent date;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.24");
			 $lb_valido=false;
		}
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_sql="ALTER TABLE sno_asignacioncargo MODIFY COLUMN denasicar VARCHAR(100) NOT NULL;";
			break;
			
			case "POSTGRE":
				$ls_sql="ALTER TABLE sno_asignacioncargo ALTER denasicar TYPE varchar(100); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.24");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_25()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_prog_pago ADD COLUMN esttipvia INTEGER(1) UNSIGNED;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_prog_pago ADD COLUMN esttipvia int2;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.25");			 		 
			 $lb_valido=false;
		}
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_sql="ALTER TABLE sno_hasignacioncargo MODIFY COLUMN denasicar VARCHAR(100) NOT NULL;";
			break;
			
			case "POSTGRE":
				$ls_sql="ALTER TABLE sno_hasignacioncargo ALTER denasicar TYPE varchar(100); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.25");			 		 
			 $lb_valido=false;
		}
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_sql="ALTER TABLE sno_thasignacioncargo MODIFY COLUMN denasicar VARCHAR(100) NOT NULL;";
			break;
			
			case "POSTGRE":
				$ls_sql="ALTER TABLE sno_thasignacioncargo ALTER denasicar TYPE varchar(100); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.25");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_26()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE scb_dt_movbco ".
					"( ".					
					"	codemp VARCHAR(4)  NOT NULL, ".
					"	codban VARCHAR(3)  NOT NULL, ".
					"	ctaban VARCHAR(25) NOT NULL, ".
					"	numdoc VARCHAR(15) NOT NULL, ".
					"	codope VARCHAR(2)  NOT NULL, ".
					"	estmov VARCHAR(1)  NOT NULL, ".
					"	cod_pro VARCHAR(10)  NOT NULL, ".
					"	ced_bene VARCHAR(10) NOT NULL, ".
					"	numsolpag VARCHAR(15) NOT NULL, ".
					"	monsolpag DOUBLE(19,4), ".
					"	PRIMARY KEY(codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag), ".
					"	CONSTRAINT scb_dt_movbco_scb_movbco FOREIGN KEY scb_dt_movbco_scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov) ".
					"	REFERENCES scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov) ".
					"	ON DELETE RESTRICT ".
					"	ON UPDATE RESTRICT ".
					")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE scb_dt_movbco ".
					"( ".
					"   codemp char(4) NOT NULL, ".
					"   codban char(3) NOT NULL, ".
					"   ctaban char(25) NOT NULL, ".
					"   numdoc char(15) NOT NULL, ". 
					"   codope char(2) NOT NULL, ".
					"   estmov char(1) NOT NULL,". 
					"   cod_pro char(10) NOT NULL, ".
					"   ced_bene char(10) NOT NULL, ". 
					"   numsolpag char(15) NOT NULL, ".
					"   monsolpag float8, ".
					"   CONSTRAINT scb_dt_movbco_pk PRIMARY KEY (codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag) USING INDEX TABLESPACE pg_default ".
					") WITHOUT OIDS ".
					"TABLESPACE pg_default;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.26");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_27()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN cedben VARCHAR(10), ".
					"  ADD COLUMN nomben VARCHAR(100), ".
					"  ADD COLUMN scctaben VARCHAR(25);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN cedben VARCHAR(10), ".
					"  ADD COLUMN nomben VARCHAR(100), ".
					"  ADD COLUMN scctaben VARCHAR(25);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.27");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_28()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN estmodiva SMALLINT(6) NOT NULL DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN estmodiva int2 NOT NULL DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.28");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_29()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_ubicacionfisica ".
					"  ADD COLUMN codpai varchar(3), ".
					"  ADD COLUMN codest varchar(3), ".
					"  ADD COLUMN codmun varchar(3), ".
					"  ADD COLUMN codpar varchar(3); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_ubicacionfisica ".
					"  ADD COLUMN codpai varchar(3), ".
					"  ADD COLUMN codest varchar(3), ".
					"  ADD COLUMN codmun varchar(3), ".
					"  ADD COLUMN codpar varchar(3); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.29");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_30()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_ubicacionfisica ".
					"  ADD COLUMN dirubifis varchar(200); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_ubicacionfisica ".
					"  ADD COLUMN dirubifis varchar(200); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.30");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_31()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE  scb_cmp_ret_op ( ".
					"  codemp char(4) NOT NULL, ".
					"  codret char(10) NOT NULL, ".
					"  numcom char(15) NOT NULL, ".
					"  fecrep datetime NOT NULL, ".
					"  perfiscal varchar(6) NOT NULL, ".
					"  codsujret varchar(10) NOT NULL, ".
					"  nomsujret varchar(80) NOT NULL, ".
					"  dirsujret varchar(200) NOT NULL, ".
					"  rif varchar(15) NOT NULL, ".
					"  nit varchar(15) NOT NULL, ".
					"  estcmpret smallint(6) NOT NULL, ".
					"  codusu varchar(50) default NULL, ".
					"  numlic varchar(25) default NULL, ".
					"  origen varchar(1) default 'A', ".
					"  PRIMARY KEY  (codemp,codret,numcom), ".
					"  CONSTRAINT fk_empresa__scb_cmpret_op FOREIGN KEY (codemp) REFERENCES tepuy_empresa (codemp) ".
					") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE  scb_cmp_ret_op ( ".
					"  codemp char(4) NOT NULL, ".
					"  codret char(10) NOT NULL, ".
					"  numcom char(15) NOT NULL, ".
					"  fecrep date NOT NULL, ".
					"  perfiscal varchar(6) NOT NULL, ".
					"  codsujret varchar(10) NOT NULL, ".
					"  nomsujret varchar(80) NOT NULL, ".
					"  dirsujret varchar(200) NOT NULL, ".
					"  rif varchar(15) NOT NULL, ".
					"  nit varchar(15) NOT NULL, ".
					"  estcmpret smallint NOT NULL, ".
					"  codusu varchar(50) default NULL, ".
					"  numlic varchar(25) default NULL, ".
					"  origen varchar(1) default 'A', ".
					"  PRIMARY KEY  (codemp,codret,numcom), ".
					"  CONSTRAINT fk_empresa__scb_cmpret_op FOREIGN KEY (codemp) REFERENCES tepuy_empresa (codemp));";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.31");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_32()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE  scb_dt_cmp_ret_op ( ".
					"  codemp char(4) NOT NULL, ".
					"  codret char(10) NOT NULL, ".
					"  numcom char(15) NOT NULL, ".
					"  numope char(10) NOT NULL, ".
					"  fecfac datetime NOT NULL, ".
					"  numfac varchar(15) NOT NULL, ".
					"  numcon varchar(15) default NULL, ".
					"  numnd varchar(10) default NULL, ".
					"  numnc varchar(10) default NULL, ".
					"  tiptrans varchar(10) default NULL, ".
					"  totcmp_sin_iva double(19,4) default NULL, ".
					"  totcmp_con_iva double(19,4) default NULL, ".
					"  basimp double(19,4) default NULL, ".
					"  porimp double(19,4) default NULL, ".
					"  totimp double(19,4) default NULL, ".
					"  iva_ret double(19,4) default NULL, ".
					"  desope varchar(200) default NULL, ".
					"  numsop varchar(40) default NULL, ".
					"  codban varchar(3) NOT NULL, ".
					"  ctaban varchar(25) NOT NULL, ".
					"  numdoc varchar(15) NOT NULL, ".
					"  codope varchar(2) NOT NULL, ".
					"  PRIMARY KEY  (codemp,codret,numcom,numope), ".
					"  CONSTRAINT fk_scb_cmp_ret_op__scb_dtcmpret_op FOREIGN KEY (codemp, codret, numcom) REFERENCES scb_cmp_ret_op (codemp, codret, numcom) ".
					") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE  scb_dt_cmp_ret_op ( ".
					"  codemp char(4) NOT NULL, ".
					"  codret char(10) NOT NULL, ".
					"  numcom char(15) NOT NULL, ".
					"  numope char(10) NOT NULL, ".
					"  fecfac date NOT NULL, ".
					"  numfac varchar(15) NOT NULL, ".
					"  numcon varchar(15) default NULL, ".
					"  numnd varchar(10) default NULL, ".
					"  numnc  varchar(10) default NULL, ".
					"  tiptrans varchar(10) default NULL, ".
					"  totcmp_sin_iva float8 NOT NULL default NULL, ".
					"  totcmp_con_iva float8 NOT NULL default NULL, ".
					"  basimp float8 NOT NULL default NULL, ".
					"  porimp float8 NOT NULL default NULL, ".
					"  totimp float8 NOT NULL default NULL, ".
					"  iva_ret float8 NOT NULL default NULL, ".
					"  desope varchar(200) default NULL, ".
					"  numsop varchar(40) default NULL, ".
					"  codban varchar(3) NOT NULL, ".
					"  ctaban varchar(25) NOT NULL, ".
					"  numdoc varchar(15) NOT NULL, ".
					"  codope varchar(2) NOT NULL, ".
					"  PRIMARY KEY  (codemp,codret,numcom,numope), ".
					"  CONSTRAINT fk_scb_cmp_ret_op__scb_dtcmpret_op FOREIGN KEY (codemp, codret, numcom) REFERENCES scb_cmp_ret_op (codemp, codret, numcom) ".
					");";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.32");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_33()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_dt_movbco ".
					"  ADD COLUMN ctabanbene varchar(25); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_dt_movbco ".
					"  ADD COLUMN ctabanbene varchar(25); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.33");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_34()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_constanciatrabajo ".
					"  ADD COLUMN tamletpiecont int; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_constanciatrabajo ".
					"  ADD COLUMN tamletpiecont int4; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.34");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_35()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_estudiorealizado ".
					"  ADD COLUMN aprestrea VARCHAR(1) DEFAULT 0, ".
					"  ADD COLUMN anoaprestrea VARCHAR(1) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_estudiorealizado ".
					"  ADD COLUMN aprestrea VARCHAR(1) DEFAULT 0, ".
					"  ADD COLUMN anoaprestrea VARCHAR(1) DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE sno_estudiorealizado ".
						"MODIFY COLUMN tipestrea VARCHAR(2);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE sno_estudiorealizado ".
						"ALTER tipestrea TYPE varchar(2);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '8' WHERE tipestrea = '0' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '9' WHERE tipestrea = '1' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '10' WHERE tipestrea = '2' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '11' WHERE tipestrea = '3' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '12' WHERE tipestrea = '4' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '13' WHERE tipestrea = '5' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '14' WHERE tipestrea = '6' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '3' WHERE tipestrea = '10' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");			 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '4' WHERE tipestrea = '11' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");	 		 
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '5' WHERE tipestrea = '12' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");	
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '6' WHERE tipestrea = '13' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_estudiorealizado SET tipestrea = '7' WHERE tipestrea = '14' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.35");	
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_36()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN turper VARCHAR(1), ".
					"  ADD COLUMN horper VARCHAR(45);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN turper VARCHAR(1), ".
					"  ADD COLUMN horper VARCHAR(45);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.36");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_37()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN hcmper VARCHAR(1) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN hcmper VARCHAR(1) ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.37");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_38()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_familiar ".
					"  ADD COLUMN hcfam VARCHAR(1), ".
					"  ADD COLUMN hcmfam VARCHAR(1) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_familiar ".
					"  ADD COLUMN hcfam VARCHAR(1), ".
					"  ADD COLUMN hcmfam VARCHAR(1) ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.38");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_39()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_estudiorealizado ".
					"  ADD COLUMN horestrea VARCHAR(3) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_estudiorealizado ".
					"  ADD COLUMN horestrea VARCHAR(3) DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.39");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_40()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN tipsanper VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN tipsanper VARCHAR(10);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.40");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_41()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE rpc_espexprov ".
					"( ".
					"  codemp CHAR(4) NOT NULL, ".
					"  cod_pro CHAR(10) NOT NULL, ".
					"  codesp CHAR(3) NOT NULL, ".
					"  PRIMARY KEY (codemp, cod_pro, codesp), ".
					"  CONSTRAINT FK_rpc_espexprov_1 FOREIGN KEY FK_rpc_espexprov_1 (codemp, cod_pro) ".
					"  	   REFERENCES rpc_proveedor (codemp, cod_pro) ".
					"  	   ON DELETE RESTRICT ".
					"      ON UPDATE RESTRICT, ".
					"  CONSTRAINT FK_rpc_espexprov_2 FOREIGN KEY FK_rpc_espexprov_2 (codesp) ".
					"      REFERENCES rpc_especialidad (codesp) ".
					"      ON DELETE RESTRICT ".
					"      ON UPDATE RESTRICT ".
					") ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE rpc_espexprov ".
					"( ".
					"   codemp char(4) NOT NULL, ".
					"   cod_pro char(10) NOT NULL, ".
					"   codesp char(3) NOT NULL, ".
					"   CONSTRAINT PK PRIMARY KEY (codemp, cod_pro, codesp), ".
					"   CONSTRAINT FK_rpc_espexprov_1 FOREIGN KEY (codemp, cod_pro) REFERENCES rpc_proveedor (codemp, cod_pro)    ON UPDATE NO ACTION ON DELETE NO ACTION, ".
					"   CONSTRAINT FK_rpc_espexprov_2 FOREIGN KEY (codesp) REFERENCES rpc_especialidad (codesp)    ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.41");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE rpc_proveedor DROP FOREIGN KEY fk_rpc_especialidad__rpc_proveedor;";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE rpc_proveedor DROP CONSTRAINT fk_rpc_prov_rpc_espec_rpc_espe;";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.41");	
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="INSERT INTO rpc_espexprov (codemp, cod_pro, codesp) ".
					"SELECT codemp, cod_pro, codesp ".
					"  FROM rpc_proveedor ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.41");	
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE rpc_proveedor SET codesp = '---' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.41");	
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_42()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_movbco ".
					"  ADD COLUMN nrocontrolop varchar(15); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movbco ".
					"  ADD COLUMN nrocontrolop varchar(15); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.42");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_43()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_movbco_spgop ".
					"  ADD COLUMN baseimp Double(19,4), ".
					"  ADD COLUMN codcar varchar(5); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movbco_spgop ".
					"  ADD COLUMN baseimp float8, ".
					"  ADD COLUMN codcar varchar(5); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.43");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_44()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_nomina ".
					"  ADD COLUMN conpernom varchar(1) DEFAULT '1'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_nomina ".
					"  ADD COLUMN conpernom varchar(1)  DEFAULT 1; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.44");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_45()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hnomina ".
					"  ADD COLUMN conpernom varchar(1) DEFAULT '1'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hnomina ".
					"  ADD COLUMN conpernom varchar(1)  DEFAULT 1; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.45");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_46()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thnomina ".
					"  ADD COLUMN conpernom varchar(1) DEFAULT '1'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thnomina ".
					"  ADD COLUMN conpernom varchar(1)  DEFAULT 1; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.46");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_47()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_constanciatrabajo ".
					"  ADD COLUMN arcrtfcont varchar(50); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_constanciatrabajo ".
					"  ADD COLUMN arcrtfcont varchar(50); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.47");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_48()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sep_solicitud ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sep_solicitud ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.48");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT sep_solicitud.numsol, tepuy_cmp.fecha ".
					"  FROM tepuy_cmp, sep_solicitud ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SEPSPC' ".
					"   AND tepuy_cmp.codemp = sep_solicitud.codemp ".
					"   AND tepuy_cmp.comprobante = sep_solicitud.numsol";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.48");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE sep_solicitud ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.48");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT sep_solicitud.numsol, tepuy_cmp.fecha ".
					"  FROM tepuy_cmp, sep_solicitud ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SEPSPA' OR tepuy_cmp.procede='SEPRPC')".
					"   AND tepuy_cmp.codemp = sep_solicitud.codemp ".
					"   AND tepuy_cmp.comprobante = sep_solicitud.numsol";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.48");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE sep_solicitud ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.48");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_49()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_ordencompra ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_ordencompra ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.49");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT soc_ordencompra.numordcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, soc_ordencompra ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SOCCOS' OR tepuy_cmp.procede ='SOCCOC')".
					"   AND tepuy_cmp.codemp = soc_ordencompra.codemp ".
					"   AND tepuy_cmp.comprobante = soc_ordencompra.numordcom";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.49");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numordcom=$row["numordcom"];
					$ls_procede=$row["procede"];
					if($ls_procede=="SOCCOS")
					{
						$ls_estcondat="S";
					}
					if($ls_procede=="SOCCOC")
					{
						$ls_estcondat="B";
					}
					$ls_sql="UPDATE soc_ordencompra ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numordcom = '".$ls_numordcom."' ".
							"   AND estcondat = '".$ls_estcondat."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.49");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT soc_ordencompra.numordcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, soc_ordencompra ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SOCAOS' OR tepuy_cmp.procede='SOCAOC')".
					"   AND tepuy_cmp.codemp = soc_ordencompra.codemp ".
					"   AND tepuy_cmp.comprobante = soc_ordencompra.numordcom";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.49");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numordcom=$row["numordcom"];
					$ls_procede=$row["procede"];
					if($ls_procede=="SOCAOS")
					{
						$ls_estcondat="S";
					}
					if($ls_procede=="SOCAOC")
					{
						$ls_estcondat="B";
					}
					$ls_sql="UPDATE soc_ordencompra ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numordcom = '".$ls_numordcom."' ".
							"   AND estcondat = '".$ls_estcondat."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.49");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_50()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_solicitudes ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_solicitudes ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.50");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT cxp_solicitudes.numsol, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, cxp_solicitudes ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='CXPSOP' ".
					"   AND tepuy_cmp.codemp = cxp_solicitudes.codemp ".
					"   AND tepuy_cmp.comprobante = cxp_solicitudes.numsol ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.50");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE cxp_solicitudes ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.50");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT cxp_solicitudes.numsol, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, cxp_solicitudes ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='CXPAOP' ".
					"   AND tepuy_cmp.codemp = cxp_solicitudes.codemp ".
					"   AND tepuy_cmp.comprobante = cxp_solicitudes.numsol ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.50");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE cxp_solicitudes ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.50");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_51()
	{
		$lb_valido=true;
		$ls_sql="";
		$ls_criterio="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_sol_dc ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',cxp_sol_dc.numdc) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_sol_dc ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||cxp_sol_dc.numdc ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.51");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT cxp_sol_dc.numdc, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, cxp_sol_dc ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='CXPNOD' OR tepuy_cmp.procede ='CXPNOC')".
					"   AND tepuy_cmp.codemp = cxp_sol_dc.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.51");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numdc=$row["numdc"];
					$ls_procede=$row["procede"];
					if($ls_procede=="CXPNOD")
					{
						$ls_codope="D";
					}
					if($ls_procede=="CXPNOC")
					{
						$ls_codope="C";
					}
					$ls_sql="UPDATE cxp_sol_dc ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdc = '".$ls_numdc."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.51");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_52()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_movbco ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',scb_movbco.numdoc) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movbco ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||scb_movbco.numdoc ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.52");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT scb_movbco.numdoc, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, scb_movbco ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SCBBCH' OR tepuy_cmp.procede ='SCBBDP' OR ".
					"		 tepuy_cmp.procede ='SCBBNC' OR tepuy_cmp.procede ='SCBBND' OR ".
					"		 tepuy_cmp.procede ='SCBOPD') ".
					"   AND tepuy_cmp.codemp = scb_movbco.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.52");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numdoc=$row["numdoc"];
					$ls_procede=$row["procede"];
					switch($ls_procede)
					{
						case "SCBBCH":
							$ls_codope="CH";
							break;
						case "SCBBDP":
							$ls_codope="DP";
							break;
						case "SCBBNC":
							$ls_codope="NC";
							break;
						case "SCBBND":
							$ls_codope="ND";
							break;
						case "SCBOPD":
							$ls_codope="OP";
							break;
					}
					$ls_sql="UPDATE scb_movbco ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdoc = '".$ls_numdoc."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.52");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT scb_movbco.numdoc, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, scb_movbco ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SCBBAH' OR tepuy_cmp.procede ='SCBBAP' OR tepuy_cmp.procede ='SCBBAC' OR tepuy_cmp.procede ='SCBBAD') ".
					"   AND tepuy_cmp.codemp = scb_movbco.codemp ".
					"   AND tepuy_cmp.comprobante = scb_movbco.numdoc ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.52");	

				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numdoc=$row["numdoc"];
					$ls_procede=$row["procede"];
					switch($ls_procede)
					{
						case "SCBBAH":
							$ls_codope="CH";
							break;
						case "SCBBAP":
							$ls_codope="DP";
							break;
						case "SCBBAC":
							$ls_codope="NC";
							break;
						case "SCBBAD":
							$ls_codope="ND";
							break;
					}
					$ls_sql="UPDATE scb_movbco ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdoc = '".$ls_numdoc."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.52");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_53()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_movcol ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',scb_movcol.numcol) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movcol ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||scb_movcol.numcol ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.53");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT scb_movcol.numcol, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, scb_movcol ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SCBCNC' OR tepuy_cmp.procede ='SCBCND' OR ".
					"		 tepuy_cmp.procede ='SCBCDP') ".
					"   AND tepuy_cmp.codemp = scb_movcol.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.53");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numcol=$row["numcol"];
					$ls_procede=$row["procede"];
					switch($ls_procede)
					{
						case "SCBCNC":
							$ls_codope="NC";
							break;
						case "SCBCND":
							$ls_codope="ND";
							break;
						case "SCBCDP":
							$ls_codope="DP";
							break;
					}
					$ls_sql="UPDATE scb_movcol ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numcol = '".$ls_numcol."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.53");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_54()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sob_asignacion ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',sob_asignacion.codasi) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sob_asignacion ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||sob_asignacion.codasi ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.54 ");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT sob_asignacion.codasi, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_asignacion ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SOBASI' ".
					"   AND tepuy_cmp.codemp = sob_asignacion.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.54");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codasi=$row["codasi"];
					$ls_sql="UPDATE sob_asignacion ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codasi = '".$ls_codasi."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.54");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT sob_asignacion.codasi, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_asignacion ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SOBRAS' OR tepuy_cmp.procede='SOBRPC') ".
					"   AND tepuy_cmp.codemp = sob_asignacion.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.54");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codasi=$row["codasi"];
					$ls_sql="UPDATE sob_asignacion ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codasi = '".$ls_codasi."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.54");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_55()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sob_contrato ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',sob_contrato.codcon) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sob_contrato ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||sob_contrato.codcon ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.55");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT sob_contrato.codcon, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_contrato ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SOBCON' ".
					"   AND tepuy_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.55");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcon=$row["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.55");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT sob_contrato.codcon, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_contrato ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SOBACO' ".
					"   AND tepuy_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.55");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcon=$row["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.55");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_56()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_dt_scg ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_dt_scg ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.56 ");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las nóminas 
			$ls_sql="SELECT sno_dt_scg.codcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_scg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_scg.tipnom = 'N' ".
					"   AND tepuy_cmp.codemp = sno_dt_scg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_scg.codcom ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.56 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcom"];
					$ls_sql="UPDATE sno_dt_scg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcom = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.56 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las nóminas 
			$ls_sql="SELECT sno_dt_scg.codcomapo, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_scg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_scg.tipnom = 'A' ".
					"   AND tepuy_cmp.codemp = sno_dt_scg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_scg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.56 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcomapo"];
					$ls_sql="UPDATE sno_dt_scg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.56 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_57()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_dt_spg ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_dt_spg ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.57 ");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las nóminas 
			$ls_sql="SELECT sno_dt_spg.codcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_spg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'N' ".
					"   AND tepuy_cmp.codemp = sno_dt_spg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_spg.codcom ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.57 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcom"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcom = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.57 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las nóminas 
			$ls_sql="SELECT sno_dt_spg.codcomapo, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_spg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'A' ".
					"   AND tepuy_cmp.codemp = sno_dt_spg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_spg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.57 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcomapo"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.57 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_58()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE saf_depreciacion ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',SUBSTR(saf_depreciacion.fecdep,6,2),SUBSTR(saf_depreciacion.fecdep,1,4))";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE saf_depreciacion ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			$ls_criterio="   AND tepuy_cmp.comprobante like '%'||SUBSTR(saf_depreciacion.fecdep,6,2)||SUBSTR(saf_depreciacion.fecdep,1,4)";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.58 ");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización 
			$ls_sql="SELECT saf_depreciacion.fecdep, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, saf_depreciacion ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SAFDPR' ".
					"   AND tepuy_cmp.codemp = saf_depreciacion.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.58 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_ano=substr($row["fecdep"],0,4);
					$ls_mes=substr($row["fecdep"],5,2);
					$ls_sql="UPDATE saf_depreciacion ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND SUBSTR(fecdep,1,4) = '".$ls_ano."' ".
							"   AND SUBSTR(fecdep,6,2) = '".$ls_mes."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.58 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_59()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_cmp_md ".
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_cmp_md ". 
					"  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01', ".
					"  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.59 ");	 		 
			 $lb_valido=false;
		}
		if($lb_valido)
		{ // para la fecha de contabilización
			$ls_sql="SELECT tepuy_cmp_md.comprobante, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, tepuy_cmp_md ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.tipo_comp = 2 ".
					"   AND tepuy_cmp.codemp = tepuy_cmp_md.codemp ".
					"   AND tepuy_cmp.comprobante = tepuy_cmp_md.comprobante ".
					"   AND tepuy_cmp.procede = tepuy_cmp_md.procede ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("Problemas al ejecutar Release 2.59 ");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_comprobante=$row["comprobante"];
					$ls_procede=$row["procede"];
					$ls_sql="UPDATE tepuy_cmp_md ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND comprobante = '".$ls_comprobante."' ".
							"   AND procede = '".$ls_procede."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_msg->message("Problemas al ejecutar Release 2.59 ");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_60()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN activo_t VARCHAR(3), ".
					"  ADD COLUMN pasivo_t VARCHAR(3), ".
					"  ADD COLUMN resultado_t VARCHAR(3), ".
					"  ADD COLUMN c_financiera VARCHAR(25), ".
					"  ADD COLUMN c_fiscal VARCHAR(25);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ".
					"  ADD COLUMN activo_t varchar(3), ".
					"  ADD COLUMN pasivo_t varchar(3), ".
					"  ADD COLUMN resultado_t varchar(3), ".
					"  ADD COLUMN c_financiera varchar(25), ".
					"  ADD COLUMN c_fiscal varchar(25); ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.60");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_61()
	{
		$lb_valido=true;
		$lb_existe=$this->uf_select_config("CFG","RELEASE","IVA9_BIENES");
		if(!$lb_existe)
		{
			$ls_sql="";
			$ld_date=date("d/m/Y");
			$lb_existe = $this->io_function_db->uf_select_column('tepuy_histcargosarticulos','codemp');
			if (!$lb_existe)
			{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					   $ls_sql="CREATE TABLE tepuy_histcargosarticulos (".
							   "             codemp CHAR(4) NOT NULL,".
							   "             codart CHAR(20) NOT NULL,".
							   "             codcarant CHAR(5) NOT NULL,".
							   "             fecregcar DATETIME NOT NULL,".
							   "             codcaract CHAR(5) NOT NULL,".
							   "             codestpro VARCHAR(33),".
							   "             spg_cuenta VARCHAR(25),".
							   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
							   ")".
							   " ENGINE = InnoDB";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							 $this->io_msg->message("Problemas al ejecutar ".$ls_sql);			 		 
							 $lb_valido=false;
						}
							   
					   break;
					   
					case "POSTGRE":
					   $ls_sql="CREATE TABLE tepuy_histcargosarticulos (".
							   "             codemp char(4) NOT NULL,".
							   "             codart char(20) NOT NULL,".
							   "             codcarant char(5) NOT NULL,".
							   "             fecregcar date NOT NULL,".
							   "             codcaract char(5) NOT NULL,".
							   "             codestpro varchar(33),".
							   "             spg_cuenta varchar(25),".
							   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
							   ")WITHOUT OIDS;";
					   break;
				}	
			}
			$lb_empresa=true;
			$ls_codemp=$_SESSION["la_empresa"]["codemp"];
			$ls_sql="SELECT codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom".
					"  FROM tepuy_cargos".
					" WHERE codemp='".$ls_codemp."'".
					"   AND porcar=11";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_43 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$this->io_sql->begin_transaction();
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_dencar="IVA 9%";//$row["dencar"];
					$ls_codestpro=$row["codestpro"];
					$ls_spgcuenta=$row["spg_cuenta"];
					$ls_estlibcom=$row["estlibcom"];
					$ls_porcar=9.00;
					$ls_formula="\$LD_MONTO*0.09";
					$ls_newcodcar=$this->io_function_db->uf_generar_codigo($lb_empresa,$ls_codemp,"tepuy_cargos","codcar");
					$lb_valido=$this->uf_insert_cargos($ls_codemp,$ls_newcodcar,$ls_dencar,$ls_codestpro,$ls_spgcuenta,
													   $ls_porcar,$ls_estlibcom,$ls_formula);
							
				}
				if($lb_valido==true)
				{
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
					break;
				}
				$this->io_sql->free_result($rs_data);
			}
			
			$ls_sql="SELECT siv_cargosarticulo.codart, siv_cargosarticulo.codcar,tepuy_cargos.codestpro,".
					"       tepuy_cargos.spg_cuenta,tepuy_cargos.estlibcom".
					"  FROM siv_cargosarticulo,tepuy_cargos".
					" WHERE siv_cargosarticulo.codemp='".$ls_codemp."'".
					"   AND siv_cargosarticulo.codemp=tepuy_cargos.codemp".
					"   AND siv_cargosarticulo.codcar=tepuy_cargos.codcar".
					"   AND tepuy_cargos.porcar=11";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_43 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$this->io_sql->begin_transaction();
				$ld_dateaux=date("Y-m-d");
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_codart=$row["codart"];
					$ls_codcarant=$row["codcar"];
					$ls_codestpro=$row["codestpro"];
					$ls_spgcuenta=$row["spg_cuenta"];
					$ls_sql="SELECT codcar".
							"  FROM tepuy_cargos".
							" WHERE codemp='".$ls_codemp."'".
							"   AND codestpro='".$ls_codestpro."'".
							"   AND spg_cuenta='".$ls_spgcuenta."'".
							"   AND porcar=9";
					$rs_datacar=$this->io_sql->select($ls_sql);
					if($rs_datacar===false)
					{
						$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_43 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
					}
					else
					{
						if($rowcargos=$this->io_sql->fetch_row($rs_datacar))
						{
							$ls_codcaract=$rowcargos["codcar"];
							$lb_valido=$this->uf_procesar_cargos($ls_codemp,$ls_codart,$ls_codcarant,$ld_dateaux,$ls_codcaract,$ls_codestpro,$ls_spgcuenta);
						}
					}
							
					$lb_valido=true;
				}
				$this->io_sql->free_result($rs_data);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_config("CFG","RELEASE","IVA9_BIENES");
			}
			if($lb_valido==true)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
			//if($li_row===false)
			//{ 
			//	 $this->io_msg->message("Problemas al ejecutar ".$ls_sql);			 		 
			//	 $lb_valido=false;
			//}
		}
       return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_62()
	{
		$lb_valido=true;
		$lb_existe=$this->uf_select_config("CFG","RELEASE","IVA9_SERVICIOS");
		if(!$lb_existe)
		{
			$ls_sql="";
			$ld_date=date("d/m/Y");
			$ls_codemp=$_SESSION["la_empresa"]["codemp"];
			$lb_existe = $this->io_function_db->uf_select_column('tepuy_histcargosservicios','codemp');
			if (!$lb_existe)
			{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					   $ls_sql="CREATE TABLE tepuy_histcargosservicios (".
							   "             codemp CHAR(4) NOT NULL,".
							   "             codser CHAR(10) NOT NULL,".
							   "             codcarant CHAR(5) NOT NULL,".
							   "             fecregcar DATETIME NOT NULL,".
							   "             codcaract CHAR(5) NOT NULL,".
							   "             codestpro VARCHAR(33),".
							   "             spg_cuenta VARCHAR(25),".
							   "  PRIMARY KEY(codemp, codser, codcarant, fecregcar, codcaract)".
							   ")".
							   " ENGINE = InnoDB";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							 $this->io_msg->message("Problemas al ejecutar ".$ls_sql);			 		 
							 $lb_valido=false;
						}
							   
					   break;
					   
					case "POSTGRE":
					   $ls_sql="CREATE TABLE tepuy_histcargosservicios (".
							   "             codemp char(4) NOT NULL,".
							   "             codser char(10) NOT NULL,".
							   "             codcarant char(5) NOT NULL,".
							   "             fecregcar date NOT NULL,".
							   "             codcaract char(5) NOT NULL,".
							   "             codestpro varchar(33),".
							   "             spg_cuenta varchar(25),".
							   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
							   ")WITHOUT OIDS;";
					   break;
				}	
			}
			
			$ls_sql="SELECT soc_serviciocargo.codser, soc_serviciocargo.codcar,tepuy_cargos.codestpro,".
					"       tepuy_cargos.spg_cuenta,tepuy_cargos.estlibcom".
					"  FROM soc_serviciocargo,tepuy_cargos".
					" WHERE soc_serviciocargo.codemp='".$ls_codemp."'".
					"   AND soc_serviciocargo.codemp=tepuy_cargos.codemp".
					"   AND soc_serviciocargo.codcar=tepuy_cargos.codcar".
					"   AND tepuy_cargos.porcar=11";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_44 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$this->io_sql->begin_transaction();
				$ld_dateaux=date("Y-m-d");
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_codser=$row["codser"];
					$ls_codcarant=$row["codcar"];
					$ls_codestpro=$row["codestpro"];
					$ls_spgcuenta=$row["spg_cuenta"];
					$ls_sql="SELECT codcar".
							"  FROM tepuy_cargos".
							" WHERE codemp='".$ls_codemp."'".
							"   AND codestpro='".$ls_codestpro."'".
							"   AND spg_cuenta='".$ls_spgcuenta."'".
							"   AND porcar=9";
					$rs_datacar=$this->io_sql->select($ls_sql);
					if($rs_datacar===false)
					{
						$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_44 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
					}
					else
					{
						if($rowcargos=$this->io_sql->fetch_row($rs_datacar))
						{
							$ls_codcaract=$rowcargos["codcar"];
							$lb_valido=$this->uf_procesar_cargosserv($ls_codemp,$ls_codser,$ls_codcarant,$ld_dateaux,$ls_codcaract,$ls_codestpro,$ls_spgcuenta);
						}
					}
							
					$lb_valido=true;
				}
				$this->io_sql->free_result($rs_data);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_config("CFG","RELEASE","IVA9_SERVICIOS");
			}
			if($lb_valido==true)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
//			if($li_row===false)
//			{ 
//				 $this->io_msg->message("Problemas al ejecutar ".$ls_sql);			 		 
//				 $lb_valido=false;
//			}
		}
		return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_63()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_nomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_nomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.63");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_64()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hnomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hnomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.64");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_65()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thnomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thnomina  ".
					"  ADD COLUMN conpronom  VARCHAR(1) DEFAULT 0; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.65");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_66()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_concepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_concepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.66");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_67()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hconcepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hconcepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.67");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_68()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thconcepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thconcepto  ".
					"  ADD COLUMN conprocon  VARCHAR(1) DEFAULT '0'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.68");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_69()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_proyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   PRIMARY KEY (codemp, codproy) ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_proyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   CONSTRAINT PK_sno_proyecto PRIMARY KEY (codemp, codproy), ".
					"	CONSTRAINT fk_empresa_sno_proyecto FOREIGN KEY (codemp) ".
					"   	REFERENCES tepuy_empresa (codemp) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.69");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_proyecto add constraint fk_empresa_sno_proyecto ".
						" foreign key (codemp) references tepuy_empresa ".
						"(codemp) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.69");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_70()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_proyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper DOUBLE(19,4), ".
					"   totdiames DOUBLE(19,4), ".
					"   PRIMARY KEY (codemp, codnom, codproy, codper) ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_proyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper FLOAT8, ".
					"   totdiames FLOAT8, ".
					"   CONSTRAINT Pk_sno_proyectopersonal PRIMARY KEY (codemp, codnom, codproy, codper), ".
					"	CONSTRAINT fk_sno_personalnomina__sno_proyectopersonal FOREIGN KEY (codemp, codper, codnom) ".
					"		REFERENCES sno_personalnomina (codemp, codper, codnom) ON UPDATE NO ACTION ON DELETE NO ACTION, ".
					"	CONSTRAINT fk_sno_proyecto_sno_proyectopersonal FOREIGN KEY (codemp, codproy) ".
					"       REFERENCES sno_proyecto (codemp, codproy) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.70");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_proyectopersonal add constraint fk_sno_personalnomina__sno_proyectopersonal ".
						" foreign key (codemp, codper, codnom) references sno_personalnomina ".
						"(codemp, codper, codnom) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.70");	
					 $lb_valido=false;
				}
			}
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_proyectopersonal add constraint fk_sno_proyecto_sno_proyectopersonal ".
						" foreign key (codemp, codproy) references sno_proyecto (codemp, codproy) ".
						" on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.70");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_71()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_hproyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   PRIMARY KEY  (codemp, codnom, anocur, codperi, codproy) ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_hproyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   CONSTRAINT PK_sno_hproyecto PRIMARY KEY (codemp, codnom, anocur, codperi, codproy), ".
					"	CONSTRAINT fk_sno_hpro_sno_hnomi_sno_hnom FOREIGN KEY (codemp, codnom, anocur, codperi) ".
					"      REFERENCES sno_hnomina (codemp, codnom, anocurnom, peractnom) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.71");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_hproyecto add constraint fk_sno_hnomina__sno_hproyecto ".
						" foreign key (codemp, codnom, anocur, codperi) references sno_hnomina ".
						"(codemp, codnom, anocurnom, peractnom) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.71");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_72()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_hproyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper DOUBLE(19,4), ".
					"   totdiames DOUBLE(19,4), ".
					"   PRIMARY KEY (codemp, codnom, anocur, codperi, codproy, codper) ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_hproyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper FLOAT8, ".
					"   totdiames FLOAT8, ".
					"   CONSTRAINT Pk_sno_hproyectopersonal PRIMARY KEY (codemp, codnom, anocur, codperi, codproy, codper), ".
					"	CONSTRAINT fk_sno_hpersonalnomina__sno_hproyectopersonal FOREIGN KEY (codemp, codnom, codperi, codper, anocur) ".
					"		REFERENCES sno_hpersonalnomina (codemp, codnom, codperi, codper, anocur) ON UPDATE NO ACTION ON DELETE NO ACTION, ".
					"	CONSTRAINT fk_sno_hproyecto__sno_hproyectopersonal FOREIGN KEY (codemp, codnom, anocur, codperi, codproy) ".
					"		REFERENCES sno_hproyecto (codemp, codnom, anocur, codperi, codproy) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.72");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_hproyectopersonal add constraint fk_sno_hpersonalnomina__sno_hproyectopersonal ".
						"foreign key (codemp, codnom, codperi, codper, anocur) references sno_hpersonalnomina ".
						"(codemp, codnom, codperi, codper, anocur) on delete restrict on update restrict; ";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.72");	
					 $lb_valido=false;
				}
			}
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_hproyectopersonal add constraint fk_sno_hproyecto__sno_hproyectopersonal ".
						"foreign key (codemp, codnom, anocur, codperi, codproy) references sno_hproyecto ".
						"(codemp, codnom, anocur, codperi, codproy) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.72");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_73()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_thproyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   PRIMARY KEY  (codemp, codnom, anocur, codperi, codproy)  ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_thproyecto ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   nomproy VARCHAR(50), ".
					"   estproproy VARCHAR(33), ".
					"   CONSTRAINT PK_sno_thproyecto PRIMARY KEY (codemp, codnom, anocur, codperi, codproy) ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.73");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_74()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_thproyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper DOUBLE(19,4), ".
					"   totdiames DOUBLE(19,4), ".
					"   PRIMARY KEY (codemp, codnom, anocur, codperi, codproy, codper) ".
					") ".
					" ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_thproyectopersonal ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codnom CHAR(4) NOT NULL, ".
					"   anocur CHAR(4) NOT NULL, ".
					"   codperi CHAR(3) NOT NULL, ".
					"   codproy CHAR(10) NOT NULL, ".
					"   codper CHAR(10) NOT NULL, ".
					"   totdiaper FLOAT8, ".
					"   totdiames FLOAT8, ".
					"   CONSTRAINT Pk_sno_thproyectopersonal PRIMARY KEY (codemp, codnom, anocur, codperi, codproy, codper) ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.74");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_75()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_proyectopersonal  ".
					"  ADD COLUMN pordiames  DOUBLE(19,4); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_proyectopersonal  ".
					"  ADD COLUMN pordiames  FLOAT8; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.75");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_76()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hproyectopersonal  ".
					"  ADD COLUMN pordiames  DOUBLE(19,4); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hproyectopersonal  ".
					"  ADD COLUMN pordiames  FLOAT8; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.76");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_77()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thproyectopersonal  ".
					"  ADD COLUMN pordiames  DOUBLE(19,4); ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thproyectopersonal  ".
					"  ADD COLUMN pordiames  FLOAT8; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.77");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_78()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_rd_scg ADD COLUMN estasicon VARCHAR(1) NOT NULL DEFAULT 'M', ".
					" DROP PRIMARY KEY, ".
					"  ADD PRIMARY KEY (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta,estasicon);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_rd_scg DROP CONSTRAINT pk_cxp_rd_scg; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.78");
			 $lb_valido=false;
		}
		if($_SESSION["ls_gestor"]=="POSTGRE")
		{
			$ls_sql="ALTER TABLE cxp_rd_scg ADD COLUMN estasicon varchar(1) NOT NULL DEFAULT 'M'; ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.78");
				 $lb_valido=false;
			}
		}
		if($_SESSION["ls_gestor"]=="POSTGRE")
		{
			$ls_sql="ALTER TABLE cxp_rd_scg ADD CONSTRAINT pk_cxp_rd_scg ".
					"PRIMARY KEY (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta,estasicon);";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.78");
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_79()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_componente ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codcom CHAR(10) NOT NULL, ".
					"   descom VARCHAR(100), ".
					"   PRIMARY KEY (codemp, codcom) ".
					") ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_componente ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codcom CHAR(10) NOT NULL, ".
					"   descom VARCHAR(100), ".
					"   CONSTRAINT Pk_sno_componente PRIMARY KEY (codemp, codcom), ".
					"	CONSTRAINT fk_empresa__sno_componente  FOREIGN KEY (codemp) ".
					"       REFERENCES tepuy_empresa (codemp) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.79");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_componente add constraint fk_empresa__sno_componente ".
						"foreign key (codemp) references tepuy_empresa ".
						"(codemp) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.79");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_80()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_rango ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codcom CHAR(10) NOT NULL, ".
					"   codran CHAR(10) NOT NULL, ".
					"   desran VARCHAR(100), ".
					"   PRIMARY KEY (codemp, codcom, codran) ".
					") ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_rango ".
					"( ".
					"   codemp CHAR(4) NOT NULL, ".
					"   codcom CHAR(10) NOT NULL, ".
					"   codran CHAR(10) NOT NULL, ".
					"   desran VARCHAR(100), ".
					"   CONSTRAINT Pk_sno_rango PRIMARY KEY (codemp, codcom, codran), ".
					"	CONSTRAINT fk_sno_componente__sno_rango  FOREIGN KEY (codemp, codcom) ".
					"       REFERENCES sno_componente (codemp, codcom) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") ".
					"WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.80");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_rango add constraint fk_sno_componente__sno_rango ".
						"foreign key (codemp, codcom) references sno_componente ".
						"(codemp, codcom) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.79");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_81()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN codcom CHAR(10), ".
					"  ADD COLUMN codran CHAR(10), ".
					"  ADD COLUMN numexpper VARCHAR(20) ;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN codcom CHAR(10), ".
					"  ADD COLUMN codran CHAR(10), ".
					"  ADD COLUMN numexpper VARCHAR(20) ;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.81");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_82()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN numexpper VARCHAR(20) ;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN numexpper VARCHAR(20) ;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.82");
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_83()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_rd ADD COLUMN codfuefin CHAR(2) NOT NULL DEFAULT '--';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_rd ADD COLUMN codfuefin char(2) NOT NULL DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.83");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE cxp_rd ".
						"ADD CONSTRAINT FK_cxp_rd_fuente FOREIGN KEY FK_cxp_rd_fuente (codemp, codfuefin) ".
						"REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON DELETE RESTRICT ON UPDATE RESTRICT";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE cxp_rd ADD CONSTRAINT fk_cxp_rd_fuente FOREIGN KEY (codemp, codfuefin) ".
						"REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON UPDATE NO ACTION ON DELETE NO ACTION;";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.83");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_84()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_rd_spg ".
					"  ADD COLUMN codfuefin CHAR(2) NOT NULL DEFAULT '--', ".
					" DROP PRIMARY KEY, ".
					"  ADD PRIMARY KEY(codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro, spg_cuenta, codfuefin);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_rd_spg ADD COLUMN codfuefin char(2) DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.84");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE cxp_rd_spg DROP CONSTRAINT pk_cxp_rd_spg; ".
						"ALTER TABLE cxp_rd_spg ADD CONSTRAINT fk_cxp_rd_spg ".
						"PRIMARY KEY (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro, spg_cuenta, codfuefin);";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.84");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_85()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN codasiona VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN codasiona varchar(10);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.85");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_86()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_nomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_nomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.86");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_87()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hnomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hnomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.87");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_88()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thnomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thnomina ADD COLUMN titrepnom VARCHAR(50);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.88");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_89()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN codusu CHAR(30) NOT NULL DEFAULT '--',".
					" DROP PRIMARY KEY, ".
					"  ADD PRIMARY KEY (codemp, codsis, procede, codusu);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN codusu char(30) NOT NULL DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.89");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE tepuy_ctrl_numero DROP CONSTRAINT pk_tepuy_ctrl_numero; ".
						"ALTER TABLE tepuy_ctrl_numero ADD CONSTRAINT pk_tepuy_ctrl_numero PRIMARY KEY (codemp, codsis, procede, codusu);";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2.89");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_90()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.90");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_91()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hpersonalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hpersonalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.91");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_92()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thpersonalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thpersonalnomina ADD COLUMN codunirac VARCHAR(10);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.92");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_93()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN estact SMALLINT DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_ctrl_numero ADD COLUMN estact int2 DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.93");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE scb_dt_cmp_ret MODIFY COLUMN desope VARCHAR(500);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE scb_dt_cmp_ret ALTER desope TYPE varchar(500);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2.93");	
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_94()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE tepuy_cmp_md ".
					 " ADD COLUMN codfuefin VARCHAR(2), ".
					 " ADD COLUMN coduac VARCHAR(5);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE tepuy_cmp_md ".
					 " ADD COLUMN codfuefin VARCHAR(2), ".
					 " ADD COLUMN coduac VARCHAR(5);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.94");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_95()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_nomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_nomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.95");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_96()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hnomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hnomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.96");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_97()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thnomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thnomina ADD COLUMN codorgcestic VARCHAR(4);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.97");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_98()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personalnomina ADD COLUMN pagtaqper SMALLINT(6) NOT NULL DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personalnomina ADD COLUMN pagtaqper int2 NOT NULL DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.98");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2_99()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hpersonalnomina ADD COLUMN pagtaqper SMALLINT(6) NOT NULL DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hpersonalnomina ADD COLUMN pagtaqper int2 NOT NULL DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2.99");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_00()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thpersonalnomina ADD COLUMN pagtaqper SMALLINT(6) NOT NULL DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thpersonalnomina ADD COLUMN pagtaqper int2 NOT NULL DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.00");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_01()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_metodobanco ADD COLUMN pagtaqnom VARCHAR(1) DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_metodobanco ADD COLUMN pagtaqnom VARCHAR(1) DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.01");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_02()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN codpainac VARCHAR(3) DEFAULT '---',".
					"  ADD COLUMN codestnac VARCHAR(3) DEFAULT '---';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personal ".
					"  ADD COLUMN codpainac VARCHAR(3) DEFAULT '---',".
					"  ADD COLUMN codestnac VARCHAR(3) DEFAULT '---';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.02");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_03()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE cxp_rd_deducciones ADD COLUMN estcmp VARCHAR(1) NOT NULL DEFAULT '0';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE cxp_rd_deducciones ADD COLUMN estcmp VARCHAR(1) NOT NULL DEFAULT '0';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.02 1");	
			 $lb_valido=false;
		}
		$ls_sql="UPDATE cxp_rd_deducciones ".
				"   SET estcmp='1' ".
				" WHERE ((codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')) OR (codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1'))) ".
				"	AND (numrecdoc IN (SELECT DS.numrecdoc FROM cxp_dt_solicitudes DS, scb_dt_cmp_ret DCR WHERE DCR.numsop=DS.numsol )) ".
				"	AND (ced_bene IN (SELECT DS.ced_bene FROM cxp_dt_solicitudes DS, scb_dt_cmp_ret DCR WHERE DCR.numsop=DS.numsol ));";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 2");
			$lb_valido=false;
		}
		$ls_sql="UPDATE cxp_rd_deducciones ".
				"   SET estcmp='1' ".
				"  WHERE ((codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')) OR (codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1'))) ".
				"	 AND (numrecdoc IN (SELECT DS.numrecdoc FROM cxp_dt_solicitudes DS, scb_dt_cmp_ret DCR WHERE DCR.numsop=DS.numsol )) ".
				"    AND (cod_pro IN (SELECT DS.cod_pro FROM cxp_dt_solicitudes DS, scb_dt_cmp_ret DCR WHERE DCR.numsop=DS.numsol ));";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 3");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_sol_dc (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, desope, fecope, monto, estnotadc, estafe, estapr, codusuapr, fecaprnc, fechaconta, fechaanula) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'NC', numdc, desope, fecope, monto, estnotadc, estafe, estapr, codusuapr, fecaprnc, fechaconta, fechaanula ".
				"       FROM cxp_sol_dc ".
				"      WHERE codope='C'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 4");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_dc_scg (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, debhab, sc_cuenta, monto, estgenasi) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'NC', numdc, debhab, sc_cuenta, monto, estgenasi ".
				"       FROM cxp_dc_scg ".
				"      WHERE codope='C'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 5");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_dc_spg (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, codestpro, spg_cuenta, monto) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'NC', numdc, codestpro, spg_cuenta, monto ".
				"       FROM cxp_dc_spg ".
				"      WHERE codope='C'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 6");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_sol_dc (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, desope, fecope, monto, estnotadc, estafe, estapr, codusuapr, fecaprnc, fechaconta, fechaanula) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'ND', numdc, desope, fecope, monto, estnotadc, estafe, estapr, codusuapr, fecaprnc, fechaconta, fechaanula ".
				"       FROM cxp_sol_dc ".
				"      WHERE codope='D'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 7");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_dc_scg (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, debhab, sc_cuenta, monto, estgenasi) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'ND', numdc, debhab, sc_cuenta, monto, estgenasi ".
				"       FROM cxp_dc_scg ".
				"      WHERE codope='D'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 8");
			$lb_valido=false;
		}
		$ls_sql="INSERT INTO cxp_dc_spg (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, codestpro, spg_cuenta, monto) ".
				"     SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 'ND', numdc, codestpro, spg_cuenta, monto ".
				"       FROM cxp_dc_spg ".
				"      WHERE codope='D'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 9");
			$lb_valido=false;
		}
		$ls_sql="DELETE FROM cxp_dc_scg WHERE (codope='D' OR codope='C')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 10");
			$lb_valido=false;
		}
		$ls_sql="DELETE FROM cxp_dc_spg WHERE (codope='D' OR codope='C')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 11");
			$lb_valido=false;
		}
		$ls_sql="DELETE FROM cxp_sol_dc WHERE (codope='D' OR codope='C')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.02 12");
			$lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_04()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_cartaorden ADD COLUMN archrtf VARCHAR(50);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_cartaorden ADD COLUMN archrtf VARCHAR(50);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.04");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_05()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE siv_config ADD COLUMN estcmp SMALLINT(6) DEFAULT 1;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE siv_config ADD COLUMN estcmp int2 DEFAULT 1;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.05 1 ");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_06()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE siv_articulo ".
					"  ADD COLUMN serart VARCHAR(25), ".
					"  ADD COLUMN ubiart VARCHAR(10), ".
					"  ADD COLUMN docart VARCHAR(20), ".
					"  ADD COLUMN fabart VARCHAR(100);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE siv_articulo ".
					"  ADD COLUMN serart VARCHAR(25), ".
					"  ADD COLUMN ubiart VARCHAR(10), ".
					"  ADD COLUMN docart VARCHAR(20), ".
					"  ADD COLUMN fabart VARCHAR(100);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.06");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_07()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE tepuy_catalogo_milco ".
					"( ".
					"	codmil VARCHAR(15) NOT NULL, ".
					"   denmil VARCHAR(100) NOT NULL, ".
					"	PRIMARY KEY (codmil) ".
					") ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE tepuy_catalogo_milco ".
					"( ".
					"   codmil varchar(15) NOT NULL, ".
					"   denmil varchar(100) NOT NULL, ".
					"   CONSTRAINT codmil PRIMARY KEY (codmil) ".
					") WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.07");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_08()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE siv_articulo ADD COLUMN codmil VARCHAR(15) DEFAULT NULL;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE siv_articulo ADD COLUMN codmil varchar(15) DEFAULT NULL;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.08");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_09()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE soc_tiposervicio ADD COLUMN codmil VARCHAR(15);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE soc_tiposervicio ADD COLUMN codmil varchar(15);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.09");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_10()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE scb_movbco_fuefinanciamiento ".
					"( ".
					"   codemp char(4) not null, ".
					"   codban char(3) not null, ".
					"   ctaban char(25) not null, ".
					"   numdoc char(15) not null, ".
					"   codope char(2) not null, ".
					"   estmov char(1) not null, ".
					"   codfuefin char(2) not null, ".
					"   PRIMARY KEY (codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) ".
					") ".
					"type = innodb;";
			break;
			
			case "POSTGRE":
			$ls_sql="CREATE TABLE scb_movbco_fuefinanciamiento ".
					"( ".
					"   codemp char(4) NOT NULL, ". 
					"   codban char(3) NOT NULL, ".
					"   ctaban char(25) NOT NULL, ".
					"   numdoc char(15) NOT NULL, ".
					"   codope char(2) NOT NULL, ".
					"   estmov char(1) NOT NULL, ".
					"   codfuefin char(2) NOT NULL, ". 
					"   CONSTRAINT pk_scb_movbco_fuefinanciamiento PRIMARY KEY (codemp, codban, ctaban, numdoc, codope, estmov, codfuefin), ".
					"   CONSTRAINT fk_scb_movbco__scb_movbcofuente FOREIGN KEY (codemp, codban, ctaban, numdoc, codope, estmov) REFERENCES scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov)    ON UPDATE NO ACTION ON DELETE NO ACTION, ".
					"   CONSTRAINT fk_tepuy_fuente__scb_movbco_fuente FOREIGN KEY (codemp, codfuefin) REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin)    ON UPDATE NO ACTION ON DELETE NO ACTION ".
					") WITHOUT OIDS;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.10");	
			 $lb_valido=false;
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_sql="alter table scb_movbco_fuefinanciamiento add constraint fk_scb_movbco__scb_movbcofuente foreign key (codemp, codban, ctaban, numdoc, codope, estmov) ".
					"      references scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov) on delete restrict on update restrict;";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.10 2");	
				 $lb_valido=false;
			}
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_sql="alter table scb_movbco_fuefinanciamiento add constraint fk_tepuy_fuente__scb_movbco_fuente foreign key (codemp, codfuefin) ".
					"      references tepuy_fuentefinanciamiento (codemp, codfuefin) on delete restrict on update restrict;";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.10 3");	
				 $lb_valido=false;
			}
		}
		$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento (codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) ".
				"SELECT codemp, codban, ctaban, numdoc, codope, estmov, (CASE length(codfuefin) WHEN 2 THEN codfuefin ELSE '--' END) ".
				"  FROM scb_movbco";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.10 4");
			$lb_valido=false;
		}
		$ls_sql="UPDATE scb_movbco SET codfuefin= '--'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("Problemas al ejecutar Release 3.10 5");
			$lb_valido=false;
		}
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_movbco ALTER COLUMN codfuefin SET DEFAULT '--';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movbco ALTER COLUMN codfuefin SET DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.10 6");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_11()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE spg_ep3 add COLUMN codfuefin CHAR(2) DEFAULT '--';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE spg_ep3 add COLUMN codfuefin CHAR(2) DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.11");	
			 $lb_valido=false;
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE spg_ep3 ADD CONSTRAINT FK_spg_ep3_tepuy_fuentefinanciamiento FOREIGN KEY FK_spg_ep3_tepuy_fuentefinanciamiento (codemp, codfuefin) ".
					"    REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE spg_ep3 ADD CONSTRAINT FK_spg_ep3_tepuy_fuentefinanciamiento FOREIGN KEY (codemp, codfuefin) ".
					"	 REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON UPDATE NO ACTION ON DELETE NO ACTION;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.11");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_12()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE spg_ep5 add COLUMN codfuefin CHAR(2) DEFAULT '--';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE spg_ep5 add COLUMN codfuefin CHAR(2) DEFAULT '--';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.12");	
			 $lb_valido=false;
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE spg_ep5 ADD CONSTRAINT FK_spg_ep5_tepuy_fuentefinanciamiento FOREIGN KEY FK_spg_ep5_tepuy_fuentefinanciamiento (codemp, codfuefin) ".
					"    REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE spg_ep5 ADD CONSTRAINT FK_spg_ep5_tepuy_fuentefinanciamiento FOREIGN KEY (codemp, codfuefin) ".
					"	 REFERENCES tepuy_fuentefinanciamiento (codemp, codfuefin) ON UPDATE NO ACTION ON DELETE NO ACTION;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.11");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_13()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_constante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_constante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.13");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_14()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hconstante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hconstante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.14");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_15()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thconstante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thconstante add COLUMN conespseg CHAR(1) DEFAULT '0';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.15");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_16()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_archivotxt ".
					"(".
					"   codemp  char(4) not null, ".
					"   codarch char(4) not null, ".
					"   denarch varchar(120) not null, ".
					"   PRIMARY KEY (codemp, codarch) ".
					") type = innodb;";
			break;

			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_archivotxt ".
					"(".
					"   codemp  char(4) not null, ".
					"   codarch char(4) not null, ".
					"   denarch varchar(120) not null, ".
					"   CONSTRAINT Pk_sno_archivotxt PRIMARY KEY (codemp, codarch), ".
					"	CONSTRAINT fk_empresa__archivotxt  FOREIGN KEY (codemp) ".
					"       REFERENCES tepuy_empresa (codemp) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					")WITHOUT OIDS; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.16");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_archivotxt add constraint fk_empresa__archivotxt foreign key (codemp) ".
						"      references tepuy_empresa (codemp) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.16");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_17()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_archivotxtcampo ".
					"(".
					"   codemp  char(4) not null, ".
					"   codarch char(4) not null, ".
					"   codcam  int not null, ".
					"   descam  varchar(20) not null, ".
					"   inicam  int not null, ".
					"   loncam  int not null, ".
					"   edicam  char(1) not null, ".
					"   clacam  char(1) not null, ".
					"   actcam  char(1) not null, ".
					"	cricam  varchar(255), ".
					"   tabrelcam varchar(50) not null, ".
					"   iterelcam varchar(30) not null, ".
					"   PRIMARY KEY (codemp, codarch, codcam), ".
					"	CONSTRAINT fk_sno_archivotxt__sno_archivotxtcampo FOREIGN KEY sno_archivotxtcampo (codemp, codarch) ".
					"	REFERENCES sno_archivotxt (codemp, codarch) ON DELETE RESTRICT ON UPDATE RESTRICT ".
					") type = innodb;";
			break;

			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_archivotxtcampo ".
					"(".
					"   codemp  char(4) not null, ".
					"   codarch char(4) not null, ".
					"   codcam  int2 not null, ".
					"   descam  varchar(20) not null, ".
					"   inicam  int2 not null, ".
					"   loncam  int2 not null, ".
					"   edicam  char(1) not null, ".
					"   clacam  char(1) not null, ".
					"   actcam  char(1) not null, ".
					"	cricam  varchar(255), ".
					"   tabrelcam varchar(50) not null, ".
					"   iterelcam varchar(30) not null, ".
					"   CONSTRAINT Pk_sno_archivotxtcampo PRIMARY KEY (codemp, codarch, codcam), ".
					"	CONSTRAINT fk_sno_archivotxt__sno_archivotxtcampo  FOREIGN KEY (codemp, codarch) ".
					"       REFERENCES sno_archivotxt (codemp, codarch) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					")WITHOUT OIDS; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.17");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_18()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_tabulador add COLUMN maxpasgra INTEGER DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_tabulador add COLUMN maxpasgra int2 DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.18");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_19()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_htabulador add COLUMN maxpasgra INTEGER DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_htabulador add COLUMN maxpasgra int2 DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.19");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_20()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thtabulador add COLUMN maxpasgra INTEGER DEFAULT 0;";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thtabulador add COLUMN maxpasgra int2 DEFAULT 0;";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.20");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_21()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="CREATE TABLE sno_beneficiario ".
					"(".
					"	codemp char(4)  not null,  ".
					"   codper char(10) not null, ".
					"   codben char(10) not null, ".
					"   cedben varchar(10), ".
					"   nomben varchar(50), ".
					"   apeben varchar(50), ".
					"   dirben varchar(100), ".
					"   telben varchar(80), ".
					"   tipben char(1), ".
					"   nomcheben varchar(150), ".
					"   porpagben double(19,4), ".
					"   monpagben double(19,4), ".
					"   codban char(3), ".
					"   ctaban varchar(25), ".
					"   sc_cuenta varchar(25), ".
					"   primary key (codemp, codper, codben) ".
					") type = innodb;";
			break;

			case "POSTGRE":
			$ls_sql="CREATE TABLE sno_beneficiario ".
					"(".
					"	codemp char(4)  not null,  ".
					"   codper char(10) not null, ".
					"   codben char(10) not null, ".
					"   cedben varchar(10), ".
					"   nomben varchar(50), ".
					"   apeben varchar(50), ".
					"   dirben varchar(100), ".
					"   telben varchar(80), ".
					"   tipben char(1), ".
					"   nomcheben varchar(150), ".
					"   porpagben float8, ".
					"   monpagben float8, ".
					"   codban char(3), ".
					"   ctaban varchar(25), ".
					"   sc_cuenta varchar(25), ".
					"   CONSTRAINT Pk_sno_beneficiario PRIMARY KEY (codemp, codper, codben), ".
					"	CONSTRAINT fk_sno_personal__sno_beneficiario FOREIGN KEY (codemp, codper) ".
					"       REFERENCES sno_personal (codemp, codper) ON UPDATE NO ACTION ON DELETE NO ACTION ".
					")WITHOUT OIDS; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.21");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_beneficiario add constraint fk_sno_personal__sno_beneficiario foreign key (codemp, codper) ".
						"      references sno_personal (codemp, codper) on delete restrict on update restrict;";
				break;
				
				case "POSTGRE":
				$ls_sql="";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.21");	
					 $lb_valido=false;
				}
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_22()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_beneficiario ADD COLUMN forpagben CHAR(1);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_beneficiario ADD COLUMN forpagben CHAR(1);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.22");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_23()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_archivotxtcampo ADD COLUMN tipcam CHAR(1) NOT NULL DEFAULT 'C';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_archivotxtcampo ADD COLUMN tipcam CHAR(1) NOT NULL DEFAULT 'C';";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.23");	
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE sno_estudiorealizado MODIFY COLUMN insestrea VARCHAR(254);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE sno_estudiorealizado ALTER insestrea TYPE varchar(254);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.23");	
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE sno_estudiorealizado MODIFY COLUMN desestrea VARCHAR(254);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE sno_estudiorealizado ALTER desestrea TYPE varchar(254);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.23");	
				 $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE sno_estudiorealizado MODIFY COLUMN titestrea VARCHAR(254);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE sno_estudiorealizado ALTER titestrea TYPE varchar(254);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.23");	
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_24()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_beneficiario ".
					" ADD COLUMN nacben CHAR(1), ".
					" ADD COLUMN tipcueben CHAR(1);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_beneficiario ".
					" ADD COLUMN nacben CHAR(1), ".
					" ADD COLUMN tipcueben CHAR(1);";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.24");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 function uf_create_release_db_libre_V_3_25()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE rpc_beneficiario ".
					" ADD  COLUMN tipcuebanben  VARCHAR(1) ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE rpc_beneficiario ".
					" ADD  COLUMN tipcuebanben  VARCHAR(1) ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.25");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 function uf_create_release_db_libre_V_3_26()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE scb_cheques  ".
					" ADD COLUMN estins smallint(6) default 0; ";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_cheques  ".
					" ADD COLUMN estins int2 default 0; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.26");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 function uf_create_release_db_libre_V_3_27()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE soc_ordencompra   ".
						" ADD COLUMN uniejeaso TEXT ";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE soc_ordencompra   ".
						" ADD COLUMN uniejeaso text; ";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.27");	
				 $lb_valido=false;
			}
			return $lb_valido;	
		} // end function 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_28()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE soc_enlace_sep    ".
						"ADD COLUMN coduniadm VARCHAR(10);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE soc_enlace_sep    ".
						" ADD COLUMN coduniadm VARCHAR(10); ";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.28");	
				 $lb_valido=false;
			}
			return $lb_valido;	
		} // end function 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_29()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE soc_dt_bienes    ".
						"ADD COLUMN coduniadm VARCHAR(10) NOT NULL;";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE soc_dt_bienes    ".
						" ADD COLUMN coduniadm VARCHAR(10); ";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.29");	
				 $lb_valido=false;
			}
			return $lb_valido;	
		} // end function 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_30()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE soc_dt_servicio   ".
						" ADD COLUMN coduniadm VARCHAR(10) NOT NULL";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE soc_dt_servicio   ".
						"  ADD COLUMN coduniadm VARCHAR(10);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.30");	
				 $lb_valido=false;
			}
			return $lb_valido;	
		} // end function 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_31()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE tepuy_empresa   ".
						" ADD COLUMN diacadche VARCHAR(3)  AFTER codasiona ";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE tepuy_empresa    ".
						" ADD COLUMN diacadche VARCHAR(3);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.31");	
				 $lb_valido=false;
			}
			return $lb_valido;	
		} // end function 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_32()
		{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_32
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: Crea los campos (codrespri,codresuso,coduniadm,ubigeoact,tiprespri,tipresuso,fecentact) 
		//                 en la tabla saf_movimiento
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creacion: 07/01/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   $lb_valido=true;
		   $ls_sql="";
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
				   $ls_sql="ALTER TABLE saf_movimiento  ".
						   "  ADD COLUMN codrespri VARCHAR(10) DEFAULT ' ' AFTER estpromov, ".
						   "  ADD COLUMN codresuso VARCHAR(10) DEFAULT ' ' AFTER codrespri, ".
						   "  ADD COLUMN coduniadm VARCHAR(10) DEFAULT ' ' AFTER codresuso, ".
						   "  ADD COLUMN ubigeoact VARCHAR(100) DEFAULT ' ' AFTER coduniadm, ".
						   "  ADD COLUMN tiprespri VARCHAR(1) DEFAULT ' ' AFTER ubigeoact, ".
						   "  ADD COLUMN tipresuso VARCHAR(1) DEFAULT ' ' AFTER tiprespri, ".
						   "  ADD COLUMN fecentact DATETIME AFTER tipresuso;";
				   break;
				   
				case "POSTGRE":
				   $ls_sql="ALTER TABLE saf_movimiento  ".
						   "  ADD COLUMN codrespri varchar(10)  DEFAULT ' ', ".
						   "  ADD COLUMN codresuso varchar(10)  DEFAULT ' ', ".
						   "  ADD COLUMN coduniadm varchar(10)  DEFAULT ' ', ".
						   "  ADD COLUMN ubigeoact varchar(100) DEFAULT ' ', ".
						   "  ADD COLUMN tiprespri varchar(1)   DEFAULT ' ', ".
						   "  ADD COLUMN tipresuso varchar(1)   DEFAULT ' ', ".
						   "  ADD COLUMN fecentact date;";
				   break;
			}	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 1.01");
				 $lb_valido=false;
			}
		   return $lb_valido;	
		} // end function uf_create_release_db_libre_V_3_32
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_create_release_db_libre_V_3_33()
		{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_33
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: Crea el campo (estcat) en la tabla saf_dt_movimiento;
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creacion: 07/01/2008 								Fecha Ultima Modificacion : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    $lb_valido=true;
			$ls_sql="";
			$lb_existe=false;
			$lb_existe = $this->io_function_db->uf_select_column('saf_causas','estcat');
			if ($lb_existe)
			{  
				$lb_valido=true; 
			}else 
			{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
						$ls_sql="  ALTER TABLE saf_causas ADD COLUMN estcat SMALLINT(6) NOT NULL DEFAULT 1;";
						break;
			
					case "POSTGRE":
						$ls_sql="  ALTER TABLE saf_causas ADD COLUMN estcat smallint NOT NULL DEFAULT 1;";
						break;
				}

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-1");	
					 $lb_valido=false;
				}
			}
			
			if($lb_valido)
			{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					$ls_sql="  ALTER TABLE saf_movimiento ADD COLUMN estcat SMALLINT(6) NOT NULL DEFAULT 1;";
					break;
					
					case "POSTGRE":
					$ls_sql="  ALTER TABLE saf_movimiento ADD COLUMN estcat smallint NOT NULL DEFAULT 1;";
					break;
				}

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-2");	
					 $lb_valido=false;
				}	
			}
			
			if($lb_valido)
			{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					$ls_sql=" ALTER TABLE saf_dt_movimiento ADD COLUMN estcat SMALLINT(6) NOT NULL DEFAULT 1;";
					break;
					
					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_dt_movimiento ADD COLUMN estcat smallint NOT NULL DEFAULT 1;";
					break;
				}

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-3");	
					 $lb_valido=false;
				}	
			}
			
			if($lb_valido)//Eliminado Primary Key  saf_dt_movimiento
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					$ls_sql=" ALTER TABLE saf_dt_movimiento                  ".
							" DROP PRIMARY KEY,  ADD PRIMARY KEY(codemp, cmpmov, codcau, feccmp, codact, ideact, estcat);                      ";
					break;

					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_dt_movimiento DROP CONSTRAINT pk_saf_dt_movimiento;                   ".
					        " ALTER TABLE saf_dt_movimiento DROP CONSTRAINT fk_saf_dt_m_saf_movim_saf_movi;";
				}

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-4");
					 $lb_valido=false;
				}
			}
			
			if($lb_valido) //Eliminado Primary Key  saf_movimiento
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					$ls_sql=" ALTER TABLE `saf_movimiento` DROP PRIMARY KEY, ".
                            " ADD PRIMARY KEY USING BTREE(`codemp`, `cmpmov`, `codcau`, `feccmp`, `estcat`);                           ";
					break;

					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_movimiento DROP CONSTRAINT pk_saf_movimiento;".
					        " ALTER TABLE saf_movimiento DROP CONSTRAINT fk_saf_movi_saf_causa_saf_caus;";
						
					break;
					 
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-5");
					 $lb_valido=false;
				}
			}
			
			if($lb_valido)
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
					$ls_sql=" ALTER TABLE saf_causas                  ".
							" DROP PRIMARY KEY, ADD PRIMARY KEY(codcau,estcat);                       ";
					break;
					
					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_causas DROP CONSTRAINT pk_saf_causas; ";
					break;
					 
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-6");
					 $lb_valido=false;
				}
			}
			if($lb_valido)
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{
					
					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_causas ADD CONSTRAINT pk_saf_causas ".
							" PRIMARY KEY(codcau,estcat);";
					break;
					 
				}
				if ($ls_sql <> "")
				{
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-7");
					 $lb_valido=false;
				 }
				}
			}
			
			if($lb_valido)
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{

					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_movimiento ADD CONSTRAINT pk_saf_movimiento  PRIMARY KEY(codemp, cmpmov, feccmp, estcat, codcau);".
					        " ALTER TABLE saf_movimiento ADD CONSTRAINT fk_saf_movi_saf_causa_saf_caus FOREIGN KEY (codcau,estcat) REFERENCES saf_causas (codcau,estcat)  ON UPDATE RESTRICT ON DELETE RESTRICT;";
						
					break;
					 
				}
				if ($ls_sql <> "")
				{
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-8");
					 $lb_valido=false;
				 }
			    }	 
			}
			if($lb_valido)
			{
				$ls_sql="";
				switch($_SESSION["ls_gestor"])
				{

					case "POSTGRE":
					$ls_sql=" ALTER TABLE saf_dt_movimiento ADD CONSTRAINT pk_saf_dt_movimiento PRIMARY KEY (codemp, cmpmov, feccmp, codact, ideact, estcat); ". 
					        " ALTER TABLE saf_dt_movimiento ADD CONSTRAINT fk_saf_dt_m_saf_movim_saf_movi FOREIGN KEY (codemp, cmpmov, codcau, feccmp,estcat) REFERENCES saf_movimiento (codemp, cmpmov, codcau, feccmp,estcat) ON UPDATE RESTRICT ON DELETE RESTRICT;";
					        
					break;
				}
				if ($ls_sql <> "")
				{
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 3.33-9");
					 $lb_valido=false;
				 }
				}
			}
		    return $lb_valido;	
		} // end function uf_create_release_db_libre_V_3_33
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    function uf_create_release_db_libre_V_3_34()
		{
			$lb_valido=true;
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN confi_ch char(1);";
				break;
				
				case "POSTGRE":
				$ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN confi_ch char(1);";
				break;
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 3.34");	
				 $lb_valido=false;
			}
	        return $lb_valido;	
	    } // end function 

       function uf_create_release_db_libre_V_3_35()
	   {
	   
	    $lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
		   case "MYSQL":
		   $ls_sql="CREATE TABLE saf_item (".
				   "             codgru char(3) NOT NULL,".
				   "             codsubgru char(3) NOT NULL,".
				   "             codsec char(3) NOT NULL,".
				   "             codite CHAR(3) NOT NULL,".
				   "             denite VARCHAR(254) NOT NULL,".
				   "  PRIMARY KEY(codgru, codsubgru, codsec, codite)) ".
				   " ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		   break;
			   
		   case "POSTGRE":
		   $ls_sql="CREATE TABLE saf_item (".
				   "             codgru char(3) NOT NULL,".
				   "             codsubgru char(3) NOT NULL,".
				   "             codsec char(3) NOT NULL,".
				   "             codite CHAR(3) NOT NULL,".
				   "             denite VARCHAR(254) NOT NULL,".
				   "  PRIMARY KEY(codgru, codsubgru, codsec, codite) ".
				   ")WITHOUT OIDS;";
			   break;
	   }

	   $li_row=$this->io_sql->execute($ls_sql);
	   if($li_row===false)
	   { 
		 $this->io_msg->message("Problemas al ejecutar Release 3.35");	
		 $lb_valido=false;
	   }
	    if ($lb_valido)
	    {
	     $ls_sql="";
		 switch($_SESSION["ls_gestor"])
		 {
		   case "MYSQL":
		   $ls_sql=" ALTER TABLE saf_item ADD CONSTRAINT fk_saf_item FOREIGN KEY (codgru, codsubgru, codsec) ".
                   " REFERENCES saf_seccion (codgru, codsubgru, codsec) ".
                   " ON DELETE RESTRICT ".
                   " ON UPDATE RESTRICT; ";
		   break;
			   
		   case "POSTGRE":
		   $ls_sql=" ALTER TABLE saf_item ADD CONSTRAINT fk_saf_item FOREIGN KEY (codgru, codsubgru, codsec) ".
                   " REFERENCES saf_seccion (codgru, codsubgru, codsec) MATCH SIMPLE ".
                   " ON UPDATE RESTRICT ON DELETE RESTRICT; ";
		   break;
	     }

	     $li_row=$this->io_sql->execute($ls_sql);
	     if($li_row===false)
	     { 
		  $this->io_msg->message("Problemas al ejecutar Release 3.35-1");	
		  $lb_valido=false;
	     }
		 else
		 {
		   $lb_existe = $this->uf_select_saf_seccion();
		   if ($lb_existe)
		   {
		    $ls_sql ="";
		    $ls_sql="INSERT INTO saf_item(codgru, codsubgru, codsec, codite, denite)".
			 	   "     VALUES ('---','---','---','---','---seleccione---')";	
		    $li_row=$this->io_sql->execute($ls_sql);
		    if($li_row===false)
		    {
			 $this->io_msg->message("CLASE->release MÉTODO->uf_insert_item ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		    }
		   }
		   else
		   {
		    $ls_sql ="";
		    $ls_sql=" INSERT INTO saf_grupo(codgru, dengru) VALUES ('---','---seleccione---') ";
			$li_row=$this->io_sql->execute($ls_sql);
		    if($li_row===false)
		    {
			 $this->io_msg->message(" Error al insertar Grupo por defecto...".$this->io_function->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		    }
			else
			{
			 $ls_sql ="";
		     $ls_sql=" INSERT INTO saf_subgrupo(codgru, codsubgru, densubgru) VALUES ('---','---','---seleccione---')  ";
			 $li_row=$this->io_sql->execute($ls_sql);
		     if($li_row===false)
		     {
			 $this->io_msg->message(" Error al insertar SubGrupo por defecto...".$this->io_function->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		     }
			 else
			 {
			  $ls_sql ="";
		      $ls_sql=" INSERT INTO saf_seccion(codgru, codsubgru, codsec, densec) VALUES ('---','---','---','---seleccione---') ";
			  $li_row=$this->io_sql->execute($ls_sql);
		      if($li_row===false)
		      {
			   $this->io_msg->message(" Error al insertar Seccion por defecto...".$this->io_function->uf_convertirmsg($this->io_sql->message));
			   $lb_valido=false;
			  }
			  else
			  {
			   $ls_sql ="";
		       $ls_sql=" INSERT INTO saf_item(codgru, codsubgru, codsec, codite, denite) VALUES ('---','---','---','---','---seleccione---') ";			
			   $li_row=$this->io_sql->execute($ls_sql);
		       if($li_row===false)
		       {
			    $this->io_msg->message(" Error al insertar Item por defecto...".$this->io_function->uf_convertirmsg($this->io_sql->message));
			    $lb_valido=false;
			   }
			  }
			 }
		    }
		   }
	     }
	   }
	   return $lb_valido;	
	  }
	   
	  function uf_create_release_db_libre_V_3_36()
	  {
       $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql=" ALTER TABLE saf_activo ADD COLUMN codite VARCHAR(3);";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE saf_activo ADD COLUMN codite CHAR(3);";
			   break;
			   
	   }

	   $li_row=$this->io_sql->execute($ls_sql);
	   if($li_row===false)
	   { 
	    $this->io_msg->message("Problemas al ejecutar Release 3.36");
		$lb_valido=false;
	   }
	    return $lb_valido;		
	  }
	 function uf_create_release_db_libre_V_3_37()
	 {
	   $lb_valido=true;
	   $ls_sql="";
	   switch(strtoupper($_SESSION["ls_gestor"]))
	   {
			case "MYSQL":
			   $ls_sql="ALTER TABLE saf_cambioresponsable
                          ADD COLUMN codact VARCHAR(15),
						  ADD COLUMN idact  VARCHAR(15)";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE saf_cambioresponsable
                          ADD COLUMN codact character varying(15),
						  ADD COLUMN idact  character varying(15) ";
			   break;
	   }
	
	   $li_row=$this->io_sql->execute($ls_sql);
       if($li_row===false)
	   { 
			 $this->io_msg->message("Problemas al ejecutar Release 3.37");
			 $lb_valido=false;
	   }
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_37
    function uf_create_release_db_libre_V_3_38()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_personalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_personalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.38");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_39()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_hpersonalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_hpersonalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.39");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_40()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE sno_thpersonalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE sno_thpersonalnomina add COLUMN fecascper DATE DEFAULT '1900-01-01';";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.40");	

			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_41()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE `scb_movbco` ADD COLUMN `conanu` VARCHAR(500);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scb_movbco ADD COLUMN conanu varchar(500);";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.41");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_3_42()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql="ALTER TABLE `scv_dt_personal` ADD COLUMN `codnom` VARCHAR(4);";
			break;
			
			case "POSTGRE":
			$ls_sql="ALTER TABLE scv_dt_personal ADD COLUMN codnom VARCHAR(4);";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.42");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_create_release_db_libre_V_3_43()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE sno_prestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0'; ";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE sno_prestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0'; ";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.43");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_44()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE sno_hprestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0';";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE sno_hprestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0'; ";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.44");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_45()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE sno_thprestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0'; ";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE sno_thprestamos ADD COLUMN tipcuopre CHAR(1) NOT NULL DEFAULT '0'; ";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.45");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	function uf_create_release_db_libre_V_3_46()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_46
		//		   Access: public 
		//        Modulos: RPC
		//	  Description: crea una tabla para el modulo de Nómina
		// Fecha Creacion: 22/02/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="create table sno_sueldominimo ".
						"( ".
						"   codemp               char(4) not null, ".
						"   codsuemin            char(4) not null, ".
						"   anosuemin            integer not null, ".
						"   gacsuemin            varchar(10) not null, ".
						"   decsuemin            varchar(10) not null, ".
						"   fecvigsuemin         date not null, ".
						"   monsuemin            double(19,4) not null default 0, ".
						"   obssuemin            varchar(254), ".
						"   primary key (codemp, codsuemin) ".
						") ENGINE = InnoDB";
			   break;
			   
			case "POSTGRE":
			    $ls_sql="create table sno_sueldominimo ".
						"( ".
						"   codemp               char(4) not null, ".
						"   codsuemin            char(4) not null, ".
						"   anosuemin            integer not null, ".
						"   gacsuemin            varchar(10) not null, ".
						"   decsuemin            varchar(10) not null, ".
						"   fecvigsuemin         date not null, ".
						"   monsuemin            float8 not null default 0, ".
						"   obssuemin            varchar(254), ".
						"   primary key (codemp, codsuemin) ".
						") WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.46");
			 $lb_valido=false;
		}
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="alter table sno_sueldominimo add constraint fk_empresa__sno_sueldominimo foreign key (codemp) ".
					   "      references tepuy_empresa (codemp) on delete restrict on update restrict;";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="alter table sno_sueldominimo add constraint fk_empresa__sno_sueldominimo foreign key (codemp) ".
					   "      references tepuy_empresa (codemp) ON UPDATE RESTRICT ON DELETE RESTRICT;";
			   break;
			   
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.46");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_46
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_47()
	{
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE tepuy_empresa ADD COLUMN confiva VARCHAR(1) DEFAULT 'P'; ";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE tepuy_empresa ADD COLUMN confiva varchar(1) DEFAULT 'P'; ";
			break;
		}

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.47");	
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_48()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_26
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: Crea la tabla sno_clasificacionobrero
		// Fecha Creacion: 16/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="CREATE TABLE sno_clasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	suemin   	DOUBLE(19,4) NOT NULL, ".
					    "	suemax   	DOUBLE(19,4) NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	PRIMARY KEY(codemp, grado) ".
						")".
						" ENGINE = InnoDB";
			   break;
			   
			case "POSTGRE":
			    $ls_sql="CREATE TABLE sno_clasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	suemin   	FLOAT8 NOT NULL, ".
					    "	suemax   	FLOAT8 NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	CONSTRAINT pk_sno_clasificacionobrero PRIMARY KEY (codemp, grado), ".
						"   CONSTRAINT fk_empresa__sno_clasidoc FOREIGN KEY (codemp) REFERENCES tepuy_empresa (codemp) ON UPDATE NO ACTION ON DELETE NO ACTION ".
						")WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.48");
			 $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
				$ls_sql="alter table sno_clasificacionobrero add constraint fk_empresa__sno_clasidoc foreign key (codemp) ".
						"      references tepuy_empresa (codemp) on delete restrict on update restrict;";
				break;
			}
			if($ls_sql!="")
			{
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 3.48");	
					 $lb_valido=false;
				}
			}
		}
		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_48
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_49()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_27
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: Crea la tabla sno_clasificacionobrero
		// Fecha Creacion: 16/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="CREATE TABLE sno_hclasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	codnom   	CHAR(4) NOT NULL, ".
					    "	anocur   	CHAR(4) NOT NULL, ".
					    "	codperi   	CHAR(3) NOT NULL, ".
					    "	suemin   	DOUBLE(19,4) NOT NULL, ".
					    "	suemax   	DOUBLE(19,4) NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	PRIMARY KEY(codemp, grado, codnom, anocur, codperi) ".
						")".
						" ENGINE = InnoDB";
			   break;
			   
			case "POSTGRE":
			    $ls_sql="CREATE TABLE sno_hclasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	codnom   	CHAR(4) NOT NULL, ".
					    "	anocur   	CHAR(4) NOT NULL, ".
					    "	codperi   	CHAR(3) NOT NULL, ".
					    "	suemin   	FLOAT8 NOT NULL, ".
					    "	suemax   	FLOAT8 NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	PRIMARY KEY (codemp, grado, codnom, anocur, codperi) ".
						")WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.49");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_49
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_50()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_28
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: Crea la tabla sno_clasificacionobrero
		// Fecha Creacion: 16/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="CREATE TABLE sno_thclasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	codnom   	CHAR(4) NOT NULL, ".
					    "	anocur   	CHAR(4) NOT NULL, ".
					    "	codperi   	CHAR(3) NOT NULL, ".
					    "	suemin   	DOUBLE(19,4) NOT NULL, ".
					    "	suemax   	DOUBLE(19,4) NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	PRIMARY KEY(codemp, grado, codnom, anocur, codperi) ".
						")".
						" ENGINE = InnoDB";
			   break;
			   
			case "POSTGRE":
			    $ls_sql="CREATE TABLE sno_thclasificacionobrero (".
					    "	codemp   	CHAR(4) NOT NULL, ".
					    "	grado   	CHAR(4) NOT NULL, ".
					    "	codnom   	CHAR(4) NOT NULL, ".
					    "	anocur   	CHAR(4) NOT NULL, ".
					    "	codperi   	CHAR(3) NOT NULL, ".
					    "	suemin   	FLOAT8 NOT NULL, ".
					    "	suemax   	FLOAT8 NOT NULL, ".
					    "	tipcla   	CHAR(2) NOT NULL, ".
					    "	obscla  	VARCHAR(254), ".
					    "	PRIMARY KEY (codemp, grado, codnom, anocur, codperi) ".
						")WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.50");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_50
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_51()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_19
		//		   Access: public 
		//        Modulos: NOMINA
		//	  Description: 
		// Fecha Creacion: 14/03/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="ALTER TABLE sno_personalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_personalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;
			   
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.51");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_51
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_52()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_20
		//		   Access: public 
		//        Modulos: NOMINA
		//	  Description: 
		// Fecha Creacion: 14/03/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="ALTER TABLE sno_hpersonalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_hpersonalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;			   
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.52");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_52
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_create_release_db_libre_V_3_53()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_1_21
		//		   Access: public 
		//        Modulos: NOMINA
		//	  Description: 
		// Fecha Creacion: 14/03/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="ALTER TABLE sno_thpersonalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE sno_thpersonalnomina ".
			   		   "  ADD COLUMN grado  CHAR(4) DEFAULT '0000';";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.53");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_53
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function uf_create_release_db_libre_V_3_54()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_54
		//		   Access: public 
		//        Modulos: Configuracion
		//	  Description: 
		// Fecha Creacion: 23/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql="CREATE TABLE tepuy_unidad_tributaria ( ".
                       "             codunitri CHAR(4) NOT NULL, ".
                       "  			 anno CHAR(4) NOT NULL, ".
                       "             fecentvig DATE NOT NULL, ".
                       "             gacofi CHAR(10) NOT NULL, ".
                       "             fecpubgac DATE NOT NULL, ".
                       "             decnro CHAR(10) NOT NULL, ".
                       "             fecdec DATE NOT NULL, ".
                       "             valunitri DOUBLE(19,4) NOT NULL, ".
                       "             PRIMARY KEY (codunitri)  ".
                       "             ) ENGINE = InnoDB CHARACTER SET utf8;";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="CREATE TABLE tepuy_unidad_tributaria ( ".
						"            codunitri char(4)  not null, ".
						"   		 anno      char(4)  not null, ".
						"   		 fecentvig date     not null, ".
						"   		 gacofi    char(10) not null, ".
						"   		 fecpubgac date     not null, ".
						"   	     decnro    char(10) not null, ".
						"   		 fecdec    date     not null, ".
						"   		 valunitri decimal(19,4) not null, ".
						"   		 constraint pk_tepuy_unidad_tributaria primary key (codunitri) ".
						"   )WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.54");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_54
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    function uf_create_release_db_libre_V_3_55()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_55
		//		   Access: public 
		//        Modulos: Configuracion
		//	  Description: 
		// Fecha Creacion: 23/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN estvaldis CHAR(1) DEFAULT 1;";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE tepuy_empresa ADD COLUMN estvaldis CHAR(1) DEFAULT 1;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.55");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_55
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_56()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_56
		//		   Access: public 
		//        Modulos: Caja y banco
		//	  Description: 
		// Fecha Creacion: 30/04/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql="ALTER TABLE scb_cheques ADD COLUMN orden SMALLINT(3) UNSIGNED DEFAULT 0;";
			   break;
			   
			case "POSTGRE":
			   $ls_sql="ALTER TABLE scb_cheques ADD COLUMN orden int2 DEFAULT 0;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.56");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_56
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_57()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_57
		//		   Access: public 
		//        Modulos: Activos Fijos
		//	  Description: 
		// Fecha Creacion: 05/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			  $ls_sql= " create table saf_prestamo( ".
					    "   codemp               char(4) not null, ".
					    "   cmppre               char(15) not null, ".
						"   coduniced            varchar(10) not null, ".
						"   codunirec            varchar(10) not null, ".
						"   fecpreact            date not null, ".
						"   codtespre            varchar(10) not null, ".
						"   codresced            varchar(10) not null, ".
						"   codresrec            varchar(10) not null, ".
						"   estpropre            smallint default 0, ".
						"   obspre               varchar(500), ".
						"   primary key (codemp, cmppre, coduniced, codunirec, fecpreact))".
						"   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;"; 
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" CREATE TABLE saf_prestamo(".
						"  codemp character(4) NOT NULL,".
						"  cmppre character varying(15) NOT NULL, ".
						"  coduniced character varying(10) NOT NULL, ".
						"  codunirec character varying(10) NOT NULL, ".
						"  codresced character varying(10) NOT NULL, ".
						"  codresrec character varying(10) NOT NULL, ".
						"  codtespre character varying(10) NOT NULL, ".
						"  fecpreact date NOT NULL, ".
						"  estpropre smallint DEFAULT 0, ".
						"  obspre character varying(500), ".
						"  CONSTRAINT pk_saf_prestamo PRIMARY KEY (codemp, cmppre, coduniced, codunirec, fecpreact), ".
						"  CONSTRAINT fk_saf_pres_fk_tepuy_tepuy_e FOREIGN KEY (codemp) ".
						"      REFERENCES tepuy_empresa (codemp) MATCH SIMPLE ".
						"      ON UPDATE RESTRICT ON DELETE RESTRICT) ".
						"  WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.57");
			 $lb_valido=false;
		}
		///-------------------------------------------------------------------------------------------------------------
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_prestamo ".
				  			"  ADD constraint fk_tepuy_empresa__saf_prestamo foreign key (codemp)       ".
						    "  references tepuy_empresa (codemp) on delete restrict on update restrict ";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.57-1 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
		//---------------------------------------------------------------------------------------------------------	
		  $ls_sql="";
	      switch($_SESSION["ls_gestor"])
	      {
			case "MYSQL":
 			   $ls_sql= " create table saf_dt_prestamo( ".
						"   codemp               char(4) not null, ".
						"   cmppre               char(15) not null, ".
						"   coduniced            varchar(10) not null, ".
						"   codunirec            varchar(10) not null, ".
						"   fecpreact            date not null, ".
						"   codact               char(15) not null, ".
						"   ideact               char(15) not null, ".
						"   primary key (codemp, cmppre, coduniced, codunirec, fecpreact, codact, ideact)) ".
						"   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" CREATE TABLE saf_dt_prestamo( ".
						"  codemp character(4) NOT NULL, ".
						"  cmppre character varying(15) NOT NULL, ".
						"  coduniced character varying(10) NOT NULL, ".
						"  codunirec character varying(10) NOT NULL, ".
						"  fecpreact date NOT NULL, ".
						"  codact character(15) NOT NULL, ".
						"  ideact character(15) NOT NULL, ".
						"  CONSTRAINT pk_saf_dt_prestamo PRIMARY KEY (codemp, cmppre, coduniced, codunirec, fecpreact, codact, ideact), ".
						"  CONSTRAINT fk_saf_dt_p_fk_saf_dt_saf_pres FOREIGN KEY (codemp, cmppre, coduniced, codunirec, fecpreact) ".
						"      REFERENCES saf_prestamo (codemp, cmppre, coduniced, codunirec, fecpreact) MATCH SIMPLE ".
						"      ON UPDATE RESTRICT ON DELETE RESTRICT, ".
						"  CONSTRAINT fk_saf_dt_pres__saf_dta FOREIGN KEY (codemp, codact, ideact) ".
						"      REFERENCES saf_dta (codemp, codact, ideact) MATCH SIMPLE ".
						"      ON UPDATE RESTRICT ON DELETE RESTRICT) ".
						"  WITHOUT OIDS;";
			   break;
		 }	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 { 
			 $this->io_msg->message("Problemas al ejecutar Release 3.57 - 2");
			 $lb_valido=false;
		 }
	    //-----------------------------------------------------------------------------------------------------------------------
		$ls_sql="";
		  switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_dt_prestamo ".
				  			"  ADD constraint fk_saf_prestmo__saf_dt_pres foreign ".
							"  key (codemp, cmppre, coduniced, codunirec, fecpreact) ".
						    "  references saf_prestamo (codemp, cmppre, coduniced, ".
							"  codunirec, fecpreact) on delete restrict on update restrict, ".
							"  ADD constraint fk_saf_dta__saf_dt_pres foreign key (codemp, codact, ideact) ".
						    "  references saf_dta (codemp, codact, ideact) on delete restrict on update restrict";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.57-3 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
		//-----------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_57
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	function uf_create_release_db_libre_V_3_58()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_58
		//		   Access: public 
		//        Modulos: Activos Fijos
		//	  Description: 
		// Fecha Creacion: 05/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL": 			   
				 $ls_sql= " create table saf_autsalida( ".
						"   codemp               char(4) not null, ".
						"   cmpsal               char(15) not null, ".
						"   coduniadm            varchar(10) not null, ".
						"   fecaut               date not null, ".
						"   codpro               varchar(10) not null, ".
						"   codrep               varchar(10) not null, ".
						"   fecent               date not null, ".
						"   fecdev               date not null, ".
						"   estprosal            smallint default 0, ".
						"   consal               varchar(500), ".
						"   obssal               varchar(500), ".
						"   primary key (codemp, cmpsal, coduniadm, fecaut)) ".
						"   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`; ";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" CREATE TABLE saf_autsalida( ".
						"  codemp character(4) NOT NULL, ".
						"  cmpsal character varying(15) NOT NULL, ".
						"  coduniadm character varying(10) NOT NULL, ".
						"  codpro character varying(10) NOT NULL, ".
						"  codrep character varying(10) NOT NULL, ".
						"  fecaut date NOT NULL, ".
						"  fecent date NOT NULL, ".
						"  fecdev date NOT NULL, ".
						"  estprosal smallint DEFAULT 0, ".
						"  consal character varying(500), ".
						"  obssal character varying(500), ".
						"  CONSTRAINT pk_saf_autsalida PRIMARY KEY (codemp, cmpsal, coduniadm, fecaut), ".
						"  CONSTRAINT fk_tepuy_empresa__saf_autsal foreign key (codemp) REFERENCES tepuy_empresa (codemp) ".
                        "  on delete restrict on update restrict) ".
						"  WITHOUT OIDS;";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.58");
			 $lb_valido=false;
		}
   	   //-----------------------------------------------------------------------------------------------------------------------
	   $ls_sql="";
		switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_autsalida ".
				  			"  ADD constraint fk_tepuy_empresa_saf_autsalida foreign key (codemp) ".
						    "  references tepuy_empresa (codemp) on delete restrict on update restrict";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.58-1 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
	   //-----------------------------------------------------------------------------------------------------------------------
	    $ls_sql="";
		 switch($_SESSION["ls_gestor"])
	     {

			case "MYSQL": 			  
				 $ls_sql= " create table saf_dt_autsalida( ".
						"   codemp               char(4) not null, ".
						"   cmpsal               char(15) not null, ".
						"   coduniadm            varchar(10) not null, ".
						"   fecaut               date not null, ".
						"   codact               char(15) not null, ".
						"   ideact               char(15) not null, ".
						"   primary key (codemp, cmpsal, coduniadm, fecaut, codact, ideact)) ".
						"   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`; ";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" CREATE TABLE saf_dt_autsalida( ".
						"  codemp character(4) NOT NULL, ".
						"  cmpsal character varying(15) NOT NULL, ".
						"  coduniadm character varying(10) NOT NULL, ".
						"  fecaut date NOT NULL, ".
						"  codact character(15) NOT NULL, ".
						"  ideact character(15) NOT NULL, ".
						"  CONSTRAINT pk_saf_dt_autsalida PRIMARY KEY (codemp, cmpsal, coduniadm, fecaut, codact, ideact), ".
						"  CONSTRAINT fk_saf_dt_a_reference_saf_dta FOREIGN KEY (codemp, codact, ideact) ".
						"      REFERENCES saf_dta (codemp, codact, ideact) MATCH SIMPLE ".
						"      ON UPDATE RESTRICT ON DELETE RESTRICT, ".
						"  CONSTRAINT fk_saf_dta__saf_autsalida FOREIGN KEY (codemp, cmpsal, coduniadm, fecaut) ".
						"      REFERENCES saf_autsalida (codemp, cmpsal, coduniadm, fecaut) MATCH SIMPLE ".
						"      ON UPDATE RESTRICT ON DELETE RESTRICT) ".
						"  WITHOUT OIDS;";
			   break;
		  }	
		  $li_row=$this->io_sql->execute($ls_sql);
		  if($li_row===false)
		  { 
			 $this->io_msg->message("Problemas al ejecutar Release 3.58 - 2 ");
			 $lb_valido=false;
		  }
	   
	   //-----------------------------------------------------------------------------------------------------------------------
	    $ls_sql="";
		switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_dt_autsalida ".
				  			"  ADD constraint fk_saf_autsalida__saf_dt_autsal foreign key (codemp, cmpsal, coduniadm, fecaut) ".
						    "      references saf_autsalida (codemp, cmpsal, coduniadm, fecaut) ".
							"  on delete restrict on update restrict, ".
						    "  ADD constraint fk_saf_dta__saf_dt_autsalida foreign key (codemp, codact, ideact) ".
						    "      references saf_dta (codemp, codact, ideact) on delete restrict on update restrict";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.58-3 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
	   //-----------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_58
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_59()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_59
		//		   Access: public 
		//        Modulos: Activos Fijos
		//	  Description: 
		// Fecha Creacion: 05/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE saf_dta ADD estactpre SMALLINT  DEFAULT 0; ";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" ALTER TABLE saf_dta ADD estactpre INT2  DEFAULT 0; ";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.59");
			 $lb_valido=false;
		}
		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_59
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_60()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_60
		//		   Access: public 
		//        Modulos: Activos Fijos
		//	  Description: 
		// Fecha Creacion: 05/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE saf_dta ADD codunipre VARCHAR(10) DEFAULT '----------';";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" ALTER TABLE saf_dta ADD codunipre VARCHAR(10) DEFAULT '----------';";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.60");
			 $lb_valido=false;
		}
		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_60
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_create_release_db_libre_V_3_61()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_61
		//		   Access: public 
		//        Modulos: Caja y Banco
		//	  Description: 
		// Fecha Creacion: 07/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE scb_cheques ADD COLUMN codusu VARCHAR(30) NOT NULL DEFAULT '-'";
			   break;
			   
			case "POSTGRE":
			   $ls_sql=	" ALTER TABLE scb_cheques ADD COLUMN codusu VARCHAR(30) NOT NULL DEFAULT '-'";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 3.61");
			 $lb_valido=false;
		}
		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_61
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-------------------------------------------------------------------------------------------------------------------------------

function uf_create_release_db_libre_V_2008_3_62()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_62
		//		   Access: public 
		//        Modulos: INV
		//	  Description: 
		// Fecha Creacion: 12/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE siv_dt_scg ".
			           "  ADD COLUMN fechaconta DATETIME DEFAULT '1900-01-01'; ";					  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE siv_dt_scg ".
			           "  ADD COLUMN fechaconta DATE DEFAULT '1900-01-01'; ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_62");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_62
//------------------------------------------------------------------------------------------------------------------------------	
  function uf_create_release_db_libre_V_2008_3_63()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_63
		//		   Access: public 
		//        Modulos: INV
		//	  Description: 
		// Fecha Creacion: 12/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE siv_dt_scg ".
			           "  ADD COLUMN fechaanula DATETIME DEFAULT '1900-01-01'; ";					  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE siv_dt_scg ".
			           "  ADD COLUMN fechaanula DATE DEFAULT '1900-01-01'; ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_63");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_63
//-----------------------------------------------------------------------------------------------------------------------------	
	   function uf_create_release_db_libre_V_2008_3_64()
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_create_release_db_libre_V_2008_3_64
			//		   Access: public 
			//        Modulos: SSS
			//	  Description: 
			// Fecha Creacion: 15/05/2008 								Fecha Ultima Modificacion : 
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   $lb_valido=true;
		   $ls_sql="";
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
				   $ls_sql= "  CREATE TABLE `sss_permisos_internos_grupos` ( ".
						    "  `codemp` CHAR(4) NOT NULL, ".
							"  `codsis` CHAR(3) NOT NULL, ".
							"  `nomgru` CHAR(60) NOT NULL, ".
							"  `codintper` VARCHAR(33) NOT NULL, ".
							"   PRIMARY KEY (`codemp`, `codsis`, `nomgru`, `codintper`)) ".
							"   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;";					  
				   break;
				   
				case "POSTGRE":
				   $ls_sql="  CREATE TABLE sss_permisos_internos_grupos( ".
							"   codemp char(4), ".
							"   codsis char(3), ".
							"   nomgru char(60), ".
							"   codintper varchar(33), ".
							"   CONSTRAINT pk_sss_permisos_internos_grupos PRIMARY KEY (codemp, codsis, nomgru, codintper),". 
							"   CONSTRAINT FK_sss_permisos_internos_grupos_1 ".
							"   FOREIGN KEY (codemp, nomgru) REFERENCES sss_grupos (codemp, nomgru)  ".  
							"   ON UPDATE NO ACTION ON DELETE NO ACTION) WITHOUT OIDS;";			   
				   break;	   
				
			}	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_3_64 -1");
				 $lb_valido=false;
			}
			///----------------------------------cosntrains-----------------------------------------------------------------
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
				   $ls_sql= "  ALTER TABLE `sss_permisos_internos_grupos` ".
				  			"  ADD CONSTRAINT `FK_sss_permisos_internos_grupos_1` ".
							"  FOREIGN KEY `FK_sss_permisos_internos_grupos_1` (`codemp`, `nomgru`) ".
							"  REFERENCES `sss_grupos` (`codemp`, `nomgru`) ".
							"  ON DELETE RESTRICT ".
							"  ON UPDATE RESTRICT, COMMENT = 'InnoDB free: 73728 kB'";
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 2008_3_64-2");
						 $lb_valido=false;
					}							  
				   break;			
			}	
				
			///-------------------------------------------------------------------------------------------------------------
		   return $lb_valido;	
		} // end function uf_create_release_db_libre_V_2008_3_64


//-----------------------------------------------------------------------------------------------------------------------------
		function uf_create_release_db_libre_V_2008_3_65()
			{
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//	     Function: uf_create_release_db_libre_V_2008_3_65
				//		   Access: public 
				//        Modulos: SSS
				//	  Description: 
				// Fecha Creacion: 15/05/2008 								Fecha Ultima Modificacion : 
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			   $lb_valido=true;
			   $ls_sql="";
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql="  ALTER TABLE sss_derechos_grupos ".
					   		   "  ADD COLUMN codintper VARCHAR(33) NOT NULL AFTER ejecutar, ".
 							   "  DROP PRIMARY KEY, ".
 							   "  ADD PRIMARY KEY  USING BTREE(codemp, nomgru, codsis, nomven, codintper); ";			  
					   break;
					   
					case "POSTGRE":
					   $ls_sql=" ALTER TABLE sss_derechos_grupos DROP CONSTRAINT pk_sss_derechos_grupos; ";			   
					   break;	   
					
				}	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_3_65-1");
					 $lb_valido=false;
				}
				
				//------------------------------------------------------------------------------------------------------
				switch($_SESSION["ls_gestor"])
				   {
						case "MYSQL":
						   $ls_sql="  ALTER TABLE sss_derechos_grupos ".
						           "  ADD CONSTRAINT FK_sss_derechos_grupos_3 ".
								   "  FOREIGN KEY FK_sss_derechos_grupos_3 (codemp, codsis, nomgru, codintper) ".
								   "  REFERENCES sss_permisos_internos_grupos (codemp, codsis, nomgru, codintper) ".
								   "  ON DELETE RESTRICT ".
								   "  ON UPDATE RESTRICT, COMMENT = 'InnoDB free: 36864 kB';";			  
						   break;
						   
						case "POSTGRE":
						   $ls_sql=" ALTER TABLE sss_derechos_grupos ADD COLUMN codintper varchar(33); ";			   
						   break;	   
						
					}	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 2008_3_65-2");
						 $lb_valido=false;
					}
				//-----------------------------------------------------------------------------------------------------
				switch($_SESSION["ls_gestor"])
				   {
						case "POSTGRE":
						   $ls_sql=" ALTER TABLE sss_derechos_grupos ADD CONSTRAINT pk_sss_derechos_grupos ".
						           " PRIMARY KEY (codemp, nomgru, codsis, nomven, codintper); ";
						   $li_row=$this->io_sql->execute($ls_sql);
								if($li_row===false)
								{ 
									 $this->io_msg->message("Problemas al ejecutar Release 2008_3_65-3");
									 $lb_valido=false;
								}			   
						   break;					
					}	
					
				//-----------------------------------------------------------------------------------------------------
				switch($_SESSION["ls_gestor"])
				   {					   
						   case "POSTGRE":
						   $ls_sql=" ALTER TABLE sss_derechos_grupos ".
						           " ADD CONSTRAINT FK_sss_derechos_grupos_3 ".
								   " FOREIGN KEY (codemp, codsis, nomgru, codintper) ".
								   " REFERENCES sss_permisos_internos_grupos (codemp, codsis, nomgru, codintper) ".
								   " ON UPDATE NO ACTION ON DELETE NO ACTION; ";			   
						   $li_row=$this->io_sql->execute($ls_sql);
							if($li_row===false)
							{ 
								 $this->io_msg->message("Problemas al ejecutar Release 2008_3_65-4");
								 $lb_valido=false;
							}
						   break;					
					}						
				//-----------------------------------------------------------------------------------------------------
			   return $lb_valido;	
			} // end function uf_create_release_db_libre_V_2008_3_65		
		//-----------------------------------------------------------------------------------------------------------------------------	
		function uf_create_release_db_libre_V_2008_3_66()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_66
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			  $ls_sql="  ALTER TABLE sno_nomina ".
 					  "  ADD COLUMN confidnom VARCHAR(5), ".
  					  "  ADD COLUMN recdocfid VARCHAR(1), ".
  					  "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					  "  ADD COLUMN codbenfid VARCHAR(10), ".
  					  "  ADD COLUMN cueconfid VARCHAR(25); ";						  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_nomina ".
 					   "  ADD COLUMN confidnom VARCHAR(5), ".
  					   "  ADD COLUMN recdocfid VARCHAR(1), ".
  					   "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					   "  ADD COLUMN codbenfid VARCHAR(10), ".
  					   "  ADD COLUMN cueconfid VARCHAR(25); ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_66");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_66 //-----------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_67()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_67
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			  $ls_sql="  ALTER TABLE sno_hnomina ".
 					  "  ADD COLUMN confidnom VARCHAR(5), ".
  					  "  ADD COLUMN recdocfid VARCHAR(1), ".
  					  "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					  "  ADD COLUMN codbenfid VARCHAR(10), ".
  					  "  ADD COLUMN cueconfid VARCHAR(25); ";						  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_hnomina ".
 					   "  ADD COLUMN confidnom VARCHAR(5), ".
  					   "  ADD COLUMN recdocfid VARCHAR(1), ".
  					   "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					   "  ADD COLUMN codbenfid VARCHAR(10), ".
  					   "  ADD COLUMN cueconfid VARCHAR(25); ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_67");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_67 

//-----------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_68()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_68
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			  $ls_sql="  ALTER TABLE sno_thnomina ".
 					  "  ADD COLUMN confidnom VARCHAR(5), ".
  					  "  ADD COLUMN recdocfid VARCHAR(1), ".
  					  "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					  "  ADD COLUMN codbenfid VARCHAR(10), ".
  					  "  ADD COLUMN cueconfid VARCHAR(25); ";						  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_thnomina ".
 					   "  ADD COLUMN confidnom VARCHAR(5), ".
  					   "  ADD COLUMN recdocfid VARCHAR(1), ".
  					   "  ADD COLUMN tipdocfid VARCHAR(5), ".
  					   "  ADD COLUMN codbenfid VARCHAR(10), ".
  					   "  ADD COLUMN cueconfid VARCHAR(25); ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_68");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_68 

//-----------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_69()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_69
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_periodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_periodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_69");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_69

//------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_70()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_70
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_hperiodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_hperiodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_70");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_70

//------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_71()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_71
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_thperiodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_thperiodo ".
  					   "  ADD COLUMN ingconper smallint NOT NULL DEFAULT 0, ".
  					   "  ADD COLUMN fidconper smallint NOT NULL DEFAULT 0; ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_71");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_71

//------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_3_72()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_72
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_fideiconfigurable ADD COLUMN cueprefid VARCHAR(25); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_fideiconfigurable ADD COLUMN cueprefid VARCHAR(25); ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_72");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_72

//-----------------------------------------------------------------------------------------------------------------------------

   function uf_create_release_db_libre_V_2008_3_73()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_73
		//		   Access: public 
		//        Modulos: SOC
		//	  Description: 
		// Fecha Creacion: 21/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE soc_servicios ADD COLUMN codunimed char(4); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE soc_servicios ADD COLUMN codunimed char(4); ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_73");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_73

//--------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_74()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_74
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 21/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE siv_unidadmedida ADD COLUMN tiposep char(1); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE siv_unidadmedida ADD COLUMN tiposep char(1); ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_74");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_74
//--------------------------------------------------------------------------------------------------------------------------------

    function uf_create_release_db_libre_V_2008_3_75()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_75
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 26/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_concepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_concepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_75");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_75
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_76()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_76
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 26/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_hconcepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_hconcepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_76");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_76
//-------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_3_77()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_77
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 26/05/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE sno_thconcepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE sno_thconcepto ".
					   "  ADD COLUMN repacucon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN repconsunicon CHAR(1) NOT NULL DEFAULT '0', ".
					   "  ADD COLUMN consunicon VARCHAR(10); ";			   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_77");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_77
//-------------------------------------------------------------------------------------------------------------------------------

     	function uf_create_release_db_libre_V_2008_3_78()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_78
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " alter table tepuy_empresa ".
						" add nomrep varchar(60);    ";						
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " alter table tepuy_empresa ".
						" add nomrep varchar(60);    ";	
		        break;			 
		}
		if (!empty($ls_sql))
		{	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 { 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_78");
			 $lb_valido=false;
		 }
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_78
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2008_3_79()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_79
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " alter table tepuy_empresa ".
						" add cedrep varchar(10);    ";						
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " alter table tepuy_empresa ".
						" add cedrep varchar(10);    ";	
		        break;			   
			
		}
		if (!empty($ls_sql))
		{	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 { 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_79");
			 $lb_valido=false;
		 }
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_79	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_create_release_db_libre_V_2008_3_80()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_80
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " alter table tepuy_empresa ".
						" add  telfrep varchar(15);  ";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " alter table tepuy_empresa ".
						" add  telfrep varchar(15);  ";	
		        break;	   
			 
		}
		if (!empty($ls_sql))
		{	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 { 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_80");
			 $lb_valido=false;
		 }
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_80
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_81()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_81
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/06/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql="  ALTER TABLE tepuy_empresa ADD COLUMN cargorep VARCHAR(80); ";							  
			   break;
			   
			case "POSTGRE":
			   $ls_sql="  ALTER TABLE tepuy_empresa ADD COLUMN cargorep VARCHAR(80);  ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_81");
			 $lb_valido=false;
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_81
//-------------------------------------------------------------------------------------------------------------------------------
   function uf_create_release_db_libre_V_2008_3_82()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_82
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE tepuy_empresa ADD nroivss varchar(15);";
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE tepuy_empresa ADD nroivss varchar(15);";
		        break;		   
			
		}
		if (!empty($ls_sql))
		{	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 { 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_82");
			 $lb_valido=false;
		 }
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_82
   
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_83()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_83
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_clasificacionobrero     ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_clasificacionobrero     ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";		
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_83");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_83
//-------------------------------------------------------------------------------------------------------------------------------
   function uf_create_release_db_libre_V_2008_3_84()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_84
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_hclasificacionobrero     ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_hclasificacionobrero    ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";		
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_84");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_84
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_85()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_85
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_thclasificacionobrero     ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_thclasificacionobrero    ".
			            " ADD COLUMN anovig char(4) DEFAULT 1900, ". 
			            " ADD COLUMN nrogac varchar(10)          ;";		
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_85");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_85
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_86()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_86
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 05/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE siv_despacho ".
                        " ADD nomproy       varchar(100), ".
                        " ADD codproy       char(15), ".
                        " ADD direccproy    varchar (254), ".
                        " ADD codpai        char (3), ".
                        " ADD codmun        char (3), ".
                        " ADD codest        char (3), ".
                        " ADD codpar        char (3), ".
                        " ADD nomdir        varchar(50); ";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE siv_despacho ".
                        " ADD nomproy       varchar(100), ".
                        " ADD codproy       char(15), ".
                        " ADD direccproy    varchar (254), ".
                        " ADD codpai        char (3), ".
                        " ADD codmun        char (3), ".
                        " ADD codest        char (3), ".
                        " ADD codpar        char (3), ".
                        " ADD nomdir        varchar(50); ";					
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_86");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_86
//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_3_87()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_87
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: Crea la tabla saf_entrega
		// Fecha Creacion: 10/06/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="  CREATE TABLE saf_entrega( ".
  						"  codemp char(4) NOT NULL,  ".
						"  cmpent char(15) NOT NULL, ".
						"  feccmp date NOT NULL, ".
						"  fecent date NOT NULL, ".
						"  coduniadm varchar(10) NOT NULL, ".
						"  codunisol varchar(10) DEFAULT '----------', ".
						"  estproent smallint default 0, ".
						"  codres varchar(10), ".
						"  coddes varchar(10), ".
						"  codrec varchar(10), ".
						"  tipres char(1), ".
						"  tipdes char(1), ".
						"  tiprec char(1), ".
						"  obsent text, ".
						"  CONSTRAINT pk_saf_entrega PRIMARY KEY (codemp, cmpent, feccmp, coduniadm)) ".
						"  ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;";
			   break;
			   
			case "POSTGRE":
			    $ls_sql=" CREATE TABLE saf_entrega( ".
  						" codemp char(4) NOT NULL,  ".
  						" cmpent char(15) NOT NULL, ".
  						" feccmp date NOT NULL,     ".
  						" fecent date NOT NULL,     ".
  						" coduniadm varchar(10) NOT NULL, ".
  						" codunisol varchar(10) DEFAULT '----------', ".
  						" estproent smallint default 0, ".
  						" codres varchar(10), ".
  						" coddes varchar(10), ".
  						" codrec varchar(10), ".
  						" tipres char(1), ".
  						" tipdes char(1), ".
  						" tiprec char(1), ".
  						" obsent text,    ".
  						" CONSTRAINT pk_saf_entrega PRIMARY KEY (codemp, cmpent, feccmp, coduniadm), ".
  						" CONSTRAINT fk_saf_entr_empresa__tepuy_e FOREIGN KEY (codemp) ".
      					" REFERENCES tepuy_empresa (codemp) ".
      					" ON UPDATE RESTRICT ON DELETE RESTRICT ) WITHOUT OIDS; ";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release_db_libre_V_3_87");
			 $lb_valido=false;
		}
		//-------------------------------------------------------------------------------------------------------------------
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_entrega ".
				  			"  ADD  CONSTRAINT fk_saf_entr_empresa__tepuy_e FOREIGN KEY (codemp) ".
					        "  REFERENCES tepuy_empresa (codemp) ".
						    "  ON UPDATE RESTRICT ON DELETE RESTRICT";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.87-1 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
		//-------------------------------------------------------------------------------------------------------------------
		
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_87

//-----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
   function uf_create_release_db_libre_V_3_88()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_3_88
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: Crea la tabla saf_dt_entrega
		// Fecha Creacion: 10/06/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			    $ls_sql="  CREATE TABLE saf_dt_entrega( ".
						"  codemp char(4) NOT NULL, ".
						"  cmpent char(15) NOT NULL, ".
						"  feccmp date NOT NULL, ".
						"  coduniadm varchar(10) NOT NULL, ".
						"  codact char(15) NOT NULL, ".
						"  ideact char(15) NOT NULL, ".
						"  CONSTRAINT pk_saf_dt_entrega PRIMARY KEY (codemp, cmpent, feccmp, coduniadm, codact, ideact)) ".
						"  ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;";
			   break;
			   
			case "POSTGRE":
			    $ls_sql="  CREATE TABLE saf_dt_entrega ( ".
						"  codemp char(4) NOT NULL, ".
						"  cmpent char(15) NOT NULL, ".
						"  feccmp date NOT NULL, ".
						"  coduniadm varchar(10) NOT NULL, ".
						"  codact char(15) NOT NULL, ".
						"  ideact char(15) NOT NULL, ".
						"  CONSTRAINT pk_saf_dt_entrega PRIMARY KEY (codemp, cmpent, feccmp, coduniadm, codact, ideact), ".
						"  CONSTRAINT fk_saf_dt_ent__saf_entrega FOREIGN KEY (codemp,cmpent, feccmp, coduniadm) ".
					    "  REFERENCES saf_entrega (codemp, cmpent, feccmp, coduniadm) ".
						"  ON UPDATE RESTRICT ON DELETE RESTRICT,  ".
						"  CONSTRAINT fk_saf_dt_ent__tepuy_empresa FOREIGN KEY (codemp) ".
					    "  REFERENCES tepuy_empresa (codemp) ".
						"  ON UPDATE RESTRICT ON DELETE RESTRICT, ".
						"  CONSTRAINT fk_saf_dt_ent__saf_dta FOREIGN KEY (codemp,codact,ideact) ".
						"  REFERENCES saf_dta (codemp,codact,ideact) ".
						"  ON UPDATE RESTRICT ON DELETE RESTRICT)	WITHOUT OIDS; ";
			   break;
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("uf_create_release_db_libre_V_3_88");
			 $lb_valido=false;
		}
	  //------------------------------------------------------------------------------------------------------------------------
	  $ls_sql="";
		switch($_SESSION["ls_gestor"])
		   {
				 case "MYSQL":
				   $ls_sql= "  ALTER TABLE saf_entrega ".
				  			"  ADD  CONSTRAINT fk_saf_dt_ent__saf_entrega FOREIGN KEY (codemp,cmpent, feccmp, coduniadm) ".
						    "  REFERENCES saf_entrega (codemp, cmpent, feccmp, coduniadm) ".
						    "  ON UPDATE RESTRICT ON DELETE RESTRICT, ".
						    "  ADD CONSTRAINT fk_saf_dt_ent__tepuy_empresa FOREIGN KEY (codemp) ".
						    "  REFERENCES tepuy_empresa (codemp) ".
						    "  ON UPDATE RESTRICT ON DELETE RESTRICT, ".
						    "  ADD CONSTRAINT fk_saf_dt_ent__saf_dta FOREIGN KEY (codemp,codact,ideact) ".
						    "  REFERENCES saf_dta (codemp,codact,ideact) ".
						    "  ON UPDATE RESTRICT ON DELETE RESTRICT";							
							
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 3.88-1 -->Ver el tipo de las Tablas<--");
						 $lb_valido=false;
					}							  
				 break;			
			}
	  //------------------------------------------------------------------------------------------------------------------------
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_3_88
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_89()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_89
		//		   Access: public 
		//        Modulos: SCB
		//	  Description: 
		// Fecha Creacion: 10/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE scb_banco   ".
			            " ADD codsudeban char(4) ;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE scb_banco   ".
			            " ADD codsudeban char(4) ;";			
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_89");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_89
//-----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------

    function uf_create_release_db_libre_V_2008_3_90()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_90
		//		   Access: public 
		//        Modulos: CFG
		//	  Description: 
		// Fecha Creacion: 12/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE tepuy_deducciones    ".
			            " ADD tipopers char(1) DEFAULT 'J' ;";					
			   break;
			   
			case "POSTGRE":
			    $ls_sql= " ALTER TABLE tepuy_deducciones    ".
			             " ADD tipopers char(1) DEFAULT 'J' ;";				
		        break;   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_90");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_90
	
//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_91()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_91
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 13/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_beneficiario ".
                        " ADD nexben VARCHAR(1) DEFAULT '-' ;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_beneficiario ".
                        " ADD nexben VARCHAR(1) DEFAULT '-' ;";			
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_91");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_91


//----------------------------------------------------------------------------------------------------------------------------------

    function uf_create_release_db_libre_V_2008_3_92()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_92
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_personal    ".
			            " ADD enviorec varchar(1) DEFAULT '-' ;";					
			   break;
			   
			case "POSTGRE":
			    $ls_sql= " ALTER TABLE sno_personal    ".
			             " ADD enviorec varchar(1) DEFAULT '-';";				
		        break;   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_92");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_92
//-----------------------------------------------------------------------------------------------------------------------------------

    function uf_create_release_db_libre_V_2008_3_93()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_93
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 16/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_beneficiario ".
                        " ADD  cedaut varchar(10)";					
			   break;
			   
			case "POSTGRE":
			     $ls_sql= " ALTER TABLE sno_beneficiario ".
                          " ADD  cedaut varchar(10)";						
		        break;   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_93");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_93

//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_94()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_94
		//		   Access: public 
		//        Modulos: CFG
		//	  Description: 
		// Fecha Creacion: 17/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE tepuy_empresa ".
                        " ADD  estretiva varchar(1) DEFAULT 'C'";					
			   break;
			   
			case "POSTGRE":
			    $ls_sql= " ALTER TABLE tepuy_empresa ".
                         " ADD  estretiva varchar(1) DEFAULT 'C'";						
		        break;   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_94");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_94
//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_95()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_95
		//		   Access: public 
		//        Modulos: CFG
		//	  Description: 
		// Fecha Creacion: 17/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE cxp_documento ".
                        " ADD  tipodocanti int2 NOT NULL DEFAULT 0";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE cxp_documento ".
                        " ADD  tipodocanti int2 NOT NULL DEFAULT 0";						
		        break;   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_95");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_95

//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_96()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_96
		//		   Access: public 
		//        Modulos: RRHH
		//	  Description: 
		// Fecha Creacion: 18/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE srh_departamento       ".
			            "   ADD COLUMN minorguniadm Char(4), ".
						"   ADD COLUMN ofiuniadm Char(2),    ".	
						"   ADD COLUMN uniuniadm char(2),    ".	
						"   ADD COLUMN depuniadm char(2),    ".	
						"   ADD COLUMN prouniadm char(2);    ";						
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE srh_departamento       ".
			            "   ADD COLUMN minorguniadm Char(4), ".
						"   ADD COLUMN ofiuniadm Char(2),    ".	
						"   ADD COLUMN uniuniadm char(2),    ".	
						"   ADD COLUMN depuniadm char(2),    ".	
						"   ADD COLUMN prouniadm char(2);    ";		
		        break;			
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_3_96");
				 $lb_valido=false;
			 }
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_96

//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_97()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_97
		//		   Access: public 
		//        Modulos: RRHH
		//	  Description: 
		// Fecha Creacion: 19/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE srh_movimiento_personal ".
			            "   ADD COLUMN minorguniadm Char(4),  ".
						"   ADD COLUMN ofiuniadm Char(2),     ".	
						"   ADD COLUMN uniuniadm char(2),     ".	
						"   ADD COLUMN depuniadm char(2),     ".	
						"   ADD COLUMN prouniadm char(2);     ";						
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE srh_movimiento_personal ".
			            "   ADD COLUMN minorguniadm Char(4),  ".
						"   ADD COLUMN ofiuniadm Char(2),     ".	
						"   ADD COLUMN uniuniadm char(2),     ".	
						"   ADD COLUMN depuniadm char(2),     ".	
						"   ADD COLUMN prouniadm char(2);     ";		
		        break;			
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_3_97");
				 $lb_valido=false;
			 }
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_97

//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_98()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_98
		//		   Access: public 
		//        Modulos: RRHH
		//	  Description: 
		// Fecha Creacion: 19/06/2008 								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			   $ls_sql= "  ALTER TABLE srh_movimiento_personal ".
						"  DROP COLUMN codunivi;               ";					  
			   break;
			   
			case "POSTGRE":
			    $ls_sql= "  ALTER TABLE srh_movimiento_personal ".
						 "  DROP COLUMN codunivi;               ";		   
			   break;	   
			
		}	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_3_98");
			 $lb_valido=false;
		}	
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_98

//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_3_99()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_3_99
		//		   Access: public 
		//        Modulos: SOC
		//	  Description: 
		// Fecha Creacion: 19/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE soc_ordencompra                          ".
			            " ADD COLUMN fechentdesde date DEFAULT '1900-01-01',   ". 
			            " ADD COLUMN fechenthasta date DEFAULT '1900-01-01'    ;";				
			   break;
			   
			case "POSTGRE":
			    $ls_sql= " ALTER TABLE soc_ordencompra                          ".
			             " ADD COLUMN fechentdesde date DEFAULT '1900-01-01',   ". 
			             " ADD COLUMN fechenthasta date DEFAULT '1900-01-01'    ;";		
		        break;	   
			  
		}
		if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_3_99");
			 	$lb_valido=false;
		 	}
		}
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_3_99

//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_00()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_00
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 25/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$lb_existe="";
	    $ls_sql="";
	    switch($_SESSION["ls_gestor"])
	    { 
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_nomina ".
                        " ADD COLUMN divcon varchar(1) DEFAULT 0;";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_nomina ".
                        " ADD COLUMN divcon character(1) DEFAULT 0;";		
		        break;	   
			  
		 }
		 if (!empty($ls_sql))
		 {	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_4_00-1");
			 	$lb_valido=false;
		 	}
		 }
		 
		$lb_existe = $this->io_function_db->uf_select_column('sno_hnomina','divcon');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
				case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_hnomina ".
							" ADD COLUMN divcon varchar(1) DEFAULT 0;";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_hnomina ".
							" ADD COLUMN divcon character(1) DEFAULT 0;";		
					break;	   
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_00-2");
					$lb_valido=false;
				}
			 }
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_thnomina','divcon');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
				case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_thnomina ".
							" ADD COLUMN divcon varchar(1) DEFAULT 0;";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_thnomina ".
							" ADD COLUMN divcon character(1) DEFAULT 0;";		
					break;	   
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_00-3");
					$lb_valido=false;
				}
			 }
		}
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_00
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_01()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_01
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 25/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$lb_existe="";
	    $ls_sql="";
	    switch($_SESSION["ls_gestor"])
	    { 
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_concepto ".
                        " ADD COLUMN quirepcon varchar(1) DEFAULT '-';";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_concepto ".
                        " ADD COLUMN quirepcon character(1) DEFAULT '-';";		
		        break;	   
			  
		 }
		 if (!empty($ls_sql))
		 {	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_4_01-1");
			 	$lb_valido=false;
		 	}
		 }
		 
		$lb_existe = $this->io_function_db->uf_select_column('sno_hconcepto','quirepcon');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
			   case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_hconcepto ".
                        " ADD COLUMN quirepcon varchar(1) DEFAULT '-';";					
			   break;
			   
			   case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_hconcepto ".
                        " ADD COLUMN quirepcon character(1) DEFAULT '-';";		
		        break;	   
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_01-2");
					$lb_valido=false;
				}
			 }
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_thconcepto','quirepcon');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
				case "MYSQL":
 			      $ls_sql= " ALTER TABLE sno_thconcepto ".
                           " ADD COLUMN quirepcon varchar(1) DEFAULT '-';";					
			   break;
			   
			   case "POSTGRE":
			     $ls_sql= " ALTER TABLE sno_thconcepto ".
                          " ADD COLUMN quirepcon character(1) DEFAULT '-';";		
		        break;	     
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_01-3");
					$lb_valido=false;
				}
			 }
		}
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_01

//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_02()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_02
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 25/06/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$lb_existe="";
	    $ls_sql="";
	    switch($_SESSION["ls_gestor"])
	    { 
			case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_salida ".
                        " ADD COLUMN priquisal double(19,4) DEFAULT 0, ".
                        " ADD COLUMN segquisal double(19,4) DEFAULT 0; ";					
			   break;
			   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_salida ".
                        " ADD COLUMN priquisal float8 DEFAULT 0, ".
                        " ADD COLUMN segquisal float8 DEFAULT 0;";		
		        break;	   
			  
		 }
		 if (!empty($ls_sql))
		 {	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar Release 2008_4_02-1");
			 	$lb_valido=false;
		 	}
		 }
		 
		$lb_existe = $this->io_function_db->uf_select_column('sno_hsalida','priquisal');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
			   case "MYSQL":
 			   $ls_sql= " ALTER TABLE sno_hsalida ".
                        " ADD COLUMN priquisal double(19,4) DEFAULT 0, ".
                        " ADD COLUMN segquisal double(19,4) DEFAULT 0; ";					
			   break;
			   
			   case "POSTGRE":
			   $ls_sql= " ALTER TABLE sno_hsalida ".
                        " ADD COLUMN priquisal float8 DEFAULT 0, ".
                        " ADD COLUMN segquisal float8 DEFAULT 0;";		
		        break;	    
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_02-2");
					$lb_valido=false;
				}
			 }
		}
		
		$lb_existe = $this->io_function_db->uf_select_column('sno_thsalida','priquisal');
		 
		if (!$lb_existe)
		{
			$ls_sql="";
			switch($_SESSION["ls_gestor"])
			{ 
				case "MYSQL":
 			    $ls_sql= " ALTER TABLE sno_thsalida ".
                         " ADD COLUMN priquisal double(19,4) DEFAULT 0, ".
                         " ADD COLUMN segquisal double(19,4) DEFAULT 0; ";					
			    break;
			   
			    case "POSTGRE":
			    $ls_sql= " ALTER TABLE sno_thsalida ".
                         " ADD COLUMN priquisal float8 DEFAULT 0, ".
                         " ADD COLUMN segquisal float8 DEFAULT 0;";		
		        break;	        
				  
			 }
			 if (!empty($ls_sql))
			 {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_02-3");
					$lb_valido=false;
				}
			 }
		}
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_02


//----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_03()
     {			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_create_release_db_libre_V_2008_4_03
			//		   Access: public 
			//        Modulos: RRHH
			//	  Description: 
			// Fecha Creacion: 01/07/2008 								Fecha Ultima Modificacion : 			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   $lb_valido=true;
		   $ls_sql="";
		   switch($_SESSION["ls_gestor"])
		   {
				case "POSTGRE":
				   $ls_sql= " CREATE TABLE srh_defcontrato( ".
						    " codemp   char(4) NOT NULL, ".
						    " codcont  char(3) NOT NULL, ".
						    " descont  char(254), ".
						    " concont  text,      ".
						    " tamletcont integer, ".
						    " intlincont integer, ".
						    " marinfcont double precision DEFAULT (3), ".
						    " marsupcont double precision DEFAULT (4), ".
						    " titcont    text,       ".
						    " piepagcont text,       ".
						    " tamletpiecont integer, ".
						    " arcrtfcont char (50),  ".
						    " PRIMARY KEY (codemp, codcont), ".
						    " FOREIGN KEY(codemp) REFERENCES tepuy_empresa(codemp) ".
							" ON DELETE RESTRICT ON UPDATE RESTRICT) WITHOUT OIDS;  ";					  
				   break;
				   
				case "MYSQL":
				   $ls_sql=" CREATE TABLE srh_defcontrato( ".
						   " codemp   char(4) NOT NULL, ".
						   " codcont  char(3) NOT NULL, ".
						   " descont  char(254), ".
						   " concont  text, ".
						   " tamletcont integer, ".
						   " intlincont integer, ".
						   " marinfcont float, ".
						   " marsupcont float, ".
						   " titcont    text, ".
						   " piepagcont text, ".
						   " tamletpiecont integer, ".
						   " arcrtfcont char (50),  ".
						   " PRIMARY KEY (codemp, codcont) ".
						   " ) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci`;  ";			   
				   break;	   
				
			}	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_03 -1");
				 $lb_valido=false;
			}
			///----------------------------------cosntrains-----------------------------------------------------------------
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
				   $ls_sql= "  ALTER TABLE srh_defcontrato ".
    						"  ADD CONSTRAINT FK_srh_defcontrato_1 FOREIGN KEY FK_srh_defcontrato_1 (codemp) ".
    						"  REFERENCES tepuy_empresa(codemp) ".
    						"  ON DELETE RESTRICT ".
    						"  ON UPDATE RESTRICT;";
				   $li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						 $this->io_msg->message("Problemas al ejecutar Release 2008_4_03-2");
						 $lb_valido=false;
					}							  
				   break;			
			}	
				
			///-------------------------------------------------------------------------------------------------------------
		   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_03
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_04()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_04
		//		   Access: public 
		//        Modulos: RRHH
		//	  Description: 
		// Fecha Creacion: 18/07/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
		case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_permiso ADD COLUMN tothorper float; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_permiso ADD COLUMN tothorper float;";							
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_04");
					$lb_valido=false;
				}
			}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_04
//---------------------------------------------------------------------------------------------------------------------------------
	 function uf_create_release_db_libre_V_2008_4_05()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_05
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 29/07/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
		case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_metodobanco ". 
							" ADD nroref char(1) DEFAULT 1; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_metodobanco   ". 
							" ADD nroref char(1) DEFAULT 1; ";								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_05");
					$lb_valido=false;
				}
			}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_05
//---------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_06()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_06
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
				case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_concepto                             ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";						
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_concepto                             ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_06");
					$lb_valido=false;
				}
			}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_06
//----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	 function uf_create_release_db_libre_V_2008_4_07()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_07
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
				case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_hconcepto                            ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";						
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_hconcepto                            ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_07");
					$lb_valido=false;
				}
			}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_07
//-----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	 function uf_create_release_db_libre_V_2008_4_08()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_08
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 03/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
				case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_thconcepto                           ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";						
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE sno_thconcepto                           ".
				            " ADD asifidper character(1) NOT NULL DEFAULT 0,       ".
							" ADD asifidpat character(1) NOT NULL DEFAULT 0,       ".
							" ADD frevarcon character(1) NOT NULL DEFAULT 0        ";				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_08");
					$lb_valido=false;
				}
			}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_08
//---------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_09()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_09
		//		   Access: public 
		//        Modulos: CFG
		//	  Description: 
		// Fecha Creacion: 08/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
		       case "MYSQL":
				   $ls_sql= " CREATE TABLE scg_casa_presu (                                    ".
							"  codemp char(4) NOT NULL,                                        ".
							"  sig_cuenta char(25) NOT NULL,                                   ".  
							"  sc_cuenta char(25),                                             ".
							"  CONSTRAINT pk_scg_casa_presu PRIMARY KEY (codemp, sig_cuenta))  ".
							"     ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci;    ";				
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " CREATE TABLE scg_casa_presu (                                    ".
							"  codemp char(4) NOT NULL,                                        ".
							"  sig_cuenta char(25) NOT NULL,                                   ".  
							"  sc_cuenta char(25),                                             ".
							"  CONSTRAINT pk_scg_casa_presu PRIMARY KEY (codemp, sig_cuenta),  ".
							"  CONSTRAINT fk_scg_casa_presu FOREIGN KEY (codemp)               ".
							"	  REFERENCES tepuy_empresa (codemp) MATCH SIMPLE              ".  
							"	  ON UPDATE NO ACTION ON DELETE NO ACTION,                     ".
							"  CONSTRAINT fk_scg_casa_presu2 FOREIGN KEY (sig_cuenta)          ".
							"	  REFERENCES tepuy_plan_unico_re (sig_cuenta) MATCH SIMPLE    ".
							"	  ON UPDATE NO ACTION ON DELETE NO ACTION)	WITHOUT OIDS;      ";								
					break;  				  
	    }
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_09-1");
				$lb_valido=false;
			}
		}
		if (($lb_valido)&&($_SESSION["ls_gestor"]=="MYSQL"))
		{
			 $ls_sql= "  ALTER TABLE scg_casa_presu                                                                 ".			 
					  "    ADD CONSTRAINT fk_scg_casa_presu FOREIGN KEY (codemp) REFERENCES tepuy_empresa (codemp) ".
					  "        MATCH SIMPLE  ON UPDATE NO ACTION ON DELETE NO ACTION,                               ".
					  "    ADD CONSTRAINT fk_scg_casa_presu2 FOREIGN KEY (sig_cuenta) REFERENCES                    ".
					  "                   tepuy_plan_unico_re (sig_cuenta) MATCH SIMPLE                            ".
					  "	                  ON UPDATE NO ACTION ON DELETE NO ACTION;	                                ";
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_09-2");
					$lb_valido=false;
				}
			}			
		}	
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_09
//----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_10()
	{
		$lb_valido=true;
		$ls_sql="";
		$lb_valido=true;
		$ls_sql="";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
			$ls_sql=" ALTER TABLE tepuy_empresa ADD COLUMN confinstr VARCHAR(1) DEFAULT 'N'; ";
			break;
			
			case "POSTGRE":
			$ls_sql=" ALTER TABLE tepuy_empresa ADD COLUMN confinstr varchar(1) DEFAULT 'N'; ";
			break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ 
			 $this->io_msg->message("Problemas al ejecutar Release 2008_4_10");			 		 
			 $lb_valido=false;
		}
        return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_10
//-----------------------------------------------------------------------------------------------------------------------------------
	 function uf_create_release_db_libre_V_2008_4_11()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_11
		//		   Access: public 
		//        Modulos: SCG
		//	  Description: 
		// Fecha Creacion: 27/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
		       case "MYSQL":
				   $ls_sql= " ALTER TABLE scg_pc_reporte                   ".
				            "   ADD saldo_real_ant double(19,4) default 0, ".
							"   ADD saldo_apro double(19,4) default 0,     ".
							"   ADD saldo_mod double(19,4) default 0;      ";				
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scg_pc_reporte                       ".
				            "   ADD saldo_real_ant double precision default 0, ".
							"   ADD saldo_apro double precision default 0,     ".
							"   ADD saldo_mod double precision default 0;      ";							
					break;  				  
	    }
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_11");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_11
//----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_12()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_12
		//		   Access: public 
		//        Modulos: SCB
		//	  Description: 
		// Fecha Creacion: 27/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true; 
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco','conmov','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql=" ALTER TABLE scb_movbco MODIFY COLUMN conmov                     ".
				           "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT  NULL;  ";			  			
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scb_movbco ALTER conmov TYPE text;";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_12-1");
					$lb_valido=false;
				}
			}	   			
	   }
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_scg','desmov','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql="  ALTER TABLE scb_movbco_scg MODIFY COLUMN desmov                    ".
				           "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT  NULL;      ";						   				
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scb_movbco_scg ALTER desmov TYPE text; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_12-2");
					$lb_valido=false;
				}
			}	   			
	   }
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spg','desmov','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql="  ALTER TABLE scb_movbco_spg MODIFY COLUMN desmov                    ".
				           "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT  NULL;     ";	  
				  					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scb_movbco_spg ALTER desmov TYPE text; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_12-3");
					$lb_valido=false;
				}
			}	   			
	   }
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spi','desmov','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				    $ls_sql="  ALTER TABLE scb_movbco_spi MODIFY COLUMN desmov                    ".
				            "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT  NULL      ";					   			
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scb_movbco_spi ALTER desmov TYPE text; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_12-4");
					$lb_valido=false;
				}
			}	   			
	   }
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_12
//-------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_13()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_13
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 02/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
		       case "MYSQL":
				   $ls_sql=  " ALTER table sno_personal           ". 
							 "	 ADD talcamper varchar(5),        ".
							 "	 ADD talzapper double  default 0, ".
							 "	 ADD talpanper varchar(5);        ";		
				   break;
				   
				case "POSTGRE":
				    $ls_sql= " ALTER table sno_personal                     ". 
							 "	 ADD talcamper varchar(5),                  ".
							 "	 ADD talzapper double precision default 0,  ".
							 "	 ADD talpanper varchar(5);                  ";					
					break;  				  
	    }
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_13");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_13
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_14()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_14
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " ALTER TABLE siv_articulo          ".
				            "   ADD estact char(1) DEFAULT '0';";
															
				   break;
				   
			case "POSTGRE":
				     $ls_sql= " ALTER TABLE siv_articulo        ".
				            "   ADD estact char(1) DEFAULT '0'; ";
																	
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_14");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_14
//-------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_15()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_15
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 09/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_personal ".
                            "   ADD anoservprecont integer NOT NULL DEFAULT 0; ";								
				   break;
				   
			case "POSTGRE":
				    $ls_sql= " ALTER TABLE sno_personal ".
                             "   ADD anoservprecont integer NOT NULL DEFAULT 0; ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_15");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_15
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_16()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_16
		//		   Access: public 
		//        Modulos: SNO
		//	  Description: 
		// Fecha Creacion: 10/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " ALTER TABLE sno_personal ".
                            "   ADD anoservprefijo integer NOT NULL DEFAULT 0; ";								
				   break;
				   
			case "POSTGRE":
				    $ls_sql= " ALTER TABLE sno_personal ".
                             "   ADD anoservprefijo integer NOT NULL DEFAULT 0; ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_16");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_16
//----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	 function uf_create_release_db_libre_V_2008_4_17()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_17
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 10/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " ALTER TABLE siv_articulo          ".				            
							"   ADD codact char(15);            ";								
				   break;
				   
			case "POSTGRE":
				     $ls_sql= " ALTER TABLE siv_articulo        ".				           
							"   ADD codact char(15);            ";											
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_17");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_17
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_18()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_18
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 12/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " ALTER TABLE siv_dt_recepcion          ".
   							"	ADD estregact  smallint DEFAULT 0;  ";								
				   break;
				   
			case "POSTGRE":
				     $ls_sql= " ALTER TABLE siv_dt_recepcion          ".
   							  "   ADD estregact  smallint DEFAULT 0;  ";											
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_18");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_18
//------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_19()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_19
		//		   Access: public 
		//        Modulos: SCB
		//	  Description: 
		// Fecha Creacion: 16/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true; 
	   $lb_existe="";
	   switch($_SESSION["ls_gestor"])
	  	{
			case "MYSQL":
				  $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spgop','desmov','longtext');				
			 break;
				   
			case "POSTGRE":
				 $lb_existe =$this->io_function_db->uf_select_type_columna('scb_movbco_spgop','desmov','text');
				   								
			break;  				  
		}	   
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				    $ls_sql=" ALTER TABLE scb_movbco_spgop MODIFY COLUMN desmov               ".
				           "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT  NULL;  ";				
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scb_movbco_spgop ALTER desmov TYPE text;";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_19");
					$lb_valido=false;
				}
			}	   			
	   }	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_19
//----------------------------------------------------------------------------------------------------------------------------------
///---------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_20()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_20
		//		   Access: public 
		//        Modulos: SOB
		//	  Description: 
		// Fecha Creacion: 17/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true; 
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql="  ALTER TABLE rpc_proveedor ADD COLUMN sc_ctaant varchar(25);          ";					 			
			  break;
				   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE rpc_proveedor ADD COLUMN sc_ctaant character varying(25);   ";
										   								
				break;  				  
	  }
	  if (!empty($ls_sql))
	  {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas  Release 2008_4_20-01");
					$lb_valido=false;
				}
	  }
	  switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql="	ALTER TABLE sob_anticipo ADD COLUMN estgenrd varchar(1) DEFAULT '0'; ";						 			
			  break;
				   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sob_anticipo ADD COLUMN estgenrd character varying(1) DEFAULT '0';  ";
										   								
				break;  				  
	  }
	  if (!empty($ls_sql))
	  {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas  Release 2008_4_20-02");
					$lb_valido=false;
				}
	  }	    
	  switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql="	ALTER TABLE sob_valuacion ADD COLUMN estgenrd varchar(1) DEFAULT '0';";				
			  break;
				   
			case "POSTGRE":
			   $ls_sql= " ALTER TABLE sob_valuacion ADD COLUMN estgenrd character varying(1) DEFAULT '0'; ";				   								
				break;  				  
	  }
	  if (!empty($ls_sql))
	  {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas  Release 2008_4_20-03");
					$lb_valido=false;
				}
	  }	    	    
	  return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_20  
 //-----------------------------------------------------------------------------------------------------------------------------------
 //-----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_21()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_21
		//		   Access: public 
		//        Modulos: SOB
		//	  Description: 
		// Fecha Creacion: 17/08/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true; 
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql="  CREATE TABLE `sob_documento` (  ".
						 "	 `codemp` CHAR(4) NOT NULL,     ".
						 "	 `coddoc` CHAR(3) NOT NULL,     ".
						 "	 `tipdoc` VARCHAR(15) NOT NULL, ".
						 "	 `desdoc` VARCHAR(254),         ".
						 "	 `condoc` TEXT,                 ".
						 "	 `tamletdoc` INTEGER UNSIGNED,  ".
						 "	 `intlindoc` INTEGER UNSIGNED,  ".
						 "	 `marinfdoc` DOUBLE(8,2),       ".
						 "	 `marsupdoc` DOUBLE(8,2),       ".
						 "	 `titdoc` TEXT,                 ".
						 "	 `piepagdoc` TEXT,              ".
						 "	 `arcrtfdoc` VARCHAR(50),       ".
						 "	 PRIMARY KEY (`codemp`, `coddoc`)) ENGINE = InnoDB;";				
			  break;
				   
			case "POSTGRE":
			   $ls_sql= " CREATE TABLE sob_documento(        ".
						"	  codemp character(4) NOT NULL,  ".
						"	  coddoc character(3) NOT NULL,  ".
						"	  tipdoc character(15),          ".
						"	  desdoc character varying(254), ".
						"	  condoc text,                   ".
						"	  tamletdoc integer,             ".
						"	  intlindoc integer,             ".
						"	  marinfdoc double precision DEFAULT 3, ".
						"	  marsupdoc double precision DEFAULT 4, ".
						"	  titdoc text,                          ".
						"	  piepagdoc text,                       ".
						"	  tamletpiedoc integer,                 ".
						"	  arcrtfdoc character varying(50),      ".
						"	  CONSTRAINT pk_sob_documento PRIMARY KEY (codemp, coddoc), ".
						"	  CONSTRAINT sob_documento__tepuy_empresa                  ".
						"     FOREIGN KEY (codemp) REFERENCES tepuy_empresa (codemp)   ".
						"     ON UPDATE NO ACTION ON DELETE NO ACTION) WITHOUT OIDS;    ";				   								
				break;  				  
	  }
	  if (!empty($ls_sql))
	  {	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas Release 2008_4_21");
				$lb_valido=false;
			}
	  }	    
	  if (($lb_valido)&&($_SESSION["ls_gestor"]=="MYSQL"))
	  {
	  		$ls_sql= "ALTER TABLE sob_documento                           ".
			         "  ADD CONSTRAINT `FK_sob_documento__tepuy_empresa` ".
					 "  FOREIGN KEY `FK_sob_documento__tepuy_empresa` (`codemp`)".
					 "  REFERENCES `tepuy_empresa` (`codemp`) ".
					 "  ON DELETE RESTRICT                     ".
					 "  ON UPDATE RESTRICT                     ";
				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_21-1");
				$lb_valido=false;
			}		 	    
	  }
	  return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_21  //-------------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_22()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_22
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_anticipos	(                   ".
						   "  codemp     char(4)       not null,            ".
						   "  codnom     char(4)       not null,            ".
						   "  codper     char(10)      not null,            ".
						   "  fecantper  date          not null,            ".
						   "  anoserper  integer       not null,            ".
						   "  messerper  integer       not null,            ".
						   "  diaserper  integer       not null,            ".
						   "  motant     varchar(254)  not null,            ".
						   "  mondeulab  double(19,4)  not null,            ".
						   "  monporant  double(19,4)  not null,            ".
						   "  monant     double(19,4)  not null,            ".
						   "  estant     varchar(1)    not null default '0',".
						   "  obsant     varchar(254))ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_anticipos (                        ".
						   " codemp               CHAR(4)              not null, ".
						   " codnom               CHAR(4)              not null, ".
						   " codper               CHAR(10)             not null, ".
						   " fecantper            DATE                 not null, ".
						   " anoserper            INT4                 not null, ".
						   " messerper            INT4                 not null, ".
						   " diaserper            INT4                 not null, ".
						   " motant               VARCHAR(254)         not null, ".
						   " mondeulab            FLOAT8               not null, ".
						   " monporant            FLOAT8               not null, ".
						   " monant               FLOAT8               not null, ".
						   " estant               VARCHAR(1)           not null default '0' ".
						   " constraint CKC_ESTANT_SPS_ANTI check (estant in ('0','1','2','3')), ".
						   "obsant               VARCHAR(254)         null); ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_22-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_anticipos ".
   							"   add primary key (codemp, codper, codnom, fecantper); ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_anticipos ".
                           "   add constraint PK_SPS_ANTICIPOS primary key (codemp, codper, codnom, fecantper);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_22-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	
	   //----------------creación de un index--------------------------------------------------------------------------------
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			
			case "MYSQL":
				   $ls_sql= " create unique index idx_anticipos on sps_anticipos ( ".
							"   codemp, codnom,                                    ".							
							"   codper,                                            ".
							"   fecantper);                                        ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create unique index idx_anticipos on sps_anticipos ( ".
						   " codemp,                                              ".
						   " codnom,                                              ".
						   " codper,                                              ".
						   " fecantper);                                          ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
			    switch($_SESSION["ls_gestor"])
	            {
				 	case "POSTGRE":
						$this->io_msg->message("Problemas al ejecutar Release 2008_4_22-03");
					break;
				}				
				$lb_valido=false;
			}
		}		
	   //-------------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_22
	//-------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_23()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_23
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_antiguedad(
							   codemp                         char(4)                        not null,
							   codnom                         char(4)                        not null,
							   codper                         char(10)                       not null,
							   fecant                         date                           not null,
							   anoserant                      integer                        not null,
							   messerant                      integer                        not null,
							   diaserant                      integer                        not null,
							   salbas                         double(19,4)                   not null default 0,
							   incbonvac                      double(19,4)                   not null,
							   incbonnav                      double(19,4)                   not null,
							   salint                         double(19,4)                   not null default 0,
							   salintdia                      double(19,4)                   not null,
							   diabas                         integer                        not null,
							   diacom                         integer                        not null,
							   diaacu                         integer                        not null,
							   monant                         double(19,4)                   not null default 0,
							   monacuant                      double(19,4)                   not null default 0,
							   monantant                      double(19,4)                   not null default 0,
							   salparant                      double(19,4)                   not null default 0,
							   porint                         double(5,2)                    not null,
							   diaint                         integer                        not null,
							   monint                         double(19,4)                   not null default 0,
							   monacuint                      double(19,4)                   not null default 0,
							   saltotant                      double(19,4)                   not null,
							   estcapint                      char(1)                        not null,
							   estant                         char(1)                        not null,
							   liquidacion                    char(10)
							)ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_antiguedad (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codper               CHAR(10)             not null,
							   fecant               DATE                 not null,
							   anoserant            INT4                 not null,
							   messerant            INT4                 not null,
							   diaserant            INT4                 not null,
							   salbas               FLOAT8               not null default '0',
							   incbonvac            FLOAT8               not null,
							   incbonnav            FLOAT8               not null,
							   salint               FLOAT8               not null default '0',
							   salintdia            FLOAT8               not null,
							   diabas               INT4                 not null,
							   diacom               INT4                 not null,
							   diaacu               INT4                 not null,
							   monant               FLOAT8               not null default '0',
							   monacuant            FLOAT8               not null default '0',
							   monantant            FLOAT8               not null default '0',
							   salparant            FLOAT8               not null default '0',
							   porint               FLOAT8               not null,
							   diaint               INT4                 not null,
							   monint               FLOAT8               not null default '0',
							   monacuint            FLOAT8               not null default '0',
							   saltotant            FLOAT8               not null,
							   estcapint            CHAR(1)              not null,
							   estant               CHAR(1)              not null 
							   constraint CKC_ESTANT_SPS_ANTI check (estant in ('R','P','L')),
							   liquidacion          CHAR(10)             null
							);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_23-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_antiguedad
   								add primary key (codemp, codper, codnom, fecant);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_antiguedad
   							   add constraint PK_SPS_ANTIGUEDAD primary key (codemp, codper, codnom, fecant);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_23-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	
	   //----------------creación de un index--------------------------------------------------------------------------------
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			
			case "MYSQL":
				   $ls_sql= " create unique index idx_antiguedad on sps_antiguedad	(
							  codemp,
							  codnom,
							  codper,
							  fecant); ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create unique index idx_antiguedad on sps_antiguedad (
							 codemp,
							 codnom,
							 codper,
							 fecant);   ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				switch($_SESSION["ls_gestor"])
	            {
				 	case "POSTGRE":
						$this->io_msg->message("Problemas al ejecutar Release 2008_4_23-03");
					break;
				}
				$lb_valido=false;
			}
		}		
	   //-------------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_23
//----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_24()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_24
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_articulos(
							   id_art                         char(4)                        not null,
							   numart                         char(4)                        not null,
							   fecvig                         date                           not null,
							   numlitart                      char(2)                        not null,
							   numcon                         char(1)                        not null,
							   conart                         varchar(60)                    not null,
							   operador                       char(1)                        not null,
							   canmes                         integer                        not null,
							   tiempo                         char(1)                        not null,
							   diasal                         double                         not null,
							   condicion                      char(4)                        not null,
							   estacu                         char(1)                        not null,
							   diaacu                         double                         not null
							)ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_articulos (
							   id_art               CHAR(4)              not null,
							   numart               CHAR(4)              not null,
							   fecvig               DATE                 not null,
							   numlitart            CHAR(2)              not null,
							   numcon               CHAR(1)              not null,
							   conart               VARCHAR(60)          not null,
							   operador             CHAR(1)              not null,
							   canmes               INT4                 not null,
							   tiempo               CHAR(1)              not null,
							   diasal               FLOAT8               not null,
							   condicion            CHAR(4)              not null,
							   estacu               CHAR(1)              not null,
							   diaacu               FLOAT8               not null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_24-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_articulos
   								add primary key (id_art, numart, fecvig, numlitart, numcon);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_articulos
   							   add constraint PK_SPS_ARTICULOS primary key (id_art, numart, fecvig, numlitart, numcon);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_24-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_24
//---------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_25()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_25
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_carta_anticipos(
							   codemp                         char(4)          not null,
							   codcarant                      char(3)          not null,
							   descarant                      varchar(254),
							   concarant                      text,
							   tamletcarant                   int,
							   intlincarant                   int,
							   marinfcarant                   double(5,2),
							   marsupcarant                   double(5,2),
							   titcarant                      text,
							   piepagcarant                   text,
							   tamletpiepag                   int,
							   arcrtfcarant                   varchar(50)
							)ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_carta_anticipos (
							   codemp               CHAR(4)              not null,
							   codcarant            CHAR(3)              not null,
							   descarant            VARCHAR(254)         null,
							   concarant            TEXT                 null,
							   tamletcarant         INT4                 null,
							   intlincarant         INT4                 null,
							   marinfcarant         FLOAT8               null,
							   marsupcarant         FLOAT8               null,
							   titcarant            TEXT                 null,
							   piepagcarant         TEXT                 null,
							   tamletpiepag         INT4                 null,
							   arcrtfcarant         VARCHAR(50)          null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_25-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_carta_anticipos
   								add primary key (codemp, codcarant);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_carta_anticipos
                               add constraint PK_SPS_CARTA_ANTICIPOS primary key (codemp, codcarant);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_25-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_25
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_26()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_26
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_causaretiro(
							   codcauret                      char(2)      not null,
							   dencauret                      varchar(50)  not null
							)ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_causaretiro (
							   codcauret            CHAR(2)     not null,
							   dencauret            VARCHAR(50) not null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_26-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_causaretiro
   								add primary key (codcauret);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_causaretiro
   							   add constraint PK_SPS_CAUSARETIRO primary key (codcauret);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_26-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_26
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_27()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_27
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_configuracion(
							   id                             char(1)     not null,
							   porant                         double(5,2) not null,
							   estsue                         char(1)     not null,
							   estincbon                      char(1)     not null,
							   sc_cuenta_ps                   char(25)    default ' ',
							   sig_cuenta_emp_fijo_ps         char(25)    default ' ',
							   sig_cuenta_emp_fijo_vac        char(25)    default ' ',
							   sig_cuenta_emp_fijo_agu        char(25)    default ' ',
							   sig_cuenta_obr_fijo_ps         char(25)    default ' ',
							   sig_cuenta_obr_fijo_vac        char(25)    default ' ',
							   sig_cuenta_obr_fijo_agu        char(25)    default ' ',
							   sig_cuenta_emp_cont_ps         char(25)    default ' ',
							   sig_cuenta_emp_cont_vac        char(25)    default ' ',
							   sig_cuenta_emp_cont_agu        char(25)    default ' ',
							   sig_cuenta_emp_esp_ps          char(25)    default ' ',
							   sig_cuenta_emp_esp_vac         char(25)    default ' ',
							   sig_cuenta_emp_esp_agu         char(25)    default ' '
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_configuracion (
							   id                   CHAR(1)              not null,
							   porant               FLOAT8               not null,
							   estsue               CHAR(1)              not null,
							   estincbon            CHAR(1)              not null,
							   sc_cuenta_ps         CHAR(25)             null default ' ',
							   sig_cuenta_emp_fijo_ps CHAR(25)           null default ' ',
							   sig_cuenta_emp_fijo_vac CHAR(25)          null default ' ',
							   sig_cuenta_emp_fijo_agu CHAR(25)          null default ' ',
							   sig_cuenta_obr_fijo_ps CHAR(25)           null default ' ',
							   sig_cuenta_obr_fijo_vac CHAR(25)          null default ' ',
							   sig_cuenta_obr_fijo_agu CHAR(25)          null default ' ',
							   sig_cuenta_emp_cont_ps CHAR(25)           null default ' ',
							   sig_cuenta_emp_cont_vac CHAR(25)          null default ' ',
							   sig_cuenta_emp_cont_agu CHAR(25)          null default ' ',
							   sig_cuenta_emp_esp_ps CHAR(25)            null default ' ',
							   sig_cuenta_emp_esp_vac CHAR(25)           null default ' ',
							   sig_cuenta_emp_esp_agu CHAR(25)           null default ' ');";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_27-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_configuracion
							   add primary key (id);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_configuracion
   							   add constraint PK_SPS_CONFIGURACION primary key (id);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_27-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_27
//------------------------------------------------------------------------------------------------------------------------------------- 
    function uf_create_release_db_libre_V_2008_4_28()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_28
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_deuda_anterior(
							   codemp                         char(4)           not null,
							   codnom                         char(4)           not null,
							   codper                         char(10)          not null,
							   feccordeuant                   date              not null,
							   deuantant                      double(19,4)      not null default 0,
							   deuantint                      double(19,4)      not null default 0,
							   antpag                         double(19,4)      not null default 0,
							   estdeuant                      char(1)           not null
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_deuda_anterior (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codper               CHAR(10)             not null,
							   feccordeuant         DATE                 not null,
							   deuantant            FLOAT8               not null default '0',
							   deuantint            FLOAT8               not null default '0',
							   antpag               FLOAT8               not null default '0',
							   estdeuant            CHAR(1)              not null 
								  constraint CKC_ESTDEUANT_SPS_DEUD check (estdeuant in ('E','P')));";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_28-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_deuda_anterior
   								add primary key (codemp, codper, codnom, feccordeuant);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_deuda_anterior
   							   add constraint PK_SPS_DEUDA_ANTERIOR primary key (codemp, codper, codnom, feccordeuant);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_28-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   //----------------creación de un index--------------------------------------------------------------------------------
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			
			case "MYSQL":
				   $ls_sql= " create unique index idx_deuda_anterior on sps_deuda_anterior(
							   codemp,
							   codnom,
							   codper,
							   feccordeuant); ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create unique index idx_deuda_anterior on sps_deuda_anterior (
								codemp,
								codnom,
								codper,
								feccordeuant);  ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
			    switch($_SESSION["ls_gestor"])
	            {
				 	case "POSTGRE":
						$this->io_msg->message("Problemas al ejecutar Release 2008_4_28-03");
					break;
				}			
				$lb_valido=false;
			}
		}		
	   //-------------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_28
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_29()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_29
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_dt_liquidacion(
							   codemp                         char(4)        not null,
							   codper                         char(10)       not null,
							   codnom                         char(4)        not null,
							   numliq                         char(10)       not null,
							   numespliq                      char(2)        not null,
							   desespliq                      char(150)      not null,
							   salpro                         double(19,4)   not null,
							   diapag                         double(8,4)    not null,
							   subtotal                       double(19,4)   not null,
							   sc_cuenta_ded                  char(25)
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_dt_liquidacion (
							   codemp               CHAR(4)              not null,
							   codper               CHAR(10)             not null,
							   codnom               CHAR(4)              not null,
							   numliq               CHAR(10)             not null,
							   numespliq            CHAR(2)              not null,
							   desespliq            CHAR(150)            not null,
							   salpro               FLOAT8               not null,
							   diapag               FLOAT8               not null,
							   subtotal             FLOAT8               not null,
							   sc_cuenta_ded        CHAR(25)             null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_29-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_dt_liquidacion
   								add primary key (codemp, codper, codnom, numliq, numespliq);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_dt_liquidacion
  							   add constraint PK_SPS_DT_LIQUIDACION primary key (codemp, codper, codnom, numliq, numespliq);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_29-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_29
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_30()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_30
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_dt_scg(
							   codemp                         char(4)                        not null,
							   codnom                         char(4)                        not null,
							   codcom                         char(15)                       not null,
							   tipo                           char(1)                        not null,
							   sc_cuenta                      char(25)                       not null,
							   debhab                         char(1)                        not null,
							   ced_bene                       varchar(10)                    not null,
							   descripcion                    varchar(254)                   not null,
							   monto                          double(19,4)                   not null,
							   estatus                        smallint                       not null,
							   estrd                          smallint                       not null,
							   codtipdoc                      varchar(5)                     not null,
							   fechaconta                     date,
							   fechaanula                     date
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_dt_scg (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codcom               CHAR(15)             not null,
							   tipo                 CHAR(1)              not null,
							   sc_cuenta            CHAR(25)             not null,
							   debhab               CHAR(1)              not null,
							   ced_bene             VARCHAR(10)          not null,
							   descripcion          VARCHAR(254)         not null,
							   monto                FLOAT8               not null,
							   estatus              INT2                 not null,
							   estrd                INT2                 not null,
							   codtipdoc            VARCHAR(5)           not null,
							   fechaconta           DATE                 null,
							   fechaanula           DATE                 null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_30-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_dt_scg
   								add primary key (codemp, codnom, tipo, sc_cuenta, debhab, codcom);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_dt_scg
   							   add constraint PK_SPS_DT_SCG primary key (codemp, codnom, tipo, sc_cuenta, debhab, codcom);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_30-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_30
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_31()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_30
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_dt_spg(
							   codemp                         char(4)       not null,
							   codnom                         char(4)       not null,
							   codcom                         char(15)      not null,
							   tipo                           char(1)       not null,
							   codestpro1                     char(20)      not null,
							   codestpro2                     char(6)       not null,
							   codestpro3                     char(3)       not null,
							   codestpro4                     char(2)       not null,
							   codestpro5                     char(2)       not null,
							   spg_cuenta                     char(25)      not null,
							   operacion                      char(3)       not null,
							   ced_bene                       varchar(10)   not null,
							   descripcion                    varchar(254)  not null,
							   monto                          double(19,4)  not null,
							   estatus                        smallint      not null,
							   estrd                          smallint      not null,
							   codtipdoc                      varchar(5)    not null,
							   fechaconta                     date,
							   fechaanula                     date
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_dt_spg (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codcom               CHAR(15)             not null,
							   tipo                 CHAR(1)              not null,
							   codestpro1           CHAR(20)             not null,
							   codestpro2           CHAR(6)              not null,
							   codestpro3           CHAR(3)              not null,
							   codestpro4           CHAR(2)              not null,
							   codestpro5           CHAR(2)              not null,
							   spg_cuenta           CHAR(25)             not null,
							   operacion            CHAR(3)              not null,
							   ced_bene             VARCHAR(10)          not null,
							   descripcion          VARCHAR(254)         not null,
							   monto                FLOAT8               not null,
							   estatus              INT2                 not null,
							   estrd                INT2                 not null,
							   codtipdoc            VARCHAR(5)           not null,
							   fechaconta           DATE                 null,
							   fechaanula           DATE                 null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_31-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_dt_spg
   							    add primary key (codemp, codnom, codcom, tipo, codestpro1, codestpro2, codestpro3, 
								                 codestpro4, codestpro5, spg_cuenta, operacion);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_dt_spg
                               add constraint PK_SPS_DT_SPG primary key (codemp, codnom, codcom, tipo, codestpro1, 
							                                             codestpro2, codestpro3, codestpro4, 
																		 codestpro5, spg_cuenta, operacion);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_31-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_31
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_32()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_32
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_liquidacion(
							   codemp                         char(4)        not null,
							   codnom                         char(4)        not null,
							   codper                         char(10)       not null,
							   numliq                         char(10)       not null,
							   codcauret                      char(2)        not null,
							   fecliq                         date           not null,
							   fecing                         date           not null,
							   fecegr                         date           not null,
							   salint                         double(19,4)   not null,
							   descargo                       char(40)       not null,
							   anoser                         integer        not null,
							   messer                         integer        not null,
							   diaser                         integer        not null,
							   totasiliq                      double(19,4)   not null,
							   totdedliq                      double(19,4)   not null,
							   totpagliq                      double(19,4)   not null,
							   estliq                         char(1)        not null,
							   obsliq                         varchar(200),
							   dedicacion                     varchar(100)   not null,
							   tipopersonal                   varchar(100)   not null
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_liquidacion (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codper               CHAR(10)             not null,
							   numliq               CHAR(10)             not null,
							   codcauret            CHAR(2)              not null,
							   fecliq               DATE                 not null,
							   fecing               DATE                 not null,
							   fecegr               DATE                 not null,
							   salint               FLOAT8               not null,
							   descargo             CHAR(40)             not null,
							   anoser               INT4                 not null,
							   messer               INT4                 not null,
							   diaser               INT4                 not null,
							   totasiliq            FLOAT8               not null,
							   totdedliq            FLOAT8               not null,
							   totpagliq            FLOAT8               not null,
							   estliq               CHAR(1)              not null 
								  constraint CKC_ESTLIQ_SPS_LIQU check (estliq in ('R','A','P')),
							   obsliq               VARCHAR(200)         null,
							   dedicacion           VARCHAR(100)         not null,
							   tipopersonal         VARCHAR(100)         not null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_32-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_liquidacion
							   add primary key (codemp, codper, codnom, numliq);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_liquidacion
                               add constraint PK_SPS_LIQUIDACION primary key (codemp, codper, codnom, numliq);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_32-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   //----------------creación de un index--------------------------------------------------------------------------------
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			
			case "MYSQL":
				   $ls_sql= " create unique index idx_liquidacion on sps_liquidacion(
							   codemp,
							   codnom,
							   codper,
							   numliq);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create unique index idx_liquidacion on sps_liquidacion (
								codemp,
								codnom,
								codper,
								numliq); ";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				switch($_SESSION["ls_gestor"])
	            {
				 	case "POSTGRE":
						$this->io_msg->message("Problemas al ejecutar Release 2008_4_32-03");
					break;
				}			
				$lb_valido=false;
			}
		}		
	   //-------------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_32
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_33()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_33
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_sueldos(
							   codemp                         char(4)       not null,
							   codnom                         char(4)       not null,
							   codper                         char(10)      not null,
							   fecincsue                      date          not null,
							   monsuebas                      double(19,4)  not null,
							   monsueint                      double(19,4)  not null,
							   monsuenordia                   double(19,4)  not null
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_sueldos (
							   codemp               CHAR(4)              not null,
							   codnom               CHAR(4)              not null,
							   codper               CHAR(10)             not null,
							   fecincsue            DATE                 not null,
							   monsuebas            FLOAT8               not null,
							   monsueint            FLOAT8               not null,
							   monsuenordia         FLOAT8               not null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_33-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_sueldos
   								add primary key (codemp, codper, codnom, fecincsue);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_sueldos
                               add constraint PK_SPS_SUELDOS primary key (codemp, codper, codnom, fecincsue);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_33-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   //----------------creación de un index--------------------------------------------------------------------------------
	   $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			
			case "MYSQL":
				   $ls_sql= " create unique index idx_sueldos on sps_sueldos(
							   codemp,
							   codnom,
							   codper,
							   fecincsue);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create unique index idx_sueldos on sps_sueldos (
								codemp,
								codnom,
								codper,
								fecincsue);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				switch($_SESSION["ls_gestor"])
	            {
				 	case "POSTGRE":
						$this->io_msg->message("Problemas al ejecutar Release 2008_4_33-03");
					break;
				}				
				$lb_valido=false;
			}
		}		
	   //-------------------------------------------------------------------------------------------------------------------------
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_33
//-------------------------------------------------------------------------------------------------------------------------------------
     function constrains_foraneos()
	 {
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				  $ls_sql="alter table sps_tasa_interes
   							   add primary key (anotasint, mestasint);
							alter table sps_anticipos add constraint FK_reference_437 foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper) on delete restrict on update restrict;							
							alter table sps_antiguedad add constraint FK_reference_435 foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper) on delete restrict on update restrict;							
							alter table sps_carta_anticipos add constraint FK_reference_150 foreign key (codemp)
								  references tepuy_empresa (codemp) on delete restrict on update restrict;							
							alter table sps_deuda_anterior add constraint FK_reference_439 foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper) on delete restrict on update restrict;							
							alter table sps_dt_liquidacion add constraint FK_reference_8 foreign key (codemp, codper, 
							      codnom, numliq) references sps_liquidacion (codemp, codper, codnom, numliq) 
								  on delete restrict on update restrict;							
							alter table sps_dt_scg add constraint FK_reference_151 foreign key (codemp)
								  references tepuy_empresa (codemp) on delete restrict on update restrict;							
							alter table sps_dt_spg add constraint FK_reference_152 foreign key (codemp)
								  references tepuy_empresa (codemp) on delete restrict on update restrict;							
							alter table sps_liquidacion add constraint FK_reference_436 foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper) on delete restrict on update restrict;							
							alter table sps_liquidacion add constraint FK_reference_7 foreign key (codcauret)
								  references sps_causaretiro (codcauret) on delete restrict on update restrict;							
							alter table sps_sueldos add constraint FK_reference_438 foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper) on delete restrict on update restrict;";								
				   break;
				   
			case "POSTGRE":
				  $ls_sql=" alter table sps_anticipos
							  add constraint FK_SPS_ANTI_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper)
								  on delete restrict on update restrict;							
							alter table sps_antiguedad
							   add constraint FK_SPS_ANTI_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper)
								  on delete restrict on update restrict;							
							alter table sps_carta_anticipos
							   add constraint FK_SPS_CART_REFERENCE_tepuy_E foreign key (codemp)
								  references tepuy_empresa (codemp)
								  on delete restrict on update restrict;							
							alter table sps_deuda_anterior
							   add constraint FK_SPS_DEUD_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper)
								  on delete restrict on update restrict;							
							alter table sps_dt_liquidacion
							   add constraint FK_SPS_DT_L_REFERENCE_SPS_LIQU foreign key (codemp, codper, codnom, numliq)
								  references sps_liquidacion (codemp, codper, codnom, numliq)
								  on delete restrict on update restrict;							
							alter table sps_dt_scg
							   add constraint FK_SPS_DT_S_REFERENCE_tepuy_E foreign key (codemp)
								  references tepuy_empresa (codemp)
								  on delete restrict on update restrict;							
							alter table sps_dt_spg
							   add constraint FK_SPS_DT_S_REFERENCE_tepuy_E foreign key (codemp)
								  references tepuy_empresa (codemp)
								  on delete restrict on update restrict;							
							alter table sps_liquidacion
							   add constraint FK_SPS_LIQU_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper)
								  on delete restrict on update restrict;							
							alter table sps_liquidacion
							   add constraint FK_SPS_LIQU_REFERENCE_SPS_CAUS foreign key (codcauret)
								  references sps_causaretiro (codcauret)
								  on delete restrict on update restrict;							
							alter table sps_sueldos
							   add constraint FK_SPS_SUEL_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
								  references sno_personalnomina (codemp, codnom, codper)
								  on delete restrict on update restrict;";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release constrains_foraneos");
				$lb_valido=false;
			}
		}	
	 return $lb_valido;	
	} // end function cosntrains_foraneos
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_34()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_34
		//		   Access: public 
		//        Modulos: SPS
		//	  Description: 
		// Fecha Creacion: 21/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" create table sps_tasa_interes(
							   anotasint                      integer     not null,
							   mestasint                      integer     not null,
							   valtas                         double      not null,
							   numgac                         char(6)     not null
							) ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci ";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" create table sps_tasa_interes (
							   anotasint            INT4                 not null,
							   mestasint            INT4                 not null,
							   valtas               FLOAT8               not null,
							   numgac               CHAR(6)              not null);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_34-01");
				$lb_valido=false;
			}
		}	
	   
	   //-----------creacion de la clave primaria---------------------------------------------------------------------------
       $ls_sql="";
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql= " alter table sps_tasa_interes
                                add primary key (anotasint, mestasint);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" alter table sps_tasa_interes
                              add constraint PK_SPS_TASA_INTERES primary key (anotasint, mestasint);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_34-02");
				$lb_valido=false;
			}
		}		
	   //--------------------------------------------------------------------------------------------------------------------	  
	   if ($lb_valido)
	   {
	   		$this->constrains_foraneos();
	   }
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_34  
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_35()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_35
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: 
		// Fecha Creacion: 25/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   $lb_existe="";   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" ALTER TABLE saf_movimiento ADD COLUMN ubigeoact varchar(100) DEFAULT ' ';";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" ALTER TABLE saf_movimiento ADD COLUMN ubigeoact varchar(100) DEFAULT ' ';";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_35");
				$lb_valido=false;
			}
		} 	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_35 
//------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_db_libre_V_2008_4_36()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_36
		//		   Access: public 
		//        Modulos: SOC
		//	  Description: 
		// Fecha Creacion: 29/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   $lb_existe="";   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" ALTER TABLE soc_solicitudcargos ADD numsol varchar(15);";								
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" ALTER TABLE soc_solicitudcargos ADD numsol varchar(15);";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_36");
				$lb_valido=false;
			}
		} 	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_36 
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_37()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_37
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 29/09/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   $lb_existe="";   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				   $ls_sql=" ALTER TABLE tepuy_empresa ADD estmodpartsep char(1) DEFAULT '0';";								
				   break;
				   
			case "POSTGRE":
				    $ls_sql=" ALTER TABLE tepuy_empresa ADD estmodpartsep char(1) DEFAULT '0';";											
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_37-01");
				$lb_valido=false;
			}
		} 
			   
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
				    $ls_sql=" ALTER TABLE tepuy_empresa ADD estmodpartsoc char(1) DEFAULT '0';";							
				   break;
				   
			case "POSTGRE":
				   $ls_sql=" ALTER TABLE tepuy_empresa ADD estmodpartsoc char(1) DEFAULT '0';";										
					break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas al ejecutar Release 2008_4_37-02");
				$lb_valido=false;
			}
		} 	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_37 
//---------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_38()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_38
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 01/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="";	   
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql=" ALTER TABLE saf_activo ".
							   "   ADD tipinm varchar(1)";								
					   break;
					   
				case "POSTGRE":
					   $ls_sql=" ALTER TABLE saf_activo ".
							   "   ADD tipinm varchar(1)";											
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_38");
					$lb_valido=false;
				}
			}	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_38 
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_39()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_39
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 01/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="";	   
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql=" CREATE TABLE saf_edificios(
								  codemp varchar(4) NOT NULL,
								  codact varchar(15) NOT NULL,
								  expact varchar(15) NOT NULL,
								  clasfun text,
								  diract text,

								  areatot double precision,
								  areacons double precision,
								  numpiso int4,
								  areatotpiso double precision,
								  areanex double precision,  
								  lindero text,
								  estlegprop text,
								  avaluo text,
								  feccont date default '1900-01-01',
								  moncont double  precision default 0.00,
								  CONSTRAINT pk_saf_edificios PRIMARY KEY (codemp, codact, expact))
								  ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci";								
					   break;
					   
				case "POSTGRE":
					      $ls_sql=" CREATE TABLE saf_edificios(
								  codemp varchar(4) NOT NULL,
								  codact varchar(15) NOT NULL,
								  expact varchar(15) NOT NULL,
								  clasfun text,
								  diract text,
								  areatot double precision,
								  areacons double precision,
								  numpiso int4,
								  areatotpiso double precision,
								  areanex double precision,  
								  lindero text,
								  estlegprop text,
								  avaluo text,
								  feccont date default '1900-01-01',
								  moncont double  precision default 0.00,
								  CONSTRAINT pk_saf_edificios PRIMARY KEY (codemp, codact, expact))";													
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_39-01");
					$lb_valido=false;
				}
			}
		if ($lb_valido)
		{
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql=" ALTER TABLE saf_edificios								
									ADD CONSTRAINT fk_saf_activos FOREIGN KEY (codemp, codact)
									REFERENCES saf_activo (codemp, codact) MATCH SIMPLE
									ON UPDATE NO ACTION ON DELETE NO ACTION";								
					   break;
					   
				case "POSTGRE":
					      $ls_sql=" ALTER TABLE saf_edificios									
									ADD CONSTRAINT fk_saf_activos FOREIGN KEY (codemp, codact)
									REFERENCES saf_activo (codemp, codact) MATCH SIMPLE
									ON UPDATE NO ACTION ON DELETE NO ACTION";												
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_39-02");
					$lb_valido=false;
				}
			}
		}	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_39 
//----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_40()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_40
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 01/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="";	   
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql="create table saf_tipoestructura(
								    codemp varchar(4) NOT NULL,
								    codtipest varchar(4) NOT NULL,
								    dentipest varchar(50),
								    CONSTRAINT pk_saf_tipoest PRIMARY KEY (codemp, codtipest))								    
									ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci; ";								
					   break;
					   
				case "POSTGRE":
					      $ls_sql="create table saf_tipoestructura(
								    codemp varchar(4) NOT NULL,
								    codtipest varchar(4) NOT NULL,
								    dentipest varchar(50),
								    CONSTRAINT pk_saf_tipoest PRIMARY KEY (codemp, codtipest))								    
									WITHOUT OIDS; ";												
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_40-01");
					$lb_valido=false;
				}
			}
		if ($lb_valido)
		{
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					  $ls_sql=" ALTER TABLE saf_tipoestructura									
								ADD CONSTRAINT fk_saf_tipoest_emp FOREIGN KEY (codemp)
								    REFERENCES tepuy_empresa (codemp) 
								    MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT";							
					   break;
					   
				case "POSTGRE":
					    $ls_sql=" ALTER TABLE saf_tipoestructura								
								    ADD CONSTRAINT fk_saf_tipoest_emp FOREIGN KEY (codemp)
								    REFERENCES tepuy_empresa (codemp) 
								    MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT";											
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_40-02");
					$lb_valido=false;
				}
			}
		}	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_40
//----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_41()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_41
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 01/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="";	   
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql="create table saf_componente(
								 codemp varchar(4) NOT NULL,
								 codtipest varchar(4) NOT NULL,
								 codcomp varchar(4) NOT NULL,
								 dencomp varchar(50),
								 CONSTRAINT pk_saf_componente PRIMARY KEY (codemp, codtipest, codcomp))
								 ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci; ";								
					   break;
					   
				case "POSTGRE":
					      $ls_sql="create table saf_componente(
								 codemp varchar(4) NOT NULL,
								 codtipest varchar(4) NOT NULL,
								 codcomp varchar(4) NOT NULL,
								 dencomp varchar(50),
								 CONSTRAINT pk_saf_componente PRIMARY KEY (codemp, codtipest, codcomp))
								 WITHOUT OIDS;";												
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_41-01");
					$lb_valido=false;
				}
			}
		if ($lb_valido)
		{
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					  $ls_sql=" ALTER TABLE saf_componente									
								ADD  CONSTRAINT fk_saf_componente_emp FOREIGN KEY (codemp)
                                     REFERENCES tepuy_empresa (codemp) MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT";							
					   break;
					   
				case "POSTGRE":
					  $ls_sql=" ALTER TABLE saf_componente									
								ADD  CONSTRAINT fk_saf_componente_emp FOREIGN KEY (codemp)
                                     REFERENCES tepuy_empresa (codemp) MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT";													
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_41-02");
					$lb_valido=false;
				}
			}
		}	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_41
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_release_db_libre_V_2008_4_42()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_42
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 01/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="";	   
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql="create table saf_edificiotipest(
								 codemp varchar(4) NOT NULL,
								 codtipest varchar(4) NOT NULL,
								 codcomp varchar(4) NOT NULL,
								 codact character varying(15) NOT NULL,
								 expact character varying(15) NOT NULL,
								 CONSTRAINT pk_saf_edificiotipest PRIMARY KEY (codemp, codtipest, codcomp, codact, expact))
								 ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci; ";								
					   break;
					   
				case "POSTGRE":
					      $ls_sql="create table saf_edificiotipest(
								   codemp varchar(4) NOT NULL,
								   codtipest varchar(4) NOT NULL,
								   codcomp varchar(4) NOT NULL,
								   codact character varying(15) NOT NULL,
								   expact character varying(15) NOT NULL,
								   CONSTRAINT pk_saf_edificiotipest PRIMARY KEY (codemp, codtipest, codcomp, codact, expact))
								   WITHOUT OIDS;";												
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_42-01");
					$lb_valido=false;
				}
			}
		if ($lb_valido)
		{
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					  $ls_sql=" ALTER TABLE saf_edificiotipest									
								ADD   CONSTRAINT fk_saf_edificiotipest_emp FOREIGN KEY (codemp)
									  REFERENCES tepuy_empresa (codemp) MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT,
								ADD   CONSTRAINT fk_saf_edificiotipest_tipoestructura FOREIGN KEY (codemp, codtipest)
									  REFERENCES saf_tipoestructura (codemp, codtipest) MATCH SIMPLE ON UPDATE RESTRICT 
									  ON DELETE RESTRICT,
							    ADD   CONSTRAINT fk_saf_edificiotipest_componente FOREIGN KEY (codemp, codtipest, codcomp)
									  REFERENCES saf_componente (codemp, codtipest, codcomp) MATCH SIMPLE ON UPDATE 
									  RESTRICT ON DELETE RESTRICT,
								ADD   CONSTRAINT fk_saf_edificiotipest_edificio FOREIGN KEY (codemp, codact, expact)
									  REFERENCES saf_edificios (codemp, codact, expact) MATCH SIMPLE ON UPDATE RESTRICT 
									  ON DELETE RESTRICT";							
					   break;
					   
				case "POSTGRE":
					  $ls_sql=" ALTER TABLE saf_edificiotipest									
								ADD   CONSTRAINT fk_saf_edificiotipest_emp FOREIGN KEY (codemp)
									  REFERENCES tepuy_empresa (codemp) MATCH SIMPLE ON UPDATE RESTRICT ON DELETE RESTRICT,
								ADD   CONSTRAINT fk_saf_edificiotipest_tipoestructura FOREIGN KEY (codemp, codtipest)
									  REFERENCES saf_tipoestructura (codemp, codtipest) MATCH SIMPLE ON UPDATE RESTRICT 
									  ON DELETE RESTRICT,
							    ADD   CONSTRAINT fk_saf_edificiotipest_componente FOREIGN KEY (codemp, codtipest, codcomp)
									  REFERENCES saf_componente (codemp, codtipest, codcomp) MATCH SIMPLE ON UPDATE 
									  RESTRICT ON DELETE RESTRICT,
								ADD   CONSTRAINT fk_saf_edificiotipest_edificio FOREIGN KEY (codemp, codact, expact)
									  REFERENCES saf_edificios (codemp, codact, expact) MATCH SIMPLE ON UPDATE RESTRICT 
									  ON DELETE RESTRICT";														
						break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas al ejecutar Release 2008_4_42-02");
					$lb_valido=false;
				}
			}
		}	   
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_42
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
      function uf_create_release_db_libre_V_2008_4_43()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_43
		//		   Access: public 
		//        Modulos: SCV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_existe1 = $this->io_function_db->uf_select_constraint('scv_solicitudviatico','fk_scv_soli_scv_rutas_scv_ruta');
	   if ($ls_existe1)
	   {
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
			       $ls_sql= " ALTER TABLE scv_solicitudviatico DROP CONSTRAINT fk_scv_soli_scv_rutas_scv_ruta;";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scv_solicitudviatico DROP CONSTRAINT fk_scv_soli_scv_rutas_scv_ruta;";	
					break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_4352-01");
					 $lb_valido=false;
				 }
			}
	    }//fin del if($ls_existe)	   
	   //-----------------------------------------------------------------------------------------
	   if ($lb_valido)
	   {
		   $ls_existe2 = $this->io_function_db->uf_select_constraint('scv_rutas','ak_key_2_scv_ruta');
		   if ($ls_existe2)
		   {
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
				  $ls_sql= " ALTER TABLE scv_rutas DROP CONSTRAINT ak_key_2_scv_ruta;";					
					   break;
					   
					case "POSTGRE":
					   $ls_sql= " ALTER TABLE scv_rutas DROP CONSTRAINT ak_key_2_scv_ruta;";	
						break;				  
				}
				if (!empty($ls_sql))
				{	
					 $li_row=$this->io_sql->execute($ls_sql);
					 if($li_row===false)
					 { 
						 $this->io_msg->message("Problemas al ejecutar Release 2008_4_43-02");
						 $lb_valido=false;
					 }
				}
		   }//fin del if($ls_existe)
		}// fin del if($lb_valido)	   
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_43 
//------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_44()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_44
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql= " create table siv_segmento (
							   codemp   char(4)   not null,
							   codseg   char(2)   not null,
							   desseg   text      null,
							   tipo     char(1)   null,
							   constraint pk_siv_segmento primary key (codemp, codseg))
							   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci;";					
              break;
				   
			case "POSTGRE":
				 $ls_sql= " create table siv_segmento (
							   codemp   char(4)   not null,
							   codseg   char(2)   not null,
							   desseg   text      null,
							   tipo     char(1)   null,
							   constraint pk_siv_segmento primary key (codemp, codseg))
							   WITHOUT OIDS";	
			  break;				  
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_44-01");
				 $lb_valido=false;
			 }
		}
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  alter table siv_segmento
								   add constraint fk_siv_segmento__tepuy_empresa foreign key (codemp)
									  references tepuy_empresa (codemp)
									  on delete restrict on update restrict;";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  alter table siv_segmento
								   add constraint fk_siv_segmento__tepuy_empresa foreign key (codemp)
									  references tepuy_empresa (codemp)
									  on delete restrict on update restrict;";	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_44-02");
					 $lb_valido=false;
				 }
			}
		}// fin del if  
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  insert into siv_segmento (codemp, codseg) values ('".$this->ls_codemp."', '--');";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  insert into siv_segmento (codemp, codseg) values ('".$this->ls_codemp."', '--');";					
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_44-03");
					 $lb_valido=false;
				 }
			}
		}// fin del if    
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_44 
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_45()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_45
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql= " create table siv_familia (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   desfami              text                 null,
							   constraint pk_siv_familia primary key (codemp, codseg, codfami))
							   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci;";					
              break;
				   
			case "POSTGRE":
				$ls_sql= " create table siv_familia (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   desfami              text                 null,
							   constraint pk_siv_familia primary key (codemp, codseg, codfami))
							   WITHOUT OIDS";	
			  break;				  
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_45-01");
				 $lb_valido=false;
			 }
		}
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  alter table siv_familia
								    add constraint fk_siv_familia__siv_segmento foreign key (codemp, codseg)
									  references siv_segmento (codemp, codseg)
									  on delete restrict on update restrict;";					
				  break;
					   
				case "POSTGRE":
					   $ls_sql= "  alter table siv_familia
								    add constraint fk_siv_familia__siv_segmento foreign key (codemp, codseg)
									  references siv_segmento (codemp, codseg)
									  on delete restrict on update restrict;";	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_45-02");
					 $lb_valido=false;
				 }
			}
		}// fin del if
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  insert into siv_familia (codemp, codseg, codfami) values ('".$this->ls_codemp."', '--','----');";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  insert into siv_familia  (codemp, codseg, codfami) values ('".$this->ls_codemp."', '--', '----');";					
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_45-03");
					 $lb_valido=false;
				 }
			}
		}// fin del if       
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_45 
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_46()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_46
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql= " create table siv_clase (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   codclase             char(6)              not null,
							   desclase             text                 null,
							   constraint pk_siv_clase primary key (codemp, codseg, codfami, codclase))
							   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci;";					
              break;
				   
			case "POSTGRE":
				 $ls_sql= " create table siv_clase (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   codclase             char(6)              not null,
							   desclase             text                 null,
							   constraint pk_siv_clase primary key (codemp, codseg, codfami, codclase))
							   WITHOUT OIDS";	
			  break;				  
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_46-01");
				 $lb_valido=false;
			 }
		}
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  alter table siv_clase
								    add constraint fk_siv_clase__siv_familia foreign key (codemp, codseg, codfami)
									    references siv_familia (codemp, codseg, codfami)
									    on delete restrict on update restrict;";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  alter table siv_clase
								    add constraint fk_siv_clase__siv_familia foreign key (codemp, codseg, codfami)
									    references siv_familia (codemp, codseg, codfami)
									    on delete restrict on update restrict;";
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_46-02");
					 $lb_valido=false;
				 }
			}
		}// fin del if
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  insert into siv_clase (codemp, codseg, codfami, codclase) values ('".$this->ls_codemp."', '--','----','------');";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  insert into siv_clase (codemp, codseg, codfami, codclase) values ('".$this->ls_codemp."', '--', '----', '------');";					
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_46-03");
					 $lb_valido=false;
				 }
			}
		}// fin del if          
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_46 
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_47()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_47
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql= " create table siv_producto (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   codclase             char(6)              not null,
							   codprod              char(8)              not null,
							   desproducto          text                 null,
							   constraint pk_siv_producto primary key (codemp, codseg, codfami, codclase, codprod))
							   ENGINE = InnoDB CHAR SET `utf8` COLLATE `utf8_general_ci;";					
              break;
				   
			case "POSTGRE":
				  $ls_sql= " create table siv_producto (
							   codemp               char(4)              not null,
							   codseg               char(2)              not null,
							   codfami              char(4)              not null,
							   codclase             char(6)              not null,
							   codprod              char(8)              not null,
							   desproducto          text                 null,
							   constraint pk_siv_producto primary key (codemp, codseg, codfami, codclase, codprod))
							   WITHOUT OIDS";	
			  break;				  
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_47-01");
				 $lb_valido=false;
			 }
		}
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  alter table siv_producto
								   add constraint fk_siv_producto__siv_clase foreign key (codemp, codseg, codfami, codclase)
									   references siv_clase (codemp, codseg, codfami, codclase)
									   on delete restrict on update restrict;";					
				  break;
					   
				case "POSTGRE":
					   $ls_sql= "  alter table siv_producto
								   add constraint fk_siv_producto__siv_clase foreign key (codemp, codseg, codfami, codclase)
									   references siv_clase (codemp, codseg, codfami, codclase)
									   on delete restrict on update restrict;";	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_47-02");
					 $lb_valido=false;
				 }
			}
		}// fin del if
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
	   		{
				case "MYSQL":
					  $ls_sql= "  insert into siv_producto (codemp, codseg, codfami, codclase, codprod) values ('".$this->ls_codemp."', '--','----','------','--------');";					
				  break;
					   
				case "POSTGRE":
					  $ls_sql= "  insert into siv_producto (codemp, codseg, codfami, codclase, codprod) values ('".$this->ls_codemp."', '--', '----', '------', '--------');";					
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_47-03");
					 $lb_valido=false;
				 }
			}
		}// fin del if             
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_47 
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_48()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_48
		//		   Access: public 
		//        Modulos: SIV
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
			     $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codseg char(2) null default '--'; ";

              break;
				   
			case "POSTGRE":
				  $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codseg char(2) null default '--'; ";						  	
			  break;				  
		}
		if (!empty($ls_sql))
		{	
			 $li_row=$this->io_sql->execute($ls_sql);
			 if($li_row===false)
			 { 
				 $this->io_msg->message("Problemas al ejecutar Release 2008_4_48-01");
				 $lb_valido=false;
			 }
		}
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					 $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codfami char(4) null default '----'; ";
	
				  break;
					   
				case "POSTGRE":
					  $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codfami char(4) null default '----'; ";				  	

				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_48-02");
					 $lb_valido=false;
				 }
			}
		}// fin del if   
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					 $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codclase  char(6) null default '------'; ";
	
				  break;
					   
				case "POSTGRE":
					   $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codclase  char(6) null default '------'; ";			  	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_48-03");
					 $lb_valido=false;
				 }
			}
		}// fin del if  
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					 $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codprod  char(8) null default '--------'; ";
	
				  break;
					   
				case "POSTGRE":
					  $ls_sql= " ALTER TABLE siv_articulo ADD COLUMN codprod  char(8) null default '--------'; ";			  	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_48-04");
					 $lb_valido=false;
				 }
			}
		}// fin del if  
		if ($lb_valido)
		{
			switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					 $ls_sql= " alter table siv_articulo
								   add constraint fk_siv_articulo__siv_producto foreign key 
								      (codemp, codseg, codfami, codclase, codprod)
									  references siv_producto (codemp, codseg, codfami, codclase, codprod)
									  on delete restrict on update restrict; ";	
				  break;
					   
				case "POSTGRE":
					  $ls_sql= " alter table siv_articulo
								   add constraint fk_siv_articulo__siv_producto foreign key 
								      (codemp, codseg, codfami, codclase, codprod)
									  references siv_producto (codemp, codseg, codfami, codclase, codprod)
									  on delete restrict on update restrict; ";			  	
				  break;				  
			}
			if (!empty($ls_sql))
			{	
				 $li_row=$this->io_sql->execute($ls_sql);
				 if($li_row===false)
				 { 
					 $this->io_msg->message("Problemas al ejecutar Release 2008_4_48-05");
					 $lb_valido=false;
				 }
			}
		}// fin del if  
	    return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_48 
//---------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
     function uf_create_release_db_libre_V_2008_4_49()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_49
		//		   Access: public 
		//        Modulos: CFG
		//	  Description: 
		// Fecha Creacion: 09/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
		  	   $ls_sql= "  ALTER TABLE tepuy_ctrl_numero MODIFY COLUMN codusu char(30) ".
						          "              character SET utf8 COLLATE utf8_general_ci ";					
			 break;
			 
		   case "POSTGRE":
			   $ls_sql= "  ALTER TABLE tepuy_ctrl_numero ALTER COLUMN codusu TYPE char(30);";
														
			  break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas uf_cambiar_tipo_data Release 2008_4_49");
				$lb_valido=false;
			}
		}		
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_49
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_create_release_db_libre_V_2008_4_50()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_50
		//		   Access: public 
		//        Modulos: SOB
		//	  Description: 
		// Fecha Creacion: 12/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
				   $ls_sql= "  ALTER TABLE sob_tipounidad ADD COLUMN tipper char(1);";					
				 break;
				 
			   case "POSTGRE":
				  $ls_sql= "  ALTER TABLE sob_tipounidad ADD COLUMN tipper char(1);";																
				  break;  				  
	   }
	   if (!empty($ls_sql))
	   {	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas con el  Release 2008_4_50-01");
				$lb_valido=false;
			}
	   }
	   if ($lb_valido)
	   {
	   		switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN civinsact varchar(10);";					
					 break;
					 
				   case "POSTGRE":
					  $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN civinsact varchar(10);";																
					  break;  				  
		   }
		   if (!empty($ls_sql))
		   {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas con el  Release 2008_4_50-02");
					$lb_valido=false;
				}
		   }	
	   }// fin del if
	   if ($lb_valido)
	   {
	   		switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN civresact varchar(10);";					
					 break;
					 
				   case "POSTGRE":
					  $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN civresact varchar(10);";																
					  break;  				  
		   }
		   if (!empty($ls_sql))
		   {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas con el  Release 2008_4_50-03");
					$lb_valido=false;
				}
		   }	
	   }// fin del if	
	   if ($lb_valido)
	   {
	   	   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN nomresact varchar(254);";					
					 break;
					 
				   case "POSTGRE":
					  $ls_sql= "  ALTER TABLE sob_acta ADD COLUMN nomresact varchar(254);";																
					  break;  				  
		   }
		   if (!empty($ls_sql))
		   {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas con el  Release 2008_4_50-04");
					$lb_valido=false;
				}
		   }	
	   }// fin del if
	   if ($lb_valido)
	   {    
	        $ls_existe="";
	   		$ls_existe = $this->io_function_db->uf_select_constraint('sob_acta','rpc_supervisores__sob_acta_3');
			if ($ls_existe)
			{
				   switch($_SESSION["ls_gestor"])
				   {
						case "MYSQL":
							   $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_3;";					
							 break;
							 
						   case "POSTGRE":
							 $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_3;";																
							  break;  				  
				   }
				   if (!empty($ls_sql))
				   {	
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_msg->message("Problemas con el  Release 2008_4_50-05");
							$lb_valido=false;
						}
				   }	
			}// fin del if($ls_existe)
	   }// fin del if($lb_valido)
	   
	   if ($lb_valido)
	   {    
	        $ls_existe="";
	   		$ls_existe = $this->io_function_db->uf_select_constraint('sob_acta','rpc_supervisores__sob_acta_2');
			if ($ls_existe)
			{
				   switch($_SESSION["ls_gestor"])
				   {
						case "MYSQL":
							   $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_2;";					
							 break;
							 
						   case "POSTGRE":
							 $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_2;";																
							  break;  				  
				   }
				   if (!empty($ls_sql))
				   {	
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_msg->message("Problemas con el  Release 2008_4_50-06");
							$lb_valido=false;
						}
				   }	
			}// fin del if($ls_existe)
	   }// fin del if($lb_valido)	
	   
	   if ($lb_valido)
	   {    
	        $ls_existe="";
	   		$ls_existe = $this->io_function_db->uf_select_constraint('sob_acta','rpc_supervisores__sob_acta_1');
			if ($ls_existe)
			{
				   switch($_SESSION["ls_gestor"])
				   {
						case "MYSQL":
							   $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_1;";					
							 break;
							 
						   case "POSTGRE":
							 $ls_sql= " ALTER TABLE sob_acta DROP CONSTRAINT rpc_supervisores__sob_acta_1;";																
							  break;  				  
				   }
				   if (!empty($ls_sql))
				   {	
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_msg->message("Problemas con el  Release 2008_4_50-07");
							$lb_valido=false;
						}
				   }	
			}// fin del if($ls_existe)
	   }// fin del if($lb_valido)
	   if ($lb_valido)
	   {
	   	   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQL":
					   $ls_sql= "   INSERT INTO sob_tipounidad (codtun, nomtun) VALUES ('---','Por Defecto');
									INSERT INTO sob_unidad (codemp, coduni,codtun,nomuni) 
									       VALUES ('0001','---','---','Por Defecto');
									INSERT INTO tepuy_procedencias(procede,codsis,opeproc,desproc) 
									       VALUES ('SOBCON','SOB','CON','Contabilizar Contratos');
									INSERT INTO tepuy_procedencias(procede,codsis,opeproc,desproc)
									       VALUES ('SOBRPC','SOB','RPC','Contabilizar Contratos');";					
					 break;
					 
				   case "POSTGRE":
					 $ls_sql= "   INSERT INTO sob_tipounidad (codtun, nomtun) VALUES ('---','Por Defecto');
									INSERT INTO sob_unidad (codemp, coduni,codtun,nomuni) 
									       VALUES ('0001','---','---','Por Defecto');
									INSERT INTO tepuy_procedencias(procede,codsis,opeproc,desproc) 
									       VALUES ('SOBCON','SOB','CON','Contabilizar Contratos');
									INSERT INTO tepuy_procedencias(procede,codsis,opeproc,desproc)
									       VALUES ('SOBRPC','SOB','RPC','Contabilizar Contratos');";															
					  break;  				  
		   }
		   if (!empty($ls_sql))
		   {	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas con el  Release 2008_4_50-08");
					$lb_valido=false;
				}
		   }	
	   }// fin del if					
	   return $lb_valido;	
	} // end function uf_create_release_db_libre_V_2008_4_50
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
   function uf_create_release_db_libre_V_2008_4_51()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_release_db_libre_V_2008_4_51
		//		   Access: public 
		//        Modulos: 
		//	  Description: 
		// Fecha Creacion: 15/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   switch($_SESSION["ls_gestor"])
	   {
	   		case "MYSQL":
				   $ls_sql= "  ALTER TABLE tepuy_deducciones ADD COLUMN retaposol int2 DEFAULT 0;";					
				 break;
				 
			   case "POSTGRE":
				   $ls_sql= "  ALTER TABLE tepuy_deducciones ADD COLUMN retaposol int2 DEFAULT 0;";																
				  break;  				  
	   }
	   if (!empty($ls_sql))
	   {	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas con el  Release 2008_4_51");
				$lb_valido=false;
			}
	   }	   
	  return $lb_valido;	
   }//FIN DE uf_create_release_db_libre_V_2008_4_51() 
//------------------------------------------------------------------------------------------------------------------------------------
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Para cambiar el IVA de un porcentaje a otro
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_insert_cargos($as_codemp,$as_newcodcar,$as_dencar,$as_codestpro,$as_spgcuenta,$as_porcar,$as_estlibcom,$as_formula)
	{
		$lb_valido=true;
		$ls_sql="INSERT INTO tepuy_cargos(codemp, codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom, formula)".
				"     VALUES ('".$as_codemp."','".$as_newcodcar."','".$as_dencar."','".$as_codestpro."','".$as_spgcuenta."',".
				"             ".$as_porcar.",'".$as_estlibcom."','".$as_formula."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_insert_cargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;

	}

	function uf_procesar_cargos($as_codemp,$as_codart,$as_codcarant,$ad_dateaux,$as_codcaract,$as_codestpro,$as_spgcuenta)
	{
		$lb_valido=true;
		$ls_sql="INSERT INTO tepuy_histcargosarticulos(codemp, codart, codcarant, fecregcar, codcaract,".
				"                                       codestpro, spg_cuenta)".
				"     VALUES ('".$as_codemp."','".$as_codart."','".$as_codcarant."','".$ad_dateaux."',".
				"             '".$as_codcaract."','".$as_codestpro."','".$as_spgcuenta."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_create_release_db_libre_V_2_20_hist ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		$ls_sql="UPDATE siv_cargosarticulo".
				"   SET codcar='".$as_codcaract."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'".
				"   AND codcar='".$as_codcarant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_procesar_cargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}	

	function uf_procesar_cargosserv($as_codemp,$ls_codser,$as_codcarant,$ad_dateaux,$as_codcaract,$as_codestpro,$as_spgcuenta)
	{
		$lb_valido=true;
		$ls_sql="INSERT INTO tepuy_histcargosservicios(codemp, codser, codcarant, fecregcar, codcaract,".
				"                                       codestpro, spg_cuenta)".
				"     VALUES ('".$as_codemp."','".$ls_codser."','".$as_codcarant."','".$ad_dateaux."',".
				"             '".$as_codcaract."','".$as_codestpro."','".$as_spgcuenta."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_procesar_cargosserv_insert ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			return false;
		}
		$ls_sql="UPDATE soc_serviciocargo".
				"   SET codcar='".$as_codcaract."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codser='".$ls_codser."'".
				"   AND codcar='".$as_codcarant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_procesar_cargosserv ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
				
	}

    function uf_select_saf_seccion()
	{
	  $lb_existe=false;
	  $ls_sql="SELECT * ".
			  "  FROM saf_seccion ".
			  " WHERE codgru='---'".
			  "   AND codsubgru='---'".
			  "   AND codsec='---'";
	  $rs_data=$this->io_sql->select($ls_sql);
	   if($rs_data===false)
		{
			return $lb_existe;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				
			}
		}
		return $lb_existe;
	}
	
	function uf_select_config($as_codsis,$as_seccion,$as_entry)
	{
		$lb_existe=false;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT * ".
			    "  FROM tepuy_config ".
			    " WHERE codemp='".$ls_codemp."' ".
			    "   AND codsis='".$as_codsis."' ".
			    "   AND seccion='".$as_seccion."' ".
			    "   AND entry='".$as_entry."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("No existe la tabla.");
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}

	function uf_insert_config($as_codsis,$as_seccion,$as_entry)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$ls_sql="INSERT INTO tepuy_config(codemp, codsis, seccion, entry, type, value)".
				"     VALUES ('".$ls_codemp."','".$as_codsis."','".$as_seccion."','".$as_entry."','C','')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->release MÉTODO->uf_insert_config ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;

	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//--------------------------------------------------------------------------------------------------------------------------------	
    function uf_create_release_ajustar_programatica_cargos()
	{
	   $lb_valido=true;
	   $ls_sql="SELECT * ".
	   		   "  FROM tepuy_config ".
			   " WHERE codemp='0001' ".
			   "   AND codsis='SEP' ".
			   "   AND seccion='RELEASE' ".
			   "   AND entry='ESTRUC-PROGRAM2' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("No existe la tabla.");
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_msg->message("Ya se proceso la data.");
			}
			else
			{
				$ls_sql="INSERT INTO tepuy_config(codemp,codsis,seccion,entry,type,value)VALUES('0001','SEP','RELEASE','ESTRUC-PROGRAM2','','')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_msg->message(" CLASE->Release Metodo->uf_create_release_ajustar_programatica_cargos "); 
				}
				else
				{
					$ls_sql=" SELECT sep_solicitud.numsol,sep_cuentagasto.codestpro1,sep_cuentagasto.codestpro2, ".
							"        sep_cuentagasto.codestpro3,sep_cuentagasto.codestpro4,sep_cuentagasto.codestpro5, ".
							"        sep_cuentagasto.spg_cuenta ".
							" FROM   sep_solicitud, sep_cuentagasto, sep_solicitudcargos ".
							" WHERE  sep_solicitud.codemp=sep_cuentagasto.codemp AND sep_cuentagasto.codemp='0001' AND ".
							"        sep_solicitud.numsol=sep_cuentagasto.numsol AND ".
							"        sep_cuentagasto.numsol=sep_solicitudcargos.numsol  AND ".
							"        sep_cuentagasto.spg_cuenta=sep_solicitudcargos.spg_cuenta ".
							" ORDER BY sep_solicitud.numsol ";
					$rs_data=$this->io_sql->select($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$this->io_msg->message(" CLASE->Release Metodo->uf_create_release_ajustar_programatica_cargos "); 
					}
					else
					{
						while($row=$this->io_sql->fetch_row($rs_data))
						{
						  $ls_numsol=$row["numsol"];
						  $ls_codestpro1=$row["codestpro1"];
						  $ls_codestpro2=$row["codestpro2"];
						  $ls_codestpro3=$row["codestpro3"];
						  $ls_codestpro4=$row["codestpro4"];
						  $ls_codestpro5=$row["codestpro5"];
						  $ls_spg_cuenta=$row["spg_cuenta"];
						  $ls_sql=" UPDATE sep_solicitudcargos ".
								  " SET    codestpro1='".$ls_codestpro1."' , ".
								  "        codestpro2='".$ls_codestpro2."' , ".
								  "        codestpro3='".$ls_codestpro3."' , ".
								  "        codestpro4='".$ls_codestpro4."' , ".
								  "        codestpro5='".$ls_codestpro5."'   ".
								  " WHERE  numsol='".$ls_numsol."' AND spg_cuenta='".$ls_spg_cuenta."'";
						  $li_row=$this->io_sql->execute($ls_sql);
						  if($li_row===false)
						  {
							$lb_valido=false;
							$this->io_msg->message(" CLASE->Release Metodo->uf_create_release_ajustar_programatica_cargos "); 
						  }
						  else
						  {
							$lb_valido=true;
						  }//else
						}//while
						if($lb_valido)
						{
							$ls_sql=" UPDATE sep_solicitudcargos SET  monto = monret ";
							$rs_data=$this->io_sql->execute($ls_sql);
							if($rs_data===false)
							{ 
								$this->io_msg->message("Problemas al ejecutar ".$ls_sql);			 		 
								$lb_valido=false;
							}
						}
					}//else		 
				}//else
				if($lb_valido)
				{
					$this->io_sql->commit(); 
				}
				else
				{
					$this->io_sql->rollback();
				}
		  }//else
	  }//else
      return $lb_valido;	
    }// uf_create_release_ajustar_programatica_cargos
	//--------------------------------------------------------------------------------------------------------------------------------	

	//-------------------------------------------------------------------------------------------------------------------------------- 
    function uf_create_release_actualizar_ventanas_sep()
 	{
    $lb_valido=true;
    $ls_sql="SELECT * ".
         "  FROM tepuy_config ".
      " WHERE codemp='0001' ".
      "   AND codsis='SEP' ".
      "   AND seccion='RELEASE' ".
      "   AND entry='ACTUALIZAR-VENTANAS-SEP' ";
  $rs_data=$this->io_sql->select($ls_sql);
  if($rs_data===false)
  {
   $this->io_msg->message("No existe la tabla.");
   $lb_valido=false;
  }
  else
  {
   if($row=$this->io_sql->fetch_row($rs_data))
   {
    $this->io_msg->message("Ya se proceso la data.");
   }
   else
   {
    $ls_sql="INSERT INTO tepuy_config(codemp,codsis,seccion,entry,type,value)VALUES('0001','SEP','RELEASE','ACTUALIZAR-VENTANAS-SEP','','')";
    $li_row=$this->io_sql->execute($ls_sql);
    if($li_row===false)
    {
     $lb_valido=false;
     $this->io_msg->message(" CLASE->Release Metodo->uf_create_release_actualizar_ventanas_sep 1"); 
    }
    else
    {
      $ls_sql=" DELETE  ".
                          " FROM   sss_derechos_usuarios ".
                          " WHERE  codsis='SEP' AND codemp='0001' AND ".
           "        (nomven='tepuy_sep_p_solicitud.php' OR  ".
                          "         nomven='tepuy_sep_p_anulacion.php' OR  ". 
                          "         nomven='tepuy_sep_p_aprobacion.php' OR ".
                          "         nomven='tepuy_sep_r_solicitudes.php')";
     $li_row=$this->io_sql->execute($ls_sql);
     if($li_row===false)
     {
     $lb_valido=false;
     $this->io_msg->message(" CLASE->Release Metodo->uf_create_release_actualizar_ventanas_sep 2"); 
     }
     else
     {
     $ls_sql=" SELECT * ".
                            " FROM   sss_derechos_usuarios ".
                            " WHERE  codsis='SEP' AND codemp='0001' ";
     $rs_data=$this->io_sql->select($ls_sql);
     if($rs_data===false)
     {
      $lb_valido=false;
      $this->io_msg->message(" CLASE->Release Metodo->uf_create_release_actualizar_ventanas_sep 3"); 
     }
     else
     {
      while($row=$this->io_sql->fetch_row($rs_data))
      {
        $ls_codemp=$row["codemp"];
        $ls_codusu=$row["codusu"];
        $ls_codsis=$row["codsis"];
        $ls_nomven=trim($row["nomven"]);
        $ls_codintper=$row["codintper"];
        $li_visible=$row["visible"];
        $li_enabled=$row["enabled"];
        $li_leer=$row["leer"];
        $li_incluir=$row["incluir"];
        $li_cambiar=$row["cambiar"];
        $li_eliminar=$row["eliminar"];
        $li_imprimir=$row["imprimir"];
        $li_administrativo=$row["administrativo"];
        $li_anular=$row["anular"];
        $li_ejecutar=$row["ejecutar"];
        switch ($ls_nomven)
        {
        case "tepuy_sep_p_sol_eje_pre.php":
              $ls_nomven_actual="tepuy_sep_p_solicitud.php";  
        break;
       
        case "tepuy_sep_p_aprob_sep.php":
              $ls_nomven_actual="tepuy_sep_p_aprobacion.php";  
        break;
       
        case "tepuy_sep_p_anula_sep.php":
              $ls_nomven_actual="tepuy_sep_p_anulacion.php";  
        break;
       
        case "tepuy_sep_r_sol_eje_pre.php":
              $ls_nomven_actual="tepuy_sep_r_solicitudes.php";  
        break;
        
        }
        
        $ls_sql=" INSERT INTO sss_derechos_usuarios (codemp, codusu, codsis, nomven, codintper, visible,  ".
                "                                    enabled, leer, incluir, cambiar, eliminar, imprimir, ".
          "                                    administrativo, anular, ejecutar)                    ".
                " VALUES ('".$ls_codemp."','".$ls_codusu."','".$ls_codsis."','".$ls_nomven_actual."',     ".
          "         '".$ls_codintper."','".$li_visible."','".$li_enabled."','".$li_leer."',         ".
          "         '".$li_incluir."','".$li_cambiar."','".$li_eliminar."','".$li_imprimir."',      ".
          "         '".$li_administrativo."','".$li_anular."','".$li_ejecutar."')";
        $li_row=$this->io_sql->execute($ls_sql);
        if($li_row===false)
        {
       $lb_valido=false;
       $this->io_msg->message(" CLASE->Release Metodo->uf_create_release_actualizar_ventanas_sep 4"); 
        }
        else
        {
       $lb_valido=true;
        }//else
      }//while
      }//else 
       }//else 
    }//else
    if($lb_valido)
    {
     $this->io_sql->commit(); 
    }
    else
    {
     $this->io_sql->rollback();
    }
    }//else
   }//else 
      return $lb_valido; 
    }// uf_create_release_actualizar_ventanas_sep
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_create_release_insert_tepuy_procedencias()
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  Para insertar en la tabla  tepuy_procedencias
	/// Creado por: Jennifer Rivero
	//  Fehca de Creación: 14/05/2008  Ultima Fecha de Modificación
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	{
	   $lb_valido=true;
	   $ls_sql=" SELECT * ".
	   		   "  FROM tepuy_procedencias ".
			   "  WHERE procede='SIVCND' ".
			   "   AND codsis='SIV' ".
			   "   AND opeproc='CND' ";
			   
		  $rs_data=$this->io_sql->select($ls_sql);
		  if($rs_data===false)
		  {
		   $this->io_msg->message("No existe la tabla.");
		   $lb_valido=false;
		  }
		  else
			{
			   if($row=$this->io_sql->fetch_row($rs_data))
			   {
				$this->io_msg->message("Ya se proceso la data para tepuy_procedencias.");
			   }
			   else
			   {
				$ls_sql=" INSERT INTO tepuy_procedencias (procede, codsis, opeproc, desproc) ".
				        " VALUES ('SIVCND','SIV','CND','Contabilizacion de Nota de Despacho');";
				$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
					 $lb_valido=false;
					 $this->io_msg->message(" CLASE->Release Metodo->uf_create_release_insert_tepuy_procedencias"); 
					}
					else
					{  
				   	  $lb_valido=true;
					}
			    if($lb_valido)
					{
					 $this->io_sql->commit(); 
					}
					else
					{
					 $this->io_sql->rollback();
					}					
			   }   
	        }  
		return $lb_valido; 	  
	}//end uf_create_release_insert_tepuy_procedencias 

//--------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
   function uf_cambiar_tipo_data()
   {
   	   $lb_valido=true; 
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('tepuy_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `tepuy_cmp` MODIFY COLUMN `descripcion`          ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE tepuy_cmp ALTER descripcion TYPE text; ".
							" ALTER TABLE tepuy_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (1)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //--------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('tepuy_cmp_md','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `tepuy_cmp_md` MODIFY COLUMN `descripcion`       ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE tepuy_cmp_md ALTER descripcion TYPE text;                ".
							" ALTER TABLE tepuy_cmp_md ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (2)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //--------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scg_dt_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `scg_dt_cmp` MODIFY COLUMN `descripcion`          ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scg_dt_cmp ALTER descripcion TYPE text;                ".
							" ALTER TABLE scg_dt_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (3)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //-------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('scg_dtmp_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `scg_dtmp_cmp` MODIFY COLUMN `descripcion`         ".
				            "  LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE scg_dtmp_cmp ALTER descripcion TYPE text;                ".
							" ALTER TABLE scg_dtmp_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (4)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //-------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('spg_dt_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `spg_dt_cmp` MODIFY COLUMN `descripcion`          ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE spg_dt_cmp ALTER descripcion TYPE text;                ".
							" ALTER TABLE spg_dt_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (5)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //-------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('spg_dtmp_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `spg_dtmp_cmp` MODIFY COLUMN `descripcion`        ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE spg_dtmp_cmp ALTER descripcion TYPE text;                ".
							" ALTER TABLE spg_dtmp_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (6)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //--------------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('spi_dt_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `spi_dt_cmp` MODIFY COLUMN `descripcion`        ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE spi_dt_cmp ALTER descripcion TYPE text;                ".
							" ALTER TABLE spi_dt_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (7)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //--------------------------------------------------------------------------------------------------
	   $lb_existe="";
	   $lb_existe =$this->io_function_db->uf_select_type_columna('spi_dtmp_cmp','descripcion','text');
	   if (!$lb_existe)
	   {
	   		switch($_SESSION["ls_gestor"])
	  		{
				case "MYSQL":
				   $ls_sql= " ALTER TABLE `spi_dtmp_cmp` MODIFY COLUMN `descripcion`        ".
				            " LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; ";					
				   break;
				   
				case "POSTGRE":
				   $ls_sql= " ALTER TABLE spi_dtmp_cmp ALTER descripcion TYPE text; ".
							" ALTER TABLE spi_dtmp_cmp ALTER COLUMN descripcion SET STATISTICS -1; ";
				   								
					break;  				  
			}
			if (!empty($ls_sql))
			{	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("Problemas uf_cambiar_tipo_data (8)");
					$lb_valido=false;
				}
			}	   			
	   }
	  //--------------------------------------------------------------------------------------------------
	  return $lb_valido;
   }//fin uf_cambiar_tipo_data
//----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
   function insertar_activos()
	 {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: insertar_activos()
		//		   Access: public 
		//        Modulos: SAF
		//	  Description: 
		// Fecha Creacion: 10/10/2008								Fecha Ultima Modificacion : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_sql="";	
	   $valor=0;
	   switch($_SESSION["ls_gestor"])
	   {
			case "MYSQL":
		  	   $ls_sql= "  SELECT COUNT(*) AS valor FROM saf_grupo WHERE codgru='---' ";					
			 break;
			 
		   case "POSTGRE":
			    $ls_sql= "  SELECT COUNT(*) AS valor FROM saf_grupo WHERE codgru='---' "; 													
			  break;  				  
		}
		if (!empty($ls_sql))
		{	
			$li_row=$this->io_sql->select($ls_sql); 
			if($li_row===false)
			{ 
				$this->io_msg->message("Problemas con el  Insertar en Activos -01");
				$lb_valido=false;
			}
			else
			{ 			
			    if($row=$this->io_sql->fetch_row($li_row))
				{    
					$valor=$row["valor"]; 
				}
			}
		}
		
		if (($lb_valido)&&($valor==0))
		{     
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					     $ls_sql= "  INSERT INTO saf_grupo(codgru, dengru) VALUES ('---','---seleccione---'); ";					
					 break;
					 
				   case "POSTGRE":
						 $ls_sql= "  INSERT INTO saf_grupo(codgru, dengru) VALUES ('---','---seleccione---'); ";														
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el Insertar en Activos -02");
						$lb_valido=false;
					}
				}
		}// fin del if	
		if ($lb_valido)
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  SELECT COUNT(*) AS valor FROM saf_subgrupo WHERE codgru='---' AND codsubgru='---' ";					
					 break;
					 
				   case "POSTGRE":
						$ls_sql= "  SELECT COUNT(*) AS valor FROM saf_subgrupo WHERE codgru='---' AND codsubgru='---' ";														
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->select($ls_sql); 
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el Insertar en Activos -03");
						$lb_valido=false;
					}
					else
					{
						if($row=$this->io_sql->fetch_row($li_row))
						{
							$valor=$row["valor"];
						}
					}
				}
		}// fin del if
		if (($lb_valido)&&($valor==0))
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  INSERT INTO saf_subgrupo(codgru, codsubgru, densubgru) ".
                                "       VALUES ('---', '---', '---seleccione---'); ";					
					 break;
					 
				   case "POSTGRE":
					   $ls_sql= "  INSERT INTO saf_subgrupo(codgru, codsubgru, densubgru) ".
                                "       VALUES ('---', '---', '---seleccione---'); ";													
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el Insertar en Activos -04");
						$lb_valido=false;
					}
				}
		}// fin del if
		if ($lb_valido)
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  SELECT COUNT(*) AS valor FROM saf_seccion WHERE codgru='---' AND codsubgru='---' AND codsec='---' ";					
					 break;
					 
				   case "POSTGRE":
						$ls_sql= "  SELECT COUNT(*) AS valor FROM saf_seccion WHERE codgru='---' AND codsubgru='---' AND codsec='---'";														
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->select($ls_sql); 
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el  Insertar en Activos -05");
						$lb_valido=false;
					}
					else
					{
						if($row=$this->io_sql->fetch_row($li_row))
						{
							$valor=$row["valor"];
						}
					}
				}
		}// fin del if
		if (($lb_valido)&&($valor==0))
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  INSERT INTO saf_seccion(codgru, codsubgru, codsec, densec) ".
                                "       VALUES ('---', '---','---','---seleccione---');";					
					 break;
					 
				   case "POSTGRE":
						 $ls_sql= "  INSERT INTO saf_seccion(codgru, codsubgru, codsec, densec) ".
                                "       VALUES ('---', '---','---','---seleccione---');";															
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el  Insertar en Activos -06");
						$lb_valido=false;
					}
				}
		}// fin del if
		
		if ($lb_valido)
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  SELECT COUNT(*) AS valor FROM saf_item ".
					            "   WHERE codgru='---' ".
								"     AND codsubgru='---' ".
								"     AND codsec='---' ".
								"     AND codite='---' ";					
					 break;
					 
				   case "POSTGRE":
						$ls_sql= "  SELECT COUNT(*) AS valor FROM saf_item ".
					            "   WHERE codgru='---' ".
								"     AND codsubgru='---' ".
								"     AND codsec='---' ".
								"     AND codite='---' ";											
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->select($ls_sql); 
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el  Insertar en Activos -07");
						$lb_valido=false;
					}
					else
					{
						if($row=$this->io_sql->fetch_row($li_row))
						{
							$valor=$row["valor"];
						}
					}
				}
		}// fin del if
		if (($lb_valido)&&($valor==0))
		{
			   $ls_sql="";	
			   switch($_SESSION["ls_gestor"])
			   {
					case "MYSQL":
					   $ls_sql= "  INSERT INTO saf_item(codgru, codsubgru, codsec, codite, denite) ".
                                "   VALUES ('---', '---', '---', '---', '---seleccione---');";					
					 break;
					 
				   case "POSTGRE":
						$ls_sql= "  INSERT INTO saf_item(codgru, codsubgru, codsec, codite, denite) ".
                                "   VALUES ('---', '---', '---', '---', '---seleccione---');";															
					  break;  				  
				}
				if (!empty($ls_sql))
				{	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_msg->message("Problemas con el  Release Insertar en Activos -08");
						$lb_valido=false;
					}
				}
		}// fin del if
	   return $lb_valido;	
	} // end function insertar_activos()
//-----------------------------------------------------------------------------------------------------------------------------------
} // end class uf_check_update()
?>
