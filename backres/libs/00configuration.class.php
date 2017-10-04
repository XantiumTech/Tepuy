<?php

class configuration {

	var $filename;
	var $numbermi;
	var $ncurrent;
	var $numbermx;
	var $empresas;
	var $apertura;
	var $evalores = array();
	var $table = array();
	
	function configuration($f = "")
	{
		$this->filename = $f;

		$this->upTables();

		$this->empresas = array("hostname", "port", "database", "login", "password", "gestor", "width", "height", "logo");
	}
	
	function upTables()
	{
		// empresa
		$this->table[1][0] = "tepuy_banco_sigecof";
		$this->table[1][1] = "tepuy_cargos";
		$this->table[1][2] = "tepuy_catalogo_milco";

		$this->table[1][3] = "tepuy_cmp";		
//		$this->table['tepuy_cmp'][0] = "REPLACE(REPLACE(comprobante,'0000',''), CONCAT('CIERRE','-',YEAR(fecha)), CONCAT('APERTURA','-',YEAR(fecha)+1)) AS comprobante, DATE_ADD(fecha, INTERVAL 1 YEAR) AS fecha, REPLACE(descripcion, 'CIERRE', 'APERTURA') AS descripcion, REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede";
//		$this->table['tepuy_cmp'][0] = "REPLACE(REPLACE(comprobante,'0000',''), CONCAT('CIERRE','-',YEAR(fecha)), CONCAT('APERTURA','-',YEAR(fecha)+1)) AS comprobante, DATE_ADD(fecha, INTERVAL 1 YEAR) AS fecha, REPLACE(descripcion, 'CIERRE', 'APERTURA') AS descripcion, REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede";
		$this->table['tepuy_cmp'][0] = "DATE_ADD(fecha, INTERVAL 1 YEAR) AS fecha, IF(descripcion != '', 'APERTURA DE EJERCICIO', descripcion) AS descripcion, REPLACE(procede, 'SCGCMP', 'SCGAPR') AS procede";
		$this->table['tepuy_cmp'][1] = "procede = 'SCGCMP' AND descripcion LIKE '%APERTURA%'";

		$this->table[1][4] = "tepuy_cmp_md";
		$this->table[1][5] = "tepuy_comunidad";
		$this->table[1][6] = "tepuy_config";
		$this->table[1][7] = "tepuy_consolidacion";
		$this->table[1][8] = "tepuy_correo";
		$this->table[1][9] = "tepuy_ctrl_numero";
		$this->table[1][10] = "tepuy_deducciones";
		$this->table[1][11] = "tepuy_dt_expediente";
		$this->table[1][12] = "tepuy_dt_moneda";
		$this->table[1][13] = "tepuy_dt_poliza";
		$this->table[1][14] = "tepuy_dt_proc_cons";
		$this->table[1][15] = "tepuy_empresa";
		$this->table['tepuy_empresa'][0] = "REPLACE(m01, '0', '1') AS m01, REPLACE(m12, '1', '0') AS m12, DATE_ADD(periodo, INTERVAL 1 YEAR) AS periodo, REPLACE(estciespg, '1', '0') AS estciespg, REPLACE(estciespi, '1', '0') AS estciespi, REPLACE(estciescg, '1', '0') AS estciescg";
		$this->table['tepuy_empresa'][1] = "codemp = '0001'";		
		
		$this->table[1][16] = "tepuy_estados";
		$this->table[1][17] = "tepuy_expediente";
		$this->table[1][18] = "tepuy_fuentefinanciamiento";
		$this->table[1][19] = "tepuy_histcargosarticulos";
		$this->table[1][20] = "tepuy_histcargosservicios";
		$this->table[1][21] = "tepuy_moneda";
		$this->table[1][22] = "tepuy_municipio";
		$this->table[1][23] = "tepuy_pais";
		$this->table[1][24] = "tepuy_parroquia";
		$this->table[1][25] = "tepuy_plan_unico";
		$this->table[1][26] = "tepuy_plan_unico_re";
		$this->table[1][27] = "tepuy_poliza";
		$this->table[1][28] = "tepuy_proc_cons";
		$this->table[1][29] = "tepuy_procedencias";
		$this->table[1][30] = "tepuy_reportes";
		$this->table[1][31] = "tepuy_traspaso";
		$this->table[1][32] = "tepuy_unidad_tributaria";
		$this->table[1][33] = "tepuy_version";

		// otros conceptos
		$this->table[1][34] = "sep_conceptocargos";
		$this->table[1][35] = "sep_conceptos";
		$this->table[1][36] = "sep_tiposolicitud";
		// tipos de documentos		
		$this->table[1][37] = "cxp_documento";

		// plan de cuenta contable
		$this->table[2][0] = "scg_cuentas";
		
		$this->table[2][1] = "scg_dt_cmp";
		$this->table['scg_dt_cmp'][0] = "REPLACE(REPLACE(comprobante,'0000',''), CONCAT('CIERRE','-',YEAR(fecha)), CONCAT('APERTURA','-',YEAR(fecha)+1)) AS comprobante, DATE_ADD(fecha, INTERVAL 1 YEAR) AS fecha, IF(descripcion NOT LIKE '', CONCAT('APERTURA DEL EJERCICIO AÑO', ' ', YEAR(fecha)+1), CONCAT('APERTURA DEL EJERCICIO AÑO', ' ', YEAR(fecha)+1)) AS descripcion, REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede, REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede_doc";
		$this->table['scg_dt_cmp'][1] = "procede = 'SCGCIE'";
		
		$this->table[2][2] = "scg_pc_reporte";
//		$this->table[2][3] = "scg_saldos";

		// estructura presupuestaria
		$this->table[3][0] = "spg_dt_unidadadministrativa";
		$this->table[3][1] = "spg_ep1";
		$this->table[3][2] = "spg_ep2";
		$this->table[3][3] = "spg_ep3";
		$this->table[3][4] = "spg_ep4";
		$this->table[3][5] = "spg_ep5";
		$this->table[3][6] = "spg_ministerio_ua";
		$this->table[3][7] = "spg_unidadadministrativa";
		
		// plan de cuenta presupuestario
		$this->table[4][0] = "spg_cuenta_fuentefinanciamiento";
		$this->table[4][1] = "spg_cuentas";
		$this->table[4][2] = "spg_dt_fuentefinanciamiento";

		// proveedores
		$this->table[5][0] = "rpc_clasificacion";
		$this->table[5][1] = "rpc_docxprov";
		$this->table[5][2] = "rpc_especialidad";
		$this->table[5][3] = "rpc_espexprov";
		$this->table[5][4] = "rpc_proveedor";
		$this->table[5][5] = "rpc_proveedorsocios";
		$this->table[5][6] = "rpc_supervisores";
		$this->table[5][7] = "rpc_tipo_organizacion";

		// beneficiario
		$this->table[6][0] = "rpc_beneficiario";

		// inventario - articulos
		$this->table[7][0] = "siv_almacen";
		$this->table[7][1] = "siv_articulo";
		$this->table[7][2] = "siv_articuloalmacen";
		$this->table[7][3] = "siv_cargosarticulo";
		$this->table[7][4] = "siv_clase";
		$this->table[7][5] = "siv_componente";
		$this->table[7][6] = "siv_config";
		$this->table[7][7] = "siv_familia";
		$this->table[7][8] = "siv_producto";
		$this->table[7][9] = "siv_segmento";
		$this->table[7][10] = "siv_tipoarticulo";
		$this->table[7][11] = "siv_unidadmedida";
		
		// servicios
		$this->table[8][0] = "soc_serviciocargo";
		$this->table[8][1] = "soc_servicios";
		$this->table[8][2] = "soc_tiposervicio";

		// cuentas y saldos de banco
		$this->table[9][0] = "scb_agencias";
		$this->table[9][1] = "scb_banco";
		$this->table[9][2] = "scb_colocacion";
		$this->table[9][3] = "scb_concepto";
//		$this->table[9][4] = "scb_conciliacion";
//		$this->table['scb_conciliacion'][0] = "CONCAT('01', SUBSTRING(mesano,3,7)+1) AS mesano";
//		$this->table['scb_conciliacion'][1] = "SUBSTRING(mesano,1,2) = '12' AND estcon = 'C'";
		
		$this->table[9][4] = "scb_config";
		$this->table[9][5] = "scb_tipocuenta";
		$this->table[9][6] = "scb_ctabanco";
		$this->table[9][7] = "scb_movbco";
		$this->table['scb_movbco'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco'][1] = "estmov = 'C' AND estcon = 0";

//		$this->table['scb_movbco'][0] = "REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede_doc mesano AS mesano";
//		$this->table['scb_movbco'][1] = "((feccon='1900-01-01' AND DATE_FORMAT(fecmov,'%m-%d') <= '12-31') OR (DATE_FORMAT(feccon,'%m-%d') = '12-01') OR (DATE_FORMAT(fecmov,'%m-%d') <= '12-31' AND DATE_FORMAT(feccon,'%m-%d') > '12-01' )) AND estcon = 0";

		$this->table[9][8] = "scb_movbco_scg";
		$this->table['scb_movbco_scg'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_scg'][1] = "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";
		$this->table[9][9] = "scb_movbco_spg";
		$this->table['scb_movbco_spg'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spg'][1] = "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";

		$this->table[9][10] = "scb_movbco_spgop";
		$this->table['scb_movbco_spgop'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spgop'][1] = "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";
		$this->table[9][11] = "scb_movbco_spi";
		$this->table['scb_movbco_spi'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spi'][1] = "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";

		$this->table[9][12] = "scb_tipocuenta";
		
		// definiciones de viaticos
		$this->table[10][0] = "scv_categorias";
		$this->table[10][1] = "scv_ciudades";
		$this->table[10][2] = "scv_distancias";
		$this->table[10][3] = "scv_misiones";
		$this->table[10][4] = "scv_otrasasignaciones";
		$this->table[10][5] = "scv_regiones";
		$this->table[10][6] = "scv_rutas";
		$this->table[10][7] = "scv_tarifakms";
		$this->table[10][8] = "scv_tarifas";
		$this->table[10][9] = "scv_transportes";
		
		// nominas
		$this->table[11][0] = "sno_archivotxt";
		$this->table[11][1] = "sno_archivotxtcampo";
		$this->table[11][2] = "sno_asignacioncargo";
		$this->table[11][3] = "sno_banco";
		$this->table[11][4] = "sno_beneficiario";
		$this->table[11][5] = "sno_cargo";
		$this->table[11][6] = "sno_cestaticket";
		$this->table[11][7] = "sno_cestaticunidadadm";
		$this->table[11][8] = "sno_clasificaciondocente";
		$this->table[11][9] = "sno_clasificacionobrero";
		$this->table[11][10] = "sno_componente";
		$this->table[11][11] = "sno_concepto";
		$this->table[11][12] = "sno_conceptopersonal";
		$this->table[11][13] = "sno_conceptovacacion";
		$this->table[11][14] = "sno_constanciatrabajo";
		$this->table[11][15] = "sno_constante";
		$this->table[11][16] = "sno_constantepersonal";
		$this->table[11][17] = "sno_dedicacion";
		$this->table[11][18] = "sno_diaferiado";
//
///		$this->table[11][19] = "sno_dt_scg";
///		$this->table[11][20] = "sno_dt_spg";
		////////////////////////////////////
		$this->table[11][21] = "sno_escaladocente";
		$this->table[11][22] = "sno_estudiorealizado";
		$this->table[11][23] = "sno_familiar";
		$this->table[11][24] = "sno_fideicomiso";
		$this->table[11][25] = "sno_fideiconfigurable";
		$this->table[11][26] = "sno_fideiperiodo";
		$this->table[11][27] = "sno_grado";
		$this->table[11][28] = "sno_hasignacioncargo";
		$this->table[11][29] = "sno_hcargo";
		$this->table[11][30] = "sno_hclasificacionobrero";
		$this->table[11][31] = "sno_hconcepto";
		$this->table[11][32] = "sno_hconceptopersonal";
		$this->table[11][33] = "sno_hconceptovacacion";
		$this->table[11][34] = "sno_hconstante";
		$this->table[11][35] = "sno_hconstantepersonal";
		$this->table[11][36] = "sno_hgrado";
		$this->table[11][37] = "sno_hnomina";
		$this->table[11][38] = "sno_hperiodo";
		$this->table[11][39] = "sno_hpersonalnomina";
		$this->table[11][40] = "sno_hprenomina";
		$this->table[11][41] = "sno_hprestamos";
		$this->table[11][42] = "sno_hprestamosamortizado";
		$this->table[11][43] = "sno_hprestamosperiodo";
		$this->table[11][44] = "sno_hprimaconcepto";
		$this->table[11][45] = "sno_hprimagrado";
		$this->table[11][46] = "sno_hproyecto";
		$this->table[11][47] = "sno_hproyectopersonal";
		$this->table[11][48] = "sno_hresumen";
		$this->table[11][49] = "sno_hsalida";
		$this->table[11][50] = "sno_hsubnomina";
		$this->table[11][51] = "sno_htabulador";
		$this->table[11][52] = "sno_htipoprestamo";
		$this->table[11][53] = "sno_hunidadadmin";
		$this->table[11][54] = "sno_hvacacpersonal";
		$this->table[11][55] = "sno_ipasme_afiliado";
		$this->table[11][56] = "sno_ipasme_beneficiario";
		$this->table[11][57] = "sno_ipasme_dependencias";
		$this->table[11][58] = "sno_metodobanco";
		
		$this->table[11][59] = "sno_nomina";
		$this->table['sno_nomina'][0] = "anocurnom+1 AS anocurnom, DATE_ADD(fecininom, INTERVAL 1 YEAR) AS fecininom, IF(peractnom != '001', '001', peractnom) AS peractnom";
		$this->table['sno_nomina'][1] = "codemp = codemp";

//		$this->table[11][19] = "sno_dt_scg";
//		$this->table[11][20] = "sno_dt_spg";
		
		$this->table[11][60] = "sno_periodo";
		$this->table['sno_periodo'][0] = "IF(totper != 0, 0, totper) AS totper, REPLACE(cerper, 1, 0) AS cerper, REPLACE(conper, 1, 0) AS conper, REPLACE(apoconper, 1, 0) AS apoconper, REPLACE(fidconper, 1, 0) AS fidconper, DATE_ADD(fecdesper, INTERVAL 1 YEAR) AS fecdesper, DATE_ADD(fechasper, INTERVAL 1 YEAR) AS fechasper";
//		$this->table['sno_periodo'][1] = "YEAR(fecdesper) > 1900";
		$this->table['sno_periodo'][1] = "fecdesper = fecdesper";
		
		$this->table[11][61] = "sno_permiso";
		$this->table[11][62] = "sno_personal";
		$this->table[11][63] = "sno_personalisr";
		$this->table[11][64] = "sno_personalnomina";
		
		$this->table[11][65] = "sno_personalpension";

		$this->table[11][66] = "sno_prestamos";
		$this->table[11][67] = "sno_prestamosamortizado";
		$this->table[11][68] = "sno_prestamosperiodo";
		$this->table[11][69] = "sno_primaconcepto";
		$this->table[11][70] = "sno_primagrado";
		$this->table[11][71] = "sno_profesion";
		$this->table[11][72] = "sno_programacionreporte";
		$this->table[11][73] = "sno_proyecto";
		$this->table[11][74] = "sno_proyectopersonal";
		$this->table[11][75] = "sno_rango";
		$this->table[11][76] = "sno_resumen";
		$this->table[11][77] = "sno_salida";
		$this->table[11][78] = "sno_subnomina";
		$this->table[11][79] = "sno_sueldominimo";
		$this->table[11][80] = "sno_tablavacacion";
		$this->table[11][81] = "sno_tablavacperiodo";
		$this->table[11][82] = "sno_tabulador";
		$this->table[11][83] = "sno_thasignacioncargo";
		$this->table[11][84] = "sno_thcargo";
		$this->table[11][85] = "sno_thclasificacionobrero";
		$this->table[11][86] = "sno_thconcepto";
		$this->table[11][87] = "sno_thconceptopersonal";
		$this->table[11][88] = "sno_thconceptovacacion";
		$this->table[11][89] = "sno_thconstante";
		$this->table[11][90] = "sno_thconstantepersonal";
		$this->table[11][91] = "sno_thgrado";
		$this->table[11][92] = "sno_thnomina";
		$this->table[11][93] = "sno_thperiodo";
		$this->table[11][94] = "sno_thpersonalnomina";
		$this->table[11][95] = "sno_thprenomina";
		$this->table[11][96] = "sno_thprestamos";
		$this->table[11][97] = "sno_thprestamosamortizado";
		$this->table[11][98] = "sno_thprestamosperiodo";
		$this->table[11][99] = "sno_thprimaconcepto";
		$this->table[11][100] = "sno_thprimagrado";
		$this->table[11][101] = "sno_thproyecto";
		$this->table[11][102] = "sno_thproyectopersonal";
		$this->table[11][103] = "sno_thresumen";
		$this->table[11][104] = "sno_thsalida";
		$this->table[11][105] = "sno_thsubnomina";
		$this->table[11][106] = "sno_thtabulador";
		$this->table[11][107] = "sno_thtipoprestamo";
		$this->table[11][108] = "sno_thunidadadmin";
		$this->table[11][109] = "sno_thvacacpersonal";
		$this->table[11][110] = "sno_tipopersonal";
		$this->table[11][111] = "sno_tipoprestamo";
		$this->table[11][112] = "sno_trabajoanterior";
		$this->table[11][113] = "sno_ubicacionfisica";
		$this->table[11][114] = "sno_unidadadmin";
		$this->table[11][115] = "sno_vacacpersonal";
		$this->table[11][116] = "sno_encargaduria";
		
		//personal
		$this->table[12][0] = "srh_accidentes";
		$this->table[12][1] = "srh_area";
		$this->table[12][2] = "srh_cargos";
		$this->table[12][3] = "srh_ciudades";
		$this->table[12][4] = "srh_contratos";
		$this->table[12][5] = "srh_cualitativos";
		$this->table[12][6] = "srh_defcontrato";
		$this->table[12][7] = "srh_documentos";
		$this->table[12][8] = "srh_dt_accidentes";
		$this->table[12][9] = "srh_dt_evaluacionpasantias";
		$this->table[12][10] = "srh_dt_evaluacionperfil";
		$this->table[12][11] = "srh_dt_reportes";
		$this->table[12][12] = "srh_dt_resultadopruebas";
		$this->table[12][13] = "srh_dt_seleccion";
		$this->table[12][14] = "srh_dt_solicitudadiestramientos";
		$this->table[12][15] = "srh_dt_solicitudempleo";
		$this->table[12][16] = "srh_enfermedades";
		$this->table[12][17] = "srh_evaluacionmetas";
		$this->table[12][18] = "srh_evaluacionpasantias";
		$this->table[12][19] = "srh_evaluacionperfil";
		$this->table[12][20] = "srh_grupomovimientos";
		$this->table[12][21] = "srh_metaspersonal";
		$this->table[12][22] = "srh_movimientopersonal";
		$this->table[12][23] = "srh_nivelseleccion";
		$this->table[12][24] = "srh_pasantes";
		$this->table[12][25] = "srh_perfil";
		$this->table[12][26] = "srh_profesion";
		$this->table[12][27] = "srh_proveedoradiestramientos";
		$this->table[12][28] = "srh_pruebas";
		$this->table[12][29] = "srh_reportes";
		$this->table[12][30] = "srh_requerimientos";
		$this->table[12][31] = "srh_resultadopruebas";
		$this->table[12][32] = "srh_seleccion";
		$this->table[12][33] = "srh_solicitudadiestramientos";
		$this->table[12][34] = "srh_solicitudempleo";
		$this->table[12][35] = "srh_tipoaccidentes";
		$this->table[12][36] = "srh_tipocontratos";
		$this->table[12][37] = "srh_tipodocumentos";
		$this->table[12][38] = "srh_tipoenfermedades";
		$this->table[12][39] = "srh_tipomovimientos";
		$this->table[12][40] = "srh_tiporequerimientos";
		$this->table[12][41] = "srh_unidades";

		$this->table[12][42] = "srh_departamento";

		// por defecto
		$this->table[13][0] = "sss_grupos";
		$this->table[13][1] = "sss_sistemas";
		$this->table[13][2] = "sss_sistemas_ventanas";
		$this->table[13][3] = "sss_usuarios";
		$this->table[13][4] = "sss_usuarios_en_grupos";
		$this->table[13][5] = "sss_permisos_internos_grupos";
		$this->table[13][6] = "sss_permisos_internos";
		$this->table[13][7] = "sss_derechos_usuarios";
		$this->table[13][8] = "sss_derechos_grupos";
		$this->table[13][9] = "tepuy_c_retenciones";

//		$this->table[10][10] = "people"; // example
//		$this->table[10][11] = "calculo_conceptospersonal"; // vista1
//		$this->table[10][12] = "calculo_personal"; // vista1
		
		$this->apertura = "SELECT * FROM tepuy_cmp WHERE comprobante like '%CIERRE%' AND procede = 'SCGCIE'"; // comprobar apertura

	}
	
	function retTables()
	{
		return $this->table;
	}

	function retApertura()
	{
		return $this->apertura;
	}

	function configure()
	{
//		$perms = fileperms ($filename);

		$contents = implode('', file($this->filename));

		preg_match_all("|(.*)=(.*);|U", $contents, $matches);

		$initform = "<form action=\"?\" method=post>
					<table class=config cellspacing=0 cellpadding=4>
					<tr align=center><th width=50%><i>Variable</i></th><th width=50%><i>Valor</i></th></tr>\n\n";

		$maximo = 0;
		$minimo = 9999999999999;
		foreach($matches[1] as $k => $v)
		{
			if (ereg("<?php", $v))
			{
				$v = str_replace("<?php", "", $v);
			}
			$number = preg_replace("/[^0-9]/", '', $v);
			if ( $number > $maximo ) $maximo = $number;
			if ( $number < $minimo ) $minimo = $number;
			
			$vm = array('$' . 'empresa', '[', '"', ']');
			$vm = trim ( str_replace($vm, "", $v) );
			$item = preg_replace("/[^0-9]/", '', $vm);
			$vnam = preg_replace("/[^A-Za-z]/", '', $vm);
			
			$value = trim( htmlentities($matches[2][$k]) );
			$value = str_replace("&quot;", "", $value); 
			
			$this->evalores["$vnam"][$item] = $value ;

//			$initform .= "  <tr><td>$v</td><td><input type=text size=40 name=vars[$vm] value=\"$value\"></td></tr>\n\n";
			$initform .= "  <tr><td>$vnam</td><td><input type=text size=40 name=$vm value=\"$value - $item\"></td></tr>\n\n";
		}
		$this->numbermi = $minimo;
		$this->ncurrent = $maximo;

		$maximo ++;   // nuevo sistema escrito en archivo
		$this->numbermx = $maximo;

		$initform .= "
</table>
<p align=right><input type=submit  style=\"width:200\"  class=button></p>
<input type=hidden name=go value=saveconfig>
</form>\n\n";

		return $initform ;
	}

	function currentSystemNumber()
	{
		return ( $this->ncurrent );
	}
	
	function eValores()
	{
		for ($i = 0; $i < count( $this->empresas ); $i++ )
		{
			$name = $this->empresas[ $i ];
			for ($j = 1; $j <= count( $this->evalores[ $name ] ); $j++ )
			{
				echo $name . "[$j] : " . $this->evalores[ $name ][ $j ] . "<br>";
			}
		}
	}

	function saveconfig()
	{
		global $vars;
		
		$contents = implode('',file($this->filename));

		preg_match_all("|(.*)=(.*);|U", $contents, $matches );

		foreach($matches[1] as $k => $v)
		{
			$contents = str_replace($matches[1][$k]."=".$matches[2][$k].";", $matches[1][$k] . ' = "' . stripcslashes($vars[$k]) . '" ;', $contents);
		}

		if (is_writable($this->filename))
		{
		    if (!$handle = fopen($this->filename, 'w'))
			{
		         echo "Imposible escribir en el archivo ($this->filename)";
		         exit;
		    }
		    if (fwrite($handle, $contents) === FALSE) {
		       echo "Imposible escribir el contenido en el archivo ($this->filename)";
		       exit;
		    }		    
		    fclose($handle);
		    return TRUE;
		} else {
		    return FALSE;
		}
	}
	
	function savenew($company)
	{
		////// lectura del archivo php
		$contents = implode('', file($this->filename));
		$contents = str_replace("?>", "", $contents);
		if (is_writable($this->filename))
		{
		    if (!$handle = fopen($this->filename, 'w'))
			{
		         echo "Imposible escribir en el archivo ($this->filename)";
		         exit;
		    }
		    if (fwrite($handle, $contents) === FALSE) {
		       echo "Imposible escribir el contenido en el archivo ($this->filename)";
		       exit;
		    }		    
		    fclose($handle);
		}
		///// quitar el termino del script php
		$entr = "\n";
		$inic = $entr . '$' . 'empresa';
		for ( $i = 0; $i < count( $this->empresas ); $i++ )
		{
			$emp = $this->empresas[$i];
			
			$cont .= $inic;
			$cont .= '["' . $emp . '"][' . $this->numbermx . ']	';
			if ( $emp == "port" || $emp == "logo")  $cont .= '	';
			$cont .= '= "' . stripcslashes( $company[$i] ) . '";';
		}		
		$cont .= $entr . "?>";
		
		if (is_writable($this->filename))
		{
		    if (!$handle = fopen($this->filename, 'a'))
			{
		         echo "Imposible agregar escritura en el archivo ($this->filename)";
		         exit;
		    }
		    if (fwrite($handle, $cont) === FALSE) {
		       echo "Imposible escribir el contenido en el archivo ($this->filename)";
		       exit;
		    }		    
		    fclose($handle);
		    return TRUE;
		} else {
		    return FALSE;
		}
	}
	
	function savenew22($host, $dbms, $port, $user, $pass, $data, $anch, $alto, $logo)
	{
		////// lectura del archivo php
		$contents = implode('', file($this->filename));
		$contents = str_replace("?>", "", $contents);
		if (is_writable($this->filename))
		{
		    if (!$handle = fopen($this->filename, 'w'))
			{
		         echo "Imposible escribir en el archivo ($this->filename)";
		         exit;
		    }
		    if (fwrite($handle, $contents) === FALSE) {
		       echo "Imposible escribir el contenido en el archivo ($this->filename)";
		       exit;
		    }		    
		    fclose($handle);
		}
		///// quitar el termino del script php
		$entr = "\n";
		
		$inic = $entr . '$' . 'empresa';

		$cont .= $inic . '["hostname"][' . $this->numbermx . ']	= "' . stripcslashes($host) . '";';

		$cont .= $inic . '["port"][' . $this->numbermx . '] = "' . stripcslashes($port) . '";';
		
		$cont .= $inic . '["database"][' . $this->numbermx . ']	= "' . stripcslashes($data) . '";';
		
		$cont .= $inic . '["login"][' . $this->numbermx . '] = "' . stripcslashes($user) . '";';
		
		$cont .= $inic . '["password"][' . $this->numbermx . ']	= "' . stripcslashes($pass) . '";';
		
		$cont .= $inic . '["gestor"][' . $this->numbermx . '] = "' . stripcslashes($dbms) . '";';
		
		$cont .= $inic . '["width"][' . $this->numbermx . '] = "' . stripcslashes($anch) . '";';
		
		$cont .= $inic . '["height"][' . $this->numbermx . '] = "' . stripcslashes($alto) . '";';
		
		$cont .= $inic . '["logo"][' . $this->numbermx . '] = "' . stripcslashes($logo) . '";';
		
		$cont .= $entr . "?>";
		
		if (is_writable($this->filename))
		{
		    if (!$handle = fopen($this->filename, 'a'))
			{
		         echo "Imposible agregar escritura en el archivo ($this->filename)";
		         exit;
		    }
		    if (fwrite($handle, $cont) === FALSE) {
		       echo "Imposible escribir el contenido en el archivo ($this->filename)";
		       exit;
		    }		    
		    fclose($handle);
		    return TRUE;
		} else {
		    return FALSE;
		}
	}
	
}
?>
