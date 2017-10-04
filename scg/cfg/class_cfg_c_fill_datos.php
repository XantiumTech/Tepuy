<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : class_cfg_c_fill_datos                                                         
// Description : Esta clase llena por defectos los registros basicos de arranque del sistema
////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_cfg_c_fill_datos
{
    //conexion	
	var $sqlca;   
    var $is_msg_error;
	var $dts_empresa; 
	var $obj="";
	var $io_sql;
	var $io_function;	
	var $io_fecha;
	function class_cfg_c_fill_datos()
	{
		$this->io_function   = new class_funciones() ;
		$io_siginc           = new tepuy_include();
		$io_connect          = $io_siginc->uf_conectar();
		$this->io_sql        = new class_sql($io_connect);		
		$this->obj           = new class_datastore();
		$this->dts_solicitud = new class_datastore();
	}

function uf_main_fill()
{
   $lb_valido=$this->uf_fill_datos_tepuy();
   if(!$lb_valido){	return false;}
   $lb_valido=$this->uf_fill_datos_rpc();
   if(!$lb_valido){	return false;}
   $lb_valido=$this->uf_fill_datos_spg();
   if(!$lb_valido){ return false;}	   
   $lb_valido=$this->uf_fill_datos_soc();
   if(!$lb_valido){return false;}
   $lb_valido=$this->uf_fill_datos_spi();	   
   if(!$lb_valido){ return false;}
   $lb_valido=$this->uf_fill_datos_sistemas();
   if(!$lb_valido){	return false;}	   
   $lb_valido=$this->uf_fill_datos_seguridad();
   if(!$lb_valido){	return false;}	   
   $lb_valido=$this->uf_fill_datos_procedencia();
   if(!$lb_valido){	return false;}	   
   $lb_valido=$this->uf_fill_datos_saf();
   if(!$lb_valido){	return false;}
   $lb_valido=$this->uf_fill_datos_banco();
   if(!$lb_valido){	return false;}
   $lb_valido=$this->uf_fill_datos_nomina();
   if(!$lb_valido){ return false;}
   return true;
}//end function uf_main_fill()

function uf_fill_datos_tepuy()
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_tepuy
//	 Returns:  datos insertados
//Description:  Llena por defectos tagblas basicas
/////////////////////////////////////////////////////////////////////////////
	$ls_sql  = "INSERT INTO tepuy_pais (codpai,despai) VALUES ('---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Pais,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO tepuy_estados (codpai,codest,desest) VALUES ('---','---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Estado,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO tepuy_municipio (codpai,codest,codmun,denmun) VALUES ('---','---','---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Municipio,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}		
	$ls_sql  = "INSERT INTO tepuy_parroquia (codpai,codest,codmun,codpar,denpar) VALUES ('---','---','---','---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Parroquia,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO tepuy_moneda (codmon,denmon,imamon,codpai,tascam,estmonpri) VALUES ('---','----seleccione----','------','---',0,0)"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Moneda,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}		
	$ls_sql  = "INSERT INTO soc_modalidadclausulas (codemp, codtipmod, denmodcla) VALUES  ('0001','--','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Modalidad de Clausula,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO cxp_clasificador_rd (codcla, dencla) VALUES ('--', '---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if ($li_result===false)
	   {
		 print "Error al insertar Clasificador de Recepciones de Documentos,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 return false;
	   }
	$ls_sql  = "INSERT INTO tepuy_banco_sigecof (codbansig, denbansig) VALUES ('---', '---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if ($li_result===false)
	   {
		 print "Error al insertar Clasificador de Recepciones de Documentos,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 return false;
	   }	
	
	return true;
}//end function uf_fill_datos_tepuy()

function uf_fill_datos_rpc()
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_geografica
//	 Returns:  datos insertados
//Description:  Llena por defectos las operaciones de ingresos
/////////////////////////////////////////////////////////////////////////////
	$ls_sql  = "INSERT INTO rpc_tipo_organizacion (codtipoorg,dentipoorg) VALUES ('--','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar tipo de organizacion,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}		
	$ls_sql  = "INSERT INTO rpc_especialidad (codesp,denesp) VALUES ('---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Especialidad,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}		
	$ls_sql  = " INSERT INTO rpc_proveedor (codemp,cod_pro,nompro,dirpro,sc_cuenta,estpro,estcon,estaso,inspector,codban,codmon,codtipoorg,codesp,codbansig) ".
				 " VALUES ('0001','----------','Ninguno','-',' ',1,0,0,0,'---','---','--','---','---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Proveedor,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = " INSERT INTO rpc_beneficiario (codemp,ced_bene,codpai,codest,codmun,codpar,codtipcta,rifben,nombene,apebene,dirbene,telbene,celbene,email,sc_cuenta,codban,ctaban,codbansig) ".
				 " VALUES ('0001','----------','---','---','---','---',' ',' ',' Beneficiario Nulo',' ',' ',' ',' ',' ',' ',' ',' ','---')"; 			
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Beneficiario,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	return true;
}//end function uf_fill_datos_spg()
	
function uf_fill_datos_spg()
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_spg
//	 Returns:  datos insertados
//Description:  Llena por defectos las operaciones de gasto
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_spg_operacion('AAP','ASIENTO DE APERTURA',			1,0,0,0,0,0,0,1);
   if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_spg_operacion('AU ','AUMENTO DE PARTIDA',			0,1,0,0,0,0,0,1);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('DI ','DISMINUCION DE PARTIDA',		0,0,1,0,0,0,0,1);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('PC ','PRE-COMPROMISO',		        0,0,0,1,0,0,0,0);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('CS ','COMPROMISO SIMPLE',			0,0,0,0,1,0,0,0);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('CG ','COMPROMISO Y  GASTO CAUSADO',	0,0,0,0,1,1,0,0);
   if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_spg_operacion('GC ','GASTO CAUSADO',				0,0,0,0,0,1,0,0);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('CP ','GASTO CAUSADO Y PAGO',		0,0,0,0,0,1,1,0);
   if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_spg_operacion('PG ','PAGO',						0,0,0,0,0,0,1,0);
   if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_spg_operacion('CCP','COMPROMISO,CAUSADO Y PAGADO', 0,0,0,0,1,1,1,0);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spg_fuente_financiamiento('--','---seleccione---','---');
   if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_spg_fuente_financiamiento('01', 'Ingresos Ordinarios', '');
   if(!$lb_valido){	return false;}		
   	$lb_valido=$this->uf_insert_spg_fuente_financiamiento('02', 'Ingresos Extraordinarios', '');
   if(!$lb_valido){	return false;}		
   	$lb_valido=$this->uf_insert_spg_fuente_financiamiento('03', 'FIDES', 'Ninguna');
   if(!$lb_valido){	return false;}		
   	$lb_valido=$this->uf_insert_spg_fuente_financiamiento('04', 'LAEE', '');
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spg_unidad_administrativa('0001','----------','NINGUNA',0,'-','-','-','-','-');
   if(!$lb_valido){	return false;}
   return true;
}//end function uf_fill_datos_spg()
	
