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
		$cond = " WHERE ";

		// empresa
		$this->table[1][0] = "tepuy_banco_sigecof";
		$this->table[1][1] = "tepuy_catalogo_milco";

		$this->table[1][2] = "tepuy_cmp_md";
		$this->table[1][3] = "tepuy_comunidad";
//////////////////////////////////////////
		$this->table[1][4] = "tepuy_config";
		$this->table[1][5] = "tepuy_consolidacion";
		$this->table[1][6] = "tepuy_correo";
		$this->table[1][7] = "tepuy_ctrl_numero";
		$this->table[1][8] = "tepuy_deducciones";
		$this->table[1][9] = "tepuy_dt_expediente";
		$this->table[1][10] = "tepuy_dt_moneda";
		$this->table[1][11] = "tepuy_dt_poliza";
		$this->table[1][12] = "tepuy_dt_proc_cons";
		$this->table[1][13] = "tepuy_empresa";
		$this->table['tepuy_empresa'][0] = "REPLACE(m01, '0', '1') AS m01, REPLACE(m12, '1', '0') AS m12, DATE_ADD(periodo, INTERVAL 1 YEAR) AS periodo, REPLACE(estciespg, '1', '0') AS estciespg, REPLACE(estciespi, '1', '0') AS estciespi, REPLACE(estciescg, '1', '0') AS estciescg";
		$this->table['tepuy_empresa'][1] = $cond . "codemp = '0001'";
		
		$this->table[1][14] = "tepuy_estados";
		$this->table[1][15] = "tepuy_expediente";
		$this->table[1][16] = "tepuy_fuentefinanciamiento";
		$this->table[1][17] = "tepuy_histcargosarticulos";
		$this->table[1][18] = "tepuy_histcargosservicios";
		$this->table[1][19] = "tepuy_moneda";
		$this->table[1][20] = "tepuy_municipio";
		$this->table[1][21] = "tepuy_pais";
		$this->table[1][22] = "tepuy_parroquia";
		$this->table[1][23] = "tepuy_plan_unico";
		$this->table[1][24] = "tepuy_plan_unico_re";
		$this->table[1][25] = "tepuy_poliza";
		$this->table[1][26] = "tepuy_proc_cons";
		$this->table[1][27] = "tepuy_procedencias";
		$this->table[1][28] = "tepuy_reportes";
		$this->table[1][29] = "tepuy_traspaso";
		$this->table[1][30] = "tepuy_unidad_tributaria";
		$this->table[1][31] = "tepuy_version";
		$this->table[1][32] = "tepuy_c_retenciones";

		// otros conceptos
		$this->table[1][33] = "sep_conceptocargos";
		$this->table[1][34] = "sep_conceptos";
		$this->table[1][35] = "sep_tiposolicitud";
		// tipos de documentos		
		$this->table[1][36] = "cxp_documento";
		$this->table[1][37] = "cxp_clasificador_rd";

		// unidades
		$this->table[1][38] = "spg_ministerio_ua";
		
		// seguridad
		$this->table[1][39] = "sss_derechos_grupos";
		$this->table[1][40] = "sss_derechos_usuarios";
		$this->table[1][41] = "sss_eventos";
		$this->table[1][42] = "sss_grupos";
		$this->table[1][43] = "sss_permisos_internos";
		$this->table[1][44] = "sss_permisos_internos_grupos";
		$this->table[1][45] = "sss_sistemas";
		$this->table[1][46] = "sss_sistemas_ventanas";
		$this->table[1][47] = "sss_usuarios";
		$this->table[1][48] = "sss_usuarios_en_grupos";

		//operaciones		
		$this->table[1][49] = "spg_operaciones";
		$this->table[1][48] = "spi_operaciones";


		// plan de cuenta contable
		$this->table[2][0] = "scg_cuentas";
