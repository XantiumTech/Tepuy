stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);
stm_ai("p0i0",[0," Sistemas ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
//SEP
stm_aix("p1i0","p0i0",[0,"Solicitud de Ejecuci�n Presupuestaria","","",-1,-1,0,"","","","","../shared/imagebank/iconos/presupuestaria.gif","../shared/imagebank/iconos/presupuestaria.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
	stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_sep.php","_self"]);
	stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_sep.php","_self"]);
	stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]); 
	stm_aix("p3i0","p1i0",[0," Anular ","","",-1,-1,0,"tepuy_mis_p_anula_sep.php","_self"]);
stm_ep();
//SOC
stm_aix("p1i4","p1i0",[0,"Compras","","",-1,-1,0,"","","","","../shared/imagebank/iconos/compras.gif","../shared/imagebank/iconos/compras.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0,"Comprometer","","",-1,-1,0,"","","","","../shared/imagebank/iconos/compras.gif","../shared/imagebank/iconos/compras.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
 	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_soc.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_soc.php","_self"]);
		stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]); 
		stm_aix("p3i0","p1i0",[0," Anular ","","",-1,-1,0,"tepuy_mis_p_anula_soc.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Elimina Anulaci�n ","","",-1,-1,0,"tepuy_mis_p_reverso_anula_soc.php","_self"]);
 	stm_ep(); 
stm_ep();
//CXP
stm_aix("p1i4","p1i0",[0,"Cuentas por Pagar","","",-1,-1,0,"","","","","../shared/imagebank/iconos/pagar.gif","../shared/imagebank/iconos/pagar.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0,"Solicitudes","","",-1,-1,0,"","","","","../shared/imagebank/iconos/pagar.gif","../shared/imagebank/iconos/pagar.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_cxp.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_cxp.php","_self"]);
		stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);  
		stm_aix("p3i0","p1i0",[0," Anular","","",-1,-1,0,"tepuy_mis_p_anula_cxp.php","_self"]);  
		stm_aix("p3i0","p1i0",[0," Elimina Anulaci�n","","",-1,-1,0,"tepuy_mis_p_reverso_anula_cxp.php","_self"]);  
	stm_ep();
	stm_aix("p1i4","p1i0",[0,"Notas de D�bitos y Cr�ditos","","",-1,-1,0,"","","","","../shared/imagebank/iconos/pagar.gif","../shared/imagebank/iconos/pagar.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_ncd.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_ncd.php","_self"]);
	stm_ep();
stm_ep();
// SCB
stm_aix("p1i4","p1i0",[0," Caja y Bancos ","","",-1,-1,0,"","","","","../shared/imagebank/iconos/banco.gif","../shared/imagebank/iconos/banco.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0," Movimiento de Banco ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar ","","",-1,-1,0,"tepuy_mis_p_contabiliza_scb.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_scb.php","_self"]);
		stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);  
		stm_aix("p3i0","p1i0",[0," Anular","","",-1,-1,0,"tepuy_mis_p_anula_scb.php","_self"]); 
		stm_aix("p3i0","p1i0",[0," Elimina Anulaci�n","","",-1,-1,0,"tepuy_mis_p_reverso_anula_scb.php","_self"]);  
	stm_ep();  
	stm_aix("p1i4","p1i0",[0," Colocaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_scbcol.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_scbcol.php","_self"]);
	stm_ep();  
	stm_aix("p1i4","p1i0",[0," Orden de Pago ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_scbop.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_scbop.php","_self"]);
	stm_ep();  
stm_ep();  
// SOB
stm_aix("p1i4","p1i0",[0," Obras ","","",-1,-1,0,"","","","","../shared/imagebank/iconos/obras.gif","../shared/imagebank/iconos/obras.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0," Asignaci�n ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_asignacion_sob.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_asignacion_sob.php","_self"]);
		stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);   
		stm_aix("p3i0","p1i0",[0," Anular ","","",-1,-1,0,"tepuy_mis_p_anula_asignacion_sob.php","_self"]); 
		stm_aix("p3i0","p1i0",[0," Reversar Anulaci�n ","","",-1,-1,0,"tepuy_mis_p_revanula_asignacion_sob.php","_self"]); 
	stm_ep();  
	stm_aix("p1i4","p1i0",[0," Contratos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_contrato_sob.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_contrato_sob.php","_self"]);
		stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);   
		stm_aix("p3i0","p1i0",[0," Anular ","","",-1,-1,0,"tepuy_mis_p_anula_contrato_sob.php","_self"]); 
		stm_aix("p3i0","p1i0",[0," Reversar Anulaci�n ","","",-1,-1,0,"tepuy_mis_p_revanula_contrato_sob.php","_self"]); 
	stm_ep();   
/* stm_aix("p3i0","p1i0",[0," Anticipos","","",-1,-1,0,"","_self"]);
 stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
 stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_anticipo_sob.php","_self"]);
 stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_anticipo_sob.php","_self"]);
 stm_ep();   
 stm_aix("p3i0","p1i0",[0," Valuaciones","","",-1,-1,0,"","_self"]);
 stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
 stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_valuacion_sob.php","_self"]);
 stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_valuacion_sob.php","_self"]);
 stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);   
 stm_aix("p3i0","p1i0",[0," Anular ","","",-1,-1,0,"tepuy_mis_p_anula_valuacion_sob.php","_self"]); 
 stm_aix("p3i0","p1i0",[0," Reversar Anulaci�n ","","",-1,-1,0,"tepuy_mis_p_revanula_valuacion_sob.php","_self"]); 
 stm_ep();  */
 stm_ep();   
// SNO
stm_aix("p1i4","p1i0",[0," N�mina ","","",-1,-1,0,"","","","","../shared/imagebank/iconos/nomina.gif","../shared/imagebank/iconos/nomina.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
	stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_sno.php","_self"]); 
	stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_sno.php","_self"]);
stm_ep();  
// SAF
stm_aix("p1i4","p1i0",[0," Activos Fijos ","","",-1,-1,0,"","","","","../shared/imagebank/iconos/activo_fijo.png","../shared/imagebank/iconos/activo_fijo.png",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0," Depreciaci�n ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0," Contabilizar ","","",-1,-1,0,"tepuy_mis_p_contabiliza_depreciacion_saf.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_depreciacion_saf.php","_self"]);
	stm_ep();  
stm_ep();  
// SPG
stm_aix("p1i4","p1i0",[0,"Contabilidad Presupuestaria de Gasto","","",-1,-1,0,"","","","","../shared/imagebank/iconos/gastos.gif","../shared/imagebank/iconos/gastos.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
	stm_aix("p3i0","p1i0",[0," Aprobaci�n de Modificaci�n Presupuestaria","","",-1,-1,0,"tepuy_mis_p_contabiliza_mp.php","_self"]);
	stm_aix("p3i0","p1i0",[0," Reversar Aprobaci�n de Modificaci�n Presupuestaria","","",-1,-1,0,"tepuy_mis_p_reversa_mp.php","_self"]);
stm_ep(); 
// SPI
stm_aix("p1i4","p1i0",[0,"Contabilidad Presupuestaria de Ingreso","","",-1,-1,0,"","","","","../shared/imagebank/iconos/ingresos.gif","../shared/imagebank/iconos/ingresos.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
	stm_aix("p3i0","p1i0",[0," Aprobaci�n de Modificaci�n Presupuestaria","","",-1,-1,0,"tepuy_mis_p_contabiliza_mp_spi.php","_self"]);
	stm_aix("p3i0","p1i0",[0," Reversar Aprobaci�n de Modificaci�n Presupuestaria","","",-1,-1,0,"tepuy_mis_p_reverso_mp_spi.php","_self"]);
stm_ep(); 
//SIV
stm_aix("p1i4","p1i0",[0,"Inventario","","",-1,-1,0,"","","","","../shared/imagebank/iconos/inventario.gif","../shared/imagebank/iconos/inventario.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
	stm_aix("p1i4","p1i0",[0,"Despacho","","",-1,-1,0,"","","","","../shared/imagebank/iconos/inventario.gif","../shared/imagebank/iconos/inventario.gif",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
 	stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
		stm_aix("p3i0","p1i0",[0," Contabilizar","","",-1,-1,0,"tepuy_mis_p_contabiliza_siv.php","_self"]);
		stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"tepuy_mis_p_reverso_siv.php","_self"]);
 	stm_ep(); 
stm_ep();
stm_ep(); 
stm_ep();
// Men� Principal - Mantenimiento
stm_aix("p0i5","p0i0",[0," Mantenimiento "]);
stm_bpx("p7","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i4","p1i0",[0," Configuraci�n ","","",-1,-1,0,"tepuy_mis_p_configuracion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
// Men� Principal - Ayuda
stm_aix("p0i8","p0i0",[0," Ayuda "]);
stm_bpx("p10","p1",[]);
stm_ep();
// M�dulos
stm_aix("p4i0","p1i0",[0," Ir a M�dulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();
stm_em();