function uf_fill_datos_soc()
{
///////////////////////////////////////////////////////////////////////////////////
//	 Function:  uf_fill_datos_soc
//	  Returns:  datos insertados
//Description:  Llena por defectos los datos necesarios para el  modulo de compras.
/////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql  = "INSERT INTO soc_ordencompra(codemp, numordcom,estcondat, cod_pro, codmon, codfuefin, codtipmod, fecordcom, estsegcom, porsegcom, monsegcom, forpagcom, estcom, diaplacom, concom, obscom, monsubtotbie, monsubtotser, monsubtot, monbasimp, monimp, mondes, montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, lugentdir, monant, estlugcom, tascamordcom, montotdiv, estapro, fecaprord, codusuapr, numpolcon,coduniadm,numanacot)".
	         "VALUES('0001', '000000000000000','-','----------', '---', '01', '--', '2006-03-13', 0, 0.00, 0.0000, 's1', 1, 0, 's1', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 's1', '000', 's1', '000', '', '', 0.0000, 0, 0.0000, 0.0000, 0, '2006-06-29', 'admin', '0','----------','-')";
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   print "Error al Insertar Orden de Compra Por Defecto,".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   print $this->io_sql->message;
	   return false;
	 } 
return true;
}//end de la Funcion uf_fill_datos_soc.

function uf_fill_datos_spi()
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_spi
//	 Returns:  datos insertados
//Description:  Llena por defectos las operaciones de ingresos
/////////////////////////////////////////////////////////////////////////////
   $lb_valido=$this->uf_insert_spi_operacion('PRE','Previsto            ',1,0,0,0,0,0,1);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spi_operacion('AU ','Aumento             ',0,1,0,0,0,0,1);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spi_operacion('DI ','Disminucion         ',0,0,1,0,0,0,1);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spi_operacion('DEV','Devengado           ',0,0,0,1,0,0,0);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spi_operacion('COB','Cobrado             ',0,0,0,0,1,0,0);
   if(!$lb_valido){	return false;}		
   $lb_valido=$this->uf_insert_spi_operacion('DC ','Devengado y Cobrado ',0,0,0,1,1,0,0);
   if(!$lb_valido){	return false;}		
   return true;
}//end function uf_fill_datos_spi()

function uf_fill_datos_sistemas()
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_sistemas
//	 Returns:  datos insertados
//Description:  Llena por defectos los sistemas del tepuy
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_sss_sistema('APR','Apertura');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('CFG','Configuración');		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('CIE','Cierre');				
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('CXP','Cuentas por Pagar');						
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SAF','Sistema de Activos Fijos');								
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('RPC','Sistema de Registro de Contratista/Proveedores y Beneficiario');										
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SCB','Sistema de Banco');										
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SCG','Sistema de Contabilidad General');														
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SCF','Sistema de Contabilidad Fiscal');																
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SPG','Sistema de Gasto');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SPI','Sistema de Ingresos');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SEP','Sistema de Ejecución Presupuestaria');																		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SIV','Sistema de Inventario');																				
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SOC','Sistema de Ordenes de Compra');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SNO','Sistema de Nomina');		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SNR','Sistema de Nomina Recursos Humano');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SOB','Sistema de Obras');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SSS','Sistema de Seguridad');		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('MIS','Módulo Integrador tepuy');		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('SCV','Sistema de Control de Viáticos');		
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_sistema('INS','Instala');		
	if(!$lb_valido){	return false;}	
	return true;
}//end function uf_fill_datos_sistemas()
	
function uf_fill_datos_seguridad()
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_seguridad
//	 Returns:  datos insertados
//Description:  Llena por defectos los sistemas del tepuy
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_sss_eventos('INSERT','Insertar Registro');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_eventos('DELETE','Eliminar Registro');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_eventos('UPDATE','Actualizar Registro');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_eventos('PROCESS','Procesamiento');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_sss_eventos('REPORT','Ejecución de Reporte');
	if(!$lb_valido){	return false;}	
	return true;
}//end function uf_fill_datos_seguridad()