//		$this->table['scg_cuentas'][0] = "codemp AS codemp";
//		$this->table['scg_cuentas'][1] = "A WHERE (SELECT (SUM(X.haber_mes) - SUM(X.debe_mes)) FROM scg_saldos X WHERE A.sc_cuenta = X.sc_cuenta AND A.codemp = X.codemp GROUP BY X.sc_cuenta) != 0";
		
		// saldos contables
		$this->table[3][0] = "tepuy_cmp";		
		$this->table['tepuy_cmp'][0] = "DATE_ADD(fecha, INTERVAL 1 YEAR) AS fecha, IF(descripcion != '', 'APERTURA DE EJERCICIO', descripcion) AS descripcion, REPLACE(procede, 'SCGCMP', 'SCGAPR') AS procede";
		$this->table['tepuy_cmp'][1] = $cond . "procede = 'SCGCMP' AND descripcion LIKE '%APERTURA%'";

		$this->table[3][1] = "scg_saldos";

		$this->table['scg_saldos'][0] = "DATE_ADD(B.periodo, INTERVAL 1 YEAR) AS fecsal,

CASE WHEN A.sc_cuenta LIKE '5%' OR A.sc_cuenta LIKE '6%' THEN 0

 WHEN A.sc_cuenta = '31401010102001' THEN ( SELECT IF (
(SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) > (SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta) , ABS((SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) + ( SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta)) , 0) )

 WHEN A.sc_cuenta LIKE '3%' AND A.sc_cuenta <> '31401010102001' THEN ( SELECT IF( SUM(X.debe_mes) > SUM(X.haber_mes), ABS(SUM(X.debe_mes) - SUM(X.haber_mes)), 0 ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = B.codemp AND X.fecsal < CONCAT(YEAR(B.periodo), '-12-31') GROUP BY X.sc_cuenta )

 WHEN A.sc_cuenta LIKE '125%' THEN ( SELECT IF( SUM(X.haber_mes) > SUM(X.debe_mes), SUM(X.debe_mes) - SUM(X.haber_mes), 0 ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = B.codemp AND X.fecsal < CONCAT(YEAR(B.periodo), '-12-31') GROUP BY X.sc_cuenta )

 ELSE IF(SUM(A.debe_mes) > SUM(A.haber_mes), ABS(SUM(A.debe_mes) - SUM(A.haber_mes)), 0) 

END AS debe_mes,

CASE WHEN A.sc_cuenta LIKE '5%' OR A.sc_cuenta LIKE '6%' THEN 0 

 WHEN A.sc_cuenta = '31401010102001' THEN ( SELECT IF (
(SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) < (SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta) , abs((SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) + ( SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta)) , 0) )

 WHEN A.sc_cuenta LIKE '3%' AND A.sc_cuenta <> '31401010102001' THEN ( SELECT IF( SUM(X.haber_mes) > SUM(X.debe_mes), ABS(SUM(X.haber_mes) - SUM(X.debe_mes)), 0 ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = B.codemp AND X.fecsal < CONCAT(YEAR(B.periodo), '-12-31') GROUP BY X.sc_cuenta )

 WHEN A.sc_cuenta LIKE '125%' THEN ( SELECT IF( SUM(X.debe_mes) > SUM(X.haber_mes), SUM(X.debe_mes) - SUM(X.haber_mes), 0 ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = B.codemp AND X.fecsal < CONCAT(YEAR(B.periodo), '-12-31') GROUP BY X.sc_cuenta )

 ELSE IF(SUM(A.haber_mes) > SUM(A.debe_mes), ABS(SUM(A.haber_mes) - SUM(A.debe_mes)), 0)

END AS haber_mes";

		$this->table['scg_saldos'][1] = "A, tepuy_empresa B WHERE A.codemp = B.codemp AND (SELECT (SUM(X.haber_mes) - SUM(X.debe_mes)) FROM scg_saldos X WHERE A.sc_cuenta = X.sc_cuenta AND A.codemp = X.codemp GROUP BY X.sc_cuenta) != 0 GROUP BY A.sc_cuenta, A.codemp";

		$this->table[3][2] = "scg_pc_reporte";

		$this->table[3][3] = "scg_dt_cmp";

		$this->table['scg_dt_cmp'][0] = "CONCAT(YEAR(C.periodo), '-12-31') AS fecha, IF(A.descripcion != '', 'APERTURA DE EJERCICIO', A.descripcion) AS descripcion, IF(A.procede != '', 'SCGAPR', A.procede) AS procede, IF(A.comprobante != '', '0000000APERTURA', A.comprobante) AS comprobante, IF(A.procede_doc != '', 'SCGAPR', A.procede_doc) AS procede_doc, IF(A.documento != '', '0000000APERTURA', A.documento) AS documento, IF(A.codban != '', '---', A.codban) AS codban, IF(A.ctaban != '', '-------------------------', A.ctaban) AS ctaban,

CASE

 WHEN A.sc_cuenta = '31401010102001' THEN ( SELECT IF (
(SELECT ABS(SUM( X.haber_mes ) - SUM( X.debe_mes )) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) > (SELECT ABS(SUM( X.debe_mes ) - SUM( X.haber_mes )) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta) , 'H' , 'D') )

 WHEN A.sc_cuenta LIKE '125%' THEN

( SELECT IF( SUM(X.haber_mes) > SUM(X.debe_mes), 'D', 'H') FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = C.codemp AND X.fecsal < CONCAT(YEAR(C.periodo), '-12-31') GROUP BY X.sc_cuenta )

 ELSE

( SELECT IF( SUM(X.debe_mes) > SUM(X.haber_mes), 'D', 'H' ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = C.codemp AND X.fecsal < CONCAT(YEAR(C.periodo), '-12-31') GROUP BY X.sc_cuenta )

END AS debhab,

CASE

 WHEN A.sc_cuenta = '31401010102001' THEN ( SELECT IF (
(SELECT SUM( X.haber_mes ) - SUM( X.debe_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) > (SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta) , abs((SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '50000000000000' GROUP BY X.sc_cuenta ) + ( SELECT SUM( X.debe_mes ) - SUM( X.haber_mes ) FROM scg_saldos X WHERE X.sc_cuenta = '60000000000000' GROUP BY X.sc_cuenta)) , 0) )

 WHEN A.sc_cuenta LIKE '3%' AND A.sc_cuenta <> '31401010102001' THEN ( SELECT ABS(SUM(X.debe_mes) - SUM(X.haber_mes)) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = C.codemp AND X.fecsal < CONCAT(YEAR(C.periodo), '-12-31') GROUP BY X.sc_cuenta )

 WHEN A.sc_cuenta NOT LIKE '3%' AND A.sc_cuenta LIKE '125%' THEN 

( SELECT IF( SUM(X.haber_mes) > SUM(X.debe_mes), SUM(X.debe_mes) - SUM(X.haber_mes), SUM(X.haber_mes) - SUM(X.debe_mes) ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = B.codemp AND X.fecsal < CONCAT(YEAR(C.periodo), '-12-31') GROUP BY X.sc_cuenta )

 ELSE

( SELECT IF( SUM(X.debe_mes) > SUM(X.haber_mes), ABS(SUM(X.debe_mes) - SUM(X.haber_mes)), ABS(SUM(X.haber_mes) - SUM(X.debe_mes)) ) FROM scg_saldos X WHERE X.sc_cuenta = A.sc_cuenta AND A.codemp = X.codemp AND X.codemp = C.codemp AND X.fecsal < CONCAT(YEAR(C.periodo), '-12-31') GROUP BY X.sc_cuenta )


END AS monto";

		$this->table['scg_dt_cmp'][1] = "A, scg_cuentas B, tepuy_empresa C WHERE B.nivel = 7 AND A.codemp = B.codemp AND B.codemp = C.codemp AND A.sc_cuenta NOT LIKE '5%' AND A.sc_cuenta NOT LIKE '6%' AND A.sc_cuenta = B.sc_cuenta AND (SELECT (SUM(X.haber_mes) - SUM(X.debe_mes)) FROM scg_saldos X WHERE A.sc_cuenta = X.sc_cuenta AND A.codemp = X.codemp GROUP BY X.sc_cuenta) != 0 GROUP BY A.sc_cuenta, A.codemp";

		// estructura presupuestaria
		$this->table[4][0] = "spg_dt_unidadadministrativa";
		$this->table[4][1] = "spg_ep1";
		$this->table[4][2] = "spg_ep2";
		$this->table[4][3] = "spg_ep3";
		$this->table[4][4] = "spg_ep4";
		$this->table[4][5] = "spg_ep5";
		$this->table[4][6] = "spg_unidadadministrativa";

		// plan de cuenta presupuestario
		$this->table[5][0] = "spg_cuenta_fuentefinanciamiento";
		$this->table[5][1] = "spg_cuentas";
		$this->table[5][2] = "spg_dt_fuentefinanciamiento";

		// proveedores
		$this->table[6][0] = "rpc_clasificacion";
		$this->table[6][1] = "rpc_docxprov";
		$this->table[6][2] = "rpc_especialidad";
		$this->table[6][3] = "rpc_espexprov";
		$this->table[6][4] = "rpc_proveedor";
		$this->table[6][5] = "rpc_proveedorsocios";
		$this->table[6][6] = "rpc_supervisores";
		$this->table[6][7] = "rpc_tipo_organizacion";

		// beneficiario
		$this->table[7][0] = "rpc_beneficiario";

		// inventario - articulos
		$this->table[8][0] = "siv_almacen";
		$this->table[8][1] = "siv_articulo";
		$this->table[8][2] = "siv_articuloalmacen";
		$this->table[8][3] = "siv_cargosarticulo";
		$this->table[8][4] = "siv_clase";
		$this->table[8][5] = "siv_componente";
		$this->table[8][6] = "siv_config";
		$this->table[8][7] = "siv_familia";
		$this->table[8][8] = "siv_producto";
		$this->table[8][9] = "siv_segmento";
		$this->table[8][10] = "siv_tipoarticulo";
		$this->table[8][11] = "siv_unidadmedida";
		
		// servicios
//		$this->table[9][0] = "soc_serviciocargo";
		$this->table[9][0] = "soc_servicios";
		$this->table[9][1] = "soc_tiposervicio";
		//soc clausulas
		$this->table[9][2] = "soc_clausulas";
		$this->table[9][3] = "soc_modalidadclausulas";



		// cuentas y saldos de banco
		$this->table[10][0] = "scb_agencias";
		$this->table[10][1] = "scb_banco";
		$this->table[10][2] = "scb_colocacion";
		$this->table[10][3] = "scb_concepto";
//		$this->table[10][4] = "scb_conciliacion";
//		$this->table['scb_conciliacion'][0] = "CONCAT('01', SUBSTRING(mesano,3,7)+1) AS mesano";
//		$this->table['scb_conciliacion'][1] = $cond . "SUBSTRING(mesano,1,2) = '12' AND estcon = 'C'";
		
		$this->table[10][4] = "scb_config";
		$this->table[10][5] = "scb_tipocuenta";
		$this->table[10][6] = "scb_ctabanco";
		$this->table[10][7] = "scb_movbco";
		$this->table['scb_movbco'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco'][1] = $cond . "estmov = 'C' AND estcon = 0";

//		$this->table['scb_movbco'][0] = "REPLACE(procede, 'SCGCIE', 'SCGAPR') AS procede_doc mesano AS mesano";
//		$this->table['scb_movbco'][1] = $cond . "((feccon='1900-01-01' AND DATE_FORMAT(fecmov,'%m-%d') <= '12-31') OR (DATE_FORMAT(feccon,'%m-%d') = '12-01') OR (DATE_FORMAT(fecmov,'%m-%d') <= '12-31' AND DATE_FORMAT(feccon,'%m-%d') > '12-01' )) AND estcon = 0";

		$this->table[10][8] = "scb_movbco_scg";
		$this->table['scb_movbco_scg'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_scg'][1] = $cond . "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";
		$this->table[10][9] = "scb_movbco_spg";
		$this->table['scb_movbco_spg'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spg'][1] = $cond . "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";

		$this->table[10][10] = "scb_movbco_spgop";
		$this->table['scb_movbco_spgop'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spgop'][1] = $cond . "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";
		$this->table[10][11] = "scb_movbco_spi";
		$this->table['scb_movbco_spi'][0] = "REPLACE(estmov, 'C', 'L') AS estmov";
		$this->table['scb_movbco_spi'][1] = $cond . "estmov = 'C' AND numdoc IN (SELECT numdoc FROM scb_movbco WHERE estmov = 'C' AND estcon = 0)";

		$this->table[10][12] = "scb_tipocuenta";
		
		// definiciones de viaticos
		$this->table[11][0] = "scv_categorias";
		$this->table[11][1] = "scv_ciudades";
		$this->table[11][2] = "scv_distancias";
		$this->table[11][3] = "scv_misiones";
		$this->table[11][4] = "scv_otrasasignaciones";
		$this->table[11][5] = "scv_regiones";
		$this->table[11][6] = "scv_rutas";
		$this->table[11][7] = "scv_tarifakms";
		$this->table[11][8] = "scv_tarifas";
		$this->table[11][9] = "scv_transportes";
		
		// nominas
		$this->table[12][0] = "sno_archivotxt";
		$this->table[12][1] = "sno_archivotxtcampo";
		$this->table[12][2] = "sno_asignacioncargo";
		$this->table[12][3] = "sno_banco";
		$this->table[12][4] = "sno_beneficiario";
		$this->table[12][5] = "sno_cargo";
		$this->table[12][6] = "sno_cestaticket";
		$this->table[12][7] = "sno_cestaticunidadadm";
		$this->table[12][8] = "sno_clasificaciondocente";
		$this->table[12][9] = "sno_clasificacionobrero";
		$this->table[12][10] = "sno_componente";
		$this->table[12][11] = "sno_concepto";
		$this->table[12][12] = "sno_conceptopersonal";
		$this->table[12][13] = "sno_conceptovacacion";
		$this->table[12][14] = "sno_constanciatrabajo";
		$this->table[12][15] = "sno_constante";
		$this->table[12][16] = "sno_constantepersonal";
		$this->table[12][17] = "sno_dedicacion";
		$this->table[12][18] = "sno_diaferiado";
///		$this->table[12][19] = "sno_dt_scg";
///		$this->table[12][20] = "sno_dt_spg";
		////////////////////////////////////
		$this->table[12][20] = "sno_escaladocente";
		$this->table[12][21] = "sno_estudiorealizado";
		$this->table[12][22] = "sno_familiar";
		$this->table[12][23] = "sno_fideicomiso";
		$this->table[12][24] = "sno_fideiconfigurable";
		$this->table[12][25] = "sno_fideiperiodo";
		$this->table[12][26] = "sno_grado";
		$this->table[12][27] = "sno_hasignacioncargo";
		$this->table[12][28] = "sno_hcargo";
		$this->table[12][29] = "sno_hclasificacionobrero";
		$this->table[12][30] = "sno_hconcepto";
		$this->table[12][31] = "sno_hconceptopersonal";
		$this->table[12][32] = "sno_hconceptovacacion";
		$this->table[12][33] = "sno_hconstante";
		$this->table[12][34] = "sno_hconstantepersonal";
		$this->table[12][35] = "sno_hgrado";
		$this->table[12][36] = "sno_hnomina";
		$this->table[12][37] = "sno_hperiodo";
		$this->table[12][38] = "sno_hpersonalnomina";
		$this->table[12][39] = "sno_hprenomina";
		$this->table[12][40] = "sno_hprestamos";
		$this->table[12][41] = "sno_hprestamosamortizado";
		$this->table[12][42] = "sno_hprestamosperiodo";
		$this->table[12][43] = "sno_hprimaconcepto";
		$this->table[12][44] = "sno_hprimagrado";
		$this->table[12][45] = "sno_hproyecto";
		$this->table[12][46] = "sno_hproyectopersonal";
		$this->table[12][47] = "sno_hresumen";
		$this->table[12][48] = "sno_hsalida";
		$this->table[12][49] = "sno_hsubnomina";
		$this->table[12][50] = "sno_htabulador";
		$this->table[12][51] = "sno_htipoprestamo";
		$this->table[12][52] = "sno_hunidadadmin";
		$this->table[12][53] = "sno_hvacacpersonal";
		$this->table[12][54] = "sno_ipasme_afiliado";
		$this->table[12][55] = "sno_ipasme_beneficiario";
		$this->table[12][56] = "sno_ipasme_dependencias";
		$this->table[12][57] = "sno_metodobanco";
		
		$this->table[12][58] = "sno_nomina";
		$this->table['sno_nomina'][0] = "anocurnom+1 AS anocurnom, DATE_ADD(fecininom, INTERVAL 1 YEAR) AS fecininom, IF(peractnom != '001', '001', peractnom) AS peractnom";
		$this->table['sno_nomina'][1] = $cond . "codemp = codemp";
		$this->table[12][59] = "sno_periodo";
		$this->table['sno_periodo'][0] = "IF(totper != 0, 0, totper) AS totper, REPLACE(cerper, 1, 0) AS cerper, REPLACE(conper, 1, 0) AS conper, REPLACE(apoconper, 1, 0) AS apoconper, REPLACE(fidconper, 1, 0) AS fidconper, DATE_ADD(fecdesper, INTERVAL 1 YEAR) AS fecdesper, DATE_ADD(fechasper, INTERVAL 1 YEAR) AS fechasper";
//		$this->table['sno_periodo'][1] = $cond . "YEAR(fecdesper) > 1900";
		$this->table['sno_periodo'][1] = $cond . "fecdesper = fecdesper";
		
		$this->table[12][60] = "sno_permiso";
		$this->table[12][61] = "sno_personal";
		$this->table[12][62] = "sno_personalisr";
		$this->table[12][63] = "sno_personalnomina";
		
		$this->table[12][64] = "sno_personalpension";

		$this->table[12][65] = "sno_prestamos";
		$this->table[12][66] = "sno_prestamosamortizado";
		$this->table[12][67] = "sno_prestamosperiodo";
		$this->table[12][68] = "sno_primaconcepto";
		$this->table[12][69] = "sno_primagrado";
		$this->table[12][70] = "sno_profesion";
		$this->table[12][71] = "sno_programacionreporte";
		$this->table[12][72] = "sno_proyecto";
		$this->table[12][73] = "sno_proyectopersonal";
		$this->table[12][74] = "sno_rango";
		$this->table[12][75] = "sno_resumen";
		$this->table[12][76] = "sno_salida";
		$this->table[12][77] = "sno_subnomina";
		$this->table[12][78] = "sno_sueldominimo";
		$this->table[12][79] = "sno_tablavacacion";
		$this->table[12][80] = "sno_tablavacperiodo";
		$this->table[12][81] = "sno_tabulador";
		$this->table[12][82] = "sno_thasignacioncargo";
		$this->table[12][83] = "sno_thcargo";
		$this->table[12][84] = "sno_thclasificacionobrero";
		$this->table[12][85] = "sno_thconcepto";
		$this->table[12][86] = "sno_thconceptopersonal";
		$this->table[12][87] = "sno_thconceptovacacion";
		$this->table[12][88] = "sno_thconstante";
		$this->table[12][89] = "sno_thconstantepersonal";
		$this->table[12][90] = "sno_thgrado";
		$this->table[12][91] = "sno_thnomina";
		$this->table[12][92] = "sno_thperiodo";
		$this->table[12][93] = "sno_thpersonalnomina";
		$this->table[12][94] = "sno_thprenomina";
		$this->table[12][95] = "sno_thprestamos";
		$this->table[12][96] = "sno_thprestamosamortizado";
		$this->table[12][97] = "sno_thprestamosperiodo";
		$this->table[12][98] = "sno_thprimaconcepto";
		$this->table[12][99] = "sno_thprimagrado";
		$this->table[12][100] = "sno_thproyecto";
		$this->table[12][101] = "sno_thproyectopersonal";
		$this->table[12][102] = "sno_thresumen";
		$this->table[12][103] = "sno_thsalida";
		$this->table[12][104] = "sno_thsubnomina";
		$this->table[12][105] = "sno_thtabulador";
		$this->table[12][106] = "sno_thtipoprestamo";
		$this->table[12][107] = "sno_thunidadadmin";
		$this->table[12][108] = "sno_thvacacpersonal";
		$this->table[12][109] = "sno_tipopersonal";
		$this->table[12][110] = "sno_tipopersonalsss";
		$this->table[12][111] = "sno_tipoprestamo";
		$this->table[12][112] = "sno_trabajoanterior";
		$this->table[12][113] = "sno_ubicacionfisica";
		$this->table[12][114] = "sno_unidadadmin";
		$this->table[12][115] = "sno_vacacpersonal";
		$this->table[12][116] = "sno_encargaduria";
		
		//personal
		$this->table[13][0] = "srh_accidentes";
		$this->table[13][1] = "srh_area";
		$this->table[13][2] = "srh_cargos";
		$this->table[13][3] = "srh_ciudades";
		$this->table[13][4] = "srh_contratos";
		$this->table[13][5] = "srh_cualitativos";
		$this->table[13][6] = "srh_defcontrato";
		$this->table[13][7] = "srh_documentos";
		$this->table[13][8] = "srh_dt_accidentes";
		$this->table[13][9] = "srh_dt_evaluacionpasantias";
		$this->table[13][10] = "srh_dt_evaluacionperfil";
		$this->table[13][11] = "srh_dt_reportes";
		$this->table[13][12] = "srh_dt_resultadopruebas";
		$this->table[13][13] = "srh_dt_seleccion";
		$this->table[13][14] = "srh_dt_solicitudadiestramientos";
		$this->table[13][15] = "srh_dt_solicitudempleo";
		$this->table[13][16] = "srh_enfermedades";
		$this->table[13][17] = "srh_evaluacionmetas";
		$this->table[13][18] = "srh_evaluacionpasantias";
		$this->table[13][19] = "srh_evaluacionperfil";
		$this->table[13][20] = "srh_grupomovimientos";
		$this->table[13][21] = "srh_metaspersonal";
		$this->table[13][22] = "srh_movimientopersonal";
		$this->table[13][23] = "srh_nivelseleccion";
		$this->table[13][24] = "srh_organigrama";
		$this->table[13][25] = "srh_pasantes";
		$this->table[13][26] = "srh_perfil";
		$this->table[13][27] = "srh_profesion";
		$this->table[13][28] = "srh_proveedoradiestramientos";
		$this->table[13][29] = "srh_pruebas";
		$this->table[13][30] = "srh_reportes";
		$this->table[13][31] = "srh_requerimientos";
		$this->table[13][32] = "srh_resultadopruebas";
		$this->table[13][33] = "srh_seleccion";
		$this->table[13][34] = "srh_solicitudadiestramientos";
		$this->table[13][35] = "srh_solicitudempleo";
		$this->table[13][36] = "srh_tipoaccidentes";
		$this->table[13][37] = "srh_tipocontratos";
		$this->table[13][38] = "srh_tipodocumentos";
		$this->table[13][39] = "srh_tipoenfermedades";
		$this->table[13][40] = "srh_tipomovimientos";
		$this->table[13][41] = "srh_tiporequerimientos";
		$this->table[13][42] = "srh_unidades";
		$this->table[13][43] = "srh_departamento";


		
		$this->apertura = "SELECT * FROM tepuy_cmp WHERE comprobante LIKE '%CIERRE%' AND procede = 'SCGCIE'"; // comprobar apertura

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
	
	function verificarDato($var, $dat)
	{
		$enc = 0;
		for ($j = 1; $j <= count( $this->evalores[ $var ] ); $j++ )
		{
			if ( $dat == $this->evalores[ $var ][ $j ] )
			{
				$enc = 1;
				break;
			}
		}
		return $enc;
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