function uf_fill_datos_procedencia()
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_procedencia
//	 Returns:  datos insertados
//Description:  Llena por defectos las procedencias
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_procedencia('SCGCMP','SCG','CMP','Comprobante Contable');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCGAMP','SCG','AMP','Anulacion - Comprobante Contable');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCGAPR','SCG','APR','Comprobante de Apertura Contable');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCGCIE','SCG','CIE','Comprobante de Cierre Contable');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGCMP','SPG','CMP','Comprobante Presupuesto de Gastos');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGAMP','SPG','AMP','Anulacion - Comprobante Presupuesto de Gastos');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGAPR','SPG','APR','Apertura de cuentas Presupuesto de Gastos');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGREC','SPG','REC','Rectificaciones al presupuesto');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGTRA','SPG','TRA','Traspasos');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGINS','SPG','INS','Insubsistencias');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPGCRA','SPG','CRA','Credito adicional');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPICMP','SPI','CMP','Comprobante Presupuesto de Ingreso');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPIAMP','SPI','AMP','Anulacion - Comprobante Presupuesto de Ingreso');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPIAPR','SPI','APR','Apertura de cuentas Presupuesto de Ingreso');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPIAUM','SPI','AUM','Aumento de Ingreso');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SPIDIS','SPI','DIS','Disminucion de Ingreso');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCCPC','SOC','CPC','Precontabilizacion de Orden de Compra');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCCPA','SOC','CPA','Reverso-Precontabilizacion de Orden de Compra');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCCOC','SOC','COC','Contabilizacion de Orden de Compra');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCAOC','SOC','AOC','Anulacion de Orden de Compras');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCSPC','SOC','SPC','Precontabilizacion de Orden de Servicios');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCSPA','SOC','SPA','Reverso-Precontabilizacion de Orden de Servicios');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCCOS','SOC','COS','Contabilizacion de Orden de Servicios');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCAOS','SOC','AOS','Anulacion de Orden de Servicios');
	if(!$lb_valido){	return false;}			
	$lb_valido=$this->uf_insert_procedencia('SOCCND','SOC','CND','Nota de Despacho');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOCAND','SOC','AND','Anulacion de Nota de Despacho');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('CXPRCD','CXP','RCD','Recepciones de Documento');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('CXPSOP','CXP','SOP','Solicitud de Pago');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('CXPAOP','CXP','AOP','Anulacion Solicitud de Pago');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBCH','SCB','BCH','Banco - emision de cheque');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBAH','SCB','BAH','Banco - anulacion de cheque');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_procedencia('SCBOPD','SCB','OPD','Banco - Orden de Pago Directa');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBDP','SCB','BDP','Banco - deposito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBAP','SCB','BAP','Banco - anulacion de deposito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBRE','SCB','BRE','Banco - retiro');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBAE','SCB','BAE','Banco - anulacion de retiro');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBND','SCB','BND','Banco - Nota de Debito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBAD','SCB','BAD','Banco - anulacion de Nota de Debito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBNC','SCB','BNC','Banco - Nota de Credito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBBAC','SCB','BAC','Banco - anulacion de Nota de Credito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCCH','SCB','CCH','Colocacion - emision de cheque');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCAH','SCB','CAH','Colocacion - anulacion de cheque');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCDP','SCB','CDP','Colocacion - deposito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCAP','SCB','CAP','Colocacion - anulacion de deposito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCRE','SCB','CRE','Colocacion - retiro');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCAE','SCB','CAE','Colocacion - anulacion de retiro');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCND','SCB','CND','Colocacion - Nota de Debito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCAD','SCB','CAD','Colocacion - anulacion de Nota de Debito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCNC','SCB','CNC','Colocacion - Nota de Credito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBCAC','SCB','CAC','Colocacion - anulacion de Nota de Credito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBJCD','SCB','JCD','Caja x Debe');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBJAD','SCB','JAD','Anulacion Caja x Debe');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBJCH','SCB','JCH','Caja x Haber');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SCBJAH','SCB','JAH','Anulacion Caja x Haber');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SNOCNO','SNO','CNO','Nomina - Contabilizacion');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOBRCO','SOB','RCO','Registro de Contrato de Obras');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SOBACO','SOB','ACO','Anulacion de Contrato de Obras');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFCIN','SAF','CIN','Contabilizar Incorporaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFAIN','SAF','AIN','Anular Incorporaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFCDN','SAF','CDN','Contabilizar Desincorporaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFADN','SAF','ADN','Anular Desincorporaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFCAJ','SAF','CAJ','Contabilizar Ajustes');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFAAJ','SAF','AAJ','Anular Ajustes');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFCDP','SAF','CDP','Contabilizar Depreciaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFADP','SAF','ADP','Anular Depreciaciones');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SEPSPC','SEP','SPC','Precontabilizacion de Solicitud de Ejecucion Presupuestaria');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SEPSPA','SEP','SPA','Reverso-Precontabilizacion de Solicitud de Ejecucion Presupuestaria');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SAFDPR','SAF','DPR','Comprobante de Depreciación de Activos.');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('SEPRPC','SEP','SPR','Reverso Solicitud Ejecucion Presupuestaria al contabilizar OC/OS');
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_procedencia('CXPNOD','CXP','NOD','Cuentas por Pagar Nota de Débito');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_procedencia('CXPNOC','CXP','NOC','Cuentas por Pagar Nota de Crédito');
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_procedencia('SOBASI','SOB','ASI','Obras Asiganación');
	if(!$lb_valido){	return false;}	
	return true;	
}//end function uf_fill_datos_procedencia()

function uf_fill_datos_saf()
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_saf
//	 Returns:  datos insertados
//Description:  Llena por defectos las operaciones de activos
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_saf_edo_conservacion('1','Muy Bueno',' ');
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_edo_conservacion('2','Bueno',' ');		
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_edo_conservacion('3','Regular',' ');		
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_edo_conservacion('4','Malo',' ');		
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_edo_conservacion('9','Muy Malo',' ');		
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_rotulacion('1','De Rótulo flexibles autoadhesivos',' ');	
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_rotulacion('2','Grabación por arenado',' ');				
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_rotulacion('3','Pintado',' ');				
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_rotulacion('4','Rótulo rigido',' ');				
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_rotulacion('5','Herrete',' ');				
	if(!$lb_valido){	return false;}	
	//SIGECOF
	$lb_valido=$this->uf_insert_saf_causas('001','Compras','I',0,1,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('002','Inventario Inicial','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('003','Fabricación o Producción de Materiales y Bienes','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('004','Omisión por Inventario Inicial','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('005','Ingreso Provisional de bienes y materiales provinientes de programas especiales.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('006','Ingreso Definitivos de bienes y materiales provinientes de programas especiales.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('007','Devolución de bienes y materiales robados, hurtados o perdido.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('008','Aparición de Bienes y materiales desincorporados por causas imputables a funcionarios y a empleados.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('009','Nacimiento de Semovientes','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('010','Incremento de Edad de Semovientes','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('011','Donaciones','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('012','Permuta','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('013','Ingreso Provisional de Bienes dado en comodatos','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('014','Ingreso definitivo de bienes datos en comodatos','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('015','Herencia Vacante','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('016','Decomiso de bienes y materiales','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('017','Ingreso Provisional de bienes y materiales bajo guarda judicial.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('018','Ingreso Definitivo de Bienes y materiales que habian sido registrado provisionalmente bajo guarda judicial.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('019','Incorporación de Otros conceptos.','I',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('020','Recepción de Bienes o Materiales procedentes de almacenes de la administracion central.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('021','Recepción de Bienes y Materiales de Otras dependencia del organismo ordenador de compromisos y pago.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('022','Recepción de Bienes y Materiales de Otros Organismos del organismo ordenador de compromisos y pago.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('023','Recepción de Bienes y Materiales  procedentes de otros organismos de la administración pública.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('024','Devolución de Bienes Prestado a Contratistas.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('025','Incorporación por Cambios de Grupo, cuenta y subcuentas.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('026','Correcciones de Desincorporaciones.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('030','Entrega de Bienes o Materiales por parte de almacenes.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('031','Entrega de Bienes o Materiales a otras dependencias del organismo ordenador de compromisos y pagos.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('032','Entrega de Bienes o Materiales a otras organismo ordenador de compromisos y pagos.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('033','Entrega de Bienes o materiales a otros organismos de la Administración Pública Nacional.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('034','Préstamos de bienes a contratistas','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('035','Desincorporación por cambios de grupo, cuentas o cubcuentas','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('036','Correciones de Incorporaciones','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('037','Ajuste de Cambios del método de depreciación.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('038','Otros descargos por reasignaciones.','R',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('040','Error de Incorporación de bienes de materiales.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('041','Pase a situacion de desuso para reasignación, venta o disposición final','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('042','Bienes o Materiales en custodia en el almacen.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('043','Ventas.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('044','Cesiones sin cargos a organismos del sector privado.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('045','Cesiones sin cargos a los entes descentralizados territorialmente.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('046','Perdida de bienes con formulación de cargos.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('047','Robo hurto de bienes o materiales.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('048','Otras perdidas de bienes o materiales no culposas.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('049','Destrucción o incineración de bienes y materiales.','D',1,0,'',1);																																
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('050','Desarme o desmantelamiento de bienes.','D',1,0,'',1);																																		
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('051','Inservibilidad.','D',1,0,'',1);																																				
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('052','Deterioro.','D',1,0,'',1);																																						
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('053','Demolición.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('054','Muerte de semovimiente.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('055','Desincorporación por cambio de edad de semovimiente.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('056','Reclasificación de semovimiente como bienes de cambios.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('057','Desincorporación por permuta.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('058','Desincorporación por donación.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('059','Desincorporación por otros conceptos.','D',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('060','Adiciones a Bienes.','M',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('061','Mejoras a Bienes.','M',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('062','Mayor costo de Bienes.','M',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('063','Reparaciones extraordinarias de los Bienes.','M',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('063','Reparaciones extraordinarias de los Bienes.','M',1,0,'',1);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('064','Correción de Errores.','M',1,0,'',1);
	if(!$lb_valido){	return false;}
    //CONTRALORIA GRAL DE LA REPUBLICA.
	$lb_valido=$this->uf_insert_saf_causas('001','Incorporación por inventario inicial','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('002','Incorporación por traspaso','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('003','Incorporación por compras','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('004','Incorporación por construcción de inmuebles','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('005','Incorporación por adiciones y mejoras','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('006','Incorporación por producción de elementos (muebles)','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('007','Incorporación por suministro de bienes de otras entidades','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('008','Incorporación por devolución de bienes prestados a contratistas','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('009','Incorporación de semovientes','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('010','Incorporación por reconstrucción de equipos','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('011','Incorporación por donación','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('012','Incorporación por permuta','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('013','Incorporación por adscripción de bienes inmuebles','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('014','Incorporación por omisión en inventario','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('016','Incorporación por cambio de subgrupo','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('017','Incorporación por correción de desincorporaciones','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('018','Incorporación por otros conceptos','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('019','Incorporación de muebles procedentes de los almacenes','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('020','Incorporación por herencias vacantes','I','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('051','Desincorporación por traspaso','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('052','Desincorporación por venta','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('053','Desincorporación por préstamos de bienes a contratistas','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('054','Desincorporación por suministros de bienes a otras entidades','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('055','Desincorporación por desarme','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('056','Desincorporación por inservibilidad','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('057','Desincorporación por deterioro','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('058','Desincorporación por demolición','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('059','Desincorporación de semovimientes','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('060','Desincorporación por faltantes por investigar','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('061','Desincorporación por permuta','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('062','Desincorporación por donación','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('063','Desincorporación por adscripción de bienes inmuebles','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('065','Desincorporación por cambio de subgrupo','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('066','Desincorporación por corrección de incorporaciones','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('067','Desincorporación por otros conceptos','D','1','0','',2);
	if(!$lb_valido){	return false;}		
	$lb_valido=$this->uf_insert_saf_causas('080','Ajustes','A','1','0','',2);
	if(!$lb_valido){	return false;}	
	$lb_valido=$this->uf_insert_saf_situacion_contable('1','Bienes Inmuebles','Comprenden los bienes inmuebles patrimoniales del dominio privado de la Nación permanentes o para uso oficial o para cumplir finalidades específicas de servicio público');																		
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_situacion_contable('2','Bienes en depósito','Son aquellos bienes que se encuentran almacenados  y que por su naturaleza, uso o destino');
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_situacion_contable('3','Bienes desafectados de uso','Representan aquellos materiales y suministros que no estén en servicio de presentar avería total, es decir, por carecer de partes aprovechables y de valor de rescate que haga posible su desmantelamiento o venta. No incluye los bienes de uso cuya estructura esté conformada mayormente de elementosmetálicos, tales como vehiculos, sillas, etc. en virtud de que estos siempren tendrán valor de rescate como material de desecho. Tambien se incluyen aquellos materiales y suministros de uso dañados parcialmente, que no tengan partes utilizables, pero si posible valor de salvamento');																		
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_situacion_contable('4','Materiales y suministros para la venta','Comprende los materiales y suministros destinados para la venta que se efectúe conforme a las disposiciones de la Ley Orgánicaque Regula la Enejación de Bienes del Sector Público no Afectos a las Industrias Básicas');																		
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_condicion_compra('01','Costo, seguro y flete (CIF)','Costo por concepto de gastos de seguro y de transporte, el cual debe ser cancelado por el traslado de la mercancia desde el lugar de la venta hasta el lugar de destino de la entidad que efectua la compra');
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_condicion_compra('02','Libre a bordo puerto de embarque (FOB)','Expresion utilizada en el comercio exterior y en los creditos documentarios. Significa que el vendedor debe entregar la mercancia, convenientemente embalada, a bordo de un navío, designado por el comprador, en el puerto de embarque a la fecha o el plazo convenido');																		
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_condicion_compra('03','Libre puerto de embarque (FAS)','Termino condicional empleado en el movimiento de exportacion');																		
	if(!$lb_valido){	return false;}
	$lb_valido=$this->uf_insert_saf_condicion_compra('04','Otros','Otras condiciones de la compra');																		
	if(!$lb_valido){	return false;}
	
	$ls_sql  = "INSERT INTO saf_grupo (codgru, dengru) VALUES ('---', '---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar grupo,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO saf_subgrupo (codgru, codsubgru, densubgru) VALUES ('---', '---', '---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Subgrupo,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}		
	$ls_sql  = "INSERT INTO saf_seccion (codgru, codsubgru, codsec, densec) VALUES ('---', '---', '---', '---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Seccion,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO saf_metodo (codmetdep, denmetdep, formetdep) VALUES ('001', 'Linea Recta', '')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar Método,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	$ls_sql  = "INSERT INTO saf_item (codgru,codsubgru,codsec,codite,denite) VALUES ('---','---','---','---','---seleccione---')"; 	
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		print "Error al insertar SAF ITEM,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	return true;
}//end function uf_fill_datos_saf()

function uf_fill_datos_banco()
{
//////////////////////////////////////////////////////////////////////////////
//	 Function:  uf_fill_datos_banco.
//	  Returns:  datos insertados.
//Description:  Llena por defectos los datos de banco.
/////////////////////////////////////////////////////////////////////////////
	
	$lb_valido=$this->uf_insert_scb_conceptos('---','Ninguno','--');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_scb_tipocta('---','Ninguno');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_scb_banco('0001','---','Ninguno');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_scb_ctabanco('0001','---','-------------------------','---','-------------------------','Ninguno','');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_scb_cartaorden('000','NULL','NULL','NULL','Cheque Voucher','1','0001');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_scb_cartaorden('001', 'Dirigido a:\r\n@banco@\r\nCiudad.\r\n                                     
	                                           "                                             Atencion: @gerente@\r\n                   
	                                           "                                                               Gerente General         
	                                           "    ', '          Nos dirigimos a ustedes en la oportunidad de saludarlos y a la vez solicitarles
	                                           " que transfieran de la Cuenta  @tipocuenta@  No  @cuenta@ a nombre de @empresa@  la cantidad de  
	                                           " @montoletras@  (Bs.  @monto@), a la cuenta que a continuacion se menciona:\r\n\r\n<b>CUENTA 
	                                           " @tipocuenta@ No @cuenta@  </b>\r\n\r\n<b>MONTO TOTAL A TRANSFERIR @monto@</b>\r\n', 'Agradeciendo
	                                           " de antemano su atencion, nos reiteramos de ustedes.\r\n\r\nAtentamente:\r\n\r\nXXXXXXX XXXXXXXX
	                                           " XXXXXXXX XXXXXXX\r\n   Gerente General               
	                                           " Administradora', 'Carta Orden Ejemplo 1','0','0001');
	if(!$lb_valido){return false;}	
    return true;
}//end function uf_fill_datos_banco()

function uf_fill_datos_nomina()
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_fill_datos_nomina
//	 Returns:  datos insertados
//Description:  Llena por defectos los Métodos a Banco.
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0100','SIN METODO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0101','BOD VIEJO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0102','BOD NUEVO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0103','BOD VERSION 3','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0104','CANARIAS','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0105','CARACAS','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0106','CARONI','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0107','V2_CARONI','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0108','CARIBE','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0109','CASA PROPIA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0110','CASA PROPIA 2003','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0111','CENTRAL','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0112','CONFEDERADO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0113','LARA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0114','MERCANTIL','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0115','PROVINCIAL VIEJO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0116','PROVINCIAL NUEVO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0117','PROVINCIAL GUANARE','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0118','e-PROVINCIAL','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0119','e-PROVINCIAL_02','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0120','UNION','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0121','UNIBANCA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0122','UNIBANCA_20_Digitos','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0123','VENEZUELA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0125','INDUSTRIAL','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0126','DEL SUR E.A.P.','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0127','BANESCO','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0128','BANESCO_PAYMUL','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0129','BANFOANDES','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0130','SOFITASA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0131','MI CASA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0132','FONDO COMUN','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0133','EAP_MICASA','0','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0200','SIN METODO','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0201','VIVIENDA','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0202','CASA PROPIA','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0203','MERENAP','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0204','MIRANDA','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0205','FONDO MUTUAL HABITACIONAL','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0206','BANESCO','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0207','MI CASA EAP','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0208','CANARIAS','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0209','VENEZUELA','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0210','DELSUR','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0211','MERCANTIL','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0212','CENTRAL','1','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0300','SIN METODO','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0301','CARIBE','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0302','UNION','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0303','OCEPRE','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0304','MERCANTIL','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0305','VENEZOLANO DE CREDITO','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_metodos_banco('0001','0306','BANCO DE VENEZUELA','2','','','','','','','','','','','','','','','');
	if(!$lb_valido){return false;}	

	$lb_valido=$this->uf_insert_sno_dedicacion('0001','000','Sin dedicación');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_dedicacion('0001','100', 'Personal Fijo Tiempo Completo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_dedicacion('0001','200', 'Personal Fijo Tiempo Parcial');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_dedicacion('0001','300', 'Personal Contratado');
	if(!$lb_valido){return false;}	

	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','000','0000', 'Sin tipo de personal');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0101', 'Directivo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0102', 'Profesional y Técnico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0103', 'Personal Administrativo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0104', 'Personal Docente');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0105', 'Personal de Investigación');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0106', 'Personal Médico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','100', '0107', 'Personal Obrero');
	if(!$lb_valido){return false;}	

	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0201', 'Directivo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0202', 'Profesional y Técnico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0203', 'Personal Administrativo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0204', 'Personal Docente');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0205', 'Personal de Investigación');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0206', 'Personal Médico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','200', '0207', 'Personal Obrero');
	if(!$lb_valido){return false;}	

	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0301', 'Directivo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0302', 'Profesional y Técnico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0303', 'Personal Administrativo');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0304', 'Personal Docente');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0305', 'Personal de Investigación');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0306', 'Personal Médico');
	if(!$lb_valido){return false;}	
	$lb_valido=$this->uf_insert_sno_tipopersonal('0001','300', '0307', 'Personal Obrero');
	if(!$lb_valido){return false;}	
	/*
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','-------','TODOS LOS EMPLEADOS');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000001','EMPLEADO FIJO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000002','EMPLEADO CONTRATADO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000003','EMPLEADO SUPLENTE');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000004','OBRERO FIJO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000005','OBRERO CONTRATADO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000006','OBRERO SUPLENTE');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000007','DOCENTE FIJO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000008','DOCENTE CONTRATADO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000009','DOCENTE SUPLENTE');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000010','JUBILADO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000011','COMISION DE SERVICIOS');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000012','LIBRE NOMBRAMIENTO');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000013','MILITAR');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000014','PENSIONADOS');
	if(!$lb_valido){return false;}
	$lb_valido=$this->uf_insert_sno_tipopersonalsss('0001','0000015','SUPLENTE');
	if(!$lb_valido){return false;}
	return true;	
	*/
	return true;
}//end function uf_fill_datos_nomina()

function uf_insert_spg_operacion($as_operacion,$as_denominacion,$ai_asignar,$ai_aumento,$ai_disminucion,$ai_precomprometer,$ai_comprometer,$ai_causar,$ai_pagar,$ai_reservado)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_spg_operacion
//	 Returns:  
//Description: inserta registro tipo de operacion de gasto.
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM spg_operaciones WHERE operacion='".$as_operacion."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_operacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{      
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		
		}
		else
		{
		   $ls_sql  = "INSERT INTO spg_operaciones(operacion, denominacion, asignar, aumento, disminucion, precomprometer, comprometer, causar, pagar, reservado) ".
						"VALUES ('".$as_operacion."','".$as_denominacion."',".$ai_asignar.",".$ai_aumento.",".$ai_disminucion.",".$ai_precomprometer.",".$ai_comprometer.",".$ai_causar.",".$ai_pagar.",".$ai_reservado.")"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido = false;
			   $this->is_msg_error="ERROR método->uf_insert_operacion_gasto".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	
	return $lb_valido;
}//end function uf_insert_spg_operacion()

function uf_insert_spg_fuente_financiamiento($as_codfuefin,$as_denfuefin,$as_expfuefin)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_spg_fuente_financiamiento
//	 Returns:  
//Description: inserta registro tipo de operacion de gasto.
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;
	$this->is_msg_error = "";
	$ls_codemp          = '0001';
	$ls_sql             = " SELECT * FROM tepuy_fuentefinanciamiento WHERE codemp = '".$ls_codemp."' AND codfuefin='".$as_codfuefin."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_spg_fuente_financiamiento".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{      
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql  = "INSERT INTO tepuy_fuentefinanciamiento(codemp,codfuefin,denfuefin,expfuefin) VALUES ('".$ls_codemp."','".$as_codfuefin."','".$as_denfuefin."','".$as_expfuefin."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido = false;
			   $this->is_msg_error="ERROR método->uf_insert_spg_fuente_financiamiento".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	
	return $lb_valido;
}//end function uf_insert_spg_fuente_financiamiento()

function uf_insert_spg_unidad_administrativa($as_codemp,$as_coduniadm,$as_denuniadm,$as_estemireq,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_spg_fuente_financiamiento
//	 Returns:  
//Description: inserta Unidad Administrativa por defecto.
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM spg_unidadadministrativa WHERE codemp = '".$as_codemp."' AND coduniadm='".$as_coduniadm."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_spg_fuente_financiamiento".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
 	   }
	else
	   {      
		if (!$row=$this->io_sql->fetch_row($rs_data))
		   {
		     $ls_sql  = "INSERT INTO spg_unidadadministrativa (codemp, coduniadm, denuniadm, estemireq, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5) VALUES ('".$as_codemp."','".$as_coduniadm."','".$as_denuniadm."','".$as_estemireq."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."')"; 	
		     $rs_data = $this->io_sql->execute($ls_sql);
		     if ($rs_data===false)
		        {
			      $lb_valido = false;
			      $this->is_msg_error="ERROR método->uf_insert_spg_unidad_administrativa".$this->io_function->uf_convertirmsg($this->io_sql->message);
			      print $this->io_sql->message;
		        }
		    }
		 else
		    {
		      $this->io_sql->free_result($rs_data);	
			}
	   }
	return $lb_valido;
}//end function uf_insert_spg_unidad_administrativa();

function uf_insert_spi_operacion($as_operacion,$as_denominacion,$ai_previsto,$ai_aumento,$ai_disminucion,$ai_devengado,$ai_cobrado,$ai_cobrado_ant,$ai_reservado)
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_spi_operacion
//	 Returns:  
//Description: inserta registro tipo de operacion de Ingresos.
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM spi_operaciones WHERE operacion='".$as_operacion."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_operacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		
		 $ls_sql  = "INSERT INTO spi_operaciones(operacion, denominacion,previsto,aumento,disminucion,devengado,cobrado,cobrado_ant,reservado) ".
						"VALUES ('".$as_operacion."','".$as_denominacion."',".$ai_previsto.",".$ai_aumento.",".$ai_disminucion.",".$ai_devengado.",".$ai_cobrado.",".$ai_cobrado_ant.",".$ai_reservado.")"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			  $lb_valido=false;
			  $this->is_msg_error="Error en insert spi_operacion,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			  print $this->io_sql->message;
		   }
		   else
		   {
			 $lb_valido=true;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_spi_operacion()
	
function uf_insert_sss_sistema($as_codigo,$as_denominacion)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_sss_sistema
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM sss_sistemas WHERE codsis='".$as_codigo."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_sistemas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql    = "INSERT INTO sss_sistemas (codsis,nomsis) VALUES ('".$as_codigo."','".$as_denominacion."')"; 	
		   $li_result = $this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_insert_sistemas".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_sss_sistema()

function uf_insert_sss_eventos($as_codigo,$as_denominacion)
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_sss_eventos
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM sss_eventos WHERE evento='".$as_codigo."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_evento".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if(!$row=$this->io_sql->fetch_row($rs_data))
		{
		   $ls_sql  = "INSERT INTO sss_eventos (evento,deseve) ".
						"VALUES ('".$as_codigo."','".$as_denominacion."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_insert_eventos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_spi_eventos()

function uf_insert_procedencia($as_procede,$as_codsis,$as_opeproc,$as_desproc)
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_procedencias
//	 Returns:  
//Description: inserta las procedencias de sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM tepuy_procedencias WHERE procede='".$as_procede."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	$this->is_msg_error="";
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_procedencia".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql  = "INSERT INTO tepuy_procedencias (procede,codsis,opeproc,desproc) ".
						"VALUES ('".$as_procede."','".$as_codsis."','".$as_opeproc."','".$as_desproc."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido = false;
			   $this->is_msg_error="ERROR método->uf_insert_procedencias".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_procedencia()

function uf_insert_saf_condicion_compra($as_codconcom,$as_denconcom,$as_expconcom)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_saf_condicion_compra
//	 Returns:  
//Description: inserta el estado de conservacion del inmueble
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM saf_condicioncompra WHERE codconcom = '".$as_codconcom."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   { // error interno sql
	     $lb_valido          = false;
		 $this->is_msg_error = "ERROR método->uf_insert_procedencia".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
	   }
	else
	   {                 
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
		    }
		else
		    {
		      $ls_sql    = "INSERT INTO saf_condicioncompra  (codconcom, denconcom, expconcom) ".
						   "VALUES ('".$as_codconcom."','".$as_denconcom."','".$as_expconcom."')"; 	
		      $li_result = $this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_saf_condicion_compra".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		    }
		$this->io_sql->free_result($rs_data);
	}
return $lb_valido;
}//end function uf_insert_saf_condicion_compra del bien()

function uf_insert_saf_situacion_contable($as_codsitcon,$as_densitcon,$as_expsitcon)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_saf_situacion_contable
//	 Returns:  
//Description: inserta el estado de conservacion del inmueble
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM saf_situacioncontable WHERE codsitcon = '".$as_codsitcon."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   { // error interno sql
	     $lb_valido          = false;
		 $this->is_msg_error = "ERROR método->uf_insert_saf_situacion_contable".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
	   }
	else
	   {                 
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
		    }
		else
		    {
		      $ls_sql    = "INSERT INTO saf_situacioncontable (codsitcon, densitcon, expsitcon) ".
						   "VALUES ('".$as_codsitcon."','".$as_densitcon."','".$as_expsitcon."')"; 	
		      $li_result = $this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_saf_situacion_contable".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		    }
		$this->io_sql->free_result($rs_data);
	}
return $lb_valido;
}//end function uf_insert_saf_situacion_contable().

function uf_insert_saf_causas($as_codcau,$as_dencau,$as_tipcau,$ai_estafecon,$ai_estafepre,$as_expcau,$as_estcat)
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_saf_causas
//	 Returns:  
//Description: inserta el estado de conservacion del inmueble
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;	
	$this->is_msg_error="";	
	$ls_sql  = " SELECT codcau 
	               FROM saf_causas 
				  WHERE codcau = '".$as_codcau."' 
				    AND estcat = '".$as_estcat."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_causas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql  = "INSERT INTO saf_causas (codcau,dencau,tipcau,estafecon,estafepre,expcau,estcat) ".
						"VALUES ('".$as_codcau."','".$as_dencau."','".$as_tipcau."',".$ai_estafecon.",".$ai_estafepre.",'".$as_expcau."',".$as_estcat.")"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido = false;
			   $this->is_msg_error="ERROR método->uf_insert_causas".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
			   
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_saf_causas()

function uf_insert_saf_edo_conservacion($as_codconbie,$as_denconbie,$as_desconbie)
{  
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_saf_edo_conservacion
//	 Returns:  
//Description: inserta el estado de conservacion del inmueble
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$this->is_msg_error="";
	$ls_sql  = " SELECT * FROM saf_conservacionbien WHERE codconbie='".$as_codconbie."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_procedencia".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql  = "INSERT INTO saf_conservacionbien (codconbie,denconbie,desconbie) ".
						"VALUES ('".$as_codconbie."','".$as_denconbie."','".$as_desconbie."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_insert_conservacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_saf_edo_conservacion()

function uf_insert_saf_rotulacion($as_codconbie,$as_denconbie,$as_desconbie)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_saf_edo_rotulacion
//	 Returns:  
//Description: inserta el estado de conservacion del inmueble
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;		
	$this->is_msg_error="";
	$ls_sql  = " SELECT * FROM saf_rotulacion WHERE codrot='".$as_codconbie."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_procedencia".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql  = "INSERT INTO saf_rotulacion (codrot,denrot,emprot) ".
						"VALUES ('".$as_codconbie."','".$as_denconbie."','".$as_desconbie."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_saf_rotulacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }

		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_saf_rotulacion()

function uf_insert_scb_tipocta($as_codtipcue,$as_dentipcue)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_scb_banco
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM scb_tipocuenta WHERE codtipcta='".$as_codtipcue."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {   
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_evento".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {                  
	     if (!$row=$this->io_sql->fetch_row($rs_data))
	  	    {
		      $ls_sql = "INSERT INTO scb_tipocuenta (codtipcta, nomtipcta) VALUES ('".$as_codtipcue."','".$as_dentipcue."')"; 	
		      $li_result=$this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_eventos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_scb_tipocta()

function uf_insert_scb_conceptos($as_codigo,$as_denominacion,$as_codope)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_scb_conceptos
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM scb_concepto WHERE codconmov='".$as_codigo."' ";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{  
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_evento".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{                 
		if(!$row=$this->io_sql->fetch_row($rs_data))
		{
		   $ls_sql  = "INSERT INTO scb_concepto (codconmov,denconmov,codope) ".
						"VALUES ('".$as_codigo."','".$as_denominacion."','".$as_codope."')"; 	
		   $li_result=$this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_insert_eventos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_scb_conceptos()

function uf_insert_scb_banco($as_codemp,$as_codban,$as_nomban)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_scb_banco
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM scb_banco WHERE codemp='".$as_codemp."' AND codban='".$as_codban."' ";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {   
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_evento".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {                  
	     if (!$row=$this->io_sql->fetch_row($rs_data))
	  	    {
		      $ls_sql = "INSERT INTO scb_banco (codemp,codban,nomban) VALUES ('".$as_codemp."','".$as_codban."','".$as_nomban."')"; 	
		      $li_result=$this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_eventos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_scb_banco()

function uf_insert_scb_ctabanco($as_codemp,$as_codban,$as_ctaban,$as_codtipcta,$as_ctabanext,$as_dencta,$as_sccuenta)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_scb_ctabanco
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {   
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_evento".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {                  
	     if (!$row=$this->io_sql->fetch_row($rs_data))
	  	    {
		      $ls_sql = "INSERT INTO scb_ctabanco (codemp,codban,ctaban,codtipcta,ctabanext,dencta,sc_cuenta) VALUES ('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_codtipcta."','".$as_ctabanext."','".$as_dencta."','".$as_sccuenta."')"; 	
		      $li_result=$this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_eventos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_scb_ctabanco()('000','NULL','NULL','NULL','Cheque Voucher','1','0001');

function uf_insert_scb_cartaorden($as_codban,$as_enccar,$as_cuecar,$as_piecar,$as_nomcar,$as_estcar,$as_codemp)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_scb_ctabanco
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM scb_cartaorden WHERE codemp='".$as_codemp."' AND codigo='".$as_codban."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {   
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_scb_cartaorden".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {                  
	     if (!$row=$this->io_sql->fetch_row($rs_data))
	  	    {
		      $ls_sql = "INSERT INTO scb_cartaorden (codigo,encabezado,cuerpo,pie,nombre,status,codemp) VALUES ('".$as_codban."','".$as_enccar."','".$as_cuecar."','".$as_piecar."','".$as_nomcar."','".$as_estcar."','".$as_codemp."')"; 	
		      $li_result=$this->io_sql->execute($ls_sql);
		      if ($li_result===false)
		         {
			       $lb_valido=false;
			       $this->is_msg_error="ERROR método->uf_insert_scb_cartaorden".$this->io_function->uf_convertirmsg($this->io_sql->message);
			       print $this->io_sql->message;
		         }
		    }
		 $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;
}//end function uf_insert_scb_ctabanco()
	
function uf_insert_sno_metodos_banco($as_codemp,$as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_tipcuecrenom,$as_tipcuedebnom,$as_numplalph,$as_numconlph,$as_suclph,$as_cuelph,$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,$as_numofifps,$as_numfonfps,$as_confps,$as_nroplafps)
{   
//////////////////////////////////////////////////////////////////////////////
//	 Function:  uf_insert_sno_metodos_banco
//	  Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM sno_metodobanco WHERE codemp='".$as_codemp."' AND codmet='".$as_codmet."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_sno_metodos_banco".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
	   }
	else
	   {                 
		 if (!$row=$this->io_sql->fetch_row($rs_data))
			{
			  $ls_sql   = "INSERT INTO sno_metodobanco (codemp, codmet, desmet, tipmet, codempnom, tipcuecrenom, tipcuedebnom, numplalph, numconlph, suclph, cuelph, grulph, subgrulph, conlph, numactlph, numofifps, numfonfps, confps, nroplafps) ".
						  "     VALUES ('".$as_codemp."','".$as_codmet."','".$as_desmet."','".$as_tipmet."','".$as_codempnom."','".$as_tipcuecrenom."','".$as_tipcuedebnom."','".$as_numplalph."','".$as_numconlph."','".$as_suclph."','".$as_cuelph."','".$as_grulph."','".$as_subgrulph."','".$as_conlph."','".$as_numactlph."','".$as_numofifps."','".$as_numfonfps."','".$as_confps."','".$as_nroplafps."')"; 	
			  $li_result=$this->io_sql->execute($ls_sql);
			  if ($li_result===false)
				 {
				   $lb_valido=false;
				   $this->is_msg_error="ERROR método->uf_insert_sno_metodos_banco".$this->io_function->uf_convertirmsg($this->io_sql->message);
				   print $this->io_sql->message;
				 }
			}
		 $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;
}//end function uf_insert_sno_metodos_banco().

function uf_insert_sno_dedicacion($as_codemp,$as_codded,$as_desded)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_sss_eventos
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT * FROM sno_dedicacion WHERE codemp='".$as_codemp."' AND codded='".$as_codded."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_sno_dedicacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
	   }
	else
	   {                 
		 if (!$row=$this->io_sql->fetch_row($rs_data))
			{
			  $ls_sql   = "INSERT INTO sno_dedicacion (codemp, codded, desded) VALUES ('".$as_codemp."', '".$as_codded."', '".$as_desded."')"; 	
			  $li_result=$this->io_sql->execute($ls_sql);
			  if ($li_result===false)
				 {
				   $lb_valido=false;
				   $this->is_msg_error="ERROR método->uf_insert_sno_dedicacion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
				   print $this->io_sql->message;
				 }
			}
		 $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;
}//end function uf_insert_sno_dedicacion().
	
function uf_insert_sno_tipopersonal($as_codemp,$as_codded,$as_codtipper,$as_destipper)
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_sss_eventos
//	 Returns:  
//Description: inserta los sistemas
/////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;		
	$this->is_msg_error="";
	$ls_sql  = " SELECT * FROM sno_tipopersonal WHERE codemp='".$as_codemp."' AND codded='".$as_codded."' AND codtipper='".$as_codtipper."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->is_msg_error="ERROR método->uf_insert_sno_tipopersonal".$this->io_function->uf_convertirmsg($this->io_sql->message);
		 print $this->is_msg_error;
	   }
	else
	   {                 
		 if (!$row=$this->io_sql->fetch_row($rs_data))
			{
			  $ls_sql   = "INSERT INTO sno_tipopersonal (codemp, codded, codtipper, destipper) ".
						  "     VALUES ('".$as_codemp."','".$as_codded."','".$as_codtipper."','".$as_destipper."')"; 	
			  $li_result=$this->io_sql->execute($ls_sql);
			  if ($li_result===false)
				 {
				   $lb_valido=false;
				   $this->is_msg_error="ERROR método->uf_insert_sno_tipopersonal".$this->io_function->uf_convertirmsg($this->io_sql->message);
				   print $this->io_sql->message;
				 }
			}
		 $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;
}//end function uf_insert_sno_tipopersonal().

/*function uf_insert_sno_tipopersonalsss($as_codemp,$as_codigo,$as_denominacion)
{   
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_insert_sno_tipopersonalsss
//	 Returns:  
//Description: inserta los tipo de personal
/////////////////////////////////////////////////////////////////////////////
	$lb_valido          = true;		
	$this->is_msg_error = "";
	$ls_sql             = " SELECT codemp FROM sno_tipopersonalsss WHERE  codemp='".$as_codemp."' AND  codtippersss='".$as_codigo."'";
	$rs_data            = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$lb_valido = false;
		$this->is_msg_error="ERROR método->uf_insert_tipopersonalsss".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{                 
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		}
		else
		{
		   $ls_sql    = "INSERT INTO sno_tipopersonalsss (codemp,codtippersss,dentippersss) VALUES ('".$as_codemp."','".$as_codigo."','".$as_denominacion."')"; 	
		   $li_result = $this->io_sql->execute($ls_sql);
		   if($li_result===false)
		   {
			   $lb_valido=false;
			   $this->is_msg_error="ERROR método->uf_insert_tipopersonalsss".$this->io_function->uf_convertirmsg($this->io_sql->message);
			   print $this->io_sql->message;
		   }
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}//end function uf_insert_sss_sistema()*/
}
?>
